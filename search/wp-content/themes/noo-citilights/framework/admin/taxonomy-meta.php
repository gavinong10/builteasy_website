<?php

// Add term page
function noo_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	wp_enqueue_media();
	?>
	<hr/>
	<div class="form-field">
		<label for="term_meta-enable-heading"><?php _e( 'Show Headline', 'noo' ); ?></label>
		<input type="hidden" name="term_meta[enable-heading]" value="0" >
		<input type="checkbox" class="parent-control" name="term_meta[enable-heading]" id="term_meta-enable-heading" value="1" checked="checked">
		<p class="description"><?php _e( 'Enable Headline when viewing this taxonomy page.', 'noo' ); ?></p>
	</div>
	<div class="form-field term_meta-enable-heading-child">
		<label for="term_meta-heading-image"><?php _e( 'Headline Background Image', 'noo' ); ?></label>
		<input type="hidden" name="term_meta[heading-image]" id="term_meta-heading-image" value="" />
		<input type="button" class="button button-primary" name="term_meta-heading-image_button_upload" id="term_meta-heading-image_button_upload" value="<?php _e( 'Select Image', 'noo' ); ?>" />
		<input type="button" class="button" name="term_meta-heading-image_button_clear" id="term_meta-heading-image_button_clear" value="<?php _e( 'Clear Image', 'noo' ); ?>" style="display: none;"/>
		<div class="noo-thumb-wrapper"></div>
		<p class="description"><?php _e( 'If you leave it blank, it will use the image set on Customizer setting for Index page.', 'noo' ); ?></p>
		<script>
			jQuery(document).ready(function($) {
				$('#term_meta-heading-image_button_upload').on('click', function(event) {
					event.preventDefault();
					var noo_upload_btn   = $(this);

					// if media frame exists, reopen
					if(wp_media_frame) {
		                wp_media_frame.open();
		                return;
		            }

					// create new media frame
					// I decided to create new frame every time to control the selected images
					var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
						title: "<?php echo __( 'Select or Upload your Image', 'noo' ); ?>",
						button: {
							text: "<?php echo __( 'Select', 'noo' ); ?>"
						},
						library: { type: 'image' },
						multiple: false
					});

					// when open media frame, add the selected image
					wp_media_frame.on('open',function() {
						var selected_id = noo_upload_btn.siblings('#term_meta-heading-image').val();
						if (!selected_id)
							return;
						var selection = wp_media_frame.state().get('selection');
						var attachment = wp.media.attachment(selected_id);
						attachment.fetch();
						selection.add( attachment ? [ attachment ] : [] );
					});

					// when image selected, run callback
					wp_media_frame.on('select', function(){
						var attachment = wp_media_frame.state().get('selection').first().toJSON();
						noo_upload_btn.siblings('#term_meta-heading-image').val(attachment.id);

						noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
						noo_thumb_wraper.html('');
						noo_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');

						noo_upload_btn.attr('value', '<?php echo __( 'Change Image', 'noo' ); ?>');
						$('#term_meta-heading-image_button_clear').css('display', 'inline-block');
					});

					// open media frame
					wp_media_frame.open();
				});

				$('#term_meta-heading-image_button_clear').on('click', function(event) {
					var noo_clear_btn = $(this);
					noo_clear_btn.hide();
					$('#term_meta-heading-image_button_upload').attr('value', '<?php echo __( 'Select Image', 'noo' ); ?>');
					noo_clear_btn.siblings('#term_meta-heading-image').val('');
					noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
				});
			});
		</script>
	</div>
	<div class="form-field term_meta-enable-heading-child">
		<label for="term_meta[heading-title]"><?php _e( 'Headline Title', 'noo' ); ?></label>
		<input type="text" name="term_meta[heading-title]" id="term_meta[heading-title]" value="">
		<p class="description"><?php _e( 'This Title is used to display on the Headline.','noo' ); ?></p>
	</div>
	<div class="form-field term_meta-enable-heading-child">
		<label for="term_meta[heading-sub-title]"><?php _e( 'Headline Sub-Title', 'noo' ); ?></label>
		<textarea type="" name="term_meta[heading-sub-title]" id="term_meta[heading-sub-title]" value="" row="5" col="40"></textarea>
		<p class="description"><?php _e( 'This Sub-Title is used to display below Title on the Headline.', 'noo' ); ?></p>
	</div>
	<?php
}

add_action( 'category_add_form_fields', 'noo_taxonomy_add_new_meta_field', 10, 2 );
add_action( 'post_tag_add_form_fields', 'noo_taxonomy_add_new_meta_field', 10, 2 );

