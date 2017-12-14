<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
		$homeland_single_portfolio_layout = esc_attr( get_option('homeland_single_portfolio_layout') );
		$homeland_portfolio_static_image = esc_attr( get_option('homeland_portfolio_static_image') );

		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--PORTFOLIO LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<?php
				if($homeland_single_portfolio_layout =="Right Sidebar") :
					$homeland_single_layout_class = "left-container";
					$homeland_portfolio_class_sidebar = "sidebar";
					$homeland_portfolio_slide_image_size = "homeland_theme_large";
				elseif($homeland_single_portfolio_layout =="Left Sidebar") :
					$homeland_single_layout_class = "left-container right";
					$homeland_portfolio_class_sidebar = "sidebar left";
					$homeland_portfolio_slide_image_size = "homeland_theme_large";
				else :
					$homeland_single_layout_class = "theme-fullwidth";
					$homeland_portfolio_slide_image_size = "homeland_portfolio_large";
 				endif;

 				if(!empty($homeland_portfolio_static_image)) :
 					$homeland_portfolio_slider_class = "portfolio-static-images";
 				else :
 					$homeland_portfolio_slider_class = "portfolio-flexslider";
 				endif;
			?>		

			<!--FULLWIDTH-->			
			<div class="<?php echo $homeland_single_layout_class; ?>">				
				<?php
					if (have_posts()) : 
						if ( post_password_required() ) :
							?><div class="password-protect-content"><?php the_content(); ?></div><?php
						else :
							while (have_posts()) : the_post(); ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<div class="portfolio-image">
										<?php 
											$homeland_portfolio_attachment_order = esc_attr( get_option('homeland_portfolio_attachment_order') );
											$homeland_portfolio_attachment_orderby = esc_attr( get_option('homeland_portfolio_attachment_orderby') );
											$homeland_portfolio_category = get_the_term_list( $post->ID, 'homeland_portfolio_category', ' ', ', ', '' );
											$homeland_portfolio_tag = get_the_term_list ( $post->ID, 'homeland_portfolio_tag', ' ', ' ', '' );
											$homeland_rev_slider = esc_attr( get_post_meta($post->ID, 'homeland_rev_slider', true) ); 
											$homeland_website = esc_url( get_post_meta( $post->ID, 'homeland_website', true ) );
											$homeland_large_featured_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

											if(shortcode_exists("rev_slider") && !empty($homeland_rev_slider)) : 
												echo(do_shortcode('[rev_slider '.$homeland_rev_slider.']'));
											else : ?>
												<div class="<?php echo $homeland_portfolio_slider_class; ?>">
													<ul class="slides">
														<?php
															$args = array( 
																'post_type' => 'attachment', 
																'numberposts' => -1, 'order' => $homeland_portfolio_attachment_order, 
																'orderby' => $homeland_portfolio_attachment_orderby, 
																'post_status' => null, 
																'post_parent' => $post->ID 
															);
															$homeland_attachments = get_posts( $args );

															if ( $homeland_attachments ) :								
																foreach ( $homeland_attachments as $homeland_attachment ) :
																	$homeland_attachment_id = $homeland_attachment->ID;
																	$homeland_large_image_url = wp_get_attachment_image_src( $homeland_attachment_id, 'full' );
																	?>
																		<li>
																			<a href="<?php echo esc_url( $homeland_large_image_url[0] ); ?>" rel="gallery" title="<?php _e( 'View Fullscreen', 'codeex_theme_name' ); ?>">
																				<?php
																					echo wp_get_attachment_image( $homeland_attachment->ID, $homeland_portfolio_slide_image_size );
																				?>
																			</a>
																		</li>
																	<?php	
																endforeach;
															else : ?>
																<li>
																	<a href="<?php echo esc_url( $homeland_large_featured_image_url[0] ); ?>" rel="gallery" title="<?php _e( 'View Fullscreen', 'codeex_theme_name' ); ?>">
																		<?php 
																			if ( has_post_thumbnail() ) : 
																				the_post_thumbnail($homeland_portfolio_slide_image_size); 
																			endif; 
																		?>
																	</a>
																</li><?php
															endif;			
														?>
													</ul>
												</div><?php
											endif;											
										?>				
									</div>
									<div class="portfolio-list-desc clear">
										<?php the_title( '<h4>', '</h4>' ); ?>
										<span class="portfolio-category"><?php echo $homeland_portfolio_category; ?></span>
										<?php 
											the_content(); 	

											if(!empty($homeland_website)) : 
												?>
													<a href="<?php echo $homeland_website; ?>" class="live-demo" target="_blank">
														<?php _e( 'Live Demo', 'codeex_theme_name' ); ?>
													</a>
												<?php
											endif;

											if(!empty($homeland_portfolio_tag)) : ?>
												<label class="portfolio-tags">
													<span><?php _e( 'Tags', 'codeex_theme_name' ); ?></span>
													<?php echo $homeland_portfolio_tag; ?>
												</label><?php
											endif;
										?>
									</div>
									
								</article>	
								<?php wp_link_pages(); 
							endwhile;
						endif;
					endif;	
				?>					
				

				<!--NEXT/PREV NAV-->
		    	<div class="post-link-blog clear">
					<span class="prev">
						<?php previous_post_link( '%link', '&larr;&nbsp;' . __( 'Previous Post', 'codeex_theme_name' ), '' ); ?>
					</span>
					<span class="next">
						<?php next_post_link( '%link', __( 'Next Post', 'codeex_theme_name' ) . '&nbsp;&rarr;', '' ); ?>
					</span>
				</div>
			</div>

			<?php
				//Sidebar
				if($homeland_single_portfolio_layout == "Right Sidebar" || $homeland_single_portfolio_layout == "Left Sidebar") : 
					?><div class="<?php echo $homeland_portfolio_class_sidebar; ?>"><?php get_sidebar(); ?></div><?php
				endif;
			?>

		</div>
	</section>

<?php get_footer(); ?>