<?php
/*
Plugin Name: WP Change Default Email
Plugin URI: http://www.techeach.com/wordpress-plugins
Description: WP Change Default Email allows you to change the default from email and name.
Version: 0.4
Author: Vijay Sharma
Author Email: vijay@techeach.com
License:

  Copyright 2011 Vijay Sharma (vijay@techeach.com)

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

class WPChangeDefaultEmail {
	private $wcdeOptions;
	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'WP Change Default Email';
	const slug = 'wp_change_default_email';
	const lang_domain = 'wp-change-default-email';
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_wp_change_default_email' ) );
		
		//register a deactivation hook for the plugin
		register_deactivation_hook( __FILE__, array( &$this, 'uninstall_wp_change_default_email') );
		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_wp_change_default_email' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_wp_change_default_email() {
		// do not generate any output here
		$this->wcdeOptions = array();
		$this->wcdeOptions["from"] = "";
		$this->wcdeOptions["fromname"] = "";
		$this->wcdeOptions["deactivate"] = 0;
		add_option("wp_change_default_email_options",$wcdeOptions);
	}
  
  	/**
	 * Runs when the plugin is deactivated
	 */ 
  	function uninstall_wp_change_default_email() {

        $wcdeOptions = get_option('wp_change_default_email_options');

        if($wcdeOptions["deactivate"]!= 0){
  			delete_option("wp_change_default_email_options");	
		}
    }
	/**
	 * Runs when the plugin is initialized
	 */
	function init_wp_change_default_email() {
		// Setup localization
		load_plugin_textdomain( self::lang_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		
		$this->wcdeOptions = get_option('wp_change_default_email_options'); 
		
		if ( is_admin() ) {
			//this will run when in the WordPress admin
			add_filter('plugin_action_links',array(&$this,'wp_change_default_email_settings_link'),10,2);
			require_once( dirname(__FILE__) . '/admin/wp_change_default_email_admin.php');
		} else {
			//this will run when on the frontend
		}
		
		add_filter('wp_mail_from', array(&$this,'wp_change_default_email_change_from_email'));
		add_filter('wp_mail_from_name', array(&$this,'wp_change_default_email_change_from_name'));
		   
	}

	function wp_change_default_email_change_from_email($email_old) { 
		if ( isset($this->wcdeOptions['from']) && is_email($this->wcdeOptions['from'])) {
		    return $this->wcdeOptions['from'];	
		} else {
		    return $email_old;	
		}
	}

	function wp_change_default_email_change_from_name($fromname) {
	    if ( !empty($this->wcdeOptions['fromname'])) {
		 	return $this->wcdeOptions['fromname'];
		} else {
			return $fromname;
		}
	}
	
	function wp_change_default_email_settings_link($action_links,$plugin_file){
		if($plugin_file==plugin_basename(__FILE__)){
			$ws_settings_link = '<a href="options-general.php?page=' . dirname(plugin_basename(__FILE__)) . '/admin/wp_change_default_email_admin.php">' . __("Settings") . '</a>';
			array_unshift($action_links,$ws_settings_link);
		}
		return $action_links;
	}
	  
} // end class


new WPChangeDefaultEmail();

