<?php

if (!function_exists('madhex2rgba')) {

	function madhex2rgba($color, $opacity = false) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if(empty($color))
			return $default;

		//Sanitize $color if "#" is provided
		if ($color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map('hexdec', $hex);

		//Check if opacity is set(rgba or rgb)
		if($opacity){
			if(abs($opacity) > 1)
				$opacity = 1.0;
			$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
		} else {
			$output = 'rgb('.implode(",",$rgb).')';
		}

		//Return rgb(a) color string
		return $output;
	}
}

if (!function_exists('mad_pre_dynamic_stylesheet')) {

	function mad_pre_dynamic_stylesheet () {

		$options = mad_custom_get_option();

		$color_scheme = $options['color_scheme'];
		if ($color_scheme == null) {
			$color_scheme = 'scheme_default';
		}

		$styles	= $custom = array();

		include(MAD_FRAMEWORK::$path['configPath'] . 'register-color-schemes.php');

		foreach ($options as $key => $option) {
			if (strpos($key, 'styles-') === 0) {
				$explode_key = explode('-', $key);
				$styles[$explode_key[1]] = $option;
			}
		}

		if (isset($mad_color_schemes)) {
			foreach ($mad_color_schemes as $scheme) {
				if (isset($scheme['color_scheme']) && $scheme['color_scheme'] == $color_scheme) {
					foreach ($scheme as $schemekey => $option) {
						if (strpos($schemekey, 'custom-') === 0) {
							$custom_explode = explode('-', $schemekey);
							$custom[$custom_explode[1]] = $option;
						}
					}
				}
			}
		}

		if (empty($styles['body_bg_image'])) $styles['body_bg_image'] = "";

		if ($styles['bg_img'] == 'custom') {
			$body_bg_color = empty($styles['general_body_bg_color']) ? "" : $styles['general_body_bg_color'];
			$url = empty($styles['body_bg_image']) ? "" : "url(" . $styles['body_bg_image'] . ")";
			$styles['body_bg'] = "$body_bg_color $url " . $styles['body_repeat'] . " " . $styles['body_position'] . " " . $styles['body_attachment'];
		} else {
			$body_bg_color = empty($styles['general_body_bg_color']) ? "" : $styles['general_body_bg_color'];
			$styles['body_bg'] = "$body_bg_color";
		}

		extract($styles);
		extract($custom);

		$color_scheme = mad_custom_get_option('color_scheme');
		if ($color_scheme) {
			$color_scheme = '.' . $color_scheme;
		}

		require(MAD_BASE_PATH . 'css/dynamic-global-css.php');
	}
}
