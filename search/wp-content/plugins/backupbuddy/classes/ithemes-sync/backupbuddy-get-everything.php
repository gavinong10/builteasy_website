<?php
class Ithemes_Sync_Verb_Backupbuddy_Get_Everything extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-get-everything';
	public static $description = 'Get everything including overview, list destinations, profiles, schedules, etc.';
	
	private $default_arguments = array(
	);
	
	/*
	 * Return:
	 *		array(
	 *			'success'	=>	'0' | '1'
	 *			'status'	=>	'Status message.'
	 *			'overview'	=>	[array of overview information]
	 *		)
	 *
	 */
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		$fail = false;
		
		include( 'backupbuddy-get-overview.php' );
		$overviewClass = new Ithemes_Sync_Verb_Backupbuddy_Get_Overview();
		$overview = $overviewClass->run( array() );
		if ( 'error' == $overview['status'] ) {
			$fail = true;
		}
		
		include( 'backupbuddy-get-latestBackupProcess.php' );
		$latestBackupProcessClass = new Ithemes_Sync_Verb_Backupbuddy_Get_LatestBackupProcess();
		$latestBackupProcess = $latestBackupProcessClass->run( array() );
		if ( 'error' == $overview['status'] ) {
			$fail = true;
		}
		
		include( 'backupbuddy-list-destinations.php' );
		$destinationsClass = new Ithemes_Sync_Verb_Backupbuddy_List_Destinations();
		$destinations = $destinationsClass->run( array() );
		if ( 'error' == $destinations['status'] ) {
			$fail = true;
		}
		
		include( 'backupbuddy-list-profiles.php' );
		$profilesClass = new Ithemes_Sync_Verb_Backupbuddy_List_Profiles();
		$profiles = $profilesClass->run( array() );
		if ( 'error' == $profiles['status'] ) {
			$fail = true;
		}
		
		include( 'backupbuddy-list-schedules.php' );
		$schedulesClass = new Ithemes_Sync_Verb_Backupbuddy_List_Schedules();
		$schedules = $schedulesClass->run( array() );
		if ( 'error' == $schedules['status'] ) {
			$fail = true;
		}
		
		if ( true === $fail ) {
			$status = 'error';
			$message = 'One or more errors while retrieving everything.';
		} else {
			$status = 'ok';
			$message = 'Everything retrieved successfully.';
		}
		
		return array(
			'version' => '1',
			'status' => $status,
			'message' => $message,
			'overview' => $overview,
			'latestBackupProcess' => $latestBackupProcess,
			'destinations' => $destinations,
			'profiles' => $profiles,
			'schedules' => $schedules,
		);
		
	} // End run().
	
	
} // End class.
