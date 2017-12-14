<div id="form_ribbon_menu">
	<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( 'Ribbon general options', 'thrive-leads' ) ?></span>
	<ul class="tve_menu">
		<?php include dirname( __FILE__ ) . '/_form_box.php' ?>
		<li class="tve_text tve_slider_config" data-value="1080" data-min-value="500"
		    data-max-value="available"
		    data-property="max-width"
		    data-selector=".tve-ribbon-content"
		    data-input-selector="#ribbon_size">
			<label for="ribbon_size" class="tve_left">&nbsp;<?php echo __( 'Maximum width', 'thrive-leads' ) ?></label>

			<div class="tve_slider tve_left" style="max-width: 200px;">
				<div class="tve_slider_element" id="tve_icon_size_slider"></div>
			</div>
			<input class="tve_left" type="text" id="ribbon_size" value="1080" size="3"> px

			<div class="clear"></div>
		</li>
	</ul>
</div>