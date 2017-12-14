/**
 * utility functions to use throughout Thrive products
 *
 * @type {object}
 */
var TVE_Dash = TVE_Dash || {};

(function ( $ ) {
	/**
	 * instantiate an Object
	 *
	 * @param {object} Type Constructor function
	 * @param {object} [opts] constructor parameter
	 * @returns {object}
	 */
	TVE_Dash._instantiate = function ( Type, opts ) {
		var Constructor = function () {
		}, instance, args = Array.prototype.slice.call( arguments, 1 );

		Constructor.prototype = Type.prototype;

		instance = new Constructor();
		Type.apply( instance, args );

		return instance;
	};

	/**
	 * open a modal
	 *
	 * @param {Backbone.View} ViewConstructor
	 * @param {object} opts
	 */
	TVE_Dash.modal = function ( ViewConstructor, opts ) {

		opts = opts || {};
		opts['max-width'] = opts['max-width'] || '35%';

		var reserved = [
			'events', 'model', 'collection', 'el', 'id', 'className', 'tagName', 'attributes'
		];

		if ( opts.model instanceof Backbone.Model ) {
			_.each( opts.model.toJSON(), function ( _value, _key ) {
				if ( typeof opts[_key] === 'undefined' && _.indexOf( reserved, _key ) === - 1 ) {
					opts[_key] = _value;
				}
			} );
		}

		var view = TVE_Dash._instantiate( ViewConstructor, opts );

		if ( ! view instanceof TVE_Dash.views.Modal ) {
			console.error && console.error( 'View must be an instance of Modal' );
			return;
		}

		return view.render().open();

	};

	/**
	 * bind the zclip js lib over a "copy" button
	 *
	 * @param $element jQuery elem
	 */
	TVE_Dash.bindZClip = function ( $element ) {
		function bind_it() {
			//bind zclip on links that copy the shortcode in clipboard
			try {
				$element.closest( '.tvd-copy-row' ).find( 'input.tvd-copy' ).on( 'click', function ( e ) {
					this.select();
					e.preventDefault();
					e.stopPropagation();
				} );

				$element.zclip( {
					path: TVE_Dash_Const.dash_url + '/js/util//jquery.zclip.1.1.1/ZeroClipboard.swf',
					copy: function () {
						return jQuery( this ).parents( '.tvd-copy-row' ).find( 'input' ).val();
					},
					afterCopy: function () {
						var $link = jQuery( this );
						$link.prev().select();
						$link.removeClass( 'tvd-btn-blue' ).addClass( 'tvd-btn-green' ).find( '.tvd-copy-text' ).html( '<span class="tvd-icon-check"></span>' );
						setTimeout( function () {
							$link.removeClass( 'tvd-btn-green' ).addClass( 'tvd-btn-blue' ).find( '.tvd-copy-text' ).html( TVE_Dash_Const.translations.Copy )
						}, 3000 );
						$link.parent().prev().select();
					}
				} );
			} catch ( e ) {
				console.error && console.error( 'Error embedding zclip - most likely another plugin is messing this up' ) && console.error( e );
			}
		}

		setTimeout( bind_it, 200 );
	};

	/**
	 * bind materialize on every element inside the $root node
	 *
	 * @param {object} $root jQuery wrapper over a html node
	 */
	TVE_Dash.materialize = function ( $root ) {
		$root.find( '.tvd-collapsible' ).each( function () {
			jQuery( this ).collapsible()
		} );
		$root.find( 'select' ).not( '.tvd-browser-default' ).select2();

		$root.find( '.tvd-dropdown-button' ).each( function () {
			jQuery( this ).tvd_dropdown()
		} );
		$root.find( '.tvd-tabs' ).each( function () {
			jQuery( this ).tvd_tabs()
		} );

		//initialize sliders
		$root.find( '.tvd-slider-widget' ).each( function () {
			$( this ).tvd_nouislider();
		} );

		Materialize.updateTextFields();
	};


	/**
	 * show a page loader (or, if a modal is opened, show a loading spinner over that modal)
	 *
	 * @param {Boolean} [force_show_page_loader] if not undefined, show the global page loader
	 */
	TVE_Dash.showLoader = function ( force_show_page_loader ) {

		/**
		 * if a modal view is opened, we show the preloader over the modal view, else we show the global preloader
		 */
		if ( ! force_show_page_loader && TVE_Dash.opened_modal_view ) {
			return TVE_Dash.opened_modal_view.showLoader();
		}

		if ( ! TVE_Dash.page_loader ) {
			TVE_Dash.page_loader = new TVE_Dash.views.PageLoader();
			TVE_Dash.page_loader.render();
		}

		TVE_Dash.page_loader.open();

	};

	/**
	 * hide the page loader, if any
	 */
	TVE_Dash.hideLoader = function () {

		if ( TVE_Dash.opened_modal_view ) {
			TVE_Dash.opened_modal_view.hideLoader();
		}

		if ( TVE_Dash.page_loader ) {
			TVE_Dash.page_loader.close();
		}

	};

	/**
	 * returns the template function or rendered template content for a backbone template
	 *
	 * @param {string} tpl_path path to the template (e.g. dir1/page-loader)
	 * @param {object} [opt] optional. If sent, it will return html content (the rendered template)
	 */
	TVE_Dash.tpl = function ( tpl_path, opt ) {
		var _html = $( 'script#' + tpl_path.replace( /\//g, '-' ) ).html() || '';
		if ( opt ) {
			return _.template( _html )( opt );
		}
		return _.template( _html );
	};

	/**
	 * With the correct html structure this plugin toggles visibility
	 * of html blocks. Implemented to be usually called on backbone view element
	 *
	 * @return null
	 */
	$.fn.tve_toggle_visibility = function () {

		var self = this; //usually this is a backbone view

		this.on( 'click', '.tl-toggle-visibility', function ( e ) {
			var $elem = $( e.currentTarget ), visible = $elem.hasClass( 'tve-visible' ), css = {
				visibility: visible ? 'hidden' : 'visible', height: visible ? 0 : ''
			};

			self.find( $elem.data( 'target' ) ).css( css );
			$elem.toggleClass( 'tve-visible' );
			$elem.toggleClass( 'hover' );
		} );
	};

	/**
	 * mark a card as loading
	 * shows an overlay over the card
	 * @param {object} $card any element from the card or the card itself
	 */
	TVE_Dash.cardLoader = function ( $card ) {
		var _children = $card.find( '.tvd-card' );
		if ( _children.length ) {
			$card = _children;
		}
		$card = $card.closest( '.tvd-card' );

		$card.addClass( 'tvd-preloader-overlay' );
		if ( ! $card.find( '.tvd-card-preloader' ).length ) {
			$card.find( '.tvd-card-content' ).append( '<div class="tvd-card-preloader"><div class="tvd-preloader-wrapper tvd-big tvd-active"><div class="tvd-spinner-layer tvd-spinner-blue-only"><div class="tvd-circle-clipper tvd-left"><div class="tvd-circle"></div></div><div class="tvd-gap-patch"><div class="tvd-circle"></div></div><div class="tvd-circle-clipper tvd-right"><div class="tvd-circle"></div></div></div></div></div>' )
		}
	};

	TVE_Dash.hideCardLoader = function ( $card ) {

		var _children = $card.find( '.tvd-card' );
		if ( _children.length ) {
			$card = _children;
		}
		$card = $card.closest( '.tvd-card' );
		$card.removeClass( 'tvd-preloader-overlay' ).find( '.tvd-card-preloader' ).remove();

	};

	/**
	 * show a toast containing an error message
	 *
	 * @param {string} message error message to be displayed
	 * @param {Number} [duration] optional, duration in milliseconds - defaults to 3000
	 * @param {function} callback optional, a callback to be executed when the toast is hidden
	 */
	TVE_Dash.err = function ( message, duration, callback ) {
		$( '.tvd-toast' ).remove();
		Materialize.toast( message, duration || 3000, 'tvd-toast tvd-red', callback, 'bottom' );
	};

	/**
	 * show a toast containing a success message
	 *
	 * @param {string} message success message to be displayed
	 * @param {Number} [duration] optional, duration in milliseconds - defaults to 3000
	 * @param {function} callback optional, a callback to be executed when the toast is hidden
	 */
	TVE_Dash.success = function ( message, duration, callback ) {
		$( '.tvd-toast' ).remove();
		Materialize.toast( message, duration || 3000, 'tvd-toast tvd-green', callback );
	};

	/**
	 * Function to select card, and deselect all other cards
	 *
	 * @param targetEl element
	 * @param targetSiblings
	 * @param selectedClass class
	 */
	TVE_Dash.select_card = function ( targetEl, targetSiblings, selectedClass ) {

		if ( ! selectedClass ) {
			selectedClass = 'tvd-selected-card';
		}

		targetSiblings.removeClass( selectedClass );
		targetEl.addClass( selectedClass );
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
	TVE_Dash.data_binder = function ( view, model ) {

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
							model.set( prop, _value );
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
	 * Uppercase the 1st letter in string
	 *
	 * @param str
	 * @returns string
	 */
	TVE_Dash.upperFirst = function ( str ) {
		if ( ! str ) {
			return '';
		}

		return str.toLowerCase().charAt( 0 ).toUpperCase() + str.slice( 1 );
	};

})( jQuery );
