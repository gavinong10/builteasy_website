<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */
 
if( !defined( 'ABSPATH') ) exit();

class RevSliderFront extends RevSliderBaseFront{
	
	/**
	 * 
	 * the constructor
	 */
	public function __construct(){
		
		parent::__construct($this);
		
		//set table names
		RevSliderGlobals::$table_sliders = self::$table_prefix.RevSliderGlobals::TABLE_SLIDERS_NAME;
		RevSliderGlobals::$table_slides = self::$table_prefix.RevSliderGlobals::TABLE_SLIDES_NAME;
		RevSliderGlobals::$table_static_slides = self::$table_prefix.RevSliderGlobals::TABLE_STATIC_SLIDES_NAME;
		RevSliderGlobals::$table_settings = self::$table_prefix.RevSliderGlobals::TABLE_SETTINGS_NAME;
		RevSliderGlobals::$table_css = self::$table_prefix.RevSliderGlobals::TABLE_CSS_NAME;
		RevSliderGlobals::$table_layer_anims = self::$table_prefix.RevSliderGlobals::TABLE_LAYER_ANIMS_NAME;
		RevSliderGlobals::$table_navigation = self::$table_prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME;
		
		add_filter('punchfonts_modify_url', array('RevSliderFront', 'modify_punch_url'));
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
	}
	
	
	/**
	 * 
	 * a must function. you can not use it, but the function must stay there!
	 */		
	public static function onAddScripts(){
		global $wp_version;
		
		$style_pre = '';
		$style_post = '';
		if($wp_version < 3.7){
			$style_pre = '<style type="text/css">';
			$style_post = '</style>';
		}
		
		$operations = new RevSliderOperations();
		$arrValues = $operations->getGeneralSettingsValues();
		
		$includesGlobally = RevSliderFunctions::getVal($arrValues, "includes_globally","on");
		$includesFooter = RevSliderFunctions::getVal($arrValues, "js_to_footer","off");
		$strPutIn = RevSliderFunctions::getVal($arrValues, "pages_for_includes");
		$isPutIn = RevSliderOutput::isPutIn($strPutIn,true);
		
		//put the includes only on pages with active widget or shortcode
		// if the put in match, then include them always (ignore this if)			
		if($isPutIn == false && $includesGlobally == "off"){
			$isWidgetActive = is_active_widget( false, false, "rev-slider-widget", true );
			$hasShortcode = RevSliderFunctionsWP::hasShortcode("rev_slider");
			
			if($isWidgetActive == false && $hasShortcode == false)
				return(false);
		}
		
		wp_enqueue_style('rs-plugin-settings', RS_PLUGIN_URL .'public/assets/css/settings.css', array(), RevSliderGlobals::SLIDER_REVISION);
		
		$custom_css = RevSliderOperations::getStaticCss();
		$custom_css = RevSliderCssParser::compress_css($custom_css);
		
		if(trim($custom_css) == '') $custom_css = '#rs-demo-id {}';
		
		wp_add_inline_style( 'rs-plugin-settings', $style_pre.$custom_css.$style_post );
		
		$setBase = (is_ssl()) ? "https://" : "http://";
		
		wp_enqueue_script(array('jquery'));
		
		//add icon sets
		//wp_register_style('rs-icon-set-fa-icon-', RS_PLUGIN_URL .'public/assets/fonts/font-awesome/css/font-awesome.css', array(), RevSliderGlobals::SLIDER_REVISION);
		//wp_register_style('rs-icon-set-pe-7s-', RS_PLUGIN_URL .'public/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array(), RevSliderGlobals::SLIDER_REVISION);


		if($includesFooter == "off"){

			$waitfor = array('jquery');
			
			$enable_logs = RevSliderFunctions::getVal($arrValues, "enable_logs",'off');
			
			if($enable_logs == 'on'){
				wp_enqueue_script('enable-logs', RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.enablelog.js', $waitfor, RevSliderGlobals::SLIDER_REVISION);
				$waitfor[] = 'enable-logs';
			}
			
			wp_enqueue_script('tp-tools', RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.tools.min.js', $waitfor, RevSliderGlobals::SLIDER_REVISION);
			wp_enqueue_script('revmin', RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.revolution.min.js', 'tp-tools', RevSliderGlobals::SLIDER_REVISION);
			
		}else{
			//put javascript to footer

			add_action('wp_footer', array('RevSliderFront', 'putJavascript'));
		}

		
		add_action('wp_head', array('RevSliderFront', 'add_meta_generator'));
		add_action("wp_footer", array('RevSliderFront',"load_icon_fonts") );
		
		// Async JS Loading
		$js_defer = RevSliderBase::getVar($arrValues, 'js_defer', 'off');
		if($js_defer!='off') add_filter('clean_url', array('RevSliderFront', 'add_defer_forscript'), 11, 1);
		
		add_action('wp_before_admin_bar_render', array('RevSliderFront', 'add_admin_menu_nodes'));
		add_action('wp_footer', array('RevSliderFront', 'putAdminBarMenus'));
	}
	
	/**
	 * add admin menu points in ToolBar Top
	 * @since: 5.0.5
	 */
	public static function putAdminBarMenus () {
		if(!is_super_admin() || !is_admin_bar_showing()) return;
		
		?>
		<script>	
			jQuery(document).ready(function() {			
				
				if (jQuery('#wp-admin-bar-revslider-default').length>0 && jQuery('.rev_slider_wrapper').length>0) {									
					var aliases = new Array();
					jQuery('.rev_slider_wrapper').each(function() {
						aliases.push(jQuery(this).data('alias'));
					});								
					if 	(aliases.length>0)	
						jQuery('#wp-admin-bar-revslider-default li').each(function() {
							var li = jQuery(this),
								t = jQuery.trim(li.find('.ab-item .rs-label').data('alias')); //text()
								
							if (jQuery.inArray(t,aliases)!=-1) {							
							} else {
								li.remove();							
							}
						});																				
				} else {
					jQuery('#wp-admin-bar-revslider').remove();
				}
			});
		</script>
		<?php 	
	}
	
	/**
	 * add admin nodes
	 * @since: 5.0.5
	 */
	public static function add_admin_menu_nodes(){
		if(!is_super_admin() || !is_admin_bar_showing()) return;
		
		self::_add_node('<span class="rs-label">Slider Revolution</span>', false, admin_url('admin.php?page=revslider'), array('class' => 'revslider-menu' ), 'revslider'); //<span class="wp-menu-image dashicons-before dashicons-update"></span>
		
		//add all nodes of all Slider
		$sl = new RevSliderSlider();
		$sliders = $sl->getAllSliderForAdminMenu();
		
		if(!empty($sliders)){
			foreach($sliders as $id => $slider){
				self::_add_node('<span class="rs-label" data-alias="'.esc_attr($slider['alias']).'">'.esc_attr($slider['title']).'</span>', 'revslider', admin_url('admin.php?page=revslider&view=slide&id=new&slider='.intval($id)), array('class' => 'revslider-sub-menu' ), esc_attr($slider['alias'])); //<span class="wp-menu-image dashicons-before dashicons-update"></span>
			}
		}
		
	}
	
	
	/**
	 * add admin node
	 * @since: 5.0.5
	 */
	public static function _add_node($title, $parent = false, $href = '', $custom_meta = array(), $id = ''){
		global $wp_admin_bar;
		
		if(!is_super_admin() || !is_admin_bar_showing()) return;
		
		if($id == '') $id = strtolower(str_replace(' ', '-', $title));

		// links from the current host will open in the current window
		$meta = strpos( $href, site_url() ) !== false ? array() : array( 'target' => '_blank' ); // external links open in new tab/window
		$meta = array_merge( $meta, $custom_meta );

		$wp_admin_bar->add_node(array(
			'parent' => $parent,
			'id'     => $id,
			'title'  => $title,
			'href'   => $href,
			'meta'   => $meta,
		));
	}
	
	
	/**
	 *
	 * create db tables
	 */
	public static function createDBTables(){
		self::createTable(RevSliderGlobals::TABLE_SLIDERS_NAME);
		self::createTable(RevSliderGlobals::TABLE_SLIDES_NAME);
		self::createTable(RevSliderGlobals::TABLE_STATIC_SLIDES_NAME);
		self::createTable(RevSliderGlobals::TABLE_CSS_NAME);
		self::createTable(RevSliderGlobals::TABLE_LAYER_ANIMS_NAME);
		self::createTable(RevSliderGlobals::TABLE_NAVIGATION_NAME);
		
		self::updateTables();
	}
	
	public static function load_icon_fonts(){
		global $fa_icon_var,$pe_7s_var;
		if($fa_icon_var) echo "<link rel='stylesheet' property='stylesheet' id='rs-icon-set-fa-icon-css'  href='" . RS_PLUGIN_URL . "public/assets/fonts/font-awesome/css/font-awesome.css' type='text/css' media='all' />";
		if($pe_7s_var) echo "<link rel='stylesheet' property='stylesheet' id='rs-icon-set-pe-7s-css'  href='" . RS_PLUGIN_URL . "public/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css' type='text/css' media='all' />";
	}
	
	public static function updateTables(){
		$cur_ver = get_option('revslider_table_version', '1.0.0');
		if(version_compare($cur_ver, '1.0.1', '<')){ //add missing settings field, for new creates lines in slide editor for example
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			$tableName = RevSliderGlobals::TABLE_SLIDES_NAME;
			$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
						  id int(9) NOT NULL AUTO_INCREMENT,
						  slider_id int(9) NOT NULL,
						  slide_order int not NULL,
						  params text NOT NULL,
						  layers text NOT NULL,
						  settings text NOT NULL,
						  UNIQUE KEY id (id)
						);";
			dbDelta($sql);
			
			$tableName = RevSliderGlobals::TABLE_STATIC_SLIDES_NAME;
			$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
						  id int(9) NOT NULL AUTO_INCREMENT,
						  slider_id int(9) NOT NULL,
						  params text NOT NULL,
						  layers text NOT NULL,
						  settings text NOT NULL,
						  UNIQUE KEY id (id)
						);";
			dbDelta($sql);
			
			update_option('revslider_table_version', '1.0.1');
			$cur_ver = '1.0.1';
		}
		
		if(version_compare($cur_ver, '1.0.2', '<')){
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$tableName = RevSliderGlobals::TABLE_SLIDERS_NAME;
			
			$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
						  id int(9) NOT NULL AUTO_INCREMENT,
						  title tinytext NOT NULL,
						  alias tinytext,
						  params text NOT NULL,
						  settings text NULL,
						  UNIQUE KEY id (id)
						);";
			dbDelta($sql);
			
			update_option('revslider_table_version', '1.0.2');
			$cur_ver = '1.0.2';
		}
		
		if(version_compare($cur_ver, '1.0.3', '<')){
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$tableName = RevSliderGlobals::TABLE_CSS_NAME;
			
			$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
						  id int(9) NOT NULL AUTO_INCREMENT,
						  handle TEXT NOT NULL,
						  settings TEXT,
						  hover TEXT,
						  advanced MEDIUMTEXT,
						  params TEXT NOT NULL,
						  UNIQUE KEY id (id)
						);";
			dbDelta($sql);
			
			update_option('revslider_table_version', '1.0.3');
			$cur_ver = '1.0.3';
		}
		
