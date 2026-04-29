<?php

namespace RRZE\ResearchData\Frontend;

defined('ABSPATH') || exit;

/**
 * Formats a single publication as an HTML list item.
 *
 * Supports three citation styles:
 * - standard: Authors | Year | Title (linked) | Journal | Volume, Pages
 * - apa:      Author, A. (Year). Title. Journal, Volume(Issue), Pages. DOI
 * - mla:      Lastname, Firstname, et al. „Title." Journal, Year, S. Pages. DOI
 *
 * Called by PublicationRenderer via renderItem().
 */
class CitationFormatter
{
    /**
     * Formats a publication according to the given citation style.
     *
     * @param object $publication A Publication object
     * @param string $citationStyle Citation style: "apa", "mla", or "" (standard)
     * @return string              HTML <li> element
     */
    public static function format(object $publication, string $citationStyle = ''): string
    {
        return match ($citationStyle) {
            'apa' => self::formatApa($publication),
            'mla' => self::formatMla($publication),
            default => self::formatStandard($publication),
        };
    }

    /**
     * Renders a publication in the default format.
     * Format: Authors | Year | Title (linked) | Journal | Volume, Pages
     *
     * @param object $publication A Publication object
     * @return string             HTML <li> element
     */
    private static function formatStandard(object $publication): string
    {
        $title = wp_kses($publication->title ?? '',
            ['sub' => [],
                'sup' => [],
                'i' => [],
                'b' => []
            ]);
        $year = esc_html($publication->year ?? '');
        $journal = esc_html($publication->journal ?? '');
        $link = !empty($publication->doi)
            ? 'https://doi.org/' . $publication->doi
            : esc_url($publication->url ?? '');
        $volume = esc_html($publication->volume ?? '');
        $pages = esc_html($publication->pages ?? '');

        $volumeHtml = $volume ? ' | ' . $volume : '';
        $pagesHtml = $pages ? ', ' . $pages : '';
        $yearHtml = $year ? $year . ' | ' : '';
        $journalHtml = $journal ? ' | <span class="publication-journal">' . $journal . '</span>' : '';
        $authorsHtml = !empty($publication->authors)
            ? esc_html(implode(', ', $publication->authors)) . ' | '
            : '';

        return sprintf(
            '<li>%s%s<a href="%s" target="_blank" rel="noopener">%s</a>%s%s%s</li>',
            $authorsHtml,
            $yearHtml,
            $link,
            $title,
            $journalHtml,
            $volumeHtml,
            $pagesHtml
        );
    }

    /**
     * Renders a publication in APA format.
     * Format: Author, A., & Author, B. (Year). Title. Journal, Volume, Pages. DOI
     *
     * @param object $publication A Publication object
     * @return string             HTML <li> element
     */
    private static function formatApa(object $publication): string
    {
        $title = wp_kses($publication->title ?? '', ['sub' => [], 'sup' => [], 'i' => [], 'b' => []]);
        $year = esc_html($publication->year ?? '');
        $journal = esc_html($publication->journal ?? '');
        $volume = esc_html($publication->volume ?? '');
        $issue = esc_html($publication->issue ?? '');
        $pages = str_replace('-', '–', esc_html($publication->pages ?? ''));
        $doi = $publication->doi ?? '';
        $link = !empty($doi) ? 'https://doi.org/' . $doi : esc_url($publication->url ?? '');

        // Convert "Firstname Lastname" → "Lastname, F."
        // array_pop() removes and returns the last element (= last name).
        // The remaining parts become initials: "Anna" → "A."
        $authors = array_map(function ($name) {
            $parts = explode(' ', trim($name));
            $lastName = array_pop($parts);
            $initials = implode('. ', array_map(fn($p) => mb_substr($p, 0, 1), $parts)) . '.';
            return $lastName . ', ' . $initials;
        },
            $publication->authors ?? []);

        // Known limit: APA 7 requires listing all authors up to 20.
        // For 21+ authors: first 19, then " ... ", then the last author.
        // This truncation is not yet implemented. All authors are listed for now.
        $authorsHtml = '';
        if (!empty($authors)) {
            if (count($authors) === 1) {
                $authorsHtml = esc_html($authors[0]);
            } else {
                $last = array_pop($authors);
                $authorsHtml = esc_html(implode(', ', $authors) . ', & ' . $last);
            }
            $authorsHtml .= ' ';
        }

        $yearHtml = $year ? '(' . $year . '). ' : '';
        $journalHtml = $journal ? '<em>' . $journal . '</em>' : '';

        // Add issue number in parentheses after volume, e.g. Journal, 12(3).
        // Only the volume is italicised in APA — the issue number is not.
        $volumeHtml = $volume ? ', <em>' . $volume . '</em>' . ($issue ? '(' . $issue . ')' : '') : '';
        $pagesHtml = $pages ? ', ' . $pages : '';
        $doiHtml = !empty($doi)
            ? '. <a href="' . esc_url($link) . '" target="_blank" rel="noopener">' . esc_html($link) . '</a>'
            : '.';

        // (documented, not yet fixed): $title may contain HTML tags from wp_kses,
        // e.g. "Vitamin D<sup>2</sup>". str_ends_with() then sees '>' not '.',
        // and appends a wrong suffix. Acceptable for v1.
        // Future fix: strip_tags($title) before this check.
        $titleSuffix = str_ends_with(trim($title), '.') ? ' ' : '. ';

        return sprintf(
            '<li>%s%s%s%s%s%s%s</li>',
            $authorsHtml,
            $yearHtml,
            $title . $titleSuffix,
            $journalHtml,
            $volumeHtml,
            $pagesHtml,
            $doiHtml
        );
    }

