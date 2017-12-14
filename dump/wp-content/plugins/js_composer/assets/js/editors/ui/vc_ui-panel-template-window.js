/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';
	vc.TemplateWindowUIPanelBackendEditor = vc.TemplatesPanelViewBackend
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperPanelViewResizable )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			panelName: 'template_window',
			showMessageDisabled: false,
			initialize: function() {
				vc.TemplateWindowUIPanelBackendEditor.__super__.initialize.call( this );
				this.trigger('show', this.initTemplatesTabs, this);
			},
			show: function () {
				vc.TemplateWindowUIPanelBackendEditor.__super__.show.call( this );
			},
			initTemplatesTabs: function() {
				this.$el.find( '[data-vc-ui-element="panel-tabs-controls"]' ).vcTabsLine( 'moveTabs' );
			},
			showMessage: function ( text, type ) {
				var wrapperCssClasses;
				if ( this.showMessageDisabled ) {
					return false;
				}
				wrapperCssClasses = 'vc_col-xs-12 wpb_element_wrapper';
				this.message_box_timeout && this.$el.find( '[data-vc-panel-message]' ).remove() && window.clearTimeout( this.message_box_timeout );
				this.message_box_timeout = false;
				var messageBoxTemplate = _.template( '<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>">' +
				'<div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>' );
				var $messageBox;
				switch ( type ) {
					case 'error':
					{
						$messageBox = $( '<div class="' + wrapperCssClasses +'" data-vc-panel-message>' ).html( messageBoxTemplate( {
							color: "danger",
							icon: "times",
							text: text
						} ) );
						break;
					}
					case 'warning':
					{
						$messageBox = $( '<div class="' + wrapperCssClasses +'" data-vc-panel-message>' ).html( messageBoxTemplate( {
							color: "warning",
							icon: "exclamation-triangle",
							text: text
						} ) );
						break;
					}
					case 'success':
					{
						$messageBox = $( '<div class="' + wrapperCssClasses +'" data-vc-panel-message>' ).html( messageBoxTemplate( {
							color: "success",
							icon: "check",
							text: text
						} ) );
						break;
					}
				}
				$messageBox.prependTo( this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_row.vc_active' ) );
				$messageBox.fadeIn();
				this.message_box_timeout = window.setTimeout( function () {
					$messageBox.remove();
				}, 6000 );
			},
			changeTab: function ( e ) {
				e.preventDefault();
				var $tab = $( e.currentTarget );
				if ( ! $tab.parent().hasClass( 'vc_active' ) ) {
					this.$el.find( '[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])' ).removeClass( 'vc_active' );
					$tab.parent().addClass( 'vc_active' );
					this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_active' ).removeClass( 'vc_active' );
					this.$el.find( $tab.data( 'vcUiElementTarget' ) ).addClass( 'vc_active' );
					this.$tabsMenu && this.$tabsMenu.vcTabsLine( 'checkDropdownContainerActive' );
				}
			}
		} );
	vc.TemplateWindowUIPanelBackendEditor.prototype.events = $.extend( true,
		vc.TemplateWindowUIPanelBackendEditor.prototype.events,
		{
			'click .vc_template-save-btn': 'saveTemplate',
			'click [data-template_unique_id] [data-template-handler]': 'loadTemplate',
			'click .vc_template-delete-icon': 'removeTemplate',
			'click [data-vc-ui-element="panel-tab-control"]': 'changeTab'
		} );
	vc.TemplateWindowUIPanelFrontendEditor = vc.TemplatesPanelViewFrontend
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperPanelViewResizable )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			panelName: 'template_window',
			showMessageDisabled: false,
			showMessage: function ( text, type ) {
				if ( this.showMessageDisabled ) {
					return false;
				}
				this.message_box_timeout && this.$el.find( '[data-vc-panel-message]' ).remove() && window.clearTimeout( this.message_box_timeout );
				this.message_box_timeout = false;
				var messageBoxTemplate = _.template( '<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>">' +
				'<div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>' );
				var $messageBox;
				var wrapperCssClasses;
				wrapperCssClasses = 'vc_col-xs-12 wpb_element_wrapper';
				switch ( type ) {
					case 'error':
					{
						$messageBox = $( '<div class="' + wrapperCssClasses + '" data-vc-panel-message>' ).html( messageBoxTemplate( {
							color: "danger",
							icon: "times",
							text: text
						} ) );
						break;
					}
					case 'warning':
					{
						$messageBox = $( '<div class="' + wrapperCssClasses + '" data-vc-panel-message>' ).html( messageBoxTemplate( {
							color: "warning",
							icon: "exclamation-triangle",
							text: text
						} ) );
						break;
					}
					case 'success':
					{
						$messageBox = $( '<div class="' + wrapperCssClasses + '" data-vc-panel-message>' ).html( messageBoxTemplate( {
							color: "success",
							icon: "check",
							text: text
						} ) );
						break;
					}
				}
				$messageBox.prependTo( this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_row.vc_active' ) );
				$messageBox.fadeIn();
				this.message_box_timeout = window.setTimeout( function () {
					$messageBox.remove();
				}, 6000 );
			},
			changeTab: function ( e ) {
				e.preventDefault();
				var $tab = $( e.currentTarget );
				if ( ! $tab.parent().hasClass( 'vc_active' ) ) {
					this.$el.find( '[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])' ).removeClass( 'vc_active' );
					$tab.parent().addClass( 'vc_active' );
					this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_active' ).removeClass( 'vc_active' );
					this.$el.find( $tab.data( 'vcUiElementTarget' ) ).addClass( 'vc_active' );
					this.$tabsMenu && this.$tabsMenu.vcTabsLine( 'checkDropdownContainerActive' );
				}
			}
		} );
})( window.jQuery );