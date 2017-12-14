<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Progress Bar Element", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Border Type">Border Type</span><span
				class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_brdr_none" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">none</li>
						<li id="tve_brdr_dotted" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">dotted</li>
						<li id="tve_brdr_dashed" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">dashed</li>
						<li id="tve_brdr_solid" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">solid</li>
						<li id="tve_brdr_double" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">double</li>
						<li id="tve_brdr_groove" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">groove</li>
						<li id="tve_brdr_ridge" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">ridge</li>
						<li id="tve_brdr_inset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">inset</li>
						<li id="tve_brdr_outset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1">outset</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text clearfix">
		<label class="tve_left" style="color: #878787">
			<input id="progress_bar_border_width" class="tve_change tve_css_applier" value="0" type="text" size="3" data-css-property="border-width" data-suffix="px"
			       data-size="1"> px
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_text" data-multiple-hide>
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Data Settings", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_text_left tve_no_click" style="min-width: 260px">
					<ul>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_large"><?php echo __( "Label: ", "thrive-cb" ) ?></span>
								<input type="text" class="tve_change" id="tve_data_element_label_p" data-ctrl="controls.change.data_element_label" placeholder="<?php echo __( "Progress bar label", "thrive-cb" ) ?>" value="1" size="15"/>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_large"><?php echo __( "Fill percentage: ", "thrive-cb" ) ?></span>
								<input type="text" class="tve_change" id="tve_data_element_percentage" data-ctrl="controls.change.data_element_percentage" placeholder="" value="1" size="2"/> %
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_large"><?php echo __( "Label font size: ", "thrive-cb" ) ?></span>
								<input type="text" class="tve_change" id="tve_data_element_font_size" data-ctrl="controls.change.data_element_font_size" placeholder="" value="1" size="2"/> px
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_large"><?php echo __( "Label line height: ", "thrive-cb" ) ?></span>
								<input type="text" class="tve_change" id="tve_data_element_line_height" data-ctrl="controls.change.data_element_line_height" placeholder="" value="1" size="2"/> px
							</label>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<?php include dirname( __FILE__ ) . '/_custom_font.php' ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li data-multiple-hide>
		<input type="text" class="tve_change tve_text element_id" placeholder="ID" data-ctrl="controls.change.element_id">
	</li>
	<li><input type="text" class="element_class tve_text tve_change" data-ctrl="controls.change.cls" placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
</ul>