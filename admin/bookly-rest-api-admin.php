<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    bookly
 * @subpackage bookly_api/admin
 * @author     PDG Solutions <info@pdg.solutions>
 */
class Bookly_Rest_Api_Admin
{
    private $version;

    private $plugin_name;

    private $api_namespace;

    private $restbase;

    private $appointment_slug;

    private $staff_slug;

    private $service_slug;

    private $customer_slug;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   string    $plugin_name        The name of the plugin.
     * @param   string    $version            The version of this plugin.
     * @param   string    $api_namespace      The namespace url for rest api.
     * @param   string    $restbase           The baseurl slug for rest api.
     * @param   string    $appointment_slug   The appointment slug for rest api.
     * @param   string    $staff_slug         The staff slug for rest api.
     * @param   string    $service_slug       The service slug for rest api.
     * @param   string    $customer_slug      The customer slug for rest api.
     */
    public function __construct(
        $plugin_name,
        $version,
        $api_namespace,
        $restbase,
        $appointment_slug,
        $staff_slug,
        $service_slug,
        $customer_slug
    ) {
        $this->plugin_name      = $plugin_name;
        $this->version          = $version;
        $this->api_namespace    = $api_namespace;
        $this->restbase         = $restbase;
        $this->appointment_slug = $appointment_slug;
        $this->staff_slug       = $staff_slug;
        $this->service_slug     = $service_slug;
        $this->customer_slug    = $customer_slug;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/bookly-rest-api-admin.css',
            [],
            $this->version,
            'all'
        );

        wp_enqueue_style(
            $this->plugin_name.'-swagger',
            plugin_dir_url(__FILE__) . 'swagger/swagger-ui.css',
            [],
            $this->version,
            'all'
        );
    }

    /**
     * Adds a submenu item under the Bookly main menu item
     *
     * @since    1.0.0
     */
    public function add_admin_page()
    {
        $this->enqueue_styles();

        add_action('admin_enqueue_scripts', 'Bookly_Rest_Api_Admin::bookly_admin_load_scripts');

        add_submenu_page(
            'bookly-menu',
            'Bookly REST API',
            'Bookly REST API',
            'manage_options',
            $this->plugin_name,
            [$this, 'load_admin_page_content']
        );
    }

    // Load the plugin admin page partial.
    public function load_admin_page_content()
    {
        if (!Bookly_Bookly::isActive() || !Bookly_Bookly::isCurrentVersionSupported()) {
            return $this->display_admin_page_error();
        }

        return $this->display_admin_page_swagger();
    }

    private function display_admin_page_error()
    {
        ?>
        <div class="notice notice-error" style="margin-top: 32px;">
            <p><?php echo Bookly_Bookly::getNoBooklyPluginErrorMessage() ?></p>
        </div>
        <?php
    }

    private function display_admin_page_swagger()
    {
        require_once plugin_dir_path(__FILE__). 'swagger/index.php';
    }

    // Adds the noance variable to the template
    // How it works: bloody wordpress..arrrrr
    // A script is loaded where the wp_head() method is with the name you gave to the script
    // The file is added with the variables set here
    // The noane variable is sent on the headers on every call
    // The requests from outside don't use PHP session so they don't need to send a noance header
    public static function bookly_admin_load_scripts()
    {
        wp_enqueue_script('swagger-ui-bundle', plugin_dir_url(__FILE__) . 'swagger/swagger-ui-bundle.js');
        wp_enqueue_script('swagger-ui-standalone-preset', plugin_dir_url(__FILE__) . 'swagger/swagger-ui-standalone-preset.js');

        wp_enqueue_script('wp-api', plugin_dir_url(__FILE__) . 'js/bookly-rest-api.js');
        wp_localize_script('wp-api', 'wpApiSettings', [
            'root'  => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ]);
    }
}
