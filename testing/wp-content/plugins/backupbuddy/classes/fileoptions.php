<?php
/* Class pb_backupbuddy_fileoptions
 *
 * @author Dustin Bolton
 * @date April, 2013
 *
 * Uses the filesystem for storing options data. Data is serialized & base64 encoded.
 * By default uses a locking mechanism to lock out accessing the options from another instance
 * while open with this instance. Lock automatically removed on class destruction.
 *
 * After construction check is_ok() function to verify === true. If not true returns error message.
 *
 * Example usage:
 * $backup_options = new pb_backupbuddy_fileoptions( $filename );
 * if ( $backup_options->is_ok() ) {
 * 	$backup_options->options = array( 'hello' => 'world' );
 * 	$backup_options->save(); // Optional force save now. If omitted destructor will hopefully save.
 * }
 
 Another in-use example:
 
pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = true, $ignore_lock = true, $create_file = false );
if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
	pb_backupbuddy::status( 'error', __('Fatal Error #9034.2344848. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
	return false;
}
pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
$fileoptions = &$fileoptions_obj->options;

 *
 */

class pb_backupbuddy_fileoptions {
	
	public $options = ''; // Current options.
	private $_options_hash = ''; // Hold hash of options so we don't perform file operations needlessly if things have not changed.
	private $_file; // Filename options are stored in.
	private $_is_ok = 'UNKNOWN'; // true on error; error message otherwise
	private $_read_only = false;
	private $_loaded = false; // Has file been succesfully loaded yet?
	
	/* __construct()
	 *
	 * Reads and creates file lock. If file does not exist, creates it. Places options into this class's $options.
	 *
	 * @param	string		$file			Full filename to save fileoptions into.
	 * @param	bool		$read_only		true read only mode; false writable.
	 * @param	bool		$ignore_lock	When true ignore file locking. default: false
	 * @param	bool		$create_file	Create file if it does not yet exist and mark is_ok value to true.
	 * @return	null
	 *
	 */
	function __construct( $file, $read_only = false, $ignore_lock = false, $create_file = false ) {
		$this->_file = $file;
		$this->_read_only = $read_only;
		
		// If read-only then ignore locks is forced.
		if ( $read_only === true ) {
			$ignore_lock = true;
		}
		
		if ( ! file_exists( dirname( $file ) ) ) { // Directory exist?
			pb_backupbuddy::anti_directory_browsing( dirname( $file ), $die_on_fail = false, $deny_all = true );
		}
		
		/*
		if ( ! file_exists( $file ) ) { // File exist?
			//$this->save();
		}
		*/
		
		$this->load( $ignore_lock, $create_file );
		
	} // End __construct().
	
	
	
	/* __destruct()
	 *
	 * Saves options on destruction.
	 *
	 * @return null
	 *
	 */
	function __destruct() {
		
		// IMPORTANT: We can NOT rely on any outside classes from here on out such as the framework status method. Pass true to unlock to insure it does not perform any status logging.
		$this->unlock( $destructorCalled = true );
		
	} // End __destruct().
	
	
	
	/* is_ok()
	 *
	 * Determine whether options was loaded correctly and is ok.
	 *
	 * @return true\string		True on valid, else returns error message string.
	 *
	 */
	public function is_ok() {
		
		return $this->_is_ok;
		
	} // End is_ok().
	
	
	
