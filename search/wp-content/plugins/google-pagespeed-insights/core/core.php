<?php

/**
 * Simple function to replicate PHP 5 behaviour
 */
if ( !function_exists( 'microtime_float' ) ) {
	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}
}

if (!class_exists('googlePagespeedInsights')) {

	class googlePagespeedInsights {
	
		var $gpi_options;
		var $execution_start_time;

		function googlePagespeedInsights(&$gpi_options) {

			//Get Options (passed from init.php)
			$this->gpi_options = &$gpi_options;

			//Add our action + Schedule Crons
			add_action('googlepagespeedinsightsworker', array(&$this, 'googlepagespeedinsightsworker'));
			add_action('googlepagespeedinsightschecknow', array(&$this, 'googlepagespeedinsightsworker'), 10, 3);
			add_action('googlepagespeedinsightschecker', array(&$this, 'googlepagespeedinsightschecker'));
			$this->setup_cron_events();

		}

		function googlepagespeedinsightschecker() {

			if($this->gpi_options['last_run_finished'] == false) {
				$this->googlepagespeedinsightsworker();
			}

		}

		function google_pagespeed_insights_Check_Status() {

			$status = false;
			$mutex_lock = $this->get_lock();
			$this->release_lock();
			if ( !$mutex_lock ){
				$status = true;
			}

			return $status;
		}

		function googlepagespeedinsightsworker( $urls_to_check = array(), $flush = false, $recheck = false ) {

			$status = true;
			$mutex_lock = $this->get_lock();
			if ( !$mutex_lock ){
				$status = false;
			} else {
				if(empty($urls_to_check) && $recheck) {
					$urls_to_check = $this->google_pagespeed_insights_Get_URLS();
				}
				$strategy = $this->gpi_options['strategy'];
				$this->googlepagespeedinsightsdowork($urls_to_check, $strategy, $flush);
			}

			return $status;
		}

		function googlepagespeedinsightsdowork( $urls_to_recheck = array(), $strategy, $flush = false ) {

			if(empty($this->gpi_options['google_developer_key'])) {
				return;
			}

			//Set last run finished to false, we will change this to true if this process finishes before max execution time.
			$this->google_pagespeed_insights_Update_Options('last_run_finished',false,'gpagespeedi_options');

			//Include Google API + Start new Instance. Check first to make sure they arent already included by another plugin!
			if(!class_exists('Google_Client')) {
				require_once GPI_DIRECTORY.'/google-api/src/Google_Client.php';
			}
			if(!class_exists('Google_PagespeedonlineService')) {
				require_once GPI_DIRECTORY.'/google-api/src/contrib/Google_PagespeedonlineService.php';
			}

			$client = new Google_Client();
			$client->setApplicationName("Google_Pagespeed_Insights");
			$client->setDeveloperKey($this->gpi_options['google_developer_key']);
			$service = new Google_PagespeedonlineService($client);

			//Deal with Max Execution Time & PHP runtime
			$this->start_timer();

			$recheck_interval = $this->gpi_options['recheck_interval'];
			$max_execution_time = $this->gpi_options['max_execution_time'];

			// Check for safe mode
			if( $this->is_safe_mode() ){
				// Use PHP.ini value. It isn't going to allow us to override it (BOO HISS!)
				$t = ini_get('max_execution_time');
				if ($t && ($t < $max_execution_time)) 
					$max_execution_time = $t-1;
			} else {
				// Use max_execution_time set in settings.
				@set_time_limit( $max_execution_time );
			}

			//Don't stop the script when the connection is closed
			ignore_user_abort(true);

			//Continue sending output to browser while this runs in the background
			if ($flush){
				flush();
				ob_flush();
	 		}

			//Get our URLs and go to work!
			if(empty($urls_to_recheck)) {
				$url_groups = $this->google_pagespeed_insights_Get_URLS();
			} elseif(!empty($urls_to_recheck)) {
				$url_groups = $urls_to_recheck;
			} else {
				$url_groups = array();
			}
			$url_group_count = 0;
			foreach($url_groups as $group)
			{
				$url_group_count = $url_group_count + count($group);
			}

			$current_page = 0;
			foreach($url_groups as $url_group_type => $url_group_array)
			{
				foreach($url_group_array as $url_array)
				{
					global $wpdb;
			
					$this->google_pagespeed_insights_Update_Options('progress',$current_page . ':' . $url_group_count ,'gpagespeedi_options');
					$current_page++;

					$object_url = $url_array['url'];
					$object_id	= $url_array['objectid'];

					//Use Term ID or Object ID depending on Url Group Type
					if($url_group_type != 'category') {
						$where_column = 'object_id';
					} else {
						$where_column = 'term_id';
					}

					$time = microtime_float();
					$gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
					$desktop_existing_url_info = $wpdb->get_row("
						SELECT desktop_last_modified, force_recheck
						FROM $gpi_page_stats
						WHERE $where_column = $object_id
					");
					if($strategy == "desktop" || $strategy == "both") {
						if((!empty($desktop_existing_url_info->desktop_last_modified) && ($time - $desktop_existing_url_info->desktop_last_modified) > $recheck_interval)
							|| (empty($desktop_existing_url_info->desktop_last_modified) && (!empty($desktop_existing_url_info)) )
							|| !empty($urls_to_recheck)
							|| (!empty($desktop_existing_url_info->force_recheck) && $desktop_existing_url_info->force_recheck == 1 ) ) {
							try{
								$result = $service->pagespeedapi->runpagespeed($object_url, array('locale' => $this->gpi_options['response_language'], 'strategy' => 'desktop'));
								if(!empty($result)) {
									if(isset($result['responseCode']) && $result['responseCode'] == 404) {
										$this->google_pagespeed_insights_Save_Bad_Request($url_group_type, $where_column, $object_id, $object_url);
									} else {
										$result['type'] = $url_group_type;
										$result[$where_column] = $object_id;
										$result['last_modified'] = $time;
										$this->google_pagespeed_insights_Save_Values($result, $where_column, $object_id, $object_url, true, 'desktop');
									}
								}
							} catch(Exception $e) {
								$this->exception_handler($e, 'desktop_existing', $url_group_type, $where_column, $object_id, $object_url);
							}
						} elseif(empty($desktop_existing_url_info->desktop_last_modified)) {
							try{
								$result = $service->pagespeedapi->runpagespeed($object_url, array('locale' => $this->gpi_options['response_language'], 'strategy' => 'desktop'));
								if(!empty($result)) {
									if(isset($result['responseCode']) && $result['responseCode'] == 404) {
										$this->google_pagespeed_insights_Save_Bad_Request($url_group_type, $where_column, $object_id, $object_url);
									} else {
										$result['type'] = $url_group_type;
										$result[$where_column] = $object_id;
										$result['last_modified'] = $time;
										$this->google_pagespeed_insights_Save_Values($result, $where_column, $object_id, $object_url, false, 'desktop');
									}
								}
							} catch(Exception $e) {
								$this->exception_handler($e, 'desktop_new', $url_group_type, $where_column, $object_id, $object_url);
							}
						}
					}
					$mobile_existing_url_info = $wpdb->get_row("
						SELECT mobile_last_modified, force_recheck
						FROM $gpi_page_stats
						WHERE $where_column = $object_id
					");
					if($strategy == "mobile" || $strategy == "both") {
						if((!empty($mobile_existing_url_info->mobile_last_modified) && ($time - $mobile_existing_url_info->mobile_last_modified) > $recheck_interval)
							|| (empty($mobile_existing_url_info->mobile_last_modified) && (!empty($mobile_existing_url_info)) )
							|| !empty($urls_to_recheck)
							|| (!empty($mobile_existing_url_info->force_recheck) && $mobile_existing_url_info->force_recheck == 1 ) ) {
							try{
								$result = $service->pagespeedapi->runpagespeed($object_url, array('locale' => $this->gpi_options['response_language'], 'strategy' => 'mobile'));
								if(!empty($result)) {
									if(isset($result['responseCode']) && $result['responseCode'] == 404) {
										$this->google_pagespeed_insights_Save_Bad_Request($url_group_type, $where_column, $object_id, $object_url);
									} else {
										$result['type'] = $url_group_type;
										$result[$where_column] = $object_id;
										$result['last_modified'] = $time;
										$this->google_pagespeed_insights_Save_Values($result, $where_column, $object_id, $object_url, true, 'mobile');
									}
								}
							} catch(Exception $e) {
								$this->exception_handler($e, 'mobile_existing', $url_group_type, $where_column, $object_id, $object_url);
							}
						} elseif(empty($mobile_existing_url_info->mobile_last_modified)) {
							try{
								$result = $service->pagespeedapi->runpagespeed($object_url, array('locale' => $this->gpi_options['response_language'], 'strategy' => 'mobile'));
								if(!empty($result)) {
									if(isset($result['responseCode']) && $result['responseCode'] == 404) {
										$this->google_pagespeed_insights_Save_Bad_Request($url_group_type, $where_column, $object_id, $object_url);
									} else {
										$result['type'] = $url_group_type;
										$result[$where_column] = $object_id;
										$result['last_modified'] = $time;
										$this->google_pagespeed_insights_Save_Values($result, $where_column, $object_id, $object_url, false, 'mobile');
									}
								}
							} catch(Exception $e) {
								$this->exception_handler($e, 'mobile_new', $url_group_type, $where_column, $object_id, $object_url);
							}
						}
					}

					// Some web servers seem to have a difficult time responding to the constant requests from the Google API, sleeping inbetween each URL helps
					sleep($this->gpi_options['sleep_time']);
				}
			}
			//Clear Pagespeed Disabled warning
			$this->google_pagespeed_insights_Update_Options('pagespeed_disabled',false,'gpagespeedi_options');
			//All menu items have been processed, update the 'last_run_finished' value in the options so we know for next time
			$this->google_pagespeed_insights_Update_Options('last_run_finished',true,'gpagespeedi_options');
			//Clear out our status option
			$this->google_pagespeed_insights_Update_Options('progress',false,'gpagespeedi_options');
			//Release our lock on the DB
			$this->release_lock();
			//If this is the first time we have run through the whole way, update the DB
			$this->google_pagespeed_insights_Update_Options('first_run_complete',true,'gpagespeedi_options');

		}

		function google_pagespeed_insights_Save_Values($result, $where_column, $object_id, $object_url, $update = false, $strategy) {
			global $wpdb;
			$gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
			$gpi_page_stats_values = array();
			
			//Store identifying information
			$gpi_page_stats_values['URL'] = $object_url;
			$gpi_page_stats_values['type'] = $result['type'];
			$gpi_page_stats_values[$where_column] = $result[$where_column];
			$last_modified_column = $strategy . '_last_modified';
			$gpi_page_stats_values[$last_modified_column] = $result['last_modified'];
			$gpi_page_stats_values['force_recheck'] = 0;
			//Store Score, Response Code, and Page Statistics
			if(isset($result['score'])) {
				$score_column = $strategy . '_score';
				$gpi_page_stats_values[$score_column] = $result['score'];
			}
			if(isset($result['responseCode'])) {
				$gpi_page_stats_values['response_code'] = $result['responseCode'];
			}
			if(isset($result['pageStats'])) {
				$stats_column = $strategy . '_page_stats';
				$gpi_page_stats_values[$stats_column] = serialize($result['pageStats']);
			}
						
			if($update) {
				$wpdb->update( $gpi_page_stats, $gpi_page_stats_values, array($where_column => $object_id));
				$last_updated = $wpdb->get_var( "SELECT ID FROM $gpi_page_stats WHERE $where_column = $object_id" );
			} else {
				$wpdb->insert( $gpi_page_stats, $gpi_page_stats_values);
				$last_updated = $wpdb->insert_id;
			}

			//Begin "rules"
			$gpi_page_reports = $wpdb->prefix . 'gpi_page_reports';
			if($update) {
				$sql = "DELETE FROM $gpi_page_reports WHERE page_id = '$last_updated' AND strategy = '$strategy'";
				$wpdb->query( $sql );
			}
			$x=0;
			foreach($result['formattedResults']['ruleResults'] as $rulename => $ruleset) 
			{
				$gpi_page_reports_values = array();

				$gpi_page_reports_values['page_id'] = $last_updated;
				$gpi_page_reports_values['strategy'] = $strategy;
				$gpi_page_reports_values['rule_key'] = $rulename;
				$gpi_page_reports_values['rule_name'] = $ruleset['localizedRuleName'];
				$gpi_page_reports_values['rule_impact'] = $ruleset['ruleImpact'];
				if(isset($ruleset['urlBlocks'])) {
					$gpi_page_reports_values['rule_blocks'] = serialize($ruleset['urlBlocks']);
				}

				$wpdb->insert( $gpi_page_reports, $gpi_page_reports_values);

				$x++;
			}

		}

		function google_pagespeed_insights_Save_Bad_Request($type, $where_column, $object_id, $object_url, $message = true) {

			global $wpdb;
			$gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';

			$row_exist = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT ID
					FROM $gpi_page_blacklist
					WHERE URL = %s",
					$object_url
				),
				ARRAY_A
			);

			if(!$row_exist) {
				$gpi_page_blacklist_values = array();

				//Store identifying information
				$gpi_page_blacklist_values['URL'] = $object_url;
				$gpi_page_blacklist_values['type'] = $type;
				$gpi_page_blacklist_values[$where_column] = $object_id;

				if($message) {
					$this->google_pagespeed_insights_Update_Options('new_ignored_items',true,'gpagespeedi_options');
				}

				$wpdb->insert( $gpi_page_blacklist, $gpi_page_blacklist_values);
			}

		}

		function google_pagespeed_insights_Get_Blacklist() {

			global $wpdb;

    		$gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';
			$query = "
				SELECT URL
				FROM $gpi_page_blacklist
			";
			$blacklist_urls = $wpdb->get_col( $query );

			return $blacklist_urls;

		}

		function google_pagespeed_insights_Check_Blacklist($url, $blacklist_urls) {

			if(in_array($url, $blacklist_urls)) {
				return true;
			}

			return false;

		}

		function google_pagespeed_insights_Get_URLS() {

			$urls_to_check = array();
			$blacklist_urls = $this->google_pagespeed_insights_Get_Blacklist();

			//Get Custom Post Type URLs
			if($this->gpi_options['cpt_whitelist']) {

				$cpt_whitelist_arr = unserialize($this->gpi_options['cpt_whitelist']);

				$args=array(
				  'public'   => true,
				  '_builtin' => false
				); 
				$custom_post_types = get_post_types($args,'names','and');
				foreach ($custom_post_types as $custom_post_type ) {

					if($cpt_whitelist_arr && in_array($custom_post_type, $cpt_whitelist_arr)) {
						$x=0;
						$custom_posts_array = get_posts( array('post_status' => 'publish', 'post_type' => $custom_post_type, 'posts_per_page' => -1, 'fields' => 'ids') );
						foreach($custom_posts_array as $custom_post) {
							$url = get_permalink($custom_post);
							$blacklisted = $this->google_pagespeed_insights_Check_Blacklist($url, $blacklist_urls);
							if(!$blacklisted) {
								$urls_to_check[$custom_post_type][$x]['url'] = $url;
								$urls_to_check[$custom_post_type][$x]['objectid'] = $custom_post;
								$x++;
							}
						}
					}
				}

			}

			//Get Posts URLs form built in post type
			if($this->gpi_options['check_posts']) {
			
				$x=0;
				$builtin_posts_array = get_posts( array('post_status' => 'publish', 'post_type' => 'post', 'posts_per_page' => -1, 'fields' => 'ids') );
				foreach($builtin_posts_array as $standard_post) {
					$url = get_permalink($standard_post);
					$blacklisted = $this->google_pagespeed_insights_Check_Blacklist($url, $blacklist_urls);
					if(!$blacklisted) {
						$urls_to_check['post'][$x]['url'] = $url;
						$urls_to_check['post'][$x]['objectid'] = $standard_post;
						$x++;
					}
				}
			
			}

			//Get Page URLs
			if($this->gpi_options['check_pages']) {

				$x=0;
				$pages_array = get_pages();
				foreach($pages_array as $page) {
					$url = get_permalink($page->ID);
					$blacklisted = $this->google_pagespeed_insights_Check_Blacklist($url, $blacklist_urls);
					if(!$blacklisted) {
						$urls_to_check['page'][$x]['url'] = $url;
						$urls_to_check['page'][$x]['objectid'] = $page->ID;
						$x++;
					}
				}

			}

			//Get Category URLs
			if($this->gpi_options['check_categories']) {

				$x=0;
				$categories_array = get_categories();
				foreach($categories_array as $category) {
					$url = get_category_link($category->term_id);
					$blacklisted = $this->google_pagespeed_insights_Check_Blacklist($url, $blacklist_urls);
					if(!$blacklisted) {
						$urls_to_check['category'][$x]['url'] = $url;
						$urls_to_check['category'][$x]['objectid'] = $category->term_id;
						$x++;
					}
				}

			}

			return $urls_to_check;

		}

		function exception_handler($e, $log_name, $url_group_type, $where_column, $object_id, $object_url) {

			$errors = $e->getErrors();

			if(isset($errors[0]["reason"]) && $errors[0]["reason"] == "keyInvalid") {

				$this->google_pagespeed_insights_Update_Options('bad_api_key',true,'gpagespeedi_options');
				return;

			} elseif(isset($errors[0]["reason"]) && $errors[0]["reason"] == "accessNotConfigured") {

				$this->google_pagespeed_insights_Update_Options('pagespeed_disabled',true,'gpagespeedi_options');
				return;

			} elseif(isset($errors[0]["reason"]) && $errors[0]["reason"] == "backendError") {

				$this->google_pagespeed_insights_Save_Bad_Request($url_group_type, $where_column, $object_id, $object_url, false);
				$this->google_pagespeed_insights_Update_Options('backend_error',true,'gpagespeedi_options');

			} elseif($e->getCode() == "500") {

				$this->google_pagespeed_insights_Save_Bad_Request($url_group_type, $where_column, $object_id, $object_url);

			} else {

				if( $this->gpi_options['log_api_errors'] ) {
					file_put_contents(GPI_DIRECTORY . '/logs/gpi_exception_' . $log_name . '.txt', print_r($errors, true), FILE_APPEND);
				}
			}

		}

		function get_lock() {

			global $wpdb;
			$lock = $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, %d)', 'gpi_lock_a1b2c3', 0));
		
			return $lock == 1;

		}

		function release_lock() {

			global $wpdb;
			$release = $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', 'gpi_lock_a1b2c3'));
			
			return $release == 1;

		}

		function setup_cron_events(){
			//Setup our cron to check if the last run finished
			if (!wp_next_scheduled('googlepagespeedinsightschecker')) {
				wp_schedule_event( time(), 'gpi_lastrun_checker', 'googlepagespeedinsightschecker' );
			}
		} 

		function google_pagespeed_insights_Update_Options($opt_key,$opt_val,$opt_group){   
			// get options-data as it exists before update
			$options = get_option($opt_group);
			// update it
			$options[$opt_key] = $opt_val;
			// store updated data
			update_option($opt_group,$options);
		}

		function start_timer(){
			$this->execution_start_time = microtime_float();
		}

		function execution_time(){
			return microtime_float() - $this->execution_start_time;
		}

		function is_safe_mode(){
			$retval = true;
		
			if (function_exists('ini_get')) {
				if (ini_get('safe_mode')==true) {
					$retval = true;
				} else {
					$retval = false;
				}
			} else {
				$retval = true;
			}
			return $retval;
		}
	}
}

