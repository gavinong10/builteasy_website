<?php



/*	class pluginbuddy_format
 *	@author Dustin Bolton
 *	
 *	Helps format content or data such as time, date, file size, etc.
 */
class pb_backupbuddy_format {
	
	
	
	// ********** PUBLIC PROPERTIES **********
	
	
	
	// ********** PRIVATE PROPERTIES **********
	private $_timestamp = 'M j, Y g:i:s a';
	
	
	// ********** FUNCTIONS **********
	
	
	
	/*	pluginbuddy_format->__construct()
	 *	
	 *	Default constructor.
	 *	
	 *	@return		null
	 */
	function __construct() {
	} // End __construct().
	
	
	
	/*	pluginbuddy_format->file_size()
	 *	
	 *	Takes a file size in bytes and transforms it into a human readable format with more friendly units. Decides on unit based on the size.
	 *	
	 *	@param		int		$size	File size.
	 *	@return		string			Human formatted friendly readable format.
	 */
	function file_size( $size ) {
		$sizes = array( ' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
		if ( $size == 0 ) {
			return( '0 MB' );
		} else {
			return ( round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), $i > 1 ? 2 : 0) . $sizes[$i] );
		}
	} // End file_size().
	
	
	
	/*	pluginbuddy_format->date()
	 *	
	 *	Formats a timestamp into a nice human date format.
	 *	
	 *	@param		int		$timestamp		Timestamp to make pretty.
	 *	@param		string	$customFormat	Custom timestamp format. Else uses $this->_timestamp defined at top of this file.
	 *	@return		string					Pretty human timestamp.
	 */
	function date( $timestamp, $customFormat = '' ) {
		if ( '' == $customFormat ) {
			return date( $this->_timestamp, $timestamp );
		} else {
			return date( $customFormat, $timestamp );
		}
	} // End date().
	
	
	
	/*	pluginbuddy_format->unlocalize_time()
	 *	
	 *	Removes the timezone offset of a localized time display for a user.
	 *	
	 *	@param		int		$timestamp		Timestamp to remove time offset for.
	 *	@return		int						Corrected timestamp.
	 */
	function localize_time( $timestamp ) {
		if ( function_exists( 'get_option' ) ) {
			$gmt_offset = get_option( 'gmt_offset' );
		} else {
			$gmt_offset = 0;
		}
		return $timestamp + ( $gmt_offset * 3600 );
	} // End localize_time().
	
	
	
	/*	pluginbuddy_format->unlocalize_time()
	 *	
	 *	Removes the timezone offset of a localized time display for a user.
	 *	
	 *	@param		int		$timestamp		Timestamp to remove time offset for.
	 *	@return		int						Corrected timestamp.
	 */
	function unlocalize_time( $timestamp ) {
		return $timestamp - ( get_option( 'gmt_offset' ) * 3600 );
	} // End unlocalize_time().
	
	
	
	/*	pluginbuddy_format->time_ago()
	 *	
	 *	Accepts NON-localized timestamps.
	 *	@see time_duration
	 *	
	 *	@param		
	 *	@return		
	 */
	 // TODO: deprecated?
	function time_ago( $timestamp ) {
		return human_time_diff( $timestamp, time() );
	} // End time_ago().
	
	
	
	/*	pluginbuddy_format->duration()
	 *	
	 *	Returns a human readable duration. Useful for time ago or countdowns.
	 *	Ex: 5 hours, 4 minutes, 43 seconds.
	 *	
	 *	@param		int		$seconds		Number of seconds to turn into a human friendly readable format.
	 *	@return				string			Human readable string duration.
	 */
	function time_duration( $seconds ) {
		$time = time() - $seconds;
		
		$periods = array(__('second', 'it-l10n-backupbuddy' ),
						 __('minute', 'it-l10n-backupbuddy' ),
						 __('hour',   'it-l10n-backupbuddy' ),
						 __('day', 	  'it-l10n-backupbuddy' ),
						 __('week',   'it-l10n-backupbuddy' ),
						 __('month',  'it-l10n-backupbuddy' ),
						 __('year',   'it-l10n-backupbuddy' ),
						 __('decade'. 'LION' )
						 );
		$lengths = array('60','60','24','7','4.35','12','10');
		
		$now = time();
		
		$difference = $now - $time;
		$tense = __('ago', 'it-l10n-backupbuddy' );
		
		
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
		
		$difference = round($difference);
		
		if($difference != 1) {
			$periods[$j].= "s";
		}
		
		return "$difference $periods[$j]";
	} // End duration().
	
	
	
	/*	prettify()
	 *	
	 *	Takes a string and returns a more pretty version. Looks in an array for a key matching the string.
	 *	Returns the associated value. Returns original value if no pretty replacer is found.
	 *	Ex:
	 *		prettify( 'dog', array( 'cats' => 'Cats', 'dog' => 'Dog' ) );
	 *		Returns: Dog
	 *	
	 *	@param		string		$value			Value to be replaced with a pretty version.
	 *	@param		array		$replacements	Array of: value to look for => value to replace with.
	 *	@return		string						Pretty version that replaced $value. Returns original $value if not found in $replacements keys.
	 */
	public function prettify( $value, $replacements ) {
		
		if ( isset( $replacements[$value] ) ) { // Found replacement.
			return $replacements[$value];
		} else { // No replacement; return original value.
			return $value;
		}
		
	} // End prettify();
	
	
	
	/* multi_implode()
	 *
	 * Deep recursive implosion.
	 *
	 */
	public function multi_implode($array, $glue) {
	    $ret = '';

	    foreach ($array as $item) {
	        if (is_array($item)) {
	            $ret .= $this->multi_implode($item, $glue) . $glue;
	        } else {
	            $ret .= $item . $glue;
	        }
	    }

	    $ret = substr($ret, 0, 0-strlen($glue));

	    return $ret;
	} // End multi_implode().
	
	
	
} // End class pluginbuddy_settings.



?>