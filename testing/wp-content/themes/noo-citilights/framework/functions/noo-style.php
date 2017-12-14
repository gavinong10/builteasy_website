<?php
/**
 * Style Functions for NOO Framework.
 * This file contains functions for calculating style (normally it's css class) base on settings from admin side.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('noo_body_class')):
	function noo_body_class($output) {
		global $wp_customize;
		if (isset($wp_customize)) {
			$output[] = 'is-customize-preview';
		}

		$page_layout = get_page_layout();
		if ($page_layout == 'fullwidth') {
			$output[] = ' page-fullwidth';
		} elseif ($page_layout == 'left_sidebar') {
			$output[] = ' page-left-sidebar';
		} else {
			$output[] = ' page-right-sidebar';
		}
		
		switch (noo_get_option('noo_site_layout', 'fullwidth')) {
			case 'boxed':
				// if(get_page_template_slug() != 'page-full-width.php')
				$output[] = 'boxed-layout';
			break;
			default:
				$output[] = 'full-width-layout';
			break;
		}

		// SmoothScroll
		if( noo_get_option( 'noo_smooth_scrolling', false ) ) {
			$output[] = 'enable-nice-scroll';
		}

		// Fixed left and/or right Navigation
		$navbar_position = noo_get_option('noo_header_nav_position', 'fixed_top');
		if ($navbar_position == 'fixed_left') {
			$output[] = 'navbar-fixed-left-layout';
		} elseif ($navbar_position == 'fixed_right') {
			$output[] = 'navbar-fixed-right-layout';
		}

		if( is_one_page_enabled() ) {
			$output[] = 'one-page-layout';
		}

		if( is_singular('portfolio_project') || is_singular( 'product' ) || is_single() || is_page() ) {
			$meta_body_class = noo_get_post_meta(get_the_ID(), '_noo_body_css', '');
			if( !empty( $meta_body_class ) )
				$output[] = $meta_body_class;
		}
		
		return $output;
	}
endif;
add_filter('body_class', 'noo_body_class');

if (!function_exists('noo_header_class')):
	function noo_header_class() {
		$class = '';

		$navbar_position = noo_get_option('noo_header_nav_position', 'fixed_top');

		echo $class;
	}
endif;

if (!function_exists('noo_navbar_class')):
	function noo_navbar_class() {
		$class = '';

		$navbar_position = noo_get_option('noo_header_nav_position', 'fixed_top');
		if ($navbar_position == 'static_top') {
			$class .= ' navbar-static-top';
		} elseif ($navbar_position == 'fixed_left' || $navbar_position == 'fixed_right') {
			// noo_header_side_nav_alignment
			if ($navbar_position == 'fixed_left') {
				$class .= ' navbar-fixed-left';
			} else {
				$class .= ' navbar-fixed-right';
			}

			$alignment = noo_get_option( 'noo_header_side_nav_alignment', 'center' );
			$class .= ( $alignment != '' ) ? ' align-' . $alignment : '';
		} elseif ($navbar_position == 'fixed_right') {
			$class = ' navbar-fixed-right';
		} else {
			$class = ' fixed-top';
			$shrinkable = noo_get_option( 'noo_header_nav_shrinkable', true );
			if( $shrinkable ) {
				$class .= ' shrinkable';
			}

			$smart_scroll = noo_get_option( 'noo_header_nav_smart_scroll', false );
			if( $smart_scroll ) {
				$class .= ' smart_scroll';
			}
		}

		$nav_phone_number = noo_get_option( 'noo_header_nav_phone_number', '' );
		if( ($navbar_position == 'fixed_top' || $navbar_position == 'static_top' ) && !empty( $nav_phone_number ) ) {
			$class .= ' has-mobile';
		}

		echo $class;
	}
endif;

if (!function_exists('noo_main_class')):
	function noo_main_class() {
		$class = 'noo-main';
		$page_layout = get_page_layout();
		if ($page_layout == 'fullwidth') {
			$class.= ' col-md-12';
		} elseif ($page_layout == 'left_sidebar') {
			$class.= ' col-md-8 left-sidebar';
		} else {
			$class.= ' col-md-8 right-sidebar';
		}
		
		echo $class;
	}
endif;

if(!function_exists('noo_container_class')){
	function noo_container_class(){
		
		if(is_fullwidth() && ( noo_get_option( 'noo_portfolio_grid_style', 'standard' ) == 'masonry') ) {
			echo  'container-fullwidth';
			return;
		}
		echo 'container-boxed max offset';
	}
}

if (!function_exists('noo_sidebar_class')):
	function noo_sidebar_class() {
		$class = ' noo-sidebar col-md-4';
		$page_layout = get_page_layout();
		
		if ( $page_layout == 'left_sidebar' ) {
			$class .= ' noo-sidebar-left pull-left';
		}
		
		echo $class;
	}
endif;

if (!function_exists('noo_blog_class')):
	function noo_blog_class() {
		$class = ' post-area';
		$blog_style = noo_get_option('noo_blog_style', 'standard');
		
		if ($blog_style == 'masonry') {
			$class.= ' masonry-blog';
		} else {
			$class.= ' standard-blog';
		}
		
		echo $class;
	}
endif;

if (!function_exists('noo_page_class')):
	function noo_page_class() {
		$class = ' noo-page';
		
		echo $class;
	}
endif;

if (!function_exists('noo_portfolio_class')):
	function noo_portfolio_class() {
		$class = ' post-area';
		// $portfolio_style = noo_get_option('noo_portfolio_style', 'masonry');
		
		// if ($portfolio_style == 'standard') {
		// 	$class .= ' standard-portfolio';
		// } else {
			$class .= ' masonry-portfolio';
			if(noo_get_option('noo_portfolio_items_title', false) === true) {
				$class .= ' no-title';
			}

			if(noo_get_option( 'noo_portfolio_grid_style', 'standard' ) == 'masonry') {
				$class .= ' no-gap';
			}
		// }
		
		echo $class;
	}
endif;

if (!function_exists('noo_post_class')):
	function noo_post_class($output) {
		if (has_featured_content()) {
			$output[] = 'has-featured';
		} else {
			$output[] = 'no-featured';
		}

		if(!is_single()) {

		}

		$post_id = get_the_id();
		$post_type = get_post_type($post_id);

		$post_format = noo_get_post_format($post_id, $post_type);

		// Post format class for NOO Portfolio
		if ($post_type == 'portfolio_project') {
			if( is_single() ) {
				$output[] = 'single-noo-portfolio';
			}
			if(!empty($post_format)) {
				$output[] = "media-{$post_format}";
			}
		}

		// Masonry Style
		if(is_masonry_style()) {
			$prefix = ($post_type == 'portfolio_project') ? '_noo_portfolio' : '_noo_wp_post';
			// if it's portfolio page, get the size from setting
			$masonry_size = noo_get_post_meta($post_id, "{$prefix}_masonry_{$post_format}_size", 'regular');
			$output[] = 'masonry-item ' . $masonry_size;

			if($post_type == 'portfolio_project') {
			    $categories = wp_get_object_terms( $post_id, 'portfolio_category' );
			    foreach ( $categories as $category ) {
			      $output[] = 'noo-portfolio-' . $category->slug;
			    }
			}
		}
		
		return $output;
	}
	
	add_filter('post_class', 'noo_post_class');
endif;
