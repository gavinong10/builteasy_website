<?php
/**
 * Theme functions for NOO Framework.
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

if( !function_exists('is_plugin_active') )
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Set global constance
define( 'NOO_FRAMEWORK', get_template_directory() . '/framework' );
define( 'NOO_FRAMEWORK_ADMIN', NOO_FRAMEWORK . '/admin' );
define( 'NOO_FRAMEWORK_FUNCTION', NOO_FRAMEWORK . '/functions' );

define( 'NOO_FRAMEWORK_URI', get_template_directory_uri() . '/framework' );
define( 'NOO_FRAMEWORK_ADMIN_URI', NOO_FRAMEWORK_URI . '/admin' );

if ( !defined( 'NOO_ASSETS' ) ) {
	define( 'NOO_ASSETS', get_template_directory() . '/assets' );
}

if ( !defined( 'NOO_ASSETS_URI' ) ) {
	define( 'NOO_ASSETS_URI', get_template_directory_uri() . '/assets' );
}

if( !defined('NOO_TEXT_DOMAIN') ) {
	define( 'NOO_TEXT_DOMAIN', 'noo' );
}

define( 'NOO_WOOCOMMERCE_EXIST', is_plugin_active( 'woocommerce/woocommerce.php' ) );

if ( !defined( 'NOO_SUPPORT_PORTFOLIO' ) ) {
	define( 'NOO_SUPPORT_PORTFOLIO', false );
}

// Functions for specific theme
$theme_name = basename(dirname(__FILE__));
if ( file_exists( get_template_directory() . '/functions_' . $theme_name . '.php' ) ) {
	require_once get_template_directory() . '/functions_' . $theme_name . '.php';
}

//
// Helper functions.
//
require_once NOO_FRAMEWORK_FUNCTION . '/noo-theme.php';
require_once NOO_FRAMEWORK_FUNCTION . '/noo-utilities.php';
require_once NOO_FRAMEWORK_FUNCTION . '/noo-html.php';
require_once NOO_FRAMEWORK_FUNCTION . '/noo-style.php';
require_once NOO_FRAMEWORK_FUNCTION . '/noo-wp-style.php';
require_once NOO_FRAMEWORK_FUNCTION . '/noo-css.php';

require_once NOO_FRAMEWORK_FUNCTION . '/noo-user.php';

require_once NOO_FRAMEWORK_FUNCTION . '/noo-ajax-upload.php';

//
// Enqueue assets
//
require_once NOO_FRAMEWORK_FUNCTION . '/noo-enqueue-css.php';
require_once NOO_FRAMEWORK_FUNCTION . '/noo-enqueue-js.php';

//
// Admin panel
//

// Initialize theme
require_once NOO_FRAMEWORK_ADMIN . '/_init.php';

// Initialize NOO Customizer
require_once NOO_FRAMEWORK_ADMIN . '/customizer/_init.php';

// WooCommerce
if( NOO_WOOCOMMERCE_EXIST ) {
	require_once NOO_FRAMEWORK_FUNCTION . '/woocommerce.php';
}

// Initialize CitiLights function
require_once NOO_FRAMEWORK_ADMIN . '/noo-agent.php';
require_once NOO_FRAMEWORK_ADMIN . '/noo-property.php';
require_once NOO_FRAMEWORK_FUNCTION . '/class-paypal-framework.php';
require_once NOO_FRAMEWORK_ADMIN . '/noo-payment.php';

// Initialize NOO Shortcodes
require_once NOO_FRAMEWORK_ADMIN . '/shortcodes/_init.php';

// Meta Boxes
require_once NOO_FRAMEWORK_ADMIN . '/meta-boxes/_init.php';

// Taxonomy Meta Fields
// require_once NOO_FRAMEWORK_ADMIN . '/taxonomy-meta.php';

// Mega Menu
require_once NOO_FRAMEWORK_ADMIN . '/mega-menu.php';

// SMK Sidebar Generator
if ( !defined( 'SMK_SBG_PATH' ) ) define( 'SMK_SBG_PATH', NOO_FRAMEWORK_ADMIN . '/smk-sidebar-generator/' );
if ( !defined( 'SMK_SBG_URI' ) ) define( 'SMK_SBG_URI', NOO_FRAMEWORK_ADMIN_URI . '/smk-sidebar-generator/' );
require_once SMK_SBG_PATH . 'smk-sidebar-generator.php';

// Visual Composer
require_once NOO_FRAMEWORK_ADMIN . '/visual-composer.php';

//
// Widgets
//
$widget_path = get_template_directory() . '/widgets';

if ( file_exists( $widget_path . '/widgets_init.php' ) ) {
	require_once $widget_path . '/widgets_init.php';
}

// CitiLights Widgets
require_once $widget_path . '/citilights_widgets.php';

//
// Plugins
// First we'll check if there's any plugins inluded
//
$plugin_path = get_template_directory() . '/plugins';
if ( file_exists( $plugin_path . '/tgmpa_register.php' ) ) {
	require_once NOO_FRAMEWORK_ADMIN . '/class-tgm-plugin-activation.php';
	require_once $plugin_path . '/tgmpa_register.php';
}
?>
