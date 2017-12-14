<?php
// @author Dustin Bolton 2012.
// Incoming variables: $destination
if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

$stash_allfiles_access_timelimit = 60*60*1; // Time, in seconds, to store transient allowing user access to all files in Stash once they have logged in.
?>


<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_hoveraction_copy' ).click( function() {
			var backup_file = jQuery(this).attr( 'rel' );
			var backup_url = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=<?php echo pb_backupbuddy::_GET( 'destination_id' ); ?>&remote_path=<?php echo htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ); ?>&cpy_file=' + backup_file + '&stashhash=' + jQuery('#pb_backupbuddy_stashhash').attr( 'rel' );
			
			window.location.href = backup_url;
			
			return false;
		} );
		
		jQuery( '.pb_backupbuddy_hoveraction_download_link' ).click( function() {
			var backup_file = jQuery(this).attr( 'rel' );
			var backup_url = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=<?php echo pb_backupbuddy::_GET( 'destination_id' ); ?>&remote_path=<?php echo htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ); ?>&downloadlink_file=' + backup_file + '&stashhash=' + jQuery('#pb_backupbuddy_stashhash').attr( 'rel' );
			
			window.location.href = backup_url;
			
			return false;
		} );
		
	});
</script>


<?php




// Load required files.
require_once( pb_backupbuddy::plugin_path() . '/destinations/stash/init.php' );
require_once( dirname( __FILE__ ) . '/lib/class.itx_helper.php' );
require_once( dirname( dirname( __FILE__ ) ) . '/_s3lib/aws-sdk/sdk.class.php' );


// Settings.
if ( isset( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')] ) ) {
	$settings = &pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET('destination_id')];
}
$settings = array_merge( pb_backupbuddy_destination_stash::$default_settings, $settings );
$itxapi_username = $settings['itxapi_username'];
$itxapi_password = $settings['itxapi_password'];


// Set up paths.
if ( pb_backupbuddy::_GET( 'remote_path' ) == '' ) {
	$remote_path = pb_backupbuddy_destination_stash::get_remote_path(); // Has leading and trailng slashes.  $settings['directory']
} else {
	$remote_path = pb_backupbuddy::_GET( 'remote_path' );
	if ( ( $remote_path != '/' ) && ( $remote_path != '' ) ) { // Only allow an empty path or a single slash.
		$remote_path = '/';
	}
}


// Lock out all file access without authentication.
function pb_backupbuddy_stash_pass_form() {
	echo 'Please enter your iThemes.com Member Password to access your full Stash listing including files stored from other sites:<br><br><br>';
	echo '<form method="post"><b>iThemes Member Password</b>: &nbsp;&nbsp;&nbsp; <input type="password" name="stash_password" size="20"> &nbsp;&nbsp;&nbsp; <input type="submit" name="submit" value="Authenticate" class="button button-primary">';
	pb_backupbuddy::nonce();
	echo '</form>';
	echo '<br><br><br><br>';
}


