<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_Api_SendGridEmail {

	protected
		$namespace = 'SendGrid',
		$headers = array( 'Content-Type' => 'application/json', 'Accept' => '*/*' ),
		$client,
		$options;

	public
		$apiUser,
		$apiKey,
		$url,
		$endpoint,
		$version = '4.0.4';

	public function __construct( $apiUserOrKey, $apiKeyOrOptions = null, $options = array() ) {
		// Check if given a username + password or api key
		if ( is_string( $apiKeyOrOptions ) ) {
			// Username and password
			$this->apiUser = $apiUserOrKey;
			$this->apiKey  = $apiKeyOrOptions;
			$this->options = $options;
		} elseif ( is_array( $apiKeyOrOptions ) || $apiKeyOrOptions === null ) {
			// API key
			$this->apiKey  = $apiUserOrKey;
			$this->apiUser = null;

			// With options
			if ( is_array( $apiKeyOrOptions ) ) {
				$this->options = $apiKeyOrOptions;
			}
		} else {
			// Won't be thrown?
			throw new InvalidArgumentException( 'Need a username + password or api key!' );
		}

		$this->options['turn_off_ssl_verification'] = ( isset( $this->options['turn_off_ssl_verification'] ) && $this->options['turn_off_ssl_verification'] == true );
		if ( ! isset( $this->options['raise_exceptions'] ) ) {
			$this->options['raise_exceptions'] = true;
		}
		$protocol = isset( $this->options['protocol'] ) ? $this->options['protocol'] : 'https';
		$host     = isset( $this->options['host'] ) ? $this->options['host'] : 'api.sendgrid.com';
		$port     = isset( $this->options['port'] ) ? $this->options['port'] : '';

		$this->url      = isset( $this->options['url'] ) ? $this->options['url'] : $protocol . '://' . $host . ( $port ? ':' . $port : '' );
		$this->endpoint = isset( $this->options['endpoint'] ) ? $this->options['endpoint'] : '/api/mail.send.json';


		//it seems that this is the header
		$this->client = $this->prepareHttpClient();
	}

	/**
	 * Prepares the HTTP client
	 *
	 * @return $header
	 */
	private function prepareHttpClient() {

		$args = array(
			'headers'   => array(
				'Authorization' => 'Bearer ' . $this->apiKey,
				'User-Agent'    => 'sendgrid/' . $this->version . ';php'
			),
			'timeout'   => 15,
			'sslverify' => false,
		);

		return $args;
	}

	/**
	 * @return array The protected options array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Makes a post request to SendGrid to send an email
	 *
	 * @param Thrive_Dash_Api_SendGridEmail_Email $email Email object built
	 *
	 * @throws Thrive_Dash_Api_SendGridEmail_Exception if the response code is not 200
	 * @return stdClass SendGrid response object
	 */
	public function send( Thrive_Dash_Api_SendGridEmail_Email $email ) {
		$form = $email->toWebFormat();

		// Using username password
		if ( $this->apiUser !== null ) {
			$form['api_user'] = $this->apiUser;
			$form['api_key']  = $this->apiKey;
		}

		$response = $this->postRequest( $this->endpoint, $form );

		if ( $response->code != 200 && $this->options['raise_exceptions'] ) {
			throw new Thrive_Dash_Api_SendGridEmail_Exception( $response->body->errors['0'], $response->code );
		}

		return $response;
	}

	/**
	 * Makes the actual HTTP request to SendGrid
	 *
	 * @param $endpoint string endpoint to post to
	 * @param $form array web ready version of SendGrid\Email
	 *
	 * @return Thrive_Dash_Api_SendGridEmail_Response
	 */
	public function postRequest( $endpoint, $form ) {
		$url          = $this->url . $endpoint;
		$args         = $this->client;
		$args['body'] = $form;

		$result = tve_dash_api_remote_post( $url, $args );


		$response = new Thrive_Dash_Api_SendGridEmail_Response( $result['response']['code'], $result['headers'], $result['body'], json_decode( $result['body'] ) );

		return $response;
	}
}
