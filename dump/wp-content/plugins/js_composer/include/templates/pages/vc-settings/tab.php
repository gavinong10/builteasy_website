<?php
$tab = preg_replace( '/^vc\-/', '', $page->getSlug() );
$use_custom = get_option( vc_settings()->getFieldPrefix() . 'use_custom' );
$css = ( ( 'color' === $tab ) && $use_custom ) ? ' color_enabled' : '';
?>
<script type="text/javascript">
	var vcAdminNonce = '<?php echo vc_generate_nonce( 'vc-admin-nonce' ); ?>';
</script>
<form action="options.php" method="post" id="vc_settings-<?php echo $tab ?>"
      class="vc_settings-tab-content vc_settings-tab-content-active <?php echo esc_attr( $css ) ?>"<?php echo apply_filters( 'vc_setting-tab-form-' . $tab, '' ) ?>>
	<?php settings_fields( vc_settings()->getOptionGroup() . '_' . $tab ) ?>
	<?php do_settings_sections( vc_settings()->page() . '_' . $tab ) ?>
	<?php if ( 'general' === $tab && vc_pointers_is_dismissed() ): ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Guide tours', 'js_composer' ) ?></th>
				<td>
					<a href="#" class="button vc_pointers-reset-button"
					   id="vc_settings-vc-pointers-reset"
					   data-vc-done-txt="<?php _e( 'Done', 'js_composer' ) ?>"><?php _e( 'Reset', 'js_composer' ) ?></a>

					<p class="description indicator-hint"><?php _e( 'Guide tours are shown in VC editors to help you to start working with editors. Yo can see them again by clicking button above.', 'js_composer' ) ?></p>
				</td>
			</tr>
		</table>
	<?php endif ?>
	<?php
	$submit_button_attributes = array();
	$submit_button_attributes = apply_filters( 'vc_settings-tab-submit-button-attributes', $submit_button_attributes, $tab );
	$submit_button_attributes = apply_filters( 'vc_settings-tab-submit-button-attributes-' . $tab, $submit_button_attributes, $tab );
	$license_activation_key = vc_license()->deactivation();
	if ( $tab === 'updater' && ! empty( $license_activation_key ) ) $submit_button_attributes['disabled'] = 'true'
	?>
	<?php if ( $tab !== 'updater' ): ?>
		<?php submit_button( __( 'Save Changes', 'js_composer' ), 'primary', 'submit_btn', true, $submit_button_attributes ); ?>
	<?php endif ?>
	<input type="hidden" name="vc_action" value="vc_action-<?php echo $tab; ?>"
	       id="vc_settings-<?php echo $tab; ?>-action"/>
	<?php if ( $tab === 'color' ): ?>
		<a href="#" class="button vc_restore-button"
		   id="vc_settings-color-restore-default"><?php _e( 'Restore Default', 'js_composer' ) ?></a>
	<?php endif ?>
	<?php if ( $tab === 'updater' ): ?>
		<input type="hidden" id="vc_settings-license-status" name="vc_license_status"
		       value="<?php echo empty( $license_activation_key ) ? 'not_activated' : 'activated' ?>"/>
		<a href="#" class="button button-primary vc_activate-license-button"
		   id="vc_settings-activate-license"><?php empty( $license_activation_key ) ? _e( 'Activate License', 'js_composer' ) : _e( 'Deactivate License', 'js_composer' ) ?></a>
		<span class="vc_updater-spinner-wrapper" style="display: none;" id="vc_updater-spinner"><img
				src="<?php echo get_site_url() ?>/wp-admin/images/wpspin_light.gif"/></span>
	<?php endif ?>
</form>
