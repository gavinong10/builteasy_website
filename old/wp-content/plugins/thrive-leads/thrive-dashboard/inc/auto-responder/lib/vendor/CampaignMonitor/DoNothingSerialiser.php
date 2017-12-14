<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_Api_CampaignMonitor_DoNothingSerialiser extends Thrive_Dash_Api_CampaignMonitor_BaseSerialiser {
	function __construct() {
	}

	function get_type() {
		return 'do_nothing';
	}

	function serialise( $data ) {
		return $data;
	}

	function deserialise( $text ) {
		$data = json_decode( $text );

		return is_null( $data ) ? $text : $data;
	}

	function check_encoding( $data ) {
		return $data;
	}
}