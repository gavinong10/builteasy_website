
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if( is_singular() ) : ?>
	    <div class="content-featured">
	        <?php noo_featured_default(); ?>
	    </div>
	<?php endif; ?>
	<div class="content-wrap">
		<?php if(is_singular()): ?>
			<?php noo_social_share();?>
		<?php endif;?>
		<header class="content-header">
			<?php if ( is_singular() ) : ?>
			<h1 class="content-title">
				<?php the_title(); ?>
			</h1>
			<?php else : ?>
			<h2 class="content-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permanent link to: "%s"', 'noo' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
			</h2>
			<?php endif; ?>
			<?php noo_content_meta(); ?>
		</header>
        <?php if( has_featured_content() && !is_singular() ) : ?>
            <div class="content-featured">
                <?php noo_featured_image(); ?>
            </div>
        <?php endif; ?>
		<?php if ( is_singular() ) : ?>
			<div class="content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		<?php else : ?>
			<!-- Don't use excerpt in Image post -->
			<!-- <div class="content-excerpt">
				<?php // the_excerpt(); ?>
			</div> -->
		<?php endif; ?>
	</div>
	<?php noo_get_layout('post', 'footer'); ?>
</article> <!-- /#post- -->