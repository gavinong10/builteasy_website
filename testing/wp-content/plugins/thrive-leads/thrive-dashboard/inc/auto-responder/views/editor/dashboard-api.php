<h4><?php echo __( "Connect with Service", TVE_DASH_TRANSLATE_DOMAIN ) ?></h4>
<hr class="tve_lightbox_line"/>
<?php
$connection_config          = $data['connection_config'];
$klicktipp_option           = count( $connection_config ) == 1 && isset( $connection_config['klicktipp'] );
$custom_messages            = is_array( $data['custom_messages'] ) ? $data['custom_messages'] : array();
$custom_messages['error']   = empty( $custom_messages['error'] ) ? '' : $custom_messages['error'];
$custom_messages['success'] = empty( $custom_messages['success'] ) ? '' : $custom_messages['success'];
/**
 * at this stage, we have a list of existing connections that are to be displayed in a list
 */

$available = Thrive_Dash_List_Manager::getAvailableAPIs( true );
$helper    = new Thrive_Dash_Api_CustomHtml();
$form_type = $helper->getFormType();
$form_type !== 'lead_generation' ? $variations = $helper->getFormVariations() : $variations = array();

if ( function_exists( 'tve_leads_get_form_variation' ) ) {
	if( ! empty($_POST['_key']) ){
		$this_variation           = tve_leads_get_form_variation( null, $_POST['_key'] );

		if($form_type != 'lightbox' && $this_variation['form_state'] == 'lightbox') {
			foreach($variations as $key => $variation) {
				if($variation['form_state'] == 'lightbox') {
					unset($variations[$key]);
				}
			}
		}
	}
}

