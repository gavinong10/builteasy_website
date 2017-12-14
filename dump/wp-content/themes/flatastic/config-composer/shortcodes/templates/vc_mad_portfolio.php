<?php

class WPBakeryShortCode_VC_mad_portfolio extends WPBakeryShortCode {

	public $atts = array();
	public $entries = '';
	public $settings = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'tag_title' => 'h2',
			'layout' => 'grid',
			'categories' => array(),
			'orderby' => 'date',
			'order' => 'DESC',
			'columns' 	=> 3,
			'items' 	=> 6,
			'sort' 		=> 'yes',
			'filter_style' => 'dropdown',
			'pagination' => 'no',
			'items_per_page' => 4,
			'image_size' => '640*400',
			'taxonomy'  => 'portfolio_categories',
			'class'		=> "",
			'position_text' => 'bottom',
			'related' => "",
			'css_animation' => '',
			'offset' => 0,
			'action' => 'mad_ajax_isotope_items_more'
		), $atts, 'vc_mad_portfolio');

		$this->query_entries();
		$html = $this->html();

		return $html;
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script('waypoints');
			$output = ' animate-' . $css_animation;
		}
		return $output;
	}

	public function query_entries($params = array()) {

		if (empty($params)) $params = $this->atts;

		$tag_ids = $tax_query = array();

		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		if (!$page || $params['pagination'] == 'no') $page = 1;

		if (!empty($params['categories'])) {
			$categories = explode(',', $params['categories']);
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'portfolio_categories',
					'field' => 'id',
					'terms' => $categories
				)
			);
		}

		$query = array(
			'post_type' => 'portfolio',
			'post_status'  => 'publish',
			'posts_per_page' => $params['items'],
			'orderby' => $params['orderby'],
			'order' => $params['order'],
			'paged' => $page,
			'tax_query' => $tax_query,
			'offset' => $params['offset']
		);

		if ($params['related'] == 'yes') {
			global $post;
			$this_id = $post->ID;
			$tags = wp_get_post_tags($this_id);

			if (!empty($tags) && is_array($tags)) {
				foreach ($tags as $tag ) {
					$tag_ids[] = (int) $tag->term_id;
				}
			}
			if (!empty($tag_ids)) {
				$query['tag__in'] = $tag_ids;
			}
			$query['post__not_in'] = array($this_id);
		}

		$this->entries = new WP_Query($query);
		$this->prepare_entries($params);
	}

	protected function entry_title($title, $tag_title) {
		return "<{$tag_title} class='section-title'>". $title ."</$tag_title>";
	}

	protected function sort_links($entries, $params) {

		$categories = get_categories(array(
			'taxonomy'	=> $params['taxonomy'],
			'hide_empty'=> 0
		));
		$current_cats = array();
		$display_cats = is_array($params['categories']) ? $params['categories'] : array_filter(explode(',', $params['categories']));
		$filter_style = !empty($params['filter_style']) ? $params['filter_style'] : 'dropdown';

		foreach ($entries as $entry) {
			if ($current_item_cats = get_the_terms( $entry->ID, $params['taxonomy'] )) {
				if (!empty($current_item_cats)) {
					foreach ($current_item_cats as $current_item_cat) {
						if (empty($display_cats) || in_array($current_item_cat->term_id, $display_cats)) {
							$current_cats[$current_item_cat->term_id] = $current_item_cat->term_id;
						}
					}
				}
			}
		}

		ob_start(); ?>

		<?php if ($filter_style == 'dropdown'): ?>

			<select class="portfolio_filter">

				<option value="*"><?php _e('Sort Porfolio...', MAD_BASE_TEXTDOMAIN) ?></option>
				<option data-filter="*" value="*"><?php _e('All', MAD_BASE_TEXTDOMAIN) ?></option>

				<?php foreach ($categories as $category): ?>
					<?php if (in_array($category->term_id, $current_cats)): ?>
						<?php $nicename = $category->category_nicename ?>
						<option data-filter=".<?php echo esc_attr($nicename) ?>" value=".<?php echo esc_attr($nicename) ?>" class="<?php echo esc_attr($nicename); ?>">
							<?php echo esc_html(trim($category->cat_name)); ?>
						</option>
					<?php endif; ?>
				<?php endforeach; ?>

			</select><!--/ .portfolio_filter-->

		<?php else: ?>

			<div class="portfolio-filter">
				<ul>
					<li class="active"><a data-filter="*"><?php _e('All', MAD_BASE_TEXTDOMAIN) ?></a></li>
					<?php foreach($categories as $category): ?>
						<?php if (in_array($category->term_id, $current_cats)): ?>
							<?php $nicename = $category->category_nicename ?>
							<li><a data-filter=".<?php echo esc_attr($nicename) ?>"><?php echo esc_html(trim($category->cat_name)); ?></a></li>
						<?php endif; ?>
					<?php endforeach ?>
				</ul>
			</div><!--/ .portfolio-filter-->

		<?php endif; ?>

		<?php return ob_get_clean();
	}

	protected function getTerms($id, $params, $slug) {
		$classes = "";
		$item_categories = get_the_terms($id, $params['taxonomy']);
		if (is_object($item_categories) || is_array($item_categories)) {
			foreach ($item_categories as $cat) {
				$classes .= $cat->$slug . ' ';
			}
		}
		return $classes;
	}

	public function html() {

		if (empty($this->loop)) return;

		$layoutMode = $atts = $grid = '';
		$data_group = 'data-group=portfolio-'. rand() .'';
		$title = 		 !empty($this->atts['title']) ? $this->atts['title'] : '';
		$tag_title = 	 !empty($this->atts['tag_title']) ? $this->atts['tag_title'] : '';
		$layout = 		 !empty($this->atts['layout']) ? $this->atts['layout'] : '';
		$columns = 		 !empty($this->atts['columns']) ? $this->atts['columns'] : '';
		$sort = 		 !empty($this->atts['sort']) ? $this->atts['sort'] : '';
		$pagination = 	 !empty($this->atts['pagination']) ? $this->atts['pagination'] : '';
		$position_text = !empty($this->atts['position_text']) ? $this->atts['position_text'] : '';
		$position_class = ($position_text == 'bottom') ? 'text-bottom' : 'text-inside';
		$filter_style = !empty($this->atts['filter_style']) ? $this->atts['filter_style'] : '';
//		$css_animation = !empty($this->atts['css_animation']) ? $this->atts['css_animation'] : '';
//		$animations = $this->getExtraClass($this->getCSSAnimation($css_animation));

		$zoom_image = mad_custom_get_option('zoom_image', '');

		$defaults = array(
			'id' => '',
			'sort_class' =>'',
			'masonry_class' =>'',
			'thumbnail' => '',
			'link' => '',
			'title' => '',
			'post_excerpt' => '',
			'cur_terms' => '',
			'image_size' => '640*400'
		);

		switch ($layout) {
			case 'grid':
				$init_layout = 'portfolio-isotope';
				$layoutMode = 'data-layout-type=fitRows';
				switch ($columns) {
					case 2: $grid = 'two_columns';    break;
					case 3: $grid = 'three_columns';  break;
					case 4: $grid = 'four_columns';   break;
					case 5: $grid = 'five_columns';   break;
				}
			break;
			case 'carousel':
				$init_layout = 'portfolio-carousel';
				$atts = "data-columns={$columns}";
				break;
			case 'masonry':
				$init_layout = 'portfolio-isotope';
				$layoutMode = 'data-layout-type=masonry';
			break;
		}

		$init_layout = $this->getExtraClass($init_layout);
		$grid = $this->getExtraClass($grid);
		$position_class = $this->getExtraClass($position_class);
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'portfolio-items ' . $init_layout . $grid . $position_class, $this->settings['base'], $this->atts );

		ob_start(); ?>

		<div class="portfolio-holder">

			<div class="section-line <?php if ($filter_style == 'buttons'): ?>filter-buttons<?php endif; ?>">
				<?php echo (!empty($title)) ? $this->entry_title($title, $tag_title) : ""; ?>
				<?php echo ($sort == 'yes') ? $this->sort_links($this->entries->posts, $this->atts) : ""; ?>
			</div><!--/ .section-line-->

			<div <?php echo esc_attr($layoutMode) ?> <?php echo esc_attr($atts) ?> class="<?php echo esc_attr($css_class) ?>">

				<?php foreach ($this->loop as $entry):
					extract(array_merge($defaults, $entry));
				?>

					<div class="portfolio-item <?php echo esc_attr($sort_class) ?><?php echo esc_attr($masonry_class) ?>">

						<div class="image-overlay <?php echo esc_attr($zoom_image); ?>">

							<div class="photoframe with-buttons">

								<?php if (has_post_thumbnail($id)): ?>
									<?php
										$thumbnail_atts = array(
											'class'	=> "tr_all_long_hover",
											'alt'	=> esc_attr($post_excerpt),
											'title'	=> esc_attr($title),
										);
										echo MAD_HELPER::get_the_post_thumbnail($id, $image_size, $thumbnail_atts);

										$post_thumbnail = get_post_thumbnail_id($id);

										if (isset($post_thumbnail) && $post_thumbnail > 0) {
											$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id), '');
											if (is_array($thumbnail) && isset($thumbnail[0])) {
												$thumbnail = $thumbnail[0];
											}
										}
									?>
								<?php endif; ?>

								<?php if ($position_text == 'inside'): ?>
									<?php if (!empty($title) && $layout !== 'masonry'): ?>
										<div class="image-extra">
											<div class="extra-content">
												<div class="inner-extra">

													<h4 class="entry-title">
														<a href="<?php echo esc_url($link) ?>" title="<?php echo esc_attr(strip_tags($title)) ?>"><?php echo esc_html($title) ?></a>
													</h4><!--/ .entry-title-->

													<?php if (!empty($cur_terms)): ?>
														<div class="post-meta">
															<?php foreach($cur_terms as $cur_term): ?>
																<a href="<?php echo get_term_link( (int) $cur_term->term_id, $cur_term->taxonomy ) ?>"><?php echo $cur_term->name ?></a>
															<?php endforeach; ?>
														</div><!--/ .post-meta-->
													<?php endif; ?>

													<div class="open-buttons clearfix">
														<a href="<?php echo esc_url($thumbnail) ?>" class="open-button lightbox-icon jackbox" <?php echo esc_attr($data_group) ?> data-title="<?php echo esc_attr($title) ?>"><span class="curtain"></span></a>
														<a href="<?php echo esc_url($link) ?>" class="open-button link-icon"><span class="curtain"></span></a>
													</div><!--/ .open-buttons-->

												</div><!--/ .inner-extra-->
											</div><!--/ .extra-content-->
										</div><!--/ .image-extra-->
									<?php endif; ?>

								<?php else: ?>
									<div class="image-extra">
										<div class="extra-content">
											<div class="inner-extra">
												<div class="open-buttons clearfix">
													<a href="<?php echo esc_url($thumbnail) ?>" class="open-button lightbox-icon jackbox" <?php echo esc_attr($data_group) ?> data-title="<?php echo esc_attr($title) ?>"><span class="curtain"></span></a>
													<a href="<?php echo esc_url($link) ?>" class="open-button link-icon"><span class="curtain"></span></a>
												</div><!--/ .open-buttons-->
											</div><!--/ .inner-extra-->
										</div><!--/ .extra-content-->
									</div><!--/ .image-extra-->
								<?php endif; ?>

							</div><!--/ .with-buttons-->

						</div><!--/ .image-overlay-->

						<?php if ($position_text == 'bottom'): ?>
							<?php if (!empty($title) && $layout != 'masonry'): ?>
								<div class="text-holder">
									<h4 class="entry-title">
										<a href="<?php echo esc_url($link) ?>" title="<?php echo esc_attr(strip_tags($title)) ?>"><?php echo esc_html($title) ?></a>
									</h4><!--/ .entry-title-->

									<?php if (!empty($cur_terms)): ?>
										<div class="post-meta">
											<?php foreach($cur_terms as $cur_term): ?>
												<a href="<?php echo get_term_link( (int) $cur_term->term_id, $cur_term->taxonomy ) ?>"><?php echo $cur_term->name ?></a>
											<?php endforeach; ?>
										</div><!--/ .post-meta-->
									<?php endif; ?>
								</div><!--/ .text-holder-->
							<?php endif; ?>
						<?php endif; ?>

					</div><!--/ .portfolio-item-->

				<?php endforeach; ?>

			</div><!--/ .portfolio-items-->

			<?php if ($pagination == 'load-more'): ?>
				<?php echo $this->load_more_button(); ?>
			<?php elseif ($pagination == 'yes') : ?>
				<?php echo mad_corenavi($this->entries->max_num_pages); ?>
			<?php endif; ?>

		</div><!--/ .portfolio-holder-->

		<?php return ob_get_clean();
	}

	public function load_more_button() {
		ob_start(); ?>

		<div class="post-load-more">
			<a class="load-button" href="#load-more" <?php echo $this->create_data_string($this->atts); ?>><?php _e('Load More', MAD_BASE_TEXTDOMAIN) ?></a>
		</div><!--/ .post-load-more-->

		<?php return ob_get_clean();
	}

	public function prepare_entries($params) {
		$this->loop = array();

		if (empty($params)) $params = $this->atts;
		if (empty($this->entries) || empty($this->entries->posts)) return;

		foreach ($this->entries->posts as $key => $entry) {
			$this->loop[$key]['id'] = $id = $entry->ID;
			$this->loop[$key]['sort_class'] = $sort_class = $this->getTerms($id, $params, 'slug');
			$this->loop[$key]['masonry_class'] = '';
			$this->loop[$key]['link'] = get_permalink($id);
			$this->loop[$key]['title'] = get_the_title($id);
			$this->loop[$key]['post_excerpt'] = $entry->post_excerpt;
			$this->loop[$key]['cur_terms'] = get_the_terms($id, 'portfolio_categories');

			if ($params['layout'] == 'masonry') {
				$image_size = get_post_meta($id, 'mad_masonry_size', true);
				switch ($image_size) {
					case 'masonry-big': $this->loop[$key]['image_size'] = '440*345'; break;
					case 'masonry-medium': $this->loop[$key]['image_size'] = '340*150'; break;
					case 'masonry-small': $this->loop[$key]['image_size'] = '260*150'; break;
					case 'masonry-long': $this->loop[$key]['image_size'] = '260*345'; break;
				}
				$this->loop[$key]['masonry_class'] = get_post_meta($id, 'mad_masonry_size', true);
			}
		}

	}

	public function create_data_string($data = array()) {
		$data_string = "";

		foreach($data as $key => $value) {
			if (is_array($value)) $value = implode(", ", $value);
			$data_string .= " data-$key='$value' ";
		}
		return $data_string;
	}

}