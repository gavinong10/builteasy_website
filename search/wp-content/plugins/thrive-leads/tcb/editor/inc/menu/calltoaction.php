<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Call to Action options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$extra_attr              = 'data-multiple-hide';
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_btn_text clearfix" data-multiple-hide>
		<label for="call_to_action_url" class="tve_left"><?php echo __( "URL", "thrive-cb" ) ?></label>
		<input type="text" id="call_to_action_url" class="tve_left tve_change" data-ctrl="controls.change.link_url"/>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_center" data-multiple-hide>
		<a href="#" class="cta_test_link" target="_blank"><?php echo __( "Test Link", "thrive-cb" ) ?></a>
	</li>
	<li class="tve_text clearfix" data-multiple-hide>
		<input type="checkbox" id="cta_link_new_window" class="tve_change" data-ctrl="controls.change.link_target">
		<label for="cta_link_new_window" class="tve_left"><?php echo __( "Open link in new window?", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix" data-multiple-hide>
		<input type="checkbox" id="cta_nofollow" class="tve_change" data-ctrl="controls.change.link_rel" data-value="nofollow">
		<label for="cta_nofollow" class="tve_left"><?php echo __( "Nofollow", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_clear"></li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<?php $li_custom_style = ' data-multiple-hide';
	include dirname( __FILE__ ) . '/_event_manager.php' ?>
</ul>