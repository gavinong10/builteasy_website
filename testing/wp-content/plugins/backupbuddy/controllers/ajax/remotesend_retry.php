<?php
backupbuddy_core::verifyAjaxAccess();
pb_backupbuddy::$ui->ajax_header();

$send_id = pb_backupbuddy::_GET( 'send_id' );
$send_id = str_replace( '/\\', '', $send_id );

require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = true, $ignore_lock = false, $create_file = false );
if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
	pb_backupbuddy::status( 'error', __('Fatal Error #9034.23443. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
	return false;
}

// Don't do anything for success, failure, or already-marked as -1 finish time.
$whyNoSend = '';
if ( 'success' == $fileoptions_obj->options['status'] ) {
	$whyNoSend = 'This transfer is already marked as sucessfully completing.';
}
/* elseif ( 'failure' == $fileoptions_obj->options['status'] ) {
	$whyNoSend = 'This transfer is marked as officially failed.';
} elseif ( -1 == $fileoptions_obj->options['finish_time'] ) {
	$whyNoSend = 'This transfer is marked as cancelled.';
} */


if ( '' != $whyNoSend ) {
	die( 'Error #8438483:<br><br>This send is not eligable to be resent. ' . $whyNoSend . ' Please re-initiate a send for this file manually with a fresh send.' );
}

echo '<center>';
echo '<img src="' . pb_backupbuddy::plugin_url() . '/destinations/' . $fileoptions_obj->options['destinationSettings']['type'] . '/icon50.png">';
echo '<br>';
echo '<h3>Resending to "' . $fileoptions_obj->options['destinationSettings']['title'] . '"</h3>';
echo '<br>';

if ( ! isset( $fileoptions_obj->options['destinationSettings'] ) || ( count( $fileoptions_obj->options['destinationSettings'] ) == 0 ) ) {
	echo 'This remote send does not support retrying. Please re-send manually.';
} else {
	$response = backupbuddy_core::remoteSendRetry( $fileoptions_obj, $send_id, 20 ); // 20 is max retries allowed; it is NOT how many tries will actually be ran.
	if ( true === $response ) {
		pb_backupbuddy::alert( '<b>Success</b> initiating retry attempt. It has been re-scheduled to send momentarily. Refresh the page for updates.' );
	} else {
		pb_backupbuddy::alert( 'Error #238933: File has NOT been scheduled for re-sending. Manually re-send or see plugin log for details.', true );
	}
}
echo '</center>';

echo '<br><br><br><br><br><hr><br><br>';
echo '<b>Troubleshooting Data (if asked for by technical support):</b><br>';
echo '<textarea style="width: 100%; height: 75%; max-height: 300px;" wrap="off" readonly="readonly">' . print_r( $fileoptions_obj->options, true ) . '</textarea>';