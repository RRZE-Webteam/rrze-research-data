<?php

namespace RRZE\ResearchData\Blocks;

use RRZE\ResearchData\Frontend\PublicationRenderer;

defined('ABSPATH') || exit;


/**
 * Handles server-side rendering for the Research block.
 */
class BlockRender
{
    /**
     * Server-side render callback for the block.
     *
     * @param array $attributes Block attributes from the editor.
     * @return string HTML output.
     */
    public static function output(array $attributes = []): string
    {
        return PublicationRenderer::render($attributes);
    }
}