<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}

// On initial login to Step 1 (checks for password field from auth form) reset any dangling defaults from a partial restore.
if ( ( true === Auth::is_authenticated() ) && ( pb_backupbuddy::_POST( 'password' ) != '' ) ) {
	pb_backupbuddy::reset_defaults();
}


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


/**
 *	wordpress_exists()
 *
 *	Notifies the user with an alert if WordPress appears to already exist in this directory.
 *
 *	@return		boolean		True if WordPress already exists; false otherwise.
 */
function wordpress_exists() {
	if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
		return true;
	} else {
		return false;
	}
}


function phpini_exists() {
	return file_exists( ABSPATH . 'php.ini' );
}


function htaccess_exists() {
	return file_exists( ABSPATH . '.htaccess' );
}


function index_exists() {
	if ( file_exists( ABSPATH . 'index.htm' ) === true ) {
		return true;
	}
	if ( file_exists( ABSPATH . 'index.html' ) === true ) {
		return true;
	}
}


pb_backupbuddy::load_view( 'html_1' );

// LOG IMPORTBUDDY VERSION INFORMATION.
pb_backupbuddy::status( 'details', 'Running ImportBuddy v' . pb_backupbuddy::$options['bb_version'] . '.' );

?>
