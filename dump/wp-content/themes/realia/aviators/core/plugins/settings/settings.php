<?php
/*
Plugin Name: Config
Description: Configuration plugin for template variables
Version: 1.0
*/

/**
 * Configuration filename in theme
 */
define( 'AVIATORS_SETTINGS_FILENAME', 'settings/settings.json' );


/**
 * Callback for rendering section's description
 */
define( 'AVIATORS_SETTINGS_SECTION_DESCRIPTION_CALLBACK', 'aviators_settings_render_section_description' );

/**
 * Callback for rendering field
 */
define( 'AVIATORS_SETTINGS_FIELD_CALLBACK', 'aviators_settings_render_field' );

/**
 * User capabality
 */
define( 'AVIATORS_SETTINGS_CAPABALITY', 'administrator' );


require_once dirname( __FILE__ ) . '/renderers.php';
require_once dirname( __FILE__ ) . '/helpers.php';


/**
 * Create admin menu item
 */
function aviators_settings_admin_menu() {
	$optional_plugins = aviators_core_plugins_list();
	$required_plugins = aviators_core_required_plugins_list();

	$plugins = array_merge( $optional_plugins, $required_plugins );

	foreach ( $plugins as $key => $plugin ) {
		$filename = $plugin['path'] . '/settings.json';
		if ( is_file( $filename ) ) {
			$default_item = $key;
			break;
		}
	}

	aviators_settings_register_parent_menu( $default_item );

	foreach ( $plugins as $plugin ) {
		$filename = $plugin['path'] . '/settings.json';
		if ( is_file( $filename ) ) {
			$settings = aviators_settings_get_config( $filename );
			aviators_settings_apply_settings( $settings, $default_item );
		}
	}
}

add_action( 'admin_menu', 'aviators_settings_admin_menu' );


function aviators_settings_register_parent_menu( $default_item ) {
	$icon = get_template_directory_uri() . '/aviators/core/plugins/settings/assets/img/icon.png';
	add_menu_page( __( 'Settings', 'aviators' ), __( 'Settings', 'aviators' ), AVIATORS_SETTINGS_CAPABALITY, 'money', 'aviators_settings_render_settings_page', $icon, 31 );
//    add_submenu_page('templates', __('Settings', 'aviators'), '', AVIATORS_SETTINGS_CAPABALITY, 'money', 'aviators_settings_render_settings_page');
}

function aviators_settings_apply_settings( $settings, $default_item ) {
	add_submenu_page( 'money', $settings->title, $settings->title, AVIATORS_SETTINGS_CAPABALITY, $settings->slug, 'aviators_settings_render_settings_page' );
}

/**
 * Registers sections and fields
 */
function aviators_settings_admin_init() {
	aviators_settings_register_pages();
}

add_action( 'admin_menu', 'aviators_settings_admin_init' );