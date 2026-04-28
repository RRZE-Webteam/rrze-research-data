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
     * Main render callback – extracts block attributes and delegates torenderList() and renderJsonLd().
     *
     * @param array $attributes Block attributes from the editor (authorId, source,limit, yearFrom, yearTo, type, groupBy, citationStyle)
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
        $groupBy = $attributes['groupBy'] ?? '';
        $citationStyle = $attributes['citationStyle'] ?? '';


        $service = new ResearchService();
        $publications = $service->preparePublications($authorId, $limit, $source, $yearFrom, $yearTo, $type);

        if (is_wp_error($publications)) {
            return '<p>' . esc_html($publications->get_error_message()) . '</p>';
        }

        if (empty ($publications)) {
            return '<p>' . esc_html__('No publications found.', 'rrze-research-data') . '</p>';
        }

        return self::renderJsonLd($publications) . self::renderList($publications, $groupBy, $citationStyle);

    }


    /**
     * Renders publications as an HTML list, optionally grouped by year or type.
     *
     * If groupBy is "year", publications are grouped under <h3> headings by year(descending).
     * If groupBy is "type", publications are grouped under <h3> headings bypublication type (ascending).
     * If groupBy is empty, all publications are rendered as a single flat list.
     *
     * @param array  $publications Array of Publication objects
     * @param string $groupBy      Grouping mode: "year", "type", or "" (nogrouping)
     * @return string              Rendered HTML
     */

    private static function renderList(array $publications, string $groupBy='', string $citationStyle=''): string
    {
        if (empty($publications)) {
            return '<p>' . esc_html__('No publications found.', 'rrze-research-data') . '</p>';
        }

        if ($groupBy === 'year' || $groupBy === 'type') {
            $groups = [];
            foreach ($publications as $publication) {
                $key = $groupBy === 'year'
                    ? ($publication->year ?? '?')
                    : ($publication->type ?? 'other');
                $groups[$key][] = $publication;
            }
            $groupBy === 'year' ? krsort($groups) : ksort($groups);

            $html = '';
            foreach ($groups as $groupKey => $groupPublications) {
                $label = $groupBy === 'type' ? self::getTranslatedLabel($groupKey) : esc_html($groupKey);
                $html .= '<h3>' . $label . '</h3>';
                $html .= '<ul class="wp-block-list wp-block-research-list">';
                foreach ($groupPublications as $publication) {
                    $html .= self::renderItem($publication, $citationStyle);
                }
                $html .= '</ul>';
            }
            return $html;
        }

        $html = '<ul class="wp-block-list wp-block-research-list">';
        foreach ($publications as $publication) {
            $html .= self::renderItem($publication, $citationStyle);
        }
        $html .= '</ul>';
        return $html;
    }


    /**
     * Renders a single publication as an HTML list item.
     *
     * Outputs authors, year, title (linked via DOI or URL), journal, volume and pages.
     * Fields are separated by " | " and omitted if empty.
     *
     * @param object $publication A Publication object
     * @return string             HTML <li> element
     */
    private static function renderItem(object $publication, $citationStyle=''): string
    {
        return CitationFormatter::format($publication, $citationStyle);

    }


    /**
     * Returns a human-readable, translated label for a publication type.
     *
     * @param string $type Canonical publication type, e.g. "journal-article"
     * @return string      Translated label
     */
    private static function getTranslatedLabel(string $type): string
    {
        $labels = [
            'journal-article' => __('Journal Article', 'rrze-research-data'),
            'conference'      => __('Conference', 'rrze-research-data'),
            'book'            => __('Book', 'rrze-research-data'),
            'book-chapter'    => __('Book Chapter', 'rrze-research-data'),
            'editorship'      => __('Editorship', 'rrze-research-data'),
            'preprint'        => __('Preprint', 'rrze-research-data'),
            'review'          => __('Review', 'rrze-research-data'),
            'thesis'          => __('Thesis', 'rrze-research-data'),
            'other'           => __('Other', 'rrze-research-data'),
        ];

        return $labels[$type] ?? esc_html($type);
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