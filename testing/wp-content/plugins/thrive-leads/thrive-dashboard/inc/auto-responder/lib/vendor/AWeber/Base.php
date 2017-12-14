<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 18:00
 */


/**
 * AWeberAPIBase
 *
 * Base object that all AWeberAPI objects inherit from.  Allows specific pieces
 * of functionality to be shared across any object in the API, such as the
 * ability to introspect the collections map.
 *
 * @package
 * @version $id$
 */
class Thrive_Dash_Api_AWeber_Base {

	public $adapter = false;

	/**
	 * Maintains data about what children collections a given object type
	 * contains.
	 */
	static public $_collectionMap = array(
		'account'             => array( 'lists', 'integrations' ),
		'broadcast_campaign'  => array( 'links', 'messages', 'stats' ),
		'followup_campaign'   => array( 'links', 'messages', 'stats' ),
		'link'                => array( 'clicks' ),
		'list'                => array(
			'campaigns',
			'custom_fields',
			'subscribers',
			'web_forms',
			'web_form_split_tests'
		),
		'web_form'            => array(),
		'web_form_split_test' => array( 'components' ),
	);

	/**
	 * loadFromUrl
	 *
	 * Creates an object, either collection or entry, based on the given
	 * URL.
	 *
	 * @param mixed $url URL for this request
	 *
	 * @access public
	 * @return Thrive_Dash_Api_AWeber_Entry or Thrive_Dash_Api_AWeber_Collection
	 */
	public function loadFromUrl( $url ) {
		$data = $this->adapter->request( 'GET', $url );

		return $this->readResponse( $data, $url );
	}

	protected function _cleanUrl( $url ) {
		return str_replace( $this->adapter->app->getBaseUri(), '', $url );
	}

	/**
	 * readResponse
	 *
	 * Interprets a response, and creates the appropriate object from it.
	 *
	 * @param mixed $response Data returned from a request to the AWeberAPI
	 * @param mixed $url URL that this data was requested from
	 *
	 * @access protected
	 * @return mixed
	 */
	protected function readResponse( $response, $url ) {
		$this->adapter->parseAsError( $response );
		if ( ! empty( $response['id'] ) ) {
			return new Thrive_Dash_Api_AWeber_Entry( $response, $url, $this->adapter );
		} else if ( array_key_exists( 'entries', $response ) ) {
			return new Thrive_Dash_Api_AWeber_Collection( $response, $url, $this->adapter );
		}

		return false;
	}
} 