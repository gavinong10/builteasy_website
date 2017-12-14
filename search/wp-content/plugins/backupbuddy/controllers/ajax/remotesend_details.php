<?php
backupbuddy_core::verifyAjaxAccess();


// Display backup integrity status.
/* remotesend_details()
 *
 * View log for a remote destination file transfer. Outputs HTML and information and die()'s.
 *
 */


$send_id = pb_backupbuddy::_GET( 'send_id' );
$send_id = str_replace( '/\\', '', $send_id );

pb_backupbuddy::$ui->ajax_header();

$log_file = backupbuddy_core::getLogDirectory() . 'status-remote_send-' . $send_id . '_' . pb_backupbuddy::$options['log_serial'] . '.txt';
if ( ! file_exists( $log_file ) ) {
	die( 'Error #4438958945985: Unable to read log file `' . $log_file . '`.' );
}

// Display log.
echo '<textarea style="width: 100%; height: 80%;" wrap="off" readonly="readonly">';
$lines = file_get_contents( $log_file );
if ( false === $lines ) {
	echo 'Error #849834: Unable to read log file `' . $log_file . '`.';
} else {
	$lines = explode( "\n", $lines );
	foreach( (array)$lines as $rawline ) {
		$line = json_decode( $rawline, true );
		//print_r( $line );
		if ( is_array( $line ) ) {
			$u = '';
			if ( isset( $line['u'] ) ) { // As off v4.2.15.6. TODO: Remove this in a couple of versions once old logs without this will have cycled out.
				$u = '.' . $line['u'];
			}
			echo pb_backupbuddy::$format->date( $line['time'], 'G:i:s' ) . $u . "\t\t";
			echo $line['run'] . "sec\t";
			echo $line['mem'] . "MB\t";
			echo $line['event'] . "\t";
			echo $line['data'] . "\n";
		} else {
			echo $rawline . "\n";
		}
	}
}
echo '</textarea>
<br><br>
<small>Log file: ' . $log_file . '</small>
<br>
<small>Last modified: ' . pb_backupbuddy::$format->date( filemtime( $log_file ) ) . ' (' . pb_backupbuddy::$format->time_ago( filemtime( $log_file ) ) . ' ago)';

pb_backupbuddy::$ui->ajax_footer();
die();

