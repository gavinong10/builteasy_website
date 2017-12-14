<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

class pb_backupbuddy_destination_stash2 { // Change class name end to match destination name.
	
	const MINIMUM_CHUNK_SIZE = 5; // Minimum size, in MB to allow chunks to be. Anything less will not be chunked even if requested.
	const API_URL = 'https://stash-api.ithemes.com';
	
	public static $destination_info = array(
		'name'			=>		'BackupBuddy Stash (v2)',
		'description'	=>		'<b>The easiest of all destinations</b> for PHP v5.3.3+; just enter your iThemes login and Stash away! Store your backups in the BackupBuddy cloud safely with high redundancy and encrypted storage.  Supports multipart uploads for larger file support with both bursting and chunking. Active BackupBuddy customers receive <b>free</b> storage! Additional storage upgrades optionally available. <a href="http://ithemes.com/backupbuddy-stash/" target="_blank">Learn more here.</a>',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'						=>		'stash2',	// MUST MATCH your destination slug. Required destination field.
		'title'						=>		'',			// Required destination field.
		
		'itxapi_username'			=>		'',			// Username to connect to iThemes API.
		'itxapi_token'				=>		'',			// Site token for iThemes API.
		
		'ssl'						=>		'1',		// Whether or not to use SSL encryption for connecting.
		'server_encryption'			=>		'AES256',	// Encryption (if any) to have the destination enact. Empty string for none.
		'max_time'					=>		'',			// Default max time in seconds to allow a send to run for. Set to 0 for no time limit. Aka no chunking.
		'max_burst'					=>		'10',		// Max size in mb of each burst within the same page load.
		'db_archive_limit'			=>		'0',		// Maximum number of db backups for this site in this directory for this account. No limit if zero 0.
		'full_archive_limit' 		=>		'0',		// Maximum number of full backups for this site in this directory for this account. No limit if zero 0.
		'files_archive_limit' 		=>		'0',		// Maximum number of files only backups for this site in this directory for this account. No limit if zero 0.
		'manage_all_files'			=>		'0',		// Allow user to manage all files in Stash? If enabled then user can view all files after entering their password. If disabled the link to view all is hidden.
		'use_packaged_cert'			=>		'0',		// When 1, use the packaged cacert.pem file included with the AWS SDK.
		'disable_file_management'	=>		'0',		// When 1, _manage.php will not load which renders remote file management DISABLED.
		'disabled'					=>		'0',		// When 1, disable this destination.
		'skip_bucket_prepare'		=>		'1',		// Always skip bucket prepare for Stash.
		'stash_mode'				=>		'1',		// Master destination is Stash.
		
		'_multipart_id'				=>		'',			// Instance var. Internal use only for continuing a chunked upload.
	);
	
	
	
	public static function _init( $settings ) {
		require_once( pb_backupbuddy::plugin_path() . '/destinations/s32/init.php' );
		
		$settings = self::_formatSettings( $settings );
		return $settings;
	} // End _init().
	
	
	
