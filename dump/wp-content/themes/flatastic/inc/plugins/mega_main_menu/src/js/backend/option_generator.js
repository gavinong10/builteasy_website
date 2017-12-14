;;;
/* 
 * Common variables and functions.
 */
;jQuery(document).ready(function(){
	/* 
	 * Function remove all "multiplied_example" containers on page before send request.
	 */
	;jQuery('#update-nav-menu .button-primary, .mm_theme_options_form .button-primary').click( function( event ) {
		;jQuery( '.multiplied_example' ).remove();
//		;jQuery( '[value="is_checkbox"]' ).remove();
//		;jQuery('#menu-to-edit > li:not(.menu-item-depth-0) div[class*="submenu_"]').remove();
	});

	/* 
	 * Initialize all functions.
	 */
	mm_multiplier();
	mm_dependency_init();
	mm_generator_bind();
});

/* 
 * NEW AGE 2.0
 */

/* 
 * List of functions what must bind's everytime
 */
;function mm_generator_bind(){
	mm_multiplier_numerator();
	mm_multiplier_helpers();
	mm_gradient_example();
	mm_file_upload();
	mm_icon_selector();
	mm_must_be_colorpicker();
	mm_dependency_watcher();
};

/* 
 * Function add one more item from example
 */
;function mm_multiplier(){
	var mm_clone = '';
	jQuery( '.multiplier_type' ).each( function( p_index, p_element ) {
		/* multipler_add_one_more button manipulation */
		mm_generator_bind();
		mm_clone = '';
		jQuery( p_element ).find( '.multipler_add_one_more' ).click( function() {
			mm_clone = jQuery( p_element ).find( '.multiplied_example > *' ).clone();
			jQuery( p_element ).find('.multiplied_content').append( mm_clone );
			mm_generator_bind();
		});
	});
};

/* 
 * Create helpers for mm_multiplier.
 */
;function mm_multiplier_helpers(){
	jQuery( '.multiplier_type' ).each( function( p_index, p_element ) {
		/* make multiplier items sortable */
		jQuery( p_element ).find( '.multiplied_content' ).sortable({ 
			opacity: 0.6, 
			cursor: 'move', 
			distance: 15,
			revert: 150,
			update: function() {
				mm_generator_bind();
			}
		});
		/* "remove" button manipulation */
		jQuery( p_element ).find( '.multiplied_content > div' ).each( function( index, element ) {
			check_remover = jQuery( element ).find('.multiple_item_remover');
			if ( check_remover.length == 0 ) {
				jQuery( element ).prepend( '<span class="multiple_item_remover">X</span>' );
			}
			jQuery( element ).find( '.multiple_item_remover' ).click(function(){
				jQuery( element ).fadeOut( 400, function() {
					jQuery( this ).remove();
					mm_multiplier_numerator();
				});
			});
		});
	});
};

/* 
 * Function select important multiplied containers id-values and set them current order.
 */
;function mm_multiplier_numerator () {
	jQuery( '.multiplier_type' ).each( function( p_index, p_element ) {
		classes_new_id = 1;
		jQuery( p_element ).find( '.multiplied_content > div' ).each( function( index, element ) {
				// replace all href="" identificators
				jQuery(this).find('*[href]').each(function(index,element){
					element_href = jQuery(element).attr( 'href' );
					jQuery(element).attr( 'href', element_href.replace( /\d+/g, classes_new_id ) );
					element_text = jQuery(element).text();
					jQuery(element).text( element_text.replace( /\d+/g, classes_new_id ) );
				});
				// replace all id="" identificators
				jQuery(this).find('*[id]').each(function(index,element){
					element_id = jQuery(element).attr( 'id' );
					jQuery(element).attr( 'id', element_id.replace( /\d+/g, classes_new_id ) );
				});
				// replace all name="" identificators
				jQuery(this).find('*[name]').each(function(index,element){
					element_name = jQuery(element).attr( 'name' );
					jQuery(element).attr( 'name', element_name.replace( /\[\d+\]/g, '[' + classes_new_id + ']' ) );
				});
				// replace all data-imgprev="" identificators
				jQuery(this).find('*[data-imgprev]').each(function(index,element){
					element_data = jQuery(element).attr( 'data-imgprev' );
					jQuery(element).attr( 'data-imgprev', element_data.replace( /\d+/g, classes_new_id ) );
				});
				// replace all data-mm_icon="" identificators
				jQuery(this).find('*[data-mm_icon]').each(function(index,element){
					element_data = jQuery(element).attr( 'data-mm_icon' );
					jQuery(element).attr( 'data-mm_icon', element_data.replace( /\d+/g, classes_new_id ) );
				});
				// replace all data-target="" identificators
				jQuery(this).find('*[data-target]').each(function(index,element){
					element_data = jQuery(element).attr( 'data-target' );
					jQuery(element).attr( 'data-target', element_data.replace( /\d+/g, classes_new_id ) );
				});
				// replace all scripts identificators
				jQuery(this).find('script').each(function(index,element){
					element_html = jQuery(element).html();
					jQuery(element).html( element_html.replace( /\d+/g, classes_new_id ) );
				});

				classes_new_id = classes_new_id + 1;
		});
	});
};

/* 
 * 
 */
