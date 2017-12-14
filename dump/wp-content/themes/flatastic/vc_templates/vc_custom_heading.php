<?php
/**
 * @var $this WPBakeryShortCode_VC_Custom_heading
 */

$link = $output = $text = $google_fonts = $animations = $font_container = $el_class = $css = $google_fonts_data = $font_container_data = $with_bottom_border = '';
extract( $this->getAttributes( $atts ) );
extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

if (isset($atts['css_animation']) && !empty($atts['css_animation'])) {
	$animations = $this->getExtraClass(MAD_VC_CONFIG::getCSSAnimation($atts['css_animation']));
}

$add_border = (isset($atts['with_bottom_border']) && $atts['with_bottom_border'] == 'on') ? $this->getExtraClass('with_border') : '';
$heading_color = (isset($atts['heading_color']) && !empty($atts['heading_color'])) ? $atts['heading_color'] : '';
$font_size = (isset($atts['font_size']) && !empty($atts['font_size'])) ? $atts['font_size'] : '';
$font_weight = (isset($atts['font_weight']) && !empty($atts['font_weight'])) ? $atts['font_weight'] : '';
$font_style = (isset($atts['font_style']) && !empty($atts['font_style'])) ? $atts['font_style'] : '';
$text_align = (isset($atts['text_align']) && !empty($atts['text_align'])) ? $this->getExtraClass($atts['text_align']) : '';

$settings = get_option( 'wpb_js_google_fonts_subsets' );
$subsets = '';
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
}
if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
}

$output .= '<div class="' . esc_attr( $css_class ) . esc_attr($animations) . esc_attr( $add_border ) . esc_attr($text_align). '" >';
$style = '';
if ( !empty($heading_color) ) {
	$style .= "color: {$heading_color};";
}

if ( !empty($font_size) ) {
	$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
	$regexr = preg_match( $pattern, $font_size, $matches );
	$value = isset( $matches[1] ) ? (float) $matches[1] : (float) $font_size;
	$unit = isset( $matches[2] ) ? $matches[2] : 'px';
	$value = $value . $unit;
	$style .= "font-size: {$value};";
}

if ( !empty($font_weight) ) {
	if (is_numeric($font_weight)) {
		$style .= "font-weight: {$font_weight};";
	}
}

if ( !empty($font_style) ) {
	$style .= "font-style: {$font_style};";
}


if ( ! empty( $styles ) ) {
	$style .= esc_attr( implode( ';', $styles ) );
}

if ( !empty($style) ) {
	$style = "style='{$style}'";
}

$output_text = $text;
if ( ! empty( $link ) ) {
	$link = vc_build_link( $link );
	$output_text = '<a href="' . esc_attr( $link['url'] ) . '"'
		. ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' )
		. ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' )
		. '>' . $text . '</a>';
}

$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' >';
$output .= $output_text;
$output .= '</' . $font_container_data['values']['tag'] . '>';
$output .= '</div>';

echo $output;