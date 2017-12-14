<form action="" method="post">


	<div class="tvd-row">
		<div class="tvd-col tvd-s12 tvd-m3 tvd-l2">
			<?php echo __( "Upload Icon Pack", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</div>
		<div class="tvd-col tvd-s12 tvd-m9 tvd-l10">

			<input type="hidden" name="tve_save_icon_pack" value="1">
			<input type="text" value="<?php echo $this->icon_pack_name ?>" id="tve_icon_pack_file" name="tve_icon_pack_url" class="thrive_options" readonly="readonly">
			<input type="hidden" value="<?php echo $this->icon_pack_id ?>" id="tve_icon_pack_file_id" name="attachment_id">

			<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-green" id="tve_icon_pack_upload" href="javascript:void(0)">
				<i class="tvd-icon-plus"></i> <?php echo __( "Upload", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>

			<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-red" id="tve_icon_pack_remove" href="javascript:void(0)">
				<i class="tvd-icon-remove"></i> <?php echo __( "Remove", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>
		</div>

	</div>
	<div class="tvd-row">
		<div class="tvd-col tvd-s12 tvd-m3 tvd-l2">
			<?php echo __( "Save options", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</div>
		<div class="tvd-col tvd-s12 tvd-m9 tvd-l10">
			<input type="submit" id="tve_icon_pack_save" value="<?php echo __( "Save and Generate Icons", TVE_DASH_TRANSLATE_DOMAIN ) ?>" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-blue"/>
		</div>
	</div>
</form>