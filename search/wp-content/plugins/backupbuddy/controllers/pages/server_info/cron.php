All scheduled WordPress tasks (CRON jobs) are listed below. Use caution when manually running or deleting scheduled CRON
jobs as plugins, themes, or WordPress itself may expect these to remain in place. WordPress will recreate any mandatory
internal CRON jobs automatically if they are removed.<br><br>
<?php
$cron = get_option('cron');


// Handle CRON deletions.
if ( pb_backupbuddy::_POST( 'bulk_action' ) == 'delete_cron' ) {
	if ( defined( 'PB_DEMO_MODE' ) ) {
		pb_backupbuddy::alert( 'Access denied in demo mode.', true );
	} else {
		$delete_items = pb_backupbuddy::_POST( 'items' );
		
		$deleted_crons = array(); // For listing in alert.
		foreach( $delete_items as $delete_item ) {
			$cron_parts = explode( '|', $delete_item );
			$timestamp = $cron_parts[0];
			$cron_hook = $cron_parts[1];
			$cron_key = $cron_parts[2];
			
			if ( isset( $cron[ $timestamp ][ $cron_hook ][ $cron_key ] ) ) { // Run cron.
				
				$cron_array = $cron[ $timestamp ][ $cron_hook ][ $cron_key ]; // Get cron array based on passed values.
				$result = backupbuddy_core::unschedule_event( $timestamp, $cron_hook, $cron_array['args'] ); // Delete the scheduled cron.
				if ( $result === FALSE ) {
					pb_backupbuddy::alert( 'Error #5657667675. Unable to delete CRON job. Please see your BackupBuddy error log for details.' );
				}
				$deleted_crons[] = $cron_hook . ' / ' . $cron_key; // Add deleted cron to list of deletions for display.
				
			} else { // Cron not found, error.
				pb_backupbuddy::alert( 'Invalid CRON job. Not found.', true );
			}
			
		}
		
		pb_backupbuddy::alert( __('Deleted scheduled CRON event(s):', 'it-l10n-backupbuddy' ) . '<br>' . implode( '<br>', $deleted_crons ) );
		$cron = get_option('cron'); // Reset to most up to date status for cron listing below. Takes into account deletions.
	}
}



// Handle RUNNING cron jobs manually.
if ( !empty( $_GET['run_cron'] ) ) {
	if ( defined( 'PB_DEMO_MODE' ) ) {
		pb_backupbuddy::alert( 'Access denied in demo mode.', true );
	} else {
		$cron_parts = explode( '|', pb_backupbuddy::_GET( 'run_cron' ) );
		$timestamp = $cron_parts[0];
		$cron_hook = $cron_parts[1];
		$cron_key = $cron_parts[2];
		
		if ( isset( $cron[ $timestamp ][ $cron_hook ][ $cron_key ] ) ) { // Run cron.
			$cron_array = $cron[ $timestamp ][ $cron_hook ][ $cron_key ]; // Get cron array based on passed values.
			
			/*
			if ( count( $cron_array['args'] ) == 1 ) {
				$args = $cron_array['args'][0];
			} else {
				$args = $cron_array['args'];
			}
			*/
			
			do_action_ref_array( $cron_hook, $cron_array['args'] ); // Run the cron job!
			
			pb_backupbuddy::alert( 'Ran CRON event `' . $cron_hook . ' / ' . $cron_key . '`. Its schedule was not modified.' );
		} else { // Cron not found, error.
			pb_backupbuddy::alert( 'Invalid CRON job. Not found.', true );
		}
	}
}





