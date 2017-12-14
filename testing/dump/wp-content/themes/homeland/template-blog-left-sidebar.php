<?php
/*
	Template Name: Blog List - Left Sidebar
*/
?>

<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--BLOG LIST-->
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
						
				<div class="blog-list clear">
					<?php
						homeland_get_home_pagination(); //modify function in "functions.php"...
						$homeland_page_nav = esc_attr( get_option('homeland_pnav') );

						$args = array( 'post_type' => 'post', 'paged' => $paged );		
						$wp_query = new WP_Query( $args );	

						if ($wp_query->have_posts()) : 
							while ($wp_query->have_posts()) : $wp_query->the_post(); 					
								get_template_part( 'loop', 'entry' );								
							endwhile;	
						else :
							_e( 'You have no blog post yet!', 'codeex_theme_name' );
						endif;					
					?>
				</div>
				<?php 
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