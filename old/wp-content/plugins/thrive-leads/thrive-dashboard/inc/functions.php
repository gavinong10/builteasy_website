<?php
/**
 * Holds different helper functions
 * User: Danut
 * Date: 12/8/2015
 * Time: 5:31 PM
 */

/**
 * Main Dashboard section
 * @includes dashboard.phtml template
 */
function tve_dash_section() {
	$products = tve_dash_get_products();

	$thrive_features = tve_dash_get_features();

	require_once TVE_DASH_PATH . '/templates/sections/dashboard.phtml';
}

/**
 * Licence Manager section
 * @includes licence_manager.phtml template
 */
function tve_dash_license_manager_section() {
	$products = tve_dash_get_products();

	$returnUrl = ! empty( $_REQUEST['return'] ) ? $_REQUEST['return'] : '';

	/**
	 * Filter products to only active once
	 * @var $product TVE_Dash_Product_Abstract
	 */
	foreach ( $products as $key => $product ) {
		if ( ! $product->isActivated() ) {
			unset( $products[ $key ] );
		}
	}

	require_once TVE_DASH_PATH . '/templates/sections/license_manager.phtml';
}

/**
 * Get all settings for the general settings view.
 * Uses 'tve_dash_general_settings_filter' filter if other plugins want to add their settings
 * @return array|mixed|void
 */
function tve_dash_get_general_settings() {
	$settings = array(
		array(
			'name'         => 'tve_social_fb_app_id',
			'id'           => 'tve_social_fb_app_id',
			'class'        => 'tvd-validate tve_social_fb_app_id',
			'data-success' => 'The App ID provided is valid',
			'data-error'   => 'The App ID provided is invalid',
			'label'        => 'Facebook App ID',
			'description'  => __( 'Facebook ID that will be used in our apps.', TVE_DASH_TRANSLATE_DOMAIN ),
			'value'        => tve_dash_get_option( 'tve_social_fb_app_id', '' ),
			'type'         => 'text',
			'multiple'     => false
		),
		array(
			'name'         => 'tve_comments_facebook_admins',
			'id'           => 'tve_comments_facebook_admins',
			'class'        => 'tvd-validate tve_comments_facebook_admins',
			'data-success' => '',
			'data-error'   => 'This field can not be empty',
			'label'        => 'Facebook Admins',
			'description'  => __( 'Admins that will moderate the comments', TVE_DASH_TRANSLATE_DOMAIN ),
			'value'        => tve_dash_get_option( 'tve_comments_facebook_admins', '' ),
			'type'         => 'text',
			'multiple'     => true
		),
		array(
			'name'         => 'tve_comments_disqus_shortname',
			'id'           => 'tve_comments_disqus_shortname',
			'class'        => 'tvd-validate tve_comments_disqus_shortname',
			'data-success' => '',
			'data-error'   => 'This field can not be empty',
			'label'        => 'Disqus forum name',
			'description'  => __( 'Your forum name is part of the address that you login to "http://xxxxxxxx.disqus.com" - the xxxxxxx is your shortname.  For example, with this URL: https://hairfreelife.disqus.com/ the shortname is "hairfreelife', TVE_DASH_TRANSLATE_DOMAIN ),
			'value'        => tve_dash_get_option( 'tve_comments_disqus_shortname', '' ),
			'type'         => 'text',
			'multiple'     => false
		)
	);
	$settings = apply_filters( 'tve_dash_general_settings_filter', $settings );

	return $settings;
}

/**
 * wrapper over the wp get_option function
 *
 * @param $name
 * @param bool $default
 *
 * @return mixed|void
 */
function tve_dash_get_option( $name, $default = false ) {
	$value = get_option( $name, $default );

	return $value;
}

/**
 * wrapper over the wp update_option() function
 *
 * @param string $name
 * @param mixed $value
 *
 * @return bool
 */
function tve_dash_update_option( $name, $value ) {
	return update_option( $name, $value );
}

/**
 * General Settings section
 * @includes general_settings.phtml template
 */
function tve_dash_general_settings_section() {
	$settings = tve_dash_get_general_settings();
	/* text, radio, checkbox, password */
	$accepted_settings = array( 'text' );
	require_once TVE_DASH_PATH . '/templates/settings/general_settings.phtml';
}

/**
 * Thrive UI toolkit - should contain a list of all UI components used in Thrive admin UI
 *
 * @see templates/ui.phtml
 */
function tve_dash_ui_toolkit() {
	require TVE_DASH_PATH . '/templates/ui.phtml';
}

/**
 * wrapper over the wp_enqueue_script functions
 * it will add the version
 *
 * @param $handle
 * @param string $src
 * @param array $deps
 * @param bool $ver
 * @param bool $in_footer
 */
