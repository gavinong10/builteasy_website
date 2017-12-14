<?php 

/*

Plugin Name: Project Manager
Plugin URI: http://builteasy.com
Author:Alok Tripathi
Version: 1.0
*/



register_activation_hook( __FILE__, 'project_activate' );

register_deactivation_hook( __FILE__, 'project_deactivate' );

#Create Table

function project_activate(){

/*  global $wpdb;

  $table_name = $wpdb->prefix . "property_pdf";
  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (

		  `id` int(9) NOT NULL auto_increment,
		  `pdf_title` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `map_feature_img` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `acknowledgement` text collate utf8_unicode_ci NOT NULL,
		  `created` varchar(150) collate utf8_unicode_ci NOT NULL,
		  `updated` varchar(150) collate utf8_unicode_ci NOT NULL,
		  UNIQUE KEY `id` (`id`)

		);";



		$wpdb->query($sql);

  }


   // create packege table

   $table_name = $wpdb->prefix ."pdf_packages";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (

		  `id` int(9) NOT NULL auto_increment,
		  `property_pdf_id` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `build_price` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `size` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `bed` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `bath` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `car` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `storeys` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `package_price` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `created` varchar(150) collate utf8_unicode_ci NOT NULL,
		  `updated` varchar(150) collate utf8_unicode_ci NOT NULL,

		  UNIQUE KEY `id` (`id`)

		);";


       $wpdb->query($sql);


   }


   // create feature img table
   $table_name = $wpdb->prefix . "pdf_gallery_images";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
		  `id` int(9) NOT NULL auto_increment,
		  `property_pdf_id` int(9) NOT NULL,
		  `gallery_image` varchar(225) collate utf8_unicode_ci NOT NULL,

		  `created` varchar(150) collate utf8_unicode_ci NOT NULL,
		  `updated` varchar(150) collate utf8_unicode_ci NOT NULL,
		  UNIQUE KEY `id` (`id`)

		);";


		$wpdb->query($sql);

   }


   // create building package table

   $table_name = $wpdb->prefix . "pdf_building_package";

   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
		  `id` int(9) NOT NULL auto_increment,
		  `property_pdf_id` int(9) NOT NULL,
		  `package_includes` text collate utf8_unicode_ci NOT NULL,
		  `feature_image` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `building_model` varchar(225) collate utf8_unicode_ci NOT NULL, 
		  `price` varchar(225) collate utf8_unicode_ci NOT NULL,

		  `created` varchar(150) collate utf8_unicode_ci NOT NULL,
		  `updated` varchar(150) collate utf8_unicode_ci NOT NULL,
		  UNIQUE KEY `id` (`id`)

		);";


		$wpdb->query($sql);

   }


  // create FAqs table

   $table_name = $wpdb->prefix . "faqs";

  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {



      $sql = "CREATE TABLE " . $table_name . " (

		  `id` int(9) NOT NULL auto_increment,
		  `property_pdf_id` int(9) NOT NULL,
		  `question` varchar(225) collate utf8_unicode_ci NOT NULL,
		  `answer` text collate utf8_unicode_ci NOT NULL,

		  `created` varchar(150) collate utf8_unicode_ci NOT NULL,
		  UNIQUE KEY `id` (`id`)
		);";

		$wpdb->query($sql);

  }


  // create lookup table

   $table_name = $wpdb->prefix . "pdf_property_lookup";

  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
		  `id` int(9) NOT NULL auto_increment,
		  `property_pdf_id` int(9) NOT NULL,
		  `property_id` int(9) NOT NULL,
		  
		  `created` varchar(150) collate utf8_unicode_ci NOT NULL,
		  UNIQUE KEY `id` (`id`)
		);";

		$wpdb->query($sql);

  }
*/

}



#Drop Table
function project_deactivate() 
{

    

}



#Create Menus and submenus
add_action('admin_menu', 'show_property_menu');
function show_property_menu() {   
   $role=get_user_role();
   if($role=='administrator'){
   	  ProjectsManagerMenu();
   }else if($role=='author' || $role=='editor'){
   	  ProjectAuthorMenu();
   }
  

}


function ProjectsManagerMenu(){

	//add_menu_page('PDF Manager', 'PDF Manager', 0, 'pdfs', 'register_property_func');
	add_menu_page('', 'PDF Manager', 'manage_options', 'pdfs', 'register_property_func');
	//add_submenu_page('PDF', 'PDFS', 'PDFS', 0, 'pdfs', 'register_property_func' );
	add_submenu_page ( 'pdfs', 'PDFS', 'PDFS', 'manage_options', 'pdfs', 'register_property_func' );	
	add_submenu_page ( 'pdfs', 'Add PDF', 'Add PDF', 'manage_options', 'add_pdfs', 'register_property_func' );
	add_submenu_page (NULL, 'Edit PDF', 'Edit PDF', 'manage_options', 'edit_pdfs', 'register_property_func' );

}

function ProjectAuthorMenu(){
	add_menu_page('PDF Manager', 'PDF Manager', 0, 'pdfs', 'register_property_func');

	add_submenu_page ( 'pdfs', 'PDFS', 'PDFS', 0, 'pdfs', 'register_property_func' );	
	add_submenu_page ( 'pdfs', 'Add PDF', 'Add PDF', 0, 'add_pdfs', 'register_property_func' );
	add_submenu_page (NULL, 'Edit PDF', 'Edit PDF', 0, 'edit_pdfs', 'register_property_func' );
}

function register_property_func(){

	$url = plugins_url();
	include(dirname(__FILE__).'/Project_controller.php');
	$bl_obj= new Property();
	$bl_obj->controller();

}



function load_wp_media_files() {
  wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'load_wp_media_files');


?>