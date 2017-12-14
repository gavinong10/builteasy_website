<?php
/**
 *	_migrate_database.php
 *
 *	Handles all SQL data migration for both importbuddy and multisite importing.
 *	Handles updating paths, URLs, etc.
 *	
 *	@since 1.0.1
 *	@author Dustin Bolton
 *
 *	REQUIREMENTS:
 *
 *	1) Set up the variable $this->destinationType to the destination type if non-standalone (default) or Multisite Network (auto-detected). Valid values: standalone, multisite_import, multisite_network
 *	2) Mysql should already be connected.
 *	3) $this->restoreData['dat'] should be initialized with the DAT file array.
 *	4) If migrating a network -> network then set up the variable $this->networkPrefix to be the database prefix of the network. Needed to access users tables.
 *	5) If multisite_import is DESTINATION then $wp_upload_dir (upload_path option), $this->restoreData['upload_url'] (fileupload_url option) must be set.
 *
 *	USED BY:
 *
 *	1) ImportBuddy
 *	2) Multisite Import / Restore
 *
 *	NOTES:
 *
 *	- Example instantiation: $migrate = new backupbuddy_migrateDB( 'standalone', $this->_state );
 *	- dbreplace class intelligently ignores replacing values with identical values for performance.
 *
 */



global $wpdb;

if ( isset( $destination_type ) && ( $destination_type == 'multisite_import' ) ) { // MULTISITE IMPORT
	
	$state = array(
		'dat' => pb_backupbuddy::$options['dat_file'],
		'maxExecutionTime' => 30,
		'siteurl' => $destination_siteurl,
		'homeurl' => $destination_home,
		'upload_url' => $this->restoreData['upload_url'], // MS related stuff.
		'databaseSettings' => array(
									'prefix' => $multisite_network_db_prefix,
									'migrateDatabaseBruteForce' => true,
								)
	);
	
	$migrate = new backupbuddy_migrateDB( $destination_type, $state, $multisite_network_db_prefix );
}



class backupbuddy_migrateDB {

	const TIME_WIGGLE_ROOM = 1; // Number of seconds to fudge up the time elapsed to give a little wiggle room so we don't accidently hit the edge and time out.
	
	var $startTime; // Microtime migration started.
	
	var $restoreData;
	var $sourceType;
	var $destinationType;
	var $networkPrefix;
	var $overridePrefix; // Prefix to use. Defaults to $this->restoreData['databaseSettings']['prefix']. Allows overriding.
	var $finalPrefix; // Final prefix to use. If not overriding then ths will be the same as the $overridePrefix.

	var $oldURLs;
	var $newURLs;
	
	var $oldFullReplace;
	var $newFullReplace;
	
	var $networkUploadURLReal;
	var $bruteforceExcludedTables;

	
	// DESTINATION TYPE. Valid values: standalone, multisite_import, multisite_network
	// RETURNS: true on success fully completing, array( resumeFunction, resumePoint ) on needing chunking. false on failure.
	function __construct( $destinationType, $restoreData, $networkPrefix = '', $overridePrefix = '' ) {

		$this->startTime = microtime( true ); // Start tracking for elapsed time.
		
		$this->destinationType = $destinationType;
		$this->restoreData = &$restoreData;
		
		if ( '' == $overridePrefix ) { // Not overriding prefix.
			$this->overridePrefix = $this->restoreData['databaseSettings']['prefix'];
			$this->finalPrefix = $this->restoreData['databaseSettings']['prefix'];
			pb_backupbuddy::status( 'details', 'Using normal mode database prefix based on settings: `' . $this->overridePrefix . '`.' );
		} else { // Overriding prefix.
			$this->overridePrefix = $overridePrefix;
			$this->finalPrefix = $this->restoreData['databaseSettings']['prefix'];
			pb_backupbuddy::status( 'details', 'Using override database prefix: `' . $this->overridePrefix . '`. Final prefix: `' . $this->finalPrefix . '`.' );
		}
		
		$this->networkPrefix = $networkPrefix; 
		if ( '' == $networkPrefix ) {
			$this->networkPrefix = $this->overridePrefix;
		}

		pb_backupbuddy::status( 'message', 'Migrating database content...' );
		pb_backupbuddy::status( 'details', 'Destination site table prefix: ' . $this->overridePrefix );

		if ( '' == $this->restoreData['homeurl'] ) { // If no home then we set it equal to site URL.
			$settings['homeurl'] = $this->restoreData['siteurl'];
		}
		
		// Source type. Valid values: network, multisite_export (single site exported from multisite network), standalone
		if ( isset( $this->restoreData['dat'][ 'is_multisite' ] ) && ( ( $this->restoreData['dat'][ 'is_multisite' ] === true ) || ( $this->restoreData['dat'][ 'is_multisite' ] === 'true' ) ) ) {
			$this->sourceType = 'multisite_network';
			$this->destinationType = 'multisite_network';
		} elseif ( isset( $this->restoreData['dat'][ 'is_multisite_export' ] ) && ( ( $this->restoreData['dat'][ 'is_multisite_export' ] === true ) || ( $this->restoreData['dat'][ 'is_multisite_export' ] === 'true' ) ) ) {
			$this->sourceType = 'multisite_export';
		} else {
			$this->sourceType = 'standalone';
		}
		
		pb_backupbuddy::status( 'details', 'Migration type: `' . $this->sourceType . '` to `' . $this->destinationType . '`.' );
		pb_backupbuddy::status( 'details', 'Destination Site URL: ' . $this->restoreData['siteurl'] );
		pb_backupbuddy::status( 'details', 'Destination Home URL: ' . $this->restoreData['homeurl'] );
		
	} // End __construct().
	
	
	
