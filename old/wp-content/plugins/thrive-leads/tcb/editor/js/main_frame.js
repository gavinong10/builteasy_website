(function ( $ ) {
	/** current post constructor - if needed **/
	var Post = Backbone.Model.extend( {} );

	var TCB_Main = Backbone.View.extend( {
		device: 'desktop',
		responsive: {
			desktop: {
				media: '(min-width: 300px)',
				rule_index: 0
			},
			tablet: {
				media: '(max-width: 1023px)',
				rule_index: 1
			},
			mobile: {
				media: '(max-width: 767px)',
				rule_index: 2
			}
		},
		media_query_tpl: function () {
			return this.responsive[this.device].media;
		},
		media_rule_index: function () {
			return this.responsive[this.device].rule_index;
		},
		all_media: function () {
			var arr = [];
			$.each( this.responsive, function ( device, data ) {
				arr.push( data.media );
			} );

			return arr;
		},
		events: {
			'click #tve_collapse_editor_btn': 'collapse_cpanel',
			'click #tve_flipEditor': 'flip_cpanel',
			'click #tve_flipColor': 'flip_color',
			'click .tve_click': 'forward_click',
			'click .tve-undoredo': 'undo_redo',
			'click #tve_update_content': 'save',
			'dragstart': 'drag_start',
			'dragend': 'drag_end',
			'click .cp_draggable': 'cpanel_elem_click',
			'click .tcb-preview': 'change_preview',
			'keyup': 'keyup',
			'keydown': 'keydown'
		},
		initialize: function () {
			this.$cpanel = this.$( '#tve_cpanel' );
			this.$loader = this.$( '#tve_page_loader' );
			this.$cpanel.find( '.cp_draggable' ).attr( 'draggable', 'true' );
			this.$container = this.$( '#tcb-frame-container' );

			TVE_Content_Builder.controls.dropdown_menus();

			this.admin_bar();

			this.custom_events();

			this.key_listeners();
		},
		keyup: function ( e ) {
			this.trigger( 'keyup', e );
		},
		keydown: function ( e ) {
			this.trigger( 'keydown', e );
		},
		custom_events: function () {
			this.on( 'editorloaded', this.editor_loaded );
			this.on( 'overlayevent', this.overlay );
			this.on( 'undoredochange', this.undo_redo_update );
			this.on( 'hide_sub_menu', this.hide_sub_menu );
			this.on( 'child-drag-end', this.child_drag_end );
			this.on( 'lb_opening', function () {
				this.$el.addClass( 'frame-maximized' );
			} );
			this.on( 'lb_close', function () {
				this.$el.removeClass( 'frame-maximized' );
			} );
			this.on( 'trigger_action', this.trigger_action );
		},
		trigger_action: function ( $target, action ) {
			if ( ! ($target instanceof jQuery) ) {
				$target = $( $target );
			}

			if ( typeof $target[action] === 'function' ) {
				$target[action]();
			}
		},
		admin_bar: function () {
			var $admin_bar = $( '#wpadminbar' );
			var _height = $admin_bar.length ? parseInt( $admin_bar.height() ) : 0;
			$( '.tve-admin-padding' ).css( 'padding-top', _height + 'px' );
			$( '.tve-admin-position-top' ).css( 'top', _height + 'px' );
		},
		editor_loaded: function () {
			this.overlay( true );
			this.$( 'iframe#tve-editor-frame' ).contents().on( 'click', 'a', function () {
				if ( ! $( this ).parents( '#tve_lightbox_frame, #tve_cpanel_onpage' ).length ) {
					return false;
				}
			} );
		},
		overlay: function ( close_it ) {
			if ( close_it ) {
				this.$loader.removeClass( 'tve-open' );
				return;
			}
			this.$loader.addClass( 'tve-open' );
		},
		collapse_cpanel: function ( event ) {
			this.$el.add( this.$cpanel ).toggleClass( 'tve_editor_collapse' );
			return false;
		},
		flip_cpanel: function ( event ) {
			this.hide_sub_menu();
			this.$el.add( this.$cpanel ).toggleClass( 'tve_cpanelFlip' );
			var _value = this.$el.hasClass( 'tve_cpanelFlip' ) ? 'left' : 'right';
			$.post( tcb_main_const.ajax_url, {
				action: 'tve_editor_display_config',
				attribute: 'position',
				value: _value,
				security: tcb_main_const.nonce
			} );
			this.trigger( 'cpanelflip', _value );
			return false;
		},
		flip_color: function ( event ) {
			this.hide_sub_menu();
			this.$cpanel.toggleClass( 'tve_is_dark' );
			var color = this.$cpanel.hasClass( 'tve_is_dark' ) ? 'dark' : 'light';
			$.post( tcb_main_const.ajax_url, {
				action: 'tve_editor_display_config',
				attribute: 'color',
				value: color,
				security: tcb_main_const.nonce
			} );
			this.trigger( 'cpanelcolorchange', color );
			return false;
		},
		hide_sub_menu: function () {
			this.$( '.active_sub_menu' ).hide();
		},
		/**
		 * forward click event to the child frame
		 * @param event
		 */
		forward_click: function ( event ) {
			this.trigger( 'clickevent', event );
			return false;
		},
		undo_redo: function ( event ) {
			this.trigger( 'undoredo', $( event.target ).data( 'type' ) );
			return false;
		},
		save: function () {
			this.trigger( 'tve_update_content' );
			return false;
		},
		undo_redo_update: function ( data ) {
			if ( data.undo ) {
				this.$( '#tve_undo_manager' )[data.undo]( 'tve-disabled' );
			}
			if ( data.redo ) {
				this.$( '#tve_redo_manager' )[data.redo]( 'tve-disabled' );
			}
		},
		drag_start: function ( e ) {
			var $target = $( e.target );
			if ( ! $target.hasClass( 'cp_draggable' ) || this.device == 'mobile' ) {
				return false;
			}
			e.originalEvent.dataTransfer.setData( 'text/plain', null );
			e.target.style.opacity = 0.6;
			e.stopPropagation();
			this.dragged_elem = this._build_elem_data( $target );
			this.trigger( 'dragstart', this.dragged_elem );
		},
		drag_end: function ( e ) {
			if ( e ) {
				e.target.style.opacity = 1;
			}
			this.dragged_elem = null;
			this.trigger( 'main-drag-end' );
		},
		_build_elem_data: function ( $elem ) {
			return {
				type: $elem.data( 'elem' ),
				data: $elem.data(),
				post_data: $elem.find( 'input' ).serializeArray()
			};
		},
		cpanel_elem_click: function ( e ) {
			this.trigger( 'insertelement', this._build_elem_data( $( e.currentTarget ) ) );
			return false;
		},
		child_drag_end: function () {
			this.dragged_elem = null;
		},
		key_listeners: function () {
			var self = this;
			$( document ).on( "keydown", function ( e ) {
				if ( e.ctrlKey && ! e.altKey ) {
					switch ( e.which ) {
						case 83: /* CTRL + S */
							return self.save();
							break;
						case 89: /* CTRL + Y */
							self.trigger( 'undoredo', 'redo' );
							break;
						case 90: /* CTRL + Z */
							self.trigger( 'undoredo', 'undo' );
							break;
					}
				}
			} );
		},
		change_preview: function ( e ) {
			var $btn = $( e.currentTarget ),
				self = this;
			requestAnimationFrame( function () {
				self.$container.removeClass( 'preview-desktop preview-tablet preview-mobile' ).addClass( 'preview-' + $btn.data( 'screen' ) );
			} );
			$btn.siblings().removeClass( 'selected' );
			$btn.addClass( 'selected' );
			this.device = $btn.data( 'screen' );

			this.trigger( 'change_preview', this.device );

			return false;
		}
	} );

	$( function () {
		window.TCB_Main = new TCB_Main( {
			el: document.body
		} );
	} );

})( jQuery );