/* global vc, i18nLocale, getUserSetting, setUserSetting */
(function ( $ ) {
	'use strict';
	vc.HelperPanelViewDraggable = {
		draggable: true,
		draggableOptions: {
			iframeFix: true,
			handle: '[data-vc-ui-element="panel-heading"]'
		},
		uiEvents: {
			'show': 'initDraggable'
		},
		initDraggable: function () {
			this.$el.draggable( _.extend( {}, this.draggableOptions, {
				start: this.fixElContainment,
				stop: this.fixElContainment
			} ) );
		}
	};
})( window.jQuery );