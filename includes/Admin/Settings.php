<?php

namespace RRZE\ResearchData\Admin;

defined('ABSPATH') || exit;


/**
 * Registers and renders the Research Data settings page in the WordPress admin.
 *
 * Responsibilities:
 * - Add settings page under "Settings"
 * - Register plugin options
 * - Render the settings form
 *
 * This class only handles administration UI and option storage.
 */
class Settings
{
    /**
     * Constructor.
     *
     * Hooks the settings page and option registration into the appropriate WordPress admin actions.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addOptionsPage']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_init', [$this, 'handleClearCache']);
    }

    /**
     * Adds the Research Data settings page to the WordPress admin menu.
     *
     * The page is registered under "Settings" and requires the "manage_options" capability.
     *
     * @return void
     */
    public function addOptionsPage()
    {
        add_options_page(
                esc_html__('Research Data Settings', 'rrze-research-data'),
                'RRZE Research Data',
                'manage_options',
                'rrze-research-data',
                [$this, 'renderSettings']
        );
    }

    /**
     * Registers plugin settings.
     *
     * Currently no configurable options — reserved for future use.
     *
     * @return void
     */
    public function registerSettings(): void
    {
        //no settings
    }

    /**
     * Handles the cache clear form submission.
     *
     * Verifies the nonce, clears all plugin transients, sets a
     * success flag and redirects back to the settings page.
     *
     * @return void
     */
    public function handleClearCache(): void
    {
        if (isset($_POST['rrze_clear_cache']) && check_admin_referer('rrze_research_data_clear_cache')) {
            $cache = new \RRZE\ResearchData\Services\CacheService();
            $cache->deleteAll();
            set_transient('rrze_research_cache_cleared', true, 30);
            wp_redirect(admin_url('options-general.php?page=rrze-research-data'));
            exit;
        }
    }

    /**
     * Renders the settings page HTML.
     *
     * Outputs a form with a cache clear button and a success notice if the cache was recently cleared.
     *
     * @return void
     */
    public function renderSettings(): void
    {
        $cleared = (bool)get_transient('rrze_research_cache_cleared');
        if ($cleared) {
            delete_transient('rrze_research_cache_cleared');
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('RRZE Research Data Settings', 'rrze-research-data'); ?></h1>
            <p><?php echo esc_html__('Publication data from external sources (ORCID, PubMed, OpenAlex etc.) is cached for 12 hours to reduce API requests and improve page load times.', 'rrze-research-data'); ?></p>

            <?php if ($cleared) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__('Cache cleared successfully.', 'rrze-research-data'); ?></p>
                </div>
            <?php endif;
            ?>
            <h2><?php echo esc_html__('Cache', 'rrze-research-data'); ?></h2>
            <p><?php echo esc_html__('If publications are not up to date, you can clear the cache manually. New data will be fetched from the source on the next page load.', 'rrze-research-data'); ?></p>

            <form method="post">
                <?php
                wp_nonce_field('rrze_research_data_clear_cache'); ?>
                <?php submit_button(__('Clear Cache', 'rrze-research-data'), 'primary', 'rrze_clear_cache');
                ?>
            </form>

        </div>
        <?php
    }
}

