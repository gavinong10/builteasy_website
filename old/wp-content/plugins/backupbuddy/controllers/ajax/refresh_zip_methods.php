<?php
backupbuddy_core::verifyAjaxAccess();


// Server info page available zip methods update.

/* refresh_zip_methods()
*
* Server Info page refreshing available zip methods. Useful since these are normally cached.
*
*/

// Make sure the legacy method transient is gone
delete_transient( 'pb_backupbuddy_avail_zip_methods_classic' );

if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {

	// We don't have an instance of zipbuddy so make sure we can create one
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	
	// Find out the transient name(s) and delete them
	$transients = pluginbuddy_zipbuddy::get_transient_names_static();
	foreach ( $transients as $transient ) {
	
		delete_transient( $transient );
		
	}
	
	// Instantiating a class object will renew the deleted method transient
	pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( ABSPATH );
	
} else {

	// We have an instance of zipbuddy so we can use it
	// Find out the transient name(s) and delete them
	$transients = pluginbuddy_zipbuddy::get_transient_names_static();
	foreach ( $transients as $transient ) {
	
		delete_transient( $transient );
		
	}
	
	// Just call the refresh function
	pb_backupbuddy::$classes['zipbuddy']->refresh_zip_methods();
	
}

// Now simply provide the list of methods
echo implode( ', ', pb_backupbuddy::$classes['zipbuddy']->_zip_methods );

die();