		if(version_compare($cur_ver, '1.0.4', '<')){
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_SLIDERS_NAME ." (
					  UNIQUE KEY id (id)
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_SLIDES_NAME ." (
					  UNIQUE KEY id (id)
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_STATIC_SLIDES_NAME ." (
					  UNIQUE KEY id (id)
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_CSS_NAME ." (
					  UNIQUE KEY id (id)
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_LAYER_ANIMS_NAME ." (
					  UNIQUE KEY id (id)
					);";
			dbDelta($sql);
			
			update_option('revslider_table_version', '1.0.4');
			$cur_ver = '1.0.4';
		}
		
		if(version_compare($cur_ver, '1.0.5', '<')){
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_SLIDERS_NAME ." (
					  type VARCHAR(191) NOT NULL
					  params MEDIUMTEXT NOT NULL
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_SLIDES_NAME ." (
					  params MEDIUMTEXT NOT NULL,
					  layers MEDIUMTEXT NOT NULL
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_STATIC_SLIDES_NAME ." (
					  params MEDIUMTEXT NOT NULL,
					  layers MEDIUMTEXT NOT NULL
					);";
			dbDelta($sql);
			$sql = "CREATE TABLE " .self::$table_prefix.RevSliderGlobals::TABLE_LAYER_ANIMS_NAME ." (
					  settings text NULL
					);";
			dbDelta($sql);
			
			update_option('revslider_table_version', '1.0.5');
			$cur_ver = '1.0.5';
		}

	}
	
	
	/**
	 * create tables
	 */
	public static function createTable($tableName){
		global $wpdb;

		$parseCssToDb = false;

		$checkForTablesOneTime = get_option('revslider_checktables', '0');
		
		if($checkForTablesOneTime == '0'){
			update_option('revslider_checktables', '1');
			if(RevSliderFunctionsWP::isDBTableExists(self::$table_prefix.RevSliderGlobals::TABLE_CSS_NAME)){ //$wpdb->tables( 'global' )
				//check if database is empty
				$result = $wpdb->get_row("SELECT COUNT( DISTINCT id ) AS NumberOfEntrys FROM ".self::$table_prefix.RevSliderGlobals::TABLE_CSS_NAME);
				if($result->NumberOfEntrys == 0) $parseCssToDb = true;
			}
		}

		if($parseCssToDb){
			$RevSliderOperations = new RevSliderOperations();
			$RevSliderOperations->importCaptionsCssContentArray();
			$RevSliderOperations->moveOldCaptionsCss();
		}

		//if table exists - don't create it.
		$tableRealName = self::$table_prefix.$tableName;
		if(RevSliderFunctionsWP::isDBTableExists($tableRealName))
			return(false);

		switch($tableName){
			case RevSliderGlobals::TABLE_SLIDERS_NAME:
			$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
						  id int(9) NOT NULL AUTO_INCREMENT,
						  title tinytext NOT NULL,
						  alias tinytext,
						  params MEDIUMTEXT NOT NULL,
						  UNIQUE KEY id (id)
						);";
			break;
			case RevSliderGlobals::TABLE_SLIDES_NAME:
				$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
							  id int(9) NOT NULL AUTO_INCREMENT,
							  slider_id int(9) NOT NULL,
							  slide_order int not NULL,
							  params MEDIUMTEXT NOT NULL,
							  layers MEDIUMTEXT NOT NULL,
							  UNIQUE KEY id (id)
							);";
			break;
			case RevSliderGlobals::TABLE_STATIC_SLIDES_NAME:
				$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
							  id int(9) NOT NULL AUTO_INCREMENT,
							  slider_id int(9) NOT NULL,
							  params MEDIUMTEXT NOT NULL,
							  layers MEDIUMTEXT NOT NULL,
							  UNIQUE KEY id (id)
							);";
			break;
			case RevSliderGlobals::TABLE_CSS_NAME:
				$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
							  id int(9) NOT NULL AUTO_INCREMENT,
							  handle TEXT NOT NULL,
							  settings MEDIUMTEXT,
							  hover MEDIUMTEXT,
							  params MEDIUMTEXT NOT NULL,
							  UNIQUE KEY id (id)
							);";
				$parseCssToDb = true;
			break;
			case RevSliderGlobals::TABLE_LAYER_ANIMS_NAME:
				$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
							  id int(9) NOT NULL AUTO_INCREMENT,
							  handle TEXT NOT NULL,
							  params TEXT NOT NULL,
							  UNIQUE KEY id (id)
							);";
			break;
			case RevSliderGlobals::TABLE_NAVIGATION_NAME:
				$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
							  id int(9) NOT NULL AUTO_INCREMENT,
							  name VARCHAR(191) NOT NULL,
							  handle VARCHAR(191) NOT NULL,
							  css MEDIUMTEXT NOT NULL,
							  markup MEDIUMTEXT NOT NULL,
							  settings MEDIUMTEXT NULL,
							  UNIQUE KEY id (id)
							);";
			break;

			default:
				RevSliderFunctions::throwError("table: $tableName not found");
			break;
		}
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		if($parseCssToDb){
			$RevSliderOperations = new RevSliderOperations();
			$RevSliderOperations->importCaptionsCssContentArray();
			$RevSliderOperations->moveOldCaptionsCss();
		}

	}
	
	
	
	public function enqueue_styles(){
		
	}
	
	
	/**
	 * Change FontURL to new URL (added for chinese support since google is blocked there)
	 * @since: 5.0
	 */
	public static function modify_punch_url($url){
		$operations = new RevSliderOperations();
		$arrValues = $operations->getGeneralSettingsValues();
		
		$set_diff_font = RevSliderFunctions::getVal($arrValues, "change_font_loading",'');
		if($set_diff_font !== ''){
			return $set_diff_font;
		}else{
			return $url;
		}
	}
	
	
	/**
	 * 
	 * javascript output to footer
	 */
	public static function putJavascript(){
		$urlPlugin = RS_PLUGIN_URL."public/assets/";
		
		$operations = new RevSliderOperations();
		$arrValues = $operations->getGeneralSettingsValues();
		
		$js_defer = RevSliderBase::getVar($arrValues, 'js_defer', 'off');
		if($js_defer!='off') $js_defer = 'defer="defer"';
		else $js_defer = '';
		?>
		<script type='text/javascript' <?php echo $js_defer;?> src='<?php echo $urlPlugin; ?>js/jquery.themepunch.tools.min.js?rev=<?php echo RevSliderGlobals::SLIDER_REVISION; ?>'></script>
		<script type='text/javascript' <?php echo $js_defer;?> src='<?php echo $urlPlugin; ?>js/jquery.themepunch.revolution.min.js?rev=<?php echo  RevSliderGlobals::SLIDER_REVISION; ?>'></script>
		<?php
	}
	
	/**
	 * Add Meta Generator Tag in FrontEnd
	 * @since: 5.0
	 */
	public static function add_meta_generator(){
		global $revSliderVersion;
		
		echo '<meta name="generator" content="Powered by Slider Revolution '.$revSliderVersion.' - responsive, Mobile-Friendly Slider Plugin for WordPress with comfortable drag and drop interface." />'."\n";
	}

	/**
	 *
	 * adds async loading
	 * @since: 5.0
	 */
	public static function add_defer_forscript($url)
	{
	    if ( strpos($url, 'themepunch.enablelog.js' )===false && strpos($url, 'themepunch.revolution.min.js' )===false  && strpos($url, 'themepunch.tools.min.js' )===false )
	        return $url;
	    else if (is_admin())
	        return $url;
	    else
	        return $url."' defer='defer"; 
	}
	
}
	
?>