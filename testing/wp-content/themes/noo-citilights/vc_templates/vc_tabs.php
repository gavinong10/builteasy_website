<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'title'           => '',
    'active_tab'      => '',
), $atts));

$visibility       = ( $visibility      != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class            = ( $class           != ''     ) ? 'noo-vc-accordion panel-group ' . esc_attr( $class ) : 'noo-vc-accordion panel-group ';
$class           .= noo_visibility_class( $visibility );

$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '";' : '';

$id   = ( $id != '' ) ? esc_attr( $id ) : 'noo-tabs-' . noo_vc_elements_id_increment();
$active_tab = ( $active_tab != '' && ( intval($active_tab, 10) > 0 ) ) ? intval($active_tab) - 1 : 0;

// Extract tab titles
preg_match_all( '/vc_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}(\sicon\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();
if ( isset($matches[0]) ) {
    $tab_titles = $matches[0];
}

$_shortcode = defined( 'WPB_VC_VERSION' ) ? $this->shortcode : $tag;

$vc_tour_class = ( 'vc_tour' == $_shortcode ) ? ' tabs-left' : '';

$html = array("<div class=\"noo-tabs{$vc_tour_class}\">");

// Tabs nav
$html[] .= '<ul class="nav nav-tabs" role="tablist" id="' . $id . '">';
foreach ( $tab_titles as $index => $tab ) {
    preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}(\sicon\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
    if(isset($tab_matches[1][0])) {
        $icon = isset($tab_matches[5][0]) ? '<i class="fa ' . $tab_matches[5][0] . '"></i>' : '';
        $html[] .= '<li><a role="tab" data-toggle="tab" href="#tab-'. (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .'">' . $icon . $tab_matches[1][0] . '</a></li>';
    }
}
$html[] .= '</ul>'."\n";

// Tabs content
$html[] = '<div class="tab-content">';
$html[] = do_shortcode( $content );
$html[] = "</div>";
$html[] = "<script>";
$html[] = "jQuery('document').ready(function ($) {";
$html[] = "  $('#{$id} a:eq({$active_tab})').tab('show');";
$html[] = '});';
$html[] = '</script>';

$html[] = '</div>';

echo implode( "\n", $html );