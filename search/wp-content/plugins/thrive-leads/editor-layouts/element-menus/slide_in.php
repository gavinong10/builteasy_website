<div id="form_slide_in_menu">
	<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( 'Slide In general options', 'thrive-leads' ) ?></span>
	<ul class="tve_menu">
		<?php $hide_default_colors = 1;
		include dirname( __FILE__ ) . '/_form_box.php' ?>

		<li class="tve_text tve_slider_config" data-value="800" data-min-value="300"
		    data-max-value="available"
		    data-property="max-width"
		    data-selector=""
		    data-input-selector="#slide_in_size">
			<label for="ribbon_size" class="tve_left">&nbsp;<?php echo __( 'Maximum width', 'thrive-leads' ) ?></label>

			<div class="tve_slider tve_left" style="max-width: 200px;">
				<div class="tve_slider_element" id="tve_slide_in_slider"></div>
			</div>
			<input class="tve_left" type="text" id="slide_in_size" value="800" size="4"> px

			<div class="clear"></div>
		</li>
	</ul>
</div>