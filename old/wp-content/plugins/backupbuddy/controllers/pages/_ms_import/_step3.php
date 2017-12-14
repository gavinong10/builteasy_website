<?php
//$this->_parent->set_greedy_script_limits();

echo $this->status_box( 'Extracting files . . .' );
echo '<div id="pb_importbuddy_working" style="width: 100px;"><center><img src="' . pb_backupbuddy::plugin_url() . '/images/working.gif" title="Working... Please wait as this may take a moment..."></center></div>';
pb_backupbuddy::flush();

$backup_archive = $this->import_options['file']; // Full path to file.
$destination_directory = $this->import_options['extract_to'] . '/';

$this->status( 'message', 'Unzipping `' . $backup_archive . '` into `' . $destination_directory . '`' );

if ( isset( $this->advanced_options['skip_files'] ) && ( $this->advanced_options['skip_files'] == 'true' ) ) {
	$this->status( 'message', 'Skipping file extraction based on advanced settings.' );
	$result = true;
} else {
	// Set compatibility mode if defined in advanced options.
	$compatibility_mode = false; // Default to no compatibility mode.
	if ( isset( $this->advanced_options['force_compatibility_medium'] ) && ( $this->advanced_options['force_compatibility_medium'] == "true" ) ) {
		$compatibility_mode = 'ziparchive';
	} elseif ( isset( $this->advanced_options['force_compatibility_slow'] ) && ( $this->advanced_options['force_compatibility_slow'] == "true" ) ) {
		$compatibility_mode = 'pclzip';
	}
	
	// Zip & Unzip library setup.
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	if ( !isset( pb_backupbuddy::$classes['zipbuddy'] ) ) {
		pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( ABSPATH, array(), 'unzip' );
	}
	$result = pb_backupbuddy::$classes['zipbuddy']->unzip( $backup_archive, $destination_directory, $compatibility_mode );
}
echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
flush();

// Extract zip file & verify it worked.
if ( true !== $result ) {
	$this->status( 'error', 'Failed unzipping archive.' );
	pb_backupbuddy::alert( 'Failed unzipping archive.' );
	$failed = true;
} else { // Reported success; verify extraction.
	/* Breaks MS import if wp-config was in a parent directory.
	if ( ! file_exists( $destination_directory . 'wp-config.php' ) ) {
		$this->status( 'error', 'Error #9004: Key files missing. The unzip process reported success but `' . $destination_directory . 'wp-config.php' . '` was not found in the extracted files. Verify that this is a FULL backup. If so then the unzip process either failed or the zip file is not a proper BackupBuddy backup.' );
		return false;
	}
	*/
	$this->status( 'details', 'Success extracting Zip File "' . ABSPATH . $this->import_options['file'] . '" into "' . ABSPATH . '".' );
	$failed = false;
}

if ( true === $result ) {
	global $current_site;
	$errors = false;	
	$blog = $domain = $path = '';
	$form_url = add_query_arg( array(
		'step' => '4',
		'action' => 'step4'
	) , pb_backupbuddy::page_url() );
	
	?>
	<form method="post" action="<?php echo esc_url( $form_url ); ?>">
	<?php wp_nonce_field( 'bbms-migration', 'pb_bbms_migrate' ); ?>
	<table class="form-table">
		<tr class="form-field form-required">
			<td>
			<p><?php _e( 'Your files have been extracted to a temporary directory.  Proceed to the next step to copy your media, plugins, and themes into the new site.', 'it-l10n-backupbuddy' ); ?></p>
			</td>
		</tr>
	</table>
	<input type='hidden' name='backup_file' value='<?php echo esc_attr( $_POST[ 'backup_file' ] ); ?>' />
	<input type='hidden' name='blog_id' value='<?php echo esc_attr( absint( $_POST[ 'blog_id' ] ) ); ?>' />
	<input type='hidden' name='blog_path' value='<?php echo esc_attr( $_POST[ 'blog_path' ] ); ?>' />
	<input type='hidden' name='global_options' value='<?php echo base64_encode( serialize( $this->advanced_options ) ); ?>' />
	<?php submit_button( __('Next Step') . ' &raquo;', 'primary', 'add-site' ); ?>
	</form>
	<?php
}
?>