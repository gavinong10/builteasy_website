<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}


// On initial login to Step 1 (checks for password field from auth form) reset any dangling defaults from a partial restore.
if ( ( true === Auth::is_authenticated() ) && ( pb_backupbuddy::_POST( 'password' ) != '' ) ) {
	pb_backupbuddy::reset_defaults();
}


// Handle small size PHP upload limit knocking off authentication when uploading a backup.
if ( isset( $_SERVER['CONTENT_LENGTH'] ) && ( intval( $_SERVER['CONTENT_LENGTH'] ) > 0 ) && ( count( $_POST ) === 0 ) ) {
	pb_backupbuddy::alert( 'Error #5484548595. Unable to upload. Your PHP post_max_size setting is too small so it discarded POST data. You may have to log back in.', true );
}
upload(); // Handle any uploading of a backup file.


$data = array();

/**
 *	upload()
 *
 *	Processes uploaded backup file.
 *
 *	@return		array		True on upload success; false otherwise.
 */
function upload() {
	
	Auth::require_authentication();
	
	if ( isset( $_POST['upload'] ) && ( $_POST['upload'] == 'local' ) ) {
		$path_parts = pathinfo( $_FILES['file']['name'] );
		if ( ( strtolower( substr( $_FILES['file']['name'], 0, 6 ) ) == 'backup' ) && ( strtolower( $path_parts['extension'] ) == 'zip' ) ) {
			if ( move_uploaded_file( $_FILES['file']['tmp_name'], basename( $_FILES['file']['name'] ) ) ) {
				pb_backupbuddy::alert( 'File Uploaded. Your backup was successfully uploaded.' );
				return true;
			} else {
				pb_backupbuddy::alert( 'Sorry, there was a problem uploading your file.', true );
				return false;
			}
		} else {
			pb_backupbuddy::alert( 'Only properly named BackupBuddy zip archives with a zip extension may be uploaded.', true );
			return false;
		}
	}
	
	// DOWNLOAD FILE FROM STASH TO LOCAL.
	if ( pb_backupbuddy::_POST( 'upload' ) == 'stash' ) {
		
		pb_backupbuddy::set_greedy_script_limits( true );
		
		$requestcore_file = dirname( dirname( dirname( __FILE__ ) ) ) . '/lib/requestcore/requestcore.class.php';
		require_once( $requestcore_file );
		
		$link = pb_backupbuddy::_POST( 'link' );
		$destination_file = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/' . basename( pb_backupbuddy::_POST( 'link' ) );
		$destination_file = substr( $destination_file, 0, stripos( $destination_file, '.zip' ) + 4 );
		
		$_GET['file'] = basename( $destination_file );
		
		$request = new RequestCore( $link );
		$request->set_write_file( $destination_file );
		
		echo '<div id="pb_importbuddy_working" style="padding: 20px;">Downloading backup from Stash to `' . $destination_file . '`...<br><br><img src="' . pb_backupbuddy::plugin_url() . '/images/loading_large.gif" title="Working... Please wait as this may take a moment..."><br><br></div>';
		pb_backupbuddy::flush();
		
		$response = $request->send_request( false );
		if ( $response !== true ) {
			pb_backupbuddy::alert( 'Error #8548459598. Unable to download file from Stash. You may manually download it and upload to the server via FTP.' );
		} else { // No error.
			if ( ! file_exists( $destination_file ) ) {
				pb_backupbuddy::alert( 'Error #34845745878. Stash returned a success but the backup file was not found locally. Check this server\'s directory write permissions. You may manually download it and upload to the server via FTP.' );
			}
		}
		
		echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
		
	}
}


/**
 *	get_archives_list()
 *
 *	Returns an array of backup archive zip filenames found.
 *
 *	@return		array		Array of .zip filenames; path NOT included.
 */
function get_archives_list() {
	
	Auth::require_authentication();
	
	if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
		pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( ABSPATH );
	}
	
	// List backup files in this directory.
	$backup_archives = array();
	$backup_archives_glob = glob( ABSPATH . 'backup*.zip' );
	if ( !is_array( $backup_archives_glob ) || empty( $backup_archives_glob ) ) { // On failure glob() returns false or an empty array depending on server settings so normalize here.
		$backup_archives_glob = array();
	}
	foreach( $backup_archives_glob as $backup_archive ) {
		$comment = pb_backupbuddy::$classes['zipbuddy']->get_comment( $backup_archive );
		$comment = backupbuddy_core::normalize_comment_data( $comment );
		
		$this_archive = array(
			'file'		=>		basename( $backup_archive ),
			'comment'	=>		$comment,
		);
		$backup_archives[] = $this_archive;
	}
	unset( $backup_archives_glob );
	
	
	return $backup_archives;
}


/* preflightScan()
 *
 * Checks for potential problems before getting started.
 * @return array of potential problems. Key is a unique slug to the issue, value is a descriptive text.
 */
function preflightScan() {
	$issues = array();
	
	if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
		$issues['wordpress_exists'] = 'WARNING: Existing WordPress installation found. It is strongly recommended that existing WordPress files and database be removed prior to migrating or restoring to avoid conflicts. You should not install WordPress prior to migrating.';
	}
	
	if ( file_exists( ABSPATH . 'php.ini' ) ) {
		$issues['php_ini_exists'] = 'WARNING: Existing php.ini file found. If your backup also contains a php.ini file it may overwrite the current one, possibly resulting in changes in cofiguration or problems. Make a backup of your existing file if your are unsure.';
	}
	
	if ( file_exists( ABSPATH . '.htaccess' ) ) {
		$issues['htaccess_exists'] = 'WARNING: Existing .htaccess file found. If your backup also contains a .htaccess file it may overwrite the current one, possibly resulting in changed in configuration or problems. Make a backup of your existing file if you are unsure.';
	}
	
	/* TODO: Move to post flight scan.
	if ( ( file_exists( ABSPATH . 'index.htm' ) === true ) || ( file_exists( ABSPATH . 'index.html' ) === true ) ) {
		$issues['index_exists'] = 'An index.htm(l) file exists in this site path. Depending on your server setup it may take precedence in loading instead of WordPress. If after restore your site loads an unexpected or blank page, try renaming or deleting the index.htm(l) file(s) in the site root, `' . ABSPATH . '`.';
	}
	*/
	
	// Look for directories named after a backup file that contain WordPress. -- improperly unzipped in the wrong location.
	$backup_dirs = glob( ABSPATH . 'backup-*/wp-login.php' );
	if ( ! is_array( $backup_dirs ) ) {
		$backup_dirs = array();
	}
	if ( count( (array)$backup_dirs ) > 0 ) {
		$issues['manual_unzip_wrong_location'] = 'A manually unzipped backup may have been found in the following location(s). If you manually unzipped confirm the files were not unzipped into this subdirectory else they will need to be moved up out of the subdirectory into the same directory as importbuddy.php. Possible manually unzipped backups in a subdirectory: ' . implode( ', ', $backup_dirs );
	}
	
	return $issues;
}




pb_backupbuddy::load_view( 'home', $data );

// LOG IMPORTBUDDY VERSION INFORMATION
pb_backupbuddy::status( 'details', 'Running ImportBuddy v' . pb_backupbuddy::$options['bb_version'] . '.' );

?>
