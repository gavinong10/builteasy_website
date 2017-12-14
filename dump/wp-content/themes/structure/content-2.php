<?php
/**
 * @package ThemeMove
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="post-thumb">
			<div class="dates">
				<span class="month"><?php the_time( 'F' ); ?></span>
				<span class="date"><?php the_time( 'd' ); ?></span>
				<span class="year"><?php the_time( 'Y' ); ?></span>
				<span
					class="comments-counts"><span><?php comments_number( '0', '1', '%' ); ?></span><?php comments_number( 'comment', 'Comment', 'Comments' ); ?></span>
			</div>
			<?php the_post_thumbnail( 'post-thumb' ); ?>
		</div>
	<?php } ?>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header>
	<!-- .entry-header -->
	<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<span class="author vcard"><i
					class="fa fa-user"></i> <?php echo __( 'Posted by ', 'thememove' ) . get_the_author(); ?></span>
			<span class="categories-links"><i
					class="fa fa-folder"></i> <?php echo __( 'In ', 'thememove' ) . get_the_category_list( __( ', ', 'thememove' ) ) ?> </span>
		</div><!-- .entry-meta -->
	<?php endif; ?>

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>
	<!-- .entry-content -->
	<div class="row">
		<div class="col-sm-6">
			<a class="btn read-more"
			   href="<?php echo get_permalink() ?>"><?php echo __( 'Continue Reading', 'thememove' ) ?></a>
		</div>
		<div class="col-sm-6">
			<div class="share">
				<span><i class="fa fa-share-alt"></i> <?php echo __( 'Share: ', 'thememove' ); ?></span>
				<span><a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>"><i
							class="fa fa-facebook"></i></a></span>
				<span><a target="_blank"
				         href="http://twitter.com/share?text=<?php echo urlencode( the_title() ); ?>&url=<?php echo urlencode( the_permalink() ); ?>&via=twitter&related=<?php echo urlencode( "coderplus:Wordpress Tips, jQuery and more" ); ?>"><i
							class="fa fa-twitter"></i></a></span>
				<span><a target="_blank" href="https://plus.google.com/share?url=<?php the_permalink() ?>"><i
							class="fa fa-google-plus"></i></a></span>
			</div>
		</div>
	</div>

</article><!-- #post-## -->