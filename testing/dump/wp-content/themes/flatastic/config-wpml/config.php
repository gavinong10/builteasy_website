<?php

if (!class_exists('MAD_WC_WPML_CONFIG')) {

	class MAD_WC_WPML_CONFIG {

		public $paths = array();

		protected function path($name, $file = '') {
			return $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
		}
		protected function assetUrl($file) {
			return $this->paths['BASE_URI'] . $this->path('ASSETS_DIR_NAME', $file);
		}

		function __construct() {
			$dir = dirname(__FILE__);

			$this->paths = array(
				'PHP' => $dir . '/' . trailingslashit('php'),
				'ASSETS_DIR_NAME' => 'assets',
				'BASE_URI' => MAD_BASE_URI . trailingslashit('config-wpml')
			);

			$this->add_actions();

			require_once( $this->paths['PHP'] . 'wpml-integration.php' );

//			add_action('pre_import_hook', array(&$this, 'mad_wpml_import_start'));
		}

		public function mad_wpml_import_start() {
			global $sitepress;
			$sitepress->set_setting('setup_complete', 1);
		}

		public function add_actions() {
			add_action( 'wp_enqueue_scripts', array(&$this, 'wpml_register_assets') );
		}

		public function wpml_register_assets() {
			wp_enqueue_style(MAD_PREFIX . 'wpml-mod', $this->assetUrl('css/wpml-mod.css'));
			wp_enqueue_script(MAD_PREFIX . 'wpml-mod', $this->assetUrl('js/wpml-mod.js'), array('jquery'), 1, true);
		}

		public static function wpml_header_languages_list() {
			$languages = array();

			if (function_exists( 'icl_get_languages' )){
				$languages = icl_get_languages('skip_missing=0&orderby=code');
			}

			if (!empty($languages)) { ?>
				<a class="toggle-button" href="#">
					<?php
					foreach($languages as $l) {
						if ($l['active']) {
							if ($l['country_flag_url']) {
								echo '<img src="'. esc_url($l['country_flag_url']) .'" height="12" alt="'. esc_attr($l['language_code']) .'" width="18" />';
							}
							echo icl_disp_language($l['native_name'], $l['translated_name']);
						}
					}
					?>
				</a>
				<?php wpml_languages_list('', 'header_language_list');
			}
		}

	}

	new MAD_WC_WPML_CONFIG();

}


