<?php
backupbuddy_core::verifyAjaxAccess();


// Display file listing of zip.
/*	file_tree()
*	
*	File tree for viewing zip contents.
*	
*	@return		null
*/

// How long to cache the specific backup file tree information for (seconds)
$max_cache_time = 86400;

// This is the root directory we want the listing for
$root = trim( urldecode( pb_backupbuddy::_POST( 'dir' ) ) );
$root_len = strlen( $root );

// This will identify the backup zip file we want to list
$serial = pb_backupbuddy::_GET( 'serial' );

// The fileoptions file that contains the file tree information		
require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
$fileoptions_file = backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '-filetree.txt';

// Purge cache if too old.
if ( file_exists( $fileoptions_file ) && ( ( time() - filemtime( $fileoptions_file ) ) > $max_cache_time ) ) {
	if ( false === unlink( $fileoptions_file ) ) {
		pb_backupbuddy::alert( 'Error #456765545. Unable to wipe cached fileoptions file `' . $fileoptions_file . '`.' );
	}
}

pb_backupbuddy::status( 'details', 'Fileoptions instance #28.' );
$fileoptions = new pb_backupbuddy_fileoptions( $fileoptions_file );

// Either we are getting cached file tree information or we need to create afresh		
if ( true !== ( $result = $fileoptions->is_ok() ) ) {
	// Get file listing.
	require_once( pb_backupbuddy::plugin_path() . '/lib/zipbuddy/zipbuddy.php' );
	pb_backupbuddy::$classes['zipbuddy'] = new pluginbuddy_zipbuddy( ABSPATH, array(), 'unzip' );
	$files = pb_backupbuddy::$classes['zipbuddy']->get_file_list( backupbuddy_core::getBackupDirectory() . str_replace( '\\/', '', pb_backupbuddy::_GET( 'zip_viewer' ) ) );
	$fileoptions->options = $files;
	$fileoptions->save();
} else {
	$files = &$fileoptions->options;
}

// Just make sure we have a sensible files listing
if ( ! is_array( $files ) ) {
	die( 'Error #548484.  Unable to retrieve file listing from backup file `' . htmlentities( pb_backupbuddy::_GET( 'zip_viewer' ) ) . '`.' );
}

// To record subdirs of this root
$subdirs = array();

// Strip out any files/subdirs that are not actually directly under the given root
foreach( $files as $key => $file ) {
	
	// If shorter than root length then certainly is not within this (root) directory.
	// It's a quick test that is more effective the longer the root (the deeper you go
	// into the tree)
	if ( strlen( $file[ 0 ] ) < $root_len ) {
	
		unset( $files[ $key ] );
		continue;
		
	}
	
	// The root must be prefix of this file	otherwise it's not under the root
	// e.g., with root=this/dir/path/
	// these will fail: file=this/dir/file; file=this/dir/otherpath/; file=that/dir/path/file
	// and these would succeed: file=this/dir/path/; file=this/dir/path/file; file=this/dir/path/otherpath/
	if ( substr( $file[ 0 ], 0, $root_len ) != $root ) {
	
		unset( $files[ $key ] );
		continue;
		
	}
	
	// If the file _is_ the root then we don't want to list it
	// Don't want to do this on _every_ file as very specific so do it here after we have
	// weeded out files for more common reasons
	if ( 0 == strcmp( $file[ 0 ], $root ) ) {
	
		unset( $files[ $key ] );
		continue;				
	
	}
	
	// Interesting file, get the path with the root prefix removed
	// Note: root may be empty in which case the result will be the original filename
	$unrooted_file = substr( $file[ 0 ], $root_len );
	
	// We must ensure that we list the subdir/ even if subdir/ does not appear
	// as a distinct entry in the list but only subdir/file or subdir/subsubdir/ or
	// subdir/subsubdir/file. Find if we have any directory separator(s) in the filename
	// and if so remember where the first is
	if ( false !== ( $pos = strpos( $unrooted_file, '/' ) ) ) {
		
		// Get the subdir/ prefix part, discarding everything after the first /
		$subdir = substr( $unrooted_file, 0, ( $pos + 1 ) );
		
		// Have we already seen it
		if ( !in_array( $subdir, $subdirs ) ) {
		
			// Not already seen so record we have seen it and modify this entry to be
			// specific for the subdir/
			$subdirs[] = $subdir;
			
			// Replace the original (rooted) file name
			$files[ $key ][ 0 ] = $subdir;
			
		} else {
		
			// We already know about the subdir/ so remove this entry
			unset( $files[ $key ] );
			continue;
		
		}
	
	} else {
	
		// This is just like file within the root
		// Replace the original (rooted) file name
		$files[ $key ][ 0 ] = $unrooted_file;
	
	}
	
}

