<?php
/**
 * WP Element Functions.
 * This file contains functions related to Wordpress base elements.
 * It mostly contains functions for improving trivial issue on Wordpress.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */


// Remove Recent Comments Style
// --------------------------------------------------------

if ( ! function_exists( 'remove_wp_widget_recent_comments_style' ) ) :
	function remove_wp_widget_recent_comments_style() {
		global $wp_widget_factory;
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
	add_filter( 'wp_head', 'remove_wp_widget_recent_comments_style', 1 );
endif;


// Excerpt Length
// --------------------------------------------------------

if ( ! function_exists( 'noo_excerpt_length' ) ) :
	function noo_excerpt_length( $length ) {
		$excerpt_length = noo_get_option('noo_blog_excerpt_length', 60);

		return (empty($excerpt_length) ? 60 : $excerpt_length); 
	}
	add_filter( 'excerpt_length', 'noo_excerpt_length' );
endif;


if(!function_exists('noo_the_excerpt')){
	function noo_the_excerpt($excerpt=''){
		return str_replace('&nbsp;', '', $excerpt);
	}
	add_filter('the_excerpt', 'noo_the_excerpt');
}


// Excerpt Read More
// --------------------------------------------------------

if ( ! function_exists( 'noo_excerpt_read_more' ) ) :
	function noo_excerpt_read_more( $more ) {

		return '...<div>' . noo_get_readmore_link() . '</div>';
	}

	add_filter( 'excerpt_more', 'noo_excerpt_read_more' );
endif;


// Content Read More
// --------------------------------------------------------

if ( ! function_exists( 'noo_content_read_more' ) ) :
	function noo_content_read_more( $more ) {

		return noo_get_readmore_link();
	}

	add_filter( 'the_content_more_link', 'noo_content_read_more' );
endif;


// Navbar search
if(!function_exists('noo_search_menu_item'))
{
	function noo_search_menu_item ( $items, $args ) {
		if( !noo_get_option('noo_header_nav_icon_search', false) ) return $items;
		if ($args->theme_location == 'primary') {
			$searchform = get_search_form(false);
			if( noo_get_option('noo_header_nav_search_property', false) ) {
				$searchform = preg_replace('/<\/form>/', '<input type="hidden" id="post_type" name="post_type" value="noo_property" /></form>', $searchform);
			}
	        $items .= '<li id="nav-menu-item-search" class="menu-item noo-menu-item-search"><a class="search-button" href="#"><i class="fa fa-search"></i></a><div class="searchbar hide">'.$searchform.'</div></li>';
	    }
	    return $items;
	}
	add_filter( 'wp_nav_menu_items', 'noo_search_menu_item', 11, 2 );
}