	/*	send()
	 *	
	 *	Send one or more files.
	 *	
	 *	@param		array			$files			Array of one or more files to send.
	 *	@return		boolean|array					True on success, false on failure, array if a multipart chunked send so there is no status yet.
	 */
	public static function send( $settings = array(), $file, $send_id = '', $delete_after = false, $clear_uploads = false ) {
		$settings = self::_init( $settings );
		
		if ( '1' == $settings['disabled'] ) {
			self::_error( __( 'Error #48933: This destination is currently disabled. Enable it under this destination\'s Advanced Settings.', 'it-l10n-backupbuddy' ) );
			return false;
		}
		if ( is_array( $file ) ) {
			$file = $files[0];
		}
		
		if ( '' == $settings['_multipart_id'] ) { // New transfer. Populate initial Stash settings.
			$file_size = filesize( $file );
			$remote_path = self::get_remote_path(); // Has leading and trailng slashes.
			$backup_type = backupbuddy_core::getBackupTypeFromFile( $file );
			if ( '' == $backup_type ) { // unknown backup type
				$backup_type_path = '';
			} else { // known backup type. store in subdir.
				$backup_type_path = $backup_type . '/';
			}
			$additionalParams =array(
				'filename' => $remote_path . $backup_type_path . basename( $file ),
				'size'     => $file_size,
				'timezone' => get_option('timezone_string')
			);
			$response = self::stashAPI( $settings, 'upload', $additionalParams );
			if ( ! is_array( $response ) ) {
				$error = 'Error #832973: Unable to initiate Stash (v2) upload. Details: `' . $response . '`.';
				self::_error( $error );
				return false;
			}
			$backup_type = backupbuddy_core::getBackupTypeFromFile( $file );
			if ( pb_backupbuddy::$options['log_level'] == '3' ) { // Full logging enabled.
				pb_backupbuddy::status( 'details', 'Stash API upload action response due to logging level: `' . print_r( $response, true ) . '`. Call params: `' . print_r( $additionalParams, true ) . ' `.' );
			}
			$settings['stash_mode'] = '1'; // Stash is calling the s32 destination.
			$settings['bucket'] = $response['bucket'];
			$settings['credentials'] = $response['credentials'];
			$settings['_stash_object'] = $response['object'];
			$settings['_stash_upload_id'] = $response['upload_id'];
			/*
			$settings['_multipart_id'] = $response['upload_id'];
			$settings['_multipart_partnumber'] = 0;
			$settings['_multipart_file'] = $file;
			$settings['_multipart_remotefile'] = $response['object']; //$remote_path . basename( $file );
			$settings['_multipart_counts'] = pb_backupbuddy_destination_s32::_get_multipart_counts( $file_size, $settings['max_burst'] * 1024 * 1024 ); // Size of chunks expected to be in bytes.
			$settings['_multipart_backup_type'] = $backup_type;
			$settings['_multipart_backup_size'] = $file_size;
			*/
		}
		//error_log( print_r( $settings, true ) );
		
		// Send file.
		$result = pb_backupbuddy_destination_s32::send( $settings, $file, $send_id, $delete_after, $clear_uploads );
		
		if ( false === $result ) { // Notify Stash if failure.
			self::_uploadFailed( $settings );
		}
		
		return $result;
	} // End send().
	
	
	
	public static function test( $settings ) {
		$settings = self::_init( $settings );
		return pb_backupbuddy_destination_s32::test( $settings );
	} // End test().
	
	
	
	/* stashAPI()
	 *
	 * Communicate with the Stash API.
	 *
	 * @param	array 			$settings	Destination settings array.
	 * @param	string			$action		API verb/action to call.
	 * @return	array|string				Array with response data on success. String with error message if something went wrong. Auto-logs all errors to status log.
	 */
	public static function stashAPI( $settings, $action, $additionalParams = array() ) {
		global $wp_version;
		
		$url_params = array(
			'action'    => $action,
			'user'      => $settings['itxapi_username'],
			'wp'        => $wp_version,
			'bb'		=> pb_backupbuddy::settings( 'version' ),
			'site'      => site_url(),
			'timestamp' => time()
		);
		
		if ( isset( $settings['itxapi_password' ] ) ) { // Used on initital connection to  
			$params = array( 'auth_token' => $settings['itxapi_password'] ); // itxapi_password is a HASH of user's password.
		} elseif ( isset( $settings['itxapi_token' ] ) ) { // Used on initital connection to  
			$params = array( 'token' => $settings['itxapi_token'] ); // itxapi_password is a HASH of user's password.
		} else {
			$error = 'BackupBuddy Error #438923983: No valid token (itxapi_token) or hashed password (itxapi_password) specified. This should not happen.';
			self::_error( $error );
			trigger_error( $error, E_USER_NOTICE );
			return $error;
		}
		
		$params = array_merge( $params, $additionalParams );
		
		$post_url = self::API_URL . '/?' . http_build_query( $url_params, null, '&' );
		$http_data = array(
			'method' => 'POST',
			'timeout' => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'body' => array( 'request' => json_encode( $params ) ),
			'cookies' => array()
		);
		
		$response = wp_remote_post(
			$post_url,
			$http_data
		);
		
		/*
		echo 'POST URL: ' .$post_url . '<br>';
		echo '<pre>';
		print_r( $http_data );
		echo '</pre>';
		*/
		
		if ( is_wp_error( $response ) ) {
			$error = 'Error #3892774: `' . $response->get_error_message() . '`.';
			self::_error( 'error', $error );
			return $error;
		} else {
			if ( null !== ( $response_decoded = json_decode( $response['body'], true  ) ) ) {
				if ( isset( $response_decoded['error'] ) ) {
					if ( isset( $response_decoded['error']['message'] ) ) {
						$error = 'Error #32893. Details: `' . print_r( $response_decoded['error'], true ) . '`.';
						self::_error( $error );
						return $response_decoded['error']['message'];
					} else {
						$error = 'Error #3823973. Received Stash API error but no message found. Details: `' . print_r( $response_decoded, true ) . '`.';
						self::_error( $error );
						return $error;
					}
				} else {
					/*
					echo 'Response: ';
					echo '<pre>';
					print_r( $response_decoded );
					echo '</pre>';
					*/
					return $response_decoded;
				}
			} else {
				$error = 'Error #8393833: Unexpected server response: `' . htmlentities( $response['body'] ) . '`.';
				self::_error( $error );
				return $error;
			}
		}
	} // End stashAPI().
	
	
	
