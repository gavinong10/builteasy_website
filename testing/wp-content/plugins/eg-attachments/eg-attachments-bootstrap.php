<?php
/**
 * Bootstrap file for getting the ABSPATH constant to wp-load.php
 * This is requried when a plugin requires access not via the admin screen.
 *
 * If the wp-load.php file is not found, then an error will be displayed
 *
 */

$path  = ''; // It should be end with a trailing slash    

if ( ! defined('WP_LOAD_PATH') ) {

	$bootstrap = 'wp-load.php';
	while( !is_file( $bootstrap ) ) {
		if( is_dir( '..' ) ) 
			chdir( '..' );
		else
			die( 'Could not find WordPress!' );
	}
	require_once( $bootstrap );
}

?>