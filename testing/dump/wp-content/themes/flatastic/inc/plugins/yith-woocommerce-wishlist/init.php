<?php
/**
* Plugin Name: YITH WooCommerce Wishlist
* Plugin URI: http://yithemes.com/
* Description: YITH WooCommerce Wishlist allows you to add Wishlist functionality to your e-commerce.
* Version: 1.1.6
* Author: Your Inspiration Themes
* Author URI: http://yithemes.com/
* Text Domain: yit
* Domain Path: /languages/
*
* @author Your Inspiration Themes
* @package YITH WooCommerce Wishlist
* @version 1.1.5
*/
/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Required functions
 */
if( !defined('YITH_FUNCTIONS') ) {
	require_once( 'yit-common/yit-functions.php' );
}

if ( !function_exists('yith_wishlist_constructor')) {
	function yith_wishlist_constructor() {
		global $woocommerce;
		if ( ! isset( $woocommerce ) ) return;

		define( 'YITH_WCWL', true );

		define( 'YITH_WCWL_URL', MAD_BASE_URI . 'inc/plugins/yith-woocommerce-wishlist/' );
		define( 'YITH_WCWL_DIR', trailingslashit(dirname(__FILE__)) );

		// Load required classes and functions
		require_once( 'functions.yith-wcwl.php' );
		require_once( 'class.yith-wcwl.php' );
		require_once( 'class.yith-wcwl-init.php' );
		require_once( 'class.yith-wcwl-install.php' );

		if ( get_option( 'yith_wcwl_enabled' ) == 'yes' ) {
			require_once( 'class.yith-wcwl-ui.php' );
			require_once( 'class.yith-wcwl-shortcode.php' );
		}

		// Let's start the game!
		global $yith_wcwl;
		$yith_wcwl = new YITH_WCWL( $_REQUEST );

	}

	yith_wishlist_constructor();
	add_action('product-actions-before', array( 'YITH_WCWL_Shortcode', 'add_to_wishlist') );
}