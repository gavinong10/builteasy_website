/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';

	vc.TemplateWindowUIPanelBackendEditor = vc.TemplatesPanelViewBackend
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperTemplatesPanelViewSearch )
		.vcExtendUI( vc.HelperPanelViewResizable )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			panelName: 'template_window',
			showMessageDisabled: false,
			initialize: function () {
				vc.TemplateWindowUIPanelBackendEditor.__super__.initialize.call( this );
				this.trigger( 'show', this.initTemplatesTabs, this );
			},
			show: function () {
				this.clearSearch();
				vc.TemplateWindowUIPanelBackendEditor.__super__.show.call( this );
			},
			initTemplatesTabs: function () {
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
				e && ! e.isClearSearch && this.clearSearch();
				var $tab = $( e.currentTarget );
				if ( ! $tab.parent().hasClass( 'vc_active' ) ) {
					this.$el.find( '[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])' ).removeClass( 'vc_active' );
					$tab.parent().addClass( 'vc_active' );
					this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_active' ).removeClass( 'vc_active' );
					this.$el.find( $tab.data( 'vcUiElementTarget' ) ).addClass( 'vc_active' );
					this.$tabsMenu && this.$tabsMenu.vcTabsLine( 'checkDropdownContainerActive' );
				}
			},
			setPreviewFrameHeight: function ( templateID, height ) {
				$( 'data-vc-template-preview-frame="' + templateID + '"' ).height( height );
			}
		} );
	vc.TemplateWindowUIPanelBackendEditor.prototype.events = $.extend( true,
		vc.TemplateWindowUIPanelBackendEditor.prototype.events,
		{
			// header footer
			'click [data-vc-ui-element="button-save"]': 'save', // need to save, hide into this code.
			'click [data-vc-ui-element="button-close"]': 'hide',
			'click [data-vc-ui-element="button-minimize"]': 'toggleOpacity',
			// search
			'keyup [data-vc-templates-name-filter]': 'searchTemplate',
			'search [data-vc-templates-name-filter]': 'searchTemplate',
			// templates
			'click .vc_template-save-btn': 'saveTemplate',
			'click [data-template_unique_id] [data-template-handler]': 'loadTemplate',
			'click [data-vc-container=".vc_ui-list-bar"][data-vc-preview-handler]': 'buildTemplatePreview',
			'click [data-vc-ui-delete=template-title]': 'removeTemplate',
			'click [data-vc-ui-element="panel-tab-control"]': 'changeTab'
		} );
	vc.TemplateWindowUIPanelFrontendEditor = vc.TemplatesPanelViewFrontend
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperTemplatesPanelViewSearch )
		.vcExtendUI( vc.HelperPanelViewResizable )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			panelName: 'template_window',
			showMessageDisabled: false,
			show: function () {
				this.clearSearch();
				vc.TemplateWindowUIPanelFrontendEditor.__super__.show.call( this );
			},
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
				e && ! e.isClearSearch && this.clearSearch();
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

	$.fn.vcAccordion.Constructor.prototype.collapseTemplate = function () {
		var $allTriggers;
		var $activeTriggers;
		var $this,
			$triggers;

		$this = this.$element;
		var i;
		i = 0;
		$allTriggers = this.getContainer().find( '[data-vc-preview-handler]' ).each( function () {
			var accordion, $this;
			$this = $( this );
			accordion = $this.data( 'vc.accordion' );
			if ( 'undefined' === typeof(accordion) ) {
				$this.vcAccordion();
				accordion = $this.data( 'vc.accordion' );
			}
			accordion && accordion.setIndex && accordion.setIndex( i ++ );
		} );

		$activeTriggers = $allTriggers.filter( function () {
			var $this, accordion;
			$this = $( this );
			accordion = $this.data( 'vc.accordion' );

			return accordion.getTarget().hasClass( accordion.activeClass );
		} );

		$triggers = $activeTriggers.filter( function () {
			return $this[ 0 ] !== this;
		} );

		if ( $triggers.length ) {
			$.fn.vcAccordion.call( $triggers, 'hide' );
		}
		// toggle preview
		if ( this.isActive() ) {
			$.fn.vcAccordion.call( $this, 'hide' );
		} else {
			$.fn.vcAccordion.call( $this, 'show' );
			var $triggerPanel = $this.closest( '.vc_ui-list-bar-item' );
			var $wrapper = $this.closest( '[data-template_unique_id]' );
			var $panel = $wrapper.closest('[data-vc-ui-element=panel-content]').parent();
			setTimeout(function () {
				if (Math.round($wrapper.offset().top - $panel.offset().top) < 0) {
					var posit = Math.round($wrapper.offset().top - $panel.offset().top + $panel.scrollTop() - $triggerPanel.height());
					$panel.animate({scrollTop: posit}, 400);
				}
			}, 400);
		}
	};
})( window.jQuery );