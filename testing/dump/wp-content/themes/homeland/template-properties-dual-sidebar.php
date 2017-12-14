<?php
/*
	Template Name: Properties - Dual Sidebar
*/
?>

<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--PROPERTY LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<!--SIDEBAR-->	
			<div class="sidebar sidebar-dual"><?php get_sidebar('dual'); ?></div>

			<!--LEFT CONTAINER-->			
			<div class="left-container dual-container">		

				<?php homeland_property_sort_order(); //modify function in "functions.php"... ?>

				<div class="agent-properties property-list dual-property-list clear">
					<?php
						homeland_get_home_pagination(); //modify function in "functions.php"...
						
						$homeland_hide_map_list = esc_attr( get_option('homeland_hide_map_list') );
						$homeland_num_properties = esc_attr( get_option('homeland_num_properties') );
						$homeland_page_nav = esc_attr( get_option('homeland_pnav') );
						$homeland_filter_default = get_option('homeland_filter_default');

						//displays page contents
						if (have_posts()) : 
							while (have_posts()) : the_post(); 
								the_content(); 								
							endwhile; 
						endif;
						
						//displays property map
						if(empty($homeland_hide_map_list)) :
							?><section id="map-homepage"></section><?php
						endif;

						if($homeland_filter_default == "Price") :
							$args_wp = array( 
								'post_type' => 'homeland_properties', 
								'meta_key' => 'homeland_price',
								'orderby' => 'meta_value_num', 
								'order' => 'DESC', 
								'posts_per_page' => $homeland_num_properties, 
								'paged' => $paged
							);
						elseif($homeland_filter_default == "Name") :
							$args_wp = array( 
								'post_type' => 'homeland_properties', 
								'orderby' => 'title', 
								'order' => 'DESC', 
								'posts_per_page' => $homeland_num_properties, 
								'paged' => $paged
							);
						else :
							$args_wp = array( 
								'post_type' => 'homeland_properties', 
								'orderby' => 'date', 
								'order' => 'DESC', 
								'posts_per_page' => $homeland_num_properties, 
								'paged' => $paged
							);
						endif;
						
						$args = apply_filters('homeland_properties_parameters', $args_wp);
						$wp_query = new WP_Query( $args );	

						if ( $wp_query->have_posts() ) : 
							?>	
								<!--Search Count-->
								<div class="search-count">
	            				<?php echo $wp_query->found_posts . "&nbsp;"; _e( 'Properties found...', 'codeex_theme_name' ); ?>
	            			</div>

	            			<!--Property List-->
								<div class="grid cs-style-3">
									<ul class="clear">
										<?php
											while ( $wp_query->have_posts() ) : 
												$wp_query->the_post(); 
												get_template_part( 'loop', 'property-1col' );
									    	endwhile; 
									    ?>
						    		</ul>
						    	</div>
					    	<?php
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