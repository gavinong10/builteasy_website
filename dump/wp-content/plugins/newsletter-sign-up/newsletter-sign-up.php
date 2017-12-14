<?php
/*
Plugin Name: Newsletter Sign-Up
Plugin URI: https://dannyvankooten.com/wordpress-plugins/newsletter-sign-up/
Description: Adds various ways for your visitors to sign-up to your mailinglist (checkbox, widget, form)
Version: 2.0.5
Author: Danny van Kooten
Author URI: https://dannyvankooten.com
License: GPL2
*/

/*  Copyright 2010-2014  Danny van Kooten (email: hi@dannyvankooten.com)

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

if( ! defined( 'ABSPATH' ) ) {
    exit;
}

define('NSU_VERSION_NUMBER', "2.0.5");
define("NSU_PLUGIN_DIR", plugin_dir_path(__FILE__)); 

require_once NSU_PLUGIN_DIR . 'includes/NSU.php';
new NSU();