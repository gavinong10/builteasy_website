<?php
backupbuddy_core::verifyAjaxAccess();


// Used for setting a note on a backup archive in the backup listing.

/*	set_backup_note()
 *	
 *	Used for setting a note to a backup archive.
 *	
 *	@return		null
 */

if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
}

$backup_file = backupbuddy_core::getBackupDirectory() . pb_backupbuddy::_POST( 'backup_file' );
$note = pb_backupbuddy::_POST( 'note' );
$note = preg_replace( "/[[:space:]]+/", ' ', $note );
$note = preg_replace( "/[^[:print:]]/", '', $note );
$note = substr( $note, 0, 200 );


// Returns true on success, else the error message.
$old_comment = pb_backupbuddy::$classes['zipbuddy']->get_comment( $backup_file );
$comment = backupbuddy_core::normalize_comment_data( $old_comment );
$comment['note'] = $note;

//$new_comment = base64_encode( serialize( $comment ) );

$comment_result = pb_backupbuddy::$classes['zipbuddy']->set_comment( $backup_file, $comment );

if ( $comment_result !== true ) {
	echo $comment_result;
} else {
	echo '1';
}

// Even if we cannot save the note into the archive file, store it in internal settings.
$serial = backupbuddy_core::get_serial_from_file( $backup_file );


require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
pb_backupbuddy::status( 'details', 'Fileoptions instance #24.' );
$backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' );
if ( true === ( $result = $backup_options->is_ok() ) ) {
	$backup_options->options['integrity']['comment'] = $note;
	$backup_options->save();
}


die();