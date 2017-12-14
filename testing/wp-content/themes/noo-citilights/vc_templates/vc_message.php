<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'heading'         => '',
    'type'            => 'success',
    'dismissible'     => ''
), $atts));

$class            = ( $class         != ''    ) ? 'noo-message alert ' . esc_attr( $class ) : 'noo-message alert';
$class           .= " alert-{$type}";
$class           .= ( $dismissible   == 'true') ? ' alert-dimissible' : '';
$visibility       = ( $visibility    != ''    ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class           .= noo_visibility_class( $visibility );

$id           = ( $id != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
$class        = ( $class != '' ) ? 'class="' . $class . '"' : '';
$custom_style = ( $custom_style    != ''     ) ? ' style="' . $custom_style . '"' : '';

$html   = array();
$html[] = "<div {$id} {$class} {$custom_style} role=\"alert\">";
if( $dismissible == 'true' ) {
	$html[] = '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">' . __( 'Close', 'noo' ) . '</span></button>';
}
if( $heading != '' ) {
	$html[] = '  <h4>' . esc_attr( $heading ) . '</h4>';
}

$html[] = noo_handler_shortcode_content( $content, true );
$html[] = "</div>";

echo implode( "\n", $html );
