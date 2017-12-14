/* =========================================================
 * custom_views.js v1.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer ViewModel objects for shortcodes with custom
 * functionality.
 * ========================================================= */

(function ( $ ) {
	if ( _.isUndefined( window.vc ) ) {
		window.vc = {};
	}
	// Custom vc_column shortcode
	window.InlineShortcodeViewContainer = window.InlineShortcodeView.extend( {
		controls_selector: '#vc_controls-template-container',
		events: {
			'click > .vc_controls .vc_element .vc_control-btn-delete': 'destroy',
			'click > .vc_controls .vc_element .vc_control-btn-edit': 'edit',
			'click > .vc_controls .vc_element .vc_control-btn-clone': 'clone',
			'click > .vc_controls .vc_element .vc_control-btn-prepend': 'prependElement',
			'click > .vc_controls .vc_control-btn-append': 'appendElement',
			'click > .vc_empty-element': 'appendElement',
			'mouseenter': 'resetActive',
			'mouseleave': 'holdActive'
		},
		hold_active: false,
		initialize: function ( params ) {
			_.bindAll( this, 'holdActive' );
			window.InlineShortcodeViewContainer.__super__.initialize.call( this, params );
			this.parent_view = vc.shortcodes.get( this.model.get( 'parent_id' ) ).view;
		},
		resetActive: function ( e ) {
			this.hold_active && window.clearTimeout( this.hold_active );
		},
		holdActive: function ( e ) {
			this.resetActive();
			this.$el.addClass( 'vc_hold-active' );
			var view = this;
			this.hold_active = window.setTimeout( function () {
				view.hold_active && window.clearTimeout( view.hold_active );
				view.hold_active = false;
				view.$el.removeClass( 'vc_hold-active' );
			}, 700 );
		},
		content: function () {
			if ( false === this.$content ) {
				this.$content = this.$el.find( '.vc_container-anchor:first' ).parent();
				this.$el.find( '.vc_container-anchor:first' ).remove();
			}
			return this.$content;
		},
		render: function () {
			window.InlineShortcodeViewContainer.__super__.render.call( this );
			this.content().addClass( 'vc_element-container' );
			this.$el.addClass( 'vc_container-block' );
			return this;
		},
		changed: function () {
			(0 === this.$el.find( '.vc_element[data-tag]' ).length && this.$el.addClass( 'vc_empty' ).find( '> :first' ).addClass( 'vc_empty-element' ))
			|| this.$el.removeClass( 'vc_empty' ).find( '> .vc_empty-element' ).removeClass( 'vc_empty-element' );
		},
		prependElement: function ( e ) {
			_.isObject( e ) && e.preventDefault();
			this.prepend = true;
			vc.add_element_block_view.render( this.model, true );
		},
		appendElement: function ( e ) {
			_.isObject( e ) && e.preventDefault();
			vc.add_element_block_view.render( this.model );
		},
		addControls: function () {
			var template = $( this.controls_selector ).html(),
				parent = vc.shortcodes.get( this.model.get( 'parent_id' ) ),
				data = {
					name: vc.getMapped( this.model.get( 'shortcode' ) ).name,
					tag: this.model.get( 'shortcode' ),
					parent_name: vc.getMapped( parent.get( 'shortcode' ) ).name,
					parent_tag: parent.get( 'shortcode' )
				};
			this.$controls = $( _.template( template, data, vc.template_options ).trim() ).addClass( 'vc_controls' );
			if ( ! this.hasUserAccess() ) {
				this.$controls.find( '.vc_control-btn:not(.vc_element-move)' ).remove();
			}
			this.$controls.appendTo( this.$el );
		},
		multi_edit: function ( e ) {
			var models = [], parent, children;
			_.isObject( e ) && e.preventDefault();
			if ( this.model.get( 'parent_id' ) ) {
				parent = vc.shortcodes.get( this.model.get( 'parent_id' ) );
			}
			if ( parent ) {
				models.push( parent );
				children = vc.shortcodes.where( { parent_id: parent.get( 'id' ) } );
				vc.multi_edit_element_block_view.render( models.concat( children ), this.model.get( 'id' ) );
			} else {
				vc.edit_element_block_view.render( this.model );
			}
		}
	} );
	window.InlineShortcodeViewContainerWithParent = window.InlineShortcodeViewContainer.extend( {
		controls_selector: '#vc_controls-template-container-with-parent',
		events: {
			'click > .vc_controls .vc_element .vc_control-btn-delete': 'destroy',
			'click > .vc_controls .vc_element .vc_control-btn-edit': 'edit',
			'click > .vc_controls .vc_element .vc_control-btn-clone': 'clone',
			'click > .vc_controls .vc_element .vc_control-btn-prepend': 'prependElement',
			'click > .vc_controls .vc_control-btn-append': 'appendElement',
			'click > .vc_controls .vc_parent .vc_control-btn-delete': 'destroyParent',
			'click > .vc_controls .vc_parent .vc_control-btn-edit': 'editParent',
			'click > .vc_controls .vc_parent .vc_control-btn-clone': 'cloneParent',
			'click > .vc_controls .vc_parent .vc_control-btn-prepend': 'addSibling',
			'click > .vc_controls .vc_parent .vc_control-btn-layout': 'changeLayout',
			'click > .vc_empty-element': 'appendElement',
			'click > .vc_controls .vc_control-btn-switcher': 'switchControls',
			'mouseenter': 'resetActive',
			'mouseleave': 'holdActive'
		},
		destroyParent: function ( e ) {
			e && e.preventDefault();
			this.parent_view.destroy( e );
		},
		cloneParent: function ( e ) {
			e && e.preventDefault();
			this.parent_view.clone( e );
		},
		editParent: function ( e ) {
			e && e.preventDefault();
			this.parent_view.edit( e );
		},
		addSibling: function ( e ) {
			e && e.preventDefault();
			this.parent_view.addElement( e );
		},
		changeLayout: function ( e ) {
			e && e.preventDefault();
			this.parent_view.changeLayout( e );
		},
		switchControls: function ( e ) {
			e && e.preventDefault();
			vc.unsetHoldActive();
			var $control = $( e.currentTarget ),
				$parent = $control.parent(),
				$current;
			$parent.addClass( 'vc_active' );
			$current = $parent.siblings( '.vc_active' ).removeClass( 'vc_active' );
			! $current.hasClass( 'vc_element' ) && window.setTimeout( this.holdActive, 500 );
		}
	} );
	window.InlineShortcodeView_vc_column_text = window.InlineShortcodeView.extend( {
		initialize: function ( options ) {
			window.InlineShortcodeView_vc_column_text.__super__.initialize.call( this, options );
			_.bindAll( this, 'setupEditor', 'updateContent' );
		},
		render: function () {
			window.InlineShortcodeView_vc_column_text.__super__.render.call( this );
			// Here
			return this;
		},
		setupEditor: function ( ed ) {
			ed.on( 'keyup', this.updateContent )
		},
		updateContent: function () {
			var params = this.model.get( 'params' );
			params.content = tinyMCE.activeEditor.getContent();
			this.model.save( { params: params }, { silent: true } );
		}
	} );
	window.InlineShortcodeView_vc_row = window.InlineShortcodeView.extend( {
		column_tag: 'vc_column',
		events: {
			'mouseenter': 'removeHoldActive'
		},
		layout: 1,
		addControls: function () {
			this.$controls = $( '<div class="no-controls"></div>' );
			this.$controls.appendTo( this.$el );
			return this;
		},
		removeHoldActive: function () {
			vc.unsetHoldActive();
		},
		addColumn: function () {
			vc.builder.create( {
				shortcode: this.column_tag,
				parent_id: this.model.get( 'id' )
			} ).render();
		},
		addElement: function ( e ) {
			e && e.preventDefault();
			this.addColumn();
		},
		changeLayout: function ( e ) {
			e && e.preventDefault();
			this.layoutEditor().render( this.model ).show();
		},
		layoutEditor: function () {
			if ( _.isUndefined( vc.row_layout_editor ) ) {
				vc.row_layout_editor = new vc.RowLayoutUIPanelFrontendEditor( { el: $( '#vc_ui-panel-row-layout' ) } );
			}
			return vc.row_layout_editor;
		},
		convertToWidthsArray: function ( string ) {
			return _.map( string.split( /_/ ), function ( c ) {
				var w = c.split( '' );
				w.splice( Math.floor( c.length / 2 ), 0, '/' );
				return w.join( '' );
			} );
		},
		changed: function () {
			window.InlineShortcodeView_vc_row.__super__.changed.call( this );
			this.addLayoutClass();
		},
		content: function () {
			if ( false === this.$content ) {
				this.$content = this.$el.find( '.vc_container-anchor:first' ).parent();
			}
			this.$el.find( '.vc_container-anchor:first' ).remove();
			return this.$content;
		},
		addLayoutClass: function () {
			this.$el.removeClass( 'vc_layout_' + this.layout );
			this.layout = _.reject( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				return model.get( 'deleted' )
			} ).length;
			this.$el.addClass( 'vc_layout_' + this.layout );
		},
		convertRowColumns: function ( layout, builder ) {
			if ( ! layout ) {
				return false;
			}
			var view = this, columns_contents = [], new_model,
				columns = this.convertToWidthsArray( layout );
			vc.layout_change_shortcodes = [];
			vc.layout_old_columns = vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } );
			_.each( vc.layout_old_columns, function ( column ) {
				column.set( 'deleted', true );
				columns_contents.push( {
					shortcodes: vc.shortcodes.where( { parent_id: column.get( 'id' ) } ),
					params: column.get( 'params' )
				} );
			} );
			_.each( columns, function ( column ) {
				var prev_settings = columns_contents.shift();
				if ( _.isObject( prev_settings ) ) {
					new_model = builder.create( {
						shortcode: this.column_tag,
						parent_id: this.model.get( 'id' ),
						order: vc.shortcodes.nextOrder(),
						params: _.extend( {}, prev_settings.params, { width: column } )
					} ).last();
					_.each( prev_settings.shortcodes, function ( shortcode ) {
						shortcode.save( {
								parent_id: new_model.get( 'id' ),
								order: vc.shortcodes.nextOrder()
							},
							{ silent: true } );
						vc.layout_change_shortcodes.push( shortcode );
					}, this );
				} else {
					new_model = builder.create( {
						shortcode: this.column_tag,
						parent_id: this.model.get( 'id' ),
						order: vc.shortcodes.nextOrder(),
						params: { width: column }
					} ).last();
				}
			}, this );
			_.each( columns_contents, function ( column ) {
				_.each( column.shortcodes, function ( shortcode ) {
					shortcode.save( {
							parent_id: new_model.get( 'id' ),
							order: vc.shortcodes.nextOrder()
						},
						{ silent: true } );
					vc.layout_change_shortcodes.push( shortcode );
					shortcode.view.rowsColumnsConverted && shortcode.view.rowsColumnsConverted()
				}, this );
			}, this );
			builder.render( function () {
				_.each( vc.layout_change_shortcodes, function ( shortcode ) {
					shortcode.trigger( 'change:parent_id' );
					shortcode.view.rowsColumnsConverted && shortcode.view.rowsColumnsConverted();
				} );
				_.each( vc.layout_old_columns, function ( column ) {
					column.destroy();
				} );
				vc.layout_old_columns = [];
				vc.layout_change_shortcodes = [];
			} );
			return columns;
		}
	} );
	window.InlineShortcodeView_vc_column = window.InlineShortcodeViewContainerWithParent.extend( {
		controls_selector: '#vc_controls-template-vc_column',
		resizeDomainName: 'columnSize',
		_x: 0,
		css_width: 12,
		prepend: false,
		initialize: function ( params ) {
			window.InlineShortcodeView_vc_column.__super__.initialize.call( this, params );
			_.bindAll( this, 'startChangeSize', 'stopChangeSize', 'resize' );
		},
		render: function () {
			var width;
			window.InlineShortcodeView_vc_column.__super__.render.call( this );
			this.prepend = false;
			// Here goes width logic
			$( '<div class="vc_resize-bar"></div>' )
				.appendTo( this.$el )
				.mousedown( this.startChangeSize );
			this.setColumnClasses();
			this.customCssClassReplace();
			return this;
		},
		destroy: function ( e ) {
			var parent_id = this.model.get( 'parent_id' );
			window.InlineShortcodeView_vc_column.__super__.destroy.call( this, e );
			if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
				vc.shortcodes.get( parent_id ).destroy();
			}
		},
		customCssClassReplace: function () {
			var css_classes, css_regex, class_match;

			css_classes = this.$el.find( '.wpb_column' ).attr( 'class' );
			css_regex = /.*(vc_custom_\d+).*/;
			class_match = css_classes && css_classes.match ? css_classes.match( css_regex ) : false;
			if ( class_match && class_match[ 1 ] ) {
				this.$el.addClass( class_match[ 1 ] );
				this.$el.find( '.wpb_column' ).attr( 'class', css_classes.replace( class_match[ 1 ], '' ).trim() );
			}
		},
		setColumnClasses: function () {
			var offset = this.getParam( 'offset' ) || '',
				width = this.getParam( 'width' ) || '1/1',
				$content = this.$el.find( '> .wpb_column' );
			this.css_class_width = this.convertSize( width ).replace( /[^\d]/g, '' );
			$content.removeClass( 'vc_col-sm-' + this.css_class_width );
			if ( ! offset.match( /vc_col\-sm\-\d+/ ) ) {
				this.$el.addClass( 'vc_col-sm-' + this.css_class_width );
			}
			if ( vc.responsive_disabled ) {
				offset = offset.replace( /vc_col\-(lg|md|xs)[^\s]*/g, '' );
			}
			if ( ! _.isEmpty( offset ) ) {
				$content.removeClass( offset );
				this.$el.addClass( offset );
			}
		},
		startChangeSize: function ( e ) {
			var width = this.getParam( width ) || 12;
			this._grid_step = this.parent_view.$el.width() / width;
			vc.frame_window.jQuery( 'body' ).addClass( 'vc_column-dragging' ).disableSelection();
			this._x = parseInt( e.pageX );
			vc.$page.bind( 'mousemove.' + this.resizeDomainName, this.resize );
			$( vc.frame_window.document ).mouseup( this.stopChangeSize );
		},
		stopChangeSize: function () {
			this._x = 0;
			vc.frame_window.jQuery( 'body' ).removeClass( 'vc_column-dragging' ).enableSelection();
			vc.$page.unbind( 'mousemove.' + this.resizeDomainName );
		},
		resize: function ( e ) {
			var width, old_width, diff, params = this.model.get( 'params' );
			diff = e.pageX - this._x;
			if ( Math.abs( diff ) < this._grid_step ) {
				return;
			}
			this._x = parseInt( e.pageX );
			old_width = '' + this.css_class_width;
			if ( 0 < diff ) {
				this.css_class_width += 1;
			} else if ( 0 > diff ) {
				this.css_class_width -= 1;
			}
			if ( 12 < this.css_class_width ) {
				this.css_class_width = 12;
			}
			if ( 1 > this.css_class_width ) {
				this.css_class_width = 1;
			}
			params.width = vc.getColumnSize( this.css_class_width );
			this.model.save( { params: params }, { silent: true } );
			this.$el.removeClass( 'vc_col-sm-' + old_width ).addClass( 'vc_col-sm-' + this.css_class_width );
		},
		convertSize: function ( width ) {
			var prefix = 'vc_col-sm-',
				numbers = width ? width.split( '/' ) : [
					1,
					1
				],
				range = _.range( 1, 13 ),
				num = ! _.isUndefined( numbers[ 0 ] ) && 0 <= _.indexOf( range,
					parseInt( numbers[ 0 ], 10 ) ) ? parseInt( numbers[ 0 ], 10 ) : false,
				dev = ! _.isUndefined( numbers[ 1 ] ) && 0 <= _.indexOf( range,
					parseInt( numbers[ 1 ], 10 ) ) ? parseInt( numbers[ 1 ], 10 ) : false;
			if ( false !== num && false !== dev ) {
				return prefix + (12 * num / dev);
			}
			return prefix + '12';
		}
	} );
	window.InlineShortcodeView_vc_row_inner = window.InlineShortcodeView_vc_row.extend( {
		column_tag: 'vc_column_inner'
	} );
	window.InlineShortcodeView_vc_column_inner = window.InlineShortcodeView_vc_column.extend( {} );
	window.InlineShortcodeView_vc_tabs = window.InlineShortcodeView_vc_row.extend( {
		events: {
			'click > :first > .vc_empty-element': 'addElement',
			'click > :first > .wpb_wrapper > .ui-tabs-nav > li': 'setActiveTab'
		},
		already_build: false,
		active_model_id: false,
		$tabsNav: false,
		active: 0,
		render: function () {
			_.bindAll( this, 'stopSorting' );
			this.$tabs = this.$el.find( '> .wpb_tabs' );
			window.InlineShortcodeView_vc_tabs.__super__.render.call( this );
			this.buildNav();
			return this;
		},
		buildNav: function () {
			var $nav = this.tabsControls();
			this.$tabs.find( '> .wpb_wrapper > .vc_element[data-tag="vc_tab"]' ).each( function ( key ) {
				$( 'li:eq(' + key + ')', $nav ).attr( 'data-m-id', $( this ).data( 'model-id' ) );
			} );
		},
		changed: function () {
			if ( 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' ).find( '> :first > div' ).addClass( 'vc_empty-element' );
			} else {
				this.$el.removeClass( 'vc_empty' ).find( '> :first > div' ).removeClass( 'vc_empty-element' );
			}
			this.setSorting();
		},
		setActiveTab: function ( e ) {
			var $tab = $( e.currentTarget );
			this.active_model_id = $tab.data( 'm-id' );
		},
		tabsControls: function () {
			return this.$tabsNav ? this.$tabsNav : this.$tabsNav = this.$el.find( '.wpb_tabs_nav' );
		},
		buildTabs: function ( active_model ) {
			if ( active_model ) {
				this.active_model_id = active_model.get( 'id' );
				this.active = this.tabsControls().find( '[data-m-id=' + this.active_model_id + ']' ).index();
			}
			if ( false === this.active_model_id ) {
				var active_el = this.tabsControls().find( 'li:first' );
				this.active = active_el.index();
				this.active_model_id = active_el.data( 'm-id' );
			}
			if ( ! this.checkCount() ) {
				vc.frame_window.vc_iframe.buildTabs( this.$tabs, this.active );
			}
		},
		checkCount: function () {
			return this.$tabs.find( '> .wpb_wrapper > .vc_element[data-tag="vc_tab"]' ).length != this.$tabs.find( '> .wpb_wrapper > .vc_element.vc_vc_tab' ).length;
		},
		beforeUpdate: function () {
			this.$tabs.find( '.wpb_tabs_heading' ).remove();
			vc.frame_window.vc_iframe.destroyTabs( this.$tabs );
		},
		updated: function () {
			window.InlineShortcodeView_vc_tabs.__super__.updated.call( this );
			this.$tabs.find( '.wpb_tabs_nav:first' ).remove();
			this.buildNav();
			vc.frame_window.vc_iframe.buildTabs( this.$tabs );
			this.setSorting();
		},
		rowsColumnsConverted: function () {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
			} );
		},
		addTab: function ( model ) {
			if ( this.updateIfExistTab( model ) ) {
				return false;
			}
			var $control = this.buildControlHtml( model ),
				$cloned_tab;
			if ( model.get( 'cloned' ) && ($cloned_tab = this.tabsControls().find( '[data-m-id=' + model.get( 'cloned_from' ).id + ']' )).length ) {
				if ( ! model.get( 'cloned_appended' ) ) {
					$control.appendTo( this.tabsControls() );
					model.set( 'cloned_appended', true );
				}
			} else {
				$control.appendTo( this.tabsControls() );
			}
			this.changed();
			return true;
		},
		cloneTabAfter: function ( model ) {
			this.$tabs.find( '> .wpb_wrapper > .wpb_tabs_nav > div' ).remove();
			this.buildTabs( model );
		},
		updateIfExistTab: function ( model ) {
			var $tab = this.tabsControls().find( '[data-m-id=' + model.get( 'id' ) + ']' );
			if ( $tab.length ) {
				$tab.attr( 'aria-controls', 'tab-' + model.getParam( 'tab_id' ) )
					.find( 'a' )
					.attr( 'href', '#tab-' + model.getParam( 'tab_id' ) )
					.text( model.getParam( 'title' ) );
				return true;
			}
			return false;
		},
		buildControlHtml: function ( model ) {
			var params = model.get( 'params' ),
				$tab = $( '<li data-m-id="' + model.get( 'id' ) + '"><a href="#tab-' + model.getParam( 'tab_id' ) + '"></a></li>' );
			$tab.data( 'model', model );
			$tab.find( '> a' ).text( model.getParam( 'title' ) );
			return $tab;
		},
		addElement: function ( e ) {
			e && e.preventDefault();
			new vc.ShortcodesBuilder()
				.create( {
					shortcode: 'vc_tab',
					params: {
						tab_id: vc_guid() + '-' + this.tabsControls().find( 'li' ).length,
						title: this.getDefaultTabTitle()
					},
					parent_id: this.model.get( 'id' )
				} )
				.render();
		},
		getDefaultTabTitle: function () {
			return window.i18nLocale.tab;
		},
		setSorting: function () {
			if ( this.hasUserAccess() ) {
				vc.frame_window.vc_iframe.setTabsSorting( this );
			}
		},
		stopSorting: function ( event, ui ) {
			this.tabsControls().find( '> li' ).each( function ( key, value ) {
				var model = $( this ).data( 'model' );
				model.save( { order: key }, { silent: true } );
			} );
		},
		placeElement: function ( $view, activity ) {
			var model = vc.shortcodes.get( $view.data( 'modelId' ) );
			if ( model && model.get( 'place_after_id' ) ) {
				$view.insertAfter( vc.$page.find( '[data-model-id=' + model.get( 'place_after_id' ) + ']' ) );
				model.unset( 'place_after_id' );
			} else {
				$view.insertAfter( this.tabsControls() );
			}
			this.changed();
		},
		removeTab: function ( model ) {
			if ( 1 === vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ).length ) {
				return this.model.destroy();
			}
			var $tab = this.tabsControls().find( '[data-m-id=' + model.get( 'id' ) + ']' ),
				index = $tab.index();
			if ( this.tabsControls().find( '[data-m-id]:eq(' + (index + 1) + ')' ).length ) {
				vc.frame_window.vc_iframe.setActiveTab( this.$tabs, (index + 1) );
			} else if ( this.tabsControls().find( '[data-m-id]:eq(' + (index - 1) + ')' ).length ) {
				vc.frame_window.vc_iframe.setActiveTab( this.$tabs, (index - 1) );
			} else {
				vc.frame_window.vc_iframe.setActiveTab( this.$tabs, 0 );
			}
			$tab.remove();
		},
		clone: function ( e ) {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.set( 'active_before_cloned', this.active_model_id === model.get( 'id' ) );
			}, this );
			window.InlineShortcodeView_vc_tabs.__super__.clone.call( this, e );
		}
	} );
	window.InlineShortcodeView_vc_tour = window.InlineShortcodeView_vc_tabs.extend( {
		render: function () {
			_.bindAll( this, 'stopSorting' );
			this.$tabs = this.$el.find( '> .wpb_tour' );
			window.InlineShortcodeView_vc_tabs.__super__.render.call( this );
			this.buildNav();
			return this;
		},
		beforeUpdate: function () {
			this.$tabs.find( '.wpb_tour_heading,.wpb_tour_next_prev_nav' ).remove();
			vc.frame_window.vc_iframe.destroyTabs( this.$tabs );
		},
		updated: function () {
			this.$tabs.find( '.wpb_tour_next_prev_nav' ).appendTo( this.$tabs );
			window.InlineShortcodeView_vc_tour.__super__.updated.call( this );
		}
	} );
	window.InlineShortcodeView_vc_tab = window.InlineShortcodeViewContainerWithParent.extend( {
		controls_selector: '#vc_controls-template-vc_tab',
		render: function () {
			var tab_id, active, params;
			params = this.model.get( 'params' );
			window.InlineShortcodeView_vc_tab.__super__.render.call( this );
			this.$tab = this.$el.find( '> :first' );
			/**
			 * @deprecated 4.4.3
			 * @see composer-atts.js vc.atts.tab_id.addShortcode
			 */
			if ( _.isEmpty( params.tab_id ) ) {
				params.tab_id = vc_guid() + '-' + Math.floor( Math.random() * 11 );
				this.model.save( 'params', params );
				tab_id = 'tab-' + params.tab_id;
				this.$tab.attr( 'id', tab_id );
			} else {
				tab_id = this.$tab.attr( 'id' );
			}
			this.$el.attr( 'id', tab_id );
			this.$tab.attr( 'id', tab_id + '-real' );
			if ( ! this.$tab.find( '.vc_element[data-tag]' ).length ) {
				this.$tab.empty();
			}
			this.$el.addClass( 'ui-tabs-panel wpb_ui-tabs-hide' );
			this.$tab.removeClass( 'ui-tabs-panel wpb_ui-tabs-hide' );
			if ( this.parent_view && this.parent_view.addTab ) {
				if ( ! this.parent_view.addTab( this.model ) ) {
					this.$el.removeClass( 'wpb_ui-tabs-hide' );
				}
			}
			active = this.doSetAsActive();
			this.parent_view.buildTabs( active );
			return this;
		},
		doSetAsActive: function () {
			var active_before_cloned = this.model.get( 'active_before_cloned' );
			if ( ! this.model.get( 'from_content' ) && ! this.model.get( 'default_content' ) && _.isUndefined( active_before_cloned ) ) {
				return this.model;
			} else if ( ! _.isUndefined( active_before_cloned ) ) {
				this.model.unset( 'active_before_cloned' );
				if ( true === active_before_cloned ) {
					return this.model;
				}
			}
			return false;
		},
		removeView: function ( model ) {
			window.InlineShortcodeView_vc_tab.__super__.removeView.call( this, model );
			if ( this.parent_view && this.parent_view.removeTab ) {
				this.parent_view.removeTab( model );
			}
		},
		clone: function ( e ) {
			_.isObject( e ) && e.preventDefault() && e.stopPropagation();
			vc.clone_index = vc.clone_index / 10;
			var clone = this.model.clone(),
				params = clone.get( 'params' ),
				builder = new vc.ShortcodesBuilder();
			var newmodel = vc.CloneModel( builder, this.model, this.model.get( 'parent_id' ) );
			var active_model = this.parent_view.active_model_id;
			var that = this;
			builder.render( function () {
				if ( that.parent_view.cloneTabAfter ) {
					that.parent_view.cloneTabAfter( newmodel );
				}
			} );

		},
		rowsColumnsConverted: function () {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
			} );
		}
	} );
	window.InlineShortcodeView_vc_accordion = window.InlineShortcodeView_vc_row.extend( {
		events: {
			'click > .wpb_accordion > .vc_empty-element': 'addElement'
		},
		render: function () {
			_.bindAll( this, 'stopSorting' );
			this.$accordion = this.$el.find( '> .wpb_accordion' );
			window.InlineShortcodeView_vc_accordion.__super__.render.call( this );
			return this;
		},
		changed: function () {
			if ( 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' ).find( '> :first' ).addClass( 'vc_empty-element' );
			} else {
				this.$el.removeClass( 'vc_empty' ).find( '> .vc_empty-element' ).removeClass( 'vc_empty-element' );
				this.setSorting();
			}
		},
		buildAccordion: function ( active_model ) {
			var active = false;
			if ( active_model ) {
				active = this.$accordion.find( '[data-model-id=' + active_model.get( 'id' ) + ']' ).index();
			}
			vc.frame_window.vc_iframe.buildAccordion( this.$accordion, active );
		},
		setSorting: function () {
			vc.frame_window.vc_iframe.setAccordionSorting( this );
		},
		beforeUpdate: function () {
			this.$el.find( '.wpb_accordion_heading' ).remove();
			window.InlineShortcodeView_vc_accordion.__super__.beforeUpdate.call( this );
		},
		stopSorting: function () {
			this.$accordion.find( '> .wpb_accordion_wrapper > .vc_element[data-tag]' ).each( function () {
				var model = vc.shortcodes.get( $( this ).data( 'modelId' ) );
				model.save( { order: $( this ).index() }, { silent: true } );
			} );
		},
		addElement: function ( e ) {
			e && e.preventDefault();
			new vc.ShortcodesBuilder()
				.create( {
					shortcode: 'vc_accordion_tab',
					params: { title: window.i18nLocale.section },
					parent_id: this.model.get( 'id' )
				} )
				.render();
		},
		rowsColumnsConverted: function () {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
			} );
		}
	} );
	window.InlineShortcodeView_vc_accordion_tab = window.InlineShortcodeView_vc_tab.extend( {
		events: {
			'click > .vc_controls .vc_element .vc_control-btn-delete': 'destroy',
			'click > .vc_controls .vc_element .vc_control-btn-edit': 'edit',
			'click > .vc_controls .vc_element .vc_control-btn-clone': 'clone',
			'click > .vc_controls .vc_element .vc_control-btn-prepend': 'prependElement',
			'click > .vc_controls .vc_control-btn-append': 'appendElement',
			'click > .vc_controls .vc_parent .vc_control-btn-delete': 'destroyParent',
			'click > .vc_controls .vc_parent .vc_control-btn-edit': 'editParent',
			'click > .vc_controls .vc_parent .vc_control-btn-clone': 'cloneParent',
			'click > .vc_controls .vc_parent .vc_control-btn-prepend': 'addSibling',
			'click > .wpb_accordion_section > .vc_empty-element': 'appendElement',
			'click > .vc_controls .vc_control-btn-switcher': 'switchControls',
			'mouseenter': 'resetActive',
			'mouseleave': 'holdActive'
		},
		changed: function () {
			if ( 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' );
				this.content().addClass( 'vc_empty-element' );
			} else {
				this.$el.removeClass( 'vc_empty' );
				this.content().removeClass( 'vc_empty-element' );
			}
		},
		render: function () {
			window.InlineShortcodeView_vc_tab.__super__.render.call( this );
			if ( ! this.content().find( '.vc_element[data-tag]' ).length ) {
				this.content().empty();
			}
			this.parent_view.buildAccordion( ! this.model.get( 'from_content' ) && ! this.model.get( 'default_content' ) ? this.model : false );
			return this;
		},
		rowsColumnsConverted: function () {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
			} );
		},
		destroy: function ( e ) {
			var parent_id = this.model.get( 'parent_id' );
			window.InlineShortcodeView_vc_accordion_tab.__super__.destroy.call( this, e );
			if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
				vc.shortcodes.get( parent_id ).destroy();
			}
		}
	} );
	vc.cloneMethod_vc_tab = function ( data, model ) {
		data.params = _.extend( {}, data.params );
		data.params.tab_id = vc_guid() + '-cl';
		if ( ! _.isUndefined( model.get( 'active_before_cloned' ) ) ) {
			data.active_before_cloned = model.get( 'active_before_cloned' );
		}
		return data;
	};
	window.InlineShortcodeView_vc_pie = window.InlineShortcodeView.extend( {
		render: function () {
			_.bindAll( this, 'parentChanged' );
			window.InlineShortcodeView_vc_pie.__super__.render.call( this );
			this.unbindResize();
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_iframe.vc_pieChart();
			} );
			return this;
		},
		unbindResize: function () {
			vc.frame_window.jQuery( vc.frame_window ).unbind( 'resize.vcPieChartEditable' );
		},
		parentChanged: function () {
			this.$el.find( '.vc_pie_chart' ).removeClass( 'vc_ready' );
			vc.frame_window.vc_pieChart();
		},
		rowsColumnsConverted: function () {
			window.setTimeout( this.parentChanged, 200 );
			this.parentChanged();
		}
	} );
	window.InlineShortcodeView_vc_round_chart = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_round_chart.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_round_charts( model_id );
			} );
			return this;
		},
		parentChanged: function () {
			var modelId = this.model.get( 'id' );
			window.InlineShortcodeView_vc_round_chart.__super__.parentChanged.call( this );
			_.defer( function () {
				vc.frame_window.vc_round_charts( modelId );
			} );
			return this;
		},
		remove: function () {
			var id = this.$el.find( '.vc_round-chart' ).data( 'vcChartId' );
			window.InlineShortcodeView_vc_round_chart.__super__.remove.call( this );
			if ( id && undefined !== vc.frame_window.Chart.instances[ id ] ) {
				delete vc.frame_window.Chart.instances[ id ];
			}
		}
	} );
	window.InlineShortcodeView_vc_line_chart = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_line_chart.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_line_charts( model_id );
			} );
			return this;
		},
		parentChanged: function () {
			var modelId = this.model.get( 'id' );
			window.InlineShortcodeView_vc_line_chart.__super__.parentChanged.call( this );
			_.defer( function () {
				vc.frame_window.vc_line_charts( modelId );
			} );
			return this;
		},
		remove: function () {
			var id = this.$el.find( '.vc_line-chart' ).data( 'vcChartId' );
			window.InlineShortcodeView_vc_line_chart.__super__.remove.call( this );
			if ( id && undefined !== vc.frame_window.Chart.instances[ id ] ) {
				delete vc.frame_window.Chart.instances[ id ];
			}
		}
	} );
	window.InlineShortcodeView_vc_single_image = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_single_image.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				if ( 'undefined' !== typeof(this.vc_image_zoom) ) {
					this.vc_image_zoom( model_id );
				}

			} );
			return this;
		},
		parentChanged: function () {
			var modelId = this.model.get( 'id' );
			window.InlineShortcodeView_vc_single_image.__super__.parentChanged.call( this );
			if ( 'undefined' !== typeof(vc.frame_window.vc_image_zoom) ) {
				_.defer( function () {
					vc.frame_window.vc_image_zoom( modelId );
				} );
			}
			return this;
		}
	} );
	window.InlineShortcodeView_vc_images_carousel = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_images_carousel.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_iframe.vc_imageCarousel( model_id );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_vc_carousel = window.InlineShortcodeView_vc_images_carousel.extend( {} );
	window.InlineShortcodeView_vc_gallery = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_gallery.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_iframe.vc_gallery( model_id );
			} );
			return this;
		},
		parentChanged: function () {
			window.InlineShortcodeView_vc_gallery.__super__.parentChanged.call( this );
			vc.frame_window.vc_iframe.vc_gallery( this.model.get( 'id' ) );
		}
	} );

	window.InlineShortcodeView_vc_posts_slider = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_posts_slider.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_iframe.vc_postsSlider( model_id );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_vc_toggle = window.InlineShortcodeView.extend( {
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_toggle.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_iframe.vc_toggle( model_id );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_vc_flickr = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_vc_flickr.__super__.render.call( this );
			var $placeholder = this.$el.find( '.vc_flickr-inline-placeholder' );
			vc.frame_window.vc_iframe.addActivity( function () {
				this.vc_iframe.vc_Flickr( $placeholder );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_vc_raw_js = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_vc_raw_js.__super__.render.call( this );
			var script = this.$el.find( '.vc_js_inline_holder' ).val();
			this.$el.find( '.wpb_wrapper' ).html( script );
			return this;
		}
	} );

	vc.addTemplateFilter( function ( string ) {
		var random_id = VCS4() + '-' + VCS4();
		return string.replace( /tab\_id\=\"([^\"]+)\"/g, 'tab_id="$1' + random_id + '"' );
	} );
	window.InlineShortcodeView_vc_basic_grid = vc.shortcode_view.extend( {
		render: function ( e ) {
			window.InlineShortcodeView_vc_basic_grid.__super__.render.call( this, e );
			this.initGridJs( true );
			return this;
		},
		parentChanged: function () {
			window.InlineShortcodeView_vc_basic_grid.__super__.parentChanged.call( this );
			this.initGridJs();
		},
		initGridJs: function ( useAddActivity ) {
			var model = this.model;
			if ( true === model.get( 'grid_activity' ) ) {
				return false;
			}
			model.set( 'grid_activity', true );
			if ( true === useAddActivity ) {

				vc.frame_window.vc_iframe.addActivity( function () {
					this.vc_iframe.gridInit( model.get( 'id' ) );
					model.set( 'grid_activity', false );
				} );
			} else {
				vc.frame_window.vc_iframe.gridInit( model.get( 'id' ) );
				model.set( 'grid_activity', false );
			}
		}
	} );
	window.InlineShortcodeView_vc_masonry_grid = window.InlineShortcodeView_vc_basic_grid.extend();
	window.InlineShortcodeView_vc_media_grid = window.InlineShortcodeView_vc_basic_grid.extend();
	window.InlineShortcodeView_vc_masonry_media_grid = window.InlineShortcodeView_vc_basic_grid.extend();

	window.InlineShortcodeView_vc_tta_accordion = window.InlineShortcodeViewContainer.extend( {
		events: {},
		childTag: 'vc_tta_section',
		activeClass: 'vc_active',
		// controls_selector: '#vc_controls-template-vc_tta_accordion',
		defaultSectionTitle: window.i18nLocale.section,
		initialize: function () {
			window.InlineShortcodeView_vc_tta_accordion.__super__.initialize.call( this );
		},
		render: function () {
			window.InlineShortcodeViewContainer.__super__.render.call( this );
			_.bindAll( this, 'buildSortable', 'updateSorting' );
			this.content(); // just to remove span inline-container anchor..
			this.buildPagination();
			return this;
		},
		addControls: function () {
			this.$controls = $( '<div class="no-controls"></div>' );
			this.$controls.appendTo( this.$el );
			return this;
		},
		/**
		 * Add new element to Accordion.
		 * @param e
		 */
		addElement: function ( e ) {
			e && e.preventDefault();
			this.addSection( 'parent.prepend' === $( e.currentTarget ).data( 'vcControl' ) );
		},
		appendElement: function ( e ) {
			return this.addElement( e );
		},
		prependElement: function ( e ) {
			return this.addElement( e );
		},
		addSection: function ( prepend ) {
			var shortcode, params, i;

			shortcode = this.childTag;

			params = {
				shortcode: shortcode,
				parent_id: this.model.get( 'id' ),
				isActiveSection: true,
				params: {
					title: this.defaultSectionTitle
				}
			};

			if ( prepend ) {
				vc.activity = 'prepend';
				params.order = this.getSiblingsFirstPositionIndex();
			}

			vc.builder.create( params );

			// extend default params with settings presets if there are any
			for ( i = vc.builder.models.length - 1;
				  i >= 0;
				  i -- ) {
				shortcode = vc.builder.models[ i ].get( 'shortcode' );
				if ( 'undefined' !== typeof(window.vc_settings_presets[ shortcode ]) ) {
					vc.builder.models[ i ].attributes.params = _.extend(
						vc.builder.models[ i ].attributes.params,
						window.vc_settings_presets[ shortcode ]
					);

					// generate new random tab_id if needed
					if ( 'vc_tta_section' === shortcode && 'undefined' !== typeof(vc.builder.models[ i ].attributes.params.tab_id ) ) {
						vc.builder.models[ i ].attributes.params.tab_id = vc_guid() + '-cl';
					}
				}
			}

			vc.builder.render();
		},
		getSiblingsFirstPositionIndex: function () {
			var order,
				shortcodeFirst;
			order = 0;
			shortcodeFirst = vc.shortcodes.sort().findWhere( { parent_id: this.model.get( 'id' ) } );
			if ( shortcodeFirst ) {
				order = shortcodeFirst.get( 'order' ) - 1;
			}
			return order;
		},
		changed: function () {
			vc.frame_window.vc_iframe.buildTTA();
			window.InlineShortcodeView_vc_tta_accordion.__super__.changed.call( this );
			_.defer( this.buildSortable );
			this.buildPagination();
		},
		updated: function () {
			window.InlineShortcodeView_vc_tta_accordion.__super__.updated.call( this );
			_.defer( this.buildSortable );
			this.buildPagination();
		},
		buildSortable: function () {
			if ( this.$el ) {
				this.$el.find( '.vc_tta-panels' ).sortable( {
					forcePlaceholderSize: true,
					placeholder: 'vc_placeholder-row', // TODO: fix placeholder
					start: this.startSorting,
					over: function ( event, ui ) {
						ui.placeholder.css( { maxWidth: ui.placeholder.parent().width() } );
						ui.placeholder.removeClass( 'vc_hidden-placeholder' );
					},
					items: '> .vc_element',
					handle: '.vc_tta-panel-heading, .vc_child-element-move',// TODO: change vc_column to vc_tta_section
					update: this.updateSorting
				} );
			}
		},
		startSorting: function ( event, ui ) {
			ui.placeholder.width( ui.item.width() );
		},
		updateSorting: function ( event, ui ) {
			var self = this;
			this.getPanelsList().find( '> .vc_element' ).each( function () {
				var shortcode, modelId, $this;

				$this = $( this );
				modelId = $this.data( 'modelId' );
				shortcode = vc.shortcodes.get( modelId );
				shortcode.save( { 'order': self.getIndex( $this ) }, { silent: true } );
			} );
			// re-render pagination
			this.buildPagination();
		},
		getIndex: function ( $element ) {
			return $element.index();
		},
		getPanelsList: function () {
			return this.$el.find( '.vc_tta-panels' );
		},
		parentChanged: function () {
			window.InlineShortcodeView_vc_tta_accordion.__super__.parentChanged.call( this );

			if ( 'undefined' !== typeof(vc.frame_window.vc_round_charts) ) {
				vc.frame_window.vc_round_charts( this.model.get( 'id' ) );
			}

			if ( 'undefined' !== typeof(vc.frame_window.vc_line_charts) ) {
				vc.frame_window.vc_line_charts( this.model.get( 'id' ) );
			}
		},
		buildPagination: function () {
		},
		removePagination: function () {
			this.$el.find( '.vc_tta-panels-container' ).find( ' > .vc_pagination' ).remove(); // TODO: check this
		},
		getPaginationList: function () {
			var $accordions,
				classes,
				styleChunks,
				that,
				html,
				params;

			params = this.model.get( 'params' );
			if ( ! _.isUndefined( params.pagination_style ) && params.pagination_style.length ) {
				$accordions = this.$el.find( '[data-vc-accordion]' );
				classes = [];
				classes.push( 'vc_general' );
				classes.push( 'vc_pagination' );
				styleChunks = params.pagination_style.split( '-' );
				classes.push( 'vc_pagination-style-' + styleChunks[ 0 ] );
				classes.push( 'vc_pagination-shape-' + styleChunks[ 1 ] );

				if ( ! _.isUndefined( params.pagination_color ) && params.pagination_color.length ) {
					classes.push( 'vc_pagination-color-' + params.pagination_color );
				}
				html = [];
				html.push( '<ul class="' + classes.join( ' ' ) + '">' );

				that = this;
				$accordions.each( function () {
					var sectionClasses,
						activeSection,
						$this,
						$closestPanel,
						selector,
						aHtml;

					$this = $( this );
					$closestPanel = $this.closest( '.vc_tta-panel' );
					activeSection = $closestPanel.hasClass( that.activeClass );
					sectionClasses = [ 'vc_pagination-item' ];
					if ( activeSection ) {
						sectionClasses.push( that.activeClass );
					}

					selector = $this.attr( 'href' );
					if ( 0 !== selector.indexOf( '#' ) ) {
						selector = '';
					}
					if ( $this.attr( 'data-vc-target' ) ) {
						selector = $this.attr( 'data-vc-target' );
					}
					aHtml = '<a href="javascript:;" data-vc-target="' + selector + '" class="vc_pagination-trigger" data-vc-tabs data-vc-container=".vc_tta"></a>';
					html.push( '<li class="' + sectionClasses.join( ' ' ) + '" data-vc-tab>' + aHtml + '</li>' );
				} );

				html.push( '</ul>' );
				return $( html.join( '' ) );
			}
			return null;
		}
	} );
	window.InlineShortcodeView_vc_tta_tabs = window.InlineShortcodeView_vc_tta_accordion.extend( {
		render: function () {
			window.InlineShortcodeView_vc_tta_tabs.__super__.render.call( this );
			_.bindAll( this, 'buildSortableNavigation', 'updateSortingNavigation' );
			this.createTabs();
			_.defer( this.buildSortableNavigation );
			return this;
		},
		createTabs: function () {
			var models = _.sortBy( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ),
				function ( model ) {
					return model.get( 'order' );
				} );
			_.each( models, function ( model ) {
				this.sectionUpdated( model, true );
			}, this );
		},
		defaultSectionTitle: window.i18nLocale.tab,
		addIcon: function ( model, html ) {
			var icon, iconClass, iconHtml;
			if ( 'true' === model.getParam( 'add_icon' ) ) {
				icon = model.getParam( 'i_icon_' + model.getParam( 'i_type' ) );
				if ( ! _.isUndefined( icon ) ) {
					iconClass = 'vc_tta-icon' + ' ' + icon;
					iconHtml = '<i class="' + iconClass + '"></i>';
				}
				if ( 'right' === model.getParam( 'i_position' ) ) {
					html += iconHtml;
				} else {
					html = iconHtml + html;
				}
			}
			return html;
		},
		/**
		 *
		 * @param {Backbone.Model}model
		 */
		sectionUpdated: function ( model, justAppend ) {
			// update builded tabs, remove/add check orders and title/target

			var $tabEl,
				$navigation,
				sectionId,
				html, title, models, index, tabAdded;
			tabAdded = false;
			sectionId = model.get( 'id' );
			$navigation = this.$el.find( '.vc_tta-tabs-container .vc_tta-tabs-list' );
			$tabEl = $navigation.find( '[data-vc-target="[data-model-id=' + sectionId + ']"]' );
			title = model.getParam( 'title' );

			if ( $tabEl.length ) {
				html = '<span class="vc_tta-title-text">' + title + '</span>';
				html = this.addIcon( model, html );

				$tabEl.html( html );
			} else {
				var $element;
				html = '<span class="vc_tta-title-text">' + title + '</span>';

				html = this.addIcon( model, html );
				$element = $( '<li class="vc_tta-tab" data-vc-target-model-id="' + sectionId + '" data-vc-tab><a href="javascript:;" data-vc-use-cache="false" data-vc-tabs data-vc-target="[data-model-id=' + sectionId + ']" data-vc-container=".vc_tta">' + html + '</a></li>' );
				if ( true !== justAppend ) {
					models = _.pluck( _.sortBy( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ),
						function ( childModel ) {
							return childModel.get( 'order' );
						} ), 'id' );
					index = models.indexOf( model.get( 'id' ) ) - 1;
					if ( index > - 1 && $navigation.find( '[data-vc-tab]:eq(' + index + ')' ).length ) {
						$element.insertAfter( $navigation.find( '[data-vc-tab]:eq(' + index + ')' ) );
						tabAdded = true;
					}
				}
				! tabAdded && $element.appendTo( $navigation );
				if ( model.get( 'isActiveSection' ) ) {
					$element.addClass( this.activeClass );
				}
			}
			this.buildPagination();
		},
		getNextTab: function ( $viewTab ) {
			var lastIndex, viewTabIndex, $nextTab, $navigationSections;

			$navigationSections = this.$el.find( '.vc_tta-tabs-container .vc_tta-tabs-list' ).children();
			lastIndex = $navigationSections.length - 1; // -1 because length starts from 1
			viewTabIndex = $viewTab.index();

			if ( viewTabIndex !== lastIndex ) {
				$nextTab = $navigationSections.eq( viewTabIndex + 1 );
			} else {
				// If we are the last tab in in navigation lets make active previous
				$nextTab = $navigationSections.eq( viewTabIndex - 1 );
			}
			return $nextTab;
		},
		removeSection: function ( modelId ) {
			var $viewTab, $nextTab, tabIsActive;

			$viewTab = this.$el.find( '.vc_tta-tabs-container .vc_tta-tabs-list [data-vc-target="[data-model-id=' + modelId + ']"]' ).parent();
			tabIsActive = $viewTab.hasClass( this.activeClass );

			// Make next tab active if needed
			if ( tabIsActive ) {
				$nextTab = this.getNextTab( $viewTab );
				vc.frame_window.jQuery( $nextTab ).find( '[data-vc-target]' ).trigger( 'click' );
			}
			// Remove tab from navigation
			$viewTab.remove();
			this.buildPagination();
		},
		buildSortableNavigation: function () {
			// this should be called when new tab added/removed/changed.
			this.$el.find( '.vc_tta-tabs-container .vc_tta-tabs-list' ).sortable( {
				items: '.vc_tta-tab',
				forcePlaceholderSize: true,
				placeholder: 'vc_tta-tab vc_placeholder-tta-tab',
				helper: this.renderSortingHelper,
				start: function ( event, ui ) {
					ui.placeholder.width( ui.item.width() );
				},
				over: function ( event, ui ) {
					ui.placeholder.css( { maxWidth: ui.placeholder.parent().width() } );
					ui.placeholder.removeClass( 'vc_hidden-placeholder' );
				},
				update: this.updateSortingNavigation
			} );
		},
		updateSorting: function ( event, ui ) {
			window.InlineShortcodeView_vc_tta_tabs.__super__.updateSorting.call( this, event, ui );
			this.updateTabsPositions( this.getPanelsList() );
		},
		updateSortingNavigation: function () {
			var $tabs, self;
			self = this;
			$tabs = this.$el.find( '.vc_tta-tabs-list' );
			// we are sorting a tabs navigation
			$tabs.find( '> .vc_tta-tab' ).each( function () {
				var shortcode, modelId, $li;

				$li = $( this ).removeAttr( 'style' ); // TODO: Attensiton maybe e need to create method with filter
				modelId = $li.data( 'vcTargetModelId' );
				shortcode = vc.shortcodes.get( modelId );
				shortcode.save( { 'order': self.getIndex( $li ) }, { silent: true } );
				// now we need to sort panels
			} );
			this.updatePanelsPositions( $tabs );
		},
		updateTabsPositions: function ( $panels ) {
			var $tabs, $elements, tabSortableData;
			$tabs = this.$el.find( '.vc_tta-tabs-list' );
			if ( $tabs.length ) {
				$elements = [];
				tabSortableData = $panels.sortable( 'toArray', { attribute: 'data-model-id' } );
				_.each( tabSortableData, function ( value ) {
					$elements.push( $tabs.find( '[data-vc-target-model-id="' + value + '"]' ) );
				}, this );
				$tabs.prepend( $elements );
			}
			this.buildPagination();
		},
		updatePanelsPositions: function ( $tabs ) {
			var $elements, tabSortableData, $panels;
			$panels = this.getPanelsList();
			$elements = [];
			tabSortableData = $tabs.sortable( 'toArray', { attribute: 'data-vc-target-model-id' } );
			_.each( tabSortableData, function ( value ) {
				$elements.push( $panels.find( '[data-model-id="' + value + '"]' ) );
			}, this );
			$panels.prepend( $elements );
			this.buildPagination();
		},
		renderSortingHelper: function ( event, currentItem ) {
			var helper, currentItemWidth, currentItemHeight;
			helper = currentItem;
			currentItemWidth = currentItem.width() + 1;
			currentItemHeight = currentItem.height();
			helper.width( currentItemWidth );
			helper.height( currentItemHeight );
			return helper;
		},
		buildPagination: function () {
			var params;
			this.removePagination();
			// If tap-pos top append:
			params = this.model.get( 'params' );
			if ( ! _.isUndefined( params.pagination_style ) && params.pagination_style.length ) {
				if ( 'top' === params.tab_position ) {
					this.$el.find( '.vc_tta-panels-container' ).append( this.getPaginationList() );
				} else {
					this.getPaginationList().insertBefore( this.$el.find( '.vc_tta-container .vc_tta-panels' ) ); // TODO: change this
				}
			}
		}
	} );
	window.InlineShortcodeView_vc_tta_tour = window.InlineShortcodeView_vc_tta_tabs.extend( {
		defaultSectionTitle: window.i18nLocale.section,
		buildPagination: function () {
			this.removePagination();
			var params = this.model.get( 'params' );
			if ( ! _.isUndefined( params.pagination_style ) && params.pagination_style.length ) {
				this.$el.find( '.vc_tta-panels-container' ).append( this.getPaginationList() ); // TODO: change this
			}
		}
	} );
	window.InlineShortcodeView_vc_tta_pageable = window.InlineShortcodeView_vc_tta_tour.extend( {} );

	vc.ttaSectionActivateOnClone = false;
	window.InlineShortcodeView_vc_tta_section = window.InlineShortcodeViewContainerWithParent.extend( {
		events: {
			'click > .vc_controls [data-vc-control="destroy"]': 'destroy',
			'click > .vc_controls [data-vc-control="edit"]': 'edit',
			'click > .vc_controls [data-vc-control="clone"]': 'clone',
			'click > .vc_controls [data-vc-control="prepend"]': 'prependElement',
			'click > .vc_controls [data-vc-control="append"]': 'appendElement',
			'click > .vc_controls [data-vc-control="parent.destroy"]': 'destroyParent',
			'click > .vc_controls [data-vc-control="parent.edit"]': 'editParent',
			'click > .vc_controls [data-vc-control="parent.clone"]': 'cloneParent',
			'click > .vc_controls [data-vc-control="parent.append"]': 'addSibling',
			'click .vc_tta-panel-body > [data-js-panel-body].vc_empty-element': 'appendElement',
			'click > .vc_controls .vc_control-btn-switcher': 'switchControls',
			'mouseenter': 'resetActive',
			'mouseleave': 'holdActive'
		},

		controls_selector: '#vc_controls-template-vc_tta_section',
		previousClasses: false,
		activeClass: 'vc_active',
		render: function () {
			var model = this.model;
			window.InlineShortcodeView_vc_tta_section.__super__.render.call( this );
			_.bindAll( this, 'bindAccordionEvents' );
			this.refreshContent();
			this.moveClasses();
			_.defer( this.bindAccordionEvents );
			if ( this.isAsActiveSection() ) {
				window.vc.frame_window.vc_iframe.addActivity( function () {
					var $accordion = window.vc.frame_window.jQuery( '[data-vc-accordion][data-vc-target="[data-model-id=' + model.get( 'id' ) + ']"]' );
					$accordion.trigger( 'click' );
				} );
			}
			return this;
		},
		clone: function ( e ) {
			vc.ttaSectionActivateOnClone = true;
			window.InlineShortcodeView_vc_tta_section.__super__.clone.call( this, e );
		},
		addSibling: function ( e ) {
			window.InlineShortcodeView_vc_tta_section.__super__.addSibling.call( this, e );
		},
		parentChanged: function () {
			window.InlineShortcodeView_vc_tta_section.__super__.parentChanged.call( this );
			this.refreshContent( true );
			return this;
		},
		changed: function () {
			if ( 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' ).find( '.vc_tta-panel-body > [data-js-panel-body]' ).addClass( 'vc_empty-element' );
			} else {
				this.$el.removeClass( 'vc_empty' ).find( '.vc_tta-panel-body > [data-js-panel-body].vc_empty-element' ).removeClass( 'vc_empty-element' );
			}
		},
		moveClasses: function () {
			var panelClassName;
			if ( this.previousClasses ) {
				this.$el.get( 0 ).className = this.$el.get( 0 ).className.replace( this.previousClasses, "" );
			}
			panelClassName = this.$el.find( '.vc_tta-panel' ).get( 0 ).className;
			this.$el.attr( 'data-vc-content', this.$el.find( '.vc_tta-panel' ).data( 'vcContent' ) );
			this.previousClasses = panelClassName;
			this.$el.find( '.vc_tta-panel' ).get( 0 ).className = "";
			this.$el.get( 0 ).className = this.$el.get( 0 ).className + " " + this.previousClasses;
			// Fix data-vc-target for accordions:
			this.$el.find( '.vc_tta-panel-title [data-vc-target]' ).attr( 'data-vc-target',
				'[data-model-id=' + this.model.get( 'id' ) + ']' );
		},
		refreshContent: function ( noSectionUpdate ) {
			var $controlsIcon, $controlsIconsPositionEl, parentModel, parentParams, paramsMap, parentLayout;

			parentModel = vc.shortcodes.get( this.model.get( 'parent_id' ) );
			if ( _.isObject( parentModel ) ) {
				paramsMap = vc.getDefaultsAndDependencyMap( parentModel.get( 'shortcode' ) );
				parentParams = _.extend( {}, paramsMap.defaults, parentModel.get( 'params' ) );
				$controlsIcon = this.$el.find( '.vc_tta-controls-icon' );
				if ( parentParams && ! _.isUndefined( parentParams.c_icon ) && 0 < parentParams.c_icon.length ) {
					if ( $controlsIcon.length ) {
						$controlsIcon.attr( 'data-vc-tta-controls-icon', parentParams.c_icon );
					} else {
						this.$el.find( '[data-vc-tta-controls-icon-wrapper]' ).append(
							$( '<i class="vc_tta-controls-icon" data-vc-tta-controls-icon="' + parentParams.c_icon + '"></i>' )
						);
					}
					if ( ! _.isUndefined( parentParams.c_position ) && 0 < parentParams.c_position.length ) {
						$controlsIconsPositionEl = this.$el.find( '[data-vc-tta-controls-icon-position]' );
						if ( $controlsIconsPositionEl.length ) {
							$controlsIconsPositionEl.attr( 'data-vc-tta-controls-icon-position',
								parentParams.c_position );
						}
					}
				} else {
					$controlsIcon.remove();
					this.$el.find( '[data-vc-tta-controls-icon-position]' ).attr( 'data-vc-tta-controls-icon-position',
						'' );
				}
				if ( true !== noSectionUpdate && parentModel.view && parentModel.view.sectionUpdated ) {
					parentModel.view.sectionUpdated( this.model );
				}
			}
		},
		setAsActiveSection: function ( isActive ) {
			this.model.set( 'isActiveSection', ! ! isActive );
		},
		isAsActiveSection: function () {
			return ! ! this.model.get( 'isActiveSection' );
		},
		bindAccordionEvents: function () {
			var that = this;
			window.vc.frame_window.jQuery( '[data-vc-target="[data-model-id=' + this.model.get( 'id' ) + ']"]' )
				.on( 'show.vc.accordion hide.vc.accordion',
				function ( e ) {
					that.setAsActiveSection( 'show' === e.type );
				} );

		},
		destroy: function ( e ) {
			var parentModel, parentId;
			parentId = this.model.get( 'parent_id' );
			window.InlineShortcodeView_vc_tta_section.__super__.destroy.call( this, e );
			parentModel = vc.shortcodes.get( parentId );
			if ( ! vc.shortcodes.where( { parent_id: parentId } ).length ) {
				parentModel.destroy();
			} else {
				parentModel.view && parentModel.view.removeSection && parentModel.view.removeSection( this.model.get( 'id' ) );
			}
		}
	} );
	function TTaMapChildEvents( model ) {
		var childTag = 'vc_tta_section';
		vc.events.on(
			'shortcodes:' + childTag + ':add:parent:' + model.get( 'id' ),
			function ( model ) {
				var activeTabIndex, models, parentModel;
				parentModel = vc.shortcodes.get( model.get( 'parent_id' ) );
				activeTabIndex = parseInt( parentModel.getParam( 'active_section' ) );
				if ( 'undefined' === typeof(activeTabIndex) ) {
					activeTabIndex = 1;
				}
				models = _.pluck( _.sortBy( vc.shortcodes.where( { parent_id: parentModel.get( 'id' ) } ),
					function ( model ) {
						return model.get( 'order' );
					} ), 'id' );
				if ( models.indexOf( model.get( 'id' ) ) === activeTabIndex - 1 ) {
					model.set( 'isActiveSection', true );
				}
				return model;
			} );
		vc.events.on(
			'shortcodes:' + childTag + ':clone:parent:' + model.get( 'id' ),
			function ( model ) {
				vc.ttaSectionActivateOnClone && model.set( 'isActiveSection', true );
				vc.ttaSectionActivateOnClone = false;
			} );
	}

	vc.events.on( 'shortcodes:vc_tta_accordion:add', TTaMapChildEvents );
	vc.events.on( 'shortcodes:vc_tta_tabs:add', TTaMapChildEvents );
	vc.events.on( 'shortcodes:vc_tta_tour:add', TTaMapChildEvents );
	vc.events.on( 'shortcodes:vc_tta_pageable:add', TTaMapChildEvents );

	vc.events.on( 'shortcodeView:updated', function ( model ) {
		var modelId, settings;
		settings = vc.map[ model.get( 'shortcode' ) ] || false;
		if ( true === settings.is_container ) {
			modelId = model.get( 'id' );
			vc.frame_window.vc_iframe.updateChildGrids( modelId );
		}
	} );
})( window.jQuery );