	function migrate() {
		if ( is_array( $this->restoreData['databaseSettings']['migrateResumeSteps'] ) ) { // Resuming so use existing steps list.
			$steps = &$this->restoreData['databaseSettings']['migrateResumeSteps'];
		} else { // Not resuming so build steps list.
			// Migrate anything common to all types.
			$steps = array( 'migrateCommon' );
			// Migrate Network -> Network.
			if ( ( $this->sourceType == 'multisite_network' ) && ( $this->destinationType == 'multisite_network' ) ) {
				$steps[] = 'migrateNetworkToNetwork';
			}
			// Standalone -> Multisite Import.
			if ( ( $this->sourceType == 'standalone' ) && ( $this->destinationType == 'multisite_import' ) ) {
				$steps[] = 'migrateStandaloneToMultisiteImport';
			}
			// Multisite Export -> Multisite Import.
			if ( ( $this->sourceType == 'multisite_export' ) && ( $this->destinationType == 'multisite_import' ) ) {
				$steps[] = 'migrateMultisiteImportToMultisiteExport';
			}
			// Multisite Export -> Standalone.
			if ( ( $this->sourceType == 'multisite_export' ) && ( $this->destinationType == 'standalone' ) ) {
				$steps[] = 'migrateMultisiteExportToStandalone';
			}
			$steps[] = '_calculateNewOldURLs';
			$steps[] = 'bruteForceTables';
			$steps[] = 'finalize';
			$steps[] = 'verifyDatabase';
		}
		pb_backupbuddy::status( 'details', 'Steps to run: `' . implode( ',', $steps ) . '`.' );
		
		
		foreach( $steps as $step ) {
			$this->restoreData['databaseSettings']['migrateResumePoint'] = ''; // Clear out needing to resume for now.
			
			// Run the function.
			pb_backupbuddy::status( 'details', 'Starting step `' . $step . '`.' );
			$results = call_user_func( array( $this, $step ), $this->restoreData['databaseSettings']['migrateResumePoint'] );
			pb_backupbuddy::status( 'details', 'Finished step `' . $step . '`.' );
			
			if ( TRUE === $results ) { // Success so move to next loop.
				array_shift( $steps ); // Shifts step off the front of the array.
				
				pb_backupbuddy::status( 'details', 'Database migration step `' . $step . '` finished successfully.' );

				if ( $this->nearTimeLimit() ) {
					return array( $steps, '', '' ); // array of remaining steps, no resume point since not within a function.
				}

				// Do nothing... will just continue to next step.
			} elseif( is_array( $results ) ) { // NEEDS CHUNKING.
				$steps = array_unshift( $steps, $step ); // This step did not finish so put it back at the beginning for resuming.
				pb_backupbuddy::status( 'details', 'Migrating the database did not complete in the first passs. Chunking into multiple parts. Resuming step `' . $step . '` shortly at point `' . $results[0] . '`.' );
				return array( $steps, $results[0] ); // Array of steps to run, resume point.
			} else { // FALSE or something weird...
				pb_backupbuddy::status( 'error', 'Database migration step `' . $step . '` failed. See log for details. Result: `' . $results . '`.' );
				return FALSE;
			}
		} // end foreach.
		
		
		
		
		
		pb_backupbuddy::status( 'message', 'Took ' . round( microtime( true ) - pb_backupbuddy::$start_time, 3 ) . ' seconds. Done.' );
		pb_backupbuddy::status( 'message', 'Database content migrated.' );
		
		return true;
	} // End function __construct().
	
	
	
	function nearTimeLimit() {
		// If we are within 1 second of reaching maximum PHP runtime then stop here so that it can be picked up in another PHP process...
		if ( ( ( microtime( true ) - $this->startTime ) + self::TIME_WIGGLE_ROOM ) >= $this->restoreData['maxExecutionTime'] ) {
			pb_backupbuddy::status( 'message', 'Approaching limit of available PHP chunking time of `' . $this->restoreData['maxExecutionTime'] . '` sec. Ran for ' . round( microtime( true ) - $this->startTime, 3 ) . ' sec.' );
			return true;
		} else {
			return false;
		}
	}
	
	
	
	function _calculateNewOldURLs() {
		// ********** BEGIN MAKING OLD URLS UNIQUE AND TRIMMING CORRESPONDING NEW URLS **********

		// This entire section is in place to prevent duplicate replacements.
		$unique_urls = $this->_array_pairs_unique_first( $this->oldURLs, $this->newURLs );
		$this->oldURLs = $unique_urls[0];
		$this->newURLs = $unique_urls[1];

		$unique_urls = $this->_array_pairs_unique_first( $this->oldFullReplace, $this->newFullReplace );
		$this->oldFullReplace = $unique_urls[0];
		$this->newFullReplace = $unique_urls[1];

		pb_backupbuddy::status( 'details', 'Old URLs: ' . implode( ', ', $this->oldURLs ) );
		pb_backupbuddy::status( 'details', 'New URLs: ' . implode( ', ', $this->newURLs ) );
		pb_backupbuddy::status( 'details', 'Old full replace: ' . implode( ', ', $this->oldFullReplace ) );
		pb_backupbuddy::status( 'details', 'New full replace: ' . implode( ', ', $this->newFullReplace ) );

		return true;

		// ********** END MAKING OLD URLS UNIQUE AND TRIMMING CORRESPONDING NEW URLS **********
	}
	
	
	
