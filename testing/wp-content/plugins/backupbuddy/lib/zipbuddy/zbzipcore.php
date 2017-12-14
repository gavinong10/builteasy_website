<?php
/**
 *	pluginbuddy_zbzipcore Class
 *
 *  Provides an abstract zip capability core class
 *	
 *	Version: 1.0.0
 *	Author:
 *	Author URI:
 *
 *	@param		$parent		object		Optional parent object which can provide functions for reporting, etc.
 *	@return		null
 *
 */
if ( !class_exists( "pluginbuddy_zbzipcore" ) ) {

	/**
	 *	pluginbuddy_stat Class
	 *
	 *	Convenience for being able to augment the stat() function either in the event
	 *	of failure or for cases where the actual file size reported is too large for the
	 *	(signed) integer type in which case we create an additional associative field in
	 *	the array which is a double and contains the file size.
	 *	For now it's just some static methods but might extend to be a true class.
	 *
	 *	@param	string		$filename	The name of the file to stat
	 *	@return	array|bool				False on failure otherwise array
	 *
	 */
	class pluginbuddy_stat {

		const THIRTY_TWO_BIT = 32;
		const SIXTY_FOUR_BIT = 64;
		
		public static function is_php( $bits ) {
		
			$result = ( ( PHP_INT_SIZE * 8 ) == $bits ) ? true : false;
			
			return $result;
		
		}
	
		public static function stat( $filename ) {
		
			$result = false;

			// If the file is readable then we should be able to stat it 
			if ( @is_readable( $filename ) ) {
			
				$stats = @stat( $filename );
				
				if ( false !== $stats ) {
				
					// Looks like we got some valid data - for now just process the size
					if ( self::is_php( self::THIRTY_TWO_BIT ) ) {
					
						// PHP is 32 bits so we may have a file size problem over 2GB.
						// This is one way to test for a file size problem - there are others
						if ( 0 > $stats[ 'size' ] ) {
						
							// Unsigned long has been interpreted as a signed int and has sign bit
							// set so is appearing as negative - magically convert it to a double
							// Note: this only works to give us an extension from 2GB to 4GB but that
							// should be enough as the underlying OS probably can't support >4GB or
							// zip command cannot anyway
							$stats[ 'dsize' ] = ( (double)0x80000000 + ( $stats[ 'size' ] & 0x7FFFFFFF ) );
						
						} else {
						
							// Assume it's valid
							$stats[ 'dsize' ] = (double)$stats[ 'size' ];
						
						}
												
					} else {
					
						// Looks like 64 bit PHP so file size should be fine
						// Force added item to double for consistency
						$stats[ 'dsize' ] = (double)$stats[ 'size' ];
					
					}
					
					// Add an additional item for short octal representation of mode
					$stats[ 'mode_octal_four' ] = substr( sprintf( '%o', $stats[ 'mode' ] ), -4 );
					
					$result = $stats;
				
				} else {
				
					// Hmm, stat() failed for some reason - could be an LFS problem with the
					// way PHP has been built :-(
					// TODO: Consider alternatives - may be able to use exec to run the
					// command line stat function which _should_ be ok and we can map output
					// into the same array format. This does depend on having exec() and the
					// stat command available and it's definitely not a nice option
					$result = false;
				
				}
			
			}
			
			return $result;
		}
	
	}

	abstract class pluginbuddy_zbzipcore {
	
		// status method type parameter values - would like a class for this
		const STATUS_TYPE_DETAILS       = 'details';
		
		// Constants for handling paths
		const NORM_DIRECTORY_SEPARATOR  = '/';
		const DIRECTORY_SEPARATORS      = '/\\';

		// Constants for result handling
		const MAX_ERROR_LINES_TO_SHOW   = 20;
		const MAX_WARNING_LINES_TO_SHOW = 20;
		const MAX_OTHER_LINES_TO_SHOW   = 20;
		
		// Enumerated types that we need for now
		// Note: Values must be sequential
		const OS_TYPE_UNKNOWN 	=	0;
		const OS_TYPE_NIX		=	1;
		const OS_TYPE_WIN		=	2;
		const OS_TYPE_MAX		=	2;

		const ZIP_WARNING_UNKNOWN  			= 0;
		const ZIP_WARNING_GENERIC  			= 1;
		const ZIP_WARNING_SKIPPED  			= 2;
		const ZIP_WARNING_FILTERED 			= 3;
		const ZIP_WARNING_LONGPATH 			= 4;
		const ZIP_WARNING_IGNORED_SYMLINK 	= 5;
		
		const ZIP_OTHER_UNKNOWN         = 0;
		const ZIP_OTHER_GENERIC         = 1;
		const ZIP_OTHER_SKIPPED  		= 2;
		const ZIP_OTHER_FILTERED 		= 3;
		const ZIP_OTHER_LONGPATH 		= 4;
		const ZIP_OTHER_IGNORED_SYMLINK	= 5;
		
		const COMMAND_UNKNOWN_PATH	= 0;
		const COMMAND_ZIP_PATH		= 1;
		const COMMAND_UNZIP_PATH	= 2;

		public $_version = '1.0';


        /**
         * The plugin path for this plugin
         * 
         * @var $_pluginPath string
         */
        public $_pluginPath = '';

        /**
         * The path of this directory node
         * 
         * @var path string
         */
        protected $_path = "";
        
        /**
         * The absolute paths to be excluded, must be / terminated
         * 
         * @var paths_to_exclude array of string
         */
        protected $_paths_to_exclude = array();

        /**
         * The details of the method
         * 
         * @var method_details array
         */
		protected $_method_details = array();
		
        /**
         * The set of paths where to look for executables
         * 
         * @var  executable_paths	array
         */
		protected $_executable_paths = array();
		
        /**
         * Array of status information
         * 
         * @var status array
         */
		protected $_status = array();
		
        /**
         * Enumerated OS type
         * 
         * @var os_type	int
         */
		protected $_os_type = self::OS_TYPE_UNKNOWN;
		
        /**
         * Convenience boolean indicating if PHP has exec_dir set or not
         * 
         * @var exec_dir_set	bool
         */
		protected $_exec_dir_set = false;
		
        /**
         * Convenience boolean indicating if Warnings should be ignored when building archives
         * 
         * @var ignore_warnings	bool
         */
		protected $_ignore_warnings = false;
		
        /**
         * Convenience boolean indicating if symlinks should be ignored/not-followed when building archives
         * 
         * @var ignore_symlinks	bool
         */
		protected $_ignore_symlinks = false;
		
         /**
         * Convenience boolean indicating if compression shoul dbe used when building archives
         * 
         * @var compression	bool
         */
		protected $_compression = false;
		
       /**
         * Used to translate our warnings reasons into a longer description
         * 
         * @var array
         */
		public static $_warning_desc = array( self::ZIP_WARNING_UNKNOWN  			=> 'warning reason unknown',
											  self::ZIP_WARNING_GENERIC  			=> 'general problem as indicated',
											  self::ZIP_WARNING_SKIPPED  			=> 'file unreadable or does not exist',
											  self::ZIP_WARNING_FILTERED 			=> 'file filtered',
											  self::ZIP_WARNING_LONGPATH 			=> 'filename path too long',
											  self::ZIP_WARNING_IGNORED_SYMLINK  	=> 'file is a symlink and is ignored based on settings',
											 );

		public static $_other_desc   = array( self::ZIP_OTHER_UNKNOWN 			=> 'other reason unknown',
											  self::ZIP_OTHER_GENERIC 			=> 'other problem as indicated',
											  self::ZIP_OTHER_SKIPPED 			=> 'file unreadable or does not exist',
											  self::ZIP_OTHER_FILTERED			=> 'file filtered',
											  self::ZIP_OTHER_LONGPATH 			=> 'filename path too long',
											  self::ZIP_OTHER_IGNORED_SYMLINK	=> 'file is a symlink and is ignored based on settings',
											 );

        /**
         * The Server API that is in use
         * 
         * @var string
         */
		protected $_sapi_name = "";

		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	
		 *	@return		null
		 *
		 */
		public function __construct() {
		
			// Make sure we know what we are running on for later
			$this->set_os_type();
			
			// Derive whether we are ignoring Warnings or not (expected to be overridden by user)
			$this->set_ignore_warnings();
			
			// Derive whether we are ignoring/not-following symlinks or not (expected to be overridden by user)
			$this->set_ignore_symlinks();
			
			// Derive whether compression should be used (expected to be overridden by user)
			$this->set_compression();
			
			// Specific method constructor will override some of these and the tests may override others
			$this->_method_details[ 'attr' ] = array( 'name' => 'Unknown Method',
													  'compatibility' => false ,
													  'is_checker' => false,
													  'is_lister' => false,
													  'is_archiver' => false,
													  'is_unarchiver' => false,
													  'is_commenter' => false,
													  'is_zipper' => false,
													  'is_unzipper' => false,
													  'is_extractor' => false
													 );

			// Must _not_ default 'path' values because we test whether set or not to decide whether to use
			$this->_method_details[ 'param' ] = array( // 'path' => '',
													   'zip' => array( // 'path' => '',
													   		'version' => array( 'major' => 0, 'minor' => 0 ),
													   		'options' => '',
													   		'info' => '' ),
													   'unzip' => array( // 'path' => '',
													   		'version' => array( 'major' => 0, 'minor' => 0 ),
													   		'options' => '',
													   		'info' => '' )
													 );

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

		}
				
		/**
		 *	set_os_type()
		 *
		 *	Sets the identifier for the OS type that we are running on that can then be used for
		 *	OS specific processing. If no enumerated type value is passed in then deduce the
		 *	value to set from system information.
		 *	Note: Currently uses PHP_OS which strictly speaking is the OS that PHP was built on
		 *	whereas php_uname() could be used to determine the actual OS being run on if we really
		 *	need that (and sometimes it has to revert back to just returning the PHP_OS value if
		 *	the OS uname library doesn't exist or isn't working properly.
		 *
		 *	@param		$os_type	int		OS type to set (can be used to override deduced type)
		 *
		 */
		 public function set_os_type( $os_type = PHP_INT_MAX ) {
		 
		 	// Check if we have been given a valid enumerated value
		 	if ( ( self::OS_TYPE_UNKNOWN < $os_type ) && ( self::OS_TYPE_MAX >= $os_type ) ) {
		 	
		 		$this->_os_type = $os_type;
		 		
		 	} else {
		 		
		 		// Use UC for ease - this _should not? cause any ambiguity
		 		$os_name = strtoupper( PHP_OS );
		 
		 		// Currently we'll assume anything that doesn't look like Windows is *nix based
		 		if ( substr( $os_name, 0, 3 ) === 'WIN') {
		 		
		 			$this->_os_type = self::OS_TYPE_WIN;
		 			
		 		} else {
		 		
		 			$this->_os_type = self::OS_TYPE_NIX;
		 			
		 		}
		 	
		 	}
		 	
		 	return $this;
		 	
		 }

		/**
		 *	get_os_type()
		 *
		 *	Gets the enumerated identifier for the OS type that we are running on
		 *
		 *	@return		int		Enumerated OS type value
		 *
		 */
		 public function get_os_type( ) {
		 
			return $this->_os_type;

		 }

		/**
		 *	set_exec_dir_flag()
		 *
		 *	Checks whether exec_dir is set in PHP environment and sets internal flag
		 *
		 *	@return		bool		True is exec_dir is set and not-empty
		 *
		 */
		 public function set_exec_dir_flag( ) {
		 
		 	$exec_dir = '';
		 	$result = false;

		 	if ( ( false !== ( $exec_dir = ini_get( 'exec_dir' ) ) ) && ( '' != trim( $exec_dir ) ) ) {
		 	
		 		$result = true;
		 	
		 	} else {
		 	
		 		$result = false;
		 		
		 	}
		 
		 	$this->_exec_dir_set = $result;

			return $this;

		 }

		/**
		 *	get_exec_dir_flag()
		 *
		 *	Gets the flag indicating the status of exec_dir setting
		 *
		 *	@return		bool		Value of $_exec_dir_set
		 *
		 */
		 public function get_exec_dir_flag() {
		 
			return $this->_exec_dir_set;

		 }

		/**
		 *	set_ignore_warnings()
		 *
		 *	Checks conditions to see if warnings should be ignored when archives are
		 *	being built.
		 *
		 *	@param		bool	$ignore	False to not ignore warnings, True to force ignore
		 *	@return		bool			True if conditions indicate warnings should be ignored, false otherwise
		 *
		 */
		 public function set_ignore_warnings( $ignore = null ) {
		 
		 	$this->_ignore_warnings = ( is_bool( $ignore ) ) ? $ignore : false ;

			return $this;

		 }

		/**
		 *	get_ignore_warnings()
		 *
		 *	Gets the flag indicating whether warnings should be ignored when building archives
		 *
		 *	@return		bool		Value of $_ignore_warnings
		 *
		 */
		 public function get_ignore_warnings() {
		 
			return $this->_ignore_warnings;

		 }

		/**
		 *	set_ignore_synlinks()
		 *
		 *	Checks conditions to see if symlinks should be ignored/not-followed when archives are
		 *	being built.
		 *
		 *	@param		bool	$ignore	False to not ignore symlinks, True to force ignore
		 *	@return		bool			True if conditions indicate symlinks should be ignored/not-followed, false otherwise
		 *
		 */
		 public function set_ignore_symlinks( $ignore = null ) {
		 
		 	$this->_ignore_symlinks =  ( is_bool( $ignore ) ) ? $ignore : true ;

			return $this;

		 }

		/**
		 *	get_ignore_symlinks()
		 *	
		 *	This returns true if the option to ignore symlinks is set. In this context ignoring
		 *	means not following but the symlink itself is recorded in the backup
		 *	
		 *	@return		bool				Value of $_ignore_symlinks
		 *
		 */
		protected function get_ignore_symlinks() {
		
			return $this->_ignore_symlinks;
		
		}
		
		/**
		 *	set_compression()
		 *
		 *	Checks conditions to see if compression should be used when building archive.
		 *
		 *	@param		bool	$compression	False to prohibit compression, True to force compression
		 *	@return		bool					True if conditions indicate compression should be used, false otherwise
		 *
		 */
		 public function set_compression( $compression = null ) {
		 
		 	$this->_compression =  ( is_bool( $compression ) ) ? $compression : true ;

			return $this;

		 }

		/**
		 *	get_compression()
		 *	
		 *	This returns true if the option to use compression is set.
		 *	
		 *	@return		bool				Value of $_compression
		 *
		 */
		protected function get_compression() {
		
			return $this->_compression;
		
		}
		
		/**
		 *	set_sapi_name()
		 *
		 *	Sets the sapi name to that given or leave empty
		 *
		 *	@param	string	$name	A sapi name to set (default empty)
		 *	@return	object			This object
		 */
		public function set_sapi_name( $sapi_name = "" ) {
		
			$this->_sapi_name = $sapi_name;
			
			return $this;
			
		}

		/**
		 *	get_sapi_name()
		 *
		 *	Returns the previously set sapi name
		 *
		 *	@return	string			The stored sapi name
		 */
		public function get_sapi_name() {
			
			return $this->_sapi_name;
			
		}

		/**
		 *	get_status()
		 *	
		 *	Returns the status array
		 *	
		 *	@return		array	The status array
		 *
		 */
		public function get_status() {
		
			return $this->_status;
		
		}
		
		/**
		 *	log_archive_file_stats()
		 *	
		 *	Produced a status log entry for the archive file stats
		 *	
		 *	@param	string	$file	The file to stat and and log
		 *	@return		
		 *
		 */
		protected function log_archive_file_stats( $file ) {
		
			// Get the file stats so we can log some information
			$file_stats = pluginbuddy_stat::stat( $file );
			
			// Only log anything if we got some valid file stats
			if ( false !== $file_stats ) {
			
				pb_backupbuddy::status( 'details', sprintf( __( 'Zip Archive file size: %1$s bytes, owned by user:group %2$s:%3$s with permissions %4$s', 'it-l10n-backupbuddy' ), $file_stats[ 'dsize' ], $file_stats[ 'uid' ], $file_stats[ 'gid' ], $file_stats[ 'mode_octal_four' ] ) );

			}
			
		}

		/**
		 *	get_method_tag()
		 *	
		 *	Returns the (static) method tag
		 *	
		 *	@return		string The method tag
		 *
		 */
		abstract public function get_method_tag();

		/**
		 *	get_is_compatibility_method()
		 *	
		 *	Returns the (static) is_compatibility_method boolean
		 *	
		 *	@return		bool
		 *
		 */
		abstract public function get_is_compatibility_method();

		/**
		 *	get_method_details()
		 *	
		 *	Returns the details array
		 *	
		 *	@return		array
		 *
		 */
		public function get_method_details() {
		
			return $this->_method_details;
			
		}

		/**
		 *	set_method_details()
		 *	
		 *	Sets the internal (settable) details
		 *	
		 *	@param		array
		 *	@return		null
		 *
		 */
		public function set_method_details( array $details, $merge = true ) {
		
			if ( true === $merge ) {
			
				$this->_method_details[ 'attr' ] = array_merge( $this->_method_details[ 'attr' ], $details[ 'attr' ] );
				$this->_method_details[ 'param' ] = array_merge( $this->_method_details[ 'param' ], $details[ 'param' ] );
			
			} else {
			
				$this->_method_details = $details;
			
			}
			
			return $this;
						
		}

		/**
		 *	get_executable_paths()
		 *	
		 *	Returns the executable_paths array
		 *	
		 *	@return		array
		 *
		 */
		public function get_executable_paths() {
		
			return $this->_executable_paths;
			
		}

		/**
		 *	set_executable_paths()
		 *	
		 *	Sets the executable_paths array so can be used to augment or override the default
		 *	
		 *	@param		$paths	array	Paths to set or merge
		 *	@param		$merge	bool	True (default) if merging paths with current paths
		 *	@param		$before	bool	True (default) if paths to be prepended
		 *	@return		null
		 *
		 */
		public function set_executable_paths( array $paths, $merge = true, $before = true ) {
		
			if ( true === $merge ) {
			
				if ( true === $before ) {
				
					$this->_executable_paths = array_merge( $paths, $this->_executable_paths );
					
				} else {
			
					$this->_executable_paths = array_merge( $this->_executable_paths, $paths );
				
				}
			
			} else {
			
				$this->_executable_paths = $paths;
			
			}
			
			return $this;
						
		}

		/**
		 *	delete_directory_recursive()
		 *	
		 *	Recursively delete a directory and it's content
		 *	
		 *	@param		string	$directory	Directory to delete
		 *	@return		bool				True if operation fully successful, otherwise false
		 *
		 */
		protected function delete_directory_recursive( $directory ) {

			// Remove any trailing directory separator so we know where we are
			$directory = rtrim( $directory, self::DIRECTORY_SEPARATORS );
			
			// Non-existent directory so pretend we deleted it ok
			if ( !file_exists( $directory ) ) {
			
				return true;
				
			}

			// Make sure it wasn't just a file or link - if so just delete it and return			
			if ( !is_dir( $directory ) || is_link( $directory ) ) {
			
				return @unlink( $directory );
				
			}
			
			// So it is a directory so process content
			foreach ( scandir( $directory ) as $item ) {
			
				// Skip the this and parent directories
				if ( $item == '.' || $item == '..' ) {
				
					continue;
					
				}
				
				// Delete the item if we can			
				if ( !$this->delete_directory_recursive( $directory . "/" . $item ) ) {
				
					// TODO: Supposedly change the perms on the item so we can delete it?
					@chmod( $directory . "/" . $item, 0777 );
					
					if ( !$this->delete_directory_recursive( $directory . "/" . $item ) ) {
					
						return false;
						
					}
					
				}
				
			}
			
			return @rmdir( $directory );
				
		}
		
		/*	_render_exclusions_file()
		 *	
		 *	function description
		 *	
		 *	@param		string		$file			File to write exclusions into.
		 *	@param		array		$exclusions		Array of directories/paths to exclude. One per line.
		 *	@return		null
		 */
		protected function _render_exclusions_file( $file, $exclusions ) {
		
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
		
		/**
		 *	slashify()
		 *
		 *	A function to add a slash to the end of a path. It is much like the WordPress trailingslashit()
		 *	but allows for not adding a slash to an empty path. Will add a normalized slash unless overridden
		 *	Note: Will not process any embedded directory separators
		 *
		 *	@param	string	$path					The path to add a trailing slash to
		 *	@param	bool	$ignore_empty			True (default) if should _not_ add a trailing slash to an empty path
		 *	@param	bool	$use_normalized_slash	True (default) to add a normalized slash, otherwise add platform separator
		 *	@return	string							The path with trailing slash optionally added
		 *
		 */
		 
		 protected function slashify( $path, $ignore_empty = true, $use_normalized_slash = true ) {
		 
		 	// Check if it is empty now before we may remove a single slash
		 	if ( ! ( empty( $path ) && ( true === $ignore_empty ) ) ) {
		 	
				// First remove any trailing slash that may be present
				$path = $this->unslashify( $path );
				
				if ( true === $use_normalized_slash ) {
				
					$path = $path . self::NORM_DIRECTORY_SEPARATOR;
				
				} else {
				
					$path = $path . DIRECTORY_SEPARATOR;
				
				}
				
		 	}
		 	
		 	return $path;
		 
		 }
		
		/**
		 *	unslashify()
		 *
		 *	A function to remove a slash to the end of a path. It is much like the WordPress untrailingslashit()
		 *	but copes with either form of trailing slash.
		 *	Note: Will not process any embedded directory separators and may produce an empty path.
		 *
		 *	@param	string	$path					The path to remove a trailing slash from
		 *	@param	bool	$ignore_empty			True (default) if should proceed even if will produce an empty path
		 *	@return	string							The path with trailing slash removed
		 *
		 */
		 
		 protected function unslashify( $path, $ignore_empty = true ) {
		 
		 	// Create a candidate path to optionally return
		 	$candidate_path = rtrim( $path, self::DIRECTORY_SEPARATORS );
		 
		 	// If candidate isn't empty or we're ignoring it being empty anyway
		 	if ( !empty( $candidate_path ) || ( true === $ignore_empty ) ) {
		 	
				$path = $candidate_path;
				
		 	}
		 	
		 	return $path;
		 
		 }
		
		/**
		 *	log_zip_reports()
		 *
		 *	A function to process reports parsed from the zip process output and log them and optionally
		 *	send to a file if there are a lot of reports. If the number of reports is such that they require
		 *	to be written to a file then all the reports will be written to the file, not just the overflow.
.		 *
		 *	@param	array	$reports_log			array containing the type of reports to log
		 *	@param	array	$reports_desc			array containing text description of report reason
		 *	@param	string	$report_prefix			a prefix string to go before the report text
		 *	@param	integer	$report_lines_to_show	the number of reports to show in log before overflowing to a file
		 *	@param	string	$report_file			overflow file if too many reports to show directly in log
		 *	@return	N/A								Currently no return parameter
		 *
		 */
		 
		protected function log_zip_reports( $reports_log, $report_desc, $report_prefix, $report_lines_to_show, $reports_file ) {

			$reports = array();
			$reports_count = 0;
			$result = false;

			// Make sure we clear up ant previous reports file that may still be present
			if ( @file_exists( $reports_file ) ) {
	
				@unlink( $reports_file );
		
			}

			// Parse the reports array into an ordered array based on id (log line number) as sort key
			foreach ( $reports_log as $reason => $report ) {
	
				foreach ( $report as $id => $filename ) {

					$reports[ $id ] = sprintf( __( '%1$s: (%2$s): %3$s' . PHP_EOL,'it-l10n-backupbuddy' ), $report_prefix, $report_desc[ $reason ], $filename );

				}
	
			}
	
			// Make sure array is now ordered by the numeric log line number key
			$result = ksort( $reports, SORT_NUMERIC );

			// Always show the first number of lines in the log
			$show_lines = array_slice( $reports, 0, $report_lines_to_show, true );

			foreach ( $show_lines as $line ) {

				pb_backupbuddy::status( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );

			}
		
			// If there were more lines then output the whole to the report file
			$reports_count = sizeof( $reports );
			if ( $reports_count  > $report_lines_to_show ) {
	
				@file_put_contents( $reports_file, $reports );
		
				if ( @file_exists( $reports_file ) ) {
		
					pb_backupbuddy::status( 'details', sprintf( __( 'Zip process reported %1$s more %2$s report%3$s - please review in: %4$s','it-l10n-backupbuddy' ), ( $reports_count - $report_lines_to_show ), $report_prefix, ( ( 1 == $reports_count ) ? '' : 's' ), $reports_file ) );
			
				}
		
			}
			
		}
		
		/**
		 *	is_available()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		abstract public function is_available( $tempdir );
		
		/**
		 *	create()
		 *	
		 *	A function that creates an archive file
		 *	
		 *	The $excludes will be a list or relative path excludes if the $listmaker object is NULL otherwise
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
		abstract public function create( $zip, $dir, $excludes, $tempdir, $listmaker = NULL );
		
		/**
		 *	extract()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@param	array		$items						Mapping of what to extract and to what
		 *	@return	bool									true on success (all extractions successful), false otherwise
		 */
		abstract public function extract( $zip_file, $destination_directory = '', $items = array() );

		/**
		 *	file_exists()
		 *	
		 *	Tests whether a file (with path) exists in the given zip file
		 *	If leave_open is true then the zip object will be left open for faster checking for subsequent files within this zip
		 *	
		 *	@param		string	$zip_file		The zip file to check
		 *	@param		string	$locate_file	The file to test for
		 *	@param		bool	$leave_open		Optional: True if the zip file should be left open
		 *	@return		bool					True if the file is found in the zip otherwise false
		 *
		 */
		abstract public function file_exists( $zip_file, $locate_file, $leave_open = false );
		
		/*	get_file_list()
		 *	
		 *	Get an array of all files in a zip file with some file properties.
		 *	
		 *	@param		string		$zip_file	The file to list the content of
		 *	@return		bool|array				false on failure, otherwise array of file properties (may be empty)
		 */
		abstract public function get_file_list( $zip_file );
		
		/*	set_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		bool							true on success, otherwise false.
		 */
		abstract public function set_comment( $zip_file, $comment );

		/*	get_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		bool|string					false on failure, Zip comment otherwise.
		 */
		abstract public function get_comment( $zip_file );
		

	} // end pluginbuddy_zbzipcore class.	
	
}
?>
