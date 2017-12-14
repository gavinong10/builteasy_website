<div class="tvd-modal-content">
	<h3 class="tvd-modal-title"><?php echo $variation['post_title'] . ' ' . __( 'Position Settings', 'thrive-leads' ) ?></h3>
	<p>
		<?php echo __( 'This setting determines the position of the "' . $variation['tve_form_type_name'] . '" form".', 'thrive-leads' ) ?>

		<?php if ( $variation['tve_form_type'] == 'in_content' ): ?>
			<?php echo __( ' This type of form will be displayed only on singular pages!', 'thrive-leads' ) ?>
		<?php endif; ?>
	</p>

	<div id="position-settings-base">
		<div class="tvd-input-field">
			<select class="form-position" name="position" id="variation-position-settings" tabindex="1">
				<?php foreach ( $form_type_position['position'] as $key => $position ): ?>
					<option
						value="<?php echo $key; ?>" <?php echo $variation['position'] == $key ? ' selected="selected"' : '' ?>><?php echo $position; ?></option>
				<?php endforeach; ?>
			</select>
			<label for="variation-position-settings"><?php echo $form_type_position['label'] ?></label>
		</div>
	</div>

	<script type="text/javascript">
		ThriveLeads.objects.position_settings_model = new ThriveLeads.models.FormVariation(<?php echo json_encode($variation) ?>);
	</script>
</div>
<div class="tvd-modal-footer">
	<div class="tvd-row">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a href="javascript:void(0)" class="tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-modal-close tvd-waves-effect"
			   tabindex="3"><?php echo __( 'Cancel', 'thrive-leads' ) ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a href="javascript:void(0)" class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-right tve-save-position tvd-modal-submit"
			   tabindex="2"><?php echo __( 'Save', 'thrive-leads' ) ?></a>
		</div>
	</div>
</div>