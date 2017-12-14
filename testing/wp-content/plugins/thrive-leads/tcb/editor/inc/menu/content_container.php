<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Content Container options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_text tve_firstOnRow"><?php echo __( "Align:", "thrive-cb" ) ?></li>
	<li id="tve_leftCC" class="btn_alignment tve_alignment_left"><?php echo __( "Left", "thrive-cb" ) ?></li>
	<li id="tve_centerCC" class="btn_alignment tve_alignment_center"><?php echo __( "Center", "thrive-cb" ) ?></li>
	<li id="tve_rightCC" class="btn_alignment tve_alignment_right"><?php echo __( "Right", "thrive-cb" ) ?></li>
	<li class="tve_text tve_slider_config" data-value="300" data-min-value="50"
	    data-max-value="available"
	    data-input-selector="#content_container_width_input"
	    data-property="width"
	    data-selector="> .tve_content_inner">
		<label for="content_container_width_input" class="tve_left">&nbsp;<?php echo __( "Max-width", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element"></div>
		</div>
		<input class="tve_left width50" type="text" id="content_container_width_input" value="300px">

		<div class="clear"></div>
	</li>
	<li data-multiple-hide>
		<input type="text" class="tve_change tve_text element_id" placeholder="ID" data-ctrl="controls.change.element_id">
	</li>
	<li>
		<input type="text" class="element_class tve_text tve_change" data-ctrl="controls.change.cls" placeholder="Custom class">&nbsp;&nbsp;
	</li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>