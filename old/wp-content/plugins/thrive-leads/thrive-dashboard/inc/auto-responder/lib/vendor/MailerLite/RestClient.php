<?php

class Thrive_Dash_Api_MailerLite_RestClient {

	public $httpClient;

	public $apiKey;

	public $baseUrl;

	/**
	 * @param string $baseUrl
	 * @param string $apiKey
	 */
	public function __construct( $baseUrl, $apiKey ) {
		$this->baseUrl = $baseUrl;
		$this->apiKey  = $apiKey;
	}

	/**
	 * Execute GET request
	 *
	 * @param $endpointUri
	 * @param array $queryString
	 *
	 * @return array
	 */
	public function get( $endpointUri, $queryString = array() ) {
		return $this->send( 'GET', $endpointUri . '?' . http_build_query( $queryString ) );
	}

	/**
	 * Execute POST request
	 *
	 * @param $endpointUri
	 * @param array $data
	 *
	 * @return array
	 */
	public function post( $endpointUri, $data = array() ) {
		return $this->send( 'POST', $endpointUri, http_build_query( $data ) );
	}

	/**
	 * Execute PUT request
	 *
	 * @param $endpointUri
	 * @param array $putData
	 *
	 * @return array
	 */
	public function put( $endpointUri, $putData = array() ) {
		return $this->send( 'PUT', $endpointUri, http_build_query( $putData ) );
	}

	/**
	 * Execute DELETE request
	 *
	 * @param $endpointUri
	 *
	 * @return array
	 */
	public function delete( $endpointUri ) {
		return $this->send( 'DELETE', $endpointUri );
	}

	/**
	 * Execute HTTP request
	 *
	 * @param $method
	 * @param $endpointUri
	 * @param null $body
	 * @param array $headers
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_MailerLite_MailerLiteSdkException
	 */
	protected function send( $method, $endpointUri, $body = null, array $headers = array() ) {
		$headers     = array_merge( $headers, self::getDefaultHeaders() );
		$endpointUrl = $this->baseUrl . $endpointUri;

		switch ( $method ) {
			case 'GET':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$response = $fn( $endpointUrl, array(
			'body'      => $body,
			'timeout'   => 15,
			'headers'   => $headers,
			'sslverify' => false,
		) );

		return $this->handleResponse( $response );
	}

	/**
	 * Handle HTTP response
	 *
	 * @param $response
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_MailerLite_MailerLiteSdkException
	 */
	protected function handleResponse( $response ) {

		if ( $response instanceof WP_Error ) {
			throw new Thrive_Dash_Api_MailerLite_MailerLiteSdkException( 'Failed connecting: ' . $response->get_error_message() );
		}

		if ( isset( $response['response']['code'] ) ) {
			switch ( $response['response']['code'] ) {
				case 200:
					$response_obj = json_decode( $response['body'] );
					$result       = (array) $response_obj;

					return $result;
					break;
				case 400:

					throw new Thrive_Dash_Api_MailerLite_MailerLiteSdkException( 'Missing a required parameter or calling invalid method' );
					break;
				case 401:
					throw new Thrive_Dash_Api_MailerLite_MailerLiteSdkException( 'Invalid API key provided!' );
					break;
				case 404:
					throw new Thrive_Dash_Api_MailerLite_MailerLiteSdkException( 'Can\'t find requested items' );
					break;
			}
		}

		return $response['body'];
	}

	/**
	 * @return array
	 */
	protected function getDefaultHeaders() {
		return array (
			'User-Agent'          => Thrive_Dash_Api_MailerLite_ApiConstants::SDK_USER_AGENT . '/' . Thrive_Dash_Api_MailerLite_ApiConstants::SDK_VERSION,
			'X-MailerLite-ApiKey' => $this->apiKey
		);
	}
}