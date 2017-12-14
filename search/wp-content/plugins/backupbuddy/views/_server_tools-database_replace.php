<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }

global $wpdb;

echo '<a name="database_replace"></a>';

echo '<p><b>Warning: This is an advanced feature. Use with caution; improper use may result in data loss.</b></p>';
if ( pb_backupbuddy::_GET( 'database_replace' ) == '1' ) {
	
	global $pb_backupbuddy_js_status;
	$pb_backupbuddy_js_status = true;
	
	?>
	<script type="text/javascript">
			function pb_status_append( json ) {
				if( 'undefined' === typeof statusBox ) { // No status box yet so may need to create it.
					statusBox = jQuery( '#pb_backupbuddy_status' );
					if( statusBox.length == 0 ) { // No status box yet so suppress.
						return;
					}
				}
				
				if ( 'string' == ( typeof json ) ) {
					backupbuddy_log( json );
					console.log( 'Status log received string: ' + json );
					return;
				}
				
				// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php
				json.date = new Date();
				json.date = new Date(  ( json.time * 1000 ) + json.date.getTimezoneOffset() * 60000 );
				var seconds = json.date.getSeconds();
				if ( seconds < 10 ) {
					seconds = '0' + seconds;
				}
				json.date = backupbuddy_hourpad( json.date.getHours() ) + ':' + json.date.getMinutes() + ':' + seconds;
				
				triggerEvent = 'backupbuddy_' + json.event;
				
				
				// Log non-text events.
				if ( ( 'details' !== json.event ) && ( 'message' !== json.event ) && ( 'error' !== json.event ) ) {
					//console.log( 'Non-text event `' + triggerEvent + '`.' );
				} else {
					//console.log( json.data );
				}
				//console.log( 'trigger: ' + triggerEvent );
				
				backupbuddy_log( json );
				
				
			} // End function pb_status_append().
			
			// left hour pad with zeros
			function backupbuddy_hourpad(n) { return ("0" + n).slice(-2); }
			
			// Used in BackupBuddy _backup-perform.php and ImportBuddy _header.php and _rollback.php
			function backupbuddy_log( json ) {
				
				message = '';
				
				if ( 'string' == ( typeof json ) ) {
					message = "-----------\t\t-------\t-------\t" + json;
				} else {
					message = json.date + '.' + json.u + " \t" + json.run + "sec \t" + json.mem + "MB\t" + json.data;
				}
				
				statusBox.append( "\r\n" + message );
				statusBox.scrollTop( statusBox[0].scrollHeight - statusBox.height() );
				
			}
		</script>
	<?php
	
	echo pb_backupbuddy::status_box( 'Mass replacing in database with Server Tools from BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . '...' );
	//echo '<div id="pb_backupbuddy_replace_working"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading_large.gif" title="Working... Please wait as this may take a moment..."></div>';
	
	// Instantiate database replacement class.
	require_once( pb_backupbuddy::plugin_path() . '/lib/dbreplace/dbreplace.php' );
	$dbreplace = new pluginbuddy_dbreplace( '', 1, 60*60*24 );
	
	// Set up variables by getting POST data.
	$needle = backupbuddy_core::dbEscape( pb_backupbuddy::_POST( 'needle' ) );
	if ( $needle == '' ) {
		echo '<b>Error #4456582. Missing needle. You must enter text to search for.';
		echo '<br><a href="' . pb_backupbuddy::page_url() . '&tab=1#database_replace" class="button secondary-button">&larr; ' .  __( 'back', 'it-l10n-backupbuddy' ) . '</a>';
		return;
	}
	$replacement = backupbuddy_core::dbEscape( pb_backupbuddy::_POST( 'replacement' ) );
	pb_backupbuddy::status( 'message', 'Replacing `' . $needle . '` with `' . $replacement . '`.' );
	/*
	if ( pb_backupbuddy::_POST( 'maybe_serialized' ) == 'true' ) {
		pb_backupbuddy::status( 'message', 'Accounting for serialized data based on settings.' );
		$maybe_serialized = true;
	} else {
		pb_backupbuddy::status( 'warning', 'NOT accounting for serialized data based on settings. Use with caution.' );
		$maybe_serialized = false;
	}
	*/
	
	
	// Replace based on the type of table replacement selected.
	if ( pb_backupbuddy::_POST( 'table_selection' ) == 'all' ) { // All tables.
		pb_backupbuddy::status( 'message', 'Replacing in all tables based on settings.' );
		
		$tables = array();
		$rows = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()", ARRAY_A );
		foreach( $rows as $row ) {
			$tables[] = $row['table_name'];
		}
		unset( $rows );
		$rows_changed = 0;
		foreach( $tables as $table ) {
			pb_backupbuddy::status( 'message', 'Replacing in table `' . $table . '`.' );
			$rows_changed += $dbreplace->bruteforce_table( $table, array( $needle ), array( $replacement ) );
		}
		pb_backupbuddy::status( 'message', 'Total rows updated across all tables: ' . $rows_changed . '.' );
		
		pb_backupbuddy::status( 'message', 'Replacement finished.' );
	} elseif ( pb_backupbuddy::_POST( 'table_selection' ) == 'single_table' ) {
		$table = backupbuddy_core::dbEscape( pb_backupbuddy::_POST( 'table' ) ); // Single specified table.
		pb_backupbuddy::status( 'message', 'Replacing in single table `' . $table . '` based on settings.' );
		$dbreplace->bruteforce_table( $table, array( $needle ), array( $replacement ) );
		pb_backupbuddy::status( 'message', 'Replacement finished.' );
	} elseif ( pb_backupbuddy::_POST( 'table_selection' ) == 'prefix' ) { // Matching table prefix.
		$prefix = backupbuddy_core::dbEscape( pb_backupbuddy::_POST( 'table_prefix' ) );
		pb_backupbuddy::status( 'message', 'Replacing in all tables matching prefix `' . $prefix . '`.' );
		
		$tables = array();
		$escaped_prefix = str_replace( '_', '\_', $prefix );
		$rows = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_name LIKE '{$escaped_prefix}%' AND table_schema = DATABASE()", ARRAY_A );
		foreach( $rows as $row ) {
			$tables[] = $row['table_name'];
		}
		unset( $rows );
		$rows_changed = 0;
		foreach( $tables as $table ) {
			pb_backupbuddy::status( 'message', 'Replacing in table `' . $table . '`.' );
			$rows_changed += $dbreplace->bruteforce_table( $table, array( $needle ), array( $replacement ) );
		}
		pb_backupbuddy::status( 'message', 'Total rows updated across all tables: ' . $rows_changed . '.' );
		
		pb_backupbuddy::status( 'message', 'Replacement finished.' );
	} else {
		die( 'Error #4456893489349834. Unknown method.' );
	}
	
	echo '<br><a href="' . pb_backupbuddy::page_url() . '&tab=1#database_replace" class="button secondary-button">&larr; ' .  __( 'back', 'it-l10n-backupbuddy' ) . '</a>';
	
	$pb_backupbuddy_js_status = false;
	return;
}










echo '<p><b>Tip:</b> When replacing a site address there may be more than one URL. Ie. http://site.com, http://www.site.com, https://site.com, etc.</p>';


$tables = array();
$prefixes = array();

// Make sure this WP's prefix is in there for sure (useful if someone uses a prefix that has an underscore in it; they shouldnt but they do).
global $table_prefix;
$prefixes[] = $table_prefix;

// Calculate prefixes foudn in this database. Does not handle multiple-underscore
$rows = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()", ARRAY_A );
foreach( $rows as $row ) {
	$tables[] = $row['table_name'];
	
	if ( preg_match( '/[a-zA-Z0-9]*_([0-9]+_)*/i', $row['table_name'], $matches ) ) {
		$prefixes[] = $matches[0];
	}
}
unset( $rows );

$prefixes = array_unique( $prefixes );
natsort( $prefixes );
?>
<div>
	<form action="<?php echo pb_backupbuddy::page_url();?>&database_replace=1&tab=1#database_replace" method="post">
		<input type="hidden" name="action" value="replace">
		
		<h4>Replace <?php pb_backupbuddy::tip( 'Text you want to be searched for and replaced. Everything in the box is considered one match and may span multiple lines.' ); ?></h4>
		<textarea name="needle" style="width: 100%;"></textarea>
		<br>
		
		<h4>With <?php pb_backupbuddy::tip( 'Text you want to replace with. Any text found matching the box above will be replaced with this text. Everything in the box is considered one match and may span multiple lines.' ); ?></h4>
		<textarea name="replacement" style="width: 100%;"></textarea>
		
		<h4>In table(s)</h4>
		<label for="table_selection_all"><input id="table_selection_all"  checked='checked' type="radio" name="table_selection" value="all"> all tables</label>
		<label for="table_selection_prefix"><input id="table_selection_prefix" type="radio" name="table_selection" value="prefix"> with prefix:</label>
		<select name="table_prefix" id="table_selection_prefix" onclick="jQuery('#table_selection_prefix').click();">
			<?php
			foreach( $prefixes as $prefix ) {
				echo '<option value="' . $prefix . '">' . $prefix . '</option>';
			}
			?>
		</select>
		<label for="table_selection_table"><input id="table_selection_table" type="radio" name="table_selection" value="single_table"> single:</label>
		<select name="table" id="table_selection_table" onclick="jQuery('#table_selection_table').click();">
			<?php
			foreach( $tables as $table ) {
				echo '<option value="' . $table . '">' . $table . '</option>';
			}
			?>
		</select>
		<?php
		if ( substr_count( $table_prefix, '_' ) > 1 ) {
			echo '<span class="pb_label pb_label-warning">Warning</span> ';
			_e( "Site table prefix contains multiple underscores. Prefix list may be inaccurate if these are not Multisite subsites.", 'it-l10n-backupbuddy' );
		}
		/*
		<h4>With advanced options</h4>
		<label for="maybe_serialized"><input id="maybe_serialized" type="checkbox" name="maybe_serialized" value="true" checked="checked"> Treat fields as possibly containing serialized data (uncheck with caution; slower).</label>
		*/
		?><br><br>
	
		<p>
			<input type="submit" name="submit" value="Begin Replacement" class="button button-primary" /> <span class="description">Caution; this cannot be undone. Serialized data is handled by this replacement.</span>
		</p>
	</form>
</div>
<br>