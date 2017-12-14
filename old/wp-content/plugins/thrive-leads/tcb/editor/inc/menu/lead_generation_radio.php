<span class="tve_options_headline"><?php echo __( "Radio Options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php
	$css_selector      = $css_padding_selector = ".tve_lg_radio";
	$margin_right_hide = $margin_left_hide = true;
	include dirname( __FILE__ ) . '/_margin.php';
	?>

	<?php
	$change_class_target = ".edit_mode .tve_lg_radio";
	include dirname( __FILE__ ) . '/_custom_class.php';
	?>

	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo sprintf( __( "Column %s", "thrive-cb" ), "1" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<?php for ( $i = 1; $i <= 10; $i ++ ): ?>
							<li class="tve_click" data-type="lead_generation_radio" data-cls="tve_lg_column<?php echo $i ?>" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Column %s", "thrive-cb" ), $i ) ?></li>
						<?php endfor; ?>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_text tve_slider_config" data-min-value="100" data-property="max-width" data-max-value="available"
	    data-selector="function:controls.lead_generation.radio_width_selector"
	    data-input-selector="#tve_lg_input">
		<label for="tve_lg_input" class="tve_left"><?php echo __( "Max Width", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element" id="tve_lg_radio_slider"></div>
		</div>
		<input class="tve_left" type="text" id="tve_lg_input" value="">

		<div class="clear"></div>
	</li>
	<?php include dirname( __FILE__ ) . '/_font_size.php' ?>
</ul>