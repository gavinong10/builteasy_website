<?php
// Authored by Skyler Moore.

if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

pb_backupbuddy::$ui->title( 'FTP' );

// FTP connection information
$ftp_server = $destination['address'];
$ftp_username = $destination['username'];
$ftp_password = $destination['password'];
$ftp_directory = (string)$destination['path'];
$ftps = $destination['ftps'];
if ( !empty( $ftp_directory ) ) {
	$ftp_directory = $ftp_directory . '/';
}
if ( isset( $destination['active_mode'] ) && ( $destination['active_mode'] == '0' ) ) {
	$active = false;
} else {
	$active = true;
}

$port = '21';
if ( strstr( $ftp_server, ':' ) ) {
	$server_params = explode( ':', $ftp_server );
	$ftp_server = $server_params[0];
	$port = $server_params[1];
}

// Delete ftp backups
if ( !empty( $_POST['delete_file'] ) ) {
	
	pb_backupbuddy::verify_nonce();
	
	$delete_count = 0;
	if ( !empty( $_POST['files'] ) && is_array( $_POST['files'] ) ) {
		
		
		// Connect to server.
		if ( $ftps == '1' ) { // Connect with FTPs.
			if ( function_exists( 'ftp_ssl_connect' ) ) {
				$conn_id = ftp_ssl_connect( $ftp_server, $port );
				if ( $conn_id === false ) {
					pb_backupbuddy::status( 'details',  'Unable to connect to FTPS  (check address/FTPS support).', 'error' );
					return false;
				} else {
					pb_backupbuddy::status( 'details',  'Connected to FTPs.' );
				}
			} else {
				pb_backupbuddy::status( 'details',  'Your web server doesnt support FTPS in PHP.', 'error' );
				return false;
			}
		} else { // Connect with FTP (normal).
			if ( function_exists( 'ftp_connect' ) ) {
				$conn_id = ftp_connect( $ftp_server, $port );
				if ( $conn_id === false ) {
					pb_backupbuddy::status( 'details',  'ERROR: Unable to connect to FTP (check address).', 'error' );
					return false;
				} else {
					pb_backupbuddy::status( 'details',  'Connected to FTP.' );
				}
			} else {
				pb_backupbuddy::status( 'details',  'Your web server doesnt support FTP in PHP.', 'error' );
				return false;
			}
		}
		
		
		// login with username and password
		$login_result = ftp_login( $conn_id, $ftp_username, $ftp_password );
		
		if ( $active === true ) {
			// do nothing, active is default.
			pb_backupbuddy::status( 'details', 'Active FTP mode based on settings.' );
		} elseif ( $active === false ) {
			// Turn passive mode on.
			pb_backupbuddy::status( 'details', 'Passive FTP mode based on settings.' );
			ftp_pasv( $conn_id, true );
		} else {
			pb_backupbuddy::status( 'error', 'Unknown FTP active/passive mode: `' . $active . '`.' );
		}
		
		ftp_chdir( $conn_id, (string)$ftp_directory );
		
		// loop through and delete ftp backup files
		foreach ( $_POST['files'] as $backup ) {
			// try to delete backup
			if ( ftp_delete( $conn_id,  $backup ) ) {
				$delete_count++;
			}
		}
	
		// close this connection
		ftp_close( $conn_id );
	}
	if ( $delete_count > 0 ) {
		pb_backupbuddy::alert( sprintf( _n( 'Deleted %d file.', 'Deleted %d files.', $delete_count, 'it-l10n-backupbuddy' ), $delete_count ) );
	} else {
		pb_backupbuddy::alert( __('No backups were deleted.', 'it-l10n-backupbuddy' ) );
	}
	echo '<br>';
}

