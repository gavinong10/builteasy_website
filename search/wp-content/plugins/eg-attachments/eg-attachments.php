<?php
/*
Plugin Name: EG-Attachments
Plugin URI: http://www.emmanuelgeorjon.com/eg-attachments-1233
Description: Shortcode displaying lists of attachments for a post
Version: 2.1.3
Author: Emmanuel GEORJON
Author URI: http://www.emmanuelgeorjon.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /lang
Text Domain: eg-attachments
*/

/*
EG-Attachments is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
EG-Attachments is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with EG-Attachments. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

define('EGA_VERSION', 		'2.1.3'		);
define('EGA_COREFILE',		__FILE__	);
define('EGA_ENABLE_CACHE',	FALSE		);

/* --- 
   Loading libraries 
   -------------------------------------------- */
if (! class_exists('EG_Plugin_135')) {
	require('lib/eg-plugin.inc.php');
}

if (! class_exists('EG_Widget_220')) {
	require_once('lib/eg-widgets.inc.php');
}

/* --- 
   Loading plugin functions 
   -------------------------------------------- */
if (! class_exists('EG_Attachments_Common')) {
	require('inc/eg-attachments-common.inc.php');
}

if (is_admin()) {
	require_once('inc/eg-attachments-admin.inc.php');
}
else {
	require_once('inc/eg-attachments-public.inc.php');
}

/* --- 
   Loading Widgets 
   -------------------------------------------- */
require_once('inc/eg-attachments-widgets.inc.php');


?>