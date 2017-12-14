<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Interface to the SendGrid Web API
 */
class Thrive_Dash_Api_SendGrid {

	public
		$client,
		$version = '5.0.0';

	/**
	 * Setup the HTTP Client
	 *
	 * @param string $apiKey your SendGrid API Key.
	 * @param array $options an array of options, currenlty only "host" is implemented.
	 */
	public function __construct( $apiKey, $options = array() ) {

		/**
		 * Create the headers
		 */
		$headers = array(
			'Authorization' => 'Bearer ' . $apiKey,
			'User-Agent'    => 'sendgrid/' . $this->version . ';php'
		);
		/**
		 * Build Host
		 */
		$host = isset( $options['host'] ) ? $options['host'] : 'https://api.sendgrid.com';

		$this->client = new Thrive_Dash_Api_SendGrid_Client( $host, $headers, '/v3', null );
	}
}
