<?php

define('EGA_TEXTDOMAIN',  		'eg-attachments' 		);
define('EGA_OPTIONS_ENTRY',		'EG-Attachments-Options');
define('EGA_OPTIONS_PAGE_ID', 	'ega_options'			);
define('EGA_SHORTCODE',     	'attachments'			);
define('EGA_TEMPLATE_POST_TYPE','egatmpl'				);

define('EGA_READ_TEMPLATES', 	'edit_posts'			);
define('EGA_EDIT_TEMPLATES', 	'publish_pages'			);
define('EGA_CREATE_TEMPLATES', 	'publish_pages'			);
define('EGA_DELETE_TEMPLATES', 	'delete_others_posts'	);
define('EGA_VIEW_STATS', 		EGA_READ_TEMPLATES		);

define('EGA_DEFAULT_ICON_SET_ID', 'flags');

define('EGA_TEMPLATE_CACHE_EXPIRATION', 604800); /* 1 week = 7 * 24 * 3600 */
define('EGA_SHORTCODE_CACHE_EXPIRATION', 86400); /* 1 day = 24 * 3600 */

$EGA_DEFAULT_OPTIONS = array(
		'load_css'					  => 1,
		'uninstall_del_options'		  => 1,
		'uninstall_del_stats'		  => 0,
		'display_admin_bar'			  => 1,
		'tinymce_button'			  => 1,
		'use_metabox'				  => 0,
		'shortcode_auto'			  => 0, 		/* 0='Not activated', 1=no more used, 2=At the end, 3=Before the excerpt, 4=Between excerpt and content */
		'shortcode_auto_exclusive'	  => 0,
		'shortcode_auto_where'		  => array('post', 'page'),
		'shortcode_auto_title'  	  => '',
		'shortcode_auto_title_tag'	  => 'h2',
		'shortcode_auto_template'	  => 'large',
		'shortcode_auto_doc_type'	  => 'document',
		'shortcode_auto_orderby'	  => 'title',
		'shortcode_auto_order'		  => 'ASC',
		'shortcode_auto_limit'		  => -1,
		'shortcode_auto_default_opts' => 0,
		'clicks_table'				  => 0,
		'standard_templates'		  => '',   /* list of templates created during the plugin installation */
		'force_saveas' 				  => 0,
		'logged_users_only'			  => 0,
		'login_url'					  => '',
		'stats_enable'				  => 0,
		'stats_ip_exclude'			  => '',
		'purge_stats'				  => 24,
		'date_format'				  => '',
		'tags_assignment'			  => 0,
		'icon_set'					  => EGA_DEFAULT_ICON_SET_ID,
		'icon_path'					  => '',
		'icon_url'					  => '',
		'icon_image'				  => 'icon',    /* icon or thumbnail */
		'link'						  => 'direct',
		'nofollow'				  	  => 0,
		'target_blank'				  => 0,
		'exclude_thumbnail'			  => 0,
		'legacy_custom_format'		  => '',
		'clear_cache' 				  => FALSE,
		'on_duplicate'				  => 0
	);

