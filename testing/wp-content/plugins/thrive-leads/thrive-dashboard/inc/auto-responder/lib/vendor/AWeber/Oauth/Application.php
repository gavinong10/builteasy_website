<?php

/**
 * OAuthApplication
 *
 * Base class to represent an OAuthConsumer application.  This class is
 * intended to be extended and modified for each ServiceProvider. Each
 * Thrive_Dash_Api_AWeber_Oauth_ServiceProvider should have a complementary OAuthApplication
 *
 * The OAuthApplication class should contain any details on preparing
 * requires that is unique or specific to that specific service provider's
 * implementation of the OAuth model.
 *
 * This base class is based on OAuth 1.0, designed with AWeber's implementation
 * as a model.  An OAuthApplication built to work with a different service
 * provider (especially an OAuth2.0 Application) may alter or bypass portions
 * of the logic in this class to meet the needs of the service provider it
 * is designed to interface with.
 *
 * @package
 * @version $id$
 */
class Thrive_Dash_Api_AWeber_Oauth_Application implements Thrive_Dash_Api_AWeber_Oauth_Adapter {
	public $debug = false;

	public $userAgent = 'AWeber OAuth Consumer Application 1.0 - https://labs.aweber.com/';

	public $format = false;

	public $requiresTokenSecret = true;

	public $signatureMethod = 'HMAC-SHA1';
	public $version = '1.0';

	public $error = '';

	/**
	 * @var Thrive_Dash_Api_AWeber_Oauth_User User currently interacting with the service provider
	 */
	public $user = false;

	// Data binding this OAuthApplication to the consumer application it is acting
	// as a proxy for
	public $consumerKey = false;
	public $consumerSecret = false;

	/**
	 * __construct
	 *
	 * Create a new OAuthApplication, based on an Thrive_Dash_Api_AWeber_Oauth_ServiceProvider
	 * @access public
	 *
	 * @param bool|mixed $parentApp
	 *
	 * @throws Exception
	 */
	public function __construct( $parentApp = false ) {
		if ( $parentApp ) {
			if ( ! is_a( $parentApp, 'Thrive_Dash_Api_AWeber_Oauth_ServiceProvider' ) ) {
				throw new Exception( 'Parent App must be a valid Thrive_Dash_Api_AWeber_Oauth_ServiceProvider!' );
			}
			$this->app = $parentApp;
		}
		$this->user = new Thrive_Dash_Api_AWeber_Oauth_User();
	}

	/**
	 * request
	 *
	 * Implemented for a standard OAuth adapter interface
	 *
	 * @param mixed $method
	 * @param mixed $uri
	 * @param array $data
	 * @param array $options
	 *
	 * @access public
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function request( $method, $uri, $data = array(), $options = array() ) {
		$uri = $this->app->removeBaseUri( $uri );
		$url = $this->app->getBaseUri() . $uri;

		# WARNING: non-primative items in data must be json serialized in GET and POST.
		if ( $method == 'POST' or $method == 'GET' ) {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$data[ $key ] = json_encode( $value );
				}
			}
		}

		$response = $this->makeRequest( $method, $url, $data );
		if ( ! empty( $options['return'] ) ) {
			if ( $options['return'] == 'status' ) {
				return $response->headers['Status-Code'];
			}
			if ( $options['return'] == 'headers' ) {
				return $response->headers;
			}
			if ( $options['return'] == 'integer' ) {
				return intval( $response->body );
			}
		}

		$data = json_decode( $response->body, true );

		if ( empty( $options['allow_empty'] ) && ! isset( $data ) ) {
			throw new Thrive_Dash_Api_AWeber_ResponseError( $uri );
		}

		return $data;
	}

	/**
	 * getRequestToken
	 *
	 * Gets a new request token / secret for this user.
	 *
	 * @param bool|string $callbackUrl
	 *
	 * @return mixed
	 */
	public function getRequestToken( $callbackUrl = false ) {
		$data = ( $callbackUrl ) ? array( 'oauth_callback' => $callbackUrl ) : array();
		$resp = $this->makeRequest( 'POST', $this->app->getRequestTokenUrl(), $data );
		$data = $this->parseResponse( $resp );
		$this->requiredFromResponse( $data, array( 'oauth_token', 'oauth_token_secret' ) );
		$this->user->requestToken = $data['oauth_token'];
		$this->user->tokenSecret  = $data['oauth_token_secret'];

		return $data['oauth_token'];
	}

