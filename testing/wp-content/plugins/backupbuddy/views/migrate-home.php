<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_hoveraction_migrate' ).click( function(e) {
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'migration_picker' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&migrate=1&TB_iframe=1&width=640&height=455', null );
			return false;
		});
		
		jQuery( '.pb_backupbuddy_hoveraction_hash' ).click( function(e) {
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'hash' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&TB_iframe=1&width=640&height=455', null );
			return false;
		});
		
		jQuery( '.pb_backupbuddy_hoveraction_send' ).click( function(e) {
			<?php if ( pb_backupbuddy::$options['importbuddy_pass_hash'] == '' ) { ?>
				alert( 'You must set an ImportBuddy password via the BackupBuddy settings page before you can send this file.' );
				return false;
			<?php } ?>
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&sending=1&TB_iframe=1&width=640&height=455', null );
			return false;
		});
		
		jQuery( '.pb_backupbuddy_get_importbuddy' ).click( function(e) {
			<?php
			if ( pb_backupbuddy::$options['importbuddy_pass_hash'] == '' ) {
				?>
				
				var password = prompt( '<?php _e( 'To download, enter a password to lock the ImportBuddy script from unauthorized access. You will be prompted for this password when you go to importbuddy.php in your browser. Since you have not defined a default password yet this will be used as your default and can be changed later from the Settings page.', 'it-l10n-backupbuddy' ); ?>' );
				if ( ( password != null ) && ( password != '' ) ) {
					window.location.href = '<?php echo pb_backupbuddy::ajax_url( 'importbuddy' ); ?>&p=' + encodeURIComponent( password );
				}
				if ( password == '' ) {
					alert( 'You have not set a default password on the Settings page so you must provide a password here to download ImportBuddy.' );
				}
				
				return false;
				<?php
			} else {
				?>
				var password = prompt( '<?php _e( 'To download, either enter a new password for just this download OR LEAVE BLANK to use your default ImportBuddy password (set on the Settings page) to lock the ImportBuddy script from unauthorized access.', 'it-l10n-backupbuddy' ); ?>' );
				if ( password != null ) {
					window.location.href = '<?php echo pb_backupbuddy::ajax_url( 'importbuddy' ); ?>&p=' + encodeURIComponent( password );
				}
				return false;
				<?php
			}
			?>
			return false;
		});
		

		
		jQuery( '.pb_backupbuddy_hoveraction_note' ).click( function(e) {
			
			var existing_note = jQuery(this).parents( 'td' ).find('.pb_backupbuddy_notetext').text();
			if ( existing_note == '' ) {
				existing_note = 'My first backup';
			}
			
			var note_text = prompt( '<?php _e( 'Enter a short descriptive note to apply to this archive for your reference. (175 characters max)', 'it-l10n-backupbuddy' ); ?>', existing_note );
			if ( ( note_text == null ) || ( note_text == '' ) ) {
				// User cancelled.
			} else {
				jQuery( '.pb_backupbuddy_backuplist_loading' ).show();
				jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'set_backup_note' ); ?>', { backup_file: jQuery(this).attr('rel'), note: note_text }, 
					function(data) {
						data = jQuery.trim( data );
						jQuery( '.pb_backupbuddy_backuplist_loading' ).hide();
						if ( data != '1' ) {
							alert( "<?php _e('Error', 'it-l10n-backupbuddy' );?>: " + data );
						}
						javascript:location.reload(true);
					}
				);
			}
			return false;
		});
		
	}); // end ready.
	
	
	
	
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data, delete_after, mode ) {
		if ( 'destination' == mode ) { // Normal destination send.
			triggerText = 'manual';
		} else if ( 'migration' == mode ) { // Migration.
			triggerText = 'migration';
		} else { // Unknown mode.
			alert( 'Error #388484: Unknown mode `' + mode + '`.' );
			return false;
		}
		
		if ( callback_data != '' ) {
			if ( callback_data == 'importbuddy.php' ) {
				window.location.href = '<?php echo pb_backupbuddy::page_url(); ?>&destination=' + destination_id + '&destination_title=' + destination_title + '&callback_data=' + callback_data;
				return false;
			}
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_send' ); ?>', { destination_id: destination_id, destination_title: destination_title, file: callback_data, trigger: triggerText, send_importbuddy: '1' }, 
				function(data) {
					data = jQuery.trim( data );
					if ( data.charAt(0) != '1' ) {
						alert( "<?php _e("Error starting remote send of file to migrate", 'it-l10n-backupbuddy' ); ?>:" + "\n\n" + data );
					} else {
						if ( 'migration' == mode ) {
							window.location.href = '<?php echo pb_backupbuddy::page_url(); ?>&destination=' + destination_id + '&destination_title=' + destination_title + '&callback_data=' + callback_data;
							return;
						}
						alert( "<?php _e('Your file has been scheduled to be sent now. It should arrive shortly.', 'it-l10n-backupbuddy' ); ?> <?php _e( 'You will be notified by email if any problems are encountered.', 'it-l10n-backupbuddy' ); ?>\n\n" + data.slice(1) );
					}
				}
			);
			
			/* Try to ping server to nudge cron along since sometimes it doesnt trigger as expected. */
			jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>',
				function(data) {
				}
			);

		} else {
			window.location.href = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=' + destination_id;
		}
	}
	
	
	