	/* load()
	 *
	 * Load options from file. Use is_ok() to verify integrity. If is_ok() !== true, returns error message.
	 *
	 * @param	bool		$ignore_lock	Whether or not to ignore the file being locked.
	 * @param	bool		$create_file	Create file if it does not yet exist and mark is_ok value to true.
	 * @param	int			$retryCount		If ERROR_EMPTY_FILE_NON_CREATE_MODE error then we will retry a couple of times after a slight pause in case there was a race condition while another process was updating the file.
	 * @return	bool		true on load success, else false.
	 *
	 */
	public function load( $ignore_lock = false, $create_file = false, $retryCount = 0 ) {
		
		// Handle locked file.
		if ( ( false === $ignore_lock ) && ( true === $this->is_locked() ) ) {
			pb_backupbuddy::status( 'warning', 'Warning #54555. Unable to read fileoptions file `' . $this->_file . '` as it is currently locked. Lock file ID: ' . $this->_last_seen_lock_id . '.' );
			$this->_is_ok = 'ERROR_LOCKED';
			return false;
		}
		
		// Get options and decode into usable format.
		if ( file_exists( $this->_file ) ) {
			$options = @file_get_contents( $this->_file );
		} else {
			if ( true !== $create_file ) {
				pb_backupbuddy::status( 'warning', 'Fileoptions file `' . $this->_file . '` not found and NOT in create mode. Verify file exists & check permissions.' );
				$this->_is_ok = 'ERROR_FILE_MISSING_NON_CREATE_MODE';
			}
			$options = '';
		}
		
		if ( false === $options ) {
			pb_backupbuddy::status( 'error', 'Unable to read fileoptions file `' . $this->_file . '`. Verify permissions on this directory.' );
			$this->_is_ok = 'ERROR_READ';
			return false;
		}
		if ( false === ( $options = base64_decode( $options ) ) ) {
			pb_backupbuddy::status( 'error', 'Unable to base64 decode data from fileoptions file `' . $this->_file . '`.' );
			$this->_is_ok = 'ERROR_BASE64_DECODE';
			return false;
		}
		if ( false === ( $options = maybe_unserialize( $options ) ) ) {
			pb_backupbuddy::status( 'error', 'Unable to unserialize data from fileoptions file `' . $this->_file . '`.' );
			$this->_is_ok = 'ERROR_UNSERIALIZE';
			return false;
		}
		
		if ( false === $this->_read_only ) { // Only lock when not in read-only mode.
			if ( false === $this->_lock() ) { // If lock fails (possibly due to existing lock file) then fail load.
				$this->_is_ok = 'ERROR_UNABLE_TO_LOCK';
				return false;
			}
		}
		
		if ( true === $create_file ) {
			$this->_is_ok = true;
		} elseif ( '' != $options ) {
			$this->_is_ok = true;
		} else {
			$this->_is_ok = 'ERROR_EMPTY_FILE_NON_CREATE_MODE';
			if ( $retryCount < 2 ) { // Give it one more chance by sleeping then loading once more. Return whatever result that gives.
				$this->unlock();
				$retryCount++;
				pb_backupbuddy::status( 'details', 'Fileoptions file was EMPTY. Unlocking & sleeping momentarily and then trying again. Attempt #1' . $retryCount );
				sleep( 3 );
				return $this->load( $ignore_lock, $create_file, $retryCount );
			}
		}
		$this->options = $options;
		$this->_loaded = true;
		$this->_options_hash = md5( serialize( $options ) );
		
		return true;
	} // End load();
	
	
	
	/* save()
	 *
	 * Save the options into file now without removing lock.
	 *
	 * @param		bool	$remove_lock	When true the lock will be removed as well. default: false
	 * @return		bool					true on save success, else false.
	 *
	 */
	public function save( $remove_lock = false ) {
		
		if ( '' == $this->_file ) { // No file set yet. Just return.
			return true;
		}
		
		if ( true === $this->_read_only ) {
			pb_backupbuddy::status( 'error', 'Attempted to write to fileoptions while in readonly mode; denied.' );
			return false;
		}
		
		if ( false === $this->_loaded ) { // Skip saving if we have not successfully loaded yet to prevent overwriting data.
			return false;
		}
		
		
		/*
		if ( true === $this->is_locked() ) {
			error_log( 'saveislocked' );
			pb_backupbuddy::status( 'details', 'Unable to write to fileoptions as file is currently locked: `' . $this->_file . '`.' );
			return false;
		}
		*/
		
		$serialized = serialize( $this->options );
		$options_hash = md5( $serialized );
		
		if ( $options_hash == $this->_options_hash ) { // Only update if options has changed so if equal then no change so return.
			if ( true === $remove_lock ) {
				$this->unlock();
			}
			return true;
		}
		
		$options = base64_encode( $serialized );
		
		if ( false === ( $bytesWritten = file_put_contents( $this->_file, $options ) ) ) { // unable to write.
			pb_backupbuddy::status( 'error', 'Unable to write fileoptions file `' . $this->_file . '`. Verify permissions.' );
			if ( true === $remove_lock ) {
				$this->unlock();
			}
			return false;
		} else { // wrote to file.
			pb_backupbuddy::status( 'details', 'Fileoptions saved. ' . $bytesWritten . ' bytes written.' );
			$this->_options_hash = $options_hash;
			if ( true === $remove_lock ) {
				$this->unlock();
			}
			return true;
		}
		
	} // End save().
	
	
	