	/* get_quota()
	 *
	 * Get Stash quota.
	 *
	 */
	public static function get_quota( $settings, $bypass_cache = false ) {
		$settings = self::_init( $settings );
		
		$cache_time = 60*5; // 5 minutes.
		$bypass_cache = true;
		
		if ( false === $bypass_cache ) {
			$transient = get_transient( 'pb_backupbuddy_stash2quota_' . $settings['itxapi_username'] );
			if ( $transient !== false ) {
				pb_backupbuddy::status( 'details', 'Stash quota information CACHED. Returning cached version.' );
				return $transient;
			}
		} else {
			pb_backupbuddy::status( 'details', 'Stash bypassing cached quota information. Getting new values.' );
		}
		
		// Contact API.
		$quota_data = self::stashAPI( $settings, 'quota' );
		
		/*
		echo "QUOTARESULTS:";
		echo '<pre>';
		print_r( $quota_data );
		echo '</pre>';
		*/
		
		if ( ! is_array( $quota_data ) ) {
			return false;
		} else {
			set_transient( 'pb_backupbuddy_stash2quota_' . $settings['itxapi_username'], $quota_data, $cache_time );
			return $quota_data;
		}
		
	} // End get_quota().
	
	
	
	/*	get_manage_data()
	 *	
	 *	Get the required credentials and management data for managing user files.
	 *	
	 *	@param		array	$settings		Destination settings.
	 *	@param		bool	$hideAuthAlert	Default: false. Whether or not to suppress an alert box if authentication is failing. Useful for showing a more friendly message for that common error, or a re-auth form.
	 *	@return		false|array				Boolean false on failure. Array of data on success.
	 */
	public static function get_manage_data( $settings, $suppressAuthAlert = false ) {
		$settings = self::_init( $settings );
		
		$remote_path = self::get_remote_path(); // Has leading and trailng slashes.
		
		array( 'token'    => SITE_TOKEN,
			'filename' => $remote_path . '/uploadtest.jpg',
			'size'     => '329041',
			'timezone' => 'America/Chicago'
		);
		
		return $manage_data;
	} // End get_manage_data().
	
	
	
	/*	get_remote_path()
	 *	
	 *	Returns the site-specific remote path to store into.
	 *	Slashes (caused by subdirectories in url) are replaced with underscores.
	 *	Always has a leading and trailing slash.
	 *	
	 *	@return		string			Ex: /dustinbolton.com_blog/
	 */
	public static function get_remote_path( $directory = '' ) {
		
		$remote_path = site_url();
		$remote_path = str_ireplace( 'http://', '', $remote_path );
		$remote_path = str_ireplace( 'https://', '', $remote_path );
		
		//$remote_path = preg_replace('/[^\da-z]/i', '_', $remote_path );
		
		$remote_path = str_ireplace( '/', '_', $remote_path );
		$remote_path = str_ireplace( '~', '_', $remote_path );
		$remote_path = str_ireplace( ':', '_', $remote_path );
		
		$remote_path = '/' . trim( $remote_path, '/\\' ) . '/';
		
		$directory = trim( $directory, '/\\' );
		if ( $directory != '' ) {
			$remote_path .= $directory . '/';
		}
		
		return $remote_path;
		
	} // End get_remote_path().
	
	
	
