<div class="tve-custom-proprieties-container tve-api-option-group tve-api-option-group-list" <?php echo !isset( $data['type'] ) || $data['type'] == 'list' ? '' : 'style="display: none;"';?>>
	<div class="tve-sp"></div>
	<p class="tl-mock-paragraph"><?php echo __( 'Choose the type of optin you would like for the Drip integration. When choosing single opt-in the confirmation message should be disabled for the list on your GetDrip Account', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<div class="tvd-row tvd-collapse">
		<div class="tvd-col tvd-s4">
			<div class="tve_lightbox_select_holder tve_lightbox_input_inline tve_lightbox_select_inline tvd-input-field">
				<label for="tve-api-extra" class="tve-custom-select">
					<select class="tve-api-extra tl-api-connection-list" name="drip_optin">
						<option
							value="s" <?php echo $data['optin'] === 's' ? ' selected="selected"' : '' ?>><?php echo __( 'Single optin', TVE_DASH_TRANSLATE_DOMAIN ) ?></option>
						<option
							value="d" <?php echo $data['optin'] === 'd' ? ' selected="selected"' : '' ?>><?php echo __( 'Double optin', TVE_DASH_TRANSLATE_DOMAIN ) ?></option>
					</select>
				</label>
			</div>
		</div>
	</div>

	<br>
	<p><?php echo __( '(Double optin means your subscribers will need to confirm their email address before being added to your list).', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
</div>
