<?php


// 2.9

if ( ! function_exists( 'is_multisite' ) ) {
	function is_multisite() {
		return false;
	}
}

if ( ! function_exists( 'is_network_admin' ) ) {
	function is_network_admin() {
		return false;
	}
}

// 2.8

if ( ! function_exists( 'home_url' ) ) {
	function home_url() {
		return get_option( 'home' );
	}
}


// 2.7

if ( ! function_exists( 'add_site_option' ) ) {
	function add_site_option( $key, $value ) {
		return add_option($key, $value);
	}
}

if ( ! function_exists( 'get_site_option' ) ) {
	function get_site_option( $key, $default = false, $use_cache = true ) {
		return get_option($key, $default);
	}
}

if ( ! function_exists( 'esc_url_raw' ) ) {
	function esc_url_raw( $url, $protocols = null ) {
		return clean_url( $url, $protocols, 'db' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url, $protocols = null ) {
		return clean_url( $url, $protocols, 'display' );
	}
}

if ( ! function_exists( 'wp_script_is' ) ) {
	function wp_script_is( $handle, $list = 'queue' ) {
		global $wp_scripts;
		if ( !is_a($wp_scripts, 'WP_Scripts') )
			$wp_scripts = new WP_Scripts();
		
		$query = $wp_scripts->query( $handle, $list );
		
		if ( is_object( $query ) )
			return true;
		
		return $query;
	}
}

if ( ! function_exists( 'wp_style_is' ) ) {
	function wp_style_is( $handle, $list = 'queue' ) {
		global $wp_styles;
		if ( !is_a( $wp_styles, 'WP_Scripts' ) )
			$wp_styles = new WP_Styles();
		
		$query = $wp_styles->query( $handle, $list );
		
		if ( is_object( $query ) )
			return true;
		
		return $query;
	}
}

if ( ! function_exists( 'fetch_feed' ) ) {
	function fetch_feed( $url ) {
		return new WP_Error( 'unsupported', 'This version of WordPress does not support this function.' );
	}
}


// 2.6

if ( ! function_exists( 'wp_remote_post' ) ) {
	function wp_remote_post( $url, $args = array() ) {
		return new WP_Error( 'unsupported', 'This version of WordPress does not support this function.' );
	}
}

if ( ! function_exists( 'wp_remote_get' ) ) {
	function wp_remote_get( $url, $args = array() ) {
		return new WP_Error( 'unsupported', 'This version of WordPress does not support this function.' );
	}
}

if ( ! function_exists( 'wp_remote_retrieve_response_code' ) ) {
	function wp_remote_retrieve_response_code(&$response) {
		return '';
	}
}

if ( ! function_exists( 'wp_remote_retrieve_body' ) ) {
	function wp_remote_retrieve_body(&$response) {
		return '';
	}
}
