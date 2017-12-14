<?php
pb_backupbuddy::$ui->title( 'Remote Site Migration' );



// Set whether or not the migration should auto-begin or wait for user to click to begin.
$auto_run = false;


?>


<script type="text/javascript">
	keep_polling = 1;
	pb_step = '1';
	
	
	jQuery(document).ready(function() {
			// Wait 2 seconds before first poll.
			//setTimeout( 'backupbuddy_poll()' , 2000 );
			//setInterval( 'blink_ledz()' , 600 );
			
			
			jQuery( '#pb_backupbuddy_migration_form' ).submit(function() {
				if ( jQuery('#pb_backupbuddy_url').val() == '' ) { // Make sure URL is entered.
					alert( 'Invalid site URL.' );
					return false;
				}
				
				pb_step = '1'; // Set to start over.
				backupbuddy_poll();
				return false;
			});
	});
	
	
	

	
	
	
	function backupbuddy_poll() {
		jQuery('#pb_backupbuddy_loading').show();
		jQuery.ajax({
			url:		'<?php echo pb_backupbuddy::ajax_url( 'migrate_status' ); ?>',
			type:		'post',
			dataType:	'json',
			data:		{
							step: 'step' + pb_step,
							destination: '<?php echo pb_backupbuddy::_GET( 'destination' ); ?>',
							backup_file: '<?php echo pb_backupbuddy::_GET( 'callback_data' ); ?>',
							url: jQuery('#pb_backupbuddy_url').val(),
						},
			error:		function( jqXHR, textStatus, errorThrown ) {
				jQuery('#pb_backupbuddy_loading').hide();
				alert( 'Error or invalid formatted server response. Response: `' + jqXHR.responseText + '`; Status: `' + textStatus + '`; Error thrown: `' + errorThrown + '`.');
			},
			success: function( data ) { // data contains json response objects.
				stop_polling = false;
				
				if ( pb_step == '0' ) { // Finished. Stop polling.
					
					stop_polling = true;
					
				} else if ( pb_step == '1' ) { // Step 1 - Checks if backup and importbuddy are done transferring yet.
					
					jQuery('#pb_backupbuddy_loading').hide();
					jQuery( '#pb_backupbuddy_statusmsg' ).html( data.status_message );
					
				} else if ( pb_step == '2' ) { // Step 2 - Checks to see if URL seems to point to importbuddy.php by accessing http://url.com/importbuddy.php?api=ping serverside to look for pong.
					
					jQuery('#pb_backupbuddy_loading').hide();
					jQuery( '#pb_backupbuddy_statusmsg' ).html( data.status_message );
					if ( data.status_code == 'success' ) {
						
						jQuery( '#pb_backupbuddy_iframe' ).attr( 'src', data.import_url ); // jQuery('#pb_backupbuddy_url').val()
						jQuery( '#pb_backupbuddy_migration_begin' ).attr('disabled','disabled');
						
						jQuery( '.pb_backupbuddy_start_migration' ).slideUp();
					} else {
						
					}
					stop_polling = true;
					
				
				} else { // Unknown step.
					
					jQuery('#pb_backupbuddy_loading').hide();
					alert( 'Unknown step `' + pb_step + '`.' );
					stop_polling = true;
					
				}
				
				if ( data.next_step != '0' ) {
					pb_step = data.next_step; // Assign next step based on what AJAX response says it is.
				} else { // Told next step is 0 which means something went wrong.
					stop_polling = true;
				}
				
				if ( stop_polling == false ) { // Continuing to poll since not told to stop.
					setTimeout( 'backupbuddy_poll()' , 2000 );
				}
			}
		});
		
	}
</script>

<?php
function pb_intersection( $text1, $text2 ) {
	
	$text1 = str_split( $text1 );
	$text2 = str_split( $text2 );
	
	$intersection = array_intersect_assoc( $text1, $text2 );
	
	return implode( '', $intersection );
}
function pb_str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if($pos === false)
    {
        return $subject;
    }
    else
    {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
}





$destination = pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET( 'destination' )];
if ( !isset( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET( 'destination' )] ) ) {
	die( 'Error #83474783. Invalid destination ID.' );
}