$EGA_SHORTCODE_DEFAULTS = array(
	'title'    		=> '',
	'titletag' 		=> 'h2',
	'orderby'  		=> 'title ASC',
	'template'		=> 'large',
 	'size'     		=> 'large',  /* deprecated attribut, keep it for compatibility only */
	'doctype'  		=> 'document',
	'limit'			=> -1,
	'docid'    		=>  0,		/* deprecated attribut, keep it for compatibility only */
	'id'            =>  0,
	'force_saveas'	=> -1,
	'tags'			=> '',
	'tags_and'		=> '',
	'icon'			=>  1,		/* deprecated attribut, keep it for compatibility only */
	'logged_users'  => -1,
	'include'		=> '',
	'exclude'		=> '',
	'nofollow'		=>  0,
	'icon_image'	=>  'icon',
	'target'		=>  0,
	'exclude_thumbnail' => 1
);

 $EGA_FIELDS_ORDER_LABEL = array(
	'id'			=> 'ID',
	'caption' 		=> 'Caption',
	'date'			=> 'Date',
	'description' 	=> 'Description',
	'filename'		=> 'File name',
	'filedate'		=> 'File Date',
	'size'			=> 'Size',
	'menu_order'	=> 'Menu Order',
	'title' 		=> 'Title',
	'type'			=> 'Type',
	'name'			=> 'Name',
	'author'		=> 'Author',
	'modified'		=> 'Attachment date',
	'comment_count'	=> 'Comments (number of)',
	'clicks'		=> 'Number of click(s)'
);

 $EGA_FIELDS_ORDER_KEY = array(
	'id'			=> 'ID',
	'caption' 		=> 'post_excerpt',
	'date'			=> 'date',
	'description' 	=> 'post_content',
	'filename'		=> 'post_name',
	'filedate'		=> 'file_date',
	'size'			=> 'file_size',
	'menu_order'	=> 'menu_order',
	'title' 		=> 'title',
	'type'			=> 'post_mime_type',
	'name'			=> 'name',
	'author'		=> 'author',
	'modified'		=> 'modified',
	'rand'			=> 'rand',
	'comment_count'	=> 'comment_count',
	'clicks'		=> 'clicks'
);


