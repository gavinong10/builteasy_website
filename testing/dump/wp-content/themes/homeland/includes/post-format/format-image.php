<?php 
	global $homeland_thumbnail_size; 
	$homeland_blog_button = esc_attr( get_option('homeland_blog_button') );
	$homeland_single_blog_layout = esc_attr( get_option('homeland_single_blog_layout') );
	$homeland_blog_button_label = !empty( $homeland_blog_button ) ? $homeland_blog_button : __( 'Learn More', 'codeex_theme_name' );

	if ( has_post_thumbnail() ) :
		?>
			<div class="blog-image">
				<div class="blog-large-image">
					<?php
						if(is_single()) : 
							if($homeland_single_blog_layout == "Fullwidth") :
								the_post_thumbnail( 'homeland_portfolio_large' );
							else :
								the_post_thumbnail( 'homeland_theme_large' );
							endif;
						else : ?>
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($homeland_thumbnail_size); ?></a>
							<a href="<?php the_permalink(); ?>" class="continue"><?php echo $homeland_blog_button_label; ?> &rarr;</a><?php
						endif;
					?>
				</div>
			</div>
		<?php
	endif;
?>