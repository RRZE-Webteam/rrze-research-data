<?php

namespace RRZE\ResearchData\Frontend;


use RRZE\ResearchData\Services\ResearchService;

defined('ABSPATH') || exit;

/**
 * Renders publication data as HTML for the Research Data block.
 *
 * Receives a prepared list of Publication objects from ResearchService
 * and outputs them as a list or table depending on the block settings.
 */
class PublicationRenderer
{


    /**
     * Main render callback – extracts block attributes and delegates to the correct
     * view.
     *
     * @param array $attributes Block attributes from the editor
     * @return string            Rendered HTML output
     */
    public static function render(array $attributes = []): string
    {
        $authorId = $attributes['authorId'] ?? '';
        $limit = $attributes['limit'] ?? 10;
        $sort = $attributes['sort'] ?? 'desc';
        $view = $attributes['view'] ?? 'list';
        $source = $attributes['source'] ?? '';
        $year = (int)($attributes['year'] ?? 0);


        $service = new ResearchService();
        $publications = $service->preparePublications($authorId, $limit, $sort, $source, $year);

        if (is_wp_error($publications)) {
            return '<p>' . esc_html($publications->get_error_message()) . '</p>';
        }

        if (empty ($publications)) {
            return '<p>' . esc_html__('No publications found.', 'rrze-data-research') . '</p>';
        }

        switch ($view) {
            case 'table':
                return self::renderTable($publications);
            case 'list':
            default:
                return self::renderList($publications);
        }
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
            return '<p>' . esc_html__('No files found.', 'rrze-data-research') . '</p>';
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
     * Renders publications as an HTML table with title and year columns.
     *
     * @param array $publications Array of Publication objects
     * @return string              Rendered HTML
     */
    private static function renderTable(array $publications): string
    {
        if (empty($publications)) {
            return '<p>' . esc_html__('No files found.', 'rrze-data-research') . '</p>';
        }

        $html = '<figure class="wp-block-table"><table class="wp-block-research-table">';
        $html .= '<thead><tr>';
        $html .= '<th>Authors</th>';
        $html .= '<th>Year</th>';
        $html .= '<th>Title</th>';
        $html .= '<th>Venue</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        foreach ($publications as $publication) {
            $title = wp_kses($publication->title ?? '', [
                'sub' => [],
                'sup' => [],
                'i' => [],
                'b' => [],
            ]);
            $year = esc_html($publication->year ?? '');
            $link = !empty($publication->doi)
                ? 'https://doi.org/' . $publication->doi
                : esc_url($publication->url ?? '');
            $journal = esc_html($publication->journal ?? '');
            $volume = esc_html($publication->volume ?? '');
            $pages = esc_html($publication->pages ?? '');

            $volumeHtml = $volume ? ' , ' . $volume : '';
            $pagesHtml = $pages ? ', ' . $pages : '';

            $authorsHtml = !empty($publication->authors) ? esc_html(implode(', ', $publication->authors)) : '';


            $html .= '<tr>';
            $html .= '<td>' . $authorsHtml . '</td>';
            $html .= '<td>' . $year . '</td>';
            $html .= sprintf('<td><a href="%s" target="_blank" rel="noopener">%s</a></td>',
                $link,
                $title);
            $html .= '<td class="publication-journal">' . $journal . $volumeHtml . $pagesHtml . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></figure>';


        return $html;
    }
}