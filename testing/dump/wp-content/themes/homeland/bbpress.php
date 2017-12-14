<?php get_header(); ?>
	
	<!--AGENT LIST-->
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
					
			</div>

			<!--SIDEBAR-->	
			<div class="sidebar"><?php get_sidebar(); ?></div>

		</div>

	</section>

<?php get_footer(); ?>