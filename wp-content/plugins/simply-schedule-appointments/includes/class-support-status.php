<?php
/**
 * Simply Schedule Appointments Support Status.
 *
 * @since   2.1.6
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Support Status.
 *
 * @since 2.1.6
 */
class SSA_Support_Status {
	/**
	 * Parent plugin class.
	 *
	 * @since 2.1.6
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  2.1.6
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
	 * @since  2.1.6
	 */
	public function hooks() {

	}

	public function get_site_status() {
		$site_status = new TD_Health_Check_Site_Status();
		return array(
			'ssa_version' => $this->plugin->version,
			'php_version' => $site_status->test_php_version(),
			'wordpress_version' => $site_status->test_wordpress_version(),
			'sql_server' => $site_status->test_sql_server(),
			'json_extension' => $site_status->test_json_extension(),
			'utf8mb4_support' => $site_status->test_utf8mb4_support(),
			'dotorg_communication' => $site_status->test_dotorg_communication(),
			'https_status' => $site_status->test_https_status(),
			'ssl_support' => $site_status->test_ssl_support(),
			'scheduled_events' => $site_status->test_scheduled_events(),
			'php_timezone' => $site_status->test_php_default_timezone()
		);
	}

	/**
	 * Receives a JSON formatted string, parses into import data, and runs all the import process.
	 *
	 * @param array $decoded    the JSON import data, decoded into an associative array format.
	 * @return boolean|WP_Error true if import process was successful, WP_Error if something bad happens.
	 */
	public function import_data( $decoded ) {

		// if settings data is available, disable all settings (so we don't trigger hooks for notifications, webhooks, etc). The settings will get overwritten again at the end of this import process
		if( isset( $decoded['settings'] ) ) {
			$old_settings = $this->plugin->settings->get();
			foreach ($old_settings as $key => &$old_setting) {
				if ( empty( $old_setting ) || ! is_array( $old_setting ) ) {
					continue;
				}

				$old_setting['enabled'] = false;
			}

			// disable settings before import
			$update = $this->plugin->settings->update( $old_settings );

			// staff
			$delete = $this->plugin->staff_model->truncate();
			$this->plugin->staff_model->create_table();
			foreach( $decoded['staff'] as $staff ) {
				$include = $this->plugin->staff_model->raw_insert( $staff );

				// if any error happens while trying to import appointment type data, return
				if( is_wp_error( $include ) ) {
					return $include;
				}
			}

		}

		// if appointment types data is available, update
		if( isset( $decoded['appointment_types'] ) ) {
			$delete = $this->plugin->appointment_type_model->truncate();
			$this->plugin->appointment_type_model->create_table();

			foreach( $decoded['appointment_types'] as $appointment_type ) {
				$include = $this->plugin->appointment_type_model->raw_insert( $appointment_type );

				// if any error happens while trying to import appointment type data, return
				if( is_wp_error( $include ) ) {
					return $include;
				}
			}

			$delete = $this->plugin->staff_appointment_type_model->truncate();
			$this->plugin->staff_appointment_type_model->create_table();
			foreach( $decoded['staff_appointment_types'] as $staff_appointment_type ) {
				$include = $this->plugin->staff_appointment_type_model->raw_insert( $staff_appointment_type );

				// if any error happens while trying to import staff appointment type data, return
				if( is_wp_error( $include ) ) {
					return $include;
				}
			}
		}

		// if appointments data is available, update
		if( isset( $decoded['appointments'] ) ) {
			$delete = $this->plugin->appointment_model->truncate();
			$this->plugin->appointment_model->create_table();

			foreach( $decoded['appointments'] as $appointment ) {
				$include = $this->plugin->appointment_model->raw_insert( $appointment );

				// if any error happens while trying to import appointment data, return
				if( is_wp_error( $include ) ) {
					return $include;
				}
			}

			$delete = $this->plugin->staff_appointment_model->truncate();
			$this->plugin->staff_appointment_model->create_table();

			if ( ! empty( $decoded['staff_appointments'] ) ) {			
				foreach( $decoded['staff_appointments'] as $staff_appointment ) {
					$include = $this->plugin->staff_appointment_model->raw_insert( $staff_appointment );

					// if any error happens while trying to import staff_appointment data, return
					if( is_wp_error( $include ) ) {
						return $include;
					}
				}
			}

		}

		// if appointments meta data is available, update
		if( isset( $decoded['appointment_meta'] ) ) {
			$delete = $this->plugin->appointment_meta_model->truncate();

			foreach( $decoded['appointment_meta'] as $appointment_meta ) {
				$include = $this->plugin->appointment_meta_model->raw_insert( $appointment_meta );

				// if any error happens while trying to import appointment data, return
				if( is_wp_error( $include ) ) {
					return $include;
				}
			}
		}

		// if settings data is available, update
		if( isset( $decoded['settings'] ) ) {
			$update = $this->plugin->settings->update( $decoded['settings'] );
		}

		$delete = $this->plugin->availability_model->truncate();
		$this->plugin->availability_cache_invalidation->increment_cache_version();
		$this->plugin->google_calendar->increment_google_cache_version();

		// everything was successfully imported
		return true;
	}
	
