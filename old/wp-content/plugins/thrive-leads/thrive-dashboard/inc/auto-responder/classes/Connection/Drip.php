<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/15/2015
 * Time: 12:45 PM
 */
class Thrive_Dash_List_Connection_Drip extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Drip';
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Drip( $this->param( 'token' ) );
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'drip' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$token     = ! empty( $_POST['connection']['token'] ) ? $_POST['connection']['token'] : '';
		$client_id = ! empty( $_POST['connection']['client_id'] ) ? $_POST['connection']['client_id'] : '';

		if ( empty( $token ) || empty( $client_id ) ) {
			return $this->error( __( 'You must provide a valid Drip token and Client ID', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Drip using the provided Token and Client ID (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Drip connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		try {

			/** @var Thrive_Dash_Api_Drip $api */
			$api = $this->getApi();

			$accounts = $api->get_accounts();

			if ( empty( $accounts ) || ! is_array( $accounts ) ) {
				return __( "Drip connection could not be validated!", TVE_DASH_TRANSLATE_DOMAIN );
			}

			foreach ( $accounts['accounts'] as $account ) {
				if ( $account['id'] === $this->param( 'client_id' ) ) {
					return true;
				}
			}

			return false;

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		try {
			/** @var Thrive_Dash_Api_Drip $api */
			$api = $this->getApi();

			$campaigns = $api->get_campaigns( array(
				'account_id' => $this->param( 'client_id' ),
				'status'     => 'all',
			) );

			if ( empty( $campaigns ) || ! is_array( $campaigns ) ) {
				$this->_error = __( 'There is not Campaign in your Drip account to be fetched !', TVE_DASH_TRANSLATE_DOMAIN );

				return false;
			}

			$lists = array();

			foreach ( $campaigns['campaigns'] as $campaign ) {
				$lists[] = array(
					'id'   => $campaign['id'],
					'name' => $campaign['name']
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
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		$phone = ! empty( $arguments['phone'] ) ? $arguments['phone'] : '';

		$arguments['drip_optin'] = ! isset( $arguments['drip_optin'] ) ? 's' : $arguments['drip_optin'];
		$double_optin            = isset( $arguments['drip_optin'] ) && $arguments['drip_optin'] == 's' ? false : true;

		$url = wp_get_referer();

		try {
			/** @var Thrive_Dash_Api_Drip $api */
			$api         = $this->getApi();
			$proprieties = new stdClass();

			if ( isset( $first_name ) ) {
				$proprieties->thrive_first_name = $first_name;
			}

			if ( isset( $last_name ) ) {
				$proprieties->thrive_last_name = $last_name;
			}

			if ( isset( $phone ) ) {
				$proprieties->thrive_phone = $phone;
			}

			if ( isset( $arguments['drip_type'] ) && $arguments['drip_type'] == 'automation' ) {
				$proprieties->thrive_referer    = $url;
				$proprieties->thrive_ip_address = $_SERVER['REMOTE_ADDR'];

				if ( ! empty( $arguments['drip_field'] ) ) {
					foreach ( $arguments['drip_field'] as $field => $field_value ) {
						$proprieties->{$field} = $field_value;
					}
				}
			}

			$user = array(
				'account_id'    => $this->param( 'client_id' ),
				'campaign_id'   => $list_identifier,
				'email'         => $arguments['email'],
				'ip_address'    => $_SERVER['REMOTE_ADDR'],
				'custom_fields' => $proprieties
			);

			if ( isset( $arguments['drip_type'] ) && $arguments['drip_type'] == 'list' ) {
				$user['double_optin'] = $double_optin;
			}

			$lead = $api->create_or_update_subscriber( $user );
			if ( empty( $user ) ) {
				return __( "User could not be subscribed", TVE_DASH_TRANSLATE_DOMAIN );
			}

			if ( isset( $arguments['drip_type'] ) && $arguments['drip_type'] == 'list' || !isset( $arguments['drip_type']) ) {
				$client = array_shift( $lead['subscribers'] );

				$api->subscribe_subscriber( array(
					'account_id'   => $this->param( 'client_id' ),
					'campaign_id'  => $list_identifier,
					'email'        => $client['email'],
					'double_optin' => $double_optin,
				) );
			}

			$api->record_event( array(
				'account_id'  => $this->param( 'client_id' ),
				'action'      => 'Submitted a Thrive Leads form',
				'email'       => $arguments['email'],
				'proprieties' => $proprieties,
			) );

			return true;

		} catch ( Thrive_Dash_Api_Drip_Exception_Unsubscribed $e ) {
			$api->delete_subscriber( $user );

			return $this->addSubscriber( $list_identifier, $arguments );

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Allow the user to choose whether to have a single or a double optin for the form being edited
	 * It will hold the latest selected value in a cookie so that the user is presented by default with the same option selected the next time he edits such a form
	 *
	 * @param array $params
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$processed_params = array();

		$params['optin'] = empty( $params['optin'] ) ? ( isset( $_COOKIE['tve_api_drip_optin'] ) ? $_COOKIE['tve_api_drip_optin'] : 'd' ) : $params['optin'];
		setcookie( 'tve_api_drip_optin', $params['optin'], strtotime( '+6 months' ), '/' );

		if ( ! empty( $params ) ) {
			foreach ( $params as $k => $v ) {
				if ( strpos( $k, 'field[' ) !== false ) {
					$key                                     = str_replace( 'field[', '', $k );
					$processed_params['proprieties'][ $key ] = $v;
				} else {
					$processed_params[ $k ] = $v;
				}
			}
		}
		$this->_directFormHtml( 'drip/optin-type', $processed_params );
		$this->_directFormHtml( 'drip/proprieties', $processed_params );
	}

	public function renderBeforeListsSettings( $params = array() ) {

		$this->_directFormHtml( 'drip/select-type', $params );
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{{subscriber.email}}';
	}
}
