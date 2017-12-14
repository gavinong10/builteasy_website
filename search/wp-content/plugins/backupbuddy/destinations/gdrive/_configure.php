<?php
/*
	Pre-populated variables coming into this script:
		$destination_settings
		$mode
		$destination_id
*/

global $pb_hide_save;
global $pb_hide_test;
$pb_hide_save = true;
$pb_hide_test = true;

$default_name = NULL;

set_include_path( pb_backupbuddy::plugin_path() . '/destinations/gdrive/' . PATH_SEPARATOR . get_include_path());

if ( 'add' == $mode ) {
	if ( 'auth_gdrive' == pb_backupbuddy::_POST( 'gaction' ) ) {
		$client_id = trim( pb_backupbuddy::_POST( 'client_id' ) );
		$client_secret = trim( pb_backupbuddy::_POST( 'client_secret' ) );
		if ( ( '' == $client_id ) || ( '' == $client_secret ) ) {
			$_POST[ 'gaction' ] = ''; // Go back to auth.
			pb_backupbuddy::alert( 'Error #484834: Missing fields. All fields required.', true );
		}
	}
	
	
	
	if ( 'auth_gdrive' != pb_backupbuddy::_POST( 'gaction' ) ) {
		?>
		
		<ol>
			<li><a href="https://console.developers.google.com/project?authuser=0" target="_blank" class="button secondary-button" style="vertical-align: 0;">Open Google API Console in a new window</a></li>
			<li>In the new window select <span class="pb_label pb_label-subtle">Create Project</span> and name it something like "BackupBuddy" & <span class="pb_label pb_label-subtle">Create</span>. Wait for the next screen to display.</li>
			<li>Once the project is created, from the left menu expand <span class="pb_label pb_label-subtle">APIs & auth</span> then <span class="pb_label pb_label-subtle">APIs</span></li>
			<li>Beneath Google Apps APIs select <span class="pb_label pb_label-subtle">Drive API</span> then <span class="pb_label pb_label-subtle">Enable API</span> (Make sure you select Drive <b>API</b>, <i>not</i> Drive <b>SDK</b>)</li>
			<li>From the left menu under <span class="pb_label pb_label-subtle">APIs & auth</span> select <span class="pb_label pb_label-subtle">Credentials</span></li>
			<li>Select the <span class="pb_label pb_label-subtle">Add Credentials</span> button then <span class="pb_label pb_label-subtle">OAuth 2.0 client ID</span></li>
			<li>Select Application type of <span class="pb_label pb_label-subtle">Other</span></li>
			<!--
			<li>Select your email address then for Product name enter something like "BackupBuddy" then <span class="pb_label pb_label-subtle">Save</span></li>
			<li>Select Application type of <span class="pb_label pb_label-subtle">Installed application</span> & below it Installed type <span class="pb_label pb_label-subtle">Other</span> then select <span class="pb_label pb_label-subtle">Create Client ID</span></li>
		-->
			<li>Copy & paste the <span class="pb_label pb_label-subtle">Client ID</span> & <span class="pb_label pb_label-subtle">Client Secret</span> below</li>
		</ol>
		
		<br><br>
		<h3>Enter Google Drive Client ID & Secret</h3>
		<form method="post" action="<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ) . '&add=gdrive&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ); ?>">
			<input type="hidden" name="gaction" value="auth_gdrive">
			<table class="form-table">
				<tr>
					<th scope="row">Client ID</th>
					<td><input type="text" name="client_id" style="width: 100%; max-width: 720px;"></td>
				</tr>
				<tr>
					<th scope="row">Client Secret</th>
					<td><input type="text" name="client_secret" style="width: 100%; max-width: 720px;"></td>
				</tr>
				<tr>
					<th scope="row">&nbsp;</th>
					<td><input class="button-primary" type="submit" value="Continue"></td>
				</tr>
			</table>
		</form>
		
		<?php
		return;
	}
	
	
	
	if ( 'auth_gdrive' == pb_backupbuddy::_POST( 'gaction' ) ) {
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Client.php' );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Http/MediaFileUpload.php' );
		require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Service/Drive.php' );
		
		$redirect_uri = 'urn:ietf:wg:oauth:2.0:oob';

		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setAccessType('offline'); // Required so that Google will return the refresh token.
		$client->addScope("https://www.googleapis.com/auth/drive");
		$service = new Google_Service_Drive($client);
		
		$auth_code = pb_backupbuddy::_POST( 'auth_code' );
		if ( '' != $auth_code ) {
			try {
				$result = $client->authenticate( $auth_code );
				$destination_settings['tokens'] = $client->getAccessToken();
			} catch (Exception $e) {
				pb_backupbuddy::alert( 'Error Authenticating. Make sure you entered the code exactly. Details: `' . $e->getMessage() . '`. Please check codes and try again.' );
				$destination_settings['tokens'] = '';
			}
			
			/*
			echo '<br>';
			echo 'token: ' . $client->getAccessToken();
			echo '<br><br>';
			*/
			
			
			
		}
		
		
		if ( '' == $destination_settings['tokens'] ) {
			?>
			<ol>
				<li><a href="<?php echo $client->createAuthUrl(); ?>" target="_blank" class="button secondary-button" style="vertical-align: 0;">Click here & click "Accept" to authorize BackupBuddy access to your Google Drive</a></li>
				<li>Copy & paste the provided code into the box below</li>
			</ol>
			
			<br>
			<form method="post">
				<input type="hidden" name="gaction" value="auth_gdrive">
				<input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
				<input type="hidden" name="client_secret" value="<?php echo $client_secret; ?>">
				
				<table class="form-table">
					<tr>
						<th scope="row">Auth Code</th>
						<td><input type="text" name="auth_code" style="width: 100%; max-width: 720px;"></td>
					</tr>
					<tr>
						<th scope="row">&nbsp;</th>
						<td><input class="button-primary" type="submit" value="Continue"></td>
					</tr>
				</table>
				
			</form>
			
			<?php
			
			return;
		}
		
		
		
		
	}
}


