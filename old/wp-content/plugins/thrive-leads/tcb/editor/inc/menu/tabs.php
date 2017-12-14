<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Tabs options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_add_highlight" data-multiple-hide>
		<div id="add_tab" class="tve_ed_btn tve_btn_text tve_center btn_alignment tve_left tve_click"><?php echo __( "Add New Tab", "thrive-cb" ) ?>
		</div>
	</li>
	<li id="vtabs_menu_width" class="tve_text tve_slider_config" data-value="200" data-min-value="50"
	    data-max-value="500"
	    data-input-selector="#vtabs_width_input"
	    data-property="width"
	    data-callback="tabs"
	    data-selector="> .tve_scT > ul" data-multiple-hide>
		<label for="vtabs_width_input" class="tve_left">&nbsp;<?php echo __( "Tabs width", "thrive-cb" ) ?></label>

		<div class="tve_slider tve_left">
			<div class="tve_slider_element" id="s_vtabs_menu_width"></div>
		</div>
		<input class="tve_left width50" type="text" id="vtabs_width_input" value="200px">

		<div class="clear"></div>
	</li>
	<li class="tve_text tve_firstOnRow" data-multiple-hide><?php echo __( "Selected tab", "thrive-cb" ) ?></li>
	<li class="tve_ed_btn tve_btn_text tve_firstOnRow" data-multiple-hide>
		<div class="tve_option_separator">
			<span class="tve_ind tve_left" id="tabs_default_selected" data-default="Selected tab"><?php echo __( "Selected tab", "thrive-cb" ) ?></span><span class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_medium tve_dark">
					<ul id="tve_tabs_selected">

					</ul>
				</div>
			</div>
		</div>
	</li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>