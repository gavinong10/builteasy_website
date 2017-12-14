<?php
global $wpdb;

delete_option("dsidxpress");
$wpdb->query("DELETE FROM `{$wpdb->prefix}options` WHERE option_name LIKE '_transient_idx_%' OR option_name LIKE '_transient_timeout_idx_%'");

$flushCacheTask = "cron-dsidxpress-flush-cache";
function dsidxpressRemoveCacheFlush() {
	wp_clear_scheduled_hook($flushCacheTask);
}
remove_action($flushCacheTask, "dsidxpressRemoveCacheFlush");

//remove_role("dsidxpress_visitor"); not sure if we want to do this because of the nature of the registration type and the quantity