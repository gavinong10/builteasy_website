/* =========================================================
 * build.js v1.0.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer builder backbone/underscore version
 * ========================================================= */

if ( _.isUndefined( vc ) ) {
	var vc = {};
}
(function ( $ ) {
	"use strict";
	vc.createPreLoader = function () {
		vc.$preloader = $( '#vc_preloader' );

	};
	vc.removePreLoader = function () {
		vc.$preloader && vc.$preloader.remove();
	};
	vc.createPreLoader();
	vc.$frame_wrapper = $( '#vc_inline-frame-wrapper' );
	vc.$frame = $( '<iframe src="'
	+ vc_iframe_src + '" scrolling="auto" style="width: 100%;" id="vc_inline-frame"></iframe>' );
	vc.$frame.appendTo( vc.$frame_wrapper );
	vc.build = function () {
		if ( vc.loaded ) {
			return;
		}
		vc.loaded = true;

		vc.map = window.vc_mapper; // vc_user_mapper // TODO: check why user mapper

		$( '#wpadminbar' ).remove();
		$( '#screen-meta-links, #screen-meta' ).hide();
		var $body = $( 'body' );
		$body.attr( 'data-vc', true );
		vc.post_id = $( '#vc_post-id' ).val();
		vc.is_mobile = 0 < $( 'body.mobile' ).length;
		vc.title = $( '#vc_title-saved' ).val();
		// Create Modals & panels
		vc.add_element_block_view = new vc.AddElementUIPanelFrontendEditor( { el: '#vc_ui-panel-add-element' } );
		vc.edit_element_block_view = new vc.EditElementUIPanel( { el: '#vc_ui-panel-edit-element' } );
		vc.post_settings_view = new vc.PostSettingsUIPanelFrontendEditor( { el: '#vc_ui-panel-post-settings' } );
		/**
		 * @deprecated 4.4
		 * @type {vc.TemplatesEditorPanelView}
		 */
		vc.templates_editor_view = new vc.TemplatesEditorPanelView( { el: '#vc_templates-editor' } );
		vc.templates_panel_view = new vc.TemplateWindowUIPanelFrontendEditor( { el: '#vc_ui-panel-templates' } );

		vc.app = new vc.View();
		vc.buildRelevance();
		if ( $body.hasClass( 'vc_responsive_disabled' ) ) {
			vc.responsive_disabled = true;
		}
		// Build Frame {{
		vc.setFrameSize( '100%' );
		vc.frame = new vc.FrameView( { el: $( vc.$frame.get( 0 ).contentWindow.document ).find( 'body' ).get( 0 ) } );
		vc.app.render();
		// }}
		// Build content of the page
		// Get current content data
		vc.post_shortcodes = vc.frame_window.vc_post_shortcodes;
		vc.builder.buildFromContent();
		vc.removePreLoader();
		$( window ).trigger( 'vc_build' );
	};
	vc.$frame.load( function () {
		if ( ! vc.loaded ) {
			window.setTimeout( function () {
				vc.build()
			}, 10 );
		}

	} );
})( window.jQuery );