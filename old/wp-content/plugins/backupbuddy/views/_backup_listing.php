<?php
// Incoming variables: $backups generated via core.php backups_list() function.


// $listing_mode should be either:  default,  migrate

$hover_actions = array();

// If download URL is within site root then allow downloading via web.
$backup_directory = backupbuddy_core::getBackupDirectory(); // Normalize for Windows paths.
$backup_directory = str_replace( '\\', '/', $backup_directory );
$backup_directory = rtrim( $backup_directory, '/\\' ) . '/'; // Enforce single trailing slash.
if ( ( $listing_mode != 'restore_files' ) && ( FALSE !== stristr( $backup_directory, ABSPATH ) ) ) {
	$hover_actions[pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup='] = '<span class="dashicons dashicons-download"></span> ' . __( 'Download', 'it-l10n-backupbuddy' );
}


if ( $listing_mode == 'restore_files' ) {
	$hover_actions[pb_backupbuddy::ajax_url( 'download_archive' ) . '&zip_viewer='] = '<span class="dashicons dashicons-visibility"></span> ' . __( 'Browse & Restore Files', 'it-l10n-backupbuddy' );
	$hover_actions['note'] = __( 'Note', 'it-l10n-backupbuddy' );
	$bulk_actions = array();
}


if ( $listing_mode == 'default' ) {
	
	$hover_actions['send'] = '<span class="dashicons dashicons-migrate"></span> ' . __( 'Send', 'it-l10n-backupbuddy' );
	$hover_actions['zip_viewer'] = '<span class="dashicons dashicons-visibility"></span>&nbsp; ' . __( 'Browse & Restore Files', 'it-l10n-backupbuddy' );
	$hover_actions['note'] = '<span class="dashicons dashicons-edit"></span> ' . __( 'Note', 'it-l10n-backupbuddy' );
	$hover_actions['hash'] = '<span class="dashicons dashicons-chart-line"></span> ' . __( 'Checksum', 'it-l10n-backupbuddy' );
	$bulk_actions = array( 'delete_backup' => __( 'Delete', 'it-l10n-backupbuddy' ) );

}


if ( $listing_mode == 'migrate' ) {
	$hover_actions['migrate'] = '<span class="dashicons dashicons-share-alt2"></span> ' . __( 'Migrate', 'it-l10n-backupbuddy' );
	$hover_actions[pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup='] = '<span class="dashicons dashicons-download"></span> ' . __( 'Download', 'it-l10n-backupbuddy' );
	$hover_actions['note'] = '<span class="dashicons dashicons-edit"></span> ' . __( 'Note', 'it-l10n-backupbuddy' );
	$bulk_actions = array();
	
	foreach( $backups as $backup_id => $backup ) {
		if ( $backup[1] == 'Database' ) {
			unset( $backups[$backup_id] );
		}
	}
	
}


if ( $listing_mode == 'restore_migrate' ) {
	$hover_actions[pb_backupbuddy::ajax_url( 'download_archive' ) . '&backupbuddy_backup='] = '<span class="dashicons dashicons-download"></span> ' . __( 'Download', 'it-l10n-backupbuddy' );
	$hover_actions['send'] = '<span class="dashicons dashicons-migrate"></span> ' . __( 'Send', 'it-l10n-backupbuddy' );
	$hover_actions['page=pb_backupbuddy_backup&zip_viewer'] = '<span class="dashicons dashicons-visibility"></span>&nbsp; ' . __( 'Browse & Restore Files', 'it-l10n-backupbuddy' );
	$hover_actions['rollback'] = '<span class="dashicons dashicons-backup"></span> ' . __( 'Database Rollback', 'it-l10n-backupbuddy' );
	$hover_actions['migrate'] = '<span class="dashicons dashicons-share-alt2"></span> ' . __( 'Migrate', 'it-l10n-backupbuddy' );
	$hover_actions['note'] = '<span class="dashicons dashicons-edit"></span> ' . __( 'Note', 'it-l10n-backupbuddy' );
	$bulk_actions = array();
	
	/*
	foreach( $backups as $backup_id => $backup ) {
		if ( $backup[1] == 'Database' ) {
			unset( $backups[$backup_id] );
		}
	}
	*/
	
}



if ( count( $backups ) == 0 ) {
	_e( 'No backups have been created yet.', 'it-l10n-backupbuddy' );
	echo '<br>';
} else {
	
	$columns = array(
		__('Local Backups', 'it-l10n-backupbuddy' ) . ' <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first">',
		__('Type', 'it-l10n-backupbuddy' ) . ' | ' . __('Profile', 'it-l10n-backupbuddy' ),
		__('File Size', 'it-l10n-backupbuddy' ),
		__('Status', 'it-l10n-backupbuddy' ) . pb_backupbuddy::tip( __('Backups are checked to verify that they are valid BackupBuddy backups and contain all of the key backup components needed to restore. Backups may display as invalid until they are completed. Click the refresh icon to re-verify the archive.', 'it-l10n-backupbuddy' ), '', false ),
	);
	
	
	// Remove some columns for migration mode.
	/*
	if ( $listing_mode != 'default' ) {
		
		foreach( $backups as &$backup ) {
			unset( $backup[1] ); // Remove backup type (only full shows for migration).
			$backup = array_values( $backup );
		}
		$backups = array_values( $backups );
		
		unset( $columns[1] );
		$columns = array_values( $columns );
		
		
	}
	*/
	
	
	pb_backupbuddy::$ui->list_table(
		$backups,
		array(
			'action'		=>	pb_backupbuddy::page_url(),
			'columns'		=>	$columns,
			'hover_actions'	=>	$hover_actions,
			'hover_action_column_key'	=>	'0',
			'bulk_actions'	=>	$bulk_actions,
			'css'			=>		'width: 100%;',
		)
	);
}
?>