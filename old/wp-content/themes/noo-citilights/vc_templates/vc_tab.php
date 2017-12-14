<?php

extract(shortcode_atts(array(
	'tab_id'    => '',
	'title' => __("Section", 'noo'),
	'icon'  => ''
), $atts));

$html = array();
$id = ( $tab_id == '' ) ? 'tab-' . sanitize_title( $title ) : 'tab-' . $tab_id;

$html[] = '<div class="tab-pane" id="' . $id . '">';
$html[] = do_shortcode( $content );
$html[] = '</div>';

echo implode( "\n", $html );