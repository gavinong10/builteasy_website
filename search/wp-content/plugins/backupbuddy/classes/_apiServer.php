<?php
/*
Payload:
	backupbuddy_api
	backupbuddy_version
	verb
*/


defined('ABSPATH') or die( '404 Not Found' );
if ( ! isset( $_POST['backupbuddy_api'] ) ) {
	die( '404 Not Found.' );
}



backupbuddy_api_server::runVerb( pb_backupbuddy::_POST( 'verb' ) );

class backupbuddy_api_server {
	
	
	
	public static function runVerb( $verb ) {
		
		if ( 'getPreDeployInfo' == $verb ) {
			self::apiReturn( backupbuddy_api::getPreDeployInfo() );
		}
		
	}
	
	
	
	public static function apiReturn( $data ) {
		die(
			json_encode(
				array(
					'success' => true,
					'data' => $data
				)
			)
		);
	} // End apiReturn().
	
	
	
} // End class.