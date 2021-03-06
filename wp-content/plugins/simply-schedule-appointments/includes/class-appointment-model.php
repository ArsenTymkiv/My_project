<?php
/**
 * Simply Schedule Appointments Appointments Model.
 *
 * @since   0.0.3
 * @package Simply_Schedule_Appointments
 */
use League\Period\Period;

/**
 * Simply Schedule Appointments Appointments Model.
 *
 * @since 0.0.3
 */
class SSA_Appointment_Model extends SSA_Db_Model {
	protected $slug = 'appointment';
	protected $version = '2.2.2';

	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.2
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.0.2
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		// $this->version = $this->version.'.'.time(); // dev mode
		parent::__construct( $plugin );

		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.2
	 */
	public function hooks() {
		add_filter( 'ssa/appointment/before_insert', array( $this, 'default_appointment_status' ), 5, 1 );

		add_filter( 'ssa/appointment/before_update', array( $this, 'merge_customer_information' ), 10, 3 );

		add_filter( 'ssa/appointment/after_insert', array( $this, 'update_rescheduled_to_appointment_id' ), 10, 3 );
	}

	public static function get_booked_statuses() {
		return array( 'booked' );
	}
	public static function is_a_booked_status( $status ) {
		return in_array( $status, self::get_booked_statuses() );
	}

	public static function get_reserved_statuses() {
		return array( 'pending_payment', 'pending_form' );
	}
	public static function is_a_reserved_status( $status ) {
		return in_array( $status, self::get_reserved_statuses() );
	}

	public static function get_canceled_statuses() {
		return array( 'canceled' );
	}

	public static function get_unavailable_statuses() {
		return array_merge(
			self::get_booked_statuses(),
			self::get_reserved_statuses()
		);
	}
	public static function is_a_unavailable_status( $status ) {
		return in_array( $status, self::get_unavailable_statuses() );
	}
	public static function is_a_available_status( $status ) {
		return ! self::is_a_unavailable_status();
	}

	public function merge_customer_information( $data, $data_before, $appointment_id ) {
		if ( empty( $data['customer_information'] ) ) {
			return $data;
		}

		if ( empty( $data_before['customer_information'] ) ) {
			$data_before['customer_information'] = array();
		}

		$data['customer_information'] = array_merge( $data_before['customer_information'], $data['customer_information'] );

		return $data;
	}

	public function default_appointment_status( $data ) {
		// We want to allow "pending_form" status if it's provided
		if ( !empty( $data['status'] ) && $data['status'] === 'pending_form' ) {
			return $data;
		}

		$data['status'] = 'booked';
		return $data;
	}

	public function debug() {
	}

	public function belongs_to() {
		return array(
			// 'Author' => array(
			// 	'model' => 'WP_User_Model',
			// 	'foreign_key' => 'author_id',
			// ),
			'AppointmentType' => array(
				'model' => $this->plugin->appointment_type_model,
				'foreign_key' => 'appointment_type_id',
			),
		);
	}

	public function has_many() {
		return array(
			'Payment' => array(
				'model' => $this->plugin->payment_model,
				'foreign_key' => 'appointment_id',
			),
		);
	}

