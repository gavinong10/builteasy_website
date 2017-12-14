/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';

	vc.EditElementUIPanel = vc.EditElementPanelView
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.vcExtendUI( vc.HelperPanelViewResizable )
		.vcExtendUI( vc.HelperPanelViewDraggable )
		.extend( {
			el: '#vc_ui-panel-edit-element',
			events: {
				'click [data-vc-ui-element="button-save"]': 'save',
				'click [data-vc-ui-element="button-close"]': 'hide',
				'click [data-vc-ui-element="button-minimize"]': 'toggleOpacity',
				'click [data-vc-ui-element="panel-tab-control"]': 'changeTab'
			},
			initialize: function () {
				vc.EditElementUIPanel.__super__.initialize.call( this );

				this.on( 'afterResizeStart', function () {
					this.$el.css( 'maxHeight', 'none' );
				} );
			},
			show: function () {
				vc.EditElementUIPanel.__super__.show.call( this );
				$( '[data-vc-ui-element="panel-tabs-controls"]', this.$el ).remove();

				this.$el.css( 'maxHeight', '75vh' );
			},
			setTitle: function () {
				this.$el.find( '[data-vc-ui-element="panel-title"]' ).text( vc.getMapped( this.model.get( 'shortcode' ) ).name + ' ' + i18nLocale.settings );
				return this;
			},
			tabsMenu: function () {
				if ( false === this.tabsInit ) {
					this.tabsInit = true;
					var $tabsMenu = this.$el.find( '[data-vc-ui-element="panel-tabs-controls"]' );
					if ( $tabsMenu.length ) {
						this.$tabsMenu = $tabsMenu;
					}
				}
				return this.$tabsMenu;
			},
			buildTabs: function () {
				var $tabs = this.content().find( '[data-vc-ui-element="panel-tabs-controls"]' );
				$tabs.prependTo( '[data-vc-ui-element="panel-header-content"]' );
			},
			changeTab: function ( e ) {
				e.preventDefault();
				var $tab = $( e.currentTarget );
				if ( ! $tab.parent().hasClass( 'vc_active' ) ) {
					this.$el.find( '[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])' ).removeClass( 'vc_active' );
					$tab.parent().addClass( 'vc_active' );
					this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_active' ).removeClass( 'vc_active' );
					this.active_tab_index = this.$el.find( $tab.data( 'vcUiElementTarget' ) ).addClass( 'vc_active' ).index();
					this.initParams();
					this.$tabsMenu && this.$tabsMenu.vcTabsLine( 'checkDropdownContainerActive' );
					// In Firefox, scrollTop(0) is buggy, scrolling to non-0 value first fixes it
					this.$content.parent().scrollTop( 1 ).scrollTop( 0 );
					this.trigger( 'tabChange' );
				}
			},
			checkTabs: function () {
				var _this = this;
				if ( false === this.tabsInit ) {
					this.tabsInit = true;
					this.$tabsMenu = this.$el.find( '[data-vc-ui-element="panel-tabs-controls"]' );
				}
				if ( this.tabsMenu() ) {
					this.content().find( '[data-vc-ui-element="panel-edit-element-tab"]' ).each( function ( index ) {
						var $tabControl = _this.$tabsMenu.find( '> [data-tab-index="' + index + '"]' );
						if ( $( this ).find( '[data-vc-ui-element="panel-shortcode-param"]:not(".vc_dependent-hidden")' ).length ) {
							if ( $tabControl.hasClass( 'vc_dependent-hidden' ) ) {
								$tabControl.removeClass( 'vc_dependent-hidden' );
								window.setTimeout( function () {
									$tabControl.removeClass( 'vc_tab-color-animated' );
								}, 200 );
							}
						} else {
							$tabControl.addClass( 'vc_dependent-hidden' );
						}
					} );
					this.$tabsMenu.vcTabsLine( 'refresh' );
					this.$tabsMenu.vcTabsLine( 'moveTabs' );
				}
			}
		} );
})( window.jQuery );
