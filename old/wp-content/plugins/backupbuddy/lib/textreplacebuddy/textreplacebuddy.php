<?php
/*	pb_backupbuddy_textreplacebuddy class
 *	
 *	@author Dustin Bolton
 *	@since 3.0.0
 *
 *	Text replacements using command line if available. There is no good way to do text replacements
 *	on a large scale in PHP efficiently.
 *
 *	Resulting file stored at $file . '.tmp'. Original file is NOT replaced.
 *
 */
class pb_backupbuddy_textreplacebuddy {
	
	
	/********** Properties **********/
	
	
	private $_version = '0.0.2';																		// Internal version number for this library.
	
	private $_methods = array();																				// Available mechanisms for dumping in order of preference.
	private $_commandbuddy;
	
	/********** Methods **********/
	
	
	/*	__construct()
	 *	
	 *	Default constructor.
	 *	
	 *	@param		array		$force_methods			Optional parameter to override automatic method detection. Skips test and runs first method first.  Falls back to other methods if any failure.
	 *	@return		
	 */
	public function __construct( $force_methods = array() ) {
		
		pb_backupbuddy::status( 'details', 'textreplacebuddy: Loading textreplacebuddy library.' );
		
		// Handles command line execution.
		require_once( pb_backupbuddy::plugin_path() . '/lib/commandbuddy/commandbuddy.php' );
		$this->_commandbuddy = new pb_backupbuddy_commandbuddy();
		
		// Set mechanism for dumping / restoring.
		if ( count( $force_methods ) > 0 ) { // Mechanism forced. Overriding automatic check.
			pb_backupbuddy::status( 'message', 'textreplacebuddy: Settings overriding automatic detection of available database dump methods. Using forced override methods: `' . implode( ',', $force_methods ) . '`.' );
			$this->_methods = $force_methods;
		} else { // No method defined; auto-detect the best.
			$this->_methods = $this->available_textreplace_methods();
		}
		pb_backupbuddy::status( 'message', 'textreplacebuddy: Detected text replacement methods: `' . implode( ',', $this->_methods ) . '`.' );
		
	} // End __construct().
	
	
	
	/*	available_dump_methods()
	 *	
	 *	function description
	 *	
	 *	@param		
	 *	@return		string				Possible returns:  mysqldump, php
	 */
	public function available_textreplace_methods() {
		
		pb_backupbuddy::status( 'details', 'textreplace test: Testing available text replacement methods.' );
		if ( function_exists( 'exec' ) ) { // Exec is available so test mysqldump from here.
			pb_backupbuddy::status( 'details', 'textreplace test: exec() function exists. Testing running sed (text replace command) via exec().' );
			
			
			/********** Begin preparing command **********/
			// Handle Windows wanting .exe.
			if ( stristr( PHP_OS, 'WIN' ) && !stristr( PHP_OS, 'DARWIN' ) ) { // Running Windows. (not darwin)
				return array( 'php' ); // Nothing good in Windows available to text replace.
			} else {
				$command = "echo backup | sed 's/backup/buddy/'"; // Will attempt to replace backup with buddy
			}
			
			// Redirect STDERR to STDOUT.
			$command .= '  2>&1';
			
			/********** End preparing command **********/
			
			
			// Run command.
			pb_backupbuddy::status( 'details', 'textreplace test: Running test command next.' );
			list( $exec_output, $exec_exit_code ) = $this->_commandbuddy->execute( $command );
			
			if ( stristr( implode( ' ', $exec_output ), 'buddy' ) !== false ) { // String Ver appeared in response or usage explanations. Some version dont give version.
				pb_backupbuddy::status( 'details', 'textreplace test: Command appears to be accessible and returns expected response.' );
				return array( 'commandline', 'php' ); // mysqldump best, php next.
			}
			
			
		} else { // No exec() so must fall back to PHP method only.
			pb_backupbuddy::status( 'message', 'textreplace test: Falling back to textreplace compatibility mode (PHP replace). This is slower and more memory intensive.' );
			return array( 'php' );
		}
		
		return array( 'php' );
		
	} // End available_dump_method().
	
	
	
