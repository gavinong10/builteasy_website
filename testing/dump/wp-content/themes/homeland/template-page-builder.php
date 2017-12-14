<?php
/*
	Template Name: Page Builder
*/
?>

<?php get_header(); ?>
	
	<?php
		if (have_posts()) : 
			while (have_posts()) : the_post(); 
				the_content(); 								
			endwhile; 
		endif;
	?>

<?php get_footer(); ?>