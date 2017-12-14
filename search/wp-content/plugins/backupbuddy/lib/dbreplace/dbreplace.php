<?php
/**
 *	pluginbuddy_dbreplace Class
 *
 *	Handles replacement of data in a table/database, text or serialized. A database connection should be initialized before instantiation.
 *	
 *	@since 1.0.0
 *	@author Dustin Bolton
 *
 *	@param		$status_callback		object		Optional object containing the status() function for reporting back information.
 *	@return		null
 *
 */
if (!class_exists("pluginbuddy_dbreplace")) {
	class pluginbuddy_dbreplace {
		var $_version = '1.0';
		
		var $startTime;
		var $timeWiggleRoom;
		var $maxExecutionTime;
		
		const MAX_ROWS_PER_SELECT = 500;
		
		
		/**
		 *	__construct()
		 *	
		 *	Default constructor. Sets up optional status() function class if applicable.
		 *	
		 *	@param		reference	&$status_callback		[optional] Reference to the class containing the status() function for status updates.
		 *	@return		null
		 *
		 */
		function __construct( $startTime = '', $timeWiggleRoom = 1, $maxExecutionTime = 30 ) {
			if ( '' == $startTime ) {
				$this->startTime = microtime( true );
			} else {
				$this->startTime = $startTime;
			}
			$this->timeWiggleRoom = $timeWiggleRoom;
			$this->maxExecutionTime = $maxExecutionTime;
		}
		
		
		
		/**
		 *	text()
		 *	
		 *	Replaces text within a table by specifying the table, rows to replace within and the old and new value(s).
		 *	
		 *	@param		string		$table		Table to replace text in.
		 *	@param		mixed		$olds		Old value(s) to find for replacement. May be a string or array of values.
		 *	@param		mixed		$news		New value(s) to be replaced with. May be a string or array. If array there must be the same number of values as $olds.
		 *	@param		mixed		$rows		Table row(s) to replace within. May be an array of tables.
		 *	@return		null
		 *
		 */
		public function text( $table, $olds, $news, $rows ) {
			$rows_sql = array();
			
			if ( !is_array( $olds ) ) {
				$olds = array( $olds );
			}
			if ( !is_array( $news ) ) {
				$news = array( $news );
			}
			if ( !is_array( $rows ) ) {
				$rows = array( $rows );
			}
			
			// Prevent trying to replace data with the same data for performance.
			$this->remove_matching_array_elements( $olds, $news );
			
			foreach ( $rows as $row ) {
				$i = 0;
				foreach ( $olds as $old ) {
					$rows_sql[] = $row . " = replace( {$row}, '{$old}', '{$news[$i]}')";
					$i++;
				}
			}
			
			global $wpdb;
			$wpdb->query( "UPDATE `{$table}` SET " . implode( ',', $rows_sql ) . ";" );
			
			return true;
			
		} // End text().
		
		
		/**
		 *	serialized()
		 *	
		 *	Replaces serialized text within a table by specifying the table, rows to replace within and the old and new value(s).
		 *	
		 *	@param		string		$table		Table to replace text in.
		 *	@param		mixed		$olds		Old value(s) to find for replacement. May be a string or array of values.
		 *	@param		mixed		$news		New value(s) to be replaced with. May be a string or array. If array there must be the same number of values as $olds.
		 *	@param		mixed		$rows		Table row(s) to replace within. May be an array of tables.
		 *	@param		int			$rows_start	Row to start at. Used for resuming.
		 *	@return		null
		 *
		 */
		public function serialized( $table, $olds, $news, $rows, $rows_start = '' ) {
			if ( !is_array( $olds ) ) {
				$olds = array( $olds );
			}
			if ( !is_array( $news ) ) {
				$news = array( $news );
			}
			if ( !is_array( $rows ) ) {
				$rows = array( $rows );
			}
			
			global $wpdb;
			
			// Get the total row count for this table
			$total_rows = 0;
			$tables_status = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
			
			foreach ( $tables_status as $table_status ) {
			
				if ( $table === $table_status[ 'Name' ] ) {
				
					// Fix up row count and average row length for InnoDB engine which returns inaccurate
					// (and changing) values for these
					if ( 'InnoDB' === $table_status[ 'Engine' ] ) {
						if ( false !== ( $count = $wpdb->get_var( "SELECT COUNT(1) FROM `{$table_status['Name']}`" ) ) ) {
							$table_status[ 'Rows' ] = $count;
						}
					}
			
					$total_rows = $table_status[ 'Rows' ];
				
				}
			
			}
			
			// Prevent trying to replace data with the same data for performance.
			$this->remove_matching_array_elements( $olds, $news );
			$key_results = $wpdb->get_results( "SHOW KEYS FROM `{$table}` WHERE Key_name='PRIMARY';", ARRAY_A );
			if ( $key_results === false ) {
				pb_backupbuddy::status( 'details', 'Table `' . $table . '` does not exist; skipping migration of this table.' );
				return;
			}
			
			// No primary key found; unsafe to edit this table. @since 2.2.32.
			if ( count( $key_results ) == 0 ) {
				pb_backupbuddy::status( 'message', 'Error #9029: Warning only! Table `'.  $table .'` does not contain a primary key; BackupBuddy cannot safely modify the contents of this table. Skipping migration of this table. (serialized()).' );
				return true;
			}
			
			$primary_key = $key_results[0]['Column_name'];
			unset( $key_result );
			
			$updated = false; // Was something in DB updated?
			$rows_remain = true; // More rows remaining / aka another query for more rows needed.
			if ( '' == $rows_start ) {
				$rows_start = 0;
			}
			pb_backupbuddy::status( 'details', 'Finding rows in table `' . $table . '` to update.' );
			while ( true === $rows_remain ) { // Keep looping through rows until none remain. Looping through like this to limit memory usage as wpdb classes loads all results into memory.
				$rowsResult = $wpdb->get_results( "SELECT `" . implode( '`,`', $rows ) . "`,`{$primary_key}` FROM `{$table}` LIMIT " . $rows_start . ',' . self::MAX_ROWS_PER_SELECT );
				$rowsCount = count( $rowsResult );

				// Provide an update on progress
				if ( 0 === $rowsCount ) {
					pb_backupbuddy::status( 'details', 'Table: `'. $table . '` - processing ' . $rowsCount . ' rows ( of ' . $total_rows . ' )' );				
				} else {
					pb_backupbuddy::status( 'details', 'Table: `'. $table . '` - processing ' . $rowsCount . ' rows ( Rows ' . $rows_start . '-' . ( $rows_start + $rowsCount - 1 ) . ' of ' . $total_rows . ' )' );	
				}
				
				$rows_start += self::MAX_ROWS_PER_SELECT; // Next loop we will begin at this offset.
				if ( ( 0 == $rowsCount ) || ( $rowsCount < self::MAX_ROWS_PER_SELECT ) ) {
					$rows_remain = false;
				}
				
				foreach( $rowsResult as $row ) {
					$needs_update = false;
					$sql_update = array();
					
					foreach( $row as $column => $value ) {
						if ( $column != $primary_key ) {
							if ( false !== ( $edited_data = $this->replace_maybe_serialized( $value, $olds, $news ) ) ) { // Data changed.
								$needs_update = true;
								$sql_update[] = $column . "= '" . backupbuddy_core::dbEscape( $edited_data ) . "'";
							}
						} else {
							$primary_key_value = $value;
						}
					}
					
					if ( $needs_update === true ) {
						$updated = true;
						$wpdb->query( "UPDATE `{$table}` SET " . implode( ',', $sql_update ) . " WHERE `{$primary_key}` = '{$primary_key_value}' LIMIT 1" );
					}
				}
				unset( $rowsResult );
				
				// See how we are doing on time. Trigger chunking if needed.
				if ( ( ( microtime( true ) - $this->startTime ) + $this->timeWiggleRoom ) >= $this->maxExecutionTime ) {
					return array( $rows_start );
				}
			}
			
			if ( $updated === true ) {
				pb_backupbuddy::status( 'details', 'Updated serialized data in table `' . $table . '`.' );
			} else {
				pb_backupbuddy::status( 'details', 'Nothing found to update in table `' . $table . '`.' );
			}
			return true;
		} // End serialized().
		
		
		/**
		 *	replace_maybe_serialized()
		 *	
		 *	Replaces possibly serialized (or non-serialized) text if a change is needed. Returns false if there was no change.
		 *  Note: As of BB v3.2.x supports double serialized data.
		 *	
		 *	@param		string		$table		Text (possibly serialized) to update.
		 *	@param		mixed		$olds		Text to search for to replace. May be an array of strings to search for.
		 *	@param		mixed		$news		New value(s) to be replaced with. May be a string or array. If array there must be the same number of values as $olds.
		 *	@return		mixed					Returns modified string data if serialized data was replaced. False if no change was made.
		 *
		 */
		function replace_maybe_serialized( $data, $olds, $news ) {
			if ( !is_array( $olds ) ) {
				$olds = array( $olds );
			}
			if ( !is_array( $news ) ) {
				$news = array( $news );
			}
			
			$type = '';
			$unserialized = false; // first assume not serialized data
			if ( is_serialized( $data ) ) { // check if this is serialized data
				$unserialized = @unserialize( $data ); // unserialise - if false is returned we won't try to process it as serialised.
			}
			if ( $unserialized !== false ) { // Serialized data.
				$type = 'serialized';
				
				$double_serialized = false;
				if ( is_serialized( $unserialized ) ) { // double-serialized data (opposite of a double rainbow). Some plugins seem to double-serialize for some unknown wacky reason...
					$unserialized = @unserialize( $unserialized ); // unserialise - if false is returned we won't try to process it as serialised.
					$double_serialized = true;
				}
				
				$i = 0;
				foreach ( $olds as $old ) {
					$this->recursive_array_replace( $old, $news[$i], $unserialized );
					$i++;
				}
				
				$edited_data = serialize( $unserialized );
				if ( true === $double_serialized ) {
					$edited_data = serialize( $edited_data );
				}
				
			}	else { // Non-serialized data.
				$type = 'text';
				$edited_data = $data;
				$i = 0;
				foreach ( $olds as $old ) {
					$edited_data =str_ireplace( $old, $news[$i], $edited_data );
					$i++;
				}
			}
			
			// Return the results.
			if ( $data != $edited_data ) {
				return $edited_data;
			} else {
				return false;
			}
		} // End replace_maybe_serialized().
		
		
		/**
		 *	bruteforce_table()
		 *
		 *	!!! HANDLES SERIALIZED DATA !!!!
		 *	Replaces text, serialized or not, within the entire table. Bruteforce method iterates through every row & column in the entire table and replaces if needed.
		 *	
		 *	@param		string		$table		Text (possibly serialized) to update.
		 *	@param		mixed		$olds		Text to search for to replace. May be an array of strings to search for.
		 *	@param		mixed		$news		New value(s) to be replaced with. May be a string or array. If array there must be the same number of values as $olds.
		 *	@return		int						Number of rows changed.
		 *
		 */
		function bruteforce_table( $table, $olds, $news, $rows_start = '' ) {
			pb_backupbuddy::status( 'message', 'Starting brute force data migration for table `' . $table . '`...' );
			if ( !is_array( $olds ) ) {
				$olds = array( $olds );
			}
			if ( !is_array( $news ) ) {
				$news = array( $news );
			}
			
			$count_items_checked = 0;
			$count_items_changed = 0;
			
			global $wpdb;
			
			// Get the total row count for this table
			$total_rows = 0;
			$tables_status = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
			
			foreach ( $tables_status as $table_status ) {
			
				if ( $table === $table_status[ 'Name' ] ) {
				
					// Fix up row count and average row length for InnoDB engine which returns inaccurate
					// (and changing) values for these
					if ( 'InnoDB' === $table_status[ 'Engine' ] ) {
						if ( false !== ( $count = $wpdb->get_var( "SELECT COUNT(1) FROM `{$table_status['Name']}`" ) ) ) {
							$table_status[ 'Rows' ] = $count;
						}
					}
			
					$total_rows = $table_status[ 'Rows' ];
				
				}
			
			}
			
			$fields = $wpdb->get_results( "DESCRIBE `{$table}`", ARRAY_A );
			$index_fields = '';  // Reset fields for each table.
			$column_name = '';
			$table_index = '';
			$i = 0;
			
			$found_primary_key = false;
			
			foreach( $fields as $field ) {
				$column_name[$i++] = $field['Field'];
				if ( $field['Key'] == 'PRI' ) {
					$table_index[$i] = true;
					$found_primary_key = true;
				}
			}
			
			// Skips migration of this table if there is no primary key. Modifying on any other key is not safe. mysql automatically returns a PRIMARY if a UNIQUE non-primary is found according to http://dev.mysql.com/doc/refman/5.1/en/create-table.html  @since 2.2.32.
			if ( $found_primary_key === false ) {
				pb_backupbuddy::status( 'warning', 'Error #9029b: Warning only! Table `' . $table . '` does not contain a primary key; BackupBuddy cannot safely modify the contents of this table. Skipping migration of this table. (bruteforce_table()).' );
				return true;
			}
			
			$row_loop = 0;

			$rows_remain = true; // More rows remaining / aka another query for more rows needed.
			if ( '' == $rows_start ) {
				$rows_start = 0;
			}

			while ( true === $rows_remain ) { // Keep looping through rows until none remain. Looping through like this to limit memory usage as wpdb classes loads all results into memory.

				$data = $wpdb->get_results( "SELECT * FROM `{$table}` LIMIT {$rows_start}," . self::MAX_ROWS_PER_SELECT, ARRAY_A );
				if ( false === $data ) {
					pb_backupbuddy::status( 'error', 'ERROR #44545343 ... SQL ERROR: ' . $wpdb->last_error );
				}
				
				// Provide an update on progress
				$rowsCount = count( $data );
				if ( 0 === $rowsCount ) {
					pb_backupbuddy::status( 'details', 'Table: `'. $table . '` - processing ' . $rowsCount . ' rows ( of ' . $total_rows . ' )' );				
				} else {
					pb_backupbuddy::status( 'details', 'Table: `'. $table . '` - processing ' . $rowsCount . ' rows ( Rows ' . $rows_start . '-' . ( $rows_start + $rowsCount - 1 ) . ' of ' . $total_rows . ' )' );	
				}
				
				$rows_start += self::MAX_ROWS_PER_SELECT; // Next loop we will begin at this offset.
				if ( ( 0 == $rowsCount ) || ( $rowsCount < self::MAX_ROWS_PER_SELECT ) ) {
					$rows_remain = false;
				}
		
				foreach( $data as $row ) {
					$need_to_update = false;
					$UPDATE_SQL = 'UPDATE `' . $table . '` SET ';
					$WHERE_SQL = ' WHERE ';
				
					$j = 0;
					foreach ( $column_name as $current_column ) {
						$j++;
						$count_items_checked++;
					
						$data_to_fix = $row[$current_column];
						if ( false !== ( $edited_data = $this->replace_maybe_serialized( $data_to_fix, $olds, $news ) ) ) { // no change needed
							$count_items_changed++;
							if ( $need_to_update != false ) { // If this isn't our first time here, we need to add a comma.
								$UPDATE_SQL = $UPDATE_SQL . ',';
							}
							$UPDATE_SQL = $UPDATE_SQL . ' ' . $current_column . ' = "' . backupbuddy_core::dbEscape( $edited_data ) . '"';
							$need_to_update = true; // Only set if we need to update - avoids wasted UPDATE statements.
						}
					
						if ( isset( $table_index[$j] ) ) {
							$WHERE_SQL = $WHERE_SQL . '`' . $current_column . '` = "' . $row[$current_column] . '" AND ';
						}
					}
				
					if ( $need_to_update ) {
						$WHERE_SQL = substr( $WHERE_SQL , 0, -4 ); // Strip off the excess AND - the easiest way to code this without extra flags, etc.
						$UPDATE_SQL = $UPDATE_SQL . $WHERE_SQL;
						$result = $wpdb->query( $UPDATE_SQL );
						if ( false === $result ) {
							pb_backupbuddy::status( 'error', 'ERROR: mysql error updating db: ' . mysql_error() . '. SQL Query: ' . htmlentities( $UPDATE_SQL ) );
						} 
					}
					
				}

				unset( $data );

				// See how we are doing on time. Trigger chunking if needed.
				if ( ( ( microtime( true ) - $this->startTime ) + $this->timeWiggleRoom ) >= $this->maxExecutionTime ) {
					return array( $rows_start );
				}
			}
			
			pb_backupbuddy::status( 'message', 'Brute force data migration for table `' . $table . '` complete. Checked ' . $count_items_checked . ' items; ' . $count_items_changed . ' changed.' );
			
			return true;
		} // End bruteforce_table().
		
		
		/**
		 *	recursive_array_replace()
		 *	
		 *	Recursively replace text in an array, stepping through arrays/objects within arrays/objects as needed.
		 *	
		 *	@param		string		$find		Text to find.
		 *	@param		string		$replace	Text to replace found text with.
		 *	@param		reference	&$data		Pass the variable to change the data within.
		 *	@return		boolean					Always true currently.
		 *
		 */
		public function recursive_array_replace( $find, $replace, &$data ) {
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					// ARRAYS
					if ( is_array( $value ) ) {
						$this->recursive_array_replace( $find, $replace, $data[$key] );
						
					// STRINGS
					} elseif ( is_string( $value ) ) {
						$data[$key] = str_replace( $find, $replace, $value );
					
					// OBJECTS
					} elseif ( is_object( $value ) ) {
						//error_log( var_export( $data[$key], true ) );
						$this->recursive_object_replace( $find, $replace, $data[$key] );
						//error_log( var_export( $data[$key], true ) );
					}
				}
			} elseif ( is_string( $data ) ) {
				$data = str_replace( $find, $replace, $data );
			} elseif ( is_object( $data ) ) {
				$this->recursive_object_replace( $find, $replace, $data );
			}
		}
		
		
		/**
		 *	recursive_object_replace()
		 *	
		 *	Recursively replace text in an object, stepping through objects/arrays within objects/arrays as needed.
		 *	
		 *	@param		string		$find		Text to find.
		 *	@param		string		$replace	Text to replace found text with.
		 *	@param		reference	&$data		Pass the variable to change the data within.
		 *	@return		boolean					Always true currently.
		 *
		 */
		public function recursive_object_replace( $find, $replace, &$data ) {
			if ( is_object( $data ) ) {
				$vars = get_object_vars( $data );
				foreach( $vars as $key => $var ) {
					// ARRAYS
					if ( is_array( $data->{$key} ) ) {
						$this->recursive_array_replace( $find, $replace, $data->{$key} );
					// OBJECTS
					} elseif ( is_object( $data->{$key} ) ) {
						$this->recursive_object_replace( $find, $replace, $data->{$key} );
					// STRINGS
					} elseif ( is_string( $data->{$key} ) ) {
						$data->{$key} = str_replace( $find, $replace, $data->{$key} );
					}
				}
			} elseif ( is_string( $data ) ) {
				$data = str_replace( $find, $replace, $data );
			} elseif ( is_array( $data ) ) {
				$this->recursive_array_replace( $find, $replace, $data );
			}
		} // End recursive_object_replace().
		
		
		/**
		 * Check value to find if it was serialized.
		 *
		 * If $data is not an string, then returned value will always be false.
		 * Serialized data is always a string.
		 *
		 * Courtesy WordPress; since WordPress 2.0.5.
		 *
		 * @param mixed $data Value to check to see if was serialized.
		 * @return bool False if not serialized and true if it was.
		 */
		function is_serialized( $data ) {
			// if it isn't a string, it isn't serialized
			if ( ! is_string( $data ) )
				return false;
			$data = trim( $data );
		 	if ( 'N;' == $data )
				return true;
			$length = strlen( $data );
			if ( $length < 4 )
				return false;
			if ( ':' !== $data[1] )
				return false;
			$lastc = $data[$length-1];
			if ( ';' !== $lastc && '}' !== $lastc )
				return false;
			$token = $data[0];
			switch ( $token ) {
				case 's' :
					if ( '"' !== $data[$length-2] )
						return false;
				case 'a' :
				case 'O' :
					return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
				case 'b' :
				case 'i' :
				case 'd' :
					return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
			}
			return false;
		}
		
		
		/**
		 *	remove_matching_array_elements()
		 *	
		 *	Removes identical elements (same index and value) from both arrays where they match.
		 *
		 *	Ex:
		 *		// Before:
		 *		$a = array( 'apple', 'banana', 'carrot' );
		 *		$b = array( 'apple', 'beef', 'cucumber' );
		 *		remove_matching_array_elements( $a, $b );
		 *		// After:
		 *		$a = array( 'banana', 'carrot' );
		 *		$b = array( 'beef', 'cucumber' );
		 *	
		 *	@param		array		&$a		First array to compare with second. (reference)
		 *	@param		array		&$b		Second array to compare with first. (reference)
		 *	@return		null				Arrays passed are updated as they are passed by reference.
		 *
		 */
		function remove_matching_array_elements( &$a, &$b ) {
			$sizeof = sizeof( $a );
			for( $i=0; $i < $sizeof; $i++ ) {
				if ( $a[$i] == $b[$i] ) {
					unset( $a[$i] );
					unset( $b[$i] );
				}
			}
			
			$a = array_merge( $a ); // Reset numbering of keys.
			$b = array_merge( $b ); // Reset numbering of keys.
		}
		
		
	} // end pluginbuddy_dbreplace class.
}
?>