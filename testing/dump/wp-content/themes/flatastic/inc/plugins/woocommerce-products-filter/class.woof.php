<?php

if (!class_exists('MAD_WOOF')) {

	class MAD_WOOF {

		public $settings = array();
		public $version = '1.0.1';
		public $html_types = array(
			'radio' => 'Radio',
			'checkbox' => 'Checkbox',
			'color' => 'Color'
		);

		public $query_types = array(
			'and' => 'AND',
			'or' => 'OR'
		);

		public function __construct() {

			$this->init_settings();

			add_action('widgets_init', array($this, 'registerWidgets'));
			add_action('woocommerce_settings_tabs_array', array($this, 'woocommerce_settings_tabs_array'), 50);
			add_action('woocommerce_settings_tabs_woof', array($this, 'print_plugin_options'), 50);
			add_action('wp_enqueue_scripts', array($this, 'add_enqueue_scripts'), 15);
			add_action('init', array($this, 'woocommerce_layered_nav_init' ));
			add_action('init', array($this, 'price_filter_init' ));
			add_action('wp_ajax_woof_select_type', array( $this, 'ajax_print_terms') );

			add_shortcode('woof', array($this, 'woof_shortcode'));
		}

		public function registerWidgets() {
			register_widget('MAD_WOOF_Widget');
		}

		public function ajax_print_terms() {
			$type = $_POST['value']; //'color'
			$attribute = $_POST['attribute']; // pa_color
			$return['content'] = $this->attributes_table(
				$type, $attribute, json_decode($_POST['value']), false
			);

			echo json_encode($return);
			die();
		}

		public function woocommerce_settings_tabs_array($tabs) {
			$tabs['woof'] = __('Products Filter', MAD_BASE_TEXTDOMAIN);
			return $tabs;
		}

		public function add_enqueue_scripts() {
			if ( is_active_widget( false, false, 'widget-woof-filter', true ) && ! is_admin() ) {

				wp_enqueue_style('woof', WOOF_LINK . 'css/woof-front.css');
				wp_enqueue_script('woof', WOOF_LINK . 'js/woof-front.js', array(MAD_PREFIX . 'woocommerce-mod'), WC_VERSION, true);

				$args = array(
					'container'    => '.products.clearfix',
					'pagination'   => '.pagination-holder',
					'result_count' => '.woocommerce-result-count'
				);
				wp_localize_script( 'woof', 'woof_mod', $args );
			}
		}

		public function price_filter_init() {
			if ( is_active_widget( false, false, 'widget-woof-filter', true ) && ! is_admin() ) {

				wp_register_script( 'wc-price-slider-mod', WOOF_LINK . 'js/price-slider.js', array( 'jquery-ui-slider', 'woof' ), WC_VERSION, true );

				wp_localize_script( 'wc-price-slider-mod', 'woocommerce_price_slider_params', array(
					'currency_symbol' 	=> get_woocommerce_currency_symbol(),
					'min_price'			=> isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '',
					'max_price'			=> isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''
				));

				add_filter( 'loop_shop_post_in', array( $this, 'price_filter' ) );
			}
		}

		public function price_filter( $filtered_posts ) {
			global $wpdb;

			if ( isset( $_GET['max_price'] ) && isset( $_GET['min_price'] ) ) {

				$matched_products = array();
				$min 	= floatval( $_GET['min_price'] );
				$max 	= floatval( $_GET['max_price'] );

				$matched_products_query = apply_filters( 'woocommerce_price_filter_results', $wpdb->get_results( $wpdb->prepare("
	        	SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
				INNER JOIN $wpdb->postmeta ON ID = post_id
				WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish' AND meta_key = %s AND meta_value BETWEEN %d AND %d
			", '_price', $min, $max ), OBJECT_K ), $min, $max );

				if ( $matched_products_query ) {
					foreach ( $matched_products_query as $product ) {
						if ( $product->post_type == 'product' )
							$matched_products[] = $product->ID;
						if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) )
							$matched_products[] = $product->post_parent;
					}
				}

				// Filter the id's
				if ( sizeof( $filtered_posts ) == 0) {
					$filtered_posts = $matched_products;
					$filtered_posts[] = 0;
				} else {
					$filtered_posts = array_intersect( $filtered_posts, $matched_products );
					$filtered_posts[] = 0;
				}

			}

			return (array) $filtered_posts;
		}

		public function woocommerce_layered_nav_init() {

			if ( is_active_widget( false, false, 'widget-woof-filter', true ) && !is_admin() ) {

				global $_chosen_attributes, $woocommerce, $_attributes_array;

				$_chosen_attributes = $_attributes_array = array();

				/* FIX TO WOOCOMMERCE 2.1 */
				if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
					$attribute_taxonomies = wc_get_attribute_taxonomies();
				}
				else {
					$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
				}

				if ( $attribute_taxonomies ) {
					foreach ( $attribute_taxonomies as $tax ) {

						$attribute = sanitize_title( $tax->attribute_name );

						/* FIX TO WOOCOMMERCE 2.1 */
						if ( function_exists( 'wc_attribute_taxonomy_name' ) ) {
							$taxonomy = wc_attribute_taxonomy_name( $attribute );
						}
						else {
							$taxonomy = $woocommerce->attribute_taxonomy_name( $attribute );
						}


						// create an array of product attribute taxonomies
						$_attributes_array[] = $taxonomy;

						$name            = 'filter_' . $attribute;
						$query_type_name = 'query_type_' . $attribute;

						if ( ! empty( $_GET[$name] ) && taxonomy_exists( $taxonomy ) ) {

							$_chosen_attributes[$taxonomy]['terms'] = explode( ',', $_GET[$name] );

							if ( empty( $_GET[$query_type_name] ) || ! in_array( strtolower( $_GET[$query_type_name] ), array( 'and', 'or' ) ) ) {
								$_chosen_attributes[$taxonomy]['query_type'] = apply_filters( 'woocommerce_layered_nav_default_query_type', 'and' );
							}
							else {
								$_chosen_attributes[$taxonomy]['query_type'] = strtolower( $_GET[$query_type_name] );
							}

						}
					}
				}

				if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
					add_filter( 'loop_shop_post_in', 'woocommerce_layered_nav_query' );
				}
				else {
					add_filter( 'loop_shop_post_in', array( WC()->query, 'layered_nav_query' ) );
				}

			}
		}

		public function woof_shortcode() {

			$args = array();
			$args['taxonomies'] = array();
			$taxonomies = $this->get_taxonomies();

			$price = (object) array(
				'labels' => (object) array(
						'name' => __('Price', MAD_BASE_TEXTDOMAIN)
					)
			);
			$taxonomies['price'] = $price;

			if (!isset($this->settings['tax'])) { return; }

			$allow_taxonomies = array();

			if (isset($this->settings['tax'])) {
				$allow_taxonomies = (array) $this->settings['tax'];
			}

			if (!empty($taxonomies)) {
				foreach ($taxonomies as $tax_key => $tax) {

					if (!in_array($tax_key, array_keys($allow_taxonomies))) { continue; }

					$args['woof_settings'] = get_option('woof_settings');
					$args['taxonomies_info'][$tax_key] = $tax;

					if ($tax_key == 'price') {
						$price = (object) array(
							'labels' => (object) array(
									'name' => __('Price', MAD_BASE_TEXTDOMAIN)
								)
						);
						$args['taxonomies'][$tax_key] = $price;
					} else {
						$args['taxonomies'][$tax_key] = get_terms($tax_key, array('hide_empty'=>'0') );
					}

				}
			}

			return $this->render_html(WOOF_PATH . 'views/woof.php', $args);
		}

		public function print_plugin_options() {

			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('woof', WOOF_LINK . 'js/plugin_options.js', array('jquery'));
			wp_enqueue_style('woof', WOOF_LINK . 'css/plugin_options.css');

			if (isset($_POST['woof_settings'])) {
				WC_Admin_Settings::save_fields($this->get_options());
				update_option('woof_settings', $_POST['woof_settings']);
				$this->init_settings();
			}

			$args = array('woof_settings' => array());
			$woof_settings = get_option('woof_settings');

			if (!empty($woof_settings)) {
				$args['woof_settings'] = $woof_settings;
			}

			echo $this->render_html(WOOF_PATH . 'views/plugin_options.php', $args);
		}

		private function init_settings() {
			$this->settings = get_option('woof_settings');
		}

		private function get_taxonomies() {
			$taxonomies = get_object_taxonomies('product', 'objects');
			unset($taxonomies['product_shipping_class']);
			unset($taxonomies['product_type']);
			unset($taxonomies['product_cat']);
			unset($taxonomies['product_tag']);
			return $taxonomies;
		}

		public function get_options() {
			$options = array(
				array(
					'name' => __('Products Filter Options', MAD_BASE_TEXTDOMAIN),
					'type' => 'title',
					'desc' => '',
					'id' => 'woof_general_settings'
				),
				array(
					'name' => __('Show count', MAD_BASE_TEXTDOMAIN),
					'desc' => __('Show count of items near taxonomies terms on the front', MAD_BASE_TEXTDOMAIN),
					'id' => 'woof_show_count',
					'type' => 'select',
					'class' => 'chosen_select',
					'css' => 'min-width:300px;',
					'options' => array(
						0 => __('No', MAD_BASE_TEXTDOMAIN),
						1 => __('Yes', MAD_BASE_TEXTDOMAIN)
					),
					'default'  => 0,
					'desc_tip' => true
				),
				array(
					'name' => __('Show reset', MAD_BASE_TEXTDOMAIN),
					'desc' => __('Show reset products filter', MAD_BASE_TEXTDOMAIN),
					'id' => 'woof_show_reset',
					'type' => 'select',
					'class' => 'chosen_select',
					'css' => 'min-width:300px;',
					'options' => array(
						0 => __('No', MAD_BASE_TEXTDOMAIN),
						1 => __('Yes', MAD_BASE_TEXTDOMAIN)
					),
					'default'  => 1,
					'desc_tip' => true
				),
				array('type' => 'sectionend', 'id' => 'woof_general_settings')
			);
			return apply_filters('wc_settings_tab_woof_settings', $options);
		}

		public static function attributes_table( $type, $attribute, $values = array(), $echo = true ) {

			$return = '';
			$terms = get_terms( $attribute, array(
				'hide_empty' => '0',
				'orderby' => 'slug'
			) );

			if ('color' == $type) {
				if (!empty($terms)) {
					$return = sprintf( '<table><tr><th>%s</th><th>%s</th></tr>', __( 'Term', MAD_BASE_TEXTDOMAIN ), __( 'Color', MAD_BASE_TEXTDOMAIN ) );
					foreach ( $terms as $term ) {
						$return .= "<tr><td><label for='{$attribute}_{$term->slug}'>{$term->name}</label></td><td><input type='text' id='{$attribute}_{$term->slug}' name='woof_settings[colors][{$attribute}][{$term->slug}]' value='" . ( isset( $values[$term->slug] ) ? $values[$term->slug] : '' ) . "' size='3' class='mad-colorpicker' /></td></tr>";
					}
					$return .= '</table>';
				}
			}

			if ( $echo ) { echo $return; }
			return $return;
		}

		public function render_html($pagepath, $data = array()) {
			@extract($data);
			ob_start();
			include($pagepath);
			return ob_get_clean();
		}

	}

}
