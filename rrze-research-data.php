<?php

/**
 * Plugin Name:        RRZE Research Data
 * Plugin URI:         https://github.com/RRZE-Webteam/rrze-research-data
 * Version:            1.0.1
 * Description:        Displays scientific publications from external research platforms
 * Author:             RRZE Webteam
 * Author URI:         https://www.wp.rrze.fau.de/
 * License:            GNU General Public License Version 3
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        rrze-research-data
 * Domain Path:        /languages
 * Requires at least:  6.8
 * Requires PHP:       8.2
 */

declare(strict_types=1);

namespace RRZE\ResearchData;

defined('ABSPATH') || exit;

const RESEARCH_DATA_PHP_VERSION = '8.2';
const RESEARCH_DATA_WP_VERSION = '6.8';

spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__ . '\\';
    $baseDir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Checks whether the server meets the minimum PHP and WordPress version requirements.
 *
 * @return string Error message if requirements are not met, empty string otherwise.
 */

function systemRequirements(): string
{
    $error = '';
    if (version_compare(PHP_VERSION, RESEARCH_DATA_PHP_VERSION, '<')) {
        $error = sprintf(
        /* translators: 1: Server PHP version number, 2: Required PHP version number. */
            __('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'rrze-research-data'),
            PHP_VERSION,
            RESEARCH_DATA_PHP_VERSION
        );
    } elseif (version_compare($GLOBALS['wp_version'], RESEARCH_DATA_WP_VERSION, '<')) {
        $error = sprintf(
        /* translators: 1: Server WordPress version number, 2: Required WordPress version number. */
            __('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'rrze-research-data'),
            $GLOBALS['wp_version'],
            RESEARCH_DATA_WP_VERSION
        );
    }
    return $error;
}


/**
 * Loads the text domain and initializes the plugin.
 *
 * Hooked into plugins_loaded. Shows an admin notice if system requirements are not met.
 */
add_action('plugins_loaded', __NAMESPACE__ . '\initializePlugin');

function initializePlugin(): void
{
    load_plugin_textdomain(
        'rrze-research-data',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
    $error = systemRequirements();
    if ($error !== '') {
        add_action('admin_notices', static function () use ($error): void {
            echo '<div class="notice notice-error"><p>' . esc_html($error) . '</p></div>';
        });
        return;
    }
    new Main();
}

