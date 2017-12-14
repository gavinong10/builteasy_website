<?php

class Thrive_Dash_Api_SparkPost {

	public $transmission;

	/**
	 * Connection config for making requests.
	 */
	private $config;

	/**
	 * Default config values. Passed in values will override these.
	 */
	private static $apiDefaults = array(
		'host'      => 'api.sparkpost.com',
		'protocol'  => 'https',
		'port'      => 443,
		'strictSSL' => true,
		'key'       => '',
		'version'   => 'v1',
	);

	/**
	 * Sets up httpAdapter and config
	 *
	 * Sets up instances of sub libraries.
	 *
	 * @param String | array $settingsConfig - Hashmap that contains config values
	 *              for the SDK to connect to SparkPost. If its a string we assume that
	 *              its just they API Key.
	 */
	public function __construct( $settingsConfig ) {
		//config needs to be setup before adapter because of default adapter settings
		$this->setConfig( $settingsConfig );

		$this->transmission = new Thrive_Dash_Api_SparkPost_Connection( $this );
	}

	/**
	 * Helper function for getting the configuration for http requests
	 * @return $baseUrl
	 */
	public function getUrl() {
		$config  = $this->config;
		$baseUrl = $config['protocol'] . '://' . $config['host'] . ( $config['port'] ? ':' . $config['port'] : '' ) . '/api/' . $config['version'];

		return $baseUrl;
	}

	/**
	 * Creates an unwrapped api interface for endpoints that aren't yet supported.
	 * The new resource is attached to this object as well as returned
	 *
	 * @param string $endpoint
	 *
	 * @return Thrive_Dash_Api_SparkPost_APIResource - the unwrapped resource
	 */
	public function setupUnwrapped( $endpoint ) {
		$this->{$endpoint}           = new Thrive_Dash_Api_SparkPost_APIResource( $this );
		$this->{$endpoint}->endpoint = $endpoint;

		return $this->{$endpoint};
	}

	/**
	 * Merges passed in headers with default headers for http requests
	 */
	public function getHttpHeaders() {
		$defaultOptions = array(
			'Authorization' => $this->config['key'],
			'Content-Type'  => 'application/json',
		);

		return $defaultOptions;
	}

	/**
	 * Allows the user to pass in values to override the defaults and set their API key
	 *
	 * @param String | array $settingsConfig - Hashmap that contains config values
	 *              for the SDK to connect to SparkPost. If its a string we assume that
	 *              its just they API Key.
	 *
	 * @throws Exception
	 */
	public function setConfig( $settingsConfig ) {
		// if the config map is a string we should assume that its an api key
		if ( is_string( $settingsConfig ) ) {
			$settingsConfig = array( 'key' => $settingsConfig );
		}
		$trimmed = trim( $settingsConfig['key'] );
		// Validate API key because its required
		if ( ! isset( $settingsConfig['key'] ) || empty( $trimmed ) ) {
			throw new Exception( 'You must provide an API key' );
		}

		$this->config = self::$apiDefaults;

		// set config, overriding defaults
		foreach ( $settingsConfig as $configOption => $configValue ) {
			if ( key_exists( $configOption, $this->config ) ) {
				$this->config[ $configOption ] = $configValue;
			}
		}
	}
}

?>
