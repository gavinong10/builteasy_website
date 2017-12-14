<?php


if ( pb_backupbuddy::_GET( 'backupbuddy_backup' ) == '' ) {
	
	pb_backupbuddy::$ui->title( 'Multisite: Export Site (EXPERIMENTAL)' );
	
	$view_data['backups'] = backupbuddy_core::backups_list( 'default', true ); // Second param true makes subsite mode only. Only lists backups for this subsite.
	
	// Load view.
	pb_backupbuddy::load_view( 'multisite_export', $view_data );
	
} // End if.


// Handle MS export through the normal backup mechanism.
$export_only = true;
require_once( 'backup.php' );


?>