	/*	get_quota_bar()
	 *	
	 *	Returns the progress quota bar showing usage.
	 *	
	 *	@param		array 			Array of account info from API call.
	 *	@param		string			HTML to append below bar, eg for more options.
	 *	@return		string			HTML for the quota bar.
	 */
	public static function get_quota_bar( $account_info, $settings = array(), $additionalOptions = false ) {
		$settings = self::_init( $settings );
		//echo '<pre>' . print_r( $account_info, true ) . '</pre>';
		
		$return = '<div class="backupbuddy-stash2-quotawrap">';
		$return .= '
		<style>
			.outer_progress {
				-moz-border-radius: 4px;
				-webkit-border-radius: 4px;
				-khtml-border-radius: 4px;
				border-radius: 4px;
				
				border: 1px solid #DDD;
				background: #EEE;
				
				max-width: 700px;
				
				margin-left: auto;
				margin-right: auto;
				
				height: 30px;
			}
			
			.inner_progress {
				border-right: 1px solid #85bb3c;
				background: #8cc63f url("' . pb_backupbuddy::plugin_url() . '/destinations/stash2/progress.png") 50% 50% repeat-x;
				
				height: 100%;
			}
			
			.progress_table {
				color: #5E7078;
				font-family: "Open Sans", Arial, Helvetica, Sans-Serif;
				font-size: 14px;
				line-height: 20px;
				text-align: center;
				
				margin-left: auto;
				margin-right: auto;
				margin-bottom: 20px;
				max-width: 700px;
			}
		</style>';
		
		if ( isset( $account_info['quota_warning'] ) && ( $account_info['quota_warning'] != '' ) ) {
			//echo '<div style="color: red; max-width: 700px; margin-left: auto; margin-right: auto;"><b>Warning</b>: ' . $account_info['quota_warning'] . '</div><br>';
		}
		
		$return .= '
		<div class="outer_progress">
			<div class="inner_progress" style="width: ' . $account_info['quota_used_percent'] . '%"></div>
		</div>
		
		<table align="center" class="progress_table">
			<tbody><tr align="center">
			    <td style="width: 10%; font-weight: bold; text-align: center">Free Tier</td>
			    <td style="width: 10%; font-weight: bold; text-align: center">Paid Tier</td>        
			    <td style="width: 10%"></td>
			    <td style="width: 10%; font-weight: bold; text-align: center">Total</td>
			    <td style="width: 10%; font-weight: bold; text-align: center">Used</td>
			    <td style="width: 10%; font-weight: bold; text-align: center">Available</td>        
			</tr>

			<tr align="center">
			    <td style="text-align: center">' . $account_info['quota_free_nice'] . '</td>
			    <td style="text-align: center">';
			    if ( $account_info['quota_paid'] == '0' ) {
			    	$return .= 'none';
			    } else {
			    	$return .= $account_info['quota_paid_nice'];
			    }
			    $return .= '</td>
			    <td></td>
			    <td style="text-align: center">' . $account_info['quota_total_nice'] . '</td>
			    <td style="text-align: center">' . $account_info['quota_used_nice'] . ' (' . $account_info['quota_used_percent'] . '%)</td>
			    <td style="text-align: center">' . $account_info['quota_available_nice'] . '</td>
			</tr>
			';
		$return .= '
		</tbody></table>';
		
		$return .= '<div style="text-align: center;">';
		$return .= '
		<b>' . __( 'Upgrade storage', 'it-l10n-backupbuddy' ) . ':</b> &nbsp;
		<a href="https://ithemes.com/member/cart.php?action=add&id=290" target="_blank" style="text-decoration: none;">+ 5GB</a>, &nbsp;
		<a href="https://ithemes.com/member/cart.php?action=add&id=291" target="_blank" style="text-decoration: none;">+ 10GB</a>, &nbsp;
		<a href="https://ithemes.com/member/cart.php?action=add&id=292" target="_blank" style="text-decoration: none;">+ 25GB</a>
		
		&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="https://sync.ithemes.com/stash/" target="_blank" style="text-decoration: none;"><b>Manage Files & Account</b></a>';
		
		// Welcome text.
		$up_path = '/';
		if ( ( count( $settings) > 0 ) && ( true === $additionalOptions ) ) {
			if ( $settings['manage_all_files'] == '1' ) {
				if ( 'true' != pb_backupbuddy::_GET( 'listAll' ) ) {
					$manage_all_link = '<a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&listAll=true" style="text-decoration: none;" title="By default, Stash will display files in the Stash directory for this particular site. Clicking this will display files for all your sites in Stash.">List all site\'s files</a>';
				} else {
					$manage_all_link = '<a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&listAll=false" style="text-decoration: none;" title="By default, Stash will display files in the Stash directory for this particular site. Clicking this will only show this site\'s files in Stash.">Only list this site\'s files</a>';
				}
				$return .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' . $manage_all_link;
			}
		}
		
		$return .= '<br><br></div>';
		$return .= '</div>';
		
		return $return;
		
	} // End get_quota_bar().
	
	
	
