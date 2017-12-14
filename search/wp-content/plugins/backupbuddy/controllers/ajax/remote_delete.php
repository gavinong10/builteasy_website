<?php
backupbuddy_core::verifyAjaxAccess();


/* remote_delete()
*
* description
*
*/


pb_backupbuddy::verify_nonce(); // Security check.

// Destination ID.
$destination_id = pb_backupbuddy::_GET( 'pb_backupbuddy_destinationid' );

// Delete the destination.
require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
$delete_response = pb_backupbuddy_destinations::delete_destination( $destination_id, true );

// Response.
if ( $delete_response !== true ) { // Some kind of error so just echo it.
	echo 'Error #544558: `' . $delete_response . '`.';
} else { // Success.
	echo 'Destination deleted.';
}

die();

