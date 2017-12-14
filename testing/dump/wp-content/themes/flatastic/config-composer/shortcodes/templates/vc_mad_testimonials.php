<?php

class WPBakeryShortCode_VC_mad_testimonials extends WPBakeryShortCode {

	public $atts = array();
	public $entries = '';

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'tag_title' => 'h2',
			'text_align' => 'align-left',
			'orderby' => 'date',
			'order' => 'DESC',
			'items' => '6',
			'style' => '',
			'categories' => array(),
			'display_show_image' => '',
			'pagination' => 'no',
			'autoplay' => '',
			'autoplaytimeout' => 5000,
			'css_animation' => ''
		), $atts, 'vc_mad_testimonials');

		$this->query_entries();
		return $this->html();
	}

	public function query_entries() {
		$params = $this->atts;
		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		if (!$page || $params['pagination'] == 'no') $page = 1;

		$tax_query = array();

		if (!empty($params['categories'])) {
			$categories = explode(',', $params['categories']);
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'testimonials_category',
					'field' => 'id',
					'terms' => $categories
				)
			);
		}

		$query = array(
			'orderby' => $params['orderby'],
			'order' => $params['order'],
			'paged' => $page,
			'post_type' => 'testimonials',
			'posts_per_page' => $params['items'],
			'tax_query' 	 => $tax_query
		);

		$this->entries = new WP_Query($query);
	}

	protected function entry_title($title, $tag_title) {
		return "<{$tag_title} class='section-title m_bottom_25'>". $title ."</{$tag_title}>";
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation == 'yes' ) {
			wp_enqueue_script('waypoints');
			$output = ' animate-left-to-right';
		}
		return $output;
	}

	public function html() {

		if (empty($this->entries) || empty($this->entries->posts)) return;

		$tag_title = $style = $text_align = $display_show_image = $pagination = $autoplay = $autoplaytimeout = $css_animation = '';

		extract($this->atts);

		$animation = $this->getCSSAnimation($css_animation) ? $this->getCSSAnimation($css_animation) : '';

		if ($style == 'tm-slider') {
			MAD_BASE_FUNCTIONS::enqueue_script('owlcarousel');
		}

		ob_start(); ?>

		<div class="testimonials-area">

			<?php echo (!empty($title)) ? $this->entry_title($title, $tag_title) : ""; ?>

			<div data-autoplaytimeout="<?php echo $autoplaytimeout ?>"
				 data-autoplay="<?php echo $autoplay == 'yes' ? 'true' : 'false' ?>" class="<?php echo esc_attr($style) ?> <?php echo esc_attr($text_align) ?>">

				<?php $post_loop = 1; ?>

				<?php foreach ($this->entries->posts as $entry):
					$id = $entry->ID;
					$name = get_the_title($id);
					$link  = get_permalink($id);
					$place = rwmb_meta('mad_tm_place', '', $id);
					$thumbnail_attr = array(
						'alt'	=> trim(strip_tags($entry->post_excerpt)),
						'title'	=> trim(strip_tags($entry->post_title)),
					);
					?>

					<div class="tm-item">

						<blockquote class="tm-blockquote <?php echo $animation ?>">
							<?php echo $entry->post_content; ?>
						</blockquote>

						<?php if ($display_show_image): ?>
							<?php if (has_post_thumbnail($id)): ?>
								<div class="tm-photo <?php echo $animation ?>">
									<?php echo get_the_post_thumbnail($id, array(70, 70), $thumbnail_attr); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<div class="tm-text-holder <?php echo $animation ?>">
							<h5 class="entry-title"><a href="<?php echo $link ?>"><?php echo $name; ?></a></h5>
							<span class="tm-place"><?php echo $place; ?></span>
						</div><!--/ .tm-text-holder-->

					</div><!--/ .tm-item-->

					<?php $post_loop ++; ?>
				<?php endforeach; ?>

			</div>

		</div><!--/ .testimonials-area-->

		<?php if ($pagination == "yes"): ?>
			<?php echo mad_corenavi($this->entries->max_num_pages); ?>
		<?php endif; ?>

		<?php
		$output = ob_get_clean();
		return $output;
	}

}