if (! class_exists('EG_Attachments_Common')) {

	Class EG_Attachments_Common {

		/**
		 * parse_template
		 *
		 * Get a text, parse it, and extract templates components
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	string	$content	Text.
		 * @return	array				components of the template
		 *
		 */
		static function parse_template($content) {
			$template = FALSE;
			// IMPORTANT: option s is mandatory to manage the carriage returns
			preg_match_all('/\[before\](.*)\[\/before\](.*)\[loop\](.+)\[\/loop\](.*)\[after\](.*)\[\/after\]/is',
							$content,
							$matches);
			if (sizeof($matches) > 4) {
				$template = array(
					'before'	=> $matches[1][0],
					'loop'		=> $matches[3][0],
					'after'		=> $matches[5][0]);
			}
			return ($template);
		} // End of parse_template

		/**
		 * get_templates
		 *
		 * Query DB to get and return the list of templates
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	array	$options		plugin options
		 * @param 	string	$type			all / standard / custom.
		 * @param 	boolean	$title_only	    return only the list of title, or return list of templates with all fields
		 * @return	array					list of templates
		 *
		 */
		static function get_templates($options, $type='all', $title_only=TRUE) {

			$template_list = ( EGA_ENABLE_CACHE ? get_transient('eg-attachments-templates') : FALSE );
// eg_plugin_error_log('EGA', "Templates from cache", $template_list);
			if (FALSE === $template_list) {
				$results = get_posts(array(
							'post_status' 	=> 'publish',
							'post_type'		=> EGA_TEMPLATE_POST_TYPE,
							'orderby' 		=> 'title',
							'order' 		=> 'ASC',
							'numberposts' 	=> -1
						)
					);
// eg_plugin_error_log('EGA', "Templates from get_post", $results);
					
				$template_list = array( 'standard' => array(), 'custom' => array() );
				
				/* Extract list of templates created during the plugin installation */
				$std_tmpl = explode(',', $options['standard_templates']);
				
				/* If we find templates ... */
				if ($results) {
					/* Record each template, in custom or standard categories */
					foreach ($results as $template) {
						if (in_array($template->ID, $std_tmpl)) {
							$template_list['standard'][$template->post_name] = $template;
						}
						else {
							$template_list['custom'][$template->post_name] = $template;
						}
					}
				}
				
// eg_plugin_error_log('EGA', "Templates from get_post", $template_list);

				if (EGA_ENABLE_CACHE)
					set_transient('eg-attachments-templates', $template_list, EGA_TEMPLATE_CACHE_EXPIRATION);

			} // End of no data in cache
			
			/* Prepare the list */
			$returned_list = FALSE;
			if (FALSE !== $template_list)

				if ('all' == $type)
					$returned_list = array_merge($template_list['standard'], $template_list['custom']);
				else
					$returned_list = $template_list[$type];

				if ($title_only) {
					foreach ($returned_list as $key => $value) {
						$returned_list[$key] = esc_html($value->post_title);
				} // End of foreach
			}
			
			/* Return the list */
			return ($returned_list);

		} // End of get_templates

		/**
		 * get_shortcode_defaults
		 *
		 * Merge default parameters of the shortcode, and default parameters of the plugin, and return the results
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	array	$options		plugin options
		 * @return	array					shortcode parameters
		 *
		 */
		static function get_shortcode_defaults($options) {
			global $EGA_SHORTCODE_DEFAULTS;

			$values = array(
				'force_saveas'		=> $options['force_saveas'],
				'logged_users'		=> $options['logged_users_only'],
				'login_url' 		=> $options['login_url'],
				'nofollow' 			=> $options['nofollow'],
				'icon_image' 		=> $options['icon_image'],  /* Added in 2.0.3 */
				'target' 			=> $options['target_blank'],
				'exclude_thumbnail'	=> $options['exclude_thumbnail'],
			);
			return wp_parse_args($values, $EGA_SHORTCODE_DEFAULTS);

		} // End of get_shortcode_defaults

		
		/**
		 * get_cache_entry
		 *
		 * Build and return list of cache entries
		 *
		 * @param 	none
		 * @return 	none
		 */
		static function get_cache_entry($name, $id='') {
			return strtolower($name).'_cache_'.( '' == $id ? '' : ( 0 < $id ? $id : 'all'));
		} // End of get_cache_entry
		
		/**
		 * list_of_cache
		 *
		 * Build and return list of cache entries
		 *
		 * @param 	none
		 * @return 	none
		 */
		static function list_of_cache($name, $textdomain) {

			global $wpdb;

			$cache_list = FALSE;

			/* --- Get list of cache items --- */
			$pattern = '_transient_' . self::get_cache_entry($name);
			$transient_list = $wpdb->get_results('SELECT option_name FROM '.$wpdb->options.' WHERE option_name like "'.$pattern.'%"');

			/* --- Transients found --- */
			if ( $transient_list ) {
			
				/* --- Build the list of post id --- */
				$attach_id = array();
				foreach ($transient_list as $value) {
					$id = str_replace( $pattern, '', $value->option_name );
					$attach_id[$id] = $id;
				}

				/* --- Add 'all' found, remove it from the initial list --- */
				if ( isset($attach_id['all']) ) {
					$cache_list['all'] = __('Attachments not linked to posts', $textdomain);
					unset($attach_id['all']);
				}
				if ( 0 < sizeof($attach_id) ) {

					/* --- Getting title of attachments --- */
					$attachments_list = $wpdb->get_results('SELECT ID, post_title FROM '.$wpdb->posts.' WHERE ID in ('.implode(',',$attach_id).')' );
					if ($attachments_list) {
						foreach ($attachments_list as $value) {
							$cache_list[$value->ID] = $value->ID.' - '.esc_html($value->post_title);
						}
					}
				}
			} // End of cache entry exist
			return ($cache_list);
		} // End of list_of_cache

		/**
		 * get_icon_set_list
		 *
		 * Get the list of available icon set.
		 *
		 * Setting the list with the icons sets provided by the plugin
		 * Apply 
		 *
		 * @param 	string	Textdomain - Textdomain to be used to translate the icon set label
		 * @return 	array	list of icons sets
		 */
		static function get_icon_set_list($textdomain) {
			$icon_set = array(  
							EGA_DEFAULT_ICON_SET_ID =>  __('Default', $textdomain),
							'metro' 				=>  __('Metro UI', $textdomain)
						);
						
			/**
			 * Filter the list of icons sets.
			 *
			 * This filter should be used to add an icons set.
			 * If you use this filter, you need also to use get_icon_for_file_ext, and get_icon_for_type
			 *
			 * If you want to know, how to add an icon set, you can take a look to the plugin EG-Attach-Icons-Oxygen (http://wordpress.org/plugins/eg-attach-icons-oxiygen)
			 *
			 * @since 2.0.3
			 *
			 * @param array $icon_set list of icons set ( id => label).
			 */
			return apply_filters('ega_add_icon_set', $icon_set);

		} // End of get_icon_set_list

		/**
		 * get_icon_for_file_ext
		 *
		 * Return the list of (Path => url) for file extensions
		 *
		 * @param 	string	absolute path of the plugin,
		 * 			string  url of the plugin
		 * @return 	array 	Library => (path => url)
		 */
		static function get_icon_for_file_ext($plugin_path, $plugin_url) {
	
			$icon_path_url = array( 
								EGA_DEFAULT_ICON_SET_ID => array(  path_join($plugin_path, 'img/flags/file-ext') => path_join($plugin_url, 'img/flags/file-ext') ),
								'metro' 				=> array(  path_join($plugin_path, 'img/metro/file-ext') => path_join($plugin_url, 'img/metro/file-ext') ) 
							);

			/**
			 * Filter the list.
			 *
			 * This filter should be used to set the paths and urls for new icon set.
			 * The directory specified by the path/url contains a list of icons for file extensions ( <extension>.png )
			 * If you use this filter, you need also to use get_icon_set_list, and get_icon_for_type
			 *
			 * If you want to know, how to add an icon set, you can take a look to the plugin EG-Attach-Icons-Oxygen (http://wordpress.org/plugins/eg-attach-icons-oxiygen)
			 *
			 * @since 2.0.3
			 *
			 * @param array $icon_path_url list of path/url for new icons set ( id => array( path => url)).
			 */
			return apply_filters('ega_add_icon_for_file_ext', $icon_path_url);
		} // End of add_icon_for_file_ext
		
		/**
		 * get_icon_for_type
		 *
		 * Return the list of (Path => url) for file type
		 *
		 * @param 	string	absolute path of the plugin,
		 * 			string  url of the plugin
		 * @return 	array 	Library => (path => url)
		 */
		static function get_icon_for_type($plugin_path, $plugin_url) {

			$icon_path_url = array( 
								EGA_DEFAULT_ICON_SET_ID => array( path_join($plugin_path, 'img/flags') => path_join($plugin_url, 'img/flags') ),
								'metro' 				=> array( path_join($plugin_path, 'img/metro') => path_join($plugin_url, 'img/metro') )
							);
							
			/**
			 * Filter the list.
			 *
			 * This filter should be used to set the paths and urls for new icon set.
			 * The directory specified by the path/url contains a list of icons for file type: image, audio, video, document, spreadsheet, interactive, text, archive, code
			 * If you use this filter, you need also to use get_icon_set_list, and get_icon_for_ext
			 *
			 * If you want to know, how to add an icon set, you can take a look to the plugin EG-Attach-Icons-Oxygen (http://wordpress.org/plugins/eg-attach-icons-oxiygen)
			 *
			 * @since 2.0.3
			 *
			 * @param array $icon_path_url list of path/url for new icons set ( id => array( path => url)).
			 */
			return apply_filters('ega_add_icon_for_type', $icon_path_url);
		} // End of add_icon_for_type

		/**
		 * check_icons_set
		 *
		 * Check if the icon set is still valid
		 *
		 * If the current selected icons set is no more available (because a plugin was disabled, for example), we need to update it.
		 *
		 * @param 	string	icon set id,
		 * 			string  the options
		 * @return 	none
		 */
		static function check_icons_set($list, & $options) {
			
			$id = $options['icon_set'];
			if ( EGA_DEFAULT_ICON_SET_ID != $id ) {

				if ( ! isset( $list[$id] ) ) {
					 $options['icon_set'] = EGA_DEFAULT_ICON_SET_ID;
					 update_option(EGA_OPTIONS_ENTRY, $options);
				} // End of id doesn't exist in the list
			} // End of id is not the default

		} // End of check_icons_set

	} // End of class

} // End of class_exists
?>