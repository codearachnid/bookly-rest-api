<?php

/**
 * @package    bookly
 * @subpackage Bookly_Rest_Api/public
 * @author     PDG Solutions <info@pdg.solutions>
 */
class BooklyCustomers
{
    /**
     * Database table name.
     * @var string
     */
    private $customers_table;

    public function __construct($customers_table)
    {
        $this->customers_table = $customers_table;
    }

    /**
     * Get a paginated array of customers objects.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function index(WP_REST_Request $request)
    {
        global $wpdb;
        $sql    = "SELECT * FROM ".$this->customers_table;
        $result = $wpdb->get_results($sql, ARRAY_A);
        return new WP_REST_Response($result);
    }

    /**
     * Get a single customer object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function show(WP_REST_Request $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $customerId = $params['id'];

        $sql    = "SELECT * FROM $this->customers_table where id = $customerId";
        $result = $wpdb->get_row($sql, ARRAY_A);

        if (!$result) {
            return new WP_REST_Response('', 404);
        }

        return new WP_REST_Response($result);
    }

    /**
     * Store a single customer object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function store(WP_REST_Request $request)
    {
        global $wpdb;
        $params = $request->get_params();

        $wp_user_id = $full_name = $first_name = $last_name = $phone = $email = $birthday = $country = $state = $postcode = $city = $street = $additional_address = $notes = $info_fields = $created  = '';

        if (isset($params['full_name']) && !empty($params['full_name'])) {
            $full_name = sanitize_text_field($params['full_name']);
        }

        if (empty($full_name)) {
            $response= [
                'message' => 'full_name is required field to create Customer',
                'status'  => 422
            ];
            return new WP_REST_Response($response, 422);
        }

        if (isset($params['email']) && !empty($params['email'])) {
            $email = sanitize_email($params['email']);
        }

        if (isset($params['wp_user_id']) && !empty($params['wp_user_id'])) {
            $wp_user_id = sanitize_text_field($params['wp_user_id']);
        }

        if (isset($params['first_name']) && !empty($params['first_name'])) {
            $first_name = sanitize_text_field($params['first_name']);
        }

        if (isset($params['last_name']) && !empty($params['last_name'])) {
            $last_name = sanitize_text_field($params['last_name']);
        }

        if (isset($params['phone']) && !empty($params['phone'])) {
            $phone = sanitize_text_field($params['phone']);
        }

        if (isset($params['birthday']) && !empty($params['birthday'])) {
            $birthday = sanitize_text_field($params['birthday']);
        }

        if (isset($params['country']) && !empty($params['country'])) {
            $country = sanitize_text_field($params['country']);
        }

        if (isset($params['state']) && !empty($params['state'])) {
            $state = sanitize_text_field($params['state']);
        }

        if (isset($params['postcode']) && !empty($params['postcode'])) {
            $postcode = sanitize_text_field($params['postcode']);
        }

        if (isset($params['city']) && !empty($params['city'])) {
            $city = sanitize_text_field($params['city']);
        }
        if (isset($params['street']) && !empty($params['street'])) {
            $street = sanitize_text_field($params['street']);
        }

        if (isset($params['additional_address']) && !empty($params['additional_address'])) {
            $additional_address = sanitize_text_field($params['additional_address']);
        }

        if (isset($params['notes']) && !empty($params['notes'])) {
            $notes = sanitize_text_field($params['notes']);
        }

        if (isset($params['info_fields']) && !empty($params['info_fields'])) {
            $info_fields = sanitize_text_field($params['info_fields']);
        }

        $created = date("Y-m-d H:s:i");
        if (isset($params['created']) && !empty($params['created'])) {
            $created = sanitize_text_field($params['created']);
        }

        $wpdb->insert(
            $this->customers_table,
            [
                'wp_user_id'         => $wp_user_id,
                'full_name'          => $full_name,
                'first_name'         => $first_name,
                'last_name'          => $last_name,
                'phone'              => $phone,
                'email'              => $email,
                'birthday'           => $birthday,
                'country'            => $country,
                'state'              => $state,
                'postcode'           => $postcode,
                'city'               => $city,
                'street'             => $street,
                'additional_address' => $additional_address,
                'notes'              => $notes,
                'info_fields'        => $info_fields,
                'created'            => $created,
            ],
            [
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ]
        );

        if ($wpdb->last_error) {
            $response = [
                'Error'  => $wpdb->last_error,
                'status' => 400
            ];
            return new WP_REST_Response($response, 400);
        }
        $customer_id = $wpdb->insert_id;
        $sql         = "SELECT * FROM $this->customers_table where id = $customer_id";
        $result      = $wpdb->get_row($sql, ARRAY_A);

        return new WP_REST_Response($result);
    }

    /**
     * Update a single customer object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update(WP_REST_Request $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $customerId = $request['id'];
        $user_data  = [];

        $sql       = "SELECT * FROM $this->customers_table where id = $customerId";
        $user_data = $wpdb->get_row($sql, ARRAY_A);

        if (empty($user_data)) {
            return new WP_REST_Response('Resource not found.', 404);
        }

        $wp_user_id         = $user_data['wp_user_id'];
        $full_name          = $user_data['full_name'];
        $first_name         = $user_data['first_name'];
        $last_name          = $user_data['last_name'];
        $phone              = $user_data['phone'];
        $email              = $user_data['email'];
        $birthday           = $user_data['birthday'];
        $country            = $user_data['country'];
        $state              = $user_data['state'];
        $postcode           = $user_data['postcode'];
        $city               = $user_data['city'];
        $street             = $user_data['street'];
        $additional_address = $user_data['additional_address'];
        $notes              = $user_data['notes'];
        $info_fields        = $user_data['info_fields'];

        if (isset($params['full_name']) && !empty($params['full_name'])) {
            $full_name = sanitize_text_field($params['full_name']);
        }

        if (isset($params['email']) && !empty($params['email'])) {
            $email = sanitize_email($params['email']);
        }

        if (isset($params['wp_user_id']) && !empty($params['wp_user_id'])) {
            $wp_user_id = sanitize_text_field($params['wp_user_id']);
        }

        if (isset($params['first_name']) && !empty($params['first_name'])) {
            $first_name = sanitize_text_field($params['first_name']);
        }

        if (isset($params['last_name']) && !empty($params['last_name'])) {
            $last_name = sanitize_text_field($params['last_name']);
        }

        if (isset($params['phone']) && !empty($params['phone'])) {
            $phone = sanitize_text_field($params['phone']);
        }

        if (isset($params['birthday']) && !empty($params['birthday'])) {
            $birthday = sanitize_text_field($params['birthday']);
        }

        if (isset($params['country']) && !empty($params['country'])) {
            $country = sanitize_text_field($params['country']);
        }

        if (isset($params['state']) && !empty($params['state'])) {
            $state = sanitize_text_field($params['state']);
        }

        if (isset($params['postcode']) && !empty($params['postcode'])) {
            $postcode = sanitize_text_field($params['postcode']);
        }

        if (isset($params['city']) && !empty($params['city'])) {
            $city = sanitize_text_field($params['city']);
        }

        if (isset($params['street']) && !empty($params['street'])) {
            $street = sanitize_text_field($params['street']);
        }

        if (isset($params['additional_address']) && !empty($params['additional_address'])) {
            $additional_address = sanitize_text_field($params['additional_address']);
        }

        if (isset($params['notes']) && !empty($params['notes'])) {
            $notes = sanitize_text_field($params['notes']);
        }

        if (isset($params['info_fields']) && !empty($params['info_fields'])) {
            $info_fields = sanitize_text_field($params['info_fields']);
        }

        $wpdb->update(
            $this->customers_table,
            [
                'wp_user_id'         => $wp_user_id,
                'full_name'          => $full_name,
                'first_name'         => $first_name,
                'last_name'          => $last_name,
                'phone'              => $phone,
                'email'              => $email,
                'birthday'           => $birthday,
                'country'            => $country,
                'state'              => $state,
                'postcode'           => $postcode,
                'city'               => $city,
                'street'             => $street,
                'additional_address' => $additional_address,
                'notes'              => $notes,
                'info_fields'        => $info_fields
            ],
            ['id' => $customerId],
            [
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ],
            ['%d']
        );

        if ($wpdb->last_error) {
            return new WP_REST_Response($wpdb->last_error, 400);
        }


        $sql    = "SELECT * FROM $this->customers_table where id = $customerId";
        $result = $wpdb->get_row($sql, ARRAY_A);

        return new WP_REST_Response($result);
    }

    /**
     * Delete a customer object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function destroy(WP_REST_Request $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $customerId = $params['id'];

        $id = $wpdb->delete($this->customers_table, [ 'id' => $customerId ], [ '%d' ]);

        if (!$id) {
            return new WP_REST_Response('', 404);
        }

        return new WP_REST_Response();
    }
}
