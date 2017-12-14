<?php
/**
 * @package ThemeMove
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<?php if ( has_post_thumbnail() ) { ?>
					<div class="post-thumb">
						<?php the_post_thumbnail( 'post-thumb-2' ); ?>
					</div>
				<?php } ?>
			</div>
			<div class="col-md-7">
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
				</div>
			</div>
		</div>
	</div>
</article><!-- #post-## -->