<?php $link = noo_get_post_meta( get_the_id(), '_noo_wp_post_link',  '' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if( has_featured_content() && !is_singular()) : ?>
	<div class="content-featured">
		<?php noo_featured_default(); ?>
	</div>
	<?php endif; ?>
	<div class="content-wrap">
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
			<?php if($link != '') : ?>
			<span class="content-sub-title content-link">
				<a href="<?php echo $link; ?>" title="<?php echo esc_attr( sprintf( __( 'Shared link from post: "%s"', 'noo' ), the_title_attribute( 'echo=0' ) ) ); ?>" target="_blank">
				<i class="nooicon-link"></i>
				<?php echo $link; ?>
				</a>
			</span>
			<?php endif; ?>
			<?php //noo_content_meta(); ?>
		</header>
		<?php if ( is_singular() ) : ?>
			<div class="content">
				<?php if( has_featured_content() ) : ?>
				<div class="content-featured">
					<?php noo_featured_default(); ?>
				</div>
				<?php endif; ?>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		<?php else : ?>
			<!-- Don't use excerpt in Link post -->
			<!-- <div class="content-excerpt">
				<?php  //the_excerpt(); ?>
			</div> -->
		<?php endif; ?>
	</div>
	<?php noo_get_layout('post', 'footer'); ?>
</article> <!-- /#post- -->