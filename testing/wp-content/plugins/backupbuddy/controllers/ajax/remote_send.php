<?php
backupbuddy_core::verifyAjaxAccess();


/*	remote_send()
*	
*	Send backup archive to a remote destination manually. Optionally sends importbuddy.php with files.
*	Sends are scheduled to run in a cron and are passed to the cron.php remote_send() method.
*	
*	@return		null
*/


$success_output = false; // Set to true onece a leading 1 has been sent to the javascript to indicate success.
$destination_id = pb_backupbuddy::_POST( 'destination_id' );
if ( pb_backupbuddy::_POST( 'file' ) != 'importbuddy.php' ) {
	$backup_file = backupbuddy_core::getBackupDirectory() . pb_backupbuddy::_POST( 'file' );
	if ( ! file_exists( $backup_file ) ) { // Error if file to send did not exist!
		$error_message = 'Unable to find file `' . $backup_file . '` to send. File does not appear to exist. You can try again in a moment or turn on full error logging and try again to log for support.';
		pb_backupbuddy::status( 'error', $error_message );
		echo $error_message;
		die();
	}
	if ( is_dir( $backup_file ) ) { // Error if a directory is trying to be sent.
		$error_message = 'You are attempting to send a directory, `' . $backup_file . '`. Try again and verify there were no javascript errors.';
		pb_backupbuddy::status( 'error', $error_message );
		echo $error_message;
		die();
	}
} else {
	$backup_file = '';
}

// Send ImportBuddy along-side?
if ( pb_backupbuddy::_POST( 'send_importbuddy' ) == '1' ) {
	$send_importbuddy = true;
	pb_backupbuddy::status( 'details', 'Cron send to be scheduled with importbuddy sending.' );
} else {
	$send_importbuddy = false;
	pb_backupbuddy::status( 'details', 'Cron send to be scheduled WITHOUT importbuddy sending.' );
}

// Delete local copy after send completes?
if ( pb_backupbuddy::_POST( 'delete_after' ) == 'true' ) {
	$delete_after = true;
	pb_backupbuddy::status( 'details', 'Remote send set to delete after successful send.' );
} else {
	$delete_after = false;
	pb_backupbuddy::status( 'details', 'Remote send NOT set to delete after successful send.' );
}

if ( !isset( pb_backupbuddy::$options['remote_destinations'][$destination_id] ) ) {
	die( 'Error #833383: Invalid destination ID `' . htmlentities( $destination_id ) . '`.' );
}

// For Stash we will check the quota prior to initiating send.
if ( pb_backupbuddy::$options['remote_destinations'][$destination_id]['type'] == 'stash' ) {
	// Pass off to destination handler.
	require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
	$send_result = pb_backupbuddy_destinations::get_info( 'stash' ); // Used to kick the Stash destination into life.
	$stash_quota = pb_backupbuddy_destination_stash::get_quota( pb_backupbuddy::$options['remote_destinations'][$destination_id], true );
	
	if ( isset( $stash_quota['error'] ) ) {
		echo  ' Error accessing Stash account. Send aborted. Details: `' . implode( ' - ', $stash_quota['error'] ) . '`.';
		die();
	}
	
	if ( $backup_file != '' ) {
		$backup_file_size = filesize( $backup_file );
	} else {
		$backup_file_size = 50000;
	}
	if ( ( $backup_file_size + $stash_quota['quota_used'] ) > $stash_quota['quota_total'] ) {
		echo "You do not have enough Stash storage space to send this file. Please upgrade your Stash storage or delete files to make space.\n\n";
		
		echo 'Attempting to send file of size ' . pb_backupbuddy::$format->file_size( $backup_file_size ) . ' but you only have ' . $stash_quota['quota_available_nice'] . ' available. ';
		echo 'Currently using ' . $stash_quota['quota_used_nice'] . ' of ' . $stash_quota['quota_total_nice'] . ' (' . $stash_quota['quota_used_percent'] . '%).';
		die();
	} else {
		if ( isset( $stash_quota['quota_warning'] ) && ( $stash_quota['quota_warning'] != '' ) ) {
			echo '1Warning: ' . $stash_quota['quota_warning'] . "\n\n";
			$success_output = true;
		}
	}
	
} // end if Stash.

pb_backupbuddy::status( 'details', 'Scheduling cron to send to this remote destination...' );
$schedule_result = backupbuddy_core::schedule_single_event( time(), 'remote_send', array( $destination_id, $backup_file, pb_backupbuddy::_POST( 'trigger' ), $send_importbuddy, $delete_after ) );
if ( $schedule_result === FALSE ) {
	$error = 'Error scheduling file transfer. Please check your BackupBuddy error log for details. A plugin may have prevented scheduling or the database rejected it.';
	pb_backupbuddy::status( 'error', $error );
	echo $error;
} else {
	pb_backupbuddy::status( 'details', 'Cron to send to remote destination scheduled.' );
}
spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.

// SEE cron.php remote_send() for sending function that we pass to via the cron above.

if ( $success_output === false ) {
	echo 1;
}
die();

