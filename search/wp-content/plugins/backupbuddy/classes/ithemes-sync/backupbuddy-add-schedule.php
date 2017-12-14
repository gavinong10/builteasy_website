<?php
class Ithemes_Sync_Verb_Backupbuddy_Add_Schedule extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-add-schedule';
	public static $description = 'Add a new schedule.';
	
	private $default_arguments = array(
		'title'			=> '', // User-friendly string title for convenience.
		'profile'		=> '', // Profile ID number (numeric).
		'interval'		=> '', // Tag interval for schedule for WP cron. ie. hourly, daily, twicedaily, weekly, twiceweekly, monthly, twicemonthly
		'firstRun'		=> 0, // Timestamp of first runtime.
		'destinations'	=> array(), // Array of destination IDs to send to after the schedule completes running.
		'deleteAfter'	=> false, // Whether or not to delete the local copy of the backup after sending to a destination (if applicable).
		'enabled'		=> true, // Whether or not this schedule is currently enabled (active) to be able to run.
	);
	
	/*
	 * Return:
	 *		array(
	 *			'success'	=>	'0' | '1'
	 *			'status'	=>	'Status message.'
	 *		)
	 *
	 */
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		if ( true !== backupbuddy_api::addSchedule( $arguments['title'], $arguments['profile'], $arguments['interval'], $arguments['firstRun'], $arguments['destinations'], $arguments['deleteAfter'], $arguments['enabled'] ) ) {
			
			return array(
				'api' => '0',
				'status' => 'error',
				'message' => 'Error #378235: Creating schedule failed. A plugin may have blocked scheduling with WordPress. Details: ' . backupbuddy_api::$lastError,
			);
			
		} else {
			
			return array(
				'api' => '0',
				'status' => 'ok',
				'message' => 'Schedule added successfully.',
				'scheduleID' => (int) ( pb_backupbuddy::$options['next_schedule_index'] - 1 ),
			);
			
		}
		
	} // End run().
	
	
} // End class.
