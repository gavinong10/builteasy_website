<?php
/*
Plugin Name: Facebook Conversion Pixel
Plugin URI: https://github.com/kellenmace/facebook-conversion-pixel
Description: Facebook's recommended plugin for adding Facebook Conversion Pixel code to WordPress sites.
Version: 1.3.5
Author: Kellen Mace
Author URI: http://kellenmace.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Insert Facebook Conversion Pixel
 * @since 1.0
 */
function fb_pxl_head() {
	$post_type = get_post_type();
	$fb_pxl_options = get_option( 'fb_pxl_options' );

	// If user has enabled this post type
	if ( isset( $fb_pxl_options[ $post_type ] ) && 'on' === $fb_pxl_options[ $post_type ] ) {
		$fb_pxl_switch = get_post_meta( get_the_id(), 'fb_pxl_checkbox', true );

		// If user has chosen to insert code, insert it
		if ( 'on' === $fb_pxl_switch ) {
			$nonce = wp_create_nonce( 'fb-pxl-nonce' );
			fb_pxl_insert_facebook_conversion_pixel( $nonce );
		}
	}
}
add_action( 'wp_head', 'fb_pxl_head' );

/**
 * Insert Facebook Conversion Pixel
 * @since 1.3.2.
 */
function fb_pxl_insert_facebook_conversion_pixel( $nonce ) {

	// If this function has been called from somewhere other than fb_pxl_head(), bail.
	if ( ! wp_verify_nonce( $nonce, 'fb-pxl-nonce' ) ) {
		exit;
	}
	
	// Output code
	$fb_pxl_code = get_post_meta( get_the_id(), 'fb_pxl_conversion_code', true);
	if ( ! empty( $fb_pxl_code ) ) {
		echo htmlspecialchars_decode( $fb_pxl_code );
	}
}

/**
 * Include plugin admin dependencies
 * @since 1.0
 */
function fb_pxl_admin_includes() {

	if ( ! is_admin() ) {
		return;
	}

	include_once( plugin_dir_path( __FILE__ ) . 'includes/admin.php' );
	require_once( plugin_dir_path( __FILE__ ) . '/includes/cmb2/init.php' );
}
add_action( 'init', 'fb_pxl_admin_includes' );

/**
 * Display meta box in admin
 * @since 1.2
 */
function fb_pxl_display_meta_box() {
	$prefix = 'fb_pxl_';

	$options = get_option( 'fb_pxl_options' );
	$post_types = array();
	foreach ( $options as $post_type => $checkbox ) {
		if ( 'on' == $checkbox ) {
			array_push( $post_types, $post_type );
		}
	}

	$metabox = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => 'Facebook Conversion Pixel',
		'object_types'  => $post_types,
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true,
	) );

	$metabox->add_field( array(
		'name' => __( 'Insert Code', 'facebook-conversion-pixel' ),
		'desc' => __( 'Insert Facebook Conversion Pixel code on this page', 'facebook-conversion-pixel' ),
		'id'   => $prefix . 'checkbox',
		'type' => 'checkbox',
	) );

	$metabox->add_field( array(
		'name' => __( 'Conversion Pixel', 'facebook-conversion-pixel' ),
		'desc' => __( 'Paste your Facebook Conversion Pixel code here', 'facebook-conversion-pixel' ),
		'id'   => $prefix . 'conversion_code',
		'type' => 'textarea_code',
	) );
}
add_filter( 'cmb2_init', 'fb_pxl_display_meta_box' );

/**
 * Display settings link on WP plugin page
 * @since 1.0
 */
function fb_pxl_plugin_action_links( $links, $file ) {
	$plugin_file = 'facebook-conversion-pixel/facebook-conversion-pixel.php';
	if ( $file == $plugin_file ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=fb_pxl_options' ) . '">' . __( 'Settings', 'facebook-conversion-pixel' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'fb_pxl_plugin_action_links', 10, 4 );

/**
 * Set default options on activation
 * @since 1.1
 */
function fb_pxl_activate() {
	$options = array(
		'post' => 'on',
		'page' => 'on'
	);
	update_option( 'fb_pxl_options', $options );
}
register_activation_hook( __FILE__, 'fb_pxl_activate' );