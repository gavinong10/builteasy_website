<?php
// @author Dustin Bolton, July 2013.
// Incoming variables: $destination


if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

//pb_backupbuddy::$ui->title( 'Dropbox destination "' . htmlentities( $destination['title'] ) . '"' );



// Copy remote file down to local.
if ( '' != pb_backupbuddy::_GET( 'cpy' ) ) {
	// Copy dropbox backups to the local backup files
	pb_backupbuddy::alert( 'The remote file is now being copied to your local backups. If the backup gets marked as bad during copying, please wait a bit then click the `Refresh` icon to rescan after the transfer is complete.' );
	echo '<br>';
	pb_backupbuddy::status( 'details',  'Scheduling Cron for creating Dropbox copy.' );
	backupbuddy_core::schedule_single_event( time(), 'process_destination_copy', array( $destination, pb_backupbuddy::_GET( 'cpy' ) ) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
}



// Delete selected dropbox backup(s) from form submission.
if ( 'delete_backup' == pb_backupbuddy::_POST( 'bulk_action' ) ) {
	pb_backupbuddy::verify_nonce();
	if ( is_array( pb_backupbuddy::_POST( 'items' ) ) ) {
		
		if ( true === ( $result = pb_backupbuddy_destinations::delete( $destination, pb_backupbuddy::_POST( 'items' ) ) ) ) {
			pb_backupbuddy::alert( __( 'Selected file(s) deleted.', 'it-l10n-backupbuddy' ) );
		} else {
			pb_backupbuddy::alert( __( 'Unable to delete one or more files. Details: ', 'it-l10n-backupbuddy' ) . $result );
		}
		echo '<br>';
	}
}



$files_result = pb_backupbuddy_destinations::listFiles( $destination );

$backup_files = array();
foreach( (array)$files_result['contents'] as $file ) { // Loop through files looking for backups.
	
	if ( $file['is_dir'] == '1' ) { // Do NOT display subdirectories.
		continue;
	}
	
	$filename = str_ireplace( $files_result['path'] . '/', '', $file['path'] ); // Remove path from filename.
	if ( isset( $file['client_mtime'] ) ) {
		$last_modified = strtotime( $file['client_mtime'] );
		//$last_modified = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $last_modified ) ) . '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $last_modified ) . ' ago)</span>';
	} else {
		$last_modified = '<i>' . __( 'Unknown', 'it-l10n-backupbuddy' ) . '</i>';
	}
	
	$size = $file['bytes'];
	
	if ( false !== stristr( $filename, '-db-' ) ) {
		$backup_type ='Database';
	} elseif ( false !== stristr( $filename, '-full-' ) ) {
		$backup_type ='Full';
	} elseif ( false !== stristr( $filename, '-files-' ) ) {
		$backup_type ='Files';
	} elseif ( false !== stristr( $filename, 'importbuddy.php' ) ) {
		$backup_type = 'ImportBuddy Tool';
	} else {
		$backup_type = 'Unknown';
	}
	
	// Generate array of table rows.
	$backup_files[$filename] = array(
		$filename,
		$last_modified,
		pb_backupbuddy::$format->file_size( $size ),
		$backup_type,
		'file_timestamp' => $last_modified,
	);
	
}


// For sorting by array item value.
function pb_backupbuddy_aasort(&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
	    $sorter[$ii]=$va[$key];
	}
	asort($sorter);
	foreach ($sorter as $ii => $va) {
	    $ret[$ii]=$array[$ii];
	}
	$array=$ret;
}

// Fix ordering.
pb_backupbuddy_aasort( $backup_files, 'file_timestamp' ); // Sort by multidimensional array with key start_timestamp.
$backup_files = array_reverse( $backup_files ); // Reverse array order to show newest first.

// Format timestamp.
foreach( $backup_files as &$backup_file ) {
	$backup_file[1] = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup_file[1] ) ) . '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $backup_file[1] ) . ' ago)</span>';
}

$urlPrefix = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) );

// Render table listing files.
if ( count( $backup_files ) == 0 ) {
	echo '<b>' . __( 'You have not completed sending any backups to this destination yet.', 'it-l10n-backupbuddy' ) . '</b>';
} else {
	pb_backupbuddy::$ui->list_table(
		$backup_files,
		array(
			'action'					=>	$urlPrefix,
			'columns'					=>	array(
												'Backup File',
												'Uploaded <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first">',
												'File Size',
												'Type'
											),
			'hover_actions'				=>	array(
												$urlPrefix . '&cpy=' => 'Copy to Local',
											),
			'hover_action_column_key'	=>	'0',
			'bulk_actions'				=>	array(
												'delete_backup' => 'Delete'
											),
			'css'						=>	'width: 100%;',
		)
	);
}


return;
