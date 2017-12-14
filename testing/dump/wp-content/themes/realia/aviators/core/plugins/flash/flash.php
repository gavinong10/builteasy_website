<?php


define( 'AVIATORS_FLASH_INFO', 'alert-info' );
define( 'AVIATORS_FLASH_ERROR', 'alert-error' );
define( 'AVIATORS_FLASH_SUCCESS', 'alert-success' );


function aviators_flash_add_message( $level, $message ) {
	$_SESSION['flash'][] = array(
		'level'   => $level,
		'message' => $message,
	);
}


function aviators_flash_has_messages() {
	if ( ! empty( $_SESSION['flash'] ) ) {
		return TRUE;
	}
	return FALSE;
}


function aviators_flash_get_messages() {
	if ( ! empty( $_SESSION['flash'] ) ) {
		$messages = $_SESSION['flash'];
		unset( $_SESSION['flash'] );
		return $messages;
	}
	return NULL;
}