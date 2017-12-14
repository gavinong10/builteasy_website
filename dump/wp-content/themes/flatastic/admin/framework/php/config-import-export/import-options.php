<?php

if (!class_exists('mad_wp_import_options')) {

	class mad_wp_import_options {

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

	}

}
