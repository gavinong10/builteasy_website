<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Pricing Table options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_text" data-multiple-hide><?php echo __( "Move highlighted column:", "thrive-cb" ) ?></li>
	<li class="btn_alignment tve_alignment_left tve_click" data-ctrl="controls.pricing_table.move" data-dir="prev" data-multiple-hide><?php echo __( "Left", "thrive-cb" ) ?></li>
	<li class="btn_alignment tve_alignment_right tve_click" data-ctrl="controls.pricing_table.move" data-dir="next" data-multiple-hide><?php echo __( "Right", "thrive-cb" ) ?></li>
	<li class="tve_add_highlight" data-multiple-hide>
		<div class="tve_btn_highlight tve_ed_btn tve_btn_text tve_left tve_click" data-ctrl="controls.pricing_table.highlight_toggle"><?php echo __( "Add highlighted column", "thrive-cb" ) ?></div>
	</li>
	<li><input type="text" class="element_class tve_text tve_change" data-ctrl="controls.change.cls" placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
	<li class="tve_clear" data-multiple-hide></li>

	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>