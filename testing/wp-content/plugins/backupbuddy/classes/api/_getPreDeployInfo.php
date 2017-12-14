<?php
$sha1 = false; // Whether to calculate sha1 hash for determining file differences.



$upload_max_filesize = str_ireplace( 'M', '', @ini_get( 'upload_max_filesize' ) );
if ( ( ! is_numeric( $upload_max_filesize ) ) || ( 0 == $upload_max_filesize ) ) {
	$upload_max_filesize = 1;
}

$max_execution_time = str_ireplace( 's', '', @ini_get( 'max_execution_time' ) );
if ( ( ! is_numeric( $max_execution_time ) ) || ( 0 == $max_execution_time ) ) {
	$max_execution_time = 30;
}

$memory_limit = str_ireplace( 'M', '', @ini_get( 'memory_limit' ) );
if ( ( ! is_numeric( $memory_limit ) ) || ( 0 == $memory_limit ) ) {
	$memory_limit = 32;
}

$max_post_size = str_ireplace( 'M', '', @ini_get( 'post_max_size' ) );
if ( ( ! is_numeric( $max_post_size ) ) || ( 0 == $max_post_size ) ) {
	$max_post_size = 8;
}

$dbTables = array();
global $wpdb;
$rows = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
foreach( $rows as $row ) {
	
	// Hide BackupBuddy temp tables.
	if ( 'bbold-' == substr( $row['Name'], 0, 6 ) ) {
		continue;
	}
	if ( 'bbnew-' == substr( $row['Name'], 0, 6 ) ) {
		continue;
	}
	
	$dbTables[] = $row['Name'];
}






/* backupbuddy_dbMediasince()
 *
 * Generate list of media files with modified times. Optionally include thumbnail media files (default).
 *
 * @return	array 			Array of media files arrays. Eg array( 'filename.jpg' => array( 'modified' => 1111111111
 *
 */
function backupbuddy_dbMediaSince( $includeThumbs = true ) {
	global $wpdb;
	$wpdb->show_errors(); // Turn on error display.
	
	$mediaFiles = array();
	
	// Select all media attachments.
	$sql = "select " . $wpdb->prefix . "postmeta.meta_value as file," . $wpdb->prefix . "posts.post_modified as file_modified," . $wpdb->prefix . "postmeta.meta_key as meta_key from " . $wpdb->prefix . "postmeta," . $wpdb->prefix . "posts WHERE ( meta_key='_wp_attached_file' OR meta_key='_wp_attachment_metadata' ) AND " . $wpdb->prefix . "postmeta.post_id = " . $wpdb->prefix . "posts.id ORDER BY meta_key ASC";
	$results = $wpdb->get_results( $sql, ARRAY_A );
	if ( ( null === $results ) || ( false === $results ) ) {
		pb_backupbuddy::status( 'error', 'Error #238933: Unable to calculate media with query `' . $sql . '`. Check database permissions or contact host.' );
	}
	
	foreach( (array)$results as $result ) {
		
		if ( $result['meta_key'] == '_wp_attached_file' ) {
			$mediaFiles[ $result['file'] ] = array(
				'modified'	=> $result['file_modified']
			);
		}
		
		// Include thumbnail image files.
		if ( true === $includeThumbs ) {
			if ( $result['meta_key'] == '_wp_attachment_metadata' ) {
				$data = unserialize( $result['file'] );
				foreach( $data['sizes'] as $size ) { // Go through each sized thumbnail file.
					$mediaFiles[ $size['file'] ] = array(
						'modified'	=> $mediaFiles[ $data['file'] ]['modified']
					);
				}
			}
		}
		
	} // end foreach $results.
	unset( $results );
	return $mediaFiles;
	
} // End backupbuddy_dbMediaSince().


// Get list of active plugins and remove BackupBuddy from it so we don't update any BackupBuddy files when deploying. Could cause issues with the API replacing files mid-deploy.
$activePlugins = backupbuddy_api::getActivePlugins();
foreach( $activePlugins as $activePluginIndex => $activePlugin ) {
	if ( false !== strpos( $activePlugin['name'], 'BackupBuddy' ) ) {
		unset( $activePlugins[ $activePluginIndex ] );
	}
}
$activePluginDirs = array();
foreach( $activePlugins as $activePluginDir => $activePlugin ) {
	$activePluginDirs[] = dirname( WP_PLUGIN_DIR . '/' . $activePluginDir );
}
$allPluginDirs = glob( WP_PLUGIN_DIR . '/*', GLOB_ONLYDIR );
$inactivePluginDirs = array_diff( $allPluginDirs, $activePluginDirs ); // Remove active plugins from directories of all plugins to get directories of inactive plugins to exclude later.
$inactivePluginDirs[] = pb_backupbuddy::plugin_path(); // Also exclude BackupBuddy directory.


// Calculate media files signatures.
$upload_dir = wp_upload_dir();
$mediaExcludes = array(
	'/backupbuddy_backups',
	'/pb_backupbuddy',
	'/backupbuddy_temp',
);
$mediaSignatures = backupbuddy_core::hashGlob( $upload_dir['basedir'], $sha1, $mediaExcludes, $handle_utf8 = true );


// Calculate child theme file signatures, excluding main theme directory..
if ( get_stylesheet_directory() == get_template_directory() ) { // Theme & childtheme are same so do not send any childtheme files!
	$childThemeSignatures = array();
} else {
	$childThemeSignatures = backupbuddy_core::hashGlob( get_stylesheet_directory(), $sha1 );
}


global $wp_version;

return array(
	'backupbuddyVersion'		=> pb_backupbuddy::settings( 'version' ),
	'wordpressVersion'			=> $wp_version,
	'localTime'					=> time(),
	'php'						=> array(
									'upload_max_filesize' => $upload_max_filesize,
									'max_execution_time' => $max_execution_time,
									'memory_limit' => $memory_limit,
									'max_post_size' => $max_post_size,
									),
	'abspath'					=> ABSPATH,
	'siteurl'					=> site_url(),
	'homeurl'					=> home_url(),
	'tables'					=> $dbTables,
	'dbPrefix'					=> $wpdb->prefix,
	'activePlugins'				=> $activePlugins,
	'activeTheme'				=> get_template(),
	'activeChildTheme'			=> get_stylesheet(),
	'themeSignatures'			=> backupbuddy_core::hashGlob( get_template_directory(), $sha1 ),
	'childThemeSignatures'		=> $childThemeSignatures,
	'pluginSignatures'			=> backupbuddy_core::hashGlob( WP_PLUGIN_DIR, $sha1, $inactivePluginDirs ),
	'mediaSignatures'			=> $mediaSignatures,
	'mediaCount'				=> count( $mediaSignatures ),
	'notifications'				=> array(), // Array of string notification messages.
);

