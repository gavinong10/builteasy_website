<?php
$default_tab = 0;
if ( is_numeric( pb_backupbuddy::_GET( 'tab' ) ) ) {
	$default_tab = pb_backupbuddy::_GET( 'tab' );
}


wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );

wp_enqueue_script( 'jquery-ui-core' );
wp_print_styles( 'jquery-ui-core' );

wp_enqueue_script( 'jquery-ui-progressbar' );
wp_print_styles( 'jquery-ui-progressbar' );

//pb_backupbuddy::disalert( 'backup_stash_advert', 'Active BackupBuddy customers already have a <b>BackupBuddy Stash</b> account with <span class="pb_label pb_label">1 GB Free Storage</span>. Just login on the <a href="?page=pb_backupbuddy_destinations">Remote Destinations</a> page.' );
?>




<script type="text/javascript">
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data, delete_after, mode ) {
		if ( callback_data != '' ) {
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_send' ); ?>', { destination_id: destination_id, destination_title: destination_title, file: callback_data, trigger: 'manual' }, 
				function(data) {
					data = jQuery.trim( data );
					if ( data.charAt(0) != '1' ) {
						alert( "<?php _e("Error starting remote send", 'it-l10n-backupbuddy' ); ?>:" + "\n\n" + data );
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
	
	
	
	jQuery(document).ready(function() {
		
		
		jQuery( '.backupbuddy-progressbar' ).each( function(){
			percentDone = jQuery(this).attr( 'data-percent' );
			jQuery(this).progressbar( { value: parseInt( percentDone, 10 ) } );
			jQuery(this).find( '.backupbuddy-progressbar-label' ).text( percentDone + ' %' );
		});
		
		
		jQuery( '#backupbuddy-deployment-regenerateKey' ).click( function(e){
			e.preventDefault();
			
			if ( false === confirm( '<?php _e( 'Are you sure you want to generate a new key? This will render any existing keys invalid.', 'it-l10n-backupbuddy' ); ?>' ) ) {
				return false;
			}
			
			jQuery( '.pb_backupbuddy_loading-regenerateKey' ).show();
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'deployment_regenerateKey' ); ?>', { }, 
				function(data) {
					jQuery( '.pb_backupbuddy_loading-regenerateKey' ).hide();
					data = jQuery.trim( data );
					try {
						var data = jQuery.parseJSON( data );
					} catch(e) {
						alert( 'Error #3899833: Unexpected non-json response from server: `' + data + '`.' );
						return;
					}
					if ( true !== data.success ) {
						alert( 'Error #32983: Unable to generate new key. Details: `' + data.message + '`.' );
						return;
					}
					
					jQuery( '#backupbuddy-deployment-regenerateKey-textarea' ).val( data.key );
				}
			);
		}); // End jQuery( '#backupbuddy-deployment-regenerateKey' ).click().
		
		
	});
	
</script>


<?php
$api_button = '';
$api_button = '<a href="javascript:void(0)" class="add-new-h2" onClick="jQuery(\'.backupbuddy_api_key-hide\').toggle(); return false;">' . __( 'Show Deployment Key', 'it-l10n-backupbuddy' ) . '</a>';
pb_backupbuddy::$ui->title( __( 'Remote Destinations', 'it-l10n-backupbuddy' ) . ' <a href="javascript:void(0)" class="add-new-h2" onClick="jQuery(\'.backupbuddy-destination-sends\').toggle()">View recently sent files</a> ' . $api_button ); //  . ' <a href="javascript:void(0)" class="add-new-h2">Add New</a>' )
?>


<div class="backupbuddy_api_key-hide" style="
	display: none;
	border: 1px solid #e5e5e5;
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);
	box-shadow: 0 1px 1px rgba(0,0,0,.04);
	padding: 20px;
	background: #fff;
	margin-bottom: 40px;
">
	<?php
	if ( defined( 'BACKUPBUDDY_API_ENABLE' ) && ( TRUE == BACKUPBUDDY_API_ENABLE ) ) { // && ( defined( 'BACKUPBUDDY_API_SALT' ) && ( 'CHANGEME' != BACKUPBUDDY_API_SALT ) && ( strlen( BACKUPBUDDY_API_SALT ) >= 5 ) )
		require_once( pb_backupbuddy::plugin_path() . '/classes/remote_api.php' );
		
		if ( ( ! isset( pb_backupbuddy::$options['remote_api']['keys'][0] ) ) || ( '' == pb_backupbuddy::$options['remote_api']['keys'][0] ) ) {
			pb_backupbuddy::alert( 'New Deployment Key generated.' );
			pb_backupbuddy::$options['remote_api']['keys'][0] = backupbuddy_remote_api::generate_key();
			pb_backupbuddy::save();
			
			// Make sure API key box is showing.
			echo "<script>
				jQuery(document).ready(function() {
					jQuery('.backupbuddy_api_key-hide').show();
				});
			</script>";
		}
		?>
		
		<form method="post">
			<?php pb_backupbuddy::nonce(); ?>
			<input type="hidden" name="regenerate_api_key" value="1">
				<h3 style="margin-top: 0;"><?php _e( 'Deployment Key', 'it-l10n-backupbuddy' ); ?></h3>
				<p>
					Copy this Deployment Key into the other BackupBuddy Site you wish to have access to Push to or Pull from this site.
				</p>
				<textarea id="backupbuddy-deployment-regenerateKey-textarea" cols="90" rows="4" style="padding: 15px; background: #fcfcfc;" readonly="readonly" onClick="this.focus();this.select();"><?php echo pb_backupbuddy::$options['remote_api']['keys'][0]; ?></textarea>
				<br><br>
				<input id="backupbuddy-deployment-regenerateKey" type="submit" name="submit" value="<?php _e( 'Generate New Deployment Key', 'it-l10n-backupbuddy' ); ?>" class="button button-primary" style="margin-top: -5px;">
				&nbsp;<span class="pb_backupbuddy_loading-regenerateKey" style="display: none; margin-left: 10px;"><img src="<?php echo pb_backupbuddy::plugin_url(); ?>/images/loading.gif" alt="<?php _e('Loading...', 'it-l10n-backupbuddy' ); ?>" title="<?php _e('Loading...', 'it-l10n-backupbuddy' ); ?>" width="16" height="16" style="vertical-align: -3px;" /></span>
		</form>
		
		<?php
	} else {
		?>
		<h3 style="margin-top: 0;"><?php _e( 'Deployment', 'it-l10n-backupbuddy' ); ?></h3>
		Remote API Access allows other sites with your API access key entered to push to or pull data from this site.
		<br><br>
		<button class="button button-primary" style="vertical-align: 1px;" onClick="jQuery('.backupbuddy_api_wpconfig-hide').toggle(); jQuery(this).hide(); return false;"><?php _e( 'Enable Deployment', 'it-l10n-backupbuddy' ); ?></button>
		<span class="backupbuddy_api_wpconfig-hide" style="display: none;">
			<b>For added security you must manually enable the API. To do this <i>add the following to your wp-config.php file ABOVE the line commenting "That's all, stop editing!"</i>. <i>Refresh this page after adding</i> the following:</b>
			<br>
<textarea style="width: 100%; padding: 15px;" readonly="readonly" onClick="this.focus();this.select();">
define( 'BACKUPBUDDY_API_ENABLE', true ); // Enable BackupBuddy Deployment access.
</textarea><!-- define( 'BACKUPBUDDY_API_SALT', '<?php echo pb_backupbuddy::random_string( 32 ); ?>' ); // Random security identifier. 5+ characters. -->
		</span>
		<br>
		<?php
	}
echo '</div>';



echo '<div class="backupbuddy-destination-sends" style="display: none;"><br>';
require_once( 'server_info/remote_sends.php' );
echo '<br></div>';


echo '<iframe id="pb_backupbuddy_iframe-dest-wrap" src="' . pb_backupbuddy::ajax_url( 'destinationTabs' ) . '&tab=' . $default_tab . '&action_verb=to%20manage%20files" width="100%" height="4000" frameBorder="0">Error #4584594579. Browser not compatible with iframes.</iframe>';


?>

<br style="clear: both;"><br style="clear: both;">

<?php
// Handles thickbox auto-resizing. Keep at bottom of page to avoid issues.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}
