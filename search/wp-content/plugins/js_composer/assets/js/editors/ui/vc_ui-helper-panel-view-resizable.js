/* global vc, i18nLocale, getUserSetting, setUserSetting */
(function ( $ ) {
	'use strict';
	vc.HelperPanelViewResizable = {
		sizeInitialized: false,
		uiEvents: {
			'show': 'setSavedSize initResize',
			'tabChange': 'setDefaultHeightSettings',
			'afterMinimize': 'setupOnMinimize',
			'afterUnminimize': 'initResize',
			'fixElContainment': 'saveUIPanelSizes'
		},
		setDefaultHeightSettings: function () {
			this.$el.css( 'height', 'auto' );
			this.$el.css( 'maxHeight', '75vh' );
		},
		initResize: function () {
			var _this = this;
			this.$el.data( 'uiResizable' ) && this.$el.resizable( 'destroy' );
			this.$el.resizable( {
				minHeight: 240,
				minWidth: 380,
				resize: function () {
					_this.trigger( 'resize' );
				},
				handles: "n, e, s, w, ne, se, sw, nw",
				start: function ( e, ui ) {
					_this.trigger('beforeResizeStart');
					_this.$el.css( 'maxHeight', 'none' );
					_this.$el.css( 'height', ui.size.height);
					$( 'iframe' ).css( 'pointerEvents', 'none' ); // TODO: rewrite with css
					_this.trigger('afterResizeStart');
				},
				stop: function () {
					_this.trigger('beforeResizeStop');
					$( 'iframe' ).css( 'pointerEvents', '' );
					_this.saveUIPanelSizes();
					_this.trigger('afterResizeStop');
				}
			} );
			this.content().addClass( 'vc_properties-list-init' );
			this.trigger( 'resize' );
		},
		setSavedSize: function () {
			this.setDefaultHeightSettings();
			if ( vc.is_mobile ) {
				return false;
			}
			var sizes = {
				width: getUserSetting( this.panelName + '_vcUIPanelWidth' ),
				left: getUserSetting( this.panelName + '_vcUIPanelLeft' ).replace( 'minus', '-' ),
				top: getUserSetting( this.panelName + '_vcUIPanelTop' ).replace( 'minus', '-' )
			};
			if ( ! _.isEmpty( sizes.width ) ) {
				this.$el.width( sizes.width );
			}
			if ( ! _.isEmpty( sizes.left ) ) {
				this.$el.css( 'left', sizes.left );
			}
			if ( ! _.isEmpty( sizes.top ) ) {
				this.$el.css( 'top', sizes.top );
			}
			this.sizeInitialized = true;
		},
		saveUIPanelSizes: function () {
			if ( false === this.sizeInitialized ) {
				return false;
			}
			var sizes = {
				width: this.$el.width(),
				left: parseInt( this.$el.css( 'left' ), 10 ),
				top: parseInt( this.$el.css( 'top' ), 10 )
			};
			setUserSetting( this.panelName + '_vcUIPanelWidth', sizes.width );
			setUserSetting( this.panelName + '_vcUIPanelLeft', sizes.left.toString().replace( '-', 'minus' ) + 'px' );
			setUserSetting( this.panelName + '_vcUIPanelTop', sizes.top.toString().replace( '-', 'minus' ) + 'px' ); // WordPress doesnt save `-` symbol
		},
		setupOnMinimize: function () {
			this.$el.data( 'uiResizable' ) && this.$el.resizable( 'destroy' );
			this.$el.resizable( {
				minWidth: 380,
				handles: 'w, e',
				start: function ( e ) {
					$( 'iframe' ).css( 'pointerEvents', 'none' );
				},
				stop: function () {
					$( 'iframe' ).css( 'pointerEvents', '' );
				}
			} );
		}
	};
})( window.jQuery );