<?php
/**
 * BackupBuddy ImportBuddy class for testing DB permissions
 *
 * @package BackupBuddy
 * @subpackage ImportBuddy
 * @since 6.4.0.13
 */

/**
 * DB Permission Tests
 *
 * @package BackupBuddy
 * @subpackage ImportBuddy
 * @since 6.4.0.13
 */
class importbuddy_test_db{

	/**
	 * DB Credentials passed via form
	 * @access private
	 * @var array
	 */
	private $creds = array();

	/**
	 * WordPress Database Class
	 *
	 * @since 6.4.0.13
	 * @access public
	 * @var object
	 */
	var $wpdb;

	/**
	 * @since 6.4.0.13
	 * @access public
	 * @var array
	 */
	var $tests = array();

	/**
	 * Class constructor sets up the environment
	 *
	 * @since 6.4.0.13
	 *
	 * @return void
	 */
	function __construct() {

		// Register the tests
		$this->register_tests();

		// Grab the DB credentials from POST
		$creds             = array();
		$creds['server']   = pb_backupbuddy::_POST( 'server' );
		$creds['username'] = pb_backupbuddy::_POST( 'username' );
		$creds['password'] = pb_backupbuddy::_POST( 'password' );
		$creds['database'] = pb_backupbuddy::_POST( 'database' );
		$creds['prefix']   = pb_backupbuddy::_POST( 'prefix' );

		// If we don't have all the fields, die with error
		if ( ( '' == $creds['server'] ) || ( '' == $creds['username'] ) || ( '' == $creds['database'] ) || ( '' == $creds['prefix'] ) ) {
			$this->tests['overall_error'] = 'One or more database settings was left blank. All fields except optional password are required.';
			die( json_encode( $this->tests ) );
		}

		$this->creds = $creds;

		// Run the tests, one at a time
		$this->run_tests();
	}

	/**
	 * Sets the array of tests that we will run with their default values
	 *
	 * @since 6.4.0.13
	 *
	 * @return void
	 */
	function register_tests() {

		// Tests variables to populate with results.
		$this->tests = array(
			'connect'               => false,   // Able to connect & login to db server?
			'connect_error'         => '',      // mysql error message in response to connect & login (if any).
			'selectdb'              => false,   // Able to select the database?
			'selectdb_error'        => '',      // mysql error message in response to selecting (if any).
			'createdroptable'       => false,   // ability to CREATE a new table (and delete it).
			'createdroptable_error' => '',      // create table mysql error (if any).
			'prefix'                => false,   // Whether or not prefix meets the bare minimum to be accepted.
			'prefix_exists'         => true,    // WordPress tables matching prefix found?
			'prefix_warn'           => true,    // Warn if prefix of a bad format.
			'overall_error'         => '',      // Overall error of the test. If missing fields then this will be what errors about missing field(s).
			'gta_testing' => 'true',
		);
	}

	/**
	 * Uses the tests array to call tests individually
	 *
	 * @since 6.4.0.13
	 *
	 * @return void
	 */
	function run_tests() {
		// Loop through array of tests, only calling ones where function exists
		foreach ( $this->tests as $test => $result ) {
			if ( is_callable( array( $this, 'test_' . $test ) ) && empty( $this->tests[$test] ) ) {
				call_user_func( array( $this, 'test_' . $test ) );
			}
		}

		die( json_encode( $this->tests ) );
	}

	/**
	 * Test for DB Connect and DB Select
	 *
	 * @since 6.4.0.13
	 *
	 * @return void
	 */
	 function test_connect() {
		require_once( ABSPATH . 'importbuddy/classes/wp-db.php' );
		global $wpdb;
		$this->wpdb = new wpdb( $this->creds['username'], $this->creds['password'], $this->creds['database'], $this->creds['server'] );
		if ( false === $this->wpdb->dbh ) {
			if ( empty( $this->wpdb->use_mysqli ) ) {
				$mysql_error = mysql_error();
			} else {
				$mysql_error = mysqli_error();
			}
			$this->tests['connect_error'] = 'Unable to connect to database server and/or select the database. Details: `' . $mysql_error . '`.';
			die( json_encode( $this->tests ) );
		}
		$this->tests['connect'] = true;
		$this->tests['selectdb'] = true;
	}

	/**
	 * Tests the ability to create and drop a table
	 *
	 * @since 6.4.0.13
	 *
	 * @return void
	 */
	function test_createdroptable() {
		// Make sure we've already connected. This should never run unless someone calls it manually again.
		if ( empty( $this->tests['connect'] ) ) {
			$this->test_connect();
		}

		// Escape prefix manually since we can't use $wpdb->prefix and $wpdb->prepare adds single quotes
		if ( empty( $this->wpdb->use_mysqli ) ) {
			$prefix = mysql_real_escape_string( $this->creds['prefix'] );
		} else {
			$prefix = mysqli_real_escape_string( $this->wpdb->dbh, $this->creds['prefix'] );
		}

		// Try to drop test table in event previous attempt failed. Not a part of the test. NOTE: This throws an error to the PHP error log if wpdb logging enabled unless errors are suppressed.
		$this->wpdb->suppress_errors( true ); // Hide errors if this test fails since we have logging on by default.
		$drop_test_table = 'DROP TABLE ' . $prefix . 'buddy_test';
		$this->wpdb->query( $drop_test_table );
		$this->wpdb->suppress_errors( false );

		// Attempt to create the test table
		$create_test_table = 'CREATE TABLE ' . $prefix . 'buddy_test (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);';
		if ( FALSE !== $this->wpdb->query( $create_test_table ) ) {
			// Drop temp test table we created before we declare success.
			if ( FALSE !== $this->wpdb->query( $drop_test_table ) ) {
				$this->tests['createdroptable'] = true;
			} else { // drop failed.
				if ( empty( $this->wpdb->use_mysqli ) ) {
					$mysql_errno = mysql_errno( $this->wpdb->dbh );
				} else {
					$mysql_errno = mysqli_errno( $this->wpdb->dbh );
				}
				$this->tests['createdroptable_error'] = 'Unable to delete temporary table. ' . $this->wpdb->last_error . ' - ErrorNo: `' . $mysql_errno . '`.';
			}
		}
	}

	/**
	 * Tests for existing tables with the same prefix
	 *
	 * @since 6.4.0.13
	 *
	 * @return void
	 */
	function test_prefix() {
		// Make sure we've already connected. This should never be true.
		if ( empty( $this->tests['connect'] ) ) {
			$this->test_connect();
		}

        // WordPress tables exist matching prefix?
        $prefix_exists_sql = $this->wpdb->prepare( "SHOW TABLES LIKE '%s';", str_replace( '_', '\_', $this->creds['prefix'] ) . "%" );
		$result            = $this->wpdb->get_results( $prefix_exists_sql );
		if ( empty( $result ) ) { // WordPress prefix does not exist
            $this->tests['prefix_exists'] = false;
        }

		// Make sure prefix meets wp standards
		if ( ! preg_match('|[^a-z0-9_]|i', $this->creds['prefix'] ) ) { // Prefix meets WP standards
			$this->tests['prefix'] = true;
			if ( preg_match('/^[a-z0-9]+_$/i', $this->creds['prefix'] ) ) { // Prefix passes with no warning.
				$this->tests['prefix_warn'] = false;
			} else {
				$this->tests['prefix_warn'] = true;
			}
		}

	}
}
