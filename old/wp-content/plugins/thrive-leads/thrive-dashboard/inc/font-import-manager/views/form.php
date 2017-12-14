<form action="" method="post" style="margin-top: 10px">

	<div class="tvd-row">
		<div class="tvd-col tvd-s12 tvd-m2 tvd-l1">
			<?php echo __( "Upload Fonts", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</div>
		<div class="tvd-col tvd-s12 tvd-m10 tvd-l11">
			<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-green" id="thrive_upload" href="javascript:void(0)">
				<i class="tvd-icon-plus"></i> <?php echo __( "Upload", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>

			<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-red" id="thrive_remove" href="javascript:void(0)">
				<i class="tvd-icon-remove"></i> <?php echo __( "Remove", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>

			<input type="text" id="thrive_attachment_name" readonly="readonly" value="<?php echo ! empty( $this->font_pack['filename'] ) ? $this->font_pack['filename'] : '' ?>">
			<input type="hidden" id="thrive_attachment_id" name="attachment_id"/>
		</div>

	</div>
	<div class="tvd-row">

		<div class="tvd-col tvd-s12 tvd-m2 tvd-l1">
			<?php echo __( "Save options", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</div>
		<div class="tvd-col tvd-s12 tvd-m10 tvd-l11">
			<input type="submit" value="<?php echo __( "Save and Generate Fonts", TVE_DASH_TRANSLATE_DOMAIN ) ?>" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-blue"/>
		</div>

	</div>

</form>
