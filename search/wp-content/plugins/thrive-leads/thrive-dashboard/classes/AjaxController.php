<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 22.12.2015
 * Time: 13:15
 */

/**
 * should handle all AJAX requests
 *
 * implemented as a singleton
 *
 * Class TVE_Dash_AjaxController
 */
class TVE_Dash_AjaxController {
	/**
	 * @var TVE_Dash_AjaxController
	 */
	private static $instance;

	/**
	 * singleton implementation
	 *
	 * @return TVE_Dash_AjaxController
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new TVE_Dash_AjaxController();
		}

		/**
		 * Remove these actions
		 * Because some other plugins have hook on these actions and some errors may occur
		 */
		remove_all_actions( 'wp_insert_post' );
		remove_all_actions( 'save_post' );

		return self::$instance;
	}

	/**
	 * gets a request value and returns a default if the key is not set
	 * it will first search the POST array
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	private function param( $key, $default = null ) {
		return isset( $_POST[ $key ] ) ? $_POST[ $key ] : ( isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $default );
	}

	/**
	 * entry-point for each ajax request
	 * this should dispatch the request to the appropriate method based on the "route" parameter
	 *
	 * @return array|object
	 */
	public function handle() {
		$route = $this->param( 'route' );
		$route      = preg_replace( '#([^a-zA-Z0-9-])#', '', $route );
		$methodName = $route . 'Action';

		return $this->{$methodName}();
	}

	/**
	 * save global settings for the plugin
	 */
	public function generalSettingsAction() {
		$allowed = array(
			'tve_social_fb_app_id',
			'tve_comments_facebook_admins',
			'tve_comments_disqus_shortname'
		);
		$field   = $this->param( 'field' );
		$value   = $this->param( 'value' );

		if ( ! in_array( $field, $allowed ) ) {
			exit();
		}

		tve_dash_update_option( $field, $value );

		switch ( $field ) {
			case 'tve_social_fb_app_id':
				$object = wp_remote_get( "https://graph.facebook.com/{$value}" );
				$body   = json_decode( wp_remote_retrieve_body( $object ) );
				if ( $body && ! empty( $body->link ) ) {
					return array( 'valid' => 1, 'elem' => 'tve_social_fb_app_id' );
				} else {
					return array( 'valid' => 0, 'elem' => 'tve_social_fb_app_id' );
				}
				break;
			case 'tve_comments_facebook_admins':
				if ( ! empty( $value ) ) {
					return array( 'valid' => 1, 'elem' => 'tve_comments_facebook_admins' );
				} else {
					return array( 'valid' => 0, 'elem' => 'tve_comments_facebook_admins' );
				}
				break;
			case 'tve_comments_disqus_shortname':
				if ( ! empty( $value ) ) {
					return array( 'valid' => 1, 'elem' => 'tve_comments_disqus_shortname' );
				} else {
					return array( 'valid' => 0, 'elem' => 'tve_comments_disqus_shortname' );
				}
				break;
			default:
				break;
		}

		exit();
	}

	public function licenseAction() {
		$email = ! empty( $_POST['email'] ) ? trim( $_POST['email'], ' ' ) : '';
		$key   = ! empty( $_POST['license'] ) ? trim( $_POST['license'], ' ' ) : '';
		$tag   = ! empty( $_POST['tag'] ) ? trim( $_POST['tag'], ' ' ) : false;

		$licenseManager = TVE_Dash_Product_LicenseManager::getInstance();
		$response       = $licenseManager->checkLicense( $email, $key, $tag );

		if ( ! empty( $response['success'] ) ) {
			$licenseManager->activateProducts( $response );
		}

		exit( json_encode( $response ) );
	}

	public function activeStateAction() {
		$_products = $this->param( 'products' );

		if ( empty( $_products ) ) {
			wp_send_json( array( 'items' => array() ) );
		}

		$installed = tve_dash_get_products();
		$to_show   = array();
		foreach ( $_products as $product ) {
			if ( $product === 'all' ) {
				$to_show = $installed;
				break;
			} elseif ( isset( $installed[ $product ] ) ) {
				$to_show [] = $installed[ $product ];
			}
		}

		$response = array();
		foreach ( $to_show as $_product ) {
			/** @var TVE_Dash_Product_Abstract $product */
			ob_start();
			$_product->render();
			$response[ $_product->getTag() ] = ob_get_contents();
			ob_end_clean();
		}

		wp_send_json( $response );

	}

	public function getErrorLogsAction() {

		$order_by     = $_GET['orderby'];
		$order        = $_GET['order'];
		$per_page     = $_GET['per_page'];
		$current_page = $_GET['current_page'];

		$order_by     = ! empty( $order_by ) ? $order_by : 'date';
		$order        = ! empty( $order ) ? $order : 'DESC';
		$per_page     = ! empty( $per_page ) ? $per_page : 10;
		$current_page = ! empty( $current_page ) ? $current_page : 1;

		return tve_dash_get_error_log_entries( $order_by, $order, $per_page, $current_page );

	}
}
