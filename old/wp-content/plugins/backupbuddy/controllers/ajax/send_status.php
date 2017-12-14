<?php
backupbuddy_core::verifyAjaxAccess();


// Display backup integrity status.

/* integrity_status()
*
* description
*
*/
$send_id = pb_backupbuddy::_GET( 'send_id' );
$send_di = str_replace( '/\\', '', $send_id );
pb_backupbuddy::load();
pb_backupbuddy::$ui->ajax_header();

require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
pb_backupbuddy::status( 'details', 'Fileoptions instance #27.' );
$optionsFile = backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt';
$send_options = new pb_backupbuddy_fileoptions( $optionsFile, $read_only = true );
if ( true !== ( $result = $send_options->is_ok() ) ) {
	pb_backupbuddy::alert( __('Unable to access fileoptions data file.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
	die();
}





$start_time = 'Unknown';
$finish_time = 'Unknown';
if ( isset( $send_options->options['start_time'] ) ) {
	$start_time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $send_options->options['start_time'] ) ) . ' <span class="description">(' . pb_backupbuddy::$format->time_ago( $send_options->options['start_time'] ) . ' ago)</span>';
	if ( $send_options->options['finish_time'] > 0 ) {
		$finish_time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $send_options->options['finish_time'] ) ) . ' <span class="description">(' . pb_backupbuddy::$format->time_ago( $send_options->options['finish_time'] ) . ' ago)</span>';
	} else { // unfinished.
		$finish_time = '<i>Unfinished</i>';
	}
}





$steps = array();
$steps[] = array( 'Start Time', $start_time );


$steps[] = array(
	'File',
	'<span title="' . $send_options->options['file'] . '">' . basename( $send_options->options['file'] ) . '</span>',
);
$destination = '<i>Unknown</i>';
if ( isset( pb_backupbuddy::$options['remote_destinations'][ $send_options->options['destination'] ] ) ) {
	$destination = htmlentities( pb_backupbuddy::$options['remote_destinations'][ $send_options->options['destination'] ]['title'] ) . ' [' . backupbuddy_core::pretty_destination_type( pb_backupbuddy::$options['remote_destinations'][ $send_options->options['destination'] ]['type'] ) . ']';
}
$steps[] = array(
	'Destination',
	$destination,
);


// Total overall time from initiation to end.
if ( isset( $send_options->options['finish_time'] ) && isset( $send_options->options['start_time'] ) && ( $send_options->options['finish_time'] != 0 ) && ( $send_options->options['start_time'] != 0 ) ) {
	$seconds = ( $send_options->options['finish_time'] - $send_options->options['start_time'] );
	if ( $seconds < 1 ) {
		$total_time = '< 1 second';
	} else {
		$total_time = $seconds . ' seconds';
	}
} else {
	$total_time = '<i>Unknown</i>';
}
$steps[] = array( 'Last Updated Time', $send_options->options['update_time'] );
$steps[] = array( 'Finish Time', $finish_time );
$steps[] = array(
	'Total Overall Time',
	$total_time,
	'',
);





$steps[] = array(
	'Status',
	'<span title="' . $send_options->options['file'] . '">' . $send_options->options['status'] . '</span>',
);
$steps[] = array(
	'Write Speed',
	'<span title="' . $send_options->options['file'] . '">&gt; ' . pb_backupbuddy::$format->file_size( $send_options->options['write_speed'] ) . '</span>',
);
$steps[] = array(
	'Trigger',
	$send_options->options['trigger']
);
$steps[] = array(
	'Send ID',
	$send_options->options['sendID']
);
if ( isset( $send_options->options['retries'] ) ) {
	$steps[] = array(
		'Retries',
		$send_options->options['retries']
	);
}



$columns = array(
	__( 'Backup Steps', 'it-l10n-backupbuddy' ),
	__( 'Time', 'it-l10n-backupbuddy' ),
);

if ( count( $steps ) == 0 ) {
	_e( 'No step statistics were found for this backup.', 'it-l10n-backupbuddy' );
} else {
	pb_backupbuddy::$ui->list_table(
		$steps,
		array(
			'columns'		=>	$columns,
			'css'			=>	'width: 100%; min-width: 200px;',
		)
	);
}
echo '<br><br>';
//***** END STEPS.




echo '<br><br><br>';

echo '<a class="button secondary-button" onclick="jQuery(\'#pb_backupbuddy_advanced_debug\').slideToggle();">Display Advanced Debugging</a>';
echo '<div id="pb_backupbuddy_advanced_debug" style="display: none;">';
echo '<textarea style="width: 100%; height: 400px;" wrap="on">';
echo print_r( $send_options->options, true );
echo '</textarea>';
echo 'From options file: `' . $optionsFile . '`.<br>';
echo '<br><br>';
echo '</div><br><br>';


pb_backupbuddy::$ui->ajax_footer();
die();
