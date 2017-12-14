<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Vendor_WPML
 * @since 4.9
 */
class Vc_Vendor_WPML implements Vc_Vendor_Interface {

	public function load() {
		add_filter( 'vc_object_id', array(
			&$this,
			'filterMediaId',
		) );

		add_filter( 'vc_basic_grid_filter_query_suppress_filters', '__return_false' );

		add_filter( 'vc_frontend_editor_iframe_url', array(
			&$this,
			'appendLangToUrl',
		) );
		add_filter( 'vc_grid_request_url', array(
			&$this,
			'appendLangToUrl',
		) );
		add_filter( 'vc_admin_url', array(
			&$this,
			'appendLangToUrl',
		) );
		if ( ! vc_is_frontend_editor() ) {
			add_filter( 'vc_get_inline_url', array(
				&$this,
				'appendLangToUrl',
			) );
		}
	}

	public function appendLangToUrl( $link ) {
		$args = func_get_args();
		global $sitepress;
		if ( is_object( $sitepress ) ) {
			if ( is_string( $link ) && strpos( $link, 'lang' ) === false && ( strpos( $link, 'vc_inline' ) !== false || strpos( $link, 'vc_editable' ) !== false || strpos( $link, 'admin-ajax' ) !== false ) ) {
				return add_query_arg( array( 'lang' => $sitepress->get_current_language() ), $link );
			}
		}

		return $link;
	}

	public function filterMediaId( $id ) {
		return apply_filters( 'wpml_object_id', $id, 'post', true );
	}
}