// Editing or add mode authed. Show settings.
$pb_hide_test = false;
$pb_hide_save = false;



if ( 'save' != $mode ) {
	$info = pb_backupbuddy_destination_gdrive::getDriveInfo( $destination_settings );
	echo 'Used ' . pb_backupbuddy::$format->file_size( $info['quotaUsed'] ) . ' of ' . pb_backupbuddy::$format->file_size( $info['quotaTotal'] ) . ' available space in ' . $info['name'] . '\'s Google Drive.';
}



if ( 'add' == $mode ) {
	$tokens = base64_encode( $destination_settings['tokens'] );
	$default_name = 'My Google Drive';
} else {
	$tokens = NULL;
	$client_id = NULL;
	$client_secret = NULL;
}


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'title',
	'title'		=>		__( 'Destination name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( 'Name of the new destination to create. This is for your convenience only.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-45]',
	'default'	=>		$default_name,
) );

$settings_form->add_setting( array(
	'type'		=>		'hidden',
	'name'		=>		'tokens',
	'default'	=>		$tokens,
) );
$settings_form->add_setting( array(
	'type'		=>		'hidden',
	'name'		=>		'client_id',
	'default'	=>		$client_id,
) );
$settings_form->add_setting( array(
	'type'		=>		'hidden',
	'name'		=>		'client_secret',
	'default'	=>		$client_secret,
) );


$folderText = '';
if ( 'save' != $mode ) {
	$folderID = $destination_settings['folderID'];
	if ( '' == $folderID ) {
		$folderID = 'root';
	}
	//print_r( $destination_settings );
	$folderMeta = pb_backupbuddy_destination_gdrive::getFileMeta( $destination_settings, $folderID );
	//print_r( $folderMeta );
	$folderText = 'Folder name: "<a href="' . $folderMeta->alternateLink . '" target="_new">' . $folderMeta->title . '"</a>';
}



