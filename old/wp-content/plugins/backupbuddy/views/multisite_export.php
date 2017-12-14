<?php

// We cant export the main site from the network as the prefix is the same as the network. Makes a big mess of things and may have unpredictable results on import/migration.
global $wpdb, $table_prefix;
if ( $wpdb->prefix == $wpdb->base_prefix ) {
	echo '<h3>' . __( 'Export Unavailable for Main Site', 'it-l10n-backupbuddy' ) . '</h3>';
	_e( 'The main Network site (base: `' . $wpdb->base_prefix . '`; this site prefix: `' . $wpdb->prefix . '`; table prefix: `' . $table_prefix . '`) cannot be exported as it is tied to the Network. It can only be backed up from the main Network Admin. All other subsites can be exported for importing into the same Network (aka Duplicate), another Network, or as a standalone site.', 'it-l10n-backupbuddy' );
	echo ' See the <a href="http://ithemes.com/codex/page/BackupBuddy_Multisite">BackupBuddy Multisite Knowledge Base</a> for additional information.';
	echo '<br><br><br>';
	return;
}
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_hoveraction_send' ).click( function(e) {
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&sending=1&TB_iframe=1&width=640&height=455', null );
			return false;
		});
		
		jQuery( '.pb_backupbuddy_hoveraction_hash' ).click( function(e) {
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'hash' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&TB_iframe=1&width=640&height=455', null );
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
		
		
		
	});
	
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data, delete_after, mode ) {
		if ( callback_data != '' ) {
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_send' ); ?>', { destination_id: destination_id, destination_title: destination_title, file: callback_data, trigger: 'manual' }, 
				function(data) {
					data = jQuery.trim( data );
					if ( data.charAt(0) != '1' ) {
						alert( "<?php _e("Error starting remote send", 'it-l10n-backupbuddy' );?>:" + "\n\n" + data );
					} else {
						alert( "<?php _e('Your file has been scheduled to be sent now. It should arrive shortly.', 'it-l10n-backupbuddy' ); ?> <?php _e( 'You will be notified by email if any problems are encountered.', 'it-l10n-backupbuddy' ); ?>" + "\n\n" + data.slice(1) );
					}
				}
			);
			
			/* Try to ping server to nudge cron along since sometimes it doesnt trigger as expected. */
			jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>',
				function(data) {
				}
			);

		} else {
			//window.location.href = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=' + destination_id;
			window.location.href = '<?php
			if ( is_network_admin() ) {
				echo network_admin_url( 'admin.php' );
			} else {
				echo admin_url( 'admin.php' );
			}
			?>?page=pb_backupbuddy_backup&custom=remoteclient&destination_id=' + destination_id;
		}
	}
	
	
	/*
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data ) {
		window.location.href = '<?php echo admin_url( 'admin.php' ); ?>?page=pb_backupbuddy_backup&custom=remoteclient&destination_id=' + destination_id;
	}
	*/
</script>


<p>For BackupBuddy Multisite documentation, please visit the <a href='http://ithemes.com/codex/page/BackupBuddy_Multisite'>BackupBuddy Multisite Codex</a>.</p>
<br>

<h3>Select plugins to include in Export</h3>


<form method="post" action="<?php echo pb_backupbuddy::page_url(); ?>&backupbuddy_backup=export">

<div id='plugin-list'>
<?php
?>
	<table class="widefat">
		<thead>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th><?php esc_html_e( 'Plugin', 'it-l10n-backupbuddy' ); ?></th>
				<th><?php esc_html_e( 'Description', 'it-l10n-backupbuddy' ); ?></th>
				<th><?php esc_html_e( 'Plugin Type', 'it-l10n-backupbuddy' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th><?php esc_html_e( 'Plugin', 'it-l10n-backupbuddy' ); ?></th>
				<th><?php esc_html_e( 'Description', 'it-l10n-backupbuddy' ); ?></th>
				<th><?php esc_html_e( 'Plugin Type', 'it-l10n-backupbuddy' ); ?></th>
			</tr>
		</tfoot>
		<tbody id="pb_reorder">
			<?php
			//Get MU Plugins
			foreach ( get_mu_plugins() as $file => $meta ) {
				$description = !empty( $meta[ 'Description' ] ) ? $meta[ 'Description' ] : '';
				$name = !empty( $meta[ 'Name' ] ) ? $meta[ 'Name' ] : $file;
				?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="items[mu][]" class="entries" value="<?php echo esc_attr( $file ); ?>" /></th>
				<td><?php echo esc_html( $name ); ?></td>
				<td><?php echo esc_html( $description ); ?></td>
				<td><?php esc_html_e( 'Must Use', 'it-l10n-backupbuddy' ); ?></td>
			</tr>	
				<?php
			} //end foreach
			
			//Get Drop INs
			foreach ( get_dropins() as $file => $meta ) {
				$description = !empty( $meta[ 'Description' ] ) ? $meta[ 'Description' ] : '';
				$name = !empty( $meta[ 'Name' ] ) ? $meta[ 'Name' ] : $file;
				?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="items[dropins][]" class="entries" value="<?php echo esc_attr( $file ); ?>" /></th>
				<td><?php echo esc_html( $name ); ?></td>
				<td><?php echo esc_html( $description ); ?></td>
				<td><?php esc_html_e( 'Drop In', 'it-l10n-backupbuddy' ); ?></td>
			</tr>	
				<?php
			} //end foreach drop ins
			
			//Get Network Activated
			foreach ( get_plugins() as $file => $meta ) {
				if ( !is_plugin_active_for_network( $file ) ) continue;
				$description = !empty( $meta[ 'Description' ] ) ? $meta[ 'Description' ] : '';
				$name = !empty( $meta[ 'Name' ] ) ? $meta[ 'Name' ] : $file;
				?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="items[network][]" class="entries" value="<?php echo esc_attr( $file ); ?>" /></th>
				<td><?php echo esc_html( $name ); ?></td>
				<td><?php echo esc_html( $description ); ?></td>
				<td><?php esc_html_e( 'Network Activated', 'it-l10n-backupbuddy' ); ?></td>
			</tr>	
				<?php
			} //end foreach drop ins
			?>
		</tbody>
	</table>
</div><!-- #plugin-list-->
<input type="hidden" name="action" value="export" />
<?php wp_nonce_field( 'bb-plugins-export', '_bb_nonce' ); ?>
<?php submit_button( __( 'Begin Export', 'it-l10n-backupbuddy' ), 'primary', 'bb-plugins' ); ?>
</form>




<br><br>



<?php
echo '<h3>' . __( 'Previously Created Site Exports', 'it-l10n-backupbuddy' ) . '</h3>';
$listing_mode = 'default';
require_once( '_backup_listing.php' );
?>