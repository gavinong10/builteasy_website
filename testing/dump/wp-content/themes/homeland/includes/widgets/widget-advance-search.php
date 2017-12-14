<?php
	/**********************************************
	CUSTOM PROPERTY ADVANCE SEARCH WIDGET
	***********************************************/
	
	class homeland_Widget_Property_Advance_Search extends WP_Widget {
	
		function homeland_Widget_Property_Advance_Search() {		
			$widget_ops = array('classname' => 'homeland_widget-property-advance-search', 'description' => __('Custom widget for property advance search', 'codeex_theme_name'));	
			parent::__construct('AdvanceSearch', __('Homeland: Advance Search Property', 'codeex_theme_name'), $widget_ops);		
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

				<div class="advance-search-widget"><?php homeland_advance_search_form(); ?></div>

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
						<small><i><?php esc_attr( _e( 'Property Advance Search will automatically display', 'codeex_theme_name' ) ); ?></i></small>	
					</div>
		<?php
				}			
		}

		function homeland_widgets_property_advance_search() {			
			register_widget('homeland_Widget_Property_Advance_Search');			
		}
		add_action('widgets_init', 'homeland_widgets_property_advance_search');
?>