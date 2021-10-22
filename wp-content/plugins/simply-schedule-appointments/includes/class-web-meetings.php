<?php
/**
 * Simply Schedule Appointments Web Meetings.
 *
 * @since   4.3.3-beta3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Web Meetings.
 *
 * @since 4.3.3-beta3
 */
class SSA_Web_Meetings {
	/**
	 * Parent plugin class.
	 *
	 * @since 4.3.3-beta3
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  4.3.3-beta3
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  4.3.3-beta3
	 */
	public function hooks() {
		add_filter( 'ssa/appointment/before_insert', array( $this, 'set_custom_web_meeting_url' ) );
	}

	public function set_custom_web_meeting_url( $data ) {
		if ( empty( $data['appointment_type_id'] ) ) {
			return $data;
		}

		$appointment_type = $this->plugin->appointment_type_model->get( $data['appointment_type_id'] );
		if ( empty( $appointment_type['web_meetings']['provider'] )
		 || 'custom' !== $appointment_type['web_meetings']['provider']
		 || empty( $appointment_type['web_meetings']['url'] )
		) {
			return $data;
		}

		$data['web_meeting_url'] = trim( $appointment_type['web_meetings']['url'] );
		return $data;
	}
}