	/* _lock()
	 *
	 * Lock file.
	 *
	 * @return		bool	true on lock success, else false.
	 *
	 */
	private function _lock() {
		
		$lockFile = $this->_file . '.lock';
		
		if ( true === $this->_read_only ) {
			pb_backupbuddy::status( 'error', 'Attempted to lock fileoptions while in readonly mode; denied.' );
			return false;
		}
		
		$handle = @fopen( $lockFile, 'x' );
		if ( false === $handle ) { // Failed creating file.
			if ( file_exists( $lockFile ) ) {
				$this->_last_seen_lock_id = @file_get_contents( $lockFile );
				pb_backupbuddy::status( 'error', 'Unable to create fileoptions lock file as it already exists: `' . $lockFile . '`. Lock file ID: ' . $this->_last_seen_lock_id . '.' );
			} else {
				pb_backupbuddy::status( 'error', 'Unable to create fileoptions lock file `' . $lockFile . '`. Verify permissions on this directory.' );
			}
			return false;
		} else { // Created file.
			$lockID = uniqid( '', true );
			if ( false === @fwrite( $handle, $lockID ) ) {
				pb_backupbuddy::status( 'warning', 'Unable to write unique lock ID `' . $lockID . '` to lock file `' . $lockFile . '`.' );
			} else {
				pb_backupbuddy::status( 'details', 'Created fileoptions lock file `' . $lockFile . '` with ID: ' . $lockID . '.' );
			}
			@fclose( $handle );
		}
		
	}
	
	
	
	/* unlock()
	 *
	 * Unlock file.
	 * WARNING!!! IMPORTANT!!! We cannot reliably call pb_backupbuddy::status() here _IF_ calling via destructor, $destructorCalled = true.
	 *
	 * @param		bool	$destructorCalled		Whether or not this function was called via the destructor. See warning in comments above.
	 * @return		bool	true on unlock success, else false.
	 *
	 */
	public function unlock( $destructorCalled = false ) {
		
		$lockFile = $this->_file . '.lock';
		
		if ( file_exists( $lockFile ) ) { // Locked; continue to unlock;
			$this->_last_seen_lock_id = @file_get_contents( $lockFile );
			$result = @unlink(  $lockFile );
			if ( true === $result ) {
				if ( false === $destructorCalled ) {
					pb_backupbuddy::status( 'details', 'Unlocked fileoptions lock file `' . $lockFile . '` with lock ID `' . $this->_last_seen_lock_id . '`.' );
				}
				return true;
			} else {
				if ( class_exists( 'pb_backupbuddy' ) ) {
					if ( false === $destructorCalled ) {
						pb_backupbuddy::status( 'error', 'Unable to delete fileoptions lock file `' . $lockFile . '` with lock ID `' . $this->_last_seen_lock_id . '`. Verify permissions on this file / directory.' );
					}
				}
				return false;
			}
		} else { // File already unlocked.
			return true;
		}
		
	} // End unlock().
	
	
	
	/* is_locked()
	 *
	 * Is this file locked / in use?
	 *
	 * @return		bool		Whether or not file is currenty locked.
	 *
	 */
	public function is_locked() {
		
		$lockFile = $this->_file . '.lock';
		
		if ( file_exists( $lockFile ) ) {
			$this->_last_seen_lock_id = @file_get_contents( $lockFile );
			return true;
		} else {
			return false;
		}
		
	} // End is_locked().
	
	
	
} // End class pb_backupbuddy_fileoptions.