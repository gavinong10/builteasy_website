<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_Api_CampaignMonitor_ServicesJsonSerialiser extends Thrive_Dash_Api_CampaignMonitor_BaseSerialiser {

	var $_serialiser;

	function __construct( $log ) {
		parent::__construct( $log );
		$this->_serialiser = new Services_JSON();
	}

	function get_content_type() {
		return 'application/json';
	}

	function get_format() {
		return 'json';
	}

	function get_type() {
		return 'services_json';
	}

	function serialise( $data ) {
		if ( is_null( $data ) || $data == '' ) {
			return '';
		}

		return $this->_serialiser->encode( $this->check_encoding( $data ) );
	}

	function deserialise( $text ) {
		$data = $this->_serialiser->decode( $text );

		return is_null( $data ) ? $text : $data;
	}
}