function tve_dash_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {
	if ( $ver === false ) {
		$ver = TVE_DASH_VERSION;
	}
	if ( defined( 'TVE_DEBUG' ) && TVE_DEBUG ) {
		$src = preg_replace( '/\.min.js$/', '.js', $src );
	}
	wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
}

/**
 * Wrapper over the wp enqueue_style function
 * It will add the version
 *
 * @param $handle
 * @param $src
 * @param array $deps
 * @param bool $ver
 * @param $media
 */
function tve_dash_enqueue_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	if ( $ver === false ) {
		$ver = TVE_DASH_VERSION;
	}
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}

/**
 * Returns the products to be displayed on Main Dashboard Section
 * @calls apply_filters('tve_dash_installed_products')
 * @return array
 */
function tve_dash_get_products() {
	$return = array();

	foreach ( apply_filters( 'tve_dash_installed_products', array() ) as $_product ) {
		$return[ $_product->getTag() ] = $_product;
	}

	return $return;
}

/**
 * get a list of all available thrive features
 *
 * uses the tve_dash_features filter to populate the array with pre-existing functionalities
 * the filter should just add one of the keys to the array:
 *
 *      api_connections
 *      font_manager
 *      icon_manager
 *      general_settings
 *
 * @return array
 */
function tve_dash_get_features() {
	$dash_features = array(
		'api_connections'  => array(
			'icon'        => 'tvd-icon-exchange',
			'title'       => __( "API Connections", TVE_DASH_TRANSLATE_DOMAIN ),
			'description' => __( "Connect to your email marketing system, reCaptcha, email delivery services & more.", TVE_DASH_TRANSLATE_DOMAIN ),
			'btn_link'    => add_query_arg( 'page', 'tve_dash_api_connect', admin_url( 'admin.php' ) ),
			'btn_text'    => __( "Manage Connections", TVE_DASH_TRANSLATE_DOMAIN ),
		),
		'font_manager'     => array(
			'icon'        => 'tvd-icon-font',
			'title'       => __( "Custom Fonts", TVE_DASH_TRANSLATE_DOMAIN ),
			'description' => __( "Add & edit Google Fonts and other custom fonts to use in your Thrive products.", TVE_DASH_TRANSLATE_DOMAIN ),
			'btn_link'    => add_query_arg( 'page', 'tve_dash_font_manager', admin_url( 'admin.php' ) ),
			'btn_text'    => __( "Manage Fonts", TVE_DASH_TRANSLATE_DOMAIN ),
		),
		'icon_manager'     => array(
			'icon'        => 'tvd-icon-rocket',
			'title'       => __( "Retina Icons", TVE_DASH_TRANSLATE_DOMAIN ),
			'description' => __( "Add & edit fully scalable icons with our font icon manager.", TVE_DASH_TRANSLATE_DOMAIN ),
			'btn_link'    => add_query_arg( 'page', 'tve_dash_icon_manager', admin_url( 'admin.php' ) ),
			'btn_text'    => __( "Manage Icons", TVE_DASH_TRANSLATE_DOMAIN ),
		),
		'general_settings' => array(
			'icon'        => 'tvd-icon-cogs',
			'title'       => __( "General Settings", TVE_DASH_TRANSLATE_DOMAIN ),
			'description' => __( "Shared settings between multiple themes and plugins.", TVE_DASH_TRANSLATE_DOMAIN ),
			'btn_link'    => add_query_arg( 'page', 'tve_dash_general_settings_section', admin_url( 'admin.php' ) ),
			'btn_text'    => __( "Manage Settings", TVE_DASH_TRANSLATE_DOMAIN ),
		)
	);

	$features_to_show = apply_filters( 'tve_dash_features', array() );

	$dash_features = apply_filters( 'tve_dash_filter_features', $dash_features );

	$thrive_features = array();

	foreach ( $dash_features as $_feature_key => $_data ) {
		if ( empty( $features_to_show[ $_feature_key ] ) ) {
			continue;
		}
		$thrive_features[ $_feature_key ] = $_data;
	}

	return $thrive_features;
}

/**
 * SPL loader
 *
 * @param $className
 *
 * @return bool
 */
function tve_dash_autoloader( $className ) {
	$namespace = 'TVE_Dash_';
	if ( strpos( $className, $namespace ) !== 0 ) {
		return false;
	}

	$basedir = rtrim( dirname( dirname( __FILE__ ) ), '/\\' ) . '/classes/';

	return tve_dash_autoload( $basedir, str_replace( $namespace, '', $className ) );
}

/**
 * Loads the class based on $path and $className
 *
 * @param $path
 * @param $className
 *
 * @return bool
 */
function tve_dash_autoload( $path, $className ) {
	$parts = explode( '_', $className );
	if ( empty( $parts ) ) {
		return false;
	}

	$filename = array_pop( $parts );

	foreach ( $parts as $part ) {
		$part = str_replace( array( 'Model', 'View' ), array( 'Models', 'Views' ), $part );
		$path .= $part . '/';
	}

	$path .= $filename . '.php';

	if ( ! file_exists( $path ) ) {
		return false;
	}

	require_once $path;
}

