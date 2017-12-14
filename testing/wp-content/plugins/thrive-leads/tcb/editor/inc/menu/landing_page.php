<span class="tve_options_headline"><?php echo __( "Landing Page Settings", "thrive-cb" ) ?></span>
<span class="tve_options_headline" style="font-size: 13px;"><?php echo __( "Background", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<i class="tve_icm tve-ic-color-lens tve_left"></i><span
				class="tve_caret tve_icm tve_left" id="sub_01"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu color_selector">
					<div class="tve_color_picker tve_left">
						<span class="tve_options_headline tve_color_title"><?php echo __( "Custom Colors", "thrive-cb" ) ?></span>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
	</li>
	<?php if ( ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_clear"></li>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
			<div class="tve_option_separator">
				<span class="tve_ind tve_left"><?php echo __( "Background pattern", "thrive-cb" ) ?></span>
				<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

				<div class="tve_clear"></div>
				<div class="tve_sub_btn" style="width: 700px;left:auto;right:-9px">
					<div class="tve_sub active_sub_menu" style="">
						<ul class="tve_clearfix">
							<li style="display: none"></li>
							<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-pattern="2" data-plugin="tve_lp">
								<span class="tve_section_colour tve_left tve_icm tve-ic-upload" style="margin: 0"></span>
								<span class="tve_left"><?php echo __( "Load...", "thrive-cb" ) ?></span>
							</li>
							<?php foreach ( $page_section_patterns as $i => $_image ) : ?>
								<?php $_uri = $template_uri . '/images/patterns/' . $_image . '.png' ?>
								<li class="tve_ed_btn tve_btn_text tve_left tve_section_color_change clearfix tve_click" data-ctrl="controls.click.change_pattern" data-plugin="tve_lp" data-pattern="1">
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
	<li class="tve_clear"></li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click" id="tve_lp_bg_image"><?php echo __( "Background image...", "thrive-cb" ) ?>
	</li>
	<li class="tve_clear"></li>
	<li class="tve_text clearfix tve_firstOnRow">
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="tve_lp_bg_fixed"
		       value="1"><label
			for="tve_lp_bg_fixed" class="tve_left"> <?php echo __( "Static image", "thrive-cb" ) ?></label> &nbsp;
	</li>
	<li class="tve_clear"></li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
	    id="tve_lp_clear_bg_color"><?php echo __( "Clear background color", "thrive-cb" ) ?>
	</li>
	<?php if ( ! empty( $page_section_patterns ) ) : ?>
		<li class="tve_clear"></li>
		<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
		    id="tve_lp_clear_bg_pattern"><?php echo __( "Clear background pattern", "thrive-cb" ) ?>
		</li>
	<?php endif ?>
	<li class="tve_clear"></li>
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text tve_center tve_click"
	    id="tve_lp_clear_bg_image"><?php echo __( "Clear background image", "thrive-cb" ) ?>
	</li>
	<li class="tve_clear"></li>
</ul>
<div class="tve_clear" style="height: 10px;"></div>
<span class="tve_options_headline" style="font-size: 13px;"><?php echo __( "Font Settings", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_click tve_ed_btn tve_btn_text" data-ctrl="function:landing_fonts.initElement"><?php echo __( "Landing Page Fonts", "thrive-cb" ) ?></li>
	<li class="tve_clear"></li>
</ul>
<div class="tve_clear" style="height: 10px;"></div>
<span class="tve_options_headline" style="font-size: 13px;">Custom scripts</span>
<ul class="tve_menu">
	<li class="tve_click tve_ed_btn tve_btn_text" data-ctrl="controls.lb_open" id="lb_global_scripts" data-load="1"><?php echo __( "Setup custom scripts...", "thrive-cb" ) ?></li>
	<li class="tve_clear"></li>
</ul>
<div class="tve_clear" style="height: 10px;"></div>
<span class="tve_options_headline" style="font-size: 13px;"><?php echo __( "Other settings", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_firstOnRow tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Advanced options", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn" style="width: 400px;left:auto;right:-9px">
				<div class="tve_sub active_sub_menu" style="">
					<ul class="tve_clearfix">
						<li class="tve_no_click">
							<label for="tve_strip_css">
								<input type="checkbox" id="tve_strip_css" class="tve_change" data-ctrl="controls.set_global_flag" data-flag="do_not_strip_css" value="1"/>
								<?php echo __( "Do not strip out Custom CSS from the &lt;head&gt; section", "thrive-cb" ) ?>
							</label><br>
							<div style="font-weight:normal;color:#111; line-height: 1;">
								<p><?php echo __( "The Content Builder will strip out any Custom CSS from the &lt;head&gt; section from all Landing Pages built with it.", "thrive-cb" ) ?></p>
								<p><?php echo __( "Usually, this is extra CSS that is not needed throughout the Lading Page.", "thrive-cb" ) ?></p>
								<p><?php echo __( "By ticking the checkbox above, you will disable this functionality, and all Custom CSS will be included.", "thrive-cb" ) ?></p>
								<p><?php echo __( "Please keep in mind that including this Custom CSS might prevent some of the above controls to function properly, such as: background color, background image etc.", "thrive-cb" ) ?></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
</ul>
<div class="tve_clearfix"></div>