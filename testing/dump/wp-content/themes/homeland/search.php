<?php get_header(); ?>
	
	<?php homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"... ?>

	<!--BLOG LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<!--LEFT CONTAINER-->			
			<div class="left-container">				
				<div class="blog-list clear">
					<?php
						$homeland_page_nav = esc_attr( get_option('homeland_pnav') );

						if (have_posts()) : 
							while (have_posts()) : the_post(); 					
								get_template_part( 'loop', 'entry' );								
							endwhile;	
						else :
							_e( 'The Keyword you search cannot be found!', 'codeex_theme_name' );
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