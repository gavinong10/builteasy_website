<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Lead Generation options", "thrive-cb" ) ?></span>
<ul class="tve_menu tve_clearfix">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>

	<?php include dirname( __FILE__ ) . '/_margin.php' ?>

	<?php
	$css_selector  = $css_padding_selector = "input[type='text'], input[type='image'], select, textarea, button, .tve_lg_radio, .tve_lg_checkbox";
	$margin_prefix = 'Fields';
	include dirname( __FILE__ ) . '/_margin.php'
	?>

	<li data-multiple-hide>
		<div id="lb_lead_generation_code" class="tve_ed_btn tve_btn tve_btn_text tve_click" data-wpapi="lb_lead_generation_code"
		     data-ctrl="controls.lb_open"><?php echo __( "Connect with Service", "thrive-cb" ) ?>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Vertical", "thrive-cb" ) ?></span><span id="sub_02" class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu" style="display: block;">
					<ul>
						<li class="lead_generation_style tve_click" id="thrv_lead_generation_vertical"
						    data-ctrl="controls.lead_generation.style">
							<div class="lead_generation_image" id="lead_generation_vertical_image"></div>
							<div><?php echo __( "Vertical", "thrive-cb" ) ?></div>
						</li>
						<li class="lead_generation_style tve_click" id="thrv_lead_generation_horizontal"
						    data-ctrl="controls.lead_generation.style">
							<div class="lead_generation_image" id="lead_generation_horizontal_image"></div>
							<div><?php echo __( "Horizontal", "thrive-cb" ) ?></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Dimensions", "thrive-cb" ) ?></span><span id="sub_02" class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_lg_dimensions_dropdown tve_clearfix" style="display: block;">
					<ul>
						<li class="tve_text tve_slider_config tve_clearfix" data-value="300" data-min-value="200"
						    data-property="max-width"
						    data-max-value="available"
						    data-input-selector="#lead_generation_width_input" data-callback="lead_generation">
							<label for="lead_generation_width_input" class="tve_left">&nbsp;<?php echo __( "Form size", "thrive-cb" ) ?></label>

							<div class="tve_slider tve_left">
								<div class="tve_slider_element" id="tve_lead_generation_size_slider"></div>
							</div>
							<input class="tve_left" type="text" id="lead_generation_width_input" value="50px">

							<div id="tve_fullwidthBtn"
							     class="tve_ed_btn tve_btn_text tve_center btn_alignment tve_left"><?php echo __( "Full Width", "thrive-cb" ) ?>
							</div>
							<div class="clear"></div>
						</li>
						<?php /* removed input heights, as it seems it caused issues for a lot of users
                        <li class="tve_text tve_slider_config tve_clearfix" data-min-value="30" data-property="height"
                            data-max-value="100"
                            data-selector="function:controls.lead_generation.heights_selector"
                            data-input-selector="#lead_generation_height_input" data-callback="lead_generation">
                            <label for="lead_generation_height_input" class="tve_left">&nbsp;Inputs height</label>

                            <div class="tve_slider tve_left">
                                <div class="tve_slider_element" id="tve_lead_generation_height_slider"></div>
                            </div>
                            <input class="tve_left" type="text" id="lead_generation_height_input" value="">

                            <div data-ctrl="function:controls.lead_generation.clear_inputs_heights"
                                 data-args="function:controls.lead_generation.heights_selector,.edit_mode"
                                 class="tve_ed_btn tve_btn_text tve_center tve_left tve_click tve_no_click">Clear Inputs Height
                            </div>
                            <div class="clear"></div>
                        </li> */ ?>
						<li class="tve_text tve_slider_config tve_clearfix" data-min-value="100" data-property="max-width"
						    data-max-value="available"
						    data-selector="function:controls.lead_generation.widths_selector"
						    data-input-selector="#lead_generation_widths_input" data-callback="lead_generation">
							<label for="lead_generation_height_input" class="tve_left">&nbsp;<?php echo __( "Inputs width", "thrive-cb" ) ?></label>

							<div class="tve_slider tve_left">
								<div class="tve_slider_element" id="tve_lead_generation_widths_slider"></div>
							</div>
							<input class="tve_left" type="text" id="lead_generation_widths_input" value="">

							<div data-ctrl="function:controls.lead_generation.clear_inputs_widths"
							     data-args="function:controls.lead_generation.widths_selector,.edit_mode"
							     class="tve_ed_btn tve_btn_text tve_center tve_left tve_click tve_no_click"><?php echo __( "Clear Inputs Width", "thrive-cb" ) ?>
							</div>
							<div class="clear"></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text">
		<label class="tve_text">&nbsp;<?php echo __( "Align:", "thrive-cb" ) ?>&nbsp; </label>
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
	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
	<li style="display: none !important;">
		<label>
			<input id="lead_generation_form_target" class="tve_text tve_change" type="checkbox" value="_blank"/> <?php echo __( "Open in new window", "thrive-cb" ) ?>
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Set Error Messages", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_lg_errors tve_clearfix">
					<ul>
						<li class="tve_clearfix">
							<label for="tve_lg_email_error"><?php echo __( "Email address invalid:", "thrive-cb" ) ?></label>
							<textarea id="tve_lg_email_error" class="tve_change"></textarea>
						</li>
						<li class="tve_clearfix">
							<label for="tve_lg_phone_error"><?php echo __( "Phone number invalid", "thrive-cb" ) ?></label>
							<textarea id="tve_lg_phone_error" class="tve_change"></textarea>
						</li>
						<li class="tve_clearfix">
							<label for="tve_lg_required_error"><?php echo __( "Required field missing", "thrive-cb" ) ?></label>
							<textarea id="tve_lg_required_error" class="tve_change"></textarea>
						</li>
						<li>
							<div data-ctrl="function:controls.lead_generation.reset_errors" class="tve_ed_btn tve_btn_text tve_center tve_right tve_click tve_no_click"><?php echo __( "Reset errors to default", "thrive-cb" ) ?></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Edit Components", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn" style="min-width: 140px">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_input,input[type='text']"><?php echo __( "Text Inputs", "thrive-cb" ) ?>
						</li>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_submit,button"><?php echo __( "Submit Button", "thrive-cb" ) ?>
						</li>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_textarea,textarea"><?php echo __( "Textareas", "thrive-cb" ) ?>
						</li>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_dropdown,select"><?php echo __( "Dropdown Lists", "thrive-cb" ) ?>
						</li>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_checkbox,.tve_lg_checkbox"><?php echo __( "Checkbox Inputs", "thrive-cb" ) ?>
						</li>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_radio,.tve_lg_radio"><?php echo __( "Radio Inputs", "thrive-cb" ) ?>
						</li>
						<li class="tve_clearfix tve_click" data-ctrl="controls.click.toggle_menu"
						    data-args="lead_generation_image_submit,input[type='image']"><?php echo __( "Submit Image", "thrive-cb" ) ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
</ul>
<div id="lead_generation_checkbox_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_checkbox.php"; ?>
</div>
<div id="lead_generation_dropdown_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_dropdown.php"; ?>
</div>
<div id="lead_generation_image_submit_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_image_submit.php"; ?>
</div>
<div id="lead_generation_input_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_input.php"; ?>
</div>
<div id="lead_generation_radio_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_radio.php"; ?>
</div>
<div id="lead_generation_submit_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_submit.php"; ?>
</div>
<div id="lead_generation_textarea_menu" class="tve_clearfix tve_lg_custom_menu" style="display: none">
	<?php include_once dirname( __FILE__ ) . "/lead_generation_textarea.php"; ?>
</div>