	/* _formatSettings()
	 *
	 * Called by _formatSettings().
	 *
	 */
	public static function _formatSettings( $settings ) {
		$settings['skip_bucket_prepare'] = '1';
		$settings['stash_mode'] = '1';
		return pb_backupbuddy_destination_s32::_formatSettings( $settings );
	} // End _formatSettings().
	
	
	
	/* listFiles()
	 *
	 * Get list of files with optional prefix. Returns stashAPI response.
	 *
	 */
	public static function listFiles( $settings, $prefix ) {
		$settings = self::_init( $settings );
		
		$remote_path = self::get_remote_path(); // Has leading and trailng slashes.
		$additionalParams = array( 'prefix' => $prefix );
		$files = self::stashAPI( $settings, 'files', $additionalParams );
		return $files;
		
	} // End listFiles().
	
	
	
	/* deleteFile()
	 *
	 * Alias to deleteFiles().
	 *
	 */
	public static function deleteFile( $settings, $file ) {
		if ( ! is_array( $file ) ) {
			$file = array( $file );
		}
		return self::deleteFiles( $settings, $file );
	} // End deleteFile().
	
	
	
	/* deleteFiles()
	 *
	 * Delete files.
	 *	@param		array		$settings	Destination settings.
	 *	@return		bool|string				True if success, else string error message.
	 */
	public static function deleteFiles( $settings, $files = array() ) {
		$settings = self::_init( $settings );
		if ( ! is_array( $files ) ) {
			$file = array( $files );
		}
		
		$remote_path = self::get_remote_path(); // Has leading and trailng slashes.
		$additionalParams =array();
		$manage_data = self::stashAPI( $settings, 'manage', $additionalParams );
		if ( ! is_array( $manage_data ) ) {
			$error = 'Error #47349723: Unable to initiate file deletion for file(s) `' . implode( ', ', $files ) . '`. Details: `' . $manage_data . '`.';
			self::_error( $error );
			return $error;
		}
		$settings['bucket'] = $manage_data['bucket'];
		$settings['credentials'] = $manage_data['credentials'];
		
		foreach( $files as &$file ) {
			$file = $manage_data['subkey'] . $remote_path . $file;
		}
		
		return pb_backupbuddy_destination_s32::deleteFiles( $settings, $files );
	} // End deleteFiles().
	
	
	
