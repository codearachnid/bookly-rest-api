<?php

/**
 * This class defines all the necessary code to get main Bookly data.
 *
 * @since      1.0.0
 * @package    bookly
 * @subpackage Bookly_Rest_Api/includes
 * @author     PDG Solutions <info@pdg.solutions>
 */
class Bookly_Bookly
{
    /**
     * Bookly plugin relative path from plugin directory
     */
    const BOOKLY_PLUGIN_PATH = 'bookly-responsive-appointment-booking-tool/main.php';

    /**
     * Bookly plugin official URL
     */
    const BOOKLY_PLUGIN_URL = 'https://www.booking-wp-plugin.com/';

    /**
     * Bookly plugin version supported
     */
    const BOOKLY_MIN_SUPPORTED_VERSION = 17.0;

    /**
     * Check the Bookly plugin is installed & active.
     * @return boolean
     */
    public static function isActive()
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }

        return is_plugin_active(self::getMainFilePath(false));
    }

    /**
     * Check the Bookly installed version if supported by this plugin.
     * @return boolean
     */
    public static function isCurrentVersionSupported()
    {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }

        $booklyPluginData = get_plugin_data(self::getMainFilePath());

        return isset($booklyPluginData['Version']) &&
               $booklyPluginData['Version'] >= self::BOOKLY_MIN_SUPPORTED_VERSION;
    }

    /**
     * Get Bookly plugin main file path.
     * @param  boolean $absolute Whether to get the absolute path or relative.
     * @return boolean
     */
    public static function getMainFilePath($absolute = true)
    {
        if (!defined('WP_CONTENT_DIR')) {
            error_log('WP_CONTENT_DIR constant not defined');
            return '';
        }

        if ($absolute) {
            return WP_CONTENT_DIR.'/plugins/'.self::BOOKLY_PLUGIN_PATH;
        }

        return self::BOOKLY_PLUGIN_PATH;
    }

    public static function getNoBooklyPluginErrorMessage()
    {
        $booklyPluginLink = '<a href="'.self::BOOKLY_PLUGIN_URL.'" target="_blank" rel="noopener">Bookly</a>';
        $codeCanyonUrl = filter_var('https://codecanyon.net/item/bookly-booking-plugin-responsive-appointment-booking-and-scheduling/7226091',  FILTER_SANITIZE_URL);
        $booklyPurchaseLink = '<a href="'.$codeCanyonUrl.'" target="_blank" rel="noopener">'.esc_attr__('here', 'bookly-api').'</a>';

        return sprintf(
            esc_attr__('Bookly REST API requires plugin %1$s version %2$s+ or newer installed and activated in order to work. You can buy the plugin at CodeCanyon clicking %3$s.', 'bookly-api'),
            $booklyPluginLink,
            self::BOOKLY_MIN_SUPPORTED_VERSION,
            $booklyPurchaseLink
        );
    }

    public static function getNoBooklyPluginStringErrorMessage()
    {
        return sprintf(
            esc_attr__('Bookly REST API requires plugin Bookly version %1$s+ or newer installed and activated in order to work. You can buy the plugin at CodeCanyon.', 'bookly-api'),
            self::BOOKLY_MIN_SUPPORTED_VERSION
        );
    }
}
