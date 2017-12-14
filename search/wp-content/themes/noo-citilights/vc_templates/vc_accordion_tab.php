<?php

extract(shortcode_atts(array(
	'title' => __("Section", 'noo'),
), $atts));

$html = array();
$id = 'noo-accordion-tab-' . noo_vc_elements_id_increment();

$html[] = '<div class="panel panel-default">';
$html[] = '  <div class="panel-heading">';
$html[] = '    <h4 class="panel-title">';
$html[] = '      <a data-toggle="collapse" class="accordion-toggle" href="#' . $id . '"><span>' . $title . '</span></a>';
$html[] = '    </h4>';
$html[] = '  </div>';
$html[] = '  <div id="' . $id . '" class="noo-accordion-tab panel-collapse collapse">';
$html[] = '    <div class="panel-body">';
$html[] = '      ' . noo_handler_shortcode_content( $content, true );
$html[] = '    </div>';
$html[] = '  </div>';
$html[] = '</div>';

echo implode( "\n", $html );