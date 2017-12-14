<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'image'           => '',
    'alt'             => '',
    'style'           => '',
    'href'            => '',
    'target'          => '',
    'link_title'      => '',
), $atts));

if( empty($image) ) {
    return '';
}
$src = wp_get_attachment_image_src( $image, 'fullwidth-fullwidth');
if( empty($src) ) {
    return '';
}

$class            = ( $class       != '' ) ? 'noo-image ' . esc_attr( $class ) : 'noo-image';
$class           .= ( $style       != '' ) ? ' img-' . $style : '';
$visibility       = ( $visibility  != '' ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class           .= noo_visibility_class( $visibility );

$id           = ( $id != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
$src          = ( $src != '' ) ? 'src="' . $src[0] . '"' : '';

$alt          = ( $alt != '' ) ? esc_attr( $alt ) : get_post_meta($image, '_wp_attachment_image_alt', true);
$alt          = ( $alt != '' ) ? $alt : get_the_title( $image );

$alt          = 'alt="' . $alt . '"';
$class        = ( $class != '' ) ? 'class="' . $class . '"' : '';
$custom_style = ( $custom_style    != ''     ) ? ' style="' . $custom_style . '"' : '';

$html   = array();
if( $href != '' ) {
    $target     = ( $target     == 'true' ) ? 'target="_BLANK"' : '';
    $link_title = ( $link_title != ''     ) ? 'title="' . $link_title . '"' : '';
    $html[] = "<a href=\"{$href}\" {$target} {$link_title}>";
}


$html[] = "<img {$src} {$alt} {$id} {$class} {$custom_style}>";
if( $href != '' ) {
    $html[] = "</a>";   
}

echo implode( "\n", $html );
