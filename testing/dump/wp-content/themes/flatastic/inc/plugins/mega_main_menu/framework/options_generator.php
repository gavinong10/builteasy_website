<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */

if ( !function_exists('mad_mm_options_generator') ){

	/** 
	 * Build option row.
	 * @return $out
	 */
	function mad_mm_options_generator( $option, $mm_saved_value = false, $current_class = 'none' ){
		if ( is_string( $current_class ) || $current_class == 'none' ) {
			return false;
		}
		/* Check and set all most variables */
		$option['name'] = isset( $option['name'] ) ? $option['name'] : '';
		$option['descr'] = isset( $option['descr'] ) ? $option['descr'] : '';
		$option['key'] = isset( $option['key'] ) ? $option['key'] : 'key_no_set';
		$option['type'] = isset( $option['type'] ) ? $option['type'] : '';
		$option['values'] = isset( $option['values'] ) ? $option['values'] : '';
		$tmp_key_var = explode( '[', $option['key'] );
		$clear_key = str_replace( array( $current_class->constant[ 'MM_OPTIONS_NAME' ], '[',']'), '', end( $tmp_key_var ) );
		$clear_full_key = str_replace( array( $current_class->constant[ 'MM_OPTIONS_NAME' ], '[',']'), '', $option['key'] );
		$out = '';
		/* check field "type" and return actual sting */
		switch ( $option['type'] ) {
			case 'just_html':
				$out .= ( isset( $option['default'] ) 
					? $option['default']  
					: ( isset( $option['values'] ) 
						? $option['values'] 
						: ''
					)
				);
				break;
			case 'textarea':
				$col_width = isset( $option['col_width'] ) ? $option['col_width'] : 6;
				$out .= mad_mm_common::ntab(9) . '<textarea class="textarea wpb_vc_param_value" name="' . $option['key'] . '" rows="' . $col_width . '">' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
					? esc_textarea( $mm_saved_value ) 
					: ( isset( $option['default'] ) 
						? esc_textarea( $option['default'] )  
						: ( isset( $option['values'] ) 
							? esc_textarea( $option['values'] ) 
							: ''
						)
					) 
				) . '</textarea>';
				break;
			case 'checkbox':
				$col_width = isset( $option['col_width'] ) ? $option['col_width'] : 4;
				$out .= mad_mm_common::ntab(9) . '<input type="hidden" name="' . $option['key'] . '[]" value="is_checkbox" />';
				$out .= mad_mm_common::ntab(9) . '<div class="row">';
				if ( is_array( $option['values'] ) ) {
					foreach ( $option['values'] as $key => $value ) {
						$out .= mad_mm_common::ntab(10) . '<div class="mm_checkbox col-xs-' . $col_width . '">';
						$out .= mad_mm_common::ntab(11) . '<label><input type="checkbox" class="wpb_vc_param_value" name="' . $option['key'] . '[]" value="' . $value .'" ' . ( ( isset( $mm_saved_value ) && is_array( $mm_saved_value ) )
							? ( in_array( $value, $mm_saved_value ) 
								? 'checked="checked" ' 
								: ''
							)
							: ( (  isset( $option['default'] ) && ( in_array( $value, $option['default'] ) || $value == $option['default'] ) ) 
								? 'checked="checked" ' 
								: ''
							)
						) . '/>' . ( is_string( $key ) ? $key : $value ) .'</label>';
						$out .= mad_mm_common::ntab(10) . '</div>';
					}
				}
				$out .= mad_mm_common::ntab(9) . '</div>';
				break;
			case 'radio':
				$col_width = isset( $option['col_width'] ) ? $option['col_width'] : 6;
				$out .= mad_mm_common::ntab(9) . '<div class="row">';
				if ( is_array( $option['values'] ) ) {
					foreach ( $option['values'] as $key => $value ) {
						$out .= mad_mm_common::ntab(10) . '<div class="mm_radio col-xs-' . $col_width . '">';
						$out .= mad_mm_common::ntab(11) . '<label><input type="radio" class="wpb_vc_param_value" name="' . $option['key'] . '" value="' . $value .'" ' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
							? ( $value == $mm_saved_value 
								? 'checked="checked" ' 
								: ''
							)
							: ( ( isset( $option['default'] ) && ( in_array( $value, $option['default'] ) || $value == $option['default'] ) ) 
								? 'checked="checked" ' 
								: ''
							)
						) . '/>' . ( is_string( $key ) ? $key : $value ) .'</label>';
						$out .= mad_mm_common::ntab(10) . '</div>';
					}
				}
				$out .= mad_mm_common::ntab(9) . '</div>';
					break;
			case 'select':
				$out .= mad_mm_common::ntab(9) . '<select class="col-xs-12 form-control input-sm wpb_vc_param_value" name="' . $option['key'] . '">';
				if ( is_array( $option['values'] ) ) {
					foreach ( $option['values'] as $key => $value ) {
						$out .= mad_mm_common::ntab(10) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
							? ( $value == $mm_saved_value 
								? 'selected="selected" ' 
								: ''
							)
							: ( (  isset( $option['default'] ) && ( ( is_array( $option['default'] ) && in_array( $value, $option['default'] ) ) || $value == $option['default'] ) ) 
								? 'selected="selected" ' 
								: ''
							)
						) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
					}
				}
				$out .= mad_mm_common::ntab(9) . '</select>';
				break;
			case 'number':
				$col_width = isset( $option['col_width'] ) ? $option['col_width'] : 6;
				$step = isset( $option['step'] ) ? $option['step'] : 1;
				$min = isset( $option['min'] ) ? $option['min'] : 1;
				$max = isset( $option['max'] ) ? $option['max'] : 100;
				$input = '<input class="form-control input-sm col-xs-12 wpb_vc_param_value" type="number" step="' . $step . '" min="' . $min . '" max="' . $max . '" name="' . $option['key'] . '" value="' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false ) 
					? esc_attr( $mm_saved_value ) 
					: ( isset( $option['default'] ) 
						? esc_attr( $option['default'] )  
						: ( isset( $option['values'] ) 
							? $option['values'] 
							: ''
						)
					) 
				) . '" />';
				if ( isset( $option['units'] ) && !empty( $option['units'] ) ) {
					$out .= mad_mm_common::ntab(9) . '<div class="row">';
					$out .= mad_mm_common::ntab(10) . '<div class="input-group input-group-sm col-xs-' . $col_width . '">';
					$out .= mad_mm_common::ntab(11) . $input;
					$out .= mad_mm_common::ntab(11) . '<span class="input-group-addon">' . $option['units'] . '</span>';
					$out .= mad_mm_common::ntab(10) . '</div><!-- class="input-group input-group-sm" -->';
					$out .= mad_mm_common::ntab(9) . '</div><!-- class="row" -->';
				} else {
					$out .= mad_mm_common::ntab(9) . $input;
				}
				break;
			case 'radio_html':
				$col_width = isset( $option['col_width'] ) ? $option['col_width'] : 4;
				$out .= mad_mm_common::ntab(9) . '<div class="row no_left_margin">';
				if ( is_array( $option['values'] ) ) {
					foreach ( $option['values'] as $key => $value ) {
						$out .= mad_mm_common::ntab(10) . '<div class="radio col-xs-' . $col_width . '">';
						$out .= mad_mm_common::ntab(11) . '<label><input type="radio" name="' . $option['key'] . '" value="' . $value .'" ' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
							? ( $value == $mm_saved_value 
								? 'checked="checked" ' 
								: ''
							)
							: ( (  isset( $option['default'] ) && ( in_array( $value, $option['default'] ) || $value == $option['default'] ) ) 
								? 'checked="checked" ' 
								: ''
							)
						) . '/>' . ( is_string( $key ) ? $key : $value ) .'</label>';
						$out .= mad_mm_common::ntab(10) . '</div>';
					}
				}
				$out .= mad_mm_common::ntab(9) . '</div>';
					break;
			case 'checkbox_html':
				$col_width = isset( $option['col_width'] ) ? $option['col_width'] : 4;
				$out .= mad_mm_common::ntab(9) . '<input type="hidden" name="' . $option['key'] . '[]" value="is_checkbox" />';
				$out .= mad_mm_common::ntab(9) . '<div class="row no_left_margin">';
				if ( is_array( $option['values'] ) ) {
					foreach ( $option['values'] as $key => $value ) {
						$out .= mad_mm_common::ntab(10) . '<div class="checkbox col-xs-' . $col_width . '">';
						$out .= mad_mm_common::ntab(11) . '<label><input type="checkbox" name="' . $option['key'] . '[]" value="' . $value .'" ' . ( ( isset( $mm_saved_value ) && is_array( $mm_saved_value ) )
							? ( in_array( $value, $mm_saved_value ) 
								? 'checked="checked" ' 
								: ''
							)
							: ( (  isset( $option['default'] ) && ( in_array( $value, $option['default'] ) || $value == $option['default'] ) ) 
								? 'checked="checked" ' 
								: ''
							)
						) . '/>' . ( is_string( $key ) ? $key : $value ) .'</label>';
						$out .= mad_mm_common::ntab(10) . '</div>';
					}
				}
				$out .= mad_mm_common::ntab(9) . '</div>';
				break;
			case 'file':
				// below calls scripts and styles for media library uploader.
				if ( !isset( $theme_option_file ) ) {
					static $theme_option_file = 1;
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
					wp_enqueue_script('jquery');
					wp_enqueue_style('thickbox');
				}

				$out .= mad_mm_common::ntab(9) . '<div class="row">';
				$out .= mad_mm_common::ntab(10) . '<div class="input-group input-group-sm col-xs-9">';
				$out .= mad_mm_common::ntab(10) . '<input class="upload form-control col-xs-8 wpb_vc_param_value" type="text" name="' . $option['key'] . '" value="' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
											? $mm_saved_value 
											: ( isset( $option['default'] ) 
												? esc_attr( $option['default'] )  
												: ( isset( $option['values'] ) 
													? $option['values'] 
													: ''
												)
											) 
				) . '" />';
				/*  name="' . $option['key'] . '" */
				$out .= mad_mm_common::ntab(11) . '<span class="input-group-btn">';
				$out .= mad_mm_common::ntab(12) . '<input class="' . $clear_full_key . ' select_file_button btn btn-primary" type="button" value="' . __( 'Select Image', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '" />';
				$out .= mad_mm_common::ntab(11) . '</span><!-- class="input-group-btn" -->';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="input-group" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3">';
				$out .= mad_mm_common::ntab(11) . '<img class="img_preview" data-imgprev="' . $clear_full_key . '" src="' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
											? $mm_saved_value 
											: ( isset( $option['default'] ) 
												? esc_attr( $option['default'] )  
												: ( isset( $option['values'] ) 
													? $option['values'] 
													: ''
												)
											) 
				) . '" />';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="row" -->';
				break;
			case 'multiplier':
				$out .= mad_mm_common::ntab(9) . '<div class="hidden multiplied_example ' . $clear_full_key . '">';
				foreach ( $option['values'] as $key => $subvalue ) {
					$subvalue['key'] = $option['key'] . '[999][' . $subvalue['key'] . ']';
					$subvalue['name'] = str_replace( '1', '999', $subvalue['name']);
					$out .= $current_class->options_generator( $subvalue , false );
				}
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="multiplied_example" -->';
				$out .= mad_mm_common::ntab(9) . '<div class="multiplied_content ' . $clear_full_key . '">';
				if ( is_array( $mm_saved_value ) && count( $mm_saved_value ) > 0 ) {
					foreach ( $mm_saved_value as $key => $value ) {
						foreach ( $option['values'] as $subkey => $subvalue ) {
							$mm_saved_subvalue = isset( $mm_saved_value[ $key ][ $subvalue['key'] ] ) 
								? $mm_saved_value[ $key ][ $subvalue['key'] ]
								: false;
							$subvalue['key'] = $option['key'] . '[' . $key . '][' . $subvalue['key'] . ']';
							$subvalue['name'] = str_replace( '1', $key, $subvalue['name']);
							$out .= $current_class->options_generator( $subvalue , $mm_saved_subvalue );
						}
					}
				}
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="multiplied_content" -->';
				$out .= mad_mm_common::ntab(9) . '<span class="btn btn-sm btn-primary multipler_add_one_more">' . __( 'Add One More', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '</span>';
				break;
			case 'wpeditor':
				$content = ( ( isset( $mm_saved_value ) && $mm_saved_value !== false ) 
					? $mm_saved_value 
					: ( isset( $option['default'] ) 
						? $option['default']  
						: ( isset( $option['values'] ) 
							? $option['values'] 
							: ''
						)
					) 
				);
				ob_start();
				$args = array( 
					'textarea_name' => $option['key'], 
					'wpautop' => false,
					'media_buttons' => false,
					'textarea_rows' => 5,
				);
				wp_editor( $content, $clear_full_key, $args );
				$editor = ob_get_contents();
				ob_end_clean();
				$out .= mad_mm_common::ntab(9) . '<div class="no_bootstrap">';
				$out .= $editor;
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="no_bootstrap" -->';
				break;
			case 'icons':
				$icon = ( ( isset( $mm_saved_value ) && $mm_saved_value !== false ) 
					? esc_attr( $mm_saved_value ) 
					: ( isset( $option['default'] ) 
						? esc_attr( $option['default'] )  
						: '' // array_rand( array_flip( mmpm_get_all_icons() ) )
					) 
				);
				$out .= mad_mm_common::ntab(9) . '<div class="row">';
				$out .= mad_mm_common::ntab(10) . '<div class="input-group input-group-sm col-xs-9">';
				$out .= mad_mm_common::ntab(11) . '<input class="form-control input-sm wpb_vc_param_value" type="text" name="' . $option['key'] . '" value="' . $icon . '" data-mm_icon="icons_list_' . esc_attr( $clear_full_key ) . '" />';
				$out .= mad_mm_common::ntab(11) . '<span class="input-group-btn">';
				$out .= mad_mm_common::ntab(12) . '<a data-toggle="modal" href="' . admin_url() . '?mm_page=icons_list&input_name=' . esc_attr( $option['key'] ) . '&modal_id=icons_list_' . esc_attr( $clear_full_key ) . '" data-target="#icons_list_' . $clear_full_key . '" class="btn btn-primary">' . __( 'Show Icons', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '</a>';
				$out .= mad_mm_common::ntab(11) . '</span><!-- class="input-group-btn" -->';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="input-group input-group-sm col-xs-9" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3 icon_preview">';
				$out .= mad_mm_common::ntab(11) . '<i class="' . $icon . '" data-mm_icon="icons_list_' . $clear_full_key . '"></i>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="row" -->';
				$out .= mad_mm_common::ntab(9) . '<div id="icons_list_' . $clear_full_key . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="icons_listLabel" aria-hidden="true">';
				$out .= mad_mm_common::ntab(10) . '<div class="bootstrap">';
				$out .= mad_mm_common::ntab(11) . '<div class="modal-dialog">';
				$out .= mad_mm_common::ntab(12) . '<div class="modal-content">';
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="modal-content" -->';
				$out .= mad_mm_common::ntab(11) . '</div><!-- class="modal-dialog" -->';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="bootstrap" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="modal" -->';
				break;
			case 'caption':
				$out .= mad_mm_common::ntab(7) . '<div class="bootstrap">';
				$out .= mad_mm_common::ntab(8) . '<div class="option bootstrap row ' . $option['key'] . ' ' . $option['type'] . '">';
				$out .= mad_mm_common::ntab(9) . '<div class="col-xs-12">';
				$out .= mad_mm_common::ntab(10) . '<div class="h_separator">';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="h_separator" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="col-xs-12" -->';
				$out .= mad_mm_common::ntab(9) . '<div class="col-xs-12">';
				$out .= mad_mm_common::ntab(10) . '<div class="section_caption">';
				$out .= mad_mm_common::ntab(11) . $option['name'];
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="section_caption" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="col-xs-12" -->';
				$out .= mad_mm_common::ntab(8) . '</div><!-- class="option row ' . $option['key'] . ' ' . $option['type'] . '" -->';
				$out .= mad_mm_common::ntab(7) . '</div><!-- class="bootstrap" -->';
				break;
			case 'collapse_start':
				$out .= mad_mm_common::ntab(5) . '<div class="panel bootstrap ' . str_replace( array('[',']'), array('',''), $option['key'] ) . '">';
				$out .= mad_mm_common::ntab(6) . '<div class="panel-heading">';
				$out .= mad_mm_common::ntab(7) . '<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent=".tab-pane" href="#' . str_replace( array('[',']',' '), array('','','-'), $option['key'] ) . '">' . $option['name'] . '</a>';
				$out .= mad_mm_common::ntab(6) . '</div>';
				$out .= mad_mm_common::ntab(6) . '<div id="' . str_replace( array('[',']',' '), array('','','-'), $option['key'] ) . '" class="panel-collapse collapse col-xs-12">';
				break;
			case 'collapse_end':
				$out .= mad_mm_common::ntab(6) . '</div><!-- class="panel-collapse collapse col-xs-12" -->';
				$out .= mad_mm_common::ntab(5) . '</div><!--  class="panel" -->';
				break;
			case 'devider':
				$out .= mad_mm_common::ntab(7) . '<div class="option row devider ' . $option['key'] . ' ' . $option['type'] . '">';
				$out .= mad_mm_common::ntab(8) . '<div class="col-xs-12">';
				$out .= mad_mm_common::ntab(9) . '<div class="h_separator">';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="h_separator" -->';
				$out .= mad_mm_common::ntab(9) . '<div class="h_separator">';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="h_separator" -->';
				$out .= mad_mm_common::ntab(9) . '<div class="h_separator">';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="h_separator" -->';
				$out .= mad_mm_common::ntab(8) . '</div><!-- class="col-xs-12" -->';
				$out .= mad_mm_common::ntab(7) . '</div><!-- class="option row devider ' . $option['key'] . ' ' . $option['type'] . '" -->';
				break;
			case 'skin_options_generator':
/*
				$out .= mm_skin_options_generator();
*/
				break;
			case 'color':
				$value = ( ( isset( $mm_saved_value ) && $mm_saved_value !== false ) 
					? esc_attr( $mm_saved_value ) 
					: ( isset( $option['default'] ) 
						? esc_attr( $option['default'] )  
						: ( isset( $option['values'] ) 
							? esc_attr( $option['values'] ) 
							: '#808080'
						)
					) 
				);

				$out .= mad_mm_common::ntab(7) . '<div class="color_picker">';
				$out .= mad_mm_common::ntab(8) . '<div class="row">';
				$out .= mad_mm_common::ntab(9) . '<div class="mm_must_be_colorpicker input-append color input-group input-group-sm col-xs-3" data-color="' . $value . '" data-color-format="rgba" id="' . $clear_full_key . '_colorpicker">';
				$out .= mad_mm_common::ntab(10) . '<input class="form-control col-xs-12 wpb_vc_param_value" type="text" name="' . $option['key'] . '" value="' . $value . '">';
				$out .= mad_mm_common::ntab(10) . '<span class="input-group-addon add-on"><i style="background-color: ' . $value . ';"> &nbsp; </i></span>';
				$out .= mad_mm_common::ntab(9) . '</div>';
				$out .= mad_mm_common::ntab(8) . '</div><!-- class="row" -->';
				$out .= mad_mm_common::ntab(7) . '</div><!-- class="color_picker" -->';
				break;
			case 'font':
				$out .= mad_mm_common::ntab(7) . '<div class="font_selector row">';
				if ( $option['values'] == '' || ( is_array( $option['values'] ) && in_array( 'font_family', $option['values'] ) ) ) {
					$out .= mad_mm_common::ntab(8) . '<div class="col-md-3 col-sm-6 col-xs-3 family">';
					$out .= mad_mm_common::ntab(9) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[font_family]">';

					$set_of_google_fonts = ( $current_class->get_option( 'set_of_google_fonts' ) ) ? $current_class->get_option( 'set_of_google_fonts' ) : array();
					unset( $set_of_google_fonts['0'] );
					$set_of_google_fonts[] = array( 'family' => 'Arial' );
					$set_of_google_fonts[] = array( 'family' => 'Courier New' );
					$set_of_google_fonts[] = array( 'family' => 'Helvetica' );
					$set_of_google_fonts[] = array( 'family' => 'Tahoma' );
					$set_of_google_fonts[] = array( 'family' => 'Times New Roman' );
					$set_of_google_fonts[] = array( 'family' => 'Verdana' );
					$set_of_google_fonts[] = array( 'family' => 'Inherit' );

					$out .= mad_mm_common::ntab(10) . '<optgroup label="' . __( 'Installed Google Fonts', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ). '">';
					foreach ( $set_of_google_fonts as $key => $value ) {
						if ( $value['family'] == 'Arial' ) {
							$out .= mad_mm_common::ntab(10) . '</optgroup>';
							$out .= mad_mm_common::ntab(10) . '<optgroup label="' . __( 'Safe Web Fonts (Recommended)', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ). '">';
						}
						$out .= mad_mm_common::ntab(10) . '<option value="' . $value['family'] .'" ' . ( ( isset( $mm_saved_value['font_family'] ) && $mm_saved_value['font_family'] !== false )
							? ( $value['family'] == $mm_saved_value['font_family'] 
								? 'selected="selected" ' 
								: ''
							)
							: ( ( isset( $option['default']['font_family'] ) && $value['family'] == $option['default']['font_family'] ) 
								? 'selected="selected" ' 
								: ''
							)
						) . '>' . $value['family'] .'</option>';
					}
					$out .= mad_mm_common::ntab(10) . '</optgroup>';
					$out .= mad_mm_common::ntab(9) . '</select>';
					$out .= mad_mm_common::ntab(8) . '</div><!-- class="col-md-3 col-sm-6 col-xs-3 family" -->';
				}
				if ( $option['values'] == '' || ( is_array( $option['values'] ) && in_array( 'font_color', $option['values'] ) ) ) {
					$out .= mad_mm_common::ntab(8) . '<div class="col-md-3 col-sm-6 col-xs-3 color">';
					$value = ( ( isset( $mm_saved_value['font_color'] ) && $mm_saved_value['font_color'] !== false ) 
						? esc_attr( $mm_saved_value['font_color'] ) 
						: ( isset( $option['default']['font_color'] ) 
							? esc_attr( $option['default']['font_color'] )  
							: ( isset( $option['values']['font_color'] ) 
								? esc_attr( $option['values']['font_color'] ) 
								: '#808080'
							)
						) 
					);
					$out .= mad_mm_common::ntab(9) . '<div class="color_picker">';
					$out .= mad_mm_common::ntab(10) . '<div class="row">';
					$out .= mad_mm_common::ntab(11) . '<div class="mm_must_be_colorpicker input-append color input-group input-group-sm col-xs-12" data-color="' . $value . '" data-color-format="rgba" id="' . $clear_key . '_colorpicker">';
					$out .= mad_mm_common::ntab(12) . '<input class="form-control col-xs-12" type="text" name="' . $option['key'] . '[font_color]" value="' . $value . '">';
					$out .= mad_mm_common::ntab(12) . '<span class="input-group-addon add-on"><i style="background-color: ' . $value . ';"> &nbsp; </i></span>';
					$out .= mad_mm_common::ntab(11) . '</div>';
					$out .= mad_mm_common::ntab(10) . '</div><!-- class="row" -->';
					$out .= mad_mm_common::ntab(9) . '</div><!-- class="color_picker" -->';
					$out .= mad_mm_common::ntab(8) . '</div><!-- class="col-md-3 col-sm-6 col-xs-3 color" -->';
				}
				if ( $option['values'] == '' || ( is_array( $option['values'] ) && in_array( 'font_size', $option['values'] ) ) ) {
					$out .= mad_mm_common::ntab(8) . '<div class="input-group input-group-sm col-lg-3 col-md-4 col-sm-6 col-xs-3 size">';
					$out .= mad_mm_common::ntab(9) . '<input class="form-control col-xs-12" type="number" step="1" min="4" max="120" name="' . $option['key'] . '[font_size]" value="' . ( ( isset( $mm_saved_value['font_size'] ) && $mm_saved_value['font_size'] !== false )
						? esc_attr( $mm_saved_value['font_size'] ) 
						: ( isset( $option['default']['font_size'] ) 
							? esc_attr( $option['default']['font_size'] )  
							: ( isset( $option['values']['font_size'] ) 
								? $option['values']['font_size']
								: '14'
							)
						) 
					) . '" />';
					$out .= mad_mm_common::ntab(9) . '<span class="input-group-addon">px</span>';
					$out .= mad_mm_common::ntab(8) . '</div><!-- class="input-group input-group-sm col-lg-3 col-md-4 col-sm-6 col-xs-3 size" -->';
				}
				if ( $option['values'] == '' || ( is_array( $option['values'] ) && in_array( 'font_weight', $option['values'] ) ) ) {
					$out .= mad_mm_common::ntab(8) . '<div class="col-lg-3 col-md-2 col-sm-6 col-xs-3 weight">';
					$out .= mad_mm_common::ntab(9) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[font_weight]">';
					foreach ( range( 300, 900, 100 ) as $key => $value ) {
						$out .= mad_mm_common::ntab(10) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value['font_weight'] ) && $mm_saved_value['font_weight'] !== false )
							? ( $value == $mm_saved_value['font_weight'] 
								? 'selected="selected" ' 
								: ''
							)
							: ( ( isset( $option['default']['font_weight'] ) && $value == $option['default']['font_weight'] ) 
								? 'selected="selected" ' 
								: ''
							)
						) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
					}
					$out .= mad_mm_common::ntab(9) . '</select>';
					$out .= mad_mm_common::ntab(8) . '</div><!-- class="col-lg-3 col-md-2 col-sm-6 col-xs-3 weight" -->';
				}
				$out .= mad_mm_common::ntab(7) . '</div><!-- class="font_selector row" -->';
				break;
			case 'background_image':
				// below calls scripts and styles for media library uploader.
				if ( !isset( $theme_option_file ) ) {
					static $theme_option_file = 1;
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
					wp_enqueue_script('jquery');
					wp_enqueue_style('thickbox');
				}

				$out .= mad_mm_common::ntab(9) . '<div class="row background_image_selcetor">';
				$out .= mad_mm_common::ntab(10) . '<div class="input-group input-group-sm col-xs-9">';
				$out .= mad_mm_common::ntab(10) . '<input class="upload form-control col-xs-8" type="text" name="' . $option['key'] . '[background_image]" value="' . ( ( isset( $mm_saved_value['background_image'] ) && $mm_saved_value['background_image'] !== false )
											? $mm_saved_value['background_image'] 
											: ( isset( $option['default']['background_image'] ) 
												? esc_attr( $option['default']['background_image'] )  
												: ( isset( $option['values']['background_image'] ) 
													? $option['values']['background_image'] 
													: ''
												)
											) 
				) . '" />';
				/*  name="' . $option['key'] . '" */
				$out .= mad_mm_common::ntab(11) . '<span class="input-group-btn">';
				$out .= mad_mm_common::ntab(12) . '<input class="' . $clear_full_key . ' select_file_button btn btn-primary" type="button" value="' . __( 'Select Image', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) . '" />';
				$out .= mad_mm_common::ntab(11) . '</span><!-- class="input-group-btn" -->';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="input-group" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3">';
				$out .= mad_mm_common::ntab(11) . '<img class="img_preview" data-imgprev="' . $clear_full_key . '" src="' . ( ( isset( $mm_saved_value['background_image'] ) )
											? $mm_saved_value['background_image'] 
											: ( isset( $option['default']['background_image'] ) 
												? esc_attr( $option['default']['background_image'] )  
												: ( isset( $option['values']['background_image'] ) 
													? $option['values']['background_image'] 
													: ''
												)
											) 
				) . '" />';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-12 pull-left">&nbsp;';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-12" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3">';
				$out .= mad_mm_common::ntab(11) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[background_repeat]">';
				foreach ( array('repeat','no-repeat','repeat-x','repeat-y') as $key => $value ) {
					$out .= mad_mm_common::ntab(10) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value['background_repeat'] ) && $mm_saved_value['background_repeat'] !== false )
						? ( $value == $mm_saved_value['background_repeat'] 
							? 'selected="selected" ' 
							: ''
						)
						: ( ( isset( $option['default']['background_repeat'] ) && $value == $option['default']['background_repeat'] ) 
							? 'selected="selected" ' 
							: ''
						)
					) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
				}
				$out .= mad_mm_common::ntab(1) . '</select>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3">';
				$out .= mad_mm_common::ntab(1) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[background_attachment]">';
				foreach ( array('scroll','fixed') as $key => $value ) {
					$out .= mad_mm_common::ntab(10) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value['background_attachment'] ) && $mm_saved_value['background_attachment'] !== false )
						? ( $value == $mm_saved_value['background_attachment'] 
							? 'selected="selected" ' 
							: ''
						)
						: ( ( isset( $option['default']['background_attachment'] ) && $value == $option['default']['background_attachment'] ) 
							? 'selected="selected" ' 
							: ''
						)
					) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
				}
				$out .= mad_mm_common::ntab(1) . '</select>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3">';
				$out .= mad_mm_common::ntab(1) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[background_position]">';
				foreach ( array('center','center left','center right','top left','top center','top right','bottom left','bottom center','bottom right') as $key => $value ) {
					$out .= mad_mm_common::ntab(10) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value['background_position'] ) && $mm_saved_value['background_position'] !== false )
						? ( $value == $mm_saved_value['background_position'] 
							? 'selected="selected" ' 
							: ''
						)
						: ( ( isset( $option['default']['background_position'] ) && $value == $option['default']['background_position'] ) 
							? 'selected="selected" ' 
							: ''
						)
					) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
				}
				$out .= mad_mm_common::ntab(1) . '</select>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-3">';
				$out .= mad_mm_common::ntab(1) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[background_size]">';
				foreach ( array( __( 'Keep original', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'auto', __( 'Stretch to width', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => '100% auto', __( 'Stretch to height', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'auto 100%','cover','contain') as $key => $value ) {
					$out .= mad_mm_common::ntab(10) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value['background_size'] ) && $mm_saved_value['background_size'] !== false )
						? ( $value == $mm_saved_value['background_size'] 
							? 'selected="selected" ' 
							: ''
						)
						: ( ( isset( $option['default']['background_size'] ) && $value == $option['default']['background_size'] ) 
							? 'selected="selected" ' 
							: ''
						)
					) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
				}
				$out .= mad_mm_common::ntab(1) . '</select>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-3" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="row" -->';
				break;
			case 'gradient':
				if ( !isset( $theme_option_color ) ) {
					static $theme_option_color = 1;
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_script( 'wp-color-picker' );
				}			
				$out .= mad_mm_common::ntab(9) . '<div class="row gradient_selcetor">';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-8">';
				$out .= mad_mm_common::ntab(11) . '<div class="row">';
				$out .= mad_mm_common::ntab(12) . '<div class="col-xs-5">';
				$value = ( ( isset( $mm_saved_value['color1'] ) && $mm_saved_value['color1'] !== false ) 
					? esc_attr( $mm_saved_value['color1'] ) 
					: ( isset( $option['default']['color1'] ) 
						? esc_attr( $option['default']['color1'] )  
						: ( isset( $option['values']['color1'] ) 
							? esc_attr( $option['values']['color1'] ) 
							: '#808080'
						)
					) 
				);
				$out .= mad_mm_common::ntab(9) . '<div class="color_picker">';
				$out .= mad_mm_common::ntab(10) . '<div class="row">';
				$out .= mad_mm_common::ntab(11) . '<div class="mm_must_be_colorpicker input-append color input-group input-group-sm col-xs-11" data-color="' . $value . '" data-color-format="rgba" id="' . $clear_full_key . '_1_colorpicker">';
				$out .= mad_mm_common::ntab(12) . '<input class="form-control col-xs-12" type="text" name="' . $option['key'] . '[color1]" value="' . $value . '">';
				$out .= mad_mm_common::ntab(12) . '<span class="input-group-addon add-on"><i style="background-color: ' . $value . ';"> &nbsp; </i></span>';
				$out .= mad_mm_common::ntab(11) . '</div>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="row" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="color_picker" -->';
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="col-xs-5" -->';
				$out .= mad_mm_common::ntab(12) . '<div class="col-xs-2 start_end">';
				$out .= mad_mm_common::ntab(13) . __( 'Start', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] );
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="col-xs-2" -->';
				$out .= mad_mm_common::ntab(12) . '<div class="input-group input-group-sm col-xs-5">';
				$out .= mad_mm_common::ntab(13) . '<input class="form-control col-xs-12" type="number" step="1" min="0" max="100" name="' . $option['key'] . '[start]" value="' . ( ( isset( $mm_saved_value['start'] ) && $mm_saved_value['start'] !== false )
					? esc_attr( $mm_saved_value['start'] ) 
					: ( isset( $option['default']['start'] ) 
						? esc_attr( $option['default']['start'] )  
						: ( isset( $option['values']['start'] ) 
							? $option['values']['start']
							: '0'
						)
					) 
				) . '" />';
				$out .= mad_mm_common::ntab(13) . '<span class="input-group-addon">%</span>';
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="input-group input-group-sm col-xs-5" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-12 vertical_padding pull-left">';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-12" -->';
				$out .= mad_mm_common::ntab(12) . '<div class="col-xs-5">';
				$value = ( ( isset( $mm_saved_value['color2'] ) && $mm_saved_value['color2'] !== false ) 
					? esc_attr( $mm_saved_value['color2'] ) 
					: ( isset( $option['default']['color2'] ) 
						? esc_attr( $option['default']['color2'] )  
						: ( isset( $option['values']['color2'] ) 
							? esc_attr( $option['values']['color2'] ) 
							: '#808080'
						)
					) 
				);
				$out .= mad_mm_common::ntab(9) . '<div class="color_picker">';
				$out .= mad_mm_common::ntab(10) . '<div class="row">';
				$out .= mad_mm_common::ntab(11) . '<div class="mm_must_be_colorpicker input-append color input-group input-group-sm col-xs-11" data-color="' . $value . '" data-color-format="rgba" id="' . $clear_full_key . '_2_colorpicker">';
				$out .= mad_mm_common::ntab(12) . '<input class="form-control col-xs-12" type="text" name="' . $option['key'] . '[color2]" value="' . $value . '">';
				$out .= mad_mm_common::ntab(12) . '<span class="input-group-addon add-on"><i style="background-color: ' . $value . ';"> &nbsp; </i></span>';
				$out .= mad_mm_common::ntab(11) . '</div>';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="row" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="color_picker" -->';
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="col-xs-5" -->';
				$out .= mad_mm_common::ntab(12) . '<div class="col-xs-2 start_end">';
				$out .= mad_mm_common::ntab(13) . __( 'End', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] );
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="col-xs-2" -->';
				$out .= mad_mm_common::ntab(12) . '<div class="input-group input-group-sm col-xs-5">';
				$out .= mad_mm_common::ntab(13) . '<input class="form-control col-xs-12" type="number" step="1" min="0" max="100" name="' . $option['key'] . '[end]" value="' . ( ( isset( $mm_saved_value['end'] ) && $mm_saved_value['end'] !== false )
					? esc_attr( $mm_saved_value['end'] ) 
					: ( isset( $option['default']['end'] ) 
						? esc_attr( $option['default']['end'] )  
						: ( isset( $option['values']['end'] ) 
							? $option['values']['end']
							: '100'
						)
					) 
				) . '" />';
				$out .= mad_mm_common::ntab(13) . '<span class="input-group-addon">%</span>';
				$out .= mad_mm_common::ntab(12) . '</div><!-- class="input-group input-group-sm col-xs-5" -->';
				$out .= mad_mm_common::ntab(11) . '</div><!-- class="row" -->';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-8" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-4">';
				$out .= mad_mm_common::ntab(11) . '<select class="col-xs-12 form-control input-sm" name="' . $option['key'] . '[orientation]">';
				foreach ( array( __( 'Vertical', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'top', __( 'Horizontal', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'left', __( 'Radial', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] ) => 'radial') as $key => $value ) {
					$out .= mad_mm_common::ntab(12) . '<option value="' . $value .'" ' . ( ( isset( $mm_saved_value['orientation'] ) && $mm_saved_value['orientation'] !== false )
						? ( $value == $mm_saved_value['orientation'] 
							? 'selected="selected" ' 
							: ''
						)
						: ( ( isset( $option['default']['orientation'] ) && $value == $option['default']['orientation'] ) 
							? 'selected="selected" ' 
							: ''
						)
					) . '>' . ( is_string( $key ) ? $key : $value ) .'</option>';
				}
				$out .= mad_mm_common::ntab(11) . '</select>';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-12 vertical_padding pull-left">';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-12" -->';
				$out .= mad_mm_common::ntab(10) . '<div class="col-xs-12 gradient_example pull-left">';
				$out .= mad_mm_common::ntab(11) . __( 'Click Here to View Result', $current_class->constant[ 'MM_TEXTDOMAIN_ADMIN' ] );
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-12 gradient_example" -->';
				$out .= mad_mm_common::ntab(10) . '</div><!-- class="col-xs-4" -->';
				$out .= mad_mm_common::ntab(9) . '</div><!-- class="row" -->';
				break;
			default /* 'textfield' */:
				$out .= mad_mm_common::ntab(9) . '<input class="col-xs-12 form-control input-sm wpb_vc_param_value" type="text" name="' . $option['key'] . '" value="' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false )
					? esc_attr( $mm_saved_value ) 
					: ( isset( $option['default'] ) 
						? esc_attr( $option['default'] )  
						: ( isset( $option['values'] ) 
							? esc_attr( $option['values'] ) 
							: ''
						)
					) 
				) . '" />';
				break;
		}

		if ( $option['type'] != 'collapse_start' && $option['type'] != 'collapse_end' && $option['type'] != 'skin_options_generator' && $option['type'] != 'caption' ) {
			$section = '';
			$section .= mad_mm_common::ntab(6) . '<div class="bootstrap">';
			$section .= mad_mm_common::ntab(7) . '<div class="option row ' . str_replace( array( $current_class->constant[ 'MM_OPTIONS_NAME' ], '[',']'), '', $option['key'] ) . ' ' .  $option['type'] . '_type"' . ( ( isset( $option['dependency']['element'] ) && isset( $option['dependency']['value'] ) ) ? ' data-dependencyelement="' . $option['dependency']['element'] . '" data-dependencyvalue="' . implode( '|', $option['dependency']['value'] ) . '"' : '' ) . '>';
			$section .= mad_mm_common::ntab(8) . '<div class="col-xs-12">';
			$section .= mad_mm_common::ntab(9) . '<div class="h_separator">';
			$section .= mad_mm_common::ntab(9) . '</div><!-- class="h_separator" -->';
			$section .= mad_mm_common::ntab(8) . '</div><!-- class="col-xs-12" -->';
			$section .= mad_mm_common::ntab(8) . '<div class="option_header col-md-3 col-sm-12">';
			$section .= mad_mm_common::ntab(9) . '<div class="caption">';
			$section .= mad_mm_common::ntab(10) . $option['name'];
			$section .= mad_mm_common::ntab(9) . '</div><!-- class="caption" -->';
			$section .= mad_mm_common::ntab(9) . '<div class="descr">';
			$section .= mad_mm_common::ntab(10) . $option['descr'];
			$section .= mad_mm_common::ntab(9) . '</div><!-- class="descr" -->';
			$section .= mad_mm_common::ntab(8) . '</div><!-- class="option_header col-3" -->';
			$section .= mad_mm_common::ntab(8) . '<div class="option_field col-md-9 col-sm-12">';
			$section .= $out;
			$section .= mad_mm_common::ntab(8) . '</div><!-- class="option_field col-9" -->';
			$section .= mad_mm_common::ntab(7) . '</div><!-- class="option row ' . str_replace( array( $current_class->constant[ 'MM_OPTIONS_NAME' ], '[',']'), '', $option['key'] ) . '" -->';
			$section .= mad_mm_common::ntab(6) . '</div><!-- class="bootstrap" -->';
			$out = $section;
		}
		return $out;
	}
}
?>