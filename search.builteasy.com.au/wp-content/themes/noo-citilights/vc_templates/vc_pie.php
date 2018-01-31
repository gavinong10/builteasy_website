<?php
$title = $el_class = $value = $label_value= $units = '';
extract(shortcode_atts(array(
	'title' => '',
	'class' => '',
	'value' => '50',
	'units' => '',
	'color' => '#f7f7f7',
	'value_color'=>'#333333',
	'style'	=> 'filled',
	'width'=>'1',
	'label_value' => ''
), $atts));

wp_enqueue_script('vc_pie_custom');

$class = ($class != '') ? esc_attr( $class ) : '';
$css_class =  'noo-pie-chart noo-pie-chart-'.$style.' '.$class;
$output = "\n\t".'<div data-pie-with="'.absint($width).'" class= "'.$css_class.'" data-pie-color="'.$color.'" data-pie-value="'.$value.'" data-pie-label-value="'.$label_value.'" data-pie-units="'.$units.'">';
    $output .= "\n\t\t".'<div class="noo-pie-chart-container">';
        $output .= "\n\t\t\t".'<div class="noo-pie-chart-wrapper">';
            $output .= "\n\t\t\t".'<span class="noo-pie-chart-back"></span>';
            $output .= "\n\t\t\t".'<span class="noo-pie-chart-value" style="color:'.$value_color.'"></span>';
            $output .= "\n\t\t\t".'<canvas width="101" height="101"></canvas>';
            $output .= "\n\t\t\t".'</div>';
        if ($title!='') {
        $output .= '<h4 class="noo-pie-chart-heading">'.$title.'</h4>';
        }
    $output .= "\n\t\t".'</div>';
    $output .= "\n\t".'</div>'."\n";

echo $output;