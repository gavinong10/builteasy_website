/**
 * Frontend javascript functionalities handling the display of forms
 */
var TL_Front = TL_Front || {};

/* minor hackery to ensure we have this available */
var ThriveGlobal = ThriveGlobal || {$j: jQuery.noConflict()};

TL_Front.add_page_css = function ( stylesheets ) {
	ThriveGlobal.$j.each( stylesheets, function ( _id, href ) {
		_id += '-css';
		if ( ! ThriveGlobal.$j( '#' + _id ).length ) {
			ThriveGlobal.$j( '<link rel="stylesheet" id="' + _id + '" type="text/css" href="' + href + '"/>' ).appendTo( 'head' );
		}
	} );
};

/**
 * we need to add the scripts using this method, to make sure the onload callback is fired properly
 * @param src
 * @param onload
 */
TL_Front.add_head_script = function ( src, id, onload ) {
	var script = document.createElement( 'script' ),
		head = ThriveGlobal.$j( 'head' )[0];

	script.async = true;
	if ( typeof onload === 'function' ) {
		script.onload = script.onreadystatechange = onload;
	}
	if ( typeof id !== 'undefined' ) {
		script.id = id;
	}

	script.src = src;

	head.insertBefore( script, head.firstChild );
};

TL_Front.add_page_js = function ( links, onLoad ) {
	if ( typeof onLoad !== 'function' ) {
		onLoad = function () {
		};
	}
	var to_load = 0;
	ThriveGlobal.$j.each( links, function ( _id, href ) {
		_id += '-js';
		if ( href && ! ThriveGlobal.$j( '#' + _id ).length ) {
			to_load ++;
			/* facebook needs to be inserted with a custom fragment appended - jQuery.getScript does not allow that */
			if ( href.indexOf( 'connect.facebook.net' ) !== - 1 ) {
				TL_Front.add_head_script( href, _id, function () {
					to_load --;
				} );
				return true;
			}
			ThriveGlobal.$j.getScript( href, function () {
				to_load --;
			} );
		}
	} );
	function check_loaded() {
		if ( to_load === 0 ) {
			onLoad();
			return;
		}
		setTimeout( check_loaded, 50 );
	}

	check_loaded();
};

TL_Front.do_impression = function () {
	var data = TL_Front.impressions_data;
	if ( data === undefined ) {
		console.log( "No form to register impression for !" );
		return;
	}

	var ajax_data = {
		security: TL_Const.security,
		action: TL_Const.action_impression,
		tl_data: data,
		current_screen: TL_Const.current_screen
	};
	ThriveGlobal.$j.each( TL_Const.custom_post_data, function ( k, v ) {
		ajax_data[k] = v;
	} );

	if ( window.TVE_Dash && ! TVE_Dash.ajax_sent ) {
		TVE_Dash.add_load_item( 'tl_impression', ajax_data );
	} else {
		ThriveGlobal.$j.post( TL_Const.ajax_url, ajax_data );
	}
};

