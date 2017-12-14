<?php

class WPBakeryShortCode_VC_mad_blog_posts extends WPBakeryShortCode {

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
			'posts_per_page' => 5,
			'blog_style' => 'blog-medium',
			'first_big_post' => '',
			'pagination' => 'no'
		), $atts, 'vc_mad_blog_posts');

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

		$query['paged'] = (get_query_var('paged')) ? get_query_var('paged') : get_query_var('page');

		if (!empty($params['posts_per_page'])) {
			$query['posts_per_page'] = $params['posts_per_page'];
		}

		$this->entries = new WP_Query($query);
	}

	protected function entry_title($title) {
		return "<h2 class='section-title m_bottom_25'>" . esc_html($title) . "</h2>";
	}

	public function html() {

		if (empty($this->entries) || empty($this->entries->posts)) return;

		$blog_style = $first_big_post = $pagination = $before_content = '';

		extract($this->atts);

		$post_loop = 1;
		$first_big_post = $first_big_post == 'yes' ? true : false;

		ob_start(); ?>

		<?php if (!empty($title)): ?>
			<?php echo $this->entry_title($title); ?>
		<?php endif; ?>

		<div class="post-area <?php echo esc_attr($blog_style); ?>">

			<?php foreach ($this->entries->posts as $entry):
				$first_post = $first_big_post && $post_loop == 1;
				$type_blog  = ($first_post) ? $type_blog = 'blog-big' : $blog_style;

				$this_post = array();
				$this_post['post_id'] = $id = $entry->ID;
				$this_post['url'] = $link = get_permalink($id);
				$this_post['title'] = $title = $entry->post_title;
				$this_post['post_format'] = $format = get_post_format($id) ? get_post_format($id) : 'standard';
				$this_post['image_size'] = mad_blog_alias($format, '', $type_blog);
				$this_post['content'] = $post_content = !empty($entry->post_excerpt) ? $entry->post_excerpt : $entry->post_content;

				$this_post = apply_filters('entry-format-'. $format, $this_post);
				$post_class = "post-item clearfix entry-{$type_blog}";
				extract($this_post);
				?>

				<article <?php post_class($post_class, $id) ?>>

					<?php if ($first_post || $type_blog == 'blog-big'):

						$post_content = !empty($entry->post_excerpt) ? $entry->post_excerpt : mad_post_content_truncate($entry->post_content, mad_custom_get_option('excerpt_count_big_post') , " ", "…");
						echo $before_content;

						?>

						<div class="row">

							<div class="col-sm-8">

								<?php if (is_sticky($id)): ?>
									<?php printf( '<span class="sticky-post">%s</span>', __( 'Featured', MAD_BASE_TEXTDOMAIN ) ); ?>
								<?php endif; ?>

								<div class="entry-meta">

									<h4 class="entry-title">
										<a href='<?php echo esc_url($link) ?>'><?php echo esc_html($title) ?></a>
									</h4>

									<?php echo mad_blog_post_meta($id, $entry); ?>

								</div><!--/ .entry-meta-->

							</div>

							<?php if (mad_custom_get_option('blog-listing-meta-ratings')): ?>

								<div class="col-sm-4">

									<div class="rating-box">
										<div class="rate-desc"><?php _e( 'Rate this item', MAD_BASE_TEXTDOMAIN ); ?></div>
										<div class="rating readonly-rating" data-score="<?php echo esc_attr($entry->rating); ?>"></div>
										<span>(<?php printf(_n('%d vote', '%d votes', $entry->votes, MAD_BASE_TEXTDOMAIN), $entry->votes); ?>)</span>
									</div>

								</div>

							<?php endif; ?>

						</div><!--/ .row-->

						<?php echo (!empty($post_content)) ? "<p class='entry-body'>{$post_content}</p>" : '' ?>

						<a href="<?php echo $link ?>" class="read-more">
							<?php _e('Read More', MAD_BASE_TEXTDOMAIN) ?>
						</a>

					<?php else:

						$post_content = !empty($entry->post_excerpt) ? $entry->post_excerpt : mad_post_content_truncate($entry->post_content, mad_custom_get_option('excerpt_count_medium_post') , " ", "…", '');
						echo $before_content;

						?>

						<div class="post-content">

							<?php if (is_sticky($id)): ?>
								<?php printf( '<span class="sticky-post">%s</span>', __( 'Featured', MAD_BASE_TEXTDOMAIN ) ); ?>
							<?php endif; ?>

							<div class="entry-meta">

								<h4 class="entry-title">
									<a href="<?php echo esc_url($link) ?>"><?php echo esc_html($title) ?></a>
								</h4>

								<?php echo mad_blog_post_meta($id, $entry); ?>

							</div><!--/ .entry-meta-->

							<?php if (mad_custom_get_option('blog-listing-meta-ratings')): ?>

								<div class="rating-box">
									<span class="rate-desc"><?php _e( 'Rate this item', MAD_BASE_TEXTDOMAIN ); ?></span>
									<div class="rating readonly-rating" data-score="<?php echo esc_attr($entry->rating); ?>"></div>
									<span>(<?php printf(_n('%d vote', '%d votes', $entry->votes, MAD_BASE_TEXTDOMAIN), $entry->votes); ?>)</span>
								</div>

							<?php endif; ?>

							<?php echo (!empty($post_content)) ? "<p>{$post_content}</p>" : ''; ?>

							<a href="<?php echo esc_url($link); ?>" class="read-more">
								<?php _e('Read More', MAD_BASE_TEXTDOMAIN); ?>
							</a>

						</div><!--/ .post-content-->

					<?php endif; ?>

				</article><!--/ .post-item-->

				<?php $post_loop ++; ?>

			<?php endforeach; ?>

		</div><!--/ .post-area-->

		<?php if ($pagination == "yes"): ?>
			<?php echo mad_corenavi($this->entries->max_num_pages); ?>
		<?php endif;

		return ob_get_clean();
	}

}