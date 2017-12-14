<?php
function seed_ucp_get_settings(){
    $s1 = get_option('seed_ucp_settings_content');
    $s2 = get_option('seed_ucp_settings_design');
    $s3 = get_option('seed_ucp_settings_advanced');

    if(empty($s1))
        $s1 = array();

    if(empty($s2))
        $s2 = array();

    if(empty($s3))
        $s3 = array();

    $settings = $s1 + $s2 + $s3;


    return apply_filters( 'seed_ucp_get_settings', $settings );;
}
