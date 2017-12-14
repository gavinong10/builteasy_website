<?php



/*	pluginbuddy class
 *	
 *	Framework for handling all plugin functioality, architecture, etc.
 *	$settings variable is expected to be in the same scope of this file and previously populated with all plugin settings.
 *	
 *	@author Dustin Bolton
 */
class pb_backupbuddy {
	private static $pbframework_version = '1.0.28';
	
	
	// ********** PUBLIC PROPERTIES **********
	public static $start_time;					// microtime when init() was first run.
	
	
	public static $options;						// Stores all options for plugin that will change such as user defined settings.
	public static $ui;							// User interface class for rapidly constructing WP-styled GUIs. REMOVED. Now added at runtime when init_class_controller() called
	public static $filesystem;					// Class for manipulating & interfacing file system.
	public static $format;						// Class for formatting data or text in human friendly forms.
	public static $classes = array();			// Array holder for user-defined classes needed globally by plugin. Set/get with $class['class_slug'].
	public static $variables = array();			// Array holder for user-defined variables needed globally by plugin. Set/get with $variables['var_name']. Useful for things such as an instance counter that increments.
	
	
	// ********** PRIVATE PROPERTIES **********
	
	
	
	private static $_settings = array (			// Default framework settings for this plugin. NOT the same as options. Access via self::settings().
		'slug'				=>		'',
		'series'			=>		'',
		'default_options'	=>		'',
		'log_serial' 		=>  	'',
		'init'				=>		'',
	);
	private static $_page_settings;				// Holds admin page settings for adding to the admin menu on a hook later.
	public static $_status_serial = '';			// Serial for writing the status for this page load. String.
	private static $_has_flushed = false;		/// Whether or not flush() has been called yet or not.

	// Controller objects. See: /controllers/ directory.
	private static $_actions;					// Controller for WordPress actions.
	public static $_ajax;						// Controller for WordPress AJAX actions.
	private static $_cron;						// Controller for WordPress scheduled crons.
	private static $_dashboard;					// Controller for WordPress admin dashboard items.
	private static $_filters;					// Controller for WordPress filters.
	private static $_shortcodes;				// Controller for WordPress shortcodes.
	//private static $_widgets;					// Controller for WordPress widgets.
	private static $_pages;						// Controller for WordPress pages. See /controllers/pages/ directory.
	
	// Misc variables.
	private static $_plugin_path;				// Local path to plugin. Ex: /users/pb/www/wp-content/plugins/my_plugin (no trailing slash) @see pluginbuddy:plugin_path()
	private static $_plugin_url;				// URL to plugin directory. Ex: http://pluginbuddy.com/wp-content/plugins/my_plugin/ (with trailing slash) @see self::plugin_url()
	private static $_self_link;					// Returns URL to the current admin page if on a plugin page. Ex: http://pluginbuddy.com/wp-admin/index.php?page=pb_myplugin @see self::page_link()
	//private static $_callbacks;				// DISABLED. Using create_function() to bypass need for this. Currently only holding callback for the admin menu . @see pluginbuddy_callbacks class
	public static $_dashboard_widgets;   		// Holds tag and title for unconstructed dashboard widgets temporarily.
	public static $_updater;					// Contains updater object (if enabled) of the most up to date updater found. Populated on init hook.
	private static $_skiplog;					// if unable to write to log then skip all future attempts.
	
	
	// ********** FUNCTIONS **********
	
	
	
