<?php
extract( shortcode_atts( array(
	'visibility'      	 => 'all',
	'class'           	 => '',
	'id'              	 => '',
	'custom_style'    	 => '',
	'type'            	 => '',
	'bg_color'        	 => '',
	'bg_image'        	 => '',
	'bg_image_repeat' 	 => '',
	'bg_color_overlay' 	 => '',
	'parallax'        	 => '',
	'parallax_no_mobile' =>'',
	'parallax_velocity'	 =>'0.1',
	'bg_video'       	 => '',
	'bg_video_url'    	 => '',
	'bg_video_poster' 	 => '',
	'border'          	 => '',
	'inner_container'  	 => '',
	'padding_top'     	 => '',
	'padding_bottom'  	 => '',
	), $atts ) );

$parallax       = ( $parallax      == 'true' ) ? true : false;
if( $parallax ) {
	wp_enqueue_script( 'vendor-parallax' );
}

$bg_video         = ( $bg_video        == 'true' ) ? true : false;

$bg_video_url     = ( $bg_video ) && ( $bg_video_url != '' )    ? $bg_video_url : '';
$bg_video_poster  = ( $bg_video ) && ( $bg_video_poster != '' ) ? $bg_video_poster : '';
if ( $bg_video && $bg_video_url !='') {
	wp_enqueue_script( 'vendor-bigvideo-bigvideo' );
}

$class          = ( $class         != ''     ) ? 'noo-vc-row row ' . esc_attr( $class ) : 'noo-vc-row row ';
$visibility     = ( $visibility    != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class         .= noo_visibility_class( $visibility );
$bg_color         = ( $bg_color        != ''     ) ? 'background-color: '.$bg_color.';' : '';
$padding_top      = ( $padding_top     != ''     ) ? ' padding-top: ' . $padding_top . 'px;' : '';
$padding_bottom   = ( $padding_bottom  != ''     ) ? ' padding-bottom: ' . $padding_bottom . 'px;' : '';

$bg_image         = ( $bg_image        != ''     ) ? $bg_image : '';
$bg_image_repeat  = ( $bg_image        != ''     ) && ( $bg_image_repeat == 'true' ) ? true : false;

switch ( $border ) {
	case 'top' :
		$border = ' border-top';
		break;
	case 'left' :
		$border = ' border-left';
		break;
	case 'right' :
		$border = ' border-right';
		break;
	case 'bottom' :
		$border = ' border-bottom';
		break;
	case 'vertical' :
		$border = ' border-top border-bottom';
		break;
	case 'horizontal' :
		$border = ' border-left border-right';
		break;
	case 'all' :
		$border = ' border-top border-left border-right border-bottom';
		break;
	default :
		$border = '';
}

$html = array();
$inner_html = array();

if( $inner_container == 'true') {
	$html[] = '<div class="container-boxed max">';
}

$id   = ( $id != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
if ( $bg_video && $bg_video_url !='') {

	$bg_video_class = ' bg-video';
	$bg_video_poster = wp_get_attachment_image_src( $bg_video_poster, 'full');
	$html[] = "<div {$id} class=\"{$class}{$bg_video_class}{$border}\" style=\"{$padding_top}{$padding_bottom}{$custom_style}\">";
	$html[] = do_shortcode( $content );
	$html[] = '</div>';
	$html[] = "<script> jQuery(function(){ var BV = new jQuery.BigVideo(); BV.init(); if ( Modernizr.touch ) { BV.show('{$bg_video_poster[0]}'); } else { BV.show('{$bg_video_url}', { ambient : true }); } }); </script>";

} elseif ( $bg_image != '' ) {
	$bg_image_class = $bg_image_repeat ? ' bg-image image-repeat' : ' bg-image';
	$parallax_class = $parallax        ? ' parallax' : '';
	$bg_image_class = $parallax        ? $bg_image_class . ' bg-parallax' : $bg_image_class;
	
	$bg_image = wp_get_attachment_image_src( $bg_image, 'full');
	$bg_overlay = ( $bg_color_overlay != '' ) ? 'background-color: '.$bg_color_overlay.';' : '';
	
	$row_bg_no_parallax = $parallax ? "" : "background-image: url({$bg_image[0]});";
	$html[] = "<div  {$id} class=\"{$class}{$bg_image_class}{$border}\" style=\"{$row_bg_no_parallax}{$bg_overlay}{$padding_top}{$padding_bottom}{$custom_style}\">";
	$html[] = do_shortcode( $content );
	if($parallax) {
		$parallax_id = 'parallax-' . noo_vc_elements_id_increment();
		$html[] = "<div id=\"{$parallax_id}\" class=\"{$parallax_class}\" data-parallax=\"1\" data-parallax_no_mobile=\"".((string)$parallax_no_mobile === 'true' ? "0" : "1" ) ."\" data-velocity=\"".((float)$parallax_velocity)."\" style=\"background-image: url({$bg_image[0]});\" ></div>";
		if( $bg_color_overlay != '' && noo_get_option('noo_site_layout', 'fullwidth') == 'boxed' ) {
			$html[] = "<style scoped>#{$parallax_id}:after { background-color: {$bg_color_overlay}; }</style>";
		}
	}
	$html[] = '</div>';

} else {
	$html[] = "<div {$id} class=\"{$class}{$border}\" style=\"{$bg_color}{$padding_top}{$padding_bottom}{$custom_style}\">";
	$html[] = do_shortcode( $content );
	$html[] = '</div>';
}

if( $inner_container == 'true') {
	$html[] = '</div>';
}

echo implode( "\n", $html );
