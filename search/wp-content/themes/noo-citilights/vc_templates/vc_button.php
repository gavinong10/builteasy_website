<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'title'           => '',
    'size' => 'medium',
    'fullwidth' => '',
    'vertical_padding' => '',
    'horizontal_padding' => '',
    'href' => '#',
    'target' => '',
    'icon' => '',
    'icon_color' => '',
    'icon_right' => 'false',
    'icon_only' => 'false',
    'shape' => 'rounded',
    'style' => '',
    'skin' => 'default',
    'text_color' => '',
    'hover_text_color' => '',
    'bg_color' => '',
    'hover_bg_color' => '',
    'border_color' => '',
    'hover_border_color' => '',
), $atts));

$visibility       = ( $visibility      != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class            = ( $class           != ''     ) ? 'btn ' . esc_attr( $class ) : 'btn';
switch($size) {
    case 'x_small':
        $class .= ' btn-xs';
        break;
    case 'small':
        $class .= ' btn-sm';
        break;
    case 'large':
        $class .= ' btn-lg';
        break;
}
$class .=  ($fullwidth  == 'true') ? ' btn-block' : '';
$class .=  ($icon_right == 'true') ? ' icon-right' : '';
$class .=  ($icon_only  == 'true') ? ' icon-only' : '';
$class .=  ($shape      != ''    ) ? ' ' . $shape : '';
$class .=  ($style      != ''    ) ? ' ' . $style : '';

if($skin != '') {
    $class .= " btn-{$skin}";
} else {
    $class .= ' btn-default';
}

$class       .= noo_visibility_class( $visibility );

$id           = ( $id != '' ) ? esc_attr( $id ) : 'noo-button-' . noo_vc_elements_id_increment();
$class        = ( $class != '' ) ? 'class="' . $class . '"' : '';
$href         = ( $href != '' ) ? 'href="' . $href . '"' : 'href="#"';
$target       = ( $target == 'true' ) ? 'target="_BLANK"' : '';

$icon_style   = ( $icon_color != '' ) ? 'style="color: ' . esc_attr( $icon_color ) . ';"' : '';
$icon         = ( $icon != '' ) ? '<i class="fa ' . esc_attr( $icon ) . '" ' . $icon_style . '></i>' : '';
$title        = ( $title != '' ) ? esc_attr( $title ) : '';

$hover_custom_style = '';

if( $skin == 'custom' ) {
    $custom_style       .= ( $text_color != '' ) ? ' color: ' . esc_attr( $text_color ) . ';' : '';
    $hover_custom_style .= ( $hover_text_color != '' ) ? ' color: ' . esc_attr( $hover_text_color ) . ';' : '';
    $custom_style       .= ( $bg_color != '' ) ? ' background-color: ' . esc_attr( $bg_color ) . ';' : '';
    $hover_custom_style .= ( $hover_bg_color != '' ) ? ' background-color: ' . esc_attr( $hover_bg_color ) . ';' : '';
    $custom_style       .= ( $border_color != '' ) ? ' border: 1px solid ' . esc_attr( $border_color ) . ';' : '';
    $hover_custom_style .= ( $hover_border_color != '' ) ? ' border: 1px solid ' . esc_attr( $hover_border_color ) . ';' : '';
}

if( $size == 'custom' ) {
    $custom_style .= ( $vertical_padding != '' ) ? ' padding-top:  ' . esc_attr( $vertical_padding ) . 'px;' . ' padding-bottom:  ' . esc_attr( $vertical_padding ) . 'px;' : '';
    $custom_style .= ( $horizontal_padding != '' ) ? ' padding-left:  ' . esc_attr( $horizontal_padding ) . 'px;' . ' padding-right:  ' . esc_attr( $horizontal_padding ) . 'px;' : '';
}

$box_shadow = '';
$hover_box_shadow = '';
if( $style == 'pressable' ) {
    $darken_primary = darken( noo_get_option( 'noo_site_link_color', noo_default_primary_color() ), '10%' );
    switch( $skin ) {
        case 'primary':
            $box_shadow = $darken_primary;
            $hover_box_shadow = $darken_primary;
            break;
        case 'success':
            $box_shadow = darken( '#5CB85C', '10%' );
            $hover_box_shadow = $darken_primary;
            break;
        case 'info':
            $box_shadow = darken( '#5BC0DE', '10%' );
            $hover_box_shadow = $darken_primary;
            break;
        case 'warning':
            $box_shadow = darken( '#F0AD4E', '10%' );
            $hover_box_shadow = $darken_primary;
            break;
        case 'danger':
            $box_shadow = darken( '#D9534F', '10%' );
            $hover_box_shadow = $darken_primary;
            break;
        case 'custom':
            $box_shadow = ( $bg_color != '' ) ? darken( esc_attr( $bg_color ), '10%' ) : '';
            $hover_box_shadow = ( $hover_bg_color != '' ) ? darken( esc_attr( $hover_bg_color ), '10%' ) : '';
            break;
        case 'link':
        case 'white':
        case 'black':
            $box_shadow = '';
            break;
    }

    if( $box_shadow != '' ) {
        $custom_style .= "-webkit-box-shadow: 0 4px 0 0 {$box_shadow},0 4px 9px rgba(0,0,0,0.15);";
        $custom_style .= "box-shadow: 0 4px 0 0  {$box_shadow},0 4px 9px rgba(0,0,0,0.15);";
    }

    if( $hover_box_shadow != '' ) {
        $hover_custom_style .= "-webkit-box-shadow: 0 4px 0 0 {$hover_box_shadow},0 4px 9px rgba(0,0,0,0.15) !important;";
        $hover_custom_style .= "box-shadow: 0 4px 0 0  {$hover_box_shadow},0 4px 9px rgba(0,0,0,0.15) !important;";
    }
}

// $custom_style  = ( $custom_style != '' ) ? 'style="' . $custom_style . '"' : '';

$html   = array();
// if( $hover_custom_style != '' ) {
    $html[] = "<style scoped>#{$id} { {$custom_style} } #{$id}:hover { {$hover_custom_style} }</style>";
// }
$html[] = "<a {$href} {$target} id=\"{$id}\" {$class} role=\"button\">";
if( $icon != '' ) {
    if ( $icon_only == 'true' ) {
        $title = $icon;
    } elseif( $icon_right == 'true' ) {
        $title = $title . $icon;
    } else {
        $title = $icon . $title;
    }
}

$html[] = $title;
$html[] = "</a>";

echo implode( "\n", $html );