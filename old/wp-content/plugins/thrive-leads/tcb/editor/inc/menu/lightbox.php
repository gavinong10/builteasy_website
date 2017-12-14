<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Thrive Lightbox options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<i class="tve_icm tve-ic-color-lens tve_left"></i><span
				class="tve_caret tve_icm tve_left" id="sub_01"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu color_selector" id="tve_sub_01_s">
					<div class="tve_color_picker tve_left">
						<span class="tve_options_headline tve_color_title"><?php echo __( "Custom Colors", "thrive-cb" ) ?></span>
					</div>
					<div class="tve_clear"></div>
					<div class="tve_text tve_slider_config" data-value="80" data-min-value="0"
					     data-max-value="100"
					     data-input-selector="#lightbox_opacity_input"
					     data-property="opacity"
					     data-handler="css_opacity"
					     data-no-child="1"
					     data-selector=".tve_p_lb_overlay">
						<label for="lightbox_opacity_input" class="tve_left">&nbsp;<?php echo __( "Overlay opacity", "thrive-cb" ) ?></label>

						<div class="tve_slider tve_left" style="width: 150px">
							<div class="tve_slider_element"></div>
						</div>
						<input class="tve_left width50" type="text" id="lightbox_opacity_input" value="80"><span class="tve_left" style="padding-top: 3px;">&nbsp;&nbsp;%</span>

						<div class="clear"></div>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<?php if ( ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
				<span class="tve_ind tve_left"><?php echo __( "Background pattern", "thrive-cb" ) ?></span>
				<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn" style="width: 715px;">
					<div class="tve_sub active_sub_menu" style="width: 100%">
						<ul class="tve_clearfix">
							<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-pattern="2" data-plugin="tve_lightbox">
								<span class="tve_section_colour tve_left tve_icm tve-ic-upload" style="margin: 0"></span>
								<span class="tve_left"><?php echo __( "Load...", "thrive-cb" ) ?></span>
							</li>
							<?php foreach ( $page_section_patterns as $i => $_image ) : ?>
								<?php $_uri = $template_uri . '/images/patterns/' . $_image . '.png' ?>
								<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-plugin="tve_lightbox" data-pattern="1">
									<span class="tve_section_colour tve_left" style="background:url('<?php echo $_uri ?>')"></span>
									<span class="tve_left"><?php echo 'pattern' . ( $i + 1 ); ?></span>
									<input type="hidden" data-image="<?php echo $_uri; ?>"/>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
				</div>
			</div>
		</li>
	<?php endif ?>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click" id="tve_lightbox_bg_image"><?php echo __( "Background image...", "thrive-cb" ) ?>
	</li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click"
	    id="tve_lightbox_clear_bg_color"><?php echo __( "Clear background color", "thrive-cb" ) ?>
	</li>
	<?php if ( ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click"
		    id="tve_lightbox_clear_bg_pattern"><?php echo __( "Clear background pattern", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_click"
	    id="tve_lightbox_clear_bg_image"><?php echo __( "Clear background image", "thrive-cb" ) ?>
	</li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Border Type"><?php echo __( "Border Type", "thrive-cb" ) ?></span><span
				class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_brdr_none" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "none", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dotted" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "dotted", "thrive-cb" ) ?></li>
						<li id="tve_brdr_dashed" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "dashed", "thrive-cb" ) ?></li>
						<li id="tve_brdr_solid" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "solid", "thrive-cb" ) ?></li>
						<li id="tve_brdr_double" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "double", "thrive-cb" ) ?></li>
						<li id="tve_brdr_groove" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "groove", "thrive-cb" ) ?></li>
						<li id="tve_brdr_ridge" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "ridge", "thrive-cb" ) ?></li>
						<li id="tve_brdr_inset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "inset", "thrive-cb" ) ?></li>
						<li id="tve_brdr_outset" class="tve_click" data-ctrl="controls.click.add_class" data-border="1"><?php echo __( "outset", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_firstOnRow tve_btn_text clearfix">
		<label class="tve_left" style="color: #878787">
			<input id="lightbox_border_width" class="tve_change" value="0" type="text" size="3" data-css-property="border-width" data-suffix="px"
			       data-size="1"> px&nbsp;&nbsp;
		</label>
	</li>
	<li class="tve_firstOnRow tve_btn_text clearfix">
		<label class="tve_left" style="color: #878787">
			<?php echo __( "Max width:", "thrive-cb" ) ?>
			<input id="lightbox_max_width" class="tve_change" value="650" type="text" size="3" data-css-property="max-width" data-suffix="px"
			       data-size="1"> px&nbsp;&nbsp;
		</label>
	</li>
</ul>