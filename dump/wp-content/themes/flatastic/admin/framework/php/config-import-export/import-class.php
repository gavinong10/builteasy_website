<?php

if (!class_exists('mad_wp_import')) {

	class mad_wp_import extends WP_Import {

		function save_settings($option_file) {

			if ($option_file) @include_once($option_file);
			if (!isset($options)) { return false; }

			$options = unserialize(base64_decode($options));

			global $mad_global_data;

			if (is_array($options)) {
				foreach($mad_global_data->option_pages as $page) {
					$database_option[$page['parent']] = $this->import_values($options[$page['parent']]);
				}
			}

			if (!empty($database_option)) {
				update_option($mad_global_data->option_prefix, $database_option);
			}

			if (!empty($widget_settings)) {
				$widget_settings = unserialize(base64_decode($widget_settings));
				if (!empty($widget_settings)) {
					foreach($widget_settings as $key => $setting) {
						update_option( $key, $setting );
					}
				}
			}

			if (!empty($sidebar_settings)) {
				$sidebar_settings = unserialize(base64_decode($sidebar_settings));
				if (!empty($sidebar_settings) && is_array($sidebar_settings)) {
					update_option('mad_sidebars', $sidebar_settings );
				}
			}

			if (!empty($woof_settings)) {
				$woof_settings = unserialize(base64_decode($woof_settings));
				if (!empty($woof_settings)) {
					update_option('woof_settings', $woof_settings);
				}
			}

			$this->twitter_api_config();

		}

		public function importSliders($path) {

			if (defined('RS_PLUGIN_PATH')) {

				$rev_path = $path . '/revslider'; $result = array();

				$handler = opendir($rev_path);
				if ($handler) {
					while ($file = readdir($handler)) {
						if ($file != "." AND $file != "..") {
							$result[] = $file;
						}
					}
				}
				closedir($handler);

				if (!empty($result)) {
					foreach ($result as $zip_path) {
						$slider = new RevSlider();
						$slider->importSliderFromPost(true, true, trailingslashit($rev_path) . $zip_path);
					}
				}

				if (!strpos($path, 'onepage')) {

					$sliders_needle_revolution = array(
						'full_width_image_slideshow.zip',
						'full_width_video_slideshow.zip'
					);

					foreach ($sliders_needle_revolution as $zip_path) {
						$slider = new RevSlider();
						$slider->importSliderFromPost(true, true, RS_PLUGIN_PATH . 'demo/' . $zip_path);
					}

				}

			}

			if (function_exists('layerslider')) {

				$sliders_needle = array();
				include LS_ROOT_PATH.'/classes/class.ls.importutil.php';

				if (strpos($path, 'default')) {
					$sliders_needle = array(
						'easy', 'header-after-content', 'full-width', 'landing', 'homepage', 'homepage-full-width',
					);
				} elseif (strpos($path, 'construction')) {
					$sliders_needle = array(
						'header-after-content-construction', 'landing-construction', 'homepage-construction'
					);
				} elseif (strpos($path, 'interior')) {
					$sliders_needle = array(
						'header-after-content-interior', 'landing-interior', 'home-page-interior'
					);
				} elseif (strpos($path, 'corporate')) {
					$sliders_needle = array(
						'easy', 'full-width-2-corporate', 'homepage', 'header-after-content', 'landing-corporate'
					);
				}

				if (!empty($sliders_needle)) {
					foreach ($sliders_needle as $slider) {
						if ($item = LS_Sources::getDemoSlider($slider)) {
							if (file_exists($item['file'])) {
								new LS_ImportUtil($item['file']);
							}
						}
					}
				}

			}

		}

		public function twitter_api_config() {
			$conf = array (
				'consumer_key' => '9JH7de9na8JnUjSADwpG0fJ65',
				'consumer_secret' => 'uamiAj41b46Razt38TJVgGKzBOIwOl07Pn8W53296uvReVni9N',
				'request_secret' => '',
				'access_key' => '308471286-eKRNX77anFKPKxUWbX0wRAT95GWgjnaGko5YGBpM',
				'access_secret' => 'VtRgip39ajULJ9R5oIiclxsG9Pu3F38kz3PLHeGM4fbRp'
			);

			foreach( $conf as $key => $val ) {
				update_option( 'twitter_api_' . $key, $val );
			}
		}

		public function import_values($elements) {

			$values = array();

			foreach ($elements as $element) {
				if (isset($element['id'])) {

					if (!isset($element['std'])) $element['std'] = "";

					if ($element['type'] == 'select' && !is_array($element['options'])) {
						$values[$element['id']] = $this->getSelectValues($element['options'], $element['std']);
					} else {
						$values[$element['id']] = $element['std'];
					}
				}
			}

			return $values;
		}

		public function getSelectValues($type, $name) {
			switch ($type) {
				case 'page':
				case 'post':
					$post_page = get_page_by_title($name, 'OBJECT', $type);
					if (isset($post_page->ID)) {
						return $post_page->ID;
					}
					break;
				case 'range':
					return $name;
					break;
			}
		}

		public function menu_install() {

			$get_menus = wp_get_nav_menus();

			if (!empty($get_menus)) {
				$nav_needle = array('primary' => 'Primary Menu');
				foreach ($get_menus as $menu) {
					if (is_object($menu) && in_array($menu->name, $nav_needle) ) {
						$key = array_search($menu->name, $nav_needle);
						if ($key) {
							$locations[$key] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $locations );

			$this->mad_mega_menu_options_backup();
		}

		public function mad_mega_menu_options_backup() {
			global $mega_main_menu;
			$file = MAD_BASE_URI . 'admin/mega-main-menu-backup.txt';
			$backup_file_content = mad_mm_common::get_url_content( $file );

			if ( $backup_file_content !== false && ( $options_backup = json_decode( $backup_file_content, true ) ) ) {
				if ( isset( $options_backup['last_modified'] ) ) {
					$options_backup['last_modified'] = time() + 30;
					update_option( $mega_main_menu->constant[ 'MM_OPTIONS_NAME' ], $options_backup );
				}
			}
		}

	}

}