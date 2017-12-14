<div class="tve_cpanel_sec tve_lp_sub" style="padding-right: 14px;">
	<div class="tve_option_separator tve_dropdown_submenu tve_drop_style ">
		<div class="tve_ed_btn tve_btn_text" style="display: block;">
			<span id="sub_02" class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" style="margin-top: -3px; margin-left: 4px;"></span>
			<span class="tve_expanded"><?php echo __( "Thrive Landing Pages", "thrive-cb" ) ?></span>
			<span class="tve_collapsed tve_icm tve_left tve-ic-lp"></span>
			<div class="tve_clear"></div>
		</div>
		<div class="tve_sub_btn">
			<div class="tve_sub" style="bottom: auto;top: 30px;width: 159px;">
				<ul>
					<li class="tve_click" data-ctrl="controls.landing_page_import" data-btntitle="<?php echo __( 'Import File', 'thrive-cb' ) ?>" data-title="<?php echo __( 'Import Landing Page', 'thrive-cb' ) ?>">
						<?php echo __( 'Import Landing Page', 'thrive-cb' ) ?>
					</li>
					<?php if ( $landing_page_template ) : ?>
						<li class="tve_click" data-ctrl="controls.click.lp_settings">
							<?php echo __( "Landing Page Settings", "thrive-cb" ) ?>
						</li>
						<li class="tve_click" data-ctrl="controls.lb_open" data-lb="lb_static_lp_export" data-btntext="<?php echo __( 'Download File', 'thrive-cb' ) ?>">
							<?php echo __( 'Export Landing Page', 'thrive-cb' ) ?>
						</li>
					<?php endif ?>
					<li class="tve_click" data-ctrl="controls.lb_open" id="lb_landing_pages" data-load="1">
						<?php echo __( "Choose landing page ...", "thrive-cb" ) ?>
						<input type="hidden" name="landing_page" value="<?php echo $landing_page_template ?>"/>
					</li>
					<?php if ( $landing_page_template ) : ?>
						<li class="tve_click" data-ctrl="controls.lp.disable"><?php echo __( 'Revert to theme', 'thrive-cb' ) ?></li>
						<li class="tve_click" data-ctrl="controls.lp.reset" style="color: red;"><?php echo __( 'Reset Landing Page', 'thrive-cb' ) ?></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>