<?php

/**
 * Add WP ui pointers to backend editor.
 */
function vc_frontend_editor_pointer() {
	vc_is_frontend_editor() && add_filter( 'vc-ui-pointers', 'vc_frontend_editor_register_pointer' );
}

add_action( 'admin_init', 'vc_frontend_editor_pointer' );

function vc_frontend_editor_register_pointer( $p ) {
	global $post;
	if ( is_object( $post ) && ! strlen( $post->post_content ) ) {
		$p['vc_pointers_frontend_editor'] = array(
			'name' => 'vcPointerController',
			'messages' => array(
				array(
					'target' => '#vc_add-new-element',
					'options' => array(
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
							__( 'Add Elements', 'js_composer' ),
							__( 'Add new element or start with a template.', 'js_composer' )
						),
						'position' => array(
							'edge' => 'top',
							'align' => 'left'
						),
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					),
					'closeEvent' => 'shortcodes:add',
				),
				array(
					'target' => '.vc_controls-out-tl:first',
					'options' => array(
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
							__( 'Rows and Columns', 'js_composer' ),
							__( 'This is a row container. Divide it into columns and style it. You can add elements into columns.', 'js_composer' )
						),
						'position' => array(
							'edge' => 'left',
							'align' => 'center'
						),
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					),
					'closeCallback' => 'vcPointersCloseInIFrame',
					'showCallback' => 'vcPointersSetInIFrame',
				),
				array(
					'target' => '.vc_controls-cc:first',
					'options' => array(
						'content' => sprintf( '<h3> %s </h3> <p> %s <br/><br/> %s</p>',
							__( 'Control Elements', 'js_composer' ),
							__( 'You can edit your element at any time and drag it around your layout.', 'js_composer' ),
							sprintf( __( 'P.S. Learn more at our <a href="%s" target="_blank">Knowledge Base</a>.', 'js_composer' )
								, 'http://kb.wpbakery.com' )
						),
						'position' => array(
							'edge' => 'left',
							'align' => 'center'
						),
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					),
					'closeCallback' => 'vcPointersCloseInIFrame',
					'showCallback' => 'vcPointersSetInIFrame',
				)
			),
		);
	}

	return $p;
}

function vc_page_editable_enqueue_pointer_scripts() {
	if ( vc_is_page_editable() ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		// Add pointers script to queue. Add custom script.
		wp_enqueue_script( 'vc_pointer-message', vc_asset_url( 'js/lib/vc-pointers/vc-pointer-message.js' ),
			array(
				'jquery',
				'underscore',
				'wp-pointer'
			),
			WPB_VC_VERSION,
			true );
	}
}

;

add_action( 'wp_enqueue_scripts', 'vc_page_editable_enqueue_pointer_scripts' );