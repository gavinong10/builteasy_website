<style type="text/css">
	.pb_backupbuddy_refresh_stats {
		cursor: pointer;
	}
</style>
<script>
jQuery(document).ready(function() {
	
	jQuery('.pb_backupbuddy_testErrorLog').click(function(e) {
		jQuery( '.pb_backupbuddy_loading' ).show();
		jQuery.post( jQuery(this).attr( 'rel' ), { function: 'testErrorLog' }, 
			function(data) {
				jQuery( '.pb_backupbuddy_loading' ).hide();
				alert( data );
			}
		);
		return false;
	});
	
	jQuery('.pb_backupbuddy_refresh_stats').click(function(e) {
		loading = jQuery(this).children( '.pb_backupbuddy_loading' );
		loading.show();
		
		result_obj = jQuery( '#pb_stats_' + jQuery(this).attr( 'rel' ) );
		
		jQuery.post( jQuery(this).attr( 'alt' ), jQuery(this).closest( 'form' ).serialize(), 
			function(data) {
				//alert(data);
				loading.hide();
				result_obj.html( data );
			}
		); //,"json");
		
		return false;
	});
});
</script>
<?php
/*
 *	IMPORTANT NOTE:
 *
 *	This file is shared between multiple projects / purposes:
 *		+ BackupBuddy (this plugin) Server Info page.
 *		+ ImportBuddy.php (BackupBuddy importer) Server Information button dropdown display.
 *		+ ServerBuddy (plugin)
 *
 *	Use caution when updated to prevent breaking other projects.
 *
 */


// ini_get_bool() credit: nicolas dot grekas+php at gmail dot com
function ini_get_bool( $a ) {
	$b = ini_get($a);
	switch (strtolower($b)) {
		case 'on':
		case 'yes':
		case 'true':
			return 'assert.active' !== $a;
			
		case 'stdout':
		case 'stderr':
			return 'display_errors' === $a;
			
		default:
			return (bool) (int) $b;
	}
}



