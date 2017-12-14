<?php
backupbuddy_core::verifyAjaxAccess();


// Directory exclusions picker for settings page.
/*	download_archive()
*	
*	Handle allowing download of archive.
*	
*	@param		
*	@return		
*/


if ( is_multisite() && !current_user_can( 'manage_network' ) ) { // If a Network and NOT the superadmin must make sure they can only download the specific subsite backups for security purposes.
	
	// Only allow downloads of their own backups.
	if ( !strstr( pb_backupbuddy::_GET( 'backupbuddy_backup' ), backupbuddy_core::backup_prefix() ) ) {
		die( 'Access Denied. You may only download backups specific to your Multisite Subsite. Only Network Admins may download backups for another subsite in the network.' );
	}
}

// Make sure file exists we are trying to get.
if ( !file_exists( backupbuddy_core::getBackupDirectory() . pb_backupbuddy::_GET( 'backupbuddy_backup' ) ) ) { // Does not exist.
	die( 'Error #548957857584784332. The requested backup file does not exist. It may have already been deleted.' );
}

$abspath = str_replace( '\\', '/', ABSPATH ); // Change slashes to handle Windows as we store backup_directory with Linux-style slashes even on Windows.
$backup_dir = str_replace( '\\', '/', backupbuddy_core::getBackupDirectory() );

// Make sure file to download is in a publicly accessible location (beneath WP web root technically).
if ( FALSE === stristr( $backup_dir, $abspath ) ) {
	die( 'Error #5432532. You cannot download backups stored outside of the WordPress web root. Please use FTP or other means.' );
}

// Made it this far so download dir is within this WP install.
$sitepath = str_replace( $abspath, '', $backup_dir );
$download_url = rtrim( site_url(), '/\\' ) . '/' . trim( $sitepath, '/\\' ) . '/' . pb_backupbuddy::_GET( 'backupbuddy_backup' );

if ( pb_backupbuddy::$options['lock_archives_directory'] == '1' ) { // High security mode.
	
	if ( file_exists( backupbuddy_core::getBackupDirectory() . '.htaccess' ) ) {
		$unlink_status = @unlink( backupbuddy_core::getBackupDirectory() . '.htaccess' );
		if ( $unlink_status === false ) {
			die( 'Error #844594. Unable to temporarily remove .htaccess security protection on archives directory to allow downloading. Please verify permissions of the BackupBuddy archives directory or manually download via FTP.' );
		}
	}
	
	header( 'Location: ' . $download_url );
	ob_clean();
	flush();
	sleep( 8 ); // Wait 8 seconds before creating security file.
	
	$htaccess_creation_status = @file_put_contents( backupbuddy_core::getBackupDirectory() . '.htaccess', 'deny from all' );
	if ( $htaccess_creation_status === false ) {
		die( 'Error #344894545. Security Warning! Unable to create security file (.htaccess) in backups archive directory. This file prevents unauthorized downloading of backups should someone be able to guess the backup location and filenames. This is unlikely but for best security should be in place. Please verify permissions on the backups directory.' );
	}
	
} else { // Normal mode.
	header( 'Location: ' . $download_url );
}



die();
