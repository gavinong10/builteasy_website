<?php
	/**********************************************
	CUSTOM PROPERTY STATUS WIDGET
	***********************************************/
	
	class homeland_Widget_Property_Status extends WP_Widget {
	
		function homeland_Widget_Property_Status() {		
			$widget_ops = array('classname' => 'homeland_widget-property-status', 'description' => __('Custom widget for property status', 'codeex_theme_name'));	
			parent::__construct('PropertyStatus', __('Homeland: Search By Status', 'codeex_theme_name'), $widget_ops);		
		}
	
		function widget($args, $instance) {		
			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;
					
				$instance_follow_bdesc = array();

				echo $before_widget;	
				if ($title) {						
					echo $before_title;
					echo $title;
					echo $after_title;						
				}				

				?>	

				<!--PROPERTY STATUS LIST-->
				<ul>
					<?php
						global $homeland_advance_search_page_url;
						
						$args = array( 
							'taxonomy' => 'homeland_property_status', 
							'style' => 'list', 
							'title_li' => '', 
							'hierarchical' => false, 
							'order' => 'ASC', 
							'orderby' => 'title' 
						);		

						$homeland_status = get_categories($args);						
					  	
					  	foreach($homeland_status as $homeland_category) { 
					  		echo '<li><a href="' . $homeland_advance_search_page_url . '?status='. $homeland_category->slug . '" title="' . sprintf( __( "View all posts in %s", 'codeex_theme_name' ), $homeland_category->name ) . '" ' . '>' . $homeland_category->name.'</a></li>';
					  	} 
					?>
				</ul>

				<?php
					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';												
					
				?>
					<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
					<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
					<div>	
						<small><i><?php esc_attr( _e( 'Property Status will automatically display', 'codeex_theme_name' ) ); ?></i></small>	
					</div>
		<?php
				}			
		}

		function homeland_widgets_property_status() {			
			register_widget('homeland_Widget_Property_Status');			
		}
		add_action('widgets_init', 'homeland_widgets_property_status');
?>