<?php

if (! defined('DAY_IN_SECONDS')) {
	define('DAY_IN_SECONDS', 86400);
}

if (! defined('EGA_ENABLE_CACHE')) {
	define('EGA_ENABLE_CACHE', TRUE);
}

if (! class_exists('EG_Plugin_135')) {

	/**
	 * Class EG_Plugin_135
	 *
	 *
	 *
	 * @package EG-Plugin
	 */
	Class EG_Plugin_135 {

		var $name				= '';
		var $version			= '';
		var $textdomain			= '';
		var $core_file			= '';
		var $options_entry		= '';
		var $options			= FALSE;
		var $default_options	= FALSE;
		var $option_form		= FALSE;
		var $path				= '';
		var $url				= '';
		var $stylesheet			= '';

		var $options_page_id	= FALSE;
		var $options_page_title	= '';
		var $options_page_access= '';
		var $options_page_file	= '';
		var $tinyMCE_buttons	= array();

		var $plugin_pointers	= FALSE;
		var $pointers_caps		= FALSE;
		var $changed_options	= FALSE;

		var $adminbar_menu		= array();

		/**
		 * __construct (class constructor)
		 *
		 * Long Description
		 *
		 * @package EG-Plugin
		 * @since 1.0
		 * @param none
		 * @return none
		 *
		 */
		function __construct($name, $version, $option_entry='', $textdomain='', $core_file=__FILE__, $default_options=FALSE) {

			$this->name				= $name;
			$this->version			= $version;
			$this->textdomain		= $textdomain;
			$this->core_file		= $core_file;
			$this->options_entry	= $option_entry;
			$this->default_options	= $default_options;

			$this->path				= plugin_dir_path($core_file);
			$this->url				= plugin_dir_url($core_file);

		} // End of __construct

		/**
		 * plugins_loaded
		 *
		 * Call by plugins_loaded WP action
		 *
		 * @package EG-Plugin
		 * @since 1.0
		 * @param none
		 * @return none
		 * @todo
		 *		- check that options can be loaded in this function or a specific plugin function
		 */
		function plugins_loaded() {

			/* --- Get Plugin options --- */
			if (FALSE === $this->options) {
				$this->options = get_option($this->options_entry);
			}
			// $this->install_upgrade();

			load_plugin_textdomain( $this->textdomain,
									false,
									dirname( plugin_basename( $this->core_file) ) . '/lang/'
								);
		} // End of plugins_loaded

		/**
		 * admin_menu
		 *
		 *
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return
		 *
		 */
		function admin_menu() {

			if ($this->options_page_id != '') {
				$page_hook = add_options_page(
							__($this->options_page_title, $this->textdomain),
							__($this->name, $this->textdomain),
							$this->options_page_access,
							$this->options_page_id,
							array(&$this, 'options_page')
						);
				add_action( 'load-' . $page_hook, array(&$this, 'load_options_page' ));
			}
		} // End of admin_menu

		function init() {

			$this->install_upgrade();

		} // End of init
		
		function admin_init() {
			if ($this->options_page_id) {
				register_setting($this->options_page_id.'-group', $this->options_entry, array(&$this, 'options_validation'));
			}

			if ( 0 < sizeof($this->tinyMCE_buttons) && get_user_option('rich_editing') == 'true') {
			//	current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ))

				add_filter( 'mce_buttons', 			array( &$this, 'tinymce_button' ) );
				add_filter( 'mce_external_plugins', array( &$this, 'tinymce_plugin' ) );
				
				// EGE - 2.0.2 - Comment 
				// add_filter( 'tiny_mce_version', 	array( &$this, 'tinymce_version') );
			}

		} // End of admin_init

		/**
		 * load_options_page
		 *
		 * Load EG-Forms Class
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param	array	input	list of fields of the option form
		 * @return	string			all updated options
		 *
		 */
		function load_options_page() {
			if (!class_exists('EG_Form_224')) {
				require($this->path.'lib/eg-forms.inc.php');
			}
		} // End of load_options_page

		/**
		 * options_validation
		 *
		 * Validate outputs
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param	array	input	list of fields of the option form
		 * @return	string			all updated options
		 *
		 */
		function options_validation($inputs) {

			$this->changed_options = array();
			$all_options = get_option($this->options_entry);
			foreach ($inputs as $key => $value) {
				// If field exist in plugin options, then
				if ( isset($all_options[$key])) {
					// If the value changed, we track it
					if ( $value != $all_options[$key] ) {
						$this->changed_options[$key] = $all_options[$key];
					}
					if (is_array($value)) {
						$all_options[$key] = array_filter($value);
					}
					else {
						if (is_float($value)) $all_options[$key] = floatval($value);
						elseif (is_int($value)) $all_options[$key] = intval($value);
						else $all_options[$key] = trim(stripslashes($value));
					} // End of ! is_array
				} // End of "input exist in options"
			} // End of foreach

			$this->options = $all_options;
			if (0 == sizeof($this->changed_options))
				$this->changed_options = FALSE;
			return ($all_options);
		} // End of options_validation

		/**
		 * options_page
		 *
		 *
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return
		 *
		 */
		function options_page() {

			$option_form = new EG_Form_224($this->options_page_id, $this->options_page_id.'-group', $this->options_page_title, $this->options_entry, $this->textdomain, '', '', array(&$this, 'display_sidebar'));
			require($this->path.'inc/'.$this->options_page_file);
			$option_form->display($this->options);
		} // End of options_page

 		/**
		 * settings_links
		 *
		 * add the "settings link" to the plugin entry
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	$links	array
		 *
		 */
		function settings_links( $links ) {
			if ($this->options_page_id != '') {
				$new_link = sprintf( '<a href="'.admin_url('options-general.php').'?page=%s">%s</a>',
											$this->options_page_id, __('Settings' /* , $this->textdomain */) );
				array_unshift( $links, $new_link );
			}
			return ($links);
		} // End of settings_links

		/**
		 * get_stylesheet_url
		 *
		 * Get the url of the plugin stylesheet
		 *
		 * @package EG-Plugin
		 * @since 1.0
		 * @param none
		 * @return none
		 *
		 */
		function get_stylesheet_url($stylesheet='') {

			$style_url = '';
			if ($stylesheet != '' ) {
				if (@file_exists(TEMPLATEPATH.'/'.$this->stylesheet)) {
					$style_url = get_stylesheet_directory_uri().'/'.$stylesheet;
				}
				else {
					if (@file_exists($this->path.$stylesheet)) {
						$style_url = $this->url.$stylesheet;
					}
				}
			} // End of stylesheet != ''
			return ($style_url);
		} // End of get_stylesheet_url

		/**
		 * enqueue_styles
		 *
		 * Prepare the load of stylesheets
		 *
		 * @package EG-Plugin
		 * @since 1.0
		 * @param none
		 * @return none
		 *
		 */
		function enqueue_styles () {

			/* ---- Enqueue the plugin stylesheet ---- */
			if ($this->stylesheet!='' && (!isset($this->options['load_css']) || $this->options['load_css'])) {
				wp_register_style( $this->name, $this->get_stylesheet_url($this->stylesheet), array(), $this->version );
				wp_enqueue_style( $this->name );
			}
		} // End of enqueue_styles

		/**
		 * add_menu_to_admin_bar
		 *
		 *
		 *
		 * @package EG-Plugin
		 * @since 1.0
		 * @param none
		 * @return none
		 *
		 */
		function add_menu_to_admin_bar($wp_admin_bar) {

			if (is_admin_bar_showing() && isset($this->options['display_admin_bar']) && $this->options['display_admin_bar']) {

				if ('' != $this->options_page_id) {
					$this->adminbar_menu[] = array(
						'menu' => array(
							'id'		=> sanitize_title($this->name).'-settings-menubar',
							'title'		=> __($this->name.' Settings', $this->textdomain),
							'href' 		=> admin_url('options-general.php?page='.$this->options_page_id)
						),
						'cap' => 'manage_options'
					);
				} // End of options_page_id

				// Filter menu according user capability
				if (sizeof($this->adminbar_menu) > 0) {
					foreach ($this->adminbar_menu as $key => $menu) {
						if (! current_user_can($menu['cap'])) {
							unset($this->adminbar_menu[$key]);
						}
					} // End of foreach
				} // End of sizeof > 0

				if (0 < sizeof($this->adminbar_menu)) {

					$wp_admin_bar->add_menu( array(
						'id' 		 => 'egplugins',
						'title' 	 => __('EG-Plugins'))
					);
					$parent_menu = 'egplugins';
					if (1 < sizeof($this->adminbar_menu)) {
						$parent_menu = sanitize_title($this->name).'-menubar';
						$wp_admin_bar->add_menu( array(
							'parent'	=> 'egplugins',
							'id' 	 	=> $parent_menu,
							'title'  	=> __($this->name, $this->textdomain)
							)
						);
					} // End of sizeof($this->adminbar_menu)>1

					foreach ($this->adminbar_menu as $key => $menu) {
						$menu['menu']['parent'] = $parent_menu;
						$wp_admin_bar->add_menu( $menu['menu'] );
					} // End of foreach

				} // End of sizeof(adminbar_menu)>0
			} // End of  options_page exists and bar displayed
			return (FALSE);
		} // End of add_menu_to_admin_bar

		/**
		 * add_stylesheet
		 *
		 * Init stylesheet
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return	none
		 *
		 */
		function add_stylesheet($stylesheet) {
			$this->stylesheet = $stylesheet;
		} // End of add_stylesheet

		/**
		 * add_options_page
		 *
		 * Set the options page parameters
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return	none
		 *
		 */
		function add_options_page($id, $title, $file, $access_level='manage_options') {
			$this->options_page_id 		= $id;
			$this->options_page_group 	= $id.'_group';
			$this->options_page_title 	= $title;
			$this->options_page_file 	= $file;
			$this->options_page_access 	= $access_level;
		} // End of add_options_page

		/**
		 * add_tinymce_button
		 *
		 * Set the options page parameters
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return	none
		 *
		 */
		function add_tinymce_button($button_name, $js_file_name='editor_plugin.js') {
			$this->tinyMCE_buttons[$button_name] = $js_file_name;
		} // End of add_tinymce_button

		function tinymce_button( $buttons ) {

			array_push($buttons, '|');
			foreach ($this->tinyMCE_buttons as $name => $js_file) {
				array_push($buttons, $name);
			}
			return $buttons;
		} // End of tinymce_button

		function tinymce_plugin( $plugins ) {
			foreach ($this->tinyMCE_buttons as $name => $js_file) {
				$plugins[$name] = trailingslashit($this->url).$js_file;
			}
			return $plugins;
		} // End of tinymce_plugin

		function tinymce_version($version) {
		  $version += 3;
		  return $version;
		} // End of tinymce_version

		/**
		 * install_upgrade
		 *
		 * Create or upgrade plugin options
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return	previous options table
		 *
		 */
		function install_upgrade() {

			$new_options      = FALSE;
			$previous_options = FALSE;
//eg_plugin_error_log('Install upgrade', 'Starting');

			// Read options
			if (FALSE === $this->options)
				$this->options = get_option($this->options_entry);

			// Options empty => First installation
			if (FALSE === $this->options) {
//eg_plugin_error_log('Install upgrade', 'Fresh install');
				$new_options = $this->default_options;
			} // End of options empty (first install)
			else {
				$previous_options = $this->options;
//eg_plugin_error_log('Install upgrade', 'Old and new version', $this->options['version'].'-'.$this->version);
				if (version_compare($this->options['version'], $this->version, '<')) {
//eg_plugin_error_log('Install upgrade', 'Upgrade');
					// Plugin already installed previously => upgrade
					$new_options = array();
					foreach ($this->default_options as $key => $value) {
						if (isset($this->options[$key])) $new_options[$key] = $this->options[$key];
						else $new_options[$key] = $value;
					} // End foreach
				} // End of options not empty (update)
			}

			if (FALSE !== $new_options) {
// eg_plugin_error_log('Install upgrade', 'New options', $new_options);
				$new_options['version'] = $this->version;
				update_option($this->options_entry, $new_options);
				$this->options = $new_options;
			} // End of options_updated

			return ($previous_options);
		} // End of install_upgrade

		/**
		 * load
		 *
		 * Start the plugin
		 *
		 * @package EG-Plugin
		 * @since 	1.0
		 * @param 	none
		 *
		 * @return	none
		 *
		 */
		function load() {
			add_action('plugins_loaded', 			array(&$this, 'plugins_loaded') );
			add_action( 'admin_bar_menu', 			array (&$this, 'add_menu_to_admin_bar'), 99 );
			if (is_admin()) {
				add_action( 'admin_menu',   		array( &$this, 'admin_menu') );
				add_action( 'admin_init',   		array( &$this, 'admin_init') );
				add_action( 'init',   				array( &$this, 'init') 		 );
				add_action('admin_enqueue_scripts', array(&$this, 'pointers_enqueue_scripts'));
				add_filter( 'plugin_action_links_' . plugin_basename( $this->core_file) , array( &$this, 'settings_links') );
			}
			else {
				add_action('wp_enqueue_scripts', 	array( &$this, 'enqueue_styles')  );
			}

			// add_filter( 'plugin_row_meta', array( &$this,'plugin_meta_links'), 10, 2 );
		} // End of load

		/**
		 * shortcode_is_visible
		 *
		 * Define is auto shortcode must be displayed of not.
		 *
		 * @return  int		1 if a shortcode is visble, 0 if not
		 */
		function shortcode_is_visible() {
			global $post;

			if (! is_array($this->options['shortcode_auto_where']))
				$list = array($this->options['shortcode_auto_where']);
			else
				$list = $this->options['shortcode_auto_where'];

			if (is_front_page() || is_home())	$current_page = 'home';
			elseif (is_singular()) 				$current_page = get_post_type();
			elseif (is_feed())					$current_page = 'feed';
			elseif (is_archive() || is_category() || is_tag() || is_date() || is_day() || is_month() || is_year()) $current_page = 'index';
			else $current_page='unknown';

			return ( in_array($current_page, $list) );
		} // End of shortcode_is_visible

		/**
		 * shortcode_auto_check_manual_shortcode
		 *
		 * Detect manual shortcode
		 *
		 * @return  TRUE auto-shortcode can be displayed, FALSE, auto shortcode is not displayed
		 */
		function shortcode_auto_check_manual_shortcode($shortcode_string) {
			global $post;

			$value = TRUE;
			if ( isset($post) && $this->options['shortcode_auto_exclusive'] > 0 ) {
				$value = (strpos($post->post_excerpt.' '.$post->post_content, '['.$shortcode_string.' ') === FALSE);
			}
// eg_plugin_error_log($this->name, 'shortcode_auto_check_manual_shortcode', $value);
			return ($value);
		} // End of shortcode_auto_check_manual_shortcode

		/**
		 * shortcode_title
		 *
		 * Add title to a specified string
		 *
		 * @param 	string		the string to modify
		 * @param 	string		$title
		 * @param 	string		$titletag
		 * @return 	string		string with added title
		 */
		function shortcode_title($string, $title, $titletag) {
		
			if ($title != '') {
				$string = ($titletag==''?'':'<'.$titletag.'>').
						esc_html($title).
						($titletag==''?'':'</'.$titletag.'>').
						$string;
			}
			return ($string);
		} // End of shortcode_title
		
		function display_plugin_links($args, $box) {
?>
			<ul>
				<li>
					<a href="http://wordpress.org/extend/plugins/<?php echo strtolower($this->name); ?>">
						<?php esc_html_e('Plugin\'s homepage', $this->textdomain)?>
					</a>
				</li>
				<li>
					<a href="http://wordpress.org/extend/plugins/<?php echo strtolower($this->name); ?>/faq">
						<?php esc_html_e('Frequently Asked Questions',$this->textdomain); ?>
					</a>
				</li>
				<li>
					<a href="http://wordpress.org/support/plugin/<?php echo strtolower($this->name); ?>">
						<?php esc_html_e('Support forum',$this->textdomain); ?>
					</a>
				</li>
				<li>
					<a href="http://wordpress.org/extend/plugins/<?php echo strtolower($this->name); ?>/changelog/">
						<?php esc_html_e('Last changes', $this->textdomain); ?>
					</a>
				</li>
			</ul>
<?php
		} // End of display plugin links

		function display_donate($args, $box) {
			global $locale;

			$long_lang  = $locale;
			$short_lang = substr($locale, strpos($locale, '_')+1);
?>
			<p>
			<?php esc_html_e('This plugin required and requires many hours of work. If you use the plugin, and like it, feel free to show your appreciation to the author.', $this->textdomain); ?>
			</p>
			<?php if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='localhost') { ?>
				<a href="#"><?php esc_html_e('PayPal - The safer, easier way to pay online!', $this->textdomain); ?></a>
			<?php } else { ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_donations" />
<input type="hidden" name="business" value="CPCKAJFRB5NNA" />
<input type="hidden" name="lc" value="FR" />
<input type="hidden" name="item_name" value="<?php echo $this->name; ?>" />
<input type="hidden" name="currency_code" value="EUR" />
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHosted" />
<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !" />
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
</form>
			<?php }
		} // End of display_donate

		function display_sidebar() {

			add_meta_box( 'eg-plugin-links',
						esc_html__('Links', $this->textdomain),
						array(&$this, 'display_plugin_links'),
						null,
						'side'
					);
			add_meta_box( 'eg-plugin-donate',
						esc_html__('Donate', $this->textdomain),
						array(&$this, 'display_donate'),
						null,
						'side'
					);
			$dummy_arg = FALSE;
			do_meta_boxes( null, 'side', $dummy_arg);
		}

		function add_pointers($pointers, $caps) {
			$this->plugin_pointers	= $pointers;
			$this->pointers_caps	= $caps;
		} // End of add_pointers

		function footer_pointers_scripts($pointer_id, $selector, $args) {
			if ( empty( $pointer_id ) || empty( $selector ) || empty( $args ) || empty( $args['content'] ) )
				return;

			?>
			<script type="text/javascript">
			//<![CDATA[
			(function($){
				var options = <?php echo json_encode( $args ); ?>, setup;

				if ( ! options )
					return;

				options = $.extend( options, {
					close: function() {
						$.post( ajaxurl, {
							pointer: '<?php echo $pointer_id; ?>',
							action: 'dismiss-wp-pointer'
						});
					}
				});

				setup = function() {
					$('<?php echo $selector; ?>').pointer( options ).pointer('open');
				};

				if ( options.position && options.position.defer_loading )
					$(window).bind( 'load.wp-pointers', setup );
				else
					$(document).ready( setup );

			})( jQuery );
			//]]>
			</script>
			<?php
		} // End of footer_pointers_scripts

		function pointers_enqueue_scripts($hook) {

			// Check if screen related pointer is registered
			if ( FALSE !== $this->plugin_pointers && isset($this->plugin_pointers[ $hook ] ) ) {

				$pointers = (array) $this->plugin_pointers[ $hook ];
				// Get dismissed pointers
				$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

				$got_pointers = false;
				foreach ( array_diff( $pointers, $dismissed ) as $pointer ) {
					if ( isset( $this->pointers_caps[ $pointer ] ) ) {
						foreach ( $this->pointers_caps[ $pointer ] as $cap ) {
							if ( current_user_can( $cap ) ) {
								add_action( 'admin_print_footer_scripts', array( &$this, 'pointer_' . $pointer ) );
								$got_pointers = true;
							}
						} // End of foreach cap
					} // End of isset pointer
				} // End of foreach

				if ( $got_pointers ) {
					// Add pointers script and style to queue
					wp_enqueue_style( 'wp-pointer' );
					wp_enqueue_script( 'wp-pointer' );
				}
			} // End of pointers available in the current page
		} // End of pointers_enqueue_scripts

	} // End of EG_Plugin_133

} // End of class_exists


