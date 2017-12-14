<?php
backupbuddy_core::verifyAjaxAccess();


require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
pb_backupbuddy::$ui->ajax_header();

pb_backupbuddy::load_style( 'admin.js' );
pb_backupbuddy::load_style( 'admin' );
pb_backupbuddy::load_style( 'destination_picker.css' );
pb_backupbuddy::load_script( 'jquery' );
pb_backupbuddy::load_script( 'jquery-ui-core' );
pb_backupbuddy::load_script( 'jquery-ui-widget' );

pb_backupbuddy::load_style( 'backupProcess.css' );
pb_backupbuddy::load_style( 'backupProcess2.css' );


$default_tab = 0;
if ( is_numeric( pb_backupbuddy::_GET( 'tab' ) ) ) {
	$default_tab = pb_backupbuddy::_GET( 'tab' );
}

// Destinations may hide the add and test buttons by altering these variables.
global $pb_hide_save;
global $pb_hide_test;
$pb_hide_save = false;
$pb_hide_test = false;
$mode = 'destination';


if ( '' != pb_backupbuddy::_GET( 'alert_notice' ) ) {
	pb_backupbuddy::alert( htmlentities( pb_backupbuddy::_GET( 'alert_notice' ) ) );
	echo '<br>';
}

if ( $mode == 'migration' ) {
	$picker_url = pb_backupbuddy::ajax_url( 'migration_picker' );
} else {
	$picker_url = pb_backupbuddy::ajax_url( 'destinationTabs' );
}
?>




<script>
	jQuery(document).ready(function() {
		
		jQuery( '.bb-tab-add_new' ).click( function(){
			jQuery( '.bb_destinations-adding' ).hide();
			jQuery( '.bb_destinations' ).show();
		});
		
		jQuery( '.bb_destination-new-item a' ).click( function(e){
			e.preventDefault();
			//tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&add=' + jQuery(this).attr('rel') + '&filter=' + jQuery(this).attr('rel') + '&callback_data=' + jQuery('#pb_backupbuddy_archive_send').attr('rel') + '&sending=1&TB_iframe=1&width=640&height=455', null );
			
			if ( jQuery(this).parent('.bb_destination-item').hasClass('bb_destination-item-disabled') ) {
				alert( 'Error #848448: This destination is not available on your server.' );
				return false;
			}
			
			sendURL = '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&add=' + jQuery(this).attr('rel') + '&filter=' + jQuery(this).attr('rel') + '&callback_data=' + jQuery('#pb_backupbuddy_archive_send').attr('rel') + '&sending=0';
			jQuery.post( sendURL, 
				function(data) {
					data = jQuery.trim( data );
					jQuery( '.bb_destinations' ).hide();
					jQuery( '.bb_destinations-adding' ).html( data ).show();
				}
			);
		});
		
		
		// Save a remote destination settings.
		jQuery( '.pb_backupbuddy_destpicker_save' ).click( function(e) {
			e.preventDefault();
			
			var pb_remote_id = jQuery(this).closest('.backupbuddy-destination-wrap').attr('data-destination_id');
			var new_title = jQuery(this).closest('form').find( '#pb_backupbuddy_title' ).val();
			var configToggler = jQuery(this).closest('.backupbuddy-destination-wrap').find('.backupbuddy-destination-config');
			jQuery(this).closest('form').find( '.pb_backupbuddy_destpicker_saveload' ).show();
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_save' ); ?>&pb_backupbuddy_destinationid=' + pb_remote_id, jQuery(this).parent( 'form' ).serialize(), 
				function(data) {
					data = jQuery.trim( data );
					
					if ( data == 'Destination Added.' ) {
						<?php
						if ( pb_backupbuddy::_GET( 'quickstart' ) != '' ) {
						?>
						var win = window.dialogArguments || opener || parent || top;
						win.pb_backupbuddy_quickstart_destinationselected();
						win.tb_remove();
						return false;
						<?php
						}
						?>
						//alert( data + "\n\nNow returning to destination list..." );
						window.location.href = '<?php echo $picker_url . '&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ); ?>&sending=<?php echo pb_backupbuddy::_GET( 'sending' ); ?>&alert_notice=' + encodeURIComponent( 'New destination successfully added.' );
					} else if ( data == 'Settings saved.' ) {
						jQuery( '.pb_backupbuddy_destpicker_saveload' ).hide();
						jQuery( '.nav-tab-active' ).find( '.destination_title' ).text( new_title );
						configToggler.toggle();
						configToggler.closest('.backupbuddy-destination-wrap').find( 'iframe' ).attr( 'src', function ( i, val ) { return val; }); // Refresh iframe.
					} else {
						jQuery( '.pb_backupbuddy_destpicker_saveload' ).hide();
						alert( "Error: \n\n" + data );
					}
					
				}
			);
			
			return false;
		} );
		
		
		// Test a remote destination.
		jQuery( '.pb_backupbuddy_destpicker_test' ).click( function(e) {
			e.preventDefault();
			
			jQuery(this).children( '.pb_backupbuddy_destpicker_testload' ).show();
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_test' ); ?>', jQuery(this).parent( 'form' ).serialize(), 
				function(data) {
					jQuery( '.pb_backupbuddy_destpicker_testload' ).hide();
					data = jQuery.trim( data );
					alert( data );
				}
			);
			
			return false;
		} );
		
		
		// Delete a remote destination settings.
		jQuery( '.pb_backupbuddy_destpicker_delete' ).click( function(e) {
			e.preventDefault();
			
			if ( !confirm( 'Are you sure you want to delete this destination?' ) ) {
				return false;
			}
			
			var pb_remote_id = jQuery(this).closest('.backupbuddy-destination-wrap').attr('data-destination_id');
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_delete' ); ?>&pb_backupbuddy_destinationid=' + pb_remote_id, jQuery(this).parent( 'form' ).serialize(), 
				function(data) {
					data = jQuery.trim( data );
					
					if ( data == 'Destination deleted.' ) {
						
						window.location.href = '<?php echo $picker_url . '&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ); ?>&sending=<?php echo pb_backupbuddy::_GET( 'sending' ); ?>&alert_notice=' + encodeURIComponent( 'Destination deleted.' );
						
					} else { // Show message if not success.
						
						alert( 'Error #82724. Details: `' + data + '`.' );
						
					}
					
				}
			);
			
			return false;
		} );
		
		
		jQuery( '.bb_destination_config_icon' ).click( function(e){
			e.preventDefault();
			//e.stopPropagation();
			//iframe_id = 'pb_backupbuddy_iframe-dest-' + jQuery(this).attr('data-id');
			//document.getElementById( iframe_id ).contentWindow.targetFunction();
			jQuery( '.backupbuddy-destination-wrap[data-destination_id="' + jQuery(this).attr('data-id') + '"]' ).find( '.backupbuddy-destination-config' ).toggle();
		});
		
	});
