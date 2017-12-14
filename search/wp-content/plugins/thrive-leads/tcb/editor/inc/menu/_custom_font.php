<li class="tve_ed_btn tve_btn_text">
	<div class="tve_option_separator">
        <span class="tve_ind tve_left"
              data-default="Custom Font"><?php echo __( "Custom Font", "thrive-cb" ) ?></span><span
			class="tve_caret tve_left tve_icm" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub active_sub_menu tve_medium" style="min-width: 220px">
				<ul class="tve_font_list">
					<li>
						<a class="tve_link tve_click" href="javascript:void(0)" id="tve-fonts-switcher" data-display-type="custom"><?php echo __( "Choose Web Standard Fonts", "thrive-cb" ) ?></a>
					</li>
					<?php foreach ( $fonts as $font ): ?>
						<li style="font-size:15px;line-height:28px"
						    class="tve_click tve_font_selector <?php echo $font['font_class'] ?>"
						    data-cls="<?php echo $font['font_class'] ?>"><?php echo $font['font_name'] . ' ' . $font['font_size'] ?></li>
					<?php endforeach; ?>
					<?php include $menu_path . '_extra_fonts.php' ?>
					<?php if ( ! empty( $web_safe_fonts ) ) : ?>
						<?php foreach ( $web_safe_fonts as $safe_font ) : ?>
							<li style="display: none;font-size:15px;line-height:28px;font-family:<?php echo tve_prepare_font_family( $safe_font['family'] ); ?>"
							    data-ctrl="controls.click.web_safe_font" data-web-font="<?php echo $safe_font['family'] ?>" data-prop="font-family" data-val="<?php echo tve_prepare_font_family( $safe_font['family'] ); ?>" class="tve_click tve_web_safe_font"><?php echo $safe_font['family']; ?></li>
						<?php endforeach; ?>
					<?php endif; ?>
					<li>
						<a class="tve_link" href="<?php echo $font_settings_url; ?>" target="_blank"><?php echo __( "Add new Custom Font", "thrive-cb" ) ?></a>
					</li>
				</ul>
				<div class="tve_clear"></div>
			</div>
		</div>
	</div>
</li>
<li class="tve_ed_btn tve_btn_text tve_click"
    id="tve_clear_custom_font"><?php echo __( "Clear custom font", "thrive-cb" ) ?></li>