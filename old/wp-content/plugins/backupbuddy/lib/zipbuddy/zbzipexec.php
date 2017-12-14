<?php
/**
 *	pluginbuddy_zbzipexec Class
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
if ( !class_exists( "pluginbuddy_zbzipexec" ) ) {

	class pluginbuddy_zbzipexec extends pluginbuddy_zbzipcore {
	
		// Constants for file handling
		const ZIP_LOG_FILE_NAME        = 'temp_zip_exec_log.txt';
		const ZIP_ERRORS_FILE_NAME     = 'last_exec_errors.txt';
		const ZIP_WARNINGS_FILE_NAME   = 'last_exec_warnings.txt';
		const ZIP_OTHERS_FILE_NAME     = 'last_exec_others.txt';
		const ZIP_EXCLUSIONS_FILE_NAME = 'exclusions.txt';
		const ZIP_INCLUSIONS_FILE_NAME = 'inclusions.txt';
		const ZIP_TEST_FILE            = '/zbzip.php'; // Contains file test.txt with content "Hello World"
		const ZIP_TEST_FILE_SIG        = "0a0f9b28c5ff89dfb4f2a0472be0ea8f";
		
		// Possible executable path sets
		const DEFAULT_EXECUTABLE_PATHS = '/usr/local/bin::/usr/bin:/usr/local/sbin:/usr/sbin:/sbin:/bin';
		const WINDOWS_EXECUTABLE_PATHS = '';
		
        /**
         * method tag used to refer to the method and entities associated with it such as class name
         * 
         * @var $_method_tag 	string
         */
		public static $_method_tag = 'exec';
			
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
		public static $_method_dependencies = array( 'classes' => array(),
											  		 'functions' => array( 'exec' ),
											  		 'extensions' => array(),
											  		 'files' => array(),
											  		 'check_func' => 'check_method_dependencies_static'
													);
			
        /**
         * Boolean to indicate if we can support comment handling based on dependency check
         * 
         * @var $_allow_is_commenter bool
         */
        protected static $_allow_is_commenter = true;
        
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
		
			$result = true;
			
			// Need to check if function escapeshellarg os available - if not then exec cannot
			// be used for comment handling. This isn't a show stopper so we'll return true
			// but set an internal flag to disable commenting capability.
			
			$functions = array( 'escapeshellarg' );
			
			$disabled_functions = array_map( "trim", explode( ',', ini_get( 'disable_functions' ) ) );
			
			// Check each function dependency and bail out on first failure
			foreach ( $functions as $function ) {
			
				$function = trim( $function );
				
				if ( !( ( function_exists( $function ) ) && ( !in_array( $function, $disabled_functions ) ) ) ) {

					$result = false;
					break;
					
				}
			
			}
			
			if ( false === $result ) {
			
				// Found that escapeshellarg not available so exec cannot be used for comment handling
				self::$_allow_is_commenter = false;
			
			}
			
			return true;
		
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
			
			// Set the internal flag indicating if exec_dir is set in the PHP environment
			$this->set_exec_dir_flag();
			
			// Override some of parent defaults
			$this->_method_details[ 'attr' ] = array_merge( $this->_method_details[ 'attr' ],
															array( 'name' => 'Exec Method',
													  			   'compatibility' => pluginbuddy_zbzipexec::$_is_compatibility_method )
													  	   );
													  	   
			// Now set up the default executable paths (not merging but setting)
			// Note: Parent constructor set the os type value
			switch ( $this->get_os_type() ) {
			
				case self::OS_TYPE_NIX:
				
					$this->set_executable_paths( explode( PATH_SEPARATOR, self::DEFAULT_EXECUTABLE_PATHS ), false);
					break;
					
				case self::OS_TYPE_WIN:
					
					$this->set_executable_paths( explode( PATH_SEPARATOR, self::WINDOWS_EXECUTABLE_PATHS ), false);
					
					// Need to merge in ABSPATH here because we cannot set that in the defaults - it is prepended
					$this->set_executable_paths( array( rtrim( ABSPATH, DIRECTORY_SEPARATOR ) ) );
					break;
					
				default:
					// Log error and leave paths empty
					pb_backupbuddy::status( 'details', sprintf( __('Unknown OS type (%1$s) could not set executable paths','it-l10n-backupbuddy' ), $this->get_os_type() ) );
					
			}
			
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
		
			return pluginbuddy_zbzipexec::$_method_tag;
			
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
		
			return pluginbuddy_zbzipexec::$_is_compatibility_method;
			
		}
		
		/**
		 *	get_command_path()
		 *	
		 *	This returns the path for the requested command from the method details
		 *	If not found then will return empty string which is the same as if the
		 *	command is being accessed through PATH
		 *	
		 *	@return		string				Path for command, trimmed - may be empty
		 *
		 */
		protected function get_command_path( $command = self::COMMAND_UNKNOWN_PATH ) {
		
			$result = '';
			
			switch( $command ) {
			
				case self::COMMAND_ZIP_PATH:
				
					// If there is a common path use it otherwise look for the command specific path
					if ( isset( $this->_method_details[ 'param' ][ 'path' ] ) ) {
					
						$result = trim( $this->_method_details[ 'param' ][ 'path' ] );
						
					} elseif ( isset( $this->_method_details[ 'param' ][ 'zip' ][ 'path' ] ) ) {
					
						$result = trim( $this->_method_details[ 'param' ][ 'zip' ][ 'path' ] );
					}
					
					break;
				
				case self::COMMAND_UNZIP_PATH:
				
					// If there is a common path use it otherwise look for the command specific path
					if ( isset( $this->_method_details[ 'param' ][ 'path' ] ) ) {
					
						$result = trim( $this->_method_details[ 'param' ][ 'path' ] );
						
					} elseif ( isset( $this->_method_details[ 'param' ][ 'unzip' ][ 'path' ] ) ) {
					
						$result = trim( $this->_method_details[ 'param' ][ 'unzip' ][ 'path' ] );
					}
					
					break;
				
				default:
				
					// Return the empty string default for now
			
			}

			return $result;
		
		}
		
		/**
		 *	set_zip_version()
		 *	
		 *	This sets the zip version information in the method details
		 *	Use "zip -h" to get a standardized help output that contains the zip version.
		 *	Theoretically we should be able to use "zip -v" to get zip version and build
		 *	details but pre-v3 zip running "zip -v" will not produce the required output because
		 *	there is no tty attached (when running through exec() or equivalent), instead
		 *	it will (or should) produce a zip file. However, it has been found that even this
		 *	is not reliable and some installations just seem to freeze when "zip -v" is run
		 *	which borks the whole process.
		 *	So we use "zip -h" first to get the basic zip version information and as a future
		 *	extension if it is version 3.0+ (3.0 is current and 3.1 hasn't been officially
		 *	released) we may then run "zip -v" to get the extended version and build information.
		 *	
		 *	@param		int		$major		Value to use if none found or override true
		 *	@param		int		$minor		Value to use if none found or override true
		 *	@param		bool	$override	True to use passed in value(s) regardless
		 *	@return		object				This object reference
		 *
		 */
		protected function set_zip_version( $major = 0, $minor = 0, $override = false ) {
		
			$exitcode = 127;
			$output = array();
			$zippath = '';
			$command = '';
			$matches = array();
			$info = '';
		
			// If we have been given a value to use with override then just use it
			if ( ( ( is_int( $major) ) && ( 0 < $major ) && ( is_int( $minor ) ) ) && ( true === $override ) ) {
			
				// Set the given version regardless
				$this->_method_details[ 'param' ][ 'zip' ][ 'version' ] = array( 'major' => $major, 'minor' => $minor );
				return $this; 
			
			}
			
			// Get the command path for the zip command - should return a trimmed string
			$zippath = $this->get_command_path( self::COMMAND_ZIP_PATH );
			
			// Add the trailing slash if required
			$command = $this->slashify( $zippath ) . 'zip -h';
			@exec( $command, $output, $exitcode );
			
			if ( 0 === $exitcode ) {
			
				// Expect format like: Zip 3.0 (July 5th 2008)...
				//                     Zip 3.1c BETA (June 22nd 2010)...
				// The match should take only the major/minor digits and ignore any following alpha
				// May extend to capture the alpha and also whether BETA indicated but not currently
				// required.
				foreach ( $output as $line ) {

					if ( preg_match( '/^\s*(zip)\s+(?P<major>\d)\.(?P<minor>\d+)/i', $line, $matches ) ) {
					
						$major = (int)$matches[ 'major' ];
						$minor = (int)$matches[ 'minor' ];
						break;
					
					}
				
				}
				
				// If we didn't match a version then suspect this is still not valid version info
// 				if ( !empty( $matches ) ) {
// 				
// 					// We did match a version so check if we have a chance of getting additional information
// 					if ( 3 === $major ) {
// 					
// 						$exitcode = 127;
// 						$output = array();
// 					
// 						// Add the trailing slash if required
// 						$command = $this->slashify( $zippath ) . 'zip -v';
// 						@exec( $command, $output, $exitcode );
// 						
// 						if ( 0 === $exitcode ) {
// 				
// 							// Now create the info string
// 							// Note: not worth compressing as that gives a larger string after converting
// 							// from binary to hex format for saving
// 							$info = implode( PHP_EOL, $output );
// 							$this->_method_details[ 'param' ][ 'zip' ][ 'info' ] = $info;
// 						
// 						}
// 					
// 					}
// 				
// 				}
			
			}

			// Now use either what we got or what we were given...
			if ( ( is_int( $major) ) && ( 0 < $major ) && ( is_int( $minor ) ) ) {
			
				// Set the given version regardless
				$this->_method_details[ 'param' ][ 'zip' ][ 'version' ] = array( 'major' => $major, 'minor' => $minor );
			
			}
			
			return $this; 
		
		}
		
		/**
		 *	get_zip_version()
		 *	
		 *	This gets the zip version as an array of major/minor or returns false if not known
		 *	TODO: Pass parameter to specify what format to return in
		 *	
		 *	@return		array|bool				Returns array(major, minor) or false if not known
		 *
		 */
		protected function get_zip_version() {
		
			$result = $this->_method_details[ 'param' ][ 'zip' ][ 'version' ];
			
			if ( 0 === $result[ 'major' ] ) {
			
				$result = false;
				
			}
			
			return $result;
			
		}
		
		/**
		 *	set_unzip_version()
		 *	
		 *	This sets the unzip version information in the method details
		 *	
		 *	@param		int		$major		Value to use if none found or override true
		 *	@param		int		$minor		Value to use if none found or override true
		 *	@param		bool	$override	True to use passed in value(s) regardless
		 *	@return		object				This object reference
		 *
		 */
		protected function set_unzip_version( $major = 0, $minor = 0, $override = false ) {
		
			$exitcode = 0;
			$output = array();
			$zippath = '';
			$command = '';
			$matches = array();
		
			// If we have been given a value to use with override then just use it
			if ( ( ( is_int( $major) ) && ( 0 < $major ) && ( is_int( $minor ) ) ) && ( true === $override ) ) {
			
				// Set the given version regardless
				$this->_method_details[ 'param' ][ 'unzip' ][ 'version' ] = array( 'major' => $major, 'minor' => $minor );
				return $this; 
			
			}
			
			// Get the command path for the unzip command - should return a trimmed string
			$zippath = $this->get_command_path( self::COMMAND_UNZIP_PATH );
			
			// Add the trailing slash if required
			$command = $this->slashify( $zippath ) . 'unzip -v';	
			@exec( $command, $output, $exitcode );
			
			if ( 0 === $exitcode ) {
			
				// Should be good output to try at least
				foreach ( $output as $line ) {

					if ( preg_match( '/^\s*(unzip)\s+(?P<major>\d)\.(?P<minor>\d+)/i', $line, $matches ) ) {
					
						$major = (int)$matches[ 'major' ];
						$minor = (int)$matches[ 'minor' ];
						break;
					
					}
				
				}
			
				// Now create the info string
				// Note: not worth compressing as that gives a larger string after converting
				// from binary to hex format for saving
				$info = implode( PHP_EOL, $output );
				$this->_method_details[ 'param' ][ 'unzip' ][ 'info' ] = $info;
				
			}
			
			// Now use either what we got or what we were given...
			if ( ( is_int( $major) ) && ( 0 < $major ) && ( is_int( $minor ) ) ) {
			
				// Set the given version regardless
				$this->_method_details[ 'param' ][ 'unzip' ][ 'version' ] = array( 'major' => $major, 'minor' => $minor );
			
			}
			
			return $this; 
		
		}
		
		/**
		 *	get_unzip_version()
		 *	
		 *	This gets the unzip version as an array of major/minor or returns false if not known
		 *	TODO: Pass parameter to specify what format to return in
		 *	
		 *	@return		array|bool				Returns array(major, minor) or false if not known
		 *
		 */
		protected function get_unzip_version() {
		
			$result = $this->_method_details[ 'param' ][ 'unzip' ][ 'version' ];
			
			if ( 0 === $result[ 'major' ] ) {
			
				$result = false;
				
			}
			
			return $result;
			
		}
		
		/**
		 *	get_zip_supports_logfile()
		 *	
		 *	This returns true if the zip in use is able to support logfile usage for
		 *	logging progress of zip operation
		 *	
		 *	@return		bool				True if logfile supported, otherwise false
		 *
		 */
		protected function get_zip_supports_log_file() {
		
			$result = false;
			
			// Currently check based just on the zip major version
			// TODO: decide if better to respond based on the available options
			if ( 3 <= $this->_method_details[ 'param' ][ 'zip' ][ 'version' ][ 'major' ] ) {
			
				$result = true;
				
			}
			
			return $result;
		
		}
		
		/**
		 *	is_available()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *
		 *  Note: in this case as the zip and unzip capabilities are provided by external commands we need to test
		 *  for the availability of both of them and set attributes accordingly
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		public function is_available( $tempdir ) {
		
			$result = false;
		
			// This is just a nicety for now until platform handling is fully resolved
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->is_available_generic( $tempdir );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->is_available_generic( $tempdir );
					break;
				default:
					$result = false;
			}
			
			return $result;
					  	
		}
		
		/**
		 *	is_available_generic()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *
		 *  Note: in this case as the zip and unzip capabilities are provided by external commands we need to test
		 *  for the availability of both of them and set attributes accordingly
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		protected function is_available_generic( $tempdir ) {
		
			$result = false;
			$pending_result = false;
			$found_zip = false;
			
			// This is a safety value in case exec() fails - hopefully it will not update this
			$exec_exit_code = 127;
			
			if ( function_exists( 'exec' ) ) {
			
				$candidate_paths = $this->get_executable_paths();
				
				// We are searching for zip using the list of possible paths
				while ( ( false == $found_zip ) && ( !empty( $candidate_paths ) ) ) {
				
					// Make sure it is clean of leading/trailing whitespace
					$path = trim( array_shift( $candidate_paths ) );
					
					pb_backupbuddy::status( 'details', __( 'Exec test (zip) trying executable path:','it-l10n-backupbuddy' ) . ' `' . $path . '`.' );

					$test_file = $tempdir . 'temp_test_' . uniqid() . '.zip';
					
					$command = $this->slashify( $path ) . 'zip' . " '{$test_file}'" . " '" . __FILE__ .  "'";
					
					$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;
									
					@exec( $command, $exec_output, $exec_exit_code );
			
					// Must have both a file and a success exit code to consider this successful
					if ( @file_exists( $test_file ) && ( 0 === $exec_exit_code ) ) {
			
						// Set the parameter to be remembered (note: path without trailing slash)
						$this->_method_details[ 'param' ][ 'zip' ][ 'path' ] = $path;
						
						// Platform independent capabilities
						$this->_method_details[ 'attr' ][ 'is_zipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_archiver' ] = true;
						
						// Platform specific capabilities
						switch ( $this->get_os_type() ) {
							case self::OS_TYPE_NIX:
								$this->_method_details[ 'attr' ][ 'is_commenter' ] = true;
								break;
							case self::OS_TYPE_WIN:
								// None Applicable
								break;
							default:
								// There is no default
						}
						
						pb_backupbuddy::status( 'details', __('Exec test (zip) PASSED.','it-l10n-backupbuddy' ) );
						$result = true;
				
						// TODO: Consider parsing zip file to get version of zip that created it. This may seem odd
						// but pre-v3 zip it's not possible to run "zip -v" through exec() or equivalent as it only
						// provides the required output if a tty is attached, otherwise it creates a zip file. We
						// might consider parsing the created zip file but as we have already created one here we
						// might as well use it
						
						// This will break us out of the loop
						$found_zip = true;
						
					} else {
				
						// Deal with the possible failure causes
						if ( !@file_exists( $test_file ) ) {
						
							pb_backupbuddy::status( 'details', __('Exec test (zip) FAILED: Test zip file not found.','it-l10n-backupbuddy' ) );
						
						}
						
						if ( 0 !== $exec_exit_code ) {
						
							$error_string = $exec_exit_code;
							pb_backupbuddy::status( 'details', __('Exec test (zip) FAILED: exec Exit Code: ','it-l10n-backupbuddy' ) . $error_string );
							
						}
						
						$result = false;
				
					}
					
					// Remove the test zip file if it was created
					if ( @file_exists( $test_file ) ) {
					
						if ( !@unlink( $test_file ) ) {
				
							pb_backupbuddy::status( 'details', sprintf( __('Exec test (zip) unable to delete test file (%s)','it-l10n-backupbuddy' ), $test_file ) );
					
						}
				
					}
					
				}
				
				
				// If we didn't find zip anywhere (or maybe found it but it failed) then log it
				if ( false === $found_zip ) {
					
					pb_backupbuddy::status( 'details', __('Exec test (zip) FAILED: Unable to find zip executable on any specified path.','it-l10n-backupbuddy' ) );
					$result = false;
					
				}
				
				// Remember zip result and reset for unzip test
				$pending_result = $result;
				$result = false;
				
				// See if we can determine zip version and possibly available options. This can help us
				// determine how to execute operations such as creating a zip file
				if ( true === $found_zip ) {
					
					pb_backupbuddy::status( 'details', 'Checking zip version...' );

					$this->set_zip_version();
					
					$version = $this->get_zip_version();
					if ( true === is_array( $version ) ) {
			
						( ( 2 == $version[ 'major' ] ) && ( 0 == $version[ 'minor' ] ) ) ? $version[ 'minor' ] = 'X' : true ;
						pb_backupbuddy::status( 'details', sprintf( __( 'Found zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
					} else {
			
						$version = array( "major" => "X", "minor" => "Y" );
						pb_backupbuddy::status( 'details', sprintf( __( 'Found zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

					}

				}
				
				// Reset the candidate paths for a full search for unzip
				$candidate_paths = $this->_executable_paths;
						  
				// Reset the safety value in case
				$exec_exit_code = 127;
				
				// New search
				$found_zip = false;
				
				// Using a test file
				$test_file = dirname( __FILE__ ) . self::ZIP_TEST_FILE;
				
				// It has to exist and be readable otehrwise we just have to bail on testing for unzip
				pb_backupbuddy::status( 'details', sprintf( __( 'Exec test (unzip) checking test file readable: %1$s', 'it-l10n-backupbuddy' ), $test_file ) );
				if ( is_readable( $test_file ) ) {
				
					// Only proceed if the file looks as expected
					pb_backupbuddy::status( 'details', sprintf( __( 'Exec test (unzip) checking test file intact: %1$s', 'it-l10n-backupbuddy' ), self::ZIP_TEST_FILE_SIG ) );
					if ( self::ZIP_TEST_FILE_SIG === md5_file( $test_file ) ) {
				
						// We are searching for unzip using the list of possible paths
						while ( ( false === $found_zip ) && ( !empty( $candidate_paths ) ) ) {
				
							// Make sure it is clean of leading/trailing whitespace
							$path = trim( array_shift( $candidate_paths ) );
					
							pb_backupbuddy::status( 'details', __( 'Exec test (unzip) trying executable path:','it-l10n-backupbuddy' ) . ' `' . $path . '`.' );

							$command = $this->slashify( $path ) . 'unzip -qt' . " '{$test_file}'" . " 'test.txt'";
									
							$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;
					
							@exec( $command, $exec_output, $exec_exit_code );
					
							if ( $exec_exit_code === 0 ) {
							
								// Set the parameter to be remembered (note: path without trailing slash)
								$this->_method_details[ 'param' ][ 'unzip' ][ 'path' ] = $path;
						
								// Platform independent capabilities
								$this->_method_details[ 'attr' ][ 'is_unzipper' ] = true;
								$this->_method_details[ 'attr' ][ 'is_checker' ] = true;
								$this->_method_details[ 'attr' ][ 'is_unarchiver' ] = true;
								$this->_method_details[ 'attr' ][ 'is_lister' ] = true;
								$this->_method_details[ 'attr' ][ 'is_extractor' ] = true;
						
								// Platform specific capabilities
								switch ( $this->get_os_type() ) {
									case self::OS_TYPE_NIX:
								
										// This is special - we must have zip also so this will only end up true if we previously found zip
										// and speculatively set this attribute to be true. Also we need for exec_dir to not be active - if
										// it is the command line is escaped and that is incompatible with the piping we have to use when
										// setting a comment. Also we need the escapeshellarg function to be available and that was checked
										// when dependencies were checked
										$this->_method_details[ 'attr' ][ 'is_commenter' ] = $this->_method_details[ 'attr' ][ 'is_commenter' ] && true && !$this->get_exec_dir_flag() && self::$_allow_is_commenter;
										break;
									case self::OS_TYPE_WIN:
										// None Applicable
										break;
									default:
										// There is no default
								}
						
								pb_backupbuddy::status( 'details', __('Exec test (unzip) PASSED.','it-l10n-backupbuddy' ) );
								$result = true;
				
								// This will break us out of the loop
								$found_zip = true;
						
							} else {
				
								$error_string = $exec_exit_code;
								pb_backupbuddy::status( 'details', __('Exec test (unzip) FAILED: Test unzip file test failed.','it-l10n-backupbuddy' ) );
								pb_backupbuddy::status( 'details', __('Exec test (unzip) FAILED: exec Exit Code: ','it-l10n-backupbuddy' ) . $error_string );
								$result = false;
				
							}
					
						}
					
					} else {
				
						// The test file looked corrupted so warn and bail out
						pb_backupbuddy::status( 'details', sprintf( __('Exec test (unzip) FAILED: Test file appears to be corrupted: %1$s','it-l10n-backupbuddy' ), md5_file( $test_file ) ) );

					}
				
				} else {
				
					// The test file doesn't seem to exist or be readable so warn and bail out
					pb_backupbuddy::status( 'details', __('Exec test (unzip) FAILED: Test file appears to either not exist or not be readable.','it-l10n-backupbuddy' ) );
				
				}
			
				// If we didn't find unzip anywhere (or maybe found it but it failed) then log it
				if ( false === $found_zip ) {
					
					// We speculatively set this true when we found zip but we need both zip and unzip so set if false
					$this->_method_details[ 'attr' ][ 'is_commenter' ] = false;

					pb_backupbuddy::status( 'details', __('Exec test (unzip) FAILED: Unable to find unzip executable on any specified path.','it-l10n-backupbuddy' ) );
					$result = false;
					
				} else {
				
					// See if we can determine unzip version and possibly available options. This can help us
					// determine how to execute operations such as unzipping a file
					
					pb_backupbuddy::status( 'details', 'Checking unzip version...' );

					$this->set_unzip_version();
				
					$version = $this->get_unzip_version();
					if ( true === is_array( $version ) ) {
			
						pb_backupbuddy::status( 'details', sprintf( __( 'Found unzip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
					} else {
			
						$version = array( "major" => "X", "minor" => "Y" );
						pb_backupbuddy::status( 'details', sprintf( __( 'Found unzip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

					}

				}
				
			} else {
			
				pb_backupbuddy::status( 'details', __('Exec test (zip/unzip) FAILED: One or more required function do not exist.','it-l10n-backupbuddy' ) );
				$result = false;
		  
		  	}
		  	
		  	// If we found both zip and unzip then compare the paths and if the same then set the common path
		  	if ( $pending_result && $result ) {
		  	
		  		if ( $this->_method_details[ 'param' ][ 'zip' ][ 'path' ] === $this->_method_details[ 'param' ][ 'unzip' ][ 'path' ] ) {
		  		
		  			$this->_method_details[ 'param' ][ 'path' ] = $this->_method_details[ 'param' ][ 'zip' ][ 'path' ];
		  			
		  		}
		  		
		  	}
		  	
		  	// Our result will be true if we found either or both of zip and unzip
		  	// The method attributes will tell which is available
		  	$result = ( $pending_result || $result );
		  	
		  	return $result;
		  	
		}
		
		/**
		 *	create()
		 *	
		 *	A function that creates an archive file
		 *	Always cleans up after itself
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
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->create_generic( $zip, $dir, $excludes, $tempdir, $listmaker );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->create_generic( $zip, $dir, $excludes, $tempdir, $listmaker );
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}
			
		/**
		 *	create_generic()
		 *	
		 *	A function that creates an archive file
		 *	
		 *	The $excludes will be a list or relative path excludes if the $listmaker object is NULL otehrwise
		 *	will be absolute path excludes and relative path excludes can be had from the $listmaker object
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to create
		 *	@param		string	$dir			Full path of directory to add to ZIP Archive file
		 *	@parame		array	$excludes		List of either absolute path exclusions or relative exclusions
		 *	@param		string	$tempdir		[Optional] Full path of directory for temporary usage
		 *	@param		object	$listmaker		The object from which we can get an inclusions list
		 *	@return		bool					True if the creation was successful, false otherwise
		 *
		 */
		protected function create_generic( $zip, $dir, $excludes, $tempdir, $listmaker ) {
		
			$exitcode = 0;
			$output = array();
			$zippath = '';
			$command = '';
			$temp_zip = '';
			$excluding_additional = false;
			$exclude_count = 0;
			$exclusions = array();
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
			$zip_using_log_file = false;
			$logfile_name = '';
			$zip_ignoring_symlinks = false;
		
			// The basedir must have a trailing directory separator
			$basedir = ( rtrim( trim( $dir ), DIRECTORY_SEPARATOR ) ) . DIRECTORY_SEPARATOR;
			
			if ( empty( $tempdir ) || !@file_exists( $tempdir ) ) {
			
				pb_backupbuddy::status( 'details', __('Temporary working directory must be available.','it-l10n-backupbuddy' ) );				
				return false;
				
			}
			
			// Tell which zip version is being used
			$version = $this->get_zip_version();
			
			if ( true === is_array( $version ) ) {
			
				( ( 2 == $version[ 'major' ] ) && ( 0 == $version[ 'minor' ] ) ) ? $version[ 'minor' ] = 'X' : true ;
				pb_backupbuddy::status( 'details', sprintf( __( 'Using zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
			} else {
			
				$version = array( "major" => "X", "minor" => "Y" );
				pb_backupbuddy::status( 'details', sprintf( __( 'Using zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

			}
					
			// Get the command path for the zip command - should return a trimmed string
			$zippath = $this->get_command_path( self::COMMAND_ZIP_PATH );
			
			// Determine if we are using an absolute path
			if ( !empty( $zippath ) ) {
			
				pb_backupbuddy::status( 'details', __( 'Using absolute zip path: ','it-l10n-backupbuddy' ) . $zippath );
				
			}

			// Add the trailing slash if required
			$command = $this->slashify( $zippath ) . 'zip';	

			// Always do recursive operation
			$command .= ' -r';
			
			// Check if the version of zip in use supports log file (which will help with memory usage for large sites)
			if ( true === $this->get_zip_supports_log_file() ) {
			
				// Choose to use log file so quieten stdout - we'll set up the log file later
				$command .= ' -q';
				$zip_using_log_file = true;
			
			}
			
			// Check if we need to turn off compression by settings (faster but larger backup)
			if ( true !== $this->get_compression() ) {
			
				$command .= ' -0';
				pb_backupbuddy::status( 'details', __('Zip archive creation compression disabled based on settings.','it-l10n-backupbuddy' ) );
				
			} else {
			
				pb_backupbuddy::status( 'details', __('Zip archive creation compression enabled based on settings.','it-l10n-backupbuddy' ) );
			
			}
			
			// Check if ignoring (not following) symlinks
			if ( true === $this->get_ignore_symlinks() ) {
			
				// Not all OS support this for command line zip but best to handle it late and just
				// indicate here it is requested but not supported by OS
				switch ( $this->get_os_type() ) {
					case self::OS_TYPE_NIX:
						// Want to not follow symlinks so set command option and set flag for later use
						$command .= ' -y';
						$zip_ignoring_symlinks = true;
						pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links will not be followed based on settings.','it-l10n-backupbuddy' ) );
						break;
					case self::OS_TYPE_WIN:
						pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links requested to not be followed based on settings but this option is not supported on this operating system.','it-l10n-backupbuddy' ) );
						break;
					default:
						pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links requested to not be followed based on settings but this option is not supported on this operating system.','it-l10n-backupbuddy' ) );
				}
				
			} else {
			
				pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links will be followed based on settings.','it-l10n-backupbuddy' ) );

			}
			
			// Check if we are ignoring warnings - meaning can still get a backup even
			// if, e.g., some files cannot be read
			if ( true === $this->get_ignore_warnings() ) {
			
				// Note: warnings are being ignored but will still be gathered and logged
				pb_backupbuddy::status( 'details', __('Zip archive creation actionable warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
				
			} else {
			
				pb_backupbuddy::status( 'details', __('Zip archive creation actionable warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

			}
			
			// Delete any existing zip file of same name - really this should never happen
			if ( @file_exists( $zip ) ) {

				pb_backupbuddy::status( 'details', __('Existing ZIP Archive file will be replaced.','it-l10n-backupbuddy' ) );
				@unlink( $zip );

			}
			
			// Now we'll set up the logging to file if required - use full logging
			if ( true === $zip_using_log_file ) {
			
				$logfile_name = $tempdir . self::ZIP_LOG_FILE_NAME;
				$command .= " -lf '{$logfile_name}' -li";
			
			}
						
			// Set temporary directory to store ZIP while it's being generated.			
			$command .= " -b '{$tempdir}'";

			// Specify where to place the finalized zip archive file
			// If warnings are being ignored we can tell zip to create the zip archive in the final
			// location - otherwise we must put it in a temporary location and move it later only
			// if there are no warnings. This copes with the case where (this) controlling script
			// gets timed out by the server and if the file were created in the final location with
			// warnings that should not be ignored we cannot prevent it being created. The -MM option
			// could be used but this prevents us catching such warnings and being able to report
			// them to the user in the case where the script hasn't been terminated. Additionally the
			// -MM option would bail out on the first encountered problem and so if there were a few
			// problems they would each not be found until the current one is fixed and try again.
			if ( true === $this->get_ignore_warnings() ) {
			
				$temp_zip = $zip;
			
			} else {
			
				$temp_zip = $tempdir . basename( $zip );
				
			}		

			$command .= " '{$temp_zip}' .";
			
			// Now work out exclusions dependent on what we have been given
			if ( is_object( $listmaker ) && ( defined( 'USE_EXPERIMENTAL_ZIPBUDDY_INCLUSION' ) && ( true === USE_EXPERIMENTAL_ZIPBUDDY_INCLUSION ) ) ) {
			
				// We're doing an inclusion operation, but first we'll just show the exclusiosn
				
				// For zip we need relative rather than absolute exclusion spaths
				$exclusions = $listmaker->get_relative_excludes( $basedir );
				
				if ( count( $exclusions ) > 0 ) {
				
					pb_backupbuddy::status( 'details', __('Calculating directories to exclude from backup.','it-l10n-backupbuddy' ) );
					
					$excluding_additional = false;
					$exclude_count = 0;
					foreach ( $exclusions as $exclude ) {
					
						if ( !strstr( $exclude, 'backupbuddy_backups' ) ) { // Set variable to show we are excluding additional directories besides backup dir.
	
							$excluding_additional = true;
								
						}
							
						pb_backupbuddy::status( 'details', __('Excluding','it-l10n-backupbuddy' ) . ': ' . $exclude );
													
						$exclude_count++;
							
					}
										
				}
				
				// Get the list of inclusions to process
				$inclusions = $listmaker->get_terminals();
				
				// For each directory we need to put the "wildcard" on the end
				foreach ( $inclusions as &$inclusion ) {
				
					if ( is_dir( $inclusion ) ) {
					
						$inclusion .= DIRECTORY_SEPARATOR . "*";
					}
				
					// Remove directory path prefix excluding leading slash to make relative (needed for zip)
					$inclusion = str_replace( rtrim( $basedir, DIRECTORY_SEPARATOR ), '', $inclusion );
									
				}
				
				// Now create the inclusions file in the tempdir
				
				// And update the command options
				$ifile = $tempdir . self::ZIP_INCLUSIONS_FILE_NAME;
				if ( file_exists( $ifile ) ) {
				
					@unlink( $ifile );
				
				}
				
				file_put_contents( $ifile, implode( PHP_EOL, $inclusions ) . PHP_EOL . PHP_EOL );
				
				$command .= " -i@" . "'{$ifile}'";
			
			} else {
			
				// We're doing an exclusion operation
			
				//$command .= "-i '*' "; // Not needed. Zip defaults to doing this. Removed July 10, 2012 for v3.0.41.
				
				// Since we had no $listmaker object or not using it get the standard relative excludes to process
				$exclusions = $excludes;
				
				if ( count( $exclusions ) > 0 ) {
				
					// Handle exclusions by placing them in an exclusion text file.
					$exclusion_file = $tempdir . self::ZIP_EXCLUSIONS_FILE_NAME;
					$this->_render_exclusions_file( $exclusion_file, $exclusions );
					
					pb_backupbuddy::status( 'details', sprintf( __( 'Using exclusion file `%1$s`', 'it-l10n-backupbuddy' ), $exclusion_file ) );
					$command .= ' -x@' . "'{$exclusion_file}'";
										
				}
			
			}
			
			// If we can't use a log file but exec_dir isn't in use we can redirect stderr to stdout
			// If exec_dir is in use we cannot redirect because of command line escaping so cannot log errors/warnings
			if ( false === $zip_using_log_file ) {
			
				if ( false === $this->get_exec_dir_flag() ) {
			
					$command .= ' 2>&1';
				
				} else {
				
					pb_backupbuddy::status( 'details', sprintf( __( 'Zip Errors/Warnings cannot not be logged with this version of zip and exec_dir active', 'it-l10n-backupbuddy' ), true ) );
				
				}
				
			}
			
			// Remember the current directory and change to the directory being added so that "." is valid in command
			$working_dir = getcwd();
			chdir( $dir );
			
			$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;
			
			pb_backupbuddy::status( 'details', $this->get_method_tag() . __(' command','it-l10n-backupbuddy' ) . ': ' . $command );
			@exec( $command, $output, $exitcode );
						
			// Set current working directory back to where we were
			chdir( $working_dir );
			
			// Convenience for handling different scanarios
			$result = false;
			
			// If we used a log file then process the log file - else process output
			// Always scan the output/logfile for warnings, etc. and show warnings even if user has chosen to ignore them
			if ( true === $zip_using_log_file ) {
			
				try {
				
					$logfile = new SplFileObject( $logfile_name, "rb" );
					
					while( !$logfile->eof() ) {
					
						$line = $logfile->current();
						$id = $logfile->key(); // Use the line number as unique key for later sorting
						$logfile->next();
						
						if ( preg_match( '/^\s*(zip warning:)/i', $line ) ) {
						
							// Looking for specific types of warning - in particular want the warning that
							// indicates a file couldn't be read as we want to treat that as a "skipped"
							// warning that indicates that zip flagged this as a potential problem but
							// created the zip file anyway - but it would have generated the non-zero exit
							// code of 18 and we key off that later. All other warnings are not considered
							// reasons to return a non-zero exit code whilst still creating a zip file so
							// we'll follow the lead on that and not have other warning types halt the backup.
							// So we'll try and look for a warning output that looks like it is file related...
							if ( preg_match( '/^\s*(zip warning:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
							
								// Matched to what looks like a file related warning so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "could not open for reading:":										
										$zip_warnings[ self::ZIP_WARNING_SKIPPED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "name not matched:":										
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
										$zip_warnings_count++;
								}
							
							} else {
							
								// Didn't match to what would look like a file related warning so count it regardless
								$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
								$zip_warnings_count++;
								
							}
							
						} elseif ( preg_match( '/^\s*(zip error:)/i', $line ) ) {
						
							$zip_errors[ $id ] = trim( $line );
							$zip_errors_count++;
						
						} elseif ( preg_match( '/^\s*(adding:)/i', $line ) ) {
						
							// Currently not processing additions entried
							//$zip_additions[] = trim( $line );
							//$zip_additions_count++;
						
						} elseif ( preg_match( '/^\s*(sd:)/i', $line ) ) {
						
							$zip_debug[ $id ] = trim( $line );
							$zip_debug_count++;
						
						} else {
						
							// Currently not processing other entries
							//$zip_other[] = trim( $line );
							//$zip_other_count++;
						
						}
						
					}
					
					unset( $logfile );
					
					@unlink( $logfile_name );
					
				} catch ( Exception $e ) {
				
					// Something fishy - we should have been able to open the log file...
					$error_string = $e->getMessage();
					pb_backupbuddy::status( 'details', sprintf( __('Log file could not be opened - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
					
				}

			} else {
			
				// TODO: $output could be large so if we parse it all into separate arrays then may want to shift
				// out each line and then discard it after copied to another array
				$id = 0; // Create a unique key (like a line number) for later sorting
				foreach ( $output as $line ) {
				
					if ( preg_match( '/^\s*(zip warning:)/i', $line ) ) {
					
						// Looking for specific types of warning - in particular want the warning that
						// indicates a file couldn't be read as we want to treat that as a "skipped"
						// warning that indicates that zip flagged this as a potential problem but
						// created the zip file anyway - but it would have generated the non-zero exit
						// code of 18 and we key off that later. All other warnings are not considered
						// reasons to return a non-zero exit code whilst still creating a zip file so
						// we'll follow the lead on that and not have other warning types halt the backup.
						// So we'll try and look for a warning output that looks like it is file related...
						if ( preg_match( '/^\s*(zip warning:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
							// Matched to what looks like a file related warning so check particular cases
							switch ( strtolower( $matches[ 2 ] ) ) {
								case "could not open for reading:":										
									$zip_warnings[ self::ZIP_WARNING_SKIPPED ][ $id++ ] = trim( $line );
									$zip_warnings_count++;
									break;
								case "name not matched:":										
									$zip_other[ self::ZIP_OTHER_GENERIC ][ $id++ ] = trim( $line );
									$zip_other_count++;
									break;
								default:
									$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id++ ] = trim( $line );
									$zip_warnings_count++;
							}
						
						} else {
						
							// Didn't match to what would look like a file related warning so count it regardless
							$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id++ ] = trim( $line );
							$zip_warnings_count++;
							
						}
						
					} elseif ( preg_match( '/^\s*(zip error:)/i', $line ) ) {
					
						$zip_errors[ $id++ ] = trim( $line );
						$zip_errors_count++;
					
					} elseif ( preg_match( '/^\s*(adding:)/i', $line ) ) {
					
						// Currently not processing additions entried
						//$zip_additions[] = trim( $line );
						//$zip_additions_count++;
						$id++;
					
					} elseif ( preg_match( '/^\s*(sd:)/i', $line ) ) {
					
						$zip_debug[ $id++ ] = trim( $line );
						$zip_debug_count++;
					
					} else {
					
						// Currently not processing other entries
						//$zip_other[] = trim( $line );
						//$zip_other_count++;
						$id++;
					
					}
					
				}
	
				// Now free up the memory...
				unset( $output );
				
			}
			
			// Set convenience flags			
			$have_zip_warnings = ( 0 < $zip_warnings_count );
			$have_zip_errors = ( 0 < $zip_errors_count );
			$have_zip_additions = ( 0 < $zip_additions_count );
			$have_zip_debug = ( 0 < $zip_debug_count );
			$have_zip_other = ( 0 < $zip_other_count );
			
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
			
			// See if we can figure out what happened - note that $exitcode could be non-zero for actionable warning(s) or error
			// if ( (no zip file) or (fatal exit code) or (not ignoring warnable exit code) )
			// TODO: Handle condition testing with function calls based on mapping exit codes to exit type (fatal vs non-fatal)
			if ( ( ! @file_exists( $temp_zip ) ) ||
				 ( ( 0 != $exitcode ) && ( 18 != $exitcode ) ) ||
				 ( ( 18 == $exitcode ) && !$this->get_ignore_warnings() ) ) {
			
				// If we have any zip errors reported show them regardless
				if ( true === $have_zip_errors ) {
				
					pb_backupbuddy::status( 'details', sprintf( __('Zip process reported: %1$s error%2$s','it-l10n-backupbuddy' ), $zip_errors_count, ( ( 1 == $zip_errors_count ) ? '' : 's' )  ) );
					
					foreach ( $zip_errors as $line ) {
				
						pb_backupbuddy::status( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
				
					}
					
				}

				// Report whether or not the zip file was created (whether that be in the final or temporary location)			
				if ( ! @file_exists( $temp_zip ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
					
				} else {
					
					pb_backupbuddy::status( 'details', __( 'Zip Archive file created but with errors/actionable-warnings so will be deleted - check process exit code and warnings.','it-l10n-backupbuddy' ) );

				}
				
				// The operation has failed one way or another. Note that as the user didn't choose to ignore errors the zip file
				// is always created in a temporary location and then only moved to final location on success without error or warnings.
				// Therefore if there is a zip file (produced but with warnings) it will not be visible and will be deleted when the
				// temporary directory is deleted below.
				
				$result = false;
				
			} else {
			
				// Got file with no error or warnings _or_ with warnings that the user has chosen to ignore
				if ( false === $this->get_ignore_warnings() ) {
				
					// Because not ignoring warnings the zip archive was built in temporary location so we need to move it
					pb_backupbuddy::status( 'details', __('Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
				
					// Make sure no stale file information
					clearstatcache();
					
					@rename( $temp_zip, $zip );
					
					if ( @file_exists( $zip ) ) {
					
						pb_backupbuddy::status( 'details', __('Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
						pb_backupbuddy::status( 'message', __( 'Zip Archive file successfully created with no errors or actionable warnings.','it-l10n-backupbuddy' ) );
						
						$this->log_archive_file_stats( $zip );
							
						$result = true;
						
					} else {
					
						pb_backupbuddy::status( 'details', __('Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
						
				} else {
				
					// Warnings were being ignored so built in final location so no need to move it
					if ( @file_exists( $zip ) ) {
					
						pb_backupbuddy::status( 'message', __( 'Zip Archive file successfully created with no errors (any actionable warnings ignored by user settings).','it-l10n-backupbuddy' ) );
						
						$this->log_archive_file_stats( $zip );
							
						$result = true;
						
					} else {
					
						// Odd condition - file should be present but apparently not?
						pb_backupbuddy::status( 'details', __('Zip Archive file could not be found in local archive directory.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
				
				}
				
			}			

			// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file			
			pb_backupbuddy::status( 'details', __('Removing temporary directory.','it-l10n-backupbuddy' ) );
			
			if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
			
					pb_backupbuddy::status( 'details', __('Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
			
			}
			
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
		
			$summary = '';
			$output = array();
			$exit_code = 127;
			$matches = array();
			$result = false;
			$zippath = '';
			$command = '';
			
			if ( function_exists( 'exec' ) ) {
			
				// Get the command path for the unzip command - should return a trimmed string
				$zippath = $this->get_command_path( self::COMMAND_UNZIP_PATH );
				
				// Determine if we are using an absolute path
				if ( !empty ( $zippath ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	
	
				// We'll try and extract from the backup file to the given directory, very quietly with overwrite
				// If we just did -o we could try and get file count from processing $output but it would be a bit time-consuming
				$unzip_command = $command . " -qqo '{$zip_file}' -d '{$destination_directory}' -x 'importbuddy.php'";
				
				$unzip_command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $unzip_command ) : $unzip_command;
				
				@exec( $unzip_command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to do
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and apparently no extraction problems
						
						// Now we have to do a second run to find out the file count (crazy)
						$list_command = $command . " -ql '{$zip_file}'";
						
						$list_command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $list_command ) : $list_command;
						
						//$summary = @exec( $list_command, $output, $exit_code);
						$summary = @exec( $list_command );

						// Currently don't bother to check exit code, if we failed then whatever we got back in
						// Last output line _should_ have the information we need (and that is returned by exec)
						// $summary is unlikely to match the pattern so file count will just default to 0
						if ( preg_match("|[[:^digit:]]+(?P<byte_count>[[:digit:]]+)[[:^digit:]]+(?P<file_count>[[:digit:]]+)[[:space:]]+(files)|", $summary, $matches ) ) {
						
							// Should be able to pull this straight out provided the unzip version stuck to the rules
							$file_count = $matches[ 'file_count' ];
							
						} else {
						
							// Some reason we didn't get good output or the format is odd
							$file_count = 0;
						}
						
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) extracted file contents (%1$s to %2$s)','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );
	
						$this->log_archive_file_stats( $zip_file );
						
						$result = true;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) failed to open/process file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning an array as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				pb_backupbuddy::status( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				//$result = array( 1, "Class not available to match method" );
				// Currently as we are returning an array as a valid result we just return false on failure
				$result = false;

			}
			
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
		
			$summary = '';
			$output = array();
			$exit_code = 127;
			$matches = array();
			$result = false;
			$zippath = '';
			$command = '';
			$rename_required = false;
			
			if ( function_exists( 'exec' ) ) {
			
				// Get the command path for the unzip command - should return a trimmed string
				$zippath = $this->get_command_path( self::COMMAND_UNZIP_PATH );
				
				// Determine if we are using an absolute path
				if ( !empty ( $zippath ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	
	
				// Now we need to take each item and run an unzip for it - unfortunately there is no easy way of combining
				// arbitrary extractions into a single command if some might be to a 
				foreach ( $items as $what => $where ) {
				
					$rename_required = false;
					$result = false;
				
					// Decide how to extract based on where
					if ( empty( $where) ) {
					
						// Extract direct to destination directory with junked path
						$unzip_command = $command . " -qqoj '{$zip_file}' '{$what}' -d '{$destination_directory}' ";
					
					} elseif ( !empty( $where ) ) {
					
						if ( $what === $where ) {
						
							// Extract to same directory structure - don't junk path, no need to add where to destnation as automatic
							$unzip_command = $command . " -qqo '{$zip_file}' '{$what}' -d '{$destination_directory}' ";
						
						} else {
						
							// Firt we'll extract and junk the path
							$unzip_command = $command . " -qqoj '{$zip_file}' '{$what}' -d '{$destination_directory}' ";
							
							// Will need to rename if the extract is ok
							$rename_required = true;
						
						}
					
					}
				
					$unzip_command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $unzip_command ) : $unzip_command;
				
					@exec( $unzip_command, $output, $exit_code);
				
					// Note: we don't open the file and then do stuff but it's all done in one action
					// so we need to interpret the return code to dedide what to do
					switch ( (int) $exit_code ) {
						case 0:
							// Handled archive and apparently no extraction problems						
							pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) extracted file contents (%1$s from %2$s to %3$s%4$s)','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where ) );
						
							$result = true;
							
							// Rename if we have to
							if ( true === $rename_required) {
							
								// Note: we junked the path on the extraction so just the filename of $what is the source but
								// $where could be a simple file name or a file path 
								$result = $result && rename( $destination_directory . DIRECTORY_SEPARATOR . basename( $what ),
															 $destination_directory . DIRECTORY_SEPARATOR . $where );
								
							}
							break;

						default:
							// For now let's just print the error code and drop through
							$error_string = $exit_code;
							pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) failed to open/process file to extract contents (%1$s from %2$s to %3$s%4$s) - Error Info: %5$s.','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where, $error_string ) );

							// Return an error code and a description - this needs to be handled more generically
							//$result = array( 1, "Unable to get archive contents" );
							// Currently as we are returning an array as a valid result we just return false on failure
							$result = false;
					}
					
					// If the extraction failed (or rename after extraction) then break out of the foreach and simply return false
					if ( false === $result ) {
					
						break;
						
					}
					
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				pb_backupbuddy::status( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				//$result = array( 1, "Class not available to match method" );
				// Currently as we are returning an array as a valid result we just return false on failure
				$result = false;

			}
			
			return $result;
						
		}

		/**
		 *	file_exists()
		 *	
		 *	Tests whether a file (with path) exists in the given zip file
		 *	If leave_open is true then the zip object will be left open for faster checking for subsequent files within this zip
		 *  Note: this is ignored here because it has no meaning in the use of the unzip command
		 *	
		 *	@param		string	$zip_file		The zip file to check
		 *	@param		string	$locate_file	The file to test for
		 *	@param		bool	$leave_open		Optional: True if the zip file should be left open
		 *	@return		bool/array				True if the file is found in the zip and false if not, array for other problem
		 *
		 */
		public function file_exists( $zip_file, $locate_file, $leave_open = false ) {
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->file_exists_generic( $zip_file, $locate_file );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->file_exists_generic( $zip_file, $locate_file );
					break;
				default:
					$result = false;
			}
			
			return $result;
								  	
		}

		/**
		 *	file_exists_generic()
		 *	
		 *	Tests whether a file (with path) exists in the given zip file
		 *	
		 *	@param		string	$zip_file		The zip file to check
		 *	@param		string	$locate_file	The file to test for
		 *	@return		bool/array				True if the file is found in the zip and false if not, array for other problem
		 *
		 */
		public function file_exists_generic( $zip_file, $locate_file ) {
		
			$result = array( 1, "Generic failure indication" );
			$zippath = '';
			$command = '';
			
			if ( function_exists( 'exec' ) ) {
			
				// Get the command path for the unzip command - should return a trimmed string
				$zippath = $this->get_command_path( self::COMMAND_UNZIP_PATH );
				
				// Determine if we are using an absolute path
				if ( !empty ( $zippath ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	
	
				// Try an archive test on the file and we'll just look at the return code
				$command .= " -qt '{$zip_file}' '{$locate_file}'";
				
				$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;

				@exec( $command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to indicate
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and file found and checked out ok so return success
						pb_backupbuddy::status( 'details', __('File found (exec)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						$result = true;
						break;
					case 11:
						// No problem handling archive but file simply not found so return failure
						pb_backupbuddy::status( 'details', __('File not found (exec)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						$result = false;
						break;
					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) failed to open/process file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						$result = array( 1, "Failed to open/process file" );

				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				pb_backupbuddy::status( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				$result = array( 1, "Function not available to match method" );

			}
			
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
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->get_file_list_generic( $zip_file );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->get_file_list_generic( $zip_file );
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}
		
		/*	get_file_list_generic()
		 *	
		 *	Get an array of all files in a zip file with some file properties.
		 *	
		 *	@param		string		$zip_file	The file to list the content of
		 *	@return		bool|array				false on failure, otherwise array of file properties (may be empty)
		 */
		public function get_file_list_generic( $zip_file ) {
		
			$file_list = array();
			$stat_keys = array( 'permissions', 'zip_version', 'zip_os', 'size', 'type_attr', 'compressed_size', 'compression_method', 'mdate', 'filename' );
			$output = array();
			$result = false;
			$zippath = '';
			$command = '';
			
			if ( function_exists( 'exec' ) ) {
			
				// Get the command path for the unzip command - should return a trimmed string
				$zippath = $this->get_command_path( self::COMMAND_UNZIP_PATH );
				
				// Determine if we are using an absolute path
				if ( !empty ( $zippath ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	

				// We'll try and get a *nix style directory listing output and process it
				// Note: we'll ignore stderr output for now as it might interfere
				// Note: the file date given is the stored local time (not UTC which may be stored as well)
				
				// Output format should be like:
				// -rwxr-xr-x  2.3 unx     2729 tx    1099 defN 20120220.231956 file/path/name.ext
				$command .= " -Z --h --t -lT '{$zip_file}'";
				
				$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;

				@exec( $command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to do
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and apparently got a list so try and process it
						
						// Should be one file per line, no more no less
						$file_count = sizeof( $output );
						
						foreach ( $output as $line ) {
						
							// Break up the output based on whitespace for max of 9 fields (last will be filename)
							$stat = array_combine(  $stat_keys , preg_split( "/[\s,]+/", $line, 9 ) );
							
							// Convert screwy date format to a common notation (choose MySQL format)
							$translated_mdate = preg_replace( '/(\d{4})(\d{2})(\d{2}).(\d{2})(\d{2})(\d{2})/','$1-$2-$3 $4:$5:$6' , $stat[ 'mdate' ] );

							// Must convert to a timestamp (using current timezone)
							$stat[ 'mtime' ] = strtotime( $translated_mdate );
							
							$file_list[] = array(
								$stat[ 'filename' ],
								$stat[ 'size' ],
								$stat[ 'compressed_size' ],
								$stat[ 'mtime' ]
							);
							
						}
						
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) listed file contents (%1$s)','it-l10n-backupbuddy' ), $zip_file ) );
	
						$this->log_archive_file_stats( $zip_file );
						
						$result = &$file_list;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) failed to open/process file to list contents (%1$s) - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning an array as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				pb_backupbuddy::status( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				//$result = array( 1, "Class not available to match method" );
				// Currently as we are returning an array as a valid result we just return false on failure
				$result = false;

			}

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
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->set_comment_linux( $zip_file, $comment );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->set_comment_windows( $zip_file, $comment );
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}

		/*	set_comment_windows()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		bool							true on success, otherwise false.
		 */
		public function set_comment_windows( $zip_file, $comment ) {
		
			// This should never be called but just in case return false silently
			return false;
			
		}

		/*	set_comment_linux()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		bool							true on success, otherwise false.
		 */
		public function set_comment_linux( $zip_file, $comment ) {
		
			$output = array();
			$result = false;
			$zippath = '';
			$command = '';
			
			if ( function_exists( 'exec' ) ) {
			
				// Get the command path for the zip command - should return a trimmed string
				$zippath = $this->get_command_path( self::COMMAND_ZIP_PATH );
				
				// Determine if we are using an absolute path
				if ( !empty ( $zippath ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Using absolute zip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'zip';	

				// We have to feed the comment in - trying by pipe here
				// We need to prepend the comment input
				$command .= " -z '{$zip_file}'";
				
				// Note that we escape the comment arg for the shell...
				$command  = 'echo ' . escapeshellarg( $comment ) . ' | ' . $command;
				
				@exec( $command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to do
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and apparently set the comment - no further action required
																		
						pb_backupbuddy::status( 'details', sprintf( __('exec (zip) set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
							
						$result = true;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						pb_backupbuddy::status( 'details', sprintf( __('exec (zip) failed to open/process file to set comment in file %1$s - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning a string as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				pb_backupbuddy::status( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				//$result = array( 1, "Class not available to match method" );
				// Currently as we are returning a string as a valid result we just return false on failure
				$result = false;

			}
			
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
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->get_comment_linux( $zip_file );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->get_comment_windows( $zip_file );
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}
		
		/*	get_comment_windows()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		bool|string					false on failure, Zip comment otherwise.
		 */
		public function get_comment_windows( $zip_file ) {
		
			// This should never be called but just in case return false silently
			return false;
			
		}
	
		/*	get_comment_linux()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		bool|string					false on failure, Zip comment otherwise.
		 */
		public function get_comment_linux( $zip_file ) {

			$output = array();
			$result = false;
			$comment = "";
			$zippath = '';
			$command = '';
			
			if ( function_exists( 'exec' ) ) {
			
				// Get the command path for the unzip command - should return a trimmed string
				$zippath = $this->get_command_path( self::COMMAND_UNZIP_PATH );
				
				// Determine if we are using an absolute path
				if ( !empty ( $zippath ) ) {
				
					pb_backupbuddy::status( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	

				// We expect two lines of output - the first shpuld be the archive name and the second comment if present
				$command .= " -z '{$zip_file}'";
				
				@exec( $command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to do
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and apparently got a comment so try and process it
						
						// Should be one file per line, no more no less
						$line_count = sizeof( $output );
						
						// Must have at least 2 lines for there to be a non-empty comment
						if ( $line_count > 1 ) {
						
							// Simple criteria for now - just take the final line
							// Note that if there is no comment this is still valid as an empty comment
							// so we don't treat this as an error
							//$comment = $output[ $line_count - 1];
							unset( $output[0] );
							$comment = implode( '', $output );
						
						}
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
							
						$result = $comment;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						pb_backupbuddy::status( 'details', sprintf( __('exec (unzip) failed to open/process get comment in file %1$s - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning a string as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				pb_backupbuddy::status( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				//$result = array( 1, "Class not available to match method" );
				// Currently as we are returning a string as a valid result we just return false on failure
				$result = false;

			}
			
			return $result;
			
		}
		
	} // end pluginbuddy_zbzipexec class.	
	
}