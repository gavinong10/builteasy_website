<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_remotesend_abort' ).click( function(){
			jQuery.ajax({
				type: 'POST',
				url: jQuery(this).attr( 'href' ),
				success: function(data){
					data = jQuery.trim( data );
					if ( '1' == data ) {
						alert( 'Remote transfer aborted. This may take a moment to take effect.' );
					} else {
						alert( 'Error #85448949. Unexpected server response. Details: `' + data + '`.' );
					}
				}
			});
			return false;
		});
	});
</script>
<?php

require_once( pb_backupbuddy::plugin_path() . '/classes/housekeeping.php' );
backupbuddy_housekeeping::trim_remote_send_stats();



$remote_sends = array();
$send_fileoptions = pb_backupbuddy::$filesystem->glob_by_date( backupbuddy_core::getLogDirectory() . 'fileoptions/send-*.txt' );
if ( ! is_array( $send_fileoptions ) ) {
	$send_fileoptions = array();
}
foreach( $send_fileoptions as $send_fileoption ) {
	
	$send_id = str_replace( '.txt', '', str_replace( 'send-', '', basename( $send_fileoption ) ) );
	
	pb_backupbuddy::status( 'details', 'About to load fileoptions data.' );
	require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
	pb_backupbuddy::status( 'details', 'Fileoptions instance #23.' );
	$fileoptions_obj = new pb_backupbuddy_fileoptions( backupbuddy_core::getLogDirectory() . 'fileoptions/send-' . $send_id . '.txt', $read_only = true, $ignore_lock = true, $create_file = false );
	if ( true !== ( $result = $fileoptions_obj->is_ok() ) ) {
		pb_backupbuddy::status( 'error', __('Fatal Error #9034.32393. Unable to access fileoptions data.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
		return false;
	}
	pb_backupbuddy::status( 'details', 'Fileoptions data loaded.' );
	
	$remote_sends[$send_id] = $fileoptions_obj->options;
	unset( $fileoptions_obj );
	
}



$sends = array();
//echo '<pre>' . print_r( $remote_sends, true ) . '</pre>';
foreach( $remote_sends as $send_id => $remote_send ) {
	// Set up some variables based on whether file finished sending yet or not.
	if ( $remote_send['finish_time'] > 0 ) { // Finished sending.
		$time_ago = pb_backupbuddy::$format->time_ago( $remote_send['finish_time'] ) . ' ago; <b>took ';
		$duration = pb_backupbuddy::$format->time_duration( $remote_send['finish_time'] - $remote_send['start_time'] ) . '</b>';
		$finish_time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $remote_send['finish_time'] ) );
	} else { // Did not finish (yet?).
		$time_ago = pb_backupbuddy::$format->time_ago( $remote_send['start_time'] ) . ' ago; <b>unfinished</b>';
		$duration = '';
		$finish_time = '<span class="description">Unknown</span>';
	}
	
	// Handle showing sent ImportBuddy (if sent).
	if ( isset( $remote_send['send_importbuddy'] ) && ( $remote_send['send_importbuddy'] === true ) ) {
		$send_importbuddy = '<br><span class="description" style="margin-left: 10px;">+ importbuddy.php</span>';
	} else {
		$send_importbuddy = '';
	}
	
	
	// Show file size (if available).
	if ( isset( $remote_send['file_size'] ) ) {
		$file_size = '<br><span class="description" style="margin-left: 10px;">Size: ' . pb_backupbuddy::$format->file_size( $remote_send['file_size'] ) . '</span>';
	} else {
		$file_size = '';
	}
	
	if ( isset( $remote_send['error'] ) ) {
		$error_details = '<br><span class="description" style="
  margin-left: 10px;
  display: block;
  max-width: 500px;
  max-height: 250px;
  overflow: scroll;
  background: #fff;
  padding: 10px;">' . $remote_send['error'] . '</span>';
	} else {
		$error_details = '';
	}
	
	
	// Status verbage & styling based on send status.
	if ( $remote_send['status'] == 'success' ) {
		$status = '<span class="pb_label pb_label-success">Success</span>';
	} elseif ( $remote_send['status'] == 'running' ) {
		$status = '<span class="pb_label pb_label-info">In progress or timed out</span>'; // <a class="pb_backupbuddy_remotesend_abort" href="' . pb_backupbuddy::ajax_url( 'remotesend_abort' ) . '&send_id=' . $send_id  . '">( Abort )</a>';
	} elseif ( $remote_send['status'] == 'timeout' ) {
		$status = '<span class="pb_label pb_label-error">Failed (likely timeout)</span>'; // <a class="pb_backupbuddy_remotesend_abort" href="' . pb_backupbuddy::ajax_url( 'remotesend_abort' ) . '&send_id=' . $send_id  . '">( Abort )</a>';
	} elseif ( $remote_send['status'] == 'failed' ) {
		$status = '<span class="pb_label pb_label-error">Failed</span>';
	} elseif ( $remote_send['status'] == 'aborted' ) {
		$status = '<span class="pb_label pb_label-warning">Aborted by user</span>';
	} elseif ( $remote_send['status'] == 'multipart' ) {
		$status = '<span class="pb_label pb_label-info">Multipart transfer</span>'; // <a class="pb_backupbuddy_remotesend_abort" href="' . pb_backupbuddy::ajax_url( 'remotesend_abort' ) . '&send_id=' . $send_id  . '">( Abort )</a>';
	} else {
		$status = '<span class="pb_label pb_label-important">' . ucfirst( $remote_send['status'] ) . '</span>';
	}
	if ( isset( $remote_send['_multipart_status'] ) ) {
		$status .= '<br>' . $remote_send['_multipart_status'];
	}
	
	$status .= '<div class="row-actions">';
	
	// Display 'View Log' link if log available for this send.
	$log_file = backupbuddy_core::getLogDirectory() . 'status-remote_send-' . $send_id . '_' . pb_backupbuddy::$options['log_serial'] . '.txt';
	if ( file_exists( $log_file ) ) {
		$status .= '<a title="' . __( 'View Remote Send Log', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'remotesend_details' ) . '&send_id=' . $send_id . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">View Log</a>';
	}
	
	$status .= ' | <a title="' . __( 'Remote Send Technical Details', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'send_status' ) . '&send_id=' . $send_id . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">View Details</a>';
	
	if ( 'success' != $remote_send['status'] ) {
		$status .= ' | <a title="' . __( 'Force resend attempt', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'remotesend_retry' ) . '&send_id=' . $send_id . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">Retry Send Now</a>';
	}
	
	
	
	$status .= '</div>';
	
	
	
	
	// Determine destination.
	if ( isset( pb_backupbuddy::$options['remote_destinations'][$remote_send['destination']] ) ) { // Valid destination.
		$destination = pb_backupbuddy::$options['remote_destinations'][$remote_send['destination']]['title'] . ' (' . pb_backupbuddy::$options['remote_destinations'][$remote_send['destination']]['type'] . ')';
	} else { // Invalid destination (been deleted since send?).
		$destination = '<span class="description">Unknown</span>';
	}
	
	$write_speed = '';
	if ( isset( $remote_send['write_speed'] ) && ( '' != $remote_send['write_speed'] ) ) {
		$write_speed = 'Transfer Speed: &gt; ' . pb_backupbuddy::$format->file_size( $remote_send['write_speed'] ) . '/sec<br>';
	}
	
	$trigger = ucfirst( $remote_send['trigger'] );
	$base_file = basename( $remote_send['file'] );
	if ( 'remote-send-test.php' == $base_file ) {
		$base_file = __( 'Remote destination test', 'it-l10n-backupbuddy' ) . '<br><span class="description" style="margin-left: 10px;">(Send & delete test file remote-send-test.php)</span>';
		$file_size = '';
		$trigger = __( 'Manual settings test', 'it-l10n-backupbuddy' );
		$destination = '<span class="description">Test settings</span>';
	}
	
	// Push into array.
	$sends[] = array(
		$base_file . $file_size . $send_importbuddy . $error_details,
		$destination,
		$trigger,
		$write_speed .
		'Start: ' . pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time(  $remote_send['start_time'] ) ) . '<br>' .
		'Finish: ' . $finish_time . '<br>' .
		'<span class="description">' . $time_ago  . $duration . '</span>',
		$status,
	);
} // End foreach.


if ( count( $sends ) == 0 ) {
	echo '<br><span class="description">' . __( 'There have been no recent file transfers.', 'it-l10n-backupbuddy' ) . '</span><br>';
} else {
	pb_backupbuddy::$ui->list_table(
		$sends,
		array(
			'action'		=>	pb_backupbuddy::page_url(),
			'columns'		=>	array(
				__( 'Backup File', 'it-l10n-backupbuddy' ),
				__( 'Destination', 'it-l10n-backupbuddy' ),
				__( 'Trigger', 'it-l10n-backupbuddy' ),
				__( 'Transfer Information', 'it-l10n-backupbuddy' ) . ' <img src="' . pb_backupbuddy::plugin_url() . '/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent started first">',
				__( 'Status', 'it-l10n-backupbuddy' ) . ' <span class="description">(hover for options)</span>',
				),
			'css'			=>		'width: 100%;',
		)
	);
	echo '<div class="alignright actions">';
	pb_backupbuddy::$ui->note( 'Hover over items above for additional options.' );
	echo '</div>';
}

?><br>