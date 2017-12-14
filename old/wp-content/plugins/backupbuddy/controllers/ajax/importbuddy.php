<?php
backupbuddy_core::verifyAjaxAccess();


$pass_hash = '';
$password = stripslashes( pb_backupbuddy::_GET( 'p' ) );

if ( $password != '' ) {
	$pass_hash = md5( $password );
	if ( pb_backupbuddy::$options['importbuddy_pass_hash'] == '' ) { // if no default pass is set then we set this as default.
		pb_backupbuddy::$options['importbuddy_pass_hash'] = $pass_hash;
		pb_backupbuddy::$options['importbuddy_pass_length'] = strlen( $password ); // length of pass pre-hash.
		pb_backupbuddy::save();
	}
}

backupbuddy_core::importbuddy( '', $pass_hash ); // Outputs importbuddy to browser for download.

die();