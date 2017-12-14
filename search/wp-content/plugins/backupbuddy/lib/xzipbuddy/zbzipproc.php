<?php
/**
 *	pluginbuddy_zbzipproc Class
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
if ( !class_exists( "pluginbuddy_zbzipproc" ) ) {

	class pluginbuddy_zbzipproc extends pluginbuddy_zbzipcore {
	
		// Constants for file handling
		const ZIP_LOG_FILE_NAME        = 'temp_zip_proc_log.txt';
		const ZIP_ERRORS_FILE_NAME     = 'last_proc_errors.txt';
		const ZIP_WARNINGS_FILE_NAME   = 'last_proc_warnings.txt';
		const ZIP_OTHERS_FILE_NAME     = 'last_proc_others.txt';
		const ZIP_EXCLUSIONS_FILE_NAME = 'exclusions.txt';
		const ZIP_INCLUSIONS_FILE_NAME = 'inclusions.txt';
	
		// Possible executable path sets
		const DEFAULT_EXECUTABLE_PATHS = '/usr/local/bin::/usr/bin:/usr/local/sbin:/usr/sbin:/sbin:/bin';
		const WINDOWS_EXECUTABLE_PATHS = '';
		
        /**
         * method tag used to refer to the method and entities associated with it such as class name
         * 
         * @var string
         */
		public static $_method_tag = 'proc';
		
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
											  		 'functions' => array( 'proc_open', 'proc_close', 'proc_get_status', 'proc_terminate' ),
											  		 'extensions' => array(),
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
			
			// Need to additionally check the OS - need *nix type at least
				
			// Use UC for ease - this _should not? cause any ambiguity
			$os_name = strtoupper( PHP_OS );
	 
			// Currently we'll assume anything that doesn't look like Windows is *nix based
			if ( !( substr( $os_name, 0, 3 ) === 'WIN') ) {
			
				// We're ok as meet dependency that this isn't Windows based OS
				$result = true;
			
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
															array( 'name' => 'Proc Method',
													  			   'compatibility' => self::$_is_compatibility_method )
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
		
			return self::$_method_tag;
			
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
		
			return self::$_is_compatibility_method;
			
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
		 *	Note: pre-v3 zip running "zip -v" will not produce the required output because
		 *	there is no tty attached (when running through exec() or equivalent), instead
		 *	it will produce a zip file.Currently we'll just detect that and set the version
		 *	as 2.0 and not set the info.
		 *	TODO: We could parse the zip file to get the version but also considering doing
		 *	that in the is_available() test where we already created a zip file.
		 *	TODO: Consider testing if method can zip and only then run the test
		 *	TODO: This needs refactoring to make it cleaner
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
			$command = $this->slashify( $zippath ) . 'zip -v';
			
			//@exec( $command, $output, $exitcode );
			
			// Get a temporary file to use for command output
			$outfile_name = tempnam( sys_get_temp_dir(), uniqid( 'pb_' ) );
			
			// Make sure we only do this if we have a file we can write to
			if ( is_string( $outfile_name ) && is_writable( $outfile_name ) ) { 
			
				$descriptorspec = array(
					0 => array( "pipe", "r" ),
					1 => array( "file", $outfile_name, "w" ),
					2 => array( "file", "/dev/null", "a" )	
				);
				
				$process = NULL; // Maybe it doesn't work
				
				$process = @proc_open( $command, $descriptorspec, $pipes );
				
				if ( is_resource( $process ) ) {
				
					fclose( $pipes[0] ); // Never want to send input so just close it
					
					$pstatus = proc_get_status( $process );
					
					// Make sure we only do 4 loop max
					$count = 0;
					
					while ( true == $pstatus[ 'running'] && ( $count++ < 4 ) ) {
						usleep( 500000 );
						$pstatus = proc_get_status( $process );
					}
					
					if ( true === $pstatus[ 'running' ] ) {
						// Hmm, shouldn't still be running, try to kill it and move on
						@proc_terminate( $process );
						
						// Get status again and see if now not running, if so get exit code
						
					} else {
					
						// Process finished normally so get exit code for possible use
						$exitcode = $pstatus[ 'exitcode' ];
						
					}
					
					// Ignore any close issue, shouldn't get stuck here but it is possible if
					// we process was still running and we failed to terminate it - tricky one
					// to overcome but _should_ be rare
					@proc_close( $process );
					
				}
				
			}
			
			// Note: if we couldn't create a process just fall through silently
			
			if ( 0 === $exitcode ) {
			
				// Put the file content into an array
				try {
				
					$outfile = new SplFileObject( $outfile_name, "rb" );
					
					while( !$outfile->eof() ) {
					
						// Need to trim line endings because of imploding with PHP_EOL later
						$output[] = rtrim( $outfile->current() );
						$outfile->next();
					
					}
					
					unset( $outfile );
					
				} catch ( Exception $e ) {
				
					// Maybe the file didn't exist for some reason?
					// In any case just fall through silently
					
				}
			
				// Should be good output to try at least
				// If this has a zip file signature then it must be pre-v3 zip
				$z_data = unpack( 'Vsig', $output[0] );
			
				if ( 0x04034b50 == $z_data[ 'sig' ] ) {
				
					// TODO: Consider that we could use unzip -Z -v on this file and parse the
					// output for the Central Dir info on what version of zip created the file.
					// Currently, where this function is called, we don't know if we have unzip
					// so we can't assume - with a rejig we could call this later and use unzip
					// if available.
					// Can't tell which 2.X version, cannot populate $info
					$major = 2;
					$minor = 0;
					
				} else {
				
					// Doesn't appear to be a zip file so should be version info
					// Expect format like: This is Zip 3.0 (July 5th 2008)...
					//                     This is Zip 3.1c BETA (June 22nd 2010)...
					// The match should take only the major/minor digits and ignore any following alpha
					// May extend to capture the alpha and also whether BETA indicated but not currently
					// required.
					foreach ( $output as $line ) {
	
						if ( preg_match( '/^\s*(this)\s+(is)\s+(zip)\s+(?P<major>\d)\.(?P<minor>\d+)/i', $line, $matches ) ) {
						
							$major = (int)$matches[ 'major' ];
							$minor = (int)$matches[ 'minor' ];
							break;
						
						}
					
					}
					
					// If we didn't match a version then suspect this is still not valid version info
					if ( !empty( $matches ) ) {
					
						// Now create the info string
						// Note: not worth compressing as that gives a larger string after converting
						// from binary to hex format for saving
						$info = implode( PHP_EOL, $output );
						$this->_method_details[ 'param' ][ 'zip' ][ 'info' ] = $info;
					
					}
				
				}
				
			}
			
			// Now use either what we got or what we were given...
			if ( ( is_int( $major) ) && ( 0 < $major ) && ( is_int( $minor ) ) ) {
			
				// Set the given version regardless
				$this->_method_details[ 'param' ][ 'zip' ][ 'version' ] = array( 'major' => $major, 'minor' => $minor );
			
			}
			
			// Catch-all cleanup of the output file
			if ( @file_exists( $outfile_name ) ) {
			
				@unlink( $outfile_name );
				
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
		 *	This sets the zip version information in the method details
		 *	TODO: This needs refactoring to make it cleaner
		 *	
		 *	@param		int		$major		Value to use if none found or override true
		 *	@param		int		$minor		Value to use if none found or override true
		 *	@param		bool	$override	True to use passed in value(s) regardless
		 *	@return		object				This object reference
		 *
		 */
		protected function set_unzip_version( $major = 0, $minor = 0, $override = false ) {
		
			$exitcode = 127;
			$output = array();
			$zippath = '';
			$command = '';
			$matches = array();
			$info = '';
		
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
			
			//@exec( $command, $output, $exitcode );
			
			// Get a temporary file to use for command output
			$outfile_name = tempnam( sys_get_temp_dir(), uniqid( 'pb_' ) );
			
			// Make sure we only do this if we have a file we can write to
			if ( is_string( $outfile_name ) && is_writable( $outfile_name ) ) { 
			
				$descriptorspec = array(
					0 => array( "pipe", "r" ),
					1 => array( "file", $outfile_name, "w" ),
					2 => array( "file", "/dev/null", "a" )	
				);
				
				$process = NULL; // Maybe it doesn't work
				
				$process = @proc_open( $command, $descriptorspec, $pipes );
				
				if ( is_resource( $process ) ) {
				
					fclose( $pipes[0] ); // Never want to send input so just close it
					
					$pstatus = proc_get_status( $process );
					
					// Make sure we only do 4 loop max
					$count = 0;
					
					while ( true == $pstatus[ 'running'] && ( $count++ < 4 ) ) {
						usleep( 500000 );
						$pstatus = proc_get_status( $process );
					}
					
					if ( true === $pstatus[ 'running' ] ) {
						// Hmm, shouldn't still be running, try to kill it and move on
						@proc_terminate( $process );
						
						// Get status again and see if now not running, if so get exit code
						
					} else {
					
						// Process finished normally so get exit code for possible use
						$exitcode = $pstatus[ 'exitcode' ];
						
					}
					
					// Ignore any close issue, shouldn't get stuck here but it is possible if
					// we process was still running and we failed to terminate it - tricky one
					// to overcome but _should_ be rare
					@proc_close( $process );
					
				}
				
			}
			
			// Note: if we couldn't create a process just fall through silently
			
			if ( 0 === $exitcode ) {
			
				// Put the file content into an array
				try {
				
					$outfile = new SplFileObject( $outfile_name, "rb" );
					
					while( !$outfile->eof() ) {
					
						// Need to trim line endings because of imploding with PHP_EOL later
						$output[] = rtrim( $outfile->current() );
						$outfile->next();
					
					}
					
					unset( $outfile );
					
				} catch ( Exception $e ) {
				
					// Maybe the file didn't exist for some reason?
					// In any case just fall through silently
					
				}
			
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
			
			// Catch-all cleanup of the output file
			if ( @file_exists( $outfile_name ) ) {
			
				@unlink( $outfile_name );
				
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
			
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					$result = $this->is_available_linux( $tempdir );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->is_available_windows( $tempdir );
					break;
				default:
					$result = false;
			}
			
			return $result;
					  	
		}

		/**
		 *	is_available_windows()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		protected function is_available_windows( $tempdir ) {
		
			// Just return false silently otherwise we'd be logging repeatedly...
			//pb_backupbuddy::status( 'details', $this->_method_tag . __(' method not supported on Windows.','it-l10n-backupbuddy' ) );
			return false;
			
		}
		
		/**
		 *	is_available_linux()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		protected function is_available_linux( $tempdir ) {
		
			$result = false;
			$pending_result = false;
			$found_zip = false;
			$pstatus = array();
			
			// This is a safety value in case something odd happens
			$exec_exit_code = 127;
			
			if ( function_exists( 'proc_open' ) && function_exists( 'proc_close' ) &&
				 function_exists( 'proc_get_status' ) && function_exists( 'proc_terminate' ) ) {
				 
				$candidate_paths = $this->_executable_paths;
				
				// We are searching for zip using the list of possible paths
				while ( ( false == $found_zip ) && ( !empty( $candidate_paths ) ) ) {
				
					// Make sure it is clean of leading/trailing whitespace
					$path = trim( array_shift( $candidate_paths ) );
					
					pb_backupbuddy::status( 'details', __( 'Trying executable path for zip proc:','it-l10n-backupbuddy' ) . ' `' . $path . '`.' );

					$test_file = $tempdir . 'temp_test_' . uniqid() . '.zip';
					
					$command = 'exec ' . $this->slashify( $path ) . 'zip "' . $test_file . '" "' . __FILE__ . '"';
	
					$descriptorspec = array(
						0 => array( "pipe", "r" ),
						1 => array( "file", "/dev/null", "a" ),
						2 => array( "file", "/dev/null", "a" )	
					);
					
					$process = NULL; // Maybe it doesn't work
					
					$process = @proc_open( $command, $descriptorspec, $pipes );
					
					if ( is_resource( $process ) ) {
					
						fclose( $pipes[0] ); // Never want to send input so just close it
						
						$pstatus = proc_get_status( $process );
						
						// Make sure we only do 4 loop max
						$count = 0;
						
						while ( true == $pstatus[ 'running'] && ( $count++ < 4 ) ) {
							usleep( 500000 );
							$pstatus = proc_get_status( $process );
						}
						
						if ( true === $pstatus[ 'running' ] ) {
							// Hmm, shouldn't still be running, try to kill it and move on
							@proc_terminate( $process );
							
							// Get status again and see if now not running, if so get exit code
							
						} else {
						
							// Process finished normally so get exit code for possible use
							$exec_exit_code = $pstatus[ 'exitcode' ];
							
						}
						
						// Ignore any close issue, shouldn't get stuck here but it is possible if
						// we process was still running and we failed to terminate it - tricky one
						// to overcome but _should_ be rare
						@proc_close( $process );
						
						// Must have both a file and a success exit code to consider this successful
						if ( @file_exists( $test_file ) && ( 0 === $exec_exit_code ) ) {
				
							// Set the parameter to be remembered (note: path without trailing slash)
							$this->_method_details[ 'param' ][ 'zip' ][ 'path' ] = $path;
							
							// The zip operation was successful so we can at least zip and archive
							$this->_method_details[ 'attr' ][ 'is_zipper' ] = true;
							$this->_method_details[ 'attr' ][ 'is_archiver' ] = true;
							
							pb_backupbuddy::status( 'details', __('Proc test (zip) PASSED.','it-l10n-backupbuddy' ) );
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
							
								pb_backupbuddy::status( 'details', __('Proc test (zip) FAILED: Test zip file not found.','it-l10n-backupbuddy' ) );
							
							}
							
							if ( 0 === $exec_exit_code ) {
							
								$error_string = $exec_exit_code;
								pb_backupbuddy::status( 'details', __('Proc test (zip) FAILED: proc Exit Code: ','it-l10n-backupbuddy' ) . $error_string );
								
							}
							
							$result = false;
					
						}
						
						// Remove the test zip file if it was created
						if ( @file_exists( $test_file ) ) {
						
							if ( !@unlink( $test_file ) ) {
					
								pb_backupbuddy::status( 'details', sprintf( __('Proc test (zip) unable to delete test file (%s)','it-l10n-backupbuddy' ), $test_file ) );
						
							}
					
						}
						
					} else {
					
						pb_backupbuddy::status( 'details', __('Proc test FAILED: Unable to create test zip file process.','it-l10n-backupbuddy' ) );
						$result = false;
					
					}
				
				}
				
				if ( false == $found_zip ) {
				
					// Never found zip on any candidate path
					pb_backupbuddy::status( 'details', __('Proc test Failed: Unable to find zip executable on any specified path.','it-l10n-backupbuddy' ) );
					$result = false;
										
				}
					  
				// Remember zip result and reset for unzip test
				$pending_result = $result;
				$result = false;
				
				// See if we can determine zip version and possibly available options. This can help us
				// determine how to execute operations such as creating a zip file
				if ( true === $found_zip ) {
				
					$this->set_zip_version();
				
				}
				
				// Reset the candidate paths for a full search for unzip
				$candidate_paths = $this->_executable_paths;
						  
				// Reset the safety value in case
				$exec_exit_code = 127;
				
				// New search
				$found_zip = false;
				
				// Need to create a test zip file - here's one I prepared earlier
				// containing the one file test.txt (with content "Hello World")
				// Can use this for all case tests and delete at end
				$test_file = $tempdir . 'temp_test_' . uniqid() . '.zip';
				@file_put_contents( $test_file, base64_decode( "UEsDBAoAAAAAAC8ELUHj5ZWwDAAAAAwAAAAIABwAdGVzdC50eHRVVAkAA8obUVDjG1FQdXgLAAEE+AEAAAQUAAAASGVsbG8gV29ybGQKUEsBAh4DCgAAAAAALwQtQePllbAMAAAADAAAAAgAGAAAAAAAAQAAAKSBAAAAAHRlc3QudHh0VVQFAAPKG1FQdXgLAAEE+AEAAAQUAAAAUEsFBgAAAAABAAEATgAAAE4AAAAAAA==" ) );

				// We are searching for zip using the list of possible paths
				while ( ( false == $found_zip ) && ( !empty( $candidate_paths ) ) ) {
				
					// Make sure it is clean of leading/trailing whitespace
					$path = trim( array_shift( $candidate_paths ) );

					pb_backupbuddy::status( 'details', __( 'Trying executable path for unzip:','it-l10n-backupbuddy' ) . ' `' . $path . '`.' );
					
					$command = 'exec ' . $this->slashify( $path ) . 'unzip -qt ' . ' "' . $test_file . '" "test.txt"';
	
					$descriptorspec = array(
						0 => array( "pipe", "r" ),
						1 => array( "file", "/dev/null", "a" ),
						2 => array( "file", "/dev/null", "a" )	
					);
					
					$process = NULL; // Maybe it doesn't work
					
					$process = @proc_open( $command, $descriptorspec, $pipes );
					
					if ( is_resource( $process ) ) {
					
						fclose( $pipes[0] ); // Never want to send input so just close it
						
						$pstatus = proc_get_status( $process );
						
						// Make sure we only do 4 loop max
						$count = 0;
						
						while ( true == $pstatus[ 'running'] && ( $count++ < 4 ) ) {
							usleep( 500000 );
							$pstatus = proc_get_status( $process );
						}
						
						if ( true === $pstatus[ 'running' ] ) {
							// Hmm, shouldn't still be running, try to kill it and move on
							@proc_terminate( $process );
							
							// Get status again and see if now not running, if so get exit code
							
						} else {
						
							// Process finished normally so get exit code for possible use
							$exec_exit_code = $pstatus[ 'exitcode' ];
							
						}
						
						// Ignore any close issue, shouldn't get stuck here but it is possible if
						// we process was still running and we failed to terminate it - tricky one
						// to overcome but _should_ be rare
						@proc_close( $process );
						
						if ( $exec_exit_code == 0 ) {
						
							// Set the parameter to be remembered (note: path without trailing slash)
							$this->_method_details[ 'param' ][ 'unzip' ][ 'path' ] = $path;
							
							// The unzip operation was successful so we can at least unzip
							// Note: we do not want to use this for checking yet	
							$this->_method_details[ 'attr' ][ 'is_unzipper' ] = true;
						
							pb_backupbuddy::status( 'details', __('Proc test (unzip) PASSED.','it-l10n-backupbuddy' ) );
							$result = true;
							
							// This will break us out of the loop
							$found_zip = true;
							
						} else {
						
							$error_string = $exec_exit_code;
							pb_backupbuddy::status( 'details', __('Proc test (unzip) FAILED: Test unzip file test failed.','it-l10n-backupbuddy' ) );
							pb_backupbuddy::status( 'details', __('Proc Exit Code: ','it-l10n-backupbuddy' ) . $error_string );
							$result = false;
						
						}
						
					} else {
					
						pb_backupbuddy::status( 'details', __('Proc test FAILED: Unable to create test unzip file process.','it-l10n-backupbuddy' ) );
						$result = false;
					
					}
				
				}
				
				// Remove the test zip file if it was created
				if ( @file_exists( $test_file ) ) {
				
					if ( !@unlink( $test_file ) ) {
			
						pb_backupbuddy::status( 'details', sprintf( __('Proc test (unzip) unable to delete test file (%s)','it-l10n-backupbuddy' ), $test_file ) );
				
					}
			
				}
			
				// If we didn't find unzip anywhere (or maybe found it but it failed) then log it
				if ( false == $found_zip ) {
				
					pb_backupbuddy::status( 'details', __('Proc test Failed: Unable to find unzip executable on any specified path.','it-l10n-backupbuddy' ) );
					$result = false;
										
				} else {
				
					// See if we can determine unzip version and possibly available options. This can help us
					// determine how to execute operations such as unzipping a file
					
					$this->set_unzip_version();
				
				}
				
			} else {
			
				pb_backupbuddy::status( 'details', __('Proc test FAILED: One or more required function do not exist.','it-l10n-backupbuddy' ) );
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
					$result = $this->create_linux( $zip, $dir, $excludes, $tempdir, $listmaker );
					break;
				case self::OS_TYPE_WIN:
					$result = $this->create_windows( $zip, $dir, $excludes, $tempdir, $listmaker );
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}
			
		/**
		 *	create_windows()
		 *	
		 *	A function that creates an archive file on Linux
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
		protected function create_windows( $zip, $dir, $excludes, $tempdir, $listmaker ) {
		
			// This should never be called but just in case return false silently
			//pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for archiving.','it-l10n-backupbuddy' ) );
			return false;

		}			
		
		/**
		 *	create_linux()
		 *	
		 *	A function that creates an archive file on Linux
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
		protected function create_linux( $zip, $dir, $excludes, $tempdir, $listmaker ) {
		
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

			// Hardcoding some additional options for now
			//$command .= ' -q -r';
			// Note: no longer using quiet _but_ may use it if output is being sent to log file
			$command .= ' -r';
			
			// Check if the version of zip in use supports log file (which will help with memory usage for large sites)
			if ( $this->get_zip_supports_log_file() ) {
			
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
			
				// Want to not follow symlinks so set command option and set flag for later use
				if ( $this->get_os_type() != self::OS_TYPE_WIN ) {
					$command .= ' -y';
					$zip_ignoring_symlinks = true;
				}
				
				pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links will not be followed based on settings.','it-l10n-backupbuddy' ) );

			} else {
			
				pb_backupbuddy::status( 'details', __('Zip archive creation symbolic links will be followed based on settings.','it-l10n-backupbuddy' ) );

			}
			
			// Check if we are ignoring warnings - meaning can still get a backup even
			// if, e.g., some files cannot be read
			if ( true === $this->get_ignore_warnings() ) {
			
				// Note: warnings are being ignored but will still be gathered and logged
				pb_backupbuddy::status( 'details', __('Zip archive creation warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
				
			} else {
			
				pb_backupbuddy::status( 'details', __('Zip archive creation warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

			}
				
			// Delete any existing zip file of same name - really this should never happen
			if ( @file_exists( $zip ) ) {

				pb_backupbuddy::status( 'details', __('Existing ZIP Archive file will be replaced.','it-l10n-backupbuddy' ) );
				@unlink( $zip );

			}
						
			// Now we'll set up the logging to file if required - use full logging
			// Note: we always use this file either for command line log option or captured output
			// so always set it
			$logfile_name = $tempdir . self::ZIP_LOG_FILE_NAME;
			if ( true === $zip_using_log_file ) {
			
				// Using the command line logfile option
				$command .= " -lf '{$logfile_name}' -li";
				
				// Actual command output will be sent to the bit-bucket in the sky
				// TODO: if we want to capture debug output we'll need a real file
				// is it safe to use the same filename (probably not)
				$output = '/dev/null';
			
			} else {
			
				// Old stylee zip not supporting logfile so redirect output to the file instead
				$output = $logfile_name;
			
			}
						
			// Set temporary directory to store ZIP while it's being generated.			
			$command .= " -b '{$tempdir}'";

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
			
			// Remember the current directory and change to the directory being added so that "." is valid in command
			$working_dir = getcwd();
			chdir( $dir );
			
			// Execute ZIP command - we don't care a about Windows here because this method isn't supported yet
			// Prepend "exec" so that spawned process becomes the actual zip process
			$command = 'exec ' . $command;
			pb_backupbuddy::status( 'details', $this->get_method_tag() . __(' command (Linux)','it-l10n-backupbuddy' ) . ': ' . $command );
			
			// Set stdin to be a pipe that we'll close immediately anyway
			// Send stdout to a file or dump it dependent on the zip version (the destination is set above)
			// Send stderr to wherever stdout is going (on pre-v3 zip we need stderr for warnings)
			$descriptorspec = array(
				0 => array( "pipe", "r" ),
				1 => array( "file", $output, "w" ),
				2 => array( "file", $output, "w" )
			);
			
			$process = proc_open( $command, $descriptorspec, $pipes );
			
			if ( is_resource( $process ) ) {
			
				fclose( $pipes[ 0 ] ); // Never want to send input so just close it
				
				$status = proc_get_status( $process );
				
				while ( true == $status[ 'running'] ) {
					pb_backupbuddy::status( 'details', __('Zip Archive file creation in progress.','it-l10n-backupbuddy' ) );
					sleep(5);
					$status = proc_get_status( $process );
					
					// Could also check for persistent final zip file (i.e., present over two loops)
					// which might mean we are somehow not seeing the process as finished so we should
					// terminate it and close it - and indicate some failure
				}
				
				$exitcode = $status[ 'exitcode' ];
				
				proc_close( $process );
				
			} else {
			
				// Must clean up temporary directory
			
				pb_backupbuddy::status( 'details', __('Failed to create Zip process: ','it-l10n-backupbuddy' ) );
				pb_backupbuddy::status( 'details', __('Removing temporary directory.','it-l10n-backupbuddy' ) );
				
				if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
				
						pb_backupbuddy::status( 'details', __('Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
				
				}
				
				chdir( $working_dir );
				return false;
				
			}
			
			// Set current working directory back to where we were
			chdir( $working_dir );
			
			// Convenience for handling different scanarios
			$result = false;
			
			// Log file is always present - either capturing output or as command line option
			// Always scan the logfile for warnings, etc. and show warnings even if user has chosen to ignore them
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
				if ( ( ! @file_exists( $temp_zip ) ) ||
				 ( ( 0 != $exitcode ) && ( 18 != $exitcode ) ) ||
				 ( ( 18 == $exitcode ) && !$this->get_ignore_warnings() ) ) {
				
				// If we have any zip errors reported show them regardless
				if ( true === $have_zip_errors ) {
				
					pb_backupbuddy::status( 'details', sprintf( __('Zip process reported: %1$s errors','it-l10n-backupbuddy' ), $zip_errors_count ) );
					
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
		 *	extract()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@return	bool									true on success, false otherwise
		 */
		protected function extract_generic_full( $zip_file, $destination_directory = '') {
		
			// This should never be called but just in case return false silently
			//pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for unarchiving.','it-l10n-backupbuddy' ) );
			return false;
			
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
		
			// Should never get here
			return false;
			
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
		
			// This should never be called but just in case return false silently
			//pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for archive file checking.','it-l10n-backupbuddy' ) );
			return array( 1, "Method does not support action" );

		}
		
		/*	get_file_list()
		 *	
		 *	Get an array of all files in a zip file with some file properties.
		 *	
		 *	@param		string		$zip_file	The file to list the content of
		 *	@return		bool|array				false on failure, otherwise array of file properties (may be empty)
		 */
		public function get_file_list( $zip_file ) {
		
			// This should never be called but just in case return false silently
			//pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for archive file listing.','it-l10n-backupbuddy' ) );
			return false;

		}
		
		/*	set_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		bool|string						true on success, error message otherwise.
		 */
		public function set_comment( $zip_file, $comment ) {
		
			// This should never be called but just in case return false silently
			//pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for archive comment setting.','it-l10n-backupbuddy' ) );
			return "Method does not support action";

		}

		/*	get_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		bool|string					false on failure, Zip comment otherwise.
		 */
		public function get_comment( $zip_file ) {
		
			// This should never be called but just in case return false silently
			//pb_backupbuddy::status( 'details', __('The ','it-l10n-backupbuddy' ) . $this->get_method_tag() . __(' method is not currently supported for archive comment retrieval.','it-l10n-backupbuddy' ) );
			return false;

		}
		
	} // end pluginbuddy_zbzipproc class.	
	
}
?>