	protected $schema = array(
		'appointment_type_id' => array(
			'field' => 'appointment_type_id',
			'label' => 'Appointment Type ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'rescheduled_from_appointment_id' => array(
			'field' => 'rescheduled_from_appointment_id',
			'label' => 'Rescheduled from Appointment ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'rescheduled_to_appointment_id' => array(
			'field' => 'rescheduled_to_appointment_id',
			'label' => 'Rescheduled to Appointment ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'group_id' => array(
			'field' => 'group_id',
			'label' => 'Group ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'author_id' => array(
			'field' => 'author_id',
			'label' => 'Author ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'customer_id' => array(
			'field' => 'customer_id',
			'label' => 'Customer ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'customer_information' => array(
			'field' => 'customer_information',
			'label' => 'Customer Information',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
			'encoder' => 'json',
		),
		'customer_timezone' => array(
			'field' => 'customer_timezone',
			'label' => 'Customer Timezone',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'customer_locale' => array(
			'field' => 'customer_locale',
			'label' => 'Customer Locale',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'start_date' => array(
			'field' => 'start_date',
			'label' => 'Start Date',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'datetime',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'end_date' => array(
			'field' => 'end_date',
			'label' => 'End Date',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'datetime',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'title' => array(
			'field' => 'title',
			'label' => 'Title',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'description' => array(
			'field' => 'description',
			'label' => 'Description',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TEXT',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'price_full' => array(
			'field' => 'price_full',
			'label' => 'Price Full',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'DECIMAL(9,2)',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'payment_method' => array(
			'field' => 'payment_method',
			'label' => 'Payment Method',
			'default_value' => '',
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'payment_received' => array(
			'field' => 'payment_received',
			'label' => 'Payment Received',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'DECIMAL(9,2)',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'mailchimp_list_id' => array(
			'field' => 'mailchimp_list_id',
			'label' => 'MailChimp List ID',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'google_calendar_id' => array(
			'field' => 'google_calendar_id',
			'label' => 'Google Calendar ID',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'google_calendar_event_id' => array(
			'field' => 'google_calendar_event_id',
			'label' => 'Google Calendar Event ID',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'web_meeting_url' => array(
			'field' => 'web_meeting_url',
			'label' => 'Google Calendar Event Meet Url',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TINYTEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'allow_sms' => array(
			'field' => 'allow_sms',
			'label' => 'Allow SMS',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '6',
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'status' => array(
			'field' => 'status',
			'label' => 'Status',
			'default_value' => 'booked',
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '16',
			'mysql_unsigned' => false,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'date_created' => array(
			'field' => 'date_created',
			'label' => 'Date Created',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'datetime',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'date_modified' => array(
			'field' => 'date_modified',
			'label' => 'Date Modified',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'datetime',
			'mysql_length' => '',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
	);

	public $indexes = array(
		'customer_id' => [ 'customer_id' ],
		'start_date' => [ 'start_date' ],
		'end_date' => [ 'end_date' ],
		'status' => [ 'status' ],
		'date_created' => [ 'date_created' ],
	);

	public function filter_where_conditions( $where, $args ) {
		if ( !empty( $args['customer_id'] ) ) {
			$where .= ' AND customer_id="'.sanitize_text_field( $args['customer_id'] ).'"';
		}

		if ( !empty( $args['group_id'] ) ) {
			$where .= ' AND group_id="'.sanitize_text_field( $args['group_id'] ).'"';
		}

		global $wpdb;

		if( ! empty( $args['appointment_type_id'] ) ) {
			if ( is_array( $args['appointment_type_id'] ) ) {
				$where .= ' AND (';
					foreach ($args['appointment_type_id'] as $key => $appointment_type_id) {
						$where .= $wpdb->prepare( "`appointment_type_id` = '" . '%s' . "' ", $appointment_type_id );
						if ( $key + 1 < count( $args['appointment_type_id'] ) ) {
							$where .= 'OR ';
						}
					}
				$where .= ') ';
			} else {
				$where .= $wpdb->prepare( " AND `appointment_type_id` = '" . '%s' . "' ", sanitize_text_field( $args['appointment_type_id'] ) );
			}
		}

		if( ! empty( $args['exclude_ids'] ) ) {
			if ( is_array( $args['exclude_ids'] ) ) {
				$where .= ' AND (';
				$where .= $wpdb->prepare( "`id` NOT IN (".implode(', ', array_fill(0, count($args['exclude_ids']), '%d') ).")", $args['exclude_ids'] );
				$where .= ') ';
			} else {
				$where .= $wpdb->prepare( " AND `id` != '" . '%d' . "' ", sanitize_text_field( $args['exclude_ids'] ) );
			}
		}

		if ( isset( $args['intersects_period'] ) ) {
			if ( $args['intersects_period'] instanceof Period ) {
				$start_date_string = $args['intersects_period']->getStartDate()->format( 'Y-m-d H:i:s' );
				$end_date_string = $args['intersects_period']->getEndDate()->format( 'Y-m-d H:i:s' );

				// it should END in the queried period
				// OR 
				// it should START in the queried period
				// OR
				// it should CONTAIN the queried period
				$where .= " AND (
					(end_date >= '{$start_date_string}' AND end_date <= '{$end_date_string}' )
					OR
					(start_date <= '{$end_date_string}' AND start_date >= '{$start_date_string}' )
					OR
					(start_date <= '{$start_date_string}' AND end_date >= '{$end_date_string}' )
				)";
			}
		}

		return $where;
	}

	public function create_item_permissions_check( $request ) {
		return $this->nonce_permissions_check( $request );
	}

	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		if ( true === $this->get_item_permissions_check( $request ) ) {
			return true;
		}

		return false;
	}


	public function group_cancel( $request ) {
		$params = $request->get_params();
		if ( empty( $params['id'] ) ) {
			return;
		}

		$appointment_arrays = $this->query( array(
			'number' => -1,
			'group_id' => $params['id'],
		) );

		foreach ($appointment_arrays as $appointment_array) {
			if ( 'canceled' === $appointment_array['status'] ) {
				continue;
			}

			$this->update( $appointment_array['id'], array(
				'status' => 'canceled',
			) );
		}

		return true;
	}

	public function group_delete( $request ) {
		$params = $request->get_params();
		if ( empty( $params['id'] ) ) {
			return;
		}

		$appointment_arrays = $this->query( array(
			'number' => -1,
			'group_id' => $params['id'],
		) );

		foreach ($appointment_arrays as $appointment_array) {
			$this->delete( $appointment_array['id'] );
		}

		return true;
	}

	public function create_item( $request ) {
		$params = $request->get_params();
		$params = shortcode_atts( array_merge( $this->get_field_defaults(), array(
			'appointment_type_id' => '',
			'rescheduled_to_appointment_id' => '',
			'start_date' => '',
			'customer_information' => array(),
			'post_information' => array(),
			'customer_id' => 0,
			'fetch' => array(),
		) ), $params );

		if ( ! empty( $params['customer_information']['Email'] ) ) {
			$user_by_email = get_user_by( 'email', sanitize_text_field( $params['customer_information']['Email'] ) );
			if ( ! empty( $user_by_email ) ) {
				$params['customer_id'] = $user_by_email->ID;
			}
		}

		if ( empty( $params['customer_id'] ) ) {
			if ( ! current_user_can( 'edit_users' ) && ! current_user_can( 'ssa_manage_staff' ) ) {
				$params['customer_id'] = get_current_user_id();
			}
		}

		$request->set_body_params( $params );

		// Double check availability before we insert
		$appointment_type = SSA_Appointment_Type_Object::instance( $params['appointment_type_id'] );

			$start_date = new DateTimeImmutable( $params['start_date'] );
			$period = new Period(
				$start_date->sub( $appointment_type->get_buffered_duration_interval() ),
				$start_date->add( $appointment_type->get_buffered_duration_interval() )
			);

			$availability_query = new SSA_Availability_Query(
				$appointment_type,
				$period,
				array(
					'cache_level_read' => false, // check the database, bypass cache
					'cache_level_write' => false, // don't cache the narrow-range query
				)
			);

			$prospective_appointment = SSA_Appointment_Factory::create( $appointment_type, array(
				'id' => 0,
				'start_date' => $start_date->format( 'Y-m-d H:i:s' ),
			) );
			$is_period_available = $availability_query->is_prospective_appointment_bookable( $prospective_appointment );


		if ( empty( $is_period_available ) ) {
			return array(
				'error' => array(
					'code' => 'appointment_unavailable',
					'message' => __( 'Sorry, that time was just booked and is no longer available', 'simply-schedule-appointments' ),
					'data' => array(),
				),
			);
		}

		// if ( apply_filters( 'ssa/scalability/preemptively_clear_cache', false ) ) {
		// 	$this->plugin->availability_cache_invalidation->invalidate_prospective_appointment( $prospective_appointment );
		// }

		$response = parent::create_item( $request );

		if ( is_a( $response->data['data'], 'WP_Error' ) ) {
			return $response;
		}
		if ( ! empty( $response->data['error'] ) ) {
			if ( $response->data['error'] == 'Not created' ) {
				if ( current_user_can( 'ssa_manage_appointments' ) ) {
					$response->data['error'] = __( 'Could not insert appointment into database.', 'simply-schedule-appointments' );
				} else {
					$response->data['error'] = __( 'There was a problem booking your appointment.', 'simply-schedule-appointments' );
				}
			}
			return $response;
		}

		$appointment_object = new SSA_Appointment_Object( $response->data['data']['id'] );
		$response->data['data'] = $appointment_object->get_data( 0, $params['fetch'] );

		//Store booking page information
		if( isset( $params['post_information'] ) ) {
			$appointment_meta = array();

			if( isset( $params['post_information']['booking_url'] ) && ! empty( $params['post_information']['booking_url'] ) ) {
				$appointment_meta['booking_url'] = esc_url( $params['post_information']['booking_url'] );
			}
			if( isset( $params['post_information']['booking_post_id'] ) && ! empty( $params['post_information']['booking_post_id'] ) ) {
				$appointment_meta['booking_post_id'] = intval( $params['post_information']['booking_post_id'] );
			}
			if( isset( $params['post_information']['booking_title'] ) && ! empty( $params['post_information']['booking_title'] ) ) {
				$appointment_meta['booking_title'] = html_entity_decode( urldecode( esc_attr( $params['post_information']['booking_title'] ) ) );
			}

			if( ! empty( $appointment_meta ) ) {
				$this->update_metas( $response->data['data']['id'], $appointment_meta );
			}
		}

		// $response->data['data']['ics']['customer'] = $appointment_object->get_ics( 'customer' )['file_url'];

		// if ( current_user_can( 'ssa_manage_site_settings' ) ) {
		// 	$response->data['data']['ics']['staff'] = $appointment_object->get_ics( 'staff' )['file_url'];
		// }

		// $response->data['data']['gcal']['customer'] = $appointment_object->get_gcal_add_link( 'customer' );

		return $response;
	}

	public function update_item( $request ) {
		$item_id = $request['id'];
		$params = $request->get_params();
		if ( empty( $params['fetch'] ) ) {
			$params['fetch'] = array();
			$request->set_param( 'fetch', $params['fetch'] );
		}

		// TODO: allow staff_id to be specified by booking app
		// if ( isset( $params['staff_ids'] ) ) {
		// 	if ( ! current_user_can( 'ssa_manage_others_appointments' ) ) {
		// 		$params['staff_ids'] = false;
		// 	}
		// }

		if ( ! empty( $params['appointment_type_id'] ) && ! empty( $params['start_date'] ) ) {	
			$appointment_type = $this->plugin->appointment_type_model->get( $params['appointment_type_id'] );

			if ( empty( $params['start_date'] ) ) {
				if ( isset( $params['start_date'] ) ) {
					unset( $params['start_date'] );
				}
				if ( isset( $params['end_date'] ) ) {
					unset( $params['end_date'] );
				}
			} else {			
				try {
					$start_date = ssa_datetime( $params['start_date'] );
					if ( empty( $start_date ) ) {
						return 'invalid_start_date';
					}
				} catch (Exception $e) {
					return 'invalid_start_date';
				}

				$bookable_period = Period::createFromDuration( $start_date, new DateInterval('PT'.$appointment_type['duration'].'M'));

				$params['end_date'] = $bookable_period->getEndDate()->format( 'Y-m-d H:i:s' );
				$request->set_param( 'end_date', $params['end_date'] );
			}

		}
		$response = parent::update_item( $request );

		$appointment_object = new SSA_Appointment_Object( $response->data['data']['id'] );
		$response->data['data'] = $appointment_object->get_data( 0, $params['fetch'] );

		if ( is_a( $response->data['data'], 'WP_Error' ) ) {
			return $response;
		}

		return $response;
	}


	public function register_custom_routes() {
		$namespace = $this->api_namespace.'/v' . $this->api_version;
		$base = $this->get_api_base();

		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)/ics', array(
			array(
				'methods'         => WP_REST_Server::READABLE,
				'callback'        => array( $this, 'get_item_ics' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'            => array(
					'context'          => array(
						'default'      => 'view',
					),
				),
			),
		) );

		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)/meta', array(
			array(
				'methods'         => WP_REST_Server::READABLE,
				'callback'        => array( $this, 'get_item_meta' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'            => array(
					'context'          => array(
						'default'      => 'view',
					),
				),
			),
		) );

		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)/meta', array(
			array(
				'methods'         => WP_REST_Server::CREATABLE,
				'callback'        => array( $this, 'update_item_meta' ),
				'permission_callback' => '__return_false',
				'args'            => array(
					'context'          => array(
						'default'      => 'view',
					),
				),
			),
		) );

		register_rest_route( $namespace, '/' . $base . '/groups/(?P<id>[\d]+)/cancel', array(
			array(
				'methods'         => WP_REST_Server::CREATABLE,
				'callback'        => array( $this, 'group_cancel' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'            => array(
					'context'          => array(
						'default'      => 'view',
					),
				),
			),
		) );

		register_rest_route( $namespace, '/' . $base . '/groups/(?P<id>[\d]+)/delete', array(
			array(
				'methods'         => WP_REST_Server::CREATABLE,
				'callback'        => array( $this, 'group_delete' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'            => array(
					'context'          => array(
						'default'      => 'view',
					),
				),
			),
		) );
	}

	public function verify_id_token( $appointment, $token_to_verify ) {
		$correct_token = $this->get_id_token( $appointment );

		if ( $correct_token == $token_to_verify ) {
			return true;
		}

		return false;
	}


	public function get_id_token( $appointment ) {
		if ( ! is_array( $appointment ) && ! is_a( $appointment, 'WP_REST_Request' ) && (int)$appointment == $appointment && $appointment > 0 ) {
			$appointment = array( 'id' => $appointment );
		}

		if ( empty( $appointment['id'] ) ) {
			return false;
		}

		$appointment_id = sanitize_text_field( $appointment['id'] );
		$string_to_tokenize = $appointment_id;

		if ( empty( $appointment['appointment_type_id'] ) ) {
			$appointment = $this->get( $appointment['id'], -1 );
		}

		if ( !empty( $appointment['id'] ) && !empty( $appointment['appointment_type_id'] ) ) {
			$string_to_tokenize .=  $appointment['appointment_type_id'];
		}

		return SSA_Utils::hash( $string_to_tokenize );
	}

	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$params = $request->get_params();
		if ( is_user_logged_in() && ! current_user_can( 'ssa_manage_others_appointments' ) ) {

			global $wpdb;
			if ( ! current_user_can( 'ssa_manage_appointments' ) ) {
				$params['customer_id'] = get_current_user_id();
			} else {
				$params['append_where_sql'] = $wpdb->prepare( ' AND id IN (SELECT appointment_id FROM ' . ssa()->staff_appointment_model->get_table_name() . ' WHERE staff_id = %d)', $this->plugin->staff_model->get_staff_id_for_user_id( get_current_user_id() ) );
			}
		}

		$schema = $this->get_schema();

		$data = $this->query( $params );
		$data = $this->prepare_collection_for_api_response( $data );

		$response = array(
			'response_code' => 200,
			'error' => '',
			'data' => $data,
		);

		return new WP_REST_Response( $response, 200 );
	}

	public function get_items_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_others_appointments' ) ) {
			return true;
		}

		if ( current_user_can( 'ssa_manage_appointments' ) ) {
			return true;
		}

		$params = $request->get_params();
		if ( true === parent::get_item_permissions_check( $request ) ) {
			return true;
		}

		if ( true === $this->id_token_permissions_check( $request ) ) {
			return true;
		}

		return false;
	}

	public function get_item_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_others_appointments' ) ) {
			return true;
		}

		if ( current_user_can( 'ssa_manage_appointments' ) ) {
			return true;
		}

		$params = $request->get_params();
		if ( true === parent::get_item_permissions_check( $request ) ) {
			return true;
		}

		if ( true === $this->id_token_permissions_check( $request ) ) {
			return true;
		}

		if ( is_user_logged_in() ) {
			$appointment = new SSA_Appointment_Object( $params['id'] );
			if ( $appointment->customer_id == get_current_user_id() ) {
				return true;
			}
		}

		return apply_filters( 'ssa/appointment/get_item_permissions_check', false, $params, $request );
	}

