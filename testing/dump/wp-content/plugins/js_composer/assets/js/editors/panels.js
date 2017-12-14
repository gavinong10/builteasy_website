/* =========================================================
 * lib/panels.js v0.5.0
 * =========================================================
 * Copyright 2014 Wpbakery
 *
 * Visual composer panels & modals for frontend editor
 *
 * ========================================================= */
/* global Backbone, vc */
(function ( $ ) {
	'use strict';
	if ( _.isUndefined( window.vc ) ) {
		window.vc = {};
	}
	vc.showSpinner = function () {
		$( '#vc_logo' ).addClass( 'vc_ajax-loading' );
	};
	vc.hideSpinner = function () {
		$( '#vc_logo' ).removeClass( 'vc_ajax-loading' );
	};
	$( document ).ajaxSend( function ( e, xhr, req ) {
		req && req.data && 'string' === typeof(req.data) && req.data.match( /vc_inline=true/ ) && vc.showSpinner();
	} ).ajaxStop( function () {
		vc.hideSpinner();
	} );
	vc.active_panel = false;
	vc.closeActivePanel = function ( model ) {
		if ( ! this.active_panel ) {
			return false;
		}
		if ( model && vc.active_panel.model && vc.active_panel.model.get( 'id' ) === model.get( 'id' ) ) {
			this.active_panel.hide();
		} else if ( ! model ) {
			this.active_panel.hide();
		}
	};
	vc.activePanelName = function () {
		return this.active_panel && this.active_panel.panelName ? this.active_panel.panelName : null;
	};
	vc.updateSettingsBadge = function () {
		var value = vc.$custom_css.val();
		if ( value && '' !== value.trim() ) {
			$( '#vc_post-css-badge' ).show();
		} else {
			$( '#vc_post-css-badge' ).hide();
		}
	};
	/**
	 * Modal prototype
	 *
	 * @type {*}
	 */
	vc.ModalView = Backbone.View.extend( {
		message_box_timeout: false,
		events: {
			'hidden.bs.modal': 'hide',
			'shown.bs.modal': 'shown'
		},
		initialize: function () {
			_.bindAll( this, 'setSize', 'hide' );
		},
		setSize: function () {
			var height = $( window ).height() - 150;
			this.$content.css( 'maxHeight', height );
			this.trigger( 'setSize' );
		},
		render: function () {
			$( window ).bind( 'resize.ModalView', this.setSize );
			this.setSize();
			vc.closeActivePanel();
			this.$el.modal( 'show' );
			return this;
		},
		showMessage: function ( text, type ) {
			this.message_box_timeout && this.$el.find( '.vc_message' ).remove() && window.clearTimeout( this.message_box_timeout );
			this.message_box_timeout = false;
			var $message_box = $( '<div class="vc_message type-' + type + '"></div>' );
			this.$el.find( '.vc_modal-body' ).prepend( $message_box );
			$message_box.text( text ).fadeIn();
			this.message_box_timeout = window.setTimeout( function () {
				$message_box.remove();
			}, 6000 );
		},
		hide: function () {
			$( window ).unbind( 'resize.ModalView' );
		},
		shown: function () {
		}
	} );
	vc.element_start_index = 0;
	/**
	 * Add element block to page or shortcodes container.
	 *
	 * @deprecated 4.7
	 *
	 * @type {*}
	 */
	vc.AddElementBlockView = vc.ModalView.extend( {
		el: $( '#vc_add-element-dialog' ),
		prepend: false,
		builder: '',
		events: {
			'click .vc_shortcode-link': 'createElement',
			'keyup #vc_elements_name_filter': 'filterElements',
			'hidden.bs.modal': 'hide',
			'show.bs.modal': 'buildFiltering',
			'click .wpb-content-layouts-container [data-filter]': 'filterElements',
			'shown.bs.modal': 'shown'
		},
		buildFiltering: function () {
			this.do_render = false;
			var item_selector, tag, not_in;

			item_selector = '[data-vc-ui-element="add-element-button"]';
			tag = this.model ? this.model.get( 'shortcode' ) : 'vc_column';
			not_in = this._getNotIn( tag );
			$( '#vc_elements_name_filter' ).val( '' );
			this.$content.addClass( 'vc_filter-all' );
			this.$content.attr( 'data-vc-ui-filter', '*' );
			// New vision
			var mapped = vc.getMapped( tag );
			var as_parent = tag && ! _.isUndefined( mapped.as_parent ) ? mapped.as_parent : false;
			if ( _.isObject( as_parent ) ) {
				var parent_selector = [];
				if ( _.isString( as_parent.only ) ) {
					parent_selector.push( _.reduce( as_parent.only.replace( /\s/, '' ).split( ',' ),
						function ( memo, val ) {
							return memo + ( _.isEmpty( memo ) ? '' : ',') + '[data-element="' + val.trim() + '"]';
						},
						'' ) );
				}
				if ( _.isString( as_parent.except ) ) {
					parent_selector.push( _.reduce( as_parent.except.replace( /\s/, '' ).split( ',' ),
						function ( memo, val ) {
							return memo + ':not([data-element="' + val.trim() + '"])';
						},
						'' ) );
				}
				item_selector += parent_selector.join( ',' );
			} else {
				if ( not_in ) {
					item_selector = not_in;
				}
			}
			// OLD fashion
			if ( tag && ! _.isUndefined( mapped.allowed_container_element ) ) {
				if ( ! mapped.allowed_container_element ) {
					item_selector += ':not([data-is-container=true])';
				} else if ( _.isString( mapped.allowed_container_element ) ) {
					item_selector += ':not([data-is-container=true]), [data-element=' + mapped.allowed_container_element + ']';
				}
			}
			this.$buttons.removeClass( 'vc_visible' ).addClass( 'vc_inappropriate' );
			$( item_selector, this.$content ).removeClass( 'vc_inappropriate' ).addClass( 'vc_visible' );
			this.hideEmptyFilters();
		},
		hideEmptyFilters: function () {
			this.$el.find( '.vc_filter-content-elements .active' ).removeClass( 'active' );
			this.$el.find( '.vc_filter-content-elements > :first' ).addClass( 'active' );
			var self = this;
			this.$el.find( '[data-filter]' ).each( function () {
				if ( $( $( this ).data( 'filter' ) + '.vc_visible:not(.vc_inappropriate)', self.$content ).length ) {
					$( this ).parent().show();
				} else {
					$( this ).parent().hide();
				}
			} );
		},
		render: function ( model, prepend ) {
			this.builder = new vc.ShortcodesBuilder();
			this.prepend = _.isBoolean( prepend ) ? prepend : false;
			this.place_after_id = _.isString( prepend ) ? prepend : false;
			this.model = _.isObject( model ) ? model : false;
			this.$content = this.$el.find( '[data-vc-ui-element="panel-add-element-list"]' );
			this.$buttons = $( '[data-vc-ui-element="add-element-button"]', this.$content );
			this.preventDoubleExecution = false;
			return vc.AddElementBlockView.__super__.render.call( this );
		},
		hide: function () {
			if ( this.do_render ) {
				if ( this.show_settings ) {
					this.showEditForm();
				}
				this.exit();
			}
		},
		showEditForm: function () {
			vc.edit_element_block_view.render( this.builder.last() );
		},
		exit: function () {
			this.builder.render();
		},
		createElement: function ( e ) {
			var $control, tag;
			var _this, shortcode, i;
			if ( this.preventDoubleExecution ) {
				return;
			}
			this.preventDoubleExecution = true;
			this.do_render = true;
			e.preventDefault();
			$control = $( e.currentTarget );
			tag = $control.data( 'tag' );
			if ( false === this.model && 'vc_row' !== tag ) {
				this.builder
					.create( { shortcode: 'vc_row' } )
					.create( { shortcode: 'vc_column', parent_id: this.builder.lastID(), params: { width: '1/1' } } );
				this.model = this.builder.last();
			} else if ( false !== this.model && 'vc_row' === tag ) {
				tag += '_inner';
			}
			var params = {
				shortcode: tag,
				parent_id: (this.model ? this.model.get( 'id' ) : false)
			};
			if ( this.prepend ) {
				params.order = 0;
				var shortcodeFirst = vc.shortcodes.findWhere( { parent_id: this.model.get( 'id' ) } );
				if ( shortcodeFirst ) {
					params.order = shortcodeFirst.get( 'order' ) - 1;
				}
				vc.activity = 'prepend';
			} else if ( this.place_after_id ) {
				params.place_after_id = this.place_after_id;
			}

			this.builder.create( params );

			// extend default params with settings presets if there are any
			for ( i = this.builder.models.length - 1;
				  i >= 0;
				  i -- ) {
				shortcode = this.builder.models[ i ].get( 'shortcode' );
				if ( 'undefined' !== typeof(window.vc_settings_presets[ shortcode ]) ) {
					this.builder.models[ i ].attributes.params = _.extend(
						this.builder.models[ i ].attributes.params,
						window.vc_settings_presets[ shortcode ]
					);
				}
			}

			if ( 'vc_row' === tag ) {
				this.builder.create( {
					shortcode: 'vc_column',
					parent_id: this.builder.lastID(),
					params: { width: '1/1' }
				} );
			} else if ( 'vc_row_inner' === tag ) {
				this.builder.create( {
					shortcode: 'vc_column_inner',
					parent_id: this.builder.lastID(),
					params: { width: '1/1' }
				} );
			}
			var mapped = vc.getMapped( tag );
			if ( _.isString( mapped.default_content ) && mapped.default_content.length ) {
				var newData = this.builder.parse( {},
					mapped.default_content,
					this.builder.last().toJSON() );
				_.each( newData, function ( object ) {
					object.default_content = true;
					this.builder.create( object );
				}, this );
			}
			this.show_settings = ! (_.isBoolean( mapped.show_settings_on_create ) && false === mapped.show_settings_on_create);
			_this = this;
			this.$el.one( 'hidden.bs.modal', function () {
				_this.preventDoubleExecution = false;
			} ).modal( 'hide' );
		},
		_getNotIn: _.memoize( function ( tag ) {
			var selector = _.reduce( vc.map, function ( memo, shortcode ) {
				var separator = _.isEmpty( memo ) ? '' : ',';
				if ( _.isObject( shortcode.as_child ) ) {
					if ( _.isString( shortcode.as_child.only ) ) {
						if ( ! _.contains( shortcode.as_child.only.replace( /\s/, '' ).split( ',' ), tag ) ) {
							memo += separator + '[data-element=' + shortcode.base + ']';
						}
					}
					if ( _.isString( shortcode.as_child.except ) ) {
						if ( _.contains( shortcode.as_child.except.replace( /\s/, '' ).split( ',' ), tag ) ) {
							memo += separator + '[data-element=' + shortcode.base + ']';
						}
					}
				} else if ( false === shortcode.as_child ) {
					memo += separator + '[data-element=' + shortcode.base + ']';
				}
				return memo;
			}, '' );
			return '[data-vc-ui-element="add-element-button"]:not(' + selector + ')';
		} ),
		filterElements: function ( e ) {
			e.stopPropagation();
			e.preventDefault();
			var $control = $( e.currentTarget ),
				filter = '[data-vc-ui-element="add-element-button"]',
				name_filter = $( '#vc_elements_name_filter' ).val();
			this.$content.removeClass( 'vc_filter-all' );
			if ( $control.is( '[data-filter]' ) ) {
				$( '.wpb-content-layouts-container .isotope-filter .active', this.$content ).removeClass( 'active' );
				$control.parent().addClass( 'active' );
				var filter_value = $control.data( 'filter' );
				filter += filter_value;
				if ( '*' === filter_value ) {
					this.$content.addClass( 'vc_filter-all' );
				} else {
					this.$content.removeClass( 'vc_filter-all' );
				}
				this.$content.attr( 'data-vc-ui-filter', filter_value.replace( '.js-category-', '' ) );
				$( '#vc_elements_name_filter' ).val( '' );
			} else if ( 0 < name_filter.length ) {
				filter += ":containsi('" + name_filter + "'):not('.vc_element-deprecated')";
				$( '.wpb-content-layouts-container .isotope-filter .active', this.$content ).removeClass( 'active' );
				this.$content.attr( 'data-vc-ui-filter', 'name:' + name_filter );
			} else if ( ! name_filter.length ) {
				$( '.wpb-content-layouts-container .isotope-filter [data-filter="*"]' ).parent().addClass( 'active' );
				this.$content.attr( 'data-vc-ui-filter', '*' );
				this.$content.addClass( 'vc_filter-all' );
			}
			$( '.vc_visible', this.$content ).removeClass( 'vc_visible' );
			$( filter, this.$content ).addClass( 'vc_visible' );
		},
		shown: function () {
			if ( ! vc.is_mobile ) {
				$( '#vc_elements_name_filter' ).focus();
			}
		}
	} );
	/**
	 * Add element to admin
	 *
	 * @deprecated 4.7
	 *
	 * @type {*}
	 */
	vc.AddElementBlockViewBackendEditor = vc.AddElementBlockView.extend( {
		render: function ( model, prepend ) {
			this.prepend = _.isBoolean( prepend ) ? prepend : false;
			this.place_after_id = _.isString( prepend ) ? prepend : false;
			this.model = _.isObject( model ) ? model : false;
			this.$content = this.$el.find( '[data-vc-ui-element="panel-add-element-list"]' );
			this.$buttons = $( '[data-vc-ui-element="add-element-button"]', this.$content );
			return vc.AddElementBlockView.__super__.render.call( this );
		},
		createElement: function ( e ) {
			var that, shortcode;
			if ( this.preventDoubleExecution ) {
				return;
			}
			this.preventDoubleExecution = true;
			var model, column, row;
			_.isObject( e ) && e.preventDefault();
			this.do_render = true;
			var tag = $( e.currentTarget ).data( 'tag' );
			if ( false === this.model ) {
				row = vc.shortcodes.create( { shortcode: 'vc_row' } );
				column = vc.shortcodes.create( {
					shortcode: 'vc_column',
					params: { width: '1/1' },
					parent_id: row.id,
					root_id: row.id
				} );
				if ( 'vc_row' !== tag ) {
					model = vc.shortcodes.create( {
						shortcode: tag,
						parent_id: column.id,
						root_id: row.id
					} );
				} else {
					model = row;
				}
			} else {
				if ( 'vc_row' === tag ) {
					row = vc.shortcodes.create( {
						shortcode: 'vc_row_inner',
						parent_id: this.model.id,
						order: (this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder())
					} );
					model = vc.shortcodes.create( {
						shortcode: 'vc_column_inner',
						params: { width: '1/1' },
						parent_id: row.id,
						root_id: row.id
					} );
				} else {
					model = vc.shortcodes.create( {
						shortcode: tag,
						parent_id: this.model.id,
						order: (this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()),
						root_id: this.model.get( 'root_id' )
					} );
				}
			}
			this.show_settings = ! (_.isBoolean( vc.getMapped( tag ).show_settings_on_create ) && false === vc.getMapped( tag ).show_settings_on_create);
			this.model = model;

			// extend default params with settings presets if there are any
			shortcode = this.model.get( 'shortcode' );
			if ( 'undefined' !== typeof(window.vc_settings_presets[ shortcode ]) ) {
				this.model.attributes.params = _.extend(
					this.model.attributes.params,
					window.vc_settings_presets[ shortcode ]
				);
			}

			that = this;
			this.$el.one( 'hidden.bs.modal', function () {
				that.preventDoubleExecution = false;
			} ).modal( 'hide' );
		},
		showEditForm: function () {
			vc.edit_element_block_view.render( this.model );
		},
		exit: function () {
		},
		getFirstPositionIndex: function () {
			vc.element_start_index -= 1;
			return vc.element_start_index;
		}
	} );
	/**
	 * Panel prototype
	 */
	vc.PanelView = vc.View.extend( {
		mediaSizeClassPrefix: 'vc_media-',
		customMediaQuery: true,
		panelName: 'panel',
		draggable: false,
		$body: false,
		$tabs: false,
		$content: false,
		events: {
			'click [data-dismiss=panel]': 'hide',
			'mouseover [data-transparent=panel]': 'addOpacity',
			'click [data-transparent=panel]': 'toggleOpacity',
			'mouseout [data-transparent=panel]': 'removeOpacity',
			'click .vc_panel-tabs-link': 'changeTab'
		},
		_vcUIEventsHooks: [
			{ 'resize': 'setResize' }
		],
		options: {
			startTab: 0
		},
		clicked: false,
		showMessageDisabled: true, // disabled in 4.7 due to button and new ui.
		initialize: function () {
			this.clicked = false;
			this.$el.removeClass( 'vc_panel-opacity' );
			this.$body = $( 'body' );
			this.$content = this.$el.find( '.vc_panel-body' );
			_.bindAll( this, 'setSize', 'fixElContainment', 'changeTab', 'setTabsSize' );
			this.on( 'show', this.setSize, this );
			this.on( 'setSize', this.setResize, this );
			this.on( 'render', this.resetMinimize, this );
		},
		toggleOpacity: function () {
			this.clicked = ! this.clicked;
		},
		addOpacity: function () {
			! this.clicked && this.$el.addClass( 'vc_panel-opacity' );
		},
		removeOpacity: function () {
			! this.clicked && this.$el.removeClass( 'vc_panel-opacity' );
		},
		message_box_timeout: false,
		init: function () {
		},
		render: function () {
			this.trigger( 'render' );
			this.trigger( 'afterRender' );
			return this;
		},
		show: function () {
			vc.closeActivePanel();
			this.init();
			vc.active_panel = this;
			this.clicked = false;
			this.$el.removeClass( 'vc_panel-opacity' );
			var $tabs = this.$el.find( '.vc_panel-tabs' );
			if ( $tabs.length ) {
				this.$tabs = $tabs;
				this.setTabs();
			}
			this.$el.addClass( 'vc_active' );
			if ( ! this.draggable ) {
				$( window ).trigger( 'resize' );
			} else {
				this.initDraggable();
			}
			this.fixElContainment();
			this.trigger( 'show' );
		},
		hide: function ( e ) {
			e && e.preventDefault();
			vc.active_panel = false;
			this.$el.removeClass( 'vc_active' );
		},
		content: function () {
			return this.$el.find( '.panel-body' );
		},
		setResize: function () {
			this.customMediaQuery && this.setMediaSizeClass();
		},
		setMediaSizeClass: function () {
			var modalWidth, classes;
			modalWidth = this.$el.width();
			classes = {
				xs: true,
				sm: false,
				md: false,
				lg: false
			};
			if ( 525 <= modalWidth ) {
				classes.sm = true;
			}
			if ( 745 <= modalWidth ) {
				classes.md = true;
			}
			if ( 945 <= modalWidth ) {
				classes.lg = true;
			}
			_.each( classes, function ( value, key ) {
				if ( value ) {
					this.$el.addClass( this.mediaSizeClassPrefix + key );
				} else {
					this.$el.removeClass( this.mediaSizeClassPrefix + key );
				}
			}, this );
		},
		fixElContainment: function () {
			if ( ! this.$body ) {
				this.$body = $( 'body' );
			}
			var el_w = this.$el.width(),
				container_w = this.$body.width(),
				container_h = this.$body.height();

			// To be sure that containment always correct, even after resize
			var containment = [
				- el_w + 20,
				0,
				container_w - 20,
				container_h - 30
			];
			var positions = this.$el.position();
			var new_positions = {};
			if ( positions.left < containment[ 0 ] ) {
				new_positions.left = containment[ 0 ];
			}
			if ( 0 > positions.top ) {
				new_positions.top = 0;
			}
			if ( positions.left > containment[ 2 ] ) {
				new_positions.left = containment[ 2 ];
			}
			if ( positions.top > containment[ 3 ] ) {
				new_positions.top = containment[ 3 ];
			}
			this.$el.css( new_positions );
			this.trigger( 'fixElContainment' );
			this.setSize();
		},
		/**
		 * Init draggable feature for panels to allow it Moving, also allow moving only in proper containment
		 */
		initDraggable: function () {
			this.$el.draggable( {
				iframeFix: true,
				handle: '.vc_panel-heading',
				start: this.fixElContainment,
				stop: this.fixElContainment
			} );
			this.draggable = true;
		},
		setSize: function () {
			this.trigger( 'setSize' );
		},
		setTabs: function () {
			if ( this.$tabs.length ) {
				this.$tabs.find( '.vc_panel-tabs-control' ).removeClass( 'vc_active' ).eq( this.options.startTab ).addClass( 'vc_active' );
				this.$tabs.find( '.vc_panel-tab' ).removeClass( 'vc_active' ).eq( this.options.startTab ).addClass( 'vc_active' );
				window.setTimeout( this.setTabsSize, 100 );
			}
		},
		setTabsSize: function () {
			this.$tabs && this.$tabs.parents( '.vc_with-tabs.vc_panel-body' ).css( 'margin-top',
				this.$tabs.find( '.vc_panel-tabs-menu' ).outerHeight() );
		},
		changeTab: function ( e ) {
			e && e.preventDefault && e.preventDefault();
			if ( e.target && this.$tabs ) {
				var $tab = $( e.target );
				this.$tabs.find( '.vc_active' ).removeClass( 'vc_active' );
				$tab.parent().addClass( 'vc_active' );
				this.$el.find( $tab.data( 'target' ) ).addClass( 'vc_active' );
				window.setTimeout( this.setTabsSize, 100 );
			}
		},
		showMessage: function ( text, type ) {
			if ( this.showMessageDisabled ) {
				return false;
			}
			this.message_box_timeout && this.$el.find( '.vc_panel-message' ).remove() && window.clearTimeout( this.message_box_timeout );
			this.message_box_timeout = false;
			var $message_box = $( '<div class="vc_panel-message type-' + type + '"></div>' ).appendTo( this.$el.find( '.vc_ui-panel-content-container' ) );
			$message_box.text( text ).fadeIn();
			this.message_box_timeout = window.setTimeout( function () {
				$message_box.remove();
			}, 6000 );
		},
		isVisible: function () {
			return this.$el.is( ':visible' );
		},
		resetMinimize: function () {
			this.$el.removeClass( 'vc_panel-opacity' );
		}
	} );
	/**
	 * Shortcode settings panel
	 *
	 * @type {*}
	 */
	vc.EditElementPanelView = vc.PanelView.extend( {
		panelName: 'edit_element',
		el: '#vc_properties-panel',
		// there is more than 1 element with vc_properties-list class name, so we need to increase specificity
		contentSelector: '.vc_ui-panel-content.vc_properties-list',
		minimizeButtonSelector: '[data-vc-ui-element="button-minimize"]',
		closeButtonSelector: '[data-vc-ui-element="button-close"]',
		settingsMenuSelector: '[data-vc-ui-element="settings-dropdown-list"]',
		settingsButtonSelector: '[data-vc-ui-element="settings-dropdown-button"]',
		settingsDropdownSelector: '[data-vc-ui-element="settings-dropdown"]',
		settingsPresetId: null,
		tabsInit: false,
		doCheckTabs: true,
		$tabsMenu: false,
		dependent_elements: {},
		mapped_params: {},
		draggable: false,
		panelInit: false,
		$spinner: false,
		active_tab_index: 0,
		ajax: false,
		buttonMessageTimeout: false,
		// @deprecated 4.7
		notRequestTemplate: false,
		requiredParamsInitialized: false,
		currentModelParams: false,
		events: {
			'click [data-save=true]': 'save',
			'click [data-dismiss=panel]': 'hide',
			'mouseover [data-transparent=panel]': 'addOpacity',
			'click [data-transparent=panel]': 'toggleOpacity',
			'mouseout [data-transparent=panel]': 'removeOpacity'
		},
		initialize: function () {
			_.bindAll( this, 'setSize', 'setTabsSize', 'fixElContainment', 'hookDependent' );
			this.on( 'setSize', this.setResize, this );
			this.on( 'render', this.resetMinimize, this );
			this.on( 'render', this.setTitle, this );
			this.on( 'render', this.prepareContentBlock, this );
		},
		/**
		 *
		 * @param model
		 * @param not_request_template @deprecated 4.7
		 * @returns {vc.EditElementPanelView}
		 */
		render: function ( model, not_request_template ) {
			var params;
			if ( this.$el.is( ':hidden' ) ) {
				vc.closeActivePanel();
			}
			// @deprecated 4.7
			if ( not_request_template ) {
				this.notRequestTemplate = true;
			}
			this.model = model;
			this.currentModelParams = this.model.get( 'params' );
			vc.active_panel = this;
			this.resetMinimize();
			this.clicked = false;
			this.$el.css( 'height', 'auto' );
			this.$el.css( 'maxHeight', '75vh' );
			params = this.model.setting( 'params' ) || [];
			this.$el.attr( 'data-vc-shortcode', this.model.get( 'shortcode' ) );
			this.tabsInit = false;
			this.panelInit = false;
			this.active_tab_index = 0;
			this.requiredParamsInitialized = false;
			this.mapped_params = {};
			this.dependent_elements = {};
			_.each( params, function ( param ) {
				this.mapped_params[ param.param_name ] = param;
			}, this );
			this.trigger( 'render' );
			this.show();
			this.ajax = $.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: this.ajaxData(),
				context: this
			} ).done( this.buildParamsContent );
			return this;
		},
		prepareContentBlock: function () {
			this.$content = this.notRequestTemplate ? this.$el : this.$el.find( this.contentSelector ).removeClass( 'vc_with-tabs' );
			this.$content.empty(); // if pressed multiple times
			this.$spinner = $( '<span class="vc_spinner"></span>' );
			this.$content.prepend( this.$spinner );
		},
		buildParamsContent: function ( data ) {
			var $data, $tabs, $panelHeader;
			$data = $( data );
			$tabs = $data.find( '[data-vc-ui-element="panel-tabs-controls"]' );
			$tabs.find( '.vc_edit-form-tab-control:first-child' ).addClass( 'vc_active' );
			$panelHeader = this.$el.find( '[data-vc-ui-element="panel-header-content"]' );
			$tabs.prependTo( $panelHeader );
			this.$content.html( $data );
			this.$content.removeAttr( 'data-vc-param-initialized' );
			this.active_tab_index = 0;
			this.tabsInit = false;
			this.panelInit = false;
			this.dependent_elements = {};
			this.requiredParamsInitialized = false;
			this.$content.find( '[data-vc-param-initialized]' ).removeAttr( 'data-vc-param-initialized' );
			this.init();
			// In Firefox, scrollTop(0) is buggy, scrolling to non-0 value first fixes it
			this.$content.parent().scrollTop( 1 ).scrollTop( 0 );
			this.$content.removeClass( 'vc_properties-list-init' );
			/**
			 * @deprecated 4.7
			 */
			this.$el.trigger( 'vcPanel.shown' ); // old stuff
			this.trigger( 'afterRender' );
			this.untaintSettingsPresetData();
		},
		resetMinimize: function () {
			this.$el.removeClass( 'vc_panel-opacity' );
		},
		saveSettingsAjaxData: function ( shortcode_name, title, is_default, data ) {
			return {
				action: 'vc_action_save_settings_preset',
				shortcode_name: shortcode_name,
				is_default: is_default ? 1 : 0,
				vc_inline: true,
				title: title,
				data: data,
				_vcnonce: window.vcAdminNonce
			};
		},
		saveSettings: function ( title, is_default ) {
			var shortcode_name = this.model.get( 'shortcode' ),
				data = JSON.stringify( this.getParams() ),
				success = false;

			if ( 'undefined' === typeof(title) || ! title.length ) {
				return;
			}

			if ( 'undefined' === typeof(is_default) ) {
				is_default = false;
			}

			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: this.saveSettingsAjaxData( shortcode_name, title, is_default, data ),
				context: this
			} ).done( function ( response ) {
				var $button = this.$el.find( this.settingsButtonSelector );

				if ( response.success ) {
					success = true;
					this.setSettingsMenuContent( response.html );
					this.settingsPresetId = response.id;

					if ( is_default ) {
						window.vc_settings_presets[ shortcode_name ] = this.getParams();
					}

					this.untaintSettingsPresetData();

					$button.addClass( 'vc_done' );

					setTimeout( function () {
						$button.removeClass( 'vc_done' );
					}, 2000 );
				}
			} ).always( function () {
				if ( ! success ) {
					vcConsoleLog( 'Could not save settings preset' );
				}
			} );
		},
		fetchSaveSettingsDialogAjaxData: function () {
			return {
				action: 'vc_action_render_settings_preset_title_prompt',
				vc_inline: true,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Fetch save settings dialog and insert it into DOM
		 *
		 * First param of callback function will be passed bool value whether dialog was created (true) or already existed in DOM (false)
		 *
		 * @param {function} callback function to execute after element has been added to DOM
		 */
		fetchSaveSettingsDialog: function ( callback ) {
			var $dropdown = this.$el.find( this.settingsDropdownSelector ),
				success = false;

			if ( $dropdown.find( '.vc_ui-prompt' ).length ) {
				if ( 'undefined' !== typeof(callback) ) {
					callback( false );
				}
				return;
			}

			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: this.fetchSaveSettingsDialogAjaxData()
			} ).done( function ( response ) {
				if ( response.success ) {
					success = true;

					$dropdown.append( response.html );

					if ( 'undefined' !== typeof(callback) ) {
						callback( true );
					}
				}
			} ).always( function () {
				if ( ! success ) {
					vcConsoleLog( 'Could not fetch html' );
				}
			} );
		},
		/**
		 * Show save settings dialog
		 *
		 * First time dialog is fetched via ajax.
		 *
		 * @param {boolean} [is_default=false] If true, also mark this preset as default
		 */
		showSaveSettingsDialog: function ( is_default ) {
			var that = this;

			this.isSettingsPresetDefault = ! ! is_default;

			this.fetchSaveSettingsDialog( function ( created ) {
				var $dropdown = that.$el.find( that.settingsDropdownSelector ),
					$button = that.$el.find( that.settingsButtonSelector ),
					$submit = $dropdown.find( '.vc_ui-button' ),
					$prompt = $dropdown.find( '.vc_ui-prompt' ),
					$title = $prompt.find( '.textfield' );

				$prompt.addClass( 'vc_visible' );
				$button.prop( 'disabled', true );
				$title.focus();

				if ( ! created ) {
					return;
				}

				$title.on( 'keyup', function () {
					if ( $( this ).val().length ) {
						$submit.removeProp( 'disabled' );
					} else {
						$submit.prop( 'disabled', true );
					}
				} );

				$prompt.on( 'submit', function () {
					var title = $title.val(),
						$button = that.$el.find( that.settingsButtonSelector );

					if ( ! title.length ) {
						return false;
					}

					that.saveSettings( title, that.isSettingsPresetDefault );

					$title.val( '' );

					$prompt.removeClass( 'vc_visible' );

					$button
						.removeProp( 'disabled' )
						.click();

					return false;
				} );

				$prompt.on( 'click', '.vc_ui-prompt-close', function () {
					$button.removeProp( 'disabled' );
					$prompt.removeClass( 'vc_visible' );
					return false;
				} );
			} );
		},
		loadSettingsAjaxData: function ( id ) {
			return {
				action: 'vc_action_get_settings_preset',
				vc_inline: true,
				id: id,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Load and render specific preset
		 *
		 * @param {number} id
		 */
		loadSettings: function ( id ) {
			var success = false;

			this.panelInit = false;

			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: this.loadSettingsAjaxData( id ),
				context: this
			} ).done( function ( response ) {
				if ( response.success ) {
					success = true;
					this.settingsPresetId = id;
					this.renderSettingsPreset( response.data );
				}
			} ).always( function () {
				if ( ! success ) {
					vcConsoleLog( 'Could not get settings preset' );
				}
			} );
		},
		deleteSettingsAjaxData: function ( shortcode_name, id ) {
			return {
				action: 'vc_action_delete_settings_preset',
				shortcode_name: shortcode_name,
				vc_inline: true,
				id: id,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Delete specific preset
		 *
		 * @param {number} id
		 */
		deleteSettings: function ( id ) {
			var shortcode_name = this.model.get( 'shortcode' ),
				success = false;

			if ( ! confirm( window.i18nLocale.delete_preset_confirmation ) ) {
				return;
			}

			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: this.deleteSettingsAjaxData( shortcode_name, id ),
				context: this
			} ).done( function ( response ) {
				if ( response.success ) {
					success = true;
					this.setSettingsMenuContent( response.html );

					if ( id === this.settingsPresetId ) {
						this.settingsPresetId = null;
					}

					if ( response.default ) {
						delete window.vc_settings_presets[ shortcode_name ];
					}
				}
			} ).always( function () {
				if ( ! success ) {
					vcConsoleLog( 'Could not delete settings preset' );
				}
			} );
		},
		saveAsDefaultSettingsAjaxData: function ( shortcode_name ) {
			return {
				action: 'vc_action_set_as_default_settings_preset',
				shortcode_name: shortcode_name,
				id: this.settingsPresetId,
				vc_inline: true,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Save currently loaded preset as default
		 *
		 * If no preset has been loaded or loaded preset has been changed (tainted),
		 * show "save as" dialog. Otherwise save w/o any prompt.
		 */
		saveAsDefaultSettings: function () {
			var shortcode_name = this.model.get( 'shortcode' ),
				success = false;

			// if user has not loaded preset or made any changes...
			if ( ! this.settingsPresetId || this.isSettingsPresetDataTainted() ) {
				this.showSaveSettingsDialog( true );
			} else {
				$.ajax( {
					type: 'POST',
					dataType: 'json',
					url: window.ajaxurl,
					data: this.saveAsDefaultSettingsAjaxData( shortcode_name ),
					context: this
				} ).done( function ( response ) {
					if ( response.success ) {
						success = true;
						this.setSettingsMenuContent( response.html );
						this.untaintSettingsPresetData();
						window.vc_settings_presets[ shortcode_name ] = this.getParams();
					}
				} ).always( function () {
					if ( ! success ) {
						vcConsoleLog( 'Could not save default settings preset' );
					}
				} );
			}
		},
		restoreDefaultSettingsAjaxData: function ( shortcode_name ) {
			return {
				action: 'vc_action_restore_default_settings_preset',
				shortcode_name: shortcode_name,
				vc_inline: true,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Remove "default" flag from currently default preset
		 */
		restoreDefaultSettings: function () {
			var shortcode_name = this.model.get( 'shortcode' ),
				success = false;

			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: this.restoreDefaultSettingsAjaxData( shortcode_name ),
				context: this
			} ).done( function ( response ) {
				if ( response.success ) {
					success = true;
					this.setSettingsMenuContent( response.html );
					delete window.vc_settings_presets[ shortcode_name ];
				}
			} ).always( function () {
				if ( ! success ) {
					vcConsoleLog( 'Could not save default settings preset' );
				}
			} );

		},
		/**
		 * Update settings menu (popup) content with specified html
		 *
		 * @param {string} html
		 */
		setSettingsMenuContent: function ( html ) {
			var $button = this.$el.find( this.settingsButtonSelector ),
				$menu = this.$el.find( this.settingsMenuSelector ),
				shortcode_name = this.model.get( 'shortcode' ),
				that = this;

			$button.data( 'vcShortcodeName', shortcode_name );

			$menu.html( html );

			$menu.find( '[data-vc-load-settings-preset]' ).on( 'click', function () {
				that.loadSettings( $( this ).data( 'vcLoadSettingsPreset' ) );
				that.closeSettings();
			} );

			$menu.find( '[data-vc-delete-settings-preset]' ).on( 'click', function () {
				that.deleteSettings( $( this ).data( 'vcDeleteSettingsPreset' ) );
			} );

			$menu.find( '[data-vc-save-settings-preset]' ).on( 'click', function () {
				that.showSaveSettingsDialog();
				that.closeSettings();
			} );

			$menu.find( '[data-vc-save-default-settings-preset]' ).on( 'click', function () {
				that.saveAsDefaultSettings();
				that.closeSettings();
			} );

			$menu.find( '[data-vc-restore-default-settings-preset]' ).on( 'click', function () {
				that.restoreDefaultSettings();
				that.closeSettings();
			} );

		},
		reloadSettingsMenuContentAjaxData: function ( shortcode_name ) {
			return {
				action: 'vc_action_render_settings_preset_popup',
				shortcode_name: shortcode_name,
				vc_inline: true,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Reload settings menu (popup) content
		 *
		 * This is envoked for the first time menu is opened and every time preset is
		 * saved or deleted
		 */
		reloadSettingsMenuContent: function () {
			var shortcode_name = this.model.get( 'shortcode' ),
				$button = this.$el.find( this.settingsButtonSelector ),
				success = false;

			$button.addClass( 'vc_loading' );

			this.setSettingsMenuContent( '' );

			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: this.reloadSettingsMenuContentAjaxData( shortcode_name ),
				context: this
			} ).done( function ( response ) {
				if ( response.success ) {
					success = true;
					this.setSettingsMenuContent( response.html );
					$button
						.data( 'vcSettingsMenuLoaded', true )
						.removeClass( 'vc_loading' );
				}
			} ).always( function () {
				if ( ! success ) {
					this.closeSettings();
					vcConsoleLog( 'Could not fetch html' );
				}
			} );
		},
		/**
		 * Close settings menu
		 *
		 * @param {boolean} [destroy=false] If true, mark menu as 'not loaded', so next time user opens it, it will be fetched again
		 */
		closeSettings: function ( destroy ) {
			if ( 'undefined' === typeof(destroy) ) {
				destroy = false;
			}

			var $menu = this.$el.find( this.settingsMenuSelector ),
				$button = this.$el.find( this.settingsButtonSelector );

			if ( destroy ) {
				button.data( 'vcSettingsMenuLoaded', false );
				$menu.html( '' );
			}

			$button.vcAccordion( 'hide' );
		},
		/**
		 * Check if setting preset data is tainted in current window
		 *
		 * Every time this.getParams() is accessed and design options are used, new random
		 * classname (vc_custom_RANDOM-DIGITS) is created which would generate different
		 * hash every time, so we delete this random part.
		 *
		 * @return {boolean}
		 */
		isSettingsPresetDataTainted: function () {
			var params = JSON.stringify( this.getParams() );
			params = params.replace( /vc_custom_\d+/, '' );

			return this.$el.data( 'vcSettingsPresetHash' ) !== vc_globalHashCode( params );
		},
		/**
		 * Untaint settings preset data in current window
		 *
		 * @see isSettingsPresetDataTainted for reason why vc_custom_* is removed before hashing
		 */
		untaintSettingsPresetData: function () {
			var params = JSON.stringify( this.getParams() );
			params = params.replace( /vc_custom_\d+/, '' );

			this.$el.data( 'vcSettingsPresetHash', vc_globalHashCode( params ) );
		},
		renderSettingsPresetAjaxData: function ( params ) {
			var parent_id;

			parent_id = this.model.get( 'parent_id' );

			return {
				action: 'vc_edit_form',
				tag: this.model.get( 'shortcode' ),
				parent_tag: parent_id ? this.model.collection.get( parent_id ).get( 'shortcode' ) : null,
				post_id: $( '#post_ID' ).val(),
				params: params,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Render preset
		 *
		 * @see render
		 *
		 * @param {object} params
		 * @return {vc.EditElementPanelView}
		 */
		renderSettingsPreset: function ( params ) {
			this.currentModelParams = params;
			// @todo update with event
			// generate new random tab_id if needed

			if ( 'vc_tta_section' === this.model.get( 'shortcode' ) && 'undefined' !== typeof(params.tab_id ) ) {
				params.tab_id = vc_guid() + '-cl';
			}
			this._killEditor();
			this.clearButtonMessage();
			this.trigger( 'render' );
			this.show();
			this.ajax = $.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: this.renderSettingsPresetAjaxData( params ),
				context: this
			} ).done( this.buildParamsContent );
			return this;
		},
		ajaxData: function () {
			var parent_tag, parent_id;

			parent_id = this.model.get( 'parent_id' );
			parent_tag = parent_id ? this.model.collection.get( parent_id ).get( 'shortcode' ) : null;

			return {
				action: 'vc_edit_form', // OLD version wpb_show_edit_form
				tag: this.model.get( 'shortcode' ),
				parent_tag: parent_tag,
				post_id: $( '#post_ID' ).val(),
				params: this.model.get( 'params' ),
				_vcnonce: window.vcAdminNonce
			};
		},
		init: function () {
			vc.EditElementPanelView.__super__.init.call( this );
			this.initParams();
			this.initDependency();
			var _this = this;
			$( '.wpb_edit_form_elements .textarea_html' ).each( function () {
				window.init_textarea_html( $( this ) );
			} );

			$( document ).off( 'beforeMinimize.vc.paramWindow',
				this.minimizeButtonSelector ).on( 'beforeMinimize.vc.paramWindow', this.minimizeButtonSelector,
				function () {
					var $dropdown = self.$el.find( self.settingsDropdownSelector ),
						$prompt = $dropdown.find( '.vc_ui-prompt' );
					$prompt.find( '.vc_ui-prompt-close' ).trigger( 'click' );
				} );

			$( document ).off( 'close.vc.paramWindow',
				this.closeButtonSelector ).on( 'beforeClose.vc.paramWindow', this.closeButtonSelector,
				function () {
					var $dropdown = self.$el.find( self.settingsDropdownSelector ),
						$prompt = $dropdown.find( '.vc_ui-prompt' );
					$prompt.find( '.vc_ui-prompt-close' ).trigger( 'click' );
				} );

			$( document ).off( 'show.vc.accordion', this.settingsButtonSelector ).on( 'show.vc.accordion',
				this.settingsButtonSelector,
				function () {
					var $this = $( this ),
						shortcode_name = _this.model.get( 'shortcode' );

					if ( $this.data( 'vcSettingsMenuLoaded' ) && shortcode_name === $this.data( 'vcShortcodeName' ) ) {
						return;
					}

					_this.reloadSettingsMenuContent();
				} );

			this.panelInit = true;
		},
		initParams: function () {
			var _this = this;
			var $content = this.content().find( '#vc_edit-form-tabs [data-vc-ui-element="panel-edit-element-tab"]:eq(' + this.active_tab_index + ')' );
			if ( ! $content.length ) {
				$content = this.content();
			}
			if ( ! $content.attr( 'data-vc-param-initialized' ) ) {
				$( '[data-vc-ui-element="panel-shortcode-param"]', $content ).each( function () {
					var $field;
					var param;
					$field = $( this );
					if ( ! $field.data( 'vcInitParam' ) ) {
						param = $field.data( 'param_settings' );
						vc.atts.init.call( _this, param, $field );
						$field.data( 'vcInitParam', true );
					}
				} );
				$content.attr( 'data-vc-param-initialized', true );
			}
			if ( ! this.requiredParamsInitialized && ! _.isUndefined( vc.required_params_to_init ) ) {
				$( '[data-vc-ui-element="panel-shortcode-param"]', this.content() ).each( function () {
					var $field;
					var param;
					$field = $( this );
					if ( ! $field.data( 'vcInitParam' ) && _.indexOf( vc.required_params_to_init,
							$field.data( 'param_type' ) ) > - 1 ) {
						param = $field.data( 'param_settings' );
						vc.atts.init.call( _this, param, $field );
						$field.data( 'vcInitParam', true );
					}
				} );
				this.requiredParamsInitialized = true;
			}
		},
		initDependency: function () {
			// setup dependencies
			var callDependencies = {};
			_.each( this.mapped_params, function ( param ) {
				if ( _.isObject( param ) && _.isObject( param.dependency ) ) {
					var rules = param.dependency;
					if ( _.isString( param.dependency.element ) ) {
						var $masters, $slave;

						$masters = $( '[name=' + param.dependency.element + '].wpb_vc_param_value', this.$content );
						$slave = $( '[name= ' + param.param_name + '].wpb_vc_param_value', this.$content );
						_.each( $masters, function ( master ) {
							var $master, name;
							$master = $( master );
							name = $master.attr( 'name' );
							if ( ! _.isArray( this.dependent_elements[ $master.attr( 'name' ) ] ) ) {
								this.dependent_elements[ $master.attr( 'name' ) ] = [];
							}
							this.dependent_elements[ $master.attr( 'name' ) ].push( $slave );
							! $master.data( 'dependentSet' )
							&& $master.attr( 'data-dependent-set', 'true' )
							&& $master.bind( 'keyup change', this.hookDependent );
							if ( ! callDependencies[ name ] ) {
								callDependencies[ name ] = $master;
							}
						}, this );
					}
					if ( _.isString( rules.callback ) ) {
						window[ rules.callback ].call( this );
					}
				}
			}, this );
			this.doCheckTabs = false;
			_.each( callDependencies, function ( obj ) {
				this.hookDependent( { currentTarget: obj } );
			}, this );
			this.doCheckTabs = true;
			this.checkTabs();
			callDependencies = null;
		},
		hookDependent: function ( e ) {
			var $master, $master_container, is_empty, dependent_elements, master_value, checkTabs;

			$master = $( e.currentTarget );
			$master_container = $master.closest( '.vc_column' );
			dependent_elements = this.dependent_elements[ $master.attr( 'name' ) ];
			master_value = $master.is( ':checkbox' ) ? _.map( this.$content.find( '[name=' + $( e.currentTarget ).attr( 'name' ) + '].wpb_vc_param_value:checked' ),
				function ( element ) {
					return $( element ).val();
				} )
				: $master.val();
			checkTabs = true && this.doCheckTabs;
			this.doCheckTabs = false;
			is_empty = $master.is( ':checkbox' ) ? ! this.$content.find( '[name=' + $master.attr( 'name' ) + '].wpb_vc_param_value:checked' ).length
				: ! master_value.length;
			if ( $master_container.hasClass( 'vc_dependent-hidden' ) ) {
				_.each( dependent_elements, function ( $element ) {
					var event = jQuery.Event( 'change' );
					event.extra_type = 'vcHookDepended';
					$element.closest( '.vc_column' ).addClass( 'vc_dependent-hidden' );
					$element.trigger( event );
				} );
			} else {
				_.each( dependent_elements, function ( $element ) {
					var param_name = $element.attr( 'name' ),
						rules = _.isObject( this.mapped_params[ param_name ] ) && _.isObject( this.mapped_params[ param_name ].dependency ) ? this.mapped_params[ param_name ].dependency : {},
						$param_block = $element.closest( '.vc_column' );
					if ( _.isBoolean( rules.not_empty ) && true === rules.not_empty && ! is_empty ) { // Check is not empty show dependent Element.
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else if ( _.isBoolean( rules.is_empty ) && true === rules.is_empty && is_empty ) {
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else if ( rules.value && _.intersection( (_.isArray( rules.value ) ? rules.value : [ rules.value ]),
							(_.isArray( master_value ) ? master_value : [ master_value ]) ).length ) {
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else if ( rules.value_not_equal_to && ! _.intersection( (_.isArray( rules.value_not_equal_to ) ? rules.value_not_equal_to : [ rules.value_not_equal_to ]),
							(_.isArray( master_value ) ? master_value : [ master_value ]) ).length ) {
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else {
						$param_block.addClass( 'vc_dependent-hidden' );
					}
					var event = jQuery.Event( 'change' );
					event.extra_type = 'vcHookDepended';
					$element.trigger( event );
				}, this );
			}
			if ( checkTabs ) {
				this.checkTabs();
				this.doCheckTabs = true;
			}
			return this;
		},
		// Hide tabs if all params inside is vc_dependent-hidden
		checkTabs: function () {
			var that = this;
			if ( false === this.tabsInit ) {
				this.tabsInit = true;
				if ( this.$content.hasClass( 'vc_with-tabs' ) ) {
					this.$tabsMenu = this.$content.find( '.vc_edit-form-tabs-menu' );
				}
			}
			if ( this.$tabsMenu ) {
				this.$content.find( '[data-vc-ui-element="panel-edit-element-tab"]' ).each( function ( index ) {
					var $tabControl = that.$tabsMenu.find( '> [data-tab-index="' + index + '"]' );
					if ( $( this ).find( '[data-vc-ui-element="panel-shortcode-param"]:not(".vc_dependent-hidden")' ).length ) {
						if ( $tabControl.hasClass( 'vc_dependent-hidden' ) ) {
							$tabControl.removeClass( 'vc_dependent-hidden' ).removeClass( 'vc_tab-color-animated' ).addClass( 'vc_tab-color-animated' );
							window.setTimeout( function () {
								$tabControl.removeClass( 'vc_tab-color-animated' )
							}, 200 );
						}
					} else {
						$tabControl.addClass( 'vc_dependent-hidden' );
					}
				} );
				// new enchacement from #1467
				window.setTimeout( this.setTabsSize, 100 );
			}
		},
		/**
		 * new enchacement from #1467
		 * Set tabs positions absolute and height relative to content, to make sure it is stacked to top of panel
		 * @since 4.4
		 */
		setTabsSize: function () {
			this.$tabsMenu.parents( '.vc_with-tabs.vc_panel-body' ).css( 'margin-top', this.$tabsMenu.outerHeight() );
		},
		setActive: function () {
			this.$el.prev().addClass( 'active' );
		},
		window: function () {
			return window;
		},
		getParams: function () {
			var paramsSettings;

			paramsSettings = this.mapped_params;
			this.params = _.extend( {}, this.model.get( 'params' ) );
			_.each( paramsSettings, function ( param ) {
				var value;

				value = vc.atts.parseFrame.call( this, param );
				this.params[ param.param_name ] = value;
			}, this );
			_.each( vc.edit_form_callbacks, function ( callback ) {
				callback.call( this );
			}, this );
			return this.params;
		},
		content: function () {
			return this.$content;
		},
		save: function () {
			if ( ! this.panelInit ) {
				return;
			}
			var params;
			params = _.extend( {}, vc.getDefaults( this.model.get( 'shortcode' ) ), this.getParams() );
			this.model.save( { params: params } );
			this.showMessage( window.sprintf( window.i18nLocale.inline_element_saved,
				vc.getMapped( this.model.get( 'shortcode' ) ).name ), 'success' );
			! vc.frame_window && this.hide();
			this.trigger( 'save' );
		},
		show: function () {
			this.$el.addClass( 'vc_active' );
			if ( ! this.draggable ) {
				this.initDraggable();
			}
			this.fixElContainment();
			this.trigger( 'show' );
		},
		hide: function ( e ) {
			e && e.preventDefault();
			this.ajax && this.ajax.abort();
			this.ajax = false;
			vc.active_panel = false;
			this.currentModelParams = false;
			this._killEditor();
			this.$el.removeClass( 'vc_active' );
			this.$el.find( '.vc_properties-list' ).removeClass( 'vc_with-tabs' ).css( 'margin-top', 'auto' );
			this.$content.empty();
			this.trigger( 'hide' );

		},
		setTitle: function () {
			this.$el.find( '.vc_panel-title' ).text( vc.getMapped( this.model.get( 'shortcode' ) ).name + ' ' + window.i18nLocale.settings );
			return this;
		},
		_killEditor: function () {
			if ( ! _.isUndefined( window.tinyMCE ) ) {
				$( 'textarea.textarea_html', this.$el ).each( function () {
					var id = $( this ).attr( 'id' );
					if ( "4" === tinymce.majorVersion ) {
						window.tinyMCE.execCommand( 'mceRemoveEditor', true, id );
					} else {
						window.tinyMCE.execCommand( "mceRemoveControl", true, id );
					}
				} );
			}
			// TODO: move with new version of params types.
			jQuery( 'body' ).off( 'click.wpcolorpicker' );
		}
	} );
	/**
	 * Post custom css
	 *
	 * @type {Number}
	 */
	vc.PostSettingsPanelView = vc.PanelView.extend( {
		events: {
			'click [data-save=true]': 'save',
			'click [data-dismiss=panel]': 'hide',
			'click [data-transparent=panel]': 'toggleOpacity',
			'mouseover [data-transparent=panel]': 'addOpacity',
			'mouseout [data-transparent=panel]': 'removeOpacity'
		},
		saved_css_data: '',
		saved_title: '',
		$title: false,
		editor: false,
		post_settings_editor: false,
		initialize: function () {
			vc.$custom_css = $( '#vc_post-custom-css' );
			this.saved_css_data = vc.$custom_css.val();
			this.saved_title = vc.title;
			this.initEditor();
			this.$body = $( 'body' );
			_.bindAll( this, 'setSize', 'fixElContainment' );
			this.on( 'show', this.setSize, this );
			this.on( 'setSize', this.setResize, this );
			this.on( 'render', this.resetMinimize, this );
		},
		initEditor: function () {
			this.editor = new Vc_postSettingsEditor();
		},
		render: function () {
			this.trigger( 'render' );
			this.$title = this.$el.find( '#vc_page-title-field' );
			this.$title.val( vc.title );
			this.setEditor();
			this.trigger( 'afterRender' );
			return this;
		},
		setEditor: function () {
			this.editor.setEditor( vc.$custom_css.val() );
		},
		setSize: function () {
			this.editor.setSize();
			this.trigger( 'setSize' );
		},
		save: function () {
			if ( this.$title ) {
				var title = this.$title.val();
				if ( title != vc.title ) {
					vc.frame.setTitle( title );
				}
			}
			this.setAlertOnDataChange();
			vc.$custom_css.val( this.editor.getValue() );
			vc.frame_window && vc.frame_window.vc_iframe.loadCustomCss( vc.$custom_css.val() );
			vc.updateSettingsBadge();
			this.showMessage( window.i18nLocale.css_updated, 'success' );
			this.trigger( 'save' );
		},
		/**
		 * Set alert if custom css data differs from saved data.
		 */
		setAlertOnDataChange: function () {
			if ( this.saved_css_data !== this.editor.getValue() ) {
				vc.setDataChanged();
			} else if ( this.$title && this.saved_title !== this.$title.val() ) {
				vc.setDataChanged();
			}
		}
	} );
	vc.PostSettingsPanelViewBackendEditor = vc.PostSettingsPanelView.extend( {
		render: function () {
			this.trigger( 'render' );
			this.setEditor();
			this.trigger( 'afterRender' );
			return this;
		},
		/**
		 * Set alert if custom css data differs from saved data.
		 */
		setAlertOnDataChange: function () {
			if ( vc.saved_custom_css !== this.editor.getValue() && window.tinymce ) {
				window.switchEditors.go( 'content', 'tmce' );
				window.setTimeout( function () {
					window.tinymce.get( 'content' ).isNotDirty = false;
				}, 1000 );
			}
		},
		save: function () {
			vc.PostSettingsPanelViewBackendEditor.__super__.save.call( this );
			this.hide();
		}
	} );

	/**
	 * Templates editor
	 *
	 * @deprecated 4.4 use vc.TemplatesModalViewBackend/Frontend
	 * @type {*}
	 */
	vc.TemplatesEditorPanelView = vc.PanelView.extend( {
		events: {
			'click [data-dismiss=panel]': 'hide',
			'click [data-transparent=panel]': 'toggleOpacity',
			'mouseover [data-transparent=panel]': 'addOpacity',
			'mouseout [data-transparent=panel]': 'removeOpacity',
			'click .wpb_remove_template': 'removeTemplate',
			'click [data-template_id]': 'loadTemplate',
			'click [data-template_name]': 'loadDefaultTemplate',
			'click #vc_template-save': 'saveTemplate'
		},
		render: function () {
			this.trigger( 'render' );
			this.$name = $( '#vc_template-name' );
			this.$list = $( '#vc_template-list' );
			var $tabs = $( '#vc_tabs-templates' );
			$tabs.find( '.vc_edit-form-tab-control' ).removeClass( 'vc_active' ).eq( 0 ).addClass( 'vc_active' );
			$tabs.find( '[data-vc-ui-element="panel-edit-element-tab"]' ).removeClass( 'vc_active' ).eq( 0 ).addClass( 'vc_active' );
			$tabs.find( '.vc_edit-form-link' ).click( function ( e ) {
				e.preventDefault();
				var $this = $( this );
				$tabs.find( '.vc_active' ).removeClass( 'vc_active' );
				$this.parent().addClass( 'vc_active' );
				$( $this.attr( 'href' ) ).addClass( 'vc_active' );
			} );
			this.trigger( 'afterRender' );
			return this;
		},
		/**
		 * Remove template from server database.
		 *
		 * @param e - Event object
		 */
		removeTemplate: function ( e ) {
			e && e.preventDefault();
			var $button = $( e.currentTarget );
			var template_name = $button.closest( '[data-vc-ui-element="template-title"]' ).text();
			var answer = confirm( window.i18nLocale.confirm_deleting_template.replace( '{template_name}',
				template_name ) );
			if ( answer ) {
				$button.closest( '[data-vc-ui-element="template"]' ).remove();
				this.$list.html( window.i18nLocale.loading );
				$.ajax( {
					type: 'POST',
					url: window.ajaxurl,
					data: {
						action: 'wpb_delete_template',
						template_id: $button.attr( 'rel' ),
						vc_inline: true,
						_vcnonce: window.vcAdminNonce
					},
					context: this
				} ).done( function ( html ) {
					this.$list.html( html );
				} );
			}
		},
		/**
		 * Load saved template from server.
		 *
		 * @param e - Event object
		 */
		loadTemplate: function ( e ) {
			e && e.preventDefault();
			var $button = $( e.currentTarget );
			$.ajax( {
				type: 'POST',
				url: vc.frame_window.location.href,
				data: {
					action: 'vc_frontend_template',
					template_id: $button.data( 'template_id' ),
					vc_inline: true,
					_vcnonce: window.vcAdminNonce
				},
				context: this
			} ).done( function ( html ) {
				var template, data;
				_.each( $( html ), function ( element ) {
					if ( "vc_template-data" === element.id ) {
						try {
							data = JSON.parse( element.innerHTML );
						} catch ( e ) {
							vcConsoleLog( e,
								'catching template data error' );
						}
					}
					if ( "vc_template-html" === element.id ) {
						template = element.innerHTML;
					}
				} );
				template && data && vc.builder.buildFromTemplate( template, data );
				this.showMessage( window.i18nLocale.template_added, 'success' );
			} );
		},
		ajaxData: function ( $button ) {
			return {
				action: 'vc_frontend_default_template',
				template_name: $button.data( 'template_name' ),
				vc_inline: true,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Load saved template from server.
		 *
		 * @param e - Event object
		 */
		loadDefaultTemplate: function ( e ) {
			e && e.preventDefault();
			var $button = $( e.currentTarget );
			$.ajax( {
				type: 'POST',
				url: vc.frame_window.location.href,
				data: this.ajaxData( $button ),
				context: this
			} ).done( function ( html ) {
				var template, data;
				_.each( $( html ), function ( element ) {
					if ( "vc_template-data" === element.id ) {
						try {
							data = JSON.parse( element.innerHTML )
						} catch ( e ) {
							vcConsoleLog( e, 'catching template data error' );
						}
					}
					if ( "vc_template-html" === element.id ) {
						template = element.innerHTML;
					}
				} );
				template && data && vc.builder.buildFromTemplate( template, data );
				this.showMessage( window.i18nLocale.template_added, 'success' );
			} );
		},
		/**
		 * Save current shortcode design as template with title.
		 *
		 * @param e - Event object
		 */
		saveTemplate: function ( e ) {
			e.preventDefault();
			var name = this.$name.val(),
				data, shortcodes;
			if ( _.isString( name ) && name.length ) {
				shortcodes = this.getPostContent();
				if ( ! shortcodes.trim().length ) {
					this.showMessage( window.i18nLocale.template_is_empty, 'error' );
					return false;
				}
				data = {
					action: 'wpb_save_template',
					template: shortcodes,
					template_name: name,
					frontend: true,
					vc_inline: true,
					_vcnonce: window.vcAdminNonce
				};
				this.$name.val( '' );
				this.showMessage( window.i18nLocale.template_save, 'success' );
				this.reloadTemplateList( data );
			} else {
				this.showMessage( window.i18nLocale.please_enter_templates_name, 'error' );
			}
		},
		reloadTemplateList: function ( data ) {
			this.$list.html( window.i18nLocale.loading ).load( window.ajaxurl, data );
		},
		getPostContent: function () {
			return vc.builder.getContent();
		}
	} );
	/**
	 * @deprecated 4.7
	 */
	vc.TemplatesEditorPanelViewBackendEditor = vc.TemplatesEditorPanelView.extend( {
		ajaxData: function ( $button ) {
			return {
				action: 'vc_backend_template',
				template_id: $button.attr( 'data-template_id' ),
				vc_inline: true,
				_vcnonce: window.vcAdminNonce
			};
		},
		/**
		 * Load saved template from server.
		 *
		 * @param e - Event object
		 */
		loadTemplate: function ( e ) {
			e.preventDefault();
			var $button = $( e.currentTarget );
			$.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: this.ajaxData( $button ),
				context: this
			} ).done( function ( shortcodes ) {
				_.each( vc.filters.templates, function ( callback ) {
					shortcodes = callback( shortcodes );
				} );
				vc.storage.append( shortcodes );
				vc.shortcodes.fetch( { reset: true } );
				this.showMessage( window.i18nLocale.template_added, 'success' );
			} );
		},
		/**
		 * Load default template from server.
		 *
		 * @param e - Event object
		 */
		loadDefaultTemplate: function ( e ) {
			e.preventDefault();
			var $button = $( e.currentTarget );
			$.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: {
					action: 'vc_backend_default_template',
					template_name: $button.attr( 'data-template_name' ),
					vc_inline: true,
					_vcnonce: window.vcAdminNonce
				},
				context: this
			} ).done( function ( shortcodes ) {
				_.each( vc.filters.templates, function ( callback ) {
					shortcodes = callback( shortcodes );
				} );
				vc.storage.append( shortcodes );
				vc.shortcodes.fetch( { reset: true } );
				this.showMessage( window.i18nLocale.template_added, 'success' );
			} );
		},
		getPostContent: function () {
			return vc.storage.getContent();
		}
	} );

	/**
	 * @since 4.4
	 */
	vc.TemplatesPanelViewBackend = vc.PanelView.extend( {
		// new feature -> elements filtering
		$name: false,
		$list: false,
		template_load_action: 'vc_backend_load_template',
		save_template_action: 'vc_save_template',
		delete_template_action: 'vc_delete_template',
		appendedTemplateType: 'my_templates',
		appendedTemplateCategory: 'my_templates',
		appendedCategory: 'my_templates',
		appendedClass: 'my_templates',
		loadUrl: window.ajaxurl,
		events: $.extend( vc.PanelView.prototype.events, {
			'click .vc_template-save-btn': 'saveTemplate',
			'click [data-template_unique_id] [data-template-handler]': 'loadTemplate',
			'click .vc_template-delete-icon': 'removeTemplate'
		} ),
		initialize: function () {
			vc.TemplatesPanelViewBackend.__super__.initialize.call( this );
		},
		render: function () {
			this.$el.css( 'left', ($( window ).width() - this.$el.width()) / 2 );
			this.$name = this.$el.find( '.vc_panel-templates-name' );
			this.$list = this.$el.find( '.vc_templates-list-my_templates' );
			return vc.TemplatesPanelViewBackend.__super__.render.call( this );
		},
		/**
		 * Save My Template
		 *
		 * @param e
		 * @return {boolean}
		 */
		saveTemplate: function ( e ) {
			e.preventDefault();
			var name = this.$name.val(),
				data, shortcodes;
			if ( _.isString( name ) && name.length ) {
				shortcodes = this.getPostContent();
				if ( ! shortcodes.trim().length ) {
					this.showMessage( window.i18nLocale.template_is_empty, 'error' );
					return false;
				}
				data = {
					action: this.save_template_action,
					template: shortcodes,
					template_name: name,
					vc_inline: true,
					_vcnonce: window.vcAdminNonce
				};
				this.$name.val( '' );
				this.reloadTemplateList( data ); // TODO: modify this
			} else {
				this.showMessage( window.i18nLocale.please_enter_templates_name, 'error' );
				return false;
			}
		},
		/**
		 * Remove template from server database.
		 *
		 * @param e - Event object
		 */
		removeTemplate: function ( e ) {
			e && e.preventDefault();
			var $button = $( e.target );
			var $template = $button.parents( '.vc_template' );
			var template_name = $template.find( '[data-vc-ui-element="template-title"]' ).text();
			var answer = confirm( window.i18nLocale.confirm_deleting_template.replace( '{template_name}',
				template_name ) );
			if ( answer ) {
				var template_id = $template.data( 'template_unique_id' );
				$template.remove();
				$.ajax( {
					type: 'POST',
					url: window.ajaxurl,
					data: {
						action: this.delete_template_action,
						template_id: template_id,
						vc_inline: true,
						_vcnonce: window.vcAdminNonce
					},
					context: this
				} ).done( function () {
					this.showMessage( window.i18nLocale.template_removed, 'success' );
				} );
			}
		},
		reloadTemplateList: function ( data ) {
			var self = this;
			var $template = $( '<li class="vc_template vc_col-sm-6 vc_col-xs-12 vc_col-md-4 vc_templates-template-type-' + this.appendedClass + '"></li>' );
			$template.load( window.ajaxurl, data, function ( html ) {
				self.filter = false; // reset current filter
				$template.attr( 'data-category', self.appendedTemplateCategory );
				$template.attr( 'data-template_unique_id', $( html ).data( 'template_id' ) );
				$template.attr( 'data-template_type', self.appendedTemplateType );
				self.showMessage( window.i18nLocale.template_save, 'success' );
				self.$list.prepend( $( this ) );
			} );
		},
		getPostContent: function () {
			return vc.shortcodes.stringify( 'template' );
		},
		loadTemplate: function ( e ) {
			e.preventDefault();
			var $template_data = $( e.target ).parents( '.vc_template' );
			$.ajax( {
				type: 'POST',
				url: this.loadUrl,
				data: {
					action: this.template_load_action,
					template_unique_id: $template_data.data( 'template_unique_id' ),
					template_type: $template_data.data( 'template_type' ),
					vc_inline: true,
					_vcnonce: window.vcAdminNonce
				},
				context: this
			} ).done( this.renderTemplate );
		},
		renderTemplate: function ( html ) {
			var models, models_has_id;

			_.each( vc.filters.templates, function ( callback ) {
				html = callback( html );
			} );

			models = vc.storage.parseContent( {}, html );
			models_has_id = false;
			_.each( models, function ( model ) {
				vc.shortcodes.create( model );
				if ( ! models_has_id ) {
					var param = vc.shortcodeHasIdParam( model.shortcode );
					if ( param && ! _.isUndefined( model.params ) && ! _.isUndefined( model.params[ param.param_name ] ) && 0 < model.params[ param.param_name ].length ) {
						models_has_id = true;
					}
				}
			} );

			if ( models_has_id ) {
				this.showMessage( window.i18nLocale.template_added_with_id, 'error' );
			} else {
				this.showMessage( window.i18nLocale.template_added, 'success' );
			}
		}
	} );

	/**
	 * @since 4.4
	 */
	vc.TemplatesPanelViewFrontend = vc.TemplatesPanelViewBackend.extend( {
		template_load_action: 'vc_frontend_load_template',
		loadUrl: false,
		initialize: function () {
			this.loadUrl = vc.$frame.attr( 'src' );
			vc.TemplatesPanelViewFrontend.__super__.initialize.call( this );
		},
		render: function () {
			return vc.TemplatesPanelViewFrontend.__super__.render.call( this );
		},
		renderTemplate: function ( html ) {
			// Render template for frontend
			var template, data;
			_.each( $( html ), function ( element ) {
				if ( "vc_template-data" === element.id ) {
					try {
						data = JSON.parse( element.innerHTML );
					} catch ( e ) {
						vcConsoleLog( e );
					}
				}
				if ( "vc_template-html" === element.id ) {
					template = element.innerHTML;
				}
			} );
			if ( template && data && vc.builder.buildFromTemplate( template, data ) ) {
				this.showMessage( window.i18nLocale.template_added_with_id, 'error' );
			} else {
				this.showMessage( window.i18nLocale.template_added, 'success' );
			}
		}
	} );

	vc.RowLayoutEditorPanelView = vc.PanelView.extend( {
		events: {
			'click [data-dismiss=panel]': 'hide',
			'click [data-transparent=panel]': 'toggleOpacity',
			'mouseover [data-transparent=panel]': 'addOpacity',
			'mouseout [data-transparent=panel]': 'removeOpacity',
			'click .vc_layout-btn': 'setLayout',
			'click #vc_row-layout-update': 'updateFromInput'
		},
		_builder: false,
		render: function ( model ) {
			this.$input = $( '#vc_row-layout' );
			if ( model ) {
				this.model = model;
			}
			this.addCurrentLayout();
			this.resetMinimize();
			vc.column_trig_changes = true;
			return this;
		},
		builder: function () {
			if ( ! this._builder ) {
				this._builder = new vc.ShortcodesBuilder();
			}
			return this._builder;
		},
		addCurrentLayout: function () {
			vc.shortcodes.sort();
			var string = _.map( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				var width = model.getParam( 'width' );
				return width ? width : '1/1';
			}, '', this ).join( ' + ' );
			this.$input.val( string );
		},
		isBuildComplete: function () {
			return this.builder().isBuildComplete();
		},
		setLayout: function ( e ) {
			e && e.preventDefault();
			if ( ! this.isBuildComplete() ) {
				return false;
			}
			var $control = $( e.currentTarget ),
				layout = $control.attr( 'data-cells' ),
				columns = this.model.view.convertRowColumns( layout, this.builder() );
			this.$input.val( columns.join( ' + ' ) );
		},
		updateFromInput: function ( e ) {
			e && e.preventDefault();
			if ( ! this.isBuildComplete() ) {
				return false;
			}
			var layout,
				cells = this.$input.val();
			if ( false !== (layout = this.validateCellsList( cells )) ) {
				this.model.view.convertRowColumns( layout, this.builder() );
			} else {
				window.alert( window.i18nLocale.wrong_cells_layout );
			}
		},
		validateCellsList: function ( cells ) {
			var return_cells = [],
				split = cells.replace( /\s/g, '' ).split( '+' ),
				b, num, denom;
			var sum = _.reduce( _.map( split, function ( c ) {
				if ( c.match( /^[vc\_]{0,1}span\d{1,2}$/ ) ) {
					var converted_c = vc_convert_column_span_size( c );
					if ( false === converted_c ) {
						return 1000;
					}
					b = converted_c.split( /\// );
					return_cells.push( b[ 0 ] + '' + b[ 1 ] );
					return 12 * parseInt( b[ 0 ], 10 ) / parseInt( b[ 1 ], 10 );
				} else if ( c.match( /^[1-9]|1[0-2]\/[1-9]|1[0-2]$/ ) ) {
					b = c.split( /\// );
					num = parseInt( b[ 0 ], 10 );
					denom = parseInt( b[ 1 ], 10 );
					if ( 0 !== 12 % denom || num > denom ) {
						return 1000;
					}
					return_cells.push( num + '' + b[ 1 ] );
					return 12 * num / denom;
				}
				return 1000;

			} ), function ( num, memo ) {
				memo = memo + num;
				return memo;
			}, 0 );
			if ( 1000 <= sum ) {
				return false;
			}
			return return_cells.join( '_' );
		}
	} );
	vc.RowLayoutEditorPanelViewBackend = vc.RowLayoutEditorPanelView.extend( {
		builder: function () {
			if ( ! this.builder ) {
				this.builder = vc.storage;
			}
			return this.builder;
		},
		isBuildComplete: function () {
			return true;
		},
		setLayout: function ( e ) {
			e && e.preventDefault();
			var $control = $( e.currentTarget ),
				layout = $control.attr( 'data-cells' ),
				columns = this.model.view.convertRowColumns( layout );
			this.$input.val( columns.join( ' + ' ) );
		}
	} );

	$( window ).on( 'orientationchange', function () {
		if ( vc.active_panel ) {
			vc.active_panel.$el.css( {
				top: '',
				left: 'auto',
				height: 'auto',
				width: 'auto'
			} );
		}
	} );
	$( window ).bind( 'resize.fixElContainment', function () {
		vc.active_panel && vc.active_panel.fixElContainment && vc.active_panel.fixElContainment();
	} );
})( window.jQuery );
