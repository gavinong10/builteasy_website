<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">
	<head>
		<meta charset="utf-8">
		<title>ImportBuddy v<?php echo pb_backupbuddy::$options['bb_version']; ?> Restore / Migration Tool - Powered by BackupBuddy</title>
		<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
		
		<?php
		require( '_assets.php' );
		?>
		
		<link rel="icon" type="image/png" href="importbuddy/images/favicon.png">
		<script type="text/javascript">
			
			var statusBox; // Make global.
			var backupbuddy_errors_encountered = 0; // number of errors sent via log.
			
			function pb_status_append( json ) {
				if ( 'undefined' === typeof statusBox ) { // No status box yet so may need to create it.
					statusBox = jQuery( '#backupbuddy_messages' );
					if( statusBox.length == 0 ) { // No status box yet so suppress.
						return;
					}
				}
				
				if ( 'string' == ( typeof json ) ) {
					backupbuddy_log( json );
					console.log( 'Status log received string: ' + json );
					return;
				}
				
				// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
				json.date = new Date();
				json.date = new Date(  ( json.time * 1000 ) + json.date.getTimezoneOffset() * 60000 );
				var seconds = json.date.getSeconds();
				if ( seconds < 10 ) {
					seconds = '0' + seconds;
				}
				json.date = backupbuddy_hourpad( json.date.getHours() ) + ':' + json.date.getMinutes() + ':' + seconds;
				
				triggerEvent = 'backupbuddy_' + json.event;
				
				
				// Log non-text events.
				if ( ( 'details' !== json.event ) && ( 'message' !== json.event ) && ( 'error' !== json.event ) ) {
					//console.log( 'Non-text event `' + triggerEvent + '`.' );
				} else {
					//console.log( json.data );
				}
				//console.log( 'trigger: ' + triggerEvent );
				
				jQuery('#backupbuddy_messages').trigger( triggerEvent, [json] );
				
				
			} // End function pb_status_append().
			
			
			// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php and _rollback.php
			function backupbuddy_log( json, classType ) {
				if ( 'undefined' === typeof statusBox ) { // No status box yet so may need to create it.
					statusBox = jQuery( '#backupbuddy_messages' );
					if( statusBox.length == 0 ) { // No status box yet so suppress.
						return;
					}
				}
				
				message = '';
				
				if ( 'string' == ( typeof json ) ) {
					if ( '' !== classType ) {
						json = '<span class="backupbuddy_log_' + classType + '">' + json + '</span>';
					}
					message = "-----------\t\t-------\t-------\t" + json;
				} else {
					if ( '' !== classType ) {
						json.data = '<span class="backupbuddy_log_' + classType + '">' + json.data + '</span>';
					}
					message = json.date + '.' + json.u + " \t" + json.run + "sec \t" + json.mem + "MB\t" + json.data;
				}
				
				statusBox.append( "\r\n" + message );
				statusBox.scrollTop( statusBox[0].scrollHeight - statusBox.height() );
				
			}
			
			
			// Trigger an error to be logged, displayed, etc.
			// Returns updated message with trouble URL, etc.
			// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
			function backupbuddyError( message ) {

				// Get start of any error numbers.
				troubleURL = '';
				error_number_begin = message.toLowerCase().indexOf( 'error #' );

				if ( error_number_begin >= 0 ) {
					error_number_begin += 7; // Shift over index to after 'error #'.
					error_number_end = message.toLowerCase().indexOf( ':', error_number_begin );
					if ( error_number_end < 0 ) { // End still not found.
						error_number_end = message.toLowerCase().indexOf( '.', error_number_begin );
					}
					if ( error_number_end < 0 ) { // End still not found.
						error_number_end = message.toLowerCase().indexOf( ' ', error_number_begin );
					}
					error_number = message.slice( error_number_begin, error_number_end );
					troubleURL = 'http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#' + error_number;
				}

				if ( '' !== troubleURL ) {
					// Display error in error div with class error_alert_box.
					message = message + ' <a href="' + troubleURL + '" target="_blank">Click to <b>view error details</b> in the Knowledge Base</a>';
				}
				jQuery( '.backupbuddy_error_list' ).append( '<li>' +  message + '</li>' );
				jQuery( '.error_alert_box' ).show();

				// Display error box to make it clear errors were encountered.
				backupbuddy_errors_encountered++;
				jQuery( '#backupbuddy_errors_notice_count' ).text( backupbuddy_errors_encountered );
				jQuery( '#backupbuddy_errors_notice' ).slideDown();

				// If the word error is nowhere in the error message then add in error prefix.
				if ( message.toLowerCase().indexOf( 'error' ) < 0 ) {
					message = 'ERROR: ' + message;
				}


				return message; // Return updated error message with trouble URL.
			} // end backupbuddyError().


			// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
			function backupbuddyWarning( message ) {
				jQuery( '.backupbuddy_warning_list' ).append( '<li>' +  message + '</li>' );
				//jQuery( '.warning_alert_box' ).show();
				return 'Warning: ' + message;
			} // end backupbuddyWarning().
			
			// left hour pad with zeros
			function backupbuddy_hourpad(n) { return ("0" + n).slice(-2); }
			
			function randomString(length, chars) {
				var result = '';
				for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
				return result;
			}
			
			
		</script>
		<script type="text/javascript" src="importbuddy/js/jquery.leanModal.min.js"></script>
		<script type="text/javascript" src="importbuddy/js/ejs.js"></script>
		<script type="text/javascript" src="importbuddy/js/main.js"></script>
		<script type="text/javascript" src="importbuddy/js/restoreEvents.js"></script>
		<script type="text/javascript">
			EJS.config({cache: false});
			
			window.restoreData = {};
			
			jQuery(document).ready(function() {
				jQuery('.leanModal').leanModal(
					{ top : 45, overlay : 0.4, closeButton: ".modal_close" }
				);
				
				/* MD5 Hash Button Clicked */
				jQuery( '.view_hash_click' ).click( function() {
					jQuery('#hash_view_loading').show();
					jQuery('#hash_view_response').hide();
					
					var backupFile = jQuery(this).attr( 'data-file' );
					jQuery.ajax({
						type: 'POST',
						url: 'importbuddy.php',
						data: {
							ajax: 'file_hash',
							file: backupFile
						},
						dataType: 'json'
					}).done( function(data) {
						jQuery('#hash_view_response').html( '<b>Checksum (MD5 hash):</b> <input type="text" disabled="disabled" value="' + data.hash + '" style="width: 400px;">' );
						jQuery('#hash_view_loading').hide();
						jQuery('#hash_view_response').show();
					}).fail( function( jqXHR, textStatus, errorThrown ){
						jQuery('#hash_view_response').html( 'Error: `' + jqXHR.responseText + '`.' );
						jQuery('#hash_view_loading').hide();
						jQuery('#hash_view_response').show();
					});
				});
				
				
				
				jQuery( '.main_box' ).on( 'submit', 'form', function(e) {
					if ( 'miniFrame' == jQuery(this).attr( 'target' ) ) {
						NProgress.start();
					}
					return true;
				});
				
				// Pre-load final steps so it can be displayed even though deleted.
				window.stepTemplateCleanupSettings = new EJS({url: 'importbuddy/views/cleanupSettings.ejs'});
				window.stepTemplatefinalCleanup = new EJS({url: 'importbuddy/views/finalCleanup.ejs'});
				
			});
			
			
			function bb_action( action, note ) {
				console.log( 'bb_action: `' + action + '`.' );
				if ( 'unzipSuccess' == action ) {
				} else if ( 'iframeLoaded' == action ) { // Hide iframe loading graphic.
					//NProgress.done();
				} else if ( 'importingTable' == action ) {
					jQuery('#importingDatabase-progressMessage').text( 'Restoring ' + note + ' ...' ); // note contains table name
				} else if ( 'databaseRestoreSuccess' == action ) {
					jQuery('#importingDatabase-progressMessage').text( 'Database Restore Successful' );
				} else if ( 'databaseRestoreSkipped' == action ) {
					jQuery('#importingDatabase-progressMessage').text( 'Database Restore Skipped' );
				} else if ( 'databaseRestoreFailed' == action ) {
					jQuery('#importingDatabase-progressMessage').text( 'Database Restore Failed' );
				} else if ( 'databaseMigrationSuccess' == action ) {
					jQuery('#migratingDatabase-progressMessage').text( 'Database Migration Successful' );
				} else if ( 'databaseMigrationSkipped' == action ) {
					jQuery('#migratingDatabase-progressMessage').text( 'Database Migration Skipped' );
				} else if ( 'databaseMigrationFailed' == action ) {
					jQuery('#migratingDatabase-progressMessage').text( 'Database Migration Failed' );
				} else if ( 'filesRestoreSuccess' == action ) {
					jQuery('#unzippingFiles-progressMessage').text( 'Completed Restoring Files' );
				} else if ( 'filesRestoreSkipped' == action ) {
					jQuery('#unzippingFiles-progressMessage').text( 'Skipped Restoring Files' );
				} else {
					console.log( 'Unknown JS bb_action `' + action + '` with note `' + note + '`.' );
				}
			}
			
			
			function bb_showStep( step, data ) {
				window.restoreData = data;
				jQuery('.step-wrap').hide();
				console.log( 'Show step: `' + step + '`.' );
				console.dir( window.restoreData );
				//jQuery('.step-' + step + '-wrap').show();
				if ( 'finished' == step ) { // In case we cannot load final template, at least say finished.
					jQuery('.main_box_foot').html( '<h3>Finished</h3>' );
				}
				
				if ( 'cleanupSettings' == step ) { // Preloaded template.
					jQuery('.main_box').html( window.stepTemplateCleanupSettings.render(data) );
				} else if ( 'finalCleanup' == step ) {
					jQuery('.main_box').html( jwindow.stepTemplatefinalCleanup.render(data) );
				} else { // Normal step.
					jQuery('.main_box').html( new EJS({url: 'importbuddy/views/' + step + '.ejs'}).render(data) );
				}
			}
			
			
			function tip( tip ) {
				return '<a class="pluginbuddy_tip" title="' + tip + '"><img src="importbuddy/pluginbuddy/images/pluginbuddy_tip.png" alt="(?)"></a>';
			}
			
			
		</script>
		<style>
			#backupbuddy_messages {
				background: #fff;
				border: 1px solid #CFCFCF;
				-moz-border-radius: 25px;
				height: 200px;
				overflow: scroll;
				padding: 12px;
				white-space: pre;
				width: 100%;
				margin: 0;
				box-sizing: border-box;
				resize: both;
			}
			.backupbuddy_log_error {
				color: red;
				font-weight: bold;
			}
			.backupbuddy_log_warning {
				color: orange;
				font-weight: bold;
			}
			.backupbuddy_log_notice {
				color: blue;
				font-weight: bold;
			}
			#backupbuddy_messages::-webkit-scrollbar {
				-webkit-appearance: none;
				width: 11px;
				height: 11px;
			}
			#backupbuddy_messages::-webkit-scrollbar-thumb {
				border-radius: 8px;
				border: 2px solid white; /* should match background, can't be transparent */
				background-color: rgba(0, 0, 0, .5);
			}​
		</style>
	</head>
		<?php
		//if ( pb_backupbuddy::$options['display_mode'] == 'normal' ) {
			echo '<body';
			if ( 'embed' != pb_backupbuddy::_GET( 'display_mode' ) ) { echo ' style="background: #FFF;"'; }
			echo '>';
			if ( 'embed' != pb_backupbuddy::_GET( 'display_mode' ) ) {
				echo '<div class="topNav">';
					
					if ( true === Auth::is_authenticated() ) { // Only display these links if logged in.
						echo '<a ';
						if ( pb_backupbuddy::_GET( 'step' ) != '' ) { echo 'class="activePage" '; }
						echo 'href="importbuddy.php">Restore / Migrate</a>';
						
						echo '<a ';
						if ( pb_backupbuddy::_GET( 'page' ) == 'serverinfo' ) { echo 'class="activePage" '; }
						echo 'href="?page=serverinfo">Server Information</a>';
						
						echo '<a ';
						if ( pb_backupbuddy::_GET( 'page' ) == 'dbreplace' ) { echo 'class="activePage" '; }
						echo 'href="?page=dbreplace">Database Text Replace</a>';
						
						echo '<a href="http://ithemes.com/codex/page/BackupBuddy" target="_blank">Knowledge Base</a>';
						echo '<a href="http://ithemes.com/support/" target="_blank">Support</a>';
					}
					
					$simpleVersion = pb_backupbuddy::$options['bb_version'];
					if ( strpos( pb_backupbuddy::$options['bb_version'], ' ' ) > 0 ) {
						$simpleVersion = substr( pb_backupbuddy::$options['bb_version'], 0, strpos( pb_backupbuddy::$options['bb_version'], ' ' ) );
					}
					echo '<a href="http://ithemes.com/purchase/backupbuddy/" target="_blank" title="Visit BackupBuddy Website in New Window" style="float: right;"><img src="importbuddy/images/icon_menu_32x32.png" width="16" height="16">&nbsp;&nbsp;ImportBuddy v' . $simpleVersion . ' for BackupBuddy</a>';
				echo '</div>';
			}
		?>
		
		<div style="position: relative; height: 70px; margin-top: 50px; width: 100%; overflow: hidden;">
			<div style="max-width: 1000px; margin-left: auto; margin-right: auto;">
				<!-- <span id="restorebuddy_iframe_placeholder">Welcome step 1</span> -->
				<script>iframePostInit = false;</script>
				<iframe onLoad="if ( true === iframePostInit ) { NProgress.done(); }" name="miniFrame" id="miniFrame" width="100%" height="70px" frameborder="0" padding="0" margin="0">Error #4584594579. Browser not compatible with iframes.</iframe>
				<script>iframePostInit = true;</script>
			</div>
		</div>
		
		<div style="display: none;" id="pb_importbuddy_blankalert"><?php pb_backupbuddy::alert( '#TITLE# #MESSAGE#', true, '9021' ); ?></div>
		
		<div class="main_box_wrap">
			
			
			<?php if ( true === Auth::is_authenticated() ) { // Only record logging if authenticated. ?>
				<div class="main_box_head">
					<span id="pageTitle">&nbsp;</span>
					<a style="font-size: 0.6em; float: right; margin-top: 6px;" href="javascript:void(0)" onclick="jQuery('#pb_backupbuddy_status_wrap').toggle();">Display Status Log</a>
				</div>
				<?php
				echo pb_backupbuddy::$classes['import']->status_box( 'Status Log for for ImportBuddy from BackupBuddy v' . pb_backupbuddy::$options['bb_version'] . '...' );
				?>
				
				<script>importbuddy_loadRestoreEvents();</script>
				<?php
			} else { ?>
				<div class="main_box_head">
					<span id="pageTitle">&nbsp;</span>
				</div>
				
			<?php } ?>
			<div class="main_box_head error_alert_box" style="display: none;">
				<span class="error_alert_title">Error(s)</span>
				<ul class="backupbuddy_error_list">
					<!-- <li>Error #123onlyAtest: An error has NOT happened. This is a only test.</li> -->
				</ul>
			</div>
			<div class="main_box_head warning_alert_box" style="display: none;">
				<span class="error_warning_title">Alert(s)</span>
				<ul class="backupbuddy_warning_list">
					<!-- <li>Error #123onlyAtest: A warning has NOT happened. This is a only test.</li> -->
				</ul>
			</div>
			
			
			<div class="main_box">
			
			
			