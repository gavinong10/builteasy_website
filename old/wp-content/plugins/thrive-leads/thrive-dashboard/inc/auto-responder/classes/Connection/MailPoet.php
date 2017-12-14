<?php


class Thrive_Dash_List_Connection_MailPoet extends Thrive_Dash_List_Connection_Abstract {
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
		return 'MailPoet';
	}

	/**
	 * check whether or not the MailPoet plugin is installed
	 */
	public function pluginInstalled() {
		return class_exists( 'WYSIJA' );
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mailpoet' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		if ( ! $this->pluginInstalled() ) {
			return $this->error( __( 'MailPoet plugin must be installed and activated.', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( array( 'e' => true ) );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( '<strong>' . $result . '</strong>)' );
		}
		/**
		 * finally, save the connection details
		 */
		$this->save();

		return true;
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		if ( ! $this->pluginInstalled() ) {
			return __( 'MailPoet plugin must be installed and activated.', TVE_DASH_TRANSLATE_DOMAIN );
		}

		return true;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		// no API instance needed here
		return null;
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		if ( ! $this->pluginInstalled() ) {
			$this->_error = __( 'MailPoet plugin could not be found.', TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}

		$model_list = WYSIJA::get( 'list', 'model' );
		$lists      = $model_list->get( array( 'name', 'list_id' ), array( 'is_enabled' => 1 ) );
		foreach ( $lists as $i => $list ) {
			$lists[ $i ]['id'] = $list['list_id'];
		}

		return $lists;
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
		if ( ! $this->pluginInstalled() ) {
			return __( 'MailPoet plugin is not installed / activated', TVE_DASH_TRANSLATE_DOMAIN );
		}

		list( $firstname, $lastname ) = $this->_getNameParts( $arguments['name'] );

		$user_data = array(
			'email'     => $arguments['email'],
			'firstname' => $firstname,
			'lastname'  => $lastname
		);

		$data_subscriber = array(
			'user'      => $user_data,
			'user_list' => array( 'list_ids' => array( $list_identifier ) )
		);

		/** @var WYSIJA_help_user $user_helper */
		$user_helper = WYSIJA::get( 'user', 'helper' );
		$result      = $user_helper->addSubscriber( $data_subscriber );
		if ( $result === false ) {
			$messages = $user_helper->getMsgs();
			if ( isset( $messages['xdetailed-errors'] ) ) {
				return implode( '<br><br>', $messages['xdetailed-errors'] );
			} elseif ( isset( $messages['error'] ) ) {
				return implode( '<br><br>', $messages['error'] );
			}

			return __( "Subscriber could not be saved", TVE_DASH_TRANSLATE_DOMAIN );
		}

		return true;

	}

	/**
	 * Return the connection email merge tag
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '[user:email]';
	}

} 