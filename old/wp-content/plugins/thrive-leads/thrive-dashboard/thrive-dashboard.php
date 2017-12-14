<?php
/**
 * Place where CONSTANTS, ACTIONS and FILTERS are defined
 * Implementations of all of those are placed into inc/hooks.php
 * Loads dependencies files
 */

/**
 * CONSTANTS
 */
defined( 'TVE_DASH_PATH' ) || define( 'TVE_DASH_PATH', $GLOBALS['tve_dash_loaded_from'] === 'plugins' ? rtrim( plugin_dir_path( __FILE__ ), "/\\" ) : rtrim( get_template_directory(), "/\\" ) . "/thrive-dashboard" );
defined( 'TVE_DASH_TRANSLATE_DOMAIN' ) || define( 'TVE_DASH_TRANSLATE_DOMAIN', 'thrive-dash' );

defined( 'TVE_DASH_VERSION' ) || define( 'TVE_DASH_VERSION', require dirname( __FILE__ ) . '/version.php' );
defined( 'TVE_SECRET' ) || define( 'TVE_SECRET', 'tve_secret' );

/**
 * REQUIRED FILES
 */
require_once TVE_DASH_PATH . '/inc/util.php';
require_once TVE_DASH_PATH . '/inc/hooks.php';
require_once TVE_DASH_PATH . '/inc/functions.php';
require_once TVE_DASH_PATH . '/inc/plugin-updates/plugin-update-checker.php';
require_once TVE_DASH_PATH . '/inc/notification-manager/class-td-nm.php';

if ( is_admin() ) {
	$features = tve_dash_get_features();
	if ( isset( $features['api_connections'] ) ) {
		require_once TVE_DASH_PATH . '/inc/auto-responder/admin.php';
	}
	if ( isset( $features['icon_manager'] ) ) {
		require_once( TVE_DASH_PATH . '/inc/icon-manager/classes/Tve_Dash_Thrive_Icon_Manager.php' );
	}
}

if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || apply_filters( 'tve_leads_include_auto_responder', true ) ) {  // I changed this for NM. We should always include autoresponder code in the solution
	require_once TVE_DASH_PATH . '/inc/auto-responder/misc.php';
}

/**
 * AUTO-LOADERS
 */
spl_autoload_register( 'tve_dash_autoloader' );

/**
 * ACTIONS
 */
add_action( 'init', 'tve_dash_init_action' );
add_action( 'init', 'tve_dash_load_text_domain' );

add_action( 'wp_enqueue_scripts', 'tve_dash_frontend_enqueue' );

if ( is_admin() ) {
	add_action( 'admin_menu', 'tve_dash_admin_menu', 10 );
	add_action( 'admin_enqueue_scripts', 'tve_dash_admin_enqueue_scripts' );
	add_action( 'admin_enqueue_scripts', 'tve_dash_admin_dequeue_conflicting', 90000 );
	add_action( 'wp_ajax_tve_dash_backend_ajax', 'tve_dash_backend_ajax' );

	add_action( 'wp_ajax_tve_dash_front_ajax', 'tve_dash_frontend_ajax_load' );
	add_action( 'wp_ajax_nopriv_tve_dash_front_ajax', 'tve_dash_frontend_ajax_load' );
}

/**
 * FILTERS
 */
