<?php
/**
 *	pluginbuddy_zbzippclzip Class
 *
 *  Extends the zip capability core class with pclzip specific capability
 *	
 *	Version: 1.0.0
 *	Author:
 *	Author URI:
 *
 *	@param		$parent		object		Optional parent object which can provide functions for reporting, etc.
 *	@return		null
 *
 */
if ( !class_exists( "pluginbuddy_zbzippclzip" ) ) {

	/**
	 *	pluginbuddy_PclZip Class
	 *
	 *	Wrapper for PclZip to encapsulate the process of loading the PclZip library (if not
	 *	already loaded, which it shouldn't be generally) and also surrounding method calls
	 *	with the unpleasant workaround for the mbstring issue where things may fail because
	 *	PclZip is using string functions to process binary data and if the string functions
	 *	are overloaded with the multi-byte versions the processing can (probably will) fail.
	 *
	 *	@param	string	$zip_filename	The name of the zip file that will be managed
	 *	@return	null
	 *
	 */
	class pluginbuddy_PclZip {

        /**
         * The created PclZip object if it can be created
         * 
         * @var $_za 	object
         */
		private $_za = NULL;
		
		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	This is used to try and load the PclZip library and then create an instance of
		 *	an archive with that. If the library cannot be made available then an exception
		 *	is thrown and that is handled by the caller.
		 *	TODO: Consider having a "suppress warnings" parameter to determine whether methods
		 *	should be invoked with warnings suppressed or not. For is_available() usage we would
		 *	want to so as not to potentially flood the PHP error log. For other functions that
		 *	are not called frequently we might not want to suppress the warnings.
		 *	
		 *	@param		string	$zip_filename	The name of the zip file that will be managed
		 *	@return		null
		 *
		 */
		public function __construct( $zip_filename ) {
		
			// The PclZip class has to be available for us so let's have a go
			// Note: it is not required because nothing will break without it but the method will 
			// simply not be available
			// This may seem laborious but it's robust against include_once not playing nice if the
			// class is already included and trying to include it again
			if ( !@class_exists( 'PclZip', false ) ) {
			
				$possibles = array( ABSPATH . 'wp-admin/includes/class-pclzip.php', pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
				
				foreach ( $possibles as $possible) {
				
					if ( @is_readable( $possible ) ) {
					
						// Found one that should be loadable so try it and then break out
						pb_backupbuddy::status( 'details', 'PCLZip class not found. Attempting to load from `' . $possible . '`.' );
						@include_once( $possible );
						break;
										
					} 
				
				}

			}
			
			// By now PclZip _should_ be available so let's see...
			if ( @class_exists( 'PclZip', false ) ) {
			
				// It's available so create the private instance
				$this->_za = new PclZip( $zip_filename );
				
			} else {
			
				// Not available so throw the exception for the caller to handle
				throw new Exception( 'PclZip class does not exist.' );
			
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
		
			// See #15789 - PclZip uses string functions on binary data
			// If it's overloaded with Multibyte safe functions the results are incorrect.
			if ( @ini_get( 'mbstring.func_overload' ) && @function_exists( 'mb_internal_encoding' ) ) {
			
				$previous_encoding = @mb_internal_encoding();
				@mb_internal_encoding( 'ISO-8859-1' );
				
			}
		
			$result = @call_user_func_array( array( $this->_za, $method ), $arguments );
			
			// Now undo any change we may have made to the encoding
			if ( isset( $previous_encoding ) ) {
			
				@mb_internal_encoding( $previous_encoding );
				unset( $previous_encoding );

			}
		
			return $result;
		
		}
	
	}

	class pluginbuddy_zbzippclzip extends pluginbuddy_zbzipcore {
	
		// Constants for file handling
		const ZIP_ERRORS_FILE_NAME   = 'last_pclzip_errors.txt';
		const ZIP_WARNINGS_FILE_NAME = 'last_pclzip_warnings.txt';
		const ZIP_OTHERS_FILE_NAME   = 'last_pclzip_others.txt';
		const ZIP_CONTENT_FILE_NAME  = 'last_pclzip_list.txt';
		
        /**
         * method tag used to refer to the method and entities associated with it such as class name
         * 
         * @var $_method_tag 	string
         */
		public static $_method_tag = 'pclzip';
			
        /**
         * This tells us whether this method is regarded as a "compatibility" method
         * 
         * @var bool
         */
		public static $_is_compatibility_method = true;
			
        /**
         * This tells us the dependencies of this method so they can be check to see if the method can be supported
         * Note: PclZip constructor checks for gzopen function and dies on failure so we may as well pre-empt that
         * 
         * @var array
         */
		public static $_method_dependencies = array( 'classes' => array(),
											  		 'functions' => array( 'gzopen' ),
											  		 'extensions' => array( ),
											  		 'files' => array(),
											  		 'check_func' => 'check_method_dependencies_static'
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
		 * 
		 * check_method_dependencies_static()
		 *
		 * Allows additional method dependency checks beyond the standard in a static context
		 *
		 * @return		bool	True if additional dependency checks passed
		 *
		 */
		public static function check_method_dependencies_static() {
		
			$result = false;
			
			// Need to verify that at least PclZip should be available to be loaded (but we
			// don't actually want to load it here)
			$possibles = array( ABSPATH . 'wp-admin/includes/class-pclzip.php', pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
			
			foreach ( $possibles as $possible) {
			
				if ( @is_readable( $possible ) ) {
				
					// Found one that should be loadable so break out
					$result = true;
					break;
									
				} 
			
			}
			
			return $result;
		
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
															array( 'name' => 'PclZip Method',
													  			   'compatibility' => pluginbuddy_zbzippclzip::$_is_compatibility_method )
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
		
			return pluginbuddy_zbzippclzip::$_method_tag;
			
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
		
			return pluginbuddy_zbzippclzip::$_is_compatibility_method;
			
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
			
				$za = new pluginbuddy_PclZip( $test_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('PclZip test FAILED: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;

			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				if ( $za->create( __FILE__ , PCLZIP_OPT_REMOVE_PATH, dirname( __FILE__)  ) !== 0 ) {
						
					if ( @file_exists( $test_file ) ) {
					
						if ( !@unlink( $test_file ) ) {
					
							pb_backupbuddy::status( 'details', sprintf( __('Error #564634. Unable to delete test file (%s)!','it-l10n-backupbuddy' ), $test_file ) );
						
						}
						
						// The zip operation was successful - implies can zip and unzip and hence archive, check and list
						$this->_method_details[ 'attr' ][ 'is_zipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_unzipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_archiver' ] = true;
						$this->_method_details[ 'attr' ][ 'is_checker' ] = true;
						$this->_method_details[ 'attr' ][ 'is_lister' ] = true;
						$this->_method_details[ 'attr' ][ 'is_commenter' ] = true;
						$this->_method_details[ 'attr' ][ 'is_unarchiver' ] = true;
						$this->_method_details[ 'attr' ][ 'is_extractor' ] = true;
						
						pb_backupbuddy::status( 'details', __('PclZip test PASSED.','it-l10n-backupbuddy' ) );
						$result = true;
						
					} else {
					
						pb_backupbuddy::status( 'details', __('PclZip test FAILED: Zip file not found.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
					
				} else {
				
					$error_string = $za->errorInfo( true );
					pb_backupbuddy::status( 'details', __('PclZip test FAILED: Unable to create/open zip file.','it-l10n-backupbuddy' ) );
					pb_backupbuddy::status( 'details', __('PclZip Error: ','it-l10n-backupbuddy' ) . $error_string );
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
		
			$za = NULL;
			$result = false;
			$exitcode = 0;
			$zip_output = array();
			$temp_zip = '';
			$excluding_additional = false;
			$exclude_count = 0;
			$exclusions = array();
			$temp_file_compression_threshold = 5;
			$pre_add_func = '';
			$have_zip_errors = false;
			$zip_errors_count = 0;
			$zip_errors = array();
			$have_zip_warnings = false;
			$zip_warnings_count = 0;
			$zip_warnings = array();
			$have_zip_additions = false;
			$zip_additions_count = 0;
			$zip_additions = array();
			$have_zip_debug = false;
			$zip_debug_count = 0;
			$zip_debug = array();
			$have_zip_other = false;
			$zip_other_count = 0;
			$zip_other = array();
			$zip_ignoring_symlinks = false;
			$symlinks_found = array();
			
			// The basedir must have a trailing normalized directory separator
			$basedir = ( rtrim( trim( $dir ), self::DIRECTORY_SEPARATORS ) ) . self::NORM_DIRECTORY_SEPARATOR;
		
			// Normalize platform specific directory separators in path
			$basedir = str_replace( DIRECTORY_SEPARATOR, self::NORM_DIRECTORY_SEPARATOR, $basedir );
			
			// Ensure no stale file information
			clearstatcache();
			
			// Note: could enforce trailing directory separator for robustness
			if ( empty( $tempdir ) || !file_exists( $tempdir ) ) {
			
				// This breaks the rule of single point of exit (at end) but it's early enough to not be a problem
				pb_backupbuddy::status( 'details', __('Temporary working directory must be available.','it-l10n-backupbuddy' ) );				
				return false;
				
			}
			
			pb_backupbuddy::status( 'message', __('Using Compatibility Mode.','it-l10n-backupbuddy' ) );
			pb_backupbuddy::status( 'message', __('If your backup times out in Compatibility Mode try disabling zip compression in Settings.','it-l10n-backupbuddy' ) );
			
			// Update the definition before it is used by loading the library
			// This will not wok if perchance the file has already been loaded :-(
			define( 'PCLZIP_TEMPORARY_DIR', $tempdir );
			
			// Decide whether we are offering exclusions or not
			// Note that unlike proc and zip we always use inclusion if available to offer exclusion capability for pclzip
			if ( is_object( $listmaker ) ) {
				
				// Need to get the relative exclusions so we can log what is being excluded...
				$exclusions = $listmaker->get_relative_excludes( $basedir );
				
				// Build the exclusion list - first the relative directories
				if ( count( $exclusions ) > 0 ) {
				
					pb_backupbuddy::status( 'details', __('Calculating directories/files to exclude from backup (relative to site root).','it-l10n-backupbuddy' ) );
					
					foreach ( $exclusions as $exclude ) {
					
						if ( !strstr( $exclude, 'backupbuddy_backups' ) ) {
	
							// Set variable to show we are excluding additional directories besides backup dir.
							$excluding_additional = true;
								
						}
							
						pb_backupbuddy::status( 'details', __('Excluding','it-l10n-backupbuddy' ) . ': ' . $exclude );
						
						$exclude_count++;
							
					}
					
				}
				
				
				if ( true === $excluding_additional ) {
				
					pb_backupbuddy::status( 'message', __( 'Excluding archives directory and additional directories defined in settings.','it-l10n-backupbuddy' ) . ' ' . $exclude_count . ' ' . __( 'total','it-l10n-backupbuddy' ) . '.' );
					
				} else {
				
					pb_backupbuddy::status( 'message', __( 'Only excluding archives directory based on settings.','it-l10n-backupbuddy' ) . ' ' . $exclude_count . ' ' . __( 'total','it-l10n-backupbuddy' ) . '.' );
					
				}
				
				// Now get the list from the top node
				$the_list = $listmaker->get_terminals();
				
				// Retain this for reference for now
				//file_put_contents( ( dirname( $tempdir ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $the_list, true ) );
			
			} else {
		
				// We don't have the inclusion list so we are not offering exclusions
				pb_backupbuddy::status( 'message', __('WARNING: Directory/file exclusion unavailable in Compatibility Mode. Even existing old backups will be backed up.','it-l10n-backupbuddy' ) );
				$the_list = array( $dir );
			
			}
		
			// Get started with out zip object
			// Put our final zip file in the temporary directory - it will be moved later
			$temp_zip = $tempdir . basename( $zip );
			
			// This should give us a new archive object, of not catch it and bail out
			try {
					
				$za = new pluginbuddy_PclZip( $temp_zip );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
			
				// Basic argument list
				$arguments = array();
				array_push( $arguments, $the_list );
				array_push( $arguments, PCLZIP_OPT_REMOVE_PATH, $dir );				

				if ( true !== $this->get_compression() ) {
				
					// Note: don't need to force use of temporary files for compression
					pb_backupbuddy::status( 'details', __('Zip archive creation compression disabled based on settings.','it-l10n-backupbuddy' ) );
					array_push( $arguments, PCLZIP_OPT_NO_COMPRESSION );
					
				} else {
	
					// Note: force the use of temporary files for compression when file size exceeds given value.
					// This over-rides the "auto-sense" which is based on memory_limit and this _may_ indicate a
					// memory availability that is higher than reality leading to memory allocation failure if
					// trying to compress large files. Set the threshold low enough (specify in MB) so that except in
					// The tightest memory situations we should be ok. Could have option to force use of temporary
					// files regardless.
					pb_backupbuddy::status( 'details', __('Zip archive creation compression enabled based on settings.','it-l10n-backupbuddy' ) );
					array_push( $arguments, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $temp_file_compression_threshold );
						
				}
				
				// Check if ignoring (not following) symlinks
				if ( true === $this->get_ignore_symlinks() ) {
			
					// Want to not follow symlinks so set flag for later use
					$zip_ignoring_symlinks = true;
				
					pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links will be ignored based on settings.','it-l10n-backupbuddy' ) );

				} else {
			
					pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
			
				// Check if we are ignoring warnings - meaning can still get a backup even
				// if, e.g., some files cannot be read
				if ( true === $this->get_ignore_warnings() ) {
				
					// Note: warnings are being ignored but will still be gathered and logged
					pb_backupbuddy::status( 'details', __('Zip archive creation actionable warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
					
				} else {
				
					pb_backupbuddy::status( 'details', __('Zip archive creation actionable warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
				
				// Use anonymous function to weed out the unreadable and non-existent files (common reason for failure)
				// and possibly symlinks based on user settings.
				// PclZip will record these files as 'skipped' in the file status and we can post-process to determine
				// if we had any of these and hence either stop the backup or continue dependent on whether the user
				// has chosen to ignore warnings or not and/or ignore symlinks or not.
				// Unfortunately we cannot directly tag the file with the reason why it has been skipped so when we
				// have to process the skipped items we have to try and work out why it was skipped - but shouldn't
				// be too hard.
				// TODO: Consider moving this into the PclZip wrapper and have a method to set the various pre/post
				// functions or select predefined functions (such as this).
				if ( true ) {
				
					// Note: This could be simplified - it's written to be extensible but may not need to be
					$args = '$event, &$header';
					$code = '';
					$code .= 'static $symlinks = array(); ';
					$code .= '$result = true; ';
					
					// Handle symlinks - keep the two cases of ignoring/not-ignoring separate for now to make logic more
					// apparent - but could be merged with different conditional handling
					// For a valid symlink: is_link() -> true; is_file()/is_dir() -> true; file_exists() -> true
					// For a broken symlink: is_link() -> true; is_file()/is_dir() -> false; file_exists() -> false
					// Note: pclzip first tests every file using file_exists() before ever trying to add the file so
					// for a broken symlink it will _always_ error out immediately it discovers a broken symlink so
					// we never have a chance to filter these out at this stage.
					if ( true === $zip_ignoring_symlinks ) {
					
						// If it's a symlink or it's neither a file nor a directory then ignore it. A broken symlink
						// will never get this far because pclzip will have choked on it
						$code .= 'if ( ( true === $result ) && !( @is_link( $header[\'filename\'] ) ) ) { ';
						$code .= '    if ( @is_file( $header[\'filename\'] ) || @is_dir( $header[\'filename\'] ) ) { ';
						$code .= '        $result = true; ';
						$code .= '        foreach ( $symlinks as $prefix ) { ';
						$code .= '            if ( !( false === strpos( $header[\'filename\'], $prefix ) ) ) { ';
						$code .= '                $result = false; ';
						$code .= '                break; ';
						$code .= '             } ';
						$code .= '        } ';
						$code .= '    } else { ';
//						$code .= '        error_log( "Neither a file nor a directory (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '        $result = false; ';
						$code .= '    } ';
						$code .= '} else { ';
//						$code .= '    error_log( "File is a symlink (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $symlinks[] = $header[\'filename\']; ';
//						$code .= '    error_log( "Symlinks Array: \'" . print_r( $symlinks, true ) . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
						
					} else {
					
						// If it's neither a file nor directory then ignore it - a valid symlink will register as a file
						// or directory dependent on what it is pointing at. A broken symlink will never get this far.
						$code .= 'if ( ( true === $result ) && ( @is_file( $header[\'filename\'] ) || @is_dir( $header[\'filename\'] ) ) ) { ';
						$code .= '    $result = true; ';
						$code .= '} else { ';
//						$code .= '    error_log( "Neither a file nor a directory (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
						
					}
					
					// Add the code block for ignoring unreadable files
					if ( true ) {
					
						$code .= 'if ( ( true === $result ) && ( @is_readable( $header[\'filename\'] ) ) ) { ';
						$code .= '    $result = true; ';
						$code .= '} else { ';
//						$code .= '    error_log( "File not readable: \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
					
					}
					
					// Return true (to include file) if file passes conditions otherwise false (to skip file) if not
					$code .= 'return ( ( true === $result ) ? 1 : 0 ); ';

					$pre_add_func = create_function( $args, $code );
				
				}
				
				// If we had cause to create a pre add function then add it to the argument list here
				if ( !empty( $pre_add_func ) ) {
				
					array_push( $arguments, PCLZIP_CB_PRE_ADD, $pre_add_func );
		
				}
				
				if ( @file_exists( $zip ) ) {
	
					pb_backupbuddy::status( 'details', __('Existing ZIP Archive file will be replaced.','it-l10n-backupbuddy' ) );
					@unlink( $zip );
	
				}
				
				// Now actually create the zip archive file
				// First implode any embedded array in the argument list and truncate the result if too long
				// Assume no arrays embedded in arrays - currently no reason for that
				// TODO: Make the summary length configurable so that can see more if required
				// TODO: Consider mapping pclzip argument identifiers to string representations for clarity
				$args = '$item';
				$code = 'if ( is_array( $item ) ) { $string_item = implode( ",", $item); return ( ( strlen( $string_item ) <= 50 ) ? $string_item : "List: " . substr( $string_item, 0, 50 ) . "..." ); } else { return $item; }; ';
				$imploder_func = create_function( $args, $code );
				$imploded_arguments = array_map( $imploder_func, $arguments );
				
				pb_backupbuddy::status( 'details', $this->get_method_tag() . __( ' command arguments','it-l10n-backupbuddy' ) . ': ' . implode( ';', $imploded_arguments ) );
				
				$output = call_user_func_array( array( &$za, 'create' ), $arguments );
				
				// Work out whether we have a problem or not
				if ( is_array( $output ) ) {
				
					// It's an array so at least we produced a zip archive 
					$exitcode = 0;
					
					// Process the array for any "warnings" or other reportable conditions
					$id = 0; // Create a unique key (like a line number) for later sorting
					foreach( $output as $file ) {
					
						switch ( $file[ 'status' ] ) {
							case "skipped":
							
								// First need to filter out any files skipped because under a symlink dir
								foreach ( $symlinks_found as $prefix ) {
								
									if ( !( false === strpos( $file[ 'filename' ], $prefix ) ) ) {
									
										$id++;
										// break out of the foreach and the switch
										break 2;
										
									}
									
								}
								
								// For skipped files need to determine why it was skipped
								if ( ( true === $zip_ignoring_symlinks ) && @is_link( $file[ 'filename' ] ) ) {
								
									// Remember this for filtering other files skipped because in symlink directory
									$symlinks_found[] = $file[ 'filename' ];
									
									// Skipped because we are ignoring symlinks and this is a symlink
									$zip_other[ self::ZIP_OTHER_IGNORED_SYMLINK ][ $id++ ] = $file[ 'filename' ];
									$zip_other_count++;
									
								} else {
								
									//Skipped because probably unreadable or non-existent (catch-all for now)
									$zip_warnings[ self::ZIP_WARNING_SKIPPED ][ $id++ ] = $file[ 'filename' ];
									$zip_warnings_count++;
									
								}
								break;
							case "filtered":
								$zip_warnings[ self::ZIP_OTHER_FILTERED ][ $id++ ] = $file[ 'filename' ];
								$zip_warnings_count++;
								break;
							case "filename_too_long":
								$zip_warnings[ self::ZIP_OTHER_LONGPATH ][ $id++ ] = $file[ 'filename' ];
								$zip_warnings_count++;
								break;
							default:
								// Currently not processing "ok" entries
								$id++;
						}
					
					}
					
					// Now free up the memory...
					unset( $output );
					
					// Set convenience flags			
					$have_zip_warnings = ( 0 < $zip_warnings_count );
					$have_zip_other = ( 0 < $zip_other_count );
				
				} else {
				
					// Not an array so a bad error code, something we didn't or couldn't catch
					$exitcode = $za->errorCode();
					
					// Put the error information into an array for consistency
					$zip_errors[] = $za->errorInfo( true );
					$zip_errors_count = sizeof( $zip_errors );
					$have_zip_errors = ( 0 < $zip_errors_count );
				
				}
				
				// Convenience for handling different scanarios
				$result = false;
				
				// Always report the exit code regardless of whether we might ignore it or not
				pb_backupbuddy::status( 'details', __('Zip process exit code: ','it-l10n-backupbuddy' ) . $exitcode );
	
				// Always report the number of warnings - even just to confirm that we didn't have any
				pb_backupbuddy::status( 'details', sprintf( __('Zip process reported: %1$s warning%2$s','it-l10n-backupbuddy' ), $zip_warnings_count, ( ( 1 == $zip_warnings_count ) ? '' : 's' ) ) );
				
				// Always report warnings regardless of whether user has selected to ignore them
				if ( true === $have_zip_warnings ) {
			
					$this->log_zip_reports( $zip_warnings, self::$_warning_desc, "WARNING", self::MAX_WARNING_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_WARNINGS_FILE_NAME );

				}
			
				// Always report other reports regardless
				if ( true === $have_zip_other ) {
			
					// Only report number of informationals if we have any as they are not that important
					pb_backupbuddy::status( 'details', sprintf( __('Zip process reported: %1$s information%2$s','it-l10n-backupbuddy' ), $zip_other_count, ( ( 1 == $zip_other_count ) ? 'al' : 'als' ) ) );

					$this->log_zip_reports( $zip_other, self::$_other_desc, "INFORMATION", self::MAX_OTHER_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_OTHERS_FILE_NAME );

				}
			
				// See if we can figure out what happened
				// Note: only expect exitcode to be non-zero for an error we couldn't pre-empt
				// Note: warnings will cause the operation to be stopped if user hasn't chosen to ignore regardless
				// of whether we got a zip file (which we most likely did).
				// Note: a non-zero exitcode and presence of warnings are mutually exclusive
				if ( ( ! @file_exists( $temp_zip ) ) ||
					 ( 0 != $exitcode ) ||
					 ( ( true == $have_zip_warnings ) && !$this->get_ignore_warnings() ) ) {
				
					// If we have any zip errors reported show them regardless
					if ( true == $have_zip_errors ) {
					
						pb_backupbuddy::status( 'details', sprintf( __('Zip process reported: %1$s error%2$s','it-l10n-backupbuddy' ), $zip_errors_count, ( ( 1 == $zip_errors_count ) ? '' : 's' )  ) );
					
						foreach ( $zip_errors as $line ) {
						
							pb_backupbuddy::status( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
						
						}
					
					}
					
					// Report whether or not the zip file was created (this will always be in the temporary location)			
					if ( ! @file_exists( $temp_zip ) ) {
					
						pb_backupbuddy::status( 'details', __( 'Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
						
					} else {
						
						pb_backupbuddy::status( 'details', __( 'Zip Archive file created but with errors/actionable-warnings so will be deleted - check process exit code and warnings.','it-l10n-backupbuddy' ) );
	
					}
					
					// The operation has failed one way or another. Note that for pclzip the zip file is always created in the temporary
					// location regardless of whether the user selected to ignore errors or not (we can never guarantee to create a valid
					// zip file because the script might be terminated by the server so we must wait to produce a valid file and then
					// move it to the final location if it is valid).
					// Therefore if there is a zip file (produced but with warnings) it will not be visible and will be deleted when the
					// temporary directory is deleted below.
					
					$result = false;
					
				} else {
				
					// Got file with no error or warnings _or_ with warnings that the user has chosen to ignore
					// File always built in temporary location so always need to move it
					pb_backupbuddy::status( 'details', __('Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
					
					// Make sure no stale file information
					clearstatcache();
					
					// Relocate the temporary zip file to final location
					@rename( $temp_zip, $zip );
					
					// Check that we moved the file ok
					if ( @file_exists( $zip ) ) {
					
						pb_backupbuddy::status( 'details', __('Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
						pb_backupbuddy::status( 'message', __( 'Zip Archive file successfully created with no errors (any actionable warnings ignored by user settings).','it-l10n-backupbuddy' ) );
						
						$this->log_archive_file_stats( $zip );
						
						$result = true;
						
					} else {
					
						pb_backupbuddy::status( 'details', __('Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
									
				}

			}
			
			// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file		
			pb_backupbuddy::status( 'details', __('Removing temporary directory.','it-l10n-backupbuddy' ) );
			
			if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
			
					pb_backupbuddy::status( 'details', __('Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
			
			}
			
		  	if ( NULL != $za ) { unset( $za ); }
		  	
			return $result;
															
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
								
			// Update the definition before it is used by loading the library
			// This will not wok if perchance the file has already been loaded :-(
			// TODO: Need a temporary directory that we can use for this
			//define( 'PCLZIP_TEMPORARY_DIR', $tempdir );
			
			// This should give us a new archive object, if not catch it and bail out
			try {
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
			 	
				// Make sure we opened the zip ok and it has content
				if ( ( $content_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory ) ) !== 0 ) {
				
					// How many files - must be >0 to have got here
					$file_count = sizeof( $content_list );
					
					pb_backupbuddy::status( 'details', sprintf( __('pclzip extracted file contents (%1$s to %2$s)','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );

					$this->log_archive_file_stats( $zip_file );	
					
					$result = true;
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( true );
					pb_backupbuddy::status( 'details', sprintf( __('pclzip failed to open file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
							
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
			$stat = array();
			
			// This should give us a new archive object, if not catch it and bail out
			try {
			
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has content
				if ( ( $content_list = $za->listContent() ) !== 0 ) {
				
					// Now we need to take each item and run an unzip for it - unfortunately there is no easy way of combining
					// arbitrary extractions into a single command if some might be to a 
					foreach ( $items as $what => $where ) {
			
						$rename_required = false;
						$result = false;
				
						// Decide how to extract based on where
						if ( empty( $where) ) {
					
							// First we'll extract and junk the path
							// Note: For some odd reason when we have a $what file that is a hidden (dot) file
							// the file_exists() test in pclzip for the filepath to extract to returns true even
							// though only the parent directory exists and not the file itself. No idea why at
							// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
							// so the fact the test returns true is ignored.
							$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_NAME, $what, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_REPLACE_NEWER );
								
							// Check whether we succeeded or not (would only be no list array for a zip file problem)
							// but extraction of the file itself may still have failed
							$result = ( $extract_list !== 0  && ( $extract_list[ 0 ][ 'status' ] == 'ok' ) );
															
						} elseif ( !empty( $where ) ) {
					
							if ( $what === $where ) {
							
								// Check for wildcard directory extraction like dir/* => dir/*
								if ( "*" == substr( trim( $what ), -1 ) ) {

									// Turn this into a preg_match pattern
									$whatmatch = "|^" . $what . "|";									

									// First we'll extract but we're not junking the paths
									// Note: For some odd reason when we have a $what file that is a hidden (dot) file
									// the file_exists() test in pclzip for the filepath to extract to returns true even
									// though only the parent directory exists and not the file itself. No idea why at
									// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
									// so the fact the test returns true is ignored.
									$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_PREG, $whatmatch, PCLZIP_OPT_REPLACE_NEWER );

									// Check whether we succeeded or not (would only be no list array for a zip file problem)
									// but extraction of individual files themselves may still have failed
									if ( 0 !== $extract_list ) {
									
										// So far so good - assume everything will be ok
										$result = true;
	
										// At least we got no major failure so check the extracted files
										foreach ( $extract_list as $file ) {
										
											if ( 'ok' !== $file[ 'status' ] ) {
											
												// Oops - we found a file that didn't extract ok so bail out with false
												$result = false;
												break;
											
											}
										
										}
									
									}
								
								} else {
								
									// It's just a single file extraction - breath a sign of relief
									// Extract to same directory structure - don't junk path, no need to add where to destnation as automatic
									// Note: For some odd reason when we have a $what file that is a hidden (dot) file
									// the file_exists() test in pclzip for the filepath to extract to returns true even
									// though only the parent directory exists and not the file itself. No idea why at
									// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
									// so the fact the test returns true is ignored.
									$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_NAME, $what, PCLZIP_OPT_REPLACE_NEWER );
									
									// Check whether we succeeded or not (would only be no list array for a zip file problem)
									// but extraction of the file itself may still have failed
									$result = ( $extract_list !== 0  && ( isset( $extract_list[ 0 ] ) ) && ( $extract_list[ 0 ][ 'status' ] == 'ok' ) );

								}
						
							} else {

								// First we'll extract and junk the path
								// Note: For some odd reason when we have a $what file that is a hidden (dot) file
								// the file_exists() test in pclzip for the filepath to extract to returns true even
								// though only the parent directory exists and not the file itself. No idea why at
								// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
								// so the fact the test returns true is ignored.
								$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_NAME, $what, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_REPLACE_NEWER );
																							
								// Check whether we succeeded or not (would only be no list array for a zip file problem)
								// but extraction of the file itself may still have failed
								$result = ( $extract_list !== 0  && ( $extract_list[ 0 ][ 'status' ] == 'ok' ) );

								// Will need to rename if the extract is ok
								$rename_required = true;
						
							}
					
						}
				
						// Note: we don't open the file and then do stuff but it's all done in one action
						// so we need to interpret the return code to dedide what to do
						// Currently we can only distinguish between success and failure but no finer grain
						if ( true === $result ) {
					
							pb_backupbuddy::status( 'details', sprintf( __('pclzip extracted file contents (%1$s from %2$s to %3$s%4$s)','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where ) );

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
							pb_backupbuddy::status( 'details', sprintf( __('pclzip failed to open/process file to extract file contents (%1$s from %2$s to %3$s%4$s) - Error Info: %5$s.','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where, $error_string ) );
					
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
					pb_backupbuddy::status( 'details', sprintf( __('pclzip failed to open file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

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
			$stat = array();
								
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

				// Return an error code and a description - this needs to be handled more generically
				$result = array( 1, "Class not available to match method" );
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has content
				if ( ( $content_list = $za->listContent() ) !== 0 ) {
				
					// Assume failure
					$result = false;
					
					// Get each file in sequence by index and get the properties
					for ( $i = 0; $i < sizeof( $content_list ); $i++ ) {
					
						$stat = $content_list[ $i ];
						
						// Assume the key exists (consider testing)
						if ( $stat[ 'filename' ] == $locate_file ) {
						
							// File found so we can note that
							pb_backupbuddy::status( 'details', __('File found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
							$result = true;
							
							// Need to exit the for loop
							break;
							
						}
						
					}
					
					if ( false === $result ) {
					
						// Only get here if the file wasn't found
						pb_backupbuddy::status( 'details', __('File not found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						
					}

				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( true );
					pb_backupbuddy::status( 'details', sprintf( __('pclzip failed to open file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					$result = array( 1, "Failed to open/process file" );

				}
							
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
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has content
				if ( 0 !== ( $content_list = $za->listContent() ) ) {
				
					// How many files - must be >0 to have got here
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
					
					pb_backupbuddy::status( 'details', sprintf( __('pclzip listed file contents (%1$s)','it-l10n-backupbuddy' ), $zip_file ) );

					$this->log_archive_file_stats( $zip_file );
					
					$result = &$file_list;
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( true );
					pb_backupbuddy::status( 'details', sprintf( __('pclzip failed to open file to list contents (%1$s) - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
							
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
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and we added the comment ok
				// Note: using empty array as we don't actually want to add any files
				if ( 0 !== ( $list = $za->add( array(), PCLZIP_OPT_COMMENT, $comment ) ) ) {
				
					// We got a list back so adding comment should have been successful
					pb_backupbuddy::status( 'details', sprintf( __('PclZip set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
					$result = true;
					
				} else {
				
					// If we failed to set the commnent then log it (?) and drop through
					$error_string = $za->errorInfo( true );
					pb_backupbuddy::status( 'details', sprintf( __('PclZip failed to set comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					$result = false;
										
				}
			
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
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				pb_backupbuddy::status( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has properties
				if ( 0 !== ( $properties = $za->properties() ) ) {
				
					// We got properties so should have a comment to return, even if empty
					pb_backupbuddy::status( 'details', sprintf( __('PclZip retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
					$result = $properties[ 'comment' ];
					
				} else {
				
					// If we failed to get the commnent then log it (?) and drop through
					$error_string = $za->errorInfo( true );
					pb_backupbuddy::status( 'details', sprintf( __('PclZip failed to retrieve comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					$result = false;
					
				}
				
			
			}
			
		  	if ( NULL != $za ) { unset( $za ); }		
			
			return $result;
				
		}
		
	} // end pluginbuddy_zbzippclzip class.	
	
}
?>