</script>

<style>
	.pb_backupbuddy_destpicker_testload {
		display: none;
		vertical-align: -2px;
		margin-left: 10px;
		width: 12px;
		height: 12px;
	}
	.pb_backupbuddy_destpicker_saveload,.pb_backupbuddy_destpicker_deleteload {
		display: none;
		vertical-align: -4px;
		margin-left: 5px;
		width: 16px;
		height: 16px;
	}
	.bb-tab-add_new {
		//border: 0 !important;
		//border-bottom: 0 !important;
		//border-color: transparent !import;
		//background: transparent !important;
	}
	
	.bb_destination_config_icon:before {
		//display: inline-block;
		-webkit-font-smoothing: antialiased;
		font-family: 'dashicons';
		font-size: 18px;
		color: #BBB;
		vertical-align: top;
		//margin-top: -4px;
		margin-left: 5px;
		content: "\f111"; /* dash */
	}
	.bb_destination_config_icon:hover:before {
		color: #888;
	}
</style>




<?php

$destinationTabs = array();
foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
	$titleStyle = '';
	$hoverTitleText = __( 'Destination type', 'it-l10n-backupbuddy' ) . ': ' . $destination['type'] . '. ID: ' . $destination_id;
	if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
		$titleStyle = 'text-decoration: line-through';
		$hoverTitleText .= ' [' . __( 'DISABLED', 'it-l10n-backupbuddy' ) . ']';
	}
	$destinationTabs[] = array(
		'title'		=>		'<span title="' . $hoverTitleText . '"><img src="' . pb_backupbuddy::plugin_url() . '/destinations/' . $destination['type'] . '/icon50.png" width="16" height="16" style="vertical-align: -2px;"> <span class="destination_title" style="' . $titleStyle . '">' . $destination['title'] . '</span> <span class="bb_destination_config_icon" data-id="' . $destination_id . '" title="Show configuration options"></span></span>',
		'slug'		=>		'destination_' . $destination['type'] . '_' . $destination_id,
	);
}

$destinationTabs[] = array( 'title' => '<span class="dashicons dashicons-plus" style="vertical-align: middle;"></span> Add New&nbsp;', 'slug' => 'add_new' );

pb_backupbuddy::$ui->start_tabs(
	'destinations',
	$destinationTabs,
	'width: 100%;',
	true,
	$default_tab
);


foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
	pb_backupbuddy::$ui->start_tab( 'destination_' . $destination['type'] . '_' . $destination_id );
	
	
	echo '<div class="backupbuddy-destination-wrap" data-destination_id="' . $destination_id . '">';
	
	
	/*
	echo '<div style="margin-top: -14px; margin-bottom: 18px; margin-left: 12px;">';
	echo '<button class="button-secondary" onClick="jQuery(this).closest(\'.backupbuddy-destination-wrap\').find(\'.backupbuddy-destination-config\').toggle()">Configure Destination Settings</button>';
	echo '</div>';
	*/
	
	// SETTINGS CONFIG FORM.
	echo '<div class="backupbuddy-destination-config" style="
		display: none;
		border: 1px solid rgb(229, 229, 229);
		-webkit-box-shadow: rgba(0, 0, 0, 0.0392157) 0px 1px 1px;
		box-shadow: rgba(0, 0, 0, 0.0392157) 0px 1px 1px;
		padding: 20px;
		margin-bottom: 40px;
		background: rgb(255, 255, 255);
	">';
	echo '<h3 style="margin-left: 0;">' . __( 'Destination Settings', 'it-l10n-backupbuddy' ) . '</h3>';
	$settings = pb_backupbuddy_destinations::configure( $destination, 'edit', $destination_id );
	if ( $settings === false ) {
		echo 'Error #556656b. Unable to display configuration. This destination\'s settings may be corrupt. Removing this destination. Please refresh the page.';
		unset( pb_backupbuddy::$options['remote_destinations'][ $destination_id ] );
		pb_backupbuddy::save();
	} else {
		if ( $pb_hide_test !== true ) {
			$test_button = '<a href="#" class="button secondary-button pb_backupbuddy_destpicker_test" href="#" title="Test destination settings.">Test Settings<img class="pb_backupbuddy_destpicker_testload" src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" title="Testing... This may take several seconds..."></a>';
		} else {
			$test_button = '';
		}
		/*
		if ( $pb_hide_save !== true ) {
			$save_and_delete_button = '<img class="pb_backupbuddy_destpicker_saveload" src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" title="Saving... This may take a few seconds...">';
		} else {
			$save_and_delete_button = '';
		}
		*/
		$save_and_delete_button = '';
		$save_and_delete_button .= '<a href="#" class="button secondary-button pb_backupbuddy_destpicker_delete" href="javascript:void(0)" title="Delete this Destination">Delete Destination</a>';
		echo $settings->display_settings( 'Save Settings', $save_and_delete_button . '&nbsp;&nbsp;' . $test_button . '&nbsp;&nbsp;', $afterText = ' <img class="pb_backupbuddy_destpicker_saveload" src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" title="Saving... This may take a few seconds...">', 'pb_backupbuddy_destpicker_save' ); // title, before, after, class
	}
	echo '</div>';
	
	
	$url = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . $destination_id;
	echo '<iframe id="pb_backupbuddy_iframe-dest-' . $destination_id . '" src="' . $url . '" width="100%" height="3000" frameBorder="0">Error #4584594579. Browser not compatible with iframes.</iframe>';
	echo '</div>';
	
	pb_backupbuddy::$ui->end_tab();
}


pb_backupbuddy::$ui->start_tab( 'add_new' );

$destination_type = pb_backupbuddy::_GET( 'add' );
	
	require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
	?>
	<div class="bb_destinations" style="display: block; margin: 0;">
		<div class="bb_destinations-group bb_destinations-new" style="display: block;">
			<h3>What kind of destination do you want to add?</h3>
			<ul>
				<?php
				$i = 0;
				foreach( pb_backupbuddy_destinations::get_destinations_list( $showUnavailable = true ) as $destination_name => $destination ) {
					
					$i++;
					if ( true === $destination['compatible'] ) {
						echo '<li class="bb_destination-item bb_destination-' . $destination_name . ' bb_destination-new-item">';
						
						/*
						if ( 's32' == $destination_name ) {
							echo '<div class="bb-ribbon"><span>New</span></div>';
						}
						*/
						if ( 'stash2' == $destination_name ) {
							echo '<div class="bb-ribbon"><span>New</span></div>';
						}
						
						echo '<a href="javascript:void(0)" rel="' . $destination_name . '">';
						echo $destination['name'];
						echo '</a></li>';
					} else { // Incompatible. Display only.
						echo '<li class="bb_destination-item bb_destination-item-disabled bb_destination-' . $destination_name . ' bb_destination-new-item"><a href="javascript:void(0)" rel="' . $destination_name . '" title="This destination is unavailable due to server requirements.">' . $destination['name'] . ' [Unavailable; ' . $destination['compatibility'] . ']</a></li>';
					}
					if ( $i >= 5 ) {
						echo '<span class="bb_destination-break"></span>';
						$i = 0;
					}
				}
				?>
			</ul>
		</div>
	</div>
	<div class="bb_destinations-adding"></div>
	<?php
pb_backupbuddy::$ui->end_tab();



echo '<br style="clear: both;"><br><br>';
pb_backupbuddy::$ui->end_tabs();



pb_backupbuddy::$ui->ajax_footer();
die();