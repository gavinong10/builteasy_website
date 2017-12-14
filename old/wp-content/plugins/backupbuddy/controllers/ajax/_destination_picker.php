<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::load_style( 'backupProcess.css' );
pb_backupbuddy::load_style( 'backupProcess2.css' );

$default_tab = 0;
if ( is_numeric( pb_backupbuddy::_GET( 'tab' ) ) ) {
	$default_tab = pb_backupbuddy::_GET( 'tab' );
}













// $mode is defined prior to this file load as either destination or migration.

if ( $mode == 'migration' ) {
	$picker_url = pb_backupbuddy::ajax_url( 'migration_picker' );
} else {
	if ( ( '1' == pb_backupbuddy::_GET( 'sending' ) ) || ( '1' == pb_backupbuddy::_GET( 'selecting' ) ) ) {
		$picker_url = pb_backupbuddy::ajax_url( 'destination_picker' );
	} else {
		$picker_url = pb_backupbuddy::ajax_url( 'destinationTabs' );
	}
}
//$picker_url .= '&sending=' . pb_backupbuddy::_GET( 'sending' );

if ( pb_backupbuddy::_GET( 'action_verb' ) != '' ) {
	$action_verb = ' ' . htmlentities( pb_backupbuddy::_GET( 'action_verb' ) );
} else { // default
	$action_verb = '';
}

pb_backupbuddy::load_style( 'admin' );
pb_backupbuddy::load_style( 'destination_picker.css' );
pb_backupbuddy::load_script( 'jquery' );
pb_backupbuddy::load_script( 'jquery-ui-core' );
pb_backupbuddy::load_script( 'jquery-ui-widget' );

// Load accordion JS. Pre WP v3.3 we need to load our own JS file. Was: pb_backupbuddy::load_script( 'jquery-ui-accordion' );
global $wp_version;
pb_backupbuddy::load_script( version_compare( $wp_version, '3.3', '<' ) ? 'jquery.ui.accordion.min.js' : 'jquery-ui-accordion' );

// Destinations may hide the add and test buttons by altering these variables.
global $pb_hide_save;
global $pb_hide_test;
$pb_hide_save = false;
$pb_hide_test = false;

// Load destinations class.
require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );

pb_backupbuddy::load_script( 'filetree.js' );
pb_backupbuddy::load_style( 'filetree.css' );
?>


<script>
	jQuery(document).ready(function() {
		
		// Open settings for destination.
		jQuery( '.dest_select_open' ).click( function() {
			//jQuery('.settings').stop(true, true).slideUp(200); // Limits to only one open at a time.
			jQuery(this).next('.settings').stop(true, true).slideToggle(200);
		} );
		
		
		
		// Save a remote destination settings.
		jQuery( '.pb_backupbuddy_destpicker_save' ).click( function(e) {
			e.preventDefault();
			
			var pb_remote_id = 'NEW'; //jQuery(this).closest('.backupbuddy-destination-wrap').attr('data-destination_id');
			var new_title = jQuery(this).closest('form').find( '#pb_backupbuddy_title' ).val();
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
						window.location.href = '<?php echo $picker_url . '&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ); ?>&sending=<?php echo pb_backupbuddy::_GET( 'sending' ); ?>&selecting=<?php echo pb_backupbuddy::_GET( 'selecting' ); ?>&alert_notice=' + encodeURIComponent( 'New destination successfully added.' );
					} else if ( data == 'Settings saved.' ) {
						jQuery( '.pb_backupbuddy_destpicker_saveload' ).hide();
						jQuery( '.nav-tab-active' ).find( '.destination_title' ).text( new_title );
					} else {
						jQuery( '.pb_backupbuddy_destpicker_saveload' ).hide();
						alert( "Error: \n\n" + data );
					}
					
				}
			);
			
			return false;
		} );
		
		
		
		// Select a destionation to return to parent page.
		jQuery('.bb_destinations-existing .bb_destination-item a').click(function(e) {
			e.preventDefault();
			if ( jQuery(this).parent().hasClass( 'bb_destination-item-disabled' ) ) {
				alert( 'This remote destination is unavailable.  It is either disabled in its Advanced Settings or not compatible with this server.' );
				return false;
			}
			
			<?php
			if ( $mode == 'migration' ) {
				?>
				destination_url = jQuery(this).nextAll('.settings').find('.migration_url').val();
				if ( destination_url == '' ) {
					alert( 'Please enter a destination URL in the settings for the destination, test it, then save before selecting this destination.' );
					jQuery(this).nextAll('.settings').find('.migration_url').css( 'background', '#ffffe0' );
					jQuery(this).nextAll('.settings').first().stop(true, true).slideDown(200);
					return false;
				}
				<?php
			}
			?>
			
			destinationID = jQuery(this).attr( 'rel' );
			console.log( 'Send to destinationID: `' + destinationID + '`.' );
			
			<?php
			if ( pb_backupbuddy::_GET( 'quickstart' ) != '' ) {
				?>
				var win = window.dialogArguments || opener || parent || top;
				win.pb_backupbuddy_quickstart_destinationselected( destinationID );
				win.tb_remove();
				return false;
				<?php
			}
			?>
			
			if ( jQuery( '#pb_backupbuddy_remote_delete' ).is( ':checked' ) ) {
				delete_after = true;
			} else {
				delete_after = false;
			}
			
			var win = window.dialogArguments || opener || parent || top;
			win.pb_backupbuddy_selectdestination( destinationID, jQuery(this).attr( 'title' ), '<?php echo pb_backupbuddy::_GET( 'callback_data' ); ?>', jQuery('#pb_backupbuddy_remote_delete').is(':checked'), '<?php echo $mode; ?>' );
			win.tb_remove();
			return false;
		});
		
		
		
		// Test a remote destination.
		jQuery( '.pb_backupbuddy_destpicker_test' ).click( function() {
			
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
		
		
		
	});
