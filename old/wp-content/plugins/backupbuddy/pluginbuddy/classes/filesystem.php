<?php



/*	class pluginbuddy_filesystem
 *	@author Dustin Bolton
 *	
 *	Handles interfacing with the file system.
 */
class pb_backupbuddy_filesystem {
	
	
	
	// ********** PUBLIC PROPERTIES **********
	
	
	
	// ********** PRIVATE PROPERTIES **********
	
	
	
	// ********** FUNCTIONS **********
	
	
	
	/*	pluginbuddy_filesystem->__construct()
	 *	
	 *	Default constructor.
	 *	
	 *	@return		null
	 */
	function __construct() {
		
	} // End __construct().
	
	
	
	/*	pb_backupbuddy::$filesystem->mkdir()
	 *	
	 *	mkdir that defaults to recursive behaviour. 99% of the time this is what we want.
	 *	
	 *	@param		$pathname		string		Path to create.
	 *	@param		$mode			int			Default: 0777. See PHP's mkdir() function for details.
	 *	@param		$recursive		boolean		Default: true. See PHP's mkdir() function for details.
	 *	@return						boolean		Returns TRUE on success or FALSE on failure.
	 */
	public static function mkdir( $pathname, $mode = 0755, $recursive = true) {
		return @mkdir( $pathname, $mode, $recursive );
	} // End mkdir().
	
	
	
	/*	pluginbuddy_filesystem->unlink_recursive()
	 *	
	 *	Unlink a directory recursively. Files all files and directories within. USE WITH CAUTION.
	 *	
	 *	@param		string		$dir		Directory to delete -- all contents within, subdirectories, files, etc will be permanently deleted.
	 *	@return		boolean					True on success; else false.
	 */
	function unlink_recursive( $dir ) {
		if ( defined( 'PB_DEMO_MODE' ) ) {
			return false;
		}
		
		if ( !file_exists( $dir ) ) {
			return true;
		}
		if ( !is_dir( $dir ) || is_link( $dir ) ) {
			return unlink($dir);
		}
		foreach ( scandir( $dir ) as $item ) {
			if ( $item == '.' || $item == '..' ) {
				continue;
			}
			if ( !$this->unlink_recursive( $dir . "/" . $item ) ) {
				@chmod( $dir . "/" . $item, 0777 );
				if ( !$this->unlink_recursive( $dir . "/" . $item ) ) {
					return false;
				}
			}
		}
		return @rmdir($dir);
	} // End unlink_recursive().
	
	
	
	/**
	 *	pluginbuddy_filesystem->deepglob()
	 *
	 *	Like the glob() function except walks down into paths to create a full listing of all results in the directory and all subdirectories.
	 *	This is essentially a recursive glob() although it does not use recursion to perform this.
	 *
	 *	@param		string		$dir		Path to pass to glob and walk through.
	 *	@param		array 		$excludes	Array of directories to exclude, relative to the $dir.  Include beginning slash.
	 *	@return		array					Returns array of all matches found.
	 */
	function deepglob( $dir, $excludes = array() ) {
		$dir = rtrim( $dir, '/\\' ); // Make sure no trailing slash.
		$excludes = str_replace( $dir, '', $excludes );
		$dir_len = strlen( $dir );
		
		$items = glob( $dir . '/*' );
		if ( false === $items ) {
			$items = array();
		}
		
		for ( $i = 0; $i < count( $items ); $i++ ) {
			// If this file/directory begins with an exclusion then jump to next file/directory.
			foreach( $excludes as $exclude ) {
				if ( backupbuddy_core::startsWith( substr( $items[$i], $dir_len ), $exclude ) ) {
					unset( $items[$i] );
					continue 2;
				}
			}
			
			if ( is_dir( $items[$i] ) ) {
				$add = glob( $items[$i] . '/*' );
				if ( false === $add ) {
					$add = array();
				}
				$items = array_merge( $items, $add );
			}
		}
		
		return $items;
	} // End deepglob().
	
	
	
