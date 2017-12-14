<div class="tvd-modal-content">
	<?php if ( empty( $variation ) || empty( $form_type ) ) : ?>
		<h2 class="error"><?php echo __( 'No form could be found. Please try again', 'thrive-leads' ) ?></h2>
		<?php exit() ?>
	<?php endif ?>

	<?php
	$triggers = tve_leads_get_available_triggers( $form_type );
	$config   = empty( $variation['trigger_config'] ) ? array() : $variation['trigger_config'];
	?>

	<h4><?php echo $variation['post_title'] . ' ' . __( 'Trigger Settings', 'thrive-leads' ) ?></h4>

	<div class="tvd-v-spacer vs-2"></div>
	<div id="trigger-settings-base">
		<div class="tvd-input-field">
			<select name="trigger" id="trigger-type">
				<?php foreach ( $triggers as $key => $trigger ) : /** @var TVE_Leads_Trigger_Abstract $trigger */ ?>
					<option
						value="<?php echo $key ?>"<?php echo $variation['trigger'] == $key ? ' selected="selected"' : '' ?>><?php echo $trigger->get_title() ?></option>
				<?php endforeach ?>
			</select>
			<label for="trigger-type"><?php echo __( 'Trigger', 'thrive-leads' ) ?></label>
		</div>
		<?php foreach ( $triggers as $key => $trigger ) : ?>
			<div id="trigger-settings-<?php echo $key ?>" class="trigger-settings"<?php echo $variation['trigger'] != $key ? ' style="display:none"' : '' ?>>
				<?php if ( $key == $variation['trigger'] ) {
					$trigger->set_config( $config );
				}
				$trigger->output_settings() ?>
			</div>
		<?php endforeach ?>
	</div>

	<script type="text/javascript">
		ThriveLeads.objects.current_variation = new ThriveLeads.models.FormVariation(<?php echo json_encode($variation) ?>);
	</script>
</div>
<div class="tvd-modal-footer">
	<div class="tvd-row">
		<div class="tvd-col tvd-s12">
			<a href="javascript:void(0)"
			   class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-right tve-save-trigger"><?php echo __( 'Save', 'thrive-leads' ) ?></a>
		</div>
	</div>
</div>