	function migrateCommon() {
		pb_backupbuddy::status( 'details', 'Starting migration steps for `all` sites.' );

		// ABSPATH
		$old_abspath = $this->restoreData['dat']['abspath'];
		//$old_abspath = preg_replace( '|/+$|', '', $old_abspath ); // Remove escaping of windows paths. This is wrong. strips trailing slashes. Why? It shouldnt! Removed Nov 4
		$new_abspath = ABSPATH;
		pb_backupbuddy::status( 'details', 'ABSPATH change for database. Old Path: ' . $old_abspath . ', New Path: ' . $new_abspath . '.' );



		$old_url = $this->restoreData['dat']['siteurl'];  // the value you want to search for	

		// SITEURL
		if ( stristr( $old_url, 'http://www.' ) || stristr( $old_url, 'https://www.' ) ) { // If http://www.blah.... then also we will replace http://blah... and vice versa.
			$old_url_alt = str_ireplace( 'http://www.', 'http://', $old_url );
			$old_url_alt = str_ireplace( 'https://www.', 'https://', $old_url_alt );
		} else {
			$old_url_alt = str_ireplace( 'http://', 'http://www.', $old_url );
			$old_url_alt = str_ireplace( 'https://', 'https://www.', $old_url_alt );
		}
		
		pb_backupbuddy::status( 'details', 'Calculated site URL update. Previous URL: `' . $old_url . '`, New URL: `' . $this->restoreData['siteurl'] . '`.' );
		$this->oldFullReplace = array( $old_url, $old_url_alt );
		if ( '/' != $old_abspath ) { // Only do replace on abspath if it was not previously at the root as this easily breaks things.
			$this->oldFullReplace[] = $old_abspath;
		} else {
			pb_backupbuddy::status( 'warning', 'WARNING: Skipping ABSPATH database migrations as the previous ABSPATH was in the root directory. Cannot safely update paths at this location do it only being a single slash.' );
		}
		$this->newFullReplace = array( $this->restoreData['siteurl'], $this->restoreData['siteurl'] );
		if ( '/' != $old_abspath ) { // Only do replace on abspath if it was not previously at the root as this easily breaks things.
			$this->newFullReplace[] = $new_abspath;
		}

		// HOMEURL.
		if ( $this->restoreData['homeurl'] != $this->restoreData['dat']['siteurl'] ) { // Old and new homeurl differ so needs updating.

			if ( empty( $this->restoreData['dat']['homeurl'] ) ) { // old BackupBuddy versions did not store the previous homeurl. Hang onto this for backwards compatibility for a while.
				pb_backupbuddy::status( 'error', 'Your current backup does not include a home URL. Home URLs will NOT be updated; site URL will be updated though.  Make a new backup with the latest BackupBuddy before migrating if you wish to fully update home URL configuration.' );
			} else {
				$this->oldURLs = array( $old_url, $old_url_alt, $this->restoreData['dat']['homeurl'] );
				$this->newURLs = array( $this->restoreData['siteurl'], $this->restoreData['siteurl'], $this->restoreData['homeurl'] );

				$this->oldFullReplace[] = $this->restoreData['dat']['homeurl'];
				$this->newFullReplace[] = $this->restoreData['dat']['homeurl'];

				pb_backupbuddy::status( 'details', 'Calculated home URL update. Previous URL: `' . $this->restoreData['dat']['homeurl'] . '`, New URL: `' . $this->restoreData['homeurl'] . '`' );
			}
		} else { // Site URL updates only.
			$this->oldURLs = array( $old_url, $old_url_alt );
			$this->newURLs = array( $this->restoreData['siteurl'], $this->restoreData['siteurl'] );
		}

		if ( isset( $wp_upload_dir ) ) {
			$this->networkUploadURLReal = $this->restoreData['siteurl'] . '/' . str_replace( ABSPATH, '', $wp_upload_dir );
			pb_backupbuddy::status( 'details', '$this->networkUploadURLReal = `' . $this->networkUploadURLReal . '`.' );
		}
		
		
		$this->bruteforceExcludedTables = array(
			$this->overridePrefix . 'posts',
			$this->overridePrefix . 'users', // Imported users table will temporarily be here so this is fine for MS imports.
			$this->overridePrefix . 'usermeta', // Imported users table will temporarily be here so this is fine for MS imports.
			$this->overridePrefix . 'terms',
			$this->overridePrefix . 'term_taxonomy',
			$this->overridePrefix . 'term_relationships',
			$this->overridePrefix . 'postmeta',
			$this->overridePrefix . 'options',
			$this->overridePrefix . 'comments',
			$this->overridePrefix . 'commentmeta',
			$this->overridePrefix . 'links',
		);

		pb_backupbuddy::status( 'details', 'Finished migration steps for `all` sites.' );
		
		return true;
	} // End migrateCommon().
	
	
	
