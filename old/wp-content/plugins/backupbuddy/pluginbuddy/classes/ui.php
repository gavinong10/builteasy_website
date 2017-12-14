<?php



/*	pluginbuddy_ui class
 *	
 *	Handles typical user interface items used in WordPress development.
 *	
 *	@author Dustin Bolton
 *	@version 1.0.0
 */
class pb_backupbuddy_ui {

	private $_tab_interface_tag = '';
	
	
	/*	pluginbuddy_ui->start_metabox()
	 *	
	 *	Starts a metabox. Use with end_metabox().
	 *	@see pluginbuddy_ui->end_metabox
	 *	
	 *	@param		string				$title				Title to display for the metabox.
	 *	@param		boolean				$echo				Echos if true; else returns.
	 *	@param		boolean/string		$small_or_css		true: size is limited smaller. false: size is limited larger. If a string then interpreted as CSS.
	 *	@return		null/string								Returns null if $echo is true; else returns string with HTML.
	 */
	public function start_metabox( $title, $echo = true, $small_or_css = false ) {
		if ( $small_or_css === false ) { // Large size.
			$css = 'width: 70%; min-width: 720px;';
		} elseif ( $small_or_css === true ) { // Small size.
			$css = 'width: 20%; min-width: 250px;';
		} else { // String so interpret as CSS.
			$css = $small_or_css;
		}
		
		$css .= ' padding-top: 0; margin-top: 10px; cursor: auto;';
		
		$response = '<div class="metabox-holder postbox" style="' . $css . '">
						<h3 class="hndle" style="cursor: auto;"><span>' . $title . '</span></h3>
						<div class="inside">';
		if ( $echo === true ) {
			echo $response;
		} else {
			return $response;
		}
	} // End start_metabox().
	
	
	
	/*	pluginbuddy_ui->end_metabox()
	 *	
	 *	Ends a metabox. Use with start_metabox().
	 *	@see pluginbuddy_ui->start_metabox
	 *	
	 *	@param		boolean		$echo		Echos if true; else returns.
	 *	@return		null/string				Returns null if $echo is true; else returns string with HTML.
	 */
	public function end_metabox( $echo = true ) {
		$response = '	</div>
					</div>';
		if ( $echo === true ) {
			echo $response;
		} else {
			return $response;
		}
	} // End end_metabox().
	
	
	
	/*	pluginbuddy_ui->title()
	 *	
	 *	Displays a styled, properly formatted title for pages.
	 *	
	 *	@param		string		$title		Title to display.
	 *	@param		boolean		$echo		Whether or not to echo the string or return.
	 *	@return		null/string				Returns null if $echo is true; else returns string with HTML.
	 */
	public function title( $title, $echo = true ) {
		$return = '<h1><span class="backupbuddy-icon-drive" style="font-size: 1.2em; vertical-align: -4px;"></span> ' . $title . '</h1><br />';
		if ( $echo === true ) {
			echo $return;
		} else {
			return $return;
		}
	} // End title().
	
	
	
	/*	pluginbuddy_ui->button()
	 *	
	 *	Displays a nice pretty styled button. How nice. Always returns.
	 *	
	 *	@param		string		$url				URL (href) for the button to link to.
	 *	@param		string		$text				Text to display in the button.
	 *	@param		string		$title				Optional title text to display on hover in the title tag.
	 *	@param		boolean		$primary			(optional) Whether or not this is a primary button. Primary buttons are blue and strong where default non-primary is grey and gentle.
	 *	@param		string		$additional_class	(optional) Additional CSS class to apply to button. Useful for thickbox or other JS stuff.
	 *	@param		string		$id					(optional) HTML ID to apply. Useful for JS.
	 *	@return		string							HTML string for the button.
	 */
	public function button( $url, $text, $title = '', $primary = false, $additional_class = '', $id = '' ) {
		if ( $primary === false ) {
			return '<a class="button secondary-button ' . $additional_class . '" style="margin-top: 3px;" id="' . $id . '" title="' . $title . '" href="' . $url . '">' . $text . '</a>';
		} else {
			return '<a class="button button-primary ' . $additional_class . '" style="margin-top: 3px;" id="' . $id . '" title="' . $title . '" href="' . $url . '">' . $text . '</a>';
		}
	} // End button().
	
	
	
