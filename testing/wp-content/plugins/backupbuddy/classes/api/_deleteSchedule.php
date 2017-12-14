<?php
if ( true !== $confirm ) {
	return false;
}

$next_scheduled_time = wp_next_scheduled( 'backupbuddy_cron', array( 'run_scheduled_backup', array( (int)$scheduleID ) ) );
if ( FALSE === backupbuddy_core::unschedule_event( $next_scheduled_time, 'backupbuddy_cron', array( 'run_scheduled_backup', array( (int)$scheduleID ) ) ) ) {
	return false;
}

$deletedSchedule = pb_backupbuddy::$options['schedules'][$scheduleID];
unset( pb_backupbuddy::$options['schedules'][$scheduleID] );
pb_backupbuddy::save();

backupbuddy_core::addNotification( 'schedule_deleted', 'Backup schedule deleted', 'An existing backup schedule "' . $deletedSchedule['title'] . '" has been deleted.', $deletedSchedule );

return true;