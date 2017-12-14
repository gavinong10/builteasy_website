<?php
/**
 * NOO Framework Site Package.
 *
 * Register Style
 * This file register & enqueue style used in NOO Themes.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

if ( ! function_exists( 'noo_enqueue_site_style' ) ) :
	function noo_enqueue_site_style() {

		if ( ! is_admin() ) {

			// URI variables.
			$get_stylesheet_directory_uri = get_stylesheet_directory_uri();
			$get_template_directory_uri   = get_template_directory_uri();

			// Main style
			wp_register_style( 'noo-style', $get_stylesheet_directory_uri . '/style.css', NULL, NULL, 'all' );
			$main_css = 'noo';
			if( noo_get_option( 'noo_site_skin', 'light' ) == 'dark' ) {
				$main_css .= '-dark';
			}
			wp_register_style( 'noo-fonts-style', NOO_ASSETS_URI . "/css/fonts.css", NULL, NULL, 'all' );
			wp_register_style( 'noo-main-style', NOO_ASSETS_URI . "/css/{$main_css}.css", array( 'noo-fonts-style' ), NULL, 'all' );
			wp_register_style( 'noo-idx-style', NOO_ASSETS_URI . "/css/idx.css", NULL, NULL, 'all' );
			if( is_file( noo_upload_dir() . '/custom.css' ) ) {
				wp_register_style( 'noo-custom-style', noo_upload_url() . '/custom.css', NULL, NULL, 'all' );
			}

			wp_enqueue_style( 'noo-main-style' );
			if( defined('DSIDXPRESS_PLUGIN_VERSION') ) {
				wp_enqueue_style( 'noo-idx-style' );
			}

			// woocommerce
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) :
				wp_register_style( 'noo-woocommerce', NOO_ASSETS_URI . '/css/woocommerce.css', null,null,'all');
				wp_enqueue_style( 'noo-woocommerce' );
			endif;

			if( ! noo_get_option('noo_use_inline_css', false) && wp_style_is( 'noo-custom-style', 'registered' ) ) {
				global $wp_customize;
				if ( !isset( $wp_customize ) ) {
					wp_enqueue_style( 'noo-custom-style' );
				}
			}

			wp_enqueue_style( 'noo-style' ); // place style.css here so that child theme can use custom css inside it.
			
			// Vendors
			// Font Awesome
			wp_register_style( 'vendor-font-awesome-css', NOO_FRAMEWORK_URI . '/vendor/fontawesome/css/font-awesome.min.css',array(),'4.1.0');
			wp_enqueue_style( 'vendor-font-awesome-css' );

			wp_register_style( 'vendor-nivo-lightbox-css', NOO_FRAMEWORK_URI . '/vendor/nivo-lightbox/nivo-lightbox.css', array( ), null );
			wp_register_style( 'vendor-nivo-lightbox-default-css', NOO_FRAMEWORK_URI . '/vendor/nivo-lightbox/themes/default/default.css', array( 'vendor-nivo-lightbox-css' ), null );

			wp_register_style( 'vendor-wysihtml5-css', NOO_FRAMEWORK_URI . '/vendor/bootstrap-wysihtml5/bootstrap-wysihtml5.css', array( 'noo-main-style' ), null );

			// Enqueue Fonts.
			$default_font         = noo_default_font_family();
			$default_font_weight  = noo_default_font_weight();
			$default_font_style   = 'normal';
			$default_font_subset  = 'latin';

			$protocol             = is_ssl() ? 'https' : 'http';
			$body_font_family     = '';
			$headings_font_family = '';
			$nav_font_family      = '';
			$logo_font_family     = '';

			$typo_use_custom_font = noo_get_option( 'noo_typo_use_custom_fonts', false );
			if( $typo_use_custom_font ) {
				$body_font_family            = noo_get_option( 'noo_typo_body_font', $default_font );

				if ( ! empty( $body_font_family ) && $body_font_family != $default_font ) {
					$body_font_weight        = noo_get_option( 'noo_typo_body_font_weight', $default_font_weight ) == '' ? $default_font_weight : noo_get_option( 'noo_typo_body_font_weight', $default_font_weight );
					$body_font_style         = noo_get_option( 'noo_typo_body_font_style', $default_font_style );
					$body_font_subset        = noo_get_option( 'noo_typo_body_font_subset', $default_font_subset );

					$font      = str_replace( ' ', '+', $body_font_family ) . ':' . $body_font_weight . $body_font_style;
					$subset    = ( $body_font_subset != '' && $body_font_subset != 'latin' ) ? '&subset=' . $body_font_subset : '';

					wp_enqueue_style( 'noo-google-fonts-body', "{$protocol}://fonts.googleapis.com/css?family={$font}{$subset}", false, null, 'all' );
				}

				$headings_font_family        = noo_get_option( 'noo_typo_headings_font', noo_default_headings_font_family() );

				if ( ! empty( $headings_font_family ) && $headings_font_family != $default_font ) {
					$headings_font_weight    = noo_get_option( 'noo_typo_headings_font_weight', $default_font_weight ) == '' ? $default_font_weight : noo_get_option( 'noo_typo_headings_font_weight', $default_font_weight );
					$headings_font_style     = noo_get_option( 'noo_typo_headings_font_style', $default_font_style );
					$headings_font_subset    = noo_get_option( 'noo_typo_headings_font_subset', $default_font_subset ) == '' ? 'latin' : noo_get_option( 'noo_typo_headings_font_subset', $default_font_subset );

					$font      = str_replace( ' ', '+', $headings_font_family ) . ':' . $headings_font_weight . $headings_font_style;
					$subset    = ( $headings_font_subset != '' && $headings_font_subset != 'latin' ) ? '&subset=' . $headings_font_subset : '';

					wp_enqueue_style( 'noo-google-fonts-headings', "{$protocol}://fonts.googleapis.com/css?family={$font}{$subset}", false, null, 'all' );
				}
			}
			
			$nav_custom_font          = noo_get_option( 'noo_header_custom_nav_font', false );

			if( $nav_custom_font ) {
				$nav_font_family      = noo_get_option( 'noo_header_nav_font', $default_font );

				if ( ! empty( $nav_font_family ) && $nav_font_family != $default_font ) {
					$nav_font_weight  = noo_get_option( 'noo_header_nav_font_weight', $default_font_weight ) == '' ? $default_font_weight : noo_get_option( 'noo_header_nav_font_weight', $default_font_weight );
					$nav_font_style   = noo_get_option( 'noo_header_nav_font_style', $default_font_style );
					$nav_font_subset  = noo_get_option( 'noo_header_nav_font_subset', $default_font_subset ) == '' ? 'latin' : noo_get_option( 'noo_header_nav_font_subset', $default_font_subset );

					$font      = str_replace( ' ', '+', $nav_font_family ) . ':' . $nav_font_weight . $nav_font_style;
					$subset    = ( $nav_font_subset != '' && $nav_font_subset != 'latin' ) ? '&subset=' . $nav_font_subset : '';

					wp_enqueue_style( 'noo-google-fonts-nav', "{$protocol}://fonts.googleapis.com/css?family={$font}{$subset}", false, null, 'all' );
				}
			}

			$use_image_logo           = noo_get_option( 'noo_header_use_image_logo', false );
			if( ! $use_image_logo ) {
				$logo_font_family         = noo_get_option( 'noo_header_logo_font', noo_default_logo_font_family() );
				if ( !empty( $logo_font_family ) && $logo_font_family != $default_font ) {
					$logo_font_weight     = noo_get_option( 'noo_header_logo_font_weight', '700' ) == '' ? '700' : noo_get_option( 'noo_header_logo_font_weight', '700' );
					$logo_font_style      = noo_get_option( 'noo_header_logo_font_style', $default_font_style );
					$logo_font_subset     = noo_get_option( 'noo_header_logo_font_subset', $default_font_subset ) == '' ? 'latin' : noo_get_option( 'noo_header_logo_font_subset', $default_font_subset );

					$font      = str_replace( ' ', '+', $logo_font_family ) . ':' . $logo_font_weight . $logo_font_style;
					$subset    = ( $logo_font_subset != '' && $logo_font_subset != 'latin' ) ? '&subset=' . $logo_font_subset : '';

					wp_enqueue_style( 'noo-google-fonts-logo', "{$protocol}://fonts.googleapis.com/css?family={$font}{$subset}", false, null, 'all' );
				}
			}

			// Default font
			$default_font_family = !empty( $default_font ) ? str_replace( ' ', '+', $default_font ) . ':' . '100,300,400,700,900,300italic,400italic,700italic,900italic' : '';
			wp_enqueue_style( 'noo-google-fonts-default', "{$protocol}://fonts.googleapis.com/css?family={$default_font_family}", false, null, 'all' );
			
			if(class_exists('NooAgent') && NooAgent::is_dashboard()) {
				wp_enqueue_style( 'vendor-wysihtml5-css' );
			}

			//
			// Unused style
			//
			// De-register Contact Form 7 Styles
			if ( class_exists( 'WPCF7_ContactForm' ) ) :
			    wp_deregister_style( 'contact-form-7' );
			endif;
		}
	}
add_action( 'wp_enqueue_scripts', 'noo_enqueue_site_style' );
endif;