	/*	pluginbuddy_ui->note()
	 *	
	 *	Display text in a subtle way.
	 *	
	 *	@param		string		$text		Text of note.
	 *	@param		boolean		$echo		Whether or not to echo the string or return.
	 *	@return		null/string				Returns null if $echo is true; else returns string with HTML.
	 */
	public static function note( $text, $echo = true ) {
		$return = '<span class="description"><i>' . $text . '</i></span>';
		if ( $echo === true ) {
			echo $return;
		} else {
			return $return;
		}
	} // End note().
	
	
	
	/*	pluginbuddy_ui->list_table()
	 *	
	 *	Displays a nice table with multiple columns, rows, bulk actions, hover actions, etc similar to WordPress posts table.
	 *	Currently only supports echo of output.
	 *	
	 *	@param		array		$items		Array of rows to display. Each row array contains an array with the columns. Typically set in controller.
	 *										Ex: array( array( 'blue', 'red' ), array( 'brown', 'green' ) ).
	 *										If the value for an item is an array then the first value will be assigned to the rel tag of any hover actions. If not
	 *										an array then the value itself will be put in the rel tag.  If an array the second value will be the one displayed in the column.
	 *										BackupBuddy needed the displayed item in the column to be a link (for downloading backups) but the backup file as the rel.
	 *	@param		array		$settings	Array of all the various settings. Merged with defaults prior to usage. Typically set in view.
	 *										See $default_settings at beginning of function for available settings.
	 *										Ex: $settings = array(
	 *												'action'		=>		pb_backupbuddy::plugin_url(),
	 *												'columns'		=>		array( 'Group Name', 'Images', 'Shortcode' ),
	 *												'hover_actions'	=>		array( 'edit' => 'Edit Group Settings' ),		// Slug can be a URL. In this case the value of the hovered row will be appended to the end of the URL. TODO: Make the first hover action be the link for the first listed item.
	 *												'bulk_actions'	=>		array( 'delete_images' => 'Delete' ),
	 *											);
	 *	@return		null
	 */
	public static function list_table( $items, $settings ) {
		$default_settings = array(
								'columns'					=>	array(),
								'hover_actions'				=>	array(),
								'bulk_actions'				=>	array(),
								'hover_action_column_key'	=>	'',			// int of column to set value= in URL and rel tag= for using in JS.
								'action'					=>	'',
								'reorder'					=>	'',
								'after_bulk'				=>	'',
								'css'						=>	'',
							);
		
		// Merge defaults.
		$settings = array_merge( $default_settings, $settings );
		
		// Function to iterate through bulk actions. Top and bottom set are the same.
		if ( !function_exists( 'bulk_actions' ) ) {
			function bulk_actions( $settings, $hover_note = false ) {
				if ( count( $settings['bulk_actions'] ) > 0 ) {
					echo '<div style="padding-bottom: 3px; padding-top: 3px;">';
					if ( count( $settings['bulk_actions'] ) == 1 ) {
						foreach( $settings['bulk_actions'] as $action_slug => $action_title ) {
							echo '<input type="hidden" name="bulk_action" value="' . $action_slug . '">';
							echo '<input type="submit" name="do_bulk_action" value="' . $action_title . '" class="button secondary-button backupbuddy-do_bulk_action">';
						}
					} else {
						echo '<select name="bulk_action" class="actions">';
						foreach ( $settings['bulk_actions'] as $action_slug => $action_title ) {
							echo '<option>Bulk Actions</option>';
							echo '<option value="' . $action_slug . '">' . $action_title . '</option>';
						}
						echo '</select> &nbsp;';
						//echo self::button( '#', 'Apply' );
						echo '<input type="submit" name="do_bulk_action" value="Apply" class="button secondary-button backupbuddy-do_bulk_action">';
					}
					echo '&nbsp;&nbsp;';
					echo $settings['after_bulk'];
					
					echo '<div class="alignright actions">';
					if ( $hover_note === true ) {
						echo pb_backupbuddy::$ui->note( 'Hover over items above for additional options.' );
					}
					if ( $settings['reorder'] != '' ) {
						echo '	<input type="submit" name="save_order" id="save_order" value="Save Order" class="button-secondary" />';
					}
					echo '</div>';
					
					echo '</div>';
				}
			} // End subfunction bulk_actions().
		} // End if function does not exist.
		
		if ( $settings['action'] != '' ) {
			echo '<form method="post" action="' . $settings['action'] . '">';
			pb_backupbuddy::nonce();
			if ( $settings['reorder'] != '' ) {
				echo '<input type="hidden" name="order" value="" id="pb_order">';
			}
		}
		
		
		echo '<div style="width: 70%; min-width: 720px; ' . $settings['css'] . '">';
		
		// Display bulk actions (top).
		bulk_actions( $settings );
		
		echo '<table class="widefat"';
		echo ' id="test">';
		echo '		<thead>
					<tr class="thead">';
		if ( count( $settings['bulk_actions'] ) > 0 ) {
			echo'		<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>';
		}
		foreach ( $settings['columns'] as $column ) {
			echo '<th>' . $column . '</th>';
		}
		echo '		</tr>
				</thead>
			<tfoot>
				<tr class="thead">';
		if ( count( $settings['bulk_actions'] ) > 0 ) {
			echo'	<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>';
		}
		foreach ( $settings['columns'] as $column ) {
			echo '<th>' . $column . '</th>';
		}
		echo '	</tr>
			</tfoot>
			<tbody';
		if ( $settings['reorder'] != '' ) {
			echo ' class="pb_reorder"';
		}
		echo '>';
		
		// LOOP THROUGH EACH ROW.
		foreach ( (array)$items as $item_id => $item ) {
			echo '	<tr class="entry-row alternate" id="pb_rowitem-' . $item_id . '">';
			if ( count( $settings['bulk_actions'] ) > 0 ) {
				echo'	<th scope="row" class="check-column"><input type="checkbox" name="items[]" class="entries" value="' . $item_id . '"></th>';
			}
			echo '		<td>';
			
			if ( is_array( $item['0'] ) ) {
				if ( $item['0'][1] == '' ) {
					echo '&nbsp;';
				} else {
					echo $item['0'][1];
				}
			} else {
				if ( $item['0'] == '' ) {
					echo '&nbsp;';
				} else {
					echo $item['0'];
				}
			}
			
			echo '			<div class="row-actions" style="margin-top: 10px;">'; //  style="margin:0; padding:0;"
			$i = 0;
			foreach ( $settings['hover_actions'] as $action_slug => $action_title ) { // Display all hover actions.
				$i++;
				if ( $settings['hover_action_column_key'] != '' ) {
					if ( is_array( $item[$settings['hover_action_column_key']] ) ) {
						$hover_action_column_value = $item[$settings['hover_action_column_key']][0];
					} else {
						$hover_action_column_value = $item[$settings['hover_action_column_key']];
					}
				} else {
					$hover_action_column_value = '';
				}
				
				if ( strstr( $action_slug, 'http' ) === false ) { // Word hover action slug.
					$hover_link= pb_backupbuddy::page_url() . '&' . $action_slug . '=' . $item_id . '&value=' . $hover_action_column_value;
				} else { // URL hover action slug so just append value to URL.
					$hover_link = $action_slug . $hover_action_column_value;
				}
				
				echo '<a href="' . $hover_link . '" class="pb_' . pb_backupbuddy::settings( 'slug' ) . '_hoveraction_' . $action_slug . '" rel="' . $hover_action_column_value . '">' . $action_title . '</a>';
				if ( $i < count( $settings['hover_actions'] ) ) {
					echo ' &nbsp;|&nbsp; ';
				}
			}
			echo '			</div>
						</td>';
			
			
			if ( $settings['reorder'] != '' ) {
				$count = count( $item ) + 1; // Extra row for reordering.
			} else {
				$count = count( $item );
			}
			
			// LOOP THROUGH COLUMNS FOR THIS ROW.
			for ( $i = 1; $i < $count; $i++ ) {
				if ( ! isset( $item[$i] ) ) { continue; } // This row does not have a corresponding index-based item.  It is probably a named key not for use in table?
				echo '<td';
				if ( $settings['reorder'] != '' ) {
					if ( $i == $settings['reorder'] ) {
						echo ' class="pb_draghandle" align="center"';
					}
				}
				echo '>';
				
				if ( ( $settings['reorder'] != '' ) && ( $i == ( $settings['reorder'] ) ) ) {
					echo '<img src="' . pb_backupbuddy::plugin_url() . '/pluginbuddy/images/draghandle.png" alt="Click and drag to reorder">';
				} else {
					if ( $item[$i] == '' ) {
						echo '&nbsp;';
					} else {
						echo $item[$i];
					}
				}
				
				echo '</td>';
			}

			echo '	</tr>';
		}

		echo '	</tbody>';
		echo '</table>';
		
		// Display bulk actions (bottom).
		bulk_actions( $settings, true );
		
		echo '</div>';
		if ( $settings['action'] != '' ) {
			echo '</form>';
		}
	} // End list_table().
	
	
	/**
	 *	pb_backupbuddy::get_feed()
	 *
	 *	Gets an RSS or other feed and inserts it as a list of links...
	 *
	 *	@param		string		$feed		URL to the feed.
	 *	@param		int			$limit		Number of items to retrieve.
	 *	@param		string		$append		HTML to include in the list. Should usually be <li> items including the <li> code.
	 *	@param		string		$replace	String to replace in every title returned. ie twitter includes your own username at the beginning of each line.
	 *	@return		string
	 */
	public function get_feed( $feed, $limit, $append = '', $replace = '' ) {
		$return = '';
		
		$feed_html = get_transient( md5( $feed ) );
		
		if ( false === $feed_html ) {
			$feed_html = '';
			require_once(ABSPATH.WPINC.'/feed.php');  
			$rss = fetch_feed( $feed );
			if ( is_wp_error( $rss ) ) {
				$return .= '{Temporarily unable to load feed.}';
				return $return;
			}
			$maxitems = $rss->get_item_quantity( $limit ); // Limit 
			$rss_items = $rss->get_items(0, $maxitems); 
		}
		
		$return .= '<ul class="pluginbuddy-nodecor" style="margin-left: 10px;">';

		
		if ( $feed_html == '' ) {
			foreach ( (array) $rss_items as $item ) {
				$feed_html .= '<li style="list-style-type: none;"><a href="' . $item->get_permalink() . '" target="_blank">';
				$title =  $item->get_title(); //, ENT_NOQUOTES, 'UTF-8');
				if ( $replace != '' ) {
					$title = str_replace( $replace, '', $title );
				}
				if ( strlen( $title ) < 30 ) {
					$feed_html .= $title;
				} else {
					$feed_html .= substr( $title, 0, 32 ) . ' ...';
				}
				$feed_html .= '</a></li>';
			}
			set_transient( md5( $feed ), $feed_html, 300 ); // expires in 300secs aka 5min
		} else {
			//echo 'CACHED';
		}
		$return .= $feed_html;
		
		$return .= $append;
		$return .= '</ul>';
	
		
		return $return;
	} // End get_feed().
	
	
	
