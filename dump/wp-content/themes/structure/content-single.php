<?php
/**
 * @package ThemeMove
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() && get_theme_mod( 'post_feature_image_enable', post_feature_image_enable ) == 1 ) { ?>
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
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>
	<div class="entry-meta">
		<span class="author vcard"><i
				class="fa fa-user"></i> <?php echo __( 'Posted by ', 'thememove' ) . get_the_author(); ?></span>
		<span class="categories-links"><i
				class="fa fa-folder"></i> <?php echo __( 'In ', 'thememove' ) . get_the_category_list( __( ', ', 'thememove' ) ) ?> </span>
	</div>
	<!-- .entry-meta -->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'thememove' ),
			'after'  => '</div>',
		) );
		?>
	</div>
	<div class="entry-bottom">
		<div class="row">
			<div class="col-sm-8">
				<?php the_tags( '<i class="fa fa-tags"></i> Tags: ', ', ' ); ?>
			</div>
			<div class="col-sm-4">
				<div class="share">
					<span><i class="fa fa-share-alt"></i> <?php echo __( 'Share: ', 'thememove' ); ?></span>
					<span><a target="_blank"
					         href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>"><i
								class="fa fa-facebook"></i></a></span>
					<span><a target="_blank"
					         href="http://twitter.com/share?text=<?php echo urlencode( the_title() ); ?>&url=<?php echo urlencode( the_permalink() ); ?>&via=twitter&related=<?php echo urlencode( "coderplus:Wordpress Tips, jQuery and more" ); ?>"><i
								class="fa fa-twitter"></i></a></span>
					<span><a target="_blank" href="https://plus.google.com/share?url=<?php the_permalink() ?>"><i
								class="fa fa-google-plus"></i></a></span>
				</div>
			</div>
		</div>
	</div>
	<?php if ( get_theme_mod( 'post_meta_enable', post_meta_enable ) == 1 ) { ?>
		<footer class="entry-footer">
			<div class="author-info">
				<div class="row">
					<div class="col-sm-2">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), $size = '120' ); ?>
					</div>
					<div class="col-sm-10">
						<h3><?php the_author_meta( 'user_nicename' ); ?></h3>

						<p><?php the_author_meta( 'description' ); ?></p>
					</div>
				</div>
			</div>
		</footer>
	<?php } ?>
	<!-- .entry-footer -->
</article><!-- #post-## -->
