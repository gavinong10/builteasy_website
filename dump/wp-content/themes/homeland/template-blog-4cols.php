<?php
/*
	Template Name: Blog - 4 Columns
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

			<!--FULLWIDTH-->			
			<div class="theme-fullwidth">		
				<?php
					//displays page contents
					if (have_posts()) : 
						while (have_posts()) : the_post(); 
							the_content(); 								
						endwhile; 
					endif;
				?>
					
				<div class="blog-fullwidth masonry clear">
					<?php
						homeland_get_home_pagination(); //modify function in "functions.php"...
						$homeland_page_nav = esc_attr( get_option('homeland_pnav') );

						$args = array( 'post_type' => 'post', 'paged' => $paged );		
						$wp_query = new WP_Query( $args );	

						if ($wp_query->have_posts()) : 
							for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
								$wp_query->the_post();			
								$homeland_columns = 4;	
								$homeland_class = 'blist-fullwidth blog-four-cols masonry-item ';
								$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';	

								get_template_part( 'loop', 'entry-4cols' );								
							}
						else :
							_e( 'You have no blog post yet!', 'codeex_theme_name' );
						endif;					
					?>
				</div>
				<?php 
					if( $homeland_page_nav == "Next Previous Link" ) : 
						homeland_next_previous(); //modify function in "functions.php"...
					else : homeland_pagination(); //modify function in "functions.php"... 
					endif; 

					wp_reset_query();
				?>
			</div>

		</div>

	</section>

<?php get_footer(); ?>