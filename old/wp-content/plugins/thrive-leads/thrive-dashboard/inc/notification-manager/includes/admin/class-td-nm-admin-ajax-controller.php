<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Admin_Ajax_Controller {

	protected static $_instance;

	/**
	 * TD_NM_Admin_Ajax_Controller constructor.
	 * Protected constructor because we want to use it as singleton
	 */
	protected function __construct() {
	}

	/**
	 * Gets the SingleTone's instance
	 *
	 * @return TD_NM_Admin_Ajax_Controller
	 */
	public static function instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Sets the request's header with server protocol and status
	 * Sets the request's body with specified $message
	 *
	 * @param $message
	 * @param string $status
	 */
	protected function error( $message, $status = '404 Not Found' ) {
		status_header( 400 );
		wp_send_json( array(
			'error' => $message
		) );
	}

	/**
	 * Returns the params from $_POST or $_REQUEST
	 *
	 * @param $key
	 * @param null $default
	 *
	 * @return mixed|null|$default
	 */
	protected function param( $key, $default = null ) {
		return isset( $_POST[ $key ] ) ? $_POST[ $key ] : ( isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $default );
	}

	public function handle() {
		if ( ! check_ajax_referer( 'td_nm_admin_ajax_request', '_nonce', false ) ) {
			$this->error( sprintf( __( 'Invalid request', TVE_DASH_TRANSLATE_DOMAIN ) ) );
		}

		$route = $this->param( 'route' );

		$route       = preg_replace( '#([^a-zA-Z0-9-])#', '', $route );
		$method_name = $route . '_action';

		if ( ! method_exists( $this, $method_name ) ) {
			$this->error( sprintf( __( 'Method %s not implemented', TVE_DASH_TRANSLATE_DOMAIN ), $method_name ) );
		}

		return $this->{$method_name}();
	}

	public function notification_action() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				if ( ! ( $item = td_nm_save_notification( $model ) ) ) {
					$this->error( __( 'Notification could not be saved', TVE_DASH_TRANSLATE_DOMAIN ) );
				}

				return $item;
				break;
			case 'DELETE':
				$id = $this->param( 'ID' );
				if ( empty( $id ) ) {
					$this->error( __( 'Invalid parameters in delete', TVE_DASH_TRANSLATE_DOMAIN ) );
				}

				if ( ( $deleted = td_nm_delete_notification( $id ) ) === false ) {
					$this->error( __( 'Notification could not be deleted', TVE_DASH_TRANSLATE_DOMAIN ) );
				}

				return $deleted;
				break;
		}
	}

	public function action_action() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':

				$old_actions    = td_nm_get_actions( $model['notification_id'] );
				$update_actions = $old_actions;
				$id             = null;

				if ( is_numeric( $model['ID'] ) ) { //update because we have ID
					$update_actions[ $model['ID'] ] = $model;
					$id                             = $model['ID'];
				} else { //push new model in array
					$update_actions[] = $model;
					$id               = count( $update_actions ) - 1;  // The first action index is 0.
				}

				if ( $old_actions == $update_actions ) {
					return $id;
				}

				if ( td_nm_save_actions( $update_actions, $model['notification_id'] ) === false ) {
					$this->error( __( 'Action could not be saved', TVE_DASH_TRANSLATE_DOMAIN ) );
				}

				return $id;
				break;
			case 'DELETE':
				$saved = false;
				if ( ! is_numeric( $this->param( 'ID' ) ) || ! is_numeric( $this->param( 'notification_id' ) ) ) {
					$this->error( __( 'Invalid parameters in delete', TVE_DASH_TRANSLATE_DOMAIN ) );
				}

				$actions = td_nm_get_actions( $this->param( 'notification_id' ) );
				if ( ! empty( $actions ) ) {
					unset( $actions[ $this->param( 'ID' ) ] );
					$actions = array_values( $actions );
					foreach ( $actions as $key => &$item ) {
						$item['ID'] = $key;
					}

					if ( ( $saved = td_nm_save_actions( $actions, $this->param( 'notification_id' ) ) ) === false ) {
						$this->error( __( 'Action could not be deleted', TVE_DASH_TRANSLATE_DOMAIN ) );
					}
				}

				return $saved;
				break;
		}
	}

	public function trigger_action() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				if ( ( $saved = td_nm_save_trigger( $model, $model['ID'] ) ) === false ) {
					$this->error( __( 'Trigger could not be saved', TVE_DASH_TRANSLATE_DOMAIN ) );
				}

				return $saved;
				break;
		}
	}

	/**
	 * Set which connection should be used for sending emails
	 * Updates an WP Option
	 *
	 * @return bool
	 */
	public function activateservice_action() {
		update_option( 'tvd-nm-email-service', $_POST['service'] );

		return true;
	}

	/**
	 * Save API Connection by using API List Manager
	 *
	 * @return mixed
	 */
	public function setupconnection_action() {
		$connection = Thrive_Dash_List_Manager::connectionInstance( $this->param( 'api' ) );

		$_POST['api']        = $this->param( 'api' );
		$_POST['connection'] = $this->param( 'connection' );

		if ( is_string( $saved = $connection->readCredentials() ) ) {
			$this->error( $saved );
		}

		update_option( 'tvd-nm-email-service', $_POST['api'] );

		return $saved;
	}

	/**
	 * Tests an API Connection
	 *
	 * @return string
	 */
	public function connectiontest_action() {
		$connection = Thrive_Dash_List_Manager::connectionInstance( $this->param( 'service' ) );

		return is_string( $tested = $connection->testConnection() ) ? $this->error( $tested ) : array( 'success' => __( 'Connection OK', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * Deletes a wordpress notification from the post meta table
	 *
	 * @return bool
	 */
	public function deletenotification_action() {
		$key = $this->param( 'key' );
		if ( ! empty( $key ) && is_numeric( $key ) ) {
			$return = array();
			if ( $this->param( 'redirect' ) == true ) {
				$post_meta_value        = get_post_meta( $key, 'td_nm_wordpress_notification', true );
				$return['redirect_url'] = $post_meta_value['url'];
			}

			$return['status'] = delete_post_meta( $key, 'td_nm_wordpress_notification' );
			wp_send_json( $return );

		} else {
			$this->error( __( 'Invalid parameter type', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		return false;
	}

}
