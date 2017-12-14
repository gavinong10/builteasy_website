<?php

// IMPORT & MIGRATE USERS.


global $wpdb, $current_site, $current_blog;
$blog_id = isset( $_POST[ 'blog_id' ] ) ? absint( $_POST[ 'blog_id' ] ) : die( 'Error #34775854c: Missing blog ID. Did you reload the page? Go back and try again.' );
//switch_to_blog( $blog_id );
$new_db_prefix = $wpdb->get_blog_prefix( $blog_id );

echo $this->status_box( 'Migrating users . . .' );
echo '<div id="pb_importbuddy_working" style="width: 100px;"><center><img src="' . pb_backupbuddy::plugin_url() . '/images/working.gif" title="Working... Please wait as this may take a moment..."></center></div>';
pb_backupbuddy::flush();

$this->status( 'message', 'NOTE: If a user you are attempting to import already exists in the network then they will NOT be migrated. This may result in orphaned posts with no author listed.' );

// Delete BackupBuddy options for imported site.
$this->status( 'details', 'Clearing importing BackupBuddy options.' );
$sql = "DELETE from {$new_db_prefix}options WHERE option_name = %s LIMIT 1";
$wpdb->query( $wpdb->prepare( $sql, 'pluginbuddy_backupbuddy' ) );

// Clear out all BackupBuddy cron jobs.
$this->status( 'details', 'Clearing importing BackupBuddy scheduled crons.' );

// Clear out any cron hooks related to BackupBuddy for imported site. - Ron H.
$wipe_cron_hooks = array( 
						'backupbuddy_cron', // Only cron as of BB v6.4.0.9.
						
						// TODO: Remove all of these in future after v6.6.4.0.9 change is solidly adopted. Remove around v8.0?
						pb_backupbuddy::settings( 'slug' ) . '-cron_final_cleanup',
						pb_backupbuddy::settings( 'slug' ) . '-cron_process_backup',
						pb_backupbuddy::settings( 'slug' ) . '-cron_dropbox_copy',
						pb_backupbuddy::settings( 'slug' ) . '-cron_ftp_copy',
						pb_backupbuddy::settings( 'slug' ) . '-cron_rackspace_copy',
						pb_backupbuddy::settings( 'slug' ) . '-cron_s3_copy',
						'pb_backupbuddy-cron_scheduled_backup', // Backward compat to BB 1.x.
						'pb_backupbuddy-cron_remotesend',
					);
$sql = "SELECT option_value FROM `{$new_db_prefix}options` WHERE option_name= %s LIMIT 1";
$crons = $wpdb->get_var( $wpdb->prepare( $sql, 'cron' ) );
$crons = unserialize( $crons );
if ( $crons != '' ) {
	foreach ( (array)$crons as $timestamp => $cron ) {
		foreach ( $wipe_cron_hooks as $wipe_cron_hook ) {
			if ( isset( $cron[ $wipe_cron_hook ] ) ) {
				unset( $crons[ $timestamp ] ); // Remove this BB hook from the cron system.
			}
		}
	}
	$cron = serialize( $cron );
	$sql = "UPDATE `{$new_db_prefix}options` SET option_value='{$cron}' WHERE option_name= %s LIMIT 1";
	$wpdb->query( $wpdb->prepare( $sql, 'cron' ) );
}



// Remove site-specific usermeta info as we dont import this currently.
pb_backupbuddy::status( 'details', 'Deleting unnused site-specific usermeta data in temporary usermeta table.' );
$usermeta_wipe_keys = array( // DO NOT WIPE CAPABILITIES HERE. EXCLUDE LATER DURING COPY! All rows with this meta key will be erased from the temp usermeta table.
	'%_user-settings',
	'%_user_level',
	'%_user-settings-time',
	'%_dashboard_quick_press_last_post_id',
);
foreach( $usermeta_wipe_keys as $usermeta_wipe_key ) {
	$usermeta_wipe_key = str_replace( '_', '\_', $usermeta_wipe_key );
	pb_backupbuddy::status( 'details', 'Deleting usermeta in temporary table with key like `' . $usermeta_wipe_key . '`.' );
	$rows_modified = $wpdb->query( "DELETE FROM `{$new_db_prefix}usermeta` WHERE meta_key LIKE '{$usermeta_wipe_key}';" );
	pb_backupbuddy::status( 'details', 'Row(s) modified: `' . $rows_modified . '`.' );
}



