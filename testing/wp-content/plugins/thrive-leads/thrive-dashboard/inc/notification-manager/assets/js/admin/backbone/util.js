/**
 * Created by dan bilauca on 7/14/2016.
 * Util functions/plugins for backbone
 */

var TD_NM = TD_NM || {};
TD_NM.util = TD_NM.util || {};

(function ( $ ) {
	TD_NM.util.camel_case = function ( string ) {
		var chunks = string.split( '_' ),
			result = '';
		$.each( chunks, function ( index, value ) {
			result += value.toLowerCase().charAt( 0 ).toUpperCase() + value.slice( 1 );
		} );

		return result;
	};

	/**
	 * Sprintf js version
	 * @param string
	 * @param {...*} args
	 * @returns {*}
	 */
	TD_NM.util.printf = function ( string, args ) {
		if ( ! args ) {
			return string;
		}

		var is_array = args instanceof Array;

		if ( ! is_array ) {
			args = [args];
		}

		_.each(
			args, function ( replacement ) {
				string = string.replace( "%s", replacement );
			}
		);

		return string;
	};

	/**
	 * Returns the correct form of translated string
	 * based on count number
	 *
	 * @param {String} single
	 * @param {String} plural
	 * @param {Int} count
	 *
	 * @returns {String}
	 */
	TD_NM.util.plural = function ( single, plural, count ) {
		return count == 1 ? ThriveUlt.util.printf( single, count ) : ThriveUlt.util.printf( plural, count );
	};

	/**
	 * binds all form elements on a view
	 * Form elements must have a data-bind attribute which should contain the field name from the model
	 * composite fields are not supported
	 *
	 * this will bind listeners on models and on the form elements
	 *
	 * @param {Backbone.View} view
	 * @param {Backbone.Model} [model] optional, it will default to the view's model
	 */
	TD_NM.util.data_binder = function ( view, model ) {

		if ( typeof model === 'undefined' ) {
			model = view.model;
		}

		if ( ! model instanceof Backbone.Model ) {
			return;
		}

		/**
		 * separate value by input type
		 *
		 * @param {object} $input jquery
		 * @returns {*}
		 */
		function value_getter( $input ) {
			if ( $input.is( ':checkbox' ) ) {
				return $input.is( ':checked' ) ? true : false;
			}
			if ( $input.is( ':radio' ) ) {
				return $input.is( ':checked' ) ? $input.val() : '';
			}

			return $input.val();
		}

		/**
		 * separate setter vor values based on input type
		 *
		 * @param {object} $input jquery object
		 * @param {*} value
		 * @returns {*}
		 */
		function value_setter( $input, value ) {
			if ( $input.is( ':radio' ) ) {
				return view.$el.find( 'input[name="' + $input.attr( 'name' ) + '"]:radio' ).filter( '[value="' + value + '"]' ).prop( 'checked', true );
			}
			if ( $input.is( ':checkbox' ) ) {
				return $input.prop( 'checked', value ? true : false );
			}

			return $input.val( value );
		}

		/**
		 * iterate through each of the elements and bind change listeners on DOM and on the model
		 */
		var $elements = view.$el.find( '[data-bind]' ).each(
			function () {

				var $this = $( this ),
					prop = $this.attr( 'data-bind' ),
					_dirty = false;

				$this.on(
					'change', function () {
						var _value = value_getter( $this );
						if ( model.get( prop ) != _value ) {
							_dirty = true;
							model.set( prop, _value )
							_dirty = false;
						}
					}
				);

				view.listenTo(
					model, 'change:' + prop, function () {
						if ( ! _dirty ) {
							value_setter( $this, this.model.get( prop ) );
						}
					}
				);
			}
		);

		/**
		 * if a model defines a validate() function, it should return an array of binds in the form of:
		 *      ['post_title']
		 * this will add error classes to the bound dom elements
		 */
		view.listenTo(
			model, 'invalid', function ( model, error ) {
				if ( _.isArray( error ) ) {
					_.each(
						error, function ( field ) {
							var _field = field;
							if ( field.field ) { // if this is an object, we need to use the field property
								_field = field.field
							}
							var $target = $elements.filter( '[data-bind="' + _field + '"]' ).first().addClass( 'tvd-validate tvd-invalid' ).focus();
							if ( field.message ) {
								$target.siblings( 'label' ).attr( 'data-error', field.message );
							}
							if ( $target.is( ':radio' ) || $target.is( ':checkbox' ) ) {
								TVE_Dash.err( $target.next( 'label' ).attr( 'data-error' ) );
							}
						}
					);
				} else if ( _.isString( error ) ) {
					TVE_Dash.err( error );
				}
			}
		);
	};

	/**
	 * Email validator
	 *
	 * @param string
	 * @returns {boolean}
	 */
	TD_NM.util.is_email = function ( string ) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test( string );
	};

	/**
	 * pre-process the ajaxurl admin js variable and append a querystring to it
	 * some plugins are adding an extra parameter to the admin-ajax.php url. Example: admin-ajax.php?lang=en
	 *
	 * @param {string} [query_string] optional, query string to be appended
	 */
	TD_NM.util.ajaxurl = function ( query_string ) {
		var _q = ajaxurl.indexOf( '?' ) !== - 1 ? '&' : '?';
		if ( ! query_string || ! query_string.length ) {
			return ajaxurl + _q + '_nonce=' + TD_NM.admin_nonce;
		}
		query_string = query_string.replace( /^(\?|&)/, '' );
		query_string += '&_nonce=' + TD_NM.admin_nonce;

		return ajaxurl + _q + query_string;
	};

})( jQuery );