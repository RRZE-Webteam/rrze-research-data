<?php

namespace RRZE\ResearchData\Frontend;


use RRZE\ResearchData\Services\ResearchService;

defined('ABSPATH') || exit;

/**
 * Renders publication data as HTML for the Research Data block.
 *
 * Receives a prepared list of Publication objects from ResearchService
 * and outputs them as an unordered list with optional JSON-LD markup.
 */
class PublicationRenderer
{
    /**
     * Main render callback – extracts block attributes and delegates to renderList()and renderJsonLD()
     *
     * @param array $attributes Block attributes from the editor
     * @return string            Rendered HTML output
     */
    public static function render(array $attributes = []): string
    {
        $authorId = $attributes['authorId'] ?? '';
        $limit = $attributes['limit'] ?? 10;
        $source = $attributes['source'] ?? '';
        $yearFrom = (int)($attributes['yearFrom'] ?? 0);
        $yearTo   = (int)($attributes['yearTo']   ?? 0);
        $type = $attributes['type'] ?? [];


        $service = new ResearchService();
        $publications = $service->preparePublications($authorId, $limit, $source, $yearFrom, $yearTo, $type);

        if (is_wp_error($publications)) {
            return '<p>' . esc_html($publications->get_error_message()) . '</p>';
        }

        if (empty ($publications)) {
            return '<p>' . esc_html__('No publications found.', 'rrze-research-data') . '</p>';
        }

        return self::renderJsonLd($publications) . self::renderList($publications);

    }


    /**
     * Renders publications as an unordered list.
     *
     * @param array $publications Array of Publication objects
     * @return string              Rendered HTML
     */
    private static function renderList(array $publications): string
    {
        if (empty($publications)) {
            return '<p>' . esc_html__('No files found.', 'rrze-research-data') . '</p>';
        }

        $html = '<ul class="wp-block-list wp-block-research-list">';

        foreach ($publications as $publication) {
            $title = wp_kses($publication->title ?? '', [
                'sub' => [],
                'sup' => [],
                'i' => [],
                'b' => [],
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
            $journalHtml = $journal ? ' | <span class="publication-journal">' .
                $journal . '</span>' : '';

            $authorsHtml = !empty($publication->authors) ? esc_html(implode(', ', $publication->authors)) . ' | ' : '';

            $html .= sprintf(
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

        $html .= '</ul>';

        return $html;
    }

    /**
     * Generates a schema.org JSON-LD script tag for the given publications.
     *
     * Outputs structured data as a @graph array of ScholarlyArticle entries,
     * which helps search engines understand and index the publications.
     *
     * @param array $publications Array of Publication objects
     * @return string             HTML <script> tag with JSON-LD markup
     * @see https://schema.org/ScholarlyArticle
     */
    private static function renderJsonLd(array $publications): string
    {
        $items = [];

        foreach ($publications as $pub) {
            $item = [
                '@type' => 'ScholarlyArticle',
                'name' => $pub->title ?? '',
                'datePublished' => (string)($pub->year ?? ''),
                'url' => !empty($pub->doi) ? 'https://doi.org/' . $pub->doi : ($pub->url ?? ''),
            ];

            if (!empty($pub->authors)) {
                $item['author'] = array_map(fn($name) => [
                    '@type' => 'Person',
                    'name' => $name,
                ], $pub->authors);
            }

            if (!empty($pub->journal)) {
                $item['isPartOf'] = [
                    '@type' => 'Periodical',
                    'name' => $pub->journal,
                ];
            }

            $items[] = $item;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => $items,
        ];

        return '<script type="application/ld+json">'
            . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . '</script>';
    }
}