	public function get_item_ics( $request ) {
		$params = $request->get_params();
		$appointment_object = new SSA_Appointment_Object( $params['id'] );
		$ics_exporter = $appointment_object->get_ics_exporter();
		$customer_ics = $ics_exporter->get_ics_for_appointment( $appointment_object, 'customer' );
		$response = array(
			'customer' => $customer_ics,
		);

		if ( current_user_can( 'ssa_manage_appointments' ) ) {
			$staff_ics = $ics_exporter->get_ics_for_appointment( $appointment_object, 'staff' );
			$response['staff'] = $staff_ics;
		}

		return $response;
	}

	public function insert( $data, $type = '' ) {
		$response = array();

		$wp_error = new WP_Error();
		if ( empty( $data['appointment_type_id'] ) ) {
			$wp_error->add( 422, 'appointment_type_id required' );
		}
		if ( empty( $data['start_date'] ) ) {
			$wp_error->add( 422, 'start_date required' );
		}
		if ( empty( $data['customer_information'] ) ) {
			if ( empty( $data['status'] ) || $data['status'] !== 'pending_form' ) {
				$wp_error->add( 422, 'customer_information required' );
			}
		}

		if ( !empty( $wp_error->errors ) ) {
			return $wp_error;
		}

		ssa_defensive_timezone_fix();
		$data['appointment_type_id'] = sanitize_text_field( $data['appointment_type_id'] );
		$data['start_date'] = sanitize_text_field( $data['start_date'] );
		if ( empty( $data['start_date'] ) ) {
			return 'invalid_start_date';
		}
		try {
			$start_date = ssa_datetime( $data['start_date'] );
			if ( empty( $start_date ) ) {
				return 'invalid_start_date';
			}
		} catch (Exception $e) {
			return 'invalid_start_date';
		}

		$appointment_type = $this->plugin->appointment_type_model->get( $data['appointment_type_id'] );
		$bookable_period = Period::createFromDuration( $start_date, new DateInterval('PT'.$appointment_type['duration'].'M'));
		$data['end_date'] = $bookable_period->getEndDate()->format( 'Y-m-d H:i:s' );
		if ( false !== strpos( $data['customer_timezone'], 'Etc/' ) ) {
			$data['customer_timezone'] = '';
		}

		$appointment_id = parent::insert( $data, $type );

		ssa_defensive_timezone_reset();
		return $appointment_id;
	}