// Simple sort function to bubble dirs up to the top of list and
// have dirs and files in simple alpha order
function pb_backupbuddy_sort_file_list( $a, $b ) {

	// If both are dirs or files then result is 0
	// If $a is dir and $b is file then result is -1
	// If $a is file and $b is dir then result is 1
	if ( 0 == ( $res = substr_count( $b[0], '/' ) - substr_count( $a[0], '/' ) ) ) {
	
		// Both same type so sort alpha
		$res = strcmp( rtrim( $a[0], '/' ), rtrim( $b[0], '/') );
	
	}
	
	return $res;
	
}

// Try and sort the files to put dirs first and all in alpha
// Remember original in case the sort fails
$saved_files = $files;
if ( false === usort( $files, 'pb_backupbuddy_sort_file_list' ) ) {

	// Hmm, the sort failed, just revert to original
	$files = $saved_files;
}

// Now we can start to build the listing to display
if( count( $files ) > 0 ) {
	echo '<ul class="jqueryFileTree" style="display: none;">';
	
	// Files which are considered text-based and therefore contents viewable to the user.
	$view_ext = array(
		'php',
		'htaccess',
		'htm',
		'html',
		'txt',
		'css',
		'ini',
		'sql',
	);
	
	foreach( $files as $file ) {
		if ( substr( $file[0], -1 ) == '/' ) { // Directory.
			echo '<li class="directory collapsed">';
			$return = '';
			echo '<input type="checkbox">';
			echo '<a class="hoverable" href="#" rel="' . htmlentities( $root . $file[0] ) . '" title="Toggle expand...">' . htmlentities( rtrim( $file[0], '/' ) ) . $return . '</a>';
			echo '</li>';
		} else { // File.
			
			$actions = array();
			$ext = pathinfo( htmlentities( $file[0] ), PATHINFO_EXTENSION );
			
			$viewable = false;
			if ( in_array( $ext, $view_ext ) ) {
				$viewable = true;
			}
			
			echo '<li class="file collapsed ext_' . $ext;
			if ( true === $viewable ) {
				echo ' viewable';
			}
			echo '"><input type="checkbox">';
			if ( true === $viewable ) {
				echo '<a onclick="modal_live(\'restore_file_view\',jQuery(this));" class="hoverable" rel="' . htmlentities( $root . $file[0] ) . '">';
			} else {
				echo '<a href="#" rel="' . htmlentities( $root . $file[0] ) . '">';
			}
			echo htmlentities( $file[0] );
			
			if ( true === $viewable ) {
				echo '<span class="viewlink_place"><img src="' . pb_backupbuddy::plugin_url() . '/images/eyecon.png"></span>';
				echo '<span class="viewlink"><img src="' . pb_backupbuddy::plugin_url() . '/images/eyecon.png"> View</span>';
			}
			
			echo '<span class="pb_backupbuddy_fileinfo">';
			echo '	<span class="pb_backupbuddy_col1">' . pb_backupbuddy::$format->file_size( $file[1] ) . '</span>';
			echo '	<span class="pb_backupbuddy_col2">' . pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $file[3] ) ) . ' <span class="description">(' . pb_backupbuddy::$format->time_ago( $file[3] ) . ' ago)</span></span>';
			echo '</span>';
			
			echo '</a></li>';
		}
	}
	echo '</ul>';
} else {
	echo '<ul class="jqueryFileTree" style="display: none;">';
	echo '<li><a href="#" rel="' . htmlentities( pb_backupbuddy::_POST( 'dir' ) . 'NONE' ) . '"><i>Empty Directory ...</i></a></li>';
	echo '</ul>';
}

