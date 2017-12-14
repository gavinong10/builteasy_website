<?php


class Thrive_Dash_Api_CampaignMonitor_BaseSerialiser {

	var $_log;

	function __construct( $log ) {
		$this->_log = $log;
	}

	/**
	 * Recursively ensures that all data values are utf-8 encoded.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	function check_encoding( $data ) {

		foreach ( $data as $k => $v ) {
			// If the element is a sub-array then recusively encode the array
			if ( is_array( $v ) ) {
				$data[ $k ] = $this->check_encoding( $v );
				// Otherwise if the element is a string then we need to check the encoding
			} else if ( is_string( $v ) ) {
				if ( ( function_exists( 'mb_detect_encoding' ) && mb_detect_encoding( $v ) !== 'UTF-8' ) ||
				     ( function_exists( 'mb_check_encoding' ) && ! mb_check_encoding( $v, 'UTF-8' ) )
				) {
					// The string is using some other encoding, make sure we utf-8 encode
					$v = utf8_encode( $v );
				}

				$data[ $k ] = $v;
			}
		}

		return $data;
	}
}