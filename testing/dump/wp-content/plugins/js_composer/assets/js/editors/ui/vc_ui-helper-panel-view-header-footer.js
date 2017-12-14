/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';
	vc.HelperPanelViewHeaderFooter = {
		buttonMessageTimeout: false,
		events: {
			'click [data-vc-ui-element="button-save"]': 'save', // need to save, hide into this code.
			'click [data-vc-ui-element="button-close"]': 'hide',
			'click [data-vc-ui-element="button-minimize"]': 'toggleOpacity'
		},
		uiEvents: {
			'save': 'setButtonMessage',
			'render': 'clearButtonMessage'
		},
		resetMinimize: function () {
			this.$el.removeClass( 'vc_panel-opacity' );
			this.$el.removeClass( 'vc_minimized' );
		},
		toggleOpacity: function ( e ) {
			e.preventDefault();
			var animationClass = 'vc_animating';
			var minimizedClass = 'vc_minimized';
			var _this = this;
			var $target = this.$el;
			var $panel = $target.find( $target.data( 'vcPanel' ) );
			var $panelContainer = $panel.closest( $panel.data( 'vcPanelContainer' ) );
			var timing = 400;
			var $trigger = $( e.currentTarget );

			if ( 'undefined' === typeof($target.data( 'vcHasHeight' )) ) {
				$target.data( 'vcHasHeight', (function ( $element, property ) {
					var styles = $element.attr( "style" ),
						hasStyle = false;
					styles && styles.split( ";" ).forEach( function ( e ) {
						var style = e.split( ":" );
						if ( $.trim( style[ 0 ] ) === property ) {
							hasStyle = true;
						}
					} );
					return hasStyle;
				})( $target, 'height' ) );
			}

			if ( $target.hasClass( minimizedClass ) ) {
				if ( 'undefined' === typeof($target.data( 'vcMinimizeHeight' ) ) ) {
					$target.data( 'vcMinimizeHeight', $( window ).height() - $( window ).height() * 0.2 );
				}
				$target.animate( {
					height: $target.data( 'vcMinimizeHeight' )
				}, {
					duration: timing,
					start: function () {
						$trigger.prop( 'disabled', true );
						$target.addClass( animationClass );
						_this.tabsMenu && _this.tabsMenu() && _this.tabsMenu().vcTabsLine( 'moveTabs' );
					},
					complete: function () {
						$target.removeClass( minimizedClass );
						$target.removeClass( animationClass );
						if ( ! $target.data( 'vcHasHeight' ) ) {
							$target.css( { height: '' } );
						}
						_this.trigger( 'afterUnminimize' );

						$trigger.prop( 'disabled', false );
					}
				} );
			} else {
				$target.data( 'vcMinimizeHeight', $target.height() );
				$target.animate( {
					height: $panel.outerHeight() + $panelContainer.outerHeight() - $panelContainer.height()
				}, {
					duration: timing,
					start: function () {
						$trigger.prop( 'disabled', true );
						$target.addClass( animationClass );
					},
					complete: function () {
						$target.addClass( minimizedClass );
						$target.removeClass( animationClass );
						$target.css( { height: '' } );
						_this.trigger( 'afterMinimize' );
						$trigger.prop( 'disabled', false );
					}
				} );
			}
		},
		setButtonMessage: function () {
			var currentTextHtml, $saveBtn, message, type;

			// Binding to context
			this.clearButtonMessage = _.bind( this.clearButtonMessage, this );
			// we can show only if frontend and only if old message cleared (to avoid double execution)
			if ( vc.frame_window && ! this.buttonMessageTimeout ) {
				type = 'success'; // currently only one message type
				message = window.i18nLocale.ui_saved;
				$saveBtn = this.$el.find( '[data-vc-ui-element="button-save"]' );
				currentTextHtml = $saveBtn.html();
				$saveBtn.addClass( 'vc_ui-button-' + type );
				$saveBtn.removeClass( 'vc_ui-button-action' );
				$saveBtn.data( 'vcCurrentTextHtml', currentTextHtml );
				$saveBtn.data( 'vcCurrentTextType', type );
				$saveBtn.html( message );
				_.delay( this.clearButtonMessage, 5000 );
				this.buttonMessageTimeout = true;
			}
		},
		clearButtonMessage: function () {
			var type, currentTextHtml, $saveBtn;

			if ( this.buttonMessageTimeout ) {
				window.clearTimeout( this.buttonMessageTimeout );
				$saveBtn = this.$el.find( '[data-vc-ui-element="button-save"]' );
				currentTextHtml = $saveBtn.data( 'vcCurrentTextHtml' ) || 'Save';
				type = $saveBtn.data( 'vcCurrentTextType' );
				$saveBtn.html( currentTextHtml );
				this.buttonMessageTimeout = false;
				$saveBtn.removeClass( 'vc_ui-button-' + type );
				$saveBtn.addClass( 'vc_ui-button-action' );
			}
		}
	};
})( window.jQuery );