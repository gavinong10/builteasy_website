<div id="tve-content">
	<script type="text/javascript">
		var TVE_Page_Data = {
			globalSettings: <?php echo json_encode( $dashboard_data['global_settings'] ) ?>,
		};
		ThriveLeads.objects.AssetsCollection = new ThriveLeads.collections.Assets(<?php echo json_encode( $assets_data['assets'] ) ?>);
		ThriveLeads.objects.AssetsWizard = new ThriveLeads.models.AssetWizard(<?php echo json_encode( $assets_data['wizard'] ) ?>);
		ThriveLeads.objects.AssetConnection = new ThriveLeads.collections.AssetConnection(<?php echo json_encode( $assets_data['wizard']['structured_apis'] ) ?>);
		ThriveLeads.objects.Apis = new ThriveLeads.collections.AssetConnection(<?php echo json_encode( $assets_data['wizard']['apis'] ) ?>);
		ThriveLeads.objects.BreadcrumbsCollection = new ThriveLeads.collections.BreadcrumbsCollection(<?php echo json_encode( $dashboard_data['breadcrumbs'] ) ?>);
		ThriveLeads.objects.groups = new ThriveLeads.collections.Groups(<?php echo json_encode( $dashboard_data['groups'] ) ?>);
	</script>

	<div id="tve-asset-delivery">
		<div class="tve-header">
			<nav id="tl-nav">
				<div class="nav-wrapper">
					<div class="tve-logo tve_leads_clearfix tvd-left">
						<a href="<?php menu_page_url( 'thrive_leads_dashboard' ); ?>"
						   title="<?php echo __( 'Thrive Leads Home', 'thrive-leads' ) ?>">
							<?php echo '<img src="' . plugins_url( 'thrive-leads/admin/img' ) . '/tl-logo-full-white.png" > '; ?>
						</a>
					</div>
					<?php require_once( dirname( __FILE__ ) . '/leads_menu.php' ) ?>
				</div>
			</nav>
		</div>
		<div class="tve-leads-breadcrumbs-wrapper">
			<div class="tvd-row">
				<div class="tvd-col tvd-s8">
					<?php require_once( dirname( __FILE__ ) . '/leads_breadcrumbs.php' ) ?>
				</div>
				<div class="tvd-col tvd-s4">
					<div class="tve-asset-group-status tvd-right">
						<span class="tvd-left"><?php echo __('Status', 'thrive-leads'); ?> :</span>
						<span class="tvd-left">
						<?php if ($wizard == false && $assets_data['wizard']['proprieties']['connections'] == 0) { ?>
							<div class="tve-asset-group-connection-status tve-asset-group-connection-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Click here to set up the email delivery service.', 'thrive-leads'); ?>">
								<span class="tvd-icon-paper-plane"></span>
						<?php } elseif ($wizard == 1 && $assets_data['wizard']['proprieties']['connections'] == 0) { ?>
							<div class="tve-asset-group-connection-status-red tve-asset-group-connection-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Asset Delivery is currently NOT active because there is no connection to an email delivery service. Click the icon to add a downloadable.', 'thrive-leads'); ?>">
								<span class="tvd-icon-paper-plane"></span>
						<?php } else { ?>
							<div class="tve-asset-group-connection-status-green <?php if ($wizard == 1 && $assets_data['wizard']['proprieties']['connections'] == 1) { echo 'tve-asset-group-existing-connection-event'; } else { echo 'tve-asset-group-connection-event'; } ?> tve-asset-group-status-wrapper
							tvd-tooltipped" data-tooltip="<?php echo __('Email delivery status: OK. Click to change email delivery settings.', 'thrive-leads'); ?>">
								<span class="tvd-icon-paper-plane"></span>
						<?php } ?>
							</div>
						<?php if ($wizard == false && $assets_data['wizard']['proprieties']['template'] == 0) { ?>
							<div class="tve-asset-group-template-status tve-asset-group-template-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Click here to create your default email template.', 'thrive-leads'); ?>">
								<span class="tvd-icon-file-text"></span>
						<?php } elseif ($wizard == 1 && $assets_data['wizard']['proprieties']['template'] == 0) { ?>
							<div class="tve-asset-group-template-status-red tve-asset-group-template-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Asset Delivery is currently NOT active because no default email template exists. Click the icon to add a downloadable.', 'thrive-leads'); ?>">
								<span class="tvd-icon-file-text"></span>
						<?php } else { ?>
							<div class="tve-asset-group-template-status-green tve-asset-group-template-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Default template status: OK. Click to edit the default template.', 'thrive-leads'); ?>">
								<span class="tvd-icon-file-text"></span>
						<?php } ?>
							</div>
						<?php if ($wizard == false && $assets_data['wizard']['proprieties']['files'] == 0) { ?>
							<div class="tve-asset-group-links-status tve-asset-group-links-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Click here to add a downloadable asset.', 'thrive-leads'); ?>">
								<span class="tvd-icon-stack"></span>
						<?php } elseif ($wizard == 1 && $assets_data['wizard']['proprieties']['files'] == 0) { ?>
							<div class="tve-asset-group-links-status-red tve-asset-group-links-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Asset Delivery is currently NOT active because no downloadable assets exist. Click the icon to add a downloadable.', 'thrive-leads'); ?>">
								<span class="tvd-icon-stack"></span>
						<?php } else { ?>
							<div class="tve-asset-group-links-status-green tve-asset-group-links-event tve-asset-group-status-wrapper tvd-tooltipped"
							data-tooltip="<?php echo __('Downloadable asset status: OK.', 'thrive-leads'); ?>">
								<span class="tvd-icon-stack"></span>
						<?php } ?>
							</div>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div
			id="asset-delivery-table-wrapper" <?php if ( ( $assets_data['wizard']['proprieties']['connections'] == 0 || $assets_data['wizard']['proprieties']['files'] == 0 || $assets_data['wizard']['proprieties']['template'] == 0 ) && $wizard == false ) {
			echo 'style="display: none"';
		} ?>>
			<div class="tvd-row">
				<div class="tvd-col tvd-s12">
					<h3 class="tvd-left tvd-title tvd-margin-right">
						<?php echo __( 'Asset Groups', 'thrive-leads' ); ?>
					</h3>
					<a class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-blue tvd-left tve-asset-group-links-event"
					   href="javascript:void(0)"><?php echo __( 'Add New', 'thrive-leads' ) ?></a>
				</div>
			</div>
			<ul class="tvd-collapsible" data-collapsible="expandable" id="tve-asset-group-list"></ul>
			<div class="show-no-asset-groups" style="display: none;">
				<p>
					<?php echo __( "You don't have any Asset Groups created yet. Click the 'Add new' button to create one.", 'thrive-leads' ) ?>
				</p>
				<a href="//fast.wistia.net/embed/iframe/09t999mxbt?popover=true"
				   class="wistia-popover[height=450,playerColor=2bb914,width=800]"><img
						src="<?php echo TVE_LEADS_ADMIN_URL . "img/video-thumb-asset-groups.jpg" ?>"
						alt=""/></a>
			</div>


			<div class="tve-collapse-table asset-delivery-table" id="tve-asset-delivery">
				<h2 class="tve-action-title">
					<a class="tve-leads-button tve-btn tve-btn-blue "
					   href="javascript:void(0)"
					   title="<?php echo __( 'Add New' ) ?>"
					   id="tve-asset-group-add"></a>
				</h2>
			</div>
			<a class="tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-dark tvd-waves-effect"
			   href="<?php echo admin_url( 'admin.php?page=thrive_leads_dashboard' ); ?>"
			   title="<?php echo __( 'Back to Dashboard' ) ?>"
			   id="tve-asset-group-dashboard">
				&laquo; <?php echo __( 'Back To Dashboard', 'thrive-leads' ) ?>
			</a>
		</div>
		<div
			id="tve-asset-setup" <?php if ( $assets_data['wizard']['proprieties']['connections'] == 1 && $assets_data['wizard']['proprieties']['files'] == 1 && $assets_data['wizard']['proprieties']['template'] == 1 || $wizard == 1 ) {
			echo 'style="display: none"';
		} ?>>
			<div class="tvd-row">
				<div class="tvd-col tvd-s8 tvd-offset-s2">
					<h3 class="tvd-center-align"> <?php echo __( 'Thrive Leads Asset Delivery', 'thrive-leads' ); ?></h3>
					<p class="tvd-center-align"> <?php echo __( 'Automatically send download links to new subscribers. Here are the 3 steps to get started.', 'thrive-leads' ); ?></p>
					<div class="tvd-row">
						<div class="tvd-col tvd-s12 tvd-m4">
							<div
								class="tvd-pointer tvd-card tvd-accent-card tve-asset-service <?php if ( $assets_data['wizard']['proprieties']['connections'] != 1 ) {
									echo "tvd-gray-accent";
								} else {
									echo "tvd-green-accent";
								} ?>">
								<div class="tvd-card-content tvd-center-align">
									<p>
										<strong>
											<?php echo __( 'STEP 1', 'thrive-leads' ); ?>
										</strong>
									</p>
									<div class="tvd-v-spacer vs-2"></div>
									<i class="tvd-icon-paper-plane tvd-icon-large"></i>
									<h3 class="tvd-card-title">
										<?php echo __( 'Email Delivery <br/> Service', 'thrive-leads' ); ?>
									</h3>
								</div>
								<div class="tvd-card-footer tvd-center-align">
									<strong class="tve-asset-service-status">
										<?php if ( $assets_data['wizard']['proprieties']['connections'] == 0 ) {
											echo "Pending";
										} else {
											echo "Ready!";
										} ?>
									</strong>
								</div>
							</div>
						</div>
						<div class="tvd-col tvd-s12 tvd-m4">
							<div
								class="tvd-pointer tvd-card tvd-gray-accent tvd-accent-card tve-asset-template <?php if ( $assets_data['wizard']['proprieties']['template'] != 1 ) {
									echo "tvd-gray-accent";
								} else {
									echo "tvd-green-accent";
								} ?>">
								<div class="tvd-card-content tvd-center-align">
									<p>
										<strong>
											<?php echo __( 'STEP 2', 'thrive-leads' ); ?>
										</strong>
									</p>
									<div class="tvd-v-spacer vs-2"></div>
									<i class="tvd-icon-file-text tvd-icon-large"></i>
									<h3 class="tvd-card-title">
										<?php echo __( 'Default Email <br/>Template', 'thrive-leads' ); ?>
									</h3>
								</div>
								<div class="tvd-card-footer tvd-center-align">
									<strong class="tve-asset-service-status ">
										<?php if ( $assets_data['wizard']['proprieties']['template'] == 0 ) {
											echo "Pending";
										} else {
											echo "Ready!";
										} ?>
									</strong>
								</div>
							</div>
						</div>
						<div class="tvd-col tvd-s12 tvd-m4">
							<div
								class="tvd-pointer tvd-card tvd-gray-accent tvd-accent-card tve-asset-download-link <?php if ( $assets_data['wizard']['proprieties']['files'] != 1 ) {
									echo "tvd-gray-accent";
								} else {
									echo "tvd-green-accent";
								} ?>">
								<div class="tvd-card-content tvd-center-align">
									<p>
										<strong>
											<?php echo __( 'STEP 3', 'thrive-leads' ); ?>
										</strong>
									</p>
									<div class="tvd-v-spacer vs-2"></div>
									<i class="tvd-icon-stack tvd-icon-large"></i>
									<h3 class="tvd-card-title">
										<?php echo __( 'First Download <br/>Link', 'thrive-leads' ); ?>
									</h3>
								</div>
								<div class="tvd-card-footer tvd-center-align">
									<strong class="tve-asset-service-status">
										<?php if ( $assets_data['wizard']['proprieties']['files'] == 0 ) {
											echo "Pending";
										} else {
											echo "Ready!";
										} ?>
									</strong>
								</div>
							</div>
						</div>
					</div>
					<div class="tve-leads-asset-get-details tvd-center-align">
						<p><a href="https://thrivethemes.com/tkb_item/asset-delivery/"
						      target="_blank"><?php echo __( 'Click here to learn more', 'thrive-leads' ); ?></a> <?php echo __( 'about the Asset Delivery feature', 'thrive-leads' ); ?>
						</p>
					</div>
					<div class="tve-leads-asset-go-dashboard tvd-center-align" style="display: none;">
						<p><?php echo __( 'Congratulations! Asset Delivery is now ready to go on this website.', 'thrive-leads' ); ?></p>
						<div class="tvd-row">
							<div class="tvd-col tvd-l8 tvd-offset-l2 tvd-m10 tvd-offset-m1">
								<a class="tl-play-link wistia-popover[height=450,playerColor=2bb914,width=800] tvd-btn-flat tvd-btn-flat-blue tve-leads-asset-opt-in tvd-waves-effect"
								   href="//fast.wistia.net/embed/iframe/t1r77tjnee?popover=true"><?php echo __( "Show Me How to Add Assets to Opt-in Forms", "thrive-cb" ) ?></a>
								<div class="tvd-v-spacer"></div>
								<button
									class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tve-leads-asset-dashboard"><?php echo __( "Continue to the Asset Delivery Dashboard", "thrive-cb" ) ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>