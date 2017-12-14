<?php
/**
 * Plugin Name: YITH Woocommerce Compare
 * Plugin URI: http://yithemes.com/
 * Description: YITH Woocommerce Compare allows you to compare more products with woocommerce plugin, through product attributes.
 * Version: 1.2.2
 * Author: Your Inspiration Themes
 * Author URI: http://yithemes.com/
 * Text Domain: yit
 * Domain Path: /languages/
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
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

if (defined('YITH_WOOCOMPARE')) {
	return;
}

/* Include common functions */
if( !defined('YITH_FUNCTIONS') ) {
    require_once( 'yit-common/yit-functions.php' );
}

if ( !function_exists('yith_woocompare_constructor_clone') ) {
	function yith_woocompare_constructor_clone() {
		global $woocommerce;
		if ( ! isset( $woocommerce ) ) return;

		define( 'YITH_WOOCOMPARE', true );
		define( 'YITH_WOOCOMPARE_VERSION', '1.2.2' );
		define( 'YITH_WOOCOMPARE_URL', MAD_BASE_URI . 'inc/plugins/yith-woocommerce-compare/' );
		define( 'YITH_WOOCOMPARE_DIR', trailingslashit(dirname(__FILE__)) );

//	load_plugin_textdomain( 'yit', false, YITH_WOOCOMPARE_DIR . 'languages/' );

		// Load required classes and functions
		require_once('class.yith-woocompare-helper.php');
		require_once('functions.yith-woocompare.php');
		require_once('class.yith-woocompare-admin.php');
		require_once('class.yith-woocompare-frontend.php');
		require_once('widgets/class.yith-woocompare-widget.php');
		require_once('class.yith-woocompare.php');

		// Let's start the game!
		global $yith_woocompare;
		$yith_woocompare = new YITH_Woocompare();
	}
	yith_woocompare_constructor_clone();
}