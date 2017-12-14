<?php
extract(shortcode_atts(array(
    'visibility'         => 'all',
    'class'              => '',
    'id'                 => '',
    'custom_style'       => '',
    'title'              => '',
    'message'            => '',
    'alignment'          => 'center',
    'button_text'        => '',
    'size'               => 'medium',
    'fullwidth'          => '',
    'vertical_padding'   => '',
    'horizontal_padding' => '',
    'href'               => '#',
    'target'             => '',
    'icon'               => '',
    'icon_color'         => '',
    'icon_right'         => 'false',
    'icon_only'          => 'false',
    'shape'              => 'square',
    'style'              => '',
    'skin'               => 'default',
    'text_color'         => '',
    'hover_text_color'   => '',
    'bg_color'           => '',
    'hover_bg_color'     => '',
    'border_color'       => '',
    'hover_border_color' => '',
), $atts));

$class            = ( $class      != '' ) ? 'noo-cta-btn ' . esc_attr( $class ) : 'noo-cta-btn';
$class           .= ( $alignment  != '' ) ? ' text-' . $alignment : '';
$visibility       = ( $visibility != '' ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class           .= noo_visibility_class( $visibility );

$btn_class          = 'btn';
switch($size) {
    case 'x_small':
        $btn_class .= ' btn-xs';
        break;
    case 'small':
        $btn_class .= ' btn-sm';
        break;
    case 'large':
        $btn_class .= ' btn-lg';
        break;
}
$btn_class .=  ($fullwidth  == 'true') ? ' btn-block' : '';
$btn_class .=  ($icon_right == 'true') ? ' icon-right' : '';
$btn_class .=  ($icon_only  == 'true') ? ' icon-only' : '';
$btn_class .=  ($shape      != ''    ) ? ' ' . $shape : '';
$btn_class .=  ($style      != ''    ) ? ' ' . $style : '';

if($skin != '' && $skin != 'custom') {
    $btn_class .= " btn-{$skin}";
} else {
    $btn_class .= ' btn-default';
}

$id           = ( $id != '' ) ? esc_attr( $id ) : 'noo-cta-btn-' . noo_vc_elements_id_increment();
$class        = ( $class != '' ) ? 'class="jumbotron ' . $class . '"' : '';
$custom_style = ($custom_style != '' ) ? 'style="' . $custom_style . '"' : '';
$title        = ( $title != '' ) ? esc_attr( $title ) : '';

$button_text  = ( $button_text != '' ) ? esc_attr( $button_text ) : '';
$href         = ( $href != '' ) ? 'href="' . $href . '"' : 'href="#"';
$target       = ( $target == 'true' ) ? 'target="_BLANK"' : '';
$btn_class    = ( $btn_class != '' ) ? 'class="' . $btn_class . '"' : '';

$icon_style   = ( $icon_color != '' ) ? 'style="color: ' . esc_attr( $icon_color ) . ';"' : '';
$icon         = ( $icon != '' ) ? '<i class="fa ' . esc_attr( $icon ) . '" ' . $icon_style . '></i>' : '';

$btn_custom_style       = '';
$hover_btn_custom_style = '';
if( $skin == 'custom' ) {
    $btn_custom_style       .= ( $text_color != '' ) ? ' color: ' . esc_attr( $text_color ) . ';' : '';
    $hover_btn_custom_style .= ( $hover_text_color != '' ) ? ' color: ' . esc_attr( $hover_text_color ) . ';' : '';
    $btn_custom_style       .= ( $bg_color != '' ) ? ' background-color: ' . esc_attr( $bg_color ) . ';' : '';
    $hover_btn_custom_style .= ( $hover_bg_color != '' ) ? ' background-color: ' . esc_attr( $hover_bg_color ) . ';' : '';
    $btn_custom_style       .= ( $border_color != '' ) ? ' border: 1px solid ' . esc_attr( $border_color ) . ';' : '';
    $hover_btn_custom_style .= ( $hover_border_color != '' ) ? ' border: 1px solid ' . esc_attr( $hover_border_color ) . ';' : '';
}

$btn_custom_style .= ( $vertical_padding != '' ) ? ' padding-top:  ' . esc_attr( $vertical_padding ) . 'px;' . ' padding-bottom:  ' . esc_attr( $vertical_padding ) . 'px;' : '';
$btn_custom_style .= ( $horizontal_padding != '' ) ? ' padding-left:  ' . esc_attr( $horizontal_padding ) . 'px;' . ' padding-right:  ' . esc_attr( $horizontal_padding ) . 'px;' : '';
$btn_custom_style  = ($btn_custom_style != '' ) ? 'style="' . $btn_custom_style . '"' : '';

$html   = array();
if( $hover_btn_custom_style != '' ) {
    $html[] = "<style scoped>#{$id} > a.btn:hover { {$hover_btn_custom_style} }</style>";
}

$html[] = "<div id=\"{$id}\" {$class} {$custom_style}>";

if( $title != '' ) {
    $html[] = '<h2 class="cta-title">' . $title . '</h2>';
}

if( $message != '' ) {
    $html[] = '<p class="cta-message">' . $message . '</p>';
}

$html[] = "  <a {$href} {$target} {$btn_class} {$btn_custom_style} role=\"button\">";
if( $icon != '' ) {
    if ( $icon_only == 'true' ) {
        $button_text = $icon;
    } elseif( $icon_right == 'true' ) {
        $button_text = $button_text . $icon;
    } else {
        $button_text = $icon . $button_text;
    }
}

$html[] = $button_text;
$html[] = '  </a>';

$html[] = '</div>';

echo implode( "\n", $html );