<?php

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *       title="Bookly REST API",
 *       version="0.0.1",
 *       description="Bookly REST API exposes the Bookly resources to the public making it easier to extend the functionality or serve it to other systems."
 *   )
 * )
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    bookly
 * @subpackage Bookly_Rest_Api/public
 * @author     PDG Solutions <info@pdg.solutions>
 */
class BooklyRestApi extends WP_REST_Controller
{
    private $version;

    private $plugin_name;

    private $api_namespace;

    private $restbase;

    private $appointment_slug;

    private $staff_slug;

    private $service_slug;

    private $customer_slug;

    private $appointments_table;

    private $staff_table;

    private $services_table;

    private $category_table;

    private $customer_table;

    private $payments_table;

    private $staff_services_table;

    private $customer_appointments_table;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   string  $plugin_name                    The name of the plugin.
     * @param   string  $version                        The version of this plugin.
     * @param   string  $api_namespace                  The namespace url for rest api.
     * @param   string  $restbase                       The baseurl slug for rest api.
     * @param   string  $appointment_slug               The appointment slug for rest api.
     * @param   string  $staff_slug                     The staff slug for rest api.
     * @param   string  $service_slug                   The service slug for rest api.
     * @param   string  $customer_slug                  The customer slug for rest api.
     * @param   string  $appointments_table             The appointment table name.
     * @param   string  $staff_table                    The staff table name.
     * @param   string  $services_table                 The service table name.
     * @param   string  $category_table                 The category table name.
     * @param   string  $customer_table                 The customer table name.
     * @param   string  $customer_appointments_table    The customer_appointments table name.
     * @param   string  $staff_services_table           The staff_services table name.
     * @param   string  $payments_table                 The payments table name.
     */
    public function __construct(
        $plugin_name,
        $version,
        $api_namespace,
        $restbase,
        $appointment_slug,
        $staff_slug,
        $service_slug,
        $customer_slug,
        $appointments_table,
        $staff_table,
        $services_table,
        $category_table,
        $customer_table,
        $customer_appointments_table,
        $staff_services_table,
        $payments_table
    ) {
        $this->plugin_name                 = $plugin_name;
        $this->version                     = $version;
        $this->api_namespace               = $api_namespace;
        $this->restbase                    = $restbase;

        $this->appointment_slug            = $appointment_slug;
        $this->staff_slug                  = $staff_slug;
        $this->service_slug                = $service_slug;
        $this->customer_slug               = $customer_slug;

        $this->appointments_table          = $appointments_table;
        $this->staff_table                 = $staff_table;
        $this->services_table              = $services_table;
        $this->category_table              = $category_table;
        $this->customer_table              = $customer_table;
        $this->customer_appointments_table = $customer_appointments_table;
        $this->staff_services_table        = $staff_services_table;
        $this->payments_table              = $payments_table;
    }

    public function checkBasicAuth($user)
    {
        if (!empty($user)) {
            return $user;
        }

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            return $user;
        }

        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $user     = wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            return null;
        }

        return $user->ID;
    }

    public function checkPermissions($request)
    {
        if (!Bookly_Bookly::isActive() || !Bookly_Bookly::isCurrentVersionSupported()) {
            return new WP_Error(
                'rest_error',
                Bookly_Bookly::getNoBooklyPluginStringErrorMessage(),
                ['status' => 500]
            );
        }

        // $validator = new Bookly_Rest_Api_Validator('ZlIhAF6CiznkxEXLAI4jiywnDbum3kF4', '24121394');

        // $purchase_code = get_theme_mod('bookly_purchased_code');

        // if (!$validator->isValid($purchase_code)) {
        //     return new WP_Error(
        //         'rest_forbidden',
        //         esc_html__('Purchase and validate Bookly REST API', 'bookly-api'),
        //         ['status' => 401]
        //   );
        // }

        // We ensure the user is at least an editor
        if (!current_user_can('edit_posts')) {
            return new WP_Error(
                'rest_forbidden',
                esc_html__('You do not have permissions to do this operation.', 'bookly-api'),
                [ 'status' => 401 ]
            );
        }

        return true;
    }

    public function registerRoutes()
    {
        $this->registerCustomersRoutes();
        $this->registerServicesRoutes();
        $this->registerAppointmentsRoutes();
        $this->registerStaffsRoutes();
    }


    private function registerStaffsRoutes()
    {
        require_once __DIR__.'/BooklyStaffs.php';

        $staffsResource = new BooklyStaffs($this->staff_table, $this->services_table, $this->staff_services_table);

        if (!$staffsResource) {
            throw new \Exception('BooklyStaffs instance not found');
        }

        register_rest_route($this->api_namespace, $this->restbase."_".$this->staff_slug, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $staffsResource, 'index' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $staffsResource, 'store' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);

        register_rest_route($this->api_namespace, $this->restbase."_".$this->staff_slug.'/(?P<id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $staffsResource, 'show' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $staffsResource, 'update' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $staffsResource, 'destroy' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);
    }

    private function registerAppointmentsRoutes()
    {
        require_once __DIR__.'/BooklyAppointments.php';

        $appointmentsResource = new BooklyAppointments(
            $this->appointments_table,
            $this->customer_table,
            $this->payments_table,
            $this->staff_table,
            $this->services_table,
            $this->customer_appointments_table,
            $this->appointments_table
        );

        if (!$appointmentsResource) {
            throw new \Exception('BooklyAppointments instance not found');
        }

        register_rest_route($this->api_namespace, $this->restbase."_".$this->appointment_slug, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $appointmentsResource, 'index' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $appointmentsResource, 'store' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);

        register_rest_route($this->api_namespace, $this->restbase."_".$this->appointment_slug.'/(?P<id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $appointmentsResource, 'show' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $appointmentsResource, 'update' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $appointmentsResource, 'destroy' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);

        register_rest_route($this->api_namespace, $this->restbase."_".$this->appointment_slug.'/availability', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $appointmentsResource, 'availability'] ,
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);
    }

    private function registerServicesRoutes()
    {
        require_once __DIR__.'/BooklyServices.php';

        $servicesResource = new BooklyServices(
            $this->services_table,
            $this->staff_table,
            $this->category_table,
            $this->staff_services_table
        );

        if (!$servicesResource) {
            throw new \Exception('BooklyServices instance not found');
        }

        register_rest_route($this->api_namespace, $this->restbase."_".$this->service_slug, [
          [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [ $servicesResource, 'index' ],
            'permission_callback' => [ $this, 'checkPermissions' ]
          ],
          [
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => [ $servicesResource, 'store' ],
            'permission_callback' => [ $this, 'checkPermissions' ]
          ]
        ]);

        register_rest_route($this->api_namespace, $this->restbase."_".$this->service_slug.'/(?P<id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $servicesResource, 'show' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $servicesResource, 'update' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $servicesResource, 'destroy' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);
    }

    private function registerCustomersRoutes()
    {
        require_once __DIR__.'/BooklyCustomers.php';

        $customersResource = new BooklyCustomers($this->customer_table);

        if (!$customersResource) {
            throw new \Exception('BooklyCustomers instance not found');
        }

        register_rest_route($this->api_namespace, $this->restbase."_".$this->customer_slug, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $customersResource, 'index' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $customersResource, 'store' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);

        register_rest_route($this->api_namespace, $this->restbase."_".$this->customer_slug. '/(?P<id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $customersResource, 'show' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $customersResource, 'update' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $customersResource, 'destroy' ],
                'permission_callback' => [ $this, 'checkPermissions' ]
            ]
        ]);
    }
}
