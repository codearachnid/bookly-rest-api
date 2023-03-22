<?php

/**
 * @package    bookly
 * @subpackage Bookly_Rest_Api/public
 * @author     PDG Solutions <info@pdg.solutions>
 */
class BooklyStaffs
{
    /**
     * Database table name.
     * @var string
     */
    private $staff_table;
    private $staff_services_table;
    private $service_table;

    public function __construct($staff_table, $service_table, $staff_services_table)
    {
        $this->staff_table          = $staff_table;
        $this->service_table        = $service_table;
        $this->staff_services_table = $staff_services_table;
    }

    /**
     * Get a paginated array of staffs objects.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function index(WP_REST_Request $request)
    {
        global $wpdb;

        $params = $request->get_params();
        $where  = '';
        if (isset($params['service_id'])) {
            $where = $wpdb->prepare('where s.id = %d', $params['service_id']);
        }

        $sql = "SELECT st.* FROM $this->staff_table AS st
                LEFT JOIN $this->staff_services_table AS ss ON ss.staff_id = st.id
                LEFT JOIN $this->service_table AS s ON s.id = ss.service_id $where
                GROUP BY st.id";

        $result = $wpdb->get_results($sql, ARRAY_A);

        return new WP_REST_Response($result);
    }

    /**
     * Store a single staff object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function store(WP_REST_Request $request)
    {
        global $wpdb;
        $params     = $request->get_params();
        $email      = $params['email'];
        $full_name  = $params['full_name'];

        if (empty($email) || empty($full_name)) {
            $response = [
                'message'       => 'full_name and email are required field to create Staff',
                'status'        => 422
            ];
            return new WP_REST_Response($response, 422);
        }
        $userid = $attachment_id = $full_name = $email = $phone = $info = $visibility = $position = $google_data = '';

        if (isset($params['email']) && !empty($params['email'])) {
            $email = sanitize_email($params['email']);

            $staffdata = $wpdb->get_col($wpdb->prepare("SELECT id  FROM $this->staff_table WHERE email = %s ", $email));

            if (count($staffdata) > 0) {
                $response= [
                    'message'   => 'Staff Exits with this email',
                    'staffid'   => $staffdata,
                    'status'    => 422
                ];
                return new WP_REST_Response($response, 422);
            }
        }

        if (isset($params['wp_user_id']) && !empty($params['wp_user_id'])) {
            $userid = sanitize_text_field($params['wp_user_id']);
        }

        if (isset($params['profile_image']) && !empty($params['profile_image'])) {
            $profile_image = sanitize_text_field($params['profile_image']);
            $attachment_id = $this->insertAttachmentFromUrl($profile_image);
        }

        if (isset($params['full_name']) && !empty($params['full_name'])) {
            $full_name = sanitize_text_field($params['full_name']);
        }

        if (isset($params['phone']) && !empty($params['phone'])) {
            $phone = sanitize_text_field($params['phone']);
        }

        if (isset($params['info']) && !empty($params['info'])) {
            $info = sanitize_text_field($params['info']);
        }

        if (isset($params['visibility']) && !empty($params['visibility'])) {
            $visibility = sanitize_text_field($params['visibility']);
        }

        if (isset($params['position']) && !empty($params['position'])) {
            $position = sanitize_text_field($params['position']);
        }

        if (isset($params['google_data']) && !empty($params['google_data'])) {
            $google_data = sanitize_text_field($params['google_data']);
        }

        $wpdb->insert(
            $this->staff_table,
            [
                'wp_user_id'    => $userid,
                'attachment_id' => $attachment_id,
                'full_name'     => $full_name,
                'email'         => $email,
                'phone'         => $phone,
                'info'          => $info,
                'visibility'    => $visibility,
                'position'      => $position,
                'google_data'   => $google_data
            ],
            [
                '%d',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s'
            ]
        );

        $staffid = $wpdb->insert_id;
        if ($staffid) {
            $sql      = "SELECT * FROM $this->staff_table where id = $staffid ";
            $result   = $wpdb->get_row($sql, ARRAY_A);
            $response = [
                'message'      => 'Staff Created Successfully.',
                'staff_object' => $result,
                'status'       => 200
            ];
            return new WP_REST_Response($response, 200);
        } else {
            $response = [
                'message' => 'Error while creating Staff.',
                'status'  => 401
            ];
            return new WP_REST_Response($response, 401);
        }
    }

    /**
    * Get single staff data object.
    * @param  WP_REST_Request $request
    * @return mixed
    */
    public function show(WP_REST_Request $request)
    {
        global $wpdb;
        $params   = $request->get_params();
        $staffid  = $params['id'];
        $sql      = "SELECT * FROM $this->staff_table where id = $staffid ";
        $result   = $wpdb->get_row($sql, ARRAY_A);

        if (count($result) <= 0) {
            return new WP_REST_Response($result, 404);
        }

        return new WP_REST_Response($result);
    }

