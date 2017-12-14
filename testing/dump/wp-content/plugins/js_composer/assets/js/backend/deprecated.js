(function ( $ ) {
	if ( _.isUndefined( window.vc ) ) {
		window.vc = {};
	}

	function localHasClass( $el, className ) {
		return $el[ 0 ].classList.contains( className );
	}

	/**
	 * @deprecated 4.5
	 *
	 * @param $column
	 * @returns {*}
	 */
	window.vc_get_column_size = function ( $column ) {
		if ( localHasClass( $column, 'vc_col-sm-12' ) ) //full-width
		{
			return '1/1';
		} else if ( localHasClass( $column, 'vc_col-sm-11' ) ) //three-fourth
		{
			return '11/12';
		} else if ( localHasClass( $column, 'vc_col-sm-10' ) ) //three-fourth
		{
			return '4/6';
		} else if ( localHasClass( $column, 'vc_col-sm-9' ) ) //three-fourth
		{
			return '3/4';
		} else if ( localHasClass( $column, 'vc_col-sm-8' ) ) //three-fourth
		{
			return '5/6';
		} else if ( localHasClass( $column, 'vc_col-sm-8' ) ) //two-third
		{
			return '2/3';
		} else if ( localHasClass( $column, 'vc_col-sm-7' ) ) // 7/12
		{
			return '7/12';
		} else if ( localHasClass( $column, 'vc_col-sm-6' ) ) //one-half
		{
			return '1/2';
		} else if ( localHasClass( $column, 'vc_col-sm-5' ) ) //one-half
		{
			return '5/12';
		} else if ( localHasClass( $column, 'vc_col-sm-4' ) ) // one-third
		{
			return '1/3';
		} else if ( localHasClass( $column, 'vc_col-sm-3' ) ) // one-fourth
		{
			return '1/4';
		} else if ( localHasClass( $column, 'vc_col-sm-2' ) ) // one-fourth
		{
			return '1/6';
		} else if ( localHasClass( $column, 'vc_col-sm-1' ) ) // one-fourth
		{
			return '1/12';
		} else {
			return false;
		}
	};

	/**
	 * Post custom css
	 * @type {Number}
	 * @deprecated
	 */
	var PostCustomCssBlockView = vc.post_custom_css_block_view = Backbone.View.extend( {
		tagName: 'div',
		className: 'wpb_bootstrap_modals',
		template: _.template( $( '#wpb-post-custom-css-modal-template' ).html() || '<div></div>' ),
		events: {
			'click .wpb_save_edit_form': 'save'
		},
		initialize: function () {

		},
		render: function () {
			//remove previous modal!!!
			$( "div.wpb_bootstrap_modals" ).filter( function () {
				return "none" === $( this ).css( "display" )
			} ).remove();
			this.$field = $( '#wpb_custom_post_css_field' );
			$( 'body' ).append( this.$el.html( this.template() ) );
			$( '#wpb_csseditor' ).html( this.$field.val() );
			this.$editor = ace.edit( "wpb_csseditor" );
			var session = this.$editor.getSession();
			session.setMode( "ace/mode/css" );
			this.$editor.setValue( this.$field.val() );
			this.$editor.clearSelection();
			this.$editor.focus();
//Get the number of lines
			var count = session.getLength();
//Go to end of the last line
			this.$editor.gotoLine( count, session.getLine( count - 1 ).length );
		},
		setSize: function () {
			var height = $( window ).height() - 250; // @fix ACE editor
			var $css_editor = $( "#wpb_csseditor" );
			$css_editor.css( { 'height': height, 'minHeight': height } );
		},
		save: function () {
			this.setAlertOnDataChange();
			this.$field.val( this.$editor.getValue() );
			this.close();
		},
		show: function () {
			this.render();
			$( window ).bind( 'resize.vcPropertyPanel', this.setSize );
			this.setSize();
			this.$el.modal( 'show' );
		},
		close: function () {
			this.$el.modal( 'hide' );
		},
		/**
		 * Set alert if custom css data differs from saved data.
		 */
		setAlertOnDataChange: function () {
			if ( vc.saved_custom_css !== this.$editor.getValue() && window.tinymce ) {
				window.switchEditors.go( 'content', 'tmce' );
				window.tinymce.get( 'content' ).isNotDirty = false;
			}
		}
	} );

	/**
	 * Templates List
	 * @deprecated
	 */
	vc.element_start_index = 0;
	var TemplatesBlockView = vc.add_templates_block_view = Backbone.View.extend( {
		tagName: 'div',
		className: 'wpb_bootstrap_modals',
		template: _.template( $( '#wpb-add-templates-modal-template' ).html() || '<div></div>' ),
		events: {
			//'click [data-element]':'createElement',
			'click .close': 'close',
			'hidden': 'removeView'
		},
		initialize: function () {

		},
		render: function () {
			$( 'body' ).append( this.$el.html( this.template() ) );
			$( "#vc_tabs-templates" ).tabs();
			this.$name = $( '#vc_template-name' );

			return this;
		},
		removeView: function () {
			this.remove();
		},
		show: function ( container ) {
			this.container = container;
			this.render();
			this.$el.modal( 'show' );
		},
		close: function () {
			this.$el.modal( 'hide' );
		}
	} );
	/**
	 * Edit form
	 *
	 * @deprecated
	 * @type {*}
	 */
	var SettingsView = Backbone.View.extend( {
		tagName: 'div',
		className: 'wpb_bootstrap_modals',
		template: _.template( $( '#wpb-element-settings-modal-template' ).html() || '<div></div>' ),
		textarea_html_checksum: '',
		dependent_elements: {},
		mapped_params: {},
		events: {
			'click .wpb_save_edit_form': 'save',
			// 'click .close':'close',
			'hidden': 'remove',
			'hide': 'askSaveData',
			'shown': 'loadContent'
		},
		content: function () {
			return this.$content;
		},
		window: function () {
			return window;
		},
		initialize: function () {
			var tag = this.model.get( 'shortcode' ),
				params = _.isObject( vc.map[ tag ] ) && _.isArray( vc.map[ tag ].params ) ? vc.map[ tag ].params : [];
			_.bindAll( this, 'hookDependent' );
			this.dependent_elements = {};
			this.mapped_params = {};
			_.each( params, function ( param ) {
				this.mapped_params[ param.param_name ] = param;
			}, this );
		},
		render: function () {
			$( 'body' ).append( this.$el.html( this.template() ) );
			this.$content = this.$el.find( '.modal-body > div' );
			return this;
		},
		initDependency: function () {
			// setup dependencies
			_.each( this.mapped_params, function ( param ) {
				if ( _.isObject( param ) && _.isObject( param.dependency ) && _.isString( param.dependency.element ) ) {
					var $masters = $( '[name=' + param.dependency.element + '].wpb_vc_param_value', this.$content ),
						$slave = $( '[name= ' + param.param_name + '].wpb_vc_param_value', this.$content );
					_.each( $masters, function ( master ) {
						var $master = $( master ),
							rules = param.dependency;
						if ( ! _.isArray( this.dependent_elements[ $master.attr( 'name' ) ] ) ) {
							this.dependent_elements[ $master.attr( 'name' ) ] = [];
						}
						this.dependent_elements[ $master.attr( 'name' ) ].push( $slave );
						$master.bind( 'keyup change', this.hookDependent );
						this.hookDependent( { currentTarget: $master }, [ $slave ] );
						if ( _.isString( rules.callback ) ) {
							window[ rules.callback ].call( this );
						}
					}, this );
				}
			}, this );
		},
		hookDependent: function ( e, dependent_elements ) {
			var $master = $( e.currentTarget ),
				$master_container = $master.closest( '.vc_row-fluid' ),
				master_value,
				is_empty;
			dependent_elements = _.isArray( dependent_elements ) ? dependent_elements : this.dependent_elements[ $master.attr( 'name' ) ];
			master_value = $master.is( ':checkbox' ) ? _.map( this.$content.find( '[name=' + $( e.currentTarget ).attr( 'name' ) + '].wpb_vc_param_value:checked' ),
				function ( element ) {
					return $( element ).val();
				} )
				: $master.val();
			is_empty = $master.is( ':checkbox' ) ? ! this.$content.find( '[name=' + $master.attr( 'name' ) + '].wpb_vc_param_value:checked' ).length
				: ! master_value.length;
			if ( $master_container.hasClass( 'vc_dependent-hidden' ) ) {
				_.each( dependent_elements, function ( $element ) {
					$element.closest( '.vc_row-fluid' ).addClass( 'vc_dependent-hidden' );
				} );
			} else {
				_.each( dependent_elements, function ( $element ) {
					var param_name = $element.attr( 'name' ),
						rules = _.isObject( this.mapped_params[ param_name ] ) && _.isObject( this.mapped_params[ param_name ].dependency ) ? this.mapped_params[ param_name ].dependency : {},
						$param_block = $element.closest( '.vc_row-fluid' );
					if ( _.isBoolean( rules.not_empty ) && true === rules.not_empty && ! is_empty ) { // Check is not empty show dependent Element.
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else if ( _.isBoolean( rules.is_empty ) && true === rules.is_empty && is_empty ) {
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else if ( _.intersection( (_.isArray( rules.value ) ? rules.value : [ rules.value ]),
							(_.isArray( master_value ) ? master_value : [ master_value ]) ).length ) {
						$param_block.removeClass( 'vc_dependent-hidden' );
					} else {
						$param_block.addClass( 'vc_dependent-hidden' )
					}
					$element.trigger( 'change' );
				}, this );
			}
			return this;
		},
		loadContent: function () {
			$.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: {
					action: 'wpb_show_edit_form',
					element: this.model.get( 'shortcode' ),
					post_id: $( '#post_ID' ).val(),
					shortcode: store.createShortcodeString( this.model.toJSON() ), // TODO: do it on server-side,
					_vcnonce: window.vcAdminNonce
				},
				context: this
			} ).done( function ( data ) {
				this.$content.html( data );
				this.$el.find( 'h3' ).text( this.$content.find( '> [data-title]' ).data( 'title' ) );
				this.initDependency();
			} );
		},
		save: function ( e ) {
			if ( _.isObject( e ) ) {
				e.preventDefault();
			}
			var params = this.getParams();
			this.model.save( { params: params } );
			if ( 0 === parseInt( Backbone.VERSION ) ) {
				this.model.trigger( 'change:params', this.model );
			}
			this.data_saved = true;
			this.close();
			return this;
		},
		getParams: function () {
			var attributes_settings = this.mapped_params;
			this.params = jQuery.extend( true, {}, this.model.get( 'params' ) );
			_.each( attributes_settings, function ( param ) {
				this.params[ param.param_name ] = vc.atts.parse.call( this, param );
			}, this );
			_.each( vc.edit_form_callbacks, function ( callback ) {
				callback.call( this );
			}, this );
			return this.params;
		},
		getCurrentParams: function () {
			var attributes_settings = this.mapped_params,
				params = jQuery.extend( true, {}, this.model.get( 'params' ) );
			_.each( attributes_settings, function ( param ) {
				if ( _.isUndefined( params[ param.param_name ] ) ) {
					params[ param.param_name ] = '';
				}
				if ( "textarea_html" === param.type ) {
					params[ param.param_name ] = params[ param.param_name ].replace( /\n/g,
						'' );
				}
			}, this );
			return params;
		},
		show: function () {
			this.render();
			$( window ).bind( 'resize.ModalView', this.setSize );
			this.setSize();
			this.$el.modal( 'show' );
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
		},
		dataNotChanged: function () {
			var current_params = this.getCurrentParams(),
				new_params = this.getParams();
			return _.isEqual( current_params, new_params );
		},
		askSaveData: function () {
			if ( this.data_saved || this.dataNotChanged() || confirm( window.i18nLocale.if_close_data_lost ) ) {
				this._killEditor();
				this.data_saved = true;
				$( window ).unbind( 'resize.ModalView' );
				return true;
			}
			return false;
		},
		close: function () {
			if ( this.askSaveData() ) {
				this.$el.modal( 'hide' );
			}
		},
		setSize: function () {
			var height = $( window ).height() - 250;
			this.$el.find( '.modal-body' ).css( 'maxHeight', height );
		}
	} );

})( window.jQuery );