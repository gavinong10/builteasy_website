<?php
/*
Plugin Name: codeex Shortcodes
Plugin URI: http://themeforest.net/user/codeex
Description: A simple, minimalist typography shortcode for your themes and website.
Version: 2.0.7
Author: codeex	
Author URI: http://themeforest.net/user/codeex
License: GPL
*/

include 'inc/main.php';
include 'inc/tinymce/tinymce.php';


/*---------------------------------------------
ENQUEUE STYLE and SCRIPT
----------------------------------------------*/

function typo_widget_enqueue_typo_style() {
	if( ! is_admin() ) :
		wp_enqueue_script( 'jquery' );

		wp_register_style( 'typo_style', plugin_dir_url(__FILE__) . 'typo-style.css' );	
		wp_enqueue_style( 'typo_style' );

		wp_register_script( 'typo_custom_js', plugin_dir_url(__FILE__) . 'typo-custom.js', array(), '', true );
		wp_enqueue_script( 'typo_custom_js' );	
	endif;
}
add_action( 'wp_enqueue_scripts', 'typo_widget_enqueue_typo_style' );

function typo_add_init() {
	wp_register_style( 'typo_style-popup', plugin_dir_url(__FILE__) . 'typo-popup.css' );	
	wp_enqueue_style( 'typo_style-popup' );
}
add_action('admin_init', 'typo_add_init');


/*---------------------------------------------
LOCALISATION
----------------------------------------------*/

function typo_load_textdomain() {
    load_plugin_textdomain( 'typo_codeex_plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'typo_load_textdomain' );


/*---------------------------------------------
ADD CUSTOM QUICKTAGS
----------------------------------------------*/

function typo_my_custom_quicktags() {
	wp_enqueue_script( 'typo_my_custom_quicktags', plugin_dir_url(__FILE__) . 'inc/tinymce/quicktags.js', array('quicktags')	);
}
add_action('admin_print_scripts', 'typo_my_custom_quicktags');


/*---------------------------------------------
EMPTY PARAGRAPH
----------------------------------------------*/

function typo_shortcode_empty_paragraph_fix($typo_content) {   
    $typo_array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );
    $typo_content = strtr($typo_content, $typo_array);
	return $typo_content;
}
add_filter('the_content', 'typo_shortcode_empty_paragraph_fix');


function typo_content_filter($typo_content) {	 
	$typo_block = join("|",array("alert","blockquote","a","ul","li","div","dropcap","columns","image","tabgroup","tab","toggle","video","googlemap"));	 
	$typo_rep = preg_replace("/(<p>)?\[($typo_block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$typo_content);			
	$typo_rep = preg_replace("/(<p>)?\[\/($typo_block)](<\/p>|<br \/>)?/","[/$2]",$typo_rep);	 
	return $typo_rep;	 
}
add_filter("the_content", "typo_content_filter"); 

?>