<?php
/**
 * Post Grid View
 * @var $this PostGridHelper
 */
?>
<?php if ( ! empty( $_POST['placeholder'] ) || ! isset( $this->_config['post_types'] ) ) : ?>
	<div class="image_placeholder thrv_wrapper">
		<a id="lb_post_grid" class="tve_click tve_green_button tve_clearfix" href="javascript:void(0)"
		   data-ctrl="controls.lb_open"
		   data-wpapi="lb_post_grid"
		   data-btn-text="Update" data-load="1">
			<i class="tve_icm tve-ic-upload"></i>
			<span>Add Post Grid</span>
		</a>
	</div>
<?php else : ?>
	<?php
	$config            = $this->_config;
	$config['columns'] = 1;
	$config['display'] = 'grid';

	/**
	 * backwards compatibility checks
	 */
	if ( empty( $config['posts_start'] ) ) {
		$config['posts_start'] = 0;
	}

	$post_types            = $this->tve_get_post_types( $config );
	$post_types            = empty( $post_types ) ? 'any' : $post_types;
	$custom_item_container = ! empty( $config['item_container'] ) ? ' data-tve-custom-colour="' . $config['item_container'] . '"' : '';
	?>
	<?php if ( $this->wrap && ! empty( $_POST['wrap'] ) ) : ?>
		<div class="thrv_wrapper thrv_post_grid">
	<?php endif; ?>

	<?php if ( $this->wrap ) : ?>
		<div class="thrive-shortcode-config"
		     style="display: none !important"><?php echo '__CONFIG_post_grid__' . tve_json_utf8_unslashit( json_encode( $config ) ) . '__CONFIG_post_grid__' ?></div>
	<?php endif; ?>

	<div class="tve_post_grid_wrapper tve_post_grid_vertical tve_clearfix tve_post_grid_<?php echo $config['display']; ?>">
		<?php $index = 1; ?>
		<?php $posts = $this->tve_get_post_grid_posts( $post_types, $config['filters'], $config['posts_per_page'], $config['posts_start'], $config['order'], $config['orderby'] ); ?>
		<?php $post_count = count( $posts ) ?>
		<?php foreach ( $posts as $key => $post ): ?>
			<?php if ( $config['display'] === 'grid' && ( $index === 1 || ( ( $index - 1 ) % $config['columns'] === 0 && $index - 1 > 0 ) ) ) : ?>
				<div class="tve_pg_row tve_clearfix">
			<?php endif; ?>
			<div class="tve_post tve_post_width_<?php echo $config['columns'] ?> <?php echo $this->postGridClasses( $index ) ?>">
				<div class="tve_pg_container clearfix" <?php echo $custom_item_container ?>>
					<?php if ( isset( $config['teaser_layout']['featured_image'] ) && $config['teaser_layout']['featured_image'] === 'true' ) : ?>
						<?php echo $this->tve_post_grid_display_post_featured_image( $post, $config ); ?>
					<?php endif; ?>
					<?php foreach ( $config['layout'] as $call ) : ?>
						<?php if ( isset( $config['teaser_layout'][ $call ] ) && $config['teaser_layout'][ $call ] === 'true' ) : ?>
							<?php $callable = 'tve_post_grid_display_post_' . $call; ?>
							<?php
							switch ( $callable ) {
								case 'tve_post_grid_display_post_text':
									echo $this->tve_post_grid_display_post_text( $post, $config );
									break;
								case 'tve_post_grid_display_post_title':
									echo $this->tve_post_grid_display_post_title( $post, $config );
									break;
								case 'tve_post_grid_display_post_read_more':
									echo $this->tve_post_grid_display_post_read_more( $post, $config );
									break;
							}
							?>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php if ( isset( $config['teaser_layout']['read_more'] ) && $config['teaser_layout']['read_more'] === 'true' ) : ?>
						<?php $this->tve_post_grid_display_post_read_more( $post, $config ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $config['display'] === 'grid' && ( $index % $config['columns'] === 0 || $index === $post_count ) ) : ?>
				</div>
			<?php endif; ?>
			<?php $index ++; ?>
		<?php endforeach; ?>
		<?php if ( count( $posts ) === 0 ) : ?>
			<p>No results have been returned for your Query. Please edit the query for content to display.</p>
		<?php endif; ?>
	</div>
	<?php if ( $this->wrap && ! empty( $_POST['wrap'] ) ) : ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
