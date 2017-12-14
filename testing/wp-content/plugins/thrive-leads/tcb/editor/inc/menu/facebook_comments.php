<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Facebook Comments", "thrive-cb" ) ?></span>
<ul class="tve_menu">

	<li class="tve_text">
		<label class="tve_text">
			<?php echo __( "Colour Scheme", "thrive-cb" ); ?>
			<select class="tve_change tve_fb_comment_field" data-field="colorscheme" data-ctrl="controls.comments_element.fb_field_change" id="tve_fb_comments_colour">
				<option value="light"><?php echo __( "Light", "thrive-cb" ); ?></option>
				<option value="dark"><?php echo __( "Dark", "thrive-cb" ); ?></option>
			</select>
		</label>
	</li>

	<li class="tve_btn_text">
		<label class="tve_text" for="tve_fb_comments_number">
			<?php echo __( "Number of comments", "thrive-cb" ) ?>
			<input value="10" type="text" data-field="numposts" data-ctrl="controls.comments_element.fb_field_change" class="tve_change tve_fb_comment_field" id="tve_fb_comments_number"/>
		</label>
	</li>

	<li class="tve_text">
		<label class="tve_text">
			<?php echo __( "Order By", "thrive-cb" ); ?>
			<select class="tve_change tve_fb_comment_field" data-field="order-by" data-ctrl="controls.comments_element.fb_field_change" id="tve_fb_comments_order">
				<option value="social"><?php echo __( "Social Popularity", "thrive-cb" ); ?></option>
				<option value="time"><?php echo __( "Oldest First", "thrive-cb" ); ?></option>
				<option value="reverse_time"><?php echo __( "Newest first", "thrive-cb" ); ?></option>
			</select>
		</label>
	</li>

	<li class="tve_btn_text">
		<label class="tve_text" for="tve_fb_comments_width">
			<?php echo __( "Max-width", "thrive-cb" ) ?>
			<input type="text" value="100%" data-field="width" data-ctrl="controls.comments_element.fb_field_change" class="tve_change tve_fb_comment_field" id="tve_fb_comments_width"/>
		</label>
	</li>

	<li class="tve_btn_text">
		<label class="tve_text">
			<?php echo __( "URL", "thrive-cb" ) ?>
			<span class="tve_tooltip" data-title='<?php echo __( 'If you leave this blank then the URL of the current piece of content will be used.  You can, however, specify a different URL to store comments against, if you prefer.', 'thrive-cb' ); ?>'>?</span>
			<input type="text" data-field="href" data-ctrl="controls.comments_element.fb_field_change" class="tve_change tve_fb_comment_field"/>
		</label>
	</li>

	<li class="tve_ed_btn tve_btn_text">
		<div class="tve_option_separator">
			<span class="tve_ind tve_left"><?php echo __( "Comments Moderators", "thrive-cb" ); ?></span>
			<span class="tve_caret tve_left tve_icm"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn tve_no_click">
				<div class="tve_sub active_sub_menu" style="min-width: 250px;">
					<div style="line-height: 1em;margin-bottom: 10px;">
						<?php echo __( "Add Facebook user ID for the people that you will like to moderate the comments.", "thrive-cb" ); ?>
					</div>

					<ul class="tve_moderator_fields">
						<li>
							<label>
								ID: <input placeholder="Profile ID" type="text" data-ctrl="controls.comments_element.fb_set_moderator_ID" class="tve_change tve_moderator_fb_id">
								<span class="tve_click" data-ctrl="controls.comments_element.fb_remove_moderator"><?php echo __( "Remove", "thrive-cb" ); ?></span>
							</label>
						</li>
					</ul>

					<ul class="tve_click" data-ctrl="controls.comments_element.fb_add_moderator_field" style="width: 50%">
						<li><b><?php echo __( "Add Moderator", "thrive-cb" ); ?></b></li>
					</ul>

					<div id="tve_moderator_field_clone" style="display: none;">
						<label>
							ID: <input placeholder="Profile ID" type="text" data-ctrl="controls.comments_element.fb_set_moderator_ID" class="tve_change tve_moderator_fb_id">
							<span class="tve_click" data-ctrl="controls.comments_element.fb_remove_moderator"><?php echo __( "Remove", "thrive-cb" ); ?></span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</li>

</ul>
