<?php
class Ithemes_Sync_Verb_Backupbuddy_Get_LatestBackupProcess extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-get-latestBackupProcess';
	public static $description = 'Get information on the latest backup process.';
	
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
		
		
		if ( false === ( $currentBackupStats = backupbuddy_api::getLatestBackupStats() ) ) {
			
			return array(
				'api' => '0',
				'status' => 'error',
				'message' => 'Error #238923: Stats for the latest backup not found. One may have not started yet or stats file is missing.',
			);
			
		} else {
			
			return array(
				'version'					=> '4',
				'status'					=> 'ok',
				'message'					=> 'Latest backup process details retrieved successfully.',
				'latestBackupProcess'		=> $currentBackupStats,
				'localTime'					=> time(),
			);
			
		}
		
	} // End run().
	
	
} // End class.
