<?php
/*
Template Name: Agents - Left Sidebar
*/
?>

<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--AGENT LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<!--LEFT CONTAINER-->			
			<div class="left-container right">
				<?php
					//displays page contents
					if (have_posts()) : 
						while (have_posts()) : the_post(); 
							the_content(); 								
						endwhile; 
					endif;
				?>
					
				<div class="agent-container">
					<?php
						$homeland_agent_button = esc_attr( get_option('homeland_agent_button') );
						$homeland_agent_page_order = esc_attr( get_option('homeland_agent_page_order') );
						$homeland_agent_page_orderby = esc_attr( get_option('homeland_agent_page_orderby') );
						$homeland_agent_page_limit = esc_attr( get_option('homeland_agent_page_limit') );
						$homeland_page_nav = esc_attr( get_option('homeland_pnav') );

						$homeland_agent_button_label = !empty($homeland_agent_button) ? $homeland_agent_button : __('View Profile', 'codeex_theme_name'); 

						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						if($paged==1) : $offset=0;  
						else : $offset= ($paged-1)*$homeland_agent_page_limit;
						endif;

						$args = array( 
							'role' => 'contributor', 
							'order' => $homeland_agent_page_order, 
							'orderby' => $homeland_agent_page_orderby,
							'number' => $homeland_agent_page_limit, 
							'offset' => $offset
						);

					   $homeland_agents = new WP_User_Query( $args );	

					   if (!empty( $homeland_agents->results)) :
						   foreach ($homeland_agents->results as $homeland_user) :
						    	global $wpdb;
								$homeland_post_author = $homeland_user->ID;
								$homeland_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'homeland_properties' AND post_status = 'publish' AND post_author = %d", $homeland_post_author ) );

								$homeland_agent_id = $homeland_user->ID;
						   	$homeland_agent_name = $homeland_user->display_name;
						   	$homeland_agent_desc = $homeland_user->user_description;
								$homeland_agent_facebook = $homeland_user->homeland_facebook;
						   	$homeland_agent_gplus = $homeland_user->homeland_gplus;
						   	$homeland_agent_linkedin = $homeland_user->homeland_linkedin;
						   	$homeland_agent_twitter = $homeland_user->homeland_twitter;
						   	$homeland_agent_email = $homeland_user->user_email;
						   	$homeland_agent_telno = $homeland_user->homeland_telno;
								$homeland_agent_mobile = $homeland_user->homeland_mobile;
								$homeland_agent_fax = $homeland_user->homeland_fax;
								$homeland_custom_avatar = get_the_author_meta('homeland_custom_avatar', $homeland_agent_id);
								$homeland_agent_desc_trim = wp_trim_words( $homeland_agent_desc, $homeland_num_words = 60, $homeland_more = null );
						      
						      ?>
						    	<div class="agent-list clear">

						    		<!--AGENT IMAGE and SOCIAL-->
						    		<div class="agent-image">
						    			<div class="grid cs-style-3">	
						    				<figure class="pimage">
						    					<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>">
							    					<?php
							    						if(!empty($homeland_custom_avatar)) : echo '<img src="'.$homeland_custom_avatar.'" />';
							    						else : echo get_avatar( $homeland_agent_id, 230 );
							    						endif;
							    					?>
						    					</a>
						    					<figcaption>
						    						<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>">
						    							<i class="fa fa-link fa-lg"></i>
						    						</a>
						    					</figcaption>
						    				</figure>
						    			</div>
						    			<div class="agent-social">
						    				<ul class="clear">
						    					<?php
						    						if(!empty($homeland_agent_facebook)) : 
						    							echo '<li><a href="'. $homeland_agent_facebook .'" target="_blank">
						    									<i class="fa fa-facebook fa-lg"></i></a></li>';
						    						endif;

						    						if(!empty($homeland_agent_gplus)) : 
						    							echo '<li><a href="'. $homeland_agent_gplus .'" target="_blank">
						    									<i class="fa fa-google-plus fa-lg"></i></a></li>';
						    						endif;

						    						if(!empty($homeland_agent_linkedin)) : 
						    							echo '<li><a href="'. $homeland_agent_linkedin .'" target="_blank">
						    									<i class="fa fa-linkedin fa-lg"></i></a></li>';
						    						endif;

						    						if(!empty($homeland_agent_twitter)) : 
						    							echo '<li><a href="'. $homeland_agent_twitter .'" target="_blank">
						    									<i class="fa fa-twitter fa-lg"></i></a></li>';
						    						endif;

						    						if(!empty($homeland_agent_email)) : 
						    							echo '<li class="last">
						    									<a href="mailto:'. $homeland_agent_email .'" target="_blank">
						    									<i class="fa fa-envelope-o fa-lg"></i></a></li>';
						    						endif; 
						    					?>
						    				</ul>
						    			</div>
						    		</div>

						    		<!--AGENT DESCRIPTIONS-->
						    		<div class="agent-desc">
						    			<h4>
						    				<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>">
						    					<?php echo $homeland_agent_name; ?>
						    				</a>
						    			</h4>
						    			<?php echo wpautop( $homeland_agent_desc_trim ); ?>
						    			<label class="more-info">
						    				<?php
						    					if(!empty($homeland_agent_telno)) : ?>
						    						<span>
						    							<i class="fa fa-phone"></i>
						    							<strong><?php esc_attr( _e( 'Tel no', 'codeex_theme_name' ) ); echo ":"; ?></strong>
						    							<?php echo $homeland_agent_telno; ?>
						    						</span><?php
						    					endif;

						    					if(!empty($homeland_agent_mobile)) : ?>
						    						<span>
						    							<i class="fa fa-mobile"></i>
						    							<strong><?php esc_attr( _e( 'Mobile', 'codeex_theme_name' ) ); echo ":"; ?></strong>
						    							<?php echo $homeland_agent_mobile; ?>
						    						</span><?php
						    					endif;

						    					if(!empty($homeland_agent_fax)) : ?>
						    						<span>
						    							<i class="fa fa-print"></i>
						    							<strong><?php esc_attr( _e( 'Fax', 'codeex_theme_name' ) ); echo ":"; ?></strong> 
						    							<?php echo $homeland_agent_fax; ?>
						    						</span><?php
						    					endif;
			 			    				?>
						    			</label>
							    		<label class="listed">
							    			<i class="fa fa-home fa-lg"></i> <?php esc_attr( _e( 'Listed', 'codeex_theme_name' ) ); echo ":"; ?>
							    			<span><?php echo intval($homeland_count); echo "&nbsp;"; esc_attr( _e( 'Properties', 'codeex_theme_name' ) ); ?></span>
							    		</label>
							    		<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>" class="view-profile">
							    			<?php echo $homeland_agent_button_label; ?>
							    		</a>
						    		</div>
						    	</div>	
						    	<?php
						   endforeach;
						else : _e( 'No Agents found!', 'codeex_theme_name' );
						endif;
					?>
				</div>

				<?php 	
					$homeland_total_user = $homeland_agents->total_users;  
	            $homeland_max_num_pages = ceil($homeland_total_user/$homeland_agent_page_limit);

					if( $homeland_page_nav == "Next Previous Link") : 
						homeland_next_previous(); //modify function in "functions.php"... 
					else : homeland_pagination(); //modify function in "functions.php"...
					endif; 
				?>
			</div>

			<!--SIDEBAR-->	
			<div class="sidebar left"><?php get_sidebar(); ?></div>

		</div>
	</section>

<?php get_footer(); ?>