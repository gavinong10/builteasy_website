<?php
/*
Template Name: Testimonials
*/
?>

<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--TESTIMONIALS LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<!--LEFT CONTAINER-->			
			<div class="left-container">

				<?php
					if (have_posts()) : 
						while (have_posts()) : the_post(); 
							the_content(); 								
						endwhile; 
					endif;
				?>

				<div class="services-container">
					<?php
						$homeland_page_nav = esc_attr( get_option('homeland_pnav') );

						$args = array( 
							'post_type' => 'homeland_testimonial', 
							'posts_per_page' => 10, 
							'paged' => $paged 
						);
						$wp_query = new WP_Query( $args );	

						if ($wp_query->have_posts()) : 
							while ( $wp_query->have_posts() ) : $wp_query->the_post();		
								$homeland_position = esc_attr( get_post_meta( $post->ID, 'homeland_position', true ) );
								?>
									<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('testi-page-list clear') ); ?>>
										<?php if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_theme_thumb'); endif; ?>	
										<div class="testi-desc">
											<?php 
												the_title( '<h4>', '</h4>' ); 
												echo "<h5>" . $homeland_position . "</h5>";
												the_content();
											?>
										</div>
									</div>	
								<?php
							endwhile;	
						endif;
					?>
				</div>
				<?php 
					if( $homeland_page_nav == "Next Previous Link" ) : 
						homeland_next_previous(); //modify function in "functions.php"...
					else : homeland_pagination(); //modify function in "functions.php"... 
					endif; 
				?>
			</div>

			<!--SIDEBAR-->	
			<div class="sidebar"><?php get_sidebar(); ?></div>

		</div>

	</section>

<?php get_footer(); ?>