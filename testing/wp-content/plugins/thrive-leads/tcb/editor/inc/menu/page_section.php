<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Page Section options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
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
	<?php if ( ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
				<span class="tve_ind tve_left"><?php echo __( "Background pattern", "thrive-cb" ) ?></span>
				<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn" style="width: 715px;">
					<div class="tve_sub active_sub_menu" style="width: 100%">
						<ul class="tve_clearfix">
							<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-pattern="2" data-plugin="tve_page_section">
								<span class="tve_section_colour tve_left tve_icm tve-ic-upload" style="margin: 0"></span>
								<span class="tve_left"><?php echo __( "Load...", "thrive-cb" ) ?></span>
							</li>
							<?php foreach ( $page_section_patterns as $i => $_image ) : ?>
								<?php $_uri = $template_uri . '/images/patterns/' . $_image . '.png' ?>
								<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-pattern="1" data-plugin="tve_page_section">
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
	<li class="tve_ed_btn tve_btn_text tve_center tve_click" id="tve_page_section_bg_image"><?php echo __( "Background image...", "thrive-cb" ) ?></li>
	<?php /* removing this from landing pages, as it does not make sense anymore, you can setup custom colors for stuff inside. I wasn't working on any landing page */ ?>
	<?php if ( ! $landing_page_template ) : ?>
		<li class="tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
				<span class="tve_ind tve_left"><?php echo __( "Text style", "thrive-cb" ) ?></span>
				<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn">
					<div class="tve_sub active_sub_menu">
						<ul>
							<li class="tve_btn_text tve_click clearfix" id="tve_page_section_s_light"><?php echo __( "Light", "thrive-cb" ) ?></li>
							<li class="tve_btn_text tve_click clearfix" id="tve_page_section_s_dark"><?php echo __( "Dark", "thrive-cb" ) ?></li>
						</ul>
					</div>
				</div>
			</div>
		</li>
	<?php endif ?>
	<?php $css_padding_selector = '.in';
	include dirname( __FILE__ ) . '/_margin.php' ?>
	<li data-multiple-hide><input type="text" class="element_id tve_change tve_text" data-ctrl="controls.change.element_id" placeholder="<?php echo __( "Custom ID", "thrive-cb" ) ?>"></li>
	<li class="tve_clear"></li>
	<li class="tve_text clearfix tve_firstOnRow">
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="tve_page_section_bg_fixed"
		       value="1"><label
			for="tve_page_section_bg_fixed" class="tve_left"> <?php echo __( "Static image", "thrive-cb" ) ?></label> &nbsp;
	</li>
	<li class="tve_text clearfix">
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="tve_page_section_auto_height" value="1"><label
			for="tve_page_section_auto_height" class="tve_left"> <?php echo __( "Full height image", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_clear"></li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
	    id="tve_page_section_clear_shadow"><?php echo __( "Clear shadow", "thrive-cb" ) ?>
	</li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
	    id="tve_page_section_clear_bg_color"><?php echo __( "Clear background color", "thrive-cb" ) ?>
	</li>
	<?php if ( ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
		    id="tve_page_section_clear_bg_pattern"><?php echo __( "Clear background pattern", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
	    id="tve_page_section_clear_bg_image"><?php echo __( "Clear background image", "thrive-cb" ) ?>
	</li>


	<li class="tve_ed_btn tve_btn_text">
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
	<li class="tve_btn_text clearfix">
		<label class="tve_left" style="color: #878787">
			<input id="page_section_border_width" class="tve_change tve_css_applier" value="0" type="text" size="3" data-css-property="border-width" data-suffix="px"
			       data-size="1"> px
		</label>
	</li>


	<li class="tve_ed_btn tve_btn_text tve_shadow_control<?php if ( isset( $btn_class ) ) {
		echo ' ' . $btn_class;
	}
	unset( $btn_class ) ?>" data-multiple-hide>
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Shadow", "thrive-cb" ) ?></span><span class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<?php if ( ! empty( $css_selector ) ) : ?>
				<input type="hidden" class="css-selector" value="<?php echo $css_selector ?>"/>
				<?php unset( $css_selector ); endif ?>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_dark tve_clearfix" style="min-width: 510px">
					<ul style="width: 250px" class="tve_left">
						<li style="text-align: center"><strong><?php echo __( "Internal Shadow", "thrive-cb" ) ?></strong></li>
						<li class="tve_no_hover tve_no_click tve_clearfix" style="text-align: center">
							<div class="tve_ed_btn tve_btn_text tve_click" data-ctrl="controls.shadow.size" data-size="small" data-inset="1" style="display: inline-block;margin-right: 15px;">
								<?php echo __( "Small", "thrive-cb" ) ?>
							</div>
							<div class="tve_ed_btn tve_btn_text tve_click" data-ctrl="controls.shadow.size" data-size="medium" data-inset="1" style="display: inline-block;margin-right: 15px;">
								<?php echo __( "Medium", "thrive-cb" ) ?>
							</div>
							<div class="tve_ed_btn tve_btn_text tve_click" data-ctrl="controls.shadow.size" data-size="large" data-inset="1" style="display: inline-block;"><?php echo __( "Large", "thrive-cb" ) ?></div>
						</li>
						<li class="tve_no_hover tve_no_click tve_clearfix" style="text-align: center">
							<div class="tve_text tve_slider_config" data-value="0" data-min-value="0"
							     data-handler="void_handler"
							     data-max-value="200"
							     data-step="2"
							     data-callback="function:controls.shadow.inset_slider"
							     data-property="">

								<div class="tve_slider" style="max-width: 200px; margin: 0 auto">
									<div class="tve_slider_element tve_slider_shadow tve_inset"></div>
								</div>

								<div class="clear"></div>
							</div>
						</li>
						<li class="tve_no_hover tve_no_click tve_arrow_group" style="height: auto; line-height: 1;padding: 10px 0;text-align: center">
							<div class="tve_icm tve-ic-arrow-up tve_click" data-ctrl="controls.shadow.position" data-coords="inset_top" title="<?php echo __( "Top", "thrive-cb" ) ?>"></div>
							<div class="tve_clear"></div>
							<div class="tve_icm tve-ic-square-o tve_click" data-ctrl="controls.shadow.position" data-coords="inset_center" title="<?php echo __( "Middle / Center", "thrive-cb" ) ?>"></div>
							<div class="tve_clear"></div>
							<div class="tve_icm tve-ic-arrow-down tve_click" data-ctrl="controls.shadow.position" data-coords="inset_bottom" title="<?php echo __( "Bottom", "thrive-cb" ) ?>"></div>
						</li>
						<li class="tve_no_hover tve_no_click" style="text-align: center;">
							<div style="display: inline-block" class="tve_ed_btn tve_btn_text tve_click tve_center" data-inset="1" data-ctrl="controls.shadow.clear"><?php echo __( "Clear Internal Shadow", "thrive-cb" ) ?></div>
						</li>
						<li class="tve_no_hover tve_no_click">&nbsp;</li>
					</ul>
					<ul style="width: 250px" class="tve_left">
						<li style="text-align: center"><strong><?php echo __( "External Shadow", "thrive-cb" ) ?></strong></li>
						<li class="tve_no_hover tve_no_click tve_clearfix" style="text-align: center">
							<div class="tve_ed_btn tve_btn_text tve_click" data-ctrl="controls.shadow.size" data-size="small" style="display: inline-block;margin-right: 15px;"><?php echo __( "Small", "thrive-cb" ) ?></div>
							<div class="tve_ed_btn tve_btn_text tve_click" data-ctrl="controls.shadow.size" data-size="medium" style="display: inline-block;margin-right: 15px;"><?php echo __( "Medium", "thrive-cb" ) ?></div>
							<div class="tve_ed_btn tve_btn_text tve_click" data-ctrl="controls.shadow.size" data-size="large" style="display: inline-block;"><?php echo __( "Large", "thrive-cb" ) ?></div>
						</li>
						<li class="tve_no_hover tve_no_click tve_clearfix" style="text-align: center">
							<div class="tve_text tve_slider_config" data-value="0" data-min-value="0"
							     data-handler="void_handler"
							     data-max-value="100"
							     data-step="2"
							     data-callback="function:controls.shadow.outset_slider"
							     data-property="">

								<div class="tve_slider" style="max-width: 200px; margin: 0 auto">
									<div class="tve_slider_element tve_slider_shadow tve_outer"></div>
								</div>

								<div class="clear"></div>
							</div>
						</li>
						<li class="tve_no_hover tve_no_click tve_arrow_group" style="height: auto; line-height: 1;padding: 10px 0;text-align: center">
							<div class="tve_icm tve-ic-arrow-up tve_click" data-ctrl="controls.shadow.position" data-coords="top" title="Top"></div>
							<div class="tve_clear"></div>
							<div class="tve_icm tve-ic-square-o tve_click" data-ctrl="controls.shadow.position" data-coords="center" title="<?php echo __( "Middle / Center", "thrive-cb" ) ?>"></div>
							<div class="tve_clear"></div>
							<div class="tve_icm tve-ic-arrow-down tve_click" data-ctrl="controls.shadow.position" data-coords="bottom" title="<?php echo __( "Bottom", "thrive-cb" ) ?>"></div>
						</li>
						<li class="tve_no_hover tve_no_click" style="text-align: center;">
							<div style="display: inline-block" class="tve_ed_btn tve_btn_text tve_click tve_center" data-ctrl="controls.shadow.clear"><?php echo __( "Clear External Shadow", "thrive-cb" ) ?></div>
						</li>
						<li class="tve_no_hover tve_no_click">&nbsp;</li>
					</ul>
					<p><?php echo __( "Please note - You can use the color picker tool to set the color of the internal and external shadow", "thrive-cb" ) ?></p>
				</div>
			</div>
		</div>
	</li>
</ul>