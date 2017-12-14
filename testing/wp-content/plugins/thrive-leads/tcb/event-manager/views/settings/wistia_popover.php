<?php $animation_classes = ''; /* render specific settings for Wistia Popover actions */

if ( $this->success_message ) : ?>
	<br>
	<div class="tve_message tve_success" id="tve_landing_page_msg"><?php echo $this->success_message ?></div>
<?php endif ?>
<br>
<h5>
	<?php echo __( "Open Wistia Popover Settings", "thrive-cb" ) ?>
</h5>
<div class="tve_lightbox_border_box">
	<label class="tve_lightbox_label" for="responsive_video_url">
		<?php echo __( "Video URL", "thrive-cb" ) ?>
	</label>
	<input type="text" name="event_video_url" id="tve_event_video_url" class="tve_lightbox_input tve_lightbox_input_big tve_ctrl_validate" data-validators="required;validWistiaUrl;" value="<?php echo isset( $this->config['event_video_url'] ) ? $this->config['event_video_url'] : ""; ?>"/>
</div>
<br>
<label class="tve_lightbox_label">
	<?php echo __( "Video Color", "thrive-cb" ) ?>
</label>
<span class="tve_colour_pickers tve_wistia-picker">
	<input type="text" class="tve_event_video_color" name="event_video_color"
	       value="<?php echo isset( $this->config['event_video_color'] ) ? $this->config['event_video_color'] : "#000000"; ?>">
</span>
<br><br>
<label class="tve_lightbox_label">
	<?php echo __( "Video Start Time", "thrive-cb" ) ?>
</label>
<input type="text" size="2" name="event_start_min_time" class="tve_lightbox_input tve_lightbox_small_input"
       value="<?php echo isset( $this->config['event_start_min_time'] ) ? $this->config['event_start_min_time'] : ""; ?>"/>&nbsp;<?php echo __( "mins", "thrive-cb" ) ?>&nbsp;
<input type="text" size="2" name="event_start_sec_time" class="tve_lightbox_input tve_lightbox_small_input"
       value="<?php echo isset( $this->config['event_start_sec_time'] ) ? $this->config['event_start_sec_time'] : ""; ?>"/>&nbsp;<?php echo __( "secs", "thrive-cb" ) ?>&nbsp;
<br><br>
<div class="tve_lightbox_input_holder">
	<input name="event_option_play_bar" type="checkbox" id="event_option_play_bar" <?php if ( isset( $this->config['event_option_play_bar'] ) && $this->config['event_option_play_bar'] == 'on' ) {
		echo "checked='checked'";
	} ?> />
	<label for="event_option_play_bar" class="tve_left"><?php echo __( "Play bar", "thrive-cb" ) ?></label>
</div>
<div class="tve_lightbox_input_holder">
	<input name="event_option_hide_controls" type="checkbox" id="event_option_hide_controls" <?php if ( isset( $this->config['event_option_hide_controls'] ) && $this->config['event_option_hide_controls'] == 'on' ) {
		echo "checked='checked'";
	} ?> />
	<label for="event_option_hide_controls" class="tve_left"><?php echo __( "Auto-hide player controls", "thrive-cb" ) ?></label>
</div>
<div class="tve_lightbox_input_holder">
	<input name="event_option_onload" type="checkbox" id="event_option_onload" <?php if ( isset( $this->config['event_option_onload'] ) && $this->config['event_option_onload'] == 'on' ) {
		echo "checked='checked'";
	} ?> />
	<label for="event_option_onload" class="tve_left"><?php echo __( "Controls visible on load", "thrive-cb" ) ?></label>
</div>
<div class="tve_lightbox_input_holder">
	<input name="event_option_fs" type="checkbox" id="event_option_fs" <?php if ( isset( $this->config['event_option_fs'] ) && $this->config['event_option_fs'] == 'on' ) {
		echo "checked='checked'";
	} ?>/>
	<label for="event_option_fs" class="tve_left"><?php echo __( "Hide full-screen button", "thrive-cb" ) ?></label>
</div>

<input name="event_option_uniq" type="hidden" value="<?php echo isset( $this->config['event_option_uniq'] ) ? $this->config['event_option_uniq'] : substr( md5( microtime() ), 0, 10 );; ?>"/>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('.tve_event_video_color').wpColorPicker();
	});
</script>


