<?php
// backupbuddy_core class always available to functions here.

class pb_backupbuddy_ajax extends pb_backupbuddy_ajaxcore {
	
	
	public function backupbuddy() {
		$function = str_replace( array( '/', '\\' ), '', pb_backupbuddy::_GET( 'function' ) );
		if ( '' == $function ) {
			$function = str_replace( array( '/', '\\' ), '', pb_backupbuddy::_POST( 'function' ) );
		}
		$file = pb_backupbuddy::plugin_path() . '/controllers/ajax/' . $function . '.php';
		if ( ! file_exists( $file ) ) {
			die('0');
		}
		
		pb_backupbuddy::load();
		//pb_backupbuddy::$ui->ajax_header();
		require_once( 'ajax/' . $function . '.php' );
		//pb_backupbuddy::$ui->ajax_footer();
		//die();
		die();
	} // End backupbuddy().
	
	
	
	// Access credentials _MUST_ always be checked before allowing any access whatsoever.
	function api() {
		die('0');
		
		// TODO: Internal security lockout.
		if ( ( ! isset( pb_backupbuddy::$options['api_key_test'] ) ) || ( pb_backupbuddy::$options['api_key_test'] == '' ) ) {
			die('0');
		}
		if ( pb_backupbuddy::$options['api_key_test'] != 'dsnfilasbfisybfdjybfjalybsfaklsbfa' ) {
			die('0');
		}
		
		$run = pb_backupbuddy::_POST( 'run' );
		// TODO: TESTING temp allow GET method:
		if ( '' == $run ) {
			$run = pb_backupbuddy::_GET( 'run' );
		}
		
		if ( '' == $run ) {
			die( json_encode( array( 'success' => false, 'error' => 'Error #489384: Missing run command.' ) ) );
		} else {
			if ( false === ( $return = call_user_func(  'backupbuddy_api::' . $run ) ) ) {
				die( json_encode( array( 'success' => false, 'error' => 'Error #328983: Command failed.' ) ) );
			} else {
				die( json_encode( array( 'success' => true, 'version' => pb_backupbuddy::settings( 'version' ), 'data' => $return ) ) );
			}
		}
		
		die();
	} // end api().
	
	
} // end class.