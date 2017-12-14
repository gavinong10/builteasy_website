<?php get_header(); ?>
	
	<?php homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"... ?>

	<!--BLOG LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<?php
				$homeland_archive_layout = esc_attr( get_option('homeland_archive_layout') );
				$homeland_page_nav = esc_attr( get_option('homeland_pnav') );
				$homeland_term_description = term_description();

				if($homeland_archive_layout == "Timeline") :
					$homeland_archive_class_main = "theme-fullwidth";
					$homeland_archive_class_container = "blog-list blog-timeline clear";
				elseif($homeland_archive_layout == "Fullwidth") :
					$homeland_archive_class_main = "theme-fullwidth";
					$homeland_archive_class_container = "blog-list clear";
				elseif($homeland_archive_layout == "2 Columns" || $homeland_archive_layout == "3 Columns" || $homeland_archive_layout == "4 Columns") :
					$homeland_archive_class_main = "theme-fullwidth";
					$homeland_archive_class_container = "blog-fullwidth masonry clear";
				elseif($homeland_archive_layout == "Grid") :
					$homeland_archive_class_main = "left-container";
					$homeland_archive_class_container = "blog-grid masonry clear";
					$homeland_archive_class_sidebar = "sidebar";
				elseif($homeland_archive_layout == "Grid Left Sidebar") :
					$homeland_archive_class_main = "left-container right";
					$homeland_archive_class_container = "blog-grid masonry clear";
					$homeland_archive_class_sidebar = "sidebar left";
				elseif($homeland_archive_layout == "Left Sidebar") :
					$homeland_archive_class_main = "left-container right";
					$homeland_archive_class_container = "blog-list clear";
					$homeland_archive_class_sidebar = "sidebar left";
				else :
					$homeland_archive_class_main = "left-container";
					$homeland_archive_class_container = "blog-list clear";
					$homeland_archive_class_sidebar = "sidebar";
				endif;
			?>

			<div class="<?php echo $homeland_archive_class_main; ?>">	
				<?php if(!empty( $homeland_term_description )) : echo $homeland_term_description; endif; ?>
				<div class="<?php echo $homeland_archive_class_container; ?>">
					<?php
						if ($wp_query->have_posts()) : 
							if($homeland_archive_layout == "Timeline") :
								while ($wp_query->have_posts()) : $wp_query->the_post(); 				
									get_template_part( 'loop', 'entry-timeline' );	
								endwhile;	
							elseif($homeland_archive_layout == "Fullwidth") :
								while ($wp_query->have_posts()) : $wp_query->the_post(); 		
									get_template_part( 'loop', 'entry-fullwidth' );	
								endwhile;	
							elseif($homeland_archive_layout == "2 Columns") :
								for($homeland_i = 1; have_posts(); $homeland_i++) {
									the_post();			
									$homeland_columns = 2;	
									$homeland_class = 'blist-two-cols masonry-item ';
									$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';	

									get_template_part( 'loop', 'entry-2cols' );								
								}		
							elseif($homeland_archive_layout == "3 Columns") :
								for($homeland_i = 1; have_posts(); $homeland_i++) {
									the_post();			
									$homeland_columns = 3;	
									$homeland_class = 'blist-fullwidth masonry-item ';
									$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';	

									get_template_part( 'loop', 'entry-3cols' );								
								}
							elseif($homeland_archive_layout == "4 Columns") :
								for($homeland_i = 1; have_posts(); $homeland_i++) {
									the_post();			
									$homeland_columns = 4;	
									$homeland_class = 'blist-fullwidth blog-four-cols masonry-item ';
									$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';	

									get_template_part( 'loop', 'entry-4cols' );								
								}
							elseif($homeland_archive_layout == "Grid" || $homeland_archive_layout == "Grid Left Sidebar") :
								for($homeland_i = 1; have_posts(); $homeland_i++) {
									the_post();			
									$homeland_columns = 3;	
									$homeland_class = 'blist-grid masonry-item ';
									$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';	

									get_template_part( 'loop', 'entry-grid' );								
								}
							else :
								while (have_posts()) : the_post(); 					
									get_template_part( 'loop', 'entry' );								
								endwhile;
							endif;
						else :
							_e( 'You have no blog post yet!', 'codeex_theme_name' );
						endif;		
					?>
				</div>
				<?php
					if($homeland_page_nav == "Next Previous Link") : 
						homeland_next_previous(); //modify function in "functions.php"...
					else : homeland_pagination(); //modify function in "functions.php"... 
					endif; 
				?>
			</div>

			<?php
				//Sidebar
				if($homeland_archive_layout != "Timeline" && $homeland_archive_layout != "Fullwidth" && $homeland_archive_layout != "2 Columns" && $homeland_archive_layout != "3 Columns" && $homeland_archive_layout != "4 Columns") : ?>
						<div class="<?php echo $homeland_archive_class_sidebar; ?>">
							<?php get_sidebar(); ?>
						</div><?php
				endif;
			?>

		</div>
	</section>

<?php get_footer(); ?>