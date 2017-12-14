<?php

class WPBakeryShortCode_VC_mad_pricing_box extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => 'Free',
			'currency' => '$',
			'price' => '15',
			'time' => 'per month',
			'features' => 'Up to 50 users | Limited team members | Limited disk space | Custom Domain | PayPal Integration | Basecamp Integration',
			'add_hot' => 'off',
			'box_style' => 'bg_color_dark',
			'header_bg_color' => '#292f38',
			'main_bg_color' => '#323a45',
			'link' => '',
			'css_animation' => ''
		), $atts, 'vc_mad_pricing_box');

		return $this->html();
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script('waypoints');
			$output = ' animate-' . $css_animation;
		}
		return $output;
	}

	public function html() {

		$output = $el_class = $link = $add_hot = $css_animation = $title = $currency = $price = $time = $features = $add_badge = $box_style = $features = $header_bg_color = $main_bg_color = '';

		extract($this->atts);

		$el_class = $this->getExtraClass($el_class);
		$box_style = $this->getExtraClass($box_style);
		$animations = $this->getExtraClass($this->getCSSAnimation($css_animation));

		if ($add_hot == 'on') {
			$el_class .= $this->getExtraClass('active');
		}

		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $box_style . $animations, $this->settings['base']);

		$link = ($link == '||') ? '' : $link;
		$link = vc_build_link($link);
		$a_href = $link['url'];
		$a_title = $link['title'];
		($link['target'] != '') ? $a_target = $link['target'] : $a_target = '_self';

		if (trim($box_style) == 'custom') {
			$header_bg_color = "style='background-color: {$header_bg_color};'";
			$main_bg_color = "style='background-color: {$main_bg_color};'";

		} else {
			$header_bg_color = "";
			$main_bg_color = "";
		}

		$title = '<header '. $header_bg_color .' class="pricing-header">'. $title .'</header>';
			$price = '<dt>'. $currency . $price .'</dt>';
			$time =  '<dd>'. $time .'</dd>';

		if ($features != '') {
			$features = explode('|', $features);
			$feature_list = $find_str = '';

			if (is_array($features)) {
				foreach ($features as $feature) {
					$feature = trim($feature);
					$find_str = strpos($feature, '-');
					$find_str = $find_str === 0 ? 1 : 0;
					if ($find_str) {
						$feature_class = 'price-icon-times';
						$feature = substr($feature, 1);
					} else {
						$feature_class = 'price-icon-check';
					}
					$feature_list .= "<li class='{$feature_class}'>{$feature}</li>";
				}
			}
		}

		$output .= "<div class='pricing-box {$el_class}'>";
			$output .= "<div class='pricing-table {$css_class}'>";
				if ($add_hot == 'on') {
					$output .= '<div class="price-hot"></div>';
				}
				$output .= $title;
				$output .= "<div {$main_bg_color} class='price-box'>";
					$output .= '<dl>';
						$output .= $price;
						$output .= $time;
					$output .= '</dl>';
				$output .= '</div>';

				$output .= '<ul class="features-list">';
					$output .= $feature_list;
				$output .= '</ul>';

				$output .= "<footer {$main_bg_color} class='pricing-footer'>";
					$output .= '<a title="'. esc_attr($a_title) .'" target="'. $a_target .'" href="'. esc_url($a_href) .'">'. $a_title .'</a>';
				$output .= '</footer>';

			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

}