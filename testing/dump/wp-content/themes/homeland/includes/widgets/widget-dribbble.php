<?php	
	/**********************************************
	CUSTOM DRIBBBLE FEED WIDGET
	***********************************************/
	
	class homeland_Widget_Dribbble extends WP_Widget {
	
		function homeland_Widget_Dribbble() {		
			$widget_ops = array('classname' => 'homeland_widget-dribbble', 'description' => __('Custom widget for dribbble feed', 'codeex_theme_name'));	
			parent::__construct('DribbbleFeed', __('Homeland: Dribbble Feed', 'codeex_theme_name'), $widget_ops);		
		}
	
		function widget($args, $instance) {		

			if ( is_active_widget( false, false, $this->id_base, true ) ) :
				wp_register_script( 'homeland_dribbble', get_template_directory_uri() . '/js/jribbble.js', array(), '', true );
				wp_enqueue_script( 'homeland_dribbble' );
   		endif;	

			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;
				$instance_homeland_dribbble_id = array();
				$instance_homeland_dribbble_limit = array();
				
				$homeland_dribbble_id = 'homeland_dribbble_id';
				$instance_homeland_dribbble_id = isset($instance[$homeland_dribbble_id]) ? esc_attr($instance[$homeland_dribbble_id]) : '';
				$homeland_dribbble_limit = 'homeland_dribbble_limit';
				$instance_homeland_dribbble_limit = isset($instance[$homeland_dribbble_limit]) ? esc_attr($instance[$homeland_dribbble_limit]) : '';
							
				echo ''.$before_widget.'';					
					if ($title) {						
						echo $before_title;
						echo $title;
						echo $after_title.'';						
					}

				?>	

				<!--DRIBBBLE FEED-->
				<ul id="dribbble" class="clear"></ul>

				<script type="text/javascript">
					(function($) {
					  	"use strict";
					  	$(document).ready(function () {		
							$.jribbble.getShotsByPlayerId( '<?php echo $instance_homeland_dribbble_id; ?>', function (playerShots) {
							    var html = [];
							
							    $.each(playerShots.shots, function (i, shot) {
							        html.push('<li><a href="' + shot.url + '" target="_blank">');
							        html.push('<img src="' + shot.image_teaser_url + '" ');
							        html.push('alt="' + shot.title + '"></a></li>');
							    });
							
							    $('#dribbble').html(html.join(''));
							}, {page: 1, per_page: <?php echo $instance_homeland_dribbble_limit; ?> });

						});
					})(jQuery);					
				</script>

				<?php
					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['homeland_dribbble_id'] = strip_tags($new_instance['homeland_dribbble_id']);
					$instance['homeland_dribbble_limit'] = strip_tags($new_instance['homeland_dribbble_limit']);				
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
					$instance_homeland_dribbble_id = array();
					$instance_homeland_dribbble_limit = array();							
					
					$homeland_dribbble_id = 'homeland_dribbble_id';
					$instance_homeland_dribbble_id = isset($instance[$homeland_dribbble_id]) ? esc_attr($instance[$homeland_dribbble_id]) : '';
					$homeland_dribbble_limit = 'homeland_dribbble_limit';
					$instance_homeland_dribbble_limit = isset($instance[$homeland_dribbble_limit]) ? esc_attr($instance[$homeland_dribbble_limit]) : '';	
					
				?>
					<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
					<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>					
					<div>			
						<div>
							<p><label for="<?php echo $this -> get_field_id($homeland_dribbble_id); ?>"><?php _e('User ID', 'codeex_theme_name'); ?></label>
							<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_dribbble_id); ?>" name="<?php echo $this -> get_field_name($homeland_dribbble_id); ?>" value="<?php echo $instance_homeland_dribbble_id; ?>">		
							</p>
							<p><label for="<?php echo $this -> get_field_id($homeland_dribbble_limit); ?>"><?php _e('Limit', 'codeex_theme_name'); ?></label>
							<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_dribbble_limit); ?>" name="<?php echo $this -> get_field_name($homeland_dribbble_limit); ?>" value="<?php echo $instance_homeland_dribbble_limit; ?>">
							</p>														
						</div>			
					</div>
		<?php
				}			
		}

		function homeland_Widget_dribbble() {			
			register_widget('homeland_Widget_Dribbble');			
		}
		add_action('widgets_init', 'homeland_Widget_dribbble');
?>