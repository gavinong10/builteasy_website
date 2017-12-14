<?php

class WPBakeryShortCode_VC_mad_products extends WPBakeryShortCode {

	public $atts = array();
	public $products = '';

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' 	 => '',
			'tag_title' => 'h2',
			'type' 		 => 'grid',
			'layout' 	 => 'view-grid',
			'categories' => array(),
			'columns' 	 => 4,
			'items' 	 => 6,
			'sort' 		 => '',
			'orderby' => '',
			'order' => '',
			'show' => '',
			'by_id' => '',
			'taxonomy' => 'product_cat',
			'filter' 	 => '',
			'random' => '',
			'sale' => '',
			'pagination' => 'no',
			'css_animation' => ''
		), $atts, 'vc_mad_products');

		global $woocommerce;
		if (!is_object($woocommerce) || !is_object($woocommerce->query)) return;

		$this->query();
		return $this->html();
	}

	protected function stringToArray( $value ) {
		$valid_values = array();
		$list = preg_split( '/\,[\s]*/', $value );
		foreach ( $list as $v ) {
			if ( strlen( $v ) > 0 ) {
				$valid_values[] = $v;
			}
		}
		return $valid_values;
	}

	public function query() {

		global $woocommerce;

		$params = $this->atts;

		$number = $params['items'];
		$orderby = sanitize_title ( $params['orderby'] );
		$order = sanitize_title( $params['order'] );
		$show = $params['show'];

		// Meta query
		$meta_query = array();
		$tax_query = array();
		$meta_query[] = $woocommerce->query->visibility_meta_query();
		$meta_query[] = $woocommerce->query->stock_status_meta_query();
		$meta_query = array_filter($meta_query);

		if (!empty($params['categories'])) {
			$categories = explode(',', $params['categories']);

			if (is_array($categories)) {
				$categories = $categories;
			} else {
				$categories = array($categories);
			}

			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $categories
				)
			);
		}

		$query = array(
			'post_type' 	 => 'product',
			'post_status' 	 => 'publish',
			'ignore_sticky_posts'	=> 1,
			'order'   		 => $order == 'asc' ? 'asc' : 'desc',
			'meta_query' 	 => $meta_query,
			'tax_query' 	 => $tax_query,
			'posts_per_page' => $number
		);

		if (!empty($params['by_id'])) {
			$in = $not_in = array();
			$by_ids = $params['by_id'];
			$ids = $this->stringToArray( $by_ids );

			foreach ( $ids as $id ) {
				$id = (int) $id;
				if ( $id < 0 ) {
					$not_in[] = abs( $id );
				} else {
					$in[] = $id;
				}
			}
			$query['post__in'] = $in;
			$query['post__not_in'] = $not_in;
		}

		if( $params['pagination'] == 'yes' ) {
			$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
			$query['paged'] = $paged;
		}

		if ( $orderby != '' ) {
			switch ( $orderby ) {
				case 'price' :
					$query['meta_key'] = '_price';
					$query['orderby']  = 'meta_value_num';
					break;
				case 'rand' :
					$query['orderby']  = 'rand';
					break;
				case 'sales' :
					$query['meta_key'] = 'total_sales';
					$query['orderby']  = 'meta_value_num';
					break;
				default :
					$query['orderby']  = $params['orderby'];
					break;
			}
		} else {
			$query['orderby'] = get_option('woocommerce_default_catalog_orderby');
		}

		switch ( $show ) {
			case 'featured' :
				$query['meta_query'][] = array(
					'key'   => '_featured',
					'value' => 'yes'
				);
				break;
			case 'onsale' :
				$product_ids_on_sale    = wc_get_product_ids_on_sale();
				$product_ids_on_sale[]  = 0;
				$query['post__in'] = $product_ids_on_sale;
				break;
			case 'bestselling':
				$query['ignore_sticky_posts'] = 1;
				$query['meta_key'] = 'total_sales';
				$query['orderby'] = 'meta_value_num';
				break;
			case 'toprated' :
				$query['ignore_sticky_posts'] = 1;
				$query['no_found_rows'] = 1;
				break;
		}

		if ($show == 'toprated')
			add_filter( 'posts_clauses', array( WC()->query , 'order_by_rating_post_clauses' ) );

		$this->products = new WP_Query( $query );

		global $woocommerce_loop;
		$woocommerce_loop['loop'] = 0;

		if ($show == 'toprated')
			remove_filter( 'posts_clauses', array( WC()->query , 'order_by_rating_post_clauses' ) );
	}

	protected function entry_title($title, $tag_title) {
		return "<{$tag_title} class='product-title'>". esc_html($title) ."</{$tag_title}>";
	}

	protected function sort_links($products, $params) {

		$get_categories = get_categories(array(
			'taxonomy'	 => $params['taxonomy'],
			'hide_empty' => 0
		));

		$current_cats = array();

		foreach ($products->posts as $entry) {
			if ($current_item_cats = get_the_terms( $entry->ID, $params['taxonomy'] )) {
				if (!empty($current_item_cats)) {
					foreach ($current_item_cats as $current_item_cat) {
						if (!empty($params['categories'])) {

							$categories = explode(',', $params['categories']);

							if (is_array($categories)) {
								$categories = $categories;
							} else {
								$categories = array($params['categories']);
							}

							if (in_array($current_item_cat->term_id, $categories)) {
								$current_cats[$current_item_cat->term_id] = $current_item_cat->term_id;
							}
						} else {
							$current_cats[$current_item_cat->term_id] = $current_item_cat->term_id;
						}

					}
				}
			}
		}

		ob_start(); ?>

		<ul class="product-filter">

			<li class="active animate-left-to-right"><button data-filter="*" value="All"><?php echo _e('All', MAD_BASE_TEXTDOMAIN) ?></button></li>

			<?php foreach ($get_categories as $category): ?>

				<?php if (in_array($category->term_id, $current_cats)):
					$nicename = str_replace('%', '', $category->category_nicename);
					?>
					<li><button data-filter="<?php echo esc_attr($nicename) ?>"><?php echo esc_html(trim($category->cat_name)) ?></button></li>
				<?php endif; ?>

			<?php endforeach; ?>

			<?php if ($params['random'] == "yes"): ?>
				<li class="animate-left-to-right"><button data-filter="random"><?php echo _e('Random', MAD_BASE_TEXTDOMAIN); ?></button></li>
			<?php endif; ?>

			<?php if ($params['sale'] == "yes"): ?>
				<li class="animate-left-to-right"><button data-filter="sale"><?php echo _e('Sale', MAD_BASE_TEXTDOMAIN); ?></button></li>
			<?php endif; ?>

		</ul><!--/ .product-filter-->

		<?php return ob_get_clean();
	}

	public function getTermsCat($id) {
		$classes = "";
		$item_categories = get_the_terms($id, 'product_cat');
		if (is_object($item_categories) || is_array($item_categories)) {
			foreach ($item_categories as $cat) {
				$classes .= $cat->slug . ' ';
			}
		}
		return $classes;
	}

	public function add_filter_classes($params) {
		if ($params['filter'] == 'yes') {
			add_filter('post_class', array(&$this, 'post_class_filter'));
		}
		if ($params['css_animation'] !== '') {
			add_filter('product_class', array(&$this, 'post_class_animations'));
		}
	}

	public function post_class_animations($classes) {
		$classes[] = $this->getCSSAnimation( $this->atts['css_animation'] );
		return $classes;
	}

	public function post_class_filter($classes) {
		$classes[] = str_replace('%', '', self::getTermsCat(get_the_ID()));
		return $classes;
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script('waypoints');
			$output = ' long animate-' . $css_animation;
		}
		return $output;
	}

	protected function html() {

		if (empty($this->products) || empty($this->products->posts)) return;

		$products = $this->products;
		$params = $this->atts;
		$sidebar_position = MAD_HELPER::template_layout_class('sidebar_position');

		$type = $tag_title = $layout = $columns = $filter = $pagination = $shop_columns = $attr = $css_animation = '';

		extract($params);

		$filter == 'yes' ? $isotope = 'products-isotope' : $isotope = '';

		switch ($layout) {
			case 'view-grid';
			case 'view-grid-center';
				$shop_columns = 'shop-columns-' . $columns;
				break;
			case 'view-list';
				$shop_columns = '';
				break;
		}

		if ($type == 'product-carousel') {
			$isotope = '';
			$layout = 'view-grid';
			$shop_columns = '';
			$attr = "data-columns='{$columns}' data-sidebar='{$sidebar_position}'";
		}

		ob_start();

		if ( $products->have_posts() ) : ?>

			<?php
				$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'products-container ' . $shop_columns . ' ' . $isotope . ' ' . $type . ' ' . $layout, $this->settings['base'], $params );
			?>

			<div <?php echo $attr ?> class="<?php echo esc_attr($css_class) ?>">

				<?php if (!empty($title) || $filter == "yes"): ?>
					<div class="product-holder">
						<?php if (!empty($title)): ?>
							 <?php echo $this->entry_title($title, $tag_title); ?>
						<?php endif; ?>
						<?php if ($filter == 'yes'): ?>
							<?php echo $this->sort_links($products, $params); ?>
						<?php endif; ?>
					</div><!--/ .product-holder-->
				<?php endif; ?>

				<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php $this->add_filter_classes($params); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php do_action( 'woocommerce_after_shop_loop' ); ?>

			</div><!--/ .products-container-->

		<?php else : ?>

			<?php if (!woocommerce_product_subcategories(array('before' => '<ul class="products">', 'after' => '</ul>' ))) : ?>
				<div class="woocommerce-error">
					<div class="messagebox_text">
						<p><?php _e( 'No products found which match your selection.', MAD_BASE_TEXTDOMAIN ) ?></p>
					</div><!--/ .messagebox_text-->
				</div><!--/ .woocommerce-error-->
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( $pagination == 'yes' && $filter == 'no' ): ?>
			<?php echo mad_pagination($products->max_num_pages); ?>
		<?php endif; ?>

		<?php
			woocommerce_reset_loop();
			wp_reset_postdata();
		?>

		<?php return ob_get_clean();
	}

}