// Copy ftp backups to the local backup files
if ( !empty( $_GET['cpy_file'] ) ) {
	pb_backupbuddy::alert( 'The remote file is now being copied to your local backups. If the backup gets marked as bad during copying, please wait a bit then click the `Refresh` icon to rescan after the transfer is complete.' );
	echo '<br>';
	pb_backupbuddy::status( 'details',  'Scheduling Cron for creating ftp copy.' );
	backupbuddy_core::schedule_single_event( time(), 'process_ftp_copy', array( $_GET['cpy_file'], $ftp_server, $ftp_username, $ftp_password, $ftp_directory, $port, $ftps ) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
}


// Connect to server
if ( $ftps == '1' ) { // Connect with FTPs.
	if ( function_exists( 'ftp_ssl_connect' ) ) {
		$conn_id = ftp_ssl_connect( $ftp_server, $port );
		if ( $conn_id === false ) {
			pb_backupbuddy::status( 'details',  'Unable to connect to FTPS  `' . $ftp_server . '` on port `' . $port . '` (check address/FTPS support and that server can connect to this address via this port).', 'error' );
			return false;
		} else {
			pb_backupbuddy::status( 'details',  'Connected to FTPs.' );
		}
	} else {
		pb_backupbuddy::status( 'details',  'Your web server doesnt support FTPS in PHP.', 'error' );
		return false;
	}
} else { // Connect with FTP (normal).
	if ( function_exists( 'ftp_connect' ) ) {
		$conn_id = ftp_connect( $ftp_server, $port );
		if ( $conn_id === false ) {
			pb_backupbuddy::status( 'details',  'ERROR: Unable to connect to FTP server `' . $ftp_server . '` on port `' . $port . '` (check address and that server can connect to this address via this port).', 'error' );
			return false;
		} else {
			pb_backupbuddy::status( 'details',  'Connected to FTP.' );
		}
	} else {
		pb_backupbuddy::status( 'details',  'Your web server doesnt support FTP in PHP.', 'error' );
		return false;
	}
}


// Login with username and password
$login_result = @ftp_login( $conn_id, $ftp_username, $ftp_password );
if ( false === $login_result ) {
	pb_backupbuddy::alert( 'Failure attempting to log in. PHP function ftp_login() returned false.' );
	die();
}

if ( $active === true ) {
	// do nothing, active is default.
	pb_backupbuddy::status( 'details', 'Active FTP mode based on settings.' );
} elseif ( $active === false ) {
	// Turn passive mode on.
	pb_backupbuddy::status( 'details', 'Passive FTP mode based on settings.' );
	ftp_pasv( $conn_id, true );
} else {
	pb_backupbuddy::status( 'error', 'Unknown FTP active/passive mode: `' . $active . '`.' );
}


// Get contents of the current directory
ftp_chdir( $conn_id, $ftp_directory );
$contents = ftp_nlist( $conn_id, '' );

// Create array of backups and sizes
$backups = array();
$got_modified = false;
foreach ( $contents as $backup ) {
	// check if file is backup
	$pos = strpos( $backup, 'backup-' );
	if ( $pos !== FALSE ) {
		$mod_time = ftp_mdtm( $conn_id, $ftp_directory . $backup );
		if ( $mod_time > -1 ) {
			$got_modified = true;
		}
		$backups[] = array(
			'file' => $backup,
			'size' => ftp_size( $conn_id, $ftp_directory . $backup ),
			'modified' => $mod_time,
		);
		
	}
}
	
// close this connection
ftp_close( $conn_id );


if ( $got_modified === true ) { // FTP server supports sorting by modified date.
	// Custom sort function for multidimension array usage.
	function backupbuddy_number_sort( $a,$b ) {
		return $a['modified']<$b['modified'];
	}
	// Sort by modified using custom sort function above.
	usort( $backups, 'backupbuddy_number_sort' );
}


$urlPrefix = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . htmlentities( pb_backupbuddy::_GET( 'destination_id' ) );


//echo '<h3>', __('Viewing', 'it-l10n-backupbuddy' ), ' `' . $destination['title'] . '` (' . $destination['type'] . ')</h3>';
?>
<div style="max-width: 950px;">
<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo $urlPrefix; ?>">
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="<?php _e('Delete from FTP', 'it-l10n-backupbuddy' );?>" class="button-secondary delete" />
		</div>
	</div>
	<table class="widefat">
		<thead>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<?php
					echo '<th>', __('Backup File', 'it-l10n-backupbuddy' ), '</th>',
						 '<th>', __('File Size',   'it-l10n-backupbuddy' ), '</th>',
						'<th>', __('Modified',   'it-l10n-backupbuddy' );
						if ( $got_modified === true ) {
							echo ' <img src="', pb_backupbuddy::plugin_url(), '/images/sort_down.png" style="vertical-align: 0px;" title="', __('Sorted by modified', 'it-l10n-backupbuddy' ), '" />';
						}
						echo '</th>',
						 '<th>', __('Actions',     'it-l10n-backupbuddy' ), '</th>';
				?>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<?php
					echo '<th>', __('Backup File', 'it-l10n-backupbuddy' ), '</th>',
						 '<th>', __('File Size',   'it-l10n-backupbuddy' ), '</th>',
						'<th>', __('Modified',   'it-l10n-backupbuddy' );
						if ( $got_modified === true ) {
							echo ' <img src="', pb_backupbuddy::plugin_url(), '/images/sort_down.png" style="vertical-align: 0px;" title="', __('Sorted by modified', 'it-l10n-backupbuddy' ), '" />';
						}
						echo '</th><th>', __('Actions',     'it-l10n-backupbuddy' ), '</th>';
				?>
			</tr>
		</tfoot>
		<tbody>
			<?php
			// List FTP backups
			if ( empty( $backups ) ) {
				echo '<tr><td colspan="5" style="text-align: center;"><i>', __('This directory does not have any backups.', 'it-l10n-backupbuddy' ), '</i></td></tr>';
			} else {
				$file_count = 0;
				foreach ( (array)$backups as $backup ) {
					$file_count++;
					?>
					<tr class="entry-row alternate">
						<th scope="row" class="check-column"><input type="checkbox" name="files[]" class="entries" value="<?php echo $backup['file']; ?>" /></th>
						<td>
							<?php
								echo $backup['file'];
							?>
						</td>
						<td style="white-space: nowrap;">
							<?php
							if ( $backup['modified'] > -1 ) {
								echo pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup['modified'] ) );
								echo '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $backup['modified'] ) . ' ', __('ago', 'it-l10n-backupbuddy' ), ')</span>';
							} else {
								echo '<span class="description">Unknown</span>';
							}
							?>
							
						</td>
						<td style="white-space: nowrap;">
							<?php echo pb_backupbuddy::$format->file_size( $backup['size'] ); ?>
						</td>
						<td>
							<?php echo '<a href="' . $urlPrefix . '&#38;cpy_file=' . $backup['file'] . '">Copy to local</a>'; ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="<?php _e('Delete from FTP', 'it-l10n-backupbuddy' );?>" class="button-secondary delete" />
		</div>
	</div>
	
	<?php pb_backupbuddy::nonce(); ?>
</form><br />
</div>