	/*	replace()
	 *	
	 *	Replace all instances of a string within a file. Automatically falls back.
	 *	Resulting file stored in $file . 'tmp'. Original file is NOT replaced.
	 *
	 *	@param		string		$file				Full file path to file to search. A .tmp version is made with the final results. Original file is NOT replaced.
	 *	@param		string		$search				String to search for.
	 *	@param		string		$replacement		String to replace with.
	 *	@param		string		$regex_condition	Condition when replacements may happen. Optional.
	 *	@param		string		$regex_replace		If fallen back into PHP mode then this is needed IF $regex_condition is passed. Optional.
	 *	@return
	 */
	public function string_replace( $file, $search, $replacement, $regex_condition = '', $regex_replace = '' ) {
		$return = false;
		
		pb_backupbuddy::status( 'message', 'Starting text replacement procedure.' );
		pb_backupbuddy::status( 'details', "textreplace being performed on file `{$file}` replacing `{$search}` with `{$replacement}`." );
		
		// Attempt each method in order.
		pb_backupbuddy::status( 'details', 'Preparing to textreplace using available method(s) by priority. Methods: `' . implode( ',', $this->_methods ) . '`' );
		foreach( $this->_methods as $method ) {
			if ( method_exists( $this, "_stringreplace_{$method}" ) ) {
				pb_backupbuddy::status( 'details', 'textreplacebuddy: Attempting replace method `' . $method . '`.' );
				$result = call_user_func( array( $this, "_stringreplace_{$method}" ), $file, $search, $replacement, $regex_condition, $regex_replace );
				
				if ( $result === true ) { // Dump completed succesfully with this method.
					pb_backupbuddy::status( 'details', 'textreplacebuddy: Replace method `' . $method . '` completed successfully.' );
					$return = true;
					break;
				} elseif ( $result === false ) { // Dump failed this method. Will try compatibility fallback to next mode if able.
					// Do nothing. Will try next mode next loop.
					pb_backupbuddy::status( 'details', 'textreplacebuddy: Replace method `' . $method . '` failed. Trying another compatibility mode next if able.' );
				} else {
					pb_backupbuddy::status( 'details', 'textreplacebuddy: Unexepected response: `' . implode( ',', $result ) . '`' );
				}
			}
		}
		
		if ( $return === true ) { // Success.
			pb_backupbuddy::status( 'message', 'Text replace procedure succeeded.' );
			return true;
		} else { // Overall failure.
			pb_backupbuddy::status( 'error', 'Text replace procedure failed.' );
			return false;
		}
		
	} // End dump().
	
	
	
	/*	_stringreplace_commandline()
	 *	
	 *	Performs actual command line string replacement. VIA sed (command line replace).
	 *	Case sensitive.
	 *	Resulting file stored in $file . 'tmp'. Original file is NOT replaced.
	 *	
	 *	@param		string		$file				Full file path to file to replace in. a .tmp version is temporarily made.
	 *	@param		string		$search				String to search for.
	 *	@param		string		$replacement		String to replace with.
	 *	@param		string		$regex_condition	Condition when replacements may happen. Optional.
	 *	@param		string		$regex_replace		NOT used in commandline mode.
	 *	@param		boolean		$global				Whether or not to globally replace in regex. Default: false (only replace first instance per line).
	 *	@return		boolean							True on success; else false.
	 */
	private function _stringreplace_commandline( $file, $search, $replacement, $regex_condition = '', $regex_replace = '', $global = false ) {
		
		pb_backupbuddy::status( 'details', 'textreplacebuddy: Preparing to run command line sed (replacement comment) via exec().' );
		
		// Handle optional global replacement.
		if ( $global === true ) {
			$global_flag = 'g';
			pb_backupbuddy::status( 'details', 'textreplacebuddy: Using global replace per line.' );
		} else {
			$global_flag = '';
			pb_backupbuddy::status( 'details', 'textreplacebuddy: Using first instance replace per line.' );
		}
		
		/********** Begin preparing command **********/
		// Handle Windows wanting .exe.
		if ( stristr( PHP_OS, 'WIN' ) && !stristr( PHP_OS, 'DARWIN' ) ) { // Running Windows. (not darwin)
			return false;
		} else {
			if ( $regex_condition == '' ) { // Normal string replace.
				$command = "sed s/{$search}/{$replacement}/{$global_flag} {$file} > {$file}.tmp";
			} else { // Custom regex conditions.
				$command = "sed -E '/{$regex_condition}/s/{$search}/{$replacement}/{$global_flag}' {$file} > {$file}.tmp";
			}
		}
		
		// Redirect STDERR to STDOUT.
		$command .= '  2>&1';
		
		/********** End preparing command **********/
		
		// Run command.
		pb_backupbuddy::status( 'details', 'textreplacebuddy: Running replace command via exec next.' );
		list( $exec_output, $exec_exit_code ) = $this->_commandbuddy->execute( $command );
		
		// Check the result.
		if ( $exec_exit_code == '0' ) {
			pb_backupbuddy::status( 'details', 'textreplacebuddy: Command appears to succeeded and returned proper response.' );
			if ( file_exists( $file . '.tmp' ) ) { // Temp replacement file found. SUCCESS!
				pb_backupbuddy::status( 'message', 'textreplacebuddy: Temporary text replacement file creation verified.' );
				/*
				if ( true === rename( $file . '.tmp', $file ) ) {
					pb_backupbuddy::status( 'message', 'textreplacebuddy: Temporary moved back to original file. Success.' );
					return true;
				} else {
					pb_backupbuddy::status( 'error', 'textreplacebuddy: Temporary could not be moved back to original file. Failure. Verify permissions.' );
					return false;
				}
				*/
				return true;
			} else { // SQL file MISSING. FAILURE!
				pb_backupbuddy::status( 'error', 'textreplacebuddy: Though command reported success temporary replacement file is missing: `' . $output_file . '`.' );
				return false;
			}
		} else {
			pb_backupbuddy::status( 'error', 'textreplacebuddy: Command did not exit normally. Falling back to text replacement compatibility modes.' );
			return false;
		}
		
		
		// Should never get to here.
		pb_backupbuddy::status( 'error', 'textreplacebuddy: Uncaught exception #45323890.' );
		return false;
		
	} // End _stringreplace_commandline().
	
	
	