// Migrate users.
$sql = "select * from `{$new_db_prefix}users` WHERE 1 = %d";
$users = $wpdb->get_results( $wpdb->prepare( $sql, '1' ) ); // Users to import.
if ( !is_array( $users ) ) {
	pb_backupbuddy::status( 'message', 'No users found to import.' );
	return;
}
if ( version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {
	require_once(ABSPATH . WPINC . '/registration.php');
}

/* REMOVE. Not needed  since we have already migrated, the prefix has updated.
$this->load_backup_dat(); // Need for getting prefix for import update of prefix. loads into $this->_backupdata.
if ( isset( $this->_backupdata['db_prefix'] ) && ( $this->_backupdata['db_prefix'] != '' ) ) {
	$old_db_prefix = $this->_backupdata['db_prefix'];
} else {
	pb_backupbuddy::status( 'error', 'Error #4434894. Error determining source site database prefix.' );
}
pb_backupbuddy::status( 'details', 'The old database prefix of `' . $old_db_prefix . '` will be used to pull from temporary usermeta table for detecting prior site capabilities.' );
*/

$this->status( 'message', 'This may take a moment . . .' );

$user_count = 0;
$users_skipped = 0;
$users_skipped_blog = 0;
foreach ( $users as $user ) { // For each source user to migrate.
	$user_count++;
	
	pb_backupbuddy::status( 'details', '-----' );
	pb_backupbuddy::status( 'details', 'Attempting to import user `' . $user->user_login . '` with email `' . $user->user_email . '`.' );
	
	$old_user_id = $user->ID;
	$old_user_pass = $user->user_pass;
	$sql = "select ID from {$wpdb->users} where user_login = '{$user->user_login}' or user_email = %s"; // Get user if they already exist on network.
	$sql = $wpdb->prepare( $sql, $user->user_email );
	$user_id = $wpdb->get_var( $sql ); // We will see if user already exists; 
	
	if ( null === $user_id ) { // User does NOT already exist in network.
		$new_destination_user_args = array();
		foreach ( $user as $key => $user_param ) { // Loop through all user parameters.
			$new_destination_user_args[ $key ] = $user_param;
		}
		//pb_backupbuddy::status( 'query', "select meta_value from {$new_db_prefix}usermeta where meta_key = '{$options['old_prefix']}capabilities' and user_id = {$old_user_id}" );
		$sql = "select meta_value from {$new_db_prefix}usermeta where meta_key = '{$new_db_prefix}capabilities' and user_id = %d"; // Since the migrate step migrates the table prefix in the usermeta table the old table prefix is not in front of the capability, only the new.
		pb_backupbuddy::status( 'details', 'Getting old meta data from temporary usermeta table via sql: `' . $sql . '`' );
		$user_role_var = $wpdb->get_var( $wpdb->prepare( $sql, $old_user_id )  );
		//pb_backupbuddy::status( 'details', 'rolevar: ' . $user_role_var );
		$user_role = maybe_unserialize( $user_role_var );
		$new_user_role = '';
		if ( is_array( $user_role ) ) {
			foreach ( $user_role as $key => $value ) {
				$new_user_role = $key;
			}
		}
		
		// Add user into network no matter what.
		unset( $new_destination_user_args[ 'ID' ] );
		pb_backupbuddy::status( 'details', 'Inserting user with parameters: `' . implode( ', ', $new_destination_user_args ) . '`.' );
		$user_id = wp_insert_user( $new_destination_user_args ); // Create new user into network.
		
		pb_backupbuddy::status( 'details', 'Sleeping 15 seconds' );
		
		// Only add user into this specific blog subsite if they had a capability on the source site.
		if ( $new_user_role == '' ) {
			pb_backupbuddy::status( 'warning', 'WARNING: User with old user ID of `' . $old_user_id . '` did not have a role assigned on source site. This user was imported into the network but NOT assigned to this blog with a capability.' );
			$users_skipped_blog++;
		} else {
			add_user_to_blog( $blog_id, $user_id, $new_user_role ); // Add this user to the destination blog.
			$wpdb->update( $wpdb->users, array( 'user_pass' => $old_user_pass ), array( 'ID' => $user_id ) ); // Keep password the same.
			pb_backupbuddy::status( 'details', 'Added user `' . $user->user_login . '` with ID `' . $user_id . '` and role `' . $new_user_role . '` to blog ID `' . $blog_id . '`.' );
			
			// Remove user from main site (wp_insert_user() added to main site by default earlier.
			remove_user_from_blog( $user_id, 1 ); // Remove user from main site.
			pb_backupbuddy::status( 'details', 'Removed user\'s temporary assignment to main Network site.' );
			
			// Update post author IDs with the user's new user ID.
			pb_backupbuddy::status( 'details', 'Updating post author ID from old user ID `' . $old_user_id . '` to new ID `' . $user_id . '` in table `' . $new_db_prefix . 'posts`.' );
			$rows_updated = $wpdb->update( $new_db_prefix . 'posts', array( 'post_author' => absint( $user_id ) ), array( 'post_author' => $old_user_id ), array( '%d' ) ); 
			pb_backupbuddy::status( 'details', 'Row(s) modified: `' . $rows_updated . '`.' );
			
			// Update comment author IDs with the user's new user ID.
			pb_backupbuddy::status( 'details', 'Updating comment author ID from old user ID `' . $old_user_id . '` to new ID `' . $user_id . '` in table `' . $new_db_prefix . 'comments`.' );
			$rows_updated = $wpdb->update( $new_db_prefix . 'comments', array( 'user_id' => absint( $user_id ) ), array( 'user_id' => $old_user_id ), array( '%d' ) );
			pb_backupbuddy::status( 'details', 'Row(s) modified: `' . $rows_updated . '`.' );
		}
		
		
		
		
		// Handle usermeta.
		$sql = "select meta_key,meta_value from `{$new_db_prefix}usermeta` WHERE user_id={$old_user_id} AND meta_key NOT LIKE '%\_capabilities'";
		pb_backupbuddy::status( 'details', 'Getting usermeta data. SQL query: `' . $sql . '`.' );
		$usermetas = $wpdb->get_results( $sql ); // Users to import.
		if ( is_array( $usermetas ) ) {
			pb_backupbuddy::status( 'message', 'Found usermeta data to migrate for this user (old ID: `' . $old_user_id . '`). Importing & migrating...' );
			
			$user_meta_rows_count = 0;
			foreach( $usermetas as $usermeta ) {
				$meta_key = backupbuddy_core::dbEscape( $usermeta->meta_key );
				$meta_value = backupbuddy_core::dbEscape( $usermeta->meta_value );
				$sql = "INSERT INTO `{$wpdb->base_prefix}usermeta` (user_id,meta_key,meta_value) VALUES('{$user_id}','{$meta_key}','{$meta_value}');";
				pb_backupbuddy::status( 'details', 'Copying usermeta row. SQL query: `' . $sql . '`.' );
				$rows_modified = $wpdb->query( $sql ); // $wpdb->base_prefix gives the network prefix.
				pb_backupbuddy::status( 'details', 'Row(s) modified: `' . $rows_modified . '`.' );
				$user_meta_rows_count++;
			}
			
			pb_backupbuddy::status( 'details', 'Copied and migrated `' . $user_meta_rows_count . '` usermeta rows.' );
		}
		
		
		
	} else { // User already exists.
		pb_backupbuddy::status( 'warning', 'Username `' . $user->user_login . '` or email `' . $user->user_email . '` already exists with user ID `' . $user_id . '`. User skipped.' );
		$users_skipped++;
	}
	

	
} //end foreach

$this->status( 'message', 'Migrated ' . $user_count . ' users. ' . $users_skipped . ' users were skipped due to collision. ' . $users_skipped_blog . ' were imported into the network but not to the specific subsite due to lack of capabilities.' );
if ( $users_skipped > 0 ) {
	pb_backupbuddy::status( 'warning', 'IMPORTANT: Some users could not be imported as the username already existed in the network. Any posts attributed to them will no longer show them as the author.' );
}
if ( $users_skipped_blog > 0 ) {
	pb_backupbuddy::status( 'warning', 'IMPORTANT: Some users did not hav capabilities on the source site. These users were imported into the network but not the subsite.' );
}


// Drop the imported sites temporary users tables since they are now merged into the network site.
$drop_tables = array(
	'users',
	'usermeta',
);
foreach ( $drop_tables as $table ) {
	$table = '`' . $new_db_prefix . $table . '`';
	$wpdb->query( 'DROP TABLE IF EXISTS ' . $table );
}

$this->status( 'details', 'Dropped temporary user tables.' );


$this->status( 'message', 'Users migrated.' );
echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
pb_backupbuddy::flush();


//Output form interface
global $current_site;
	$errors = false;	
	$blog = $domain = $path = '';
	$form_url = add_query_arg( array(
		'step' => '8',
		'action' => 'step8'
	) , pb_backupbuddy::page_url()  );
?>
<form method="post" action="<?php echo esc_url( $form_url ); ?>">
<?php wp_nonce_field( 'bbms-migration', 'pb_bbms_migrate' ); ?>
<input type='hidden' name='backup_file' value='<?php echo esc_attr( $_POST[ 'backup_file' ] ); ?>' />
<input type='hidden' name='blog_id' value='<?php echo esc_attr( absint( $_POST[ 'blog_id' ] ) ); ?>' />
<input type='hidden' name='blog_path' value='<?php echo esc_attr( $_POST[ 'blog_path' ] ); ?>' />
<input type='hidden' name='global_options' value='<?php echo base64_encode( serialize( $this->advanced_options ) ); ?>' />

<h3>Last Step: Final Cleanup</h3>

<label for="delete_backup" style="width: auto; font-size: 12px;"><input type="checkbox" name="delete_backup" id="delete_backup" value="1" checked> Delete backup zip archive</label>
<br>		
<label for="delete_temp" style="width: auto; font-size: 12px;"><input type="checkbox" name="delete_temp" id="delete_temp" value="1" checked> Delete temporary import files</label>


<?php submit_button( __('Next') . ' &raquo;', 'primary', 'add-site' ); ?>
</form>