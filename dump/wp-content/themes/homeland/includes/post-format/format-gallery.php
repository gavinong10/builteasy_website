<?php
	global $homeland_thumbnail_size; 
	$homeland_blog_attachment_order = esc_attr( get_option('homeland_blog_attachment_order') );
	$homeland_blog_attachment_orderby = esc_attr( get_option('homeland_blog_attachment_orderby') );
	$homeland_blog_thumb_slider = get_option('homeland_blog_thumb_slider');
	$homeland_thumb_id = get_post_thumbnail_id( get_the_ID() );
	$homeland_rev_slider = get_post_meta($post->ID, 'homeland_rev_slider', true);
	$homeland_single_blog_layout = esc_attr( get_option('homeland_single_blog_layout') );

	if(shortcode_exists("rev_slider") && !empty($homeland_rev_slider)) : 
		echo(do_shortcode('[rev_slider '.$homeland_rev_slider.']'));
	else : ?>
		<div class="blog-flexslider flex-loading">
			<ul class="slides">
				<?php
					if(empty($homeland_blog_thumb_slider)) :
						$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'order' => $homeland_blog_attachment_order, 'orderby' => $homeland_blog_attachment_orderby, 'post_status' => null, 'post_parent' => $post->ID );
					else :
						$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'order' => $homeland_blog_attachment_order, 'orderby' => $homeland_blog_attachment_orderby, 'post_status' => null, 'post_parent' => $post->ID, 'exclude' => $homeland_thumb_id );
					endif;

					$homeland_attachments = get_posts( $args );
					if ( $homeland_attachments ) :								
						foreach ( $homeland_attachments as $homeland_attachment ) :
							$homeland_attachment_id = $homeland_attachment->ID;
							$homeland_attachment_url = wp_get_attachment_url( $homeland_attachment->ID );
							$homeland_type = get_post_mime_type( $homeland_attachment->ID ); 

							if(is_single()) :
								if($homeland_single_blog_layout == "Fullwidth") :
									$homeland_attachment_img = wp_get_attachment_image( $homeland_attachment->ID, 'homeland_portfolio_large' );
								else :
									$homeland_attachment_img = wp_get_attachment_image( $homeland_attachment->ID, 'homeland_theme_large' );
								endif;
							else :
								$homeland_attachment_img = wp_get_attachment_image( $homeland_attachment->ID, $homeland_thumbnail_size );
							endif;

							$homeland_blog_video_class_width = "100%";
							$homeland_blog_video_class_height = "450";

							switch ( $homeland_type ) {
								case 'video/mp4': ?>
									<li>
										<video id="videojs_gallery" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="none" width="<?php echo $homeland_blog_video_class_width; ?>" height="<?php echo $homeland_blog_video_class_height; ?>" poster="<?php echo get_template_directory_uri(); ?>/img/video-attachment.jpg" data-setup="{}">
											<source src="<?php echo $homeland_attachment_url; ?>" type='video/mp4' />
											<source src="<?php echo $homeland_attachment_url; ?>" type='video/webm' />
											<source src="<?php echo $homeland_attachment_url; ?>" type='video/ogg' />
										</video>
									</li><?php
								break;
								default: 
									?><li><?php echo $homeland_attachment_img; ?></li><?php
								break;
							}
						endforeach;
					endif;			
				?>
			</ul>
		</div><?php
	endif;
?>