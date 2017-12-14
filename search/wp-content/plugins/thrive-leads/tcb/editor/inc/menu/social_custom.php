<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Custom Social Sharing options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_btn_text">
		<label><?php echo __( "Button type", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Icon + text", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_social_ib" class="tve_click" data-cls="tve_social_ib" data-layout="1" data-ctrl="function:social.add_class"><?php echo __( "Icon only", "thrive-cb" ) ?></li>
						<li id="tve_social_itb" class="tve_click" data-cls="tve_social_itb" data-layout="1" data-ctrl="function:social.add_class"><?php echo __( "Icon + text", "thrive-cb" ) ?></li>
						<li id="tve_social_cb" class="tve_click" data-cls="tve_social_cb" data-layout="1" data-ctrl="function:social.add_class"><?php echo __( "Counter", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_style_1" class="tve_click" data-cls="tve_style_1" data-ctrl="function:social.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
						</li>
						<li id="tve_style_2" class="tve_click" data-cls="tve_style_2" data-ctrl="function:social.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
						</li>
						<li id="tve_style_3" class="tve_click" data-cls="tve_style_3" data-ctrl="function:social.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
						</li>
						<li id="tve_style_4" class="tve_click" data-cls="tve_style_4" data-ctrl="function:social.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?>
						</li>
						<li id="tve_style_5" class="tve_click" data-cls="tve_style_5" data-ctrl="function:social.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "5" ) ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
	<?php include dirname( __FILE__ ) . '/_custom_font.php' ?>
	<li class="tve_clear"></li>
	<li class="tve_text tve_firstOnRow">
		<?php echo __( "Align:", "thrive-cb" ) ?>
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

	<li class="tve_ed_btn tve_btn_text tve_click" data-ctrl="function:social.openOptions"><?php echo __( "Social Options", "thrive-cb" ) ?></li>

	<li class="tve_ed_btn tve_btn_text tve_click" data-ctrl="function:social.enableSortable"><?php echo __( "Modify Order of Buttons", "thrive-cb" ) ?></li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Total Share Count", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_text_left tve_no_click" style="min-width: 250px">
					<input type="checkbox" class="tve_change" id="tve-share-show-counts" data-ctrl="function:social.shareCount">
					<label for="tve-share-show-counts"><?php echo __( "Show total share count", "thrive-cb" ) ?></label>
					<br>
					<?php echo __( "Set the minimum number of shares that should be reached before the total share count is displayed:", "thrive-cb" ) ?>
					<br>
					<input type="text" class="tve_change" style="width: 50px !important" data-ctrl="function:social.shareCount" id="tve-share-min-shares" value="0"> <?php echo __( "shares", "thrive-cb" ) ?>
				</div>
			</div>
		</div>
	</li>
	<li class=""><input type="text" class="element_class tve_change" data-ctrl="controls.change.cls"
	                    placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>