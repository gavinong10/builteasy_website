<?php
	/**********************************************
	CUSTOM POST TYPE: TESTIMONIALS
	***********************************************/
	
	if ( ! function_exists( 'homeland_testimonial_post_type' ) ) :
		function homeland_testimonial_post_type() {
			register_post_type( 'homeland_testimonial',
				array(
					'labels' => array(
						'name' => __( 'Testimonials', 'codeex_theme_name' ),
						'singular_name' => __( 'Testimonials', 'codeex_theme_name' ),
						'add_new' => __( 'Add New', 'codeex_theme_name' ),
						'add_new_item' => __( 'Add New Testimonial', 'codeex_theme_name' ),
						'edit_item' => __( 'Edit Testimonial', 'codeex_theme_name' ),
						'search_items' => __( 'Search Testimonial', 'codeex_theme_name' ),
						'not_found' => __( 'No testimonials found.', 'codeex_theme_name' ),
						'not_found_in_trash' => __( 'No testimonials found in Trash.', 'codeex_theme_name' ),
					),
					'public' => true,
					'has_archive' => true,	
					'publicly_queryable' => true,
					'show_ui' => true, 
				    'show_in_menu' => true, 
				    'query_var' => true,	
					'rewrite' => array( 'slug' => __( 'testimonial-item', 'codeex_theme_name' ), 'with_front' => TRUE ),
					'supports' => array('title', 'editor', 'author', 'thumbnail', 'custom-fields'),
					'menu_icon' => 'dashicons-edit',		
				)
			);
		}
	endif;
	add_action( 'init', 'homeland_testimonial_post_type' );
	

	/*----------------------------
	MetaBoxes
	----------------------------*/

	if ( ! function_exists( 'homeland_testimonial_meta' ) ) :
		function homeland_testimonial_meta() {
			global $post;

			$homeland_position = sanitize_text_field( get_post_meta($post->ID, 'homeland_position', TRUE) ); ?>
			<div class="mabuc-form-wrap">	
				<div class="mabuc-tab-content current">
					<ul>
						<li>
							<label for="homeland_position"><?php _e( 'Position', 'codeex_theme_name' ); ?></label>
							<input name="homeland_position" type="text" id="homeland_position" value="<?php echo esc_attr( $homeland_position ); ?>" /> <br>
							<span class="desc"><?php _e( 'Provide a user position', 'codeex_theme_name' ); ?></span>
						</li>
					</ul>
				</div>			
			</div><?php	
		}
	endif;


	/*----------------------------
	Custom Columns
	----------------------------*/

	if ( ! function_exists( 'homeland_edit_testimonial_columns' ) ) :
		function homeland_edit_testimonial_columns( $columns ) {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __( 'Name', 'codeex_theme_name' ),	
				'position' => __( 'Position', 'codeex_theme_name' ),	
				'thumbnail' => __( 'Thumbnail', 'codeex_theme_name' ),
				'date' => __( 'Date', 'codeex_theme_name' )
			);
			return $columns;
		}
	endif;
	add_filter( 'manage_edit-homeland_testimonial_columns', 'homeland_edit_testimonial_columns' ) ;
	

	if ( ! function_exists( 'homeland_manage_testimonial_columns' ) ) :
		function homeland_manage_testimonial_columns( $column ) {
			global $post;

			switch($column) {
				case 'position' : 
	      			$homeland_position = get_post_meta( $post->ID, 'homeland_position', true );
	      			echo $homeland_position;
	      		break;

				case 'thumbnail' : 
	      			echo the_post_thumbnail( array(80,80) );
	      		break;
				
				default :
				break;
			}
		}
	endif;
	add_action( 'manage_homeland_testimonial_posts_custom_column', 'homeland_manage_testimonial_columns', 10, 2 );


	/*----------------------------
	Save and Update
	----------------------------*/
	
	if ( ! function_exists( 'homeland_custom_posts_testimonial' ) ) :
		function homeland_custom_posts_testimonial(){
			add_meta_box(
				"homeland_testimonial_meta", 
				__( 'Testimonial Options', 'codeex_theme_name' ), 
				"homeland_testimonial_meta", 
				"homeland_testimonial", 
				"normal", 
				"low"
			);
		}	
	endif;
	add_action( 'add_meta_boxes', 'homeland_custom_posts_testimonial' );


	if ( ! function_exists( 'homeland_custom_posts_save_testimonial' ) ) :
		function homeland_custom_posts_save_testimonial( $post_id ){
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) return;
			if ( 'page' == isset($_POST['post_type']) ) { if ( !current_user_can( 'edit_page', $post_id ) ) return;
			} else { if ( !current_user_can( 'edit_post', $post_id ) ) return; }
			
			$homeland_fields = array( 'homeland_position' );

			foreach ($homeland_fields as $homeland_value) {
	         if( isset($homeland_value) ) :

	            $homeland_new = false;
	            $homeland_old = get_post_meta( $post_id, $homeland_value, true );

	            if ( isset( $_POST[$homeland_value] ) ) :
	               $homeland_new = $_POST[$homeland_value];
	           	endif;

	            if ( isset( $homeland_new ) && '' == $homeland_new && $homeland_old ) :
	               delete_post_meta( $post_id, $homeland_value, $homeland_old );
	            elseif ( false === $homeland_new || !isset( $homeland_new ) ) :
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
	add_action('save_post', 'homeland_custom_posts_save_testimonial');
?>