	function recursive_copy( $src, $dst ) {
		//pb_backupbuddy::status( 'details', 'Copying `' . $src . '` to `' . $dst . '`.' );
		if ( is_dir( $src ) ) {
			pb_backupbuddy::status( 'details', 'Copying directory `' . $src . '` to `' . $dst . '` recursively.' );
			@$this->mkdir( $dst, 0777 );
			$files = scandir($src);
			foreach ( $files as $file ) {
				if ($file != "." && $file != "..") {
					$this->recursive_copy("$src/$file", "$dst/$file");
				}
			}
		} elseif ( file_exists( $src ) ) {
			@copy( $src, $dst ); // Todo: should this need suppression? Media copying was throwing $dst is directory errors.
		}
	}
	
	
	// RH added; from Chris?
	/*
	
	public function custom_copy( $source, $destination, $args = array() ) {
		$default_args = array(
			'max_depth'    => 100,
			'folder_mode'  => 0755,
			'file_mode'    => 0744,
			'ignore_files' => array(),
		);
		$args = array_merge( $default_args, $args );
		
		return $this->_custom_copy( $source, $destination, $args );
	} // End custom_copy().
	
	
	
	private function _custom_copy( $source, $destination, $args, $depth = 0 ) {
		if ( $depth > $args['max_depth'] )
			return true;
			
		if ( in_array( basename( $source ), $args[ 'ignore_files' ] ) ) return true;
		
		if ( is_file( $source ) ) {
			if ( is_dir( $destination ) || preg_match( '|/$|', $destination ) ) {
				$destination = preg_replace( '|/+$|', '', $destination );
				
				$destination = "$destination/" . basename( $source );
			}
			
			if ( false === $this->mkdir( dirname( $destination ), $args['folder_mode'] ) )
				return false;
			
			if ( false === @copy( $source, $destination ) )
				return false;
			
			@chmod( $destination, $args['file_mode'] );
			
			return true;
		}
		else if ( is_dir( $source ) || preg_match( '|/\*$|', $source ) ) {
			if ( preg_match( '|/\*$|', $source ) )
				$source = preg_replace( '|/\*$|', '', $source );
			else if ( preg_match( '|/$|', $destination ) )
				$destination = $destination . basename( $source );
			
			$destination = preg_replace( '|/$|', '', $destination );
			
			$files = array_diff( array_merge( glob( $source . '/.*' ), glob( $source . '/*' ) ), array( $source . '/.', $source . '/..' ) );
			
			if ( false === @mkdir( $destination ) )
				return false;
			
			$result = true;
			
			foreach ( (array) $files as $file ) {
				if ( false === $this->_custom_copy( $file, "$destination/", $args, $depth + 1 ) )
					$result = false;
			}
			
			return $result;
		}
		
		return false;
	} // End _copy().
	
	
	*/

	
	// todo: document
	// $exclusions is never modified so just use PHP's copy on modify default behaviour for memory management.
	/*	function_name()
	 *	
	 *	function description
	 *	@param		array/bool		Array of directory paths to exclude.  If true then this directory is excluded so no need to check with exclusion directory.
	 *	@return		array			array( TOTAL_DIRECTORY_SIZE, TOTAL_SIZE_WITH_EXCLUSIONS_TAKEN_INTO_ACCOUNT, OBJECTS_FOUND, OBJECTS_FOUND_WITH_EXCLUSIONS )
	 */
	function dir_size_map( $dir, $base, $exclusions, &$dir_array ) {
		$dir = rtrim( $dir, '/\\' ); // Force no trailing slash.
		
		if( !is_dir( $dir ) ) {
			return 0;
		}
		
		$ret = 0;
		$ret_with_exclusions = 0;
		$ret_objects = 0;
		$ret_objects_with_exclusions = 0;
		$exclusions_result = $exclusions;
		$sub = @opendir( $dir );
		if ( false === $sub ) { // Cannot access.
			pb_backupbuddy::alert( 'Error #568385: Unable to access directory: `' . $dir . '`. Verify proper permissions.', true );
			return 0;
		} else {
			while( $file = readdir( $sub ) ) {
				$exclusions_result = $exclusions;
				
				$dir_path = '/' . str_replace( $base, '', $dir . '/' . $file ) . '/'; //str_replace( $base, '', $dir . $file . '/' );
				
				if ( ( $file == '.' ) || ( $file == '..' ) ) {
					
					// Do nothing.
					
				} elseif ( is_dir( $dir . '/' . $file ) ) { // DIRECTORY.
					
					if ( ( $exclusions === true ) || self::in_array_substr( $exclusions, $dir_path, '/' ) ) {
						$exclusions_result = true;
					}
					$result = $this->dir_size_map( $dir . '/' . $file . '/', $base, $exclusions, $dir_array );
					$this_size = $result[0];
					$this_objects = $result[2];
					
					if ( $exclusions_result === true ) { // If excluding then wipe excluded value.
						$this_size_with_exclusions = false;
						$this_objects_with_exclusions = 0;
					} else {
						$this_size_with_exclusions = $result[1]; // / 1048576 );
						$this_objects_with_exclusions = $result[3]; // / 1048576 );
					}
					
					$dir_array[ $dir_path ] = array( $this_size, $this_size_with_exclusions, $this_objects, $this_objects_with_exclusions ); // $dir_array[ DIRECTORY_PATH ] = DIRECTORY_SIZE;
					
					$ret += $this_size;
					$ret_objects += $this_objects;
					$ret_with_exclusions += $this_size_with_exclusions;
					$ret_objects_with_exclusions += $this_objects_with_exclusions;
					
					unset( $file );
					
				} else { // FILE.
					
					$stats = @stat( $dir . '/' . $file );
					if ( is_array( $stats ) ) {
						$ret += $stats['size'];
						$ret_objects++;
						if ( ( $exclusions !== true ) && !in_array( $dir_path, $exclusions ) ) { // Not excluding.
							$ret_with_exclusions += $stats['size'];
							$ret_objects_with_exclusions++;
						}
					}
					unset( $file );
					
				}
			}
			closedir( $sub );
			unset( $sub );
			return array( $ret, $ret_with_exclusions, $ret_objects, $ret_objects_with_exclusions );
		}
	} // End dir_size_map().
	
	
	
