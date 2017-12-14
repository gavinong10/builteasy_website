<?php
$all_fonts = tve_dash_get_font_family_array();

$link         = 'https://www.googleapis.com/webfonts/v1/webfonts?key=';
$key          = 'AIzaSyDJhU1bXm2YTz_c4VpWZrAyspOS37Nn-kI';
$request      = wp_remote_get( $link . $key, array( 'sslverify' => false ) );
$response     = json_decode( wp_remote_retrieve_body( $request ), true );
$google_fonts = $response['items'];
$safe_fonts   = tve_dash_font_manager_get_safe_fonts();

$imported_fonts     = Tve_Dash_Font_Import_Manager::getImportedFonts();
$imported_fonts_css = Tve_Dash_Font_Import_Manager::getCssFile();

$prefered_fonts = array();
if ( ! empty( $google_fonts ) ) {
	foreach ( $google_fonts as $key => $f ) {
		if ( array_key_exists( $f['family'], $all_fonts ) ) {
			$prefered_fonts[] = $f;
		}
	}
}
$font_id = $_GET['font_id'];

if ( isset( $_GET['font_action'] ) && $_GET['font_action'] == 'update' ) {
	$options = json_decode( get_option( 'thrive_font_manager_options' ), true );
	foreach ( $options as $option ) {
		if ( $option['font_id'] == $font_id ) {
			$font = $option;
		}
	}
	$admin_font_manager_link = "admin-ajax.php?action=tve_dash_font_manager_edit";
} else {
	$admin_font_manager_link = "admin-ajax.php?action=tve_dash_font_manager_add";
}

?>