	/**
	 * Save JSON export backups into the database.
	 *
	 * @param string $code
	 * @return bool|WP_Error true if backup was successfully saved. WP_Error if something wrong happens.
	 */
	public function save_export_backup( $code = null ) {
		if( ! $code ) {
			return false;
		}

		$date = date('Y-m-d H:i:s');
		$encoded = json_encode($code);

		$backups = get_option( 'ssa_export_backups' );

		if( ! $backups ) {
			$backups = array();
		}

		// if there is already 3 backups, remove the oldest one
		if( count($backups) >= 3 ) {
			array_pop($backups);
		}

		// insert the newest one at the beginning of the array
		array_unshift( 
			$backups, 
			array(
				'date' => $date,
				'content' => $encoded
			) 
		);

		$update = update_option( 'ssa_export_backups', $backups );

		if( ! $update ) {
			return new WP_Error( 'ssa-export-backup-not-saved', __( 'An error occurred while trying to save a backup.', 'simply-schedule-appointments' ) );
		}

		return $update;
	}

	/**
	 * Checks if there is a backup export file stored and, if it does, then decode the JSON into an associative array.
	 *
	 * @return boolean|array false if we can't find the file, or an associative array if we find it and it has a valid format.
	 */
	public function get_export_backup() {
		$backups = $this->get_export_backup_list();

		if( is_wp_error($backups) ) {
			return $backups;
		}

		$json = $backups[0]['content'];

		// verify if JSON data is valid
		$decoded = json_decode( $json, true );

		if ( ! is_object( $decoded ) && ! is_array( $decoded ) ) {
			return new WP_Error( 'export-code-invalid-format', __( 'Invalid data format.', 'simply-schedule-appointments'));
		}
		
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return new WP_Error( 'export-code-invalid-format', __( 'Invalid data format.', 'simply-schedule-appointments'));
		}

		if( $decoded ) {
			return $decoded;
		}

		return false;
	}

	/**
	 * Checks if there is an export backup stored and returns a list if any.
	 *
	 * @return array|WP_Error An associative array if we find backups. WP_Error if we can't find anything.
	 */
	public function get_export_backup_list() {
		$backups = get_option('ssa_export_backups');

		if( ! $backups || empty($backups) ) {
			return new WP_Error( 'ssa-export-backups-not-found', __( 'Could not find any export backups.', 'simply-schedule-appointments' ) );
		}

		return $backups;
	}	
	
	/**
	 * Searches for latest export backup and, if found, recover the data by running the import logic.
	 *
	 * @return boolean|WP_Error
	 */
	public function restore_settings_backup() {
		$backup = $this->get_export_backup();

		if( ! $backup || is_wp_error( $backup ) ) {
			return new WP_Error( 'ssa-export-file-not-found', __( 'No backup files were found.', 'simply-schedule-appointments' ) );
		}
		
		$import = $this->import_data( $backup );
		
		if( is_wp_error( $import ) ) {
			return $import;
		}

		return true;
	}

	
}