$stash_hash = '';
if ( pb_backupbuddy::_GET( 'stashhash' ) != '' ) {
	$stash_hash = pb_backupbuddy::_GET( 'stashhash' );
}
if ( ( $remote_path == '/' ) && ( $settings['manage_all_files'] == '1' ) ) {
	$stash_password = '';
	if ( pb_backupbuddy::_POST( 'stash_password' ) != '' ) {
		pb_backupbuddy::verify_nonce();
		$stash_password = pb_backupbuddy::_POST( 'stash_password' );
	}
	if ( ( $stash_password == '' ) && ( pb_backupbuddy::_GET( 'stashhash' ) == '' ) ) {
		pb_backupbuddy_stash_pass_form();
		return;
	} else {
		if ( pb_backupbuddy::_GET( 'stashhash' ) != '' ) {
			$itxapi_password =  pb_backupbuddy::_GET( 'stashhash' );
		} else {
			$itxapi_password = ITXAPI_Helper::get_password_hash( $itxapi_username, $stash_password ); // Generates hash for use as password for API.
		}
		$account_info = pb_backupbuddy_destination_stash::get_quota(
			array(
				'itxapi_username' => $itxapi_username,
				'itxapi_password' => $itxapi_password,
			),
			true // DO bypass caching so we can check authorization.
		);
		if ( $account_info === false ) {
			pb_backupbuddy_stash_pass_form();
			delete_transient( 'pb_backupbuddy_stashallfiles_' . $current_user->user_login );
			return;
		} else { // Valid login. Cache access for 1hr for this user.
			//echo 'settrans';
			//set_transient( 'pb_backupbuddy_stashallfiles_' . $current_user->user_login, $itxapi_password, $stash_allfiles_access_timelimit );
			$stash_hash = $itxapi_password;
		}
	}
}
echo '<span id="pb_backupbuddy_stashhash" rel="' . $itxapi_password . '" style="display: none;"></span>';


// Talk with the Stash API to get access to do things.
$stash = new ITXAPI_Helper( pb_backupbuddy_destination_stash::ITXAPI_KEY, pb_backupbuddy_destination_stash::ITXAPI_URL, $itxapi_username, $itxapi_password );

// Re-authenticating to Stash.
if ( '1' == pb_backupbuddy::_POST( 'stash_reauth' ) ) {
	$itxapi_username = strtolower( pb_backupbuddy::_POST( 'itxapi_username' ) );
	$itxapi_password = ITXAPI_Helper::get_password_hash( $itxapi_username, pb_backupbuddy::_POST( 'itxapi_password_raw' ) ); // Generates hash for use as password for API.
	
	$account_info = pb_backupbuddy_destination_stash::get_quota(
		array(
			'itxapi_username' => $itxapi_username,
			'itxapi_password' => $itxapi_password,
		),
		true // bypass caching.
	);
	if ( false !== $account_info ) { // New credentials are good. Update destination settings.
		$settings['itxapi_username'] = $itxapi_username;
		$settings['itxapi_password'] = $itxapi_password;
		pb_backupbuddy::save(); // $settings is a reference so this will save the propr destination settings.
		pb_backupbuddy::alert( __( 'Success re-authenticating to your Stash account.', 'it-l10n-backupbuddy' ) );
		echo '<br>';
	}
}

// Validate authentication with Stash.
$manage_data = pb_backupbuddy_destination_stash::get_manage_data( $settings, $suppressAuthAlert = true );

if ( ( ! is_array( $manage_data['credentials'] ) ) || ( '1' == pb_backupbuddy::_GET( 'force_stash_reauth' ) ) ) { // If not array it's because auth is invalid.
	if ( ! is_array( $manage_data['credentials'] ) ) { // Re-auth due to authentication failure. Other case could be a manual re-auth.
		echo '<h3>' . __( 'Stash Authentication Failed - Please log back in', 'it-l10n-backupbuddy' ) . '</h3>';
		_e( 'This is most often caused by changing your password.', 'it-l10n-backupbuddy' );
		echo ' ';
	}
	_e( 'Log back in with your iThemes.com member account below.', 'it-l10n-backupbuddy' );
	?>
	
	<form method="post" action="<?php echo pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ); ?>">
		<input type="hidden" name="stash_reauth" value="1">
		<table class="form-table">
			<tr>
				<th>iThemes username</th>
				<td><input type="text" name="itxapi_username"></td>
			</tr>
			<tr>
				<th>iThemes password</th>
				<td><input type="password" name="itxapi_password_raw"></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td><input type="submit" name="submit" value="Re-Authenticate" class="button button-primary"></td>
			</tr>
		</table>
	</form>
	
	<?php
	die();
}


$s3 = new AmazonS3( $manage_data['credentials'] );    // the key, secret, token
if ( $settings['ssl'] == '0' ) {
	@$s3->disable_ssl(true);
}


