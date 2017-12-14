<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Responsive video", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php
	$extra_attr = 'data-apply-to="[youtube|wistia|vimeo|self]" data-multiple-hide';
	$btn_class  = "responsive_video_option";
	?>
	<?php $has_custom_colors = true;
	$hide_default_colors     = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_btn_text tve_firstOnRow" data-multiple-hide>
		<label class="tve_text">
			<?php echo __( "Video Type", "thrive-cb" ) ?>
			<select class="tve_change" id="responsive_video_type">
				<option value="youtube">YouTube</option>
				<option value="vimeo">Vimeo</option>
				<option value="wistia">Wistia</option>
				<option value="self">Self Hosted</option>
			</select>
		</label>
		&nbsp;
	</li>
	<li class="tve_btn_text tve_firstOnRow responsive_video_option" data-apply-to="[wistia]" data-multiple-hide>
		<label class="tve_text">
			<select class="tve_change" id="responsive_video_embed_type" data-ctrl="controls.responsive_video.embed_type">
				<option value="inline"><?php echo __( "Inline Embed", "thrive-cb" ) ?></option>
				<option value="popover"><?php echo __( "Popover Embed", "thrive-cb" ) ?></option>
			</select>
		</label>
		&nbsp;
	</li>
	<?php include dirname( __FILE__ ) . '/_margin.php'; ?>

	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "No Style", "thrive-cb" ) ?></span>
			<span id="sub_02" class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn" style="width: 482px">
				<div class="tve_sub active_sub_menu" style="width: 100%; box-sizing: border-box;">
					<ul class="tve_clearfix">
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_white_frame" data-cls="rv_style_white_frame" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "White Frame", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_gray_frame" data-cls="rv_style_gray_frame" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "Gray Frame", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_dark_frame" data-cls="rv_style_dark_frame" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "Dark Frame", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_light_frame" data-cls="rv_style_light_frame" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "Light Frame", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_lifted_style1" data-cls="rv_style_lifted_style1" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "1" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_lifted_style2" data-cls="rv_style_lifted_style2" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "2" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_lifted_style3" data-cls="rv_style_lifted_style3" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "3" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_lifted_style4" data-cls="rv_style_lifted_style4" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "4" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_lifted_style5" data-cls="rv_style_lifted_style5" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "5" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_lifted_style6" data-cls="rv_style_lifted_style6" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo sprintf( __( "Lifted Style %s", 'thrive-cb' ), "6" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_grey_monitor" data-cls="rv_style_grey_monitor" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "Grey Monitor", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_black_monitor" data-cls="rv_style_black_monitor" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "Black Monitor", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_black_tablet" data-cls="rv_style_black_tablet" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "Black Tablet", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_style_white_tablet" data-cls="rv_style_white_tablet" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "White Tablet", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_style tve_ed_btn tve_btn_text tve_left clearfix tve_click" data-cls="rv_style_none" data-ctrl="controls.click.add_class">
							<div class="rv_style_image"></div>
							<div><?php echo __( "No Style", "thrive-cb" ) ?></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_btn_text" data-multiple-hide>
		<label class="tve_text" for="responsive_video_url">
			<?php echo __( "Video URL", "thrive-cb" ) ?> <input type="text" class="tve_change" id="responsive_video_url"/>
		</label>
	</li>

	<li class="tve_text clearfix responsive_video_option" data-apply-to="[self]" data-multiple-hide><span class="tve_tooltip" data-title="The supported video formats are: 'mp4', 'webm', 'ogv'" data-cls="tve_btn1">?</span></li>
	<li class="clearfix responsive_video_option tve_btn_text" data-apply-to="[youtube|wistia]" data-multiple-hide>
		<label for="" class="tve_text">
			<?php echo __( "Video Start Time", "thrive-cb" ) ?>
			<input type="text" size="2" class="tve_change" data-ctrl="controls.responsive_video.start_time_changed" id="responsive_video_start_min_time"/> <?php echo __( "mins", "thrive-cb" ) ?>
			<input type="text" size="2" class="tve_change" data-ctrl="controls.responsive_video.start_time_changed" id="responsive_video_start_sec_time"/> <?php echo __( "secs", "thrive-cb" ) ?>
		</label>
	</li>

	<li class="clearfix responsive_video_option" data-apply-to="[youtube]" data-multiple-hide>
		<div class="tve_text tve_left">
			<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="rv_option_rel"/>
			<label for="rv_option_rel" class="tve_left"><?php echo __( "Hide related videos", "thrive-cb" ) ?></label>
			<div class="tve_clear"></div>
		</div>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[youtube|vimeo]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="rv_option_modestbranding"/>
		<label for="rv_option_modestbranding" class="tve_left"><?php echo __( "Auto-hide Logo", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[wistia]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" data-ctrl="controls.responsive_video.play_bar" type="checkbox" id="rv_option_play_bar"/>
		<label for="rv_option_play_bar" class="tve_left"><?php echo __( "Play bar", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[youtube|self]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="rv_option_controls"/>
		<label for="rv_option_controls" class="tve_left"><?php echo __( "Auto-hide player controls", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[wistia]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" data-ctrl="controls.responsive_video.onload_controls" type="checkbox" id="rv_option_onload_controls"/>
		<label for="rv_option_onload_controls" class="tve_left"><?php echo __( "Controls visible on load", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[youtube|vimeo]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="rv_option_showinfo"/>
		<label for="rv_option_showinfo" class="tve_left"><?php echo __( "Hide video title bar", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[youtube|vimeo|self]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="rv_option_autoplay"/>
		<label for="rv_option_autoplay" class="tve_left"><?php echo __( "Autoplay", "thrive-cb" ) ?></label>
	</li>
	<li class="tve_text clearfix responsive_video_option" data-apply-to="[youtube|wistia]" data-multiple-hide>
		<input class="tve_change tve_left tve_checkbox_bottom" type="checkbox" id="rv_option_fs"/>
		<label for="rv_option_fs" class="tve_left"><?php echo __( "Hide full-screen button", "thrive-cb" ) ?></label>
	</li>
	<li class="clearfix responsive_video_option tve_btn_text" data-apply-to="[wistia]" data-multiple-hide id="tve_thumbnail_size_options">
		<label for="" class="tve_text">
			<?php echo __( "Video Thumbnail size", "thrive-cb" ) ?>
			<input type="text" size="2" class="tve_change" data-ctrl="controls.responsive_video.video_size_changed" id="rv_option_video_width"/>&nbsp;x&nbsp;
			<input type="text" size="2" class="tve_change" data-ctrl="controls.responsive_video.video_size_changed" id="rv_option_video_height"/>&nbsp;<?php echo __( "Pixels", "thrive-cb" ) ?>&nbsp;
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_click" id="tve_video_overlay" data-apply-to="[youtube|wistia|vimeo]" data-multiple-hide>
		<?php echo __( "Add Video Thumbnail", "thrive-cb" ) ?>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_click" id="tve_remove_video_overlay" data-apply-to="[youtube|wistia|vimeo]" data-multiple-hide>
		<?php echo __( "Remove Video Thumbnail", "thrive-cb" ) ?>
	</li>
	<li class="tve_ed_btn tve_btn_text" id="tve_add_play_button">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "No Play Button", "thrive-cb" ) ?></span>
			<span id="sub_03" class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn" style="width: 482px">
				<div class="tve_sub active_sub_menu" style="width: 100%; box-sizing: border-box;">
					<ul class="tve_clearfix">
						<li class="rv_play tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_button_rounded_rectangle_light" data-btn="play" data-cls="rv_button_rounded_rectangle_light" data-ctrl="controls.click.add_class">
							<div class="rv_button_image"></div>
							<div><?php echo __( "Rounded Rectangle (light)", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_play tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_button_rounded_rectangle_dark" data-btn="play" data-cls="rv_button_rounded_rectangle_dark" data-ctrl="controls.click.add_class">
							<div class="rv_button_image"></div>
							<div><?php echo __( "Rounded Rectangle (dark)", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_play tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_button_circular_dark" data-btn="play" data-cls="rv_button_circular_dark" data-ctrl="controls.click.add_class">
							<div class="rv_button_image"></div>
							<div><?php echo __( "Circular (dark)", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_play tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_button_circular_outline_dark" data-btn="play" data-cls="rv_button_circular_outline_dark" data-ctrl="controls.click.add_class">
							<div class="rv_button_image"></div>
							<div><?php echo __( "Circular Outline (dark)", "thrive-cb" ) ?></div>
						</li>
						<li class="rv_play tve_ed_btn tve_btn_text tve_left clearfix tve_click" id="rv_button_simple_play_light" data-btn="play" data-cls="rv_button_simple_play_light" data-ctrl="controls.click.add_class">
							<div class="rv_button_image"></div>
							<div><?php echo sprintf( __( "Simple Play (dark)", 'thrive-cb' ) ) ?></div>
						</li>
						<li class="rv_play tve_ed_btn tve_btn_text tve_left clearfix tve_click" data-cls="rv_button_none" data-btn="play" data-ctrl="controls.click.add_class">
							<div class="rv_button_image"></div>
							<div><?php echo __( "No Play Button", "thrive-cb" ) ?></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
</ul>