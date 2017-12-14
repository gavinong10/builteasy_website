<div class="tve-sp"></div>
<div class="tvd-row tvd-collapse">
	<div class="tvd-col tvd-s4">
		<div class="tvd-input-field">
			<input id="aweber_tags" type="text" class="tve-api-extra tve_lightbox_input tve_lightbox_input_inline" name="aweber_tags" value="<?php echo ! empty( $data['tags'] ) ? $data['tags'] : '' ?>" size="40"/>
			<label for="aweber_tags"><?php echo __( 'Tags', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
	</div>
	<p><?php echo __( "Comma-separated lists of tags to assign to a new contact in Aweber", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
</div>
