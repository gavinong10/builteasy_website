<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Styled List options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_ul1" class="tve_click" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?></li>
						<li id="tve_ul2" class="tve_click" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?></li>
						<li id="tve_ul3" class="tve_click" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?></li>
						<li id="tve_ul4" class="tve_click" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?></li>
						<li id="tve_ul5" class="tve_click" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "5" ) ?></li>
						<li id="tve_ul6" class="tve_click" data-ctrl="controls.click.add_class"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "6" ) ?></li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<?php include dirname( __FILE__ ) . '/_line_height.php' ?>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>