</script>

<style type="text/css">
	.bb-dest-option .settings:before {
		background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/dest_arrow.jpg') top right no-repeat;
		display: block;
		content: '';
		height: 9px;
		width: 17px;
		margin: 0 0 0 94.5%; //556px;
	}
	
	
	
	
	
	
	
	
	

	#pb_backupbuddy_destpicker {
		margin: 10px;
		-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;
		border:1px solid #C9C9C9;
		background-color:#EEEEEE;
		padding: 6px;
	}
	.pb_backupbuddy_destpicker_rowtable {
		width: 100%;
		border-collapse: collapse;
		border-top: 1px solid #C9C9C9;
	}
	.pb_backupbuddy_destpicker_rowtable tr:hover {
		//background: #E8E8E8;
		cursor: pointer;
		
		background: #dbdbdb; /* Old browsers */
background: -moz-radial-gradient(center, ellipse cover, #dbdbdb 0%, #eeeeee 79%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,#dbdbdb), color-stop(79%,#eeeeee)); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover, #dbdbdb 0%,#eeeeee 79%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover, #dbdbdb 0%,#eeeeee 79%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover, #dbdbdb 0%,#eeeeee 79%); /* IE10+ */
background: radial-gradient(ellipse at center, #dbdbdb 0%,#eeeeee 79%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dbdbdb', endColorstr='#eeeeee',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */



	}
	.pb_backupbuddy_destpicker_rowtable td {
		padding: 8px;
		padding-top: 12px;
		padding-bottom: 12px;
	}
	#pb_backupbuddy_destpicker h3:focus {
		outline: 0;
	}
	.pb_backupbuddy_destpicker_type {
		width: 80px;
	}
	.pb_backupbuddy_destpicker_config {
		width: 40px;
		text-align: right;
	}
	.pb_backupbuddy_destpicker_test {
		text-align: center;
		display: inline-block;
		margin-right: 15px;
	}
	.pb_backupbuddy_destpicker_testload {
		display: none;
		vertical-align: -2px;
		margin-left: 10px;
		width: 12px;
		height: 12px;
	}
	.pb_backupbuddy_destpicker_save {
		//width: 90px;
		text-align: center;
		display: inline-block;
		margin-right: 15px;
	}
	.pb_backupbuddy_destpicker_saveload,.pb_backupbuddy_destpicker_deleteload {
		display: none;
		vertical-align: -4px;
		margin-left: 5px;
		width: 16px;
		height: 16px;
	}
	
	
	.pb_backupbuddy_destpicker_newdest {
		background-color:#EEEEEE;
		width: 90%;
		padding: 10px;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 10px;
		-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;
	}
	.pb_backupbuddy_destpicker_newdest_select {
		float: right;
		padding-top: 10px;
	}
	
	.form-table tbody tr th {
		//font-size: 12px;
	}
	
	
	.button-primary:hover {
		color: #FFFFFF;
	}
	
	.bb-dest-view-files {
		display: none;
		float: right;
		margin-right: 10px;
		margin-top: 5px;
		font-style: italic;
	}
</style>



<?php
if ( $mode == 'migration' ) {
	pb_backupbuddy::alert( '
		<b>' . __( 'Tip', 'it-l10n-backupbuddy' ) . ':</b>
		' . __( 'If you encounter difficulty try the ImportBuddy tool. Verify the destination URL by entering the "Migration URL", and clicking "Test Settings" before proceeding.', 'it-l10n-backupbuddy' ) .
		' ' . __( 'Only Local & FTP destinations may be used for automated migrations.', 'it-l10n-backupbuddy' ) . '
	' );
	echo '<br>';
}


global $pb_hide_save;
if ( pb_backupbuddy::_GET( 'add' ) != '' ) {
	
	$destination_type = pb_backupbuddy::_GET( 'add' );
	
	//echo '<h2>Add New Destination</h2>';
	
	// the following scrollTo is so that once scrolling page down to look at long list of destinations to add, coming here bumps them back to the proper place up top.
	?>

	<script>
		var win = window.dialogArguments || opener || parent || top;
		win.window.scrollTo(0,0);
	</script>
	
	<?php
	$destination_info = pb_backupbuddy_destinations::get_info( $destination_type );
	echo '<h3>' . $destination_info['name'] . '</h3>';
	echo '<div class="pb_backupbuddy_destpicker_id bb-dest-option" rel="NEW">';
	$settings = pb_backupbuddy_destinations::configure( array( 'type' => $destination_type ), 'add' );
	
	if ( $settings === false ) {
		echo 'Error #556656a. Unable to display configuration.';
	} else {
		if ( $pb_hide_test !== true ) {
			$test_button = '<a href="#" class="button secondary-button pb_backupbuddy_destpicker_test" href="#" title="Test destination settings.">Test Settings<img class="pb_backupbuddy_destpicker_testload" src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" title="Testing... This may take several seconds..."></a>&nbsp;&nbsp;';
		} else {
			$test_button = '';
		}
		if ( $pb_hide_save !== true ) {
			$save_button = '<img class="pb_backupbuddy_destpicker_saveload" src="' . pb_backupbuddy::plugin_url() . '/images/loading.gif" title="Saving... This may take a few seconds...">';
			echo $settings->display_settings( '+ Add Destination', $test_button, $save_button, 'pb_backupbuddy_destpicker_save' ); // title, before, after, class
		}
		
	}
	echo '</div>';
	//echo '<br><br><br><a class="button secondary-button" href="' . $picker_url . '&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ) . '&quickstart=' . pb_backupbuddy::_GET( 'quickstart' ) . '&filter=' . pb_backupbuddy::_GET( 'filter' ) . '">&larr; back to destinations</a>';
	
	return;
}




// Determine how many destinations we will be listing.
if ( $mode == 'migration' ) {
	$destination_list_count = 0;
	foreach( pb_backupbuddy::$options['remote_destinations'] as $destination ) {
		if ( ( $destination['type'] != 'local' ) && ( $destination['type'] != 'ftp' ) ) { // if not local or ftp when in migration mode then skip.
			continue;
		} else {
			$destination_list_count++;
		}
	}
} else {
	$destination_list_count = count( pb_backupbuddy::$options['remote_destinations'] );
}


function pb_bb_add_box( $mode, $picker_url, $hideBack = false ) {
	?>
	<div class="bb_destinations-group bb_destinations-new">
		<h3>What kind of destination would you like to add?</h3>
		<ul>
			<?php
			$i = 0;
			foreach( pb_backupbuddy_destinations::get_destinations_list() as $destination_name => $destination ) {
				// Never show Deployment ("site") destination here.
				if ( 'site' == $destination['type'] ) {
					continue;
				}
				
				if ( $mode == 'migration' ) {
					if ( ( $destination_name != 'local' ) && ( $destination_name != 'ftp' ) && ( $destination_name != 'sftp' ) ) { // if not local or ftp when in migration mode then skip.
						continue;
					}
				}
				
				// Filter only showing certain destination type.
				if ( '' != pb_backupbuddy::_GET( 'filter' ) ) {
					if ( $destination_name != pb_backupbuddy::_GET( 'filter' ) ) {
						continue; // Move along to next destination.
					}
				}
				
				$i++;
				
				echo '<li class="bb_destination-item bb_destination-' . $destination_name . ' bb_destination-new-item"><a href="' . $picker_url . '&add=' . $destination_name . '&callback_data=' . pb_backupbuddy::_GET( 'callback_data' ) . '&sending=' . pb_backupbuddy::_GET( 'sending' ) . '&selecting=' . pb_backupbuddy::_GET( 'selecting' ) . '" rel="' . $destination_name . '">' . $destination['name'] . '</a></li>';
				if ( $i >= 5 ) {
					echo '<span class="bb_destination-break"></span>';
					$i = 0;
				}
			}
			
			if ( false === $hideBack ) {
			?>
				<br><br>
				<a href="javascript:void(0)" class="btn btn-small btn-white btn-with-icon btn-back btn-back-add"  onClick="jQuery('.bb_destinations-new').hide(); jQuery('.bb_destinations-existing').show();"><span class="btn-icon"></span>Back to existing destinations</a>
			<?php } ?>
		</ul>
	</div>
	<?php
}


$i = 0;
if ( ( pb_backupbuddy::_GET( 'show_add' ) != 'true' ) && ( $destination_list_count > 0 ) ) {
	
	if ( pb_backupbuddy::_GET( 'alert_notice' ) != '' ) {
		pb_backupbuddy::alert( htmlentities( stripslashes( pb_backupbuddy::_GET( 'alert_notice' ) ) ) );
	}
	?>
	
	
	
	
	
	<div class="bb_actions bb_actions_after slidedown" style="">
		<?php require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' ); ?>
		<div class="bb_destinations" style="display: block;">
			<div class="bb_destinations-group bb_destinations-existing">
				<h3>Send to one of your existing destinations?</h3><br>
				<?php if ( '' != pb_backupbuddy::_GET( 'sending' ) ) { ?>
					<label><input type="checkbox" name="delete_after" id="pb_backupbuddy_remote_delete" value="1">Delete local backup after successful delivery?</label>
					<br><br>
				<?php } ?>
				<ul>
					<?php
					foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
						
						// Only show local, ftp, and sftp for migrations.
						if ( 'migration' == $mode ) {
							if (
								( 'local' != $destination['type'] )
								&&
								( 'ftp' != $destination['type'] )
								&&
								( 'sftp' != $destination['type'] )
							) {
								continue;
							}
						}
						
						// Never show Deployment ("site") destination here.
						if ( 'site' == $destination['type'] ) {
							continue;
						}
						
						$disabledClass= '';
						if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
							$disabledClass = 'bb_destination-item-disabled';
						}
						
						echo '<li class="bb_destination-item bb_destination-' . $destination['type'] . ' ' . $disabledClass . '"><a href="javascript:void(0)" title="' . $destination['title'] . '" rel="' . $destination_id . '">' . $destination['title'] . '</a></li>';
					}
					?>
					<br><br><br>
					<a href="javascript:void(0)" class="btn btn-small btn-addnew" onClick="jQuery('.bb_destinations-existing').hide(); jQuery('.bb_destinations-new').show();">Add New Destination +</a>
				</ul>
			</div>
			<?php pb_bb_add_box( $mode, $picker_url); // pb_backupbuddy::ajax_url( 'destination_picker' ) ?>
		</div>
	</div>
	
	
	
	
	
	
	
	
	<?php
} else { // Add Mode
	?>
	
	<div style="text-align: center;">
		<?php pb_bb_add_box( $mode, $picker_url, $hideBack = true ); ?>
	</div>
	<br><br>
	
	<?php
}
?>

