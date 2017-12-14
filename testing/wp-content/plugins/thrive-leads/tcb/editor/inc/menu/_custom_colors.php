<li class="tve_ed_btn tve_btn_text<?php if ( isset( $btn_class ) ) {
	echo ' ' . $btn_class;
}
unset( $btn_class ) ?>" <?php if ( isset( $extra_attr ) ) {
	echo ' ' . $extra_attr;
}
unset( $extra_attr ); ?>>
	<div class="tve_option_separator">
		<i class="tve_icm tve-ic-color-lens tve_left"></i><span class="tve_caret tve_left tve_icm" id="sub_01"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn ">
			<div class="tve_sub active_sub_menu color_selector tve_clearfix" id="tve_sub_01_s">
				<?php if ( empty( $hide_default_colors ) ) : ?>
					<ul class="tve_default_colors tve_left">
						<li class="tve_color_title"><span class="tve_options_headline"><?php echo __( "Default Colors", "thrive-cb" ) ?></span></li>
						<li class="tve_clear"></li>
						<li class="tve_black"><a href="#"></a></li>
						<li class="tve_blue"><a href="#"></a></li>
						<li class="tve_green"><a href="#"></a></li>
						<li class="tve_orange"><a href="#"></a></li>
						<li class="tve_clear"></li>
						<li class="tve_purple"><a href="#"></a></li>
						<li class="tve_red"><a href="#"></a></li>
						<li class="tve_teal"><a href="#"></a></li>
						<li class="tve_white"><a href="#"></a></li>
					</ul>
				<?php endif ?>
				<?php if ( isset( $has_custom_colors ) ) : ?>
					<div class="tve_color_picker tve_left">
						<span class="tve_options_headline tve_color_title"><?php echo __( "Custom Colors", "thrive-cb" ) ?></span>
					</div>
				<?php endif ?>
				<div class="tve_clear"></div>
			</div>
		</div>
	</div>
</li>
<?php unset( $has_custom_colors, $hide_default_colors ); ?>