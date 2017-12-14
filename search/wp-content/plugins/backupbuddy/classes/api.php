<?php
// TIP: Check if a function is callable before running if using these methods from a 3rd party software. Eg. if ( is_callable( array( 'backupbuddy_api', 'runBackup' ) ) ) { ...
class backupbuddy_api {
	
	public static $apiVersion = 2;
	public static $lastError = '';
	
	
	/* getLastError()
	 *
	 * Retrieve the last error the API encountered. Use if a method returned bool FALSE to get message.
	 *
	 */
	public static function getLastError() {
		return $lastError;
	}
	
	
	/* runBackup()
	 *
	 * @param	string|int	$generic_type_or_profile_id		Valid options are: full, db, OR the numeric profile ID number of the profile to run.
	 * @param	int			$backupMode						1 = classic (single PHP page load), 2 = modern (uses cron). Default: BLANK which uses mode based on settings.
	 * @param	string		$backupSerial					If set then forces backup to use the specificed serial.
	 * @return	true|string									Returns true on success running the backup, else a string error message.
	 *
	 */
	public static function runBackup( $generic_type_or_profile_id_or_array = '', $triggerTitle = 'BB API', $backupMode = '', $backupSerial = '' ) {
		self::_before();
		return require( dirname(__FILE__) . '/api/_runBackup.php' );
	}
	
	
	/* getLatestBackupStats()
	 *
	 * Get an array of useful information about the latest backup that has started, including its progress. Returns false if no backup has run or unable to retrieve the information.
	 * @return			Returns an array of various data.
	 */
	public static function getLatestBackupStats() {
		self::_before();
		return require( dirname(__FILE__) . '/api/_getLatestBackupStats.php' );
	}
	
	
	
	// backupbuddy_api::getOverview()
	public static function getOverview() {
		self::_before();
		return require( dirname(__FILE__) . '/api/_getOverview.php' );
	}
	
	
	// backupbuddy_api::getSchedules()
	public static function getSchedules() {
		self::_before();
		return require( dirname(__FILE__) . '/api/_getSchedules.php' );
	}
	
	// NOTE: Currently only support $echo === true.
	public static function getBackupStatus( $serial, $specialAction = '', $initRetryCount = 0, $sqlFile = '', $echo = true ) {
		self::_before();
		return require( dirname(__FILE__) . '/api/_getBackupStatus.php' );
	}
	
	
	
	/* backupbuddy_api::addSchedule()
	 *
	 * Adds a new schedule for backing up.
	 *
	 * @param	string			$title					Schedule title (user-friendly name).
	 * @param	int				$profile				Profile ID.
	 * @param	string			$interval				WordPress schedule interval for cron (ie weekly, daily, hourly, etc).
	 * @param	int				$first_run				Timestamp of when to run the first in this scheduled cron series.
	 * @param	array 			$remote_destinations	Array of remote destination IDs to send to.
	 * @param	bool			$delete_after			Whether or not to delete local backup file after success sending to all remote destinations (if any). Does not delete if no destinations defined.
	 * @param	bool			$enabled				true if enabled, else false.
	 * @return	true|string								true on success, else error message string.
	 *
	 */
	public static function addSchedule( $title, $profile, $interval, $first_run, $remote_destinations = array(), $delete_after = false, $enabled = true ) {
		self::_before();
		return require( dirname(__FILE__) . '/api/_addSchedule.php' );
	}
	
	
	public static function deleteSchedule( $scheduleID, $confirm = false ) {
		self::_before();
		return require( dirname(__FILE__) . '/api/_deleteSchedule.php' );
	}
	
	
	public static function getPreDeployInfo() {
		self::_before();
		return require( dirname(__FILE__) . '/api/_getPreDeployInfo.php' );
	}
	
	
	public static function getActivePlugins() {
		self::_before();
		return require( dirname(__FILE__) . '/api/_getActivePlugins.php' );
	}
	
	
	private static function _before() {
		// Load backupbuddy class with helper functions.
		if ( ! class_exists( 'backupbuddy_core' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );
		}
	}
	
} // end class.