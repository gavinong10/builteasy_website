<?php get_header(); ?>
	
	<?php 
		$homeland_advance_search = esc_attr( get_post_meta( $post->ID, 'homeland_advance_search', true ) );
		$homeland_single_blog_layout = esc_attr( get_option('homeland_single_blog_layout') );

		if(empty($homeland_advance_search)) :
			homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
		endif;
	?>

	<!--BLOG LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<?php
				if($homeland_single_blog_layout =="Fullwidth") :
					$homeland_single_layout_class = "theme-fullwidth";
				elseif($homeland_single_blog_layout =="Left Sidebar") :
					$homeland_single_layout_class = "left-container right";
					$homeland_blog_class_sidebar = "sidebar left";
				else :
					$homeland_single_layout_class = "left-container";
					$homeland_blog_class_sidebar = "sidebar";
 				endif;
			?>		

			<!--LEFT CONTAINER-->			
			<div class="<?php echo $homeland_single_layout_class; ?>">				
				<div class="blog-list single-blog clear">
					<?php
						if (have_posts()) : 
							if ( post_password_required() ) :
								?><div class="password-protect-content"><?php the_content(); ?></div><?php
							else :
								while (have_posts()) : the_post(); ?>
									<article id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('blist') ); ?>>
										<div class="blog-image">
											<?php 
												$homeland_large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'homeland_theme_large');
												$homeland_blog_author_hide = esc_attr( get_option('homeland_blog_author_hide') );
												$homeland_hide_blog_comments = esc_attr( get_option('homeland_hide_blog_comments') );
												$homeland_author_desc = get_the_author_meta( 'description' );

												//Image Post Format
												if ( ( function_exists( 'get_post_format' ) && 'image' == get_post_format( $post->ID ) )  ) :
													get_template_part( 'includes/post-format/format', 'image' );
												
												//Video Post Format
												elseif ( ( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) )  ) :
													get_template_part( 'includes/post-format/format', 'video' );
												
												//Gallery Post Format	
												elseif ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) )  ) : 
													get_template_part( 'includes/post-format/format', 'gallery' );

												//Audio Post Format
												elseif ( ( function_exists( 'get_post_format' ) && 'audio' == get_post_format( $post->ID ) ) ) : 
													get_template_part( 'includes/post-format/format', 'audio' );
												endif;											
											?>				
										</div>
										
										<div class="blog-list-desc clear">
											<div class="blog-action">
												<ul class="clear">
													<li><i class="fa fa-calendar"></i><?php the_time(get_option('date_format')); ?></li>
													<li><i class="fa fa-folder-o"></i><?php the_category(', ') ?></li>
													<li><i class="fa fa-comment"></i><a href="<?php the_permalink(); ?>#comments"><?php comments_number( __( 'No Comments', 'codeex_theme_name' ), __( 'One Comment', 'codeex_theme_name' ), __( '% Comments', 'codeex_theme_name' ) ); ?></a>
													</li>				
												</ul>	
												<div class="blog-icon">
													<?php
														if ( ( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) ) ) :
															?><i class="fa fa-video-camera fa-lg"></i><?php	
														elseif ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) ) :
															?><i class="fa fa-camera fa-lg"></i><?php
														elseif ( ( function_exists( 'get_post_format' ) && 'audio' == get_post_format( $post->ID ) )  ) :
															?><i class="fa fa-music fa-lg"></i><?php		
														else :
															?><i class="fa fa-picture-o fa-lg"></i><?php	
														endif;
													?>
												</div>		
											</div>		
										</div>
										<?php the_content(); ?>
									</article>	
									<?php 
										wp_link_pages(); 
										the_tags( '<ul class="blog-tags clear"><li>', '</li><li>', '</li></ul>' );
										homeland_social_share(); //modify function in "includes/lib/custom-functions.php"...
								endwhile;
							endif;
						endif;	

						if(empty($homeland_blog_author_hide)) :
							if ( post_password_required() ) :
							else : ?>
								<!--AUTHOR-->
								<div class="author-block clear">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
									<h3><?php the_author_meta( 'user_firstname' ); echo "&nbsp;"; the_author_meta( 'user_lastname' ); ?></h3>
									<?php echo wpautop ( $homeland_author_desc ); ?>
								</div><?php
							endif;
						endif;

						if(empty($homeland_hide_blog_comments)) : comments_template(); endif;
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
			</div>

			<?php
				//Sidebar
				if($homeland_single_blog_layout != "Fullwidth") : 
					?><div class="<?php echo $homeland_blog_class_sidebar; ?>"><?php get_sidebar(); ?></div><?php
				endif;
			?>

		</div>

	</section>

<?php get_footer(); ?>