if ( $destination['type'] == 'local' ) {
	$source_path = rtrim( ABSPATH, '\\/' );
	$source_url = rtrim( site_url(), '\\/' );
	$source_url_parts = parse_url( $source_url );
	$destination_path = rtrim( $destination['path'], '\\/' );
	
	$intersection = rtrim( pb_intersection( $source_path, $destination_path ), '\\/' );
	
	if ( isset( $source_url_parts['path'] ) ) {
		$web_root = pb_str_lreplace( $source_url_parts['path'], '', $source_path );
	} else {
		$web_root = $source_path;
	}
	
	/*
	echo 'source: '	.			$source_path . ' = ' . $source_url		. '<br>';
	echo 'dest: ' .				$destination_path						. '<br>';
	echo 'intersection: ' .		$intersection							. '<br>';
	echo '<pre>' .				print_r( $source_url_parts, true )		. '</pre>';
	echo 'webroot: ' .			$web_root								. '<br>';
	*/
	
	if ( $intersection == $web_root ) { // Same site URL just different subdirectories.
		$new_subdir = trim( str_replace( $web_root, '', $destination_path ), '\\/' );
		$www_example = rtrim( site_url(), '\\/' );
		$www_example_dir = $new_subdir;
		
		$guess_url = $www_example . '/' . $www_example_dir;
		$destination_url = $guess_url;
	} else {
		$guess_url = 'Unknown';
	}
	
	if ( $destination['url'] != '' ) {
		$destination_url = $destination['url'];
		
		if ( $auto_run === true ) { // Whether or not to automatically run the script.
			?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery( '#pb_backupbuddy_migration_form' ).submit();
				});
			</script>
			<?php
		}
	}
} else { // FTP
	
	$guess_url = 'Unknown; No URL specified in FTP destination settings.';
	$destination_url = '';
	if ( isset( $destination['address'] ) && ( isset( $destination['path'] ) ) ) {
		
		$www_example = $destination['address'];
		$www_example = str_replace( 'ftp://', '', $www_example );
		$www_example = str_replace( 'ftp.', '', $www_example );
		$www_example = rtrim( $www_example, '/' );
		$www_example = 'http://' . $www_example;
		
		$www_example_dir = $destination['path'];
		$www_example_dir = str_replace( '/www/', '', $www_example_dir );
		$www_example_dir = str_replace( '/htdocs/', '', $www_example_dir );
		$www_example_dir = str_replace( '/public_html/', '', $www_example_dir );
		$www_example_dir = ltrim( $www_example_dir, '/' );

		$guess_url = $www_example . '/' . $www_example_dir;
			
		if ( $guess_url != 'Unknown' ) {
			$destination_url = $guess_url;
		} else {
			$destination_url = '';
		}
		
	}
}

?>

<div class="pb_backupbuddy_start_migration">
	<?php pb_backupbuddy::$ui->start_metabox( 'Begin Migration', true, 'width: 100%;' ); ?>
	Below is the URL entered corresponding to the <?php
	if ( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET( 'destination' )]['type'] == 'ftp' ) {
		echo 'ftp destination';
		echo '`' . htmlentities( pb_backupbuddy::_GET( 'destination_title' ) ) . '`';
	} elseif ( pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET( 'destination' )]['type'] == 'local' ) {
		echo 'local destination';
		echo '`' . pb_backupbuddy::$options['remote_destinations'][pb_backupbuddy::_GET( 'destination' )]['path'] . '`';
	} else {
		echo '{Unknown destination.}';
	}
	?>
	selected on the previous page. This URL must lead to the location where files uploaded to this remote destination
	would end up. If the destination is in a subdirectory make sure to include it in the corresponding URL.<br><br>
	
	
	
	<form id="pb_backupbuddy_migration_form">
		<span style="float: left; display: inline-block; width: 140px; margin: 12px;">
			Destination site URL<br>
			<span class="description">
				(from destination settings)
			</span>
		</span>
		<input style="float: left; margin: 12px;" type="text" name="url" id="pb_backupbuddy_url" size="40" value="<?php echo $destination_url; ?>">
		<span style="float: left; margin: 12px; padding-top: 3px;" class="description">Guess: <?php echo $guess_url; ?></span>
		</span>
		<br style="clear: both;"><br>
		<div style="display: inline-block; width: 140px; float: left;">
			<input id="pb_backupbuddy_migratesubmit" type="submit" name="submit" value="Begin Migration" class="button-primary" value="http://" id="pb_backupbuddy_migration_begin">
		</div>
	
		<div id="pb_backupbuddy_statusmsg" style="display: inline-block; float: left; padding-top: 3px; width: 70%;"><?php _e( 'Status: Your destination URL has been entered above. Click Begin Migration when ready.', 'it-l10n-backupbuddy' ); ?></div>
		<span id="pb_backupbuddy_loading" style="display: none; margin-left: 10px; float: left;"><img src="<?php echo pb_backupbuddy::plugin_url(); ?>/images/loading.gif" <?php echo 'alt="', __('Loading...', 'it-l10n-backupbuddy' ),'" title="',__('Loading...', 'it-l10n-backupbuddy' ),'"';?> width="16" height="16" style="vertical-align: -3px;" /></span>
		<br style="clear: both;">
	</form>
	
	<?php pb_backupbuddy::$ui->end_metabox(); ?>
</div>





<div id="pb_backupbuddy_migrate">
	<?php //pb_backupbuddy::$ui->start_metabox( 'Migration', true, 'width: 100%;' ); ?>
	<iframe id="pb_backupbuddy_iframe" src="" width="100%" height="2800" frameBorder="0">Error #4584594579. Browser not compatible with iframes.</iframe>
	<?php //pb_backupbuddy::$ui->end_metabox(); ?>
</div>