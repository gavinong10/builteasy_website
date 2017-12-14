<?php

pb_backupbuddy::$ui->title( 'Multisite Import Site (EXPERIMENTAL)' . ' ' . pb_backupbuddy::video( '4RmC5nLmabE', __('Multisite import', 'it-l10n-backupbuddy' ), false ) );

backupbuddy_core::versions_confirm();

pb_backupbuddy::set_status_serial( 'ms_import' );

/*
// Used for drag & drop / collapsing boxes.
wp_enqueue_style('dashboard');
wp_print_styles('dashboard');
wp_enqueue_script('dashboard');
wp_print_scripts('dashboard');

wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );
// Handles resizing thickbox.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}
wp_enqueue_script( 'backupbuddy-ms-export', $this->_parent->_pluginURL . '/js/ms.js', array( 'jquery' ) );
wp_print_scripts( 'backupbuddy-ms-export' );
*/


$action = isset( $_GET[ 'action' ] ) ? $_GET[ 'action' ] : false;
?>
<div class='wrap'>
<p>For BackupBuddy Multisite documentation, please visit the <a href='http://ithemes.com/codex/page/BackupBuddy_Multisite'>BackupBuddy Multisite Codex</a>.</p>
<?php
//check_admin_referer( 'bbms-migration', 'pb_bbms_migrate' );
if ( !current_user_can( 'manage_sites' ) ) 
	wp_die( __( 'You do not have permission to access this page.','it-l10n-backupbuddy' ) );
//global $current_blog;
$errors = false;	
$blog = $domain = $path = '';

// ********** BEGIN IMPORT OPTIONS **********
$import_options = array(
	'zip_id' => '',
	'extract_to' => '',
		
	'show_php_warnings' => false,
	'type' => 'zip',
	'skip_files' => false,
	'force_compatibility_slow' => false,
	'force_compatibility_medium' => true,
	'skip_database_import' => false,
);

// Set backup file.
if ( isset( $_POST[ 'backup_file' ] ) ) {
	$import_options['file'] = $_POST[ 'backup_file' ];
}
// ********** END IMPORT OPTIONS **********


$pluginbuddy_ms_import = new pluginbuddy_ms_import( $action, $import_options );

class pluginbuddy_ms_import {
	var $import_options;
	