$settings_form->add_setting( array(
	'type'			=>		'text',
	'name'			=>		'folderID',
	'title'			=>		__( 'Storage Folder Identifier', 'it-l10n-backupbuddy' ),
	'tip'			=>		__( 'Folder to store files within. Leave blank to store in the root or use the unique identifier ID. Use the folder picker or get the path ID from the folder URL in your web browser. Renaming the folder in Google Drive will not change the ID or impact backups going into it.', 'it-l10n-backupbuddy' ),
	'rules'			=>		'',
	//'default'		=>		'',
	'css'			=>		'width: 300px;',
	'after'			=>		' <span class="description">This is NOT the folder name but its ID. Leave blank to store in root.</span>&nbsp;<span class="description"><span class="backupbuddy-gdrive-folderTitleText">' . $folderText . '</span></span><br><br>',
	'row_class'		=>		'backupbuddy-gdrive-folder-row',
) );
$settings_form->add_setting( array(
	'type'		=>		'hidden',
	'name'		=>		'folderTitle',
	'default'	=>		'',
) );


if ( 'save' != $mode ) {
	pb_backupbuddy_destination_gdrive::printFolderSelector( $destination_id );
}



$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'full_archive_limit',
	'title'		=>		__( 'Full backup limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Full (complete) backup archives to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' backups',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'db_archive_limit',
	'title'		=>		__( 'Database only limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Database Only backup archives to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' backups',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'files_archive_limit',
	'title'		=>		__( 'Files only limit', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 5] - Enter 0 for no limit. This is the maximum number of Files Only backup archives to be stored in this specific destination. If this limit is met the oldest backup of this type will be deleted.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' backups',
) );






$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'advanced_begin',
	'title'		=>		'<span class="dashicons dashicons-arrow-right"></span> ' . __( 'Advanced Options', 'it-l10n-backupbuddy' ),
	'row_class'	=>		'advanced-toggle-title',
) );



$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_burst',
	'title'		=>		__( 'Send per burst', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default 25] - This is the amount of data that will be sent per burst within a single PHP page load/chunk. Bursts happen within a single page load. Chunks occur when broken up between page loads/PHP instances. Reduce if hitting PHP memory limits. Chunking time limits will only be checked between bursts. Lower burst size if timeouts occur before chunking checks trigger.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|int[0-9999999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' MB',
	'row_class'	=>		'advanced-toggle',
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_time',
	'title'		=>		__( 'Max time per chunk', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: 30] - Enter 0 for no limit (aka no chunking; bursts may still occur based on burst size setting). This is the maximum number of seconds per page load that bursts will occur. If this time is exceeded when a burst finishes then the next burst will be chunked and ran on a new page load. Multiple bursts may be sent within each chunk.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'',
	'css'		=>		'width: 50px;',
	'after'		=>		' secs. <span class="description">' . __( 'Blank for detected default:', 'it-l10n-backupbuddy' )  . ' ' . backupbuddy_core::detectMaxExecutionTime() . ' sec</span>',
	'row_class'	=>		'advanced-toggle',
) );

if ( $mode !== 'edit' ) {
	$settings_form->add_setting( array(
		'type'		=>		'checkbox',
		'name'		=>		'disable_file_management',
		'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
		'title'		=>		__( 'Disable file management', 'it-l10n-backupbuddy' ),
		'tip'		=>		__( '[Default: unchecked] - When checked, selecting this destination disables browsing or accessing files stored at this destination from within BackupBuddy.', 'it-l10n-backupbuddy' ),
		'css'		=>		'',
		'rules'		=>		'',
		'row_class'	=>		'advanced-toggle',
	) );
}
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'disabled',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Disable destination', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: unchecked] - When checked, this destination will be disabled and unusable until re-enabled. Use this if you need to temporary turn a destination off but don\t want to delete it.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Check to disable this destination until re-enabled.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'',
	'row_class'	=>		'advanced-toggle',
) );



if ( 'save' != $mode ) {
	if ( ! is_numeric( $destination_id ) ) {
		$destination_id = 'NEW';
	}
	?>
<script>
	jQuery(document).ready(function() {
		destinationWrap = backupbuddy_gdrive_getDestinationWrap( '<?php echo $destination_id; ?>' );
		
		jQuery( '.backupbuddy-gdrive-folderSelector[data-isTemplate="true"]' ).clone().attr('data-isTemplate','false').show().appendTo( destinationWrap.find( 'td.backupbuddy-gdrive-folder-row' ) ).attr( 'data-destinationID', '<?php echo $destination_id; ?>' );
		backupbuddy_gdrive_folderSelect( '<?php echo $destination_id; ?>' );
	});
</script>
<?php }