	function migrateNetworkToNetwork() {
		global $wpdb;

		pb_backupbuddy::status( 'details', 'Starting migration steps for `Network -> Network` sites.' );

		// Multisite Network domain & path from site url:
		$url_parts = parse_url( $this->restoreData['siteurl'] );
		$multisite_destination_domain =  $url_parts['host'];
		if ( isset( $url_parts['path'] ) ) {
			$destination_path = rtrim( $url_parts['path'], '/\\' ) . '/';
		} else {
			$destination_path = '/';
		}
		
		//pb_update_domain_path( $this->restoreData['dat']['domain'], $multisite_destination_domain, $this->restoreData['dat']['path'], $destination_path ); // $old_domain, $multisite_destination_domain, $old_path, $destination_path
		$old_domain = $this->restoreData['dat']['domain'];
		$old_path = $this->restoreData['dat']['path'];

		pb_backupbuddy::status( 'details', 'Multisite Network URLs: Old domain: `' . $old_domain . '`; new domain: `' . $multisite_destination_domain . '`; old path: `' . $old_path . '`; new path: `' . $destination_path . '`.' );


		// BLOGS TABLE-----

		// Update blog path for all sites that had the old domain and started with the old path in BLOGS table.
		if ( $old_path != '/' ) { // Used to be a subdomain so we can more safely replace.
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "blogs` SET path=REPLACE( path, '" . backupbuddy_core::dbEscape( $old_path ) . "', '" . backupbuddy_core::dbEscape( $destination_path ) . "') WHERE domain='" . backupbuddy_core::dbEscape( $old_domain ) . "' AND path LIKE '" . backupbuddy_core::dbEscape( $old_path ) . "%'" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating paths in blogs table to `' . backupbuddy_core::dbEscape( $destination_path ) . '` (old path was a subdirectory).' );
		} else { // Used to be in root so much prepend new path.
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "blogs` SET path=concat( '" . backupbuddy_core::dbEscape( rtrim( $destination_path, '/\\' ) ) . "', path ) WHERE domain='" . backupbuddy_core::dbEscape( $old_domain ) . "'" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating paths in blogs table to `' . backupbuddy_core::dbEscape( $destination_path ) . '` (old path was root).' );
		}
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }

