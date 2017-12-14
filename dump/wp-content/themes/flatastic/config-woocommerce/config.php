<?php

if (!class_exists('MAD_WOOCOMMERCE_CONFIG')) {

	class MAD_WOOCOMMERCE_CONFIG {

		protected static $_instance = null;

		public $action_quick_view = 'mad_action_add_product_popup';
		public $action_login = 'mad_action_login_popup';
		public $paths = array();
		public static $pathes = array();

		public function path($name, $file = '') {
			return $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
		}

		public function assetUrl($file) {
			return $this->paths['BASE_URI'] . $this->path('ASSETS_DIR_NAME', $file);
		}

		function __construct() {

			// Woocommerce support
			add_theme_support('woocommerce');

			$dir = dirname(__FILE__);

			define('MAD_WOO_CONFIG', true);

			$this->paths = array(
				'PHP' => $dir . '/' . trailingslashit('php'),
				'TEMPLATES' => $dir . '/' . trailingslashit('templates'),
				'ASSETS_DIR_NAME' => 'assets',
				'WIDGETS_DIR' => $dir . '/' . trailingslashit('widgets'),
				'BASE_URI' => trailingslashit(get_template_directory_uri()) . trailingslashit('config-woocommerce')
			);
			self::$pathes = $this->paths;

			require_once( $this->paths['PHP'] . 'functions-template.php' );
			require_once( $this->paths['PHP'] . 'ordering.class.php' );
			require_once( $this->paths['PHP'] . 'common-tab.class.php' );

			$this->woocommerce_global_config();
			$this->woocommerce_remove_hooks();
			$this->woocommerce_add_filters();
			$this->woocommerce_add_hooks();

			require_once( $this->paths['WIDGETS_DIR'] . 'class-wc-widget-products-specials.php' );

			add_action('wp_enqueue_scripts', array(&$this, 'add_enqueue_scripts'));
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('backend_theme_activation', array(&$this, 'mad_woocommerce_set_defaults'), 10);
			add_action('widgets_init', array(&$this, 'include_widgets'));

			require_once( $this->paths['PHP'] . 'dropdown-cart.class.php' );
			require_once( $this->paths['PHP'] . 'quick-view.class.php' );
			require_once( $this->paths['PHP'] . 'form-login.class.php' );
			require_once( $this->paths['PHP'] . 'currency-switcher.class.php' );

			add_action('pre_import_hook', array(&$this, 'mad_woocommerce_import_start'));
		}

		public function admin_init() {
			add_filter("manage_product_posts_columns", array(&$this, "manage_columns"));
		}

		public function include_widgets() {
			register_widget('Mad_WC_Widget_Products_Specials');
		}

		public function custom_get_option($key = false, $default = "") {

			$result = get_option('mad_options_');

			if (is_array($key)) {
				$result = $result[$key[0]];
			} else {
				$result = $result['mad'];
			}

			if (isset($result[$key])) {
				$result = $result[$key];
			} else if ($key == false) {
				$result = $result;
			} else {
				$result = $default;
			}

			if ($result == "") { $result = $default; }
			return $result;
		}

		public function woocommerce_global_config() {
			global $mad_config;

			$mad_config['shop_overview_column_count'] = $this->custom_get_option('woocommerce_column_count');
			$mad_config['shop_overview_product_count'] = $this->custom_get_option('woocommerce_product_count');

			// Add Image Size
			if (function_exists('add_image_size')) {
				$shop_thumbnail = wc_get_image_size( 'shop_thumbnail' );
				$shop_catalog	= wc_get_image_size( 'shop_catalog' );
				$shop_single	= wc_get_image_size( 'shop_single' );

				add_image_size( 'shop_thumbnail', $shop_thumbnail['width'], $shop_thumbnail['height'], $shop_thumbnail['crop'] );
				add_image_size( 'shop_catalog', $shop_catalog['width'], $shop_catalog['height'], $shop_catalog['crop'] );
				add_image_size( 'shop_single', $shop_single['width'], $shop_single['height'], $shop_single['crop'] );
			}

		}

		public function woocommerce_add_filters() {
			add_filter('woocommerce_enqueue_styles', array(&$this, 'mad_woocommerce_enqueue_styles'));

			add_filter('woocommerce_general_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));
			add_filter('woocommerce_page_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));
			add_filter('woocommerce_catalog_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));
			add_filter('woocommerce_inventory_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));
			add_filter('woocommerce_shipping_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));
			add_filter('woocommerce_tax_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));
			add_filter('woocommerce_product_settings', array(&$this, 'mad_woocommerce_general_settings_filter'));

			add_filter('loop_shop_columns', array(&$this, 'woocommerce_loop_columns'));
			add_filter('loop_shop_per_page', array(&$this, 'woocommerce_product_count'));
		}

		public function woocommerce_remove_hooks() {

			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );

			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

			remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
			remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

			remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
			remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

			remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
		}

		public function woocommerce_add_hooks() {

			add_action( 'woocommerce_single_variation', 'mad_woocommerce_single_variation_add_to_cart_button', 20 );

			add_action('woocommerce_before_shop_loop', array(&$this, 'mad_woocommerce_pagination'));
			add_action('woocommerce_after_shop_loop', array(&$this, 'mad_woocommerce_pagination'));

			add_action('woocommerce_archive_description', array(&$this, 'mad_woocommerce_ordering_products'));

			add_action('woocommerce_before_shop_loop_item_title', 'mad_woocommerce_show_product_loop_out_of_sale_flash');
			add_action('woocommerce_before_shop_loop_item_title', array(&$this, 'mad_woocommerce_before_thumbnail'));

			add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating');

			add_action('woocommerce_before_single_product_summary', 'mad_woocommerce_show_product_loop_out_of_sale_flash');
			add_action('woocommerce_before_single_product_summary', 'mad_share_product_this', 20);

			// Title, meta, content, price
			add_action('woocommerce_single_product_summary', array(&$this, 'mad_woocommerce_template_single_product_title'));
			add_action('woocommerce_single_product_summary', array(&$this, 'mad_woocommerce_template_single_meta'), 11);
			add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 12);
			add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 13);

			add_action('woocommerce_after_add_to_cart_button', array(&$this, 'product_actions'), 14);

			// Tabs, Link products, Related products
			add_action('woocommerce_after_single_product_summary', 'mad_woocommerce_output_product_data_tabs', 26);
			add_action('woocommerce_after_single_product_summary', 'mad_woocommerce_shop_link_products', 27);
			add_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 28);
			add_action('woocommerce_after_single_product_summary', 'mad_woocommerce_output_related_products', 29);

			// content desc
			add_action('woocommerce_before_shop_loop_item_title', array(&$this, 'woocommerce_shop_before_hidden'), 11);

			add_action('woocommerce_before_shop_loop_item_title',  array(&$this, 'woocommerce_shop_before_product_section'), 12);
			add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'woocommerce_shop_after_product_section'), 10);

			add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'woocommerce_shop_after_hidden'), 29);

			// description
			add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'woocommerce_shop_description'), 13);

			// process
			add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'woocommerce_shop_before_process'), 30);

			add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 31);
			add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 32);

			add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'product_actions'), 33);
			add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'woocommerce_shop_after_process'), 35);

