<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var string $el_width
 * @var string $style
 * @var string $color
 * @var string $border_width
 * @var string $accent_color
 * @var string $el_class
 * @var string $align
 * @var string $css
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Separator
 */
$el_width = $style = $color = $border_width = $accent_color = $el_class = $align = $css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

echo do_shortcode( '[vc_text_separator layout="separator_no_text" align="' .
	$align . '" style="' .
	$style . '" color="' .
	$color . '" accent_color="' .
	$accent_color . '" border_width="' .
	$border_width . '" el_width="' .
	$el_width . '" el_class="' .
	$css_class . '" ]' );
