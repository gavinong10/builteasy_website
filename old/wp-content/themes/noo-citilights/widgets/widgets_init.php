<?php
/**
 * This file initialize widgets area used in this theme.
 *
 *
 * @package    NOO Framework
 * @subpackage Widget Initiation
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if ( ! function_exists( 'noo_widgets_init' ) ) :

	function noo_widgets_init() {
		
		// Default Sidebar (WP main sidebar)
		register_sidebar( 
			array(  // 1
				'name' => __( 'Main Sidebar', 'noo' ), 
				'id' => 'sidebar-main', 
				'description' => __( 'Default Blog Sidebar.', 'noo' ), 
				'before_widget' => '<div id="%1$s" class="widget %2$s">', 
				'after_widget' => '</div>', 
				'before_title' => '<h4 class="widget-title">', 
				'after_title' => '</h4>' ) );

		if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			// Shop Sidebar (WP main sidebar)
			register_sidebar( 
				array(  // 1
					'name' => __( 'Shop Sidebar', 'noo' ), 
					'id' => 'sidebar-shop', 
					'description' => __( 'Default Shop Sidebar.', 'noo' ), 
					'before_widget' => '<div id="%1$s" class="widget %2$s">', 
					'after_widget' => '</div>', 
					'before_title' => '<h4 class="widget-title">', 
					'after_title' => '</h4>' ) );
		}
		// // Top Bar Columns (Widgetized)
		// if ( noo_get_option( 'noo_top_bar' ) == 'widgets' ) {
		// 	$num = ( noo_get_option( 'noo_top_bar_widgets' ) == '' ) ? 2 : noo_get_option( 'noo_top_bar_widgets' );
		// 	for ( $i = 1; $i <= $num; $i++ ) :
		// 		register_sidebar( 
		// 			array( 
		// 				'name' => __( 'NOO - Top Bar #', 'noo' ) . $i, 
		// 				'id' => 'noo-top-' . $i, 
		// 				'before_widget' => '<div id="%1$s" class="widget %2$s">', 
		// 				'after_widget' => '</div>', 
		// 				'before_title' => '<h4 class="widget-title">', 
		// 				'after_title' => '</h4>' ) );
		// 	endfor
		// 	;
		// }
		
		// Footer Columns (Widgetized)
		$num = ( noo_get_option( 'noo_footer_widgets' ) == '' ) ? 4 : noo_get_option( 'noo_footer_widgets' );
		for ( $i = 1; $i <= $num; $i++ ) :
			register_sidebar( 
				array( 
					'name' => __( 'NOO - Footer Column #', 'noo' ) . $i, 
					'id' => 'noo-footer-' . $i, 
					'before_widget' => '<div id="%1$s" class="widget %2$s">', 
					'after_widget' => '</div>', 
					'before_title' => '<h4 class="widget-title">', 
					'after_title' => '</h4>' ) );
		endfor
		;
	}
	add_action( 'widgets_init', 'noo_widgets_init' );

endif;
