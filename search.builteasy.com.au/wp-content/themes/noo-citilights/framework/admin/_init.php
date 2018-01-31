<?php
/**
 * Initialize Theme functions for NOO Framework.
 * This file include the framework functions, it should remain intact between themes.
 * For theme specified functions, see file functions-<theme name>.php
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Content Width
if ( ! isset( $content_width ) ) :
	$content_width = noo_thumbnail_width();
endif;

// Initialize Theme
if (!function_exists('noo_init_theme')):
	function noo_init_theme() {
		// The "plugin_locale" filter is also used in load_plugin_textdomain()
	    $locale = apply_filters('theme_locale', get_locale(), 'noo');

	    load_textdomain('noo', WP_LANG_DIR.'/noo-citilights/'.'noo'.'-'.$locale.'.mo');

		load_theme_textdomain( 'noo', get_template_directory() . '/languages' );

		require_once( 'noo-check-version.php' );
 
        if ( is_admin() ) {     
            $license_manager = new Noo_Check_Version(
                'noo-citilights',
                'Noo Citilights',
                'http://update.nootheme.com/api/license-manager/v1'
            );
        }

		// @TODO: Automatic feed links.
		add_theme_support('automatic-feed-links');
		// Add support for some post formats.
		add_theme_support('post-formats', array(
			'image',
			'gallery',
			'video',
			'link',
			'quote',
			'audio'
		));
		add_theme_support( 'woocommerce' );

		// WordPress menus location.
		$menu_list = array();
		if (noo_get_option('noo_top_bar', 'html') == 'menu') {
			$menu_list['top-menu'] = __('Top Menu', 'noo');
		}
		
		$menu_list['primary'] = __('Primary Menu', 'noo');
		
		if (noo_get_option('noo_bottom_bar_menu', false)) {
			$menu_list['footer-menu'] = __('Footer Menu', 'noo');
		}

		// Register Menu
		register_nav_menus($menu_list);

		// Define image size
		add_theme_support('post-thumbnails');

		add_image_size('property-thumb', 370,300, true);
		add_image_size('property-image', 800,450, true);
		add_image_size('property-infobox', 190,160, true);
		add_image_size('property-slider', 1170,700, true);

		// add_image_size('agent-thumb', 171,233, true);
	}
	add_action('after_setup_theme', 'noo_init_theme');
endif;

// Enqueue style for admin
if ( ! function_exists( 'noo_enqueue_admin_assets' ) ) :
	function noo_enqueue_admin_assets() {

		wp_register_style( 'noo-admin-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-admin.css', null, null, 'all' );
		wp_enqueue_style( 'noo-admin-css' );

		wp_register_style( 'vendor-font-awesome-css', NOO_FRAMEWORK_URI . '/vendor/fontawesome/css/font-awesome.min.css',array(),'4.1.0');
		wp_register_style( 'noo-icon-bootstrap-modal-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-icon-bootstrap-modal.css', null, null, 'all' );
		wp_register_style( 'noo-jquery-ui-slider', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-jquery-ui.slider.css', null, '1.10.4', 'all' );
		wp_register_style( 'vendor-chosen-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/chosen.min.css', null, null, 'all' );

		wp_register_style( 'vendor-alertify-core-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/alertify.core.css', null, null, 'all' );
		wp_register_style( 'vendor-alertify-default-css', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/alertify.default.css', array('vendor-alertify-core-css'), null, 'all' );
		
		// Main script
		wp_register_script( 'noo-admin-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/noo-admin.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'noo-admin-js' );

		wp_register_script( 'noo-bootstrap-modal-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/bootstrap-modal.js', array('jquery'), '2.3.2', true );
		wp_register_script( 'noo-bootstrap-tab-js',NOO_FRAMEWORK_ADMIN_URI . '/assets/js/bootstrap-tab.js',array('jquery'), '2.3.2', true);
		wp_register_script( 'noo-font-awesome-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/font-awesome.js', array( 'noo-bootstrap-modal-js', 'noo-bootstrap-tab-js'), null, true );
		wp_register_script( 'vendor-chosen-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/chosen.jquery.min.js', array( 'jquery'), null, true );
		wp_register_script( 'vendor-fileDownload-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/jquery.fileDownload.js', array( 'jquery' ), null, true );
		wp_register_script( 'vendor-alertify-js', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/alertify.mod.min.js', null, null, true );

	}
	add_action( 'admin_enqueue_scripts', 'noo_enqueue_admin_assets' );
endif;