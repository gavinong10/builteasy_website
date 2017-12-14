<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.


$data = array();
pb_backupbuddy::load_view( 'dbreplace', $data );
?><script>jQuery( '#pageTitle' ).html( 'Database Text Replace' );</script>