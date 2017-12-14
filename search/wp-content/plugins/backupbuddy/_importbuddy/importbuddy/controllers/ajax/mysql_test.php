<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.

// Include the DB Tests class and init it.
require_once( ABSPATH . 'importbuddy/classes/test-db.php' );
$importbuddy_test_db = new importbuddy_test_db();
