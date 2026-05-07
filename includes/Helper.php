<?php

declare(strict_types=1);

namespace RRZE\ResearchData;

defined('ABSPATH') || exit;

/**
 * Utility class for debug logging.
 *
 * Provides a wrapper around error_log() that respects WordPress
 * debug settings (WP_DEBUG, WP_DEBUG_LOG) and supports log levels.
 *
 * Usage: Helper::debug($value, 'error');
 */
class Helper
{
    /**
     * Writes a debug message to the WordPress debug log.
     *
     * Only active when WP_DEBUG and WP_DEBUG_LOG are enabled.
     * Arrays and objects are converted to string via print_r().
     *
     * @param mixed $input The value to log
     * @param string $level Log level: "e"/"error", "i"/"info", "d"/"debug" (default: "i")
     */
    public static function debug($input, string $level = 'i'): void
    {
        if (!WP_DEBUG) {
            return;
        }
        if (in_array(strtolower((string)WP_DEBUG_LOG), ['true', '1'], true)) {
            $logPath = WP_CONTENT_DIR . '/debug.log';
        } elseif (is_string(WP_DEBUG_LOG)) {
            $logPath = WP_DEBUG_LOG;
        } else {
            return;
        }
        if (is_array($input) || is_object($input)) {
            $input = print_r($input, true);
        }
        switch (strtolower($level)) {
            case 'e':
            case 'error':
                $level = 'Error';
                break;
            case 'i':
            case 'info':
                $level = 'Info';
                break;
            case 'd':
            case 'debug':
                $level = 'Debug';
                break;
            default:
                $level = 'Info';
        }
        error_log(
            date("[d-M-Y H:i:s \U\T\C]")
            . " WP $level: "
            . basename(__FILE__) . ' '
            . $input
            . PHP_EOL,
            3,
            $logPath
        );
    }

    /**
     * Returns whether WordPress debug mode is active.
     *
     * @return bool True if WP_DEBUG is defined and true, false otherwise
     */
    public static function isDebug():bool
    {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

}
