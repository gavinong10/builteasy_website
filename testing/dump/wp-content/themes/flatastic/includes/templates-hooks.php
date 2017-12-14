<?php

if (!class_exists('MAD_TEMPLATES_HOOKS')) {

	class MAD_TEMPLATES_HOOKS {

		function __construct() {
			add_action('wp_head', array(&$this, 'hook_wp_head'));
			$this->add_actions();
		}

		public function hook_wp_head() {
			$post_id = mad_post_id();

			$header_layout = rwmb_meta('mad_header_layout', '', $post_id);
			if (empty($header_layout)) {
				$header_layout = mad_custom_get_option('header_layout');
			}

			$this->get_type_header($header_layout);
			$this->footer_hooks();
		}

		public function add_actions() {
			add_action('menu_wrap_after', 'mad_header_after_breadcrumbs', 10);
			add_action('header_after', 'mad_portfolio_flex_slider', 10);
			add_action('header_after', 'mad_header_after_page_content', 10);
		}

		public function get_type_header($type) {
			switch($type) {
				case 'type-2':
					$this->header_type_2_hooks();
				break;
				case 'type-3':
					$this->header_type_3_hooks();
				break;
				case 'type-4':
					$this->header_type_4_hooks();
				break;
				case 'type-5':
					$this->header_type_5_hooks();
				break;
				case 'type-6':
					$this->header_type_6_hooks();
					break;
				default:
					$this->header_default_hooks();
				break;
			}
		}

		function header_before_container() { echo '<div class="container">'; }
		function header_after_container()  { echo '</div>'; }

		public static function header_cart_dropdown() {
			if ( !defined('MAD_WOO_CONFIG') ) return;
			echo '<div class="col-sm-8 col-md-6">';
				echo MAD_DROPDOWN_CART::mad_woocommerce_cart_dropdown();
			echo '</div>';
		}

		public function header_cart_dropdown_type_2() {
			if ( !defined('MAD_WOO_CONFIG') ) return;
				echo '<div class="alignright">';
					echo MAD_DROPDOWN_CART::mad_woocommerce_cart_dropdown_type_3();
				echo '</div>';
		}

		public static function header_cart_dropdown_type_3() {
			if ( !defined('MAD_WOO_CONFIG') ) return;
			$html = MAD_DROPDOWN_CART::mad_woocommerce_cart_dropdown_type_3();
			echo $html;
		}

		public function header_type_2_nav_cart_dropdown() {
			echo '<div class="col-lg-10 clearfix">';
			mad_navigation_type_2();
			$this->header_cart_dropdown_type_2();
			echo '</div>';
		}

		public function header_type_5_nav() {
			echo '<div class="col-lg-10 clearfix">';
			mad_navigation_type_5();
			echo '</div>';
		}

		public function header_default_hooks() {
			add_action('header_in_before', 'mad_header_default_top_part', 10);
			add_action('header_in_prepend', 'mad_header_logo', 10);
			add_action('header_in_append', array(&$this, 'header_cart_dropdown'), 10);

			add_action('navigation_after', array(&$this, 'header_before_container'), 9);
			add_action('navigation_after', 'mad_navigation_default', 10);
			add_action('navigation_after', array(&$this, 'header_after_container'), 10);
			add_action('navigation_after', 'mad_searchform_default', 10);
		}

		public function header_type_2_hooks() {
			add_action('header_in_before', 'mad_header_type_2_top_part', 10);
			add_action('header_in_prepend', 'mad_header_logo_type_2', 10);

			add_action('header_in_append', array(&$this, 'header_type_2_nav_cart_dropdown'), 10);
		}

		public function header_type_3_hooks() {
			add_action('header_in_before', 'mad_header_type_3_top_part', 10);
			add_action('header_in_prepend', 'mad_header_logo', 10);
			add_action('header_in_append', 'mad_searchform_type_3', 10);

			add_action('header_in_after', array(&$this, 'header_before_container'), 10);
				add_action('navigation_after', 'mad_navigation_type_3', 10);
				add_action('navigation_after', array(&$this, 'header_cart_dropdown_type_3'), 10);
			add_action('menu_wrap_after', array(&$this, 'header_after_container'), 10);
		}

		public function header_type_4_hooks() {
			add_action('header_in_before', 'mad_header_type_4_top_part', 10);
			add_action('header_in_prepend', 'mad_header_in_prepend_type_4', 10);
			add_action('header_in_prepend', 'mad_header_logo_type_4', 10);
			add_action('navigation_after', 'mad_navigation_type_4', 10);
		}

		public function header_type_5_hooks() {
			add_action('header_in_before', 'mad_header_type_5_top_part', 10);
			add_action('header_in_prepend', 'mad_header_logo_type_2', 10);

			add_action('header_in_append', array(&$this, 'header_type_5_nav'), 10);
		}

		public function header_type_6_hooks() {
			add_action('header_in_before', 'mad_header_type_4_top_part', 10);
			add_action('header_in_prepend', 'mad_header_logo_type_6', 10);

			add_action('header_in_after', array(&$this, 'header_before_container'), 10);
				add_action('navigation_after', 'mad_navigation_type_3', 10);
				add_action('navigation_after', array(&$this, 'header_cart_dropdown_type_3'), 10);
			add_action('menu_wrap_after', array(&$this, 'header_after_container'), 10);
		}

		public function footer_hooks() {
			add_action('footer_in_top_part', 'mad_footer_in_top_part_widgets', 10);
			add_action('footer_in_bottom_part', 'mad_footer_in_bottom_part', 10);
		}

	}

	new MAD_TEMPLATES_HOOKS();
}
