<?php
die( '<html></html>' ); // DISABLED.

Auth::require_authentication(); // Die if not logged in.

if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}

if ( file_exists( ABSPATH . 'wp-load.php' ) ) {
	ob_start( 'ob_error_handler' ); //Suppress errors
	require_once( ABSPATH . 'wp-load.php' );
	ob_end_clean();
} else {
	die( 'Unable to find WordPress files to load (wp-load.php). Verify your WordPress site is functional and able to connect to the database.' );
}

$user = get_user_by( 'login', $search_string );
if ( $user ) die( json_encode( $user ) );
