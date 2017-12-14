<?php
/**
 *	pluginbuddy_zbzippclzip Class
 *
 *  Extends the zip capability core class with pclzip specific capability
 *	
 *	Version: 1.0.0
 *	Author:
 *	Author URI:
 *
 *	@param		$parent		object		Optional parent object which can provide functions for reporting, etc.
 *	@return		null
 *
 */
if ( !class_exists( "pluginbuddy_zbzippclzip" ) ) {

	/**
	 *	pluginbuddy_PclZip Class
	 *
	 *	Wrapper for PclZip to encapsulate the process of loading the PclZip library (if not
	 *	already loaded, which it shouldn't be generally) and also surrounding method calls
	 *	with the unpleasant workaround for the mbstring issue where things may fail because
	 *	PclZip is using string functions to process binary data and if the string functions
	 *	are overloaded with the multi-byte versions the processing can (probably will) fail.
	 *
	 *	@param	string	$zip_filename	The name of the zip file that will be managed
	 * 	@param	bool	$load_only		True, only load the pclzip library
	 *	@return	null
	 *
	 */
	class pluginbuddy_PclZip {

        /**
         * The created PclZip object if it can be created
         * 
         * @var $_za 	object
         */
		private $_za = null;
		
		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	This is used to try and load the PclZip library and then create an instance of
		 *	an archive with that. If the library cannot be made available then an exception
		 *	is thrown and that is handled by the caller.
		 * 	The $load_only parameter provides to option to only load the pclzip library
		 * 	which may be required to be able to use defined constants before we are ready
		 * 	to actually create a zip file. If called with this parameter true then the
		 * 	value of the $zip_filename parameter is irrelevant.
		 *	Note: PclZip needs a temporary directory to use for temporary files and this must
		 *	be defined as PCLZIP_TEMPORARY_DIRECTORY constant _before_ PclZip class library
		 *	is loaded because loading the library locks in the value of that constant. Our
		 *	choice is to use the get_temp_dir() function to provide a valid/writable directory
		 *	and we are not allowing the caller to override this as it just gets too complicated.
		 *	Both WordPress and importbuddy (through the standalone preloader) provide a get_temp_dir()
		 *	function that _should_ be able to provide a valid/writable directory one way or another
		 *	(even if it requires the user to do some configuration) so this is the best approach
		 *	to decouple PclZip from any application functionality.
		 *	Note: We have one possible issue with this in that something else might define
		 *	PCLZIP_TEMPORARY_DIR before we get the chance and the directory thus defined may not
		 *	be valid/writable - unfortunately we cnnot do anything about that other than flag up
		 *	the possibility as a troubleshooting hint.
		 *	TODO: Consider having a "suppress warnings" parameter to determine whether methods
		 *	should be invoked with warnings suppressed or not. For is_available() usage we would
		 *	want to so as not to potentially flood the PHP error log. For other functions that
		 *	are not called frequently we might not want to suppress the warnings.
		 *	
		 *	@param		string		$zip_filename	The name of the zip file that will be managed
		 * 	@param		bool		$load_only		True, only load the pclzip library
		 *	@param		mixed		$tempdir		String, temporary directory to use (nust exist and be usuable), null for derive
		 *	@return		null
		 *
		 */
		public function __construct( $zip_filename, $load_only = false, $tmpdir = null ) {
			
			// Remember if we have logged the pclzip_temporary_dir value - this stops
			// us repeatedly logging if _we_ loaded pclzip or after the first time we
			// log the pclzip_temporary_dir value because something else loaded pclzip
			static $logged_tempdir = false;
		
			// The PclZip class has to be available for us so let's have a go
			// Note: it is not required because nothing will break without it but the method will 
			// simply not be available
			// This may seem laborious but it's robust against include_once not playing nice if the
			// class is already included and trying to include it again
			if ( !@class_exists( 'PclZip', false ) ) {
			
				$possibles = array( ABSPATH . 'wp-admin/includes/class-pclzip.php', pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
				
				foreach ( $possibles as $possible) {
				
					if ( @is_readable( $possible ) ) {
					
						// Found one that should be loadable so try it and then break out
						pb_backupbuddy::status( 'details', 'PCLZip class not found. Attempting to load from `' . $possible . '`.' );

						// We are going to load so check if pclzip_temporary_dir is already
						// defined and if it is we have to warn because it (probably) wasn't
						// set by us
						if ( defined( 'PCLZIP_TEMPORARY_DIR' ) ) {
						
							pb_backupbuddy::status( 'details', __('PCLZIP_TEMPORARY_DIR already defined (1) - may cause problems if this is not available: ','it-l10n-backupbuddy' ) . '`' . PCLZIP_TEMPORARY_DIR . '`');
						
						} else {
							
							$tempdir = ( is_string( $tmpdir ) ) ? $tmpdir : get_temp_dir() ;
						
							define( 'PCLZIP_TEMPORARY_DIR', $tempdir );
							pb_backupbuddy::status( 'details', __('PCLZIP_TEMPORARY_DIR defined: ','it-l10n-backupbuddy' ) . '`' . PCLZIP_TEMPORARY_DIR . '`');
							
						}
						
						// This stops us logging again on repeated uses
						$logged_tempdir = true;

						@include_once( $possible );
						break;
										
					} 
				
				}

			} else {
				
				// The class already exists so we might have loaded it or something
				// else might have loaded it. We'll log PCLZIP_TEMPORARY_DIR unless
				// we already logged it which we would have done if we loaded pclzip
				if ( defined( 'PCLZIP_TEMPORARY_DIR' ) && ( false === $logged_tempdir ) ) {
					
					pb_backupbuddy::status( 'details', __('PCLZIP_TEMPORARY_DIR already defined (2) - may cause problems if this is not available: ','it-l10n-backupbuddy' ) . '`' . PCLZIP_TEMPORARY_DIR . '`');

					// Now we logged it make sure we don't keep doing so
					$logged_tempdir = true;

				}

			}
			
			// By now PclZip _should_ be available so let's see...
			if ( @class_exists( 'PclZip', false ) ) {
			
				// It's available so create the private instance if required
				if ( false === $load_only ) {
					
					$this->_za = new PclZip( $zip_filename );
					
				}
				
			} else {
			
				// Not available so throw the exception for the caller to handle
				throw new Exception( 'PclZip class does not exist.' );
			
			}
			
			return;
		
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Default destructor.
		 *	
		 *	@return		null
		 *
		 */
		public function __destruct() {
		
			if ( null != $this->_za ) { unset ( $this->_za ); }
			
			return;
		
		}
		
		// --------------------------------------------------------------------------------
		// Function :
		//   add($p_filelist, $p_add_dir="", $p_remove_dir="")
		//   add($p_filelist, $p_option, $p_option_value, ...)
		// Description :
		//   This method supports two synopsis. The first one is historical.
		//   This methods add the list of files in an existing archive.
		//   If a file with the same name already exists, it is added at the end of the
		//   archive, the first one is still present.
		//   If the archive does not exist, it is created.
		// Parameters :
		//   $p_filelist : An array containing file or directory names, or
		//                 a string containing one filename or one directory name, or
		//                 a string containing a list of filenames and/or directory
		//                 names separated by spaces.
		//   $p_add_dir : A path to add before the real path of the archived file,
		//                in order to have it memorized in the archive.
		//   $p_remove_dir : A path to remove from the real path of the file to archive,
		//                   in order to have a shorter path memorized in the archive.
		//                   When $p_add_dir and $p_remove_dir are set, $p_remove_dir
		//                   is removed first, before $p_add_dir is added.
		// Options :
		//   PCLZIP_OPT_ADD_PATH :
		//   PCLZIP_OPT_REMOVE_PATH :
		//   PCLZIP_OPT_REMOVE_ALL_PATH :
		//   PCLZIP_OPT_COMMENT :
		//   PCLZIP_OPT_ADD_COMMENT :
		//   PCLZIP_OPT_PREPEND_COMMENT :
		//   PCLZIP_CB_PRE_ADD :
		//   PCLZIP_CB_POST_ADD :
		// Return Values :
		//   0 on failure,
		//   The list of the added files, with a status of the add action.
		//   (see PclZip::listContent() for list entry format)
		// --------------------------------------------------------------------------------
		function _add($p_filelist)
		{
		$v_result=1;

		// ----- Reset the error handler
		$this->_za->privErrorReset();

		// ----- Set default values
		$v_options = array();
		$v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

		// ----- Look for variable options arguments
		$v_size = func_num_args();

		// ----- Look for arguments
		if ($v_size > 1) {
		  // ----- Get the arguments
		  $v_arg_list = func_get_args();

		  // ----- Remove form the options list the first argument
		  array_shift($v_arg_list);
		  $v_size--;

		  // ----- Look for first arg
		  if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {

			// ----- Parse the options
			$v_result = $this->_za->privParseOptions($v_arg_list, $v_size, $v_options,
												array (PCLZIP_OPT_REMOVE_PATH => 'optional',
													   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
													   PCLZIP_OPT_ADD_PATH => 'optional',
													   PCLZIP_CB_PRE_ADD => 'optional',
													   PCLZIP_CB_POST_ADD => 'optional',
													   PCLZIP_OPT_NO_COMPRESSION => 'optional',
													   PCLZIP_OPT_COMMENT => 'optional',
													   PCLZIP_OPT_ADD_COMMENT => 'optional',
													   PCLZIP_OPT_PREPEND_COMMENT => 'optional',
													   PCLZIP_OPT_TEMP_FILE_THRESHOLD => 'optional',
													   PCLZIP_OPT_TEMP_FILE_ON => 'optional',
													   PCLZIP_OPT_TEMP_FILE_OFF => 'optional'
													   //, PCLZIP_OPT_CRYPT => 'optional'
													   ));
			if ($v_result != 1) {
			  return 0;
			}
		  }

		  // ----- Look for 2 args
		  // Here we need to support the first historic synopsis of the
		  // method.
		  else {

			// ----- Get the first argument
			$v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];

			// ----- Look for the optional second argument
			if ($v_size == 2) {
			  $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
			}
			else if ($v_size > 2) {
			  // ----- Error log
			  PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

			  // ----- Return
			  return 0;
			}
		  }
		}

		// ----- Look for default option values
		$this->_za->privOptionDefaultThreshold($v_options);

		// ----- Init
		$v_string_list = array();
		$v_att_list = array();
		$v_filedescr_list = array();
		$p_result_list = array();

		// ----- Look if the $p_filelist is really an array
		if (is_array($p_filelist)) {

		  // ----- Look if the first element is also an array
		  //       This will mean that this is a file description entry
		  if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
			$v_att_list = $p_filelist;
		  }

		  // ----- The list is a list of string names
		  else {
			$v_string_list = $p_filelist;
		  }
		}

		// ----- Look if the $p_filelist is a string
		else if (is_string($p_filelist)) {
		  // ----- Create a list from the string
		  $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
		}

		// ----- Invalid variable type for $p_filelist
		else {
		  PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type '".gettype($p_filelist)."' for p_filelist");
		  return 0;
		}

		// ----- Reformat the string list
		if (sizeof($v_string_list) != 0) {
		  foreach ($v_string_list as $v_string) {
			$v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
		  }
		}

		// ----- For each file in the list check the attributes
		$v_supported_attributes
		= array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
				 ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
				 ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
				 ,PCLZIP_ATT_FILE_MTIME => 'optional'
				 ,PCLZIP_ATT_FILE_CONTENT => 'optional'
				 ,PCLZIP_ATT_FILE_COMMENT => 'optional'
							);
		foreach ($v_att_list as $v_entry) {
		  $v_result = $this->_za->privFileDescrParseAtt($v_entry,
												   $v_filedescr_list[],
												   $v_options,
												   $v_supported_attributes);
		  if ($v_result != 1) {
			return 0;
		  }
		}

		// ----- Expand the filelist (expand directories)
		$v_result = $this->_za->privFileDescrExpand($v_filedescr_list, $v_options);
		if ($v_result != 1) {
		  return 0;
		}

		// ----- Call the create fct
		$v_result = $this->privAdd($v_filedescr_list, $p_result_list, $v_options);
		if ($v_result != 1) {
		  return 0;
		}

		// ----- Return
		return $p_result_list;
		}
		// --------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------
		// Function : privAdd()
		// Description :
		// Parameters :
		// Return Values :
		// --------------------------------------------------------------------------------
		function privAdd($p_filedescr_list, &$p_result_list, &$p_options)
		{
		$v_result=1;
		$v_list_detail = array();

		// ----- Look if the archive exists or is empty
		if ((!is_file($this->_za->zipname)) || (filesize($this->_za->zipname) == 0))
		{

		  // ----- Do a create
		  $v_result = $this->_za->privCreate($p_filedescr_list, $p_result_list, $p_options);

		  // ----- Return
		  return $v_result;
		}
		// ----- Magic quotes trick
		$this->_za->privDisableMagicQuotes();

		// ----- Open the zip file
		if (($v_result=$this->_za->privOpenFd('rb')) != 1)
		{
		  // ----- Magic quotes trick
		  $this->_za->privSwapBackMagicQuotes();

		  // ----- Return
		  return $v_result;
		}

		// ----- Read the central directory informations
		$v_central_dir = array();
		if (($v_result = $this->_za->privReadEndCentralDir($v_central_dir)) != 1)
		{
		  $this->_za->privCloseFd();
		  $this->_za->privSwapBackMagicQuotes();
		  return $v_result;
		}

		// ----- Go to beginning of File
		@rewind($this->_za->zip_fd);

		// ----- Creates a temporay file
		$v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

		// ----- Open the temporary file in write mode
		if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
		{
		  $this->_za->privCloseFd();
		  $this->_za->privSwapBackMagicQuotes();

		  PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');

		  // ----- Return
		  return PclZip::errorCode();
		}

		// ----- Copy the files from the archive to the temporary file
		// TBC : Here I should better append the file and go back to erase the central dir
		$v_size = $v_central_dir['offset'];
		while ($v_size != 0)
		{
		  $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
		  $v_buffer = fread($this->_za->zip_fd, $v_read_size);
		  @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
		  $v_size -= $v_read_size;
		}

		// ----- Swap the file descriptor
		// Here is a trick : I swap the temporary fd with the zip fd, in order to use
		// the following methods on the temporary fil and not the real archive
		$v_swap = $this->_za->zip_fd;
		$this->_za->zip_fd = $v_zip_temp_fd;
		$v_zip_temp_fd = $v_swap;

		// ----- Add the files
		$v_header_list = array();
		if (($v_result = $this->_za->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)
		{
		  fclose($v_zip_temp_fd);
		  $this->_za->privCloseFd();
		  @unlink($v_zip_temp_name);
		  $this->_za->privSwapBackMagicQuotes();

		  // ----- Return
		  return $v_result;
		}

		// ----- Store the offset of the central dir
		$v_offset = @ftell($this->_za->zip_fd);

		// ----- Copy the block of file headers from the old archive
		$v_size = $v_central_dir['size'];
		while ($v_size != 0)
		{
		  $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
		  $v_buffer = @fread($v_zip_temp_fd, $v_read_size);
		  @fwrite($this->_za->zip_fd, $v_buffer, $v_read_size);
		  $v_size -= $v_read_size;
		}

		// ----- Create the Central Dir files header
		for ($i=0, $v_count=0; $i<sizeof($v_header_list); $i++)
		{
		  // ----- Create the file header
		  if ($v_header_list[$i]['status'] == 'ok') {
			if (($v_result = $this->_za->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
			  fclose($v_zip_temp_fd);
			  $this->_za->privCloseFd();
			  @unlink($v_zip_temp_name);
			  $this->_za->privSwapBackMagicQuotes();

			  // ----- Return
			  return $v_result;
			}
			$v_count++;
		  }

		  // ----- Transform the header to a 'usable' info
		  $this->_za->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
		}

		// ----- Zip file comment
		$v_comment = $v_central_dir['comment'];
		if (isset($p_options[PCLZIP_OPT_COMMENT])) {
		  $v_comment = $p_options[PCLZIP_OPT_COMMENT];
		}
		if (isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
		  $v_comment = $v_comment.$p_options[PCLZIP_OPT_ADD_COMMENT];
		}
		if (isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
		  $v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT].$v_comment;
		}

		// ----- Calculate the size of the central header
		$v_size = @ftell($this->_za->zip_fd)-$v_offset;

		// ----- Create the central dir footer
		if (($v_result = $this->_za->privWriteCentralHeader($v_count+$v_central_dir['entries'], $v_size, $v_offset, $v_comment)) != 1)
		{
		  // ----- Reset the file list
		  unset($v_header_list);
		  $this->_za->privSwapBackMagicQuotes();

		  // ----- Return
		  return $v_result;
		}

		// ----- Swap back the file descriptor
		$v_swap = $this->_za->zip_fd;
		$this->_za->zip_fd = $v_zip_temp_fd;
		$v_zip_temp_fd = $v_swap;

		// ----- Close
		$this->_za->privCloseFd();

		// ----- Close the temporary file
		@fclose($v_zip_temp_fd);

		// ----- Magic quotes trick
		$this->_za->privSwapBackMagicQuotes();

		// ----- Delete the zip file
		// TBC : I should test the result ...
		@unlink($this->_za->zipname);

		// ----- Rename the temporary file
		// TBC : I should test the result ...
		//@rename($v_zip_temp_name, $this->zipname);
		PclZipUtilRename($v_zip_temp_name, $this->_za->zipname);

		// ----- Return
		return $v_result;
		}
		// --------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------
		// Function :
		//   grow($p_filelist, $p_add_dir="", $p_remove_dir="")
		//   grow($p_filelist, $p_option, $p_option_value, ...)
		// Description :
		//   This method supports two synopsis. The first one is historical.
		//   This methods add the list of files in an existing archive.
		//   If a file with the same name already exists, it is added at the end of the
		//   archive, the first one is still present.
		//   If the archive does not exist, it is created.
		// Parameters :
		//   $p_filelist : An array containing file or directory names, or
		//                 a string containing one filename or one directory name, or
		//                 a string containing a list of filenames and/or directory
		//                 names separated by spaces.
		//   $p_add_dir : A path to add before the real path of the archived file,
		//                in order to have it memorized in the archive.
		//   $p_remove_dir : A path to remove from the real path of the file to archive,
		//                   in order to have a shorter path memorized in the archive.
		//                   When $p_add_dir and $p_remove_dir are set, $p_remove_dir
		//                   is removed first, before $p_add_dir is added.
		// Options :
		//   PCLZIP_OPT_ADD_PATH :
		//   PCLZIP_OPT_REMOVE_PATH :
		//   PCLZIP_OPT_REMOVE_ALL_PATH :
		//   PCLZIP_OPT_COMMENT :
		//   PCLZIP_OPT_ADD_COMMENT :
		//   PCLZIP_OPT_PREPEND_COMMENT :
		//   PCLZIP_CB_PRE_ADD :
		//   PCLZIP_CB_POST_ADD :
		// Return Values :
		//   0 on failure,
		//   The list of the added files, with a status of the add action.
		//   (see PclZip::listContent() for list entry format)
		// --------------------------------------------------------------------------------
		function _grow($p_filelist)
		{
		$v_result=1;

		// ----- Reset the error handler
		$this->_za->privErrorReset();

		// ----- Set default values
		$v_options = array();
		$v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

		// ----- Look for variable options arguments
		$v_size = func_num_args();

		// ----- Look for arguments
		if ($v_size > 1) {
		  // ----- Get the arguments
		  $v_arg_list = func_get_args();

		  // ----- Remove form the options list the first argument
		  array_shift($v_arg_list);
		  $v_size--;

		  // ----- Look for first arg
		  if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {

			// ----- Parse the options
			$v_result = $this->_za->privParseOptions($v_arg_list, $v_size, $v_options,
												array (PCLZIP_OPT_REMOVE_PATH => 'optional',
													   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
													   PCLZIP_OPT_ADD_PATH => 'optional',
													   PCLZIP_CB_PRE_ADD => 'optional',
													   PCLZIP_CB_POST_ADD => 'optional',
													   PCLZIP_OPT_NO_COMPRESSION => 'optional',
													   PCLZIP_OPT_COMMENT => 'optional',
													   PCLZIP_OPT_ADD_COMMENT => 'optional',
													   PCLZIP_OPT_PREPEND_COMMENT => 'optional',
													   PCLZIP_OPT_TEMP_FILE_THRESHOLD => 'optional',
													   PCLZIP_OPT_TEMP_FILE_ON => 'optional',
													   PCLZIP_OPT_TEMP_FILE_OFF => 'optional'
													   //, PCLZIP_OPT_CRYPT => 'optional'
													   ));
			if ($v_result != 1) {
			  return 0;
			}
		  }

		  // ----- Look for 2 args
		  // Here we need to support the first historic synopsis of the
		  // method.
		  else {

			// ----- Get the first argument
			$v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];

			// ----- Look for the optional second argument
			if ($v_size == 2) {
			  $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
			}
			else if ($v_size > 2) {
			  // ----- Error log
			  PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

			  // ----- Return
			  return 0;
			}
		  }
		}

		// ----- Look for default option values
		$this->_za->privOptionDefaultThreshold($v_options);

		// ----- Init
		$v_string_list = array();
		$v_att_list = array();
		$v_filedescr_list = array();
		$p_result_list = array();

		// ----- Look if the $p_filelist is really an array
		if (is_array($p_filelist)) {

		  // ----- Look if the first element is also an array
		  //       This will mean that this is a file description entry
		  if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
			$v_att_list = $p_filelist;
		  }

		  // ----- The list is a list of string names
		  else {
			$v_string_list = $p_filelist;
		  }
		}

		// ----- Look if the $p_filelist is a string
		else if (is_string($p_filelist)) {
		  // ----- Create a list from the string
		  $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
		}

		// ----- Invalid variable type for $p_filelist
		else {
		  PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type '".gettype($p_filelist)."' for p_filelist");
		  return 0;
		}

		// ----- Reformat the string list
		if (sizeof($v_string_list) != 0) {
		  foreach ($v_string_list as $v_string) {
			$v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
		  }
		}

		// ----- For each file in the list check the attributes
		$v_supported_attributes
		= array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
				 ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
				 ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
				 ,PCLZIP_ATT_FILE_MTIME => 'optional'
				 ,PCLZIP_ATT_FILE_CONTENT => 'optional'
				 ,PCLZIP_ATT_FILE_COMMENT => 'optional'
							);
		foreach ($v_att_list as $v_entry) {
		  $v_result = $this->_za->privFileDescrParseAtt($v_entry,
												   $v_filedescr_list[],
												   $v_options,
												   $v_supported_attributes);
		  if ($v_result != 1) {
			return 0;
		  }
		}

		// ----- Expand the filelist (expand directories)
		$v_result = $this->_za->privFileDescrExpand($v_filedescr_list, $v_options);
		if ($v_result != 1) {
		  return 0;
		}

		// ----- Call the create fct
		$v_result = $this->privGrow($v_filedescr_list, $p_result_list, $v_options);
		if ($v_result != 1) {
		  return 0;
		}

		// ----- Return
		return $p_result_list;
		}
		// --------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------
		// Function : privGrow()
		// Description :
		// Parameters :
		// Return Values :
		// --------------------------------------------------------------------------------
		function privGrow($p_filedescr_list, &$p_result_list, &$p_options)
		{
		$v_result=1;
		$v_list_detail = array();

		// ----- Look if the archive exists or is empty
		if ((!is_file($this->_za->zipname)) || (filesize($this->_za->zipname) == 0))
		{

		  // ----- Do a create
		  $v_result = $this->_za->privCreate($p_filedescr_list, $p_result_list, $p_options);

		  // ----- Return
		  return $v_result;
		}
		// ----- Magic quotes trick
		$this->_za->privDisableMagicQuotes();

    	// ----- Open the zip file
		// ----- Open the zip file in r/w binary mode with no truncation and file pointer at start
		if (($v_result=$this->_za->privOpenFd('c+b')) != 1)
		{
		  // ----- Magic quotes trick
		  $this->_za->privSwapBackMagicQuotes();

		  // ----- Return
		  return $v_result;
		}

		// ----- Read the central directory informations
		$v_central_dir = array();
		if (($v_result = $this->_za->privReadEndCentralDir($v_central_dir)) != 1)
		{
		  $this->_za->privCloseFd();
		  $this->_za->privSwapBackMagicQuotes();
		  return $v_result;
		}

		// ----- Go to beginning of File
		//@rewind($this->_za->zip_fd);
		// ----- Go to the start of the central dir
		@fseek($this->_za->zip_fd, $v_central_dir['offset']);

		// ----- Creates a temporay file
		//$v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';
		$v_zip_temp_name = 'php://temp/maxmemory:10485760';

	    // ----- Open the temporary file in write mode
	    //if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
		// ----- Open the temporary file in read/write mode
		if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'w+b')) == 0)
		{
		  $this->_za->privCloseFd();
		  $this->_za->privSwapBackMagicQuotes();

		  //PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');
		  PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary read/write mode');

		  // ----- Return
		  return PclZip::errorCode();
		}

		// ----- Copy the files from the archive to the temporary file
		// TBC : Here I should better append the file and go back to erase the central dir
		//$v_size = $v_central_dir['offset'];
		// ----- Copy the existing central dir to a temporary file
		$v_size = $v_central_dir['size'];
		while ($v_size != 0)
		{
		  $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
		  $v_buffer = fread($this->_za->zip_fd, $v_read_size);
		  @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
		  $v_size -= $v_read_size;
		}

		// ----- Swap the file descriptor
		// Here is a trick : I swap the temporary fd with the zip fd, in order to use
		// the following methods on the temporary fil and not the real archive
		//$v_swap = $this->_za->zip_fd;
		//$this->_za->zip_fd = $v_zip_temp_fd;
		//$v_zip_temp_fd = $v_swap;
		// ----- Modify existing zip file so keep file descriptors as they are
		
		// ----- Now truncate after existing files and seek to end to add new files
		@rewind($this->_za->zip_fd);
		@ftruncate($this->_za->zip_fd, $v_central_dir['offset']);
		@fseek($this->_za->zip_fd, 0, SEEK_END);

		// ----- Add the files
		$v_header_list = array();
		if (($v_result = $this->_za->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)
		{
		  fclose($v_zip_temp_fd);
		  $this->_za->privCloseFd();
		  //@unlink($v_zip_temp_name);
		  $this->_za->privSwapBackMagicQuotes();

		  // ----- Return
		  return $v_result;
		}

		// ----- Store the offset of the central dir
		$v_offset = @ftell($this->_za->zip_fd);
		
		// ----- Rewind temp file ready to copy original central dir entries
		@rewind($v_zip_temp_fd);

		// ----- Copy the block of file headers from the old archive
		$v_size = $v_central_dir['size'];
		while ($v_size != 0)
		{
		  $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
		  $v_buffer = @fread($v_zip_temp_fd, $v_read_size);
		  @fwrite($this->_za->zip_fd, $v_buffer, $v_read_size);
		  $v_size -= $v_read_size;
		}

		// ----- Create the Central Dir files header
		for ($i=0, $v_count=0; $i<sizeof($v_header_list); $i++)
		{
		  // ----- Create the file header
		  if ($v_header_list[$i]['status'] == 'ok') {
			if (($v_result = $this->_za->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
			  fclose($v_zip_temp_fd);
			  $this->_za->privCloseFd();
			  //@unlink($v_zip_temp_name);
			  $this->_za->privSwapBackMagicQuotes();

			  // ----- Return
			  return $v_result;
			}
			$v_count++;
		  }

		  // ----- Transform the header to a 'usable' info
		  $this->_za->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
		}

		// ----- Zip file comment
		$v_comment = $v_central_dir['comment'];
		if (isset($p_options[PCLZIP_OPT_COMMENT])) {
		  $v_comment = $p_options[PCLZIP_OPT_COMMENT];
		}
		if (isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
		  $v_comment = $v_comment.$p_options[PCLZIP_OPT_ADD_COMMENT];
		}
		if (isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
		  $v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT].$v_comment;
		}

		// ----- Calculate the size of the central header
		$v_size = @ftell($this->_za->zip_fd)-$v_offset;

		// ----- Create the central dir footer
		if (($v_result = $this->_za->privWriteCentralHeader($v_count+$v_central_dir['entries'], $v_size, $v_offset, $v_comment)) != 1)
		{
		  // ----- Reset the file list
		  unset($v_header_list);
		  $this->_za->privSwapBackMagicQuotes();

		  // ----- Return
		  return $v_result;
		}

		// ----- Swap back the file descriptor
		//$v_swap = $this->_za->zip_fd;
		//$this->_za->zip_fd = $v_zip_temp_fd;
		//$v_zip_temp_fd = $v_swap;
		// ----- File descriptors never swapped originally

		// ----- Close
		$this->_za->privCloseFd();

		// ----- Close the temporary file
		@fclose($v_zip_temp_fd);

		// ----- Magic quotes trick
		$this->_za->privSwapBackMagicQuotes();

		// ----- Delete the zip file
		// TBC : I should test the result ...
		//@unlink($this->_za->zipname);
		// ----- Delete the temporary file
		//@unlink($v_zip_temp_name);

		// ----- Rename the temporary file
		// TBC : I should test the result ...
		//@rename($v_zip_temp_name, $this->zipname);
		//PclZipUtilRename($v_zip_temp_name, $this->_za->zipname);
		// ----- We grew the existing zip file so no renaming to do

		// ----- Return
		return $v_result;
		}
		// --------------------------------------------------------------------------------

		/**
		 *	__call()
		 *	
		 *	Magic method intercepting calls to unknown methods. This allows us to intercept
		 *	all method calls and add additional processing. Note that the main wrapping we
		 *	want to apply is setting the internal encoding if required so that string functions
		 *	can be used on binary data but we also need to intercept some methods and override
		 *	them. We could do this just by having a method of the same name but that would
		 *	bypass the wrapper so we need to handle those intercepted methods within this
		 *	magic method as well.
		 *	
		 *	@param		string	$method		The name of the intercepted method
		 *	@param		array	$arguments	Array of the arguments associated with the method call
		 *	@return		mixed	$result		Whatever the invoked wrapper method call returns
		 *
		 */
		public function __call( $method, $arguments ) {
		
			$result = false;
		
			// See #15789 - PclZip uses string functions on binary data
			// If it's overloaded with Multibyte safe functions the results are incorrect.
			if ( @ini_get( 'mbstring.func_overload' ) && @function_exists( 'mb_internal_encoding' ) ) {
			
				$previous_encoding = @mb_internal_encoding();
				@mb_internal_encoding( 'ISO-8859-1' );
				
			}
			
			switch ( $method ) {
				
				case 'add':
					$result = @call_user_func_array( array( $this, '_' . $method ), $arguments );
					break;
				case 'grow':
					$result = @call_user_func_array( array( $this, '_' . $method ), $arguments );
					break;
				default:
					$result = @call_user_func_array( array( $this->_za, $method ), $arguments );
			
			}
			
			// Now undo any change we may have made to the encoding
			if ( isset( $previous_encoding ) ) {
			
				@mb_internal_encoding( $previous_encoding );
				unset( $previous_encoding );

			}
		
			return $result;
		
		}
	
	}

	class pluginbuddy_zbzippclzip extends pluginbuddy_zbzipcore {
	
		// Constants for file handling
		const ZIP_LOG_FILE_NAME      = 'temp_zip_pclzip_log.txt';
		const ZIP_ERRORS_FILE_NAME   = 'last_pclzip_errors.txt';
		const ZIP_WARNINGS_FILE_NAME = 'last_pclzip_warnings.txt';
		const ZIP_OTHERS_FILE_NAME   = 'last_pclzip_others.txt';
		const ZIP_CONTENT_FILE_NAME  = 'last_pclzip_list.txt';
		
		// exec specific default for burst handling
		const ZIP_PCLZIP_DEFAULT_BURST_MAX_PERIOD = 20;
		
        /**
         * method tag used to refer to the method and entities associated with it such as class name
         * 
         * @var $_method_tag 	string
         */
		public static $_method_tag = 'pclzip';
			
        /**
         * This tells us whether this method is regarded as a "compatibility" method
         * 
         * @var bool
         */
		public static $_is_compatibility_method = true;
			
        /**
         * This tells us the dependencies of this method so they can be check to see if the method can be supported
         * Note: PclZip constructor checks for gzopen function and dies on failure so we may as well pre-empt that
         * 
         * @var array
         */
		public static $_method_dependencies = array( 'classes' => array(),
											  		 'functions' => array( 'gzopen' ),
											  		 'extensions' => array( ),
											  		 'files' => array(),
											  		 'check_func' => 'check_method_dependencies_static'
													);
			
		/**
		 * 
		 * get_method_tag_static()
		 *
		 * Get the static method tag in a static context
		 *
		 * @return		string	The method tag
		 *
		 */
		public static function get_method_tag_static() {
		
			return self::$_method_tag;
			
		}

		/**
		 * 
		 * get_is_compatibility_method_static()
		 *
		 * Get the compatibility method indicator in a static context
		 *
		 * @return		bool	True if is a compatibility method
		 *
		 */
		public static function get_is_compatibility_method_static() {
		
			return self::$_is_compatibility_method;
		}

		/**
		 * 
		 * get_method_dependencies_static()
		 *
		 * Get the method dependencies array in a static context
		 *
		 * @return		array	The dependencies of the method that is requires to be a supported method
		 *
		 */
		public static function get_method_dependencies_static() {
		
			return self::$_method_dependencies;
		}

		/**
		 * 
		 * check_method_dependencies_static()
		 *
		 * Allows additional method dependency checks beyond the standard in a static context
		 *
		 * @return		bool	True if additional dependency checks passed
		 *
		 */
		public static function check_method_dependencies_static() {
		
			$result = false;
			
			// Need to verify that at least PclZip should be available to be loaded (but we
			// don't actually want to load it here)
			$possibles = array( ABSPATH . 'wp-admin/includes/class-pclzip.php', pb_backupbuddy::plugin_path() . '/lib/pclzip/pclzip.php' );
			
			foreach ( $possibles as $possible) {
			
				if ( @is_readable( $possible ) ) {
				
					// Found one that should be loadable so break out
					$result = true;
					break;
									
				} 
			
			}
			
			return $result;
		
		}

		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	
		 *	@param		reference	&$parent		[optional] Reference to the object containing the status() function for status updates.
		 *	@return		null
		 *
		 */
		public function __construct( &$parent = null ) {

			parent::__construct( $parent );
			
			// Override some of parent defaults
			$this->_method_details[ 'attr' ] = array_merge( $this->_method_details[ 'attr' ],
															array( 'name' => 'PclZip Method',
													  			   'compatibility' => pluginbuddy_zbzippclzip::$_is_compatibility_method )
													  	   );

			// No relevant parameters for this method
			$this->_method_details[ 'param' ] = array();
			
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Default destructor.
		 *	
		 *	@return		null
		 *
		 */
		public function __destruct( ) {
		
			parent::__destruct();

		}
		
		/**
		 *	get_method_tag()
		 *	
		 *	Returns the (static) method tag
		 *	
		 *	@return		string The method tag
		 *
		 */
		public function get_method_tag() {
		
			return pluginbuddy_zbzippclzip::$_method_tag;
			
		}
		
			/**
		 *	get_is_compatibility_method()
		 *	
		 *	Returns the (static) is_compatibility_method boolean
		 *	
		 *	@return		bool
		 *
		 */
		public function get_is_compatibility_method() {
		
			return pluginbuddy_zbzippclzip::$_is_compatibility_method;
			
		}
		
		/**
		 *	is_available()
		 *	
		 *	A function that tests for the availability of the specific method and its available modes. Will test for
		 *  multiple modes (zip & unzip) and only return false if neither is available. Actual available modes will
		 *  be indicated in the method attributes.
		 *
		 *  Note: in this case as the zip and unzip capabilities are all wrapped up in the same class then if we
		 *  can zip then we'll assume (for now) that we can unzip as well so attributes are set accordingly.
		 *	
		 *	@param		string	$tempdir	Temporary directory to use for any test files (must be writeable)
		 *	@return		bool				True if the method is available for at least one mode, false otherwise
		 *
		 */
		public function is_available( $tempdir ) {
		
			$result = false;
			$za = null;
			
			$test_file = $tempdir . 'temp_test_' . uniqid() . '.zip';
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_PclZip( $test_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('PclZip test FAILED: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;

			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				if ( $za->create( __FILE__ , PCLZIP_OPT_REMOVE_PATH, dirname( __FILE__)  ) !== 0 ) {
						
					if ( @file_exists( $test_file ) ) {
					
						if ( !@unlink( $test_file ) ) {
					
							$this->log( 'details', sprintf( __('Error #564634. Unable to delete test file (%s)!','it-l10n-backupbuddy' ), $test_file ) );
						
						}
						
						// The zip operation was successful - implies can zip and unzip and hence archive, check and list
						$this->_method_details[ 'attr' ][ 'is_zipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_unzipper' ] = true;
						$this->_method_details[ 'attr' ][ 'is_archiver' ] = true;
						$this->_method_details[ 'attr' ][ 'is_checker' ] = true;
						$this->_method_details[ 'attr' ][ 'is_lister' ] = true;
						$this->_method_details[ 'attr' ][ 'is_commenter' ] = true;
						$this->_method_details[ 'attr' ][ 'is_unarchiver' ] = true;
						$this->_method_details[ 'attr' ][ 'is_extractor' ] = true;
						
						$this->log( 'details', __('PclZip test PASSED.','it-l10n-backupbuddy' ) );
						$result = true;
						
					} else {
					
						$this->log( 'details', __('PclZip test FAILED: Zip file not found.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
					
				} else {
				
					$error_string = $za->errorInfo( true );
					$this->log( 'details', __('PclZip test FAILED: Unable to create/open zip file.','it-l10n-backupbuddy' ) );
					$this->log( 'details', __('PclZip Error: ','it-l10n-backupbuddy' ) . $error_string );
					$result = false;
					
				}
				
			}
		  	
		  	if ( null != $za ) { unset( $za ); }
		  	
		  	return $result;
		  	
		}
		
		/**
		 *	create()
		 *	
		 *	A function that creates an archive file
		 *	
		 *	The $excludes will be a list or relative path excludes
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to create
		 *	@param		string	$dir			Full path of directory to add to ZIP Archive file
		 *	@parame		array	$excludes		List of either absolute path exclusions or relative exclusions
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@return		bool					True if the creation was successful, false otherwise
		 *
		 */
		public function create( $zip, $dir, $excludes, $tempdir ) {
		
			$za = null;
			$result = false;
			$exitcode = 255;
			$output = array();
			$temp_zip = '';
			$excluding_additional = false;
			$exclude_count = 0;
			$exclusions = array();
			$temp_file_compression_threshold = 5;
			$pre_add_func = '';
			$have_zip_errors = false;
			$zip_errors_count = 0;
			$zip_errors = array();
			$have_zip_warnings = false;
			$zip_warnings_count = 0;
			$zip_warnings = array();
			$have_zip_additions = false;
			$zip_additions_count = 0;
			$zip_additions = array();
			$have_zip_debug = false;
			$zip_debug_count = 0;
			$zip_debug = array();
			$have_zip_other = false;
			$zip_other_count = 0;
			$zip_other = array();
			$zip_skipped_count = 0;
			$logfile_name = '';
			$contentfile_name = '';
			$contentfile_fp = 0;
			$have_more_content = true;
			$zip_ignoring_symlinks = false;

			$zm = null;
			$lister = null;
			$visitor = null;
			$logger = null;
			$total_size = 0;
			$total_count = 0;
			$the_list = array();
			$count_ignored_symdirs = 0;
			$saved_ignored_symdirs = array();
			$zip_error_encountered = false;
			$zip_period_expired = false;
			
			// The basedir must have a trailing normalized directory separator
			$basedir = ( rtrim( trim( $dir ), self::DIRECTORY_SEPARATORS ) ) . self::NORM_DIRECTORY_SEPARATOR;
		
			// Normalize platform specific directory separators in path
			$basedir = str_replace( DIRECTORY_SEPARATOR, self::NORM_DIRECTORY_SEPARATOR, $basedir );
			
			// Ensure no stale file information
			clearstatcache();
			
			// Create the zip monitor function here	
			// Zip monitor will inherit the logger from this object	
			$zm = new pb_backupbuddy_zip_monitor( $this );
//			$zm->set_burst_max_period( self::ZIP_PCLZIP_DEFAULT_BURST_MAX_PERIOD )->set_burst_threshold_period( 'auto' )->log_parameters();
			$zm->set_burst_size_min( $this->get_min_burst_content() )
			->set_burst_size_max( $this->get_max_burst_content() )
			->set_burst_current_size_threshold( $zm->get_burst_size_min() )
			->log_parameters();


			// Note: could enforce trailing directory separator for robustness
			if ( empty( $tempdir ) || !file_exists( $tempdir ) ) {
			
				// This breaks the rule of single point of exit (at end) but it's early enough to not be a problem
				$this->log( 'details', __('Zip process reported: Temporary working directory not available: ','it-l10n-backupbuddy' ) . '`' . $tempdir . '`' );				
				return false;
				
			}
			
			// Log the temporary working directory so we might be able to spot problems
			$this->log( 'details', __('Zip process reported: Temporary working directory available: ','it-l10n-backupbuddy' ) . '`' . $tempdir . '`' );				
			
			$this->log( 'message', __('Zip process reported: Using Compatibility Mode.','it-l10n-backupbuddy' ) );
			
			// Notify the start of the step
			$this->log( 'details', sprintf( __('Zip process reported: Zip archive initial step started with step period threshold: %1$ss','it-l10n-backupbuddy' ), $this->get_step_period() ) );

			// Let's inform what we are excluding/including
			if ( count( $excludes ) > 0 ) {
			
				$this->log( 'details', __('Zip process reported: Calculating directories/files to exclude from backup (relative to site root).','it-l10n-backupbuddy' ) );
				
				foreach ( $excludes as $exclude ) {
				
					if ( !strstr( $exclude, 'backupbuddy_backups' ) ) {

						// Set variable to show we are excluding additional directories besides backup dir.
						$excluding_additional = true;
							
					}
						
					$this->log( 'details', __('Zip process reported: Excluding','it-l10n-backupbuddy' ) . ': ' . $exclude );
					
					$exclude_count++;
						
				}
				
			}
			
			if ( true === $excluding_additional ) {
			
				$this->log( 'message', __( 'Zip process reported: Excluding archives directory and additional directories defined in settings.','it-l10n-backupbuddy' ) . ' ' . $exclude_count . ' ' . __( 'total','it-l10n-backupbuddy' ) . '.' );
				
			} else {
			
				$this->log( 'message', __( 'Zip process reported: Only excluding archives directory based on settings.','it-l10n-backupbuddy' ) . ' ' . $exclude_count . ' ' . __( 'total','it-l10n-backupbuddy' ) . '.' );
				
			}


			$this->log( 'message', __( 'Zip process reported: Determining list of candidate files + directories to be added to the zip archive','it-l10n-backupbuddy' ) );

			// Now let's create the list of files and empty (vacant) directories to include in the backup.
			// Note: we can only include vacant directories (those that had no content in the first place).
			// An empty directory may have had content that was excluded but if we give this directory to
			// pclzip it automatically recurses down into it (we have no control over that) which would then
			// mess up the exclusions.
			
			$visitor = new pluginbuddy_zbdir_visitor_details( array( 'filename', 'directory', 'vacant', 'absolute_path', 'size' ) );
			
			$logger = new pluginbuddy_zipbuddy_logger( 'Zip process reported: ' );
			$visitor->set_logger( $logger );

			// Give the visitor our process monitor to be used to keep
			// the server alive as long as possible
			$visitor->set_process_monitor( $this->get_process_monitor() );
			
			$options = array( 'exclusions' => $excludes,
							  'pattern_exclusions' => array(),
							  'inclusions' => array(),
							  'pattern_inclusions' => array(),
							  'keep_tree' => false,
							  'ignore_symlinks' => $this->get_ignore_symlinks(),
							  'visitor' => $visitor );
			
			try {
			
				$lister = new pluginbuddy_zbdir( $basedir, $options );

				// As we are not keeping the tree we haev already done the visitor pass
				// as the tree was built so our visitor contains all the information we
				// need so we can destroy the lister object
				unset( $lister );
				$result = true;
				
				$this->log( 'message', __( 'Zip process reported: Determined list of candidate files + directories to be added to the zip archive','it-l10n-backupbuddy' ) );

			} catch (Exception $e) {
			
				// We couldn't build the list as required so need to bail
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('Zip process reported: Unable to determine list of candidates files + directories for backup - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

				// TODO: Should do some cleanup of any temporary directory, visitor, etc. but not for now
				$result = false;
			}

			// In case that took a while use the helper to try and keep the process alive
			$zm->burst_end();
			$this->get_process_monitor()->checkpoint();

			if ( true === $result ) {	
					
				// Now we have our flat file/directory list from the visitor - remember we didn't
				// keep the tree as we shouldn't need it for anything else as we can get all we need
				// from the visitor. First create our list. We have to do this first because we need to
				// know if we are bypassing ignored symdirs (not including them in the list) so we can
				// add the number of these to the total number of items from our simple (vacant) directory
				// and file count total so that the final stats of what was actually added and the details
				// of what we didn't add will all add up - sounds convoluted, well that's because it is...
				// Main thing is to filter non-vacant directories
				$the_list = $visitor->get_as_array( array( 'filename', 'directory', 'vacant', 'absolute_path', 'size' ) );

				foreach ( $the_list as $key => $value ) {
					if ( false === $value[ 'directory' ] ) {
						// Not a directory so must be a file (whether symlink or not) so always
						// keep it (don't remove from list)
					} elseif ( ( true === $value[ 'directory' ] ) && ( isset( $value[ 'vacant' ] ) && ( true === $value[ 'vacant' ] ) ) ) {
						// It's a directory and has the vacant attribute and it is vacant so we can
						// safely add it.
					} elseif ( ( true === $value[ 'directory' ] ) && ( isset( $value[ 'vacant' ] ) ) ) {
						// It's a directory with the vacant attribute set but not set to true (so implying
						// false) in which case we need to remove it from the list.
						// We cannot add non-vacant directories because pclzip will recurse into them.
						// If there are any files within the directory included then these will cause
						// directory to be created on unzip so we do not need the actual directory entry.
						unset( $the_list[ $key ] );
					} elseif ( ( true === $value[ 'directory' ] ) && !( isset( $value[ 'vacant' ] ) ) ) {
						// If the directory does not have the vacant attribute that is because it is
						// a symlink dir that wasn't followed because of configuration. If this is the
						// case then the list does not contain any files under this directory.
						// We will leave the item in the master list for now but we _must_ not pass
						// it to pclzip because pclzip will recurse down into it befre we have any
						// chance to stop it. For single-burst/single-step zip building we were able
						// to do fancy stuff with skipping symdirs and then remembering the prefix to
						// test against other files to skip those if under the symdir but with multi-burst/
						// multi-step this becomes way too complicated to maintain that state information
						// so we'll now have to skip them at the point of adding to the burst list
						// and we can save and then log them at the end of the burst.
					} 
				}

				// Save the total count of items to be added
				$total_count = count( $the_list );
				$this->log( 'details', sprintf( __('Zip process reported: %1$s (directories + files) will be requested to be added to backup zip archive','it-l10n-backupbuddy' ), $total_count ) );
				//$zm->set_options( array( 'directory_count' => ( $visitor->count( 'directory' => true, 'vacant' => true ) + count( $saved_ignored_symdirs ), 'file_count' => $visitor->count( array( 'directory' => false ) ) ) );
				
				// Find the sum total size of all non-directory (i.e., file) items
				// Make sure we can handle >2GB on a 32 bit PHP by using double
				// Note: Currently assuming no single item >2GB size as using the
				// basic size as returned by stat(). We'll likely need to change to
				// use our stat() to allow for up to 4GB item size on 32 bit PHP
				$total_size = (double)0;
				foreach ( $the_list as $the_item ) {
					if ( false === $the_item[ 'directory' ] ) {
						$total_size += (int)$the_item[ 'size' ];
					}
				}
				
				$this->log( 'details', sprintf( __('Zip process reported: %1$s bytes will be requested to be added to backup zip archive','it-l10n-backupbuddy' ), number_format( $total_size, 0, ".", "" ) ) );
				//$zm->set_options( array( 'content_size' => $total_size ) );

				// This is where we want to save the contents list
				$contentfile_name = $tempdir . self::ZIP_CONTENT_FILE_NAME;

				// Now push the list to a file
				$this->log( 'details', sprintf( __('Zip process reported: Writing zip content list to file: %1$s','it-l10n-backupbuddy' ), $contentfile_name ) );

				try {
					
					$contentfile = new SplFileObject( $contentfile_name, "wb" );
					
					// Simple way to ensure we don't get a final empty line in file that messes up
					// the read and json_decode. We could later use different ways such as using
					// marker arrays at start/end so we can include other stuff maybe but this is
					// all we need for now.
					$prefix = '';
										
					foreach ( $the_list as $the_item ) {
					
						$encoded_item = serialize( $the_item );
					
 						// Need to bail out if it looks like we failed to encode the data
						if ( 0 === strlen( $encoded_item ) ) {
						
							throw new Exception( 'Serialization of content list data failed' );
						
						}
						
						$bytes_written = $contentfile->fwrite( $prefix . $encoded_item );
						
						// Be very careful to make sure we had a valid write - in paticular
						// make sure we didn't write 0 bytes since even an empty line from the
						// array should have the PHP_EOL bytes written 
						if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) || ( strlen( $prefix ) >= $bytes_written ) ) {
							throw new Exception( 'Failed to append to content file during creation' );
						}

						$prefix = PHP_EOL;

					}
				
				} catch ( Exception $e ) {
					
					// Something fishy - we should have been able to open and
					// write to the content file...
					$error_string = $e->getMessage();
					$this->log( 'details', sprintf( __('Zip process reported: Zip content list file could not be created/appended-to - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

					// Temporary measure for bailing out on problems creting/appending content file
					$result = false;

				}
				
				// We are done with populating the content file
				unset( $contentfile );

				// Retain this for reference for now
				//file_put_contents( ( dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $the_list, true ) );
			
				// Presently we don't need the visitor any longer so we can free up some
				// memory by deleting
				unset( $visitor );

 				// We need to force the pclzip library to load at this point if it is
 				// not already loaded so that we can use defined constants it creates
 				// but we don't actually want to create a zip archive at this point.
 				// We can also use this as an early test of being able to use the library
 				// as an exception will be raised if the class does not exist.
 				// Note that this is only really required when zip method caching is
 				// in use, if this is disabled then the library would already have been
 				// loaded by the method testing.			
 				try {
 					
 					// Select to just load the pclzip library only and tell it the
 					// temporary directory to use if required (this is only possible
 					// if it hasn't already been loaded and the temp dir defined)
 					$za = new pluginbuddy_PclZip( "", true, $tempdir );
 					
 					// We have no purpose for this object any longer, the library
 					// will remain loaded
 					unset( $za );
 					$result = true;
 				
 				} catch ( Exception $e ) {
 			
 					// Something fishy - the methods indicated pclzip but we couldn't find the class
 					$error_string = $e->getMessage();
 					$this->log( 'details', sprintf( __('Zip process reported: pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
 					$result = false;
 				
 				}
				
			}
			
			// Only continue if we have a valid list
			// This isn't ideal at present but will suffice
			if ( true === $result ) {
			
				// Basic argument list (will be used for each burst)
				$arguments = array();
				array_push( $arguments, PCLZIP_OPT_REMOVE_PATH, $dir );				

				if ( true !== $this->get_compression() ) {
				
					// Note: don't need to force use of temporary files for compression
					$this->log( 'details', __('Zip process reported: Zip archive creation compression disabled based on settings.','it-l10n-backupbuddy' ) );
					array_push( $arguments, PCLZIP_OPT_NO_COMPRESSION );
					
				} else {
	
					// Note: force the use of temporary files for compression when file size exceeds given value.
					// This over-rides the "auto-sense" which is based on memory_limit and this _may_ indicate a
					// memory availability that is higher than reality leading to memory allocation failure if
					// trying to compress large files. Set the threshold low enough (specify in MB) so that except in
					// The tightest memory situations we should be ok. Could have option to force use of temporary
					// files regardless.
					$this->log( 'details', __('Zip process reported: Zip archive creation compression enabled based on settings.','it-l10n-backupbuddy' ) );
					array_push( $arguments, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $temp_file_compression_threshold );
						
				}
				
				// Check if ignoring (not following) symlinks
				if ( true === $this->get_ignore_symlinks() ) {
			
					// Want to not follow symlinks so set flag for later use
					$zip_ignoring_symlinks = true;
				
					$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will be ignored based on settings.','it-l10n-backupbuddy' ) );

				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
			
				// Check if we are ignoring warnings - meaning can still get a backup even
				// if, e.g., some files cannot be read
				if ( true === $this->get_ignore_warnings() ) {
				
					// Note: warnings are being ignored but will still be gathered and logged
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
					
				} else {
				
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
				
		
				// Set up the log file - for each file added we'll append a log entry to the
				// log file that maps the result of the add to the nearest equivalent command
				// line zip log entry and this allows us to eventually process and present the
				// relevant log details in a consistent manner across different methods which
				// should cut down on confusion a bit. Note that we'll also try and map the
				// pclzip exit codes to equivalent zip utility codes but we may have to still
				// maintain our own code space for those that cannot be mapped - just have to
				// see how it goes.
				// This approach gives us a unified process and also makes it easy to handle
				// the log over multiple steps if required.
				$logfile_name = $tempdir . self::ZIP_LOG_FILE_NAME;
				
				// Temporary zip file is _always_ located in the temp dir now and we move it
				// to the final location after completion if it is a good completion
				$temp_zip = $tempdir . basename( $zip );
			
				// Use anonymous function to weed out the unreadable and non-existent files (common reason for failure)
				// and possibly symlinks based on user settings.
				// PclZip will record these files as 'skipped' in the file status and we can post-process to determine
				// if we had any of these and hence either stop the backup or continue dependent on whether the user
				// has chosen to ignore warnings or not and/or ignore symlinks or not.
				// Unfortunately we cannot directly tag the file with the reason why it has been skipped so when we
				// have to process the skipped items we have to try and work out why it was skipped - but shouldn't
				// be too hard.
				// TODO: Consider moving this into the PclZip wrapper and have a method to set the various pre/post
				// functions or select predefined functions (such as this).
				if ( true ) {
				
					// Note: This could be simplified - it's written to be extensible but may not need to be
					$args = '$event, &$header';
					$code = '';
//					$code .= 'static $symlinks = array(); ';
					$code .= '$result = true; ';
					
					// Handle symlinks - keep the two cases of ignoring/not-ignoring separate for now to make logic more
					// apparent - but could be merged with different conditional handling
					// For a valid symlink: is_link() -> true; is_file()/is_dir() -> true; file_exists() -> true
					// For a broken symlink: is_link() -> true; is_file()/is_dir() -> false; file_exists() -> false
					// Note: pclzip first tests every file using file_exists() before ever trying to add the file so
					// for a broken symlink it will _always_ error out immediately it discovers a broken symlink so
					// we never have a chance to filter these out at this stage.
					// Note: now that we are generating the file list and not following symlinks at that stage we
					// never have the situation where we need to remember a symdir prefix to filter out dirs/files
					// under that symdir (once you have passed "through" a dir symlink the dirs/files under that
					// do not register as symlinks because they themselves are not so previously when pclzip was
					// generating the list internally we had to make sure we skipped such dirs/files based on
					// there being a dir symlink as a prefix to the dir/file path).
					if ( true === $zip_ignoring_symlinks ) {
					
						// If it's a symlink or it's neither a file nor a directory then ignore it. A broken symlink
						// will never get this far because pclzip will have choked on it
						$code .= 'if ( ( true === $result ) && !( @is_link( $header[\'filename\'] ) ) ) { ';
						$code .= '    if ( @is_file( $header[\'filename\'] ) || @is_dir( $header[\'filename\'] ) ) { ';
						$code .= '        $result = true; ';
// 						$code .= '        foreach ( $symlinks as $prefix ) { ';
// 						$code .= '            if ( !( false === strpos( $header[\'filename\'], $prefix ) ) ) { ';
// 						$code .= '                $result = false; ';
// 						$code .= '                break; ';
// 						$code .= '             } ';
// 						$code .= '        } ';
						$code .= '    } else { ';
//						$code .= '        error_log( "Neither a file nor a directory (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '        $result = false; ';
						$code .= '    } ';
						$code .= '} else { ';
//						$code .= '    error_log( "File is a symlink (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
//						$code .= '    $symlinks[] = $header[\'filename\']; ';
//						$code .= '    error_log( "Symlinks Array: \'" . print_r( $symlinks, true ) . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
						
					} else {
					
						// If it's neither a file nor directory then ignore it - a valid symlink will register as a file
						// or directory dependent on what it is pointing at. A broken symlink will never get this far.
						// because pclzip will have barfed on its file_exists() check before calling the pre-add. We may
						// choose later to catch this earlier during the list creation I think.
						$code .= 'if ( ( true === $result ) && ( @is_file( $header[\'filename\'] ) || @is_dir( $header[\'filename\'] ) ) ) { ';
						$code .= '    $result = true; ';
						$code .= '} else { ';
//						$code .= '    error_log( "Neither a file nor a directory (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
						
					}
					
					// Add the code block for ignoring unreadable files
					if ( true ) {
					
						$code .= 'if ( ( true === $result ) && ( @is_readable( $header[\'filename\'] ) ) ) { ';
						$code .= '    $result = true; ';
						$code .= '} else { ';
//						$code .= '    error_log( "File not readable: \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
					
					}
					
					// Return true (to include file) if file passes conditions otherwise false (to skip file) if not
					$code .= 'return ( ( true === $result ) ? 1 : 0 ); ';

					$pre_add_func = create_function( $args, $code );
				
				}
				
				// If we had cause to create a pre add function then add it to the argument list here
				if ( !empty( $pre_add_func ) ) {
				
					array_push( $arguments, PCLZIP_CB_PRE_ADD, $pre_add_func );
		
				}
				
				// Add a post-add function for progress monitoring, usage data monitoring,
				// burst handling and server tickling - using the zip helper object
				// we created earlier
				$post_add_func = '';
								
// 				if (true) {
// 				
// 					$args = '$event, &$header';
// 					$code = '';
// 					$code .= '$result = true; ';
// 					$code .= '$zm = pb_backupbuddy_pclzip_helper::get_instance();';
// 					$code .= '$result = $zm->event_handler( $event, $header );';
// 					$code .= 'return $result;';
// 				
// 					$post_add_func = create_function( $args, $code );
// 				
// 				}

				// If we had cause to create a pre add function then add it to the argument list here
				if ( !empty( $post_add_func ) ) {
				
					array_push( $arguments, PCLZIP_CB_POST_ADD, $post_add_func );
		
				}

				// Remember our "master" arguments
				$master_arguments = $arguments;

				// Use this to memorise the worst exit code we had (where we didn't immediately
				// bail out because it signalled a bad failure)
				$max_exitcode = 0;
				
				// Do this as close to when we actually want to start monitoring usage
				// Maybe this is redundant as we have already called this in the constructor.
				// If we want to do this then we have to call with true to reset monitoring to
				// start now.
				$this->get_process_monitor()->initialize_monitoring_usage();
				
				// Now we have built our common arguments and we have the list defined we can
				// start on the bursts. Note that each burst will either succeed with an array
				// output or will fail and no array. When we get an array we will iterate over
				// it and generate log file entries. For case where we have a non-fatal warning
				// condition we change the actual pclzip exit code to be the sam eas the zip
				// utility exit code (18) and this lets us handle the outcome the same way. In
				// the case of no array but an error code we map that to an equivalent zip utility
				// exit code (as much as possible) and then we'll drop out with that and a
				// logged error that the log file processing will pick up.
				
				// Now we have our command prototype we can start bursting
				// Simply build a burst list based on content size. Currently no
				// look-ahead so the size will always exceed the current size threshold
				// by some amount. May consider using a look-ahead to see if the next
				// item would exceed the threshold in which case don't add it (unless it
				// would be the only content in which case have to add it but also log
				// a warning).
				// We'll stop either when noting more to add or we have exceeded our step
				// period or we have encountered an error.
				// Note: we might bail out immediately if previous processing has already
				// caused us to exceed the step period.
				while ( $have_more_content &&
						!( $zip_period_expired = $this->exceeded_step_period( $this->get_process_monitor()->get_elapsed_time() ) ) &&
						!$zip_error_encountered ) {
						
					clearstatcache();

					// Populate the content array for zip
					$ilist = array();

					// Keep track of any symdirs that are being ignored
					$saved_ignored_symdirs = array();
					
					// Tell helper that we are preparing a new burst
					$zm->burst_begin();
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
					$this->log( 'details', sprintf( __( 'Zip process reported: Current burst size threshold: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $zm->get_burst_current_size_threshold(), 0, ".", "" ) ) );

					// Open the content list file and seek to the "current" position. This
					// will be initially zero and then updated after each burst. For multi-step
					// it will be zero on the first step and then would be passed back in
					// as a parameter on subsequent steps based on where in the file the previous
					// step reached.
					// TODO: Maybe a sanity check to make sure position seems tenable
					try {
			
						$contentfile = new SplFileObject( $contentfile_name, "rb" );
						$contentfile->fseek( $contentfile_fp );

						// Helper keeps track of what is being added to the burst content and will
						// tell us when the content is sufficient for this burst based on it's
						// criteria - this can adapt to how each successive burst goes.
						while ( ( !$contentfile->eof() ) && ( false === $zm->burst_content_complete() ) ) {
					
							// Should be at least one item to grab from the list and then move to next
							// and remember it for if we drop out because burst content complete, in
							// that case we'll return to that point in the file at the next burst start.
							// Check for unserialize failure and bail
							$item = @unserialize( $contentfile->current() );

							if ( false === $item ) {
							
								throw new Exception( 'Unserialization of content list data failed: `' . $contentfile->current() . '`' );
								
							}
							
							$contentfile->next();
						
							$file = $item[ 'absolute_path' ] . $item[ 'filename' ];
						
							// Filter out symdirs if we are ignoring symlinks and record them to log
							// Because of the way the list creation works this condition indicates
							// a symlink directory only in the case of ignorign symlinks. If we
							// were not ignoring symlinks then the "vacant" attribute would be set
							// if the directory were vacant or alternatively this entry would have
							// already been filtered out if the symlinked directory were not vacant.
							// So we must filter it out and move on
							if ( ( true === $item[ 'directory' ] ) && !( isset( $item[ 'vacant' ] ) ) ) {	
						
								$saved_ignored_symdirs[] = $file;
							
							} else {
						
								// We shouldn't have any empty items here as we should have removed them
								// earlier, but just in case...
								if ( !empty( $file ) ) {
								
									$ilist[] = $file;
							
									// Call the helper event handler as we add each file to the list
									$zm->burst_content_added( $item );
							
								}
						
							}
						
						}
					
						// Burst list is completed by way of end of content list file or size threshold
						if ( !$contentfile->eof() ) {
					
							// We haven't exhausted the content list yet so remember where we
							// are at for next burst
							$contentfile_fp = $contentfile->ftell();
					
						} else {
					
							// Exhausted the content list so make sure we drop out after this burst
							// if we don't break out of the loop due to a zip error or reached step
							// duration limit
							$have_more_content = false;
					
						}
				
						// Finished one way or another so close content list file for this burst
						unset( $contentfile );
				
					} catch ( Exception $e ) {
			
						// Something fishy - we should have been able to open the content file...
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: Zip content list file could not be opened/read - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
						$exitcode = 255;	
						$zip_error_encountered = true;
						break;
				
					}

					// Retain this for reference for now
					//file_put_contents( ( dirname( $tempdir ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $ilist, true ) );
					
					// add() method will create archive file if it doesn't aleady exist
					//$command = 'add';
					$command = 'grow';
				
					// Now create our zip handler object for thsi burst
					// This should give us a new archive object, if not catch it and bail out
					// Note we previously loaded the library and defined the temporary directory
					try {
					
						$za = new pluginbuddy_PclZip( $temp_zip );
						$result = true;
				
					} catch ( Exception $e ) {
			
						// Something fishy - the methods indicated pclzip but we couldn't find the class
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
						$exitcode = 255;
						$zip_error_encountered = true;
						break;					
				
				
					}
					
					// Allow helper to check how the burst goes
					$zm->burst_start();
		
					// Create the argument list for this burst
					$arguments = array();
					array_push( $arguments, $ilist );
					$arguments = array_merge( $arguments, $master_arguments );
					
					// Showing the "master" arguments
					// First implode any embedded array in the argument list and truncate the result if too long
					// Assume no arrays embedded in arrays - currently no reason for that
					// Make sure that there are no non-printable characters (such as in pre- or post-add function
					// names created by create_function()) by replacing with "*" using preg_replace()
					// TODO: Make the summary length configurable so that can see more if required
					// TODO: Consider mapping pclzip argument identifiers to string representations for clarity
					$args = '$item';
					$code = 'if ( is_array( $item ) ) { $string_item = implode( ",", $item); return ( ( strlen( $string_item ) <= 50 ) ? preg_replace( "/[^[:print:]]/", "*", $string_item ) : "List: " . preg_replace( "/[^[:print:]]/", "*", substr( $string_item, 0, 50 ) ) . "..." ); } else { return preg_replace( "/[^[:print:]]/", "*", $item ); }; ';
					$imploder_func = create_function( $args, $code );
					$imploded_arguments = array_map( $imploder_func, $arguments );
				
					$this->log( 'details', sprintf( __( 'Zip process reported: Burst requests %1$s (directories + files) items with %2$s bytes of content to be added to backup zip archive', 'it-l10n-backupbuddy' ), $zm->get_burst_content_count(), $zm->get_burst_content_size() ) );

					$this->log( 'details', __( 'Zip process reported: ') . $this->get_method_tag() . __( ' command arguments','it-l10n-backupbuddy' ) . ': ' . implode( ';', $imploded_arguments ) );
				
					$zip_output = call_user_func_array( array( &$za, $command ), $arguments );

					// And now we can analyse what happened and plan for next burst if any
					$zm->burst_stop();
					
					// Wrap up the individual burst handling
					// Note: because we called exec we basically went into a wait condition and so (on Linux)
					// we didn't consume any max_execution_time so we never really have to bother about
					// resetting it. However, it is true that time will have elapsed so if this burst _does_
					// take longer than our current burst threshold period then max_execution_time would be
					// reset - but what this doesn't cover is a _cumulative_ effect of bursts and so we might
					// consider reworking the mechanism to monitor this separately from the individual burst
					// period (the confusion relates to this having originally applied to the time based
					// burst handling fro pclzip rather than teh size based for exec). It could also be more
					// relevant for Windows that doesn't stop the clock when exec is called.
					$zm->burst_end();	
					$this->get_process_monitor()->checkpoint();				
					
				  	// If the output is an array then we need to do a quick iteration over the output
				  	// in order to determine whetehr we need to change the exit code from 0 to any other
				  	// value (essentially to 18). The alternative is some messy stuff with iterating
				  	// around and doing stuff based on whether the log file is available or not. By
				  	// doing the preprocessing we can simply bail out at any point if the file cannot be
				  	// opened or if a write fails.
					if ( is_array( $zip_output ) ) {
				
						// Something reasonable happened
						// For now we'll assume everything rosy but if we find unreadable
						// files we'll modify the exit code
						$exitcode = 0;
					
						foreach ( $zip_output as $file ) {
						
							switch ( $file[ 'status' ] ) {
								case "ok":
									break;
								case "skipped":								
									// For skipped files need to determine why it was skipped
									if ( ( true === $zip_ignoring_symlinks ) && @is_link( $file[ 'filename' ] ) ) {						
										// Skipped because we are ignoring symlinks and this is a symlink.
										// This just handles files as we have previously filtered out symdirs							
									} else {						
										// Skipped because probably unreadable or non-existent (catch-all for now)
										// Change the exit code as this is a warning we want to catch later
										$exitcode = 18;								
									}
									break;
								case "filtered":
									// Log it and change exit code as this is a warning we want to catch later
									$exitcode = 18;
									break;
								case "filename_too_long":
									// Log it and change exit code as this is a warning we want to catch later
									$exitcode = 18;
									break;
								default:
									// Unknown status that we'll not consider for changing exit code
							}
			
						}
								
					} else {
				
						// Something really failed
						$exitcode = $za->errorCode();

					}
				  	
					// This method never directly produces a log file so we need to append the $zip_output array
					// to the log file - first invocation will create the file.
					// We now have our exit code so this iteration is simply to log output if we can.
					// If we fail to open the log file or there is a falure writing we can just bail out
				  	
					$this->log( 'details', sprintf( __('Zip process reported: Appending zip burst log detail to zip log file: %1$s','it-l10n-backupbuddy' ), $logfile_name ) );
			
				  	try {
				  	
						$logfile = new SplFileObject( $logfile_name, "ab" );
				
						// Now handle whether the outcome of the addition
						if ( is_array( $zip_output ) ) {
					
							// Something reasonable happened
							// Note if we have skipped any files
							$skipped_count = 0;
						
							// Now we need to put the log information to file
							// Need to process each status to determine how to log the outcome
							// for the item - in particular how to log skipped items as the item
							// status didn't allow us to give any particular reason for an item
							// being skipped, so we have to try and deduce that from information
							// about the item.
							// Our logs are mapped to format like zip utility uses so we can use
							// a common log processor subsequently.
							foreach ( $zip_output as $file ) {
							
								// Use this to amass what we want to write to log file
								$line = '';
					
								switch ( $file[ 'status' ] ) {
									case "ok":
										// Item was added ok
										$line = ( 'adding: ' . $file[ 'filename' ] );
										break;
									case "skipped":								
										// For skipped files need to determine why it was skipped
										if ( ( true === $zip_ignoring_symlinks ) && @is_link( $file[ 'filename' ] ) ) {
							
											// Skipped because we are ignoring symlinks and this is a symlink.
											// This just handles files as we have previously filtered out symdirs
											// Just treat as an informational
											$line = ( 'zip info: ignored symlink: ' . $file[ 'filename' ] );
								
										} else {
							
											// Skipped because probably unreadable or non-existent (catch-all for now)
											$line = ( 'zip warning: could not open for reading: ' . $file[ 'filename' ] );
									
										}
										$skipped_count++;
										break;
									case "filtered":
										// Log that it was filtered for some reason
										$line = ( 'zip warning: filtered: ' . $file[ 'filename' ] );
										// This counts as a skip because we didn't add it
										$skipped_count++;
										break;
									case "filename_too_long":
										// Log that the given name was too long
										$line = ( 'zip warning: filename too long: ' . $file[ 'filename' ] );
										// This counts as a skip because we didn't add it
										$skipped_count++;
										break;
									default:
										// Hmm, have to assume something was not right so we'll log it as
										// a warning to be on the safe side
										$line = ( 'zip warning: unknown add status: ' . $file[ 'status' ] . ': ' . $file[ 'filename' ] );

								}
				
								// Now try and commit the log line to file
								$bytes_written = $logfile->fwrite( $line . PHP_EOL );
								
								// Be very careful to make sure we had a valid write - in paticular
								// make sure we didn't write 0 bytes since even an empty line from the
								// array should have the PHP_EOL bytes written 
								if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
									throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
								}

							}
							
							// Now assemble some optional lines
							$lines = array();
					
							// Now also add in INFORMATIONALs for any ignored symdirs because these would not have
							// been included in the build list. They were not included because pclzip would have attempted
							// to follow them and then we would have had to "filter" them and all entries that pclzip
							// would have created under them which is just a wster of time - best to not include at all
							// at tell the user now that we didnt include them
							foreach ( $saved_ignored_symdirs as $ignored_symdir ) {
				
								$lines[] = ( 'zip info: ignored symlink: ' . $ignored_symdir . self::NORM_DIRECTORY_SEPARATOR );
				
							}
				
							// Now add log entry related to skiped files if we did skip any
							// Make this look like zip utility output to some extent
							if ( 0 != $skipped_count ) {
					
								$lines[] = ( 'zip warning: Not all files were readable' );
								$lines[] = ( ' skipped:   ' . $skipped_count );
					
							}
						
							foreach ( $lines as $line ) {
							
								$bytes_written = $logfile->fwrite( $line . PHP_EOL );
								
								// Be very careful to make sure we had a valid write - in paticular
								// make sure we didn't write 0 bytes since even an empty line from the
								// array should have the PHP_EOL bytes written 
								if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
									throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
								}
							
							}
							
						} else {
					
							// Have to map exit code and warn that not all warnings/etc may be logged
					
							// Something really failed
							$bytes_written = $logfile->fwrite( 'zip error: ' . $za->errorInfo( true ) . PHP_EOL );
				
							// Be very careful to make sure we had a valid write - in paticular
							// make sure we didn't write 0 bytes since even an empty line from the
							// array should have the PHP_EOL bytes written 
							if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
								throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
							}

						}
					
						// Put the log file away - safe even if we failed to get a logfile
						unset( $logfile );
					
						// And throw away the output result as we have no further use for it
						unset( $zip_output );
					
					} catch ( Exception $e ) {
				
						// Something fishy - we should have been able to open and
						// write to the log file...
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened/appended-to - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

						// Put the log file away - safe even if we failed to get a logfile
						unset( $logfile );
					
						// And throw away the output result as we cannot use it
						unset( $zip_output );
					
					}
					
					// Put the zip archive away					
				  	unset( $za );

					// Put the log file away - safe even if we failed to get a logfile
					unset( $logfile );
					
					// Report progress at end of burst
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					clearstatcache();
					
					// Keep a running total of the backup file size (this is temporary code)
					// Using our stat() function in case file size exceeds 2GB on a 32 bit PHP system
					$temp_zip_stats = pluginbuddy_stat::stat( $temp_zip );			
					// Only log anything if we got some valid file stats
					if ( false !== $temp_zip_stats ) {			
						$this->log( 'details', sprintf( __( 'Zip process reported: Accumulated zip archive file size: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $temp_zip_stats[ 'dsize' ], 0, ".", "" ) ) );			
					}
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Ending burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
						
					// Now work out the result of that burst and what to do
					// If it is an array then append to the cumulative array and continue
					// otherwise we have an error and we must bail out. So we don't need
					// the complexity of exec to handle non-fatal errors (as warnings)
					// Note: in the multi-burst case we will still have the results array
					// accumulated from previous bursts so we _could_ chose to handle that
					// but for now we'll just throw that away. At some point we can thnk about
					// handling the output array.

					// We have to check the exit code to decide whether to keep going ot bail out (break).
					// If we get a 0 exit code ot 18 exit code then keep going and remember we got the 18
					// so that we can emit that as the final exit code if applicable. If we get any other
					// exit code then we must break out immediately.					
					if ( ( 0 !== $exitcode ) && ( 18 !== $exitcode ) ) {
						// Zip failure of some sort - must bail out with current exit code
						$zip_error_encountered = true;
					} else {
						// Make sure exit code is always the worst we've had so that when
						// we've done our last burst we drop out with the correct exit code set
						// This is really to make sure we drop out with exit code 18 if we had
						// this in _any_ burst as we would keep going and subsequent burst(s) may
						// return 0. If we had any other non-zero exit code it would be a "fatal"
						// error and we would have dropped out immediately anyway.
						$exitcode = ( $max_exitcode > $exitcode ) ? $max_exitcode : ( $max_exitcode = $exitcode ) ;
					}

					// Now inject a little delay until the next burst. This may be required to give the
					// server time to catch up with finalizing file creation and/or it may be required to
					// reduce the average load a little so there isn't a sustained "peak"
					// Theoretically a sleep could be interrupted by a signal and it would return some
					// non-zero value or false - but if that is the case it probably signals something
					// more troubling so there is little point in tryng to "handle" such a condition here.
					if ( 0 < ( $burst_gap = $this->get_burst_gap() ) ) {
					
						$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst gap delay of: %1$ss', 'it-l10n-backupbuddy' ), $burst_gap ) );
						sleep( $burst_gap );
						
					}
				
				}

				// Exited the loop for some reason so decide what to do now.
				// If we didn't exit because of exceeding the step period then it's a
				// normal exit and we'll process accordingly and end up returning true
				// or false. If we exited because of exceeding step period then we need
				// to return the current state array to enable next iteration to pick up
				// where we left off.
				// Note: we might consider having the zip helper give us a state to
				// restore on it when we create one again - but for now we'll not do that
				if ( $zip_period_expired ) {
					
					// Report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step terminated after %1$ss, continuation step will be scheduled','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );

					// Need to set up the state information we'll need to tell the next
					// loop how to set things up to continue. Next time around if another
					// step is required then some of these may be changed and others may
					// stay the same.
					// Note: the method tag 'mt' is used to tell zipbuddy exactly which
					// zipper to use, the one that was picked first time through.
					
					$state = array( 'name' => pluginbuddy_zipbuddy::STATE_NAME_IN_PROGRESS,
									'id' => pluginbuddy_zipbuddy::STATE_ID_IN_PROGRESS,
									'zipbuddy' => array( 'mt' => $this->get_method_tag(),
														),
									'zipper' => array(	'fp' => $contentfile_fp,
														'mec' => $max_exitcode,
														'sp' => $this->get_step_period(),
														'root' => $dir,
														'ts' => $total_size,
														'tc' => $total_count,
														),
									'helper' => array(	'dc' => $zm->get_added_dir_count(),
														'fc' => $zm->get_added_file_count(),
														),
									);
									
					// Now we can return directly as we haev nothing to clear up
					return $state;
					
				}
			
				// Convenience for handling different scanarios
				$result = false;
				
				// We can report how many dirs/files added				
				$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items to be added to backup zip archive (final)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

				// Work out percentage progress on items
				if ( 0 < $total_count ) {
				
					$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (final)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );

				}
					
				// Always logging to file one way or another
				// Always scan the output/logfile for warnings, etc. and show warnings even if user has chosen to ignore them
				try {
			
					$logfile = new SplFileObject( $logfile_name, "rb" );
				
					while( !$logfile->eof() ) {
				
						$line = $logfile->current();
						$id = $logfile->key(); // Use the line number as unique key for later sorting
						$logfile->next();
					
						if ( preg_match( '/^\s*(zip warning:)/i', $line ) ) {
					
							// Looking for specific types of warning - in particular want the warning that
							// indicates a file couldn't be read as we want to treat that as a "skipped"
							// warning that indicates that zip flagged this as a potential problem but
							// created the zip file anyway - but it would have generated the non-zero exit
							// code of 18 and we key off that later. All other warnings are not considered
							// reasons to return a non-zero exit code whilst still creating a zip file so
							// we'll follow the lead on that and not have other warning types halt the backup.
							// So we'll try and look for a warning output that looks like it is file related...
							if ( preg_match( '/^\s*(zip warning:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
								// Matched to what looks like a file related warning so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "could not open for reading:":										
										$zip_warnings[ self::ZIP_WARNING_SKIPPED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "filtered:":										
										$zip_warnings[ self::ZIP_WARNING_FILTERED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "filename too long:":										
										$zip_warnings[ self::ZIP_WARNING_LONGPATH ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "unknown add status:":										
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "name not matched:":										
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
										$zip_warnings_count++;
								}
						
							} else {
						
								// Didn't match to what would look like a file related warning so count it regardless
								$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
								$zip_warnings_count++;
							
							}
						
						} elseif ( preg_match( '/^\s*(zip info:)/i', $line ) ) {
						
							// An informational may have associated reason and filename so
							// check for that
							if ( preg_match( '/^\s*(zip info:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
								// Matched to what looks like a file related info so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "ignored symlink:":										
										$zip_other[ self::ZIP_OTHER_IGNORED_SYMLINK ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
								}
						
							} else {
						
								// Didn't match to what would look like a file related info so count it regardless
								$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
								$zip_other_count++;
							
							}
						
						} elseif ( preg_match( '/^\s*(zip error:)/i', $line ) ) {
					
							$zip_errors[ $id ] = trim( $line );
							$zip_errors_count++;
					
						} elseif ( preg_match( '/^\s*(adding:)/i', $line ) ) {
					
							// Currently not processing additions entried
							//$zip_additions[] = trim( $line );
							//$zip_additions_count++;
					
						} elseif ( preg_match( '/^\s*(sd:)/i', $line ) ) {
					
							$zip_debug[ $id ] = trim( $line );
							$zip_debug_count++;
							
						} elseif ( preg_match( '/^.*(skipped:)\s*(?P<skipped>\d+)/i', $line, $matches ) ) {
						
							// Each burst may have some skipped files and each will report separately
							if ( isset( $matches[ 'skipped' ] ) ) {
								$zip_skipped_count += $matches[ 'skipped' ];
							}
							
						} else {
					
							// Currently not processing other entries
							//$zip_other[] = trim( $line );
							//$zip_other_count++;
					
						}
					
					}
				
					unset( $logfile );
					@unlink( $logfile_name );
				
				} catch ( Exception $e ) {
			
					// Something fishy - we should have been able to open the log file...
					$error_string = $e->getMessage();
					$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				
				}

				// Set convenience flags			
				$have_zip_warnings = ( 0 < $zip_warnings_count );
				$have_zip_errors = ( 0 < $zip_errors_count );
				$have_zip_additions = ( 0 < $zip_additions_count );
				$have_zip_debug = ( 0 < $zip_debug_count );
				$have_zip_other = ( 0 < $zip_other_count );
			
				// Always report the exit code regardless of whether we might ignore it or not
				$this->log( 'details', __('Zip process reported: Zip process exit code: ','it-l10n-backupbuddy' ) . $exitcode );
	
				// Always report the number of warnings - even just to confirm that we didn't have any
				$this->log( 'details', sprintf( __('Zip process reported: %1$s warning%2$s','it-l10n-backupbuddy' ), $zip_warnings_count, ( ( 1 == $zip_warnings_count ) ? '' : 's' ) ) );
				
				// Always report warnings regardless of whether user has selected to ignore them
				if ( true === $have_zip_warnings ) {
			
					$this->log_zip_reports( $zip_warnings, self::$_warning_desc, "WARNING", self::MAX_WARNING_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_WARNINGS_FILE_NAME );

				}
			
				// Always report other reports regardless
				if ( true === $have_zip_other ) {
			
					// Only report number of informationals if we have any as they are not that important
					$this->log( 'details', sprintf( __('Zip process reported: %1$s information%2$s','it-l10n-backupbuddy' ), $zip_other_count, ( ( 1 == $zip_other_count ) ? 'al' : 'als' ) ) );

					$this->log_zip_reports( $zip_other, self::$_other_desc, "INFORMATION", self::MAX_OTHER_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_OTHERS_FILE_NAME );

				}
			
				// See if we can figure out what happened - note that $exitcode could be non-zero for actionable warning(s) or error
				// if ( (no zip file) or (fatal exit code) or (not ignoring warnable exit code) )
				// TODO: Handle condition testing with function calls based on mapping exit codes to exit type (fatal vs non-fatal)
				if ( ( ! @file_exists( $temp_zip ) ) ||
					 ( ( 0 != $exitcode ) && ( 18 != $exitcode ) ) ||
					 ( ( 18 == $exitcode ) && !$this->get_ignore_warnings() ) ) {
				
					// If we have any zip errors reported show them regardless
					if ( true == $have_zip_errors ) {
					
						$this->log( 'details', sprintf( __('Zip process reported: %1$s error%2$s','it-l10n-backupbuddy' ), $zip_errors_count, ( ( 1 == $zip_errors_count ) ? '' : 's' )  ) );
					
						foreach ( $zip_errors as $line ) {
						
							$this->log( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
						
						}
					
					}
					
					// Report whether or not the zip file was created (this will always be in the temporary location)			
					if ( ! @file_exists( $temp_zip ) ) {
					
						$this->log( 'details', __( 'Zip process reported: Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
						
					} else {
						
						$this->log( 'details', __( 'Zip process reported: Zip Archive file created but with errors/actionable-warnings so will be deleted - check process exit code and warnings.','it-l10n-backupbuddy' ) );
	
					}
					
					// The operation has failed one way or another. Note that for pclzip the zip file is always created in the temporary
					// location regardless of whether the user selected to ignore errors or not (we can never guarantee to create a valid
					// zip file because the script might be terminated by the server so we must wait to produce a valid file and then
					// move it to the final location if it is valid).
					// Therefore if there is a zip file (produced but with warnings) it will not be visible and will be deleted when the
					// temporary directory is deleted below.
					
					$result = false;
					
				} else {
				
					// Got file with no error or warnings _or_ with warnings that the user has chosen to ignore
					// File always built in temporary location so always need to move it
					$this->log( 'details', __('Zip process reported: Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
					
					// Make sure no stale file information
					clearstatcache();
					
					// Relocate the temporary zip file to final location
					@rename( $temp_zip, $zip );
					
					// Check that we moved the file ok
					if ( @file_exists( $zip ) ) {
					
						$this->log( 'details', __('Zip process reported: Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
						$this->log( 'message', __( 'Zip process reported: Zip Archive file successfully created with no errors (any actionable warnings ignored by user settings).','it-l10n-backupbuddy' ) );
						
						$this->log_archive_file_stats( $zip, array( 'content_size' => $total_size ) );
						
						// Temporary for now - try and incorporate into stats logging (makes the stats logging function part of the zip helper class?)
						$this->log( 'details', sprintf( __('Zip process reported: Zip Archive file size: %1$s of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ), $total_count ) );
				
						// Work out percentage on items
						if ( 0 < $total_count ) {
				
							$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ) / $total_count ) * 100 );
							$this->log( 'details', sprintf( __('Zip process reported: Zip archive file size: %1$s%% of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );

						}
							
						$result = true;
						
					} else {
					
						$this->log( 'details', __('Zip process reported: Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
									
				}

			}
			
			// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file		
			$this->log( 'details', __('Zip process reported: Removing temporary directory.','it-l10n-backupbuddy' ) );
			
			if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
			
					$this->log( 'details', __('Zip process reported: Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
			
			}
			
//		  	if ( null != $za ) { unset( $za ); }
		  	
			return $result;
															
		}
		
		/**
		 *	grow()
		 *	
		 *	A function that grows an archive file from already calculated contet list
		 *	Always cleans up after itself
		 *	
		 *	
		 *	@param		string	$zip			Full path & filename of ZIP Archive file to grow
		 *	@param		string	$tempdir		Full path of directory for temporary usage
		 *	@param		array	$state
		 *	@return		bool					True if the creation was successful, array for continuation, false otherwise
		 *
		 */
		public function grow( $zip, $tempdir, $state ) {
		
			$za = null;
			$result = false;
			$exitcode = 255;
			$output = array();
			$temp_zip = '';
			$excluding_additional = false;
			$exclude_count = 0;
			$exclusions = array();
			$temp_file_compression_threshold = 5;
			$pre_add_func = '';
			$have_zip_errors = false;
			$zip_errors_count = 0;
			$zip_errors = array();
			$have_zip_warnings = false;
			$zip_warnings_count = 0;
			$zip_warnings = array();
			$have_zip_additions = false;
			$zip_additions_count = 0;
			$zip_additions = array();
			$have_zip_debug = false;
			$zip_debug_count = 0;
			$zip_debug = array();
			$have_zip_other = false;
			$zip_other_count = 0;
			$zip_other = array();
			$zip_skipped_count = 0;
			$logfile_name = '';
			$contentfile_name = '';
			$contentfile_fp = 0;
			$contentfile_fp_start = 0;
			$have_more_content = true;
			$zip_ignoring_symlinks = false;

			$zm = null;
			$lister = null;
			$visitor = null;
			$logger = null;
			$total_size = 0;
			$total_count = 0;
			$the_list = array();
			$count_ignored_symdirs = 0;
			$saved_ignored_symdirs = array();
			$zip_error_encountered = false;
			$zip_period_expired = false;
			
			// Ensure no stale file information
			clearstatcache();
			
			// Create the helper function here so we can use it outside of the post-add
			// function. Using all defaults so includes multi-burst and server tickling
			// for now but with options we can modify this.				
			$zm = new pb_backupbuddy_zip_monitor( $this );
//			$zm->set_burst_max_period( self::ZIP_PCLZIP_DEFAULT_BURST_MAX_PERIOD )->set_burst_threshold_period( 'auto' )->log_parameters();
			$zm->set_burst_size_min( $this->get_min_burst_content() )
			->set_burst_size_max( $this->get_max_burst_content() )
			->set_burst_current_size_threshold( $zm->get_burst_size_min() )
			->log_parameters();
				
			// Set some state on it
			$zm->set_added_dir_count( $state[ 'helper' ][ 'dc' ] );
			$zm->set_added_file_count( $state[ 'helper' ][ 'fc' ] );
				
			// Note: could enforce trailing directory separator for robustness
			if ( empty( $tempdir ) || !file_exists( $tempdir ) ) {
			
				// This breaks the rule of single point of exit (at end) but it's early enough to not be a problem
				$this->log( 'details', __('Zip process reported: Temporary working directory not available: ','it-l10n-backupbuddy' ) . '`' . $tempdir . '`' );				
				return false;
				
			}
			
			// Log the temporary working directory so we might be able to spot problems
			$this->log( 'details', __('Zip process reported: Temporary working directory available: ','it-l10n-backupbuddy' ) . '`' . $tempdir . '`' );				
			
			$this->log( 'message', __('Zip process reported: Using Compatibility Mode.','it-l10n-backupbuddy' ) );
			
			// Notify the start of the step
			$this->log( 'details', sprintf( __('Zip process reported: Zip archive continuation step started with step period threshold: %1$ss','it-l10n-backupbuddy' ), $this->get_step_period() ) );


			// In case that took a while use the monitor to try and keep the process alive
			$zm->burst_end();
			$this->get_process_monitor()->checkpoint();

			// Temporary convenience
			$result = true;
			
			// This is where we previously calculated this when deriving the list
			$total_size = $state[ 'zipper' ][ 'ts' ];
			$total_count = $state[ 'zipper' ][ 'tc' ];
			
			// Only continue if we have a valid list
			// This isn't ideal at present but will suffice
			if ( true === $result ) {	

 				// We need to force the pclzip library to load at this point if it is
 				// not already loaded so that we can use defined constants it creates
 				// but we don't actually want to create a zip archive at this point.
 				// We can also use this as an early test of being able to use the library
 				// as an exception will be raised if the class does not exist.
 				// Note that this is only really required when zip method caching is
 				// in use, if this is disabled then the library would already have been
 				// loaded by the method testing.			
 				try {
 					
 					// Select to just load the pclzip library only and tell it the
 					// temporary directory to use if required (this is only possible
 					// if it hasn't already been loaded and the temp dir defined)
 					$za = new pluginbuddy_PclZip( "", true, $tempdir );
 					
 					// We have no purpose for this object any longer, the library
 					// will remain loaded
 					unset( $za );
 					$result = true;
 				
 				} catch ( Exception $e ) {
 			
 					// Something fishy - the methods indicated pclzip but we couldn't find the class
 					$error_string = $e->getMessage();
 					$this->log( 'details', sprintf( __('Zip process reported: pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
 					$result = false;
 				
 				}
				
			}
			
			// Only continue if we have a valid list
			// This isn't ideal at present but will suffice
			if ( true === $result ) {
			
				// Basic argument list (will be used for each burst)
				$arguments = array();
				array_push( $arguments, PCLZIP_OPT_REMOVE_PATH, $state[ 'zipper' ][ 'root' ] );				

				if ( true !== $this->get_compression() ) {
				
					// Note: don't need to force use of temporary files for compression
					$this->log( 'details', __('Zip process reported: Zip archive creation compression disabled based on settings.','it-l10n-backupbuddy' ) );
					array_push( $arguments, PCLZIP_OPT_NO_COMPRESSION );
					
				} else {
	
					// Note: force the use of temporary files for compression when file size exceeds given value.
					// This over-rides the "auto-sense" which is based on memory_limit and this _may_ indicate a
					// memory availability that is higher than reality leading to memory allocation failure if
					// trying to compress large files. Set the threshold low enough (specify in MB) so that except in
					// The tightest memory situations we should be ok. Could have option to force use of temporary
					// files regardless.
					$this->log( 'details', __('Zip process reported: Zip archive creation compression enabled based on settings.','it-l10n-backupbuddy' ) );
					array_push( $arguments, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $temp_file_compression_threshold );
						
				}
				
				// Check if ignoring (not following) symlinks
				if ( true === $this->get_ignore_symlinks() ) {
			
					// Want to not follow symlinks so set flag for later use
					$zip_ignoring_symlinks = true;
				
					$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will be ignored based on settings.','it-l10n-backupbuddy' ) );

				} else {
			
					$this->log( 'details', __('Zip process reported: Zip archive creation symbolic links will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
			
				// Check if we are ignoring warnings - meaning can still get a backup even
				// if, e.g., some files cannot be read
				if ( true === $this->get_ignore_warnings() ) {
				
					// Note: warnings are being ignored but will still be gathered and logged
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will be ignored based on settings.','it-l10n-backupbuddy' ) );
					
				} else {
				
					$this->log( 'details', __('Zip process reported: Zip archive creation actionable warnings will not be ignored based on settings.','it-l10n-backupbuddy' ) );

				}
				
		
				// Set up the log file - for each file added we'll append a log entry to the
				// log file that maps the result of the add to the nearest equivalent command
				// line zip log entry and this allows us to eventually process and present the
				// relevant log details in a consistent manner across different methods which
				// should cut down on confusion a bit. Note that we'll also try and map the
				// pclzip exit codes to equivalent zip utility codes but we may have to still
				// maintain our own code space for those that cannot be mapped - just have to
				// see how it goes.
				// This approach gives us a unified process and also makes it easy to handle
				// the log over multiple steps if required.
				$logfile_name = $tempdir . self::ZIP_LOG_FILE_NAME;
				
				// Temporary zip file is _always_ located in the temp dir now and we move it
				// to the final location after completion if it is a good completion
				$temp_zip = $tempdir . basename( $zip );
			
				// Use anonymous function to weed out the unreadable and non-existent files (common reason for failure)
				// and possibly symlinks based on user settings.
				// PclZip will record these files as 'skipped' in the file status and we can post-process to determine
				// if we had any of these and hence either stop the backup or continue dependent on whether the user
				// has chosen to ignore warnings or not and/or ignore symlinks or not.
				// Unfortunately we cannot directly tag the file with the reason why it has been skipped so when we
				// have to process the skipped items we have to try and work out why it was skipped - but shouldn't
				// be too hard.
				// TODO: Consider moving this into the PclZip wrapper and have a method to set the various pre/post
				// functions or select predefined functions (such as this).
				if ( true ) {
				
					// Note: This could be simplified - it's written to be extensible but may not need to be
					$args = '$event, &$header';
					$code = '';
//					$code .= 'static $symlinks = array(); ';
					$code .= '$result = true; ';
					
					// Handle symlinks - keep the two cases of ignoring/not-ignoring separate for now to make logic more
					// apparent - but could be merged with different conditional handling
					// For a valid symlink: is_link() -> true; is_file()/is_dir() -> true; file_exists() -> true
					// For a broken symlink: is_link() -> true; is_file()/is_dir() -> false; file_exists() -> false
					// Note: pclzip first tests every file using file_exists() before ever trying to add the file so
					// for a broken symlink it will _always_ error out immediately it discovers a broken symlink so
					// we never have a chance to filter these out at this stage.
					// Note: now that we are generating the file list and not following symlinks at that stage we
					// never have the situation where we need to remember a symdir prefix to filter out dirs/files
					// under that symdir (once you have passed "through" a dir symlink the dirs/files under that
					// do not register as symlinks because they themselves are not so previously when pclzip was
					// generating the list internally we had to make sure we skipped such dirs/files based on
					// there being a dir symlink as a prefix to the dir/file path).
					if ( true === $zip_ignoring_symlinks ) {
					
						// If it's a symlink or it's neither a file nor a directory then ignore it. A broken symlink
						// will never get this far because pclzip will have choked on it
						$code .= 'if ( ( true === $result ) && !( @is_link( $header[\'filename\'] ) ) ) { ';
						$code .= '    if ( @is_file( $header[\'filename\'] ) || @is_dir( $header[\'filename\'] ) ) { ';
						$code .= '        $result = true; ';
// 						$code .= '        foreach ( $symlinks as $prefix ) { ';
// 						$code .= '            if ( !( false === strpos( $header[\'filename\'], $prefix ) ) ) { ';
// 						$code .= '                $result = false; ';
// 						$code .= '                break; ';
// 						$code .= '             } ';
// 						$code .= '        } ';
						$code .= '    } else { ';
//						$code .= '        error_log( "Neither a file nor a directory (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '        $result = false; ';
						$code .= '    } ';
						$code .= '} else { ';
//						$code .= '    error_log( "File is a symlink (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
//						$code .= '    $symlinks[] = $header[\'filename\']; ';
//						$code .= '    error_log( "Symlinks Array: \'" . print_r( $symlinks, true ) . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
						
					} else {
					
						// If it's neither a file nor directory then ignore it - a valid symlink will register as a file
						// or directory dependent on what it is pointing at. A broken symlink will never get this far.
						// because pclzip will have barfed on its file_exists() check before calling the pre-add. We may
						// choose later to catch this earlier during the list creation I think.
						$code .= 'if ( ( true === $result ) && ( @is_file( $header[\'filename\'] ) || @is_dir( $header[\'filename\'] ) ) ) { ';
						$code .= '    $result = true; ';
						$code .= '} else { ';
//						$code .= '    error_log( "Neither a file nor a directory (ignoring): \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
						
					}
					
					// Add the code block for ignoring unreadable files
					if ( true ) {
					
						$code .= 'if ( ( true === $result ) && ( @is_readable( $header[\'filename\'] ) ) ) { ';
						$code .= '    $result = true; ';
						$code .= '} else { ';
//						$code .= '    error_log( "File not readable: \'" . $header[\'filename\'] . "\'" ); ';
						$code .= '    $result = false; ';
						$code .= '} ';
					
					}
					
					// Return true (to include file) if file passes conditions otherwise false (to skip file) if not
					$code .= 'return ( ( true === $result ) ? 1 : 0 ); ';

					$pre_add_func = create_function( $args, $code );
				
				}
				
				// If we had cause to create a pre add function then add it to the argument list here
				if ( !empty( $pre_add_func ) ) {
				
					array_push( $arguments, PCLZIP_CB_PRE_ADD, $pre_add_func );
		
				}
				
				// Add a post-add function for progress monitoring, usage data monitoring,
				// burst handling and server tickling - using the zip helper object
				// we created earlier
				$post_add_func = '';
								
// 				if (true) {
// 				
// 					$args = '$event, &$header';
// 					$code = '';
// 					$code .= '$result = true; ';
// 					$code .= '$zm = pb_backupbuddy_pclzip_helper::get_instance();';
// 					$code .= '$result = $zm->event_handler( $event, $header );';
// 					$code .= 'return $result;';
// 				
// 					$post_add_func = create_function( $args, $code );
// 				
// 				}

				// If we had cause to create a pre add function then add it to the argument list here
				if ( !empty( $post_add_func ) ) {
				
					array_push( $arguments, PCLZIP_CB_POST_ADD, $post_add_func );
		
				}

				// Remember our "master" arguments
				$master_arguments = $arguments;

				// Use this to memorise the worst exit code we had (where we didn't immediately
				// bail out because it signalled a bad failure)
				$max_exitcode = $state[ 'zipper' ][ 'mec' ];
				
				// This is where we want to read the contens from
				$contentfile_name = $tempdir . self::ZIP_CONTENT_FILE_NAME;

				// Need to setup where we are going to dip into the content file
				// and remember the start position so we can test for whether we
				// actually advanced through our content or not.
				$contentfile_fp = $state[ 'zipper' ][ 'fp' ];
				$contentfile_fp_start = $contentfile_fp;
				
				// Do this as close to when we actually want to start monitoring usage
				// Maybe this is redundant as we have already called this in the constructor.
				// If we want to do this then we have to call with true to reset monitoring to
				// start now.
				$this->get_process_monitor()->initialize_monitoring_usage();
				
				// Now we have built our common arguments and we have the list defined we can
				// start on the bursts. Note that each burst will either succeed with an array
				// output or will fail and no array. When we get an array we will iterate over
				// it and generate log file entries. For case where we have a non-fatal warning
				// condition we change the actual pclzip exit code to be the sam eas the zip
				// utility exit code (18) and this lets us handle the outcome the same way. In
				// the case of no array but an error code we map that to an equivalent zip utility
				// exit code (as much as possible) and then we'll drop out with that and a
				// logged error that the log file processing will pick up.
				
				// Now we have our command prototype we can start bursting
				// Simply build a burst list based on content size. Currently no
				// look-ahead so the size will always exceed the current size threshold
				// by some amount. May consider using a look-ahead to see if the next
				// item would exceed the threshold in which case don't add it (unless it
				// would be the only content in which case have to add it but also log
				// a warning).
				// We'll stop either when noting more to add or we have exceeded our step
				// period or we have encountered an error.
				// Note: we might bail out immediately if previous processing has already
				// caused us to exceed the step period.
				while ( $have_more_content &&
						!( $zip_period_expired = $this->exceeded_step_period( $this->get_process_monitor()->get_elapsed_time() ) ) &&
						!$zip_error_encountered ) {
						
					clearstatcache();

					// Populate the content array for zip
					$ilist = array();

					// Keep track of any symdirs that are being ignored
					$saved_ignored_symdirs = array();
					
					// Tell helper that we are preparing a new burst
					$zm->burst_begin();
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
					$this->log( 'details', sprintf( __( 'Zip process reported: Current burst size threshold: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $zm->get_burst_current_size_threshold(), 0, ".", "" ) ) );

					// Open the content list file and seek to the "current" position. This
					// will be initially zero and then updated after each burst. For multi-step
					// it will be zero on the first step and then would be passed back in
					// as a parameter on subsequent steps based on where in the file the previous
					// step reached.
					// TODO: Maybe a sanity check to make sure position seems tenable
					try {
			
						$contentfile = new SplFileObject( $contentfile_name, "rb" );
						$contentfile->fseek( $contentfile_fp );

						// Helper keeps track of what is being added to the burst content and will
						// tell us when the content is sufficient for this burst based on it's
						// criteria - this can adapt to how each successive burst goes.
						while ( ( !$contentfile->eof() ) && ( false === $zm->burst_content_complete() ) ) {
					
							// Should be at least one item to grab from the list and then move to next
							// and remember it for if we drop out because burst content complete, in
							// that case we'll return to that point in the file at the next burst start.
							// Check for unserialize failure and bail
							$item = @unserialize( $contentfile->current() );

							if ( false === $item ) {
							
								throw new Exception( 'Unserialization of content list data failed: `' . $contentfile->current() . '`' );
								
							}
							
							$contentfile->next();
						
							$file = $item[ 'absolute_path' ] . $item[ 'filename' ];
						
							// Filter out symdirs if we are ignoring symlinks and record them to log
							// Because of the way the list creation works this condition indicates
							// a symlink directory only in the case of ignorign symlinks. If we
							// were not ignoring symlinks then the "vacant" attribute would be set
							// if the directory were vacant or alternatively this entry would have
							// already been filtered out if the symlinked directory were not vacant.
							// So we must filter it out and move on
							if ( ( true === $item[ 'directory' ] ) && !( isset( $item[ 'vacant' ] ) ) ) {	
						
								$saved_ignored_symdirs[] = $file;
							
							} else {
						
								// We shouldn't have any empty items here as we should have removed them
								// earlier, but just in case...
								if ( !empty( $file ) ) {
									$ilist[] = $file;
							
									// Call the helper event handler as we add each file to the list
									$zm->burst_content_added( $item );
							
								}
						
							}
						
						}
					
						// Burst list is completed by way of end of content list file or size threshold
						if ( !$contentfile->eof() ) {
					
							// We haven't exhausted the content list yet so remember where we
							// are at for next burst
							$contentfile_fp = $contentfile->ftell();
					
						} else {
					
							// Exhausted the content list so make sure we drop out after this burst
							// if we don't break out of the loop due to a zip error or reached step
							// duration limit
							$have_more_content = false;
					
						}
				
						// Finished one way or another so close content list file for this burst
						unset( $contentfile );
				
					} catch ( Exception $e ) {
			
						// Something fishy - we should have been able to open the content file...
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: Zip content list file could not be opened/read - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
						$exitcode = 255;
						$zip_error_encountered = true;
						break;					
				
					}

					// Retain this for reference for now
					//file_put_contents( ( dirname( $tempdir ) . DIRECTORY_SEPARATOR . self::ZIP_CONTENT_FILE_NAME ), print_r( $ilist, true ) );
					
					// add() method will create archive file if it doesn't aleady exist
					//$command = 'add';
					$command = 'grow';
				
					// Now create our zip handler object for thsi burst
					// This should give us a new archive object, if not catch it and bail out
					// Note we previously loaded the library and defined the temporary directory
					try {
					
						$za = new pluginbuddy_PclZip( $temp_zip );
						$result = true;
				
					} catch ( Exception $e ) {
			
						// Something fishy - the methods indicated pclzip but we couldn't find the class
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
						$exitcode = 255;
						$zip_error_encountered = true;
						break;					
				
					}
					
					// Allow helper to check how the burst goes
					$zm->burst_start();
		
					// Create the argument list for this burst
					$arguments = array();
					array_push( $arguments, $ilist );
					$arguments = array_merge( $arguments, $master_arguments );
					
					// Showing the "master" arguments
					// First implode any embedded array in the argument list and truncate the result if too long
					// Assume no arrays embedded in arrays - currently no reason for that
					// Make sure that there are no non-printable characters (such as in pre- or post-add function
					// names created by create_function()) by replacing with "*" using preg_replace()
					// TODO: Make the summary length configurable so that can see more if required
					// TODO: Consider mapping pclzip argument identifiers to string representations for clarity
					$args = '$item';
					$code = 'if ( is_array( $item ) ) { $string_item = implode( ",", $item); return ( ( strlen( $string_item ) <= 50 ) ? preg_replace( "/[^[:print:]]/", "*", $string_item ) : "List: " . preg_replace( "/[^[:print:]]/", "*", substr( $string_item, 0, 50 ) ) . "..." ); } else { return preg_replace( "/[^[:print:]]/", "*", $item ); }; ';
					$imploder_func = create_function( $args, $code );
					$imploded_arguments = array_map( $imploder_func, $arguments );
				
					$this->log( 'details', sprintf( __( 'Zip process reported: Burst requests %1$s (directories + files) items with %2$s bytes of content to be added to backup zip archive', 'it-l10n-backupbuddy' ), $zm->get_burst_content_count(), $zm->get_burst_content_size() ) );

					$this->log( 'details', __( 'Zip process reported: ') . $this->get_method_tag() . __( ' command arguments','it-l10n-backupbuddy' ) . ': ' . implode( ';', $imploded_arguments ) );
				
					$zip_output = call_user_func_array( array( &$za, $command ), $arguments );

					// And now we can analyse what happened and plan for next burst if any
					$zm->burst_stop();
					
					// Wrap up the individual burst handling
					// Note: because we called exec we basically went into a wait condition and so (on Linux)
					// we didn't consume any max_execution_time so we never really have to bother about
					// resetting it. However, it is true that time will have elapsed so if this burst _does_
					// take longer than our current burst threshold period then max_execution_time would be
					// reset - but what this doesn't cover is a _cumulative_ effect of bursts and so we might
					// consider reworking the mechanism to monitor this separately from the individual burst
					// period (the confusion relates to this having originally applied to the time based
					// burst handling fro pclzip rather than teh size based for exec). It could also be more
					// relevant for Windows that doesn't stop the clock when exec is called.
					$zm->burst_end();	
					$this->get_process_monitor()->checkpoint();				
					
				  	// If the output is an array then we need to do a quick iteration over the output
				  	// in order to determine whetehr we need to change the exit code from 0 to any other
				  	// value (essentially to 18). The alternative is some messy stuff with iterating
				  	// around and doing stuff based on whether the log file is available or not. By
				  	// doing the preprocessing we can simply bail out at any point if the file cannot be
				  	// opened or if a write fails.
					if ( is_array( $zip_output ) ) {
				
						// Something reasonable happened
						// For now we'll assume everything rosy but if we find unreadable
						// files we'll modify the exit code
						$exitcode = 0;
					
						foreach ( $zip_output as $file ) {
						
							switch ( $file[ 'status' ] ) {
								case "ok":
									break;
								case "skipped":								
									// For skipped files need to determine why it was skipped
									if ( ( true === $zip_ignoring_symlinks ) && @is_link( $file[ 'filename' ] ) ) {						
										// Skipped because we are ignoring symlinks and this is a symlink.
										// This just handles files as we have previously filtered out symdirs							
									} else {						
										// Skipped because probably unreadable or non-existent (catch-all for now)
										// Change the exit code as this is a warning we want to catch later
										$exitcode = 18;								
									}
									break;
								case "filtered":
									// Log it and change exit code as this is a warning we want to catch later
									$exitcode = 18;
									break;
								case "filename_too_long":
									// Log it and change exit code as this is a warning we want to catch later
									$exitcode = 18;
									break;
								default:
									// Unknown status that we'll not consider for changing exit code
							}
			
						}
								
					} else {
				
						// Something really failed
						$exitcode = $za->errorCode();

					}
				  	
					// This method never directly produces a log file so we need to append the $zip_output array
					// to the log file - first invocation will create the file.
					// We now have our exit code so this iteration is simply to log output if we can.
					// If we fail to open the log file or there is a falure writing we can just bail out
				  	
					$this->log( 'details', sprintf( __('Zip process reported: Appending zip burst log detail to zip log file: %1$s','it-l10n-backupbuddy' ), $logfile_name ) );
			
				  	try {
				  	
						$logfile = new SplFileObject( $logfile_name, "ab" );
				
						// Now handle whether the outcome of the addition
						if ( is_array( $zip_output ) ) {
					
							// Something reasonable happened
							// Note if we have skipped any files
							$skipped_count = 0;
						
							// Now we need to put the log information to file
							// Need to process each status to determine how to log the outcome
							// for the item - in particular how to log skipped items as the item
							// status didn't allow us to give any particular reason for an item
							// being skipped, so we have to try and deduce that from information
							// about the item.
							// Our logs are mapped to format like zip utility uses so we can use
							// a common log processor subsequently.
							foreach ( $zip_output as $file ) {
							
								// Use this to amass what we want to write to log file
								$line = '';
					
								switch ( $file[ 'status' ] ) {
									case "ok":
										// Item was added ok
										$line = ( 'adding: ' . $file[ 'filename' ] );
										break;
									case "skipped":								
										// For skipped files need to determine why it was skipped
										if ( ( true === $zip_ignoring_symlinks ) && @is_link( $file[ 'filename' ] ) ) {
							
											// Skipped because we are ignoring symlinks and this is a symlink.
											// This just handles files as we have previously filtered out symdirs
											// Just treat as an informational
											$line = ( 'zip info: ignored symlink: ' . $file[ 'filename' ] );
								
										} else {
							
											// Skipped because probably unreadable or non-existent (catch-all for now)
											$line = ( 'zip warning: could not open for reading: ' . $file[ 'filename' ] );
									
										}
										$skipped_count++;
										break;
									case "filtered":
										// Log that it was filtered for some reason
										$line = ( 'zip warning: filtered: ' . $file[ 'filename' ] );
										// This counts as a skip because we didn't add it
										$skipped_count++;
										break;
									case "filename_too_long":
										// Log that the given name was too long
										$line = ( 'zip warning: filename too long: ' . $file[ 'filename' ] );
										// This counts as a skip because we didn't add it
										$skipped_count++;
										break;
									default:
										// Hmm, have to assume something was not right so we'll log it as
										// a warning to be on the safe side
										$line = ( 'zip warning: unknown add status: ' . $file[ 'status' ] . ': ' . $file[ 'filename' ] );

								}
				
								// Now try and commit the log line to file
								$bytes_written = $logfile->fwrite( $line . PHP_EOL );
								
								// Be very careful to make sure we had a valid write - in paticular
								// make sure we didn't write 0 bytes since even an empty line from the
								// array should have the PHP_EOL bytes written 
								if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
									throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
								}

							}
							
							// Now assemble some optional lines
							$lines = array();
					
							// Now also add in INFORMATIONALs for any ignored symdirs because these would not have
							// been included in the build list. They were not included because pclzip would have attempted
							// to follow them and then we would have had to "filter" them and all entries that pclzip
							// would have created under them which is just a wster of time - best to not include at all
							// at tell the user now that we didnt include them
							foreach ( $saved_ignored_symdirs as $ignored_symdir ) {
				
								$lines[] = ( 'zip info: ignored symlink: ' . $ignored_symdir . self::NORM_DIRECTORY_SEPARATOR );
				
							}
				
							// Now add log entry related to skiped files if we did skip any
							// Make this look like zip utility output to some extent
							if ( 0 != $skipped_count ) {
					
								$lines[] = ( 'zip warning: Not all files were readable' );
								$lines[] = ( ' skipped:   ' . $skipped_count );
					
							}
						
							foreach ( $lines as $line ) {
							
								$bytes_written = $logfile->fwrite( $line . PHP_EOL );
								
								// Be very careful to make sure we had a valid write - in paticular
								// make sure we didn't write 0 bytes since even an empty line from the
								// array should have the PHP_EOL bytes written 
								if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
									throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
								}
							
							}
							
						} else {
					
							// Have to map exit code and warn that not all warnings/etc may be logged
					
							// Something really failed
							$bytes_written = $logfile->fwrite( 'zip error: ' . $za->errorInfo( true ) . PHP_EOL );
				
							// Be very careful to make sure we had a valid write - in paticular
							// make sure we didn't write 0 bytes since even an empty line from the
							// array should have the PHP_EOL bytes written 
							if ( ( null === $bytes_written ) || ( 0 === $bytes_written ) ) {
								throw new Exception( 'Failed to append to zip log file during zip creation - zip log details will be incomplete but zip exit code will still be valid' );
							}

						}
					
						// Put the log file away - safe even if we failed to get a logfile
						unset( $logfile );
					
						// And throw away the output result as we have no further use for it
						unset( $zip_output );
					
					} catch ( Exception $e ) {
				
						// Something fishy - we should have been able to open and
						// write to the log file...
						$error_string = $e->getMessage();
						$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened/appended-to - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

						// Put the log file away - safe even if we failed to get a logfile
						unset( $logfile );
					
						// And throw away the output result as we cannot use it
						unset( $zip_output );
					
					}
					
					// Put the zip archive away					
				  	unset( $za );
				  	
					// Report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated burst requested %1$s (directories + files) items requested to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					clearstatcache();
					
					// Keep a running total of the backup file size (this is temporary code)
					// Using our stat() function in case file size exceeds 2GB on a 32 bit PHP system
					$temp_zip_stats = pluginbuddy_stat::stat( $temp_zip );			
					// Only log anything if we got some valid file stats
					if ( false !== $temp_zip_stats ) {			
						$this->log( 'details', sprintf( __( 'Zip process reported: Accumulated zip archive file size: %1$s bytes', 'it-l10n-backupbuddy' ), number_format( $temp_zip_stats[ 'dsize' ], 0, ".", "" ) ) );			
					}
					
					$this->log( 'details', sprintf( __( 'Zip process reported: Ending burst number: %1$s', 'it-l10n-backupbuddy' ), $zm->get_burst_count() ) );
						
					// Now work out the result of that burst and what to do
					// If it is an array then append to the cumulative array and continue
					// otherwise we have an error and we must bail out. So we don't need
					// the complexity of exec to handle non-fatal errors (as warnings)
					// Note: in the multi-burst case we will still have the results array
					// accumulated from previous bursts so we _could_ chose to handle that
					// but for now we'll just throw that away. At some point we can thnk about
					// handling the output array.

					// We have to check the exit code to decide whether to keep going ot bail out (break).
					// If we get a 0 exit code ot 18 exit code then keep going and remember we got the 18
					// so that we can emit that as the final exit code if applicable. If we get any other
					// exit code then we must break out immediately.					
					if ( ( 0 !== $exitcode ) && ( 18 !== $exitcode ) ) {
						// Zip failure of some sort - must bail out with current exit code
						$zip_error_encountered = true;
					} else {
						// Make sure exit code is always the worst we've had so that when
						// we've done our last burst we drop out with the correct exit code set
						// This is really to make sure we drop out with exit code 18 if we had
						// this in _any_ burst as we would keep going and subsequent burst(s) may
						// return 0. If we had any other non-zero exit code it would be a "fatal"
						// error and we would have dropped out immediately anyway.
						$exitcode = ( $max_exitcode > $exitcode ) ? $max_exitcode : ( $max_exitcode = $exitcode ) ;
					}

					// Report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items requested to be added to backup zip archive (end of burst)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Now inject a little delay until the next burst. This may be required to give the
					// server time to catch up with finalizing file creation and/or it may be required to
					// reduce the average load a little so there isn't a sustained "peak"
					// Theoretically a sleep could be interrupted by a signal and it would return some
					// non-zero value or false - but if that is the case it probably signals something
					// more troubling so there is little point in tryng to "handle" such a condition here.
					if ( 0 < ( $burst_gap = $this->get_burst_gap() ) ) {
					
						$this->log( 'details', sprintf( __( 'Zip process reported: Starting burst gap delay of: %1$ss', 'it-l10n-backupbuddy' ), $burst_gap ) );
						sleep( $burst_gap );
						
					}
				
				}

				// Exited the loop for some reason so decide what to do now.
				// If we didn't exit because of exceeding the step period then it's a
				// normal exit and we'll process accordingly and end up returning true
				// or false. If we exited because of exceeding step period then we need
				// to return the current state array to enable next iteration to pick up
				// where we left off.
				// Note: we might consider having the zip helper give us a state to
				// restore on it when we create one again - but for now we'll not do that
				if ( $zip_period_expired && $have_more_content && !$zip_error_encountered ) {
					
					// Report progress at end of step
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated burst requested %1$s (directories + files) items requested to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

					// Work out percentage progress on items
					if ( 0 < $total_count ) {
					
						$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
						$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (end of step)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );
	
					}
					
					if ( $contentfile_fp <> $contentfile_fp_start ) {
						
						// We have advanced through content file
						
						$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step terminated after %1$ss, continuation step will be scheduled','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );
						
						// Need to set up the state information we'll need to tell the next
						// loop how to set things up to continue. Next time around if another
						// step is required then some of these may be changed and others may
						// stay the same.
						// Note: the method tag 'mt' is used to tell zipbuddy exactly which
						// zipper to use, the one that was picked first time through.
					
						$new_state = $state;
						$new_state[ 'zipper' ][ 'fp' ] = $contentfile_fp;
						$new_state[ 'zipper' ][ 'mec' ] = $max_exitcode;
						$new_state[ 'zipper' ][ 'sp' ] = $this->get_step_period();
						$new_state[ 'helper' ][ 'dc' ] = $zm->get_added_dir_count();
						$new_state[ 'helper' ][ 'fc' ] = $zm->get_added_file_count();
									
						// Now we can return directly as we have nothing to clear up
						return $new_state;
					
					} else {
						
						// It appears the content file pointer didn't change so we
						// haven't advanced through the content for some reason so
						// we need to bail out as there is a risk of getting into
						// an infinite loop
					
						$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step did not progress through content due to unknown server issue','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );
						$this->log( 'details', sprintf( __('Zip process reported: Zip archive build step terminated after %1$ss, continuation step will not be scheduled due to abnormal progress indication','it-l10n-backupbuddy' ), $this->get_process_monitor()->get_elapsed_time() ) );

						// Set a generic exit code to force termination of the build
						// after what we have so far is processed
						$exitcode = 255;
						$zip_error_encountered = true;
					
					}
					
				}
			
				// Convenience for handling different scanarios
				$result = false;
				
				// We can report how many dirs/files added				
				$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s (directories + files) items requested to be added to backup zip archive (final)','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) ) );

				// Work out percentage progress on items
				if ( 0 < $total_count ) {
				
					$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() ) / $total_count ) * 100 );
					$this->log( 'details', sprintf( __('Zip process reported: Accumulated bursts requested %1$s%% of %2$s (directories + files) total items to be added to backup zip archive (final)','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );

				}
					
				// Always logging to file one way or another
				// Always scan the output/logfile for warnings, etc. and show warnings even if user has chosen to ignore them
				try {
			
					$logfile = new SplFileObject( $logfile_name, "rb" );
				
					while( !$logfile->eof() ) {
				
						$line = $logfile->current();
						$id = $logfile->key(); // Use the line number as unique key for later sorting
						$logfile->next();
					
						if ( preg_match( '/^\s*(zip warning:)/i', $line ) ) {
					
							// Looking for specific types of warning - in particular want the warning that
							// indicates a file couldn't be read as we want to treat that as a "skipped"
							// warning that indicates that zip flagged this as a potential problem but
							// created the zip file anyway - but it would have generated the non-zero exit
							// code of 18 and we key off that later. All other warnings are not considered
							// reasons to return a non-zero exit code whilst still creating a zip file so
							// we'll follow the lead on that and not have other warning types halt the backup.
							// So we'll try and look for a warning output that looks like it is file related...
							if ( preg_match( '/^\s*(zip warning:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
								// Matched to what looks like a file related warning so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "could not open for reading:":										
										$zip_warnings[ self::ZIP_WARNING_SKIPPED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "filtered:":										
										$zip_warnings[ self::ZIP_WARNING_FILTERED ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "filename too long:":										
										$zip_warnings[ self::ZIP_WARNING_LONGPATH ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "unknown add status:":										
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
										$zip_warnings_count++;
										break;
									case "name not matched:":										
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
										$zip_warnings_count++;
								}
						
							} else {
						
								// Didn't match to what would look like a file related warning so count it regardless
								$zip_warnings[ self::ZIP_WARNING_GENERIC ][ $id ] = trim( $line );
								$zip_warnings_count++;
							
							}
						
						} elseif ( preg_match( '/^\s*(zip info:)/i', $line ) ) {
						
							// An informational may have associated reason and filename so
							// check for that
							if ( preg_match( '/^\s*(zip info:)\s*([^:]*:)\s*(.*)/i', $line, $matches ) ) {
						
								// Matched to what looks like a file related info so check particular cases
								switch ( strtolower( $matches[ 2 ] ) ) {
									case "ignored symlink:":										
										$zip_other[ self::ZIP_OTHER_IGNORED_SYMLINK ][ $id ] = trim( $line );
										$zip_other_count++;
										break;
									default:
										$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
										$zip_other_count++;
								}
						
							} else {
						
								// Didn't match to what would look like a file related info so count it regardless
								$zip_other[ self::ZIP_OTHER_GENERIC ][ $id ] = trim( $line );
								$zip_other_count++;
							
							}
						
						} elseif ( preg_match( '/^\s*(zip error:)/i', $line ) ) {
					
							$zip_errors[ $id ] = trim( $line );
							$zip_errors_count++;
					
						} elseif ( preg_match( '/^\s*(adding:)/i', $line ) ) {
					
							// Currently not processing additions entried
							//$zip_additions[] = trim( $line );
							//$zip_additions_count++;
					
						} elseif ( preg_match( '/^\s*(sd:)/i', $line ) ) {
					
							$zip_debug[ $id ] = trim( $line );
							$zip_debug_count++;
							
						} elseif ( preg_match( '/^.*(skipped:)\s*(?P<skipped>\d+)/i', $line, $matches ) ) {
						
							// Each burst may have some skipped files and each will report separately
							if ( isset( $matches[ 'skipped' ] ) ) {
								$zip_skipped_count += $matches[ 'skipped' ];
							}
							
						} else {
					
							// Currently not processing other entries
							//$zip_other[] = trim( $line );
							//$zip_other_count++;
					
						}
					
					}
				
					unset( $logfile );
					@unlink( $logfile_name );
				
				} catch ( Exception $e ) {
			
					// Something fishy - we should have been able to open the log file...
					$error_string = $e->getMessage();
					$this->log( 'details', sprintf( __('Zip process reported: Zip log file could not be opened - error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				
				}

				// Set convenience flags			
				$have_zip_warnings = ( 0 < $zip_warnings_count );
				$have_zip_errors = ( 0 < $zip_errors_count );
				$have_zip_additions = ( 0 < $zip_additions_count );
				$have_zip_debug = ( 0 < $zip_debug_count );
				$have_zip_other = ( 0 < $zip_other_count );
			
				// Always report the exit code regardless of whether we might ignore it or not
				$this->log( 'details', __('Zip process reported: Zip process exit code: ','it-l10n-backupbuddy' ) . $exitcode );
	
				// Always report the number of warnings - even just to confirm that we didn't have any
				$this->log( 'details', sprintf( __('Zip process reported: %1$s warning%2$s','it-l10n-backupbuddy' ), $zip_warnings_count, ( ( 1 == $zip_warnings_count ) ? '' : 's' ) ) );
				
				// Always report warnings regardless of whether user has selected to ignore them
				if ( true === $have_zip_warnings ) {
			
					$this->log_zip_reports( $zip_warnings, self::$_warning_desc, "WARNING", self::MAX_WARNING_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_WARNINGS_FILE_NAME );

				}
			
				// Always report other reports regardless
				if ( true === $have_zip_other ) {
			
					// Only report number of informationals if we have any as they are not that important
					$this->log( 'details', sprintf( __('Zip process reported: %1$s information%2$s','it-l10n-backupbuddy' ), $zip_other_count, ( ( 1 == $zip_other_count ) ? 'al' : 'als' ) ) );

					$this->log_zip_reports( $zip_other, self::$_other_desc, "INFORMATION", self::MAX_OTHER_LINES_TO_SHOW, dirname( dirname( $tempdir ) ) . DIRECTORY_SEPARATOR . 'pb_backupbuddy' . DIRECTORY_SEPARATOR . self::ZIP_OTHERS_FILE_NAME );

				}
			
				// See if we can figure out what happened - note that $exitcode could be non-zero for actionable warning(s) or error
				// if ( (no zip file) or (fatal exit code) or (not ignoring warnable exit code) )
				// TODO: Handle condition testing with function calls based on mapping exit codes to exit type (fatal vs non-fatal)
				if ( ( ! @file_exists( $temp_zip ) ) ||
					 ( ( 0 != $exitcode ) && ( 18 != $exitcode ) ) ||
					 ( ( 18 == $exitcode ) && !$this->get_ignore_warnings() ) ) {
				
					// If we have any zip errors reported show them regardless
					if ( true == $have_zip_errors ) {
					
						$this->log( 'details', sprintf( __('Zip process reported: %1$s error%2$s','it-l10n-backupbuddy' ), $zip_errors_count, ( ( 1 == $zip_errors_count ) ? '' : 's' )  ) );
					
						foreach ( $zip_errors as $line ) {
						
							$this->log( 'details', __( 'Zip process reported: ','it-l10n-backupbuddy' ) . $line );
						
						}
					
					}
					
					// Report whether or not the zip file was created (this will always be in the temporary location)			
					if ( ! @file_exists( $temp_zip ) ) {
					
						$this->log( 'details', __( 'Zip process reported: Zip Archive file not created - check process exit code.','it-l10n-backupbuddy' ) );
						
					} else {
						
						$this->log( 'details', __( 'Zip process reported: Zip Archive file created but with errors/actionable-warnings so will be deleted - check process exit code and warnings.','it-l10n-backupbuddy' ) );
	
					}
					
					// The operation has failed one way or another. Note that for pclzip the zip file is always created in the temporary
					// location regardless of whether the user selected to ignore errors or not (we can never guarantee to create a valid
					// zip file because the script might be terminated by the server so we must wait to produce a valid file and then
					// move it to the final location if it is valid).
					// Therefore if there is a zip file (produced but with warnings) it will not be visible and will be deleted when the
					// temporary directory is deleted below.
					
					$result = false;
					
				} else {
				
					// Got file with no error or warnings _or_ with warnings that the user has chosen to ignore
					// File always built in temporary location so always need to move it
					$this->log( 'details', __('Zip process reported: Moving Zip Archive file to local archive directory.','it-l10n-backupbuddy' ) );
					
					// Make sure no stale file information
					clearstatcache();
					
					// Relocate the temporary zip file to final location
					@rename( $temp_zip, $zip );
					
					// Check that we moved the file ok
					if ( @file_exists( $zip ) ) {
					
						$this->log( 'details', __('Zip process reported: Zip Archive file moved to local archive directory.','it-l10n-backupbuddy' ) );
						$this->log( 'message', __( 'Zip process reported: Zip Archive file successfully created with no errors (any actionable warnings ignored by user settings).','it-l10n-backupbuddy' ) );
						
						$this->log_archive_file_stats( $zip, array( 'content_size' => $total_size ) );
						
						// Temporary for now - try and incorporate into stats logging (makes the stats logging function part of the zip helper class?)
						$this->log( 'details', sprintf( __('Zip process reported: Zip Archive file size: %1$s of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ), $total_count ) );
				
						// Work out percentage on items
						if ( 0 < $total_count ) {
				
							$percentage_complete = (int)( ( ( $zm->get_added_dir_count() + $zm->get_added_file_count() - $zip_skipped_count ) / $total_count ) * 100 );
							$this->log( 'details', sprintf( __('Zip process reported: Zip archive file size: %1$s%% of %2$s (directories + files) actually added','it-l10n-backupbuddy' ), $percentage_complete, $total_count ) );

						}
						
						$result = true;
						
					} else {
					
						$this->log( 'details', __('Zip process reported: Zip Archive file could not be moved to local archive directory.','it-l10n-backupbuddy' ) );
						$result = false;
						
					}
									
				}

			}
			
			// Cleanup the temporary directory that will have all detritus and maybe incomplete zip file		
			$this->log( 'details', __('Zip process reported: Removing temporary directory.','it-l10n-backupbuddy' ) );
			
			if ( !( $this->delete_directory_recursive( $tempdir ) ) ) {
			
					$this->log( 'details', __('Zip process reported: Temporary directory could not be deleted: ','it-l10n-backupbuddy' ) . $tempdir );
			
			}
			
//		  	if ( null != $za ) { unset( $za ); }
		  	
			return $result;
															
		}
		
		/**
		 *	extract()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *	If no specific items given to extract then it's a complete unzip
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@param	array		$items						Mapping of what to extract and to what
		 *	@return	bool									true on success (all extractions successful), false otherwise
		 */
		public function extract( $zip_file, $destination_directory = '', $items = array() ) {
		
			$result = false;
		
			switch ( $this->get_os_type() ) {
				case self::OS_TYPE_NIX:
					if ( empty( $items ) ) {
						$result = $this->extract_generic_full( $zip_file, $destination_directory );
					} else {
						$result = $this->extract_generic_selected( $zip_file, $destination_directory, $items );					
					}
					break;
				case self::OS_TYPE_WIN:
					if ( empty( $items ) ) {
						$result = $this->extract_generic_full( $zip_file, $destination_directory );
					} else {
						$result = $this->extract_generic_selected( $zip_file, $destination_directory, $items );					
					}
					break;
				default:
					$result = false;
			}
			
			return $result;
			
		}

		/**
		 *	extract_generic_full()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@return	bool									true on success, false otherwise
		 */
		protected function extract_generic_full( $zip_file, $destination_directory = '' ) {
		
			$result = false;
			$za = null;
								
			// Update the definition before it is used by loading the library
			// This will not wok if perchance the file has already been loaded :-(
			// TODO: Need a temporary directory that we can use for this
			//define( 'PCLZIP_TEMPORARY_DIR', $tempdir );
			
			// This should give us a new archive object, if not catch it and bail out
			try {
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
			 	
				// Make sure we opened the zip ok and it has content
				if ( ( $content_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory ) ) !== 0 ) {
				
					// How many files - must be >0 to have got here
					$file_count = sizeof( $content_list );
					
					$this->log( 'details', sprintf( __('pclzip extracted file contents (%1$s to %2$s)','it-l10n-backupbuddy' ), $zip_file, $destination_directory ) );

					$this->log_archive_file_stats( $zip_file );	
					
					$result = true;
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( true );
					$this->log( 'details', sprintf( __('pclzip failed to open file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
							
			}
			
		  	if ( null != $za ) { unset( $za ); }		
			
			return $result;
			
		}

		/**
		 *	extract_generic_selected()
		 *
		 *	Extracts the contents of a zip file to the specified directory using the best unzip methods possible.
		 *
		 *	@param	string		$zip_file					Full path & filename of ZIP file to extract from.
		 *	@param	string		$destination_directory		Full directory path to extract into.
		 *	@param	array		$items						Mapping of what to extract and to what
		 *	@return	bool									true on success (all extractions successful), false otherwise
		 */
		protected function extract_generic_selected( $zip_file, $destination_directory = '', $items ) {
		
			$result = false;
			$za = null;
			$stat = array();
			
			// This should give us a new archive object, if not catch it and bail out
			try {
			
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated ziparchive but we couldn't find the class
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has content
				if ( ( $content_list = $za->listContent() ) !== 0 ) {
				
					// Now we need to take each item and run an unzip for it - unfortunately there is no easy way of combining
					// arbitrary extractions into a single command if some might be to a 
					foreach ( $items as $what => $where ) {
			
						$rename_required = false;
						$result = false;
				
						// Decide how to extract based on where
						if ( empty( $where) ) {
					
							// First we'll extract and junk the path
							// Note: For some odd reason when we have a $what file that is a hidden (dot) file
							// the file_exists() test in pclzip for the filepath to extract to returns true even
							// though only the parent directory exists and not the file itself. No idea why at
							// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
							// so the fact the test returns true is ignored.
							$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_NAME, $what, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_REPLACE_NEWER );
								
							// Check whether we succeeded or not (would only be no list array for a zip file problem)
							// but extraction of the file itself may still have failed
							$result = ( $extract_list !== 0  && ( $extract_list[ 0 ][ 'status' ] == 'ok' ) );
															
						} elseif ( !empty( $where ) ) {
					
							if ( $what === $where ) {
							
								// Check for wildcard directory extraction like dir/* => dir/*
								if ( "*" == substr( trim( $what ), -1 ) ) {

									// Turn this into a preg_match pattern
									$whatmatch = "|^" . $what . "|";									

									// First we'll extract but we're not junking the paths
									// Note: For some odd reason when we have a $what file that is a hidden (dot) file
									// the file_exists() test in pclzip for the filepath to extract to returns true even
									// though only the parent directory exists and not the file itself. No idea why at
									// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
									// so the fact the test returns true is ignored.
									$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_PREG, $whatmatch, PCLZIP_OPT_REPLACE_NEWER );

									// Check whether we succeeded or not (would only be no list array for a zip file problem)
									// but extraction of individual files themselves may still have failed
									if ( 0 !== $extract_list ) {
									
										// So far so good - assume everything will be ok
										$result = true;
	
										// At least we got no major failure so check the extracted files
										foreach ( $extract_list as $file ) {
										
											if ( 'ok' !== $file[ 'status' ] ) {
											
												// Oops - we found a file that didn't extract ok so bail out with false
												$result = false;
												break;
											
											}
										
										}
									
									}
								
								} else {
								
									// It's just a single file extraction - breath a sign of relief
									// Extract to same directory structure - don't junk path, no need to add where to destnation as automatic
									// Note: For some odd reason when we have a $what file that is a hidden (dot) file
									// the file_exists() test in pclzip for the filepath to extract to returns true even
									// though only the parent directory exists and not the file itself. No idea why at
									// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
									// so the fact the test returns true is ignored.
									$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_NAME, $what, PCLZIP_OPT_REPLACE_NEWER );
									
									// Check whether we succeeded or not (would only be no list array for a zip file problem)
									// but extraction of the file itself may still have failed
									$result = ( $extract_list !== 0  && ( $extract_list[ 0 ][ 'status' ] == 'ok' ) );

								}
						
							} else {

								// First we'll extract and junk the path
								// Note: For some odd reason when we have a $what file that is a hidden (dot) file
								// the file_exists() test in pclzip for the filepath to extract to returns true even
								// though only the parent directory exists and not the file itself. No idea why at
								// present. Because of that we have to use the PCL_ZIP_OPT_REPLACE_NEWER option
								// so the fact the test returns true is ignored.
								$extract_list = $za->extract( PCLZIP_OPT_PATH, $destination_directory, PCLZIP_OPT_BY_NAME, $what, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_REPLACE_NEWER );
																							
								// Check whether we succeeded or not (would only be no list array for a zip file problem)
								// but extraction of the file itself may still have failed
								$result = ( $extract_list !== 0  && ( $extract_list[ 0 ][ 'status' ] == 'ok' ) );

								// Will need to rename if the extract is ok
								$rename_required = true;
						
							}
					
						}
				
						// Note: we don't open the file and then do stuff but it's all done in one action
						// so we need to interpret the return code to dedide what to do
						// Currently we can only distinguish between success and failure but no finer grain
						if ( true === $result ) {
					
							$this->log( 'details', sprintf( __('pclzip extracted file contents (%1$s from %2$s to %3$s%4$s)','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where ) );

							// Rename if we have to
							if ( true === $rename_required) {
							
								// Note: we junked the path on the extraction so just the filename of $what is the source but
								// $where could be a simple file name or a file path 
								$result = $result && rename( $destination_directory . DIRECTORY_SEPARATOR . basename( $what ),
															 $destination_directory . DIRECTORY_SEPARATOR . $where );
							
							}

						} else {
					
							// For now let's just print the error code and drop through
							$error_string = $za->errorInfo();
							$this->log( 'details', sprintf( __('pclzip failed to open/process file to extract file contents (%1$s from %2$s to %3$s%4$s) - Error Info: %5$s.','it-l10n-backupbuddy' ), $what, $zip_file, $destination_directory, $where, $error_string ) );
					
							// May seem redundant but belt'n'braces
							$result = false;
							
						}
					
						// If the extraction failed (or rename after extraction) then break out of the foreach and simply return false
						if ( false === $result ) {
					
							break;
						
						}
					
					}
				
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( $result );
					$this->log( 'details', sprintf( __('pclzip failed to open file to extract contents (%1$s to %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $zip_file, $destination_directory, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
				
				$za->close();
			
			}
			
		  	if ( null != $za ) { unset( $za ); }		
			
			return $result;
			
		}
		
		/**
		 *	file_exists()
		 *	
		 *	Tests whether a file (with path) exists in the given zip file
		 *	If leave_open is true then the zip object will be left open for faster checking for subsequent files within this zip
		 *	
		 *	@param		string	$zip_file		The zip file to check
		 *	@param		string	$locate_file	The file to test for
		 *	@param		bool	$leave_open		Optional: True if the zip file should be left open
		 *	@return		bool/array				True if the file is found in the zip and false if not, array for other problem
		 *
		 */
		public function file_exists( $zip_file, $locate_file, $leave_open = false ) {
		
			$result = array( 1, "Generic failure indication" );
			$za = null;
			$stat = array();
								
			
			// This should give us a new archive object, of not catch it and bail out
			try {
			
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );

				// Return an error code and a description - this needs to be handled more generically
				$result = array( 1, "Class not available to match method" );
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has content
				if ( ( $content_list = $za->listContent() ) !== 0 ) {
				
					// Assume failure
					$result = false;
					
					// Get each file in sequence by index and get the properties
					for ( $i = 0; $i < sizeof( $content_list ); $i++ ) {
					
						$stat = $content_list[ $i ];
						
						// Assume the key exists (consider testing)
						if ( $stat[ 'filename' ] == $locate_file ) {
						
							// File found so we can note that
							$this->log( 'details', __('File found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
							$result = true;
							
							// Need to exit the for loop
							break;
							
						}
						
					}
					
					if ( false === $result ) {
					
						// Only get here if the file wasn't found
						$this->log( 'details', __('File not found (pclzip)','it-l10n-backupbuddy' ) . ': ' . $locate_file );
						
					}

				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( true );
					$this->log( 'details', sprintf( __('pclzip failed to open file to check if file exists (looking for %1$s in %2$s) - Error Info: %3$s.','it-l10n-backupbuddy' ), $locate_file , $zip_file, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					$result = array( 1, "Failed to open/process file" );

				}
							
			}
			
		  	if ( null != $za ) { unset( $za ); }		
			
			return $result;
				
		}
				
		/*	get_file_list()
		 *	
		 *	Get an array of all files in a zip file with some file properties.
		 *	
		 *	@param		string		$zip_file	The file to list the content of
		 *	@return		bool|array				false on failure, otherwise array of file properties (may be empty)
		 */
		public function get_file_list( $zip_file ) {
		
			$file_list = array();
			$result = false;
			$za = null;
			$stat = array();
								
			// This should give us a new archive object, of not catch it and bail out
			try {
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has content
				if ( 0 !== ( $content_list = $za->listContent() ) ) {
				
					// How many files - must be >0 to have got here
					$file_count = sizeof( $content_list );
					
					// Get each file in sequence by index and get the properties
					for ( $i = 0; $i < $file_count; $i++ ) {
					
						$stat = $content_list[ $i ];
						
						// Assume all these keys do exist (consider testing)
						$file_list[] = array(
							$stat[ 'filename' ],
							$stat[ 'size' ],
							$stat[ 'compressed_size' ],
							$stat[ 'mtime' ]
						);
												
					}
					
					$this->log( 'details', sprintf( __('pclzip listed file contents (%1$s)','it-l10n-backupbuddy' ), $zip_file ) );

					$this->log_archive_file_stats( $zip_file );
					
					$result = &$file_list;
					
				} else {
				
					// Couldn't open archive - will return for maybe another method to try
					$error_string = $za->errorInfo( true );
					$this->log( 'details', sprintf( __('pclzip failed to open file to list contents (%1$s) - Error Info: %2$s.','it-l10n-backupbuddy' ), $zip_file, $error_string ) );

					// Return an error code and a description - this needs to be handled more generically
					//$result = array( 1, "Unable to get archive contents" );
					// Currently as we are returning an array as a valid result we just return false on failure
					$result = false;

				}
							
			} 
			
		  	if ( null != $za ) { unset( $za ); }		
			
			return $result;
				
		}
		
		/*	set_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string			$zip_file		Filename of archive to set comment on.
		 *	@param		string			$comment		Comment to apply to archive.
		 *	@return		bool							true on success, otherwise false.
		 */
		public function set_comment( $zip_file, $comment ) {
		
			$result = false;
			$za = null;
			
			// This should give us a new archive object, of not catch it and bail out
			try {
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and we added the comment ok
				// Note: using empty array as we don't actually want to add any files
				if ( 0 !== ( $list = $za->add( array(), PCLZIP_OPT_COMMENT, $comment ) ) ) {
				
					// We got a list back so adding comment should have been successful
					$this->log( 'details', sprintf( __('PclZip set comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
					$result = true;
					
				} else {
				
					// If we failed to set the commnent then log it (?) and drop through
					$error_string = $za->errorInfo( true );
					$this->log( 'details', sprintf( __('PclZip failed to set comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					$result = false;
										
				}
			
			}
			
		  	if ( null != $za ) { unset( $za ); }		
			
			return $result;
				
		}

		/*	get_comment()
		 *	
		 *	Retrieve archive comment.
		 *	
		 *	@param		string		$zip_file		Filename of archive to retrieve comment from.
		 *	@return		bool|string					false on failure, Zip comment otherwise.
		 */
		public function get_comment( $zip_file ) {
		
			$result = false;
			$za = null;
			
			// This should give us a new archive object, of not catch it and bail out
			try {
					
				$za = new pluginbuddy_PclZip( $zip_file );
				$result = true;
				
			} catch ( Exception $e ) {
			
				// Something fishy - the methods indicated pclzip but we couldn't find the class
				$error_string = $e->getMessage();
				$this->log( 'details', sprintf( __('pclzip indicated as available method but error reported: %1$s','it-l10n-backupbuddy' ), $error_string ) );
				$result = false;
				
			}
			
			// Only continue if we have a valid archive object
			if ( true === $result ) {
				
				// Make sure we opened the zip ok and it has properties
				if ( 0 !== ( $properties = $za->properties() ) ) {

					// Because comment may have been added by zip utility it may have been split over
					// multiple lines so we need to "unsplit" it - need to check for different possible
					// line endings
					$lines = preg_split( "/\r\n|\n|\r/", $properties[ 'comment' ] );

					// Now convert back to a string but with line endings removed
					$comment = implode( "", $lines );

					// We got properties so should have a comment to return, even if empty
					$this->log( 'details', sprintf( __('PclZip retrieved comment in file %1$s','it-l10n-backupbuddy' ), $zip_file ) );
					$result = $comment;

				} else {

					// If we failed to get the commnent then log it (?) and drop through
					$error_string = $za->errorInfo( true );
					$this->log( 'details', sprintf( __('PclZip failed to retrieve comment in file %1$s - Error Info: %2$s','it-l10n-backupbuddy' ), $zip_file, $error_string ) );
					$result = false;

				}
			
			}
			
		  	if ( null != $za ) { unset( $za ); }		
			
			return $result;
				
		}
		
	} // end pluginbuddy_zbzippclzip class.	
	
}
?>
