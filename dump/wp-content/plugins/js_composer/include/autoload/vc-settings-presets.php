<?php

add_action( 'wp_ajax_vc_action_save_settings_preset', 'vc_action_save_settings_preset' );
add_action( 'wp_ajax_vc_action_set_as_default_settings_preset', 'vc_action_set_as_default_settings_preset' );
add_action( 'wp_ajax_vc_action_delete_settings_preset', 'vc_action_delete_settings_preset' );
add_action( 'wp_ajax_vc_action_restore_default_settings_preset', 'vc_action_restore_default_settings_preset' );
add_action( 'wp_ajax_vc_action_get_settings_preset', 'vc_action_get_settings_preset' );
add_action( 'wp_ajax_vc_action_render_settings_preset_popup', 'vc_action_render_settings_preset_popup' );
add_action( 'wp_ajax_vc_action_render_settings_preset_title_prompt', 'vc_action_render_settings_preset_title_prompt' );

/**
 * Include settings preset class
 *
 * Also check if user has 'edit_posts' capability and if not, respond with unsuccessful status
 *
 * @since 4.8
 */
function vc_include_settings_preset_class() {
	if ( ! vc_verify_admin_nonce() || ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) ) {
		wp_send_json( array(
			'success' => false
		) );
	}

	require_once vc_path_dir( 'AUTOLOAD_DIR', 'class-vc-settings-presets.php' );
}

/**
 * Save settings preset for specific shortcode
 *
 * Include freshly rendered html in response
 *
 * Required _POST params:
 * - shortcode_name string
 * - title string
 * - data string params in json
 * - is_default
 *
 * @since 4.7
 */
function vc_action_save_settings_preset() {
	vc_include_settings_preset_class();

	$id = Vc_Settings_Preset::saveSettingsPreset(
		vc_post_param( 'shortcode_name' ),
		vc_post_param( 'title' ),
		vc_post_param( 'data' ),
		vc_post_param( 'is_default' )
	);

	$response = array(
		'success' => (bool) $id,
		'html' => Vc_Settings_Preset::getRenderedSettingsPresetPopup( vc_post_param( 'shortcode_name' ) ),
		'id' => $id
	);

	wp_send_json( $response );
}

/**
 * Set existing preset as default
 *
 * Include freshly rendered html in response
 *
 * Required _POST params:
 * - id int
 * - shortcode_name string
 *
 * @since 4.7
 */
function vc_action_set_as_default_settings_preset() {
	vc_include_settings_preset_class();

	$id = vc_post_param( 'id' );
	$shortcode_name = vc_post_param( 'shortcode_name' );

	$status = Vc_Settings_Preset::setAsDefaultSettingsPreset( $id, $shortcode_name );

	$response = array(
		'success' => $status,
		'html' => Vc_Settings_Preset::getRenderedSettingsPresetPopup( $shortcode_name )
	);

	wp_send_json( $response );
}

/**
 * Unmark current default preset as default
 *
 * Include freshly rendered html in response
 *
 * Required _POST params:
 * - shortcode_name string
 *
 * @since 4.7
 */
function vc_action_restore_default_settings_preset() {
	vc_include_settings_preset_class();

	$shortcode_name = vc_post_param( 'shortcode_name' );

	$status = Vc_Settings_Preset::setAsDefaultSettingsPreset( null, $shortcode_name );

	$response = array(
		'success' => $status,
		'html' => Vc_Settings_Preset::getRenderedSettingsPresetPopup( $shortcode_name )
	);

	wp_send_json( $response );
}

/**
 * Delete specific settings preset
 *
 * Include freshly rendered html in response
 *
 * Required _POST params:
 * - shortcode_name string
 * - id int
 *
 * @since 4.7
 */
function vc_action_delete_settings_preset() {
	vc_include_settings_preset_class();

	$default = get_post_meta( vc_post_param( 'id' ), '_vc_default', true );

	$status = Vc_Settings_Preset::deleteSettingsPreset(
		vc_post_param( 'id' )
	);

	$response = array(
		'success' => $status,
		'default' => $default,
		'html' => Vc_Settings_Preset::getRenderedSettingsPresetPopup( vc_post_param( 'shortcode_name' ) )
	);

	wp_send_json( $response );
}

/**
 * Get data for specific settings preset
 *
 * Required _POST params:
 * - id int
 *
 * @since 4.7
 */
function vc_action_get_settings_preset() {
	vc_include_settings_preset_class();

	$data = Vc_Settings_Preset::getSettingsPreset(
		vc_post_param( 'id' ), true
	);

	if ( false !== $data ) {
		$response = array(
			'success' => true,
			'data' => $data
		);
	} else {
		$response = array(
			'success' => false
		);
	}

	wp_send_json( $response );
}

/**
 * Respond with rendered popup menu
 *
 * Required _POST params:
 * - shortcode_name string
 *
 * @since 4.7
 */
function vc_action_render_settings_preset_popup() {
	vc_include_settings_preset_class();

	$html = Vc_Settings_Preset::getRenderedSettingsPresetPopup( vc_post_param( 'shortcode_name' ) );

	$response = array(
		'success' => true,
		'html' => $html
	);

	wp_send_json( $response );
}

/**
 * Return rendered title prompt
 *
 * @since 4.7
 *
 * @return string
 */
function vc_action_render_settings_preset_title_prompt() {
	if ( ! vc_verify_admin_nonce() || ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) ) {
		wp_send_json( array(
			'success' => false
		) );
	}
	ob_start();
	vc_include_template( apply_filters( 'vc_render_settings_preset_title_prompt', 'editors/partials/prompt.tpl.php' ) );
	$html = ob_get_clean();

	$response = array(
		'success' => true,
		'html' => $html
	);

	wp_send_json( $response );
}