<div class="font-manager-settings" style="margin: 20px">
	<div id="fontPreview" style="font-size: 20px; margin-bottom: 10px;">
		Grumpy wizards make toxic brew for the evil Queen and Jack.
	</div>
	<hr>
	<div>
		<div>
			<input type="radio" name="display_fonts" id="ttfm_google_fonts" value="google_fonts"/>
			<label for="ttfm_google_fonts"> <?php echo __( "Show all fonts", TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
		</div>
		<div>
			<input type="radio" name="display_fonts" id="ttfm_prefered_fonts" value="prefered_fonts" checked/>
			<label
				for="ttfm_prefered_fonts"><?php echo __( "Recommended Fonts Only", TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
		</div>
		<div>
			<input type="radio" name="display_fonts" id="ttfm_safe_fonts" value="safe_fonts"/>
			<label for="ttfm_safe_fonts"><?php echo __( "Web Safe Fonts", TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
		</div>
		<div>
			<input type="radio" name="display_fonts" id="ttfm_imported_fonts" value="imported_fonts"/>
			<label for="ttfm_imported_fonts"><?php echo __( "Imported Fonts", TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
		</div>
		<div class="tvd-row">
			<a target="_blank" href="//www.google.com/fonts" class="tvd-right">
				<?php echo __( "View All Google Font Previews", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
		</div>
	</div>

	<div class="tvd-row input-field">
		<select id="ttfm_fonts">
			<option value="none" disabled
			        selected><?php echo __( "- Select Font -", TVE_DASH_TRANSLATE_DOMAIN ); ?></option>
			<?php foreach ( $prefered_fonts as $name => $f ): ?>
				<option data-url='<?php echo json_encode( $f['files'] ); ?>'
					<?php if ( isset( $f['font_name'] ) && $f['family'] == $f['font_name'] ) {
						echo 'selected';
					} ?>
					    value="<?php echo $f['family']; ?>">
					<?php echo $f['family']; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="tvd-row">
		<div id="ttfm-font-regular" class="tvd-col tvd-m4 tvd-s12"></div>
		<div id="ttfm-font-bold" class="tvd-col tvd-m4 tvd-s12"></div>
		<div id="ttfm-font-subsets" class="tvd-col tvd-m4 tvd-s12"></div>
	</div>
	<div style="clear: both"></div>
	<hr>
	<div>
		<div class="tvd-row">
			<div class="tvd-col tvd-m4"><?php echo __( "Class", TVE_DASH_TRANSLATE_DOMAIN ); ?></div>
			<div class="tvd-col tvd-m8">
				<input id="ttfm-font-class" type="text" readonly value="ttfm<?php echo $font_id; ?>">
			</div>
		</div>

		<div id="ttfm-font-size" class="tvd-row">
			<div class="tvd-col tvd-m4 tvd-s12"><?php echo __( "Size", TVE_DASH_TRANSLATE_DOMAIN ); ?></div>
			<div class="tvd-col tvd-m4 tvd-s12">
				<input min="0"
				       type="number" <?php if ( ! empty( $font['font_size'] ) && $font['font_size'] != (int) $font['font_size'] ): ?> step="0.1" <?php endif; ?>
				       value="<?php echo empty( $font['font_size'] ) ? '1.6' : floatval( $font['font_size'] ); ?>">
			</div>
			<div class="tvd-col tvd-m4 tvd-s12 input-field">
				<select>
					<option <?php if ( isset( $font['font_size'] ) && strpos( $font['font_size'], 'em' ) > 0 ) {
						echo 'selected';
					} ?>>
						em
					</option>
					<option <?php if ( isset( $font['font_size'] ) && strpos( $font['font_size'], 'px' ) > 0 ) {
						echo 'selected';
					} ?>>
						px
					</option>
					<option <?php if ( isset( $font['font_size'] ) && strpos( $font['font_size'], '%' ) > 0 ) {
						echo 'selected';
					} ?>>
						%
					</option>
				</select>
			</div>
		</div>

		<div id="ttfm-font-height" class="tvd-row">
			<div class="tvd-col tvd-m4 tvd-s12"><?php echo __( "Line Height", TVE_DASH_TRANSLATE_DOMAIN ); ?></div>
			<div class="tvd-col tvd-m4 tvd-s12">
				<input min="0"
				       type="number" <?php if ( ! empty( $font['font_height'] ) && $font['font_height'] != (int) $font['font_height'] ): ?> step="0.1" <?php endif; ?>
				       value="<?php echo empty( $font['font_height'] ) ? '1.6' : floatval( $font['font_height'] ); ?>">
			</div>
			<div class="tvd-col tvd-m4 tvd-s12 input-field">
				<select>
					<option <?php if ( isset( $font['font_height'] ) && strpos( $font['font_height'], 'em' ) > 0 ) {
						echo 'selected';
					} ?>>
						em
					</option>
					<option <?php if ( isset( $font['font_height'] ) && strpos( $font['font_height'], 'px' ) > 0 ) {
						echo 'selected';
					} ?>>
						px
					</option>
					<option <?php if ( isset( $font['font_height'] ) && strpos( $font['font_height'], '%' ) > 0 ) {
						echo 'selected';
					} ?>>
						%
					</option>
				</select>
			</div>
		</div>
		<div class="tvd-row">
			<div class="tvd-col tvd-m4"><?php echo __( "Color", TVE_DASH_TRANSLATE_DOMAIN ); ?></div>
			<div class="tvd-col tvd-m8">
				<input type="text" value="<?php if ( isset( $font['font_color'] ) ) {
					echo $font['font_color'];
				} ?>"
				       class="wp-color-picker-field" data-default-color="#ffffff"/>
			</div>
		</div>
		<div class="tvd-row">
			<div class="tvd-col tvd-m4"><?php echo __( "Custom CSS", TVE_DASH_TRANSLATE_DOMAIN ); ?></div>
			<div class="tvd-col tvd-m8">
                <textarea
	                id="ttfm-custom-css"><?php if ( isset( $font['custom_css'] ) ) {
		                echo $font['custom_css'];
	                } ?></textarea>
			</div>
		</div>
	</div>
	<hr>

	<button id="ttfm_save_font_options"
	        class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-green tvd-right"><?php echo __( "Save", TVE_DASH_TRANSLATE_DOMAIN ) ?></button>

	<div class="clear"></div>
</div>


<script type="text/javascript">
	jQuery( '#TB_window' ).addClass( 'fmp' );
	jQuery( document ).ready( function ( $ ) {
		$( 'select' ).select2();
		$( '.wp-color-picker-field' ).wpColorPicker();
		window.prefered_fonts = <?php echo json_encode( $prefered_fonts ); ?>;
		window.google_fonts = <?php echo json_encode( $google_fonts ); ?>;
		window.safe_fonts = <?php echo json_encode( $safe_fonts ); ?>;
		window.imported_fonts = <?php echo json_encode( $imported_fonts ); ?>;
		window.imported_fonts_css = '<?php echo $imported_fonts_css; ?>';
		window.selected_fonts_set = jQuery( 'input[name="display_fonts"]:checked' ).val();
		var update_font = '<?php echo empty( $font['font_name'] ) ? 0 : $font['font_name']; ?>',
			current_font = <?php echo json_encode( empty( $font ) ? array() : $font ) ?>,
			font_variants;

		rewriteSettings( current_font );

		jQuery( '#ttfm_fonts' ).change( function () {
			jQuery( '#ttfm-font-regular' ).html( '<b>Regular Style</b> <br />' );
			jQuery( '#ttfm-font-bold' ).html( '<b>Bold Style</b> <br />' );
			jQuery( '#ttfm-font-subsets' ).html( '<b>Character Set</b><br/>' );
			for ( var i in window[selected_fonts_set] ) {
				if ( window[selected_fonts_set][i].family == jQuery( this ).val() ) {
					font_variants = window[selected_fonts_set][i].variants;
					for ( var j in window[selected_fonts_set][i].variants ) {
						jQuery( '<input>' ).attr( {
							id: 'tve-dash-regular-' + font_variants[j],
							type: 'radio',
							name: 'ttfm-font-style',
							value: font_variants[j],
							checked: font_variants[j] == current_font.font_style
						} ).appendTo( '#ttfm-font-regular' );
						jQuery( '#ttfm-font-regular' ).append( '<label for="tve-dash-regular-' + font_variants[j] + '">' + font_variants[j] + '</label><br/>' );
						if ( window[selected_fonts_set][i].variants[j] > 400 ) {
							jQuery( '<input>' ).attr( {
								id: 'tve-dash-bold-' + font_variants[j],
								type: 'radio',
								name: 'ttfm-font-bold',
								value: font_variants[j],
								checked: font_variants[j] == current_font.font_bold
							} ).appendTo( '#ttfm-font-bold' );
							jQuery( '#ttfm-font-bold' ).append( '<label for="tve-dash-bold-' + font_variants[j] + '">' + font_variants[j] + '</label><br/>' );
						}
					}
					for ( j in window[selected_fonts_set][i].subsets ) {
						jQuery( '<input/>' ).attr( {
							id: 'tve-dash-character-' + window[selected_fonts_set][i].subsets[j],
							type: 'radio',
							name: 'ttfm-font-character-sets',
							value: window[selected_fonts_set][i].subsets[j],
							checked: window[selected_fonts_set][i].subsets[j] == current_font.font_character_set
						} ).appendTo( '#ttfm-font-subsets' );
						jQuery( '#ttfm-font-subsets' ).append( '<label for="tve-dash-character-' + window[selected_fonts_set][i].subsets[j] + '">' + window[selected_fonts_set][i].subsets[j] + '</label><br/>' );
					}
					/* check default values for new fonts */
					if ( current_font.length == 0 ) {
						jQuery( 'input[name="ttfm-font-style"]' ).filter( function () {
							return this.value == 'regular'
						} ).prop( 'checked', true );
						jQuery( 'input[name="ttfm-font-character-sets"]' ).filter( function () {
							return this.value == 'latin'
						} ).prop( 'checked', true );
					}
				}
			}
			importFont();
		} );
		jQuery( document ).on( 'change', 'input#ttfm_google_fonts', function () {
			add_fonts( google_fonts );
			window.selected_fonts_set = this.value;
			$( 'select' ).select2();
		} );
		jQuery( document ).on( 'change', 'input#ttfm_prefered_fonts', function () {
			add_fonts( prefered_fonts );
			window.selected_fonts_set = this.value;
			$( 'select' ).select2();
		} );
		jQuery( document ).on( 'change', 'input#ttfm_safe_fonts', function () {
			add_fonts( safe_fonts );
			window.selected_fonts_set = this.value;
			$( 'select' ).select2();
		} );
		jQuery( document ).on( 'change', 'input#ttfm_imported_fonts', function () {
			add_fonts( imported_fonts );
			window.selected_fonts_set = this.value;
			$( 'select' ).select2();
		} );
		function isSafeFont( font ) {
			var _isSafeFont = false;
			jQuery( safe_fonts ).each( function ( index, safe_font ) {
				if ( font === safe_font.family ) {
					_isSafeFont = true;
					return;
				}
			} );

			return _isSafeFont;
		}

		function isImportedFont( font ) {
			var _isImportedFont = false;
			jQuery( imported_fonts ).each( function ( index, imported_font ) {
				if ( font === imported_font.family ) {
					_isImportedFont = true;
					return;
				}
			} );

			return _isImportedFont;
		}

		function add_fonts( fonts ) {
			jQuery( '#ttfm_fonts option' ).remove();
			var select = jQuery( '#ttfm_fonts' );
			for ( var i in fonts ) {
				select.append( $( '<option>', {
					text: fonts[i].family,
					value: fonts[i].family,
					selected: fonts[i].family == current_font.font_name
				} ) );
			}
			setTimeout( function () {
				select.trigger( 'change' );
			}, 100 );
		}

		jQuery( document ).on( 'change', 'input[name="ttfm-font-style"]', function () {
			if ( jQuery( '#ttfm_fonts' ).val() != 'none' ) {
				importFont();
			}
		} );
		jQuery( '#ttfm_save_font_options' ).click( function () {

			if ( jQuery( '#ttfm_fonts' ).val() == 'none' ) {
				alert( 'Please select a font!' );
				return;
			}

			if ( ! jQuery( 'input[name="ttfm-font-style"]' ).is( ':checked' ) ) {
				alert( 'Plese select a font style!' );
				return;
			}

			if ( ! jQuery( 'input[name="ttfm-font-character-sets"]' ).is( ':checked' ) ) {
				alert( 'Plese select a font character set!' );
				return;
			}
			var style = jQuery( 'input[name="ttfm-font-style"]:checked' ).val(),
				bold = jQuery( 'input[name="ttfm-font-bold"]:checked' ).val(),
				subset = jQuery( 'input[name="ttfm-font-character-sets"]:checked' ).val(),
				italic = '';
			if ( style == 'regular' ) {
				style = 400;
				if ( jQuery.inArray( 'italic', font_variants ) !== - 1 ) {
					italic = ',400italic';
				}
			} else if ( style == 'italic' ) {
				style = '400italic';
			}
			if ( style == bold ) {
				bold = 0;
			}
			if ( jQuery.inArray( bold + 'italic', font_variants ) > - 1 ) {
				italic += ',' + bold + 'italic';
			}
			if ( jQuery.inArray( style + 'italic', font_variants ) > - 1 ) {
				italic += ',' + style + 'italic';
			}
			if ( bold == undefined ) {
				jQuery.each( font_variants, function ( key, value ) {
					var _value = parseInt( value );
					if ( bold == undefined && ! isNaN( _value ) && _value > 400 && (
							isNaN( style ) || (
								! isNaN( style ) && _value > style
							)
						) ) {
						bold = value;
					}
				} );
			}
			if ( typeof bold === 'undefined' ) {
				bold = 0;
			}
			if ( italic == '' ) {
				italic = 0;
			}
			if ( subset == 'latin' ) {
				subset = 0;
			}
			var font_manager_link = '<?php echo $admin_font_manager_link; ?>',
				postData = {
					<?php if ( isset( $font_id ) ) {
					echo 'font_id:' . $font_id . ',';
				} ?>
					font_name: jQuery( '#ttfm_fonts' ).val() + '',
					font_style: style + '',
					font_bold: bold + '',
					font_italic: italic + '',
					font_character_set: subset + '',
					font_class: jQuery( '#ttfm-font-class' ).val() + '',
					font_size: jQuery( '#ttfm-font-size input' ).val() + jQuery( '#ttfm-font-size select' ).val(),
					font_height: jQuery( '#ttfm-font-height input' ).val() + jQuery( '#ttfm-font-height select' ).val(),
					font_color: jQuery( '.wp-color-picker-field' ).val(),
					custom_css: jQuery( '#ttfm-custom-css' ).val()
				};
			jQuery.post( font_manager_link, postData, function ( response ) {
				location.reload();
			} );
		} );

		if ( update_font != 0 ) {
			if ( isSafeFont( update_font ) ) {
				jQuery( 'input#ttfm_safe_fonts' ).click();
			} else if ( isImportedFont( update_font ) ) {
				jQuery( 'input#ttfm_imported_fonts' ).click();
			} else {
				jQuery( 'input#ttfm_google_fonts' ).click();
			}
			jQuery( '#ttfm_fonts' ).val( update_font ).trigger( 'change' );
			jQuery( 'input[name="ttfm-font-style"]' ).trigger( 'change' ).filter( function () {
				return this.value == '<?php echo isset( $font["font_style"] ) ? $font["font_style"] : ""; ?>' || (
						this.value === 'italic' && current_font.font_style === '400italic'
					);
			} ).prop( 'checked', true );
			jQuery( 'input[name="ttfm-font-bold"]' ).filter( function () {
				return this.value == '<?php echo isset( $font["font_bold"] ) ? $font["font_bold"] : ""; ?>';
			} ).prop( 'checked', true );
			jQuery( 'input[name="ttfm-font-character-sets"]' ).filter( function () {
				return this.value == '<?php echo isset( $font["font_character_set"] ) ? $font["font_character_set"] : ""; ?>';
			} ).prop( 'checked', true );
		}
	} );
	function prepareFontFamily( font_family ) {
		var chunks = font_family.split( "," ),
			length = chunks.length,
			font = '';

		jQuery( chunks ).each( function ( i, value ) {
			font += "'" + value.trim() + "'";
			font += i + 1 != length ? ", " : '';
		} );

		return font;

	}
	function importFont() {

		var font = jQuery( '#ttfm_fonts' ).val(),
			style = jQuery( 'input[name="ttfm-font-style"]:checked' ).val(),
			subset = jQuery( 'input[name="ttfm-font-character-sets"]:checked' ).val();
		if ( style == 'regular' ) {
			style = undefined;
		} else if ( style == 'italic' ) {
			style = '400italic';
		}
		if ( subset == 'latin' ) {
			subset = undefined;
		}

		if ( window.selected_fonts_set === 'google_fonts' || window.selected_fonts_set === 'prefered_fonts' ) {
			var font_link = "//fonts.googleapis.com/css?family=" + font.replace( " ", "+" ) + (
					style !== undefined ? ":" + style : ""
				) + (
				                subset !== undefined ? "&subset=" + subset : ""
			                );
			jQuery( '.imported-font' ).remove();
			jQuery( "head" ).prepend( "<link class='imported-font' href='" + font_link + "' rel='stylesheet' type='text/css'>" );
			jQuery( '#fontPreview' ).css( {'font-family': font} );
		} else if ( window.selected_fonts_set === 'imported_fonts' ) {
			jQuery( '.imported-font' ).remove();
			jQuery( "head" ).prepend( "<link class='imported-font' href='" + window.imported_fonts_css + "' rel='stylesheet' type='text/css'>" );
			jQuery( '#fontPreview' ).css( {
				'font-family': prepareFontFamily( font )
			} );
		} else {
			var _css = {
				'font-family': prepareFontFamily( font ),
				'font-style': 'normal',
				'font-weight': 'normal'
			};
			if ( style === '400italic' ) {
				_css['font-style'] = 'italic';
			}
			if ( ! isNaN( style ) ) {
				_css['font-weight'] = style;
			}
			jQuery( '#fontPreview' ).css( _css );
		}
	}

	function rewriteSettings( font ) {
		if ( font.font_bold == 0 ) {
			font.font_bold = font.font_style;
		}

		if ( font.font_style == 400 ) {
			font.font_style = 'regular';
		}

		if ( font.font_style == '400italic' ) {
			font.font_style = 'italic';
		}

		if ( font.font_character_set == 0 ) {
			font.font_character_set = 'latin';
		}
	}
</script>
