<?php
$schedules = array();
foreach( pb_backupbuddy::$options['schedules'] as $schedule_id => $schedule ) {
	$schedules[] = array(
		'title' => strip_tags( $schedule['title'] ),
		'type' => pb_backupbuddy::$options['profiles'][$schedule['profile']]['type'],
		'interval' => $schedule['interval'],
		'lastRun' => $schedule['last_run'],
		'enabled' => $schedule['on_off'],
		'profileID' => $schedule['profile'],
		'profileTitle' => strip_tags( pb_backupbuddy::$options['profiles'][$schedule['profile']]['title'] ),
		'id' => $schedule_id
	);
}
return $schedules;