	/*	_stringreplace_php()
	 *	
	 *	Performs actual command line string replacement. VIA PHP.
	 *	Case sensitive.
	 *	Resulting file stored in $file . 'tmp'.
	 *	
	 *	@param		string		$file				Full file path to file to replace in. a .tmp version is temporarily made.
	 *	@param		string		$search				String to search for.
	 *	@param		string		$replacement		String to replace with.
	 *	@param		string		$regex_condition	Condition when replacements may happen. Optional.
	 *	@param		string		$regex_replace		If fallen back into PHP mode then this is needed IF $regex_condition is passed. Optional.
	 *	@return		boolean							True on success; else false.
	 */
	private function _stringreplace_php( $file, $search, $replacement, $regex_condition = '', $regex_replace = '' ) {
		
		pb_backupbuddy::status( 'details', 'textreplacebuddy: Starting comptibility mode PHP text replacement.' );
		
		
		$file_o = fopen( $file, 'r' );
		$temp = $file . '.tmp';
		
		if ( !is_writable( $temp ) ) {
			pb_backupbuddy::status( 'error', 'textreplacebuddy: Permission denied writing temporary file `' . $temp . '`.' );
			return false;
		}
		
		if ( is_resource( $file_o ) === true ) {
			while ( feof( $file_o ) === false ) {
			
				if ( $regex_condition != '' ) { // regex
					$content = preg_replace( "/^{$regex_condition}/i", $regex_replace, fgets( $file_o ) );
				} else { // standard string
					$content = str_replace( $search, $replacement, fgets( $file_o ) );
				}
				file_put_contents( $temp, $content, FILE_APPEND );
			}
			
			fclose( $file_o );
		}
		
		//unlink($file);
		
		/*
		$result = rename( $temp, $file );
		if ( $result === false ) {
			pb_backupbuddy::status( 'error', 'textreplacebuddy: Unable to move temporary file back to original file. Failure.' );
		} else {
			pb_backupbuddy::status( 'details', 'textreplacebuddy: Moved temporary file back to original file. Success.' );
		}
		*/
		
		return $result;
		
	} // End _stringreplace_php().
	
	
	
	/*	get_methods()
	 *	
	 *	Get an array of methods. Note: If force overriding methods then detected methods will not be able to display.
	 *	
	 *	@return		array				Array of methods.
	 */
	public function get_methods() {
		return $this->_methods;
	} // End get_methods().
	
	
	
	/*	set_methods()
	 *	
	 *	Set methods. Overrides detected.
	 *	
	 *	@param		array		$methods		Array of methods to set.
	 *	@return		null
	 */
	public function set_methods( $methods = array() ) {
		$this->_methods = $methods;
	} // End set_methods().
	
	
	
} // End pb_backupbuddy_mysqlbuddy class.
?>