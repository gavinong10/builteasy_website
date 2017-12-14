<?php
backupbuddy_core::verifyAjaxAccess();


// Remote destination saving.
/*	remote_save()
*	
*	Remote destination saving.
*	
*	@return		null
*/


pb_backupbuddy::verify_nonce();


require_once( pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php' );
$settings_form = pb_backupbuddy_destinations::configure( array( 'type' => pb_backupbuddy::_POST( 'pb_backupbuddy_type' ) ), 'save' );

$save_result = $settings_form->process();


$destination_id = trim( pb_backupbuddy::_GET( 'pb_backupbuddy_destinationid' ) );


if ( count( $save_result['errors'] ) == 0 ) { // NO ERRORS SO SAVE.
	
	if ( $destination_id == 'NEW' ) { // ADD NEW.
	
		// Copy over dropbox token.
		$save_result['data']['token'] = pb_backupbuddy::$options['dropboxtemptoken'];
		
		pb_backupbuddy::$options['remote_destinations'][] = $save_result['data'];
		
		$newDestination = array();
		$newDestination['title'] = $save_result['data']['title'];
		$newDestination['type'] = $save_result['data']['type'];
		backupbuddy_core::addNotification( 'destination_created', 'Remote destination created', 'A new remote destination "' . $newDestination['title'] . '" has been created.', $newDestination );
		
		pb_backupbuddy::save();
		echo 'Destination Added.';
	} elseif ( !isset( pb_backupbuddy::$options['remote_destinations'][$destination_id] ) ) { // EDITING NONEXISTANT.
		echo 'Error #54859. Invalid destination ID `' . $destination_id . '`.';
	} else { // EDITING EXISTING -- Save!
		
		// Copy over dropbox token.
		//$token_copy_holder = pb_backupbuddy::$options['remote_destinations'][$destination_id]['token'];
		
		pb_backupbuddy::$options['remote_destinations'][$destination_id] = array_merge( pb_backupbuddy::$options['remote_destinations'][$destination_id], $save_result['data'] );
		//echo '<pre>' . print_r( pb_backupbuddy::$options['remote_destinations'][$destination_id], true ) . '</pre>';
		
		pb_backupbuddy::save();
		echo 'Settings saved.';
		
		$editedDestination = array();
		$editedDestination['title'] = $save_result['data']['title'];
		$editedDestination['type'] = $save_result['data']['type'];
		backupbuddy_core::addNotification( 'destination_updated', 'Remote destination updated', 'An existing remote destination "' . $editedDestination['title'] . '" has been updated.', $editedDestination );
	}
	
} else {
	echo "Error saving settings.\n\n";
	echo implode( "\n", $save_result['errors'] );
}
die();

