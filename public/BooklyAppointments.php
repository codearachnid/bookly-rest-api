<?php

/**
 * @package    bookly
 * @subpackage Bookly_Rest_Api/public
 * @author     PDG Solutions <info@pdg.solutions>
 */
class BooklyAppointments
{
    /**
     * Database table name.
     * @var string
     */
    private $appointments_table;
    private $customer_table;
    private $payments_table;
    private $staff_table;
    private $services_table;
    private $customer_appointments_table;

    public function __construct(
        $appointments_table,
        $customer_table,
        $payments_table,
        $staff_table,
        $services_table,
        $customer_appointments_table
    ) {
        $this->appointments_table          = $appointments_table;
        $this->customer_table              = $customer_table;
        $this->payments_table              = $payments_table;
        $this->staff_table                 = $staff_table;
        $this->services_table              = $services_table;
        $this->customer_appointments_table = $customer_appointments_table;
    }

    /**
     * Get a paginated array of appointments objects.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function index(WP_REST_Request $request)
    {
        global $wpdb;
        $params    = $request->get_params();
        $condition = $order = '';
        if (isset($params['filter']) && is_array($params['filter']) && count($params['filter']) > 0) {
            $filter_arr = $params['filter'];
            if (isset($filter_arr['staff']) && !empty($filter_arr['staff'])) {
                $condition = $wpdb->prepare("LEFT JOIN $this->staff_table ON $this->appointments_table.staff_id = $this->staff_table.id WHERE $this->staff_table.email LIKE %s", $filter_arr['staff']);
            }

            if (isset($filter_arr['customer']) && !empty($filter_arr['customer'])) {
                $condition = $wpdb->prepare("WHERE id IN(SELECT appointment_id FROM $this->customer_appointments_table LEFT JOIN $this->customer_table ON $this->customer_appointments_table.customer_id = $this->customer_table.id WHERE $this->customer_table.email LIKE %s)", $filter_arr['customer']);
            }

            if (isset($filter_arr['appointments']) && !empty($filter_arr['appointments'])) {
                if ($filter_arr['appointments'] == 'today') {
                    $currentday = date('Y-m-d 00:00:00');
                    $nextday    = date('Y-m-d 23:59:00');
                    $condition  = $wpdb->prepare("WHERE `start_date` BETWEEN %s AND %s ", $currentday, $nextday);
                }
            }

            if (isset($filter_arr['month']) && !empty($filter_arr['month'])) {
                $start_date = date('Y-'.$filter_arr["month"].'-01 00:00:00');
                $lastday    = cal_days_in_month(CAL_GREGORIAN, $filter_arr['month'], date('Y'));
                ;
                $end_date   = date('Y-'.$filter_arr["month"]."-$lastday 23:59:00");
                $condition  = $wpdb->prepare("WHERE `start_date` BETWEEN %s AND %s ", $start_date, $end_date);
            }

            if (isset($filter_arr['startdate']) && !empty($filter_arr['startdate']) && isset($filter_arr['enddate']) && !empty($filter_arr['enddate'])) {
                $start_date = $filter_arr["startdate"].' 00:00:00';
                $end_date   = $filter_arr["enddate"].' 23:59:00';
                $condition  = $wpdb->prepare("WHERE `start_date` BETWEEN %s AND %s ", $start_date, $end_date);
            }

            if (isset($filter_arr['order']) && !empty($filter_arr['order']) && isset($filter_arr['orderby']) && !empty($filter_arr['orderby'])) {
                $order = "ORDER BY  $this->appointments_table.".$filter_arr['orderby']. " ".$filter_arr['order'];
            }
        }

        if (isset($params['historic']) && !empty($params['historic'])) {
            $condition = "LEFT JOIN $this->customer_appointments_table AS ca ON a.id = ca.appointment_id
                    LEFT JOIN $this->customer_table AS c ON c.id = ca.customer_id ";
            $userEmail = wp_get_current_user()->user_email;
            switch ($params['historic']) {
              case 'approved':
                $condition .= $wpdb->prepare("WHERE ca.status = 'approved' && a.start_date > NOW() AND c.email = %s", $userEmail);
                break;

              case 'cancelled':
                $condition .= $wpdb->prepare("WHERE ca.status = 'cancelled' AND c.email = %s", $userEmail);
                break;

              default:
                $condition .= $wpdb->prepare("WHERE  c.email = %s", $userEmail);
                break;
            }
        }

        $sql    = "SELECT a.* FROM $this->appointments_table AS a $condition $order";
        $result = $wpdb->get_results($sql, ARRAY_A);

        $lists = [];
        if (is_array($result) && count($result) > 0) {
            foreach ($result as $list_key => $list_value) {
                if (isset($list_value['staff_id']) && !empty($list_value['staff_id'])) {
                    $staffsql               = $wpdb->prepare("SELECT * FROM $this->staff_table where id = %d", $list_value['staff_id']);
                    $staffresult            = $wpdb->get_row($staffsql, ARRAY_A);
                    $list_value['staff_id'] = $staffresult;
                }

                if (isset($list_value['service_id']) && !empty($list_value['service_id'])) {
                    $servicesql               = $wpdb->prepare("SELECT * FROM $this->services_table where id = %d", $list_value['service_id']);
                    $serviceresult            = $wpdb->get_row($servicesql, ARRAY_A);
                    $list_value['service_id'] = $serviceresult;
                }

                $customer_sql = $wpdb->prepare("SELECT * FROM $this->customer_appointments_table where appointment_id = %d", $list_value['id']);
                $customer_data= $wpdb->get_row($customer_sql, ARRAY_A);
                if (is_array($customer_data) && !empty($customer_data)) {
                    $list_value['customer_appointment'] = $customer_data;
                }

                $lists[ $list_key] = $list_value ;
            }
        }

        return new WP_REST_Response($lists) ;
    }

    /**
     * Store a single appointment object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function store(WP_REST_Request $request)
    {
        global $wpdb;
        $params       = $request->get_params();
        $pd_coupon_id = $pd_type = $pd_total = $pd_tax = $pd_paid = $pd_paid_type = $pd_gateway_price_correction = $pd_status = $pd_details = $pd_created = $ca_notes = $ca_status = $ca_units = $ca_number_of_persons = $ca_customer_id = $location_id = $staff_id = $staff_any = $service_id = $custom_service_name = $custom_service_price = $start_date = $end_date = $extras_duration = $internal_note = $google_event_id = $google_event_etag = $created_from = $ca_package_id = $ca_extras = $ca_status_changed_at = $ca_token = $ca_time_zone = $ca_time_zone_offset = $ca_rating = $ca_rating_comment = $ca_locale = $ca_compound_service_id = $ca_compound_token = $ca_created_from = $ca_created = $ca_custom_fields = '';

        if (isset($params['location_id']) && !empty($params['location_id'])) {
            $location_id = sanitize_text_field($params['location_id']);
        }

        if (isset($params['staff_id']) && !empty($params['staff_id'])) {
            $staff_id = sanitize_text_field($params['staff_id']);
        }

        if (isset($params['staff_any']) && !empty($params['staff_any'])) {
            $staff_any = sanitize_text_field($params['staff_any']);
        }

        if (isset($params['service_id']) && !empty($params['service_id'])) {
            $service_id = sanitize_text_field($params['service_id']);
        }

        if (isset($params['custom_service_name']) && !empty($params['custom_service_name'])) {
            $custom_service_name = sanitize_text_field($params['custom_service_name']);
        }

        if (isset($params['custom_service_price']) && !empty($params['custom_service_price'])) {
            $custom_service_price = sanitize_text_field($params['custom_service_price']);
        }

        if (isset($params['start_date']) && !empty($params['start_date'])) {
            $start_date = sanitize_text_field($params['start_date']);
        }

        if (isset($params['end_date']) && !empty($params['end_date'])) {
            $end_date = sanitize_text_field($params['end_date']);
        }

        if (isset($params['extras_duration']) && !empty($params['extras_duration'])) {
            $extras_duration = sanitize_text_field($params['extras_duration']);
        }

        if (isset($params['internal_note']) && !empty($params['internal_note'])) {
            $internal_note = sanitize_text_field($params['internal_note']);
        }

        if (isset($params['google_event_id']) && !empty($params['google_event_id'])) {
            $google_event_id = sanitize_text_field($params['google_event_id']);
        }

        if (isset($params['google_event_etag']) && !empty($params['google_event_etag'])) {
            $google_event_etag = sanitize_text_field($params['google_event_etag']);
        }

        if (isset($params['created_from']) && !empty($params['created_from'])) {
            $created_from = sanitize_text_field($params['created_from']);
        }

        if (isset($params['customer_appointment']['package_id']) && !empty($params['customer_appointment']['package_id'])) {
            $ca_package_id = sanitize_text_field($params['customer_appointment']['package_id']);
        }

        if (isset($params['customer_appointment']['customer_id']) && !empty($params['customer_appointment']['customer_id'])) {
            $ca_customer_id = sanitize_text_field($params['customer_appointment']['customer_id']);
        }

        if (isset($params['customer_appointment']['number_of_persons']) && !empty($params['customer_appointment']['number_of_persons'])) {
            $ca_number_of_persons = sanitize_text_field($params['customer_appointment']['number_of_persons']);
        }

        if (isset($params['customer_appointment']['units']) && !empty($params['customer_appointment']['units'])) {
            $ca_units = sanitize_text_field($params['customer_appointment']['units']);
        }

        if (isset($params['customer_appointment']['status']) && !empty($params['customer_appointment']['status'])) {
            $ca_status = sanitize_text_field($params['customer_appointment']['status']);
        }

        if (isset($params['customer_appointment']['notes']) && !empty($params['customer_appointment']['notes'])) {
            $ca_notes = sanitize_text_field($params['customer_appointment']['notes']);
        }

        if (isset($params['customer_appointment']['extras']) && !empty($params['customer_appointment']['extras'])) {
            $ca_extras = sanitize_text_field($params['customer_appointment']['extras']);
        }

        if (isset($params['customer_appointment']['status_changed_at']) && !empty($params['customer_appointment']['status_changed_at'])) {
            $ca_status_changed_at = sanitize_text_field($params['customer_appointment']['status_changed_at']);
        }

        if (isset($params['customer_appointment']['token']) && !empty($params['customer_appointment']['token'])) {
            $ca_token = sanitize_text_field($params['customer_appointment']['token']);
        }

        if (isset($params['customer_appointment']['time_zone']) && !empty($params['customer_appointment']['time_zone'])) {
            $ca_time_zone = sanitize_text_field($params['customer_appointment']['time_zone']);
        }

        if (isset($params['customer_appointment']['time_zone_offset']) && !empty($params['customer_appointment']['time_zone_offset'])) {
            $ca_time_zone_offset = sanitize_text_field($params['customer_appointment']['time_zone_offset']);
        }

        if (isset($params['customer_appointment']['rating']) && !empty($params['customer_appointment']['rating'])) {
            $ca_rating = sanitize_text_field($params['customer_appointment']['rating']);
        }

        if (isset($params['customer_appointment']['rating_comment']) && !empty($params['customer_appointment']['rating_comment'])) {
            $ca_rating_comment = sanitize_text_field($params['customer_appointment']['rating_comment']);
        }

        if (isset($params['customer_appointment']['locale']) && !empty($params['customer_appointment']['locale'])) {
            $ca_locale = sanitize_text_field($params['customer_appointment']['locale']);
        }

        if (isset($params['customer_appointment']['compound_service_id']) && !empty($params['customer_appointment']['compound_service_id'])) {
            $ca_compound_service_id = sanitize_text_field($params['customer_appointment']['compound_service_id']);
        }

        if (isset($params['customer_appointment']['compound_token']) && !empty($params['customer_appointment']['compound_token'])) {
            $ca_compound_token = sanitize_text_field($params['customer_appointment']['compound_token']);
        }

        if (isset($params['customer_appointment']['created_from']) && !empty($params['customer_appointment']['created_from'])) {
            $ca_created_from = sanitize_text_field($params['customer_appointment']['created_from']);
        }

        if (isset($params['customer_appointment']['created']) && !empty($params['customer_appointment']['created_at'])) {
            $ca_created = sanitize_text_field($params['customer_appointment']['created_at']);
        }

        if (isset($params['customer_appointment']['custom_fields']) && !empty($params['customer_appointment']['custom_fields'])) {
            $ca_custom_fields = sanitize_text_field($params['customer_appointment']['custom_fields']);
        }

        if (isset($params['payment_details']['coupon_id']) && !empty($params['payment_details']['coupon_id'])) {
            $pd_coupon_id = sanitize_text_field($params['payment_details']['coupon_id']);
        }

        if (isset($params['payment_details']['type']) && !empty($params['payment_details']['type'])) {
            $pd_type = sanitize_text_field($params['payment_details']['type']);
        }

        if (isset($params['payment_details']['total']) && !empty($params['payment_details']['total'])) {
            $pd_total = sanitize_text_field($params['payment_details']['total']);
        }

        if (isset($params['payment_details']['tax']) && !empty($params['payment_details']['tax'])) {
            $pd_tax = sanitize_text_field($params['payment_details']['tax']);
        }

        if (isset($params['payment_details']['paid']) && !empty($params['payment_details']['paid'])) {
            $pd_paid = sanitize_text_field($params['payment_details']['paid']);
        }

        if (isset($params['payment_details']['paid_type']) && !empty($params['payment_details']['paid_type'])) {
            $pd_paid_type = sanitize_text_field($params['payment_details']['paid_type']);
        }

        if (isset($params['payment_details']['gateway_price_correction']) && !empty($params['payment_details']['gateway_price_correction'])) {
            $pd_gateway_price_correction = sanitize_text_field($params['payment_details']['gateway_price_correction']);
        }

        if (isset($params['payment_details']['status']) && !empty($params['payment_details']['status'])) {
            $pd_status = sanitize_text_field($params['payment_details']['status']);
        }

        if (isset($params['payment_details']['details']) && !empty($params['payment_details']['details'])) {
            $pd_details = serialize($params['payment_details']['details']);
        }

        if (isset($params['payment_details']['created_at']) && !empty($params['payment_details']['created_at'])) {
            $pd_created = sanitize_text_field($params['payment_details']['created_at']);
        }

        $wpdb->insert(
            $this->appointments_table,
            [
                'location_id'          => $location_id,
                'staff_id'             => $staff_id,
                'staff_any'            => $staff_any,
                'service_id'           => $service_id,
                'custom_service_name'  => "$custom_service_name",
                'custom_service_price' => "$custom_service_price",
                'start_date'           => "$start_date",
                'end_date'             => "$end_date",
                'extras_duration'      => "$extras_duration",
                'internal_note'        => "$internal_note",
                'google_event_id'      => "$google_event_id",
                'google_event_etag'    => "$google_event_etag",
                'created_from'         => "$created_from",
            ],
            [
                '%d',
                '%d',
                '%d',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s'
            ]
        );

        $appointment_id = $wpdb->insert_id;

        if ($wpdb->last_error) {
            return new WP_REST_Response($wpdb->last_error, 401) ;
        } else {
            $wpdb->insert(
                $this->payments_table,
                [
                    'coupon_id'                => $pd_coupon_id,
                    'type'                     => "$pd_type",
                    'total'                    => $pd_total,
                    'tax'                      => $pd_tax,
                    'paid'                     => $pd_paid,
                    'paid_type'                => $pd_paid_type,
                    'gateway_price_correction' => $pd_gateway_price_correction,
                    'status'                   => $pd_status,
                    'details'                  => "$pd_details",
                    'created_at'                  => "$pd_created"
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
                    '%s'
                ]
            );

            $payments_id = $wpdb->insert_id;

            if ($wpdb->last_error) {
                return new WP_REST_Response($wpdb->last_error, 401) ;
            } else {
                $wpdb->insert(
                    $this->customer_appointments_table,
                    [
                        'package_id'          => "$ca_package_id",
                        'customer_id'         => $ca_customer_id,
                        'appointment_id'      => $appointment_id,
                        'payment_id'          => $payments_id,
                        'number_of_persons'   => $ca_number_of_persons,
                        'units'               => $ca_units,
                        'notes'               => $ca_notes,
                        'extras'              => "$ca_extras",
                        'custom_fields'       => "$ca_custom_fields",
                        'status'              => "$ca_status",
                        'status_changed_at'   => "$ca_status_changed_at",
                        'token'               => "$ca_token",
                        'time_zone'           => "$ca_time_zone",
                        'time_zone_offset'    => "$ca_time_zone_offset",
                        'rating'              => "$ca_rating",
                        'rating_comment'      => "$ca_rating_comment",
                        'locale'              => "$ca_locale",
                        'compound_service_id' => "$ca_compound_service_id",
                        'compound_token'      => "$ca_compound_token",
                        'created_from'        => "$ca_created_from",
                        'created_at'             => "$ca_created",
                    ],
                    [
                        '%d',
                        '%d',
                        '%d',
                        '%d',
                        '%d',
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

                $customer_appointmentid = $wpdb->insert_id;

                if ($wpdb->last_error) {
                    return new WP_REST_Response($wpdb->last_error, 401) ;
                } else {
                    $sql                = "SELECT * FROM $this->appointments_table where id = $appointment_id ";
                    $appointment_object = $wpdb->get_row($sql, ARRAY_A);
                    $response           = [
                        'appointment_object'     => $appointment_object,
                        'customer_appointmentID' => $customer_appointmentid,
                        'payment_id'             => $payments_id,
                    ];

                    return new WP_REST_Response($response) ;
                }
            }
        }
    }

    /**
     * Get a single appointment data object.
     * @param  WP_REST_Request $request
     * @return mixed
     */
    public function show(WP_REST_Request $request)
    {
        global $wpdb;
        $params         = $request->get_params();
        $appointmentid  = $params['id'];
        $sql            = "SELECT * FROM $this->appointments_table where id = $appointmentid ";

        $result = $wpdb->get_row($sql, ARRAY_A);

        if (!(is_array($result) && count($result) > 0)) {
            return new WP_REST_Response('', 404);
        }
        $lists = [];

        if (isset($result['staff_id']) && !empty($result['staff_id'])) {
            $staffsql           = "SELECT id, wp_user_id, attachment_id, full_name, email, phone, info FROM $this->staff_table where id = ".$result['staff_id'];
            $staffresult        = $wpdb->get_row($staffsql, ARRAY_A);
            $result['staff_id'] = $staffresult;
        }

        if (isset($result['service_id']) && !empty($result['service_id'])) {
            $servicesql           = "SELECT id, category_id, title, duration, price FROM $this->services_table where id = ".$result['service_id'];
            $serviceresult        = $wpdb->get_row($servicesql, ARRAY_A);
            $result['service_id'] = $serviceresult;
        }

        $customer_sql = "SELECT customer_id, number_of_persons, units, status, notes FROM $this->customer_appointments_table where appointment_id =". $result['id'];
        $customer_data= $wpdb->get_row($customer_sql, ARRAY_A);
        if (is_array($customer_data) && !empty($customer_data)) {
            $result['customer_appointment'] = $customer_data;
        }

        $lists = $result;
        return new WP_REST_Response($lists);
    }

