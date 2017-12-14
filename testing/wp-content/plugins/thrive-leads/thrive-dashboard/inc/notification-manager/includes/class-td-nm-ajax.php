<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Ajax {

	public static function init() {
		self::add_ajax_events();
	}

	public static function add_ajax_events() {
		$ajax_events = array(
			'admin_controller' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_td_nm_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_td_nm_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	/**
	 * All ajax requests for admin will be managed by admin ajax controller
	 * Dies with json response
	 */
	public static function admin_controller() {
		include_once TD_NM()->path( 'includes/admin/class-td-nm-admin-ajax-controller.php' );
		$response = TD_NM_Admin_Ajax_Controller::instance()->handle();
		wp_send_json( $response );
	}
}

TD_NM_Ajax::init();
