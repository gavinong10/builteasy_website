<?php
/**
 * Contains:
 * - autoloaders for the main library files
 * - wrappers over WordPress wp_remote_* functions
 * - helper functions
 */

/**
 * Wrapper over the WP wp_remote_get function
 *
 * @see wp_remote_get
 *
 * @param string $url Site URL to retrieve.
 * @param array $args Optional. Request arguments. Default empty array.
 *
 * @return WP_Error|array The response or WP_Error on failure.
 */
function tve_dash_api_remote_get( $url, $args = array() ) {
	$args['sslverify'] = false;
	/* SUPP-988 increased timeout to 15, it seems some hosts have some issues, not being able to resolve API URLs in 5 seconds */
	$args['timeout'] = 15;

	return wp_remote_get( $url, $args );
}

/**
 * SUPP-1146 strange issue on user's host - ssl requests timed out - seems this was the way to fix it
 * this is currently not used, but I'm leaving it here so that we have it for future references
 *
 * this is how it was used:
 *
 * add_action('http_api_curl', 'tve_dash_api_curl_ssl_version');
 *
 * @param resource $handle
 */
function tve_dash_api_curl_ssl_version( & $handle ) {
	curl_setopt( $handle, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
}

/**
 * Wrapper over the WP wp_remote_post function - all API POST requests pass through this function
 *
 * @see wp_remote_post
 *
 * @param string $url Site URL to retrieve.
 * @param array $args Optional. Request arguments. Default empty array.
 *
 * @return WP_Error|array The response or WP_Error on failure.
 */
function tve_dash_api_remote_post( $url, $args = array() ) {
	$args['sslverify'] = false;
	/* SUPP-988 increased timeout to 15, it seems some hosts have some issues, not being able to resolve API URLs in 5 seconds */
	$args['timeout'] = 15;

	return wp_remote_post( $url, $args );
}

/**
 *
 * Wrapper over the WP wp_remote_request function - API external calls pass through this function
 * sets some default parameters
 *
 * @see wp_remote_request
 *
 * @param string $url Site URL to retrieve.
 * @param array $args Optional. Request arguments. Default empty array.
 *
 * @return WP_Error|array The response or WP_Error on failure.
 */
function tve_dash_api_remote_request( $url, $args = array() ) {
	$args['sslverify'] = false;

	return wp_remote_request( $url, $args );
}

/**
 * handle the actual autoload (require_once)
 *
 * @param string $basepath
 * @param string $className
 *
 * @return bool|void
 */
function _tve_dash_api__autoload( $basepath, $className ) {
	$parts = explode( '_', $className );
	if ( empty( $parts ) ) {
		return false;
	}

	$filename = array_pop( $parts );

	$path = $basepath;
	foreach ( $parts as $part ) {
		$path .= $part . '/';
	}

	$path .= $filename . '.php';
	if ( ! file_exists( $path ) ) {
		return false;
	}

	require_once $path;

	return true;
}

/**
 * autoload any class from the lib/vendor folder
 *
 * @param string $className
 *
 * @return bool|void
 */
function tve_dash_api_vendor_loader( $className ) {
	$namespace = 'Thrive_Dash_Api_'; // = thrive
	if ( strpos( $className, $namespace ) !== 0 ) {
		return false;
	}

	$basedir = rtrim( dirname( __FILE__ ), '/\\' ) . '/lib/vendor/';

	return _tve_dash_api__autoload( $basedir, str_replace( $namespace, '', $className ) );
}

/**
 *
 * autoload "internal" auto-responder component classes (located in inc/auto-responder/classes folder)
 *
 * @param string $className
 *
 * @return bool
 */
function tve_dash_api_classes_loader( $className ) {
	$namespace = 'Thrive_Dash_List_';
	if ( strpos( $className, $namespace ) !== 0 ) {
		return false;
	}

	$basedir = rtrim( dirname( __FILE__ ), '/\\' ) . '/classes/';

	return _tve_dash_api__autoload( $basedir, str_replace( $namespace, '', $className ) );
}

spl_autoload_register( 'tve_dash_api_vendor_loader' );
spl_autoload_register( 'tve_dash_api_classes_loader' );

/**
 * AJAX call handler for API logs that are being retried
 * If the subscription is made with success the log is deleted from db
 */
function tve_dash_api_form_retry() {
	$connection_name = ! empty( $_POST['connection_name'] ) ? $_POST['connection_name'] : null;
	$list_id         = ! empty( $_POST['list_id'] ) ? $_POST['list_id'] : null;
	$email           = ! empty( $_POST['email'] ) ? $_POST['email'] : null;
	$name            = ! empty( $_POST['name'] ) ? $_POST['name'] : '';
	$phone           = ! empty( $_POST['phone'] ) ? $_POST['phone'] : '';
	$log_id          = ! empty( $_POST['log_id'] ) ? intval( $_POST['log_id'] ) : null;
	$url             = ! empty( $_POST['url'] ) ? $_POST['url'] : null;

	if ( empty( $connection_name ) ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => __( 'Connection is not specified !', TVE_DASH_TRANSLATE_DOMAIN )
		) ) );
	}

	if ( empty( $list_id ) ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => __( 'Where should I subscribe this user? List is not specified !', TVE_DASH_TRANSLATE_DOMAIN )
		) ) );
	}

	if ( empty( $email ) ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => __( 'Email is not specified !', TVE_DASH_TRANSLATE_DOMAIN )
		) ) );
	}

	$data = array(
		'email' => $email,
		'name'  => $name,
		'phone' => $phone,
		'url'   => $url,
	);

	if ( $list_id == "asset" ) {

		$api = Thrive_Dash_List_Manager::connectionInstance( $connection_name );
		if ( ! $api ) {
			$response = __( 'Cannot establish API connection', TVE_DASH_TRANSLATE_DOMAIN );
		} else {

			$post_data['_asset_group'] = $_POST['_asset_group'];
			$post_data['email']        = $_POST['email'];
			if ( isset( $_POST['name'] ) ) {
				$post_data['name'] = $_POST['name'];
			}

			$response = true;
			try {
				$api->sendEmail( $post_data );
			} catch ( Exception $e ) {
				$response = $e->getMessage();
			}

		}

	} else {
		$response = tve_api_add_subscriber( $connection_name, $list_id, $data, false );
	}


	if ( $response !== true ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => $response
		) ) );
	}

	if ( ! empty( $log_id ) ) {

		global $wpdb;

		$delete_result = $wpdb->delete( $wpdb->prefix . 'tcb_api_error_log', array( 'id' => $log_id ), array( '%d' ) );

		if ( $delete_result === false ) {
			exit( json_encode( array(
				'status'  => 'error',
				'message' => __( "Subscription was made with success but we could not delete the log from database !", TVE_DASH_TRANSLATE_DOMAIN )
			) ) );
		}
	}

	exit( json_encode( array(
		'status'  => 'success',
		'message' => __( 'Subscription was made with success !', TVE_DASH_TRANSLATE_DOMAIN )
	) ) );
}

