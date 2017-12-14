<?php

class WPBakeryShortCode_VC_mad_dropcap extends WPBakeryShortCode {

	public $atts = array();
	public $content = '';

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'type' => '',
			'letter' => '',
		), $atts, 'vc_mad_dropcap');

		$this->content = $content;
		$html = $this->html();

		return $html;
	}

	public function html() {

		$output = $class = $dropcap = "";

		extract($this->atts);

		$class .= ($type != '') ? ' vc_dropcap_' . $type : '';
		$dropcap .= ($letter != '') ? '<span class="dropcap-letter">'. $letter .'</span>' : '';

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_dropcap wpb_content_element' . $class, $this->settings['base'], $this->atts );

		$output .= "<div class='{$css_class}'>";
			$output .= $dropcap;
			$output .= wpb_js_remove_wpautop($this->content, true);
		$output .= '</div>' . $this->endBlockComment('dropcap') . "\n";

		return $output;
	}

}