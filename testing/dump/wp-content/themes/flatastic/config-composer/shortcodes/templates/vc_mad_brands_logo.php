<?php

class WPBakeryShortCode_VC_mad_brands_logo extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => "",
			'twin' => "no",
			'images' => "",
			'links' => "",
			'autoplay' => '',
			'autoplaytimeout' => 5000,
			'css_animation' => ""
		), $atts, 'vc_mad_brands_logo');

		MAD_BASE_FUNCTIONS::enqueue_script('owlcarousel');

		return $this->html();
	}

	protected function entry_title($title) {
		return "<h2 class='section-title'>". $title ."</h2>";
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script('waypoints');
			$output = ' animate-' . $css_animation;
		}
		return $output;
	}

	public function html() {

		$images = $twin = $autoplay = $autoplaytimeout = $css_animation = '';
		extract($this->atts);

		$links = !empty($links) ? explode('|', $links) : '';
		$images = explode( ',', $images);
		$css_animation = $this->getCSSAnimation($css_animation);

		ob_start(); ?>

		<div class="brands-logo-area">

			<?php echo (!empty($title)) ? $this->entry_title($title) : $this->entry_title('&nbsp;'); ?>

			<ul data-autoplaytimeout="<?php echo esc_attr($autoplaytimeout) ?>"
				data-autoplay="<?php echo esc_attr($autoplay) == 'yes' ? 'true' : 'false' ?>" class="product-brands">

				<?php $i = 0; ?>

				<?php if ($twin == 'yes'): ?>
					<?php
						$images = array_chunk($images, 2);
						if (!empty($links)) {
							$links = array_chunk($links, 2);
						}
					?>
				<?php endif; ?>

				<?php foreach ($images as $id => $attach_id): ?>

					<li>

					<?php if (is_array($attach_id)): ?>

						<?php $b = 0; ?>

						<?php foreach($attach_id as $ids): ?>

							<?php if ($ids > 0): ?>
								<?php
									$post_thumbnail = wpb_getImageBySize(
										array(
											'attach_id' => $ids,
											'thumb_size' => array(165, 80),
											'class' => 'tr_all_long_hover ' . $css_animation
										)
									);

								?>
							<?php else: ?>

								<?php
									$post_thumbnail = array();
									$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
								?>

							<?php endif; ?>

							<?php $link = trim($links[$i][$b]) ?>

							<?php if (!empty($link)): ?>
								<a target="_blank" href="<?php echo esc_url($link) ?>" class="<?php echo esc_attr($css_animation) ?>">
							<?php endif; ?>

								<?php echo $post_thumbnail['thumbnail']; ?>

							<?php if (!empty($link)): ?>
								</a>
							<?php endif; ?>

							<?php $b++; ?>

						<?php endforeach; ?>

					<?php else: ?>

						<?php if ($attach_id > 0): ?>

							<?php
								$post_thumbnail = wpb_getImageBySize(
									array(
										'attach_id' => $attach_id,
										'thumb_size' => array(165, 80),
										'class' => 'tr_all_long_hover ' . $css_animation
									)
								);
							?>

						<?php else: ?>

							<?php
								$post_thumbnail = array();
								$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
							?>

						<?php endif; ?>

						<?php $link = (isset($links[$i]) && !empty($links[$i])) ? trim($links[$i]) : ''; ?>

						<?php if (isset($link) && !empty($link)): ?>
							<a target="_blank" href="<?php echo esc_url($link) ?>" class="<?php echo esc_attr($css_animation) ?>">
						<?php endif; ?>

						<?php echo $post_thumbnail['thumbnail']; ?>

						<?php if (isset($link) && !empty($link)): ?>
							</a>
						<?php endif; ?>

					<?php endif; ?>

					</li>

					<?php $i++; ?>

				<?php endforeach; ?>

			</ul><!--/ .product-brands-->

		</div><!--/ .brands-logo-area-->

		<?php return ob_get_clean();
	}

}