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
;/**
 * contains general backbone views definitions
 */

/**
 * utility functions to use throughout Thrive products
 *
 * @type {object}
 */
var TVE_Dash = TVE_Dash || {};
TVE_Dash.views = TVE_Dash.views || {};

(function ( $ ) {

	/**
	 * base class for modals
	 *
	 * backbone view wrapped over a modal div from materialize
	 */
	TVE_Dash.views.Modal = Backbone.View.extend( {
		className: 'tvd-modal',
		id: 'tvd-modal-base',
		/**
		 * .tvd-modal-content div reference
		 */
		$_content: '',
		/* this will be populated with data coming from params when instantiating the view / modal */
		data: {},
		/**
		 * used to setup this.data field - it will set all constructor arguments into this.data so that they are available
		 * @param {object} args
		 */
		initialize: function ( args ) {

			this.beforeInitialize( args );

			this.data = args;
			var self = this;

			_.each( args, function ( v, k ) {

				if ( typeof self[k] === 'undefined' ) {
					self[k] = v;
				}
			} );

			this.afterInitialize( args );
		},
		/**
		 * we should not override this, use afterRender() instead
		 * this does not open the modal, it just renders it and appends it to the body tag
		 * @returns {TVE_Dash.views.Modal}
		 */
		render: function () {
			var self = this;
			this.$el.empty();
			this.$el.appendTo( 'body' );
			// make the whole data object available to the template
			this.data = _.extend( {
				model: this.model
			}, this.data );

			this.$el.html( this.template( this.data ) );

			if ( this.data['max-width'] ) {
				/* max-width should always be sent in % */
				this.$el.css( 'max-width', this.data['max-width'] );
			} else {
				this.$el.css( 'max-width', '' );
			}

			if ( this.data['no_close'] ) {
				this.$el.find( '.tvd-modal-close' ).remove();
			} else if ( ! this.$el.children( '.tvd-modal-close' ).length ) {
				this.$el.append( '<a href="javascript:void(0)" class="tvd-modal-action tvd-modal-close tvd-modal-close-x"><i class="tvd-icon-close2"></i></a>' );
			}

			if ( this.data['title'] ) {
				var $title = this.$el.find( 'h3.tvd-modal-title' );
				if ( ! $title.length ) {
					$title = $( '<h3 class="tvd-modal-title"></h3>' );
					this.$el.find( '.tvd-modal-content' ).prepend( $title );
				}
				$title.html( this.data['title'] );
			}

			if ( this.data['width'] ) {
				this.$el.css( 'width', this.data['width'] );
			}
			if ( this.data['height'] ) {
				this.$el.css( 'height', this.data['height'] + 'px' );
			}

			/**
			 * rebind the wistia listeners
			 */
			if ( window.rebindWistiaFancyBoxes ) {
				window.rebindWistiaFancyBoxes();
			}

			this.$_content = this.$el.find( '.tvd-modal-content' );

			this.afterRender();

			/**
			 * when the autocomplete opens, we need to scroll the .tvd-modal-content so that the results are visible
			 */
			this.$el.on( 'autocompleteopen', _.bind( this.autocomplete_scroll, this ) );

			this.$el.on( 'tvdpickeropen', _.bind( this.pickadate_scroll, this ) );

			return this;
		},
		/**
		 * called when a jquery autocomplete widget is opened inside a modal
		 * scrolls the modal frame to contain as much of the autocomplete element as possible
		 *
		 * @param event
		 * @param ui
		 */
		autocomplete_scroll: function ( event, ui ) {
			var $input = $( event.target ),
				$ul = $input.parent().find( 'ul.ui-autocomplete' ),
				ui_height = $input.outerHeight() + $ul.outerHeight(),
				ui_top = $input.offset().top,
				c_top = this.$_content.offset().top,
				delta_scroll = ui_top - c_top + this.$_content.scrollTop() - 25;

			if ( ui_height + ui_top > this.$_content.outerHeight() + c_top ) {
				this.$_content.animate( {
					scrollTop: delta_scroll
				} );
			}
		},
		/**
		 * called when a datepicker (pickadate) is opened inside this modal
		 * it scroll the modal frame so that the date picker is fully visible
		 *
		 * @param {object} event jQuery event instance
		 * @param {object} P Picker instance
		 */
		pickadate_scroll: function ( event, P ) {
			var $input = $( event.target ),
				$picker = P.$root,
				picker_height = $picker.outerHeight( true ) + 40,
				picker_top = $input.offset().top - picker_height / 2,
				c_top = this.$_content.offset().top;

			if ( picker_top + picker_height > this.$_content.outerHeight() + c_top ) {
				this.$_content.animate( {
					scrollTop: picker_height + picker_top - c_top - this.$_content.outerHeight() + this.$_content.scrollTop() - 25
				} );
			} else if ( picker_top < c_top ) {
				this.$_content.animate( {
					scrollTop: this.$_content.scrollTop() - ( c_top - picker_top )
				} );
			}
		},
		/**
		 * auto-focus the first input and bind the ENTER event
		 *
		 * @param {object} [$root] optional, jquery wrapper over a DOM element
		 */
		input_focus: function ( $root ) {
			$root = $root || this.$el;

			var $inputs = $root.find( 'input:not(.tvd-select-dropdown),textarea,.tve-confirm-delete-action' );
			$inputs.filter( ':visible' ).filter( ':not(.tvd-no-focus)' ).first().focus().select();

			$root.add( $inputs ).not( '.tvd-skip-modal-save' ).off( 'keyup.tvd-save' ).on( 'keyup.tvd-save', function ( e ) {
				if ( e.which === 13 ) {
					if ( this.tagName.toLowerCase() === 'textarea' ) {
						event.preventDefault();
					} else {
						$root.find( '.tvd-modal-submit' ).filter( ':visible' ).first().click();
					}
					return false;
				}
			} );
		},
		/**
		 * triggered immediately after the rendering is completed (after the template is added to DOM) but before showing the modal
		 * override this to populate extra stuff in the view
		 */
		afterRender: function () {
			return this;
		},
		/**
		 * open the modal
		 * options for the modal can be sent when instantiating the view
		 *
		 * @returns {TVE_Dash.Modal}
		 */
		open: function () {
			var self = this;

			var options = _.extend( {
				in_duration: 200,
				out_duration: 300,
				dismissible: true,
				ready: function () {
					self.onOpen.call( self );
					/**
					 * allow also 'afterOpen' callback
					 */
					if ( typeof self.afterOpen === 'function' ) {
						self.afterOpen.call( self );
					}
					TVE_Dash.materialize( self.$el );
					self.input_focus();

					if ( typeof self.afterMaterialize === 'function' ) {
						self.afterMaterialize.call( self );
					}
				},
				complete: function () {
					delete TVE_Dash.opened_modal_view;
					self.$el.css( 'width', '' ).css( 'height', '' );
					self.beforeClose.call( self );
					self.remove();
					self.onClose.call( self );
					/**
					 * allow also 'afterClose' callback
					 */
					if ( typeof self.afterClose === 'function' ) {
						self.afterClose.call( self );
					}
				}
			}, this.data );

			this.modal_options = options;

			this.$el.openModal( options );

			TVE_Dash.opened_modal_view = this;

			return this;
		},
		close: function () {
			this.$el.closeModal( this.modal_options );
			return this;
		},
		/**
		 * triggered after the modal has been opened
		 */
		onOpen: function () {
		},
		/**
		 * triggered after the modal has been closed and the html has been removed
		 */
		onClose: function () {
		},
		/**
		 * triggered before the modal has been closed and the html has been removed
		 */
		beforeClose: function () {
		},
		/**
		 * show a preloader over this modal
		 */
		showLoader: function () {
			var _loader = this.$el.find( '.tvd-modal-preloader' );
			if ( ! _loader.length ) {
				_loader = $( TVE_Dash.tpl( 'modal-loader', {} ) );
				this.$el.prepend( _loader );
			}
			_loader.css( 'top', (
			                    this.$el.height() / 2 + this.$el.scrollTop()
			                    ) + 'px' );
			_loader.fadeIn();
			this.$el.addClass( 'tvd-modal-disable' );

			return this;
		},
		/**
		 * hide the preloader
		 */
		hideLoader: function () {
			this.$el.removeClass( 'tvd-modal-disable' );
			this.$el.find( '.tvd-modal-preloader' ).fadeOut();

			return this;
		},
		/**
		 * set the loading state on a button
		 * @param $btn
		 * @returns {*}
		 */
		btnLoading: function ( $btn ) {
			return jQuery( $btn ).addClass( 'tvd-disabled' ).prop( 'disabled', true );
		},
		beforeInitialize: function ( args ) {
			return this;
		},
		afterInitialize: function ( args ) {
			return this;
		}
	} );

	/**
	 * Modal Steps View
	 */
	TVE_Dash.views.ModalSteps = TVE_Dash.views.Modal.extend( {
		stepClass: '.tvd-modal-step',
		currentStep: 0,
		$step: null,
		events: {
			'click .tvd-modal-next-step': "next",
			'click .tvd-modal-prev-step': "prev"
		},
		afterRender: function () {
			this.steps = this.$el.find( this.stepClass ).hide();
			this.gotoStep( 0 );
			return this;
		},
		gotoStep: function ( index ) {
			var step = this.steps.hide().eq( index ).show(),
				self = this;
			this.$step = step;
			setTimeout( function () {
				self.input_focus( step );
			}, 50 );

			this.currentStep = index;

			return this;
		},
		next: function () {
			if ( this.beforeNext() === false ) {
				return;
			}
			this.gotoStep( this.currentStep + 1 );
			this.afterNext();
		},
		prev: function () {
			this.beforePrev();
			this.gotoStep( this.currentStep - 1 );
			this.afterPrev();
		},
		beforeNext: function () {
			return this;
		},
		afterNext: function () {
			return this;
		},
		beforePrev: function () {
			return this;
		},
		afterPrev: function () {
			return this;
		}
	} );

	/**
	 * page loader view
	 * shows a global page loader
	 */
	TVE_Dash.views.PageLoader = Backbone.View.extend( {
		_state: 'closed',
		render: function () {
			this.$el.html( TVE_Dash.tpl( 'page-loader', {} ) );
			this.$el.appendTo( 'body' );
			this.loader = this.$el.find( '#tvd-hide-onload' );
		},
		open: function () {
			this.loader.show();
		},
		close: function () {
			var self = this;
			setTimeout( function () {
				self.loader.fadeOut( 100 );
			}, 200 );
		}
	} );

})( jQuery );;/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 *
 * Open source under the BSD License.
 *
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */;    // Custom Easing
    jQuery.extend( jQuery.easing,
    {
      easeInOutMaterial: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t + b;
        return c/4*((t-=2)*t*t + 2) + b;
      }
    });

;/*! VelocityJS.org (1.2.3). (C) 2014 Julian Shapiro. MIT @license: en.wikipedia.org/wiki/MIT_License */
/*! VelocityJS.org jQuery Shim (1.0.1). (C) 2014 The jQuery Foundation. MIT @license: en.wikipedia.org/wiki/MIT_License. */
/*! Note that this has been modified by Materialize to confirm that Velocity is not already being imported. */
jQuery.Velocity?console.log("Velocity is already loaded. You may be needlessly importing Velocity again; note that Materialize includes Velocity."):(!function(e){function t(e){var t=e.length,a=r.type(e);return"function"===a||r.isWindow(e)?!1:1===e.nodeType&&t?!0:"array"===a||0===t||"number"==typeof t&&t>0&&t-1 in e}if(!e.jQuery){var r=function(e,t){return new r.fn.init(e,t)};r.isWindow=function(e){return null!=e&&e==e.window},r.type=function(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?n[i.call(e)]||"object":typeof e},r.isArray=Array.isArray||function(e){return"array"===r.type(e)},r.isPlainObject=function(e){var t;if(!e||"object"!==r.type(e)||e.nodeType||r.isWindow(e))return!1;try{if(e.constructor&&!o.call(e,"constructor")&&!o.call(e.constructor.prototype,"isPrototypeOf"))return!1}catch(a){return!1}for(t in e);return void 0===t||o.call(e,t)},r.each=function(e,r,a){var n,o=0,i=e.length,s=t(e);if(a){if(s)for(;i>o&&(n=r.apply(e[o],a),n!==!1);o++);else for(o in e)if(n=r.apply(e[o],a),n===!1)break}else if(s)for(;i>o&&(n=r.call(e[o],o,e[o]),n!==!1);o++);else for(o in e)if(n=r.call(e[o],o,e[o]),n===!1)break;return e},r.data=function(e,t,n){if(void 0===n){var o=e[r.expando],i=o&&a[o];if(void 0===t)return i;if(i&&t in i)return i[t]}else if(void 0!==t){var o=e[r.expando]||(e[r.expando]=++r.uuid);return a[o]=a[o]||{},a[o][t]=n,n}},r.removeData=function(e,t){var n=e[r.expando],o=n&&a[n];o&&r.each(t,function(e,t){delete o[t]})},r.extend=function(){var e,t,a,n,o,i,s=arguments[0]||{},l=1,u=arguments.length,c=!1;for("boolean"==typeof s&&(c=s,s=arguments[l]||{},l++),"object"!=typeof s&&"function"!==r.type(s)&&(s={}),l===u&&(s=this,l--);u>l;l++)if(null!=(o=arguments[l]))for(n in o)e=s[n],a=o[n],s!==a&&(c&&a&&(r.isPlainObject(a)||(t=r.isArray(a)))?(t?(t=!1,i=e&&r.isArray(e)?e:[]):i=e&&r.isPlainObject(e)?e:{},s[n]=r.extend(c,i,a)):void 0!==a&&(s[n]=a));return s},r.queue=function(e,a,n){function o(e,r){var a=r||[];return null!=e&&(t(Object(e))?!function(e,t){for(var r=+t.length,a=0,n=e.length;r>a;)e[n++]=t[a++];if(r!==r)for(;void 0!==t[a];)e[n++]=t[a++];return e.length=n,e}(a,"string"==typeof e?[e]:e):[].push.call(a,e)),a}if(e){a=(a||"fx")+"queue";var i=r.data(e,a);return n?(!i||r.isArray(n)?i=r.data(e,a,o(n)):i.push(n),i):i||[]}},r.dequeue=function(e,t){r.each(e.nodeType?[e]:e,function(e,a){t=t||"fx";var n=r.queue(a,t),o=n.shift();"inprogress"===o&&(o=n.shift()),o&&("fx"===t&&n.unshift("inprogress"),o.call(a,function(){r.dequeue(a,t)}))})},r.fn=r.prototype={init:function(e){if(e.nodeType)return this[0]=e,this;throw new Error("Not a DOM node.")},offset:function(){var t=this[0].getBoundingClientRect?this[0].getBoundingClientRect():{top:0,left:0};return{top:t.top+(e.pageYOffset||document.scrollTop||0)-(document.clientTop||0),left:t.left+(e.pageXOffset||document.scrollLeft||0)-(document.clientLeft||0)}},position:function(){function e(){for(var e=this.offsetParent||document;e&&"html"===!e.nodeType.toLowerCase&&"static"===e.style.position;)e=e.offsetParent;return e||document}var t=this[0],e=e.apply(t),a=this.offset(),n=/^(?:body|html)$/i.test(e.nodeName)?{top:0,left:0}:r(e).offset();return a.top-=parseFloat(t.style.marginTop)||0,a.left-=parseFloat(t.style.marginLeft)||0,e.style&&(n.top+=parseFloat(e.style.borderTopWidth)||0,n.left+=parseFloat(e.style.borderLeftWidth)||0),{top:a.top-n.top,left:a.left-n.left}}};var a={};r.expando="velocity"+(new Date).getTime(),r.uuid=0;for(var n={},o=n.hasOwnProperty,i=n.toString,s="Boolean Number String Function Array Date RegExp Object Error".split(" "),l=0;l<s.length;l++)n["[object "+s[l]+"]"]=s[l].toLowerCase();r.fn.init.prototype=r.fn,e.Velocity={Utilities:r}}}(window),function(e){"object"==typeof module&&"object"==typeof module.exports?module.exports=e():"function"==typeof define&&define.amd?define(e):e()}(function(){return function(e,t,r,a){function n(e){for(var t=-1,r=e?e.length:0,a=[];++t<r;){var n=e[t];n&&a.push(n)}return a}function o(e){return m.isWrapped(e)?e=[].slice.call(e):m.isNode(e)&&(e=[e]),e}function i(e){var t=f.data(e,"velocity");return null===t?a:t}function s(e){return function(t){return Math.round(t*e)*(1/e)}}function l(e,r,a,n){function o(e,t){return 1-3*t+3*e}function i(e,t){return 3*t-6*e}function s(e){return 3*e}function l(e,t,r){return((o(t,r)*e+i(t,r))*e+s(t))*e}function u(e,t,r){return 3*o(t,r)*e*e+2*i(t,r)*e+s(t)}function c(t,r){for(var n=0;m>n;++n){var o=u(r,e,a);if(0===o)return r;var i=l(r,e,a)-t;r-=i/o}return r}function p(){for(var t=0;b>t;++t)w[t]=l(t*x,e,a)}function f(t,r,n){var o,i,s=0;do i=r+(n-r)/2,o=l(i,e,a)-t,o>0?n=i:r=i;while(Math.abs(o)>h&&++s<v);return i}function d(t){for(var r=0,n=1,o=b-1;n!=o&&w[n]<=t;++n)r+=x;--n;var i=(t-w[n])/(w[n+1]-w[n]),s=r+i*x,l=u(s,e,a);return l>=y?c(t,s):0==l?s:f(t,r,r+x)}function g(){V=!0,(e!=r||a!=n)&&p()}var m=4,y=.001,h=1e-7,v=10,b=11,x=1/(b-1),S="Float32Array"in t;if(4!==arguments.length)return!1;for(var P=0;4>P;++P)if("number"!=typeof arguments[P]||isNaN(arguments[P])||!isFinite(arguments[P]))return!1;e=Math.min(e,1),a=Math.min(a,1),e=Math.max(e,0),a=Math.max(a,0);var w=S?new Float32Array(b):new Array(b),V=!1,C=function(t){return V||g(),e===r&&a===n?t:0===t?0:1===t?1:l(d(t),r,n)};C.getControlPoints=function(){return[{x:e,y:r},{x:a,y:n}]};var T="generateBezier("+[e,r,a,n]+")";return C.toString=function(){return T},C}function u(e,t){var r=e;return m.isString(e)?b.Easings[e]||(r=!1):r=m.isArray(e)&&1===e.length?s.apply(null,e):m.isArray(e)&&2===e.length?x.apply(null,e.concat([t])):m.isArray(e)&&4===e.length?l.apply(null,e):!1,r===!1&&(r=b.Easings[b.defaults.easing]?b.defaults.easing:v),r}function c(e){if(e){var t=(new Date).getTime(),r=b.State.calls.length;r>1e4&&(b.State.calls=n(b.State.calls));for(var o=0;r>o;o++)if(b.State.calls[o]){var s=b.State.calls[o],l=s[0],u=s[2],d=s[3],g=!!d,y=null;d||(d=b.State.calls[o][3]=t-16);for(var h=Math.min((t-d)/u.duration,1),v=0,x=l.length;x>v;v++){var P=l[v],V=P.element;if(i(V)){var C=!1;if(u.display!==a&&null!==u.display&&"none"!==u.display){if("flex"===u.display){var T=["-webkit-box","-moz-box","-ms-flexbox","-webkit-flex"];f.each(T,function(e,t){S.setPropertyValue(V,"display",t)})}S.setPropertyValue(V,"display",u.display)}u.visibility!==a&&"hidden"!==u.visibility&&S.setPropertyValue(V,"visibility",u.visibility);for(var k in P)if("element"!==k){var A,F=P[k],j=m.isString(F.easing)?b.Easings[F.easing]:F.easing;if(1===h)A=F.endValue;else{var E=F.endValue-F.startValue;if(A=F.startValue+E*j(h,u,E),!g&&A===F.currentValue)continue}if(F.currentValue=A,"tween"===k)y=A;else{if(S.Hooks.registered[k]){var H=S.Hooks.getRoot(k),N=i(V).rootPropertyValueCache[H];N&&(F.rootPropertyValue=N)}var L=S.setPropertyValue(V,k,F.currentValue+(0===parseFloat(A)?"":F.unitType),F.rootPropertyValue,F.scrollData);S.Hooks.registered[k]&&(i(V).rootPropertyValueCache[H]=S.Normalizations.registered[H]?S.Normalizations.registered[H]("extract",null,L[1]):L[1]),"transform"===L[0]&&(C=!0)}}u.mobileHA&&i(V).transformCache.translate3d===a&&(i(V).transformCache.translate3d="(0px, 0px, 0px)",C=!0),C&&S.flushTransformCache(V)}}u.display!==a&&"none"!==u.display&&(b.State.calls[o][2].display=!1),u.visibility!==a&&"hidden"!==u.visibility&&(b.State.calls[o][2].visibility=!1),u.progress&&u.progress.call(s[1],s[1],h,Math.max(0,d+u.duration-t),d,y),1===h&&p(o)}}b.State.isTicking&&w(c)}function p(e,t){if(!b.State.calls[e])return!1;for(var r=b.State.calls[e][0],n=b.State.calls[e][1],o=b.State.calls[e][2],s=b.State.calls[e][4],l=!1,u=0,c=r.length;c>u;u++){var p=r[u].element;if(t||o.loop||("none"===o.display&&S.setPropertyValue(p,"display",o.display),"hidden"===o.visibility&&S.setPropertyValue(p,"visibility",o.visibility)),o.loop!==!0&&(f.queue(p)[1]===a||!/\.velocityQueueEntryFlag/i.test(f.queue(p)[1]))&&i(p)){i(p).isAnimating=!1,i(p).rootPropertyValueCache={};var d=!1;f.each(S.Lists.transforms3D,function(e,t){var r=/^scale/.test(t)?1:0,n=i(p).transformCache[t];i(p).transformCache[t]!==a&&new RegExp("^\\("+r+"[^.]").test(n)&&(d=!0,delete i(p).transformCache[t])}),o.mobileHA&&(d=!0,delete i(p).transformCache.translate3d),d&&S.flushTransformCache(p),S.Values.removeClass(p,"velocity-animating")}if(!t&&o.complete&&!o.loop&&u===c-1)try{o.complete.call(n,n)}catch(g){setTimeout(function(){throw g},1)}s&&o.loop!==!0&&s(n),i(p)&&o.loop===!0&&!t&&(f.each(i(p).tweensContainer,function(e,t){/^rotate/.test(e)&&360===parseFloat(t.endValue)&&(t.endValue=0,t.startValue=360),/^backgroundPosition/.test(e)&&100===parseFloat(t.endValue)&&"%"===t.unitType&&(t.endValue=0,t.startValue=100)}),b(p,"reverse",{loop:!0,delay:o.delay})),o.queue!==!1&&f.dequeue(p,o.queue)}b.State.calls[e]=!1;for(var m=0,y=b.State.calls.length;y>m;m++)if(b.State.calls[m]!==!1){l=!0;break}l===!1&&(b.State.isTicking=!1,delete b.State.calls,b.State.calls=[])}var f,d=function(){if(r.documentMode)return r.documentMode;for(var e=7;e>4;e--){var t=r.createElement("div");if(t.innerHTML="<!--[if IE "+e+"]><span></span><![endif]-->",t.getElementsByTagName("span").length)return t=null,e}return a}(),g=function(){var e=0;return t.webkitRequestAnimationFrame||t.mozRequestAnimationFrame||function(t){var r,a=(new Date).getTime();return r=Math.max(0,16-(a-e)),e=a+r,setTimeout(function(){t(a+r)},r)}}(),m={isString:function(e){return"string"==typeof e},isArray:Array.isArray||function(e){return"[object Array]"===Object.prototype.toString.call(e)},isFunction:function(e){return"[object Function]"===Object.prototype.toString.call(e)},isNode:function(e){return e&&e.nodeType},isNodeList:function(e){return"object"==typeof e&&/^\[object (HTMLCollection|NodeList|Object)\]$/.test(Object.prototype.toString.call(e))&&e.length!==a&&(0===e.length||"object"==typeof e[0]&&e[0].nodeType>0)},isWrapped:function(e){return e&&(e.jquery||t.Zepto&&t.Zepto.zepto.isZ(e))},isSVG:function(e){return t.SVGElement&&e instanceof t.SVGElement},isEmptyObject:function(e){for(var t in e)return!1;return!0}},y=!1;if(e.fn&&e.fn.jquery?(f=e,y=!0):f=t.Velocity.Utilities,8>=d&&!y)throw new Error("Velocity: IE8 and below require jQuery to be loaded before Velocity.");if(7>=d)return void(jQuery.fn.velocity=jQuery.fn.animate);var h=400,v="swing",b={State:{isMobile:/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),isAndroid:/Android/i.test(navigator.userAgent),isGingerbread:/Android 2\.3\.[3-7]/i.test(navigator.userAgent),isChrome:t.chrome,isFirefox:/Firefox/i.test(navigator.userAgent),prefixElement:r.createElement("div"),prefixMatches:{},scrollAnchor:null,scrollPropertyLeft:null,scrollPropertyTop:null,isTicking:!1,calls:[]},CSS:{},Utilities:f,Redirects:{},Easings:{},Promise:t.Promise,defaults:{queue:"",duration:h,easing:v,begin:a,complete:a,progress:a,display:a,visibility:a,loop:!1,delay:!1,mobileHA:!0,_cacheValues:!0},init:function(e){f.data(e,"velocity",{isSVG:m.isSVG(e),isAnimating:!1,computedStyle:null,tweensContainer:null,rootPropertyValueCache:{},transformCache:{}})},hook:null,mock:!1,version:{major:1,minor:2,patch:2},debug:!1};t.pageYOffset!==a?(b.State.scrollAnchor=t,b.State.scrollPropertyLeft="pageXOffset",b.State.scrollPropertyTop="pageYOffset"):(b.State.scrollAnchor=r.documentElement||r.body.parentNode||r.body,b.State.scrollPropertyLeft="scrollLeft",b.State.scrollPropertyTop="scrollTop");var x=function(){function e(e){return-e.tension*e.x-e.friction*e.v}function t(t,r,a){var n={x:t.x+a.dx*r,v:t.v+a.dv*r,tension:t.tension,friction:t.friction};return{dx:n.v,dv:e(n)}}function r(r,a){var n={dx:r.v,dv:e(r)},o=t(r,.5*a,n),i=t(r,.5*a,o),s=t(r,a,i),l=1/6*(n.dx+2*(o.dx+i.dx)+s.dx),u=1/6*(n.dv+2*(o.dv+i.dv)+s.dv);return r.x=r.x+l*a,r.v=r.v+u*a,r}return function a(e,t,n){var o,i,s,l={x:-1,v:0,tension:null,friction:null},u=[0],c=0,p=1e-4,f=.016;for(e=parseFloat(e)||500,t=parseFloat(t)||20,n=n||null,l.tension=e,l.friction=t,o=null!==n,o?(c=a(e,t),i=c/n*f):i=f;s=r(s||l,i),u.push(1+s.x),c+=16,Math.abs(s.x)>p&&Math.abs(s.v)>p;);return o?function(e){return u[e*(u.length-1)|0]}:c}}();b.Easings={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2},spring:function(e){return 1-Math.cos(4.5*e*Math.PI)*Math.exp(6*-e)}},f.each([["ease",[.25,.1,.25,1]],["ease-in",[.42,0,1,1]],["ease-out",[0,0,.58,1]],["ease-in-out",[.42,0,.58,1]],["easeInSine",[.47,0,.745,.715]],["easeOutSine",[.39,.575,.565,1]],["easeInOutSine",[.445,.05,.55,.95]],["easeInQuad",[.55,.085,.68,.53]],["easeOutQuad",[.25,.46,.45,.94]],["easeInOutQuad",[.455,.03,.515,.955]],["easeInCubic",[.55,.055,.675,.19]],["easeOutCubic",[.215,.61,.355,1]],["easeInOutCubic",[.645,.045,.355,1]],["easeInQuart",[.895,.03,.685,.22]],["easeOutQuart",[.165,.84,.44,1]],["easeInOutQuart",[.77,0,.175,1]],["easeInQuint",[.755,.05,.855,.06]],["easeOutQuint",[.23,1,.32,1]],["easeInOutQuint",[.86,0,.07,1]],["easeInExpo",[.95,.05,.795,.035]],["easeOutExpo",[.19,1,.22,1]],["easeInOutExpo",[1,0,0,1]],["easeInCirc",[.6,.04,.98,.335]],["easeOutCirc",[.075,.82,.165,1]],["easeInOutCirc",[.785,.135,.15,.86]]],function(e,t){b.Easings[t[0]]=l.apply(null,t[1])});var S=b.CSS={RegEx:{isHex:/^#([A-f\d]{3}){1,2}$/i,valueUnwrap:/^[A-z]+\((.*)\)$/i,wrappedValueAlreadyExtracted:/[0-9.]+ [0-9.]+ [0-9.]+( [0-9.]+)?/,valueSplit:/([A-z]+\(.+\))|(([A-z0-9#-.]+?)(?=\s|$))/gi},Lists:{colors:["fill","stroke","stopColor","color","backgroundColor","borderColor","borderTopColor","borderRightColor","borderBottomColor","borderLeftColor","outlineColor"],transformsBase:["translateX","translateY","scale","scaleX","scaleY","skewX","skewY","rotateZ"],transforms3D:["transformPerspective","translateZ","scaleZ","rotateX","rotateY"]},Hooks:{templates:{textShadow:["Color X Y Blur","black 0px 0px 0px"],boxShadow:["Color X Y Blur Spread","black 0px 0px 0px 0px"],clip:["Top Right Bottom Left","0px 0px 0px 0px"],backgroundPosition:["X Y","0% 0%"],transformOrigin:["X Y Z","50% 50% 0px"],perspectiveOrigin:["X Y","50% 50%"]},registered:{},register:function(){for(var e=0;e<S.Lists.colors.length;e++){var t="color"===S.Lists.colors[e]?"0 0 0 1":"255 255 255 1";S.Hooks.templates[S.Lists.colors[e]]=["Red Green Blue Alpha",t]}var r,a,n;if(d)for(r in S.Hooks.templates){a=S.Hooks.templates[r],n=a[0].split(" ");var o=a[1].match(S.RegEx.valueSplit);"Color"===n[0]&&(n.push(n.shift()),o.push(o.shift()),S.Hooks.templates[r]=[n.join(" "),o.join(" ")])}for(r in S.Hooks.templates){a=S.Hooks.templates[r],n=a[0].split(" ");for(var e in n){var i=r+n[e],s=e;S.Hooks.registered[i]=[r,s]}}},getRoot:function(e){var t=S.Hooks.registered[e];return t?t[0]:e},cleanRootPropertyValue:function(e,t){return S.RegEx.valueUnwrap.test(t)&&(t=t.match(S.RegEx.valueUnwrap)[1]),S.Values.isCSSNullValue(t)&&(t=S.Hooks.templates[e][1]),t},extractValue:function(e,t){var r=S.Hooks.registered[e];if(r){var a=r[0],n=r[1];return t=S.Hooks.cleanRootPropertyValue(a,t),t.toString().match(S.RegEx.valueSplit)[n]}return t},injectValue:function(e,t,r){var a=S.Hooks.registered[e];if(a){var n,o,i=a[0],s=a[1];return r=S.Hooks.cleanRootPropertyValue(i,r),n=r.toString().match(S.RegEx.valueSplit),n[s]=t,o=n.join(" ")}return r}},Normalizations:{registered:{clip:function(e,t,r){switch(e){case"name":return"clip";case"extract":var a;return S.RegEx.wrappedValueAlreadyExtracted.test(r)?a=r:(a=r.toString().match(S.RegEx.valueUnwrap),a=a?a[1].replace(/,(\s+)?/g," "):r),a;case"inject":return"rect("+r+")"}},blur:function(e,t,r){switch(e){case"name":return b.State.isFirefox?"filter":"-webkit-filter";case"extract":var a=parseFloat(r);if(!a&&0!==a){var n=r.toString().match(/blur\(([0-9]+[A-z]+)\)/i);a=n?n[1]:0}return a;case"inject":return parseFloat(r)?"blur("+r+")":"none"}},opacity:function(e,t,r){if(8>=d)switch(e){case"name":return"filter";case"extract":var a=r.toString().match(/alpha\(opacity=(.*)\)/i);return r=a?a[1]/100:1;case"inject":return t.style.zoom=1,parseFloat(r)>=1?"":"alpha(opacity="+parseInt(100*parseFloat(r),10)+")"}else switch(e){case"name":return"opacity";case"extract":return r;case"inject":return r}}},register:function(){9>=d||b.State.isGingerbread||(S.Lists.transformsBase=S.Lists.transformsBase.concat(S.Lists.transforms3D));for(var e=0;e<S.Lists.transformsBase.length;e++)!function(){var t=S.Lists.transformsBase[e];S.Normalizations.registered[t]=function(e,r,n){switch(e){case"name":return"transform";case"extract":return i(r)===a||i(r).transformCache[t]===a?/^scale/i.test(t)?1:0:i(r).transformCache[t].replace(/[()]/g,"");case"inject":var o=!1;switch(t.substr(0,t.length-1)){case"translate":o=!/(%|px|em|rem|vw|vh|\d)$/i.test(n);break;case"scal":case"scale":b.State.isAndroid&&i(r).transformCache[t]===a&&1>n&&(n=1),o=!/(\d)$/i.test(n);break;case"skew":o=!/(deg|\d)$/i.test(n);break;case"rotate":o=!/(deg|\d)$/i.test(n)}return o||(i(r).transformCache[t]="("+n+")"),i(r).transformCache[t]}}}();for(var e=0;e<S.Lists.colors.length;e++)!function(){var t=S.Lists.colors[e];S.Normalizations.registered[t]=function(e,r,n){switch(e){case"name":return t;case"extract":var o;if(S.RegEx.wrappedValueAlreadyExtracted.test(n))o=n;else{var i,s={black:"rgb(0, 0, 0)",blue:"rgb(0, 0, 255)",gray:"rgb(128, 128, 128)",green:"rgb(0, 128, 0)",red:"rgb(255, 0, 0)",white:"rgb(255, 255, 255)"};/^[A-z]+$/i.test(n)?i=s[n]!==a?s[n]:s.black:S.RegEx.isHex.test(n)?i="rgb("+S.Values.hexToRgb(n).join(" ")+")":/^rgba?\(/i.test(n)||(i=s.black),o=(i||n).toString().match(S.RegEx.valueUnwrap)[1].replace(/,(\s+)?/g," ")}return 8>=d||3!==o.split(" ").length||(o+=" 1"),o;case"inject":return 8>=d?4===n.split(" ").length&&(n=n.split(/\s+/).slice(0,3).join(" ")):3===n.split(" ").length&&(n+=" 1"),(8>=d?"rgb":"rgba")+"("+n.replace(/\s+/g,",").replace(/\.(\d)+(?=,)/g,"")+")"}}}()}},Names:{camelCase:function(e){return e.replace(/-(\w)/g,function(e,t){return t.toUpperCase()})},SVGAttribute:function(e){var t="width|height|x|y|cx|cy|r|rx|ry|x1|x2|y1|y2";return(d||b.State.isAndroid&&!b.State.isChrome)&&(t+="|transform"),new RegExp("^("+t+")$","i").test(e)},prefixCheck:function(e){if(b.State.prefixMatches[e])return[b.State.prefixMatches[e],!0];for(var t=["","Webkit","Moz","ms","O"],r=0,a=t.length;a>r;r++){var n;if(n=0===r?e:t[r]+e.replace(/^\w/,function(e){return e.toUpperCase()}),m.isString(b.State.prefixElement.style[n]))return b.State.prefixMatches[e]=n,[n,!0]}return[e,!1]}},Values:{hexToRgb:function(e){var t,r=/^#?([a-f\d])([a-f\d])([a-f\d])$/i,a=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i;return e=e.replace(r,function(e,t,r,a){return t+t+r+r+a+a}),t=a.exec(e),t?[parseInt(t[1],16),parseInt(t[2],16),parseInt(t[3],16)]:[0,0,0]},isCSSNullValue:function(e){return 0==e||/^(none|auto|transparent|(rgba\(0, ?0, ?0, ?0\)))$/i.test(e)},getUnitType:function(e){return/^(rotate|skew)/i.test(e)?"deg":/(^(scale|scaleX|scaleY|scaleZ|alpha|flexGrow|flexHeight|zIndex|fontWeight)$)|((opacity|red|green|blue|alpha)$)/i.test(e)?"":"px"},getDisplayType:function(e){var t=e&&e.tagName.toString().toLowerCase();return/^(b|big|i|small|tt|abbr|acronym|cite|code|dfn|em|kbd|strong|samp|var|a|bdo|br|img|map|object|q|script|span|sub|sup|button|input|label|select|textarea)$/i.test(t)?"inline":/^(li)$/i.test(t)?"list-item":/^(tr)$/i.test(t)?"table-row":/^(table)$/i.test(t)?"table":/^(tbody)$/i.test(t)?"table-row-group":"block"},addClass:function(e,t){e.classList?e.classList.add(t):e.className+=(e.className.length?" ":"")+t},removeClass:function(e,t){e.classList?e.classList.remove(t):e.className=e.className.toString().replace(new RegExp("(^|\\s)"+t.split(" ").join("|")+"(\\s|$)","gi")," ")}},getPropertyValue:function(e,r,n,o){function s(e,r){function n(){u&&S.setPropertyValue(e,"display","none")}var l=0;if(8>=d)l=f.css(e,r);else{var u=!1;if(/^(width|height)$/.test(r)&&0===S.getPropertyValue(e,"display")&&(u=!0,S.setPropertyValue(e,"display",S.Values.getDisplayType(e))),!o){if("height"===r&&"border-box"!==S.getPropertyValue(e,"boxSizing").toString().toLowerCase()){var c=e.offsetHeight-(parseFloat(S.getPropertyValue(e,"borderTopWidth"))||0)-(parseFloat(S.getPropertyValue(e,"borderBottomWidth"))||0)-(parseFloat(S.getPropertyValue(e,"paddingTop"))||0)-(parseFloat(S.getPropertyValue(e,"paddingBottom"))||0);return n(),c}if("width"===r&&"border-box"!==S.getPropertyValue(e,"boxSizing").toString().toLowerCase()){var p=e.offsetWidth-(parseFloat(S.getPropertyValue(e,"borderLeftWidth"))||0)-(parseFloat(S.getPropertyValue(e,"borderRightWidth"))||0)-(parseFloat(S.getPropertyValue(e,"paddingLeft"))||0)-(parseFloat(S.getPropertyValue(e,"paddingRight"))||0);return n(),p}}var g;g=i(e)===a?t.getComputedStyle(e,null):i(e).computedStyle?i(e).computedStyle:i(e).computedStyle=t.getComputedStyle(e,null),"borderColor"===r&&(r="borderTopColor"),l=9===d&&"filter"===r?g.getPropertyValue(r):g[r],(""===l||null===l)&&(l=e.style[r]),n()}if("auto"===l&&/^(top|right|bottom|left)$/i.test(r)){var m=s(e,"position");("fixed"===m||"absolute"===m&&/top|left/i.test(r))&&(l=f(e).position()[r]+"px")}return l}var l;if(S.Hooks.registered[r]){var u=r,c=S.Hooks.getRoot(u);n===a&&(n=S.getPropertyValue(e,S.Names.prefixCheck(c)[0])),S.Normalizations.registered[c]&&(n=S.Normalizations.registered[c]("extract",e,n)),l=S.Hooks.extractValue(u,n)}else if(S.Normalizations.registered[r]){var p,g;p=S.Normalizations.registered[r]("name",e),"transform"!==p&&(g=s(e,S.Names.prefixCheck(p)[0]),S.Values.isCSSNullValue(g)&&S.Hooks.templates[r]&&(g=S.Hooks.templates[r][1])),l=S.Normalizations.registered[r]("extract",e,g)}if(!/^[\d-]/.test(l))if(i(e)&&i(e).isSVG&&S.Names.SVGAttribute(r))if(/^(height|width)$/i.test(r))try{l=e.getBBox()[r]}catch(m){l=0}else l=e.getAttribute(r);else l=s(e,S.Names.prefixCheck(r)[0]);return S.Values.isCSSNullValue(l)&&(l=0),b.debug>=2&&console.log("Get "+r+": "+l),l},setPropertyValue:function(e,r,a,n,o){var s=r;if("scroll"===r)o.container?o.container["scroll"+o.direction]=a:"Left"===o.direction?t.scrollTo(a,o.alternateValue):t.scrollTo(o.alternateValue,a);else if(S.Normalizations.registered[r]&&"transform"===S.Normalizations.registered[r]("name",e))S.Normalizations.registered[r]("inject",e,a),s="transform",a=i(e).transformCache[r];else{if(S.Hooks.registered[r]){var l=r,u=S.Hooks.getRoot(r);n=n||S.getPropertyValue(e,u),a=S.Hooks.injectValue(l,a,n),r=u}if(S.Normalizations.registered[r]&&(a=S.Normalizations.registered[r]("inject",e,a),r=S.Normalizations.registered[r]("name",e)),s=S.Names.prefixCheck(r)[0],8>=d)try{e.style[s]=a}catch(c){b.debug&&console.log("Browser does not support ["+a+"] for ["+s+"]")}else i(e)&&i(e).isSVG&&S.Names.SVGAttribute(r)?e.setAttribute(r,a):e.style[s]=a;b.debug>=2&&console.log("Set "+r+" ("+s+"): "+a)}return[s,a]},flushTransformCache:function(e){function t(t){return parseFloat(S.getPropertyValue(e,t))}var r="";if((d||b.State.isAndroid&&!b.State.isChrome)&&i(e).isSVG){var a={translate:[t("translateX"),t("translateY")],skewX:[t("skewX")],skewY:[t("skewY")],scale:1!==t("scale")?[t("scale"),t("scale")]:[t("scaleX"),t("scaleY")],rotate:[t("rotateZ"),0,0]};f.each(i(e).transformCache,function(e){/^translate/i.test(e)?e="translate":/^scale/i.test(e)?e="scale":/^rotate/i.test(e)&&(e="rotate"),a[e]&&(r+=e+"("+a[e].join(" ")+") ",delete a[e])})}else{var n,o;f.each(i(e).transformCache,function(t){return n=i(e).transformCache[t],"transformPerspective"===t?(o=n,!0):(9===d&&"rotateZ"===t&&(t="rotate"),void(r+=t+n+" "))}),o&&(r="perspective"+o+" "+r)}S.setPropertyValue(e,"transform",r)}};S.Hooks.register(),S.Normalizations.register(),b.hook=function(e,t,r){var n=a;return e=o(e),f.each(e,function(e,o){if(i(o)===a&&b.init(o),r===a)n===a&&(n=b.CSS.getPropertyValue(o,t));else{var s=b.CSS.setPropertyValue(o,t,r);"transform"===s[0]&&b.CSS.flushTransformCache(o),n=s}}),n};var P=function(){function e(){return s?k.promise||null:l}function n(){function e(e){function p(e,t){var r=a,n=a,i=a;return m.isArray(e)?(r=e[0],!m.isArray(e[1])&&/^[\d-]/.test(e[1])||m.isFunction(e[1])||S.RegEx.isHex.test(e[1])?i=e[1]:(m.isString(e[1])&&!S.RegEx.isHex.test(e[1])||m.isArray(e[1]))&&(n=t?e[1]:u(e[1],s.duration),e[2]!==a&&(i=e[2]))):r=e,t||(n=n||s.easing),m.isFunction(r)&&(r=r.call(o,V,w)),m.isFunction(i)&&(i=i.call(o,V,w)),[r||0,n,i]}function d(e,t){var r,a;return a=(t||"0").toString().toLowerCase().replace(/[%A-z]+$/,function(e){return r=e,""}),r||(r=S.Values.getUnitType(e)),[a,r]}function h(){var e={myParent:o.parentNode||r.body,position:S.getPropertyValue(o,"position"),fontSize:S.getPropertyValue(o,"fontSize")},a=e.position===L.lastPosition&&e.myParent===L.lastParent,n=e.fontSize===L.lastFontSize;L.lastParent=e.myParent,L.lastPosition=e.position,L.lastFontSize=e.fontSize;var s=100,l={};if(n&&a)l.emToPx=L.lastEmToPx,l.percentToPxWidth=L.lastPercentToPxWidth,l.percentToPxHeight=L.lastPercentToPxHeight;else{var u=i(o).isSVG?r.createElementNS("http://www.w3.org/2000/svg","rect"):r.createElement("div");b.init(u),e.myParent.appendChild(u),f.each(["overflow","overflowX","overflowY"],function(e,t){b.CSS.setPropertyValue(u,t,"hidden")}),b.CSS.setPropertyValue(u,"position",e.position),b.CSS.setPropertyValue(u,"fontSize",e.fontSize),b.CSS.setPropertyValue(u,"boxSizing","content-box"),f.each(["minWidth","maxWidth","width","minHeight","maxHeight","height"],function(e,t){b.CSS.setPropertyValue(u,t,s+"%")}),b.CSS.setPropertyValue(u,"paddingLeft",s+"em"),l.percentToPxWidth=L.lastPercentToPxWidth=(parseFloat(S.getPropertyValue(u,"width",null,!0))||1)/s,l.percentToPxHeight=L.lastPercentToPxHeight=(parseFloat(S.getPropertyValue(u,"height",null,!0))||1)/s,l.emToPx=L.lastEmToPx=(parseFloat(S.getPropertyValue(u,"paddingLeft"))||1)/s,e.myParent.removeChild(u)}return null===L.remToPx&&(L.remToPx=parseFloat(S.getPropertyValue(r.body,"fontSize"))||16),null===L.vwToPx&&(L.vwToPx=parseFloat(t.innerWidth)/100,L.vhToPx=parseFloat(t.innerHeight)/100),l.remToPx=L.remToPx,l.vwToPx=L.vwToPx,l.vhToPx=L.vhToPx,b.debug>=1&&console.log("Unit ratios: "+JSON.stringify(l),o),l}if(s.begin&&0===V)try{s.begin.call(g,g)}catch(x){setTimeout(function(){throw x},1)}if("scroll"===A){var P,C,T,F=/^x$/i.test(s.axis)?"Left":"Top",j=parseFloat(s.offset)||0;s.container?m.isWrapped(s.container)||m.isNode(s.container)?(s.container=s.container[0]||s.container,P=s.container["scroll"+F],T=P+f(o).position()[F.toLowerCase()]+j):s.container=null:(P=b.State.scrollAnchor[b.State["scrollProperty"+F]],C=b.State.scrollAnchor[b.State["scrollProperty"+("Left"===F?"Top":"Left")]],T=f(o).offset()[F.toLowerCase()]+j),l={scroll:{rootPropertyValue:!1,startValue:P,currentValue:P,endValue:T,unitType:"",easing:s.easing,scrollData:{container:s.container,direction:F,alternateValue:C}},element:o},b.debug&&console.log("tweensContainer (scroll): ",l.scroll,o)}else if("reverse"===A){if(!i(o).tweensContainer)return void f.dequeue(o,s.queue);"none"===i(o).opts.display&&(i(o).opts.display="auto"),"hidden"===i(o).opts.visibility&&(i(o).opts.visibility="visible"),i(o).opts.loop=!1,i(o).opts.begin=null,i(o).opts.complete=null,v.easing||delete s.easing,v.duration||delete s.duration,s=f.extend({},i(o).opts,s);var E=f.extend(!0,{},i(o).tweensContainer);for(var H in E)if("element"!==H){var N=E[H].startValue;E[H].startValue=E[H].currentValue=E[H].endValue,E[H].endValue=N,m.isEmptyObject(v)||(E[H].easing=s.easing),b.debug&&console.log("reverse tweensContainer ("+H+"): "+JSON.stringify(E[H]),o)}l=E}else if("start"===A){var E;i(o).tweensContainer&&i(o).isAnimating===!0&&(E=i(o).tweensContainer),f.each(y,function(e,t){if(RegExp("^"+S.Lists.colors.join("$|^")+"$").test(e)){var r=p(t,!0),n=r[0],o=r[1],i=r[2];if(S.RegEx.isHex.test(n)){for(var s=["Red","Green","Blue"],l=S.Values.hexToRgb(n),u=i?S.Values.hexToRgb(i):a,c=0;c<s.length;c++){var f=[l[c]];o&&f.push(o),u!==a&&f.push(u[c]),y[e+s[c]]=f}delete y[e]}}});for(var z in y){var O=p(y[z]),q=O[0],$=O[1],M=O[2];z=S.Names.camelCase(z);var I=S.Hooks.getRoot(z),B=!1;if(i(o).isSVG||"tween"===I||S.Names.prefixCheck(I)[1]!==!1||S.Normalizations.registered[I]!==a){(s.display!==a&&null!==s.display&&"none"!==s.display||s.visibility!==a&&"hidden"!==s.visibility)&&/opacity|filter/.test(z)&&!M&&0!==q&&(M=0),s._cacheValues&&E&&E[z]?(M===a&&(M=E[z].endValue+E[z].unitType),B=i(o).rootPropertyValueCache[I]):S.Hooks.registered[z]?M===a?(B=S.getPropertyValue(o,I),M=S.getPropertyValue(o,z,B)):B=S.Hooks.templates[I][1]:M===a&&(M=S.getPropertyValue(o,z));var W,G,Y,D=!1;if(W=d(z,M),M=W[0],Y=W[1],W=d(z,q),q=W[0].replace(/^([+-\/*])=/,function(e,t){return D=t,""}),G=W[1],M=parseFloat(M)||0,q=parseFloat(q)||0,"%"===G&&(/^(fontSize|lineHeight)$/.test(z)?(q/=100,G="em"):/^scale/.test(z)?(q/=100,G=""):/(Red|Green|Blue)$/i.test(z)&&(q=q/100*255,G="")),/[\/*]/.test(D))G=Y;else if(Y!==G&&0!==M)if(0===q)G=Y;else{n=n||h();var Q=/margin|padding|left|right|width|text|word|letter/i.test(z)||/X$/.test(z)||"x"===z?"x":"y";switch(Y){case"%":M*="x"===Q?n.percentToPxWidth:n.percentToPxHeight;break;case"px":break;default:M*=n[Y+"ToPx"]}switch(G){case"%":M*=1/("x"===Q?n.percentToPxWidth:n.percentToPxHeight);break;case"px":break;default:M*=1/n[G+"ToPx"]}}switch(D){case"+":q=M+q;break;case"-":q=M-q;break;case"*":q=M*q;break;case"/":q=M/q}l[z]={rootPropertyValue:B,startValue:M,currentValue:M,endValue:q,unitType:G,easing:$},b.debug&&console.log("tweensContainer ("+z+"): "+JSON.stringify(l[z]),o)}else b.debug&&console.log("Skipping ["+I+"] due to a lack of browser support.")}l.element=o}l.element&&(S.Values.addClass(o,"velocity-animating"),R.push(l),""===s.queue&&(i(o).tweensContainer=l,i(o).opts=s),i(o).isAnimating=!0,V===w-1?(b.State.calls.push([R,g,s,null,k.resolver]),b.State.isTicking===!1&&(b.State.isTicking=!0,c())):V++)}var n,o=this,s=f.extend({},b.defaults,v),l={};switch(i(o)===a&&b.init(o),parseFloat(s.delay)&&s.queue!==!1&&f.queue(o,s.queue,function(e){b.velocityQueueEntryFlag=!0,i(o).delayTimer={setTimeout:setTimeout(e,parseFloat(s.delay)),next:e}}),s.duration.toString().toLowerCase()){case"fast":s.duration=200;break;case"normal":s.duration=h;break;case"slow":s.duration=600;break;default:s.duration=parseFloat(s.duration)||1}b.mock!==!1&&(b.mock===!0?s.duration=s.delay=1:(s.duration*=parseFloat(b.mock)||1,s.delay*=parseFloat(b.mock)||1)),s.easing=u(s.easing,s.duration),s.begin&&!m.isFunction(s.begin)&&(s.begin=null),s.progress&&!m.isFunction(s.progress)&&(s.progress=null),s.complete&&!m.isFunction(s.complete)&&(s.complete=null),s.display!==a&&null!==s.display&&(s.display=s.display.toString().toLowerCase(),"auto"===s.display&&(s.display=b.CSS.Values.getDisplayType(o))),s.visibility!==a&&null!==s.visibility&&(s.visibility=s.visibility.toString().toLowerCase()),s.mobileHA=s.mobileHA&&b.State.isMobile&&!b.State.isGingerbread,s.queue===!1?s.delay?setTimeout(e,s.delay):e():f.queue(o,s.queue,function(t,r){return r===!0?(k.promise&&k.resolver(g),!0):(b.velocityQueueEntryFlag=!0,void e(t))}),""!==s.queue&&"fx"!==s.queue||"inprogress"===f.queue(o)[0]||f.dequeue(o)}var s,l,d,g,y,v,x=arguments[0]&&(arguments[0].p||f.isPlainObject(arguments[0].properties)&&!arguments[0].properties.names||m.isString(arguments[0].properties));if(m.isWrapped(this)?(s=!1,d=0,g=this,l=this):(s=!0,d=1,g=x?arguments[0].elements||arguments[0].e:arguments[0]),g=o(g)){x?(y=arguments[0].properties||arguments[0].p,v=arguments[0].options||arguments[0].o):(y=arguments[d],v=arguments[d+1]);var w=g.length,V=0;if(!/^(stop|finish)$/i.test(y)&&!f.isPlainObject(v)){var C=d+1;v={};for(var T=C;T<arguments.length;T++)m.isArray(arguments[T])||!/^(fast|normal|slow)$/i.test(arguments[T])&&!/^\d/.test(arguments[T])?m.isString(arguments[T])||m.isArray(arguments[T])?v.easing=arguments[T]:m.isFunction(arguments[T])&&(v.complete=arguments[T]):v.duration=arguments[T]}var k={promise:null,resolver:null,rejecter:null};s&&b.Promise&&(k.promise=new b.Promise(function(e,t){k.resolver=e,k.rejecter=t}));var A;switch(y){case"scroll":A="scroll";break;case"reverse":A="reverse";break;case"finish":case"stop":f.each(g,function(e,t){i(t)&&i(t).delayTimer&&(clearTimeout(i(t).delayTimer.setTimeout),i(t).delayTimer.next&&i(t).delayTimer.next(),delete i(t).delayTimer)});var F=[];return f.each(b.State.calls,function(e,t){t&&f.each(t[1],function(r,n){var o=v===a?"":v;return o===!0||t[2].queue===o||v===a&&t[2].queue===!1?void f.each(g,function(r,a){a===n&&((v===!0||m.isString(v))&&(f.each(f.queue(a,m.isString(v)?v:""),function(e,t){
m.isFunction(t)&&t(null,!0)}),f.queue(a,m.isString(v)?v:"",[])),"stop"===y?(i(a)&&i(a).tweensContainer&&o!==!1&&f.each(i(a).tweensContainer,function(e,t){t.endValue=t.currentValue}),F.push(e)):"finish"===y&&(t[2].duration=1))}):!0})}),"stop"===y&&(f.each(F,function(e,t){p(t,!0)}),k.promise&&k.resolver(g)),e();default:if(!f.isPlainObject(y)||m.isEmptyObject(y)){if(m.isString(y)&&b.Redirects[y]){var j=f.extend({},v),E=j.duration,H=j.delay||0;return j.backwards===!0&&(g=f.extend(!0,[],g).reverse()),f.each(g,function(e,t){parseFloat(j.stagger)?j.delay=H+parseFloat(j.stagger)*e:m.isFunction(j.stagger)&&(j.delay=H+j.stagger.call(t,e,w)),j.drag&&(j.duration=parseFloat(E)||(/^(callout|transition)/.test(y)?1e3:h),j.duration=Math.max(j.duration*(j.backwards?1-e/w:(e+1)/w),.75*j.duration,200)),b.Redirects[y].call(t,t,j||{},e,w,g,k.promise?k:a)}),e()}var N="Velocity: First argument ("+y+") was not a property map, a known action, or a registered redirect. Aborting.";return k.promise?k.rejecter(new Error(N)):console.log(N),e()}A="start"}var L={lastParent:null,lastPosition:null,lastFontSize:null,lastPercentToPxWidth:null,lastPercentToPxHeight:null,lastEmToPx:null,remToPx:null,vwToPx:null,vhToPx:null},R=[];f.each(g,function(e,t){m.isNode(t)&&n.call(t)});var z,j=f.extend({},b.defaults,v);if(j.loop=parseInt(j.loop),z=2*j.loop-1,j.loop)for(var O=0;z>O;O++){var q={delay:j.delay,progress:j.progress};O===z-1&&(q.display=j.display,q.visibility=j.visibility,q.complete=j.complete),P(g,"reverse",q)}return e()}};b=f.extend(P,b),b.animate=P;var w=t.requestAnimationFrame||g;return b.State.isMobile||r.hidden===a||r.addEventListener("visibilitychange",function(){r.hidden?(w=function(e){return setTimeout(function(){e(!0)},16)},c()):w=t.requestAnimationFrame||g}),e.Velocity=b,e!==t&&(e.fn.velocity=P,e.fn.velocity.defaults=b.defaults),f.each(["Down","Up"],function(e,t){b.Redirects["slide"+t]=function(e,r,n,o,i,s){var l=f.extend({},r),u=l.begin,c=l.complete,p={height:"",marginTop:"",marginBottom:"",paddingTop:"",paddingBottom:""},d={};l.display===a&&(l.display="Down"===t?"inline"===b.CSS.Values.getDisplayType(e)?"inline-block":"block":"none"),l.begin=function(){u&&u.call(i,i);for(var r in p){d[r]=e.style[r];var a=b.CSS.getPropertyValue(e,r);p[r]="Down"===t?[a,0]:[0,a]}d.overflow=e.style.overflow,e.style.overflow="hidden"},l.complete=function(){for(var t in d)e.style[t]=d[t];c&&c.call(i,i),s&&s.resolver(i)},b(e,p,l)}}),f.each(["In","Out"],function(e,t){b.Redirects["fade"+t]=function(e,r,n,o,i,s){var l=f.extend({},r),u={opacity:"In"===t?1:0},c=l.complete;l.complete=n!==o-1?l.begin=null:function(){c&&c.call(i,i),s&&s.resolver(i)},l.display===a&&(l.display="In"===t?"auto":"none"),b(this,u,l)}}),b}(window.jQuery||window.Zepto||window,window,document)}));
;// Required for Meteor package, the use of window prevents export by Meteor
(function (window) {
    if (window.Package) {
        Materialize = {};
    } else {
        window.Materialize = {};
    }
})(window);


// Unique ID
Materialize.guid = (function () {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }

    return function () {
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    };
})();

Materialize.elementOrParentIsFixed = function (element) {
    var $element = $(element);
    var $checkElements = $element.add($element.parents());
    var isFixed = false;
    $checkElements.each(function () {
        if ($(this).css("position") === "fixed") {
            isFixed = true;
            return false;
        }
    });
    return isFixed;
};

// Velocity has conflicts when loaded with jQuery, this will check for it
var Vel;
if (jQuery) {
    Vel = jQuery.Velocity;
} else {
    Vel = Velocity;
}
;(
	function ( $ ) {

		// Add posibility to scroll to selected option
		// usefull for select for example
		$.fn.scrollTo = function ( elem ) {
			$( this ).scrollTop( $( this ).scrollTop() - $( this ).offset().top + $( elem ).offset().top );
			return this;
		};

		$.fn.tvd_dropdown = function ( option ) {
			var defaults = {
				inDuration: 100,
				outDuration: 225,
				constrain_width: true, // Constrains width of dropdown to the activator
				hover: true,
				gutter: 0, // Spacing from edge
				belowOrigin: false,
				alignment: 'left',
				maxHeight: null
			};

			this.each( function () {
				var origin = $( this );
				var options = $.extend( {}, defaults, option );
				var isFocused = false;

				// Dropdown menu
				var activates = $( "#" + origin.attr( 'data-activates' ) );

				function updateOptions() {
					if ( origin.data( 'induration' ) !== undefined ) {
						options.inDuration = origin.data( 'inDuration' );
					}
					if ( origin.data( 'outduration' ) !== undefined ) {
						options.outDuration = origin.data( 'outDuration' );
					}
					if ( origin.data( 'constrainwidth' ) !== undefined ) {
						options.constrain_width = origin.data( 'constrainwidth' );
					}
					if ( origin.data( 'hover' ) !== undefined ) {
						options.hover = origin.data( 'hover' );
					}
					if ( origin.data( 'gutter' ) !== undefined ) {
						options.gutter = origin.data( 'gutter' );
					}
					if ( origin.data( 'beloworigin' ) !== undefined ) {
						options.belowOrigin = origin.data( 'beloworigin' );
					}
					if ( origin.data( 'alignment' ) !== undefined ) {
						options.alignment = origin.data( 'alignment' );
					}
				}

				updateOptions();

				// Attach dropdown to its activator
				origin.after( activates );

				var $modal = activates.parents( '.tvd-modal' );

				/*
				 Helper function to position and resize dropdown.
				 Used in hover and click handler.
				 */
				function placeDropdown( eventType ) {
					// Check for simultaneous focus and click events.
					if ( eventType === 'focus' ) {
						isFocused = true;
					}

					// Check html data attributes
					updateOptions();

					// Set Dropdown state
					activates.addClass( 'tvd-active' );
					origin.addClass( 'tvd-active' );

					// Constrain width
					if ( options.constrain_width === true ) {
						activates.css( 'width', origin.outerWidth() );
					} else {
						activates.css( 'white-space', 'nowrap' );
					}
					if ( options.maxHeight ) {
						activates.css( 'max-height', options.maxHeight );
					}

					if ( $modal.length && activates.find( 'li' ).length ) {
						activates.css( 'margin-bottom', '40px' );
						var m_top = $modal.offset().top,
							ui_top = origin.offset().top,
							ui_total_height = activates.find( 'li' ).length * 34,
							m_height = $modal.height();

						var _size = ui_top - m_top + ui_total_height;
						if ( _size > m_height ) {
							$modal.animate( {
								height: _size
							}, options.inDuration );
						}
					}

					// Offscreen detection
					var windowHeight = window.innerHeight;
					var originHeight = origin.innerHeight();
					var offsetLeft = origin.offset().left;
					var offsetTop = origin.offset().top - $( window ).scrollTop();
					var currAlignment = options.alignment;
					var activatesLeft, gutterSpacing;

					// Below Origin
					var verticalOffset = 0;
					if ( options.belowOrigin === true ) {
						verticalOffset = originHeight;
					}

					if ( offsetLeft + activates.innerWidth() > $( window ).width() ) {
						// Dropdown goes past screen on right, force right alignment
						currAlignment = 'right';

					} else if ( offsetLeft - activates.innerWidth() + origin.innerWidth() < 0 ) {
						// Dropdown goes past screen on left, force left alignment
						currAlignment = 'left';
					}
					// Vertical bottom offscreen detection
					if ( offsetTop + activates.innerHeight() > windowHeight ) {
						// If going upwards still goes offscreen, just crop height of dropdown.
						if ( offsetTop + originHeight - activates.innerHeight() < 0 ) {
							var adjustedHeight = windowHeight - offsetTop - verticalOffset;
							activates.css( 'max-height', adjustedHeight );
						} else {
							// Flow upwards.
							if ( ! verticalOffset ) {
								verticalOffset += originHeight;
							}
							verticalOffset -= activates.innerHeight();
						}
					}

					// Handle edge alignment
					if ( currAlignment === 'left' ) {
						gutterSpacing = options.gutter;
						leftPosition = origin.position().left + gutterSpacing;
					}
					else if ( currAlignment === 'right' ) {
						var offsetRight = origin.position().left + origin.outerWidth() - activates.outerWidth();
						gutterSpacing = - options.gutter;
						leftPosition = offsetRight + gutterSpacing;
					}
					// Position dropdown
					activates.css( {
						position: 'absolute',
						top: origin.position().top + verticalOffset,
						left: leftPosition
					} );

					// Show dropdown
					activates
						.stop( true, true )
						.css( 'opacity', 0 )
						.slideDown( {
							queue: false,
							duration: options.inDuration,
							easing: 'easeOutCubic',
							complete: function () {
								$( this ).css( 'height', '' );
							}
						} )
						.animate( {opacity: 1}, {queue: false, duration: options.inDuration, easing: 'easeOutSine'} );
				}

				function hideDropdown() {
					// Check for simultaneous focus and click events.
					isFocused = false;
					if ( $modal.length ) {
						$modal.css( 'height', '' );
						activates.hide( 0 );
					} else {
						activates.slideUp( options.outDuration );
					}
					activates.removeClass( 'tvd-active' );
					activates.css( 'max-height', '' );
					origin.removeClass( 'tvd-active' );
				}

				// Hover
				if ( options.hover ) {
					var open = false;
					origin.unbind( 'click.' + origin.attr( 'id' ) );
					// Hover handler to show dropdown
					origin.on( 'mouseenter', function ( e ) { // Mouse over
						if ( open === false ) {
							placeDropdown();
							open = true;
						}
					} );
					origin.on( 'mouseleave', function ( e ) {
						// If hover on origin then to something other than dropdown content, then close
						var toEl = e.toElement || e.relatedTarget; // added browser compatibility for target element
						if ( ! $( toEl ).closest( '.tvd-dropdown-content' ).is( activates ) ) {
							activates.stop( true, true );
							hideDropdown();
							open = false;
						}
					} );

					activates.on( 'mouseleave', function ( e ) { // Mouse out
						var toEl = e.toElement || e.relatedTarget;
						if ( ! $( toEl ).closest( '.tvd-dropdown-button' ).is( origin ) ) {
							activates.stop( true, true );
							hideDropdown();
							open = false;
						}
					} );

					// Click
				} else {
					// Click handler to show dropdown
					origin.unbind( 'click.' + origin.attr( 'id' ) );
					origin.bind( 'click.' + origin.attr( 'id' ), function ( e ) {
						if ( ! isFocused ) {
							if ( origin[0] == e.currentTarget && ! origin.hasClass( 'tvd-active' ) &&
							     (
								     $( e.target ).closest( '.tvd-dropdown-content' ).length === 0
							     ) ) {
								e.preventDefault(); // Prevents button click from moving window
								placeDropdown( 'click' );
							}
							// If origin is clicked and menu is open, close menu
							else if ( origin.hasClass( 'tvd-active' ) ) {
								hideDropdown();
								$( document ).unbind( 'click.' + activates.attr( 'id' ) + ' touchstart.' + activates.attr( 'id' ) );
							}
							// If menu open, add click close handler to document
							if ( activates.hasClass( 'tvd-active' ) ) {
								$( document ).bind( 'click.' + activates.attr( 'id' ) + ' touchstart.' + activates.attr( 'id' ), function ( e ) {
									if ( ! activates.is( e.target ) && ! origin.is( e.target ) && (
											! origin.find( e.target ).length
										) ) {
										hideDropdown();
										$( document ).unbind( 'click.' + activates.attr( 'id' ) + ' touchstart.' + activates.attr( 'id' ) );
									}
								} );
							}
						}
					} );

				} // End else

				// Listen to open and close event - useful for select component
				origin.on( 'open', function ( e, eventType ) {
					placeDropdown( eventType );
				} );
				origin.on( 'close', hideDropdown );


			} );
		}; // End dropdown plugin

		$( document ).ready( function () {
			$( '.tvd-dropdown-button' ).tvd_dropdown();
		} );
	}( jQuery )
);;(function ( $ ) {
	var _stack = 0,
		_lastID = 0,
		_generateID = function () {
			_lastID ++;
			return 'tvd-materialize-lean-overlay-' + _lastID;
		};

	$.fn.extend( {
		openModal: function ( options ) {

			var $body = $( 'body' ).css( 'overflow', 'hidden' );

			var defaults = {
					opacity: 0.7,
					top: '10%',
					in_duration: 350,
					out_duration: 250,
					ready: undefined,
					complete: undefined,
					dismissible: true,
					starting_top: '4%',
					overlay_class: ''
				},
				overlayID = _generateID(),
				$modal = $( this ),
				$overlay = $( '<div class="tvd-lean-overlay"></div>' ),
				lStack = (++ _stack);
			// Store a reference of the overlay
			$overlay.attr( 'id', overlayID ).css( 'z-index', 10000 + lStack * 2 );
			$modal.data( 'tvd-overlay-id', overlayID ).css( 'z-index', 10000 + lStack * 2 + 1 );

			$body.append( $overlay );

			// Override defaults
			options = $.extend( defaults, options );

			$overlay.addClass( options.overlay_class );

			if ( options.dismissible ) {
				$overlay.click( function () {
					$modal.closeModal( options );
				} );
				// Return on ESC
				$( document ).on( 'keyup.leanModal' + overlayID, function ( e ) {
					if ( e.keyCode === 27 ) {   // ESC key
						$modal.closeModal( options );
					}
				} );
			}

			$modal.find( ".tvd-modal-close" ).on( 'click.close', function ( e ) {
				$modal.closeModal( options );
			} );

			$overlay.css( {display: "block", opacity: 0} );

			$modal.css( {
				display: "block",
				opacity: 0
			} );

			$overlay.velocity( {opacity: options.opacity}, {
				duration: options.in_duration,
				queue: false,
				ease: "easeOutCubic"
			} );
			$modal.data( 'tvd-associated-overlay', $overlay[0] );

			// Define Bottom Sheet animation
			if ( $modal.hasClass( 'bottom-sheet' ) ) {
				$modal.velocity( {bottom: "0", opacity: 1}, {
					duration: options.in_duration,
					queue: false,
					ease: "easeOutCubic",
					// Handle modal ready callback
					complete: function () {
						if ( typeof(options.ready) === "function" ) {
							options.ready();
						}
					}
				} );
			}
			else {
				$.Velocity.hook( $modal, "scaleX", 0.7 );
				$modal.css( {top: options.starting_top} );
				$modal.velocity( {top: options.top || "10%", opacity: 1, scaleX: '1'}, {
					duration: options.in_duration,
					queue: false,
					ease: "easeOutCubic",
					// Handle modal ready callback
					complete: function () {
						if ( typeof(options.ready) === "function" ) {
							options.ready();
						}
					}
				} );
			}


		}
	} );

	$.fn.extend( {
		closeModal: function ( options ) {
			var defaults = {
					out_duration: 250,
					complete: undefined
				},
				$modal = $( this ),
				overlayID = $modal.data( 'tvd-overlay-id' ),
				$overlay = $( '#' + overlayID );

			options = $.extend( defaults, options );

			// Disable scrolling
			$( 'body' ).css( 'overflow', '' );

			$modal.find( '.tvd-modal-close' ).off( 'click.close' );
			$( document ).off( 'keyup.leanModal' + overlayID );

			$overlay.velocity( {opacity: 0}, {
				duration: options.out_duration,
				queue: false,
				ease: "easeOutQuart"
			} );
			_stack --;

			// Define Bottom Sheet animation
			if ( $modal.hasClass( 'bottom-sheet' ) ) {
				$modal.velocity( {bottom: "-100%", opacity: 0}, {
					duration: options.out_duration,
					queue: false,
					ease: "easeOutCubic",
					// Handle modal ready callback
					complete: function () {
						$overlay.css( {display: "none"} );

						// Call complete callback
						if ( typeof(options.complete) === "function" ) {
							options.complete();
						}
						$overlay.remove();
					}
				} );
			}
			else {
				if ( options.out_duration === 0 ) {
					$modal.hide( 0 );
					if ( typeof(options.complete) === "function" ) {
						options.complete();
					}
					$overlay.remove();
					return;
				}
				$modal.velocity(
					{top: options.starting_top, opacity: 0, scaleX: 0.7}, {
						duration: options.out_duration,
						complete: function () {

							$( this ).css( 'display', 'none' );
							// Call complete callback
							if ( typeof(options.complete) === "function" ) {
								options.complete();
							}
							$overlay.remove();
						}
					}
				);
			}
		}
	} );

	$.fn.extend( {
		leanModal: function ( option ) {
			return this.each( function () {

				var defaults = {
						starting_top: '4%'
					},
					$this = $( this ),
					// Override defaults
					options = $.extend( defaults, option, $this.data() );

				// Close Handlers
				$this.click( function ( e ) {
					options.starting_top = ($( this ).offset().top - $( window ).scrollTop()) / 1.15;
					options.elem = $this;
					var modal_id = $this.attr( "href" ) || '#' + $this.data( 'target' );
					$( modal_id ).openModal( options );
					e.preventDefault();
					e.stopPropagation();
					return false;
				} ); // done set on click
			} ); // done return
		}
	} );
})( jQuery );
;(
	function ( $ ) {
		$( document ).ready( function () {

			// Function to update labels of text fields
			Materialize.updateTextFields = function () {
				var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea';
				$( input_selector ).each( function ( index, element ) {
					if ( $( element ).val().length > 0 || $( this ).attr( 'placeholder' ) !== undefined || $( element )[0].validity.badInput === true ) {
						$( this ).siblings( 'label' ).addClass( 'tvd-active' );
					}
					else {
						$( this ).siblings( 'label, i' ).removeClass( 'tvd-active' );
					}
				} );
			};

			// Text based inputs
			var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea';

			// Handle HTML5 autofocus
			$( 'input[autofocus]' ).siblings( 'label, i' ).addClass( 'tvd-active' );

			// Add active if form auto complete
			$( document ).on( 'change', input_selector, function () {
				if ( $( this ).val().length !== 0 || $( this ).attr( 'placeholder' ) !== undefined ) {
					$( this ).siblings( 'label' ).addClass( 'tvd-active' );
				}
				validate_field( $( this ) );
			} );

			// Add active if input element has been pre-populated on document ready
			$( document ).ready( function () {
				Materialize.updateTextFields();
			} );

			// HTML DOM FORM RESET handling
			$( document ).on( 'reset', function ( e ) {
				var formReset = $( e.target );
				if ( formReset.is( 'form' ) ) {
					formReset.find( input_selector ).removeClass( 'tvd-valid' ).removeClass( 'tvd-invalid' );
					formReset.find( input_selector ).each( function () {
						if ( $( this ).attr( 'value' ) === '' ) {
							$( this ).siblings( 'label, i' ).removeClass( 'tvd-active' );
						}
					} );

					// Reset select
					formReset.find( 'select.tvd-initialized' ).each( function () {
						var reset_text = formReset.find( 'option[selected]' ).text();
						formReset.siblings( 'input.tvd-select-dropdown' ).val( reset_text );
					} );
				}
			} );

			// Add active when element has focus
			$( document ).on( 'focus', input_selector, function () {
				$( this ).siblings( 'label, i' ).addClass( 'tvd-active' );
			} );

			$( document ).on( 'blur', input_selector, function () {
				var $inputElement = $( this );
				if ( $inputElement.val().length === 0 && $inputElement[0].validity.badInput !== true && $inputElement.attr( 'placeholder' ) === undefined ) {
					$inputElement.siblings( 'label, i' ).removeClass( 'tvd-active' );
				}

				if ( $inputElement.val().length === 0 && $inputElement[0].validity.badInput !== true && $inputElement.attr( 'placeholder' ) !== undefined ) {
					$inputElement.siblings( 'i' ).removeClass( 'tvd-active' );
				}
				validate_field( $inputElement );
			} );

			window.validate_field = function ( object ) {
				var hasLength = object.attr( 'length' ) !== undefined;
				var lenAttr = parseInt( object.attr( 'length' ) );
				var len = object.val().length;

				if ( object.val().length === 0 && object[0].validity.badInput === false ) {
					if ( object.hasClass( 'tvd-validate' ) ) {
						object.removeClass( 'tvd-valid' );
						object.removeClass( 'tvd-invalid' );
					}
				}
				else {
					if ( object.hasClass( 'tvd-validate' ) ) {
						// Check for character counter attributes
						if ( (
							     object.is( ':valid' ) && hasLength && (
								     len <= lenAttr
							     )
						     ) || (
							     object.is( ':valid' ) && ! hasLength
						     ) ) {
							object.removeClass( 'tvd-invalid' );
							object.addClass( 'tvd-valid' );
						}
						else {
							object.removeClass( 'tvd-valid' );
							object.addClass( 'tvd-invalid' );
						}
					}
				}
			};


			// Textarea Auto Resize
			var hiddenDiv = $( '.tvd-hiddendiv' ).first();
			if ( ! hiddenDiv.length ) {
				hiddenDiv = $( '<div class="tvd-hiddendiv tvd-common"></div>' );
				$( 'body' ).append( hiddenDiv );
			}
			var text_area_selector = '.tvd-materialize-textarea';

			function textareaAutoResize( $textarea ) {
				// Set font properties of hiddenDiv

				var fontFamily = $textarea.css( 'font-family' );
				var fontSize = $textarea.css( 'font-size' );

				if ( fontSize ) {
					hiddenDiv.css( 'font-size', fontSize );
				}
				if ( fontFamily ) {
					hiddenDiv.css( 'font-family', fontFamily );
				}

				if ( $textarea.attr( 'wrap' ) === "off" ) {
					hiddenDiv.css( 'overflow-wrap', "normal" )
					         .css( 'white-space', "pre" );
				}

				hiddenDiv.text( $textarea.val() + '\n' );
				var content = hiddenDiv.html().replace( /\n/g, '<br>' );
				hiddenDiv.html( content );


				// When textarea is hidden, width goes crazy.
				// Approximate with half of window size

				if ( $textarea.is( ':visible' ) ) {
					hiddenDiv.css( 'width', $textarea.width() );
				}
				else {
					hiddenDiv.css( 'width', $( window ).width() / 2 );
				}

				$textarea.css( 'height', hiddenDiv.height() );
			}

			$( text_area_selector ).each( function () {
				var $textarea = $( this );
				if ( $textarea.val().length ) {
					textareaAutoResize( $textarea );
				}
			} );

			$( 'body' ).on( 'keyup keydown autoresize', text_area_selector, function () {
				textareaAutoResize( $( this ) );
			} );

			// File Input Path
			$( document ).on( 'change', '.tvd-file-field input[type="file"]', function () {
				var file_field = $( this ).closest( '.tvd-file-field' );
				var path_input = file_field.find( 'input.tvd-file-path' );
				var files = $( this )[0].files;
				var file_names = [];
				for ( var i = 0; i < files.length; i ++ ) {
					file_names.push( files[i].name );
				}
				path_input.val( file_names.join( ", " ) );
				path_input.trigger( 'change' );
			} );

			/****************
			 *  Range Input  *
			 ****************/

			var range_type = 'input[type=range]';
			var range_mousedown = false;
			var left;

			$( range_type ).each( function () {
				var thumb = $( '<span class="thumb"><span class="value"></span></span>' );
				$( this ).after( thumb );
			} );

			var range_wrapper = '.range-field';
			$( document ).on( 'change', range_type, function ( e ) {
				var thumb = $( this ).siblings( '.thumb' );
				thumb.find( '.value' ).html( $( this ).val() );
			} );

			$( document ).on( 'input mousedown touchstart', range_type, function ( e ) {
				var thumb = $( this ).siblings( '.thumb' );
				var width = $( this ).outerWidth();

				// If thumb indicator does not exist yet, create it
				if ( thumb.length <= 0 ) {
					thumb = $( '<span class="thumb"><span class="value"></span></span>' );
					$( this ).after( thumb );
				}

				// Set indicator value
				thumb.find( '.value' ).html( $( this ).val() );

				range_mousedown = true;
				$( this ).addClass( 'active' );

				if ( ! thumb.hasClass( 'active' ) ) {
					thumb.velocity( {height: "30px", width: "30px", top: "-20px", marginLeft: "-15px"}, {
						duration: 300,
						easing: 'easeOutExpo'
					} );
				}

				if ( e.type !== 'input' ) {
					if ( e.pageX === undefined || e.pageX === null ) {//mobile
						left = e.originalEvent.touches[0].pageX - $( this ).offset().left;
					}
					else { // desktop
						left = e.pageX - $( this ).offset().left;
					}
					if ( left < 0 ) {
						left = 0;
					}
					else if ( left > width ) {
						left = width;
					}
					thumb.addClass( 'active' ).css( 'left', left );
				}

				thumb.find( '.value' ).html( $( this ).val() );
			} );

			$( document ).on( 'mouseup touchend', range_wrapper, function () {
				range_mousedown = false;
				$( this ).removeClass( 'active' );
			} );

			$( document ).on( 'mousemove touchmove', range_wrapper, function ( e ) {
				var thumb = $( this ).children( '.thumb' );
				var left;
				if ( range_mousedown ) {
					if ( ! thumb.hasClass( 'active' ) ) {
						thumb.velocity( {
							height: '30px',
							width: '30px',
							top: '-20px',
							marginLeft: '-15px'
						}, {duration: 300, easing: 'easeOutExpo'} );
					}
					if ( e.pageX === undefined || e.pageX === null ) { //mobile
						left = e.originalEvent.touches[0].pageX - $( this ).offset().left;
					}
					else { // desktop
						left = e.pageX - $( this ).offset().left;
					}
					var width = $( this ).outerWidth();

					if ( left < 0 ) {
						left = 0;
					}
					else if ( left > width ) {
						left = width;
					}
					thumb.addClass( 'active' ).css( 'left', left );
					thumb.find( '.value' ).html( thumb.siblings( range_type ).val() );
				}
			} );

			$( document ).on( 'mouseout touchleave', range_wrapper, function () {
				if ( ! range_mousedown ) {

					var thumb = $( this ).children( '.thumb' );

					if ( thumb.hasClass( 'active' ) ) {
						thumb.velocity( {height: '0', width: '0', top: '10px', marginLeft: '-6px'}, {duration: 100} );
					}
					thumb.removeClass( 'active' );
				}
			} );
//			$( 'select' ).material_select();
		} ); // End of $(document).ready

		/*******************
		 *  Select Plugin  *
		 ******************/
		/* not used anymore */
//		$.fn.material_select = function ( callback ) {
//			$( this ).each( function () {
//				var $select = $( this );
//
//				if ( $select.hasClass( 'tvd-browser-default' ) || $select.hasClass( 'tvd-select2' ) ) {
//					return; // Continue to next (return false breaks out of entire loop)
//				}
//
//				var multiple = $select.attr( 'multiple' ) ? true : false,
//					maxHeight = $select.attr( 'data-max-height' ) || '',
//					lastID = $select.data( 'select-id' ); // Tear down structure if Select needs to be rebuilt
//
//				if ( lastID ) {
//					$select.parent().find( 'span.tvd-caret' ).remove();
//					$select.parent().find( 'input,label.tvd-dropdown-label' ).remove();
//
//					$select.unwrap().off( 'tvderror tvdclear' );
//					$( 'ul#tvd-select-options-' + lastID ).remove();
//				}
//
//				// If destroying the select, remove the selelct-id and reset it to it's uninitialized state.
//				if ( callback === 'destroy' ) {
//					$select.data( 'select-id', null ).removeClass( 'tvd-initialized' );
//					return;
//				}
//
//				var uniqueID = Materialize.guid();
//				$select.data( 'select-id', uniqueID );
//				var wrapper = $( '<div class="tvd-select-wrapper"></div>' );
//				wrapper.addClass( $select.attr( 'class' ) );
//				var options = $( '<ul id="tvd-select-options-' + uniqueID + '" class="tvd-dropdown-content tvd-select-dropdown ' + (
//						multiple ? 'tvd-multiple-select-dropdown' : ''
//					) + '"></ul>' );
//				var selectOptions = $select.children( 'option' );
//				var selectOptGroups = $select.children( 'optgroup' ),
//					all_children = $select.children();
//
//				var valuesSelected = [],
//					optionsHover = false,
//					err_label = $select.siblings( 'label[for="' + $select.attr( 'id' ) + '"]' ).clone().attr( 'for', '' ).addClass( 'tvd-dropdown-label' ).text( '' );
//
//				if ( $select.find( 'option:selected' ).length > 0 ) {
//					label = $select.find( 'option:selected' );
//				} else {
//					label = selectOptions.first();
//				}
//
//				// Function that renders and appends the option taking into
//				// account type and possible image icon.
//				var appendOptionWithIcon = function ( select, option, type ) {
//					// Add disabled attr if disabled
//					var disabledClass = (
//						option.is( ':disabled' )
//					) ? 'disabled ' : '';
//
//					// add icons
//					var icon_url = option.data( 'icon' );
//					var classes = option.attr( 'class' );
//					if ( ! ! icon_url ) {
//						var classString = '';
//						if ( ! ! classes ) {
//							classString = ' class="' + classes + '"';
//						}
//
//						// Check for multiple type.
//						if ( type === 'multiple' ) {
//							options.append( $( '<li class="' + disabledClass + '"><img src="' + icon_url + '"' + classString + '><span><input type="checkbox"' + disabledClass + '/><label></label>' + option.html() + '</span></li>' ) );
//						} else {
//							options.append( $( '<li class="' + disabledClass + '"><img src="' + icon_url + '"' + classString + '><span>' + option.html() + '</span></li>' ) );
//						}
//						return true;
//					}
//
//					// Check for multiple type.
//					if ( type === 'multiple' ) {
//						options.append( $( '<li class="' + disabledClass + '"><span><input type="checkbox"' + disabledClass + '/><label></label>' + option.html() + '</span></li>' ) );
//					} else {
//						options.append( $( '<li class="' + disabledClass + '"><span>' + option.html() + '</span></li>' ) );
//					}
//				};
//
//				/* Create dropdown structure. */
//				if ( selectOptGroups.length ) {
//					all_children.each( function () {
//						var $this = $( this );
//						if ( $this.is( 'optgroup' ) ) {
//							selectOptions = $this.children( 'option' );
//							options.append( $( '<li class="tvd-optgroup"><span>' + $this.attr( 'label' ) + '</span></li>' ) );
//
//							selectOptions.each( function () {
//								appendOptionWithIcon( $select, $( this ) );
//							} );
//						} else {
//							appendOptionWithIcon( $select, $this, multiple ? 'multiple' : '' );
//						}
//					} );
//				} else {
//					selectOptions.each( function () {
//						var disabledClass = (
//							$( this ).is( ':disabled' )
//						) ? 'disabled ' : '';
//						if ( multiple ) {
//							appendOptionWithIcon( $select, $( this ), 'multiple' );
//
//						} else {
//							appendOptionWithIcon( $select, $( this ) );
//						}
//					} );
//				}
//
//
//				options.find( 'li:not(.tvd-optgroup)' ).each( function ( i ) {
//					var $curr_select = $select;
//					$( this ).click( function ( e ) {
//						// Check if option element is disabled
//						if ( ! $( this ).hasClass( 'tvd-disabled' ) && ! $( this ).hasClass( 'tvd-optgroup' ) ) {
//							if ( multiple ) {
//								$( 'input[type="checkbox"]', this ).prop( 'checked', function ( i, v ) {
//									return ! v;
//								} );
//								toggleEntryFromArray( valuesSelected, $( this ).index(), $curr_select );
//								$newSelect.trigger( 'focus' );
//
//							} else {
//								options.find( 'li' ).removeClass( 'tvd-active' );
//								$( this ).toggleClass( 'tvd-active' );
//								$curr_select.siblings( 'input.tvd-select-dropdown' ).val( $( this ).text() );
//							}
//							activateOption( options, $( this ) );
//							$curr_select.find( 'option' ).eq( i ).prop( 'selected', true );
//							// Trigger onchange() event
//							$curr_select.trigger( 'change' );
//							if ( typeof callback !== 'undefined' ) {
//								callback();
//							}
//							$select.trigger( 'tvdclear' );
//						}
//
//						e.stopPropagation();
//					} );
//				} );
//
//				// Wrap Elements
//				$select.wrap( wrapper );
//				// Add Select Display Element
//				var dropdownIcon = $( '<span class="tvd-caret">&#9660;</span>' );
//				if ( $select.is( ':disabled' ) ) {
//					dropdownIcon.addClass( 'tvd-disabled' );
//				}
//
//				// escape double quotes
//				var sanitizedLabelHtml = label.html() && label.html().replace( /"/g, '&quot;' );
//
//				var $newSelect = $( '<input type="text" class="tvd-select-dropdown" readonly="true" ' + (
//						(
//							$select.is( ':disabled' )
//						) ? 'disabled' : ''
//					) + ' data-activates="tvd-select-options-' + uniqueID + '" id="si-' + uniqueID + '" value="' + sanitizedLabelHtml + '"/>' );
//				$select.before( $newSelect );
//				$newSelect.before( dropdownIcon );
//
//				// preventing the select to close when clicking on the scroll bar
//				options.on( 'mousedown', function ( event ) {
//					if ( ! $( event.target ).is( 'span' ) ) {
//						return false;
//					}
//				} );
//				$newSelect.after( options );
//				if ( err_label.length ) {
//					err_label.attr( 'for', 'si-' + uniqueID );
//					$newSelect.after( err_label );
//				}
//
//				// Check if section element is disabled
//				if ( ! $select.is( ':disabled' ) ) {
//					$newSelect.tvd_dropdown( {'hover': false, 'closeOnClick': false, maxHeight: maxHeight} );
//				}
//
//				// Copy tabindex
//				if ( $select.attr( 'tabindex' ) ) {
//					$( $newSelect[0] ).attr( 'tabindex', $select.attr( 'tabindex' ) );
//				}
//
//				$select.addClass( 'tvd-initialized' );
//
//				$newSelect.on( {
//					'focus': function () {
//						if ( $( 'ul.tvd-select-dropdown' ).not( options[0] ).is( ':visible' ) ) {
//							$( 'input.tvd-select-dropdown' ).trigger( 'close' );
//						}
//						if ( ! options.is( ':visible' ) ) {
//							$( this ).trigger( 'open', ['focus'] );
//							var label = $( this ).val();
//							var selectedOption = options.find( 'li' ).filter( function () {
//								return $( this ).text().toLowerCase() === label.toLowerCase();
//							} )[0];
//							activateOption( options, selectedOption );
//						}
//					},
//					'click': function ( e ) {
//						e.stopPropagation();
//					}
//				} );
//				$newSelect.on( 'blur', function ( e ) {
//					var $this = $( this );
//					setTimeout( function () {
//						if ( ! multiple ) {
//							$this.trigger( 'close' );
//						}
//						options.find( 'li.tvd-selected' ).removeClass( 'tvd-selected' );
//					}, 150 );
//				} );
//
//				$select.on( {
//					'tvderror': function ( event, err_message ) {
//						$newSelect.parent().addClass( 'tvd-invalid' );
//						err_label.attr( 'data-error', err_message );
//					},
//					'tvdclear': function () {
//						$newSelect.parent().removeClass( 'tvd-invalid' );
//					}
//				} );
//
//				options.hover( function () {
//					optionsHover = true;
//				}, function () {
//					optionsHover = false;
//				} );
//
//				$( window ).on( {
//					'click': function ( e ) {
//						multiple && (
//							optionsHover || $newSelect.trigger( 'close' )
//						);
//					}
//				} );
//
//				// Make option as selected and scroll to selected position
//				activateOption = function ( collection, newOption ) {
//					collection.find( 'li.tvd-selected' ).removeClass( 'tvd-selected' );
//					$( newOption ).addClass( 'tvd-selected' );
//				};
//
//				// Allow user to search by typing
//				// this array is cleared after 1 second
//				var filterQuery = [],
//					onKeyDown = function ( e ) {
//						// TAB - switch to another input
//						if ( e.which == 9 ) {
//							$newSelect.trigger( 'close' );
//							return;
//						}
//
//						// ARROW DOWN WHEN SELECT IS CLOSED - open select options
//						if ( e.which == 40 && ! options.is( ':visible' ) ) {
//							$newSelect.trigger( 'open' );
//							return;
//						}
//
//						// ENTER WHEN SELECT IS CLOSED - submit form
//						if ( e.which == 13 && ! options.is( ':visible' ) ) {
//							return;
//						}
//
//						e.preventDefault();
//
//						// CASE WHEN USER TYPE LETTERS
//						var letter = String.fromCharCode( e.which ).toLowerCase(),
//							nonLetters = [9, 13, 27, 38, 40];
//						if ( letter && (
//								nonLetters.indexOf( e.which ) === - 1
//							) ) {
//							filterQuery.push( letter );
//
//							var string = filterQuery.join( '' ),
//								newOption = options.find( 'li' ).filter( function () {
//									return $( this ).text().toLowerCase().indexOf( string ) === 0;
//								} )[0];
//
//							if ( newOption ) {
//								activateOption( options, newOption );
//							}
//						}
//
//						// ENTER - select option and close when select options are opened
//						if ( e.which == 13 ) {
//							var activeOption = options.find( 'li.tvd-selected:not(.tvd-disabled)' )[0];
//							if ( activeOption ) {
//								$( activeOption ).trigger( 'click' );
//								if ( ! multiple ) {
//									$newSelect.trigger( 'close' );
//								}
//							}
//						}
//
//						// ARROW DOWN - move to next not disabled option
//						if ( e.which == 40 ) {
//							if ( options.find( 'li.tvd-selected' ).length ) {
//								newOption = options.find( 'li.tvd-selected' ).next( 'li:not(.tvd-disabled)' )[0];
//							} else {
//								newOption = options.find( 'li:not(.tvd-disabled)' )[0];
//							}
//							activateOption( options, newOption );
//						}
//
//						// ESC - close options
//						if ( e.which == 27 ) {
//							$newSelect.trigger( 'close' );
//						}
//
//						// ARROW UP - move to previous not disabled option
//						if ( e.which == 38 ) {
//							newOption = options.find( 'li.tvd-selected' ).prev( 'li:not(.tvd-disabled)' )[0];
//							if ( newOption ) {
//								activateOption( options, newOption );
//							}
//						}
//
//						// Automaticaly clean filter query so user can search again by starting letters
//						setTimeout( function () {
//							filterQuery = [];
//						}, 1000 );
//					};
//
//				$newSelect.on( 'keydown', onKeyDown );
//			} );
//
//			function toggleEntryFromArray( entriesArray, entryIndex, select ) {
//				var index = entriesArray.indexOf( entryIndex );
//
//				if ( index === - 1 ) {
//					entriesArray.push( entryIndex );
//				} else {
//					entriesArray.splice( index, 1 );
//				}
//
//				select.siblings( 'ul.tvd-dropdown-content' ).find( 'li' ).eq( entryIndex ).toggleClass( 'tvd-active' );
//				select.find( 'option' ).eq( entryIndex ).prop( 'selected', true );
//				setValueToInput( entriesArray, select );
//			}
//
//			function setValueToInput( entriesArray, select ) {
//				var value = '';
//
//				for ( var i = 0, count = entriesArray.length; i < count; i ++ ) {
//					var text = select.find( 'option' ).eq( entriesArray[i] ).text();
//
//					i === 0 ? value += text : value += ', ' + text;
//				}
//
//				if ( value === '' ) {
//					value = select.find( 'option:disabled' ).eq( 0 ).text();
//				}
//
//				select.siblings( 'input.tvd-select-dropdown' ).val( value );
//			}
//		};

	}( jQuery )
);
;/*!
 * Waves v0.6.4
 * http://fian.my.id/Waves
 *
 * Copyright 2014 Alfiana E. Sibuea and other contributors
 * Released under the MIT license
 * https://github.com/fians/Waves/blob/master/LICENSE
 */

;(function(window) {
    'use strict';

    var Waves = Waves || {};

    var $$ = document.querySelectorAll.bind(document);

    // Find exact position of element
    function isWindow(obj) {
        return obj !== null && obj === obj.window;
    }

    function getWindow(elem) {
        return isWindow(elem) ? elem : elem.nodeType === 9 && elem.defaultView;
    }

    function offset(elem) {
        var docElem, win,
            box = {top: 0, left: 0},
            doc = elem && elem.ownerDocument;

        docElem = doc.documentElement;

        if (typeof elem.getBoundingClientRect !== typeof undefined) {
            box = elem.getBoundingClientRect();
        }
        win = getWindow(doc);
        return {
            top: box.top + win.pageYOffset - docElem.clientTop,
            left: box.left + win.pageXOffset - docElem.clientLeft
        };
    }

    function convertStyle(obj) {
        var style = '';

        for (var a in obj) {
            if (obj.hasOwnProperty(a)) {
                style += (a + ':' + obj[a] + ';');
            }
        }

        return style;
    }

    var Effect = {

        // Effect delay
        duration: 750,

        show: function(e, element) {

            // Disable right click
            if (e.button === 2) {
                return false;
            }

            var el = element || this;

            // Create ripple
            var ripple = document.createElement('div');
            ripple.className = 'tvd-waves-ripple';
            el.appendChild(ripple);

            // Get click coordinate and element witdh
            var pos         = offset(el);
            var relativeY   = (e.pageY - pos.top);
            var relativeX   = (e.pageX - pos.left);
            var scale       = 'scale('+((el.clientWidth / 100) * 10)+')';

            // Support for touch devices
            if ('touches' in e) {
              relativeY   = (e.touches[0].pageY - pos.top);
              relativeX   = (e.touches[0].pageX - pos.left);
            }

            // Attach data to element
            ripple.setAttribute('data-hold', Date.now());
            ripple.setAttribute('data-scale', scale);
            ripple.setAttribute('data-x', relativeX);
            ripple.setAttribute('data-y', relativeY);

            // Set ripple position
            var rippleStyle = {
                'top': relativeY+'px',
                'left': relativeX+'px'
            };

            ripple.className = ripple.className + ' tvd-waves-notransition';
            ripple.setAttribute('style', convertStyle(rippleStyle));
            ripple.className = ripple.className.replace('tvd-waves-notransition', '');

            // Scale the ripple
            rippleStyle['-webkit-transform'] = scale;
            rippleStyle['-moz-transform'] = scale;
            rippleStyle['-ms-transform'] = scale;
            rippleStyle['-o-transform'] = scale;
            rippleStyle.transform = scale;
            rippleStyle.opacity   = '1';

            rippleStyle['-webkit-transition-duration'] = Effect.duration + 'ms';
            rippleStyle['-moz-transition-duration']    = Effect.duration + 'ms';
            rippleStyle['-o-transition-duration']      = Effect.duration + 'ms';
            rippleStyle['transition-duration']         = Effect.duration + 'ms';

            rippleStyle['-webkit-transition-timing-function'] = 'cubic-bezier(0.250, 0.460, 0.450, 0.940)';
            rippleStyle['-moz-transition-timing-function']    = 'cubic-bezier(0.250, 0.460, 0.450, 0.940)';
            rippleStyle['-o-transition-timing-function']      = 'cubic-bezier(0.250, 0.460, 0.450, 0.940)';
            rippleStyle['transition-timing-function']         = 'cubic-bezier(0.250, 0.460, 0.450, 0.940)';

            ripple.setAttribute('style', convertStyle(rippleStyle));
        },

        hide: function(e) {
            TouchHandler.touchup(e);

            var el = this;
            var width = el.clientWidth * 1.4;

            // Get first ripple
            var ripple = null;
            var ripples = el.getElementsByClassName('tvd-waves-ripple');
            if (ripples.length > 0) {
                ripple = ripples[ripples.length - 1];
            } else {
                return false;
            }

            var relativeX   = ripple.getAttribute('data-x');
            var relativeY   = ripple.getAttribute('data-y');
            var scale       = ripple.getAttribute('data-scale');

            // Get delay beetween mousedown and mouse leave
            var diff = Date.now() - Number(ripple.getAttribute('data-hold'));
            var delay = 350 - diff;

            if (delay < 0) {
                delay = 0;
            }

            // Fade out ripple after delay
            setTimeout(function() {
                var style = {
                    'top': relativeY+'px',
                    'left': relativeX+'px',
                    'opacity': '0',

                    // Duration
                    '-webkit-transition-duration': Effect.duration + 'ms',
                    '-moz-transition-duration': Effect.duration + 'ms',
                    '-o-transition-duration': Effect.duration + 'ms',
                    'transition-duration': Effect.duration + 'ms',
                    '-webkit-transform': scale,
                    '-moz-transform': scale,
                    '-ms-transform': scale,
                    '-o-transform': scale,
                    'transform': scale,
                };

                ripple.setAttribute('style', convertStyle(style));

                setTimeout(function() {
                    try {
                        el.removeChild(ripple);
                    } catch(e) {
                        return false;
                    }
                }, Effect.duration);
            }, delay);
        },

        // Little hack to make <input> can perform waves effect
        wrapInput: function(elements) {
            for (var a = 0; a < elements.length; a++) {
                var el = elements[a];

                if (el.tagName.toLowerCase() === 'input') {
                    var parent = el.parentNode;

                    // If input already have parent just pass through
                    if (parent.tagName.toLowerCase() === 'i' && parent.className.indexOf('tvd-waves-effect') !== -1) {
                        continue;
                    }

                    // Put element class and style to the specified parent
                    var wrapper = document.createElement('i');
                    wrapper.className = el.className + ' tvd-waves-input-wrapper';

                    var elementStyle = el.getAttribute('style');

                    if (!elementStyle) {
                        elementStyle = '';
                    }

                    wrapper.setAttribute('style', elementStyle);

                    el.className = 'tvd-waves-button-input';
                    el.removeAttribute('style');

                    // Put element as child
                    parent.replaceChild(wrapper, el);
                    wrapper.appendChild(el);
                }
            }
        }
    };


    /**
     * Disable mousedown event for 500ms during and after touch
     */
    var TouchHandler = {
        /* uses an integer rather than bool so there's no issues with
         * needing to clear timeouts if another touch event occurred
         * within the 500ms. Cannot mouseup between touchstart and
         * touchend, nor in the 500ms after touchend. */
        touches: 0,
        allowEvent: function(e) {
            var allow = true;

            if (e.type === 'touchstart') {
                TouchHandler.touches += 1; //push
            } else if (e.type === 'touchend' || e.type === 'touchcancel') {
                setTimeout(function() {
                    if (TouchHandler.touches > 0) {
                        TouchHandler.touches -= 1; //pop after 500ms
                    }
                }, 500);
            } else if (e.type === 'mousedown' && TouchHandler.touches > 0) {
                allow = false;
            }

            return allow;
        },
        touchup: function(e) {
            TouchHandler.allowEvent(e);
        }
    };


    /**
     * Delegated click handler for .waves-effect element.
     * returns null when .waves-effect element not in "click tree"
     */
    function getWavesEffectElement(e) {
        if (TouchHandler.allowEvent(e) === false) {
            return null;
        }

        var element = null;
        var target = e.target || e.srcElement;

        while (target.parentElement !== null) {
            if (!(target instanceof SVGElement) && target.className.indexOf('tvd-waves-effect') !== -1) {
                element = target;
                break;
            } else if (target.classList.contains('tvd-waves-effect')) {
                element = target;
                break;
            }
            target = target.parentElement;
        }

        return element;
    }

    /**
     * Bubble the click and show effect if .waves-effect elem was found
     */
    function showEffect(e) {
        var element = getWavesEffectElement(e);

        if (element !== null) {
            Effect.show(e, element);

            if ('ontouchstart' in window) {
                element.addEventListener('touchend', Effect.hide, false);
                element.addEventListener('touchcancel', Effect.hide, false);
            }

            element.addEventListener('mouseup', Effect.hide, false);
            element.addEventListener('mouseleave', Effect.hide, false);
        }
    }

    Waves.displayEffect = function(options) {
        options = options || {};

        if ('duration' in options) {
            Effect.duration = options.duration;
        }

        //Wrap input inside <i> tag
        Effect.wrapInput($$('.tvd-waves-effect'));

        if ('ontouchstart' in window) {
            document.body.addEventListener('touchstart', showEffect, false);
        }

        document.body.addEventListener('mousedown', showEffect, false);
    };

    /**
     * Attach Waves to an input element (or any element which doesn't
     * bubble mouseup/mousedown events).
     *   Intended to be used with dynamically loaded forms/inputs, or
     * where the user doesn't want a delegated click handler.
     */
    Waves.attach = function(element) {
        //FUTURE: automatically add waves classes and allow users
        // to specify them with an options param? Eg. light/classic/button
        if (element.tagName.toLowerCase() === 'input') {
            Effect.wrapInput([element]);
            element = element.parentElement;
        }

        if ('ontouchstart' in window) {
            element.addEventListener('touchstart', showEffect, false);
        }

        element.addEventListener('mousedown', showEffect, false);
    };

    window.Waves = Waves;

    document.addEventListener('DOMContentLoaded', function() {
        Waves.displayEffect();
    }, false);

})(window);
;(
	function ( $ ) {
		var methods = {
			init: function () {
				return this.each( function () {

					// For each set of tabs, we want to keep track of
					// which tab is active and its associated content
					var $this = $( this );

					$this.width( '100%' );
					var $active, $content, $links = $this.find( 'li.tvd-tab a' ),
						$tabs_width = $this.width(),
						$tab_width = $this.find( 'li' ).first().outerWidth(),
						$tab_min_width = parseInt( $this.find( 'li' ).first().css( 'minWidth' ) ),
						$index = 0,
						$indicator = $this.find( '.tvd-indicator' );
					// If the location.hash matches one of the links, use that as the active tab.
					$active = $( $links.filter( '[href="' + location.hash + '"]' ) );

					// If no match is found, use the first link or any with class 'active' as the initial active tab.
					if ( $active.length === 0 ) {
						$active = $( this ).find( 'li.tvd-tab a.active' ).first();
					}
					if ( $active.length === 0 ) {
						$active = $( this ).find( 'li.tvd-tab a' ).first();
					}

					$active.addClass( 'tvd-active' );
					$index = $links.index( $active );
					if ( $index < 0 ) {
						$index = 0;
					}

					$content = $( $active[0].hash );

					// append indicator then set indicator width to tab width
					if ( $indicator.length === 0 ) {
						$indicator = $( '<div class="tvd-indicator"></div>' );
						$this.append( $indicator );
					}
					if ( $this.is( ":visible" ) ) {
						$indicator.css( {
							"right": $tabs_width - (
								(
									$index + 1
								) * $tab_width
							)
						} );
						$indicator.css( {"left": $index * $tab_width} );
					}
					$( window ).resize( function () {
						$tabs_width = $this.width();
						$tab_width = $this.find( 'li' ).first().outerWidth();
						if ( $index < 0 ) {
							$index = 0;
						}
						if ( $tab_width !== 0 && $tabs_width !== 0 ) {
							$indicator.css( {
								"right": $tabs_width - (
									(
										$index + 1
									) * $tab_width
								)
							} );
							$indicator.css( {"left": $index * $tab_width} );
						}
					} );

					// Hide the remaining content
					$links.not( $active ).each( function () {
						$( this.hash ).hide();
					} );


					// Bind the click event handler
					$this.on( 'click', 'a', function ( e ) {
						if ( $( this ).parent().hasClass( 'tvd-disabled' ) ) {
							e.preventDefault();
							return;
						}

						$tabs_width = $this.width();
						$tab_width = $this.find( 'li' ).first().outerWidth();

						// Make the old tab inactive.
						$active.removeClass( 'tvd-active' );
						$content.hide();

						// Update the variables with the new link and content
						$active = $( this );
						$content = $( this.hash );
						$links = $this.find( 'li.tvd-tab a' );

						// Make the tab active.
						$active.addClass( 'tvd-active' );
						var $prev_index = $index;
						$index = $links.index( $( this ) );
						if ( $index < 0 ) {
							$index = 0;
						}
						// Change url to current tab
						// window.location.hash = $active.attr('href');

						$content.show();

						// Update indicator
						if ( (
							     $index - $prev_index
						     ) >= 0 ) {
							$indicator.velocity( {
								"right": $tabs_width - (
									(
										$index + 1
									) * $tab_width
								)
							}, {duration: 300, queue: false, easing: 'easeOutQuad'} );
							$indicator.velocity( {"left": $index * $tab_width}, {duration: 300, queue: false, easing: 'easeOutQuad', delay: 90} );

						}
						else {
							$indicator.velocity( {"left": $index * $tab_width}, {duration: 300, queue: false, easing: 'easeOutQuad'} );
							$indicator.velocity( {
								"right": $tabs_width - (
									(
										$index + 1
									) * $tab_width
								)
							}, {duration: 300, queue: false, easing: 'easeOutQuad', delay: 90} );
						}

						// Prevent the anchor's default click action
						e.preventDefault();
					} );

					// Add scroll for small screens
					if ( $tab_width <= $tab_min_width ) {
						$this.wrap( '<div class="tvd-hide-tab-scrollbar"></div>' );

						// Create the measurement node
						var scrollDiv = document.createElement( "div" );
						scrollDiv.className = "tvd-scrollbar-measure";
						document.body.appendChild( scrollDiv );
						var scrollbarHeight = scrollDiv.offsetHeight - scrollDiv.clientHeight;
						document.body.removeChild( scrollDiv );

						if ( scrollbarHeight === 0 ) {
							scrollbarHeight = 15;
							$this.find( '.tvd-indicator' ).css( 'bottom', scrollbarHeight );
						}
						$this.height( $( this ).height() + scrollbarHeight );
					}
				} );

			},
			select_tab: function ( id ) {
				this.find( 'a[href="#' + id + '"]' ).trigger( 'click' );
			}
		};

		$.fn.tvd_tabs = function ( methodOrOptions ) {
			if ( methods[methodOrOptions] ) {
				return methods[methodOrOptions].apply( this, Array.prototype.slice.call( arguments, 1 ) );
			} else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
				// Default to "init"
				return methods.init.apply( this, arguments );
			} else {
				$.error( 'Method ' + methodOrOptions + ' does not exist on jQuery.tooltip' );
			}
		};

		$( document ).ready( function () {
			$( 'ul.tvd-tabs' ).tvd_tabs();
		} );
	}( jQuery )
);
;/*!
 * typeahead.js 0.11.1
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2015 Twitter, Inc. and other contributors; Licensed MIT
 */

(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        define("typeahead.js", [ "jquery" ], function(a0) {
            return factory(a0);
        });
    } else if (typeof exports === "object") {
        module.exports = factory(require("jquery"));
    } else {
        factory(jQuery);
    }
})(this, function($) {
    var _ = function() {
        "use strict";
        return {
            isMsie: function() {
                return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : false;
            },
            isBlankString: function(str) {
                return !str || /^\s*$/.test(str);
            },
            escapeRegExChars: function(str) {
                return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            },
            isString: function(obj) {
                return typeof obj === "string";
            },
            isNumber: function(obj) {
                return typeof obj === "number";
            },
            isArray: $.isArray,
            isFunction: $.isFunction,
            isObject: $.isPlainObject,
            isUndefined: function(obj) {
                return typeof obj === "undefined";
            },
            isElement: function(obj) {
                return !!(obj && obj.nodeType === 1);
            },
            isJQuery: function(obj) {
                return obj instanceof $;
            },
            toStr: function toStr(s) {
                return _.isUndefined(s) || s === null ? "" : s + "";
            },
            bind: $.proxy,
            each: function(collection, cb) {
                $.each(collection, reverseArgs);
                function reverseArgs(index, value) {
                    return cb(value, index);
                }
            },
            map: $.map,
            filter: $.grep,
            every: function(obj, test) {
                var result = true;
                if (!obj) {
                    return result;
                }
                $.each(obj, function(key, val) {
                    if (!(result = test.call(null, val, key, obj))) {
                        return false;
                    }
                });
                return !!result;
            },
            some: function(obj, test) {
                var result = false;
                if (!obj) {
                    return result;
                }
                $.each(obj, function(key, val) {
                    if (result = test.call(null, val, key, obj)) {
                        return false;
                    }
                });
                return !!result;
            },
            mixin: $.extend,
            identity: function(x) {
                return x;
            },
            clone: function(obj) {
                return $.extend(true, {}, obj);
            },
            getIdGenerator: function() {
                var counter = 0;
                return function() {
                    return counter++;
                };
            },
            templatify: function templatify(obj) {
                return $.isFunction(obj) ? obj : template;
                function template() {
                    return String(obj);
                }
            },
            defer: function(fn) {
                setTimeout(fn, 0);
            },
            debounce: function(func, wait, immediate) {
                var timeout, result;
                return function() {
                    var context = this, args = arguments, later, callNow;
                    later = function() {
                        timeout = null;
                        if (!immediate) {
                            result = func.apply(context, args);
                        }
                    };
                    callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) {
                        result = func.apply(context, args);
                    }
                    return result;
                };
            },
            throttle: function(func, wait) {
                var context, args, timeout, result, previous, later;
                previous = 0;
                later = function() {
                    previous = new Date();
                    timeout = null;
                    result = func.apply(context, args);
                };
                return function() {
                    var now = new Date(), remaining = wait - (now - previous);
                    context = this;
                    args = arguments;
                    if (remaining <= 0) {
                        clearTimeout(timeout);
                        timeout = null;
                        previous = now;
                        result = func.apply(context, args);
                    } else if (!timeout) {
                        timeout = setTimeout(later, remaining);
                    }
                    return result;
                };
            },
            stringify: function(val) {
                return _.isString(val) ? val : JSON.stringify(val);
            },
            noop: function() {}
        };
    }();
    var WWW = function() {
        "use strict";
        var defaultClassNames = {
            wrapper: "twitter-typeahead",
            input: "tt-input",
            hint: "tt-hint",
            menu: "tt-menu",
            dataset: "tt-dataset",
            suggestion: "tt-suggestion",
            selectable: "tt-selectable",
            empty: "tt-empty",
            open: "tt-open",
            cursor: "tt-cursor",
            highlight: "tt-highlight"
        };
        return build;
        function build(o) {
            var www, classes;
            classes = _.mixin({}, defaultClassNames, o);
            www = {
                css: buildCss(),
                classes: classes,
                html: buildHtml(classes),
                selectors: buildSelectors(classes)
            };
            return {
                css: www.css,
                html: www.html,
                classes: www.classes,
                selectors: www.selectors,
                mixin: function(o) {
                    _.mixin(o, www);
                }
            };
        }
        function buildHtml(c) {
            return {
                wrapper: '<span class="' + c.wrapper + '"></span>',
                menu: '<div class="' + c.menu + '"></div>'
            };
        }
        function buildSelectors(classes) {
            var selectors = {};
            _.each(classes, function(v, k) {
                selectors[k] = "." + v;
            });
            return selectors;
        }
        function buildCss() {
            var css = {
                wrapper: {
                    position: "relative",
                    display: "inline-block"
                },
                hint: {
                    position: "absolute",
                    top: "0",
                    left: "0",
                    borderColor: "transparent",
                    boxShadow: "none",
                    opacity: "1"
                },
                input: {
                    position: "relative",
                    verticalAlign: "top",
                    backgroundColor: "transparent"
                },
                inputWithNoHint: {
                    position: "relative",
                    verticalAlign: "top"
                },
                menu: {
                    position: "absolute",
                    top: "100%",
                    left: "0",
                    zIndex: "100",
                    display: "none"
                },
                ltr: {
                    left: "0",
                    right: "auto"
                },
                rtl: {
                    left: "auto",
                    right: " 0"
                }
            };
            if (_.isMsie()) {
                _.mixin(css.input, {
                    backgroundImage: "url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)"
                });
            }
            return css;
        }
    }();
    var EventBus = function() {
        "use strict";
        var namespace, deprecationMap;
        namespace = "typeahead:";
        deprecationMap = {
            render: "rendered",
            cursorchange: "cursorchanged",
            select: "selected",
            autocomplete: "autocompleted"
        };
        function EventBus(o) {
            if (!o || !o.el) {
                $.error("EventBus initialized without el");
            }
            this.$el = $(o.el);
        }
        _.mixin(EventBus.prototype, {
            _trigger: function(type, args) {
                var $e;
                $e = $.Event(namespace + type);
                (args = args || []).unshift($e);
                this.$el.trigger.apply(this.$el, args);
                return $e;
            },
            before: function(type) {
                var args, $e;
                args = [].slice.call(arguments, 1);
                $e = this._trigger("before" + type, args);
                return $e.isDefaultPrevented();
            },
            trigger: function(type) {
                var deprecatedType;
                this._trigger(type, [].slice.call(arguments, 1));
                if (deprecatedType = deprecationMap[type]) {
                    this._trigger(deprecatedType, [].slice.call(arguments, 1));
                }
            }
        });
        return EventBus;
    }();
    var EventEmitter = function() {
        "use strict";
        var splitter = /\s+/, nextTick = getNextTick();
        return {
            onSync: onSync,
            onAsync: onAsync,
            off: off,
            trigger: trigger
        };
        function on(method, types, cb, context) {
            var type;
            if (!cb) {
                return this;
            }
            types = types.split(splitter);
            cb = context ? bindContext(cb, context) : cb;
            this._callbacks = this._callbacks || {};
            while (type = types.shift()) {
                this._callbacks[type] = this._callbacks[type] || {
                        sync: [],
                        async: []
                    };
                this._callbacks[type][method].push(cb);
            }
            return this;
        }
        function onAsync(types, cb, context) {
            return on.call(this, "async", types, cb, context);
        }
        function onSync(types, cb, context) {
            return on.call(this, "sync", types, cb, context);
        }
        function off(types) {
            var type;
            if (!this._callbacks) {
                return this;
            }
            types = types.split(splitter);
            while (type = types.shift()) {
                delete this._callbacks[type];
            }
            return this;
        }
        function trigger(types) {
            var type, callbacks, args, syncFlush, asyncFlush;
            if (!this._callbacks) {
                return this;
            }
            types = types.split(splitter);
            args = [].slice.call(arguments, 1);
            while ((type = types.shift()) && (callbacks = this._callbacks[type])) {
                syncFlush = getFlush(callbacks.sync, this, [ type ].concat(args));
                asyncFlush = getFlush(callbacks.async, this, [ type ].concat(args));
                syncFlush() && nextTick(asyncFlush);
            }
            return this;
        }
        function getFlush(callbacks, context, args) {
            return flush;
            function flush() {
                var cancelled;
                for (var i = 0, len = callbacks.length; !cancelled && i < len; i += 1) {
                    cancelled = callbacks[i].apply(context, args) === false;
                }
                return !cancelled;
            }
        }
        function getNextTick() {
            var nextTickFn;
            if (window.setImmediate) {
                nextTickFn = function nextTickSetImmediate(fn) {
                    setImmediate(function() {
                        fn();
                    });
                };
            } else {
                nextTickFn = function nextTickSetTimeout(fn) {
                    setTimeout(function() {
                        fn();
                    }, 0);
                };
            }
            return nextTickFn;
        }
        function bindContext(fn, context) {
            return fn.bind ? fn.bind(context) : function() {
                fn.apply(context, [].slice.call(arguments, 0));
            };
        }
    }();
    var highlight = function(doc) {
        "use strict";
        var defaults = {
            node: null,
            pattern: null,
            tagName: "strong",
            className: null,
            wordsOnly: false,
            caseSensitive: false
        };
        return function hightlight(o) {
            var regex;
            o = _.mixin({}, defaults, o);
            if (!o.node || !o.pattern) {
                return;
            }
            o.pattern = _.isArray(o.pattern) ? o.pattern : [ o.pattern ];
            regex = getRegex(o.pattern, o.caseSensitive, o.wordsOnly);
            traverse(o.node, hightlightTextNode);
            function hightlightTextNode(textNode) {
                var match, patternNode, wrapperNode;
                if (match = regex.exec(textNode.data)) {
                    wrapperNode = doc.createElement(o.tagName);
                    o.className && (wrapperNode.className = o.className);
                    patternNode = textNode.splitText(match.index);
                    patternNode.splitText(match[0].length);
                    wrapperNode.appendChild(patternNode.cloneNode(true));
                    textNode.parentNode.replaceChild(wrapperNode, patternNode);
                }
                return !!match;
            }
            function traverse(el, hightlightTextNode) {
                var childNode, TEXT_NODE_TYPE = 3;
                for (var i = 0; i < el.childNodes.length; i++) {
                    childNode = el.childNodes[i];
                    if (childNode.nodeType === TEXT_NODE_TYPE) {
                        i += hightlightTextNode(childNode) ? 1 : 0;
                    } else {
                        traverse(childNode, hightlightTextNode);
                    }
                }
            }
        };
        function getRegex(patterns, caseSensitive, wordsOnly) {
            var escapedPatterns = [], regexStr;
            for (var i = 0, len = patterns.length; i < len; i++) {
                escapedPatterns.push(_.escapeRegExChars(patterns[i]));
            }
            regexStr = wordsOnly ? "\\b(" + escapedPatterns.join("|") + ")\\b" : "(" + escapedPatterns.join("|") + ")";
            return caseSensitive ? new RegExp(regexStr) : new RegExp(regexStr, "i");
        }
    }(window.document);
    var Input = function() {
        "use strict";
        var specialKeyCodeMap;
        specialKeyCodeMap = {
            9: "tab",
            27: "esc",
            37: "left",
            39: "right",
            13: "enter",
            38: "up",
            40: "down"
        };
        function Input(o, www) {
            o = o || {};
            if (!o.input) {
                $.error("input is missing");
            }
            www.mixin(this);
            this.$hint = $(o.hint);
            this.$input = $(o.input);
            this.query = this.$input.val();
            this.queryWhenFocused = this.hasFocus() ? this.query : null;
            this.$overflowHelper = buildOverflowHelper(this.$input);
            this._checkLanguageDirection();
            if (this.$hint.length === 0) {
                this.setHint = this.getHint = this.clearHint = this.clearHintIfInvalid = _.noop;
            }
        }
        Input.normalizeQuery = function(str) {
            return _.toStr(str).replace(/^\s*/g, "").replace(/\s{2,}/g, " ");
        };
        _.mixin(Input.prototype, EventEmitter, {
            _onBlur: function onBlur() {
                this.resetInputValue();
                this.trigger("blurred");
            },
            _onFocus: function onFocus() {
                this.queryWhenFocused = this.query;
                this.trigger("focused");
            },
            _onKeydown: function onKeydown($e) {
                var keyName = specialKeyCodeMap[$e.which || $e.keyCode];
                this._managePreventDefault(keyName, $e);
                if (keyName && this._shouldTrigger(keyName, $e)) {
                    this.trigger(keyName + "Keyed", $e);
                }
            },
            _onInput: function onInput() {
                this._setQuery(this.getInputValue());
                this.clearHintIfInvalid();
                this._checkLanguageDirection();
            },
            _managePreventDefault: function managePreventDefault(keyName, $e) {
                var preventDefault;
                switch (keyName) {
                    case "up":
                    case "down":
                        preventDefault = !withModifier($e);
                        break;

                    default:
                        preventDefault = false;
                }
                preventDefault && $e.preventDefault();
            },
            _shouldTrigger: function shouldTrigger(keyName, $e) {
                var trigger;
                switch (keyName) {
                    case "tab":
                        trigger = !withModifier($e);
                        break;

                    default:
                        trigger = true;
                }
                return trigger;
            },
            _checkLanguageDirection: function checkLanguageDirection() {
                var dir = (this.$input.css("direction") || "ltr").toLowerCase();
                if (this.dir !== dir) {
                    this.dir = dir;
                    this.$hint.attr("dir", dir);
                    this.trigger("langDirChanged", dir);
                }
            },
            _setQuery: function setQuery(val, silent) {
                var areEquivalent, hasDifferentWhitespace;
                areEquivalent = areQueriesEquivalent(val, this.query);
                hasDifferentWhitespace = areEquivalent ? this.query.length !== val.length : false;
                this.query = val;
                if (!silent && !areEquivalent) {
                    this.trigger("queryChanged", this.query);
                } else if (!silent && hasDifferentWhitespace) {
                    this.trigger("whitespaceChanged", this.query);
                }
            },
            bind: function() {
                var that = this, onBlur, onFocus, onKeydown, onInput;
                onBlur = _.bind(this._onBlur, this);
                onFocus = _.bind(this._onFocus, this);
                onKeydown = _.bind(this._onKeydown, this);
                onInput = _.bind(this._onInput, this);
                this.$input.on("blur.tt", onBlur).on("focus.tt", onFocus).on("keydown.tt", onKeydown);
                if (!_.isMsie() || _.isMsie() > 9) {
                    this.$input.on("input.tt", onInput);
                } else {
                    this.$input.on("keydown.tt keypress.tt cut.tt paste.tt", function($e) {
                        if (specialKeyCodeMap[$e.which || $e.keyCode]) {
                            return;
                        }
                        _.defer(_.bind(that._onInput, that, $e));
                    });
                }
                return this;
            },
            focus: function focus() {
                this.$input.focus();
            },
            blur: function blur() {
                this.$input.blur();
            },
            getLangDir: function getLangDir() {
                return this.dir;
            },
            getQuery: function getQuery() {
                return this.query || "";
            },
            setQuery: function setQuery(val, silent) {
                this.setInputValue(val);
                this._setQuery(val, silent);
            },
            hasQueryChangedSinceLastFocus: function hasQueryChangedSinceLastFocus() {
                return this.query !== this.queryWhenFocused;
            },
            getInputValue: function getInputValue() {
                return this.$input.val();
            },
            setInputValue: function setInputValue(value) {
                this.$input.val(value);
                this.clearHintIfInvalid();
                this._checkLanguageDirection();
            },
            resetInputValue: function resetInputValue() {
                this.setInputValue(this.query);
            },
            getHint: function getHint() {
                return this.$hint.val();
            },
            setHint: function setHint(value) {
                this.$hint.val(value);
            },
            clearHint: function clearHint() {
                this.setHint("");
            },
            clearHintIfInvalid: function clearHintIfInvalid() {
                var val, hint, valIsPrefixOfHint, isValid;
                val = this.getInputValue();
                hint = this.getHint();
                valIsPrefixOfHint = val !== hint && hint.indexOf(val) === 0;
                isValid = val !== "" && valIsPrefixOfHint && !this.hasOverflow();
                !isValid && this.clearHint();
            },
            hasFocus: function hasFocus() {
                return this.$input.is(":focus");
            },
            hasOverflow: function hasOverflow() {
                var constraint = this.$input.width() - 2;
                this.$overflowHelper.text(this.getInputValue());
                return this.$overflowHelper.width() >= constraint;
            },
            isCursorAtEnd: function() {
                var valueLength, selectionStart, range;
                valueLength = this.$input.val().length;
                selectionStart = this.$input[0].selectionStart;
                if (_.isNumber(selectionStart)) {
                    return selectionStart === valueLength;
                } else if (document.selection) {
                    range = document.selection.createRange();
                    range.moveStart("character", -valueLength);
                    return valueLength === range.text.length;
                }
                return true;
            },
            destroy: function destroy() {
                this.$hint.off(".tt");
                this.$input.off(".tt");
                this.$overflowHelper.remove();
                this.$hint = this.$input = this.$overflowHelper = $("<div>");
            }
        });
        return Input;
        function buildOverflowHelper($input) {
            return $('<pre aria-hidden="true"></pre>').css({
                position: "absolute",
                visibility: "hidden",
                whiteSpace: "pre",
                fontFamily: $input.css("font-family"),
                fontSize: $input.css("font-size"),
                fontStyle: $input.css("font-style"),
                fontVariant: $input.css("font-variant"),
                fontWeight: $input.css("font-weight"),
                wordSpacing: $input.css("word-spacing"),
                letterSpacing: $input.css("letter-spacing"),
                textIndent: $input.css("text-indent"),
                textRendering: $input.css("text-rendering"),
                textTransform: $input.css("text-transform")
            }).insertAfter($input);
        }
        function areQueriesEquivalent(a, b) {
            return Input.normalizeQuery(a) === Input.normalizeQuery(b);
        }
        function withModifier($e) {
            return $e.altKey || $e.ctrlKey || $e.metaKey || $e.shiftKey;
        }
    }();
    var Dataset = function() {
        "use strict";
        var keys, nameGenerator;
        keys = {
            val: "tt-selectable-display",
            obj: "tt-selectable-object"
        };
        nameGenerator = _.getIdGenerator();
        function Dataset(o, www) {
            o = o || {};
            o.templates = o.templates || {};
            o.templates.notFound = o.templates.notFound || o.templates.empty;
            if (!o.source) {
                $.error("missing source");
            }
            if (!o.node) {
                $.error("missing node");
            }
            if (o.name && !isValidName(o.name)) {
                $.error("invalid dataset name: " + o.name);
            }
            www.mixin(this);
            this.highlight = !!o.highlight;
            this.name = o.name || nameGenerator();
            this.limit = o.limit || 5;
            this.displayFn = getDisplayFn(o.display || o.displayKey);
            this.templates = getTemplates(o.templates, this.displayFn);
            this.source = o.source.__ttAdapter ? o.source.__ttAdapter() : o.source;
            this.async = _.isUndefined(o.async) ? this.source.length > 2 : !!o.async;
            this._resetLastSuggestion();
            this.$el = $(o.node).addClass(this.classes.dataset).addClass(this.classes.dataset + "-" + this.name);
        }
        Dataset.extractData = function extractData(el) {
            var $el = $(el);
            if ($el.data(keys.obj)) {
                return {
                    val: $el.data(keys.val) || "",
                    obj: $el.data(keys.obj) || null
                };
            }
            return null;
        };
        _.mixin(Dataset.prototype, EventEmitter, {
            _overwrite: function overwrite(query, suggestions) {
                suggestions = suggestions || [];
                if (suggestions.length) {
                    this._renderSuggestions(query, suggestions);
                } else if (this.async && this.templates.pending) {
                    this._renderPending(query);
                } else if (!this.async && this.templates.notFound) {
                    this._renderNotFound(query);
                } else {
                    this._empty();
                }
                this.trigger("rendered", this.name, suggestions, false);
            },
            _append: function append(query, suggestions) {
                suggestions = suggestions || [];
                if (suggestions.length && this.$lastSuggestion.length) {
                    this._appendSuggestions(query, suggestions);
                } else if (suggestions.length) {
                    this._renderSuggestions(query, suggestions);
                } else if (!this.$lastSuggestion.length && this.templates.notFound) {
                    this._renderNotFound(query);
                }
                this.trigger("rendered", this.name, suggestions, true);
            },
            _renderSuggestions: function renderSuggestions(query, suggestions) {
                var $fragment;
                $fragment = this._getSuggestionsFragment(query, suggestions);
                this.$lastSuggestion = $fragment.children().last();
                this.$el.html($fragment).prepend(this._getHeader(query, suggestions)).append(this._getFooter(query, suggestions));
            },
            _appendSuggestions: function appendSuggestions(query, suggestions) {
                var $fragment, $lastSuggestion;
                $fragment = this._getSuggestionsFragment(query, suggestions);
                $lastSuggestion = $fragment.children().last();
                this.$lastSuggestion.after($fragment);
                this.$lastSuggestion = $lastSuggestion;
            },
            _renderPending: function renderPending(query) {
                var template = this.templates.pending;
                this._resetLastSuggestion();
                template && this.$el.html(template({
                    query: query,
                    dataset: this.name
                }));
            },
            _renderNotFound: function renderNotFound(query) {
                var template = this.templates.notFound;
                this._resetLastSuggestion();
                template && this.$el.html(template({
                    query: query,
                    dataset: this.name
                }));
            },
            _empty: function empty() {
                this.$el.empty();
                this._resetLastSuggestion();
            },
            _getSuggestionsFragment: function getSuggestionsFragment(query, suggestions) {
                var that = this, fragment;
                fragment = document.createDocumentFragment();
                _.each(suggestions, function getSuggestionNode(suggestion) {
                    var $el, context;
                    context = that._injectQuery(query, suggestion);
                    $el = $(that.templates.suggestion(context)).data(keys.obj, suggestion).data(keys.val, that.displayFn(suggestion)).addClass(that.classes.suggestion + " " + that.classes.selectable);
                    fragment.appendChild($el[0]);
                });
                this.highlight && highlight({
                    className: this.classes.highlight,
                    node: fragment,
                    pattern: query
                });
                return $(fragment);
            },
            _getFooter: function getFooter(query, suggestions) {
                return this.templates.footer ? this.templates.footer({
                    query: query,
                    suggestions: suggestions,
                    dataset: this.name
                }) : null;
            },
            _getHeader: function getHeader(query, suggestions) {
                return this.templates.header ? this.templates.header({
                    query: query,
                    suggestions: suggestions,
                    dataset: this.name
                }) : null;
            },
            _resetLastSuggestion: function resetLastSuggestion() {
                this.$lastSuggestion = $();
            },
            _injectQuery: function injectQuery(query, obj) {
                return _.isObject(obj) ? _.mixin({
                    _query: query
                }, obj) : obj;
            },
            update: function update(query) {
                var that = this, canceled = false, syncCalled = false, rendered = 0;
                this.cancel();
                this.cancel = function cancel() {
                    canceled = true;
                    that.cancel = $.noop;
                    that.async && that.trigger("asyncCanceled", query);
                };
                this.source(query, sync, async);
                !syncCalled && sync([]);
                function sync(suggestions) {
                    if (syncCalled) {
                        return;
                    }
                    syncCalled = true;
                    suggestions = (suggestions || []).slice(0, that.limit);
                    rendered = suggestions.length;
                    that._overwrite(query, suggestions);
                    if (rendered < that.limit && that.async) {
                        that.trigger("asyncRequested", query);
                    }
                }
                function async(suggestions) {
                    suggestions = suggestions || [];
                    if (!canceled && rendered < that.limit) {
                        that.cancel = $.noop;
                        rendered += suggestions.length;
                        that._append(query, suggestions.slice(0, that.limit - rendered));
                        that.async && that.trigger("asyncReceived", query);
                    }
                }
            },
            cancel: $.noop,
            clear: function clear() {
                this._empty();
                this.cancel();
                this.trigger("cleared");
            },
            isEmpty: function isEmpty() {
                return this.$el.is(":empty");
            },
            destroy: function destroy() {
                this.$el = $("<div>");
            }
        });
        return Dataset;
        function getDisplayFn(display) {
            display = display || _.stringify;
            return _.isFunction(display) ? display : displayFn;
            function displayFn(obj) {
                return obj[display];
            }
        }
        function getTemplates(templates, displayFn) {
            return {
                notFound: templates.notFound && _.templatify(templates.notFound),
                pending: templates.pending && _.templatify(templates.pending),
                header: templates.header && _.templatify(templates.header),
                footer: templates.footer && _.templatify(templates.footer),
                suggestion: templates.suggestion || suggestionTemplate
            };
            function suggestionTemplate(context) {
                return $("<div>").text(displayFn(context));
            }
        }
        function isValidName(str) {
            return /^[_a-zA-Z0-9-]+$/.test(str);
        }
    }();
    var Menu = function() {
        "use strict";
        function Menu(o, www) {
            var that = this;
            o = o || {};
            if (!o.node) {
                $.error("node is required");
            }
            www.mixin(this);
            this.$node = $(o.node);
            this.query = null;
            this.datasets = _.map(o.datasets, initializeDataset);
            function initializeDataset(oDataset) {
                var node = that.$node.find(oDataset.node).first();
                oDataset.node = node.length ? node : $("<div>").appendTo(that.$node);
                return new Dataset(oDataset, www);
            }
        }
        _.mixin(Menu.prototype, EventEmitter, {
            _onSelectableClick: function onSelectableClick($e) {
                this.trigger("selectableClicked", $($e.currentTarget));
            },
            _onRendered: function onRendered(type, dataset, suggestions, async) {
                this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty());
                this.trigger("datasetRendered", dataset, suggestions, async);
            },
            _onCleared: function onCleared() {
                this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty());
                this.trigger("datasetCleared");
            },
            _propagate: function propagate() {
                this.trigger.apply(this, arguments);
            },
            _allDatasetsEmpty: function allDatasetsEmpty() {
                return _.every(this.datasets, isDatasetEmpty);
                function isDatasetEmpty(dataset) {
                    return dataset.isEmpty();
                }
            },
            _getSelectables: function getSelectables() {
                return this.$node.find(this.selectors.selectable);
            },
            _removeCursor: function _removeCursor() {
                var $selectable = this.getActiveSelectable();
                $selectable && $selectable.removeClass(this.classes.cursor);
            },
            _ensureVisible: function ensureVisible($el) {
                var elTop, elBottom, nodeScrollTop, nodeHeight;
                elTop = $el.position().top;
                elBottom = elTop + $el.outerHeight(true);
                nodeScrollTop = this.$node.scrollTop();
                nodeHeight = this.$node.height() + parseInt(this.$node.css("paddingTop"), 10) + parseInt(this.$node.css("paddingBottom"), 10);
                if (elTop < 0) {
                    this.$node.scrollTop(nodeScrollTop + elTop);
                } else if (nodeHeight < elBottom) {
                    this.$node.scrollTop(nodeScrollTop + (elBottom - nodeHeight));
                }
            },
            bind: function() {
                var that = this, onSelectableClick;
                onSelectableClick = _.bind(this._onSelectableClick, this);
                this.$node.on("click.tt", this.selectors.selectable, onSelectableClick);
                _.each(this.datasets, function(dataset) {
                    dataset.onSync("asyncRequested", that._propagate, that).onSync("asyncCanceled", that._propagate, that).onSync("asyncReceived", that._propagate, that).onSync("rendered", that._onRendered, that).onSync("cleared", that._onCleared, that);
                });
                return this;
            },
            isOpen: function isOpen() {
                return this.$node.hasClass(this.classes.open);
            },
            open: function open() {
                this.$node.addClass(this.classes.open);
            },
            close: function close() {
                this.$node.removeClass(this.classes.open);
                this._removeCursor();
            },
            setLanguageDirection: function setLanguageDirection(dir) {
                this.$node.attr("dir", dir);
            },
            selectableRelativeToCursor: function selectableRelativeToCursor(delta) {
                var $selectables, $oldCursor, oldIndex, newIndex;
                $oldCursor = this.getActiveSelectable();
                $selectables = this._getSelectables();
                oldIndex = $oldCursor ? $selectables.index($oldCursor) : -1;
                newIndex = oldIndex + delta;
                newIndex = (newIndex + 1) % ($selectables.length + 1) - 1;
                newIndex = newIndex < -1 ? $selectables.length - 1 : newIndex;
                return newIndex === -1 ? null : $selectables.eq(newIndex);
            },
            setCursor: function setCursor($selectable) {
                this._removeCursor();
                if ($selectable = $selectable && $selectable.first()) {
                    $selectable.addClass(this.classes.cursor);
                    this._ensureVisible($selectable);
                }
            },
            getSelectableData: function getSelectableData($el) {
                return $el && $el.length ? Dataset.extractData($el) : null;
            },
            getActiveSelectable: function getActiveSelectable() {
                var $selectable = this._getSelectables().filter(this.selectors.cursor).first();
                return $selectable.length ? $selectable : null;
            },
            getTopSelectable: function getTopSelectable() {
                var $selectable = this._getSelectables().first();
                return $selectable.length ? $selectable : null;
            },
            update: function update(query) {
                var isValidUpdate = query !== this.query;
                if (isValidUpdate) {
                    this.query = query;
                    _.each(this.datasets, updateDataset);
                }
                return isValidUpdate;
                function updateDataset(dataset) {
                    dataset.update(query);
                }
            },
            empty: function empty() {
                _.each(this.datasets, clearDataset);
                this.query = null;
                this.$node.addClass(this.classes.empty);
                function clearDataset(dataset) {
                    dataset.clear();
                }
            },
            destroy: function destroy() {
                this.$node.off(".tt");
                this.$node = $("<div>");
                _.each(this.datasets, destroyDataset);
                function destroyDataset(dataset) {
                    dataset.destroy();
                }
            }
        });
        return Menu;
    }();
    var DefaultMenu = function() {
        "use strict";
        var s = Menu.prototype;
        function DefaultMenu() {
            Menu.apply(this, [].slice.call(arguments, 0));
        }
        _.mixin(DefaultMenu.prototype, Menu.prototype, {
            open: function open() {
                !this._allDatasetsEmpty() && this._show();
                return s.open.apply(this, [].slice.call(arguments, 0));
            },
            close: function close() {
                this._hide();
                return s.close.apply(this, [].slice.call(arguments, 0));
            },
            _onRendered: function onRendered() {
                if (this._allDatasetsEmpty()) {
                    this._hide();
                } else {
                    this.isOpen() && this._show();
                }
                return s._onRendered.apply(this, [].slice.call(arguments, 0));
            },
            _onCleared: function onCleared() {
                if (this._allDatasetsEmpty()) {
                    this._hide();
                } else {
                    this.isOpen() && this._show();
                }
                return s._onCleared.apply(this, [].slice.call(arguments, 0));
            },
            setLanguageDirection: function setLanguageDirection(dir) {
                this.$node.css(dir === "ltr" ? this.css.ltr : this.css.rtl);
                return s.setLanguageDirection.apply(this, [].slice.call(arguments, 0));
            },
            _hide: function hide() {
                this.$node.hide();
            },
            _show: function show() {
                this.$node.css("display", "block");
            }
        });
        return DefaultMenu;
    }();
    var Typeahead = function() {
        "use strict";
        function Typeahead(o, www) {
            var onFocused, onBlurred, onEnterKeyed, onTabKeyed, onEscKeyed, onUpKeyed, onDownKeyed, onLeftKeyed, onRightKeyed, onQueryChanged, onWhitespaceChanged;
            o = o || {};
            if (!o.input) {
                $.error("missing input");
            }
            if (!o.menu) {
                $.error("missing menu");
            }
            if (!o.eventBus) {
                $.error("missing event bus");
            }
            www.mixin(this);
            this.eventBus = o.eventBus;
            this.minLength = _.isNumber(o.minLength) ? o.minLength : 1;
            this.input = o.input;
            this.menu = o.menu;
            this.enabled = true;
            this.active = false;
            this.input.hasFocus() && this.activate();
            this.dir = this.input.getLangDir();
            this._hacks();
            this.menu.bind().onSync("selectableClicked", this._onSelectableClicked, this).onSync("asyncRequested", this._onAsyncRequested, this).onSync("asyncCanceled", this._onAsyncCanceled, this).onSync("asyncReceived", this._onAsyncReceived, this).onSync("datasetRendered", this._onDatasetRendered, this).onSync("datasetCleared", this._onDatasetCleared, this);
            onFocused = c(this, "activate", "open", "_onFocused");
            onBlurred = c(this, "deactivate", "_onBlurred");
            onEnterKeyed = c(this, "isActive", "isOpen", "_onEnterKeyed");
            onTabKeyed = c(this, "isActive", "isOpen", "_onTabKeyed");
            onEscKeyed = c(this, "isActive", "_onEscKeyed");
            onUpKeyed = c(this, "isActive", "open", "_onUpKeyed");
            onDownKeyed = c(this, "isActive", "open", "_onDownKeyed");
            onLeftKeyed = c(this, "isActive", "isOpen", "_onLeftKeyed");
            onRightKeyed = c(this, "isActive", "isOpen", "_onRightKeyed");
            onQueryChanged = c(this, "_openIfActive", "_onQueryChanged");
            onWhitespaceChanged = c(this, "_openIfActive", "_onWhitespaceChanged");
            this.input.bind().onSync("focused", onFocused, this).onSync("blurred", onBlurred, this).onSync("enterKeyed", onEnterKeyed, this).onSync("tabKeyed", onTabKeyed, this).onSync("escKeyed", onEscKeyed, this).onSync("upKeyed", onUpKeyed, this).onSync("downKeyed", onDownKeyed, this).onSync("leftKeyed", onLeftKeyed, this).onSync("rightKeyed", onRightKeyed, this).onSync("queryChanged", onQueryChanged, this).onSync("whitespaceChanged", onWhitespaceChanged, this).onSync("langDirChanged", this._onLangDirChanged, this);
        }
        _.mixin(Typeahead.prototype, {
            _hacks: function hacks() {
                var $input, $menu;
                $input = this.input.$input || $("<div>");
                $menu = this.menu.$node || $("<div>");
                $input.on("blur.tt", function($e) {
                    var active, isActive, hasActive;
                    active = document.activeElement;
                    isActive = $menu.is(active);
                    hasActive = $menu.has(active).length > 0;
                    if (_.isMsie() && (isActive || hasActive)) {
                        $e.preventDefault();
                        $e.stopImmediatePropagation();
                        _.defer(function() {
                            $input.focus();
                        });
                    }
                });
                $menu.on("mousedown.tt", function($e) {
                    $e.preventDefault();
                });
            },
            _onSelectableClicked: function onSelectableClicked(type, $el) {
                this.select($el);
            },
            _onDatasetCleared: function onDatasetCleared() {
                this._updateHint();
            },
            _onDatasetRendered: function onDatasetRendered(type, dataset, suggestions, async) {
                this._updateHint();
                this.eventBus.trigger("render", suggestions, async, dataset);
            },
            _onAsyncRequested: function onAsyncRequested(type, dataset, query) {
                this.eventBus.trigger("asyncrequest", query, dataset);
            },
            _onAsyncCanceled: function onAsyncCanceled(type, dataset, query) {
                this.eventBus.trigger("asynccancel", query, dataset);
            },
            _onAsyncReceived: function onAsyncReceived(type, dataset, query) {
                this.eventBus.trigger("asyncreceive", query, dataset);
            },
            _onFocused: function onFocused() {
                this._minLengthMet() && this.menu.update(this.input.getQuery());
            },
            _onBlurred: function onBlurred() {
                if (this.input.hasQueryChangedSinceLastFocus()) {
                    this.eventBus.trigger("change", this.input.getQuery());
                }
            },
            _onEnterKeyed: function onEnterKeyed(type, $e) {
                var $selectable;
                if ($selectable = this.menu.getActiveSelectable()) {
                    this.select($selectable) && $e.preventDefault();
                }
            },
            _onTabKeyed: function onTabKeyed(type, $e) {
                var $selectable;
                if ($selectable = this.menu.getActiveSelectable()) {
                    this.select($selectable) && $e.preventDefault();
                } else if ($selectable = this.menu.getTopSelectable()) {
                    this.autocomplete($selectable) && $e.preventDefault();
                }
            },
            _onEscKeyed: function onEscKeyed() {
                this.close();
            },
            _onUpKeyed: function onUpKeyed() {
                this.moveCursor(-1);
            },
            _onDownKeyed: function onDownKeyed() {
                this.moveCursor(+1);
            },
            _onLeftKeyed: function onLeftKeyed() {
                if (this.dir === "rtl" && this.input.isCursorAtEnd()) {
                    this.autocomplete(this.menu.getTopSelectable());
                }
            },
            _onRightKeyed: function onRightKeyed() {
                if (this.dir === "ltr" && this.input.isCursorAtEnd()) {
                    this.autocomplete(this.menu.getTopSelectable());
                }
            },
            _onQueryChanged: function onQueryChanged(e, query) {
                this._minLengthMet(query) ? this.menu.update(query) : this.menu.empty();
            },
            _onWhitespaceChanged: function onWhitespaceChanged() {
                this._updateHint();
            },
            _onLangDirChanged: function onLangDirChanged(e, dir) {
                if (this.dir !== dir) {
                    this.dir = dir;
                    this.menu.setLanguageDirection(dir);
                }
            },
            _openIfActive: function openIfActive() {
                this.isActive() && this.open();
            },
            _minLengthMet: function minLengthMet(query) {
                query = _.isString(query) ? query : this.input.getQuery() || "";
                return query.length >= this.minLength;
            },
            _updateHint: function updateHint() {
                var $selectable, data, val, query, escapedQuery, frontMatchRegEx, match;
                $selectable = this.menu.getTopSelectable();
                data = this.menu.getSelectableData($selectable);
                val = this.input.getInputValue();
                if (data && !_.isBlankString(val) && !this.input.hasOverflow()) {
                    query = Input.normalizeQuery(val);
                    escapedQuery = _.escapeRegExChars(query);
                    frontMatchRegEx = new RegExp("^(?:" + escapedQuery + ")(.+$)", "i");
                    match = frontMatchRegEx.exec(data.val);
                    match && this.input.setHint(val + match[1]);
                } else {
                    this.input.clearHint();
                }
            },
            isEnabled: function isEnabled() {
                return this.enabled;
            },
            enable: function enable() {
                this.enabled = true;
            },
            disable: function disable() {
                this.enabled = false;
            },
            isActive: function isActive() {
                return this.active;
            },
            activate: function activate() {
                if (this.isActive()) {
                    return true;
                } else if (!this.isEnabled() || this.eventBus.before("active")) {
                    return false;
                } else {
                    this.active = true;
                    this.eventBus.trigger("active");
                    return true;
                }
            },
            deactivate: function deactivate() {
                if (!this.isActive()) {
                    return true;
                } else if (this.eventBus.before("idle")) {
                    return false;
                } else {
                    this.active = false;
                    this.close();
                    this.eventBus.trigger("idle");
                    return true;
                }
            },
            isOpen: function isOpen() {
                return this.menu.isOpen();
            },
            open: function open() {
                if (!this.isOpen() && !this.eventBus.before("open")) {
                    this.menu.open();
                    this._updateHint();
                    this.eventBus.trigger("open");
                }
                return this.isOpen();
            },
            close: function close() {
                if (this.isOpen() && !this.eventBus.before("close")) {
                    this.menu.close();
                    this.input.clearHint();
                    this.input.resetInputValue();
                    this.eventBus.trigger("close");
                }
                return !this.isOpen();
            },
            setVal: function setVal(val) {
                this.input.setQuery(_.toStr(val));
            },
            getVal: function getVal() {
                return this.input.getQuery();
            },
            select: function select($selectable) {
                var data = this.menu.getSelectableData($selectable);
                if (data && !this.eventBus.before("select", data.obj)) {
                    this.input.setQuery(data.val, true);
                    this.eventBus.trigger("select", data.obj);
                    this.close();
                    return true;
                }
                return false;
            },
            autocomplete: function autocomplete($selectable) {
                var query, data, isValid;
                query = this.input.getQuery();
                data = this.menu.getSelectableData($selectable);
                isValid = data && query !== data.val;
                if (isValid && !this.eventBus.before("autocomplete", data.obj)) {
                    this.input.setQuery(data.val);
                    this.eventBus.trigger("autocomplete", data.obj);
                    return true;
                }
                return false;
            },
            moveCursor: function moveCursor(delta) {
                var query, $candidate, data, payload, cancelMove;
                query = this.input.getQuery();
                $candidate = this.menu.selectableRelativeToCursor(delta);
                data = this.menu.getSelectableData($candidate);
                payload = data ? data.obj : null;
                cancelMove = this._minLengthMet() && this.menu.update(query);
                if (!cancelMove && !this.eventBus.before("cursorchange", payload)) {
                    this.menu.setCursor($candidate);
                    if (data) {
                        this.input.setInputValue(data.val);
                    } else {
                        this.input.resetInputValue();
                        this._updateHint();
                    }
                    this.eventBus.trigger("cursorchange", payload);
                    return true;
                }
                return false;
            },
            destroy: function destroy() {
                this.input.destroy();
                this.menu.destroy();
            }
        });
        return Typeahead;
        function c(ctx) {
            var methods = [].slice.call(arguments, 1);
            return function() {
                var args = [].slice.call(arguments);
                _.each(methods, function(method) {
                    return ctx[method].apply(ctx, args);
                });
            };
        }
    }();
    (function() {
        "use strict";
        var old, keys, methods;
        old = $.fn.typeahead;
        keys = {
            www: "tt-www",
            attrs: "tt-attrs",
            typeahead: "tt-typeahead"
        };
        methods = {
            initialize: function initialize(o, datasets) {
                var www;
                datasets = _.isArray(datasets) ? datasets : [].slice.call(arguments, 1);
                o = o || {};
                www = WWW(o.classNames);
                return this.each(attach);
                function attach() {
                    var $input, $wrapper, $hint, $menu, defaultHint, defaultMenu, eventBus, input, menu, typeahead, MenuConstructor;
                    _.each(datasets, function(d) {
                        d.highlight = !!o.highlight;
                    });
                    $input = $(this);
                    $wrapper = $(www.html.wrapper);
                    $hint = $elOrNull(o.hint);
                    $menu = $elOrNull(o.menu);
                    defaultHint = o.hint !== false && !$hint;
                    defaultMenu = o.menu !== false && !$menu;
                    defaultHint && ($hint = buildHintFromInput($input, www));
                    defaultMenu && ($menu = $(www.html.menu).css(www.css.menu));
                    $hint && $hint.val("");
                    $input = prepInput($input, www);
                    if (defaultHint || defaultMenu) {
                        $wrapper.css(www.css.wrapper);
                        $input.css(defaultHint ? www.css.input : www.css.inputWithNoHint);
                        $input.wrap($wrapper).parent().prepend(defaultHint ? $hint : null).append(defaultMenu ? $menu : null);
                    }
                    MenuConstructor = defaultMenu ? DefaultMenu : Menu;
                    eventBus = new EventBus({
                        el: $input
                    });
                    input = new Input({
                        hint: $hint,
                        input: $input
                    }, www);
                    menu = new MenuConstructor({
                        node: $menu,
                        datasets: datasets
                    }, www);
                    typeahead = new Typeahead({
                        input: input,
                        menu: menu,
                        eventBus: eventBus,
                        minLength: o.minLength
                    }, www);
                    $input.data(keys.www, www);
                    $input.data(keys.typeahead, typeahead);
                }
            },
            isEnabled: function isEnabled() {
                var enabled;
                ttEach(this.first(), function(t) {
                    enabled = t.isEnabled();
                });
                return enabled;
            },
            enable: function enable() {
                ttEach(this, function(t) {
                    t.enable();
                });
                return this;
            },
            disable: function disable() {
                ttEach(this, function(t) {
                    t.disable();
                });
                return this;
            },
            isActive: function isActive() {
                var active;
                ttEach(this.first(), function(t) {
                    active = t.isActive();
                });
                return active;
            },
            activate: function activate() {
                ttEach(this, function(t) {
                    t.activate();
                });
                return this;
            },
            deactivate: function deactivate() {
                ttEach(this, function(t) {
                    t.deactivate();
                });
                return this;
            },
            isOpen: function isOpen() {
                var open;
                ttEach(this.first(), function(t) {
                    open = t.isOpen();
                });
                return open;
            },
            open: function open() {
                ttEach(this, function(t) {
                    t.open();
                });
                return this;
            },
            close: function close() {
                ttEach(this, function(t) {
                    t.close();
                });
                return this;
            },
            select: function select(el) {
                var success = false, $el = $(el);
                ttEach(this.first(), function(t) {
                    success = t.select($el);
                });
                return success;
            },
            autocomplete: function autocomplete(el) {
                var success = false, $el = $(el);
                ttEach(this.first(), function(t) {
                    success = t.autocomplete($el);
                });
                return success;
            },
            moveCursor: function moveCursoe(delta) {
                var success = false;
                ttEach(this.first(), function(t) {
                    success = t.moveCursor(delta);
                });
                return success;
            },
            val: function val(newVal) {
                var query;
                if (!arguments.length) {
                    ttEach(this.first(), function(t) {
                        query = t.getVal();
                    });
                    return query;
                } else {
                    ttEach(this, function(t) {
                        t.setVal(newVal);
                    });
                    return this;
                }
            },
            destroy: function destroy() {
                ttEach(this, function(typeahead, $input) {
                    revert($input);
                    typeahead.destroy();
                });
                return this;
            }
        };
        $.fn.typeahead = function(method) {
            if (methods[method]) {
                return methods[method].apply(this, [].slice.call(arguments, 1));
            } else {
                return methods.initialize.apply(this, arguments);
            }
        };
        $.fn.typeahead.noConflict = function noConflict() {
            $.fn.typeahead = old;
            return this;
        };
        function ttEach($els, fn) {
            $els.each(function() {
                var $input = $(this), typeahead;
                (typeahead = $input.data(keys.typeahead)) && fn(typeahead, $input);
            });
        }
        function buildHintFromInput($input, www) {
            return $input.clone().addClass(www.classes.hint).removeData().css(www.css.hint).css(getBackgroundStyles($input)).prop("readonly", true).removeAttr("id name placeholder required").attr({
                autocomplete: "off",
                spellcheck: "false",
                tabindex: -1
            });
        }
        function prepInput($input, www) {
            $input.data(keys.attrs, {
                dir: $input.attr("dir"),
                autocomplete: $input.attr("autocomplete"),
                spellcheck: $input.attr("spellcheck"),
                style: $input.attr("style")
            });
            $input.addClass(www.classes.input).attr({
                autocomplete: "off",
                spellcheck: false
            });
            try {
                !$input.attr("dir") && $input.attr("dir", "auto");
            } catch (e) {}
            return $input;
        }
        function getBackgroundStyles($el) {
            return {
                backgroundAttachment: $el.css("background-attachment"),
                backgroundClip: $el.css("background-clip"),
                backgroundColor: $el.css("background-color"),
                backgroundImage: $el.css("background-image"),
                backgroundOrigin: $el.css("background-origin"),
                backgroundPosition: $el.css("background-position"),
                backgroundRepeat: $el.css("background-repeat"),
                backgroundSize: $el.css("background-size")
            };
        }
        function revert($input) {
            var www, $wrapper;
            www = $input.data(keys.www);
            $wrapper = $input.parent().filter(www.selectors.wrapper);
            _.each($input.data(keys.attrs), function(val, key) {
                _.isUndefined(val) ? $input.removeAttr(key) : $input.attr(key, val);
            });
            $input.removeData(keys.typeahead).removeData(keys.www).removeData(keys.attr).removeClass(www.classes.input);
            if ($wrapper.length) {
                $input.detach().insertAfter($wrapper);
                $wrapper.remove();
            }
        }
        function $elOrNull(obj) {
            var isValid, $el;
            isValid = _.isJQuery(obj) || _.isElement(obj);
            $el = isValid ? $(obj).first() : [];
            return $el.length ? $el : null;
        }
    })();
});;/**
 The MIT License (MIT)

 Copyright (c) 2015 Henry Chavez

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
* */

(function ($)
{
    "use strict";

    /**
     * Default Configuration
     *
     * @type {{tagClass: tagClass, itemValue: itemValue, itemText: itemText, itemTitle: itemTitle, freeInput: boolean, addOnBlur: boolean, maxTags: undefined, maxChars: undefined, confirmKeys: number[], onTagExists: onTagExists, trimValue: boolean, allowDuplicates: boolean}}
     */
    var defaultOptions = {
        tagClass        : tagClass,
        itemValue       : itemValue,
        itemText        : itemText,
        itemTitle       : itemTitle,
        freeInput       : true,
        addOnBlur       : true,
        maxTags         : undefined,
        maxChars        : undefined,
        confirmKeys     : [9,13, 44],
        onTagExists     : onTagExists,
        trimValue       : true,
        allowDuplicates : false
    };

    function tagClass(item)
    {
        return 'tvd-chip';
    }

    function itemValue(item)
    {
        return item ? item.toString() : item;
    }

    function itemText(item)
    {
        return this.itemValue(item);
    }

    function itemTitle(item)
    {
        return null;
    }

    function onTagExists(item, $tag)
    {
        $tag.hide().fadeIn();
    }

    /**
     * Constructor function
     *
     * @param element
     * @param options
     * @constructor
     */
    function TagsMaterialize(element, options)
    {
        this.itemsArray = [];

        this.$element = $(element);
        this.$element.hide();

        this.objectItems     = options && options.itemValue;
        this.placeholderText = element.hasAttribute('placeholder') ? this.$element.attr('placeholder') : '';
        this.inputSize       = Math.max(1, this.placeholderText.length);

        this.$container = $('<div class="tvd-materialize-tags"></div>');
        this.$input     = $('<input type="text" class="tvd-n-tag"  placeholder="' + this.placeholderText + '"/>').appendTo(this.$container);
        this.$label     = this.$element.parent().find('label');

        this.$element.before(this.$container);
        this.build(options);

        this.$label.on('click', function ()
        {
            $(this).addClass('tvd-active');
            $(this).parents('.tvd-input-field').find('input.tvd-n-tag').focus();
        });

        this.$input.on('focus', function ()
        {
            var label = $(this).parents('.tvd-materialize-tags').parent().find('label');
            $(this).parents('.tvd-materialize-tags').addClass('tvd-active');

            if (!label.hasClass('tvd-active'))
            {
                label.addClass('tvd-active');
            }
        }).on('focusout', function ()
        {
            var parentContainer = $(this).parents('.tvd-materialize-tags'),
                tags            = parentContainer.find('span.tvd-chip');
            parentContainer.removeClass('tvd-active');
            // Verify if is empty and remove "active" class from label
            if (tags.length == 0)
            {
                parentContainer.parent().find('label').removeClass('tvd-active');
            }
        });
    }

    TagsMaterialize.prototype = {
        constructor : TagsMaterialize,

        /**
         * Adds the given item as a new tag. Pass true to dontPushVal to prevent
         * updating the elements val()
         *
         * @param item
         * @param dontPushVal
         * @param options
         */
        add : function (item, dontPushVal, options)
        {
            var self = this;

            if (self.options.maxTags && self.itemsArray.length >= self.options.maxTags)
            {
                return;
            }

            // Ignore false values, except false
            if (item !== false && !item)
            {
                return;
            }

            // Trim value
            if (typeof item === "string" && self.options.trimValue)
            {
                item = $.trim(item);
            }

            // Throw an error when trying to add an object while the itemValue option was not set
            if (typeof item === "object" && !self.objectItems)
            {
                throw("Can't add objects when itemValue option is not set");
            }

            // Ignore strings only contain whitespace
            if (item.toString().match(/^\s*$/))
            {
                return;
            }

            if (typeof item === "string" && this.$element[0].tagName === 'INPUT')
            {
                var items = item.split(',');
                if (items.length > 1)
                {
                    for (var i = 0; i < items.length; i++)
                    {
                        this.add(items[i], true);
                    }

                    if (!dontPushVal)
                    {
                        self.pushVal();
                    }
                    return;
                }
            }

            var itemValue = self.options.itemValue(item),
                itemText  = self.options.itemText(item),
                tagClass  = self.options.tagClass(item),
                itemTitle = self.options.itemTitle(item);

            // Ignore items all ready added
            var existing = $.grep(self.itemsArray, function (item) { return self.options.itemValue(item) === itemValue; })[0];
            if (existing && !self.options.allowDuplicates)
            {
                // Invoke onTagExists
                if (self.options.onTagExists)
                {
                    var $existingTag = $(".tvd-tag", self.$container).filter(function () { return $(this).data("tvd-item") === existing; });
                    self.options.onTagExists(item, $existingTag);
                }
                return;
            }

            // if length greater than limit
            if (self.items().toString().length + item.length + 1 > self.options.maxInputLength)
            {
                return;
            }

            // raise beforeItemAdd arg
            var beforeItemAddEvent = $.Event('beforeItemAdd', {item : item, cancel : false, options : options});
            self.$element.trigger(beforeItemAddEvent);
            if (beforeItemAddEvent.cancel)
            {
                return;
            }

            // register item in internal array and map
            self.itemsArray.push(item);

            // add a tag element
            var $tag = $('<span class="' + htmlEncode(tagClass) + (itemTitle !== null ? ('" title="' + itemTitle) : '') + '">' + htmlEncode(itemText) + '<i class="tvd-icon-close2" data-role="remove">close</i></span>');
            $tag.data('tvd-item', item);
            self.findInputWrapper().before($tag);
            $tag.after(' ');

            if (!dontPushVal)
            {
                self.pushVal();
            }

            // Add class when reached maxTags
            if (self.options.maxTags === self.itemsArray.length || self.items().toString().length === self.options.maxInputLength)
            {
                self.$container.addClass('tvd-materialize-tags-max');
            }

            self.$element.trigger($.Event('itemAdded', {item : item, options : options}));
        },

        /**
         * Removes the given item. Pass true to dontPushVal to prevent updating the
         * elements val()
         *
         * @param item
         * @param dontPushVal
         * @param options
         */
        remove : function (item, dontPushVal, options)
        {
            var self = this;

            if (self.objectItems)
            {
                if (typeof item === "object")
                {
                    item = $.grep(self.itemsArray, function (other) { return self.options.itemValue(other) == self.options.itemValue(item); });
                }
                else
                {
                    item = $.grep(self.itemsArray, function (other) { return self.options.itemValue(other) == item; });
                }

                item = item[item.length - 1];
            }

            if (item)
            {
                var beforeItemRemoveEvent = $.Event('beforeItemRemove', {
                    item    : item,
                    cancel  : false,
                    options : options
                });
                self.$element.trigger(beforeItemRemoveEvent);
                if (beforeItemRemoveEvent.cancel)
                {
                    return;
                }

                $('.tvd-chip', self.$container).filter(function () { return $(this).data('tvd-item') === item; }).remove();

                if ($.inArray(item, self.itemsArray) !== -1)
                {
                    self.itemsArray.splice($.inArray(item, self.itemsArray), 1);
                }
            }

            if (!dontPushVal)
            {
                self.pushVal();
            }

            // Remove class when reached maxTags
            if (self.options.maxTags > self.itemsArray.length)
            {
                self.$container.removeClass('tvd-materialize-tags-max');
            }

            self.$element.trigger($.Event('itemRemoved', {item : item, options : options}));
        },

        /**
         * Removes all items
         */
        removeAll : function ()
        {
            var self = this;

            $('.tvd-chip', self.$container).remove();

            while (self.itemsArray.length > 0)
            {
                self.itemsArray.pop();
            }

            self.pushVal();
        },

        /**
         * Refreshes the tags so they match the text/value of their corresponding
         * item.
         */
        refresh : function ()
        {
            var self = this;
            $('.tvd-chip', self.$container).each(function ()
            {
                var $tag        = $(this),
                    item        = $tag.data('tvd-item'),
                    itemValue   = self.options.itemValue(item),
                    itemText    = self.options.itemText(item),
                    tagClass    = self.options.tagClass(item);

                // Update tag's class and inner text
                $tag.attr('class', null);
                $tag.addClass('tvd-tag ' + htmlEncode(tagClass));
                $tag.contents().filter(function ()
                {
                    return this.nodeType == 3;
                })[0].nodeValue = htmlEncode(itemText);

            });
        },

        /**
         * Returns the items added as tags
         */
        items : function ()
        {
            return this.itemsArray;
        },

        /**
         * Assembly value by retrieving the value of each item, and set it on the
         * element.
         */
        pushVal : function ()
        {
            var self = this,
                val  = $.map(self.items(), function (item)
                {
                    return self.options.itemValue(item).toString();
                });

            self.$element.val(val, true).trigger('change');
        },

        /**
         * Initializes the tags input behaviour on the element
         *
         * @param options
         */
        build : function (options)
        {
            var self = this;

            self.options = $.extend({}, defaultOptions, options);
            // When itemValue is set, freeInput should always be false
            if (self.objectItems)
            {
                self.options.freeInput = false;
            }

            makeOptionItemFunction(self.options, 'itemValue');
            makeOptionItemFunction(self.options, 'itemText');
            makeOptionFunction(self.options, 'tagClass');

            // Typeahead.js
            if (self.options.typeaheadjs)
            {

                var typeaheadConfig   = null;
                var typeaheadDatasets = {};

                // Determine if main configurations were passed or simply a dataset
                var typeaheadjs = self.options.typeaheadjs;
                if ($.isArray(typeaheadjs))
                {
                    typeaheadConfig   = typeaheadjs[0];
                    typeaheadDatasets = typeaheadjs[1];
                }
                else
                {
                    typeaheadDatasets = typeaheadjs;
                }

                self.$input.typeahead(typeaheadConfig, typeaheadDatasets).on('typeahead:selected', $.proxy(function (obj, datum)
                {
                    if (typeaheadDatasets.valueKey)
                    {
                        self.add(datum[typeaheadDatasets.valueKey]);
                    }
                    else
                    {
                        self.add(datum);
                    }
                    self.$input.typeahead('val', '');
                }, self));
            }

            self.$container.on('click', $.proxy(function (event)
            {
                if (!self.$element.attr('disabled'))
                {
                    self.$input.removeAttr('disabled');
                }
                self.$input.focus();
            }, self));

            if (self.options.addOnBlur && self.options.freeInput)
            {
                self.$input.on('focusout', $.proxy(function (event)
                {
                    // HACK: only process on focusout when no typeahead opened, to
                    //       avoid adding the typeahead text as tag
                    if ($('.typeahead, .twitter-typeahead', self.$container).length === 0)
                    {
                        self.add(self.$input.val());
                        self.$input.val('');
                    }
                }, self));
            }

            self.$container.on('keydown', 'input', $.proxy(function (event)
            {
                var $input        = $(event.target),
                    $inputWrapper = self.findInputWrapper();

                if (self.$element.attr('disabled'))
                {
                    self.$input.attr('disabled', 'disabled');
                    return;
                }

                switch (event.which)
                {
                    // BACKSPACE
                    case 8:
                        if (doGetCaretPosition($input[0]) === 0)
                        {
                            var prev = $inputWrapper.prev();
                            if (prev)
                            {
                                self.remove(prev.data('tvd-item'));
                            }
                        }
                        break;

                    // DELETE
                    case 46:
                        if (doGetCaretPosition($input[0]) === 0)
                        {
                            var next = $inputWrapper.next();
                            if (next)
                            {
                                self.remove(next.data('tvd-item'));
                            }
                        }
                        break;

                    // LEFT ARROW
                    case 37:
                        // Try to move the input before the previous tag
                        var $prevTag = $inputWrapper.prev();
                        if ($input.val().length === 0 && $prevTag[0])
                        {
                            $prevTag.before($inputWrapper);
                            $input.focus();
                        }
                        break;
                    // RIGHT ARROW
                    case 39:
                        // Try to move the input after the next tag
                        var $nextTag = $inputWrapper.next();
                        if ($input.val().length === 0 && $nextTag[0])
                        {
                            $nextTag.after($inputWrapper);
                            $input.focus();
                        }
                        break;
                    default:
                    // ignore
                }

                // Reset internal input's size
                var textLength = $input.val().length,
                    wordSpace  = Math.ceil(textLength / 5),
                    size       = textLength + wordSpace + 1;
                $input.attr('size', Math.max(this.inputSize, $input.val().length));
            }, self));

            self.$container.on('keydown', 'input', $.proxy(function (event)
            {
                var $input = $(event.target);

                if (self.$element.attr('disabled'))
                {
                    self.$input.attr('disabled', 'disabled');
                    return;
                }

                var text             = $input.val(),
                    maxLengthReached = self.options.maxChars && text.length >= self.options.maxChars;
                if (self.options.freeInput && (keyCombinationInList(event, self.options.confirmKeys) || maxLengthReached))
                {
                    self.add(maxLengthReached ? text.substr(0, self.options.maxChars) : text);
                    $input.val('');
                    event.preventDefault();
                }

                // Reset internal input's size
                var textLength = $input.val().length,
                    wordSpace  = Math.ceil(textLength / 5),
                    size       = textLength + wordSpace + 1;
                $input.attr('size', Math.max(this.inputSize, $input.val().length));
            }, self));

            // Remove icon clicked
            self.$container.on('click', '[data-role=remove]', $.proxy(function (event)
            {
                if (self.$element.attr('disabled'))
                {
                    return;
                }
                self.remove($(event.target).closest('.tvd-chip').data('tvd-item'));
            }, self));

            // Only add existing value as tags when using strings as tags
            if (self.options.itemValue === defaultOptions.itemValue)
            {
                if (self.$element[0].tagName === 'INPUT')
                {
                    self.add(self.$element.val());
                }
            }
        },

        /**
         * Removes all materialtags behaviour and unregsiter all event handlers
         */
        destroy : function ()
        {
            var self = this;

            // Unbind events
            self.$container.off('keydown', 'input');
            self.$container.off('click', '[role=remove]');

            self.$container.remove();
            self.$element.removeData('tvd-materialtags');
            self.$element.show();
        },

        /**
         * Sets focus on the materialtags
         */
        focus : function ()
        {
            this.$input.focus();
        },

        /**
         * Returns the internal input element
         */
        input : function ()
        {
            return this.$input;
        },

        /**
         * Returns the element which is wrapped around the internal input. This
         * is normally the $container, but typeahead.js moves the $input element.
         */
        findInputWrapper : function ()
        {
            var elt       = this.$input[0],
                container = this.$container[0];
            while (elt && elt.parentNode !== container)
            {
                elt = elt.parentNode;
            }

            return $(elt);
        }
    };

    /**
     * Register JQuery plugin
     *
     * @param arg1
     * @param arg2
     * @param arg3
     * @returns {Array}
     */
    $.fn.materialtags = function (arg1, arg2, arg3)
    {
        var results = [];
        this.each(function ()
        {
            var materialtags = $(this).data('tvd-materialtags');
            // Initialize a new material tags input
            if (!materialtags)
            {
                materialtags = new TagsMaterialize(this, arg1);
                $(this).data('tvd-materialtags', materialtags);
                results.push(materialtags);

                // Init tags from $(this).val()
                $(this).val($(this).val());
            }
            else if (!arg1 && !arg2)
            {
                // materialtags already exists
                // no function, trying to init
                results.push(materialtags);
            }
            else if (materialtags[arg1] !== undefined)
            {
                // Invoke function on existing tags input
                if (materialtags[arg1].length === 3 && arg3 !== undefined)
                {
                    var retVal = materialtags[arg1](arg2, null, arg3);
                }
                else
                {
                    var retVal = materialtags[arg1](arg2);
                }
                if (retVal !== undefined)
                {
                    results.push(retVal);
                }
            }
        });

        if (typeof arg1 == 'string')
        {
            // Return the results from the invoked function calls
            return results.length > 1 ? results : results[0];
        }
        else
        {
            return results;
        }
    };

    $.fn.materialtags.Constructor = TagsMaterialize;

    /**
     * Most options support both a string or number as well as a function as
     * option value. This function makes sure that the option with the given
     * key in the given options is wrapped in a function
     *
     * @param options
     * @param key
     */
    function makeOptionItemFunction(options, key)
    {
        if (typeof options[key] !== 'function')
        {
            var propertyName = options[key];
            options[key]     = function (item) { return item[propertyName]; };
        }
    }

    function makeOptionFunction(options, key)
    {
        if (typeof options[key] !== 'function')
        {
            var value    = options[key];
            options[key] = function () { return value; };
        }
    }

    /**
     * HtmlEncodes the given value
     */
    var htmlEncodeContainer = $('<div />');

    function htmlEncode(value)
    {
        if (value)
        {
            return htmlEncodeContainer.text(value).html();
        }
        else
        {
            return '';
        }
    }

    /**
     * Returns the position of the caret in the given input field
     * http://flightschool.acylt.com/devnotes/caret-position-woes/
     *
     * @param oField
     * @returns {number}
     */
    function doGetCaretPosition(oField)
    {
        var iCaretPos = 0;
        if (document.selection)
        {
            oField.focus();
            var oSel  = document.selection.createRange();
            oSel.moveStart('character', -oField.value.length);
            iCaretPos = oSel.text.length;
        }
        else if (oField.selectionStart || oField.selectionStart == '0')
        {
            iCaretPos = oField.selectionStart;
        }
        return (iCaretPos);
    }

    /**
     * Returns boolean indicates whether user has pressed an expected key combination.
     * http://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html
     * [13, {which: 188, shiftKey: true}]
     *
     * @param keyDownEvent
     * @param lookupList
     * @returns {boolean}
     */
    function keyCombinationInList(keyDownEvent, lookupList)
    {
        var found = false;
        $.each(lookupList, function (index, keyCombination)
        {
            if (typeof (keyCombination) === 'number' && keyDownEvent.which === keyCombination)
            {
                found = true;
                return false;
            }

            if (keyDownEvent.which === keyCombination.which)
            {
                var alt   = !keyCombination.hasOwnProperty('altKey') || keyDownEvent.altKey === keyCombination.altKey,
                    shift = !keyCombination.hasOwnProperty('shiftKey') || keyDownEvent.shiftKey === keyCombination.shiftKey,
                    ctrl  = !keyCombination.hasOwnProperty('ctrlKey') || keyDownEvent.ctrlKey === keyCombination.ctrlKey;
                if (alt && shift && ctrl)
                {
                    found = true;
                    return false;
                }
            }
        });

        return found;
    }

    /**
     * Initialize materialtags behaviour on inputs which have
     * data-role=materialtags
     */
    $(function ()
    {
        $("input[data-role=tvd-materialtags]").materialtags();

    });
})(window.jQuery);
;(function ($) {
  $(document).ready(function() {

    $(document).on('click.card', '.card', function (e) {
      if ($(this).find('> .card-reveal').length) {
        if ($(e.target).is($('.card-reveal .card-title')) || $(e.target).is($('.card-reveal .card-title i'))) {
          // Make Reveal animate down and display none
          $(this).find('.card-reveal').velocity(
            {translateY: 0}, {
              duration: 225,
              queue: false,
              easing: 'easeInOutQuad',
              complete: function() { $(this).css({ display: 'none'}); }
            }
          );
        }
        else if ($(e.target).is($('.card .activator')) ||
                 $(e.target).is($('.card .activator i')) ) {
          $(e.target).closest('.card').css('overflow', 'hidden');
          $(this).find('.card-reveal').css({ display: 'block'}).velocity("stop", false).velocity({translateY: '-100%'}, {duration: 300, queue: false, easing: 'easeInOutQuad'});
        }
      }

      $('.card-reveal').closest('.card').css('overflow', 'hidden');

    });

  });
}( jQuery ));;(function ($) {
  $(document).ready(function() {

    // jQuery reverse
    $.fn.reverse = [].reverse;

    // Hover behaviour: make sure this doesn't work on .click-to-toggle FABs!
    $(document).on('mouseenter.fixedActionBtn', '.fixed-action-btn:not(.click-to-toggle)', function(e) {
      var $this = $(this);
      openFABMenu($this);
    });
    $(document).on('mouseleave.fixedActionBtn', '.fixed-action-btn:not(.click-to-toggle)', function(e) {
      var $this = $(this);
      closeFABMenu($this);
    });

    // Toggle-on-click behaviour.
    $(document).on('click.fixedActionBtn', '.fixed-action-btn.click-to-toggle > a', function(e) {
      var $this = $(this);
      var $menu = $this.parent();
      if ($menu.hasClass('active')) {
        closeFABMenu($menu);
      } else {
        openFABMenu($menu);
      }
    });

  });

  $.fn.extend({
    openFAB: function() {
      var $this = $(this);
      openFABMenu($this);
    },
    closeFAB: function() {
      closeFABMenu($this);
    }
  });


  var openFABMenu = function (btn) {
    $this = btn;
    if ($this.hasClass('active') === false) {

      // Get direction option
      var horizontal = $this.hasClass('horizontal');
      var offsetY, offsetX;

      if (horizontal === true) {
        offsetX = 40;
      } else {
        offsetY = 40;
      }

      $this.addClass('active');
      $this.find('ul .btn-floating').velocity(
        { scaleY: ".4", scaleX: ".4", translateY: offsetY + 'px', translateX: offsetX + 'px'},
        { duration: 0 });

      var time = 0;
      $this.find('ul .btn-floating').reverse().each( function () {
        $(this).velocity(
          { opacity: "1", scaleX: "1", scaleY: "1", translateY: "0", translateX: '0'},
          { duration: 80, delay: time });
        time += 40;
      });
    }
  };

  var closeFABMenu = function (btn) {
    $this = btn;
    // Get direction option
    var horizontal = $this.hasClass('horizontal');
    var offsetY, offsetX;

    if (horizontal === true) {
      offsetX = 40;
    } else {
      offsetY = 40;
    }

    $this.removeClass('active');
    var time = 0;
    $this.find('ul .btn-floating').velocity("stop", true);
    $this.find('ul .btn-floating').velocity(
      { opacity: "0", scaleX: ".4", scaleY: ".4", translateY: offsetY + 'px', translateX: offsetX + 'px'},
      { duration: 80 }
    );
  };


}( jQuery ));
;(function ($) {
  // Image transition function
  Materialize.fadeInImage =  function(selector){
    var element = $(selector);
    element.css({opacity: 0});
    $(element).velocity({opacity: 1}, {
        duration: 650,
        queue: false,
        easing: 'easeOutSine'
      });
    $(element).velocity({opacity: 1}, {
          duration: 1300,
          queue: false,
          easing: 'swing',
          step: function(now, fx) {
              fx.start = 100;
              var grayscale_setting = now/100;
              var brightness_setting = 150 - (100 - now)/1.75;

              if (brightness_setting < 100) {
                brightness_setting = 100;
              }
              if (now >= 0) {
                $(this).css({
                    "-webkit-filter": "grayscale("+grayscale_setting+")" + "brightness("+brightness_setting+"%)",
                    "filter": "grayscale("+grayscale_setting+")" + "brightness("+brightness_setting+"%)"
                });
              }
          }
      });
  };

  // Horizontal staggered list
  Materialize.showStaggeredList = function(selector) {
    var time = 0;
    $(selector).find('li').velocity(
        { translateX: "-100px"},
        { duration: 0 });

    $(selector).find('li').each(function() {
      $(this).velocity(
        { opacity: "1", translateX: "0"},
        { duration: 800, delay: time, easing: [60, 10] });
      time += 120;
    });
  };


  $(document).ready(function() {
    // Hardcoded .staggered-list scrollFire
    // var staggeredListOptions = [];
    // $('ul.staggered-list').each(function (i) {

    //   var label = 'scrollFire-' + i;
    //   $(this).addClass(label);
    //   staggeredListOptions.push(
    //     {selector: 'ul.staggered-list.' + label,
    //      offset: 200,
    //      callback: 'showStaggeredList("ul.staggered-list.' + label + '")'});
    // });
    // scrollFire(staggeredListOptions);

    // HammerJS, Swipe navigation

    // Touch Event
    var swipeLeft = false;
    var swipeRight = false;


    // Dismissible Collections
    $('.dismissable').each(function() {
      $(this).hammer({
        prevent_default: false
      }).bind('pan', function(e) {
        if (e.gesture.pointerType === "touch") {
          var $this = $(this);
          var direction = e.gesture.direction;
          var x = e.gesture.deltaX;
          var velocityX = e.gesture.velocityX;

          $this.velocity({ translateX: x
              }, {duration: 50, queue: false, easing: 'easeOutQuad'});

          // Swipe Left
          if (direction === 4 && (x > ($this.innerWidth() / 2) || velocityX < -0.75)) {
            swipeLeft = true;
          }

          // Swipe Right
          if (direction === 2 && (x < (-1 * $this.innerWidth() / 2) || velocityX > 0.75)) {
            swipeRight = true;
          }
        }
      }).bind('panend', function(e) {
        // Reset if collection is moved back into original position
        if (Math.abs(e.gesture.deltaX) < ($(this).innerWidth() / 2)) {
          swipeRight = false;
          swipeLeft = false;
        }

        if (e.gesture.pointerType === "touch") {
          var $this = $(this);
          if (swipeLeft || swipeRight) {
            var fullWidth;
            if (swipeLeft) { fullWidth = $this.innerWidth(); }
            else { fullWidth = -1 * $this.innerWidth(); }

            $this.velocity({ translateX: fullWidth,
              }, {duration: 100, queue: false, easing: 'easeOutQuad', complete:
              function() {
                $this.css('border', 'none');
                $this.velocity({ height: 0, padding: 0,
                  }, {duration: 200, queue: false, easing: 'easeOutQuad', complete:
                    function() { $this.remove(); }
                  });
              }
            });
          }
          else {
            $this.velocity({ translateX: 0,
              }, {duration: 100, queue: false, easing: 'easeOutQuad'});
          }
          swipeLeft = false;
          swipeRight = false;
        }
      });

    });


    // time = 0
    // // Vertical Staggered list
    // $('ul.staggered-list.vertical li').velocity(
    //     { translateY: "100px"},
    //     { duration: 0 });

    // $('ul.staggered-list.vertical li').each(function() {
    //   $(this).velocity(
    //     { opacity: "1", translateY: "0"},
    //     { duration: 800, delay: time, easing: [60, 25] });
    //   time += 120;
    // });

    // // Fade in and Scale
    // $('.fade-in.scale').velocity(
    //     { scaleX: .4, scaleY: .4, translateX: -600},
    //     { duration: 0});
    // $('.fade-in').each(function() {
    //   $(this).velocity(
    //     { opacity: "1", scaleX: 1, scaleY: 1, translateX: 0},
    //     { duration: 800, easing: [60, 10] });
    // });
  });
}( jQuery ));
;(
	function ( $ ) {
		function hide_tooltip( newTooltip, backdrop, onComplete ) {
			// Animate back
			newTooltip.velocity( {
					opacity: 0, marginTop: 0, marginLeft: 0
				}, {duration: 200, queue: false, delay: 50}
			);
			backdrop.velocity( {opacity: 0, scale: 1}, {
				duration: 200,
				delay: 150, queue: false,
				complete: function () {
					backdrop.css( 'display', 'none' );
					newTooltip.css( 'display', 'none' );
					onComplete.call();
				}
			} );
		}

		function display_tooltip( origin, newTooltip, backdrop, margin ) {
			newTooltip.css( {display: 'block', left: '0px', top: '0px'} );

			// Set Tooltip text
			newTooltip.children( 'span' ).text( origin.attr( 'data-tooltip' ) );

			// Tooltip positioning
			var originWidth = origin.outerWidth();
			var originHeight = origin.outerHeight();
			var tooltipPosition = origin.attr( 'data-position' );
			var tooltipHeight = newTooltip.outerHeight();
			var tooltipWidth = newTooltip.outerWidth();
			var tooltipVerticalMovement = '0px';
			var scale_factor = 8;
			var tooltipHorizontalMovement = '0px';
			var targetTop, targetLeft, newCoordinates;

			if ( tooltipPosition === "top" ) {
				// Top Position
				targetTop = origin.offset().top - tooltipHeight - margin;
				targetLeft = origin.offset().left + originWidth / 2 - tooltipWidth / 2;
				newCoordinates = repositionWithinScreen( targetLeft, targetTop, tooltipWidth, tooltipHeight );

				tooltipVerticalMovement = '-5px';
				backdrop.css( {
					borderRadius: '14px 14px 0 0',
					transformOrigin: '50% 90%',
					marginTop: tooltipHeight,
					marginLeft: (
						            tooltipWidth / 2
					            ) - (
						            backdrop.width() / 2
					            )
				} );
			}
			// Left Position
			else if ( tooltipPosition === "left" ) {
				targetTop = origin.offset().top + originHeight / 2 - tooltipHeight / 2;
				targetLeft = origin.offset().left - tooltipWidth - margin;
				newCoordinates = repositionWithinScreen( targetLeft, targetTop, tooltipWidth, tooltipHeight );

				tooltipHorizontalMovement = '-10px';
				backdrop.css( {
					width: '14px',
					height: '14px',
					borderRadius: '14px 0 0 14px',
					transformOrigin: '95% 50%',
					marginTop: tooltipHeight / 2,
					marginLeft: tooltipWidth
				} );
			}
			// Right Position
			else if ( tooltipPosition === "right" ) {
				targetTop = origin.offset().top + originHeight / 2 - tooltipHeight / 2;
				targetLeft = origin.offset().left + originWidth + margin;
				newCoordinates = repositionWithinScreen( targetLeft, targetTop, tooltipWidth, tooltipHeight );

				tooltipHorizontalMovement = '+10px';
				backdrop.css( {
					width: '14px',
					height: '14px',
					borderRadius: '0 14px 14px 0',
					transformOrigin: '5% 50%',
					marginTop: tooltipHeight / 2,
					marginLeft: '0px'
				} );
			}
			else {
				// Bottom Position
				targetTop = origin.offset().top + origin.outerHeight() + margin;
				targetLeft = origin.offset().left + originWidth / 2 - tooltipWidth / 2;
				newCoordinates = repositionWithinScreen( targetLeft, targetTop, tooltipWidth, tooltipHeight );
				tooltipVerticalMovement = '+10px';
				backdrop.css( {
					marginLeft: (
						            tooltipWidth / 2
					            ) - (
						            backdrop.width() / 2
					            )
				} );
			}

			// Set tooptip css placement
			newTooltip.css( {
				top: newCoordinates.y,
				left: newCoordinates.x
			} );

			// Calculate Scale to fill
			scale_factor = tooltipWidth / 8;
			if ( scale_factor < 8 ) {
				scale_factor = 8;
			}
			if ( tooltipPosition === "right" || tooltipPosition === "left" ) {
				scale_factor = tooltipWidth / 10;
				if ( scale_factor < 6 ) {
					scale_factor = 6;
				}
			}

			newTooltip.velocity( {marginTop: tooltipVerticalMovement, marginLeft: tooltipHorizontalMovement}, {duration: 350, queue: false} )
			          .velocity( {opacity: 1}, {duration: 300, delay: 50, queue: false} );
			backdrop.css( {display: 'block'} )
			        .velocity( {opacity: 1}, {duration: 55, delay: 0, queue: false} )
			        .velocity( {scale: scale_factor}, {duration: 300, delay: 0, queue: false, easing: 'easeInOutQuad'} );
		}

		$.fn.live_tooltip = function ( options ) {
			var timeout = null,
				counter = null,
				started = false,
				counterInterval = null,
				margin = 5;

			// Defaults
			var defaults = {
				delay: 0
			};

			// Remove tooltip from the activator
			if ( options === 'remove' || options === 'destroy' ) {
				this.find( '.tvd-tooltipped' ).each( function () {
					var $this = $( this );
					$( '#' + $this.attr( 'data-tooltip-id' ) ).remove();
					$this.removeAttr( 'data-tooltip-id' );
				} );
				return false;
			}

			options = $.extend( defaults, options );

			return this.each( function () {
				var $container = $( this );

				function setup( $element ) {
					if ( $element.attr( 'data-tooltip-id' ) ) {
						return $element;
					}
					var tooltipId = Materialize.guid();
					$element.attr( 'data-tooltip-id', tooltipId );

					// Create Text span
					var tooltip_text = $( '<span></span>' ).text( $element.attr( 'data-tooltip' ) );

					// Create tooltip
					var newTooltip = $( '<div></div>' );
					newTooltip.addClass( 'tvd-material-tooltip' ).append( tooltip_text )
					          .appendTo( $( 'body' ) )
					          .attr( 'id', tooltipId );

					$element.data( 'tvd-new-tooltip', newTooltip );

					var backdrop = $( '<div></div>' ).addClass( 'tvd-backdrop' );
					backdrop.appendTo( newTooltip );
					backdrop.css( {top: 0, left: 0} );

					$element.data( 'tvd-backdrop', backdrop );

					return $element;
				}

				//Destroy previously binded events

				$container.off( 'mouseenter.tooltip mouseleave.tooltip' );

				$container.on( 'mouseenter.tooltip', '.tvd-tooltipped', function ( e ) {
					var origin = setup( $( this ) ),
						newTooltip = origin.data( 'tvd-new-tooltip' ),
						backdrop = origin.data( 'tvd-backdrop' ),
						tooltip_delay = origin.data( "delay" );
					tooltip_delay = (
						tooltip_delay === undefined || tooltip_delay === ''
					) ? options.delay : tooltip_delay;
					counter = 0;
					if ( tooltip_delay === 0 ) {
						started = true;
						display_tooltip( origin, newTooltip, backdrop, margin );
					} else {
						counterInterval = setInterval( function () {
							counter += 10;
							if ( counter >= tooltip_delay && started === false ) {
								started = true;
								display_tooltip( origin, newTooltip, backdrop, margin );
							}
						}, 10 ); // End Interval
					}
				} );

				$container.on( 'mouseleave.tooltip', '.tvd-tooltipped', function () {
					var $element = $( this ),
						newTooltip = $element.data( 'tvd-new-tooltip' ),
						backdrop = $element.data( 'tvd-backdrop' );

					// Reset State
					clearInterval( counterInterval );
					counter = 0;

					hide_tooltip( newTooltip, backdrop, function () {
						started = false;
					} );

				} );

			} );
		};

		$.fn.tooltip = function ( options ) {
			var timeout = null,
				counter = null,
				started = false,
				counterInterval = null,
				margin = 5;

			// Defaults
			var defaults = {
				delay: 100
			};

			// Remove tooltip from the activator
			if ( options === "remove" ) {
				this.each( function () {
					$( '#' + $( this ).attr( 'data-tooltip-id' ) ).remove();
				} );
				return false;
			}

			options = $.extend( defaults, options );

			return this.each( function () {
				var tooltipId = Materialize.guid();
				var origin = $( this );

				origin.attr( 'data-tooltip-id', tooltipId );
				// Create Text span
				var tooltip_text = $( '<span></span>' ).text( origin.attr( 'data-tooltip' ) );

				// Create tooltip
				var newTooltip = $( '<div></div>' );
				newTooltip.addClass( 'tvd-material-tooltip' ).append( tooltip_text )
				          .appendTo( $( 'body' ) )
				          .attr( 'id', tooltipId );

				var backdrop = $( '<div></div>' ).addClass( 'tvd-backdrop' );
				backdrop.appendTo( newTooltip );
				backdrop.css( {top: 0, left: 0} );


				//Destroy previously binded events
				origin.off( 'mouseenter.tvd-tooltip mouseleave.tvd-tooltip' );
				// Mouse In
				origin.on( {
					'mouseenter.tooltip': function ( e ) {
						var tooltip_delay = origin.data( "delay" );
						tooltip_delay = (
							tooltip_delay === undefined || tooltip_delay === ''
						) ? options.delay : tooltip_delay;
						counter = 0;
						counterInterval = setInterval( function () {
							counter += 10;
							if ( counter >= tooltip_delay && started === false ) {
								started = true;
								display_tooltip( origin, newTooltip, backdrop, margin );
							}
						}, 10 ); // End Interval

						// Mouse Out
					},
					'mouseleave.tooltip': function () {
						// Reset State
						clearInterval( counterInterval );
						counter = 0;

						hide_tooltip( newTooltip, backdrop, function () {
							started = false;
						} );
					}
				} );
			} );
		};

		var repositionWithinScreen = function ( x, y, width, height ) {
			var newX = x
			var newY = y;

			if ( newX < 0 ) {
				newX = 4;
			} else if ( newX + width > window.innerWidth ) {
				newX -= newX + width - window.innerWidth;
			}

			if ( newY < 0 ) {
				newY = 4;
			} else if ( newY + height > window.innerHeight + $( window ).scrollTop ) {
				newY -= newY + height - window.innerHeight;
			}

			return {x: newX, y: newY};
		};

		$( document ).ready( function () {
			$( 'body' ).live_tooltip();
		} );
	}( jQuery )
);
;(function ($) {
  $(document).ready(function() {

    $(document).on('click.tvd-chip', '.tvd-chip .tvd-icon-close2', function (e) {
      $(this).parent().remove();
    });

  });
}( jQuery ));;/*!
 * Select2 4.0.2
 * https://select2.github.io
 *
 * Released under the MIT license
 * https://github.com/select2/select2/blob/master/LICENSE.md
 */
(function ( factory ) {
	if ( typeof define === 'function' && define.amd ) {
		// AMD. Register as an anonymous module.
		define( ['jquery'], factory );
	} else if ( typeof exports === 'object' ) {
		// Node/CommonJS
		factory( require( 'jquery' ) );
	} else {
		// Browser globals
		factory( jQuery );
	}
}( function ( jQuery ) {
	// This is needed so we can catch the AMD loader configuration and use it
	// The inner file should be wrapped (by `banner.start.js`) in a function that
	// returns the AMD loader references.
	var S2 = (function () {
		// Restore the Select2 AMD loader so it can be used
		// Needed mostly in the language files, where the loader is not inserted
		if ( jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd ) {
			var S2 = jQuery.fn.select2.amd;
		}
		var S2;
		(function () {
			if ( ! S2 || ! S2.requirejs ) {
				if ( ! S2 ) {
					S2 = {};
				} else {
					require = S2;
				}
				/**
				 * @license almond 0.3.1 Copyright (c) 2011-2014, The Dojo Foundation All Rights Reserved.
				 * Available via the MIT or new BSD license.
				 * see: http://github.com/jrburke/almond for details
				 */
//Going sloppy to avoid 'use strict' string cost, but strict practices should
//be followed.
				/*jslint sloppy: true */
				/*global setTimeout: false */

				var requirejs, require, define;
				(function ( undef ) {
					var main, req, makeMap, handlers, defined = {}, waiting = {}, config = {}, defining = {}, hasOwn = Object.prototype.hasOwnProperty, aps = [].slice, jsSuffixRegExp = /\.js$/;

					function hasProp( obj, prop ) {
						return hasOwn.call( obj, prop );
					}

					/**
					 * Given a relative module name, like ./something, normalize it to
					 * a real name that can be mapped to a path.
					 * @param {String} name the relative name
					 * @param {String} baseName a real name that the name arg is relative
					 * to.
					 * @returns {String} normalized name
					 */
					function normalize( name, baseName ) {
						var nameParts, nameSegment, mapValue, foundMap, lastIndex, foundI, foundStarMap, starI, i, j, part, baseParts = baseName && baseName.split( "/" ), map = config.map, starMap = (map && map['*']) || {};

						//Adjust any relative paths.
						if ( name && name.charAt( 0 ) === "." ) {
							//If have a base name, try to normalize against it,
							//otherwise, assume it is a top-level require that will
							//be relative to baseUrl in the end.
							if ( baseName ) {
								name = name.split( '/' );
								lastIndex = name.length - 1;

								// Node .js allowance:
								if ( config.nodeIdCompat && jsSuffixRegExp.test( name[lastIndex] ) ) {
									name[lastIndex] = name[lastIndex].replace( jsSuffixRegExp, '' );
								}

								//Lop off the last part of baseParts, so that . matches the
								//"directory" and not name of the baseName's module. For instance,
								//baseName of "one/two/three", maps to "one/two/three.js", but we
								//want the directory, "one/two" for this normalization.
								name = baseParts.slice( 0, baseParts.length - 1 ).concat( name );

								//start trimDots
								for ( i = 0; i < name.length; i += 1 ) {
									part = name[i];
									if ( part === "." ) {
										name.splice( i, 1 );
										i -= 1;
									} else if ( part === ".." ) {
										if ( i === 1 && (name[2] === '..' || name[0] === '..') ) {
											//End of the line. Keep at least one non-dot
											//path segment at the front so it can be mapped
											//correctly to disk. Otherwise, there is likely
											//no path mapping for a path starting with '..'.
											//This can still fail, but catches the most reasonable
											//uses of ..
											break;
										} else if ( i > 0 ) {
											name.splice( i - 1, 2 );
											i -= 2;
										}
									}
								}
								//end trimDots

								name = name.join( "/" );
							} else if ( name.indexOf( './' ) === 0 ) {
								// No baseName, so this is ID is resolved relative
								// to baseUrl, pull off the leading dot.
								name = name.substring( 2 );
							}
						}

						//Apply map config if available.
						if ( (baseParts || starMap) && map ) {
							nameParts = name.split( '/' );

							for ( i = nameParts.length; i > 0; i -= 1 ) {
								nameSegment = nameParts.slice( 0, i ).join( "/" );

								if ( baseParts ) {
									//Find the longest baseName segment match in the config.
									//So, do joins on the biggest to smallest lengths of baseParts.
									for ( j = baseParts.length; j > 0; j -= 1 ) {
										mapValue = map[baseParts.slice( 0, j ).join( '/' )];

										//baseName segment has  config, find if it has one for
										//this name.
										if ( mapValue ) {
											mapValue = mapValue[nameSegment];
											if ( mapValue ) {
												//Match, update name to the new value.
												foundMap = mapValue;
												foundI = i;
												break;
											}
										}
									}
								}

								if ( foundMap ) {
									break;
								}

								//Check for a star map match, but just hold on to it,
								//if there is a shorter segment match later in a matching
								//config, then favor over this star map.
								if ( ! foundStarMap && starMap && starMap[nameSegment] ) {
									foundStarMap = starMap[nameSegment];
									starI = i;
								}
							}

							if ( ! foundMap && foundStarMap ) {
								foundMap = foundStarMap;
								foundI = starI;
							}

							if ( foundMap ) {
								nameParts.splice( 0, foundI, foundMap );
								name = nameParts.join( '/' );
							}
						}

						return name;
					}

					function makeRequire( relName, forceSync ) {
						return function () {
							//A version of a require function that passes a moduleName
							//value for items that may need to
							//look up paths relative to the moduleName
							var args = aps.call( arguments, 0 );

							//If first arg is not require('string'), and there is only
							//one arg, it is the array form without a callback. Insert
							//a null so that the following concat is correct.
							if ( typeof args[0] !== 'string' && args.length === 1 ) {
								args.push( null );
							}
							return req.apply( undef, args.concat( [relName, forceSync] ) );
						};
					}

					function makeNormalize( relName ) {
						return function ( name ) {
							return normalize( name, relName );
						};
					}

					function makeLoad( depName ) {
						return function ( value ) {
							defined[depName] = value;
						};
					}

					function callDep( name ) {
						if ( hasProp( waiting, name ) ) {
							var args = waiting[name];
							delete waiting[name];
							defining[name] = true;
							main.apply( undef, args );
						}

						if ( ! hasProp( defined, name ) && ! hasProp( defining, name ) ) {
							throw new Error( 'No ' + name );
						}
						return defined[name];
					}

					//Turns a plugin!resource to [plugin, resource]
					//with the plugin being undefined if the name
					//did not have a plugin prefix.
					function splitPrefix( name ) {
						var prefix, index = name ? name.indexOf( '!' ) : - 1;
						if ( index > - 1 ) {
							prefix = name.substring( 0, index );
							name = name.substring( index + 1, name.length );
						}
						return [prefix, name];
					}

					/**
					 * Makes a name map, normalizing the name, and using a plugin
					 * for normalization if necessary. Grabs a ref to plugin
					 * too, as an optimization.
					 */
					makeMap = function ( name, relName ) {
						var plugin, parts = splitPrefix( name ), prefix = parts[0];

						name = parts[1];

						if ( prefix ) {
							prefix = normalize( prefix, relName );
							plugin = callDep( prefix );
						}

						//Normalize according
						if ( prefix ) {
							if ( plugin && plugin.normalize ) {
								name = plugin.normalize( name, makeNormalize( relName ) );
							} else {
								name = normalize( name, relName );
							}
						} else {
							name = normalize( name, relName );
							parts = splitPrefix( name );
							prefix = parts[0];
							name = parts[1];
							if ( prefix ) {
								plugin = callDep( prefix );
							}
						}

						//Using ridiculous property names for space reasons
						return {
							f: prefix ? prefix + '!' + name : name, //fullName
							n: name, pr: prefix, p: plugin
						};
					};

					function makeConfig( name ) {
						return function () {
							return (config && config.config && config.config[name]) || {};
						};
					}

					handlers = {
						require: function ( name ) {
							return makeRequire( name );
						}, exports: function ( name ) {
							var e = defined[name];
							if ( typeof e !== 'undefined' ) {
								return e;
							} else {
								return (defined[name] = {});
							}
						}, module: function ( name ) {
							return {
								id: name,
								uri: '',
								exports: defined[name],
								config: makeConfig( name )
							};
						}
					};

					main = function ( name, deps, callback, relName ) {
						var cjsModule, depName, ret, map, i, args = [], callbackType = typeof callback, usingExports;

						//Use name if no relName
						relName = relName || name;

						//Call the callback to define the module, if necessary.
						if ( callbackType === 'undefined' || callbackType === 'function' ) {
							//Pull out the defined dependencies and pass the ordered
							//values to the callback.
							//Default to [require, exports, module] if no deps
							deps = ! deps.length && callback.length ? [
								'require', 'exports', 'module'
							] : deps;
							for ( i = 0; i < deps.length; i += 1 ) {
								map = makeMap( deps[i], relName );
								depName = map.f;

								//Fast path CommonJS standard dependencies.
								if ( depName === "require" ) {
									args[i] = handlers.require( name );
								} else if ( depName === "exports" ) {
									//CommonJS module spec 1.1
									args[i] = handlers.exports( name );
									usingExports = true;
								} else if ( depName === "module" ) {
									//CommonJS module spec 1.1
									cjsModule = args[i] = handlers.module( name );
								} else if ( hasProp( defined, depName ) || hasProp( waiting, depName ) || hasProp( defining, depName ) ) {
									args[i] = callDep( depName );
								} else if ( map.p ) {
									map.p.load( map.n, makeRequire( relName, true ), makeLoad( depName ), {} );
									args[i] = defined[depName];
								} else {
									throw new Error( name + ' missing ' + depName );
								}
							}

							ret = callback ? callback.apply( defined[name], args ) : undefined;

							if ( name ) {
								//If setting exports via "module" is in play,
								//favor that over return value and exports. After that,
								//favor a non-undefined return value over exports use.
								if ( cjsModule && cjsModule.exports !== undef && cjsModule.exports !== defined[name] ) {
									defined[name] = cjsModule.exports;
								} else if ( ret !== undef || ! usingExports ) {
									//Use the return value from the function.
									defined[name] = ret;
								}
							}
						} else if ( name ) {
							//May just be an object definition for the module. Only
							//worry about defining if have a module name.
							defined[name] = callback;
						}
					};

					requirejs = require = req = function ( deps, callback, relName, forceSync, alt ) {
						if ( typeof deps === "string" ) {
							if ( handlers[deps] ) {
								//callback in this case is really relName
								return handlers[deps]( callback );
							}
							//Just return the module wanted. In this scenario, the
							//deps arg is the module name, and second arg (if passed)
							//is just the relName.
							//Normalize module name, if it contains . or ..
							return callDep( makeMap( deps, callback ).f );
						} else if ( ! deps.splice ) {
							//deps is a config object, not an array.
							config = deps;
							if ( config.deps ) {
								req( config.deps, config.callback );
							}
							if ( ! callback ) {
								return;
							}

							if ( callback.splice ) {
								//callback is an array, which means it is a dependency list.
								//Adjust args if there are dependencies
								deps = callback;
								callback = relName;
								relName = null;
							} else {
								deps = undef;
							}
						}

						//Support require(['a'])
						callback = callback || function () {
							};

						//If relName is a function, it is an errback handler,
						//so remove it.
						if ( typeof relName === 'function' ) {
							relName = forceSync;
							forceSync = alt;
						}

						//Simulate async callback;
						if ( forceSync ) {
							main( undef, deps, callback, relName );
						} else {
							//Using a non-zero value because of concern for what old browsers
							//do, and latest browsers "upgrade" to 4 if lower value is used:
							//http://www.whatwg.org/specs/web-apps/current-work/multipage/timers.html#dom-windowtimers-settimeout:
							//If want a value immediately, use require('id') instead -- something
							//that works in almond on the global level, but not guaranteed and
							//unlikely to work in other AMD implementations.
							setTimeout( function () {
								main( undef, deps, callback, relName );
							}, 4 );
						}

						return req;
					};

					/**
					 * Just drops the config on the floor, but returns req in case
					 * the config return value is used.
					 */
					req.config = function ( cfg ) {
						return req( cfg );
					};

					/**
					 * Expose module registry for debugging and tooling
					 */
					requirejs._defined = defined;

					define = function ( name, deps, callback ) {
						if ( typeof name !== 'string' ) {
							throw new Error( 'See almond README: incorrect module build, no module name' );
						}

						//This module may not have dependencies
						if ( ! deps.splice ) {
							//deps is not an array, so probably means
							//an object literal or factory function for
							//the value. Adjust args.
							callback = deps;
							deps = [];
						}

						if ( ! hasProp( defined, name ) && ! hasProp( waiting, name ) ) {
							waiting[name] = [name, deps, callback];
						}
					};

					define.amd = {
						jQuery: true
					};
				}());

				S2.requirejs = requirejs;
				S2.require = require;
				S2.define = define;
			}
		}());
		S2.define( "almond", function () {
		} );

		/* global jQuery:false, $:false */
		S2.define( 'jquery', [], function () {
			var _$ = jQuery || $;

			if ( _$ == null && console && console.error ) {
				console.error( 'Select2: An instance of jQuery or a jQuery-compatible library was not ' + 'found. Make sure that you are including jQuery before Select2 on your ' + 'web page.' );
			}

			return _$;
		} );

		S2.define( 'select2/utils', [
			'jquery'
		], function ( $ ) {
			var Utils = {};

			Utils.Extend = function ( ChildClass, SuperClass ) {
				var __hasProp = {}.hasOwnProperty;

				function BaseConstructor() {
					this.constructor = ChildClass;
				}

				for ( var key in SuperClass ) {
					if ( __hasProp.call( SuperClass, key ) ) {
						ChildClass[key] = SuperClass[key];
					}
				}

				BaseConstructor.prototype = SuperClass.prototype;
				ChildClass.prototype = new BaseConstructor();
				ChildClass.__super__ = SuperClass.prototype;

				return ChildClass;
			};

			function getMethods( theClass ) {
				var proto = theClass.prototype;

				var methods = [];

				for ( var methodName in proto ) {
					var m = proto[methodName];

					if ( typeof m !== 'function' ) {
						continue;
					}

					if ( methodName === 'constructor' ) {
						continue;
					}

					methods.push( methodName );
				}

				return methods;
			}

			Utils.Decorate = function ( SuperClass, DecoratorClass ) {
				var decoratedMethods = getMethods( DecoratorClass );
				var superMethods = getMethods( SuperClass );

				function DecoratedClass() {
					var unshift = Array.prototype.unshift;

					var argCount = DecoratorClass.prototype.constructor.length;

					var calledConstructor = SuperClass.prototype.constructor;

					if ( argCount > 0 ) {
						unshift.call( arguments, SuperClass.prototype.constructor );

						calledConstructor = DecoratorClass.prototype.constructor;
					}

					calledConstructor.apply( this, arguments );
				}

				DecoratorClass.displayName = SuperClass.displayName;

				function ctr() {
					this.constructor = DecoratedClass;
				}

				DecoratedClass.prototype = new ctr();

				for ( var m = 0; m < superMethods.length; m ++ ) {
					var superMethod = superMethods[m];

					DecoratedClass.prototype[superMethod] = SuperClass.prototype[superMethod];
				}

				var calledMethod = function ( methodName ) {
					// Stub out the original method if it's not decorating an actual method
					var originalMethod = function () {
					};

					if ( methodName in DecoratedClass.prototype ) {
						originalMethod = DecoratedClass.prototype[methodName];
					}

					var decoratedMethod = DecoratorClass.prototype[methodName];

					return function () {
						var unshift = Array.prototype.unshift;

						unshift.call( arguments, originalMethod );

						return decoratedMethod.apply( this, arguments );
					};
				};

				for ( var d = 0; d < decoratedMethods.length; d ++ ) {
					var decoratedMethod = decoratedMethods[d];

					DecoratedClass.prototype[decoratedMethod] = calledMethod( decoratedMethod );
				}

				return DecoratedClass;
			};

			var Observable = function () {
				this.listeners = {};
			};

			Observable.prototype.on = function ( event, callback ) {
				this.listeners = this.listeners || {};

				if ( event in this.listeners ) {
					this.listeners[event].push( callback );
				} else {
					this.listeners[event] = [callback];
				}
			};

			Observable.prototype.trigger = function ( event ) {
				var slice = Array.prototype.slice;

				this.listeners = this.listeners || {};

				if ( event in this.listeners ) {
					this.invoke( this.listeners[event], slice.call( arguments, 1 ) );
				}

				if ( '*' in this.listeners ) {
					this.invoke( this.listeners['*'], arguments );
				}
			};

			Observable.prototype.invoke = function ( listeners, params ) {
				for ( var i = 0, len = listeners.length; i < len; i ++ ) {
					listeners[i].apply( this, params );
				}
			};

			Utils.Observable = Observable;

			Utils.generateChars = function ( length ) {
				var chars = '';

				for ( var i = 0; i < length; i ++ ) {
					var randomChar = Math.floor( Math.random() * 36 );
					chars += randomChar.toString( 36 );
				}

				return chars;
			};

			Utils.bind = function ( func, context ) {
				return function () {
					func.apply( context, arguments );
				};
			};

			Utils._convertData = function ( data ) {
				for ( var originalKey in data ) {
					var keys = originalKey.split( '-' );

					var dataLevel = data;

					if ( keys.length === 1 ) {
						continue;
					}

					for ( var k = 0; k < keys.length; k ++ ) {
						var key = keys[k];

						// Lowercase the first letter
						// By default, dash-separated becomes camelCase
						key = key.substring( 0, 1 ).toLowerCase() + key.substring( 1 );

						if ( ! (key in dataLevel) ) {
							dataLevel[key] = {};
						}

						if ( k == keys.length - 1 ) {
							dataLevel[key] = data[originalKey];
						}

						dataLevel = dataLevel[key];
					}

					delete data[originalKey];
				}

				return data;
			};

			Utils.hasScroll = function ( index, el ) {
				// Adapted from the function created by @ShadowScripter
				// and adapted by @BillBarry on the Stack Exchange Code Review website.
				// The original code can be found at
				// http://codereview.stackexchange.com/q/13338
				// and was designed to be used with the Sizzle selector engine.

				var $el = $( el );
				var overflowX = el.style.overflowX;
				var overflowY = el.style.overflowY;

				//Check both x and y declarations
				if ( overflowX === overflowY && (overflowY === 'hidden' || overflowY === 'visible') ) {
					return false;
				}

				if ( overflowX === 'scroll' || overflowY === 'scroll' ) {
					return true;
				}

				return ($el.innerHeight() < el.scrollHeight || $el.innerWidth() < el.scrollWidth);
			};

			Utils.escapeMarkup = function ( markup ) {
				var replaceMap = {
					'\\': '&#92;',
					'&': '&amp;',
					'<': '&lt;',
					'>': '&gt;',
					'"': '&quot;',
					'\'': '&#39;',
					'/': '&#47;'
				};

				// Do not try to escape the markup if it's not a string
				if ( typeof markup !== 'string' ) {
					return markup;
				}

				return String( markup ).replace( /[&<>"'\/\\]/g, function ( match ) {
					return replaceMap[match];
				} );
			};

			// Append an array of jQuery nodes to a given element.
			Utils.appendMany = function ( $element, $nodes ) {
				// jQuery 1.7.x does not support $.fn.append() with an array
				// Fall back to a jQuery object collection using $.fn.add()
				if ( $.fn.jquery.substr( 0, 3 ) === '1.7' ) {
					var $jqNodes = $();

					$.map( $nodes, function ( node ) {
						$jqNodes = $jqNodes.add( node );
					} );

					$nodes = $jqNodes;
				}

				$element.append( $nodes );
			};

			return Utils;
		} );

		S2.define( 'select2/results', [
			'jquery', './utils'
		], function ( $, Utils ) {
			function Results( $element, options, dataAdapter ) {
				this.$element = $element;
				this.data = dataAdapter;
				this.options = options;

				Results.__super__.constructor.call( this );
			}

			Utils.Extend( Results, Utils.Observable );

			Results.prototype.render = function () {
				var $results = $( '<ul class="select2-results__options" role="tree"></ul>' );

				if ( this.options.get( 'multiple' ) ) {
					$results.attr( 'aria-multiselectable', 'true' );
				}

				this.$results = $results;

				return $results;
			};

			Results.prototype.clear = function () {
				this.$results.empty();
			};

			Results.prototype.displayMessage = function ( params ) {
				var escapeMarkup = this.options.get( 'escapeMarkup' );

				this.clear();
				this.hideLoading();

				var $message = $( '<li role="treeitem" aria-live="assertive"' + ' class="select2-results__option"></li>' );

				var message = this.options.get( 'translations' ).get( params.message );

				$message.append( escapeMarkup( message( params.args ) ) );

				$message[0].className += ' select2-results__message';

				this.$results.append( $message );
			};

			Results.prototype.hideMessages = function () {
				this.$results.find( '.select2-results__message' ).remove();
			};

			Results.prototype.append = function ( data ) {
				this.hideLoading();

				var $options = [];

				if ( data.results == null || data.results.length === 0 ) {
					if ( this.$results.children().length === 0 ) {
						this.trigger( 'results:message', {
							message: 'noResults'
						} );
					}

					return;
				}

				data.results = this.sort( data.results );

				for ( var d = 0; d < data.results.length; d ++ ) {
					var item = data.results[d];

					var $option = this.option( item );

					$options.push( $option );
				}

				this.$results.append( $options );
			};

			Results.prototype.position = function ( $results, $dropdown ) {
				var $resultsContainer = $dropdown.find( '.select2-results' );
				$resultsContainer.append( $results );
			};

			Results.prototype.sort = function ( data ) {
				var sorter = this.options.get( 'sorter' );

				return sorter( data );
			};

			Results.prototype.setClasses = function () {
				var self = this;

				this.data.current( function ( selected ) {
					var selectedIds = $.map( selected, function ( s ) {
						return s.id.toString();
					} );

					var $options = self.$results
						.find( '.select2-results__option[aria-selected]' );

					$options.each( function () {
						var $option = $( this );

						var item = $.data( this, 'data' );

						// id needs to be converted to a string when comparing
						var id = '' + item.id;

						if ( (item.element != null && item.element.selected) || (item.element == null && $.inArray( id, selectedIds ) > - 1) ) {
							$option.attr( 'aria-selected', 'true' );
						} else {
							$option.attr( 'aria-selected', 'false' );
						}
					} );

					var $selected = $options.filter( '[aria-selected=true]' );

					// Check if there are any selected options
					if ( $selected.length > 0 ) {
						// If there are selected options, highlight the first
						$selected.first().trigger( 'mouseenter' );
					} else {
						// If there are no selected options, highlight the first option
						// in the dropdown
						$options.first().trigger( 'mouseenter' );
					}
				} );
			};

			Results.prototype.showLoading = function ( params ) {
				this.hideLoading();

				var loadingMore = this.options.get( 'translations' ).get( 'searching' );

				var loading = {
					disabled: true, loading: true, text: loadingMore( params )
				};
				var $loading = this.option( loading );
				$loading.className += ' loading-results';

				this.$results.prepend( $loading );
			};

			Results.prototype.hideLoading = function () {
				this.$results.find( '.loading-results' ).remove();
			};

			Results.prototype.option = function ( data ) {
				var option = document.createElement( 'li' );
				option.className = 'select2-results__option';

				var attrs = {
					'role': 'treeitem', 'aria-selected': 'false'
				};

				if ( data.disabled ) {
					delete attrs['aria-selected'];
					attrs['aria-disabled'] = 'true';
				}

				if ( data.id == null ) {
					delete attrs['aria-selected'];
				}

				if ( data._resultId != null ) {
					option.id = data._resultId;
				}

				if ( data.title ) {
					option.title = data.title;
				}

				if ( data.children ) {
					attrs.role = 'group';
					attrs['aria-label'] = data.text;
					delete attrs['aria-selected'];
				}

				for ( var attr in attrs ) {
					var val = attrs[attr];

					option.setAttribute( attr, val );
				}

				if ( data.children ) {
					var $option = $( option );

					var label = document.createElement( 'strong' );
					label.className = 'select2-results__group';

					var $label = $( label );
					this.template( data, label );

					var $children = [];

					for ( var c = 0; c < data.children.length; c ++ ) {
						var child = data.children[c];

						var $child = this.option( child );

						$children.push( $child );
					}

					var $childrenContainer = $( '<ul></ul>', {
						'class': 'select2-results__options select2-results__options--nested'
					} );

					$childrenContainer.append( $children );

					$option.append( label );
					$option.append( $childrenContainer );
				} else {
					this.template( data, option );
				}

				$.data( option, 'data', data );

				return option;
			};

			Results.prototype.bind = function ( container, $container ) {
				var self = this;

				var id = container.id + '-results';

				this.$results.attr( 'id', id );

				container.on( 'results:all', function ( params ) {
					self.clear();
					self.append( params.data );

					if ( container.isOpen() ) {
						self.setClasses();
					}
				} );

				container.on( 'results:append', function ( params ) {
					self.append( params.data );

					if ( container.isOpen() ) {
						self.setClasses();
					}
				} );

				container.on( 'query', function ( params ) {
					self.hideMessages();
					self.showLoading( params );
				} );

				container.on( 'select', function () {
					if ( ! container.isOpen() ) {
						return;
					}

					self.setClasses();
				} );

				container.on( 'unselect', function () {
					if ( ! container.isOpen() ) {
						return;
					}

					self.setClasses();
				} );

				container.on( 'open', function () {
					// When the dropdown is open, aria-expended="true"
					self.$results.attr( 'aria-expanded', 'true' );
					self.$results.attr( 'aria-hidden', 'false' );

					self.setClasses();
					self.ensureHighlightVisible();
				} );

				container.on( 'close', function () {
					// When the dropdown is closed, aria-expended="false"
					self.$results.attr( 'aria-expanded', 'false' );
					self.$results.attr( 'aria-hidden', 'true' );
					self.$results.removeAttr( 'aria-activedescendant' );
				} );

				container.on( 'results:toggle', function () {
					var $highlighted = self.getHighlightedResults();

					if ( $highlighted.length === 0 ) {
						return;
					}

					$highlighted.trigger( 'mouseup' );
				} );

				container.on( 'results:select', function () {
					var $highlighted = self.getHighlightedResults();

					if ( $highlighted.length === 0 ) {
						return;
					}

					var data = $highlighted.data( 'data' );

					if ( $highlighted.attr( 'aria-selected' ) == 'true' ) {
						self.trigger( 'close', {} );
					} else {
						self.trigger( 'select', {
							data: data
						} );
					}
				} );

				container.on( 'results:previous', function () {
					var $highlighted = self.getHighlightedResults();

					var $options = self.$results.find( '[aria-selected]' );

					var currentIndex = $options.index( $highlighted );

					// If we are already at te top, don't move further
					if ( currentIndex === 0 ) {
						return;
					}

					var nextIndex = currentIndex - 1;

					// If none are highlighted, highlight the first
					if ( $highlighted.length === 0 ) {
						nextIndex = 0;
					}

					var $next = $options.eq( nextIndex );

					$next.trigger( 'mouseenter' );

					var currentOffset = self.$results.offset().top;
					var nextTop = $next.offset().top;
					var nextOffset = self.$results.scrollTop() + (nextTop - currentOffset);

					if ( nextIndex === 0 ) {
						self.$results.scrollTop( 0 );
					} else if ( nextTop - currentOffset < 0 ) {
						self.$results.scrollTop( nextOffset );
					}
				} );

				container.on( 'results:next', function () {
					var $highlighted = self.getHighlightedResults();

					var $options = self.$results.find( '[aria-selected]' );

					var currentIndex = $options.index( $highlighted );

					var nextIndex = currentIndex + 1;

					// If we are at the last option, stay there
					if ( nextIndex >= $options.length ) {
						return;
					}

					var $next = $options.eq( nextIndex );

					$next.trigger( 'mouseenter' );

					var currentOffset = self.$results.offset().top + self.$results.outerHeight( false );
					var nextBottom = $next.offset().top + $next.outerHeight( false );
					var nextOffset = self.$results.scrollTop() + nextBottom - currentOffset;

					if ( nextIndex === 0 ) {
						self.$results.scrollTop( 0 );
					} else if ( nextBottom > currentOffset ) {
						self.$results.scrollTop( nextOffset );
					}
				} );

				container.on( 'results:focus', function ( params ) {
					params.element.addClass( 'select2-results__option--highlighted' );
				} );

				container.on( 'results:message', function ( params ) {
					self.displayMessage( params );
				} );

				if ( $.fn.mousewheel ) {
					this.$results.on( 'mousewheel', function ( e ) {
						var top = self.$results.scrollTop();

						var bottom = self.$results.get( 0 ).scrollHeight - top + e.deltaY;

						var isAtTop = e.deltaY > 0 && top - e.deltaY <= 0;
						var isAtBottom = e.deltaY < 0 && bottom <= self.$results.height();

						if ( isAtTop ) {
							self.$results.scrollTop( 0 );

							e.preventDefault();
							e.stopPropagation();
						} else if ( isAtBottom ) {
							self.$results.scrollTop( self.$results.get( 0 ).scrollHeight - self.$results.height() );

							e.preventDefault();
							e.stopPropagation();
						}
					} );
				}

				this.$results.on( 'mouseup', '.select2-results__option[aria-selected]', function ( evt ) {
					var $this = $( this );

					var data = $this.data( 'data' );

					if ( $this.attr( 'aria-selected' ) === 'true' ) {
						if ( self.options.get( 'multiple' ) ) {
							self.trigger( 'unselect', {
								originalEvent: evt, data: data
							} );
						} else {
							self.trigger( 'close', {} );
						}

						return;
					}

					self.trigger( 'select', {
						originalEvent: evt, data: data
					} );
				} );

				this.$results.on( 'mouseenter', '.select2-results__option[aria-selected]', function ( evt ) {
					var data = $( this ).data( 'data' );

					self.getHighlightedResults()
						.removeClass( 'select2-results__option--highlighted' );

					self.trigger( 'results:focus', {
						data: data, element: $( this )
					} );
				} );
			};

			Results.prototype.getHighlightedResults = function () {
				var $highlighted = this.$results
					.find( '.select2-results__option--highlighted' );

				return $highlighted;
			};

			Results.prototype.destroy = function () {
				this.$results.remove();
			};

			Results.prototype.ensureHighlightVisible = function () {
				var $highlighted = this.getHighlightedResults();

				if ( $highlighted.length === 0 ) {
					return;
				}

				var $options = this.$results.find( '[aria-selected]' );

				var currentIndex = $options.index( $highlighted );

				var currentOffset = this.$results.offset().top;
				var nextTop = $highlighted.offset().top;
				var nextOffset = this.$results.scrollTop() + (nextTop - currentOffset);

				var offsetDelta = nextTop - currentOffset;
				nextOffset -= $highlighted.outerHeight( false ) * 2;

				if ( currentIndex <= 2 ) {
					this.$results.scrollTop( 0 );
				} else if ( offsetDelta > this.$results.outerHeight() || offsetDelta < 0 ) {
					this.$results.scrollTop( nextOffset );
				}
			};

			Results.prototype.template = function ( result, container ) {
				var template = this.options.get( 'templateResult' );
				var escapeMarkup = this.options.get( 'escapeMarkup' );

				var content = template( result, container );

				if ( content == null ) {
					container.style.display = 'none';
				} else if ( typeof content === 'string' ) {
					container.innerHTML = escapeMarkup( content );
				} else {
					$( container ).append( content );
				}
			};

			return Results;
		} );

		S2.define( 'select2/keys', [], function () {
			var KEYS = {
				BACKSPACE: 8,
				TAB: 9,
				ENTER: 13,
				SHIFT: 16,
				CTRL: 17,
				ALT: 18,
				ESC: 27,
				SPACE: 32,
				PAGE_UP: 33,
				PAGE_DOWN: 34,
				END: 35,
				HOME: 36,
				LEFT: 37,
				UP: 38,
				RIGHT: 39,
				DOWN: 40,
				DELETE: 46
			};

			return KEYS;
		} );

		S2.define( 'select2/selection/base', [
			'jquery', '../utils', '../keys'
		], function ( $, Utils, KEYS ) {
			function BaseSelection( $element, options ) {
				this.$element = $element;
				this.options = options;

				BaseSelection.__super__.constructor.call( this );
			}

			Utils.Extend( BaseSelection, Utils.Observable );

			BaseSelection.prototype.render = function () {
				var $selection = $( '<span class="select2-selection" role="combobox" ' + ' aria-haspopup="true" aria-expanded="false">' + '</span>' );

				this._tabindex = 0;

				if ( this.$element.data( 'old-tabindex' ) != null ) {
					this._tabindex = this.$element.data( 'old-tabindex' );
				} else if ( this.$element.attr( 'tabindex' ) != null ) {
					this._tabindex = this.$element.attr( 'tabindex' );
				}

				$selection.attr( 'title', this.$element.attr( 'title' ) );
				$selection.attr( 'tabindex', this._tabindex );

				this.$selection = $selection;

				return $selection;
			};

			BaseSelection.prototype.bind = function ( container, $container ) {
				var self = this;

				var id = container.id + '-container';
				var resultsId = container.id + '-results';

				this.container = container;

				this.$selection.on( 'focus', function ( evt ) {
					self.trigger( 'focus', evt );
				} );

				this.$selection.on( 'blur', function ( evt ) {
					self._handleBlur( evt );
				} );

				this.$selection.on( 'keydown', function ( evt ) {
					self.trigger( 'keypress', evt );

					if ( evt.which === KEYS.SPACE ) {
						evt.preventDefault();
					}
				} );

				container.on( 'results:focus', function ( params ) {
					self.$selection.attr( 'aria-activedescendant', params.data._resultId );
				} );

				container.on( 'selection:update', function ( params ) {
					self.update( params.data );
				} );

				container.on( 'open', function () {
					// When the dropdown is open, aria-expanded="true"
					self.$selection.attr( 'aria-expanded', 'true' );
					self.$selection.attr( 'aria-owns', resultsId );

					self._attachCloseHandler( container );
				} );

				container.on( 'close', function () {
					// When the dropdown is closed, aria-expanded="false"
					self.$selection.attr( 'aria-expanded', 'false' );
					self.$selection.removeAttr( 'aria-activedescendant' );
					self.$selection.removeAttr( 'aria-owns' );

					self.$selection.focus();

					self._detachCloseHandler( container );
				} );

				container.on( 'enable', function () {
					self.$selection.attr( 'tabindex', self._tabindex );
				} );

				container.on( 'disable', function () {
					self.$selection.attr( 'tabindex', '-1' );
				} );
			};

			BaseSelection.prototype._handleBlur = function ( evt ) {
				var self = this;

				// This needs to be delayed as the active element is the body when the tab
				// key is pressed, possibly along with others.
				window.setTimeout( function () {
					// Don't trigger `blur` if the focus is still in the selection
					if ( (document.activeElement == self.$selection[0]) || ($.contains( self.$selection[0], document.activeElement )) ) {
						return;
					}

					self.trigger( 'blur', evt );
				}, 1 );
			};

			BaseSelection.prototype._attachCloseHandler = function ( container ) {
				var self = this;

				$( document.body ).on( 'mousedown.select2.' + container.id, function ( e ) {
					var $target = $( e.target );

					var $select = $target.closest( '.select2' );

					var $all = $( '.select2.select2-container--open' );

					$all.each( function () {
						var $this = $( this );

						if ( this == $select[0] ) {
							return;
						}

						var $element = $this.data( 'element' );

						$element.select2( 'close' );
					} );
				} );
			};

			BaseSelection.prototype._detachCloseHandler = function ( container ) {
				$( document.body ).off( 'mousedown.select2.' + container.id );
			};

			BaseSelection.prototype.position = function ( $selection, $container ) {
				var $selectionContainer = $container.find( '.selection' );
				$selectionContainer.append( $selection );
			};

			BaseSelection.prototype.destroy = function () {
				this._detachCloseHandler( this.container );
			};

			BaseSelection.prototype.update = function ( data ) {
				throw new Error( 'The `update` method must be defined in child classes.' );
			};

			return BaseSelection;
		} );

		S2.define( 'select2/selection/single', [
			'jquery', './base', '../utils', '../keys'
		], function ( $, BaseSelection, Utils, KEYS ) {
			function SingleSelection() {
				SingleSelection.__super__.constructor.apply( this, arguments );
			}

			Utils.Extend( SingleSelection, BaseSelection );

			SingleSelection.prototype.render = function () {
				var $selection = SingleSelection.__super__.render.call( this );

				$selection.addClass( 'select2-selection--single' );

				$selection.html( '<span class="select2-selection__rendered"></span>' + '<span class="select2-selection__arrow" role="presentation">' + '<b role="presentation"></b>' + '</span>' );

				return $selection;
			};

			SingleSelection.prototype.bind = function ( container, $container ) {
				var self = this;

				SingleSelection.__super__.bind.apply( this, arguments );

				var id = container.id + '-container';

				this.$selection.find( '.select2-selection__rendered' ).attr( 'id', id );
				this.$selection.attr( 'aria-labelledby', id );

				this.$selection.on( 'mousedown', function ( evt ) {
					// Only respond to left clicks
					if ( evt.which !== 1 ) {
						return;
					}

					self.trigger( 'toggle', {
						originalEvent: evt
					} );
				} );

				this.$selection.on( 'focus', function ( evt ) {
					// User focuses on the container
				} );

				this.$selection.on( 'blur', function ( evt ) {
					// User exits the container
				} );

				container.on( 'selection:update', function ( params ) {
					self.update( params.data );
				} );
			};

			SingleSelection.prototype.clear = function () {
				this.$selection.find( '.select2-selection__rendered' ).empty();
			};

			SingleSelection.prototype.display = function ( data, container ) {
				var template = this.options.get( 'templateSelection' );
				var escapeMarkup = this.options.get( 'escapeMarkup' );

				return escapeMarkup( template( data, container ) );
			};

			SingleSelection.prototype.selectionContainer = function () {
				return $( '<span></span>' );
			};

			SingleSelection.prototype.update = function ( data ) {
				if ( data.length === 0 ) {
					this.clear();
					return;
				}

				var selection = data[0];

				var $rendered = this.$selection.find( '.select2-selection__rendered' );
				var formatted = this.display( selection, $rendered );

				$rendered.empty().append( formatted );
				$rendered.prop( 'title', selection.title || selection.text );
			};

			return SingleSelection;
		} );

		S2.define( 'select2/selection/multiple', [
			'jquery', './base', '../utils'
		], function ( $, BaseSelection, Utils ) {
			function MultipleSelection( $element, options ) {
				MultipleSelection.__super__.constructor.apply( this, arguments );
			}

			Utils.Extend( MultipleSelection, BaseSelection );

			MultipleSelection.prototype.render = function () {
				var $selection = MultipleSelection.__super__.render.call( this );

				$selection.addClass( 'select2-selection--multiple' );

				$selection.html( '<ul class="select2-selection__rendered"></ul>' );

				return $selection;
			};

			MultipleSelection.prototype.bind = function ( container, $container ) {
				var self = this;

				MultipleSelection.__super__.bind.apply( this, arguments );

				this.$selection.on( 'click', function ( evt ) {
					self.trigger( 'toggle', {
						originalEvent: evt
					} );
				} );

				this.$selection.on( 'click', '.select2-selection__choice__remove', function ( evt ) {
					// Ignore the event if it is disabled
					if ( self.options.get( 'disabled' ) ) {
						return;
					}

					var $remove = $( this );
					var $selection = $remove.parent();

					var data = $selection.data( 'data' );

					self.trigger( 'unselect', {
						originalEvent: evt, data: data
					} );
				} );
			};

			MultipleSelection.prototype.clear = function () {
				this.$selection.find( '.select2-selection__rendered' ).empty();
			};

			MultipleSelection.prototype.display = function ( data, container ) {
				var template = this.options.get( 'templateSelection' );
				var escapeMarkup = this.options.get( 'escapeMarkup' );

				return escapeMarkup( template( data, container ) );
			};

			MultipleSelection.prototype.selectionContainer = function () {
				var $container = $( '<li class="select2-selection__choice">' + '<span class="select2-selection__choice__remove" role="presentation">' + '&times;' + '</span>' + '</li>' );

				return $container;
			};

			MultipleSelection.prototype.update = function ( data ) {
				this.clear();

				if ( data.length === 0 ) {
					return;
				}

				var $selections = [];

				for ( var d = 0; d < data.length; d ++ ) {
					var selection = data[d];

					var $selection = this.selectionContainer();
					var formatted = this.display( selection, $selection );

					$selection.append( formatted );
					$selection.prop( 'title', selection.title || selection.text );

					$selection.data( 'data', selection );

					$selections.push( $selection );
				}

				var $rendered = this.$selection.find( '.select2-selection__rendered' );

				Utils.appendMany( $rendered, $selections );
			};

			return MultipleSelection;
		} );

		S2.define( 'select2/selection/placeholder', [
			'../utils'
		], function ( Utils ) {
			function Placeholder( decorated, $element, options ) {
				this.placeholder = this.normalizePlaceholder( options.get( 'placeholder' ) );

				decorated.call( this, $element, options );
			}

			Placeholder.prototype.normalizePlaceholder = function ( _, placeholder ) {
				if ( typeof placeholder === 'string' ) {
					placeholder = {
						id: '', text: placeholder
					};
				}

				return placeholder;
			};

			Placeholder.prototype.createPlaceholder = function ( decorated, placeholder ) {
				var $placeholder = this.selectionContainer();

				$placeholder.html( this.display( placeholder ) );
				$placeholder.addClass( 'select2-selection__placeholder' )
					.removeClass( 'select2-selection__choice' );

				return $placeholder;
			};

			Placeholder.prototype.update = function ( decorated, data ) {
				var singlePlaceholder = (
					data.length == 1 && data[0].id != this.placeholder.id
				);
				var multipleSelections = data.length > 1;

				if ( multipleSelections || singlePlaceholder ) {
					return decorated.call( this, data );
				}

				this.clear();

				var $placeholder = this.createPlaceholder( this.placeholder );

				this.$selection.find( '.select2-selection__rendered' ).append( $placeholder );
			};

			return Placeholder;
		} );

		S2.define( 'select2/selection/allowClear', [
			'jquery', '../keys'
		], function ( $, KEYS ) {
			function AllowClear() {
			}

			AllowClear.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				decorated.call( this, container, $container );

				if ( this.placeholder == null ) {
					if ( this.options.get( 'debug' ) && window.console && console.error ) {
						console.error( 'Select2: The `allowClear` option should be used in combination ' + 'with the `placeholder` option.' );
					}
				}

				this.$selection.on( 'mousedown', '.select2-selection__clear', function ( evt ) {
					self._handleClear( evt );
				} );

				container.on( 'keypress', function ( evt ) {
					self._handleKeyboardClear( evt, container );
				} );
			};

			AllowClear.prototype._handleClear = function ( _, evt ) {
				// Ignore the event if it is disabled
				if ( this.options.get( 'disabled' ) ) {
					return;
				}

				var $clear = this.$selection.find( '.select2-selection__clear' );

				// Ignore the event if nothing has been selected
				if ( $clear.length === 0 ) {
					return;
				}

				evt.stopPropagation();

				var data = $clear.data( 'data' );

				for ( var d = 0; d < data.length; d ++ ) {
					var unselectData = {
						data: data[d]
					};

					// Trigger the `unselect` event, so people can prevent it from being
					// cleared.
					this.trigger( 'unselect', unselectData );

					// If the event was prevented, don't clear it out.
					if ( unselectData.prevented ) {
						return;
					}
				}

				this.$element.val( this.placeholder.id ).trigger( 'change' );

				this.trigger( 'toggle', {} );
			};

			AllowClear.prototype._handleKeyboardClear = function ( _, evt, container ) {
				if ( container.isOpen() ) {
					return;
				}

				if ( evt.which == KEYS.DELETE || evt.which == KEYS.BACKSPACE ) {
					this._handleClear( evt );
				}
			};

			AllowClear.prototype.update = function ( decorated, data ) {
				decorated.call( this, data );

				if ( this.$selection.find( '.select2-selection__placeholder' ).length > 0 || data.length === 0 ) {
					return;
				}

				var $remove = $( '<span class="select2-selection__clear">' + '&times;' + '</span>' );
				$remove.data( 'data', data );

				this.$selection.find( '.select2-selection__rendered' ).prepend( $remove );
			};

			return AllowClear;
		} );

		S2.define( 'select2/selection/search', [
			'jquery', '../utils', '../keys'
		], function ( $, Utils, KEYS ) {
			function Search( decorated, $element, options ) {
				decorated.call( this, $element, options );
			}

			Search.prototype.render = function ( decorated ) {
				var $search = $( '<li class="select2-search select2-search--inline">' + '<input class="select2-search__field" type="search" tabindex="-1"' + ' autocomplete="off" autocorrect="off" autocapitalize="off"' + ' spellcheck="false" role="textbox" aria-autocomplete="list" />' + '</li>' );

				this.$searchContainer = $search;
				this.$search = $search.find( 'input' );

				var $rendered = decorated.call( this );

				this._transferTabIndex();

				return $rendered;
			};

			Search.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				decorated.call( this, container, $container );

				container.on( 'open', function () {
					self.$search.trigger( 'focus' );
				} );

				container.on( 'close', function () {
					self.$search.val( '' );
					self.$search.removeAttr( 'aria-activedescendant' );
					self.$search.trigger( 'focus' );
				} );

				container.on( 'enable', function () {
					self.$search.prop( 'disabled', false );

					self._transferTabIndex();
				} );

				container.on( 'disable', function () {
					self.$search.prop( 'disabled', true );
				} );

				container.on( 'focus', function ( evt ) {
					self.$search.trigger( 'focus' );
				} );

				container.on( 'results:focus', function ( params ) {
					self.$search.attr( 'aria-activedescendant', params.id );
				} );

				this.$selection.on( 'focusin', '.select2-search--inline', function ( evt ) {
					self.trigger( 'focus', evt );
				} );

				this.$selection.on( 'focusout', '.select2-search--inline', function ( evt ) {
					self._handleBlur( evt );
				} );

				this.$selection.on( 'keydown', '.select2-search--inline', function ( evt ) {
					evt.stopPropagation();

					self.trigger( 'keypress', evt );

					self._keyUpPrevented = evt.isDefaultPrevented();

					var key = evt.which;

					if ( key === KEYS.BACKSPACE && self.$search.val() === '' ) {
						var $previousChoice = self.$searchContainer
							.prev( '.select2-selection__choice' );

						if ( $previousChoice.length > 0 ) {
							var item = $previousChoice.data( 'data' );

							self.searchRemoveChoice( item );

							evt.preventDefault();
						}
					}
				} );

				// Try to detect the IE version should the `documentMode` property that
				// is stored on the document. This is only implemented in IE and is
				// slightly cleaner than doing a user agent check.
				// This property is not available in Edge, but Edge also doesn't have
				// this bug.
				var msie = document.documentMode;
				var disableInputEvents = msie && msie <= 11;

				// Workaround for browsers which do not support the `input` event
				// This will prevent double-triggering of events for browsers which support
				// both the `keyup` and `input` events.
				this.$selection.on( 'input.searchcheck', '.select2-search--inline', function ( evt ) {
					// IE will trigger the `input` event when a placeholder is used on a
					// search box. To get around this issue, we are forced to ignore all
					// `input` events in IE and keep using `keyup`.
					if ( disableInputEvents ) {
						self.$selection.off( 'input.search input.searchcheck' );
						return;
					}

					// Unbind the duplicated `keyup` event
					self.$selection.off( 'keyup.search' );
				} );

				this.$selection.on( 'keyup.search input.search', '.select2-search--inline', function ( evt ) {
					// IE will trigger the `input` event when a placeholder is used on a
					// search box. To get around this issue, we are forced to ignore all
					// `input` events in IE and keep using `keyup`.
					if ( disableInputEvents && evt.type === 'input' ) {
						self.$selection.off( 'input.search input.searchcheck' );
						return;
					}

					var key = evt.which;

					// We can freely ignore events from modifier keys
					if ( key == KEYS.SHIFT || key == KEYS.CTRL || key == KEYS.ALT ) {
						return;
					}

					// Tabbing will be handled during the `keydown` phase
					if ( key == KEYS.TAB ) {
						return;
					}

					self.handleSearch( evt );
				} );
			};

			/**
			 * This method will transfer the tabindex attribute from the rendered
			 * selection to the search box. This allows for the search box to be used as
			 * the primary focus instead of the selection container.
			 *
			 * @private
			 */
			Search.prototype._transferTabIndex = function ( decorated ) {
				this.$search.attr( 'tabindex', this.$selection.attr( 'tabindex' ) );
				this.$selection.attr( 'tabindex', '-1' );
			};

			Search.prototype.createPlaceholder = function ( decorated, placeholder ) {
				this.$search.attr( 'placeholder', placeholder.text );
			};

			Search.prototype.update = function ( decorated, data ) {
				var searchHadFocus = this.$search[0] == document.activeElement;

				this.$search.attr( 'placeholder', '' );

				decorated.call( this, data );

				this.$selection.find( '.select2-selection__rendered' )
					.append( this.$searchContainer );

				this.resizeSearch();
				if ( searchHadFocus ) {
					this.$search.focus();
				}
			};

			Search.prototype.handleSearch = function () {
				this.resizeSearch();

				if ( ! this._keyUpPrevented ) {
					var input = this.$search.val();

					this.trigger( 'query', {
						term: input
					} );
				}

				this._keyUpPrevented = false;
			};

			Search.prototype.searchRemoveChoice = function ( decorated, item ) {
				this.trigger( 'unselect', {
					data: item
				} );

				this.$search.val( item.text );
				this.handleSearch();
			};

			Search.prototype.resizeSearch = function () {
				this.$search.css( 'width', '25px' );

				var width = '';

				if ( this.$search.attr( 'placeholder' ) !== '' ) {
					width = this.$selection.find( '.select2-selection__rendered' ).innerWidth();
				} else {
					var minimumWidth = this.$search.val().length + 1;

					width = (minimumWidth * 0.75) + 'em';
				}

				this.$search.css( 'width', width );
			};

			return Search;
		} );

		S2.define( 'select2/selection/eventRelay', [
			'jquery'
		], function ( $ ) {
			function EventRelay() {
			}

			EventRelay.prototype.bind = function ( decorated, container, $container ) {
				var self = this;
				var relayEvents = [
					'open',
					'opening',
					'close',
					'closing',
					'select',
					'selecting',
					'unselect',
					'unselecting'
				];

				var preventableEvents = ['opening', 'closing', 'selecting', 'unselecting'];

				decorated.call( this, container, $container );

				container.on( '*', function ( name, params ) {
					// Ignore events that should not be relayed
					if ( $.inArray( name, relayEvents ) === - 1 ) {
						return;
					}

					// The parameters should always be an object
					params = params || {};

					// Generate the jQuery event for the Select2 event
					var evt = $.Event( 'select2:' + name, {
						params: params
					} );

					self.$element.trigger( evt );

					// Only handle preventable events if it was one
					if ( $.inArray( name, preventableEvents ) === - 1 ) {
						return;
					}

					params.prevented = evt.isDefaultPrevented();
				} );
			};

			return EventRelay;
		} );

		S2.define( 'select2/translation', [
			'jquery', 'require'
		], function ( $, require ) {
			function Translation( dict ) {
				this.dict = dict || {};
			}

			Translation.prototype.all = function () {
				return this.dict;
			};

			Translation.prototype.get = function ( key ) {
				return this.dict[key];
			};

			Translation.prototype.extend = function ( translation ) {
				this.dict = $.extend( {}, translation.all(), this.dict );
			};

			// Static functions

			Translation._cache = {};

			Translation.loadPath = function ( path ) {
				if ( ! (path in Translation._cache) ) {
					var translations = require( path );

					Translation._cache[path] = translations;
				}

				return new Translation( Translation._cache[path] );
			};

			return Translation;
		} );

		S2.define( 'select2/diacritics', [], function () {
			var diacritics = {
				'\u24B6': 'A',
				'\uFF21': 'A',
				'\u00C0': 'A',
				'\u00C1': 'A',
				'\u00C2': 'A',
				'\u1EA6': 'A',
				'\u1EA4': 'A',
				'\u1EAA': 'A',
				'\u1EA8': 'A',
				'\u00C3': 'A',
				'\u0100': 'A',
				'\u0102': 'A',
				'\u1EB0': 'A',
				'\u1EAE': 'A',
				'\u1EB4': 'A',
				'\u1EB2': 'A',
				'\u0226': 'A',
				'\u01E0': 'A',
				'\u00C4': 'A',
				'\u01DE': 'A',
				'\u1EA2': 'A',
				'\u00C5': 'A',
				'\u01FA': 'A',
				'\u01CD': 'A',
				'\u0200': 'A',
				'\u0202': 'A',
				'\u1EA0': 'A',
				'\u1EAC': 'A',
				'\u1EB6': 'A',
				'\u1E00': 'A',
				'\u0104': 'A',
				'\u023A': 'A',
				'\u2C6F': 'A',
				'\uA732': 'AA',
				'\u00C6': 'AE',
				'\u01FC': 'AE',
				'\u01E2': 'AE',
				'\uA734': 'AO',
				'\uA736': 'AU',
				'\uA738': 'AV',
				'\uA73A': 'AV',
				'\uA73C': 'AY',
				'\u24B7': 'B',
				'\uFF22': 'B',
				'\u1E02': 'B',
				'\u1E04': 'B',
				'\u1E06': 'B',
				'\u0243': 'B',
				'\u0182': 'B',
				'\u0181': 'B',
				'\u24B8': 'C',
				'\uFF23': 'C',
				'\u0106': 'C',
				'\u0108': 'C',
				'\u010A': 'C',
				'\u010C': 'C',
				'\u00C7': 'C',
				'\u1E08': 'C',
				'\u0187': 'C',
				'\u023B': 'C',
				'\uA73E': 'C',
				'\u24B9': 'D',
				'\uFF24': 'D',
				'\u1E0A': 'D',
				'\u010E': 'D',
				'\u1E0C': 'D',
				'\u1E10': 'D',
				'\u1E12': 'D',
				'\u1E0E': 'D',
				'\u0110': 'D',
				'\u018B': 'D',
				'\u018A': 'D',
				'\u0189': 'D',
				'\uA779': 'D',
				'\u01F1': 'DZ',
				'\u01C4': 'DZ',
				'\u01F2': 'Dz',
				'\u01C5': 'Dz',
				'\u24BA': 'E',
				'\uFF25': 'E',
				'\u00C8': 'E',
				'\u00C9': 'E',
				'\u00CA': 'E',
				'\u1EC0': 'E',
				'\u1EBE': 'E',
				'\u1EC4': 'E',
				'\u1EC2': 'E',
				'\u1EBC': 'E',
				'\u0112': 'E',
				'\u1E14': 'E',
				'\u1E16': 'E',
				'\u0114': 'E',
				'\u0116': 'E',
				'\u00CB': 'E',
				'\u1EBA': 'E',
				'\u011A': 'E',
				'\u0204': 'E',
				'\u0206': 'E',
				'\u1EB8': 'E',
				'\u1EC6': 'E',
				'\u0228': 'E',
				'\u1E1C': 'E',
				'\u0118': 'E',
				'\u1E18': 'E',
				'\u1E1A': 'E',
				'\u0190': 'E',
				'\u018E': 'E',
				'\u24BB': 'F',
				'\uFF26': 'F',
				'\u1E1E': 'F',
				'\u0191': 'F',
				'\uA77B': 'F',
				'\u24BC': 'G',
				'\uFF27': 'G',
				'\u01F4': 'G',
				'\u011C': 'G',
				'\u1E20': 'G',
				'\u011E': 'G',
				'\u0120': 'G',
				'\u01E6': 'G',
				'\u0122': 'G',
				'\u01E4': 'G',
				'\u0193': 'G',
				'\uA7A0': 'G',
				'\uA77D': 'G',
				'\uA77E': 'G',
				'\u24BD': 'H',
				'\uFF28': 'H',
				'\u0124': 'H',
				'\u1E22': 'H',
				'\u1E26': 'H',
				'\u021E': 'H',
				'\u1E24': 'H',
				'\u1E28': 'H',
				'\u1E2A': 'H',
				'\u0126': 'H',
				'\u2C67': 'H',
				'\u2C75': 'H',
				'\uA78D': 'H',
				'\u24BE': 'I',
				'\uFF29': 'I',
				'\u00CC': 'I',
				'\u00CD': 'I',
				'\u00CE': 'I',
				'\u0128': 'I',
				'\u012A': 'I',
				'\u012C': 'I',
				'\u0130': 'I',
				'\u00CF': 'I',
				'\u1E2E': 'I',
				'\u1EC8': 'I',
				'\u01CF': 'I',
				'\u0208': 'I',
				'\u020A': 'I',
				'\u1ECA': 'I',
				'\u012E': 'I',
				'\u1E2C': 'I',
				'\u0197': 'I',
				'\u24BF': 'J',
				'\uFF2A': 'J',
				'\u0134': 'J',
				'\u0248': 'J',
				'\u24C0': 'K',
				'\uFF2B': 'K',
				'\u1E30': 'K',
				'\u01E8': 'K',
				'\u1E32': 'K',
				'\u0136': 'K',
				'\u1E34': 'K',
				'\u0198': 'K',
				'\u2C69': 'K',
				'\uA740': 'K',
				'\uA742': 'K',
				'\uA744': 'K',
				'\uA7A2': 'K',
				'\u24C1': 'L',
				'\uFF2C': 'L',
				'\u013F': 'L',
				'\u0139': 'L',
				'\u013D': 'L',
				'\u1E36': 'L',
				'\u1E38': 'L',
				'\u013B': 'L',
				'\u1E3C': 'L',
				'\u1E3A': 'L',
				'\u0141': 'L',
				'\u023D': 'L',
				'\u2C62': 'L',
				'\u2C60': 'L',
				'\uA748': 'L',
				'\uA746': 'L',
				'\uA780': 'L',
				'\u01C7': 'LJ',
				'\u01C8': 'Lj',
				'\u24C2': 'M',
				'\uFF2D': 'M',
				'\u1E3E': 'M',
				'\u1E40': 'M',
				'\u1E42': 'M',
				'\u2C6E': 'M',
				'\u019C': 'M',
				'\u24C3': 'N',
				'\uFF2E': 'N',
				'\u01F8': 'N',
				'\u0143': 'N',
				'\u00D1': 'N',
				'\u1E44': 'N',
				'\u0147': 'N',
				'\u1E46': 'N',
				'\u0145': 'N',
				'\u1E4A': 'N',
				'\u1E48': 'N',
				'\u0220': 'N',
				'\u019D': 'N',
				'\uA790': 'N',
				'\uA7A4': 'N',
				'\u01CA': 'NJ',
				'\u01CB': 'Nj',
				'\u24C4': 'O',
				'\uFF2F': 'O',
				'\u00D2': 'O',
				'\u00D3': 'O',
				'\u00D4': 'O',
				'\u1ED2': 'O',
				'\u1ED0': 'O',
				'\u1ED6': 'O',
				'\u1ED4': 'O',
				'\u00D5': 'O',
				'\u1E4C': 'O',
				'\u022C': 'O',
				'\u1E4E': 'O',
				'\u014C': 'O',
				'\u1E50': 'O',
				'\u1E52': 'O',
				'\u014E': 'O',
				'\u022E': 'O',
				'\u0230': 'O',
				'\u00D6': 'O',
				'\u022A': 'O',
				'\u1ECE': 'O',
				'\u0150': 'O',
				'\u01D1': 'O',
				'\u020C': 'O',
				'\u020E': 'O',
				'\u01A0': 'O',
				'\u1EDC': 'O',
				'\u1EDA': 'O',
				'\u1EE0': 'O',
				'\u1EDE': 'O',
				'\u1EE2': 'O',
				'\u1ECC': 'O',
				'\u1ED8': 'O',
				'\u01EA': 'O',
				'\u01EC': 'O',
				'\u00D8': 'O',
				'\u01FE': 'O',
				'\u0186': 'O',
				'\u019F': 'O',
				'\uA74A': 'O',
				'\uA74C': 'O',
				'\u01A2': 'OI',
				'\uA74E': 'OO',
				'\u0222': 'OU',
				'\u24C5': 'P',
				'\uFF30': 'P',
				'\u1E54': 'P',
				'\u1E56': 'P',
				'\u01A4': 'P',
				'\u2C63': 'P',
				'\uA750': 'P',
				'\uA752': 'P',
				'\uA754': 'P',
				'\u24C6': 'Q',
				'\uFF31': 'Q',
				'\uA756': 'Q',
				'\uA758': 'Q',
				'\u024A': 'Q',
				'\u24C7': 'R',
				'\uFF32': 'R',
				'\u0154': 'R',
				'\u1E58': 'R',
				'\u0158': 'R',
				'\u0210': 'R',
				'\u0212': 'R',
				'\u1E5A': 'R',
				'\u1E5C': 'R',
				'\u0156': 'R',
				'\u1E5E': 'R',
				'\u024C': 'R',
				'\u2C64': 'R',
				'\uA75A': 'R',
				'\uA7A6': 'R',
				'\uA782': 'R',
				'\u24C8': 'S',
				'\uFF33': 'S',
				'\u1E9E': 'S',
				'\u015A': 'S',
				'\u1E64': 'S',
				'\u015C': 'S',
				'\u1E60': 'S',
				'\u0160': 'S',
				'\u1E66': 'S',
				'\u1E62': 'S',
				'\u1E68': 'S',
				'\u0218': 'S',
				'\u015E': 'S',
				'\u2C7E': 'S',
				'\uA7A8': 'S',
				'\uA784': 'S',
				'\u24C9': 'T',
				'\uFF34': 'T',
				'\u1E6A': 'T',
				'\u0164': 'T',
				'\u1E6C': 'T',
				'\u021A': 'T',
				'\u0162': 'T',
				'\u1E70': 'T',
				'\u1E6E': 'T',
				'\u0166': 'T',
				'\u01AC': 'T',
				'\u01AE': 'T',
				'\u023E': 'T',
				'\uA786': 'T',
				'\uA728': 'TZ',
				'\u24CA': 'U',
				'\uFF35': 'U',
				'\u00D9': 'U',
				'\u00DA': 'U',
				'\u00DB': 'U',
				'\u0168': 'U',
				'\u1E78': 'U',
				'\u016A': 'U',
				'\u1E7A': 'U',
				'\u016C': 'U',
				'\u00DC': 'U',
				'\u01DB': 'U',
				'\u01D7': 'U',
				'\u01D5': 'U',
				'\u01D9': 'U',
				'\u1EE6': 'U',
				'\u016E': 'U',
				'\u0170': 'U',
				'\u01D3': 'U',
				'\u0214': 'U',
				'\u0216': 'U',
				'\u01AF': 'U',
				'\u1EEA': 'U',
				'\u1EE8': 'U',
				'\u1EEE': 'U',
				'\u1EEC': 'U',
				'\u1EF0': 'U',
				'\u1EE4': 'U',
				'\u1E72': 'U',
				'\u0172': 'U',
				'\u1E76': 'U',
				'\u1E74': 'U',
				'\u0244': 'U',
				'\u24CB': 'V',
				'\uFF36': 'V',
				'\u1E7C': 'V',
				'\u1E7E': 'V',
				'\u01B2': 'V',
				'\uA75E': 'V',
				'\u0245': 'V',
				'\uA760': 'VY',
				'\u24CC': 'W',
				'\uFF37': 'W',
				'\u1E80': 'W',
				'\u1E82': 'W',
				'\u0174': 'W',
				'\u1E86': 'W',
				'\u1E84': 'W',
				'\u1E88': 'W',
				'\u2C72': 'W',
				'\u24CD': 'X',
				'\uFF38': 'X',
				'\u1E8A': 'X',
				'\u1E8C': 'X',
				'\u24CE': 'Y',
				'\uFF39': 'Y',
				'\u1EF2': 'Y',
				'\u00DD': 'Y',
				'\u0176': 'Y',
				'\u1EF8': 'Y',
				'\u0232': 'Y',
				'\u1E8E': 'Y',
				'\u0178': 'Y',
				'\u1EF6': 'Y',
				'\u1EF4': 'Y',
				'\u01B3': 'Y',
				'\u024E': 'Y',
				'\u1EFE': 'Y',
				'\u24CF': 'Z',
				'\uFF3A': 'Z',
				'\u0179': 'Z',
				'\u1E90': 'Z',
				'\u017B': 'Z',
				'\u017D': 'Z',
				'\u1E92': 'Z',
				'\u1E94': 'Z',
				'\u01B5': 'Z',
				'\u0224': 'Z',
				'\u2C7F': 'Z',
				'\u2C6B': 'Z',
				'\uA762': 'Z',
				'\u24D0': 'a',
				'\uFF41': 'a',
				'\u1E9A': 'a',
				'\u00E0': 'a',
				'\u00E1': 'a',
				'\u00E2': 'a',
				'\u1EA7': 'a',
				'\u1EA5': 'a',
				'\u1EAB': 'a',
				'\u1EA9': 'a',
				'\u00E3': 'a',
				'\u0101': 'a',
				'\u0103': 'a',
				'\u1EB1': 'a',
				'\u1EAF': 'a',
				'\u1EB5': 'a',
				'\u1EB3': 'a',
				'\u0227': 'a',
				'\u01E1': 'a',
				'\u00E4': 'a',
				'\u01DF': 'a',
				'\u1EA3': 'a',
				'\u00E5': 'a',
				'\u01FB': 'a',
				'\u01CE': 'a',
				'\u0201': 'a',
				'\u0203': 'a',
				'\u1EA1': 'a',
				'\u1EAD': 'a',
				'\u1EB7': 'a',
				'\u1E01': 'a',
				'\u0105': 'a',
				'\u2C65': 'a',
				'\u0250': 'a',
				'\uA733': 'aa',
				'\u00E6': 'ae',
				'\u01FD': 'ae',
				'\u01E3': 'ae',
				'\uA735': 'ao',
				'\uA737': 'au',
				'\uA739': 'av',
				'\uA73B': 'av',
				'\uA73D': 'ay',
				'\u24D1': 'b',
				'\uFF42': 'b',
				'\u1E03': 'b',
				'\u1E05': 'b',
				'\u1E07': 'b',
				'\u0180': 'b',
				'\u0183': 'b',
				'\u0253': 'b',
				'\u24D2': 'c',
				'\uFF43': 'c',
				'\u0107': 'c',
				'\u0109': 'c',
				'\u010B': 'c',
				'\u010D': 'c',
				'\u00E7': 'c',
				'\u1E09': 'c',
				'\u0188': 'c',
				'\u023C': 'c',
				'\uA73F': 'c',
				'\u2184': 'c',
				'\u24D3': 'd',
				'\uFF44': 'd',
				'\u1E0B': 'd',
				'\u010F': 'd',
				'\u1E0D': 'd',
				'\u1E11': 'd',
				'\u1E13': 'd',
				'\u1E0F': 'd',
				'\u0111': 'd',
				'\u018C': 'd',
				'\u0256': 'd',
				'\u0257': 'd',
				'\uA77A': 'd',
				'\u01F3': 'dz',
				'\u01C6': 'dz',
				'\u24D4': 'e',
				'\uFF45': 'e',
				'\u00E8': 'e',
				'\u00E9': 'e',
				'\u00EA': 'e',
				'\u1EC1': 'e',
				'\u1EBF': 'e',
				'\u1EC5': 'e',
				'\u1EC3': 'e',
				'\u1EBD': 'e',
				'\u0113': 'e',
				'\u1E15': 'e',
				'\u1E17': 'e',
				'\u0115': 'e',
				'\u0117': 'e',
				'\u00EB': 'e',
				'\u1EBB': 'e',
				'\u011B': 'e',
				'\u0205': 'e',
				'\u0207': 'e',
				'\u1EB9': 'e',
				'\u1EC7': 'e',
				'\u0229': 'e',
				'\u1E1D': 'e',
				'\u0119': 'e',
				'\u1E19': 'e',
				'\u1E1B': 'e',
				'\u0247': 'e',
				'\u025B': 'e',
				'\u01DD': 'e',
				'\u24D5': 'f',
				'\uFF46': 'f',
				'\u1E1F': 'f',
				'\u0192': 'f',
				'\uA77C': 'f',
				'\u24D6': 'g',
				'\uFF47': 'g',
				'\u01F5': 'g',
				'\u011D': 'g',
				'\u1E21': 'g',
				'\u011F': 'g',
				'\u0121': 'g',
				'\u01E7': 'g',
				'\u0123': 'g',
				'\u01E5': 'g',
				'\u0260': 'g',
				'\uA7A1': 'g',
				'\u1D79': 'g',
				'\uA77F': 'g',
				'\u24D7': 'h',
				'\uFF48': 'h',
				'\u0125': 'h',
				'\u1E23': 'h',
				'\u1E27': 'h',
				'\u021F': 'h',
				'\u1E25': 'h',
				'\u1E29': 'h',
				'\u1E2B': 'h',
				'\u1E96': 'h',
				'\u0127': 'h',
				'\u2C68': 'h',
				'\u2C76': 'h',
				'\u0265': 'h',
				'\u0195': 'hv',
				'\u24D8': 'i',
				'\uFF49': 'i',
				'\u00EC': 'i',
				'\u00ED': 'i',
				'\u00EE': 'i',
				'\u0129': 'i',
				'\u012B': 'i',
				'\u012D': 'i',
				'\u00EF': 'i',
				'\u1E2F': 'i',
				'\u1EC9': 'i',
				'\u01D0': 'i',
				'\u0209': 'i',
				'\u020B': 'i',
				'\u1ECB': 'i',
				'\u012F': 'i',
				'\u1E2D': 'i',
				'\u0268': 'i',
				'\u0131': 'i',
				'\u24D9': 'j',
				'\uFF4A': 'j',
				'\u0135': 'j',
				'\u01F0': 'j',
				'\u0249': 'j',
				'\u24DA': 'k',
				'\uFF4B': 'k',
				'\u1E31': 'k',
				'\u01E9': 'k',
				'\u1E33': 'k',
				'\u0137': 'k',
				'\u1E35': 'k',
				'\u0199': 'k',
				'\u2C6A': 'k',
				'\uA741': 'k',
				'\uA743': 'k',
				'\uA745': 'k',
				'\uA7A3': 'k',
				'\u24DB': 'l',
				'\uFF4C': 'l',
				'\u0140': 'l',
				'\u013A': 'l',
				'\u013E': 'l',
				'\u1E37': 'l',
				'\u1E39': 'l',
				'\u013C': 'l',
				'\u1E3D': 'l',
				'\u1E3B': 'l',
				'\u017F': 'l',
				'\u0142': 'l',
				'\u019A': 'l',
				'\u026B': 'l',
				'\u2C61': 'l',
				'\uA749': 'l',
				'\uA781': 'l',
				'\uA747': 'l',
				'\u01C9': 'lj',
				'\u24DC': 'm',
				'\uFF4D': 'm',
				'\u1E3F': 'm',
				'\u1E41': 'm',
				'\u1E43': 'm',
				'\u0271': 'm',
				'\u026F': 'm',
				'\u24DD': 'n',
				'\uFF4E': 'n',
				'\u01F9': 'n',
				'\u0144': 'n',
				'\u00F1': 'n',
				'\u1E45': 'n',
				'\u0148': 'n',
				'\u1E47': 'n',
				'\u0146': 'n',
				'\u1E4B': 'n',
				'\u1E49': 'n',
				'\u019E': 'n',
				'\u0272': 'n',
				'\u0149': 'n',
				'\uA791': 'n',
				'\uA7A5': 'n',
				'\u01CC': 'nj',
				'\u24DE': 'o',
				'\uFF4F': 'o',
				'\u00F2': 'o',
				'\u00F3': 'o',
				'\u00F4': 'o',
				'\u1ED3': 'o',
				'\u1ED1': 'o',
				'\u1ED7': 'o',
				'\u1ED5': 'o',
				'\u00F5': 'o',
				'\u1E4D': 'o',
				'\u022D': 'o',
				'\u1E4F': 'o',
				'\u014D': 'o',
				'\u1E51': 'o',
				'\u1E53': 'o',
				'\u014F': 'o',
				'\u022F': 'o',
				'\u0231': 'o',
				'\u00F6': 'o',
				'\u022B': 'o',
				'\u1ECF': 'o',
				'\u0151': 'o',
				'\u01D2': 'o',
				'\u020D': 'o',
				'\u020F': 'o',
				'\u01A1': 'o',
				'\u1EDD': 'o',
				'\u1EDB': 'o',
				'\u1EE1': 'o',
				'\u1EDF': 'o',
				'\u1EE3': 'o',
				'\u1ECD': 'o',
				'\u1ED9': 'o',
				'\u01EB': 'o',
				'\u01ED': 'o',
				'\u00F8': 'o',
				'\u01FF': 'o',
				'\u0254': 'o',
				'\uA74B': 'o',
				'\uA74D': 'o',
				'\u0275': 'o',
				'\u01A3': 'oi',
				'\u0223': 'ou',
				'\uA74F': 'oo',
				'\u24DF': 'p',
				'\uFF50': 'p',
				'\u1E55': 'p',
				'\u1E57': 'p',
				'\u01A5': 'p',
				'\u1D7D': 'p',
				'\uA751': 'p',
				'\uA753': 'p',
				'\uA755': 'p',
				'\u24E0': 'q',
				'\uFF51': 'q',
				'\u024B': 'q',
				'\uA757': 'q',
				'\uA759': 'q',
				'\u24E1': 'r',
				'\uFF52': 'r',
				'\u0155': 'r',
				'\u1E59': 'r',
				'\u0159': 'r',
				'\u0211': 'r',
				'\u0213': 'r',
				'\u1E5B': 'r',
				'\u1E5D': 'r',
				'\u0157': 'r',
				'\u1E5F': 'r',
				'\u024D': 'r',
				'\u027D': 'r',
				'\uA75B': 'r',
				'\uA7A7': 'r',
				'\uA783': 'r',
				'\u24E2': 's',
				'\uFF53': 's',
				'\u00DF': 's',
				'\u015B': 's',
				'\u1E65': 's',
				'\u015D': 's',
				'\u1E61': 's',
				'\u0161': 's',
				'\u1E67': 's',
				'\u1E63': 's',
				'\u1E69': 's',
				'\u0219': 's',
				'\u015F': 's',
				'\u023F': 's',
				'\uA7A9': 's',
				'\uA785': 's',
				'\u1E9B': 's',
				'\u24E3': 't',
				'\uFF54': 't',
				'\u1E6B': 't',
				'\u1E97': 't',
				'\u0165': 't',
				'\u1E6D': 't',
				'\u021B': 't',
				'\u0163': 't',
				'\u1E71': 't',
				'\u1E6F': 't',
				'\u0167': 't',
				'\u01AD': 't',
				'\u0288': 't',
				'\u2C66': 't',
				'\uA787': 't',
				'\uA729': 'tz',
				'\u24E4': 'u',
				'\uFF55': 'u',
				'\u00F9': 'u',
				'\u00FA': 'u',
				'\u00FB': 'u',
				'\u0169': 'u',
				'\u1E79': 'u',
				'\u016B': 'u',
				'\u1E7B': 'u',
				'\u016D': 'u',
				'\u00FC': 'u',
				'\u01DC': 'u',
				'\u01D8': 'u',
				'\u01D6': 'u',
				'\u01DA': 'u',
				'\u1EE7': 'u',
				'\u016F': 'u',
				'\u0171': 'u',
				'\u01D4': 'u',
				'\u0215': 'u',
				'\u0217': 'u',
				'\u01B0': 'u',
				'\u1EEB': 'u',
				'\u1EE9': 'u',
				'\u1EEF': 'u',
				'\u1EED': 'u',
				'\u1EF1': 'u',
				'\u1EE5': 'u',
				'\u1E73': 'u',
				'\u0173': 'u',
				'\u1E77': 'u',
				'\u1E75': 'u',
				'\u0289': 'u',
				'\u24E5': 'v',
				'\uFF56': 'v',
				'\u1E7D': 'v',
				'\u1E7F': 'v',
				'\u028B': 'v',
				'\uA75F': 'v',
				'\u028C': 'v',
				'\uA761': 'vy',
				'\u24E6': 'w',
				'\uFF57': 'w',
				'\u1E81': 'w',
				'\u1E83': 'w',
				'\u0175': 'w',
				'\u1E87': 'w',
				'\u1E85': 'w',
				'\u1E98': 'w',
				'\u1E89': 'w',
				'\u2C73': 'w',
				'\u24E7': 'x',
				'\uFF58': 'x',
				'\u1E8B': 'x',
				'\u1E8D': 'x',
				'\u24E8': 'y',
				'\uFF59': 'y',
				'\u1EF3': 'y',
				'\u00FD': 'y',
				'\u0177': 'y',
				'\u1EF9': 'y',
				'\u0233': 'y',
				'\u1E8F': 'y',
				'\u00FF': 'y',
				'\u1EF7': 'y',
				'\u1E99': 'y',
				'\u1EF5': 'y',
				'\u01B4': 'y',
				'\u024F': 'y',
				'\u1EFF': 'y',
				'\u24E9': 'z',
				'\uFF5A': 'z',
				'\u017A': 'z',
				'\u1E91': 'z',
				'\u017C': 'z',
				'\u017E': 'z',
				'\u1E93': 'z',
				'\u1E95': 'z',
				'\u01B6': 'z',
				'\u0225': 'z',
				'\u0240': 'z',
				'\u2C6C': 'z',
				'\uA763': 'z',
				'\u0386': '\u0391',
				'\u0388': '\u0395',
				'\u0389': '\u0397',
				'\u038A': '\u0399',
				'\u03AA': '\u0399',
				'\u038C': '\u039F',
				'\u038E': '\u03A5',
				'\u03AB': '\u03A5',
				'\u038F': '\u03A9',
				'\u03AC': '\u03B1',
				'\u03AD': '\u03B5',
				'\u03AE': '\u03B7',
				'\u03AF': '\u03B9',
				'\u03CA': '\u03B9',
				'\u0390': '\u03B9',
				'\u03CC': '\u03BF',
				'\u03CD': '\u03C5',
				'\u03CB': '\u03C5',
				'\u03B0': '\u03C5',
				'\u03C9': '\u03C9',
				'\u03C2': '\u03C3'
			};

			return diacritics;
		} );

		S2.define( 'select2/data/base', [
			'../utils'
		], function ( Utils ) {
			function BaseAdapter( $element, options ) {
				BaseAdapter.__super__.constructor.call( this );
			}

			Utils.Extend( BaseAdapter, Utils.Observable );

			BaseAdapter.prototype.current = function ( callback ) {
				throw new Error( 'The `current` method must be defined in child classes.' );
			};

			BaseAdapter.prototype.query = function ( params, callback ) {
				throw new Error( 'The `query` method must be defined in child classes.' );
			};

			BaseAdapter.prototype.bind = function ( container, $container ) {
				// Can be implemented in subclasses
			};

			BaseAdapter.prototype.destroy = function () {
				// Can be implemented in subclasses
			};

			BaseAdapter.prototype.generateResultId = function ( container, data ) {
				var id = container.id + '-result-';

				id += Utils.generateChars( 4 );

				if ( data.id != null ) {
					id += '-' + data.id.toString();
				} else {
					id += '-' + Utils.generateChars( 4 );
				}
				return id;
			};

			return BaseAdapter;
		} );

		S2.define( 'select2/data/select', [
			'./base', '../utils', 'jquery'
		], function ( BaseAdapter, Utils, $ ) {
			function SelectAdapter( $element, options ) {
				this.$element = $element;
				this.options = options;

				SelectAdapter.__super__.constructor.call( this );
			}

			Utils.Extend( SelectAdapter, BaseAdapter );

			SelectAdapter.prototype.current = function ( callback ) {
				var data = [];
				var self = this;

				this.$element.find( ':selected' ).each( function () {
					var $option = $( this );

					var option = self.item( $option );

					data.push( option );
				} );

				callback( data );
			};

			SelectAdapter.prototype.select = function ( data ) {
				var self = this;

				data.selected = true;

				// If data.element is a DOM node, use it instead
				if ( $( data.element ).is( 'option' ) ) {
					data.element.selected = true;

					this.$element.trigger( 'change' );

					return;
				}

				if ( this.$element.prop( 'multiple' ) ) {
					this.current( function ( currentData ) {
						var val = [];

						data = [data];
						data.push.apply( data, currentData );

						for ( var d = 0; d < data.length; d ++ ) {
							var id = data[d].id;

							if ( $.inArray( id, val ) === - 1 ) {
								val.push( id );
							}
						}

						self.$element.val( val );
						self.$element.trigger( 'change' );
					} );
				} else {
					var val = data.id;

					this.$element.val( val );
					this.$element.trigger( 'change' );
				}
			};

			SelectAdapter.prototype.unselect = function ( data ) {
				var self = this;

				if ( ! this.$element.prop( 'multiple' ) ) {
					return;
				}

				data.selected = false;

				if ( $( data.element ).is( 'option' ) ) {
					data.element.selected = false;

					this.$element.trigger( 'change' );

					return;
				}

				this.current( function ( currentData ) {
					var val = [];

					for ( var d = 0; d < currentData.length; d ++ ) {
						var id = currentData[d].id;

						if ( id !== data.id && $.inArray( id, val ) === - 1 ) {
							val.push( id );
						}
					}

					self.$element.val( val );

					self.$element.trigger( 'change' );
				} );
			};

			SelectAdapter.prototype.bind = function ( container, $container ) {
				var self = this;

				this.container = container;

				container.on( 'select', function ( params ) {
					self.select( params.data );
				} );

				container.on( 'unselect', function ( params ) {
					self.unselect( params.data );
				} );
			};

			SelectAdapter.prototype.destroy = function () {
				// Remove anything added to child elements
				this.$element.find( '*' ).each( function () {
					// Remove any custom data set by Select2
					$.removeData( this, 'data' );
				} );
			};

			SelectAdapter.prototype.query = function ( params, callback ) {
				var data = [];
				var self = this;

				var $options = this.$element.children();

				$options.each( function () {
					var $option = $( this );

					if ( ! $option.is( 'option' ) && ! $option.is( 'optgroup' ) ) {
						return;
					}

					var option = self.item( $option );

					var matches = self.matches( params, option );

					if ( matches !== null ) {
						data.push( matches );
					}
				} );

				callback( {
					results: data
				} );
			};

			SelectAdapter.prototype.addOptions = function ( $options ) {
				Utils.appendMany( this.$element, $options );
			};

			SelectAdapter.prototype.option = function ( data ) {
				var option;

				if ( data.children ) {
					option = document.createElement( 'optgroup' );
					option.label = data.text;
				} else {
					option = document.createElement( 'option' );

					if ( option.textContent !== undefined ) {
						option.textContent = data.text;
					} else {
						option.innerText = data.text;
					}
				}

				if ( data.id ) {
					option.value = data.id;
				}

				if ( data.disabled ) {
					option.disabled = true;
				}

				if ( data.selected ) {
					option.selected = true;
				}

				if ( data.title ) {
					option.title = data.title;
				}

				var $option = $( option );

				var normalizedData = this._normalizeItem( data );
				normalizedData.element = option;

				// Override the option's data with the combined data
				$.data( option, 'data', normalizedData );

				return $option;
			};

			SelectAdapter.prototype.item = function ( $option ) {
				var data = {};

				data = $.data( $option[0], 'data' );

				if ( data != null ) {
					return data;
				}

				if ( $option.is( 'option' ) ) {
					data = {
						id: $option.val(),
						text: $option.text(),
						disabled: $option.prop( 'disabled' ),
						selected: $option.prop( 'selected' ),
						title: $option.prop( 'title' )
					};
				} else if ( $option.is( 'optgroup' ) ) {
					data = {
						text: $option.prop( 'label' ), children: [], title: $option.prop( 'title' )
					};

					var $children = $option.children( 'option' );
					var children = [];

					for ( var c = 0; c < $children.length; c ++ ) {
						var $child = $( $children[c] );

						var child = this.item( $child );

						children.push( child );
					}

					data.children = children;
				}

				data = this._normalizeItem( data );
				data.element = $option[0];

				$.data( $option[0], 'data', data );

				return data;
			};

			SelectAdapter.prototype._normalizeItem = function ( item ) {
				if ( ! $.isPlainObject( item ) ) {
					item = {
						id: item, text: item
					};
				}

				item = $.extend( {}, {
					text: ''
				}, item );

				var defaults = {
					selected: false, disabled: false
				};

				if ( item.id != null ) {
					item.id = item.id.toString();
				}

				if ( item.text != null ) {
					item.text = item.text.toString();
				}

				if ( item._resultId == null && item.id && this.container != null ) {
					item._resultId = this.generateResultId( this.container, item );
				}

				return $.extend( {}, defaults, item );
			};

			SelectAdapter.prototype.matches = function ( params, data ) {
				var matcher = this.options.get( 'matcher' );

				return matcher( params, data );
			};

			return SelectAdapter;
		} );

		S2.define( 'select2/data/array', [
			'./select', '../utils', 'jquery'
		], function ( SelectAdapter, Utils, $ ) {
			function ArrayAdapter( $element, options ) {
				var data = options.get( 'data' ) || [];

				ArrayAdapter.__super__.constructor.call( this, $element, options );

				this.addOptions( this.convertToOptions( data ) );
			}

			Utils.Extend( ArrayAdapter, SelectAdapter );

			ArrayAdapter.prototype.select = function ( data ) {
				var $option = this.$element.find( 'option' ).filter( function ( i, elm ) {
					return elm.value == data.id.toString();
				} );

				if ( $option.length === 0 ) {
					$option = this.option( data );

					this.addOptions( $option );
				}

				ArrayAdapter.__super__.select.call( this, data );
			};

			ArrayAdapter.prototype.convertToOptions = function ( data ) {
				var self = this;

				var $existing = this.$element.find( 'option' );
				var existingIds = $existing.map( function () {
					return self.item( $( this ) ).id;
				} ).get();

				var $options = [];

				// Filter out all items except for the one passed in the argument
				function onlyItem( item ) {
					return function () {
						return $( this ).val() == item.id;
					};
				}

				for ( var d = 0; d < data.length; d ++ ) {
					var item = this._normalizeItem( data[d] );

					// Skip items which were pre-loaded, only merge the data
					if ( $.inArray( item.id, existingIds ) >= 0 ) {
						var $existingOption = $existing.filter( onlyItem( item ) );

						var existingData = this.item( $existingOption );
						var newData = $.extend( true, {}, item, existingData );

						var $newOption = this.option( newData );

						$existingOption.replaceWith( $newOption );

						continue;
					}

					var $option = this.option( item );

					if ( item.children ) {
						var $children = this.convertToOptions( item.children );

						Utils.appendMany( $option, $children );
					}

					$options.push( $option );
				}

				return $options;
			};

			return ArrayAdapter;
		} );

		S2.define( 'select2/data/ajax', [
			'./array', '../utils', 'jquery'
		], function ( ArrayAdapter, Utils, $ ) {
			function AjaxAdapter( $element, options ) {
				this.ajaxOptions = this._applyDefaults( options.get( 'ajax' ) );

				if ( this.ajaxOptions.processResults != null ) {
					this.processResults = this.ajaxOptions.processResults;
				}

				AjaxAdapter.__super__.constructor.call( this, $element, options );
			}

			Utils.Extend( AjaxAdapter, ArrayAdapter );

			AjaxAdapter.prototype._applyDefaults = function ( options ) {
				var defaults = {
					data: function ( params ) {
						return $.extend( {}, params, {
							q: params.term
						} );
					}, transport: function ( params, success, failure ) {
						var $request = $.ajax( params );

						$request.then( success );
						$request.fail( failure );

						return $request;
					}
				};

				return $.extend( {}, defaults, options, true );
			};

			AjaxAdapter.prototype.processResults = function ( results ) {
				return results;
			};

			AjaxAdapter.prototype.query = function ( params, callback ) {
				var matches = [];
				var self = this;

				if ( this._request != null ) {
					// JSONP requests cannot always be aborted
					if ( $.isFunction( this._request.abort ) ) {
						this._request.abort();
					}

					this._request = null;
				}

				var options = $.extend( {
					type: 'GET'
				}, this.ajaxOptions );

				if ( typeof options.url === 'function' ) {
					options.url = options.url.call( this.$element, params );
				}

				if ( typeof options.data === 'function' ) {
					options.data = options.data.call( this.$element, params );
				}

				function request() {
					var $request = options.transport( options, function ( data ) {
						var results = self.processResults( data, params );

						if ( self.options.get( 'debug' ) && window.console && console.error ) {
							// Check to make sure that the response included a `results` key.
							if ( ! results || ! results.results || ! $.isArray( results.results ) ) {
								console.error( 'Select2: The AJAX results did not return an array in the ' + '`results` key of the response.' );
							}
						}

						callback( results );
					}, function () {
						self.trigger( 'results:message', {
							message: 'errorLoading'
						} );
					} );

					self._request = $request;
				}

				if ( this.ajaxOptions.delay && params.term !== '' ) {
					if ( this._queryTimeout ) {
						window.clearTimeout( this._queryTimeout );
					}

					this._queryTimeout = window.setTimeout( request, this.ajaxOptions.delay );
				} else {
					request();
				}
			};

			return AjaxAdapter;
		} );

		S2.define( 'select2/data/tags', [
			'jquery'
		], function ( $ ) {
			function Tags( decorated, $element, options ) {
				var tags = options.get( 'tags' );

				var createTag = options.get( 'createTag' );

				if ( createTag !== undefined ) {
					this.createTag = createTag;
				}

				var insertTag = options.get( 'insertTag' );

				if ( insertTag !== undefined ) {
					this.insertTag = insertTag;
				}

				decorated.call( this, $element, options );

				if ( $.isArray( tags ) ) {
					for ( var t = 0; t < tags.length; t ++ ) {
						var tag = tags[t];
						var item = this._normalizeItem( tag );

						var $option = this.option( item );

						this.$element.append( $option );
					}
				}
			}

			Tags.prototype.query = function ( decorated, params, callback ) {
				var self = this;

				this._removeOldTags();

				if ( params.term == null || params.page != null ) {
					decorated.call( this, params, callback );
					return;
				}

				function wrapper( obj, child ) {
					var data = obj.results;

					for ( var i = 0; i < data.length; i ++ ) {
						var option = data[i];

						var checkChildren = (
							option.children != null && ! wrapper( {
								results: option.children
							}, true )
						);

						var checkText = option.text === params.term;

						if ( checkText || checkChildren ) {
							if ( child ) {
								return false;
							}

							obj.data = data;
							callback( obj );

							return;
						}
					}

					if ( child ) {
						return true;
					}

					var tag = self.createTag( params );

					if ( tag != null ) {
						var $option = self.option( tag );
						$option.attr( 'data-select2-tag', true );

						self.addOptions( [$option] );

						self.insertTag( data, tag );
					}

					obj.results = data;

					callback( obj );
				}

				decorated.call( this, params, wrapper );
			};

			Tags.prototype.createTag = function ( decorated, params ) {
				var term = $.trim( params.term );

				if ( term === '' ) {
					return null;
				}

				return {
					id: term, text: term
				};
			};

			Tags.prototype.insertTag = function ( _, data, tag ) {
				data.unshift( tag );
			};

			Tags.prototype._removeOldTags = function ( _ ) {
				var tag = this._lastTag;

				var $options = this.$element.find( 'option[data-select2-tag]' );

				$options.each( function () {
					if ( this.selected ) {
						return;
					}

					$( this ).remove();
				} );
			};

			return Tags;
		} );

		S2.define( 'select2/data/tokenizer', [
			'jquery'
		], function ( $ ) {
			function Tokenizer( decorated, $element, options ) {
				var tokenizer = options.get( 'tokenizer' );

				if ( tokenizer !== undefined ) {
					this.tokenizer = tokenizer;
				}

				decorated.call( this, $element, options );
			}

			Tokenizer.prototype.bind = function ( decorated, container, $container ) {
				decorated.call( this, container, $container );

				this.$search = container.dropdown.$search || container.selection.$search || $container.find( '.select2-search__field' );
			};

			Tokenizer.prototype.query = function ( decorated, params, callback ) {
				var self = this;

				function select( data ) {
					self.trigger( 'select', {
						data: data
					} );
				}

				params.term = params.term || '';

				var tokenData = this.tokenizer( params, this.options, select );

				if ( tokenData.term !== params.term ) {
					// Replace the search term if we have the search box
					if ( this.$search.length ) {
						this.$search.val( tokenData.term );
						this.$search.focus();
					}

					params.term = tokenData.term;
				}

				decorated.call( this, params, callback );
			};

			Tokenizer.prototype.tokenizer = function ( _, params, options, callback ) {
				var separators = options.get( 'tokenSeparators' ) || [];
				var term = params.term;
				var i = 0;

				var createTag = this.createTag || function ( params ) {
						return {
							id: params.term, text: params.term
						};
					};

				while ( i < term.length ) {
					var termChar = term[i];

					if ( $.inArray( termChar, separators ) === - 1 ) {
						i ++;

						continue;
					}

					var part = term.substr( 0, i );
					var partParams = $.extend( {}, params, {
						term: part
					} );

					var data = createTag( partParams );

					if ( data == null ) {
						i ++;
						continue;
					}

					callback( data );

					// Reset the term to not include the tokenized portion
					term = term.substr( i + 1 ) || '';
					i = 0;
				}

				return {
					term: term
				};
			};

			return Tokenizer;
		} );

		S2.define( 'select2/data/minimumInputLength', [], function () {
			function MinimumInputLength( decorated, $e, options ) {
				this.minimumInputLength = options.get( 'minimumInputLength' );

				decorated.call( this, $e, options );
			}

			MinimumInputLength.prototype.query = function ( decorated, params, callback ) {
				params.term = params.term || '';

				if ( params.term.length < this.minimumInputLength ) {
					this.trigger( 'results:message', {
						message: 'inputTooShort', args: {
							minimum: this.minimumInputLength, input: params.term, params: params
						}
					} );

					return;
				}

				decorated.call( this, params, callback );
			};

			return MinimumInputLength;
		} );

		S2.define( 'select2/data/maximumInputLength', [], function () {
			function MaximumInputLength( decorated, $e, options ) {
				this.maximumInputLength = options.get( 'maximumInputLength' );

				decorated.call( this, $e, options );
			}

			MaximumInputLength.prototype.query = function ( decorated, params, callback ) {
				params.term = params.term || '';

				if ( this.maximumInputLength > 0 && params.term.length > this.maximumInputLength ) {
					this.trigger( 'results:message', {
						message: 'inputTooLong', args: {
							maximum: this.maximumInputLength, input: params.term, params: params
						}
					} );

					return;
				}

				decorated.call( this, params, callback );
			};

			return MaximumInputLength;
		} );

		S2.define( 'select2/data/maximumSelectionLength', [], function () {
			function MaximumSelectionLength( decorated, $e, options ) {
				this.maximumSelectionLength = options.get( 'maximumSelectionLength' );

				decorated.call( this, $e, options );
			}

			MaximumSelectionLength.prototype.query = function ( decorated, params, callback ) {
				var self = this;

				this.current( function ( currentData ) {
					var count = currentData != null ? currentData.length : 0;
					if ( self.maximumSelectionLength > 0 && count >= self.maximumSelectionLength ) {
						self.trigger( 'results:message', {
							message: 'maximumSelected', args: {
								maximum: self.maximumSelectionLength
							}
						} );
						return;
					}
					decorated.call( self, params, callback );
				} );
			};

			return MaximumSelectionLength;
		} );

		S2.define( 'select2/dropdown', [
			'jquery', './utils'
		], function ( $, Utils ) {
			function Dropdown( $element, options ) {
				this.$element = $element;
				this.options = options;

				Dropdown.__super__.constructor.call( this );
			}

			Utils.Extend( Dropdown, Utils.Observable );

			Dropdown.prototype.render = function () {
				var $dropdown = $( '<span class="select2-dropdown">' + '<span class="select2-results"></span>' + '</span>' );

				$dropdown.attr( 'dir', this.options.get( 'dir' ) );

				this.$dropdown = $dropdown;

				return $dropdown;
			};

			Dropdown.prototype.bind = function () {
				// Should be implemented in subclasses
			};

			Dropdown.prototype.position = function ( $dropdown, $container ) {
				// Should be implmented in subclasses
			};

			Dropdown.prototype.destroy = function () {
				// Remove the dropdown from the DOM
				this.$dropdown.remove();
			};

			return Dropdown;
		} );

		S2.define( 'select2/dropdown/search', [
			'jquery', '../utils'
		], function ( $, Utils ) {
			function Search() {
			}

			Search.prototype.render = function ( decorated ) {
				var $rendered = decorated.call( this );

				var $search = $( '<span class="select2-search select2-search--dropdown">' + '<input class="select2-search__field" type="search" tabindex="-1"' + ' autocomplete="off" autocorrect="off" autocapitalize="off"' + ' spellcheck="false" role="textbox" />' + '</span>' );

				this.$searchContainer = $search;
				this.$search = $search.find( 'input' );

				$rendered.prepend( $search );

				return $rendered;
			};

			Search.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				decorated.call( this, container, $container );

				this.$search.on( 'keydown', function ( evt ) {
					self.trigger( 'keypress', evt );

					self._keyUpPrevented = evt.isDefaultPrevented();
				} );

				// Workaround for browsers which do not support the `input` event
				// This will prevent double-triggering of events for browsers which support
				// both the `keyup` and `input` events.
				this.$search.on( 'input', function ( evt ) {
					// Unbind the duplicated `keyup` event
					$( this ).off( 'keyup' );
				} );

				this.$search.on( 'keyup input', function ( evt ) {
					self.handleSearch( evt );
				} );

				container.on( 'open', function () {
					self.$search.attr( 'tabindex', 0 );

					self.$search.focus();

					window.setTimeout( function () {
						self.$search.focus();
					}, 0 );
				} );

				container.on( 'close', function () {
					self.$search.attr( 'tabindex', - 1 );

					self.$search.val( '' );
				} );

				container.on( 'results:all', function ( params ) {
					if ( params.query.term == null || params.query.term === '' ) {
						var showSearch = self.showSearch( params );

						if ( showSearch ) {
							self.$searchContainer.removeClass( 'select2-search--hide' );
						} else {
							self.$searchContainer.addClass( 'select2-search--hide' );
						}
					}
				} );
			};

			Search.prototype.handleSearch = function ( evt ) {
				if ( ! this._keyUpPrevented ) {
					var input = this.$search.val();

					this.trigger( 'query', {
						term: input
					} );
				}

				this._keyUpPrevented = false;
			};

			Search.prototype.showSearch = function ( _, params ) {
				return true;
			};

			return Search;
		} );

		S2.define( 'select2/dropdown/hidePlaceholder', [], function () {
			function HidePlaceholder( decorated, $element, options, dataAdapter ) {
				this.placeholder = this.normalizePlaceholder( options.get( 'placeholder' ) );

				decorated.call( this, $element, options, dataAdapter );
			}

			HidePlaceholder.prototype.append = function ( decorated, data ) {
				data.results = this.removePlaceholder( data.results );

				decorated.call( this, data );
			};

			HidePlaceholder.prototype.normalizePlaceholder = function ( _, placeholder ) {
				if ( typeof placeholder === 'string' ) {
					placeholder = {
						id: '', text: placeholder
					};
				}

				return placeholder;
			};

			HidePlaceholder.prototype.removePlaceholder = function ( _, data ) {
				var modifiedData = data.slice( 0 );

				for ( var d = data.length - 1; d >= 0; d -- ) {
					var item = data[d];

					if ( this.placeholder.id === item.id ) {
						modifiedData.splice( d, 1 );
					}
				}

				return modifiedData;
			};

			return HidePlaceholder;
		} );

		S2.define( 'select2/dropdown/infiniteScroll', [
			'jquery'
		], function ( $ ) {
			function InfiniteScroll( decorated, $element, options, dataAdapter ) {
				this.lastParams = {};

				decorated.call( this, $element, options, dataAdapter );

				this.$loadingMore = this.createLoadingMore();
				this.loading = false;
			}

			InfiniteScroll.prototype.append = function ( decorated, data ) {
				this.$loadingMore.remove();
				this.loading = false;

				decorated.call( this, data );

				if ( this.showLoadingMore( data ) ) {
					this.$results.append( this.$loadingMore );
				}
			};

			InfiniteScroll.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				decorated.call( this, container, $container );

				container.on( 'query', function ( params ) {
					self.lastParams = params;
					self.loading = true;
				} );

				container.on( 'query:append', function ( params ) {
					self.lastParams = params;
					self.loading = true;
				} );

				this.$results.on( 'scroll', function () {
					var isLoadMoreVisible = $.contains( document.documentElement, self.$loadingMore[0] );

					if ( self.loading || ! isLoadMoreVisible ) {
						return;
					}

					var currentOffset = self.$results.offset().top + self.$results.outerHeight( false );
					var loadingMoreOffset = self.$loadingMore.offset().top + self.$loadingMore.outerHeight( false );

					if ( currentOffset + 50 >= loadingMoreOffset ) {
						self.loadMore();
					}
				} );
			};

			InfiniteScroll.prototype.loadMore = function () {
				this.loading = true;

				var params = $.extend( {}, {page: 1}, this.lastParams );

				params.page ++;

				this.trigger( 'query:append', params );
			};

			InfiniteScroll.prototype.showLoadingMore = function ( _, data ) {
				return data.pagination && data.pagination.more;
			};

			InfiniteScroll.prototype.createLoadingMore = function () {
				var $option = $( '<li ' + 'class="select2-results__option select2-results__option--load-more"' + 'role="treeitem" aria-disabled="true"></li>' );

				var message = this.options.get( 'translations' ).get( 'loadingMore' );

				$option.html( message( this.lastParams ) );

				return $option;
			};

			return InfiniteScroll;
		} );

		S2.define( 'select2/dropdown/attachBody', [
			'jquery', '../utils'
		], function ( $, Utils ) {
			function AttachBody( decorated, $element, options ) {
				this.$dropdownParent = options.get( 'dropdownParent' ) || $( document.body );

				decorated.call( this, $element, options );
			}

			AttachBody.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				var setupResultsEvents = false;

				decorated.call( this, container, $container );

				container.on( 'open', function () {
					self._showDropdown();
					self._attachPositioningHandler( container );

					if ( ! setupResultsEvents ) {
						setupResultsEvents = true;

						container.on( 'results:all', function () {
							self._positionDropdown();
							self._resizeDropdown();
						} );

						container.on( 'results:append', function () {
							self._positionDropdown();
							self._resizeDropdown();
						} );
					}
				} );

				container.on( 'close', function () {
					self._hideDropdown();
					self._detachPositioningHandler( container );
				} );

				this.$dropdownContainer.on( 'mousedown', function ( evt ) {
					evt.stopPropagation();
				} );
			};

			AttachBody.prototype.destroy = function ( decorated ) {
				decorated.call( this );

				this.$dropdownContainer.remove();
			};

			AttachBody.prototype.position = function ( decorated, $dropdown, $container ) {
				// Clone all of the container classes
				$dropdown.attr( 'class', $container.attr( 'class' ) );

				$dropdown.removeClass( 'select2' );
				$dropdown.addClass( 'select2-container--open' );

				$dropdown.css( {
					position: 'absolute', top: - 999999
				} );

				this.$container = $container;
			};

			AttachBody.prototype.render = function ( decorated ) {
				var $container = $( '<span></span>' );

				var $dropdown = decorated.call( this );
				$container.append( $dropdown );

				this.$dropdownContainer = $container;

				return $container;
			};

			AttachBody.prototype._hideDropdown = function ( decorated ) {
				this.$dropdownContainer.detach();
			};

			AttachBody.prototype._attachPositioningHandler = function ( decorated, container ) {
				var self = this;

				var scrollEvent = 'scroll.select2.' + container.id;
				var resizeEvent = 'resize.select2.' + container.id;
				var orientationEvent = 'orientationchange.select2.' + container.id;

				var $watchers = this.$container.parents().filter( Utils.hasScroll );
				$watchers.each( function () {
					$( this ).data( 'select2-scroll-position', {
						x: $( this ).scrollLeft(), y: $( this ).scrollTop()
					} );
				} );

				$watchers.on( scrollEvent, function ( ev ) {
					var position = $( this ).data( 'select2-scroll-position' );
					$( this ).scrollTop( position.y );
				} );

				$( window ).on( scrollEvent + ' ' + resizeEvent + ' ' + orientationEvent, function ( e ) {
					self._positionDropdown();
					self._resizeDropdown();
				} );
			};

			AttachBody.prototype._detachPositioningHandler = function ( decorated, container ) {
				var scrollEvent = 'scroll.select2.' + container.id;
				var resizeEvent = 'resize.select2.' + container.id;
				var orientationEvent = 'orientationchange.select2.' + container.id;

				var $watchers = this.$container.parents().filter( Utils.hasScroll );
				$watchers.off( scrollEvent );

				$( window ).off( scrollEvent + ' ' + resizeEvent + ' ' + orientationEvent );
			};

			AttachBody.prototype._positionDropdown = function () {
				var $window = $( window );

				var isCurrentlyAbove = this.$dropdown.hasClass( 'select2-dropdown--above' );
				var isCurrentlyBelow = this.$dropdown.hasClass( 'select2-dropdown--below' );

				var newDirection = null;

				var offset = this.$container.offset();

				offset.bottom = offset.top + this.$container.outerHeight( false );

				var container = {
					height: this.$container.outerHeight( false )
				};

				container.top = offset.top;
				container.bottom = offset.top + container.height;

				var dropdown = {
					height: this.$dropdown.outerHeight( false )
				};

				var viewport = {
					top: $window.scrollTop(), bottom: $window.scrollTop() + $window.height()
				};

				var enoughRoomAbove = viewport.top < (offset.top - dropdown.height);
				var enoughRoomBelow = viewport.bottom > (offset.bottom + dropdown.height);

				var css = {
					left: offset.left, top: container.bottom
				};

				// Determine what the parent element is to use for calciulating the offset
				var $offsetParent = this.$dropdownParent;

				// For statically positoned elements, we need to get the element
				// that is determining the offset
				if ( $offsetParent.css( 'position' ) === 'static' ) {
					$offsetParent = $offsetParent.offsetParent();
				}

				var parentOffset = $offsetParent.offset();

				css.top -= parentOffset.top;
				css.left -= parentOffset.left;

				if ( ! isCurrentlyAbove && ! isCurrentlyBelow ) {
					newDirection = 'below';
				}

				if ( ! enoughRoomBelow && enoughRoomAbove && ! isCurrentlyAbove ) {
					newDirection = 'above';
				} else if ( ! enoughRoomAbove && enoughRoomBelow && isCurrentlyAbove ) {
					newDirection = 'below';
				}

				if ( newDirection == 'above' || (isCurrentlyAbove && newDirection !== 'below') ) {
					css.top = container.top - dropdown.height;
				}

				if ( newDirection != null ) {
					this.$dropdown
						.removeClass( 'select2-dropdown--below select2-dropdown--above' )
						.addClass( 'select2-dropdown--' + newDirection );
					this.$container
						.removeClass( 'select2-container--below select2-container--above' )
						.addClass( 'select2-container--' + newDirection );
				}

				this.$dropdownContainer.css( css );
			};

			AttachBody.prototype._resizeDropdown = function () {
				var css = {
					width: this.$container.outerWidth( false ) + 'px'
				};

				if ( this.options.get( 'dropdownAutoWidth' ) ) {
					css.minWidth = css.width;
					css.width = 'auto';
				}

				this.$dropdown.css( css );
			};

			AttachBody.prototype._showDropdown = function ( decorated ) {
				this.$dropdownContainer.appendTo( this.$dropdownParent );

				this._positionDropdown();
				this._resizeDropdown();
			};

			return AttachBody;
		} );

		S2.define( 'select2/dropdown/minimumResultsForSearch', [], function () {
			function countResults( data ) {
				var count = 0;

				for ( var d = 0; d < data.length; d ++ ) {
					var item = data[d];

					if ( item.children ) {
						count += countResults( item.children );
					} else {
						count ++;
					}
				}

				return count;
			}

			function MinimumResultsForSearch( decorated, $element, options, dataAdapter ) {
				this.minimumResultsForSearch = options.get( 'minimumResultsForSearch' );

				if ( this.minimumResultsForSearch < 0 ) {
					this.minimumResultsForSearch = Infinity;
				}

				decorated.call( this, $element, options, dataAdapter );
			}

			MinimumResultsForSearch.prototype.showSearch = function ( decorated, params ) {
				if ( countResults( params.data.results ) < this.minimumResultsForSearch ) {
					return false;
				}

				return decorated.call( this, params );
			};

			return MinimumResultsForSearch;
		} );

		S2.define( 'select2/dropdown/selectOnClose', [], function () {
			function SelectOnClose() {
			}

			SelectOnClose.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				decorated.call( this, container, $container );

				container.on( 'close', function () {
					self._handleSelectOnClose();
				} );
			};

			SelectOnClose.prototype._handleSelectOnClose = function () {
				var $highlightedResults = this.getHighlightedResults();

				// Only select highlighted results
				if ( $highlightedResults.length < 1 ) {
					return;
				}

				var data = $highlightedResults.data( 'data' );

				// Don't re-select already selected resulte
				if ( (data.element != null && data.element.selected) || (data.element == null && data.selected) ) {
					return;
				}

				this.trigger( 'select', {
					data: data
				} );
			};

			return SelectOnClose;
		} );

		S2.define( 'select2/dropdown/closeOnSelect', [], function () {
			function CloseOnSelect() {
			}

			CloseOnSelect.prototype.bind = function ( decorated, container, $container ) {
				var self = this;

				decorated.call( this, container, $container );

				container.on( 'select', function ( evt ) {
					self._selectTriggered( evt );
				} );

				container.on( 'unselect', function ( evt ) {
					self._selectTriggered( evt );
				} );
			};

			CloseOnSelect.prototype._selectTriggered = function ( _, evt ) {
				var originalEvent = evt.originalEvent;

				// Don't close if the control key is being held
				if ( originalEvent && originalEvent.ctrlKey ) {
					return;
				}

				this.trigger( 'close', {} );
			};

			return CloseOnSelect;
		} );

		S2.define( 'select2/i18n/en', [], function () {
			// English
			return {
				errorLoading: function () {
					return 'The results could not be loaded.';
				}, inputTooLong: function ( args ) {
					var overChars = args.input.length - args.maximum;

					var message = 'Please delete ' + overChars + ' character';

					if ( overChars != 1 ) {
						message += 's';
					}

					return message;
				}, inputTooShort: function ( args ) {
					var remainingChars = args.minimum - args.input.length;

					var message = 'Please enter ' + remainingChars + ' or more characters';

					return message;
				}, loadingMore: function () {
					return 'Loading more results…';
				}, maximumSelected: function ( args ) {
					var message = 'You can only select ' + args.maximum + ' item';

					if ( args.maximum != 1 ) {
						message += 's';
					}

					return message;
				}, noResults: function () {
					return 'No results found';
				}, searching: function () {
					return 'Searching…';
				}
			};
		} );

		S2.define( 'select2/defaults', [
			'jquery',
			'require',

			'./results',

			'./selection/single',
			'./selection/multiple',
			'./selection/placeholder',
			'./selection/allowClear',
			'./selection/search',
			'./selection/eventRelay',

			'./utils',
			'./translation',
			'./diacritics',

			'./data/select',
			'./data/array',
			'./data/ajax',
			'./data/tags',
			'./data/tokenizer',
			'./data/minimumInputLength',
			'./data/maximumInputLength',
			'./data/maximumSelectionLength',

			'./dropdown',
			'./dropdown/search',
			'./dropdown/hidePlaceholder',
			'./dropdown/infiniteScroll',
			'./dropdown/attachBody',
			'./dropdown/minimumResultsForSearch',
			'./dropdown/selectOnClose',
			'./dropdown/closeOnSelect',

			'./i18n/en'
		], function ( $, require, ResultsList, SingleSelection, MultipleSelection, Placeholder, AllowClear, SelectionSearch, EventRelay, Utils, Translation, DIACRITICS, SelectData, ArrayData, AjaxData, Tags, Tokenizer, MinimumInputLength, MaximumInputLength, MaximumSelectionLength, Dropdown, DropdownSearch, HidePlaceholder, InfiniteScroll, AttachBody, MinimumResultsForSearch, SelectOnClose, CloseOnSelect, EnglishTranslation ) {
			function Defaults() {
				this.reset();
			}

			Defaults.prototype.apply = function ( options ) {
				options = $.extend( true, {}, this.defaults, options );

				if ( options.dataAdapter == null ) {
					if ( options.ajax != null ) {
						options.dataAdapter = AjaxData;
					} else if ( options.data != null ) {
						options.dataAdapter = ArrayData;
					} else {
						options.dataAdapter = SelectData;
					}

					if ( options.minimumInputLength > 0 ) {
						options.dataAdapter = Utils.Decorate( options.dataAdapter, MinimumInputLength );
					}

					if ( options.maximumInputLength > 0 ) {
						options.dataAdapter = Utils.Decorate( options.dataAdapter, MaximumInputLength );
					}

					if ( options.maximumSelectionLength > 0 ) {
						options.dataAdapter = Utils.Decorate( options.dataAdapter, MaximumSelectionLength );
					}

					if ( options.tags ) {
						options.dataAdapter = Utils.Decorate( options.dataAdapter, Tags );
					}

					if ( options.tokenSeparators != null || options.tokenizer != null ) {
						options.dataAdapter = Utils.Decorate( options.dataAdapter, Tokenizer );
					}

					if ( options.query != null ) {
						var Query = require( options.amdBase + 'compat/query' );

						options.dataAdapter = Utils.Decorate( options.dataAdapter, Query );
					}

					if ( options.initSelection != null ) {
						var InitSelection = require( options.amdBase + 'compat/initSelection' );

						options.dataAdapter = Utils.Decorate( options.dataAdapter, InitSelection );
					}
				}

				if ( options.resultsAdapter == null ) {
					options.resultsAdapter = ResultsList;

					if ( options.ajax != null ) {
						options.resultsAdapter = Utils.Decorate( options.resultsAdapter, InfiniteScroll );
					}

					if ( options.placeholder != null ) {
						options.resultsAdapter = Utils.Decorate( options.resultsAdapter, HidePlaceholder );
					}

					if ( options.selectOnClose ) {
						options.resultsAdapter = Utils.Decorate( options.resultsAdapter, SelectOnClose );
					}
				}

				if ( options.dropdownAdapter == null ) {
					if ( options.multiple ) {
						options.dropdownAdapter = Dropdown;
					} else {
						var SearchableDropdown = Utils.Decorate( Dropdown, DropdownSearch );

						options.dropdownAdapter = SearchableDropdown;
					}

					if ( options.minimumResultsForSearch !== 0 ) {
						options.dropdownAdapter = Utils.Decorate( options.dropdownAdapter, MinimumResultsForSearch );
					}

					if ( options.closeOnSelect ) {
						options.dropdownAdapter = Utils.Decorate( options.dropdownAdapter, CloseOnSelect );
					}

					if ( options.dropdownCssClass != null || options.dropdownCss != null || options.adaptDropdownCssClass != null ) {
						var DropdownCSS = require( options.amdBase + 'compat/dropdownCss' );

						options.dropdownAdapter = Utils.Decorate( options.dropdownAdapter, DropdownCSS );
					}

					options.dropdownAdapter = Utils.Decorate( options.dropdownAdapter, AttachBody );
				}

				if ( options.selectionAdapter == null ) {
					if ( options.multiple ) {
						options.selectionAdapter = MultipleSelection;
					} else {
						options.selectionAdapter = SingleSelection;
					}

					// Add the placeholder mixin if a placeholder was specified
					if ( options.placeholder != null ) {
						options.selectionAdapter = Utils.Decorate( options.selectionAdapter, Placeholder );
					}

					if ( options.allowClear ) {
						options.selectionAdapter = Utils.Decorate( options.selectionAdapter, AllowClear );
					}

					if ( options.multiple ) {
						options.selectionAdapter = Utils.Decorate( options.selectionAdapter, SelectionSearch );
					}

					if ( options.containerCssClass != null || options.containerCss != null || options.adaptContainerCssClass != null ) {
						var ContainerCSS = require( options.amdBase + 'compat/containerCss' );

						options.selectionAdapter = Utils.Decorate( options.selectionAdapter, ContainerCSS );
					}

					options.selectionAdapter = Utils.Decorate( options.selectionAdapter, EventRelay );
				}

				if ( typeof options.language === 'string' ) {
					// Check if the language is specified with a region
					if ( options.language.indexOf( '-' ) > 0 ) {
						// Extract the region information if it is included
						var languageParts = options.language.split( '-' );
						var baseLanguage = languageParts[0];

						options.language = [options.language, baseLanguage];
					} else {
						options.language = [options.language];
					}
				}

				if ( $.isArray( options.language ) ) {
					var languages = new Translation();
					options.language.push( 'en' );

					var languageNames = options.language;

					for ( var l = 0; l < languageNames.length; l ++ ) {
						var name = languageNames[l];
						var language = {};

						try {
							// Try to load it with the original name
							language = Translation.loadPath( name );
						} catch ( e ) {
							try {
								// If we couldn't load it, check if it wasn't the full path
								name = this.defaults.amdLanguageBase + name;
								language = Translation.loadPath( name );
							} catch ( ex ) {
								// The translation could not be loaded at all. Sometimes this is
								// because of a configuration problem, other times this can be
								// because of how Select2 helps load all possible translation files.
								if ( options.debug && window.console && console.warn ) {
									console.warn( 'Select2: The language file for "' + name + '" could not be ' + 'automatically loaded. A fallback will be used instead.' );
								}

								continue;
							}
						}

						languages.extend( language );
					}

					options.translations = languages;
				} else {
					var baseTranslation = Translation.loadPath( this.defaults.amdLanguageBase + 'en' );
					var customTranslation = new Translation( options.language );

					customTranslation.extend( baseTranslation );

					options.translations = customTranslation;
				}

				return options;
			};

			Defaults.prototype.reset = function () {
				function stripDiacritics( text ) {
					// Used 'uni range + named function' from http://jsperf.com/diacritics/18
					function match( a ) {
						return DIACRITICS[a] || a;
					}

					return text.replace( /[^\u0000-\u007E]/g, match );
				}

				function matcher( params, data ) {
					// Always return the object if there is nothing to compare
					if ( $.trim( params.term ) === '' ) {
						return data;
					}

					// Do a recursive check for options with children
					if ( data.children && data.children.length > 0 ) {
						// Clone the data object if there are children
						// This is required as we modify the object to remove any non-matches
						var match = $.extend( true, {}, data );

						// Check each child of the option
						for ( var c = data.children.length - 1; c >= 0; c -- ) {
							var child = data.children[c];

							var matches = matcher( params, child );

							// If there wasn't a match, remove the object in the array
							if ( matches == null ) {
								match.children.splice( c, 1 );
							}
						}

						// If any children matched, return the new object
						if ( match.children.length > 0 ) {
							return match;
						}

						// If there were no matching children, check just the plain object
						return matcher( params, match );
					}

					var original = stripDiacritics( data.text ).toUpperCase();
					var term = stripDiacritics( params.term ).toUpperCase();

					// Check if the text contains the term
					if ( original.indexOf( term ) > - 1 ) {
						return data;
					}

					// If it doesn't contain the term, don't return anything
					return null;
				}

				this.defaults = {
					amdBase: './',
					amdLanguageBase: './i18n/',
					closeOnSelect: true,
					debug: false,
					dropdownAutoWidth: false,
					escapeMarkup: Utils.escapeMarkup,
					language: EnglishTranslation,
					matcher: matcher,
					minimumInputLength: 0,
					maximumInputLength: 0,
					maximumSelectionLength: 0,
					minimumResultsForSearch: 8,
					selectOnClose: false,
					sorter: function ( data ) {
						return data;
					},
					templateResult: function ( result ) {
						return result.text;
					},
					templateSelection: function ( selection ) {
						return selection.text;
					},
					theme: 'default',
					width: 'resolve'
				};
			};

			Defaults.prototype.set = function ( key, value ) {
				var camelKey = $.camelCase( key );

				var data = {};
				data[camelKey] = value;

				var convertedData = Utils._convertData( data );

				$.extend( this.defaults, convertedData );
			};

			var defaults = new Defaults();

			return defaults;
		} );

		S2.define( 'select2/options', [
			'require', 'jquery', './defaults', './utils'
		], function ( require, $, Defaults, Utils ) {
			function Options( options, $element ) {
				this.options = options;

				if ( $element != null ) {
					this.fromElement( $element );
				}

				this.options = Defaults.apply( this.options );

				if ( $element && $element.is( 'input' ) ) {
					var InputCompat = require( this.get( 'amdBase' ) + 'compat/inputData' );

					this.options.dataAdapter = Utils.Decorate( this.options.dataAdapter, InputCompat );
				}
			}

			Options.prototype.fromElement = function ( $e ) {
				var excludedData = ['select2'];

				if ( this.options.multiple == null ) {
					this.options.multiple = $e.prop( 'multiple' );
				}

				if ( this.options.disabled == null ) {
					this.options.disabled = $e.prop( 'disabled' );
				}

				if ( this.options.language == null ) {
					if ( $e.prop( 'lang' ) ) {
						this.options.language = $e.prop( 'lang' ).toLowerCase();
					} else if ( $e.closest( '[lang]' ).prop( 'lang' ) ) {
						this.options.language = $e.closest( '[lang]' ).prop( 'lang' );
					}
				}

				if ( this.options.dir == null ) {
					if ( $e.prop( 'dir' ) ) {
						this.options.dir = $e.prop( 'dir' );
					} else if ( $e.closest( '[dir]' ).prop( 'dir' ) ) {
						this.options.dir = $e.closest( '[dir]' ).prop( 'dir' );
					} else {
						this.options.dir = 'ltr';
					}
				}

				$e.prop( 'disabled', this.options.disabled );
				$e.prop( 'multiple', this.options.multiple );

				if ( $e.data( 'select2Tags' ) ) {
					if ( this.options.debug && window.console && console.warn ) {
						console.warn( 'Select2: The `data-select2-tags` attribute has been changed to ' + 'use the `data-data` and `data-tags="true"` attributes and will be ' + 'removed in future versions of Select2.' );
					}

					$e.data( 'data', $e.data( 'select2Tags' ) );
					$e.data( 'tags', true );
				}

				if ( $e.data( 'ajaxUrl' ) ) {
					if ( this.options.debug && window.console && console.warn ) {
						console.warn( 'Select2: The `data-ajax-url` attribute has been changed to ' + '`data-ajax--url` and support for the old attribute will be removed' + ' in future versions of Select2.' );
					}

					$e.attr( 'ajax--url', $e.data( 'ajaxUrl' ) );
					$e.data( 'ajax--url', $e.data( 'ajaxUrl' ) );
				}

				var dataset = {};

				// Prefer the element's `dataset` attribute if it exists
				// jQuery 1.x does not correctly handle data attributes with multiple dashes
				if ( $.fn.jquery && $.fn.jquery.substr( 0, 2 ) == '1.' && $e[0].dataset ) {
					dataset = $.extend( true, {}, $e[0].dataset, $e.data() );
				} else {
					dataset = $e.data();
				}

				var data = $.extend( true, {}, dataset );

				data = Utils._convertData( data );

				for ( var key in data ) {
					if ( $.inArray( key, excludedData ) > - 1 ) {
						continue;
					}

					if ( $.isPlainObject( this.options[key] ) ) {
						$.extend( this.options[key], data[key] );
					} else {
						this.options[key] = data[key];
					}
				}

				return this;
			};

			Options.prototype.get = function ( key ) {
				return this.options[key];
			};

			Options.prototype.set = function ( key, val ) {
				this.options[key] = val;
			};

			return Options;
		} );

		S2.define( 'select2/core', [
			'jquery', './options', './utils', './keys'
		], function ( $, Options, Utils, KEYS ) {
			var Select2 = function ( $element, options ) {
				if ( $element.data( 'select2' ) != null ) {
					$element.data( 'select2' ).destroy();
				}

				this.$element = $element;

				this.id = this._generateId( $element );

				options = options || {};

				this.options = new Options( options, $element );


				//this.options.set( 'isModalSelect', _isInModal );

				Select2.__super__.constructor.call( this );

				// Set up the tabindex

				var tabindex = $element.attr( 'tabindex' ) || 0;
				$element.data( 'old-tabindex', tabindex );
				$element.attr( 'tabindex', '-1' );

				// Set up containers and adapters

				var DataAdapter = this.options.get( 'dataAdapter' );
				this.dataAdapter = new DataAdapter( $element, this.options );

				var $container = this.render();

				this._placeContainer( $container );

				var SelectionAdapter = this.options.get( 'selectionAdapter' );
				this.selection = new SelectionAdapter( $element, this.options );
				this.$selection = this.selection.render();

				this.selection.position( this.$selection, $container );

				var DropdownAdapter = this.options.get( 'dropdownAdapter' );
				this.dropdown = new DropdownAdapter( $element, this.options );
				this.$dropdown = this.dropdown.render();

				this.dropdown.position( this.$dropdown, $container );

				var ResultsAdapter = this.options.get( 'resultsAdapter' );
				this.results = new ResultsAdapter( $element, this.options, this.dataAdapter );
				this.$results = this.results.render();

				this.results.position( this.$results, this.$dropdown );

				// Bind events

				var self = this;

				// Bind the container to all of the adapters
				this._bindAdapters();

				// Register any DOM event handlers
				this._registerDomEvents();

				// Register any internal event handlers
				this._registerDataEvents();
				this._registerSelectionEvents();
				this._registerDropdownEvents();
				this._registerResultsEvents();
				this._registerEvents();

				// Set the initial state
				this.dataAdapter.current( function ( initialData ) {
					self.trigger( 'selection:update', {
						data: initialData
					} );
				} );

				// Hide the original select
				$element.addClass( 'select2-hidden-accessible' );
				$element.attr( 'aria-hidden', 'true' );

				// Synchronize any monitored attributes
				this._syncAttributes();

				$element.data( 'select2', this );
			};

			Utils.Extend( Select2, Utils.Observable );

			Select2.prototype._generateId = function ( $element ) {
				var id = '';

				if ( $element.attr( 'id' ) != null ) {
					id = $element.attr( 'id' );
				} else if ( $element.attr( 'name' ) != null ) {
					id = $element.attr( 'name' ) + '-' + Utils.generateChars( 2 );
				} else {
					id = Utils.generateChars( 4 );
				}

				id = id.replace( /(:|\.|\[|\]|,)/g, '' );
				id = 'select2-' + id;

				return id;
			};

			Select2.prototype._placeContainer = function ( $container ) {
				$container.insertAfter( this.$element );
				var _isInModal = $container.parents( '.tvd-modal, #TB_window' ).length ? 'select2-modal' : '';
				if ( _isInModal ) {
					$container.addClass( _isInModal );
				}

				if ( ! this.options.get( 'calculateWidth' ) ) {
					$container.css( 'width', '100%' );
					return;
				}

				var width = this._resolveWidth( this.$element, this.options.get( 'width' ) );

				if ( width != null ) {
					$container.css( 'width', width );
				}
			};

			Select2.prototype._resolveWidth = function ( $element, method ) {
				var WIDTH = /^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i;

				if ( method == 'resolve' ) {
					var styleWidth = this._resolveWidth( $element, 'style' );

					if ( styleWidth != null ) {
						return styleWidth;
					}

					return this._resolveWidth( $element, 'element' );
				}

				if ( method == 'element' ) {
					var elementWidth = $element.outerWidth( false );

					if ( elementWidth <= 0 ) {
						return 'auto';
					}

					return elementWidth + 'px';
				}

				if ( method == 'style' ) {
					var style = $element.attr( 'style' );

					if ( typeof(style) !== 'string' ) {
						return null;
					}

					var attrs = style.split( ';' );

					for ( var i = 0, l = attrs.length; i < l; i = i + 1 ) {
						var attr = attrs[i].replace( /\s/g, '' );
						var matches = attr.match( WIDTH );

						if ( matches !== null && matches.length >= 1 ) {
							return matches[1];
						}
					}

					return null;
				}

				return method;
			};

			Select2.prototype._bindAdapters = function () {
				this.dataAdapter.bind( this, this.$container );
				this.selection.bind( this, this.$container );

				this.dropdown.bind( this, this.$container );
				this.results.bind( this, this.$container );
			};

			Select2.prototype._registerDomEvents = function () {
				var self = this;

				this.$element.on( 'change.select2', function () {
					self.dataAdapter.current( function ( data ) {
						self.trigger( 'selection:update', {
							data: data
						} );
					} );
					self.$element.trigger('tvdclear');
				} ).off( 'tvderror' ).on( 'tvderror', function ( evt, error_message ) {
					self.$element.addClass( 'tvd-invalid' );
					self.$element.parent().find( 'label[for="' + self.$element.attr( 'id' ) + '"]' ).attr( 'data-error', error_message );
					self.$element.parent().find( '#select2-' + self.$element.attr( 'id' ) + '-container' ).parent().addClass( 'tve-leads-input-error' );
				} ).off( 'tvdclear' ).on( 'tvdclear', function () {
					self.$element.removeClass( 'tvd-invalid' );
					self.$element.parent().find( '#select2-' + self.$element.attr( 'id' ) + '-container' ).parent().removeClass( 'tve-leads-input-error' );
				} );

				this._sync = Utils.bind( this._syncAttributes, this );

				if ( this.$element[0].attachEvent ) {
					this.$element[0].attachEvent( 'onpropertychange', this._sync );
				}

				var observer = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

				if ( observer != null ) {
					this._observer = new observer( function ( mutations ) {
						$.each( mutations, self._sync );
					} );
					this._observer.observe( this.$element[0], {
						attributes: true, subtree: false
					} );
				} else if ( this.$element[0].addEventListener ) {
					this.$element[0].addEventListener( 'DOMAttrModified', self._sync, false );
				}
			};

			Select2.prototype._registerDataEvents = function () {
				var self = this;

				this.dataAdapter.on( '*', function ( name, params ) {
					self.trigger( name, params );
				} );
			};

			Select2.prototype._registerSelectionEvents = function () {
				var self = this;
				var nonRelayEvents = ['toggle', 'focus'];

				this.selection.on( 'toggle', function () {
					self.toggleDropdown();
				} );

				this.selection.on( 'focus', function ( params ) {
					self.focus( params );
				} );

				this.selection.on( '*', function ( name, params ) {
					if ( $.inArray( name, nonRelayEvents ) !== - 1 ) {
						return;
					}

					self.trigger( name, params );
				} );
			};

			Select2.prototype._registerDropdownEvents = function () {
				var self = this;

				this.dropdown.on( '*', function ( name, params ) {
					self.trigger( name, params );
				} );
			};

			Select2.prototype._registerResultsEvents = function () {
				var self = this;

				this.results.on( '*', function ( name, params ) {
					self.trigger( name, params );
				} );
			};

			Select2.prototype._registerEvents = function () {
				var self = this;

				this.on( 'open', function () {
					self.$container.addClass( 'select2-container--open' );
				} );

				this.on( 'close', function () {
					self.$container.removeClass( 'select2-container--open' );
				} );

				this.on( 'enable', function () {
					self.$container.removeClass( 'select2-container--disabled' );
				} );

				this.on( 'disable', function () {
					self.$container.addClass( 'select2-container--disabled' );
				} );

				this.on( 'blur', function () {
					self.$container.removeClass( 'select2-container--focus' );
				} );

				this.on( 'query', function ( params ) {
					if ( ! self.isOpen() ) {
						self.trigger( 'open', {} );
					}

					this.dataAdapter.query( params, function ( data ) {
						self.trigger( 'results:all', {
							data: data, query: params
						} );
					} );
				} );

				this.on( 'query:append', function ( params ) {
					this.dataAdapter.query( params, function ( data ) {
						self.trigger( 'results:append', {
							data: data, query: params
						} );
					} );
				} );

				this.on( 'keypress', function ( evt ) {
					var key = evt.which;

					if ( self.isOpen() ) {
						if ( key === KEYS.ESC || key === KEYS.TAB || (key === KEYS.UP && evt.altKey) ) {
							self.close();

							evt.preventDefault();
						} else if ( key === KEYS.ENTER ) {
							self.trigger( 'results:select', {} );

							evt.preventDefault();
						} else if ( (key === KEYS.SPACE && evt.ctrlKey) ) {
							self.trigger( 'results:toggle', {} );

							evt.preventDefault();
						} else if ( key === KEYS.UP ) {
							self.trigger( 'results:previous', {} );

							evt.preventDefault();
						} else if ( key === KEYS.DOWN ) {
							self.trigger( 'results:next', {} );

							evt.preventDefault();
						}
					} else {
						if ( key === KEYS.ENTER || key === KEYS.SPACE || (key === KEYS.DOWN && evt.altKey) ) {
							self.open();

							evt.preventDefault();
						}
					}
				} );
			};

			Select2.prototype._syncAttributes = function () {
				this.options.set( 'disabled', this.$element.prop( 'disabled' ) );

				if ( this.options.get( 'disabled' ) ) {
					if ( this.isOpen() ) {
						this.close();
					}

					this.trigger( 'disable', {} );
				} else {
					this.trigger( 'enable', {} );
				}
			};

			/**
			 * Override the trigger method to automatically trigger pre-events when
			 * there are events that can be prevented.
			 */
			Select2.prototype.trigger = function ( name, args ) {
				var actualTrigger = Select2.__super__.trigger;
				var preTriggerMap = {
					'open': 'opening',
					'close': 'closing',
					'select': 'selecting',
					'unselect': 'unselecting'
				};

				if ( args === undefined ) {
					args = {};
				}

				if ( name in preTriggerMap ) {
					var preTriggerName = preTriggerMap[name];
					var preTriggerArgs = {
						prevented: false, name: name, args: args
					};

					actualTrigger.call( this, preTriggerName, preTriggerArgs );

					if ( preTriggerArgs.prevented ) {
						args.prevented = true;

						return;
					}
				}

				actualTrigger.call( this, name, args );
			};

			Select2.prototype.toggleDropdown = function () {
				if ( this.options.get( 'disabled' ) ) {
					return;
				}

				if ( this.isOpen() ) {
					this.close();
				} else {
					this.open();
				}
			};

			Select2.prototype.open = function () {
				if ( this.isOpen() ) {
					return;
				}

				this.trigger( 'query', {} );
			};

			Select2.prototype.close = function () {
				if ( ! this.isOpen() ) {
					return;
				}

				this.trigger( 'close', {} );
			};

			Select2.prototype.isOpen = function () {
				return this.$container.hasClass( 'select2-container--open' );
			};

			Select2.prototype.hasFocus = function () {
				return this.$container.hasClass( 'select2-container--focus' );
			};

			Select2.prototype.focus = function ( data ) {
				// No need to re-trigger focus events if we are already focused
				if ( this.hasFocus() ) {
					return;
				}

				this.$container.addClass( 'select2-container--focus' );
				this.trigger( 'focus', {} );
			};

			Select2.prototype.enable = function ( args ) {
				if ( this.options.get( 'debug' ) && window.console && console.warn ) {
					console.warn( 'Select2: The `select2("enable")` method has been deprecated and will' + ' be removed in later Select2 versions. Use $element.prop("disabled")' + ' instead.' );
				}

				if ( args == null || args.length === 0 ) {
					args = [true];
				}

				var disabled = ! args[0];

				this.$element.prop( 'disabled', disabled );
			};

			Select2.prototype.data = function () {
				if ( this.options.get( 'debug' ) && arguments.length > 0 && window.console && console.warn ) {
					console.warn( 'Select2: Data can no longer be set using `select2("data")`. You ' + 'should consider setting the value instead using `$element.val()`.' );
				}

				var data = [];

				this.dataAdapter.current( function ( currentData ) {
					data = currentData;
				} );

				return data;
			};

			Select2.prototype.val = function ( args ) {
				if ( this.options.get( 'debug' ) && window.console && console.warn ) {
					console.warn( 'Select2: The `select2("val")` method has been deprecated and will be' + ' removed in later Select2 versions. Use $element.val() instead.' );
				}

				if ( args == null || args.length === 0 ) {
					return this.$element.val();
				}

				var newVal = args[0];

				if ( $.isArray( newVal ) ) {
					newVal = $.map( newVal, function ( obj ) {
						return obj.toString();
					} );
				}

				this.$element.val( newVal ).trigger( 'change' );
			};

			Select2.prototype.destroy = function () {
				this.$container.remove();

				if ( this.$element[0].detachEvent ) {
					this.$element[0].detachEvent( 'onpropertychange', this._sync );
				}

				if ( this._observer != null ) {
					this._observer.disconnect();
					this._observer = null;
				} else if ( this.$element[0].removeEventListener ) {
					this.$element[0]
						.removeEventListener( 'DOMAttrModified', this._sync, false );
				}

				this._sync = null;

				this.$element.off( '.select2' );
				this.$element.attr( 'tabindex', this.$element.data( 'old-tabindex' ) );

				this.$element.removeClass( 'select2-hidden-accessible' );
				this.$element.attr( 'aria-hidden', 'false' );
				this.$element.removeData( 'select2' );

				this.dataAdapter.destroy();
				this.selection.destroy();
				this.dropdown.destroy();
				this.results.destroy();

				this.dataAdapter = null;
				this.selection = null;
				this.dropdown = null;
				this.results = null;
			};

			Select2.prototype.render = function () {

				var $container = $( '<span class="select2 select2-container">' + '<span class="selection"></span>' + '<span class="dropdown-wrapper" aria-hidden="true"></span>' + '</span>' );

				$container.attr( 'dir', this.options.get( 'dir' ) );

				this.$container = $container;

				this.$container.addClass( 'select2-container--' + this.options.get( 'theme' ) );
				$container.data( 'element', this.$element );

				return $container;
			};

			return Select2;
		} );

		S2.define( 'jquery-mousewheel', [
			'jquery'
		], function ( $ ) {
			// Used to shim jQuery.mousewheel for non-full builds.
			return $;
		} );

		S2.define( 'jquery.select2', [
			'jquery', 'jquery-mousewheel',

			'./select2/core', './select2/defaults'
		], function ( $, _, Select2, Defaults ) {
			if ( $.fn.select2 == null ) {
				// All methods that should return the element
				var thisMethods = ['open', 'close', 'destroy'];

				$.fn.select2 = function ( options ) {
					options = options || {};

					if ( typeof options === 'object' ) {
						this.each( function () {
							var instanceOptions = $.extend( true, {}, options );

							var instance = new Select2( $( this ), instanceOptions );
						} );

						return this;
					} else if ( typeof options === 'string' ) {
						var ret;

						this.each( function () {
							var instance = $( this ).data( 'select2' );

							if ( instance == null && window.console && console.error ) {
								console.error( 'The select2(\'' + options + '\') method was called on an ' + 'element that is not using Select2.' );
							}

							var args = Array.prototype.slice.call( arguments, 1 );

							ret = instance[options].apply( instance, args );
						} );

						// Check if we should be returning `this`
						if ( $.inArray( options, thisMethods ) > - 1 ) {
							return this;
						}

						return ret;
					} else {
						throw new Error( 'Invalid arguments for Select2: ' + options );
					}
				};
			}

			if ( $.fn.select2.defaults == null ) {
				$.fn.select2.defaults = Defaults;
			}

			return Select2;
		} );

		// Return the AMD loader configuration so it can be used outside of this file
		return {
			define: S2.define, require: S2.require
		};
	}());

	// Autoload the jQuery bindings
	// We know that all of the modules exist above this, so we're safe
	var select2 = S2.require( 'jquery.select2' );

	// Hold the AMD module references on the jQuery function that was just loaded
	// This allows Select2 to use the internal loader outside of this file, such
	// as in the language files.
	jQuery.fn.select2.amd = S2;
	// Return the Select2 instance for anyone who is importing it.
	return select2;
} ));;  (function ($) {
  $.fn.collapsible = function(options) {
    var defaults = {
        accordion: undefined
    };

    options = $.extend(defaults, options);

    return this.each(function() {

      var $this = $(this);
      if( $this.hasClass('tvd-collapsible-bound') ){
        return;
      }else{
        $this.addClass('tvd-collapsible-bound');
      }

      var $panel_headers = $(this).find('> li > .tvd-collapsible-header');
      var collapsible_type = $this.data("collapsible");

      // Turn off any existing event handlers
       $this.off('click.collapse', '.tvd-collapsible-header');
       $panel_headers.off('click.collapse');


       /****************
       Helper Functions
       ****************/

      // Accordion Open
      function accordionOpen(object) {
        $panel_headers = $this.find('> li > .tvd-collapsible-header');
        if (object.hasClass('tvd-active')) {
            object.parent().addClass('tvd-active');
        }
        else {
            object.parent().removeClass('tvd-active');
        }
        if (object.parent().hasClass('tvd-active')){
          object.siblings('.tvd-collapsible-body').stop(true,false).slideDown({ duration: 350, easing: "easeOutQuart", queue: false, complete: function() {$(this).css('height', '');}});
        }
        else{
          object.siblings('.tvd-collapsible-body').stop(true,false).slideUp({ duration: 350, easing: "easeOutQuart", queue: false, complete: function() {$(this).css('height', '');}});
        }

        $panel_headers.not(object).removeClass('tvd-active').parent().removeClass('tvd-active');
        $panel_headers.not(object).parent().children('.tvd-collapsible-body').stop(true,false).slideUp(
          {
            duration: 350,
            easing: "easeOutQuart",
            queue: false,
            complete:
              function() {
                $(this).css('height', '');
              }
          });
      }

      // Expandable Open
      function expandableOpen(object) {
        if (object.hasClass('tvd-active')) {
            object.parent().addClass('tvd-active');
        }
        else {
            object.parent().removeClass('tvd-active');
        }
        if (object.parent().hasClass('tvd-active')){
          object.siblings('.tvd-collapsible-body').stop(true,false).slideDown({ duration: 350, easing: "easeOutQuart", queue: false, complete: function() {$(this).css('height', '');}});
        }
        else{
          object.siblings('.tvd-collapsible-body').stop(true,false).slideUp({ duration: 350, easing: "easeOutQuart", queue: false, complete: function() {$(this).css('height', '');}});
        }
      }

      /**
       * Check if object is children of panel header
       * @param  {Object}  object Jquery object
       * @return {Boolean} true if it is children
       */
      function isChildrenOfPanelHeader(object) {

        var panelHeader = getPanelHeader(object);

        return panelHeader.length > 0;
      }

      /**
       * Get panel header from a children element
       * @param  {Object} object Jquery object
       * @return {Object} panel header object
       */
      function getPanelHeader(object) {
        return object.closest('li > .tvd-collapsible-header');
      }

      /*****  End Helper Functions  *****/



      // Add click handler to only direct collapsible header children
      $this.on('click.collapse', '> li > .tvd-collapsible-header', function(e) {
        var $header = $(this),
            element = $(e.target);

        if (isChildrenOfPanelHeader(element)) {
          element = getPanelHeader(element);
        }

        element.toggleClass('tvd-active');

        if (options.accordion || collapsible_type === "accordion" || collapsible_type === undefined) { // Handle Accordion
          accordionOpen(element);
        } else { // Handle Expandables
          expandableOpen(element);

          if ($header.hasClass('tvd-active')) {
            expandableOpen($header);
          }
        }
      });

      // Open first active
      var $panel_headers = $this.find('> li > .tvd-collapsible-header');
      if (options.accordion || collapsible_type === "accordion" || collapsible_type === undefined) { // Handle Accordion
        accordionOpen($panel_headers.filter('.tvd-active').first());
      }
      else { // Handle Expandables
        $panel_headers.filter('.tvd-active').each(function() {
          expandableOpen($(this));
        });
      }

    });
  };

  $(document).ready(function(){
    $('.tvd-collapsible').collapsible();
  });
}( jQuery ));;/*!
 * Materialize v0.97.5 (http://materializecss.com)
 * Copyright 2014-2015 Materialize
 * MIT License (https://raw.githubusercontent.com/Dogfalo/materialize/master/LICENSE)
 */
// wNumb
(function(){function r(b){return b.split("").reverse().join("")}function s(b,f,c){if((b[f]||b[c])&&b[f]===b[c])throw Error(f);}function v(b,f,c,d,e,p,q,k,l,h,n,a){q=a;var m,g=n="";p&&(a=p(a));if("number"!==typeof a||!isFinite(a))return!1;b&&0===parseFloat(a.toFixed(b))&&(a=0);0>a&&(m=!0,a=Math.abs(a));b&&(p=Math.pow(10,b),a=(Math.round(a*p)/p).toFixed(b));a=a.toString();-1!==a.indexOf(".")&&(b=a.split("."),a=b[0],c&&(n=c+b[1]));f&&(a=r(a).match(/.{1,3}/g),a=r(a.join(r(f))));m&&k&&(g+=k);d&&(g+=d);
m&&l&&(g+=l);g=g+a+n;e&&(g+=e);h&&(g=h(g,q));return g}function w(b,f,c,d,e,h,q,k,l,r,n,a){var m;b="";n&&(a=n(a));if(!a||"string"!==typeof a)return!1;k&&a.substring(0,k.length)===k&&(a=a.replace(k,""),m=!0);d&&a.substring(0,d.length)===d&&(a=a.replace(d,""));l&&a.substring(0,l.length)===l&&(a=a.replace(l,""),m=!0);e&&a.slice(-1*e.length)===e&&(a=a.slice(0,-1*e.length));f&&(a=a.split(f).join(""));c&&(a=a.replace(c,"."));m&&(b+="-");b=Number((b+a).replace(/[^0-9\.\-.]/g,""));q&&(b=q(b));return"number"===
typeof b&&isFinite(b)?b:!1}function x(b){var f,c,d,e={};for(f=0;f<h.length;f+=1)c=h[f],d=b[c],void 0===d?e[c]="negative"!==c||e.negativeBefore?"mark"===c&&"."!==e.thousand?".":!1:"-":"decimals"===c?0<d&&8>d&&(e[c]=d):"encoder"===c||"decoder"===c||"edit"===c||"undo"===c?"function"===typeof d&&(e[c]=d):"string"===typeof d&&(e[c]=d);s(e,"mark","thousand");s(e,"prefix","negative");s(e,"prefix","negativeBefore");return e}function u(b,f,c){var d,e=[];for(d=0;d<h.length;d+=1)e.push(b[h[d]]);e.push(c);return f.apply("",
e)}function t(b){if(!(this instanceof t))return new t(b);"object"===typeof b&&(b=x(b),this.to=function(f){return u(b,v,f)},this.from=function(f){return u(b,w,f)})}var h="decimals thousand mark prefix postfix encoder decoder negativeBefore negative edit undo".split(" ");window.wNumb=t})();


/*! nouislider - 8.0.2 - 2015-07-06 13:22:09 */

/*jslint browser: true */
/*jslint white: true */

(function (factory) {

    if ( typeof define === 'function' && define.amd ) {

        // AMD. Register as an anonymous module.
        define([], factory);

    } else if ( typeof exports === 'object' ) {

        var fs = require('fs');

        // Node/CommonJS
        module.exports = factory();
        module.exports.css = function () {
            return fs.readFileSync(__dirname + '/nouislider.min.css', 'utf8');
        };

    } else {

        // Browser globals
        window.noUiSlider = factory();
    }

}(function( ){

	'use strict';


	// Removes duplicates from an array.
	function unique(array) {
		return array.filter(function(a){
			return !this[a] ? this[a] = true : false;
		}, {});
	}

	// Round a value to the closest 'to'.
	function closest ( value, to ) {
		return Math.round(value / to) * to;
	}

	// Current position of an element relative to the document.
	function offset ( elem ) {

	var rect = elem.getBoundingClientRect(),
		doc = elem.ownerDocument,
		win = doc.defaultView || doc.parentWindow,
		docElem = doc.documentElement,
		xOff = win.pageXOffset;

		// getBoundingClientRect contains left scroll in Chrome on Android.
		// I haven't found a feature detection that proves this. Worst case
		// scenario on mis-match: the 'tap' feature on horizontal sliders breaks.
		if ( /webkit.*Chrome.*Mobile/i.test(navigator.userAgent) ) {
			xOff = 0;
		}

		return {
			top: rect.top + win.pageYOffset - docElem.clientTop,
			left: rect.left + xOff - docElem.clientLeft
		};
	}

	// Checks whether a value is numerical.
	function isNumeric ( a ) {
		return typeof a === 'number' && !isNaN( a ) && isFinite( a );
	}

	// Rounds a number to 7 supported decimals.
	function accurateNumber( number ) {
		var p = Math.pow(10, 7);
		return Number((Math.round(number*p)/p).toFixed(7));
	}

	// Sets a class and removes it after [duration] ms.
	function addClassFor ( element, className, duration ) {
		addClass(element, className);
		setTimeout(function(){
			removeClass(element, className);
		}, duration);
	}

	// Limits a value to 0 - 100
	function limit ( a ) {
		return Math.max(Math.min(a, 100), 0);
	}

	// Wraps a variable as an array, if it isn't one yet.
	function asArray ( a ) {
		return Array.isArray(a) ? a : [a];
	}

	// Counts decimals
	function countDecimals ( numStr ) {
		var pieces = numStr.split(".");
		return pieces.length > 1 ? pieces[1].length : 0;
	}

	// http://youmightnotneedjquery.com/#add_class
	function addClass ( el, className ) {
		if ( el.classList ) {
			el.classList.add(className);
		} else {
			el.className += ' ' + className;
		}
	}

	// http://youmightnotneedjquery.com/#remove_class
	function removeClass ( el, className ) {
		if ( el.classList ) {
			el.classList.remove(className);
		} else {
			el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
		}
	}

	// http://youmightnotneedjquery.com/#has_class
	function hasClass ( el, className ) {
		if ( el.classList ) {
			el.classList.contains(className);
		} else {
			new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
		}
	}


	var
	// Determine the events to bind. IE11 implements pointerEvents without
	// a prefix, which breaks compatibility with the IE10 implementation.
	/** @const */
	actions = window.navigator.pointerEnabled ? {
		start: 'pointerdown',
		move: 'pointermove',
		end: 'pointerup'
	} : window.navigator.msPointerEnabled ? {
		start: 'MSPointerDown',
		move: 'MSPointerMove',
		end: 'MSPointerUp'
	} : {
		start: 'mousedown touchstart',
		move: 'mousemove touchmove',
		end: 'mouseup touchend'
	},
	// Re-usable list of classes;
	/** @const */
	Classes = [
/*  0 */  'noUi-target'
/*  1 */ ,'noUi-base'
/*  2 */ ,'noUi-origin'
/*  3 */ ,'noUi-handle'
/*  4 */ ,'noUi-horizontal'
/*  5 */ ,'noUi-vertical'
/*  6 */ ,'noUi-background'
/*  7 */ ,'noUi-connect'
/*  8 */ ,'noUi-ltr'
/*  9 */ ,'noUi-rtl'
/* 10 */ ,'noUi-dragable'
/* 11 */ ,''
/* 12 */ ,'noUi-state-drag'
/* 13 */ ,''
/* 14 */ ,'noUi-state-tap'
/* 15 */ ,'noUi-active'
/* 16 */ ,''
/* 17 */ ,'noUi-stacking'
	];


// Value calculation

	// Determine the size of a sub-range in relation to a full range.
	function subRangeRatio ( pa, pb ) {
		return (100 / (pb - pa));
	}

	// (percentage) How many percent is this value of this range?
	function fromPercentage ( range, value ) {
		return (value * 100) / ( range[1] - range[0] );
	}

	// (percentage) Where is this value on this range?
	function toPercentage ( range, value ) {
		return fromPercentage( range, range[0] < 0 ?
			value + Math.abs(range[0]) :
				value - range[0] );
	}

	// (value) How much is this percentage on this range?
	function isPercentage ( range, value ) {
		return ((value * ( range[1] - range[0] )) / 100) + range[0];
	}


// Range conversion

	function getJ ( value, arr ) {

		var j = 1;

		while ( value >= arr[j] ){
			j += 1;
		}

		return j;
	}

	// (percentage) Input a value, find where, on a scale of 0-100, it applies.
	function toStepping ( xVal, xPct, value ) {

		if ( value >= xVal.slice(-1)[0] ){
			return 100;
		}

		var j = getJ( value, xVal ), va, vb, pa, pb;

		va = xVal[j-1];
		vb = xVal[j];
		pa = xPct[j-1];
		pb = xPct[j];

		return pa + (toPercentage([va, vb], value) / subRangeRatio (pa, pb));
	}

	// (value) Input a percentage, find where it is on the specified range.
	function fromStepping ( xVal, xPct, value ) {

		// There is no range group that fits 100
		if ( value >= 100 ){
			return xVal.slice(-1)[0];
		}

		var j = getJ( value, xPct ), va, vb, pa, pb;

		va = xVal[j-1];
		vb = xVal[j];
		pa = xPct[j-1];
		pb = xPct[j];

		return isPercentage([va, vb], (value - pa) * subRangeRatio (pa, pb));
	}

	// (percentage) Get the step that applies at a certain value.
	function getStep ( xPct, xSteps, snap, value ) {

		if ( value === 100 ) {
			return value;
		}

		var j = getJ( value, xPct ), a, b;

		// If 'snap' is set, steps are used as fixed points on the slider.
		if ( snap ) {

			a = xPct[j-1];
			b = xPct[j];

			// Find the closest position, a or b.
			if ((value - a) > ((b-a)/2)){
				return b;
			}

			return a;
		}

		if ( !xSteps[j-1] ){
			return value;
		}

		return xPct[j-1] + closest(
			value - xPct[j-1],
			xSteps[j-1]
		);
	}


// Entry parsing

	function handleEntryPoint ( index, value, that ) {

		var percentage;

		// Wrap numerical input in an array.
		if ( typeof value === "number" ) {
			value = [value];
		}

		// Reject any invalid input, by testing whether value is an array.
		if ( Object.prototype.toString.call( value ) !== '[object Array]' ){
			throw new Error("noUiSlider: 'range' contains invalid value.");
		}

		// Covert min/max syntax to 0 and 100.
		if ( index === 'min' ) {
			percentage = 0;
		} else if ( index === 'max' ) {
			percentage = 100;
		} else {
			percentage = parseFloat( index );
		}

		// Check for correct input.
		if ( !isNumeric( percentage ) || !isNumeric( value[0] ) ) {
			throw new Error("noUiSlider: 'range' value isn't numeric.");
		}

		// Store values.
		that.xPct.push( percentage );
		that.xVal.push( value[0] );

		// NaN will evaluate to false too, but to keep
		// logging clear, set step explicitly. Make sure
		// not to override the 'step' setting with false.
		if ( !percentage ) {
			if ( !isNaN( value[1] ) ) {
				that.xSteps[0] = value[1];
			}
		} else {
			that.xSteps.push( isNaN(value[1]) ? false : value[1] );
		}
	}

	function handleStepPoint ( i, n, that ) {

		// Ignore 'false' stepping.
		if ( !n ) {
			return true;
		}

		// Factor to range ratio
		that.xSteps[i] = fromPercentage([
			 that.xVal[i]
			,that.xVal[i+1]
		], n) / subRangeRatio (
			that.xPct[i],
			that.xPct[i+1] );
	}


// Interface

	// The interface to Spectrum handles all direction-based
	// conversions, so the above values are unaware.

	function Spectrum ( entry, snap, direction, singleStep ) {

		this.xPct = [];
		this.xVal = [];
		this.xSteps = [ singleStep || false ];
		this.xNumSteps = [ false ];

		this.snap = snap;
		this.direction = direction;

		var index, ordered = [ /* [0, 'min'], [1, '50%'], [2, 'max'] */ ];

		// Map the object keys to an array.
		for ( index in entry ) {
			if ( entry.hasOwnProperty(index) ) {
				ordered.push([entry[index], index]);
			}
		}

		// Sort all entries by value (numeric sort).
		ordered.sort(function(a, b) { return a[0] - b[0]; });

		// Convert all entries to subranges.
		for ( index = 0; index < ordered.length; index++ ) {
			handleEntryPoint(ordered[index][1], ordered[index][0], this);
		}

		// Store the actual step values.
		// xSteps is sorted in the same order as xPct and xVal.
		this.xNumSteps = this.xSteps.slice(0);

		// Convert all numeric steps to the percentage of the subrange they represent.
		for ( index = 0; index < this.xNumSteps.length; index++ ) {
			handleStepPoint(index, this.xNumSteps[index], this);
		}
	}

	Spectrum.prototype.getMargin = function ( value ) {
		return this.xPct.length === 2 ? fromPercentage(this.xVal, value) : false;
	};

	Spectrum.prototype.toStepping = function ( value ) {

		value = toStepping( this.xVal, this.xPct, value );

		// Invert the value if this is a right-to-left slider.
		if ( this.direction ) {
			value = 100 - value;
		}

		return value;
	};

	Spectrum.prototype.fromStepping = function ( value ) {

		// Invert the value if this is a right-to-left slider.
		if ( this.direction ) {
			value = 100 - value;
		}

		return accurateNumber(fromStepping( this.xVal, this.xPct, value ));
	};

	Spectrum.prototype.getStep = function ( value ) {

		// Find the proper step for rtl sliders by search in inverse direction.
		// Fixes issue #262.
		if ( this.direction ) {
			value = 100 - value;
		}

		value = getStep(this.xPct, this.xSteps, this.snap, value );

		if ( this.direction ) {
			value = 100 - value;
		}

		return value;
	};

	Spectrum.prototype.getApplicableStep = function ( value ) {

		// If the value is 100%, return the negative step twice.
		var j = getJ(value, this.xPct), offset = value === 100 ? 2 : 1;
		return [this.xNumSteps[j-2], this.xVal[j-offset], this.xNumSteps[j-offset]];
	};

	// Outside testing
	Spectrum.prototype.convert = function ( value ) {
		return this.getStep(this.toStepping(value));
	};

/*	Every input option is tested and parsed. This'll prevent
	endless validation in internal methods. These tests are
	structured with an item for every option available. An
	option can be marked as required by setting the 'r' flag.
	The testing function is provided with three arguments:
		- The provided value for the option;
		- A reference to the options object;
		- The name for the option;

	The testing function returns false when an error is detected,
	or true when everything is OK. It can also modify the option
	object, to make sure all values can be correctly looped elsewhere. */

	var defaultFormatter = { 'to': function( value ){
		return value.toFixed(2);
	}, 'from': Number };

	function testStep ( parsed, entry ) {

		if ( !isNumeric( entry ) ) {
			throw new Error("noUiSlider: 'step' is not numeric.");
		}

		// The step option can still be used to set stepping
		// for linear sliders. Overwritten if set in 'range'.
		parsed.singleStep = entry;
	}

	function testRange ( parsed, entry ) {

		// Filter incorrect input.
		if ( typeof entry !== 'object' || Array.isArray(entry) ) {
			throw new Error("noUiSlider: 'range' is not an object.");
		}

		// Catch missing start or end.
		if ( entry.min === undefined || entry.max === undefined ) {
			throw new Error("noUiSlider: Missing 'min' or 'max' in 'range'.");
		}

		parsed.spectrum = new Spectrum(entry, parsed.snap, parsed.dir, parsed.singleStep);
	}

	function testStart ( parsed, entry ) {

		entry = asArray(entry);

		// Validate input. Values aren't tested, as the public .val method
		// will always provide a valid location.
		if ( !Array.isArray( entry ) || !entry.length || entry.length > 2 ) {
			throw new Error("noUiSlider: 'start' option is incorrect.");
		}

		// Store the number of handles.
		parsed.handles = entry.length;

		// When the slider is initialized, the .val method will
		// be called with the start options.
		parsed.start = entry;
	}

	function testSnap ( parsed, entry ) {

		// Enforce 100% stepping within subranges.
		parsed.snap = entry;

		if ( typeof entry !== 'boolean' ){
			throw new Error("noUiSlider: 'snap' option must be a boolean.");
		}
	}

	function testAnimate ( parsed, entry ) {

		// Enforce 100% stepping within subranges.
		parsed.animate = entry;

		if ( typeof entry !== 'boolean' ){
			throw new Error("noUiSlider: 'animate' option must be a boolean.");
		}
	}

	function testConnect ( parsed, entry ) {

		if ( entry === 'lower' && parsed.handles === 1 ) {
			parsed.connect = 1;
		} else if ( entry === 'upper' && parsed.handles === 1 ) {
			parsed.connect = 2;
		} else if ( entry === true && parsed.handles === 2 ) {
			parsed.connect = 3;
		} else if ( entry === false ) {
			parsed.connect = 0;
		} else {
			throw new Error("noUiSlider: 'connect' option doesn't match handle count.");
		}
	}

	function testOrientation ( parsed, entry ) {

		// Set orientation to an a numerical value for easy
		// array selection.
		switch ( entry ){
		  case 'horizontal':
			parsed.ort = 0;
			break;
		  case 'vertical':
			parsed.ort = 1;
			break;
		  default:
			throw new Error("noUiSlider: 'orientation' option is invalid.");
		}
	}

	function testMargin ( parsed, entry ) {

		if ( !isNumeric(entry) ){
			throw new Error("noUiSlider: 'margin' option must be numeric.");
		}

		parsed.margin = parsed.spectrum.getMargin(entry);

		if ( !parsed.margin ) {
			throw new Error("noUiSlider: 'margin' option is only supported on linear sliders.");
		}
	}

	function testLimit ( parsed, entry ) {

		if ( !isNumeric(entry) ){
			throw new Error("noUiSlider: 'limit' option must be numeric.");
		}

		parsed.limit = parsed.spectrum.getMargin(entry);

		if ( !parsed.limit ) {
			throw new Error("noUiSlider: 'limit' option is only supported on linear sliders.");
		}
	}

	function testDirection ( parsed, entry ) {

		// Set direction as a numerical value for easy parsing.
		// Invert connection for RTL sliders, so that the proper
		// handles get the connect/background classes.
		switch ( entry ) {
		  case 'ltr':
			parsed.dir = 0;
			break;
		  case 'rtl':
			parsed.dir = 1;
			parsed.connect = [0,2,1,3][parsed.connect];
			break;
		  default:
			throw new Error("noUiSlider: 'direction' option was not recognized.");
		}
	}

	function testBehaviour ( parsed, entry ) {

		// Make sure the input is a string.
		if ( typeof entry !== 'string' ) {
			throw new Error("noUiSlider: 'behaviour' must be a string containing options.");
		}

		// Check if the string contains any keywords.
		// None are required.
		var tap = entry.indexOf('tap') >= 0,
			drag = entry.indexOf('drag') >= 0,
			fixed = entry.indexOf('fixed') >= 0,
			snap = entry.indexOf('snap') >= 0;

		parsed.events = {
			tap: tap || snap,
			drag: drag,
			fixed: fixed,
			snap: snap
		};
	}

	function testFormat ( parsed, entry ) {

		parsed.format = entry;

		// Any object with a to and from method is supported.
		if ( typeof entry.to === 'function' && typeof entry.from === 'function' ) {
			return true;
		}

		throw new Error( "noUiSlider: 'format' requires 'to' and 'from' methods.");
	}

	// Test all developer settings and parse to assumption-safe values.
	function testOptions ( options ) {

		var parsed = {
			margin: 0,
			limit: 0,
			animate: true,
			format: defaultFormatter
		}, tests;

		// Tests are executed in the order they are presented here.
		tests = {
			'step': { r: false, t: testStep },
			'start': { r: true, t: testStart },
			'connect': { r: true, t: testConnect },
			'direction': { r: true, t: testDirection },
			'snap': { r: false, t: testSnap },
			'animate': { r: false, t: testAnimate },
			'range': { r: true, t: testRange },
			'orientation': { r: false, t: testOrientation },
			'margin': { r: false, t: testMargin },
			'limit': { r: false, t: testLimit },
			'behaviour': { r: true, t: testBehaviour },
			'format': { r: false, t: testFormat }
		};

		var defaults = {
			'connect': false,
			'direction': 'ltr',
			'behaviour': 'tap',
			'orientation': 'horizontal'
		};

		// Set defaults where applicable.
		Object.keys(defaults).forEach(function ( name ) {
			if ( options[name] === undefined ) {
				options[name] = defaults[name];
			}
		});

		// Run all options through a testing mechanism to ensure correct
		// input. It should be noted that options might get modified to
		// be handled properly. E.g. wrapping integers in arrays.
		Object.keys(tests).forEach(function( name ){

			var test = tests[name];

			// If the option isn't set, but it is required, throw an error.
			if ( options[name] === undefined ) {

				if ( test.r ) {
					throw new Error("noUiSlider: '" + name + "' is required.");
				}

				return true;
			}

			test.t( parsed, options[name] );
		});

		// Forward pips options
		parsed.pips = options.pips;

		// Pre-define the styles.
		parsed.style = parsed.ort ? 'top' : 'left';

		return parsed;
	}


	// Delimit proposed values for handle positions.
	function getPositions ( a, b, delimit ) {

		// Add movement to current position.
		var c = a + b[0], d = a + b[1];

		// Only alter the other position on drag,
		// not on standard sliding.
		if ( delimit ) {
			if ( c < 0 ) {
				d += Math.abs(c);
			}
			if ( d > 100 ) {
				c -= ( d - 100 );
			}

			// Limit values to 0 and 100.
			return [limit(c), limit(d)];
		}

		return [c,d];
	}

	// Provide a clean event with standardized offset values.
	function fixEvent ( e ) {

		// Prevent scrolling and panning on touch events, while
		// attempting to slide. The tap event also depends on this.
		e.preventDefault();

		// Filter the event to register the type, which can be
		// touch, mouse or pointer. Offset changes need to be
		// made on an event specific basis.
		var touch = e.type.indexOf('touch') === 0,
			mouse = e.type.indexOf('mouse') === 0,
			pointer = e.type.indexOf('pointer') === 0,
			x,y, event = e;

		// IE10 implemented pointer events with a prefix;
		if ( e.type.indexOf('MSPointer') === 0 ) {
			pointer = true;
		}

		if ( touch ) {
			// noUiSlider supports one movement at a time,
			// so we can select the first 'changedTouch'.
			x = e.changedTouches[0].pageX;
			y = e.changedTouches[0].pageY;
		}

		if ( mouse || pointer ) {
			x = e.clientX + window.pageXOffset;
			y = e.clientY + window.pageYOffset;
		}

		event.points = [x, y];
		event.cursor = mouse || pointer; // Fix #435

		return event;
	}

	// Append a handle to the base.
	function addHandle ( direction, index ) {

		var origin = document.createElement('div'),
			handle = document.createElement('div'),
			additions = [ '-lower', '-upper' ];

		if ( direction ) {
			additions.reverse();
		}

		addClass(handle, Classes[3]);
		addClass(handle, Classes[3] + additions[index]);

		addClass(origin, Classes[2]);
		origin.appendChild(handle);

		return origin;
	}

	// Add the proper connection classes.
	function addConnection ( connect, target, handles ) {

		// Apply the required connection classes to the elements
		// that need them. Some classes are made up for several
		// segments listed in the class list, to allow easy
		// renaming and provide a minor compression benefit.
		switch ( connect ) {
			case 1:	addClass(target, Classes[7]);
					addClass(handles[0], Classes[6]);
					break;
			case 3: addClass(handles[1], Classes[6]);
					/* falls through */
			case 2: addClass(handles[0], Classes[7]);
					/* falls through */
			case 0: addClass(target, Classes[6]);
					break;
		}
	}

	// Add handles to the slider base.
	function addHandles ( nrHandles, direction, base ) {

		var index, handles = [];

		// Append handles.
		for ( index = 0; index < nrHandles; index += 1 ) {

			// Keep a list of all added handles.
			handles.push( base.appendChild(addHandle( direction, index )) );
		}

		return handles;
	}

	// Initialize a single slider.
	function addSlider ( direction, orientation, target ) {

		// Apply classes and data to the target.
		addClass(target, Classes[0]);
		addClass(target, Classes[8 + direction]);
		addClass(target, Classes[4 + orientation]);

		var div = document.createElement('div');
		addClass(div, Classes[1]);
		target.appendChild(div);
		return div;
	}


function closure ( target, options ){

	// All variables local to 'closure' are prefixed with 'scope_'
	var scope_Target = target,
		scope_Locations = [-1, -1],
		scope_Base,
		scope_Handles,
		scope_Spectrum = options.spectrum,
		scope_Values = [],
		scope_Events = {};


	function getGroup ( mode, values, stepped ) {

		// Use the range.
		if ( mode === 'range' || mode === 'steps' ) {
			return scope_Spectrum.xVal;
		}

		if ( mode === 'count' ) {

			// Divide 0 - 100 in 'count' parts.
			var spread = ( 100 / (values-1) ), v, i = 0;
			values = [];

			// List these parts and have them handled as 'positions'.
			while ((v=i++*spread) <= 100 ) {
				values.push(v);
			}

			mode = 'positions';
		}

		if ( mode === 'positions' ) {

			// Map all percentages to on-range values.
			return values.map(function( value ){
				return scope_Spectrum.fromStepping( stepped ? scope_Spectrum.getStep( value ) : value );
			});
		}

		if ( mode === 'values' ) {

			// If the value must be stepped, it needs to be converted to a percentage first.
			if ( stepped ) {

				return values.map(function( value ){

					// Convert to percentage, apply step, return to value.
					return scope_Spectrum.fromStepping( scope_Spectrum.getStep( scope_Spectrum.toStepping( value ) ) );
				});

			}

			// Otherwise, we can simply use the values.
			return values;
		}
	}

	function generateSpread ( density, mode, group ) {

		var originalSpectrumDirection = scope_Spectrum.direction,
			indexes = {},
			firstInRange = scope_Spectrum.xVal[0],
			lastInRange = scope_Spectrum.xVal[scope_Spectrum.xVal.length-1],
			ignoreFirst = false,
			ignoreLast = false,
			prevPct = 0;

		// This function loops the spectrum in an ltr linear fashion,
		// while the toStepping method is direction aware. Trick it into
		// believing it is ltr.
		scope_Spectrum.direction = 0;

		// Create a copy of the group, sort it and filter away all duplicates.
		group = unique(group.slice().sort(function(a, b){ return a - b; }));

		// Make sure the range starts with the first element.
		if ( group[0] !== firstInRange ) {
			group.unshift(firstInRange);
			ignoreFirst = true;
		}

		// Likewise for the last one.
		if ( group[group.length - 1] !== lastInRange ) {
			group.push(lastInRange);
			ignoreLast = true;
		}

		group.forEach(function ( current, index ) {

			// Get the current step and the lower + upper positions.
			var step, i, q,
				low = current,
				high = group[index+1],
				newPct, pctDifference, pctPos, type,
				steps, realSteps, stepsize;

			// When using 'steps' mode, use the provided steps.
			// Otherwise, we'll step on to the next subrange.
			if ( mode === 'steps' ) {
				step = scope_Spectrum.xNumSteps[ index ];
			}

			// Default to a 'full' step.
			if ( !step ) {
				step = high-low;
			}

			// Low can be 0, so test for false. If high is undefined,
			// we are at the last subrange. Index 0 is already handled.
			if ( low === false || high === undefined ) {
				return;
			}

			// Find all steps in the subrange.
			for ( i = low; i <= high; i += step ) {

				// Get the percentage value for the current step,
				// calculate the size for the subrange.
				newPct = scope_Spectrum.toStepping( i );
				pctDifference = newPct - prevPct;

				steps = pctDifference / density;
				realSteps = Math.round(steps);

				// This ratio represents the ammount of percentage-space a point indicates.
				// For a density 1 the points/percentage = 1. For density 2, that percentage needs to be re-devided.
				// Round the percentage offset to an even number, then divide by two
				// to spread the offset on both sides of the range.
				stepsize = pctDifference/realSteps;

				// Divide all points evenly, adding the correct number to this subrange.
				// Run up to <= so that 100% gets a point, event if ignoreLast is set.
				for ( q = 1; q <= realSteps; q += 1 ) {

					// The ratio between the rounded value and the actual size might be ~1% off.
					// Correct the percentage offset by the number of points
					// per subrange. density = 1 will result in 100 points on the
					// full range, 2 for 50, 4 for 25, etc.
					pctPos = prevPct + ( q * stepsize );
					indexes[pctPos.toFixed(5)] = ['x', 0];
				}

				// Determine the point type.
				type = (group.indexOf(i) > -1) ? 1 : ( mode === 'steps' ? 2 : 0 );

				// Enforce the 'ignoreFirst' option by overwriting the type for 0.
				if ( !index && ignoreFirst ) {
					type = 0;
				}

				if ( !(i === high && ignoreLast)) {
					// Mark the 'type' of this point. 0 = plain, 1 = real value, 2 = step value.
					indexes[newPct.toFixed(5)] = [i, type];
				}

				// Update the percentage count.
				prevPct = newPct;
			}
		});

		// Reset the spectrum.
		scope_Spectrum.direction = originalSpectrumDirection;

		return indexes;
	}

	function addMarking ( spread, filterFunc, formatter ) {

		var style = ['horizontal', 'vertical'][options.ort],
			element = document.createElement('div');

		addClass(element, 'noUi-pips');
		addClass(element, 'noUi-pips-' + style);

		function getSize( type ){
			return [ '-normal', '-large', '-sub' ][type];
		}

		function getTags( offset, source, values ) {
			return 'class="' + source + ' ' +
				source + '-' + style + ' ' +
				source + getSize(values[1]) +
				'" style="' + options.style + ': ' + offset + '%"';
		}

		function addSpread ( offset, values ){

			if ( scope_Spectrum.direction ) {
				offset = 100 - offset;
			}

			// Apply the filter function, if it is set.
			values[1] = (values[1] && filterFunc) ? filterFunc(values[0], values[1]) : values[1];

			// Add a marker for every point
			element.innerHTML += '<div ' + getTags(offset, 'noUi-marker', values) + '></div>';

			// Values are only appended for points marked '1' or '2'.
			if ( values[1] ) {
				element.innerHTML += '<div '+getTags(offset, 'noUi-value', values)+'>' + formatter.to(values[0]) + '</div>';
			}
		}

		// Append all points.
		Object.keys(spread).forEach(function(a){
			addSpread(a, spread[a]);
		});

		return element;
	}

	function pips ( grid ) {

	var mode = grid.mode,
		density = grid.density || 1,
		filter = grid.filter || false,
		values = grid.values || false,
		stepped = grid.stepped || false,
		group = getGroup( mode, values, stepped ),
		spread = generateSpread( density, mode, group ),
		format = grid.format || {
			to: Math.round
		};

		return scope_Target.appendChild(addMarking(
			spread,
			filter,
			format
		));
	}


	// Shorthand for base dimensions.
	function baseSize ( ) {
		return scope_Base['offset' + ['Width', 'Height'][options.ort]];
	}

	// External event handling
	function fireEvent ( event, handleNumber ) {

		if ( handleNumber !== undefined ) {
			handleNumber = Math.abs(handleNumber - options.dir);
		}

		Object.keys(scope_Events).forEach(function( targetEvent ) {

			var eventType = targetEvent.split('.')[0];

			if ( event === eventType ) {
				scope_Events[targetEvent].forEach(function( callback ) {
					// .reverse is in place
					// Return values as array, so arg_1[arg_2] is always valid.
					callback( asArray(valueGet()), handleNumber, inSliderOrder(Array.prototype.slice.call(scope_Values)) );
				});
			}
		});
	}

	// Returns the input array, respecting the slider direction configuration.
	function inSliderOrder ( values ) {

		// If only one handle is used, return a single value.
		if ( values.length === 1 ){
			return values[0];
		}

		if ( options.dir ) {
			return values.reverse();
		}

		return values;
	}


	// Handler for attaching events trough a proxy.
	function attach ( events, element, callback, data ) {

		// This function can be used to 'filter' events to the slider.
		// element is a node, not a nodeList

		var method = function ( e ){

			if ( scope_Target.hasAttribute('disabled') ) {
				return false;
			}

			// Stop if an active 'tap' transition is taking place.
			if ( hasClass(scope_Target, Classes[14]) ) {
				return false;
			}

			e = fixEvent(e);

			// Ignore right or middle clicks on start #454
			if ( events === actions.start && e.buttons !== undefined && e.buttons > 1 ) {
				return false;
			}

			e.calcPoint = e.points[ options.ort ];

			// Call the event handler with the event [ and additional data ].
			callback ( e, data );

		}, methods = [];

		// Bind a closure on the target for every event type.
		events.split(' ').forEach(function( eventName ){
			element.addEventListener(eventName, method, false);
			methods.push([eventName, method]);
		});

		return methods;
	}

	// Handle movement on document for handle and range drag.
	function move ( event, data ) {

		var handles = data.handles || scope_Handles, positions, state = false,
			proposal = ((event.calcPoint - data.start) * 100) / baseSize(),
			handleNumber = handles[0] === scope_Handles[0] ? 0 : 1, i;

		// Calculate relative positions for the handles.
		positions = getPositions( proposal, data.positions, handles.length > 1);

		state = setHandle ( handles[0], positions[handleNumber], handles.length === 1 );

		if ( handles.length > 1 ) {

			state = setHandle ( handles[1], positions[handleNumber?0:1], false ) || state;

			if ( state ) {
				// fire for both handles
				for ( i = 0; i < data.handles.length; i++ ) {
					fireEvent('slide', i);
				}
			}
		} else if ( state ) {
			// Fire for a single handle
			fireEvent('slide', handleNumber);
		}
	}

	// Unbind move events on document, call callbacks.
	function end ( event, data ) {

		// The handle is no longer active, so remove the class.
		var active = scope_Base.getElementsByClassName(Classes[15]),
			handleNumber = data.handles[0] === scope_Handles[0] ? 0 : 1;

		if ( active.length ) {
			removeClass(active[0], Classes[15]);
		}

		// Remove cursor styles and text-selection events bound to the body.
		if ( event.cursor ) {
			document.body.style.cursor = '';
			document.body.removeEventListener('selectstart', document.body.noUiListener);
		}

		var d = document.documentElement;

		// Unbind the move and end events, which are added on 'start'.
		d.noUiListeners.forEach(function( c ) {
			d.removeEventListener(c[0], c[1]);
		});

		// Remove dragging class.
		removeClass(scope_Target, Classes[12]);

		// Fire the change and set events.
		fireEvent('set', handleNumber);
		fireEvent('change', handleNumber);
	}

	// Bind move events on document.
	function start ( event, data ) {

		var d = document.documentElement;

		// Mark the handle as 'active' so it can be styled.
		if ( data.handles.length === 1 ) {
			addClass(data.handles[0].children[0], Classes[15]);

			// Support 'disabled' handles
			if ( data.handles[0].hasAttribute('disabled') ) {
				return false;
			}
		}

		// A drag should never propagate up to the 'tap' event.
		event.stopPropagation();

		// Attach the move and end events.
		var moveEvent = attach(actions.move, d, move, {
			start: event.calcPoint,
			handles: data.handles,
			positions: [
				scope_Locations[0],
				scope_Locations[scope_Handles.length - 1]
			]
		}), endEvent = attach(actions.end, d, end, {
			handles: data.handles
		});

		d.noUiListeners = moveEvent.concat(endEvent);

		// Text selection isn't an issue on touch devices,
		// so adding cursor styles can be skipped.
		if ( event.cursor ) {

			// Prevent the 'I' cursor and extend the range-drag cursor.
			document.body.style.cursor = getComputedStyle(event.target).cursor;

			// Mark the target with a dragging state.
			if ( scope_Handles.length > 1 ) {
				addClass(scope_Target, Classes[12]);
			}

			var f = function(){
				return false;
			};

			document.body.noUiListener = f;

			// Prevent text selection when dragging the handles.
			document.body.addEventListener('selectstart', f, false);
		}
	}

	// Move closest handle to tapped location.
	function tap ( event ) {

		var location = event.calcPoint, total = 0, handleNumber, to;

		// The tap event shouldn't propagate up and cause 'edge' to run.
		event.stopPropagation();

		// Add up the handle offsets.
		scope_Handles.forEach(function(a){
			total += offset(a)[ options.style ];
		});

		// Find the handle closest to the tapped position.
		handleNumber = ( location < total/2 || scope_Handles.length === 1 ) ? 0 : 1;

		location -= offset(scope_Base)[ options.style ];

		// Calculate the new position.
		to = ( location * 100 ) / baseSize();

		if ( !options.events.snap ) {
			// Flag the slider as it is now in a transitional state.
			// Transition takes 300 ms, so re-enable the slider afterwards.
			addClassFor( scope_Target, Classes[14], 300 );
		}

		// Support 'disabled' handles
		if ( scope_Handles[handleNumber].hasAttribute('disabled') ) {
			return false;
		}

		// Find the closest handle and calculate the tapped point.
		// The set handle to the new position.
		setHandle( scope_Handles[handleNumber], to );

		fireEvent('slide', handleNumber);
		fireEvent('set', handleNumber);
		fireEvent('change', handleNumber);

		if ( options.events.snap ) {
			start(event, { handles: [scope_Handles[total]] });
		}
	}

	// Attach events to several slider parts.
	function events ( behaviour ) {

		var i, drag;

		// Attach the standard drag event to the handles.
		if ( !behaviour.fixed ) {

			for ( i = 0; i < scope_Handles.length; i += 1 ) {

				// These events are only bound to the visual handle
				// element, not the 'real' origin element.
				attach ( actions.start, scope_Handles[i].children[0], start, {
					handles: [ scope_Handles[i] ]
				});
			}
		}

		// Attach the tap event to the slider base.
		if ( behaviour.tap ) {

			attach ( actions.start, scope_Base, tap, {
				handles: scope_Handles
			});
		}

		// Make the range dragable.
		if ( behaviour.drag ){

			drag = [scope_Base.getElementsByClassName( Classes[7] )[0]];
			addClass(drag[0], Classes[10]);

			// When the range is fixed, the entire range can
			// be dragged by the handles. The handle in the first
			// origin will propagate the start event upward,
			// but it needs to be bound manually on the other.
			if ( behaviour.fixed ) {
				drag.push(scope_Handles[(drag[0] === scope_Handles[0] ? 1 : 0)].children[0]);
			}

			drag.forEach(function( element ) {
				attach ( actions.start, element, start, {
					handles: scope_Handles
				});
			});
		}
	}


	// Test suggested values and apply margin, step.
	function setHandle ( handle, to, noLimitOption ) {

		var trigger = handle !== scope_Handles[0] ? 1 : 0,
			lowerMargin = scope_Locations[0] + options.margin,
			upperMargin = scope_Locations[1] - options.margin,
			lowerLimit = scope_Locations[0] + options.limit,
			upperLimit = scope_Locations[1] - options.limit;

		// For sliders with multiple handles,
		// limit movement to the other handle.
		// Apply the margin option by adding it to the handle positions.
		if ( scope_Handles.length > 1 ) {
			to = trigger ? Math.max( to, lowerMargin ) : Math.min( to, upperMargin );
		}

		// The limit option has the opposite effect, limiting handles to a
		// maximum distance from another. Limit must be > 0, as otherwise
		// handles would be unmoveable. 'noLimitOption' is set to 'false'
		// for the .val() method, except for pass 4/4.
		if ( noLimitOption !== false && options.limit && scope_Handles.length > 1 ) {
			to = trigger ? Math.min ( to, lowerLimit ) : Math.max( to, upperLimit );
		}

		// Handle the step option.
		to = scope_Spectrum.getStep( to );

		// Limit to 0/100 for .val input, trim anything beyond 7 digits, as
		// JavaScript has some issues in its floating point implementation.
		to = limit(parseFloat(to.toFixed(7)));

		// Return false if handle can't move.
		if ( to === scope_Locations[trigger] ) {
			return false;
		}

		// Set the handle to the new position.
		handle.style[options.style] = to + '%';

		// Force proper handle stacking
		if ( !handle.previousSibling ) {
			removeClass(handle, Classes[17]);
			if ( to > 50 ) {
				addClass(handle, Classes[17]);
			}
		}

		// Update locations.
		scope_Locations[trigger] = to;

		// Convert the value to the slider stepping/range.
		scope_Values[trigger] = scope_Spectrum.fromStepping( to );

		fireEvent('update', trigger);

		return true;
	}

	// Loop values from value method and apply them.
	function setValues ( count, values ) {

		var i, trigger, to;

		// With the limit option, we'll need another limiting pass.
		if ( options.limit ) {
			count += 1;
		}

		// If there are multiple handles to be set run the setting
		// mechanism twice for the first handle, to make sure it
		// can be bounced of the second one properly.
		for ( i = 0; i < count; i += 1 ) {

			trigger = i%2;

			// Get the current argument from the array.
			to = values[trigger];

			// Setting with null indicates an 'ignore'.
			// Inputting 'false' is invalid.
			if ( to !== null && to !== false ) {

				// If a formatted number was passed, attemt to decode it.
				if ( typeof to === 'number' ) {
					to = String(to);
				}

				to = options.format.from( to );

				// Request an update for all links if the value was invalid.
				// Do so too if setting the handle fails.
				if ( to === false || isNaN(to) || setHandle( scope_Handles[trigger], scope_Spectrum.toStepping( to ), i === (3 - options.dir) ) === false ) {
					fireEvent('update', trigger);
				}
			}
		}
	}

	// Set the slider value.
	function valueSet ( input ) {

		var count, values = asArray( input ), i;

		// The RTL settings is implemented by reversing the front-end,
		// internal mechanisms are the same.
		if ( options.dir && options.handles > 1 ) {
			values.reverse();
		}

		// Animation is optional.
		// Make sure the initial values where set before using animated placement.
		if ( options.animate && scope_Locations[0] !== -1 ) {
			addClassFor( scope_Target, Classes[14], 300 );
		}

		// Determine how often to set the handles.
		count = scope_Handles.length > 1 ? 3 : 1;

		if ( values.length === 1 ) {
			count = 1;
		}

		setValues ( count, values );

		// Fire the 'set' event for both handles.
		for ( i = 0; i < scope_Handles.length; i++ ) {
			fireEvent('set', i);
		}
	}

	// Get the slider value.
	function valueGet ( ) {

		var i, retour = [];

		// Get the value from all handles.
		for ( i = 0; i < options.handles; i += 1 ){
			retour[i] = options.format.to( scope_Values[i] );
		}

		return inSliderOrder( retour );
	}

	// Removes classes from the root and empties it.
	function destroy ( ) {
		Classes.forEach(function(cls){
			if ( !cls ) { return; } // Ignore empty classes
			removeClass(scope_Target, cls);
		});
		scope_Target.innerHTML = '';
		delete scope_Target.noUiSlider;
	}

	// Get the current step size for the slider.
	function getCurrentStep ( ) {

		// Check all locations, map them to their stepping point.
		// Get the step point, then find it in the input list.
		var retour = scope_Locations.map(function( location, index ){

			var step = scope_Spectrum.getApplicableStep( location ),

				// As per #391, the comparison for the decrement step can have some rounding issues.
				// Round the value to the precision used in the step.
				stepDecimals = countDecimals(String(step[2])),

				// Get the current numeric value
				value = scope_Values[index],

				// To move the slider 'one step up', the current step value needs to be added.
				// Use null if we are at the maximum slider value.
				increment = location === 100 ? null : step[2],

				// Going 'one step down' might put the slider in a different sub-range, so we
				// need to switch between the current or the previous step.
				prev = Number((value - step[2]).toFixed(stepDecimals)),

				// If the value fits the step, return the current step value. Otherwise, use the
				// previous step. Return null if the slider is at its minimum value.
				decrement = location === 0 ? null : (prev >= step[1]) ? step[2] : (step[0] || false);

			return [decrement, increment];
		});

		// Return values in the proper order.
		return inSliderOrder( retour );
	}

	// Attach an event to this slider, possibly including a namespace
	function bindEvent ( namespacedEvent, callback ) {
		scope_Events[namespacedEvent] = scope_Events[namespacedEvent] || [];
		scope_Events[namespacedEvent].push(callback);

		// If the event bound is 'update,' fire it immediately for all handles.
		if ( namespacedEvent.split('.')[0] === 'update' ) {
			scope_Handles.forEach(function(a, index){
				fireEvent('update', index);
			});
		}
	}

	// Undo attachment of event
	function removeEvent ( namespacedEvent ) {

		var event = namespacedEvent.split('.')[0],
			namespace = namespacedEvent.substring(event.length);

		Object.keys(scope_Events).forEach(function( bind ){

			var tEvent = bind.split('.')[0],
				tNamespace = bind.substring(tEvent.length);

			if ( (!event || event === tEvent) && (!namespace || namespace === tNamespace) ) {
				delete scope_Events[bind];
			}
		});
	}


	// Throw an error if the slider was already initialized.
	if ( scope_Target.noUiSlider ) {
		throw new Error('Slider was already initialized.');
	}


	// Create the base element, initialise HTML and set classes.
	// Add handles and links.
	scope_Base = addSlider( options.dir, options.ort, scope_Target );
	scope_Handles = addHandles( options.handles, options.dir, scope_Base );

	// Set the connect classes.
	addConnection ( options.connect, scope_Target, scope_Handles );

	// Attach user events.
	events( options.events );

	if ( options.pips ) {
		pips(options.pips);
	}

	return {
		destroy: destroy,
		steps: getCurrentStep,
		on: bindEvent,
		off: removeEvent,
		get: valueGet,
		set: valueSet
	};

}


	// Run the standard initializer
	function initialize ( target, originalOptions ) {

		if ( !target.nodeName ) {
			throw new Error('noUiSlider.create requires a single element.');
		}

		// Test the options and create the slider environment;
		var options = testOptions( originalOptions, target ),
			slider = closure( target, options );

		// Use the public value method to set the start values.
		slider.set(options.start);

		target.noUiSlider = slider;

		if (originalOptions.tooltips === true || originalOptions.tooltips === undefined) {
			// Tooltips
			var tipHandles = target.getElementsByClassName('noUi-handle'),
				tooltips = [];

			// Add divs to the slider handles.
			for ( var i = 0; i < tipHandles.length; i++ ){
				tooltips[i] = document.createElement('div');
				tipHandles[i].appendChild(tooltips[i]);
			// Add a class for styling
			tooltips[i].className += 'range-label';
			// Add additional markup
			tooltips[i].innerHTML = '<span></span>';
			// Replace the tooltip reference with the span we just added
			tooltips[i] = tooltips[i].getElementsByTagName('span')[0];
			}


			// When the slider changes, write the value to the tooltips.
			target.noUiSlider.on('update', function( values, handle ){

				tooltips[handle].innerHTML = values[handle];
			});
		}
	}

	// Use an object instead of a function for future expansibility;
	return {
		create: initialize
	};

}));
(function ($) {
	$.fn.tvd_nouislider = function (opts) {
		opts = opts || {};
		if (!opts.range) {
			opts.range = {
				min: this.data('min') || 0,
				max: this.data('max') || 100
			};
		}
		opts = $.extend(true, {
			start: 0,
			step: 1,
			connect: 'lower',
			range: {
				min: 0,
				max: 100
			},
			update: function () {
			},
			format: {
				to: function ( value ) {
					return Math.round(value);
				},
				from: function ( value ) {
					return Math.round(value);
				}
			},
			pips: {
				mode: 'range',
				density: 3
			}
		}, opts);

		if (!opts.input && this.data('connect-to')) {
			opts.input = $(this.data('connect-to'));
		}

		var element = this[0];
		if (typeof opts === 'string' && opts === 'destroy') {
			this[0].noUiSlider.destroy();
			delete this[0].noUiSlider;
		}
		if (element.noUiSlider) {
			return this;
		}
		noUiSlider.create(element, opts);
		if (opts.input && opts.input.length) {
			opts.input.on('change', function () {
				var _value = parseFloat(this.value);
				element.noUiSlider.set(_value);
			});
			element.noUiSlider.on('update', function (_value) {
				var _val = Math.round(parseInt(_value[0]));
				opts.input.val(_val);
			});
		}

		if (this.data('value')) {
			element.noUiSlider.set(this.data('value'));
		}
	};
})(jQuery);;/*!
 * pickadate.js v3.5.6, 2015/04/20
 * By Amsul, http://amsul.ca
 * Hosted on http://amsul.github.io/pickadate.js
 * Licensed under MIT
 */

(function ( factory ) {

	// AMD.
	if ( typeof define == 'function' && define.amd ) {
		define( 'picker', ['jquery'], factory )
	}// Node.js/browserify.
	else if ( typeof exports == 'object' ) {
		module.exports = factory( require( 'jquery' ) )
	}// Browser globals.
	else {
		this.Picker = factory( jQuery )
	}

}( function ( $ ) {

	var $window = $( window )
	var $document = $( document )
	var $html = $( document.documentElement )
	var supportsTransitions = document.documentElement.style.transition != null


	/**
	 * The picker constructor that creates a blank picker.
	 */
	function PickerConstructor( ELEMENT, NAME, COMPONENT, OPTIONS ) {

		// If there’s no element, return the picker constructor.
		if ( ! ELEMENT ) {
			return PickerConstructor
		}


		var
			IS_DEFAULT_THEME = false,


		// The state of the picker.
			STATE = {
				id: ELEMENT.id || 'P' + Math.abs( ~ ~ (
					Math.random() * new Date()
				) )
			},


		// Merge the defaults and options passed.
			SETTINGS = COMPONENT ? $.extend( true, {}, COMPONENT.defaults, OPTIONS ) : OPTIONS || {},


		// Merge the default classes with the settings classes.
			CLASSES = $.extend( {}, PickerConstructor.klasses(), SETTINGS.klass ),


		// The element node wrapper into a jQuery object.
			$ELEMENT = $( ELEMENT ),


		// Pseudo picker constructor.
			PickerInstance = function () {
				return this.start()
			},


		// The picker prototype.
			P = PickerInstance.prototype = {

				constructor: PickerInstance,

				$node: $ELEMENT,


				/**
				 * Initialize everything
				 */
				start: function () {

					// If it’s already started, do nothing.
					if ( STATE && STATE.start ) {
						return P
					}


					// Update the picker states.
					STATE.methods = {}
					STATE.start = true
					STATE.open = false
					STATE.type = ELEMENT.type


					// Confirm focus state, convert into text input to remove UA stylings,
					// and set as readonly to prevent keyboard popup.
					ELEMENT.autofocus = ELEMENT == getActiveElement()
					ELEMENT.readOnly = ! SETTINGS.editable
					ELEMENT.id = ELEMENT.id || STATE.id
					if ( ELEMENT.type != 'text' ) {
						ELEMENT.type = 'text'
					}


					// Create a new picker component with the settings.
					P.component = new COMPONENT( P, SETTINGS )


					// Create the picker root and then prepare it.
					P.$root = $( '<div class="' + CLASSES.picker + '" id="' + ELEMENT.id + '_root" />' )
					prepareElementRoot()


					// Create the picker holder and then prepare it.
					P.$holder = $( createWrappedComponent() ).appendTo( P.$root )
					prepareElementHolder()


					// If there’s a format for the hidden input element, create the element.
					if ( SETTINGS.formatSubmit ) {
						prepareElementHidden()
					}


					// Prepare the input element.
					prepareElement()


					// Insert the hidden input as specified in the settings.
					if ( SETTINGS.containerHidden ) {
						$( SETTINGS.containerHidden ).append( P._hidden )
					} else {
						$ELEMENT.after( P._hidden )
					}


					// Insert the root as specified in the settings.
					if ( SETTINGS.container ) {
						$( SETTINGS.container ).append( P.$root )
					} else {
						if ( $ELEMENT.next().is( 'label' ) ) {
							$ELEMENT.next().after( P.$root );
						} else {
							$ELEMENT.after( P.$root )
						}
					}


					// Bind the default component and settings events.
					P.on( {
						start: P.component.onStart,
						render: P.component.onRender,
						stop: P.component.onStop,
						open: P.component.onOpen,
						close: P.component.onClose,
						set: P.component.onSet
					} ).on( {
						start: SETTINGS.onStart,
						render: SETTINGS.onRender,
						stop: SETTINGS.onStop,
						open: SETTINGS.onOpen,
						close: SETTINGS.onClose,
						set: SETTINGS.onSet
					} )


					// Once we’re all set, check the theme in use.
					IS_DEFAULT_THEME = isUsingDefaultTheme( P.$holder[0] )


					// If the element has autofocus, open the picker.
					if ( ELEMENT.autofocus ) {
						P.open()
					}


					// Trigger queued the “start” and “render” events.
					return P.trigger( 'start' ).trigger( 'render' )
				}, //start


				/**
				 * Render a new picker
				 */
				render: function ( entireComponent ) {

					// Insert a new component holder in the root or box.
					if ( entireComponent ) {
						P.$holder = $( createWrappedComponent() )
						prepareElementHolder()
						P.$root.html( P.$holder )
					}
					else {
						P.$root.find( '.' + CLASSES.box ).html( P.component.nodes( STATE.open ) )
					}

					// Trigger the queued “render” events.
					return P.trigger( 'render' )
				}, //render


				/**
				 * Destroy everything
				 */
				stop: function () {

					// If it’s already stopped, do nothing.
					if ( ! STATE.start ) {
						return P
					}

					// Then close the picker.
					P.close()

					// Remove the hidden field.
					if ( P._hidden ) {
						P._hidden.parentNode.removeChild( P._hidden )
					}

					// Remove the root.
					P.$root.remove()

					// Remove the input class, remove the stored data, and unbind
					// the events (after a tick for IE - see `P.close`).
					$ELEMENT.removeClass( CLASSES.input ).removeData( NAME )
					setTimeout( function () {
						$ELEMENT.off( '.' + STATE.id )
					}, 0 )

					// Restore the element state
					ELEMENT.type = STATE.type
					ELEMENT.readOnly = false

					// Trigger the queued “stop” events.
					P.trigger( 'stop' )

					// Reset the picker states.
					STATE.methods = {}
					STATE.start = false

					return P
				}, //stop


				/**
				 * Open up the picker
				 */
				open: function ( dontGiveFocus ) {

					// If it’s already open, do nothing.
					if ( STATE.open ) {
						return P
					}

					// Add the “active” class.
					$ELEMENT.addClass( CLASSES.active )
					aria( ELEMENT, 'expanded', true )

					// * A Firefox bug, when `html` has `overflow:hidden`, results in
					//   killing transitions :(. So add the “opened” state on the next tick.
					//   Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=625289
					setTimeout( function () {

						// Add the “opened” class to the picker root.
						P.$root.addClass( CLASSES.opened )
						aria( P.$root[0], 'hidden', false )

					}, 0 )

					// If we have to give focus, bind the element and doc events.
					if ( dontGiveFocus !== false ) {

						// Set it as open.
						STATE.open = true

						// Prevent the page from scrolling.
						if ( IS_DEFAULT_THEME ) {
							$html.css( 'overflow', 'hidden' ).css( 'padding-right', '+=' + getScrollbarWidth() )
						}

						// Pass focus to the root element’s jQuery object.
						focusPickerOnceOpened()

						// Bind the document events.
						$document.on( 'click.' + STATE.id + ' focusin.' + STATE.id, function ( event ) {

							var target = event.target

							// If the target of the event is not the element, close the picker picker.
							// * Don’t worry about clicks or focusins on the root because those don’t bubble up.
							//   Also, for Firefox, a click on an `option` element bubbles up directly
							//   to the doc. So make sure the target wasn't the doc.
							// * In Firefox stopPropagation() doesn’t prevent right-click events from bubbling,
							//   which causes the picker to unexpectedly close when right-clicking it. So make
							//   sure the event wasn’t a right-click.
							if ( target != ELEMENT && target != document && event.which != 3 ) {

								// If the target was the holder that covers the screen,
								// keep the element focused to maintain tabindex.
								P.close( target === P.$holder[0] )
							}

						} ).on( 'keydown.' + STATE.id, function ( event ) {

							var
							// Get the keycode.
								keycode = event.keyCode,

							// Translate that to a selection change.
								keycodeToMove = P.component.key[keycode],

							// Grab the target.
								target = event.target


							// On escape, close the picker and give focus.
							if ( keycode == 27 ) {
								P.close( true )
							}


							// Check if there is a key movement or “enter” keypress on the element.
							else if ( target == P.$holder[0] && (
									keycodeToMove || keycode == 13
								) ) {

								// Prevent the default action to stop page movement.
								event.preventDefault()

								// Trigger the key movement action.
								if ( keycodeToMove ) {
									PickerConstructor._.trigger( P.component.key.go, P, [PickerConstructor._.trigger( keycodeToMove )] )
								}

								// On “enter”, if the highlighted item isn’t disabled, set the value and close.
								else if ( ! P.$root.find( '.' + CLASSES.highlighted ).hasClass( CLASSES.disabled ) ) {
									P.set( 'select', P.component.item.highlight )
									if ( SETTINGS.closeOnSelect ) {
										P.close( true )
									}
								}
							}


							// If the target is within the root and “enter” is pressed,
							// prevent the default action and trigger a click on the target instead.
							else if ( $.contains( P.$root[0], target ) && keycode == 13 ) {
								event.preventDefault()
								target.click()
							}
						} )
					}

					// Trigger the queued “open” events.
					/**
					 * also trigger the 'tvdpickeropen' event after the css animation finishes
					 */
					setTimeout( function () {
						P.$node.trigger( 'tvdpickeropen', [P] );
					}, 50 );
					return P.trigger( 'open' )
				}, //open


				/**
				 * Close the picker
				 */
				close: function ( giveFocus ) {

					// If we need to give focus, do it before changing states.
					if ( giveFocus ) {
						if ( SETTINGS.editable ) {
							ELEMENT.focus()
						}
						else {
							// ....ah yes! It would’ve been incomplete without a crazy workaround for IE :|
							// The focus is triggered *after* the close has completed - causing it
							// to open again. So unbind and rebind the event at the next tick.
							P.$holder.off( 'focus.toOpen' ).focus()
							setTimeout( function () {
								P.$holder.on( 'focus.toOpen', handleFocusToOpenEvent )
							}, 0 )
						}
					}

					// Remove the “active” class.
					$ELEMENT.removeClass( CLASSES.active )
					aria( ELEMENT, 'expanded', false )

					// * A Firefox bug, when `html` has `overflow:hidden`, results in
					//   killing transitions :(. So remove the “opened” state on the next tick.
					//   Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=625289
					setTimeout( function () {

						// Remove the “opened” and “focused” class from the picker root.
						P.$root.removeClass( CLASSES.opened + ' ' + CLASSES.focused )
						aria( P.$root[0], 'hidden', true )

					}, 0 )

					// If it’s already closed, do nothing more.
					if ( ! STATE.open ) {
						return P
					}

					// Set it as closed.
					STATE.open = false

					// Allow the page to scroll.
					if ( IS_DEFAULT_THEME ) {
						$html.css( 'overflow', '' ).css( 'padding-right', '-=' + getScrollbarWidth() )
					}

					// Unbind the document events.
					$document.off( '.' + STATE.id )

					// Trigger the queued “close” events.
					return P.trigger( 'close' )
				}, //close


				/**
				 * Clear the values
				 */
				clear: function ( options ) {
					return P.set( 'clear', null, options )
				}, //clear


				/**
				 * Set something
				 */
				set: function ( thing, value, options ) {

					var thingItem, thingValue,
						thingIsObject = $.isPlainObject( thing ),
						thingObject = thingIsObject ? thing : {}

					// Make sure we have usable options.
					options = thingIsObject && $.isPlainObject( value ) ? value : options || {}

					if ( thing ) {

						// If the thing isn’t an object, make it one.
						if ( ! thingIsObject ) {
							thingObject[thing] = value
						}

						// Go through the things of items to set.
						for ( thingItem in thingObject ) {

							// Grab the value of the thing.
							thingValue = thingObject[thingItem]

							// First, if the item exists and there’s a value, set it.
							if ( thingItem in P.component.item ) {
								if ( thingValue === undefined ) {
									thingValue = null
								}
								P.component.set( thingItem, thingValue, options )
							}

							// Then, check to update the element value and broadcast a change.
							if ( thingItem == 'select' || thingItem == 'clear' ) {
								$ELEMENT.val( thingItem == 'clear' ? '' : P.get( thingItem, SETTINGS.format ) ).trigger( 'change' )
							}
						}

						// Render a new picker.
						P.render()
					}

					// When the method isn’t muted, trigger queued “set” events and pass the `thingObject`.
					return options.muted ? P : P.trigger( 'set', thingObject )
				}, //set


				/**
				 * Get something
				 */
				get: function ( thing, format ) {

					// Make sure there’s something to get.
					thing = thing || 'value'

					// If a picker state exists, return that.
					if ( STATE[thing] != null ) {
						return STATE[thing]
					}

					// Return the submission value, if that.
					if ( thing == 'valueSubmit' ) {
						if ( P._hidden ) {
							return P._hidden.value
						}
						thing = 'value'
					}

					// Return the value, if that.
					if ( thing == 'value' ) {
						return ELEMENT.value
					}

					// Check if a component item exists, return that.
					if ( thing in P.component.item ) {
						if ( typeof format == 'string' ) {
							var thingValue = P.component.get( thing )
							return thingValue ?
								PickerConstructor._.trigger(
									P.component.formats.toString,
									P.component,
									[format, thingValue]
								) : ''
						}
						return P.component.get( thing )
					}
				}, //get


				/**
				 * Bind events on the things.
				 */
				on: function ( thing, method, internal ) {

					var thingName, thingMethod,
						thingIsObject = $.isPlainObject( thing ),
						thingObject = thingIsObject ? thing : {}

					if ( thing ) {

						// If the thing isn’t an object, make it one.
						if ( ! thingIsObject ) {
							thingObject[thing] = method
						}

						// Go through the things to bind to.
						for ( thingName in thingObject ) {

							// Grab the method of the thing.
							thingMethod = thingObject[thingName]

							// If it was an internal binding, prefix it.
							if ( internal ) {
								thingName = '_' + thingName
							}

							// Make sure the thing methods collection exists.
							STATE.methods[thingName] = STATE.methods[thingName] || []

							// Add the method to the relative method collection.
							STATE.methods[thingName].push( thingMethod )
						}
					}

					return P
				}, //on


				/**
				 * Unbind events on the things.
				 */
				off: function () {
					var i, thingName,
						names = arguments;
					for ( i = 0, namesCount = names.length; i < namesCount; i += 1 ) {
						thingName = names[i]
						if ( thingName in STATE.methods ) {
							delete STATE.methods[thingName]
						}
					}
					return P
				},


				/**
				 * Fire off method events.
				 */
				trigger: function ( name, data ) {
					var _trigger = function ( name ) {
						var methodList = STATE.methods[name]
						if ( methodList ) {
							methodList.map( function ( method ) {
								PickerConstructor._.trigger( method, P, [data] )
							} )
						}
					}
					_trigger( '_' + name )
					_trigger( name );
					// also trigger the events and allow them to bubble up the DOM
					return P
				} //trigger
			} //PickerInstance.prototype


		/**
		 * Wrap the picker holder components together.
		 */
		function createWrappedComponent() {

			// Create a picker wrapper holder
			return PickerConstructor._.node( 'div',

				// Create a picker wrapper node
				PickerConstructor._.node( 'div',

					// Create a picker frame
					PickerConstructor._.node( 'div',

						// Create a picker box node
						PickerConstructor._.node( 'div',

							// Create the components nodes.
							P.component.nodes( STATE.open ),

							// The picker box class
							CLASSES.box
						),

						// Picker wrap class
						CLASSES.wrap
					),

					// Picker frame class
					CLASSES.frame
				),

				// Picker holder class
				CLASSES.holder,

				'tabindex="-1"'
			) //endreturn
		} //createWrappedComponent


		/**
		 * Prepare the input element with all bindings.
		 */
		function prepareElement() {

			$ELEMENT.// Store the picker data by component name.
			        data( NAME, P ).// Add the “input” class name.
			        addClass( CLASSES.input ).// If there’s a `data-value`, update the value of the element.
			        val( $ELEMENT.data( 'value' ) ?
				P.get( 'select', SETTINGS.format ) :
				ELEMENT.value
			)


			// Only bind keydown events if the element isn’t editable.
			if ( ! SETTINGS.editable ) {

				$ELEMENT.// On focus/click, open the picker.
				        on( 'focus.' + STATE.id + ' click.' + STATE.id, function ( event ) {
					event.preventDefault()
					P.open()
				} ).// Handle keyboard event based on the picker being opened or not.
				        on( 'keydown.' + STATE.id, handleKeydownEvent )
			}


			// Update the aria attributes.
			aria( ELEMENT, {
				haspopup: true,
				expanded: false,
				readonly: false,
				owns: ELEMENT.id + '_root'
			} )
		}


		/**
		 * Prepare the root picker element with all bindings.
		 */
		function prepareElementRoot() {
			aria( P.$root[0], 'hidden', true )
		}


		/**
		 * Prepare the holder picker element with all bindings.
		 */
		function prepareElementHolder() {

			P.$holder.on( {

				// For iOS8.
				keydown: handleKeydownEvent,

				'focus.toOpen': handleFocusToOpenEvent,

				blur: function () {
					// Remove the “target” class.
					$ELEMENT.removeClass( CLASSES.target )
				},

				// When something within the holder is focused, stop from bubbling
				// to the doc and remove the “focused” state from the root.
				focusin: function ( event ) {
					P.$root.removeClass( CLASSES.focused )
					event.stopPropagation()
				},

				// When something within the holder is clicked, stop it
				// from bubbling to the doc.
				'mousedown click': function ( event ) {

					var target = event.target

					// Make sure the target isn’t the root holder so it can bubble up.
					if ( target != P.$holder[0] ) {

						event.stopPropagation()

						// * For mousedown events, cancel the default action in order to
						//   prevent cases where focus is shifted onto external elements
						//   when using things like jQuery mobile or MagnificPopup (ref: #249 & #120).
						//   Also, for Firefox, don’t prevent action on the `option` element.
						if ( event.type == 'mousedown' && ! $( target ).is( 'input, select, textarea, button, option' ) ) {

							event.preventDefault()

							// Re-focus onto the holder so that users can click away
							// from elements focused within the picker.
							P.$holder[0].focus()
						}
					}
				}

			} ).// If there’s a click on an actionable element, carry out the actions.
			 on( 'click', '[data-pick], [data-nav], [data-clear], [data-close]', function () {

				var $target = $( this ),
					targetData = $target.data(),
					targetDisabled = $target.hasClass( CLASSES.navDisabled ) || $target.hasClass( CLASSES.disabled ),

				// * For IE, non-focusable elements can be active elements as well
				//   (http://stackoverflow.com/a/2684561).
					activeElement = getActiveElement()
				activeElement = activeElement && (
						activeElement.type || activeElement.href
					)

				// If it’s disabled or nothing inside is actively focused, re-focus the element.
				if ( targetDisabled || activeElement && ! $.contains( P.$root[0], activeElement ) ) {
					P.$holder[0].focus()
				}

				// If something is superficially changed, update the `highlight` based on the `nav`.
				if ( ! targetDisabled && targetData.nav ) {
					P.set( 'highlight', P.component.item.highlight, {nav: targetData.nav} )
				}

				// If something is picked, set `select` then close with focus.
				else if ( ! targetDisabled && 'pick' in targetData ) {
					P.set( 'select', targetData.pick )
					if ( SETTINGS.closeOnSelect ) {
						P.close( true )
					}
				}

				// If a “clear” button is pressed, empty the values and close with focus.
				else if ( targetData.clear ) {
					P.clear()
					if ( SETTINGS.closeOnClear ) {
						P.close( true )
					}
				}

				else if ( targetData.close ) {
					P.close( true )
				}

			} ) //P.$holder

		}


		/**
		 * Prepare the hidden input element along with all bindings.
		 */
		function prepareElementHidden() {

			var name

			if ( SETTINGS.hiddenName === true ) {
				name = ELEMENT.name
				ELEMENT.name = ''
			}
			else {
				name = [
					typeof SETTINGS.hiddenPrefix == 'string' ? SETTINGS.hiddenPrefix : '',
					typeof SETTINGS.hiddenSuffix == 'string' ? SETTINGS.hiddenSuffix : '_submit'
				]
				name = name[0] + ELEMENT.name + name[1]
			}

			P._hidden = $(
				'<input ' +
				'type=hidden ' +

				// Create the name using the original input’s with a prefix and suffix.
				'name="' + name + '"' +

				// If the element has a value, set the hidden value as well.
				(
					$ELEMENT.data( 'value' ) || ELEMENT.value ?
				' value="' + P.get( 'select', SETTINGS.formatSubmit ) + '"' :
						''
				) +
				'>'
			)[0]

			$ELEMENT.// If the value changes, update the hidden input with the correct format.
			        on( 'change.' + STATE.id, function () {
				P._hidden.value = ELEMENT.value ?
					P.get( 'select', SETTINGS.formatSubmit ) :
					''
			} )
		}


		// Wait for transitions to end before focusing the holder. Otherwise, while
		// using the `container` option, the view jumps to the container.
		function focusPickerOnceOpened() {

//            if (IS_DEFAULT_THEME && supportsTransitions) {
//                P.$holder.find('.' + CLASSES.frame).one('transitionend', function() {
//                    P.$holder[0].focus()
//                })
//            }
//            else {
//                P.$holder[0].focus()
//            }
		}


		function handleFocusToOpenEvent( event ) {

			// Stop the event from propagating to the doc.
			event.stopPropagation()

			// Add the “target” class.
			$ELEMENT.addClass( CLASSES.target )

			// Add the “focused” class to the root.
			P.$root.addClass( CLASSES.focused )

			// And then finally open the picker.
			P.open()
		}


		// For iOS8.
		function handleKeydownEvent( event ) {

			var keycode = event.keyCode,

			// Check if one of the delete keys was pressed.
				isKeycodeDelete = /^(8|46)$/.test( keycode )

			// For some reason IE clears the input value on “escape”.
			if ( keycode == 27 ) {
				P.close( true )
				return false
			}

			// Check if `space` or `delete` was pressed or the picker is closed with a key movement.
			if ( keycode == 32 || isKeycodeDelete || ! STATE.open && P.component.key[keycode] ) {

				// Prevent it from moving the page and bubbling to doc.
				event.preventDefault()
				event.stopPropagation()

				// If `delete` was pressed, clear the values and close the picker.
				// Otherwise open the picker.
				if ( isKeycodeDelete ) { P.clear().close() }
				else { P.open() }
			}
		}


		// Return a new picker instance.
		return new PickerInstance()
	} //PickerConstructor


	/**
	 * The default classes and prefix to use for the HTML classes.
	 */
	PickerConstructor.klasses = function ( prefix ) {
		prefix = prefix || 'picker'
		return {

			picker: prefix,
			opened: prefix + '--opened',
			focused: prefix + '--focused',

			input: prefix + '__input',
			active: prefix + '__input--active',
			target: prefix + '__input--target',

			holder: prefix + '__holder',

			frame: prefix + '__frame',
			wrap: prefix + '__wrap',

			box: prefix + '__box'
		}
	} //PickerConstructor.klasses


	/**
	 * Check if the default theme is being used.
	 */
	function isUsingDefaultTheme( element ) {

		var theme,
			prop = 'position'

		// For IE.
		if ( element.currentStyle ) {
			theme = element.currentStyle[prop]
		}

		// For normal browsers.
		else if ( window.getComputedStyle ) {
			theme = getComputedStyle( element )[prop]
		}

		return theme == 'fixed'
	}


	/**
	 * Get the width of the browser’s scrollbar.
	 * Taken from: https://github.com/VodkaBears/Remodal/blob/master/src/jquery.remodal.js
	 */
	function getScrollbarWidth() {

		if ( $html.height() <= $window.height() ) {
			return 0
		}

		var $outer = $( '<div style="visibility:hidden;width:100px" />' ).appendTo( 'body' )

		// Get the width without scrollbars.
		var widthWithoutScroll = $outer[0].offsetWidth

		// Force adding scrollbars.
		$outer.css( 'overflow', 'scroll' )

		// Add the inner div.
		var $inner = $( '<div style="width:100%" />' ).appendTo( $outer )

		// Get the width with scrollbars.
		var widthWithScroll = $inner[0].offsetWidth

		// Remove the divs.
		$outer.remove()

		// Return the difference between the widths.
		return widthWithoutScroll - widthWithScroll
	}


	/**
	 * PickerConstructor helper methods.
	 */
	PickerConstructor._ = {

		/**
		 * Create a group of nodes. Expects:
		 * `
		 {
			 min:    {Integer},
			 max:    {Integer},
			 i:      {Integer},
			 node:   {String},
			 item:   {Function}
		 }
		 * `
		 */
		group: function ( groupObject ) {

			var
			// Scope for the looped object
				loopObjectScope,

			// Create the nodes list
				nodesList = '',

			// The counter starts from the `min`
				counter = PickerConstructor._.trigger( groupObject.min, groupObject )


			// Loop from the `min` to `max`, incrementing by `i`
			for ( ; counter <= PickerConstructor._.trigger( groupObject.max, groupObject, [counter] ); counter += groupObject.i ) {

				// Trigger the `item` function within scope of the object
				loopObjectScope = PickerConstructor._.trigger( groupObject.item, groupObject, [counter] )

				// Splice the subgroup and create nodes out of the sub nodes
				nodesList += PickerConstructor._.node(
					groupObject.node,
					loopObjectScope[0],   // the node
					loopObjectScope[1],   // the classes
					loopObjectScope[2]    // the attributes
				)
			}

			// Return the list of nodes
			return nodesList
		}, //group


		/**
		 * Create a dom node string
		 */
		node: function ( wrapper, item, klass, attribute ) {

			// If the item is false-y, just return an empty string
			if ( ! item ) {
				return ''
			}

			// If the item is an array, do a join
			item = $.isArray( item ) ? item.join( '' ) : item

			// Check for the class
			klass = klass ? ' class="' + klass + '"' : ''

			// Check for any attributes
			attribute = attribute ? ' ' + attribute : ''

			// Return the wrapped item
			return '<' + wrapper + klass + attribute + '>' + item + '</' + wrapper + '>'
		}, //node


		/**
		 * Lead numbers below 10 with a zero.
		 */
		lead: function ( number ) {
			return (
				       number < 10 ? '0' : ''
			       ) + number
		},


		/**
		 * Trigger a function otherwise return the value.
		 */
		trigger: function ( callback, scope, args ) {
			return typeof callback == 'function' ? callback.apply( scope, args || [] ) : callback
		},


		/**
		 * If the second character is a digit, length is 2 otherwise 1.
		 */
		digits: function ( string ) {
			return (
				/\d/
			).test( string[1] ) ? 2 : 1
		},


		/**
		 * Tell if something is a date object.
		 */
		isDate: function ( value ) {
			return {}.toString.call( value ).indexOf( 'Date' ) > - 1 && this.isInteger( value.getDate() )
		},


		/**
		 * Tell if something is an integer.
		 */
		isInteger: function ( value ) {
			return {}.toString.call( value ).indexOf( 'Number' ) > - 1 && value % 1 === 0
		},


		/**
		 * Create ARIA attribute strings.
		 */
		ariaAttr: ariaAttr
	} //PickerConstructor._


	/**
	 * Extend the picker with a component and defaults.
	 */
	PickerConstructor.extend = function ( name, Component ) {

		// Extend jQuery.
		$.fn[name] = function ( options, action ) {

			// Grab the component data.
			var componentData = this.data( name )

			// If the picker is requested, return the data object.
			if ( options == 'picker' ) {
				return componentData
			}

			// If the component data exists and `options` is a string, carry out the action.
			if ( componentData && typeof options == 'string' ) {
				return PickerConstructor._.trigger( componentData[options], componentData, [action] )
			}

			// Otherwise go through each matched element and if the component
			// doesn’t exist, create a new picker using `this` element
			// and merging the defaults and options with a deep copy.
			return this.each( function () {
				var $this = $( this )
				if ( ! $this.data( name ) ) {
					new PickerConstructor( this, name, Component, options )
				}
			} )
		}

		// Set the defaults.
		$.fn[name].defaults = Component.defaults
	} //PickerConstructor.extend


	function aria( element, attribute, value ) {
		if ( $.isPlainObject( attribute ) ) {
			for ( var key in attribute ) {
				ariaSet( element, key, attribute[key] )
			}
		}
		else {
			ariaSet( element, attribute, value )
		}
	}

	function ariaSet( element, attribute, value ) {
		element.setAttribute(
			(
				attribute == 'role' ? '' : 'aria-'
			) + attribute,
			value
		)
	}

	function ariaAttr( attribute, data ) {
		if ( ! $.isPlainObject( attribute ) ) {
			attribute = {attribute: data}
		}
		data = ''
		for ( var key in attribute ) {
			var attr = (
				           key == 'role' ? '' : 'aria-'
			           ) + key,
				attrVal = attribute[key]
			data += attrVal == null ? '' : attr + '="' + attribute[key] + '"'
		}
		return data
	}

// IE8 bug throws an error for activeElements within iframes.
	function getActiveElement() {
		try {
			return document.activeElement
		} catch ( err ) { }
	}


// Expose the picker constructor.
	return PickerConstructor


} ));;/*!
 * Date picker for pickadate.js v3.5.6
 * http://amsul.github.io/pickadate.js/date.htm
 */

(function ( factory ) {

	// AMD.
    if ( typeof define == 'function' && define.amd ) {
        define( ['picker', 'jquery'], factory )
    }// Node.js/browserify.
    else if ( typeof exports == 'object' ) {
        module.exports = factory( require( './picker.js' ), require( 'jquery' ) )
    }// Browser globals.
    else {
        factory( Picker, jQuery )
    }

}( function ( Picker, $ ) {

    /**
	 * Globals and constants
	 */
	var DAYS_IN_WEEK = 7,
		WEEKS_IN_CALENDAR = 6,
		_ = Picker._


	/**
	 * The date picker constructor
	 */
	function DatePicker( picker, settings ) {

		var calendar = this,
			element = picker.$node[0],
			elementValue = element.value,
			elementDataValue = picker.$node.data( 'value' ),
			valueString = elementDataValue || elementValue,
			formatString = elementDataValue ? settings.formatSubmit : settings.format,
			isRTL = function () {

				return element.currentStyle ?

					// For IE.
				element.currentStyle.direction == 'rtl' :

					// For normal browsers.
				getComputedStyle( picker.$root[0] ).direction == 'rtl'
			}

		calendar.settings = settings
		calendar.$node = picker.$node

		// The queue of methods that will be used to build item objects.
		calendar.queue = {
			min: 'measure create',
			max: 'measure create',
			now: 'now create',
			select: 'parse create validate',
			highlight: 'parse navigate create validate',
			view: 'parse create validate viewset',
			disable: 'deactivate',
			enable: 'activate'
		}

		// The component's item object.
		calendar.item = {}

		calendar.item.clear = null
		calendar.item.disable = ( settings.disable || [] ).slice( 0 )
		calendar.item.enable = - (function ( collectionDisabled ) {
			return collectionDisabled[0] === true ? collectionDisabled.shift() : - 1
		})( calendar.item.disable )

		calendar.set( 'min', settings.min ).set( 'max', settings.max ).set( 'now' )

		// When there’s a value, set the `select`, which in turn
		// also sets the `highlight` and `view`.
		if ( valueString ) {
			calendar.set( 'select', valueString, {
				format: formatString,
				defaultValue: true
			} )
		}

		// If there’s no value, default to highlighting “today”.
		else {
			calendar.set( 'select', null ).set( 'highlight', calendar.item.now )
		}


		// The keycode to movement mapping.
		calendar.key = {
			40: 7, // Down
			38: - 7, // Up
			39: function () {
				return isRTL() ? - 1 : 1
			}, // Right
			37: function () {
				return isRTL() ? 1 : - 1
			}, // Left
			go: function ( timeChange ) {
				var highlightedObject = calendar.item.highlight,
					targetDate = new Date( highlightedObject.year, highlightedObject.month, highlightedObject.date + timeChange )
				calendar.set(
					'highlight',
					targetDate,
					{interval: timeChange}
				)
				this.render()
			}
		}
		// Bind some picker events.
		picker.on( 'render', function () {
			picker.$root.find( '.' + settings.klass.selectMonth.split( ' ' )[0] ).on( 'change', function () {
				var value = this.value
				if ( value ) {
					picker.set( 'highlight', [picker.get( 'view' ).year, value, picker.get( 'highlight' ).date] )
					picker.$root.find( '.' + settings.klass.selectMonth.split( ' ' )[0] ).trigger( 'focus' )
				}
			} )
			picker.$root.find( '.' + settings.klass.selectYear.split( ' ' )[0] ).on( 'change', function () {
				var value = this.value
				if ( value ) {
					picker.set( 'highlight', [value, picker.get( 'view' ).month, picker.get( 'highlight' ).date] )
					picker.$root.find( '.' + settings.klass.selectYear.split( ' ' )[0] ).trigger( 'focus' )
				}
			} )
		}, 1 ).on( 'open', function () {
			var includeToday = ''
			if ( calendar.disabled( calendar.get( 'now' ) ) ) {
				includeToday = ':not(.' + settings.klass.buttonToday + ')'
			}
			picker.$root.find( 'button' + includeToday + ', select' ).attr( 'disabled', false )
		}, 1 ).on( 'close', function () {
			picker.$root.find( 'button, select' ).attr( 'disabled', true )
		}, 1 )

	} //DatePicker


	/**
	 * Set a datepicker item object.
	 */
	DatePicker.prototype.set = function ( type, value, options ) {

		var calendar = this,
			calendarItem = calendar.item

		// If the value is `null` just set it immediately.
		if ( value === null ) {
            if ( type == 'clear' ) {
                type = 'select'
            }
            calendarItem[type] = value
			return calendar
		}

		// Otherwise go through the queue of methods, and invoke the functions.
		// Update this as the time unit, and set the final value as this item.
		// * In the case of `enable`, keep the queue but set `disable` instead.
		//   And in the case of `flip`, keep the queue but set `enable` instead.
		calendarItem[( type == 'enable' ? 'disable' : type == 'flip' ? 'enable' : type )] = calendar.queue[type].split( ' ' ).map( function ( method ) {
			value = calendar[method]( type, value, options )
			return value
		} ).pop()

		// Check if we need to cascade through more updates.
		if ( type == 'select' ) {
			calendar.set( 'highlight', calendarItem.select, options )
		}
		else if ( type == 'highlight' ) {
			calendar.set( 'view', calendarItem.highlight, options )
		}
		else if ( type.match( /^(flip|min|max|disable|enable)$/ ) ) {
			if ( calendarItem.select && calendar.disabled( calendarItem.select ) ) {
				calendar.set( 'select', calendarItem.select, options )
			}
			if ( calendarItem.highlight && calendar.disabled( calendarItem.highlight ) ) {
				calendar.set( 'highlight', calendarItem.highlight, options )
			}
		}

		return calendar
	} //DatePicker.prototype.set


	/**
	 * Get a datepicker item object.
	 */
	DatePicker.prototype.get = function ( type ) {
		return this.item[type]
	} //DatePicker.prototype.get


	/**
	 * Create a picker date object.
	 */
	DatePicker.prototype.create = function ( type, value, options ) {

		var isInfiniteValue,
			calendar = this

		// If there’s no value, use the type as the value.
		value = value === undefined ? type : value


		// If it’s infinity, update the value.
		if ( value == - Infinity || value == Infinity ) {
			isInfiniteValue = value
		}

		// If it’s an object, use the native date object.
		else if ( $.isPlainObject( value ) && _.isInteger( value.pick ) ) {
			value = value.obj
		}

		// If it’s an array, convert it into a date and make sure
		// that it’s a valid date – otherwise default to today.
		else if ( $.isArray( value ) ) {
			value = new Date( value[0], value[1], value[2] )
			value = _.isDate( value ) ? value : calendar.create().obj
		}

		// If it’s a number or date object, make a normalized date.
		else if ( _.isInteger( value ) || _.isDate( value ) ) {
			value = calendar.normalize( new Date( value ), options )
		}

		// If it’s a literal true or any other case, set it to now.
		else /*if ( value === true )*/ {
			value = calendar.now( type, value, options )
		}

		// Return the compiled object.
		return {
			year: isInfiniteValue || value.getFullYear(),
			month: isInfiniteValue || value.getMonth(),
			date: isInfiniteValue || value.getDate(),
			day: isInfiniteValue || value.getDay(),
			obj: isInfiniteValue || value,
			pick: isInfiniteValue || value.getTime()
		}
	} //DatePicker.prototype.create


	/**
	 * Create a range limit object using an array, date object,
	 * literal “true”, or integer relative to another time.
	 */
	DatePicker.prototype.createRange = function ( from, to ) {

		var calendar = this,
			createDate = function ( date ) {
				if ( date === true || $.isArray( date ) || _.isDate( date ) ) {
					return calendar.create( date )
				}
				return date
			}

		// Create objects if possible.
		if ( ! _.isInteger( from ) ) {
			from = createDate( from )
		}
		if ( ! _.isInteger( to ) ) {
			to = createDate( to )
		}

		// Create relative dates.
		if ( _.isInteger( from ) && $.isPlainObject( to ) ) {
			from = [to.year, to.month, to.date + from];
		}
		else if ( _.isInteger( to ) && $.isPlainObject( from ) ) {
			to = [from.year, from.month, from.date + to];
		}

		return {
			from: createDate( from ),
			to: createDate( to )
		}
	} //DatePicker.prototype.createRange


	/**
	 * Check if a date unit falls within a date range object.
	 */
	DatePicker.prototype.withinRange = function ( range, dateUnit ) {
		range = this.createRange( range.from, range.to )
		return dateUnit.pick >= range.from.pick && dateUnit.pick <= range.to.pick
	}


	/**
	 * Check if two date range objects overlap.
	 */
	DatePicker.prototype.overlapRanges = function ( one, two ) {

		var calendar = this

		// Convert the ranges into comparable dates.
		one = calendar.createRange( one.from, one.to )
		two = calendar.createRange( two.from, two.to )

		return calendar.withinRange( one, two.from ) || calendar.withinRange( one, two.to ) ||
		       calendar.withinRange( two, one.from ) || calendar.withinRange( two, one.to )
	}


	/**
	 * Get the date today.
	 */
	DatePicker.prototype.now = function ( type, value, options ) {
		value = new Date()
		if ( options && options.rel ) {
			value.setDate( value.getDate() + options.rel )
		}
		return this.normalize( value, options )
	}


	/**
	 * Navigate to next/prev month.
	 */
	DatePicker.prototype.navigate = function ( type, value, options ) {

		var targetDateObject,
			targetYear,
			targetMonth,
			targetDate,
			isTargetArray = $.isArray( value ),
			isTargetObject = $.isPlainObject( value ),
			viewsetObject = this.item.view
		/*,
		 safety = 100*/


		if ( isTargetArray || isTargetObject ) {

			if ( isTargetObject ) {
				targetYear = value.year
				targetMonth = value.month
				targetDate = value.date
			}
			else {
				targetYear = + value[0]
				targetMonth = + value[1]
				targetDate = + value[2]
			}

			// If we’re navigating months but the view is in a different
			// month, navigate to the view’s year and month.
			if ( options && options.nav && viewsetObject && viewsetObject.month !== targetMonth ) {
				targetYear = viewsetObject.year
				targetMonth = viewsetObject.month
			}

			// Figure out the expected target year and month.
			targetDateObject = new Date( targetYear, targetMonth + ( options && options.nav ? options.nav : 0 ), 1 )
			targetYear = targetDateObject.getFullYear()
			targetMonth = targetDateObject.getMonth()

			// If the month we’re going to doesn’t have enough days,
			// keep decreasing the date until we reach the month’s last date.
			while ( /*safety &&*/ new Date( targetYear, targetMonth, targetDate ).getMonth() !== targetMonth ) {
				targetDate -= 1
				/*safety -= 1
				 if ( !safety ) {
				 throw 'Fell into an infinite loop while navigating to ' + new Date( targetYear, targetMonth, targetDate ) + '.'
				 }*/
			}

			value = [targetYear, targetMonth, targetDate]
		}

		return value
	} //DatePicker.prototype.navigate


	/**
	 * Normalize a date by setting the hours to midnight.
	 */
	DatePicker.prototype.normalize = function ( value/*, options*/ ) {
		value.setHours( 0, 0, 0, 0 )
		return value
	}


	/**
	 * Measure the range of dates.
	 */
	DatePicker.prototype.measure = function ( type, value/*, options*/ ) {

		var calendar = this

		// If it’s anything false-y, remove the limits.
		if ( ! value ) {
			value = type == 'min' ? - Infinity : Infinity
		}

		// If it’s a string, parse it.
		else if ( typeof value == 'string' ) {
			value = calendar.parse( type, value )
		}

		// If it's an integer, get a date relative to today.
		else if ( _.isInteger( value ) ) {
			value = calendar.now( type, value, {rel: value} )
		}

		return value
	} ///DatePicker.prototype.measure


	/**
	 * Create a viewset object based on navigation.
	 */
	DatePicker.prototype.viewset = function ( type, dateObject/*, options*/ ) {
		return this.create( [dateObject.year, dateObject.month, 1] )
	}


	/**
	 * Validate a date as enabled and shift if needed.
	 */
	DatePicker.prototype.validate = function ( type, dateObject, options ) {

		var calendar = this,

		// Keep a reference to the original date.
			originalDateObject = dateObject,

		// Make sure we have an interval.
			interval = options && options.interval ? options.interval : 1,

		// Check if the calendar enabled dates are inverted.
			isFlippedBase = calendar.item.enable === - 1,

		// Check if we have any enabled dates after/before now.
			hasEnabledBeforeTarget, hasEnabledAfterTarget,

		// The min & max limits.
			minLimitObject = calendar.item.min,
			maxLimitObject = calendar.item.max,

		// Check if we’ve reached the limit during shifting.
			reachedMin, reachedMax,

		// Check if the calendar is inverted and at least one weekday is enabled.
			hasEnabledWeekdays = isFlippedBase && calendar.item.disable.filter( function ( value ) {

					// If there’s a date, check where it is relative to the target.
					if ( $.isArray( value ) ) {
						var dateTime = calendar.create( value ).pick
						if ( dateTime < dateObject.pick ) hasEnabledBeforeTarget = true
						else if ( dateTime > dateObject.pick ) {
                            hasEnabledAfterTarget = true
                        }
                    }

                    // Return only integers for enabled weekdays.
					return _.isInteger( value )
				} ).length
		/*,

		 safety = 100*/


		// Cases to validate for:
		// [1] Not inverted and date disabled.
		// [2] Inverted and some dates enabled.
		// [3] Not inverted and out of range.
		//
		// Cases to **not** validate for:
		// • Navigating months.
		// • Not inverted and date enabled.
		// • Inverted and all dates disabled.
		// • ..and anything else.
		if ( ! options || (! options.nav && ! options.defaultValue) ) if (
			/* 1 */ ( ! isFlippedBase && calendar.disabled( dateObject ) ) ||
			        /* 2 */ ( isFlippedBase && calendar.disabled( dateObject ) && ( hasEnabledWeekdays || hasEnabledBeforeTarget || hasEnabledAfterTarget ) ) ||
			        /* 3 */ ( ! isFlippedBase && (dateObject.pick <= minLimitObject.pick || dateObject.pick >= maxLimitObject.pick) )
		) {


			// When inverted, flip the direction if there aren’t any enabled weekdays
			// and there are no enabled dates in the direction of the interval.
			if ( isFlippedBase && ! hasEnabledWeekdays && ( ( ! hasEnabledAfterTarget && interval > 0 ) || ( ! hasEnabledBeforeTarget && interval < 0 ) ) ) {
				interval *= - 1
			}


			// Keep looping until we reach an enabled date.
			while ( /*safety &&*/ calendar.disabled( dateObject ) ) {

				/*safety -= 1
				 if ( !safety ) {
				 throw 'Fell into an infinite loop while validating ' + dateObject.obj + '.'
				 }*/


				// If we’ve looped into the next/prev month with a large interval, return to the original date and flatten the interval.
				if ( Math.abs( interval ) > 1 && ( dateObject.month < originalDateObject.month || dateObject.month > originalDateObject.month ) ) {
					dateObject = originalDateObject
					interval = interval > 0 ? 1 : - 1
				}


				// If we’ve reached the min/max limit, reverse the direction, flatten the interval and set it to the limit.
				if ( dateObject.pick <= minLimitObject.pick ) {
					reachedMin = true
					interval = 1
					dateObject = calendar.create( [
						minLimitObject.year,
						minLimitObject.month,
						minLimitObject.date + (dateObject.pick === minLimitObject.pick ? 0 : - 1)
					] )
				}
				else if ( dateObject.pick >= maxLimitObject.pick ) {
					reachedMax = true
					interval = - 1
					dateObject = calendar.create( [
						maxLimitObject.year,
						maxLimitObject.month,
						maxLimitObject.date + (dateObject.pick === maxLimitObject.pick ? 0 : 1)
					] )
				}


				// If we’ve reached both limits, just break out of the loop.
				if ( reachedMin && reachedMax ) {
					break
				}


				// Finally, create the shifted date using the interval and keep looping.
				dateObject = calendar.create( [dateObject.year, dateObject.month, dateObject.date + interval] )
			}

		} //endif


		// Return the date object settled on.
		return dateObject
	} //DatePicker.prototype.validate


	/**
	 * Check if a date is disabled.
	 */
	DatePicker.prototype.disabled = function ( dateToVerify ) {

		var
			calendar = this,

		// Filter through the disabled dates to check if this is one.
			isDisabledMatch = calendar.item.disable.filter( function ( dateToDisable ) {

				// If the date is a number, match the weekday with 0index and `firstDay` check.
				if ( _.isInteger( dateToDisable ) ) {
					return dateToVerify.day === ( calendar.settings.firstDay ? dateToDisable : dateToDisable - 1 ) % 7
				}

				// If it’s an array or a native JS date, create and match the exact date.
				if ( $.isArray( dateToDisable ) || _.isDate( dateToDisable ) ) {
					return dateToVerify.pick === calendar.create( dateToDisable ).pick
				}

				// If it’s an object, match a date within the “from” and “to” range.
				if ( $.isPlainObject( dateToDisable ) ) {
					return calendar.withinRange( dateToDisable, dateToVerify )
				}
			} )

		// If this date matches a disabled date, confirm it’s not inverted.
		isDisabledMatch = isDisabledMatch.length && ! isDisabledMatch.filter( function ( dateToDisable ) {
				return $.isArray( dateToDisable ) && dateToDisable[3] == 'inverted' ||
				       $.isPlainObject( dateToDisable ) && dateToDisable.inverted
			} ).length

		// Check the calendar “enabled” flag and respectively flip the
		// disabled state. Then also check if it’s beyond the min/max limits.
		return calendar.item.enable === - 1 ? ! isDisabledMatch : isDisabledMatch ||
		                                                          dateToVerify.pick < calendar.item.min.pick ||
		                                                          dateToVerify.pick > calendar.item.max.pick

	} //DatePicker.prototype.disabled


	/**
	 * Parse a string into a usable type.
	 */
	DatePicker.prototype.parse = function ( type, value, options ) {

		var calendar = this,
			parsingObject = {}

		// If it’s already parsed, we’re good.
		if ( ! value || typeof value != 'string' ) {
			return value
		}

		// We need a `.format` to parse the value with.
		if ( ! ( options && options.format ) ) {
			options = options || {}
			options.format = calendar.settings.format
		}

		// Convert the format into an array and then map through it.
		calendar.formats.toArray( options.format ).map( function ( label ) {

			var
			// Grab the formatting label.
				formattingLabel = calendar.formats[label],

			// The format length is from the formatting label function or the
			// label length without the escaping exclamation (!) mark.
				formatLength = formattingLabel ? _.trigger( formattingLabel, calendar, [value, parsingObject] ) : label.replace( /^!/, '' ).length

			// If there's a format label, split the value up to the format length.
			// Then add it to the parsing object with appropriate label.
			if ( formattingLabel ) {
				parsingObject[label] = value.substr( 0, formatLength )
			}

			// Update the value as the substring from format length to end.
			value = value.substr( formatLength )
		} )

		// Compensate for month 0index.
		return [
			parsingObject.yyyy || parsingObject.yy,
			+ ( parsingObject.mm || parsingObject.m ) - 1,
			parsingObject.dd || parsingObject.d
		]
	} //DatePicker.prototype.parse


	/**
	 * Various formats to display the object in.
	 */
	DatePicker.prototype.formats = (function () {

		// Return the length of the first word in a collection.
		function getWordLengthFromCollection( string, collection, dateObject ) {

			// Grab the first word from the string.
			// Regex pattern from http://stackoverflow.com/q/150033
			var word = string.match( /[^\x00-\x7F]+|\w+/ )[0]

			// If there's no month index, add it to the date object
			if ( ! dateObject.mm && ! dateObject.m ) {
				dateObject.m = collection.indexOf( word ) + 1
			}

			// Return the length of the word.
			return word.length
		}

		// Get the length of the first word in a string.
		function getFirstWordLength( string ) {
			return string.match( /\w+/ )[0].length
		}

		return {

			d: function ( string, dateObject ) {

				// If there's string, then get the digits length.
				// Otherwise return the selected date.
				return string ? _.digits( string ) : dateObject.date
			},
			dd: function ( string, dateObject ) {

				// If there's a string, then the length is always 2.
				// Otherwise return the selected date with a leading zero.
				return string ? 2 : _.lead( dateObject.date )
			},
			ddd: function ( string, dateObject ) {

				// If there's a string, then get the length of the first word.
				// Otherwise return the short selected weekday.
				return string ? getFirstWordLength( string ) : this.settings.weekdaysShort[dateObject.day]
			},
			dddd: function ( string, dateObject ) {

				// If there's a string, then get the length of the first word.
				// Otherwise return the full selected weekday.
				return string ? getFirstWordLength( string ) : this.settings.weekdaysFull[dateObject.day]
			},
			m: function ( string, dateObject ) {

				// If there's a string, then get the length of the digits
				// Otherwise return the selected month with 0index compensation.
				return string ? _.digits( string ) : dateObject.month + 1
			},
			mm: function ( string, dateObject ) {

				// If there's a string, then the length is always 2.
				// Otherwise return the selected month with 0index and leading zero.
				return string ? 2 : _.lead( dateObject.month + 1 )
			},
			mmm: function ( string, dateObject ) {

				var collection = this.settings.monthsShort

				// If there's a string, get length of the relevant month from the short
				// months collection. Otherwise return the selected month from that collection.
				return string ? getWordLengthFromCollection( string, collection, dateObject ) : collection[dateObject.month]
			},
			mmmm: function ( string, dateObject ) {

				var collection = this.settings.monthsFull

				// If there's a string, get length of the relevant month from the full
				// months collection. Otherwise return the selected month from that collection.
				return string ? getWordLengthFromCollection( string, collection, dateObject ) : collection[dateObject.month]
			},
			yy: function ( string, dateObject ) {

				// If there's a string, then the length is always 2.
				// Otherwise return the selected year by slicing out the first 2 digits.
				return string ? 2 : ( '' + dateObject.year ).slice( 2 )
			},
			yyyy: function ( string, dateObject ) {

				// If there's a string, then the length is always 4.
				// Otherwise return the selected year.
				return string ? 4 : dateObject.year
			},

			// Create an array by splitting the formatting string passed.
			toArray: function ( formatString ) {
				return formatString.split( /(d{1,4}|m{1,4}|y{4}|yy|!.)/g )
			},

			// Format an object into a string using the formatting options.
			toString: function ( formatString, itemObject ) {
				var calendar = this
				return calendar.formats.toArray( formatString ).map( function ( label ) {
					return _.trigger( calendar.formats[label], calendar, [0, itemObject] ) || label.replace( /^!/, '' )
				} ).join( '' )
			}
		}
	})() //DatePicker.prototype.formats


	/**
	 * Check if two date units are the exact.
	 */
	DatePicker.prototype.isDateExact = function ( one, two ) {

		var calendar = this

		// When we’re working with weekdays, do a direct comparison.
		if (
			( _.isInteger( one ) && _.isInteger( two ) ) ||
			( typeof one == 'boolean' && typeof two == 'boolean' )
		) {
			return one === two
		}

		// When we’re working with date representations, compare the “pick” value.
		if (
			( _.isDate( one ) || $.isArray( one ) ) &&
			( _.isDate( two ) || $.isArray( two ) )
		) {
			return calendar.create( one ).pick === calendar.create( two ).pick
		}

		// When we’re working with range objects, compare the “from” and “to”.
		if ( $.isPlainObject( one ) && $.isPlainObject( two ) ) {
			return calendar.isDateExact( one.from, two.from ) && calendar.isDateExact( one.to, two.to )
		}

		return false
	}


	/**
	 * Check if two date units overlap.
	 */
	DatePicker.prototype.isDateOverlap = function ( one, two ) {

		var calendar = this,
			firstDay = calendar.settings.firstDay ? 1 : 0

		// When we’re working with a weekday index, compare the days.
		if ( _.isInteger( one ) && ( _.isDate( two ) || $.isArray( two ) ) ) {
			one = one % 7 + firstDay
			return one === calendar.create( two ).day + 1
		}
		if ( _.isInteger( two ) && ( _.isDate( one ) || $.isArray( one ) ) ) {
			two = two % 7 + firstDay
			return two === calendar.create( one ).day + 1
		}

		// When we’re working with range objects, check if the ranges overlap.
		if ( $.isPlainObject( one ) && $.isPlainObject( two ) ) {
			return calendar.overlapRanges( one, two )
		}

		return false
	}


	/**
	 * Flip the “enabled” state.
	 */
	DatePicker.prototype.flipEnable = function ( val ) {
		var itemObject = this.item
		itemObject.enable = val || (itemObject.enable == - 1 ? 1 : - 1)
	}


	/**
	 * Mark a collection of dates as “disabled”.
	 */
	DatePicker.prototype.deactivate = function ( type, datesToDisable ) {

		var calendar = this,
			disabledItems = calendar.item.disable.slice( 0 )


		// If we’re flipping, that’s all we need to do.
		if ( datesToDisable == 'flip' ) {
			calendar.flipEnable()
		}

		else if ( datesToDisable === false ) {
			calendar.flipEnable( 1 )
			disabledItems = []
		}

		else if ( datesToDisable === true ) {
			calendar.flipEnable( - 1 )
			disabledItems = []
		}

		// Otherwise go through the dates to disable.
		else {

			datesToDisable.map( function ( unitToDisable ) {

				var matchFound

				// When we have disabled items, check for matches.
				// If something is matched, immediately break out.
				for ( var index = 0; index < disabledItems.length; index += 1 ) {
					if ( calendar.isDateExact( unitToDisable, disabledItems[index] ) ) {
						matchFound = true
						break
					}
				}

				// If nothing was found, add the validated unit to the collection.
				if ( ! matchFound ) {
					if (
						_.isInteger( unitToDisable ) ||
						_.isDate( unitToDisable ) ||
						$.isArray( unitToDisable ) ||
						( $.isPlainObject( unitToDisable ) && unitToDisable.from && unitToDisable.to )
					) {
						disabledItems.push( unitToDisable )
					}
				}
			} )
		}

		// Return the updated collection.
		return disabledItems
	} //DatePicker.prototype.deactivate


	/**
	 * Mark a collection of dates as “enabled”.
	 */
	DatePicker.prototype.activate = function ( type, datesToEnable ) {

		var calendar = this,
			disabledItems = calendar.item.disable,
			disabledItemsCount = disabledItems.length

		// If we’re flipping, that’s all we need to do.
		if ( datesToEnable == 'flip' ) {
			calendar.flipEnable()
		}

		else if ( datesToEnable === true ) {
			calendar.flipEnable( 1 )
			disabledItems = []
		}

		else if ( datesToEnable === false ) {
			calendar.flipEnable( - 1 )
			disabledItems = []
		}

		// Otherwise go through the disabled dates.
		else {

			datesToEnable.map( function ( unitToEnable ) {

				var matchFound,
					disabledUnit,
					index,
					isExactRange

				// Go through the disabled items and try to find a match.
				for ( index = 0; index < disabledItemsCount; index += 1 ) {

					disabledUnit = disabledItems[index]

					// When an exact match is found, remove it from the collection.
					if ( calendar.isDateExact( disabledUnit, unitToEnable ) ) {
						matchFound = disabledItems[index] = null
						isExactRange = true
						break
					}

					// When an overlapped match is found, add the “inverted” state to it.
					else if ( calendar.isDateOverlap( disabledUnit, unitToEnable ) ) {
						if ( $.isPlainObject( unitToEnable ) ) {
							unitToEnable.inverted = true
							matchFound = unitToEnable
						}
						else if ( $.isArray( unitToEnable ) ) {
							matchFound = unitToEnable
							if ( ! matchFound[3] ) matchFound.push( 'inverted' )
						}
						else if ( _.isDate( unitToEnable ) ) {
							matchFound = [unitToEnable.getFullYear(), unitToEnable.getMonth(), unitToEnable.getDate(), 'inverted']
						}
						break
					}
				}

				// If a match was found, remove a previous duplicate entry.
				if ( matchFound ) for ( index = 0; index < disabledItemsCount; index += 1 ) {
					if ( calendar.isDateExact( disabledItems[index], unitToEnable ) ) {
						disabledItems[index] = null
						break
					}
				}

				// In the event that we’re dealing with an exact range of dates,
				// make sure there are no “inverted” dates because of it.
				if ( isExactRange ) for ( index = 0; index < disabledItemsCount; index += 1 ) {
					if ( calendar.isDateOverlap( disabledItems[index], unitToEnable ) ) {
						disabledItems[index] = null
						break
					}
				}

				// If something is still matched, add it into the collection.
				if ( matchFound ) {
					disabledItems.push( matchFound )
				}
			} )
		}

		// Return the updated collection.
		return disabledItems.filter( function ( val ) {
			return val != null
		} )
	} //DatePicker.prototype.activate


	/**
	 * Create a string for the nodes in the picker.
	 */
	DatePicker.prototype.nodes = function ( isOpen ) {

		var
			calendar = this,
			settings = calendar.settings,
			calendarItem = calendar.item,
			nowObject = calendarItem.now,
			selectedObject = calendarItem.select,
			highlightedObject = calendarItem.highlight,
			viewsetObject = calendarItem.view,
			disabledCollection = calendarItem.disable,
			minLimitObject = calendarItem.min,
			maxLimitObject = calendarItem.max,


		// Create the calendar table head using a copy of weekday labels collection.
		// * We do a copy so we don't mutate the original array.
			tableHead = (function ( collection, fullCollection ) {

				// If the first day should be Monday, move Sunday to the end.
				if ( settings.firstDay ) {
					collection.push( collection.shift() )
					fullCollection.push( fullCollection.shift() )
				}

				// Create and return the table head group.
				return _.node(
					'thead',
					_.node(
						'tr',
						_.group( {
							min: 0,
							max: DAYS_IN_WEEK - 1,
							i: 1,
							node: 'th',
							item: function ( counter ) {
								return [
									collection[counter],
									settings.klass.weekdays,
									'scope=col title="' + fullCollection[counter] + '"'
								]
							}
						} )
					)
				) //endreturn
			})( ( settings.showWeekdaysFull ? settings.weekdaysFull : settings.weekdaysShort ).slice( 0 ), settings.weekdaysFull.slice( 0 ) ), //tableHead


		// Create the nav for next/prev month.
			createMonthNav = function ( next ) {

				// Otherwise, return the created month tag.
				return _.node(
					'div',
					' ',
					settings.klass['nav' + ( next ? 'Next' : 'Prev' )] + (

						// If the focused month is outside the range, disabled the button.
						( next && viewsetObject.year >= maxLimitObject.year && viewsetObject.month >= maxLimitObject.month ) ||
						( ! next && viewsetObject.year <= minLimitObject.year && viewsetObject.month <= minLimitObject.month ) ?
					' ' + settings.klass.navDisabled : ''
					),
					'data-nav=' + ( next || - 1 ) + ' ' +
					_.ariaAttr( {
						role: 'button',
						controls: calendar.$node[0].id + '_table'
					} ) + ' ' +
					'title="' + (next ? settings.labelMonthNext : settings.labelMonthPrev ) + '"'
				) //endreturn
			}, //createMonthNav


		// Create the month label.
			createMonthLabel = function () {

				var monthsCollection = settings.showMonthsShort ? settings.monthsShort : settings.monthsFull

				// If there are months to select, add a dropdown menu.
				if ( settings.selectMonths ) {

					return _.node( 'select',
						_.group( {
							min: 0,
							max: 11,
							i: 1,
							node: 'option',
							item: function ( loopedMonth ) {

								return [

									// The looped month and no classes.
									monthsCollection[loopedMonth], 0,

									// Set the value and selected index.
									'value=' + loopedMonth +
									( viewsetObject.month == loopedMonth ? ' selected' : '' ) +
									(
										(
											( viewsetObject.year == minLimitObject.year && loopedMonth < minLimitObject.month ) ||
											( viewsetObject.year == maxLimitObject.year && loopedMonth > maxLimitObject.month )
										) ?
											' disabled' : ''
									)
								]
							}
						} ),
						settings.klass.selectMonth,
						( isOpen ? '' : 'disabled' ) + ' ' +
						_.ariaAttr( {controls: calendar.$node[0].id + '_table'} ) + ' ' +
						'title="' + settings.labelMonthSelect + '"'
					)
				}

				// If there's a need for a month selector
				return _.node( 'div', monthsCollection[viewsetObject.month], settings.klass.month )
			}, //createMonthLabel


		// Create the year label.
			createYearLabel = function () {

				var focusedYear = viewsetObject.year,

				// If years selector is set to a literal "true", set it to 5. Otherwise
				// divide in half to get half before and half after focused year.
					numberYears = settings.selectYears === true ? 5 : ~ ~ ( settings.selectYears / 2 )

				// If there are years to select, add a dropdown menu.
				if ( numberYears ) {

					var
						minYear = minLimitObject.year,
						maxYear = maxLimitObject.year,
						lowestYear = focusedYear - numberYears,
						highestYear = focusedYear + numberYears

					// If the min year is greater than the lowest year, increase the highest year
					// by the difference and set the lowest year to the min year.
					if ( minYear > lowestYear ) {
						highestYear += minYear - lowestYear
						lowestYear = minYear
					}

					// If the max year is less than the highest year, decrease the lowest year
					// by the lower of the two: available and needed years. Then set the
					// highest year to the max year.
					if ( maxYear < highestYear ) {

						var availableYears = lowestYear - minYear,
							neededYears = highestYear - maxYear

						lowestYear -= availableYears > neededYears ? neededYears : availableYears
						highestYear = maxYear
					}

					return _.node( 'select',
						_.group( {
							min: lowestYear,
							max: highestYear,
							i: 1,
							node: 'option',
							item: function ( loopedYear ) {
								return [

									// The looped year and no classes.
									loopedYear, 0,

									// Set the value and selected index.
									'value=' + loopedYear + ( focusedYear == loopedYear ? ' selected' : '' )
								]
							}
						} ),
						settings.klass.selectYear,
						( isOpen ? '' : 'disabled' ) + ' ' + _.ariaAttr( {controls: calendar.$node[0].id + '_table'} ) + ' ' +
						'title="' + settings.labelYearSelect + '"'
					)
				}

				// Otherwise just return the year focused
				return _.node( 'div', focusedYear, settings.klass.year )
			} //createYearLabel


		// Create and return the entire calendar.
		return _.node(
				'div',
               ( settings.selectYears ? createYearLabel() + createMonthLabel() : createMonthLabel() + createYearLabel() ) +
               createMonthNav() + createMonthNav( 1 ),
				settings.klass.header
			) + _.node(
				'table',
               tableHead +
               _.node(
	               'tbody',
	               _.group( {
		               min: 0,
		               max: WEEKS_IN_CALENDAR - 1,
		               i: 1,
		               node: 'tr',
		               item: function ( rowCounter ) {

			               // If Monday is the first day and the month starts on Sunday, shift the date back a week.
			               var shiftDateBy = settings.firstDay && calendar.create( [viewsetObject.year, viewsetObject.month, 1] ).day === 0 ? - 7 : 0

			               return [
				               _.group( {
					               min: DAYS_IN_WEEK * rowCounter - viewsetObject.day + shiftDateBy + 1, // Add 1 for weekday 0index
					               max: function () {
						               return this.min + DAYS_IN_WEEK - 1
					               },
					               i: 1,
					               node: 'td',
					               item: function ( targetDate ) {

						               // Convert the time date from a relative date to a target date.
						               targetDate = calendar.create( [viewsetObject.year, viewsetObject.month, targetDate + ( settings.firstDay ? 1 : 0 )] )

						               var isSelected = selectedObject && selectedObject.pick == targetDate.pick,
							               isHighlighted = highlightedObject && highlightedObject.pick == targetDate.pick,
							               isDisabled = disabledCollection && calendar.disabled( targetDate ) || targetDate.pick < minLimitObject.pick || targetDate.pick > maxLimitObject.pick,
							               formattedDate = _.trigger( calendar.formats.toString, calendar, [settings.format, targetDate] )

						               return [
							               _.node(
								               'div',
								               targetDate.date,
								               (function ( klasses ) {

									               // Add the `infocus` or `outfocus` classes based on month in view.
									               klasses.push( viewsetObject.month == targetDate.month ? settings.klass.infocus : settings.klass.outfocus )

									               // Add the `today` class if needed.
									               if ( nowObject.pick == targetDate.pick ) {
										               klasses.push( settings.klass.now )
									               }

									               // Add the `selected` class if something's selected and the time matches.
									               if ( isSelected ) {
										               klasses.push( settings.klass.selected )
									               }

									               // Add the `highlighted` class if something's highlighted and the time matches.
									               if ( isHighlighted ) {
										               klasses.push( settings.klass.highlighted )
									               }

									               // Add the `disabled` class if something's disabled and the object matches.
									               if ( isDisabled ) {
										               klasses.push( settings.klass.disabled )
									               }

									               return klasses.join( ' ' )
								               })( [settings.klass.day] ),
								               'data-pick=' + targetDate.pick + ' ' + _.ariaAttr( {
									               role: 'gridcell',
									               label: formattedDate,
									               selected: isSelected && calendar.$node.val() === formattedDate ? true : null,
									               activedescendant: isHighlighted ? true : null,
									               disabled: isDisabled ? true : null
								               } )
							               ),
							               '',
							               _.ariaAttr( {role: 'presentation'} )
						               ] //endreturn
					               }
				               } )
			               ] //endreturn
		               }
	               } )
               ),
				settings.klass.table,
               'id="' + calendar.$node[0].id + '_table' + '" ' + _.ariaAttr( {
	               role: 'grid',
	               controls: calendar.$node[0].id,
	               readonly: true
               } )
			) +

		       // * For Firefox forms to submit, make sure to set the buttons’ `type` attributes as “button”.
		       _.node(
			       'div',
			   _.node( 'button', settings.today, settings.klass.buttonToday,
			   'type=button data-pick=' + nowObject.pick +
			   ( isOpen && ! calendar.disabled( nowObject ) ? '' : ' disabled' ) + ' ' +
			   _.ariaAttr( {controls: calendar.$node[0].id} ) ) +
			   _.node( 'button', settings.clear, settings.klass.buttonClear,
			   'type=button data-clear=1' +
			   ( isOpen ? '' : ' disabled' ) + ' ' +
			   _.ariaAttr( {controls: calendar.$node[0].id} ) ) +
			   _.node( 'button', settings.close, settings.klass.buttonClose,
			   'type=button data-close=true ' +
			   ( isOpen ? '' : ' disabled' ) + ' ' +
			   _.ariaAttr( {controls: calendar.$node[0].id} ) ),
			       settings.klass.footer
		       ) //endreturn
	} //DatePicker.prototype.nodes


	/**
	 * The date picker defaults.
	 */
	DatePicker.defaults = (function ( prefix ) {

		return {

			// The title label to use for the month nav buttons
			labelMonthNext: 'Next month',
			labelMonthPrev: 'Previous month',

			// The title label to use for the dropdown selectors
			labelMonthSelect: 'Select a month',
			labelYearSelect: 'Select a year',

			// Months and weekdays
			monthsFull: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			weekdaysFull: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
			weekdaysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

			// Today and clear
			today: 'Today',
			clear: 'Clear',
			close: 'Close',

			// Picker close behavior
			closeOnSelect: true,
			closeOnClear: true,

			// The format to show on the `input` element
			format: 'd mmmm, yyyy',

			// Classes
			klass: {

				table: prefix + 'table',

				header: prefix + 'header',

				navPrev: prefix + 'nav--prev',
				navNext: prefix + 'nav--next',
				navDisabled: prefix + 'nav--disabled',

				month: prefix + 'month',
				year: prefix + 'year',

				selectMonth: prefix + 'select--month tvd-browser-default',
				selectYear: prefix + 'select--year tvd-browser-default',

				weekdays: prefix + 'weekday',

				day: prefix + 'day',
				disabled: prefix + 'day--disabled',
				selected: prefix + 'day--selected',
				highlighted: prefix + 'day--highlighted',
				now: prefix + 'day--today',
				infocus: prefix + 'day--infocus',
				outfocus: prefix + 'day--outfocus',

				footer: prefix + 'footer',

				buttonClear: prefix + 'button--clear',
				buttonToday: prefix + 'button--today',
				buttonClose: prefix + 'button--close'
			}
		}
	})( Picker.klasses().picker + '__' )


	/**
	 * Extend the picker to add the date picker.
	 */
	Picker.extend( 'pickadate', DatePicker )


} ));



;/**
 * Created by User on 11/20/2015.
 */
/**
 * jQuery Timepicker - v1.3.2 - 2014-09-13
 * http://timepicker.co
 *
 * Enhances standard form input fields helping users to select (or type) times.
 *
 * Copyright (c) 2014 Willington Vega; Licensed MIT, GPL
 */

if (typeof jQuery !== 'undefined') {
    (function($, undefined) {

        function pad(str, ch, length) {
            return (new Array(length + 1 - str.length).join(ch)) + str;
        }

        function normalize() {
            if (arguments.length === 1) {
                var date = arguments[0];
                if (typeof date === 'string') {
                    date = $.fn.timepicker.parseTime(date);
                }
                return new Date(0, 0, 0, date.getHours(), date.getMinutes(), date.getSeconds());
            } else if (arguments.length === 3) {
                return new Date(0, 0, 0, arguments[0], arguments[1], arguments[2]);
            } else if (arguments.length === 2) {
                return new Date(0, 0, 0, arguments[0], arguments[1], 0);
            } else {
                return new Date(0, 0, 0);
            }
        }

        $.TimePicker = function(inputElement) {
            this.init(inputElement);
        };

        $.TimePicker.count = 0;
        $.TimePicker.instance = function(input) {
            var $input = $(input);
            if ($input.data('TimePickerGlobal')) {
                return $input.data('TimePickerGlobal');
            }
            return new $.TimePicker(input);
        };

        $.TimePicker.prototype = {
            init: function (input) {
                var $input = $(input),
                    widget = this,
                    $appendTo = $input.parents('.timepicker-append-to').first();

                if (!$appendTo.length) {
                    $appendTo = $input.parent();
                }

                widget.container = $('.ui-timepicker-container', $appendTo);
                widget.ui = widget.container.find('.ui-timepicker');
                if (widget.container.length === 0) {
                    widget.container = $('<div></div>').addClass('ui-timepicker-container')
                        .addClass('ui-timepicker-hidden ui-helper-hidden')
                        .appendTo($appendTo)
                        .hide();
                    widget.ui = $( '<div></div>' ).addClass('ui-timepicker')
                        .addClass('ui-widget ui-widget-content ui-menu')
                        .addClass('ui-corner-all')
                        .appendTo(widget.container);
                    widget.viewport = $('<ul></ul>').addClass( 'ui-timepicker-viewport' )
                        .appendTo( widget.ui );

                    widget.ui.delegate('a', 'mouseenter.timepicker', function() {
                        // passing false instead of an instance object tells the function
                        // to use the current instance
                        widget.activate(false, $(this).parent());
                    }).delegate('a', 'mouseleave.timepicker', function() {
                        widget.deactivate(false);
                    }).delegate('a', 'mousedown.timepicker', function(event) {
                        event.preventDefault();
                        widget.select(false, $(this).parent());
                    });

                    widget.ui.bind('click.timepicker, scroll.timepicker', function() {
                        clearTimeout(widget.closing);
                    });
                }
                $input.data('TimePickerGlobal', this);
            },
            // extracted from from jQuery UI Core
            // http://github,com/jquery/jquery-ui/blob/master/ui/jquery.ui.core.js
            keyCode: {
                ALT: 18,
                BLOQ_MAYUS: 20,
                CTRL: 17,
                DOWN: 40,
                END: 35,
                ENTER: 13,
                HOME: 36,
                LEFT: 37,
                NUMPAD_ENTER: 108,
                PAGE_DOWN: 34,
                PAGE_UP: 33,
                RIGHT: 39,
                SHIFT: 16,
                TAB: 9,
                UP: 38
            },

            _items: function(i, startTime) {
                var widget = this, ul = $('<ul></ul>'), item = null, time, end;

                // interval should be a multiple of 60 if timeFormat is not
                // showing minutes
                if (i.options.timeFormat.indexOf('m') === -1 && i.options.interval % 60 !== 0) {
                    i.options.interval = Math.max(Math.round(i.options.interval / 60), 1) * 60;
                }

                if (startTime) {
                    time = normalize(startTime);
                } else if (i.options.startTime) {
                    time = normalize(i.options.startTime);
                } else {
                    time = normalize(i.options.startHour, i.options.startMinutes);
                }

                end = new Date(time.getTime() + 24 * 60 * 60 * 1000);

                while(time < end) {
                    if (widget._isValidTime(i, time)) {
                        item = $('<li>').addClass('ui-menu-item').appendTo(ul);
                        $('<a>').addClass('ui-corner-all').text($.fn.timepicker.formatTime(i.options.timeFormat, time)).appendTo(item);
                        item.data('time-value', time);
                    }
                    time = new Date(time.getTime() + i.options.interval * 60 * 1000);
                }

                return ul.children();
            },

            _isValidTime: function(i, time) {
                var min = null, max = null;

                time = normalize(time);

                if (i.options.minTime !== null) {
                    min = normalize(i.options.minTime);
                } else if (i.options.minHour !== null || i.options.minMinutes !== null) {
                    min = normalize(i.options.minHour, i.options.minMinutes);
                }

                if (i.options.maxTime !== null) {
                    max = normalize(i.options.maxTime);
                } else if (i.options.maxHour !== null || i.options.maxMinutes !== null) {
                    max = normalize(i.options.maxHour, i.options.maxMinutes);
                }

                if (min !== null && max !== null) {
                    return time >= min && time <= max;
                } else if (min !== null) {
                    return time >= min;
                } else if (max !== null) {
                    return time <= max;
                }

                return true;
            },

            _hasScroll: function() {
                // fix for jQuery 1.6 new prop method
                var m = typeof this.ui.prop !== 'undefined' ? 'prop' : 'attr';
                return this.ui.height() < this.ui[m]('scrollHeight');
            },

            /**
             * TODO: Write me!
             *
             * @param i
             * @param direction
             * @param edge
             * */
            _move: function(i, direction, edge) {
                var widget = this;
                if (widget.closed()) {
                    widget.open(i);
                }
                if (!widget.active) {
                    widget.activate( i, widget.viewport.children( edge ) );
                    return;
                }
                var next = widget.active[direction + 'All']('.ui-menu-item').eq(0);
                if (next.length) {
                    widget.activate(i, next);
                } else {
                    widget.activate( i, widget.viewport.children( edge ) );
                }
            },

            //
            // protected methods
            //

            register: function(node, options) {
                var widget = this, i = {}; // timepicker instance object

                i.element = $(node);

                if (i.element.data('TimePicker')) {
                    return;
                }

                i.options = $.metadata ? $.extend({}, options, i.element.metadata()) : $.extend({}, options);
                i.widget = widget;

                // proxy functions for the exposed api methods
                $.extend(i, {
                    next: function() {return widget.next(i) ;},
                    previous: function() {return widget.previous(i) ;},
                    first: function() { return widget.first(i) ;},
                    last: function() { return widget.last(i) ;},
                    selected: function() { return widget.selected(i) ;},
                    open: function() { return widget.open(i) ;},
                    close: function(force) { return widget.close(i, force) ;},
                    closed: function() { return widget.closed(i) ;},
                    destroy: function() { return widget.destroy(i) ;},

                    parse: function(str) { return widget.parse(i, str) ;},
                    format: function(time, format) { return widget.format(i, time, format); },
                    getTime: function() { return widget.getTime(i) ;},
                    setTime: function(time, silent) { return widget.setTime(i, time, silent); },
                    option: function(name, value) { return widget.option(i, name, value); }
                });

                widget._setDefaultTime(i);
                widget._addInputEventsHandlers(i);

                i.element.data('TimePicker', i);
            },

            _setDefaultTime: function(i) {
                if (i.options.defaultTime === 'now') {
                    i.setTime(normalize(new Date()));
                } else if (i.options.defaultTime && i.options.defaultTime.getFullYear) {
                    i.setTime(normalize(i.options.defaultTime));
                } else if (i.options.defaultTime) {
                    i.setTime($.fn.timepicker.parseTime(i.options.defaultTime));
                }
            },

            _addInputEventsHandlers: function(i) {
                var widget = this;

                i.element.bind('keydown.timepicker', function(event) {
                    switch (event.which || event.keyCode) {
                        case widget.keyCode.ENTER:
                        case widget.keyCode.NUMPAD_ENTER:
                            event.preventDefault();
                            if (widget.closed()) {
                                i.element.trigger('change.timepicker');
                            } else {
                                widget.select(i, widget.active);
                            }
                            break;
                        case widget.keyCode.UP:
                            i.previous();
                            break;
                        case widget.keyCode.DOWN:
                            i.next();
                            break;
                        default:
                            if (!widget.closed()) {
                                i.close(true);
                            }
                            break;
                    }
                }).bind('focus.timepicker', function() {
                    i.open();
                }).bind('blur.timepicker', function() {
                    i.close();
                }).bind('change.timepicker', function() {
                    if (i.closed()) {
                        i.setTime($.fn.timepicker.parseTime(i.element.val()));
                    }
                });
            },

            select: function(i, item) {
                var widget = this, instance = i === false ? widget.instance : i;
                clearTimeout(widget.closing);
                widget.setTime(instance, $.fn.timepicker.parseTime(item.children('a').text()));
                widget.close(instance, true);
            },

            activate: function(i, item) {
                var widget = this, instance = i === false ? widget.instance : i;

                if (instance !== widget.instance) {
                    return;
                } else {
                    widget.deactivate();
                }

                if (widget._hasScroll()) {
                    var offset = item.offset().top - widget.ui.offset().top,
                        scroll = widget.ui.scrollTop(),
                        height = widget.ui.height();
                    if (offset < 0) {
                        widget.ui.scrollTop(scroll + offset);
                    } else if (offset >= height) {
                        widget.ui.scrollTop(scroll + offset - height + item.height());
                    }
                }

                widget.active = item.eq(0).children('a').addClass('ui-state-hover')
                    .attr('id', 'ui-active-item')
                    .end();
            },

            deactivate: function() {
                var widget = this;
                if (!widget.active) { return; }
                widget.active.children('a').removeClass('ui-state-hover').removeAttr('id');
                widget.active = null;
            },

            /**
             * _activate, _deactivate, first, last, next, previous, _move and
             * _hasScroll were extracted from jQuery UI Menu
             * http://github,com/jquery/jquery-ui/blob/menu/ui/jquery.ui.menu.js
             */

            //
            // public methods
            //

            next: function(i) {
                if (this.closed() || this.instance === i) {
                    this._move(i, 'next', '.ui-menu-item:first');
                }
                return i.element;
            },

            previous: function(i) {
                if (this.closed() || this.instance === i) {
                    this._move(i, 'prev', '.ui-menu-item:last');
                }
                return i.element;
            },

            first: function(i) {
                if (this.instance === i) {
                    return this.active && this.active.prevAll('.ui-menu-item').length === 0;
                }
                return false;
            },

            last: function(i) {
                if (this.instance === i) {
                    return this.active && this.active.nextAll('.ui-menu-item').length === 0;
                }
                return false;
            },

            selected: function(i) {
                if (this.instance === i)  {
                    return this.active ? this.active : null;
                }
                return null;
            },

            open: function(i) {
                var widget = this,
                    selectedTime = i.getTime(),
                    arrange = i.options.dynamic && selectedTime;

                // return if dropdown is disabled
                if (!i.options.dropdown) { return i.element; }

                // if a date is already selected and options.dynamic is true,
                // arrange the items in the list so the first item is
                // cronologically right after the selected date.
                // TODO: set selectedTime
                if (i.rebuild || !i.items || arrange) {
                    i.items = widget._items(i, arrange ? selectedTime : null);
                }

                // remove old li elements keeping associated events, then append
                // the new li elements to the ul
                if (i.rebuild || widget.instance !== i || arrange) {
                    widget.viewport.children().detach();
                    widget.viewport.append(i.items);
                }

                i.rebuild = false;

                // theme
                widget.container.removeClass('ui-helper-hidden ui-timepicker-hidden ui-timepicker-standard ui-timepicker-corners').show();

                switch (i.options.theme) {
                    case 'standard':
                        widget.container.addClass('ui-timepicker-standard');
                        break;
                    case 'standard-rounded-corners':
                        widget.container.addClass('ui-timepicker-standard ui-timepicker-corners');
                        break;
                    default:
                        break;
                }

                /* resize ui */

                // we are hiding the scrollbar in the dropdown menu adding a 40px
                // padding to the wrapper element making the scrollbar appear in the
                // part of the wrapper that's hidden by the container (a DIV).
                if ( ! widget.container.hasClass( 'ui-timepicker-no-scrollbar' ) && ! i.options.scrollbar ) {
                    widget.container.addClass( 'ui-timepicker-no-scrollbar' );
                    widget.viewport.css( { paddingRight: 40 } );
                }

                var containerDecorationHeight = widget.container.outerHeight() - widget.container.height(),
                    zindex = i.options.zindex ? i.options.zindex : i.element.offsetParent().css( 'z-index' ),
                    elementOffset = i.element.offset();

                // position the container right below the element, or as close to as possible.
                widget.container.css( {
                    top: elementOffset.top + i.element.outerHeight(),
                    left: elementOffset.left
                } );

                // then show the container so that the browser can consider the timepicker's
                // height to calculate the page's total height and decide if adding scrollbars
                // is necessary.
                widget.container.show();

                // now we need to calculate the element offset and position the container again.
                // If the browser added scrollbars, the container's original position is not aligned
                // with the element's final position. This step fixes that problem.
                widget.container.css( {
                    left: i.element.offset().left,
                    height: widget.ui.outerHeight() + containerDecorationHeight,
                    width: i.element.outerWidth(),
                    zIndex: zindex + 1,
                    cursor: 'default'
                } );

                var calculatedWidth = widget.container.width() - ( widget.ui.outerWidth() - widget.ui.width() );

                // hardcode ui, viewport and item's width. I couldn't get it to work using CSS only
                widget.ui.css( { width: calculatedWidth } );
                widget.viewport.css( { width: calculatedWidth } );
                i.items.css( { width: calculatedWidth } );

                // XXX: what's this line doing here?
                widget.instance = i;

                // try to match input field's current value with an item in the
                // dropdown
                if (selectedTime) {
                    i.items.each(function() {
                        var item = $(this), time;

                        time = item.data('time-value');

                        if (time.getTime() === selectedTime.getTime()) {
                            widget.activate(i, item);
                            return false;
                        }
                        return true;
                    });
                } else {
                    widget.deactivate(i);
                }

                // don't break the chain
                return i.element;
            },

            close: function(i, force) {
                var widget = this;
                if (widget.closed() || force) {
                    clearTimeout(widget.closing);
                    if (widget.instance === i) {
                        widget.container.addClass('ui-helper-hidden ui-timepicker-hidden').hide();
                        widget.ui.scrollTop(0);
                        widget.ui.children().removeClass('ui-state-hover');
                    }
                } else {
                    widget.closing = setTimeout(function() {
                        widget.close(i, true);
                    }, 150);
                }
                return i.element;
            },

            closed: function() {
                return this.ui.is(':hidden');
            },

            destroy: function(i) {
                var widget = this;
                widget.close(i, true);
                return i.element.unbind('.timepicker').data('TimePicker', null);
            },

            //

            parse: function(i, str) {
                return $.fn.timepicker.parseTime(str);
            },

            format: function(i, time, format) {
                format = format || i.options.timeFormat;
                return $.fn.timepicker.formatTime(format, time);
            },

            getTime: function(i) {
                var widget = this,
                    current = $.fn.timepicker.parseTime(i.element.val());

                // if current value is not valid, we return null.
                // stored Date object is ignored, because the current value
                // (valid or invalid) always takes priority
                if (current instanceof Date && !widget._isValidTime(i, current)) {
                    return null;
                } else if (current instanceof Date && i.selectedTime) {
                    // if the textfield's value and the stored Date object
                    // have the same representation using current format
                    // we prefer the stored Date object to avoid unnecesary
                    // lost of precision.
                    if (i.format(current) === i.format(i.selectedTime)) {
                        return i.selectedTime;
                    } else {
                        return current;
                    }
                } else if (current instanceof Date) {
                    return current;
                } else {
                    return null;
                }
            },

            setTime: function(i, time, silent) {
                var widget = this, previous = i.selectedTime;

                if (typeof time === 'string') {
                    time = i.parse(time);
                }

                if (time && time.getMinutes && widget._isValidTime(i, time)) {
                    time = normalize(time);
                    i.selectedTime = time;
                    i.element.val(i.format(time, i.options.timeFormat));

                    // TODO: add documentaion about setTime being chainable
                    if (silent) { return i; }
                } else {
                    i.selectedTime = null;
                }

                // custom change event and change callback
                // TODO: add documentation about this event
                if (previous !== null || i.selectedTime !== null) {
                    i.element.trigger('time-change', [time]);
                    if ($.isFunction(i.options.change)) {
                        i.options.change.apply(i.element, [time]);
                    }
                }

                return i.element;
            },

            option: function(i, name, value) {
                if (typeof value === 'undefined') {
                    return i.options[name];
                }

                var time = i.getTime(),
                    options, destructive;

                if (typeof name === 'string') {
                    options = {};
                    options[name] = value;
                } else {
                    options = name;
                }

                // some options require rebuilding the dropdown items
                destructive = ['minHour', 'minMinutes', 'minTime',
                    'maxHour', 'maxMinutes', 'maxTime',
                    'startHour', 'startMinutes', 'startTime',
                    'timeFormat', 'interval', 'dropdown'];


                $.each(options, function(name) {
                    i.options[name] = options[name];
                    i.rebuild = i.rebuild || $.inArray(name, destructive) > -1;
                });

                if (i.rebuild) {
                    i.setTime(time);
                }
            }
        };

        $.TimePicker.defaults =  {
            timeFormat: 'hh:mm p',
            minHour: null,
            minMinutes: null,
            minTime: null,
            maxHour: null,
            maxMinutes: null,
            maxTime: null,
            startHour: null,
            startMinutes: null,
            startTime: null,
            interval: 30,
            dynamic: true,
            theme: 'standard',
            zindex: null,
            dropdown: true,
            scrollbar: false,
            // callbacks
            change: function(/*time*/) {}
        };

        $.TimePicker.methods = {
            chainable: [
                'next',
                'previous',
                'open',
                'close',
                'destroy',
                'setTime'
            ]
        };

        $.fn.timepicker = function(options) {
            // support calling API methods using the following syntax:
            //   $(...).timepicker('parse', '11p');
            if (typeof options === 'string') {
                var args = Array.prototype.slice.call(arguments, 1),
                    method, result;

                // chainable API methods
                if (options === 'option' && arguments.length > 2) {
                    method = 'each';
                } else if ($.inArray(options, $.TimePicker.methods.chainable) !== -1) {
                    method = 'each';
                    // API methods that return a value
                } else {
                    method = 'map';
                }

                result = this[method](function() {
                    var element = $(this), i = element.data('TimePicker');
                    if (typeof i === 'object') {
                        return i[options].apply(i, args);
                    }
                });

                if (method === 'map' && this.length === 1) {
                    return $.makeArray(result).shift();
                } else if (method === 'map') {
                    return $.makeArray(result);
                } else {
                    return result;
                }
            }

            // calling the constructor again on a jQuery object with a single
            // element returns a reference to a TimePicker object.
            if (this.length === 1 && this.data('TimePicker')) {
                return this.data('TimePicker');
            }

            var globals = $.extend({}, $.TimePicker.defaults, options);

            return this.each(function() {
                $.TimePicker.instance(this).register(this, globals);
            });
        };

        /**
         * TODO: documentation
         */
        $.fn.timepicker.formatTime = function(format, time) {
            var hours = time.getHours(),
                hours12 = hours % 12,
                minutes = time.getMinutes(),
                seconds = time.getSeconds(),
                replacements = {
                    hh: pad((hours12 === 0 ? 12 : hours12).toString(), '0', 2),
                    HH: pad(hours.toString(), '0', 2),
                    mm: pad(minutes.toString(), '0', 2),
                    ss: pad(seconds.toString(), '0', 2),
                    h: (hours12 === 0 ? 12 : hours12),
                    H: hours,
                    m: minutes,
                    s: seconds,
                    p: hours > 11 ? 'PM' : 'AM'
                },
                str = format, k = '';
            for (k in replacements) {
                if (replacements.hasOwnProperty(k)) {
                    str = str.replace(new RegExp(k,'g'), replacements[k]);
                }
            }
            // replacements is not guaranteed to be order and the 'p' can cause problems
            str = str.replace(new RegExp('a','g'), hours > 11 ? 'pm' : 'am');
            return str;
        };

        /**
         * Convert a string representing a given time into a Date object.
         *
         * The Date object will have attributes others than hours, minutes and
         * seconds set to current local time values. The function will return
         * false if given string can't be converted.
         *
         * If there is an 'a' in the string we set am to true, if there is a 'p'
         * we set pm to true, if both are present only am is setted to true.
         *
         * All non-digit characters are removed from the string before trying to
         * parse the time.
         *
         * ''       can't be converted and the function returns false.
         * '1'      is converted to     01:00:00 am
         * '11'     is converted to     11:00:00 am
         * '111'    is converted to     01:11:00 am
         * '1111'   is converted to     11:11:00 am
         * '11111'  is converted to     01:11:11 am
         * '111111' is converted to     11:11:11 am
         *
         * Only the first six (or less) characters are considered.
         *
         * Special case:
         *
         * When hours is greater than 24 and the last digit is less or equal than 6, and minutes
         * and seconds are less or equal than 60, we append a trailing zero and
         * start parsing process again. Examples:
         *
         * '95' is treated as '950' and converted to 09:50:00 am
         * '46' is treated as '460' and converted to 05:00:00 am
         * '57' can't be converted and the function returns false.
         *
         * For a detailed list of supported formats check the unit tests at
         * http://github.com/wvega/timepicker/tree/master/tests/
         */
        $.fn.timepicker.parseTime = (function() {
            var patterns = [
                    // 1, 12, 123, 1234, 12345, 123456
                    [/^(\d+)$/, '$1'],
                    // :1, :2, :3, :4 ... :9
                    [/^:(\d)$/, '$10'],
                    // :1, :12, :123, :1234 ...
                    [/^:(\d+)/, '$1'],
                    // 6:06, 5:59, 5:8
                    [/^(\d):([7-9])$/, '0$10$2'],
                    [/^(\d):(\d\d)$/, '$1$2'],
                    [/^(\d):(\d{1,})$/, '0$1$20'],
                    // 10:8, 10:10, 10:34
                    [/^(\d\d):([7-9])$/, '$10$2'],
                    [/^(\d\d):(\d)$/, '$1$20'],
                    [/^(\d\d):(\d*)$/, '$1$2'],
                    // 123:4, 1234:456
                    [/^(\d{3,}):(\d)$/, '$10$2'],
                    [/^(\d{3,}):(\d{2,})/, '$1$2'],
                    //
                    [/^(\d):(\d):(\d)$/, '0$10$20$3'],
                    [/^(\d{1,2}):(\d):(\d\d)/, '$10$2$3']
                ],
                length = patterns.length;

            return function(str) {
                var time = normalize(new Date()),
                    am = false, pm = false, h = false, m = false, s = false;

                if (typeof str === 'undefined' || !str.toLowerCase) { return null; }

                str = str.toLowerCase();
                am = /a/.test(str);
                pm = am ? false : /p/.test(str);
                str = str.replace(/[^0-9:]/g, '').replace(/:+/g, ':');

                for (var k = 0; k < length; k = k + 1) {
                    if (patterns[k][0].test(str)) {
                        str = str.replace(patterns[k][0], patterns[k][1]);
                        break;
                    }
                }
                str = str.replace(/:/g, '');

                if (str.length === 1) {
                    h = str;
                } else if (str.length === 2) {
                    h = str;
                } else if (str.length === 3 || str.length === 5) {
                    h = str.substr(0, 1);
                    m = str.substr(1, 2);
                    s = str.substr(3, 2);
                } else if (str.length === 4 || str.length > 5) {
                    h = str.substr(0, 2);
                    m = str.substr(2, 2);
                    s = str.substr(4, 2);
                }

                if (str.length > 0 && str.length < 5) {
                    if (str.length < 3) {
                        m = 0;
                    }
                    s = 0;
                }

                if (h === false || m === false || s === false) {
                    return false;
                }

                h = parseInt(h, 10);
                m = parseInt(m, 10);
                s = parseInt(s, 10);

                if (am && h === 12) {
                    h = 0;
                } else if (pm && h < 12) {
                    h = h + 12;
                }

                if (h > 24) {
                    if (str.length >= 6) {
                        return $.fn.timepicker.parseTime(str.substr(0,5));
                    } else {
                        return $.fn.timepicker.parseTime(str + '0' + (am ? 'a' : '') + (pm ? 'p' : ''));
                    }
                } else {
                    time.setHours(h, m, s);
                    return time;
                }
            };
        })();
    })(jQuery);
}
;!function(a,b,c,d){"use strict";function k(a,b,c){return setTimeout(q(a,c),b)}function l(a,b,c){return Array.isArray(a)?(m(a,c[b],c),!0):!1}function m(a,b,c){var e;if(a)if(a.forEach)a.forEach(b,c);else if(a.length!==d)for(e=0;e<a.length;)b.call(c,a[e],e,a),e++;else for(e in a)a.hasOwnProperty(e)&&b.call(c,a[e],e,a)}function n(a,b,c){for(var e=Object.keys(b),f=0;f<e.length;)(!c||c&&a[e[f]]===d)&&(a[e[f]]=b[e[f]]),f++;return a}function o(a,b){return n(a,b,!0)}function p(a,b,c){var e,d=b.prototype;e=a.prototype=Object.create(d),e.constructor=a,e._super=d,c&&n(e,c)}function q(a,b){return function(){return a.apply(b,arguments)}}function r(a,b){return typeof a==g?a.apply(b?b[0]||d:d,b):a}function s(a,b){return a===d?b:a}function t(a,b,c){m(x(b),function(b){a.addEventListener(b,c,!1)})}function u(a,b,c){m(x(b),function(b){a.removeEventListener(b,c,!1)})}function v(a,b){for(;a;){if(a==b)return!0;a=a.parentNode}return!1}function w(a,b){return a.indexOf(b)>-1}function x(a){return a.trim().split(/\s+/g)}function y(a,b,c){if(a.indexOf&&!c)return a.indexOf(b);for(var d=0;d<a.length;){if(c&&a[d][c]==b||!c&&a[d]===b)return d;d++}return-1}function z(a){return Array.prototype.slice.call(a,0)}function A(a,b,c){for(var d=[],e=[],f=0;f<a.length;){var g=b?a[f][b]:a[f];y(e,g)<0&&d.push(a[f]),e[f]=g,f++}return c&&(d=b?d.sort(function(a,c){return a[b]>c[b]}):d.sort()),d}function B(a,b){for(var c,f,g=b[0].toUpperCase()+b.slice(1),h=0;h<e.length;){if(c=e[h],f=c?c+g:b,f in a)return f;h++}return d}function D(){return C++}function E(a){var b=a.ownerDocument;return b.defaultView||b.parentWindow}function ab(a,b){var c=this;this.manager=a,this.callback=b,this.element=a.element,this.target=a.options.inputTarget,this.domHandler=function(b){r(a.options.enable,[a])&&c.handler(b)},this.init()}function bb(a){var b,c=a.options.inputClass;return b=c?c:H?wb:I?Eb:G?Gb:rb,new b(a,cb)}function cb(a,b,c){var d=c.pointers.length,e=c.changedPointers.length,f=b&O&&0===d-e,g=b&(Q|R)&&0===d-e;c.isFirst=!!f,c.isFinal=!!g,f&&(a.session={}),c.eventType=b,db(a,c),a.emit("hammer.input",c),a.recognize(c),a.session.prevInput=c}function db(a,b){var c=a.session,d=b.pointers,e=d.length;c.firstInput||(c.firstInput=gb(b)),e>1&&!c.firstMultiple?c.firstMultiple=gb(b):1===e&&(c.firstMultiple=!1);var f=c.firstInput,g=c.firstMultiple,h=g?g.center:f.center,i=b.center=hb(d);b.timeStamp=j(),b.deltaTime=b.timeStamp-f.timeStamp,b.angle=lb(h,i),b.distance=kb(h,i),eb(c,b),b.offsetDirection=jb(b.deltaX,b.deltaY),b.scale=g?nb(g.pointers,d):1,b.rotation=g?mb(g.pointers,d):0,fb(c,b);var k=a.element;v(b.srcEvent.target,k)&&(k=b.srcEvent.target),b.target=k}function eb(a,b){var c=b.center,d=a.offsetDelta||{},e=a.prevDelta||{},f=a.prevInput||{};(b.eventType===O||f.eventType===Q)&&(e=a.prevDelta={x:f.deltaX||0,y:f.deltaY||0},d=a.offsetDelta={x:c.x,y:c.y}),b.deltaX=e.x+(c.x-d.x),b.deltaY=e.y+(c.y-d.y)}function fb(a,b){var f,g,h,j,c=a.lastInterval||b,e=b.timeStamp-c.timeStamp;if(b.eventType!=R&&(e>N||c.velocity===d)){var k=c.deltaX-b.deltaX,l=c.deltaY-b.deltaY,m=ib(e,k,l);g=m.x,h=m.y,f=i(m.x)>i(m.y)?m.x:m.y,j=jb(k,l),a.lastInterval=b}else f=c.velocity,g=c.velocityX,h=c.velocityY,j=c.direction;b.velocity=f,b.velocityX=g,b.velocityY=h,b.direction=j}function gb(a){for(var b=[],c=0;c<a.pointers.length;)b[c]={clientX:h(a.pointers[c].clientX),clientY:h(a.pointers[c].clientY)},c++;return{timeStamp:j(),pointers:b,center:hb(b),deltaX:a.deltaX,deltaY:a.deltaY}}function hb(a){var b=a.length;if(1===b)return{x:h(a[0].clientX),y:h(a[0].clientY)};for(var c=0,d=0,e=0;b>e;)c+=a[e].clientX,d+=a[e].clientY,e++;return{x:h(c/b),y:h(d/b)}}function ib(a,b,c){return{x:b/a||0,y:c/a||0}}function jb(a,b){return a===b?S:i(a)>=i(b)?a>0?T:U:b>0?V:W}function kb(a,b,c){c||(c=$);var d=b[c[0]]-a[c[0]],e=b[c[1]]-a[c[1]];return Math.sqrt(d*d+e*e)}function lb(a,b,c){c||(c=$);var d=b[c[0]]-a[c[0]],e=b[c[1]]-a[c[1]];return 180*Math.atan2(e,d)/Math.PI}function mb(a,b){return lb(b[1],b[0],_)-lb(a[1],a[0],_)}function nb(a,b){return kb(b[0],b[1],_)/kb(a[0],a[1],_)}function rb(){this.evEl=pb,this.evWin=qb,this.allow=!0,this.pressed=!1,ab.apply(this,arguments)}function wb(){this.evEl=ub,this.evWin=vb,ab.apply(this,arguments),this.store=this.manager.session.pointerEvents=[]}function Ab(){this.evTarget=yb,this.evWin=zb,this.started=!1,ab.apply(this,arguments)}function Bb(a,b){var c=z(a.touches),d=z(a.changedTouches);return b&(Q|R)&&(c=A(c.concat(d),"identifier",!0)),[c,d]}function Eb(){this.evTarget=Db,this.targetIds={},ab.apply(this,arguments)}function Fb(a,b){var c=z(a.touches),d=this.targetIds;if(b&(O|P)&&1===c.length)return d[c[0].identifier]=!0,[c,c];var e,f,g=z(a.changedTouches),h=[],i=this.target;if(f=c.filter(function(a){return v(a.target,i)}),b===O)for(e=0;e<f.length;)d[f[e].identifier]=!0,e++;for(e=0;e<g.length;)d[g[e].identifier]&&h.push(g[e]),b&(Q|R)&&delete d[g[e].identifier],e++;return h.length?[A(f.concat(h),"identifier",!0),h]:void 0}function Gb(){ab.apply(this,arguments);var a=q(this.handler,this);this.touch=new Eb(this.manager,a),this.mouse=new rb(this.manager,a)}function Pb(a,b){this.manager=a,this.set(b)}function Qb(a){if(w(a,Mb))return Mb;var b=w(a,Nb),c=w(a,Ob);return b&&c?Nb+" "+Ob:b||c?b?Nb:Ob:w(a,Lb)?Lb:Kb}function Yb(a){this.id=D(),this.manager=null,this.options=o(a||{},this.defaults),this.options.enable=s(this.options.enable,!0),this.state=Rb,this.simultaneous={},this.requireFail=[]}function Zb(a){return a&Wb?"cancel":a&Ub?"end":a&Tb?"move":a&Sb?"start":""}function $b(a){return a==W?"down":a==V?"up":a==T?"left":a==U?"right":""}function _b(a,b){var c=b.manager;return c?c.get(a):a}function ac(){Yb.apply(this,arguments)}function bc(){ac.apply(this,arguments),this.pX=null,this.pY=null}function cc(){ac.apply(this,arguments)}function dc(){Yb.apply(this,arguments),this._timer=null,this._input=null}function ec(){ac.apply(this,arguments)}function fc(){ac.apply(this,arguments)}function gc(){Yb.apply(this,arguments),this.pTime=!1,this.pCenter=!1,this._timer=null,this._input=null,this.count=0}function hc(a,b){return b=b||{},b.recognizers=s(b.recognizers,hc.defaults.preset),new kc(a,b)}function kc(a,b){b=b||{},this.options=o(b,hc.defaults),this.options.inputTarget=this.options.inputTarget||a,this.handlers={},this.session={},this.recognizers=[],this.element=a,this.input=bb(this),this.touchAction=new Pb(this,this.options.touchAction),lc(this,!0),m(b.recognizers,function(a){var b=this.add(new a[0](a[1]));a[2]&&b.recognizeWith(a[2]),a[3]&&b.requireFailure(a[3])},this)}function lc(a,b){var c=a.element;m(a.options.cssProps,function(a,d){c.style[B(c.style,d)]=b?a:""})}function mc(a,c){var d=b.createEvent("Event");d.initEvent(a,!0,!0),d.gesture=c,c.target.dispatchEvent(d)}var e=["","webkit","moz","MS","ms","o"],f=b.createElement("div"),g="function",h=Math.round,i=Math.abs,j=Date.now,C=1,F=/mobile|tablet|ip(ad|hone|od)|android/i,G="ontouchstart"in a,H=B(a,"PointerEvent")!==d,I=G&&F.test(navigator.userAgent),J="touch",K="pen",L="mouse",M="kinect",N=25,O=1,P=2,Q=4,R=8,S=1,T=2,U=4,V=8,W=16,X=T|U,Y=V|W,Z=X|Y,$=["x","y"],_=["clientX","clientY"];ab.prototype={handler:function(){},init:function(){this.evEl&&t(this.element,this.evEl,this.domHandler),this.evTarget&&t(this.target,this.evTarget,this.domHandler),this.evWin&&t(E(this.element),this.evWin,this.domHandler)},destroy:function(){this.evEl&&u(this.element,this.evEl,this.domHandler),this.evTarget&&u(this.target,this.evTarget,this.domHandler),this.evWin&&u(E(this.element),this.evWin,this.domHandler)}};var ob={mousedown:O,mousemove:P,mouseup:Q},pb="mousedown",qb="mousemove mouseup";p(rb,ab,{handler:function(a){var b=ob[a.type];b&O&&0===a.button&&(this.pressed=!0),b&P&&1!==a.which&&(b=Q),this.pressed&&this.allow&&(b&Q&&(this.pressed=!1),this.callback(this.manager,b,{pointers:[a],changedPointers:[a],pointerType:L,srcEvent:a}))}});var sb={pointerdown:O,pointermove:P,pointerup:Q,pointercancel:R,pointerout:R},tb={2:J,3:K,4:L,5:M},ub="pointerdown",vb="pointermove pointerup pointercancel";a.MSPointerEvent&&(ub="MSPointerDown",vb="MSPointerMove MSPointerUp MSPointerCancel"),p(wb,ab,{handler:function(a){var b=this.store,c=!1,d=a.type.toLowerCase().replace("ms",""),e=sb[d],f=tb[a.pointerType]||a.pointerType,g=f==J,h=y(b,a.pointerId,"pointerId");e&O&&(0===a.button||g)?0>h&&(b.push(a),h=b.length-1):e&(Q|R)&&(c=!0),0>h||(b[h]=a,this.callback(this.manager,e,{pointers:b,changedPointers:[a],pointerType:f,srcEvent:a}),c&&b.splice(h,1))}});var xb={touchstart:O,touchmove:P,touchend:Q,touchcancel:R},yb="touchstart",zb="touchstart touchmove touchend touchcancel";p(Ab,ab,{handler:function(a){var b=xb[a.type];if(b===O&&(this.started=!0),this.started){var c=Bb.call(this,a,b);b&(Q|R)&&0===c[0].length-c[1].length&&(this.started=!1),this.callback(this.manager,b,{pointers:c[0],changedPointers:c[1],pointerType:J,srcEvent:a})}}});var Cb={touchstart:O,touchmove:P,touchend:Q,touchcancel:R},Db="touchstart touchmove touchend touchcancel";p(Eb,ab,{handler:function(a){var b=Cb[a.type],c=Fb.call(this,a,b);c&&this.callback(this.manager,b,{pointers:c[0],changedPointers:c[1],pointerType:J,srcEvent:a})}}),p(Gb,ab,{handler:function(a,b,c){var d=c.pointerType==J,e=c.pointerType==L;if(d)this.mouse.allow=!1;else if(e&&!this.mouse.allow)return;b&(Q|R)&&(this.mouse.allow=!0),this.callback(a,b,c)},destroy:function(){this.touch.destroy(),this.mouse.destroy()}});var Hb=B(f.style,"touchAction"),Ib=Hb!==d,Jb="compute",Kb="auto",Lb="manipulation",Mb="none",Nb="pan-x",Ob="pan-y";Pb.prototype={set:function(a){a==Jb&&(a=this.compute()),Ib&&(this.manager.element.style[Hb]=a),this.actions=a.toLowerCase().trim()},update:function(){this.set(this.manager.options.touchAction)},compute:function(){var a=[];return m(this.manager.recognizers,function(b){r(b.options.enable,[b])&&(a=a.concat(b.getTouchAction()))}),Qb(a.join(" "))},preventDefaults:function(a){if(!Ib){var b=a.srcEvent,c=a.offsetDirection;if(this.manager.session.prevented)return b.preventDefault(),void 0;var d=this.actions,e=w(d,Mb),f=w(d,Ob),g=w(d,Nb);return e||f&&c&X||g&&c&Y?this.preventSrc(b):void 0}},preventSrc:function(a){this.manager.session.prevented=!0,a.preventDefault()}};var Rb=1,Sb=2,Tb=4,Ub=8,Vb=Ub,Wb=16,Xb=32;Yb.prototype={defaults:{},set:function(a){return n(this.options,a),this.manager&&this.manager.touchAction.update(),this},recognizeWith:function(a){if(l(a,"recognizeWith",this))return this;var b=this.simultaneous;return a=_b(a,this),b[a.id]||(b[a.id]=a,a.recognizeWith(this)),this},dropRecognizeWith:function(a){return l(a,"dropRecognizeWith",this)?this:(a=_b(a,this),delete this.simultaneous[a.id],this)},requireFailure:function(a){if(l(a,"requireFailure",this))return this;var b=this.requireFail;return a=_b(a,this),-1===y(b,a)&&(b.push(a),a.requireFailure(this)),this},dropRequireFailure:function(a){if(l(a,"dropRequireFailure",this))return this;a=_b(a,this);var b=y(this.requireFail,a);return b>-1&&this.requireFail.splice(b,1),this},hasRequireFailures:function(){return this.requireFail.length>0},canRecognizeWith:function(a){return!!this.simultaneous[a.id]},emit:function(a){function d(d){b.manager.emit(b.options.event+(d?Zb(c):""),a)}var b=this,c=this.state;Ub>c&&d(!0),d(),c>=Ub&&d(!0)},tryEmit:function(a){return this.canEmit()?this.emit(a):(this.state=Xb,void 0)},canEmit:function(){for(var a=0;a<this.requireFail.length;){if(!(this.requireFail[a].state&(Xb|Rb)))return!1;a++}return!0},recognize:function(a){var b=n({},a);return r(this.options.enable,[this,b])?(this.state&(Vb|Wb|Xb)&&(this.state=Rb),this.state=this.process(b),this.state&(Sb|Tb|Ub|Wb)&&this.tryEmit(b),void 0):(this.reset(),this.state=Xb,void 0)},process:function(){},getTouchAction:function(){},reset:function(){}},p(ac,Yb,{defaults:{pointers:1},attrTest:function(a){var b=this.options.pointers;return 0===b||a.pointers.length===b},process:function(a){var b=this.state,c=a.eventType,d=b&(Sb|Tb),e=this.attrTest(a);return d&&(c&R||!e)?b|Wb:d||e?c&Q?b|Ub:b&Sb?b|Tb:Sb:Xb}}),p(bc,ac,{defaults:{event:"pan",threshold:10,pointers:1,direction:Z},getTouchAction:function(){var a=this.options.direction,b=[];return a&X&&b.push(Ob),a&Y&&b.push(Nb),b},directionTest:function(a){var b=this.options,c=!0,d=a.distance,e=a.direction,f=a.deltaX,g=a.deltaY;return e&b.direction||(b.direction&X?(e=0===f?S:0>f?T:U,c=f!=this.pX,d=Math.abs(a.deltaX)):(e=0===g?S:0>g?V:W,c=g!=this.pY,d=Math.abs(a.deltaY))),a.direction=e,c&&d>b.threshold&&e&b.direction},attrTest:function(a){return ac.prototype.attrTest.call(this,a)&&(this.state&Sb||!(this.state&Sb)&&this.directionTest(a))},emit:function(a){this.pX=a.deltaX,this.pY=a.deltaY;var b=$b(a.direction);b&&this.manager.emit(this.options.event+b,a),this._super.emit.call(this,a)}}),p(cc,ac,{defaults:{event:"pinch",threshold:0,pointers:2},getTouchAction:function(){return[Mb]},attrTest:function(a){return this._super.attrTest.call(this,a)&&(Math.abs(a.scale-1)>this.options.threshold||this.state&Sb)},emit:function(a){if(this._super.emit.call(this,a),1!==a.scale){var b=a.scale<1?"in":"out";this.manager.emit(this.options.event+b,a)}}}),p(dc,Yb,{defaults:{event:"press",pointers:1,time:500,threshold:5},getTouchAction:function(){return[Kb]},process:function(a){var b=this.options,c=a.pointers.length===b.pointers,d=a.distance<b.threshold,e=a.deltaTime>b.time;if(this._input=a,!d||!c||a.eventType&(Q|R)&&!e)this.reset();else if(a.eventType&O)this.reset(),this._timer=k(function(){this.state=Vb,this.tryEmit()},b.time,this);else if(a.eventType&Q)return Vb;return Xb},reset:function(){clearTimeout(this._timer)},emit:function(a){this.state===Vb&&(a&&a.eventType&Q?this.manager.emit(this.options.event+"up",a):(this._input.timeStamp=j(),this.manager.emit(this.options.event,this._input)))}}),p(ec,ac,{defaults:{event:"rotate",threshold:0,pointers:2},getTouchAction:function(){return[Mb]},attrTest:function(a){return this._super.attrTest.call(this,a)&&(Math.abs(a.rotation)>this.options.threshold||this.state&Sb)}}),p(fc,ac,{defaults:{event:"swipe",threshold:10,velocity:.65,direction:X|Y,pointers:1},getTouchAction:function(){return bc.prototype.getTouchAction.call(this)},attrTest:function(a){var c,b=this.options.direction;return b&(X|Y)?c=a.velocity:b&X?c=a.velocityX:b&Y&&(c=a.velocityY),this._super.attrTest.call(this,a)&&b&a.direction&&a.distance>this.options.threshold&&i(c)>this.options.velocity&&a.eventType&Q},emit:function(a){var b=$b(a.direction);b&&this.manager.emit(this.options.event+b,a),this.manager.emit(this.options.event,a)}}),p(gc,Yb,{defaults:{event:"tap",pointers:1,taps:1,interval:300,time:250,threshold:2,posThreshold:10},getTouchAction:function(){return[Lb]},process:function(a){var b=this.options,c=a.pointers.length===b.pointers,d=a.distance<b.threshold,e=a.deltaTime<b.time;if(this.reset(),a.eventType&O&&0===this.count)return this.failTimeout();if(d&&e&&c){if(a.eventType!=Q)return this.failTimeout();var f=this.pTime?a.timeStamp-this.pTime<b.interval:!0,g=!this.pCenter||kb(this.pCenter,a.center)<b.posThreshold;this.pTime=a.timeStamp,this.pCenter=a.center,g&&f?this.count+=1:this.count=1,this._input=a;var h=this.count%b.taps;if(0===h)return this.hasRequireFailures()?(this._timer=k(function(){this.state=Vb,this.tryEmit()},b.interval,this),Sb):Vb}return Xb},failTimeout:function(){return this._timer=k(function(){this.state=Xb},this.options.interval,this),Xb},reset:function(){clearTimeout(this._timer)},emit:function(){this.state==Vb&&(this._input.tapCount=this.count,this.manager.emit(this.options.event,this._input))}}),hc.VERSION="2.0.4",hc.defaults={domEvents:!1,touchAction:Jb,enable:!0,inputTarget:null,inputClass:null,preset:[[ec,{enable:!1}],[cc,{enable:!1},["rotate"]],[fc,{direction:X}],[bc,{direction:X},["swipe"]],[gc],[gc,{event:"doubletap",taps:2},["tap"]],[dc]],cssProps:{userSelect:"default",touchSelect:"none",touchCallout:"none",contentZooming:"none",userDrag:"none",tapHighlightColor:"rgba(0,0,0,0)"}};var ic=1,jc=2;kc.prototype={set:function(a){return n(this.options,a),a.touchAction&&this.touchAction.update(),a.inputTarget&&(this.input.destroy(),this.input.target=a.inputTarget,this.input.init()),this},stop:function(a){this.session.stopped=a?jc:ic},recognize:function(a){var b=this.session;if(!b.stopped){this.touchAction.preventDefaults(a);var c,d=this.recognizers,e=b.curRecognizer;(!e||e&&e.state&Vb)&&(e=b.curRecognizer=null);for(var f=0;f<d.length;)c=d[f],b.stopped===jc||e&&c!=e&&!c.canRecognizeWith(e)?c.reset():c.recognize(a),!e&&c.state&(Sb|Tb|Ub)&&(e=b.curRecognizer=c),f++}},get:function(a){if(a instanceof Yb)return a;for(var b=this.recognizers,c=0;c<b.length;c++)if(b[c].options.event==a)return b[c];return null},add:function(a){if(l(a,"add",this))return this;var b=this.get(a.options.event);return b&&this.remove(b),this.recognizers.push(a),a.manager=this,this.touchAction.update(),a},remove:function(a){if(l(a,"remove",this))return this;var b=this.recognizers;return a=this.get(a),b.splice(y(b,a),1),this.touchAction.update(),this},on:function(a,b){var c=this.handlers;return m(x(a),function(a){c[a]=c[a]||[],c[a].push(b)}),this},off:function(a,b){var c=this.handlers;return m(x(a),function(a){b?c[a].splice(y(c[a],b),1):delete c[a]}),this},emit:function(a,b){this.options.domEvents&&mc(a,b);var c=this.handlers[a]&&this.handlers[a].slice();if(c&&c.length){b.type=a,b.preventDefault=function(){b.srcEvent.preventDefault()};for(var d=0;d<c.length;)c[d](b),d++}},destroy:function(){this.element&&lc(this,!1),this.handlers={},this.session={},this.input.destroy(),this.element=null}},n(hc,{INPUT_START:O,INPUT_MOVE:P,INPUT_END:Q,INPUT_CANCEL:R,STATE_POSSIBLE:Rb,STATE_BEGAN:Sb,STATE_CHANGED:Tb,STATE_ENDED:Ub,STATE_RECOGNIZED:Vb,STATE_CANCELLED:Wb,STATE_FAILED:Xb,DIRECTION_NONE:S,DIRECTION_LEFT:T,DIRECTION_RIGHT:U,DIRECTION_UP:V,DIRECTION_DOWN:W,DIRECTION_HORIZONTAL:X,DIRECTION_VERTICAL:Y,DIRECTION_ALL:Z,Manager:kc,Input:ab,TouchAction:Pb,TouchInput:Eb,MouseInput:rb,PointerEventInput:wb,TouchMouseInput:Gb,SingleTouchInput:Ab,Recognizer:Yb,AttrRecognizer:ac,Tap:gc,Pan:bc,Swipe:fc,Pinch:cc,Rotate:ec,Press:dc,on:t,off:u,each:m,merge:o,extend:n,inherit:p,bindFn:q,prefixed:B}),typeof define==g&&define.amd?define(function(){return hc}):"undefined"!=typeof module&&module.exports?module.exports=hc:a[c]=hc}(window,document,"Hammer");;(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery', 'hammerjs'], factory);
    } else if (typeof exports === 'object') {
        factory(require('jquery'), require('hammerjs'));
    } else {
        factory(jQuery, Hammer);
    }
}(function($, Hammer) {
    function hammerify(el, options) {
        var $el = $(el);
        if(!$el.data("hammer")) {
            $el.data("hammer", new Hammer($el[0], options));
        }
    }

    $.fn.hammer = function(options) {
        return this.each(function() {
            hammerify(this, options);
        });
    };

    // extend the emit method to also trigger jQuery events
    Hammer.Manager.prototype.emit = (function(originalEmit) {
        return function(type, data) {
            originalEmit.call(this, type, data);
            $(this.element).trigger({
                type: type,
                gesture: data
            });
        };
    })(Hammer.Manager.prototype.emit);
}));
;Materialize.toast = function ( message, displayLength, className, completeCallback, position ) {
	className = className || '';
	position = position || 'top';

	var container = document.getElementById( 'tvd-toast-container-' + position );

	// Create toast container if it does not exist
	if ( container === null ) {
		// create notification container
		container = document.createElement( 'div' );
		container.id = 'tvd-toast-container-' + position;
		container.className = 'tvd-toast-container';
		document.body.appendChild( container );
	}

	// Select and append toast
	var newToast = createToast( message );

	// only append toast if message is not undefined
	if ( message ) {
		container.appendChild( newToast );
	}

	newToast.style.top = position === 'top' ? '-35px' : '35px';
	newToast.style.opacity = 0;

	var out_top = position === 'top' ? '-35px' : '35px';

	// Animate toast in
	Vel( newToast, {"top": "0px", opacity: 1}, {
		duration: 300,
		easing: 'easeOutCubic',
		queue: false
	} );

	// Allows timer to be pause while being panned
	var timeLeft = displayLength;
	var counterInterval = setInterval( function () {

		if ( newToast.parentNode === null ) {
			window.clearInterval( counterInterval );
		}

		// If toast is not being dragged, decrease its time remaining
		if ( ! newToast.classList.contains( 'panning' ) ) {
			timeLeft -= 20;
		}

		if ( timeLeft <= 0 ) {
			// Animate toast out
			Vel( newToast, {"opacity": 0, top: out_top}, {
				duration: 375,
				easing: 'easeOutExpo',
				queue: false,
				complete: function () {
					// Call the optional callback
					if ( typeof completeCallback === "function" ) {
						completeCallback();
					}
					// Remove toast after it times out
					this[0].parentNode.removeChild( this[0] );
				}
			} );
			window.clearInterval( counterInterval );
		}
	}, 20 );


	function createToast( html ) {

		// Create toast
		var toast = document.createElement( 'div' );
		toast.classList.add( 'tvd-toast' );
		if ( className ) {
			var classes = className.split( ' ' );

			for ( var i = 0, count = classes.length; i < count; i ++ ) {
				toast.classList.add( classes[i] );
			}
		}
		// If type of parameter is HTML Element
		if ( typeof HTMLElement === "object" ? html instanceof HTMLElement : html && typeof html === "object" && html !== null && html.nodeType === 1 && typeof html.nodeName === "string"
		) {
			toast.appendChild( html );
		}
		else if ( html instanceof jQuery ) {
			// Check if it is jQuery object
			toast.appendChild( html[0] );
		}
		else {
			// Insert as text;
			toast.innerHTML = html;
		}
		// Bind hammer
		var hammerHandler = new Hammer( toast, {prevent_default: false} );
		hammerHandler.on( 'pan', function ( e ) {
			var deltaX = e.deltaX;
			var activationDistance = 80;

			// Change toast state
			if ( ! toast.classList.contains( 'panning' ) ) {
				toast.classList.add( 'panning' );
			}

			var opacityPercent = 1 - Math.abs( deltaX / activationDistance );
			if ( opacityPercent < 0 ) {
				opacityPercent = 0;
			}

			Vel( toast, {left: deltaX, opacity: opacityPercent}, {duration: 50, queue: false, easing: 'easeOutQuad'} );

		} );

		hammerHandler.on( 'panend', function ( e ) {
			var deltaX = e.deltaX;
			var activationDistance = 80;

			// If toast dragged past activation point
			if ( Math.abs( deltaX ) > activationDistance ) {
				Vel( toast, {marginTop: '-40px'}, {
					duration: 375,
					easing: 'easeOutExpo',
					queue: false,
					complete: function () {
						if ( typeof(
								completeCallback
							) === "function" ) {
							completeCallback();
						}
						toast.parentNode.removeChild( toast );
					}
				} );

			} else {
				toast.classList.remove( 'panning' );
				// Put toast back into original position
				Vel( toast, {left: 0, opacity: 1}, {
					duration: 300,
					easing: 'easeOutExpo',
					queue: false
				} );

			}
		} );

		return toast;
	}
};
;var TVE_Dash = TVE_Dash || {};

(function ( $ ) {
	TVE_Dash.product = {
		showLicenceForm: function () {
			var $this = $( this ),
				$card = $this.parents( '.tvd-card' ).first(),
				$form = $card.parent().find( '.tvd-card[data-state="form"]' );

			TVE_Dash.hideCardLoader( $form );
			$card.slideUp();
			$form.slideDown();
		},
		cancelLicense: function () {
			var $this = $( this ),
				$card = $this.parents( '.tvd-card' ).first(),
				$inactive = $card.parent().find( '.tvd-card[data-state="inactive"]' );

			$card.slideUp();
			$inactive.slideDown();
		},
		submitLicenseForm: function () {

			function show_active_states( products ) {

				function insert_response( response ) {
					$.each( response, function ( _id, item ) {
						$( '#tvd-product-' + _id ).replaceWith( item );
						$( '#tvd-product-' + _id ).hide().fadeIn().find( '.tvd-dropdown-button' ).tvd_dropdown();
					} );
				}

				$.ajax( {
					url: ajaxurl,
					type: 'post',
					data: {
						action: TVE_Dash_Const.actions.backend_ajax,
						route: TVE_Dash_Const.routes.active_states,
						products: products
					},
					dataType: 'json'
				} ).done( function ( response ) {
					setTimeout( function () {
						insert_response( response );
					}, 1500 );
				} ).fail( function () {
					// noop
				} );
				return false;
			}

			var $this = $( this ),
				$card = $this.parents( '.tvd-card' ).first(),
				$success = $card.parent().find( '.tvd-card[data-state="success"]' ),
				$error = $card.parent().find( '.tvd-card[data-state="error"]' ),
				data = $this.serialize() + '&action=' + TVE_Dash_Const.actions.backend_ajax + '&route=' + TVE_Dash_Const.routes.license,
				$btn = $this.find( '.tve-dash-item-license-btn' ),
				_html = $btn.html();

			TVE_Dash.cardLoader( $card );

			var ajax = $.ajax( {
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: data
			} );

			ajax.fail( function ( response ) {
				$btn.html( _html );
			} );

			ajax.done( function ( response ) {
				$btn.html( _html );
				$card.slideUp();
				if ( response.success ) {
					$card.parent().removeClass( 'tvd-inactive-product' );
					$success.slideDown();
					if ( response.level === "unlimited" ) {
						$success.find( "#tvd-license-uses" ).html( response.level )
					}
					show_active_states( response.products );
					$.each( response.products, function ( i, item ) {
						/**
						 * this license activated all products - highlight them all
						 */
						if ( item === 'all' ) {
							$( '#tvd-installed-products .tvd-inactive-product' )
								.find( '.tvd-card[data-state="inactive"]' ).slideUp()
								.end()
								.find( '.tvd-card[data-state="success"]' ).slideDown();
						}
					} );

				} else {
					$error.find( '.tvd-license-error' ).html( response.reason );
					$error.slideDown();
				}
			} );

			return false;
		}
	};

	TVE_Dash.settings = {
		getType: function ( $element ) {
			var $setting = $( $element ).parents( '.tvd-row-settings' );
			return typeof $setting.attr( 'data-setting-type' ) === 'undefined' ? '' : $setting.attr( 'data-setting-type' );
		},
		getName: function ( $element ) {
			var $setting = $( $element ).parents( '.tvd-row-settings' );
			return typeof $setting.attr( 'data-setting-name' ) === 'undefined' ? '' : $setting.attr( 'data-setting-name' );
		},
		saveOptions: function () {
			var $this = $( this ),
				data = {
					action: TVE_Dash_Const.actions.backend_ajax,
					route: TVE_Dash_Const.routes.settings,
					field: TVE_Dash.settings.getName( $this ),
					value: TVE_Dash.settings.getSettingValue( $this )
				};
			if ( data.field === '' ) {
				return;
			}
			TVE_Dash.settings.showLoading( $this );
			$.ajax( {
				url: ajaxurl,
				dataType: 'json',
				type: 'POST',
				data: data
			} ).done( function ( response ) {
				if ( response ) {
					$( "label[for='" + response.elem + "']" ).addClass( "tvd-active" );
					if ( response.valid == '1' ) {
						$( "." + response.elem ).removeClass( 'tvd-invalid' );
						$( "." + response.elem ).addClass( 'tvd-valid' );
					} else {
						$( "." + response.elem ).removeClass( 'tvd-valid' );
						$( "." + response.elem ).addClass( 'tvd-invalid' );
					}
					if ( response.elem == "tve_comments_facebook_admins" ) {
						$( ".tve_comments_facebook_admins" ).each( function () {
							if ( $( this ).val() == "" ) {
								$( this ).removeClass( 'tvd-valid' );
								$( this ).addClass( 'tvd-invalid' );
							}
						} );
					}

				}
			} ).always( function () {
				TVE_Dash.settings.hideLoading( $this );
			} );
		},
		getSettingValue: function ( $element ) {
			var type = TVE_Dash.settings.getType( $element ),
				value = [];

			if ( type === 'text' ) {
				$element.parents( '.tvd-row-settings' ).find( 'input' ).each( function () {
					value.push( $( this ).val() );
				} );
				if ( value.length === 1 ) {
					value = value[0];
				}
			}

			return value;
		},
		addOption: function () {
			var $this = $( this ),
				$parent = $this.parents( '.tvd-row-settings' ),
				$clone = $this.parents( '.tvd-row' ).clone();

			$parent.append( $clone );
			$clone.find( '.tvd-col:first' ).html( '&nbsp;' );
			$clone.find( '.tve_comments_facebook_admins' ).removeClass( "tvd-valid" ).removeClass( "tvd-invalid" );
			$clone.find( 'input' ).val( '' );
			$clone.find( '.tvd-add-option' ).remove();
			$clone.find( '.tvd-save-option' ).remove();
			/* Remove waves effect */
			$clone.find( '.tvd-waves-ripple' ).remove();

			if ( $parent.find( '.tvd-row' ).length > 1 ) {
				$parent.find( '.tvd-row:not(:first-child)' ).each( function () {
					if ( $( this ).find( '.tvd-delete-option' ).length === 0 ) {
						$( this ).find( '.tvd-col:last' ).append( "<a class='tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-red tvd-delete-option'>Delete</a>" )
					}
				} )
			}

		},
		deleteOption: function () {
			$( this ).parents( '.tvd-row' ).remove();
		},
		showLoading: function ( $element ) {
			$element.addClass( 'tvd-disabled' );
			$element.prepend( '<i style="font-size: 14px;" class="tvd-icon-spinner mdi-pulse">&nbsp;</i>' );
		},
		hideLoading: function ( $element ) {
			$element.removeClass( 'tvd-disabled' );
			$element.find( '.tvd-icon-spinner' ).remove();
		}
	};

	TVE_Dash.LicenseManager = {
		init: function () {
			var self = this;

			$( 'form#license_manager_form' ).submit( function () {
				self.checkLicense( $( this ) );
				return false;
			} );

			$( ".tvd-license-manager-cancel, .tvd-license-manager-retry" ).click( function () {
				var $current_card = $( this ).parents( '.tvd-card' ).first(),
					$form_card = $current_card.parent().find( '.tvd-card[data-state="form"]' );

				$current_card.slideUp();
				$form_card.slideDown();
			} );

			$( ".tvd-license-manager-new" ).click( function () {
				var $current_card = $( this ).parents( '.tvd-card' ),
					$form_card = $( this ).parents( '.tvd-card' ).parent().find( '.tvd-card[data-state="form"]' );

				$current_card.slideUp();
				$form_card.find( 'input[type="text"],input[type="email"]' ).val( "" );
				$form_card.slideDown();

				Materialize.updateTextFields();
			} );
		},
		checkLicense: function ( $form ) {
			var self = this,
				$current_card = $form.parents( '.tvd-card' ).first(),
				$error_card = $form.parents( '.tvd-card' ).first().parent().find( '.tvd-card[data-state="error"]' ),
				$success_card = $form.parents( '.tvd-card' ).first().parent().find( '.tvd-card[data-state="success"]' ),
				$btn = $form.find( 'button[type=submit]' ),
				_html = $btn.html(),
				data = $form.serialize() + '&action=' + TVE_Dash_Const.actions.backend_ajax + '&route=' + TVE_Dash_Const.routes.license,
				ajax = $.ajax( {
					url: ajaxurl,
					data: data,
					type: 'post',
					dataType: 'json'
				} );


			TVE_Dash.cardLoader( $current_card );

			ajax.fail( function () {
				$btn.html( _html );
			} );

			ajax.done( function ( response ) {
				$btn.html( _html );
				TVE_Dash.hideCardLoader( $current_card );
				if ( response.success ) {
					self.updateSuccessCard( response, $success_card );
					$current_card.slideUp();
					$success_card.slideDown();
				} else {
					$current_card.slideUp();
					$error_card.find( "#tvd-license-error" ).html( response.reason );
					$error_card.slideDown();
				}
			} );
		},
		updateSuccessCard: function ( response, $card ) {
			var $name = $card.find( '#tvd-license-name' ),
				$uses = $card.find( '#tvd-license-uses' ),
				$type = $card.find( '#tvd-license-type' );

			if ( response.products.length == 1 && response.products[0] === 'all' ) {
				$name.html( TVE_Dash_Const.products[response.products[0]] );
				$type.html( TVE_Dash_Const.license_types.full );
			} else {
				for ( var i = 0; i < response.products.length; i ++ ) {
					var tag = response.products[i];
					$name.html( $name.html() + (
							TVE_Dash_Const.products[tag] ? TVE_Dash_Const.products[tag] : (
							    tag.charAt( 0 ).toUpperCase() + tag.slice( 1 )
							)
						) + " " );
				}
				$type.html( TVE_Dash_Const.license_types.individual );
			}

			if ( response.level === "unlimited" ) {
				$uses.html( response.uses + ' / ' + TVE_Dash_Const.translations.Unlimited );
			} else {
				$uses.html( response.uses + " / " + response.level );
			}
		}
	};

	$( document ).ready( function () {
		var $body = $( 'body' );
		$( ".tve-dash-show-license-form" ).click( TVE_Dash.product.showLicenceForm );
		$( ".tve-dash-show-inactive-state" ).click( TVE_Dash.product.cancelLicense );
		$( 'form.tve-dash-item-license-form' ).submit( TVE_Dash.product.submitLicenseForm );

		$( document ).on( 'click', '.tvd-save-option:not(.tvd-disabled)', TVE_Dash.settings.saveOptions );
		$( document ).on( 'click', '.tvd-add-option', TVE_Dash.settings.addOption );
		$( document ).on( 'click', '.tvd-delete-option', TVE_Dash.settings.deleteOption );

		$body.on( 'click', '.tvd-modal-trigger', function ( e ) {
			var defaults = {
					starting_top: '4%'
				},
				$this = $( this ),
				options = $.extend( defaults, {}, $this.data() );

			options.starting_top = ($( this ).offset().top - $( window ).scrollTop()) / 1.15;
			var modal_id = $this.attr( "href" ) || '#' + $this.data( 'target' );
			$( modal_id ).openModal( options );
			e.preventDefault();
			e.stopPropagation();
			return false;
		} );

		TVE_Dash.LicenseManager.init();
		$body.on( 'click', 'a[href]', function () {
			var _href = $( this ).attr( 'href' ),
				$this = $( this );

			if ( _href === 'javascript:void(0)' || _href === '#' || _href.indexOf( 'http' ) === - 1 || $this.attr( 'target' ) == '_blank' || $this.hasClass( 'tvd-no-load' ) ) {
				return true;
			}

			TVE_Dash.showLoader();

			setTimeout( function () {
				TVE_Dash.hideLoader();
			}, 3000 );
		} );
	} );

})( jQuery );
