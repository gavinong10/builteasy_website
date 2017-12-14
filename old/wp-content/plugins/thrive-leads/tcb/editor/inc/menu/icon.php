<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Icon options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
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
			<input id="icon_border_width" class="tve_change tve_css_applier" value="0" type="text" size="3" data-css-property="border-width" data-suffix="px"
			       data-size="1"> px
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<span class="tve_icm tve-ic-paragraph-left tve_click tve_icon_align" data-cls="alignleft"></span>
	</li>
	<li class="tve_ed_btn tve_btn_icon tve_firstOnRow">
		<span class="tve_icm tve-ic-paragraph-center tve_click tve_icon_align" data-cls="aligncenter"></span>
	</li>
	<li class="tve_ed_btn tve_btn_icon tve_firstOnRow">
		<span class="tve_icm tve-ic-paragraph-right tve_click tve_icon_align" data-cls="alignright"></span>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_center tve_click tve_icon_align tve_firstOnRow tve_hide_cb" data-cls=""><?php echo __( "None", "thrive-cb" ) ?></li>

	<?php $margin_config['hide_padding'] = true;
	$css_selector                        = '_parent::.thrv_icon';
	include dirname( __FILE__ ) . '/_margin.php' ?>

	<?php include dirname( __FILE__ ) . '/_quick_link.php' ?>
	<!-- this only shows when the user clicks on a hyperlink -->
	<li class="tve_text tve_slider_config tve_firstOnRow tve_hide_cb" data-value="30" data-min-value="10"
	    data-max-value="available"
	    data-property="font-size"
	    data-callback="icon_size"
	    data-selector=".tve_sc_icon"
	    data-input-selector="#icon_size_input">
		<label for="icon_size_input" class="tve_left">&nbsp;<?php echo __( "Icon size", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left" style="max-width: 200px;">
			<div class="tve_slider_element" id="tve_icon_size_slider"></div>
		</div>
		<input class="tve_left" type="text" id="icon_size_input" value="30" size="3"> px

		<div class="clear"></div>
	</li>
	<li class="tve_text tve_slider_config tve_firstOnRow tve_hide_cb" data-value="0" data-min-value="0"
	    data-max-value="200"
	    data-property="padding"
	    data-selector=".tve_sc_icon"
	    data-input-selector="#icon_padding_input">
		<label for="icon_padding_input" class="tve_left">&nbsp;<?php echo __( "Padding size", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left" style="max-width: 200px;">
			<div class="tve_slider_element" id="tve_icon_padding_slider"></div>
		</div>
		<input class="tve_left" type="text" id="icon_padding_input" value="0" size="3"> px

		<div class="clear"></div>
	</li>
	<?php $border_radius_selector = '.tve_sc_icon';
	$border_radius_callback       = 'icon';
	$max_width                    = '300';
	include dirname( __FILE__ ) . '/_border_radius.php' ?>

	<li id="lb_icon" data-load="1" data-wpapi="lb_icon"
	    class="tve_ed_btn tve_btn_text tve_btn tve_click tve_icon_ctrl" data-ctrl="controls.lb_open" data-multiple-hide><?php echo __( "Change Icon", "thrive-cb" ) ?>
	</li>
	<li class="tve_text tve_text_ctrl" data-multiple-hide>
		<label class="tve_left"><?php echo __( "Icon text", "thrive-cb" ) ?> &nbsp;</label><input class="tve_change tve_left" id="tve_icon_text" type="text" data-ctrl="controls.change.icon_text" placeholder="<?php echo __( "Icon text", "thrive-cb" ) ?>" value="1" size="2">
	</li>
	<li class=""><input type="text" class="element_class tve_change" data-ctrl="controls.change.cls" placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>

	<?php $li_custom_class = 'tve_hide_cb';
	include dirname( __FILE__ ) . '/_event_manager.php' ?>
	<li class="tve_clear"></li>
</ul>