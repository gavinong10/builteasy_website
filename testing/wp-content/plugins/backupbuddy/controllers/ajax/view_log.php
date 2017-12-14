<?php
backupbuddy_core::verifyAjaxAccess();


pb_backupbuddy::$ui->ajax_header();

$serial = pb_backupbuddy::_GET( 'serial' );
$logFile = backupbuddy_core::getLogDirectory() . 'status-' . $serial . '_sum_' . pb_backupbuddy::$options['log_serial'] . '.txt';

if ( ! file_exists( $logFile ) ) {
	die( 'Error #858733: Log file `' . $logFile . '` not found or access denied.' );
}

$lines = file_get_contents( $logFile );
$lines = explode( "\n", $lines );
?>

<textarea readonly="readonly" id="backupbuddy_messages" wrap="off" style="width: 100%; min-height: 400px; height: 500px; height: 80%; background: #FFF;"><?php
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
?></textarea><br><br>
<small>Log file: <?php echo $logFile; ?></small>
<br>
<?php
echo '<small>Last modified: ' . pb_backupbuddy::$format->date( filemtime( $logFile ) ) . ' (' . pb_backupbuddy::$format->time_ago( filemtime( $logFile ) ) . ' ago)';
?>
<br><br>


<?php
pb_backupbuddy::$ui->ajax_footer();
die();