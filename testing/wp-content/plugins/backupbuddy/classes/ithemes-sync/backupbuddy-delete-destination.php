<?php
class Ithemes_Sync_Verb_Backupbuddy_Delete_Destination extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-delete-destination';
	public static $description = 'Delete an existing destination. Handles stripping from existing scheduled destinations.';
	
	private $default_arguments = array(
		'id'	=> '', // Destination ID to delete.
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
		
		if ( ! isset( pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ] ) ) {
			return array(
				'api' => '1',
				'status' => 'error',
				'message' => 'Error #847383: Invalid destination ID. Not found.',
			);
		}
		
		unset( pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ] );
		pb_backupbuddy::save();
		
		return array(
			'api' => '1',
			'status' => 'ok',
			'message' => 'Destination deleted.',
			'destination_id' => $arguments['id'],
		);
		
	} // End run().
	
	
} // End class.