ThriveGlobal.$j( function () {

	/**
	 * SUPP-1217 Divi + Yoast + TL conflict
	 */
	if ( typeof TL_Const === 'undefined' ) {
		return;
	}

	ThriveGlobal.$j( '.tve-leads-screen-filler iframe, .tve-leads-ribbon iframe' ).not( '.thrv_social_default iframe' ).not( '.tcb-dr-done' ).each( function () {
		var $frame = ThriveGlobal.$j( this ).addClass( 'tcb-dr-done' );
		if ( $frame.attr( 'src' ) ) {
			$frame.attr( 'data-src', $frame.attr( 'src' ) );
		}
		$frame.attr( 'src', '' );
	} );
	ThriveGlobal.$j( TL_Front ).on( 'showform.thriveleads', function ( event, data ) {
		var $target = data.$target ? data.$target : ThriveGlobal.$j( '.' + data.form_id ),
			$anim_target;
		if ( ! $target.length ) {
			return;
		}
		if ( $target.attr( 'data-s-state' ) ) {
			/**
			 * find the already subscribed state and show it
			 */
			$target = $target.closest( '.tl-states-root' ).find( '[data-state="' + $target.attr( 'data-s-state' ) + '"] .tl-lb-target' );
		}
		$anim_target = $target.hasClass( 'tve-tl-anim' ) ? $target : $target.find( '.tve-tl-anim' );
		$target.css( 'display', '' );
		setTimeout( function () {
			$anim_target.addClass( 'tve-leads-triggered' );
			TL_Front.handle_typefocus( $anim_target, 'start' );
			TCB_Front.postGridLayout();
		}, 0 );

		if ( typeof TL_Front['open_' + data.form_type] === 'function' ) {
			TL_Front['open_' + data.form_type]( $target, data.TargetEvent );
		} else {
			$target.show();
		}
		setTimeout( function () {
			$target.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe' ).each( function () {
				var $this = ThriveGlobal.$j( this );
				if ( $this.attr( 'data-src' ) ) {
					$this.attr( 'src', $this.attr( 'data-src' ) );
				}
			} );
		}, 200 );
	} );
	if ( ! TL_Const.ajax_load ) {
		TL_Front.do_impression();
	}

	function init() {
		if ( ! TL_Const.forms ) {
			return false;
		}

		/**
		 * try to detect the email field from a form and get its value
		 *
		 * @param $form
		 */
		function try_getting_email( $form ) {
			/* if there is a field which requires email validation, use that */
			if ( $form.find( '[data-validation="email"]' ).length ) {
				return $form.find( '[data-validation="email"]' ).val();
			}
			var maybe_email = '';
			/* try search for 'email' in the input name */
			$form.find( 'input' ).each( function () {
				if ( this.name && this.name.match( /email/i ) ) {
					maybe_email = this.value;
					return false;
				}
			} );

			return maybe_email;
		}

		/**
		 * listen for the forms submission, and send tracking data requests
		 * the submit listener is delegated just to be sure we can track everything
		 */
		ThriveGlobal.$j( 'body' ).on( 'submit', '.tve-leads-conversion-object form', function ( event ) {
			var $form = ThriveGlobal.$j( this ),
				type = $form.parents( '.tve-leads-conversion-object' ).first().attr( 'data-tl-type' ),
				custom_fields = {};

			if ( $form.data( 'tl-do-submit' ) || ! type || ! TL_Const.forms[type] ) {
				return true;
			}

			$form.find( 'input' ).each( function () {
				var $input = ThriveGlobal.$j( this ),
					field = $input.attr( 'name' );
				if ( typeof field !== 'undefined' && TL_Const.ignored_fields.indexOf( field ) === - 1 ) {
					custom_fields[$input.attr( 'name' )] = $input.val();
				}
			} );

			var ajax_data = {
				security: TL_Const.security,
				action: TL_Const.action_conversion,
				type: type,
				tl_data: TL_Const.forms[type],
				custom_fields: custom_fields,
				email: try_getting_email( $form ),
				current_screen: TL_Const.current_screen
			};

			ThriveGlobal.$j.each( TL_Const.custom_post_data, function ( k, v ) {
				ajax_data[k] = v;
			} );

			ThriveGlobal.$j.ajax( {
				url: TL_Const.ajax_url,
				data: ajax_data,
				type: 'post',
				async: false
			} );

			/* When there is no action for the form we reload the page manually so we won't mess up the redirects from WP */
			if ( typeof $form.attr( 'action' ) === 'undefined' ) {
				event.preventDefault();
				location.reload();
			}

			return true;
		} );

		/**
		 * event listener that allows setting custom post data in forms created with TCB and connected to an API
		 */
		ThriveGlobal.$j( 'body' ).on( 'form_conversion.tcb', '.tve-leads-conversion-object form', function ( event ) {
			var $form = ThriveGlobal.$j( this ),
				type = $form.parents( '.tve-leads-conversion-object' ).first().attr( 'data-tl-type' );

			if ( ! type || ! TL_Const.hasOwnProperty( 'forms' ) || ! TL_Const.forms[type] ) {
				return true;
			}

			var ajax_data = {
				type: type,
				tl_data: TL_Const.forms[type],
				current_screen: TL_Const.current_screen
			};

			ThriveGlobal.$j.each( TL_Const.custom_post_data, function ( k, v ) {
				ajax_data[k] = v;
			} );

			event.post_data = event.post_data || {};
			event.post_data.thrive_leads = ajax_data;
		} ).on( 'lead_conversion_success.tcb', '.tve_lead_lock_shortcode form', function ( event ) {
			var $form = ThriveGlobal.$j( this ),
				$container = $form.parents( '.tve_content_lock' );

			$container.removeClass( 'tve_lead_lock' ).find( '.tve_lead_lock_shortcode' ).remove();
			$container.find( '.tve_lead_locked_overlay' ).remove();

			event.content_unlocked = true;
		} ).on( 'leads_states.tcb', '.tve-leads-conversion-object form', function ( event, content ) {
			var $form = ThriveGlobal.$j( this ),
				_form_type = $form.find( '#_form_type' ).val();

			switch ( _form_type ) {
				case 'ribbon':
					var ribbon = $form.parents( '.tve-leads-ribbon' );
					$form.parents( '.tve_shortcode_editor' ).empty().html( content );
					TL_Front.open_ribbon( ribbon );
					break;
				case 'lightbox':
				case 'tve_lead_2s_lightbox':
					$form.parents( '.tve_p_lb_control' ).empty().html( content );
					break;
				case 'widget':
				case 'in-content':
				case 'post-footer':
				case 'php-insert':
					$form.parents( '.tve_shortcode_editor' ).empty().html( content );
					break;
				case 'slide-in':
					var slide_in = $form.parents( '.tve-leads-slide-in' );
					$form.parents( '.tve_shortcode_editor' ).empty().html( content );
					TL_Front.open_slide_in( slide_in );
					break;
				case 'screen-filler-lightbox':
					var screen_filler = $form.parents( '.tve-leads-screen-filler' );
					$form.parents( '.tve_shortcode_editor' ).empty().html( content );
					TL_Front.open_screen_filler( screen_filler );
					break;
				case 'scroll-mat':
					var ribbon = $form.parents( '.tve-leads-greedy_ribbon' );
					$form.parents( '.tve_shortcode_editor' ).empty().html( content );
					TL_Front.open_ribbon( ribbon );
					break;
				case 'tve_lead_shortcode':
					$form.parents( '.tve-leads-shortcode' ).empty().html( content );
					break;
				case 'lead_generation':
				default:
					event.change_states = false;
					break;
			}

			event.change_states = true;

		} ).on( 'leads_messages.tcb', '.tve-leads-conversion-object form', function ( event ) {
			var $form = ThriveGlobal.$j( this ),
				_form_type = $form.find( '#_form_type' ).val();

			switch ( _form_type ) {
				case 'ribbon':
					$form.parents( '.thrv-ribbon' ).find( '.tve-ribbon-close' ).click();
					break;
				case 'lightbox':
				case 'tve_lead_2s_lightbox':
					$form.parents( '.tve_p_lb_content' ).find( '.tve_p_lb_close' ).click();
					$form.parents( '.tve-leads-screen-filler' ).find( '.tve-screen-filler-close' ).click();
					break;
				case 'widget':
				case 'in-content':
				case 'post-footer':
				case 'php-insert':
				default:
					$form.find( 'input' ).val( '' );
					break;
				case 'slide-in':
					$form.parents( '.thrv-leads-slide-in' ).find( '.tve-leads-close' ).click();
					break;
				case 'screen-filler-lightbox':
					$form.parents( '.tve-leads-screen-filler' ).find( '.tve-screen-filler-close' ).click();
					break;
				case 'scroll-mat':
					$form.parents( '.thrv-greedy-ribbon' ).find( '.tve_et_click' ).click();
					break;
			}
		} );
	}

	if ( TL_Const.ajax_load ) {

		var resources_loaded = false;

		/* We hide all the triggers for the two step lightbox and display them only if the lightbox is visible */
		var two_step_triggers = ThriveGlobal.$j( '.tve-leads-two-step-trigger' ).hide();

		function ajax_load_callback( response ) {
			if ( ! response || ! response.res || ! response.js || ! response.html ) {
				return;
			}
			TL_Front.add_page_css( response.res.css );
			TL_Front.add_page_css( response.res.fonts );

			if ( response.html ) {
				if ( ! response.html.widget ) {
					ThriveGlobal.$j( '.tl-widget-container' ).remove();
				}
				function show_trigger( two_step_key ) {
					var two_step_id = two_step_key.replace( 'two_step_', '' );
					ThriveGlobal.$j( '.tl-2step-trigger-' + two_step_id ).show();
				}

				ThriveGlobal.$j.each( response.html, function ( elem_type, html ) {
					if ( ! html ) {
						return true;
					}
					if ( elem_type === 'in_content' ) {
						// move the placeholder after the nth paragraph
						var fn = 'after',
							post = ThriveGlobal.$j( '.tve-tl-cnt-wrap' ),
							p = post.find( 'p' ).filter( ':visible' );
						if ( p.length === 0 && response.in_content_pos == 0 ) {
							ThriveGlobal.$j( '.tve-tl-cnt-wrap' ).prepend( html );
						} else {
							if ( response.in_content_pos == 0 ) {
								response.in_content_pos = 1;
								fn = 'before';
							}
							p.eq( parseInt( response.in_content_pos ) - 1 )[fn]( html );
						}
					} else {
						var $placeholder = ThriveGlobal.$j( '.tl-placeholder-f-type-' + elem_type );
						if ( response.js[elem_type] && response.js[elem_type].content_locking ) {
							/**
							 * content locking shortcode - add the blur class if this is the case
							 * or show the locked content if the user has a conversion registered
							 */
							var $parent = $placeholder.parents( '.tve_content_lock' ).first();

							if ( response.js[elem_type].has_conversion ) {
								$parent.removeClass( 'tve_lock_hide' );
								return true;
							}

							if ( response.js[elem_type].lock == 'tve_lock_blur' ) {
								$parent.removeClass( 'tve_lock_hide' ).addClass( response.js[elem_type].lock );
							}
						}
						$placeholder.replaceWith( html );
						if ( elem_type === 'widget' ) {
							ThriveGlobal.$j( '.tl-widget-container' ).children().unwrap();
						}

						/* for the forms that we going to be added in the page, we're displaying the trigger */
						if ( elem_type.indexOf( 'two_step' ) === 0 ) {
							show_trigger( elem_type );
						}
					}
				} );
			}
			if ( response.body_end ) {
				/**
				 * filter the end-of-body contents to remove any (possible) existing wistia embed divs
				 */
				var $body_end = ThriveGlobal.$j( response.body_end );
				$body_end.find( '.tve_wistia_popover' ).each( function () {
					if ( ThriveGlobal.$j( '#' + this.id ).length ) {
						this.parentNode.removeChild( this );
					}
				} );
				ThriveGlobal.$j( 'body' ).append( $body_end );
			}

			TL_Front.add_page_js( response.res.js, function () {
				resources_loaded = true;
			} );

			/**
			 * rebind the TCB event listeners
			 */
			function dom_ready() {
				if ( ! resources_loaded ) {
					setTimeout( dom_ready, 50 );
					return;
				}

				TCB_Front.event_triggers( ThriveGlobal.$j( 'body' ) );
				TCB_Front.onDOMReady();
				init();
				ThriveGlobal.$j( TCB_Front ).trigger( 'tl-ajax-loaded' );
			}

			setTimeout( dom_ready, 50 );
			TL_Const.forms = response.js;
		}

		/**
		 * ajax load all the forms that are to be displayed on this page
		 */
		var ajax_data = {
			main_group_id: TL_Const.main_group_id,
			shortcode_ids: TL_Const.shortcode_ids,
			two_step_ids: TL_Const.two_step_ids,
			action: 'tve_leads_ajax_load_forms',
			security: TL_Const.security,
			display_options: TL_Const.display_options,
			current_screen: TL_Const.current_screen
		};
		ThriveGlobal.$j.each( TL_Const.custom_post_data, function ( k, v ) {
			ajax_data[k] = v;
		} );

		if ( window.TVE_Dash && ! TVE_Dash.ajax_sent ) {
			ThriveGlobal.$j( document ).on( 'tve-dash.load', function ( event ) {
				TVE_Dash.add_load_item( 'tl_lazy_load', ajax_data, ajax_load_callback );
			} );
		} else {
			ThriveGlobal.$j.ajax( {
				url: TL_Const.ajax_url,
				type: 'post',
				dataType: 'json',
				data: ajax_data,
				xhrFields: {
					withCredentials: true
				}
			} ).done( ajax_load_callback );
		}
		return;
	}
	/**
	 * if there is no ajax_load setting, init stuff as usual
	 */
	init();
} );

