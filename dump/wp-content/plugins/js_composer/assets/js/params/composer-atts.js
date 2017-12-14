/* =========================================================
 * composer-atts.js v0.2.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer backbone/underscore shortcodes attributes
 * form field and parsing controls
 * ========================================================= */
if ( _.isUndefined( window.vc ) ) {
	var vc = {};
}
window.vc.filters = { templates: [] };
window.vc.addTemplateFilter = function ( callback ) {
	if ( _.isFunction( callback ) ) {
		this.filters.templates.push( callback );
	}
};

(function ( $ ) {
	var vc_activeMce;

	window.init_textarea_html = function ( $element ) {
		var $wp_link, textfield_id, $form_line, $content_holder;
		$wp_link = $( '#wp-link' );
		if ( $wp_link.parent().hasClass( 'wp-dialog' ) ) {
			$wp_link.wpdialog( 'destroy' );
		}
		textfield_id = $element.attr( "id" );
		$form_line = $element.closest( '.edit_form_line' );
		$content_holder = $form_line.find( '.vc_textarea_html_content' );
		try {
			// Init Quicktag
			if ( _.isUndefined( tinyMCEPreInit.qtInit[ textfield_id ] ) ) {
				window.tinyMCEPreInit.qtInit[ textfield_id ] = _.extend( {},
					window.tinyMCEPreInit.qtInit[ window.wpActiveEditor ],
					{ id: textfield_id } );
			}
			// Init tinymce
			if ( window.tinyMCEPreInit && window.tinyMCEPreInit.mceInit[ window.wpActiveEditor ] ) {
				window.tinyMCEPreInit.mceInit[ textfield_id ] = _.extend( {},
					window.tinyMCEPreInit.mceInit[ window.wpActiveEditor ],
					{
						resize: 'vertical',
						height: 200,
						id: textfield_id,
						setup: function ( ed ) {
							if ( 'undefined' !== typeof(ed.on) ) {
								ed.on( 'init', function ( ed ) {
									ed.target.focus();
									window.wpActiveEditor = textfield_id;
								} );
							} else {
								ed.onInit.add( function ( ed ) {
									ed.focus();
									window.wpActiveEditor = textfield_id;
								} );
							}
						}
					} );
				window.tinyMCEPreInit.mceInit[ textfield_id ].plugins = window.tinyMCEPreInit.mceInit[ textfield_id ].plugins.replace( /,?wpfullscreen/,
					'' );
				window.tinyMCEPreInit.mceInit[ textfield_id ].wp_autoresize_on = false;
			}
			if ( vc.edit_element_block_view && vc.edit_element_block_view.currentModelParams ) {
				$element.val( vc.edit_element_block_view.currentModelParams[ $content_holder.attr( 'name' ) ] || '' );
			} else {
				$element.val( $content_holder.val() );
			}
			quicktags( window.tinyMCEPreInit.qtInit[ textfield_id ] );
			QTags._buttonsInit();
			if ( window.tinymce ) {
				window.switchEditors && window.switchEditors.go( textfield_id, 'tmce' );
				if ( "4" === tinymce.majorVersion ) {
					tinymce.execCommand( 'mceAddEditor', true, textfield_id );
				}
			}
			vc_activeMce = textfield_id;
			window.wpActiveEditor = textfield_id;
		} catch ( e ) {
			$element.data( 'vcTinyMceDisabled', true ).appendTo( $form_line );
			$( '#wp-' + textfield_id + '-wrap' ).remove();
			if ( console && console.error ) {
				console.error( 'VC: Tinymce error! Compatibility problem with other plugins.' );
				console.error( e );
			}
		}
	};

	// TODO: unsecure. Think about it
	Color.prototype.toString = function () {
		if ( 1 > this._alpha ) {
			return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );
		}
		var hex = parseInt( this._color, 10 ).toString( 16 );
		if ( this.error ) {
			return '';
		}
		// maybe left pad it
		if ( 6 > hex.length ) {
			for ( var i = 6 - hex.length - 1;
				  0 <= i;
				  i -- ) {
				hex = '0' + hex;
			}
		}
		return '#' + hex;
	};

	var template_options = {
		evaluate: /<#([\s\S]+?)#>/g,
		interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
		escape: /\{\{([^\}]+?)\}\}(?!\})/g
	};

	/**
	 * Loop param for shortcode with magic query posts constructor.
	 */
	vc.loop_partial = function ( template_name, key, loop, settings ) {
		var data = _.isObject( loop ) && ! _.isUndefined( loop[ key ] ) ? loop[ key ] : '';
		return _.template( $( '#_vcl-' + template_name ).html(), {
			name: key,
			data: data,
			settings: settings
		}, template_options );
	};
	vc.loop_field_not_hidden = function ( key, loop ) {
		return ! (_.isObject( loop[ key ] ) && _.isBoolean( loop[ key ].hidden ) && true === loop[ key ].hidden);
	};
	vc.is_locked = function ( data ) {
		return _.isObject( data ) && _.isBoolean( data.locked ) && true === data.locked;
	};

	function Suggester( element, options ) {
		this.el = element;
		this.$el = $( this.el );
		this.$el_wrap = '';
		this.$block = '';
		this.suggester = '';
		this.selected_items = [];
		this.options = _.isObject( options ) ? options : {};
		_.defaults( this.options, {
			css_class: 'vc_suggester',
			limit: false,
			source: {},
			predefined: [],
			locked: false,
			select_callback: function ( label, data ) {
			},
			remove_callback: function ( label, data ) {
			},
			update_callback: function ( label, data ) {
			},
			check_locked_callback: function ( el, data ) {
				return false;
			}
		} );
		this.init();
	}

	Suggester.prototype = {
		constructor: Suggester,
		init: function () {
			_.bindAll( this, 'buildSource', 'itemSelected', 'labelClick', 'setFocus', 'resize' );
			this.$el.wrap( '<ul class="' + this.options.css_class + '"><li class="input"/></ul>' );

			this.$el_wrap = this.$el.parent();
			this.$block = this.$el_wrap.closest( 'ul' ).append( $( '<li class="clear"/>' ) );
			this.$el.focus( this.resize ).blur( function () {
				$( this ).parent().width( 170 );
				$( this ).val( '' );
			} );
			this.$block.click( this.setFocus );
			this.suggester = this.$el.data( 'suggest' ); // Remove form here
			this.$el.autocomplete( {
				source: this.buildSource,
				select: this.itemSelected,
				minLength: 2,
				focus: function ( event, ui ) {
					return false;
				}
			} ).data( "ui-autocomplete" )._renderItem = function ( ul, item ) {
				return $( '<li data-value="' + item.value + '">' )
					.append( "<a>" + item.name + "</a>" )
					.appendTo( ul );
			};
			this.$el.autocomplete( "widget" ).addClass( 'vc_ui-front' );
			if ( _.isArray( this.options.predefined ) ) {
				_.each( this.options.predefined, function ( item ) {
					this.create( item );
				}, this );
			}
		},
		resize: function () {
			var position = this.$el_wrap.position(),
				block_position = this.$block.position();
			this.$el_wrap.width( parseFloat( this.$block.width() ) - (parseFloat( position.left ) - parseFloat( block_position.left ) + 4) );
		},
		setFocus: function ( e ) {
			e.preventDefault();
			var $target = $( e.target );
			if ( $target.hasClass( this.options.css_class ) ) {
				this.$el.trigger( 'focus' );
			}
		},
		itemSelected: function ( event, ui ) {
			this.$el.blur();
			this.create( ui.item );
			this.$el.focus();
			return false;
		},
		create: function ( item ) {
			var index = (this.selected_items.push( item ) - 1),
				remove = true === this.options.check_locked_callback( this.$el,
					item ) ? '' : ' <a class="remove">&times;</a>',
				$label,
				exclude_css;
			if ( _.isUndefined( this.selected_items[ index ].action ) ) {
				this.selected_items[ index ].action = '+';
			}
			exclude_css = '-' === this.selected_items[ index ].action ? ' exclude' : ' include';
			$label = $( '<li class="vc_suggest-label' + exclude_css + '" data-index="' + index + '" data-value="' + item.value + '"><span class="label">' + item.name + '</span>' + remove + '</li>' );
			$label.insertBefore( this.$el_wrap );
			if ( ! _.isEmpty( remove ) ) {
				$label.click( this.labelClick );
			}
			this.options.select_callback( $label, this.selected_items );
		},
		labelClick: function ( e ) {
			e.preventDefault();
			var $label = $( e.currentTarget ),
				index = parseInt( $label.data( 'index' ), 10 ),
				$target = $( e.target );
			if ( $target.is( '.remove' ) ) {
				delete this.selected_items[ index ];
				this.options.remove_callback( $label, this.selected_items );
				$label.remove();
				return false;
			}
			this.selected_items[ index ].action = '+' === this.selected_items[ index ].action ? '-' : '+';
			if ( '+' === this.selected_items[ index ].action ) {
				$label.removeClass( 'exclude' ).addClass( 'include' );
			} else {
				$label.removeClass( 'include' ).addClass( 'exclude' );
			}
			this.options.update_callback( $label, this.selected_items );
		},
		buildSource: function ( request, response ) {
			var exclude = _.map( this.selected_items, function ( item ) {
				return item.value;
			} ).join( ',' );
			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: {
					action: 'wpb_get_loop_suggestion',
					field: this.suggester,
					exclude: exclude,
					query: request.term,
					_vcnonce: window.vcAdminNonce
				}
			} ).done( function ( data ) {
				response( data );
			} );
		}
	};
	$.fn.suggester = function ( option ) {
		return this.each( function () {
			var $this = $( this ),
				data = $this.data( 'suggester' );
			if ( ! data ) {
				$this.data( 'suggester', (data = new Suggester( this, option )) );
			}
			if ( 'string' === typeof(option) ) {
				data[ option ]();
			}
		} );
	};

	var VcLoopEditorView = Backbone.View.extend( {
		className: 'loop_params_holder',
		events: {
			'click input, select': 'save',
			'change input, select': 'save',
			'change :checkbox[data-input]': 'updateCheckbox'
		},
		query_options: {},
		return_array: {},
		controller: '',
		initialize: function () {
			_.bindAll( this, 'save', 'updateSuggestion', 'suggestionLocked' );
		},
		render: function ( controller ) {
			var template = _.template( $( '#vcl-loop-frame' ).html(),
				this.model,
				_.extend( {}, template_options, { variable: 'loop' } ) );
			this.controller = controller;
			this.$el.html( template );
			this.controller.$el.append( this.$el );
			_.each( $( '[data-suggest]' ), function ( object ) {
				var $field = $( object ),
					current_value = window.decodeURIComponent( $( '[data-suggest-prefill=' + $field.data( 'suggest' ) + ']' ).val() );
				$field.suggester( {
					predefined: $.parseJSON( current_value ),
					select_callback: this.updateSuggestion,
					update_callback: this.updateSuggestion,
					remove_callback: this.updateSuggestion,
					check_locked_callback: this.suggestionLocked
				} );
			}, this );
			return this;
		},
		show: function () {
			this.$el.slideDown();
		},
		save: function ( e ) {
			this.return_array = {};
			_.each( this.model, function ( value, key ) {
				var value = this.getValue( key, value );
				if ( _.isString( value ) && ! _.isEmpty( value ) ) {
					this.return_array[ key ] = value;
				}
			}, this );
			this.controller.setInputValue( this.return_array );
		},
		getValue: function ( key ) {
			var value = $( '[name=' + key + ']', this.$el ).val();
			return value;
		},
		hide: function () {
			this.$el.slideUp();
		},
		toggle: function () {
			if ( ! this.$el.is( ':animated' ) ) {
				this.$el.slideToggle();
			}
		},
		updateCheckbox: function ( e ) {
			var $checkbox = $( e.currentTarget ),
				input_name = $checkbox.data( 'input' ),
				$input = $( '[data-name=' + input_name + ']', this.$el ),
				value = [];
			$( '[data-input=' + input_name + ']:checked' ).each( function () {
				value.push( $( this ).val() );
			} );
			$input.val( value );
			this.save();
		},
		updateSuggestion: function ( $elem, data ) {
			var value,
				$suggestion_block = $elem.closest( '[data-block=suggestion]' );
			value = _.reduce( data, function ( memo, label ) {
				if ( ! _.isEmpty( label ) ) {
					return memo + (_.isEmpty( memo ) ? '' : ',') + ('-' === label.action ? '-' : '') + label.value;
				}
			}, '' );
			$suggestion_block.find( '[data-suggest-value]' ).val( value ).trigger( 'change' );
		},
		suggestionLocked: function ( $elem, data ) {
			var value = data.value,
				field = $elem.closest( '[data-block=suggestion]' ).find( '[data-suggest-value]' ).data( 'suggest-value' );

			return this.controller.settings[ field ]
				&& _.isBoolean( this.controller.settings[ field ].locked )
				&& true == this.controller.settings[ field ].locked
				&& _.isString( this.controller.settings[ field ].value )
				&& 0 <= _.indexOf( this.controller.settings[ field ].value.replace( '-', '' ).split( /\,/ ),
					'' + value );
		}
	} );
	var VcLoop = Backbone.View.extend( {
		events: {
			'click .vc_loop-build': 'showEditor'
		},
		initialize: function () {
			_.bindAll( this, 'createEditor' );
			this.$input = $( '.wpb_vc_param_value', this.$el );
			this.$button = this.$el.find( '.vc_loop-build' );
			this.data = this.$input.val();
			this.settings = $.parseJSON( window.decodeURIComponent( this.$button.data( 'settings' ) ) );
		},
		render: function () {
			return this;
		},
		showEditor: function ( e ) {
			e.preventDefault();
			if ( _.isObject( this.loop_editor_view ) ) {
				this.loop_editor_view.toggle();
				return false;
			}
			$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: window.ajaxurl,
				data: {
					action: 'wpb_get_loop_settings',
					value: this.data,
					settings: this.settings,
					post_id: vc.post_id,
					_vcnonce: window.vcAdminNonce
				}
			} ).done( this.createEditor );
		},
		createEditor: function ( data ) {
			this.loop_editor_view = new VcLoopEditorView( { model: ! _.isEmpty( data ) ? data : {} } );
			this.loop_editor_view.render( this ).show();
		},
		setInputValue: function ( value ) {
			this.$input.val( _.map( value, function ( value, key ) {
				return key + ':' + value;
			} ).join( '|' ) );
		}
	} );
	var VcOptionsField = Backbone.View.extend( {
		events: {
			'click .vc_options-edit': 'showEditor',
			'click .vc_close-button': 'showEditor',
			'click input, select': 'save',
			'change input, select': 'save',
			'keyup input': 'save'
		},
		data: {},
		fields: {},
		initialize: function () {
			this.$button = this.$el.find( '.vc_options-edit' );
			this.$form = this.$el.find( '.vc_options-fields' );
			this.$input = this.$el.find( '.wpb_vc_param_value' );
			this.settings = this.$form.data( 'settings' );
			this.parseData();
			this.render();
		},
		render: function () {
			var html = '';
			_.each( this.settings, function ( field ) {
				if ( ! _.isUndefined( this.data[ field.name ] ) ) {
					field.value = this.data[ field.name ];
				} else if ( ! _.isUndefined( field.value ) ) {
					field.value = field.value.toString().split( ',' );
					this.data[ field.name ] = field.value;
				}
				this.fields[ field.name ] = field;
				var $field = $( '#vcl-options-field-' + field.type );
				if ( $field.is( 'script' ) ) {
					html += _.template(
						$field.html(),
						$.extend( {
							name: '',
							label: '',
							value: [],
							options: '',
							description: ''
						}, field ),
						_.extend( {}, template_options )
					);
				}
			}, this );
			this.$form.html( html + this.$form.html() );
			return this;
		},
		parseData: function () {
			_.each( this.$input.val().split( "|" ), function ( data ) {
				if ( data.match( /\:/ ) ) {
					var split = data.split( ':' ),
						name = split[ 0 ],
						value = split[ 1 ];
					this.data[ name ] = _.map( value.split( ',' ), function ( v ) {
						return window.decodeURIComponent( v );
					} );
				}
			}, this );
		},
		saveData: function () {
			var data_string = _.map( this.data, function ( value, key ) {
				return key + ':' + _.map( value, function ( v ) {
						return window.encodeURIComponent( v );
					} ).join( ',' );
			} ).join( '|' );
			this.$input.val( data_string );
		},
		showEditor: function () {
			this.$form.slideToggle();
		},
		save: function ( e ) {
			var $field = $( e.currentTarget );
			if ( $field.is( ':checkbox' ) ) {
				var value = [];
				this.$el.find( 'input[name=' + $field.attr( 'name' ) + ']' ).each( function () {
					if ( this.checked ) {
						value.push( $( this ).val() );
					}
				} );
				this.data[ $field.attr( 'name' ) ] = value;
			} else {
				this.data[ $field.attr( 'name' ) ] = [ $field.val() ];
			}
			this.saveData();
		}
	} );

	/**
	 * VC_link power code.
	 */
	function VcSortedList( element, settings ) {
		this.el = element;
		this.$el = $( this.el );
		this.$data_field = this.$el.find( '.wpb_vc_param_value' );
		this.$toolbar = this.$el.find( '.vc_sorted-list-toolbar' );
		this.$current_control = this.$el.find( '.vc_sorted-list-container' );
		_.defaults( this.options, {} );
		this.init();
	}

	VcSortedList.prototype = {
		constructor: VcSortedList,
		init: function () {
			_.bindAll( this, 'controlEvent', 'save' );
			this.$toolbar.on( 'change', 'input', this.controlEvent );
			var selected_data = this.$data_field.val().split( ',' );
			for ( var i in
				selected_data ) {
				var control_settings = selected_data[ i ].split( '|' ),
					$control = control_settings.length ? this.$toolbar.find( '[data-element=' + decodeURIComponent( control_settings[ 0 ] ) + ']' ) : false;
				if ( false !== $control && $control.is( 'input' ) ) {
					$control.prop( 'checked', true );
					this.createControl( {
						value: $control.val(),
						label: $control.parent().text(),
						sub: $control.data( 'subcontrol' ),
						sub_value: _.map( control_settings.slice( 1 ), function ( item ) {
							return window.decodeURIComponent( item )
						} )
					} );
				}
			}
			this.$current_control.sortable( {
				stop: this.save
			} ).on( 'change', 'select', this.save );
		},
		createControl: function ( data ) {
			var sub_control = '',
				selected_sub_value = _.isUndefined( data.sub_value ) ? [] : data.sub_value;
			if ( _.isArray( data.sub ) ) {
				_.each( data.sub, function ( sub, index ) {
					sub_control += ' <select>';
					_.each( sub, function ( item ) {
						sub_control += '<option value="' + item[ 0 ] + '"' + (_.isString( selected_sub_value[ index ] ) && selected_sub_value[ index ] === item[ 0 ] ? ' selected="true"' : '') + '>' + item[ 1 ] + '</option>';
					} );
					sub_control += '</select>';
				}, this );

			}
			this.$current_control.append( '<li class="vc_control-' + data.value + '" data-name="' + data.value + '">' + data.label + sub_control + '</li>' );

		},
		controlEvent: function ( e ) {
			var $control = $( e.currentTarget );
			if ( $control[0].checked ) {
				this.createControl( {
					value: $control.val(),
					label: $control.parent().text(),
					sub: $control.data( 'subcontrol' )
				} );

			} else {
				this.$current_control.find( '.vc_control-' + $control.val() ).remove();
			}
			this.save();
		},
		save: function () {
			var value = _.map( this.$current_control.find( '[data-name]' ), function ( element ) {
				var return_string = encodeURIComponent( $( element ).data( 'name' ) );
				$( element ).find( 'select' ).each( function () {
					var $sub_control = $( this );
					if ( $sub_control.is( 'select' ) && '' !== $sub_control.val() ) {
						return_string += '|' + encodeURIComponent( $sub_control.val() );
					}
				} );
				return return_string;
			} ).join( ',' );
			this.$data_field.val( value );
		}
	};
	$.fn.VcSortedList = function ( option ) {
		return this.each( function () {
			var $this = $( this ),
				data = $this.data( 'vc_sorted_list' ),
				options = _.isObject( option ) ? option : {};
			if ( ! data ) {
				$this.data( 'vc_sorted_list', (data = new VcSortedList( this, option )) );
			}
			if ( 'string' === typeof(option) ) {
				data[ option ]();
			}
		} );
	};

	/**
	 * Google fonts element methods
	 */
	var GoogleFonts = Backbone.View.extend( {
		preview_el: ".vc_google_fonts_form_field-preview-container > span",
		font_family_dropdown_el: ".vc_google_fonts_form_field-font_family-container > select",
		font_style_dropdown_el: ".vc_google_fonts_form_field-font_style-container > select",
		font_style_dropdown_el_container: ".vc_google_fonts_form_field-font_style-container",
		status_el: ".vc_google_fonts_form_field-status-container > span",
		events: {
			'change .vc_google_fonts_form_field-font_family-container > select': 'fontFamilyDropdownChange',
			'change .vc_google_fonts_form_field-font_style-container > select': 'fontStyleDropdownChange'
		},
		initialize: function ( attr ) {
			_.bindAll( this, 'previewElementInactive', 'previewElementActive', 'previewElementLoading' );
			this.$preview_el = $( this.preview_el, this.$el );
			this.$font_family_dropdown_el = $( this.font_family_dropdown_el, this.$el );
			this.$font_style_dropdown_el = $( this.font_style_dropdown_el, this.$el );
			this.$font_style_dropdown_el_container = $( this.font_style_dropdown_el_container, this.$el );
			this.$status_el = $( this.status_el, this.$el );
			this.fontFamilyDropdownRender();
		},
		render: function () {
			return this;
		},
		previewElementRender: function () {
			this.$preview_el.css( {
				"font-family": this.font_family,
				"font-style": this.font_style,
				"font-weight": this.font_weight
			} );
			return this;
		},
		previewElementInactive: function () {
			this.$status_el.text( window.i18nLocale.gfonts_loading_google_font_failed || "Loading google font failed." ).css( 'color',
				'#FF0000' );
		},
		previewElementActive: function () {
			this.$preview_el.text( "Grumpy wizards make toxic brew for the evil Queen and Jack." ).css( 'color',
				'inherit' );
			this.fontStyleDropdownRender();
		},
		previewElementLoading: function () {
			this.$preview_el.text( window.i18nLocale.gfonts_loading_google_font || "Loading Font..." );
		},
		fontFamilyDropdownRender: function () {
			this.fontFamilyDropdownChange();
			return this;
		},
		fontFamilyDropdownChange: function () {
			var $font_family_selected = this.$font_family_dropdown_el.find( ':selected' );
			this.font_family_url = $font_family_selected.val();
			this.font_family = $font_family_selected.attr( 'data[font_family]' );
			this.font_types = $font_family_selected.attr( 'data[font_types]' );
			this.$font_style_dropdown_el_container.parent().hide();

			if ( this.font_family_url && 0 < this.font_family_url.length ) {
				WebFont.load( {
					google: {
						families: [ this.font_family_url ]
					},
					inactive: this.previewElementInactive,
					active: this.previewElementActive,
					loading: this.previewElementLoading
				} );
			}
			return this;
		},
		fontStyleDropdownRender: function () {
			var str = this.font_types;
			var str_arr = str.split( ',' );
			var oel = '';
			var default_f_style = this.$font_family_dropdown_el.attr( 'default[font_style]' );
			for ( var str_inner in
				str_arr ) {
				var str_arr_inner = str_arr[ str_inner ].split( ':' );
				var sel = "";
				if ( _.isString( default_f_style ) && 0 < default_f_style.length && str_arr[ str_inner ] == default_f_style ) {
					sel = 'selected';
				}
				oel = oel + '<option ' + sel + ' value="' + str_arr[ str_inner ] + '" data[font_weight]="' + str_arr_inner[ 1 ] + '" data[font_style]="' + str_arr_inner[ 2 ] + '" class="' + str_arr_inner[ 2 ] + '_' + str_arr_inner[ 1 ] + '" >' + str_arr_inner[ 0 ] + '</option>';

			}
			this.$font_style_dropdown_el.html( oel );
			this.$font_style_dropdown_el_container.parent().show();
			this.fontStyleDropdownChange();
			return this;
		},
		fontStyleDropdownChange: function () {
			var $font_style_selected = this.$font_style_dropdown_el.find( ':selected' );
			this.font_weight = $font_style_selected.attr( 'data[font_weight]' );
			this.font_style = $font_style_selected.attr( 'data[font_style]' );
			this.previewElementRender();
			return this;
		}
	} );

	/**
	 * Auto Complete PARAMETER
	 */
	var VC_AutoComplete = Backbone.View.extend( {
		min_length: 2,
		delay: 500,
		auto_focus: true,
		ajax_url: window.ajaxurl,
		source_data: function () {
			return {};
		},
		replace_values_on_select: false,
		initialize: function ( params ) {
			_.bindAll( this,
				'sortableChange',
				'resize',
				'labelRemoveHook',
				'updateItems',
				'sortableCreate',
				'sortableUpdate',
				'source',
				'select',
				'labelRemoveClick',
				'createBox',
				'focus',
				'response',
				'change',
				'close',
				'open',
				'create',
				'search',
				'_renderItem',
				'_renderMenu',
				'_renderItemData',
				'_resizeMenu' );
			params = $.extend( {
				min_length: this.min_length,
				delay: this.delay,
				auto_focus: this.auto_focus,
				replace_values_on_select: this.replace_values_on_select
			}, params );
			this.options = params;
			this.param_name = this.options.param_name;
			this.$el = this.options.$el;
			this.$el_wrap = this.$el.parent();
			this.$sortable_wrapper = this.$el_wrap.parent();
			this.$input_param = this.options.$param_input;
			this.selected_items = [];
			this.isMultiple = false;

			this.render();
		},
		resize: function () {
			var position = this.$el_wrap.position(),
				block_position = this.$block.position();
			var widget = this.$el.autocomplete( "widget" );
			widget.width( parseFloat( this.$block.width() ) - (parseFloat( position.left ) - parseFloat( block_position.left ) + 4 ) + 11 );
		},
		enableMultiple: function () {
			this.isMultiple = true;
			this.$el.show();
			this.$el.focus();
		},
		enableSortable: function () {
			this.sortable = this.$sortable_wrapper.sortable( {
				items: ".vc_data",
				axis: 'y',
				change: this.sortableChange,
				create: this.sortableCreate,
				update: this.sortableUpdate
			} );

		},
		updateItems: function () {
			if ( this.selected_items.length ) {
				this.$input_param.val( this.getSelectedItems().join( ", " ) );
			} else {
				this.$input_param.val( '' );
			}
		},
		sortableChange: function ( event, ui ) {
		},
		itemsCreate: function () {
			var sel_items = [];
			this.$block.find( '.vc_data' ).each( function ( key, item ) {
				sel_items.push( { label: item.dataset.label, value: item.dataset.value } );
			} );
			this.selected_items = sel_items;
		},
		sortableCreate: function ( event, ui ) {
		},
		sortableUpdate: function ( event, ui ) {
			var elems = this.$sortable_wrapper.sortable( 'toArray', { attribute: 'data-index' } );
			var items = [];
			_.each( elems, function ( index ) {
				items.push( this.selected_items[ index ] );
			}, this );
			var index = 0;
			$( 'li.vc_data', this.$sortable_wrapper ).each( function () {
				$( this ).attr( 'data-index', index ++ );
			} );
			this.selected_items = items;
			this.updateItems();
		},
		getWidget: function () {
			return this.$el.autocomplete( 'widget' );
		},
		render: function () {
			this.$el.focus( this.resize );
			this.data = this.$el.autocomplete( {
				source: this.source,
				minLength: this.options.min_length,
				delay: this.options.delay,
				autoFocus: this.options.auto_focus,
				select: this.select,
				focus: this.focus,
				response: this.response,
				change: this.change,
				close: this.close,
				open: this.open,
				create: this.create,
				search: this.search
			} );
			this.data.data( "ui-autocomplete" )._renderItem = this._renderItem;
			this.data.data( "ui-autocomplete" )._renderMenu = this._renderMenu;
			this.data.data( "ui-autocomplete" )._resizeMenu = this._resizeMenu;
			if ( 0 < this.$input_param.val().length ) {
				if ( ! this.isMultiple ) {
					this.$el.hide();
				} else {
					this.$el.focus();
				}
				var that = this;
				$( '.vc_autocomplete-label.vc_data', this.$sortable_wrapper ).each( function () {
					that.labelRemoveHook( $( this ) );
				} );

			}
			this.getWidget().addClass( 'vc_ui-front' ).addClass( 'vc_ui-auotocomplete' );
			this.$block = this.$el_wrap.closest( 'ul' ).append( $( '<li class="clear"/>' ) );
			this.itemsCreate();
			return this;
		},
		close: function ( event, ui ) {
			if ( this.selected && this.options.no_hide ) {
				this.getWidget().show();
				this.selected ++;
				if ( 2 < this.selected ) {
					this.selected = undefined;
				}
			}
		},
		open: function ( event, ui ) {
			var widget = this.getWidget().menu();
			var widget_position = widget.position();
			widget.css( 'left', widget_position.left - 6 );
			widget.css( 'top', widget_position.top + 2 );
		},
		focus: function ( event, ui ) {
			if ( ! this.options.replace_values_on_select ) {
				event.preventDefault();
				return false;
			}
		},
		create: function ( event, ui ) {
		},
		change: function ( event, ui ) {
		},
		response: function ( event, ui ) {
		},
		search: function ( event, ui ) {
		},
		select: function ( event, ui ) {
			this.selected = 1;
			if ( ui.item ) {
				if ( this.options.unique_values ) {
					var $li_el = this.getWidget().data( 'uiMenu' ).active;
					if ( this.options.groups ) {
						var $prev_el = $li_el.prev();
						var $next_el = $li_el.next();
						if ( $prev_el.hasClass( 'vc_autocomplete-group' ) && ! $next_el.hasClass( 'vc_autocomplete-item' ) ) {
							$prev_el.remove();
						}
					}
					$li_el.remove();

					var that = this;
					if ( ! $( 'li.ui-menu-item', this.getWidget() ).length ) {
						that.selected = undefined;
					}
				}
				this.createBox( ui.item );
				if ( ! this.isMultiple ) {
					this.$el.hide();
				} else {
					this.$el.focus();
				}
			}
			return false;
		},
		createBox: function ( item ) {
			var index = (this.selected_items.push( item ) - 1),
				remove = '<a class="vc_autocomplete-remove">&times;</a>',
				$label;
			this.updateItems();
			$label = $( '<li class="vc_autocomplete-label vc_data" data-index="' + index + '" data-value="' + item.value + '" data-label="' + item.label + '"><span class="vc_autocomplete-label"><a>' + item.label + '</a></span>' + remove + '</li>' );
			$label.insertBefore( this.$el_wrap );
			this.labelRemoveHook( $label );
		},
		labelRemoveHook: function ( $label ) {
			this.$el.blur();
			this.$el.val( '' );
			$label.click( this.labelRemoveClick );
		},
		labelRemoveClick: function ( e, ui ) {
			e.preventDefault();
			var $label = $( e.currentTarget ),
				index = parseInt( $label.data( 'index' ), 10 ),
				$target = $( e.target );
			if ( $target.is( '.vc_autocomplete-remove' ) ) {
				delete this.selected_items[ index ];
				$label.remove();
				this.updateItems();
				this.$el.show();
				return false;
			}
		},
		getSelectedItems: function () {
			if ( this.selected_items.length ) {
				var results = [];
				_.each( this.selected_items, function ( item ) {
					results.push( item[ 'value' ] );
				} );
				return results;
			}
			return false;
		},
		_renderMenu: function ( ul, items ) {
			var that = this;
			var group = null;
			if ( this.options.groups ) {
				items.sort( function ( a, b ) {
					return a.group > b.group;
				} );
			}
			$.each( items, function ( index, item ) {
				if ( that.options.groups ) {
					if ( item.group != group ) {
						group = item.group;
						ul.append( "<li class='ui-autocomplete-group vc_autocomplete-group' aria-label='" + group + "'>" + group + "</li>" );
					}
				}
				that._renderItemData( ul, item );
			} );
		},
		_renderItem: function ( ul, item ) {
			return $( '<li data-value="' + item.value + '" class="vc_autocomplete-item">' )
				.append( '<a>' + item.label + '</a>' )
				.appendTo( ul );
		},
		_renderItemData: function ( ul, item ) {
			return this._renderItem( ul, item ).data( "ui-autocomplete-item", item );
		},
		_resizeMenu: function () {
		},
		/**
		 * Used to remove all data in the list.
		 */
		clearValue: function () {
			this.selected_items = [];
			this.updateItems();
			$( '.vc_autocomplete-label.vc_data', this.$sortable_wrapper ).remove();
		},
		source: function ( request, response ) {
			var that = this;
			if ( this.options.values && 0 < this.options.values.length ) {
				if ( this.options.unique_values ) {
					response( $.ui.autocomplete.filter(
						_.difference( this.options.values, this.selected_items ),
						request.term
					) );
				} else {
					response( $.ui.autocomplete.filter(
						this.options.values, request.term
					) );
				}
			} else {
				$.ajax( {
					type: 'POST',
					dataType: 'json',
					url: this.ajax_url,
					data: $.extend( {
						action: 'vc_get_autocomplete_suggestion',
						shortcode: vc.active_panel.model.get( 'shortcode' ), // get current shortcode?
						param: this.param_name,
						query: request.term,
						_vcnonce: window.vcAdminNonce
					}, this.source_data( request, response ) )
				} ).done( function ( data ) {
					if ( that.options.unique_values ) {
						response(
							_.filter( data, function ( obj ) {
								return ! _.findWhere( that.selected_items, obj );
							} )
						);
					} else {
						response( data );
					}
				} );
			}
		}
	} );

	/**
	 * Param initializer
	 */
	var Vc_ParamInitializer = Backbone.View.extend( {
		$content: {},
		initialize: function () {
			_.bindAll( this, 'content' );
			this.$content = this.$el;
			this.model = vc.active_panel.model;
		},
		setContent: function ( $el ) {
			this.$content = $el;
		},
		content: function () {
			return this.$content;
		},
		render: function () {
			var self;

			self = this;
			$( '[data-vc-ui-element="panel-shortcode-param"]', this.content() ).each( function () {
				var _this = $( this ),
					param = _this.data( 'param_settings' );
				vc.atts.init.call( self, param, _this );
			} );
			return this;
		}
	} );

	/**
	 * Param group
	 */
	var VC_ParamGroup = Backbone.View.extend( {
		options: {
			max_items: 0,
			sortable: true,
			deletable: true,
			collapsible: true
		},
		items: 0,
		$ul: false,
		initializer: {},
		mappedParams: {},
		adminLabelParams: [],
		groupParamName: '',
		events: {
			'click > .edit_form_line > .vc_param_group-list > .vc_param_group-add_content': 'addNew'
		},
		initialize: function ( data ) {
			var $elParam, settings, self;

			this.$ul = this.$el.find( '> .edit_form_line > .vc_param_group-list' );
			$elParam = $( '> .wpb_vc_row', this.$ul );
			this.initializer = new Vc_ParamInitializer( { el: this.$el } );
			this.model = vc.active_panel.model;
			settings = this.$ul.data( 'settings' );

			this.mappedParams = {};
			this.adminLabelParams = [];
			this.options = _.defaults( {},
				_.isObject( data.settings ) ? data.settings : {},
				settings,
				this.options
			);

			this.groupParamName = this.options.param.param_name;

			if ( _.isObject( this.options.param ) && _.isArray( this.options.param.params ) ) {
				_.each( this.options.param.params, function ( param ) {
					var elemName;
					elemName = this.groupParamName + '_' + param.param_name;
					this.mappedParams[ elemName ] = param;

					if ( _.isObject( param ) && true === param.admin_label ) {
						this.adminLabelParams.push( elemName );
					}
				}, this );
			}

			this.items = 0;

			self = this;
			if ( $elParam.length ) {
				$elParam.each( function () {
					new VC_ParamGroup_Param( {
						el: $( this ),
						parent: self
					} );
					self.items ++;
					self.afterAdd( $( this ), 'init' );
				} );
			}

			if ( this.options.sortable ) {
				this.$ul.sortable( {
					handle: '.vc_control.column_move',
					items: '> .wpb_vc_row:not(vc_param_group-add_content-wrapper)',
					placeholder: 'vc_placeholder'
				} );
			}
		},
		addNew: function ( ev ) {
			ev.preventDefault();
			if ( this.addAllowed() ) {
				var $newEl, fn;

				if ( 'undefined' !== typeof(this.options.param.callbacks) && 'undefined' !== typeof(this.options.param.callbacks.before_add) ) {
					fn = window[ this.options.param.callbacks.before_add ];
					if ( 'function' === typeof(fn) ) {
						if ( ! fn() ) {
							return;
						}
					}
				}

				$newEl = $( JSON.parse( this.$ul.next( '.vc_param_group-template' ).html() ) );
				$newEl.removeClass( 'vc_param_group-add_content-wrapper' );
				$newEl.insertBefore( ev.currentTarget );
				$newEl.show();
				this.initializer.setContent( $newEl.find( '> .wpb_element_wrapper' ) );
				this.initializer.render();
				this.items ++;

				new VC_ParamGroup_Param( { el: $newEl, parent: this } );
				this.afterAdd( $newEl, 'new' );
			}
		},
		/**
		 * If you want to disable adding just add max_items = 1
		 *
		 * @returns {boolean}
		 */
		addAllowed: function () {
			return (0 < this.options.max_items && this.items + 1 <= this.options.max_items) || 0 >= this.options.max_items;
		},
		afterAdd: function ( $newEl, action ) {
			var fn;

			if ( ! this.addAllowed() ) {
				this.$ul.find( '> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_clone' ).hide();
				this.$ul.find( '> .vc_param_group-add_content' ).hide();
			}
			if ( ! this.options.sortable ) {
				this.$ul.find( '> .wpb_vc_row > .vc_param_group-controls > .vc_control.column_move' ).hide();
			}
			if ( ! this.options.deletable ) {
				this.$ul.find( '> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_delete' ).hide();
			}
			if ( ! this.options.collapsible ) {
				this.$ul.find( '> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_toggle' ).hide();
			}

			if ( 'undefined' !== typeof(this.options.param.callbacks) && 'undefined' !== typeof(this.options.param.callbacks.after_add) ) {
				fn = window[ this.options.param.callbacks.after_add ];
				if ( 'function' === typeof(fn) ) {
					fn( $newEl, action );
				}
			}
		},
		afterDelete: function () {
			var fn;

			if ( this.addAllowed() ) {
				this.$ul.find( '> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_clone' ).show();
				this.$ul.find( '> .vc_param_group-add_content' ).show();
			}

			if ( 'undefined' !== typeof(this.options.param.callbacks) && 'undefined' !== typeof(this.options.param.callbacks.after_delete) ) {
				fn = window[ this.options.param.callbacks.after_delete ];
				if ( 'function' === typeof(fn) ) {
					fn();
				}
			}
		}
	} );
	var VC_ParamGroup_Param = Backbone.View.extend( {
		dependentElements: false,
		mappedParams: false,
		groupParamName: '',
		adminLabelParams: [],
		events: {
			'click > .controls > .vc_row_edit_clone_delete > .vc_control.column_toggle': 'toggle',
			'click > .controls > .vc_row_edit_clone_delete > .vc_control.column_delete': 'deleteParam',
			'click > .controls > .vc_row_edit_clone_delete > .vc_control.column_clone': 'clone'
		},
		initialize: function ( options ) {
			this.options = options;

			this.$content = this.options.parent.$ul;
			this.model = vc.active_panel.model;
			this.mappedParams = this.options.parent.mappedParams;
			this.groupParamName = this.options.parent.groupParamName;
			this.adminLabelParams = this.options.parent.adminLabelParams;

			this.dependentElements = {};
			_.bindAll( this, 'hookDependent' );
			this.initializeDependency();

			_.bindAll( this, 'hookAdminLabel' );

			this.initializeAdminLabels();
		},
		initializeAdminLabels: function () {
			var i, $fields, callback;
			callback = this.hookAdminLabel;

			for ( i = 0;
				  i < this.adminLabelParams.length;
				  i ++ ) {
				$fields = $( '[name=' + this.adminLabelParams[ i ] + '].wpb_vc_param_value', this.$el );

				$fields.each( function () {
					var $field = $( this );

					if ( ! $field.data( 'vc_admin_labels' ) ) {
						$field.data( 'vc_admin_labels', true );
						$field.bind( 'keyup change', callback );
						callback( { currentTarget: this } );
					}
				} );
			}
		},
		hookAdminLabel: function ( e ) {
			var i, $wrapperLabel, elemName, paramSettings, labelName, labelValue, labels, $field, $parent;

			labelName = '';
			labelValue = '';
			labels = [];
			$field = $( e.currentTarget );
			$parent = $field.closest( '.vc_param_group-wrapper' );

			$wrapperLabel = $field.closest( '.vc_param' ).find( '.vc_param-group-admin-labels' );

			for ( i = 0;
				  i < this.adminLabelParams.length;
				  i ++ ) {
				var $paramWrapper;

				elemName = this.adminLabelParams[ i ];
				$field = $parent.find( '[name=' + elemName + ']' );
				$paramWrapper = $field.closest( '[data-vc-ui-element="panel-shortcode-param"]' );

				if ( 'undefined' !== typeof( this.mappedParams[ elemName ] ) ) {
					labelName = this.mappedParams[ elemName ][ 'heading' ];
				}

				if ( $field.is( 'select' ) ) {
					labelValue = $field.find( 'option:selected' ).text();
				} else if ( $field.is( 'input:checkbox' ) ) {
					labelValue = $field[0].checked ? $field.val() : '';
				} else {
					labelValue = $field.val();
				}

				paramSettings = {
					type: $paramWrapper.data( 'param_type' ),
					param_name: $paramWrapper.data( 'param_name' )
				};

				if ( _.isObject( vc.atts[ paramSettings.type ] ) && _.isFunction( vc.atts[ paramSettings.type ].render ) ) {
					labelValue = vc.atts[ paramSettings.type ].render.call( this, paramSettings, labelValue );
				}

				if ( '' !== labelValue ) {
					labels.push( '<label>' + labelName + '</label>: ' + labelValue );
				}
			}

			$wrapperLabel
				.html( labels.join( ', ' ) )
				.toggleClass( 'vc_hidden-label', ! labels.length );
		},
		initializeDependency: function () {
			var callDependencies;
			callDependencies = {};
			_.each( this.mappedParams, function ( param, name ) {
				if ( _.isObject( param ) && _.isObject( param.dependency ) && _.isString( param.dependency.element ) ) {
					var $masters, $slave;

					$masters = $( '[name=' + this.groupParamName + '_' + param.dependency.element + '].wpb_vc_param_value',
						this.$el );
					$slave = $( '[name=' + name + '].wpb_vc_param_value',
						this.$el );
					if ( $slave.length ) {
						_.each( $masters, function ( master ) {
							var $master;
							$master = $( master );
							var masterName, rules;
							masterName = $master.attr( 'name' );
							rules = param.dependency;
							if ( ! _.isArray( this.dependentElements[ masterName ] ) ) {
								this.dependentElements[ masterName ] = [];
							}
							this.dependentElements[ masterName ].push( $slave );
							if ( ! $master.data( 'dependentSet' ) ) {
								$master.attr( 'data-dependent-set', 'true' );
								$master.bind( 'keyup change', this.hookDependent );
							}
							if ( ! callDependencies[ masterName ] ) {
								callDependencies[ masterName ] = $master;
							}
							if ( _.isString( rules.callback ) ) {
								window[ rules.callback ].call( this );
							}
						}, this );
					}
				}
			}, this );
			_.each( callDependencies, function ( obj ) {
				this.hookDependent( { currentTarget: obj } );
			}, this );
		},
		hookDependent: function ( e ) {
			var $master, $masterContainer, isMasterEmpty, dependentElements, masterValue;

			$master = $( e.currentTarget );
			$masterContainer = $master.closest( '.vc_column' );
			dependentElements = this.dependentElements[ $master.attr( 'name' ) ];
			masterValue = $master.is( ':checkbox' ) ? _.map( this.$el.find( '[name=' + $master.attr( 'name' ) + '].wpb_vc_param_value:checked' ),
				function ( element ) {
					return $( element ).val();
				} )
				: $master.val();

			isMasterEmpty = $master.is( ':checkbox' ) ? ! this.$el.find( '[name=' + $master.attr( 'name' ) + '].wpb_vc_param_value:checked' ).length
				: ! masterValue.length;

			if ( $masterContainer.hasClass( 'vc_dependent-hidden' ) ) {
				_.each( dependentElements, function ( $element ) {
					var event = $.Event( 'change' );
					event.extra_type = 'vcHookDependedParamGroup';
					$element.closest( '.vc_column' ).addClass( 'vc_dependent-hidden' );
					$element.trigger( event );
				} );
			} else {
				_.each( dependentElements, function ( $element ) {
					var event, paramName, rules, $paramBlock;

					paramName = $element.attr( 'name' );
					rules = _.isObject( this.mappedParams[ paramName ] ) && _.isObject( this.mappedParams[ paramName ].dependency ) ? this.mappedParams[ paramName ].dependency : {};
					$paramBlock = $element.closest( '.vc_column' );
					if ( _.isBoolean( rules.not_empty ) && true === rules.not_empty && ! isMasterEmpty ) { // Check is not empty show dependent Element.
						$paramBlock.removeClass( 'vc_dependent-hidden' );
					} else if ( _.isBoolean( rules.is_empty ) && true === rules.is_empty && isMasterEmpty ) {
						$paramBlock.removeClass( 'vc_dependent-hidden' );
					} else if ( rules.value && _.intersection( ( _.isArray( rules.value ) ? rules.value : [ rules.value ]),
							(_.isArray( masterValue ) ? masterValue : [ masterValue ] ) ).length ) {
						$paramBlock.removeClass( 'vc_dependent-hidden' );
					} else if ( rules.value_not_equal_to && ! _.intersection( ( _.isArray( rules.value_not_equal_to ) ? rules.value_not_equal_to : [ rules.value_not_equal_to ]),
							(_.isArray( masterValue ) ? masterValue : [ masterValue ] ) ).length ) {
						$paramBlock.removeClass( 'vc_dependent-hidden' );
					} else {
						$paramBlock.addClass( 'vc_dependent-hidden' );
					}
					event = $.Event( 'change' );
					event.extra_type = 'vcHookDependedParamGroup';
					$element.trigger( event );
				}, this );
			}
			return this;
		},
		deleteParam: function ( ev ) {
			_.isObject( ev ) && ev.preventDefault && ev.preventDefault();
			var answer = confirm( window.i18nLocale.press_ok_to_delete_section );
			if ( true === answer ) {
				this.options.parent.items --;
				this.options.parent.afterDelete();
				this.$el.remove();

				// TODO: check memleaks everywhere
				this.unbind(); // Unbind all local event bindings
				this.remove(); // Remove view from DOM
			}
		},
		content: function () {
			return this.$content;
		},
		clone: function ( ev ) {
			ev.preventDefault();
			if ( this.options.parent.addAllowed() ) {
				var param = this.options.parent.$ul.data( 'settings' );
				var $content = this.$content;
				this.$content = this.$el;
				var value = vc.atts.param_group.parseOne.call( this, param );

				$.ajax( {
					type: 'POST',
					url: window.ajaxurl,
					data: {
						action: 'vc_param_group_clone',
						param: encodeURIComponent( JSON.stringify( param ) ),
						shortcode: vc.active_panel.model.get( 'shortcode' ),
						value: value,
						vc_inline: true,
						_vcnonce: window.vcAdminNonce
					},
					dataType: 'html',
					context: this
				} ).done( function ( html ) {
					var $newEl;

					$newEl = $( html );
					$newEl.insertAfter( this.$el );
					this.$content = $content;
					this.options.parent.initializer.$content = $( '> .wpb_element_wrapper', $newEl );
					this.options.parent.initializer.render();
					new VC_ParamGroup_Param( {
						el: $newEl,
						parent: this.options.parent
					} );
					this.options.parent.items ++;
					this.options.parent.afterAdd( $newEl, 'clone' );
				} );

			}
		},
		toggle: function ( ev ) {
			ev.preventDefault();
			var $parent = this.$el,
				$elem = $parent.find( '> .wpb_element_wrapper' );
			$elem.slideToggle();
			$parent
				.toggleClass( 'vc_param_group-collapsed' )
				.siblings( ':not(.vc_param_group-collapsed)' )
				.addClass( 'vc_param_group-collapsed' )
				.find( '> .wpb_element_wrapper' )
				.slideUp();
		}
	} );

	var i18n = window.i18nLocale;
	vc.edit_form_callbacks = [];
	vc.atts = {
		parse: function ( param ) {
			var value, params, $param, $field;

			$field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' );
			$param = $field.closest( '[data-vc-ui-element="panel-shortcode-param"]' );

			if ( ! _.isUndefined( vc.atts[ param.type ] ) && ! _.isUndefined( vc.atts[ param.type ].parse ) ) {
				if ( $param.data( 'vcInitParam' ) ) {
					value = vc.atts[ param.type ].parse.call( this, param );
				} else {
					params = this.model.get( 'params' );
					value = (! _.isUndefined( params[ param.param_name ] ) ) ? params[ param.param_name ] : ($field.length ? $field.val() : null);
				}
			} else {
				value = $field.length ? $field.val() : null;
			}

			if ( 'undefined' !== typeof($field.data( 'js-function' )) && 'undefined' !== typeof(window[ $field.data( 'js-function' ) ]) ) {
				var fn = window[ $field.data( 'js-function' ) ];
				fn( this.$el, this, param );
			}

/*			if ( 'content' !== param.param_name ) {
				value = vcEscapeHtml( value );
			}*/

			return value;
		},
		parseFrame: function ( param ) {
			return vc.atts.parse.call( this, param );
		},
		init: function ( param, $field ) {
			if ( ! _.isUndefined( vc.atts[ param.type ] ) && ! _.isUndefined( vc.atts[ param.type ].init ) ) {
				vc.atts[ param.type ].init.call( this, param, $field );
			}
		}
	};

	// Default atts
	vc.atts.textarea_html = {
		parse: function ( param ) {
			var _window = this.window(),
				$field = this.content().find( '.textarea_html.' + param.param_name + '' );
			try {
				if ( _window.tinyMCE && _.isArray( _window.tinyMCE.editors ) ) {
					_.each( _window.tinyMCE.editors, function ( _editor ) {
						if ( 'wpb_tinymce_content' === _editor.id ) {
							_editor.save();
						}
					} );
				}
			} catch ( e ) {
				console && console.error && console.error( e );
			}
			return $field.val();
		},
		render: function ( param, value ) {
			return _.isUndefined( value ) ? value : vc_wpautop( value );
		}
	};

	vc.atts.textarea_safe = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' ),
				new_value = $field.val();
			return new_value.match( /"/ ) ? '#E-8_' + base64_encode( rawurlencode( new_value ) ) : new_value;
		},
		render: function ( param, value ) {
			return value && value.match( /^#E\-8_/ ) ? $( "<div/>" ).text( rawurldecode( base64_decode( value.replace( /^#E\-8_/,
				'' ) ) ) ).html() : value;
		}
	};

	vc.atts.checkbox = {
		parse: function ( param ) {
			var arr, newValue;

			arr = [];
			newValue = '';
			$( 'input[name=' + param.param_name + ']', this.content() ).each( function () {
				var self;

				self = $( this );
				if ( this.checked ) {
					arr.push( self.attr( 'value' ) );
				}
			} );
			if ( 0 < arr.length ) {
				newValue = arr.join( ',' );
			}
			return newValue;
		},
		defaults: function ( param ) {
			return '';
		}
	};

	vc.atts.el_id = {
		/**
		 * Called when shortcode cloning happens
		 * Resets el_id value
		 *
		 * @param clonedModel
		 * @param paramValue
		 * @param paramSettings
		 */
		clone: function ( clonedModel, paramValue, paramSettings ) {
			var shortcodeParams;
			shortcodeParams = clonedModel.get( 'params' );
			if ( ! _.isUndefined( paramSettings ) && ! _.isUndefined( paramSettings.settings ) && ! _.isUndefined( paramSettings.settings.auto_generate ) && true === paramSettings.settings.auto_generate ) {
				shortcodeParams[ paramSettings.param_name ] = Date.now() + '-' + vc_guid();
			} else {
				shortcodeParams[ paramSettings.param_name ] = ""; // just reset a value
			}
			clonedModel.set( { params: shortcodeParams }, { silent: true } );
		},
		/**
		 * Called when shortcode being created, used to generate new Id or remove it
		 *
		 * @param shortcodeModel
		 * @param paramValue
		 * @param paramSettings
		 */
		create: function ( shortcodeModel, paramValue, paramSettings ) {
			if ( shortcodeModel.get( 'cloned' ) ) {
				return vc.atts.el_id.clone( shortcodeModel, paramValue, paramSettings );
			}
			if ( _.isEmpty( paramValue ) && ! _.isUndefined( paramSettings ) && ! _.isUndefined( paramSettings.settings ) && ! _.isUndefined( paramSettings.settings.auto_generate ) && true == paramSettings.settings.auto_generate ) {
				var shortcodeParams;

				shortcodeParams = shortcodeModel.get( 'params' );
				shortcodeParams[ paramSettings.param_name ] = Date.now() + '-' + vc_guid();
				shortcodeModel.set( { params: shortcodeParams }, { silent: true } );
			}
		}
	};
	// Adding event listener on shortcode being created having param_type el_id
	vc.events.on( 'shortcodes:add:param:type:el_id', vc.atts.el_id.create );

	vc.atts.posttypes = {
		parse: function ( param ) {
			var posstypes_arr = [],
				new_value = '';
			$( 'input[name=' + param.param_name + ']', this.content() ).each( function () {
				var self = $( this );
				if ( this.checked ) {
					posstypes_arr.push( self.attr( "value" ) );
				}
			} );
			if ( 0 < posstypes_arr.length ) {
				new_value = posstypes_arr.join( ',' );
			}
			return new_value;
		}
	};

	vc.atts.taxonomies = {
		parse: function ( param ) {
			var posstypes_arr = [],
				new_value = '';
			$( 'input[name=' + param.param_name + ']', this.content() ).each( function () {
				var self = $( this );
				if ( this.checked ) {
					posstypes_arr.push( self.attr( "value" ) );
				}
			} );
			if ( 0 < posstypes_arr.length ) {
				new_value = posstypes_arr.join( ',' );
			}
			return new_value;
		}
	};

	vc.atts.exploded_textarea = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' );
			return $field.val().replace( /\n/g, "," );
		}
	};

	vc.atts.textarea_raw_html = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' ),
				new_value = $field.val();
			return base64_encode( rawurlencode( new_value ) );
		},
		render: function ( param, value ) {
			return value ? $( "<div/>" ).text( rawurldecode( base64_decode( value.trim() ) ) ).html() : '';
		}
	};

	vc.atts.dropdown = {
		render: function ( param, value ) {
			return value;
		},
		init: function ( param, $field ) {
			$( '.wpb_vc_param_value.dropdown', $field ).change( function () {
				var $this = $( this ),
					$options = $this.find( ':selected' ),
					prev_option_class = $this.data( 'option' ),
					option_class = $options.length ? $options.attr( 'class' ).replace( /\s/g, '_' ) : '';
				option_class = option_class.replace( '#', 'hash-' );
				'undefined' !== typeof(prev_option_class) && $this.removeClass( prev_option_class );
				'undefined' !== typeof(option_class) && $this.data( 'option',
					option_class ) && $this.addClass( option_class );
			} );
		},
		defaults: function ( param ) {
			var values;
			if ( ! _.isArray( param.value ) && ! _.isString( param.value ) ) {
				values = _.values( param.value )[ 0 ];
				return values.label ? values.value : values;
			} else if ( _.isArray( param.value ) ) {
				values = param.value[ 0 ];
				return _.isArray( values ) && values.length ? values[ 0 ] : values;
			}
			return '';
		}
	};

	vc.atts.attach_images = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' ),
				thumbnails_html = '';
			// TODO: Check image search with Wordpress
			$field.parent().find( 'li.added' ).each( function () {
				thumbnails_html += '<li><img src="' + $( this ).find( 'img' ).attr( 'src' ) + '" alt=""></li>';
			} );
			$( '[data-model-id=' + this.model.id + ']' ).data( 'field-' + param.param_name + '-attach-images',
				thumbnails_html );
			return $field.length ? $field.val() : null;
		},
		render: function ( param, value ) {
			var $thumbnails = this.$el.find( '.attachment-thumbnails[data-name=' + param.param_name + ']' );

			if ( 'external_link' === this.model.getParam( 'source' ) ) {
				value = this.model.getParam( 'custom_srcs' );
			}

			if ( ! _.isEmpty( value ) ) {
				$.ajax( {
					type: 'POST',
					url: window.ajaxurl,
					data: {
						action: 'wpb_gallery_html',
						content: value,
						_vcnonce: window.vcAdminNonce
					},
					dataType: 'html',
					context: this
				} ).done( function ( html ) {
					vc.atts.attach_images.updateImages( $thumbnails, html );
				} );
			} else {
				this.$el.removeData( 'field-' + param.param_name + '-attach-images' );
				vc.atts.attach_images.updateImages( $thumbnails, '' );
			}

			return value;
		},
		updateImages: function ( $thumbnails, thumbnails_html ) {
			$thumbnails.html( thumbnails_html );
			if ( thumbnails_html.length ) {
				$thumbnails.removeClass( 'image-exists' ).next().addClass( 'image-exists' );
			} else {
				$thumbnails.addClass( 'image-exists' ).next().removeClass( 'image-exists' );
			}
		}
	};

	vc.atts.href = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' ),
				val = '';
			if ( $field.length && 'http://' !== $field.val() ) {
				val = $field.val();
			}
			return val;
		}
	};

	vc.atts.attach_image = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' ),
				image_src = '';
			if ( $field.parent().find( 'li.added' ).length ) {
				image_src = $field.parent().find( 'li.added img' ).attr( 'src' );
			}
			$( '[data-model-id=' + this.model.id + ']' ).data( 'field-' + param.param_name + '-attach-image',
				image_src );
			return $field.length ? $field.val() : null;
		},
		render: function ( param, value ) {
			var $model = $( '[data-model-id=' + this.model.id + ']' );
			var image_src = $model.data( 'field-' + param.param_name + '-attach-image' );
			var $thumbnail = this.$el.find( '.attachment-thumbnail[data-name=' + param.param_name + ']' );
			var $post_id = $( '#post_ID' );
			var post_id = $post_id.length ? $post_id.val() : 0;

			if ( 'image' === param.param_name ) {
				switch ( this.model.getParam( 'source' ) ) {
					case 'external_link':
						vc.atts[ 'attach_image' ].updateImage( $thumbnail, this.model.getParam( 'custom_src' ) );
						break;

					default:
						if ( ! _.isEmpty( value ) || 'featured_image' === this.model.getParam( 'source' ) ) {
							$.ajax( {
								type: 'POST',
								url: window.ajaxurl,
								data: {
									action: 'wpb_single_image_src',
									content: value,
									params: this.model.get( 'params' ),
									post_id: post_id,
									_vcnonce: window.vcAdminNonce
								},
								dataType: 'html',
								context: this
							} ).done( function ( image_src ) {
								var image_exists = image_src.length || 'featured_image' === this.model.getParam( 'source' );
								vc.atts[ 'attach_image' ].updateImage( $thumbnail, image_src, image_exists );
							} );
						} else if ( ! _.isUndefined( image_src ) ) {
							$model.removeData( 'field-' + param.param_name + '-attach-image' );
							vc.atts[ 'attach_image' ].updateImage( $thumbnail, image_src );
						}

						break;
				}
			}

			return value;
		},
		updateImage: function ( $thumbnail, image_src, image_exists ) {
			if ( ! $thumbnail.length ) {
				return;
			}

			if ( 'undefined' === typeof(image_exists) ) {
				image_exists = false;
			}

			if ( image_exists || ! _.isEmpty( image_src ) ) {
				$thumbnail.attr( 'src', image_src );

				if ( _.isEmpty( image_src ) ) {
					$thumbnail.hide();
					$thumbnail.next().removeClass( 'image-exists' ).next().addClass( 'image-exists' );
				} else {
					$thumbnail.show();
					$thumbnail.next().addClass( 'image-exists' ).next().addClass( 'image-exists' );
				}
			} else {
				$thumbnail
					.attr( 'src', '' )
					.hide()
					.next().removeClass( 'image-exists' ).next().removeClass( 'image-exists' );
			}
		}
	};

	vc.atts.google_fonts = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' );
			var $block = $field.parent();
			var options = {},
				string_pieces,
				string;
			options.font_family = $block.find( '.vc_google_fonts_form_field-font_family-select > option:selected' ).val();
			options.font_style = $block.find( '.vc_google_fonts_form_field-font_style-select > option:selected' ).val();
			string_pieces = _.map( options, function ( value, key ) {
				if ( _.isString( value ) && 0 < value.length ) {
					return key + ':' + encodeURIComponent( value );
				}
			} );
			string = $.grep( string_pieces, function ( value ) {
				return _.isString( value ) && 0 < value.length;
			} ).join( '|' );
			return string;
		},
		init: function ( param, $field ) {
			var $g_fonts = $field;
			if ( $g_fonts.length ) {
				if ( 'undefined' !== typeof(WebFont) ) {
					new GoogleFonts( { el: $g_fonts } );
				} else {
					$g_fonts.find( '> .edit_form_line' ).html( window.i18nLocale.gfonts_unable_to_load_google_fonts || "Unable to load Google Fonts" );
				}
			}
		}
	};

	vc.atts.font_container = {
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' );
			var $block = $field.parent();
			var options = {},
				string_pieces,
				string;
			options.tag = $block.find( '.vc_font_container_form_field-tag-select > option:selected' ).val();
			options.font_size = $block.find( '.vc_font_container_form_field-font_size-input' ).val();
			options.text_align = $block.find( '.vc_font_container_form_field-text_align-select > option:selected' ).val();
			options.font_family = $block.find( '.vc_font_container_form_field-font_family-select > option:selected' ).val();
			options.color = $block.find( '.vc_font_container_form_field-color-input' ).val();
			options.line_height = $block.find( '.vc_font_container_form_field-line_height-input' ).val();
			options.font_style_italic = $block.find( '.vc_font_container_form_field-font_style-checkbox.italic' ).prop( 'checked' ) ? "1" : "";
			options.font_style_bold = $block.find( '.vc_font_container_form_field-font_style-checkbox.bold' ).prop( 'checked' ) ? "1" : "";
			string_pieces = _.map( options, function ( value, key ) {
				if ( _.isString( value ) && 0 < value.length ) {
					return key + ':' + encodeURIComponent( value );
				}
			} );
			string = $.grep( string_pieces, function ( value ) {
				return _.isString( value ) && 0 < value.length;
			} ).join( '|' );
			return string;
		},
		init: function ( param, $field ) {
			vc.atts.colorpicker.init.call( this, param, $field );
		}
	};

	vc.atts.param_group = {
		parse: function ( param ) {
			var data,
				$content,
				$block,
				$list;

			$content = this.content();
			$block = $content.find( '.wpb_el_type_param_group[data-vc-ui-element="panel-shortcode-param"][data-vc-shortcode-param-name="' + param[ 'param_name' ] + '"]' );
			$list = $block.find( '> .edit_form_line > .vc_param_group-list' );

			data = vc.atts.param_group.extractValues.call( this,
				param,
				$( '>.wpb_vc_row:not(".vc_param_group-add_content-wrapper")', $list ) );

			// return back content to be correct on next save
			this.$content = $content;
			return encodeURIComponent( JSON.stringify( data ) );
		},
		extractValues: function ( param, $el ) {
			var data, self;

			data = [];
			self = this;
			$el.each( function () {
				var innerData;

				innerData = {};
				self.$content = $( this );
				_.each( param[ 'params' ], function ( par ) {
					var innerParam, innerParamName, value;

					innerParam = $.extend( {}, par );
					innerParamName = innerParam[ 'param_name' ];
					innerParam[ 'param_name' ] = param[ 'param_name' ] + '_' + innerParamName;
					value = vc.atts.parse.call( self, innerParam );
					if ( value.length || innerParam.save_always ) {
						innerData[ innerParamName ] = value;
					}
				} );
				data.push( innerData );
			} );

			return data;
		},
		parseOne: function ( param ) {
			var $content,
				data;

			$content = this.content();
			data = vc.atts.param_group.extractValues.call( this, param, $content );
			this.$content = $content;
			return encodeURIComponent( JSON.stringify( data ) );
		},
		init: function ( param, $field ) {
			new VC_ParamGroup( {
				el: $field,
				settings: {
					param: param
				}
			} );
		}
	};
	vc.atts.colorpicker = {
		init: function ( param, $field ) {
			var $content = $field;
			$( '.vc_color-control', $content ).each( function () {
				var $control = $( this ),
					value = $control.val().replace( /\s+/g, '' ),
					alpha_val = 100,
					$alpha, $alpha_output, $pickerContainer;
				if ( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ ) ) {
					alpha_val = parseFloat( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ )[ 1 ] ) * 100;
				}
				$control.wpColorPicker( {
					clear: function ( event, ui ) {
						$alpha.val( 100 );
						$alpha_output.val( 100 + '%' );
					},
					change: _.debounce( function () {
						$( this ).trigger( 'change' );
					}, 500 )
				} );
				$pickerContainer = $control.closest( '.wp-picker-container' );
				$( '<div class="vc_alpha-container">'
				+ '<label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label>'
				+ '<input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="vc_alpha-field">'
				+ '</div>' ).appendTo( $pickerContainer.addClass( 'vc_color-picker' ).find( '.iris-picker' ) );
				$alpha = $pickerContainer.find( '.vc_alpha-field' );
				$alpha_output = $pickerContainer.find( '.vc_alpha-container output' );
				$alpha.bind( 'change keyup', function () {
					var alpha_val = parseFloat( $alpha.val() ),
						iris = $control.data( 'a8c-iris' ),
						color_picker = $control.data( 'wp-wpColorPicker' );
					$alpha_output.val( $alpha.val() + '%' );
					iris._color._alpha = alpha_val / 100.0;
					$control.val( iris._color.toString() );
					color_picker.toggler.css( { backgroundColor: $control.val() } );
				} ).val( alpha_val ).trigger( 'change' );
			} );
		}
	};

	vc.atts.autocomplete = {
		init: function ( param, $field ) {
			var $el_type_autocomplete = $field;
			if ( $el_type_autocomplete.length ) {
				$el_type_autocomplete.each( function () {
					var options,
						$param = $( '.wpb_vc_param_value', this ),
						param_name = $param.attr( 'name' ),
						$el = $( '.vc_auto_complete_param', this ),
						ac;

					options = $.extend( {
							$param_input: $param,
							param_name: param_name,
							$el: $el
						},
						$param.data( 'settings' )
					);

					ac = new VC_AutoComplete( options );
					if ( options.multiple ) {
						ac.enableMultiple();
					}
					if ( options.sortable ) {
						ac.enableSortable();
					}
					$param.data( 'object', ac );
				} );

			}
		}
	};

	vc.atts.loop = {
		init: function ( param, $field ) {
			new VcLoop( { el: $field } );
		}
	};

	vc.atts.vc_link = {
		init: function ( param, $field ) {
			$( '.vc_link-build', $field ).click( function ( e ) {
				var $block,
					$input,
					$url_label,
					$title_label,
					value_object,
					$link_submit,
					$vc_link_submit,
					dialog;
				e.preventDefault();
				$block = $( this ).closest( '.vc_link' );
				$input = $block.find( '.wpb_vc_param_value' );
				$url_label = $block.find( '.url-label' );
				$title_label = $block.find( '.title-label' );
				value_object = $input.data( 'json' );
				$link_submit = $( '#wp-link-submit' );
				$vc_link_submit = $( '<input type="submit" name="vc_link-submit" id="vc_link-submit" class="button-primary" value="Set Link">' );
				$link_submit.hide();
				$( "#vc_link-submit" ).remove();
				$vc_link_submit.insertBefore( $link_submit );
				if ( ! window.wpLink && $.fn.wpdialog && $( '#wp-link' ).length ) {
					dialog = {
						$link: false,
						open: function () {
							this.$link = $( '#wp-link' ).wpdialog( {
								title: wpLinkL10n.title,
								width: 480,
								height: 'auto',
								modal: true,
								dialogClass: 'wp-dialog',
								zIndex: 300000
							} );
						},
						close: function () {
							this.$link.wpdialog( 'close' );
						}
					};
				} else {
					dialog = window.wpLink;
				}

				dialog.open( 'content' );
				if ( _.isString( value_object.url ) ) {
					$( '#wp-link-url' ).length ? $( '#wp-link-url' ).val( value_object.url ) : $( '#url-field' ).val( value_object.url );
				}
				if ( _.isString( value_object.title ) ) {
					$( '#wp-link-text' ).length ? $( '#wp-link-text' ).val( value_object.title ) : $( '#link-title-field' ).val( value_object.title );
				}
				if ( $( '#wp-link-target' ).length ) {
					$( '#wp-link-target' ).prop( 'checked', ! _.isEmpty( value_object.target ) );
				} else {
					$( '#link-target-checkbox' ).prop( 'checked', ! _.isEmpty( value_object.target ) );
				}
				$vc_link_submit.unbind( 'click.vcLink' ).bind( 'click.vcLink', function ( e ) {
					e.preventDefault();
					e.stopImmediatePropagation();
					var options = {},
						string;
					options.url = $( '#wp-link-url' ).length ? $( '#wp-link-url' ).val() : $( '#url-field' ).val();
					options.title = $( '#wp-link-text' ).length ? $( '#wp-link-text' ).val() : $( '#link-title-field' ).val();
					var $checkbox = $( '#wp-link-target' ).length ? $( '#wp-link-target' ) : $( '#link-target-checkbox' );
					options.target = $checkbox[0].checked ? ' _blank' : '';
					string = _.map( options, function ( value, key ) {
						if ( _.isString( value ) && 0 < value.length ) {
							return key + ':' + encodeURIComponent( value );
						}
					} ).join( '|' );
					$input.val( string );
					$input.data( 'json', options );
					$url_label.html( options.url + options.target );
					$title_label.html( options.title );

					dialog.close();
					$link_submit.show();
					$vc_link_submit.unbind( 'click.vcLink' );
					$vc_link_submit.remove();
					// remove vc_link hooks for wpLink
					$( '#wp-link-cancel' ).unbind( 'click.vcLink' );
					window.wpLink.textarea = '';
					$checkbox.attr( 'checked', false );
					return false;
				} );
				$( '#wp-link-cancel' ).unbind( 'click.vcLink' ).bind( 'click.vcLink', function ( e ) {
					e.preventDefault();
					dialog.close();
					// remove vc_link hooks for wpLink
					$vc_link_submit.unbind( 'click.vcLink' );
					$vc_link_submit.remove();
					// remove vc_link hooks for wpLink
					$( '#wp-link-cancel' ).unbind( 'click.vcLink' );
					window.wpLink.textarea = '';
				} );
			} );
		}
	};

	vc.atts.sorted_list = {
		init: function ( param, $field ) {
			$( '.vc_sorted-list', $field ).VcSortedList();
		}
	};
	vc.atts.options = {
		init: function ( param, $field ) {
			new VcOptionsField( { el: $field } );
		}
	};

	vc.atts.iconpicker = {
		change: function ( param, $field ) {
			var $select = $field.find( '.vc-iconpicker' );
			$select.val( this.value );
			$select.data( 'vc-no-check', true );
			$select.find( '[value="' + this.value + '"]' ).attr( 'selected', 'selected' );
			$select.data( 'vcFontIconPicker' ).loadIcons(); // this methods actualy reload "active icon" and triggers event change
		},
		parse: function ( param ) {
			var $field = this.content().find( '.wpb_vc_param_value[name=' + param.param_name + ']' );
			var $block = $field.parent();
			var val = $block.find( '.vc-iconpicker' ).val();

			return val;
		},
		init: function ( param, $field ) {
			var $el = $field.find( '.wpb_vc_param_value' );

			var settings = $.extend( {
				iconsPerPage: 100, // default icons per page for iconpicker
				iconDownClass: 'fip-fa fa fa-arrow-down',
				iconUpClass: 'fip-fa fa fa-arrow-up',
				iconLeftClass: 'fip-fa fa fa-arrow-left',
				iconRightClass: 'fip-fa fa fa-arrow-right',
				iconSearchClass: 'fip-fa fa fa-search',
				iconCancelClass: 'fip-fa fa fa-remove',
				iconBlockClass: 'fip-fa'
			}, $el.data( 'settings' ) );

			$field.find( '.vc-iconpicker' ).vcFontIconPicker( settings ).on( 'change', function ( e ) {
				var $select = $( this );
				if ( ! $select.data( 'vc-no-check' ) ) {
					$el.data( 'vc-no-check', true ).val( this.value ).trigger( 'change' );
				}
				$select.data( 'vc-no-check', false );

			} );
			$el.on( 'change', function ( e ) {
				if ( ! $el.data( 'vc-no-check' ) ) {
					vc.atts.iconpicker.change.call( this, param, $field );
				}
				$el.data( 'vc-no-check', false );
			} );
		}
	};

	vc.atts.animation_style = {
		init: function ( param, $field ) {
			var content = $field;
			var $field_input = $( '.wpb_vc_param_value[name=' + param.param_name + ']', content );
			$( 'option[value="' + $field_input.val() + '"]', content ).attr( 'selected', true );
			function animation_style_test( el, x ) {
				$( el ).removeClass().addClass( x + ' animated' ).one( 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
					function () {
						$( this ).removeClass().addClass( 'vc_param-animation-style-preview' );
					} );
			}

			$( '.vc_param-animation-style-trigger', content ).click( function ( e ) {
				e.preventDefault();
				var animation = $( '.vc_param-animation-style', content ).val();
				if ( 'none' !== animation ) {
					animation_style_test( this.parentNode, 'vc_param-animation-style-preview ' + animation );
				}
			} );

			$( '.vc_param-animation-style', content ).change( function () {
				var animation = $( this ).val();
				$field_input.val( animation );
				if ( 'none' !== animation ) {
					var el = $( '.vc_param-animation-style-preview', content );
					animation_style_test( el, 'vc_param-animation-style-preview ' + animation );
				}
			} );
		}
	};

	vc.atts.vc_grid_id = {
		/**
		 * Called in backend when element being save by edit form
		 * @returns {string}
		 */
		parse: function () {
			var value = 'vc_gid:' + (Date.now() + '-' + this.model.get( 'id' ) + '-' + Math.floor( Math.random() * 11 ));
			return value;
		}
	};
	/**
	 * @type {{addShortcode: Function}}
	 * @param model vc.shortcode
	 */
	vc.atts.addShortcodeIdParam = function ( model ) {
		var params, settings;
		params = model.get( 'params' );
		settings = vc.map[ model.get( 'shortcode' ) ];
		if ( _.isArray( settings.params ) ) {
			_.each( settings.params, function ( p ) {
				if ( 'tab_id' === p.type && _.isEmpty( params[ p.param_name ] ) ) {
					params[ p.param_name ] = vc_guid() + '-' + Math.floor( Math.random() * 11 );
				} else if ( 'vc_grid_id' === p.type ) {
					params[ p.param_name ] = vc.atts.vc_grid_id.parse.call( { model: model } );
				}
			} );
		}
		model.save( 'params', params, { silent: true } );
	};
	vc.getMapped = vc.memoizeWrapper( function ( tag ) {
		return vc.map[ tag ] || {};
	} );
})( window.jQuery );