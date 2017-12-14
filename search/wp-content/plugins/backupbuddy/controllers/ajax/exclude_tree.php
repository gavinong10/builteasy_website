<?php
backupbuddy_core::verifyAjaxAccess();


// Directory exclusions picker for settings page.

/*	exclude_tree()
 *	
 *	Directory exclusion tree for settings page.
 *	
 *	@return		null
 */

$root = ABSPATH . urldecode( pb_backupbuddy::_POST( 'dir' ) );

if( file_exists( $root ) ) {
	$files = scandir( $root );
	
	natcasesort( $files );
	
	// Sort with directories first.
	$sorted_files = array(); // Temporary holder for sorting files.
	$sorted_directories = array(); // Temporary holder for sorting directories.
	foreach( $files as $file ) {
		if ( ( $file == '.' ) || ( $file == '..' ) ) {
			continue;
		}
		if( is_file( str_replace( '//', '/', $root . $file ) ) ) {
			array_push( $sorted_files, $file );
		} else {
			array_unshift( $sorted_directories, $file );
		}
	}
	$files = array_merge( array_reverse( $sorted_directories ), $sorted_files );
	unset( $sorted_files );
	unset( $sorted_directories );
	unset( $file );
	
	
	if( count( $files ) > 0 ) { // Files found.
		echo '<ul class="jqueryFileTree" style="display: none;">';
		foreach( $files as $file ) {
			if( file_exists( str_replace( '//', '/', $root . $file ) ) ) {
				if ( is_dir( str_replace( '//', '/', $root . $file ) ) ) { // Directory.
					echo '<li class="directory collapsed">';
					$return = '';
					$return .= '<div class="pb_backupbuddy_treeselect_control">';
					$return .= '<img src="' . pb_backupbuddy::plugin_url() . '/images/redminus.png" style="vertical-align: -3px;" title="Add to exclusions..." class="pb_backupbuddy_filetree_exclude">';
					$return .= '</div>';
					echo '<a href="#" rel="' . htmlentities( str_replace( ABSPATH, '', $root ) . $file) . '/" title="Toggle expand...">' . htmlentities($file) . $return . '</a>';
					echo '</li>';
				} else { // File.
					echo '<li class="file collapsed">';
					$return = '';
					$return .= '<div class="pb_backupbuddy_treeselect_control">';
					$return .= '<img src="' . pb_backupbuddy::plugin_url() . '/images/redminus.png" style="vertical-align: -3px;" title="Add to exclusions..." class="pb_backupbuddy_filetree_exclude">';
					$return .= '</div>';
					echo '<a href="#" rel="' . htmlentities( str_replace( ABSPATH, '', $root ) . $file) . '">' . htmlentities($file) . $return . '</a>';
					echo '</li>';
				}
			}
		}
		echo '</ul>';
	} else {
		echo '<ul class="jqueryFileTree" style="display: none;">';
		echo '<li><a href="#" rel="' . htmlentities( pb_backupbuddy::_POST( 'dir' ) . 'NONE' ) . '"><i>Empty Directory ...</i></a></li>';
		echo '</ul>';
	}
} else {
	echo 'Error #1127555. Unable to read site root.';
}

die();
