<?php

if (! class_exists('EG_Attachments_Public')) {

	/**
	 * Class EG_Attachments_Public
	 *
	 * Implement a shortcode to display the list of attachments in a post.
	 *
	 * @package EG-Attachments
	 */
	Class EG_Attachments_Public extends EG_Plugin_135 {

		// var $order_by 		= 'title';
		var $order 			= array();
		var $stats_enabled  = FALSE;
		var $id				= 0;

		/**
		  *  init
		  *
		  * Declare shortcode, and auto-shortcode
		  *
		  * @package EG-Attachments
		  *
		  * @param  none
		  * @return	none
		  */
		function init() {

			parent::init();

			// Define if we can collect statistics or not.
			$this->stats_enabled = $this->options['stats_enable'] && $this->options['clicks_table'];
			if ($this->stats_enabled && $this->options['stats_ip_exclude'] != '') {
				if (isset($_SERVER['REMOTE_ADDR']) && '' != $_SERVER['REMOTE_ADDR'] ) {
					$this->stats_enabled = (! in_array($_SERVER['REMOTE_ADDR'], explode(',', $this->options['stats_ip_exclude'])) );
				}
			}

			add_action('template_redirect', array(&$this, 'manage_link'));

			// Add the shortcode
			add_shortcode(EGA_SHORTCODE, array(&$this, 'get_attachments'));

			// Add the auto shortcode
			if ( $this->options['shortcode_auto'] > 0 ) {
				add_filter('the_content',     array(&$this, 'shortcode_auto_content'));
				if ($this->options['shortcode_auto'] == 3) {
					add_filter('get_the_excerpt', array(&$this, 'shortcode_auto_excerpt'));
				}
			}

		} // End of init

		/**
		  *  manage_link
		  *
		  * Manage the file download
		  *
		  * @package EG-Attachments
		  *
		  * @param  none
		  * @return	none
		  */
		function manage_link() {
			global $post;

			// Ensure that the link is coming from EG-Attachment
			if ( isset($_GET['aid']) /*&& is_numeric($_GET['aid']) */) {

				// First security check. If post not defined, something strange happens.
				if (! isset($post)) {
					wp_die(__('Something is going wrong. Bad address, or perhaps you try to access to a private document.', $this->textdomain));
				}

				// Are we in an attachment? or a post?
				if ( is_attachment() ) {

					$attach_id    = $post->ID;
					$attach_title = $post->post_title;
					$parent_id    = ( isset($_GET['pid']) ? $_GET['pid'] : reset( get_post_ancestors($attach_id) ) );
					$parent_title = get_post_field('post_title', $parent_id);
				}
				else {

					$parent_id    		= $post->ID;
					$parent_title 		= $post->post_title;
					$attach       		= get_post($_GET['aid']);
					if (isset($attach) && $attach && 'attachment' == $attach->post_type) {
						$attach_id    	= $attach->ID;
						$attach_title 	= get_post_field('post_title',$attach_id) ;
					}
				}

				if ( isset($attach_id) ) {

// eg_plugin_error_log('EGA', 'download', $parent_id);
// eg_plugin_error_log('EGA', 'download', $parent_title);
// eg_plugin_error_log('EGA', 'download', $attach_id);
// eg_plugin_error_log('EGA', 'download', $attach_title);
// eg_plugin_error_log('EGA', 'download, Post status', get_post_field('post_status', $parent_id));

					// $parent_id = reset(get_post_ancestors($attach_id));

					// Security check: private posts / pages
					if ( 'private' == get_post_field('post_status', $parent_id) && !is_user_logged_in() ) {
						wp_die(__('The parent post of this document is private. You must be a user of the site, and logged in, to display this file.', $this->textdomain));
					}

					// Security check: protected post
					if ( post_password_required($parent_id) ) {
						wp_die(__('The parent post of this document is password protected. Please go to the site, and enter the password required to display the document', $this->textdomain));
					}

					// Security check: private Attachment
					if ( 'private' == get_post_field('post_status', $attach_id) && !is_user_logged_in() ) {
						wp_die(__('This document is private. You must be a user of the site, and logged in, to display this file.', $this->textdomain));
					}

					// Security check: protected attachment
					if ( post_password_required($attach_id) ) {
						wp_die(__('This document is password protected. Please go to the site, and enter the password required to display the document', $this->textdomain));
					}

					// Security check: Attachments not accessible (through EG-Attachments rule)
					if ( 0 < $this->options['logged_users_only'] && !is_user_logged_in() ) {
						wp_die(__('This document is available only to the connected site\'s users.', $this->textdomain));
					}

					$this->record_click($parent_id, $parent_title, $attach_id, $attach_title);

					if ($_GET['sa'] < 1) {
						if ( !is_attachment() ) {
							wp_redirect(esc_url(wp_get_attachment_url($attach_id)));
							exit;
						}
					}
					else { // Force "Save as"

						global $is_IE;

						$chunksize	= 2*(1024*1024);
						$file_path	= get_attached_file($attach_id);
						$stat 		= @stat($file_path);
						$etag		= sprintf('%x-%x-%x', $stat['ino'], $stat['size'], $stat['mtime'] * 1000000);
						$path 		= pathinfo($file_path);

						if ( isset($path['extension']) && strtolower($path['extension']) == 'zip' && $is_IE && ini_get('zlib.output_compression') ) {
							ini_set('zlib.output_compression', 'Off');
							// apache_setenv('no-gzip', '1');
						}

						header('Pragma: public');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Cache-Control: private', FALSE);
						header('Content-Type: application/force-download', FALSE);
						header('Content-Type: application/octet-stream', FALSE);
						header('Content-Type: application/download', FALSE);
						header('Content-Disposition: attachment; filename="'.basename($file_path).'";');
						header('Content-Transfer-Encoding: binary');
						header('Last-Modified: ' . date('r', $stat['mtime']));
						header('Etag: "' . $etag . '"');
						header('Content-Length: '.$stat['size']);
						header('Accept-Ranges: bytes');
						ob_flush();
						flush();
						if ($stat['size'] < $chunksize) {
							@readfile($file_path);
						}
						else {
							$handle = fopen($file_path, 'rb');
							while (!feof($handle)) {
								echo fread($handle, $chunksize);
								ob_flush();
								flush();
							}
							fclose($handle);
						}
						exit();
					} // End of force save as

				} // End of isset attach_id
			} // End of if $_GET[aid]

		} // End of manage_link

		/**
		  *  record_click
		  *
		  * Record the click (download) in the statistics table
		  *
		  * @package EG-Attachments
		  *
		  * @param  int		$parent_id		the post from where the user click
		  * @param	string	$parent_title	Title of this post
		  * @param	int		$attach_id		id of the attachment to download
		  * @param	strong	$attach_title	Title of the attachment
		  * @return	none
		  */
		function record_click($parent_id, $parent_title, $attach_id, $attach_title) {
			global $wpdb;

			// changed in 2.0.3. stats_enabled is calculated in the init function
			// $stats_enable = $this->options['stats_enable'] && $this->options['clicks_table'];
			//if ($this->stats_enabled && $this->options['stats_ip_exclude'] != '') {
			//	$stat_ip = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : FALSE);
			//	if ($stat_ip !== FALSE ) {
			//		$stats_enabled = (! in_array($stat_ip, explode(',', $this->options['stats_ip_exclude'])) );
			//	}
			// }

			if ($this->stats_enabled) {

				if ( !isset($this->options['on_duplicate'] ) || 1 > $this->options['on_duplicate'] ) {
					$sql = $wpdb->prepare('SELECT `click_id`,clicks_number FROM `wp_eg_attachments_clicks` WHERE click_date=CURRENT_DATE() AND attach_id=%d AND post_id=%d',
											$attach_id, $parent_id);
					$results = $wpdb->get_results($sql);
					if ( $results && 0 < sizeof($results) ) {
						$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'eg_attachments_clicks SET clicks_number=clicks_number+1 WHERE click_id=%d',
									$results[0]->click_id);
					}
					else {
						$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'eg_attachments_clicks '.
									'(click_date,post_id,post_title,attach_id,attach_title,clicks_number) values '.
									'(CURRENT_DATE(),%d,%s,%d, %s, %d)',
									$parent_id, $parent_title, $attach_id, $attach_title, 1);
					}
					$wpdb->query($sql);
				}
				else {
					// Count click
					$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'eg_attachments_clicks '.
									'(click_date,post_id,post_title,attach_id,attach_title,clicks_number) values '.
									'(CURRENT_DATE(),%d,%s,%d, %s, %d)'.
									'ON DUPLICATE KEY UPDATE clicks_number=clicks_number+1',
									$parent_id, $parent_title, $attach_id, $attach_title, 1);
					$wpdb->query($sql);
				}
			} // End of stat enable
		} // End of record_click

		/**
		  *  get_type() - Try to get type of document according mime type
		  *
		  * @package EG-Attachments
		  * @param  string 	$mime_type		mime type of the attachment as stored in the DB
		  * @return string					readable type of the attachment
		  */
		function get_type($attachment) {

			if ( isset($attachment->file_ext) ) {
				$type = wp_ext2type( $attachment->file_ext );
			}
			else {
				$parts = explode('/', $attachment->post_mime_type);
				$type = $parts[0];
			}
			return ucfirst($type);
		} // End of get_type

		/**
		  *  icon_dirs() - Add the icon path of the plugin, to the list of paths of WordPress icons
		  *
		  * @package EG-Attachments
		  *
		  * @package EG-Attachments
		  * @param $args	array		list of path and url (array( path1 => url1, path2 => url2 ...))
		  * @return 		array		the previous array, with additional paths
		  */
		function icon_dirs($args) {

			// If $args is not an array => return directly the value
			if ( is_array($args) ) {
				// If icon_path, and icon_url are specified, add them
				if ( '' != $this->options['icon_path'] && '' !=	$this->options['icon_url'] ) {
					$path = wp_normalize_path( path_join(ABSPATH,$this->options['icon_path']) );
					if ( file_exists($path) ) {
						$args = array_merge(
										$args,
										array( $path => path_join(get_bloginfo('url'), $this->options['icon_url']) )
									);
					}
				}
				else {
					// Get the path of all icons set
					$icon_set_list     = EG_Attachments_Common::get_icon_set_list($this->textdomain);
					$path_url_for_ext  = EG_Attachments_Common::get_icon_for_file_ext($this->path, $this->url);
					$path_url_for_type = EG_Attachments_Common::get_icon_for_type($this->path, $this->url);

					// Check the current icon set id: if the current icon set is no more available, we go back to the default one
					EG_Attachments_Common::check_icons_set($icon_set_list, $this->options);

					// Add path/url of extension before the WP default path/url, and the path/url for type after.
					if  ( isset( $path_url_for_ext[$this->options['icon_set']] )) {
						$args = array_merge(
										$path_url_for_ext[$this->options['icon_set']],
										$args);
					} // path/url for extension are defined

					if  ( isset( $path_url_for_type[$this->options['icon_set']] )) {
						$args = array_merge(
										$args,
										$path_url_for_type[$this->options['icon_set']]
									);
					} // path/url for types are defined

				} // icon_path & icon_url not specified

			} // No path / url provided
			return ($args);

		} // End of icon_dirs


		/**
		  * get_icon_url() - Get the url of the attachment's icon
		  *
		  * Get the thumbnail of the file when the option icon_image is set, or get the mime type's icon
		  *
		  * @package EG-Attachments
		  *
		  * @return string link (http) to the icon
		  */
		function get_icon_url($id, $icon_image) {

			$icon_url = false;
			// --- New in 2.0.1
			if ( 'thumbnail' == $icon_image && $image = image_downsize($id, 'thumbnail'))
				$icon_url = $image[0];
			else
				$icon_url = wp_mime_type_icon($id);

			if ( ! $icon_url ) {
				$icon_url = includes_url('/images/crystal/default.png');
			}

			return ($icon_url);

		} // End of get_icon_url

		/**
		  * Get_icon() - Get the thumbnail of the atttachment
		  *
		  * @package EG-Attachments
		  *
		  * @return string html entities IMG
		  */
		function get_icon($html, $attachment, $icon_image) {
			$output = $html;

			preg_match_all("/%ICON-([0-9]+)x([0-9]+)%/", $html, $matches);
			if ($matches) {

				// Get url
				$icon_url = $this->get_icon_url($attachment->ID, $icon_image);

				foreach ($matches[0] as $key => $pattern) {
					$hwstring = image_hwstring($matches[1][$key], $matches[2][$key]);
					$output   = preg_replace('/'.$pattern.'/', '<img src="'.$icon_url.'" '.$hwstring.' alt="'.$attachment->alt_text.'" />', $output);

				} // End of foreach
			} // End of if matches
			return ($output);
		} /* end of get_icon */

		/**
		  * posts_where
		  *
		  * add a WHERE condition to the query built by get_posts
		  *
		  * @package EG-Attachments
		  *
		  * @param  string	existing where conditions
		  * @return	string	the new where conditions
		  */
		function posts_where($where) {
			global $wpdb;

			if ($where != '') {
				$where = str_replace($wpdb->posts.'.post_mime_type LIKE \'notimage/%\'', $wpdb->posts.'.post_mime_type NOT LIKE \'image/%\'',$where);
			}
			return $where;

		} // End of posts_where

		/**
		  * posts_join
		  *
		  * add a JOINTURE between wp_posts and wp_eg_attachments_cliks tables, to the query built by get_posts
		  *
		  * @package EG-Attachments
		  *
		  * @param  string	existing join string
		  * @return	string	the new join, including the table wp_eg_attachments_clicks
		  */
		function posts_join( $join ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'eg_attachments_clicks';
			$join .= ' LEFT JOIN '. $table_name .' ON '.$wpdb->posts.'.ID='.$table_name.'.attach_id' ;
			if ( 0 < $this->id ) {
				// 2.1.3 => Replace posts.ID by posts.post_parent
				$join .= ' AND '.$wpdb->posts.'.post_parent='.$table_name.'.post_id';
			}
			return ($join);

		} // End of posts_join


		/**
		  * posts_fields
		  *
		  * add the number of clicks to the list of fields returned by get_posts
		  *
		  * @package EG-Attachments
		  *
		  * @param  string	existing list of fields
		  * @return	string	the existing list of fields happened with the number of clicks
		  */
		function posts_fields( $fields ) {
			global $wpdb;

			return ($fields . ', SUM('.$wpdb->prefix . 'eg_attachments_clicks.clicks_number) AS clicks' );

		} // End of posts_fields

		/**
		  * posts_groupby
		  *
		  * Add a GROUP BY condition to the query built by get_posts
		  *
		  * @package EG-Attachments
		  *
		  * @param
		  *
		  */
		function posts_groupby( $groupby ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'eg_attachments_clicks';
			return ($groupby . ( '' == $groupby ? '' : ', ').$wpdb->posts.'.ID');
		} // End of posts_groupby

		/**
		  * sort_list
		  *
		  * Sort attachments' list
		  *
		  * @package EG-Attachments
		  *
		  * @param
		  * @return
		  */
		function sort_list($a, $b) {
			return ( 'ASC' === $this->order[1]
						?  $a->{$this->order[0]} > $b->{$this->order[0]}
						:  $a->{$this->order[0]} < $b->{$this->order[0]}
					);
		} // End of function sort_list


		/**
		  * Add date, size, extension to the list of attachments
		  *
		  *
		  * @package EG-Attachments
		  *
		  * @param array $attachments	list of attachements
		  * @return
		  */
		function add_fields( & $attachments ) {

			/* --- Get file date, size, and extension --- */
			foreach ($attachments as $attachment) {
				// Added in the version 2.0.3
				$file_name = get_attached_file($attachment->ID);
				if ( FALSE != $file_name ) {

					// Added in the version 2.0.3
					$pathinfo = pathinfo($file_name);
					$attachment->file_ext  = $pathinfo['extension'];
					$info = @stat($file_name);
					if ( FALSE !== $info ) {
						$attachment->file_date = date('Y-m-d H:i:s', $info['mtime']);
						$docsize = intval($info['size']);

						if ( 0 == $docsize ) {
							$attachment->file_size = __('unknown', $this->textdomain);
						}
						else {
							$size_value = explode(' ',size_format($docsize, 0)); // WP function found in file wp-includes/functions.php
							$attachment->file_size = $size_value[0];
							$attachment->file_size_unit = __($size_value[1]);
						}
					} // End of get info

					// Added in the version 2.0.3
					$attachment->alt_text = trim(strip_tags( get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) ) );
					if ( empty($attachment->alt_text) )
						$attachment->alt_text = trim(strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
					if ( empty($attachment->alt_text) )
						$attachment->alt_text = trim(strip_tags( $attachment->post_title )); // Finally, use the title
				} // if get file_name

				if ( null == $attachment->clicks) $attachment->clicks = 0;
			} // End of foreach attachment

		} // End of add_fields

		/**
		  * get_template
		  *
		  * @package EG-Attachments
		  *
		  * @param object template
		  * @return string error message if error, empty string if everything is ok.
		  */
		function get_template( $template, & $template_content ) {

			$error_msg = '';
			$cache_entry = strtolower($this->name).'-shortcode-tmpl';
			$templates = (EGA_ENABLE_CACHE ? get_transient($cache_entry) : FALSE);
			if (FALSE !== $templates && isset($templates[$template])) {
				$template_content = $templates[$template];
			}
			else {

				// Query
				$tmpl = get_posts( array('post_type' => EGA_TEMPLATE_POST_TYPE, 'name' => $template));
				if (! $tmpl) {
					$error_msg = esc_html__('Template doesn\'t exists. Use default', $this->textdomain);
					$tmpl = get_posts( array('post_type' => EGA_TEMPLATE_POST_TYPE, 'name' => $EGA_SHORTCODE_DEFAULTS['shortcode_auto_size']));
				}

				// Parse the result
				if ($tmpl) {
					if (FALSE === $templates) $templates = array();

					$template_content = EG_Attachments_Common::parse_template($tmpl[0]->post_content);
					if (FALSE === $template) {
						$error_msg = esc_html__('Error during processing shortcode template', $this->textdomain);
					}
					elseif (EGA_ENABLE_CACHE) {
						$templates[$template] = $template_content;
						set_transient($cache_entry, $templates, EGA_TEMPLATE_CACHE_EXPIRATION);
					}
				} // End of template found
			} // End of no cache

			return ($error_msg);
		} // End of get_template



		/**
		  * The eg-attachments shortcode.
		  *
		  * This implements the functionality of the Attachments Shortcode for displaying
		  * WordPress documents on a post.
		  *
		  * @package EG-Attachments
		  *
		  * @param array $attr Attributes of the shortcode.
		  * @return string HTML content to display gallery.
		  */
		function get_attachments($atts) {

			global $EGA_SHORTCODE_DEFAULTS;
			global $EGA_FIELDS_ORDER_KEY;
			global $post;

			// Update default shortcode parameters with the options
			$EGA_SHORTCODE_DEFAULTS = EG_Attachments_Common::get_shortcode_defaults($this->options);
/*
			$EGA_SHORTCODE_DEFAULTS['force_saveas'] 	 = $this->options['force_saveas'];
			$EGA_SHORTCODE_DEFAULTS['logged_users'] 	 = $this->options['logged_users_only'];
			$EGA_SHORTCODE_DEFAULTS['login_url'] 		 = $this->options['login_url'];
			$EGA_SHORTCODE_DEFAULTS['nofollow'] 		 = $this->options['nofollow'];
			$EGA_SHORTCODE_DEFAULTS['icon_image'] 		 = $this->options['icon_image'];
			$EGA_SHORTCODE_DEFAULTS['target'] 			 = $this->options['target_blank'];
			$EGA_SHORTCODE_DEFAULTS['exclude_thumbnail'] = $this->options['exclude_thumbnail'];
*/
			// Extract shortcode parameters
			extract( shortcode_atts( $EGA_SHORTCODE_DEFAULTS, $atts ) );

			// Check Security: if the post is password protected, or private, we don't display anything
			if ( post_password_required($post->ID) ||
				( 'private' == get_post_field('post_status', $post->ID) && !is_user_logged_in() ) ||
				( 2 == $logged_users && !is_user_logged_in() ) ) {
				return '';
			}

			// Get the post parent id
			$this->id = (0 == $id ? $post->ID : $id);

			// Manage deprecated parameter "docid"
			if (0 != $docid && '' == $include)
				$include = $docid;

			// Manage sort parameters
			//list($this->order_by, $this->order) = explode(' ', strtolower($orderby));     // Get shortcodee parameters
			$this->order = explode(' ', strtolower($orderby));     // Get shortcodee parameters
			list($orderby_default, $order_default) = $EGA_SHORTCODE_DEFAULTS['orderby'];  // Get default values

			$this->order[0] = (isset($EGA_FIELDS_ORDER_KEY[$this->order[0]]) ? $EGA_FIELDS_ORDER_KEY[$this->order[0]] : $orderby_default);
			if ( ! isset( $this->order[1] ) )
				$this->order[1] = 'ASC';
			else
				$this->order[1] = strtoupper(in_array($this->order[1], array('asc', 'desc')) ? $this->order[1] : $order_default);

			/* -------------------------------------------------------------------
			   Manage deprecated parameter "size", and "icon"
			   - $template is already set, with shortcode_atts. So we use it as default value
			   ------------------------------------------------------------------- */

			// If size is used, and template is not used,
			if ( '' != $size && ! isset($atts['template']) ) {
				// If size = custom, try to get the old custom template get from the previous version
				if ( 'custom' == $size ) {
					if ('' != $this->options['legacy_custom_format'])
						$template = $this->options['legacy_custom_format'];
				}
				else {
					// $size is one of the other value (large, medium, or small)
					// if $icon is set to 0, or FALSE, add "-list" to the template name
					$template = $size;
					if (FALSE === strpos($size, '-list') && ! $icon) $template .= '-list';
				}
			} // No template defined

			// Getting the template
			$template_content = null;
			$error_msg = $this->get_template( $template, $template_content );

			// Preparing query
			$params = array( /* 'numberposts' 	=> $limit, */
							'posts_per_page'	=> -1,
							'post_type'   		=> 'attachment',
							'suppress_filters' 	=> false,
							'orderby'			=> $this->order[0],
							'order'				=> $this->order[1] /*,
							'post_mime_type'	=> ( 'image' == $doctype ? 'image' : 'notimage' ) */
						);

			// --- V2.1.2
			if ( 'image' == $doctype )
				$params['post_mime_type'] = 'image';
			elseif ( 'document' == $doctype )
				$params['post_mime_type'] = 'notimage';

			// Add include parameter
			if ( '' != $include )
				$params['include'] = $include;

			// Add post_parent, and exclude parameters
			if ( 0 < $this->id ) {
				$params['post_parent'] = $this->id;

				if ( 0 !== intval($exclude_thumbnail) ) {
					$featured_id = get_post_thumbnail_id($this->id);
					if ( FALSE !== $featured_id && '' != $featured_id )
						$exclude = ( '' == $exclude ? $featured_id : $exclude.','.$featured_id );
				} // End of exclude thumbnail
			} // End of parent specified

			if ( '' != $exclude )
				$params['exclude'] = $exclude;

			// Add tag, tag_slug__in, tag_slug__and parameters
			if ('' != $tags) {
				$list = explode(',', $tags);
				if (! is_array($list)) $params['tag'] = $list;
				else {
					if (sizeof($list) == 1) $params['tag'] = current($list);
					else $params['tag_slug__in'] = $list;
				}
			} // End of tags != ''
			else {
				if ('' != $tags_and) {
					$list = explode(',', $tags_and);
					if (! is_array($list)) $params['tag'] = $list;
					else {
						if (sizeof($list) == 1) $params['tag'] = current($list);
						else $params['tag_slug__and'] = $list;
					}
				} // End of tags_and=''
			} // End of tags=''

			/**
			  * Is there a similar query in the cache?
			  *
			  */
			$cache_entry = EG_Attachments_Common::get_cache_entry($this->name, ( 0 < $this->id ? $this->id : 'all') );
			$cache_id  = md5(implode('', $params));
			$cache = ( EGA_ENABLE_CACHE ? get_transient($cache_entry) : FALSE );
			if ( $cache && is_array($cache) && isset($cache[$cache_id])) {
				$attachments = $cache[$cache_id];
				unset($cache);
			}
			else {

				/**
				  * Query DB
				  */
				if ( $this->stats_enabled ) {
					add_filter('posts_groupby',    	array(&$this, 'posts_groupby'), 10, 2	);
					add_filter('posts_fields',     	array(&$this, 'posts_fields'),  10, 2	);
					add_filter('posts_join',     	array(&$this, 'posts_join'),    10, 2	);
				}
				add_filter('posts_where', 			array(&$this, 'posts_where')			);

				$attachments = get_posts($params);

				remove_filter('posts_where', 		array(&$this, 'posts_where') 			);
				if ( $this->stats_enabled ) {
					remove_filter('posts_join',  	array(&$this, 'posts_join') 			);
					remove_filter('posts_fields',  	array(&$this, 'posts_fields') 			);
					remove_filter('posts_groupby',  array(&$this, 'posts_groupby') 			);
				}

				// No attachments found => stop the shortcode and return empty string
				if (! $attachments || sizeof($attachments) == 0 ) {
					return '';
				}
// eg_plugin_error_log('EG-Attachments', 'Before add fields: ', $attachments);
				// Add missing fields: file date, file size, click counter, ...
				$this->add_fields( $attachments );
// eg_plugin_error_log('EG-Attachments', 'After add fields: ', $attachments);

				if ( EGA_ENABLE_CACHE ) {
					$cache[$cache_id] = $attachments;
					set_transient($cache_entry, $cache, EGA_SHORTCODE_CACHE_EXPIRATION);
				}

			} // End of cache empty

			/* --- Sort list of --- */
			/* get_posts allows only the following sort keys: 'name', 'author', 'date', 'title', 'modified', 'menu_order', 'ID', 'rand', 'comment_count' */
			if ( ! in_array( $this->order[0], array( 'name', 'author', 'date', 'title', 'modified', 'menu_order', 'ID', 'rand', 'comment_count' ) ) ) {
				uasort($attachments, array($this, 'sort_list') );
			}

			/* --- Get only required attachments --- */
			if ( 0 < $limit ) {
				$attachments = array_slice($attachments, 0, $limit);
			}

			/* --- Prepare loop --- */
			$date_format = ( $this->options['date_format']!='' ? $this->options['date_format'] : get_option('date_format') );
			$output = '';
			add_filter('icon_dirs', array(&$this, 'icon_dirs'));


			/* --- Starting loop --- */
			reset($attachments);
			foreach ($attachments as $attachment) {

				if ( post_password_required($attachment->post_parent) || ('private' == get_post_status($attachment->ID)  && !is_user_logged_in()) ) {
					continue;
				}

				$alt_img_icon 	= '';
				$lock_icon 		= '';
				$url 			= '';
				$click_count 	= '';

				if ( 1 == $logged_users && !is_user_logged_in()) {
					$url = $file_url = $attach_url = $direct_url = ( '' != $this->options['login_url'] ?
							$this->options['logged_users'] :
							wp_login_url( apply_filters( 'the_permalink', get_permalink( $post->ID )))
					);
					$alt_img_icon = __('You need to login to access to the attachments', $this->textdomain);
				} // End of attachments required login

				if ($alt_img_icon != '') {
					$lock_icon = '<img class="lock" src="'.$this->url.'img/lock.png" height="16" width="16" alt="'.$alt_img_icon.'" />';
				}

				if ('' == $url) {
					$query_args = array('aid' => $attachment->ID, 'sa' => $force_saveas);
					$attach_url = add_query_arg( array_merge(array('pid' => $post->ID),$query_args), get_permalink($attachment->ID) );

					$file_url   = wp_get_attachment_url( $attachment->ID );
					$direct_url = add_query_arg( $query_args, get_permalink($post->ID) );

					if ('link' == $this->options['link'])
						$url = $attach_url;
					elseif ('file' == $this->options['link'])
						$url = $file_url;
					else
						$url = $direct_url;
				} // Url empty

				$file_date = mysql2date($date_format, $attachment->file_date, TRUE);
				$post_date = mysql2date($date_format, $attachment->post_date, TRUE);

				$item = html_entity_decode(stripslashes($template_content['loop']));
				$item = preg_replace("/%LINK_URL%/",		$attach_url,													$item);
				$item = preg_replace("/%URL%/",				$url,															$item);
				$item = preg_replace("/%FILE_URL%/",		$file_url,														$item);
				$item = preg_replace("/%DIRECT_URL%/",		$direct_url,													$item);
				$item = preg_replace("/%GUID%/",			$attachment->guid,												$item);
				$item = $this->get_icon($item, $attachment, $icon_image);
				$item = preg_replace("/%ICONURL%/",			$this->get_icon_url($attachment->ID, $icon_image),				$item);
				$item = preg_replace("/%TITLE%/",			esc_html($attachment->post_title),								$item);
				$item = preg_replace("/%TITLE_LABEL%/",		esc_html__('Title'), 											$item);
				$item = preg_replace("/%CAPTION%/",    		esc_html($attachment->post_excerpt),							$item);
				$item = preg_replace("/%CAPTION_LABEL%/", 	esc_html__('Caption', $this->textdomain), 						$item);
				$item = preg_replace("/%DESCRIPTION%/", 	esc_html($attachment->post_content),							$item);
				$item = preg_replace("/%DESCRIPTION_LABEL%/", esc_html__('Description', $this->textdomain),			 		$item);
				// Changed in 2.0.3
				// $item = preg_replace("/%FILENAME%/",		esc_html(basename(get_attached_file($attachment->ID))),			$item);
				$item = preg_replace("/%FILENAME%/",		esc_html($attachment->post_name).'.'.esc_html($attachment->file_ext),	$item);
				$item = preg_replace("/%FILENAME_LABEL%/",	esc_html__('Filename', $this->textdomain), 						$item);
				// Added in 2.0.3
				$item = preg_replace("/%EXT%/",				esc_html($attachment->file_ext),								$item);
				$item = preg_replace("/%EXT_LABEL%/",		esc_html__('Extension', $this->textdomain), 					$item);
				// Added in 2.0.3
				$item = preg_replace("/%MIME%/",			esc_html($attachment->post_mime_type),							$item);
				$item = preg_replace("/%MIME_LABEL%/",		esc_html__('Mime type', $this->textdomain), 					$item);

				// Change in 2.0.3
				$item = preg_replace("/%FILESIZE%/",		esc_html($attachment->file_size.' '.$attachment->file_size_unit),$item);
				// $item = preg_replace("/%FILESIZE%/",		esc_html($this->get_file_size($attachment /* ->ID)*/)),			$item);
				$item = preg_replace("/%FILESIZE_LABEL%/",	esc_html__('Size', $this->textdomain), 							$item);

				$item = preg_replace("/%ATTID%/",       	$attachment->ID,												$item); //For use with stylesheets
				$item = preg_replace("/%TYPE%/",		  	esc_html($this->get_type($attachment)),							$item);
				$item = preg_replace("/%TYPE_LABEL%/",	 	esc_html__('Type', $this->textdomain), 							$item);
				$item = preg_replace("/%DATE%/",		   	esc_html($file_date),											$item);
				$item = preg_replace("/%POSTDATE%/",		esc_html($post_date),											$item);
				$item = preg_replace("/%DATE_LABEL%/",  	esc_html__('Date', $this->textdomain), 							$item);
				$item = preg_replace("/%SHOWLOCK%/",  		$lock_icon, 													$item);
				$item = preg_replace("/%COUNTER%/",  		esc_html( $attachment->clicks ), 								$item);
				$item = preg_replace("/%COUNTER_LABEL%/",	esc_html__((2>$attachment->clicks?'click':'clicks'), $this->textdomain),		$item);

				if ( $nofollow )
					$item = preg_replace("/%NOFOLLOW%/",	'rel="nofollow"', 												$item);
				else
					$item = preg_replace("/%NOFOLLOW%/",	'', 															$item);

				if ( $target ) {
					$item = preg_replace("/%TARGET=([^%]*)%/",	'target=$1', 												$item);
					$item = preg_replace("/%TARGET%/",		'target="_blank"', 												$item);
				}
				else {
					$item = preg_replace("/%TARGET=([^%]*)%/",	'', 														$item);
					$item = preg_replace("/%TARGET%/",		'', 															$item);
				}
				$output .= $item;

			} // End foreach attachment
			remove_filter('icon_dirs', array(&$this, 'icon_dirs'));

			if ($output != '') {
				$output = html_entity_decode($template_content['before']) . $output . html_entity_decode($template_content['after']);
				$output = $this->shortcode_title($output, $title, $titletag);
				$output = '<div class="attachments">'.$output.'<p>'.$error_msg.'</p></div>';
			} // End of $output

			return ($output);
		} // End of get_attachments

		/**
		 * get_shortcode_parameters
		 *
		 * Get the plugin options for the shortcode, and return them
		 *
		 * @param 						none
		 * @return 	array				list of parameters depending of the plugin options
		 */
		function get_shortcode_parameters() {
			return array(
						'template' 	=> $this->options['shortcode_auto_template'],
						'doctype'	=> $this->options['shortcode_auto_doc_type'],
						'title'		=> $this->options['shortcode_auto_title'],
						'titletag'  => $this->options['shortcode_auto_title_tag'],
						'orderby'   => $this->options['shortcode_auto_orderby'].' '.$this->options['shortcode_auto_order'],
						'limit'		=> $this->options['shortcode_auto_limit']
					);
		} // End of get_shortcode_parameters

		/**
		 * shortcode_auto_excerpt
		 *
		 * Display list of attachment in the post excerpt
		 *
		 * @param 	string	$excerpt	post excerpt
		 * @return 	string				modified excerpt
		 */
		function shortcode_auto_excerpt($output) {

			if ($output &&
				3 == $this->options['shortcode_auto'] &&
			    $this->shortcode_is_visible() &&
				$this->shortcode_auto_check_manual_shortcode(EGA_SHORTCODE)) {

				$attrs = $this->get_shortcode_parameters();

				$output = $this->get_attachments($attrs).$output;
			} // End of shortcode activated and visible
			return ($output);

		} // End of shortcode_auto_excerpt

		/**
		 * shortcode_auto_content
		 *
		 * Display list of attachment in the post content
		 *
		 * @param 	string	$content	post_content
		 * @return 	string				modified post content
		 */
		function shortcode_auto_content($content = '') {
			global $post;


			if ($this->options['shortcode_auto']  > 0 	&&
			 	$this->shortcode_is_visible() 			&&
				$this->shortcode_auto_check_manual_shortcode(EGA_SHORTCODE)) {

				$attrs = $this->get_shortcode_parameters();

				$shortcode_output = $this->get_attachments($attrs);

				switch ($this->options['shortcode_auto']) {
					case 2: // At the end of post
						if (FALSE === strpos( $content, '#more-'.$post->ID) && FALSE === strpos($content, 'class="more-link"') )
							$content .= $shortcode_output;
					break;

					case 3: // Before the excerpt
						if (! $post->post_excerpt)
							$content = $shortcode_output . $content;
					break;

					case 4:
						if ($post->post_excerpt) {
							// Case of manual excerpt
							$content = $shortcode_output . $content;
						}
						else {
							// Case of teaser
							if(strpos($content, 'span id="more-')) {
								$parts = preg_split('/(<span id="more-' . $post->ID . '"><\/span>)/', $content, -1,  PREG_SPLIT_DELIM_CAPTURE);
								$content = $parts[0].$parts[1].$shortcode_output.$parts[2];
							} // End of detect tag "more"
						} // End of teaser case
					break;
				} // End of switch
			} // End of shortcode is activated and visible
			return ($content);
		} // End of shortcode_auto_content

		/**
		 * load
		 *
		 * Add "init" hook to the plugin
		 *
		 * @param 	none
		 * @return 	none
		 */
		function load() {
			parent::load();
			add_action('init', array( &$this, 'init') );

		} // End of load

	} /* End of Class */

} /* End of if class_exists */

$eg_attach_public = new EG_Attachments_Public(
							'EG-Attachments',
							EGA_VERSION,
							EGA_OPTIONS_ENTRY,
							EGA_TEXTDOMAIN,
							EGA_COREFILE,
							$EGA_DEFAULT_OPTIONS);

$eg_attach_public->add_stylesheet('css/eg-attachments.css');
$eg_attach_public->load();

?>