	public static function in_array_substr( $haystack, $needle, $trailing = '' ) {
		foreach( $haystack as $hay ) {
			if ( ( $hay . $trailing ) == substr( $needle . $trailing, 0, strlen( $hay . $trailing ) ) ) {
				//echo $needle . '~' . $hay . '<br>';
				return true;
			}
		}
		
		return false;
	}
	
	
	public function exit_code_lookup( $code ) {
		switch( (string)$code ) {
			case '0':
				return 'Command completed & returned normally.';
				break;
			case '126':
				return 'Command invoked cannot execute. Check command has valid permisions and execute capability.';
				break;
			case '127':
				return 'Command not found.';
				break;
			case '152':
				return 'SIGXCPU 152; CPU time limit exceeded.';
				break;
			case '153':
				return 'SIGXFSZ 153; File size limit exceeded. Verify enough free space exists & filesystem max size not exceeded.';
				break;
			case '158':
				return 'SIGXCPU 158; CPU time limit exceeded.';
				break;
			case '159':
				return 'SIGXFSZ 159; File size limit exceeded. Verify enough free space exists & filesystem max size not exceeded.';
				break;
			default:
				return '-No information available for this exit code- See: https://wiki.ncsa.illinois.edu/display/MRDPUB/Batch+Exit+Codes ';
				break;
		}
	}
	
	// Newest to oldest.
	function glob_by_date( $pattern ) {
		$file_array = array();
		$glob_result = glob( $pattern );
		if ( ! is_array( $glob_result ) ) {
			$glob_result = array();
		}
		foreach ( $glob_result as $filename ) {
			$ctime = filectime( $filename );
			while( isset( $file_array[$ctime] ) ) { // Avoid collisions.
				$ctime = $ctime + 0.1;
			}
			$file_array[$ctime] = $filename; // or just $filename
		}
		krsort( $file_array );
		return $file_array;
		
	} // End glob_by_date().
	
	
} // End class pluginbuddy_settings.



?>