<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::verify_nonce();

// Quick Start form saving.

/* quickstart_form()
*
* Saving Quickstart form.
*
*/


$errors = array();
$form = pb_backupbuddy::_POST();
//print_r( $form );

if ( ( '' != $form['email'] ) && ( false !== stristr( $form['email'], '@' ) ) ) {
	pb_backupbuddy::$options['email_notify_error'] = strip_tags( $form['email'] );
} else {
	$errors[] = 'Invalid email address.';
}

if ( ( '' != $form['password'] ) && ( $form['password'] == $form['password_confirm'] ) ) {
	pb_backupbuddy::$options['importbuddy_pass_hash'] = md5( $form['password'] );
	pb_backupbuddy::$options['importbuddy_pass_length'] = strlen( $form['password'] );
} elseif ( '' == $form['password'] ) {
	$errors[] = 'Please enter a password for restoring / migrating.';
} else {
	$errors[] = 'Passwords do not match.';
}


/***** BEGIN STASH v1 SETUP *****/


// Note: If existing Stash exists with this username then use that instead of making a new stash2 destination.
if ( 'stash' == pb_backupbuddy::_POST( 'destination' ) ) {
	if ( ( '' == pb_backupbuddy::_POST( 'stash_username' ) ) || ( '' == pb_backupbuddy::_POST( 'stash_password' ) ) ) { // A field is blank.
		$errors[] = 'You must enter your iThemes username & password to log in to the remote destination BackupBuddy Stash (v1).';
	} else { // Username and password provided.
		
		$itxapi_username = strtolower( pb_backupbuddy::_POST( 'stash_username' ) );
		
		// See if this user already exists.
		foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_index => $destination ) { // Loop through ending with the last created destination of this type.
			if ( 'stash' == $destination['type'] ) {
				if ( $itxapi_username == $destination['itxapi_username'] ) { // Existing destination match.
					$destination_id = $destination_index;
				}
			}
		}
		
		if ( ! isset( $destination_id ) ) { // Did not already find the same Stash destination.
			require_once( pb_backupbuddy::plugin_path() . '/destinations/stash/lib/class.itx_helper.php' );
			require_once( pb_backupbuddy::plugin_path() . '/destinations/stash/init.php' );
			
			$itxapi_username = strtolower( pb_backupbuddy::_POST( 'stash_username' ) );
			$itxapi_password = ITXAPI_Helper::get_password_hash( $itxapi_username, pb_backupbuddy::_POST( 'stash_password' ) ); // Generates hash for use as password for API.
			
			$account_info = pb_backupbuddy_destination_stash::get_quota(
				array(
					'itxapi_username' => $itxapi_username,
					'itxapi_password' => $itxapi_password,
				),
				true // bypass caching.
			);
			
			if ( false === $account_info ) { // Bad credentials.
				global $pb_backupbuddy_destination_errors;
				$errors[] = 'Unable to authenticate with Stash. Check your iThemes login credentials and try again. Details: `' . implode( ', ', $pb_backupbuddy_destination_errors ) . '`.';
			} else {
				if ( count( pb_backupbuddy::$options['remote_destinations'] ) > 0 ) {
					$nextDestKey = max( array_keys( pb_backupbuddy::$options['remote_destinations'] ) ) + 1;
				} else { // no destinations yet. first index.
					$nextDestKey = 0;
				}
				
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ] = pb_backupbuddy_destination_stash::$default_settings;
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ]['itxapi_username'] = pb_backupbuddy::_POST( 'stash_username' );
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ]['itxapi_password'] = $itxapi_password; // Hashed password.
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ]['title'] = 'My Stash (v1)';
				pb_backupbuddy::save();
				$destination_id = $nextDestKey;
			} // end if good credentials.
		} // end if no destination id already set.
		
	} // end if user and pass set.
} // end stash setup.


/***** END STASH v1 SETUP *****/


/***** BEGIN STASH v2 SETUP *****/


