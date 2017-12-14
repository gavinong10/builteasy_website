<?php
	/**********************************************
	CUSTOM FLICKR FEED WIDGET
	***********************************************/
	
	class homeland_Widget_Flickr extends WP_Widget {
	
		function homeland_Widget_Flickr() {		
			$widget_ops = array('classname' => 'homeland_widget-flickr', 'description' => __('Custom widget for flickr feed', 'codeex_theme_name'));	
			parent::__construct('Flickr', __('Homeland: Flickr Feed', 'codeex_theme_name'), $widget_ops);		
		}
	
		function widget($args, $instance) {		
			extract($args);		
			$title = apply_filters('widget_title', $instance['title']);		
			if (empty($title)) $title = false;
				$instance_homeland_flickr_id = array();
				$instance_homeland_flickr_limit = array();
				
				$homeland_flickr_id = 'homeland_flickr_id';
				$instance_homeland_flickr_id = isset($instance[$homeland_flickr_id]) ? esc_attr($instance[$homeland_flickr_id]) : '';
				$homeland_flickr_limit = 'homeland_flickr_limit';
				$instance_homeland_flickr_limit = isset($instance[$homeland_flickr_limit]) ? esc_attr($instance[$homeland_flickr_limit]) : '';
							
				echo ''.$before_widget.'';					
				if ($title) {						
					echo $before_title;
			 		echo $title;
			 		echo $after_title.'';						
			 	}

				?>
				<ul id="fbox" class="clear">
					<?php
						function attr($s,$attrname) { // return html attribute
							preg_match_all('#\s*('.$attrname.')\s*=\s*["|\']([^"\']*)["|\']\s*#i', $s, $x);
							if (count($x)>=3) return $x[2][0]; else return "";
						}

						function parseFlickrFeed($id,$n) {
							$url = "http://api.flickr.com/services/feeds/photos_public.gne?id={$id}&lang=it-it&format=rss_200";
							$s = wp_remote_fopen($url);
							preg_match_all('#<item>(.*)</item>#Us', $s, $items);
							$out = "";
							for($i=0;$i<count($items[1]);$i++) {
								if($i>=$n) return $out;
								$item = $items[1][$i];
								preg_match_all('#<link>(.*)</link>#Us', $item, $temp);
								$link = $temp[1][0];
								preg_match_all('#<title>(.*)</title>#Us', $item, $temp);
								$title = $temp[1][0];
								preg_match_all('#<media:thumbnail([^>]*)>#Us', $item, $temp);
								$thumb = attr($temp[0][0],"url");
								$out.="<li><a href='$link' onclick='window.open(this.href); return false;' title=\"".str_replace('"','',$title)."\"><img src='$thumb' alt='' title='' /></a></li>";
							}
							return $out;
						}

						echo parseFlickrFeed("$instance_homeland_flickr_id", $instance_homeland_flickr_limit);
					?>
				</ul>					
				<?php
					echo $after_widget.'';				
				}
			
				function update($new_instance, $old_instance) {				
					$instance = $old_instance;				
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['homeland_flickr_id'] = strip_tags($new_instance['homeland_flickr_id']);
					$instance['homeland_flickr_limit'] = strip_tags($new_instance['homeland_flickr_limit']);				
					return $instance;				
				}
			
				function form($instance) {				
					$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
					$instance_homeland_flickr_id = array();
					$instance_homeland_flickr_limit = array();							
					
					$homeland_flickr_id = 'homeland_flickr_id';
					$instance_homeland_flickr_id = isset($instance[$homeland_flickr_id]) ? esc_attr($instance[$homeland_flickr_id]) : '';
					$homeland_flickr_limit = 'homeland_flickr_limit';
					$instance_homeland_flickr_limit = isset($instance[$homeland_flickr_limit]) ? esc_attr($instance[$homeland_flickr_limit]) : '';					
					
				?>
					<p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title', 'codeex_theme_name'); ?></label>
					<input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>					
					<div>			
						<div>
							<p><label for="<?php echo $this -> get_field_id($homeland_flickr_id); ?>"><?php _e('ID', 'codeex_theme_name'); ?></label>
							<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_flickr_id); ?>" name="<?php echo $this -> get_field_name($homeland_flickr_id); ?>" value="<?php echo $instance_homeland_flickr_id; ?>">		
							</p>
							<p><label for="<?php echo $this -> get_field_id($homeland_flickr_limit); ?>"><?php _e('Number of Images', 'codeex_theme_name'); ?></label>
							<input class="widefat" type="text" id="<?php echo $this -> get_field_id($homeland_flickr_limit); ?>" name="<?php echo $this -> get_field_name($homeland_flickr_limit); ?>" value="<?php echo $instance_homeland_flickr_limit; ?>">
							</p>														
						</div>			
					</div>
		<?php
				}			
		}

		function homeland_widget_flickr() {			
			register_widget('homeland_Widget_Flickr');			
		}
		add_action('widgets_init', 'homeland_widget_flickr');
?>