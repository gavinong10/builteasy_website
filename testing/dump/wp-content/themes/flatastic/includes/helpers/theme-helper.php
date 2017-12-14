<?php

if (!class_exists('MAD_HELPER')) {

	class MAD_HELPER {

		/*	Get Registered Sidebars
		/* ---------------------------------------------------------------------- */

		public static function get_registered_sidebars($sidebars = array(), $exclude = array()) {
			global $wp_registered_sidebars;

			foreach ($wp_registered_sidebars as $sidebar) {
				if (!in_array($sidebar['name'], $exclude)) {
					$sidebars[$sidebar['name']] = $sidebar['name'];
				}
			}
			return $sidebars;
		}

		/*	Check page layout
		/* ---------------------------------------------------------------------- */

		public static function check_page_layout ($post_id = false) {
			global $mad_config;

			$result = false;
			$sidebar_position = 'sidebar_archive_position';

			if (empty($post_id)) $post_id = mad_post_id();

			if (is_page() || is_search() || is_attachment()) {
				$sidebar_position = 'sidebar_page_position';
			}
			if (is_archive()) {
				$sidebar_position = 'sidebar_archive_position';
			}
			if (is_single()) {
				$sidebar_position = 'sidebar_post_position';
			}
			if (is_singular()) {
				$result = rwmb_meta('mad_page_sidebar_position', '', $post_id);
			}
			if (is_404()) {
				$result = 'no_sidebar';
			}
			if (is_post_type_archive('portfolio')) {
				$result = mad_custom_get_option('sidebar_portfolio_archive_position');
			}
			if (is_post_type_archive('testimonials')) {
				$result = mad_custom_get_option('sidebar_testimonials_archive_position');
			}
			if (is_post_type_archive('team-members')) {
				$result = mad_custom_get_option('sidebar_team_members_archive_position');
			}
			if (mad_is_shop_installed()) {
				if (is_post_type_archive('product') || mad_is_product_category() || mad_is_product_tag()) {
					$result = mad_custom_get_option('sidebar_product_archive_position');
				}
			}

			if (!$result) {
				$result = mad_custom_get_option($sidebar_position);
			}

			if (!$result) {
				$result = 'sbr';
			}

			if ($result) {
				$mad_config['sidebar_position'] = $result;
			}
		}

		public static function template_layout_class($key, $echo = false) {
			global $mad_config;

			if (!isset($mad_config['sidebar_position'])) { self::check_page_layout(); }

			$return = $mad_config[$key];

			if ($echo == true) {
				echo $return;
			} else {
				return $return;
			}
		}

		/*	Header type layout
		/* ---------------------------------------------------------------------- */

		public static function header_layout () {
			$post_id = mad_post_id();

			@$header_layout = rwmb_meta('mad_header_layout', '', $post_id);
			if (empty($header_layout)) {
				$header_layout = mad_custom_get_option('header_layout');
			}
			return $header_layout;
		}

		/*	Header full width
		/* ---------------------------------------------------------------------- */

		public static function header_full_width () {
			$header_full_width = '';
			if (mad_custom_get_option('header_full_width')) {
				$header_full_width = 'header_full_width';
			}
			return $header_full_width;
		}

		/*	Footer full width
		/* ---------------------------------------------------------------------- */

		public static function footer_full_width () {
			$footer_full_width = '';
			if (mad_custom_get_option('footer_full_width')) {
				$footer_full_width = 'footer_full_width';
			}
			return $footer_full_width;
		}

		/*	Page type layout
		/* ---------------------------------------------------------------------- */

		public static function page_layout () {
			$post_id = mad_post_id();

			@$page_layout = rwmb_meta('mad_page_layout', '', $post_id);
			if (empty($page_layout)) {
				$page_layout = mad_custom_get_option('page_layout');
			}
			if (is_post_type_archive('portfolio')) {
				$page_layout = mad_custom_get_option('portfolio_archive_page_layout');
			}
			if (is_post_type_archive('testimonials')) {
				$page_layout = mad_custom_get_option('testimonials_archive_page_layout');
			}
			if (is_post_type_archive('team-members')) {
				$page_layout = mad_custom_get_option('team_members_archive_page_layout');
			}
			if (mad_is_shop_installed()) {
				if (is_post_type_archive('product') || mad_is_product_category() || mad_is_product_tag()) {
					$page_layout = mad_custom_get_option('product_archive_page_layout');
				}
			}

			return $page_layout;
		}

		/*  Main Navigation
		/* ---------------------------------------------------------------------- */

		public static function main_navigation() {

			$defaults = array(
				'container' => '',
				'theme_location' => 'primary'
			);

			$nav_menu = rwmb_meta('mad_nav_menu', '', mad_post_id());
			if (!empty($nav_menu) && is_numeric($nav_menu)) {
				$defaults['menu'] = $nav_menu;
			}

			$frontpage = get_permalink(mad_custom_get_option('frontpage'));
			$defaults['walker'] = new mad_walker_nav_menu($frontpage);

			if (has_nav_menu('primary')) {
				wp_nav_menu( $defaults );
			} else {
				echo '<ul>';
				wp_list_pages('title_li=');
				echo '</ul>';
			}
			echo '<div class="clear"></div>';
		}

		public static function output_html($view, $data = array()) {
			$path = 'widgets/';
			@extract($data);
			ob_start();
			include(MAD_INCLUDES_PATH . $path . $view . '.php');
			return ob_get_clean();
		}

		public static function create_atts_string ($data = array()) {
			$atts_string = "";

			foreach ($data as $key => $value) {
				if (is_array($value)) $value = implode(", ", $value);
				$atts_string .= " $key='$value' ";
			}
			return $atts_string;
		}

		public static function get_post_attachment_image($attachment_id, $dimensions, $crop = true) {
			$img_src = wp_get_attachment_image_src($attachment_id, $dimensions);
			$img_src = $img_src[0];
			return self::get_image($img_src, $dimensions, $crop);
		}

		public static function get_post_featured_image($post_id, $dimensions, $crop = true) {
			$img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
			$img_src = $img_src[0];
			return self::get_image($img_src, $dimensions, $crop);
		}

		public static function get_image($img_src, $dimensions, $crop = true) {
			if (empty($dimensions)) return $img_src;

			$sizes = explode('*', $dimensions);
			$img_src = aq_resize($img_src, $sizes[0], $sizes[1], $crop);

			if (!$img_src) {
				return 'http://dummyimage.com/' . $sizes[0] . 'x' . $sizes[1] . '&text=NO RESIZE';
			}
			return $img_src;
		}

		public static function get_the_post_thumbnail ($post_id, $dimensions, $thumbnail_atts = array()) {
			$atts = '';
			$sizes = array_filter(explode("*", $dimensions));
			if (is_array($sizes) && !empty($sizes)) {
				$atts = "width={$sizes[0]} height={$sizes[1]}";
			}
			return '<img '. esc_attr($atts) .' src="' . self::get_post_featured_image($post_id, $dimensions, true) . '" ' . self::create_atts_string($thumbnail_atts) . ' />';
		}

		public static function get_the_thumbnail ($attach_id, $dimensions, $thumbnail_atts = array()) {
			$atts = '';
			$sizes = array_filter(explode("*", $dimensions));
			if (is_array($sizes) && !empty($sizes)) {
				$atts = "width={$sizes[0]} height={$sizes[1]}";
			}

			return '<img '. esc_attr($atts) .' src="' . self::get_post_attachment_image($attach_id, $dimensions) . '" ' . self::create_atts_string($thumbnail_atts) . ' />';
		}

	}

}

