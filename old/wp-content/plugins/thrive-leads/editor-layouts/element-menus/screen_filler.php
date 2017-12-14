<div id="form_screen_filler_menu">
	<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( 'Screen Filler general options', 'thrive-leads' ) ?></span>
	<ul class="tve_menu">
		<?php include dirname( __FILE__ ) . '/_form_box.php' ?>
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
		<li class="tve_ed_btn_text clearfix">
			<label class="tve_left" style="color: #878787">
				<input id="image_border_width" class="tve_change tve_brdr_width" value="0" type="text" size="3" data-css-property="border-width" data-suffix="px"
				       data-size="1"> px
			</label>
		<li class="tve_text tve_slider_config" data-value="1080" data-min-value="500"
		    data-max-value="2500"
		    data-property="max-width"
		    data-selector=".tve-screen-filler-content"
		    data-input-selector="#screen_filler_size">
			<label for="screen_filler_size" class="tve_left">&nbsp;<?php echo __( 'Maximum width', 'thrive-leads' ) ?></label>

			<div class="tve_slider tve_left" style="max-width: 200px;">
				<div class="tve_slider_element" id="tve_icon_size_slider"></div>
			</div>
			<input class="tve_left" type="text" id="screen_filler_size" value="1080" size="3"> px

			<div class="clear"></div>
		</li>
	</ul>
</div>