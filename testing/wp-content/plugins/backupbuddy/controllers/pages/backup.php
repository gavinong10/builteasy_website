<?php


// Multisite Export. This file loaded from multisite_export.php.
if ( isset( $export_only ) && ( $export_only === true ) ) {
	if ( pb_backupbuddy::_GET( 'backupbuddy_backup' ) == '' ) {
		// Do nothing.
	} elseif ( pb_backupbuddy::_GET( 'backupbuddy_backup' ) == 'export' ) {
		require_once( '_backup-perform.php' );
	} else {
		die( '{Unknown backup type.}' );
	}
	
	return;
}



if ( pb_backupbuddy::_GET( 'custom' ) != '' ) { // Custom page.
	
	if ( pb_backupbuddy::_GET( 'custom' ) == 'remoteclient' ) {
		//require_once( '_remote_client.php' );
		die( 'Fatal Error #847387344: Obselete URL. Use remoteClient AJAX URL.' );
	} else {
		die( 'Unknown custom page. Error #4385489545.' );
	}
	
} else { // Normal backup page.
	
	if ( pb_backupbuddy::_GET( 'zip_viewer' ) != '' ) {
		require_once( '_zip_viewer.php' );
	} elseif ( pb_backupbuddy::_GET( 'backupbuddy_backup' ) == '' ) {
		require_once( '_backup-home.php' );
	} else {
		require_once( '_backup-perform.php' );
	}

}
