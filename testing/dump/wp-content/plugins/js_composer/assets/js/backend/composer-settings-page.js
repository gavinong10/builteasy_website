/* global ajaxurl */
jQuery( document ).ready( function ( $ ) {
	$( '.wpb_settings_accordion' ).accordion( {
		active: (getCookie( 'wpb_js_composer_settings_group_tab' ) ? getCookie( 'wpb_js_composer_settings_group_tab' ) : false),
		collapsible: true,
		change: function ( event, ui ) {
			if ( 'undefined' !== typeof(ui.newHeader.attr( 'id' )) ) {
				setCookie( 'wpb_js_composer_settings_group_tab', '#' + ui.newHeader.attr( 'id' ), 365 * 24 * 60 * 60 );
			} else {
				setCookie( 'wpb_js_composer_settings_group_tab', '', 365 * 24 * 60 * 60 );
			}
		},
		heightStyle: 'content'
	} );
	$( '.wpb-settings-select-all-shortcodes' ).click( function ( e ) {
		e.preventDefault();
		$( this ).parent().parent().find( '[type=checkbox]' ).attr( 'checked', true );
	} );
	$( '.wpb-settings-select-none-shortcodes' ).click( function ( e ) {
		e.preventDefault();
		$( this ).parent().parent().find( '[type=checkbox]' ).removeAttr( 'checked' );
	} );
	$( '.vc_settings-tab-control' ).click( function ( e ) {
		e.preventDefault();
		if ( $( this ).hasClass( 'nav-tab-active' ) ) {
			return false;
		}
		var tab_id = $( this ).attr( 'href' );
		$( '.vc_settings-tabs > .nav-tab-active' ).removeClass( 'nav-tab-active' );
		$( this ).addClass( 'nav-tab-active' );
		/*
		@deprecated since 4.7.1 and will be removed in 4.8. #48381311685558
		$( '.vc_settings-tab-content' ).hide().removeClass( 'vc_settings-tab-content-active' );
		$( tab_id ).fadeIn( 400, function () {
			$( this ).addClass( 'vc_settings-tab-content-active' );
			if ( window.css_editor ) {
				window.css_editor.focus();
			}
		} );*/
	} );
	$( '.vc_settings-tab-content' ).submit( function () {
		return true;
	} );

	$( '.vc_show_example' ).click( function ( e ) {
		e.preventDefault();
		var $helper = $( '.vc_helper' );
		if ( $helper.is( ':animated' ) ) {
			return false;
		}
		$helper.toggle( 100 );
	} );

	$( '#vc_settings-custom-css-reset-data' ).click( function ( e ) {
		e.preventDefault();
		if ( confirm( window.i18nLocaleSettings.are_you_sure_reset_css_classes ) ) {
			$( '#vc_settings-element_css-action' ).val( 'remove_all_css_classes' );
			$( '#vc_settings-element_css' ).attr( 'action', window.location.href ).trigger( 'submit' );
		}
	} );
	$( '.color-control' ).wpColorPicker();
	$( '#vc_settings-color-restore-default' ).click( function ( e ) {
		e.preventDefault();
		if ( confirm( window.i18nLocaleSettings.are_you_sure_reset_color ) ) {
			$( '#vc_settings-color-action' ).val( 'restore_color' );
			$( '#vc_settings-color' ).attr( 'action', window.location.href ).find( '[type=submit]' ).click();
		}
	} );
	$( '#wpb_js_use_custom' ).change( function () {
		if ( this.checked ) {
			$( '#vc_settings-color' ).addClass( 'color_enabled' );
		} else {
			$( '#vc_settings-color' ).removeClass( 'color_enabled' );

		}
	} );

	function showUpdaterSubmitButton() {
		$( '#vc_settings-updater [type=submit]' ).attr( 'disabled', false );
	}

	function hideUpdaterSubmitButton() {
		$( '#vc_settings-updater [type=submit]' ).attr( 'disabled', true );
	}

	$( '#vc_settings-activate-license' ).click( function ( e ) {
		var $button = $( this ),
			$username = $( '[name=wpb_js_envato_username]' ),
			$key = $( '[name=wpb_js_js_composer_purchase_code]' ),
			status = $( '#vc_settings-license-status' ).val(),
			$api_key = $( '[name=wpb_js_envato_api_key]' ),
			message_html = '<div id="vc_license-activation-message" class="updated vc_updater-result-message hidden"><p><strong>{message}</strong></p></div>';
		if ( true === $button.attr( 'disabled' ) ) {
			return false;
		}
		$button.attr( 'disabled', true );
		$( '.form-invalid' ).removeClass( 'form-invalid' );
		$( '#vc_license-activation-message' ).remove();
		e.preventDefault();
		$( '#vc_updater-spinner' ).show();
		$.ajax( {
			type: 'POST',
			url: window.ajaxurl,
			dataType: 'json',
			data: {
				action: 'activated' === status ? 'wpb_deactivate_license' : 'wpb_activate_license',
				username: $username.val(),
				key: $key.val(),
				api_key: $api_key.val(),
				_vcnonce: window.vcAdminNonce
			}
		} ).done( function ( data ) {
			var code;
			if ( data.result && 'activated' !== status ) {
				$( '#vc_settings-license-status' ).val( 'activated' );
				$key.addClass( 'vc_updater-passive' ).attr( 'disabled', true );
				$username.addClass( 'vc_updater-passive' ).attr( 'disabled', true );
				$api_key.addClass( 'vc_updater-passive' ).attr( 'disabled', true );
				$( '#vc_settings-activate-license' ).html( i18nLocaleSettings.vc_updater_deactivate_license );
				message_html = message_html.replace( '{message}',
					i18nLocaleSettings.vc_updater_license_activation_success );
				hideUpdaterSubmitButton();
			} else if ( data.result && 'activated' === status ) {
				$( '#vc_settings-license-status' ).val( 'not_activated' );
				$key.removeClass( 'vc_updater-passive' ).attr( 'disabled', false );
				$username.removeClass( 'vc_updater-passive' ).attr( 'disabled', false );
				$api_key.removeClass( 'vc_updater-passive' ).attr( 'disabled', false );
				$( '#vc_settings-activate-license' ).html( i18nLocaleSettings.vc_updater_activate_license );
				message_html = message_html.replace( '{message}',
					i18nLocaleSettings.vc_updater_license_deactivation_success );
				showUpdaterSubmitButton();
			} else {
				message_html = message_html.replace( 'updated', 'error' );
				code = 'undefined' === typeof(data.code) ? parseInt( data.code ) : 300;

				switch ( code ) {
					case 100: // Empty data
						message_html = message_html.replace( '{message}', i18nLocaleSettings.vc_updater_empty_data );
						$key.closest( 'tr' ).addClass( 'form-invalid' );
						$username.closest( 'tr' ).addClass( 'form-invalid' );
						break;

					case 200: // Wrong purchase code.
						message_html = message_html.replace( '{message}',
							i18nLocaleSettings.vc_updater_wrong_license_key );
						$key.closest( 'tr' ).addClass( 'form-invalid' );
						break;

					case 300: // Wrong data.
						message_html = message_html.replace( '{message}', i18nLocaleSettings.vc_updater_wrong_data );
						break;

					case 401: // Already activated
						message_html = message_html.replace( '{message}',
							i18nLocaleSettings.vc_updater_already_activated );
						break;

					case 402: // Activated on other wordpress site.
						message_html = message_html.replace( '{message}',
							i18nLocaleSettings.vc_updater_already_activated_another_url ).replace( '{site}',
							data.activated_url );
						$key.closest( 'tr' ).addClass( 'form-invalid' );
						break;

					case 500:
						message_html = message_html.replace( '{message}',
							i18nLocaleSettings.vc_updater_deactivate_license );
						break;

					default:
						if ( data.message ) {
							message_html = message_html.replace( '{message}', i18nLocaleSettings[ data.message ] );
						} else {
							message_html = message_html.replace( '{message}', i18nLocaleSettings.vc_updater_error );
						}
				}
			}

			$( message_html ).insertAfter( '#vc_settings-activate-license' ).fadeIn( 100 );
			$button.attr( 'disabled', false );
			$( '#vc_updater-spinner' ).hide();
		} ).error( function ( data ) {
			$( message_html.replace( '{message}',
				i18nLocaleSettings.vc_updater_error ) ).insertAfter( '#vc_settings-activate-license' ).show( 100 );
			$( '#vc_updater-spinner' ).hide();
			$button.attr( 'disabled', false );
		} );
	} );
	var $css_editor = $( '#wpb_csseditor' );
	var $css_editor_input = $( "textarea.custom_css.wpb_csseditor" );
	if ( $css_editor.length ) {
		window.css_editor = new Vc_postSettingsEditor();
		window.css_editor.setEditor( $css_editor_input.val() );

		window.css_editor.getEditor().on( "change", function () {
			$css_editor_input.val( window.css_editor.getValue() );
		} );
	}

	$( '#vc_settings-vc-pointers-reset' ).click( function ( e ) {
		e.preventDefault();
		$.post( window.ajaxurl, {
			action: 'vc_pointer_reset',
			_vcnonce: window.vcAdminNonce
		} );
		$( this ).text( $( this ).data( 'vcDoneTxt' ) );
	} );

	function showMessageMore( text, typeClass, timeout, remove ) {
		if ( remove ) {
			$( '.vc_atm-message' ).remove();
		}
		var $message = $( '<div class="vc_atm-message ' + (typeClass ? typeClass : '') + '" style="display: none;"><p></p></div>' );
		$message.find( 'p' ).text( text );
		if ( ! _.isUndefined( timeout ) ) {
			window.setTimeout( function () {
				$message.fadeOut( 500, function () {
					$( this ).remove();
				} );
			}, timeout );
		}
		return $message;
	}

	var lessBuilding = false;
	$( '#vc_settings-color' ).submit( function ( e ) {
		e.preventDefault();
		if ( lessBuilding ) {
			return;
		}
		var form, $submitButton, $designCheckBox;

		form = this;
		$submitButton = $( '#submit_btn' );
		$designCheckBox = $( '#wpb_js_use_custom' );
		if ( $designCheckBox.prop( 'checked' ) && 'restore_color' !== $( '#vc_settings-color-action' ).val() ) {
			var modifyVars, variablesDataLinker, $spinner;

			lessBuilding = true;
			modifyVars = $( form ).serializeArray();
			variablesDataLinker = $submitButton.data( 'vc-less-variables' );
			$spinner = $( '<span class="vc_settings-less-spinner"></span>' );
			var preloaderUrl = ajaxurl.replace( /admin\-ajax\.php/, 'images/wpspin_light.gif' );
			$( '<img src="' + preloaderUrl + '" />' ).appendTo( $spinner );
			$submitButton.val( window.i18nLocaleSettings.saving );
			$spinner.insertBefore( $submitButton ).show();

			_.delay( function () {
				vc.less.build( {
					modifyVars: modifyVars,
					variablesDataLinker: variablesDataLinker,
					lessPath: $submitButton.data( 'vc-less-path' ),
					rootpath: $submitButton.data( 'vc-less-root' )
				}, function ( output, error ) {
					if ( ! _.isUndefined( output ) && ! _.isUndefined( output.css ) ) {
						$( '[name="wpb_js_compiled_js_composer_less"]' ).val( output.css );
						var $form = $( '#vc_settings-color' );
						$.ajax( {
							type: 'POST',
							url: $form.attr( 'action' ),
							data: $form.eq( 0 ).serializeArray(),
							success: function () {
								showMessageMore( window.i18nLocaleSettings.saved,
									'updated',
									5000,
									true ).insertBefore( $submitButton.parent() ).fadeIn( 500 );
								$submitButton.val( window.i18nLocaleSettings.save );
								lessBuilding = false;
								$spinner.remove();
							},
							error: function () {
								showMessageMore( window.i18nLocaleSettings.form_save_error,
									'error',
									undefined,
									true ).insertBefore( $submitButton.parent() ).fadeIn( 500 );
								$submitButton.val( window.i18nLocaleSettings.save );
								lessBuilding = false;
								$spinner.remove();
							}
						} );

					} else if ( ! _.isUndefined( error ) ) {
						window.console && window.console.error && window.console.error( error );
						showMessageMore( window.i18nLocaleSettings.save_error + ". " + error,
							'error',
							undefined,
							true ).insertBefore( $submitButton.parent() ).fadeIn( 500 );
						$submitButton.val( window.i18nLocaleSettings.save );
						lessBuilding = false;
						$spinner.remove();
					}
				} );
			}, 100 );
		} else {
			form.submit();
		}
	} );

} );