TL_Front.switch_lightbox_state = function ( $state_target, $current_state ) {
	$state_target.find( '.tve_p_lb_overlay' ).css( 'opacity', '0.8' );
	$state_target.find( '.tve_p_lb_content' ).css( 'top', $current_state.find( '.tve_p_lb_content' ).css( 'top' ) ).addClass( 'tve-leads-triggered' );
	window.tve_lb_switch_state = true;
	return TL_Front.open_lightbox( $state_target.find( '.tl-lb-target' ) );
};

TL_Front.close_lightbox = function () {
	/**
	 * Clear the parent state in case we have other forms working with lightboxes
	 */
	var $body = ThriveGlobal.$j( 'body' ),
		$html = ThriveGlobal.$j( 'html' ),
		overflow_hidden = 'tve-o-hidden tve-l-open tve-hide-overflow',
		$lightbox = arguments[0] || $body.data( 'tl-open-lightbox' );
	if ( ! $lightbox || ! $lightbox.length ) {
		return;
	}
	$lightbox.find( '.tve-tl-anim' ).removeClass( 'tve-leads-triggered' );
	if ( ! window.tve_lb_switch_state ) {
		if ( ThriveGlobal.$j( '.tve_lb_open' ).length === 1 ) {
			$body.removeClass( overflow_hidden ).css( 'padding-right', '' );
			$html.removeClass( overflow_hidden );
		}
		$html.removeClass( $html.data( 'tl-anim-class' ) );
	}
	window.tve_lb_switch_state = false;
	setTimeout( function () {
		$lightbox.addClass( 'tve_lb_closing' );
		//$lightbox.removeClass('tve_lb_open tve_lb_opening tve_lb_closing tve_p_lb_background').css('display', 'none').find('tve_p_lb_content').trigger('tve.lightbox-close');
		$lightbox.removeClass( 'tve_lb_open tve_lb_opening tve_lb_closing tve_p_lb_background' ).css( {
			'visibility': 'hidden',
			'position': 'fixed',
			'left': '-9000px'
		} ).find( 'tve_p_lb_content' ).trigger( 'tve.lightbox-close' );
	}, 200 );

	$lightbox.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
		var $this = ThriveGlobal.$j( this );
		$this.attr( 'data-src', $this.attr( 'src' ) );
		$this.attr( 'src', '' );
	} );

	TL_Front.handle_typefocus( $lightbox, 'pause' );

	/**
	 * close any error messages that might have been displayed on forms inside the lightbox
	 */
	ThriveGlobal.$j( '#tve-lg-error-container' ).hide();
};