// Note: If existing Stash2 exists with this username then use that instead of making a new stash2 destination.
if ( 'stash2' == pb_backupbuddy::_POST( 'destination' ) ) {
	if ( ( '' == pb_backupbuddy::_POST( 'stash2_username' ) ) || ( '' == pb_backupbuddy::_POST( 'stash2_password' ) ) ) { // A field is blank.
		$errors[] = 'You must enter your iThemes username & password to log in to the remote destination BackupBuddy Stash (v2).';
	} else { // Username and password provided.
		
		//require_once(  );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/stash2/class.itx_helper2.php' );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/stash2/init.php' );
		global $wp_version;
		
		$itxapi_username = strtolower( pb_backupbuddy::_POST( 'stash2_username' ) );
		
		// See if this user already exists.
		foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_index => $destination ) { // Loop through ending with the last created destination of this type.
			if ( 'stash2' == $destination['type'] ) {
				if ( $itxapi_username == $destination['itxapi_username'] ) { // Existing destination match.
					$destination_id = $destination_index;
				}
			}
		}
		
		if ( ! isset( $destination_id ) ) { // Did not already find the same Stash destination.
			$password_hash = iThemes_Credentials::get_password_hash( $itxapi_username, pb_backupbuddy::_POST( 'stash2_password' ) );
			$access_token = ITXAPI_Helper2::get_access_token( $itxapi_username, $password_hash, site_url(), $wp_version );
			
			$settings = array(
				'itxapi_username' => $itxapi_username,
				'itxapi_password' => $access_token,
			);
			$response = pb_backupbuddy_destination_stash2::stashAPI( $settings, 'connect' );
			
			if ( ! is_array( $response ) ) { // Error message.
				$errors[] = 'Error #32898973: Unexpected server response. Check your Stash login and try again. Detailed response: `' . print_r( $response, true ) .'`.';
			} else {
				if ( isset( $response['error'] ) ) {
					$errors[] = $response['error']['message'];
				} else {
					if ( isset( $response['token'] ) ) {
						$itxapi_token = $response['token'];
					} else {
						$errors[] = 'Error #32977932: Unexpected server response. Token missing. Check your Stash login and try again. Detailed response: `' . print_r( $response, true ) .'`.';
					}
				}
			}
			
			// If we have the token then create the Stash2 destination.
			if ( isset( $itxapi_token ) ) {
				if ( count( pb_backupbuddy::$options['remote_destinations'] ) > 0 ) {
					$nextDestKey = max( array_keys( pb_backupbuddy::$options['remote_destinations'] ) ) + 1;
				} else { // no destinations yet. first index.
					$nextDestKey = 0;
				}
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ] = pb_backupbuddy_destination_stash2::$default_settings;
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ]['itxapi_username'] = pb_backupbuddy::_POST( 'stash2_username' );
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ]['itxapi_token'] = $itxapi_token;
				pb_backupbuddy::$options['remote_destinations'][ $nextDestKey ]['title'] = 'My Stash (v2)';
				pb_backupbuddy::save();
				$destination_id = $nextDestKey;
			}
		} // end $destination_id not set.
	} // end if user and pass set.
} // end stash setup.


/***** END STASH v2 SETUP *****/



if ( '' != $form['schedule'] ) {
	if ( ! isset( $destination_id ) ) {
		$destination_id = '';
		if ( '' != $form['destination_id'] ) { // Dest id explicitly set.
			$destination_id = $form['destination_id'];
		} else { // No explicit destination ID; deduce it.
			if ( '' != $form['destination'] ) {
				foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_index => $destination ) { // Loop through ending with the last created destination of this type.
					if ( $destination['type'] == $form['destination'] ) {
						$destination_id = $destination_index;
					}
				} // end foreach.
			}
		}
	} // end if ! isset( $destination_id ).
	
	function pb_backupbuddy_schedule_exist_by_title( $title ) {
		foreach( pb_backupbuddy::$options['schedules'] as $schedule ) {
			if ( $schedule['title'] == $title ) {
				return true;
			}
		}
		return false;
	}
	
	// STARTER
	if ( 'starter' == $form['schedule'] ) {
		
		$title = 'Weekly Database (Quick Setup - Starter)';
		if ( false === pb_backupbuddy_schedule_exist_by_title( $title ) ) {
			$add_response = backupbuddy_api::addSchedule(
				$title,
				$profile = '1',
				$interval = 'weekly',
				$first_run = ( time() + ( get_option( 'gmt_offset' ) * 3600 ) + 86400 ),
				$remote_destinations = array( $destination_id )
			);
			if ( true !== $add_response ) { $errors[] = $add_response; }
		}
		
		$title = 'Monthly Full (Quick Setup - Starter)';
		if ( false === pb_backupbuddy_schedule_exist_by_title( $title ) ) {
			$add_response = backupbuddy_api::addSchedule(
				$title,
				$profile = '2',
				$interval = 'monthly',
				$first_run = ( time() + ( get_option( 'gmt_offset' ) * 3600 ) + 86400 + 18000 ),
				$remote_destinations = array( $destination_id )
			);
			if ( true !== $add_response ) { $errors[] = $add_response; }
		}
		
	}
	
	// BLOGGER
	if ( 'blogger' == $form['schedule'] ) {
		
		$title = 'Daily Database (Quick Setup - Blogger)';
		if ( false === pb_backupbuddy_schedule_exist_by_title( $title ) ) {
			$add_response = backupbuddy_api::addSchedule(
				$title,
				$profile = '1',
				$interval = 'daily',
				$first_run = ( time() + ( get_option( 'gmt_offset' ) * 3600 ) + 86400 ),
				$remote_destinations = array( $destination_id )
			);
			if ( true !== $add_response ) { $errors[] = $add_response; }
		}
		
		$title = 'Weekly Full (Quick Setup - Blogger)';
		if ( false === pb_backupbuddy_schedule_exist_by_title( $title ) ) {
			$add_response = backupbuddy_api::addSchedule(
				$title,
				$profile = '2',
				$interval = 'weekly',
				$first_run = ( time() + ( get_option( 'gmt_offset' ) * 3600 ) + 86400 + 18000 ),
				$remote_destinations = array( $destination_id )
			);
			if ( true !== $add_response ) { $errors[] = $add_response; }
		}
		
	}
	
	
} // end set schedule.


if ( 0 == count( $errors ) ) {
	pb_backupbuddy::save();
	die( 'Success.' );
} else {
	die( '* ' . implode( "\n* ", $errors ) );
}

