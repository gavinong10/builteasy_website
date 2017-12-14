<?php
class Ithemes_Sync_Verb_Backupbuddy_List_Schedules extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-list-schedules';
	public static $description = 'List backup schedules.';
	
	private $default_arguments = array(
	);
	
	/*
	 * Return:
	 *		array(
	 *			'success'	=>	'0' | '1'
	 *			'status'	=>	'Status message.'
	 *			'schedules'	=>	[array of schedule information]
	 *		)
	 *
	 */
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		$status = 'error';
		$schedules = backupbuddy_api::getSchedules();
		if ( false !== $schedules ) {
			$status = 'ok';
		}
		
		return array(
			'api' => '0',
			'status' => $status,
			'message' => 'Schedules listed successfully.',
			'schedules' => $schedules,
		);
		
	} // End run().
	
	
} // End class.
