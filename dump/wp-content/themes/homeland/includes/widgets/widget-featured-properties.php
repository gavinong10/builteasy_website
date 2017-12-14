<?php
	/**********************************************
	CUSTOM FEATURED PROPERTIES WIDGET
	***********************************************/
	
	class homeland_Widget_Featured_Properties extends WP_Widget {
	
		function homeland_Widget_Featured_Properties() {		
			$widget_ops = array('classname' => 'homeland_widget-featured-properties', 'description' => __('Custom widget for featured properties', 'codeex_theme_name'));	
			parent::__construct('FeaturedProperties', __('Homeland: Featured Properties', 'codeex_theme_name'), $widget_ops);		
		}
	
		function widget($args, $instance) {		
			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;
				$instance_property_limit = array();
				
				$property_limit = 'property_limit';
				$instance_property_limit = isset($instance[$property_limit]) ? esc_attr($instance[$property_limit]) : '';
							
				echo ''.$before_widget.'';					
				if ($title) {						
					echo $before_title;
			 		echo $title;
			 		echo $after_title.'';						
			 	}

					global $post;

			 		$args = array( 
			 			'post_type' => 'homeland_properties', 
			 			'posts_per_page' => $instance_property_limit, 
			 			'meta_query' => array( array( 
			 				'key' => 'homeland_featured', 
			 				'value' => 'on', 
			 				'compare' => '==' 
			 			) ) 
			 		);		

					$wp_feat_query = new WP_Query( $args );						
					?>
						<div class="featured-flexslider grid cs-style-3">
							<ul class="slides">
								<?php
									while ($wp_feat_query->have_posts()) : 
										$wp_feat_query->the_post(); 
										$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
										$homeland_price = esc_attr( get_post_meta( $post->ID, 'homeland_price', true ) );
										$homeland_price_format = esc_attr( get_option('homeland_price_format') );
										$homeland_currency = esc_attr( get_option('homeland_property_currency') );
										$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') );
										?>
										<li>
											<figure class="pimage">
												<a href="<?php the_permalink(); ?>">
													<?php 
														if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_property_medium'); 
														else :
															echo '<img src="'. get_template_directory_uri() .'/img/no-property-image.png" title="" alt="" />';
														endif; 
													?>
												</a>
												<figcaption><a href="<?php the_permalink(); ?>"><i class="fa fa-link fa-lg"></i></a></figcaption>
												<div class="property-desc-slide">
													<?php 
														the_title( '<h3>', '</h3>' );
														if( !empty($homeland_price) ) : ?>
															<span class="price"><?php homeland_property_price_format(); ?></span><?php
														endif;
													?>
												</div>
											</figure>
										</li>
										<?php
									endwhile;	
								?>
							</ul>
						</div>						
					<?php
					wp_reset_query();

					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['property_limit'] = $new_instance['property_limit'];				
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
					$instance_property_limit = array();							
					
					$property_limit = 'property_limit';
					$instance_property_limit = isset($instance[$property_limit]) ? esc_attr($instance[$property_limit]) : '';					
					
				?>
					<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
					<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>					
					<div>			
						<div>
							<p><label for="<?php echo $this -> get_field_id($property_limit); ?>"><?php _e('Number of Posts', 'codeex_theme_name'); ?></label>
							<input class="widefat" type="text" id="<?php echo $this -> get_field_id($property_limit); ?>" name="<?php echo $this -> get_field_name($property_limit); ?>" value="<?php echo $instance_property_limit; ?>">
							</p>														
						</div>			
					</div>
		<?php
				}			
		}

		function homeland_widget_featured_properties() {			
			register_widget('homeland_Widget_Featured_Properties');			
		}
		add_action('widgets_init', 'homeland_widget_featured_properties');
?>