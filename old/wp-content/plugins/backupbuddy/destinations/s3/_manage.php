<?php
// @author Dustin Bolton 2013.
// Incoming variables: $destination

if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
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
require_once( pb_backupbuddy::plugin_path() . '/destinations/s3/init.php' );
require_once( dirname( dirname( __FILE__ ) ) . '/_s3lib/aws-sdk/sdk.class.php' );


// Settings.
if ( isset( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')] ) ) {
	$settings = &pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')];
}
$settings = array_merge( pb_backupbuddy_destination_s3::$default_settings, $settings );
$settings['bucket'] = strtolower( $settings['bucket'] ); // Buckets must be lowercase.

$remote_path = pb_backupbuddy_destination_s3::get_remote_path( $settings['directory'] );


// Welcome text.
$manage_data = pb_backupbuddy_destination_s3::get_credentials( $settings );


// Connect to S3.
$s3 = new AmazonS3( $manage_data );    // the key, secret, token
if ( $settings['ssl'] == '0' ) {
	@$s3->disable_ssl(true);
}

// The bucket must be in existence and we must get it's region to be able to proceed
$region = '';
pb_backupbuddy::status( 'details', 'Getting region for bucket: `' . $settings['bucket'] . "`." );
$response = pb_backupbuddy_destination_s3::get_bucket_region( $s3, $settings['bucket'] );
if( !$response->isOK() ) {

	$this_error = 'Bucket region could not be determined for management operation. Message details: `' . (string)$response->body->Message . '`.';
	pb_backupbuddy::status( 'error' , $this_error );
	
} else {

	pb_backupbuddy::status( 'details', 'Bucket exists in region: ' .  (($response->body ==="") ? 'us-east-1' : $response->body ) );
	$region = $response->body; // Must leave as is for actual operational usage
	
}

// Set region context for later operations - will be s3.amazonaws.com or s3-<region>.amazonaws.com
$s3->set_region( 's3' . ( ($region == "" ) ? "" : '-' . $region ) . '.amazonaws.com' );

// Handle deletion.
if ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_backup' ) {
	pb_backupbuddy::verify_nonce();
	$deleted_files = array();
	foreach( (array)pb_backupbuddy::_POST( 'items' ) as $item ) {
		
		$response = $s3->delete_object( $manage_data['bucket'], $remote_path . $item );
		if ( $response->isOK() ) {
			$deleted_files[] = $item;
		} else {
			pb_backupbuddy::alert( 'Error: Unable to delete `' . $item . '`. Verify permissions.' );
		}
		
		
	}
	
	if ( count( $deleted_files ) > 0 ) {
		pb_backupbuddy::alert( 'Deleted ' . implode( ', ', $deleted_files ) . '.' );
	}
	echo '<br>';
}


// Handle copying files to local
if ( pb_backupbuddy::_GET( 'cpy_file' ) != '' ) {
	pb_backupbuddy::alert( 'The remote file is now being copied to your local backups. If the backup gets marked as bad during copying, please wait a bit then click the `Refresh` icon to rescan after the transfer is complete.' );
	echo '<br>';
	pb_backupbuddy::status( 'details',  'Scheduling Cron for creating S3 copy.' );
	backupbuddy_core::schedule_single_event( time(), 'process_remote_copy', array( 's3', pb_backupbuddy::_GET( 'cpy_file' ), $settings ) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
}


// Handle download link
if ( pb_backupbuddy::_GET( 'downloadlink_file' ) != '' ) {
	$link = $s3->get_object( $manage_data['bucket'], $remote_path . pb_backupbuddy::_GET( 'downloadlink_file' ), array('preauth'=>time()+3600));
	pb_backupbuddy::alert( 'You may download this backup (' . pb_backupbuddy::_GET( 'downloadlink_file' ) . ') with <a href="' . $link . '">this link</a>. The link is valid for one hour.' );
	echo '<br>';
}

$prefix = backupbuddy_core::backup_prefix();

// Get file listing.
$response = $s3->list_objects(
	$manage_data['bucket'],
	array(
		'prefix' => $remote_path
	)
); // list all the files in the subscriber account

// Get list of files.
$backup_list_temp = array();
foreach( $response->body->Contents as $object ) {
	
	$file = str_ireplace( $remote_path, '', $object->Key );
	if ( FALSE !== stristr( $file, '/' ) ) { // Do NOT display any files within a deeper subdirectory.
		continue;
	}
	if ( ( ! preg_match( pb_backupbuddy_destination_s3::BACKUP_FILENAME_PATTERN, $file ) ) && ( 'importbuddy.php' !== $file ) ) { // Do not display any files that do not appear to be a BackupBuddy backup file (except importbuddy.php).
		continue;
	}
	/*
	Unsure whether to include this here or not?
	if ( FALSE === ( strpos( $file, 'backup-' . $prefix . '-' ) ) ) { // Not a backup for THIS site. Skip.
		continue;
	}
	*/
	
	$last_modified = strtotime( $object->LastModified );
	$size = (double) $object->Size;
	$backup_type = backupbuddy_core::getBackupTypeFromFile( $file );
	
	// Generate array of table rows.
	while( isset( $backup_list_temp[$last_modified] ) ) { // Avoid collisions.
		$last_modified += 0.1;
	}
	$backup_list_temp[$last_modified] = array(
		$file,
		pb_backupbuddy::$format->date(
			pb_backupbuddy::$format->localize_time( $last_modified )
		) . '<br /><span class="description">(' .
		pb_backupbuddy::$format->time_ago( $last_modified ) .
		' ago)</span>',
		pb_backupbuddy::$format->file_size( $size ),
		backupbuddy_core::pretty_backup_type( $backup_type )
	);

}


krsort( $backup_list_temp );
$backup_list = array();
foreach( $backup_list_temp as $backup_item ) {
	$backup_list[ $backup_item[0] ] = $backup_item;
}
unset( $backup_list_temp );

$urlPrefix = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) );

// Render table listing files.
if ( count( $backup_list ) == 0 ) {
	echo '<b>';
	_e( 'You have not completed sending any backups to this S3 destination for this site yet.', 'it-l10n-backupbuddy' );
	echo '</b>';
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
