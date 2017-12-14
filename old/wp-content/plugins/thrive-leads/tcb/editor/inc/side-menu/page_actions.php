<div class="tve_cpanel_sec" id="tve_page_actions">
	<div class="tve_ed_btn tve_btn_icon tve_left">
		<div class="tve_icm tve-ic-undo tve-disabled tve-undoredo" id="tve_undo_manager" data-type="undo" title="<?php echo __( "Undo last action", 'thrive-cb' ) ?>"></div>
	</div>
	<div class="tve_ed_btn tve_btn_icon tve_left tve_expanded">
		<div class="tve_icm tve-ic-redo tve-disabled tve-undoredo" id="tve_redo_manager" data-type="redo" title="<?php echo __( "Redo last action", "thrive-cb" ) ?>"></div>
	</div>
	<div class="tve_ed_btn tve_btn_icon tve_left" title="HTML">
		<div class="tve_icm tve-ic-code tve_click" data-ctrl="controls.lb_open" data-load="1" id="lb_full_html" title="HTML"></div>
	</div>
	<div class="tve_ed_btn tve_btn_icon tve_left" title="Save page content">
		<div class="tve_icm tve-ic-toggle-down tve_click tve_lb_small" data-ctrl="controls.lb_open" data-load="1" id="lb_save_user_template" title="<?php echo __( "Save page content as template", "thrive-cb" ) ?>"></div>
	</div>
	<div class="tve_ed_btn tve_btn_icon tve_left tve_option_separator tve_dropdown_submenu">
		<div class="tve_icm tve-ic-plus">
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu" id="tve_global_page_settings">
					<ul>
						<?php if ( $post_type != 'tcb_lightbox' && empty( $cpanel_config['disabled_controls']['page_events'] ) ) : ?>
							<li data-ctrl="controls.lb_open" id="tve_event_manager" class="tve_click" title="<?php echo __( "Page Event Manager", "thrive-cb" ) ?>">
								<?php echo __( "Page Event Manager", "thrive-cb" ) ?>
								<input type="hidden" name="scope" value="page">
							</li>
						<?php endif ?>
						<li id="tve_flipEditor"><?php echo __( "Switch Editor Side", "thrive-cb" ) ?></li>
						<li id="tve_flipColor"><?php echo __( "Change Editor Color", 'thrive-cb' ) ?></li>
						<li title="<?php echo __( "Turn On/Off Save Reminders on entire site", "thrive-cb" ) ?>" class="tve_click" data-skip-undo="1" data-ctrl="controls.click.save_reminders" data-args="<?php echo $tve_display_save_notification ? 0 : 1 ?>">Turn <span><?php echo $tve_display_save_notification ? "Off" : "On" ?></span> <?php echo __( "Save Reminders", 'thrive-cb' ) ?></li>
					</ul>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_ed_btn tve_btn_icon tve_left" title="<?php echo __( 'Revision Manager', 'thrive-cb' ) ?>" style="<?php echo ! $last_revision_id ? "display: none" : ""; ?>">
		<div class="tve_icm tve-ic-back-in-time tve_click" data-wpapi="lb_revision_manager" data-skip-undo="1" data-ctrl="controls.lb_open" data-load="1" data-btntext="<?php echo __( 'Close', 'thrive-cb' ) ?>" id="lb_revision_manager"></div>
	</div>
	<div class="tve_clear"></div>
</div>
<?php
/**
 * action that allows outputting custom page buttons to the top of the control panel
 */
do_action( 'tcb_custom_top_buttons' ); ?>