	public function get_staff_ids( $appointment_id, $appointment=array() ) {
		if ( ! $this->plugin->settings_installed->is_enabled( 'staff' ) ) {
			return array();
		}

		$cache_key = 'appointment/'.$appointment_id.'/staff_ids';
		$cached = ssa_cache_get( $cache_key );
		if ( $cached !== false ) {
			return $cached;
		}

		$data = $this->plugin->staff_appointment_model->get_staff_ids( $appointment_id );
		ssa_cache_set(
			$cache_key,
			$data,
			'',
			5
		);

		return $data;
	}

	public function get_public_edit_url( $appointment_id, $appointment=array() ) {
		$appointment['id'] = $appointment_id;
		$token = $this->get_id_token( $appointment );
		$url = untrailingslashit( add_query_arg( array(
			'appointment_action' => 'edit',
			'appointment_token' => $token . $appointment_id,
		), home_url() ) );
		return $url;
	}

	public function deprecated_get_public_edit_url( $appointment_id, $appointment=array() ) {
		$appointment['id'] = $appointment_id;
		$token = $this->get_id_token( $appointment );
		$url = home_url( trailingslashit( $this->plugin->shortcodes->get_appointment_edit_permalink() ) . $token . $appointment_id );
		return $url;
	}

	public function get_admin_edit_url( $appointment_id, $appointment=array() ) {
		$url = $this->plugin->wp_admin->url( 'ssa/appointment/' . $appointment_id );
		return $url;
	}

