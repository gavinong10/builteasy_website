<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Table of Contents options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li data-multiple-hide>
		<div id="tve_update_contents_table" class="tve_ed_btn tve_btn_text tve_center btn_alignment tve_left tve_click"><?php echo __( "Update", "thrive-cb" ) ?></div>
	</li>
	<li class="tve_firstOnRow" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom ct_heading" value="h1" type="checkbox" id="ct_heading1"/>
		<label for="ct_heading1" class="tve_left">H1</label>
	</li>
	<li data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom ct_heading" value="h2" type="checkbox" id="ct_heading2"/>
		<label for="ct_heading2" class="tve_left">H2</label>
	</li>
	<li data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom ct_heading" value="h3" type="checkbox" id="ct_heading3"/>
		<label for="ct_heading3" class="tve_left">H3</label>
	</li>
	<li data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom ct_heading" value="h4" type="checkbox" id="ct_heading4"/>
		<label for="ct_heading4" class="tve_left">H4</label>
	</li>
	<li data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom ct_heading" value="h5" type="checkbox" id="ct_heading5"/>
		<label for="ct_heading5" class="tve_left">H5</label>
	</li>
	<li data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom ct_heading" value="h6" type="checkbox" id="ct_heading6"/>
		<label for="ct_heading6" class="tve_left">H6</label>
	</li>
	<li data-multiple-hide>
		<label class="tve_text">
			<?php echo __( "Columns", "thrive-cb" ) ?>
			<select class="tve_change" id="ct_columns">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select>
		</label>
	</li>
	<li data-multiple-hide>
		<label>
			<?php echo __( "Min-width", "thrive-cb" ) ?> <input id="ct_min_width" class="tve_text tve_change" type="text" size="4" maxlength="4"/> px
		</label>
	</li>
	<li data-multiple-hide>
		<label>
			<?php echo __( "Max-width", "thrive-cb" ) ?> <input id="ct_max_width" class="tve_text tve_change" type="text" size="4" maxlength="4"/> px
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div title="<?php echo __( "Text align left", "thrive-cb" ) ?>" class="tve_icm tve-ic-paragraph-left tve_click" data-cls="tve_p_left" data-ctrl="controls.click.text_align"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div title="<?php echo __( "Text align center", "thrive-cb" ) ?>" class="tve_icm tve-ic-paragraph-center tve_click" data-cls="tve_p_center" data-ctrl="controls.click.text_align"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div title="<?php echo __( "Text align right", "thrive-cb" ) ?>" class="tve_icm tve-ic-paragraph-right tve_click" data-cls="tve_p_right" data-ctrl="controls.click.text_align"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div title="<?php echo __( "Text align justify", "thrive-cb" ) ?>" class="tve_icm tve-ic-paragraph-justify tve_click" data-cls="tvealignjustify" data-ctrl="controls.click.text_align"></div>
	</li>
</ul>