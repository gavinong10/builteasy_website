<?php
/**
 *	pluginbuddy_zipbuddy Class
 *
 *	Handles zipping and unzipping, using the best methods available and falling back to worse methods
 *	as needed for compatibility. Allows for forcing compatibility modes.
 *	
 *	@since 3.0.0
 *	@author Dustin Bolton
 *
 *	$temp_dir		string		Temporary directory absolute path for temporary file storage. Must be writable!
 *	$zip_methods	array		Optional. Array of available zip methods to use. Useful for not having to re-test every time.
 *								If omitted then a test will be performed to find the methods that work on this host.
 *	$mode			string		Future use to allow for other compression methods other than zip. Currently not in use.
 *
 */



// Try and load the experimental version - if successful then class will exist and remaining code will be ignored
if (
		( defined( 'USE_EXPERIMENTAL_ZIPBUDDY' ) && ( true === USE_EXPERIMENTAL_ZIPBUDDY ) )
		||
		( isset( pb_backupbuddy::$options['alternative_zip'] ) && ( '1' == pb_backupbuddy::$options['alternative_zip'] ) )
	) {
		require_once( dirname( __FILE__ ) . '/x-zipbuddy.php' );
}



if ( !class_exists( "pluginbuddy_zipbuddy" ) ) {
	class pluginbuddy_zipbuddy {
		
		
		/********** Properties **********/
		
		
		const ZIP_METHODS_TRANSIENT = 'pb_backupbuddy_avail_zip_methods_classic';
		const ZIP_EXECPATH_TRANSIENT = 'pb_backupbuddy_exec_path_classic';
		const ZIP_TRANSIENT_LIFE = 60;
		const NORM_DIRECTORY_SEPARATOR = '/';
		const DIRECTORY_SEPARATORS = '/\\';
		
		private $_commandbuddy;
		public $_zip_methods;		// Array of available zip methods.
		
		
		/********** Methods **********/
		
		
		function __construct( $temp_dir, $zip_methods = array(), $mode = 'zip' ) {
			//$this->_status = array();
			$this->_tempdir = $temp_dir;
			$this->_execpath = '';
			
			// Handles command line execution.
			require_once( pb_backupbuddy::plugin_path() . '/lib/commandbuddy/commandbuddy.php' );
			$this->_commandbuddy = new pb_backupbuddy_commandbuddy();
			
			if ( !empty( $zip_methods ) && ( count( $zip_methods ) > 0 ) ) {
				$this->_zip_methods = $zip_methods;
			} else {
				if ( function_exists( 'get_transient' ) ) { // Inside WordPress
					
					if ( pb_backupbuddy::$options['disable_zipmethod_caching'] == '1' ) {
						pb_backupbuddy::status( 'details', 'Zip method caching disabled based on settings.' );
						$available_methods = false;
						$exec_path = false;
					} else { // Use caching.
						$available_methods = get_transient( self::ZIP_METHODS_TRANSIENT );
						$exec_path = get_transient( self::ZIP_EXECPATH_TRANSIENT );
					}
					
					if ( ( $available_methods === false ) || ( $exec_path === false ) ) {
						pb_backupbuddy::status( 'details', 'Zip methods or exec path were not cached; detecting...' );
						$this->_zip_methods = $this->available_zip_methods( false, $mode );
						set_transient( self::ZIP_METHODS_TRANSIENT, $this->_zip_methods, self::ZIP_TRANSIENT_LIFE );
						set_transient( self::ZIP_EXECPATH_TRANSIENT, $this->_execpath, self::ZIP_TRANSIENT_LIFE ); // Calculated and set in available_zip_methods().
						pb_backupbuddy::status( 'details', 'Caching zipbuddy classic methods & exec path for `' . self::ZIP_TRANSIENT_LIFE . '` seconds.' );
					} else {
						pb_backupbuddy::status( 'details', 'Using cached zipbuddy classic methods: `' . implode( ',', $available_methods ) . '`.' );
						pb_backupbuddy::status( 'details', 'Using cached zipbuddy classic exec path: `' . $exec_path . '`.' );
						$this->_zip_methods = $available_methods;
					}
				} else { // Outside WordPress
					$this->_zip_methods = $this->available_zip_methods( false, $mode );
					pb_backupbuddy::status( 'details', 'Zipbuddy classic methods not cached due to being outside WordPress.' );
				}
			}
		}
		
		
		// Function to translate a ZipArchive error code into an informative string description
		function ziparchive_error_info( $error, $full = true ) {
		
			// For safety let's check that the class actually exists...
			if ( class_exists( 'ZipArchive', false ) ) {
			
				// We can check the symbolic values
				switch( (int) $error ) {
				 case ZIPARCHIVE::ER_OK:
				 	$error_name = "ZIPARCHIVE::ERR_OK";
				 	$error_description = "No error";
				 	break;
				 case ZIPARCHIVE::ER_OPEN:
				 	$error_name = "ZIPARCHIVE::ER_OPEN";
				 	$error_description = "Can't open file";
				 	break;
				 case ZIPARCHIVE::ER_MEMORY:
				 	$error_name = "ZIPARCHIVE::ER_MEMORY";
				 	$error_description = "Memory allocation failure";
				 	break;
				 case ZIPARCHIVE::ER_EXISTS:
				 	$error_name = "ZIPARCHIVE::ERR_EXISTS";
				 	$error_description = "File already exists";
				 	break;
				 case ZIPARCHIVE::ER_INCONS:
				 	$error_name = "ZIPARCHIVE::ER_INCONS";
				 	$error_description = "Zip archive inconsistent";
				 	break;
				 case ZIPARCHIVE::ER_INVAL:
				 	$error_name = "ZIPARCHIVE::ER_INVAL";
				 	$error_description = "Invalid argument";
				 	break;
				 case ZIPARCHIVE::ER_NOENT:
				 	$error_name = "ZIPARCHIVE::ER_NOENT";
				 	$error_description = "No such file";
				 	break;
				 case ZIPARCHIVE::ER_NOZIP:
				 	$error_name = "ZIPARCHIVE::ER_NOZIP";
				 	$error_description = "Not a zip archive";
				 	break;
				 case ZIPARCHIVE::ER_READ:
				 	$error_name = "ZIPARCHIVE::ER_READ";
				 	$error_description = "Read error";
				 	break;
				 case ZIPARCHIVE::ER_SEEK:
				 	$error_name = "ZIPARCHIVE::ER_SEEK";
				 	$error_description = "Seek error";
				 	break;
				 case ZIPARCHIVE::ER_MULTIDISK:
				 	$error_name = "ZIPARCHIVE::ER_MULTIDISK";
				 	$error_description = "Multi-disk zip archives not supported";
				 	break;
				 case ZIPARCHIVE::ER_RENAME:
				 	$error_name = "ZIPARCHIVE::ER_RENAME";
				 	$error_description = "Renaming temporary file failed";
				 	break;
				 case ZIPARCHIVE::ER_CLOSE:
				 	$error_name = "ZIPARCHIVE::ER_CLOSE";
				 	$error_description = "Closing zip archive failed";
				 	break;
				 case ZIPARCHIVE::ER_WRITE:
				 	$error_name = "ZIPARCHIVE::ER_WRITE";
				 	$error_description = "Write error";
				 	break;
				 case ZIPARCHIVE::ER_CRC:
				 	$error_name = "ZIPARCHIVE::ER_CRC";
				 	$error_description = "CRC error";
				 	break;
				 case ZIPARCHIVE::ER_ZIPCLOSED:
				 	$error_name = "ZIPARCHIVE::ER_ZIPCLOSED";
				 	$error_description = "Containing zip archive was closed";
				 	break;
				 case ZIPARCHIVE::ER_TMPOPEN:
				 	$error_name = "ZIPARCHIVE::ER_TMPOPEN";
				 	$error_description = "Failure to create temporary file";
				 	break;
				 case ZIPARCHIVE::ER_ZLIB:
				 	$error_name = "ZIPARCHIVE::ER_ZLIB";
				 	$error_description = "Zlib error";
				 	break;
				 case ZIPARCHIVE::ER_CHANGED:
				 	$error_name = "ZIPARCHIVE::ER_CHANGED";
				 	$error_description = "Entry has been changed";
				 	break;
				 case ZIPARCHIVE::ER_COMPNOTSUPP:
				 	$error_name = "ZIPARCHIVE::ER_COMPNOTSUPP";
				 	$error_description = "Compression method not supported";
				 	break;
				 case ZIPARCHIVE::ER_EOF:
				 	$error_name = "ZIPARCHIVE::ER_EOF";
				 	$error_description = "Premature EOF";
				 	break;
				 case ZIPARCHIVE::ER_INTERNAL:
				 	$error_name = "ZIPARCHIVE::ER_INTERNAL";
				 	$error_description = "Internal error";
				 	break;
				 case ZIPARCHIVE::ER_REMOVE:
				 	$error_name = "ZIPARCHIVE::ER_REMOVE";
				 	$error_description = "Can't remove file";
				 	break;
				 case ZIPARCHIVE::ER_DELETED:
				 	$error_name = "ZIPARCHIVE::ER_DELETED";
				 	$error_description = "Entry has been deleted";
				 	break;
				 default:
				 	$error_name = "ZIPARCHIVE::ERR_UNKNOWN";
				 	$error_description = "Unkmown error";
				}
				
				$error_string = $error_name . "(" . $error . ") : " . $error_description;
			
			} else {
			
				// If the ZipArchive class doesn't exists just return a generic
				$error_string = "ZIPARCHIVE::ER_UNKNOWN(" . $error . ") : Unknown error";
			
			}
			
			// One way or another we have a string to return
			return $error_string;
		
		}
		
		// Returns true if the file (with path) exists in the ZIP.
		// If leave_open is true then the zip object will be left open for faster checking for subsequent files within this zip
		// Note: leave_open functionality currently not implemented
		function file_exists( $zip_file, $locate_file, $leave_open = false ) {

			// Use ZipArchive if available
			if ( in_array( 'ziparchive', $this->_zip_methods ) ) {
			
				// Make doubly sure it is available - if not we'll just drop through
				if ( class_exists( 'ZipArchive', false) ) {
				
					$za = new ZipArchive();
					$result = $za->open( $zip_file );
					
					// Make sure we opened the zip ok
					if ( $result === true ) {
					
						// Now try and find the index of the file
						$index = $za->locateName( $locate_file );
						
						// We have finished with the archive (leave_open ignored for now)
						$za->close();
						
						// If we got an index we found it otherwise not found
						if ( $index !== false ) {
						
							pb_backupbuddy::status( 'details', __('File found (ziparchive)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
							return true;
							
						} else {
						
							pb_backupbuddy::status( 'details', __('File not found (ziparchive)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
							return false;
							
						}
						
					} else {
					
						// Couldn't open archive - drop through as maybe other method will succeed?
						$error_string = $this->ziparchive_error_info( $result );
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

					}
				
				} else {
				
					// Something fishy - the methods indicated ziparchive but we couldn't find the class
					pb_backupbuddy::status( 'details', __('ziparchive indicated as available method but ZipArchive class non-existent','it-l10n-backupbuddy' ) );

				}
				
			}
			
			// Dropped through because ZipArchive not available or failed to open file
			if ( in_array( 'pclzip', $this->_zip_methods ) ) {
			
				// Make sure we have it
				if ( !class_exists( 'PclZip', false ) ) {
				
					// It's not already loaded so try and find/load it from possible locations
					if ( file_exists( ABSPATH . 'wp-admin/includes/class-pclzip.php' ) ) {
					
						// Running under WordPress
						@include_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
						
					} elseif ( file_exists( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' ) ) {
					
						// Running Standalone (importbuddy)
						@include_once( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
						
					}
					
				}
				
				// Make sure we did load it
				if ( class_exists( 'PclZip', false ) ) {
				
					$za = new PclZip( $zip_file );
					
					// Make sure we opened the zip ok and it has content
					if ( ( $content_list = $za->listContent() ) !== 0 ) {
					
						// Get each file in sequence by index and get the properties
						for ( $i = 0; $i < sizeof( $content_list ); $i++ ) {
						
							$stat = $content_list[ $i ];
							
							// Assume the key exists (consider testing)
							if ( $stat[ 'filename' ] == $locate_file ) {
							
								// File found so we can just return
								pb_backupbuddy::status( 'details', __('File found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
								return true;
								
							}
							
						}
						
						// Only get here if the file wasn't found
						pb_backupbuddy::status( 'details', __('File not found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						return false;

					} else {
					
						// Couldn't open archive - drop through as maybe other method will succeed?
						$error_string = $za->errorInfo( true );
						pb_backupbuddy::status( 'details', sprintf( __('pclzip failed to open file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

					}
								
				} else {
				
					// Something fishy - the methods indicated pclzip but we couldn't find the class
					pb_backupbuddy::status( 'details', __('pclzip indicated as available method but class PclZip non-existent','it-l10n-backupbuddy' ) );

				}
				
			}
			
			// If we got this far then no method to check backup content was available or worked
			pb_backupbuddy::status( 'details', sprintf( __('Unable to check if file exists (looking for %1$s in %2$s): No compatible zip method found.','it-l10n-backupbuddy' ), $locate_file, $zip_file ) );
			return false;

		}
		
		
		
		/*	set_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		boolean/string					true on success, error message otherwise.
		 */
		function set_comment( $zip_file, $comment ) {
		
			//Use ZipArchive if available
			if ( in_array( 'ziparchive', $this->_zip_methods ) ) {
			
				// Make doubly sure it is available
				if ( class_exists( 'ZipArchive', false ) ) {
				
					$za = new ZipArchive;
					$result = $za->open( $zip_file );
					
					// Make sure at least the zip file opened ok
					if ( $result === true ) {
					
							// Set the comment - true on success, false on failure
							$result = $za->setArchiveComment( $comment );
							$za->close();
							
							// If we got back true then all is well with the world
							if ( $result === true ) {
							
								pb_backupbuddy::status( 'details', sprintf( __('ZipArchive set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
								return true;
								
							} else {
							
								// If we failed to set the commnent then log it (?) and drop through
								pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );

							}
							
					} else {
					
						// If we couldn't open the zip file then log it (?) and drop through
						$error_string = $this->ziparchive_error_info( $result );
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to set comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
						
					}
									
				} else {
				
					// Something fishy - the methods indicated ziparchive but we couldn't find the class
					pb_backupbuddy::status( 'details', __('ziparchive indicated as available method but ZipArchive class non-existent','it-l10n-backupbuddy' ) );

				}
				
			}
			
			// Dropped through because ZipArchive not available or failed for some reason
			if ( in_array( 'pclzip', $this->_zip_methods ) ) {
			
				// Make sure we have it
				if ( !class_exists( 'PclZip', false ) ) {
				
					// It's not already loaded so try and find/load it from possible locations
					if ( file_exists( ABSPATH . 'wp-admin/includes/class-pclzip.php' ) ) {
					
						// Running under WordPress
						@include_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
						
					} elseif ( file_exists( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' ) ) {
					
						// Running Standalone (importbuddy)
						@include_once( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
						
					}
					
				}
				
				// Make sure we did load it
				if ( class_exists( 'PclZip', false) ) {
				
					$za = new PclZip( $zip_file );
					
					// Make sure we opened the zip ok and we added the comment ok
					// Note: using empty array as we don't actually want to add any files
					if ( ( $list = $za->add( array(), PCLZIP_OPT_COMMENT, $comment ) ) !== 0 ) {
					
						// We got a list back so adding comment should have been successful
						pb_backupbuddy::status( 'details', sprintf( __('PclZip set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
						return true;
						
					} else {
					
						// If we failed to set the commnent then log it (?) and drop through
						$error_string = $za->errorInfo( true );
						pb_backupbuddy::status( 'details', sprintf( __('PclZip failed to set comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
						
					}
				
				} else {
				
					// Something fishy - the methods indicated pclzip but we couldn't find the class
					pb_backupbuddy::status( 'details', __('pclzip indicated as available method but class PclZip non-existent','it-l10n-backupbuddy' ) );

				}
				
			}
			
			// We couldn't set a comment at all - either no available method or all methods failed
			pb_backupbuddy::status( 'details', sprintf( __('Unable to set comment in file %1$s: No compatible zip method found or all methods failed - note stored internally only.','it-l10n-backupbuddy' ), $zip_file ) );

			// Return message for display - maybe should return false and have caller display it's own message?
			$message = "\n\nUnable to set note in file.\nThe note will only be stored internally in your settings and not in the zip file itself.";
			return $message;
			
		} // End set_comment().
		
		
		
		/*	get_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		string						Zip comment.
		 */
		function get_comment( $zip_file ) {
		
			// Use ZipArchive if available
			if ( in_array( 'ziparchive', $this->_zip_methods ) ) {
			
				// Make doubly sure it is available
				if ( class_exists( 'ZipArchive', false ) ) {
				
					$za = new ZipArchive();
					$result = $za->open( $zip_file );
					
					// Make sure that at least the zip file opened ok
					if ( $result === true ) {
					
						// Get the comment or false on failure for some reason
						$comment = $za->getArchiveComment();
						$za->close();
						
						// If we have a comment (even if empty) then return it
						if ( $comment !== false ) {
						
							// Note: new archives will return an empty comment if one was not added at creation
							pb_backupbuddy::status( 'details', sprintf( __('ZipArchive retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );

							// Format has changed and no longer encoding as htmlemtities when setting comment
							// For older backups may need to remove encoding - action _should_ be null if N/A
							// Only spanner would be if someone had put an entity in their comment but that is
							// really an outsider and in any case the correction is simply to edit and resave
							// TODO: Remove this when new format has been in use for some time
							$comment = html_entity_decode( $comment );				

							return $comment;
							
						} else {
						
							// If we failed to get the commnent then log it (?) and drop through
							pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to retrieve comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );

						}

					} else {
					
						// If we couldn't open the zip file then log it (?) and drop through
						$error_string = $this->ziparchive_error_info( $result );
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to retrieve comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

					}
				
				} else {
				
					// Something fishy - the methods indicated ziparchive but we couldn't find the class
					pb_backupbuddy::status( 'details', __('ziparchive indicated as available method but ZipArchive class non-existent','it-l10n-backupbuddy' ) );

				}
				
			}
			
			// Dropped through because ZipArchive not available or failed for some reason
			if ( in_array( 'pclzip', $this->_zip_methods ) ) {
			
				// Make sure we have it
				if ( !class_exists( 'PclZip', false ) ) {
				
					// It's not already loaded so try and find/load it from possible locations
					if ( file_exists( ABSPATH . 'wp-admin/includes/class-pclzip.php' ) ) {
					
						// Running under WordPress
						@include_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
						
					} elseif ( file_exists( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' ) ) {
					
						// Running Standalone (importbuddy)
						@include_once( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
						
					}
					
				}
				
				// Make sure we did load it
				if ( class_exists( 'PclZip', false) ) {
				
					$za = new PclZip( $zip_file );
					
					// Make sure we opened the zip ok and it has properties
					if ( ( $properties = $za->properties() ) !== 0 ) {
					
						// We got properties so should have a comment to return, even if empty
						pb_backupbuddy::status( 'details', sprintf( __('PclZip retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
						$comment = $properties[ 'comment' ];

						// Format has changed and no longer encoding as htmlemtities when setting comment
						// For older backups may need to remove encoding - action _should_ be null if N/A
						// Only spanner would be if someone had put an entity in their comment but that is
						// really an outsider and in any case the correction is simply to edit and resave
						// TODO: Remove this when new format has been in use for some time
						$comment = html_entity_decode( $comment );
					
						return $comment;
						
					} else {
					
						// If we failed to get the commnent then log it (?) and drop through
						$error_string = $za->errorInfo( true );
						pb_backupbuddy::status( 'details', sprintf( __('PclZip failed to retrieve comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
						
					}
				
				} else {
				
					// Something fishy - the methods indicated pclzip but we couldn't find the class
					pb_backupbuddy::status( 'details', __('pclzip indicated as available method but class PclZip non-existent','it-l10n-backupbuddy' ) );

				}
				
			}
			
			// We couldn't get a comment at all - either no available method or all methods failed
			pb_backupbuddy::status( 'details', __('Unable to get comment: No compatible zip method found.','it-l10n-backupbuddy' ) );
			return false;
			
		} // End get_comment().
		
		
		
		// FOR FUTURE USE; NOT YET IMPLEMENTED. Use to check .sql file is non-empty.
		function file_stats( $zip_file, $locate_file, $leave_open = false ) {
			if ( in_array( 'ziparchive', $this->_zip_methods ) ) {
				$this->_zip = new ZipArchive;
				if ( $this->_zip->open( $zip_file ) === true ) {
					if ( ( $stats = $this->_zip->statName( $locate_file ) ) === false ) { // File not found in zip.
						$this->_zip->close();
						pb_backupbuddy::status( 'details', __('File not found (ziparchive) for stats','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						return false;
					}
					$this->_zip->close();
					return $stats;
				} else {
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to check stats (looking in %1$s).','it-l10n-backupbuddy' ), $zip_file ) );
					
					return false;
				}
			}
			
			// If we made it this far then ziparchive not available/failed.
			if ( in_array( 'pclzip', $this->_zip_methods ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
				$this->_zip = new PclZip( $zip_file );
				if ( ( $file_list = $this->_zip->listContent() ) == 0 ) { // If zero, zip is corrupt or empty.
					pb_backupbuddy::status( 'details', $this->_zip->errorInfo( true ) );
				} else {
					foreach( $file_list as $file ) {
						if ( $file['filename'] == $locate_file ) { // Found file.
							return true;
						}
					}
					pb_backupbuddy::status( 'details', __('File not found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
					return false;
				}
			} else {
				pb_backupbuddy::status( 'details', __('Unable to check if file exists: No compatible zip method found.','it-l10n-backupbuddy' ) );
				return false;
			}
		}
		
		
		
		/*	get_zip_methods()
		 *	
		 *	Get an array of the zip methods. Useful for transient caching for constructor.
		 *	
		 *	@return		array		Array of methods.
		 */
		public function get_zip_methods() {
			$this->_zip_methods;
		} // End get_zip_methods();
		
		
		
		/**
		 *	add_directory_to_zip()
		 *
		 *	Adds a directory to a new or existing (TODO: not yet available) ZIP file.
		 *
		 *	$zip_file					string						Full path & filename of ZIP file to create.
		 *	$add_directory				string						Full directory to add to zip file.
		 *	$compression				boolean						True to enable ZIP compression,
		 *															(if possible with available zip methods)
		 *	$excludes					array(strings)				Array of strings of paths/files to exclude from zipping,
		 *															(if possible with available zip methods).
		 *	$temporary_zip_directory	string						Optional. Full directory path to directory to temporarily place ZIP
		 *															file while creating. Uses same directory if omitted.
		 *	$force_compatibility_mode	boolean						True: only use PCLZip. False: try exec first if available,
		 *															and fallback to lesser methods as required.
		 *
		 *	@return													true on success, false otherwise
		 *
		 */
		function add_directory_to_zip( $zip_file, $add_directory, $compression, $excludes = array(), $temporary_zip_directory = '', $force_compatibility_mode = false ) {
			if ( $force_compatibility_mode === true ) {
				$zip_methods = array( 'pclzip' );
				pb_backupbuddy::status( 'message', __('Forced compatibility mode (PCLZip) based on settings. This is slower and less reliable.','it-l10n-backupbuddy' ) );
			} else {
				$zip_methods = $this->_zip_methods;
				pb_backupbuddy::status( 'details', __('Using all available zip methods in preferred order.','it-l10n-backupbuddy' ) );
			}
			
			$append = false; // Possible future option to allow appending if file exists.
			
			// Normalize $temporary_zip_directory to format: /xxx/yyy/zzz/.
			$temporary_zip_directory = rtrim( $temporary_zip_directory, '/\\' ) . '/';
			
			if ( !empty( $temporary_zip_directory ) ) {
				if ( !file_exists( $temporary_zip_directory ) ) { // Create temp dir if it does not exist.
					mkdir( $temporary_zip_directory );
				}
			}
			
			if ( is_array( $excludes ) ) {
				$excludes_text = implode( ',', $excludes );
			} else {
				$excludes_text = '(in file: `' . $excludes . '`)';
			}
			pb_backupbuddy::status( 'details', __('Creating ZIP file','it-l10n-backupbuddy' ) . ' `' . $zip_file . '`. ' . __('Adding directory','it-l10n-backupbuddy' ) . ' `' . $add_directory . '`. ' . __('Compression','it-l10n-backupbuddy' ) . ': ' . $compression . '; ' . __('Excludes','it-l10n-backupbuddy' ) . ': ' . $excludes_text );
			unset( $excludes_text );
			
			if ( in_array( 'exec', $zip_methods ) ) {
				pb_backupbuddy::status( 'details', __('Using exec() method for ZIP.','it-l10n-backupbuddy' ) );
				
				$command = 'zip -q -r';
				
				if ( $compression !== true ) {
					$command .= ' -0';
					pb_backupbuddy::status( 'details', __('Exec compression disabled based on settings.','it-l10n-backupbuddy' ) );
				}
				if ( file_exists( $zip_file ) ) {
					if ( $append === true ) {
						pb_backupbuddy::status( 'details', __('ZIP file exists. Appending based on options.','it-l10n-backupbuddy' ) );
						$command .= ' -g';
					} else {
						pb_backupbuddy::status( 'details', __('ZIP file exists. Deleting & writing based on options.','it-l10n-backupbuddy' ) );
						unlink( $zip_file );
					}
				}
				
				//$command .= " -r";
				
				// Set temporary directory to store ZIP while it's being generated.
				if ( !empty( $temporary_zip_directory ) ) {
					$command .= " -b '{$temporary_zip_directory}'";
				}
				
				$command .= " '{$zip_file}' .";
				// -i '*'"; // Not needed. Zip defaults to doing this. Removed July 10, 2012 for v3.0.41.
				
								
				// Handle exclusions by placing them in an exclusion text file.
				$exclusion_file = $temporary_zip_directory . 'exclusions.txt';
				$this->_render_exclusions_file( $exclusion_file, $excludes );
				pb_backupbuddy::status( 'details', 'Using exclusion file `' . $exclusion_file . '`.' );
				$command .= ' -x@' . "'{$exclusion_file}'";
				
				
				$command .= ' 2>&1'; //  2>&1 redirects STDERR to STDOUT
				
				$working_dir = getcwd();
				chdir( $add_directory ); // Change directory to the path we are adding.
				
				if ( $this->_execpath != '' ) {
					pb_backupbuddy::status( 'details', __( 'Using custom exec() path: ', 'it-l10n-backupbuddy' ) . $this->_execpath );
				}
				
				// Run ZIP command.
				if ( stristr( PHP_OS, 'WIN' ) && !stristr( PHP_OS, 'DARWIN' ) ) { // Running Windows. (not darwin)
					if ( file_exists( ABSPATH . 'zip.exe' ) ) {
						pb_backupbuddy::status( 'message', __('Attempting to use provided Windows zip.exe.','it-l10n-backupbuddy' ) );
						$command = str_replace( '\'', '"', $command ); // Windows wants double quotes
						$command = ABSPATH . $command;
					}
					
					pb_backupbuddy::status( 'details', __('Exec command (Windows)','it-l10n-backupbuddy' ) . ': ' . $command );
					list( $exec_output, $exec_exit_code ) = $this->_commandbuddy->execute( $this->_execpath . $command );
				} else { // Allow exec warnings not in Windows
					pb_backupbuddy::status( 'details', __('Exec command (Linux)','it-l10n-backupbuddy' ) . ': ' . $command );
					list( $exec_output, $exec_exit_code ) = $this->_commandbuddy->execute( $this->_execpath . $command );
				}
				
				
				// Cleanup exclusions file if it exists.
				if ( file_exists( $temporary_zip_directory . 'exclusions.txt' ) ) {
					@unlink( $temporary_zip_directory . 'exclusions.txt' );
				}
				
				
				sleep( 1 );
				
				// We may not have a zip file or we may have one but there was error/warning when producing it
				// Note: In event of warnings we could still get a zip file if the script terminates whilst zip command still running
				
				if ( ( ! file_exists( $zip_file ) ) || ( $exec_exit_code != 0 ) ) {
					
					// Log the failure
					pb_backupbuddy::status( 'message', __( 'Full speed mode did not complete. Trying compatibility mode next.','it-l10n-backupbuddy' ) );
					
					// Check whether a zip file was actually produced in the backups directory (as opposed to the temp zip directory)
					if ( ! file_exists( $zip_file ) ) {
					
						pb_backupbuddy::status( 'details', __( 'Exec command ran but ZIP file did not exist.','it-l10n-backupbuddy' ) );
						
					}
					
					// We did get a zip file but cannot truest it so must delete it
					if ( file_exists( $zip_file ) ) {
					
						pb_backupbuddy::status( 'details', __( 'Cleaning up damaged ZIP file. Issue #3489328998.','it-l10n-backupbuddy' ) );
						unlink( $zip_file );
						
					}
					
					// Need to clean up any temporary zip directory
					if ( file_exists( $temporary_zip_directory ) ) {
					
						pb_backupbuddy::status( 'details', __( 'Cleaning up incomplete temporary ZIP file. Issue #343894.','it-l10n-backupbuddy' ) );
						if ( !( $this->delete_directory_recursive( $temporary_zip_directory ) ) ) {
						
							pb_backupbuddy::status( 'details', __( 'Unable to delete temporary zip directory','it-l10n-backupbuddy' ) );
						
						}
						
					}
					
				} else {
				
					// We got a zip file and no errors/warnings so good to go
					pb_backupbuddy::status( 'message', __( 'Full speed mode completed & generated ZIP file.','it-l10n-backupbuddy' ) );
					if ( !( $this->delete_directory_recursive( $temporary_zip_directory ) ) ) {
					
						pb_backupbuddy::status( 'details', __( 'Unable to delete temporary zip directory','it-l10n-backupbuddy' ) );
					
					}
					
					return true;
					
				}
				
				chdir( $working_dir );
				
				unset( $command );
				unset( $exclude );
				unset( $excluding_additional );
				
				pb_backupbuddy::status( 'details', __('Exec command did not succeed. Falling back.','it-l10n-backupbuddy' ) );
				
			}
			
			if ( in_array( 'pclzip', $zip_methods ) ) {
				pb_backupbuddy::status( 'message', __('Using Compatibility Mode for ZIP. This is slower and less reliable.','it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'message', __('If your backup times out in compatibility mode try disabled zip compression.','it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'message', __('WARNING: Directory/file exclusion unavailable in Compatibility Mode. Even existing old backups will be backed up.','it-l10n-backupbuddy' ) );
				
				require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
				
				// Determine zip file name / path.
				if ( !empty( $temporary_zip_directory ) ) {
					$pclzip_file = $temporary_zip_directory . basename( $zip_file );
				} else {
					$pclzip_file = $zip_file;
				}
				
				if ( !file_exists( dirname( $pclzip_file ) ) ) {
					pb_backupbuddy::status( 'details', 'Creating PCLZip file directory `' . dirname( $pclzip_file ) . '`.' );
					mkdir( dirname( $pclzip_file ) );
				}
				
				// Instantiate PclZip Object.
				pb_backupbuddy::status( 'details', 'PclZip zip filename: `' . $pclzip_file . '`.' );
				$pclzip = new PclZip( $pclzip_file );
				
				if ( $compression !== true ) {
					pb_backupbuddy::status( 'details', __('PCLZip compression disabled based on settings.','it-l10n-backupbuddy' ) );
					$arguments = array( $add_directory, PCLZIP_OPT_NO_COMPRESSION, PCLZIP_OPT_REMOVE_PATH, $add_directory );
				} else {
					pb_backupbuddy::status( 'details', __('PCLZip compression enabled based on settings.','it-l10n-backupbuddy' ) );
					$arguments = array( $add_directory, PCLZIP_OPT_REMOVE_PATH, $add_directory );
				}
				
				$mode = 'create';
				if ( file_exists( $zip_file ) && ( $append === true ) ) {
					pb_backupbuddy::status( 'details', __('ZIP file exists. Appending based on options.','it-l10n-backupbuddy' ) );
					$mode = 'append';
				}
				
				if ( $mode == 'append' ) {
					pb_backupbuddy::status( 'details', __('Appending to ZIP file via PCLZip.','it-l10n-backupbuddy' ) );
					$retval = call_user_func_array( array( &$pclzip, 'add' ), $arguments );
				} else { // create
					pb_backupbuddy::status( 'details', __( 'Creating ZIP file via PCLZip','it-l10n-backupbuddy' ) . ':' . implode( ';', $arguments ) );
					//error_log( 'pclzip args: ' . print_r( $arguments, true ) . "\n" );
					$retval = call_user_func_array( array( &$pclzip, 'create' ), $arguments );
				}
				
				// Move the zip file if we were creating it in a temporary directory
				if ( !empty( $temporary_zip_directory ) ) {
					if ( file_exists( $temporary_zip_directory . basename( $zip_file ) ) ) {
						pb_backupbuddy::status( 'details', __('Renaming PCLZip File...','it-l10n-backupbuddy' ) );
						rename( $temporary_zip_directory . basename( $zip_file ), $zip_file );
						if ( file_exists( $zip_file ) ) {
							pb_backupbuddy::status( 'details', __('Renaming PCLZip success.','it-l10n-backupbuddy' ) );
						} else {
							pb_backupbuddy::status( 'details', __('Renaming PCLZip failure.','it-l10n-backupbuddy' ) );
						}
					} else {
						pb_backupbuddy::status( 'details', __('Temporary PCLZip archive file expected but not found. Please verify permissions on the ZIP archive directory.','it-l10n-backupbuddy' ) );
					}
				}
				
				// Work out whether we have a problem or not
				if ( is_array( $retval ) ) {
				
					// It's an array so a good result
					$exitcode = 0;
				
				} else {
				
					// Not an array so a bad error code
					$exitcode = $pclzip->errorCode();
				
				}
				
				// Convenience for handling different scanarios
				$result = false;
				
				// See if we can figure out what happened - note that $exitcode could be non-zero for a warning or error
				// There may be no zip file at all if there was a problem creating it or it may be left in the temp
				// directory if it couldn't be moved
				if ( ( ! file_exists( $zip_file ) ) || ( $exitcode != 0 ) ) {
				
					// If we had a non-zero exit code then should report it (file may or may not be created)
					if ( $exitcode != 0 ) {
					
						pb_backupbuddy::status( 'details', __('Zip process exit code: ','it-l10n-backupbuddy' ) . $exitcode );
						
					}
	
					// Report whether or not the zip file was created				
					if ( ! file_exists( $zip_file ) ) {
					
						pb_backupbuddy::status( 'details', __( 'Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
						
					} else {
						
						pb_backupbuddy::status( 'details', __( 'Zip Archive file created but will be removed - check process exit code.','it-l10n-backupbuddy' ) );
	
						@unlink( $zip_file );
						
					}
					
					// Put the error information into an array for consistency
					$zip_output[] = $pclzip->errorInfo( true );
					
					// Now we don't move it (because either it doesn't exist or may be incomplete) but we'll show any error/wartning output
					if ( !empty( $zip_output ) ) {
					
						// Assume we don't have a lot of lines for now - could be risky assumption!
						foreach ( $zip_output as $line ) {
						
							pb_backupbuddy::status( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
						
						}
					
						// Extra details for particular error
						if ( false !== strpos( $pclzip->errorInfo( true ), 'PCLZIP_ERR_READ_OPEN_FAIL' ) ) {
							pb_backupbuddy::status( 'details', __( 'PCLZIP_ERR_READ_OPEN_FAIL details: This error indicates that fopen failed (returned false) when trying to open the file in the mode specified. This is almost always due to permissions.', 'it-l10n-backupbuddy' ) );
						}
										
					}
					
					// One way or another we failed
					$result = false;
					
				} else {
				
					// Got file with no error or warnings at all so it should be good to go
					
					if ( file_exists( $zip_file ) ) {
					
						pb_backupbuddy::status( 'details', __('Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
						pb_backupbuddy::status( 'message', __( 'Zip Archive file successfully created with no errors or warnings.','it-l10n-backupbuddy' ) );
						$result = true;
						
					} else {
					
						pb_backupbuddy::status( 'details', __('Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
									
				}			
	
				if ( !empty( $temporary_zip_directory ) ) {
				
					// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file			
					pb_backupbuddy::status( 'details', __('Removing temporary directory.','it-l10n-backupbuddy' ) );
					
					if ( !( $this->delete_directory_recursive( $temporary_zip_directory ) ) ) {
					
							pb_backupbuddy::status( 'details', __('Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $temporary_zip_directory );
					
					}
				
				}
				
				if ( $result ) return $result;
				
			}
			
			// If we made it this far then something didnt result in a success.
			return false;
		}
		
		
		
		/**
		 *	unzip()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	$zip_file					string		Full path & filename of ZIP file to create.
		 *	$destination_directory		string		Full directory path to extract into.
		 *	$force_compatibility_mode	mixed		false (default): use best methods available (zip exec first), falling back as needed.
		 *											ziparchive: first fallback method. (Medium performance)
		 *											pclzip: second fallback method. (Worst performance; buggy)
		 *
		 *	@return``								true on success, false otherwise
		 */
		function unzip( $zip_file, $destination_directory, $force_compatibility_mode = false ) {
			
			$destination_directory = rtrim( $destination_directory, '\\/' ) . '/'; // Make sure trailing slash exists to normalize.
			
			if ( $force_compatibility_mode == 'ziparchive' ) {
				$zip_methods = array( 'ziparchive' );
				pb_backupbuddy::status( 'message', __('Forced compatibility mode (ZipArchive; medium speed) based on settings. This is slower and less reliable.','it-l10n-backupbuddy' ) );
			} elseif ( $force_compatibility_mode == 'pclzip' ) {
				$zip_methods = array( 'pclzip' );
				pb_backupbuddy::status( 'message', __('Forced compatibility mode (PCLZip; slow speed) based on settings. This is slower and less reliable.','it-l10n-backupbuddy' ) );
			} else {
				$zip_methods = $this->_zip_methods;
				pb_backupbuddy::status( 'details', __('Using all available zip methods in preferred order.','it-l10n-backupbuddy' ) );
			}
			
			if ( in_array( 'exec', $zip_methods ) ) {
				pb_backupbuddy::status( 'details',  'Starting highspeed extraction (exec)... This may take a moment...' );
				
				$command = 'unzip -qo'; // q = quiet, o = overwrite without prompt.
				$command .= " '$zip_file' -d '$destination_directory' -x 'importbuddy.php'"; // x excludes importbuddy script to prevent overwriting newer importbuddy on extract step.
			
				// Handle windows.
				if ( stristr( PHP_OS, 'WIN' ) && !stristr( PHP_OS, 'DARWIN' ) ) { // Running Windows. (not darwin)
					if ( file_exists( ABSPATH . 'unzip.exe' ) ) {
						pb_backupbuddy::status( 'details',  'Attempting to use Windows unzip.exe.' );
						$command = str_replace( '\'', '"', $command ); // Windows wants double quotes
						$command = ABSPATH . $command;
					}
				}
				
				$command .= '  2>&1'; // Redirect STDERR to STDOUT.
				
				if ( $this->_execpath != '' ) {
					pb_backupbuddy::status( 'details', __( 'Using custom exec() path: ', 'it-l10n-backupbuddy' ) . $this->_execpath );
				}
				
				pb_backupbuddy::status( 'details', 'Running ZIP command. This may take a moment.' );
				list( $exec_output, $exec_exit_code ) = $this->_commandbuddy->execute( $this->_execpath . $command );
				
				$failed = false; // Default.
				
				if ( !file_exists( $destination_directory . 'wp-login.php' ) && !file_exists( $destination_directory . 'db_1.sql' ) && !file_exists( $destination_directory . 'wordpress/wp-login.php' ) ) { // wp-login.php for WordPress, db_1.sql for DB backup, wordpress/wp-login.php for fresh WordPress downloaded from wp.org for MS export
					pb_backupbuddy::status( 'error', 'Both wp-login.php (full backups) and db_1.sql (database only backups) are missing after extraction. Unzip process appears to have failed.' );
					$failed = true;
				}
				
				if ( $exec_exit_code != '0' ) {
					pb_backupbuddy::status( 'error',  'Exit code `' . $exec_exit_code . '` indicates a problem was encountered.' );
					$failed = true;
				}
				
				// Sometimes exec returns success codes but never extracted actual files. Do a check to make sure known files were extracted to verify against that.
				if ( $failed === false ) {
					pb_backupbuddy::status( 'message', 'File extraction complete.' );
					return true;
				} else {
					pb_backupbuddy::status( 'message',  'Falling back to next compatibility mode.' );
				}
			}
			
			if ( in_array( 'ziparchive', $zip_methods ) ) {
				pb_backupbuddy::status( 'details',  'Starting medium speed extraction (ziparchive)... This may take a moment...' );
				
				$zip = new ZipArchive;
				if ( $zip->open( $zip_file ) === true ) {
					if ( true === $zip->extractTo( $destination_directory ) ) {
						pb_backupbuddy::status( 'details',  'ZipArchive extraction success.' );
						$zip->close();
						return true;
					} else {
						$zip->close();
						pb_backupbuddy::status( 'message',  'Error: ZipArchive was available but failed extracting files.  Falling back to next compatibility mode.' );
					}
				} else {
					pb_backupbuddy::status( 'message',  'Error: Unable to open zip file via ZipArchive. Falling back to next compatibility mode.' );
				}
			}
			
			if ( in_array( 'pclzip', $zip_methods ) ) {
				pb_backupbuddy::status( 'details',  'Starting low speed extraction (pclzip)... This may take a moment...' );
				
				if ( !class_exists( 'PclZip' ) ) {
					$pclzip_file = pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php';
					pb_backupbuddy::status( 'details', 'PCLZip class not found. Attempting to load from `' . $pclzip_file . '`.' );
					if ( file_exists( $pclzip_file ) ) {
						pb_backupbuddy::status( 'details', 'Loading `' . $pclzip_file . '`.' );
						require_once( $pclzip_file );
					} else {
						pb_backupbuddy::status( 'details', 'PCLZip file not found: `' . $pclzip_file . '`.' );
					}
				}
				
				$archive = new PclZip( $zip_file );
				$result = $archive->extract(); // Extract to current directory. Explicity using PCLZIP_OPT_PATH results in extraction to a PCLZIP_OPT_PATH subfolder.
				
				if ( 0 == $result ) {
					pb_backupbuddy::status( 'details',  'PCLZip Failure: ' . $archive->errorInfo( true ) );
					pb_backupbuddy::status( 'message',  'Low speed (PCLZip) extraction failed.', $archive->errorInfo( true ) );
				} else {
					return true;
				}
			}
			
			// Nothing succeeded if we made it this far...
			return false;
		}
		
		
		
		// Test availability of ZipArchive and that it actually works.
		function test_ziparchive() {
			if ( class_exists( 'ZipArchive' ) ) {
				$test_file = $this->_tempdir . 'temp_test_' . uniqid() . '.zip';
				
				$zip = new ZipArchive;
				if ( $zip->open( $test_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ) === true ) {
					$zip->addFile( __FILE__, 'this_is_a_test.txt');
					$zip->close();
					if ( file_exists( $test_file ) ) {
						unlink( $test_file );
						pb_backupbuddy::status( 'details', __('ZipArchive test passed.','it-l10n-backupbuddy' ) );
						return true;
					} else {
						pb_backupbuddy::status( 'details', __('ZipArchive test failed: Zip file not found.','it-l10n-backupbuddy' ) );
						return false;
					}
				} else {
					pb_backupbuddy::status( 'details', __('ZipArchive test FAILED: Unable to create/open zip file.','it-l10n-backupbuddy' ) );
					return false;
				}
			}
		}
		
		
		
		/*	get_file_list()
		 *	
		 *	Get an array of all files in a zip file.
		 *	
		 *	@param		
		 *	@return		array	
		 */
		public function get_file_list( $zip_file ) {
		
			$file_list = array();
			
			// Use ZipArchive if available
			if ( in_array( 'ziparchive', $this->_zip_methods ) ) {
			
				// Make doubly sure it is available
				if ( class_exists( 'ZipArchive', false) ) {
				
					$za = new ZipArchive();
					$result = $za->open( $zip_file );
					
					// Make sure we opened the zip ok and it has content
					if ( $result === true ) {
					
						if ( ( $file_count = $za->numFiles ) > 0 ) {
					
							// Get each file in sequence by index and get the properties
							for( $i = 0; $i < $file_count; $i++ ){
							
								$stat = $za->statIndex( $i );
								
								// Assume all these keys do exist (consider testing)
								$file_list[] = array(
									$stat['name'],
									$stat['size'],
									$stat['comp_size'],
									$stat['mtime'],
								);
								
							}
							
						}
						
						$za->close();
						
						return $file_list;
						
					} else {
					
						// Couldn't open archive - drop through as maybe other method will succeed?
						$error_string = $this->ziparchive_error_info( $result );
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to list content in file %1$s - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					
					}
				
				} else {
				
					// Something fishy - the methods indicated ziparchive but we couldn't find the class
					pb_backupbuddy::status( 'details', __('ziparchive indicated as available method but ZipArchive class non-existent','it-l10n-backupbuddy' ) );
				
				}
				
			}
			
			// Dropped through because ZipArchive not available or failed to open file
			if ( in_array( 'pclzip', $this->_zip_methods ) ) {
			
				// Make sure we have it
				if ( !class_exists( 'PclZip', false ) ) {
				
					// It's not already loaded so try and find/load it from possible locations
					if ( file_exists( ABSPATH . 'wp-admin/includes/class-pclzip.php' ) ) {
					
						// Running under WordPress
						@include_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
						
					} elseif ( file_exists( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' ) ) {
					
						// Running Standalone (importbuddy)
						@include_once( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
						
					}
					
				}
				
				// Make sure we did load it
				if ( class_exists( 'PclZip', false ) ) {
				
					$za = new PclZip( $zip_file );
					
					// Make sure we opened the zip ok and it has content
					if ( ( $content_list = $za->listContent() ) !== 0 ) {
					
						$file_count = sizeof( $content_list );
						
						// Get each file in sequence by index and get the properties
						for ( $i = 0; $i < $file_count; $i++ ) {
						
							$stat = $content_list[ $i ];
							
							// Assume all these keys do exist (consider testing)
							$file_list[] = array(
								$stat[ 'filename' ],
								$stat[ 'size' ],
								$stat[ 'compressed_size' ],
								$stat[ 'mtime' ]
							);
							
						}
						
						return $file_list;

					} else {
					
						// Couldn't open archive - drop through as maybe other method will succeed?
						$error_string = $za->errorInfo( true );
						pb_backupbuddy::status( 'details', sprintf( __('PclZip failed to open file to list content in file %1$s - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					
					}
					
				} else {
				
					// Something fishy - the methods indicated pclzip but we couldn't find the class
					pb_backupbuddy::status( 'details', __('pclzip indicated as available method but class PclZip non-existent','it-l10n-backupbuddy' ) );
				
				}
			
			}
			
			// If we got this far then no method to list backup content was available or worked
			return false;
			
		} // End get_file_list().
		
		
								
		/*	available_zip_methods()
		 *	
		 *	Test availability of zip methods to determine which exist and actually work.
		 *	Detects the available zipping methods on this server. Tests command line zip via exec(), PHP's ZipArchive, or emulated zip via the PHP PCLZip library.
		 *	TODO: Actually test unzipping in unzip mode not just zipping and assuming the other will work
		 *	
		 *	@param		boolean		$return_best	
		 *	@param		string		$mode			Possible values: zip, unzip
		 *	@return		array						Possible return values: exec, ziparchive, pclzip
		 */
		function available_zip_methods( $return_best = true, $mode = 'zip' ) {
			$return = array();
			$test_file = $this->_tempdir . 'temp_' . uniqid() . '.zip';
			
			// Test command-line ZIP.
			if ( function_exists( 'exec' ) ) {
				$command = 'zip';
				$run_exec_zip_test = true;
				
				// Handle windows.
				if ( stristr( PHP_OS, 'WIN' ) && !stristr( PHP_OS, 'DARWIN' ) ) { // Running Windows. (not darwin)
					if ( file_exists( ABSPATH . 'zip.exe' ) ) {
						$command = ABSPATH . $command;
					}
					// If unzip mode and unzip.exe is found then assume we have that option for unzipping since we arent actually testing unzip.
					if ( $mode == 'unzip' ) {
						$run_exec_zip_test = false;
						if ( file_exists( ABSPATH . 'unzip.exe' ) ) {
							array_push( $return, 'exec' );
						}
					}
					
					$exec_paths = array( '' );
				} else { // *NIX system.
					$exec_paths = array( '', '/usr/bin/', '/usr/local/bin/', '/usr/local/sbin/', '/usr/sbin/', '/sbin/', '/bin/' ); // Include preceeding & trailing slash.
				}
				
				if ( $run_exec_zip_test === true ) {
					// Possible locations to find the ZIP executable. Start with a blank string to attempt to run in current directory.
					
					pb_backupbuddy::status( 'details', 'Trying exec() in the following paths: `' . implode( ',', $exec_paths ) . '`' );
					
					$exec_completion = false; // default state.
					while( $exec_completion === false ) { // Check all possible zip path locations starting with current dir. Usually the path is set to make this work without hunting.
						if ( empty( $exec_paths ) ) {
							$exec_completion = true;
							pb_backupbuddy::status( 'error', __( 'Exhausted all known exec() path possibilities with no success.', 'it-l10n-backupbuddy' ) );
							break;
						}
						$path = array_shift( $exec_paths );
						pb_backupbuddy::status( 'details', __( 'Trying exec() ZIP path:', 'it-l10n-backupbuddy' ) . ' `' . $path . '`.' );
						
						$exec_command = $path . $command . ' "' . $test_file . '" "' . __FILE__ . '"  2>&1'; //  2>&1 to redirect STRERR to STDOUT.
						pb_backupbuddy::status( 'details', 'Zip test exec() command: `' . $exec_command . '`' );
						list( $exec_output, $exec_exit_code ) = $this->_commandbuddy->execute( $exec_command );
						
						if ( ( !file_exists( $test_file ) ) || ( $exec_exit_code == '-1' ) ) { // File not made or error returned.
							$exec_completion = false;
							
							if ( $exec_exit_code == '-1' ) {
								pb_backupbuddy::status( 'details', __( 'Exec command returned -1.', 'it-l10n-backupbuddy' ) );
							}
							if ( !file_exists( $test_file ) ) {
								pb_backupbuddy::status( 'details', __( 'Exec command ran but ZIP file did not exist.', 'it-l10n-backupbuddy' ) );
							}
							if ( file_exists( $test_file ) ) { // If file was somehow created, do cleanup on it.
								pb_backupbuddy::status( 'details', __( 'Cleaning up damaged ZIP file. Issue #3489328998.', 'it-l10n-backupbuddy' ) );
								unlink( $test_file );
							}
						} else { // Success.
							$exec_completion = true;
							
							if ( !unlink( $test_file ) ) {
								echo sprintf( __( 'Error #564634. Unable to delete test file (%s)!', 'it-l10n-backupbuddy' ), $test_file );
							}
							array_push( $return, 'exec' );
							$this->_execpath = $path;
							
							break;
						}
					} // end while
				} // End $run_exec_test === true.
			} // End function_exists( 'exec' ).
			
			// Test ZipArchive
			if ( class_exists( 'ZipArchive' ) ) {
				if ( $this->test_ziparchive() === true ) {
					array_push( $return, 'ziparchive' );
				}
			}
			
			// Test PCLZip
			if ( class_exists( 'PclZip' ) ) { // Class already loaded.
				array_push( $return, 'pclzip' );
			} else { // Class not loaded. Seek it out.
				
				if ( file_exists( ABSPATH . 'wp-admin/includes/class-pclzip.php' ) ) { // Inside WP.
					require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
					array_push( $return, 'pclzip' );
				} elseif ( file_exists( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' ) ) { // ImportBuddy.
					require_once( pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
					array_push( $return, 'pclzip' );
				}
				
			}
			
			return $return;
		} // End available_zip_methods().
		
		
		
		// Recursively delete a directory and all content within.
		function delete_directory_recursive( $directory ) {
			$directory = preg_replace( '|[/\\\\]+$|', '', $directory );
			
			$files = glob( $directory . '/*', GLOB_MARK );
			if ( is_array( $files ) && !empty( $files ) ) {
				foreach( $files as $file ) {
					if( '/' === substr( $file, -1 ) )
						$this->delete_directory_recursive( $file );
					else
						unlink( $file );
				}
			}
			
			if ( is_dir( $directory ) ) rmdir( $directory );
			
			if ( is_dir( $directory ) )
				return false;
			return true;
		} // End delete_directory_recursive().
		
		
		
		function set_zip_methods( $methods ) {
			$this->_zip_methods = $methods;
		} // End set_zip_methods().
		
		
		
		/*	_render_exclusions_file()
		 *	
		 *	function description
		 *	
		 *	@param		string		$file			File to write exclusions into.
		 *	@param		array		$exclusions		Array of directories/paths to exclude. One per line.
		 *	@return		null
		 */
		public function _render_exclusions_file( $file, $exclusions ) {
		
			// Array for cleaned up exclusions list
			$sanitized_exclusions = array();
			
			pb_backupbuddy::status( 'details', 'Creating backup exclusions file `' . $file . '`.' );
			//$exclusions = backupbuddy_core::get_directory_exclusions();
			
			// Test each exclusion for validity (presence) and drop those not actually present
			foreach( $exclusions as $exclusion ) {
				
				// Make sure platform specific directory separators are used (could have migrated from different platform)
				$exclusion = preg_replace( '|[' . addslashes( self::DIRECTORY_SEPARATORS ) . ']+|', DIRECTORY_SEPARATOR, $exclusion );
				
				// DIRECTORY.
				if ( is_dir( ABSPATH . ltrim( $exclusion, DIRECTORY_SEPARATOR ) ) ) {
					
					pb_backupbuddy::status( 'details', 'Excluding directory `' . $exclusion . '`.' );
					
					// Need to add the wildcard so that zip will exclude the directory and content
					$exclusion = rtrim( $exclusion, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . '*';
				
				// FILE.
				} elseif ( is_file( ABSPATH . ltrim( $exclusion, DIRECTORY_SEPARATOR ) ) ) {
					
					pb_backupbuddy::status( 'details', 'Excluding file `' . $exclusion . '`.' );
				
				// SYMBOLIC LINK.
				} elseif ( is_link( ABSPATH . ltrim( $exclusion, DIRECTORY_SEPARATOR ) ) ) {
					
					pb_backupbuddy::status( 'details', 'Excluding symbolic link `' . $exclusion . '`.' );
				
				// DOES NOT EXIST.
				} else {
					
					pb_backupbuddy::status( 'details', 'Omitting exclusion as file/directory does not currently exist: `' . $exclusion . '`.' );
					
					// Skip to next exclusion
					continue;
					
				}
				
				// We have a valid exclude so add it
				$sanitized_exclusions[] = $exclusion;
				
			}
			
			// Put the exclusions to a file as a string
			file_put_contents( $file, implode( PHP_EOL, $sanitized_exclusions ) . PHP_EOL );
			pb_backupbuddy::status( 'details', 'Backup exclusions file created.' );
			
		} // End render_exclusions_file().
		
		
		
	} // End class
	
}
?>