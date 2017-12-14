<?php

if (!class_exists('MAD_CONTACT_FORM_7_CONFIG')) {

	class MAD_CONTACT_FORM_7_CONFIG {

		public $paths = array();

		function __construct() {
			$this->paths = array(
				'BASE_URI' => MAD_BASE_URI . trailingslashit('config-contact-form-7'),
				'ASSETS_DIR_NAME' => 'assets'
			);

			add_action('init', array(&$this, 'init'));
		}

		public function path($name, $file = '') {
			return $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
		}

		public function assetUrl($file) {
			return $this->paths['BASE_URI'] . $this->path('ASSETS_DIR_NAME', $file);
		}

		public function init() {
			if (!is_admin()) {
				add_action('wp_enqueue_scripts', array(&$this, 'frontend_assets'));
			}
		}

		public function frontend_assets(){
			wp_deregister_style('contact-form-7');
			wp_register_style('contact-form-7',  $this->assetUrl('css/style.css'), array(), WPCF7_VERSION);
		}

	}

	new MAD_CONTACT_FORM_7_CONFIG();
}