	public function prepare_item_for_response( $item, $recursive=0 ) {
		$item = parent::prepare_item_for_response( $item, $recursive );

		if ( $recursive >= 0 ) {
			$item['public_edit_url'] = $this->get_public_edit_url( $item['id'], $item );
			$item['public_token'] = $this->get_id_token( $item['id'] );
			$item['staff_ids'] = $this->get_staff_ids( $item['id'] );
		}

		return $item;
	}

	public function get_item_meta( $request ) {
		$params = $request->get_params();
		$appointment_id = esc_attr( $params['id'] );

		$data = array();
		if ( empty( $params['keys'] ) ) {
			$data = $this->get_metas( $appointment_id );
		} else if ( is_string( $params['keys'] ) ) {
			$data = array(
				$params['keys'] => $this->get_meta( $appointment_id, $params['keys'] )
			);
		} else if ( is_array( $params['keys'] ) ) {
			$data = $this->get_metas( $appointment_id, $params['keys'] );
		}

		$response = array(
			'response_code' => 200,
			'error' => '',
			'data' => $data,
		);

		return new WP_REST_Response( $response, 200 );
	}

	public function update_item_meta( $request ) {
		$params = $request->get_params();
		$appointment_id = esc_attr( $params['id'] );

		$meta_keys_and_values = array();
		$excluded_keys = array( 'id', 'context' );
		foreach ($params as $key => $value) {
			if ( in_array( $key, $excluded_keys ) ) {
				continue;
			}

			$meta_keys_and_values[$key] = esc_attr( $value );
		}

		$this->update_metas( $appointment_id, $meta_keys_and_values );

		$response = array(
			'response_code' => 200,
			'error' => '',
			'data' => $meta_keys_and_values,
		);

		return new WP_REST_Response( $response, 200 );
	}