	public function __construct( $action, $import_options ) {
		$this->import_options = $import_options;
		if ( ( $this->import_options['zip_id'] == '' ) && ( isset( $this->import_options['file'] ) ) ) {
			$this->import_options['zip_id'] = $this->get_zip_id( basename( $this->import_options['file'] ) );
		}
		
		// Detect max execution time for database steps so they can pause when needed for additional PHP processes.
		$this->detected_max_execution_time = str_ireplace( 's', '', ini_get( 'max_execution_time' ) );
		if ( is_numeric( $this->detected_max_execution_time ) === false ) {
			$this->detected_max_execution_time = 30;
		}
		
		
		// Set advanced options if they have been passed along.
		if ( isset( $_POST['global_options'] ) && ( $_POST['global_options'] != '' ) ) {
			$this->advanced_options = unserialize( base64_decode( $_POST['global_options'] ) );
		}
		
		$this->time_start = microtime( true );
		
		// Temporarily unzips into the main sites uploads temp 
		$wp_uploads = wp_upload_dir();
		$this->import_options[ 'extract_to' ] = $wp_uploads[ 'basedir' ] . '/backupbuddy_temp/import_' . $this->import_options[ 'zip_id' ];
		
		global $current_blog;
		$import_steps = 8;
		
		switch( $action ) {
			case 'step2':
				echo '<h3>Step 2 of ' . $import_steps . ': Create Site</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step2.php' );
				break;
			case 'step3':
				echo '<h3>Step 3 of ' . $import_steps . ': Unzipping Backup File</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step3.php' );
				break;
			case 'step4':
				echo '<h3>Step 4 of ' . $import_steps . ': Migrating Files (Media, Plugins, Themes, and more)</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step4.php' );
				break;
			case 'step5':
				echo '<h3>Step 5 of ' . $import_steps . ': Importing Database Content</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step5.php' );
				break;
			case 'step6':
				echo '<h3>Step 6 of ' . $import_steps . ': Migrating Database Content (URLs, Paths, and more)</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step6.php' );
				break;
			case 'step7':
				echo '<h3>Step 7 of ' . $import_steps . ': Migrating Users & Accounts</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step7.php' );
				break;
			case 'step8':
				echo '<h3>Step 8 of ' . $import_steps . ': Final Cleanup</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step8.php' );
				break;
			default:
				//require( pb_backupbuddy::plugin_path() . '/classes/' . 'msimport_steps/step1.php' );
				echo '<h3>Step 1 of ' . $import_steps . ': Select Backup & Site Address</h3>';
				require( pb_backupbuddy::plugin_path() . '/controllers/pages/_ms_import/_step1.php' );
				break;
		} //end switch
	}
	
	function load_backup_dat() {
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
		
		$dat_file = $this->import_options[ 'extract_to' ] . '/' . str_replace( ABSPATH, '', backupbuddy_core::getTempDirectory() ) . $this->import_options[ 'zip_id' ] . '/backupbuddy_dat.php';
		$this->_backupdata = $this->get_backup_dat( $dat_file );
	}
	
	
	function get_backup_dat( $dat_file ) {
		require_once( pb_backupbuddy::plugin_path() . '/classes/import.php' );
		$import = new pb_backupbuddy_import();
		
		return backupbuddy_core::get_dat_file_array( $dat_file );
	}
	
	
	function get_ms_option( $blogID, $option_name ) {
		global $wpdb;
		
		$sql = "SELECT option_value FROM `" . DB_NAME . "`.`" . $wpdb->get_blog_prefix( $blogID ) . "options` WHERE `option_name` = %s";
		$query = $wpdb->prepare( $sql, $option_name );
		$option_value = $wpdb->get_var( $query );
		return $option_value;
	}
	
	
	/**
	 *	array_remove()
	 *
	 *	Removes array values in $remove from $array.
	 *
	 *	@param			$array		array		Source array. This will have values removed and be returned.
	 *	@param			$remove		array		Array of values to search for in $array and remove.
	 *	@return						array		Returns array $array stripped of all values found in $remove
	 */
	function array_remove( $array, $remove ) {
		if ( !is_array( $remove ) ) {
			$remove = array( $remove );
		}
		return array_values( array_diff( $array, $remove ) );
	}
	
	
	/**
	 *	status_box()
	 *
	 *	Displays a textarea for placing status text into.
	 *
	 *	@param			$default_text	string		First line of text to display.
	 *	@return							string		HTML for textarea.
	 */
	function status_box( $default_text = '' ) {
		return '<textarea readonly="readonly" style="width: 100%;" rows="10" cols="75" id="importbuddy_status" wrap="off">' . $default_text . '</textarea><br><br>';
	}		
	
	
	/**
	 *	status()
	 *
	 *	Write a status line into an existing textarea created with the status_box() function.
	 *
	 *	@param			$type		string		message, details, error, or warning. Currently not in use.
	 *	@param			$message	string		Message to append to the status box.
	 *	@return			null
	 */
	function status( $type, $message ) {
		pb_backupbuddy::status( $type, $message );
		
		$status_lines = pb_backupbuddy::get_status( 'ms_import', true, true, true ); // $serial = '', $clear_retrieved = true, $erase_retrieved = true, $hide_getting_status = false
		if ( $status_lines !== false ) { // Only add lines if there is status contents.
			foreach( $status_lines as $status_line ) {
				$status_line = json_decode( $status_line, true );
				$status_line['time'] = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $status_line['time'] ) );
				$status_line['run'] .= 'sec';
				$status_line['mem'] .= 'MB';
				echo '<script type="text/javascript">jQuery( "#importbuddy_status" ).append( "\n' . 
					$status_line['time'] . "\t" . $status_line['run'] . "\t" . $status_line['mem'] . "\t" . $status_line['event'] . "\t" . $status_line['data']
				 . '");	textareaelem = document.getElementById( "importbuddy_status" );	textareaelem.scrollTop = textareaelem.scrollHeight;	</script>';
				pb_backupbuddy::flush();
			}
		}
	}
	
	
	/**
	 *	get_zip_id()
	 *
	 *	Given a BackupBuddy ZIP file, extracts the random ZIP ID from the filename. This random string determines
	 *	where BackupBuddy will find the temporary directory in the backup's wp-uploads directory. IE a zip ID of
	 *	3poje9j34 will mean the temporary directory is wp-uploads/temp_3poje9j34/. backupbuddy_dat.php is in this
	 *	directory as well as the SQL dump.
	 *
	 *	Currently handles old BackupBuddy ZIP file format. Remove this backward compatibility at some point.
	 *
	 *	$file			string		BackupBuddy ZIP filename.
	 *	@return			string		ZIP ID characters.
	 *
	 */
	function get_zip_id( $file ) {
		$posa = strrpos($file,'_')+1;
		$posb = strrpos($file,'-')+1;
		if ( $posa < $posb ) {
			$zip_id = strrpos($file,'-')+1;
		} else {
			$zip_id = strrpos($file,'_')+1;
		}
		
		$zip_id = substr( $file, $zip_id, - 4 );
		return $zip_id;
	}
	
	
	
	/*	remove_file()
	 *	
	 *	Deletes a file.
	 *	
	 *	@param		string		$file				Full path to file to delete.
	 *	@param		string		$description		Description of file for logging.
	 *	@param		boolean		$error_on_missing	default false. Whether to log an error for a missing file.
	 *	@return		null
	 */
	function remove_file( $file, $description, $error_on_missing = false ) {
		$this->status( 'message', 'Deleting ' . $description . '...' );
	
		@chmod( $file, 0755 ); // High permissions to delete.
		
		if ( is_dir( $file ) ) { // directory.
			$this->remove_dir( $file );
			if ( file_exists( $file ) ) {
				$this->status( 'error', 'Unable to delete directory: `' . $description . '` named `' . $file . '`. You should manually delete it.' );
			} else {
				$this->status( 'message', 'Deleted `' . $file . '`.' );
			}
		} else { // file
			if ( file_exists( $file ) ) {
				if ( @unlink( $file ) != 1 ) {
					$this->status( 'error', 'Unable to delete file: `' . $description . '` named `' . $file . '`. You should manually delete it.' );
				} else {
					$this->status( 'message', 'Deleted `' . $file . '`.' );
				}
			} else {
				$this->status( 'message', 'File does not exist; nothing to delete.' );
			}
		}
	}
}


?>
</div><!-- .wrap-->