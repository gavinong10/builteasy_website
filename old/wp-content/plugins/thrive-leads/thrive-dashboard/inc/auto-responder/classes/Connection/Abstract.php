<?php

/**
 * Class Thrive_Dash_List_Connection_Abstract
 *
 * base class for all connections
 * acts as an high-level interface for the main functionalities exposed by the system
 */
abstract class Thrive_Dash_List_Connection_Abstract {
	/**
	 * @var array connection details (used for API calls)
	 */
	protected $_credentials = array();

	/**
	 * @var string internal key for the connection api
	 */
	protected $_key = null;

	/**
	 * @var mixed
	 */
	protected $_api;

	/**
	 * error message to be displayed in the editor
	 *
	 * @var string
	 */
	protected $_error = '';

	/**
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->_key = $key;
	}

	/**
	 * get the API Connection code to use in calls
	 *
	 * @return mixed
	 */
	public function getApi() {
		if ( ! isset( $this->_api ) ) {
			$this->_api = $this->_apiInstance();
		}

		return $this->_api;
	}

	/**
	 * @return array
	 */
	public function getCredentials() {
		return $this->_credentials;
	}

	/**
	 * @param array $connectionDetails
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function setCredentials( $connectionDetails ) {
		$this->_credentials = $connectionDetails;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->_key;
	}

	/**
	 * @param string $key
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function setKey( $key ) {
		$this->_key = $key;

		return $this;
	}

	/**
	 * whether or not this list is connected to the service (has been authenticated)
	 *
	 * @return bool
	 */
	public function isConnected() {
		return ! empty( $this->_credentials );
	}

	/**
	 * @return bool
	 */
	public function isRelated() {
		return false;
	}

	/**
	 * get connection parameter by name
	 *
	 * @param string $field
	 * @param string $default
	 *
	 * @return mixed
	 */
	public function param( $field, $default = null ) {
		return isset( $this->_credentials[ $field ] ) ? $this->_credentials[ $field ] : $default;
	}

	/**
	 * set connection parameter
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setParam( $field, $value ) {
		$this->_credentials[ $field ] = $value;

		return $this;
	}

	/**
	 * output directly the html for a connection form from views/setup
	 *
	 * @param string $filename
	 * @param array $data allows passing variables to the view file
	 */
	protected function _directFormHtml( $filename, $data = array() ) {
		include dirname( dirname( dirname( __FILE__ ) ) ) . '/views/setup/' . $filename . '.php';
	}

	/**
	 * @param $type
	 * @param $message
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	protected function _message( $type, $message ) {
		Thrive_Dash_List_Manager::message( $type, $message );

		return $this;
	}

	/**
	 * setup a global error message
	 *
	 * @param string $message
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function error( $message ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $message;
		}

		return $this->_message( 'error', $message );
	}

	/**
	 * setup a global success message
	 *
	 * @param string $message
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function success( $message ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}

		return $this->_message( 'success', $message );
	}

	/**
	 * save the connection details
	 *
	 * @see Thrive_Dash_List_Manager
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function save() {
		Thrive_Dash_List_Manager::save( $this );

		return $this;
	}

	/**
	 * disconnect (remove) this API connection
	 */
	public function disconnect() {
		$this->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $this );

		return $this;
	}

	/**
	 * get the last error message in communicating with the api
	 *
	 * @return string the error message
	 */
	public function getApiError() {
		return $this->_error;
	}

	/**
	 * explode fullname into first name and last name
	 *
	 * @param string $full_name
	 *
	 * @return array
	 */
	protected function _getNameParts( $full_name ) {
		if ( empty( $full_name ) ) {
			return array( '', '' );
		}
		$parts = explode( ' ', $full_name );

		if ( count( $parts ) == 1 ) {
			return array(
				$parts[0],
				''
			);
		}
		$last_name  = array_pop( $parts );
		$first_name = implode( ' ', $parts );

		return array(
			$first_name,
			$last_name
		);
	}

	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '[email]';
	}

	/**
	 * @return string the API connection title
	 */
	public abstract function getTitle();

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public abstract function outputSetupForm();

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error return the error message
	 *
	 * on success return true
	 *
	 * @return mixed
	 */
	public abstract function readCredentials();

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public abstract function testConnection();

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected abstract function _apiInstance();

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected abstract function _getLists();

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public abstract function addSubscriber( $list_identifier, $arguments );

	/**
	 * get all Subscriber Lists from this API service
	 * it will first check a local cache for the existing lists
	 *
	 * @see self::_getLists
	 *
	 * @param bool $use_cache if true, it will read the lists from a local cache (wp options)
	 *
	 * @return array|bool for error
	 */
	public function getLists( $use_cache = true ) {
		if ( ! $this->isConnected() ) {
			$this->_error = $this->getTitle() . ' ' . __( "is not connected", TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}
		$cache = get_option( 'thrive_auto_responder_lists', array() );
		if ( ! $use_cache || ! isset( $cache[ $this->getKey() ] ) ) {
			$lists = $this->_getLists();
			if ( $lists !== false ) {
				$cache[ $this->getKey() ] = $lists;
				update_option( 'thrive_auto_responder_lists', $cache );
			}
		} else {
			$lists = $cache[ $this->getKey() ];
		}

		return $lists;
	}

	/**
	 * Check connection and get all groups for a specific list
	 *
	 * @param $list_id
	 *
	 * @return bool
	 */
	public function getGroups( $list_id ) {
		if ( ! $this->isConnected() ) {
			$this->_error = $this->getTitle() . ' ' . __( "is not connected", TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}

		$params['list_id'] = $list_id;

		return $this->_getGroups( $params );
	}

	/**
	 * if an API instance has a special way of designating the list, it should override this method
	 * e.g. "Choose the mailing list you want your subscribers to be assigned to"
	 *
	 * @return string
	 */
	public function getListSubtitle() {
		return '';
	}

	/**
	 * get an array of warning messages (e.g. The access token will expire in xxx days. Click here to renew it)
	 * @return array
	 */
	public function getWarnings() {
		return array();
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		return;
	}

	public function renderBeforeListsSettings( $params = array() ) {
		return;
	}

	public function getLogoUrl() {
		return TVE_DASH_URL . '/inc/auto-responder/views/images/' . $this->getKey() . '.png';
	}

	public function prepareJSON() {
		$properties = array(
			'key'             => $this->getKey(),
			'connected'       => $this->isConnected(),
			'credentials'     => $this->getCredentials(),
			'title'           => $this->getTitle(),
			'type'            => $this->getType(),
			'logoUrl'         => $this->getLogoUrl(),
			'success_message' => $this->customSuccessMessage(),
		);

		return $properties;
	}

	/**
	 * Custom message for success state
	 * @return string
	 */
	public function customSuccessMessage() {
		return '';
	}
}
