/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';
	vc.HelperTemplatesPanelViewSearch = {
		searchSelector: '[data-vc-templates-name-filter]',
		events: {
			'keyup [data-vc-templates-name-filter]': 'searchTemplate',
			'search [data-vc-templates-name-filter]': 'searchTemplate'
		},
		uiEvents: {
			'show': 'focusToSearch'
		},
		focusToSearch: function() {
			if ( ! vc.is_mobile ) {
				$( this.searchSelector, this.$el ).focus();
			}
		},
		searchTemplate: function ( e ) {
			var $el = $( e.currentTarget );
			if ( $el.val().length ) {
				this.searchByName( $el.val() );
			} else {
				this.clearSearch();
			}
		},
		clearSearch: function () {
			this.$el.find( '[data-vc-templates-name-filter]' ).val( '' );
			this.$el.find( '[data-template_name]' ).css( 'display', 'block' );
			this.$el.removeAttr( 'data-vc-template-search' );
			this.$el.find( '.vc-search-result-empty' ).removeClass( 'vc-search-result-empty' );
			var ev = new jQuery.Event( 'click' );
			ev.isClearSearch = true;
			this.$el.find( '.vc_panel-tabs-control:first [data-vc-ui-element="panel-tab-control"]' ).trigger( ev );
		},
		searchByName: function ( name ) {
			this.$el.find( '.vc_panel-tabs-control.vc_active' ).removeClass( 'vc_active' );
			this.$el.attr( 'data-vc-template-search', 'true' );
			this.$el.find( '[data-template_name]' ).css( 'display', 'none' );
			this.$el.find( '[data-template_name*=' + name.toLowerCase() + ']' ).css( 'display', 'block' );
			this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"]' ).each( function () {
				var $el = $( this );
				$el.removeClass( 'vc-search-result-empty' );
				if ( ! $el.find( '[data-template_name]:visible' ).length ) {
					$el.addClass( 'vc-search-result-empty' );
				}
			} );
			//if ( ! this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"]:visible' ).length ) {
			//this.showMessage( 'Nothing found', 'error' ); // todo improve this
			//}
		}
	};
})( window.jQuery );