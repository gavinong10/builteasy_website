/* global vc, i18nLocale */
(function ( $ ) {
	'use strict';

	vc.element_start_index = 0;

	vc.AddElementUIPanelBackendEditor = vc.PanelView
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.extend( {
			el: '#vc_ui-panel-add-element',
			searchSelector: '#vc_elements_name_filter',
			prepend: false,
			builder: '',
			events: {
				'click [data-vc-ui-element="button-close"]': 'hide',
				'click [data-vc-ui-element="panel-tab-control"]': 'filterElements',
				'click .vc_shortcode-link': 'createElement',
				'keyup #vc_elements_name_filter': 'filterElements',
				'search #vc_elements_name_filter': 'filterElements'
			},
			render: function ( model, prepend ) {
				if ( ! _.isUndefined( vc.ShortcodesBuilder ) ) {
					this.builder = new vc.ShortcodesBuilder();
				}

				if ( this.$el.is( ':hidden' ) ) {
					vc.closeActivePanel();
				}
				vc.active_panel = this;
				this.prepend = _.isBoolean( prepend ) ? prepend : false;
				this.place_after_id = _.isString( prepend ) ? prepend : false;
				this.model = _.isObject( model ) ? model : false;
				this.$content = this.$el.find( '[data-vc-ui-element="panel-add-element-list"]' );
				this.$buttons = $( '[data-vc-ui-element="add-element-button"]', this.$content );

				this.buildFiltering();

				this.$el.find( '[data-vc-ui-element="panel-tab-control"]' ).eq( 0 ).click();

				this.show();

				// must be after show()
				this.$el.find( '[data-vc-ui-element="panel-tabs-controls"]' ).vcTabsLine( 'moveTabs' );

				if ( ! vc.is_mobile ) {
					$( this.searchSelector ).focus();
				}

				return vc.AddElementUIPanelBackendEditor.__super__.render.call( this );
			},
			buildFiltering: function () {
				var itemSelector, tag, notIn, asParent, parentSelector;

				itemSelector = '[data-vc-ui-element="add-element-button"]';
				tag = this.model ? this.model.get( 'shortcode' ) : 'vc_column';
				notIn = this._getNotIn( tag );
				$( this.searchSelector ).val( '' );
				this.$content.addClass( 'vc_filter-all' );
				this.$content.attr( 'data-vc-ui-filter', '*' );

				asParent = tag && ! _.isUndefined( vc.getMapped( tag ).as_parent ) ? vc.getMapped( tag ).as_parent : false;

				if ( _.isObject( asParent ) ) {
					parentSelector = [];
					if ( _.isString( asParent.only ) ) {
						parentSelector.push( _.reduce( asParent.only.replace( /\s/, '' ).split( ',' ),
							function ( memo, val ) {
								return memo + ( _.isEmpty( memo ) ? '' : ',') + '[data-element="' + val.trim() + '"]';
							},
							'' ) );
					}
					if ( _.isString( asParent.except ) ) {
						parentSelector.push( _.reduce( asParent.except.replace( /\s/, '' ).split( ',' ),
							function ( memo, val ) {
								return memo + ':not([data-element="' + val.trim() + '"])';
							},
							'' ) );
					}
					itemSelector += parentSelector.join( ',' );
				} else if ( notIn ) {
					itemSelector = notIn;
				}

				if ( false !== tag && ! _.isUndefined( vc.getMapped( tag ).allowed_container_element ) ) {
					if ( false === vc.getMapped( tag ).allowed_container_element ) {
						itemSelector += ':not([data-is-container=true])';
					} else if ( _.isString( vc.getMapped( tag ).allowed_container_element ) ) {
						itemSelector += ':not([data-is-container=true]), [data-element=' + vc.getMapped( tag ).allowed_container_element + ']';
					}
				}

				this.$buttons.removeClass( 'vc_visible' ).addClass( 'vc_inappropriate' );
				$( itemSelector, this.$content ).removeClass( 'vc_inappropriate' ).addClass( 'vc_visible' );

				this.hideEmptyFilters();
			},
			hideEmptyFilters: function () {
				var _this = this;

				this.$el.find( '[data-vc-ui-element="panel-add-element-tab"].vc_active' ).removeClass( 'vc_active' );
				this.$el.find( '[data-vc-ui-element="panel-add-element-tab"]:first' ).addClass( 'vc_active' );
				this.$el.find( '[data-filter]' ).each( function () {
					if ( ! $( $( this ).data( 'filter' ) + '.vc_visible:not(.vc_inappropriate)',
							_this.$content ).length ) {
						$( this ).parent().hide();
					} else {
						$( this ).parent().show();
					}
				} );
			},
			_getNotIn: _.memoize( function ( tag ) {
				var selector;

				selector = _.reduce( vc.map, function ( memo, shortcode ) {
					var separator;

					separator = _.isEmpty( memo ) ? '' : ',';

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
				if ( _.isObject( e ) ) {
					e.preventDefault() && e.stopPropagation();
				} else {
					e = window.event;
				}

				var filterValue, $visibleElements,
					$control = $( e.currentTarget ),
					filter = '[data-vc-ui-element="add-element-button"]',
					nameFilter = $( this.searchSelector ).val();

				this.$content.removeClass( 'vc_filter-all' );

				$( '[data-vc-ui-element="panel-add-element-tab"].vc_active' ).removeClass( 'vc_active' );

				if ( $control.is( '[data-filter]' ) ) {
					$control.parent().addClass( 'vc_active' );

					filterValue = $control.data( 'filter' );
					filter += filterValue;

					if ( '*' === filterValue ) {
						this.$content.addClass( 'vc_filter-all' );
					} else {
						this.$content.removeClass( 'vc_filter-all' );
					}

					this.$content.attr( 'data-vc-ui-filter', filterValue.replace( '.js-category-', '' ) );

					$( this.searchSelector ).val( '' );
				} else if ( nameFilter.length ) {
					filter += ":containsi('" + nameFilter + "'):not('.vc_element-deprecated')";

					this.$content.attr( 'data-vc-ui-filter', 'name:' + nameFilter );
				} else if ( ! nameFilter.length ) {
					$( '[data-vc-ui-element="panel-tab-control"][data-filter="*"]' ).parent().addClass( 'vc_active' );

					this.$content
						.attr( 'data-vc-ui-filter', '*' )
						.addClass( 'vc_filter-all' );
				}

				$( '.vc_visible', this.$content ).removeClass( 'vc_visible' );
				$( filter, this.$content ).addClass( 'vc_visible' );

				// if user has pressed enter into search box and only one item is visible, simulate click
				if ( nameFilter.length ) {
					if ( 13 === (e.keyCode || e.which) ) {
						$visibleElements = $( '.vc_visible:not(.vc_inappropriate)', this.$content );
						if ( 1 === $visibleElements.length ) {
							$visibleElements.find( '[data-vc-clickable]' ).click();
						}
					}
				}
			},
			createElement: function ( e ) {
				_.isObject( e ) && e.preventDefault();

				var model, column, row, showSettings, shortcode,
					tag = $( e.currentTarget ).data( 'tag' );

				if ( false === this.model ) {
					vc.storage.lock();
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
						vc.storage.lock();
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

				this.model = model;

				showSettings = ! (_.isBoolean( vc.getMapped( tag ).show_settings_on_create ) && false === vc.getMapped( tag ).show_settings_on_create );

				// extend default params with settings presets if there are any
				shortcode = this.model.get( 'shortcode' );
				if ( 'undefined' !== typeof(window.vc_settings_presets[ shortcode ]) ) {
					this.model.save({params:  _.extend({},
						this.model.attributes.params,
						window.vc_settings_presets[ shortcode ]
					)});
				}

				this.hide();

				if ( showSettings ) {
					this.showEditForm();
				}

				this.addCustomCssStyleTag();
			},
			getFirstPositionIndex: function () {
				vc.element_start_index -= 1;

				return vc.element_start_index;
			},
			show: function () {
				this.$el.addClass( 'vc_active' );
				this.trigger( 'show' );
			},
			hide: function () {
				this.$el.removeClass( 'vc_active' );
				vc.active_panel = false;
				this.trigger( 'hide' );
			},
			showEditForm: function () {
				vc.edit_element_block_view.render( this.model );
			},
			addCustomCssStyleTag: function () {
				var custom_css = this.model.attributes.params.css;
				if ( custom_css && vc.frame_window ) {
					vc.frame_window.vc_iframe.setCustomShortcodeCss( custom_css );
				}
			}
		} );

	vc.AddElementUIPanelFrontendEditor = vc.AddElementUIPanelBackendEditor
		.vcExtendUI( vc.HelperPanelViewHeaderFooter )
		.extend( {
			events: {
				'click [data-vc-ui-element="button-close"]': 'hide',
				'click [data-vc-ui-element="panel-tab-control"]': 'filterElements',
				'click .vc_shortcode-link': 'createElement',
				'keyup #vc_elements_name_filter': 'filterElements'
			},
			createElement: function ( e ) {
				_.isObject( e ) && e.preventDefault();

				var showSettings, params, shortcodeFirst, newData, i, shortcode,
					$control = $( e.currentTarget ),
					tag = $control.data( 'tag' );

				if ( false === this.model && 'vc_row' !== tag ) {
					this.builder
						.create( { shortcode: 'vc_row' } )
						.create( {
							shortcode: 'vc_column',
							parent_id: this.builder.lastID(),
							params: { width: '1/1' }
						} );
					this.model = this.builder.last();
				} else if ( false !== this.model && 'vc_row' === tag ) {
					tag += '_inner';
				}

				params = {
					shortcode: tag,
					parent_id: (this.model ? this.model.get( 'id' ) : false)
				};

				if ( this.prepend ) {
					params.order = 0;
					shortcodeFirst = vc.shortcodes.findWhere( { parent_id: this.model.get( 'id' ) } );

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

				if ( _.isString( vc.getMapped( tag ).default_content ) && vc.getMapped( tag ).default_content.length ) {
					newData = this.builder.parse( {},
						vc.getMapped( tag ).default_content,
						this.builder.last().toJSON() );
					_.each( newData, function ( object ) {
						object.default_content = true;
						this.builder.create( object );
					}, this );
				}

				this.model = this.builder.last();

				showSettings = ! (_.isBoolean( vc.getMapped( tag ).show_settings_on_create ) && false === vc.getMapped( tag ).show_settings_on_create );

				this.hide();

				if ( showSettings ) {
					this.showEditForm();
				}

				this.builder.render();

				this.addCustomCssStyleTag();
			}
		} );

})( window.jQuery );