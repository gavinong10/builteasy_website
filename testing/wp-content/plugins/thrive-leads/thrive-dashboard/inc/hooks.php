<?php
/**
 * Holds ACTIONS/FILTERS implementations ONLY
 * User: Danut
 * Date: 12/8/2015
 * Time: 4:40 PM
 */

/**
 * Hook for "init" wp action
 */
function tve_dash_init_action() {
	if ( $GLOBALS['tve_dash_loaded_from'] === 'plugins' ) {
		defined( 'TVE_DASH_URL' ) || define( 'TVE_DASH_URL', rtrim( plugins_url(), "/\\" ) . "/" . trim( $GLOBALS['tve_dash_included']['folder'], "/\\" ) . '/thrive-dashboard' );
	} else {
		defined( 'TVE_DASH_URL' ) || define( 'TVE_DASH_URL', rtrim( get_template_directory_uri(), "/\\" ) . '/thrive-dashboard' );
	}

	defined( 'TVE_DASH_IMAGES_URL' ) || define( 'TVE_DASH_IMAGES_URL', TVE_DASH_URL . '/css/images' );

	require_once( TVE_DASH_PATH . '/inc/font-import-manager/classes/Tve_Dash_Font_Import_Manager.php' );
	require_once( TVE_DASH_PATH . '/inc/font-manager/font-manager.php' );
}

/**
 * Add main Thrive Dashboard item to menu
 */
