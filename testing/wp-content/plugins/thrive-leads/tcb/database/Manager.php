<?php
if ( ! class_exists( 'Thrive_TCB_Database_Manager' ) ) {

	/**
	 * handles all database updates for the plugin
	 *
	 * Class Thrive_TCB_Database_Manager
	 */
	class Thrive_TCB_Database_Manager {
		/**
		 * @var string version as xx.xx
		 */
		protected static $CURRENT_DB_VERSION;

		/**
		 * Get the current version of database tables
		 * If there is no version saved 0.0 is returned
		 * @return mixed|string|void
		 */
		public static function dbVersion() {
			if ( empty( self::$CURRENT_DB_VERSION ) ) {
				// check for previous DB version installed
				self::$CURRENT_DB_VERSION = get_option( 'tve_tcb_db_version', '0.0' );
			}

			return self::$CURRENT_DB_VERSION;
		}

		/**
		 * Compare db version with code version
		 * Runs all the scrips of old db version until the current code version
		 */
		public static function check() {
			if ( version_compare( self::dbVersion(), TVE_TCB_DB_VERSION, '<' ) ) {

				$scripts = self::getScripts( self::dbVersion(), TVE_TCB_DB_VERSION );

				if ( ! empty( $scripts ) ) {
					define( 'TVE_TCB_DB_UPGRADE', true );
				}
				global $wpdb;
				$wpdb->suppress_errors();
				foreach ( $scripts as $filePath ) {
					require_once $filePath;
				}

				update_option( 'tve_tcb_db_version', TVE_TCB_DB_VERSION );
			}
		}

		/**
		 * get all DB update scripts from $fromVersion to $toVersion
		 *
		 * @param $fromVersion
		 * @param $toVersion
		 *
		 * @return array
		 */
		protected static function getScripts( $fromVersion, $toVersion ) {
			$scripts = array();
			$dir     = new DirectoryIterator( plugin_dir_path( __FILE__ ) . 'migrations/' );
			foreach ( $dir as $file ) {
				/**
				 * @var $file DirectoryIterator
				 */
				if ( $file->isDot() ) {
					continue;
				}
				$scriptVersion = self::getScriptVersion( $file->getFilename() );
				if ( empty( $scriptVersion ) ) {
					continue;
				}
				if ( version_compare( $scriptVersion, $fromVersion, '>' ) && version_compare( $scriptVersion, $toVersion, '<=' ) ) {
					$scripts[ $scriptVersion ] = $file->getPathname();
				}
			}

			/**
			 * sort the scripts in the correct version order
			 */
			uksort( $scripts, 'version_compare' );

			return $scripts;
		}

		/**
		 * Parse the scriptName and return the version
		 *
		 * @param string $scriptName in the following format {name}-{[\d+].[\d+]}.php
		 *
		 * @return string
		 */
		protected static function getScriptVersion( $scriptName ) {
			if ( ! preg_match( '/(.+?)-(\d+)\.(\d+)(.\d+)?\.php/', $scriptName, $m ) ) {
				return false;
			}

			return $m[2] . '.' . $m[3] . ( ! empty( $m[4] ) ? $m[4] : '' );
		}

	}
}