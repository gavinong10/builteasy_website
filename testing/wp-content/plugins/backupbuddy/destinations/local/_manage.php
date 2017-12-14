<?php
// @author Dustin Bolton 2012.
if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

// Set reference to destination.
if ( isset( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')] ) ) {
	$destination = &pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')];
}


// Welcome text.
//pb_backupbuddy::$ui->title( 'Local Destination `' . $destination['title'] . '`' );
echo 'Listing backups in local directory `' . $destination['path'] . '`...<br><br>';


// Handle deletion.
if ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_backup' ) {
	pb_backupbuddy::verify_nonce();
	$deleted_files = array();
	foreach( (array)pb_backupbuddy::_POST( 'items' ) as $item ) {
		@unlink( $destination['path'] . $item );
		if ( file_exists( $destination['path'] . $item ) ) {
			pb_backupbuddy::alert( 'Error: Unable to delete `' . $item . '`. Verify permissions.' );
		} else {
			$deleted_files[] = $item;
		}
	}
	
	if ( count( $deleted_files ) > 0 ) {
		pb_backupbuddy::alert( 'Deleted ' . implode( ', ', $deleted_files ) . '.' );
	}
	echo '<br>';
}


// Find backups in directory.
$backups = glob( $destination['path'] . '*.zip' );
if ( !is_array( $backups ) ) {
	$backups = array();
}


// Generate array of table rows.
$backup_list = array();
foreach( $backups as $backup ) {
	$backup_list[basename($backup)] = array(
						basename( $backup ),
						pb_backupbuddy::$format->date(
							pb_backupbuddy::$format->localize_time( filemtime( $backup ) )
						) . '<br /><span class="description">(' .
						pb_backupbuddy::$format->time_ago( filemtime( $backup ) ) .
						' ago)</span>',
						pb_backupbuddy::$format->file_size( filesize( $backup ) ),
					);
}

$urlPrefix = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) );

// Render table.
pb_backupbuddy::$ui->list_table(
	$backup_list,
	array(
		'action'		=>	$urlPrefix,
		'columns'		=>	array( 'Backup File', 'Last Modified', 'File Size' ),
		//'hover_actions'	=>	array( 'copy' => 'Copy to Local' ),
		'hover_action_column_key'	=>	'0',
		'bulk_actions'	=>	array( 'delete_backup' => 'Delete' ),
		'css'			=>		'width: 100%;',
	)
);

