<?php




/*	class pb_backupbuddy_actions
 *	
 *	Handles actions. Currently just reports if actions were registered and called but the callback was missing.
 *	
 *	@return		null
 */
class pb_backupbuddy_actionscore {
	
	
	/*	pluginbuddy_actionscore->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		echo '{Missing actions method `' . $name . '`.}';
	} // End __call().
	
	
	
} // End class pb_backupbuddy_actions.



/*	class pb_backupbuddy_ajaxcore
 *	
 *	Handles ajax. Currently just reports if ajax was registered and called but the callback was missing.
 *	
 *	@return		null
 */
class pb_backupbuddy_ajaxcore {
	
	
	/*	pluginbuddy_shortcodes->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		die( '{Missing ajax method `' . $name . '`.}' );
	} // End __call().
	
	
} // End class pb_backupbuddy_ajax.



/*	class backupbuddy_croncore
 *	
 *	Handles crons. Currently just reports if crons were registered and called but the callback was missing.
 *	
 *	@return		null
 */
class pb_backupbuddy_croncore {
	
	
	/*	pluginbuddy_shortcodes->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		die( '{Missing cron method `' . $name . '`.}' );
	} // End __call().
	
	
} // End class backupbuddy_cron.



/*	class pb_backupbuddy_dashboardcore
 *	
 *	Handles dashboard widgets (on main admin screen). Reports if admin dashboard widgets were registered and called but the callback was missing.
 *	Also handles the actual registering of the widgets.
 *	
 *	@return		null
 */
class pb_backupbuddy_dashboardcore {
	
	
	/*	pluginbuddy_shortcodes->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		die( '{Missing dashboard method `' . $name . '`.}' );
	} // End __call().
	
	
	/*	pluginbuddy_dashboard->register_widgets()
	 *	
	 *	Called back by WordPress to actually register the dashboard widget in the admin.
	 *	
	 *	@return		null
	 */
	function register_widgets() {
		//wp_add_dashboard_widget( 'pb_' . self::settings( 'slug' ) . '_' . $tag, $title,  array( &self::$_dashboard, $tag ) );
		foreach ( pb_backupbuddy::$_dashboard_widgets as $widget ) {
			
			if ( $widget['capability'] == 'godmode' ) { // godmode capabiltiy.
				if ( is_multisite() && backupbuddy_core::is_network_activated() ) { // In a network installation.
					if ( !current_user_can( 'manage_network' ) ) {
						continue; // Skip this widget. Access denied to it.
					}
				} else { // Standalone
					if ( !current_user_can( 'activate_plugins' ) ) {
						continue; // Skip this widget. Access denied to it.
					}
				}
			} else { // WP capability.
				if ( !current_user_can( $widget['capability'] ) ) {
					continue; // Skip this widget. Access denied to it.
				}
			}
			
			$widget_slug = 'pb_' . pb_backupbuddy::settings( 'slug' ) . '_' . $widget['tag'];
			wp_add_dashboard_widget( $widget_slug, $widget['title'],  array( &$this, $widget['tag'] ) );

			// If force top is enabled then we will attempt to force the widget to the top if possible.
			if ( isset( $widget['force_top'] ) && ( $widget['force_top'] === true ) ) {
				// Note: Only works if users have never re-arranged their dashboard widgets.
				global $wp_meta_boxes;
				$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
				$widget_backup = array( $widget_slug => $normal_dashboard[$widget_slug] ); // Save copy of our widget.
				unset( $normal_dashboard[$widget_slug] ); // Delete our widget.
				$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $widget_backup, $normal_dashboard ); // Merge our widget into the top.
			}
		}
	} // End register_widgets().
	
	
} // End class pb_backupbuddy_dashboard.



/*	class pb_backupbuddy_filterscore
 *	
 *	Handles filters. Currently just reports if filters were registered and called but the callback was missing.
 *	
 *	@return		null
 */
class pb_backupbuddy_filterscore {
	
	
	/*	pluginbuddy_shortcodes->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		return '{Missing filters method `' . $name . '`.}';
	} // End __call().
	
	
} // End class pb_backupbuddy_filters.



/*	class pb_backupbuddy_pagescore
 *	
 *	Handles admin pages. Reports if pages were registered and called but the callback was missing.
 *	Also provides load_controller() function for pages to call to load a controller while in the controller.
 *	
 *	@return		null
 */
class pb_backupbuddy_pagescore {
	
	
	/*	pluginbuddy_pages->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	Attempts to load a controller page matching the method name if possible.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		$page_file = pb_backupbuddy::plugin_path() . '/controllers/pages/' . $name . '.php';
		if ( $name == 'getting_started' ) {
			$page_file = pb_backupbuddy::plugin_path() . '/pluginbuddy/_' . $name . '.php';
		}
		
		if ( file_exists( $page_file ) ) { // Load from /controllers/pages/PAGE.php if it exists.
			
			// Display page.
			pb_backupbuddy::load_script( 'admin.js', true );
			pb_backupbuddy::load_style( 'admin.css', true );
			pb_backupbuddy::load_script( 'jquery-ui-tooltip', false );
			pb_backupbuddy::load_style( 'jQuery-ui-1.11.2.css', true );
			echo '<div class="wrap">';
 			require_once( $page_file );
			echo '</div>';
			
			//echo '<div id="footer-thankyou" style="float: right; color: #777; margin-right: 21px; margin-top: 20px; margin-bottom: -34px;">Running BackupBuddy v' . pb_backupbuddy::settings( 'version' ) . '.</div>';
			
		} else { // Not found
			echo '{Missing pages method `' . $name . '`.}';
		}
	} // End __call().
	
	
	/*	pluginbuddy_pages->load_controller()
	 *	
	 *	Load a controller from within a page (which is loaded by a controller itself).
	 *	
	 *	@param		string		$page		Name of the page. Loads page from /controllers/pages/NAME.php.
	 *	@return		
	 */
	public function load_controller( $page ) {
		if ( file_exists( pb_backupbuddy::plugin_path() . '/controllers/pages/' . $page . '.php' ) ) {
			require_once( pb_backupbuddy::plugin_path() . '/controllers/pages/' . $page . '.php' );
		} else {
			echo '{Error: Unable to load page controller `' . $page . '`; file not found.}';
		}
	}
	
	
	
} // End class pb_backupbuddy_pages.



/*	class pb_backupbuddy_shortcodescore
 *	
 *	Handles shortcodes. Currently just reports if shortcodes were registered and called but the callback was missing.
 *	
 *	@return		null
 */
class pb_backupbuddy_shortcodescore {
	
	
	/*	pluginbuddy_shortcodes->__call()
	 *	
	 *	Magic method if a method is called that does not exist.
	 *	
	 *	@param		string		$name			Function name.
	 *	@param		array		$arguments		Array of arguments passed to function.
	 *	@return		null
	 */
	function __call( $name, $arguments ) {
		return '{Missing shortcodes method `' . $name . '`.}';
	} // End __call().
	
	
} // End class pb_backupbuddy_shortcodes.


?>