	/**
	 *	pb_backupbuddy::tip()
	 *
	 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
	 *	HTML is supposed within tooltips.
	 *
	 *	@param		string		$message		Actual message to show to user.
	 *	@param		string		$title			Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
	 *	@param		boolean		$echo_tip		Whether to echo the tip (default; true), or return the tip (false). (optional)
	 *	@return		string/null					If not echoing tip then the string will be returned. When echoing there is no return.
	 */
	public function tip( $message, $title = '', $echo_tip = true ) {
		if ( '' != $title ) {
			$message = $title . ' - ' . $message;
		}
		$tip = ' <a class="pluginbuddy_tip" title="' . $message . '"><img src="' . pb_backupbuddy::plugin_url() . '/pluginbuddy/images/pluginbuddy_tip.png" alt="(?)" /></a>';
		if ( $echo_tip === true ) {
			echo $tip;
		} else {
			return $tip;
		}
	} // End tip().
	
	
	
	/**
	 *	pb_backupbuddy::alert()
	 *
	 *	Displays a message to the user at the top of the page when in the dashboard.
	 *
	 *	@param		string		$message		Message you want to display to the user.
	 *	@param		boolean		$error			OPTIONAL! true indicates this alert is an error and displays as red. Default: false
	 *	@param		int			$error_code		OPTIONAL! Error code number to use in linking in the wiki for easy reference.
	 *	@return		null
	 */
	public function alert( $message, $error = false, $error_code = '', $rel_tag = '' ) {
		$log_error = false;

		echo '<div id="message" style="padding: 9px;" rel="' . $rel_tag . '" class="pb_backupbuddy_alert ';
		if ( $error === false ) {
			echo 'updated fade';
		} else {
			echo 'error';
			$log_error = true;
		}
		if ( $error_code != '' ) {
			$message .= ' <a href="http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#' . $error_code . '" target="_blank"><i>' . pb_backupbuddy::settings( 'name' ) . ' Error Code ' . $error_code . ' - Click for more details.</i></a>';
			$log_error = true;
		}
		if ( $log_error === true ) {
			pb_backupbuddy::log( $message . ' Error Code: ' . $error_code, 'error' );
		}
		echo '" >' . $message . '</div>';
	} // End alert().
	
	
	
