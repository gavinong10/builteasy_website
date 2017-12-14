<?php
backupbuddy_core::verifyAjaxAccess();


// Display backup integrity status.

/* integrity_status()
*
* description
*
*/
$serial = pb_backupbuddy::_GET( 'serial' );
$serial = str_replace( '/\\', '', $serial );
pb_backupbuddy::load();
pb_backupbuddy::$ui->ajax_header();

require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
pb_backupbuddy::status( 'details', 'Fileoptions instance #27.' );
$optionsFile = backupbuddy_core::getLogDirectory() . 'fileoptions/' . $serial . '.txt';
$backup_options = new pb_backupbuddy_fileoptions( $optionsFile, $read_only = true );
if ( true !== ( $result = $backup_options->is_ok() ) ) {
	pb_backupbuddy::alert( __('Unable to access fileoptions data file.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
	die();
}

$integrity = $backup_options->options['integrity'];

//echo '<p><b>' . __( 'Backup File', 'it-l10n-backupbuddy' ) . '</b>: ' . $integrity['file'] . '</p>';

$start_time = 'Unknown';
$finish_time = 'Unknown';
if ( isset( $backup_options->options['start_time'] ) ) {
	$start_time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup_options->options['start_time'] ) ) . ' <span class="description">(' . pb_backupbuddy::$format->time_ago( $backup_options->options['start_time'] ) . ' ago)</span>';
	if ( $backup_options->options['finish_time'] > 0 ) {
		$finish_time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup_options->options['finish_time'] ) ) . ' <span class="description">(' . pb_backupbuddy::$format->time_ago( $backup_options->options['finish_time'] ) . ' ago)</span>';
	} else { // unfinished.
		$finish_time = '<i>Unfinished</i>';
	}
}


//***** BEGIN TESTS AND RESULTS.
if ( isset( $integrity['status_details'] ) ) { // $integrity['status_details'] is NOT array (old, pre-3.1.9).
	echo '<h3>Integrity Technical Details</h3>';
	echo '<textarea style="width: 100%; height: 175px;" wrap="off">';
	foreach( $integrity as $item_name => $item_value ) {
		$item_value = str_replace( '<br />', '<br>', $item_value );
		$item_value = str_replace( '<br><br>', '<br>', $item_value );
		$item_value = str_replace( '<br>', "\n     ", $item_value );
		echo $item_name . ' => ' . $item_value . "\n";
	}
	echo '</textarea><br><br><b>Note:</b> It is normal to see several "file not found" entries as BackupBuddy checks for expected files in multiple locations, expecting to only find each file once in one of those locations.';
} else { // $integrity['status_details'] is array.
	
	echo '<br>';
	
	if ( isset( $integrity['status_details'] ) ) { // PRE-v4.0 Tests.
		function pb_pretty_results( $value ) {
			if ( $value === true ) {
				return '<span class="pb_label pb_label-success">Pass</span>';
			} else {
				return '<span class="pb_label pb_label-important">Fail</span>';
			}
		}
		
		// The tests & their status..
		$tests = array();
		$tests[] = array( 'BackupBackup data file exists', pb_pretty_results( $integrity['status_details']['found_dat'] ) );
		$tests[] = array( 'Database SQL file exists', pb_pretty_results( $integrity['status_details']['found_sql'] ) );
		if ( $integrity['detected_type'] == 'full' ) { // Full backup.
			$tests[] = array( 'WordPress wp-config.php exists (full/files backups only)', pb_pretty_results( $integrity['status_details']['found_wpconfig'] ) );
		} elseif ( $integrity['detected_type'] == 'files' ) { // Files only backup.
			$tests[] = array( 'WordPress wp-config.php exists (full/files backups only)', pb_pretty_results( $integrity['status_details']['found_wpconfig'] ) );
		} else { // DB only.
			$tests[] = array( 'WordPress wp-config.php exists (full/files backups only)', '<span class="pb_label pb_label-success">N/A</span>' );
		}
	} else { // 4.0+ Tests.
		$tests = array();
		if ( isset( $integrity['tests'] ) ) {
			foreach( (array)$integrity['tests'] as $test ) {
				if ( true === $test['pass'] ) {
					$status_text = '<span class="pb_label pb_label-success">Pass</span>';
				} else {
					$status_text = '<span class="pb_label pb_label-important">Fail</span>';
				}
				$tests[] = array( $test['test'], $status_text );
			}
		}
	}
	
	$columns = array(
		__( 'Integrity Test', 'it-l10n-backupbuddy' ),
		__( 'Status', 'it-l10n-backupbuddy' ),
	);
	
	pb_backupbuddy::$ui->list_table(
		$tests,
		array(
			'columns'		=>	$columns,
			'css'			=>	'width: 100%; min-width: 200px;',
		)
	);

} // end $integrity['status_details'] is an array.
echo '<br><br>';
//***** END TESTS AND RESULTS.


