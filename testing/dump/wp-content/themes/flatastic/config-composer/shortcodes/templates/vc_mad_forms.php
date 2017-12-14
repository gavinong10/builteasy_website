<?php

class WPBakeryShortCode_VC_mad_forms extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'group' => ''
		), $atts, 'vc_mad_forms');

		$html = $this->html();

		return $html;
	}

	public function html() {
		$output = '';
		extract($this->atts);
		if (empty($group)) {
			return __( '<strong>Forms:</strong> No forms selected to show.', MAD_BASE_TEXTDOMAIN);
		}
		$output .=  do_shortcode('[dhvc_form id="'. $group .'"]');
		return $output;
	}

}