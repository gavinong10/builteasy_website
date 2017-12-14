<?php
backupbuddy_core::verifyAjaxAccess();


die( 'NOT IMPLEMENTED. SEE _getBackupStatus.php for implementation.' );

if ( ! is_admin() ) { die( 'Access denied.' ); }

update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking.
spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.