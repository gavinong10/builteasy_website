<?php
class Ithemes_Sync_Verb_Backupbuddy_Test_Destination extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-test-destination';
	public static $description = 'Test provided destination settings. Can be tested prior to creation.';
	
	private $default_arguments = array(
		'settings' => array(),
	);
	
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
		if ( true === $results = pb_backupbuddy_destinations::test( $settings ) ) {
			
			return array(
				'api' => '0',
				'status' => 'ok',
				'message' => 'Supported destinations retrieved.',
			);
			
		} else {
			
			return array(
				'api' => '0',
				'status' => 'error',
				'message' => $results,
			);
			
		}
		
	} // End run().
	
	
} // End class.
