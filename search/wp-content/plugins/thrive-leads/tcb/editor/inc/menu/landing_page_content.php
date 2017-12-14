<?php
$hidden_menu_elements = ! empty( $landing_page_config['hidden_menu_items'] ) ? $landing_page_config['hidden_menu_items'] : array();
?>
<span class="tve_options_headline"><?php echo __( "Content Area Settings", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php if ( ! in_array( 'bg_color', $hidden_menu_elements ) ) : ?>
		<li class="tve_ed_btn tve_btn_text">
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
					</div>
				</div>
			</div>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'bg_pattern', $hidden_menu_elements ) && ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
				<span class="tve_ind tve_left"><?php echo __( "Background pattern", "thrive-cb" ) ?></span>
				<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn" style="width: 700px;">
					<div class="tve_sub active_sub_menu" style="">
						<ul class="tve_clearfix">
							<li style="display: none"></li>
							<?php foreach ( $page_section_patterns as $i => $_image ) : ?>
								<?php $_uri = $template_uri . '/images/patterns/' . $_image . '.png' ?>
								<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-pattern="1" data-plugin="tve_lp">
									<span class="tve_section_colour tve_left" style="background:url('<?php echo $_uri ?>')"></span>
									<span class="tve_left tve-pat-fixed"><?php echo 'pattern' . ( $i + 1 ); ?></span>
									<input type="hidden" data-image="<?php echo $_uri; ?>"/>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
				</div>
			</div>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'bg_image', $hidden_menu_elements ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click" id="tve_lp_bg_image"><?php echo __( "Background image...", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'max_width', $hidden_menu_elements ) ) : ?>
		<li class="tve_text tve_slider_config" data-value="1080" data-min-value="400"
		    data-max-value="3000"
		    data-input-selector="#lp_content_width"
		    data-property="max-width"
		    data-callback="function:landing_page.max_width_callback"
		    data-selector="function:landing_page.content_width_selector">
			<label for="content_container_width_input" class="tve_left">&nbsp;<?php echo __( "Max-width", "thrive-cb" ) ?></label>

			<div class="tve_slider tve_left">
				<div class="tve_slider_element"></div>
			</div>
			<input class="tve_left width50" type="text" id="lp_content_width" value="1080"><span style="float:left">&nbsp;px</span>

			<div class="clear"></div>
		</li>
	<?php endif ?>
	<li class="tve_clear"></li>
	<?php if ( ! in_array( 'bg_color', $hidden_menu_elements ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
		    id="tve_lp_clear_bg_color"><?php echo __( "Clear background color", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'bg_pattern', $hidden_menu_elements ) && ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
		    id="tve_lp_clear_bg_pattern"><?php echo __( "Clear background pattern", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'bg_image', $hidden_menu_elements ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
		    id="tve_lp_clear_bg_image"><?php echo __( "Clear background image", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<li class="tve_clear"></li>
	<?php if ( ! in_array( 'bg_static', $hidden_menu_elements ) ) : ?>
		<li class="tve_text clearfix tve_firstOnRow">
			<label class="tve_left">
				<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="tve_lp_bg_fixed"
				       value="1"> <?php echo __( "Static image", "thrive-cb" ) ?></label>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'bg_full_height', $hidden_menu_elements ) ) : ?>
		<li class="tve_text clearfix">
			<label class="tve_left">
				<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="tve_lp_auto_height" value="1"> <?php echo __( "Full height image", "thrive-cb" ) ?></label>
		</li>
	<?php endif ?>
	<?php if ( ! in_array( 'border_radius', $hidden_menu_elements ) ) : ?>
		<?php $border_radius_selector = ''; ?>
		<?php include dirname( __FILE__ ) . '/_border_radius.php' ?>
	<?php endif ?>

	<?php
	/* include any other possible control panel settings that might be needed for this specific landing page template */
	/* such files are located inside landing-page/menu/__landing_page_name__.php */
	$extra_settings_path = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/landing-page/menu/' . $landing_page_template . '.php';
	if ( is_file( $extra_settings_path ) ) {
		include $extra_settings_path;
	}
	?>
</ul>