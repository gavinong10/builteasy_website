<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $type
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_TweetMeMe
 */
$type = $css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'twitter-share-button';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$output = '<a href="http://twitter.com/share" class="'
	. esc_attr( $css_class ) . '" data-count="'
	. $type . '">'
	. __( 'Tweet', 'js_composer' ) . '</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';

echo $output;