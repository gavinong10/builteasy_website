<?php

if (!class_exists('MAD_VC_CONFIG')) {

	class MAD_VC_CONFIG {

		public $paths = array();

		public $removeElements = array(
			'vc_pie', 'vc_single_image', 'vc_images_carousel',
			'vc_posts_slider', 'vc_progress_bar', 'vc_carousel', 'vc_gallery',
			'vc_gmaps', 'vc_button2', 'vc_cta_button', 'vc_cta_button2',
			'vc_media_grid', 'vc_masonry_media_grid', 'vc_masonry_grid', 'vc_widget_sidebar',
			'vc_basic_grid',

			'products', 'product', 'product_attribute', 'recent_products', 'add_to_cart',
			'add_to_cart_url', 'product_category', 'product_categories', 'featured_products',
			'sale_products', 'best_selling_products', 'top_rated_products'
		);

		function __construct() {

			$dir = dirname(__FILE__);

			$this->paths = array(
				'APP_ROOT' => $dir,
				'APP_DIR' => basename( $dir ),
				'CONFIG_DIR' => $dir . '/config',
				'ASSETS_DIR_NAME' => 'assets',
				'BASE_URI' => MAD_BASE_URI . trailingslashit('config-composer'),
				'PARTS_VIEWS_DIR' => $dir . '/shortcodes/views/',
				'TEMPLATES_DIR' => $dir . '/shortcodes/templates/'
			);

			// Add New param
			$this->add_shortcode_params();
			$this->add_hooks();

			// Load
			require_once $this->path('CONFIG_DIR', 'templates.php');
			$this->autoloadLibraries($this->path('TEMPLATES_DIR'));

			$this->init();
		}

		public function before_init() {
			require_once $this->path('CONFIG_DIR', 'map.php');
		}

		public function init() {

			add_action('vc_build_admin_page', array(&$this, 'autoremoveElements'), 11);
			add_action('vc_load_shortcode', array(&$this, 'autoremoveElements'), 11);

			add_action('wp_head', array(&$this, 'replace_shortcode_custom_css'), 100);

			if ( is_admin() ) {
				add_action('load-post.php', array($this, 'admin_init') , 4);
				add_action('load-post-new.php', array($this, 'admin_init') , 4 );
				if (class_exists( 'WooCommerce' )) {
					add_action('vc_after_set_mode', array($this, 'woo_load'));
				}
			} else {
				add_action('wp_enqueue_scripts', array(&$this, 'front_init'), 9);
			}
		}

		public function replace_shortcode_custom_css($id = null) {
			if ( ! is_singular() ) { return; }
			if ( ! $id ) { $id = get_the_ID(); }

			if ( $id ) {
				$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
				if ( ! empty( $shortcodes_custom_css ) ) {
					$css = str_replace('!important', '', $shortcodes_custom_css);
					update_post_meta( $id, '_wpb_shortcodes_custom_css', $css );
				}
			}
		}

		public function woo_load() {
			add_action('vc_backend_editor_render', array(&$this, 'enqueueJsBackend'));
			add_filter('vc_form_fields_render_field_vc_mad_product_attribute_filter_param', array(&$this, 'productAttributeFilterParamValue' ), 10, 4 );
		}

		public function add_hooks() {
			add_action('pre_import_hook', array(&$this, 'wpb_content_types'));
			add_action('vc_before_init', array(&$this, 'before_init'), 1);
			add_action('init', array(&$this, 'isotope_ajax_hooks'));

			if (function_exists('layerslider')) {
				add_action('vc_after_init', array(&$this, 'add_param_css_animation_to_layerslider'));
			}
			add_filter('vc_font_container_get_allowed_tags', array(&$this, 'mad_font_container_get_allowed_tags'));
		}

		public function isotope_ajax_hooks() {
			add_action('wp_ajax_mad_ajax_isotope_items_more', array(&$this, 'ajax_load_more'));
			add_action('wp_ajax_nopriv_mad_ajax_isotope_items_more', array(&$this, 'ajax_load_more'));
		}

		public function ajax_load_more() {
			$mad_portfolio = new mad_isotope_masonry_entries($_POST);
			$mad_portfolio->query_entries($_POST);
			$output = $mad_portfolio->html();
			echo '{mad-isotope-loaded}'. $output;
			exit();
		}

		public function add_shortcode_params() {
			vc_add_shortcode_param('table_hidden', array(&$this, 'param_hidden_field'));
			vc_add_shortcode_param('choose_icons', array(&$this, 'param_icon_field'), $this->assetUrl('js/js_shortcode_param_icon.js'));
			vc_add_shortcode_param('table_number', array(&$this, 'param_table_number_field'), $this->assetUrl('js/js_shortcode_tables.js'));
			vc_add_shortcode_param('term_categories', array(&$this, 'param_woocommerce_term_categories'), $this->assetUrl('js/js_shortcode_products_cat.js'));
		}

		public function add_param_css_animation_to_layerslider() {
			$add_css_animation = array(
				'type' => 'dropdown',
				'heading' => __( 'CSS Animation', MAD_BASE_TEXTDOMAIN ),
				'param_name' => 'css_animation',
				'admin_label' => true,
				'value' => array(
					__( 'No', MAD_BASE_TEXTDOMAIN ) => '',
					__( 'Top to bottom', MAD_BASE_TEXTDOMAIN ) => 'top-to-bottom',
					__( 'Bottom to top', MAD_BASE_TEXTDOMAIN ) => 'bottom-to-top',
					__( 'Left to right', MAD_BASE_TEXTDOMAIN ) => 'left-to-right',
					__( 'Right to left', MAD_BASE_TEXTDOMAIN ) => 'right-to-left',
					__( 'Appear from center', MAD_BASE_TEXTDOMAIN ) => "appear",
					__( 'Fade', MAD_BASE_TEXTDOMAIN ) => "fade"
				),
				'group' => __( 'Animations', MAD_BASE_TEXTDOMAIN ),
				'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', MAD_BASE_TEXTDOMAIN )
			);
			vc_add_param( 'layerslider_vc', $add_css_animation );
		}

		public function admin_init() {
			add_action('admin_enqueue_scripts', array(&$this, 'admin_extend_js_css'));
		}

		public function front_init() {
			$this->register_jquery_ui();
			$this->register_css();
			$this->front_js_register();
			$this->wp_print_styles();
			$this->front_extend_js_css();
		}

		public function admin_extend_js_css() {
			wp_enqueue_style(MAD_PREFIX . 'extend-admin', $this->assetUrl('css/vc_st_extend_admin.css'), false, WPB_VC_VERSION);
			wp_enqueue_style(MAD_PREFIX . 'font-awesome', $this->assetUrl('css/font-awesome.min.css'), false, WPB_VC_VERSION);
		}

		public function front_js_register() {
			wp_register_script(MAD_PREFIX . 'wpb_composer_front_js', $this->assetUrl('js/js_composer_front'. (WP_DEBUG ? '' : '.min') .'.js'), array( 'jquery' ), WPB_VC_VERSION, true );
		}

		public function front_extend_js_css() {
			wp_enqueue_script(MAD_PREFIX . 'wpb_composer_front_js');
		}

		public function wp_print_styles() {
			wp_deregister_style('js_composer_front');
			wp_enqueue_style(MAD_PREFIX . 'css_composer_front');
		}

		public function register_jquery_ui() {
			$jquery_ui_css_file = $this->assetUrl('css/jquery-ui.css');
			wp_deregister_style('jquery-ui-style');
			wp_enqueue_style( 'jquery-ui-style', $jquery_ui_css_file, array(MAD_PREFIX . 'css_composer_front'), WPB_VC_VERSION, 'all' );
		}

		public function register_css() {
			$front_css_file = $this->assetUrl('css/css_composer_front.css');
			wp_register_style(MAD_PREFIX . 'css_composer_front', $front_css_file, array(MAD_PREFIX . 'style'), WPB_VC_VERSION, 'all');
		}

		public function autoremoveElements() {
			$elements = $this->removeElements;

			foreach ($elements as $element) {
				vc_remove_element($element);
			}
		}

		protected function autoloadLibraries($path) {
			foreach (glob($path. '*.php') as $file) {
				require_once($file);
			}
		}

		public function assetUrl($file) {
			return $this->paths['BASE_URI'] . $this->path('ASSETS_DIR_NAME', $file);
		}

		public function path($name, $file = '') {
			$path = $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
			return apply_filters('vc_path_filter', $path);
		}

		function fieldAttachedImages( $att_ids = array() ) {
			$output = '';

			foreach ( $att_ids as $th_id ) {
				$thumb_src = wp_get_attachment_image_src( $th_id, 'thumbnail' );
				if ( $thumb_src ) {
					$thumb_src = $thumb_src[0];
					$output .= '
							<li class="added">
								<img rel="' . $th_id . '" src="' . $thumb_src . '" />
								<input type="text" name=""/>
								<a href="#" class="icon-remove"></a>
							</li>';
				}
			}
			if ( $output != '' ) {
				return $output;
			}
		}

		public function param_icon_field($settings, $value) {
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type = isset($settings['type']) ? $settings['type'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';
			$icons = array('none', 'adjust', 'adn', 'align-center', 'align-justify', 'align-left', 'align-right', 'ambulance', 'anchor', 'android', 'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'apple', 'archive', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'asterisk', 'backward', 'ban', 'bar-chart-o', 'barcode', 'bars', 'beer', 'bell', 'bell-o', 'bitbucket', 'bitbucket-square', 'bitcoin', 'bold', 'bolt', 'book', 'bookmark', 'bookmark-o', 'briefcase', 'btc', 'bug', 'building-o', 'bullhorn', 'bullseye', 'calendar', 'calendar-o', 'camera', 'camera-retro', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'certificate', 'chain', 'chain-broken', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'circle', 'circle-o', 'clipboard', 'clock-o', 'cloud', 'cloud-download', 'cloud-upload', 'cny', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'columns', 'comment', 'comment-o', 'comments', 'comments-o', 'compass', 'compress', 'copy', 'credit-card', 'crop', 'crosshairs', 'css3', 'cut', 'cutlery', 'dashboard', 'dedent', 'desktop', 'dollar', 'dot-circle-o', 'download', 'dribbble', 'dropbox', 'edit', 'eject', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'eraser', 'eur', 'euro', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'expand', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'facebook', 'facebook-square', 'fast-backward', 'fast-forward', 'female', 'fighter-jet', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flash', 'flask', 'flickr', 'floppy-o', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'font', 'forward', 'foursquare', 'frown-o', 'gamepad', 'gavel', 'gbp', 'gear', 'gears', 'gift', 'github', 'github-alt', 'github-square', 'gittip', 'glass', 'globe', 'google-plus', 'google-plus-square', 'group', 'h-square', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'hdd-o', 'headphones', 'heart', 'heart-o', 'home', 'hospital-o', 'html5', 'inbox', 'indent', 'info', 'info-circle', 'inr', 'instagram', 'italic', 'jpy', 'key', 'keyboard-o', 'krw', 'laptop', 'leaf', 'legal', 'lemon-o', 'level-down', 'level-up', 'lightbulb-o', 'link', 'linkedin', 'linkedin-square', 'linux', 'list', 'list-alt', 'list-ol', 'list-ul', 'location-arrow', 'lock', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up', 'magic', 'magnet', 'mail-forward', 'mail-reply', 'mail-reply-all', 'male', 'map-marker', 'maxcdn', 'medkit', 'meh-o', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'mobile-phone', 'money', 'moon-o', 'music', 'none', 'outdent', 'pagelines', 'paperclip', 'paste', 'pause', 'pencil', 'pencil-square', 'pencil-square-o', 'phone', 'phone-square', 'picture-o', 'pinterest', 'pinterest-square', 'plane', 'play', 'play-circle', 'play-circle-o', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'quote-left', 'quote-right', 'random', 'refresh', 'renren', 'repeat', 'reply', 'reply-all', 'retweet', 'rmb', 'road', 'rocket', 'rotate-left', 'rotate-right', 'rouble', 'rss', 'rmad-square', 'rub', 'ruble', 'rupee', 'save', 'scissors', 'search', 'search-minus', 'search-plus', 'share', 'share-square', 'share-square-o', 'shield', 'shopping-cart', 'sign-in', 'sign-out', 'signal', 'sitemap', 'skype', 'smile-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-down', 'sort-numeric-asc', 'sort-numeric-desc', 'sort-up', 'spinner', 'square', 'square-o', 'stack-exchange', 'stack-overflow', 'star', 'star-half', 'star-half-empty', 'star-half-full', 'star-half-o', 'star-o', 'step-backward', 'step-forward', 'stethoscope', 'stop', 'strikethrough', 'subscript', 'suitcase', 'sun-o', 'superscript', 'table', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'terminal', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'toggle-down', 'toggle-left', 'toggle-right', 'toggle-up', 'trash-o', 'trello', 'trophy', 'truck', 'try', 'tumblr', 'tumblr-square', 'turkish-lira', 'twitter', 'twitter-square', 'umbrella', 'underline', 'undo', 'unlink', 'unlock', 'unlock-alt', 'unsorted', 'upload', 'usd', 'user', 'user-md', 'users', 'video-camera', 'vimeo-square', 'vk', 'volume-down', 'volume-off', 'volume-up', 'warning', 'weibo', 'wheelchair', 'windows', 'won', 'wrench', 'xing', 'xing-square', 'youtube', 'youtube-play', 'youtube-square');

			ob_start(); ?>

			<input type="hidden" name="<?php echo esc_attr($param_name) ?>" class="wpb_vc_param_value <?php echo esc_attr($param_name) . ' ' . esc_attr($type) . ' ' . esc_attr($class) ?> " value="<?php echo esc_attr($value) ?>" id="mad-trace" />
			<div class="mad-icon-preview"><i class="fa fa-<?php echo esc_attr($value) ?>"></i></div>
			<input class="mad-search" type="text" placeholder="Search icon" />
			<div id="mad-icon-dropdown">
				<ul class="mad-icon-list">

					<?php foreach ($icons as $icon): ?>
						<?php $selected = ($icon == $value) ? 'class="selected"' : ''; ?>
						<li <?php echo $selected ?> data-icon="<?php echo esc_attr($icon) ?>"><i class="mad-icon fa fa-<?php echo esc_attr($icon) ?>"></i></li>
					<?php endforeach; ?>

				</ul><!--/ .mad-icon-list-->
			</div><!--/ #mad-icon-dropdown-->

			<?php return ob_get_clean();
		}

		function param_hidden_field($settings, $value) {
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type = isset($settings['type']) ? $settings['type'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';
			ob_start(); ?>
				<input type="hidden" name="<?php echo esc_attr($param_name) ?>" class="wpb_vc_param_value wpb_el_type_table_hidden <?php echo $param_name . ' ' . $type . ' ' . $class ?>" value="<?php echo trim($value) ?>" />
			<?php return ob_get_clean();
		}

		function param_table_number_field($settings, $value) {
			ob_start(); ?>
			<div class="mad_number_block">
				<input id="<?php echo esc_attr($settings['param_name']) ?>" name="<?php echo esc_attr($settings['param_name']) ?>" class="wpb_vc_param_value wpb-number <?php echo esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '_field'  ?>" type="number" value="<?php echo esc_attr($value) ?>"/>
			</div><!--/ .mad_number_block-->
			<?php return ob_get_clean();
		}

		function param_woocommerce_term_categories($settings, $value) {

			$categories = get_terms($settings["term"]);

			ob_start(); ?>

			<input type="text" value="<?php echo esc_attr($value) ?>" name="<?php echo esc_attr($settings['param_name']) ?>" class="wpb_vc_param_value wpb-input mad-custom-val <?php echo esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) ?>" id="<?php echo esc_attr($settings['param_name']); ?>">

			<div class="mad-custom-wrapper">

				<ul class="mad-custom-vals">

					<?php $inserted_vals = explode(',', $value); ?>

					<?php foreach($categories as $val): ?>
						<?php if( in_array($val->term_id, $inserted_vals) ): ?>
							<li data-val="<?php echo $val->term_id ?>"><?php echo $val->name ?><button>×</button></li>
						<?php endif; ?>
					<?php endforeach; ?>

				</ul><!--/ .mad-custom-vals-->

				<ul class="mad-custom">

					<?php foreach($categories as $val): ?>
						<?php
							$selected = '';
							if ( in_array($val->term_id, $inserted_vals) ) {
								$selected = ' class="selected"';
							}
						?>
						<li <?php echo $selected ?> data-val="<?php echo $val->term_id ?>"><?php echo $val->name ?></li>
					<?php endforeach; ?>

				</ul><!--/ .mad-custom-->

			</div><!--/ .mad-custom-wrapper-->

			<?php
			return ob_get_clean();
		}

		public static function array_number($from = 0, $to = 50, $step = 1, $array = array()) {
			for ($i = $from; $i <= $to; $i += $step) {
				$array[$i] = $i;
			}
			return $array;
		}

		public static function get_order_sort_array() {
			return array('ID' => 'ID', 'date' => 'date', 'post_date' => 'post_date', 'title' => 'title',
				'post_title' => 'post_title', 'name' => 'name', 'post_name' => 'post_name', 'modified' => 'modified',
				'post_modified' => 'post_modified', 'modified_gmt' => 'modified_gmt', 'post_modified_gmt' => 'post_modified_gmt',
				'menu_order' => 'menu_order', 'parent' => 'parent', 'post_parent' => 'post_parent',
				'rand' => 'rand', 'comment_count' => 'comment_count', 'author' => 'author', 'post_author' => 'post_author');
		}

		public static function count_posts($type_post) {
			if (!isset($type_post)) {
				$type_post = 'post';
			}
			$count_posts = wp_count_posts($type_post);
			$published_posts = $count_posts->publish;
			return $published_posts;
		}

		public function mad_font_container_get_allowed_tags($allowed_tags) {
			array_unshift($allowed_tags, 'h1');
			return $allowed_tags;
		}

		public function getAttributeTermsAjax() {
			$attribute = vc_post_param( 'attribute' );
			$values = $this->getAttributeTerms( $attribute );
			$param = array(
				'param_name' => 'filter',
				'type' => 'checkbox'
			);
			$param_line = '';
			foreach ( $values as $label => $v ) {
				$param_line .= ' <label class="vc_checkbox-label"><input id="' . $param['param_name'] . '-' . $v . '" value="' . $v . '" class="wpb_vc_param_value ' . $param['param_name'] . ' ' . $param['type'] . '" type="checkbox" name="' . $param['param_name'] . '"' . '> ' . __( $label, "js_composer" ) . '</label>';
			}
			die( json_encode( $param_line ) );
		}

		public function enqueueJsBackend() {
			$woocommmerce_js_url = $this->assetUrl('js/woocommerce.js');
			wp_enqueue_script( 'mad_vc_vendor_woocommerce_backend', $woocommmerce_js_url, array( 'wpb_js_composer_js_storage' ), '1.0', true );
		}

		public function productAttributeFilterParamValue( $param_settings, $current_value, $map_settings, $atts ) {
			if ( isset( $atts['attribute'] ) ) {
				$value = $this->getAttributeTerms( $atts['attribute'] );
				if ( is_array( $value ) && ! empty( $value ) ) {
					$param_settings['value'] = $value;
				}
			}

			return $param_settings;
		}

		public function getAttributeTerms( $attribute ) {
			$terms = get_terms( 'pa_' . $attribute ); // return array. take slug
			$data = array();
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$data[ $term->name ] = $term->slug;
				}
			}

			return $data;
		}

		public function wpb_content_types() {
			$wpb_content_types = array( 'post', 'page', 'portfolio', 'product' );
			update_option('wpb_js_content_types', $wpb_content_types);
		}

		public static function getCSSAnimation($css_animation) {
			$output = '';
			if ( $css_animation != '' ) {
				wp_enqueue_script('waypoints');
				$output = ' animate-' . $css_animation;
			}
			return $output;
		}

	}

	new MAD_VC_CONFIG();
}