		// Update blog domain for all matching sites.
		$wpdb->query( "UPDATE `" . $this->overridePrefix . "blogs` SET domain='" . backupbuddy_core::dbEscape( $multisite_destination_domain ) . "' WHERE domain='" . backupbuddy_core::dbEscape( $old_domain ) . "'" );
		pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating domain in blogs table to `' . backupbuddy_core::dbEscape( $multisite_destination_domain ) . '`.' );
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }


		// SITE TABLE-----

		// Update blog path for all matching sites in SITE table.
		if ( $old_path != '/' ) { // Used to be a subdomain so we can more safely replace.
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "site` SET path=REPLACE( path, '" . backupbuddy_core::dbEscape( $old_path ) . "', '" . backupbuddy_core::dbEscape( $destination_path ) . "') WHERE domain='" . backupbuddy_core::dbEscape( $old_domain ) . "' AND path LIKE '" . backupbuddy_core::dbEscape( $old_path ) . "%'" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating paths in site table to `' . backupbuddy_core::dbEscape( $destination_path ) . '` (old path was a subdirectory).' );
		} else { // Used to be in root so much prepend new path.
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "site` SET path=concat( '" . backupbuddy_core::dbEscape( rtrim( $destination_path, '/\\' ) ) . "', path ) WHERE domain='" . backupbuddy_core::dbEscape( $old_domain ) . "'" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating paths in site table to `' . backupbuddy_core::dbEscape( $destination_path ) . '` (old path was root).' );
		}
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }

		// Update blog domain for all matching sites.
		$wpdb->query( "UPDATE `" . $this->overridePrefix . "site` SET domain='" . backupbuddy_core::dbEscape( $multisite_destination_domain ) . "' WHERE domain='" . backupbuddy_core::dbEscape( $old_domain ) . "'" );
		pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating domain in site table to `' . backupbuddy_core::dbEscape( $multisite_destination_domain ) . '`.' );
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }

		pb_backupbuddy::status( 'details', 'Finished migration steps for `Network -> Network` sites.' );
		return true;
	} // End migrateNetworkToNetwork().
	
	
	
	function migrateStandaloneToMultisiteImport() {
		
		global $wpdb;
		global $wp_version;
		pb_backupbuddy::status( 'details', 'Starting migration steps for `Standalone -> Multisite Import` sites.' );
		
		// Note for any destination of multisite_import: Users tables exist temporarily in their normal location so we replace them like a normal standalone site. The next import step will merge them into the multisite tables.
		
		// TODO: add code from ms_importbuddy.php into here for any updates if needed.
		
		// The old uploads URL. Standalone source like: http://getbackupbuddy.com/wp-content/uploads/. BB doesnt currently support moved uploads. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'Old uploads URL: ' . $this->restoreData['dat']['siteurl'] . '/wp-content/uploads' );
		array_unshift( $this->oldURLs, $this->restoreData['dat']['siteurl'] . '/wp-content/uploads' );
		array_unshift( $this->oldFullReplace, $this->restoreData['dat']['siteurl'] . '/wp-content/uploads' );
		
		// The new standalone upload URL. Ex: http://pluginbuddy.com/wp-content/uploads/. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'New virtual upload URL to replace standalone upload URL: ' . $this->restoreData['upload_url'] );
		array_unshift( $this->newURLs, $this->restoreData['upload_url'] );
		array_unshift( $this->newFullReplace, $this->restoreData['upload_url'] );
		
		// Update upload_path in options table.
		if ( version_compare( $wp_version, '3.5', '>=') ) { // As of WP v3.5 substies should have upload_path option removed.
			$wpdb->query( "DELETE FROM `" . $this->overridePrefix . "options` WHERE option_name='upload_path' LIMIT 1" );
			pb_backupbuddy::status( 'details', 'Deleted ' . $wpdb->rows_affected . ' row(s) as upload_path is no longer needed by Multisite.' );
		} else {
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='" . backupbuddy_core::dbEscape( str_replace( $this->restoreData['siteurl'] . '/', '', $this->networkUploadURLReal ) ) . "' WHERE option_name='upload_path' LIMIT 1" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affacted . ' row(s) while updating uploads URL in options table. New value: ' . str_replace( $this->restoreData['siteurl'] . '/', '', $this->networkUploadURLReal ) );
		}
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		
		// Update user roles option_name row.
		$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_name='" . $this->overridePrefix . "user_roles' WHERE option_name LIKE '%\_user\_roles' LIMIT 1" );
		pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating user roles option_name to `' . $this->overridePrefix . 'user_roles`.' );
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		
		// Update fileupload_url in options table.
		$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='" . backupbuddy_core::dbEscape( $this->restoreData['upload_url'] ) . "' WHERE option_name='fileupload_url' LIMIT 1" );
		pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating fileupload_url in options table. New value: `' . $this->restoreData['upload_url'] . '`.' );
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		
		pb_backupbuddy::status( 'details', 'Finished migration steps for `Standalone -> Multisite Import` sites.' );

		return true;
	} // End migrateStandaloneToMultisiteImport().
	
	
	
	function migrateMultisiteExportToMultisiteImport() {
		
		global $wpdb, $wp_version;
		pb_backupbuddy::status( 'details', 'Starting migration steps for `Multisite Export -> Multisite Import` sites.' );
		
		// NOTE for any destination of multisite_import: Users tables exist temporarily in their normal location so we replace them like a normal standalone site. The next import step will merge them into the multisite tables.
		
		// The old virtual uploads URL. Standalone source like: http://getbackupbuddy.com/wp-content/uploads/. BB doesnt currently support moved uploads. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'Old virtual uploads URL: ' . $this->restoreData['dat']['upload_url'] );
		array_unshift( $this->oldURLs, $this->restoreData['dat']['upload_url'] );
		array_unshift( $this->oldFullReplace, $this->restoreData['dat']['upload_url'] );
		
		// The new virtual upload URL. Ex: http://pluginbuddy.com/wp-content/uploads/. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'New virtual upload URL to replace old virtual uploads URL: ' . $this->restoreData['upload_url'] );
		array_unshift( $this->newURLs, $this->restoreData['upload_url'] );
		array_unshift( $this->newFullReplace, $this->restoreData['upload_url'] );
		
		// The old real direct uploads URL. Standalone source like: http://getbackupbuddy.com/wp-content/uploads/. BB doesnt currently support moved uploads. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'Old real direct uploads URL: ' . $this->restoreData['dat']['upload_url_rewrite'] );
		array_unshift( $this->oldURLs, $this->restoreData['dat']['upload_url_rewrite'] );
		array_unshift( $this->oldFullReplace, $this->restoreData['dat']['upload_url_rewrite'] );
		
		// The new real direct upload URL. Ex: http://pluginbuddy.com/wp-content/uploads/. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'New real direct upload URL to replace old virtual uploads URL: ' . $this->networkUploadURLReal );
		array_unshift( $this->newURLs, $this->networkUploadURLReal );
		array_unshift( $this->newFullReplace, $this->networkUploadURLReal );
		
		// Update upload_path in options table.
		if ( version_compare( $wp_version, '3.5', '>=') ) { // As of WP v3.5 substies should have upload_path option removed.
			$wpdb->query( "DELETE FROM `" . $this->overridePrefix . "options` WHERE option_name='upload_path' LIMIT 1" );
			pb_backupbuddy::status( 'details', 'Deleted ' . $wpdb->rows_affected . ' row(s) as upload_path is no longer needed by Multisite.' );
			if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		} else {
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='" . backupbuddy_core::dbEscape( str_replace( $this->restoreData['siteurl'] . '/', '', $this->networkUploadURLReal ) ) . "' WHERE option_name='upload_path' LIMIT 1" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating upload_path in options table. New value: ' . str_replace( $this->restoreData['siteurl'] . '/', '', $this->networkUploadURLReal ) );
			if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		}
		
		// Update fileupload_url in options table.
		$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='" . backupbuddy_core::dbEscape( $this->restoreData['upload_url'] ) . "' WHERE option_name='fileupload_url' LIMIT 1" );
		pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating fileupload_url in options table. New value: `' . $this->restoreData['upload_url'] . '`.' );
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		
		pb_backupbuddy::status( 'details', 'Finished migration steps for `Multisite Export -> Multisite Import` sites.' );
		return true;
	} // End migrateMultisiteExportToMultisiteImport().
	
	
	
	// ********** BEGIN MULTISITE EXPORT -> STANDALONE  **********
	function migrateMultisiteExportToStandalone() {
		
		global $wpdb;
		pb_backupbuddy::status( 'details', 'Starting migration steps for `Multisite Export -> Standalone` sites.' );
		
		// IMPORTANT: Upload URLs _MUST_ be updated before doing a full URL replacement or else the first portion of the URL will be migrated so these will no longer match. array_unshift() is used to bump these to the top of the list to update.
		// These will handle both the REAL url http://.../wp-content/blogs.dir/##/files/ that the virtual path (http://..../wp-content/uploads/).
		
		// The old virtual upload URL. Ex: http://getbackupbuddy.com/files/. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'Old virtual upload URL: ' . $this->restoreData['dat'][ 'upload_url' ] );
		array_unshift( $this->oldURLs, $this->restoreData['dat'][ 'upload_url' ] );
		array_unshift( $this->oldFullReplace, $this->restoreData['dat'][ 'upload_url' ] );
		
		// The new standalone upload URL. Ex: http://pluginbuddy.com/wp-content/uploads/. Unshifted to place these replacements FIRST in the array of URLs to replace.
		pb_backupbuddy::status( 'details', 'New upload URL to replace virtual upload URL: ' . $this->restoreData['siteurl'] . '/wp-content/uploads/' );
		array_unshift( $this->newURLs, $this->restoreData['siteurl'] . '/wp-content/uploads/' );
		array_unshift( $this->newFullReplace, $this->restoreData['siteurl'] . '/wp-content/uploads/' );
		
		// Only update another URL if it differs -- usually will. They will be the same if the virtual url doesn't exist for some reason (no htaccess availability so the virtual url would match the real url)
		if ( $this->restoreData['dat'][ 'upload_url' ] != $this->restoreData['dat'][ 'upload_url_rewrite' ] ) {
			// The old virtual upload URL. Ex: http://getbackupbuddy.com/files/. Unshifted to place these replacements FIRST in the array of URLs to replace.
			pb_backupbuddy::status( 'details', 'Old real upload URL: ' . $this->restoreData['dat'][ 'upload_url_rewrite' ] );
			array_unshift( $this->oldURLs, $this->restoreData['dat'][ 'upload_url_rewrite' ] ); // The old real upload URL.
			array_unshift( $this->oldFullReplace, $this->restoreData['dat'][ 'upload_url_rewrite' ] );
			
			// The new standalone upload URL. Ex: http://pluginbuddy.com/wp-content/uploads/. Unshifted to place these replacements FIRST in the array of URLs to replace.
			pb_backupbuddy::status( 'details', 'New upload URL to replace real upload URL: ' . $this->restoreData['siteurl'] . '/wp-content/uploads/' );
			array_unshift( $this->newURLs, $this->restoreData['siteurl'] . '/wp-content/uploads/' ); // The new standalone upload URL.
			array_unshift( $this->newFullReplace, $this->restoreData['siteurl'] . '/wp-content/uploads/' ); // The new standalone upload URL.
		}
		
		// Update upload_path in options table to be default blank value.
		$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='' WHERE option_name='upload_path' LIMIT 1" );
		pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating uploads path in options table. New value: `` (blank default).' );
		if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
		
		pb_backupbuddy::status( 'details', 'Finished migration steps for `Multisite Export -> Standalone` sites.' );
		return true;
	} // End migrateMultisiteExportToStandalone().
	// ********** END MULTISITE EXPORT -> STANDALONE **********
	
	
	
	function bruteforceTables() {
		
		global $wpdb;
		// Loop through the tables matching this prefix. Does NOT change data in other tables.
		// This changes actual data on a column by column basis for very row in every table.
		$tables = array();
		$rows = $wpdb->get_results( "SELECT table_name FROM information_schema.tables WHERE table_name LIKE '" . str_replace( '_', '\_', $this->overridePrefix ) . "%' AND table_schema = DATABASE()", ARRAY_A );
		foreach( $rows as $row ) {
			$tables[] = $row['table_name'];
		}
		pb_backupbuddy::status( 'message', 'Found ' . count( $rows ) . ' WordPress tables. ' );
		unset( $rows );
		$bruteforce_tables = pb_backupbuddy::array_remove( $tables, $this->bruteforceExcludedTables ); // Removes all tables listed in $excluded_tables from $tables.
		unset( $tables );
		
		
		if ( $this->destinationType == 'multisite_import' ) {
			require_once( pb_backupbuddy::plugin_path() . '/lib/dbreplace/dbreplace.php' );
		} else {
			require_once( 'importbuddy/lib/dbreplace/dbreplace.php' );
		}
		
		// Instantiate db replace class.
		$dbreplace = new pluginbuddy_dbreplace( $this->startTime, self::TIME_WIGGLE_ROOM, $this->restoreData['maxExecutionTime'] );


		if ( is_array( $this->restoreData['databaseSettings']['migrateResumePoint'] ) ) {
			$steps = $this->restoreData['databaseSettings']['migrateResumePoint'][0];
			$stepResumePoint = $this->restoreData['databaseSettings']['migrateResumePoint'][1];
		} else {
			$stepResumePoint = '';
			
			$steps = array(
				array(
					'Posts table site URLs.',
					'text',
					$this->overridePrefix . 'posts',
					$this->oldURLs,
					$this->newURLs,
					array( 'post_content', 'post_excerpt', 'post_content_filtered' ),
				),
				array(
					'WordPress core database text data.',
					'text',
					$this->overridePrefix . 'users',
					$this->oldURLs,
					$this->newURLs,
					array( 'user_url' )
				),
				array(
					'WordPress core database text data.',
					'text',
					$this->overridePrefix . 'comments',
					$this->oldURLs,
					$this->newURLs,
					array( 'comment_content', 'comment_author_url' )
				),
				array(
					'WordPress core database text data.',
					'text',
					$this->overridePrefix . 'links',
					$this->oldURLs,
					$this->newURLs,
					array( 'link_url', 'link_image', 'link_target', 'link_description', 'link_notes', 'link_rss' ),
				),
				array(
					'WordPress core database serialized data.',
					'serialized',
					$this->overridePrefix . 'options',
					$this->oldFullReplace,
					$this->newFullReplace,
					array( 'option_value' )
				),
				array(
					'WordPress core database serialized data.',
					'serialized',
					$this->networkPrefix . 'usermeta',
					$this->oldFullReplace,
					$this->newFullReplace,
					array( 'meta_value' ),
				),
				array(
					'WordPress core database serialized data.',
					'serialized',
					$this->overridePrefix . 'postmeta',
					$this->oldFullReplace,
					$this->newFullReplace,
					array( 'meta_value' ),
				),
				array(
					'WordPress core database serialized data.',
					'serialized',
					$this->overridePrefix . 'commentmeta',
					$this->oldFullReplace,
					$this->newFullReplace,
					array( 'meta_value' ),
				),
				
			);
			
			if ( ! isset( $this->restoreData['databaseSettings']['migrateDatabaseBruteForce'] ) || ( true !== $this->restoreData['databaseSettings']['migrateDatabaseBruteForce'] ) ) { // skip bruteforce.
				pb_backupbuddy::status( 'details', 'Brute force database migration skipped based on advanced settings' );
			} else { // dont skip bruteforce.
				
				foreach ( $bruteforce_tables as $bruteforce_table ) {
					$steps[] = array(
						'Bruteforcing entire tables: `' . implode( ',', $bruteforce_tables ) .'`.',
						'bruteforce_table',
						$bruteforce_table,
						$this->oldFullReplace,
						$this->newFullReplace
					);
				}
			}
		}
		
		
		foreach( $steps as $step ) {
			$this->restoreData['databaseSettings']['migrateResumePoint'][1] = ''; // Clear out needing to resume this substep for now.

			// Run the function.
			pb_backupbuddy::status( 'details', 'Starting substep `' . $step[0] . '`.' );
			if ( 'bruteforce_table' == $step[1] ) { // Table bruteforce has different param count.
				$results = call_user_func( array( $dbreplace, $step[1] ), $step[2], $step[3], $step[4], $stepResumePoint );
			} else {
				$results = call_user_func( array( $dbreplace, $step[1] ), $step[2], $step[3], $step[4], $step[5], $stepResumePoint );
			}
			pb_backupbuddy::status( 'details', 'Finished substep `' . $step[0] . '`.' );

			if ( TRUE === $results ) { // Success so move to next loop.
				array_shift( $steps ); // Shifts step off the front of the array.
				// Do nothing... will just continue to next step.
				pb_backupbuddy::status( 'details', 'Database migration substep `' . $step[0] . '` finished successfully.' );

				if ( $this->nearTimeLimit() ) {
					return array( $steps, '' ); // array of remaining steps, no resume point since not within a function.
				}
			} elseif( is_array( $results ) ) { // NEEDS CHUNKING.
				$steps = array_unshift( $steps, $step ); // This step did not finish so put it back at the beginning for resuming.
				pb_backupbuddy::status( 'details', 'Substep migrating the database did not complete in the first pass. Chunking into multiple parts. Resuming substep `' . $step . '` shortly at point `' . $results[0] . '`.' );
				return array( $steps, $results[0] ); // Array of steps to run, resume point.
			} else { // FALSE or something weird...
				pb_backupbuddy::status( 'error', 'Database migration substep `' . $step[0] . '` failed. See log for details. This may only be a non-fatal warning.' );
				return FALSE;
			}
		} // end foreach.
		
		
		// Update table prefixes in some WordPress meta data. $this->networkPrefix is set to the normal prefix in non-ms environment.
		$old_prefix = backupbuddy_core::dbEscape( $this->restoreData['dat']['db_prefix'] );
		$finalPrefix = backupbuddy_core::dbEscape( $this->finalPrefix );
		pb_backupbuddy::status( 'details', 'Old DB prefix: `' . $old_prefix . '`; Override prefix: `' . $this->overridePrefix . '`. New final DB prefix (override does not apply): `' . $finalPrefix . '`. Network prefix: `' . $this->networkPrefix . '`' );
		if ($old_prefix != $finalPrefix ) {
			$wpdb->query( "UPDATE `". $this->overridePrefix ."usermeta` SET meta_key = REPLACE(meta_key, '" . $old_prefix . "', '" . $finalPrefix ."' );" ); // usermeta table temporarily is in the new subsite's prefix until next step.
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating meta_key\'s for DB prefix in site\'s [subsite; temporary if multisite] usermeta table from `' . $old_prefix . '` to `' . $finalPrefix . '`.' );
			if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }

			$wpdb->query( "UPDATE `" . $this->overridePrefix ."options` SET option_name = '" . $finalPrefix . "user_roles' WHERE option_name ='" . $old_prefix . "user_roles' LIMIT 1" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating option_name user_roles DB prefix in [subsite if multisite] options table to `' . backupbuddy_core::dbEscape( $finalPrefix ) . '`.' );
			if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }

			pb_backupbuddy::status( 'message', 'Updated prefix META data.' );
		}

		pb_backupbuddy::status( 'message', 'Migrated ' . count( $bruteforce_tables ) . ' tables via brute force.' );
		return true;

	} // End bruteforceTables().



	function finalize() {
		// LASTLY UPDATE SITE/HOME URLS to prevent double replacement; just in case!
		global $wpdb;
		
		if ( ! isset( $this->restoreData['dat']['tables_sizes'][ $this->restoreData['dat']['db_prefix'] . 'options' ] ) ) {
			pb_backupbuddy::status( 'details', 'Options table was not backed up. Skipping finalizing database URLs for _options table.' );
		} else {
			// Update SITEURL in options table. Usually mass replacement will cover this but set these here just in case.
			$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='" . backupbuddy_core::dbEscape( $this->restoreData['siteurl'] ) . "' WHERE option_name='siteurl' LIMIT 1" );
			pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating Site URL in options table `' . $this->overridePrefix . 'options` to `' . $this->restoreData['siteurl'] . '`.' );
			if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }

			// Update HOME URL in options table. Usually mass replacement will cover this but set these here just in case.
			if ( $this->restoreData['homeurl'] != '' ) {
				$wpdb->query( "UPDATE `" . $this->overridePrefix . "options` SET option_value='" . backupbuddy_core::dbEscape( $this->restoreData['homeurl'] ) . "' WHERE option_name='home' LIMIT 1" );
				pb_backupbuddy::status( 'details', 'Modified ' . $wpdb->rows_affected . ' row(s) while updating Home URL in options table to `' . $this->restoreData['homeurl'] . '`.' );
				if ( ! empty( $wpdb->last_error ) ) { pb_backupbuddy::status( 'error', 'mysql error: ' . $wpdb->last_error ); }
			}
		}
		
		return true;
	}



	/*	verify_database()
	 *	
	 *	Verify various contents of the database after all migration is complete.
	 *	
	 *	@param		
	 *	@return		
	 */
	function verifyDatabase() {
		global $wpdb;

		//pb_backupbuddy::$classes['import']->connect_database();
		$db_prefix = $this->overridePrefix;
		
		// If wp_options was not backed up then skip this function since it checks that.
		if ( ! isset( $this->restoreData['dat']['tables_sizes'][ $this->restoreData['dat']['db_prefix'] . 'options' ] ) ) {
			pb_backupbuddy::status( 'details', 'Options table was not backed up. Skipping verifying the database.' );
			return true;
		} else {
			pb_backupbuddy::status( 'details', 'Options table was backed up. Verifying the database.' );
		}
		
		// Check site URL.
		$result = $wpdb->get_var( "SELECT option_value FROM `{$db_prefix}options` WHERE option_name='siteurl' LIMIT 1" );
		if ( $result === false ) {
			pb_backupbuddy::status( 'error', 'Unable to retrieve siteurl from database. A portion of the database may not have imported (or with the wrong prefix).' );
		} else {
			pb_backupbuddy::status( 'details', 'Final site URL: `' . $result . '`.' );
			@$wpdb->flush(); // Free memory.
		}

		// Check home URL.
		$result = $wpdb->get_var( "SELECT option_value FROM `{$db_prefix}options` WHERE option_name='home' LIMIT 1" );
		if ( $result === false ) {
			pb_backupbuddy::status( 'error', 'Unable to retrieve home [url] from database. A portion of the database may not have imported (or with the wrong prefix).' );
		} else {
			pb_backupbuddy::status( 'details', 'Final home URL: `' . $result . '`.' );
		}
		@$wpdb->flush(); // Free memory.

		// Verify media upload path.
		$result = $wpdb->get_var( "SELECT option_value FROM `{$db_prefix}options` WHERE option_name='upload_path' LIMIT 1" );
		if ( $result === false ) {
			pb_backupbuddy::status( 'error', 'Unable to retrieve upload_path from database table ' . "`{$db_prefix}options`" . '. A portion of the database may not have imported (or with the wrong prefix).' );
			$media_upload_path = '{ERR_34834984-UNKNOWN}';
		} else {
			$media_upload_path = $result;
		}
		@$wpdb->flush(); // Free memory.

		pb_backupbuddy::status( 'details', 'Media upload path in database options table: `' . $media_upload_path . '`.' );
		if ( substr( $media_upload_path, 0, 1 ) == '/' ) { // Absolute path.
			if ( ! file_exists( $media_upload_path ) ) { // Media path does not exist.
				$media_upload_message = 'Your media upload path is assigned a directory which does not appear to exist on this server. Please verify it is correct in your WordPress settings. Current path: `' . $media_upload_path . '`.';
				pb_backupbuddy::alert( $media_upload_message );
				pb_backupbuddy::status( 'warning', $media_upload_message );
			} else { // Media path does exist.
				pb_backupbuddy::status( 'details', 'Your media upload path is assigned an absolute path which appears to be correct.' );
			}
		} else { // Relative path.
			pb_backupbuddy::status( 'details', 'Your media upload path is assigned a relative path; validity not tested.' );
		}
		
		return true;

	} // End verifyDatabase().
	
	
	
	/*	_array_pairs_unique_first()
	 *	
	 *	Takes two arrays. Looks for any duplicate values in the first array. That item is removed. The corresponding item in the second array is removed also.
	 *	Resets indexes as a courtesy while maintaining order.
	 *	
	 *	@param		array		$a		First array to make unique.
	 *	@param		array		$b		Second array that has items removed that were in the same position as the removed duplicates found in $a.
	 *	@return		
	 */
	function _array_pairs_unique_first( $a, $b ) {
		$a_uniques = array_unique( $a ); // Get unique values in $a. Keys are maintained.
	
		$result = array();
		$result[0] = $a_uniques;
		$result[1] = array_intersect_key( $b, $a_uniques ); // Get the part of the $b array that is missing from $a.
	
		$result[0] = array_merge( $result[0] );
		$result[1] = array_merge( $result[1] );
		return $result;
	} // End _array_pairs_unique_first().



} // end class backupbuddy_migrateDB.