?>
<div class="tve_large_lightbox tve_lead_gen_lightbox_small">
	<p><?php echo __( "Your sign up form is connected to service(s) using the following API connections:", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<table>
		<thead>
		<tr>
			<th colspan="2">
				<?php echo __( "Service Name", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $connection_config as $key => $list_id ) : if ( ! isset( $available[ $key ] ) ) {
			continue;
		} ?>
			<tr>
				<td width="90%">
					<?php echo $available[ $key ]->getTitle() ?>
				</td>
				<td width="10%">
					<a href="javascript:void(0)" class="tve_click" data-ctrl="function:auto_responder.connection_form"
					   data-connection-type="api" data-key="<?php echo $key ?>" title="<?php echo __( "Settings", TVE_DASH_TRANSLATE_DOMAIN ) ?>">
						<span class="tve_icm tve-ic-cog tve_ic_small tve_lightbox_icon_small"></span>
					</a>
					&nbsp;&nbsp;&nbsp;
					<a href="javascript:void(0)" class="tve_click" data-ctrl="function:auto_responder.api.remove"
					   data-key="<?php echo $key ?>" title="<?php echo __( "Remove", TVE_DASH_TRANSLATE_DOMAIN ) ?>">
						<span class="tve_icm tve-ic-close tve_ic_small tve_lightbox_icon_small"></span>
					</a>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<div class="tve-sp"></div>
	<?php if ( count( $available ) != count( $connection_config ) ) : ?>
		<div class="clearfix">
			<a href="javascript:void(0)" class="tve_click tve_right tve_editor_button tve_editor_button_success"
			   data-ctrl="function:auto_responder.connection_form" data-connection-type="api">
				<?php echo __( "Add New Connection", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>
		</div>
	<?php endif ?>
	<div class="tve_clear" style="height:30px;"></div>
	<p><?php echo __( 'Select which fields to display and their properties (you can reorder them by dragging the "move" icon from the left):', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<?php
	$fields_table       = isset( $data['fields_table'] ) ? $data['fields_table'] : '';
	$show_thank_you_url = true;
	$show_reCaptcha     = true;
	include dirname( __FILE__ ) . '/autoresponder-code-fields.php';

	?>
	<div class="tve-sp"></div>
	<?php if ( ! empty( $data['show_submit_options'] ) ) : ?>
		<div class="tve_gray_box">
			<h4><?php echo __( "Action After Signup", TVE_DASH_TRANSLATE_DOMAIN ) ?></h4>
			<?php $submit = ! empty( $_POST['submit_option'] ) ? $_POST['submit_option'] : 'reload' ?>
			<?php $state = ! empty( $_POST['state'] ) ? $_POST['state'] : '' ?>
			<label><?php echo __( "After the form is submitted:", TVE_DASH_TRANSLATE_DOMAIN ) ?>&nbsp;</label>

			<div class="tve_lightbox_select_holder tve_lightbox_select_holder_submit tve_lightbox_input_inline tve_lightbox_select_inline">
				<select class="tve_lg_validation_options tve_change tve-api-submit-filters" id="tve-api-submit-option"
				        data-ctrl="function:auto_responder.api.submit_option_changed">
					<option
						value="reload"<?php echo $submit == 'reload' ? ' selected="selected"' : '' ?>><?php echo __( "Reload current page", TVE_DASH_TRANSLATE_DOMAIN ) ?>
					</option>
					<option
						value="redirect"<?php echo $submit == 'redirect' ? ' selected="selected"' : '' ?>><?php echo __( "Redirect to URL", TVE_DASH_TRANSLATE_DOMAIN ) ?>
					</option>
					<option
						value="message" <?php echo $submit == 'message' ? ' selected="selected"' : '' ?>><?php echo __( "Display message without reload", TVE_DASH_TRANSLATE_DOMAIN ) ?>
					</option>
					<?php if ( $form_type !== 'lead_generation' && ! empty( $variations ) ) : ?>
						<option
							value="state" <?php echo $submit == 'state' ? ' selected="selected"' : '' ?>><?php echo __( "Switch State", TVE_DASH_TRANSLATE_DOMAIN ) ?>
						</option>
					<?php endif; ?>
					<?php if ( $klicktipp_option ) : ?>
						<option
							value="klicktipp-redirect" <?php echo $submit == 'klicktipp-redirect' ? ' selected="selected"' : '' ?>><?php echo __( "KlickTipp Thank You URL", TVE_DASH_TRANSLATE_DOMAIN ) ?>
						</option>
					<?php endif; ?>

				</select>
			</div>
			<input <?php echo $submit !== 'redirect' ? ' style="display: none"' : '' ?> size="70"
			                                                                            class="tve_change tve_text tve_lightbox_input tve_lightbox_input_inline tve_lightbox_input_inline_redirect"
			                                                                            data-ctrl="function:auto_responder.api.thank_you_url"
			                                                                            value="<?php echo ! empty( $_POST['thank_you_url'] ) ? $_POST['thank_you_url'] : '' ?>"
			                                                                            placeholder="http://"/>

			<div class="tve_message_settings" <?php echo $submit !== 'message' ? ' style="display: none"' : '' ?>>
				<p><?php echo __( "The following message will be displayed in a small popup after signup, without reloading the page.", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
				<div class="tve_dashboard_tab_success tve_dashboard_tab tve_dashboard_tab_selected">
					<?php wp_editor( $custom_messages['success'], 'tve_success_wp_editor', $settings = array( 'quicktags' => false, 'media_buttons' => false ) ); ?>
				</div>
			</div>
			<?php if ( $form_type !== 'lead_generation' ) : ?>
				<div class="tve_state_settings" <?php echo $submit !== 'state' ? ' style="display: none"' : '' ?>>
					<?php if ( ! empty( $variations ) ) : ?>
						<label><?php echo __( "Choose the state to switch :", TVE_DASH_TRANSLATE_DOMAIN ) ?>&nbsp;</label>
						<div class="tve_lightbox_select_holder tve_lightbox_input_inline tve_lightbox_select_inline">
							<select class="tve_change_states tve_change" data-ctrl="function:auto_responder.api.state_changed">
								<?php foreach ( $variations as $variation ) : ?>
									<option data-state="<?php echo $variation['form_state'] ?>"
									        value="<?php echo $variation['key'] ?>" <?php echo $state == $variation['key'] ? ' selected="selected"' : '' ?>><?php echo $variation['state_name'] ?>
									</option>
								<?php endforeach; ?>

							</select>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<div
			class="tve-shortcodes-wrapper tve-shortcodes-message" <?php echo $submit == 'message' && $_POST['error_message_option'] != 1 ? '' : 'style="display:none"' ?>>
			<?php include dirname( __FILE__ ) . '/partials/api-shortcodes.php'; ?>
		</div>
		<div class="tve-sp"></div>
		<div class="tve-error-message-option tve_lightbox_input_holder">
			<input type="checkbox" <?php echo $_POST['error_message_option'] == 1 ? 'checked' : '' ?> class="tve_change" id="tve-error-message-option"
			       data-ctrl="function:auto_responder.error_message_option_changed"/>
			<label for="tve-error-message-option"><?php echo __( "Add Error Message", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tve_gray_box tve-error-message-wrapper" <?php echo $_POST['error_message_option'] != 1 ? 'style="display:none"' : '' ?>>
			<h4><?php echo __( "Edit your error message", TVE_DASH_TRANSLATE_DOMAIN ) ?></h4>
			<p><?php echo __( "This error message is shown in the rare case that the signup fails. This can happen when your connected email marketing service can't be reached.", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</p>
			<div class="tve_dashboard_tab_error">
				<?php wp_editor( $custom_messages['error'], 'tve_error_wp_editor', $settings = array( 'quicktags' => false, 'media_buttons' => false ) ); ?>
			</div>
		</div>
		<div class="tve-shortcodes-wrapper tve-shortcodes-error" <?php echo $_POST['error_message_option'] != 1 ? 'style="display:none"' : '' ?>>
			<?php include dirname( __FILE__ ) . '/partials/api-shortcodes.php'; ?>
		</div>
	<?php endif ?>

	<div class="tve-sp"></div>
	<div class="tve_clearfix">
		<a href="javascript:void(0)" class="tve_click tve_editor_button tve_editor_button_default tve_right tve_button_margin"
		   data-ctrl="function:controls.lb_close">
			<?php echo __( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</a>
		&nbsp;
		<a href="javascript:void(0)" class="tve_click tve_editor_button tve_editor_button_success tve_right"
		   data-ctrl="function:auto_responder.save_api_connection" data-edit-custom="1">
			<?php echo __( "Save", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</a>
	</div>
</div>
