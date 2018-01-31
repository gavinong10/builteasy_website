<?php get_header(); ?>
<div class="container-wrap">
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="<?php noo_main_class(); ?> <?php noo_blog_class(); ?>" role="main">
				<?php if(is_masonry_style()) : ?>
					<div class="masonry">
					<?php 
						$columns  = noo_get_option( 'noo_blog_masonry_columns', '2' );
						if(is_archive() && (noo_get_option('noo_blog_archive_style', 'same_as_blog' ) == 'same_as_blog')) {
							$columns  = noo_get_option( 'noo_blog_archive_masonry_columns', '2' );
						}
					?>
					<div id="masonry-container" data-masonry-gutter="40" class="masonry-container columns-<?php echo $columns; ?>">
				<?php endif; ?>

				<!-- Begin The loop -->
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php noo_get_layout( 'post',get_post_format()); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php noo_get_layout( 'no-content' ); ?>
				<?php endif; ?>
				<!-- End The loop -->

				<?php if(is_masonry_style()) : ?>
					</div><!-- /#masonry-container -->
					</div><!-- /#masonry -->
				<?php endif; ?>

				<?php noo_pagination(); ?>

			</div> <!-- /.main -->
			
			<?php get_sidebar(); ?>
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->
	
<?php get_footer(); ?>