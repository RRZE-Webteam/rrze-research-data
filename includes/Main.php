<?php

namespace RRZE\ResearchData;

defined('ABSPATH') || exit;

use RRZE\ResearchData\Rest\RestController;
use RRZE\ResearchData\Blocks\BlockRegistration;
use RRZE\ResearchData\Admin\Settings;


/**
 * Plugin entry point.
 *
 * Bootstraps all plugin components: REST API endpoints, block registration, and admin settings.
 */
class Main
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initializes all plugin components.
     */
    private function init(): void
    {
        new RestController();
        new BlockRegistration();
        new Settings();

    }
}
