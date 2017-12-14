/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';
	var events = {
		'click [data-vc-ui-element="button-save"]': 'save',
		'click [data-vc-ui-element="button-close"]': 'hide',
		'click [data-vc-ui-element="button-minimize"]': 'toggleOpacity',
		'click [data-vc-ui-element="button-layout"]': 'setLayout',
		'click [data-vc-ui-element="button-update-layout"]': 'updateFromInput'
	};
	vc.RowLayoutUIPanelFrontendEditor = vc.RowLayoutEditorPanelView
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			panelName: 'rowLayouts',
			events: events
		} );
	vc.RowLayoutUIPanelBackendEditor = vc.RowLayoutEditorPanelViewBackend
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			panelName: 'rowLayouts',
			events: events
		} );
})( window.jQuery );