<?php
$blog_id = isset( $_POST[ 'blog_id' ] ) ? absint( $_POST[ 'blog_id' ] ) : 0;
switch_to_blog( $blog_id );


echo $this->status_box( 'Migrating files (media, plugins, themes, etc) . . .' );
echo '<div id="pb_importbuddy_working" style="width: 100px;"><center><img src="' . pb_backupbuddy::plugin_url() . '/images/working.gif" title="Working... Please wait as this may take a moment..."></center></div>';
pb_backupbuddy::flush();

	
// VERIFY THIS IS NOT A NETWORK BACKUP SINCE WE CANNOT IMPORT A NETWORK INTO A NETWORK. Not doing this on previous page due to time limitations.
$this->load_backup_dat();
if ( isset( $this->_backupdata[ 'is_multisite' ] ) && ( ( $this->_backupdata[ 'is_multisite' ] === true ) || $this->_backupdata[ 'is_multisite' ] === 'true' ) ) {
	$error_9003 = 'The backup is of a full Multisite Network. You cannot import a Network into a Network. You may only import standalone sites and sites exported from a Multisite.';
	$this->status( 'error', 'ERROR #9023: ' . $error_9003 );
	pb_backupbuddy::alert( $error_9003, true, '9003' );
	$this->status( 'error', 'Unable to continue. Import halted.' );
	echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
	pb_backupbuddy::flush();
} else { // Not a network being imported.
	
	// Set up destination upload path and URL information. We must pass these on in form since importing database will overwrite these.
	$wp_upload_url = $this->get_ms_option( $blog_id, 'fileupload_url' ); // Ronalds: $site_uploads[ 'baseurl' ];
	if ( $wp_upload_url == '' ) {
		$wp_upload_array = wp_upload_dir();
		//pb_backupbuddy::status( 'details', 'wp_upload_dir response: `' . print_r( $wp_upload_array, true ) . '`.' );
		$wp_upload_url = $wp_upload_array['baseurl'];
		$wp_upload_dir = $wp_upload_array['basedir'];
		pb_backupbuddy::status( 'details', 'Upload URL option not found. Using new method to determine both URL and path. (WP 3.5+). Baseurl: `' . $wp_upload_url . '`. Basepath: `' . $wp_upload_dir . '`.' );
	} else {
		$wp_upload_dir = ABSPATH . $this->get_ms_option( $blog_id, 'upload_path' ); // Ronalds: $site_uploads[ 'basedir' ];
	}
	
	$wp_upload_dir = rtrim( $wp_upload_dir, "\\/" ); // Trim trailing slash if there (shouldnt be by default but someone could have manually edited it)
	pb_backupbuddy::status( 'details', 'Destination site uploads real file path: ' . $wp_upload_dir );
	$wp_upload_url = rtrim( $wp_upload_url, "\\/" ); // Trim trailing slash if there (shouldnt be by default but someone could have manually edited it)
	pb_backupbuddy::status( 'details', 'Destination site virtual uploads URL (URL; no trailing slash): ' . $wp_upload_url );
	
	$migrate_items = array();
	
	
	// ********** BEGIN MEDIA FILES **********
	pb_backupbuddy::status( 'message', 'Migrating media files . . .' );
	// Iterate through the temporary directory and get a list of files/directories to copy over.
	$source_uploads_dir = $this->import_options[ 'extract_to' ] . '/wp-content/uploads';
	if ( is_dir( $source_uploads_dir ) ) {
		$root_dir = @ opendir( $source_uploads_dir );
		while (($file = readdir( $root_dir ) ) !== false ) { // Loop through all files in directory.
			if ( substr( $file, 0, 11 ) != 'backupbuddy' && $file != '.' && $file != '..' ) { // Ignore ., .., and any directories starting with backupbuddy (temps, etc).
				$migrate_items[] = $source_uploads_dir . '/' . $file;
			}
		}
	}
	$this->status( 'details', 'Migrating media: ' . implode( ', ', $migrate_items ) );
	
	// Creating media directory.
	pb_backupbuddy::status( 'details', 'Creating uploads directory `' . $wp_upload_dir . '`.' );
	if ( true !== pb_backupbuddy::$filesystem->mkdir( $wp_upload_dir ) ) {
		pb_backupbuddy::status( 'error', 'Unable to create uploads directory. Verify file permissions.' );
	}
	
	// Copy files…
	$this->status( 'message', 'Copying media into destination media directory: ' . $wp_upload_dir );
	foreach ( $migrate_items as $filename ) {
		pb_backupbuddy::$filesystem->recursive_copy( $filename, $wp_upload_dir . '/' . basename( $filename ) ); 
	}
	$this->status( 'message', 'Media files migrated.' );
	// ********** END MEDIA FILES **********
	
	
	// ********** BEGIN PLUGIN FILES **********
	$this->status( 'message', 'Migrating plugin files . . .' );
	$source_plugins_dir = $this->import_options[ 'extract_to' ] . '/wp-content/plugins';
	if ( is_dir( $source_plugins_dir ) ) {
		$migrate_items = array();
		$root_dir = @ opendir( $source_plugins_dir );
		while (($file = readdir( $root_dir ) ) !== false ) { // Loop through all files in directory.
			if ( substr( $file, 0, 11 ) != 'backupbuddy' && $file != '.' && $file != '..' ) { // Ignore ., .., and any directories starting with backupbuddy (temps, etc).
				$migrate_items[] = $source_plugins_dir . '/' . $file;
			}
		}
	}
	$this->status( 'details', 'Migrating plugins: ' . implode( ', ', $migrate_items ) );
	
	// Copy files…
	$destination_plugins_dir = dirname( pb_backupbuddy::plugin_path() );
	$this->status( 'message', 'Copying plugins into destination plugin directory: ' . $destination_plugins_dir );
	foreach ( $migrate_items as $filename ) {
		if ( file_exists( $destination_plugins_dir . '/' . basename( $filename ) ) ) continue; // If plugin already exists skip it.
		pb_backupbuddy::$filesystem->recursive_copy( $filename, $destination_plugins_dir . '/' . basename( $filename ) );
	}
	$this->status( 'message', 'Plugin files migrated . . .' );
	// ********** END PLUGIN FILES **********
	
	
	// ********** BEGIN THEME FILES **********
	$this->status( 'message', 'Migrating theme files . . .' );
	$source_themes_dir = $this->import_options[ 'extract_to' ] . '/wp-content/themes';
	if ( is_dir( $source_themes_dir ) ) {
		$migrate_items = array();
		$root_dir = @ opendir( $source_themes_dir );
		while (($file = readdir( $root_dir ) ) !== false ) {
			if ( substr( $file, 0, 11 ) != 'backupbuddy' && $file != '.' && $file != '..' ) {
				$migrate_items[] = $source_themes_dir . '/' . $file;
			}
		}
	}
	$this->status( 'details', 'Migrating themes: ' . implode( ', ', $migrate_items ) );
	
	// Copy files...
	$destination_themes_dir = WP_CONTENT_DIR . '/themes';
	$this->status( 'message', 'Copying themes into destination theme directory: ' . $destination_themes_dir );
	foreach ( $migrate_items as $filename ) {
		if ( file_exists( $destination_themes_dir . '/' . basename( $filename ) ) ) continue; // If theme already exists skip it.
		pb_backupbuddy::$filesystem->recursive_copy( $filename, $destination_themes_dir . '/' . basename( $filename ) );
	}
	global $current_site;
		$errors = false;	
		$blog = $domain = $path = '';
		$form_url = add_query_arg( array(
			'step' => '5',
			'action' => 'step5'
		) , pb_backupbuddy::page_url()  );
	$this->status( 'message', 'Theme files migrated . . .' );
	// ********** END THEME FILES **********
	
	$this->status( 'message', 'Files migrated.' );
	echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
	pb_backupbuddy::flush();
	
	$this->status( 'message', __('Files, such as media, plugins, and themes, were successfully migrated.', 'it-l10n-backupbuddy' ) );
	?>
	
	<form method="post" action="<?php echo esc_url( $form_url ); ?>">
	<?php wp_nonce_field( 'bbms-migration', 'pb_bbms_migrate' ); ?>
	<input type='hidden' name='backup_file' value='<?php echo esc_attr( $_POST[ 'backup_file' ] ); ?>' />
	<input type='hidden' name='blog_id' value='<?php echo esc_attr( absint( $_POST[ 'blog_id' ] ) ); ?>' />
	<input type='hidden' name='blog_path' value='<?php echo esc_attr( $_POST[ 'blog_path' ] ); ?>' />
	<input type='hidden' name='upload_path' value='<?php echo esc_attr( $wp_upload_dir ); ?>' />
	<input type='hidden' name='global_options' value='<?php echo base64_encode( serialize( $this->advanced_options ) ); ?>' />
	<input type='hidden' name='fileupload_url' value='<?php echo esc_attr( $wp_upload_url ); ?>' />
	<?php submit_button( __('Next Step') . ' &raquo;', 'primary', 'add-site' ); ?>
	</form>
<?php } // end if not multisite network backup type ?>
