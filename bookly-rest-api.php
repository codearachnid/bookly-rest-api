<?php
/**
 * @link              https://pdg.solutions
 * @since             1.0.0
 * @package           bookly_api_crud_operations
 * Plugin Name:       Bookly REST API
 * Plugin URI:        https://pdg.solutions/portfolio/bookly-rest-api
 * Description:       Enable a REST API for your Bookly Plugin to performe CRUD operations on Appointments, Staff, Services and Customers.
 * Version:           1.1.2
 * Author:            PDG Solutions
 * Author URI:        https://pdg.solutions
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bookly-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort!
if (!defined('WPINC')) {
    die;
}



// ---- PLUGIN CONSTANTS ---- //

/**
 * Plugin version
 */
define('BOOKLY_REST_API_VERSION', '1.1.2');

/**
 * Plugin root path
 */
define('BOOKLY_REST_API_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Plugin URI path
 */
define('BOOKLY_REST_API_PLUGIN_URI', trailingslashit(plugin_dir_url(__FILE__)));



// ---- PLUGIN ACTIVATION ---- //

function bookly_rest_api_activate()
{
    require_once BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api-activator.php';
    Bookly_Rest_Api_Activator::activate();
}
register_activation_hook(__FILE__, 'bookly_rest_api_activate');



// ---- PLUGIN DEACTIVATION ---- //

function bookly_rest_api_deactivate()
{
    require_once BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api-activator.php';
    Bookly_Rest_Api_Activator::deactivate();
}
register_deactivation_hook(__FILE__, 'bookly_rest_api_deactivate');



// ---- PLUGIN SETTINGS LINK/URL ---- //

function bookly_rest_api_setting_link($links)
{
    $links[] = '<a href="'.esc_url(get_admin_url(null, 'admin.php?page=bookly-api')).'">'.
               esc_attr__('Settings', 'bookly-api').'</a>';

    return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'bookly_rest_api_setting_link');


// ---- PLUGIN EXECUTION ---- //

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function bookly_rest_api_run()
{
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks & settings, and public-facing site hooks.
     */
    require_once BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api.php';

    $plugin = new Bookly_Rest_Api();
    $plugin->run();
}
bookly_rest_api_run();
