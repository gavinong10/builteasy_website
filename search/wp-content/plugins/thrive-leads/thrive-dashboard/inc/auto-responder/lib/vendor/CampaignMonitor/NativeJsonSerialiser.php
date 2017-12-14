<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_Api_CampaignMonitor_NativeJsonSerialiser extends Thrive_Dash_Api_CampaignMonitor_BaseSerialiser {

	function get_format() {
		return 'json';
	}

	function get_type() {
		return 'native';
	}

	function serialise( $data ) {
		if ( is_null( $data ) || $data == '' ) {
			return '';
		}

		return json_encode( $this->check_encoding( $data ) );
	}

	function deserialise( $text ) {
		$data = json_decode( $text );

		return $this->strip_surrounding_quotes( is_null( $data ) ? $text : $data );
	}

	/**
	 * We've had sporadic reports of people getting ID's from create routes with the surrounding quotes present.
	 * There is no case where these should be present. Just get rid of it.
	 */
	function strip_surrounding_quotes( $data ) {
		if ( is_string( $data ) ) {
			return trim( $data, '"' );
		}

		return $data;
	}
}