	/**
	 * getAccessToken
	 *
	 * Makes a request for access tokens.  Requires that the current user has an authorized
	 * token and token secret.
	 *
	 * @access public
	 * @return array
	 *
	 * @throws Exception
	 */
	public function getAccessToken() {
		$resp = $this->makeRequest( 'POST', $this->app->getAccessTokenUrl(),
			array( 'oauth_verifier' => $this->user->verifier )
		);
		$data = $this->parseResponse( $resp );
		$this->requiredFromResponse( $data, array( 'oauth_token', 'oauth_token_secret' ) );

		if ( empty( $data['oauth_token'] ) ) {
			throw new Thrive_Dash_Api_AWeber_OAuthDataMissing( 'oauth_token' );
		}

		$this->user->accessToken = $data['oauth_token'];
		$this->user->tokenSecret = $data['oauth_token_secret'];

		return array( $data['oauth_token'], $data['oauth_token_secret'] );
	}

	/**
	 * parseAsError
	 *
	 * Checks if response is an error.  If it is, raise an appropriately
	 * configured exception.
	 *
	 * @param mixed $response Data returned from the server, in array form
	 *
	 * @access public
	 * @throws Exception
	 */
	public function parseAsError( $response ) {
		if ( ! empty( $response['error'] ) ) {
			throw new Thrive_Dash_Api_AWeber_OAuthException( $response['error']['type'],
				$response['error']['message'] );
		}
	}

	/**
	 * requiredFromResponse
	 *
	 * Enforce that all the fields in requiredFields are present and not
	 * empty in data.  If a required field is empty, throw an exception.
	 *
	 * @param mixed $data Array of data
	 * @param mixed $requiredFields Array of required field names.
	 *
	 * @access protected
	 * @throws Exception
	 */
	protected function requiredFromResponse( $data, $requiredFields ) {
		foreach ( $requiredFields as $field ) {
			if ( empty( $data[ $field ] ) ) {
				throw new Thrive_Dash_Api_AWeber_OAuthDataMissing( $field );
			}
		}
	}

	/**
	 * _addParametersToUrl
	 *
	 * Adds the parameters in associative array $data to the
	 * given URL
	 *
	 * @param String $url URL
	 * @param array $data Parameters to be added as a query string to
	 *      the URL provided
	 *
	 * @access protected
	 * @return string
	 */
	protected function _addParametersToUrl( $url, $data ) {
		if ( ! empty( $data ) ) {
			if ( strpos( $url, '?' ) === false ) {
				$url .= '?' . $this->buildData( $data );
			} else {
				$url .= '&' . $this->buildData( $data );
			}
		}

		return $url;
	}

	/**
	 * generateNonce
	 *
	 * Generates a 'nonce', which is a unique request id based on the
	 * timestamp.  If no timestamp is provided, generate one.
	 *
	 * @param mixed $timestamp Either a timestamp (epoch seconds) or false,
	 *  in which case it will generate a timestamp.
	 *
	 * @access public
	 * @return string   Returns a unique nonce
	 */
	public function generateNonce( $timestamp = false ) {
		if ( ! $timestamp ) {
			$timestamp = $this->generateTimestamp();
		}

		return md5( $timestamp . '-' . rand( 10000, 99999 ) . '-' . uniqid() );
	}

	/**
	 * generateTimestamp
	 *
	 * Generates a timestamp, in seconds
	 * @access public
	 * @return int Timestamp, in epoch seconds
	 */
	public function generateTimestamp() {
		return time();
	}

	/**
	 * createSignature
	 *
	 * Creates a signature on the signature base and the signature key
	 *
	 * @param mixed $sigBase Base string of data to sign
	 * @param mixed $sigKey Key to sign the data with
	 *
	 * @access public
	 * @return string   The signature
	 */
	public function createSignature( $sigBase, $sigKey ) {
		switch ( $this->signatureMethod ) {
			case 'HMAC-SHA1':
			default:
				return base64_encode( hash_hmac( 'sha1', $sigBase, $sigKey, true ) );
		}
	}

	/**
	 * encode
	 *
	 * Short-cut for utf8_encode / rawurlencode
	 *
	 * @param mixed $data Data to encode
	 *
	 * @access protected
	 * @return string         Encoded data
	 */
	protected function encode( $data ) {
		return rawurlencode( $data );
	}

	/**
	 * createSignatureKey
	 *
	 * Creates a key that will be used to sign our signature.  Signatures
	 * are signed with the consumerSecret for this consumer application and
	 * the token secret of the user that the application is acting on behalf
	 * of.
	 * @access public
	 * @return string
	 */
	public function createSignatureKey() {
		return $this->consumerSecret . '&' . $this->user->tokenSecret;
	}

