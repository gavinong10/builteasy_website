<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_Mailchimp extends Thrive_Dash_List_Connection_Abstract {
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
		return 'Mailchimp';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );
		if ( $related_api->isConnected() ) {
			$credentials = $related_api->getCredentials();
			$this->setParam( 'email', $credentials['email'] );
			$this->setParam( 'mandrill-key', $credentials['key'] );
		}


		$this->_directFormHtml( 'mailchimp' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$mandrill_key = ! empty( $_POST['connection']['mandrill-key'] ) ? $_POST['connection']['mandrill-key'] : '';

		if ( isset( $_POST['connection']['mailchimp_key'] ) ) {
			$_POST['connection']['mandrill-key'] = $_POST['connection']['key'];
			$_POST['connection']['key']          = $_POST['connection']['mailchimp_key'];
			$mandrill_key = $_POST['connection']['mandrill-key'];
		}

		if ( empty( $_POST['connection']['key'] ) ) {
			return $this->error( __( 'You must provide a valid Mailchimp key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Mailchimp using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/** @var Thrive_Dash_List_Connection_Mandrill $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );

		if ( ! empty( $mandrill_key ) ) {
			/**
			 * Try to connect to the email service too
			 */

			$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );
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

		return $this->success( __( 'Mailchimp connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/**
		 * just try getting a list as a connection test
		 */

		try {
			/** @var Thrive_Dash_Api_Mailchimp $mc */
			$mc = $this->getApi();

			$mc->request( 'lists' );
		} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
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
		return new Thrive_Dash_Api_Mailchimp( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {

		try {
			/** @var Thrive_Dash_Api_Mailchimp $mc */
			$mc = $this->getApi();

			$raw   = $mc->request( 'lists', array( 'count' => 1000 ) );
			$lists = array();

			if ( empty( $raw->total_items ) || empty( $raw->lists ) ) {
				return array();
			}
			foreach ( $raw->lists as $item ) {

				$lists [] = array(
					'id'   => $item->id,
					'name' => $item->name,
				);
			}

			return $lists;
		} catch ( Thrive_Dash_Api_Mailchimp_Error $e ) {
			$this->_error = $e->getMessage() . ' ' . __( "Please re-check your API connection details.", TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}
	}

	/**
	 * @param $params
	 *
	 * @return string
	 */
	protected function _getGroups( $params ) {

		$return    = '';
		$groupings = new stdClass();
		/** @var Thrive_Dash_Api_Mailchimp $api */
		$api   = $this->getApi();
		$lists = $api->request( 'lists', array( 'count' => 1000 ) );

		if ( empty( $params['list_id'] ) && ! empty( $lists ) ) {
			$params['list_id'] = $lists->lists[0]->id;
		}

		foreach ( $lists->lists as $list ) {
			if ( $list->id == $params['list_id'] ) {
				$groupings = $api->request( 'lists/' . $params['list_id'] . '/interest-categories', array( 'count' => 1000 ) );
			}
		}

		if ( $groupings->total_items > 0 ) {
			foreach ( $groupings->categories as $grouping ) {
				//if we have a grouping id in the params, we should only get that grouping
				if ( isset( $params['grouping_id'] ) && $grouping->id !== $params['grouping_id'] ) {
					continue;
				}
				$groups = $api->request( 'lists/' . $params['list_id'] . '/interest-categories/' . $grouping->id . '/interests', array( 'count' => 1000 ) );

				if ( $groups->total_items > 0 ) {
					$grouping->groups = $groups->interests;
				}
				$return[] = $grouping;
			}
		}

		return $return;
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
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		$optin     = isset( $arguments['mailchimp_optin'] ) && $arguments['mailchimp_optin'] == 's' ? 'subscribed' : 'pending';
		$interests = new stdClass();

		/** @var Thrive_Dash_Api_Mailchimp $api */
		try {
			$api = $this->getApi();
		} catch ( Thrive_Dash_Api_Mailchimp_Error $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Mailchimp Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

		$merge_tags = array(
			'FNAME' => $first_name,
			'LNAME' => $last_name,
			'NAME'  => $arguments['name']
		);

		if ( isset( $arguments['mailchimp_groupin'] ) && $arguments['mailchimp_groupin'] != '0' && ! empty( $arguments['mailchimp_group'] ) ) {
			$group_ids             = explode( ',', $arguments['mailchimp_group'] );
			$params['list_id']     = $list_identifier;
			$params['grouping_id'] = $arguments['mailchimp_groupin'];
			$grouping              = $this->_getGroups( $params );

			if ( ! empty( $grouping ) ) {
				$interests = array();
				foreach ( $grouping[0]->groups as $group ) {
					if ( in_array( $group->id, $group_ids ) ) {
						$interests[ $group->id ] = true;
					}
				}
			}
		}

		if ( isset( $arguments['phone'] ) && ! empty( $arguments['phone'] ) ) {
			$phone_tag  = false;
			$merge_vars = $this->getCustomFields( $list_identifier );
			foreach ( $merge_vars as $item ) {

				if ( $item->type == 'phone' || $item->name == $arguments['phone'] ) {
					$phone_tag                 = true;
					$merge_tags[ $item->name ] = $arguments['phone'];
					$merge_tags[ $item->tag ]  = $arguments['phone'];
				}
			}

			/**
			 * if we don't have a phone merge field let's create one
			 */
			if ( $phone_tag == false ) {
				try {
					$api->request( 'lists/' . $list_identifier . '/merge-fields', array(
						'name' => 'phone',
						'type' => 'phone',
						'tag'  => 'phone',
					), 'POST' );

					$merge_tags['phone'] = $arguments['phone'];
				} catch ( Thrive_Dash_Api_Mailchimp_Error $e ) {
					return $e->getMessage() ? $e->getMessage() : __( 'Unknown Mailchimp Error', TVE_DASH_TRANSLATE_DOMAIN );
				} catch ( Exception $e ) {
					return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
				}
			}
		}
		$email = strtolower( $arguments['email'] );
		try {
			$response = $api->request( 'lists/' . $list_identifier . '/members/' . md5( $email ), array(
				'status'        => 'subscribed',
				'merge_fields'  => $merge_tags,
				'interests'     => $interests,
				'email_address' => $arguments['email'],
				'status_if_new' => $optin,
			), 'PUT' );

			return true;
		} catch ( Thrive_Dash_Api_Mailchimp_Error $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Mailchimp Error', TVE_DASH_TRANSLATE_DOMAIN );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

	}

	/**
	 * Allow the user to choose whether to have a single or a double optin for the form being edited
	 * It will hold the latest selected value in a cookie so that the user is presented by default with the same option selected the next time he edits such a form
	 *
	 * @param array $params
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$params['optin'] = empty( $params['optin'] ) ? ( isset( $_COOKIE['tve_api_mailchimp_optin'] ) ? $_COOKIE['tve_api_mailchimp_optin'] : 'd' ) : $params['optin'];
		setcookie( 'tve_api_mailchimp_optin', $params['optin'], strtotime( '+6 months' ), '/' );
		$groups           = $this->_getGroups( $params );
		$params['groups'] = $groups;
		$this->_directFormHtml( 'mailchimp/api-groups', $params );
		$this->_directFormHtml( 'mailchimp/optin-type', $params );
	}

	/**
	 * @param $list
	 *
	 * @return mixed
	 */
	public function getCustomFields( $list ) {

		try {
			/** @var Thrive_Dash_Api_Mailchimp $api */
			$api = $this->getApi();

			$merge_vars = $api->request( 'lists/' . $list . '/merge-fields' );

			if ( $merge_vars->total_items === 0 ) {
				return array();
			}

		} catch ( Thrive_Dash_Api_Mailchimp_Error $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Mailchimp Error', TVE_DASH_TRANSLATE_DOMAIN );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

		return $merge_vars->merge_fields;
	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '*|EMAIL|*';
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
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );
		$related_api->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $related_api );

		return $this;
	}

}
