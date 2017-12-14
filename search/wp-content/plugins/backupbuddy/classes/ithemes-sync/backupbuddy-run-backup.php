<?php
class Ithemes_Sync_Verb_Backupbuddy_Run_Backup extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-run-backup';
	public static $description = 'Run a backup profile.';
	
	private $default_arguments = array(
		'profile'	=> '', // Valid values: db, full, [numeric profile ID]
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
		
		$results = backupbuddy_api::runBackup( $arguments['profile'], 'iThemes Sync', $backupMode = '2' );
		
		if ( true !== $results ) {
			return array(
				'api' => '0',
				'status' => 'error',
				'message' => 'Error running backup. Details: ' . $results,
			);
		
		} else {
			
			return array(
				'api' => '0',
				'status' => 'ok',
				'message' => 'Backup initiated successfully.',
				'serial' => $serial,
			);
			
		}
		
	} // End run().
	
	
} // End class.