	/**
	 * getOAuthRequestData
	 *
	 * Get all the pre-signature, OAuth specific parameters for a request.
	 * @access public
	 * @return array
	 */
	public function getOAuthRequestData() {
		$token = $this->user->getHighestPriorityToken();
		$ts    = $this->generateTimestamp();
		$nonce = $this->generateNonce( $ts );

		return array(
			'oauth_token'            => $token,
			'oauth_consumer_key'     => $this->consumerKey,
			'oauth_version'          => $this->version,
			'oauth_timestamp'        => $ts,
			'oauth_signature_method' => $this->signatureMethod,
			'oauth_nonce'            => $nonce
		);
	}


	/**
	 * mergeOAuthData
	 *
	 * @param mixed $requestData
	 *
	 * @access public
	 * @return array
	 */
	public function mergeOAuthData( $requestData ) {
		$oauthData = $this->getOAuthRequestData();

		return array_merge( $requestData, $oauthData );
	}

	/**
	 * createSignatureBase
	 *
	 * @param mixed $method String name of HTTP method, such as "GET"
	 * @param mixed $url URL where this request will go
	 * @param mixed $data Array of params for this request. This should
	 *      include ALL oauth properties except for the signature.
	 *
	 * @access public
	 * @return string
	 */
	public function createSignatureBase( $method, $url, $data ) {
		$method = $this->encode( strtoupper( $method ) );
		$query  = parse_url( $url, PHP_URL_QUERY );
		if ( $query ) {
			$parts = explode( '?', $url, 2 );
			$url   = array_shift( $parts );
			$items = explode( '&', $query );
			foreach ( $items as $item ) {
				list( $key, $value ) = explode( '=', $item );
				$data[ rawurldecode( $key ) ] = rawurldecode( $value );
			}
		}
		$url  = $this->encode( $url );
		$data = $this->encode( $this->collapseDataForSignature( $data ) );

		return $method . '&' . $url . '&' . $data;
	}

	/**
	 * collapseDataForSignature
	 *
	 * Turns an array of request data into a string, as used by the oauth
	 * signature
	 *
	 * @param mixed $data
	 *
	 * @access public
	 * @return string
	 */
	public function collapseDataForSignature( $data ) {
		ksort( $data );
		$collapse = '';
		foreach ( $data as $key => $val ) {
			if ( ! empty( $collapse ) ) {
				$collapse .= '&';
			}
			$collapse .= $key . '=' . $this->encode( $val );
		}

		return $collapse;
	}

	/**
	 * signRequest
	 *
	 * Signs the request.
	 *
	 * @param mixed $method HTTP method
	 * @param mixed $url URL for the request
	 * @param mixed $data The data to be signed
	 *
	 * @access public
	 * @return array            The data, with the signature.
	 */
	public function signRequest( $method, $url, $data ) {
		$base                    = $this->createSignatureBase( $method, $url, $data );
		$key                     = $this->createSignatureKey();
		$data['oauth_signature'] = $this->createSignature( $base, $key );
		ksort( $data );

		return $data;
	}

	/**
	 * makeRequest
	 *
	 * Public facing function to make a request
	 *
	 * @param mixed $method
	 * @param mixed $url - Reserved characters in query params MUST be escaped
	 * @param mixed $data - Reserved characters in values MUST NOT be escaped
	 *
	 * @access public
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function makeRequest( $method, $url, $data = array() ) {
		/**
		 * AWeber requires curl extension to be enabled and have the https protocol available
		 */
		if ( ! extension_loaded( 'curl' ) || ! function_exists( 'curl_version' ) ) {
			throw new Thrive_Dash_Api_AWeber_Exception( 'AWeber needs the php <strong>curl</strong> extension to be enabled and support the HTTPS protocol. Please contact you hosting provider and ask them to enable it on your server.' );
		}

		$version = curl_version();
		if ( ! in_array( 'https', $version['protocols'] ) ) {
			throw new Thrive_Dash_Api_AWeber_Exception( 'AWeber needs the php <strong>curl</strong> extension to be enabled and support the HTTPS protocol. Please contact you hosting provider and ask them to enable it on your server. The currently installed protocols are: ' . implode( ', ', $version['protocols'] ) );
		}

		if ( $this->debug ) {
			echo "\n** {$method}: $url\n";
		}

		$args = array(
			'headers' => array( 'Expect' => '' )
		);