/**
 * AJAX call handler to delete API's logs
 */
function tve_dash_api_delete_log() {
	$log_id = ! empty( $_POST['log_id'] ) ? intval( $_POST['log_id'] ) : null;

	if ( empty( $log_id ) ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => __( "Log ID is not valid !", TVE_DASH_TRANSLATE_DOMAIN )
		) ) );
	}

	global $wpdb;

	$delete_result = $wpdb->delete( $wpdb->prefix . 'tcb_api_error_log', array( 'id' => $log_id ), array( '%d' ) );
	if ( $delete_result === false ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => sprintf( __( "An error occurred: %s", TVE_DASH_TRANSLATE_DOMAIN ), $wpdb->last_error )
		) ) );
	} else if ( $delete_result === 0 ) {
		exit( json_encode( array(
			'status'  => 'error',
			'message' => sprintf( __( "The log with ID: %s could not be found !", TVE_DASH_TRANSLATE_DOMAIN ), $log_id )
		) ) );
	}

	exit( json_encode( array(
		'status'  => 'success',
		'message' => sprintf( __( "API Log with ID: %s has been deleted with success !", TVE_DASH_TRANSLATE_DOMAIN ), $log_id )
	) ) );
}

if ( ! class_exists( 'Thrive_List_Manager' ) ) {
	class Thrive_List_Manager extends Thrive_Dash_List_Manager {

	}
}

if ( ! class_exists( 'Thrive_List_Connection_Mandrill' ) ) {
	class Thrive_List_Connection_Mandrill extends Thrive_Dash_List_Connection_Mandrill {

	}
}

if ( ! class_exists( 'Thrive_List_Connection_Postmark' ) ) {
	class Thrive_List_Connection_Postmark extends Thrive_Dash_List_Connection_Postmark {

	}
}

if ( ! class_exists( 'Thrive_List_Connection_SparkPost' ) ) {
	class Thrive_List_Connection_SparkPost extends Thrive_Dash_List_Connection_SparkPost {

	}
}

if ( ! class_exists( 'Thrive_List_Connection_Mailgun' ) ) {
	class Thrive_List_Connection_Mailgun extends Thrive_Dash_List_Connection_Mailgun {

	}
}

if ( ! class_exists( 'Thrive_List_Connection_Awsses' ) ) {
	class Thrive_List_Connection_Awsses extends Thrive_Dash_List_Connection_Awsses {

	}
}

if ( ! class_exists( 'Thrive_List_Connection_SendinblueEmail' ) ) {
	class Thrive_List_Connection_SendinblueEmail extends Thrive_Dash_List_Connection_SendinblueEmail {

	}
}
