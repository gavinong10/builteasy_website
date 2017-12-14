<?php
	/**********************************************
	CUSTOM POST TYPE: PARTNERS
	***********************************************/	
	
	if ( ! function_exists( 'homeland_partners_post_type' ) ) :
		function homeland_partners_post_type() {

			register_post_type( 'homeland_partners',
				array(
					'labels' => array(
						'name' => __( 'Partners', 'codeex_theme_name' ),
						'singular_name' => __( 'Partners', 'codeex_theme_name' ),
						'add_new' => __( 'Add New', 'codeex_theme_name' ),
						'add_new_item' => __( 'Add New Partner', 'codeex_theme_name' ),
						'edit_item' => __( 'Edit Partner', 'codeex_theme_name' ),
						'search_items' => __( 'Search Partners', 'codeex_theme_name' ),
						'not_found' => __( 'No partners found.', 'codeex_theme_name' ),
						'not_found_in_trash' => __( 'No partners found in Trash.', 'codeex_theme_name' ),
					),
					'public' => true,
					'has_archive' => true,	
					'publicly_queryable' => true,
					'show_ui' => true, 
			    	'show_in_menu' => true, 
			   	'query_var' => true,	
				   'rewrite' => array( 'slug' => __( 'partner-item', 'codeex_theme_name' ), 'with_front' => TRUE ),
				   'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields', 'excerpt'),
					'menu_icon' => 'dashicons-groups',		
				)
			);
		}
	endif;
	add_action( 'init', 'homeland_partners_post_type' );


	/*----------------------------
	MetaBoxes
	----------------------------*/

	if ( ! function_exists( 'homeland_partners_meta' ) ) :
		function homeland_partners_meta() {
			global $post;

			$homeland_url = sanitize_text_field( get_post_meta($post->ID, 'homeland_url', TRUE) );
			?>
				<div class="mabuc-form-wrap">
					<div class="mabuc-tab-content current">
						<ul>
							<li>
								<label for="homeland_url"><?php _e( 'Website Link', 'codeex_theme_name' ); ?></label>
								<input id="homeland_url" name="homeland_url" type="text" value="<?php echo esc_attr( $homeland_url ); ?>" /><br>
									<span class="desc"><?php _e( 'Provide your partner website link here', 'codeex_theme_name' ); ?></span>
							</li>
						</ul>
					</div>		
				</div>	
			<?php
		}	
	endif;

	
	/*----------------------------
	Custom Columns
	----------------------------*/

	if ( ! function_exists( 'homeland_edit_partners_columns' ) ) :
		function homeland_edit_partners_columns( $columns ) {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __( 'Name', 'codeex_theme_name' ),		
				'url' => __( 'Website', 'codeex_theme_name' ),	
				'thumbnail' => __( 'Thumbnail', 'codeex_theme_name' ),
				'date' => __( 'Date', 'codeex_theme_name' )
			);
			return $columns;
		}
	endif;
	add_filter( 'manage_edit-homeland_partners_columns', 'homeland_edit_partners_columns' ) ;

	
	if ( ! function_exists( 'homeland_manage_partners_columns' ) ) :
		function homeland_manage_partners_columns( $column ) {
			global $post;

			switch( $column ) {	
				case 'url' :
					$homeland_url = get_post_meta( $post->ID, 'homeland_url', true );
					if ( empty( $homeland_url ) )
						echo __( 'No URL', 'codeex_theme_name' );
					else
						?>
							<a href="<?php printf( __( '%s', 'codeex_theme_name' ), $homeland_url ); ?>" target="_blank">
								<?php printf( __( '%s', 'codeex_theme_name' ), $homeland_url ); ?>
							</a>
						<?php
				break;

				case 'thumbnail' : 
	      			echo the_post_thumbnail( array(80,80) );
	      		break;
				
				default :
				break;
			}
		}
	endif;
	add_action( 'manage_homeland_partners_posts_custom_column', 'homeland_manage_partners_columns', 10, 2 );


	/*----------------------------
	Save and Update
	----------------------------*/
	
	if ( ! function_exists( 'homeland_custom_posts_partners' ) ) :
		function homeland_custom_posts_partners(){
			add_meta_box(
				"homeland_partners_meta", 
				__( 'Partner Options', 'codeex_theme_name' ), 
				"homeland_partners_meta", 
				"homeland_partners", 
				"normal", 
				"low"
			);
		}
	endif;
	add_action( 'add_meta_boxes', 'homeland_custom_posts_partners' );	
	

	if ( ! function_exists( 'homeland_custom_posts_save_partners' ) ) :
		function homeland_custom_posts_save_partners( $post_id ){
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) return;
			if ( 'page' == isset($_POST['post_type']) ) { if ( !current_user_can( 'edit_page', $post_id ) ) return;
			} else { if ( !current_user_can( 'edit_post', $post_id ) ) return; }
			
			$homeland_fields = array( 'homeland_url' );

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
	add_action('save_post', 'homeland_custom_posts_save_partners');
?>