<?php
class Ithemes_Sync_Verb_Backupbuddy_List_Destinations extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-list-destinations';
	public static $description = 'List backup destinations.';
	
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
		
		$destinations = array();
		foreach( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
			$destinations[$destination_id] = array(
				'title' => strip_tags( $destination['title'] ),
				'type' => $destination['type'],
				'id' => $destination_id
			);
		}
		
		return array(
			'api' => '0',
			'status' => 'ok',
			'message' => 'Destinations listed successfully.',
			'destinations' => $destinations,
		);
		
	} // End run().
	
	
} // End class.
