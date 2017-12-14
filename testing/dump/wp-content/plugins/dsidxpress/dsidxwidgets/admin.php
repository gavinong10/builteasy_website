<?php

add_action("admin_init", array("dsIDXWidgets_Admin", "ShortCircuit"));
add_action("admin_init", array("dsIDXWidgets_Admin", "Initialize"));
add_action("admin_menu", array("dsIDXWidgets_Admin", "AddMenu"));
add_filter('nav_menu_items_page', array('dsIDXWidgets_Admin', 'NavMenus'));

class dsIDXWidgets_Admin {
	static $HeaderLoaded = null;
	static function AddMenu() {
		$options = get_option(DSIDXWIDGETS_OPTION_NAME);
		dsIDXWidgets_Admin::GenerateAdminMenus(DSIDXWIDGETS_PLUGIN_URL . 'images/idxwidgets_LOGOicon.png');
	}
	static function GenerateAdminMenus($icon_url){
        if (!current_user_can("manage_options"))
			return;
		
		$idxpress_options = get_option(DSIDXPRESS_OPTION_NAME);

		// hide the menu if it's a zpress site and it has the IDs filled in
		if (!defined('ZPRESS_API') && empty($idxpress_options['AccountID']) && empty($idxpress_options['SearchSetupID'])) {
			add_menu_page('IDX Widgets', 'IDX Widgets', "manage_options", "dsidxwidgets-options", "", $icon_url);

			$optionsPage = add_submenu_page("dsidxwidgets", "IDX Widgets Options", "Options", "manage_options", "dsidxwidgets-options", array("dsIDXWidgets_Admin", "EditOptions"));
			add_action("admin_print_scripts-{$optionsPage}", array("dsIDXWidgets_Admin", "LoadHeader"));
		}
	}
    static function Activation() {
    }
	static function Initialize() {
		register_setting("dsidxwidgets_options", DSIDXWIDGETS_OPTION_NAME, array("dsIDXWidgets_Admin", "SanitizeOptions"));
	}
	static function LoadHeader() {
		if (self::$HeaderLoaded)
			return;

		$pluginUrl = DSIDXWIDGETS_PLUGIN_URL;
		echo <<<HTML
			<link rel="stylesheet" href="{$pluginUrl}css/admin-options.css" type="text/css" />
HTML;
		self::$HeaderLoaded = true;
	}
	static function DismissNotification() {
		$action = $_POST["action"];
		check_ajax_referer($action);

		$options = get_option(DSIDXWIDGETS_OPTION_NAME);
		$options["HideIntroNotification"] = true;
		update_option(DSIDXWIDGETS_OPTION_NAME, $options);
		die();
	}
	
	static function EditOptions() {
		$options = get_option(DSIDXWIDGETS_OPTION_NAME);
?>

	<div class="wrap metabox-holder">
		<?php screen_icon(); ?>
		<h2>IDX Widgets Options</h2>

		<form method="post" action="options.php">
			<?php settings_fields("dsidxwidgets_options"); ?>

			<h4>Account Information</h4>
			<span class="description">This information is used in identifying you to the website visitor. For example: Listing PDF Printouts, Contact Forms, and Dwellicious</span>
			<table class="form-table">
				<tr>
					<th>
						<label for="dsidxwidgets-AccountID">Account ID:</label>
					</th>
					<td>
						<input type="text" id="dsidxwidgets-AccountID" name="<?php echo DSIDXWIDGETS_OPTION_NAME; ?>[AccountID]" value="<?php echo $options["AccountID"]; ?>" /><br />
						<span class="description"></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="dsidxwidgets-SearchSetupID">Search Setup ID:</label>
					</th>
					<td>
						<input type="text" id="dsidxwidgets-SearchSetupID" maxlength="4" name="<?php echo DSIDXWIDGETS_OPTION_NAME; ?>[SearchSetupID]" value="<?php echo $options["SearchSetupID"]; ?>" /><br />
						<span class="description"></span>
					</td>
				</tr>
			</table>
            <p class="submit">
				<input type="submit" class="button-primary" name="Submit" value="Save Options" />
			</p>
		</form>
	</div><?php

	}
	
    static function SanitizeOptions($options) {
		return $options;
	}

	public static function NavMenus($posts) {
		$options = get_option(DSIDXWIDGETS_OPTION_NAME);
		
		// offset the time to ensure we have a unique post id
		$post_id = time() + sizeof($posts);
		
		if (isset($options['AgentID']) && $options['AgentID'] != '') {
			$posts[] = (object) array(
				'ID'           => $post_id,
				'object_id'    => $post_id,
				'post_content' => '',
				'post_excerpt' => '',
				'post_parent'  => 0,
				'post_title'   => 'My Listings',
				'post_type'    => 'nav_menu_item',
				'type'         => 'custom',
				'url'          => get_home_url().'/idx/?'.urlencode('idx-q-ListingAgentID<0>='.$options['AgentID']),
				'zpress_page'  => true
			);
			$post_id++;
		}
		
		if (isset($options['OfficeID']) && $options['OfficeID'] != '') {
			$posts[] = (object) array(
				'ID'           => $post_id,
				'object_id'    => $post_id,
				'post_content' => '',
				'post_excerpt' => '',
				'post_parent'  => 0,
				'post_title'   => 'My Office Listings',
				'post_type'    => 'nav_menu_item',
				'type'         => 'custom',
				'url'          => get_home_url().'/idx/?'.urlencode('idx-q-ListingOfficeID<0>='.$options['OfficeID']),
				'zpress_page'  => true
			);
		}
		
		return $posts;
	}

	public static function Sort($a, $b) {
		if (isset($a->post_title) && isset($b->post_title)) {
			if ($a->post_title == 'Home') {
				return -1;
			}
				
			if ($b->post_title == 'Home') {
				return 1;
			}
				
			return strcmp($a->post_title, $b->post_title);
		} else {
			return 0;
		}
	}
	
	public static function ShortCircuit() {
		if (isset($_GET['zpress_widget_ajax']) && $_GET['zpress_widget_ajax'] == 'true' && isset($_GET['api_target'])) {
			if (defined('DS_API')) {
				dsWidgetAgent_ApiRequest::$ApiEndPoint = DS_API;
			}
			$response = dsWidgetAgent_ApiRequest::FetchData($_GET['api_target'], array(), true, null, null, array('Origin' => $_SERVER['SERVER_NAME']));
			if (isset($response['body'])) {
				echo($response['body']);
			} else {
				echo('console.log("Error occurred while retrieving API data.");');
			}
			die();
		}
	}
}
?>
