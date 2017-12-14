<?php

/**
 * SDK interface for managing SparkPost API endpoints
 */
class Thrive_Dash_Api_SparkPost_APIResource {

	/**
	 * name of the API endpoint, mainly used for URL construction.
	 * This is public to provide an interface
	 *
	 * @var string
	 */
	public $endpoint;

	/**
	 * Mapping for values passed into the send method to the values needed for the respective API
	 * @var array
	 */
	protected $parameterMappings = array();

	/**
	 * Sets up default structure and default values for the model that is acceptable by the API
	 * @var array
	 */
	protected $structure = array();

	/**
	 * SparkPost reference for configs
	 */
	protected $sparkpost;

	/**
	 * Initializes config for use later.
	 *
	 * @param $sparkpost Thrive_Dash_Api_SparkPost_Connection provides api configuration information
	 */
	public function __construct( Thrive_Dash_Api_SparkPost $sparkpost ) {
		$this->sparkpost = $sparkpost;
	}

	/**
	 * Private Method helper to reference parameter mappings and set the right value for the right parameter
	 *
	 * @param array $model (pass by reference) the set of values to map
	 * @param string $mapKey a dot syntax path determining which value to set
	 * @param mixed $value value for the given path
	 */
	protected function setMappedValue( &$model, $mapKey, $value ) {
		//get mapping
		if ( empty( $this->parameterMappings ) ) {
			// if parameterMappings is empty we can assume that no wrapper is defined
			// for the current endpoint and we will use the mapKey to define the mappings directly
			$mapPath = $mapKey;
		} elseif ( array_key_exists( $mapKey, $this->parameterMappings ) ) {
			// use only defined parameter mappings to construct $model
			$mapPath = $this->parameterMappings[ $mapKey ];
		} else {
			return;
		}

		$path = explode( '.', $mapPath );
		$temp = &$model;
		foreach ( $path as $key ) {
			if ( ! isset( $temp[ $key ] ) ) {
				$temp[ $key ] = null;
			}
			$temp = &$temp[ $key ];
		}
		$temp = $value;

	}

	/**
	 * maps values from the passed in model to those needed for the request
	 *
	 * @param array $requestConfig the passed in model
	 * @param array $model the set of defaults
	 *
	 * @return array A model ready for the body of a request
	 */
	protected function buildRequestModel( Array $requestConfig, Array $model = array() ) {
		foreach ( $requestConfig as $key => $value ) {
			$this->setMappedValue( $model, $key, $value );
		}

		return $model;
	}

	/**
	 * posts to the api with a supplied body
	 *
	 * @param array $body post body for the request
	 *
	 * @return array Result of the request
	 */
	public function create( Array $body = array() ) {
		return $this->callResource( 'post', null, array( 'body' => $body ) );
	}

	/**
	 * Makes a put request to the api with a supplied body
	 *
	 * @param $resourcePath
	 * @param array $body Put body for the request
	 *
	 * @return array Result of the request
	 * @throws Thrive_Dash_Api_SparkPost_Exception
	 */
	public function update( $resourcePath, Array $body = array() ) {
		return $this->callResource( 'put', $resourcePath, array( 'body' => $body ) );
	}

	/**
	 * Wrapper method for issuing GET request to current API endpoint
	 *
	 * @param string $resourcePath (optional) string resource path of specific resource
	 * @param array $query (optional) query string parameters
	 *
	 * @return array Result of the request
	 */
	public function get( $resourcePath = null, Array $query = array() ) {
		return $this->callResource( 'get', $resourcePath, array( 'query' => $query ) );
	}

	/**
	 * Wrapper method for issuing DELETE request to current API endpoint
	 *
	 * @param string $resourcePath (optional) string resource path of specific resource
	 * @param array $query (optional) query string parameters
	 *
	 * @return array Result of the request
	 */
	public function delete( $resourcePath = null, Array $query = array() ) {
		return $this->callResource( 'delete', $resourcePath, array( 'query' => $query ) );
	}


	/**
	 * assembles a URL for a request
	 *
	 * @param string $resourcePath path after the initial endpoint
	 * @param array $options array with an optional value of query with values to build a querystring from.
	 *
	 * @return string the assembled URL
	 */
	private function buildUrl( $resourcePath, $options ) {

		$url = $this->sparkpost->getUrl();
		$url .= "/{$this->endpoint}/";
		if ( ! is_null( $resourcePath ) ) {
			$url .= $resourcePath;
		}

		if ( ! empty( $options['query'] ) ) {
			$queryString = http_build_query( $options['query'] );
			$url .= '?' . $queryString;
		}

		return $url;
	}


	/**
	 * Prepares a body for put and post requests
	 *
	 * @param array $options array with an optional value of body with values to build a request body from.
	 *
	 * @return string|null A json encoded string or null if no body was provided
	 */
	private function buildBody( $options ) {
		$body = null;
		if ( ! empty( $options['body'] ) ) {
			$model        = $this->structure;
			$requestModel = $this->buildRequestModel( $options['body'], $model );
			$body         = json_encode( $requestModel );
		}

		return $body;
	}

	/**
	 * Private Method for issuing GET and DELETE request to current API endpoint
	 *
	 *  This method is responsible for getting the collection _and_
	 *  a specific entity from the API endpoint
	 *
	 *  If resourcePath parameter is omitted, then we fetch the collection
	 *
	 * @param string $action HTTP method type
	 * @param string $resourcePath (optional) string resource path of specific resource
	 * @param array $options (optional) query string parameters
	 *
	 * @return array Result set of action performed on resource
	 * @throws Thrive_Dash_Api_SparkPost_Exception
	 */
	private function callResource( $action, $resourcePath = null, $options = array() ) {
		$action = strtoupper( $action ); // normalize

		switch ( $action ) {
			case 'GET':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$url  = $this->buildUrl( $resourcePath, $options );
		$body = $this->buildBody( $options );

		//make request
		try {
			$response = $fn( $url, array(
				'body'    => $body,
				'timeout' => 15,
				'headers' => $this->sparkpost->getHttpHeaders(),
			) );

			if ( $response instanceof WP_Error ) {
				throw new Thrive_Dash_Api_SparkPost_Exception( $response->get_error_message() );
			}

			$statusCode = $response['response']['code'];

			// Handle 4XX responses, 5XX responses will throw an HttpAdapterException
			if ( $statusCode < 500 ) {
				$response_obj = json_decode( $response['body'] );
				$body         = (array) $response_obj;
				$error        = isset( $body['errors'][0] ) ? $body['errors'][0] : '';
				if ( $statusCode < 400 ) {
					return $body;
				} elseif ( $statusCode === 404 ) {
					throw new Thrive_Dash_Api_SparkPost_Exception( 'The specified resource does not exist', 404 );
				} else {
					throw new Thrive_Dash_Api_SparkPost_Exception( isset( $error->description ) ? $error->description : "" );
				}
			}

		} /*
     * Configuration Errors, and a catch all for other errors
     */
		catch ( Exception $exception ) {
			if ( $exception instanceof Thrive_Dash_Api_SparkPost_Exception ) {
				throw $exception;
			}

			throw new Thrive_Dash_Api_SparkPost_Exception( 'Unable to contact ' . ucfirst( $this->endpoint ) . ' API: ' . $exception->getMessage(), $exception->getCode() );
		}
	}

}
