<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Post_Types {
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}

	public static function register_post_types() {
		if ( post_type_exists( 'td_nm_notification' ) ) {
			return;
		}

		register_post_type( 'td_nm_notification', array(
			'publicly_queryable' => true,
			'query_var'          => false,
			'description'        => 'Thrive Notification',
			'rewrite'            => false,
		) );
	}
}

TD_NM_Post_Types::init();
