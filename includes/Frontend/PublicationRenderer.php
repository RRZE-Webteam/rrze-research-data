<?php

namespace RRZE\ResearchData\Frontend;


defined('ABSPATH') || exit;

/**
 * Renders the Block
 */
class PublicationRenderer
{

public static function render (array $attributes = []): string
{
    return '<pre>' . print_r($attributes, true) . '</pre>';
}





}