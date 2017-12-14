<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'title'           => '',
    'sidebar_id'      => ''
), $atts));

if ( $sidebar_id == '' )
	return '';

$class         = ( $class != '' ) ? 'noo-widget-area-wrapper ' . esc_attr( $class ) : 'noo-widget-area-wrapper';
$visibility    = ( $visibility != '' ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class        .= noo_visibility_class( $visibility );

$id            = ( $id != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
$class         = ( $class != '' ) ? 'class="' . esc_attr( $class ) . '"' : '';
$custom_style  = ($custom_style != '' ) ? 'style="' . esc_attr( $custom_style ) . '"' : '';

ob_start();
dynamic_sidebar($sidebar_id);
$sidebar_value = ob_get_contents();
ob_end_clean();

$sidebar_value = trim($sidebar_value);
$sidebar_value = (substr($sidebar_value, 0, 3) == '<li' ) ? '<ul>'.$sidebar_value.'</ul>' : $sidebar_value;

$html    = array();
$html[] .= "<div {$id} {$class} {$custom_style} >";
$html[] .= '  <div class="noo-widget-area">';
if(!empty($title))
	$html[] .= '    <h3 class="noo-widget-area-title">' . esc_attr( $title ) . '</h3>';
$html[] .= $sidebar_value;
$html[] .= '  </div>';
$html[] .= '</div>';

echo implode( "\n", $html );