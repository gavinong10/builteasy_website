<?php

/**
 * Class Thrive_Dash_Api_Mailchimp
 */
class Thrive_Dash_Api_Mailchimp {

	/**
	 * Endpoint for Mailchimp API v3
	 *
	 * @var string
	 */
	private $endpoint = 'https://us1.api.mailchimp.com/3.0/';

	/**
	 * @var string
	 */
	private $apikey;

	/**
	 * @var array
	 */
	private $allowedMethods = array( 'get', 'head', 'put', 'post', 'patch', 'delete' );

	/**
	 * @var array
	 */
	public $options = array();

	/**
	 * @param string $apikey
	 * @param array $clientOptions
	 */
	public function __construct( $apikey = '', $clientOptions = array() ) {
		$this->apikey = $apikey;

		$this->detectEndpoint( $this->apikey );

		$this->options['headers'] = array(
			'Authorization' => 'apikey ' . $this->apikey
		);
	}

	/**
	 * @param string $resource
	 * @param array $arguments
	 * @param string $method
	 *
	 * @return string
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function request( $resource, $arguments = array(), $method = 'GET' ) {
		if ( ! $this->apikey ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( 'Please provide an API key.' );
		}

		return $this->makeRequest( $resource, $arguments, strtolower( $method ) );
	}

	/**
	 * Enable proxy if needed.
	 *
	 * @param string $host
	 * @param int $port
	 * @param bool $ssl
	 * @param string $username
	 * @param string $password
	 *
	 * @return string
	 */
	public function setProxy(
		$host,
		$port,
		$ssl = false,
		$username = null,
		$password = null
	) {
		$scheme = ( $ssl ? 'https://' : 'http://' );

		if ( ! is_null( $username ) ) {
			return $this->options['proxy'] = sprintf( '%s%s:%s@%s:%s', $scheme, $username, $password, $host, $port );
		}

		return $this->options['proxy'] = sprintf( '%s%s:%s', $scheme, $host, $port );
	}

	/**
	 * @return string
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}

	/**
	 * @param $apikey
	 *
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function detectEndpoint( $apikey ) {
		if ( ! strstr( $apikey, '-' ) ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( 'There seems to be an issue with your apikey. Please consult Mailchimp' );
		}

		list( , $dc ) = explode( '-', $apikey );
		$this->endpoint = str_replace( 'us1', $dc, $this->endpoint );
	}

	/**
	 * @param string $apikey
	 */
	public function setApiKey( $apikey ) {
		$this->detectEndpoint( $apikey );

		$this->apikey = $apikey;
	}

	/**
	 * @param string $resource
	 * @param array $arguments
	 * @param string $method
	 *
	 * @return string
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	private function makeRequest( $resource, $arguments, $method ) {

		$options = $this->getOptions( $method, $arguments );
		$query_string = '';
		switch ( $method ) {
			case 'get':
				$fn   = 'tve_dash_api_remote_get';
				$body = isset($options['query']) ? $options['query'] : '';
				$query_string = isset($options['query']) ? '?' . http_build_query( $options['query']) : '';
				break;
			default:
				$fn   = 'tve_dash_api_remote_post';
				$body = json_encode( $options['json'] );
				$options['headers']['Content-type'] = 'application/json';
				break;
		}

		$response = $fn( $this->endpoint . $resource . $query_string, array(
			'body'      => $body,
			'timeout'   => 15,
			'headers'   => $options['headers'],
			'sslverify' => false,
			'method' => $method,
		) );

		if ( $response instanceof WP_Error ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( $response->get_error_message() );
		}

		$response_code = $response['response']['code'];
		$response_message = $response['response']['message'];

		if($response_code != 200 ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception($response_message);
		}

		$response_body = json_decode($response['body']);

		return $response_body;
	}

	/**
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return array
	 */
	private function getOptions( $method, $arguments ) {
		unset( $this->options['json'], $this->options['query'] );

		if ( count( $arguments ) < 1 ) {
			return $this->options;
		}

		if ( $method == 'get' ) {
			$this->options['query'] = $arguments;
		} else {
			$this->options['json'] = $arguments;
		}

		return $this->options;
	}

	/**
	 * @param $method
	 * @param $arguments
	 *
	 * @return string
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function __call( $method, $arguments ) {
		if ( count( $arguments ) < 1 ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( 'Magic request methods require a URI and optional options array' );
		}

		if ( ! in_array( $method, $this->allowedMethods ) ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( 'Method "' . $method . '" is not supported.' );
		}

		$resource = $arguments[0];
		$options  = isset( $arguments[1] ) ? $arguments[1] : array();

		return $this->request( $resource, $options, $method );
	}
}
