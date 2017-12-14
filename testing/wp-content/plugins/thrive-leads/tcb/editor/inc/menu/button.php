<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Button menu", "thrive-cb" ) ?></span>
<!--
    Tooltip Example
<li><span class="tve_tooltip" data-title="This is some lorem ipsum which will be set as a a tooltip" data-cls="tve_btn1">?</span></li>
-->
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_btn1" class="tve_click" data-cls="tve_btn1" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
						</li>
						<li id="tve_btn3" class="tve_click" data-cls="tve_btn3" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
						</li>
						<li id="tve_btn5" class="tve_click" data-cls="tve_btn5" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
						</li>
						<li id="tve_btn6" class="tve_click" data-cls="tve_btn6" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?>
						</li>
						<li id="tve_btn7" class="tve_click" data-cls="tve_btn7" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "5" ) ?>
						</li>
						<li id="tve_btn8" class="tve_click" data-cls="tve_btn8" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "6" ) ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Small", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_smallBtn" class="tve_click" data-cls="tve_smallBtn" data-size="1" data-ctrl="controls.click.add_class"><?php echo __( "Small", "thrive-cb" ) ?></li>
						<li id="tve_normalBtn" class="tve_click" data-cls="tve_normalBtn" data-size="1" data-ctrl="controls.click.add_class"><?php echo __( "Normal", "thrive-cb" ) ?></li>
						<li id="tve_bigBtn" class="tve_click" data-cls="tve_bigBtn" data-size="1" data-ctrl="controls.click.add_class"><?php echo __( "Big", "thrive-cb" ) ?></li>
						<li id="tve_hugeBtn" class="tve_click" data-cls="tve_hugeBtn" data-size="1" data-ctrl="controls.click.add_class"><?php echo __( "Huge", "thrive-cb" ) ?></li>
						<li id="tve_fullwidthBtn" class="btn_alignment"><?php echo __( "Full Width", "thrive-cb" ) ?></li>
						<li id="tve_defaultBtn" class="btn_alignment"><?php echo __( "Default", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Link Settings", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_large tve_dark tve_clearfix" style="min-width: 200px">
					<ul>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_small"><?php echo __( "Text", "thrive-cb" ) ?></span>
								<input type="text" id="buttonText" placeholder="<?php echo __( "Button text", "thrive-cb" ) ?>" class="tve_change"
								       data-ctrl="controls.change.link_text"/>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_small"><?php echo __( "URL", "thrive-cb" ) ?></span>
								<input type="text" id="buttonLink" placeholder="http://" class="tve_change"
								       data-ctrl="controls.change.link_url"/>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<input type="checkbox" id="btn_link_new_window" class="tve_change"
							       data-ctrl="controls.change.link_target">
							<label for="btn_link_new_window"><?php echo __( "New window?", "thrive-cb" ) ?></label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<input type="checkbox" id="btn_nofollow" class="tve_change"
							       data-ctrl="controls.change.link_rel"
							       data-value="nofollow">
							<label for="btn_nofollow"><?php echo __( "Nofollow?", "thrive-cb" ) ?></label>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<?php $css_padding_selector = 'a.tve_btnLink'; ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li class=""><input type="text" class="element_class tve_change" data-ctrl="controls.change.cls"
	                    placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
	<li class="tve_clear"></li>
	<li class="tve_text tve_firstOnRow">
		Align:
	</li>
	<li id="tve_leftBtn" class="btn_alignment tve_alignment_left">
		Left
	</li>
	<li id="tve_centerBtn" class="btn_alignment tve_alignment_center">
		Center
	</li>
	<li id="tve_rightBtn" class="btn_alignment tve_alignment_right">
		Right
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Use Icon", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_large tve_dark tve_clearfix" style="min-width: 400px">
					<ul>
						<li class="tve_no_hover tve_no_click">
							<div id="lb_icon" class="tve_ed_btn tve_btn_text tve_center tve_click" data-wpapi="lb_icon" data-load="1"
							     data-ctrl="controls.lb_open">
								<?php echo __( "Change Icon", "thrive-cb" ) ?>
							</div>
						</li>
						<li class="tve_no_hover tve_no_click">
							<div class="tve_text tve_slider_config"
							     data-value="0"
							     data-min-value="0"
							     data-property="font-size"
							     data-max-value="200"
							     data-selector=".tve_sc_icon"
							     data-input-selector="#tve_icon_button_font_size">
								<label for="tve_icon_button_font_size" class="tve_left"><?php echo __( "Icon size", "thrive-cb" ) ?></label>

								<div class="tve_slider tve_left">
									<div class="tve_slider_element" id="tve_icon_button_font_size_slider"></div>
								</div>
								<input class="tve_left" type="text" id="tve_icon_button_font_size" value="0" size="3">px

								<div class="clear"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Use Image", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_large tve_dark tve_clearfix" style="min-width: 400px">
					<ul>
						<li class="tve_no_hover tve_no_click">
							<div id="tve_changeImageBtn" class="tve_ed_btn tve_btn_text tve_center tve_click">
								<?php echo __( "Change Image", "thrive-cb" ) ?>
							</div>
						</li>
						<li class="tve_no_hover tve_no_click">
							<div class="tve_text tve_slider_config"
							     data-value="0"
							     data-min-value="0"
							     data-max-value="200"
							     data-selector="i"
							     data-input-selector="#tve_image_button_size_input">
								<label for="tve_image_button_size_input" class="tve_left"><?php echo __( "Image size", "thrive-cb" ) ?></label>

								<div class="tve_slider tve_left">
									<div class="tve_slider_element" id="tve_button_image_size_slider"></div>
								</div>
								<input class="tve_left" type="text" id="tve_image_button_size_input" value="0" size="3">px

								<div class="clear"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li>
		<div class="tve_text tve_left">
			<input type="checkbox" id="button_cc_icons" class="tve_change" data-ctrl="controls.change.cc_in_btn">
			<label for="button_cc_icons"><?php echo __( "Show Credit Card Icons?", "thrive-cb" ) ?></label>
		</div>
		<div class="tve_clear"></div>
	</li>
	<?php
	$event_target = '.tve_btnLink';
	include dirname( __FILE__ ) . '/_event_manager.php'
	?>
	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
	<?php include dirname( __FILE__ ) . '/_custom_font.php' ?>
	<li class="tve_clear"></li>
</ul>