<?php
class backupbuddy_restore_files {

	/* restore()
	 *
	 * Restore one or more files to a path.
	 *
	 * @param	$archive_file	Backup zip archive file to restore files from.
	 * @param	$files			Array of files to restore. Each key and value must be the same. Format: array( 'filename.txt' => 'filename.txt' );
	 * @param	$finalPath		Destination path to extract into.
	 * @return	bool			True on success, else false.
	 *
	 */
	public static function restore( $archive_file, $files, $finalPath, &$zipbuddy = null ) {
		if ( !defined( 'PB_STANDALONE' ) || PB_STANDALONE === false ) {
			if ( ! current_user_can( pb_backupbuddy::$options['role_access'] ) ) {
				die( 'Error #473623. Access Denied.' );
			}
		}
		
		$serial = backupbuddy_core::get_serial_from_file( $archive_file ); // serial of archive.
		$success = false;
		
		foreach( $files as $file ) {
			$file = str_replace( '*', '', $file ); // Remove any wildcard.
			if ( file_exists( $finalPath . $file ) && is_dir( $finalPath . $file ) ) {
				if ( ( $file_count = @scandir( $finalPath . $file ) ) && ( count( $file_count ) > 2 ) ) {
					pb_backupbuddy::status( 'error', __( 'Error #9036. The destination directory being restored already exists and is NOT empty. The directory will not be restored to prevent inadvertently losing files within the existing directory. Delete existing directory first if you wish to proceed or restore individual files.', 'it-l10n-backupbuddy' ) . ' Existing directory: `' . $finalPath . $file . '`.' );
					return false;
				}
			}
		}
		
		
		if ( null === $zipbuddy ) {
			require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
			$zipbuddy = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		}
		
		
		// Calculate temp directory & lock it down.
		$temp_dir = get_temp_dir();
		$destination = $temp_dir . 'backupbuddy-' . $serial;
		if ( ( ( ! file_exists( $destination ) ) && ( false === mkdir( $destination, 0777, true ) ) ) ) {
			$error = 'Error #458485945: Unable to create temporary location `' . $destination . '`. Check permissions.';
			pb_backupbuddy::status( 'error', $error );
			return false;
		}
		
		
		// If temp directory is within webroot then lock it down.
		$temp_dir = str_replace( '\\', '/', $temp_dir ); // Normalize for Windows.
		$temp_dir = rtrim( $temp_dir, '/\\' ) . '/'; // Enforce single trailing slash.
		if ( FALSE !== stristr( $temp_dir, ABSPATH ) ) { // Temp dir is within webroot.
			pb_backupbuddy::anti_directory_browsing( $destination );
		}
		unset( $temp_dir );
		pb_backupbuddy::status( 'details', 'Extracting into temporary directory "' . $destination . '".' );
		
		$prettyFilesList = array();
		foreach( $files as $fileSource => $fileDestination ) {
			$prettyFilesList[] = $fileSource . ' => ' . $fileDestination;
		}
		pb_backupbuddy::status( 'details', 'Files to extract: `' . htmlentities( implode( ', ', $prettyFilesList ) ) . '`.' );
		unset( $prettyFilesList );
		
		pb_backupbuddy::flush();
		
		// Do the actual extraction.
		$extract_success = true;
		if ( false === $zipbuddy->extract( $archive_file, $destination, $files ) ) {
			pb_backupbuddy::status( 'error', 'Error #584984458b. Unable to extract.' );
			$extract_success = false;
		}
		
		if ( true === $extract_success ) {
			
			// Verify all files/directories to be extracted exist in temp destination directory. If any missing then delete everything and bail out.
			foreach( $files as &$file ) {
				$file = str_replace( '*', '', $file ); // Remove any wildcard.
				if ( ! file_exists( $destination . '/' . $file ) ) {
					// Cleanup.
					foreach( $files as $file ) {
						@trigger_error( '' ); // Clear out last error.
						@unlink( $destination . '/' . $file);
						$last_error = error_get_last();
						if ( is_array( $last_error ) ) {
							pb_backupbuddy::status( 'error', $last_error['message'] . ' File: `' . $last_error['file'] . '`. Line: `' . $last_error['line'] . '`.' );
						}
					}
					pb_backupbuddy::status( 'error', 'Error #854783474. One or more expected files / directories missing.' );
					
					$extract_success = false;
					break;
				}
			}
			unset( $file );
			
			// Made it this far so files all exist. Move them all.
			foreach( $files as $file ) {
				@trigger_error( '' ); // Clear out last error.
				if ( false === pb_backupbuddy::$filesystem->recursive_copy( $destination . '/' . $file, $finalPath . $file ) ) {
					$last_error = error_get_last();
					if ( is_array( $last_error ) ) {
						//print_r( $last_error );
						pb_backupbuddy::status( 'error', $last_error['message'] . ' File: `' . $last_error['file'] . '`. Line: `' . $last_error['line'] . '`.' );
					}
					$error = 'Error #9035. Unable to copyrestored file `' . $destination . '/' . $file . '` to `' . $finalPath . $file . '`. Verify permissions on destination location & that the destination directory/file does not already exist.';
					pb_backupbuddy::status( 'error', $error );
				} else {
					$details = 'Recursively moved `' . $destination . '/' . $file . '` to `' . $finalPath . $file . '`.<br>';
					pb_backupbuddy::status( 'details', $details );
					$success = true;
				}
			}
			
		} // end extract success.
		
		
		// Try to cleanup.
		if ( file_exists( $destination ) ) {
			if ( false === pb_backupbuddy::$filesystem->unlink_recursive( $destination ) ) {
				pb_backupbuddy::status( 'details', 'Unable to delete temporary holding directory `' . $destination . '`.' );
			} else {
				pb_backupbuddy::status( 'details', 'Cleaned up temporary files.' );
			}
		}
		
		
		if ( true === $success ) {
			pb_backupbuddy::status( 'message', 'File retrieval completed successfully.' );
			return true;
		} else {
			return false;
		}
		
	} // End function restore().

} // End class.