	public function get_metas( $appointment_id, array $meta_keys = array() ) {
		$data = array();

		if ( empty( $meta_keys ) ) {
			// return all keys and values
			$rows = $this->plugin->appointment_meta_model->query( array(
				'appointment_id' => $appointment_id,
			) );
			foreach ($rows as $key => $row) {
				$data[$row['meta_key']] = $row['meta_value'];
			}
		}

		if ( count( $meta_keys ) > 3 ) {
			// For performance, perform single SQL query and filter in PHP
			// instead of running lots of individual queries against meta table
			$rows = $this->plugin->appointment_meta_model->query( array(
				'appointment_id' => $appointment_id,
			) );

			foreach ($rows as $key => $row) {
				if ( ! empty( $meta_keys ) && ! in_array( $row['meta_key'], $meta_keys ) ) {
					continue; // request only asked for certain keys and this isn't one of them
				}

				$data[$row['meta_key']] = $row['meta_value'];
			}

			foreach ($meta_keys as $key) {
				if ( ! isset( $data[$key] ) ) {
					$data[$key] = null;
				}
			}
			foreach ($data as $key => $value) {
				if ( ! in_array( $key, $meta_keys ) ) {
					unset( $data[$key] );
				}
			}
		} else {
			foreach ($meta_keys as $meta_key) {
				$data[$meta_key] = $this->get_meta( $appointment_id, $meta_key );
			}
		}

		return $data;
	}

