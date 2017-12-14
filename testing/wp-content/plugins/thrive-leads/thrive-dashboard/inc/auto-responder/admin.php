<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 14:16
 */
//add_action('admin_init', 'tve_dash_api_handle_save');

add_action( 'admin_menu', 'tve_dash_api_admin_menu', 20 );
add_action( 'admin_enqueue_scripts', 'tve_dash_api_admin_scripts' );
add_action( 'admin_notices', 'tve_dash_api_admin_notices', 9 );
add_action( 'wp_ajax_tve_dash_api_form_retry', 'tve_dash_api_form_retry' );
add_action( 'wp_ajax_tve_dash_api_delete_log', 'tve_dash_api_delete_log' );

if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	add_action( 'wp_ajax_tve_dash_api_handle_save', 'tve_dash_api_handle_save' );
	add_action( 'wp_ajax_tve_dash_api_handle_redirect', 'tve_dash_api_api_handle_redirect' );
} else {
	add_action( 'admin_init', 'tve_dash_api_handle_save' );
}


/**
 * FILTERS
 */
add_filter( 'tve_dash_localize', 'tve_dash_api_filter_localize' );
add_filter( 'tve_dash_include_ui', 'tve_dash_api_filter_ui_hooks' );

function tve_dash_api_admin_menu() {
	remove_submenu_page( 'thrive_admin_options', 'thrive_font_manager' );

	add_submenu_page( null, __( 'API Connections', TVE_DASH_TRANSLATE_DOMAIN ), __( 'API Connections', TVE_DASH_TRANSLATE_DOMAIN ), 'manage_options', 'tve_dash_api_connect', 'tve_dash_api_connect' );
	add_submenu_page( null, __( 'API Connections Error Log', TVE_DASH_TRANSLATE_DOMAIN ), __( 'API Connections Error Log', TVE_DASH_TRANSLATE_DOMAIN ), 'manage_options', 'tve_dash_api_error_log', 'tve_dash_api_error_log' );
}

/**
 * check for any expired connections (expired access tokens), or tokens that are about to expire and display global warnings / error messages
 */
function tve_dash_api_admin_notices() {
	$screen = get_current_screen();
	if ( $screen && $screen->base == 'admin_page_tve_dash_api_connect' ) {
		return;
	}

	require_once dirname( __FILE__ ) . '/misc.php';
	$connected_apis = Thrive_Dash_List_Manager::getAvailableAPIs( true );
	$warnings       = array();

	foreach ( $connected_apis as $instance ) {
		if ( $instance->param( '_nd' ) ) {
			continue;
		}
		$warnings = array_merge( $warnings, $instance->getWarnings() );
	}

	$nonce = sprintf( '<span class="nonce" style="display:none">%s</span>', wp_create_nonce( 'tve_api_dismiss' ) );

	$template = '<div class="%s notice is-dismissible tve-api-notice"><p>%s</p>%s</div>';

	$html = '';

	foreach ( $warnings as $err ) {
		$html .= sprintf( $template, 'error', $err, $nonce );
	}

	echo $html;
}

/**
 * main entry point
 */
function tve_dash_api_connect() {
	require_once dirname( __FILE__ ) . '/misc.php';

	$available_apis = Thrive_Dash_List_Manager::getAvailableAPIs();
	foreach ( $available_apis as $key => $api ) {
		/** @var Thrive_Dash_List_Connection_Abstract $api */
		if ( $api->isConnected() || $api->isRelated() ) {
			unset( $available_apis[ $key ] );
		}
	}
	$connected_apis = Thrive_Dash_List_Manager::getAvailableAPIs( true );

	foreach($connected_apis as $key => $api) {
		if($api->isRelated()) {
			unset( $connected_apis[ $key ] );
		}
	}

	$api_types = Thrive_Dash_List_Manager::$API_TYPES;

	$api_types = apply_filters( 'tve_filter_api_types', $api_types );

	$types = array();
	foreach ( $api_types as $type => $label ) {
		$types[] = array(
			'type'  => $type,
			'label' => $label
		);
	}

	$current_key = ! empty( $_REQUEST['api'] ) ? $_REQUEST['api'] : '';

	Thrive_Dash_List_Manager::flashMessages();

	include dirname( __FILE__ ) . '/views/admin-list.php';
}

/**
 * check to see if we currently need to save some credentials, early in the admin section (e.g. a redirect from Oauth)
 */
