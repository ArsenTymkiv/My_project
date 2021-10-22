<?php
/**
 * Simply Schedule Appointments Scheduler.
 *
 * @since   4.7.4
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Scheduler.
 *
 * @since 4.7.4
 */

class SSA_Action_Scheduler {
	/**
	 * Parent plugin class.
	 *
	 * @since 4.7.4
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  4.7.4
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
	 * @since  4.7.4
	 */
	public function hooks() {
    add_action( 'init', array( $this, 'schedule_async_actions' ) );
    add_action( 'ssa/async/ics_cleanup', array( $this, 'cleanup_ics_files' ) );
	}

  /**
   * Schedule all async actions using Action Scheduler.
   * 
   * @since 4.7.4
   * 
   * @uses as_has_scheduled_action
   * @uses as_schedule_recurring_action
   * @return void
   */
  public function schedule_async_actions() {
    if ( ! class_exists( 'ActionScheduler' ) ) {
      return;
    }

    if ( ! function_exists( 'as_has_scheduled_action' ) ) {
      return;
    }

    if ( ! function_exists( 'as_schedule_recurring_action' ) ) {
      return;
    }

    if ( false === as_has_scheduled_action('ssa/async/ics_cleanup' ) ) {
      as_schedule_recurring_action( strtotime( 'now' ), DAY_IN_SECONDS, 'ssa/async/ics_cleanup' );
    }    
  }

  /**
   * Logic to cleanup .ics files periodically.
   * 
   * @since 4.7.4
   *
   * @return void
   */
  public function cleanup_ics_files() {
    $path = $this->plugin->filesystem->get_uploads_dir_path() . '/ics/*';
    $files = glob( $path );

    foreach( $files as $file ){
      if( is_file( $file ) ) {
        unlink( $file );
      }
    }
  }

}