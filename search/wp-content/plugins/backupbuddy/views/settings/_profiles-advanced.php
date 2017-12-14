<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }


/*
IMPORTANT INCOMING VARIABLES (expected to be set before this file is loaded):
$profile	Index number of profile.
*/
if ( isset( pb_backupbuddy::$options['profiles'][$profile] ) ) {
	$profile_id = $profile;
	$profile_array = &pb_backupbuddy::$options['profiles'][$profile];
	$profile_array = array_merge( pb_backupbuddy::settings( 'profile_defaults' ), $profile_array );
} else {
	die( 'Error #565676756. Invalid profile ID index.' );
}


$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_advanced',
	'title'		=>		__( 'Advanced', 'it-l10n-backupbuddy' ),
) );

if ( $profile_array['type'] != 'defaults' ) {
	$settings_form->add_setting( array(
		'type'		=>		'radio',
		'name'		=>		'profiles#' . $profile_id . '#skip_database_dump',
		'options'	=>		array( '-1' => 'Use global default', '1' => 'Skip', '0' => 'Do not skip' ),
		'title'		=>		__('Skip database dump on backup', 'it-l10n-backupbuddy' ),
		'tip'		=>		__('[Default: disabled] - (WARNING: This prevents BackupBuddy from backing up the database during any kind of backup. This is for troubleshooting / advanced usage only to work around being unable to backup the database.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		//'after'		=>		'<br><span class="description"> ' . __('Use with caution.', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'required',
		'orientation' =>	'vertical',
	) );


/*
	$settings_form->add_setting( array(
		'type'		=>		'radio',
		'name'		=>		'profiles#' . $profile_id . '#compression',
		'options'	=>		array( '-1' => 'Use global default', '0' => 'Disable compression', '1' => 'Enable compression' ),
		'title'		=>		__( 'Enable zip compression', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Default: enabled] - ZIP compression decreases file sizes of stored backups. If you are encountering timeouts due to the script running too long, disabling compression may allow the process to complete faster.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		'after'		=>		'<br><span class="description"> ' . __('Disable for large sites causing backups to not complete.', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'required',
	) );
*/


	$settings_form->add_setting( array(
		'type'		=>		'radio',
		'name'		=>		'profiles#' . $profile_id . '#integrity_check',
		'options'	=>		array( '-1' => 'Use global default', '0' => 'Disable check', '1' => 'Enable check' ),
		'title'		=>		__('Perform integrity check on backup files', 'it-l10n-backupbuddy' ),
		'tip'		=>		__('[Default: enabled] - By default each backup file is checked for integrity and completion the first time it is viewed on the Backup page.  On some server configurations this may cause memory problems as the integrity checking process is intensive.  If you are experiencing out of memory errors on the Backup file listing, you can uncheck this to disable this feature.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		//'after'		=>		'<br><span class="description"> ' . __( 'Disable if unable to view backup listing.', 'it-l10n-backupbuddy' ) . '</span>',
		'rules'		=>		'required',
		'orientation' =>	'vertical',
	) );
}