// Loop through each cron time to create $crons array for displaying later.
$crons = array();
foreach ( (array) $cron as $time => $cron_item ) {
	if ( is_numeric( $time ) ) {
		// Loop through each schedule for this time
		foreach ( (array) $cron_item as $hook_name => $event ) {
			foreach ( (array) $event as $item_name => $item ) {
				
				// Determine period.
				if ( !empty( $item['schedule'] ) ) { // Recurring schedule.
					$period = '';
					if ( false !== ( $prettyInterval = backupbuddy_core::prettyCronInterval( $item['interval'] ) ) ) {
						$period .= '<span title="Interval tag: `' . $prettyInterval[0] . '`.">' . $prettyInterval[1] . '</span>';
					} else {
						$period .= '<span title="Interval tag: `' . $item['schedule'] . '`.">' . $item['schedule'] . '</span>';
					}
				} else { // One-time only cron.
					$period = __('one time only', 'it-l10n-backupbuddy' );
				}
				
				// Determine interval.
				if ( ! empty( $item['interval'] ) ) {
					$interval = $item['interval'] . ' seconds';
				} else {
					$interval = __('one time only', 'it-l10n-backupbuddy' );
				}
				
				// Determine arguments.
				if ( !empty( $item['args'] ) ) {
					//$arguments = implode( ',', $item['args'] );
					$arguments = '';
					foreach( $item['args'] as $args ) {
						$arguments_inner = array();
						$is_array = false;
						if ( ! is_array( $args ) ) {
							$arguments_inner[] = $args;
						} else {
							$is_array = true;
							foreach( $args as $arg ) {
								if ( is_array( $arg ) ) {
									$arguments_inner[] = print_r( $arg, true );
								} else {
									$arguments_inner[] = $arg;
								}
							}
						}
						if ( true === $is_array ) {
							$arguments_inner = 'Array( ' . implode( ', ', $arguments_inner ) . ' )';
						} else {
							$arguments_inner = implode( ', ', $arguments_inner );
						}
						$arguments .= '<textarea wrap="off">' . $arguments_inner . '</textarea>';
						/*
						if ( is_array( $arg ) ) {
							$arguments .=  '[' . print_r( $arg, true ) . ']';//pb_backupbuddy::$format->multi_implode( $arg , '; ' )
						} else {
							$arguments .= $arg;
						}
						*/
					}
				} else {
					$arguments = __('none', 'it-l10n-backupbuddy' );
				}
				
				// Populate crons array for displaying later.
				$crons[ $time . '|' . $hook_name . '|' . $item_name] = array(
					'<span title=\'Key: ' . $item_name . '\'>' . $hook_name . '</span>',
					pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $time ) ) . '<br><span class="description">Timestamp: ' . $time . '</span>',
					$period,
					$interval,
					$arguments,
				);
				
			} // End foreach.
			unset( $item );
			unset( $item_name );
		} // End foreach.
		unset( $event );
		unset( $hook_name );
	} // End if is_numeric.
} // End foreach.
unset( $cron_item );
unset( $time );



// Display CRON table.
pb_backupbuddy::$ui->list_table(
	$crons, // Array of cron items set in code section above.
	array(
		'action'					=>	pb_backupbuddy::page_url() . '#pb_backupbuddy_getting_started_tab_tools',
		'columns'					=>	array(
											__( 'Scheduled Events', 'it-l10n-backupbuddy' ),
											__( 'Next Run', 'it-l10n-backupbuddy' ),
											__( 'Period', 'it-l10n-backupbuddy' ),
											__( 'Interval', 'it-l10n-backupbuddy' ),
											__( 'Arguments', 'it-l10n-backupbuddy' ),
										),
		'css'						=>		'width: 100%;',
		'hover_actions'				=>	array(
											'run_cron'	=>	'Run cron job now',
										),
		'bulk_actions'	=>	array( 'delete_cron' => 'Delete' ),
		'hover_action_column_key'	=>	'0',
	)
);
echo '<br><br>';


// Display time intervals table.
$pretty_intervals = array();
$schedule_intervals = wp_get_schedules();
foreach( $schedule_intervals as $interval_tag => $schedule_interval ) {
	$pretty_intervals[ $schedule_interval['interval'] ] = array(
		$schedule_interval['display'],
		$interval_tag,
		$schedule_interval['interval'],
	);
}
ksort( $pretty_intervals );
pb_backupbuddy::$ui->list_table(
	$pretty_intervals, // Array of cron items set in code section above.
	array(
		'columns'					=>	array(
											__( 'Schedule Periods', 'it-l10n-backupbuddy' ),
											__( 'Tag', 'it-l10n-backupbuddy' ),
											__( 'Interval', 'it-l10n-backupbuddy' ),
										),
		'css'						=>		'width: 100%;',
	)
);
echo '<br><br>';


echo '<center>' . __('Current Time', 'it-l10n-backupbuddy' ) . ': ' . pb_backupbuddy::$format->date( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) . ' (' . time() . ')</center>';



if ( empty( $_GET['show_cron_array'] ) ) {
	?>
	<p>
	<center>
		<a href="<?php echo pb_backupbuddy::page_url(); ?>&tab=3&show_cron_array=true#pb_backupbuddy_getting_started_tab_tools" style="text-decoration: none;">
			<?php _e('Display CRON Debugging Array', 'it-l10n-backupbuddy' ); ?>
		</a>
	</center>
	</p>
	<?php
} else {
	
	echo '<br><textarea readonly="readonly" style="width: 793px;" rows="13" cols="75" wrap="off">';
	print_r( $cron );
	echo '</textarea><br><br>';
}
unset( $cron );
?>

<br>
<div class="description">
	<b>Note</b>: Due to the way schedules are triggered in WordPress your site must be accessed (frontend or admin area) for scheduled backups to occur.
	WordPress scheduled events ("crons") may be viewed or run manually in the table above</a>. A <a href="https://www.google.com/search?q=free+website+uptime&oq=free+website+uptime" target="_blank">free website uptime</a> service or <a href="https://ithemes.com/sync-pro/uptime-monitoring/" target="_blank">iThemes Sync Pro's Uptime Monitoring</a> can be used to automatically access your site regularly to help trigger scheduled actions ("crons") in cases of low site activity, with the added perk of keeping track of your site uptime.
</div>