</script>
<style>
	.backupbuddyFileTitle {
		//color: #0084CB;
		color: #000;
		font-size: 1.2em;
	}
</style>

<?php
if ( pb_backupbuddy::$options['importbuddy_pass_hash'] == '' ) { // NO HASH SET.
	$importAlert = '<span class="pb_label pb_label">Important</span> <b>Set an ImportBuddy password on the <a href="';
	if ( is_network_admin() ) {
		$importAlert .= network_admin_url( 'admin.php' );
	} else {
		$importAlert .= admin_url( 'admin.php' );
	}
	$importAlert .= '?page=pb_backupbuddy_settings">Settings</a> page before attempting to Migrate to a new server.</b>';
	pb_backupbuddy::alert( $importAlert, true );
	echo '<br>';
}
?>


The best way to Restore or Migrate your site is by using a standalone PHP script named <b>importbuddy.php</b>. This file is run without first
installing WordPress, in combination with your backup ZIP file will allow you to restore this server or to a new server entirely. Sites may be
restored to a new site URL or domain.
You should keep a copy of importbuddy.php for future restores.  It is also stored within backup ZIP files for your convenience. importbuddy.php files are not
site/backup specific.
<br><br>
<ol>
	<li>
		<a id="pb_backupbuddy_downloadimportbuddy" href="<?php echo pb_backupbuddy::ajax_url( 'importbuddy' ); ?>" class="button button-primary pb_backupbuddy_get_importbuddy">Download importbuddy.php</a> or
		<a id="pb_backupbuddy_sendimportbuddy" href="" rel="importbuddy.php" class="button button-primary pb_backupbuddy_hoveraction_send">Send importbuddy.php to a Destination</a>
	</li>
	<li>
		Download a backup zip file from the list below or send it directly to a destination by selecting "Send file" when hovering over a backup below.
	</li>
	<li>
		Upload importbuddy.php & the downloaded backup zip file to the destination server directory where you want your site restored.
		<ul style="list-style-type: circle; margin-left: 20px; margin-top: 8px;">
			<li>
				Upload these into the FTP directory for your site's web root such as /home/buddy/public_html/.
				If you want to restore into a subdirectory, put these files in it.
			</li>
			<li>
				WordPress should not be installed prior to the restore. You should delete it if it already exists.
			<li>
				Full backups should be restored before restoring database only backups.
			</li>
		</ul>
	</li>
	<li>Navigate to the uploaded importbuddy.php URL in your web browser (ie http://your.com/importbuddy.php).</li>
	<li>Follow the on-screen directions until the restore / migration is complete.</li>
</ol>


<?php
/*
<br>
<h3>Database Rollback</h3>
You may roll back the database on this site to a database contained in a backup (full or database only) by selecting the "Rollback Database" option when
hovering below. This lets you easily undo changes made to the site. You will be given the opportunity to verify the rollback was successful
before making it permanent.
<br><br><br>
*/
?>
<br><br>


<h3 id="pb_backupbuddy_restoremigratelisttitle">Hover Backup for Additional Options</h3>
<?php


$listing_mode = 'restore_migrate';
require_once( '_backup_listing.php' );


echo '<br><br>';






// Handles thickbox auto-resizing. Keep at bottom of page to avoid issues.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}
?>