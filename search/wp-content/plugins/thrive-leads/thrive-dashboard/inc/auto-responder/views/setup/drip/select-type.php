<div class="tve-sp"></div>
<h6><?php echo __( 'Choose the type of Drip integration you would like to use', TVE_DASH_TRANSLATE_DOMAIN ) ?></h6>
<div class="tvd-row tvd-collapse">
	<div class="tvd-col tvd-s4">
		<?php  ?>
		<div class="tve_lightbox_select_holder tve_lightbox_input_inline tve_lightbox_select_inline tvd-input-field">
			<label for="tve-api-extra" class="tve-custom-select">
				<select class="tve-api-extra tl-api-connection-list tve_change" name="drip_type" data-ctrl="auto_responder.api.change_integration_type">
					<option
						value="list" <?php isset($data['type']) ? selected( $data['type'], 'list' ) : ''; ?>><?php echo __( 'Mailing List', TVE_DASH_TRANSLATE_DOMAIN ) ?></option>
					<option
						value="automation" <?php  isset($data['type']) ? selected( $data['type'], 'automation' ) : ''; ?>><?php echo __( 'Drip Automation', TVE_DASH_TRANSLATE_DOMAIN ) ?></option>
				</select>
			</label>
		</div>
	</div>
</div>

<br>
<p><?php echo __( '(You can select from having a Mailing List integration or use Drip Automation services which allow you to create actions from events with custom proprieties sent trough the API)', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
