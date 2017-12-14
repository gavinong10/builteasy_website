<?php 
	global $homeland_thumbnail_size; 
	$homeland_blog_button = esc_attr( get_option('homeland_blog_button') );
	$homeland_blog_excerpt = esc_attr( get_option('homeland_blog_excerpt') );
	$homeland_audio = get_post_meta( $post->ID, 'homeland_audio', true );
?>

<article id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('blist clear') ); ?>>
	<div class="blog-timeline-image">
		<?php 
			if ( post_password_required() ) :
				?><div class="password-protect-thumb password-blog-timeline"><i class="fa fa-lock fa-2x"></i></div><?php
			else :
				//Video Post Format
				if ( ( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) ) ) :
					$homeland_format_icon = "fa-video-camera";
					get_template_part( 'includes/post-format/format', 'video' );
				
				//Gallery Post Format	
				elseif ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) ) : 
					$homeland_thumbnail_size = 'homeland_property_large';
					$homeland_format_icon = "fa-clone"; 
					get_template_part( 'includes/post-format/format', 'gallery' );

				//Audio Post Format
				elseif ( ( function_exists( 'get_post_format' ) && 'audio' == get_post_format( $post->ID ) ) ) : 
					$homeland_format_icon = "fa-music";
					get_template_part( 'includes/post-format/format', 'audio' );

				//Image Post Format
				else :
					$homeland_thumbnail_size = 'homeland_property_large';
					$homeland_format_icon = "fa-picture-o";	
					get_template_part( 'includes/post-format/format', 'image' );
				endif;	
			endif;
		?>
	</div>
	<div class="blog-timeline-content">
		<?php the_title( '<h4><a href="' . get_permalink() . '">', '</a></h4>' ); ?>
		<label>
			<?php the_time(get_option('date_format')); ?> / <?php the_author_meta( 'display_name' ); ?> / <?php the_category(', ') ?> / <a href="<?php the_permalink(); ?>#comments"><?php comments_number( __( 'No Comments', 'codeex_theme_name' ), __( 'One Comment', 'codeex_theme_name' ), __( '% Comments', 'codeex_theme_name' ) ); ?></a>
		</label>
		<?php the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="continue">
			<?php
				if(!empty( $homeland_blog_button )) : echo $homeland_blog_button;
				else : _e( 'Learn More', 'codeex_theme_name' );
				endif;
			?> &rarr;
		</a>	
		<div class="blog-icon"><i class="fa <?php echo $homeland_format_icon; ?> fa-lg"></i></div>
	</div>
</article>