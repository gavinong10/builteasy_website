<?php

class pb_backupbuddy_filters extends pb_backupbuddy_filterscore {
	
	
	
	/* cron_scheduled()
	 *
	 * Adds in additional scheduling intervals into WordPress such as weekly, twice monthly, monthly, etc.
	 *
	 * @param	$schedules	array	Array of existing schedule intervals already registered with WordPress. Handles missing param or not being an array.
	 * @return				array	Array containing old and new schedule intervals.
	 */
	public function cron_schedules( $schedules = array() ) {
		if ( ! is_array( $schedules ) ) {
			$schedules = array();
		}
		
		//$schedules['five_minutes_interval'] = array( 'interval' => 300, 'display' => __( 'Once every five minutes', 'it-l10n-backupbuddy' ) ); // Used for BB Live.
		$schedules['twicedaily'] = array( 'interval' => 21600, 'display' => __( 'Every Six Hours', 'it-l10n-backupbuddy' ) );
		$schedules['twicedaily'] = array( 'interval' => 43200, 'display' => __( 'Twice Daily', 'it-l10n-backupbuddy' ) );
		$schedules['everyotherday'] = array( 'interval' => 172800, 'display' => __( 'Every Other Day', 'it-l10n-backupbuddy' ) );
		$schedules['twiceweekly'] = array( 'interval' => 302400, 'display' => __( 'Twice Weekly', 'it-l10n-backupbuddy' ) );
		$schedules['weekly'] = array( 'interval' => 604800, 'display' => __( 'Once Weekly', 'it-l10n-backupbuddy' ) );
		$schedules['twicemonthly'] = array( 'interval' => 1296000, 'display' => __( 'Twice Monthly', 'it-l10n-backupbuddy' ) );
		$schedules['monthly'] = array( 'interval' => 2592000, 'display' => __( 'Once Monthly', 'it-l10n-backupbuddy' ) );
		$schedules['quarterly'] = array( 'interval' => 7889225, 'display' => __( 'Every Three Months', 'it-l10n-backupbuddy' ) );
		$schedules['twiceyearly'] = array( 'interval' => 15778450, 'display' => __( 'Twice Yearly', 'it-l10n-backupbuddy' ) );
		$schedules['yearly'] = array( 'interval' => 31556900, 'display' => __( 'Once Yearly', 'it-l10n-backupbuddy' ) );
		return $schedules;
	} // End cron_schedules().
	
	
	
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( isset( $plugin_meta[2] ) && strstr( $plugin_meta[2], 'backupbuddy' ) ) {
			$plugin_meta[] = '<a href="http://ithemes.com/codex/page/BackupBuddy" target="_blank">' . __( 'Documentation', 'it-l10n-backupbuddy' ) . '</a>';
			$plugin_meta[] = '<a href="http://ithemes.com/support/" target="_blank">' . __( 'Support', 'it-l10n-backupbuddy' ) . '</a>';
			
			return $plugin_meta;
		} else {
			return $plugin_meta;
		}
	} // End plugin_row_meta().
	
	
	
} // End class pb_backupbuddy_filters.
?>