// Edit term page
function noo_taxonomy_edit_meta_field($term) {

	wp_enqueue_media();

	// put the term ID into a variable
	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_{$t_id}" );
	$enable    = !isset( $term_meta['enable-heading'] ) ? true : ( $term_meta['enable-heading'] == '1' ); ?>
	<hr/>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta-enable-heading"><?php _e( 'Show Headline', 'noo' ); ?></label></th>
		<td>
			<input type="hidden" name="term_meta[enable-heading]" value="0" >
			<input type="checkbox" class="parent-control" name="term_meta[enable-heading]" id="term_meta-enable-heading" value="1" <?php echo ( $enable ? 'checked="checked"' : '' ); ?> >
			<p class="description"><?php _e( 'Enable Headline when viewing this taxonomy page.', 'noo' ); ?></p>
		</td>
	</tr>
	<tr class="form-field term_meta-enable-heading-child" <?php echo ( $enable ) ? '' : 'style="display: none;"'; ?> >
		<th scope="row" valign="top"><label for="term_meta-heading-image"><?php _e( 'Headline Background Image', 'noo' ); ?></label></th>
		<td>
			<?php
				$image_id = isset( $term_meta['heading-image'] ) ? $term_meta['heading-image'] : '';
				$output   = !empty( $image_id ) ? wp_get_attachment_image( $image_id, 'admin-thumb') : '';
				$btn_text = !empty( $image_id ) ? __( 'Change Image', 'noo' ) : __( 'Select Image', 'noo' );
			?>
			<input type="hidden" name="term_meta[heading-image]" id="term_meta-heading-image" value="<?php echo $image_id; ?>" />
			<input type="button" class="button button-primary" name="term_meta-heading-image_button_upload" id="term_meta-heading-image_button_upload" value="<?php echo $btn_text; ?>" />
			<input type="button" class="button" name="term_meta-heading-image_button_clear" id="term_meta-heading-image_button_clear" value="<?php _e( 'Clear Image', 'noo' ); ?>" <?php echo ( $image_id ) ? '' : 'style="display: none;"' ?> />
			<div class="noo-thumb-wrapper"><?php echo $output; ?></div>
			<p class="description"><?php _e( 'If you leave it blank, it will use the image set on Customizer setting for Index page.', 'noo' ); ?></p>
			<script>
				jQuery(document).ready(function($) {
					$('#term_meta-heading-image_button_upload').on('click', function(event) {
						event.preventDefault();
						var noo_upload_btn   = $(this);

						// if media frame exists, reopen
						if(wp_media_frame) {
			                wp_media_frame.open();
			                return;
			            }

						// create new media frame
						// I decided to create new frame every time to control the selected images
						var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
							title: "<?php echo __( 'Select or Upload your Image', 'noo' ); ?>",
							button: {
								text: "<?php echo __( 'Select', 'noo' ); ?>"
							},
							library: { type: 'image' },
							multiple: false
						});

						// when open media frame, add the selected image
						wp_media_frame.on('open',function() {
							var selected_id = noo_upload_btn.siblings('#term_meta-heading-image').val();
							if (!selected_id)
								return;
							var selection = wp_media_frame.state().get('selection');
							var attachment = wp.media.attachment(selected_id);
							attachment.fetch();
							selection.add( attachment ? [ attachment ] : [] );
						});

						// when image selected, run callback
						wp_media_frame.on('select', function(){
							var attachment = wp_media_frame.state().get('selection').first().toJSON();
							noo_upload_btn.siblings('#term_meta-heading-image').val(attachment.id);

							noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
							noo_thumb_wraper.html('');
							noo_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');

							noo_upload_btn.attr('value', '<?php echo __( 'Change Image', 'noo' ); ?>');
							$('#term_meta-heading-image_button_clear').css('display', 'inline-block');
						});

						// open media frame
						wp_media_frame.open();
					});

					$('#term_meta-heading-image_button_clear').on('click', function(event) {
						var noo_clear_btn = $(this);
						noo_clear_btn.hide();
						$('#term_meta-heading-image_button_upload').attr('value', '<?php echo __( 'Select Image', 'noo' ); ?>');
						noo_clear_btn.siblings('#term_meta-heading-image').val('');
						noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
					});
				});
			</script>
		</td>
	</tr>
	<tr class="form-field term_meta-enable-heading-child" <?php echo ( $enable ) ? '' : 'style="display: none;"'; ?> >
		<th scope="row" valign="top"><label for="term_meta[heading-title]"><?php _e( 'Headline Title', 'noo' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[heading-title]" id="term_meta[heading-title]" value="<?php echo esc_attr( $term_meta['heading-title'] ) ? esc_attr( $term_meta['heading-title'] ) : ''; ?>">
			<p class="description"><?php _e( 'This Title is used to display on the Headline. If you leave it blank, the Title will be used.', 'noo' ); ?></p>
		</td>
	</tr>
	<tr class="form-field term_meta-enable-heading-child" <?php echo ( $enable ) ? '' : 'style="display: none;"'; ?> >
		<th scope="row" valign="top"><label for="term_meta[heading-sub-title]"><?php _e( 'Headline Sub Title', 'noo' ); ?></label></th>
		<td>
			<textarea name="term_meta[heading-sub-title]" id="term_meta[heading-sub-title]" value="<?php echo esc_attr( $term_meta['heading-sub-title'] ) ? esc_attr( $term_meta['heading-sub-title'] ) : ''; ?>" row="5" col="40"></textarea>
			<p class="description"><?php _e( 'This Sub-Title is used to display below Title on the Headline.', 'noo' ); ?></p>
		</td>
	</tr>
	<?php
}

add_action( 'category_edit_form_fields', 'noo_taxonomy_edit_meta_field', 10, 2 );
add_action( 'post_tag_edit_form_fields', 'noo_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function noo_save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_{$t_id}" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}

		// Save the option array.
		update_option( "taxonomy_{$t_id}", $term_meta );
	}
}

add_action( 'edited_category', 'noo_save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_category', 'noo_save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_post_tag', 'noo_save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_post_tag', 'noo_save_taxonomy_custom_meta', 10, 2 );