<style type="text/css">
	/* Core Styles - USED BY DIRECTORY EXCLUDER */
	.jqueryFileTree LI.directory { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/directory.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.expanded { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/folder_open.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.file { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/file.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.wait { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/spinner.gif') 6px 6px no-repeat; }
	/* File Extensions*/
	.jqueryFileTree LI.ext_3gp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_afp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_afpa { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_asp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_aspx { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_avi { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_bat { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/application.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_bmp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_c { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_cfm { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_cgi { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_com { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/application.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_cpp { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_css { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/css.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_doc { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/doc.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_exe { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/application.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_gif { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_fla { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/flash.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_h { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_htm { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/html.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_html { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/html.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_jar { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/java.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_jpg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_jpeg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_js { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/script.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_lasso { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_log { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/txt.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_m4p { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_mov { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_mp3 { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_mp4 { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_mpg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_mpeg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_ogg { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_pcx { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_pdf { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/pdf.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_php { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/php.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_png { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_ppt { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ppt.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_psd { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/psd.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_pl { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/script.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_py { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/script.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_rb { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_rbx { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_rhtml { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_rpm { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/linux.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_ruby { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/ruby.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_sql { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/db.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_swf { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/flash.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_tif { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_tiff { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/picture.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_txt { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/txt.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_vb { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_wav { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/music.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_wmv { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/film.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_xls { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/xls.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_xml { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/code.png') 6px 6px no-repeat; }
	.jqueryFileTree LI.ext_zip { background: url('<?php echo pb_backupbuddy::plugin_url(); ?>/images/filetree/zip.png') 6px 6px no-repeat; }
</style>