<?php
$ITXAPI_KEY = 'ixho7dk0p244n0ob';
$ITXAPI_URL = 'http://api.ithemes.com';
					


$credentials_form = new pb_backupbuddy_settings( 'pre_settings', false, 'upload=stash#pluginbuddy-tabs-stash' ); // name, savepoint|false, additional querystring
/*
$credentials_form->add_setting( array(
	'type'		=>		'hidden',
	'name'		=>		'pass_hash',
	'default'	=>		PB_PASSWORD,
) );

$credentials_form->add_setting( array(
	'type'		=>		'hidden',
	'name'		=>		'options',
	'default'	=>		htmlspecialchars( serialize( pb_backupbuddy::$options ) ),
) );
*/

$credentials_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'itxapi_username',
	'title'		=>		__( 'iThemes username', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-45]',
) );
$credentials_form->add_setting( array(
	'type'		=>		'password',
	'name'		=>		'itxapi_password_raw',
	'title'		=>		__( 'iThemes password', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-45]',
) );

$settings_result = $credentials_form->process();
$login_welcome = __( 'Connect to Stash with your iThemes.com member account to select a backup to restore.', 'it-l10n-backupbuddy' ) . '<br><br>';

if ( count( $settings_result ) == 0 ) { // No form submitted.
	
	echo $login_welcome;
	$credentials_form->display_settings( 'Connect to Stash' );
	
} else { // Form submitted.
	if ( count( $settings_result['errors'] ) > 0 ) { // Form errors.
		echo $login_welcome;
		
		pb_backupbuddy::alert( implode( '<br>', $settings_result['errors'] ) );
		$credentials_form->display_settings( 'Connect to Stash' );
		
	} else { // No form errors; process!
		
		
		$itx_helper_file = dirname( dirname( __FILE__ ) ) . '/classes/class.itx_helper.php';
		require_once( $itx_helper_file );
		
		$itxapi_username = $settings_result['data']['itxapi_username'];
		$itxapi_password = ITXAPI_Helper::get_password_hash( $itxapi_username, $settings_result['data']['itxapi_password_raw'] ); // Generates hash for use as password for API.
		
		
		$requestcore_file = dirname( dirname( __FILE__ ) ) . '/lib/requestcore/requestcore.class.php';
		require_once( $requestcore_file );
		
		
		$stash = new ITXAPI_Helper( $ITXAPI_KEY, $ITXAPI_URL, $itxapi_username, $itxapi_password );
		
		$files_url = $stash->get_files_url();
		
		$request = new RequestCore( $files_url );
		$response = $request->send_request(true);
		
		// See if the request was successful.
		if(!$response->isOK())
			pb_backupbuddy::status( 'error', 'Stash request for files failed.' );
		
		// See if we got a json response.
		if(!$stash_files = json_decode($response->body, true))
			pb_backupbuddy::status( 'error', 'Stash did not get valid json response.' );
		
		// Finally see if the API returned an error.
		if(isset($stash_files['error'])) {            
			if ( $stash_files['error']['code'] == '3002' ) {
				pb_backupbuddy::alert( 'Invalid iThemes.com Member account password. Please verify your password. <a href="http://ithemes.com/member/member.php" target="_blank">Forget your password?</a>' );
			} else {
				pb_backupbuddy::alert( implode( ' - ', $stash_files['error'] ) );
			}
			
			$credentials_form->display_settings( 'Submit' );
		} else { // NO ERRORS
			
			/*
			echo '<pre>';
			print_r( $stash_files );
			echo '</pre>';
			*/
			
			$backup_list_temp = array();
			foreach( $stash_files['files'] as $stash_file ) {
				$file = $stash_file['filename'];
				$url = $stash_file['link'];
				$size = $stash_file['size'];
				$modified = $stash_file['last_modified'];
				
				if ( substr( $file, 0, 3 ) == 'db/' ) {
					$backup_type = 'Database';
				} elseif ( substr( $file, 0, 5 ) == 'full/' ) {
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
				
				$backup_list_temp[ $modified ] = array(
					$url,
					$file,
					pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $modified ) ) . '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $modified ) . ' ago)</span>',
					pb_backupbuddy::$format->file_size( $size ),
					$backup_type,
				);
			}
			
			krsort( $backup_list_temp );
			
			$backup_list = array();
			foreach( $backup_list_temp  as $backup_item ) {
				$backup_list[ $backup_item[0] ] = array(
					$backup_item[1],
					$backup_item[2],
					$backup_item[3],
					$backup_item[4],
					'<form action="?#pluginbuddy-tabs-server" method="POST">
						<input type="hidden" name="pass_hash" value="' . PB_PASSWORD . '">
						<input type="hidden" name="upload" value="stash">
						<input type="hidden" name="options" value="' . htmlspecialchars( serialize( pb_backupbuddy::$options ) ) . '">
						<input type="hidden" name="link" value="' . $backup_item[0] . '">
						<input type="hidden" name="itxapi_username" value="' . $itxapi_username . '">
						<input type="hidden" name="itxapi_password" value="' . $itxapi_password . '">
						<input type="submit" name="submit" value="Select" class="button-primary">
					</form>
					'
				);
			}
			unset( $backup_list_temp );
			
			
			// Render table listing files.
			if ( count( $backup_list ) == 0 ) {
				echo '<b>';
				_e( 'You have not sent any backups to Stash yet (or files are still transferring).', 'it-l10n-backupbuddy' );
				echo '</b>';
			} else {
				pb_backupbuddy::$ui->list_table(
					$backup_list,
					array(
						//'action'		=>	pb_backupbuddy::page_url() . '&custom=remoteclient&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) ) . '&remote_path=' . htmlentities( pb_backupbuddy::_GET( 'remote_path' ) ),
						'columns'		=>	array( 'Backup File', 'Uploaded <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first">', 'File Size', 'Type', '&nbsp;' ),
						'css'			=>		'width: 100%;',
					)
				);
			}
			
			
			
			if ( $stash_files === false ) {
				$credentials_form->display_settings( 'Submit' );
			}
		} // end no errors getting file info from API.
		
	}
	
} // end form submitted.

?>

<br><br>
<i>You can manage your Stash backups at the <a href="http://ithemes.com/member/panel/stash.php">iThemes Stash Panel</a></i>

