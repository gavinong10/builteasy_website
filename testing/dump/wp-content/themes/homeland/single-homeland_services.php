<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--SERVICES LIST-->
	<section class="theme-pages">

		<div class="inside clear">
			<?php
				$homeland_services_single_layout = esc_attr( get_option('homeland_services_single_layout') );

				if($homeland_services_single_layout == "Fullwidth") :
					$homeland_services_class_main = "theme-fullwidth";
				elseif($homeland_services_single_layout == "Left Sidebar") :
					$homeland_services_class_main = "left-container right";
					$homeland_services_class_sidebar = "sidebar left";
				else :
					$homeland_services_class_main = "left-container";
					$homeland_services_class_sidebar = "sidebar";
				endif;
			?>		
			<div class="<?php echo $homeland_services_class_main; ?>">	
				<div class="services-container">
					<?php
						if (have_posts()) : 
							while ( have_posts() ) : the_post(); 	
								$homeland_icon = esc_html( get_post_meta( $post->ID, "homeland_icon", true ) );
								$homeland_custom_icon = esc_html( get_post_meta( $post->ID, "homeland_custom_icon", true ) );	
								?>
									<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('clear') ); ?>>
										<div class="services-page-icon">
											<?php
												if(!empty($homeland_icon)) : ?><i class="fa <?php echo $homeland_icon; ?> fa-4x"></i><?php
												else : ?><img src="<?php echo $homeland_custom_icon; ?>" alt="" title="" /><?php
												endif;
											?>
										</div>						
										<div class="services-page-desc"><?php the_content(); ?></div>
									</div>
								<?php							
							endwhile;	
						endif;
					?>
				</div>
			</div>

			<?php
				//Sidebar
				if($homeland_services_single_layout != "Fullwidth") : 
					?><div class="<?php echo $homeland_services_class_sidebar; ?>"><?php get_sidebar(); ?></div><?php
				endif;
			?>

		</div>
	</section>

<?php get_footer(); ?>