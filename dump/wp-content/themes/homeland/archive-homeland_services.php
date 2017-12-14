<?php get_header(); ?>
	
	<?php homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"... ?>

	<!--AGENT LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<?php
				$homeland_services_archive_layout = esc_attr( get_option('homeland_services_archive_layout') );
				$homeland_services_order = esc_attr( get_option('homeland_services_order') );
				$homeland_services_orderby = esc_attr( get_option('homeland_services_orderby') );
				$homeland_num_services = esc_attr( get_option('homeland_num_services') );
				$homeland_page_nav = esc_attr( get_option('homeland_pnav') );

				if($homeland_services_archive_layout == "Left Sidebar") :
					$homeland_services_archive_class = "left-container right";
					$homeland_services_archive_sidebar_class = "sidebar left";
				elseif($homeland_services_archive_layout == "Fullwidth") :
					$homeland_services_archive_class = "theme-fullwidth";
				elseif($homeland_services_archive_layout == "Grid Fullwidth") :
					$homeland_services_archive_class = "theme-fullwidth services-grid-fullwidth clear";
				else :
					$homeland_services_archive_class = "left-container";
					$homeland_services_archive_sidebar_class = "sidebar";
				endif;
			?>

			<!--LEFT CONTAINER-->			
			<div class="<?php echo $homeland_services_archive_class; ?>">

				<div class="services-container">
					<?php
						$args_wp = array( 
							'post_type' => 'homeland_services', 
							'orderby' => $homeland_services_orderby, 
							'order' => $homeland_services_order, 
							'posts_per_page' => $homeland_num_services, 
							'paged' => $paged 
						);
						$wp_query = new WP_Query( $args_wp );

						if ($wp_query->have_posts()) : 
							while ( $wp_query->have_posts() ) : $wp_query->the_post();												
								get_template_part( 'loop', 'services' );
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
			<?php
				if($homeland_services_archive_layout != "Fullwidth" && $homeland_services_archive_layout != "Grid Fullwidth") : ?>
					<div class="<?php echo $homeland_services_archive_sidebar_class; ?>">
						<?php get_sidebar(); ?>
					</div><?php
				endif;
			?>

		</div>

	</section>

<?php get_footer(); ?>