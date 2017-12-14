<?php
class Ithemes_Sync_Verb_Backupbuddy_Edit_Destination extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-edit-destination';
	public static $description = 'Edit an existing destionation.';
	
	private $default_arguments = array(
		'settings'	=> array(), // Array of all destination settings for this destination type. These are NOT verified and must be insured to be accurate and sanitized prior to entry. Defaults always merged later down the line.
		'id'		=>	'',	// Destination ID number.
	);
	
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		if ( ( '' == $arguments['id'] ) || ( ! is_numeric( $arguments['id'] ) ) ) {
			return array(
				'api' => '1',
				'status' => 'error',
				'message' => 'Missing destination ID setting or not numeric.',
			);
		}
		
		if ( ! isset( pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ] ) ) {
			return array(
				'api' => '1',
				'status' => 'error',
				'message' => 'Invalid destination ID. Not found.',
			);
		}
		
		// Merge passed settings over current ones.
		pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ] = array_merge( pb_backupbuddy::$options['remote_destinations'][ $arguments['id'] ], $arguments['settings'] );
		pb_backupbuddy::save();
		
		return array(
			'api' => '1',
			'status' => 'ok',
			'message' => 'Destination updated.',
			'destination_id' => $arguments['id'],
		);
		
	} // End run().
	
	
} // End class.
