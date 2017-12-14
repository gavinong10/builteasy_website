<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Default Social Sharing options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_ed_btn tve_btn_text tve_click" data-ctrl="function:social.openOptions"><?php echo __( "Social Options", "thrive-cb" ) ?></li>
	<li class="tve_text tve_text_ctrl">
		<label class="tve_left"><?php echo __( "Button type:", "thrive-cb" ) ?> &nbsp;</label>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Button"><?php echo __( "Button", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_social_btn" data-type="btn" class="tve_click" data-ctrl="function:social.defaultButtonType"><?php echo __( "Button", "thrive-cb" ) ?></li>
						<li id="tve_social_btn_count" data-type="btn_count" class="tve_click" data-ctrl="function:social.defaultButtonType"><?php echo __( "Button + count", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_click" data-ctrl="function:social.enableSortable"><?php echo __( "Modify Order of Buttons", "thrive-cb" ) ?></li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>

	<li class="tve_text tve_firstOnRow">
		&nbsp;<?php echo __( "Align:", "thrive-cb" ) ?>
	</li>
	<li id="tve_leftBtn" class="btn_alignment tve_alignment_left">
		<?php echo __( "Left", "thrive-cb" ) ?>
	</li>
	<li id="tve_centerBtn" class="btn_alignment tve_alignment_center">
		<?php echo __( "Center", "thrive-cb" ) ?>
	</li>
	<li id="tve_rightBtn" class="btn_alignment tve_alignment_right">
		<?php echo __( "Right", "thrive-cb" ) ?>
	</li>
</ul>