	public static function archiveLimit( $settings, $backup_type ) {
		$settings = self::_init( $settings );
		
		if ( $backup_type == 'full' ) {
			$limit = $settings['full_archive_limit'];
			pb_backupbuddy::status( 'details', 'Full backup archive limit of `' . $limit . '` of type `full` based on destination settings.' );
		} elseif ( $backup_type == 'db' ) {
			$limit = $settings['db_archive_limit'];
			pb_backupbuddy::status( 'details', 'Database backup archive limit of `' . $limit . '` of type `db` based on destination settings.' );
		} elseif ( $backup_type == 'files' ) {
			$limit = $settings['files_archive_limit'];
			pb_backupbuddy::status( 'details', 'Database backup archive limit of `' . $limit . '` of type `files` based on destination settings.' );
		} else {
			$limit = 0;
			pb_backupbuddy::status( 'warning', 'Warning #237332. Unable to determine backup type (reported: `' . $backup_type . '`) so archive limits NOT enforced for this backup.' );
		}
		if ( ( '' != $limit ) && ( $limit > 0 ) ) {
			
			pb_backupbuddy::status( 'details', 'Archive limit enforcement beginning.' );
			
			// Get file listing.
			$files = self::listFiles( $settings, $prefix = '' );
			if ( ! is_array( $files ) ) {
				pb_backupbuddy::status( 'Error #3892383: Unable to list files. Skipping archive limiting.' );
				return false;
			}
			$remotePath = 'backup-' . backupbuddy_core::backup_prefix();
			$prefixLen = strlen( backupbuddy_core::backup_prefix() );
			
			// List backups associated with this site by date.
			$backups = array();
			foreach( $files as $file ) {
				if ( $file['backup_type'] != $backup_type ) {
					continue;
				}
				if ( ! backupbuddy_core::startsWith( basename( $file['filename'] ), $remotePath ) ) { // Only show backups for this site unless set to show all.
					continue;
				}
				
				$backups[ $file['filename'] ] = $file['uploaded_timestamp'];
			}
			unset( $files );
			arsort( $backups );
			
			pb_backupbuddy::status( 'details', 'Found `' . count( $backups ) . '` backups of this type when checking archive limits.' );
			if ( ( count( $backups ) ) > $limit ) {
				pb_backupbuddy::status( 'details', 'More archives (' . count( $backups ) . ') than limit (' . $limit . ') allows. Trimming...' );
				$i = 0;
				$delete_fail_count = 0;
				foreach( $backups as $buname => $butime ) {
					$i++;
					if ( $i > $limit ) {
						pb_backupbuddy::status( 'details', 'Trimming excess file `' . $buname . '`...' );
						$delete_response = self::deleteFile( $settings, substr( $buname, $prefixLen + 1 ) );
						if ( true !== $delete_response ) {
							self::_error( 'Unable to delete excess Stash file `' . $buname . '`. Details: `' . $delete_response . '`.' );
							$delete_fail_count++;
						}
					}
				} // end foreach.
				pb_backupbuddy::status( 'details', 'Finished trimming excess backups.' );
				if ( $delete_fail_count !== 0 ) {
					$error_message = 'Stash remote limit could not delete ' . $delete_fail_count . ' backups.';
					pb_backupbuddy::status( 'error', $error_message );
					backupbuddy_core::mail_error( $error_message );
				}
			}
			
			pb_backupbuddy::status( 'details', 'Stash completed archive limiting.' );
			
		} else {
			pb_backupbuddy::status( 'details',  'No Stash archive file limit to enforce.' );
		} // End remote backup limit
		
		return true;
	} // End archiveLimit();
	
	
	
	// find Xth occurance position from the write.
	public static function strrpos_count($haystack, $needle, $count) {
		if ($count <= 0)
			return false;
		
		$len = strlen($haystack);
		$pos = $len;
		
		for ($i = 0; $i < $count && $pos; $i++)
			$pos = strrpos($haystack, $needle, $pos - $len - 1);
		
		return $pos;
	} // End _strrpost_count().
	
	
	
	/* _error()
	 *
	 * Log error into status logger and destination error global. Returns false.
	 *
	 */
	private static function _error( $message ) {
		global $pb_backupbuddy_destination_errors;
		$pb_backupbuddy_destination_errors[] = $message;
		pb_backupbuddy::status( 'error', $message );
		return false;
	}
	
	
	/* _uploadFailed()
	 *
	 * Reports to the Stash API that the upload failed.
	 * @return	bool		True if reporting failure succeeded, else false.
	 *
	 */
	public static function _uploadFailed( $settings ) {
		pb_backupbuddy::status( 'details', 'Notifying Stash of upload failure.' );
		$additionalParams =array(
			'upload_id' => $settings['_stash_upload_id'],
		);
		$failResult = self::stashAPI( $settings, 'upload-fail', $additionalParams );
		if ( ( ! isset( $failResult['success'] ) || ( true !== $failResult['success'] ) ) ) {
			pb_backupbuddy::status( 'error', 'Warning #32736326: Error notifying Stash of upload failure. Details: `' . print_r( $failResult, true ) . '`.' );
			return false;
		} else {
			pb_backupbuddy::status( 'details', 'Stash notified of upload fail.' );
			return true;
		}
	} // End _uploadFailed().
	
	
	
} // End class.