// Handle deletion.
if ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_backup' ) {
	pb_backupbuddy::verify_nonce();
	$deleted_files = array();
	foreach( (array)pb_backupbuddy::_POST( 'items' ) as $item ) {
		
		$response = $s3->delete_object( $manage_data['bucket'], $manage_data['subkey'] . $remote_path . $item );
		if ( $response->isOK() ) {
			$deleted_files[] = $item;
		} else {
			pb_backupbuddy::alert( 'Error: Unable to delete `' . $item . '`. Verify permissions.' );
		}
		
		
	}
	
	if ( count( $deleted_files ) > 0 ) {
		pb_backupbuddy::alert( 'Deleted ' . implode( ', ', $deleted_files ) . '.' );
		delete_transient( 'pb_backupbuddy_stashquota_' . $itxapi_username ); // Delete quota transient since it probably has changed now.
	}
	echo '<br>';
}


// Handle copying files to local
if ( pb_backupbuddy::_GET( 'cpy_file' ) != '' ) {
	pb_backupbuddy::alert( 'The remote file is now being copied to your local backups. If the backup gets marked as bad during copying, please wait a bit then click the `Refresh` icon to rescan after the transfer is complete.' );
	echo '<br>';
	pb_backupbuddy::status( 'details',  'Scheduling Cron for creating Stash copy.' );
	backupbuddy_core::schedule_single_event( time(), 'process_remote_copy', array( 'stash', pb_backupbuddy::_GET( 'cpy_file' ), $settings ) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
}


// Handle download link
if ( pb_backupbuddy::_GET( 'downloadlink_file' ) != '' ) {
	$link = $s3->get_object( $manage_data['bucket'], $manage_data['subkey'] . $remote_path . pb_backupbuddy::_GET( 'downloadlink_file' ), array('preauth'=>time()+3600));
	pb_backupbuddy::alert( 'You may download this backup (' . pb_backupbuddy::_GET( 'downloadlink_file' ) . ') with <a href="' . $link . '">this link</a>. The link is valid for one hour.' );
	echo '<br>';
}



// QUOTA INFORMATION.
$account_info = pb_backupbuddy_destination_stash::get_quota(
	array(
		'itxapi_username' => $itxapi_username,
		'itxapi_password' => $itxapi_password,
	),
	false // do NOT bypass caching.
);

/*
echo '<pre>';
print_r( $account_info );
echo '</pre>';
*/





echo pb_backupbuddy_destination_stash::get_quota_bar( $account_info );

echo '<div style="text-align: center;">';
echo '
<b>Upgrade to get more Stash space:</b> &nbsp;
<a href="https://ithemes.com/member/cart.php?action=add&id=290" target="_blank" style="text-decoration: none; font-weight: 300;">+ 5GB</a>, &nbsp;
<a href="https://ithemes.com/member/cart.php?action=add&id=291" target="_blank" style="text-decoration: none; font-weight: 600; font-size: 1.1em;">+ 10GB</a>, &nbsp;
<a href="https://ithemes.com/member/cart.php?action=add&id=292" target="_blank" style="text-decoration: none; font-weight: 800; font-size: 1.2em;">+ 25GB</a>
&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="https://sync.ithemes.com/stash/" target="_blank" style="text-decoration: none;"><b>Manage Files & Account</b></a>
';
echo '<br><br></div>';



// Welcome text.
$up_path = '/';
if ( $settings['manage_all_files'] == '1' ) {
	$manage_all_link = ' <a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&remote_path=' . $up_path . '" style="text-decoration: none; margin-left: 15px;" title="By default, Stash will display files in the Stash directory for this particular site. Clicking this will display files for all your sites in Stash.">List files for all sites</a>';
} else {
	$manage_all_link = '<!-- manage all disabled based on settings -->';
	if ( $remote_path == '/' ) {
		die( 'Access denied. Possible hacking attempt has been logged. Error #5549450.' );
	}
}
$reauth_link = ' <a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&force_stash_reauth=1" style="text-decoration: none; margin-left: 15px;" title="Re-authenticate to Stash or change the Stash account this Stash destination uses.">Re-authenticate</a>';
echo '<div style="font-size: 12px; text-align: center;"><b>Current Remote Directory</b>: ' . $remote_path . $manage_all_link . $reauth_link . '</div>';


// Get file listing.
$response = $s3->list_objects(
	$manage_data['bucket'],
	array(
		'prefix' => $manage_data['subkey'] . $remote_path
	)
);     // list all the files in the subscriber account

/*
echo '<pre>';
print_r( $response );
echo '</pre>';
*/

// Display prefix somewhere to aid in troubleshooting/support.
$subscriber_prefix = substr( $response->body->Prefix, 0, strpos( $response->body->Prefix, '/' ) );


// Get list of files.
$backup_list_temp = array();
foreach( $response->body->Contents as $object ) {
	
	$file = str_ireplace( $manage_data['subkey'] . $remote_path, '', $object->Key );
	$last_modified = strtotime( $object->LastModified );
	$size = (double) $object->Size;
	if ( substr( $file, 0, 3 ) == 'db/' ) { // Database backup
		$backup_type = 'Database';
	} elseif ( substr( $file, 0, 5 ) == 'full/' ) { // Full backup
		$backup_type = 'Full';
	} elseif( $file == 'importbuddy.php' ) {
		$backup_type = 'ImportBuddy Tool';
	} else {
		if ( stristr( $file, '/db/' ) !== false ) {
			$backup_type = 'Database';
		} elseif( stristr( $file, '/full/' ) !== false ) {
			$backup_type = 'Full';
		} else {
			$backup_type = 'Unknown';
		}
	}
	
	// Make list without directory in name.
	$removePrefixDirs = array(
		'full/',
		'db/',
	);
	$filesNoDir = str_replace( $removePrefixDirs, '', $file );
	
	// Generate array of table rows.
	$backup_list_temp[$last_modified] = array(
		array( $file, $filesNoDir ),
		pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $last_modified ) ) . '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $last_modified ) . ' ago)</span>',
		pb_backupbuddy::$format->file_size( $size ),
		$backup_type
	);

}


