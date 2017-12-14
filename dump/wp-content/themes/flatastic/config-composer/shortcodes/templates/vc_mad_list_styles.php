<?php

class WPBakeryShortCode_VC_mad_list_styles extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'list_type' => '',
			'list_unordered_styles' => '',
			'list_ordered_styles' => '',
			'values' => '',
			'css_animation' => ''
		), $atts, 'vc_mad_list_styles');

		return $this->html();
	}

	public function html() {

		$output = $list = $list_items = $list_type = $list_styles = $list_ordered_styles = $list_unordered_styles = $values = $css_animation = '';

		extract($this->atts);

		if (!empty($values)) {
			$values = explode('|', $values);

			if (is_array($values)) {
				foreach ($values as $value) {
					$list_items .= "<li>{$value}</li>";
				}

				if ($list_type == 'unordered') {
					$list_styles = $list_unordered_styles;
				} else if ($list_type == 'ordered') {
					$list_styles = $list_ordered_styles;
				}

				$list .= '<ul class="list-styles '. $list_styles .'">'. $list_items .'</ul>';
			}
		}

		$animations = $this->getExtraClass($this->getCSSAnimation($css_animation));
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_list_styles wpb_content_element', $this->settings['base'], $this->atts );

		$output .= "\n\t" . '<div class="' . $css_class . $animations . '">';
		$output .= "\n\t\t" . '<div class="wpb_wrapper">';
			$output .= "\n\t\t\t" . $list;
		$output .= "\n\t\t" . '</div> ' . $this->endBlockComment('.wpb_wrapper');
		$output .= "\n\t" . '</div> ' . $this->endBlockComment('.wpb_list_styles');

		return $output;
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script('waypoints');
			$output = ' animate-' . $css_animation;
		}
		return $output;
	}

}