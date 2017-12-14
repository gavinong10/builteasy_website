<?php
class Ithemes_Sync_Verb_Backupbuddy_Get_Importbuddy extends Ithemes_Sync_Verb {
	public static $name = 'backupbuddy-get-importbuddy';
	public static $description = 'Get importbuddy.php file.';

	private $default_arguments = array(
		'password'	=> '',
	);

	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		if ( '' == $arguments['password'] ) { // no password send in arguments.
			if ( !isset( pb_backupbuddy::$options ) ) {
				pb_backupbuddy::load();
			}
			if ( '' == pb_backupbuddy::$options['importbuddy_pass_hash'] ) { // no default password is set on Settings page.
				return array(
					'api' => '0',
					'status' => 'error',
					'message' => 'No ImportBuddy password was entered and no default has been set on the Settings page.'
				);
			} else { // Use default.
				$importbuddy_pass_hash = pb_backupbuddy::$options['importbuddy_pass_hash'];
			}
		} else { // Password passed in arguments.
			$importbuddy_pass_hash = md5( $arguments['password'] );
		}

		require_once( pb_backupbuddy::plugin_path() . '/classes/core.php' );

		$ibscript = backupbuddy_core::importbuddy( '', $importbuddy_pass_hash, $returnNotEcho = true );
		$ibhash = md5($ibscript);

		return array(
			'api' => '5',
			'status' => 'ok',
			'message' => 'ImportBuddy retrieved.',
			'importbuddy_hash' => $ibhash,
			'importbuddy' => base64_encode( $ibscript ),
		);

	} // End run().


} // End class.

