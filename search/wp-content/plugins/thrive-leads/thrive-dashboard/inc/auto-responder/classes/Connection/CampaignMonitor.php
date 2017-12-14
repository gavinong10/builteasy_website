<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_CampaignMonitor extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'Campaign Monitor';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitoremail' );
		if($related_api->isConnected()) {
			$this->setParam('new_connection', 1);
		}

		$this->_directFormHtml( 'campaignmonitor' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid Campaign Monitor key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Campaign Monitor using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/** @var Thrive_Dash_List_Connection_CampaignMonitorEmail $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitoremail' );

		if ( isset( $_POST['connection']['new_connection'] ) && intval( $_POST['connection']['new_connection'] ) === 1 ) {
			/**
			 * Try to connect to the email service too
			 */

			$r_result    = true;
			if ( ! $related_api->isConnected() ) {
				$r_result = $related_api->readCredentials();
			}

			if ( $r_result !== true ) {
				$this->disconnect();

				return $this->error( $r_result );
			}
		} else {
			/**
			 * let's make sure that the api was not edited and disconnect it
			 */
			$related_api->setCredentials( array() );
			Thrive_Dash_List_Manager::save( $related_api );
		}

		return $this->success( __( 'Campaign Monitor connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_CampaignMonitor $cm */

		$cm = $this->getApi();


		try {
			$clients = $cm->get_clients();

			/** @var Thrive_Dash_Api_CampaignMonitor_Clients $ci */
			$ci = $cm->client( $clients['0']->ClientID );
			$ci->get_lists();

		} catch ( Thrive_Dash_Api_CampaignMonitor_Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_CampaignMonitor( array(
				'api_key' => $this->param( 'key' ),
			)
		);
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_CampaignMonitor $api */
		$api = $this->getApi();

		$clients = $api->get_clients();

		/** @var Thrive_Dash_Api_CampaignMonitor_Clients $ci */
		$ci   = $api->client( $clients['0']->ClientID );
		$body = $ci->get_lists();

		$lists = array();
		foreach ( $body as $item ) {
			$lists [] = array(
				'id'   => $item->ListID,
				'name' => $item->Name,
			);
		}

		return $lists;
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|string true for success or string error message for failure
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		$args = array();
		/** @var Thrive_Dash_Api_CampaignMonitor $api */
		$api = $this->getApi();

		$args['EmailAddress']                           = $arguments['email'];
		$args['Resubscribe']                            = true;
		$args['RestartSubscriptionBasedAutoresponders'] = true;

		if ( ! empty( $arguments['name'] ) ) {
			$args['Name'] = $arguments['name'];
		}


		if ( ! empty( $arguments['phone'] ) ) {
			/** @var Thrive_Dash_Api_CampaignMonitor_Lists $list */
			$list          = $api->lists( $list_identifier );
			$custom_fields = $list->get_custom_fields();
			$phone         = false;
			if ( ! empty( $custom_fields ) ) {
				foreach ( $custom_fields as $field ) {
					if ( $field->FieldName == 'Phone' ) {
						$phone = true;
					}
				}
			}

			if ( $phone == false ) {
				$args = array(
					'FieldName'                 => 'Phone',
					'DataType'                  => 'Number',
					'Options'                   => array(),
					'VisibleInPreferenceCenter' => true,
				);

				$list->create_custom_field( $args );
			}
			$args['CustomFields'] = array(
				array(
					'Key'   => 'Phone',
					'Value' => strval( $arguments['phone'] ),
				)
			);
		}

		try {

			/** @var Thrive_Dash_Api_CampaignMonitor_Lists $list */
			$subscribers = $api->subscribers( $list_identifier );
			$subscribers->add( $args );

		} catch ( Thrive_Dash_Api_CampaignMonitor_Exception $e ) {
			return $e->getMessage();
		}


		return true;

	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '[email]';
	}

	/**
	 * disconnect (remove) this API connection
	 */
	public function disconnect() {

		$this->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $this );

		/**
		 * disconnect the email service too
		 */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitoremail' );
		$related_api->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $related_api );

		return $this;
	}

}