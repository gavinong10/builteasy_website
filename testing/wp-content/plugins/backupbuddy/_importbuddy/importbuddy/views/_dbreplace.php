<?php
if ( ! is_admin() ) { // Not in WordPress (or not logged in). Check if in ImportBuddy.
	if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) { // Not in ImportBuddy.
		die( '<html></html>' );
	}
	// In ImportBuddy so check authentication.
	Auth::require_authentication(); // Die if not logged in.
}

pb_backupbuddy::load_style( 'admin.css', true );
global $wpdb;


echo '<a name="database_replace"></a>';
echo 'This tool allows you to automatically replace text contained throughout your WordPress database.<br>';
echo '<br><b>Note:</b> ImportBuddy automatically handles migrating & replacing your site URLs and file paths during restore/migration; this tool is not needed for normal backup / restore operations.';
echo '<p><b>Tip:</b> When replacing a site address there may be more than one URL so multiple passes at replacements may need to be made. Ie. http://site.com, http://<b>www.</b>site.com, http<b>s</b>://site.com, etc.</p>';
echo '<p><img src="' . pb_backupbuddy::plugin_url() . '/images/bullet_error.png" style="vertical-align: -3px;"> Caution: This is an advanced feature. Use with care; improper use may result in data loss.</p>';
echo '<br>';


if ( pb_backupbuddy::_GET( 'database_replace' ) == '1' ) {
	
	global $pb_backupbuddy_js_status;
	$pb_backupbuddy_js_status = true;
	
	
	echo '<div id="pb_importbuddy_working"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading_large.gif" title="Working... Please wait as this may take a moment..."></div>';
	echo '<script>jQuery("#pb_backupbuddy_status_wrap").show();</script>';
	pb_backupbuddy::flush();
	//echo '<div id="pb_backupbuddy_replace_working"><img src="' . pb_backupbuddy::plugin_url() . '/images/loading_large.gif" title="Working... Please wait as this may take a moment..."></div>';
	
	// Instantiate database replacement class.
	require_once( pb_backupbuddy::plugin_path() . '/lib/dbreplace/dbreplace.php' );
	$dbreplace = new pluginbuddy_dbreplace( '', 1, 60*60*24 );
	
	// Set up variables by getting POST data.
	$needle = backupbuddy_core::dbescape( pb_backupbuddy::_POST( 'needle' ) );
	if ( $needle == '' ) {
		echo '<b>Error #4456582. Missing needle. You must enter text to search for.';
		echo '<br><a href="' . pb_backupbuddy::page_url() . '&parent_config=' . htmlentities( pb_backupbuddy::_GET( 'parent_config' ) ) . '" class="button secondary-button">&larr; ' .  __( 'back', 'it-l10n-backupbuddy' ) . '</a>';
		return;
	}
	$replacement = backupbuddy_core::dbescape( pb_backupbuddy::_POST( 'replacement' ) );
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
		
		$results = $wpdb->get_results( "SHOW TABLES", ARRAY_A );
		foreach( $results as $result ) {
			pb_backupbuddy::status( 'message', 'Replacing in table `' . $result[0] . '`.' );
			$dbreplace->bruteforce_table( $result[0], array( $needle ), array( $replacement ) );
		}
		
		pb_backupbuddy::status( 'message', 'Replacement finished.' );
		
	} elseif ( pb_backupbuddy::_POST( 'table_selection' ) == 'single_table' ) {
		
		$table = backupbuddy_core::dbescape( pb_backupbuddy::_POST( 'table' ) ); // Single specified table.
		pb_backupbuddy::status( 'message', 'Replacing in single table `' . $table . '` based on settings.' );
		$dbreplace->bruteforce_table( $table, array( $needle ), array( $replacement ) );
		pb_backupbuddy::status( 'message', 'Replacement finished.' );
		
	} elseif ( pb_backupbuddy::_POST( 'table_selection' ) == 'prefix' ) { // Matching table prefix.
		
		$prefix = backupbuddy_core::dbescape( pb_backupbuddy::_POST( 'table_prefix' ) );
		pb_backupbuddy::status( 'message', 'Replacing in all tables matching prefix `' . $prefix . '`.' );
		
		$escaped_prefix = str_replace( '_', '\_', $prefix );
		$results = $wpdb->get_results( "SHOW TABLES LIKE '{$escaped_prefix}%'", ARRAY_A );
		foreach( $results as $result ) {
			pb_backupbuddy::status( 'message', 'Replacing in table `' . $result[0] . '`.' );
			$dbreplace->bruteforce_table( $result[0], array( $needle ), array( $replacement ) );
		}
		
		pb_backupbuddy::status( 'message', 'Replacement finished.' );
		
	} else {
		echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
		die( 'Error #4456893489349834. Unknown method.' );
	}
	
	echo '<script type="text/javascript">jQuery("#pb_importbuddy_working").hide();</script>';
	echo '<br><a href="' . pb_backupbuddy::page_url() . '&parent_config=' . htmlentities( pb_backupbuddy::_GET( 'parent_config' ) ) . '" class="button secondary-button">&larr; ' .  __( 'back', 'it-l10n-backupbuddy' ) . '</a>';
	
	$pb_backupbuddy_js_status = false;
	return;
}


