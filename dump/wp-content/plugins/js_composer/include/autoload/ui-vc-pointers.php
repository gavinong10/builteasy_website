<?php
global $vc_default_pointers, $vc_pointers;
$vc_default_pointers = (array) apply_filters( 'vc_pointers_list',
	array(
		'vc_grid_item',
		'vc_pointers_backend_editor',
		'vc_pointers_frontend_editor'
	) );
if ( is_admin() ) {
	add_action( 'admin_enqueue_scripts', 'vc_pointer_load', 1000 );
}

function vc_pointer_load( $hook_suffix = '' ) {
	global $vc_pointers;
	// Don't run on WP < 3.3
	if ( get_bloginfo( 'version' ) < '3.3' ) {
		return;
	}

	$screen = get_current_screen();
	$screen_id = $screen->id;

	// Get pointers for this screen
	$pointers = apply_filters( 'vc-ui-pointers', array() );
	$pointers = apply_filters( 'vc_ui-pointers-' . $screen_id, $pointers );

	if ( ! $pointers || ! is_array( $pointers ) ) {
		return;
	}

	// Get dismissed pointers
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$vc_pointers = array( 'pointers' => array() );

	// Check pointers and remove dismissed ones.
	foreach ( $pointers as $pointer_id => $pointer ) {

		// Sanity check
		if ( in_array( $pointer_id, $dismissed ) || empty( $pointer ) || empty( $pointer_id ) || empty( $pointer['name'] ) ) {
			continue;
		}

		$pointer['pointer_id'] = $pointer_id;

		// Add the pointer to $valid_pointers array

		$vc_pointers['pointers'][] = $pointer;
	}

	// No valid pointers? Stop here.
	if ( empty( $vc_pointers['pointers'] ) ) {
		return;
	}

	// Add pointers style to queue.
	wp_enqueue_style( 'wp-pointer' );

	// Add pointers script to queue. Add custom script.
	wp_enqueue_script( 'vc_pointer-message', vc_asset_url( 'js/lib/vc-pointers/vc-pointer-message.js' ),
		array(
			'jquery',
			'underscore',
			'wp-pointer'
		),
		WPB_VC_VERSION,
		true );
	wp_enqueue_script( 'vc_pointers-controller', vc_asset_url( 'js/lib/vc-pointers/vc-pointers-controller.js' ), array(
		'vc_pointer-message',
		'wpb_js_composer_js_listeners',
		'wpb_scrollTo_js'
	),
		WPB_VC_VERSION,
		true );
	/*
	wp_enqueue_script( 'vc_event-pointers-controller', vc_asset_url( 'js/lib/vc-pointers/vc-event-pointers-controller.js' ), array( 'vc_pointers-controller' ),
		WPB_VC_VERSION,
		true );
	*/
	wp_enqueue_script( 'vc_wp-pointer',
		vc_asset_url( 'js/lib/vc-pointers/pointers.js' ),
		array( 'vc_pointers-controller' ),
		WPB_VC_VERSION,
		true );
	// messages
	$vc_pointers['texts'] = array(
		'finish' => __( 'Finish', 'js_composer' ),
		'next' => __( 'Next', 'js_composer' ),
		'prev' => __( 'Prev', 'js_composer' ),
	);

	// Add pointer options to script.
	wp_localize_script( 'vc_wp-pointer', 'vcPointer', $vc_pointers );
}

/**
 * Remove Vc pointers keys to show Tour markers again.
 * @sine 4.5
 */
function vc_pointer_reset() {
	global $vc_default_pointers;
	if ( ! vc_verify_admin_nonce() || ! current_user_can( 'manage_options' ) ) {
		die();
	}
	$pointers = (array) apply_filters( 'vc_pointers_list', $vc_default_pointers );
	$prev_meta_value = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
	$dismissed = explode( ',', (string) $prev_meta_value );
	if ( count( $dismissed ) > 0 && count( $pointers ) ) {
		$meta_value = implode( ',', array_diff( $dismissed, $pointers ) );
		update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', $meta_value, $prev_meta_value );
	}
}

/**
 * Reset tour guid
 * @return bool
 */
function vc_pointers_is_dismissed() {
	global $vc_default_pointers;
	$pointers = (array) apply_filters( 'vc_pointers_list', $vc_default_pointers );
	$prev_meta_value = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
	$dismissed = explode( ',', (string) $prev_meta_value );

	return count( array_diff( $dismissed, $pointers ) ) < count( $dismissed );
}

add_action( 'wp_ajax_vc_pointer_reset', 'vc_pointer_reset' );
