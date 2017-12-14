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
 * Class Thrive_Dash_List_Connection_Twitter
 */
class Thrive_Dash_List_Connection_Twitter extends Thrive_Dash_List_Connection_Abstract {

	private $url = 'https://api.twitter.com/1.1/';

	protected $_key = 'twitter';

	/**
	 * Thrive_Dash_List_Connection_Twitter constructor.
	 */
	public function __construct() {
		$this->setCredentials( Thrive_Dash_List_Manager::credentials( $this->_key ) );
	}

	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'social';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Twitter';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'twitter' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$access_token = ! empty( $_POST['access_token'] ) ? $_POST['access_token'] : '';
		$token_secret = ! empty( $_POST['token_secret'] ) ? $_POST['token_secret'] : '';
		$api_key      = ! empty( $_POST['api_key'] ) ? $_POST['api_key'] : '';
		$api_secret   = ! empty( $_POST['api_secret'] ) ? $_POST['api_secret'] : '';

		if ( empty( $access_token ) || empty( $token_secret ) || empty( $api_key ) || empty( $api_secret ) ) {
			return $this->error( __( 'All fields are required', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Incorrect credentials.', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Twitter connected successfully!', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if the credentials are correct.
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		$call = $this->url . 'account/verify_credentials.json';

		/** @var Thrive_Dash_Api_Twitter $api */
		$api = $this->getApi();

		$call     = $api->buildOauth( $call, 'GET' )->performRequest();
		$response = json_decode( $call, true );

		return empty( $response['errors'] );
	}

	/**
	 * @return Thrive_Dash_Api_Twitter
	 */
	protected function _apiInstance() {

		$params = array(
			'oauth_access_token'        => $this->param( 'access_token' ),
			'oauth_access_token_secret' => $this->param( 'token_secret' ),
			'consumer_key'              => $this->param( 'api_key' ),
			'consumer_secret'           => $this->param( 'api_secret' ),
		);

		return new Thrive_Dash_Api_Twitter( $params );
	}

	/**
	 * Get Twitter comment
	 *
	 * @param $id
	 *
	 * @return array|string|void
	 */
	public function get_comment( $id ) {

		/** @var Thrive_Dash_Api_Twitter $api */
		$api  = $this->getApi();
		$call = $this->url . 'statuses/show.json';

		$response = json_decode( $api->setGetfield( '?id=' . $id )->buildOauth( $call, 'GET' )->performRequest(), true );

		if ( ! empty( $response ) && is_array( $response ) ) {
			/* build the user picture so we can get the original one, not the small one */
			$user_picture = 'https://twitter.com/' . $response['user']['screen_name'] . '/profile_image?size=original';

			$comment = array(
				'screen_name' => $response['user']['screen_name'],
				'name'        => $response['user']['name'],
				'text'        => $response['text'],
				'url'         => $response['user']['url'],
				'picture'     => $user_picture,
			);
		} else {
			$comment = __( 'An error occured while getting the comment. Please verify your Twitter connection!', TVE_DASH_TRANSLATE_DOMAIN );
		}

		return $comment;
	}

	/**
	 * @return string
	 */
	public function customSuccessMessage() {
		return ' ';
	}

	protected function _getLists() {
	}

	public function addSubscriber( $list_identifier, $arguments ) {
	}
}
