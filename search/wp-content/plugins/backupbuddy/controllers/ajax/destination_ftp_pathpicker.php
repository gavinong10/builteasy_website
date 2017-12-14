<?php
backupbuddy_core::verifyAjaxAccess();


// FTP destination path picker.
/* destination_ftp_pathpicker()
 *
 * description
 *
 */


function pb_backupbuddy_ftp_listDetailed($resource, $directory = '.') { 
	if (is_array($children = @ftp_rawlist($resource, $directory))) { 
	    $items = array(); 

	    foreach ($children as $child) { 
	        $chunks = preg_split("/\s+/", $child); 
	        list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks; 
	        $item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file'; 
	        array_splice($chunks, 0, 8); 
	        $items[implode(" ", $chunks)] = $item; 
	    } 

	    return $items; 
	} 
	return false;
} // end listDetailed subfunction.


$settings = array(
	'address'		=> pb_backupbuddy::_GET( 'pb_backupbuddy_address' ),
	'username'		=> pb_backupbuddy::_GET( 'pb_backupbuddy_username' ),
	'password'		=> pb_backupbuddy::_GET( 'pb_backupbuddy_password' ),
	'ftps'			=> pb_backupbuddy::_GET( 'pb_backupbuddy_ftps' ),
	'active_mode'	=> pb_backupbuddy::_GET( 'pb_backupbuddy_active_mode' ),
);

if ( ( $settings['address'] == '' ) || ( $settings['username'] == '' ) || ( $settings['password'] == '' ) ) {
	die( __('Missing required FTP server inputs.', 'it-l10n-backupbuddy' ) );
}

// Settings
if ( $settings['active_mode'] == '0' ) {
	$active_mode = false;
} else {
	$active_mode = true;
}
$server = $settings['address'];
$port = '21';
if ( strstr( $server, ':' ) ) {
	$server_params = explode( ':', $server );
	
	$server = $server_params[0];
	$port = $server_params[1];
}

// Connect.
if ( $settings['ftps'] == '0' ) {
	$conn_id = @ftp_connect( $server, $port, 10 ); // timeout of 10 seconds.
	if ( $conn_id === false ) {
		$error = __( 'Unable to connect to FTP address `' . $server . '` on port `' . $port . '`.', 'it-l10n-backupbuddy' );
		$error .= "\n" . __( 'Verify the server address and port (default 21). Verify your host allows outgoing FTP connections.', 'it-l10n-backupbuddy' );
		die( $error );
	}
} else {
	if ( function_exists( 'ftp_ssl_connect' ) ) {
		$conn_id = @ftp_ssl_connect( $server, $port );
		if ( $conn_id === false ) {
			die( __('Destination server does not support FTPS?', 'it-l10n-backupbuddy' ) );
		}
	} else {
		die( __('Your web server doesnt support FTPS.', 'it-l10n-backupbuddy' ) );
	}
}

// Authenticate.
$login_result = @ftp_login( $conn_id, $settings['username'], $settings['password'] );
if ( ( !$conn_id ) || ( !$login_result ) ) {
	pb_backupbuddy::status( 'details', 'FTP test: Invalid user/pass.' );
	$response = __('Unable to login to FTP server. Bad user/pass.', 'it-l10n-backupbuddy' );
	if ( $settings['ftps'] != '0' ) {
		$response .= "\n\nNote: You have FTPs enabled. You may get this error if your host does not support encryption at this address/port.";
	}
	die( $response );
}

pb_backupbuddy::status( 'details', 'FTP test: Success logging in.' );

// Handle active/pasive mode.
if ( $active_mode === true ) { // do nothing, active is default.
	pb_backupbuddy::status( 'details', 'Active FTP mode based on settings.' );
} elseif ( $active_mode === false ) { // Turn passive mode on.
	pb_backupbuddy::status( 'details', 'Passive FTP mode based on settings.' );
	ftp_pasv( $conn_id, true );
} else {
	pb_backupbuddy::status( 'error', 'Unknown FTP active/passive mode: `' . $active_mode . '`.' );
}

// Calculate root.
$ftpRoot = urldecode( pb_backupbuddy::_POST( 'dir' ) );
if ( '' == $ftpRoot ) { // No root passed so figure out root from FTP server itself.
	$ftpRoot = ftp_pwd( $conn_id );
}


$ftpList = pb_backupbuddy_ftp_listDetailed( $conn_id, $ftpRoot );


echo '<ul class="jqueryFileTree pb_backupbuddy_ftpdestination_pathpickerboxtree">';
if ( count( $ftpList ) > 2 ) {
	foreach( $ftpList as $fileName => $file ) {
		if ( ( '.' == $fileName ) || ( '..' == $fileName ) ) {
			continue;
		}
		if ( 'directory' == $file['type'] ) { // Directory.
			echo '<li class="directory collapsed">';
			$return = '';
			$return .= '<div class="pb_backupbuddy_treeselect_control">';
			$return .= '<img src="' . pb_backupbuddy::plugin_url() . '/images/greenplus.png" style="vertical-align: -3px;" title="Select this path..." class="pb_backupbuddy_filetree_select">';
			$return .= '</div>';
			echo '<a href="#" rel="' . htmlentities( $ftpRoot . $fileName ) . '/" title="Toggle expand...">' . htmlentities($fileName) . $return . '</a>';
			echo '</li>';
		} else { // File.
			echo '<li class="file collapsed">';
			echo '<a href="#" rel="' . htmlentities( $ftpRoot . $fileName ) . '">' . htmlentities($fileName) . '</a>';
			echo '</li>';
		}
	}
} else {
	echo '<ul class="jqueryFileTree">';
	echo '<li><a href="#" rel="' . htmlentities( pb_backupbuddy::_POST( 'dir' ) . 'NONE' ) . '"><i>Empty Directory ...</i></a></li>';
	echo '</ul>';
}
echo '</ul>';

die();