<?php
class Ithemes_Sync_Verb_Backupbuddy_List_DestinationTypes extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-list-destinationTypes';
	public static $description = 'List available destination types for this server.';
	
	private $default_arguments = array(
	);
	
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
		
		return array(
			'api' => '0',
			'status' => 'ok',
			'message' => 'Supported destinations retrieved.',
			'destinations' => pb_backupbuddy_destinations::get_destinations_list(),
		);
		
	} // End run().
	
	
} // End class.
