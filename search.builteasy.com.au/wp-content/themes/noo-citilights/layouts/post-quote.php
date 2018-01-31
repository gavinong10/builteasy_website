<?php
	$quote = '';
	$quote = noo_get_post_meta(get_the_id() , '_noo_wp_post_quote', '');
	if($quote == '') {
		$quote = get_the_title( get_the_id() );
	}
	$cite = noo_get_post_meta(get_the_id() , '_noo_wp_post_quote_citation', '');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (has_featured_content() && !is_singular()): ?>
	<div class="content-featured">
		<?php noo_featured_default(); ?>
	</div>
	<?php endif; ?>
	<div class="content-wrap">
		<header class="content-header">
			<?php if (is_singular()): ?>
				<h1 class="content-title content-quote">
					<?php echo $quote; ?>
				</h1>
				<cite class="content-sub-title content-cite"><i class="nooicon-quote-left"></i> <?php echo $cite; ?></cite>
			<?php else : ?>
				<h2 class="content-title content-quote">
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permanent link to: "%s"', 'noo') , the_title_attribute('echo=0'))); ?>">
						<?php echo $quote; ?>
					</a>
				</h2>
				<cite class="content-sub-title content-cite"><i class="nooicon-quote-left"></i> <?php echo $cite; ?></cite>
			<?php endif; ?>
			<?php //noo_content_meta(); ?>
		</header>
		<?php if (is_singular()): ?>
			<div class="content">
				<?php if (has_featured_content()): ?>
				<div class="content-featured">
					<?php noo_featured_default(); ?>
				</div>
				<?php endif; ?>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		<?php else: ?>
			<!-- Don't use excerpt in Quote post -->
			<!-- <div class="content-excerpt">
				<?php // the_excerpt(); ?>
			</div> -->
		<?php endif; ?>
	</div>
	<?php noo_get_layout('post', 'footer'); ?>
</article> <!-- /#post- -->