/**
 *
 * transform any url into a protocol-independent url
 *
 * @param string $raw_url
 *
 * @return string
 */
function tve_dash_url_no_protocol( $raw_url ) {
	return preg_replace( '#http(s)?://#', '//', $raw_url );
}

/**
 * check whether or not the user has a caching plugin installed and try to detect the actual plugin being used
 *
 * @return bool|string false if there is no known caching plugin installed, or string the name of installed caching plugin
 */
function tve_dash_detect_cache_plugin() {
	$known_plugins = array(
		'wp-super-cache/wp-cache.php',
		'w3-total-cache/w3-total-cache.php',
		'wp-rocket/wp-rocket.php',
		'wp-fastest-cache/wpFastestCache.php'
	);
	$known_plugins = apply_filters( 'tve_dash_cache_known_plugins', $known_plugins );

	if ( ! is_array( $known_plugins ) || empty( $known_plugins ) ) {
		return false;
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	foreach ( $known_plugins as $plugin_file ) {
		if ( is_plugin_active( $plugin_file ) ) {
			return dirname( $plugin_file );
		}
	}

	return false;
}


/**
 * try to automatically prune (clear) the cache if the user has a known caching plugin installed
 *
 * @param string $cache_plugin
 *
 * @return bool true on success, false on failure
 */
function tve_dash_cache_plugin_clear( $cache_plugin ) {
	$known_callbacks = array(
		'wp-super-cache'   => 'wp_cache_clear_cache',
		'w3-total-cache'   => 'w3tc_pgcache_flush',
		'wp-rocket'        => 'rocket_clean_domain',
		'wp-fastest-cache' => 'deleteCssAndJsCache'
	);

	if ( ! isset( $known_callbacks[ $cache_plugin ] ) ) {
		$known_callbacks[ $cache_plugin ] = apply_filters( 'tve_dash_cache_clear_callback', '', $cache_plugin );
	}
	if ( ! isset( $known_callbacks[ $cache_plugin ] ) || ! function_exists( $known_callbacks[ $cache_plugin ] ) ) {
		return false;
	}
	call_user_func( $known_callbacks[ $cache_plugin ] );

	return true;
}

function tve_dash_get_error_log_entries( $order_by = 'date', $order = 'DESC', $per_page = 10, $current_page = 1 ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'tcb_api_error_log';
	$sql        = "SELECT * FROM {$table_name}";

	$sql .= " ORDER BY {$order_by} {$order}";

	//get total items
	$data['settings']['items'] = $wpdb->query( $sql );

	$data['settings']['pages'] = ceil( $data['settings']['items'] / $per_page );

	//calculate the offset from where to begin the query
	$offset = ( $current_page - 1 ) * $per_page;
	$sql .= " LIMIT {$offset}," . $per_page;
	$models = $wpdb->get_results( $sql );

	$availableAPIs = Thrive_Dash_List_Manager::getAvailableAPIs( false, array(), true );
	foreach ( $models as $key => $entry ) {
		$unserialized_data                   = unserialize( $entry->api_data );
		$models[ $key ]->fields_html         = tve_dash_build_column_api_data( $unserialized_data );
		$models[ $key ]->api_data            = json_encode( $unserialized_data );
		$models[ $key ]->connection_explicit = $availableAPIs[ $entry->connection ];
	}

	$data['models'] = $models;

	return $data;
}

function tve_dash_build_column_api_data( $data ) {
	$info = "";

	if ( ! empty( $data['email'] ) ) {
		$info .= "<strong>" . __( "Email", TVE_DASH_TRANSLATE_DOMAIN ) . "</strong>: {$data['email']}<br/>";
	}

	if ( ! empty( $data['name'] ) ) {
		$info .= " <strong>" . __( "Name", TVE_DASH_TRANSLATE_DOMAIN ) . "</strong>: {$data['name']}<br/>";
	}

	if ( ! empty( $data['phone'] ) ) {
		$info .= " <strong>" . __( "Phone", TVE_DASH_TRANSLATE_DOMAIN ) . "</strong>: {$data['phone']}";
	}

	return sprintf( '%1$s',
		trim( $info )
	);
}

/**
 * Generate secret and set cookie
 *
 * @return array
 */
function tve_dash_generate_secret() {
	$rand = md5( rand() );
	setcookie( TVE_SECRET, $rand, strtotime( '+1 day' ), '/' );

	return array(
		'secret' => $rand
	);
}

/**
 * Verify secret
 *
 * @param string $secret
 *
 * @return bool
 */
function tve_dash_check_secret( $secret ) {
	if ( empty( $secret ) || empty( $_COOKIE[ TVE_SECRET ] ) || $secret != $_COOKIE[ TVE_SECRET ] ) {
		return false;
	}

	return true;
}