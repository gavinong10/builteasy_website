<?php
/**
 * WPBakery Visual Composer main class.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */

/**
 * Edit form for shortcodes with ability to manage shortcode attributes in more convenient way.
 *
 * @since   4.2
 */
class Vc_Shortcode_Edit_Form implements Vc_Render {

	/**
	 *
	 */
	public function init() {
		/**
		 * @deprecated Please use vc_edit_form hook.
		 */
		add_action( 'wp_ajax_wpb_show_edit_form', array( &$this, 'build' ) );
		add_action( 'wp_ajax_vc_edit_form', array( &$this, 'renderFields' ) );

		add_filter( 'vc_single_param_edit', array( &$this, 'changeEditFormFieldParams' ) );
		add_filter( 'vc_edit_form_class', array( &$this, 'changeEditFormParams' ) );
	}

	/**
	 *
	 */
	public function render() {
		vc_include_template( 'editors/popups/vc_ui-panel-edit-element.tpl.php', array(
			'box' => $this
		) );
	}

	/**
	 * Build edit form fields.
	 *
	 * @since 4.4
	 */
	public function renderFields() {
		if ( ! vc_verify_admin_nonce() || ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) ) {
			wp_send_json( array(
				'success' => false
			) );
		}
		$params = array_map( 'htmlspecialchars_decode', (array) stripslashes_deep( vc_post_param( 'params' ) ) );
		$tag = stripslashes( vc_post_param( 'tag' ) );
		require_once vc_path_dir( 'EDITORS_DIR', 'class-vc-edit-form-fields.php' );
		$fields = new Vc_Edit_Form_Fields( $tag, $params );
		$fields->render();
		die();
	}

	/**
	 * Build edit form fields
	 *
	 * @deprecated 4.4
	 * @use Vc_Shortcode_Edit_Form::renderFields
	 */
	public function build() {
		if ( ! vc_verify_admin_nonce( vc_post_param( 'nonce' ) ) || ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) ) {
			wp_send_json( array(
				'success' => false
			) );
		}
		$tag = vc_post_param( 'element' );
		$shortCode = stripslashes( vc_post_param( 'shortcode' ) );
		require_once vc_path_dir( 'EDITORS_DIR', 'class-vc-edit-form-fields.php' );
		$fields = new Vc_Edit_Form_Fields( $tag, shortcode_parse_atts( $shortCode ) );
		$fields->render();
		die();
	}

	/**
	 * @param $param
	 *
	 * @return mixed
	 */
	public function changeEditFormFieldParams( $param ) {
		$css = $param['vc_single_param_edit_holder_class'];
		if ( isset( $param['edit_field_class'] ) ) {
			$new_css = $param['edit_field_class'];
		} else {
			$new_css = 'vc_col-xs-12 vc_column';
		}
		array_unshift( $css, $new_css );
		$param['vc_single_param_edit_holder_class'] = $css;

		return $param;
	}

	/**
	 * @param $css_classes
	 *
	 * @return mixed
	 */
	public function changeEditFormParams( $css_classes ) {
		$css = '';
		array_unshift( $css_classes, $css );

		return $css_classes;
	}
}