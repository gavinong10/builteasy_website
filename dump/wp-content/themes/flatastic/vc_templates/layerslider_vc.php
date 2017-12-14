<?php
$output = $title = $id = $el_class = '';
extract( shortcode_atts( array(
    'title' => '',
    'id' => '',
    'el_class' => '',
    'css_animation' => ''
), $atts ) );

wp_enqueue_script('waypoints');
if ( $css_animation != '' ) {
	wp_enqueue_script('waypoints');
	$css_animation = ' animate-' . $css_animation;
}

$el_class = $this->getExtraClass($el_class);
$el_class .= $this->getExtraClass($css_animation);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_layerslider_element wpb_content_element' . $el_class, $this->settings['base'], $atts );

$output .= '<div class="'.$css_class.'">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_layerslider_heading'));
$output .= apply_filters('vc_layerslider_shortcode', do_shortcode('[layerslider id="'.$id.'"]'));
$output .= '</div>'.$this->endBlockComment('wpb_layerslider_element')."\n";

echo $output;