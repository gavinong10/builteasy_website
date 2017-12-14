<?php

// Each shortcode method is passed two parameters: $atts (shortcode attributes) and $content (content if shortcode wraps text).
// Shortcodes should RETURN. Widgets echo.

class pb_backupbuddy_actions extends pb_backupbuddy_actionscore {
	
	
	
	/* admin_notices()
	 *
	 * Run anything in the admin that may output a notice / error. Runs at proper time to not gunk up HTML.
	 *
	 */
	function admin_notices() {
		
		backupbuddy_core::verify_directories( $skipTempGeneration = true );
		
	} // End admin_notices().
	
	
	
	/*	wp_update_backup_reminder()
	 *	
	 *	Sets up output buffering for reminder to backup before upgrading WordPress.
	 *	@see wp_update_backup_reminder_dump()
	 *	
	 *	@return		null
	 */
	function wp_update_backup_reminder() {
		ob_start( array( &$this, 'wp_update_backup_reminder_dump' ) );
		add_action( 'admin_footer', create_function( '', '@ob_end_flush();' ) );
	}
	
	
		
	/*	wp_update_backup_reminder_dump()
	 *	
	 *	Output buffer dump callback to output actual reminder text.
	 *	@see wp_update_backup_reminder()
	 *	
	 *	@return		string		Text of notice to display.
	 */
	function wp_update_backup_reminder_dump( $text = '' ) {
		return str_replace( '<h2>WordPress Updates</h2>', 
							'<h2>' . __('WordPress Updates', 'it-l10n-backupbuddy' ) . '</h2><div id="message" class="updated fade"><p><img src="' . pb_backupbuddy::plugin_url() . '/images/pluginbuddy.png" style="vertical-align: -3px;" /> <a href="admin.php?page=pb_backupbuddy_backup" target="_blank" style="text-decoration: none;">' . __('Remember to back up your site with BackupBuddy before upgrading!', 'it-l10n-backupbuddy' ) . '</a></p></div>', 
							$text );
	}
	
	
	
	/*	content_editor_backup_reminder_on_update()
	 *	
	 *	On post / page save injects an additional reminder to remember to back the site up if reminders are enabled.
	 *	
	 *	@param		array		$messages		Array of messages to be displayed.
	 *	@return		array						Returns modified array (or original if this is not the message to edit).
	 */
	function content_editor_backup_reminder_on_update( $messages ) {
		if ( !isset( $messages['post'] ) ) { // Fixes conflict with Simpler CSS plugin. Issue #226.
			return $messages;
		}
			
		pb_backupbuddy::$options['edits_since_last']++;
		pb_backupbuddy::save();
		$admin_url = '';
		//Only show the backup message for network admins or adminstrators
		if ( is_multisite() && current_user_can( 'manage_network' ) ) { // Network Admin in multisite. Don't show messages in this case.
			//$admin_url = admin_url( 'network/admin.php' );
			return $messages;
		} elseif( !is_multisite() && current_user_can( pb_backupbuddy::$options['role_access'] ) ) { // User with access in standalone.
			$admin_url = admin_url( 'admin.php' );
		} else {
			return $messages;
		}
		$fullbackup = esc_url( add_query_arg( array(
				'page' => 'pb_backupbuddy_backup',
				'backupbuddy_backup' => '2'
			), $admin_url
		) );
		$dbbackup = esc_url( add_query_arg( array(
				'page' => 'pb_backupbuddy_backup',
				'backupbuddy_backup' => '1'
			), $admin_url
		) );
		$backup_message = " | <a href='{$dbbackup}'>" . __('Database Backup', 'it-l10n-backupbuddy' ) . "</a> | <a href='{$fullbackup}'>" . __('Full Backup', 'it-l10n-backupbuddy' ) . "</a>";
		
		
		$reminder_posts = array(); // empty array to store customized post messages array
		$reminder_pages = array(); // empty array to store customized page messages array
		$others = array(); // An empty array to store the array for custom post types
		foreach ( $messages['post'] as $num => $message ) {
			$message .= $backup_message;
			if ( $num == 0 ) {
			$message = ''; // The first element in the messages['post'] array is always empty
			}
			array_push( $reminder_posts, $message ); // Insert/copy the modified message value to the last element of reminder array
		}
		$reminder_posts = array( 'post' => $reminder_posts ); // Apply the post key to the first dimension of messages array
		foreach ( $messages['page'] as $num => $message ) {
			$message .= $backup_message;
			if ( $num == 0 ) {
				$message = ''; // The first element in the messages['page'] array is always empty
			}
			array_push( $reminder_pages, $message ); // Insert/copy the modified message value to the last element of reminder array
		}
		$reminder_pages = array( 'page' => $reminder_pages ); // Apply the page key to the first dimension of messages array
		$reminder = array_merge( $reminder_posts, $reminder_pages );
		foreach ( $messages as $type => $message ) {
			if ( ( $type == 'post' ) || ( $type == 'page' ) ) { // Skip the post key since it is already defined
				continue;
			}
			$others[$type] = $message; // Since message is an array, this statement forms 2D array
		}
		$reminder = array_merge( $reminder, $others ); // Merge the arrays in the others array with reminder array in order to form an appropriate format for messages array
		
		return $reminder;
	}
	
	
	
	/*	multisite_network_warning()
	 *	
	 *	If BackupBuddy is detected to be running on Multisite but not Network Activated this warning is displayed as a reminder.
	 *	Todo: Only show this on BackupBuddy pages AND plugins.php?
	 *	
	 *	@param		
	 *	@return		
	 */
	function multisite_network_warning() {
		
		$message = 'BackupBuddy Multisite support is experimental beta software and is not officially supported in a Multisite setting.';
		
		if ( ! backupbuddy_core::is_network_activated() ) {
			$message .= ' You must <a href="' . esc_url( admin_url( 'network/plugins.php' ) ) . '">Network Activate</a> BackupBuddy to use it with Multisite (not activate within subsites nor the main site).';
		}
		
		if ( ! defined( 'PB_BACKUPBUDDY_MULTISITE_EXPERIMENT' ) || ( PB_BACKUPBUDDY_MULTISITE_EXPERIMENT != TRUE ) ) {
			$message .= ' You must add the following line to your wp-config.php to activate Multisite experimental functionality: <b>define( \'PB_BACKUPBUDDY_MULTISITE_EXPERIMENT\', true );</b>';
		}
		
		pb_backupbuddy::alert( $message, true );
	}
	
	
	
}
?>