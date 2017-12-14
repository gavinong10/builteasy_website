<span class="tve_options_headline"><?php echo __( "Checkbox Options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php
	$css_selector      = $css_padding_selector = ".tve_lg_checkbox";
	$margin_right_hide = $margin_left_hide = true;
	include dirname( __FILE__ ) . '/_margin.php';
	?>

	<?php
	$change_class_target = ".edit_mode .tve_lg_checkbox";
	include dirname( __FILE__ ) . '/_custom_class.php';
	?>
	<li class="tve_text tve_slider_config" data-min-value="100" data-property="max-width" data-max-value="available"
	    data-selector="function:controls.lead_generation.checkbox_width_selector"
	    data-input-selector="#tve_lg_input">
		<label for="tve_lg_input" class="tve_left"><?php echo __( "Max Width", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element" id="tve_lg_checkbox_slider"></div>
		</div>
		<input class="tve_left" type="text" id="tve_lg_input" value="">

		<div class="clear"></div>
	</li>
	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
</ul>