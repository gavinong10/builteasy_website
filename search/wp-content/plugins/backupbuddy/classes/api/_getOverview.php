<?php
return array(
	'backupbuddyVersion'		=> pb_backupbuddy::settings( 'version' ),
	'localTime'					=> time(),
	'lastBackupStart'			=> pb_backupbuddy::$options['last_backup_start'],
	'lastBackupSerial'			=> pb_backupbuddy::$options['last_backup_serial'],
	'lastBackupStats'			=> pb_backupbuddy::$options['last_backup_stats'],
	'editsSinceLastBackup'		=> pb_backupbuddy::$options['edits_since_last'],
	'scheduleCount'				=> count( pb_backupbuddy::$options['schedules'] ),
	'profileCount'				=> count( pb_backupbuddy::$options['profiles'] ),
	'destinationCount'			=> count( pb_backupbuddy::$options['remote_destinations'] ),
	'gmtOffset'					=> get_option( 'gmt_offset' ),
	'php'						=> array(
									'upload_max_filesize' => @ini_get( 'upload_max_filesize' ),
									'max_execution_time' => @ini_get( 'max_execution_time' ),
									),
	'notifications'				=> array(), // Array of string notification messages.
);