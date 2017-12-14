<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.

$file = ABSPATH . pb_backupbuddy::_POST( 'file' );

if ( '' == $file ) {
	die( 'No file passed.' );
}
if ( ! file_exists( $file ) ) {
	die( 'File not found.' );
}

$fileHash = @md5_file( $file );

if ( false === $fileHash ) {
	die( 'Unable to calculate hash. Verify file permissions.' );
} else {
	die( json_encode( array( 'hash' => $fileHash ) ) );
}