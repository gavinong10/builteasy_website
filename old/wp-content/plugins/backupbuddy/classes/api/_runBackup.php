<?php
if ( '' == $backupMode ) {
	$backupMode = pb_backupbuddy::$options['backup_mode']; // Use user-defined setting.
}
if ( ! isset( $triggerTitle ) || ( '' == $triggerTitle ) ) {
	$triggerTitle = 'manual';
}

if ( is_array( $generic_type_or_profile_id_or_array ) ) { // is a profile array
	$profileArray = $generic_type_or_profile_id_or_array;
} else {
	$profile = $generic_type_or_profile_id_or_array;
	if ( 'db' == $profile ) { // db profile is always index 1.
		$profile = '1';
	} elseif ( 'full' == $profile ) { // full profile is always index 2.
		$profile = '2';
	}

	if ( is_numeric( $profile ) ) {
		if ( isset( pb_backupbuddy::$options['profiles'][ $profile ] ) ) {
			$profileArray = pb_backupbuddy::$options['profiles'][ $profile ];
		} else {
			return 'Error #2332904: Invalid profile ID `' . htmlentities( $profile ) . '`. Profile with this number was not found. Try deactivating then reactivating the plugin. If this fails please reset the plugin Settings back to Defaults from the Settings page.';
		}
	} else {
		return 'Error #85489548955. You cannot refresh this page to re-run it to prevent accidents. You will need to go back and try again. (Invalid profile ID not numeric: `' . htmlentities( $profile ) . '`).';
	}
	
}



$profileArray['backup_mode'] = $backupMode; // Force modern mode when running under API. 1=classic (single page load), 2=modern (cron)

if ( '' == $backupSerial ) {
	$backupSerial = pb_backupbuddy::random_string( 10 );
}


require_once( pb_backupbuddy::plugin_path() . '/classes/backup.php' );
$newBackup = new pb_backupbuddy_backup();

// Run the backup!
if ( $newBackup->start_backup_process(
	$profileArray,											// Profile array.
	$triggerTitle,											// Backup trigger. manual, scheduled
	array(),												// pre-backup array of steps.
	array(),												// post-backup array of steps.
	$triggerTitle,											// friendly title of schedule that ran this (if applicable).
	$backupSerial,											// if passed then this serial is used for the backup insteasd of generating one.
	array()													// Multisite export only: array of plugins to export.
  ) !== true ) {
	return 'Error #435832: Backup failed. See BackupBuddy log for details.';
}

return true;