<?php
/*
 * Provides command line access via WP-CLI: http://wp-cli.org/
 * @since Nov 11, 2013
 *
 */

if ( ! class_exists( 'WP_CLI_Command' ) ) {
	return;
}

class backupbuddy_wp_cli extends WP_CLI_Command {
	
	/**
	 * Run a BackupBuddy backup. http://getbackupbuddy.com
	 *
	 * ## OPTIONS
	 * 
	 * <profile>
	 * : Profile may either specify the profile ID number, "full" to run the first defined Full backup profile, or "db" to run the first defined Database-only backup profile. The first Full and Database-only profiles are always available as they are not user-deletable. To find the profile number, run a backup inside BackupBuddy in WordPress and note the number at the end of the URL (3 in this case): http://...&backupbuddy_backup=3
	 *
	 * [--quiet]
	 * : Suppresses display of status log information from being output to the screen.
	 *
	 * ## EXAMPLES
	 * 
	 *     RUN FULL BACKUP:     wp backupbuddy backup full
	 *     RUN PROFILE #3:      wp backupbuddy backup 3
	 *
	 * ## USAGE
	 *
	 *    wp backupbuddy backup <profile> [--quiet]
	 *
	 * @synopsis <profile> [--quiet]
	 */
	public function backup( $args, $assoc_args ) {
		if ( ! isset( $assoc_args['quiet'] ) ) {
			define( 'BACKUPBUDDY_WP_CLI', true );
		}
		
		
		$profile = $args[0];
		$results = backupbuddy_api::runBackup( $profile, $friendlyTrigger = 'wp-cli', $backupMode = '1' );
		
		
		if ( true === $results ) { // success
			WP_CLI::success( 'Backup completed successfully.' );
			return;
		} else { // fail
			WP_CLI::error( 'Error initiating backup. Details: ' . $results );
		}
		
	}
	
} // End backupbuddy_wp_cli class.

// Register with WP-CLI.
WP_CLI::add_command( 'backupbuddy', 'backupbuddy_wp_cli' );