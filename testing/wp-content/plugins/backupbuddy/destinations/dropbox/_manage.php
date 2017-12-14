<?php
//pb_backupbuddy::$ui->title( 'Dropbox' );

if ( isset( $destination['disabled'] ) && ( '1' == $destination['disabled'] ) ) {
	die( __( 'This destination is currently disabled based on its settings. Re-enable it under its Advanced Settings.', 'it-l10n-backupbuddy' ) );
}

require_once( pb_backupbuddy::plugin_path() . '/destinations/dropbox/lib/dropbuddy/dropbuddy.php' );
$dropbuddy = new pb_backupbuddy_dropbuddy( $destination['token'] );
if ( $dropbuddy->authenticate() === true ) {
	$account_info = $dropbuddy->get_account_info();
} else {
	$account_info = false;
}

if ( !isset( $destination['directory'] ) ) {
	$destination['directory'] = '';
}
if ( !isset( $destination['title'] ) ) {
	$destination['title'] = '';
}

$destination['directory'] = '/' . ltrim( $destination['directory'], '/\\' );

$meta_data = $dropbuddy->get_meta_data( $destination['directory'] );


/*
echo '<pre>';
print_r( $meta_data ) );
echo '</pre>';
*/

// Delete dropbox backups
if ( !empty( $_POST['delete_file'] ) ) {
	pb_backupbuddy::verify_nonce();
	$delete_count = 0;
	if ( !empty( $_POST['files'] ) && is_array( $_POST['files'] ) ) {
		// loop through and delete dropbox files
		foreach ( $_POST['files'] as $dropboxfile ) {
			$delete_count++;
			// Delete dropbox file
			$dropbuddy->delete( $dropboxfile );
		}
	}
	if ( $delete_count > 0 ) {
		pb_backupbuddy::alert( sprintf( _n('Deleted %d file', 'Deleted %d files', $delete_count, 'it-l10n-backupbuddy' ), $delete_count) );
		$meta_data = $dropbuddy->get_meta_data( $destination['directory'] ); // Refresh listing.
	}
}


// Convert time string to timestamp.
if ( isset( $meta_data['contents'] ) && is_array( $meta_data['contents'] ) ) {
	foreach( $meta_data['contents'] as &$backup ) {
		$backup['modified'] = strtotime( $backup['modified'] );
	}
	// Custom sort function for multidimension array usage.
	function backupbuddy_number_sort( $a,$b ) {
		return $a['modified']<$b['modified'];
	}
	// Sort by modified using custom sort function above.
	usort( $meta_data['contents'], 'backupbuddy_number_sort' );
}

// Copy dropbox backups to the local backup files
if ( !empty( $_GET['cpy_file'] ) ) {
	pb_backupbuddy::alert( __( 'The remote file is now being copied to your local backups. If the backup gets marked as bad during copying, please wait a bit then click the `Refresh` icon to rescan after the transfer is complete.', 'it-l10n-backupbuddy' ) );
	pb_backupbuddy::status( 'details',  'Scheduling Cron for creating Dropbox copy.' );
	backupbuddy_core::schedule_single_event( time(), 'process_dropbox_copy', array( $_GET['destination_id'], $_GET['cpy_file'] ) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
}

//stecho '<h3>', __('Viewing', 'it-l10n-backupbuddy' ),' `' . $destination['title'] . '` (' . $destination['type'] . ')</h3>';
?>

<div>
<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo pb_backupbuddy::ajax_url( 'remoteClient' ) . '&custom=' . pb_backupbuddy::_GET('custom') . '&destination_id=' . pb_backupbuddy::_GET('destination_id');?>">
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="<?php _e('Delete from Dropbox', 'it-l10n-backupbuddy' );?>" class="button-secondary delete" />
		</div>
	</div>
	<table class="widefat">
		<thead>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<?php 
					echo '<th>', __('Backup File', 'it-l10n-backupbuddy' ), '</th>',
						 '<th>', __('Last Modified', 'it-l10n-backupbuddy' ), ' <img src="', pb_backupbuddy::plugin_url(), '/images/sort_down.png" style="vertical-align: 0px;" title="', __('Sorted by modified', 'it-l10n-backupbuddy' ), '" /></th>',
						 '<th>', __('File Size', 'it-l10n-backupbuddy' ), '</th>',
						 '<th>', __('Actions', 'it-l10n-backupbuddy' ), '</th>';
				?>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<?php
					echo '<th>', __('Backup File', 'it-l10n-backupbuddy' ), '</th>',
						 '<th>', __('Last Modified', 'it-l10n-backupbuddy' ),'<img src="', pb_backupbuddy::plugin_url(), '/images/sort_down.png" style="vertical-align: 0px;" title="', __('Sorted by modified', 'it-l10n-backupbuddy' ), '" /> </th>',
						 '<th>', __('File Size', 'it-l10n-backupbuddy' ), '</th>',
						 '<th>', __('Actions', 'it-l10n-backupbuddy' ), '</th>';
				?>
			</tr>
		</tfoot>
		<tbody>
			<?php
			// List dropbox backups
			if ( empty( $meta_data['contents'] ) ) {
				echo '<tr><td colspan="5" style="text-align: center;"><i>', __('You have not created any dropbox backups yet.', 'it-l10n-backupbuddy' ) ,' </i></td></tr>';
			} else {
				$file_count = 0;
				foreach ( (array) $meta_data['contents'] as $file ) {
					// check if file is backup
					if ( strstr( $file['path'], 'backup-' ) ) {
						$file_count++;
						?>
						<tr class="entry-row alternate">
							<th scope="row" class="check-column"><input type="checkbox" name="files[]" class="entries" value="<?php echo $file['path']; ?>" /></th>
							<td>
								<?php
									echo str_replace( '/' . $destination['directory'] . '/', '', $file['path'] );
								?>
							</td>
							<td style="white-space: nowrap;">
								<?php
									$modified = $file['modified'];
									echo pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $modified ) );
									echo '<br /><span class="description">(' . pb_backupbuddy::$format->time_ago( $modified ) . ' ', __('ago', 'it-l10n-backupbuddy' ), ')</span>';
								?>
							</td>
							<td style="white-space: nowrap;">
								<?php echo pb_backupbuddy::$format->file_size( $file['bytes'] ); ?>
							</td>
							<td>
								<?php echo '<a href="' . pb_backupbuddy::ajax_url( 'remoteClient' ) . '&custom=' . pb_backupbuddy::_GET('custom') . '&destination_id=' . pb_backupbuddy::_GET('destination_id') . '&#38;cpy_file=' . $file['path'] . '">',__('Copy to local', 'it-l10n-backupbuddy' ), '</a>'; ?>
							</td>
						</tr>
						<?php
					}
				}
			}
			?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="<?php _e('Delete from Dropbox', 'it-l10n-backupbuddy' );?>" class="button-secondary delete" />
		</div>
	</div>
	
	<?php pb_backupbuddy::nonce(); ?>
</form><br />
</div>