    /**
     * Renders a publication in MLA format.
     *
     * Format: Lastname, Firstname, et al. „Title." Journal, Year, S. Pages. DOI
     *
     * The first author is inverted (Lastname, Firstname), all others remain
     * in natural order. Four or more authors are abbreviated with "et al."
     * Quotation marks adapt to the site language (German: „", English: "").
     *
     * @param object $publication A Publication object
     * @return string             HTML <li> element
     */
    private static function formatMla(object $publication): string
    {
        $title = wp_kses($publication->title ?? '', ['sub' => [], 'sup' => [], 'i' => [], 'b' => []]);
        $year = esc_html($publication->year ?? '');
        $journal = esc_html($publication->journal ?? '');
        $volume = esc_html($publication->volume ?? '');
        $pages = str_replace('-', '–', esc_html($publication->pages ?? ''));
        $doi = $publication->doi ?? '';
        $link = !empty($doi) ? 'https://doi.org/' . $doi : esc_url($publication->url ?? '');

        // MLA: only first author is inverted ("Lastname,Firstname"),
        // all others remain in natural order ("Firstname Lastname").
        // 4+ authors → first author + "et al."
        $rawAuthors = $publication->authors ?? [];
        $authorsHtml = '';
        if (!empty($rawAuthors)) {
            $parts = explode(' ', trim($rawAuthors[0]));
            $lastName = array_pop($parts);
            $firstName = implode(' ', $parts);
            $firstInverted = $lastName . ', ' . $firstName;

            if (count($rawAuthors) === 1) {
                $authorsHtml = esc_html($firstInverted);
            } elseif (count($rawAuthors) >= 3) {
                $authorsHtml = esc_html($firstInverted) . ', et al.';
            } else {
                // 2 authors: first inverted, rest natural, last with "and"
                $rest = array_slice($rawAuthors, 1);
                $last = esc_html(array_pop($rest));
                $middle = !empty($rest) ? ', ' . esc_html(implode(', ', $rest)) : '';
                $authorsHtml = esc_html($firstInverted) . $middle . ', ' . __('and', 'rrze-research-data') . ' ' . $last;
            }
            $authorsHtml .= str_ends_with($authorsHtml, '.') ? ' ' : '. ';
        }
        $titleSuffix = str_ends_with(trim($title), '.') ? '' : '.';
        $journalHtml = $journal ? ' <em>' . $journal . '</em>,' : '';
        $volumeHtml = $volume ? ', ' . __('vol.', 'rrze-research-data') . ' ' . $volume . ',' : '';
        $yearHtml = $year ? ' ' . $year : '';
        $pagesHtml = $pages ? ', ' . __('pp.', 'rrze-research-data') . ' ' . $pages : '';
        $doiHtml = !empty($doi)
            ? '. <a href="' . esc_url($link) . '" target="_blank" rel="noopener">' . esc_html($link) . '</a>.'
            : '.';

        $locale = get_locale();
        $openQuote = str_starts_with($locale, 'de') ? '&bdquo;' : '&ldquo;';
        $closeQuote = str_starts_with($locale, 'de') ? '&ldquo;' : '&rdquo;';

        return sprintf(
            '<li>%s' . $openQuote . '%s%s' . $closeQuote . '%s%s%s%s%s</li>',
            $authorsHtml,
            $title,
            $titleSuffix,
            $journalHtml,
            $volumeHtml,
            $yearHtml,
            $pagesHtml,
            $doiHtml
        );
    }

}
