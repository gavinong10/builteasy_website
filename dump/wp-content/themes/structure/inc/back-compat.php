<?php
/**
 * ThemeMove back compat functionality
 *
 * Prevents ThemeMove from running on WordPress versions prior to 3.9,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 3.9.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since ThemeMove 1.0
 */

/**
 * Prevent switching to ThemeMove on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since ThemeMove 1.0
 */
function thememove_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'thememove_upgrade_notice' );
}

add_action( 'after_switch_theme', 'thememove_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * ThemeMove on WordPress versions prior to 3.9.
 *
 * @since Twenty Fifteen 1.0
 */
function thememove_upgrade_notice() {
	$message = sprintf( __( 'ThemeMove requires at least WordPress version 3.9. You are running version %s. Please upgrade and try again.', 'thememove' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 3.9.
 *
 * @since ThemeMove 1.0
 */
function thememove_customize() {
	wp_die( sprintf( __( 'ThemeMove requires at least WordPress version 3.9. You are running version %s. Please upgrade and try again.', 'thememove' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}

add_action( 'load-customize.php', 'thememove_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 3.9.
 *
 * @since ThemeMove 1.0
 */
function thememove_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'ThemeMove requires at least WordPress version 3.9. You are running version %s. Please upgrade and try again.', 'thememove' ), $GLOBALS['wp_version'] ) );
	}
}

add_action( 'template_redirect', 'thememove_preview' );