TL_Front.open_lightbox = function ( $target, TargetEvent ) {

	function getBrowserScrollSize() {
		var $ = ThriveGlobal.$j;
		var css = {
			"border": "none",
			"height": "200px",
			"margin": "0",
			"padding": "0",
			"width": "200px"
		};

		var inner = $( "<div>" ).css( $.extend( {}, css ) );
		var outer = $( "<div>" ).css( $.extend( {
			"left": "-1000px",
			"overflow": "scroll",
			"position": "absolute",
			"top": "-1000px"
		}, css ) ).append( inner ).appendTo( "body" )
			.scrollLeft( 1000 )
			.scrollTop( 1000 );

		var scrollSize = {
			"height": (
				          outer.offset().top - inner.offset().top
			          ) || 0,
			"width": (
				         outer.offset().left - inner.offset().left
			         ) || 0
		};

		outer.remove();
		return scrollSize;
	}

	var is_switch_state = window.tve_lb_switch_state;

	ThriveGlobal.$j.fn.thrive_iphone_placeholder && $target.find( 'input[placeholder]' ).thrive_iphone_placeholder();

	/* close any other opened lightboxes */
	TL_Front.close_lightbox( ThriveGlobal.$j( '.tve_p_lb_background.tve_lb_open' ) );

	//$target.css('display', '').parents('.tl-style').css('display', '');
	$target.css( {
		'visibility': '',
		'position': '',
		'left': '',
		'display': ''
	} ).parents( '.tl-style' ).css( {
		'visibility': '',
		'position': '',
		'left': '',
		'display': ''
	} );

	var $body = ThriveGlobal.$j( 'body' ),
		$html = ThriveGlobal.$j( 'html' ),
		overflow_hidden = 'tve-o-hidden tve-l-open tve-hide-overflow',
		scroll_width = getBrowserScrollSize().width,
		oPadding = parseInt( $body.css( 'paddingRight' ) ),
		has_lb_open = ThriveGlobal.$j( '.tve_p_lb_background.tve_lb_open' ).length;

	if ( isNaN( oPadding ) ) {
		oPadding = 0;
	}

	$target.find( '.tve_p_lb_close' ).off().on( "click", function () {
		TL_Front.close_lightbox();
		return false;
	} );

	$body.off( 'keyup.tve_lb_close' ).on( 'keyup.tve_lb_close', function ( e ) {
		if ( e.which == 27 ) {
			TL_Front.close_lightbox();
			return false;
		}
	} );

	$target.find( '.tve_p_lb_overlay' ).off( 'click.tve_lb_close' ).on( 'click.tve_lb_close', function () {
		TL_Front.close_lightbox();
		return false;
	} );

	$body.data( 'tl-open-lightbox', $target );

	$target.addClass( 'tve_p_lb_background' );

	$body.addClass( overflow_hidden );
	$html.addClass( overflow_hidden );

	var wHeight = ThriveGlobal.$j( window ).height(),
		page_has_scroll = wHeight < ThriveGlobal.$j( document ).height();

	if ( ! is_switch_state && page_has_scroll ) {
		$body.css( 'padding-right', (
		                            oPadding + scroll_width
		                            ) + 'px' );
	}

	//load the responsive video iframes
	$target.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
		var $this = jQuery( this );
		if ( $this.attr( 'data-src' ) ) {
			$this.attr( 'src', $this.attr( 'data-src' ) );
		}
	} );

	var cls = '';
	/* append the animation class to the html element */
	ThriveGlobal.$j.each( $target.parents( '.tl-states-root' ).attr( 'class' ).split( ' ' ), function ( i, c ) {
		if ( c.indexOf( 'tl-anim' ) === 0 ) {
			cls = c;
			return false;
		}
	} );
	$html.addClass( cls ).data( 'tl-anim-class', cls );

	setTimeout( function () {

		setTimeout( function () {
			$target.addClass( 'tve_lb_opening' );
		}, 0 );

		/* reload any iframe that might be in there, this was causing issues with google maps embeds in hidden tabs */
		$target.find( 'iframe' ).not( '.thrv_social_default iframe' ).each( function () {
			var $this = ThriveGlobal.$j( this );
			if ( $this.data( 'tve_ifr_loaded' ) || ! $this.attr( 'data-src' ) ) {
				return;
			}
			$this.data( 'tve_ifr_loaded', 1 ).attr( 'src', $this.attr( 'data-src' ) );
		} );

		function position_it() {
			var cHeight = $target.find( '.tve_p_lb_content' ).outerHeight( true ) + (
						2 * parseInt( $target.css( 'padding-top' ) )
					),
				$lContent = $target.find( '.tve_p_lb_content' ),
				wHeight = ThriveGlobal.$j( window ).height(),
				top = (
					      wHeight - cHeight
				      ) / 2;

			$target.find( '.tve_p_lb_overlay' ).css( {
				height: (
				        cHeight + 80
				        ) + 'px',
				'min-height': wHeight + 'px'
			} );
			if ( has_lb_open ) {
				$lContent.animate( {
					top: (
						top < 40 ? 40 : top
					)
				}, 100 );
			} else {
				$lContent.css( 'top', (
					                      top < 40 ? 40 : top
				                      ) + 'px' );
			}
			if ( cHeight + 40 > wHeight ) {
				$target.addClass( 'tve-scroll' );
			}
		}

		position_it();
		ThriveGlobal.$j( window ).on( 'resize', position_it );

	}, 20 );

	setTimeout( function () {
		$target.removeClass( 'tve_lb_opening' ).addClass( 'tve_lb_open' ).find( '.tve_p_lb_content' ).trigger( 'tve.lightbox-open' );
		ThriveGlobal.$j( window ).trigger( 'scroll' );
	}, 300 );

	// called to stop other events that might interact in the bad way with the lightbox, like a button that redirects the page.
	if ( TargetEvent && TargetEvent.preventDefault ) {
		TargetEvent.preventDefault();
		TargetEvent.stopPropagation();
	}
	$target.parents( '.tl-states-root' ).off( 'switchstate' ).on( 'switchstate', function ( e, $state ) {
		var args = Array.prototype.slice.call( arguments, 1 );
		TL_Front.switch_lightbox_state.apply( TL_Front, args );
	} );
};