if ( ! function_exists( 'eg_plugin_error_log' ) ) {

	/**
	 * eg_plugin_error_log
	 *
	 * write a message to WordPress error log
	 *
	 * @package EG-Plugin
	 * @since 	1.0
	 * @param 	$mixed	string
	 *
	 */
	function eg_plugin_error_log($plugin_name, $msg='', $mixed=null) {

		if (WP_DEBUG === true) {

			$debug_info = debug_backtrace(FALSE);
			$output = /*date('d-M-Y H:i:s').' - '.*/$plugin_name.'-'.(isset($debug_info[1]) ? $debug_info[1]['function'] : '').($msg!=''?' - '.$msg:'');
			if (! is_null($mixed)) {
				$output .= ' - ('.gettype($mixed). ') ';
				switch (gettype($mixed)) {
					case 'boolean':

						$output .= ($mixed === TRUE ? 'TRUE' : 'FALSE');
					break;

					case 'array':
					case 'object':
					case 'resource':
						$output .= var_export($mixed, TRUE);
					break;

					case 'NULL':
						$output .= 'NULL';
					break;

					default: $output .= $mixed;
				}
			}
			error_log($output);
		} // End of WP_DEBUG===True
	} // End of eg_plugin_error_log
}


if ( ! function_exists( 'wp_normalize_path' ) ) {

	/**
	 * Normalize a filesystem path.
	 *
	 * The function is available since WP 3.9, not before
	 *
	 * @param string $path Path to normalize.
	 * @return string Normalized path.
	 */
	function wp_normalize_path( $path ) {
		$path = str_replace( '\\', '/', $path );
		$path = preg_replace( '|/+|','/', $path );
		return $path;
	}
}