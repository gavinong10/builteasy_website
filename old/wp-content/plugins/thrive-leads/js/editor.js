var TVE_Content_Builder = TVE_Content_Builder || {};
TVE_Content_Builder.ext = TVE_Content_Builder.ext || {};

var TL_Editor = TL_Editor || {};
/** @var tve_leads_page_data Object */

/**
 * Extensions added to the TCB editor
 */
(function ( $ ) {

	$( document ).ready( function () {
		$( '.tl-state-minimized' ).draggable( {
			handle: '.multistep-lightbox-heading',
			stop: function ( event, ui ) {
				if ( "JSON" in window ) {
					Util.set_cookie( 'tve_leads_states_pos', JSON.stringify( ui.position ) );
				}
			}
		} );
	} );

	/**
	 * callback for the load_control_panel menu trigger - called when an element's control panel is opened
	 */
	TL_Editor.on_menu_show = function ( element ) {
		if ( element.is( '.thrv-ribbon' ) ) {
			load_control_panel_menu( element, 'form_ribbon' );
			return true;
		} else if ( element.is( '.thrv-leads-widget' ) ) {
			load_control_panel_menu( element, 'form_widget' );
			return true;
		} else if ( element.is( '.thrv-leads-slide-in' ) ) {
			load_control_panel_menu( element, 'form_slide_in' );
			return true;
		} else if ( element.is( '.thrv-leads-form-box' ) ) {
			load_control_panel_menu( element, 'lead_form' );
			return true;
		} else if ( element.is( '.thrv-leads-screen-filler' ) ) {
			load_control_panel_menu( element, 'form_screen_filler' );
			return true;
		} else if ( element.is( '.thrv-greedy-ribbon' ) ) {
			load_control_panel_menu( element, 'form_greedy_ribbon' );
			return true;
		} else if ( element.is( '.thrv-leads-in-content' ) ) {
			load_control_panel_menu( element, 'lead_form' );
			return true;
		}

	};

	/**
	 * selectors for any elements that should be editable
	 * @param current
	 * @returns {string}
	 */
	TL_Editor.editable_elements_extend = function ( current ) {
		return current;
	};

	/**
	 * callback applied when an element's control panel is populated with values
	 *
	 * @param type
	 * @param element
	 * @param $menu
	 */
	TL_Editor.load_button_values = function ( type, element, $menu ) {
		switch ( type ) {
			case 'form_ribbon':
				var _maxWidth = parseInt( element.find( '.tve-ribbon-content' ).outerWidth() );
				if ( ! isNaN( _maxWidth ) ) {
					$menu.find( '#ribbon_size' ).val( _maxWidth ).trigger( 'change' );
				}
				break;
			case 'form_slide_in':
				var _maxWidth = TVE_Content_Builder.max_width_px( element ),
					bw = parseInt( element.css( 'borderBottomWidth' ) );
				if ( _maxWidth ) {
					$menu.find( '#slide_in_size' ).val( _maxWidth ).trigger( 'change' );
				}
				bw = isNaN( bw ) ? 0 : bw;
				$menu.find( '.tl-border-width' ).val( bw );
				break;
			case 'form_widget':
				var bw = parseInt( element.css( 'borderBottomWidth' ) );
				bw = isNaN( bw ) ? 0 : bw;
				$menu.find( '.tl-border-width' ).val( bw );
				break;
			case 'lead_form':
				var bw = parseInt( element.css( 'borderBottomWidth' ) );
				bw = isNaN( bw ) ? 0 : bw;
				$menu.find( '.tl-border-width' ).val( bw );
				break;
			case 'form_screen_filler':
				var _maxWidth = TVE_Content_Builder.max_width_px( element.find( '.tve-screen-filler-content' ) );
				if ( ! _maxWidth ) {
					_maxWidth = parseInt( element.find( '.tve-screen-filler-content' ).outerWidth() );
				}
				$menu.find( '#screen_filler_size' ).val( _maxWidth ).trigger( 'change' );
				break;
			case 'form_greedy_ribbon':
				var _maxWidth = parseInt( element.find( '.tve-greedy-ribbon-content' ).outerWidth() );
				if ( ! isNaN( _maxWidth ) ) {
					$menu.find( '#greedy_ribbon_size' ).val( _maxWidth ).trigger( 'change' );
				}
				break;
		}
		/**
		 * some default controls
		 */
		if ( $menu.find( '.tve_brdr_radius' ).length ) {
			var radius = parseInt( element.css( 'border-top-left-radius' ) );
			radius = isNaN( radius ) ? 0 : radius;
			$menu.find( '.tve_brdr_radius' ).val( radius ).trigger( 'change' );
		}
		if ( $menu.find( '.tve_brdr_width' ).length ) {
			var bw = parseInt( element.css( 'borderBottomWidth' ) );
			bw = isNaN( bw ) ? 0 : bw;
			$menu.find( '.tve_brdr_width' ).val( bw );
		}
	};

	/**
	 * callback applied when the user selects an image from the WP media frame API and the type has not been identified by TCB
	 *
	 * @param type
	 * @param attachment
	 */
	TL_Editor.media_selected = function ( $element, type, attachment ) {
		switch ( type ) {
			case 'tl_form_bg':
				$element.tve_leads_form().bgImage( attachment );
				break;
			default:
				break;
		}
	};

	/**
	 * called after the main AJAX request callback - it will open the template chooser by default,
	 * allowing the user to choose a template the first time a form variation is edited
	 */
	TL_Editor.open_template_chooser = function () {
		TCB_Main.trigger( 'trigger_action', '#tl-tpl-chooser', 'click' );
		//hide the close button on the template chooser so that the user cannot cancel the process
		setTimeout( function () {
			jQuery( '#tve_lightbox_frame' ).find( '.tve-lightbox-close' ).hide();
			hide_sub_menu();
		}, 0 );
	};

	/**
	 * Filter data for cloud templates request
	 * Filter applied in TCB
	 */
	TL_Editor.filter_fetch_cloud_templates_data = function ( data ) {

		var $button = $( data.event.currentTarget );

		data.form_type = $button.attr( 'data-form-type' );
		data.action = 'tve_leads_cloud_templates';
		data.exclude_multi_step = $button.attr( 'data-variation-child' );
		/*Hide Choose template button when fetching thrive cloud templates*/
		jQuery( '#tve-leads-choose-template' ).hide();
		return data;
	};

	TL_Editor.filter_cloud_template_download_data = function ( data ) {

		var $button = $( data.event.currentTarget );

		data.action = 'tve_leads_cloud_templates';
		data.multi_step = $button.attr( 'data-multi-step' );
		data.form_type = $button.attr( 'data-form-type' );

		return data;
	};

	/**
	 * pre-process the HTML node to be inserted
	 *
	 * @param {object} $html jQuery wrapper over the HTML to be inserted
	 */
	TL_Editor.pre_process_content_template = function ( $html ) {
		var tl_classes = [
			'thrv-leads-slide-in',
			'thrv-greedy-ribbon',
			'thrv-leads-form-box',
			'thrv-ribbon',
			'thrv-leads-screen-filler',
			'thrv-leads-widget'
		];

		$.each( tl_classes, function ( i, cls ) {
			if ( $html.hasClass( cls ) ) {
				$html = $html.children();
				$html.find( '.tve-leads-close' ).remove();
				return false;
			}
		} );

		return $html;
	};

	/**
	 * hook into JS filters for TCB
	 */
	TVE_Content_Builder.add_filter( 'menu_show', TL_Editor.on_menu_show );
	TVE_Content_Builder.add_filter( 'editable_elements', TL_Editor.editable_elements_extend );
	TVE_Content_Builder.add_filter( 'load_button_values', TL_Editor.load_button_values );
	TVE_Content_Builder.add_filter( 'tcb_media_selected', TL_Editor.media_selected );
	TVE_Content_Builder.add_filter( 'tcb_fetch_cloud_templates', TL_Editor.filter_fetch_cloud_templates_data );
	TVE_Content_Builder.add_filter( 'tcb_cloud_template_download', TL_Editor.filter_cloud_template_download_data );
	TVE_Content_Builder.add_filter( 'tcb_insert_content_template', TL_Editor.pre_process_content_template );

	if ( ! tve_leads_page_data.has_content ) {
		TVE_Content_Builder.add_filter( 'main_ajax_callback', TL_Editor.open_template_chooser );
	}

	var Util = {
		tpl_ajax: function ( data, ajax_param ) {
			var params = {
				type: 'post',
				dataType: 'json',
				url: tve_leads_page_data.ajaxurl
			};
			TVE_Editor_Page.overlay();
			data.action = tve_leads_page_data.tpl_action;
			data._key = data._key || tve_leads_page_data._key;
			data.post_id = tve_leads_page_data.post_id;
			data.security = tve_leads_page_data.security;

			params.data = data;

			if ( ajax_param ) {
				for ( var k in ajax_param ) {
					params[k] = ajax_param[k];
				}
			}

			return jQuery.ajax( params, data );
		},
		state_ajax: function ( data, ajax_param ) {
			var params = {
				type: 'post',
				dataType: 'json',
				url: tve_leads_page_data.ajaxurl
			};
			TVE_Editor_Page.overlay();
			data.action = tve_leads_page_data.state_action;
			data._key = tve_leads_page_data._key;
			data.post_id = tve_leads_page_data.post_id;
			data.security = tve_leads_page_data.security;
			data.active_state = tve_leads_page_data._key;

			params.data = data;

			if ( ajax_param ) {
				for ( var k in ajax_param ) {
					params[k] = ajax_param[k];
				}
			}

			return jQuery.ajax( params, data );
		},
		/**
		 *
		 * @param cookie_name
		 * @param value
		 * @param expires number of seconds after the cookie should expire
		 * @param path
		 */
		set_cookie: function ( cookie_name, value, expires, path ) {
			path = path || '/';
			expires = expires || (30 * 24 * 3600);

			var _now = new Date(), sExpires;
			expires = parseInt( expires );
			_now.setTime( _now.getTime() + expires * 1000 );
			sExpires = _now.toUTCString();

			document.cookie = encodeURIComponent( cookie_name ) + '=' + encodeURIComponent( value ) + ';expires=' + sExpires + ';path=' + path;
		}
	};
	TVE_Content_Builder.ext.tve_leads = {
		/**
		 * actions for the template chooser, user-saved templates etc
		 */
		template: {
			/**
			 * choose a new template
			 * @param $btn
			 * @returns {boolean}
			 */
			choose: function ( $btn ) {
				var $selected = jQuery( '#tve-leads-tpl' ).find( '.tve_cell_selected:visible' );
				if ( ! $selected.length ) {
					$selected = jQuery( '#tve-leads-tpl' ).find( '.tve_cell_template_cloud_selected:visible' );
					if ( ! $selected.length ) {
						alert( tve_leads_page_data.L.alert_choose_tpl );
						return false;
					}
				}
				/**
				 * if the user is choosing a multi-step, warn him that he will lose all other states
				 */
				if ( tve_leads_page_data.has_content && $selected.find( '.multi_step' ).val() === '1' && ! confirm( tve_leads_page_data.L.confirm_multi_step ) ) {
					return false;
				}
				Util.tpl_ajax( {
					custom: 'choose',
					tpl: $selected.find( '.lp_code' ).val()
				} ).done( TVE_Content_Builder.ext.tve_leads.state.insertResponse );

				TVE_Content_Builder.controls.lb_close();
				jQuery( '#tve_lightbox_frame' ).find( '.tve-lightbox-close' ).show();

				/* if this tab was opened from dashboard link and that is still open, we sent an event for when the template is saved so we know we have valid content in it */
				window.opener && window.opener.jQuery( window.opener ).trigger( 'tl.tpl.save', {
					ID: TL_Const.custom_post_data.get_data.p,
					key: TL_Const.custom_post_data.get_data._key
				} );
			},
			reset: function () {
				if ( ! confirm( tve_leads_page_data.L.confirm_tpl_reset ) ) {
					return false;
				}
				Util.tpl_ajax( {
					custom: 'reset'
				} ).done( TVE_Content_Builder.ext.tve_leads.state.insertResponse );
				TVE_Content_Builder.controls.lb_close();
			},
			/**
			 * filter templates based on template categories
			 * @returns {*}
			 */
			filter: function () {
				var $container = jQuery( '#tve-leads-tpl' );
				var _cls = [];
				jQuery( '.tve-tpl-filter:checked' ).each( function () {
					_cls.push( this.value );
				} );
				if ( ! _cls.length ) {
					return $container.find( '.tve_grid_cell' ).show();
				}
				$container.find( '.tve_grid_cell' ).hide();
				jQuery.each( _cls, function ( k, v ) {
					$container.find( '.tve_grid_cell.tve-tpl-' + v ).show();
				} );
			},
			/**
			 * Save the current template as a user-defined one
			 */
			save: function ( $btn ) {
				var _name = $btn.parent().find( 'input#tve_landing_page_name' ).val();
				if ( ! _name ) {
					tve_add_notification( tve_leads_page_data.L.tpl_name_required, true, 5000 );
					return;
				}
				Util.tpl_ajax( {
					custom: 'save',
					name: $btn.parent().find( 'input#tve_landing_page_name' ).val()
				} ).done( function ( response ) {
					jQuery( '#tve_landing_page_msg' ).removeClass( 'tve_warning' ).addClass( 'tve_success' ).html( response.message );
					jQuery( '#tl-saved-templates' ).html( response.list );
					TVE_Editor_Page.overlay( 'close' );
				} );
			},
			delete_saved: function () {
				var $selected = jQuery( '#tve-leads-tpl' ).find( '.tve_cell_selected:visible' );

				if ( ! $selected.length ) {
					alert( tve_leads_page_data.L.alert_choose_tpl );
					return false;
				}

				if ( ! confirm( tve_leads_page_data.L.tpl_confirm_delete ) ) {
					return false;
				}

				Util.tpl_ajax( {
					custom: 'delete',
					tpl: $selected.find( '.lp_code' ).val()
				}, {
					dataType: 'html'
				} ).done( function ( response ) {
					jQuery( '#tl-saved-templates' ).html( response );
					TVE_Editor_Page.overlay( 'close' );
				} );
			},
			/**
			 * get all the user saved templates
			 * @param filter
			 */
			get_saved: function () {
				var current_template = jQuery( '#tl-user-current-templates:checked' ).length;
				Util.tpl_ajax( {
					custom: 'get_saved',
					current_template: current_template
				}, {
					dataType: 'html'
				} ).done( function ( response ) {
					TVE_Editor_Page.overlay( 'close' );
					jQuery( '#tl-saved-templates' ).html( response );
				} );
				return false;
			},
			user_tab_clicked: function ( $btn ) {
				/*Hide choose template button when user is in thrive template cloud*/
				if ( $btn.is( '#tve_cloud_templates' ) ) {
					jQuery( '#tve-leads-choose-template' ).hide();
				} else {
					jQuery( '#tve-leads-choose-template' ).show();
				}

				if ( $btn.is( '#tve_leads_saved_templates' ) ) {
					setTimeout( this.get_saved, 150 );
				}

				return false;
			},
			select: function ( $btn, element, event ) {
				if ( $btn.find( '.lp_code' ).length && ! $btn.hasClass( 'tve-templates-cloud' ) ) {
					$btn.parents( '#tve_cloud_template_list' ).find( '.tve_cell_selected' ).removeClass( 'tve_cell_selected' );
					$btn.addClass( 'tve_cell_selected' );
				}
			},
			open: function ( $btn ) {
				$btn.parents( '#tve_cloud_template_list' ).find( '.tve_cell_template_cloud_selected' ).removeClass( 'tve_cell_selected' );
				$btn.parents( '#tve_cloud_template_list' ).find( '.tve_cell_template_cloud_selected' ).removeClass( 'tve_cell_template_cloud_selected' );
				$btn.parents( '.tve-templates-cloud' ).addClass( 'tve_cell_template_cloud_selected' );
				this.choose( $btn );
			}
		},
		/**
		 * show the WP media API selector for form backgrounds
		 */
		open_form_bg_media: function ( $btn, $element ) {
			thrive_open_media( null, 'load', 'tl_form_bg', $element );
		},
		/**
		 * open the form settings menu - this is triggered from the side Control Panel menu
		 * @param $btn
		 */
		form_settings: function ( $btn, element, e ) {
			hide_sub_menu();
			var $wrapper = jQuery( $btn.data( 'element-selector' ) );
			e.target = $wrapper;
			e.currentTarget = $wrapper;
			tve_editor_init( e, $wrapper );
		},
		/**
		 * set border width for the form
		 * @param $input
		 * @param element
		 */
		formBorderWidth: function ( $input, element ) {
			element.css( 'border-width', $input.val() );
		},
		/**
		 * handles all user interactions related to form states
		 */
		state: {
			insertResponse: function ( response ) {
				if ( ! response ) {
					tve_add_notification( 'Something went wrong', true );
				}
				/** custom CSS */
				jQuery( '.tve_custom_style,.tve_user_custom_style' ).remove();
				TVE_Content_Builder.CSS_Rule_Cache.clear();
				jQuery( 'head' ).append( response.custom_css );

				/** template-related CSS and fonts */
				if ( ! response.css.thrive_events ) {
					jQuery( '#thrive_events-css,#tve_lightbox_post-css' ).remove();
				}
				jQuery.each( response.css, function ( _id, href ) {
					if ( ! jQuery( '#' + _id + '-css' ).length ) {
						jQuery( 'head' ).append( '<link href="' + href + '" type="text/css" rel="stylesheet" id="' + _id + '-css"/>' );
					}
				} );

				/**
				 * custom body classes needed for lightboxes
				 */
				jQuery( 'body' ).removeClass( 'tve-l-open tve-o-hidden tve-lightbox-page' ).addClass( response.body_class );

				/**
				 * custom page buttons need changing (reset contents, choose template, x settings)
				 */
				jQuery( '#tve-leads-page-tpl-options' ).html( response.page_buttons );

				/**
				 * javascript params that need updating
				 */
				tve_path_params = jQuery.extend( tve_path_params, response.tve_path_params, true );

				/**
				 * if the template has changed, remove the old css (the new one will be added automatically)
				 */
				if ( tve_leads_page_data.current_css != response.tve_leads_page_data.current_css ) {
					jQuery( '#' + tve_leads_page_data.current_css + '-css' ).remove();
				}

				/**
				 * tve_leads javascript page data
				 */
				tve_leads_page_data = jQuery.extend( tve_leads_page_data, response.tve_leads_page_data, true );

				jQuery( '#tl-form-states' ).html( response.state_bar );
				jQuery( '#tve-leads-editor-replace' ).replaceWith( jQuery( response.main_page_content ) );
				TVE_Content_Builder.controls.lb_close();
				TVE_Editor_Page.initEditorActions();
				try {
					tve_init_sliders();
				} catch ( exception ) {
					console.log( exception );
				}
				setTimeout( function () {
					TVE_Editor_Page.overlay( 'close' );
				}, 1 );

			},
			add: function ( $link ) {
				if ( $link.attr( 'data-subscribed' ) ) {
					alert( tve_leads_page_data.L.only_one_subscribed );
					return;
				}
				var self = this;
				tve_save_post( 'true', function () {
					Util.state_ajax( {
						custom: 'add',
						state: $link.attr( 'data-state' )
					} ).done( jQuery.proxy( self.insertResponse, self ) );
				} ); // passed in callback function to skip the closing of overlay

			},
			duplicate: function ( $link, $element, event ) {
				if ( $link.attr( 'data-state' ) == 'already_subscribed' ) {
					alert( tve_leads_page_data.L.only_one_subscribed );
					return;
				}
				var self = this;
				tve_save_post( 'true', function () {
					Util.state_ajax( {
						custom: 'duplicate',
						id: $link.attr( 'data-id' )
					} ).done( self.insertResponse );
				} );

				event.stopPropagation();
			},
			visibility: function ( $link, $element, event ) {
				if ( ! $link.parents( 'li' ).hasClass( 'lightbox-step-active' ) || typeof $link.attr( 'data-visible' ) === 'undefined' ) {
					return;
				}

				var self = this;
				Util.state_ajax( {
					custom: 'visibility',
					visible: $link.attr( 'data-visible' )
				} ).done( function ( response ) {
					tve_add_notification( response.message );
					self.insertResponse( response );
				} );

				$link.toggleClass( 'tve-icon-visible tve-icon-invisible' );

				event.stopPropagation();
			},
			remove: function ( $link, $elemenet, event ) {
				if ( ! confirm( tve_leads_page_data.L.confirm_state_delete ) ) {
					return true;
				}
				var self = this;
				Util.state_ajax( {
					custom: 'delete',
					id: $link.attr( 'data-id' )
				} ).done( self.insertResponse );

				event.stopPropagation();
			},
			state_click: function ( $link ) {
				var self = this;
				tve_save_post( 'true', function () {
					self.display( $link.attr( 'data-id' ), true );
				} ); // passed in callback function to skip the closing of overlay
			},
			display: function ( id ) {

				/**
				 * get the state via ajax
				 */
				Util.state_ajax( {
					custom: 'display',
					id: id
				} ).done( jQuery.proxy( this.insertResponse, this ) );
			},
			/**
			 * collapse / expand the state manager container
			 */
			toggle_manager: function ( $link ) {
				var clsFn = 'addClass',
					cookie_exp = 30 * 24 * 3600,
					$target = jQuery( '.multistep-lightbox' );

				if ( $link.attr( 'data-expand' ) ) {
					clsFn = 'removeClass';
					cookie_exp = - cookie_exp;
				}

				jQuery( 'body' )[clsFn]( 'tl-state-collapse' );
				Util.set_cookie( 'tve_leads_state_collapse', 1, cookie_exp, '/' );

				$target.css( 'display', '' );
			},
			toggle_add: function ( $link ) {
				$link.toggleClass( 'tl-multistep-open' );
			}
		}
	};

	/**
	 * general plugin (handler) for all form types
	 * @returns {{bgPattern: Function, clearBgImage: Function, clearBgColor: Function, bgImage: Function}}
	 */
	$.fn.tve_leads_form = function () {
		var $element = this;

		return {
			bgPattern: function ( $btn ) {
				this.clearBgImage().clearBgColor();
				$element.css( {
					'background-image': "url('" + $btn.find( 'input[data-image]' ).attr( 'data-image' ) + "')",
					'background-repeat': 'repeat',
					'background-position': ''
				} );
			},
			clearBgImage: function () {
				$element.css( {
					'background-image': '',
					'background-repeat': '',
					'background-size': '',
					'background-position': ''
				} );
				return this;
			},
			clearBgColor: function () {
				if ( $element.tve_color_selector() ) {
					tve_remove_css_rule( $element.tve_color_selector(), 'background-color' );
				}
				$element.css( 'background-color', '' );
				return this;
			},
			bgImage: function ( wpAttachment ) {
				this.clearBgImage();
				$element.css( {
					'background-image': "url('" + wpAttachment.url + "')",
					'background-repeat': '',
					'background-size': 'cover',
					'background-position': 'center center'
				} );
			}
		};
	}
})( jQuery );