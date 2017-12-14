<?php
/**
 * Utilities Functions for NOO Framework.
 * This file contains various functions for getting and preparing data.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('smk_get_all_sidebars')):
	function smk_get_all_sidebars() {
		global $wp_registered_sidebars;
		$sidebars = array();
		$none_sidebars = array();
		for ($i = 1;$i <= 4;$i++) {
			$none_sidebars[] = "noo-top-{$i}";
			$none_sidebars[] = "noo-footer-{$i}";
		}
		if ($wp_registered_sidebars && !is_wp_error($wp_registered_sidebars)) {
			
			foreach ($wp_registered_sidebars as $sidebar) {
				// Don't include Top Bar & Footer Widget Area
				if (in_array($sidebar['id'], $none_sidebars)) continue;
				
				$sidebars[$sidebar['id']] = $sidebar['name'];
			}
		}
		return $sidebars;
	}
endif;

if (!function_exists('get_sidebar_name')):
	function get_sidebar_name($id = '') {
		if (empty($id)) return '';
		
		global $wp_registered_sidebars;
		if ($wp_registered_sidebars && !is_wp_error($wp_registered_sidebars)) {
			foreach ($wp_registered_sidebars as $sidebar) {
				if ($sidebar['id'] == $id) return $sidebar['name'];
			}
		}
		
		return '';
	}
endif;

if (!function_exists('get_sidebar_id')):
	function get_sidebar_id() {
		// Normal Page or Static Front Page
		if ( is_page() || (is_front_page() && get_option('show_on_front') == 'page') ) {

			$page_id = get_the_ID();
			$page_template = noo_get_post_meta($page_id, '_wp_page_template', 'default');

			// search result get the global setting of property
			if( $page_template == 'search-property-result.php' ) {
				$noo_property_layout =  noo_get_option('noo_property_layout','fullwidth');
				if($noo_property_layout != 'fullwidth'){
					return noo_get_option('noo_property_sidebar', '');
				}
				return '';
			}

			// Get the sidebar setting from
			$sidebar = noo_get_post_meta(get_the_ID(), '_noo_wp_page_sidebar', 'sidebar-main');
			
			return $sidebar;
		}

		// WooCommerce Product
		if( is_plugin_active('woocommerce/woocommerce.php') ) {
			if( is_product() ) {
				$product_layout = noo_get_option('noo_woocommerce_product_layout', 'same_as_shop');
				$sidebar = '';
				if ( $product_layout == 'same_as_shop' ) {
					$product_layout = noo_get_option('noo_shop_layout', 'fullwidth');
					$sidebar = noo_get_option('noo_shop_sidebar', '');
				} else {
					$sidebar = noo_get_option('noo_woocommerce_product_sidebar', '');
				}
				
				if ( $product_layout == 'fullwidth' ) {
					return '';
				}
				
				return $sidebar;
			}

			// Shop, Product Category, Product Tag, Cart, Checkout page
			if( is_shop() || is_product_category() || is_product_tag() ) {
				$shop_layout = noo_get_option('noo_shop_layout', 'fullwidth');
				if($shop_layout != 'fullwidth'){
					return noo_get_option('noo_shop_sidebar', '');
				}

				return '';
			}
		}
		
		
		// NOO Portfolio
		if( is_post_type_archive( 'portfolio_project' )
			|| is_tax( 'portfolio_category' )
			|| is_tax( 'portfolio_tag' )
			|| is_singular( 'portfolio_project' ) ) {

			$portfolio_layout = noo_get_option('noo_portfolio_layout', 'fullwidth');
			if ($portfolio_layout != 'fullwidth') {
				return noo_get_option('noo_portfolio_sidebar', '');
			}

			return '';
		}
		
		// NOO Property
		if(is_post_type_archive('noo_property') 
				|| is_tax('property_category')
				|| is_tax('property_status')
				|| is_tax('property_label') 
				|| is_tax('property_location') 
				|| is_tax('property_sub_location')) {
			$noo_property_layout =  noo_get_option('noo_property_layout','fullwidth');
			if($noo_property_layout != 'fullwidth'){
				return noo_get_option('noo_property_sidebar', '');
			}
			return '';
		}

		if(is_singular( 'noo_property' )) {
			$property_detail_layout = noo_get_option('noo_property_detail_layout', 'same_as_listing');
			$sidebar = '';
			if ($property_detail_layout == 'same_as_listing') {
				$property_detail_layout = noo_get_option('noo_property_layout', 'fullwidth');
				$sidebar = noo_get_option('noo_property_sidebar', '');
			} else {
				$sidebar = noo_get_option('noo_property_detail_sidebar', '');
			}

			if($property_detail_layout == 'fullwidth'){
				return '';
			}
			
			return $sidebar;
		}

		// Noo Agent
		if(is_post_type_archive('noo_agent') || is_singular('noo_agent')) {
			$noo_agent_layout =  noo_get_option('noo_agent_layout','fullwidth');
			if($noo_agent_layout != 'fullwidth'){
				return noo_get_option('noo_agent_sidebar', '');
			}
			return '';
		}
		
		// Single post page
		if (is_single()) {
			// Check if there's overrode setting in this post.
			$post_id = get_the_ID();
			$override_setting = noo_get_post_meta($post_id, '_noo_wp_post_override_layout', false);
			if ($override_setting) {
				// overrode
				$overrode_layout = noo_get_post_meta($post_id, '_noo_wp_post_layout', 'fullwidth');
				if ($overrode_layout != 'fullwidth') {
					return noo_get_post_meta($post_id, '_noo_wp_post_sidebar', 'sidebar-main');
				}
			} else{

				$post_layout = noo_get_option('noo_blog_post_layout', 'same_as_blog');
				$sidebar = '';
				if ($post_layout == 'same_as_blog') {
					$post_layout = noo_get_option('noo_blog_layout', 'sidebar');
					$sidebar = noo_get_option('noo_blog_sidebar', 'sidebar-main');
				} else {
					$sidebar = noo_get_option('noo_blog_post_sidebar', 'sidebar-main');
				}
				
				if($post_layout == 'fullwidth'){
					return '';
				}
				
				return $sidebar;
			}

			return '';
		}

		// Archive page
		if( is_archive() ) {
			$archive_layout = noo_get_option('noo_blog_archive_layout', 'same_as_blog');
			$sidebar = '';
			if ($archive_layout == 'same_as_blog') {
				$archive_layout = noo_get_option('noo_blog_layout', 'sidebar');
				$sidebar = noo_get_option('noo_blog_sidebar', 'sidebar-main');
			} else {
				$sidebar = noo_get_option('noo_blog_archive_sidebar', 'sidebar-main');
			}
			
			if($archive_layout == 'fullwidth'){
				return '';
			}
			
			return $sidebar;
		}

		// Archive, Index or Home
		if (is_home() || (is_front_page() && get_option('show_on_front') == 'posts')) {
			
			$blog_layout = noo_get_option('noo_blog_layout', 'sidebar');
			if ($blog_layout != 'fullwidth') {
				return noo_get_option('noo_blog_sidebar', 'sidebar-main');
			}
			
			return '';
		}

		if( is_search() ) {
			if( !isset( $_GET['post_type'] ) || $_GET['post_type'] == 'post' ) {
				$blog_layout = noo_get_option('noo_blog_layout', 'sidebar');
				if ($blog_layout != 'fullwidth') {
					return noo_get_option('noo_blog_sidebar', 'sidebar-main');
				}
			}
		}
		
		return '';
	}
endif;

if ( !function_exists('noo_default_primary_color') ) :
	function noo_default_primary_color() {
		return '#75b08a';
	}
endif;
if ( !function_exists('noo_default_font_family') ) :
	function noo_default_font_family() {
		return 'Lato';
	}
endif;
if ( !function_exists('noo_default_text_color') ) :
	function noo_default_text_color() {
		if( noo_get_option( 'noo_site_skin', 'light' ) == 'dark' ) {
			return '#B8B8B8';
		}

		return '#2d313f';
	}
endif;
if ( !function_exists('noo_default_headings_font_family') ) {
	function noo_default_headings_font_family() {
		return noo_default_font_family();
	}
}
if ( !function_exists('noo_default_headings_color') ) {
	function noo_default_headings_color() {
		if( noo_get_option( 'noo_site_skin', 'light' ) == 'dark' ) {
			return '#D2D2D2';
		}

		return '#181a21';
	}
}
if ( !function_exists('noo_default_header_bg') ) {
	function noo_default_header_bg() {
		if( noo_get_option( 'noo_site_skin', 'light' ) == 'dark' ) {
			return '#000000';
		}

		return '#FFFFFF';
	}
}
if ( !function_exists('noo_default_logo_font_family') ) {
	function noo_default_logo_font_family() {
		return noo_default_headings_font_family();
	}
}
if ( !function_exists('noo_default_logo_color') ) {
	function noo_default_logo_color() {
		return '#444444';
	}
}
if ( !function_exists('noo_default_font_size') ) {
	function noo_default_font_size() {
		return '16';
	}
}
if ( !function_exists('noo_default_font_weight') ) {
	function noo_default_font_weight() {
		return '300';
	}
}

if( !function_exists('noo_is_first_page') ) :
	function noo_is_first_page(){
		// get current page we are on. If not set we can assume we are on page 1.
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		// are we on page one?
		if(1 == $paged) {
			return true;
		}

		return false;
	}
endif;

//
// This function help to create the dynamic thumbnail width,
// but we don't use it at the moment.
// 
if (!function_exists('noo_thumbnail_width')) :
	function noo_thumbnail_width() {
		$site_layout	= noo_get_option('noo_site_layout', 'fullwidth');
		$page_layout	= get_page_layout();
		$width			= 1200; // max width

		if($site_layout == 'boxed') {
			$site_width = (int) noo_get_option('noo_layout_site_width', '90');
			$site_max_width = (int) noo_get_option('noo_layout_site_max_width', '1200');
			$width = min($width * $site_width / 100, $site_max_width);
		}

		if($page_layout != 'fullwidth') {
			$width = $width * 75 / 100; // 75% of col-9
		}

		return $width;
	}
endif;

if (!function_exists('get_thumbnail_width')) :
	function get_thumbnail_width() {

		// if( is_admin()) {
		// 	return 'admin-thumb';
		// }

		// // NOO Portfolio
		// if( is_post_type_archive( 'portfolio_project' ) ) {
		// 	// if it's portfolio page, check if the masonry size is fixed or original
		// 	if(noo_get_option('noo_portfolio_masonry_item_size', 'original' ) == 'fixed') {
		// 		$masonry_size = noo_get_post_meta($post_id, '_noo_portfolio_image_masonry_size', 'regular');
		// 		return "masonry-fixed-{$masonry_size}";
		// 	}
		// }

		// $site_layout	= noo_get_option('noo_site_layout', 'fullwidth');
		// $page_layout	= get_page_layout();

		// if($site_layout == 'boxed') {
		// 	if($page_layout == 'fullwidth') {
		// 		return 'boxed-fullwidth';
		// 	} else {
		// 		return 'boxed-sidebar';
		// 	}
		// } else {
		// 	if($page_layout == 'fullwidth') {
		// 		return 'fullwidth-fullwidth';
		// 	} else {
		// 		return 'fullwidth-sidebar';
		// 	}
		// }

		return 'property-image';
	}
endif;

if (!function_exists('get_page_layout')):
	function get_page_layout() {

		// Normal Page or Static Front Page
		if (is_page() || (is_front_page() && get_option('show_on_front') == 'page')) {
			// WP page,
			// get the page template setting
			$page_id = get_the_ID();
			$page_template = noo_get_post_meta($page_id, '_wp_page_template', 'default');

			// search result get the global setting of property
			if( $page_template == 'search-property-result.php' ) {
				return noo_get_option('noo_property_layout', 'fullwidth');
			}
			
			if (strpos($page_template, 'sidebar') !== false) {
				if (strpos($page_template, 'left') !== false) {
					return 'left_sidebar';
				}
				
				return 'sidebar';
			}

			if(strpos($page_template, 'dashboard') !== false) {
				return 'left_sidebar';
			}
			
			return 'fullwidth';
		}
		// WooCommerce
		if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			if( is_shop() || is_product_category() || is_product_tag() ){
				return noo_get_option('noo_shop_layout', 'fullwidth');
			}

			if( is_product() ) {
				$product_layout = noo_get_option('noo_woocommerce_product_layout', 'same_as_shop');
				if ($product_layout == 'same_as_shop') {
					$product_layout = noo_get_option('noo_shop_layout', 'fullwidth');
				}
				return $product_layout;
			}
		}
		
		// NOO Portfolio
		if( is_post_type_archive( 'portfolio_project' )
			|| is_tax( 'portfolio_category' )
			|| is_tax( 'portfolio_tag' )
			|| is_singular( 'portfolio_project' ) ) {

			return noo_get_option('noo_portfolio_layout', 'fullwidth');
		}

		// Noo Property
		if(is_post_type_archive('noo_property') 
				|| is_tax('property_category')
				|| is_tax('property_status')
				|| is_tax('property_label') 
				|| is_tax('property_location') 
				|| is_tax('property_sub_location')) {
			return noo_get_option('noo_property_layout','fullwidth');
		}

		if(is_singular( 'noo_property' )) {
			$property_detail_layout = noo_get_option('noo_property_detail_layout', 'same_as_listing');
			if ($property_detail_layout == 'same_as_listing') {
				$property_detail_layout = noo_get_option('noo_property_layout', 'fullwidth');
			}
			
			return $property_detail_layout;
		}

		// Noo Agent
		if(is_post_type_archive('noo_agent') || is_singular('noo_agent')) {
			return noo_get_option('noo_agent_layout','fullwidth');
		}
		
		// Single post page
		if (is_single()) {

			// WP post,
			// check if there's overrode setting in this post.
			$post_id = get_the_ID();
			$override_setting = noo_get_post_meta($post_id, '_noo_wp_post_override_layout', false);
			
			if ( !$override_setting ) {
				$post_layout = noo_get_option('noo_blog_post_layout', 'same_as_blog');
				if ($post_layout == 'same_as_blog') {
					$post_layout = noo_get_option('noo_blog_layout', 'sidebar');
				}
				
				return $post_layout;
			}

			// overrode
			return noo_get_post_meta($post_id, '_noo_wp_post_layout', 'sidebar-main');
		}

		// Archive
		if (is_archive()) {
			$archive_layout = noo_get_option('noo_blog_archive_layout', 'same_as_blog');
			if ($archive_layout == 'same_as_blog') {
				$archive_layout = noo_get_option('noo_blog_layout', 'sidebar');
			}
			
			return $archive_layout;
		}

		// Index or Home
		if (is_home() || (is_front_page() && get_option('show_on_front') == 'posts')) {
			
			return noo_get_option('noo_blog_layout', 'sidebar');
		}

		// Search
		if( is_search() ) {
			if( !isset( $_GET['post_type'] ) || $_GET['post_type'] == 'post' ) {
				$blog_layout = noo_get_option('noo_blog_layout', 'sidebar');
				if ($blog_layout != 'fullwidth') {
					return noo_get_option('noo_blog_layout', 'sidebar');
				}
			}
		}
		
		return 'fullwidth';
	}
endif;

if(!function_exists('is_fullwidth')){
	function is_fullwidth(){
		return get_page_layout() == 'fullwidth';
	}
}

if (!function_exists('is_one_page_enabled')):
	function is_one_page_enabled() {
		// if( (is_front_page() && get_option('show_on_front' == 'page')) || is_page()) {
		// 	$page_id = get_the_ID();
		// 	return ( noo_get_post_meta( $page_id, '_noo_wp_page_enable_one_page', false ) );
		// }

		return false;
	}
endif;

if (!function_exists('get_one_page_menu')):
	function get_one_page_menu() {
		if( is_one_page_enabled() ) {
			if( (is_front_page() && get_option('show_on_front' == 'page')) || is_page()) {
				$page_id = get_the_ID();
				return noo_get_post_meta( $page_id, '_noo_wp_page_one_page_menu', '' );
			}
		}

		return '';
	}
endif;

if (!function_exists('has_home_slider')):
	function has_home_slider() {
		if (class_exists( 'RevSlider' )) {
			if( (is_front_page() && get_option('show_on_front' == 'page')) || is_page()) {
				$page_id = get_the_ID();
				return ( noo_get_post_meta( $page_id, '_noo_wp_page_enable_home_slider', false ) )
					&& ( noo_get_post_meta( $page_id, '_noo_wp_page_slider_rev', '' ) != '' );
			}
		}

		return false;
	}
endif;

if (!function_exists('home_slider_position')):
	function home_slider_position() {
		if (has_home_slider()) {
			return noo_get_post_meta( get_the_ID(), '_noo_wp_page_slider_position', 'below' );
		}

		return '';
	}
endif;

if (!function_exists('is_masonry_style')):
	function is_masonry_style() {
		if( is_post_type_archive( 'portfolio_project' ) || is_tax('portfolio_category') || is_tax('portfolio_tag')  ) {
			return true;
		}

		if(is_home()) {
			return (noo_get_option( 'noo_blog_style' ) == 'masonry');
		}
		
		if(is_archive()) {
			$archive_style = noo_get_option( 'noo_blog_archive_style', 'same_as_blog' );
			if ($archive_style == 'same_as_blog') {
				return (noo_get_option( 'noo_blog_style', 'standard' ) == 'masonry');
			} else {
				return ($archive_style == 'masonry');
			}
		}

		return false;
	}
endif;

// if (!function_exists('get_page_heading')):
// 	function get_page_heading() {
// 		$heading = '';
// 		$sub_heading = '';
// 		if ( is_home() ) {
// 			if ( noo_get_option( 'noo_blog_heading', false ) ) {
// 				$heading = noo_get_option( 'noo_blog_heading_title', __( 'My Blog', 'noo' ) );
// 				$sub_heading = noo_get_option( 'noo_blog_heading_sub_title', '' );
// 			}
// 		} elseif ( is_post_type_archive( 'portfolio_project' ) ) {
// 			if ( noo_get_option( 'noo_portfolio_heading', true ) ) {
// 				$heading = noo_get_option( 'noo_portfolio_heading_title', __( 'My Portfolio', 'noo' ) );
// 				$sub_heading = noo_get_option( 'noo_portfolio_heading_sub_title', '' );
// 			}
// 		} elseif ( NOO_WOOCOMMERCE_EXIST && is_shop() ) {
// 			if ( noo_get_option( 'noo_shop_heading', true ) ) {
// 				$heading = noo_get_option( 'noo_shop_heading_title', __( 'My Shop', 'noo' ) );
// 				$sub_heading = noo_get_option( 'noo_shop_heading_sub_title', '' );
// 			}
// 		} elseif ( is_search() ) {
// 			$heading = __( 'Search Results', 'noo' );
// 			global $wp_query;
// 			if(!empty($wp_query->found_posts)) {
// 				if($wp_query->found_posts > 1) {
// 					$heading =  $wp_query->found_posts ." ". __('Search Results for:','noo')." ".esc_attr( get_search_query() );
// 				} else {
// 					$heading =  $wp_query->found_posts ." ". __('Search Results for:','noo')." ".esc_attr( get_search_query() );
// 				}
// 			} else {
// 				if(!empty($_GET['s'])) {
// 					$heading = __('Search Results for:','noo')." ".esc_attr( get_search_query() );
// 				} else {
// 					$heading = __('To search the site please enter a valid term','noo');
// 				}
// 			}
// 		} elseif ( is_author() ) {
// 			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
// 			$heading = __('Author Archive','noo')." ";

// 			if(isset($curauth->nickname)) $heading .= __('for:','noo')." ".$curauth->nickname;
// 		} elseif ( is_tax( 'portfolio_category' ) || ( NOO_WOOCOMMERCE_EXIST && is_product_category() ) || is_category() ) {
// 			$queried_object = get_queried_object();
// 			$term_meta      = get_option( 'taxonomy_' . $queried_object->term_id );
// 			$enable_heading = isset( $term_meta['enable-heading'] ) ? $term_meta['enable-heading'] : true;
// 			if( $enable_heading ) {
// 				$heading        = ( $term_meta['heading-title']    != '' ) ? $term_meta['heading-title'] : single_cat_title( '', false );
// 				$sub_heading    = ( $term_meta['heading-sub-title'] != '' ) ? $term_meta['heading-sub-title'] : '';
// 			}
// 		} elseif ( is_tax( 'portfolio_tag' ) || ( NOO_WOOCOMMERCE_EXIST && is_product_tag() ) || is_tag() ) {
// 			$queried_object = get_queried_object();
// 			$term_meta      = get_option( 'taxonomy_' . $queried_object->term_id );
// 			$enable_heading = isset( $term_meta['enable-heading'] ) ? $term_meta['enable-heading'] : true;
// 			if( $enable_heading ) {
// 				$heading        = ( $term_meta['heading-title'] != '' ) ? $term_meta['heading-title'] : single_cat_title( '', false );
// 				$sub_heading    = ( $term_meta['heading-sub-title'] != '' ) ? $term_meta['heading-sub-title'] : '';
// 			}
// 		} elseif ( is_singular( 'portfolio_project' ) || is_singular( 'product' ) || is_page() || is_single() ) {
// 			$enable_heading = noo_get_post_meta(get_the_ID(), '_noo-enable-heading', true);
// 			if( $enable_heading ) {
// 				$heading = noo_get_post_meta(get_the_ID(), '_noo-heading-title', get_the_title());
// 				$sub_heading = noo_get_post_meta(get_the_ID(), '_noo-heading-sub-title', '');
// 			}
// 		} elseif ( is_year() ) {
//     		$heading = __( 'Post Archive by Year: ', 'noo' ) . get_the_date( 'Y' );
// 		} elseif ( is_month() ) {
//     		$heading = __( 'Post Archive by Month: ', 'noo' ) . get_the_date( 'F,Y' );
// 		} elseif ( is_day() ) {
//     		$heading = __( 'Post Archive by Day: ', 'noo' ) . get_the_date( 'F j, Y' );
// 		} elseif ( is_404() ) {
//     		$heading = __( 'Oops! We could not find anything to show to you.', 'noo' );
//     		$sub_heading =  __( 'Would you like going else where to find your stuff.', 'noo' );
// 		}

// 		return array($heading, $sub_heading);
// 	}
// endif;

// if (!function_exists('get_page_heading_image')):
// 	function get_page_heading_image() {
// 		$image = '';
// 		if ( is_post_type_archive( 'portfolio_project' ) ) {
// 			if ( noo_get_option( 'noo_portfolio_heading' ) ) {
// 				$image = noo_get_option( 'noo_portfolio_heading_image', '' );
// 			}
// 		} elseif( NOO_WOOCOMMERCE_EXIST && is_shop() ) {
// 			if ( noo_get_option( 'noo_shop_heading' ) ) {
// 				$image = noo_get_option( 'noo_shop_heading_image', '' );
// 			}			
// 		} elseif ( is_home() ) {
// 			if ( noo_get_option( 'noo_blog_heading' ) ) {
// 				$image = noo_get_option( 'noo_blog_heading_image', '' );
// 			}
// 		} elseif( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_tag' ) ) {
// 			$queried_object = get_queried_object();
// 			$term_meta      = get_option( 'taxonomy_' . $queried_object->term_id );
// 			$enable_heading = isset( $term_meta['enable-heading'] ) ? $term_meta['enable-heading'] : true;
// 			if( $enable_heading ) {
// 				$image = isset( $term_meta['heading-image'] ) ? $term_meta['heading-image'] : noo_get_option( 'noo_portfolio_heading_image', '' );
// 			}
// 		} elseif ( NOO_WOOCOMMERCE_EXIST && ( is_product_category() || is_product_tag() ) ) {
// 			$queried_object = get_queried_object();
// 			$term_meta      = get_option( 'taxonomy_' . $queried_object->term_id );
// 			$enable_heading = isset( $term_meta['enable-heading'] ) ? $term_meta['enable-heading'] : true;
// 			if( $enable_heading ) {
// 				$image = isset( $term_meta['heading-image'] ) ? $term_meta['heading-image'] : noo_get_option( 'noo_shop_heading_image', '' );
// 			}
// 		} elseif(  is_category() || is_tag() ) {
// 			$queried_object = get_queried_object();
// 			$term_meta      = get_option( 'taxonomy_' . $queried_object->term_id );
// 			$enable_heading = isset( $term_meta['enable-heading'] ) ? $term_meta['enable-heading'] : true;
// 			if( $enable_heading ) {
// 				$image = isset( $term_meta['heading-image'] ) ? $term_meta['heading-image'] : noo_get_option( 'noo_blog_heading_image', '' );
// 			}
// 		} elseif ( is_singular( 'portfolio' ) ) {
// 			$enable_heading = noo_get_post_meta(get_the_ID(), '_noo-enable-heading', true);
// 			if( $enable_heading ) {
// 				$image = noo_get_post_meta(get_the_ID(), '_noo-heading-image', '');
// 				$image = ! empty( $image ) ? $image : noo_get_option( 'noo_porfolio_heading_image', '' );
// 			}
// 		} elseif ( NOO_WOOCOMMERCE_EXIST && is_product() ) {
// 			$enable_heading = noo_get_post_meta(get_the_ID(), '_noo-enable-heading', true);
// 			if( $enable_heading ) {
// 				$image = noo_get_post_meta(get_the_ID(), '_noo-heading-image', '');
// 				$image = ! empty( $image ) ? $image : noo_get_option( 'noo_shop_heading_image', '' );
// 			}
// 		} elseif ( is_single() ) {
// 			$enable_heading = noo_get_post_meta(get_the_ID(), '_noo-enable-heading', true);
// 			if( $enable_heading ) {
// 				$image = noo_get_post_meta(get_the_ID(), '_noo-heading-image', '');
// 				$image = ! empty( $image ) ? $image : noo_get_option( 'noo_blog_heading_image', '' );
// 			}
// 		} elseif ( is_page() ) {
// 			$enable_heading = noo_get_post_meta(get_the_ID(), '_noo-enable-heading', true);
// 			if( $enable_heading ) {
// 				$image = noo_get_post_meta(get_the_ID(), '_noo-heading-image', '');
// 			}
// 		}

// 		$image = ! empty( $image ) ? wp_get_attachment_url( $image ) : '';

// 		return $image;
// 	}
// endif;

if (!function_exists('noo_get_post_format')):
	function noo_get_post_format($post_id = null, $post_type = '') {
		$post_id = (null === $post_id) ? get_the_ID() : $post_id;
		$post_type = ('' === $post_type) ? get_post_type($post_id) : $post_type;

		$post_format = '';
		
		if ($post_type == 'post') {
			$post_format = get_post_format($post_id);
		}
		
		if ($post_type == 'portfolio_project') {
			$post_format = noo_get_post_meta($post_id, '_noo_portfolio_media_type', 'image');
		}

		return $post_format;
	}
endif;

if (!function_exists('has_featured_content')):
	function has_featured_content($post_id = null) {
		$post_id = (null === $post_id) ? get_the_ID() : $post_id;

		$post_type = get_post_type($post_id);
		$prefix = '';
		$post_format = '';
		
		if ($post_type == 'post') {
			$prefix = '_noo_wp_post';
			$post_format = get_post_format($post_id);
		}
		
		if ($post_type == 'portfolio_project') {
			$prefix = '_noo_portfolio';
			$post_format = noo_get_post_meta($post_id, "{$prefix}_media_type", 'image');
		}
		
		switch ($post_format) {
			case 'image':
				$main_image = noo_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
				if( $main_image == 'featured') {
					return has_post_thumbnail($post_id);
				}

				return has_post_thumbnail($post_id) || ( (bool)noo_get_post_meta($post_id, "{$prefix}_image", '') );
			case 'gallery':
				if (!is_singular()) {
					$preview_content = noo_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
					if ($preview_content == 'featured') {
						return has_post_thumbnail($post_id);
					}
				}
				
				return (bool)noo_get_post_meta($post_id, "{$prefix}_gallery", '');
			case 'video':
				if (!is_singular()) {
					$preview_content = noo_get_post_meta($post_id, "{$prefix}_preview_video", 'both');
					if ($preview_content == 'featured') {
						return has_post_thumbnail($post_id);
					}
				}
				
				$m4v_video = (bool)noo_get_post_meta($post_id, "{$prefix}_video_m4v", '');
				$ogv_video = (bool)noo_get_post_meta($post_id, "{$prefix}_video_ogv", '');
				$embed_video = (bool)noo_get_post_meta($post_id, "{$prefix}_video_embed", '');
				
				return $m4v_video || $ogv_video || $embed_video;
			case 'link':
			case 'quote':
				return false;
				
			case 'audio':
				$mp3_audio = (bool)noo_get_post_meta($post_id, "{$prefix}_audio_mp3", '');
				$oga_audio = (bool)noo_get_post_meta($post_id, "{$prefix}_audio_oga", '');
				$embed_audio = (bool)noo_get_post_meta($post_id, "{$prefix}_audio_embed", '');
				return $mp3_audio || $oga_audio || $embed_audio;
			default: // standard post format
				return has_post_thumbnail($post_id);
		}
		
		return false;
	}
endif;

if (!function_exists('noo_get_page_link_by_template')):
	function noo_get_page_link_by_template( $page_template ) {
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $page_template
		));

		if( $pages ){
			$link = get_permalink( $pages[0]->ID );
		}else{
			$link=home_url();
		}
		return $link;
	}
endif;

if (!function_exists('noo_current_url')):
	function noo_current_url($encoded = false) {
		global $wp;
		$current_url = esc_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		if( $encoded ) {
			return urlencode($current_url);
		}
		return $current_url;
	}
endif;

if (!function_exists('noo_upload_dir_name')):
	function noo_upload_dir_name() {
		return 'noo_citilights';
	}
endif;

if (!function_exists('noo_upload_dir')):
	function noo_upload_dir() {
		$upload_dir = wp_upload_dir();

		return $upload_dir['basedir'] . '/' . noo_upload_dir_name();
	}
endif;

if (!function_exists('noo_upload_url')):
	function noo_upload_url() {
		$upload_dir = wp_upload_dir();

		return $upload_dir['baseurl'] . '/' . noo_upload_dir_name();
	}
endif;

if (!function_exists('noo_create_upload_dir')):
	function noo_create_upload_dir( $wp_filesystem = null ) {
		if( empty( $wp_filesystem ) ) {
			return false;
		}

		$upload_dir = wp_upload_dir();
		global $wp_filesystem;

		$noo_upload_dir = $wp_filesystem->find_folder( $upload_dir['basedir'] ) . noo_upload_dir_name();
		if ( ! $wp_filesystem->is_dir( $noo_upload_dir ) ) {
			if ( $wp_filesystem->mkdir( $noo_upload_dir, 0777 ) ) {
				return $noo_upload_dir;
			}

			return false;
		}

		return $noo_upload_dir;
	}
endif;

/**
 * This function is original from Visual Composer. Redeclare it here so that it could be used for site without VC.
 */
