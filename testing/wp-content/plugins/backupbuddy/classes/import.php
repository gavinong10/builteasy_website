<?php
class pb_backupbuddy_import {
	
	
	
	
	
	
	
	/**
	 *	wipePrefix()
	 *
	 *	Clear out tables matching supplied prefix.
	 *
	 *	@return			boolean		Currently always true.
	 */
	function wipePrefix( $prefix, $confirm = false ) {
		if ( $confirm !== true ) {
			die( 'Error #5466566b: Parameter 2 to wipePrefix() must be boolean true to proceed.' );
		}
		
		if ( $prefix == '' ) {
			pb_backupbuddy::status( 'warning', 'No database prefix specified to wipe.' );
			return false;
		}
		pb_backupbuddy::status( 'message', 'Beginning wipe of database tables matching prefix `' . $prefix . '`...' );
		
		// Connect to database.
		//$this->connect_database();
		
		global $wpdb;
		$rows = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_name LIKE '" . backupbuddy_core::dbEscape( str_replace( '_', '\_', $prefix ) ) . "%' AND table_schema = DATABASE()", ARRAY_A );
		$table_wipe_count = count( $rows );
		foreach( $rows as $row ) {
			pb_backupbuddy::status( 'details', 'Dropping table `' . $row['table_name'] . '`.' );
			$wpdb->query( 'DROP TABLE `' . $row['table_name'] . '`' );
		}
		unset( $rows );
		pb_backupbuddy::status( 'message', 'Wiped database of ' . $table_wipe_count . ' tables.' );
		
		return true;
	} // End wipePrefix().
	
	
	
	/**
	 *	wipeDatabase()
	 *
	 *	Clear out the existing database to prepare for importing new data.
	 *
	 *	@return			boolean		Currently always true.
	 */
	function wipeDatabase( $confirm = false ) {
		if ( $confirm !== true ) {
			die( 'Error #5466566a: Parameter 1 to wipeDdatabase() must be boolean true to proceed.' );
		}
		
		pb_backupbuddy::status( 'message', 'Beginning wipe of ALL database tables...' );
		
		// Connect to database.
		//$this->connect_database();
		
		global $wpdb;
		$rows = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()", ARRAY_A );
		$table_wipe_count = count( $rows );
		foreach( $rows as $row ) {
			pb_backupbuddy::status( 'details', 'Dropping table `' . $row['table_name'] . '`.' );
			$wpdb->query( 'DROP TABLE `' . $row['table_name'] . '`' );
		}
		unset( $rows );
		pb_backupbuddy::status( 'message', 'Wiped database of ' . $table_wipe_count . ' tables.' );
		
		return true;
	} // End wipeDatabase().
	
	
	
	/*	preg_escape_back()
	 *	
	 *	Escape backreferences from string for use with regex. Used by migrate_wp_config().
	 *	@see migrate_wp_config()
	 *	
	 *	@param		string		$string		String to escape.
	 *	@return		string					Escaped string.
	 */
	function preg_escape_back($string) {
		// Replace $ with \$ and \ with \\
		$string = preg_replace('#(?<!\\\\)(\\$|\\\\)#', '\\\\$1', $string);
		return $string;
	} // End preg_escape_back().
	
	
	
	
	
	
	
	// TODO: switch to using pb_backupbuddy::status_box() instead.
	/**
	 *	status_box()
	 *
	 *	Displays a textarea for placing status text into.
	 *
	 *	@param			$default_text	string		First line of text to display.
	 *	@param			boolean			$hidden		Whether or not to apply display: none; CSS.
	 *	@return							string		HTML for textarea.
	 */
	function status_box( $default_text = '', $hidden = false ) {
		define( 'PB_STATUS', true ); // Tells framework status() function to output future logging info into status box via javascript.
		$return = '<div id="pb_backupbuddy_status_wrap" style="padding: 0;"><pre readonly="readonly" id="backupbuddy_messages" wrap="off"';
		if ( $hidden === true ) {
			$return .= ' style="display: none; "';
		}
		$return .= '>' . $default_text . '</pre>';
		$return .= '<div style="text-align: center;">
			<button class="button button-primary" onClick="backupbuddy_saveLogAsFile();" style="margin-left: auto; margin-right: auto; display: inherit; font-size: 0.9em;">Download Status Log (.txt)</button>
		</div>';
		$return .= '</div>';
		
		return $return;
	}
	
	
	
	
	
	
} // End class.
?>
