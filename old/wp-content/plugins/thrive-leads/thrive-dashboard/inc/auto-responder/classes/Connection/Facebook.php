<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_Facebook extends Thrive_Dash_List_Connection_Abstract {

	private static $scopes = 'user_about_me,user_posts,user_photos';

	protected $_key = 'facebook';

	public $success_message = 'You are cool!';

	/**
	 * Thrive_Dash_List_Connection_Facebook constructor.
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
		return 'Facebook';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'facebook' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$app_id     = ! empty( $_REQUEST['app_id'] ) ? $_REQUEST['app_id'] : '';
		$app_secret = ! empty( $_REQUEST['app_secret'] ) ? $_REQUEST['app_secret'] : '';

		if ( empty( $app_id ) || empty( $app_secret ) ) {
			return $this->error( __( 'Both Client ID and Client Secret fields are required', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( array(
			'app_id'     => $app_id,
			'app_secret' => $app_secret,
		) );

		/* app has been authorized */
		if ( isset( $_REQUEST['code'] ) ) {

			$this->getApi()->getUser();

			$this->save();

			return true;
		}

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( __( 'You must give access to Facebook <a target="_blank" href="' . $this->getAuthorizeUrl() . '">here</a>.', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Facebook connected successfully!', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if the secret key is correct and it exists.
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_Facebook $api */
		$api = $this->getApi();

		$user = $api->getUser();

		$ready = false;

		if ( $user ) {
			try {
				$info = $api->api( '/me' );

				if ( is_array( $info ) ) {
					$ready = true;
				}
			} catch ( Tve_Facebook_Api_Exception $e ) {
				$ready = array(
					'success' => false,
					'message' => __( 'You must give access to Facebook <a target="_blank" href="' . $this->getAuthorizeUrl() . '">here</a>.', TVE_DASH_TRANSLATE_DOMAIN ),
				);
			}
		} else {

			$ready = array(
				'success' => false,
				'message' => __( 'You must give access to Facebook <a target="_blank" href="' . $this->getAuthorizeUrl() . '">here</a>.', TVE_DASH_TRANSLATE_DOMAIN ),
			);
		}

		return $ready;
	}

	/**
	 * @return string
	 */
	public function getAuthorizeUrl() {

		/** @var Thrive_Dash_Api_Facebook $api */
		$api = $this->getApi();

		$login_url = $api->getLoginUrl( array(
			'scope'        => self::$scopes,
			'redirect_uri' => add_query_arg( array(
				'page'       => 'tve_dash_api_connect',
				'api'        => 'facebook',
				'app_id'     => $this->param( 'app_id' ),
				'app_secret' => $this->param( 'app_secret' ),
			), admin_url( 'admin.php' ) ),
		) );

		return $login_url;
	}

	/**
	 * Those functions do not apply
	 *
	 * @return Thrive_Dash_Api_Facebook
	 */
	protected function _apiInstance() {

		$params = array(
			'appId'  => $this->param( 'app_id' ),
			'secret' => $this->param( 'app_secret' ),
		);

		return new Thrive_Dash_Api_Facebook( $params );
	}

	/**
	 * @param $fbid
	 * @param $comment_id
	 *
	 * @return array|string|void
	 */
	public function get_comment( $fbid, $comment_id ) {

		/** @var Thrive_Dash_Api_Facebook $api */
		$api = $this->getApi();

		$comment = array();

		$user = $api->getUser();
		if ( $user ) {
			try {
				$response = $api->api( '/' . $fbid . '_' . $comment_id );

				if ( is_array( $response ) ) {
					$comment = array(
						'id'      => $response['from']['id'],
						'name'    => $response['from']['name'],
						'picture' => 'https://graph.facebook.com/' . $response['from']['id'] . '/picture?type=large',
						'message' => $response['message'],
					);
				}
			} catch ( Tve_Facebook_Api_Exception $e ) {
				$comment = __( 'Error! The Facebook link provided is invalid', TVE_DASH_TRANSLATE_DOMAIN );
			}
		} else {
			$comment = __( 'Your Facebook connection expired. Go to API Connections to reactivate it!', TVE_DASH_TRANSLATE_DOMAIN );
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
