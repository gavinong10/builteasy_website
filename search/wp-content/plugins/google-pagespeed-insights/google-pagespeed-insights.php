<?php
/**
 * @package google_pagespeed_insights
 * @version 1.0.6
 */
/*
Plugin Name: Google Pagespeed Insights
Plugin URI: http://mattkeys.me
Description: Google Pagespeed Insights
Author: Matt Keys
Version: 1.0.6
Author URI: http://mattkeys.me
*/

/*  Copyright 2013  Matt Keys  (email : me@mattkeys.me)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Path to this file
if ( !defined('GPI_PLUGIN_FILE') ){
	define('GPI_PLUGIN_FILE', __FILE__);
}

//Path to the plugin's directory
if ( !defined('GPI_DIRECTORY') ){
	define('GPI_DIRECTORY', dirname(__FILE__));
}

//Publicly Accessible path
if ( !defined('GPI_PUBLIC_PATH') ){
	define('GPI_PUBLIC_PATH', plugin_dir_url(__FILE__) . '/' );
}

//Load the actual plugin
require 'core/init.php';