;function mm_dependency_watcher () {
	jQuery( '[data-dependencyelement]' ).each( function( p_index, p_element ) {
		jQuery( '[name*="' + jQuery( p_element ).attr( 'data-dependencyelement' ) + '"]' ).change(function(){
			current_val = jQuery( this ).val();
			if ( jQuery( this ).attr( 'type' ) == 'checkbox' || jQuery( this ).attr( 'type' ) == 'radio' ) {
				current_val = jQuery( '[name*="' + jQuery( p_element ).attr( 'data-dependencyelement' ) + '"]:checked' ).val()
			}
			if ( jQuery.inArray( current_val, jQuery( p_element ).attr( 'data-dependencyvalue' ).split( '|' ) ) == -1 ) {
				jQuery( p_element ).slideUp( 500 );
			} else {
				jQuery( p_element ).slideDown( 500 );
			};
		});
	});
};

/* 
 * 
 */
;function mm_dependency_init () {
	jQuery( 'div[data-dependencyelement]' ).each( function( p_index, p_element ) {
		// ID manipulations
		child_name = jQuery( p_element ).find('input[name], select[name]').attr( 'name' );
		child_id = child_name.replace(/[^0-9]/g,'');
		element_name = jQuery( p_element ).attr( 'data-dependencyelement' );
		element_name = element_name.replace( /__ID__/g, child_id );
		jQuery( p_element ).attr( 'data-dependencyelement', element_name );
		// regular dependency function
		current_val = '';

		jQuery( '[name*="' + element_name + '"]' ).each( function( index, element ) {
			current_val = jQuery( element ).val();
			if ( jQuery( element ).attr( 'type' ) == 'checkbox' || jQuery( element ).attr( 'type' ) == 'radio' ) {
				current_val = jQuery( '[name*="' + element_name + '"]:checked' ).val()
			}
		});
		if ( jQuery.inArray( current_val, jQuery( p_element ).attr( 'data-dependencyvalue' ).split( '|' ) ) == -1 ) {
			jQuery( p_element ).css({ display: "none" });
		} else {
			jQuery( p_element ).css({ display: "block" });
		};
	});
};

/* 
 * 
 */
;function mm_icon_selector( input_name, modal_id ) {
	checked_icon = jQuery( '#' + modal_id + ' .all_icons_container input:checked' ).val(); 
	jQuery( 'i[data-mm_icon="' + modal_id + '"]' ).removeClass(); 
	jQuery( 'i[data-mm_icon="' + modal_id + '"]' ).addClass( checked_icon ); 
	jQuery( 'input[data-mm_icon="' + modal_id + '"]' ).val( checked_icon ); 
	jQuery( '#' + modal_id ).modal('hide'); 
	jQuery( '#' + modal_id ).removeClass('in'); 
};

/* 
 * Trigger on all "ColorPicker" buttons.
 */
;function mm_must_be_colorpicker(){
	jQuery( '.mm_must_be_colorpicker' ).each( function( p_index, p_element ) {
		jQuery( this ).mm_colorpicker();
	});
};

/* 
 * Trigger on "Upload Image" button.
 */
;function mm_file_upload() {
	jQuery( '.file_type, .background_image_type' ).each( function( index, element ) {
		jQuery( element ).find( '.select_file_button' ).click(function(){
			mmUploadID = jQuery( element ).find( '.upload' );
			mmPrewImage = jQuery( element ).find( '.img_preview' );
			tb_show("Select file", "media-upload.php?type=image&amp;TB_iframe=true");
			return false;
		});
		window.send_to_editor = function(html) {
			imgurl = jQuery("img",html).attr("src");
			mmUploadID.val(imgurl);
			mmPrewImage.attr({src: imgurl});
			tb_remove();
		};
	});
};

/* 
 * Trigger on "Gradient Example" button.
 */
;function mm_gradient_example( ) {
	jQuery( '.gradient_type' ).each( function( index, element ) {
		jQuery( element ).find( '.gradient_example' ).click(function(){
			color1 = jQuery( element ).find( 'input[name*="[color1]"]' ).val();
			color2 = jQuery( element ).find( 'input[name*="[color2]"]' ).val();
			start = jQuery( element ).find( 'input[name*="[start]"]' ).val();
			end = jQuery( element ).find( 'input[name*="[end]"]' ).val();
			orientation = jQuery( element ).find( '*[name*="[orientation]"]' ).val();
			if ( orientation == 'radial' ) {
				orient1 = 'radial-gradient(center, ellipse cover';
				orient2 = 'radial, center center, 0px, center center, 100%';
				orient3 = 'radial-gradient(ellipse at center';
			} else if ( orientation == 'left' ) {
				orient1 = 'linear-gradient(left';
				orient2 = 'linear, left top, right top';
				orient3 = 'linear-gradient(to right';
			} else {
				orient1 = 'linear-gradient(top';
				orient2 = 'linear, left top, left bottom';
				orient3 = 'linear-gradient(to bottom';
			}
			gradient = 'background-color: ' + color1 + ';';
			gradient += 'background: -moz-' + orient1 + ',  ' + color1 + ' ' + start + '%, ' + color2 + ' ' + end + '%);';
			gradient += 'background: -webkit-' + orient1 + ',  ' + color1 + ' ' + start + '%,' + color2 + ' ' + end + '%);';
			gradient += 'background: -o-' + orient1 + ',  ' + color1 + ' ' + start + '%,' + color2 + ' ' + end + '%);';
			gradient += 'background: -ms-' + orient1 + ',  ' + color1 + ' ' + start + '%,' + color2 + ' ' + end + '%);';
			gradient += 'background: -webkit-gradient(' + orient2 + ', color-stop(' + start + '%,' + color1 + '), color-stop(' + end + '%,' + color2 + '));';
			gradient += 'background: ' + orient3 + ',  ' + color1 + ' ' + start + '%,' + color2 + ' ' + end + '%);';
			gradient += 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="' + color1 + '", endColorstr="' + color2 + '",GradientType=0 );';
			jQuery( this ).attr( 'style', gradient );
		});
	});
};
