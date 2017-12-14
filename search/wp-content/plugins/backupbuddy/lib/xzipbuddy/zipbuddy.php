<?php
/**
 *	pluginbuddy_zipbuddy Class (Experimental)
 *
 *	Handles zipping and unzipping, using the best methods available and falling back to worse methods
 *	as needed for compatibility. Allows for forcing compatibility modes.
 *	
 *	Version: 1.0.0
 *	Author: 
 *	Author URI: 
 *
 *
 */

// Test if we are loading as standard or experimental - if experimental just drop through
if ( 0 === strcmp( basename( dirname( __FILE__ ) ), 'zipbuddy' ) ) {

	// Currently loading as standard so determine if we need to load experimental
	if ( isset( pb_backupbuddy::$options['alternative_zip_2'] ) && ( '1' == pb_backupbuddy::$options['alternative_zip_2'] ) ) {
	
		// User enabled experimental so look for it and load it is found, otherwise log
		$experimental_zipbuddy = dirname( dirname( __FILE__ ) ) . '/xzipbuddy/zipbuddy.php';
		if ( @is_readable( $experimental_zipbuddy ) ) {
		
			require_once( $experimental_zipbuddy );
			
		} else {
		
			pb_backupbuddy::status( 'details', sprintf( __('Alternate Zip System enabled but not found/readable at: %1$s','it-l10n-backupbuddy' ), $experimental_zipbuddy ) );

		
		}
	
	}

} 

