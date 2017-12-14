<?php

/**
 * Gets home URL
 */
function aviators_get_home_url() {
	global $sitepress;

	$home = get_bloginfo( 'wpurl' );

	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		if ( $sitepress->get_default_language() == ICL_LANGUAGE_CODE ) {
			return $home;
		}
		return $home . '/' . ICL_LANGUAGE_CODE;
	}

	return $home;
}

/**
 * Loads all plugins from plugins.json
 */
function aviators_core_load_plugins() {
	$content = file_get_contents( get_template_directory() . '/settings/plugins.json' );
	$options = json_decode( $content );

	foreach ( $options->plugins as $plugin ) {
		aviators_core_require_plugin( $plugin );
	}
}

/**
 * Get list of all available plugins
 */
function aviators_core_plugins_list() {
	$content = file_get_contents( get_template_directory() . '/settings/plugins.json' );
	$options = json_decode( $content );
	$plugins = array();

	foreach ( $options->plugins as $plugin ) {
		$plugins[$plugin] = array(
			'path' => AVIATORS_DIR . '/plugins/' . $plugin,
		);
	}

	return $plugins;
}

/**
 * Get list of all required core plugins
 */
function aviators_core_required_plugins_list() {
	$plugins = array();
	$dirs    = glob( AVIATORS_DIR . '/core/plugins/*', GLOB_ONLYDIR );

	foreach ( $dirs as $dir ) {
		$parts          = explode( '/', $dir );
		$name           = $parts[count( $parts ) - 1];
		$plugins[$name] = array(
			'path' => AVIATORS_DIR . '/core/plugins/' . $name,
		);
	}

	return $plugins;
}

function aviators_core_get_core_plugins_list() {
	return glob( AVIATORS_DIR . '/core/plugins/*', GLOB_ONLYDIR );
}

function aviator_core_get_all_plugins_list() {
	$optional_plugins = aviators_core_plugins_list();
	$required_plugins = aviators_core_required_plugins_list();
	return array_merge( $optional_plugins, $required_plugins );
}

/**
 * Better plugin loader
 */
function aviators_core_require_plugin( $plugin_name ) {
	require_once AVIATORS_DIR . '/plugins/' . $plugin_name . '/' . $plugin_name . '.php';
}


function aviators_core_get_post_teaser( $id ) {
	$post = get_post( $id );

	if ( preg_match( '/<!--more(.*?)?-->/', $post->post_content, $matches ) ) {
		$parts = explode( $matches[0], $post->post_content );
		return $parts[0];
	} else {
		if (!empty($post->post_content)) {
			return substr(strip_tags($post->post_content), 0, 200) . ' ...';
		}
	}

	return FALSE;
}

/**
 * Print full list of queries
 */
function aviators_core_debug_queries() {
	global $wpdb;

	echo "<pre>";
	print_r( $wpdb->queries );
	echo "</pre>";
	die;
}