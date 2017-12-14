<?php
/**
 * ThemeMove Theme Customizer
 *
 * @package ThemeMove
 */

/**
 * ============================================================================
 * Load customizer configs
 * ============================================================================
 */
function customizer_config() {
	$args = array(
		'color_active'  => '#1abc9c',
		'color_light'   => '#8cddcd',
		'color_select'  => '#34495e',
		'color_accent'  => '#FF5740',
		'url_path'      => THEME_ROOT . '/core/customizer/',
		'stylesheet_id' => 'thememove-main',
	);
	return $args;
}

add_filter( 'kirki/config', 'customizer_config' );

/**
 * ============================================================================
 * Setup Customizer Backend Link
 * ============================================================================
 */
if ( $wp_version >= '3.6' ) {
	function thememove_remove_customize_submenu_page() {
		remove_submenu_page( 'themes.php', 'customize.php' );
	}

	add_action( 'admin_menu', 'thememove_remove_customize_submenu_page' );
}

function thememove_add_customizer_menu() {
	add_menu_page( 'Customizer', 'Customizer', 'edit_theme_options', 'customize.php', null, null, 61 );
}

add_action( 'admin_menu', 'thememove_add_customizer_menu' );

/**
 * ============================================================================
 * Remove Unused Native Sections
 * ============================================================================
 */
function thememove_remove_customizer_sections( $wp_customize ) {
	$wp_customize->remove_section( 'nav' );
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'title_tagline' );
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'header_image' );
	$wp_customize->remove_section( 'static_front_page' );

	$wp_customize->remove_control( 'blogname' );
	$wp_customize->remove_control( 'blogdescription' );
	$wp_customize->remove_control( 'page_for_posts' );
}

add_action( 'customize_register', 'thememove_remove_customizer_sections' );

/**
 * ============================================================================
 * Setup Customizer Links
 * ============================================================================
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function thememove_customize_register( $wp_customize ) {
	$wp_customize->get_control( 'show_on_front' )->section  = 'site_settings_section';
	$wp_customize->get_control( 'show_on_front' )->priority = '2';
	$wp_customize->get_control( 'page_on_front' )->section  = 'site_settings_section';
	$wp_customize->get_control( 'page_on_front' )->priority = '3';
	$wp_customize->get_control( 'page_on_front' )->label    = 'Choose a page';
	$wp_customize->get_control( 'show_on_front' )->label    = '';
	$wp_customize->get_section( 'io_section' )->priority = '40';
}

add_action( 'customize_register', 'thememove_customize_register' );

/**
 * ============================================================================
 * Create sections: Site Background
 * ============================================================================
 */
function register_sections_site_background( $wp_customize ) {
	$wp_customize->add_section( 'background_image', array(
		'title'       => __( 'Background', 'thememove' ),
		'description' => __( 'In this section you can change background image of the site', 'thememove' ),
		'priority'    => 30,
	) );
}

add_action( 'customize_register', 'register_sections_site_background' );
/**
 * ============================================================================
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 * ============================================================================
 */
function thememove_customize_preview_js() {
	wp_enqueue_script( 'thememove_customizer', THEME_ROOT . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}

add_action( 'customize_preview_init', 'thememove_customize_preview_js' );

/**
 * ============================================================================
 * Include panels/sections
 * ============================================================================
 */
require_once get_template_directory() . '/inc/customizer/section__options.php';
require_once get_template_directory() . '/inc/customizer/section__typography.php';
require_once get_template_directory() . '/inc/customizer/section__colors.php';
require_once get_template_directory() . '/inc/customizer/section__menu.php';
require_once get_template_directory() . '/inc/customizer/section__header.php';
require_once get_template_directory() . '/inc/customizer/section__footer.php';
require_once get_template_directory() . '/inc/customizer/section__post.php';
require_once get_template_directory() . '/inc/customizer/section__custom-code.php';

/**
 * ============================================================================
 * Color Scheme Settings
 * ============================================================================
 */
if ( ! function_exists( 'thememove_get_color_scheme' ) ) :
	function thememove_get_color_scheme() {
		$color_scheme_option = get_theme_mod( 'site_color_scheme', 'scheme1' );
		$color_schemes       = thememove_get_color_schemes();
		global $thememove_color_scheme;

		if ( $thememove_color_scheme == 'default' ) {
			return $color_schemes[ $color_scheme_option ]['colors'];
		} elseif ( $thememove_color_scheme != 'default' && $thememove_color_scheme != '' ) {
			return $color_schemes[ $thememove_color_scheme ]['colors'];
		} elseif ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
			return $color_schemes[ $color_scheme_option ]['colors'];
		}

		return $color_schemes['scheme1']['colors'];
	}
endif; // thememove_get_color_scheme

if ( ! function_exists( 'thememove_get_color_scheme_choices' ) ) :
	function thememove_get_color_scheme_choices() {
		$color_schemes                = thememove_get_color_schemes();
		$color_scheme_control_options = array();

		foreach ( $color_schemes as $color_scheme => $value ) {
			$color_scheme_control_options[ $color_scheme ] = $value['label'];
		}

		return $color_scheme_control_options;
	}
endif;