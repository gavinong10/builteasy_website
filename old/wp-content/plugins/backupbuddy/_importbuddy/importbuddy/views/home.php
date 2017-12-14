<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
?>
<script>
	jQuery( '#pageTitle' ).html( 'Step 1: Select Backup to Restore' );
	
	function backupRestoreSuggest( backupType ) {
		if ( 'db' == backupType ) { // DB has no files (except SQL of course) so skip root file extraction.
			jQuery( 'input[name="restoreFiles"]' ).prop( 'checked', false ).attr( 'disabled', true );
			jQuery( 'input[name="restoreDatabase"]' ).prop( 'checked', true ).attr( 'disabled', false );
		} else if ( 'files' == backupType ) { // Files has no DB so disable DB.
			jQuery( 'input[name="restoreFiles"]' ).prop( 'checked', true ).attr( 'disabled', false );
			jQuery( 'input[name="restoreDatabase"]' ).prop( 'checked', false ).attr( 'disabled', true );
		} else { // default restore all
			jQuery( 'input[name="restoreFiles"]' ).prop( 'checked', true ).attr( 'disabled', false );
			jQuery( 'input[name="restoreDatabase"]' ).prop( 'checked', true ).attr( 'disabled', false );
		}
	}
	
	jQuery(document).ready(function() {
		jQuery( '.selectableBackupArchive' ).click( function(){
			backupType = jQuery(this).attr( 'data-type' );
			backupRestoreSuggest( backupType );
		});
		
		startingBackupType = jQuery( '.selectableBackupArchive:checked' ).attr( 'data-type' );
		backupRestoreSuggest( startingBackupType );
	});
</script>


<?php
echo '<div class="step-wrap step-selectBackup-wrap">';



// Display pre-flight scan warnings & errors.
$preflightResults = preflightScan();
if ( count( $preflightResults ) > 0 ) {
	pb_backupbuddy::alert( implode( '<hr>', $preflightResults ) );
}
unset( $preflightResults );



//$step = '1';
//echo '<script>pageTitle( "Step <span class=\"step_number\">' . $step . '</span> of 6: Choose your backup file" );</script>';

if ( 'stash' == pb_backupbuddy::_GET( 'upload' ) ) {
	require( '_html_1_stash.php' );
	echo '</div>';
	return;
}
?>










<div class="wrap">

