<li class="tve_btn_text">
	<?php $line_height_label = empty( $line_height_label ) ? __( 'Line height', "thrive-cb" ) : $line_height_label ?>
	<label><?php echo $line_height_label ?></label>
	<input id="tve_line_height" class="tve_change tve_mousedown" data-ctrl-mousedown="controls.save_selection" data-key="textSel" value="" type="text" size="4" data-css-property="line-height"
	       data-size="1">
</li>
<li class="tve_ed_btn tve_btn_text">
	<div class="tve_option_separator">
		<span class="tve_ind tve_left line-height-unit" data-default="px" id="tve_line_height">px</span><span
			class="tve_caret tve_left tve_icm"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub active_sub_menu">
				<ul>
					<li id="tve_line_height_px" class="tve_click tve_line_height_unit">px</li>
					<li id="tve_line_height_em" class="tve_click tve_line_height_unit">em</li>
				</ul>
			</div>
		</div>
	</div>
</li>
<li class="tve_ed_btn tve_btn_text tve_click" id="tve_clear_line_height"><?php echo __( "Clear line height", "thrive-cb" ) ?></li>