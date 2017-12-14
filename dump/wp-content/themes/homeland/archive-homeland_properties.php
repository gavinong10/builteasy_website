<?php get_header(); ?>
	
	<?php homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"... ?>

	<!--PROPERTY LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<?php
				$homeland_num_properties = esc_attr( get_option('homeland_num_properties') );
				$homeland_tax_layout = esc_attr( get_option('homeland_tax_layout') );
				$homeland_hide_map_list = esc_attr( get_option('homeland_hide_map_list') );
				$homeland_page_nav = esc_attr( get_option('homeland_pnav') );
				$homeland_filter_default = esc_attr( get_option('homeland_filter_default') );
				$homeland_term_description = term_description();

				if($homeland_filter_default == "Price") :
					$args_wp = array( 
						'post_type' => 'homeland_properties', 
						'taxonomy' => 'homeland_property_type',
						'meta_key' => 'homeland_price',
						'orderby' => 'meta_value_num', 
						'order' => 'DESC', 
						'posts_per_page' => $homeland_num_properties, 
						'paged' => $paged 
					);
				elseif($homeland_filter_default == "Name") :
					$args_wp = array( 
						'post_type' => 'homeland_properties', 
						'taxonomy' => 'homeland_property_type',
						'orderby' => 'title', 
						'order' => 'DESC', 
						'posts_per_page' => $homeland_num_properties, 
						'paged' => $paged 
					);
				else :
					$args_wp = array( 
						'post_type' => 'homeland_properties', 
						'taxonomy' => 'homeland_property_type',
						'orderby' => 'date', 
						'order' => 'DESC', 
						'posts_per_page' => $homeland_num_properties, 
						'paged' => $paged 
					);
				endif;
						
				$args = apply_filters('homeland_properties_parameters', $args_wp);
				$wp_query = new WP_Query( $args );

				//Taxonomy Dynamic Classes
				if($homeland_tax_layout == "Left Sidebar") :
					$homeland_tax_class_main = "left-container right";
					$homeland_tax_class_container = "agent-properties property-list clear";
					$homeland_tax_class_sidebar = "sidebar left";
				elseif($homeland_tax_layout == "1 Column") :
					$homeland_tax_class_main = "theme-fullwidth";
					$homeland_tax_class_container = "property-list property-one-cols clear";
				elseif($homeland_tax_layout == "2 Columns") :
					$homeland_tax_class_main = "theme-fullwidth";
					$homeland_tax_class_container = "property-list property-two-cols clear";
					$homeland_tax_class_masonry_cols = "masonry";
				elseif($homeland_tax_layout == "3 Columns") :
					$homeland_tax_class_main = "theme-fullwidth";
					$homeland_tax_class_container = "property-list property-three-cols clear";
					$homeland_tax_class_masonry_cols = "masonry";
				elseif($homeland_tax_layout == "4 Columns") :
					$homeland_tax_class_main = "theme-fullwidth";
					$homeland_tax_class_container = "property-list property-four-cols clear";
					$homeland_tax_class_masonry_cols = "masonry";
				elseif($homeland_tax_layout == "Grid Sidebar") :
					$homeland_tax_class_main = "left-container";
					$homeland_tax_class_container = "property-list property-four-cols property-grid-sidebar clear";
					$homeland_tax_class_masonry_cols = "masonry";
					$homeland_tax_class_sidebar = "sidebar";
				elseif($homeland_tax_layout == "Grid Left Sidebar") :
					$homeland_tax_class_main = "left-container right";
					$homeland_tax_class_container = "property-list property-four-cols property-grid-sidebar clear";
					$homeland_tax_class_masonry_cols = "masonry";
					$homeland_tax_class_sidebar = "sidebar left";
				else :
					$homeland_tax_class_main = "left-container";
					$homeland_tax_class_container = "agent-properties property-list clear";
					$homeland_tax_class_sidebar = "sidebar";
				endif;

				?>		
					<div class="<?php echo $homeland_tax_class_main; ?>">	
						<?php homeland_property_sort_order(); ?>
						<div class="<?php echo $homeland_tax_class_container; ?>">
							<?php
								if(!empty( $homeland_term_description )) : echo $homeland_term_description; endif;
				         	if(empty($homeland_hide_map_list)) : echo "<section id='map-homepage'></section>"; endif;

								if ( $wp_query->have_posts() ) : ?>

									<!--Search Count-->
									<div class="search-count">
		            				<?php echo $wp_query->found_posts . "&nbsp;"; _e( 'Properties found...', 'codeex_theme_name' ); ?>
		            			</div>

		            			<!--Property List-->
									<div class="grid cs-style-3 <?php echo $homeland_tax_class_masonry_cols; ?>">
										<ul class="clear">
											<?php
												if($homeland_tax_layout == "1 Column") :
													while ($wp_query->have_posts()) : 
														$wp_query->the_post(); 
														get_template_part( 'loop', 'property-1col' );				
													endwhile; 
												elseif($homeland_tax_layout == "2 Columns") :
													for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
														$wp_query->the_post();			
														$homeland_columns = 2;	
														$homeland_class = 'property-cols masonry-item ';
														$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
														
														get_template_part( 'loop', 'property-2cols' );
													}
												elseif($homeland_tax_layout == "3 Columns") :
													for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
														$wp_query->the_post();			
														$homeland_columns = 3;	
														$homeland_class = 'property-cols masonry-item ';
														$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
														
														get_template_part( 'loop', 'property-3cols' );
													}
												elseif($homeland_tax_layout == "4 Columns") :
													for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
														$wp_query->the_post();			
														$homeland_columns = 4;	
														$homeland_class = 'property-cols masonry-item ';
														$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
														
														get_template_part( 'loop', 'property-4cols' );
													}
												elseif($homeland_tax_layout == "Grid Sidebar" || $homeland_tax_layout == "Grid Left Sidebar") :
													for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
														$wp_query->the_post();			
														$homeland_columns = 3;	
														$homeland_class = 'property-cols masonry-item ';
														$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
														
														get_template_part( 'loop', 'property-4cols' );
													}
												else :
													while ( $wp_query->have_posts() ) : 
														$wp_query->the_post(); 
														get_template_part( 'loop', 'properties' );
											    	endwhile; 
												endif;
										    ?>
							    		</ul>
							    	</div><?php
							    endif;		
							?>
						</div>
						<?php 
							if( $homeland_page_nav == "Next Previous Link" ) : 
								homeland_next_previous(); //modify function in "functions.php"...
							else : homeland_pagination(); //modify function in "functions.php"... 
							endif; 
						?>
					</div><?php

					//Sidebar
					if($homeland_tax_layout != "1 Column" && $homeland_tax_layout != "2 Columns" && $homeland_tax_layout != "3 Columns" && $homeland_tax_layout != "4 Columns") :
						?><div class="<?php echo $homeland_tax_class_sidebar; ?>"><?php get_sidebar(); ?></div><?php
					endif;
			?>
		</div>
	</section>

<?php get_footer(); ?>