function pb_backupbuddy_get_loadavg() {
	$result = array( 'n/a', 'n/a', 'n/a' );
	if ( function_exists('sys_getloadavg') ) {
		$load = @sys_getloadavg();
		if (is_array($load)) {
			if(count($load) == 3)
				return $load;
			else {
				for($i=0;$i<count($load);$i++)
					$result[$i] = $load[$i];
			}
		}
	}
	if ( substr( PHP_OS, 0, 3 ) == 'WIN' ) { // WINDOWS.
		ob_start();
		$status = null;
		@passthru('typeperf -sc 1 "\processor(_total)\% processor time"',$status);
		$content = ob_get_contents();
		ob_end_clean();
		if ($status === 0) {
			if (preg_match("/\,\"([0-9]+\.[0-9]+)\"/",$content,$load)) {					
				$result[0] = number_format_i18n($load[1],2).' %';
				$result[1] = 'n/a';
				$result[2] = 'n/a';
				return $result;
			}
		}			
	} else {
		if (function_exists('file_get_contents') && @file_exists('/proc/loadavg')) {
			$load = explode(chr(32), @file_get_contents('/proc/loadavg'));
			if (is_array($load) && (count($load) >= 3)) {
				$result = array_slice($load, 0, 3);
				return $result;
			}
		}
		if (function_exists('shell_exec')) {
			$str = substr(strrchr(@shell_exec('uptime'),":"),1);
			return array_map("trim",explode(",",$str));
		}
	}
	return $result;
}


	
	$tests = array();
	
	
	// Skip these tests in importbuddy.
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		
		// BACKUPBUDDY VERSION
		// TODO: Put BB version here. After Jordan fixes updater API to always provide latest version info then we can compare and warn if too out of data.
		/*
		$parent_class_test = array(
						'title'			=>		'BackupBuddy Version',
						'suggestion'	=>		'>= ' . pb_backupbuddy::settings( 'wp_minimum' ) . ' (latest best)',
						'value'			=>		$wp_version,
						'tip'			=>		__('Version of WordPress currently running. It is important to keep your WordPress up to date for security & features.', 'it-l10n-backupbuddy' ),
					);
		if ( version_compare( $wp_version, pb_backupbuddy::settings( 'wp_minimum' ), '<=' ) ) {
			$parent_class_test['status'] = __('FAIL', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
		*/
		
		
		
		// BACKUPBUDDY VERSION
		if ( false === ( $latestVersion = backupbuddy_core::determineLatestVersion( $bypassCache = true ) ) ) {
			$suggestion_text = '[information unavailable]';
			$latest_backupbuddy_nonminor_version = 0;
		} else {
			$latest_backupbuddy_version = $latestVersion[0];
			$latest_backupbuddy_nonminor_version = $latestVersion[1];
			
			$suggestion_text = $latest_backupbuddy_nonminor_version;
			if ( $latest_backupbuddy_version == pb_backupbuddy::settings( 'version' ) ) { // At absolute latest including minor.
				$suggestion_text .= ' (major version) or ' . $latest_backupbuddy_version . ' (<a href="options-general.php?page=ithemes-licensing" title="You may enable upgrading to the quick release version on the iThemes Licensing page.">quick release</a>)';
			} elseif ( $latest_backupbuddy_nonminor_version != $latest_backupbuddy_version ) { // Minor version available that is newer than latest major.
				$suggestion_text .= ' (major version) or ' . $latest_backupbuddy_version . ' (<a href="plugins.php?ithemes-updater-force-minor-update=1" title="You may enable upgrading to the quick release version on the iThemes Licensing page.">quick release version</a>; <a href="options-general.php?page=ithemes-licensing" title="Once you have licensed BackupBuddy you may select this to go to the Plugins page to upgrade to the latest quick release version. Typically only the main major versions are available for automatic updates but this option instructs the updater to display minor version updates for approximately one hour. If it does not immediately become available on the Plugins page, try refreshing a couple of times.">quick release settings</a>)';
			} else {
				$suggestion_text .= ' (latest)';
			}
		}
		
		$version_string = pb_backupbuddy::settings( 'version' );
		// If on DEV system (.git dir exists) then append some details on current.
		if ( @file_exists( pb_backupbuddy::plugin_path() . '/.git/logs/HEAD' ) ) {
			$commit_log = escapeshellarg( pb_backupbuddy::plugin_path() . '/.git/logs/HEAD' );
			$commit_line = str_replace( '\'', '`', exec( "tail -n 1 {$commit_log}" ) );
			$version_string .= ' <span style="display: inline-block; max-width: 250px; font-size: 8px;">[DEV: ' . $commit_line . ']</span>';
		}
		$parent_class_test = array(
						'title'			=>		'BackupBuddy Version',
						'suggestion'	=>		$suggestion_text,
						'value'			=>		$version_string,
						'tip'			=>		__('Version of BackupBuddy currently running on this site.', 'it-l10n-backupbuddy' ),
					);
		if ( version_compare( pb_backupbuddy::settings( 'version' ), $latest_backupbuddy_nonminor_version, '<' ) ) {
			$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
		
		
		
		// WORDPRESS VERSION
		global $wp_version;
		$parent_class_test = array(
						'title'			=>		'WordPress Version',
						'suggestion'	=>		'>= ' . pb_backupbuddy::settings( 'wp_minimum' ) . ' (latest best)',
						'value'			=>		$wp_version,
						'tip'			=>		__('Version of WordPress currently running. It is important to keep your WordPress up to date for security & features.', 'it-l10n-backupbuddy' ),
					);
		if ( version_compare( $wp_version, pb_backupbuddy::settings( 'wp_minimum' ), '<=' ) ) {
			$parent_class_test['status'] = __('FAIL', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
	
		// MYSQL VERSION
		global $wpdb;
		$parent_class_test = array(
						'title'			=>		'MySQL Version',
						'suggestion'	=>		'>= 5.5.0',
						'value'			=>		$wpdb->db_version(),
						'tip'			=>		__('Version of your database server (mysql) as reported to this script by WordPress.', 'it-l10n-backupbuddy' ),
					);
		if ( version_compare( $wpdb->db_version(), '5.0.15', '<=' ) ) {
			$parent_class_test['status'] = __('FAIL', 'it-l10n-backupbuddy' );
		} elseif ( version_compare( $wpdb->db_version(), '5.5.0', '<=' ) ) {
			$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
		
		
		// ADDHANDLER HTACCESS CHECK
		$parent_class_test = array(
						'title'			=>		'AddHandler in .htaccess',
						'suggestion'	=>		'host dependant (none best unless required)',
						'tip'			=>		__('If detected then you may have difficulty migrating your site to some hosts without first removing the AddHandler line. Some hosts will malfunction with this line in the .htaccess file.', 'it-l10n-backupbuddy' ),
					);
		if ( file_exists( ABSPATH . '.htaccess' ) ) {
			$addhandler_note = '';
			$htaccess_lines = file( ABSPATH . '.htaccess' );
			foreach ( $htaccess_lines as $htaccess_line ) {
				if ( preg_match( '/^(\s*)AddHandler(.*)/i', $htaccess_line, $matches ) > 0 ) {
					$addhandler_note = pb_backupbuddy::tip( htmlentities( $matches[0] ), __( 'AddHandler Value', 'it-l10n-backupbuddy' ), false );
				}
			}
			unset( $htaccess_lines );
			
			if ( $addhandler_note == '' ) {
				$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
				$parent_class_test['value'] = __('none, n/a', 'it-l10n-backupbuddy' );
			} else {
				$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
				$parent_class_test['value'] = __('exists', 'it-l10n-backupbuddy' ) . $addhandler_note;
			}
			unset( $htaccess_contents );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
			$parent_class_test['value'] = __('n/a', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
		
		
		// Set up ZipBuddy when within BackupBuddy
		require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
		pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( backupbuddy_core::getBackupDirectory() );
		
		require_once( pb_backupbuddy::plugin_path() . '/lib/mysqlbuddy/mysqlbuddy.php' );
		global $wpdb;
		pb_backupbuddy::$classes['mysqlbuddy'] = new pb_backupbuddy_mysqlbuddy( DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, $wpdb->prefix ); // $database_host, $database_name, $database_user, $database_pass, $old_prefix, $force_method = array()
	}
	
	
	// PHP VERSION
	if ( !defined( 'pluginbuddy_importbuddy' ) ) {
		$php_minimum = pb_backupbuddy::settings( 'php_minimum' );
	} else { // importbuddy value.
		$php_minimum = pb_backupbuddy::settings( 'php_minimum' );
	}
	$parent_class_test = array(
					'title'			=>		'PHP Version',
					'suggestion'	=>		'>= ' . $php_minimum . ' (5.2.16+ best)',
					'value'			=>		phpversion(),
					'tip'			=>		__('Version of PHP currently running on this site.', 'it-l10n-backupbuddy' ),
				);
	if ( version_compare( PHP_VERSION, $php_minimum, '<=' ) ) {
		$parent_class_test['status'] = __('FAIL', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	// PHP max_execution_time
	$parent_class_test = array(
					'title'			=>		'PHP max_execution_time (server-reported)',
					'suggestion'	=>		'>= ' . '30 seconds (30+ best)',
					'value'			=>		ini_get( 'max_execution_time' ),
					'tip'			=>		__('Maximum amount of time that PHP allows scripts to run. After this limit is reached the script is killed. The more time available the better. 30 seconds is most common though 60 seconds is ideal.', 'it-l10n-backupbuddy' ),
				);
	if ( str_ireplace( 's', '', ini_get( 'max_execution_time' ) ) < 30 ) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// Maximum PHP Runtime (ACTUAL TESTED!)
	/*
	if ( ! defined( 'PB_IMPORTBUDDY' ) ) {
		$parent_class_test = array(
						'title'			=>		'Tested PHP Max Execution Time (beta)',
						'suggestion'	=>		'>= 30 seconds (30+ best)',
						'value'			=>		'<span id="pb_stats_php_max_runtime_test"><!-- current value here--></span> <a class="pb_backupbuddy_refresh_stats_DISABLEDAJAX" rel="php_max_runtime_test" alt="' . pb_backupbuddy::ajax_url( 'php_max_runtime_test' ) . '" href="' . pb_backupbuddy::ajax_url( 'php_max_runtime_test' ) . '" target="_blank" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '" title="The page may not show anything until the test completes (max runtime gets hit). Test results will also be displayed in your BackupBuddy log if full logging is enabled.">Begin Test<img style="display: none;" src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('This is the TESTED amount of time that PHP allows scripts to run. The test was performed by outputting / logging the script time elapsed once per second until PHP timed out and thus the time reported stopped. This gives a fairly accurate number compared to the reported number which is most often overriden at the server with a limit. If the page stays blank for a while then eventually loads then your server does not support live flushing of the updated time to your browser so you will not see updates until the test completes. ', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
	}
	*/
	
	
	
	// MEMORY LIMIT
	if ( !ini_get( 'memory_limit' ) ) {
		$parent_class_val = 'unknown';
	} else {
		$parent_class_val = ini_get( 'memory_limit' );
	}
	$parent_class_test = array(
					'title'			=>		'PHP Memory Limit',
					'suggestion'	=>		'>= 128M (256M+ best)',
					'value'			=>		$parent_class_val,
					'tip'			=>		__('The amount of memory this site is allowed to consume.', 'it-l10n-backupbuddy' ),
				);
	if ( preg_match( '/(\d+)(\w*)/', $parent_class_val, $matches ) ) {
		$parent_class_val = $matches[1];
		$unit = $matches[2];
		// Up memory limit if currently lower than 256M.
		if ( 'g' !== strtolower( $unit ) ) {
			if ( ( $parent_class_val < 128 ) || ( 'm' !== strtolower( $unit ) ) ) {
				$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
			} else {
				$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
			}
		}
	} else {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// ERROR LOGGING ENABLED/DISABLED
	if ( true == ini_get( 'log_errors' ) ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
		'title'			=>		'PHP Error Logging (log_errors)',
		'suggestion'	=>		'enabled',
		'value'			=>		$parent_class_val . ' [<a href="javascript:void(0)" class="pb_backupbuddy_testErrorLog" rel="' . pb_backupbuddy::ajax_url( 'testErrorLog' ) . '" title="' . __('Testing this will trigger an error_log() event with the content "BackupBuddy Test - This is only a test. A user triggered BackupBuddy to determine if writing to the PHP error log is working as expected."', 'it-l10n-backupbuddy' ) . '">Test</a>]',
		'tip'			=>		__('Whether or not PHP errors are logged to a file or not. Set by php.ini log_errors', 'it-l10n-backupbuddy' ),
	);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	array_push( $tests, $parent_class_test );
	
	
	
	// ERROR LOG FILE
	if ( !ini_get( 'error_log' ) ) {
		$parent_class_val = 'unknown';
	} else {
		$parent_class_val = ini_get( 'error_log' );
	}
	$parent_class_test = array(
		'title'			=>		'PHP Error Log File (error_log)',
		'suggestion'	=>		'n/a',
		'value'			=>		'<span style="display: inline-block; max-width: 250px;">' . $parent_class_val . '</span>',
		'tip'			=>		__('File where PHP errors are logged to if PHP Error Logging is enabled (recommended). Set by php.ini error_log', 'it-l10n-backupbuddy' ),
	);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	array_push( $tests, $parent_class_test );
	
	
	
	// DISPLAY_ERRORS SETTING
	if ( true == ini_get( 'display_errors' ) ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
		'title'			=>		'PHP Display Errors to Screen (display_errors)',
		'suggestion'	=>		'disabled',
		'value'			=>		$parent_class_val,
		'tip'			=>		__('Whether or not PHP errors are displayed on screen to the user. This is useful for troubleshooting PHP problems but disabling by default is more secure for production. Set by php.ini display_errors', 'it-l10n-backupbuddy' ),
	);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	/*
	if ( 'enabled' != $parent_class_val ) {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	}
	*/
	array_push( $tests, $parent_class_test );
	
	
	
	if ( defined( 'PB_IMPORTBUDDY' ) ) {
		if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
			pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( ABSPATH );
		}
	}
	$zip_methods = implode( ', ', pb_backupbuddy::$classes['zipbuddy']->_zip_methods );
	
	if ( ! defined( 'PB_IMPORTBUDDY' ) ) {
		$zipmethod_refresh = '<a class="pb_backupbuddy_refresh_stats" rel="refresh_zip_methods" alt="' . pb_backupbuddy::ajax_url( 'refresh_zip_methods' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>';
	} else {
		$zipmethod_refresh = '';
	}
	$parent_class_test = array(
					'title'			=>		'Zip Methods',
					'suggestion'	=>		'Command line [fastest] > ziparchive > PHP-based (pclzip) [slowest]',
					'value'			=>		'<span id="pb_stats_refresh_zip_methods">' . $zip_methods . '</span> ' . $zipmethod_refresh,
					'tip'			=>		__('Methods your server supports for creating ZIP files. These were tested & verified to operate. Command line is magnitudes better than other methods and operates via exec() or other execution functions. ZipArchive is a PHP extension. PHP-based ZIP compression/extraction is performed via a PHP script called pclzip but it is slower and can be memory intensive.', 'it-l10n-backupbuddy' ),
				);
	if ( in_array( 'exec', pb_backupbuddy::$classes['zipbuddy']->_zip_methods ) ) {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		
		$parent_class_test = array(
						'title'			=>		'Database Dump Methods',
						'suggestion'	=>		'Command line and/or PHP-based',
						'value'			=>		implode( ', ', pb_backupbuddy::$classes['mysqlbuddy']->get_methods() ),
						'tip'			=>		__('Methods your server supports for dumping (backing up) your mysql database. These were tested values unless compatibility / troubleshooting settings override.', 'it-l10n-backupbuddy' ),
					);
		$db_methods = pb_backupbuddy::$classes['mysqlbuddy']->get_methods();
		if ( in_array( 'commandline', $db_methods ) || in_array( 'php', $db_methods ) ) { // PHP is considered just as good as of BB v5.0.
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
		
		
		
		// Site Size
		if ( pb_backupbuddy::$options['stats']['site_size'] > 0 ) {
			$site_size = pb_backupbuddy::$format->file_size( pb_backupbuddy::$options['stats']['site_size'] );
		} else {
			$site_size = '<i>Unknown</i>';
		}
		$parent_class_test = array(
						'title'			=>		'Site Size',
						'suggestion'	=>		'n/a',
						'value'			=>		'<span id="pb_stats_refresh_site_size">' . $site_size . '</span> <a class="pb_backupbuddy_refresh_stats" rel="refresh_site_size" alt="' . pb_backupbuddy::ajax_url( 'refresh_site_size' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('Total size of your site (starting in your WordPress main directory) INCLUDING any excluded directories / files.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Site size WITH EXCLUSIONS accounted for.
		if ( pb_backupbuddy::$options['stats']['site_size_excluded'] > 0 ) {
			$site_size_excluded = pb_backupbuddy::$format->file_size( pb_backupbuddy::$options['stats']['site_size_excluded'] );
		} else {
			$site_size_excluded = '<i>Unknown</i>';
		}
		$parent_class_test = array(
						'title'			=>		'Site Size (Default Exclusions applied)',
						'suggestion'	=>		'n/a',
						'value'			=>		'<span id="pb_stats_refresh_site_size_excluded">' . $site_size_excluded . '</span> <a class="pb_backupbuddy_refresh_stats" rel="refresh_site_size_excluded" alt="' . pb_backupbuddy::ajax_url( 'refresh_site_size_excluded' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('Total size of your site (starting in your WordPress main directory) EXCLUDING any directories / files you have marked for exclusion.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		// Site Objects
		if ( isset( pb_backupbuddy::$options['stats']['site_objects'] ) && ( pb_backupbuddy::$options['stats']['site_objects'] > 0 ) ) {
			$site_objects = pb_backupbuddy::$options['stats']['site_objects'];
		} else {
			$site_objects = '<i>Unknown</i>';
		}
		$parent_class_test = array(
						'title'			=>		'Site number of files',
						'suggestion'	=>		'n/a',
						'value'			=>		'<span id="pb_stats_refresh_objects">' . $site_objects . '</span> <a class="pb_backupbuddy_refresh_stats" rel="refresh_objects" alt="' . pb_backupbuddy::ajax_url( 'refresh_site_objects' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('Total number of files/folders in your site (starting in your WordPress main directory) INCLUDING any excluded directories / files.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Site objects WITH EXCLUSIONS accounted for.
		if ( isset( pb_backupbuddy::$options['stats']['site_objects_excluded'] ) && ( pb_backupbuddy::$options['stats']['site_objects_excluded'] > 0 ) ) {
			$site_objects_excluded = pb_backupbuddy::$options['stats']['site_objects_excluded'];
		} else {
			$site_objects_excluded = '<i>Unknown</i>';
		}
		$parent_class_test = array(
						'title'			=>		'Site number of files (Default Exclusions applied)',
						'suggestion'	=>		'n/a',
						'value'			=>		'<span id="pb_stats_refresh_objects_excluded">' . $site_objects_excluded . '</span> <a class="pb_backupbuddy_refresh_stats" rel="refresh_objects_excluded" alt="' . pb_backupbuddy::ajax_url( 'refresh_site_objects_excluded' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('Total number of files/folders site (starting in your WordPress main directory) EXCLUDING any directories / files you have marked for exclusion.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Database Size
		$parent_class_test = array(
						'title'			=>		'Database Size',
						'suggestion'	=>		'n/a',
						'value'			=>		'<span id="pb_stats_refresh_database_size">' .pb_backupbuddy::$format->file_size( pb_backupbuddy::$options['stats']['db_size'] ) . '</span> <a class="pb_backupbuddy_refresh_stats" rel="refresh_database_size" alt="' . pb_backupbuddy::ajax_url( 'refresh_database_size' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('Total size of your database INCLUDING any excluded tables.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Database size WITH EXCLUSIONS accounted for.
		$parent_class_test = array(
						'title'			=>		'Database Size (Default Exclusions applied)',
						'suggestion'	=>		'n/a',
						'value'			=>		'<span id="pb_stats_refresh_database_size_excluded">' . pb_backupbuddy::$format->file_size( pb_backupbuddy::$options['stats']['db_size_excluded'] ) . '</span> <a class="pb_backupbuddy_refresh_stats" rel="refresh_database_size_excluded" alt="' . pb_backupbuddy::ajax_url( 'refresh_database_size_excluded' ) . '" title="' . __('Refresh', 'it-l10n-backupbuddy' ) . '"><img src="' . pb_backupbuddy::plugin_url() . '/images/refresh_gray.gif" style="vertical-align: -1px;"> <span class="pb_backupbuddy_loading" style="display: none; margin-left: 10px;"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" alt="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" title="' . __('Loading...', 'it-l10n-backupbuddy' ) . '" width="16" height="16" style="vertical-align: -3px;" /></span></a>',
						'tip'			=>		__('Total size of your database EXCLUDING any tables you have marked for exclusion.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		/***** BEGIN AVERAGE WRITE SPEED *****/
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		
		$write_speed_samples = 0;
		$write_speed_sum = 0;
		$backups = glob( backupbuddy_core::getBackupDirectory() . '*.zip' );
		if ( ! is_array( $backups ) ) {
			$backups = array();
		}
		foreach( $backups as $backup ) {
			
			$serial = backupbuddy_core::get_serial_from_file( $backup );
			pb_backupbuddy::status( 'details', 'Fileoptions instance #22.' );
			$backup_options = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt', $read_only = true );
			if ( true !== ( $result = $backup_options->is_ok() ) ) {
				pb_backupbuddy::status( 'warning', 'Unable to open fileoptions file `' . backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt' . '`. Details: `' . $result . '`.' );
			} 
				
				
			if ( isset( $backup_options->options['integrity'] ) && isset( $backup_options->options['integrity']['size'] ) ) {
				$write_speed_samples++;
				
				$size = $backup_options->options['integrity']['size'];
				$time_taken = 0;
				if ( isset( $backup_options->options['steps'] ) ) {
					foreach( $backup_options->options['steps'] as $step ) {
						if ( $step['function'] == 'backup_zip_files' ) {
							$time_taken = $step['finish_time'] - $step['start_time'];
							break;
						}
					} // End foreach.
				} // End if steps isset.
				
				if ( $time_taken == 0 ) {
					//$write_speed_sum += 0;
					$write_speed_samples = $write_speed_samples - 1; // Ignore this sample since it's too small to count.
				} else {
					$write_speed_sum += $size / $time_taken; // Sum up write speeds.
				}
				
			}
		}
		
		if ( $write_speed_sum > 0 ) {
			$final_write_speed = pb_backupbuddy::$format->file_size( $write_speed_sum / $write_speed_samples ) . '/sec';
			$final_write_speed_guess = pb_backupbuddy::$format->file_size( ( $write_speed_sum / $write_speed_samples ) * ini_get( 'max_execution_time' ) );
		} else {
			$final_write_speed = '<i>Unknown</i>';
			$final_write_speed_guess = '<i>Unknown</i>';
		}
		
		$parent_class_test = array(
						'title'			=>		'Average Write Speed',
						'suggestion'	=>		'n/a',
						'value'			=>		 $final_write_speed,
						'tip'			=>		__('Average ZIP file creation write speed. Backup file sizes divided by the time taken to create each. Samples: `' . $write_speed_samples . '`.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		/***** END AVERAGE WRITE SPEED *****/
		
		
		// Guess max site size to be able to backup.
		$parent_class_test = array(
						'title'			=>		'Guesstimate of max ZIP size',
						'suggestion'	=>		'n/a',
						'value'			=>		$final_write_speed_guess,
						'tip'			=>		__('Calculated estimate of the largest .ZIP backup file that may be created. As ZIP files are compressed the site size that may be backed up should be larger than this.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Http loopbacks.
		if ( ( $loopback_response = backupbuddy_core::loopback_test() ) === true ) {
			$loopback_status = 'enabled';
			$status = 'OK';
		} else {
			$loopback_status = 'disabled (enable alternate cron)';
			$status = 'WARNING';
		}
		global $backupbuddy_loopback_details;
		$loopback_status .= ' <span title="' . htmlentities( $backupbuddy_loopback_details ) . '" style="font-style: italic;">Hover for details</span>';
		$parent_class_test = array(
						'title'			=>		'Http Loopbacks',
						'suggestion'	=>		'enabled',
						'value'			=>		$loopback_status,
						'tip'			=>		__('Some servers do are not configured properly to allow WordPress to connect back to itself via the site URL (ie: http://your.com connects back to itself on the same server at http://your.com/ to trigger a simulated cron step). If this is the case you must either ask your hosting provider to fix this or enable WordPres Alternate Cron mode in your wp-config.php file.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __( $status, 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// wp-cron.php http loopbacks.
		if ( ( $cronback_response = backupbuddy_core::cronback_test() ) === true ) {
			$cronback_status = 'enabled';
			$status = 'OK';
		} else {
			$cronback_status = 'disabled (enable alternate cron)';
			$status = 'WARNING';
		}
		global $backupbuddy_cronback_details;
		$cronback_status .= ' <span title="' . htmlentities( $backupbuddy_cronback_details ) . '" style="font-style: italic;">Hover for details</span>';
		$parent_class_test = array(
						'title'			=>		'wp-cron.php Loopbacks',
						'suggestion'	=>		'enabled',
						'value'			=>		$cronback_status,
						'tip'			=>		__('Some servers do are not configured properly to allow WordPress to connect back to itself via the site URL (ie: http://your.com connects back to itself on the same server at http://your.com/ to trigger a simulated cron step). If this is the case you must either ask your hosting provider to fix this or enable WordPres Alternate Cron mode in your wp-config.php file.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __( $status, 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Http loopback URL & IP.
		$status = 'OK';
		$parsed_url = parse_url( site_url() );
		$ip = gethostbyname( $parsed_url['host'] );
		$parent_class_test = array(
						'title'			=>		'Loopback Domain & IP',
						'suggestion'	=>		'n/a',
						'value'			=>		$parsed_url['host'] . ' =&gt; ' . $ip,
						'tip'			=>		__('Sometimes due to DNS delays the server may detect the old IP address as being associated with your site domain used in the loopback URL. This can result in loopback problems even though they may be enabled. Contact your host or wait longer if the IP address this server reports is incorrect.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __( $status, 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// CRON disabled?
		if ( defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ) {
			$cron_status = 'disabled';
			$status = 'FAIL';
		} else {
			$cron_status = 'enabled';
			$status = 'OK';
		}
		$parent_class_test = array(
						'title'			=>		'WordPress Cron',
						'suggestion'	=>		'enabled',
						'value'			=>		$cron_status,
						'tip'			=>		__( 'This check verifies that the cron system has not been disabled by the DISABLE_WP_CRON constant. This may be defined by a plugin or other method to disable the cron system which may result in automated functionality not being available.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __( $status, 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		// Alternate cron on?
		if ( defined( 'ALTERNATE_WP_CRON' ) && ( ALTERNATE_WP_CRON === true ) ) {
			$alternate_cron_status = 'enabled';
		} else {
			$alternate_cron_status = 'disabled (default)';
		}
		$parent_class_test = array(
						'title'			=>		'WordPress Alternate Cron',
						'suggestion'	=>		'Varies (server-dependent)',
						'value'			=>		$alternate_cron_status,
						'tip'			=>		__('Some servers do not allow sites to connect back to themselves at their own URL.  WordPress and BackupBuddy make use of these "Http Loopbacks" for several things.  Without them you may encounter issues. If your server needs it or you are directed by support you may enable Alternate Cron in your wp-config.php file.  When enabled this setting will display "Enabled" to remind you.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
		
		
		
		
	} // End non-importbuddy tests.
	
	
	
	// DISABLED FUNCTIONS
	$disabled_functions = ini_get( 'disable_functions' );
	if ( $disabled_functions == '' ) {
		$disabled_functions = '(none)';
	}
	$parent_class_test = array(
					'title'			=>		'Disabled PHP Functions',
					'suggestion'	=>		'n/a',
					'value'			=>		'<textarea style="width: 100%; max-height: 200px;" disabled="disabled">' . str_replace( ',', ', ', $disabled_functions ) . '</textarea>',
					'tip'			=>		__('Some hosts block certain PHP functions for various reasons. Sometimes hosts block functions that are required for proper functioning of WordPress or plugins.', 'it-l10n-backupbuddy' ),
				);
	$disabled_functions = str_replace( ', ', ',', $disabled_functions ); // Normalize spaces or lack of spaces between disabled functions.
	$disabled_functions_array = explode( ',', $disabled_functions );
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	if (
		( true === in_array( 'exec', $disabled_functions_array ) )
		||
		( true === in_array( 'ini_set', $disabled_functions_array ) )
		) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// MYSQL_CONNECT
	if ( is_callable( 'mysql_connect' ) ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
		'title' => 'PHP function: mysql_connect()',
		'suggestion' => 'n/a',
		'value'      => $parent_class_val,
		'tip'        => __( 'Deprecated in PHP 5.5.0 and removed in PHP 7.0.0. Replaced by mysqli_connect or PDO::__construct()', 'it-l10n-backupbuddy' ),
	);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	array_push( $tests, $parent_class_test );
	
	
	
	// REGISTER GLOBALS
	if ( ini_get_bool( 'register_globals' ) === true ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
					'title'			=>		'PHP Register Globals',
					'suggestion'	=>		'disabled',
					'value'			=>		$parent_class_val,
					'tip'			=>		__('Automatically registers user input as variables. HIGHLY discouraged. Removed from PHP in PHP 6 for security.', 'it-l10n-backupbuddy' ),
				);
	if ( $parent_class_val != 'disabled' ) {
		$parent_class_test['status'] = __('FAIL', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// MAGIC QUOTES GPC
	if ( ini_get_bool( 'magic_quotes_gpc' ) === true ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
					'title'			=>		'PHP Magic Quotes GPC',
					'suggestion'	=>		'disabled',
					'value'			=>		$parent_class_val,
					'tip'			=>		__('Automatically escapes user inputted data. Not needed when using properly coded software.', 'it-l10n-backupbuddy' ),
				);
	if ( $parent_class_val != 'disabled' ) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	// MAGIC QUOTES RUNTIME
	if ( ini_get_bool( 'magic_quotes_runtime' ) === true ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
					'title'			=>		'PHP Magic Quotes Runtime',
					'suggestion'	=>		'disabled',
					'value'			=>		$parent_class_val,
					'tip'			=>		__('Automatically escapes user inputted data. Not needed when using properly coded software.', 'it-l10n-backupbuddy' ),
				);
	if ( $parent_class_val != 'disabled' ) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// SAFE MODE
	if ( ini_get_bool( 'safe_mode' ) === true ) {
		$parent_class_val = 'enabled';
	} else {
		$parent_class_val = 'disabled';
	}
	$parent_class_test = array(
					'title'			=>		'PHP Safe Mode',
					'suggestion'	=>		'disabled',
					'value'			=>		$parent_class_val,
					'tip'			=>		__('This mode is HIGHLY discouraged and is a sign of a poorly configured host.', 'it-l10n-backupbuddy' ),
				);
	if ( $parent_class_val != 'disabled' ) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// PHP API
	$php_api = 'Unknown';
	if ( is_callable( 'php_sapi_name' ) ) {
		$php_api = php_sapi_name();
	}
	$parent_class_test = array(
					'title'			=>		'PHP API',
					'suggestion'	=>		'n/a',
					'value'			=>		$php_api,
					'tip'			=>		__('API mode PHP is running under.', 'it-l10n-backupbuddy' ),
				);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	array_push( $tests, $parent_class_test );
	
	
	
	// PHP Bits
	$parent_class_test = array(
					'title'			=>		'PHP Architecture',
					'suggestion'	=>		'64-bit',
					'value'			=>		( PHP_INT_SIZE * 8 ) . '-bit',
					'tip'			=>		__('Whether PHP is running in 32 or 64 bit mode. 64-bit is recommended over 32-bit. Note: This only determines PHP status NOT status of other server functionality such as filesystem, command line zip, etc.', 'it-l10n-backupbuddy' ),
				);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	array_push( $tests, $parent_class_test );
	
	
	
	// http Server Software
	if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
		$server_software = $_SERVER['SERVER_SOFTWARE'];
	} else {
		$server_software = 'Unknown';
	}
	$parent_class_test = array(
					'title'			=>		'Http Server Software',
					'suggestion'	=>		'n/a',
					'value'			=>		$server_software,
					'tip'			=>		__('Software running this http web server, such as Apache, IIS, or Nginx.', 'it-l10n-backupbuddy' ),
				);
	$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	array_push( $tests, $parent_class_test );
	
	
	
	// Load Average
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		$load_average = pb_backupbuddy_get_loadavg();
		foreach( $load_average as &$this_load ) {
			$this_load = round( $this_load, 2 );
		}
		$parent_class_test = array(
						'title'			=>		'Server Load Average',
						'suggestion'	=>		'n/a',
						'value'			=>		implode( ', ', $load_average ),
						'tip'			=>		__('Server CPU use in intervals: 1 minute, 5 minutes, 15 minutes. E.g. .45 basically equates to 45% CPU usage.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
	}
	
	
	
	// SFTP support?
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		$connect = 'no';
		$sftp = 'no';
		if ( is_callable( 'ssh2_connect' ) && ( false === in_array( 'ssh2_connect', $disabled_functions_array ) ) ) {
			$connect = 'yes';
		}
		if ( is_callable( 'ssh2_ftp' ) && ( false === in_array( 'ssh2_ftp', $disabled_functions_array ) ) ) {
			$connect = 'yes';
		}
		$parent_class_test = array(
						'title'			=>		'PHP SSH2, SFTP Support',
						'suggestion'	=>		'n/a',
						'value'			=>		$connect . ', ' . $sftp,
						'tip'			=>		__( 'Whether or not your server is configured to allow SSH2 connections over PHP or SFTP connections or PHP. Most hosts do not currently provide this feature. Information only; BackupBuddy cannot make use of this functionality at this time.', 'it-l10n-backupbuddy' ),
					);
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		array_push( $tests, $parent_class_test );
	}
	
	
	
	// ABSPATH
	$parent_class_test = array(
					'title'			=>		'WordPress ABSPATH',
					'suggestion'	=>		'n/a',
					'value'			=>		'<span style="display: inline-block; max-width: 250px;">' . ABSPATH . '</span>',
					'tip'			=>		__( 'This is the directory which WordPress reports to BackupBuddy it is installed in.', 'it-l10n-backupbuddy' ),
				);
	if ( ! @file_exists( ABSPATH ) ) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// OS
	$php_uname = '';
	if ( is_callable( 'php_uname' ) ) {
		$php_uname = ' <span style="display: inline-block; max-width: 250px; font-size: 8px;">(' . @php_uname() . ')</span>';
	}
	$parent_class_test = array(
					'title'			=>		'Operating System',
					'suggestion'	=>		'Linux',
					'value'			=>		PHP_OS . $php_uname,
					'tip'			=>		__('The server operating system running this site. Linux based systems are encouraged. Windows users may need to perform additional steps to get plugins to perform properly.', 'it-l10n-backupbuddy' ),
				);
	if ( substr( PHP_OS, 0, 3 ) == 'WIN' ) {
		$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
	} else {
		$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
	}
	array_push( $tests, $parent_class_test );
	
	
	
	// Active plugins list.
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		// Active Plugins
		$success = true;
		$active_plugins = serialize( get_option( 'active_plugins' ) );
		$found_plugins = array();
		foreach( backupbuddy_core::$warn_plugins as $warn_plugin => $warn_plugin_title ) {
			if ( FALSE !== strpos( $active_plugins, $warn_plugin ) ) { // Plugin active.
				$found_plugins[] = $warn_plugin_title;
				$success = false;
			}
		}
		$parent_class_test = array(
						'title'			=>		'Active WordPress Plugins',
						'suggestion'	=>		'n/a',
						'value'			=>		'<textarea style="width: 100%; max-height: 200px;" disabled="disabled">' . implode( ', ', unserialize( $active_plugins ) ) . '</textarea>',
						'tip'			=>		__( 'Plugins currently activated for this site. A warning does not guarentee problems with a plugin but indicates that a plugin is activated that at one point may have caused operational issues.  Plugin conflicts can be specific and may only occur under certain circumstances such as certain plugin versions, plugin configurations, and server settings.', 'it-l10n-backupbuddy' ),
					);
		if ( false === $success ) {
			$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
	}
	
	
	
	// PHP Process user/group.
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		$success = true;
		$php_user = '<i>' . __( 'Unknown', 'it-l10n-backupbuddy' ) . '</i>';
		$php_uid = '<i>' . __( 'Unknown', 'it-l10n-backupbuddy' ) . '</i>';
		$php_gid = '<i>' . __( 'Unknown', 'it-l10n-backupbuddy' ) . '</i>';
		
		if ( is_callable( 'posix_geteuid' ) && ( false === in_array( 'posix_geteuid', $disabled_functions_array ) ) ) {
			$php_uid = @posix_geteuid();
			if ( is_callable( 'posix_getpwuid' ) && ( false === in_array( 'posix_getpwuid', $disabled_functions_array ) ) ) {
				$php_user = @posix_getpwuid( $php_uid );
				$php_user = $php_user['name'];
			}
		}
		if ( is_callable( 'posix_getegid' ) && ( false === in_array( 'posix_getegid', $disabled_functions_array ) ) ) {
			$php_gid = @posix_getegid();
		}
		$parent_class_test = array(
						'title'			=>		'PHP Process User (UID:GID)',
						'suggestion'	=>		'n/a',
						'value'			=>		$php_user . ' (' . $php_uid . ':' . $php_gid . ')',
						'tip'			=>		__( 'Current user, user ID, and group ID under which this PHP process is running. This user must have proper access to your files and directories. If the PHP user is not your own then setting up a system such as suphp is encouraged to ensure proper access and security.', 'it-l10n-backupbuddy' ),
					);
		if ( false === $success ) {
			$parent_class_test['status'] = __('WARNING', 'it-l10n-backupbuddy' );
		} else {
			$parent_class_test['status'] = __('OK', 'it-l10n-backupbuddy' );
		}
		array_push( $tests, $parent_class_test );
	}
	
	
	
?>



<table class="widefat">
	<thead>
		<tr class="thead">
			<th style="width: 15px;">&nbsp;</th>
			<?php
				echo '<th>', __('Server Configuration', 'it-l10n-backupbuddy' ), '</th>',
					 '<th>', __('Suggestion', 'it-l10n-backupbuddy' ), '</th>',
					 '<th>', __('Value', 'it-l10n-backupbuddy' ), '</th>',
					 //'<th>', __('Result', 'it-l10n-backupbuddy' ), '</th>',
					 '<th style="width: 60px;">', __('Status', 'it-l10n-backupbuddy' ), '</th>';
			?>
		</tr>
	</thead>
	<tfoot>
		<tr class="thead">
			<th style="width: 15px;">&nbsp;</th>
			<?php
				echo '<th>', __('Server Configuration', 'it-l10n-backupbuddy' ), '</th>',
					 '<th>', __('Suggestion', 'it-l10n-backupbuddy' ), '</th>',
					 '<th>', __('Value', 'it-l10n-backupbuddy' ), '</th>',
					 //'<th>', __('Result', 'it-l10n-backupbuddy' ), '</th>',
					 '<th style="width: 15px;">', __('Status', 'it-l10n-backupbuddy' ), '</th>';
			?>
		</tr>
	</tfoot>
	<tbody>
		<?php
		foreach( $tests as $parent_class_test ) {
			echo '<tr class="entry-row alternate">';
			echo '	<td>' . pb_backupbuddy::tip( $parent_class_test['tip'], '', false ) . '</td>';
			echo '	<td>' . $parent_class_test['title'] . '</td>';
			echo '	<td>' . $parent_class_test['suggestion'] . '</td>';
			echo '	<td>' . $parent_class_test['value'] . '</td>';
			//echo '	<td>' . $parent_class_test['status'] . '</td>';
			echo '	<td>';
			if ( $parent_class_test['status'] == __('OK', 'it-l10n-backupbuddy' ) ) {
				echo '<span class="pb_label pb_label-success">Pass</span>';
				//echo '<div style="background-color: #22EE5B; border: 1px solid #E2E2E2;">&nbsp;&nbsp;&nbsp;</div>';
			} elseif ( $parent_class_test['status'] == __('FAIL', 'it-l10n-backupbuddy' ) ) {
				echo '<span class="pb_label pb_label-important">Fail</span>';
				//echo '<div style="background-color: #CF3333; border: 1px solid #E2E2E2;">&nbsp;&nbsp;&nbsp;</div>';
			} elseif ( $parent_class_test['status'] == __('WARNING', 'it-l10n-backupbuddy' ) ) {
				echo '<span class="pb_label pb_label-warning">Warning</span>';
				//echo '<div style="background-color: #FEFF7F; border: 1px solid #E2E2E2;">&nbsp;&nbsp;&nbsp;</div>';
			}
			echo '	</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
<?php
if ( isset( $_GET['phpinfo'] ) && $_GET['phpinfo'] == 'true' ) {
	if ( defined( 'PB_DEMO_MODE' ) ) {
		pb_backupbuddy::alert( 'Access denied in demo mode.', true );
	} else {
		echo '<br><h3>phpinfo() ', __('Response', 'it-l10n-backupbuddy' ), ':</h3>';
		
		echo '<div style="width: 100%; height: 600px; padding-top: 10px; padding-bottom: 10px; overflow: scroll; ">';
		ob_start();
		
		phpinfo();
		
		$info = ob_get_contents();
		ob_end_clean();
		$info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);
		echo $info;
		unset( $info );
		
		echo '</div>';
	}
} else {
	echo '<br>';
	echo '<center>';
	
	if ( !defined( 'PB_IMPORTBUDDY' ) ) {
		echo '<a href="#TB_inline?width=640&#038;height=600&#038;inlineId=pb_serverinfotext_modal" class="button button-secondary button-tertiary thickbox" title="Server Information Results">Display Server Configuration in Text Format</a> &nbsp;&nbsp;&nbsp; ';
		echo '<a href="' . pb_backupbuddy::ajax_url( 'phpinfo' ) . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox button secondary-button" title="' . __('Display Extended PHP Settings via phpinfo()', 'it-l10n-backupbuddy' ) . '">' . __('Display Extended PHP Settings via phpinfo()', 'it-l10n-backupbuddy' ) . '</a>';
	} else {
		echo '<a id="serverinfotext" class="button button-secondary button-tertiary button-primary thickbox toggle" title="Server Information Results">Display Results in Text Format</a> &nbsp;&nbsp;&nbsp; ';
	}
	echo '</center>';
	
	/*
	echo '<pre>';
	print_r( ini_get_all() );
	echo '</pre>';
	*/
}
?><br>



<div
<?php
if ( !defined( 'PB_IMPORTBUDDY' ) ) {
	echo 'id="pb_serverinfotext_modal"';
} else {
	echo 'id="toggle-serverinfotext"';
}
?> style="display: none;">
		<?php
		if ( !defined( 'PB_IMPORTBUDDY' ) ) {
			echo '<h3>' . __( 'Server Information Results', 'it-l10n-backupbuddy' ) . '</h3>';
			echo '<textarea style="width: 100%; height: 300px;" wrap="off">';
		} else {
			echo '<textarea style="width: 95%; height: 300px;" wrap="off">';
		}
		foreach( $tests as $test ) {
			echo '[' . $test['status'] . ']     ' . $test['title'] . '   =   ' . strip_tags( $test['value'] ) . "\n"; 
		}
		?></textarea>
</div>
