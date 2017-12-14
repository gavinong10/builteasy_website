<?php
class Auth {
	
	const MAX_LOGIN_ATTEMPTS_ALLOWED = 4; // Maximum number of invalid login attempts before locking importbuddy.
	const RESET_DEFAULTS_ON_INVALID_LOGIN = false; // Whether or not reset all settings/options back to defaults on login failure.
	const COOKIE_EXPIRATION = 86400; // Number of seconds an importbuddy cookie is valid for.
	private static $_authenticated = false; // Whether user is validly authenticated or not.
	private static $_checked = false; // Whether check() has been run yet.
	
	
	
	/* check()
	 *
	 * Check authentication based on form data submitted. This only needs to be run once. Use is_authenticated() to determine auth status.
	 *
	 * @param	force_check		bool	If true then will re-check even if check() has already been run this session.
	 *
	 */
	public static function check( $force_check = false ) {
		
		if ( ( true === self::$_checked ) && ( $force_check === false ) ) { // Skip checking if already skipped unless forcing.
			return self::is_authenticated();
		}
		
		$login_attempt_file = ABSPATH . 'importbuddy/_login_attempts.php';
		$login_attempts = 1;
		if ( file_exists( $login_attempt_file ) ) {
			$login_attempts = @file_get_contents( $login_attempt_file );
		}
		if ( false !== $login_attempts ) {
			$login_attempts = trim( str_replace( '<?php die(); ?>', '', $login_attempts ) );
			if ( $login_attempts > self::MAX_LOGIN_ATTEMPTS_ALLOWED ) {
				die( 'Access Denied. Maximum login attempts exceeded. You must delete the file "_login_attempts.php" in the importbuddy directory on your server to unlock this ImportBuddy to allow it to continue.' );
			}
		}
		
		$actual_pass_hash = PB_PASSWORD;
		if ( ( '#PASSWORD#' == $actual_pass_hash ) || ( '' == $actual_pass_hash ) ) { die( 'Error #84578459745. A password must be set.' ); }
		
		if ( pb_backupbuddy::_POST( 'password' ) != '' ) {
			$supplied_pass_hash = md5( pb_backupbuddy::_POST( 'password' ) );
		} elseif ( pb_backupbuddy::_GET( 'password' ) != '' ) {
			$supplied_pass_hash = md5( pb_backupbuddy::_GET( 'password' ) );
		} else {
			if ( pb_backupbuddy::_GET( 'v' ) != '' ) { // Hash submitted by magic migration.
				$supplied_pass_hash = pb_backupbuddy::_GET( 'v' );
			} else { // Normal form submitted hash.
				if ( pb_backupbuddy::_POST( 'pass_hash' ) != '' ) {
					$supplied_pass_hash = pb_backupbuddy::_POST( 'pass_hash' );
				} elseif ( pb_backupbuddy::_POST( 'pb_backupbuddy_pass_hash' ) != '' ) {
					$supplied_pass_hash = pb_backupbuddy::_POST( 'pb_backupbuddy_pass_hash' );
				} else {
					$supplied_pass_hash = '';
				}
			}
		}
		
		if ( $supplied_pass_hash == $actual_pass_hash ) {
			self::$_authenticated = true;
			setcookie( 'importbuddy_login', md5( PB_PASSWORD . 'badgers' ), ( time()+ self::COOKIE_EXPIRATION ) );
		} elseif ( isset( $_COOKIE['importbuddy_login'] ) && ( $_COOKIE['importbuddy_login'] != '' ) && ( $_COOKIE['importbuddy_login'] == md5( PB_PASSWORD . 'badgers' ) ) ) {
			self::$_authenticated = true;
			setcookie( 'importbuddy_login', md5( PB_PASSWORD . 'badgers' ), ( time() + self::COOKIE_EXPIRATION ) );
		} else { // Incorrect hash. Reset settings & track attempts.
			if ( '' != $supplied_pass_hash ) { // Dont count blank hash as an attempt.
				if ( true === self::RESET_DEFAULTS_ON_INVALID_LOGIN ) {
					pb_backupbuddy::reset_defaults();
				}
				if ( false !== $login_attempts ) {
					global $pb_login_attempts;
					$pb_login_attempts = $login_attempts;
					@file_put_contents( $login_attempt_file, '<?php die(); ?>' . ( $login_attempts + 1 ) );
				}
			}
		}
		
		self::$_checked = true;
		
		return self::$_authenticated;
		
	} // End check().
	
	
	
	/* is_authenticated()
	 *
	 * Determine whether user is fully authenticated or not.
	 *
	 * @return		bool		True if fully authenticated, otherwise false.
	 *
	 */
	public static function is_authenticated() {
		
		return self::$_authenticated;
		
	} // End authenticated().
	
	
	
	/* require_authentication()
	 *
	 * Requires valid authentication to allow proceeding. die() if not logged in.
	 *
	 */
	public static function require_authentication() {
		
		// Check if previously authed already this session.
		if ( true === self::is_authenticated() ) {
			return true;
		}
		
		self::check();
		if ( true === self::is_authenticated() ) {
			return true;
		} else {
			die( 'Access Denied. You must log in first. Please return to the <a href="importbuddy.php">importbuddy.php homepage</a> authenticate.' );
		}
		
	} // End require_authorization();
	
} // End class.


