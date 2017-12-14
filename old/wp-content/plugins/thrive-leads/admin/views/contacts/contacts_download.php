<?php
$most_recent = 0;
foreach ( $download_list as $download ) {
	$date = strtotime( $download->date );
	if ( $date > $most_recent ) {
		$most_recent = $date;
	}
}
?>

<ul class="tvd-collapsible tve-collapsible-with-icon">
	<li>
		<div class="tvd-collapsible-header">
			<div class="tvd-row">
				<div class="tvd-col tvd-s12">
					<h5><?php echo __( 'Download Manager', 'thrive-leads' ); ?></h5>
				</div>
			</div>
		</div>
		<div class="tvd-collapsible-body">
			<div class="tve-manager-content">
				<div class="tvd-v-spacer vs-2"></div>
				<?php if ( ! empty( $upload['error'] ) ): ?>
					<div class="tve-error">
						<?php echo __( 'You are unable to save files in the upload folder. Please check the folder <a href="https://codex.wordpress.org/Changing_File_Permissions">permissions</a>!', 'thrive-leads' ); ?>
					</div>
				<?php else: ?>
					<div class="tvd-row tve-download-row">
						<div class="tvd-col tvd-s3">
							<div class="tvd-input-field tvd-margin-top-xsmall">
								<select class="tve-manager-source" name="tve-manager-source" autocomplete="off" id="tve-manager-source">
									<option value="all"><?php echo __( 'All Contacts in Database', 'thrive-leads' ); ?></option>
									<?php if ( ! empty( $most_recent ) ): ?>
										<option value="last_download"><?php echo __( 'All Contacts in Database since last Download ', 'thrive-leads' ); ?><?php echo date( "jS F, Y H:i", $most_recent ); ?></option>
									<?php endif; ?>
									<?php if ( ! empty( $contacts_list->items ) ): ?>
										<option value="current_report"><?php echo __( 'All Contacts in Current Report', 'thrive-leads' ); ?></option>
									<?php endif; ?>
								</select>
								<label for="tve-manager-source"><?php echo __( 'I want to download', 'thrive-leads' ); ?></label>
							</div>
						</div>
						<div class="tvd-col tvd-s3">
							<div class="tve-manager-file-type">
								<div class="tvd-input-field tvd-margin-top-xsmall">
									<select class="tve-manager-type" name="tve-manager-type" id="tve-manager-type">
										<option value="excel"><?php echo __( 'Excel', 'thrive-leads' ); ?> (.xls)</option>
										<option value="csv"><?php echo __( 'Comma-Separated Values', 'thrive-leads' ); ?> (.csv)</option>
									</select>
									<label for="tve-manager-type"><?php echo __( 'As file', 'thrive-leads' ); ?></label>
								</div>
							</div>
						</div>
						<div class="tvd-col tvd-s6">
							<a class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tve-manager-download-button"><?php echo __( 'Start Download', 'thrive-leads' ); ?></a>
						</div>
					</div>
					<div class="tvd-row">
						<div class="tvd-col tvd-s12">
							<h4><?php echo __( 'Download Archive', 'thrive-leads' ); ?></h4>
							<ul class="tvd-collection tvd-with-header tve-downloads-table">
								<li class="tvd-collection-header">
									<div class="tvd-row">
										<div class="tvd-col tvd-s3">
											<h5><?php echo __( 'Report Type', 'thrive-leads' ); ?></h5>
										</div>
										<div class="tvd-col tvd-s3">
											<h5><?php echo __( 'Date', 'thrive-leads' ); ?></h5>
										</div>
										<div class="tvd-col tvd-s3">
											<h5><?php echo __( 'Status', 'thrive-leads' ); ?></h5>
										</div>
										<div class="tvd-col tvd-s3">
											<h5>&nbsp;</h5>
										</div>
									</div>
								</li>
								<?php foreach ( $download_list as $item ): ?>
									<li class="tvd-collection-item">
										<div class="tvd-row">
											<div class="tvd-col tvd-s3">
                                                <span class="tvd-vertical-align">
                                                    <?php echo $item->type ?>
                                                </span>
											</div>
											<div class="tvd-col tvd-s3">
                                                <span class="tvd-vertical-align">
                                                    <?php echo date( "jS F, Y H:i", strtotime( $item->date ) ); ?>
                                                </span>
											</div>
											<div class="tvd-col tvd-s3">
                                                <span class="tvd-vertical-align">
                                                    <?php echo $item->status_title; ?>
                                                </span>
											</div>
											<div class="tvd-col tvd-s3">
												<div class="tvd-right">
													<?php if ( $item->status == 'complete' ): ?>
														<a href="<?php echo $item->download_link; ?>" class="tvd-btn-icon tvd-btn-icon-green tvd-no-load tvd-tooltipped" data-tooltip="Download Report" data-position="top">
															<span class="tvd-icon-cloud-download"></span>
															<span class="tvd-on-large-and-down"><?php echo __( 'Download', 'thrive-leads' ); ?></span>
														</a>
													<?php endif; ?>
													<?php if ( $item->status == 'complete' || $item->status == 'error' ): ?>
														<a data-id="<?php echo $item->id; ?>" class="tvd-btn-icon tvd-btn-icon-red tve-delete-download tvd-pointer tvd-tooltipped" data-tooltip="Delete Report" data-position="top">
															<span class="tvd-icon-delete2"></span>
															<span class="tvd-on-large-and-down"><?php echo __( 'Delete', 'thrive-leads' ); ?></span>
														</a>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<div style="display: none">
						<img style="width: 200px;" class="tve-pending-spinner" src="<?php echo includes_url(); ?>js/thickbox/loadingAnimation.gif">
					</div>
				<?php endif; ?>
			</div>
		</div>
	</li>
</ul>

<div id="tve-leads-delete-contact" class="tvd-modal tvd-red">
	<div class="tvd-modal-content">
		<a href="javascript:void(0)" class=" tvd-modal-action tvd-modal-close tvd-modal-close-x">
			<i class="tvd-icon-close2"></i>
		</a>
		<h3 class="tvd-modal-title tvd-center-align">
			<?php echo __( 'Are you sure you want to remove this contact?', 'thrive-leads' ); ?>
		</h3>
	</div>
	<div class="tvd-modal-footer">
		<div class="tvd-row">
			<div class="tvd-col tvd-s12 tvd-m6">
				<a href="javascript:void(0)" class="tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-light tvd-waves-effect tvd-modal-close">
					<?php echo __( 'Cancel', 'thrive-leads' ); ?>
				</a>
			</div>
			<div class="tvd-col tvd-s12 tvd-m6">
				<a href="" class="tvd-waves-effect tvd-waves-light tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-light tve-modal-delete-contact tvd-right">
					<?php echo __( 'Yes', 'thrive-leads' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>



