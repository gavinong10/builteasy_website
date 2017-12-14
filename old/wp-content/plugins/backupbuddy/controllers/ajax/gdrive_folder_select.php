<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::load();

//$destinationID = pb_backupbuddy::_POST( 'destinationID' ); // BackupBuddy destination ID number for remote destinations array.
$parentID = pb_backupbuddy::_POST( 'parentID' ); // Gdrive folder parent ID to list within. Use ROOT for looking in root of account.
$parentID = str_replace( array( '\\', '/', "'", '"' ), '', $parentID );

/*
if ( ( '' == $destinationID ) || ( ! is_numeric( $destinationID ) ) || ( '' == $parentID ) ) {
	die( json_encode( array( 'success' => false, 'message' => 'Missing or invalid required parameter.' ) ) );
}

if ( ! isset( pb_backupbuddy::$options['remote_destinations'][ $destinationID ] ) ) {
	die( json_encode( array( 'success' => false, 'message' => 'Invalid remote destination ID number.' ) ) );
}
*/

$settings = array();
$clientID = pb_backupbuddy::_POST( 'clientID' );
$clientSecret = pb_backupbuddy::_POST( 'clientSecret' );
$tokens = pb_backupbuddy::_POST( 'tokens' );
$settings['client_id'] = $clientID;
$settings['client_secret'] = $clientSecret;
$settings['tokens'] = $tokens;



require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/init.php' );
$returnFiles = array();

// Get all folders in this parent location.
$files = pb_backupbuddy_destination_gdrive::listFiles( $settings, 'mimeType = "application/vnd.google-apps.folder" AND "' . $parentID . '" in parents AND trashed=false' ); //"title contains 'backup' and trashed=false" ); //"title contains 'backup' and trashed=false" );
foreach( $files as $file ) {
	if ( '1' != $file->editable ) { // Only show folders we can write to.
		continue;
	}
	//echo '<span data-id="' . $file->id . '">' . $file->title . $file->createdDate . '</span>';
	
	$returnFiles[] = array(
		'id' => $file->id,
		'title' => $file->title,
		'created' => pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( strtotime( $file->createdDate ) ) ),
		'createdAgo' => pb_backupbuddy::$format->time_ago( strtotime( $file->createdDate ) ),
	);
}

die( json_encode( array( 'success' => true, 'folders' => $returnFiles ) ) );