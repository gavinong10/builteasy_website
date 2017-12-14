<?php
/*
	Template Name: About
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
			<div class="left-container">
				<?php
					$homeland_team_header = esc_attr( get_option('homeland_team_header') );
					$homeland_agent_order = esc_attr( get_option('homeland_agent_order') );
					$homeland_agent_orderby = esc_attr( get_option('homeland_agent_orderby') );
					$homeland_all_agents = esc_attr( get_option('homeland_all_agents') );
					$homeland_team_header_label = !empty( $homeland_team_header ) ? $homeland_team_header : __( 'Our Team', 'codeex_theme_name' );

					if(empty($homeland_all_agents)) : ?>
						<!--AGENT LIST-->
						<div class="agent-about-list clear">
							<h3><?php echo $homeland_team_header_label; ?></h3>
							<?php
								$args = array( 'role' => 'contributor', 'order' => $homeland_agent_order, 'orderby' => $homeland_agent_orderby );
							   $homeland_agents = new WP_User_Query( $args );		   

							   if (!empty( $homeland_agents->results)) :
								   foreach ($homeland_agents->results as $homeland_user) :

								   	$homeland_agent_id = $homeland_user->ID;
								   	$homeland_agent_name = $homeland_user->display_name;
								   	$homeland_agent_designation = $homeland_user->homeland_designation;
								   	$homeland_agent_facebook = $homeland_user->homeland_facebook;
								   	$homeland_agent_gplus = $homeland_user->homeland_gplus;
								   	$homeland_agent_linkedin = $homeland_user->homeland_linkedin;
								   	$homeland_agent_twitter = $homeland_user->homeland_twitter;
								   	$homeland_agent_email = $homeland_user->user_email;
								   	$homeland_custom_avatar = get_the_author_meta('homeland_custom_avatar', $homeland_agent_id);
								    	?>
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
								    							echo '<li><a href="'. $homeland_agent_facebook .'" target="_blank"><i class="fa fa-facebook fa-lg"></i></a></li>';
								    						endif;

								    						if(!empty($homeland_agent_gplus)) : 
								    							echo '<li><a href="'. $homeland_agent_gplus .'" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a></li>';
								    						endif;

								    						if(!empty($homeland_agent_linkedin)) : 
								    							echo '<li><a href="'. $homeland_agent_linkedin .'" target="_blank"><i class="fa fa-linkedin fa-lg"></i></a></li>';
								    						endif;

								    						if(!empty($homeland_agent_twitter)) : 
								    							echo '<li><a href="'. $homeland_agent_twitter .'" target="_blank"><i class="fa fa-twitter fa-lg"></i></a></li>';
								    						endif;

								    						if(!empty($homeland_agent_email)) : 
								    							echo '<li class="last">
								    									<a href="mailto:'. $homeland_agent_email .'" target="_blank"><i class="fa fa-envelope-o fa-lg"></i></a></li>';
								    						endif; 
								    					?>
								    				</ul>
								    			</div>
								    			<h4>
								    				<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>">
								    					<?php echo $homeland_agent_name; ?>
								    				</a>
								    			</h4>
								    			<h5><?php echo $homeland_agent_designation; ?></h5>
								    		</div>
								    	<?php
								   endforeach;
								else : _e( 'No Agents found!', 'codeex_theme_name' );
								endif;
							?>
						</div><?php
					endif;
				
					if (have_posts()) : 
						while (have_posts()) : the_post(); 
							the_content(); 								
						endwhile; 
					endif;
				?>
					
			</div>

			<!--SIDEBAR-->	
			<div class="sidebar"><?php get_sidebar(); ?></div>

		</div>

	</section>

<?php get_footer(); ?>