function tve_dash_admin_menu() {
	add_menu_page(
		"Thrive Dashboard",
		"Thrive Dashboard",
		"manage_options",
		"tve_dash_section",
		"tve_dash_section",
		TVE_DASH_IMAGES_URL . '/logo-icon.png'
	);

	/**
	 * @param tve_dash_section parent slug
	 */
	do_action( 'tve_dash_add_menu_item', 'tve_dash_section' );

	$menus = array(
		'license_manager'     => array(
			'parent_slug' => 'tve_dash_section',
			'page_title'  => __( 'Thrive License Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'License Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_license_manager_section',
			'function'    => 'tve_dash_license_manager_section',
		),
		'general_settings'    => array(
			'parent_slug' => 'tve_dash_section',
			'page_title'  => __( 'Thrive General Settings', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'General Settings', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_general_settings_section',
			'function'    => 'tve_dash_general_settings_section',
		),
		'ui_toolkit'          => array(
			/**
			 * in order to not include the page in the menu -> use null as the first parameter
			 */
			'parent_slug' => defined( 'TVE_DEBUG' ) && TVE_DEBUG ? 'tve_dash_section' : null,
			'page_title'  => __( 'Thrive UI toolkit', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Thrive UI toolkit', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_ui_toolkit',
			'function'    => 'tve_dash_ui_toolkit',
		),
		/* Font Manager Page */
		'font_manager'        => array(
			'parent_slug' => null,
			'page_title'  => __( 'Thrive Font Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Thrive Font Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_font_manager',
			'function'    => 'tve_dash_font_manager_main_page',
		),
		/* Font Import Manager Page */
		'font_import_manager' => array(
			'parent_slug' => null,
			'page_title'  => __( 'Thrive Font Import Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Thrive Font Import Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_font_import_manager',
			'function'    => 'tve_dash_font_import_manager_main_page',
		),
		'icon_manager'        => array(
			'parent_slug' => null,
			'page_title'  => __( 'Icon Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Icon Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_icon_manager',
			'function'    => 'tve_dash_icon_manager_main_page',
		),
	);

	$thrive_products_order = tve_dash_get_menu_products_order();
	$menus                 = array_merge( $menus, apply_filters( 'tve_dash_admin_product_menu', array() ) );

	foreach ( $thrive_products_order as $menu_short ) {
		if ( array_key_exists( $menu_short, $menus ) ) {
			add_submenu_page( $menus[ $menu_short ]['parent_slug'], $menus[ $menu_short ]['page_title'], $menus[ $menu_short ]['menu_title'], $menus[ $menu_short ]['capability'], $menus[ $menu_short ]['menu_slug'], $menus[ $menu_short ]['function'] );
		}
	}
}

function tve_dash_icon_manager_main_page() {
	$tve_icon_manager = Tve_Dash_Thrive_Icon_Manager::instance();
	$tve_icon_manager->mainPage();
}

function tve_dash_font_import_manager_main_page() {
	$font_import_manager = Tve_Dash_Font_Import_Manager::getInstance();
	$font_import_manager->mainPage();
}

/**
 * Checks if the current screen (current admin screen) needs to have the dashboard scripts and styles enqueued
 *
 * @param string $hook current admin page hook
 */
function tve_dash_needs_enqueue( $hook ) {
	$accepted_hooks = array(
		'toplevel_page_tve_dash_section',
		'thrive-dashboard_page_tve_dash_license_manager_section',
		'thrive-dashboard_page_tve_dash_general_settings_section',
		'thrive-dashboard_page_tve_dash_ui_toolkit',
		'admin_page_tve_dash_ui_toolkit',
		'admin_page_tve_dash_api_connect',
		'admin_page_tve_dash_api_error_log',
		'admin_page_tve_dash_api_connect'
	);

	$accepted_hooks = apply_filters( 'tve_dash_include_ui', $accepted_hooks, $hook );

	return in_array( $hook, $accepted_hooks );
}

function tve_dash_admin_enqueue_scripts( $hook ) {
	if ( tve_dash_needs_enqueue( $hook ) ) {
		tve_dash_enqueue();
	}
}

/**
 * Dequeue conflicting scripts
 *
 * @param string $hook
 */
function tve_dash_admin_dequeue_conflicting( $hook ) {
	if ( isset( $GLOBALS['tve_dash_resources_enqueued'] ) || tve_dash_needs_enqueue( $hook ) ) {
		// NewsPaper messing about and including css / scripts all over the admin panel
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
	}
}

/**
 * enqueue the dashboard CSS and javascript files
 */
function tve_dash_enqueue() {
	$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

	tve_dash_enqueue_script( 'tve-dash-main-js', TVE_DASH_URL . '/js/dist/tve-dash' . $js_suffix, array(
		'jquery',
		'backbone'
	) );
	tve_dash_enqueue_script( 'jquery-zclip', TVE_DASH_URL . '/js/util/jquery.zclip.1.1.1/jquery.zclip.js', array( 'jquery' ) );
	tve_dash_enqueue_style( 'tve-dash-styles-css', TVE_DASH_URL . '/css/styles.css' );
	tve_dash_enqueue_script( 'tve-dash-api-wistia-popover', '//fast.wistia.com/assets/external/popover-v1.js', array(), '', true );


	$options = array(
		'dash_url'      => TVE_DASH_URL,
		'actions'       => array(
			'backend_ajax'        => 'tve_dash_backend_ajax',
			'ajax_delete_api_log' => 'tve_dash_api_delete_log',
			'ajax_retry_api_log'  => 'tve_dash_api_form_retry'
		),
		'routes'        => array(
			'settings'      => 'generalSettings',
			'license'       => 'license',
			'active_states' => 'activeState',
			'error_log'     => 'getErrorLogs'
		),
		'translations'  => array(
			'UnknownError'     => __( "Unknown error", TVE_DASH_TRANSLATE_DOMAIN ),
			'Deleting'         => __( 'Deleting...', TVE_DASH_TRANSLATE_DOMAIN ),
			'Testing'          => __( 'Testing...', TVE_DASH_TRANSLATE_DOMAIN ),
			'Loading'          => __( 'Loading...', TVE_DASH_TRANSLATE_DOMAIN ),
			'ConnectionWorks'  => __( 'Connection works!', TVE_DASH_TRANSLATE_DOMAIN ),
			'ConnectionFailed' => __( 'Connection failed!', TVE_DASH_TRANSLATE_DOMAIN ),
			'Unlimited'        => __( 'Unlimited', TVE_DASH_TRANSLATE_DOMAIN ),
			'RequestError'     => 'Request error, please contact Thrive developers !',
			'Copy'             => 'Copy',
		),
		'products'      => array(
			TVE_Dash_Product_LicenseManager::ALL_TAG => 'All products',
			TVE_Dash_Product_LicenseManager::TCB_TAG => 'Thrive Content Builder',
			TVE_Dash_Product_LicenseManager::TL_TAG  => 'Thrive Leads',
			TVE_Dash_Product_LicenseManager::TCW_TAG => 'Thrive Clever Widgets'
		),
		'license_types' => array(
			'individual' => __( 'Individual product', TVE_DASH_TRANSLATE_DOMAIN ),
			'full'       => __( 'Full membership', TVE_DASH_TRANSLATE_DOMAIN ),
		)
	);

	/**
	 * Allow vendors to hook into this
	 * TVE_Dash is the output js object
	 */
	$options = apply_filters( 'tve_dash_localize', $options );

	wp_localize_script( 'tve-dash-main-js', 'TVE_Dash_Const', $options );

	/**
	 * output the main tpls for backbone views used in dashboard
	 */
	add_action( 'admin_print_footer_scripts', 'tve_dash_backbone_templates' );
	/**
	 * set this flag here so we can later remove conflicting scripts / styles
	 */
	$GLOBALS['tve_dash_resources_enqueued'] = true;
}

/**
 * main entry point for the incoming ajax requests
 *
 * passes the request to the TVE_Dash_AjaxController for processing
 */
function tve_dash_backend_ajax() {
	$response = TVE_Dash_AjaxController::instance()->handle();

	wp_send_json( $response );
}


function tve_dash_reset_license() {
	$options = array(
		'tcb'    => 'tve_license_status|tve_license_email|tve_license_key',
		'tl'     => 'tve_leads_license_status|tve_leads_license_email|tve_leads_license_key',
		'tcw'    => 'tcw_license_status|tcw_license_email|tcw_license_key',
		'themes' => 'thrive_license_status|thrive_license_key|thrive_license_email',
		'dash'   => 'thrive_license'
	);

	if ( ! empty( $_POST['products'] ) ) {
		$filtered = array_intersect_key( $options, array_flip( $_POST['products'] ) );
		foreach ( explode( '|', implode( '|', $filtered ) ) as $option ) {
			delete_option( $option );
		}
		$message = 'Licenses reset for: ' . implode( ', ', array_keys( $filtered ) );

		$dash_license = get_option( 'thrive_license', array() );
		foreach ( $_POST['products'] as $prod ) {
			unset( $dash_license[ $prod ] );
		}
		update_option( 'thrive_license', $dash_license );

	}

	require dirname( dirname( ( __FILE__ ) ) ) . '/templates/settings/reset.phtml';
}

function tve_dash_load_text_domain() {
	$domain = TVE_DASH_TRANSLATE_DOMAIN;
	$locale = $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	$path = 'thrive-dashboard/languages/';
	//$path = apply_filters('tve_dash_filter_plugin_languages_path', $path);

	load_textdomain( $domain, WP_LANG_DIR . '/thrive/' . $domain . "-" . $locale . ".mo" );
	load_plugin_textdomain( $domain, false, $path );
}

/**
 *
 * fetches and outputs the backbone templates needed for thrive dashboard
 *
 * called on 'admin_print_footer_scripts'
 *
 */
function tve_dash_backbone_templates() {
	$templates = tve_dash_get_backbone_templates( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/backbone', 'backbone' );

	tve_dash_output_backbone_templates( $templates );
}

/**
 * output script nodes for backbone templates
 *
 * @param array $templates
 */
function tve_dash_output_backbone_templates( $templates, $prefix = '', $suffix = '' ) {

	foreach ( $templates as $tpl_id => $path ) {
		$tpl_id = $prefix . $tpl_id . $suffix;
		echo '<script type="text/template" id="' . $tpl_id . '">';
		include $path;
		echo '</script>';
	}
}

/**
 * include the backbone templates in the page
 *
 * @param string $dir basedir for template search
 * @param string $root
 */
function tve_dash_get_backbone_templates( $dir = null, $root = 'backbone' ) {
	if ( null === $dir ) {
		$dir = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/backbone';
	}

	$folders   = scandir( $dir );
	$templates = array();

	foreach ( $folders as $item ) {
		if ( in_array( $item, array( ".", ".." ) ) ) {
			continue;
		}

		if ( is_dir( $dir . '/' . $item ) ) {
			$templates = array_merge( $templates, tve_dash_get_backbone_templates( $dir . '/' . $item, $root ) );
		}

		if ( is_file( $dir . '/' . $item ) ) {
			$_parts     = explode( $root, $dir );
			$_truncated = end( $_parts );
			$tpl_id     = ( ! empty( $_truncated ) ? trim( $_truncated, '/\\' ) . '/' : '' ) . str_replace( array(
					'.php',
					'.phtml'
				), '', $item );

			$tpl_id = str_replace( array( '/', '\\' ), '-', $tpl_id );

			$templates[ $tpl_id ] = $dir . '/' . $item;
		}
	}

	return $templates;
}

/**
 * enqueue the frontend.js script
 */
function tve_dash_frontend_enqueue() {

	/**
	 * action filter - can be used to skip inclusion of dashboard frontend script
	 *
	 * each product should hook and return true if it needs this script
	 *
	 * @param bool $include
	 */
	$include = apply_filters( 'tve_dash_enqueue_frontend', false );

	if ( ! $include ) {
		return false;
	}

	tve_dash_enqueue_script( 'tve-dash-frontend', TVE_DASH_URL . '/js/dist/frontend.min.js', array( 'jquery' ), false, true );

	$data = array(
		'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		'is_crawler' => (bool) tve_dash_is_crawler( true ), // Apply the filter to allow overwriting the bot detection. Can be used by 3rd party plugins to force the initial ajax request
	);
	wp_localize_script( 'tve-dash-frontend', 'tve_dash_front', $data );
}

/**
 * main AJAX request entry point
 * this is sent out by thrive dashboard on every request
 *
 * $_POST[data] has the following structure:
 * [tcb] => array(
 *  key1 => array(
 *      action => some_tcb_action
 *      other_data => ..
 *  ),
 *  key2 => array(
 *      action => another_tcb_action
 *  )
 * ),
 * [tl] => array(
 * ..
 * )
 */
function tve_dash_frontend_ajax_load() {
	$response = array();
	if ( empty( $_POST['tve_dash_data'] ) || ! is_array( $_POST['tve_dash_data'] ) ) {
		wp_send_json( $response );
	}

	foreach ( $_POST['tve_dash_data'] as $key => $data ) {
		/**
		 * this is a really ugly one, but is required, because code from various plugins relies on $_POST / $_REQUEST
		 */
		foreach ( $data as $k => $v ) {
			$_REQUEST[ $k ] = $v;
			$_POST[ $k ]    = $v;
			$_GET[ $k ]     = $v;
		}
		/**
		 * action filter - each product should have its own implementation of this
		 *
		 * @param array $data
		 */
		$response[ $key ] = apply_filters( 'tve_dash_main_ajax_' . $key, array(), $data );

	}

	wp_send_json( $response );
}