<?php
if ( pb_backupbuddy::_GET( 'file' ) != '' ) {
	$backup_archives = array( pb_backupbuddy::_GET( 'file' ) );
	echo '<div style="padding: 15px; background: #FFFFFF;">Restoring from backup <i>' . htmlentities( pb_backupbuddy::_GET( 'file' ) ) . '</i></div>
	<form action="?ajax=2" method="post" target="restorebuddy_iframe">';
	echo '<input type="hidden" name="file" value="' . pb_backupbuddy::_GET( 'file' ) . '">';
} else {
	?>
	
	<div class="backup_select_buttons">
		<button href="#pb_upload_modal" class="button button-secondary leanModal createdb_modal_link" style="font-size: 14px;">Upload a Backup</button>
		<button href="#pb_stash_modal" class="button button-secondary leanModal createdb_modal_link" style="font-size: 14px;">Restore from Stash</button>
	</div>
	
	<?php
	$backup_archives = get_archives_list();
	if ( 0 == count( $backup_archives ) ) { // No backups found.
		
		// Look for manually unzipped
		pb_backupbuddy::alert( '<b>No BackupBuddy Zip backup found in this directory `' . ABSPATH . '`</b> - 
			You must upload a backup file by FTP (into the same directory as this importbuddy.php file), the upload tab, or import from Stash via the Stash tab above to continue.
			<b>Do not rename the backup file from its original filename.</b> If you manually extracted/unzipped, upload the backup file,
			select it, then select <i>Advanced Troubleshooting Options</i> & click <i>Skip Zip Extraction</i>. Refresh this page once you have uploaded the backup.' );
		
	} else { // Found one or more backups.
		?>
			<form action="?ajax=2" method="post" target="miniFrame">
				<input type="hidden" name="pass_hash" value="<?php echo PB_PASSWORD; ?>">
				<input type="hidden" name="options" value="<?php echo htmlspecialchars( serialize( pb_backupbuddy::$options ) ); ?>'" />
		<?php
		echo '<div class="backup_select_text">Backups in <span>' . ABSPATH . '</span></div>';
		echo '<ul class="round-wrap">';
		$backup_count = count( $backup_archives );
		$i = 0;
		foreach( $backup_archives as $backup_id => $backup_archive ) {
			$i++;
			
			
			$backup_type = '';
			$backup_type_text = '';
			if ( $backup_archive['comment']['type'] == '' ) {
				if ( stristr( $backup_archive['file'], '-db-' ) !== false ) {
					$backup_type_text = 'Database Only Backup';
					$backup_type = 'db';
				} elseif ( stristr( $backup_archive['file'], '-full-' ) !== false ) {
					$backup_type_text = 'Full Backup';
					$backup_type = 'full';
				} elseif ( stristr( $backup_archive['file'], '-files-' ) !== false ) {
					$backup_type_text = 'Files Only Backup';
					$backup_type = 'files';
				} elseif ( stristr( $backup_archive['file'], '-export-' ) !== false ) {
					$backup_type_text = 'Multisite Subsite Export';
					$backup_type = 'export';
				}
			} else {
				if ( $backup_archive['comment']['type'] == 'db' ) {
					$backup_type_text = 'Database Only Backup';
					$backup_type = 'db';
				} elseif ( $backup_archive['comment']['type'] == 'full' ) {
					$backup_type_text = 'Full Backup';
					$backup_type = 'full';
				} elseif ( $backup_archive['comment']['type'] == 'files' ) {
					$backup_type_text = 'Files Only Backup';
					$backup_type = 'files';
				} elseif ( $backup_archive['comment']['type'] == 'export' ) {
					$backup_type_text = 'Multisite Subsite Export';
					$backup_type = 'export';
				} else {
					$backup_type_text = $backup_archive['comment']['type'] . ' Backup';
					$backup_type = $backup_archive['comment']['type'];
				}
			}
			
			
			echo '<li>';
			
			echo '<input class="selectableBackupArchive" type="radio" ';
			if ( $backup_id == 0 ) {
				echo 'checked="checked" ';
			}
			echo 'name="file" value="' . $backup_archive['file'] . '" data-type="' . $backup_type . '"> ' . $backup_archive['file'];
			echo '<span style="float: right;">' . pb_backupbuddy::$format->file_size( filesize( ABSPATH . $backup_archive['file'] ) ) . '</span>';
			
			echo '<div class="description" style="margin-left: 22px; margin-top: 6px; font-style: normal; line-height: 26px;">';
			$meta = array();
			
			echo $backup_type_text;
			
			if ( $backup_archive['comment']['created'] != '' ) {
				echo ' from ' . pb_backupbuddy::$format->date( $backup_archive['comment']['created'] );
			}
			
			if ( $backup_archive['comment']['wp_version'] != '' ) {
				echo ' on WordPress v' . $backup_archive['comment']['wp_version'];
			}
			/*
			if ( $backup_archive['comment']['bb_version'] != '' ) {
				echo ' & BackupBuddy v' . $backup_archive['comment']['bb_version'];
			}
			*/
			
			if ( $backup_archive['comment']['siteurl'] != '' ) {
				echo '<br>Site: ' . $backup_archive['comment']['siteurl'];
			}
			
			if ( $backup_archive['comment']['profile'] != '' ) {
				echo '<br>Profile: ' . htmlentities( $backup_archive['comment']['profile'] );
			}
			
			if ( $backup_archive['comment']['note'] != '' ) {
				echo '<br>Note: ' . htmlentities( $backup_archive['comment']['note'] ) . '<br>';
			}
			
			
			
			// Show meta button if meta info available.
			if ( $backup_archive['comment']['type'] != '' ) {
				$file_hash = md5( $backup_archive['file'] );
				echo '<a href="#hash_view" class="button button-tertiary leanModal view_hash_click" style=" float: right;" id="view_hash_' . $i . '" data-file="' . $backup_archive['file'] . '">View Checksum</a>';
				echo '<a href="#info_' . $file_hash . '" class="button button-tertiary leanModal" style="float: right;" id="view_meta_' . $i . '">View Meta</a>';
				?>
				<div id="hash_view" style="display: none;">
					<div class="modal">
						<div class="modal_header">
							<a class="modal_close">&times;</a>
							<h2>View Checksum</h2>
						</div>
						<div class="modal_content">
							<span id="hash_view_loading"><img src="importbuddy/images/loading.gif"> Calculating backup file Checksum (MD5 hash)... This may take a moment...</span>
							<span id="hash_view_response"></span>
						</div>
					</div>
				</div>
				<div id="<?php echo 'info_' . $file_hash; ?>" style="display: none; height: 90%;">
					<div class="modal">
						<div class="modal_header">
							<a class="modal_close">&times;</a>
							<h2>Backup Meta Information</h2>
						</div>
						<div class="modal_content">
							<?php
							$comment_meta = array();
							foreach( $backup_archive['comment'] as $comment_line_name => $comment_line_value ) { // Loop through all meta fields in the comment array to display.
								
								if ( false !== ( $response = backupbuddy_core::pretty_meta_info( $comment_line_name, $comment_line_value ) ) ) {
									$comment_meta[] = $response;
								}
								
							}
							if ( count( $comment_meta ) > 0 ) {
								pb_backupbuddy::$ui->list_table(
									$comment_meta,
									array(
										'columns'		=>	array( 'Meta Information', 'Value' ),
										'css'			=>	'width: 100%; min-width: 200px;',
									)
								);
							} else {
								echo '<i>No meta data found in zip comment. Skipping meta information display.</i>';
							}
							?>
						</div>
					</div>
				</div>
				<?php
			} // end if type not blank.
			
			
			
			//echo implode( ' - ', $meta );
			echo '</div>';
			echo '</li>';
		}
		echo '</ul>';
	}
	?>
	
<?php
} // End file not given in querystring.





// If one or more backup files was found then provide a button to continue.
if ( ( !empty( $backup_archives ) ) || ( 'stash' == pb_backupbuddy::_POST( 'upload' ) ) ) {
	echo '</div><!-- /wrap -->';
	?>
	
	
	<div class="main_box_foot">
		<center>
			
			<div style="margin-top: 20px; margin-bottom: 30px;">
				<b>What would you like to restore from the backup?</b>
				&nbsp;&nbsp;
				<label style="float: inherit;">
					<input type="checkbox" name="restoreFiles" value="1" CHECKED> Files
				</label>
				&nbsp;&nbsp;
				<label style="float: inherit;">
					<input type="checkbox" name="restoreDatabase" value="1" CHECKED> Database
				</label>
			</div>
			
			<input type="submit" name="submit" value="Restore Backup" class="it-button">
			<button id="advanced_options_button" href="#pb_advanced_modal" class="it-button it-secondary leanModal">Advanced Options</button>
		</center>
	</div>
<?php
} else {
	//pb_backupbuddy::alert( 'Upload a backup file to continue.' );
	echo '<br /><br /><br />';
	echo '<b>You must upload a backup file by FTP, the upload tab, or import from Stash to continue.</b>';
	echo '</div><!-- /wrap -->';
}
?>







<div id="pb_advanced_modal" style="display: none;">
	<div class="modal">
		<div class="modal_header">
			<a class="modal_close">&times;</a>
			<h2>Advanced Options</h2>
			Exercise caution. Additional options available on subsequent steps.
		</div>
		<div class="modal_content">
			
			
			Unzip Strategy:
			<select name="zipMethodStrategy">
				<option value="all" SELECTED>All Available ( unzip via exec() &gt; ZipArchive &gt; PclZip ) - default</option>
				<option value="ziparchive">Compatibility: ZipArchive</option>
				<option value="pclzip">Compatibility: PclZip</option>
			</select>
			<br><br>
			
			<input type="checkbox" name="migrateHtaccess" value="1" CHECKED> Migrate .htaccess file URLs & paths (if URL changes).<br>
			<input type="checkbox" name="skipUnzip" value="1"> Skip unzipping or opening archive (for manual extraction).
			<br><br>

			
			
		</div>
	</div>
</div>






<?php
echo '</form>';
?>



</div><!-- end wrapper div -->




<div id="pb_upload_modal" style="display: none;">
	<div class="modal">
		<div class="modal_header">
			<a class="modal_close">&times;</a>
			<h2>Upload Backup</h2>
			Smaller backups can be uploaded here directly from your computer. Larger backups work best over FTP or other methods.
		</div>
		<div class="modal_content">
			
			
			<form enctype="multipart/form-data" action="" method="POST">
				<input type="hidden" name="pass_hash" value="<?php echo PB_PASSWORD; ?>">
				<input type="hidden" name="upload" value="local">
				<input type="hidden" name="options" value="<?php echo htmlspecialchars( serialize( pb_backupbuddy::$options ) ); ?>'">
				Choose a local backup to upload:<br>
				<p>
					<input name="file" type="file" style="width: 100%;">
				</p>
				<br>
				<input type="submit" value="Upload Backup" class="toggle button">
			</form>
			
			
		</div>
	</div>
</div>




<div id="pb_stash_modal" style="display: none;">
	<div class="modal">
		<div class="modal_header">
			<a class="modal_close">&times;</a>
			<h2>Restore Backup from Stash</h2>
			Backups stored in your iThemes Stash may be retrieved for restoring here.
		</div>
		<div class="modal_content">
			
			
			<?php require( '_html_1_stash.php' ); ?>
			
			
		</div>
	</div>
</div>