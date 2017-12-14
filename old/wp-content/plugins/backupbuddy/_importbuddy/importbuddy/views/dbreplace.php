<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.

$page_title = 'Database Text Replace Tool';
require_once( '_header.php' );
?>

<div class="wrap">
<?php
$configFile = '';
if ( ! file_exists( ABSPATH . 'wp-config.php' ) ) { // Normal config file not found so warn or see if parent config may exist.
	$parentConfigMessage = '';
	$parentConfig =  dirname( ABSPATH ) . '/wp-config.php';
	if ( @file_exists( $parentConfig ) ) { // Parent config exists so offer it as an option or possibly use it if user has selected to do so.
		if ( pb_backupbuddy::_GET( 'parent_config' ) == 'true' ) { // User opted to use parent config.
			$configFile = $parentConfig;
		} else { // User has not opted to use parent config yet so set message to offer it.
			$parentConfigMessage = '<br><br><b>However</b>, a wp-config.php file was found in the parent directory as `' . $parentConfig . '`. <a href="?page=dbreplace&parent_config=true"><b>Click here</b></a> if you would like to run this tool using this wp-config.php file in the parent directory.';
		}
	}
	if ( '' == $configFile ) {
		pb_backupbuddy::alert( '<b>Error:</b> This tool requires an existing WordPress installation to perform database replacements on. No WordPress wp-config.php configuration file was found in the same directory as importbuddy.php. ' . $parentConfigMessage . ' <br><br> <b>Note:</b> ImportBuddy automatically handles migrating & replacing your site URLs and file paths during restore/migration; this tool is not needed for normal backup / restore operations.', true );
	}
} else { // Use normal config file.
	$configFile = ABSPATH . 'wp-config.php';
}

if ( '' != $configFile ) {
	
	// Read in wp-config.php file contents.
	$configContents = file_get_contents( $configFile );
	if ( false === $configContents ) {
		pb_backupbuddy::alert( 'Error: Unable to read wp-config.php configuration file.' );
		return;
	}
	
	// Grab database settings from wp-config.php contents.
	preg_match( '/define\([\s]*(\'|")DB_NAME(\'|"),[\s]*(\'|")(.*)(\'|")[\s]*\);/i', $configContents, $matches );
	$databaseSettings['name'] = $matches[4];
	preg_match( '/define\([\s]*(\'|")DB_USER(\'|"),[\s]*(\'|")(.*)(\'|")[\s]*\);/i', $configContents, $matches );
	$databaseSettings['username'] = $matches[4];
	preg_match( '/define\([\s]*(\'|")DB_PASSWORD(\'|"),[\s]*(\'|")(.*)(\'|")[\s]*\);/i', $configContents, $matches );
	$databaseSettings['password'] = $matches[4];
	preg_match( '/define\([\s]*(\'|")DB_HOST(\'|"),[\s]*(\'|")(.*)(\'|")[\s]*\);/i', $configContents, $matches );
	$databaseSettings['host'] = $matches[4];
	preg_match( '/\$table_prefix[\s]*=[\s]*(\'|")(.*)(\'|");/i', $configContents, $matches );
	$databaseSettings['prefix'] = $matches[2];
	
	//print_r( $databaseSettings );
	
	// Connect to database.
	global $wpdb;
	$wpdb = new wpdb( $databaseSettings['username'], $databaseSettings['password'], $databaseSettings['name'], $databaseSettings['host'] );
	if ( false === $wpdb->dbh ) {
		pb_backupbuddy::alert( 'Error #858383: Unable to connect to database using settings in wp-config.php. Verify connection settings.' );
	} else {
		require_once( '_dbreplace.php' );
	}
}
?>
</div>

<?php
require_once( '_footer.php' );