if ( !function_exists('noo_handler_shortcode_content') ):
	function noo_handler_shortcode_content( $content, $autop = false ) {
		if ( $autop ) {
			$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
		}
		return do_shortcode( shortcode_unautop( $content) );
	}
endif;

if (!function_exists('noo_wp_title')) :
	function noo_wp_title( $title ) {
		if ( defined('WPSEO_VERSION') ) {
			return $title;
		}

		$title = bloginfo('name') . ' ' . $title;

		return $title;
	}
endif;
add_filter( 'wp_title', 'noo_wp_title', 10, 1 );

if (!function_exists('noo_mail')) :
	function noo_mail( $to = '', $subject = '', $body = '', $headers = '' ) {
		add_filter( 'wp_mail_content_type', 'noo_mail_set_html_content' );

		if( empty( $headers ) ) {
			$headers = array();
			$from = '<'.noo_mail_do_not_reply().'>';
			$blogname = '';
			if ( is_multisite() )
				$blogname = $GLOBALS['current_site']->site_name;
			else
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

			$headers[] = 'From: ' . $blogname . ' ' . $from;
		}

		wp_mail( $to, $subject, $body, $headers );

		// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
		remove_filter( 'wp_mail_content_type', 'noo_mail_set_html_content' );
	}
endif;

if (!function_exists('noo_mail_set_html_content')) :
	function noo_mail_set_html_content() {
		return 'text/html';
	}
endif;

if (!function_exists('noo_mail_do_not_reply')) :
	function noo_mail_do_not_reply(){
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) === 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		return apply_filters( 'noo_mail_do_not_reply', 'noreply@' . $sitename );
	}
endif;

