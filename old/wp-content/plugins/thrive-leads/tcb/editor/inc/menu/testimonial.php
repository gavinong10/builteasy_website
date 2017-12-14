<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Testimonial options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$extra_attr              = 'data-multiple-hide';
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_btn_text tve_ed_btn tve_center tve_mousedown" data-ctrl="controls.mousedown.test_image" id="testimonial_image_btn" data-multiple-hide><?php echo __( "Choose Testimonial Image", "thrive-cb" ) ?></li>

	<?php $css_selector = '_parent::.thrv_testimonial_shortcode' ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>