/*	Blog alias
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_blog_alias')) {

	function mad_blog_alias ($format = 'standard', $image_size = array(), $blog_style = '') {
		global $mad_config;
		$sidebar_position = $mad_config['sidebar_position'];

		if (is_array($image_size) && !empty($image_size)) {
			$alias = $format == 'audio' || $format == 'video' ? $image_size[1] : $image_size[0];
			return $alias;
		}

		if (is_single() || $blog_style == 'blog-big') {
			switch ($format) {
				case 'standard':
				case 'gallery':
					if ($sidebar_position == 'no_sidebar') {
						$alias = '1140*495';
					} else {
						$alias = '848*370';
					}
					break;
				case 'audio':
				case 'video':
					if ($sidebar_position == 'no_sidebar') {
						$alias = array(1140, 495);
					} else {
						$alias = array(850, 370);
					}
					break;
				default: $alias = '848*370'; break;
			}
			return $alias;
		} else {
			switch ($format) {
				case 'standard':
				case 'gallery': $alias = '450*285'; break;
				case 'audio':
				case 'video':   $alias = array(350, 250); break;
				default:    $alias = '450*285'; break;
			}
			return $alias;
		}

	}
}

/*	Debug function print_r
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_print_r')) {
	function mad_print_r( $arr ) {
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}
}

/* 	Filter Frontpage Hook
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_filter_frontpage')) {

	function mad_filter_frontpage() {
		if (!is_admin()) {
			if (mad_custom_get_option('frontpage')) {
				add_filter('pre_option_page_on_front', 'mad_page_on_front_filter');
				add_filter('pre_option_show_on_front', 'mad_show_on_front_filter');
			}
		}
	}

	function mad_page_on_front_filter () { return mad_custom_get_option('frontpage'); }
	function mad_show_on_front_filter () { return 'page'; }

	add_action('init', 'mad_filter_frontpage', 3);
}

/* 	Pagination
/* ---------------------------------------------------------------------- */

