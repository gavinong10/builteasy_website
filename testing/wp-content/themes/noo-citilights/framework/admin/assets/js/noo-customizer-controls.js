/**
 * NOO Customizer Package.
 *
 * Javascript used in NOO-Customizer control
 * This file contains all the script used on Control part of NOO-Customizer.
 *
 * @package    NOO Framework
 * @subpackage NOO Customizer
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================


( function ( $ ) {

	//
	// Function for toggle Child Options with NOO Switch control.
	// parentOption     : parent Control, normally it's NOO Switch.
	// onChildOptions   : Controls that will display when parentOption = ON
	// offChildOptions  : parent Control, normally it's NOO Switch.
	//
	$.NooOnOff = function ( parentOption, onChildOptions, offChildOptions ) {
		if ( parentOption.is( ':checked' ) ) {
			if ( onChildOptions.length !== 0 )
				$.each( onChildOptions, function ( index, childOption ) {
					childOption.show().change();
				} );
			if ( offChildOptions.length === 0 )
				$.each( offChildOptions, function ( index, childOption ) {
					childOption.hide().change();
				} );
		} else {
			if ( onChildOptions.length === 0 )
				$.each( onChildOptions, function ( index, childOption ) {
					childOption.hide().change();
				} );
			if ( offChildOptions.length === 0 )
				$.each( offChildOptions, function ( index, childOption ) {
					childOption.show().change();
				} );
		}

		parentOption.change( function () {
			if ( parentOption.is( ':checked' ) ) {
				if ( onChildOptions.length === 0 )
					$.each( onChildOptions, function ( index, childOption ) {
						childOption.show().change();
					} );
				if ( offChildOptions.length === 0 )
					$.each( offChildOptions, function ( index, childOption ) {
						childOption.hide().change();
					} );
			} else {
				if ( onChildOptions.length === 0 )
					$.each( onChildOptions, function ( index, childOption ) {
						childOption.hide().change();
					} );
				if ( offChildOptions.length === 0 )
					$.each( offChildOptions, function ( index, childOption ) {
						childOption.show().change();
					} );
			}
		} );
	};

	// Object for creating WordPress 3.5 media upload menu 
	// for selecting theme images.
	wp.media.nooMediaManager = {

		init: function() {
			// Create the media frame.
			this.frame = wp.media.frames.nooMediaManager = wp.media({
				title: 'Choose Image',
				library: {
					type: 'image'
				},
				button: {
					text: 'Select',
				}
			});


			$('.choose-from-library-link').click( function( event ) {
				wp.media.nooMediaManager.$el = $(this);
				var controllerName = $(this).data('controller');
				event.preventDefault();

				wp.media.nooMediaManager.frame.open();
			});

			// When an image is selected, run a callback.
			this.frame.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = wp.media.nooMediaManager.frame.state().get('selection').first(),
				controllerName = wp.media.nooMediaManager.$el.data('controller');

				controller = wp.customize.control.instance(controllerName);
				controller.setting.set(attachment.attributes.id);
				controller.thumbnailSrc(attachment.attributes.url);
			});

		} // end init
	}; // end nooMediaManager

	wp.media.nooMediaManager.init();
} )( jQuery );

jQuery( document ).ready( function ( $ ) {

	// Alpha color picker
	$('.noo-color-control').each(function () {
		var $control = $(this),
		value = $control.val().replace(/\s+/g, ''),
		alpha_val = 100;
		if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
			alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
		}
		var palette_input = $control.attr('data-palette');
		var palette;
		if (palette_input == 'false' || palette_input === false) {
			palette = false;
		} else if (palette_input == 'true' || palette_input === true) {
			palette = true;
		} else {
			palette = $control.attr('data-palette').split(",");
		}
		$control.wpColorPicker({ // change some things with the color picker
			clear: function (event, ui) {
				// send ajax request to wp.customizer to enable Save & Publish button
				var key = $control.attr('data-customize-setting-link');
				wp.customize(key, function (obj) {
					obj.set('');
				});
				var picker_container = $(this).parents('.wp-picker-container:first');
				picker_container
					.find('.slider-alpha').slider('value',100)
					.find('.ui-slider-handle').text(100);
				picker_container.find('.wp-color-picker').val('').change();
				picker_container.find('.wp-color-result').css('backgroundColor', '');
			},
			change: function (event, ui) {
				// send ajax request to wp.customizer to enable Save & Publish button
				var _new_value = ui.color.toCSS();
				var key = $control.attr('data-customize-setting-link');
				wp.customize(key, function (obj) {
					obj.set(_new_value);
				});
				// change the background color of our transparency container whenever a color is updated
				$transparency = $control.parents('.wp-picker-container:first').find('.transparency');
				// we only want to show the color at 100% alpha
				$transparency.css('backgroundColor', ui.color.toString('no-alpha'));
			},
			palettes: palette
		});
		$('<div class="noo-alpha-container"><div class="slider-alpha"></div><div class="transparency"></div></div>').appendTo($control.parents('.wp-picker-container'));
		$alpha_slider = $control.parents('.wp-picker-container:first').find('.slider-alpha');
		$alpha_slider.slider({
			range: "min",
			min: 0,
			max: 100,
			value: alpha_val, // set initial value for slider on load
			slide: function (event, ui) {
				$(this).find('.ui-slider-handle').text(ui.value); // show value on slider handle
			},
			create: function (event, ui) {
				var v = $(this).slider('value');
				$(this).find('.ui-slider-handle').text(v);
			}
		}); // slider
		$alpha_slider.slider().on('slidechange', function (event, ui) {
			var alpha_val = parseFloat(ui.value),
			iris = $control.data('a8cIris'),
			color_picker = $control.data('wpWpColorPicker');
			iris._color._alpha = alpha_val / 100.0;
			$control.val(iris._color.toString());
			// send ajax request to wp.customizer to enable Save & Publish button
			var _new_value =iris._color.toCSS();
			var key = $control.attr('data-customize-setting-link');
			wp.customize(key, function (obj) {
				obj.set(_new_value);
			});
			color_picker.toggler.css({
				backgroundColor: $control.val()
			});
			// fix relationship between alpha slider and the 'side slider not updating.
			get_val = $control.val();
			$($control).wpColorPicker('color', get_val);
		});

		Color.prototype.toString = function (remove_alpha) {
			if (remove_alpha == 'no-alpha') {
				return this.toCSS('rgba', '1').replace(/\s+/g, '');
			}
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}
			var hex = parseInt(this._color, 10).toString(16);
			if (this.error) return '';
			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}
			return '#' + hex;
		};
	}); // each

	// Font functions
	$( 'select.noo-customize-chosen' ).chosen({
		allow_single_deselect: true,
		width: '240px'
	});

	$( 'select.noo-font-family' ).change( function () {
		var $This = $( this );

		var style_select_el = $This.siblings( 'select.noo-font-weight-and-style' );
		var selected_style = style_select_el.find( ":selected" ).val();
		style_select_el.find( "option:not(:first)" ).hide();

		var subset_select_el = $This.siblings( 'select.noo-font-subset' );
		var selected_subset = subset_select_el.find( ":selected" ).val();
		subset_select_el.find( "option:not(:first)" ).remove();

		var selected = $( this ).find( ":selected" );
		if ( selected.attr( 'data-style' ) ) {
			var variants = ( typeof selected.data( 'style' ) !== 'undefined' ) ? selected.data( 'style' ).toString().split( ',' ) : [];
			if ( variants.length !== 0 )
				$.each( variants, function ( index, variant ) {
					style_select_el.find( 'option[value="' + variant + '"]' ).show();
				} );
		}

		if ( selected.attr( 'data-subset' ) ) {
			var subsets = selected.data( 'subset' ) !== undefined ? selected.data( 'subset' ).split( ',' ) : [];
			if ( subsets.length !== 0 )
				$.each( subsets, function ( index, subset ) {
					var selected_str = subset == selected_subset ? 'selected="selected"' : '';
					subset_select_el.append( '<option value="' + subset + '"' + selected_str + '>' + subset + '</option>' );
				} );
		}
	} );

	$( 'select.noo-font-weight-and-style' ).change( function () {
		var $This = $( this );

		var weight_and_style = $This.find( ":selected" ).val();
		var weight = '';
		var style = '';
		if ( weight_and_style.indexOf( "italic" ) >= 0 ) {
			$This.siblings( 'input.noo-font-weight' ).val( weight_and_style.substring( 0, 3 ) ).change();
			$This.siblings( 'input.noo-font-style' ).val( 'italic' ).change();
		} else {
			$This.siblings( 'input.noo-font-weight' ).val( weight_and_style ).change();
			$This.siblings( 'input.noo-font-style' ).val( '' ).change();
		}

	} );

	// Add Import Settings form and Upload button
	$('form#customize-controls').after(
		$('<form></form>').attr('id', 'noo-import-form').append(
			$('<input/>')
				.attr('type', 'file')
				.attr('id', 'noo-customizer-settings-upload')
				.attr('name', "noo-customizer-settings-upload")
				.css('position', 'absolute')
				.css('top', '-100px'), // hack sercurity
			$('<input/>').attr('type','hidden').attr('name', 'action').val('noo_cusomizer_upload_settings')
		)
	);

	$( '#noo-customizer-settings-upload' ).change( function () {
		var $This = $( this );

		var formData = new FormData($('#noo-import-form')[0]);
		$.ajax({
				url: nooCustomizerL10n.ajax_url,
				type: 'POST',
				//Ajax events
				// Form data
				data: formData,
				//Options to tell JQuery not to process data or worry about content-type
				cache: false,
				contentType: false,
				processData: false
			},
			'json'
		).done(function(data) {
			if( data == '-1' || data.replace(/^\s+|\s+$/g, '') == '-1' || data == 'null' ) {
				alertify.log(nooCustomizerL10n.import_error_msg, 'customize_control_log' ,3000);
				return;
			}

			var settings = '';

			try {
				settings = JSON.parse(data);
			} catch (e) {
				alertify.log(nooCustomizerL10n.import_error_msg, 'customize_control_log' ,3000);
				return;
			}

			nodes  = $('[data-customize-setting-link]');
			radios = {};

			// Normal option type
			nodes.each( function() {
				var node = $(this),
					name,
					key = node.attr('data-customize-setting-link');

				if ( node.is(':radio') ) {
					name = node.prop('name');
					if ( radios[ name ] )
						return;

					radios[ name ] = true;
					node = nodes.filter( '[name="' + name + '"]' );
				}

				if( settings.hasOwnProperty(key)) {
					wp.customize(key, function (obj) {
						obj.set(settings[key]);
					});
					node.trigger("toggle_children");
				}
			});

			// Color options
			$('.customize-control-color').each( function() {
				var key = $(this).attr('id').substring( ("customize-control-").length );
				if( settings.hasOwnProperty(key)) {
					wp.customize(key, function (obj) {
						obj.set(settings[key]);
					});
					$control = $(this).find('.wp-color-picker');
					$control.wpColorPicker('color', settings[key]);
				}
			});

			// Image options
			$('.customize-control-image').each( function() {
				var key = $(this).find('.choose-from-library-link').data('controller');
				if( settings.hasOwnProperty(key) ) {
					var controller = wp.customize.control.instance(key);
					controller.setting.set(settings[key]);
					jQuery.ajax( nooCustomizerL10n.ajax_url, {
						type: 'POST',
						data: {
							'attachment_id': settings[key],
							'action'       : 'noo_ajax_get_attachment_url',
						}
					} ).done( function( data ) {
						if( data !== '' ) {
							controller.thumbnailSrc(data);
						}
					} );						
				}
			});
		}).fail(function() {
			alertify.log(nooCustomizerL10n.import_error_msg, 'customize_control_log' , 3000);
		}).always(function() {
		});
	} );

	// Export settings
	$(document).on("click", "a#noo-customizer-settings-download", function () {
		$.fileDownload(nooCustomizerL10n.export_url, {
			failCallback: function () {
				alertify.log(nooCustomizerL10n.export_fail_msg, 'customize_control_log' , 3000);
			}
		});

		return false; //this is critical to stop the click event which will trigger a normal file download!
	});

	// Specific Functions: Change name of controls base on user's behavior
	$( '#customize-control-noo_header_nav_position' ).find('input[type=radio]').click( function() {
		var value = $(this).val();
		switch( value ) {
			case 'static_top':
			case 'fixed_top':
				$( '#customize-control-noo_header_nav_height' ).find( 'span.customize-control-title' ).text( nooCustomizerL10n.navbar_height );
				break;
			case 'fixed_left':
			case 'fixed_right':
				$( '#customize-control-noo_header_nav_height' ).find( 'span.customize-control-title' ).text( nooCustomizerL10n.mobile_navbar_height );
				break;
		}
	});	
} );

jQuery(window).load(function () {
	// Specific Functions: Change logo image/text affect the floating logo image/text
	jQuery('#noo-switch-noo_header_use_image_logo').change( function() {
		var floating_logo = jQuery('#noo-checkbox-noo_header_nav_floating_logo').is(':checked');
		var nav_floating = jQuery('#noo-switch-noo_header_nav_floating').is(':checked');
		var checked = jQuery(this).is(':checked');
		if( nav_floating ) {
			if( floating_logo ) {
				if( checked ) {
					jQuery('#customize-control-noo_header_nav_floating_logo_image').show();
					jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').show();
					jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
				} else {
					jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
					jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
					jQuery('#customize-control-noo_header_nav_floating_logo_font_color').show();
				}
			} else {
				jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
			}
		} else {
			jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
		}
		
	});

	// Specific Functions: Enable floating need to check logo image/text first
	jQuery('#noo-switch-noo_header_nav_floating').change( function() {
		var checked = jQuery(this).is(':checked');
		var floating_logo = jQuery('#noo-checkbox-noo_header_nav_floating_logo').is(':checked');
		var logo_image = jQuery('#noo-switch-noo_header_use_image_logo').is(':checked');
		if( checked ) {
			if( floating_logo ) {
				if( logo_image ) {
					jQuery('#customize-control-noo_header_nav_floating_logo_image').show();
					jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').show();
					jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
				} else {
					jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
					jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
					jQuery('#customize-control-noo_header_nav_floating_logo_font_color').show();
				}
			} else {
				jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
			}
		} else {
			jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
		}
	});

	// Specific Functions: Change floating logo image/text need to check logo image/text first
	jQuery('#noo-checkbox-noo_header_nav_floating_logo').change( function() {
		var logo_image = jQuery('#noo-switch-noo_header_use_image_logo').is(':checked');
		var checked = jQuery(this).is(':checked');
		if( checked ) {
			if( logo_image ) {
				jQuery('#customize-control-noo_header_nav_floating_logo_image').show();
				jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').show();
				jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
			} else {
				jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_font_color').show();
			}
		} else {
			jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
		}
	});

	var nav_floating = jQuery('#noo-switch-noo_header_nav_floating').is(':checked');
	var floating_logo = jQuery('#noo-checkbox-noo_header_nav_floating_logo').is(':checked');
	var logo_image = jQuery('#noo-switch-noo_header_use_image_logo').is(':checked');
	if( nav_floating ) {
		if( floating_logo ) {
			if( logo_image ) {
				jQuery('#customize-control-noo_header_nav_floating_logo_image').show();
				jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').show();
				jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
			} else {
				jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
				jQuery('#customize-control-noo_header_nav_floating_logo_font_color').show();
			}
		} else {
			jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
			jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
		}
	} else {
		jQuery('#customize-control-noo_header_nav_floating_logo_image').hide();
		jQuery('#customize-control-noo_header_nav_floating_logo_retina_image').hide();
		jQuery('#customize-control-noo_header_nav_floating_logo_font_color').hide();
	}
});