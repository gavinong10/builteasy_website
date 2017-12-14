<?php
backupbuddy_core::verifyAjaxAccess();


// Note: importbuddy, backup files, etc should have already been cleaned up by importbuddy itself at this point.

$serial = pb_backupbuddy::_POST( 'serial' );
$direction = pb_backupbuddy::_POST( 'direction' );

pb_backupbuddy::load();

if ( 'pull' == $direction ) { // Local so clean up here.
	
	require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
	backupbuddy_housekeeping::cleanup_temp_tables( $serial );
	die( '1' );
	
} elseif ( 'push' == $direction ) { // Remote so call API to clean up.
	
	require_once( pb_backupbuddy::plugin_path() . '/classes/remote_api.php' );
	
	$destinationID = pb_backupbuddy::_POST( 'destinationID' );
	if ( ! isset( pb_backupbuddy::$options['remote_destinations'][ $destinationID ] ) ) {
		die( 'Error #8383983: Invalid destination ID `' . htmlentities( $destinationID ) . '`.' );
	}
	$destinationArray = pb_backupbuddy::$options['remote_destinations'][ $destinationID ];
	if ( 'site' != $destinationArray['type'] ) {
		die( 'Error #8378332: Destination with ID `' . htmlentities( $destinationID ) . '` not of "site" type.' );
	}
	$apiKey = $destinationArray['api_key'];
	$apiSettings = backupbuddy_remote_api::key_to_array( $apiKey );
	
	if ( false === ( $response = backupbuddy_remote_api::remoteCall( $apiSettings, 'confirmDeployment', array( 'serial' => $serial ), 30, null, null, null, null, null, null, null, $returnRaw = true ) ) ) {
		$message = 'Error #2378378324. Unable to confirm remote deployment with serial `' . $serial . '` via remote API. This is a non-fatal warning. BackupBuddy will automatically clean up temporary data later.';
		pb_backupbuddy::status( 'error', $message );
		die( $message );
	} else {
		if ( false === ( $response_decoded = json_decode( $response, true ) ) ) {
			$message = 'Error #239872373. Unable to decode remote deployment response with serial `' . $serial . '` via remote API. This is a non-fatal warning. BackupBuddy will automatically clean up temporary data later. Remote server response: `' . print_r( $response_decoded, true ) . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( $message );
		}
		if ( isset( $response_decoded['success'] ) && ( true === $response_decoded['success'] ) ) {
			die( '1' );
		} else {
			$message = 'Error #839743. Unable to confirm remote deployment with serial `' . $serial . '` via remote API. This is a non-fatal warning. BackupBuddy will automatically clean up temporary data later. Remote server response: `' . print_r( $response, true ) . '`.';
			pb_backupbuddy::status( 'error', $message );
			die( $message );
		}
	}
	
} else { // Unknown; error.
	
	die( 'Error #8383293: Unknown direction `' . $direction . '` for deployment confirmation.' );
	
}