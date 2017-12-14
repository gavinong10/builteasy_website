<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 03.04.2015
 * Time: 17:31
 */
class Thrive_Dash_List_Connection_AWeber extends Thrive_Dash_List_Connection_Abstract {
	const APP_ID = '10fd90de';
	const CONSUMER_KEY = 'AkkjPM2epMfahWNUW92Mk2tl';
	const CONSUMER_SECRET = 'V9bzMop78pXTlPEAo30hxZF7dXYE6T6Ww2LAH95m';

	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * get the authorization URL for the AWeber Application
	 *
	 * @return string
	 */
	public function getAuthorizeUrl() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber      = $this->getApi();
		$callbackUrl = admin_url( 'admin.php?page=tve_dash_api_connect&api=aweber' );

		list ( $requestToken, $requestTokenSecret ) = $aweber->getRequestToken( $callbackUrl );

		update_option( 'thrive_aweber_rts', $requestTokenSecret );

		return $aweber->getAuthorizeUrl();
	}

	/**
	 * @return bool|void
	 */
	public function isConnected() {
		return $this->param( 'token' ) && $this->param( 'secret' );
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'AWeber';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'aweber' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber = $this->getApi();

		$aweber->user->tokenSecret  = get_option( 'thrive_aweber_rts' );
		$aweber->user->requestToken = $_REQUEST['oauth_token'];
		$aweber->user->verifier     = $_REQUEST['oauth_verifier'];

		try {
			list( $accessToken, $accessTokenSecret ) = $aweber->getAccessToken();
			$this->setCredentials( array(
				'token'  => $accessToken,
				'secret' => $accessTokenSecret
			) );
		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );

			return false;
		}

		$result = $this->testConnection();
		if ( $result !== true ) {
			$this->error( sprintf( __( 'Could not test AWeber connection: %s', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );

			return false;
		}

		$this->save();

		return true;
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber = $this->getApi();

		try {
			$account = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			$isValid = $account->lists;

			return true;
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_AWeber( self::CONSUMER_KEY, self::CONSUMER_SECRET );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber = $this->getApi();

		try {
			$lists   = array();
			$account = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			foreach ( $account->lists as $item ) {
				/** @var Thrive_Dash_Api_AWeber_Entry $item */
				$lists [] = array(
					'id'   => $item->data['id'],
					'name' => $item->data['name']
				);
			}

			return $lists;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}

	}

	/**
	 * add a contact to a list
	 *
	 * @param       $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		try {
			/** @var Thrive_Dash_Api_AWeber $aweber */
			$aweber  = $this->getApi();
			$account = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			$listURL = "/accounts/{$account->id}/lists/{$list_identifier}";
			$list    = $account->loadFromUrl( $listURL );

			# create a subscriber
			$params = array(
				'email'         => $arguments['email'],
				'name'          => $arguments['name'],
				'ip_address'    => $_SERVER['REMOTE_ADDR'],
			);

			if(isset($arguments['url'])) {
				$params['custom_fields'] = array(
					'Web Form URL' => $arguments['url'],
				);
			}
			// create custom fields
			$custom_fields = $list->custom_fields;

			try {
				$custom_fields->create( array( 'name' => 'Web Form URL' ) );
			} catch ( Exception $e ) {
			}

			if ( ! empty( $arguments['phone'] ) && ( $phone_field_name = $this->phoneCustomFieldExists( $list ) ) ) {
				$params['custom_fields'] = array(
					$phone_field_name => $arguments['phone']
				);
			}

			if ( ! empty( $arguments['aweber_tags'] ) ) {
				$params['tags'] = array( $arguments['aweber_tags'] );
			}

			$subscribers    = $list->subscribers;
			$new_subscriber = $subscribers->create( $params );

			if ( ! $new_subscriber ) {
				return sprintf( __( "Could not add contact: %s to list: %s", TVE_DASH_TRANSLATE_DOMAIN ), $arguments['email'], $list->name );
			}

		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	protected function phoneCustomFieldExists( $list ) {
		$customFieldsURL = $list->custom_fields_collection_link;
		$customFields    = $list->loadFromUrl( $customFieldsURL );
		foreach ( $customFields as $custom ) {
			if ( strtolower( $custom->name ) == 'phone' ) {
				//return the name of the phone custom field cos users can set its name as: Phone/phone/pHone/etc
				//used in custom_fields for subscribers parameters
				/** @see addSubscriber */
				return $custom->name;
			}
		}

		return false;
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$this->_directFormHtml( 'aweber/tags', $params );
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{!email}';
	}

} 