TL_Front.open_two_step_lightbox = TL_Front.open_lightbox;

TL_Front.open_ribbon = function ( $target ) {

	/**
	 * used when there is also a Scroll Mat on the page, this will make sure the ribbon is opened after the greedy ribbon
	 */
	function open_it() {
		var position = $target.attr( 'data-position' ) || 'top';

		if ( position === 'top' ) {
			$target.css( 'top', ThriveGlobal.$j( '#wpadminbar' ).length ? '32px' : '0px' );
		} else if ( position === 'bottom' ) {
			$target.css( 'bottom', '0px' );
			$target.css( 'top', 'auto' );
		}

		$target.find( '.tve-ribbon-close' ).on( 'click', function () {
			$target.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
				var $this = ThriveGlobal.$j( this );
				$this.attr( 'data-src', $this.attr( 'src' ) );
				$this.attr( 'src', '' );
			} );
			$target.removeClass( 'tve-leads-triggered' );
			if ( position === 'top' ) {
				ThriveGlobal.$j( 'body' ).animate( {'margin-top': 0 + 'px'}, 200 );
			} else if ( position === 'bottom' ) {
				ThriveGlobal.$j( 'body' ).animate( {'margin-bottom': 0 + 'px'}, 200 );
			}
			TL_Front.handle_typefocus( $target, 'pause' );

			setTimeout( function () {
				$target.css( position, '' );
			}, 400 );
			/**
			 * close any error messages that might have been displayed on forms inside the lightbox
			 */
			ThriveGlobal.$j( '#tve-lg-error-container' ).hide();
		} );
		/**
		 * Mozilla is really slow at applying the loaded css. we need this workaround to have it work in mozilla.
		 */
		var iterations = 0,
			initial_height = $target.outerHeight(),
			ii = setInterval( function () {
				iterations ++;
				var _h = $target.outerHeight();
				if ( _h != initial_height || iterations == 10 ) {
					clearInterval( ii );
				}
				if ( position === 'top' ) {
					ThriveGlobal.$j( 'body' ).animate( {'margin-top': _h + 'px'}, 200, function () {
						document.body.style.setProperty( 'margin-top', _h + 'px', 'important' );
					} );
				} else if ( position === 'bottom' ) {
					ThriveGlobal.$j( 'body' ).animate( {'margin-bottom': _h + 'px'}, 200 );
				}
			}, 100 );

		$target.off( 'switchstate' ).on( 'switchstate', function ( e, $target ) {
			var args = Array.prototype.slice.call( arguments, 1 );
			TL_Front.switch_ribbon_state.apply( TL_Front, args );
		} );
	}

	if ( TL_Const.forms.greedy_ribbon && TL_Const.forms.greedy_ribbon.allow_callbacks ) {
		TL_Const.close_callbacks = TL_Const.close_callbacks || {};
		TL_Const.close_callbacks.greedy_ribbon = [open_it];
	} else {
		open_it();
	}
};