	/**
	 *	pb_backupbuddy::disalert()
	 *
	 *	Displays a DISMISSABLE message to the user at the top of the page when in the dashboard.
	 *
	 *	@param		string		$message		Message you want to display to the user.
	 *	@param		boolean		$error			OPTIONAL! true indicates this alert is an error and displays as red. Default: false
	 *	@param		int			$error_code		OPTIONAL! Error code number to use in linking in the wiki for easy reference.
	 *	@return		null
	 */
	public function disalert( $unique_id, $message, $error = false ) {
		
		if ( ! isset( pb_backupbuddy::$options['disalerts'][$unique_id] ) ) {
			$message = '<a style="float: right;" class="pb_backupbuddy_disalert" href="#" title="' . __( 'Dismiss this alert. Unhide dismissed alerts on the Settings page.', 'it-l10n-backupbuddy' ) . '" alt="' . pb_backupbuddy::ajax_url( 'disalert' ) . '"><b>' . __( 'Dismiss', 'it-l10n-backupbuddy' ) . '</b></a><div style="margin-right: 60px;">' . $message . '</div>';
			$this->alert( $message, $error, '', $unique_id );
		} else {
			echo '<!-- Previously Dismissed Alert: `' . htmlentities( $message ) . '` -->';
		}
		
		return;
		
	} // End alert().
	
	
	
