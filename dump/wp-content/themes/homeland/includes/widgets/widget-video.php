<?php
	/**********************************************
	CUSTOM VIDEO WIDGET
	***********************************************/
	
	class homeland_Widget_Video extends WP_Widget {
	
		function homeland_Widget_Video() {		
			$widget_ops = array('classname' => 'homeland_widget-video', 'description' => __('Custom widget for video', 'codeex_theme_name'));	
			parent::__construct('Video', __('Homeland: Video', 'codeex_theme_name'), $widget_ops);		
		}
	
		function widget($args, $instance) {		
			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;
				$instance_homeland_video_url = array();
				
				$homeland_video_url = 'homeland_video_url';
				$instance_homeland_video_url = isset($instance[$homeland_video_url]) ? $instance[$homeland_video_url] : '';

				echo $before_widget;					

				?>	
				
				<!--VIDEO-->
				<?php
					if ($title) {						
						echo $before_title;
						echo $title;
						echo $after_title;						
					}
				?>

				<div class="side-video"><?php echo $instance_homeland_video_url; ?></div>

				<?php
					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['homeland_video_url'] = $new_instance['homeland_video_url'];				
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
					$instance_homeland_video_url = array();
					
					$homeland_video_url = 'homeland_video_url';
					$instance_homeland_video_url = isset($instance[$homeland_video_url]) ? $instance[$homeland_video_url] : '';			
					
				?>
					<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
					<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>					
					<div>			
						<div>
							<p><label for="<?php echo $this -> get_field_id($homeland_video_url); ?>"><?php _e('Video iFrame', 'codeex_theme_name'); ?></label>
							<textarea class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_video_url); ?>" name="<?php echo $this -> get_field_name($homeland_video_url); ?>"><?php echo $instance_homeland_video_url; ?></textarea>		
							</p>
							<small><i><?php _e( 'Please enter the entire embedd codes from vimeo, youtube or dailymotion', 'codeex_theme_name' ); ?></i></small>
						</div>			
					</div>
		<?php
				}			
		}

		function homeland_widgets_video() {			
			register_widget('homeland_Widget_Video');			
		}
		add_action('widgets_init', 'homeland_widgets_video');
?>