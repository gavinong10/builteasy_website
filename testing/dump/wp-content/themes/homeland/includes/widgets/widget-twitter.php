<?php	
	/**********************************************
	CUSTOM TWITTER FEED WIDGET
	***********************************************/
	
	class homeland_Widget_TwitterFeed extends WP_Widget {
	
		function homeland_Widget_TwitterFeed() {		
			$widget_ops = array('classname' => 'homeland_widget-twitter', 'description' => __('Custom widget for twitter feed', 'codeex_theme_name'));	
			parent::__construct('Twitter', __('Homeland: Twitter Feed', 'codeex_theme_name'), $widget_ops);		
		}

		function widget($args, $instance) {		

			if ( is_active_widget( false, false, $this->id_base, true ) ) :
				wp_register_script( 'homeland_tweet', get_template_directory_uri() . '/js/twitter/jquery.tweet.min.js', array(), '', true );
				wp_enqueue_script( 'homeland_tweet' );
   		endif;	

			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;
				$instance_homeland_twitter_id = array();
				$instance_homeland_twitter_limit = array();
				$instance_homeland_twitter_loading_text = array();
				
				$homeland_twitter_id = 'homeland_twitter_id';
				$instance_homeland_twitter_id = isset($instance[$homeland_twitter_id]) ? $instance[$homeland_twitter_id] : '';
				$homeland_twitter_limit = 'homeland_twitter_limit';
				$instance_homeland_twitter_limit = isset($instance[$homeland_twitter_limit]) ? $instance[$homeland_twitter_limit] : '';
				$homeland_twitter_loading_text = 'homeland_twitter_loading_text';
				$instance_homeland_twitter_loading_text = isset($instance[$homeland_twitter_loading_text]) ? $instance[$homeland_twitter_loading_text] : '';
							
				echo ''.$before_widget.'';					
					if ($title) {						
						echo $before_title; ?><i class="fa fa-twitter"></i><?php
						echo $title; 
						echo $after_title.'';						
					}

				?>	

				<script type="text/javascript">
					(function($) {
					  	"use strict";
					  	
					  	jQuery(function($){
							$(".tweet").tweet({
								modpath: "<?php echo get_template_directory_uri(); ?>/js/twitter/index.php",
								username: "<?php echo $instance_homeland_twitter_id; ?>",
								count: <?php echo $instance_homeland_twitter_limit; ?>,							
								loading_text: "<?php echo $instance_homeland_twitter_loading_text; ?>...",
								filter: function(t){ return ! /^@\w+/.test(t.tweet_raw_text); },
							});		
						});

					})(jQuery);					
				</script>
				
				<!--TWITTER FEED-->
				<div class="tweet">&nbsp;</div>  

				<?php
					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['homeland_twitter_id'] = $new_instance['homeland_twitter_id'];
					$instance['homeland_twitter_limit'] = $new_instance['homeland_twitter_limit'];				
					$instance['homeland_twitter_loading_text'] = $new_instance['homeland_twitter_loading_text'];				
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
					$instance_homeland_twitter_id = array();
					$instance_homeland_twitter_limit = array();							
					$instance_homeland_twitter_loading_text = array();							
					
					$homeland_twitter_id = 'homeland_twitter_id';
					$instance_homeland_twitter_id = isset($instance[$homeland_twitter_id]) ? $instance[$homeland_twitter_id] : '';
					$homeland_twitter_limit = 'homeland_twitter_limit';
					$instance_homeland_twitter_limit = isset($instance[$homeland_twitter_limit]) ? $instance[$homeland_twitter_limit] : '';	
					$homeland_twitter_loading_text = 'homeland_twitter_loading_text';
					$instance_homeland_twitter_loading_text = isset($instance[$homeland_twitter_loading_text]) ? $instance[$homeland_twitter_loading_text] : '';					
					
					?>
						<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
						<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>					
						<div>			
							<div>
								<p><label for="<?php echo $this -> get_field_id($homeland_twitter_id); ?>"><?php _e('ID', 'codeex_theme_name'); ?></label>
								<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_twitter_id); ?>" name="<?php echo $this -> get_field_name($homeland_twitter_id); ?>" value="<?php echo $instance_homeland_twitter_id; ?>">		
								</p>
								<p><label for="<?php echo $this -> get_field_id($homeland_twitter_limit); ?>"><?php _e('Limit', 'codeex_theme_name'); ?></label>
								<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_twitter_limit); ?>" name="<?php echo $this -> get_field_name($homeland_twitter_limit); ?>" value="<?php echo $instance_homeland_twitter_limit; ?>">
								</p>	
								<p><label for="<?php echo $this -> get_field_id($homeland_twitter_loading_text); ?>"><?php _e('Loading Text', 'codeex_theme_name'); ?></label>
								<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_twitter_loading_text); ?>" name="<?php echo $this -> get_field_name($homeland_twitter_loading_text); ?>" value="<?php echo $instance_homeland_twitter_loading_text; ?>">
								</p>														
							</div>			
						</div>
					<?php
				}			
		}

		function homeland_widgets_twitterfeed() {			
			register_widget('homeland_Widget_TwitterFeed');			
		}
		add_action('widgets_init', 'homeland_widgets_twitterfeed');
?>