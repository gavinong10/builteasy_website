<?php

class pb_backupbuddy_dashboard extends pb_backupbuddy_dashboardcore {


	/*	stats()
	 *	
	 *	Displays (echos out) an overview of stats into the WordPress Dashboard.
	 *	
	 *	@return		null
	 */
	function stats() {

		$getOverview = backupbuddy_api::getOverview();
		
		if ( is_network_admin() ) {
			$backup_url = network_admin_url( 'admin.php' );
		} else {
			$backup_url = admin_url( 'admin.php' );
		}
		$backup_url .= '?page=pb_backupbuddy_backup';
		
		
		// Red-Green status for editsSinceLastBackup
		if ( $getOverview['editsSinceLastBackup'] == 0 )
			$status = 'green';
		else
			$status = 'red';
		

		// Format file archiveSize to readable format
		if ( isset( $getOverview['lastBackupStats']['archiveSize'] ) && ( is_numeric( $getOverview['lastBackupStats']['archiveSize'] ) ) ) {
			$file_size = $getOverview['lastBackupStats']['archiveSize'];

			if ( $file_size >= 1073741824 )
				$archiveSize = round( $file_size / 1024 / 1024 / 1024 , 2 ) . ' GB';

			elseif ( $file_size >= 1048576 )
				$archiveSize = round( $file_size / 1024 / 1024 , 1 ) . ' MB';

			elseif( $file_size >= 1024 )
				$archiveSize = round( $file_size / 1024 , 0 ) . ' KB';

			else
				$archiveSize = $file_size . ' bytes';
		} else {
			$archiveSize = 'Unknown';
		}

		// Format timestamp
		if ( isset( $getOverview['lastBackupStats']['finish'] ) ) {
			$time = pb_backupbuddy::$format->localize_time( $getOverview['lastBackupStats']['finish'] );
			$time_nice = date("M j - g:i A", $time);
		} else {
			$time_nice = 'Unknown';
		}
		
		// Format Type
		if ( isset( $getOverview['lastBackupStats']['type'] ) ) {
			if ( $getOverview['lastBackupStats']['type'] == 'full' )
				$backup_type = 'Full';
			elseif ( $getOverview['lastBackupStats']['type'] == 'db' )
				$backup_type = 'Database';
			else
				$backup_type = $getOverview['lastBackupStats']['type'];
		} else {
			$backup_type = 'Unknown';
		}
		
		// Build widget markup
		ob_start();
		?>

		<div class="edits-since-wrapper">
			<p class="edits-since <?php echo $status; ?>">
				<?php echo $getOverview['editsSinceLastBackup']; ?>
			</p>
			<h4 class="number-heading">Edits since<br>last Backup</h4>
		</div>
		
		<?php if ( isset( $getOverview['lastBackupStats']['finish'] ) ) { // only show if a last backup exists. ?>
			<div class="info-group">
				<h3>Latest Backup</h3>
				<ul class="backup-list">
					<li>
						<div class="list-wrapper">
							<div class="list-title">
								<?php if ( isset( $getOverview['lastBackupStats']['archiveFile'] ) && file_exists( backupbuddy_core::getBackupDirectory() . $getOverview['lastBackupStats']['archiveFile'] ) ) { ?>
									<a href="<?php if ( isset( $getOverview['lastBackupStats']['archiveURL'] ) ) { echo $getOverview['lastBackupStats']['archiveURL']; } ?>"><?php _e( 'Download', 'it-l10n-backupbuddy' ); ?></a>
								<?php } else { ?>
									<i>Stored offsite or deleted</i>
								<?php } ?>
							</div>
							<div class="list-description">
								<div class="backup-type description-item">
									<span>Type</span><br>
									<?php echo $backup_type; ?>
								</div>
								<div class="backup-size description-item">
									<span>Size</span><br>
									<?php echo $archiveSize; ?>
								</div>
								<div class="backup-time description-item">
									<span>Time</span><br>
									<?php echo $time_nice; ?>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		<?php } ?>

		<div class="backup-now">
			<a href="<?php echo $backup_url; ?>"><?php _e( 'Backup Now', 'it-l10n-backupbuddy' ); ?></a>
		</div>

		<?php
		ob_end_flush();
	}


}
?>