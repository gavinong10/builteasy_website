<?php

gpi_setup_default_options();
gpi_setup_db();

/***********************************************
			  Setup Default Options
************************************************/

function gpi_setup_default_options() {
	$default_values = array(
	    'google_developer_key'		=> '', 			//Google API Developer Key
	    'response_language' 		=> 'en_US', 	//Language for report response
	    'strategy'					=> 'desktop',	//Generate reports for Desktop, Mobile, or Both
	    'max_execution_time' 		=> 300, 		//in seconds
	    'sleep_time'		 		=> 0, 			//in seconds
	    'recheck_interval' 			=> 86400, 		//in seconds
		'check_pages' 				=> true,		//check pages
		'check_posts' 				=> true,		//check the built in posts-type
		'cpt_whitelist'				=> '',			//whitelist of Custom Post Types to check
		'check_categories' 			=> true,		//check category indexes
		'first_run_complete' 		=> false,		//true if all pages have been checked once
		'last_run_finished' 		=> true, 		//true if the last check finished before max execution time
		'bad_api_key'		 		=> false, 		//true if API reports the API key is bad
		'pagespeed_disabled' 		=> false, 		//true if API reports the Pagespeed API not enabled		
		'progress'					=> '',			//this is used to keep track of how far along CRON is in checking pages
		'new_ignored_items'		 	=> false, 		//true if new pages have been added to 'ignore' due to a bad request
		'backend_error'				=> false, 		//true if a 'backendErorr' is returned from the API
		'log_api_errors'			=> false,		//log uncaught API exceptions to txt files in FTP root
		'new_activation_message'	=> true, 		//display welcome messsage on first-time activation of plugin
		'scan_method'				=> 'wp_cron'	//default method for scanning pages
	);
	add_option('gpagespeedi_options', $default_values);

	$default_ui_values = array(
		'action_message'		 	=> false, 		//true if a message from an action needs to be delivered to the screen
		'view_preference'			=> 'desktop',	// Mobile or Desktop, the last report type viewed
	);
	add_option('gpagespeedi_ui_options', $default_ui_values);
}

/***********************************************
			  Create Database Table
************************************************/

function gpi_setup_db(){
	global $wpdb;
	$gpi_page_stats_table = $wpdb->prefix . 'gpi_page_stats';
	$gpi_page_reports_table = $wpdb->prefix . 'gpi_page_reports';
	$gpi_page_blacklist_table = $wpdb->prefix . 'gpi_page_blacklist';
	
	$gpi_page_stats = "CREATE TABLE IF NOT EXISTS $gpi_page_stats_table (
	  ID bigint(20) NOT NULL AUTO_INCREMENT,
	  URL text NULL,
	  response_code int(10) DEFAULT NULL,
	  desktop_score int(10) DEFAULT NULL,
	  mobile_score int(10) DEFAULT NULL,
	  desktop_page_stats longtext,
	  mobile_page_stats longtext,
	  type varchar(200) DEFAULT NULL,
	  object_id bigint(20) DEFAULT NULL,
	  term_id bigint(20) DEFAULT NULL,
	  desktop_last_modified varchar(20) NOT NULL,
	  mobile_last_modified varchar(20) NOT NULL,
	  force_recheck int(1) NOT NULL,
	  PRIMARY KEY  (ID),
	  KEY object_id (object_id),
	  KEY term_id (term_id)
	) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

	$gpi_page_reports = "CREATE TABLE IF NOT EXISTS $gpi_page_reports_table (
	  ID bigint(20) NOT NULL AUTO_INCREMENT,
	  page_id bigint(20) NOT NULL,
	  strategy varchar(20) NOT NULL,
	  rule_key varchar(200) NOT NULL,
	  rule_name varchar(200) DEFAULT NULL,
	  rule_impact decimal(5,2) DEFAULT NULL,
	  rule_blocks longtext,
	  PRIMARY KEY  (ID),
	  KEY page_id (page_id)
	) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

	$gpi_page_blacklist = "CREATE TABLE IF NOT EXISTS $gpi_page_blacklist_table (
	  ID bigint(20) NOT NULL AUTO_INCREMENT,
	  URL text NULL,
	  type varchar(200) DEFAULT NULL,
	  object_id bigint(20) DEFAULT NULL,
	  term_id bigint(20) DEFAULT NULL,
	  PRIMARY KEY  (ID)
	) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $gpi_page_stats );
	dbDelta( $gpi_page_reports );
	dbDelta( $gpi_page_blacklist );
}




