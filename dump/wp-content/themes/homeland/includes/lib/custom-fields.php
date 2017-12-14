<?php
	/**********************************************
	POSTS CUSTOM FIELDS
	***********************************************/

	if ( ! function_exists( 'homeland_post_menu' ) ) :
		function homeland_post_menu() {
			global $post;

			$homeland_advance_search = sanitize_text_field( get_post_meta($post->ID, 'homeland_advance_search', TRUE) );
			$homeland_video = sanitize_text_field( get_post_meta($post->ID, 'homeland_video', TRUE) );
			$homeland_hdimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_hdimage', TRUE) );		
			$homeland_bgimage =  sanitize_text_field( get_post_meta($post->ID, 'homeland_bgimage', TRUE) );		
			$homeland_audio =  get_post_meta($post->ID, 'homeland_audio', TRUE);		
			$homeland_rev_slider = sanitize_text_field( get_post_meta($post->ID, 'homeland_rev_slider', TRUE ) );

			$homeland_custom = get_post_custom($post->ID);
			$homeland_selected = isset( $homeland_custom['homeland_video_host'] ) ? esc_attr( $homeland_custom['homeland_video_host'][0] ) : ''; 

			?>
				<div class="mabuc-form-wrap">

					<!-- Tabs -->
					<ul class="mabuc-tabs">
						<li class="mabuc-tab-link current" data-tab="tab-1">
							<i class="fa fa-home"></i><?php _e( 'Main Settings', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-2">
							<i class="fa fa-image"></i><?php _e( 'Images', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-3">
							<i class="fa fa-video-camera"></i></i><?php _e( 'Video', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-4">
							<i class="fa fa-volume-up"></i></i><?php _e( 'Audio', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-5">
							<i class="fa fa-sliders"></i><?php _e( 'Slider', 'codeex_theme_name' ); ?>
						</li>
					</ul>

					<!-- Main Settings -->
					<div id="tab-1" class="mabuc-tab-content current">
						<ul>
							<li>
								<label for="homeland_advance_search">
									<?php esc_attr( _e( 'Hide Search', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_advance_search" type="checkbox" id="homeland_advance_search" <?php if( $homeland_advance_search == true ) { ?>checked="checked"<?php } ?> /><br>
								<span class="desc"><?php esc_attr( _e( 'Tick the box to hide advance search in this post', 'codeex_theme_name' ) ); ?></span>
							</li>
						</ul>
					</div>

					<!-- Images -->
					<div id="tab-2" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_hdimage">
									<?php esc_attr( _e( 'Header Image', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_hdimage" type="text" id="homeland_hdimage" value="<?php echo esc_attr( $homeland_hdimage ); ?>" /> <input id="upload_image_button_homeland_hdimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Please upload header image. Otherwise default header image from theme options will be displayed', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_bgimage">
									<?php esc_attr( _e( 'Background Image', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_bgimage" type="text" id="homeland_bgimage" value="<?php echo esc_attr( $homeland_bgimage ); ?>" /> <input id="upload_image_button_homeland_bgimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Please upload background image. Otherwise default background image from theme options will be displayed', 'codeex_theme_name' ) ); ?></span>
							</li>
						</ul>
					</div>

					<!-- Video -->
					<div id="tab-3" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_video_host">
									<?php esc_attr( _e( 'Video Host', 'codeex_theme_name' ) ); ?>
								</label>
								<select name="homeland_video_host" type="text" id="homeland_video_host">
									<option value="">--------------</option>
									<option value="youtube" <?php selected( $homeland_selected, 'youtube' ); ?>><?php esc_attr( _e( 'Youtube', 'codeex_theme_name' ) ); ?></option>
									<option value="vimeo" <?php selected( $homeland_selected, 'vimeo' ); ?>><?php esc_attr( _e( 'Vimeo', 'codeex_theme_name' ) ); ?></option>
									<option value="self" <?php selected( $homeland_selected, 'self' ); ?>><?php esc_attr( _e( 'Self Hosted', 'codeex_theme_name' ) ); ?></option>
								</select> <span class="desc"><?php esc_attr( _e( 'Select your video host here either YouTube, Vimeo or Self Hosted. Please make sure you selected the video post format', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_video">
									<?php esc_attr( _e( 'Video', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_video" type="text" class="newtag" id="homeland_video" value="<?php echo esc_attr( $homeland_video ); ?>" /> <input id="upload_image_button_homeland_svideo" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Add your video embedded code here (ex. http://player.vimeo.com/video/21942776 and http://youtube.com/embed/68AqHwgk2s8) or just click upload for self hosted video', 'codeex_theme_name' ) ); ?></span>
							</li>
						</ul>
					</div>
						
					<!-- Audio -->
					<div id="tab-4" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_audio">
									<?php esc_attr( _e( 'Audio', 'codeex_theme_name' ) ); ?>
								</label>
								<textarea name="homeland_audio" type="text" id="homeland_audio"><?php echo $homeland_audio; ?></textarea><br>
								<?php $homeland_soundcloud_link = "<a href='https://soundcloud.com/' target='_blank'>here</a>"; ?>
								<span class="desc">
									<?php printf( __( 'Add your audio embedded code %1$s from soundcloud', 'codeex_theme_name' ), $homeland_soundcloud_link ); ?>
								</span>
							</li>
						</ul>
					</div>

					<!-- Slider -->
					<div id="tab-5" class="mabuc-tab-content">
						<ul>
							<?php
								if(shortcode_exists("rev_slider")) : ?>
								   <li>
								   	<label for="homeland_rev_slider">
								   		<?php _e( 'Revolution Slider', 'codeex_theme_name' ); ?>
								   	</label>
								   	<select name="homeland_rev_slider" id="homeland_rev_slider">
									   	<?php
												$slider = new RevSlider();
												$revolution_sliders = $slider->getArrSliders();
												 
												echo "<option value=''>Select</option>";
												foreach ( $revolution_sliders as $revolution_slider ) {
										       	$checked="";
											       $alias = $revolution_slider->getAlias();
											       $title = $revolution_slider->getTitle();
											       if($alias==$homeland_rev_slider) $checked="selected";
											       echo "<option value='".$alias."' $checked>".$title."</option>";
												}
											?>
										</select><br>
										<span class="desc"><?php _e( 'Select your slider if you want to use revolution slider in blog single page', 'codeex_theme_name' ); ?></span>
								   </li><?php
								endif;
							?>
						</ul>
					</div>						
				</div>	
			<?php
		}	
	endif;
	

	/**********************************************
	PAGE CUSTOM FIELDS
	***********************************************/

	if ( ! function_exists( 'homeland_page_menu' ) ) :
		function homeland_page_menu() {
			global $post;

			$homeland_advance_search = sanitize_text_field( get_post_meta($post->ID, 'homeland_advance_search', TRUE) );
			$homeland_ptitle = sanitize_text_field( get_post_meta($post->ID, 'homeland_ptitle', TRUE) );
			$homeland_subtitle = sanitize_text_field( get_post_meta($post->ID, 'homeland_subtitle', TRUE) );
			$homeland_bgimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_bgimage', TRUE) );			
			$homeland_hdimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_hdimage', TRUE) );		

			?>
				<div class="mabuc-form-wrap">

					<!-- Tabs -->
					<ul class="mabuc-tabs">
						<li class="mabuc-tab-link current" data-tab="tab-1">
							<i class="fa fa-home"></i><?php _e( 'Main Settings', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-2">
							<i class="fa fa-image"></i><?php _e( 'Images', 'codeex_theme_name' ); ?>
						</li>
					</ul>

					<!-- Main Information -->
					<div id="tab-1" class="mabuc-tab-content current">
						<ul>
							<li>
								<label for="homeland_advance_search">
									<?php esc_attr( _e( 'Hide Search', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_advance_search" type="checkbox" id="homeland_advance_search" <?php if( $homeland_advance_search == true ) { ?>checked="checked"<?php } ?> /><br>
								<span class="desc"><?php esc_attr( _e( 'Tick the box to hide advance search in this page', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_ptitle">
									<?php esc_attr( _e( 'Page Title', 'codeex_theme_name' ) ); ?>
								</label>	
								<input name="homeland_ptitle" type="text" id="homeland_ptitle" value="<?php echo esc_attr( $homeland_ptitle ); ?>" /><br>
								<span class="desc"><?php esc_attr( _e( 'Provide your page title here, this will replace your default page title', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_subtitle">
									<?php esc_attr( _e( 'Page Subtitle', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_subtitle" type="text" id="homeland_subtitle" value="<?php echo esc_attr( $homeland_subtitle ); ?>" /><br>
								<span class="desc"><?php esc_attr( _e( 'Provide your page subtitle here, this will be added at the bottom of your page title', 'codeex_theme_name' ) ); ?></span>
							</li>
						</ul>
					</div>

					<!-- Images -->
					<div id="tab-2" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_hdimage">
									<?php esc_attr( _e( 'Header Image', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_hdimage" type="text" id="homeland_hdimage" value="<?php echo esc_attr( $homeland_hdimage ); ?>" /> <input id="upload_image_button_homeland_hdimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Please upload header image. Otherwise default header image from theme options will be displayed', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_bgimage">
									<?php esc_attr( _e( 'Background Image', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_bgimage" type="text" id="homeland_bgimage" value="<?php echo esc_attr( $homeland_bgimage ); ?>" /> <input id="upload_image_button_homeland_bgimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Please upload background image. Otherwise default background image from theme options will be displayed', 'codeex_theme_name' ) ); ?></span>
							</li>
						</ul>
					</div>										
				</div>	
			<?php
		}
	endif;


	/**********************************************
	ADD AND SAVE CUSTOM FIELDS
	***********************************************/

	if ( ! function_exists( 'homeland_custom_fields_box' ) ) :
		function homeland_custom_fields_box(){
			add_meta_box("homeland_post_menu", __( 'Post Options', 'codeex_theme_name' ), "homeland_post_menu", "post", "normal", "low");
			add_meta_box("homeland_page_menu", __( 'Page Options', 'codeex_theme_name' ), "homeland_page_menu", "page", "normal", "low");		
		}	
	endif;
	add_action( 'add_meta_boxes', 'homeland_custom_fields_box' );

	
	if ( ! function_exists( 'homeland_custom_fields_save' ) ) :	
		function homeland_custom_fields_save( $post_id ){
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) return;
			if ( 'page' == isset($_POST['post_type']) ) { if ( !current_user_can( 'edit_page', $post_id ) ) return;
			} else { if ( !current_user_can( 'edit_post', $post_id ) ) return; }

			$homeland_fields = array( 
				'homeland_advance_search',
				'homeland_ptitle', 
				'homeland_subtitle', 
				'homeland_hdimage', 
				'homeland_bgimage', 
				'homeland_audio', 
				'homeland_video', 
				'homeland_video_host',
				'homeland_rev_slider' 
			);

			foreach ($homeland_fields as $homeland_value) {
	         if( isset($homeland_value) ) :

	            $homeland_new = false;
	            $homeland_old = get_post_meta( $post_id, $homeland_value, true );

	            if ( isset( $_POST[$homeland_value] ) ) :
	               $homeland_new = $_POST[$homeland_value];
	           	endif;

	            if ( isset( $homeland_new ) && '' == $homeland_new && $homeland_old ) :
	               delete_post_meta( $post_id, $homeland_value, $homeland_old );
	            elseif ( ! $homeland_new || !isset( $homeland_new ) ) :
	            	delete_post_meta( $post_id, $homeland_value, $homeland_old );
	            elseif ( isset( $homeland_new ) && $homeland_new != $homeland_old ) :
	            	update_post_meta( $post_id, $homeland_value, $homeland_new );
	           	elseif ( ! isset( $homeland_old ) && isset( $homeland_new ) ) :
	               add_post_meta( $post_id, $homeland_value, $homeland_new );
	            endif;

	         endif;
	      }
		}	
	endif;
	add_action('save_post', 'homeland_custom_fields_save');
?>