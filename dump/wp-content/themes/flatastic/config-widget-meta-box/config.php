<?php

if (!class_exists('MAD_WIDGETS_META_BOX')) {

	class MAD_WIDGETS_META_BOX {

		public $paths = array();

		public function path($name, $file = '') {
			return $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
		}

		public function assetUrl($file)  {
			return $this->paths['BASE_URI'] . $this->path('ASSETS_DIR_NAME', $file);
		}

		function __construct () {
			$dir = dirname(__FILE__);

			$this->paths = array(
				'PHP' => $dir . '/' . trailingslashit('php'),
				'ASSETS_DIR_NAME' => 'assets',
				'BASE_URI' => MAD_BASE_URI . trailingslashit('config-widget-meta-box')
			);

			$this->init();
		}

		public function init() {
			if (is_admin()) {
				add_action('admin_init', array(&$this, 'add_meta_box') );
				add_action('save_post', array(&$this, 'save_post'));
				add_action('load-post.php', array($this, 'admin_enqueue_scripts'));
				add_action('load-post-new.php', array($this, 'admin_enqueue_scripts'));
				add_action('admin_print_scripts', array(&$this, 'add_json') );
			} else {

			}
		}

		public function admin_enqueue_scripts() {
			$css_file = $this->assetUrl('css/widget-meta-box.css');
			$js_file = $this->assetUrl('js/widget-meta-box.js');

			wp_enqueue_style(MAD_PREFIX . 'widget-meta-box', $css_file);
			wp_enqueue_script(MAD_PREFIX . 'widget-meta-box', $js_file, array('jquery'), 1, true);
		}

		public function add_meta_box() {
			add_meta_box("widets_footer_meta_box", __("Widgets Row Footer", MAD_BASE_TEXTDOMAIN), array(&$this, 'draw_widgets_meta_box' ), "page", "normal", "low");
		}

		public function add_json() {
			$output = "\n<script type='text/html' id='tmpl-options-hidden'>";
			$output .= json_encode($this->columns_grid());
			$output .= "\n</script>\n";
			echo $output;
		}

		public function columns_grid() {
			return array( "1" => array( array( "12" ) ) ,
						  "2" => array( array( "6", "6" ) ) ,
						  "3" => array( array( "4", "4", "4" ) , ) ,
						  "4" => array( array( "3", "3", "3", "3" ) ),
						  "5" => array( array( "3", "2", "2", "2", "3" ) ),
						  "6" => array( array( "2", "2", "2", "2", "2", "2" ) )
			);
		}

		public function get_page_settings($post_id) {
			$custom = get_post_custom($post_id);

			$data = array();
			$data['footer_row_top_show'] = @$custom["footer_row_top_show"][0];
			$data['footer_row_bottom_show'] = @$custom["footer_row_bottom_show"][0];

			$data['footer_row_top_columns_variations'] = @$custom["footer_row_top_columns_variations"][0];
			$data['footer_row_bottom_columns_variations'] = @$custom["footer_row_bottom_columns_variations"][0];

			$data['get_sidebars_top_widgets'] = @$custom["get_sidebars_top_widgets"][0];
			$data['get_sidebars_bottom_widgets'] = @$custom["get_sidebars_bottom_widgets"][0];

			$footer_row_top_show = (mad_custom_get_option('show_row_top_widgets') != '0') ? 'yes' : 'no';
			$footer_row_bottom_show = (mad_custom_get_option('show_row_bottom_widgets') != '0') ? 'yes' : 'no';

			$footer_row_top_columns_variations = mad_custom_get_option('footer_row_top_columns_variations');
			$footer_row_bottom_columns_variations = mad_custom_get_option('footer_row_bottom_columns_variations');

			if ($custom["footer_row_top_show"][0] == null) {
				$data['footer_row_top_show'] = $footer_row_top_show;
			}

			if ($custom["footer_row_bottom_show"][0] == null) {
				$data['footer_row_bottom_show'] = $footer_row_bottom_show;
			}

			if ($custom['footer_row_top_columns_variations'][0] == null) {
				$data['footer_row_top_columns_variations'] = $footer_row_top_columns_variations;
			}

			if ($custom['footer_row_bottom_columns_variations'][0] == null) {
				$data['footer_row_bottom_columns_variations'] = $footer_row_bottom_columns_variations;
			}

			if ($data['get_sidebars_top_widgets'] == null) {
				$data['get_sidebars_top_widgets'] = 'a:6:{i:0;s:21:"Footer Row - widget 1";i:1;s:21:"Footer Row - widget 2";i:2;s:21:"Footer Row - widget 3";i:3;s:21:"Footer Row - widget 4";i:4;s:21:"Footer Row - widget 5";i:5;s:21:"Footer Row - widget 6";}';
			}

			if ($data['get_sidebars_bottom_widgets'] == null) {
				$data['get_sidebars_bottom_widgets'] = 'a:6:{i:0;s:21:"Footer Row - widget 6";i:1;s:21:"Footer Row - widget 7";i:2;s:21:"Footer Row - widget 8";i:3;s:21:"Footer Row - widget 9";i:4;s:22:"Footer Row - widget 10";i:5;s:22:"Footer Row - widget 11";}';
			}

			$data['get_sidebars'] = $this->get_registered_sidebars();
			$data['columns'] = 6;
			return $data;
		}

		public function get_registered_sidebars() {
			$registered_sidebars = MAD_HELPER::get_registered_sidebars();
			$registered_footer_sidebars = array();

			foreach($registered_sidebars as $key => $value) {
				if (strpos($key, 'Footer Row') !== false) {
					$registered_footer_sidebars[$key] = $value;
				}
			}
			return $registered_footer_sidebars;
		}

		public function draw_widgets_meta_box() {
			global $post;
			$data = $this->get_page_settings($post->ID);
			echo $this->draw_page($this->path('PHP', 'meta_box.php'), $data);
		}

		public function save_post($post_id) {
			global $post;

			if (is_object($post) AND !empty($_POST)) {
				update_post_meta($post_id, "footer_row_top_show", @$_POST["footer_row_top_show"]);
				update_post_meta($post_id, "footer_row_bottom_show", @$_POST["footer_row_bottom_show"]);

				update_post_meta($post_id, "footer_row_top_columns_variations", @$_POST["footer_row_top_columns_variations"]);
				update_post_meta($post_id, "footer_row_bottom_columns_variations", @$_POST["footer_row_bottom_columns_variations"]);

				update_post_meta($post_id, "get_sidebars_top_widgets", @$_POST["get_sidebars_top_widgets"]);
				update_post_meta($post_id, "get_sidebars_bottom_widgets", @$_POST["get_sidebars_bottom_widgets"]);
			}
		}

		public function draw_page($pagepath, $data = array()) {
			@extract($data);
			ob_start();
			include $pagepath;
			return ob_get_clean();
		}

	}

	new MAD_WIDGETS_META_BOX();

}