function tve_dash_api_handle_save() {
	require_once dirname( __FILE__ ) . '/misc.php';
	/**
	 * either a POST from a regular form, or an oauth redirect
	 */
	if ( empty( $_REQUEST['api'] ) && empty( $_REQUEST['oauth_token'] ) && empty( $_REQUEST['disconnect'] ) ) {
		return;
	}

	$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

	require_once dirname( __FILE__ ) . '/misc.php';
	$connection = Thrive_Dash_List_Manager::connectionInstance( $_REQUEST['api'] );
	if ( is_null( $connection ) ) {
		return;
	}

	$response = array(
		'success' => false,
		'message' => __( 'Unknown error occurred', TVE_DASH_TRANSLATE_DOMAIN ),
	);
	if ( ! empty( $_REQUEST['disconnect'] ) ) {
		$connection->disconnect()->success( $connection->getTitle() . ' ' . __( 'is now disconnected', TVE_DASH_TRANSLATE_DOMAIN ) );
		//delete active conection for thrive ovation
		$active_connection = get_option( 'tvo_api_delivery_service', false );
		if ( $active_connection && $active_connection == $_REQUEST['api'] ) {
			delete_option( 'tvo_api_delivery_service' );
		}
		tve_dash_remove_api_from_one_click_signups( $_REQUEST['api'] );
		$response['success'] = true;
		$response['message'] = __( 'Service disconnected', TVE_DASH_TRANSLATE_DOMAIN );
	} elseif ( ! empty( $_REQUEST['test'] ) ) {
		$result = $connection->testConnection();
		if ( is_array( $result ) && isset( $result['success'] ) && ! empty( $result['message'] ) ) {
			$response = $result;
		} else {
			$response['success'] = is_string( $result ) ? false : $result;
			$response['message'] = $response['success'] ? __( 'Connection works', TVE_DASH_TRANSLATE_DOMAIN ) : __( 'Connection Error', TVE_DASH_TRANSLATE_DOMAIN );
		}
	} else {
		$response['success'] = ( $saved = $connection->readCredentials() ) === true ? true : false;
		$response['message'] = $saved === true ? __( 'Connection established', TVE_DASH_TRANSLATE_DOMAIN ) : $saved;
	}

	if ( $doing_ajax ) {
		exit( json_encode( $response ) );
	}

	if ( $response['success'] !== true ) {
		update_option( 'tve_dash_api_error', $response['message'] );
		wp_redirect( admin_url( 'admin.php?page=tve_dash_api_connect' ) . '#failed/' . $_REQUEST['api'] );
		exit;
	}

	wp_redirect( admin_url( 'admin.php?page=tve_dash_api_connect' ) . '#done/' . $_REQUEST['api'] );
	exit();
}

/**
 *  Handles the creation of the authorization URL and redirection to that url for token generation purposes
 */
function tve_dash_api_api_handle_redirect() {

	if ( empty( $_REQUEST['api'] ) && empty( $_REQUEST['oauth_token'] ) && empty( $_REQUEST['disconnect'] ) ) {
		return;
	}

	$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

	require_once dirname( __FILE__ ) . '/misc.php';
	$connection = Thrive_Dash_List_Manager::connectionInstance( $_REQUEST['api'] );

	if ( is_null( $connection ) ) {
		return;
	}

	$response = array(
		'success' => false,
		'message' => __( 'Unknown error occurred', TVE_DASH_TRANSLATE_DOMAIN ),
	);

	$connection->setCredentials( $_POST['connection'] );;
	$result = $connection->getAuthorizeUrl();

	$response['success'] = ( filter_var( $result, FILTER_VALIDATE_URL ) ) === false ? false : true;
	$response['message'] = $response['success'] == false ? 'An unknown error has occurred' : $result;

	if ( $doing_ajax ) {
		exit( json_encode( $response ) );
	}

	wp_redirect( admin_url( 'admin.php?page=tve_dash_api_connect' ) . '#failed/' . $_REQUEST['api'] );

	exit();
}

/**
 * Enqueue specific scripts for api connections page
 *
 * @param string $hook
 */
function tve_dash_api_admin_scripts( $hook ) {
	$accepted_hooks = array(
		'admin_page_tve_dash_api_connect',
		'admin_page_tve_dash_api_error_log'
	);

	if ( ! in_array( $hook, $accepted_hooks ) ) {

		return;
	}

	if ( $hook === 'admin_page_tve_dash_api_error_log' ) {
		tve_dash_enqueue_script( 'tve-dash-api-admin-logs', TVE_DASH_URL . '/inc/auto-responder/views/js/admin-logs-list.js', array( 'jquery', 'backbone' ) );

		return;
	}

	/**
	 * global admin JS file for notifications
	 */
	tve_dash_enqueue_script( 'tve-dash-api-admin-global', TVE_DASH_URL . '/inc/auto-responder/views/js/admin-global.js', array(
		'jquery',
		'backbone'
	) );

	$api_response = array(
		'message' => get_option( 'tve_dash_api_error' ),
	);
	if ( ! empty( $api_response['message'] ) ) {
		wp_localize_script( 'tve-dash-api-admin-global', 'tve_dash_api_error', $api_response );
		delete_option( 'tve_dash_api_error' );
	}
}

/**
 * for now, just a dump of the error logs from the table
 */
function tve_dash_api_error_log() {
	include plugin_dir_path( __FILE__ ) . 'views/admin-error-logs.php';
}

/**
 * hide notices for a specific API connection
 */
function tve_dash_api_hide_notice() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'tve_api_dismiss' ) ) {
		exit( '-1' );
	}

	$key = $_POST['key'];

	require_once dirname( __FILE__ ) . '/misc.php';

	$connection = Thrive_Dash_List_Manager::connectionInstance( $key );
	$connection->setParam( '_nd', 1 )->save();

	exit( '1' );
}

/**
 * remove api connection from one click signups (new name: Signup Segue)
 */
function tve_dash_remove_api_from_one_click_signups( $apiName ) {
	$one_click_signups = get_posts( array( 'post_type' => 'tve_lead_1c_signup' ) );
	foreach ( $one_click_signups as $i => $item ) {
		$connections = get_post_meta( $item->ID, 'tve_leads_api_connections', true );
		foreach ( $connections as $j => $connection ) {
			if ( $connection['apiName'] == $apiName ) {
				unset( $connections[ $j ] );
			}
		}
		update_post_meta( $item->ID, 'tve_leads_api_connections', $connections );
	}
}

function tve_dash_api_filter_localize( $localize ) {
	$localize['actions']['api_handle_save']     = 'tve_dash_api_handle_save';
	$localize['actions']['api_handle_redirect'] = 'tve_dash_api_handle_redirect';

	return $localize;
}

function tve_dash_api_filter_ui_hooks( $hooks ) {
	//this hook includes the general scripts from dash
	//$hooks[] = 'admin_page_tve_dash_api_error_log';

	return $hooks;
}