    /**
     * Update a single appointment object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update(WP_REST_Request $request)
    {
        global $wpdb;
        $params = $request->get_params();
        $app_id = $params['id'];

        $sql        = "SELECT * FROM $this->appointments_table where id = $app_id ";
        $app_result = $wpdb->get_row($sql, ARRAY_A);

        if (is_array($app_result) && count($app_result) > 0) {
            $location_id          = $app_result['location_id'];
            $staff_id             = $app_result['staff_id'];
            $staff_any            = $app_result['staff_any'];
            $service_id           = $app_result['service_id'];
            $custom_service_name  = $app_result['custom_service_name'];
            $custom_service_price = $app_result['custom_service_price'];
            $start_date           = $app_result['start_date'];
            $end_date             = $app_result['end_date'];
            $extras_duration      = $app_result['extras_duration'];
            $internal_note        = $app_result['internal_note'];
            $google_event_id      = $app_result['google_event_id'];
            $google_event_etag    = $app_result['google_event_etag'];
            $created_from         = $app_result['created_from'];

            if (isset($params['location_id']) && !empty($params['location_id'])) {
                $location_id = sanitize_text_field($params['location_id']);
            }

            if (isset($params['staff_id']) && !empty($params['staff_id'])) {
                $staff_id = sanitize_text_field($params['staff_id']);
            }

            if (isset($params['staff_any']) && !empty($params['staff_any'])) {
                $staff_any = sanitize_text_field($params['staff_any']);
            }

            if (isset($params['service_id']) && !empty($params['service_id'])) {
                $service_id = sanitize_text_field($params['service_id']);
            }

            if (isset($params['custom_service_name']) && !empty($params['custom_service_name'])) {
                $custom_service_name = sanitize_text_field($params['custom_service_name']);
            }

            if (isset($params['custom_service_price']) && !empty($params['custom_service_price'])) {
                $custom_service_price = sanitize_text_field($params['custom_service_price']);
            }

            if (isset($params['start_date']) && !empty($params['start_date'])) {
                $start_date = sanitize_text_field($params['start_date']);
            }

            if (isset($params['end_date']) && !empty($params['end_date'])) {
                $end_date = sanitize_text_field($params['end_date']);
            }

            if (isset($params['extras_duration']) && !empty($params['extras_duration'])) {
                $extras_duration = sanitize_text_field($params['extras_duration']);
            }

            if (isset($params['internal_note']) && !empty($params['internal_note'])) {
                $internal_note = sanitize_text_field($params['internal_note']);
            }

            if (isset($params['google_event_id']) && !empty($params['google_event_id'])) {
                $google_event_id = sanitize_text_field($params['google_event_id']);
            }

            if (isset($params['google_event_etag']) && !empty($params['google_event_etag'])) {
                $google_event_etag = sanitize_text_field($params['google_event_etag']);
            }

            if (isset($params['created_from']) && !empty($params['created_from'])) {
                $created_from = sanitize_text_field($params['created_from']);
            }

            $wpdb->update(
                $this->appointments_table,
                [
                    'location_id'          => $location_id,
                    'staff_id'             => $staff_id,
                    'staff_any'            => $staff_any,
                    'service_id'           => $service_id,
                    'custom_service_name'  => "$custom_service_name",
                    'custom_service_price' => "$custom_service_price",
                    'start_date'           => "$start_date",
                    'end_date'             => "$end_date",
                    'extras_duration'      => "$extras_duration",
                    'internal_note'        => "$internal_note",
                    'google_event_id'      => "$google_event_id",
                    'google_event_etag'    => "$google_event_etag",
                    'created_from'         => "$created_from",
                ],
                ['id'=> $app_id],
                [
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ],
                ['%d']
            );

            if ($wpdb->last_error) {
                return new WP_REST_Response($wpdb->last_error, 401);
            }
            $sql                = "SELECT * FROM $this->appointments_table where id = $app_id ";
            $appointment_object = $wpdb->get_row($sql, ARRAY_A);
            return new WP_REST_Response($appointment_object);
        } else {
            return new WP_REST_Response('', 404);
        }
    }

    /**
     * Delete an appointment object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function destroy(WP_REST_Request $request)
    {
        global $wpdb;
        $params         = $request->get_params();
        $appointmentId  = $params['id'];

        $id = $wpdb->delete($this->appointments_table, [ 'id' => $appointmentId ], [ '%d' ]);

        if (!$id) {
            return new WP_REST_Response('', 404);
        }

        return new WP_REST_Response();
    }

    /**
     * Get Appointments Availability.
     * @param  WP_REST_Request $request
     * @return mixed
     */
    public function availability($request)
    {
        $params = $request->get_params();

        $response = [
            'Error'  => '',
            'status' => 422
        ];

        if (!isset($params['date_from']) || empty($params['date_from'])) {
            $response['Error'] = 'Date from required';
            return new WP_REST_Response($response, 422);
        } else {
            $time    = strtotime($params['date_from']);
            $newTime = date('Y-m-d', $time);
            if ($newTime) {
                $params['date_from'] = $newTime;
            } else {
                $response['Error'] = 'Invalid date from';
                return new WP_REST_Response($response, 422);
            }
        }

        if (!isset($params['days']) || empty($params['days'])) {
            $response['Error'] = 'Days required';
            return new WP_REST_Response($response, 422);
        } else {
            $validDays      = true;
            $params['days'] = explode(',', $params['days']);
            foreach ($params['days'] as $day) {
                if ($day < 1 || $day > 7) {
                    $validDays = false;
                }
            }
            if (!$validDays) {
                $response['Error'] = 'Invalid days';
                return new WP_REST_Response($response, 422);
            }
        }

        if (!isset($params['time_from']) || empty($params['time_from'])) {
            $response['Error'] = 'Time from required';
            return new WP_REST_Response($response, 422);
        } else {
            $time    = strtotime($params['time_from']);
            $newTime = date('H:M', $time);
            if ($newTime) {
                $params['time_from'] = $newTime;
            } else {
                $response['Error'] = 'Invalid time from';
                return new WP_REST_Response($response, 422);
            }
        }

        if (!isset($params['time_to']) || empty($params['time_to'])) {
            $response['Error'] = 'Time to required';
            return new WP_REST_Response($response, 422);
        } else {
            $time    = strtotime($params['time_to']);
            $newTime = date('H:M', $time);
            if ($newTime) {
                $params['time_to'] = $newTime;
            } else {
                $response['Error'] = 'Invalid time to';
                return new WP_REST_Response($response, 422);
            }
        }

        if (!isset($params['service_id']) || empty($params['service_id'])) {
            $response['Error'] = 'Service ID required';
            return new WP_REST_Response($response, 422);
        } else {
            $params['service_id'] = intval($params['service_id']);
            if ($params['service_id'] <= 0) {
                $response['Error'] = 'Invalid service ID';
                return new WP_REST_Response($response, 422);
            }
        }

        if (!isset($params['staff_ids']) || empty($params['staff_ids'])) {
            $response['Error'] = 'Staff IDs required';
            return new WP_REST_Response($response, 422);
        } else {
            $params['staff_ids'] = explode(',', $params['staff_ids']);
        }

        $user = new Bookly\Lib\UserBookingData(0);
        $user->setDateFrom($params['date_from']);
        $user->setDays($params['days']);
        $user->setTimeFrom($params['time_from']);
        $user->setTimeTo($params['time_to']);
        $chainItem = new Bookly\Lib\ChainItem();
        $chainItem->setData([
            'service_id'        => $params['service_id'],
            'staff_ids'         => $params['staff_ids'],
            'number_of_persons' => 1,
            'quantity'          => 1,
            'extras'            => [],
            'custom_fields'     => [],
            'location_id'       => null,
            'series_unique_id'  => null,
            'first_in_series'   => null,
            'units'             => 1,
        ]);
        $user->chain->add($chainItem);
        $finder = new Bookly\Lib\Slots\Finder($user);
        $finder->prepare()->load();
        $slots_data = [];
        foreach ($finder->getSlots() as $group => $group_slots) {
            $slots_data[ $group ] = [
              'title' => date_i18n(($finder->isServiceDurationInDays() ? 'M' : 'D, M d'), strtotime($group)),
              'slots' => [],
            ];
            foreach ($group_slots as $slot) {
                $slots_data[ $group ]['slots'][] = [
                    'data'            => $slot->buildSlotData(),
                    'time_text'       => $slot->start()->toClientTz()->formatI18n($finder->isServiceDurationInDays() ? 'D, M d' : get_option('time_format')),
                    'status'          => $slot->waitingListEverStarted() ? 'waiting-list' : ($slot->fullyBooked() ? 'booked' : ''),
                    'additional_text' => $slot->waitingListEverStarted() ? '(' . $slot->maxOnWaitingList() . ')' : (Bookly\Lib\Config::groupBookingActive() ? Proxy\GroupBooking::getTimeSlotText($slot) : ''),
                ];
            }
        }

        return new WP_REST_Response($slots_data);
    }
}
