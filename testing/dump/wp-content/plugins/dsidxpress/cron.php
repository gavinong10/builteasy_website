<?php

if (defined('ZPRESS_API') && constant('ZPRESS_API'))
	return;

$flushCacheTask = "cron-dsidxpress-flush-cache";

add_action($flushCacheTask, array("dsIDXpress_Cron", "FlushCache"));

if (!wp_next_scheduled($flushCacheTask))
	wp_schedule_event(time(), "daily", $flushCacheTask);

class dsIDXpress_Cron {
	static function FlushCache() {
		global $wpdb;
		$wpdb->query("
			DELETE
			FROM `{$wpdb->prefix}options`
			WHERE
				option_name LIKE '_transient_%idx_%'
				AND RIGHT(option_name, 40) IN (
					SELECT options_to_clear.HashedKey FROM (SELECT RIGHT(option_name, 40) AS HashedKey
					FROM `{$wpdb->prefix}options`
					WHERE
						option_name LIKE '_transient_timeout_idx_%'
						AND option_value < UNIX_TIMESTAMP()
				) options_to_clear)
		");
	}
}