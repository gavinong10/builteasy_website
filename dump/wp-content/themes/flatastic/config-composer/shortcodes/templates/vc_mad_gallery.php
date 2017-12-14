<?php
class WPBakeryShortCode_VC_mad_gallery extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'type' => 'image_grid',
			'onclick' => 'link_image',
			'custom_links' => '',
			'custom_links_target' => '',
			'img_size' => '',
			'columns' => 3,
			'images' => '',
			'image_title' => false
		), $atts, 'vc_mad_gallery');

		add_filter('mad_gallery_el_start_class', array(&$this, 'mad_gallery_class_filter'), 5, 2);

		return $this->html();
	}

	protected function image_title($attach_id) {
		$alt = trim(strip_tags(get_post_meta($attach_id, '_wp_attachment_image_alt', true)));
		if (empty($alt)) {
			$attachment = get_post($attach_id);
			$alt = trim(strip_tags($attachment->post_title));
		}
		$url = get_attachment_link($attach_id);
		$output = "<a href='". esc_url($url) ."'>" . esc_html($alt) . "</a>";
		return $output;
	}

	public function mad_gallery_class_filter($column_classes, $css_classes) {
		$classes = $column_classes .' '. $css_classes;
		return $classes;
	}

	private function html() {
		$col_column_class = $layout_type = $link = $img_size = $title = $type = $onclick = $custom_links = $custom_links_target = $columns = $images = $image_title = '';

		extract($this->atts);

		if (!empty($img_size) && strpos($img_size, '^')) {
			$img_size = explode('^', $img_size);
		} else {
			$img_size = $img_size;
		}

		$zoom_image = mad_custom_get_option('zoom_image', '');

		if ($type == 'image_grid') {
			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_gallery wpb_content_element row', $this->settings['base'], $this->atts );

			switch ($columns) {
				case 2:
					$col_column_class = 'col-sm-6';
					break;
				case 3:
					$col_column_class = 'col-sm-4';
					break;
				case 4:
					$col_column_class = 'col-sm-3';
					break;
				default:
					$col_column_class = 'col-sm-4';
					break;
			}
		} else if ($type == 'masonry_grid') {
			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_gallery wpb_content_element portfolio-items portfolio-isotope', $this->settings['base'], $this->atts );

			$col_column_class = 'portfolio-item';
			$layout_type = 'data-layout-type="masonry"';
		}

		if ($images == '') {  $images = '-1,-2,-3'; }
			$images = explode(',', $images);

		if ($onclick == 'custom_link') {
			$custom_links = explode(',', $custom_links);
		}

		$lightbox_random = ' data-group="portfolio-'. rand(). '"';

		ob_start(); ?>

		<div class="portfolio-holder">

			<?php if (!empty($title)): ?>
				<h3 class="m_bottom_25"><?php echo esc_html($title) ?></h3>
			<?php endif; ?>

			<div <?php echo $layout_type ?> class="wpb_wrapper <?php echo esc_attr($css_class) ?>">

				<?php foreach ($images as $id => $attach_id):

					if ($attach_id > 0) {
						$post_thumbnail = array();

						$alt = trim(strip_tags(get_post_meta($attach_id, '_wp_attachment_image_alt', true)));
						if (empty($alt)) {
							$attachment = get_post($attach_id);
							$alt = trim(strip_tags($attachment->post_title));
						}

						$thumbnail_atts = array( 'alt' => $alt );

						$new_img_size =  is_array($img_size) && isset($img_size[$id]) ? trim($img_size[$id]) : trim($img_size);

						$post_thumbnail['thumbnail'] = MAD_HELPER::get_the_thumbnail($attach_id, $new_img_size, $thumbnail_atts);
						$post_thumbnail['p_img_large'] = MAD_HELPER::get_post_attachment_image($attach_id, '');
					} else {
						$post_thumbnail = array();
						$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
						$post_thumbnail['p_img_large'] = vc_asset_url( 'vc/no_image.png' );
					}

					$thumbnail = $post_thumbnail['thumbnail'];
					$p_img_large = $post_thumbnail['p_img_large'];

					$alt = trim(strip_tags(get_post_meta($attach_id, '_wp_attachment_image_alt', true)));

					if (empty($alt)) {
						$attachment = get_post($attach_id);
						$alt = trim(strip_tags($attachment->post_title));
					}
					?>

					<div class="<?php echo apply_filters('mad_gallery_el_start_class', $col_column_class, '') ?>">

						<div class="image-overlay <?php echo esc_attr($zoom_image); ?>">

							<div class="photoframe with-buttons">

								<?php echo $thumbnail; ?>

								<div class="image-extra">
									<div class="extra-content">
										<div class="inner-extra">

											<div class="open-buttons clearfix">
												<?php if ($onclick == 'link_image'): ?>
													<?php
													$link = '<a href="' . $p_img_large . '" class="open-button lightbox-icon jackbox" '. $lightbox_random .' data-title="' . $alt . '"><span class="curtain"></span></a>';
													?>
												<?php elseif ($onclick == 'custom_link' && isset($custom_links[$id]) && $custom_links[$id] !== ''): ?>
													<?php
													$link = '<a href="' . esc_url($custom_links[$id]) . '" ' . (!empty($custom_links_target) ? ' target="' . $custom_links_target . '"' : '' ) . ' class="open-button link-icon"><span class="curtain"></span></a>';
													?>
												<?php endif; ?>

												<?php echo $link; ?>

											</div><!--/ .open-buttons-->

										</div><!--/ .inner-extra-->
									</div><!--/ .extra-content-->
								</div><!--/ .image-extra-->

							</div><!--/ .photoframe-->

						</div><!--/ .image-overlay-->

						<?php if ($image_title == 'yes'): ?>
							<div class="text-holder">
								<h4 class="entry-title"><?php echo $this->image_title($attach_id); ?></h4>
							</div><!--/ .text-holder-->
						<?php endif; ?>

					</div><!--/ .portfolio-item-->

				<?php endforeach; ?>

			</div><!--/ .wpb_wrapper-->

		</div><!--/ .portfolio-holder-->

		<?php return ob_get_clean();
	}

}