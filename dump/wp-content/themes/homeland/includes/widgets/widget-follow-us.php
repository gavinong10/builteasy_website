<?php
	/**********************************************
	CUSTOM FOLLOW US WIDGET
	***********************************************/
	
	class homeland_Widget_GetTouch extends WP_Widget {
	
		function homeland_Widget_GetTouch() {		
			$widget_ops = array('classname' => 'homeland_widget-get-in-touch', 'description' => __('Custom widget for connect with us', 'codeex_theme_name'));	
			parent::__construct('GetTouch', __('Homeland: Connect with us', 'codeex_theme_name'), $widget_ops);		
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

				$homeland_twitter = esc_attr( get_option('homeland_twitter') );
				$homeland_facebook = esc_attr( get_option('homeland_facebook') );
				$homeland_pinterest = esc_attr( get_option('homeland_pinterest') );
				$homeland_dribbble = esc_attr( get_option('homeland_dribbble') );
				$homeland_instagram = esc_attr( get_option('homeland_instagram') );
				$homeland_rss = esc_url( get_option('homeland_rss') );
				$homeland_youtube = esc_url( get_option('homeland_youtube') );
				$homeland_gplus = esc_url( get_option('homeland_gplus') );
				$homeland_linkedin = esc_url( get_option('homeland_linkedin') );

				?>	

				<!--FOLLOW US-->
				<div class="social-colors">
					<ul class="clear">
						<?php 
							if(!empty( $homeland_twitter )) : ?>	
								<li class="twitter"><a href="http://twitter.com/<?php echo $homeland_twitter; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_facebook )) : ?>	
								<li class="facebook"><a href="http://facebook.com/<?php echo $homeland_facebook; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_youtube )) : ?>	
								<li class="youtube"><a href="<?php echo $homeland_youtube; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_linkedin )) : ?>	
								<li class="linkedin"><a href="<?php echo $homeland_linkedin; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_pinterest )) : ?>	
								<li class="pinterest"><a href="http://pinterest.com/<?php echo $homeland_pinterest; ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_dribbble )) : ?>	
								<li class="dribbble"><a href="http://dribbble.com/<?php echo $homeland_dribbble; ?>" target="_blank"><i class="fa fa-dribbble"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_gplus )) : ?>	
								<li class="gplus"><a href="<?php echo $homeland_gplus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li><?php 
							endif;
							if(!empty( $homeland_instagram )) : ?>	
								<li class="instagram"><a href="http://instagram.com/<?php echo $homeland_instagram; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li><?php 
							endif; 
							if(!empty( $homeland_rss )) : ?>	
								<li class="rss"><a href="<?php echo $homeland_rss; ?>" target="_blank"><i class="fa fa-rss"></i></a></li><?php 
							endif;  
						?>	
					</ul>
				</div>

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
						<small><i><?php _e( 'Social links will automatically display, set id and name in Theme Options', 'codeex_theme_name' ); ?></i></small>	
					</div>
		<?php
				}			
		}

		function homeland_widgets_gettouch() {			
			register_widget('homeland_Widget_GetTouch');			
		}
		add_action('widgets_init', 'homeland_widgets_gettouch');
?>