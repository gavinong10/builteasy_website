<?php
class Ithemes_Sync_Verb_Backupbuddy_Get_DestinationSettings extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-get-destinationSettings';
	public static $description = 'Retrieve the settings for an existing destination.';
	
	private $default_arguments = array(
		'id'	=> '', // Destination ID to delete.
	);
	
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		if ( isset( pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ] ) ) {
			
			return array(
				'api' => '1',
				'status' => 'ok',
				'message' => 'Destination settings retrieved.',
				'settings' => pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ],
				'destination_id' => $arguments['id'],
			);
			
		} else {  // Invalid destination ID.
			
			return array(
				'api' => '1',
				'status' => 'error',
				'message' => 'Error #327783: Invalid destination ID.',
			);
			
		}
		
	} // End run().
	
	
} // End class.