krsort( $backup_list_temp );
$backup_list = array();
foreach( $backup_list_temp as $backup_item ) {
	$backup_list[ $backup_item[0][0] ] = $backup_item; //str_replace( 'db/', '', str_replace( 'full/', '', $backup_item ) );
}
unset( $backup_list_temp );

$urlPrefix = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) );

// Render table listing files.
if ( count( $backup_list ) == 0 ) {
	echo '<b>';
	_e( 'You have not completed sending any backups to BackupBuddy Stash for this site yet.', 'it-l10n-backupbuddy' );
	echo '</b>';
} else {
	pb_backupbuddy::$ui->list_table(
		$backup_list,
		array(
			'action'		=>	pb_backupbuddy::ajax_url( 'remoteClient' ) . '&function=remoteClient&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&remote_path=' . htmlentities( pb_backupbuddy::_GET( 'remote_path' ) . '&stashhash=' . $stash_hash ),
			'columns'		=>	array( 'Backup File', 'Uploaded <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first">', 'File Size', 'Type' ),
			'hover_actions'	=>	array( $urlPrefix . '&cpy_file=' => 'Copy to Local', $urlPrefix . '&downloadlink_file=' => 'Get download link' ),
			'hover_action_column_key'	=>	'0',
			'bulk_actions'	=>	array( 'delete_backup' => 'Delete' ),
			'css'			=>		'width: 100%;',
		)
	);
}

// Display troubleshooting subscriber key.
echo '<span class="description" style="margin-top: -20px; float: right;">Subscriber key: ' . $subscriber_prefix . '</span>';
echo '<br style="clear: both;">';

return;
