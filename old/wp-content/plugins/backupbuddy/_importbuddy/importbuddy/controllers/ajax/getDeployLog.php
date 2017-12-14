<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}

if ( 'true' != pb_backupbuddy::_GET( 'deploy' ) ) {
	die( 'Access denied.' );
}

// Only allow access to this file if it has a serial hiding it. Used by deployment.
global $importbuddy_file;
$importFileSerial = backupbuddy_core::get_serial_from_file( $importbuddy_file );
if ( '' == $importFileSerial ) {
	die( 'Access denied.' );
}

pb_backupbuddy::status( 'details', '*** End ImportBuddy Log section' );
$status_lines = pb_backupbuddy::get_status( '', true, false, true ); // Clear file, dont unlink file, supress status retrieval msg.
echo implode( '', $status_lines );