<?php
if ( ! is_admin() ) { die( 'Access Denied.' ); }

// Load notifications.
$notifications = backupbuddy_core::getNotifications();

// Compile table from notifications.
$notificationTable = array();
foreach( $notifications as $notification ) {
	
	$slug = $notification['slug'];
	if ( true === $notification['urgent'] ) {
		$slug = '<font color="red">' . $slug . '</font>';
	}
	
	$additionalData = '<i>None</i>';
	if ( count( $notification['data'] ) > 0 ) {
		//$additionalData = '<textarea>' . print_r( $notification['data'], true ) . '</textarea>';
		$additionalData = '<textarea class="backupbuddy-recent-activity-details" wrap="off">';
		foreach( $notification['data'] as $thisKey => $thisData ) {
			$additionalData .= str_pad( $thisKey, 15 ) . $thisData . "\n";
		}
		$additionalData .= '</textarea>';
	}
	
	$time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $notification['time'] ) );
	$time .= '<br><span class="description">' . pb_backupbuddy::$format->time_ago( $notification['time'] ) . ' ago</span>';
	
	$notificationTable[] = array(
		$slug,
		'<b>' . $notification['title'] . '</b><br>' . esc_html( $notification['message'] ),
		$time,
		$additionalData,
	);
}


// Flip newest up top.
$notificationTable = array_reverse( $notificationTable );

echo '<p>';
if ( count( $notificationTable ) > 0 ) {
	_e( 'Below lists some of the recent activity for this site.', 'it-l10n-backupbuddy' );
} else {
	_e( 'No recent activity logged yet.', 'it-l10n-backupbuddy' );
	return;
}
echo '</p>';

// Display table.
pb_backupbuddy::$ui->list_table(
	$notificationTable, // Array of cron items set in code section above.
	array(
		//'action'					=>	pb_backupbuddy::page_url() . '#pb_backupbuddy_getting_started_tab_tools',
		'columns'					=>	array(
											__( 'Action', 'it-l10n-backupbuddy' ),
											__( 'Title & Message', 'it-l10n-backupbuddy' ),
											__( 'When', 'it-l10n-backupbuddy' ),
											__( 'Additional Data', 'it-l10n-backupbuddy' ),
										),
		'css'						=>		'width: 100%;',
	)
);