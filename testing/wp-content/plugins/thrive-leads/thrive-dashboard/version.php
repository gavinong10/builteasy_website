<?php
/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 12/8/2015
 * Time: 1:06 PM
 */

if ( ! function_exists( 'tve_dash_load' ) ) {

	add_action( 'after_setup_theme', 'tve_dash_load', 9 );

	function tve_dash_version_compare( $v1, $v2 ) {
		return version_compare( $v1, $v2 );
	}

	function tve_dash_load() {
		uksort( $GLOBALS['tve_dash_versions'], 'tve_dash_version_compare' );

		$last_dash = $GLOBALS['tve_dash_included'] = end( $GLOBALS['tve_dash_versions'] );

		$GLOBALS['tve_dash_loaded_from'] = $last_dash['from'];

		require_once $last_dash['path'];
	}
}

return '1.0.33';