// Output meta info table (if any).
$metaInfo = array();
if ( isset( $integrity['file'] ) && ( false === ( $metaInfo = backupbuddy_core::getZipMeta( backupbuddy_core::getBackupDirectory() . $integrity['file'] ) ) ) ) { // $backup_options->options['archive_file']
	echo '<i>No meta data found in zip comment. Skipping meta information display.</i>';
} else {
	pb_backupbuddy::$ui->list_table(
		$metaInfo,
		array(
			'columns'		=>	array( 'Backup Details', 'Value' ),
			'css'			=>	'width: 100%; min-width: 200px;',
		)
	);
}
echo '<br><br>';


//***** BEGIN STEPS.
$steps = array();
$steps[] = array( 'Start Time', $start_time, '' );
if ( isset( $backup_options->options['steps'] ) ) {
	foreach( $backup_options->options['steps'] as $step ) {
		if ( isset( $step['finish_time'] ) && ( $step['finish_time'] != 0 ) ) {
			
			// Step name.
			if ( $step['function'] == 'backup_create_database_dump' ) {
				if ( count( $step['args'][0] ) == 1 ) {
					$step_name = 'Database dump (breakout: ' . $step['args'][0][0] . ')';
				} else {
					$step_name = 'Database dump';
				}
			} elseif ( $step['function'] == 'backup_zip_files' ) {
				if ( isset( $backup_options->options['steps']['backup_zip_files'] ) ) {
					$zip_time = $backup_options->options['steps']['backup_zip_files'];
				} else {
					$zip_time = 0;
				}
				
				// Calculate write speed in MB/sec for this backup.
				if ( $zip_time == '0' ) { // Took approx 0 seconds to backup so report this speed.
					$write_speed = '> ' . pb_backupbuddy::$format->file_size( $backup_options->options['integrity']['size'] );
				} else {
					if ( $zip_time == 0 ) {
						$write_speed = '';
					} else {
						$write_speed = pb_backupbuddy::$format->file_size( $backup_options->options['integrity']['size'] / $zip_time ) . '/sec';
					}
				}
				$step_name = 'Zip archive creation (Write speed: ' . $write_speed . ')';
			} elseif ( $step['function'] == 'post_backup' ) {
				$step_name = 'Post-backup cleanup';
			} elseif( $step['function'] == 'integrity_check' ) {
				$step_name = 'Integrity Check';
			} else {
				$step_name = $step['function'];
			}
			
			// Step time taken.
			$seconds = (int)( $step['finish_time'] - $step['start_time'] );
			if ( $seconds < 1 ) {
				$step_time = '< 1 second';
			} else {
				$step_time = $seconds . ' seconds';
			}
			
			
			// Compile details for this step into array.
			$steps[] = array(
				$step_name,
				$step_time,
				$step['attempts'],
			);
			
		}
	} // End foreach.
} else { // End if serial in array is set.
	$step_times[] = 'unknown';
} // End if serial in array is NOT set.


// Total overall time from initiation to end.
if ( isset( $backup_options->options['finish_time'] ) && isset( $backup_options->options['start_time'] ) && ( $backup_options->options['finish_time'] != 0 ) && ( $backup_options->options['start_time'] != 0 ) ) {
	$seconds = ( $backup_options->options['finish_time'] - $backup_options->options['start_time'] );
	if ( $seconds < 1 ) {
		$total_time = '< 1 second';
	} else {
		$total_time = $seconds . ' seconds';
	}
} else {
	$total_time = '<i>Unknown</i>';
}
$steps[] = array( 'Finish Time', $finish_time, '' );
$steps[] = array(
	'<b>Total Overall Time</b>',
	$total_time,
	'',
);

$columns = array(
	__( 'Backup Steps', 'it-l10n-backupbuddy' ),
	__( 'Time', 'it-l10n-backupbuddy' ),
	__( 'Attempts', 'it-l10n-backupbuddy' ),
);

if ( count( $steps ) == 0 ) {
	_e( 'No step statistics were found for this backup.', 'it-l10n-backupbuddy' );
} else {
	pb_backupbuddy::$ui->list_table(
		$steps,
		array(
			'columns'		=>	$columns,
			'css'			=>	'width: 100%; min-width: 200px;',
		)
	);
}
echo '<br><br>';
//***** END STEPS.



if ( isset( $backup_options->options['trigger'] ) ) {
	$trigger = $backup_options->options['trigger'];
} else {
	$trigger = 'Unknown trigger';
}
if ( isset( $integrity['scan_time'] ) ) {
	$scanned = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $integrity['scan_time'] ) );
	echo ucfirst( $trigger ) . " backup {$integrity['file']} last scanned {$scanned}.";
}
echo '<br><br><br>';

echo '<a class="button secondary-button" onclick="jQuery(\'#pb_backupbuddy_advanced_debug\').slideToggle();">Display Advanced Debugging</a>';
echo '<div id="pb_backupbuddy_advanced_debug" style="display: none;">From options file: `' . $optionsFile . '`.<br>';
echo '<textarea style="width: 100%; height: 400px;" wrap="on">';
echo print_r( $backup_options->options, true );
echo '</textarea><br><br>';
echo '</div><br><br>';


pb_backupbuddy::$ui->ajax_footer();
die();
