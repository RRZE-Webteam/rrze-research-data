<?php

namespace RRZE\ResearchData\Frontend;


use RRZE\ResearchData\Services\ResearchService;

defined('ABSPATH') || exit;

/**
 * Renders the Block
 */
class PublicationRenderer
{


    /**
     * Render file list as HTML output based on view type.
     *
     * @param array $attributes
     * @return string
     */
    public static function render(array $attributes = []): string
    {
        $authorId = $attributes['authorId'] ?? '';
        $limit = $attributes['limit'] ?? 10;
        $sort = $attributes['sort'] ?? 'desc';
        $view = $attributes['view'] ?? 'list';

        $service = new ResearchService();
        $publications = $service->preparePublications($authorId, $limit, $sort);

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
     * Renders a simple list.
     *
     * @param array $data
     * @param array $atts
     * @return string
     */
    private static function renderList(array $publications): string
    {
        if (empty($publications)) {
            return '<p>' . esc_html__('No files found.', 'rrze-data-research') . '</p>';
        }

        $html = '<ul class="wp-block-list wp-block-research-list">';

        foreach ($publications as $publication) {
            $title = esc_html($publication->title ?? '');
            $year = esc_html($publication->year ?? '');
            $journal = esc_html($publication->journal ?? '');


            $link = !empty($publication->doi)
                ? 'https://doi.org/' . $publication->doi
                : esc_url($publication->url ?? '');

            $meta = [];
            if ($year) $meta[] = $year;
            if ($journal) $meta[] = $journal;

            $metaHtml = !empty($meta) ? ' <span class="publication-meta">' . implode(' – ',
                    $meta) . '</span>' : '';


            $html .= sprintf(
                '<li><a href="%s" target="_blank" rel="noopener">%s</a>%s</li>',
                $link,
                $title,
                $metaHtml
            );
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Renders HTML table.
     *
     * @param array $data
     * @param array $atts
     * @return string
     */
    private static function renderTable(array $publications): string
    {
        if (empty($publications)) {
            return '<p>' . esc_html__('No files found.', 'rrze-data-research') . '</p>';
        }

        $html = '<figure class="wp-block-table"><table class="wp-block-research-table">';
        $html .= '<thead><tr>';
        $html .= '<th>Title</th>';
        $html .= '<th>Year</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        foreach ($publications as $publication) {
            $title = esc_html($publication->title ?? '');
            $year = esc_html($publication->year ?? '');
            $link = !empty($publication->doi)
                ? 'https://doi.org/' . $publication->doi
                : esc_url($publication->url ?? '');


            $html .= '<tr>';
            $html .= sprintf('<td><a href="%s" target="_blank" rel="noopener">%s</a></td>',
                $link,
                $title);
            $html .= '<td>' . $year . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></figure>';


        return $html;
    }
}