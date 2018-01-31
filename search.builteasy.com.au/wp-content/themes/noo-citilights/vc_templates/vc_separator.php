<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'type'            => 'line',
    'title'           => '',
    'size'            => 'fullwidth',
    'position'        => 'center',
    'color'           => '',
    'thickness'       => '2',
    'space_before'    => '20',
    'space_after'     => '20',
), $atts));

$visibility       = ( $visibility      != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class            = ( $class           != ''     ) ? "noo-separator {$type} " . esc_attr( $class ) : "noo-separator {$type}";
$class           .= noo_visibility_class( $visibility );

$class .=' separator-align-'.$position;

$custom_style .= ( $size         == 'half' ) ? ' width: 50%;' : ' width: 100%;';
$custom_style .= ( $space_before != ''  ) ? ' padding-top: ' . $space_before . 'px;' : '';
$custom_style .= ( $space_after  != ''  ) ? ' padding-bottom: ' . $space_after . 'px;' : '';

$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '"' : '';

$line_style   =  ($color     != '' ) ? ' border-top-color: '. $color . ';' : '';
$line_style   .= ($thickness != '' ) ? ' border-top-width: '. $thickness . 'px;' : '';

$html = array();
$html[] = "<div class=\"{$class}\" {$custom_style}>";
$html[] = '  <span class="noo-sep-holder-l"><span style="' . esc_attr($line_style) . '" class="noo-sep-line"></span></span>';
if( $type == 'line-with-text' && $title != '' ) {
	$html[] = '<h4>' . $title . '</h4>';
}

$html[] = '  <span class="noo-sep-holder-r"><span style="' . esc_attr($line_style) . '" class="noo-sep-line"></span></span>';
$html[] = '</div>';

echo implode( "\n", $html );