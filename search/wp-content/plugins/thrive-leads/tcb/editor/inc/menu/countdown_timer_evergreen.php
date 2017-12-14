<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Countdown Timer Evergreen options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php $has_custom_colors = true;
	include dirname( __FILE__ ) . '/_custom_colors.php' ?>
	<li class="tve_ed_btn tve_btn_text" data-multiple-hide>
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?></span><span
				class="tve_caret tve_icm tve_left" id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li id="tve_countdown_1" class="tve_click" data-cls="tve_countdown_1" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
						</li>
						<li id="tve_countdown_2" class="tve_click" data-cls="tve_countdown_2" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
						</li>
						<li id="tve_countdown_3" class="tve_click" data-cls="tve_countdown_3" data-ctrl="controls.click.add_class">
							<?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_text tve_firstOnRow" data-multiple-hide>
		<label class="tve_text">
			<?php echo __( "Days", "thrive-cb" ) ?>
			<select class="tve_change" id="ct_day">
				<?php for ( $i = 0; $i <= 30; $i ++ ) : ?>
					<option value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php endfor ?>
			</select>
		</label>
		&nbsp;
		<label class="tve_text">
			<?php echo __( "Hours", "thrive-cb" ) ?>
			<select class="tve_change" id="ct_hour">
				<?php for ( $i = 0; $i < 24; $i ++ ) : ?>
					<option value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php endfor ?>
			</select>
		</label>
		&nbsp;
		<label class="tve_text">
			<?php echo __( "Minute", "thrive-cb" ) ?>
			<select class="tve_change" id="ct_min">
				<?php for ( $i = 0; $i < 60; $i ++ ) : ?>
					<option value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php endfor ?>
			</select>
		</label>
		&nbsp;
		<label class="tve_text">
			<?php echo __( "Seconds", "thrive-cb" ) ?>
			<select class="tve_change" id="ct_sec">
				<?php for ( $i = 0; $i < 60; $i ++ ) : ?>
					<option value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php endfor ?>
			</select>
		</label>
	</li>
	<li class="tve_ed_btn tve_btn_text" data-multiple-hide>
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Starts again after", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_large tve_dark tve_clearfix" style="min-width: 400px">
					<ul>
						<li class="tve_no_hover tve_no_click tve_countdown_time">
							<label class="tve_text">
								<?php echo __( "Days", "thrive-cb" ) ?>
								<select class="tve_change" id="ct_expday">
									<?php for ( $i = 0; $i < 30; $i ++ ) : ?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php endfor ?>
								</select>
							</label>
							&nbsp;
							<label class="tve_text">
								<?php echo __( "Hours", "thrive-cb" ) ?>
								<select class="tve_change" id="ct_exphour">
									<?php for ( $i = 0; $i < 24; $i ++ ) : ?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php endfor ?>
								</select>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<input type="checkbox" id="ct_no_restart" class="tve_change" data-ctrl="controls.change.countdown_no_restart">
							<label for="ct_no_restart"><?php echo __( "Never Restart", "thrive-cb" ) ?></label>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
	<li class="tve_clear" data-multiple-hide></li>
	<li class="tve_btn_text tve_firstOnRow" data-multiple-hide>
		<label class="tve_text">
			<?php echo __( "Text to show on complete", "thrive-cb" ) ?> <input type="text" class="tve_change" id="ct_text"/>
		</label>
	</li>
	<?php $css_selector = '.sc_timer_content';
	include dirname( __FILE__ ) . '/_margin.php' ?>
	<li class="tve_ed_btn tve_btn_icon">
		<span class="tve_icm tve-ic-paragraph-left tve_click" id="countdown_timer_align_left" data-align="left"></span>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<span class="tve_icm tve-ic-paragraph-center tve_click" id="countdown_timer_align_center" data-align="center"></span>
	</li>
	<li class="tve_ed_btn tve_btn_icon">
		<span class="tve_icm tve-ic-paragraph-right  tve_click" id="countdown_timer_align_right" data-align="right"></span>
	</li>
	<li class="tve_ed_btn tve_btn_text tve_center btn_alignment tve_click" id="countdown_timer_align_none" data-align="none"><?php echo __( "None", "thrive-cb" ) ?></li>
	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Translations", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_left"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu tve_large tve_dark tve_clearfix" style="min-width: 200px">
					<ul>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_small"><?php echo __( "Days", "thrive-cb" ) ?></span>
								<input type="text" placeholder="<?php echo __( "Days", "thrive-cb" ) ?>" class="tve_change tve_input_translatable"
								       data-ctrl="controls.change.translate" data-args=".tve_t_day .t-caption"/>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_small"><?php echo __( "Hour", "thrive-cb" ) ?></span>
								<input type="text" placeholder="<?php echo __( "Hour", "thrive-cb" ) ?>" class="tve_change tve_input_translatable"
								       data-ctrl="controls.change.translate" data-args=".tve_t_hour .t-caption"/>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_small"><?php echo __( "Minutes", "thrive-cb" ) ?></span>
								<input type="text" placeholder="<?php echo __( "Minutes", "thrive-cb" ) ?>" class="tve_change tve_input_translatable"
								       data-ctrl="controls.change.translate" data-args=".tve_t_min .t-caption"/>
							</label>
						</li>
						<li class="tve_no_hover tve_no_click">
							<label class="tve_text">
								<span class="tve_label_spacer tve_small"><?php echo __( "Seconds", "thrive-cb" ) ?></span>
								<input type="text" placeholder="<?php echo __( "Seconds", "thrive-cb" ) ?>" class="tve_change tve_input_translatable"
								       data-ctrl="controls.change.translate" data-args=".tve_t_sec .t-caption"/>
							</label>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
</ul>