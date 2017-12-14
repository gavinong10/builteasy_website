<?php

class WPBakeryShortCode_VC_mad_gmaps extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'link' => 'https://maps.google.com/maps?q=New+York&hl=en&sll=40.686236,-73.995409&sspn=0.038009,0.078192',
			'align' => '',
			'link' => '<iframe src="https://mapsengine.google.com/map/u/0/embed?mid=z4vjH8i214vQ.kj0Xiukzzle4" width="640" height="480"></iframe>',
			'width' => '',
			'height' => '',
			'zoom' => 14, //depreceated from 4.0.2
			'type' => 'm', //depreceated from 4.0.2
			'bubble' => '' //depreceated from 4.0.2
		), $atts, 'vc_mad_gmaps');

		return $this->html();
	}

	public function html() {

		$output = $title = $link = $width = $height = $zoom = $type = $bubble = '';

		extract($this->atts);

		if ($link == '') {
			return null;
		}

		$align = !empty($align) ? $align : '';
		$link = trim(vc_value_from_safe($link));
		$bubble = ( $bubble != '' && $bubble != '0' ) ? '&amp;iwloc=near' : '';
		$width = str_replace(array( '%', 'px' ), array( '', '' ), $width);
		$height = str_replace(array( '%', 'px' ), array( '', '' ), $height);

		$el_class = ($height == '') ? ' vc_map_responsive' : '';

		if (is_numeric($width)) {
			$link = preg_replace('/width="[0-9]*"/', 'height="' . $width . '"', $link);
		}
		if (empty($width)) $width = '100%';

		if (is_numeric($height)) {
			$link = preg_replace('/height="[0-9]*"/', 'height="' . $height . '"', $link);
		}

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_gmaps_widget wpb_content_element' . $el_class, $this->settings['base'], $this->atts );

		$output .= '<div class="'. $css_class .' '. $align .'">';
			$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_map_heading' ) );
			$output .= '<div class="wpb_wrapper">';
				$output .= '<div class="wpb_map_wraper">';
					$output .= '<div class="image-overlay">';
						$output .= '<div class="photoframe">';
							if (preg_match('/^\<iframe/', $link)) {
								$output .= $link;
							} else {
								$output .= '<iframe width="'. $width .'" height="' . $height . '" src="' . $link . '&amp;t=' . $type . '&amp;z=' . $zoom . '&amp;output=embed' . $bubble . '"></iframe>';
							}
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .=	'</div>';
		$output .= '</div>';

		return $output;
	}

}