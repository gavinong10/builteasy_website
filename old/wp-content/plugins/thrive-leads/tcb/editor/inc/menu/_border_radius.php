<li class="tve_text tve_slider_config<?php echo ! empty( $li_class ) ? ' ' . $li_class : '';
unset( $li_class ) ?>"
    data-value="0"
    data-min-value="0"
    data-property="border-radius"
    data-max-value="<?php echo isset( $max_width ) ? $max_width : 200;
    unset( $max_width ) ?>"
    data-selector="<?php echo $border_radius_selector ?>"
    data-callback="<?php echo empty( $border_radius_callback ) ? '' : $border_radius_callback;
    unset( $border_radius_callback ); ?>"
    data-input-selector="#border_radius_input">
	<label for="border_radius_input" class="tve_left">&nbsp;<?php echo __( "Border Radius", "thrive-cb" ) ?></label>

	<div class="tve_slider tve_left">
		<div class="tve_slider_element" id="tve_border_radius_slider"></div>
	</div>
	<input class="tve_left tve_brdr_radius" type="text" id="border_radius_input" value="0" size="3">px

	<div class="clear"></div>
</li>