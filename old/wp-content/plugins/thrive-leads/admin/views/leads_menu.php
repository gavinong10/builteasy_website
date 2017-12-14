<?php
$current_screen = get_current_screen();
?>
<ul id="tl-leads-submenu" class="tvd-dropdown-content">
	<li <?php if ( $current_screen->base == "admin_page_thrive_leads_asset_delivery" ) {
		echo 'class="tl-leads-current-item-sub"';
	} ?>>
		<a href="<?php menu_page_url( 'thrive_leads_asset_delivery' ); ?>"
		   title="<?php echo __( 'Asset Delivery', 'thrive-leads' ) ?>">
			<i class="tvd-icon-stack"></i>
			<?php echo __( 'Asset Delivery', 'thrive-leads' ) ?>
		</a>
	</li>
	<li <?php if ( $current_screen->base == "admin_page_thrive_leads_contacts" ) {
		echo 'class="tl-leads-current-item-sub"';
	} ?>>
		<a href="<?php menu_page_url( 'thrive_leads_contacts' ); ?>"
		   title="<?php echo __( 'Leads Export', 'thrive-leads' ) ?>">
			<i class="tvd-icon-users2"></i>
			<?php echo __( 'Leads Export', 'thrive-leads' ) ?>
		</a>
	</li>
	<li>
		<a href="javascript:void(0)" class="tl-inbound-link-builder"
		   title="<?php echo __( 'Thrive Leads SmartLinks', 'thrive-leads' ) ?>">
			<i class="tvd-icon-link"></i>
			<?php echo __( 'Smart Links', 'thrive-leads' ) ?>
		</a>
	</li>
</ul>
<ul class="tvd-right">
	<li <?php if ( $current_screen->base == "admin_page_thrive_leads_reporting" ) {
		echo 'class="tl-leads-current-item"';
	} ?>>
		<a href="<?php menu_page_url( 'thrive_leads_reporting' ); ?>"
		   title="<?php echo __( 'Lead Reports', 'thrive-leads' ) ?>">
			<i class="tvd-icon-line-graph"></i>
			<?php echo __( 'Lead Reports', 'thrive-leads' ) ?>
		</a>
	</li>
	<li <?php if ( $current_screen->base == "admin_page_thrive_leads_asset_delivery" || $current_screen->base == "admin_page_thrive_leads_contacts" ) {
		echo 'class="tl-leads-current-item"';
	} ?>>
		<a class="tvd-dropdown-button" href="javascript:void(0)"
		   title="<?php echo __( 'Advanced Features', 'thrive-leads' ) ?>" data-activates="tl-leads-submenu"
		   data-beloworigin="true" data-hover="false">
			<i class="tvd-icon-light-bulb"></i>
			<?php echo __( 'Advanced Features', 'thrive-leads' ) ?>
			<i class="tvd-icon-expanded tvd-no-margin-right"></i>
		</a>
	</li>
	<li class="tl-open-settings">
		<a href="javascript:void(0)"
		   title="<?php echo __( 'Settings', 'thrive-leads' ) ?>">
			<i class="tvd-icon-settings"></i>
			<?php echo __( 'Settings', 'thrive-leads' ) ?>
		</a>
		<div class="tvd-dropdown-wrapper" id="tve-settings-postbox">
			<div class="tvd-row tvd-collapse">
				<div class="tvd-col tvd-m6">
					<h4><?php echo __( 'Lazy load forms' ) ?></h4>
				</div>
				<div class="tvd-col tvd-m6">
					<div class="tvd-switch tvd-right">
						<label>
							<input type="checkbox" name="ajax_load" value="1"
								<?php if ( $dashboard_data['global_settings']['ajax_load'] ) { ?> checked="checked"
								<?php } ?> class="tve-setting-change tve-setting-ajax_load" autocomplete="off">
							<span class="tvd-lever"></span>
						</label>
					</div>
				</div></div>
			<p>
				<?php echo __( 'Using lazy loading can speed up the loading of your page and ensure compatibility with the various
				 WordPress caching plugins such as W3 Total Cache, WP Super Cache and WP Rocket. If set to Off while caching plugins
				  are enabled, tracking and conversions will not be recorded correctly', 'thrive-leads' ) ?>
			</p>
			<hr>
			<h4><?php echo __( 'Reset cached statistics' ) ?></h4>
			<p>
				<?php echo __( 'In order to increase overall performance, Thrive Leads caches the number of impressions and conversions
				for each Lead Group, Shortcode, ThriveBox and Form. Click the following link to purge the cache and re-build it.', 'thrive-leads' ) ?>
			</p>
			<a class="tve-leads-clear-cache tvd-btn-flat tvd-btn-flat-blue tvd-waves-effect"
			   href="javascript:void(0)"><?php echo __( 'Purge cache', 'thrive-leads' ) ?></a>
			<hr>
			<h4><?php echo __( 'Logs' ) ?></h4>
			<p>
				<?php echo __( 'Clear Archived Logs' ) ?>
			</p>
			<a class="tve-leads-delete-logs tvd-btn-flat tvd-btn-flat-red tvd-waves-effect" href="javascript:void(0)">
				<?php echo __( 'CLEAR LOGS' ) ?>
			</a>
		</div>
	</li>
	<li>
		<a id="tvd-share-modal" class="tvd-modal-trigger" href="#tvd-modal1"
		   data-overlay_class="tvd-white-bg" data-opacity=".95">
			<span class="tvd-icon-heart"></span>
		</a>
	</li>
</ul>
<?php require_once( TVE_DASH_PATH . '/templates/share.phtml' ); ?>