	/**
	 *	pb_backupbuddy::video()
	 *
	 *	Displays a YouTube video to the user when they hover over the question video mark.
	 *	HTML is supposed within tooltips.
	 *
	 *	@param		string		$video_key		YouTube video key from the URL ?v=VIDEO_KEY_HERE  -- To jump to a certain timestamp add #SECONDS to the end of the key, where SECONDS is the number of seconds into the video to start at. Example to start 65 seconds into a video: 9ZHWGjBr84s#65. This must be in seconds format.
	 *	@param		string		$title			Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
	 *	@param		boolean		$echo_tip		Whether to echo the tip (default; true), or return the tip (false). (optional)
	 *	@return		string/null					If not echoing tip then the string will be returned. When echoing there is no return.
	 */
	public function video( $video_key, $title = '', $echo_tip = true ) {
		
		self::enqueue_thickbox();
		
		if ( strstr( $video_key, '#' ) ) {
			$video = explode( '#', $video_key );
			$video[1] = '&start=' . $video[1];
		} else {
			$video[0] = $video_key;
			$video[1] = '';
		}
		
		$tip = '<a target="_blank" href="http://www.youtube.com/embed/' . urlencode( $video[0] ) . '?autoplay=1' . $video[1] . '&TB_iframe=1&width=600&height=400" class="thickbox pluginbuddy_tip" title="Video Tutorial - ' . $title . '"><img src="' . pb_backupbuddy::plugin_url() . '/pluginbuddy/images/pluginbuddy_play.png" alt="(video)" /></a>';
		if ( $echo_tip === true ) {
			echo $tip;
		} else {
			return $tip;
		}
	} // End video().
	
	
	
	/**
	 * pb_backupbuddy::enqueue_thickbox()
	 *
	 * Enqueues the required scripts / styles needed to use thickbox
	 *
	 * @return null
	 */
	public function enqueue_thickbox() {

		if ( !defined( 'PB_IMPORTBUDDY' ) ) {
			global $wp_scripts;
			if ( is_object( $wp_scripts ) ) {
				if ( !in_array( 'thickbox', $wp_scripts->done ) ) {
					wp_enqueue_script( 'thickbox' );
					wp_print_scripts( 'thickbox' );
					wp_print_styles( 'thickbox' );
				}
			}
		}
	} // End enqueue_thickbox().
	
	
	
