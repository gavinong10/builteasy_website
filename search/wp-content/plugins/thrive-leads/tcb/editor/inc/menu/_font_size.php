<li class="tve_btn_text">
	<label>
		<?php $font_size_label = empty( $font_size_label ) ? 'Font Size' : $font_size_label ?>
		<?php echo $font_size_label ?> <input class="tve_text tve_font_size tve_change tve_mousedown" data-ctrl-change="controls.font_size" data-ctrl-mousedown="controls.save_selection" data-key="textSel" type="text" size="4"/> px
	</label>
</li>
<li class="tve_ed_btn tve_btn_text tve_click" id="tve_clear_font_size"><?php echo __( "Clear font size", "thrive-cb" ) ?></li>