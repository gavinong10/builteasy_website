<span class="tve_options_headline"><span
			class="tve_icm tve-ic-move"></span><?php echo __( "Text element menu", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator tve_mousedown" data-ctrl="controls.prevent_default">
			<i class="tve_icm tve-ic-color-lens tve_left"></i>
			<span class="tve_caret tve_icm tve_left" id="sub_01"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu color_selector" id="tve_sub_01_s">
					<ul class="tve_default_colors tve_left">
						<li class="tve_color_title"><span
									class="tve_options_headline"><?php echo __( "Default Colors", "thrive-cb" ) ?></span></li>
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
					<div class="tve_color_picker tve_left">
                        <span
								class="tve_options_headline tve_color_title"><?php echo __( "Custom Colors", "thrive-cb" ) ?></span>

						<div class="tve_colour_pickers">
							<input type="text" class="text_colour_picker" data-default-color="#000000">
						</div>
						<div class="tve_clear"></div>
						<div class="tve_remove_color_formatting tve_left">
                            <span class="tve_left tve_options_headline tve_click" data-ctrl="controls.text.remove_color"
								  data-mode="foreground"><i
										class="tve_left tve_icm tve-ic-eraser"></i> <?php echo __( "Clear text color", "thrive-cb" ) ?></span>
						</div>
						<div class="tve_clear"></div>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<?php /* colour picker for background color (highlight of current selection */ ?>
	<li class="tve_ed_btn tve_btn_text" data-multiple-hide>
		<div class="tve_option_separator tve_mousedown" data-ctrl="controls.prevent_default">
			<i class="tve_icm tve-ic-brush tve_left"></i><span
					class="tve_caret tve_icm tve_left" id="sub_01"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn tve_highlight_control">
				<div class="tve_sub active_sub_menu color_selector tve_sub_generic" id="tve_sub_01_s">
					<ul class="tve_default_colors tve_left">
						<li class="tve_color_title"><span
									class="tve_options_headline"><?php echo __( "Default Colors", "thrive-cb" ) ?></span></li>
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
					<div class="tve_color_picker tve_left">
                        <span
								class="tve_options_headline tve_color_title"><?php echo __( "Custom Colors", "thrive-cb" ) ?></span>

						<div class="tve_colour_pickers">
							<input type="text" class="text_colour_picker" data-highlight="1"
								   data-default-color="#000000">
						</div>
						<div class="tve_clear"></div>

						<div class="tve_remove_color_formatting tve_left">
                            <span class="tve_left tve_options_headline tve_click" data-ctrl="controls.text.remove_color"
								  data-mode="background"><i
										class="tve_left tve_icm tve-ic-eraser"></i> <?php echo __( "Clear highlight", "thrive-cb" ) ?></span>
						</div>
						<div class="tve_clear"></div>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-bold tve_mousedown" data-ctrl="controls.rangy_cls" data-command="bold"
			 title="<?php echo __( "Bold", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-italic tve_mousedown" data-ctrl="controls.rangy_cls" data-command="italic"
			 title="<?php echo __( "Italic", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-underline tve_mousedown" data-ctrl="controls.rangy_cls" data-command="underline"
			 title="<?php echo __( "Underline", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-strikethrough tve_mousedown" data-ctrl="controls.rangy_cls"
			 data-command="strikethrough" title="<?php echo __( "Strike-through", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon" data-multiple-hide>
		<div class="tve_icm tve-ic-list2 tve_click" id="text_bullet"
			 title="<?php echo __( "Unordered List", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon" data-multiple-hide>
		<div class="tve_icm tve-ic-numbered-list tve_click" id="text_numbered_bullet"
			 title="<?php echo __( "Numbered List", "thrive-cb" ) ?>"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-paragraph-left tve_click" title="<?php echo __( "Text align left", "thrive-cb" ) ?>"
			 data-ctrl="controls.click.text_align" data-cls="tve_p_left"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-paragraph-center tve_click"
			 title="<?php echo __( "Text align center", "thrive-cb" ) ?>" data-ctrl="controls.click.text_align"
			 data-cls="tve_p_center"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-paragraph-right tve_click" title="<?php echo __( "Text align right", "thrive-cb" ) ?>"
			 data-ctrl="controls.click.text_align" data-cls="tve_p_right"></div>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<div class="tve_icm tve-ic-paragraph-justify tve_click"
			 title="<?php echo __( "Text align justify", "thrive-cb" ) ?>" data-ctrl="controls.click.text_align"
			 data-cls="tvealignjustify"></div>
	</li>

	<?php include dirname( __FILE__ ) . '/_quick_link.php' ?>

	<?php if ( empty( $cpanel_config['disabled_controls']['more_link'] ) ) : ?>
		<li class="tve_ed_btn tve_btn_icon" data-multiple-hide>
            <span class="tve_icm tve-ic-more-horiz tve_click" title="<?php echo __( "Insert more link", "thrive-cb" ) ?>"
				  data-ctrl="controls.click.more_link"></span>
		</li>
	<?php endif ?>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" data-default="Formatting"><?php echo __( "Formatting", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="p"><?php echo __( "Paragraph", "thrive-cb" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="address"><?php echo __( "Address", "thrive-cb" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="pre"><?php echo __( "Preformatted", "thrive-cb" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="blockquote"><?php echo __( "Blockquote", "thrive-cb" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="h1"><?php echo sprintf( __( "Heading %s", "thrive-cb" ), "1" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="h2"><?php echo sprintf( __( "Heading %s", "thrive-cb" ), "2" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="h3"><?php echo sprintf( __( "Heading %s", "thrive-cb" ), "3" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="h4"><?php echo sprintf( __( "Heading %s", "thrive-cb" ), "4" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="h5"><?php echo sprintf( __( "Heading %s", "thrive-cb" ), "5" ) ?></li>
						<li class="tve_click tve_block_change" data-ctrl="controls.click.block_change"
							data-tag="h6"><?php echo sprintf( __( "Heading %s", "thrive-cb" ), "6" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text">
		<label>
			<?php echo __( "Font Size", "thrive-cb" ) ?> <input class="tve_text tve_font_size tve_change tve_mousedown"
																data-ctrl-mousedown="controls.save_selection"
																data-key="textSel" type="text" size="4" maxlength="5"/> px
		</label>
	</li>
	<li class="tve_clear"></li>
	<li class="tve_ed_btn tve_btn_text tve_click"
		id="tve_clear_font_size"><?php echo __( "Clear font size", "thrive-cb" ) ?></li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
            <span class="tve_ind tve_left"
				  data-default="Custom Font"><?php echo __( "Custom Font", "thrive-cb" ) ?></span><span
					class="tve_caret tve_icm tve_left tve_icm" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_medium" style="min-width: 220px">
					<ul>
						<li>
							<a class="tve_link tve_click" href="javascript:void(0)" id="tve-fonts-switcher"
							   data-display-type="custom"><?php echo __( "Choose Web Standard Fonts", "thrive-cb" ) ?></a>
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
									data-ctrl="controls.click.web_safe_font"
									data-web-font="<?php echo $safe_font['family'] ?>" data-prop="font-family"
									data-val="<?php echo tve_prepare_font_family( $safe_font['family'] ); ?>"
									class="tve_click tve_web_safe_font"><?php echo $safe_font['family']; ?></li>
							<?php endforeach; ?>
						<?php endif; ?>
						<li>
							<a class="tve_link" target="_blank" href="<?php echo $font_settings_url; ?>"><?php echo __( "Add new Custom Font", "thrive-cb" ) ?></a>
						</li>
					</ul>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_click"
		id="tve_clear_custom_font"><?php echo __( "Clear custom font", "thrive-cb" ) ?></li>
	<li data-multiple-hide>
		<input type="text" class="tve_change tve_text element_id" placeholder="<?php echo __( "ID", "thrive-cb" ) ?>"
			   data-ctrl="controls.change.element_id">
	</li>
	<li><input type="text" class="element_class tve_text tve_change" data-ctrl="controls.change.cls"
			   placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
	<li class="menu-sep">&nbsp;</li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<?php include dirname( __FILE__ ) . '/_line_height.php' ?>

	<!-- this only shows when the user clicks on a hyperlink -->

	<?php $li_custom_class = 'tve_link_btns';
	$li_custom_style       = 'style="display: none" data-multiple-hide';
	include dirname( __FILE__ ) . '/_event_manager.php' ?>
	<li style="display: none;" class="tve_ed_btn tve_btn_text tve_click tve_typefocus_btn"
		data-ctrl="controls.typefocus.transform_selection" data-multiple-hide><?php echo __( "TypeFocus", "thrive-cb" ) ?></li>
	<?php do_action( 'tcb_text_element_extra_buttons' ); ?>
</ul>