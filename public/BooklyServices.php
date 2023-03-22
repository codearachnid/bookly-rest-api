<?php

/**
 * @package    bookly
 * @subpackage Bookly_Rest_Api/public
 * @author     PDG Solutions <info@pdg.solutions>
 */
class BooklyServices
{
    /**
     * Database table name.
     * @var string
     */
    private $services_table;
    private $staff_table;
    private $category_table;
    private $staff_services_table;

    public function __construct($services_table, $staff_table, $category_table, $staff_services_table)
    {
        $this->services_table       = $services_table;
        $this->staff_table          = $staff_table;
        $this->category_table       = $category_table;
        $this->staff_services_table = $staff_services_table;
    }

    /**
     * Get a paginated array of services objects.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function index(WP_REST_Request $request)
    {
        global $wpdb;

        $params = $request->get_params();
        $where  = '';
        if (isset($params['staff_id'])) {
            $where = $wpdb->prepare('where st.id = %d', $params['staff_id']);
        }

        $sql = "SELECT s.*, c.name AS category_name, c.position AS category_position FROM $this->services_table AS s
            LEFT JOIN $this->staff_services_table AS ss ON ss.service_id = s.id
            LEFT JOIN $this->staff_table AS st ON st.id = ss.staff_id
            LEFT JOIN $this->category_table AS c on c.id = s.category_id $where";

        $result = $wpdb->get_results($sql, ARRAY_A);

        return new WP_REST_Response($result);
    }

    /**
     * Store a single service object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function store(WP_REST_Request $request)
    {
        global $wpdb;
        $params = $request->get_params();

        $staff_ids   = [];
        $category_id = $title = $duration = $price = $color = $capacity_min = $capacity_max = $padding_left = $padding_right = $info = $start_time_info = $end_time_info = $units_min = $units_max = $type = $package_life_time = $package_size = $package_unassigned = $appointments_limit = $limit_period = $staff_preference = $recurrence_enabled = $recurrence_frequencies = $visibility = $position = '';

        if (isset($params['title']) && !empty($params['title'])) {
            $title = sanitize_text_field($params['title']);
        }

        if (empty($title)) {
            $response= [
              'message'       => 'Title is required field to create Service',
              'status'        => 422
            ];
            return new WP_REST_Response($response, 422);
        }


        if (isset($params['category_id']) && !empty($params['category_id'])) {
            $category_id = sanitize_text_field($params['category_id']);
        } else {
            $category_id = null;
        }

        if (isset($params['category_name']) && !empty($params['category_name'])) {
            $category_name = sanitize_text_field($params['category_name']);

            $cat_id = $wpdb->get_col("SELECT id FROM $this->category_table where name LIKE '".$category_name."' limit 1");

            if (is_array($cat_id) && count($cat_id) > 0) {
                $category_id = $cat_id['0'];
            } else {
                $wpdb->insert(
                    $this->category_table,
                    [
                        'name' => $category_name
                    ],
                    [
                        '%s'
                    ]
                );

                $category_id = $wpdb->insert_id;
            }
        }

        $serviceid = $wpdb->get_col($wpdb->prepare("SELECT id  FROM $this->services_table WHERE title LIKE %s AND category_id = %d", $title, $category_id));

        if (count($serviceid) > 0) {
            $response = [
                'message'       => 'Service already exists.',
                'service_id'    => $serviceid,
                'status'        => 422
            ];
            return new WP_REST_Response($response, 422);
        }

        if (isset($params['staff_ids']) && !empty($params['staff_ids'])) {
            $staff_ids = $params['staff_ids'];
        }

        if (isset($params['duration']) && !empty($params['duration'])) {
            $duration = sanitize_text_field($params['duration']);
        }

        if (isset($params['price']) && !empty($params['price'])) {
            $price = sanitize_text_field($params['price']);
        }

        if (isset($params['color']) && !empty($params['color'])) {
            $color = sanitize_text_field($params['color']);
        }

        if (isset($params['capacity_min']) && !empty($params['capacity_min'])) {
            $capacity_min = sanitize_text_field($params['capacity_min']);
        }

        if (isset($params['capacity_max']) && !empty($params['capacity_max'])) {
            $capacity_max = sanitize_text_field($params['capacity_max']);
        }

        if (isset($params['padding_left']) && !empty($params['padding_left'])) {
            $padding_left = sanitize_text_field($params['padding_left']);
        }

        if (isset($params['padding_right']) && !empty($params['padding_right'])) {
            $padding_right = sanitize_text_field($params['padding_right']);
        }

        if (isset($params['info']) && !empty($params['info'])) {
            $info = sanitize_text_field($params['info']);
        }

        if (isset($params['start_time_info']) && !empty($params['start_time_info'])) {
            $start_time_info = sanitize_text_field($params['start_time_info']);
        }

        if (isset($params['end_time_info']) && !empty($params['end_time_info'])) {
            $end_time_info = sanitize_text_field($params['end_time_info']);
        }

        if (isset($params['units_min']) && !empty($params['units_min'])) {
            $units_min = sanitize_text_field($params['units_min']);
        }

        if (isset($params['units_max']) && !empty($params['units_max'])) {
            $units_max = sanitize_text_field($params['units_max']);
        }

        if (isset($params['type']) && !empty($params['type'])) {
            $type = sanitize_text_field($params['type']);
        }

        if (isset($params['package_life_time']) && !empty($params['package_life_time'])) {
            $package_life_time = sanitize_text_field($params['package_life_time']);
        }

        if (isset($params['package_size']) && !empty($params['package_size'])) {
            $package_size = sanitize_text_field($params['package_size']);
        }

        if (isset($params['package_unassigned']) && !empty($params['package_unassigned'])) {
            $package_unassigned = sanitize_text_field($params['package_unassigned']);
        }

        if (isset($params['appointments_limit']) && !empty($params['appointments_limit'])) {
            $appointments_limit = sanitize_text_field($params['appointments_limit']);
        }

        if (isset($params['limit_period']) && !empty($params['limit_period'])) {
            $limit_period = sanitize_text_field($params['limit_period']);
        }

        if (isset($params['staff_preference']) && !empty($params['staff_preference'])) {
            $staff_preference = sanitize_text_field($params['staff_preference']);
        }

        if (isset($params['recurrence_enabled']) && !empty($params['recurrence_enabled'])) {
            $recurrence_enabled = sanitize_text_field($params['recurrence_enabled']);
        }

        if (isset($params['recurrence_frequencies']) && !empty($params['recurrence_frequencies'])) {
            $recurrence_frequencies = sanitize_text_field($params['recurrence_frequencies']);
        }

        if (isset($params['visibility']) && !empty($params['visibility'])) {
            $visibility = sanitize_text_field($params['visibility']);
        }

        if (isset($params['position']) && !empty($params['position'])) {
            $position = sanitize_text_field($params['position']);
        }

        $wpdb->insert(
            $this->services_table,
            [
                'category_id'             => $category_id,
                'title'                   => $title,
                'duration'                => $duration,
                'price'                   => $price,
                'color'                   => $color,
                'capacity_min'            => $capacity_min,
                'capacity_max'            => $capacity_max,
                'padding_left'            => $padding_left,
                'padding_right'           => $padding_right,
                'info'                    => $info,
                'start_time_info'         => $start_time_info,
                'end_time_info'           => $end_time_info,
                'units_min'               => $units_min,
                'units_max'               => $units_max,
                'type'                    => $type,
                'package_life_time'       => $package_life_time,
                'package_size'            => $package_size,
                'package_unassigned'      => $package_unassigned,
                'appointments_limit'      => $appointments_limit,
                'limit_period'            => $limit_period,
                'staff_preference'        => $staff_preference,
                'recurrence_enabled'      => $recurrence_enabled,
                'recurrence_frequencies'  => $recurrence_frequencies,
                'visibility'              => $visibility,
                'position'                => $position
            ],
            [
                '%d',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
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
                '%s',
                '%d',
          ]
        );

        if ($wpdb->last_error) {
            $response = [
                'Error'      => $wpdb->last_error,
                'status'     => 401
            ];
            return new WP_REST_Response($response, 401);
        } else {
            $service_id = $wpdb->insert_id;

            if (is_array($staff_ids) && count($staff_ids) > 0) {
                foreach ($staff_ids as $sid) {
                    $wpdb->insert(
                        $this->staff_services_table,
                        [
                            'staff_id'     => $sid,
                            'service_id'   => $service_id,
                            'price'        => $price,
                            'deposit'      => "100%",
                            'capacity_min' => $capacity_min,
                            'capacity_max' => $capacity_max
                        ],
                        [
                            '%d',
                            '%d',
                            '%s',
                            '%s',
                            '%d',
                            '%d',
                        ]
                    );
                }
            }

            $sql        = "SELECT * FROM $this->services_table where id = $service_id";
            $result     = $wpdb->get_row($sql, ARRAY_A);

            $response = [
                'message'           => 'Service Created.',
                'service_object'    => $result,
                'status'            => 200
            ];
            return new WP_REST_Response($response, 200);
        }
    }

    /**
     * Update a single service object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update(WP_REST_Request  $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $serviceid  = $params['id'];
        $sql        = "SELECT * FROM $this->services_table where id = $serviceid";
        $result     = $wpdb->get_row($sql, ARRAY_A);

        if (is_array($result) && count($result) > 0) {
            $staff_ids              = [];
            $category_id            = $result['category_id'];
            $title                  = $result['title'];
            $duration               = $result['duration'];
            $price                  = $result['price'];
            $color                  = $result['color'];
            $capacity_min           = $result['capacity_min'];
            $capacity_max           = $result['capacity_max'];
            $padding_left           = $result['padding_left'];
            $padding_right          = $result['padding_right'];
            $info                   = $result['info'];
            $start_time_info        = $result['start_time_info'];
            $end_time_info          = $result['end_time_info'];
            $units_min              = $result['units_min'];
            $units_max              = $result['units_max'];
            $type                   = $result['type'];
            $package_life_time      = $result['package_life_time'];
            $package_size           = $result['package_size'];
            $package_unassigned     = $result['package_unassigned'];
            $appointments_limit     = $result['appointments_limit'];
            $limit_period           = $result['limit_period'];
            $staff_preference       = $result['staff_preference'];
            $recurrence_enabled     = $result['recurrence_enabled'];
            $recurrence_frequencies = $result['recurrence_frequencies'];
            $visibility             = $result['visibility'];
            $position               = $result['position'];

            if (isset($params['title']) && !empty($params['title'])) {
                $title = sanitize_text_field($params['title']);
            }

            if (isset($params['category_id']) && !empty($params['category_id'])) {
                $category_id = sanitize_text_field($params['category_id']);
            } else {
                $category_id = null;
            }

            if (isset($params['category_name']) && !empty($params['category_name'])) {
                $category_name = sanitize_text_field($params['category_name']);

                $cat_id = $wpdb->get_col("SELECT id FROM $this->category_table where name LIKE '".$category_name."' limit 1");

                if (is_array($cat_id) && count($cat_id) > 0) {
                    $category_id = $cat_id['0'];
                } else {
                    $wpdb->insert(
                        $this->category_table,
                        [
                            'name' => $category_name
                        ],
                        [
                            '%s'
                        ]
                    );

                    $category_id = $wpdb->insert_id;
                }
            }

            if (isset($params['staff_ids']) && !empty($params['staff_ids'])) {
                $staff_ids = $params['staff_ids'];
            }

            if (isset($params['duration']) && !empty($params['duration'])) {
                $duration = sanitize_text_field($params['duration']);
            }

            if (isset($params['price']) && !empty($params['price'])) {
                $price = sanitize_text_field($params['price']);
            }

            if (isset($params['color']) && !empty($params['color'])) {
                $color = sanitize_text_field($params['color']);
            }

            if (isset($params['capacity_min']) && !empty($params['capacity_min'])) {
                $capacity_min = sanitize_text_field($params['capacity_min']);
            }

            if (isset($params['capacity_max']) && !empty($params['capacity_max'])) {
                $capacity_max = sanitize_text_field($params['capacity_max']);
            }

            if (isset($params['padding_left']) && !empty($params['padding_left'])) {
                $padding_left = sanitize_text_field($params['padding_left']);
            }

            if (isset($params['padding_right']) && !empty($params['padding_right'])) {
                $padding_right = sanitize_text_field($params['padding_right']);
            }

            if (isset($params['info']) && !empty($params['info'])) {
                $info = sanitize_text_field($params['info']);
            }

            if (isset($params['start_time_info']) && !empty($params['start_time_info'])) {
                $start_time_info = sanitize_text_field($params['start_time_info']);
            }

            if (isset($params['end_time_info']) && !empty($params['end_time_info'])) {
                $end_time_info = sanitize_text_field($params['end_time_info']);
            }

            if (isset($params['units_min']) && !empty($params['units_min'])) {
                $units_min = sanitize_text_field($params['units_min']);
            }

            if (isset($params['units_max']) && !empty($params['units_max'])) {
                $units_max = sanitize_text_field($params['units_max']);
            }

            if (isset($params['type']) && !empty($params['type'])) {
                $type = sanitize_text_field($params['type']);
            }

            if (isset($params['package_life_time']) && !empty($params['package_life_time'])) {
                $package_life_time = sanitize_text_field($params['package_life_time']);
            }

            if (isset($params['package_size']) && !empty($params['package_size'])) {
                $package_size = sanitize_text_field($params['package_size']);
            }

            if (isset($params['package_unassigned']) && !empty($params['package_unassigned'])) {
                $package_unassigned = sanitize_text_field($params['package_unassigned']);
            }

            if (isset($params['appointments_limit']) && !empty($params['appointments_limit'])) {
                $appointments_limit = sanitize_text_field($params['appointments_limit']);
            }

            if (isset($params['limit_period']) && !empty($params['limit_period'])) {
                $limit_period = sanitize_text_field($params['limit_period']);
            }

            if (isset($params['staff_preference']) && !empty($params['staff_preference'])) {
                $staff_preference = sanitize_text_field($params['staff_preference']);
            }

            if (isset($params['recurrence_enabled']) && !empty($params['recurrence_enabled'])) {
                $recurrence_enabled = sanitize_text_field($params['recurrence_enabled']);
            }

            if (isset($params['recurrence_frequencies']) && !empty($params['recurrence_frequencies'])) {
                $recurrence_frequencies = sanitize_text_field($params['recurrence_frequencies']);
            }

            if (isset($params['visibility']) && !empty($params['visibility'])) {
                $visibility = sanitize_text_field($params['visibility']);
            }

            if (isset($params['position']) && !empty($params['position'])) {
                $position = sanitize_text_field($params['position']);
            }

            $wpdb->update(
                $this->services_table,
                [
                    'category_id'             => $category_id,
                    'title'                   => $title,
                    'duration'                => $duration,
                    'price'                   => $price,
                    'color'                   => $color,
                    'capacity_min'            => $capacity_min,
                    'capacity_max'            => $capacity_max,
                    'padding_left'            => $padding_left,
                    'padding_right'           => $padding_right,
                    'info'                    => $info,
                    'start_time_info'         => $start_time_info,
                    'end_time_info'           => $end_time_info,
                    'units_min'               => $units_min,
                    'units_max'               => $units_max,
                    'type'                    => $type,
                    'package_life_time'       => $package_life_time,
                    'package_size'            => $package_size,
                    'package_unassigned'      => $package_unassigned,
                    'appointments_limit'      => $appointments_limit,
                    'limit_period'            => $limit_period,
                    'staff_preference'        => $staff_preference,
                    'recurrence_enabled'      => $recurrence_enabled,
                    'recurrence_frequencies'  => $recurrence_frequencies,
                    'visibility'              => $visibility,
                    'position'                => $position
                ],
                [ 'id' => $serviceid ],
                [
                    '%d',
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
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
                    '%s',
                    '%d',
                ],
                ['%d']
            );

            if ($wpdb->last_error) {
                $response = [
                  'Error'      => $wpdb->last_error,
                  'status'     => 401
                ];
                return new WP_REST_Response($response);
            } else {
                if (is_array($staff_ids) && count($staff_ids) > 0) {
                    foreach ($staff_ids as $sid) {
                        $wpdb->insert(
                            $this->staff_services_table,
                            [
                              'staff_id'     => $sid,
                              'service_id'   => $serviceid,
                              'price'        => $price,
                              'deposit'      => "100%",
                              'capacity_min' => $capacity_min,
                              'capacity_max' => $capacity_max
                            ],
                            [
                              '%d',
                              '%d',
                              '%s',
                              '%s',
                              '%d',
                              '%d',
                            ]
                        );
                    }
                }

                $sql        = "SELECT * FROM $this->services_table where id = $serviceid";
                $result     = $wpdb->get_row($sql, ARRAY_A);

                $response = [
                  'message'       => 'Service Updated.',
                  'service_object'=> $result,
                  'status'        => 200
                ];
                return new WP_REST_Response($response, 200);
            }
        } else {
            return new WP_REST_Response('', 404);
        }
    }

    /**
     * Get a single service data object.
     * @param  WP_REST_Request $request
     * @return mixed
     */
    public function show(WP_REST_Request $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $serviceid  = $params['id'];
        $sql        = "SELECT * FROM $this->services_table where id = $serviceid";
        $result     = $wpdb->get_row($sql, ARRAY_A);

        if (!(is_array($result) && count($result) > 0)) {
            return new WP_REST_Response('', 404);
        }
        $lists = [];
        if (isset($result['category_id']) && !empty($result['category_id'])) {
            $staffsql              = "SELECT id, name FROM $this->category_table where id = ".$result['category_id'];
            $staffresult           = $wpdb->get_row($staffsql, ARRAY_A);
            $result['category_id'] = $staffresult;
        }

        $lists = $result;

        return new WP_REST_Response($lists);
    }

    /**
     * Delete a service object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function destroy(WP_REST_Request $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $serviceId  = $params['id'];

        $id = $wpdb->delete($this->services_table, [ 'id' => $serviceId ], [ '%d' ]);

        if (!$id) {
            return new WP_REST_Response('', 404);
        }

        return new WP_REST_Response();
    }
}
