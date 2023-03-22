<?php

/**
 * Fired during plugin de/activation.
 *
 * This class defines all code necessary to run during the plugin's de/activation.
 *
 * @since      1.0.0
 * @package    bookly
 * @subpackage Bookly_Rest_Api/includes
 * @author     PDG Solutions <info@pdg.solutions>
 */
class Bookly_Rest_Api_Activator
{
    /**
     * WP plugin activation hook handler.
     * @return void
     */
    public static function activate()
    {
        // Add method if doesn't exists
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }

        // Toggle plugin activation
        if (is_plugin_active(plugin_basename(__FILE__))) {
            deactivate_plugins(plugin_basename(__FILE__));
        }
    }

    /**
     * WP plugin deactivation hook handler.
     * @return void
     */
    public static function deactivate()
    {
        // TODO: Remove the wp_customize settings and its values from the DB.
    }
}
