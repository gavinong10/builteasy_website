<?php
$captcha_api       = Thrive_Dash_List_Manager::credentials( 'recaptcha' );
$captcha_available = ! empty( $captcha_api['site_key'] );
?>
<div class="tve_lead_captcha_settings">
	<div class="tve_lightbox_input_holder">
		<input class="tve_lg_validation_options tve_change"
		       type="checkbox"
		       name="tve_api_use_captcha"
		       data-ctrl="function:auto_responder.use_captcha_changed" id="tve-captcha"
			<?php echo $captcha_available ? '' : ' disabled'; ?>
		>
		<label for="tve-captcha">
			<?php
			echo __( 'Add Captcha to Prevent Spam Signups', TVE_DASH_TRANSLATE_DOMAIN );
			if ( ! $captcha_available ) {
				echo '(<a href="' . admin_url( 'admin.php?page=tve_dash_api_connect' ) . '">' . __( 'Requires integration with Google ReCaptcha', TVE_DASH_TRANSLATE_DOMAIN ) . ')</a>';
			}
			?>
		</label>
	</div>
	<div class="tve_captcha_options" style="display:none;">
		<label><?php echo __( 'Theme', TVE_DASH_TRANSLATE_DOMAIN ); ?>:</label>

		<div class="tve_lightbox_select_holder tve_captcha_option">
			<select class="tve_captcha_theme tve_change" data-option="captcha_theme" data-ctrl="function:auto_responder.captcha_option_changed">
				<option value="light"><?php echo __( 'Light', TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
				<option value="dark"><?php echo __( 'Dark', TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
			</select>
		</div>


		<label><?php echo __( 'Type', TVE_DASH_TRANSLATE_DOMAIN ); ?>:</label>

		<div class="tve_lightbox_select_holder tve_captcha_option">
			<select class="tve_captcha_type tve_change" data-option="captcha_type" data-ctrl="function:auto_responder.captcha_option_changed">
				<option value="image"><?php echo __( 'Image', TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
				<option value="audio"><?php echo __( 'Audio', TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
			</select>
		</div>


		<label><?php echo __( 'Size', TVE_DASH_TRANSLATE_DOMAIN ); ?>:</label>

		<div class="tve_lightbox_select_holder tve_captcha_option">
			<select class="tve_captcha_size tve_change" data-option="captcha_size" data-ctrl="function:auto_responder.captcha_option_changed">
				<option value="normal"><?php echo __( 'Normal', TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
				<option value="compact"><?php echo __( 'Compact', TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
			</select>
		</div>

	</div>
</div>