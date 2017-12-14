<?php
	/**********************************************
	CUSTOM AGENT WIDGET
	***********************************************/
	
	class homeland_Widget_Agents extends WP_Widget {
	
		function homeland_Widget_Agents() {		
			$widget_ops = array('classname' => 'homeland_widget-agents', 'description' => __('Custom widget for agents', 'codeex_theme_name'));	
			parent::__construct('Agents', __('Homeland: Agents', 'codeex_theme_name'), $widget_ops);		
		}
	
		function widget($args, $instance) {		
			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;					
				$instance_homeland_posts_limit = array();
				
				$homeland_posts_limit = 'homeland_posts_limit';
				$instance_homeland_posts_limit = isset($instance[$homeland_posts_limit]) ? esc_attr($instance[$homeland_posts_limit]) : '';

				echo $before_widget;	
				if ($title) {						
					echo $before_title;
					echo $title;
					echo $after_title;						
				}				

				?>	

				<!--AGENTS-->
				<ul>
					<?php
						$homeland_agent_order = esc_attr( get_option('homeland_agent_order') );
						$homeland_agent_orderby = esc_attr( get_option('homeland_agent_orderby') );

						$args = array( 
							'role' => 'contributor', 
							'order' => $homeland_agent_order, 
							'orderby' => $homeland_agent_orderby, 
							'number' => $instance_homeland_posts_limit 
						);
					   $homeland_agents = new WP_User_Query( $args );

					   if (!empty( $homeland_agents->results)) :
							foreach ($homeland_agents->results as $homeland_user) :
						    	global $wpdb;

								$homeland_post_author = $homeland_user->ID;
								$homeland_custom_avatar = get_the_author_meta('homeland_custom_avatar', $homeland_post_author);
								
								$homeland_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'homeland_properties' AND post_status = 'publish' AND post_author = %d", $homeland_post_author ) );
						        ?>
						    	<li class="clear">
						    		<a href="<?php echo esc_url( get_author_posts_url( $homeland_post_author ) ); ?>">
						    			<?php 
			    							if(!empty($homeland_custom_avatar)) : 
			    								echo '<img src="'.$homeland_custom_avatar.'" class="avatar" style="width:60px; height:60px;" />';
				    						else : echo get_avatar( $homeland_post_author, 60 );
				    						endif;
			    						?>
						    		</a>
						    		<h4>
						    			<a href="<?php echo esc_url( get_author_posts_url( $homeland_post_author ) ); ?>">
						    				<?php echo $homeland_user->display_name; ?>
						    			</a>
						    		</h4>
						    		<label>
						    			<i class="fa fa-home fa-lg"></i> <?php esc_attr( _e( 'Listed:', 'codeex_theme_name' ) ); ?>
						    			<span><?php echo intval($homeland_count); echo "&nbsp;"; esc_attr( _e( 'Properties', 'codeex_theme_name' ) ); ?></span>
						    		</label>
						    	</li>	
						    	<?php
						   endforeach;
						else : _e( 'No Agents found!', 'codeex_theme_name' );
						endif;
					?>
				</ul>

				<?php
					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['homeland_posts_limit'] = strip_tags($new_instance['homeland_posts_limit']);			
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
					$instance_homeland_posts_limit = array();
					
					$homeland_posts_limit = 'homeland_posts_limit';
					$instance_homeland_posts_limit = isset($instance[$homeland_posts_limit]) ? esc_attr($instance[$homeland_posts_limit]) : '';										
					
				?>
					<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
					<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
					<div>			
						<div>
							<p><label for="<?php echo $this -> get_field_id($homeland_posts_limit); ?>"><?php _e('Limit', 'codeex_theme_name'); ?></label>
							<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_posts_limit); ?>" name="<?php echo $this -> get_field_name($homeland_posts_limit); ?>" value="<?php echo $instance_homeland_posts_limit; ?>">		
							</p>											
						</div>	
						<p><small><i><?php _e( 'Top Agents List will display automatically base on the number of properties they listed...', 'codeex_theme_name' ); ?></i></small></p>		
					</div>
		<?php
				}			
		}

		function homeland_widgets_agents() {			
			register_widget('homeland_Widget_Agents');			
		}
		add_action('widgets_init', 'homeland_widgets_agents');
?>