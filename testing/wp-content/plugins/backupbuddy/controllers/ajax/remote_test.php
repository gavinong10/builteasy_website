<?php
backupbuddy_core::verifyAjaxAccess();


// Remote destination testing.
/*	remote_test()
 *	
 *	Remote destination testing. Echos.
 *	
 *	@return		null
 */


if ( defined( 'PB_DEMO_MODE' ) ) {
	die( 'Access denied in demo mode.' );
}

global $pb_backupbuddy_destination_errors;
$pb_backupbuddy_destination_errors = array();


require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );

$form_settings = array();
foreach( pb_backupbuddy::_POST() as $post_id => $post ) {
	if ( substr( $post_id, 0, 15 ) == 'pb_backupbuddy_' ) {
		$id = substr( $post_id, 15 );
		if ( $id != '' ) {
			$form_settings[$id] = $post;
		}
	}
}

$test_result = pb_backupbuddy_destinations::test( $form_settings );

if ( $test_result === true ) {
	echo 'Test successful.';
} else {
	echo " Test failed.\n\n";
	echo $test_result;
	foreach( $pb_backupbuddy_destination_errors as $pb_backupbuddy_destination_error ) {
		echo $pb_backupbuddy_destination_error . "\n";
	}
}

die();

