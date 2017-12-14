<div id="thrive-api-list">
	<div class="tve-sp"></div>
	<?php if ( ! empty( $selected_api ) ) : ?>
		<?php $list_subtitle = $selected_api->getListSubtitle() ?>
		<?php $api_key = $selected_api->getKey(); ?>
		<?php $selected_api->renderBeforeListsSettings( empty( $extra_settings[ $api_key ] ) ? array() : $extra_settings[ $api_key ] ) ?>
		<div class="tve-list-container tve-api-option-group tve-api-option-group-list"  <?php echo $api_key == 'drip' && isset($extra_settings[ $api_key ]['type']) && $extra_settings[ $api_key ]['type'] == 'automation'? 'style="display:none"' : ''; ?>>
			<h6><?php echo empty( $list_subtitle ) ? 'Choose your mailing list:' : $list_subtitle ?></h6>
			<?php if ( false === $lists ) : /** this means there's been an error while connecting / communicating to the API */ ?>
				<p class="error-message" style="color: red">
					<?php echo __( 'Error while communicating with the service:', TVE_DASH_TRANSLATE_DOMAIN ) ?><?php echo $selected_api->getApiError() ?>
				</p>
			<?php else : ?>
				<div class="tve_lightbox_select_holder tve_lightbox_input_inline tve_lightbox_select_inline">
					<select id="thrive-api-list-select"<?php echo ( empty( $lists ) ) ? ' disabled' : ' data-api="' . $api_key .  '"' ?> <?php echo  (!empty($lists) && $api_key == 'mailchimp') ? 'class="tve_change" data-ctrl="function:auto_responder.api.api_get_groups"' : ''?>  >
						<?php if ( empty( $lists ) ) : ?>
							<option value=""><?php echo __( 'No list available', TVE_DASH_TRANSLATE_DOMAIN ) ?></option>
						<?php endif ?>
						<?php foreach ( $lists as $list ) : ?>
							<option value="<?php echo $list['id'] ?>"<?php echo ! empty( $selected_list ) && $selected_list == $list['id'] ? ' selected="selected"' : '' ?>><?php echo $list['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0)" class="tve_click tve_lightbox_link tve_lightbox_link_refresh" data-ctrl="function:auto_responder.api.reload_lists" data-force-fetch="1"" data-api="<?php echo $api_key ?>"><?php echo __( 'Reload', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
			<?php
			if(!empty($selected_list)) {
				$extra_settings[ $api_key ]['list_id'] = $selected_list;
			}
			?>
			<?php if ( ! empty( $lists ) || $api_key == 'drip' ) : ?>
				<?php echo $selected_api->renderExtraEditorSettings( empty( $extra_settings[ $api_key ] ) ? array() : $extra_settings[ $api_key ] ) ?>
			<?php endif ?>
		<?php endif ?>
	<?php endif ?>
</div>
