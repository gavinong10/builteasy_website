<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
), $atts));

$visibility       = ( $visibility      != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class            = ( $class           != ''     ) ? "noo-text-block " . esc_attr( $class ) : 'noo-text-block';
$class           .= noo_visibility_class( $visibility );

$class = ( $class != '' ) ? ' class="' . $class . '"' : '';
$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '"' : '';

$html = array();
$html[] = "<div {$id} {$class} {$custom_style}>";
$html[] = noo_handler_shortcode_content( $content, true );
$html[] = '</div>';

echo implode( "\n", $html );