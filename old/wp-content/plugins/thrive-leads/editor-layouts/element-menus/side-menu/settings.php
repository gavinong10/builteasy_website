<?php if ( empty( $do_not_wrap ) ) : ?>
	<div class="tve_cpanel_sec tve_lp_sub">
	<div class="tve_option_separator tve_dropdown_submenu tve_drop_style ">
	<div class="tve_ed_btn tve_btn_text" style="display: block;">
		<span id="sub_02" class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" style="margin-top: -3px; margin-left: 4px;"></span>
		<span class="tve_expanded"><?php echo __( 'Thrive Leads Settings', 'thrive-leads' ) ?></span>
		<span class="tve_icm tve-ic-cog tve_collapsed"></span>

		<div class="tve_clear"></div>
	</div>
	<div class="tve_sub_btn">
	<div class="tve_sub" id="tve-leads-page-tpl-options" style="bottom: auto;top: 30px;width: 159px;">
<?php endif ?>
	<ul>
		<li class="tve_click" id="tl-tpl-chooser"
		    data-ctrl="controls.lb_open"
		    data-_key="<?php echo $variation['key'] ?>"
		    data-load="1"
		    data-post_id="<?php echo $variation['post_parent'] ?>"
		    data-wpapi="thrive_leads_templates"
		    data-btn-hide="1">
			<?php echo __( 'Choose Opt-in Template', 'thrive-leads' ) ?>
		</li>
		<li class="tve_click"
		    data-element-selector="<?php echo $available[ $form_type ]['edit_selector'] ?>"
		    data-ctrl="function:ext.tve_leads.form_settings">
			<?php echo sprintf( __( '%s Settings', 'thrive-leads' ), $form_type_name ) ?>
		</li>

		<li class="tve_click" data-ctrl="function:ext.tve_leads.template.reset" style="color: red;">
			<?php echo __( 'Reset To Default Content', 'thrive-leads' ) ?>
		</li>
	</ul>
<?php if ( empty( $do_not_wrap ) ) : ?>
	</div>
	</div>
	</div>
	<div class="tve_clear"></div>
	</div>
<?php endif ?>