<?php
extract(shortcode_atts(array(
    'visibility'      => 'all',
    'class'           => '',
    'id'              => '',
    'custom_style'    => '',
    'title'           => '',
    'size'            => 'fullwidth',
    'position'        => 'center',
    'color'           => '',
    'thickness'       => '2',
    'space_before'    => '20',
    'space_after'     => '20',
), $atts));

echo do_shortcode('[vc_separator type="line-with-text" title="' . $title . '"]');