if ( !class_exists( "pluginbuddy_zipbuddy" ) ) {

	// Currently just a wrapper for pb_backupbuddy::status()
	// TODO: Would prefer to have a generic logger and this would
	// extend it if required (we may not even need this dependent
	// on how logging evolves)
	class pluginbuddy_zipbuddy_logger {
		
		protected $_prefix = '';
		protected $_suffix = '';
		
		public function __construct( $prefix = "", $suffix = "" ) {
			
			if ( !empty( $prefix ) ) {

				$this->set_prefix( $prefix );

			}
			
			if ( !empty( $suffix ) ) {

				$this->set_suffix( $suffix );

			}
			
		}
		
		public function __destruct() {
			
		}
		
		public function set_prefix( $prefix = "" ) {
			
			$this->_prefix = $prefix;
			
			return $this;
			
		}
		
		public function get_prefix() {
			
			return $this->_prefix;

		}
		
		public function set_suffix( $suffix = "" ) {
			
			$this->_suffix = $suffix;
			
			return $this;
			
		}
		
		public function get_suffix() {
			
			return $this->_suffix;

		}
		
		public function log( $level, $message, $prefix = null, $suffix = null ) {
			
			$prefix_to_use = ( is_null( $prefix ) ) ? $this->_prefix : ( ( is_string( $prefix ) ) ? $prefix : "" ) ;
			$suffix_to_use = ( is_null( $suffix ) ) ? $this->_suffix : ( ( is_string( $suffix ) ) ? $suffix : "" ) ;
			
			pb_backupbuddy::status( $level, $prefix_to_use . $message . $suffix_to_use );
			
			return $this;
			
		}
		
	}

	class pluginbuddy_zipbuddy_null_object {
		
		public function __construct() {
			
		}
		
		public function __destruct() {
			
		}
		
		public function __call( $method, $arguments ) {
			
		}
		
	}
		
	/**
	 *	pluginbuddy_zipbuddy_process_monitor Class
	 *
	 *	Class that is used monitor the progress of any process and take actions to try and
	 *	ensure the process execution is prolonged as much as possible.
	 *
	 */
	 class pluginbuddy_zipbuddy_process_monitor {
	 
		const ZIP_DEFAULT_EXECUTION_MAX_PERIOD = 30;
		const ZIP_DEFAULT_EXECUTION_THRESHOLD_PERIOD = 10;

		const ZIP_DEFAULT_MONITOR_THRESHOLD_PERIOD = 10;

		const ZIP_DEFAULT_TICKLE_THRESHOLD_PERIOD = 10;
	
		protected $_execution_threshold_period = 0;
		protected $_execution_start_time = 0;
		protected $_execution_max_period = 0;
		
		protected $_monitor_threshold_period = 0;
		protected $_monitor_start_time = 0;
		protected $_monitoring_usage = false;
		protected $_start_user_time = 0;
		protected $_elapsed_user_time = 0;
		
		protected $_tickle_threshold_period = 0;
		protected $_tickle_start_time = 0;
		protected $_server_tickling = false;
		protected $_server_tickler = '';
		
		protected $_creation_time = 0;
		
		protected $_report_connection_status = false;

         /**
         * The logger we will use
         * 
         * @var logger	object
         */
		protected $_logger = null;
	
		public function __construct( &$parent = null ) {
		
			// Inherit the parent logger by default (if it has one), caller may override after instantiated
			if ( $parent && method_exists( $parent, 'get_logger' ) ) {
			
				$this->set_logger( $parent->get_logger() );
				
			}
		
			$now = time();
			
			$this->set_creation_time( $now );
			
			// We can try and derive the configured execution_max_period
			// and the execution_threshold_period is set based on that. If
			// this is overridden by a given period then we can also choose
			// whether to set the threshold period based on that or set a
			// specific threshold period (this is up to the caller).
			$this->set_execution_start_time( $now );
			$this->set_execution_max_period( 'auto' );
			
			// The monitor threshold period cannot be derived
			$this->set_monitor_start_time( $now );
			$this->set_monitor_threshold_period( self::ZIP_DEFAULT_MONITOR_THRESHOLD_PERIOD );

			// The tickle threshold period cannot be derived
			$this->set_tickle_start_time( $now );
			$this->set_tickle_threshold_period( self::ZIP_DEFAULT_TICKLE_THRESHOLD_PERIOD );

			$this->initialize_monitoring_usage();
			
			$this->set_server_tickling( true );
			$this->_server_tickler = '<!--' . str_shuffle( substr( str_repeat( implode( '', range( 'a', 'z' ) ), 40 ), 0, 1024 ) ) . '-->' . chr(13) . chr(10);

			$this->log_parameters();

		}
		
		public function __destruct() {
		
		}
		
		/**
		 * 
		 *	checkpoint()
		 *
		 *	Check how we are doing and whether we need to take any steps to prolong
		 *	execution at this time. If any of these report anythng then connection
		 *	status is also reported.
		 *
		 *	@return		object			Return reference to this object
		 *
		 */
		public function checkpoint() {
		
			$this->monitor_usage();
			$this->monitor_execution_time();
			$this->tickle_server();
			
			return $this;
		
		}
		
		public function get_creation_time() {
		
			return $this->_creation_time;
		
		}
	
		public function set_creation_time( $time = 0 ) {
		
			$this->_creation_time = ( 0 === $time ) ? time() : $time ;
			return $this;
		
		}
		
		public function get_elapsed_time() {
			
			return ( time() - $this->get_creation_time() );
			
		}
	
		// Methods for handling the execution time management

		public function get_execution_threshold_period() {
		
			return $this->_execution_threshold_period;
		
		}
	
		public function set_execution_threshold_period( $period = self::ZIP_DEFAULT_EXECUTION_THRESHOLD_PERIOD ) {
		
			$execution_max_period = 0;
		
			if ( true === is_string( $period ) ) {
			
				switch ( $period ) {
				
					case 'auto':
						// If auto then set based on execution max period
						if ( 0 === ( $execution_max_period = $this->get_execution_max_period() ) ) {
						
							// Not set yet so we need to set it with auto
							// Ensure we don't get into a recursive loop...
							$execution_max_period = $this->set_execution_max_period( 'auto', false )->get_execution_max_period();
						
						}
					
						// Bit of an arbitrary proportion...
						$this->_execution_threshold_period = (int)( $execution_max_period / 3 );
						break;
						
					default:
						// Unknown mode so use default value
						$this->_execution_threshold_period = self::ZIP_DEFAULT_EXECUTION_THRESHOLD_PERIOD;
				
				}
			
			} elseif ( is_numeric( $period ) && ( 0 < $period ) ) {
			
				$this->_execution_threshold_period = $period;
			
			} else {
				
				$this->_execution_threshold_period = self::ZIP_DEFAULT_EXECUTION_THRESHOLD_PERIOD;
				
			}

			return $this;

		}
	
		public function get_execution_start_time() {
		
			return $this->_execution_start_time;
		
		}
	
		public function set_execution_start_time( $time = 0 ) {
		
			( 0 === $time ) ? $this->_execution_start_time = time() : $this->_execution_start_time = $time ;
			return $this;
		
		}
	
		public function get_execution_max_period() {
		
			return $this->_execution_max_period;
		
		}
	
		/**
		 * 
		 *	set_execution_max_period()
		 *
		 *	Set, or try to derive, what the maximum execution period should be.
		 *	Possibly also set the threshold if max was derived.
		 *	For deriving try to get configured values to use, otherwise use the
		 *	default.
		 *	The 0 value needs to be specially handled since it implies unlimited
		 *	and so we map this to the PHP maximum integer value. In practice,
		 *	unlimited doesn't really mean much since hosting will have other
		 *	timeouts that kick in - but we need to honour whatever the user
		 *	has configured.
		 *
		 *	@param		string|int		$period				Integer for specific value, 'auto' for derived
		 *	@param		bool			$auto_set_threshold	Whether to auto set threshold period or not		
		 *	@return		object								This object instance	
		 *
		 */
		public function set_execution_max_period( $period = self::ZIP_DEFAULT_EXECUTION_MAX_PERIOD, $auto_set_threshold = true ) {
		
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
								$this->_execution_max_period = self::ZIP_DEFAULT_EXECUTION_MAX_PERIOD;
							
							} else {
						
								// Got a configured value so use it
								$this->_execution_max_period = (int) $configured_execution_time;
						
							}
							
						} else {
						
							// Got a non-zero current execution time so use it
							$this->_execution_max_period = (int) $current_execution_time;
						
						}
						
						// If set by auto then make sure we (re)set threshold as auto _unless_
						// told not to...
						if ( true === $auto_set_threshold ) {
						
							$this->set_execution_threshold_period( 'auto' );
							
						}
						break;
					
					default:
						// Unknown mode so use default value
						$this->_execution_max_period = self::ZIP_DEFAULT_EXECUTION_MAX_PERIOD;
				
				}
			
			} elseif ( is_numeric( $period ) && ( 0 < $period ) ) {
			
				$this->_execution_max_period = (int)$period;
			
			} else {
				
				$this->_execution_max_period = self::ZIP_DEFAULT_EXECUTION_MAX_PERIOD;
				
			}
		
			return $this;
		
		}
		
		// Methods for handling the monitoring action
	
		public function get_monitor_threshold_period() {
		
			return $this->_monitor_threshold_period;
		
		}
	
		public function set_monitor_threshold_period( $period = self::ZIP_DEFAULT_MONITOR_THRESHOLD_PERIOD ) {
		
			$this->_monitor_threshold_period = ( is_numeric( $period ) && ( 0 < $period ) ) ? $period : self::ZIP_DEFAULT_MONITOR_THRESHOLD_PERIOD ;
			return $this;
		
		}
	
		public function get_monitor_start_time() {
		
			return $this->_monitor_start_time;
		
		}
	
		public function set_monitor_start_time( $time = 0 ) {
		
			$this->_monitor_start_time = ( 0 === $time ) ? time() : $time ;
			return $this;
		
		}
		
		public function initialize_monitoring_usage( $reset = true ) {
		
			$usage_data = array();
		
			// Must determine if we can monitor usage data
			if ( function_exists( 'getrusage' ) && is_callable( 'getrusage' ) ) {
				
				$this->_monitoring_usage = true;
		
				// We need to know the user time value at the start so we can monitor how
				// much actual user time we are using (which counts against max_execution_time)
				// We call this when object is created to maek sure the init is done but we can
				// make a later call closer to when we wnt to start monitoring it and that will
				// reset the start time.
				if ( ( 0 === $this->_start_user_time ) || ( true === $reset ) ) {
			
					$usage_data = getrusage();
					$this->_start_user_time = $usage_data[ 'ru_utime.tv_sec' ];
			
				}
			
			}
			
		}
		
		public function is_monitoring_usage() {
		
			return ( true === $this->_monitoring_usage );
		
		}
		
		// Methods for handling the server tickling action
		
		public function get_tickle_threshold_period() {
		
			return $this->_tickle_threshold_period;
		
		}
	
		public function set_tickle_threshold_period( $period = self::ZIP_DEFAULT_TICKLE_THRESHOLD_PERIOD ) {
		
			$this->_tickle_threshold_period = ( is_numeric( $period ) && ( 0 < $period ) ) ? $period : self::ZIP_DEFAULT_TICKLE_THRESHOLD_PERIOD ;
			return $this;
		
		}
	
		public function get_tickle_start_time() {
		
			return $this->_tickle_start_time;
		
		}
	
		public function set_tickle_start_time( $time = 0 ) {
		
			$this->_tickle_start_time = ( 0 === $time ) ? time() : $time ;
			return $this;
		
		}
	
		public function set_server_tickling( $tickle = true ) {
		
			$this->_server_tickling = $tickle;
			return $this;
		
		}
		
		public function get_server_tickling() {
		
			return $this->_server_tickling;
		
		}
		
		public function is_server_tickling() {
		
			return ( true === $this->_server_tickling );
		
		}
		
		public function get_server_tickler() {
		
			return $this->_server_tickler;
		
		}
		
		public function connection_status_tostring( $status ) {
		
			$status_name = '';
			
			switch ( $status ) {
			
				case CONNECTION_NORMAL:
					$status_name = 'Normal';
					break;
				case CONNECTION_ABORTED:
					$status_name = 'Aborted';
					break;
				case CONNECTION_TIMEOUT:
					$status_name = 'Timeout';
					break;
				default:
					$status_name = 'Unknown';
			
			}
			
			return $status_name;
		
		}
		
		protected function monitor_usage() {
		
			$usage_data = array();
			$current_monitor_period = 0;
			
			// Would expect to monitor except on Windows which doesn't support getrusage()
			if ( true === $this->is_monitoring_usage() ) {
			
				// Decide if we need to log usage data
				$current_monitor_period = ( time() - $this->get_monitor_start_time() );
				if ( $this->get_monitor_threshold_period() < $current_monitor_period ) {
			
					// Get some usage data from the server (check that function available) and log it
					$usage_data = getrusage();
				
					// Determine the total user space time since we initialized the monitoring (relative)
					$this->_elapsed_user_time = ( $usage_data[ 'ru_utime.tv_sec' ] - $this->_start_user_time );
					$this->get_logger()->log( 'details', sprintf( __('Zip process reported: Usage data (raw/relative): ( %1$s, %2$s, %3$s, %4$s, %5$s )/( -, %6$s, -, -, - )','it-l10n-backupbuddy' ), $usage_data[ 'ru_stime.tv_sec' ], $usage_data[ 'ru_utime.tv_sec' ], $usage_data[ 'ru_majflt' ], $usage_data[ 'ru_nvcsw' ], $usage_data[ 'ru_nivcsw' ], $this->_elapsed_user_time ) );

					// Reset the monitoring period start time
					$this->set_monitor_start_time( time() );
					
					// We reported something so report the connection status as well
					$this->_report_connection_status = true;

				}
			
			}
			
			return $this;
			
		}

		protected function monitor_execution_time() {
						
			$current_execution_period = 0;
			
			// Decide if we have been running long enough to need to reset time limit
			$current_execution_period = ( time() - $this->get_execution_start_time() );
			if ( $this->get_execution_threshold_period() < $current_execution_period ) {
		
				// Log how long we ran for and then reset the start time and max period
				$this->get_logger()->log( 'details', sprintf( __('Zip process reported: %1$s seconds elapsed - resetting timebase to %2$s seconds','it-l10n-backupbuddy' ), $current_execution_period, $this->get_execution_max_period() ) );

				// Reset the execution period start time
				$this->set_execution_start_time( time() );
			
				// Reset the execution time timer (if the server honours this)
				// Belt and braces in case some server disables set_time_limit()
				// for some reason, hope that init_set() works - if neither works
				// there isn't much we can do about it - user will just have to
				// use a step period that matches the configured max_execution_time.
				@set_time_limit( $this->get_execution_max_period() );
				@ini_set( 'max_execution_time', $this->get_execution_max_period );
			
				// We reported something so report the connection status as well
				$this->_report_connection_status = true;

			}
			
			return $this;
		
		}
		
		protected function tickle_server() {
		
			$current_tickle_period = 0;
		
			// Only bother with server tickling if it is selected
			if ( true === $this->is_server_tickling() ) {
			
				// Decide if we have been running long enough to need to tickle the server
				$current_tickle_period = ( time() - $this->get_tickle_start_time() );
				if ( $this->get_tickle_threshold_period() < $current_tickle_period ) {
			
					// Log how long since we last tickled and indicate tickling
					$this->get_logger()->log( 'details', sprintf( __('Zip process reported: %1$s seconds elapsed - tickling server','it-l10n-backupbuddy' ), $current_tickle_period ) );
					$this->set_tickle_start_time( time() );
				
					// Output the tickler to give something to flush
					echo $this->get_server_tickler();
				
					// Force flushing and end of buffering
					// Possibly should need to do this because nothing should have started buffering
					// should it? It's possible that PHP config could have enabled one level of buffering
					// so at least handle that with the method as exemplified in the PHP manual. Also do
					// a staright flush as that should cause a flush at least to the server which is
					// actually all we want in this particular case.
					while ( @ob_end_flush() );
					flush();
					
					// We reported something so report the connection status as well
					$this->_report_connection_status = true;

				}
			
			}
			
			return $this;
		
		}
		
		protected function report_connection_status() {
		
			if ( true === $this->_report_connection_status ) {
			
				// Log where we are at and connection status for information
				$this->get_logger()->log( 'details', sprintf( __('Zip process reported: Connection status: %1$s (%2$s)','it-l10n-backupbuddy' ), $this->connection_status_tostring( connection_status() ), connection_status() ) );
				$this->_report_connection_status = false;
						
			}
			
			return $this;
		
		}

		// Log our setup parameters
		public function log_parameters() {
		
			$this->get_logger()->log( 'details', sprintf( __('Zip process reported: Execution max/threshold periods: %1$ss/%2$ss','it-l10n-backupbuddy' ), $this->get_execution_max_period(), $this->get_execution_threshold_period() ) );
			$this->get_logger()->log( 'details', sprintf( __('Zip process reported: Monitor threshold period: %1$ss','it-l10n-backupbuddy' ), $this->get_monitor_threshold_period() ) );
			$this->get_logger()->log( 'details', sprintf( __('Zip process reported: Tickle threshold period: %1$ss','it-l10n-backupbuddy' ), $this->get_tickle_threshold_period() ) );

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
		
	}
	
	class pluginbuddy_zipbuddy {
	
		const ZIP_METHODS_TRANSIENT = 'pb_backupbuddy_avail_zip_methods';
		const ZIP_METHODS_TRANSIENT_EXPERIMENTAL = 'pb_backupbuddy_avail_xzip_methods';
		const ZIP_METHODS_TRANSIENT_LIFE = 43200; // 12 Hours - really shouldn't change unless server problem
		const NORM_DIRECTORY_SEPARATOR = '/';
		const DIRECTORY_SEPARATORS = '/\\';
		
		const STATE_NAME_BEGIN = 'begin';
		const STATE_ID_BEGIN = 1;
		const STATE_NAME_IN_PROGRESS = 'in_progress';
		const STATE_ID_IN_PROGRESS = 2;
		const STATE_NAME_END = 'end';
		const STATE_ID_END = 3;
		
		const ZIP_DEFAULT_IGNORE_WARNINGS = false;
		const ZIP_DEFAULT_IGNORE_SYMLINKS = true;
		const ZIP_DEFAULT_COMPRESSION = true;
		const ZIP_DEFAULT_STEP_PERIOD = 30;
		const ZIP_DEFAULT_BURST_GAP = 2;
		const ZIP_DEFAULT_MIN_BURST_CONTENT = 10485760; // 10MB
		const ZIP_DEFAULT_MAX_BURST_CONTENT = 104857600; // 100MB
		const ZIP_DEFAULT_BURST_THRESHOLD_PERIOD = 30;

        /**
         * The plugin path for this plugin
         * 
         * @var string
         */
        public $_pluginPath = '';

        /**
         * The path of the temporary directory that can be used for creating files and stuff
         * 
         * @var string
         */
        protected $_tempdir = "";
        
        /**
         * The list of zip methods that are requested to be used
         * 
         * @var array of string
         */
        protected $_requested_zip_methods = array();

        /**
         * Status message array used when calling other methods to get status information back
         * 
         * @var array of string
         */
        public $_status = array();

        /**
         * The list of zip methods that are to be used or are available
         * Had to make this public for now because something accesses it directly - bad karma
         * 
         * @var array of string
         */
        public $_zip_methods = array();
        
        /**
         * The details of the various zip methods that are available
         * Have to make this a separate array indexed by the method tag. Ideally would be combined
         * with the zip methods array but that would involve more general changes elsewhere so that
         * refactoring can be done later - main problem is the direct access to the zip methods
         * array that is made rather than through a function.
         * 
         * @var array of array of array
         */
        protected $_zip_methods_details = array();
        
        /**
         * The list of zip methods that are supported, i.e., there is a supporting class defined
         * 
         * @var array of string
         */
        protected $_supported_zip_methods = array();
        
        /**
         * Whether or not we can call a status calback
         * 
         * @var bool
         */
		protected $_have_status_callback = false;
		
        /**
         * Object->method array for status function
         * 
         * @var array
         */
		protected $_status_callback = array();
		
        /**
         * The directory name that we are loaded from (not: not path)
         * 
         * @var string
         */
		protected $_whereami = "";
		
        /**
         * Whether we are loaded as the experimental zipbuddy
         * 
         * @var bool
         */
		protected $_is_experimental = false;
		
        /**
         * The name of the zip methods transient will be dependent on if we are standard or experimental
         * 
         * @var string
         */
		protected $_zip_methods_transient = "";

        /**
         * The Server API that is in use
         * 
         * @var string
         */
		protected $_sapi_name = "";

        /**
         * Convenience boolean indicating if Warnings should be ignored when building archives
         * 
         * @var ignore_warnings	bool
         */
		protected $_ignore_warnings = null;
		
        /**
         * Convenience boolean indicating if symlinks should be ignored/not-followed when building archives
         * 
         * @var ignore_symlinks	bool
         */
		protected $_ignore_symlinks = null;
		
         /**
         * Convenience boolean indicating if compression shoul dbe used when building archives
         * 
         * @var compression	bool
         */
		protected $_compression = null;
		
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
         * The logger to use
         * 
         * @var logger	object
         */
		protected $_logger = null;

         /**
         * The default logger to give to any children
         * 
         * @var default_child_logger	object
         */
		protected $_default_child_logger = null;

         /**
         * The process monitor to use
         * 
         * @var process_monitor	object
         */
		protected $_process_monitor = null;

		/**
		 * 
		 * get_transient_names_static()
		 *
		 * Get the transient name(s) that may be in use
		 *
		 * @return		array	The transient name(s)
		 *
		 */
		public static function get_transient_names_static() {
		
			return array( self::ZIP_METHODS_TRANSIENT,
						  self::ZIP_METHODS_TRANSIENT_EXPERIMENTAL );
			
		}

		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	
		 *	@param		string		$temp_dir		The path of the temporary directory to use
		 *	@param		array		$zip_methods	Optional: The set of zip methods requested to use
		 *	@return		null
		 *
		 */
		public function __construct( $temp_dir, $zip_methods = array() ) {

			// Zipbuddy creates it's own default logger but can be overridden
			// Note: if user wants to override they must "get" the current logger
			// and destroy it otherwise it gets orphaned.
			$this->set_logger( new pluginbuddy_zipbuddy_logger() );
			
			// Use this for all our children unless they need a specific logger
			$this->_default_child_logger = new pluginbuddy_zipbuddy_logger();
						
			// Zipbuddy creates it's own default process monitor but can be overridden
			// Note: if user wants to override they must "get" the current process
			// monitor and destroy it otherwise it gets orphaned. We'll give it our
			// logger
			$this->set_process_monitor( new pluginbuddy_zipbuddy_process_monitor( $this ) );
			
			// Normalize the trailing directory separator on the path
			$temp_dir = rtrim( $temp_dir, self::DIRECTORY_SEPARATORS ) . self::NORM_DIRECTORY_SEPARATOR;
			
			// Normalize platform specific directory separators in path
			$this->_tempdir = str_replace( DIRECTORY_SEPARATOR, self::NORM_DIRECTORY_SEPARATOR, $temp_dir );
			
			// Record where we are located (the directory name)
			$this->_whereami = basename( dirname( __FILE__ ) );
			
			// Set a flag for easy conditional testing
			$this->_is_experimental = ( 0 === strcmp( $this->_whereami, 'zipbuddy' ) ) ? false : true ;

			// Use our experimental flag to determine which zip methods transient we should be using
			$this->_zip_methods_transient = ( $this->_is_experimental ) ? self::ZIP_METHODS_TRANSIENT_EXPERIMENTAL : self::ZIP_METHODS_TRANSIENT ;

			// Set the sapi name so we can use it later			
			$this->set_sapi_name();
			
			// Derive whether we are ignoring Warnings or not (can be overridden by method call)
			$this->set_ignore_warnings();
			
			// Derive whether we are ignoring/not-following symlinks or not (can be overridden by method call)
			$this->set_ignore_symlinks();
			
			// Derive whether compression should be used (can be overridden by method call)
			$this->set_compression();
			
			// Derive what the step period should be for any action after which a new step must be initiated
			$this->set_step_period();
						
			// Derive what the interburst gap should be for any burst related action
			$this->set_burst_gap();
			
			// Derive what the min burst content size should be for any burst related action
			$this->set_min_burst_content();
			
			// Derive what the max burst content size should be for any burst related action
			$this->set_max_burst_content();
			
			// Make sure we load the core abstract class as this will always be needed
			require_once( pb_backupbuddy::plugin_path() . '/lib/' . $this->_whereami . '/zbzipcore.php' );
			
			// If we loaded that ok then try the method specific classes
			// Could make this more generic based on config or somesuch
			if ( class_exists( 'pluginbuddy_zbzipcore' ) ) {
// 			
// 				// Only provide proc mode when experimental zip enabled
// 				if ( true === $this->_is_experimental ) {
// 				
// 					include_once( pb_backupbuddy::plugin_path() . '/lib/' . $this->_whereami . '/zbzipproc.php' );
// 					
// 					if ( class_exists( 'pluginbuddy_zbzipproc' ) ) {
// 					
// 						if ( $this->check_method_dependencies( 'pluginbuddy_zbzipproc' ) ) {
// 						
// 							$this->set_supported_zip_methods( pluginbuddy_zbzipproc::get_method_tag_static() );
// 							
// 						}
// 						
// 					}
// 				
// 				}
// 				
				include_once( pb_backupbuddy::plugin_path() . '/lib/' . $this->_whereami . '/zbzipexec.php' );
				if ( class_exists( 'pluginbuddy_zbzipexec' ) ) {
				
					if ( $this->check_method_dependencies( 'pluginbuddy_zbzipexec' ) ) {
					
						$this->set_supported_zip_methods( pluginbuddy_zbzipexec::get_method_tag_static() );
						
					}

				}
				
				include_once( pb_backupbuddy::plugin_path() . '/lib/' . $this->_whereami . '/zbzipziparchive.php' );
				if ( class_exists( 'pluginbuddy_zbzipziparchive' ) ) {
				
					if ( $this->check_method_dependencies( 'pluginbuddy_zbzipziparchive' ) ) {
					
						$this->set_supported_zip_methods( pluginbuddy_zbzipziparchive::get_method_tag_static() );
						
					}

				}
				
				include_once( pb_backupbuddy::plugin_path() . '/lib/' . $this->_whereami . '/zbzippclzip.php' );
				if ( class_exists( 'pluginbuddy_zbzippclzip' ) ) {
				
					if ( $this->check_method_dependencies( 'pluginbuddy_zbzippclzip' ) ) {
					
						$this->set_supported_zip_methods( pluginbuddy_zbzippclzip::get_method_tag_static() );
						
					}

				}
				
			}
			
 			// Work out the list of zip methods from the requested and available along with their details
			$this->set_zip_methods( $zip_methods );
			
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
			
			// TODO: Perhaps we need to check if the logger is our original one
			// that we created and if so then we should destroy it as we own it,
			// otherwise someone else owns it and we shouldn't destroy it. Same
			// goes for process monitor.
			
			unset( $this->_default_child_logger );

		}
		
		/**
		 *	set_sapi_name()
		 *
		 *	Sets the sapi name to that given or retrieves from PHP
		 *	TODO: Extend to also set a sapi id constant based on the name?
		 *
		 *	@param	string	$name	A sapi name to set (default empty)
		 *	@return	object			This object
		 */
		public function set_sapi_name( $sapi_name = "" ) {
		
			if ( empty( $sapi_name ) ) {
				
				$sapi_name = php_sapi_name();
				
			}
			
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
		 *	derive_optional_bool()
		 *
		 *	Utility function to derive the value of an optional boolean flag based on either
		 *	a specifc value being given or the related global option being set or a given
		 *	defautl value otherise. If the provided $value is null then this forces the use
		 *	of the global option if it is set or otherwise the default value given
		 *
		 *	@param		string		$option		The option name in the global options array
		 *	@param		mixed		$value		Should be bool true|false but could be null
		 * 	@param		bool		$default	The default value to use if no other provided/available
		 *	@return		bool					Derived boolean value
		 *
		 */
		 protected function derive_optional_bool( $option, $value, $default, $empty_option_default = false ) {
		 	$result = false;
		 	if ( is_bool( $value )) {
		 		$result = $value;
		 	} elseif ( isset( pb_backupbuddy::$options[ $option ] ) ) {
		 		$result = ( ( pb_backupbuddy::$options[ $option ] == '1' ) || ( pb_backupbuddy::$options[ $option ] == true ) ) ? true : $empty_option_default ;
		 	} else {
		 		$result = $default;
		 	}
		 	return $result;
		 }
		 
		/**
		 *	derive_optional_int()
		 *
		 *	Utility function to derive the value of an optional integer based on either
		 *	a specifc value being given or the related global option being set or a given
		 *	default value otherise.
		 *	If the provided $value is null then this forces the use of the global option if
		 *	it is set (adjusted by any factor given) or otherwise the default value given.
		 *	If the global option is set but is not numeric (generally left empty) then we
		 *	use the provided $empty_option_value (which is _not_ adjusted by the factor but
		 *	assumed to be valid as it is - for example if the option is given in MB but
		 *	the required value to use is in bytes then we would adjust by multiplying by
		 *	1024*1024 but the empty option value is assumed to be provided in bytes and so
		 *	is not adjusted.
		 *	In the case of using the adjusted option value or the empty option value we also
		 *	apply any value may given so hat, for example, an option value of 0 can be
		 *	mapped to mean "infinite".
		 *	Note: integer type options may be stored as strings so that we can use an empty
		 *	string to signal we want the default value to be used - so we need to check the
		 *	option value as being numeric rather than integer as this will return true for
		 *	any string that is a numerical value as well as the option being an integer value
		 *	in any case but crucually will return false for an empry string as this does not
		 *	represent any numerical value and hence that triggers the use of the empty option
		 *	default value.
		 *	We coerce any numeric string into an integer for assignment.
		 *
		 *	@param		string		$option					The option name in the global options array
		 *	@param		mixed		$value					Should be integer but could be null
		 * 	@param		int			$default				The deafult value to use if no other provided/available
		 *	@param		array		$option_value_map		Any special treatment for option values
		 *	@param		int			$option_value_factor	Adjustment factor if option given in units
		 *	@param		int			$empty_option_default	Default value to use if option is set but empty
		 *	@return		int									Derived integer value
		 *
		 */
		 protected function derive_optional_int( $option, $value, $default, $option_value_map = array(), $option_value_factor = 1, $empty_option_default = PHP_INT_MAX ) {
		 	$result = PHP_INT_MAX;
		 	if ( is_int( $value )) {
		 		$result = $value;
		 	} elseif ( isset( pb_backupbuddy::$options[ $option ] ) ) {
		 		$result = ( is_numeric( pb_backupbuddy::$options[ $option ] ) ) ? (int)( pb_backupbuddy::$options[ $option ] * $option_value_factor ) : $empty_option_default ;
		 		$result = ( array_key_exists( $result, $option_value_map ) ) ? $option_value_map[ $result ] : $result ;
		 	} else {
		 		$result = $default;
		 	}
		 	return $result;
		 }
		 
		/**
		 *	derive_optional_double()
		 *
		 *	Utility function to derive the value of an optional double based on either
		 *	a specifc value being given or the related global option being set or a given
		 *	defautl value otherise. If the provided $value is null then this forces the use
		 *	of the global option if it is set or otherwise the default value given.
		 *	Note: double type options may be stored as strings so that we can use an empty
		 *	string to signal we want the default value to be used - so we need to check the
		 *	option value as being numeric rather than double as this will return true for
		 *	any string that is a numerical value as well as the option being an double value
		 *	in any case but crucually will return false for an emprt string as this does not
		 *	represent any numerical value and hence that triggers the use of the default value.
		 *	We coerce any numeric string into an double for assignment.
		 *
		 *	@param		string		$option					The option name in the global options array
		 *	@param		mixed		$value					Should be double but could be null
		 * 	@param		double		$default				The deafult value to use if no other provided/available
		 *	@param		array		$option_value_map		Any special treatment for option values
		 *	@param		int			$option_value_factor	Adjustment factor if option given in units
		 *	@param		double		$empty_option_default	Default value to use if option is set but empty
		 *	@return		double								Derived double value
		 *
		 */
		 protected function derive_optional_double( $option, $value, $default, $option_value_map = array(), $option_value_factor = 1, $empty_option_default = null ) {
			// Example of a "large" value on either a 32 or 64 bit system - we cannot set this
			// as the $empty_option_value default value in the function sig so post-process to
			// set it if indicated.
		 	$large_value = ( 4 == PHP_INT_SIZE ) ? (double)( pow(2, 63) - 1 ) : (double)PHP_INT_MAX ;
		 	$empty_option_default = ( is_null( $empty_option_default ) ) ? $large_value : $empty_option_default ;
		 	// Need to convert $value to a double only if it is numeric
		 	$value = ( is_numeric( $value ) ) ? (double)$value : $value ;
		 	if ( is_double( $value )) {
		 		$result = $value;
		 	} elseif ( isset( pb_backupbuddy::$options[ $option ] ) ) {
		 		$result = ( is_numeric( pb_backupbuddy::$options[ $option ] ) ) ? (double)( pb_backupbuddy::$options[ $option ] * $option_value_factor ) : (double)$empty_option_default ;
		 		
		 		// Array keys can only be string/int - for now we'll assume that we only map
		 		// values that can be cast as int - we may have to consider a different approach
		 		// later.
		 		$result = ( array_key_exists( (int)$result, $option_value_map ) ) ? $option_value_map[ (int)$result ] : $result ;
		 		
		 		// Special treatment - if the value was mapped to null then we want the derived "large" value
		 		$result = ( is_null( $result ) ) ? $large_value : $result ;
		 	} else {
		 		$result = (double)$default;
		 	}
		 	return $result;
		 }
		 
		/**
		 *	set_ignore_warnings()
		 *
		 *	@param	mixed	$ignore		true|false for specific setting or null for choice	
		 *	@return	object				This object
		 */
		public function set_ignore_warnings( $ignore = null ) {
		
		 	$this->_ignore_warnings = $this->derive_optional_bool( 'ignore_zip_warnings', $ignore, self::ZIP_DEFAULT_IGNORE_WARNINGS );
		 	
			return $this;
			
		}

		/**
		 *	get_ignore_warnings()
		 *
		 *	Returns the previously set ignore warnings flag
		 *
		 *	@return	mixed			The stored ignore warnings flag true|false
		 */
		public function get_ignore_warnings() {
			
			return $this->_ignore_warnings;
			
		}

		/**
		 *	set_ignore_symlinks()
		 *
		 *	@param	mixed	$ignore		true|false for specific setting or null for choice	
		 *	@return	object				This object
		 */
		public function set_ignore_symlinks( $ignore = null ) {
		
		 	$this->_ignore_symlinks = $this->derive_optional_bool( 'ignore_zip_symlinks', $ignore, self::ZIP_DEFAULT_IGNORE_SYMLINKS );
			
			return $this;
			
		}

		/**
		 *	get_ignore_symlinks()
		 *
		 *	Returns the previously set ignore symlinks flag
		 *
		 *	@return	mixed			The stored ignore symlinks flag true|false
		 */
		public function get_ignore_symlinks() {
			
			return $this->_ignore_symlinks;
			
		}

		/**
		 *	set_compression()
		 *
		 *	@param	mixed	$compression	true|false for specific setting or null for choice	
		 *	@return	object					This object
		 */
		public function set_compression( $compression = null ) {
		
		 	$this->_compression = $this->derive_optional_bool( 'compression', $compression, self::ZIP_DEFAULT_COMPRESSION );
			
			return $this;
			
		}

		/**
		 *	get_compression()
		 *
		 *	Returns the previously set compression flag
		 *
		 *	@return	mixed			The stored compression flag true|false
		 */
		public function get_compression() {
			
			return $this->_compression;
			
		}

		/**
		 *	set_step_period()
		 *
		 *	If the option is empty (blank) => default (30s)
		 *	If the option is 0 => infinite (PHP_MAX_INT)
		 *
		 *	@param	mixed	$set_period		integer for specific value or null for choice	
		 *	@return	object					This object
		 */
		public function set_step_period( $step_period = null ) {
		
			$step_period_option_value_map = array( 0 => PHP_INT_MAX );
		
		 	$this->_step_period = $this->derive_optional_int( 'zip_step_period', $step_period, self::ZIP_DEFAULT_STEP_PERIOD, $step_period_option_value_map, 1, self::ZIP_DEFAULT_STEP_PERIOD );
			
			return $this;
			
		}

		/**
		 *	get_step_period()
		 *
		 *	Returns the previously set step period
		 *
		 *	@return	int			The stored step period int
		 */
		public function get_step_period() {
			
			return $this->_step_period;
			
		}

		/**
		 *	set_burst_gap()
		 *
		 *	If the option is empty (blank) => default (2s)
		 *
		 *	@param	mixed	$burst_gap		integer for specific value or null for choice	
		 *	@return	object					This object
		 */
		public function set_burst_gap( $burst_gap = null ) {
		
		 	$this->_burst_gap = $this->derive_optional_int( 'zip_burst_gap', $burst_gap, self::ZIP_DEFAULT_BURST_GAP, array(), 1, self::ZIP_DEFAULT_BURST_GAP );
			
			return $this;
			
		}

		/**
		 *	get_burst_gap()
		 *
		 *	Returns the previously set burst gap
		 *
		 *	@return	int			The stored burst gap int
		 */
		public function get_burst_gap() {
			
			return $this->_burst_gap;
			
		}

		/**
		 *	set_min_burst_content()
		 * 
		 *	We want this to be a double so it can have a larger value that we would
		 *	ever need to be used to represent no minimum
		 *
		 *	If the option is empty (blank) => default (10MB)
		 *	If the option is 0 => large value
		 *
		 *	@param	mixed	$min_burst_content		double for specific value or null for choice	
		 *	@return	object							This object
		 */
		public function set_min_burst_content( $min_burst_content = null ) {
		
			// null is a special value that is further mapped to a "large" value for the
			// particular architecture
			$min_burst_content_option_value_map = array( 0 => null );
			
		 	$this->_min_burst_content = $this->derive_optional_double( 'zip_min_burst_content', $min_burst_content, self::ZIP_DEFAULT_MIN_BURST_CONTENT, $min_burst_content_option_value_map, (1024*1024), self::ZIP_DEFAULT_MIN_BURST_CONTENT );
			
			return $this;
			
		}

		/**
		 *	get_min_burst_content()
		 *
		 *	Returns the previously set min burst content size
		 *
		 *	@return	double			The stored min burst content size
		 */
		public function get_min_burst_content() {
			
			return $this->_min_burst_content;
			
		}

		/**
		 *	set_max_burst_content()
		 * 
		 *	We want this to be a double so it can have a larger value that we would
		 *	ever need to be used to represent no minimum
		 *
		 *	If the option is empty (blank) => default (10MB)
		 *	If the option is 0 => large value
		 *
		 *	@param	mixed	$max_burst_content		double for specific value or null for choice	
		 *	@return	object							This object
		 */
		public function set_max_burst_content( $max_burst_content = null ) {
		
			// null is a special value that is further mapped to a "large" value for the
			// particular architecture
			$max_burst_content_option_value_map = array( 0 => null );
			
		 	$this->_max_burst_content = $this->derive_optional_double( 'zip_max_burst_content', $max_burst_content, self::ZIP_DEFAULT_MAX_BURST_CONTENT, $max_burst_content_option_value_map, (1024*1024), self::ZIP_DEFAULT_MAX_BURST_CONTENT );
			
			return $this;
			
		}

		/**
		 *	get_max_burst_content()
		 *
		 *	Returns the previously set max burst content size
		 *
		 *	@return	double			The stored max burst content size
		 */
		public function get_max_burst_content() {
			
			return $this->_max_burst_content;
			
		}

		/**
		 *	set_supported_zip_methods()
		 *
		 *	Appends or prepends the method or methods passed to the existing supported methods array
		 *
		 *	@param	string/array	$methods	Either a (comma separated) string of methods or an array
		 *	@param	bool			$append		True if $methods should be appended to existing supported methods
		 *	@return	bool						True if set succeeded, otherwise false
		 */
		protected function set_supported_zip_methods( $methods, $append = true ) {
		
			$result = false;
		
			// If $methods is a string we need to turn it into an array (of one or more elements) or
			// otherwise assume it is an array already (but we double check in a mo)
			( is_string( $methods ) ) ? $methods_to_add = explode( ",", $methods ) : $methods_to_add = $methods;

			// Make sure we have an array and if so then either append or prepend to existing supported methods
			if ( is_array( $methods_to_add ) ) {
			
				( $append ) ? $this->_supported_zip_methods = array_merge( $this->_supported_zip_methods, $methods_to_add ) :
							  $this->_supported_zip_methods = array_merge( $methods_to_add, $this->_supported_zip_methods );
			
				$result = true;			
			
			}
			
			// Will return false if we somehow didn't end up with an array to merge
			return $result;
		
		}
		
		/**
		 *	check_method_dependencies()
		 *
		 *	Checks the dependencies that a method defines for itself - this may optionally also mean
		 *	calling a given callback function that allows the method to add it's own very specific checks
		 *	beyond those that are run as standard.
		 *
		 *	@param	string		$class_name		The name of the class to check, needed because this is static checking
		 *	@return	bool						True if dependency check succeeded, otherwise false
		 */
		protected function check_method_dependencies( $class_name ) {
		
			// Assume dependency checks will pass - will be set false if a check fails
			$result = true;
			
			if ( !method_exists( $class_name, 'get_method_dependencies_static' ) ) {
			
				$result = false;
			
			} else {
		
				$method_dependencies = call_user_func( array( $class_name, 'get_method_dependencies_static' ) );
				
			}
			
			if ( ( $result ) && isset( $method_dependencies[ 'classes' ] ) && !empty( $method_dependencies[ 'classes' ] ) ) {
			
				$classes = $method_dependencies[ 'classes' ];
			
				$disabled_classes = array_map( "trim", explode( ',', ini_get( 'disable_classes' ) ) );
				
				// Check each function dependency and bail out on first failure
				foreach ( $classes as $class ) {
				
					$class = trim( $class );
					
					if ( !( ( class_exists( $class ) ) && ( !in_array( $class, $disabled_classes ) ) ) ) {

						$result = false;
						break;
						
					}
				
				}
			}
			
			if ( ( $result ) && isset( $method_dependencies[ 'functions' ] ) && !empty( $method_dependencies[ 'functions' ] ) ) {
			
				$functions = $method_dependencies[ 'functions' ];
				
				$disabled_functions = array_map( "trim", explode( ',', ini_get( 'disable_functions' ) ) );
				
				// Check each function dependency and bail out on first failure
				foreach ( $functions as $function ) {
				
					$function = trim( $function );
					
					if ( !( ( function_exists( $function ) ) && ( !in_array( $function, $disabled_functions ) ) ) ) {

						$result = false;
						break;
						
					}
				
				}
			
			}
			
			// No extension checks yet
			
			// No file checks yet (need to determine how this might work a bit better)
			
			if ( ( $result ) && isset( $method_dependencies[ 'check_func' ] ) && !empty( $method_dependencies[ 'check_func' ] ) ) {
			
				$result = call_user_func( array( $class_name, $method_dependencies[ 'check_func' ] ) );
				
			}
			
			return $result;
		
		}
		
		/**
		 *	deduce_zip_methods()
		 *	
		 *	Returns the array of zip methods that are available (or just the best) filtered by requested methods.
		 *	Because the available methods don't really change often (rarely once stable) we use a transient
		 *	which has some defined lifetime so we don't waste time repeating the testing which involves creating
		 *	objects and processes and files which can be time consuming.
		 *	The using script can decide to have the transient refreshed by deleting it and then it will be regenerated.
		 *	Note: There is an included "signature" so that we can detect server or other moves and regenerate.
		 *	Note: filemtime() is used because this will (should) force the transient to update if the plugin is
		 *	updated because the file modification time of the file will change because the plugin is installed in a
		 *	different disk location with newly written files - the same should apply if the site is restored/migrated.
		 *	
		 *	@param		array	Array reference for the deduced zip methods
		 *	@param		array	Arry reference for the details of the deduced methods
		 *	@param		array	Flat array of requested (preferred) zip methods
		 *	@param		bool	True if only the best available method wanted
		 *	@param		string	Which zip mode being tested
		 *	@return		bool	True if methods are available, False otherwise
		 *
		 */
		protected function deduce_zip_methods( array &$methods, array &$methods_details, array $requested, $best_only ) {
			
			$available_methods = array();
			$available_methods_details = array();
			$aggregate_available_methods = array();
			$server_signature_string = "";
			$server_signature = "";
			$use_cached_methods = false;

			// Decide if we should try for cached methods or not (save for later)			
			if ( ( $use_cached_methods = $this->use_cached_methods() ) ) {

				$aggregate_available_methods = get_transient( $this->_zip_methods_transient );
				
				// Drop through if we didn't get a transient otherwise we'll test it for validity
				if ( false !== $aggregate_available_methods ) {
				
					// Generate server signature and check it matches the cached signature
					// Use current filename as component as it should be unique enough for this purpose
					$server_signature_string = __FILE__ . " : " . ( ( $filemodtime = filemtime( __FILE__ ) ) ? (string) $filemodtime : '1' );
					$server_signature = md5( $server_signature_string );
					
					if ( ( false === isset( $aggregate_available_methods[ 'control' ][ 'signature' ] ) ) ||
						 ( $server_signature !== $aggregate_available_methods[ 'control' ][ 'signature' ] ) ) {

						// Either no signature previously set or it has changed - either way we need to reevaluate available methods
						$aggregate_available_methods = false;
						 
					}
				
				}

			} else {
			
				$this->log( 'details', 'Zip method caching disabled based on settings or unavailable.' );
				$aggregate_available_methods = false;
				
			}
			
			// Create new transient if we didn't have one, it was expired or we nuked it because invalid
			if ( false === $aggregate_available_methods ) {

				// Get all available methods in $available_methods - must return them in order best -> worst
				// Also getting the method details array which is keyed by method tag
				$this->get_available_zip_methods( $this->_supported_zip_methods, $available_methods, $available_methods_details );
				
				// Now we have to combine the two arrays into an aggregate to save
				$aggregate_available_methods[ 'methods' ] = $available_methods;
				$aggregate_available_methods[ 'details' ] = $available_methods_details;
				
				// Only save if we are using caching (determined earlier)
				if ( $use_cached_methods ) {
				
					// Add the server signature for detecting invalidated methods details on a migration or some other change
					// Note: See discussion above on derivation of signature
					// TODO: Check, probably can use the server signature calculated above
					$server_signature_string = __FILE__ . " : " . ( ( $filemodtime = filemtime( __FILE__ ) ) ? (string) $filemodtime : '1' );
					$server_signature = md5( $server_signature_string );
					$aggregate_available_methods[ 'control' ][ 'signature' ] = $server_signature;
					
					set_transient( $this->_zip_methods_transient, $aggregate_available_methods, self::ZIP_METHODS_TRANSIENT_LIFE );
					
				}
							
			} else {
			
				// We got a valid transient value so now separate the aggregate into two
				$available_methods = $aggregate_available_methods[ 'methods' ];
				$available_methods_details = $aggregate_available_methods[ 'details' ];
			
			}
			
			// Check whether these need to be filtered by requested methods
			if ( !empty( $requested ) ) {
			
				// Filter the available methods - result could be empty
				// Order will be retained regardless of order of requested methods
				// Renumber numeric keys fom 0
				$available_methods = array_values( array_intersect( $available_methods, $requested ) );
				
			}

			// If just the best available requested then slice it off
			if ( ( true === $best_only ) && ( !empty( $available_methods ) ) ) {
			
				$methods = array_slice( $available_methods, 0, 1 );
				$methods_details = $available_methods_details;
				
			} else {
			
				$methods = $available_methods;
				$methods_details = $available_methods_details;
			
			}
			
			if ( !empty( $methods ) ) {
			
				return true;
				
			} else {
			
				return false;
				
			}
		
		}
				
		/**
		 *	use_cached_methods()
		 *	
		 *	Returns whether conditions/configuration indicate cached methods should be used
		 *	Note: Temporarily add the condition for whether get_/set_transient functions
		 *	exist - if not (meaning we are probably running under importbuddy) then we also
		 *	skip caching. This adds a little time when instantiating because we have to test
		 *	every time but it's acceptable for now. In the longer term we will have an
		 *	alternative way to handle the transient data outside of WordPress.
		 *	
		 *	@return		bool	true if use cached methods, false otherwise
		 *
		 */
		protected function use_cached_methods() {
		
			// By default we'll be using caching
			$result = true;
			
			// Has caching been explicitly disabled
			$caching_disabled = ( isset( pb_backupbuddy::$options['disable_zipmethod_caching'] ) &&
								  ( pb_backupbuddy::$options['disable_zipmethod_caching'] == '1') );

			// Do we have the means to cache
			$caching_unavailable = ( !( function_exists( 'get_transient' ) && function_exists( 'set_transient' ) ) );	

			if ( $caching_disabled || $caching_unavailable ) {
			
				$result = false;
				
			}
			
			return $result;
		
		}
				
		/**
		 *	get_zip_methods()
		 *	
		 *	Returns the array of zip methods previously deduced
		 *	
		 *	@return		array	Flat array of zip methods (could be empty)
		 *
		 */
		public function get_zip_methods() {
			
			return $this->_zip_methods;
		
		}
				
		/**
		 *	set_zip_methods()
		 *	
		 *	Resets the zip methods based on new criteria and returns the array of zip methods
		 *	
		 *	@param		array	$requested	Flat array of requested (preferred) zip methods
		 *	@param		bool	$best_only	Optional: True if only the best available method wanted
		 *	@return		object				This object
		 *
		 */
		public function set_zip_methods( array $requested, $best_only = false ) {
			
			// Update the memory of what zip methods were requested - make it clean
			$this->_requested_zip_methods = array_map( 'trim', $requested );
			
			// Work out the list of zip methods from the requested and available
			$this->deduce_zip_methods( $this->_zip_methods, $this->_zip_methods_details, $this->_requested_zip_methods, $best_only );
			
			return $this;
		
		}
								
		/**
		 *	refresh_zip_methods()
		 *
		 *	Refresh the information on the available zip methods
		 *	TODO: Perhaps the transient could be deleted here rather than by the caller?
		 *
		 *	@param		array		$zip_methods	Optional: The set of zip methods requested to use
		 *	@return		object						This object
		 */
		public function refresh_zip_methods( $zip_methods = array() ) {
		
			$this->set_zip_methods( $zip_methods );
			
			return $this;
			
		}

		/**
		 *	sanitize_excludes()
		 *
		 *	Take an exclusion list of directories and/or files and produce a sanitized exclusion list
		 *	Directories will be recognized by having a trailing directory separator otherwise will be
		 *	treated as a file (note that here we are working with patterns and not testing to see
		 *	whether something _is_ a directory or not because we don't necessarily have the full
		 *	directory path).
		 *	Note: Anything that contains a wildcard character (* or ?) is ignored as these are not
		 *	currently supported (and maybe never will across the board). For command zip zip we can
		 *	consider an option to have these as separate exclusions (and currently we can accomodate
		 *	then through specifying environment ZIPOPTS.
		 *
		 *	@param	array		List of primary exclusions - may be empty
		 *	@param	array		List of secondary exclusions - may be empty
		 *	@param	string		The base directory to be used if normalizing
		 *
		 *	@return	mixed		array on success, false otherwise
		 */
		protected function sanitize_excludes( $primary, $secondary, $base = '' ) {

			$sanitized = array();
			
			$basedir = trim( $base );
			$normalize = !empty( $basedir );
			
			// Normalize the trailing directory separator on the path
			$basedir = rtrim( $basedir, self::DIRECTORY_SEPARATORS ) . self::NORM_DIRECTORY_SEPARATOR;
			
			// Normalize platform specific directory separators in path
			$basedir = str_replace( DIRECTORY_SEPARATOR, self::NORM_DIRECTORY_SEPARATOR, $basedir );
		
			// $primary is considered to be unclean
			foreach ( $primary as $exclude ) {
			
				// Reset flag for whether this exclude is a directory-like exclude
				$is_directory_path = false;
				
				// Get rid of standard prefix/suffix detritus
				$exclude = trim( $exclude );
				
				// Possible that we could end up with an empty entry
				// Also ignore if any wildcard characters present
				if ( !empty( $exclude ) && ( !preg_match( '|[?*]|', $exclude ) ) ) {
				
					// Remember if it has a directory separator suffix
					if ( preg_match( '|[' . addslashes( self::DIRECTORY_SEPARATORS ) . ']$|', $exclude ) ) {
					
						$is_directory_path = true;
					
					}
					
					// Remove what could be multiple prefix or suffix directory separators
					$exclude = trim( $exclude, self::DIRECTORY_SEPARATORS );
					
					// Make sure platform specific directory separators in path become normalized
					$exclude = str_replace( DIRECTORY_SEPARATOR, self::NORM_DIRECTORY_SEPARATOR, $exclude );
					
					// And add back a single instance prefix
					$exclude = self::NORM_DIRECTORY_SEPARATOR . $exclude;
					
					// And optionally a single instance suffix
					if ( $is_directory_path ) {
					
						$exclude .= self::NORM_DIRECTORY_SEPARATOR;
						
					}
										
					$sanitized[] = $exclude;
					
				}
				
			}
			
			// $secondary is considered to be clean
			if ( !empty( $secondary ) ) {
			
				$sanitized = array_merge( $sanitized, $secondary ); 
			
			}
			
			// Get unique entries and renumber numeric keys
			$sanitized = array_merge( array_unique( $sanitized ) );
			
			if ( true == $normalize ) {
			
				// Make sure the normalize base has a trailing directory separator
				$basedir = ( rtrim( $basedir, self::NORM_DIRECTORY_SEPARATOR ) ) . self::NORM_DIRECTORY_SEPARATOR;
			
				foreach ( $sanitized as &$exclusion ) {
				
					// Must remove any leading DIRECTORY_SEPARATOR because $basedir always has trailing
					$exclusion = ltrim( $exclusion, self::NORM_DIRECTORY_SEPARATOR );
					$exclusion = ( $basedir . $exclusion );
					
				}
								
			}
					
			return $sanitized;
		
		}

		/**
		 *	get_available_zip_methods()
		 *	
		 *	Returns the array of zip methods that are available for the mode of this object
		 *	Libraries must have been loaded already
		 *	
		 *	@param		array	The supported zip methods
		 *	@param		array	The array which will hold the available methods
		 *	@param		array	The array that will hold the available methods attributes (method tag is key)
		 *	@return		bool	True if methods available, False otherwise
		 *
		 */
		protected function get_available_zip_methods( array $supported_zip_methods, &$available_methods, &$available_methods_details ) {
		
			// Make sure these are cleared as the caller might not have done so
			$available_methods = array();
			$available_methods_details = array();
			
			// Try each supported zip method to see what it can do on this system		
			foreach ( $supported_zip_methods as $method_tag ) {

				$class_name = 'pluginbuddy_zbzip' . $method_tag;
	
				// Let the method object inherit our logger
				$zipper = new $class_name( $this );
				
				if ( true === $zipper->is_available( $this->_tempdir ) ) {
				
					$available_methods[] = $method_tag;
					$available_methods_details[ $method_tag ] = $zipper->get_method_details();
					
				}
				
				unset( $zipper );
			}
						
			return ( !empty( $available_methods ) );

		}
						
		/**
		 *	get_compatibility_zip_methods()
		 *	
		 *	Returns the array of zip methods that are regarded as "compatibility" methods
		 *	Libraries must have been loaded already
		 *	
		 *	@return		array	Flat array of zip methods (could be empty)
		 *
		 */
		protected function get_compatibility_zip_methods() {
		
			$compatibility_methods = array();
			
			foreach ( $this->_zip_methods as $method_tag ) {

				$class_name = 'pluginbuddy_zbzip' . $method_tag;
	
				// Let the method object inherit our logger
				$zipper = new $class_name( $this );
				
				if ( $zipper->get_is_compatibility_method() === true ) {
				
					$compatibility_methods[] = $method_tag;
					
				}
				
				unset( $zipper );
			}
			
			return $compatibility_methods;
			
		}
		
		/**
		 *	get_best_zip_methods()
		 *	
		 *	Returns the array of best zip method(s)
		 *	For now we assume only one "best" method as being the first method in the list
		 *	that has the requested attribute(s).
		 *	Libraries must have been loaded already
		 *	
		 *	@param		array	$attributes	Array of attributes to check method supports
		 *	@return		array				Flat array of zip methods (could be empty)
		 *
		 */
		protected function get_best_zip_methods( $attributes = array() ) {
		
			$best_methods = array();
			
			if ( !empty( $this->_zip_methods ) ) {
			
				if ( empty( $attributes ) ) {
				
					// No attributes to test for so just take the first in the list
					$best_methods[] = $this->_zip_methods[ 0 ];
					
				} else {
			
					// Have some attributes to test so work along the list until we find a match or end of list
					foreach ( $this->_zip_methods as $method_tag ) {
				
						// Start afresh each time - assume success
						$match = true;
						foreach ( $attributes as $attribute ) {
						
							// Check each attribute in turn (precondition that all attributes set true/false)
							$match = ( $match && ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ $attribute ] === true ) );
						
						}
						
						if ( true === $match ) {
						
							// Found our matching method so break out of the test loop to return
							$best_methods[] = $method_tag;
							break;
						
						}						

					}
				
				}
			
			
			}
						
			return $best_methods;
			
		}
		
		public function log( $level, $message ) {
			
			$this->get_logger()->log( $level, $message );
			
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
			
			if ( is_null( $this->_process_monitor ) ) {
				
				$pm = new pluginbuddy_zipbuddy_null_object();
				$this->set_process_monitor( $pm );

			}
			
			return $this->_process_monitor;
			
		}
		
		/**
		 *	create_empty_zip()
		 *	
		 *	A function that creates an empty archive file with optional comment
		 *	
		 *	Create an empty zip archive (just the end of central dir) with an optional
		 *	comment as well. This has a well known basic structure and content so we can
		 *	write it directly as binary data.
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to create
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@param		string	$comment		Comment to apply to archive. (optional)
		 *	@return		bool					True if the creation was successful, false otherwise
		 *
		 */
		public function create_empty_zip( $zip, $tempdir, $comment = '' ) {
		
			$result = false;
			$zip_file_name = $tempdir . basename( $zip );
			
			$this->log( 'details', sprintf( __( 'Zip process reported: Initializing zip archive file: %1$s', 'it-l10n-backupbuddy' ), $zip_file_name ) );
			
			try {
			
				$zip_file = new SplFileObject( $zip_file_name, "wb" );

				// Encode $comment if an array.
				if ( is_array( $comment ) ) {
				
					$comment = json_encode( $comment );
					
				}
				
				// Don't add an empty comment - so if the encoded string is empty or an empty
				// string was passed in originally don't do anything.
				if ( 0 != strlen( $comment ) ) {
				
					$comment = 'MetaData:' . $comment . 'MetaData-End:';
					
				}
				
				// ----- Packed data
				$binary_data = pack( "VvvvvVVv", 0x06054b50, 0, 0, 0, 0, 0, 0, strlen( $comment ) );

				// ----- Write the 22 bytes of the header in the zip file
				$zip_file->fwrite( $binary_data, 22 );

				// ----- Write the variable fields
				if ( 0 != strlen( $comment ) ) {
				
				  $zip_file->fwrite( $comment, strlen( $comment ) );
				  
				}
				
				unset( $zip_file );
				
				$this->log( 'details', sprintf( __( 'Zip process reported: Initialized zip archive file', 'it-l10n-backupbuddy' ) ) );
				$result = true;
			
			} catch ( Exception $e ) {
			
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('Zip process reported: Failure to initialize zip archive file - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
			}

			return $result;
			
		}
		
		/**
		 *	add_directory_to_zip()
		 *
		 *	Adds a directory to a new or existing (TODO: not yet available) ZIP file.
		 *
		 *	@param	string	$zip_file					Full path & filename of ZIP file to create.
		 *	@param	string	$add_directory				Full directory to add to zip file.
		 *	@param	array	$excludes					Array of strings of paths/files to exclude from zipping
		 *	@param	string	$temporary_zip_directory	Full directory path to directory to temporarily place ZIP
		 *
		 *	@return						true on success, false otherwise
		 *
		 */
		public function add_directory_to_zip( $zip_file, $add_directory, $excludes = array(), $temporary_zip_directory = '' ) {

			if ( true === $this->_is_experimental ) {
			
				$this->log( 'message', __('Running alternative ZIP system (BETA) based on settings.','it-l10n-backupbuddy' ) );
			
			} else {
			
				$this->log( 'message', __('Running standard ZIP system based on settings.','it-l10n-backupbuddy' ) );
			
			}
			
			// Let's just log if this is a 32 or 64 bit system
			$php_size = ( pluginbuddy_stat::is_php( pluginbuddy_stat::THIRTY_TWO_BIT ) ) ? "32" : "64" ;
			$this->log( 'details', sprintf( __( 'Running under %1$s-bit PHP', 'it-l10n-backupbuddy' ), $php_size ) );
			
			// Make sure we tell what the sapi is
			$this->log( 'details', sprintf( __( 'Server API: %1$s', 'it-l10n-backupbuddy' ), $this->get_sapi_name() ) );			
					
			$zip_methods = array();
			$sanitized_excludes = array();
			
			// Set some additional system excludes here for now - these are all from the site install root
			$additional_excludes = array( self::NORM_DIRECTORY_SEPARATOR . 'importbuddy' . self::NORM_DIRECTORY_SEPARATOR,
										  self::NORM_DIRECTORY_SEPARATOR . 'importbuddy.php',
										  self::NORM_DIRECTORY_SEPARATOR . 'wp-content' . self::NORM_DIRECTORY_SEPARATOR . 'uploads' . self::NORM_DIRECTORY_SEPARATOR . 'pb_backupbuddy' . self::NORM_DIRECTORY_SEPARATOR,
										);
			
			// Make sure we have a valid zip method strategy setting to use otherwise fall back to emergency compatibility
			if ( isset( pb_backupbuddy::$options[ 'zip_method_strategy' ] ) && 	( '0' !== pb_backupbuddy::$options[ 'zip_method_strategy' ] ) ) {
			
				$zip_method_strategy = pb_backupbuddy::$options[ 'zip_method_strategy' ];
				switch ( $zip_method_strategy ) {
					case "1":
						// Best Available
						$zip_methods = $this->get_best_zip_methods( array( 'is_archiver' ) );
						$this->log( 'details', __('Using Best Available zip method based on settings.','it-l10n-backupbuddy' ) );
						break;
					case "2":
						// All Available
						$zip_methods = $this->_zip_methods;
						$this->log( 'details', __('Using All Available zip methods in preferred order based on settings.','it-l10n-backupbuddy' ) );
						break;
					case "3":
						// Force Compatibility
						$zip_methods = $this->get_compatibility_zip_methods();				
						$this->log( 'message', __('Using Forced Compatibility zip method based on settings.','it-l10n-backupbuddy' ) );
						break;
					default:
						// Hmm...unrecognized value - emergency compatibility
						$zip_methods = $this->get_compatibility_zip_methods();				
						$this->log( 'message', sprintf( __('Forced Compatibility Mode as Zip Method Strategy setting not recognized: %1$s','it-l10n-backupbuddy' ), $zip_method_strategy ) );
				}
				
			} else {
			
				// We got no or an invalid zip method strategy which is a bad situation - emergency compatibility is the order of the day
				$zip_methods = $this->get_compatibility_zip_methods();				
				$this->log( 'message', __('Forced Compatibility Mode as Zip Method Strategy not set or setting not recognized.','it-l10n-backupbuddy' ) );
			
			}
			
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
			
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', __('Failed to create a Zip Archive file - no available methods.','it-l10n-backupbuddy' ) );
				
				// We should have a temporary directory, must get rid of it, can simply rmdir it as it will (should) be empty
				if ( !empty( $temporary_zip_directory ) && file_exists( $temporary_zip_directory ) ) {
					
					if ( !rmdir( $temporary_zip_directory ) ) {
					
						$this->log( 'details', __('Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $temporary_zip_directory );
					
					}
						
				}

				return false;
				
			}
			
			$this->log( 'details', __('Creating ZIP file','it-l10n-backupbuddy' ) . ' `' . $zip_file . '`. ' . __('Adding directory','it-l10n-backupbuddy' ) . ' `' . $add_directory . '`. ' . __('Excludes','it-l10n-backupbuddy' ) . ': ' . implode( ',', $excludes ) );
			
			// We need the classes for being able to build backup file list
			require_once( pb_backupbuddy::plugin_path() . '/lib/' . $this->_whereami . '/zbdir.php' );
			if ( !class_exists( 'pluginbuddy_zbdir' ) ) {
			
				// Hmm, require_once() didn't bomb but we haven't got the class we expect - bail out
				$this->log( 'details', __('Unable to load classes for backup file list builder.','it-l10n-backupbuddy' ) );
				
				return false;

			}
			
			// Generate our sanitized list of directories/files to exclude as relative paths
			$sanitized_excludes = $this->sanitize_excludes( $excludes, $additional_excludes );
			
			// Do the same for directories/files to include
			//$sanitized_includes = $this->sanitize_excludes( $includes, $additional_includes );
			
			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can archive with this method
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_archiver' ] === true ) {

					$class_name = 'pluginbuddy_zbzip' . $method_tag;
		
					// Zipper will initially inherit our logger and our
					// process monitor
					$zipper = new $class_name( $this );
					
					// Now override logger - will define a prefix here
					$zipper->set_logger( $this->_default_child_logger );
					
					// Set these on specific zipper based on the values we derived at construnction or
					// overridden by subsequent method calls
					$zipper->set_compression( $this->get_compression() );
					$zipper->set_ignore_symlinks( $this->get_ignore_symlinks() );
					$zipper->set_ignore_warnings( $this->get_ignore_warnings() );
					$zipper->set_step_period( $this->get_step_period() );
					$zipper->set_burst_gap( $this->get_burst_gap() );
					$zipper->set_min_burst_content( $this->get_min_burst_content() );
					$zipper->set_max_burst_content( $this->get_max_burst_content() );
				
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
					
					// Tell the method the server api in use
					$zipper->set_sapi_name( $this->get_sapi_name() );
					
					$this->log( 'details', __('Trying ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP.','it-l10n-backupbuddy' ) );
					
					// As we are looping make sure we have no stale file information
					clearstatcache();
					
					// The temporary zip directory _must_ exist
					if ( !empty( $temporary_zip_directory ) ) {
					
						if ( !file_exists( $temporary_zip_directory ) ) { // Create temp dir if it does not exist.
						
							mkdir( $temporary_zip_directory );
							
						}
						
					}
					
					// Now we are ready to try and produce the backup
					if (  true === ( $result = $zipper->create( $zip_file, $add_directory, $sanitized_excludes, $temporary_zip_directory ) ) ) {
					
						// Got a valid zip file so we can just return - method will have cleaned up the temporary directory
						$this->log( 'details', __('The ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP was successful.','it-l10n-backupbuddy' ) );
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return true;
						
					} elseif ( is_array( $result ) ) {
					
						// Didn't finish zip creation on that step so we need to set up for another step
						// Add in any addiitonal state information and simply return the state
						$this->log( 'details', __('The ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP partially completed.','it-l10n-backupbuddy' ) );
						unset( $zipper );
						return $result;
	
					} else {
					
						// We failed on the first step for one eason or another - may be an option
						// to try with another method...
						// Method will have cleaned up the temporary directory				
						$this->log( 'details', __('The ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP was unsuccessful.','it-l10n-backupbuddy' ) );
						unset( $zipper );
						
					}
				
				} else {
				
					// This method is not considered suitable (reliable enough) for creating archives or lacked zip capability
					$this->log( 'details', __('The ','it-l10n-backupbuddy' ) . $method_tag . __(' method is not currently supported for backup.','it-l10n-backupbuddy' ) );					
				
				}
				
			}
			
			// If we get here then have failed in all attempts
			$this->log( 'details', __('Failed to create a Zip Archive file with any nominated method.','it-l10n-backupbuddy' ) );
			
			return false;
	
		}

		/**
		 *	grow_zip()
		 *
		 *	This is called after the first step and carries on growing the zip with he already
		 *	calculated archive content
		 *
		 *	@param	string	$zip_file					Full path & filename of ZIP file to grow.
		 *	@param	string	$temporary_zip_directory	Full directory path to directory were we are working
		 *	@param	array	$state						State array that we need to pick up where we left off
		 *
		 *	@return	mixed								true on completion of archive, array for continuation, false on failure
		 *
		 */
		public function grow_zip( $zip_file, $temporary_zip_directory, $state ) {
			
			// Initialize the zip method tag of the method we are using
			$zip_methods = array( $state[ 'zipbuddy' ][ 'mt' ] );
			
			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can archive with this method
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_archiver' ] === true ) {

					$class_name = 'pluginbuddy_zbzip' . $method_tag;
		
					// Zipper will initially inherit our logger and
					// our process monitor
					$zipper = new $class_name( $this );
					
					// Now override logger - will define a prefix here
					$zipper->set_logger( $this->_default_child_logger );
					
					// Set these on specific zipper based on the values we derived at construnction or
					// overridden by subsequent method calls
					$zipper->set_compression( $this->get_compression() );
					$zipper->set_ignore_symlinks( $this->get_ignore_symlinks() );
					$zipper->set_ignore_warnings( $this->get_ignore_warnings() );
					$zipper->set_step_period( $state[ 'zipper' ][ 'sp' ] );
					$zipper->set_burst_gap( $this->get_burst_gap() );
					$zipper->set_min_burst_content( $this->get_min_burst_content() );
					$zipper->set_max_burst_content( $this->get_max_burst_content() );
					
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
					
					// Tell the method the server api in use
					$zipper->set_sapi_name( $this->get_sapi_name() );
					
					$this->log( 'details', __('Trying ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP.','it-l10n-backupbuddy' ) );
					
					// As we are looping make sure we have no stale file information
					clearstatcache();
					
					// The temporary zip directory _must_ exist
					if ( !empty( $temporary_zip_directory ) ) {
					
						if ( !file_exists( $temporary_zip_directory ) ) { // Create temp dir if it does not exist.
						
							mkdir( $temporary_zip_directory );
							
						}
						
					}
					
					// Now we are ready to try and produce the backup
					if (  true === ( $result = $zipper->grow( $zip_file, $temporary_zip_directory, $state ) ) ) {
					
						// Got a valid zip file so we can just return - method will have cleaned up the temporary directory
						$this->log( 'details', __('The ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP was successful.','it-l10n-backupbuddy' ) );
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return true;
						
					} elseif ( is_array( $result ) ) {
					
						// Didn't finish zip creation on that step so we need to set up for another step
						// Add in any addiitonal state information and simply return the state
						$this->log( 'details', __('The ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP partially completed.','it-l10n-backupbuddy' ) );
						unset( $zipper );
						return $result;
	
					} else {
					
						// We failed on a continuation step so we are done really...
						// Method will have cleaned up the temporary directory				
						$this->log( 'details', __('The ', 'it-l10n-backupbuddy' ) . $method_tag . __(' method for ZIP was unsuccessful.','it-l10n-backupbuddy' ) );
						unset( $zipper );
						
					}
				
				} else {
				
					// This method is not considered suitable (reliable enough) for creating archives or lacked zip capability
					$this->log( 'details', __('The ','it-l10n-backupbuddy' ) . $method_tag . __(' method is not currently supported for backup.','it-l10n-backupbuddy' ) );					
				
				}
				
			}
			
			// If we get here then have failed in all attempts
			$this->log( 'details', __('Failed to create a Zip Archive file with any nominated method.','it-l10n-backupbuddy' ) );
			
			return false;
	
		}

		/**
		 *	unzip()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@param	bool|string $force_compatibility_mode	False: use all available, String: use that method
		 *
		 *	@return	bool									true on success, false otherwise
		 */
		public function unzip( $zip_file, $destination_directory, $force_compatibility_mode = false ) {

			$zip_methods = array();
			
			// The following is just to match current functionality for importbuddy - ideally would rather
			// do it by selecting available compatibility methods based on method attributes - may do that later
			// (would also need get_compatibility_zip_methods() to be updated to take parameter to check
			// whether compatibility method for that particular function.
						
			// Decide which methods we are going to try
			if ( $force_compatibility_mode == 'ziparchive' ) {

				$zip_methods = array( 'ziparchive' );				
				$this->log( 'message', __('Forced compatibility unzip method (ZipArchive; medium speed) based on settings.','it-l10n-backupbuddy' ) );
				
			} elseif ( $force_compatibility_mode == 'pclzip' ) {
			
				$zip_methods = array( 'pclzip' );				
				$this->log( 'message', __('Forced compatibility unzip method (PCLZip; slow speed) based on settings.','it-l10n-backupbuddy' ) );			
			
			} else {
			
				$zip_methods = $this->_zip_methods;
				$this->log( 'details', __('Using all available unzip methods in preferred order.','it-l10n-backupbuddy' ) );
			}
						
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
			
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', sprintf( __('Unable to extract backup file contents (%1$s to %2$s): No available unzip methods found.','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );
				
				return false;
				
			}

			// Make sure we have a normalized directory separator suffix	
			$destination_directory = rtrim( $destination_directory, self::DIRECTORY_SEPARATORS ) . self::NORM_DIRECTORY_SEPARATOR;		

			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can check file existence with this method (ignore silently if not)
				// Note: has to be able to unzip as well but if that functionality wasn't available in
				// the method the is_checker attribute will have been set false
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_unarchiver' ] === true ) {

					$class_name = 'pluginbuddy_zbzip' . $method_tag;
		
					$zipper = new $class_name( $this );
					
					$zipper->set_logger( $this->_default_child_logger );
					
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
										
					// Now we are ready to try and extract the backup
					$result = $zipper->extract( $zip_file, $destination_directory );
					
					// Will be false if we couldn't extract the backup
					if ( $result === true ) {
					
						// Must assume that we extracted ok
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return true;
	
					} else {
					
						// The zipper encountered an error so we need to drop through and loop round to try another
						// We'll not process the result here, just drop through silently (the method will have logged it)
						unset( $zipper );
						
					}
				
				}
				
			}
			
			// If we got this far then no method to extract backup content was available or worked
			$this->log( 'details', sprintf( __('Unable to extract backup file contents (%1$s to %2$s): No compatible zip method found.','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );
			return false;
			
		}
		
		/**
		 *	extract()
		 *
		 *	Extracts the specified contents of a zip file to the specified destination using the best unzip methods possible.
		 *	The destination directory _must_ already exist and be writable - this function does not create it
		 *	The items are an array of mapping of what => where, e.g.
		 *	array( "dir/myfile.txt" => "", "dir/myfile.txt" => "tmpfilename", "dir/myfile.txt" => "dir/myfile.txt" )
		 *	In the first case the file is extracted to $destination_directory/myfile.txt
		 *	In the second case the file is extracted to $destination_directory/tmpfilename
		 *	In the third case the file is extracted to $destination_directory/dir/myfile.txt
		 *	Note: in the second case the file is initially extrcated as myfile.txt and then rename to tmpfilename
		 *	Another example is for directory extraction:
		 *	array( "dir/*" => "dir/*" )
		 *	Whereby the directory dir and all it's content (recursively) is extracted to $destination/dir
		 *	Note: the * is required otherwise you just get an empty directory
		 *
		 *	@param	string	$zip_file				Full path & filename of ZIP file to extract from.
		 *	@param	string	$destination_directory	Full directory path to extract to
		 *	@param	array	$items					Mapping of what to extract and to what
		 *
		 *	@return	bool							true on success (all extractions successful), false otherwise
		 */
		public function extract( $zip_file, $destination_directory, $items ) {

			$zip_methods = array();
			
			// The following is just to match current functionality for importbuddy - ideally would rather
			// do it by selecting available compatibility methods based on method attributes - may do that later
			// (would also need get_compatibility_zip_methods() to be updated to take parameter to check
			// whether compatibility method for that particular function.
						
			$zip_methods = $this->_zip_methods;
						
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
			
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', sprintf( __('Unable to extract from backup file (%1$s to %2$s): No available unzip methods found.','it-l10n-backupbuddy' ), $zip_file, $destination ) );
				
				return false;
				
			}
			
			if ( !( file_exists( $destination_directory ) && is_dir( $destination_directory ) && is_writable( $destination_directory ) ) ) {
			
				$this->log( 'details', sprintf( __('Unable to extract from backup file (%1$s to %2$s): %2$s does not exist, is not a directory or is not writeable','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );
			
				return false;
				
			}
			
			// Make sure we have a normalized directory separator suffix	
			$destination_directory = rtrim( $destination_directory, self::DIRECTORY_SEPARATORS ) . self::NORM_DIRECTORY_SEPARATOR;		

			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can check file existence with this method (ignore silently if not)
				// Note: has to be able to unzip as well but if that functionality wasn't available in
				// the method the is_checker attribute will have been set false
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_extractor' ] === true ) {

					$class_name = 'pluginbuddy_zbzip' . $method_tag;
		
					$zipper = new $class_name( $this );
					
					$zipper->set_logger( $this->_default_child_logger );
					
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
										
					// Now we are ready to try and extract from the backup
					$result = $zipper->extract( $zip_file, $destination_directory, $items );
					
					// Will be false if we couldn't extract from the backup
					if ( $result === true ) {
					
						// Must assume that we extracted ok
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return true;
	
					} else {
					
						// The zipper encountered an error so we need to drop through and loop round to try another
						// We'll not process the result here, just drop through silently (the method will have logged it)			
						unset( $zipper );
						
					}
				
				}
				
			}
			
			// If we got this far then no method to extract from backup content was available or worked
			$this->log( 'details', sprintf( __('Unable to extract from backup file (%1$s to %2$s): No compatible zip method found.','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );
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
		 *	@return		bool					True if the file is found in the zip otherwise false
		 *
		 */
		public function file_exists( $zip_file, $locate_file, $leave_open = false ) {
					
			$zip_methods = array();
						
			$zip_methods = $this->_zip_methods;
			
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
			
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', __('Failed to check file exists - no available methods.','it-l10n-backupbuddy' ) );
				
				return false;
				
			}
						
			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can check file existence with this method (ignore silently if not)
				// Note: has to be able to unzip as well but if that functionality wasn't available in
				// the method the is_checker attribute will have been set false
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_checker' ] === true ) {

					$class_name = 'pluginbuddy_zbzip' . $method_tag;
		
					$zipper = new $class_name( $this );
					
					$zipper->set_logger( $this->_default_child_logger );

					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
										
					// Now we are ready to try and test for the file existence
					$result = $zipper->file_exists( $zip_file, $locate_file, $leave_open );
					
					// Will be true/false if file found/not-found otherwise error information array
					if ( !is_array( $result ) ) {
					
						// Either we found the file or we didn't but we have a valid result
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return $result;
	
					} else {
					
						// The zipper encountered an error so we need to drop through and loop round to try another
						// We'll not process the result here, just drop through silently (the method will have logged it)			
						unset( $zipper );
						
					}
				
				}
				
			}
			
			// If we got this far then no method to check backup content was available or worked
			$this->log( 'details', sprintf( __('Unable to check if file exists (looking for %1$s in %2$s): No compatible zip method found.','it-l10n-backupbuddy' ), $locate_file, $zip_file ) );
			return false;
			
		}
				
		/*	get_file_list()
		 *	
		 *	Get an array of all files in a zip file with some file properties.
		 *	
		 *	@param		string		$zip_file	The file to list the content of
		 *	@return		bool|array				false on failure, otherwise array of file properties (may be empty)
		 */
		public function get_file_list( $zip_file ) {

			$zip_methods = array();
						
			$zip_methods = $this->_zip_methods;
			
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
			
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', __('Failed to list backup file contents - no available methods.','it-l10n-backupbuddy' ) );
				
				return false;
				
			}
						
			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can list backup file content with this method (ignore silently if not)
				// Note: has to be able to unzip as well but if that functionality wasn't available in
				// the method the is_lister attribute will have been set false
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_lister' ] === true ) {

					$class_name = 'pluginbuddy_zbzip' . $method_tag;
		
					$zipper = new $class_name( $this );
					
					$zipper->set_logger( $this->_default_child_logger );
					
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
										
					// Now we are ready to try and test for the file existence
					$result = $zipper->get_file_list( $zip_file );
					
					// Will be false if we couldn't list contents or file list array otherwise
					if ( is_array( $result ) ) {
					
						// We got a list so better assume it is ok
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return $result;
	
					} else {
					
						// The zipper encountered an error so we need to drop through and loop round to try another
						// We'll not process the result here, just drop through silently (the method will have logged it)			
						unset( $zipper );
						
					}
				
				}
				
			}
			
			// If we got this far then no method to list backup file content was available or worked
			$this->log( 'details', sprintf( __('Unable to check file content of backup (%1$s): No compatible zip method found.','it-l10n-backupbuddy' ), $zip_file ) );
			return false;
			
		}
		
		/*	set_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string|array	$comment		Comment to apply to archive. If array, json encoded. Deliminated with MetaData: and MetaData-End:.
		 *	@return		bool|string						true on success, error message otherwise.
		 */
		public function set_comment( $zip_file, $comment ) {
			
			$zip_methods = array();
						
			$zip_methods = $this->_zip_methods;
			
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
			
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', __('Failed to set comment in backup file - no available methods.','it-l10n-backupbuddy' ) );
				
				return false;
				
			}
			
			// Encode $comment if an array. Handle delimination.
			if ( is_array( $comment ) ) {
				$comment = json_encode( $comment );
			}
			$comment = 'MetaData:' . $comment . 'MetaData-End:';
			
			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
					
				// First make sure we can manage comments with this method (ignore silently if not)
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_commenter' ] === true ) {
					
					$class_name = 'pluginbuddy_zbzip' . $method_tag;
					
					$zipper = new $class_name( $this );
					
					$zipper->set_logger( $this->_default_child_logger );
					
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
										
					// Now we are ready to try and test for the file existence
					$result = $zipper->set_comment( $zip_file, $comment );
					
					// Will be false if we couldn't set the comment
					if ( $result === true ) {
					
						// Must assume that comment was set ok
						unset( $zipper );
						
						// We have to return here because we cannot break out of foreach
						return true;
	
					} else {
					
						// The zipper encountered an error so we need to drop through and loop round to try another
						// We'll not process the result here, just drop through silently (the method will have logged it)			
						unset( $zipper );
						
					}
				
				}
				
			}
			
			// If we got this far then couldn't set a comment at all - either no available method or all method failed
			$this->log( 'details', sprintf( __('Unable to set comment in file %1$s: No compatible zip method found or all methods failed - note stored internally only.','it-l10n-backupbuddy' ), $zip_file ) );

			// Return message for display - maybe should return false and have caller display it's own message?
			$message = "\n\nUnable to set note in file.\nThe note will only be stored internally in your settings and not in the zip file itself.";
			return $message;
			
		}
		
		/*	get_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string				$zip_file		Filename of archive to retrieve comment from.
		 *	@param		bool				$raw_comment	If true then raw comment field data returned without processing deliminators nor json. Defaults false.
		 *	@return		bool|string|array					false on failure, Zip comment otherwise. If comment is json encoded array returns array.
		 */
		public function get_comment( $zip_file, $raw_comment = false ) {
			
			$zip_methods = array();
						
			$zip_methods = $this->_zip_methods;
			
			// Better make sure we have some available methods
			if ( empty( $zip_methods ) ) {
				
				// Hmm, we don't seem to have any available methods, oops, best go no further
				$this->log( 'details', __('Failed to get comment from backup file - no available methods.','it-l10n-backupbuddy' ) );
				
				return false;
				
			}
			
			// Iterate over the methods - once we succeed just return directly otherwise drop through
			foreach ( $zip_methods as $method_tag ) {
			
				// First make sure we can manage comments with this method (ignore silently if not)
				if ( $this->_zip_methods_details[ $method_tag ][ 'attr' ][ 'is_commenter' ] === true ) {
					
					$class_name = 'pluginbuddy_zbzip' . $method_tag;
					
					$zipper = new $class_name( $this );
					
					$zipper->set_logger( $this->_default_child_logger );
					
					// We need to tell the method what details belong to it
					$zipper->set_method_details( $this->_zip_methods_details[ $method_tag ] );
										
					// Now we are ready to try and test for the file existence
					$result = $zipper->get_comment( $zip_file );
					
					// Will be false if we couldn't set the comment
					if ( is_string ( $result ) ) {
					
						// Format has changed and no longer encoding as htmlemtities when setting comment
						// For older backups may need to remove encoding - action _should_ be null if N/A
						// Only spanner would be if someone had put an entity in their comment but that is
						// really an outsider and in any case the correction is simply to edit and resave
						// TODO: Remove this when new format has been in use for some time
						$result = html_entity_decode( $result );
					
						// Must assume that comment was retrieved ok
						unset( $zipper );
						
						// Return raw comment as-is with no processing if specified.
						if ( true === $raw_comment ) {
							return $result;
						}
						
						// Handle delimination. Decode $result if json decoded (associative array mode).
						$start_deliminator = strpos( $result, 'MetaData:' );
						$end_deliminator = strpos( $result, 'MetaData-End:' );
						if ( ( false !== $start_deliminator ) && ( false !== $end_deliminator ) ) { // Found both deliminators.
							$result = substr( $result, $start_deliminator+9, $end_deliminator-9 );
							if ( NULL === ( $decoded_result = json_decode( $result, true ) ) ) { // Json decode failed so return string.
								return $result;
							} else { // Json decode success so returning variable (should be an array most likely).
								return $decoded_result;
							}
							
						}
						
						// No deliminators found if made it to this point so assuming plain text legacy comment (or deliminators missing/corrupt).
						
						// We have to return here because we cannot break out of foreach
						return $result;
						
					} else {
					
						// The zipper encountered an error so we need to drop through and loop round to try another
						// We'll not process the result here, just drop through silently (the method will have logged it)			
						unset( $zipper );
						
					}
				
				}
				
			}
			
			// If we got this far then couldn't get a comment at all - either no available method or all method failed
			$this->log( 'details', sprintf( __('Unable to get comment in file %1$s: No compatible zip method found or all methods failed.','it-l10n-backupbuddy' ), $zip_file ) );

			return false;
				
		}
			
	} // End class

}