TL_Front.switch_ribbon_state = function ( $target ) {
	var h = $target.outerHeight( true ),
		prop = $target.parent().attr( 'data-position' ) === 'top' ? 'margin-top' : 'margin-bottom',
		animation = {};
	animation[prop] = h + 'px';

	ThriveGlobal.$j( 'body' ).animate( animation, 200 );

};

TL_Front.open_greedy_ribbon = function ( $target ) {
	var $body = ThriveGlobal.$j( 'body' ),
		$window = ThriveGlobal.$j( window );
	$window.scrollTop( 0 );
	$body.addClass( 'tve-tl-gr-anim' );
	$target.css( 'top', ThriveGlobal.$j( '#wpadminbar' ).length ? '32px' : '0px' );
	var wHeight = $target.outerHeight();
	$body[0].style.setProperty( 'margin-top', wHeight + 'px', 'important' );
	var greedyCondition = 1;
	$window.scroll( function () {
		var isFormOpen = $body.hasClass( 'tve-tl-gr-anim' );
		if ( greedyCondition === 1 && isFormOpen ) {
			var browserScroll = $window.scrollTop();
			if ( browserScroll > wHeight ) {
				var hasWistiaPopover = $target.find( '.tve_ea_thrive_wistia' ).length || $target.find( '.tve_with_wistia_popover' );
				if ( hasWistiaPopover ) {
					ThriveGlobal.$j( '.wistia_placebo_close_button' ).trigger( 'click' );
				}
				$body.removeClass( 'tve-tl-gr-anim' );
				$target.addClass( 'tve-no-animation' );
				var greedyScroll = browserScroll - wHeight;
				$target.removeClass( 'tve-leads-triggered' );
				$target.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
					var $this = ThriveGlobal.$j( this );
					$this.attr( 'data-src', $this.attr( 'src' ) );
					$this.attr( 'src', '' );
				} );
				$body[0].style.removeProperty( 'margin-top' );
				$body.css( 'margin-top', wHeight );
				$body.css( 'margin-top', '0px' );
				$window.scrollTop( greedyScroll );
				$target.removeClass( 'tve-no-animation' );
				greedyCondition = 0;
				TL_Front.form_closed( 'greedy_ribbon' );
				TL_Const.forms.greedy_ribbon.allow_callbacks = false;
			}
		}
	} );
	$target.off( 'switchstate' ).on( 'switchstate', function ( e, $target ) {

	} );
};