    /**
     * Update a single staff object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update(WP_REST_Request $request)
    {
        global $wpdb;
        $params   = $request->get_params();
        $staffid  = $params['id'];
        $sql      = "SELECT * FROM $this->staff_table where id = $staffid ";
        $result   = $wpdb->get_row($sql, ARRAY_A);

        if (is_array($result) && count($result) > 0) {
            $userid        = $result['wp_user_id'];
            $email         = $result['email'];
            $full_name     = $result['full_name'];
            $attachment_id = $result['attachment_id'];
            $phone         = $result['phone'];
            $info          = $result['info'];
            $visibility    = $result['visibility'];
            $position      = $result['position'];
            $google_data   = $result['google_data'];

            if (isset($params['wp_user_id']) && !empty($params['wp_user_id'])) {
                $userid = sanitize_text_field($params['wp_user_id']);
            }

            if (isset($params['profile_image']) && !empty($params['profile_image'])) {
                $profile_image = sanitize_text_field($params['profile_image']);
                $attachment_id = $this->insertAttachmentFromUrl($profile_image);
            }

            if (isset($params['full_name']) && !empty($params['full_name'])) {
                $full_name = sanitize_text_field($params['full_name']);
            }

            if (isset($params['phone']) && !empty($params['phone'])) {
                $phone = sanitize_text_field($params['phone']);
            }

            if (isset($params['info']) && !empty($params['info'])) {
                $info = sanitize_text_field($params['info']);
            }

            if (isset($params['visibility']) && !empty($params['visibility'])) {
                $visibility = sanitize_text_field($params['visibility']);
            }

            if (isset($params['position']) && !empty($params['position'])) {
                $position = sanitize_text_field($params['position']);
            }

            if (isset($params['google_data']) && !empty($params['google_data'])) {
                $google_data = sanitize_text_field($params['google_data']);
            }

            $wpdb->update(
                $this->staff_table,
                [
                    'wp_user_id'    => $userid,
                    'attachment_id' => $attachment_id,
                    'full_name'     => $full_name,
                    'email'         => $email,
                    'phone'         => $phone,
                    'info'          => $info,
                    'visibility'    => $visibility,
                    'position'      => $position,
                    'google_data'   => $google_data
                ],
                ['id'=> $staffid],
                [
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s'
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
                $sql        = "SELECT * FROM $this->staff_table where id = $staffid";
                $result     = $wpdb->get_row($sql, ARRAY_A);

                $response = [
                    'message'      => 'Staff Details Updated.',
                    'staff_object' => $result,
                    'status'       => 200
                ];
                return new WP_REST_Response($response, 200);
            }
        } else {
            $response= [
                'message'   => 'Please check Staff ID again.',
                'status'    => 404
            ];
            return new WP_REST_Response($response, 404);
        }
    }


    /**
     * Delete a staff object.
     * @param  WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function destroy(WP_REST_Request $request)
    {
        global $wpdb;
        $params   = $request->get_params();
        $staffid  = $params['id'];

        $sid = $wpdb->delete($this->staff_table, [ 'id' => $staffid ], [ '%d' ]);

        if (!$id) {
            return new WP_REST_Response('', 404);
        }

        return new WP_REST_Response();
    }

    private function insertAttachmentFromUrl($url, $post_id = null)
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        if (!class_exists('WP_Http')) {
            include_once(ABSPATH . WPINC . '/class-http.php');
        }
        $http     = new WP_Http();
        $response = $http->request($url);
        if ($response['response']['code'] != 200) {
            return false;
        }

        $upload = wp_upload_bits(basename($url), null, $response['body']);

        if (!empty($upload['error'])) {
            return false;
        }

        $file_path        = $upload['file'];
        $file_name        = basename($file_path);
        $file_type        = wp_check_filetype($file_name, null);
        $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
        $wp_upload_dir    = wp_upload_dir();

        $post_info = [
            'guid'            => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type'  => $file_type['type'],
            'post_title'      => $attachment_title,
            'post_content'    => '',
            'post_status'     => 'inherit',
        ];

        $attach_id   = wp_insert_attachment($post_info, $file_path, $post_id);
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }
}
