<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Content Box options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	$extra_attr              = 'data-multiple-hide';
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<?php include dirname( __FILE__ ) . '/_shadow.php' ?>
	<li><input type="text" class="element_class tve_text tve_change" data-ctrl="controls.change.cls" placeholder="<?php echo __( "Custom class", "thrive-cb" ) ?>"></li>
	<li data-multiple-hide><input type="text" class="element_id tve_change tve_text" data-ctrl="controls.change.element_id" placeholder="<?php echo __( "Custom ID", "thrive-cb" ) ?>"></li>
	<?php $border_radius_selector = ".tve_cb,.tve_hd" ?>
	<?php include dirname( __FILE__ ) . '/_border_radius.php' ?>
	<?php include dirname( __FILE__ ) . '/_event_manager.php' ?>
	<li class="tve_ed_btn tve_btn_text tve_symbol_ctrl">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Symbol position", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li class="tve_click" data-cls="" data-size="1" data-ctrl="controls.click.add_class"><?php echo __( "Top", "thrive-cb" ) ?></li>
						<li class="tve_click" data-cls="tve_sb_bot" data-size="1" data-ctrl="controls.click.add_class"><?php echo __( "Bottom", "thrive-cb" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
</ul>