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
		const ZIP_CONTENT_FILE_NAME    = 'last_exec_list.txt';
		const ZIP_EXCLUSIONS_FILE_NAME = 'exclusions.txt';
		const ZIP_INCLUSIONS_FILE_NAME = 'inclusions.txt';
		const ZIP_TEST_FILE            = '/zbzip.php'; // Contains file test.txt with content "Hello World"
		const ZIP_TEST_FILE_SIG        = "0a0f9b28c5ff89dfb4f2a0472be0ea8f";
		
		// Possible executable path sets
		const DEFAULT_EXECUTABLE_PATHS = '/usr/local/bin::/usr/bin:/usr/local/sbin:/usr/sbin:/sbin:/bin';
		const WINDOWS_EXECUTABLE_PATHS = '';
		
		// exec specific default for burst handling
		const ZIP_EXEC_DEFAULT_BURST_MAX_PERIOD = 20;

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
		public function __construct( &$parent = null ) {

			parent::__construct( $parent );
			
			// Set the internal flag indicating if exec_dir is set in the PHP environment
			$this->set_exec_dir_flag();
			
			// Setup the OS specific null device for if we need/can use it
			$this->set_null_device();
			
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
					$this->log( 'details', sprintf( __('Unknown OS type (%1$s) could not set executable paths','it-l10n-backupbuddy' ), $this->get_os_type() ) );
					
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
					
					$this->log( 'details', __( 'Exec test (zip) trying executable path:','it-l10n-backupbuddy' ) . ' `' . $path . '`.' );

					$test_file = $tempdir . 'temp_test_' . uniqid() . '.zip';
					
					$command = $this->slashify( $path ) . 'zip' . " '{$test_file}'" . " '" . __FILE__ .  "'";

					$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
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
						
						$this->log( 'details', __('Exec test (zip) PASSED.','it-l10n-backupbuddy' ) );
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
						
							$this->log( 'details', __('Exec test (zip) FAILED: Test zip file not found.','it-l10n-backupbuddy' ) );
						
						}
						
						if ( 0 !== $exec_exit_code ) {
						
							$error_string = $exec_exit_code;
							$this->log( 'details', __('Exec test (zip) FAILED: exec Exit Code: ','it-l10n-backupbuddy' ) . $error_string );
							
						}
						
						$result = false;
				
					}
					
					// Remove the test zip file if it was created
					if ( @file_exists( $test_file ) ) {
					
						if ( !@unlink( $test_file ) ) {
				
							$this->log( 'details', sprintf( __('Exec test (zip) unable to delete test file (%s)','it-l10n-backupbuddy' ), $test_file ) );
					
						}
				
					}
					
				}
				
				
				// If we didn't find zip anywhere (or maybe found it but it failed) then log it
				if ( false === $found_zip ) {
					
					$this->log( 'details', __('Exec test (zip) FAILED: Unable to find zip executable on any specified path.','it-l10n-backupbuddy' ) );
					$result = false;
					
				}
				
				// Remember zip result and reset for unzip test
				$pending_result = $result;
				$result = false;
				
				// See if we can determine zip version and possibly available options. This can help us
				// determine how to execute operations such as creating a zip file
				if ( true === $found_zip ) {
					
					$this->log( 'details', 'Checking zip version...' );

					$this->set_zip_version();
					
					$version = $this->get_zip_version();
					if ( true === is_array( $version ) ) {
			
						( ( 2 == $version[ 'major' ] ) && ( 0 == $version[ 'minor' ] ) ) ? $version[ 'minor' ] = 'X' : true ;
						$this->log( 'details', sprintf( __( 'Found zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
					} else {
			
						$version = array( "major" => "X", "minor" => "Y" );
						$this->log( 'details', sprintf( __( 'Found zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

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
				$this->log( 'details', sprintf( __( 'Exec test (unzip) checking test file readable: %1$s', 'it-l10n-backupbuddy' ), $test_file ) );
				if ( is_readable( $test_file ) ) {
				
					// Only proceed if the file looks as expected
					$this->log( 'details', sprintf( __( 'Exec test (unzip) checking test file intact: %1$s', 'it-l10n-backupbuddy' ), self::ZIP_TEST_FILE_SIG ) );
					if ( self::ZIP_TEST_FILE_SIG === md5_file( $test_file ) ) {
				
						// We are searching for unzip using the list of possible paths
						while ( ( false === $found_zip ) && ( !empty( $candidate_paths ) ) ) {
				
							// Make sure it is clean of leading/trailing whitespace
							$path = trim( array_shift( $candidate_paths ) );
					
							$this->log( 'details', __( 'Exec test (unzip) trying executable path:','it-l10n-backupbuddy' ) . ' `' . $path . '`.' );

							$command = $this->slashify( $path ) . 'unzip -qt' . " '{$test_file}'" . " 'test.txt'";
									
							$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
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
						
								$this->log( 'details', __('Exec test (unzip) PASSED.','it-l10n-backupbuddy' ) );
								$result = true;
				
								// This will break us out of the loop
								$found_zip = true;
						
							} else {
				
								$error_string = $exec_exit_code;
								$this->log( 'details', __('Exec test (unzip) FAILED: Test unzip file test failed.','it-l10n-backupbuddy' ) );
								$this->log( 'details', __('Exec test (unzip) FAILED: exec Exit Code: ','it-l10n-backupbuddy' ) . $error_string );
								$result = false;
				
							}
					
						}
					
					} else {
				
						// The test file looked corrupted so warn and bail out
						$this->log( 'details', sprintf( __('Exec test (unzip) FAILED: Test file appears to be corrupted: %1$s','it-l10n-backupbuddy' ), md5_file( $test_file ) ) );

					}
				
				} else {
				
					// The test file doesn't seem to exist or be readable so warn and bail out
					$this->log( 'details', __('Exec test (unzip) FAILED: Test file appears to either not exist or not be readable.','it-l10n-backupbuddy' ) );
				
				}
			
				// If we didn't find unzip anywhere (or maybe found it but it failed) then log it
				if ( false === $found_zip ) {
					
					// We speculatively set this true when we found zip but we need both zip and unzip so set if false
					$this->_method_details[ 'attr' ][ 'is_commenter' ] = false;

					$this->log( 'details', __('Exec test (unzip) FAILED: Unable to find unzip executable on any specified path.','it-l10n-backupbuddy' ) );
					$result = false;
					
				} else {
				
					// See if we can determine unzip version and possibly available options. This can help us
					// determine how to execute operations such as unzipping a file
					
					$this->log( 'details', 'Checking unzip version...' );

					$this->set_unzip_version();
				
					$version = $this->get_unzip_version();
					if ( true === is_array( $version ) ) {
			
						$this->log( 'details', sprintf( __( 'Found unzip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
					} else {
			
						$version = array( "major" => "X", "minor" => "Y" );
						$this->log( 'details', sprintf( __( 'Found unzip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

					}

				}
				
			} else {
			
				$this->log( 'details', __('Exec test (zip/unzip) FAILED: One or more required function do not exist.','it-l10n-backupbuddy' ) );
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
		 *	The $excludes will be a list or relative path excludes if the $listmaker object is null otehrwise
		 *	will be absolute path excludes and relative path excludes can be had from the $listmaker object
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to create
		 *	@param		string	$dir			Full path of directory to add to ZIP Archive file
		 *	@parame		array	$excludes		List of either absolute path exclusions or relative exclusions
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@return		bool					True if the creation was successful, false otherwise
		 *
		 */
		public function create( $zip, $dir, $excludes, $tempdir ) {
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->create_generic( $zip, $dir, $excludes, $tempdir );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->create_generic( $zip, $dir, $excludes, $tempdir );
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
		 *	The $excludes will be a list or relative path excludes if the $listmaker object is null otehrwise
		 *	will be absolute path excludes and relative path excludes can be had from the $listmaker object
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to create
		 *	@param		string	$dir			Full path of directory to add to ZIP Archive file
		 *	@parame		array	$excludes		List of either absolute path exclusions or relative exclusions
		 *	@param		string	$tempdir		[Optional] Full path of directory for temporary usage
		 *	@return		bool					True if the creation was successful, false otherwise
		 *
		 */
		protected function create_generic( $zip, $dir, $excludes, $tempdir ) {
		
			$result = false;
			$exitcode = 255;
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
			$zip_skipped_count = 0;
			$zip_using_log_file = false;
			$logfile_name = '';
			$contentfile_name = '';
			$contentfile_fp = 0;
			$have_more_content = true;
			$zip_ignoring_symlinks = false;

			$zm = null;
			$lister = null;
			$visitor = null;
			$logger = null;
			$total_size = 0;
			$total_count = 0;
			$the_list = array();
			$zip_error_encountered = false;
			$zip_period_expired = false;
		
			// The basedir must have a trailing normalized directory separator
			$basedir = ( rtrim( trim( $dir ), self::DIRECTORY_SEPARATORS ) ) . self::NORM_DIRECTORY_SEPARATOR;
			
			// Normalize platform specific directory separators in path
			$basedir = str_replace( DIRECTORY_SEPARATOR, self::NORM_DIRECTORY_SEPARATOR, $basedir );

			// Ensure no stale file information
			clearstatcache();
			
			// Create the zip monitor function here
			// Zip monitor will inherit the logger from this object	
			$zm = new pb_backupbuddy_zip_monitor( $this );
//			$zm->set_burst_max_period( self::ZIP_EXEC_DEFAULT_BURST_MAX_PERIOD )->set_burst_threshold_period( 'auto' )->log_parameters();
			$zm->set_burst_size_min( $this->get_min_burst_content() )
			->set_burst_size_max( $this->get_max_burst_content() )
			->set_burst_current_size_threshold( $zm->get_burst_size_min() )
			->log_parameters();

			// Note: could enforce trailing directory separator for robustness
			if ( empty( $tempdir ) || !file_exists( $tempdir ) ) {
			
				// This breaks the rule of single point of exit (at end) but it's early enough to not be a problem
				$this->log( 'details', __('Zip process reported: Temporary working directory must be available.','it-l10n-backupbuddy' ) );				
				return false;
				
			}
			
			// Log the temporary working directory so we might be able to spot problems
			$this->log( 'details', __('Temporary working directory available: ','it-l10n-backupbuddy' ) . '`' . $tempdir . '`' );				
			
			$this->log( 'message', __('Zip process reported: Using Exec Mode.','it-l10n-backupbuddy' ) );
			
			// Tell which zip version is being used
			$version = $this->get_zip_version();
			
			if ( true === is_array( $version ) ) {
			
				( ( 2 == $version[ 'major' ] ) && ( 0 == $version[ 'minor' ] ) ) ? $version[ 'minor' ] = 'X' : true ;
				$this->log( 'details', sprintf( __( 'Zip process reported: Using zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
			} else {
			
				$version = array( "major" => "X", "minor" => "Y" );
				$this->log( 'details', sprintf( __( 'Zip process reported: Using zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

			}
					
			// Get the command path for the zip command - should return a trimmed string
			$zippath = $this->get_command_path( self::COMMAND_ZIP_PATH );
			
			// Determine if we are using an absolute path
			if ( !empty( $zippath ) ) {
			
				$this->log( 'details', __( 'Zip process reported: Using absolute zip path: ','it-l10n-backupbuddy' ) . $zippath );
				
			}

			// Add the trailing slash if required
			$command = $this->slashify( $zippath ) . 'zip';
			
			// Notify the start of the step
			$this->log( 'details', sprintf( __('Zip process reported: Zip archive initial step started with step period threshold: %1$ss','it-l10n-backupbuddy' ), $this->get_step_period() ) );

			// Let's inform what we are excluding/including
			if ( count( $excludes ) > 0 ) {
			
				$this->log( 'details', __('Zip process reported: Calculating directories/files to exclude from backup (relative to site root).','it-l10n-backupbuddy' ) );
				
				foreach ( $excludes as $exclude ) {
				
					if ( !strstr( $exclude, 'backupbuddy_backups' ) ) {

						// Set variable to show we are excluding additional directories besides backup dir.
						$excluding_additional = true;
							
					}
						
					$this->log( 'details', __('Zip process reported: Excluding','it-l10n-backupbuddy' ) . ': ' . $exclude );
					
					$exclude_count++;
						
				}
				
			}
			
			if ( true === $excluding_additional ) {
			
				$this->log( 'message', __( 'Zip process reported: Excluding archives directory and additional directories defined in settings.','it-l10n-backupbuddy' ) . ' ' . $exclude_count . ' ' . __( 'total','it-l10n-backupbuddy' ) . '.' );
				
			} else {
			
				$this->log( 'message', __( 'Zip process reported: Only excluding archives directory based on settings.','it-l10n-backupbuddy' ) . ' ' . $exclude_count . ' ' . __( 'total','it-l10n-backupbuddy' ) . '.' );
				
			}

			$this->log( 'message', __( 'Zip process reported: Determining list of candidate files + directories to be added to the zip archive','it-l10n-backupbuddy' ) );

			// Now let's create the list of files and empty (vacant) directories to include in the backup.
			// Note: we can only include vacant directories (those that had no content in the first place).
			// An empty directory may have had content that was excluded but if we give this directory to
			// pclzip it automatically recurses down into it (we have no control over that) which would then
			// mess up the exclusions. Make sure the visitor only retains a subset of the fields that we need
			// here so as to keep memory usage down.

			$visitor = new pluginbuddy_zbdir_visitor_details( array( 'filename', 'directory', 'vacant', 'relative_path', 'size' ) );
			
			// Give the visitor a logger (maybe we should pass ours) and
			// also a process monitor (give it ours - the "global" one). As
			// the visitor is called regularly by zbdir as the site is scanned
			// we can hook the process monitoring into that.
//			$logger = new pluginbuddy_zipbuddy_logger( 'Zip process reported: ' );
			$visitor->set_logger( $this->get_logger() );
			$visitor->set_process_monitor( $this->get_process_monitor() );

			$options = array( 'exclusions' => $excludes,
							  'pattern_exclusions' => array(),
							  'inclusions' => array(),
							  'pattern_inclusions' => array(),
							  'keep_tree' => false,
							  'ignore_symlinks' => $this->get_ignore_symlinks(),
							  'visitor' => $visitor );
			
			try {
			
				$lister = new pluginbuddy_zbdir( $basedir, $options );
				
				// As we are not keeping the tree we haev already done the visitor pass
				// as the tree was built so our visitor contains all the information we
				// need so we can destroy the lister object
				unset( $lister );
				$result = true;
				
				$this->log( 'message', __( 'Zip process reported: Determined list of candidate files + directories to be added to the zip archive','it-l10n-backupbuddy' ) );

			} catch (Exception $e) {
			
				// We couldn't build the list as required so need to bail
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('Zip process reported: Unable to determine list of candidate files + directories for backup - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

				// TODO: Should do some cleanup of any temporary directory, visitor, etc. but not for now
				$result = false;
				
			}
						
			// In case that took a while use the monitor to try and keep the process alive
			$zm->burst_end();
			$this->get_process_monitor()->checkpoint();

			if ( true === $result ) {	
					
				// Now we have our flat file/directory list from the visitor - remember we didn't
				// keep the tree as we shouldn't need it for anything else as we can get all we need
				// from the visitor. We'll get a list of the subset of things we need from the visitor
				// so we can get rid of the visitor later. We'll use this list later to create our
				// partial inclusion list files to feed to zip for each burst.
				$the_list = $visitor->get_as_array( array( 'filename', 'directory', 'vacant', 'relative_path', 'size' ) );

				// Need to remove empty values now so that we don't get misleading values
				// Here "empty value" means there is no actual path that zip would be able to
				// add and so this would have had to have been ignored later and if we counted
				// it as "vaid" now then numebrs would be awry. In this case it is probably
				// _only_ the entry that would be for the / directory which actually has a
				// empty relative path and filename.
				foreach( $the_list as $key => $value ) {
					if ( empty( $value[ 'relative_path' ] ) && empty( $value[ 'filename' ] ) ) {
						unset( $the_list[ $key ] );
					}
				}
				
				// Save the total count of items to be added
				$total_count = count( $the_list );
				$this->log( 'details', sprintf( __('Zip process reported: %1$s (directories + files) will be requested to be added to backup zip archive','it-l10n-backupbuddy' ), $total_count ) );
				//$zm->set_options( array( 'directory_count' => ( $visitor->count( 'directory' => true ), 'file_count' => $visitor->count( array( 'directory' => false ) ) ) );
				
				// Find the sum total size of all non-directory (i.e., file) items
				// Make sure we can handle >2GB on a 32 bit PHP by using double
				// Note: Currently assuming no single item >2GB size as using the
				// basic size as returned by stat(). We'll likely need to change to
				// use our stat() to allow for up to 4GB item size on 32 bit PHP
				$total_size = (double)0;
				foreach ( $the_list as $the_item ) {
					if ( false === $the_item[ 'directory' ] ) {
						$total_size += (int)$the_item[ 'size' ];
					}
				}
				
				$this->log( 'details', sprintf( __('Zip process reported: %1$s bytes will be requested to be added to backup zip archive','it-l10n-backupbuddy' ), number_format( $total_size, 0, ".", "" ) ) );
				//$zm->set_options( array( 'content_size' => $total_size ) );

				// This is where we want to save the contents list
				$contentfile_name = $tempdir . self::ZIP_CONTENT_FILE_NAME;

				// Now push the list to a file
				$this->log( 'details', sprintf( __('Zip process reported: Writing zip content list to file: %1$s','it-l10n-backupbuddy' ), $contentfile_name ) );

				try {
					
					$contentfile = new SplFileObject( $contentfile_name, "wb" );
					
					// Simple way to ensure we don't get a final empty line in file that messes up
					// the read and json_decode. We could later use different ways such as using
					// marker arrays at start/end so we can include other stuff maybe but this is
					// all we need for now.
					$prefix = '';
										
					foreach ( $the_list as $the_item ) {
					
						$encoded_item = serialize( $the_item );
					
 						// Need to bail out if it looks like we failed to encode the data
						if ( 0 === strlen( $encoded_item ) ) {
						
							throw new Exception( 'Serialization of content list data failed' );
						
						}
						
						$bytes_written = $contentfile->fwrite( $prefix . $encoded_item );
						
						// Be very careful to make sure we had a valid write - in paticular
						// make sure we didn't write 0 bytes since even an empty line from the
						// array should have the PHP_EOL bytes written 
						if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) || ( strlen( $prefix ) >= $bytes_written ) ) {
							throw new Exception( 'Failed to append to content file during creation' );
						}
						
						$prefix = PHP_EOL;

					}
				
				} catch ( Exception $e ) {
					
					// Something fishy - we should have been able to open and
					// write to the content file...
					$error_string = $e->getMessage();
					$this->log( 'details', sprintf( __('Zip process reported: Zip content file could not be created or data not encoded - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

					// Temporary measure for bailing out on problems creting/appending content file
					$result = false;

				}
				
				// We are done with populating the content file
				unset( $contentfile );

				// Retain this for reference for now
 				//file_put_contents( ( dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $the_list, true ) );
			
				// Presently we don't need the visitor any longer so we can free up some
				// memory by deleting. We have all we need in $the_list and we will use this
				// to create our burst content lists
				unset( $visitor );
								
			}
			
			// Only continue if we have a valid list
			// This isn't ideal at present but will suffice
			if ( true === $result ) {			
			
				// Check if the version of zip in use supports log file (which will help with memory usage for large sites)
				if ( true === $this->get_zip_supports_log_file() ) {
			
					// Choose to use log file so quieten stdout - we'll set up the log file later
					$command .= ' -q';
					$zip_using_log_file = true;
			
				}
			
				// Check if we need to turn off compression by settings (faster but larger backup)
				if ( true !== $this->get_compression() ) {
			
					$command .= ' -0';
					$this->log( 'details', __('Zip process reported: Zip archive creation compression disabled based on settings.','it-l10n-backupbuddy' ) );
				
				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation compression enabled based on settings.','it-l10n-backupbuddy' ) );
			
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
							$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will not be followed based on settings.','it-l10n-backupbuddy' ) );
							break;
						case self::OS_TYPE_WIN:
							$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links requested to not be followed based on settings but this option is not supported on this operating system.','it-l10n-backupbuddy' ) );
							break;
						default:
							$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links requested to not be followed based on settings but this option is not supported on this operating system.','it-l10n-backupbuddy' ) );
					}
				
				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will be followed based on settings.','it-l10n-backupbuddy' ) );

				}
			
				// Check if we are ignoring warnings - meaning can still get a backup even
				// if, e.g., some files cannot be read
				if ( true === $this->get_ignore_warnings() ) {
			
					// Note: warnings are being ignored but will still be gathered and logged
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
				
				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
				
				// We want to "grow" a file with each successive "burst". Because we have
				// already created the empty zip archive we can always grow. If we hadn't
				// already created the empty archive the use of grow on the first burst
				// would throw a warning and if we are not ignoring warnings this would
				// halt the backup - option would be to ignore that particular warning
				// but with the already created file that shouldn't be necessary.
				// Note: still will not "grow" an empty zip but rather changes to "add"
				// and throws a warning so really the only way to overcome is to actualy
				// have zip content (a dummy file)
				$command .= ' -g';
			
				// Set up the log file - if $zip_using_log_file is true it means we can log
				// directly to the log file from the zip utility so we'll set that up. If it
				// is false it means the version of zip utility in use cnnot log directly to
				// file so we'll be accumulating the output of each burst into an array and
				// at burst completion we'll append the log details to the log file. So in
				// either case we'll end up with a log file that we process from warnings, etc.
				// This approach gives us a unified process and also makes it easy to handle
				// the log over multiple steps if required.
				$logfile_name = $tempdir . self::ZIP_LOG_FILE_NAME;
				if ( true === $zip_using_log_file ) {
			
					$command .= " -lf '{$logfile_name}' -li -la";
			
				}
						
				// Set temporary directory to store ZIP while it's being generated.			
				$command .= " -b '{$tempdir}'";

				// Temporary zip file is _always_ located in the temp dir now and we move it
				// to the final location after completion if it is a good completion
				$temp_zip = $tempdir . basename( $zip );

				$command .= " '{$temp_zip}' .";
			
				// Now create the inclusions file in the tempdir
				$ifile = $tempdir . self::ZIP_INCLUSIONS_FILE_NAME;
				
				// Now the tricky bit - we have to determine how we are going to give the lisy of files
				// to zip to use. Preferred way would be as a parameter that tells it to include the
				// files listed in the file. Unfortunately there is no such option for zip - a list of
				// files to include in a zip can only be given as discrete file names on the command line
				// or read from stdin. Giving a long list of names on the command line is not
				// feasible so we have to use a stdin based method which is either to cat the file and
				// pipe it in to zip or we can use an stdin file descriptor redirection to fetch the
				// contents of the file. We can only use these methods safely on *nix systems and when
				// exec_dir is not in use.
				// When we cannot use the stdin approach we have to resort to using the -i@file
				// parameter along with the -r recursion option so that zip will match the "patterns"
				// we give it in the file as it recurses the directory tree. This is not an ideal solution
				// because the recursion can be slow on some servers where there is a big directory tree
				// and much of it is irrelevant and does not belong to the site - but we have no other choice.
				// We shouldn't have to use this method very much and it should be ok in many cases
				// where there isn't much that is superfluous in the directory tree.
				// So let's make up the final command to execute based on the operational environment
				// and then we can simply "reuse" the command on each burst, with the addition of the
				// -g option on bursts after the first.
				if ( ( true === $this->get_exec_dir_flag() ) || ( self::OS_TYPE_WIN === $this->get_os_type() ) ) {
				
					// We are running on Windows or using exec_dir so have to use -r and -i@file
					$command .= " -r -i@" . "'{$ifile}'";
				
				} else {
				
					// We aer running under a nice *nix environment so we can use a stdin redirection
					// approach. Let's just use redirection for now as that avoids having to use cat and
					// piping.
				
// 					$command .= " -@";
// 					$command = "cat '{$ifile}' | " . $command;
					
					$command .= " -@";
					$command .= " <'{$ifile}'";
					
				}
			
				// If we can't use a log file but exec_dir isn't in use we can redirect stderr to stdout
				// If exec_dir is in use we cannot redirect because of command line escaping so cannot log errors/warnings
				if ( false === $zip_using_log_file ) {
			
					if ( false === $this->get_exec_dir_flag() ) {
			
						$command .= ' 2>&1';
				
					} else {
				
						$this->log( 'details', sprintf( __( 'Zip process reported: Zip Errors/Warnings cannot not be logged with this version of zip and exec_dir active', 'it-l10n-backupbuddy' ), true ) );
				
					}
				
				} else {
				
					// Using log file but need to redirect stderr to null because zip
					// still seems to send some stuff to stderr as well as to log file.
					// Note: if exec_dir is in use then we cannot redirect so theer may
					// be some stuff gets sent to stderr and logged but it's not worth
					// telling that - if we really need that then we can change the below
					// into an if/then/else and log the condition.
					$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
				
				}
				
				// Remember our "master" command
				$master_command = $command;
				
				// Remember the "master" inclusions list filename
				$master_ifile = $ifile;
				
				// Use this to memorise the worst exit code we had (where we didn't immediately
				// bail out because it signalled a bad failure)
				$max_exitcode = 0;
				
				// Do this as close to when we actually want to start monitoring usage
				// Maybe this is redundant as we have already called this in the constructor.
				// If we want to do this then we have to call with true to reset monitoring to
				// start now.
				$this->get_process_monitor()->initialize_monitoring_usage();
				
				// Now we have our command prototype we can start bursting
				// Simply build a burst list based on content size. Currently no
				// look-ahead so the size will always exceed the current size threshold
				// by some amount. May consider using a look-ahead to see if the next
				// item would exceed the threshold in which case don't add it (unless it
				// would be the only content in which case have to add it but also log
				// a warning).
				// We'll stop either when noting more to add or we have exceeded our step
				// period or we have encountered an error.
				// Note: we might bail out immediately if previous processing has already
				// caused us to exceed the step period.
				while ( $have_more_content &&
						!( $zip_period_expired = $this->exceeded_step_period( $this->get_process_monitor()->get_elapsed_time() ) ) &&
						!$zip_error_encountered ) {
			
					// Populate the content file for zip
					$ilist = array();
					
					// Tell helper that we are preparing a new burst
					$zm->burst_begin();
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
					$this->log( 'details', sprintf( __( 'Zip process reported: Current burst size threshold: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $zm->get_burst_current_size_threshold(), 0, ".", "" ) ) );

					// Open the content list file and seek to the "current" position. This
					// will be initially zero and then updated after each burst. For multi-step
					// it will be zero on the first step and then would be passed back in
					// as a parameter on subsequent steps based on where in the file the previous
					// step reached.
					// TODO: Maybe a sanity check to make sure position seems tenable
					try {
			
						$contentfile = new SplFileObject( $contentfile_name, "rb" );
						$contentfile->fseek( $contentfile_fp );

						// Helper keeps track of what is being added to the burst content and will
						// tell us when the content is sufficient for this burst based on it's
						// criteria - this can adapt to how each successive burst goes.
						while ( ( !$contentfile->eof() ) && ( false === $zm->burst_content_complete() ) ) {
					
							// Should be at least one item to grab from the list and then move to next
							// and remember it for if we drop out because burst content complete, in
							// that case we'll return to that point in the file at the next burst start.
							// Check for unserialize failure and bail
							$item = @unserialize( $contentfile->current() );

							if ( false === $item ) {
							
								throw new Exception( 'Unserialization of content list data failed: `' . $contentfile->current() . '`' );
								
							}
							
							$contentfile->next();
					
							$file = $item[ 'relative_path' ] . $item[ 'filename' ];
						
							// We shouldn't have any empty items here as we should have removed them
							// earlier, but just in case...
							if ( !empty( $file ) ) {
							
								$ilist[] = $file;
							
								// Call the helper event handler as we add each file to the list
								$zm->burst_content_added( $item );
							
							}
						
						}
					
						// Burst list is completed by way of end of content list file or size threshold
						if ( !$contentfile->eof() ) {
					
							// We haven't exhausted the content list yet so remember where we
							// are at for next burst
							$contentfile_fp = $contentfile->ftell();
					
						} else {
					
							// Exhausted the content list so make sure we drop out after this burst
							// if we don't break out of the loop due to a zip error or reached step
							// duration limit
							$have_more_content = false;
					
						}
				
						// Finished one way or another so close content list file for this burst
						unset( $contentfile );
						
					} catch ( Exception $e ) {
			
						// Something fishy - we should have been able to open the content file...
						// TODO: We need to bail out totally here I think
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: Zip content list file could not be opened/read - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
						$exitcode = 255;	
						$zip_error_encountered = true;
						break;
								
					}

				
					// Retain this for reference for now
					//file_put_contents( ( dirname( $tempdir ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $ilist, true ) );

					// Make sure we expunge any previous version of the inclusions file
					if ( file_exists( $ifile ) ) {			
						@unlink( $ifile );			
					}
					
					// Slight kludge for now to make sure each burst content file is uniquely named
					$ifile = str_replace( ".txt", "_". $zm->get_burst_count() . ".txt", $master_ifile );
					
					$file_ok = @file_put_contents( $ifile, implode( PHP_EOL, $ilist ) . PHP_EOL );
					if ( ( false === $file_ok ) || ( 0 === $file_ok ) ) {
					
						// The file write failed for some reason, e.g., no disk space? We need to
						// bail and set exit code so that problem is apparent
						$this->log( 'details', sprintf( __('Zip process reported: Unable to write burst content file: `%1$s`','it-l10n-backupbuddy' ), $ifile ) );
						$exitcode = 255;
						$zip_error_encountered = true;
						break;					

					}
					
					unset( $ilist );
				

					// Remember the current directory and change to the directory being added so that "." is valid in command
					$working_dir = getcwd();
				
					chdir( $dir );
				
					// We don't need to remove the -g option from the command on our first burst any
					// longer because we are creating an initial empty zip that can be grown
					// Note: Sadly we still need to do add on the first burst to avoid empty zip
					// warning (until we can have dumy content in the zip to prevent that warning).
					if ( 1 === $zm->get_burst_count() ) {
						$command = str_replace( "-g", "", $master_command );
					} else {
						$command = $master_command;
					}
					// $command = $master_command;
					
					// Make sure we put the correct burst content file name in the command
					// Slight kludge for now until we build the command line dynamically each burst
					$command = str_replace( $master_ifile, $ifile, $command );
			
					$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;
			
					$this->log( 'details', sprintf( __( 'Zip process reported: Burst requests %1$s (directories + files) items with %2$s bytes of content to be added to backup zip archive', 'it-l10n-backupbuddy' ), $zm->get_burst_content_count(), $zm->get_burst_content_size() ) );
					$this->log( 'details', sprintf( __( 'Zip process reported: Using burst content file: `%1$s`', 'it-l10n-backupbuddy' ), $ifile ) );

					$this->log( 'details', __( 'Zip process reported: ') . $this->get_method_tag() . __(' command','it-l10n-backupbuddy' ) . ': ' . $command );

					// Allow helper to check how the burst goes
					$zm->burst_start();

					// We need the $output array to contain only output for this burst so
					// always reset it before invoking exec.
					$output = array();
					@exec( $command, $output, $exitcode );
					
					// And now we can analyse what happened and plan for next burst if any
					$zm->burst_stop();
					
					// Wrap up the individual burst handling
					// Note: because we called exec we basically went into a wait condition and so (on Linux)
					// we didn't consume any max_execution_time so we never really have to bother about
					// resetting it. However, it is true that time will have elapsed so if this burst _does_
					// take longer than our current burst threshold period then max_execution_time would be
					// reset - but what this doesn't cover is a _cumulative_ effect of bursts and so we might
					// consider reworking the mechanism to monitor this separately from the individual burst
					// period (the confusion relates to this having originally applied to the time based
					// burst handling fro pclzip rather than teh size based for exec). It could also be more
					// relevant for Windows that doesn't stop the clock when exec is called.
					$zm->burst_end();
					$this->get_process_monitor()->checkpoint();
					
					// Now if we are not loggign directly to file we need to append the $output array
					// to the log file - first invocation will create the file.
					if ( false === $zip_using_log_file ) {
					
						$this->log( 'details', sprintf( __('Zip process reported: Appending zip burst log detail to zip log file: %1$s','it-l10n-backupbuddy' ), $logfile_name ) );

						try {
				
							$logfile = new SplFileObject( $logfile_name, "ab" );
							
							foreach ( $output as $line ) {
							
								$bytes_written = $logfile->fwrite( $line . PHP_EOL );
								
								// Be very careful to make sure we had a valid write - in paticular
								// make sure we didn't write 0 bytes since even an empty line from the
								// array should have the PHP_EOL bytes written 
								if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
								
									unset( $logfile );
									unset( $output );
									throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
									
								}
							
							} 
					
							unset( $logfile );
							unset( $output );
					
						} catch ( Exception $e ) {
				
							// Something fishy - we should have been able to open and
							// write to the log file...
							$error_string = $e->getMessage();
							$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened/appended-to - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
					
						}
					
					}
					
					// Report progress at end of burst
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					// Keep a running total of the backup file size (this is temporary code)
					// Using our stat() function in case file size exceeds 2GB on a 32 bit PHP system
					$temp_zip_stats = pluginbuddy_stat::stat( $temp_zip );			
					// Only log anything if we got some valid file stats
					if ( false !== $temp_zip_stats ) {			
						$this->log( 'details', sprintf( __( 'Zip process reported: Accumulated zip archive file size: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $temp_zip_stats[ 'dsize' ], 0, ".", "" ) ) );			
					}
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Ending burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
						
					// Set current working directory back to where we were
					chdir( $working_dir );
					
					// We have to check the exit code to decide whether to keep going ot bail out (break).
					// If we get a 0 exit code ot 18 exit code then keep going and remember we got the 18
					// so that we can emit that as the final exit code if applicable. If we get any other
					// exit code then we must break out immediately.					
					if ( ( 0 !== $exitcode ) && ( 18 !== $exitcode ) ) {
						// Zip failure of some sort - must bail out with current exit code
						$zip_error_encountered = true;
					} else {
						// Make sure exit code is always the worst we've had so that when
						// we've done our last burst we drop out with the correct exit code set
						// This is really to make sure we drop out with exit code 18 if we had
						// this in _any_ burst as we would keep going and subsequent burst(s) may
						// return 0. If we had any other non-zero exit code it would be a "fatal"
						// error and we would have dropped out immediately anyway.
						$exitcode = ( $max_exitcode > $exitcode ) ? $max_exitcode : ( $max_exitcode = $exitcode ) ;
					}
					
					// Now inject a little delay until the next burst. This may be required to give the
					// server time to catch up with finalizing file creation and/or it may be required to
					// reduce the average load a little so there isn't a sustained "peak"
					// Theoretically a sleep could be interrupted by a signal and it would return some
					// non-zero value or false - but if that is the case it probably signals something
					// more troubling so there is little point in tryng to "handle" such a condition here.
					if ( 0 < ( $burst_gap = $this->get_burst_gap() ) ) {
					
						$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst gap delay of: %1$ss', 'it-l10n-backupbuddy' ), $burst_gap ) );
						sleep( $burst_gap );
						
					}
				
				}
				
				// Exited the loop for some reason so decide what to do now.
				// If we didn't exit because of exceeding the step period then it's a
				// normal exit and we'll process accordingly and end up returning true
				// or false. If we exited because of exceeding step period then we need
				// to return the current state array to enable next iteration to pick up
				// where we left off.
				// Note: we might consider having the zip helper give us a state to
				// restore on it when we create one again - but for now we'll not do that
				if ( $zip_period_expired ) {
					
					// Report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step terminated after %1$ss, continuation step will be scheduled','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );

					// Need to set up the state information we'll need to tell the next
					// loop how to set things up to continue. Next time around if another
					// step is required then some of these may be changed and others may
					// stay the same.
					// Note: the method tag 'mt' is used to tell zipbuddy exactly which
					// zipper to use, the one that was picked first time through.
					
					$state = array( 'name' => pluginbuddy_zipbuddy::STATE_NAME_IN_PROGRESS,
									'id' => pluginbuddy_zipbuddy::STATE_ID_IN_PROGRESS,
									'zipbuddy' => array( 'mt' => $this->get_method_tag(),
														),
									'zipper' => array(	'fp' => $contentfile_fp,
														'mec' => $max_exitcode,
														'sp' => $this->get_step_period(),
														'root' => $dir,
														'ts' => $total_size,
														'tc' => $total_count,
														),
									'helper' => array(	'dc' => $zm->get_added_dir_count(),
														'fc' => $zm->get_added_file_count(),
														),
									);
									
					// Now we can return directly as we haev nothing to clear up
					return $state;
					
				}
			
				// Convenience for handling different scanarios
				$result = false;
			
				// We can report how many dirs/files finally added				
				$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (final)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

				// Work out percentage progress on items
				if ( 0 < $total_count ) {
				
					$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (final)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );

				}
					
				// Always logging to file one way or another
				// Always scan the output/logfile for warnings, etc. and show warnings even if user has chosen to ignore them
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
									case "filtered:":										
										$zip_warnings[ self::ZIP_WARNING_FILTERED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "filename too long:":										
										$zip_warnings[ self::ZIP_WARNING_LONGPATH ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "unknown add status:":										
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
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
						
						} elseif ( preg_match( '/^\s*(zip info:)/i', $line ) ) {
						
							// An informational may have associated reason and filename so
							// check for that
							if ( preg_match( '/^\s*(zip info:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
								// Matched to what looks like a file related info so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "ignored symlink:":										
										$zip_other[ self::ZIP_OTHER_IGNORED_SYMLINK ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
								}
						
							} else {
						
								// Didn't match to what would look like a file related info so count it regardless
								$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
								$zip_other_count++;
							
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
							
						} elseif ( preg_match( '/^.*(skipped:)\s*(?P<skipped>\d+)/i', $line, $matches ) ) {
						
							// Each burst may have some skipped files and each will report separately
							if ( isset( $matches[ 'skipped' ] ) ) {
								$zip_skipped_count += $matches[ 'skipped' ];
							}
							
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
					$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				
				}

				// Set convenience flags			
				$have_zip_warnings = ( 0 < $zip_warnings_count );
				$have_zip_errors = ( 0 < $zip_errors_count );
				$have_zip_additions = ( 0 < $zip_additions_count );
				$have_zip_debug = ( 0 < $zip_debug_count );
				$have_zip_other = ( 0 < $zip_other_count );
			
				// Always report the exit code regardless of whether we might ignore it or not
				$this->log( 'details', __('Zip process reported: Zip process exit code: ','it-l10n-backupbuddy' ) . $exitcode );
			
				// Always report the number of warnings - even just to confirm that we didn't have any
				$this->log( 'details', sprintf( __('Zip process reported: %1$s warning%2$s','it-l10n-backupbuddy' ), $zip_warnings_count, ( ( 1 == $zip_warnings_count ) ? '' : 's' ) ) );

				// Always report warnings regardless of whether user has selected to ignore them
				if ( true === $have_zip_warnings ) {
			
					$this->log_zip_reports( $zip_warnings, self::$_warning_desc, "WARNING", self::MAX_WARNING_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_WARNINGS_FILE_NAME );

				}
			
				// Always report other reports regardless
				if ( true === $have_zip_other ) {
			
					// Only report number of informationals if we have any as they are not that important
					$this->log( 'details', sprintf( __('Zip process reported: %1$s information%2$s','it-l10n-backupbuddy' ), $zip_other_count, ( ( 1 == $zip_other_count ) ? 'al' : 'als' ) ) );

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
				
						$this->log( 'details', sprintf( __('Zip process reported: %1$s error%2$s','it-l10n-backupbuddy' ), $zip_errors_count, ( ( 1 == $zip_errors_count ) ? '' : 's' )  ) );
					
						foreach ( $zip_errors as $line ) {
				
							$this->log( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
				
						}
					
					}

					// Report whether or not the zip file was created (whether that be in the final or temporary location)			
					if ( ! @file_exists( $temp_zip ) ) {
				
						$this->log( 'details', __( 'Zip process reported: Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
					
					} else {
					
						$this->log( 'details', __( 'Zip process reported: Zip Archive file created but with errors/actionable-warnings so will be deleted - check process exit code and warnings.','it-l10n-backupbuddy' ) );

					}
				
					// The operation has failed one way or another. Note that as the user didn't choose to ignore errors the zip file
					// is always created in a temporary location and then only moved to final location on success without error or warnings.
					// Therefore if there is a zip file (produced but with warnings) it will not be visible and will be deleted when the
					// temporary directory is deleted below.
				
					$result = false;
				
				} else {
			
					// NOTE: Probably the two paths below can be reduced to one because even if we are
					// ignoring warnings we are still building the zip in temporary location and finally
					// moving it because we are growing it.
					// Got file with no error or warnings _or_ with warnings that the user has chosen to ignore
					if ( false === $this->get_ignore_warnings() ) {
				
						// Because not ignoring warnings the zip archive was built in temporary location so we need to move it
						$this->log( 'details', __('Zip process reported: Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
				
						// Make sure no stale file information
						clearstatcache();
					
						@rename( $temp_zip, $zip );
					
						if ( @file_exists( $zip ) ) {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
							$this->log( 'message', __( 'Zip process reported: Zip Archive file successfully created with no errors or actionable warnings.','it-l10n-backupbuddy' ) );
						
							$this->log_archive_file_stats( $zip, array( 'content_size' => $total_size ) );
							
							// Temporary for now - try and incorporate into stats logging (makes the stats logging function part of the zip helper class?)
							$this->log( 'details', sprintf( __('Zip process reported: Zip Archive file size: %1$s of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ), $total_count ) );
					
							// Work out percentage on items
							if ( 0 < $total_count ) {
					
								$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ) / $total_count ) * 100 );
								$this->log( 'details', sprintf( __('Zip process reported: Zip archive file size: %1$s%% of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
							}
					
							$result = true;
						
						} else {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
							$result = false;
						
						}
						
					} else {
				
						// With multi-burst we haev to always build the zip in temp location  so always have to move it
						$this->log( 'details', __('Zip process reported: Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
				
						// Make sure no stale file information
						clearstatcache();
					
						@rename( $temp_zip, $zip );
						
						if ( @file_exists( $zip ) ) {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
							$this->log( 'message', __( 'Zip process reported: Zip Archive file successfully created with no errors (any actionable warnings ignored by user settings).','it-l10n-backupbuddy' ) );
						
							$this->log_archive_file_stats( $zip, array( 'content_size' => $total_size ) );
							
							// Temporary for now - try and incorporate into stats logging (makes the stats logging function part of the zip helper class?)
							$this->log( 'details', sprintf( __('Zip process reported: Zip Archive file size: %1$s of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ), $total_count ) );
					
							// Work out percentage on items
							if ( 0 < $total_count ) {
					
								$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ) / $total_count ) * 100 );
								$this->log( 'details', sprintf( __('Zip process reported: Zip archive file size: %1$s%% of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
							}
							
							$result = true;
						
						} else {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
							$result = false;
						
						}
				
					}
				
				}
				
			}		

			// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file			
			$this->log( 'details', __('Zip process reported: Removing temporary directory.','it-l10n-backupbuddy' ) );
			
			if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
			
					$this->log( 'details', __('Zip process reported: Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
			
			}
			
			return $result;
												
		}
		
		/**
		 *	grow()
		 *	
		 *	A function that grows an archive file from already calculated contet list
		 *	Always cleans up after itself
		 *	
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to grow
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@param		array	$state
		 *	@return		bool					True if the creation was successful, array for continuation, false otherwise
		 *
		 */
		public function grow( $zip, $tempdir, $state ) {
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->grow_generic( $zip, $tempdir, $state );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->grow_generic( $zip, $tempdir, $state );
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}
			
		/**
		 *	grow_generic()
		 *	
		 *	A function that grows an archive file from already calculated contet list
		 *	
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to grow
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@param		array	$state
		 *	@return		bool					True if the creation was successful, array for continuation, false otherwise
		 *
		 */
		protected function grow_generic( $zip, $tempdir, $state ) {
		
			$result = false;
			$exitcode = 255;
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
			$zip_skipped_count = 0;
			$zip_using_log_file = false;
			$logfile_name = '';
			$contentfile_name = '';
			$contentfile_fp = 0;
			$contentfile_fp_start = 0;
			$have_more_content = true;
			$zip_ignoring_symlinks = false;

			$zm = null;
			$lister = null;
			$visitor = null;
			$logger = null;
			$total_size = 0;
			$total_count = 0;
			$the_list = array();
			$zip_error_encountered = false;
			$zip_period_expired = false;
		
			// Ensure no stale file information
			clearstatcache();
			
			// Create the monitor function here	
			$zm = new pb_backupbuddy_zip_monitor( $this );
//			$zm->set_burst_max_period( self::ZIP_EXEC_DEFAULT_BURST_MAX_PERIOD )->set_burst_threshold_period( 'auto' )->log_parameters();
			$zm->set_burst_size_min( $this->get_min_burst_content() )
			->set_burst_size_max( $this->get_max_burst_content() )
			->set_burst_current_size_threshold( $zm->get_burst_size_min() )
			->log_parameters();

			// Set some state on it
			$zm->set_added_dir_count( $state[ 'helper' ][ 'dc' ] );
			$zm->set_added_file_count( $state[ 'helper' ][ 'fc' ] );
				
			// Note: could enforce trailing directory separator for robustness
			if ( empty( $tempdir ) || !file_exists( $tempdir ) ) {
			
				// This breaks the rule of single point of exit (at end) but it's early enough to not be a problem
				$this->log( 'details', __('Zip process reported: Temporary working directory must be available.','it-l10n-backupbuddy' ) );				
				return false;
				
			}
			
			// Log the temporary working directory so we might be able to spot problems
			$this->log( 'details', __('Zip process reported: Temporary working directory available: ','it-l10n-backupbuddy' ) . '`' . $tempdir . '`' );				
			
			$this->log( 'message', __('Zip process reported: Using Exec Mode.','it-l10n-backupbuddy' ) );
			
			// Tell which zip version is being used
			$version = $this->get_zip_version();
			
			if ( true === is_array( $version ) ) {
			
				( ( 2 == $version[ 'major' ] ) && ( 0 == $version[ 'minor' ] ) ) ? $version[ 'minor' ] = 'X' : true ;
				$this->log( 'details', sprintf( __( 'Zip process reported: Using zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );
				
			} else {
			
				$version = array( "major" => "X", "minor" => "Y" );
				$this->log( 'details', sprintf( __( 'Zip process reported: Using zip version: %1$s.%2$s', 'it-l10n-backupbuddy' ), $version[ 'major' ], $version[ 'minor' ] ) );

			}
					
			// Get the command path for the zip command - should return a trimmed string
			$zippath = $this->get_command_path( self::COMMAND_ZIP_PATH );
			
			// Determine if we are using an absolute path
			if ( !empty( $zippath ) ) {
			
				$this->log( 'details', __( 'Zip process reported: Using absolute zip path: ','it-l10n-backupbuddy' ) . $zippath );
				
			}

			// Add the trailing slash if required
			$command = $this->slashify( $zippath ) . 'zip';
						
			// Notify the start of the step
			$this->log( 'details', sprintf( __('Zip process reported: Zip archive continuation step started with step period threshold: %1$ss','it-l10n-backupbuddy' ), $this->get_step_period() ) );

			// In case that took a while use the monitor to try and keep the process alive
			$zm->burst_end();
			$this->get_process_monitor()->checkpoint();

			// Temporary convenience
			$result = true;
			
			// This is where we previously calculated this when deriving the list
			$total_size = $state[ 'zipper' ][ 'ts' ];
			$total_count = $state[ 'zipper' ][ 'tc' ];
			
			// Only continue if we have a valid list
			// This isn't ideal at present but will suffice
			if ( true === $result ) {			
			
				// Check if the version of zip in use supports log file (which will help with memory usage for large sites)
				if ( true === $this->get_zip_supports_log_file() ) {
			
					// Choose to use log file so quieten stdout - we'll set up the log file later
					$command .= ' -q';
					$zip_using_log_file = true;
			
				}
			
				// Check if we need to turn off compression by settings (faster but larger backup)
				if ( true !== $this->get_compression() ) {
			
					$command .= ' -0';
					$this->log( 'details', __('Zip process reported: Zip archive creation compression disabled based on settings.','it-l10n-backupbuddy' ) );
				
				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation compression enabled based on settings.','it-l10n-backupbuddy' ) );
			
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
							$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will not be followed based on settings.','it-l10n-backupbuddy' ) );
							break;
						case self::OS_TYPE_WIN:
							$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links requested to not be followed based on settings but this option is not supported on this operating system.','it-l10n-backupbuddy' ) );
							break;
						default:
							$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links requested to not be followed based on settings but this option is not supported on this operating system.','it-l10n-backupbuddy' ) );
					}
				
				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will be followed based on settings.','it-l10n-backupbuddy' ) );

				}
			
				// Check if we are ignoring warnings - meaning can still get a backup even
				// if, e.g., some files cannot be read
				if ( true === $this->get_ignore_warnings() ) {
			
					// Note: warnings are being ignored but will still be gathered and logged
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
				
				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
				
				// We want to "grow" a file with each successive "burst" after the first. If the zip
				// file doesn't exist when -g is given it will be created - but the problem is that
				// zip also throws a warning and if we are not ignoring warnings we get caught on this.
				// Currently we'll check if this is the first burst and if it is we'll remove the
				// -g option from the command so that the archive file is created by default.
				// TODO: Later we are always going to have created the archive before we start
				// adding content (so that we can add the archive comment when the file is small)
				// and then we'll always use -g option for every burts.
				$command .= ' -g';
			
				// Set up the log file - if $zip_using_log_file is true it means we can log
				// directly to the log file from the zip utility so we'll set that up. If it
				// is false it means the version of zip utility in use cnnot log directly to
				// file so we'll be accumulating the output of each burst into an array and
				// at burst completion we'll append the log details to the log file. So in
				// either case we'll end up with a log file that we process from warnings, etc.
				// This approach gives us a unified process and also makes it easy to handle
				// the log over multiple steps if required.
				$logfile_name = $tempdir . self::ZIP_LOG_FILE_NAME;
				if ( true === $zip_using_log_file ) {
			
					$command .= " -lf '{$logfile_name}' -li -la";
			
				}
						
				// Set temporary directory to store ZIP while it's being generated.			
				$command .= " -b '{$tempdir}'";

				// Temporary zip file is _always_ located in the temp dir now and we move it
				// to the final location after completion if it is a good completion
				$temp_zip = $tempdir . basename( $zip );

				$command .= " '{$temp_zip}' .";
			
				// Now create the inclusions file in the tempdir
				$ifile = $tempdir . self::ZIP_INCLUSIONS_FILE_NAME;
				
				// Now the tricky bit - we have to determine how we are going to give the lisy of files
				// to zip to use. Preferred way would be as a parameter that tells it to include the
				// files listed in the file. Unfortunately there is no such option for zip - a list of
				// files to include in a zip can only be given as discrete file names on the command line
				// or read from stdin. Giving a long list of names on the command line is not
				// feasible so we have to use a stdin based method which is either to cat the file and
				// pipe it in to zip or we can use an stdin file descriptor redirection to fetch the
				// contents of the file. We can only use these methods safely on *nix systems and when
				// exec_dir is not in use.
				// When we cannot use the stdin approach we have to resort to using the -i@file
				// parameter along with the -r recursion option so that zip will match the "patterns"
				// we give it in the file as it recurses the directory tree. This is not an ideal solution
				// because the recursion can be slow on some servers where there is a big directory tree
				// and much of it is irrelevant and does not belong to the site - but we have no other choice.
				// We shouldn't have to use this method very much and it should be ok in many cases
				// where there isn't much that is superfluous in the directory tree.
				// So let's make up the final command to execute based on the operational environment
				// and then we can simply "reuse" the command on each burst, with the addition of the
				// -g option on bursts after the first.
				if ( ( true === $this->get_exec_dir_flag() ) || ( self::OS_TYPE_WIN === $this->get_os_type() ) ) {
				
					// We are running on Windows or using exec_dir so have to use -r and -i@file
					$command .= " -r -i@" . "'{$ifile}'";
				
				} else {
				
					// We aer running under a nice *nix environment so we can use a stdin redirection
					// approach. Let's just use redirection for now as that avoids having to use cat and
					// piping.
				
// 					$command .= " -@";
// 					$command = "cat '{$ifile}' | " . $command;
					
					$command .= " -@";
					$command .= " <'{$ifile}'";
					
				}
			
				// If we can't use a log file but exec_dir isn't in use we can redirect stderr to stdout
				// If exec_dir is in use we cannot redirect because of command line escaping so cannot log errors/warnings
				if ( false === $zip_using_log_file ) {
			
					if ( false === $this->get_exec_dir_flag() ) {
			
						$command .= ' 2>&1';
				
					} else {
				
						$this->log( 'details', sprintf( __( 'Zip process reported: Zip Errors/Warnings cannot not be logged with this version of zip and exec_dir active', 'it-l10n-backupbuddy' ), true ) );
				
					}
				
				} else {
				
					// Using log file but need to redirect stderr to null because zip
					// still seems to send some stuff to stderr as well as to log file.
					// Note: if exec_dir is in use then we cannot redirect so theer may
					// be some stuff gets sent to stderr and logged but it's not worth
					// telling that - if we really need that then we can change the below
					// into an if/then/else and log the condition.
					$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
				
				}
				
				// Remember our "master" command
				$master_command = $command;
				
				// Remember the "master" inclusions list filename
				$master_ifile = $ifile;
				
				// Use this to memorise the worst exit code we had (where we didn't immediately
				// bail out because it signalled a bad failure)
				$max_exitcode = $state[ 'zipper' ][ 'mec' ];
				
				// This is where we want to read the contens from
				$contentfile_name = $tempdir . self::ZIP_CONTENT_FILE_NAME;

				// Need to setup where we are going to dip into the content file
				// and remember the start position so we can test for whether we
				// actually advanced through our content or not.
				$contentfile_fp = $state[ 'zipper' ][ 'fp' ];
				$contentfile_fp_start = $contentfile_fp;
				
				// Do this as close to when we actually want to start monitoring usage
				// Maybe this is redundant as we have already called this in the constructor.
				// If we want to do this then we have to call with true to reset monitoring to
				// start now.
				$this->get_process_monitor()->initialize_monitoring_usage();
				
				// Now we have our command prototype we can start bursting
				// Simply build a burst list based on content size. Currently no
				// look-ahead so the size will always exceed the current size threshold
				// by some amount. May consider using a look-ahead to see if the next
				// item would exceed the threshold in which case don't add it (unless it
				// would be the only content in which case have to add it but also log
				// a warning).
				// We'll stop either when nothing more to add or we have exceeded our step
				// period or we have encountered an error.
				// Note: we might bail out immediately if previous processing has already
				// caused us to exceed the step period. We need to detect this as a corner
				// case otherwise we can go into an infinite loop because we have more
				// content and no error but we never get a chance to advance through the
				// content.
				while ( $have_more_content &&
						!( $zip_period_expired = $this->exceeded_step_period( $this->get_process_monitor()->get_elapsed_time() ) ) &&
						!$zip_error_encountered ) {
			
					// Populate the content file for zip
					$ilist = array();
					
					// Tell helper that we are preparing a new burst
					$zm->burst_begin();
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
					$this->log( 'details', sprintf( __( 'Zip process reported: Current burst size threshold: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $zm->get_burst_current_size_threshold(), 0, ".", "" ) ) );

					// Open the content list file and seek to the "current" position. This
					// will be initially zero and then updated after each burst. For multi-step
					// it will be zero on the first step and then would be passed back in
					// as a parameter on subsequent steps based on where in the file the previous
					// step reached.
					// TODO: Maybe a sanity check to make sure position seems tenable
					try {
			
						$contentfile = new SplFileObject( $contentfile_name, "rb" );
						$contentfile->fseek( $contentfile_fp );

						// Helper keeps track of what is being added to the burst content and will
						// tell us when the content is sufficient for this burst based on it's
						// criteria - this can adapt to how each successive burst goes.
						while ( ( !$contentfile->eof() ) && ( false === $zm->burst_content_complete() ) ) {
					
							// Should be at least one item to grab from the list and then move to next
							// and remember it for if we drop out because burst content complete, in
							// that case we'll return to that point in the file at the next burst start.
							// Check for unserialize failure and bail
							$item = @unserialize( $contentfile->current() );

							if ( false === $item ) {
							
								throw new Exception( 'Unserialization of content list data failed: `' . $contentfile->current() . '`' );
								
							}
							
							$contentfile->next();
					
							$file = $item[ 'relative_path' ] . $item[ 'filename' ];
						
							// We shouldn't have any empty items here as we should have removed them
							// earlier, but just in case...
							if ( !empty( $file ) ) {
								$ilist[] = $file;
							
								// Call the helper event handler as we add each file to the list
								$zm->burst_content_added( $item );
							
							}
						
						}
					
						// Burst list is completed by way of end of content list file or size threshold
						if ( !$contentfile->eof() ) {
					
							// We haven't exhausted the content list yet so remember where we
							// are at for next burst (which may be in a following step)
							$contentfile_fp = $contentfile->ftell();
					
						} else {
					
							// Exhausted the content list so make sure we drop out after this burst
							// if we don't break out of the loop due to a zip error or reached step
							// duration limit. We must not schedule a new step if this burst has
							// exhausted the contents file.
							$have_more_content = false;
					
						}
				
						// Finished one way or another so close content list file for this burst
						unset( $contentfile );
				
					} catch ( Exception $e ) {
			
						// Something fishy - we should have been able to open the content file...
						// TODO: We need to bail out totally here I think
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: Zip content list file could not be opened/read - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
						$exitcode = 255;
						$zip_error_encountered = true;
						break;					
				
					}

					// Retain this for reference for now
					//file_put_contents( ( dirname( $tempdir ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $ilist, true ) );

					// Make sure we expunge any previous version of the inclusions file
					if ( file_exists( $ifile ) ) {			
						@unlink( $ifile );			
					}
					
					// Slight kludge for now to make sure each burst content file is uniquely named
					$ifile = str_replace( ".txt", "_". $zm->get_burst_count() . ".txt", $master_ifile );
					
					$file_ok = @file_put_contents( $ifile, implode( PHP_EOL, $ilist ) . PHP_EOL );
					if ( ( false === $file_ok ) || ( 0 === $file_ok ) ) {
					
						// The file write failed for some reason, e.g., no disk space? We need to
						// bail and set exit code so that problem is apparent
						$this->log( 'details', sprintf( __('Zip process reported: Unable to write burst content file: `%1$s`','it-l10n-backupbuddy' ), $ifile ) );
						$exitcode = 255;
						$zip_error_encountered = true;
						break;					

					}
					
					unset( $ilist );
				
					// Remember the current directory and change to the directory being added so that "." is valid in command
					$working_dir = getcwd();
				
					chdir( $state[ 'zipper' ][ 'root' ] );
				
					// As we are growing the zip we always use -g so no need to filter it out on first burst in this step
					$command = $master_command;
					
					// Make sure we put the correct burst content file name in the command
					// Slight kludge for now until we build the command line dynamically each burst
					$command = str_replace( $master_ifile, $ifile, $command );
			
					$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;
			
					$this->log( 'details', sprintf( __( 'Zip process reported: Burst requests %1$s (directories + files) items with %2$s bytes of content to be added to backup zip archive', 'it-l10n-backupbuddy' ), $zm->get_burst_content_count(), $zm->get_burst_content_size() ) );
					$this->log( 'details', sprintf( __( 'Zip process reported: Using burst content file: `%1$s`', 'it-l10n-backupbuddy' ), $ifile ) );

					$this->log( 'details', __( 'Zip process reported: ') . $this->get_method_tag() . __(' command','it-l10n-backupbuddy' ) . ': ' . $command );

					// Allow helper to check how the burst goes
					$zm->burst_start();

					// We need the $output array to contain only output for this burst so
					// always reset it before invoking exec.
					$output = array();
					@exec( $command, $output, $exitcode );
					
					// And now we can analyse what happened and plan for next burst if any
					$zm->burst_stop();
					
					// Wrap up the individual burst handling
					// Note: because we called exec we basically went into a wait condition and so (on Linux)
					// we didn't consume any max_execution_time so we never really have to bother about
					// resetting it. However, it is true that time will have elapsed so if this burst _does_
					// take longer than our current burst threshold period then max_execution_time would be
					// reset - but what this doesn't cover is a _cumulative_ effect of bursts and so we might
					// consider reworking the mechanism to monitor this separately from the individual burst
					// period (the confusion relates to this having originally applied to the time based
					// burst handling fro pclzip rather than teh size based for exec). It could also be more
					// relevant for Windows that doesn't stop the clock when exec is called.
					$zm->burst_end();
					$this->get_process_monitor()->checkpoint();
					
					// Now if we are not logging directly to file we need to append the $output array
					// to the log file - first invocation will create the file.
					if ( false === $zip_using_log_file ) {
					
						$this->log( 'details', sprintf( __('Zip process reported: Appending zip burst log detail to zip log file: %1$s','it-l10n-backupbuddy' ), $logfile_name ) );

						try {
				
							$logfile = new SplFileObject( $logfile_name, "ab" );
							
							foreach ( $output as $line ) {
							
								$bytes_written = $logfile->fwrite( $line . PHP_EOL );
								
								// Be very careful to make sure we had a valid write - in paticular
								// make sure we didn't write 0 bytes since even an empty line from the
								// array should have the PHP_EOL bytes written 
								if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
									throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
								}
							
							} 
					
							unset( $logfile );
							unset( $output );
					
						} catch ( Exception $e ) {
				
							// Something fishy - we should have been able to open and
							// write to the log file...
							$error_string = $e->getMessage();
							$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened/appended-to - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

							// Put the log file away - safe even if we failed to get a logfile
							unset( $logfile );
							
							// And throw away the output array as we cannot use it
							unset( $output );
					
						}
					
					}
					
					// Report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					// Keep a running total of the backup file size (this is temporary code)
					// Using our stat() function in case file size exceeds 2GB on a 32 bit PHP system
					$temp_zip_stats = pluginbuddy_stat::stat( $temp_zip );
							
					// Only log anything if we got some valid file stats
					if ( false !== $temp_zip_stats ) {
								
						$this->log( 'details', sprintf( __( 'Zip process reported: Accumulated zip archive file size: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $temp_zip_stats[ 'dsize' ], 0, ".", "" ) ) );			

					}
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Ending burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
						
					// Set current working directory back to where we were
					chdir( $working_dir );

					// We have to check the exit code to decide whether to keep going ot bail out (break).
					// If we get a 0 exit code ot 18 exit code then keep going and remember we got the 18
					// so that we can emit that as the final exit code if applicable. If we get any other
					// exit code then we must break out immediately.					
					if ( ( 0 !== $exitcode ) && ( 18 !== $exitcode ) ) {
						// Zip failure of some sort - must bail out with current exit code
						$zip_error_encountered = true;
					} else {
						// Make sure exit code is always the worst we've had so that when
						// we've done our last burst we drop out with the correct exit code set
						// This is really to make sure we drop out with exit code 18 if we had
						// this in _any_ burst as we would keep going and subsequent burst(s) may
						// return 0. If we had any other non-zero exit code it would be a "fatal"
						// error and we would have dropped out immediately anyway.
						$exitcode = ( $max_exitcode > $exitcode ) ? $max_exitcode : ( $max_exitcode = $exitcode ) ;
					}
					
					// Now inject a little delay until the next burst. This may be required to give the
					// server time to catch up with finalizing file creation and/or it may be required to
					// reduce the average load a little so there isn't a sustained "peak"
					// Theoretically a sleep could be interrupted by a signal and it would return some
					// non-zero value or false - but if that is the case it probably signals something
					// more troubling so there is little point in tryng to "handle" such a condition here.
					if ( 0 < ( $burst_gap = $this->get_burst_gap() ) ) {
					
						$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst gap delay of: %1$ss', 'it-l10n-backupbuddy' ), $burst_gap ) );
						sleep( $burst_gap );
						
					}
				
				}
				
				// Exited the loop for some reason so decide what to do now.
				//
				// We only want to invoke another step if the zip period has expired,
				// there is more content and no error was encountered (based on zip
				// exit code). For any other combination of conditions that we ended
				// up exiting the loop for we simply want to drop though and process
				// final success or failure.
				//
				// Expiry of zip period is determined before the start of the loop
				// (given by $zip_period_expired boolean)
				// More content is determined by not having reached eof on contents
				// file during latest burst (given by $have_more_content boolean) - note
				// we should never enter this function if the contents file eof has
				// been reached in a previous step so we don't test eof at the start
				// of the loop, only during the loop when constructing content list for
				// a burst.
				// Zip error encountered is determined by the zip utility exit code
				// during the loop from the latest burst (given by $zip_error_encountered
				// boolean).
				// 
				// Note: we might consider having the zip helper give us a state to
				// restore on it when we create one again - but for now we'll not do that
				if ( $zip_period_expired && $have_more_content && !$zip_error_encountered ) {
					
					// Conditions are good for running a new step but make sure that we
					// did actually progress through the content with at least one burst
					// before zip period expiry was detected. We can do this by checking
					// that the content file pointer (that we would pass to a next step)
					// is not the same as the value it had at the start of current step.
					// If we find no progress then there is a server issue and we need
					// to bail out with a failure which we can do by setting an error
					// exit code.
					
					// Always report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items requested to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					if ( $contentfile_fp <> $contentfile_fp_start ) {
						
						// We have advanced through content file
						
						$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step terminated after %1$ss, continuation step will be scheduled','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );
						
						// Need to set up the state information we'll need to tell the next
						// loop how to set things up to continue. Next time around if another
						// step is required then some of these may be changed and others may
						// stay the same.
						// Note: the method tag 'mt' is used to tell zipbuddy exactly which
						// zipper to use, the one that was picked first time through.
					
						$new_state = $state;
						$new_state[ 'zipper' ][ 'fp' ] = $contentfile_fp;
						$new_state[ 'zipper' ][ 'mec' ] = $max_exitcode;
						$new_state[ 'zipper' ][ 'sp' ] = $this->get_step_period();
						$new_state[ 'helper' ][ 'dc' ] = $zm->get_added_dir_count();
						$new_state[ 'helper' ][ 'fc' ] = $zm->get_added_file_count();
									
						// Now we can return directly as we have nothing to clear up
						return $new_state;
					
					} else {
						
						// It appears the content file pointer didn't change so we
						// haven't advanced through the content for some reason so
						// we need to bail out as there is a risk of getting into
						// an infinite loop
					
						$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step did not progress through content due to unknown server issue','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );
						$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step terminated after %1$ss, continuation step will not be scheduled due to abnormal progress indication','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );

						// Set a generic exit code to force termination of the build
						// after what we have so far is processed
						$exitcode = 255;
						$zip_error_encountered = true;
					
					}
					
				}
			
				// Convenience for handling different scanarios
				$result = false;
			
				// We can report how many dirs/files finally added				
				$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items requested to be added to backup zip archive (final)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

				// Work out percentage progress on items
				if ( 0 < $total_count ) {
				
					$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (final)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );

				}
					
				// Always logging to file one way or another
				// Always scan the output/logfile for warnings, etc. and show warnings even if user has chosen to ignore them
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
									case "filtered:":										
										$zip_warnings[ self::ZIP_WARNING_FILTERED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "filename too long:":										
										$zip_warnings[ self::ZIP_WARNING_LONGPATH ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "unknown add status:":										
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
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
						
						} elseif ( preg_match( '/^\s*(zip info:)/i', $line ) ) {
						
							// An informational may have associated reason and filename so
							// check for that
							if ( preg_match( '/^\s*(zip info:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
								// Matched to what looks like a file related info so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "ignored symlink:":										
										$zip_other[ self::ZIP_OTHER_IGNORED_SYMLINK ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
								}
						
							} else {
						
								// Didn't match to what would look like a file related info so count it regardless
								$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
								$zip_other_count++;
							
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
							
						} elseif ( preg_match( '/^.*(skipped:)\s*(?P<skipped>\d+)/i', $line, $matches ) ) {
						
							// Each burst may have some skipped files and each will report separately
							if ( isset( $matches[ 'skipped' ] ) ) {
								$zip_skipped_count += $matches[ 'skipped' ];
							}
							
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
					$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				
				}

				// Set convenience flags			
				$have_zip_warnings = ( 0 < $zip_warnings_count );
				$have_zip_errors = ( 0 < $zip_errors_count );
				$have_zip_additions = ( 0 < $zip_additions_count );
				$have_zip_debug = ( 0 < $zip_debug_count );
				$have_zip_other = ( 0 < $zip_other_count );
			
				// Always report the exit code regardless of whether we might ignore it or not
				$this->log( 'details', __('Zip process reported: Zip process exit code: ','it-l10n-backupbuddy' ) . $exitcode );
			
				// Always report the number of warnings - even just to confirm that we didn't have any
				$this->log( 'details', sprintf( __('Zip process reported: Zip process reported: %1$s warning%2$s','it-l10n-backupbuddy' ), $zip_warnings_count, ( ( 1 == $zip_warnings_count ) ? '' : 's' ) ) );

				// Always report warnings regardless of whether user has selected to ignore them
				if ( true === $have_zip_warnings ) {
			
					$this->log_zip_reports( $zip_warnings, self::$_warning_desc, "WARNING", self::MAX_WARNING_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_WARNINGS_FILE_NAME );

				}
			
				// Always report other reports regardless
				if ( true === $have_zip_other ) {
			
					// Only report number of informationals if we have any as they are not that important
					$this->log( 'details', sprintf( __('Zip process reported: %1$s information%2$s','it-l10n-backupbuddy' ), $zip_other_count, ( ( 1 == $zip_other_count ) ? 'al' : 'als' ) ) );

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
				
						$this->log( 'details', sprintf( __('Zip process reported: %1$s error%2$s','it-l10n-backupbuddy' ), $zip_errors_count, ( ( 1 == $zip_errors_count ) ? '' : 's' )  ) );
					
						foreach ( $zip_errors as $line ) {
				
							$this->log( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
				
						}
					
					}

					// Report whether or not the zip file was created (whether that be in the final or temporary location)			
					if ( ! @file_exists( $temp_zip ) ) {
				
						$this->log( 'details', __( 'Zip process reported: Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
					
					} else {
					
						$this->log( 'details', __( 'Zip process reported: Zip Archive file created but with errors/actionable-warnings so will be deleted - check process exit code and warnings.','it-l10n-backupbuddy' ) );

					}
				
					// The operation has failed one way or another. Note that as the user didn't choose to ignore errors the zip file
					// is always created in a temporary location and then only moved to final location on success without error or warnings.
					// Therefore if there is a zip file (produced but with warnings) it will not be visible and will be deleted when the
					// temporary directory is deleted below.
				
					$result = false;
				
				} else {
			
					// NOTE: Probably the two paths below can be reduced to one because even if we are
					// ignoring warnings we are still building the zip in temporary location and finally
					// moving it because we are growing it.
					// Got file with no error or warnings _or_ with warnings that the user has chosen to ignore
					if ( false === $this->get_ignore_warnings() ) {
				
						// Because not ignoring warnings the zip archive was built in temporary location so we need to move it
						$this->log( 'details', __('Zip process reported: Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
				
						// Make sure no stale file information
						clearstatcache();
					
						@rename( $temp_zip, $zip );
					
						if ( @file_exists( $zip ) ) {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
							$this->log( 'message', __( 'Zip process reported: Zip Archive file successfully created with no errors or actionable warnings.','it-l10n-backupbuddy' ) );
						
							$this->log_archive_file_stats( $zip, array( 'content_size' => $total_size ) );
							
							// Temporary for now - try and incorporate into stats logging (makes the stats logging function part of the zip helper class?)
							$this->log( 'details', sprintf( __('Zip process reported: Zip Archive file size: %1$s of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ), $total_count ) );
					
							// Work out percentage on items
							if ( 0 < $total_count ) {
					
								$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ) / $total_count ) * 100 );
								$this->log( 'details', sprintf( __('Zip process reported: Zip archive file size: %1$s%% of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
							}
							
							$result = true;
						
						} else {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
							$result = false;
						
						}
						
					} else {
				
						// With multi-burst we haev to always build the zip in temp location  so always have to move it
						$this->log( 'details', __('Zip process reported: Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
				
						// Make sure no stale file information
						clearstatcache();
					
						@rename( $temp_zip, $zip );
						
						if ( @file_exists( $zip ) ) {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
							$this->log( 'message', __( 'Zip process reported: Zip Archive file successfully created with no errors (any actionable warnings ignored by user settings).','it-l10n-backupbuddy' ) );
						
							$this->log_archive_file_stats( $zip, array( 'content_size' => $total_size ) );
							
							// Temporary for now - try and incorporate into stats logging (makes the stats logging function part of the zip helper class?)
							$this->log( 'details', sprintf( __('Zip process reported: Zip Archive file size: %1$s of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ), $total_count ) );
					
							// Work out percentage on items
							if ( 0 < $total_count ) {
					
								$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ) / $total_count ) * 100 );
								$this->log( 'details', sprintf( __('Zip process reported: Zip archive file size: %1$s%% of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
							}
							
							$result = true;
						
						} else {
					
							$this->log( 'details', __('Zip process reported: Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
							$result = false;
						
						}
				
					}
				
				}
				
			}		

			// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file			
			$this->log( 'details', __('Zip process reported: Removing temporary directory.','it-l10n-backupbuddy' ) );
			
			if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
			
					$this->log( 'details', __('Zip process reported: Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
			
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
				
					$this->log( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	
	
				// We'll try and extract from the backup file to the given directory, very quietly with overwrite
				// If we just did -o we could try and get file count from processing $output but it would be a bit time-consuming
				$unzip_command = $command . " -qqo '{$zip_file}' -d '{$destination_directory}' -x 'importbuddy.php'";
				
				$unzip_command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
				$unzip_command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $unzip_command ) : $unzip_command;
				
				@exec( $unzip_command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to do
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and apparently no extraction problems
						
						// Now we have to do a second run to find out the file count (crazy)
						$list_command = $command . " -ql '{$zip_file}'";
						
						$list_command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
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
						
						$this->log( 'details', sprintf( __('exec (unzip) extracted file contents (%1$s to %2$s)','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );
	
						$this->log_archive_file_stats( $zip_file );
						
						$result = true;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						$this->log( 'details', sprintf( __('exec (unzip) failed to open/process file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning an array as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				$this->log( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

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
				
					$this->log( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
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
				
					$unzip_command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
					$unzip_command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $unzip_command ) : $unzip_command;
				
					@exec( $unzip_command, $output, $exit_code);
				
					// Note: we don't open the file and then do stuff but it's all done in one action
					// so we need to interpret the return code to dedide what to do
					switch ( (int) $exit_code ) {
						case 0:
							// Handled archive and apparently no extraction problems						
							$this->log( 'details', sprintf( __('exec (unzip) extracted file contents (%1$s from %2$s to %3$s%4$s)','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where ) );
						
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
							$this->log( 'details', sprintf( __('exec (unzip) failed to open/process file to extract contents (%1$s from %2$s to %3$s%4$s) - Error Info: %5$s.','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where, $error_string ) );

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
				$this->log( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

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
				
					$this->log( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	
	
				// Try an archive test on the file and we'll just look at the return code
				$command .= " -qt '{$zip_file}' '{$locate_file}'";
				
				$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
				$command = ( self::OS_TYPE_WIN === $this->get_os_type() ) ? str_replace( '\'', '"', $command ) : $command;

				@exec( $command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to indicate
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and file found and checked out ok so return success
						$this->log( 'details', __('File found (exec)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						$result = true;
						break;
					case 11:
						// No problem handling archive but file simply not found so return failure
						$this->log( 'details', __('File not found (exec)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						$result = false;
						break;
					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						$this->log( 'details', sprintf( __('exec (unzip) failed to open/process file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						$result = array( 1, "Failed to open/process file" );

				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				$this->log( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

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
				
					$this->log( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	

				// We'll try and get a *nix style directory listing output and process it
				// Note: we'll ignore stderr output for now as it might interfere
				// Note: the file date given is the stored local time (not UTC which may be stored as well)
				
				// Output format should be like:
				// -rwxr-xr-x  2.3 unx     2729 tx    1099 defN 20120220.231956 file/path/name.ext
				$command .= " -Z --h --t -lT '{$zip_file}'";
				
				$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
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
						
						$this->log( 'details', sprintf( __('exec (unzip) listed file contents (%1$s)','it-l10n-backupbuddy' ), $zip_file ) );
	
						$this->log_archive_file_stats( $zip_file );
						
						$result = &$file_list;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						$this->log( 'details', sprintf( __('exec (unzip) failed to open/process file to list contents (%1$s) - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning an array as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				$this->log( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

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
				
					$this->log( 'details', __( 'Using absolute zip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'zip';	

				// We have to feed the comment in - trying by pipe here
				// We need to prepend the comment input
				$command .= " -z '{$zip_file}'";
				
				$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
				// Note that we escape the comment arg for the shell...
				$command  = 'echo ' . escapeshellarg( $comment ) . ' | ' . $command;
				
				@exec( $command, $output, $exit_code);
				
				// Note: we don't open the file and then do stuff but it's all done in one action
				// so we need to interpret the return code to dedide what to do
				switch ( (int) $exit_code ) {
					case 0:
						// Handled archive and apparently set the comment - no further action required
																		
						$this->log( 'details', sprintf( __('exec (zip) set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
							
						$result = true;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						$this->log( 'details', sprintf( __('exec (zip) failed to open/process file to set comment in file %1$s - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning a string as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				$this->log( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

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
				
					$this->log( 'details', __( 'Using absolute unzip path: ','it-l10n-backupbuddy' ) . $zippath );
					
				}
				
				// Add the trailing slash if required
				$command = $this->slashify( $zippath ) . 'unzip';	

				// We expect two lines of output - the first shpuld be the archive name and the second comment if present
				$command .= " -z '{$zip_file}'";
				
				$command .= ( ( $this->get_exec_dir_flag() ) ? "" : " 2>" . $this->get_null_device() );
					
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
						$this->log( 'details', sprintf( __('exec (unzip) retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
							
						$result = $comment;
						break;

					default:
						// For now let's just print the error code and drop through
						$error_string = $exit_code;
						$this->log( 'details', sprintf( __('exec (unzip) failed to open/process get comment in file %1$s - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

						// Return an error code and a description - this needs to be handled more generically
						//$result = array( 1, "Unable to get archive contents" );
						// Currently as we are returning a string as a valid result we just return false on failure
						$result = false;
				}
				
			} else {
			
				// Something fishy - the methods indicated exec but we couldn't find the function
				$this->log( 'details', __('exec indicated as available method but exec function non-existent','it-l10n-backupbuddy' ) );

				// Return an error code and a description - this needs to be handled more generically
				//$result = array( 1, "Class not available to match method" );
				// Currently as we are returning a string as a valid result we just return false on failure
				$result = false;

			}
			
			return $result;
			
		}
		
	} // end pluginbuddy_zbzipexec class.	
	
}
