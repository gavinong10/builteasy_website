<?php
// @author Dustin Bolton 2015.
// Incoming variables: $destination
if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

// Welcome text.
if ( 'true' != pb_backupbuddy::_GET( 'listAll' ) ) {
	$listAll = '<a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&listAll=true" style="text-decoration: none;">List all site\'s files</a>';
} else {
	$listAll = '<a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&listAll=false" style="text-decoration: none;">Only list this site\'s files</a>';
}
?>


<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_hoveraction_copy' ).click( function() {
			var backup_file = jQuery(this).attr( 'rel' );
			var backup_url = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=<?php echo pb_backupbuddy::_GET( 'destination_id' ); ?>&remote_path=<?php echo htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ); ?>&cpy_file=' + backup_file;
			
			window.location.href = backup_url;
			
			return false;
		} );
		
		jQuery( '.pb_backupbuddy_hoveraction_download_link' ).click( function() {
			var backup_file = jQuery(this).attr( 'rel' );
			var backup_url = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=<?php echo pb_backupbuddy::_GET( 'destination_id' ); ?>&remote_path=<?php echo htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ); ?>&downloadlink_file=' + backup_file;
			
			window.location.href = backup_url;
			
			return false;
		} );
		
	});
</script>


<?php
// Load required files.
require_once( pb_backupbuddy::plugin_path() . '/destinations/s32/init.php' );


// Settings.
if ( isset( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')] ) ) {
	$destinationID = pb_backupbuddy::_GET('destination_id');
	if ( ! isset( pb_backupbuddy::$options['remote_destinations'][ $destinationID ] ) ) {
		die( 'Error #9828332: Destination not found.' );
	}
	$settings = &pb_backupbuddy::$options['remote_destinations'][ $destinationID ];
	$settings = pb_backupbuddy_destination_stash2::_formatSettings( $settings );
}


// Handle deletion.
if ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_backup' ) {
	pb_backupbuddy::verify_nonce();
	$deleteFiles = array();
	foreach( (array)pb_backupbuddy::_POST( 'items' ) as $file ) {
		$file = base64_decode( $file );
		
		$startPos = pb_backupbuddy_destination_stash2::strrpos_count( $file, '/', 2 ) + 1; // next to last slash.
		$file = substr( $file, $startPos );
		if ( FALSE !== strstr( $file, '?' ) ) {
			$file = substr( $file, 0, strpos( $file, '?' ) );
		}
		$deleteFiles[] = $file;
	}
	$response = pb_backupbuddy_destination_stash2::deleteFiles( $settings, $deleteFiles );
	
	if ( true === $response ) {
		pb_backupbuddy::alert( 'Deleted ' . implode( ', ', $deleteFiles ) . '.' );
	} else {
		pb_backupbuddy::alert( 'Failed to delete one or more files. Details: `' . $response . '`.' );
	}
	echo '<br>';
} // end deletion.


// Handle copying files to local
if ( pb_backupbuddy::_GET( 'cpy_file' ) != '' ) {
	pb_backupbuddy::alert( 'The remote file is now being copied to your local backups. If the backup gets marked as bad during copying, please wait a bit then click the `Refresh` icon to rescan after the transfer is complete.' );
	echo '<br>';
	pb_backupbuddy::status( 'details',  'Scheduling Cron for creating Stash copy.' );
	
	$file = base64_decode( pb_backupbuddy::_GET( 'cpy_file' ) );
	backupbuddy_core::schedule_single_event( time(), 'process_remote_copy', array( 'stash2', $file, $settings ) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
} // end copying to local.


// Handle download link
if ( pb_backupbuddy::_GET( 'downloadlink_file' ) != '' ) {
	pb_backupbuddy::alert( 'Download the selected backup file with <a href="' . esc_url( base64_decode( pb_backupbuddy::_GET( 'downloadlink_file' ) ) ) . '">this link</a>. The link is valid for one hour.' );
	echo '<br>';
} // end download link.


// Get list of files for this site.
if ( 'true' != pb_backupbuddy::_GET( 'listAll' ) ) { // NOT listing all sites.
	$remotePath = 'backup-' . backupbuddy_core::backup_prefix();
} elseif ( '1' == $settings['manage_all_files'] ) { // Listing all sites.
	$remotePath = '';
}
$files = pb_backupbuddy_destination_stash2::listFiles( $settings, $remotePath );
if ( ! is_array( $files ) ) {
	pb_backupbuddy::alert( 'Error #892329: ' . $files );
	die();
}


$backup_list_temp = array();
foreach( (array)$files as $file ) {
	/*
	echo '<br><pre>';
	print_r( $file );
	echo '</pre>';
	*/
	
	/*
	if ( ( ! preg_match( pb_backupbuddy_destination_s32::BACKUP_FILENAME_PATTERN, $file['basename'] ) ) && ( 'importbuddy.php' !== $file ) ) { // Do not display any files that do not appear to be a BackupBuddy backup file (except importbuddy.php).
		continue;
	}
	*/
	
	if ( ( '' != $remotePath ) && ( ! backupbuddy_core::startsWith( basename( $file['filename'] ), $remotePath ) ) ) { // Only show backups for this site unless set to show all.
		continue;
	}
	
	$last_modified = $file['uploaded_timestamp'];
	$size = (double) $file['size'];
	$backup_type = backupbuddy_core::getBackupTypeFromFile( $file['filename'] );
	
	// Generate array of table rows.
	while( isset( $backup_list_temp[$last_modified] ) ) { // Avoid collisions.
		$last_modified += 0.1;
	}
	$backup_list_temp[$last_modified] = array(
		array( base64_encode( $file['url'] ), '<span title="' . $file['filename'] . '">' . basename( $file['filename'] ) . '</span>' ),
		pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $last_modified ) ) . '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $last_modified ) . ' ago)</span>',
		pb_backupbuddy::$format->file_size( $size ),
		backupbuddy_core::pretty_backup_type( $backup_type ),
	);

}


krsort( $backup_list_temp );
$backup_list = array();
foreach( $backup_list_temp as $backup_item ) {
	$backup_list[ $backup_item[0][0] ] = $backup_item;
}
unset( $backup_list_temp );
$urlPrefix = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) );

$quota = pb_backupbuddy_destination_stash2::get_quota( $settings );
echo pb_backupbuddy_destination_stash2::get_quota_bar( $quota, $settings, true );


// Render table listing files.
if ( count( $backup_list ) == 0 ) {
	echo '<center><br><b>';
	_e( 'You have not completed sending any backups to this destination for this site yet.', 'it-l10n-backupbuddy' );
	echo '</b></center>';
} else {
	pb_backupbuddy::$ui->list_table(
		$backup_list,
		array(
			'action'		=>	pb_backupbuddy::ajax_url( 'remoteClient' ) . '&function=remoteClient&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&remote_path=' . htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ),
			'columns'		=>	array( 'Backup File', 'Uploaded <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first">', 'File Size', 'Type' ),
			'hover_actions'	=>	array( $urlPrefix . '&cpy_file=' => 'Copy to Local', $urlPrefix . '&downloadlink_file=' => 'Get download link' ),
			'hover_action_column_key'	=>	'0',
			'bulk_actions'	=>	array( 'delete_backup' => 'Delete' ),
			'css'			=>		'width: 100%;',
		)
	);
}

// Display troubleshooting subscriber key.
echo '<br style="clear: both;">';



return;
