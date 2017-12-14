<?php
$selectors = array(
	//html selector => label text
	'h1' => 'Heading 1',
	'h2' => 'Heading 2',
	'h3' => 'Heading 3',
	'p'  => 'Paragraph',
);
?>
<div class="tve_options_headline">
	<span class="tve_icm tve-ic-move"></span>
	<span><?php echo __( "Landing Page Font Settings", "thrive-cb" ) ?></span> -
	<a target="_blank" href="<?php echo admin_url( 'admin.php?page=tve_dash_font_manager' ) ?>"><?php echo __( "Add New Custom Fonts", "thrive-cb" ) ?></a>
</div>

<ul class="tve_menu">
	<li class="tve_btn_text">
		<label><?php echo __( "Colors", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<i class="tve_icm tve-ic-color-lens tve_left"></i>
			<span class="tve_caret tve_left tve_icm" id="sub_01"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn ">
				<div class="tve_sub active_sub_menu color_selector tve_clearfix" id="tve_sub_01_s">
					<div class="tve_color_picker tve_left">
						<span class="tve_options_headline tve_color_title"><?php echo __( "Custom Colors", "thrive-cb" ) ?></span>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_clear"></li>
</ul>

<?php foreach ( $selectors as $selector => $label ) : ?>
	<ul class="tve_menu" data-selector="<?php echo $selector ?>">
		<li class="tve_btn_text">
			<label><?php echo $label ?></label>
		</li>
		<li class="tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
				<span class="tve_ind tve_left" data-default="Custom Font"><?php echo __( "Custom Font", "thrive-cb" ) ?></span>
				<span class="tve_caret tve_left tve_icm" id="sub_02"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn">
					<div class="tve_sub active_sub_menu tve_medium" style="min-width: 220px">
						<ul class="tve_font_list">
							<?php foreach ( $fonts as $font ): ?>
								<li style="font-size:15px;line-height:28px"
								    class="tve_click <?php echo $font['font_class'] ?>"
								    data-cls="<?php echo $font['font_class'] ?>"
								    data-font-family="<?php echo tve_prepare_font_family( $font['font_name'] ) ?>"
								    data-selector="<?php echo $selector ?>"
								    data-ctrl="function:landing_fonts.setFontFamily">
									<?php echo $font['font_name'] . ' ' . $font['font_size'] ?>
								</li>
							<?php endforeach; ?>
							<?php if ( ! empty( $extra_custom_fonts ) ) : ?>
						</ul>
						<strong><?php echo __( 'Imported fonts', 'thrive-cb' ) ?></strong>
						<div class="tve_clear"></div>
						<ul class="tve_font_list">
							<?php foreach ( $extra_custom_fonts as $font ) : ?>
							<li style="font-size:15px;line-height:28px"
							    class="tve_click <?php echo $font['font_class'] ?>"
							    data-cls="<?php echo $font['font_class'] ?>"
							    data-font-family="<?php echo tve_prepare_font_family( $font['font_name'] ) ?>"
							    data-selector="<?php echo $selector ?>"
							    data-ctrl="function:landing_fonts.setFontFamily">
								<?php echo $font['font_name'] . ' ' . $font['font_size'] ?>
								<?php endforeach ?>
								<?php endif ?>
							<li>
								<a class="tve_link" href="<?php echo $font_settings_url; ?>" target="_blank">
									<?php echo __( "Add new Custom Font", "thrive-cb" ) ?>
								</a>
							</li>
						</ul>
						<div class="tve_clear"></div>
					</div>
				</div>
			</div>
		</li>
		<li class="tve_ed_btn tve_btn_text tve_click" data-ctrl="function:landing_fonts.clearFontFamily"
		    data-selector="<?php echo $selector ?>"><?php echo __( "Clear custom font", "thrive-cb" ) ?>
		</li>


		<li class="tve_btn_text">
			<label>
				Font size
				<input class="tve_text tve_change tve_landing_fonts_size" value="0"
				       data-ctrl="function:landing_fonts.setFontSize" type="text"
				       data-selector="<?php echo $selector ?>"
				       size="3" maxlength="4"/>
			</label>
		</li>
		<li class="tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
                <span class="tve_ind tve_left tve_landing_fonts_size_unit" data-selector="<?php echo $selector ?>"
                      data-default="px">px</span>
				<span class="tve_caret tve_left tve_icm"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn">
					<div class="tve_sub active_sub_menu">
						<ul>
							<li class="tve_click tve_landing_fonts_size_px" data-selector="<?php echo $selector ?>"
							    data-ctrl="function:landing_fonts.setFontSizeUnit">px
							</li>
							<li class="tve_click tve_landing_fonts_size_em" data-selector="<?php echo $selector ?>"
							    data-ctrl="function:landing_fonts.setFontSizeUnit">em
							</li>
						</ul>
					</div>
				</div>
			</div>
		</li>

		<li class="tve_btn_text">
			<label>
				Line Height
				<input class="tve_text tve_change tve_landing_fonts_line_height" value="0"
				       data-ctrl="function:landing_fonts.setFontLineHeight" type="text"
				       data-selector="<?php echo $selector ?>"
				       size="3" maxlength="4"/>
			</label>
		</li>
		<li class="tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
                <span class="tve_ind tve_left tve_landing_fonts_line_height_unit"
                      data-selector="<?php echo $selector ?>"
                      data-default="px">px</span>
				<span class="tve_caret tve_left tve_icm"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn">
					<div class="tve_sub active_sub_menu">
						<ul>
							<li class="tve_click tve_landing_fonts_line_height_px"
							    data-selector="<?php echo $selector ?>"
							    data-ctrl="function:landing_fonts.setFontLineHeightUnit">px
							</li>
							<li class="tve_click tve_landing_fonts_line_height_em"
							    data-selector="<?php echo $selector ?>"
							    data-ctrl="function:landing_fonts.setFontLineHeightUnit">em
							</li>
						</ul>
					</div>
				</div>
			</div>
		</li>

		<li class="tve_clear"></li>
	</ul>
<?php endforeach; ?>
<div class="tve_clear"></div>
