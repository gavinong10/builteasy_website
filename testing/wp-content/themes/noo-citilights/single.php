
<?php get_header(); ?>
<?php 
$single_class= '';
?>
<div class="container-boxed <?php echo $single_class ?> max offset">
	<div class="row">
		<div class="<?php noo_main_class(); ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php noo_get_layout( 'post', get_post_format()); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'noo' ), 'after' => '</div>' ) ); ?>
				<?php if ( comments_open() ) : ?>
					<?php comments_template( '', true ); ?>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
		<?php get_sidebar(); ?>
	</div> <!-- /.row -->
</div> <!-- /.container-boxed.max.offset -->
<?php get_footer(); ?>