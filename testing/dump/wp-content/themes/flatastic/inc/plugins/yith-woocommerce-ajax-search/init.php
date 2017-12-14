<?php
/**
* Plugin Name: YITH WooCommerce Ajax Search
* Plugin URI: http://yithemes.com/
* Description: YITH WooCommerce Ajax Search allows your users to search products in real time.
* Version: 1.1.3
* Author: Your Inspiration Themes
* Author URI: http://yithemes.com/
* Text Domain: yit
* Domain Path: /languages/
* 
* @author Your Inspiration Themes
* @package YITH WooCommerce Ajax Search
* @version 1.1.1
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

function yith_ajax_search_constructor() {
    global $woocommerce;
    if ( ! isset( $woocommerce ) ) return;

    /**
     * Required functions
     */
    if( !defined('YITH_FUNCTIONS') ) {
        require_once( 'yit-common/yit-functions.php' );
    }

    load_plugin_textdomain( 'yit', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

    define( 'YITH_WCAS', true );
    define( 'YITH_WCAS_URL',  MAD_BASE_URI . 'inc/plugins/yith-woocommerce-ajax-search/');
    define( 'YITH_WCAS_DIR', trailingslashit(dirname(__FILE__)) );

    // Load required classes and functions
    require_once('functions.yith-wcas.php');
    require_once('class.yith-wcas-admin.php');
    require_once('class.yith-wcas-frontend.php');
    require_once('widgets/class.yith-wcas-ajax-search.php');
    require_once('class.yith-wcas.php');

    // Let's start the game!
    global $yith_wcas;
    $yith_wcas = new YITH_WCAS();
}
yith_ajax_search_constructor();