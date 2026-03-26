<?php

namespace RRZE\ResearchData\Blocks;


defined('ABSPATH') || exit;

/**
 * Registers the Research Data block and the RRZE block category.
 *
 * Hooks into WordPress 'init' to register the block with its server-side
 * render callback, and into 'block_categories_all' to add the RRZE category.
 */

class BlockRegistration
{
    /**
     * Register hooks for block registration and category.
     */
    public function __construct()
    {
        add_filter('block_categories_all', [$this, 'addRrzeCategory'], 10, 2);
        add_action('init', [$this, 'registerBlock']);
    }


    /**
     * Register the block.
     *
     * @return void
     */
    public function registerBlock(): void
    {

        register_block_type(
            dirname(__DIR__, 2) . '/build/block',
            [
                'render_callback' => [BlockRender::class, 'output'],
            ]
        );

        wp_set_script_translations(
            'rrze-research-data-editor-script',
            'rrze-research-data',
            dirname(__DIR__, 2) . '/languages'
        );
    }


    /**
     * Adds the RRZE block category (if not present).
     */
    public function addRrzeCategory(array $categories, $post): array
    {
        foreach ($categories as $category) {
            if ($category['slug'] === 'rrze') {
                return $categories;
            }
        }

        $categories[] = [
            'slug' => 'rrze',
            'title' => __('RRZE', 'rrze-research-data'),
        ];

        return $categories;
    }
}