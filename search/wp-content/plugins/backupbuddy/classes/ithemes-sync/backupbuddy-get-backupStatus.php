<?php
class Ithemes_Sync_Verb_Backupbuddy_Get_BackupStatus extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-get-backupStatus';
	public static $description = 'Retrieve backup status log up to this point.';
	
	private $default_arguments = array(
		'serial'	=> '', // Backup serial.
	);
	
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		backupbuddy_api::getBackupStatus( $arguments['serial'] );
		return;
		
	} // End run().
	
	
} // End class.