	/*	start_tabs()
	 *	
	 *	Starts a tabbed interface.
 	 *	@see end_tabs().
	 *	
	 *	@param		string		$interface_tag		Tag/slug for this entire tabbed interface. Should be unique.
	 *	@param		array		$tabs				Array containing an array of settings for this tabbed interface. Ex:  array( array( 'title'> 'my title', 'slug' => 'mytabs' ) );
	 *												Optional setting with key `ajax_url` may define a URL for AJAX loading.
	 *	@param		string		$css				Additional CSS to apply to main outer div. (optional)
	 *	@param		boolean		$echo				Echo output instead of returning. (optional)
	 *	@param		int			$active_tab_index	Tab to start as active/selected.
	 *	@return		null/string						null if $echo = false, all data otherwise.
	 */
	public function start_tabs( $interface_tag, $tabs, $css = '', $echo = true, $active_tab_index = 0 ) {
		$this->_tab_interface_tag = $interface_tag;
		
		pb_backupbuddy::load_script( 'pb_tabs.js', true );
		
		$prefix = 'pb_' . pb_backupbuddy::settings( 'slug' ) . '_'; // pb_PLUGINSLUG_
		$return = '';
		
		/*
		$return .= '<script type="text/javascript">';
		$return .= '	jQuery(document).ready(function() {';
		$return .= '		jQuery("#' . $prefix . $this->_tab_interface_tag . '_tabs").tabs({ active: ' . $active_tab_index . ' });';
		$return .= '	});';
		$return .= '</script>';
		*/
		
		$return .= '<div class="backupbuddy-tabs-wrap">';
		
		
		$return .= '<h2 class="nav-tab-wrapper">';
		$i = 0;
		foreach( $tabs as $tab ) {
			if ( ! isset( $tab['css'] ) ) {
				$tab['css'] = '';
			}
			$active_tab_class = '';
			if ( $active_tab_index == $i ) {
				$active_tab_class = 'nav-tab-active';
			}
			if ( isset( $tab['ajax'] ) && ( $tab['ajax_url'] != '' ) ) { // AJAX tab.
				$return .= '<a class="nav-tab nav-tab-' . $i . ' ' . $active_tab_class . ' bb-tab-' . $tab['slug'] . '" href="javascript:void(0)" data-ajax="' . $tab['ajax_url'] . '">' . $tab['title'] . '</a>';
			} elseif ( isset( $tab['url'] ) && ( $tab['url'] != '' ) ) {
				$return .= '<a class="nav-tab nav-tab-' . $i . ' ' . $active_tab_class . ' bb-tab-' . $tab['slug'] . '" href="' . $tab['url'] . '">' . $tab['title'] . '</a>';
			} else { // Standard; NO AJAX.
				$return .= '<a class="nav-tab nav-tab-' . $i . ' ' . $active_tab_class . ' bb-tab-' . $tab['slug'] . '" style="' . $tab['css'] . '" href="#' . $prefix . $this->_tab_interface_tag . '_tab_' . $tab['slug'] . '">' . $tab['title'] . '</a>';
			}
			$i++;
		}
		$return .= '</h2>';
		
		
		/*
		$return .= '<div id="' . $prefix . $this->_tab_interface_tag . '_tabs" style="' . $css . '">';
		$return .= '<ul>';
		foreach( $tabs as $tab ) {
			if ( ! isset( $tab['css'] ) ) {
				$tab['css'] = '';
			}
			if ( isset( $tab['ajax'] ) && ( $tab['ajax_url'] != '' ) ) { // AJAX tab.
				$return .= '<li><a href="' . $tab['ajax_url'] . '"><span>' . $tab['title'] . '</span></a></li>';
			} else { // Standard; NO AJAX.
				$return .= '<li style="' . $tab['css'] . '"><a href="#' . $prefix . $this->_tab_interface_tag . '_tab_' . $tab['slug'] . '"><span>' . $tab['title'] . '</span></a></li>';
			}
		}
		$return .= '</ul>';
		$return .= '<br>';
		$return .= '<div class="tabs-borderwrap">';
		*/
		
		$return .= '<div class="backupbuddy-tab-blocks">';
		
		if ( $echo === true ) {
			echo $return;
		} else {
			return $return;
		}
		
	} // End start_tabs().
	
	
	
