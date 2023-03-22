<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    bookly
 * @subpackage Bookly_Rest_Api/includes
 * @author     PDG Solutions <info@pdg.solutions>
 */
class Bookly_Rest_Api
{
    protected $version;

    protected $loader;

    protected $plugin_name;

    protected $api_namespace;

    protected $restbase;

    protected $appointment_slug;

    protected $staff_slug;

    protected $service_slug;

    protected $customer_slug;

    protected $settings_slug;

    protected $appointment_table;

    protected $staff_table;

    protected $service_table;

    protected $category_table;

    protected $customer_table;

    protected $payments_table;

    protected $staff_services_table;

    protected $customer_appointments_table;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version           The version of this plugin.
     * @param      string    api_namespace      The namespace url for rest api.
     * @param      string    restbase           The baseurl slug for rest api.
     * @param      string    listrestbase       The list baseurl slug for rest api.
     * @since      1.0.0
     */
    public function __construct()
    {
        global $wpdb;

        $this->version = '0.0.0';
        if (defined('BOOKLY_REST_API_VERSION')) {
            $this->version = BOOKLY_REST_API_VERSION;
        }

        // IMPORTANT: Bookly version < 17.0 is not and won't be supported!
        $tableprefix = $wpdb->prefix.'bookly_';

        $this->plugin_name                 = 'bookly-api';
        $this->api_namespace               = 'wp/v2/';
        $this->restbase                    = 'bookly';

        $this->staff_slug                  = 'staff';
        $this->service_slug                = 'services';
        $this->customer_slug               = 'customers';
        $this->settings_slug               = 'settings';
        $this->appointment_slug            = 'appointments';

        $this->staff_table                 = $tableprefix.'staff';
        $this->service_table               = $tableprefix.'services';
        $this->category_table              = $tableprefix.'categories';
        $this->customer_table              = $tableprefix.'customers';
        $this->payments_table              = $tableprefix.'payments';
        $this->appointment_table           = $tableprefix.'appointments';
        $this->staff_services_table        = $tableprefix.'staff_services';
        $this->customer_appointments_table = $tableprefix.'customer_appointments';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_admin_settings();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Bookly_Rest_Api_Loader. Orchestrates the hooks of the plugin.
     * - Bookly_Api_Admin. Defines all hooks for the admin area.
     * - Bookly_Register_Rest_Api_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * Class used to get all Bookly plugin information
         */
        require_once BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api-bookly.php';

        /**
         * Class used to add the plugin settings to the theme customize.
         */
        require BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api-settings.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api-loader.php';
        $this->loader = new Bookly_Rest_Api_Loader();

        /**
         * The class responsible for defining all Settings.
         */
        require_once BOOKLY_REST_API_PLUGIN_PATH.'admin/bookly-rest-api-admin.php';

        /**
         * The class responsible to validate the plugin purchase code.
         */
        require_once BOOKLY_REST_API_PLUGIN_PATH.'includes/bookly-rest-api-validator.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once BOOKLY_REST_API_PLUGIN_PATH.'public/BooklyRestApi.php';
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Bookly_Rest_Api_Admin(
            $this->get_plugin_name(),
            $this->get_version(),
            $this->get_api_namespace(),
            $this->get_api_restbase(),
            $this->appointment_slug,
            $this->staff_slug,
            $this->service_slug,
            $this->customer_slug
        );
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_page', 999);
    }

    /**
     * Register all plugin settings.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_settings()
    {
        $settings_plugin = new Bookly_Rest_Api_Settings();
        $this->loader->add_action('customize_register', $settings_plugin, 'addSettings');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new BooklyRestApi(
            $this->get_plugin_name(),
            $this->get_version(),
            $this->get_api_namespace(),
            $this->get_api_restbase(),
            $this->appointment_slug,
            $this->staff_slug,
            $this->service_slug,
            $this->customer_slug,
            $this->appointment_table,
            $this->staff_table,
            $this->service_table,
            $this->category_table,
            $this->customer_table,
            $this->customer_appointments_table,
            $this->staff_services_table,
            $this->payments_table
        );

        $this->loader->add_action(
            'rest_api_init',
            $plugin_public,
            'registerRoutes'
        );

        $this->loader->add_filter(
            'determine_current_user',
            $plugin_public,
            'checkBasicAuth',
            20
        );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    public function get_api_namespace()
    {
        return $this->api_namespace;
    }

    public function get_api_restbase()
    {
        return $this->restbase;
    }

    public function get_version()
    {
        return $this->version;
    }
}