//			add_action( 'woocommerce_after_main_content',  array(&$this, 'woocommerce_after_main_content'), 10);

			// Ajax
			add_action('wp_ajax_' . $this->action_quick_view, array(&$this, 'mad_ajax_product_popup'), 30);
			add_action('wp_ajax_nopriv_' . $this->action_quick_view, array(&$this, 'mad_ajax_product_popup'), 30);

			add_action('wp_ajax_nopriv_' . $this->action_login, array(&$this, 'mad_ajax_form_login'), 30);
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function manage_columns($columns) {
			unset($columns['wpseo-title']);
			unset($columns['wpseo-metadesc']);
			unset($columns['wpseo-focuskw']);

			return $columns;
		}

		public function woocommerce_loop_columns() {
			global $mad_config;
			return $mad_config['shop_overview_column_count'];
		}

		public function woocommerce_product_count() {
			global $mad_config;
			return $mad_config['shop_overview_product_count'];
		}

		public function mad_ajax_product_popup() {
			if (function_exists('check_ajax_referer')) {
				check_ajax_referer($this->action_quick_view, '_madnonce_ajax');
			}

			$quickview = new MAD_QUICK_VIEW($_POST['id']);
			echo $quickview->html();
			wp_die('exit');
		}

		public function mad_ajax_form_login() {
			if (function_exists('check_ajax_referer')) {
				check_ajax_referer($this->action_login, '_madnonce_ajax');
			}

			$form = new MAD_FORM_LOGIN($_POST['href']);
			echo $form->html();
			wp_die('exit');
		}

		public function mad_woocommerce_set_defaults() {
			global $mad_config;

			$mad_config['themeImgSizes']['shop_thumbnail'] = array('width' => 90, 'height' => 90);
			$mad_config['themeImgSizes']['shop_catalog']   = array('width' => 325, 'height' => 325);
			$mad_config['themeImgSizes']['shop_single']    = array('width'=> 450, 'height'=> 450);

			update_option('shop_thumbnail_image_size', $mad_config['themeImgSizes']['shop_thumbnail']);
			update_option('shop_catalog_image_size', $mad_config['themeImgSizes']['shop_catalog']);
			update_option('shop_single_image_size', $mad_config['themeImgSizes']['shop_single']);

			$disabled_options = array('woocommerce_enable_lightbox', 'woocommerce_frontend_css');

			foreach ($disabled_options as $option) {
				update_option($option, false);
			}

		}

		public function add_enqueue_scripts() {
			$css_file = $this->assetUrl('css/woocommerce-mod.css');
			$woo_mod_file = $this->assetUrl('js/woocommerce-mod' . (WP_DEBUG ? '':'.min') . '.js');
			$woo_zoom_file = $this->assetUrl('js/elevatezoom.min.js');
			$woo_variation_file = $this->assetUrl('js/manage-variation-selection.js');

			wp_enqueue_style(MAD_PREFIX . 'woocommerce-mod', $css_file);
			wp_enqueue_script(MAD_PREFIX . 'woocommerce-mod', $woo_mod_file, array('jquery'), 1, true);
			wp_register_script(MAD_PREFIX . 'elevate-zoom', $woo_zoom_file, MAD_PREFIX . 'woocommerce-mod');

			$goahead = 1;
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				$agent = $_SERVER['HTTP_USER_AGENT'];
			}

			if (preg_match('/(?i)msie [5-8]/', $agent)) { $goahead = 0; }

			if ($goahead == 1) {
				wp_deregister_script('wc-add-to-cart-variation');
				wp_dequeue_script ('wc-add-to-cart-variation');
				wp_enqueue_script('wc-add-to-cart-variation', $woo_variation_file , array('jquery'), 1, true );
			} else {
				wp_enqueue_script('wc-add-to-cart-variation');
			}

			wp_localize_script(MAD_PREFIX . 'woocommerce-mod', 'woocommerce_mod', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce_quick_view_popup' => wp_create_nonce( $this->action_quick_view ),
				'nonce_login_popup' => wp_create_nonce( $this->action_login ),
				'action_quick_view' => $this->action_quick_view,
				'action_login' => $this->action_login
			));
		}

		public static function enqueue_script($script) {
			wp_enqueue_script(MAD_PREFIX . $script);
		}

		public function mad_woocommerce_pagination() {
			echo mad_corenavi();
		}

		public function mad_woocommerce_ordering_products() {
			$ordering = new MAD_CATALOG_ORDERING();
			echo $ordering->output();
		}

		public function mad_woocommerce_second_thumbnail() {
			$id = mad_post_id();
			$active_hover = $this->custom_get_option('product_hover');

			if ($active_hover) {
				$product_gallery = get_post_meta( $id, '_product_image_gallery', true );

				if (!empty($product_gallery)) {
					$gallery  = explode(',', $product_gallery);
					$image_id = $gallery[0];

					$image = wp_get_attachment_image(
						$image_id,
						'shop_catalog',
						false,
						array(
							'class' => "attachment-shop_catalog product-hover"
						)
					);

					if (!empty($image)) return $image;
				}
			}

		}

		public function mad_woocommerce_before_thumbnail () {
			global $product, $post;
			$data = $this->create_data_string(array(
				'id' => get_the_ID()
			));
			$has_thumb = ($this->mad_woocommerce_second_thumbnail() != '') ? 'has-second-thumb' : '';
			?>

			<div class="thumbnail-container <?php echo esc_attr($has_thumb) ?>">

				<?php if ( $product->is_featured() ) : ?>
					<?php echo apply_filters( 'woocommerce_featured_flash', '<span class="onfeatured"><span>' . __( 'Featured', 'flatastic' ) . '</span></span>', $post, $product ); ?>
				<?php endif; ?>

				<a href="<?php the_permalink(); ?>">
					<div class="front">
						<?php
						$thumb_image = get_the_post_thumbnail(get_the_ID(), 'shop_catalog', array('class' => ''));
						if (!$thumb_image) {
							if ( wc_placeholder_img_src() ) {
								$thumb_image = wc_placeholder_img( 'shop_catalog' );
							}
						}
						echo $thumb_image;
						?>
					</div>
					<?php if ($has_thumb): ?>
						<div class="back"><?php echo $this->mad_woocommerce_second_thumbnail(); ?></div>
					<?php endif; ?>
				</a>

				<?php if ($this->custom_get_option('quick_view')): ?>
					<span <?php echo $data ?> class='quick-view'><?php _e('Quick View', 'flatastic') ?></span>
				<?php endif; ?>

				<?php do_action('woocommerce_after_thumbnail'); ?>

			</div><!--/ .thumbnail-container-->
			<?php
		}

		function mad_woocommerce_general_settings_filter($options) {
			$delete = array('woocommerce_enable_lightbox');

			foreach ($options as $key => $option) {
				if (isset($option['id']) && in_array($option['id'], $delete)) {
					unset($options[$key]);
				}
			}
			return $options;
		}

		function mad_woocommerce_enqueue_styles($styles) {
			$styles = array();
			return $styles;
		}

		public static function content_truncate($string, $limit, $break = ".", $pad = "...") {
			if (strlen($string) <= $limit) { return $string; }

			if (false !== ($breakpoint = strpos($string, $break, $limit))) {
				if ($breakpoint < strlen($string) - 1) {
					$string = substr($string, 0, $breakpoint) . $pad;
				}
			}
			if (!$breakpoint && strlen(strip_tags($string)) == strlen($string)) {
				$string = substr($string, 0, $limit) . $pad;
			}
			return $string;
		}

		public static function create_data_string($data = array()) {
			$data_string = "";

			foreach($data as $key => $value) {
				if (is_array($value)) $value = implode(", ", $value);
				$data_string .= " data-$key='$value' ";
			}
			return $data_string;
		}

		public function mad_woocommerce_template_single_product_title() {
			?>
			<section class="product-section">
				<?php woocommerce_template_single_title(); ?>
				<?php woocommerce_template_loop_rating(); ?>
			</section><!--/ .product-section-->
			<?php
		}

		function mad_woocommerce_template_single_meta () {
			?>

			<?php global $product; ?>

			<section class="product-section">
				<div class="product_meta">

					<?php do_action('woocommerce_product_meta_start'); ?>

					<?php if ('yes' == get_option('woocommerce_manage_stock')): ?>
						<?php if ($product->is_in_stock()): ?>
							<?php $availability = sprintf(__('%s in stock', 'flatastic'), $product->get_total_stock()); ?>
							<span class="stock_wrapper">
							<span class="meta-title"><?php _e('Availability:', 'flatastic'); ?></span>
							<span class="stock in-stock"><?php echo $availability; ?></span>
						</span>
						<?php else: ?>
							<span class="stock_wrapper">
							<span class="meta-title"><?php _e('Availability:', 'flatastic'); ?></span>
							<span class="stock out-of-stock"><?php _e('out of stock', 'flatastic') ?></span>
						</span>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
						<span class="sku_wrapper">
						<span class="meta-title"><?php _e('SKU:', 'flatastic'); ?></span>
						<span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>
					</span>
					<?php endif; ?>

					<?php do_action('woocommerce_product_meta_end'); ?>

				</div><!--/ .product_meta-->

			</section><!--/ .product-section-->

			<?php $post_content = !empty($product->post->post_excerpt) ? $product->post->post_excerpt : ''; ?>

			<?php if (!empty($post_content)): ?>
				<section class="product-section">
					<?php echo $post_content; ?>
				</section><!--/ .product-section-->
			<?php endif; ?>

			<?php
		}

		public function get_attributes() {
			global $product;
			$attributes = $product->get_attributes(); ?>

			<?php if (!empty($attributes)): ?>

				<?php ob_start(); ?>

				<?php foreach ($attributes as $key => $value): ?>
					<tr>
						<?php if (!empty($key)): ?>
							<td><?php echo ucfirst(substr($key, 3)); ?></td>
						<?php endif; ?>

						<?php if ($value['name'] !== ''): ?>
							<td>
								<?php $attribute = $product->get_attribute($value['name']); ?>

								<?php if (strpos($attribute, ",") !== false): ?>

									<?php $values = explode(',', $attribute); ?>

									<div class="select-small-size">
										<select class="woo-custom-select" name="<?php echo esc_attr($key) ?>">
											<?php foreach ($values as $val): ?>
												<option value="<?php echo esc_attr(trim($val)); ?>"><?php echo esc_html($val); ?></option>
											<?php endforeach; ?>
										</select>
									</div><!--/ .select-small-size-->

								<?php else: ?>
									<?php echo $attribute; ?>
								<?php endif; ?>

							</td>
						<?php endif; ?>

					</tr>
				<?php endforeach; ?>

				<?php return ob_get_clean();

			endif;
		}

		public function product_actions() {
			?>
			<div class="product-actions">
				<?php do_action('product-actions-before'); ?>
				<?php do_action('product-actions-after'); ?>
			</div><!--/ .product-actions-->
			<?php
		}

		function woocommerce_shop_before_hidden()  { echo '<div class="content-description">'; }
		function woocommerce_shop_after_hidden()   { echo '</div>'; }

		function woocommerce_shop_before_product_section()   { echo '<div class="product-section">'; }
		function woocommerce_shop_after_product_section()    { echo '</div>'; }

		function woocommerce_shop_before_process() { echo '<div class="process-section">'; }
		function woocommerce_shop_after_process()  { echo '</div><div class="clear"></div>'; }

		function woocommerce_shop_description() {
			global $product;
			$post_content = !empty($product->post->post_excerpt) ? $product->post->post_excerpt : '';
			echo '<div class="shop-desc">' . $post_content . '</div>';
		}

		function mad_woocommerce_import_start() {
			global $wpdb;

			$file = get_template_directory() . "/admin/demo/default/default.xml";

			$parser      = new WXR_Parser();
			$import_data = $parser->parse( $file );

			if ( isset( $import_data['posts'] ) ) {
				$posts = $import_data['posts'];

				if ( $posts && sizeof( $posts ) > 0 ) foreach ( $posts as $post ) {

					if ( $post['post_type'] == 'product' ) {

						if ( $post['terms'] && sizeof( $post['terms'] ) > 0 ) {

							foreach ( $post['terms'] as $term ) {

								$domain = $term['domain'];

								if ( strstr( $domain, 'pa_' ) ) {

									// Make sure it exists!
									if ( ! taxonomy_exists( $domain ) ) {

										$nicename = strtolower( sanitize_title( str_replace( 'pa_', '', $domain ) ) );
										$nicelabel = ucfirst( sanitize_title( str_replace( 'pa_', '', $domain ) ) );

										$exists_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_id FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s;", $nicename ) );

										// Create the taxonomy
										if ( ! $exists_in_db )
											$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array(
												'attribute_name' => $nicename,
												'attribute_label' => $nicelabel,
												'attribute_type' => 'select',
												'attribute_orderby' => 'menu_order' ), array( '%s', '%s', '%s', '%s'  ) );

										// Register the taxonomy now so that the import works!
										register_taxonomy( $domain,
											apply_filters( 'woocommerce_taxonomy_objects_' . $domain, array('product') ),
											apply_filters( 'woocommerce_taxonomy_args_' . $domain, array(
												'hierarchical' => true,
												'show_ui' => false,
												'query_var' => true,
												'rewrite' => false,
											) )
										);


									}
								}
							}

							$transient_name = 'wc_attribute_taxonomies';
							$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );
							set_transient( $transient_name, $attribute_taxonomies );

						}
					}
				}
			}

			$this->woo_product_settings_update();

		}

		public function woo_product_settings_update() {

			$wc_product_settings = array(
				'woocommerce_default_country' => 'US:CA',
				'wc_currency_codes' => 'USD
			EUR
			GBP',
				'woocommerce_default_catalog_orderby' => 'menu_order',
				'woocommerce_currency' => 'USD',
				'woocommerce_shop_page_id' => '673',
				'woocommerce_cart_page_id' => '825',
				'woocommerce_checkout_page_id' => '675',
				'woocommerce_terms_page_id' => '819',
				'woocommerce_myaccount_page_id' => '676',
				'yith_wcwl_wishlist_page_id' => '883',
				'woocommerce_enable_myaccount_registration' => 1
			);

			foreach ($wc_product_settings as $key => $option) {
				update_option($key, $option);
			}
		}

	}

}