if( !function_exists( 'mad_pagination' ) ) {

	function mad_pagination( $pages = '', $range = 10 ) {

		$paged = (get_query_var('paged')) ? get_query_var('paged') : false;
		if ( $paged === false ) $paged = (get_query_var('page')) ? get_query_var('page') : false;
		if ( $paged === false ) $paged = 1;

		if( $pages == '' ) {
			global $wp_query;

			if ( isset( $wp_query->max_num_pages ) )
				$pages = $wp_query->max_num_pages;

			if( !$pages )
				$pages = 1;
		}

		if ( 1 != $pages ) { ob_start(); ?>

			<div class="pagination-holder">
				<div class="row m_xs_bottom_30">

					<div class="col-sm-7 col-xs-5">
						<?php if ($pages > 1): ?>
							<p class="d_inline_middle f_size_medium">
								<?php printf(__("Results %d of %d", MAD_BASE_TEXTDOMAIN), $paged, $pages) ?>
							</p>
						<?php endif; ?>
					</div>

					<div class="col-sm-5 col-xs-7 t_align_r">
						<div class="pagination">
							<ul>
								<?php if( $paged > 1 ):  ?>
									<li><a class='prev' href='<?php echo get_pagenum_link( $paged - 1 ) ?>'></a></li>
								<?php endif; ?>

								<?php for( $i=1; $i <= $pages; $i++ ): ?>
									<?php if ( 1 != $pages &&( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $range ) ): ?>
										<?php $class = ( $paged == $i ) ? " class='selected'" : ''; ?>
										<li><a href='<?php echo get_pagenum_link( $i ) ?>'<?php echo $class ?> ><?php echo $i ?></a></li>
									<?php endif; ?>
								<?php endfor; ?>

								<?php if ( $paged < $pages ):  ?>
									<li><a class='next' href='<?php echo get_pagenum_link( $paged + 1 ) ?>'></a></li>
								<?php endif; ?>
							</ul>
						</div><!--/ .pagination-->
					</div>

				</div>
			</div><!--/ .pagination-holder-->

		<?php return ob_get_clean(); }
	}
}

/* 	Corenavi
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_corenavi')) {

	function mad_corenavi($pages = "", $a = array()) {
		global $wp_query;

		$total = 1;

		if ($pages == '') {
			$max = $wp_query->max_num_pages;
		} else {
			$max = $pages;
		}

		if (!$current = get_query_var('paged')) {
			$current = 1;
		}

		$a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
		$a['total'] = $max;
		$a['type'] = 'list';
		$a['current'] = $current;
		$a['mid_size'] = 3;
		$a['add_args'] = false;
		$a['end_size'] = 1;
		$a['prev_text'] = '';
		$a['next_text'] = '';

		ob_start(); ?>

		<?php if ($max > 1): ?>

			<div class="pagination-holder">

				<div class="row m_xs_bottom_30">

					<div class="col-sm-7 col-xs-5">
						<?php if ($total == 1 && $max > 1): ?>

							<p class="d_inline_middle f_size_medium">
								<?php printf(__("Results %d of %d", MAD_BASE_TEXTDOMAIN), $current, $max) ?>
							</p>

						<?php endif; ?>
					</div>

					<div class="col-sm-5 col-xs-7 t_align_r">
						<div class="pagination">
							<?php echo paginate_links($a); ?>
						</div><!--/ .pagination-->
					</div>

				</div><!--/ .row-->

			</div><!--/ .pagination-holder-->

		<?php endif;

		return ob_get_clean();
	}

}