TL_Front.open_screen_filler = function ( $target, TargetEvent ) {
	var overflow_hidden = 'tve-so-hidden tve-sl-open tve-s-hide-overflow',
		html_body = ThriveGlobal.$j( 'html,body' ),
		$html = ThriveGlobal.$j( 'html' );

	ThriveGlobal.$j.fn.thrive_iphone_placeholder && $target.find( 'input[placeholder]' ).thrive_iphone_placeholder();

	$target.css( 'top', ThriveGlobal.$j( '#wpadminbar' ).length ? '32px' : '0px' ).css( 'visibility', '' );
	if ( $target.hasClass( 'stl-anim-slip_from_top' ) === true ) {
		var browserScroll = ThriveGlobal.$j( window ).scrollTop();
		$target.css( 'top', browserScroll ).css( 'visibility', '' );
	}

	$target.find( '.tve-screen-filler-close' ).on( 'click', function () {
		close_it( $target );
	} );

	html_body.addClass( overflow_hidden );

	var cls = '';
	/* append the animation class to the html element */
	ThriveGlobal.$j.each( $target.attr( 'class' ).split( ' ' ), function ( i, c ) {
		if ( c.indexOf( 'stl-anim' ) === 0 ) {
			cls = c;
			return false;
		}
	} );
	$html.addClass( cls ).data( 'tl-s-anim-class', cls );

	function close_it( $screen_filler ) {

		$screen_filler.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
			var $this = ThriveGlobal.$j( this );
			$this.attr( 'data-src', $this.attr( 'src' ) );
			$this.attr( 'src', '' );
		} );

		$screen_filler.removeClass( 'tve-leads-triggered' );
		TL_Front.handle_typefocus( $screen_filler, 'pause' );
		ThriveGlobal.$j( document ).off( 'keyup.close-screenfiller' );
		ThriveGlobal.$j( 'body' ).animate( {'margin-top': 0 + 'px'}, 200 );
		html_body.removeClass( overflow_hidden );
		$html.removeClass( $html.data( 'tl-s-anim-class' ) );
		setTimeout( function () {
			$screen_filler.css( 'top', '' ).hide();
		}, 400 );
		/**
		 * close any error messages that might have been displayed on forms inside the lightbox
		 */
		ThriveGlobal.$j( '#tve-lg-error-container' ).hide();
	}

	// called to stop other events that might interact in the bad way with the lightbox, like a button that redirects the page.
	if ( TargetEvent && TargetEvent.preventDefault ) {
		TargetEvent.preventDefault();
		TargetEvent.stopPropagation();
	}

	ThriveGlobal.$j( document ).off( 'keyup.close-screenfiller' ).on( 'keyup.close-screenfiller', function ( e ) {
		if ( e.which == 27 ) {
			close_it( $target );
		}
	} );
};

TL_Front.switch_slide_in_state = function ( $state ) {
	TL_Front.slide_in_position( $state.find( '.thrv-leads-slide-in' ) );
};
TL_Front.slide_in_position = function ( $lContent ) {
	var $window = ThriveGlobal.$j( window ),
		elHeight = $lContent.outerHeight();
	if ( $window.width() <= 782 || $window.height() < elHeight ) {
		$lContent.parents( '.tve-leads-slide-in' ).addClass( 'tve-lb' ); // display it as a lightbox
		var overflow_hidden = 'tve-o-hidden tve-l-open tve-hide-overflow',
			wHeight = $window.height(),
			$body = ThriveGlobal.$j( 'body' ),
			$html = ThriveGlobal.$j( 'html' );

		setTimeout( function () {
			var top = (
				          wHeight - elHeight
			          ) / 2;
			$body.addClass( overflow_hidden );
			$html.addClass( overflow_hidden );
			$lContent.parents( '.tve-leads-conversion-object' ).first().css( {
				height: (
				        elHeight + 80
				        ) + 'px',
				'min-height': wHeight + 'px'
			} );
			$lContent.css( 'top', (
				                      top < 40 ? 40 : top
			                      ) + 'px' );
			if ( elHeight + 40 > wHeight ) {
				$lContent.parents( '.tve-leads-slide-in' ).css( 'overflow-y', 'scroll' );
			}
		}, 0 );
	}
};
TL_Front.open_slide_in = function ( $target ) {
	var overflow_hidden = 'tve-o-hidden tve-l-open tve-hide-overflow',
		$body = ThriveGlobal.$j( 'body' ),
		$html = ThriveGlobal.$j( 'html' );

	ThriveGlobal.$j.fn.thrive_iphone_placeholder && $target.find( 'input[placeholder]' ).thrive_iphone_placeholder();

	TL_Front.slide_in_position( $target.find( '.thrv-leads-slide-in' ).filter( ':visible' ) );

	function close_it( $slidein ) {
		$slidein.removeClass( 'tve-leads-triggered' );
		TL_Front.handle_typefocus( $slidein, 'pause' );
		ThriveGlobal.$j( document ).off( 'keyup.close-slidein' );
		$body.removeClass( overflow_hidden );
		$html.removeClass( overflow_hidden );
		$slidein.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
			var $this = ThriveGlobal.$j( this );
			$this.attr( 'data-src', $this.attr( 'src' ) );
			$this.attr( 'src', '' );
		} );
		/**
		 * close any error messages that might have been displayed on forms inside the lightbox
		 */
		ThriveGlobal.$j( '#tve-lg-error-container' ).hide();
	}

	$target.off().on( 'click', '.tve-leads-close', function () {
		close_it( $target );
	} );
	$target.on( 'switchstate', function ( e, $state ) {
		var args = Array.prototype.slice.call( arguments, 1 );
		TL_Front.switch_slide_in_state.apply( TL_Front, args );
	} );
	ThriveGlobal.$j( document ).off( 'keyup.close-slidein' ).on( 'keyup.close-slidein', function ( e ) {
		if ( e.which == 27 ) {
			close_it( $target );
		}
	} );
};

