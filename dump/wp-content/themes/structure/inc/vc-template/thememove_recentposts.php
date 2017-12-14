<?php
extract( shortcode_atts( array(
	'number'       => '',
	'show_title'   => '',
	'show_excerpt' => '',
	'show_meta'    => '',
), $atts ) );
$args = array( 'post_type' => 'post', 'posts_per_page' => $number );
$loop = new WP_Query( $args );
?>
<div class="recent-posts">
	<?php while ( $loop->have_posts() ) : $loop->the_post();
		$meta = get_post_meta( get_the_ID() ); ?>
		<div class="recent-posts__item">
			<a href="<?php the_permalink() ?>"
			   class="recent-posts__thumb"><?php the_post_thumbnail( 'small-thumb' ); ?></a>
			<?php if ( $show_title ): ?>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
			<?php endif ?>
			<?php if ( $show_excerpt ): ?>
				<div class="entry-excerpt"><?php the_excerpt(); ?></div>
			<?php endif ?>
			<?php if ( $show_meta ): ?>
				<div class="post-meta">
					<span class="author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
					<span class="post-date"><i class="fa fa-clock-o"></i> <?php the_time( 'F j, Y' ); ?></span>
					<span class="post-com"><i
							class="fa fa-comments"></i> <?php comments_number( 'No response', 'One response', '% responses' ); ?></span>
				</div>
			<?php endif ?>
		</div>
	<?php endwhile;
	wp_reset_query(); ?>
</div>