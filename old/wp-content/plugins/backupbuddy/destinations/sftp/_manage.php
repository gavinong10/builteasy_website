<?php
// Authored by Dustin Bolton - Summer 2013.
// Incoming variables: $destination
if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}


//pb_backupbuddy::$ui->title( 'sFTP' );

require_once( pb_backupbuddy::plugin_path() . '/destinations/sftp/init.php' );
pb_backupbuddy_destination_sftp::_init();



// Delete sftp backups
if ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_backup' ) {
	pb_backupbuddy::verify_nonce();
	
	$delete_count = 0;
		
	// Connect to server.
	$server = $destination['address'];
	$port = '22'; // Default sFTP port.
	if ( strstr( $server, ':' ) ) { // Handle custom sFTP port.
		$server_params = explode( ':', $server );
		$server = $server_params[0];
		$port = $server_params[1];
	}
	pb_backupbuddy::status( 'details', 'Connecting to sFTP server...' );
	$sftp = new Net_SFTP( $server, $port );
	if ( ! $sftp->login( $destination['username'], $destination['password'] ) ) {
		pb_backupbuddy::status( 'error', 'Connection to sFTP server FAILED.' );
		return false;
	} else {
		pb_backupbuddy::status( 'details', 'Success connecting to sFTP server.' );
	}
	// Change to directory.
	pb_backupbuddy::status( 'details', 'Attempting to change into directory...' );
	if ( true === $sftp->chdir( $destination['path'] ) ) {
		pb_backupbuddy::status( 'details', 'Changed into directory.' );
	} else {
		pb_backupbuddy::status( 'error', 'Unable to change into specified path. Verify the path is correct with valid permissions.' );
		return false;
	}
	
	
	// loop through and delete ftp backup files
	foreach( (array)pb_backupbuddy::_POST( 'items' ) as $backup ) {
		// try to delete backup
		if ( true === $sftp->delete( $backup ) ) {
			$delete_count++;
		} else {
			pb_backupbuddy::alert( 'Unable to delete file `' . $destination['path'] . '/' . $backup . '`.' );
		}
	}
	
	
	if ( $delete_count > 0 ) {
		pb_backupbuddy::alert( sprintf( _n( 'Deleted %d file.', 'Deleted %d files.', $delete_count, 'it-l10n-backupbuddy' ), $delete_count ) );
	} else {
		pb_backupbuddy::alert( __('No backups were deleted.', 'it-l10n-backupbuddy' ) );
	}
	echo '<br>';
}




// Connect to server.
$server = $destination['address'];
$port = '22'; // Default sFTP port.
if ( strstr( $server, ':' ) ) { // Handle custom sFTP port.
	$server_params = explode( ':', $server );
	$server = $server_params[0];
	$port = $server_params[1];
}
pb_backupbuddy::status( 'details', 'Connecting to sFTP server...' );
$sftp = new Net_SFTP( $server, $port );
if ( ! $sftp->login( $destination['username'], $destination['password'] ) ) {
	pb_backupbuddy::status( 'error', 'Connection to sFTP server FAILED.' );
	return false;
} else {
	pb_backupbuddy::status( 'details', 'Success connecting to sFTP server.' );
}

pb_backupbuddy::status( 'details', 'Attempting to create path (if it does not exist)...' );
if ( true === $sftp->mkdir( $destination['path'] ) ) { // Try to make directory.
	pb_backupbuddy::status( 'details', 'Directory created.' );
} else {
	pb_backupbuddy::status( 'details', 'Directory not created.' );
}


// List files.
$files = $sftp->rawlist( $destination['path'] );
$backups = array();
$backup_list_temp = array();
foreach( $files as $filename => $file ) {
	if ( false === stristr( $filename, 'backup' ) ) { // only show backup files
		continue;
	}
	
	if ( stristr( $filename, '-db-' ) !== false ) {
		$backup_type = 'Database';
	} elseif( stristr( $filename, '-full-' ) !== false ) {
		$backup_type = 'Full';
	} else {
		$backup_type = 'Unknown';
	}
	
	$last_modified = $file['mtime'];
	
	while( isset( $backup_list_temp[$last_modified] ) ) { // Avoid collisions.
		$last_modified += 0.1;
	}
	$backup_list_temp[$last_modified] = array(
		$filename,
		pb_backupbuddy::$format->date(
			pb_backupbuddy::$format->localize_time( $file['mtime'] )
		) . '<br /><span class="description">(' .
		pb_backupbuddy::$format->time_ago( $file['mtime'] ) .
		' ago)</span>',
		pb_backupbuddy::$format->file_size( $file['size'] ),
		$backup_type
	);
}



krsort( $backup_list_temp );
$backup_list = array();
foreach( $backup_list_temp as $backup_item ) {
	$backup_list[ $backup_item[0] ] = $backup_item; //str_replace( 'db/', '', str_replace( 'full/', '', $backup_item ) );
}
unset( $backup_list_temp );



//echo '<h3>', __('Viewing', 'it-l10n-backupbuddy' ), ' `' . $destination['title'] . '` (' . $destination['type'] . ')</h3>';


// Render table listing files.
if ( count( $backup_list ) == 0 ) {
	echo '<b>';
	_e( 'You have not completed sending any backups to this sFTP destination for this site yet.', 'it-l10n-backupbuddy' );
	echo '</b>';
} else {
	pb_backupbuddy::$ui->list_table(
		$backup_list,
		array(
			'action'		=>	$urlPrefix . '&remote_path=' . htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ),
			'columns'		=>	array( 'Backup File', 'Uploaded <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first">', 'File Size', 'Type' ),
			//'hover_actions'	=>	array( 'copy' => 'Copy to Local' ),
			'hover_action_column_key'	=>	'0',
			'bulk_actions'	=>	array( 'delete_backup' => 'Delete' ),
			'css'			=>		'width: 100%;',
		)
	);
}


?>