$tables = array();
$prefixes = array();

// Make sure this WP's prefix is in there for sure (useful if someone uses a prefix that has an underscore in it; they shouldnt but they do).
global $table_prefix;
$prefixes[] = $table_prefix;

// Calculate prefixes found in this database. Does not handle multiple-underscore
$results = $wpdb->get_results( "SHOW TABLES", ARRAY_A );
foreach( $results as $result ) {
	$tables[] = $result[0];
	if ( preg_match( '/[a-zA-Z0-9]*_([0-9]+_)*/i', $result[0], $matches ) ) {
		$prefixes[] = $matches[0];
	}
}

$prefixes = array_unique( $prefixes );
natsort( $prefixes );


?>


<div>
	<form action="<?php echo pb_backupbuddy::page_url();?>&database_replace=1&parent_config=<?php echo htmlentities( pb_backupbuddy::_GET( 'parent_config' ) ); ?>" method="post">
		<input type="hidden" name="action" value="replace">
		
		<h4>Replace <?php pb_backupbuddy::tip( 'Text you want to be searched for and replaced. Everything in the box is considered one match and may span multiple lines.' ); ?></h4>
		<textarea name="needle" style="width: 100%;"></textarea>
		<br>
		
		<h4>With <?php pb_backupbuddy::tip( 'Text you want to replace with. Any text found matching the box above will be replaced with this text. Everything in the box is considered one match and may span multiple lines.' ); ?></h4>
		<textarea name="replacement" style="width: 100%;"></textarea>
		
		<h4>In table(s)</h4>
		<label style="float: none;" for="table_selection_all"><input id="table_selection_all"  checked='checked' type="radio" name="table_selection" value="all"> all tables</label>
		<label style="float: none;" for="table_selection_prefix"><input id="table_selection_prefix" type="radio" name="table_selection" value="prefix"> with prefix:</label>
		<select name="table_prefix" id="table_selection_prefix" onclick="jQuery('#table_selection_prefix').click();">
			<?php
			foreach( $prefixes as $prefix ) {
				echo '<option value="' . $prefix . '">' . $prefix . '</option>';
			}
			?>
		</select>
		<label style="float: none;" for="table_selection_table"><input id="table_selection_table" type="radio" name="table_selection" value="single_table"> single:</label>
		<select name="table" id="table_selection_table" onclick="jQuery('#table_selection_table').click();">
			<?php
			foreach( $tables as $table ) {
				echo '<option value="' . $table . '">' . $table . '</option>';
			}
			?>
		</select>
		<h4>In database</h4>
		"<?php echo $databaseSettings['name']; ?>" on host "<?php echo $databaseSettings['host']; ?>" with username "<?php echo $databaseSettings['username']; ?>".
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