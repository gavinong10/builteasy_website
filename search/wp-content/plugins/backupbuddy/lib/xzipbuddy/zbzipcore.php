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

	/**
	 *	pb_backupbuddy_zip_monitor Class
	 *
	 *	Class that is used during a zip archive file build to monitor the progress
	 *	of the build and adjust build parameters accordingly.
	 *
	 */
	 class pb_backupbuddy_zip_monitor {
	
		// Enumerate the burst modes
		const ZIP_UNKNOWN_BURST = 0;
		const ZIP_SINGLE_BURST 	= 1;
		const ZIP_MULTI_BURST  	= 2;
		
		// Various period defaults
		// These are use for methods where we vary the burst content size
		const ZIP_DEFAULT_BURST_MIN_SIZE = 10485760; // 10MB
		const ZIP_DEFAULT_BURST_MAX_SIZE = 104857600; // 100MB
		
		const ZIP_DEFAULT_BURST_MAX_PERIOD = 30;
		const ZIP_DEFAULT_BURST_THRESHOLD_PERIOD = 10;
	
		protected $_added_dir_count = 0;
		protected $_added_file_count = 0;
		
		protected $_burst_threshold_period = 0;
		protected $_burst_start_time = 0;
		protected $_burst_stop_time = 0;
		protected $_burst_max_period = 0;
		
		protected $_burst_size_min = 0;
		protected $_burst_size_max = 0;
		protected $_burst_current_size_threshold = 0;
		
		protected $_burst_mode = self::ZIP_UNKNOWN_BURST;
		
		protected $_creation_time = 0;
		
		protected $_burst_content_size = 0;
		protected $_burst_content_count = 0;
		protected $_burst_count = 0;
		protected $_burst_content_complete = false;
		protected $_last_burst_duration = 0;
		protected $_last_burst_size = 0;

         /**
         * The logger we will use
         * 
         * @var logger	object
         */
		protected $_logger = null;
	
        /**
         * Our object instance
         * 
         * @var $_instance 	object
         */
		protected static $_instance = null;
	
		public function __construct( &$parent = null ) {
		
			// Inherit the parent logger by default (if it has one), caller may override after instantiated
			if ( $parent && method_exists( $parent, 'get_logger' ) ) {
			
				$this->set_logger( $parent->get_logger() );
				
			}
		
			self::$_instance = $this;
			
			$now = time();
			
			$this->set_creation_time( $now );
			
			$this->set_burst_start_time( $now );
			$this->set_burst_max_period( 'auto' );
			//$this->set_burst_threshold_period( 'auto' ); // This is done automatically by set_burst_max_period()

			$this->set_burst_size_min();
			$this->set_burst_size_max();
			$this->set_burst_current_size_threshold( $this->get_burst_size_min() );
			
			$this->set_burst_mode( self::ZIP_MULTI_BURST );
			
			$this->log_parameters();

		}
		
		public function __destruct() {
		
			self::$_instance = null;

		}
		
		/**
		 * 
		 *	get_instance()
		 *
		 *	If the object is already created then simply return the instance else
		 *	create an object and return the instance.
		 *	Currently only one instance is allowed at a time but currently there is
		 *	no scenario that would require more than one at any time.
		 *
		 *	@return		object					This object instance	
		 *
		 */
		public static function get_instance() {
		
			if ( null === self::$_instance ) {
			
				self::$_instance = new self;
				
			}
		
			return self::$_instance;
			
		}
		
		// Methods for keeping track of burst content
		
		public function get_added_dir_count() {
		
			return $this->_added_dir_count;
		
		}
	
		public function set_added_dir_count( $count = 0 ) {
		
			$this->_added_dir_count = $count;
			return $this;
		
		}
	
		public function incr_added_dir_count( $incr = 1 ) {
		
			$this->_added_dir_count += $incr;
			return $this;
		
		}
	
		public function get_added_file_count() {
		
			return $this->_added_file_count;
		
		}
		
		public function set_added_file_count( $count = 0 ) {
		
			$this->_added_file_count = $count;
			return $this;
		
		}
		
		public function incr_added_file_count( $incr = 1 ) {
		
			$this->_added_file_count += $incr;
			return $this;
		
		}
		
		// Methods for handling burst content size
		
		public function get_burst_current_size_threshold() {
		
			return $this->_burst_current_size_threshold;
		
		}
		
		public function set_burst_current_size_threshold( $size = self::ZIP_DEFAULT_BURST_MIN_SIZE ) {
		
			// As we are dealing in doubles but we only really want the integer
			// part so use floor() whenever the value is set
			$this->_burst_current_size_threshold = (double)floor( $size );
			return $this;
			
		}
		
		public function get_burst_size_min() {
		
			return $this->_burst_size_min;
		
		}
		
		public function set_burst_size_min( $size = self::ZIP_DEFAULT_BURST_MIN_SIZE) {
		
			$this->_burst_size_min = (double)floor( $size );
			( $size > $this->get_burst_size_max() ) ? $this->set_burst_size_max( $size ) : false ;
			return $this;
		
		}
		
		public function get_burst_size_max() {
		
			return $this->_burst_size_max;
		
		}
		
		public function set_burst_size_max( $size = self::ZIP_DEFAULT_BURST_MAX_SIZE) {
		
			$this->_burst_size_max = (double)floor( $size );
			( $size < $this->get_burst_size_min() ) ? $this->set_burst_size_min( $size ) : false ;
			return $this;
		
		}

		// Methods for handling a burst
		
		public function get_burst_count() {
		
			return $this->_burst_count;
		}
		
		public function get_burst_content_size() {
		
			return $this->_burst_content_size;
		
		}
		
		public function get_burst_content_count() {
		
			return $this->_burst_content_count;
		
		}
		
		/**
		 * 
		 *	burst_begin()
		 *
		 *	Initializes to begin putting together content for a new burst
		 *
		 *	@return		none
		 *
		 */
		public function burst_begin() {
		
			$this->_burst_content_size = (double)0;
			$this->_burst_content_count = 0;
			++$this->_burst_count;
			$this->_burst_content_complete = false;
			$this->_last_burst_duration = 0;
			$this->_last_burst_size = (double)0;
		
		}
		
		/**
		 * 
		 *	burst_end()
		 *
		 *	Wrap up after the end of the current burst
		 *
		 *	@return		none
		 *
		 */
		public function burst_end() {
		
		}
		
		/**
		 * 
		 *	burst_content_added()
		 *
		 *	Gives us information on what has just been added to burst content so we
		 *	can assess the progress against our criteria for completion of the current
		 *	burst content.
		 *
		 *	@param		array		$content	Details about the item just added
		 *	@return		none
		 *
		 */
		public function burst_content_added( $content ) {
		
			// Increment the appropriate count
			( true === $content[ 'directory' ] ) ? $this->incr_added_dir_count() : $this->incr_added_file_count() ;
			
			// Increment the total size of current burst
			$this->_burst_content_size += (double)$content[ 'size' ];
			++$this->_burst_content_count;
			
			$this->_burst_content_complete = ( $this->get_burst_current_size_threshold() <= $this->_burst_content_size );
			
		}
		
		/**
		 * 
		 *	burst_content_complete()
		 *
		 *	Return whether or not we consider the burst content to be complete for the
		 *	current burst
		 *
		 *	@return		bool					True if complete, false otherwise
		 *
		 */
		public function burst_content_complete() {
		
			return $this->_burst_content_complete;
		
		}
		
		/**
		 * 
		 *	burst_start()
		 *
		 *	Signals that the burst activity is about to be started so we can start to
		 *	monitor it for the purposes of assessing how it went and how that will impact
		 *	the next burst
		 *
		 *	@return		none
		 *
		 */
		public function burst_start() {
		
			$this->set_burst_start_time( time() ); // Note when we started
		
		}
		
		/**
		 * 
		 *	burst_stop()
		 *
		 *	Signals that the burst activity has completed so we can stop the monitoring
		 *	and decide how the next burst is going to go
		 *
		 *	@return		none
		 *
		 */
		public function burst_stop() {
		
			$this->set_burst_stop_time();
			
			// Update the threshold in case we have more to do
			$this->update_burst_current_size_threshold();
			
		}
		
		/**
		 * 
		 *	update_burst_current_size_threshold()
		 *
		 *	Signals that the burst activity has completed so we can stop the monitoring
		 *	and decide how the next burst is going to go
		 *
		 *	Current algorithm is quite simple:
		 *	Nbt - New Burst Threshold
		 *	Cbt - Current Burst Threshold
		 *	Lbd - Last Burst Duration
		 *	Bdmp - Burst Default Max Period
		 *	
		 *	Nbt = Cbt + ( Cbt * ( ( Bdmp - Ldb ) / Bdmp ) )
		 *	
		 *	Which basically means the longer the burst of the current size threshold takes
		 *	to complete the smaller the amount by which we increase the burst size threshold.
		 *	Additionally, if the actual last burst duration _exceeds_ the maximum time we want
		 *	to allow a burst to run then the increment factor will become negative and we'll
		 *	reduce the burst size threshold, the bigger the overrun the bigger the reduction.
		 *	Finally we'll make sure that we keep the threshold within some min/max limits
		 *	which are currently predefined but in theory later we could make these adaptive
		 *	and/or allow the user to set them to allow for specific server capabilities - i.e.,
		 *	if the server is very fast then the high cap could be raised.
		 *
		 *	Note: an additional option would be to allow the user to set an initial
		 *	threshold size and if that were very large (or maybe 0 meaning no limit) then
		 *	the process would revert to a single burst.
		 *
		 *	@return		none
		 *
		 */
		public function update_burst_current_size_threshold() {
		
			$last_burst_duration = ( $this->get_burst_stop_time() - $this->get_burst_start_time() );
			$last_burst_duration = ( 1 > $last_burst_duration ) ? 1 : $last_burst_duration;
			
			// Calculate the increment factor - this will be +ve if the last burst took less
			// than our allowed maximum time (so we can try increasing the burst content size) or
			// -ve if the burst took longer than we would like (in which case we'll reduce the
			// burst content size).
			$factor = (float)( ( (float)$this->get_burst_max_period() - (float)$last_burst_duration ) / (float)$this->get_burst_max_period() );
			
			// Calculate a new burst size threshold - under extreme conditions it could come
			// out negative but in any case we're then going to make sure it is within certain
			// sensible bounds.
			$this->set_burst_current_size_threshold ( (double)( $this->get_burst_current_size_threshold() + ( (float)$this->get_burst_current_size_threshold() * (float)$factor) ) );

			// Now let's make sure we stay within min/max threshoild limits
			$this->set_burst_current_size_threshold ( ( $this->get_burst_size_min() > $this->get_burst_current_size_threshold() ) ? $this->get_burst_size_min() : $this->get_burst_current_size_threshold() );
			$this->set_burst_current_size_threshold ( ( $this->get_burst_current_size_threshold() < $this->get_burst_size_max() ) ? $this->get_burst_current_size_threshold() : $this->get_burst_size_max() );
			
		}

		// Methods for handling the burst period

		public function get_burst_threshold_period() {
		
			return $this->_burst_threshold_period;
		
		}
	
		public function set_burst_threshold_period( $period = self::ZIP_DEFAULT_BURST_THRESHOLD_PERIOD ) {
		
			$burst_max_period = 0;
		
			if ( true === is_string( $period ) ) {
			
				switch ( $period ) {
				
					case 'auto':
						// If auto then set based on burst max period
						if ( 0 === ( $burst_max_period = $this->get_burst_max_period() ) ) {
						
							// Not set yet so we need to set it with auto
							// Ensure we don't get into a recursive loop...
							$burst_max_period = $this->set_burst_max_period( 'auto', false )->get_burst_max_period();
						
						}
					
						// Bit of an arbitrary proportion...
						$this->_burst_threshold_period = (int)( $burst_max_period / 3 );
						break;
						
					default:
						// Unknown mode so use default value
						$this->_burst_threshold_period = self::ZIP_DEFAULT_BURST_THRESHOLD_PERIOD;
				
				}
			
			} else {
			
				// Assume integer?
				$this->_burst_threshold_period = $period;
				
			}
			
			return $this;
		
		}
	
		public function get_burst_start_time() {
		
			return $this->_burst_start_time;
		
		}
	
		public function set_burst_start_time( $time = 0 ) {
		
			( 0 === $time ) ? $this->_burst_start_time = time() : $this->_burst_start_time = $time ;
			return $this;
		
		}
	
		public function get_creation_time() {
		
			return $this->_creation_time;
		
		}
	
		public function set_creation_time( $time = 0 ) {
		
			( 0 === $time ) ? $this->_creation_time = time() : $this->_creation_time = $time ;
			return $this;
		
		}
		
		public function get_elapsed_time() {
			
			return ( time() - $this->get_creation_time() );
			
		}
	
		public function get_burst_stop_time() {
		
			return $this->_burst_stop_time;
		
		}
	
		public function set_burst_stop_time( $time = 0 ) {
		
			( 0 === $time ) ? $this->_burst_stop_time = time() : $this->_burst_stop_time = $time ;
			return $this;
		
		}
	
		public function get_burst_max_period() {
		
			return $this->_burst_max_period;
		
		}

		// NOTE: using the max_execution_time for this isnot so relevant as we are more likely
		// restricted by something like fastcgi iotimeout but unfortunately we cannot find that
		// out programmatically. For now we'll leave as is and the specific zip method helper
		// will do it's own setting in it's constructor overriding the parent constructor
		// initialization. These parameters are not relevant fo all zip methods/strategies - for
		// example, with pclzip we can run in the mode where we give it all the files to
		// invlude and we are driven by it's callback and we use that to manage the server
		// tickling and resetting of execution time if required. For exec we do work off
		// burst content size that we can accomodate within a defined burst max period so
		// we do use this (but we don't use the burst threshold period in any way).
		public function set_burst_max_period( $period = self::ZIP_DEFAULT_BURST_MAX_PERIOD, $auto_set_threshold = true ) {
		
			// Get the originally configured value if we can.
			// Returned values may be false or an integer as a string.
			// If false we want to keep it as false and we'll use our
			// default; if 0 | -1 then we'll set as larger integer;
			// otherwise if it is a numeric value and +ve we'll use it
			// otherwise set to false.
			$configured_execution_time = @get_cfg_var( 'max_execution_time' );
			
			$cfg_var_map = array(	false => false,
									"-1" => (int)PHP_INT_MAX,
									"0" => (int)PHP_INT_MAX,
									"" => false,
								);
			if ( array_key_exists( $configured_execution_time, $cfg_var_map ) ) {
				
				// "Special" value, map it
				$configured_execution_time = $cfg_var_map[ $configured_execution_time ];
				
			} else {
				
				// "Ordinary" value but must be numeric and +ve
				if ( is_numeric( $configured_execution_time ) && ( 0 < (int)$configured_execution_time )) {
					
					$configured_execution_time = (int)$configured_execution_time;
					
				} else {
					
					$configured_execution_time = false;
					
				}
				
			}
			
			// Get the current value if we can.
			// Returned values may be false or an integer as a string.
			// If false we want to keep it as false and we'll use our
			// default; if 0 | -1 then we'll set as larger integer;
			// if "7200" we have to assume it's because we set that
			// so make it false; otherwsie if it is a numeric value
			// and +ve we'll use it; otherwise set false.
			$current_execution_time = @ini_get( 'max_execution_time' );

			$ini_get_map = array(	false => false,
									"-1" => (int)PHP_INT_MAX,
									"0" => (int)PHP_INT_MAX,
									"7200" => false,
									"" => false,
								);
			if ( array_key_exists( $current_execution_time, $ini_get_map ) ) {
				
				// "Special" value, map it
				$current_execution_time = $ini_get_map[ $current_execution_time ];
				
			} else {
				
				// "Ordinary" value but must be numeric and +ve
				if ( is_numeric( $current_execution_time ) && ( 0 < (int)$current_execution_time )) {
					
					$current_execution_time = (int)$current_execution_time;
					
				} else {
					
					$current_execution_time = false;
					
				}
				
			}
		
		
			if ( true === is_string( $period ) ) {
			
				switch ( $period ) {
				
					case 'auto':
						// Try for the currently set execution time
						if ( false === $current_execution_time ) {
						
							// Couldn't get a value so try for configured value
							if ( false === $configured_execution_time ) {
						
								// Couldn't get a configured value for some reason so use default
								$this->_burst_max_period = self::ZIP_DEFAULT_BURST_MAX_PERIOD;
							
							} else {
						
								// Got a configured value so use it
								// Subtract 10% to give some headroom (only when value is derived like this
								// but not if the caller configured a value as below)
								$this->_burst_max_period = (int)( ( (int) $configured_execution_time / 10 ) * 9 );
						
							}
							
						} else {
						
							// Got a non-zero current execution time so use it
							$this->_burst_max_period = (int)( ( (int) $current_execution_time / 10 ) * 9 );
						
						}
						
						// If set by auto then make sure we (re)set threshold as auto _unless_
						// told not to...
						if ( true === $auto_set_threshold ) {
						
							$this->set_burst_threshold_period( 'auto' );
							
						}
						break;
					
					default:
						// Unknown mode so use default value
						$this->_burst_max_period = self::ZIP_DEFAULT_BURST_MAX_PERIOD;
				
				}
			
			} elseif ( is_numeric( $period ) && ( 0 < $period ) )  {
			
				$this->_burst_max_period = (int)$period;
			
			} else {
				
				$this->_burst_max_period = self::ZIP_DEFAULT_BURST_MAX_PERIOD;
				
			}
		
			return $this;
		
		}
	
		// General Methods
		
		public function set_burst_mode( $burst_mode = self::ZIP_MULTI_BURST ) {
		
			$this->_burst_mode = $burst_mode;
			return $this;
		
		}
		
		public function get_burst_mode() {
		
			return $this->_burst_mode;
		
		}
		
		public function is_multi_burst() {
		
			return ( self::ZIP_MULTI_BURST === $this->_burst_mode );
			
		}

		// Log our setup parameters
		public function log_parameters() {
		
			// Need to preformat the potentially very large doubles for printing as
			// sprintf formatting cannot do it - in particular a large double to print
			// as integer like on a 32 bit system
			$burst_max_period = number_format( $this->get_burst_max_period(), 0, ".", "" );
			$burst_threshold_period = number_format( $this->get_burst_threshold_period(), 0, ".", "" );
			$burst_size_min = number_format( $this->get_burst_size_min(), 0, ".", "" );
			$burst_size_max = number_format( $this->get_burst_size_max(), 0, ".", "" );
			$burst_current_size_threshold = number_format( $this->get_burst_current_size_threshold(), 0, ".", "" );
			
			$this->log( 'details', sprintf( __('Zip process reported: : Burst max/threshold periods: %1$ss/%2$ss','it-l10n-backupbuddy' ), $burst_max_period, $burst_threshold_period ) );
			$this->log( 'details', sprintf( __('Zip process reported: : Burst size min/max/threshold: %1$s/%2$s/%3$s','it-l10n-backupbuddy' ), $burst_size_min, $burst_size_max, $burst_current_size_threshold ) );

			return $this;

		}
		public function log( $level, $message ) {
			
			$this->get_logger()->log( $level, $message );
			
			return $this;
			
		}
		
		public function set_logger( $logger ) {
			
			$this->_logger = $logger;
			
			return $this;
			
		}
		
		public function get_logger() {
			
			if ( is_null( $this->_logger ) ) {
				
				$logger = new pluginbuddy_zipbuddy_null_object();
				$this->set_logger( $pm );
				
			}
			
			return $this->_logger;
			
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
		
		// OS Specific null device name for shell redirection as required
		const OS_TYPE_NIX_NULL_DEVICE = '/dev/null';
		const OS_TYPE_WIN_NULL_DEVICE = 'nul';
		
		const ZIP_BURST_GAP_MIN = 0;

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
         * The platform specific null device for shell output redirection
         * Assume *nix type by default.
         * Note: we can only redirect shell output if exec_dir is not in use
         * as this prohibits the use of redirection meta-characters
         * 
         * @var os_type_null_device	string
         */
		protected $_os_type_null_device = self::OS_TYPE_NIX_NULL_DEVICE;
		
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
         * Convenience integer indicating the maximum period for any action during a step
         * 
         * @var step_period	int
         */
		protected $_step_period = null;
		
         /**
         * Convenience integer indicating the gap to delay between burst
         * 
         * @var burst_gap	int
         */
		protected $_burst_gap = null;
		
          /**
         * Convenience double indicating the minimum burst content size
         * 
         * @var min_burst_content	double
         */
		protected $_min_burst_content = null;
		
          /**
         * Convenience double indicating the maximum burst content size
         * 
         * @var max_burst_content	double
         */
		protected $_max_burst_content = null;
		
          /**
         * Convenience integer indicating the burst threshold period
         * 
         * @var burst_threshold_period	int
         */
		protected $_burst_threshold_period = null;
		
         /**
         * The logger we will use
         * 
         * @var logger	object
         */
		protected $_logger = null;
	
          /**
         * The process monitor we will use
         * 
         * @var logger	object
         */
		protected $_process_monitor = null;
	
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
		public function __construct( &$parent = null ) {
			
			// Inherit the parent logger by default (if it has one), caller may override after instantiated
			if ( $parent && method_exists( $parent, 'get_logger' ) ) {
			
				$this->set_logger( $parent->get_logger() );
				
			}
		
			// Inherit the parent process monitor by default (if it has one), caller may override after instantiated
			if ( $parent && method_exists( $parent, 'get_process_monitor' ) ) {
			
				$this->set_process_monitor( $parent->get_process_monitor() );
				
			}
		
			// Make sure we know what we are running on for later
			$this->set_os_type();
			
			// Derive whether we are ignoring Warnings or not (expected to be overridden by user)
			$this->set_ignore_warnings();
			
			// Derive whether we are ignoring/not-following symlinks or not (expected to be overridden by user)
			$this->set_ignore_symlinks();
			
			// Derive whether compression should be used (expected to be overridden by user)
			$this->set_compression();
			
			// Derive step period after which a new step must be initiated
			$this->set_step_period();
			
			// Gap between bursts if a server needs time to catch up or reduce load
			$this->set_burst_gap();
			
			// Least amount of data we want to try and add during a burst
			$this->set_min_burst_content();
			
			// Most amount of data we want to try and add during a burst
			$this->set_max_burst_content();
			
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
		 *	set_null_device()
		 *
		 *	Sets the platform specific null device
		 *
		 *	@param		$null_device	string	null device to set to override auto-set
		 *
		 */
		 public function set_null_device( $null_device = '' ) {

		 	// Check if we have been given a device string - haev to assume it is valid
		 	if ( !empty( $null_device ) ) {
		 	
		 		$this->_os_type_null_device = $null_device;
		 	
		 	} else {
		 	
		 		// We _should_ have already determined the OS type before calling this method
				switch( $this->get_os_type() ) {
				
					case self::OS_TYPE_NIX: 
						$this->_os_type_null_device = self::OS_TYPE_NIX_NULL_DEVICE;
						break;
					
					case self::OS_TYPE_WIN:
						$this->_os_type_null_device = self::OS_TYPE_WIN_NULL_DEVICE;
						break;
						
					default:
						$this->_os_type_null_device = self::OS_TYPE_NIX_NULL_DEVICE;
				
				}
		 	
		 	}
		 
			return $this;

		 }

		/**
		 *	get_null_device()
		 *
		 *	Gets the platform specific null device
		 *
		 *	@return		string		String representing null device
		 *
		 */
		 public function get_null_device( ) {
		 
			return $this->_os_type_null_device;

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
		 *	@return		object					This object
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
		 *	@return		object					This object
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
		 *	@return		object					This object
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
		 *	set_step_period()
		 *
		 *	Sets the step period to use after which we start new step
		 *
		 *	@param		mixed	$step_period	Should be integer period but could be null
		 *	@return		object					This object
		 *
		 */
		 public function set_step_period( $step_period = null ) {
		 
		 	$this->_step_period =  ( is_int( $step_period ) ) ? $step_period : PHP_INT_MAX ;

			return $this;

		 }

		/**
		 *	get_step_period()
		 *	
		 *	This returns the step period to use.
		 *	
		 *	@return		int				Value of $_step_period
		 *
		 */
		protected function get_step_period() {
		
			return $this->_step_period;
		
		}
		
		/**
		 *	exceeded_step_period()
		 *	
		 *	This returns true if provided period is > step period
		 *	
		 *	@return		bool			True if exceeded, otherwise false
		 *
		 */
		protected function exceeded_step_period( $elapsed = 0 ) {
		
			return ( $elapsed < $this->get_step_period() ) ? false : true ;
		
		}		
		
		/**
		 *	set_burst_gap()
		 *
		 *	Sets the interburst gap period that we wait for server to catch up or reduce load
		 *
		 *	@param		mixed	$burst_gap	Should be integer period but could be null
		 *	@return		object				This object
		 *
		 */
		 public function set_burst_gap( $burst_gap = null ) {
		 
		 	$this->_burst_gap =  ( is_int( $burst_gap ) ) ? $burst_gap : self::ZIP_BURST_GAP_MIN ;

			return $this;

		 }

		/**
		 *	get_burst_gap()
		 *	
		 *	This returns the burst gap to use.
		 *	
		 *	@return		int				Value of $_burst_gap
		 *
		 */
		protected function get_burst_gap() {
		
			return $this->_burst_gap;
		
		}
		
		/**
		 *	set_min_burst_content()
		 *
		 *	Sets the min burst content size we want to add during a burst
		 *
		 *	@param		mixed	$min_burst_content	Should be double but could be null
		 *	@return		object						This object
		 *
		 */
		 public function set_min_burst_content( $min_burst_content = null ) {
		 
			// example of a "large" value on either a 32 or 64 bit system
		 	$default = ( 4 == PHP_INT_SIZE ) ? (double)( pow(2, 63) - 1 ) : (double)PHP_INT_MAX ;
		 	// Need to convert $min_burst_content to a double only if it is numeric
		 	$min_burst_content = ( is_numeric( $min_burst_content ) ) ? (double)$min_burst_content : $min_burst_content ;
		 	
		 	$this->_min_burst_content =  ( is_double( $min_burst_content ) ) ? $min_burst_content : $default ;

			return $this;

		 }

		/**
		 *	get_min_burst_content()
		 *	
		 *	This returns the minimum burst content to use.
		 *	
		 *	@return		double				Value of $_min_burst_content
		 *
		 */
		protected function get_min_burst_content() {
		
			return $this->_min_burst_content;
		
		}
		
		/**
		 *	set_max_burst_content()
		 *
		 *	Sets the max burst content size we want to add during a burst
		 *
		 *	@param		mixed	$max_burst_content	Should be double but could be null
		 *	@return		object						This object
		 *
		 */
		 public function set_max_burst_content( $max_burst_content = null ) {
		 
			// example of a "large" value on either a 32 or 64 bit system
		 	$default = ( 4 == PHP_INT_SIZE ) ? (double)( pow(2, 63) - 1 ) : (double)PHP_INT_MAX ;
		 	// Need to convert $max_burst_content to a double only if it is numeric
		 	$max_burst_content = ( is_numeric( $max_burst_content ) ) ? (double)$max_burst_content : $max_burst_content ;
		 	
		 	$this->_max_burst_content =  ( is_double( $max_burst_content ) ) ? $max_burst_content : $default ;

			return $this;

		 }

		/**
		 *	get_max_burst_content()
		 *	
		 *	This returns the maximum burst content to use.
		 *	
		 *	@return		double				Value of $_max_burst_content
		 *
		 */
		protected function get_max_burst_content() {
		
			return $this->_max_burst_content;
		
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
		protected function log_archive_file_stats( $file, $options = array() ) {
		
			// Get the file stats so we can log some information
			// Using our stat() function in case file size exceeds 2GB on a 32 bit PHP system
			$file_stats = pluginbuddy_stat::stat( $file );
			
			// Only log anything if we got some valid file stats
			if ( false !== $file_stats ) {
			
				$this->log( 'details', sprintf( __( 'Zip Archive file size: %1$s bytes, owned by user:group %2$s:%3$s with permissions %4$s', 'it-l10n-backupbuddy' ), number_format( $file_stats[ 'dsize' ], 0, ".", "" ), $file_stats[ 'uid' ], $file_stats[ 'gid' ], $file_stats[ 'mode_octal_four' ] ) );

				if ( isset( $options[ 'content_size' ] ) ) {
			
					// We have been given the size of the content that was added so let's
					// determine an approximate compression ratio
					$compression_ratio = $file_stats[ 'dsize' ] / (double)$options[ 'content_size' ];
					$this->log( 'details', sprintf( __( 'Zip Archive file size: content compressed to %1$d%% of original size (approximately)', 'it-l10n-backupbuddy' ), ( $compression_ratio * 100.00 ) ) );

				}
			
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
			
			$this->log( 'details', 'Creating backup exclusions file `' . $file . '`.' );
			//$exclusions = backupbuddy_core::get_directory_exclusions();
			
			// Test each exclusion for validity (presence) and drop those not actually present
			foreach( $exclusions as $exclusion ) {
				
				// Make sure platform specific directory separators are used (could have migrated from different platform)
				$exclusion = preg_replace( '|[' . addslashes( self::DIRECTORY_SEPARATORS ) . ']+|', DIRECTORY_SEPARATOR, $exclusion );
				
				// DIRECTORY.
				if ( is_dir( ABSPATH . ltrim( $exclusion, DIRECTORY_SEPARATOR ) ) ) {
					
					$this->log( 'details', 'Excluding directory `' . $exclusion . '`.' );
					
					// Need to add the wildcard so that zip will exclude the directory and content
					$exclusion = rtrim( $exclusion, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . '*';
				
				// FILE.
				} elseif ( is_file( ABSPATH . ltrim( $exclusion, DIRECTORY_SEPARATOR ) ) ) {
					
					$this->log( 'details', 'Excluding file `' . $exclusion . '`.' );
				
				// SYMBOLIC LINK.
				} elseif ( is_link( ABSPATH . ltrim( $exclusion, DIRECTORY_SEPARATOR ) ) ) {
					
					$this->log( 'details', 'Excluding symbolic link `' . $exclusion . '`.' );
				
				// DOES NOT EXIST.
				} else {
					
					$this->log( 'details', 'Omitting exclusion as file/directory does not currently exist: `' . $exclusion . '`.' );
					
					// Skip to next exclusion
					continue;
					
				}
				
				// We have a valid exclude so add it
				$sanitized_exclusions[] = $exclusion;
				
			}
			
			// Put the exclusions to a file as a string
			file_put_contents( $file, implode( PHP_EOL, $sanitized_exclusions ) . PHP_EOL );
			$this->log( 'details', 'Backup exclusions file created.' );
			
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

				$this->log( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );

			}
		
			// If there were more lines then output the whole to the report file
			$reports_count = sizeof( $reports );
			if ( $reports_count  > $report_lines_to_show ) {
	
				@file_put_contents( $reports_file, $reports );
		
				if ( @file_exists( $reports_file ) ) {
		
					$this->log( 'details', sprintf( __( 'Zip process reported %1$s more %2$s report%3$s - please review in: %4$s','it-l10n-backupbuddy' ), ( $reports_count - $report_lines_to_show ), $report_prefix, ( ( 1 == $reports_count ) ? '' : 's' ), $reports_file ) );
			
				}
		
			}
			
		}
		
		public function log( $level, $message ) {
			
			$this->get_logger()->log( $level, $message );
			
			return $this;
			
		}
		
		public function set_logger( $logger ) {
			
			$this->_logger = $logger;
			
			return $this;
			
		}
		
		public function get_logger() {
			
			if ( is_null( $this->_logger ) ) {
				
				$logger = new pluginbuddy_zipbuddy_null_object();
				$this->set_logger( $logger );
				
			}
			
			return $this->_logger;
			
		}
		
		public function set_process_monitor( $process_monitor ) {
			
			$this->_process_monitor = $process_monitor;
			
			return $this;
			
		}
		
		public function get_process_monitor() {
			
			if ( is_null( $this->_process_monitor) ) {
				
				$pm = new pluginbuddy_zipbuddy_null_object();
				$this->set_logger( $pm );
				
			}
			
			return $this->_process_monitor;
			
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
		 *	@return		bool					True if the creation was successful, array for continuation, false otherwise
		 *
		 */
		abstract public function create( $zip, $dir, $excludes, $tempdir );
		
		/**
		 *	grow()
		 *	
		 *	A function that grows and existing archive based on an already calculated content list
		 *	
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to grow
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@param		array	$state			The state information allowing us to pick up
		 *	@return		bool					True if the creation was successful, array for continuation, false otherwise
		 *
		 */
		abstract public function grow( $zip, $tempdir, $state );
		
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