	/*	self::init()
	 *	
	 *	Constructor for this static class. Called from the plugin's init (or other defined in pb_backupbuddy::settings( 'init' )) file.
	 *	
	 *	@param		array		$pluginbuddy_settings		Array of plugin settings such as slug, default options,
	 *														plugin-specific options, etc.
	 *	@return		null
	 */
	public static function init( $pluginbuddy_settings, $pluginbuddy_init = 'init.php' ) {
		self::$start_time = microtime( true );
		self::$_settings = array_merge( (array)self::$_settings, (array)$pluginbuddy_settings ); // Merge settings over framework defaults.
		
		if ( function_exists( 'plugin_dir_url' ) ) { // URL and path functions available (not in ImportBuddy but inside WordPress).
			self::$_plugin_path = rtrim( plugin_dir_path( dirname( __FILE__ ) ), '/\\' );
			self::$_plugin_url = rtrim( plugin_dir_url( dirname( __FILE__ ) ), '/\\' );
		} else { // Generate URL and paths old way (old WordPress versions or inside ImportBuddy).
			self::$_plugin_path = dirname( dirname( __FILE__ ) );
			$relative_path = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', self::$_plugin_path ) ), '\\\/' );
			if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
				self::$_plugin_url = 'importbuddy'; // Relative importbuddy path.
			} else { // Normal full path.
				self::$_plugin_url = site_url() . '/' . ltrim( $relative_path, '/' );
				if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) { // Handle https URLs properly.
					self::$_plugin_url = str_replace( 'http://', 'https://', self::$_plugin_url );
				}
			}
		}
		
		if ( isset( $_GET['page'] ) ) { // If in an admin page then append page querystring.
			$arr = explode( '?', $_SERVER['REQUEST_URI'] ); // avoid reference error by setting here.
			self::$_self_link = array_shift( $arr ) . '?page=' . htmlentities( $_GET['page'] );
			unset( $arr );
		}
		
		// Set the init file.
		self::$_settings['init'] = $pluginbuddy_init;
		
		// filesystem class controller.
		if ( isset( self::$_settings['modules']['filesystem'] ) && ( self::$_settings['modules']['filesystem'] === true ) ) {
			self::init_class_controller( 'filesystem' );
		}
		// format class controller.
		if ( isset( self::$_settings['modules']['format'] ) && ( self::$_settings['modules']['format'] === true ) ) {
			self::init_class_controller( 'format' );
		}
		
		if ( is_admin() ) {
			
			// Load UI system.
			self::init_class_controller( 'ui' );
			
			// Load activation hook if in admin and activation file exists.
			if ( file_exists( self::$_plugin_path . '/controllers/activation.php' ) ) {
				$escaped_plugin_path = preg_replace( '#^\\\\\\\\#', '\\\\\\\\\\\\\\\\', self::$_plugin_path ); // Replace a path starting with \\ to be \\\\ so that when create_function parses the backslash it will return back to \\.
				register_activation_hook( self::$_plugin_path . '/' . pb_backupbuddy::settings( 'init' ), create_function( '', "require_once('" . $escaped_plugin_path . "/controllers/activation.php');" ) ); // Run some code when plugin is activated in dashboard.
			}
		} else { // Public side.
			// Do nothing.
		}
		
	} // End init().
	
	
	
	/*	self::plugin_path()
	 *	
	 *	Returns local path to plugin. Ex: /users/pb/www/wp-content/plugins/my_plugin (no trailing slash)
	 *	
	 *	@return		string		Plugin path directory (no trailing slash).
	 */
	public static function plugin_path() {
		return self::$_plugin_path;
	} // End plugin_path().
	
	
	
	/*	self::plugin_url()
	 *	
	 *	Returns URL to plugin directory. Ex: http://pluginbuddy.com/wp-content/plugins/my_plugin/ (with trailing slash)
	 *	
	 *	@return		string		Plugin path URL (with trailing slash).
	 */
	public static function plugin_url() {
		return self::$_plugin_url;
	} // End plugin_url().
	
	
	
	/*	self::page_url()
	 *	
	 *	Returns URL to the current admin page if on a plugin page. Ex: http://pluginbuddy.com/wp-admin/index.php?page=pb_myplugin
	 *	
	 *	@return		string		Plugin page URL (with trailing slash).
	 */
	public static function page_url() {
		return self::$_self_link;
	} // End page_url().
	
	
	
	/*	self::ajax_url()
	 *	
	 *	Returns the admin-side AJAX URL. Properly handles prefixing and everything for PB framework.
	 *	Todo: provide non-admin-side functionality?
	 *	
	 *	@param		string		$tag		Tag / slug of AJAX.
	 *	@return		string					URL for AJAX.
	 */
	public static function ajax_url( $tag ) {
		return admin_url('admin-ajax.php') . '?action=pb_' . self::settings( 'slug' ) . '_backupbuddy&function=' . $tag;
	} // End ajax_url().
	
	
	
	/*	self::settings()
	 *	
	 *	Retrieves misc plugin settings both passed from the init file ( defined in pb_backupbuddy::settings( 'init' ) ) into self::$_settings
	 *	and also plugin settings defined in the init file ( defined in pb_backupbuddy::settings( 'init' ) ) header including:
	 *	name, title, description, author, authoruri, version, pluginuri OR url, textdomain, domainpath, network.
	 *	@see self::init()
	 *	
	 *	@param		string		$type		Type of setting to retrieve.
	 *	@return		mixed					Value associated with that settings. Null if not found.
	 */
	public static function settings( $type ) {
		//if ( !self::blank( @self::$_settings[$type] ) ) { // Return value if it already exists.
		if ( isset( self::$_settings[$type] ) ) {
			return self::$_settings[$type];
		}
		
		// The variable does not exist so check to see if it can be extracted from the plugin's header.
		//if ( self::blank( @self::$_settings['name'] ) ) {
		if ( !isset( self::$_settings['name'] ) || ( self::$_settings['name'] == '' ) ) {
			if ( !function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$info = array_change_key_case( get_plugin_data( self::$_plugin_path . '/' . pb_backupbuddy::settings( 'init' ), false, false ), CASE_LOWER );
			$info['url'] = $info['pluginuri'];
			unset( $info['pluginuri'] );
			self::$_settings = array_merge( self::$_settings, $info );
		}

		// Try to return setting otherwise throw an error.
		if ( isset( self::$_settings[$type] ) ) {
			return self::$_settings[$type];
		} else {
			return '{Unknown settings() variable `' . $type . '`}';
		}
	} // End settings().
	
	
	
	/*	self::blank()
	 *	
	 *	Returns whether a not a variable is blank (empty string, null, undefined) or not.
	 *	Be sure to suppress errors if using this function where indexes may be non-existant with @ sign.
	 *	
	 *	@param		mixed		Variable to determine if it is blank or not.
	 *	@return		boolean		True if variable is set and is not an empty string.
	 */
	public static function blank( $value ) {
		return empty( $value ) && !is_numeric( $value );
	} // End blank().
	
	
	
	/*	self::_POST()
	 *	
	 *	Returns $_POST value if available, else returns a blank. Prevents having to check isset first. Strips WP's added slashes.
	 *	
	 *	@param		string		Key of POST variable to check.
	 *	@return		mixed		Value of POST variable if set. If not set returns a blank string ''.
	 */
	public static function _POST( $value = null ) {
		if ( ( $value == '' ) || ( null == $value ) ) { // Requesting $_POST variable.
			if ( defined( 'PB_STANDALONE' ) && ( PB_STANDALONE === true ) && !get_magic_quotes_gpc() ) { // If in ImportBuddy mode AND magic quotes is not on, dont strip. WP escapes for us if magic quotes are off.
				return $_POST;
			}
			return stripslashes_deep( $_POST );
		} else {
			$postValue = '';
			if ( isset( $_POST[$value] ) ) {
				$postValue = $_POST[$value];
			}
			if ( defined( 'PB_STANDALONE' ) && ( PB_STANDALONE === true ) && !get_magic_quotes_gpc() ) { // If in ImportBuddy mode AND magic quotes is not on, dont strip. WP escapes for us if magic quotes are off.
				return $postValue;
			} else {
				return stripslashes_deep( $postValue ); // Remove WordPress' magic-quotes-style escaping of data.
			}
		}
	} // End _POST().
	
	
	
	/*	self::_GET()
	 *	
	 *	Returns $_POST value if available, else returns a blank. Prevents having to check isset first.
	 *	TODO: Do we need to stripslashes_deep() on GET vars also like POSTs?
	 *	
	 *	@param		string		Key of POST variable to check.
	 *	@return		mixed		Value of POST variable if set. If not set returns a blank string ''.
	 */
	public static function _GET( $value = '' ) {
		if ( ( $value == '' ) || ( null == $value ) ) { // Requesting $_GET variable.
			if ( defined( 'PB_STANDALONE' ) && ( PB_STANDALONE === true ) && !get_magic_quotes_gpc() ) { // If in ImportBuddy mode AND magic quotes is not on, dont strip. WP escapes for us if magic quotes are off.
				return $_GET;
			}
			return stripslashes_deep( $_GET );
		} else {
			$getValue = '';
			if ( isset( $_GET[$value] ) ) {
				$getValue = $_GET[$value];
			}
			if ( defined( 'PB_STANDALONE' ) && ( PB_STANDALONE === true ) && !get_magic_quotes_gpc() ) { // If in ImportBuddy mode AND magic quotes is not on, dont strip. WP escapes for us if magic quotes are off.
				return $getValue;
			} else {
				return stripslashes_deep( $getValue ); // Remove WordPress' magic-quotes-style escaping of data.
			}
		}
	} // End _GET().
	
	
	
	/*	self::get_group()
	 *
	 *	Grabs & returns a reference to a specified point in the options array.
	 *	Ex usage: $group = &self::get_group( 'groups#' . $_GET['edit'] );
	 *	
	 *	@param		string	$savepoint_root		Path in the array to return a reference to.
	 *											Ex: groups#5 will grab self::$options['groups'][5]
	 *	@return		mixed						Value within the array at the specified point.
	 *											Can be used as a reference. See example in description.
	 *											NOTE: Returns false if not found.
	 */
	public static function &get_group( $savepoint_root ) {
		if ( $savepoint_root == '' ) { // Root was requested.
			$return = &self::$options;
			return $return;
		}
		
		$savepoint_subsection = &self::$options;
		$savepoint_levels = explode( '#', $savepoint_root );
		foreach ( $savepoint_levels as $savepoint_level ) {
			if ( isset( $savepoint_subsection{$savepoint_level} ) ) {
				$savepoint_subsection = &$savepoint_subsection{$savepoint_level};
			} else {
				echo '{Error #4489045: Invalid array in path: `' . $savepoint_root . '`}';
				return false;
			}
		}
		
		return $savepoint_subsection;
	} // End get_group().
	
	
	
	/*	self::load()
	 *	
	 *	Loads the plugin options array containing all user-configurable options, etc.
	 *	Access options via self::$options. Bypasses WP options caching for reliability.
	 *	
	 *	@return		null
	 */
	public static function load() {
		if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) { // Standalone framework mode (outside WordPress).
			// Load options from file if it exists.
			$dat_file = ABSPATH . 'importbuddy/_settings_dat.php';
			if ( file_exists( $dat_file ) ) {
				$options = file_get_contents( $dat_file );
				
				// Unserialize data; If it fails it then decodes the obscufated data then unserializes it. (new dat file method starting at 2.0).
				if ( !is_serialized( $options ) || ( false === ( $return = unserialize( $options ) ) ) ) {
					// Skip first line.
					$second_line_pos = strpos( $options, "\n" ) + 1;
					$options = substr( $options, $second_line_pos );
					
					// Decode back into an array.
					$options = unserialize( base64_decode( $options ) );
				}
			} else { // No existing options. Empty options.
				$options = array();
			}
			
			// Merge defaults.
			$options = array_merge( (array)self::settings( 'default_options' ), $options );
			
			pb_backupbuddy::$options = $options;
			return;
		}
		
		self::$options = self::_get_option( 'pb_' . self::settings( 'slug' ) );
		
		// Merge defaults into temporary $options variable and save if it differs with the pre-merge options.
		if ( empty( self::$options ) ) {
			$options = (array)self::settings( 'default_options' );
		} else {
			$options = array_merge( (array)self::settings( 'default_options' ), (array)self::$options );
		}
		if ( self::$options !== $options ) {
			self::$options = $options;
			self::save();
		}
	} // End load().
	
	
	
	/*	self::_get_option()
	 *	
	 *	Bypasses WordPress options cache. Unfortunately there appears to be race condition issues with the built-in WP options system.
	 *	Used by load() function internally. Taken and modified from WordPress core.
	 *	@see load()
	 *	
	 *	@param		string		$option		Option name.
	 *	@param		mixed		$default	default = false; we do not use this.
	 *	@return		mixed					Saved option value.
	 */
	private static function _get_option( $option, $default = false ) {
		global $wpdb;

		$option = trim($option);
		if ( empty($option) )
			return false;

		if ( defined( 'WP_SETUP_CONFIG' ) )
			return false;

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );

		// Has to be get_row instead of get_var because of funkiness with 0, false, null values
		if ( is_object( $row ) ) {
			$value = $row->option_value;
		} else {
			$value = $default;
		}

		// If home is not set use siteurl.
		if ( 'home' == $option && '' == $value )
			return get_option( 'siteurl' );

		if ( in_array( $option, array('siteurl', 'home', 'category_base', 'tag_base') ) )
			$value = untrailingslashit( $value );

		$value = maybe_unserialize( $value );

		return $value;
	} // End get_option().
	
	
	
	/*	self::save()
	 *	
	 *	Save plugin options to database.
	 *	
	 *	@return		boolean			True if save succeeded, false otherwise.
	 */
	public static function save() {
		if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
			$options_content = base64_encode( serialize( pb_backupbuddy::$options ) );
			$result = file_put_contents( ABSPATH . 'importbuddy/_settings_dat.php', "<?php die('<!-- // Silence is golden. -->'); ?>\n" . $options_content );
			if ( $result === false ) {
				return false;
			} else {
				return true;
			}
		}
	
		add_site_option( 'pb_' . self::settings( 'slug' ), self::$options, '', 'no'); // 'No' prevents autoload if we wont always need the data loaded.
		return self::_update_option( 'pb_' . self::settings( 'slug' ), self::$options );
	} // End save().
	
	
	
	/*	self::_update_option()
	 *	
	 *	Bypasses WordPress built in update option cache. Taken from WordPress core and modified.
	 *	@see self::_get_option()
	 *	@see self::save()
	 *	
	 *	@param		string	$option			Option name.
	 *	@param		mixed	$newvalue		New value to save into option.
	 *	@return		boolean					True on success; false otherwise.
	 */
	private static function _update_option( $option, $newvalue ) {
		global $wpdb;

		$option = trim($option);
		if ( empty($option) )
			return false;

		$oldvalue = get_option( $option );
		if ( false === $oldvalue ) {
			return add_option( $option, $newvalue );
		} else {
			$newvalue = sanitize_option( $option, $newvalue );
			$newvalue = maybe_serialize( $newvalue );
			$result = $wpdb->update( $wpdb->options, array( 'option_value' => $newvalue ), array( 'option_name' => $option ) );
			
			if ( $result ) return true;
		}

		return false;
	} // End _update_option().
	
	
	
	/**
	 *	anti_directory_browsing()
	 *
	 *	Helps security by attempting to block directory browsing by creating
	 *	both index.htm files and .htaccess files turning browsing off.
	 *
	 *	@param		string		$directory		Full absolute pass to insert anti-directory-browsing files into. No trailing slash.
	 *	@param		bool		$deny_all		When true also enforce denying ALL web-based access to directory. default false
	 *	@return		boolean						True on success securing directory, false otherwise.
	 */
	public static function anti_directory_browsing( $directory = '', $die_on_fail = true, $deny_all = false, $suppress_alert = false ) {
		
		// Check directory exists & create if it doesn't.
		if ( !file_exists( $directory ) ) {
			if ( self::$filesystem->mkdir( $directory ) === false ) {
				$error = 'Error #9002: BackupBuddy unable to create directory `' . $directory . '`. Please verify write permissions for the parent directory `' . dirname( $directory ) . '` or manually create the specified directory & set permissions.';
				if ( $suppress_alert !== true ) {
					self::alert( $error, true, '9002' );
					pb_backupbuddy::status( 'error', $error );
				}
				if ( $die_on_fail === true ) {
					die( $error );
				}
				return false;
			}
		}
		
		// Check writable.
		if ( ! is_writable( $directory ) ) {
			$error = 'Error #9002d: BackupBuddy directory `' . $directory . '` is indicated as NOT being writable. Please verify write permissions for it and parent directories as applicable.';
			if ( $suppress_alert !== true ) {
				self::alert( $error, true, '9002' );
				pb_backupbuddy::status( 'error', $error );
			}
			if ( $die_on_fail === true ) {
				die( $error );
			}
			return false;
		}
		
		// .htaccess contents for denying.
		if ( true === $deny_all ) {
			$deny_all = "\ndeny from all";
		} else {
			$deny_all = '';
		}
		
		
		$error = '';
		
		// index.php
		if ( ! file_exists( $directory . '/index.php' ) ) {
			if ( false === @file_put_contents( $directory . '/index.php', '<html></html>' ) ) {
				$error .= 'Unable to write index.php file. ';
			}
		}
		
		// index.htm
		if ( ! file_exists( $directory . '/index.htm' ) ) {
			if ( false === @file_put_contents( $directory . '/index.htm', '<html></html>' ) ) {
				$error .= 'Unable to write index.htm file. ';
			}
		}
		
		// index.html
		if ( ! file_exists( $directory . '/index.html' ) ) {
			if ( false === @file_put_contents( $directory . '/index.html', '<html></html>' ) ) {
				$error .= 'Unable to write index.html file. ';
			}
		}
		
		// .htaccess if we aren't in the importbuddy script
		if ( ! file_exists( $directory . '/.htaccess' ) ) {
			if ( false === @file_put_contents( $directory . '/.htaccess', 'Options -Indexes' . $deny_all ) ) {
				$error .= 'Unable to write .htaccess file. ';
			}
		}
		
		if ( $error != '' ) { // Failure.
			if ( true !== $suppress_alert ) {
				$error = 'Error creating anti directory browsing security files in directory `' . $directory . '`. Please verify this directory\'s permissions allow writing & reading. Errors: `' . $error . '`.';
				self::alert( $error );
				pb_backupbuddy::status( 'error', $error );
			}
			if ( $die_on_fail === true ) {
				die( 'Script halted for security. Please verify permissions and try again.' );
			}
		} else { // Success.
			return true;
		}
	} // End anti_directory_browsing().
	
	
	
	/*	set_status_serial()
	 *	
	 *	Define a default serial for all subsequent status() calls.
	 *	
	 *	@param		string		$serial		Unique identifier to use as default serial.
	 *	@return		null
	 */
	public static function set_status_serial( $serial ) {
		
		self::$_status_serial = $serial;
		
		return;
		
	} // End set_status_serial().
	
	
	
	/*	add_status_serial()
	 *	
	 *	Add a serial for all subsequent status() calls to log to in addition to any currently logging serials.
	 *	
	 *	@param		string		$serial		Unique identifier to add to serials to log to.
	 *	@return		null
	 */
	public static function add_status_serial( $serial ) {
		
		pb_backupbuddy::status( 'details', 'Adding status serial `' . $serial . '`.' );
		if ( is_array( self::$_status_serial ) ) {
			self::$_status_serial[] = $serial;
		} else {
			self::$_status_serial = array( self::$_status_serial, $serial );
		}
		
		return;
		
	} // End add_status_serial().
	
	
	
	/*	remove_status_serial()
	 *	
	 *	Remove a serial for all subsequent status() calls to log to in addition to any currently logging serials.
	 *	
	 *	@param		string		$serial		Unique identifier to remove from serials to log to.
	 *	@return		null
	 */
	public static function remove_status_serial( $serial ) {
		
		if ( is_array( self::$_status_serial ) ) { // array
			foreach( self::$_status_serial as $i => $this_serial ) {
				if ( $this_serial == $serial ) {
					unset( self::$_status_serial[$i] );
					return;
				}
			}
		} else { // string
			if ( self::$_status_serial == $serial ) {
				self::$_status_serial == '';
			}
		}
		pb_backupbuddy::status( 'details', 'Removed status serial `' . $serial . '`.' );
		
		return;
		
	} // End remove_status_serial().
	
	
	
	/*	get_status_serial()
	 *	
	 *	Get current serial status logs are going to.
	 *	
	 *	@return		string		$serial		Current serial set.
	 */
	public static function get_status_serial() {
		
		return self::$_status_serial;
		
	} // End get_status_serial().
	
	
	
	/**
	 *	self::status()
	 *
	 *	Logs data to a CSV file. Optional unique serial identifier.
	 *	If a serial is passed then EVERYTHING will be logged to the specified serial file in addition to whatever (if anything) is logged to main status file.
	 *	Always logs to main status file based on logging settings whether serial is passed or not.
	 *	NOTE: When full logging is on AND a serial is passed, it will be written to a _sum_ text file instead of the main log file.
	 *
	 *	@see self::get_status().
	 *
	 *	@param	string			$type		Valid types: error, warning, details, message
	 *	@param	string			$text		Text message to log.
	 *	@param	string			$serial		Optional. Optional unique identifier for this plugin's message. Status messages are unique per plugin so this adds an additional unique layer for retrieval.
	 *										If self::$_status_serial has been set by set_status_serial() then it will override if $serial is blank.
	 *	@return	null
	 */
	public static function status( $type, $message, $serials = '', $js_mode = false, $echoNotWrite = false ) {
		
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
		
		if ( ( self::$_status_serial != '' ) && ( $serials == '' ) ) {
			$serials = self::$_status_serial;
		}
		
		if ( defined( 'BACKUPBUDDY_WP_CLI' ) && ( true === BACKUPBUDDY_WP_CLI ) ) {
			if ( class_exists( 'WP_CLI' ) ) {
				WP_CLI::line( $type . ' - ' . $message );
			}
		}
		
		// Make sure we have a unique log serial for all logs for security.
		if ( ! isset( self::$options['log_serial'] ) || ( self::$options['log_serial'] == '' ) ) {
			self::$options['log_serial'] = self::random_string( 15 );
			self::save();
		}
		
		if ( ! is_array( $serials ) ) {
			$serials = array( $serials );
		}
		
		// Calculate log directory.
		$log_directory = backupbuddy_core::getLogDirectory(); // Also handles when within importbuddy.
		
		// Prepare directory for log files. Return if unable to do so.
		if ( true === self::$_skiplog ) { // bool true so skip.
			return;
		} elseif( false !== self::$_skiplog ) { // something other than bool false so check directory before proceeding.
			if ( true !== self::anti_directory_browsing( $log_directory, $die_on_fail = false, $deny_all = false, $suppress_alert = true ) ) { // Unable to secure directory. Fail.
				self::$_skiplog = true;
				return;
			} else {
				self::$_skiplog = false;
			}
		}
		
		foreach( $serials as $serial ) {
			
			// ImportBuddy always write to main status log.
			if ( defined( 'PB_IMPORTBUDDY' ) && ( PB_IMPORTBUDDY === true ) ) { // IMPORTBUDDY
				
				$write_serial = false;
				$write_main = true;
				$main_file = $log_directory . 'status-' . self::$options['log_serial'] . '.txt';
				
			} else { // STANDALONE.
				
				// Determine whether writing to main extraneous log file.
				$write_main = false;
				if ( self::$options['log_level'] == 0 ) { // No logging.
						$write_main = false;
				} elseif ( self::$options['log_level'] == 1 ) { // Errors only.
					if ( $type == 'error' ) {
						$write_main = true;
						self::log( '[' . $serial . '] ' . $message, 'error' );
					}
				} else { // Everything else.
					$write_main = true;
					self::log( '[' . $serial . '] ' . $message, $type );
				}
				
				// Determine which normal status log files to write.
				if ( $serial != '' ) {
					$write_serial = true;
					$write_main = true;
					$main_file = $log_directory . 'status-' . $serial . '_sum_' . self::$options['log_serial'] . '.txt';
				} else {
					$write_serial = false;
					$write_main = false;
				}
				
			}
			
			
			// Function for writing actual log CSV data. Used later.
			if ( ! function_exists( 'write_status_line' ) ) {
				function write_status_line( $file, $content_array, $echoNotWrite ) {
					$writeData = json_encode( $content_array ) . PHP_EOL;
					if ( true === $echoNotWrite ) { // echo data instead of writing to file. used by ajax when checking status log and needing to prepend before log.
						echo $writeData;
					} else {
						//$delimiter = '|~|';
						if ( false !== ( $file_handle = @fopen( $file, 'a') ) ) { // Append mode.
							//fputcsv ( $file_handle , $content_array );
							//@fwrite( $file_handle, trim( implode( $delimiter, $content_array ) ) . PHP_EOL );
							@fwrite( $file_handle, $writeData );
							@fclose( $file_handle );
						} else {
							//pb_backupbuddy::alert( 'Unable to open file handler for status file `' . $file . '`. Unable to write status log.' );
						}
					}
				}
			}
			
			$content_array = array(
				'event'		=> $type,
				'time'		=> pb_backupbuddy::$format->localize_time( time() ), // Time this happened.
				'u'			=> substr((string)microtime(), 2, 2),
				'run'		=> sprintf( "%01.2f", round ( microtime( true ) - self::$start_time, 2 ) ), // Elapsed PHP time.
				'mem'		=> sprintf( "%01.2f", round( memory_get_peak_usage() / 1048576, 2 ) ), // Memory used.	
				'data'		=> str_replace( chr(9), '   ', $message ), // Body of the message.
			);
			
			/********** MAIN LOG FILE or SUM FILE **********/
			if ( $write_main === true ) { // WRITE TO MAIN LOG FILE or SUM FILE.
				write_status_line( $main_file, $content_array, $echoNotWrite );
			}
			
			/********** SERIAL LOG FILE **********/
			if ( $write_serial === true ) {
				$serial_file = $log_directory . 'status-' . $serial . '_' . self::$options['log_serial'] . '.txt';
				write_status_line( $serial_file, $content_array, $echoNotWrite );
			}
			
			// Output importbuddy status log to screen.
			global $pb_backupbuddy_js_status;
			if ( ( defined( 'PB_IMPORTBUDDY' ) || ( isset( $pb_backupbuddy_js_status ) && ( $pb_backupbuddy_js_status === true ) ) ) && ( 'true' != pb_backupbuddy::_GET('deploy') ) ) { // If importbuddy, js mode, and not a deployment.
				echo '<script>pb_status_append( ' . json_encode( $content_array ) . ' );</script>' . "\n";
				pb_backupbuddy::flush();
			}
			
		} // end foreach $serials.
		
		
	} // End status().
		
	
	
	/*	self::get_status()
	 *	
	 *	Gets all status information logged via status(). Returns an array of arrays with logged data.
	 *	Return format: array(
	 *					array( TIMESTAMP, TIME_IN, PEAK_MEMORY, TYPE, MESSAGE ),
	 *					array( TIMESTAMP, TYPE, MESSAGE ),
	 *				)
	 *
	 *	@see self::status().
	 *	
	 *	@param		string		$serial					Unique identifier. Retrieves a subset of logged information based on this unique ID that was passed to status() when logging.
	 *	@param		boolean		$clear_retrieved		Default: true. On true status information will be purged after retrieval.
	 *	@param		boolean		$erase_retrieved		Default: true. Whether or not to delete log file on retrieval. NOTE: PCLZip can NOT lose files mid-backup so log files cannot delete mid-zip.
	 *	@param		boolean		$hide_getting_status	Default: false. Whether or not to output status retrieval message.
	 *	@return		array								Array of arrays.  Each sub-array contains three values: timestamp, type of message, and the message itself. See function description for details. Empty array if non-existing log.
	 */
	public static function get_status( $serial = '', $clear_retrieved = true, $erase_retrieved = true, $hide_getting_status = false ) {
		//$delimiter = '|~|';
		
		// Calculate log directory.
		$log_directory = backupbuddy_core::getLogDirectory(); // Also handles when importbuddy.
		
		$status_file = $log_directory . 'status-';
		if ( $serial != '' ) {
			$status_file .= $serial . '_';
		}
		$status_file .= self::$options['log_serial'] . '.txt';
		
		if ( ! file_exists( $status_file ) ) {
			return array(); // No log.
		}
		
		if ( $hide_getting_status === false ) {
			self::status( 'details', 'Getting status for serial `' . $serial . '`. Clear: `' . ( $clear_retrieved ? 'true' : 'false' ) . '`', $serial );
		}
		
		if ( false !== ( $fh = @fopen( $status_file, 'r') ) ) { // Read write mode.
			$status_lines = array();
			while ( false !== ( $status_line = fgets( $fh ) ) ) {
				/*
				if ( stristr( $status_line, $delimiter ) ) { // Deliminator in line.
					$status_lines[] = explode( $delimiter, trim( $status_line ) );
				} else { // No deliminator. Just print line with blank values.
					$status_lines[] = array( 0,0,0,'unknown', trim( $status_line ) );
				}
				*/
				$status_lines[] = $status_line;
			}
			fclose( $fh );
			
			if ( $clear_retrieved === true ) {
				file_put_contents( $status_file, '' );
			}
			
			if ( $erase_retrieved === true ) {
				@unlink( $status_file ); // todo: catch errors on this? supress?
			}
			
			return $status_lines;
		} else {
			//self::alert( 'Unable to open file handler for status file `' . $status_file . '`. Unable to write status log.' );
		}
	} // End get_status().
	
	
	
	/**
	 *	status_box()
	 *
	 *	Displays a textarea for placing status text into.
	 *
	 *	@param			$default_text	string		First line of text to display.
	 *	@param			boolean			$hidden		Whether or not to apply display: none; CSS.
	 *	@return							string		HTML for textarea.
	 */
	public static function status_box( $default_text = '', $hidden = false ) {
		define( 'PB_STATUS', true ); // Tells framework status() function to output future logging info into status box via javascript.
		$return = '<textarea readonly="readonly" id="pb_backupbuddy_status" wrap="off"';
		if ( $hidden === true ) {
			$return .= ' style="display: none; "';
		}
		$return .= '>' . $default_text . '</textarea>';
		
		return $return;
	} // End status_box().
	
	
	
	/**
	 *	set_greedy_script_limits()
	 *
	 *	Sets greedy script limits to help prevent timeouts, running out of memory, etc.
	 *
	 *	@return		null
	 *
	 */
	public static function set_greedy_script_limits( $supress_status = false )  {
	
		$requested_socket_timeout = 60 * 60 * 2;
		$requested_execution_time = 60 * 60 * 2;
		
		// Don't abort script if the client connection is lost/closed
		@ignore_user_abort( true );
		
		// Set socket timeout to requested period.
		@ini_set( 'default_socket_timeout', $requested_socket_timeout );
		
		
		pb_backupbuddy::status( 'details', 'Checking max PHP execution time settings.' );
		// Set maximum execution time to requested period if not already better than that
		// See if we can get a current value (of any sort)
		if ( false === ( $original_execution_time = @ini_get( 'max_execution_time' ) ) ) {
			$original_execution_time = 'Unknown';
		}
		
		// Check if we need to try and set/increase
		if ( is_numeric( $original_execution_time ) && ( ( 0 == $original_execution_time ) || ( $requested_execution_time <= $original_execution_time ) ) ) {
			// There is no need to change max_execution_time
			if ( false === $supress_status ) {
				if ( false === ( $configured_execution_time = @get_cfg_var( 'max_execution_time' ) ) ) {
					$configured_execution_time = 'Unknown';
				}
				if ( false === ( $current_execution_time = @ini_get( 'max_execution_time' ) ) ) {
					$current_execution_time = 'Unknown';
				}
				self::status( 'details', __( 'Maximum PHP execution time was not modified', 'it-l10n-backupbuddy' ) );
				self::status( 'details', sprintf( __( 'Reported PHP execution time - Configured: %1$s; Original: %2$s; Current: %3$s', 'it-l10n-backupbuddy' ), $configured_execution_time, $original_execution_time, $current_execution_time ) );
			}
		} else { // Either not a numeric value or we need to try and increase
			
			if ( isset( pb_backupbuddy::$options['set_greedy_execution_time'] ) && ( '1' == pb_backupbuddy::$options['set_greedy_execution_time'] ) ) {
				if ( false === $supress_status ) {
					self::status( 'details', sprintf( __( 'Attempting to set PHP execution time to %1$s', 'it-l10n-backupbuddy' ), $requested_execution_time ) );
				}
				@set_time_limit( $requested_execution_time );
			}  elseif ( false === $supress_status ) {// end setting max execution time
				pb_backupbuddy::status( 'details', 'Skipped attempting to override max PHP execution time based on settings.' );
			}
			
			if ( false === $supress_status ) {
				if ( false === ( $configured_execution_time = @get_cfg_var( 'max_execution_time' ) ) ) {
					$configured_execution_time = 'Unknown';
				}
				if ( false === ( $current_execution_time = @ini_get( 'max_execution_time' ) ) ) {
					$current_execution_time = 'Unknown';
				}
				self::status( 'details', sprintf( __( 'Reported PHP execution time - Configured: %1$s; Original: %2$s; Current: %3$s', 'it-l10n-backupbuddy' ), $configured_execution_time, $original_execution_time, $current_execution_time ) );
			}
		}
		
		
		
		
		// Set memory_limit to either the user defined (WordPress defaulted) or over-ridden value
		// Need to get the original value here as we will be updating it
		if ( false === ( $original_memory_limit = @ini_get( 'memory_limit' ) ) ) {
			$original_memory_limit = 'Unknown';
		}

		// Need to check if we are running outside of WordPress in which case we don't try and change anything
		// but just report the memory_limit values. The user will have to update config if necessary because
		// there is no other mechanism to set the valid memory_limit.
		// If we are running under WordPress then need a little fakery for earlier versions.
		if ( ! defined( 'PB_STANDALONE' ) || ( defined( 'PB_STANDALONE' ) && ( false === PB_STANDALONE ) ) ) {	
			// Note: WP_MAX_MEMORY_LIMIT was introduced WP3.2 so we need to fake it if constant not already defined
			// Use the default value that WordPress uses if the user hasn't defined it
			if ( ! defined( 'WP_MAX_MEMORY_LIMIT' ) ) {
				define( 'WP_MAX_MEMORY_LIMIT', '256M' );
			}
			@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );
			if ( false === $supress_status ) {
				self::status( 'details', sprintf( __( 'Attempted to set PHP memory limit to user defined WP_MAX_MEMORY_LIMIT (%1$s) or over-ridden value', 'it-l10n-backupbuddy' ), WP_MAX_MEMORY_LIMIT ) );
			}
		}
		if ( false === $supress_status ) {
			if ( false === ( $configured_memory_limit = @get_cfg_var( 'memory_limit' ) ) ) {
				$configured_memory_limit = 'Unknown';
			}
			if ( false === ( $current_memory_limit = @ini_get( 'memory_limit' ) ) ) {
				$current_memory_limit = 'Unknown';
			}
			self::status( 'details', sprintf( __( 'Reported PHP memory limits - Configured: %1$s; Original: %2$s; Current: %3$s', 'it-l10n-backupbuddy' ), $configured_memory_limit, $original_memory_limit, $current_memory_limit ) );
		}

	} // End set_greedy_script_limits().
	
	
	
	/**
	 *	self::log()
	 *
	 *	Logs to a text file depending on settings.
	 *	0 = none, 1 = errors only, 2 = errors + warnings, 3 = debugging (all kinds of actions)
	 *
	 *	@param	string	$text		Text to log.
	 *	@param	string	$log_type	Valid options: error, warning, all (default so may be omitted).
	 *	@return	null
	 */
	public static function log( $text, $log_type = 'all' ) {
		if ( defined( 'PB_DEMO_MODE' ) || !isset( self::$options['log_level'] ) || ( self::$options['log_level'] == 0 ) ) { // No logging in this plugin or disabled.
			return;
		}
		
		$write = false;
		if ( self::$options['log_level'] == 1 ) { // Errors only.
			if ( $log_type == 'error' ) {
				$write = true;
			}
		} else { // All logging (debug mode).
			$write = true;
		}
		
		if ( $write === true ) {
			if ( !isset( self::$options['log_serial'] ) ) {
				self::$options['log_serial'] = self::random_string( 15 );
				self::save();
			}
			$fh = @fopen( backupbuddy_core::getLogDirectory() . 'log-' . self::$options['log_serial'] . '.txt', 'a');
			if ( $fh ) {
				if ( function_exists( 'get_option' ) ) {
					$gmt_offset = get_option( 'gmt_offset' );
				} else {
					$gmt_offset = 0;
				}
				fwrite( $fh, '[' . date( 'M j, Y H:i:s ' . $gmt_offset, time() + ( $gmt_offset * 3600) ) . '-' . $log_type . '] ' . $text . "\n" );
				fclose( $fh );
			}
		}
	} // End log().
	
	
	
	/*	self::random_string()
	 *	
	 *	Generate a random string of characters.
	 *	
	 *	@param		
	 *	@return		
	 */
	public static function random_string( $length = 32, $chars = 'abcdefghijkmnopqrstuvwxyz1234567890' ) {
		$chars_length = ( strlen( $chars ) - 1 );
		$string = $chars{rand(0, $chars_length)};
		for ( $i = 1; $i < $length; $i = strlen( $string ) ) {
			$r = $chars{rand(0, $chars_length)};
			if ( $r != $string{$i - 1} ) $string .=  $r;
		}
		return $string;
	} // End random_string().
	
	
	
	/**
	 *	self::video()
	 *
	 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
	 *	HTML is supposed within tooltips.
	 *
	 *	@param		string		$video_key		YouTube video key from the URL ?v=VIDEO_KEY_HERE
	 *	@param		string		$title			Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
	 *	@param		boolean		$echo_tip		Whether to echo the tip (default; true), or return the tip (false). (optional)
	 *	@return		string/null					If not echoing tip then the string will be returned. When echoing there is no return.
	 */
	public static function video( $video_key, $title = '', $echo_tip = true ) {
		self::init_class_controller( 'ui' ); // $ui class required pages controller and may not be set up if not in our own pages.
		return self::$ui->video( $video_key, $title, $echo_tip );
	} // End video().
	
	
	
	 /**
	 * pb_backupbuddy::enqueue_thickbox()
	 *
	 * Enqueues the required scripts / styles needed to use thickbox
	 *
	 * @return null
	 */
	public static function enqueue_thickbox() {
		self::init_class_controller( 'ui' ); // $ui class required pages controller and may not be set up if not in our own pages.
		return self::$ui->enqueue_thickbox();
	} // End enqueue_thickbox
	
	
	
	/**
	 *	self::alert()
	 *
	 *	Displays a message to the user at the top of the page when in the dashboard.
	 *
	 *	@param		string		$message		Message you want to display to the user.
	 *	@param		boolean		$error			OPTIONAL! true indicates this alert is an error and displays as red. Default: false
	 *	@param		int			$error_code		OPTIONAL! Error code number to use in linking in the wiki for easy reference.
	 *	@return		string/null					If not echoing alert then the string will be returned. When echoing there is no return.
	 */
	public static function alert( $message, $error = false, $error_code = '' ) {
		self::init_class_controller( 'ui' ); // $ui class required pages controller and may not be set up if not in our own pages.
		self::$ui->alert( $message, $error, $error_code );
	} // End alert().
	
	
	
	// Dismissable alert system. Uses alert().
	public static function disalert( $unique_id, $message ) {
		self::init_class_controller( 'ui' ); // $ui class required pages controller and may not be set up if not in our own pages.
		self::$ui->disalert( $unique_id, $message );
	} // End disalert().
	
	
	
	/**
	 *	self::tip()
	 *
	 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
	 *	HTML is supposed within tooltips.
	 *
	 *	@param		string		$message		Actual message to show to user.
	 *	@param		string		$title			Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
	 *	@param		boolean		$echo_tip		Whether to echo the tip (default; true), or return the tip (false). (optional)
	 *	@return		string/null					If not echoing tip then the string will be returned. When echoing there is no return.
	 */
	public static function tip( $message, $title = '', $echo_tip = true ) {
		self::init_class_controller( 'ui' ); // $ui class required pages controller and may not be set up if not in our own pages.
		return self::$ui->tip( $message, $title, $echo_tip );
	} // End tip().
	
	
	
	/*	self::add_page()
	 *	
	 *	Adds a page into the admin. Stores menu items to add in self::$_page_settings array. Registers callback to register_admin_menu() with WordPress to actually set up the pages.
	 *	@see self::register_admin_menu()
	 *	
	 *	@param		string		$parent_slug		Slug of the parent menu item to go under. If a series use `SERIES` for the value to automatically handle the series. PB prefix automatically applied unless $slug_prefix overrides.
	 *	@param		string		$page_slug			Slug for this page. PB prefix automatically applied unless $slug_prefix overrides.
	 *	@param		string		$page_title			Title of the page. If this menu item has no parent this can be an array of TWO titles. The root menu and the first submenu item that links to the same place.
 	 *	@param		string		$capability			Capability required to access page. Default: activate_plugins.
 	 *	@param		string		$icon				Menu icon graphic. Automatically prefixes this value with the full URL to plugin's images directory. Default: icon_16x16.png.
 	 *	@param		string		$slug_prefix		Prefix to use with this menu. Override if needing to add menu under another plugin or core menus. Default: DEFAULT.
 	 *	@param		int			$position			Priority on where in the menu to add this. By default it is added to the bottom of the menu. It's possible to overwrite another menu item if this number matches. Use caution. Default: null.
	 *	@return		null
	 */
	public static function add_page( $parent_slug, $page_slug, $page_title, $capability = 'activate_plugins', $icon = 'icon_menu_16x16.png', $slug_prefix = 'DEFAULT', $position = NULL ) {
		if ( $slug_prefix == 'DEFAULT' ) {
			$slug_prefix = 'pb_' . self::settings( 'slug' ) . '_';
		}
		
		if ( !is_object( self::$_pages ) ) {
			self::_init_core_controller( 'pages' );
			
			if ( is_network_admin() ) { // Multisite installation admin; uses different hook.
				add_action( 'network_admin_menu', create_function( '', 'pb_' . self::settings( 'slug' ) . '::register_admin_menu();' ) );
			} else { // Standalone admin.
				add_action( 'admin_menu', create_function( '', 'pb_' . self::settings( 'slug' ) . '::register_admin_menu();' ) );
			}
		}
		
		self::$_page_settings[] = array(
			'parent'  =>  $parent_slug,
			'slug'   =>  $page_slug,
			'title'   =>  $page_title,
			'capability' =>  $capability,
			'icon'   =>  $icon,
			'slug_prefix' =>  $slug_prefix,
			'position'  =>  $position,
		);
	} // End add_page().	
	
	
	/*	register_admin_menu()
	 *	
	 *	Internal callback for actually registering the menu items into WordPress. Registers pages defined by self::add_page().
	 *	@see self::add_page()
	 *	
	 *	@return		null
	 */
	public static function register_admin_menu() {
		if ( !self::blank( self::$_settings['series'] ) ) { // SERIES
			$series_slug = 'pb_' . self::$_settings['series'];
			// We need to see first if this series' root menu has been created by a plugin yet.
			global $menu;
			$found_series = false;
			foreach ( $menu as $menus => $item ) { // Loop through existing menu items looking for our series.
				if ( $item[0] == $series_slug ) {
					$found_series = true;
				}
			}
			if ( $found_series === false ) { // Series root menu does not exist; create it.
				add_menu_page( self::$_settings['series'] . ' Getting Started', self::$_settings['series'], 'activate_plugins', $series_slug, array( &self::$_pages, 'getting_started' ), self::plugin_url() . '/images/series_icon_16x16.png' ); // , $page['position']
				add_submenu_page( $series_slug, self::$_settings['series'] . ' Getting Started', 'Getting Started', 'activate_plugins', $series_slug, array( &self::$_pages, 'getting_started' ) );
			}
			
			// Register for getting started page.
			global $pluginbuddy_series;
			if ( !isset( $pluginbuddy_series[ self::$_settings['series'] ] ) ) {
				$pluginbuddy_series[ self::$_settings['series'] ] = array();
			}
			
			// Add this plugin into global series variable.
			$pluginbuddy_series[ self::$_settings['series'] ][ self::$_settings['slug'] ] = array(
				'path'		=>	self::plugin_path(),
				'name'		=>	self::settings( 'name' ),
				'slug'		=>	self::settings( 'slug' ),
			);
		}
		
		// Add all registered pages for this plugin.
		foreach ( self::$_page_settings as $page ) {
			$menu_slug = $page['slug_prefix'] . $page['slug'];
			if ( $page['parent'] == 'SERIES' ) { // Adding page into series.
				$parent_slug = 'pb_' . self::$_settings['series'];
				if ( self::blank( self::$_settings['series'] ) ) { // No series set but menu is registered into a series.
					echo '{WARNING: Menu item registered into a series but no plugin series is defined.}';
				}
			} else { // Non-series page.
				$parent_slug = $page['slug_prefix'] . $page['parent'];
			}

			if ( is_array( $page['title'] ) ) {
				$page_title = $page['title'][0];
				$page_title_alt = $page['title'][1];
			} else { // Not an array so only one page title.
				$page_title = $page['title'];
				$page_title_alt = $page['title'];
			}
			
			// Calculate icon.
			if ( '' != $page['icon'] ) { // If icon specified then figure out url.
				$icon = $page['icon']; //self::plugin_url() . '/images/' . $page['icon'];
			} else { // No icon. Usually used when manually doing CSS for retina icon.
				$icon = '';
			}
			
			if ( self::blank( $page['parent'] ) ) { // Top-level menu.
				add_menu_page( $page_title, $page_title, $page['capability'], $menu_slug, array( &self::$_pages, $page['slug'] ), $icon, $page['position'] );
				add_submenu_page( $menu_slug, self::settings( 'name' ) . ' &lsaquo; ' . $page_title_alt, $page_title_alt, $page['capability'], $menu_slug, array( &self::$_pages, $page['slug'] ) ); // Allows naming of first submenu item differently from the parent. Else its auto created with same name.
			} else { // Sub-menu.
				add_submenu_page( $parent_slug, self::settings( 'name' ) . ' &lsaquo; ' . $page_title, $page_title, $page['capability'], $menu_slug, array( &self::$_pages, $page['slug'] ) );
			}
		}
	} // End register_admin_menu().
	
	
	
	/*	self::add_action()
	 *	
	 *	Registers a WordPress action. Action of the name $tag will call the method in /controllers/actions.php with the matching name.
	 *	
	 *	@param		string/array	$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@param		int				$priority			Integer priority number for the action.
	 *	@param		int				$accepted_args		Number of arguments this action may accept in its method.
	 *	@return		null
	 */
	public static function add_action( $tag, $priority = 10, $accepted_args = 1 ) {
		if ( !is_object( self::$_actions ) ) { self::_init_core_controller( 'actions' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_action( $tag, array( &self::$_actions, $callback_method ), $priority, $accepted_args );
	} // End add_action().
	
	
	
	/*	self::add_ajax()
	 *	
	 *	Registers a WordPress ajax action. Ajax action of the name $tag will call the method in /controllers/ajax.php with the matching name.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@return		null
	 */
	public static function add_ajax( $tag ) {
		if ( !is_object( self::$_ajax ) ) { self::_init_core_controller( 'ajax' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_action( 'wp_ajax_pb_' . self::settings( 'slug' ) . '_' . $tag, array( &self::$_ajax, $callback_method ) );
	} // End add_ajax().
	
	
	
	/*	self::add_cron()
	 *	
	 *	Registers a WordPress cron callback (technically an action). Cron action of the name $tag will call the method in /controllers/cron.php with the matching name.
	 *
	 *	@param		string/array	$tag				Tag / slug for the cron action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@param		int				$priority			Integer priority number for the cron action.
	 *	@param		int				$accepted_args		Number of arguments this action may accept in its method.
	 *	@return		null
	 */
	public static function add_cron( $tag, $priority = 10, $accepted_args_num = 1 ) {
		if ( !is_object( self::$_cron ) ) { self::_init_core_controller( 'cron' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_action( self::settings( 'slug' ) . '_' . $tag, array( &self::$_cron, $callback_method ), $priority, $accepted_args_num );
	} // End add_cron().
	
	
	
	/*	self::add_dashboard_widget()
	 *	
	 *	Registers a WordPress action. Action of the name $tag will call the method in /controllers/dashboard.php with the matching name.
	 *	
	 *	@param		string/array	$tag				Tag / slug for the action.
	 *	@param		string			$title				Dashboard widget title.
	 *	@param		string			$capability			Required capability to display. Also accepts `godmode` to only allow superadmins in multisite and admins in standalone.
	 *	@param		boolean			$accepted_args		Number of arguments this action may accept in its method.
	 *	@return		null
	 */
	public static function add_dashboard_widget( $tag, $title, $capability, $force_top = false ) {
		if ( !is_object( self::$_dashboard ) ) {
			self::$_dashboard_widgets = array(); // Init variable.

			self::_init_core_controller( 'dashboard' );
			
			if ( is_network_admin() ) { // Network admin.
				add_action( 'wp_network_dashboard_setup', array( &self::$_dashboard, 'register_widgets' ) );
			} else { // Normal admin.
				add_action( 'wp_dashboard_setup', array( &self::$_dashboard, 'register_widgets' ) );
			}
		}
		self::$_dashboard_widgets[] = array( 'tag' => $tag, 'title' => $title, 'capability' => $capability, 'force_top' => $force_top ); // Push into array to be later registered via dashboard controller's register_widgets function.
	} // End add_dashboard_widget().
	
	
	
	/*	self::add_filter()
	 *	
	 *	Registers a WordPress filter. Filter of the name $tag will call the method in /controllers/filters.php with the matching name.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the action. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@param		int				$priority			Integer priority number for the filter.
	 *	@param		int				$accepted_args		Number of arguments this filter may accept in its method.
	 */
	public static function add_filter( $tag, $priority = 10, $accepted_args = 1 ) {
		if ( !is_object( self::$_filters ) ) { self::_init_core_controller( 'filters' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_filter( $tag, array( &self::$_filters, $callback_method ), $priority, $accepted_args );
	} // End add_filter().
	
	
	
	/*	self::add_shortcode()
	 *	
	 *	Registers a WordPress shortcode. Shortcode of the name $tag will call the method in /controllers/shortcodes.php with the matching name.
	 *	
	 *	@param		string/array		$tag				Tag / slug for the shortcode. If an array the first item is the tag, the second is an optional custom callback method name.
	 *	@return		
	 */
	public static function add_shortcode( $tag ) {
		if ( !is_object( self::$_shortcodes ) ) { self::_init_core_controller( 'shortcodes' ); }
		if ( is_array( $tag ) ) { // If array then first param is tag, second param is custom callback method name.
			$callback_method = $tag[1];
			$tag = $tag[0];
		} else { // No custom method name so tag and callback method name are the same.
			$callback_method = $tag;
			if ( strpos( $tag, '.' ) !== false ) {
				echo '{Warning: Your tag contains disallowed characters. Tag names are equal to the PHP method that is called back so they must conform to PHP method name standards. For custom callback method names use an array for the tag parameter in the form: array( \'tag\', \'callback_name\' ).}';
			}
		}
		add_shortcode( $tag, array( &self::$_shortcodes, $callback_method ) );
	} // End add_shortcode().
	
	
	
	/*	self::init_class_controller()
	 *	
	 *	Registers the UI class into the pluginbuddy framework for pages. Registered on demand by pages controller.
	 *	@see pages controller
	 *
	 *	@return		null
	 */
	public static function init_class_controller( $class_slug ) {
		if ( !is_object( self::$$class_slug ) ) {
			$class_file = self::plugin_path() . '/pluginbuddy/classes/' . $class_slug . '.php';
			if ( file_exists( $class_file ) ) {
				require_once( $class_file );
				$class_name = 'pb_' . self::settings( 'slug' ) . '_' . $class_slug;
				self::$$class_slug = new $class_name();
			} else {
				echo '{Error: Missing class controller file `' . $class_file . '`.}';
			}
		}
	}
	
	
	
	/*	self::_init_core_controller()
	 *	
	 *	Initialize a core controller class (ex: pages, ajax, filters, etc) for pluginbuddy framework usage.
	 *	
	 *	@param		string		$name		Name of the controller to register. Valid controllers: actions, ajax, cron, dashboard, filters, shortcodes, pages.
	 *	@return		
	 */
	private static function _init_core_controller( $name ) {
		if ( !is_array( self::$options ) ) { self::load(); } // Assume we need plugin options need loaded if controllers are loaded for this session.
		
		require_once( self::$_plugin_path . '/controllers/' . $name . '.php' );
		$classname = 'pb_backupbuddy_' . $name;
		$internal_classname = '_' . $name;
		self::$$internal_classname = new $classname();
	} // End _init_core_controller().
	
	
	
	/*	self::nonce()
	 *	
	 *	Echos or returns a WordPress nonce for the framework. Handles prefixing. Use with forms for security. Verifies the user came from a WP generated page.
	 *	
	 *	@param		boolean		$echo		True: echos the none; false: returns nonce.
	 *	@return		null/string				Returns null or string based on $echo value.
	 */
	public static function nonce( $echo = true ) {
		return wp_nonce_field( 'pb_' . self::settings( 'name' ) . '-nonce', '_wpnonce', true, $echo );
	} // End nonce().
	
	
	
	/*	self::verify_nonce()
	 *	
	 *	Verifies the nonce submitted in form.
	 *	
	 *	@return		null/true		Script die()'s on failure, returns true on success.
	 */
	public static function verify_nonce() {
		check_admin_referer( 'pb_' . self::settings( 'name' ) . '-nonce' );
	} // End verify_nonce().
	
	
	
	/*	self::load_script()
	 *	
	 *	Load a JavaScript file into the page. Handles prefixed, enqueuing, etc.
	 *	
	 *	@param		string		$script			If a .js file is included then a file in the js directory is loaded; else loads a built-in named library script.
	 *											Ex: load_script( 'sort.js' ) will load /wp-content/plugins/my_plugin/js/sort.js; load_script( 'jquery' ) will load internal jquery library in WordPress if it exists.
	 *	@param		boolean		$core_script	If true scripts are loaded from /pluginbuddy/js/SCRIPT.js. Else scripts loaded from plugin's js directory.
	 *	@return		null
	 */
	public static function load_script( $script, $core_script = false ) {
		if ( strstr( $script, '.js' ) ) { // Loading a file specifically.
			if ( $core_script === true ) {
				if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
					$url_path = 'importbuddy/pluginbuddy/js/';
				} else {
					$url_path = self::$_plugin_url . '/pluginbuddy/js/';
				}
				$local_path = self::$_plugin_path . '/pluginbuddy/js/';
				$script_name = 'pb_' . self::settings( 'slug' ) . '_core_' . $script;
			} else {
				if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
					$url_path = 'importbuddy/js/';
				} else {
					$url_path = self::$_plugin_url . '/js/';
				}
				$local_path = self::$_plugin_path . '/js/';
				$script_name = 'pb_' . self::settings( 'slug' ) . '_' . $script;
			}
			
			if ( !wp_script_is( $script_name ) ) { // Only load script once.
				if ( file_exists( $local_path . $script ) ) { // Load our local script if file exists.
					wp_enqueue_script( $script_name, $url_path . $script, array(), pb_backupbuddy::settings( 'version' ) );
					wp_print_scripts( $script_name );
				} else {
					echo '{Error: Javascript file was set to load that did not exist: `' . $url_path . $script . '`}';
				}
			}
		} else { // Not a specific file.
			if ( !wp_script_is( $script, 'done' ) ) { // Only PRINT script once. Checks the done wpscript list to see if it's been printed yet or not.
				wp_enqueue_script( $script );
				wp_print_scripts( $script );
			}
		}
	} // End load_script().
	
	
	
	/*	self::load_style()
	 *	
	 *	Load a CSS file into the page. Handles prefixed, enqueuing, etc.
	 *	
	 *	@param		string		$style			If a .css file is included then a file in the css directory is loaded; else loads a built-in named library style.
	 *											Ex: load_style( 'sort.css' ) will load /wp-content/plugins/my_plugin/css/sort.css; load_style( 'dashboard' ) will load internal dashboard css in WordPress if it exists.
	 *	@param		boolean		$core_style		If true styles are loaded from /pluginbuddy/css/STYLE.css. Else styles loaded from plugin's css directory.
	 *	@return		null
	 */
	public static function load_style( $style, $core_style = false ) {
		if ( strstr( $style, '.css' ) ) { // Loading a file specifically.
			if ( $core_style === true ) {
				if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
					$url_path = 'importbuddy/pluginbuddy/css/';
				} else {
					$url_path = self::$_plugin_url . '/pluginbuddy/css/';
				}
				$local_path = self::$_plugin_path . '/pluginbuddy/css/';
				$core_type = 'core';
			} else {
				if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
					$url_path = 'importbuddy/css/';
				} else {
					$url_path = self::$_plugin_url . '/css/';
				}
				$local_path = self::$_plugin_path . '/css/';
				$core_type = 'noncore';
			}
			$style_name = 'pb_' . self::settings( 'slug' ) . '_' . $core_type . '_' . $style;
			if ( !wp_style_is( $style_name ) ) { // Only load style once.
				if ( file_exists( $local_path . $style ) ) { // Load our local style if file exists.
					wp_enqueue_style( $style_name, $url_path . $style, array(), pb_backupbuddy::settings( 'version' ) );
					wp_print_styles( $style_name );
				} else {
					echo '{Error: CSS file was set to load that did not exist: `' . $url_path . $style . '`}';
				}
			}
		} else { // Not a specific file.
			if ( !wp_style_is( $style ) ) { // Only load style once.
				wp_enqueue_style( $style );
				wp_print_styles( $style );
			}
		}
	} // End load_style().
	
	
	
	/*	self::load_view()
	 *	
	 *	Loads a view. Typically called from within a controller. Data passed as second argument will has extract() ran on it within the view for easy variable access.
	 *	
	 *	@param		string		$view_name				Name of view. Corresponds to the view filename: /views/view_name.php
	 *	@param		array		$pluginbuddy_data		Array of variables to be extracted for use by the view.
	 *	@return		null
	 */
	public static function load_view( $view_name, $pluginbuddy_data = array() ) {
		$pluginbuddy_view_file = self::$_plugin_path . '/views/' . $view_name . '.php'; // Variable named this way as the included file inherits this variable and we don't want an accidental collision.
		if ( file_exists( $pluginbuddy_view_file ) ) {
			unset( $view_name );
			if ( is_array( $pluginbuddy_data ) ) {
				extract( $pluginbuddy_data );
			} else {
				echo '{Warning: Data parameter passed to view was not an array.}';
			}
			//global $dionysus_controller; // Gives the view the ability to access functions within the controller if needed.
			require $pluginbuddy_view_file;
		} else {
			echo '{INVALID VIEW: `' . $view_name . '`; file not found.}';
		}
	} // End load_view().
	
	
	
	/*	self::load_controller()
	 *	
	 *	Loads a controller. Controllers may load controllers. Controller uses require_once to avoid problems.
	 *	
	 *	@param		string		$controller				Name of controller. Corresponds to the controller filename: /controllers/controller_name.php
	 *	@return		null
	 */
	public static function load_controller( $controller ) {
		// Using this method so load_controller() may be used anywhere.
		if ( file_exists( self::plugin_path() . '/controllers/' . $controller . '.php' ) ) {
			require_once( self::plugin_path() . '/controllers/' . $controller . '.php' );
		} else {
			echo '{Error: Unable to load page controller `' . $controller . '`; file not found.}';
		}
	} // End load_controller().
	
	
	
	/*	self::register_widget()
	 *	
	 *	Registers a widget. Will register widget class in /controllers/widget/slug.php. Widget class extend WP_Widgets.
	 *	
	 *	@param		string		$slug		Name / slug for widget. Must match filename in controllers\widgets\ directory. Class name in the format: pb_{PLUGINSLUG}_widget_{WIDGETSLUG}
	 *	@return		null
	 */
	public static function register_widget( $slug ) {
		if ( file_exists( self::plugin_path() . '/controllers/widgets/' . $slug . '.php' ) ) {
			require( self::plugin_path() . '/controllers/widgets/' . $slug . '.php' );
			add_action( 'widgets_init', create_function( '', 'register_widget(\'pb_' . self::settings( 'slug' ) . '_widget_' . $slug . '\');' ) );
		} else {
			echo '{Error #3444548922: Unable to load widget file `controllers/widgets/' . $slug . '.php`.}';
		}
	} // End register_widget().
	
	
	
	/**
	 *	array_remove()
	 *
	 *	Removes array values in $remove from $array.
	 *
	 *	@param			$array		array		Source array. This will have values removed and be returned.
	 *	@param			$remove		array		Array of values to search for in $array and remove.
	 *	@return						array		Returns array $array stripped of all values found in $remove
	 */
	public static function array_remove( $array, $remove ) {
		if ( !is_array( $remove ) ) {
			$remove = array( $remove );
		}
		return array_values( array_diff( $array, $remove ) );
	} // End array_remove().
	
	
	
	/* flush()
	 *
	 * Attempt to strongarm a flush to actually work.
	 * Prevent flushing by adding this to wp-config.php:
	 *		define( 'BACKUPBUDDY_NOFLUSH', true );
	 *  OR
	 *		set advanced option to prevent flush
	 *
	 */
	public static function flush() {
		if ( defined( 'BACKUPBUDDY_NOFLUSH' ) && ( BACKUPBUDDY_NOFLUSH === true ) ) { // Some servers seem to die on multiple flushes in the same pageload. Define this to prevent flushing.
			return;
		}
		if ( isset( pb_backupbuddy::$options ) && ( isset( pb_backupbuddy::$options['prevent_flush'] ) ) && ( '1' == pb_backupbuddy::$options['prevent_flush'] ) ) {
			return;
		}
		if ( true !== self::$_has_flushed ) { // Only run this once.
			if ( function_exists( 'apache_setenv' ) ) {
				@apache_setenv('no-gzip', 1); // Compression could cause server to wait for page to finish before proceeding. Turn off compression.
			}
			@ini_set('zlib.output_compression', 0); // Compression could cause server to wait for page to finish before proceeding. Turn off compression.
			self::$_has_flushed = true;
		}
		@ob_flush();
		flush();
	} // End flush().
	
	
	/*	reset_defaults()
	 *	
	 *	Reset plugin options to defaults. Getting started page uses this.
	 *	
	 *	@return		boolean			True on success; false otherwise.
	 */
	public static function reset_defaults() {
		if ( isset( pb_backupbuddy::$_settings['default_options'] ) ) {
			pb_backupbuddy::$options = pb_backupbuddy::$_settings['default_options'];
			pb_backupbuddy::save();
			return true;
		} else {
			return false;
		}
	} // End reset_defaults().
	
	
	
} // End class pluginbuddy.


if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
	require_once( 'standalone_preloader.php' );
}

// ********** Load core classes **********

require_once( dirname( __FILE__ ) . '/classes/core_controllers.php' );
if ( is_admin() ) {
	require_once( dirname( __FILE__ ) . '/classes/form.php' );
	require_once( dirname( __FILE__ ) . '/classes/settings.php' );
}


// ********** Initialize PluginBuddy framework **********

if ( !isset( $pluginbuddy_init ) ) {
	$pluginbuddy_init = 'init.php'; // default init file.
}
pb_backupbuddy::init( $pluginbuddy_settings, $pluginbuddy_init );
unset( $pluginbuddy_settings );
unset( $pluginbuddy_init );

pb_backupbuddy::load();

// ********** Load initialization files **********

require_once( dirname( dirname( __FILE__ ) ) . '/init_global.php' );
if ( is_admin() ) {
	require_once( dirname( dirname( __FILE__ ) ) . '/init_admin.php' );
}

if ( defined( 'PB_STANDALONE' ) && PB_STANDALONE === true ) {
	pb_backupbuddy::load_controller( 'pages/default' );
}
