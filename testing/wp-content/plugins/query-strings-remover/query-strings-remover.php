<?php
/*
Plugin Name: Query Strings Remover
Plugin URI: http://atulhost.com/query-strings-remover
Description: Query Strings Remover removes query strings from your static resources like CSS and JavaScript files. It will improve your cache performance and overall score in Google PageSpeed, YSlow, Pingdom and GTmetrix. Just install and forget everything, as no configuration needed.
Author: Atul Kumar Pandey
Version: 1.0
Author URI: http://atulhost.com
*/
function qsr_remove_script_version( $src ){
    $parts = explode( '?ver', $src );
        return $parts[0];
}
add_filter( 'script_loader_src', 'qsr_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'qsr_remove_script_version', 15, 1 );
?>