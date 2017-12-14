<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( 'Columns options', 'thrive-cb' ) ?></span>
<ul class="tve_menu">
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li class="tve_text">
		<?php echo __( "Vertical Align:", "thrive-cb" ); ?>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve-flex-start" class="tve_icm tve-ic-uniE634 btn_alignment" title="<?php echo __( "Vertical align top", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve-flex-center" class="tve_icm tve-ic-uniE635 btn_alignment" title="<?php echo __( "Vertical align middle", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div id="tve-flex-end" class="tve_icm tve-ic-uniE636 btn_alignment" title="<?php echo __( "Vertical align bottom", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_click" id="tve_clear_font_size">
		<div class="btn_alignment" id="default">
			<?php echo __( "Default", "thrive-cb" ) ?>
		</div>
	</li>
	<li class="tve_text clearfix">
		<label for="flex-reverse" class="tve_left">
			<input type="checkbox" class="cc_checkbox tve_left tve_change" data-ctrl="controls.flex_column.reverse_toggle" id="flex-reverse">
			<?php echo __( 'Reverse column order', 'thrive-cb' ) ?>
			<span class="tve_tooltip" data-title='<?php echo __( 'On mobile, the column order will be reversed, displaying them in order from the last one to the first one', 'thrive-cb' ); ?>'>?</span>
		</label>
	</li>
	<li></li>
	<li class="tve_text clearfix tve-hide-on-desktop">
		<label for="flex_wrap" class="tve_left">
			<span class="tve_tooltip" data-title='<?php echo __( 'Content will automatically wrap if the column size goes bellow the min width!', 'thrive-cb' ); ?>'>?</span>
			<?php echo __( "Wrap", "thrive-cb" ) ?>
		</label>
		<input type="checkbox" class="cc_checkbox tve_left tve_change" data-ctrl="controls.flex_column.wrap_toggle" id="flex_wrap">
	</li>
	<li class="tve_text clearfix tve-hide-on-desktop" id="tve_flex_min_width" style="display: none;">
		<label for="min_width" class="tve_left"><?php echo __( "Min width: ", "thrive-cb" ) ?> &nbsp;</label>
		<input type="text" class="cc_checkbox tve_left tve_change" value="220" data-ctrl="controls.flex_column.set_wrap" id="min_width">
	</li>
</ul>