TL_Front.close_form = function ( element, trigger, action, config ) {
	var $element = ThriveGlobal.$j( element ),
		$parent = $element.parents( '.tve-leads-triggered' ), //every form has this class on its wrapper
		type = $parent.attr( 'data-tl-type' ); //some of them have its type in data-tl-type

	//if no type then identify it from class
	if ( type === undefined && $parent.hasClass( 'tve-leads-widget' ) ) {
		type = 'widget';
	} else if ( type === undefined && $parent.hasClass( 'tve-leads-post-footer' ) ) {
		type = 'post-footer';
	} else if ( type === undefined && $parent.hasClass( 'tve-leads-slide-in' ) ) {
		type = 'slide-in';
	} else if ( type === undefined && $parent.hasClass( 'tve-leads-in-content' ) ) {
		type = 'in-content';
	} else if ( type === undefined && $parent.hasClass( 'tve-leads-shortcode' ) ) {
		type = 'shortcode';
	} else if ( type === undefined && $parent.hasClass( 'tve-leads-greedy_ribbon' ) ) {
		type = 'greedy_ribbon';
	}

	//remove this class just because it is added dynamically and maybe we want to trigger it again
	$parent.removeClass( 'tve-leads-triggered' );
	TL_Front.handle_typefocus( $parent, 'pause' );
	switch ( type ) {
		case 'ribbon':
			$parent.find( '.tve-ribbon-close' ).trigger( 'click' );//there already exists a bind for close
			break;
		case 'slide-in':
			$parent.find( '.tve-leads-close' ).trigger( 'click' );//there already exists a bind for close
			$parent.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
				var $this = ThriveGlobal.$j( this );
				$this.attr( 'data-src', $this.attr( 'src' ) );
				$this.attr( 'src', '' );
			} );
			break;
		case 'post-footer'://case able for PHP Insert form too
		case 'in-content':
		case 'shortcode':
			$parent.fadeOut( 200, function () {
				TL_Front.form_closed( type );
			} );
			break;
		case 'widget':
			$parent.parent().slideUp( 200 );//its parent is a section tag; see the function thrive_dynamic_sidebar_params()
			break;
		case 'greedy_ribbon':
			var $body = ThriveGlobal.$j( 'body' ),
				$window = ThriveGlobal.$j( window ),
				_tempMargin = $body.css( 'margin-top' );
			$body[0].style.removeProperty( 'margin-top' );
			$parent.find( '.thrv_responsive_video iframe, .thrv_custom_html_shortcode iframe, .thrv_responsive_video video' ).each( function () {
				var $this = ThriveGlobal.$j( this );
				$this.attr( 'data-src', $this.attr( 'src' ) );
				$this.attr( 'src', '' );
			} );
			$body.css( 'margin-top', _tempMargin );
			$window.scrollTop( 0 );
			$body.animate( {'margin-top': 0 + 'px'}, 600, 'linear', function () {
				TL_Front.form_closed( type );
			} ).removeClass( 'tve-tl-gr-anim' );

			break;
	}
};

TL_Front.form_closed = function ( type ) {
	if ( TL_Const.close_callbacks && TL_Const.close_callbacks[type] ) {
		ThriveGlobal.$j.each( TL_Const.close_callbacks[type], function ( i, f ) {
			if ( ThriveGlobal.$j.isFunction( f ) ) {
				f();
			}
		} );
	}
};

TL_Front.handle_typefocus = function ( element, action ) {
	element.find( '.tve_typefocus' ).each( function () {
		var $this = jQuery( this );
		$this.attr( 'data-typefocus', action );
	} );
};