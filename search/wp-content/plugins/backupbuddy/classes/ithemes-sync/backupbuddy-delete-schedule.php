<?php
class Ithemes_Sync_Verb_Backupbuddy_Delete_Schedule extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-delete-schedule';
	public static $description = 'Delete an existing schedule. Also handles unscheduling in WP cron.';
	
	private $default_arguments = array(
		'id' => '', // Numeric schedule ID.
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
		
		$result = backupbuddy_api::deleteSchedule( $arguments['id'], $confirm = true );
		
		
		if ( true !== $result ) {
			
			return array(
				'api' => '0',
				'status' => 'error',
				'message' => 'Error #38332493: Failure deleting schedule.',
			);
			
		} else {
			
			return array(
				'api' => '0',
				'status' => 'ok',
				'message' => 'Schedule deleted.',
			);
			
		}
		
	} // End run().
	
	
} // End class.
