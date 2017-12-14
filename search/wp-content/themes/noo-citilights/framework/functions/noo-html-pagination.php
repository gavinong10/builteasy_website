<?php
/**
 * 
 * @param array|null $args
 * @param WP_Query $query
 * @return void|mixed
 */
function noo_pagination( $args = array(), $query = null ){
	global $wp_rewrite, $wp_query;
	
	do_action( 'noo_pagination_start' );
	
	if ( !empty($query)) {
		$wp_query = $query;
	} 
	
	if ( 1 >= $wp_query->max_num_pages )
		return;
	
	$paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	
	$max_num_pages = intval( $wp_query->max_num_pages );
	
	$defaults = array(
			'base' => esc_url( add_query_arg( 'paged', '%#%' ) ),
			'format' => '',
			'total' => $max_num_pages,
			'current' => $paged,
			'prev_next' => true,
			'prev_text' => '<i class="fa fa-angle-left"></i>',
			'next_text' => '<i class="fa fa-angle-right"></i>',
			'show_all' => false,
			'end_size' => 1,
			'mid_size' => 1,
			'add_fragment' => '',
			'type' => 'plain',
			'before' => '<div class="pagination list-center">',
			'after' => '</div>',
			'echo' => true,
			'use_search_permastruct' => true
	);
	
	$defaults = apply_filters( 'noo_pagination_args_defaults', $defaults );
	
	if( $wp_rewrite->using_permalinks() && ! is_search() )
		$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	
	if ( is_search() )
		$defaults['use_search_permastruct'] = false;
	
	if ( is_search() ) {
		if ( class_exists( 'BP_Core_User' ) || $defaults['use_search_permastruct'] == false ) {
			$search_query = get_query_var( 's' );
			$paged = get_query_var( 'paged' );
			$base = esc_url( add_query_arg( 's', urlencode( $search_query ) ) );
			$base = esc_url( add_query_arg( 'paged', '%#%' ) );
			$defaults['base'] = $base;
		} else {
			$search_permastruct = $wp_rewrite->get_search_permastruct();
			if ( ! empty( $search_permastruct ) ) {
				$base = get_search_link();
				$base = esc_url( add_query_arg( 'paged', '%#%', $base ) );
				$defaults['base'] = $base;
			}
		}
	}
	
	$args = wp_parse_args( $args, $defaults );
	
	$args = apply_filters( 'noo_pagination_args', $args );
	
	if ( 'array' == $args['type'] )
		$args['type'] = 'plain';
	
	$pattern = '/\?(.*?)\//i';
	
	preg_match( $pattern, $args['base'], $raw_querystring );
	if(!empty($raw_querystring)){
		if( $wp_rewrite->using_permalinks() && $raw_querystring )
			$raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
		$args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
		$args['base'] .= substr( $raw_querystring[0], 0, -1 );
	}
	$page_links = paginate_links( $args );
	
	$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );
	
	$page_links = $args['before'] . $page_links . $args['after'];
	
	$page_links = apply_filters( 'noo_pagination', $page_links );
	
	do_action( 'noo_pagination_end' );
	
	if ( $args['echo'] )
		echo $page_links;
	else
		return $page_links;
	
}
// Posts Link Attributes
// =============================================================================

if (!function_exists('posts_link_attributes')):
	function posts_link_attributes() {
		return 'class="prev-next hidden-phone"';
	}
	
	add_filter('next_posts_link_attributes', 'posts_link_attributes');
	add_filter('previous_posts_link_attributes', 'posts_link_attributes');
endif;
