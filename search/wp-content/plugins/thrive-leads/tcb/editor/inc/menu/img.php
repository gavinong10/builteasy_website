<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Image options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$btn_class               = '';
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Border Type"><?php echo __( "Border Type", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_brdr_none" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "none", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dotted" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "dotted", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dashed" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "dashed", "thrive-cb" ) ?></li>
						<li id="tve_brdr_solid" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "solid", "thrive-cb" ) ?></li>
						<li id="tve_brdr_double" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "double", "thrive-cb" ) ?></li>
						<li id="tve_brdr_groove" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "groove", "thrive-cb" ) ?></li>
						<li id="tve_brdr_ridge" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "ridge", "thrive-cb" ) ?></li>
						<li id="tve_brdr_inset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "inset", "thrive-cb" ) ?></li>
						<li id="tve_brdr_outset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "outset", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text clearfix">
		<label class="tve_left" style="color: #878787">
			<input id="image_border_width" class="tve_change" value="0" type="text" size="3" data-css-property="border-width" data-suffix="px"
			       data-size="1"> px
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_icon tve_hidden_borderless">
		<span class="tve_icm tve-ic-paragraph-left tve_click" id="img_left_align"></span>
	</li>
	<li class="tve_ed_btn tve_btn_icon tve_hidden_feature_grid tve_hidden_borderless">
		<span class="tve_icm tve-ic-paragraph-center tve_click" id="img_center_align"></span>
	</li>
	<li class="tve_ed_btn tve_btn_icon tve_hidden_borderless">
		<span class="tve_icm tve-ic-paragraph-right tve_click" id="img_right_align"></span>
	</li>
	<li id="img_no_align" class="tve_ed_btn tve_btn_text tve_center tve_click tve_hidden_feature_grid tve_hidden_borderless">None</li>

	<?php $css_selector = '_parent::.tve_image_caption';
	$btn_class          = 'tve_hidden_feature_grid';
	include dirname( __FILE__ ) . '/_margin.php' ?>

	<?php include dirname( __FILE__ ) . '/_quick_link.php' ?>
	
	<li id="change_image" class="tve_ed_btn tve_center tve_btn_text btn_alignment upload_image_cpanel" data-multiple-hide><?php echo __( "Change Image", "thrive-cb" ) ?></li>
	<li class="tve_text clearfix" data-multiple-hide>
		<label for="img_alt_att" class="tve_left"><?php echo __( "Alt text", "thrive-cb" ) ?>&nbsp;</label>
		<input type="text" id="img_alt_att" class="tve_left tve_change">
	</li>
	<li class="tve_text clearfix tve_btn_text" data-multiple-hide>
		<label for="img_title_att" class="tve_left"><?php echo __( "Title text", "thrive-cb" ) ?>&nbsp;</label>
		<input type="text" id="img_title_att" class="tve_left tve_change">
	</li>
	<li class="tve_text tve_slider_config tve_hidden_feature_grid tve_image_slider_menu tve_hidden_borderless" data-value="300" data-min-value="0" data-max-value="available"
	    data-input-selector="#image_width_input" data-multiple-hide>
		<label for="image_width_input" class="tve_left">&nbsp;<?php echo __( "Image size", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element" id="tve_img_size_slider"></div>
		</div>
		<input class="tve_left" type="text" id="image_width_input" value="20px">

		<div class="clear"></div>
	</li>
	<li class=""><input type="text" class="element_class tve_change" data-ctrl="controls.change.cls" placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
	<li class=""><input type="text" class="tve_capt_cls tve_change" data-ctrl="controls.change.wrap_cls" placeholder="<?php echo __( "Caption class", "thrive-cb" ) ?>"></li>
	<li class="tve_ed_btn tve_btn_text tve_hidden_feature_grid">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "No Style", "thrive-cb" ) ?></span>
			<span id="sub_02" class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn" style="width: 482px">
				<div class="tve_sub active_sub_menu" style="width: 100%; box-sizing: border-box;">
					<ul class="tve_clearfix">
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_dark_frame">
							<div class="img_style_image"></div>
							<div><?php echo __( "Dark Frame", "thrive-cb" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_framed">
							<div class="img_style_image"></div>
							<div><?php echo __( "Framed", "thrive-cb" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_lifted_style1">
							<div class="img_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "1" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_lifted_style2">
							<div class="img_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "2" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_polaroid">
							<div class="img_style_image"></div>
							<div><?php echo __( "Polaroid", "thrive-cb" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_rounded_corners">
							<div class="img_style_image"></div>
							<div><?php echo __( "Rounded Corners", "thrive-cb" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_circle">
							<div class="img_style_image"></div>
							<div><?php echo __( "Circle", "thrive-cb" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="img_style_caption_overlay">
							<div class="img_style_image"></div>
							<div><?php echo __( "Caption Overlay", "thrive-cb" ) ?></div>
						</li>
						<li class="img_style tve_ed_btn tve_btn_text tve_left clearfix tve_click">
							<div class="img_style_image"></div>
							<div><?php echo __( "No Style", "thrive-cb" ) ?></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<?php $li_custom_style = ' data-multiple-hide';
	include dirname( __FILE__ ) . '/_event_manager.php';
	unset( $li_custom_class ) ?>
	<li class="tve_clear"></li>
</ul>