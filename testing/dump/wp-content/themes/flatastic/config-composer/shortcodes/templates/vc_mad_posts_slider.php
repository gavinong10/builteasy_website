<?php

class WPBakeryShortCode_VC_mad_posts_slider extends WPBakeryShortCode {

	public $atts = array();
	public $entries = '';
	protected $query = false;
	protected $loop_args = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'category' => '',
			'orderby' => '',
			'order' => '',
			'items' => 1,
			'posts_per_page' => 5,
			'css_animation' => ''
		), $atts, 'vc_mad_posts_slider');

		$this->query_entries();
		$html = $this->html();

		return $html;
	}

	public function query_entries() {
		$params = $this->atts;

		$query = array(
			'post_type' => 'post',
			'orderby' => $params['orderby'],
			'order' => $params['order'],
			'post_status' => array('publish')
		);

		if (!empty($params['category'])) {
			$categories = explode(',', $params['category']);
			$query['category__in'] = $categories;
		}

		if (!empty($params['posts_per_page'])) {
			$query['posts_per_page'] = $params['posts_per_page'];
		}

		$this->entries = new WP_Query($query);
	}

	protected function entry_title($title) {
		return "<h2 class='section-title m_bottom_25'>" . $title . "</h2>";
	}

	public function getCSSAnimation($css_animation) {
		$output = false;
		if ( $css_animation == 'yes' ) {
			wp_enqueue_script('waypoints');
			$output = true;
		}
		return $output;
	}

	public function html() {

		if (empty($this->entries) || empty($this->entries->posts)) return;

		$css_animation = $items = "";
		$entries = $this->entries;
		extract($this->atts);

		$zoom_image = mad_custom_get_option('zoom_image', '');
		$animation = $this->getCSSAnimation($css_animation);

		ob_start(); ?>

		<div class="post-carousel-area">

			<?php if (!empty($title)): ?>
				<?php echo $this->entry_title($title); ?>
			<?php endif; ?>

			<ul class="post-carousel" data-items="<?php echo (int) esc_attr($items) ?>">

				<?php while ( $entries->have_posts() ) : $entries->the_post(); ?>

					<?php
						$comments_count = get_comments_number();
						$link = get_permalink();
						$id = get_the_ID();
					?>

					<li <?php post_class('post-item') ?>>

						<?php if (has_post_thumbnail()): ?>

							<?php
								$thumbnail_atts = array(
									'class'	=> "tr_all_long_hover",
									'alt'	=> trim(strip_tags(get_the_title())),
									'title'	=> trim(strip_tags(get_the_title()))
								);
								$thumbnail = MAD_HELPER::get_the_post_thumbnail($id, '245*180', $thumbnail_atts);
								$title = sprintf(esc_attr__('%s', MAD_BASE_TEXTDOMAIN), the_title_attribute('echo=0'));
							?>

							<div class="image-overlay <?php echo esc_attr($zoom_image); ?>">
								<a href="<?php echo esc_url($link) ?>" title="<?php echo esc_attr($title) ?>" class="single-image photoframe <?php echo (esc_attr($animation)) ? "animate-bottom-to-top" : "" ?>">
									<?php echo $thumbnail ?>
								</a>
							</div><!--/ .image-overlay-->

						<?php endif; ?>

						<div class="post-content">

							<h4 class="post-title <?php echo (esc_attr($animation)) ? "animate-left-to-right" : "" ?>">
								<a href="<?php echo $link ?>"><?php the_title() ?></a>
							</h4>

							<div class="post-meta <?php echo (esc_attr($animation)) ? "animate-left-to-right" : "" ?>">

								<span class="entry-date"><?php echo get_the_time(get_option('date_format')) ?></span>

								<?php

								if ($comments_count != "0" || comments_open()): ?>
									<?php
										$link_to = $comments_count === "0" ? "#respond" : "#comments";
										$text = $comments_count === "1" ? __('Comment', MAD_BASE_TEXTDOMAIN) : __('Comments', MAD_BASE_TEXTDOMAIN);
									?>
									<a href="<?php echo esc_url($link) . esc_url($link_to) ?>"><?php echo esc_html($comments_count) . ' ' . esc_html($text) ?></a>
								<?php endif; ?>

							</div><!--/ .post-meta-->

							<?php $post_content = mad_post_content_truncate(get_the_content(), 100, " ", "..."); ?>

							<?php if (!empty($post_content)): ?>
								<div class="entry-body <?php echo (esc_attr($animation)) ? "animate-left-to-right" : "" ?>"><?php echo $post_content ?></div>
							<?php endif; ?>

							<a href="<?php echo esc_url($link) ?>" class="read-more <?php echo (esc_attr($animation)) ? "animate-left-to-right" : "" ?>">
								<?php _e('Read More', MAD_BASE_TEXTDOMAIN) ?>
							</a>

						</div><!--/ .post-content-->

					</li><!--/ .post-item-->

				<?php endwhile; ?>

			</ul><!--/ .post-carousel-->

			<?php wp_reset_postdata(); ?>

		</div><!--/ .post-slider-area-->

		<?php return ob_get_clean();
	}

}