	/*	end_tabs()
	 *	
	 *	Closes off a tabbed interface.
	 *	@see start_tabs().
	 *	
	 *	@param		boolean		$echo				Echo output instead of returning.  (optional)
	 *	@return		null/string						null if $echo = false, all data otherwise.
	 */
	public function end_tabs( $echo = true ) {
		
		/*
		$return = '';
		$return .= '	</div>';
		$return .= '</div>';
		*/
		$return = '</div></div>';
		
		$this->_tab_interface_tag = '';
		
		if ( $echo === true ) {
			echo $return;
		} else {
			return $return;
		}
		
	} // End end_tabs().
	
	
	
	/*	start_tab()
	 *	
	 *	Opens the start of an individual page to be loaded by a tab.
	 *	@see end_tab().
	 *	
	 *	@param		string		$tab_tag			Unique tag for this tab section. Must match the tag defined when creating the tab interface.
	 *	@param		boolean		$echo				Echo output instead of returning.  (optional)
	 *	@return		null/string						null if $echo = false, all data otherwise.
	 */
	public function start_tab( $tab_tag, $echo = true ) {
		
		$prefix = 'pb_' . pb_backupbuddy::settings( 'slug' ) . '_'; // pb_PLUGINSLUG_
		$return = '';
		
		$return .= '<div class="backupbuddy-tab" id="' . $prefix . $this->_tab_interface_tag . '_tab_' . $tab_tag . '">';
		
		
		if ( $echo === true ) {
			echo $return;
		} else {
			return $return;
		}
		
	} // End start_tab().
	
	
	
	/*	end_tab()
	 *	
	 *	Closes this tab section.
	 *	@see start_tab().
	 *	
	 *	@param		string		$tab_tag			Unique tag for this tab section. Must match the tag defined when creating the tab interface.
	 *	@param		boolean		$echo				Echo output instead of returning.  (optional)
	 *	@return		null/string						null if $echo = false, all data otherwise.
	 */
	public function end_tab( $echo = true ) {
		
		$return = '</div>';
		
		
		if ( $echo === true ) {
			echo $return;
		} else {
			return $return;
		}
		
	} // End end_tab().
	
	
	
	/*	ajax_header()
	 *	
	 *	Output HTML headers when using AJAX.
	 *	
	 *	@param		boolean		$js			Whether or not to load javascript. Default false.
	 *	@param		bool		$padding	Whether or not to padd wrapper div. Default has padding.
	 *	@return		
	 */
	function ajax_header( $js = true, $padding = true ) {
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		echo '<title>PluginBuddy</title>';
		
		wp_print_styles( 'wp-admin' );
		wp_print_styles( 'dashicons' );
		wp_print_styles( 'buttons' );
		wp_print_styles( 'colors' );
		
		if ( $js === true ) {
			wp_enqueue_script( 'jquery' );
			wp_print_scripts( 'jquery' );
		}
		
		pb_backupbuddy::load_style( 'wp-admin.css' );
		pb_backupbuddy::load_style( 'thickboxed.css' );
		
		//echo '<link rel="stylesheet" href="' . pb_backupbuddy::plugin_url(); . '/css/admin.css" type="text/css" media="all" />';
		pb_backupbuddy::load_script( 'admin.js', true );
		pb_backupbuddy::load_style( 'admin.css', true );
		pb_backupbuddy::load_script( 'jquery-ui-tooltip', false );
		pb_backupbuddy::load_style( 'jQuery-ui-1.11.2.css', true );
		
		echo '<body class="wp-core-ui" style="background: inherit;">';
		if ( $padding === true ) {
			echo '<div style="padding: 12px; padding-left: 20px; padding-right: 20px; overflow: scroll;">';
		} else {
			echo '<div>';
		}
	} // End ajax_header().
	
	
	function ajax_footer() {
		echo '</body>';
		echo '</div>';
		echo '</head>';
		echo '</html>';
	} // End ajax_footer().
	
	
	
} // End class pluginbuddy_ui.


?>