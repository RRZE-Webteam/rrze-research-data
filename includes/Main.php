<?php

namespace RRZE\ResearchData;

defined('ABSPATH') || exit;

use RRZE\ResearchData\Rest\RestController;
use RRZE\ResearchData\Blocks\BlockRegistration;
//use RRZE\ResearchData\Admin\Settings;



/**
 * Main class
 *
 * This class serves as the entry point for the plugin.
 * It initializes blocks, REST endpoints and other components.
 *
 * @package RRZE\ResearchData
 */
class Main
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Create shared service instances.
     */
    private function init(): void
    {
    new RestController();
    new BlockRegistration();
    //new Settings();

    }
}
