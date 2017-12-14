<?php
/**
 *	pluginbuddy_zbzipziparchive Class
 *
 *  Extends the zip capability core class with proc specific capability
 *	
 *	Version: 1.0.0
 *	Author:
 *	Author URI:
 *
 *	@param		$parent		object		Optional parent object which can provide functions for reporting, etc.
 *	@return		null
 *
 */
if ( !class_exists( "pluginbuddy_zbzipziparchive" ) ) {

	/**
	 *	pluginbuddy_ZipArchive Class
	 *
	 *	Wrapper for ZipArchive to handle error situations and provide additional functions
	 *
	 *	@param	none
	 *	@return	null
	 *
	 */
	class pluginbuddy_ZipArchive {

        /**
         * The created ZipArchive object if it can be created
         * 
         * @var $_za 	object
         */
		private $_za = NULL;
		
		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	TODO: Consider having a "suppress warnings" parameter to determine whether methods
		 *	should be invoked with warnings suppressed or not. For is_available() usage we would
		 *	want to so as not to potentially flood the PHP error log. For other functions that
		 *	are not called frequently we might not want to suppress the warnings.
		 *	
		 *	@param		none
		 *	@return		null
		 *
		 */
		public function __construct() {
			
			if ( class_exists( 'ZipArchive', false ) ) {
			
				// It's available so create the private instance
				$this->_za = new ZipArchive();
				
			} else {
			
				// Not available so throw the exception for the caller to handle
				throw new Exception( 'ZipArchive class does not exist.' );
			
			}
			
			return;
		
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Default destructor.
		 *	
		 *	@return		null
		 *
		 */
		public function __destruct() {
		
			if ( NULL != $this->_za ) { unset ( $this->_za ); }
			
			return;
		
		}
		
		/**
		 *	__call()
		 *	
		 *	Magic method intercepting calls to unknown methods. This allows us to intercept
		 *	all method calls and add additional processing
		 *	
		 *	@param		string	$method		The name of the intercepted method
		 *	@param		array	$arguments	Array of the arguments associated with the method call
		 *	@return		mixed	$result		Whatever the invoked wrapper method call returns
		 *
		 */
		public function __call( $method, $arguments ) {
		
			$result = false;
		
			$result = @call_user_func_array( array( $this->_za, $method ), $arguments );
			
			return $result;
		
		}
		
		/**
		 *	__get()
		 *	
		 *	Magic method intercepting calls to unknown properties. This means we have to
		 *	provide the properties of the wrapped object but that's ok as there aren't many.
		 *	Note: Maybe we can get the object properties and automate this?
		 *	
		 *	@param		string	$name		The name of the property
		 *	@return		mixed	$result		Whatever the wrapped object property returns
		 *
		 */
		public function __get( $name ) {
		
			switch ( $name ) {
			
				case "comment":
				
					$result = $this->_za->comment;
					break;
				
				case "numFiles":
				
					$result = $this->_za->numFiles;
					break;
				
				case "filename":
				
					$result = $this->_za->filename;
					break;
				
				case "status":
				
					$result = $this->_za->status;
					break;
					
				case "statusSys":
				
					$result = $this->_za->statusSys;
					break;
					
				default:
				
					// Hmm, not quite sure what we should return here...
					$result = false;
	
			}
			
			return $result;
		
		}
	
		/**
		 *	errorInfo()
		 *	
		 *	Translate a ZipArchive error code into an informative string description
		 *	and returns the string. An error number can be passed in, otherwise will
		 *	get the status property and use that (if not indicating no error) or the
		 *	statusSys property and use that (if not indicating no error). In the case
		 *	of the statusSys property will get the error string from the getStatusString()
		 *	method, otherwise for status property or passed in value use the mappings
		 *	below.
		 *	Note: This does mean that a statusSys error no should not be passed in
		 *	presently. In future may choose to use an offset mapping to handle that.
		 *	
		 *	@param		integer	$error	Optional: The error code
		 *	@param		bool	$full	Optional: True to provide a name/value/description string
		 *	@return		string			Informative error string
		 *
		 */
		public function errorInfo( $error = PHP_INT_MAX, $full = true ) {
		
			// Get statusSys property value in case we need it
			$error_sys = $this->_za->statusSys;
			
			// Check whether we have been given or need to get a status value
			if ( PHP_INT_MAX == $error) {
			
				// No error number passed in, lets get one
				$error = $this->_za->status;
			
			}
			
			if ( ( ZIPARCHIVE::ER_OK == $error ) && ( 0 < $error_sys ) ) {
			
				// No basic error AND we have a system error
				$error_string = "ZLIB" . "(" . $error_sys . ") : " . $this->_za->getStatusString();
			
			} else {
			
				$error_name = '';
				$error_description = '';
			
				// It's either a basic error OR no system error
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
				
			}
			
			// One way or another we have a string to return
			return $error_string;
		
		}
		
	}

	class pluginbuddy_zbzipziparchive extends pluginbuddy_zbzipcore {
	
        /**
         * method tag used to refer to the method and entities associated with it such as class name
         * 
         * @var $_method_tag 	string
         */
		public static $_method_tag = 'ziparchive';
			
        /**
         * This tells us whether this method is regarded as a "compatibility" method
         * 
         * @var bool
         */
		public static $_is_compatibility_method = false;
			
        /**
         * This tells us the dependencies of this method so they can be check to see if the method can be supported
         * 
         * @var array
         */
		public static $_method_dependencies = array( 'classes' => array( 'ZipArchive' ),
											  		 'functions' => array(),
											  		 'extensions' => array(),
											  		 'files' => array()
													);
			
		/**
		 * 
		 * get_method_tag_static()
		 *
		 * Get the static method tag in a static context
		 *
		 * @return		string	The method tag
		 *
		 */
		public static function get_method_tag_static() {
		
			return self::$_method_tag;
			
		}

		/**
		 * 
		 * get_is_compatibility_method_static()
		 *
		 * Get the compatibility method indicator in a static context
		 *
		 * @return		bool	True if is a compatibility method
		 *
		 */
		public static function get_is_compatibility_method_static() {
		
			return self::$_is_compatibility_method;
		}

		/**
		 * 
		 * get_method_dependencies_static()
		 *
		 * Get the method dependencies array in a static context
		 *
		 * @return		array	The dependencies of the method that is requires to be a supported method
		 *
		 */
		public static function get_method_dependencies_static() {
		
			return self::$_method_dependencies;
		}

		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	
		 *	@param		reference	&$parent		[optional] Reference to the object containing the status() function for status updates.
		 *	@return		null
		 *
		 */
		public function __construct( &$parent = NULL ) {

			parent::__construct( $parent );
			
			// Override some of parent defaults
			$this->_method_details[ 'attr' ] = array_merge( $this->_method_details[ 'attr' ],
															array( 'name' => 'ZipArchive Method',
													  			   'compatibility' => pluginbuddy_zbzipziparchive::$_is_compatibility_method )
													  	   );

			// No relevant parameters for this method
			$this->_method_details[ 'param' ] = array();
			
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Default destructor.
		 *	
		 *	@return		null
		 *
		 */
		public function __destruct( ) {
		
			parent::__destruct();

		}
		
		/**
		 *	get_method_tag()
		 *	
		 *	Returns the (static) method tag
		 *	
		 *	@return		string The method tag
		 *
		 */
		public function get_method_tag() {
		
			return pluginbuddy_zbzipziparchive::$_method_tag;
			
		}
		
		/**
		 *	get_is_compatibility_method()
		 *	
		 *	Returns the (static) is_compatibility_method boolean
		 *	
		 *	@return		bool
		 *
		 */
		public function get_is_compatibility_method() {
		
			return pluginbuddy_zbzipziparchive::$_is_compatibility_method;
			
		}
		
		/**
		 *	is_available()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *
		 *  Note: in this case as the zip and unzip capabilities are all wrapped up in the same class then if we
		 *  can zip then we'll assume (for now) that we can unzip as well so attributes are set accordingly.
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		public function is_available( $tempdir ) {
		
			$result = false;
			$za = NULL;
			
			$test_file = $tempdir . 'temp_test_' . uniqid() . '.zip';
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ZipArchive test FAILED: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;

			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// This returns true on success, false for some error scenarios or an error code
				// If it return false we don't really know why - there may have been a Warning generated
				// but we need to suppress warnings because of the frequency this function is called
				// so we can only indicate a general failure
				$res = $za->open( $test_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE );
				
				if ( true === $res ) {
				
					if ( !$za->addFile( __FILE__, 'this_is_a_test.txt') ) {
					
						pb_backupbuddy::status( 'details',  __('ZipArchive test FAILED: Unable to add file to zip file.','it-l10n-backupbuddy' ) );
						$error_string = $za->errorInfo();
						pb_backupbuddy::status( 'details',  __('ZipArchive Error: ','it-l10n-backupbuddy' ) . $error_string );
						
					}
					
					if ( !$za->close() ) {
					
						pb_backupbuddy::status( 'details',  __('ZipArchive test FAILED: Problem creating/closing zip file.','it-l10n-backupbuddy' ) );
						$error_string = $za->errorInfo();
						pb_backupbuddy::status( 'details',  __('ZipArchive Error: ','it-l10n-backupbuddy' ) . $error_string );
						
					}
					
					if ( @file_exists( $test_file ) ) {
					
						if ( !@unlink( $test_file ) ) {
					
							pb_backupbuddy::status( 'details', 'Error #564634. Unable to delete test file `' . $test_file . '`.' );
						
						}
					
						// The zip operation was successful - implies can zip and unzip and hence check and list
						// Note: we actually don't want to do archiving with this method yet
						$this->_method_details[ 'attr' ][ 'is_zipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_unzipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_checker' ] = true;
						$this->_method_details[ 'attr' ][ 'is_lister' ] = true;
						$this->_method_details[ 'attr' ][ 'is_commenter' ] = true;
						$this->_method_details[ 'attr' ][ 'is_unarchiver' ] = true;
						$this->_method_details[ 'attr' ][ 'is_extractor' ] = true;
						
						pb_backupbuddy::status( 'details', __('ZipArchive test PASSED.','it-l10n-backupbuddy' ) );
						$result = true;
						
					} else {
					
						pb_backupbuddy::status( 'details', __('ZipArchive test FAILED: Zip file not found.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
					
				} else {
				
					pb_backupbuddy::status( 'details',  __('ZipArchive test FAILED: Unable to create/open zip file.','it-l10n-backupbuddy' ) );
					
					// If we got an error code (rather than simply a false failure indication) then translate it
					// It seems that in these cases the internal status doesn't indicate anything so we cannot use that
					if ( false !== $res ) {
					
						$error_string = $za->errorInfo( $res );
						pb_backupbuddy::status( 'details',  __('ZipArchive Error: ','it-l10n-backupbuddy' ) . $error_string );
					
					}
					
					$za->close();
					
					$result = false;
					
				}
				
			}
		  	
		  	if ( NULL != $za ) { unset( $za ); }
		  	
		  	return $result;
		  	
		}
		
		/**
		 *	create()
		 *	
		 *	A function that creates an archive file
		 *	
		 *	The $excludes will be a list or relative path excludes if the $listmaker object is NULL otehrwise
		 *	will be absolute path excludes and relative path excludes can be had from the $listmaker object
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to create
		 *	@param		string	$dir			Full path of directory to add to ZIP Archive file
		 *	@parame		array	$excludes		List of either absolute path exclusions or relative exclusions
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@param		object	$listmaker		The object from which we can get an inclusions list
		 *	@return		bool					True if the creation was successful, false otherwise
		 *
		 */
		public function create( $zip, $dir, $excludes, $tempdir, $listmaker = NULL ) {
		
			pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for backup.','it-l10n-backupbuddy' ) );
			return false;
		
		}
		
		/**
		 *	extract()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *	If no specific items given to extract then it's a complete unzip
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@param	array		$items						Mapping of what to extract and to what
		 *	@return	bool									true on success (all extractions successful), false otherwise
		 */
		public function extract( $zip_file, $destination_directory = '', $items = array() ) {
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					if ( empty( $items ) ) {
						$result = $this->extract_generic_full( $zip_file, $destination_directory );
					} else {
						$result = $this->extract_generic_selected( $zip_file, $destination_directory, $items );					
					}
					break;
				case self::OS_TYPE_WIN:
					if ( empty( $items ) ) {
						$result = $this->extract_generic_full( $zip_file, $destination_directory );
					} else {
						$result = $this->extract_generic_selected( $zip_file, $destination_directory, $items );					
					}
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}

		/**
		 *	extract_generic_full()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@return	bool									true on success, false otherwise
		 */
		protected function extract_generic_full( $zip_file, $destination_directory = '' ) {
		
			$result = false;
			$za = NULL;
			$stat = array();
			
			// This should give us a new archive object, if not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ziparchive indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				$result = $za->open( $zip_file );
				
				// Make sure we opened the zip ok
				if ( true === $result ) {
				
					// How many files - could be 0 if we had an empty zip file
					$file_count = $za->numFiles;
					
					// Only returns true for success or false for failure - no indication of why failed
					$result = $za->extractTo( $destination_directory );
									
					// Currently we can only distinguish between success and failure but no finer grain
					if ( true === $result ) {
					
						pb_backupbuddy::status( 'details', sprintf( __('ziparchive extracted file contents (%1$s to %2$s)','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );

					} else {
					
						$error_string = $za->errorInfo();
						pb_backupbuddy::status( 'details', sprintf( __('ziparchive failed to extract file contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );
					
						// May seem redundant but belt'n'braces
						$result = false;
					}
					
					$this->log_archive_file_stats( $zip_file );
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( $result );
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
				
				$za->close();
			
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
						
		}

		/**
		 *	extract_generic_selected()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@param	array		$items						Mapping of what to extract and to what
		 *	@return	bool									true on success (all extractions successful), false otherwise
		 */
		protected function extract_generic_selected( $zip_file, $destination_directory = '', $items ) {
		
			$result = false;
			$za = NULL;
			
			// This should give us a new archive object, if not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ziparchive indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				$result = $za->open( $zip_file );
				
				// Make sure we opened the zip ok
				if ( true === $result ) {
				
					// Now we need to take each item and run an unzip for it - unfortunately there is no easy way of combining
					// arbitrary extractions into a single command if some might be to a 
					foreach ( $items as $what => $where ) {
			
						$rename_required = false;
						$result = false;
				
						// Decide how to extract based on where
						if ( empty( $where) ) {
					
							// First we'll extract and then junk the path
							$result = $za->extractTo( $destination_directory, $what );
								
							// Unlike exec zip we have to effectively junk the path after the extraction
							// Do this by renaming the file to the destination directory and then getting rid of any directory
							// structure it was under. If dirname is not . then we know there is a directry path and not
							// just a simple file name (remember that $what should _not_ have any leading slash whether
							// it is a filepath or a simple filename)
							if ( "." != dirname( $what ) ) {
							
								rename( $destination_directory . DIRECTORY_SEPARATOR . $what,
										$destination_directory . DIRECTORY_SEPARATOR . basename( $what) );
										
								// Get the path component of $what - note that dirname() adds a leading slash
								// even if none was present originally. We must get the first directory component only
								// so we can do a recursive delete on it. This is a bit klunky but functional.
								$whatpath = $what;
								do {
									$whatpath = dirname( $whatpath );
								} while ( 1 < strlen( dirname( $whatpath ) ) );

								// Now we can do the recursive delete from that top level component
								$this->delete_directory_recursive( $destination_directory . $whatpath );

							}
															
					
						} elseif ( !empty( $where ) ) {
					
							if ( $what === $where ) {
							
								// Check for wildcard directory extraction like dir/* => dir/*
								if ( "*" == substr( trim( $what ), -1 ) ) {
								
									// Get our path match string (just clip off the wildcard)
									$whatroot = substr( trim( $what ), 0, -1 );
									$file_count = $za->numFiles;
								
									// Crikey, it's a directory tree extraction - don't panic
									// We need to go through the whole zip and extract each file that matches
									for ( $i = 0; $i < $file_count; $i++ ) {
									
										// Get the filename by index and see if it's in the tree
										$filename = $za->getNameIndex( $i );
										if ( 0 === strpos( $filename, $whatroot ) ) {
										
											// $what matched the root of this filename so extract it
											$result = $za->extractTo( $destination_directory, $filename );

											if ( false === $result ) {
												
												// An extraction failed so bail out here - this should just
												// drop us through to the post-processing of $result which on
												// a false should then drop us out of the foreach loop
												break;
												
											}
										
										}
									
									}
								
								} else {
								
									// It's just a single file extraction - breath a sign of relief
									// Extract to same directory structure - don't junk path, no need to add where to destnation as automatic
									$result = $za->extractTo( $destination_directory, $what );

								}
						
							} else {
						
								// First we'll extract and then junk the path
								$result = $za->extractTo( $destination_directory, $what );
								
								// Unlike exec zip we have to effectively junk the path after the extraction
								// Do this by renaming the file to the destination directory and then getting rid of any directory
								// structure it was under. If dirname is not . then we know there is a directry path and not
								// just a simple file name (remember that $what should _not_ have any leading slash whether
								// it is a filepath or a simple filename)
								if ( "." != dirname( $what ) ) {
								
									rename( $destination_directory . DIRECTORY_SEPARATOR . $what,
											$destination_directory . DIRECTORY_SEPARATOR . basename( $what) );
											
									// Get the path component of $what - note that dirname() adds a leading slash
									// even if none was present originally. We must get the first directory component only
									// so we can do a recursive delete on it. This is a bit klunky but functional.
									$whatpath = $what;
									do {
										$whatpath = dirname( $whatpath );
									} while ( 1 < strlen( dirname( $whatpath ) ) );

									// Now we can do the recursive delete from that top level component
									$this->delete_directory_recursive( $destination_directory . $whatpath );

								}
															
								// Will need to rename if the extract is ok
								$rename_required = true;
						
							}
					
						}
				
						// Note: we don't open the file and then do stuff but it's all done in one action
						// so we need to interpret the return code to dedide what to do
						// Currently we can only distinguish between success and failure but no finer grain
						if ( true === $result ) {
					
							pb_backupbuddy::status( 'details', sprintf( __('ziparchive extracted file contents (%1$s from %2$s to %3$s%4$s)','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where ) );

							// Rename if we have to
							if ( true === $rename_required) {
							
								// Note: we junked the path on the extraction so just the filename of $what is the source but
								// $where could be a simple file name or a file path 
								$result = $result && rename( $destination_directory . DIRECTORY_SEPARATOR . basename( $what ),
															 $destination_directory . DIRECTORY_SEPARATOR . $where );
							
							}

						} else {
					
							// For now let's just print the error code and drop through
							$error_string = $za->errorInfo();
							pb_backupbuddy::status( 'details', sprintf( __('ziparchive failed to open/process file to extract file contents (%1$s from %2$s to %3$s%4$s) - Error Info: %5$s.','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where, $error_string ) );
					
							// May seem redundant but belt'n'braces
							$result = false;
							
						}
					
						// If the extraction failed (or rename after extraction) then break out of the foreach and simply return false
						if ( false === $result ) {
					
							break;
						
						}
					
					}
				
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( $result );
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
				
				$za->close();
			
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
						
		}
		
		/**
		 *	file_exists()
		 *	
		 *	Tests whether a file (with path) exists in the given zip file
		 *	If leave_open is true then the zip object will be left open for faster checking for subsequent files within this zip
		 *	
		 *	@param		string	$zip_file		The zip file to check
		 *	@param		string	$locate_file	The file to test for
		 *	@param		bool	$leave_open		Optional: True if the zip file should be left open
		 *	@return		bool/array				True if the file is found in the zip and false if not, array for other problem
		 *
		 */
		public function file_exists( $zip_file, $locate_file, $leave_open = false ) {
		
			$result = array( 1, "Generic failure indication" );
			$za = NULL;
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ziparchive indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

				// Return an error code and a description - this needs to be handled more generically
				$result = array( 1, "Class not available to match method" );
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				$result = $za->open( $zip_file );
				
				// Make sure we opened the zip ok
				if ( true === $result ) {
				
					// Now try and find the index of the file
					$index = $za->locateName( $locate_file );
					
					// If we got an index we found it otherwise not found
					if ( false !== $index ) {
					
						pb_backupbuddy::status( 'details', __('File found (ziparchive)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						$result = true;
						
					} else {
					
						pb_backupbuddy::status( 'details', __('File not found (ziparchive)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						$result = false;
						
					}
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( $result );
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					$result = array( 1, "Failed to open/process file" );

				}
			
				// We have finished with the archive (leave_open ignored for now)
				$za->close();
				
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
				
		}
		
		/*	get_file_list()
		 *	
		 *	Get an array of all files in a zip file with some file properties.
		 *	
		 *	@param		string		$zip_file	The file to list the content of
		 *	@return		bool|array				false on failure, otherwise array of file properties (may be empty)
		 */
		public function get_file_list( $zip_file ) {
		
			$file_list = array();
			$result = false;
			$za = NULL;
			$stat = array();
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ziparchive indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				$result = $za->open( $zip_file );
				
				// Make sure we opened the zip ok
				if ( true === $result ) {
				
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
					
					
					pb_backupbuddy::status( 'details', sprintf( __('ziparchive listed file contents (%1$s)','it-l10n-backupbuddy' ), $zip_file ) );

					$this->log_archive_file_stats( $zip_file );	

					$result = &$file_list;
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( $result );
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to list contents (%1$s) - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
				
				$za->close();
			
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
			
		}
		
		/*	set_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		bool							true on success, otherwise false.
		 */
		public function set_comment( $zip_file, $comment ) {
		
			$result = false;
			$za = NULL;
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ziparchive indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				$result = $za->open( $zip_file );
				
				// Make sure at least the zip file opened ok
				if ( true === $result ) {
				
					// Set the comment - true on success, false on failure
					$result = $za->setArchiveComment( $comment );
					
					// If we got back true then all is well with the world
					if ( true === $result ) {
					
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
						$result = true;
						
					} else {
					
						// If we failed to set the commnent then log it (?) and drop through
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
						$result = false;
						
					}
						
				} else {
				
					// If we couldn't open the zip file then log it (?) and drop through
					$error_string = $za->errorInfo( $result );
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to set comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					$result = false;
										
				}
								
				$za->close();
				
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
			
		}

		/*	get_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		bool|string					false on failure, Zip comment otherwise.
		 */
		public function get_comment( $zip_file ) {
		
			$result = false;
			$za = NULL;
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_ZipArchive();
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('ziparchive indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				$result = $za->open( $zip_file );
				
				// Make sure at least the zip file opened ok
				if ( true === $result ) {
				
					// Get the comment or false on failure for some reason
					// Note: Currently, due to a bug in ZipArchive, getArchiveComment()
					// returns false for an empty comment whereas it should just return an
					// empty string. We'll live with this for now as it should only happen
					// when an archive is fresh and has the Integrity Check run (or when the
					// check is rerun). Once a comment is added the function behaves.
					// If any problems are thrown up then there is the option to use the
					// archive property but that has a downside in that it can only ever
					// return a string so if there really is an error in reading the comment
					// it is not possible to know (AFAIK). Perhaps an error status value might
					// be set somewhere?
					// The bug will be reported to PHP developers but we will still have to
					// live with this for a while because it takes hosts ages to catch up to
					// updated PHP versions.
					$comment = $za->getArchiveComment( ZIPARCHIVE::FL_UNCHANGED );
					//$comment = $za->comment;
					
					// If we have a comment (even if empty) then return it
					if ( false !== $comment ) {

						// Note: new archives will return an empty comment if one was not added at creation
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
						$result = $comment;
						
					} else {
					
						// If we failed to get the commnent then log it (?) and drop through
						pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to retrieve comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
						$result = false;
						
					}

				} else {
				
					// If we couldn't open the zip file then log it (?) and drop through
					$error_string = $za->errorInfo( $result );
					pb_backupbuddy::status( 'details', sprintf( __('ZipArchive failed to open file to get comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					$result = false;
										
				}
				
				$za->close();
								
			} else {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				pb_backupbuddy::status( 'details', __('ziparchive indicated as available method but ZipArchive class non-existent','it-l10n-backupbuddy' ) );
				$result = false;
				
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
			
		}
		
	} // end pluginbuddy_zbzipziparchive class.	
	
}
?>
