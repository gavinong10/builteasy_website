<span class="tve_options_headline"><span
		class="tve_icm tve-ic-move"></span><?php echo __( "TypeFocus element menu", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$hide_default_colors     = 1;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text tve_typefocus_menu_wrapper">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left tve_click" data-ctrl="controls.typefocus.init">Variations</span>
			<span class="tve_caret tve_icm tve_left tve_click" data-ctrl="controls.typefocus.init"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_typefocus_menu">
					<p><?php echo __( "TypeFocus allows you to rotate a selection of text in an animated way that can increase your conversions.", "thrive-cb" ) ?></p>
					<label><?php echo __( "Default text" ) ?></label>
					<textarea class="tve_typefocus_default_text" readonly="readonly"></textarea>

					<div class="tve_typefocus_variations">
						<p style="display: none;"><?php echo __( "You don't currently have any text variations, so TypeFocus is disabled", "thrive-cb" ) ?></p>
					</div>
					<div class="tve_ed_btn tve_btn_text tve_click tve_no_click"
					     data-ctrl="controls.typefocus.add_variation_form"><?php echo __( "Add text variation", 'thrive-cb' ) ?></div>
					<p><?php echo sprintf( __( "%s For longer texts, you may need to increase the time delay to prevent the animation from glitching", "thrive-cb" ), '<strong>' . __( "Note:", "thrive-cb" ) . '</strong>' ) ?></p>
				</div>

			</div>
		</div>
	</li>
	<li class="tve_btn_text">
		<label for="tve_typefocus_speed"><?php echo __( "Slide delay:", "thrive-cb" ) ?></label>
		<input id="tve_typefocus_speed" class="tve_change tve_typefocus_speed"
		       data-ctrl="controls.typefocus.update_speed" type="text" maxlength="4" style="3"/> ms
	</li>
	<li class="tve_ed_btn tve_btn_text tve_click tve_typefocus_clear"
	    data-ctrl="controls.typefocus.clear"><?php echo __( "Clear TypeFocus", "thrive-cb" ) ?></li>
	<li class="tve_btn_text">
		<input type="checkbox" id="tve_typefocus_cursor" class="tve_change" data-ctrl="controls.typefocus.toggle_cursor"/>
		<label for="tve_typefocus_cursor"><?php echo __( "Blink cursor effect", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_btn_text">
		<input type="checkbox" id="tve_typefocus_highlight" class="tve_change" data-ctrl="controls.typefocus.toggle_highlight"/>
		<label for="tve_typefocus_highlight"><?php echo __( "Display highlight if set", "thrive-cb" ) ?></label>
	</li>
</ul>