	public function get_meta( $appointment_id, $meta_key ) {
		$data = $this->plugin->appointment_meta_model->query( array(
			'appointment_id' => $appointment_id,
			'meta_key' => $meta_key,
			'order_by' => 'id',
			'order' => 'DESC',
			'limit' => 1,
		) );

		if ( empty( $data['0'] ) ) {
			return null;
		}

		return $data['0']['meta_value'];
	}

	public function add_meta( $appointment_id, $meta_key, $meta_value ) {
		$this->plugin->appointment_meta_model->insert( array(
			'appointment_id' => $appointment_id,
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
		) );
	}

	public function update_metas( $appointment_id, array $meta_keys_and_values ) {
		// TODO: bulk delete in single SQL statement
		// TODO: bulk insert in single SQL statement
		foreach ($meta_keys_and_values as $meta_key => $meta_value) {
			$this->update_meta( $appointment_id, $meta_key, $meta_value );
		}
	}

	public function update_meta( $appointment_id, $meta_key, $meta_value ) {
		$this->delete_meta( $appointment_id, $meta_key );
		$this->add_meta( $appointment_id, $meta_key, $meta_value );
	}

	public function delete_meta( $appointment_id, $meta_key ) {
		$this->plugin->appointment_meta_model->bulk_delete( array(
			'appointment_id' => $appointment_id,
			'meta_key' => $meta_key,
		) );
	}

	public function delete_abandoned( DateTimeImmutable $date_modified_max = null ) {
		global $wpdb;
		if ( empty( $date_modified_max ) ) {
			$date_modified_max = ssa_datetime( '-1 day' );
		}

		$sql = 'DELETE FROM '.$this->get_table_name().' WHERE status = "abandoned" AND date_modified < %s';
		$sql = $wpdb->prepare(
			$sql,
			$date_modified_max->format( 'Y-m-d H:i:s' )
		);

		$wpdb->get_results( $sql );
	}

	public function update_rescheduled_to_appointment_id( $appointment_id, $data, $data_before = array() ) {
		$rescheduled_from = $data['rescheduled_from_appointment_id'];

		if( $rescheduled_from ) {
			$this->update( $rescheduled_from, array(
				'rescheduled_to_appointment_id' => $appointment_id
			) );
		}
	}

}