		$oauth = null;
		$resp  = null;
		switch ( strtoupper( $method ) ) {
			case 'POST':
				$oauth        = $this->prepareRequest( $method, $url, $data );
				$postData     = $this->buildData( $oauth );
				$args['body'] = $postData;
				$fn           = 'tve_dash_api_remote_post';
				break;

			case 'GET':
				$oauth = $this->prepareRequest( $method, $url, $data );
				$url   = $this->_addParametersToUrl( $url, $oauth );
				$fn    = 'tve_dash_api_remote_get';
				break;

			case 'DELETE':
				$oauth          = $this->prepareRequest( $method, $url, $data );
				$url            = $this->_addParametersToUrl( $url, $oauth );
				$fn             = 'tve_dash_api_remote_request';
				$args['method'] = 'DELETE';
				break;

			case 'PATCH':
				$oauth                     = $this->prepareRequest( $method, $url, array() );
				$url                       = $this->_addParametersToUrl( $url, $oauth );
				$fn                        = 'tve_dash_api_remote_request';
				$args['method']            = 'PATCH';
				$args['body']              = json_encode( $data );
				$args['headers']['Expect'] = 'Content-Type: application/json';
				break;
			default:
				throw new Thrive_Dash_Api_AWeber_APIException( 'Method not implemented: ' . $method, $url );
		}

		$response = $fn( $url, $args );
		if ( $response instanceof WP_Error ) {
			throw new Thrive_Dash_Api_AWeber_APIException( array(
				'error'   => $response->get_error_message(),
				'message' => $response->get_error_message(),
				'type'    => 'APIUnreachableError'
			), $url );
		}

		$resp = new Thrive_Dash_Api_AWeber_Http_Response( $response );

		// enable debug output
		if ( $this->debug ) {
			echo "<pre>";
			print_r( $oauth );
			echo " --> Status: {$resp->headers['Status-Code']}\n";
			echo " --> Body: {$resp->body}";
			echo "</pre>";
		}

		if ( ! $resp ) {
			$msg   = 'Unable to connect to the AWeber API.  (' . $this->error . ')';
			$error = array(
				'message'           => $msg,
				'type'              => 'APIUnreachableError',
				'documentation_url' => 'https://labs.aweber.com/docs/troubleshooting'
			);
			throw new Thrive_Dash_Api_AWeber_APIException( $error, $url );
		}
		if ( $resp->headers['Status-Code'] >= 400 ) {
			$data = json_decode( $resp->body, true );
			if ( $data ) {
				throw new Thrive_Dash_Api_AWeber_APIException( $data['error'], $url );
			} else {
				$msg   = 'Unable to connect to the AWeber API.  (<strong>HTTP Status Code: ' . $resp->headers['Status-Code'] . '</strong>)';
				$error = array(
					'message'           => $msg,
					'type'              => 'APIUnreachableError',
					'documentation_url' => 'https://labs.aweber.com/docs/troubleshooting'
				);
				throw new Thrive_Dash_Api_AWeber_APIException( $error, $url );
			}
		}

		return $resp;
	}

	/**
	 * buildData
	 *
	 * Creates a string of data for either post or get requests.
	 *
	 * @param mixed $data Array of key value pairs
	 *
	 * @access public
	 * @return mixed
	 */
	public function buildData( $data ) {
		ksort( $data );
		$params = array();
		foreach ( $data as $key => $value ) {
			$params[] = $key . '=' . $this->encode( $value );
		}

		return implode( '&', $params );
	}

	/**
	 * prepareRequest
	 *
	 * @param mixed $method HTTP method
	 * @param mixed $url URL for the request
	 * @param mixed $data The data to generate oauth data and be signed
	 *
	 * @access public
	 * @return mixed             The data, with all its OAuth variables and signature
	 */
	public function prepareRequest( $method, $url, $data ) {
		$data = $this->mergeOAuthData( $data );
		$data = $this->signRequest( $method, $url, $data );

		return $data;
	}

	/**
	 * parseResponse
	 *
	 * Parses the body of the response into an array
	 *
	 * @param mixed $resp The body of a response
	 *
	 * @access public
	 * @return mixed
	 */
	public function parseResponse( $resp ) {
		$data = array();

		if ( ! $resp ) {
			return $data;
		}
		if ( empty( $resp ) ) {
			return $data;
		}
		if ( empty( $resp->body ) ) {
			return $data;
		}

		switch ( $this->format ) {
			case 'json':
				$data = json_decode( $resp->body );
				break;
			default:
				parse_str( $resp->body, $data );
		}
		$this->parseAsError( $data );

		return $data;
	}
} 