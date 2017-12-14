<?php global $product; ?>
<li>
	<div class="entry-thumb-image">
		<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
			<?php echo $product->get_image(array(60, 60)); ?>
		</a>
	</div>
	<div class="entry-post-holder">
		<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
			<h6 class="entry-post-title"><?php echo $product->get_title(); ?></h6>
		</a>
		<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
		<?php echo $product->get_price_html(); ?>
	</div>
	<div class="clear"></div>
</li>