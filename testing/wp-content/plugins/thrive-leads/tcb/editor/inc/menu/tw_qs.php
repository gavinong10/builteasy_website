<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Quote Share options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_ed_btn tve_btn_text tve_firstOnRow">
		<div class="tve_option_separator">
			<i class="tve_icm tve-ic-color-lens tve_left"></i><span class="tve_caret tve_icm tve_left" id="sub_01"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu color_selector" id="tve_sub_01_s">
					<ul class="tve_default_colors tve_left">
						<li class="tve_color_title"><span class="tve_options_headline"><?php echo __( "Default Colors", "thrive-cb" ) ?></span></li>
						<li class="tve_clear"></li>
						<li class="tve_blue"><a href="#"></a></li>
					</ul>
					<div class="tve_color_picker tve_left">
                                <span class="tve_options_headline tve_color_title">
                                    <?php echo __( "Custom Colors", "thrive-cb" ) ?>
                                </span>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li>
		<label>
			Via @<input id="qs_tw_via" class="tve_text tve_change" type="text" placeholder="<?php echo __( "username", "thrive-cb" ) ?>"/>
		</label>
	</li>
	<li>
		<label><input id="qs_tw_use_custom" type="checkbox" class="tve_change" data-ctrl="controls.change.qs_tw_use_custom_url" value="1"> <?php echo __( "Use Custom URL", "thrive-cb" ) ?></label>
	</li>
	<li id="qs_tw_custom_url">
		<label>
			<input id="qs_tw_url" class="tve_text tve_change" data-ctrl="controls.change.qs_tw_url" type="text" placeholder="<?php echo __( "Custom URL", "thrive-cb" ) ?>"/>
		</label>
	</li>
</ul>