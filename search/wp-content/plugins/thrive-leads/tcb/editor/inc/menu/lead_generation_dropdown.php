<span class="tve_options_headline"><?php echo __( "Dropdown Options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php
	$css_selector      = $css_padding_selector = "select";
	$margin_right_hide = $margin_left_hide = true;
	include dirname( __FILE__ ) . '/_margin.php';
	?>

	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Border Type"><?php echo __( "Border Type", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_brdr_none" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "none", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dotted" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "dotted", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dashed" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "dashed", "thrive-cb" ) ?></li>
						<li id="tve_brdr_solid" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "solid", "thrive-cb" ) ?></li>
						<li id="tve_brdr_double" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "double", "thrive-cb" ) ?></li>
						<li id="tve_brdr_groove" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "groove", "thrive-cb" ) ?></li>
						<li id="tve_brdr_ridge" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "ridge", "thrive-cb" ) ?></li>
						<li id="tve_brdr_inset" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "inset", "thrive-cb" ) ?></li>
						<li id="tve_brdr_outset" class="tve_click" data-args="select" data-ctrl="controls.lead_generation.add_border_style" data-border="1"><?php echo __( "outset", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text clearfix">
		<label class="tve_left" style="color: #878787">
			<input id="tl_dropdown_border_width" class="tve_change" value="0" type="text" size="3"> px
		</label>
	</li>

	<?php
	$change_class_target = ".edit_mode select";
	include dirname( __FILE__ ) . '/_custom_class.php';
	?>

	<li class="tve_text tve_slider_config" data-min-value="100" data-property="max-width" data-max-value="available"
	    data-selector="function:controls.lead_generation.dropdown_width_selector"
	    data-input-selector="#tve_lg_dropdown">
		<label for="tve_lg_dropdown" class="tve_left"><?php echo __( "Max Width", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element" id="tve_lg_dropdown_slider"></div>
		</div>
		<input class="tve_left" type="text" id="tve_lg_dropdown" value="">

		<div class="clear"></div>
	</li>
	<li>
		<div class="tve_ed_btn tve_btn_text tve_center tve_left tve_click"
		     data-ctrl="function:controls.lead_generation.clear_width"
		     data-args=".tve_lg_dropdown,select,lead_generation_dropdown"><?php echo __( "Full Width", "thrive-cb" ) ?>
		</div>
	</li>

	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
</ul>