<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class RevSliderAdmin extends RevSliderBaseAdmin{

	const VIEW_SLIDER = "slider";
	const VIEW_SLIDER_TEMPLATE = "slider_template"; //obsolete
	const VIEW_SLIDERS = "sliders";
	const VIEW_SLIDES = "slides";
	const VIEW_SLIDE = "slide";
	
	/**
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

		RevSliderGlobals::$filepath_backup = RS_PLUGIN_PATH.'backup/';
		RevSliderGlobals::$filepath_captions = RS_PLUGIN_PATH.'public/assets/css/captions.css';
		RevSliderGlobals::$urlCaptionsCSS = RS_PLUGIN_URL.'public/assets/css/captions.php';
		RevSliderGlobals::$urlStaticCaptionsCSS = RS_PLUGIN_URL.'public/assets/css/static-captions.css';
		RevSliderGlobals::$filepath_dynamic_captions = RS_PLUGIN_PATH.'public/assets/css/dynamic-captions.css';
		RevSliderGlobals::$filepath_static_captions = RS_PLUGIN_PATH.'public/assets/css/static-captions.css';
		RevSliderGlobals::$filepath_captions_original = RS_PLUGIN_PATH.'public/assets/css/captions-original.css';
		
		$wp_upload_dir = wp_upload_dir();
		$wp_upload_dir = $wp_upload_dir['basedir'].'/';
		RevSliderGlobals::$uploadsUrlExportZip = $wp_upload_dir.'export.zip';

		$this->init();
	}


	/**
	 * init all actions
	 */
	private function init(){
		global $revSliderAsTheme;
		
		$template = new RevSliderTemplate();
		$operations = new RevSliderOperations();
		$general_settings = $operations->getGeneralSettingsValues();
		
		$role = RevSliderBase::getVar($general_settings, 'role', 'admin');
		
		self::setMenuRole($role);

		self::addMenuPage('Revolution Slider', "adminPages");
		
		self::addSubMenuPage(__('Navigation Editor', REVSLIDER_TEXTDOMAIN), 'display_plugin_submenu_page_navigation', 'revslider_navigation');
		

		$this->addSliderMetaBox();

		//ajax response to save slider options.
		self::addActionAjax("ajax_action", "onAjaxAction");
		
		//add common scripts there
		$validated = get_option('revslider-valid', 'false');
		$notice = get_option('revslider-valid-notice', 'true');
		$latestv = RevSliderGlobals::SLIDER_REVISION;
		$stablev = get_option('revslider-stable-version', '0');
		
		$upgrade = new RevSliderUpdate( GlobalsRevSlider::SLIDER_REVISION );
		
		if(!$revSliderAsTheme || version_compare($latestv, $stablev, '<')){
			if($validated === 'false' && $notice === 'true'){
				add_action('admin_notices', array($this, 'addActivateNotification'));
			}

			if(isset($_GET['checkforupdates']) && $_GET['checkforupdates'] == 'true')
				$upgrade->_retrieve_version_info(true);
			
			if($validated === 'true' || version_compare($latestv, $stablev, '<')) {
				$upgrade->add_update_checks();
			}
		}
		
		if(isset($_REQUEST['update_shop'])){
			$template->_get_template_list(true);
		}else{
			$template->_get_template_list();
		}
		
		$upgrade->_retrieve_version_info();
		add_action('admin_notices', array($this, 'add_notices'));
		
		add_action('admin_enqueue_scripts', array('RevSliderAdmin', 'enqueue_styles'));
		
		add_action('admin_enqueue_scripts', array('RevSliderAdmin', 'enqueue_all_admin_scripts'));
		
		add_action('wp_ajax_revslider_ajax_call_front', array('RevSliderAdmin', 'onFrontAjaxAction'));
		add_action('wp_ajax_nopriv_revslider_ajax_call_front', array('RevSliderAdmin', 'onFrontAjaxAction')); //for not logged in users
		
		add_action( 'admin_head', array('RevSliderAdmin', 'include_custom_css' ));

	}
	
	
	public static function enqueue_styles(){
		wp_enqueue_style('rs-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700,600,800');
	}

	
	public static function include_custom_css(){
		
		$type = (isset($_GET['view'])) ? $_GET['view'] : '';
		$page = @$_GET['page'];
		
		if($page !== 'slider' && $page !== 'revslider_navigation') return false; //showbiz fix
		
		$sliderID = '';
		
		switch($type){
			case 'slider':
				
				$sliderID = (isset($_GET['id'])) ? $_GET['id'] : '';
			break;
			case 'slide':
				$slideID = (isset($_GET['id'])) ? $_GET['id'] : '';
				if($slideID == 'new') break;
				
				$slide = new RevSlide();
				$slide->initByID($slideID);
				$sliderID = $slide->getSliderID();
			break;
			default:
				if(isset($_GET['slider'])){
					$sliderID = $_GET['slider'];
				}
			break;
		}

		$arrFieldsParams = array();

		if(!empty($sliderID)){
			$slider = new RevSlider();
			$slider->initByID($sliderID);
			$settingsFields = $slider->getSettingsFields();
			$arrFieldsMain = $settingsFields['main'];
			$arrFieldsParams = $settingsFields['params'];			
			$custom_css = @stripslashes($arrFieldsParams['custom_css']);
			$custom_css = RevSliderCssParser::compress_css($custom_css);
			echo '<style>'.$custom_css.'</style>';
		}
	}
	
	
	public static function enqueue_all_admin_scripts() {
		wp_localize_script('unite_admin', 'rev_lang', self::get_javascript_multilanguage()); //Load multilanguage for JavaScript
		
		wp_enqueue_style(array('wp-color-picker'));
		wp_enqueue_script(array('wp-color-picker'));
	}
	

	/**
	 * Include wanted submenu page
	 */
	public function display_plugin_submenu_page_navigation() {
		self::display_plugin_submenu('navigation-editor');
	}
	

	/**
	 * Include wanted submenu page
	 */
	public function display_plugin_submenu_page_google_fonts() {
		self::display_plugin_submenu('themepunch-google-fonts');
	}

	
	public static function display_plugin_submenu($subMenu){

		parent::adminPages();

		self::setMasterView('master-view');
		self::requireView($subMenu);
	}
	
	
	/**
	 * Create Multilanguage for JavaScript
	 */
	protected static function get_javascript_multilanguage(){
		$lang = array(
			'wrong_alias' => __('-- wrong alias -- ', REVSLIDER_TEXTDOMAIN),
			'nav_bullet_arrows_to_none' => __('Navigation Bullets and Arrows are now set to none.', REVSLIDER_TEXTDOMAIN),
			'create_template' => __('Create Template', REVSLIDER_TEXTDOMAIN),
			'really_want_to_delete' => __('Do you really want to delete', REVSLIDER_TEXTDOMAIN),
			'sure_to_replace_urls' => __('Are you sure to replace the urls?', REVSLIDER_TEXTDOMAIN),
			'set_settings_on_all_slider' => __('Set selected settings on all Slides of this Slider? (This will be saved immediately)', REVSLIDER_TEXTDOMAIN),
			'select_slide_img' => __('Select Slide Image', REVSLIDER_TEXTDOMAIN),
			'select_slide_video' => __('Select Slide Video', REVSLIDER_TEXTDOMAIN),
			'show_slide_opt' => __('Show Slide Options', REVSLIDER_TEXTDOMAIN),
			'hide_slide_opt' => __('Hide Slide Options', REVSLIDER_TEXTDOMAIN),
			'close' => __('Close', REVSLIDER_TEXTDOMAIN),
			'really_update_global_styles' => __('Really update global styles?', REVSLIDER_TEXTDOMAIN),
			'global_styles_editor' => __('Global Styles Editor', REVSLIDER_TEXTDOMAIN),
			'select_image' => __('Select Image', REVSLIDER_TEXTDOMAIN),
			'video_not_found' => __('No Thumbnail Image Set on Video / Video Not Found / No Valid Video ID', REVSLIDER_TEXTDOMAIN),
			'handle_at_least_three_chars' => __('Handle has to be at least three character long', REVSLIDER_TEXTDOMAIN),
			'really_change_font_sett' => __('Really change font settings?', REVSLIDER_TEXTDOMAIN),
			'really_delete_font' => __('Really delete font?', REVSLIDER_TEXTDOMAIN),
			'class_exist_overwrite' => __('Class already exists, overwrite?', REVSLIDER_TEXTDOMAIN),
			'class_must_be_valid' => __('Class must be a valid CSS class name', REVSLIDER_TEXTDOMAIN),
			'really_overwrite_class' => __('Really overwrite Class?', REVSLIDER_TEXTDOMAIN),
			'relly_delete_class' => __('Really delete Class', REVSLIDER_TEXTDOMAIN),
			'class_this_cant_be_undone' => __('? This can\'t be undone!', REVSLIDER_TEXTDOMAIN),
			'this_class_does_not_exist' => __('This class does not exist.', REVSLIDER_TEXTDOMAIN),
			'making_changes_will_probably_overwrite_advanced' => __('Making changes to these settings will probably overwrite advanced settings. Continue?', REVSLIDER_TEXTDOMAIN),
			'select_static_layer_image' => __('Select Static Layer Image', REVSLIDER_TEXTDOMAIN),
			'select_layer_image' => __('Select Layer Image', REVSLIDER_TEXTDOMAIN),
			'really_want_to_delete_all_layer' => __('Do you really want to delete all the layers?', REVSLIDER_TEXTDOMAIN),
			'layer_animation_editor' => __('Layer Animation Editor', REVSLIDER_TEXTDOMAIN),
			'animation_exists_overwrite' => __('Animation already exists, overwrite?', REVSLIDER_TEXTDOMAIN),
			'really_overwrite_animation' => __('Really overwrite animation?', REVSLIDER_TEXTDOMAIN),
			'default_animations_cant_delete' => __('Default animations can\'t be deleted', REVSLIDER_TEXTDOMAIN),
			'must_be_greater_than_start_time' => __('Must be greater than start time', REVSLIDER_TEXTDOMAIN),
			'sel_layer_not_set' => __('Selected layer not set', REVSLIDER_TEXTDOMAIN),
			'edit_layer_start' => __('Edit Layer Start', REVSLIDER_TEXTDOMAIN),
			'edit_layer_end' => __('Edit Layer End', REVSLIDER_TEXTDOMAIN),
			'default_animations_cant_rename' => __('Default Animations can\'t be renamed', REVSLIDER_TEXTDOMAIN),
			'anim_name_already_exists' => __('Animationname already existing', REVSLIDER_TEXTDOMAIN),
			'css_name_already_exists' => __('CSS classname already existing', REVSLIDER_TEXTDOMAIN),
			'css_orig_name_does_not_exists' => __('Original CSS classname not found', REVSLIDER_TEXTDOMAIN),
			'enter_correct_class_name' => __('Enter a correct class name', REVSLIDER_TEXTDOMAIN),
			'class_not_found' => __('Class not found in database', REVSLIDER_TEXTDOMAIN),
			'css_name_does_not_exists' => __('CSS classname not found', REVSLIDER_TEXTDOMAIN),
			'delete_this_caption' => __('Delete this caption? This may affect other Slider', REVSLIDER_TEXTDOMAIN),
			'this_will_change_the_class' => __('This will update the Class with the current set Style settings, this may affect other Sliders. Proceed?', REVSLIDER_TEXTDOMAIN),
			'unsaved_changes_will_not_be_added' => __('Template will have the state of the last save, proceed?', REVSLIDER_TEXTDOMAIN),
			'please_enter_a_slide_title' => __('Please enter a Slide title', REVSLIDER_TEXTDOMAIN),
			'please_wait_a_moment' => __('Please Wait a Moment', REVSLIDER_TEXTDOMAIN),
			'copy_move' => __('Copy / Move', REVSLIDER_TEXTDOMAIN),
			'preset_loaded' => __('Preset Loaded', REVSLIDER_TEXTDOMAIN),
			'add_bulk_slides' => __('Add Bulk Slides', REVSLIDER_TEXTDOMAIN),
			'select_image' => __('Select Image', REVSLIDER_TEXTDOMAIN),
			'arrows' => __('Arrows', REVSLIDER_TEXTDOMAIN),
			'bullets' => __('Bullets', REVSLIDER_TEXTDOMAIN),
			'thumbnails' => __('Thumbnails', REVSLIDER_TEXTDOMAIN),
			'tabs' => __('Tabs', REVSLIDER_TEXTDOMAIN),
			'delete_navigation' => __('Delete this Navigation?', REVSLIDER_TEXTDOMAIN),
			'could_not_update_nav_name' => __('Navigation name could not be updated', REVSLIDER_TEXTDOMAIN),
			'name_too_short_sanitize_3' => __('Name too short, at least 3 letters between a-zA-z needed', REVSLIDER_TEXTDOMAIN),
			'nav_name_already_exists' => __('Navigation name already exists, please choose a different name', REVSLIDER_TEXTDOMAIN),
			'remove_nav_element' => __('Remove current element from Navigation?', REVSLIDER_TEXTDOMAIN),
			'create_this_nav_element' => __('This navigation element does not exist, create one?', REVSLIDER_TEXTDOMAIN),
			'overwrite_animation' => __('Overwrite current animation?', REVSLIDER_TEXTDOMAIN),
			'cant_modify_default_anims' => __('Default animations can\'t be changed', REVSLIDER_TEXTDOMAIN),
			'anim_with_handle_exists' => __('Animation already existing with given handle, please choose a different name.', REVSLIDER_TEXTDOMAIN),
			'really_delete_anim' => __('Really delete animation:', REVSLIDER_TEXTDOMAIN),
			'this_will_reset_navigation' => __('This will reset the navigation, continue?', REVSLIDER_TEXTDOMAIN),
			'preset_name_already_exists' => __('Preset name already exists, please choose a different name', REVSLIDER_TEXTDOMAIN),
			'delete_preset' => __('Really delete this preset?', REVSLIDER_TEXTDOMAIN),
			'update_preset' => __('This will update the preset with the current settings. Proceed?', REVSLIDER_TEXTDOMAIN),
			'maybe_wrong_yt_id' => __('No Thumbnail Image Set on Video / Video Not Found / No Valid Video ID', REVSLIDER_TEXTDOMAIN),
			'preset_not_found' => __('Preset not found', REVSLIDER_TEXTDOMAIN),
			'cover_image_needs_to_be_set' => __('Cover Image need to be set for videos', REVSLIDER_TEXTDOMAIN),
			'remove_this_action' => __('Really remove this action?', REVSLIDER_TEXTDOMAIN),
			'layer_action_by' => __('Layer is triggered by ', REVSLIDER_TEXTDOMAIN),
			'due_to_action' => __(' due to action: ', REVSLIDER_TEXTDOMAIN),
			'layer' => __('layer:', REVSLIDER_TEXTDOMAIN),
			'start_layer_in' => __('Start Layer "in" animation', REVSLIDER_TEXTDOMAIN),
			'start_layer_out' => __('Start Layer "out" animation', REVSLIDER_TEXTDOMAIN),
			'start_video' => __('Start Video', REVSLIDER_TEXTDOMAIN),
			'stop_video' => __('Stop Video', REVSLIDER_TEXTDOMAIN),
			'toggle_layer_anim' => __('Toggle Layer Animation', REVSLIDER_TEXTDOMAIN),
			'toggle_video' => __('Toggle Video', REVSLIDER_TEXTDOMAIN),
			'last_slide' => __('Last Slide', REVSLIDER_TEXTDOMAIN),
			'simulate_click' => __('Simulate Click', REVSLIDER_TEXTDOMAIN),
			'toggle_class' => __('Toogle Class', REVSLIDER_TEXTDOMAIN),
			'copy_styles_to_hover_from_idle' => __('Copy hover styles to idle?', REVSLIDER_TEXTDOMAIN),
			'copy_styles_to_idle_from_hover' => __('Copy idle styles to hover?', REVSLIDER_TEXTDOMAIN),
			'select_at_least_one_device_type' => __('Please select at least one device type', REVSLIDER_TEXTDOMAIN),
			'please_select_first_an_existing_style' => __('Please select an existing Style Template', REVSLIDER_TEXTDOMAIN),
			'cant_remove_last_transition' => __('Can not remove last transition!', REVSLIDER_TEXTDOMAIN),
			'name_is_default_animations_cant_be_changed' => __('Given animation name is a default animation. These can not be changed.', REVSLIDER_TEXTDOMAIN),
			'override_animation' => __('Animation exists, override existing animation?', REVSLIDER_TEXTDOMAIN),
			'this_feature_only_if_activated' => __('This feature is only available if you activate Slider Revolution for this installation', REVSLIDER_TEXTDOMAIN),
			'unsaved_data_will_be_lost_proceed' => __('Unsaved data will be lost, proceed?', REVSLIDER_TEXTDOMAIN)
		);

		return $lang;
	}

	
	public function addActivateNotification(){
		$nonce = wp_create_nonce("revslider_actions");
		?>
		<div class="updated below-h2 rs-update-notice-wrap" id="message"><a href="javascript:void(0);" style="float: right;padding-top: 9px;" id="rs-dismiss-notice"><?php _e('(never show this message again)&nbsp;&nbsp;<b>X</b>',REVSLIDER_TEXTDOMAIN); ?></a><p><?php _e('Hi! Would you like to activate your version of Revolution Slider to receive live updates & get premium support? This is optional and not needed if the slider came bundled with a theme. ',REVSLIDER_TEXTDOMAIN); ?></p></div>
		<script type="text/javascript">
			jQuery('#rs-dismiss-notice').click(function(){
				var objData = {
							action:"revslider_ajax_action",
							client_action: 'dismiss_notice',
							nonce:'<?php echo $nonce; ?>',
							data:''
							};

				jQuery.ajax({
					type:"post",
					url:ajaxurl,
					dataType: 'json',
					data:objData
				});

				jQuery('.rs-update-notice-wrap').hide();
			});
		</script>
		<?php
	}
	
	
	/**
	 * add notices from ThemePunch
	 * @since: 4.6.8
	 */
	public function add_notices(){
		$operations = new RevSliderOperations();
		$general_settings = $operations->getGeneralSettingsValues();
		
		
		$enable_newschannel = RevSliderBase::getVar($general_settings, 'enable_newschannel', 'on');
		
		$enable_newschannel = apply_filters('revslider_set_notifications', $enable_newschannel);
		
		if($enable_newschannel == 'on'){
			
			$nonce = wp_create_nonce("revslider_actions");
			
			$notices = get_option('revslider-notices', false);

			if(!empty($notices) && is_array($notices)){
				global $revslider_screens;
				
				$notices_discarded = get_option('revslider-notices-dc', array());
				
				$screen = get_current_screen();
				foreach($notices as $notice){
						if($notice->is_global !== true && !in_array($screen->id, $revslider_screens)) continue; //check if global or just on plugin related pages
							
						if(!in_array($notice->code, $notices_discarded) && version_compare($notice->version, GlobalsRevSlider::SLIDER_REVISION, '>=')){
							$text = '<div style="text-align:right;vertical-align:middle;display:table-cell; min-width:225px;border-left:1px solid #ddd; padding-left:15px;"><a href="javascript:void(0);"  class="rs-notices-button rs-notice-'. esc_attr($notice->code) .'">'. __('Close & don\'t show again<b>X</b>',REVSLIDER_TEXTDOMAIN) .'</a></div>';
							if($notice->disable == true) $text = '';
							?>
								<style>
							.rs-notices-button			{	color:#999; text-decoration: none !important; font-size:14px;font-weight: 400;}
							.rs-notices-button:hover 	{	color:#3498DB !important;}

							.rs-notices-button b 		{	font-weight:800; vertical-align:bottom;line-height:15px;font-size:10px;margin-left:10px;margin-right:10px;border:2px solid #999; display:inline-block; width:15px;height:15px; text-align: center; border-radius: 50%; -webkit-border-radius: 50%; -moz-border-radius: 50%;}
							.rs-notices-button:hover b  { 	border-color:#3498DB;}
							</style>
							<div class="<?php echo $notice->color; ?> below-h2 rs-update-notice-wrap" id="message" style="clear:both;display: block;position:relative;margin:35px 20px 25px 0px"><div style="display:table;width:100%;"><div style="vertical-align:middle;display:table-cell;min-width:100%;padding-right:15px;"><?php echo $notice->text; ?></div><?php echo $text; ?></div></div>

							<?php
						}
					}
				?>
				<script type="text/javascript">
					jQuery('.rs-notices-button').click(function(){
						
						var notice_id = jQuery(this).attr('class').replace('rs-notices-button', '').replace('rs-notice-', '');
						
						var objData = {
										action:"revslider_ajax_action",
										client_action: 'dismiss_dynamic_notice',
										nonce:'<?php echo $nonce; ?>',
										data:{'id':notice_id}
										};

						jQuery.ajax({
							type:"post",
							url:ajaxurl,
							dataType: 'json',
							data:objData
						});

						jQuery(this).closest('.rs-update-notice-wrap').slideUp(200);
					});
				</script>
				<?php
			}
		}
	}
	
	
	/**
	 *
	 * add wildcards metabox variables to posts
	 */
	private function addSliderMetaBox($postTypes = null){ //null = all, post = only posts
		try{
			self::addMetaBox("Revolution Slider Options",'',array("RevSliderAdmin","customPostFieldsOutput"),$postTypes);
		}catch(Exception $e){}
	}


	/**
	 *  custom output function
	 */
	public static function customPostFieldsOutput(){
		
		$meta = get_post_meta(get_the_ID(), 'slide_template', true);
		if($meta == '') $meta = 'default';
		
		$slider = new RevSlider();
		$arrOutput = array();
		$arrOutput["default"] = "default";

		$arrSlides = $slider->getArrSlidersWithSlidesShort(RevSlider::SLIDER_TYPE_TEMPLATE);
		$arrOutput = $arrOutput + $arrSlides;	//union arrays
		
		?>
		<ul class="revslider_settings">
			<li id="slide_template_row">
				<div title="" class="setting_text" id="slide_template_text"><?php _e('Choose Slide Template', REVSLIDER_TEXTDOMAIN); ?></div>
				<div class="setting_input">
					<select name="slide_template" id="slide_template">
						<?php
						foreach($arrOutput as $handle => $name){
							echo '<option '.selected($handle, $meta).' value="'.$handle.'">'.$name.'</option>';
						}
						?>
					</select>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
		<?php
	}


	/**
	 * a must function. please don't remove it.
	 * process activate event - install the db (with delta).
	 */
	public static function onActivate(){
		RevSliderFront::createDBTables();
	}


	/**
	 * a must function. adds scripts on the page
	 * add all page scripts and styles here.
	 * pelase don't remove this function
	 * common scripts even if the plugin not load, use this function only if no choise.
	 */
	public static function onAddScripts(){
		global $wp_version;
		
		$style_pre = '';
		$style_post = '';
		if($wp_version < 3.7){
			$style_pre = '<style type="text/css">';
			$style_post = '</style>';
		}
		
		wp_enqueue_style('edit_layers', RS_PLUGIN_URL .'admin/assets/css/edit_layers.css', array(), RevSliderGlobals::SLIDER_REVISION);
		
		wp_enqueue_script('unite_layers_timeline', RS_PLUGIN_URL .'admin/assets/js/edit_layers_timeline.js', array(), RevSliderGlobals::SLIDER_REVISION );
		wp_enqueue_script('unite_layers', RS_PLUGIN_URL .'admin/assets/js/edit_layers.js', array('jquery-ui-mouse'), RevSliderGlobals::SLIDER_REVISION );
		wp_enqueue_script('unite_css_editor', RS_PLUGIN_URL .'admin/assets/js/css_editor.js', array(), RevSliderGlobals::SLIDER_REVISION );
		wp_enqueue_script('rev_admin', RS_PLUGIN_URL .'admin/assets/js/rev_admin.js', array(), RevSliderGlobals::SLIDER_REVISION );
		
		wp_enqueue_script('tp-tools', RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.tools.min.js', array(), RevSliderGlobals::SLIDER_REVISION );

		//include all media upload scripts
		self::addMediaUploadIncludes();

		//add rs css:
		wp_enqueue_style('rs-plugin-settings', RS_PLUGIN_URL .'public/assets/css/settings.css', array(), RevSliderGlobals::SLIDER_REVISION);
		
		//add icon sets
		wp_enqueue_style('rs-icon-set-fa-icon-', RS_PLUGIN_URL .'public/assets/fonts/font-awesome/css/font-awesome.css', array(), RevSliderGlobals::SLIDER_REVISION);
		wp_enqueue_style('rs-icon-set-pe-7s-', RS_PLUGIN_URL .'public/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array(), RevSliderGlobals::SLIDER_REVISION);
		
		add_filter('revslider_mod_icon_sets', array('RevSliderBase', 'set_icon_sets'));
		
		$db = new RevSliderDB();

		$styles = $db->fetch(RevSliderGlobals::$table_css);
		$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
		$styles = RevSliderCssParser::compress_css($styles);
		wp_add_inline_style( 'rs-plugin-settings', $style_pre.$styles.$style_post );

		$custom_css = RevSliderOperations::getStaticCss();
		$custom_css = RevSliderCssParser::compress_css($custom_css);
		wp_add_inline_style( 'rs-plugin-settings', $style_pre.$custom_css.$style_post );
		
	}


	/**
	 *
	 * admin main page function.
	 */
	public static function adminPages(){

		parent::adminPages();

		self::setMasterView('master-view');
		self::requireView(self::$view);
		
	}
	

	/**
	 *
	 * import slideer handle (not ajax response)
	 */
	private static function importSliderHandle($viewBack = null, $updateAnim = true, $updateStatic = true){

		dmp(__("importing slider settings and data...",REVSLIDER_TEXTDOMAIN));

		$slider = new RevSlider();
		$response = $slider->importSliderFromPost($updateAnim, $updateStatic);
		$sliderID = $response["sliderID"];

		if(empty($viewBack)){
			$viewBack = self::getViewUrl(self::VIEW_SLIDER,"id=".$sliderID);
			if(empty($sliderID))
				$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
		}

		//handle error
		if($response["success"] == false){
			$message = $response["error"];
			dmp("<b>Error: ".$message."</b>");
			echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",REVSLIDER_TEXTDOMAIN));
		}
		else{	//handle success, js redirect.
			dmp(__("Slider Import Success, redirecting...",REVSLIDER_TEXTDOMAIN));
			echo "<script>location.href='$viewBack'</script>";
		}
		exit();
	}
	
	
	/**
	 * import slider from TP servers
	 * @since: 5.0.5
	 */
	private static function importSliderOnlineTemplateHandle($viewBack = null, $updateAnim = true, $updateStatic = true, $single_slide = false){
		dmp(__("downloading template slider from server...", REVSLIDER_TEXTDOMAIN));
		
		$uid = esc_attr(RevSliderFunctions::getPostVariable('uid'));
		if($uid == ''){
			dmp(__("ID missing, something went wrong. Please try again!", REVSLIDER_TEXTDOMAIN));
			echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",REVSLIDER_TEXTDOMAIN));
			exit;
		}else{
			//send request to TP server and download file
			$tmp = new RevSliderTemplate();
			
			$filepath = $tmp->_download_template($uid);
			
			if($filepath !== false && !is_array($filepath)){
				//check if Slider Template was already imported. If yes, remove the old Slider Template as we now do an "update" (in reality we delete and insert again)
				//get all template sliders
				$tmp_slider = $tmp->getThemePunchTemplateSliders();
				
				foreach($tmp_slider as $tslider){
					if(isset($tslider['uid']) && $uid == $tslider['uid']){
						if(!isset($tslider['installed'])){ //slider is installed
							//delete template Slider!
							$mSlider = new RevSlider();
							$mSlider->initByID($tslider['id']);
							
							$mSlider->deleteSlider();
							//remove the update flag from the slider
							
							$tmp->remove_is_new($uid);
						}
						break;
					}
				}
				
				
				$slider = new RevSlider();
				$response = $slider->importSliderFromPost($updateAnim, $updateStatic, $filepath, $uid, $single_slide);
				
				$tmp->_delete_template($uid);
				
				if($single_slide === false){
					if(empty($viewBack)){
						$sliderID = $response["sliderID"];
						$viewBack = self::getViewUrl(self::VIEW_SLIDER,"id=".$sliderID);
						if(empty($sliderID))
							$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
					}
				}

				//handle error
				if($response["success"] == false){
					$message = $response["error"];
					dmp("<b>Error: ".$message."</b>");
					echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",REVSLIDER_TEXTDOMAIN));
				}else{	//handle success, js redirect.
					dmp(__("Slider Import Success, redirecting...",REVSLIDER_TEXTDOMAIN));
					echo "<script>location.href='$viewBack'</script>";
				}
				
			}else{
				if(is_array($filepath)){
					dmp($filepath['error']);
				}else{
					dmp(__("Could not download from server. Please try again later!", REVSLIDER_TEXTDOMAIN));
				}
				echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",REVSLIDER_TEXTDOMAIN));
				exit;
			}
		}
		
		exit;
	}
	
	
	/**
	 *
	 * import slider handle (not ajax response)
	 */
	private static function importSliderTemplateHandle($viewBack = null, $updateAnim = true, $updateStatic = true, $single_slide = false){
		
		dmp(__("importing template slider settings and data...", REVSLIDER_TEXTDOMAIN));
		
		$uid = esc_attr(RevSliderFunctions::getPostVariable('uid'));
		if($uid == ''){
			dmp(__("ID missing, something went wrong. Please try again!", REVSLIDER_TEXTDOMAIN));
			echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",REVSLIDER_TEXTDOMAIN));
			exit;
		}
		
		//check if the filename is correct
		//import to templates, then duplicate Slider
		
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost($updateAnim, $updateStatic, false, $uid, $single_slide);

		if($single_slide === false){
			if(empty($viewBack)){
				$sliderID = $response["sliderID"];
				$viewBack = self::getViewUrl(self::VIEW_SLIDER,"id=".$sliderID);
				if(empty($sliderID))
					$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
			}
		}

		//handle error
		if($response["success"] == false){
			$message = $response["error"];
			dmp("<b>Error: ".$message."</b>");
			echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",REVSLIDER_TEXTDOMAIN));
		}else{	//handle success, js redirect.
			dmp(__("Slider Import Success, redirecting...",REVSLIDER_TEXTDOMAIN));
			echo "<script>location.href='$viewBack'</script>";
		}
		
		exit();
	}

	/**
	 * Get url to secific view.
	 */
	public static function getFontsUrl(){

		$link = admin_url('admin.php?page=themepunch-google-fonts');
		return($link);
	}
	
	
	/**
	 * Toggle Favorite State of Slider
	 * @since: 5.0
	 */
	public static function toggle_favorite_by_id($id){
		$id = intval($id);
		if($id === 0) return false;
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . RevSliderGlobals::TABLE_SLIDERS_NAME;
		
		//check if ID exists
		$slider = $wpdb->get_row($wpdb->prepare("SELECT settings FROM $table_name WHERE id = %s", $id), ARRAY_A);
		
		if(empty($slider))
			return __('Slider not found', REVSLIDER_TEXTDOMAIN);
			
		$settings = json_decode($slider['settings'], true);
		
		if(!isset($settings['favorite']) || $settings['favorite'] == 'false' || $settings['favorite'] == false){
			$settings['favorite'] = 'true';
		}else{
			$settings['favorite'] = 'false';
		}
		
		$response = $wpdb->update($table_name, array('settings' => json_encode($settings)), array('id' => $id));
		
		if($response === false) return __('Slider setting could not be changed', REVSLIDER_TEXTDOMAIN);
		
		return true;
	}

	/**
	 *
	 * onAjax action handler
	 */
	public static function onAjaxAction(){

		$slider = new RevSlider();
		$slide = new RevSlide();
		$operations = new RevSliderOperations();

		$action = self::getPostGetVar("client_action");
		$data = self::getPostGetVar("data");
		$nonce = self::getPostGetVar("nonce");
		if(empty($nonce))
			$nonce = self::getPostGetVar("rs-nonce");

		try{

			//verify the nonce
			$isVerified = wp_verify_nonce($nonce, "revslider_actions");

			if($isVerified == false)
				RevSliderFunctions::throwError("Wrong request");

			switch($action){
				case 'add_new_preset':
					
					if(!isset($data['settings']) || !isset($data['values'])) self::ajaxResponseError(__('Missing values to add preset', REVSLIDER_TEXTDOMAIN), false);
					
					$result = $operations->add_preset_setting($data);
					
					if($result === true){
						
						$presets = $operations->get_preset_settings();
						
						self::ajaxResponseSuccess(__('Preset created', REVSLIDER_TEXTDOMAIN), array('data' => $presets));
					}else{
						self::ajaxResponseError($result, false);
					}
					
					exit;
				break;
				case 'update_preset':
					
					if(!isset($data['name']) || !isset($data['values'])) self::ajaxResponseError(__('Missing values to update preset', REVSLIDER_TEXTDOMAIN), false);
					
					$result = $operations->update_preset_setting($data);
					
					if($result === true){
						
						$presets = $operations->get_preset_settings();
						
						self::ajaxResponseSuccess(__('Preset created', REVSLIDER_TEXTDOMAIN), array('data' => $presets));
					}else{
						self::ajaxResponseError($result, false);
					}
					
					exit;
				break;
				case 'remove_preset':
					
					if(!isset($data['name'])) self::ajaxResponseError(__('Missing values to remove preset', REVSLIDER_TEXTDOMAIN), false);
					
					$result = $operations->remove_preset_setting($data);
					
					if($result === true){
						
						$presets = $operations->get_preset_settings();
						
						self::ajaxResponseSuccess(__('Preset deleted', REVSLIDER_TEXTDOMAIN), array('data' => $presets));
					}else{
						self::ajaxResponseError($result, false);
					}
					
					exit;
				break;
				case "export_slider":
					$sliderID = self::getGetVar("sliderid");
					$dummy = self::getGetVar("dummy");
					$slider->initByID($sliderID);
					$slider->exportSlider($dummy);
				break;
				case "import_slider":
					$updateAnim = self::getPostGetVar("update_animations");
					$updateStatic = self::getPostGetVar("update_static_captions");
					self::importSliderHandle(null, $updateAnim, $updateStatic);
				break;
				case "import_slider_slidersview":
					$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
					$updateAnim = self::getPostGetVar("update_animations");
					$updateStatic = self::getPostGetVar("update_static_captions");
					self::importSliderHandle($viewBack, $updateAnim, $updateStatic);
				break;
				case "import_slider_online_template_slidersview":
					$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
					self::importSliderOnlineTemplateHandle($viewBack, 'true', 'none');
				break;
				case "import_slide_online_template_slidersview":
					$redirect_id = esc_attr(self::getPostGetVar("redirect_id"));
					$viewBack = self::getViewUrl(self::VIEW_SLIDE,"id=$redirect_id");
					$slidenum = intval(self::getPostGetVar("slidenum"));
					$sliderid = intval(self::getPostGetVar("slider_id"));
					
					self::importSliderOnlineTemplateHandle($viewBack, 'true', 'none', array('slider_id' => $sliderid, 'slide_id' => $slidenum));
				break;
				case "import_slider_template_slidersview":
					$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
					$updateAnim = self::getPostGetVar("update_animations");
					$updateStatic = self::getPostGetVar("update_static_captions");
					self::importSliderTemplateHandle($viewBack, $updateAnim, $updateStatic);
				break;
				case "import_slide_template_slidersview":
					
					$redirect_id = esc_attr(self::getPostGetVar("redirect_id"));
					$viewBack = self::getViewUrl(self::VIEW_SLIDE,"id=$redirect_id");
					$updateAnim = self::getPostGetVar("update_animations");
					$updateStatic = self::getPostGetVar("update_static_captions");
					$slidenum = intval(self::getPostGetVar("slidenum"));
					$sliderid = intval(self::getPostGetVar("slider_id"));
					
					self::importSliderTemplateHandle($viewBack, $updateAnim, $updateStatic, array('slider_id' => $sliderid, 'slide_id' => $slidenum));
				break;
				case "create_slider":
					$data = $operations->modifyCustomSliderParams($data);

					$newSliderID = $slider->createSliderFromOptions($data);

					self::ajaxResponseSuccessRedirect(__("Slider created",REVSLIDER_TEXTDOMAIN), self::getViewUrl("sliders"));

				break;
				case "update_slider":
					$data = $operations->modifyCustomSliderParams($data);
					$slider->updateSliderFromOptions($data);
					self::ajaxResponseSuccess(__("Slider updated",REVSLIDER_TEXTDOMAIN));
				break;
				case "delete_slider":
				case "delete_slider_stay":

					$isDeleted = $slider->deleteSliderFromData($data);

					if(is_array($isDeleted)){
						$isDeleted = implode(', ', $isDeleted);
						self::ajaxResponseError(__("Template can't be deleted, it is still being used by the following Sliders: ", REVSLIDER_TEXTDOMAIN).$isDeleted);
					}else{
						if($action == 'delete_slider_stay'){
							self::ajaxResponseSuccess(__("Slider deleted",REVSLIDER_TEXTDOMAIN));
						}else{
							self::ajaxResponseSuccessRedirect(__("Slider deleted",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDERS));
						}
					}
				break;
				case "duplicate_slider":

					$slider->duplicateSliderFromData($data);

					self::ajaxResponseSuccessRedirect(__("Success! Refreshing page...",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDERS));
				break;
				case "add_slide":
				case "add_bulk_slide":
					$numSlides = $slider->createSlideFromData($data);
					$sliderID = $data["sliderid"];

					if($numSlides == 1){
						$responseText = __("Slide Created",REVSLIDER_TEXTDOMAIN);
					}else{
						$responseText = $numSlides . " ".__("Slides Created",REVSLIDER_TEXTDOMAIN);
					}

					$urlRedirect = self::getViewUrl(self::VIEW_SLIDE,"id=new&slider=$sliderID");
					self::ajaxResponseSuccessRedirect($responseText,$urlRedirect);

				break;
				case "add_slide_fromslideview":
					$slideID = $slider->createSlideFromData($data,true);
					$urlRedirect = self::getViewUrl(self::VIEW_SLIDE,"id=$slideID");
					$responseText = __("Slide Created, redirecting...",REVSLIDER_TEXTDOMAIN);
					self::ajaxResponseSuccessRedirect($responseText,$urlRedirect);
				break;
				case 'copy_slide_to_slider':
					$slideID = (isset($data['redirect_id'])) ? $data['redirect_id'] : -1;
					
					if($slideID === -1) RevSliderFunctions::throwError(__('Missing redirect ID!', REVSLIDER_TEXTDOMAIN));
					
					$return = $slider->copySlideToSlider($data);
					
					if($return !== true) RevSliderFunctions::throwError($return);
					
					$urlRedirect = self::getViewUrl(self::VIEW_SLIDE,"id=$slideID");
					$responseText = __("Slide copied to current Slider, redirecting...",REVSLIDER_TEXTDOMAIN);
					self::ajaxResponseSuccessRedirect($responseText,$urlRedirect);
				break;
				case "update_slide":
				
					$slide->updateSlideFromData($data);
					self::ajaxResponseSuccess(__("Slide updated",REVSLIDER_TEXTDOMAIN));
				break;
				case "update_static_slide":
					$slide->updateStaticSlideFromData($data);
					self::ajaxResponseSuccess(__("Static Global Layers updated",REVSLIDER_TEXTDOMAIN));
				break;
				case "delete_slide":
				case "delete_slide_stay":
					$isPost = $slide->deleteSlideFromData($data);
					if($isPost)
						$message = __("Post deleted",REVSLIDER_TEXTDOMAIN);
					else
						$message = __("Slide deleted",REVSLIDER_TEXTDOMAIN);

					$sliderID = RevSliderFunctions::getVal($data, "sliderID");
					if($action == 'delete_slide_stay'){
						self::ajaxResponseSuccess($message);
					}else{
						self::ajaxResponseSuccessRedirect($message, self::getViewUrl(self::VIEW_SLIDE,"id=new&slider=$sliderID"));
					}
				break;
				case "duplicate_slide":
				case "duplicate_slide_stay":
					$return = $slider->duplicateSlideFromData($data);
					if($action == 'duplicate_slide_stay'){
						self::ajaxResponseSuccess(__("Slide duplicated",REVSLIDER_TEXTDOMAIN), array('id' => $return[1]));
					}else{
						self::ajaxResponseSuccessRedirect(__("Slide duplicated",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDE,"id=new&slider=".$return[0]));
					}
				break;
				case "copy_move_slide":
				case "copy_move_slide_stay":
					$sliderID = $slider->copyMoveSlideFromData($data);
					if($action == 'copy_move_slide_stay'){
						self::ajaxResponseSuccess(__("Success!",REVSLIDER_TEXTDOMAIN));
					}else{
						self::ajaxResponseSuccessRedirect(__("Success! Refreshing page...",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDE,"id=new&slider=$sliderID"));
					}
				break;
				case "add_slide_to_template":
					$template = new RevSliderTemplate();
					if(!isset($data['slideID']) || intval($data['slideID']) == 0){
						RevSliderFunctions::throwError(__('No valid Slide ID given', REVSLIDER_TEXTDOMAIN));
						exit;
					}
					if(!isset($data['title']) || strlen(trim($data['title'])) < 3){
						RevSliderFunctions::throwError(__('No valid title given', REVSLIDER_TEXTDOMAIN));
						exit;
					}
					if(!isset($data['settings']) || !isset($data['settings']['width']) || !isset($data['settings']['height'])){
						RevSliderFunctions::throwError(__('No valid title given', REVSLIDER_TEXTDOMAIN));
						exit;
					}
					
					$return = $template->copySlideToTemplates($data['slideID'], $data['title'], $data['settings']);
					
					if($return == false){
						RevSliderFunctions::throwError(__('Could not save Slide as Template', REVSLIDER_TEXTDOMAIN));
						exit;
					}
					
					//get HTML for template section
					ob_start();
					
					$rs_disable_template_script = true; //disable the script output of template selector file
					
					include(RS_PLUGIN_PATH.'admin/views/templates/template-selector.php');
					
					$html = ob_get_contents();
					
					ob_clean();
					ob_end_clean();
					
					self::ajaxResponseSuccess(__('Slide added to Templates', REVSLIDER_TEXTDOMAIN),array('HTML' => $html));
					exit;
				break;
				break;
				case "get_static_css":
					$contentCSS = $operations->getStaticCss();
					self::ajaxResponseData($contentCSS);
				break;
				case "get_dynamic_css":
					$contentCSS = $operations->getDynamicCss();
					self::ajaxResponseData($contentCSS);
				break;
				case "insert_captions_css":
					
					$arrCaptions = $operations->insertCaptionsContentData($data);
					
					if($arrCaptions !== false){
						$db = new RevSliderDB();
						$styles = $db->fetch(RevSliderGlobals::$table_css);
						$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
						$styles = RevSliderCssParser::compress_css($styles);
						$custom_css = RevSliderOperations::getStaticCss();
						$custom_css = RevSliderCssParser::compress_css($custom_css);
						
						$arrCSS = $operations->getCaptionsContentArray();
						$arrCssStyles = RevSliderFunctions::jsonEncodeForClientSide($arrCSS);
						$arrCssStyles = $arrCSS;
						
						self::ajaxResponseSuccess(__("CSS saved",REVSLIDER_TEXTDOMAIN),array("arrCaptions"=>$arrCaptions,'compressed_css'=>$styles.$custom_css,'initstyles'=>$arrCssStyles));
					}
					
					RevSliderFunctions::throwError(__('CSS could not be saved', REVSLIDER_TEXTDOMAIN));
					exit();
				break;
				case "update_captions_css":
					
					$arrCaptions = $operations->updateCaptionsContentData($data);
					
					if($arrCaptions !== false){
						$db = new RevSliderDB();
						$styles = $db->fetch(RevSliderGlobals::$table_css);
						$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
						$styles = RevSliderCssParser::compress_css($styles);
						$custom_css = RevSliderOperations::getStaticCss();
						$custom_css = RevSliderCssParser::compress_css($custom_css);
						
						$arrCSS = $operations->getCaptionsContentArray();
						$arrCssStyles = RevSliderFunctions::jsonEncodeForClientSide($arrCSS);
						$arrCssStyles = $arrCSS;
						
						self::ajaxResponseSuccess(__("CSS saved",REVSLIDER_TEXTDOMAIN),array("arrCaptions"=>$arrCaptions,'compressed_css'=>$styles.$custom_css,'initstyles'=>$arrCssStyles));
					}
					
					RevSliderFunctions::throwError(__('CSS could not be saved', REVSLIDER_TEXTDOMAIN));
					exit();
				break;
				case "update_captions_advanced_css":
					
					$arrCaptions = $operations->updateAdvancedCssData($data);
					if($arrCaptions !== false){
						$db = new RevSliderDB();
						$styles = $db->fetch(RevSliderGlobals::$table_css);
						$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
						$styles = RevSliderCssParser::compress_css($styles);
						$custom_css = RevSliderOperations::getStaticCss();
						$custom_css = RevSliderCssParser::compress_css($custom_css);
						
						$arrCSS = $operations->getCaptionsContentArray();
						$arrCssStyles = RevSliderFunctions::jsonEncodeForClientSide($arrCSS);
						$arrCssStyles = $arrCSS;
						
						self::ajaxResponseSuccess(__("CSS saved",REVSLIDER_TEXTDOMAIN),array("arrCaptions"=>$arrCaptions,'compressed_css'=>$styles.$custom_css,'initstyles'=>$arrCssStyles));
					}
					
					RevSliderFunctions::throwError(__('CSS could not be saved', REVSLIDER_TEXTDOMAIN));
					exit();
				break;
				case "rename_captions_css":
					//rename all captions in all sliders with new handle if success
					$arrCaptions = $operations->renameCaption($data);
					
					$db = new RevSliderDB();
					$styles = $db->fetch(RevSliderGlobals::$table_css);
					$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
					$styles = RevSliderCssParser::compress_css($styles);
					$custom_css = RevSliderOperations::getStaticCss();
					$custom_css = RevSliderCssParser::compress_css($custom_css);
					
					$arrCSS = $operations->getCaptionsContentArray();
					$arrCssStyles = RevSliderFunctions::jsonEncodeForClientSide($arrCSS);
					$arrCssStyles = $arrCSS;
					
					self::ajaxResponseSuccess(__("Class name renamed",REVSLIDER_TEXTDOMAIN),array("arrCaptions"=>$arrCaptions,'compressed_css'=>$styles.$custom_css,'initstyles'=>$arrCssStyles));
				break;
				case "delete_captions_css":
					$arrCaptions = $operations->deleteCaptionsContentData($data);
					
					$db = new RevSliderDB();
					$styles = $db->fetch(RevSliderGlobals::$table_css);
					$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
					$styles = RevSliderCssParser::compress_css($styles);
					$custom_css = RevSliderOperations::getStaticCss();
					$custom_css = RevSliderCssParser::compress_css($custom_css);
					
					$arrCSS = $operations->getCaptionsContentArray();
					$arrCssStyles = RevSliderFunctions::jsonEncodeForClientSide($arrCSS);
					$arrCssStyles = $arrCSS;
					
					self::ajaxResponseSuccess(__("Style deleted!",REVSLIDER_TEXTDOMAIN),array("arrCaptions"=>$arrCaptions,'compressed_css'=>$styles.$custom_css,'initstyles'=>$arrCssStyles));
				break;
				case "update_static_css":
					$staticCss = $operations->updateStaticCss($data);
					
					$db = new RevSliderDB();
					$styles = $db->fetch(RevSliderGlobals::$table_css);
					$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
					$styles = RevSliderCssParser::compress_css($styles);
					$custom_css = RevSliderOperations::getStaticCss();
					$custom_css = RevSliderCssParser::compress_css($custom_css);
					
					self::ajaxResponseSuccess(__("CSS saved",REVSLIDER_TEXTDOMAIN),array("css"=>$staticCss,'compressed_css'=>$styles.$custom_css));
				break;
				case "insert_custom_anim":
					$arrAnims = $operations->insertCustomAnim($data); //$arrCaptions =
					self::ajaxResponseSuccess(__("Animation saved",REVSLIDER_TEXTDOMAIN), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "update_custom_anim":
					$arrAnims = $operations->updateCustomAnim($data);
					self::ajaxResponseSuccess(__("Animation saved",REVSLIDER_TEXTDOMAIN), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "update_custom_anim_name":
					$arrAnims = $operations->updateCustomAnimName($data);
					self::ajaxResponseSuccess(__("Animation saved",REVSLIDER_TEXTDOMAIN), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "delete_custom_anim":
					$arrAnims = $operations->deleteCustomAnim($data);
					self::ajaxResponseSuccess(__("Animation deleted",REVSLIDER_TEXTDOMAIN), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "update_slides_order":
					$slider->updateSlidesOrderFromData($data);
					self::ajaxResponseSuccess(__("Order updated",REVSLIDER_TEXTDOMAIN));
				break;
				case "change_slide_title":
					$slide->updateTitleByID($data);
					self::ajaxResponseSuccess(__('Title updated',REVSLIDER_TEXTDOMAIN));
				break;
				case "change_slide_image":
					$slide->updateSlideImageFromData($data);
					$sliderID = RevSliderFunctions::getVal($data, "slider_id");
					self::ajaxResponseSuccessRedirect(__("Slide changed",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDE,"id=new&slider=$sliderID"));
				break;
				case "preview_slide":
					$operations->putSlidePreviewByData($data);
				break;
				case "preview_slider":
					$sliderID = RevSliderFunctions::getPostGetVariable("sliderid");
					$do_markup = RevSliderFunctions::getPostGetVariable("only_markup");

					if($do_markup == 'true')
						$operations->previewOutputMarkup($sliderID);
					else
						$operations->previewOutput($sliderID);
				break;
				case "toggle_slide_state":
					$currentState = $slide->toggleSlideStatFromData($data);
					self::ajaxResponseData(array("state"=>$currentState));
				break;
				case "toggle_hero_slide":
					$currentHero = $slider->setHeroSlide($data);
					self::ajaxResponseSuccess(__('Slide is now the new active Hero Slide', REVSLIDER_TEXTDOMAIN));
				break;
				case "slide_lang_operation":
					$responseData = $slide->doSlideLangOperation($data);
					self::ajaxResponseData($responseData);
				break;
				case "update_general_settings":
					$operations->updateGeneralSettings($data);
					self::ajaxResponseSuccess(__("General settings updated",REVSLIDER_TEXTDOMAIN));
				break;
				case "update_posts_sortby":
					$slider->updatePostsSortbyFromData($data);
					self::ajaxResponseSuccess(__("Sortby updated",REVSLIDER_TEXTDOMAIN));
				break;
				case "replace_image_urls":
					$slider->replaceImageUrlsFromData($data);
					self::ajaxResponseSuccess(__("Image urls replaced",REVSLIDER_TEXTDOMAIN));
				break;
				case "reset_slide_settings":
					$slider->resetSlideSettings($data);
					self::ajaxResponseSuccess(__("Settings in all Slides changed",REVSLIDER_TEXTDOMAIN));
				break;
				case "activate_purchase_code":
					$result = false;
					if(!empty($data['username']) && !empty($data['api_key']) && !empty($data['code'])){
						$result = $operations->checkPurchaseVerification($data);
					}else{
						RevSliderFunctions::throwError(__('The API key, the Purchase Code and the Username need to be set!', REVSLIDER_TEXTDOMAIN));
						exit();
					}

					if($result){
						self::ajaxResponseSuccessRedirect(__("Purchase Code Successfully Activated",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDERS));
					}else{
						RevSliderFunctions::throwError(__('Purchase Code is invalid', REVSLIDER_TEXTDOMAIN));
					}
				break;
				case "deactivate_purchase_code":
					$result = $operations->doPurchaseDeactivation($data);

					if($result){
						self::ajaxResponseSuccessRedirect(__("Successfully removed validation",REVSLIDER_TEXTDOMAIN), self::getViewUrl(self::VIEW_SLIDERS));
					}else{
						RevSliderFunctions::throwError(__('Could not remove Validation!', REVSLIDER_TEXTDOMAIN));
					}
				break;
				case 'dismiss_notice':
					update_option('revslider-valid-notice', 'false');
					self::ajaxResponseSuccess(__(".",REVSLIDER_TEXTDOMAIN));
				break;
				case 'dismiss_dynamic_notice':
					$notices_discarded = get_option('revslider-notices-dc', array());
					$notices_discarded[] = esc_attr(trim($data['id']));
					update_option('revslider-notices-dc', $notices_discarded);
					
					self::ajaxResponseSuccess(__(".",REVSLIDER_TEXTDOMAIN));
				break;
				case 'toggle_favorite':
					if(isset($data['id']) && intval($data['id']) > 0){
						$return = self::toggle_favorite_by_id($data['id']);
						if($return === true){
							self::ajaxResponseSuccess(__('Setting Changed!', REVSLIDER_TEXTDOMAIN));
						}else{
							$error = $return;
						}	
					}else{
						$error = __('No ID given', REVSLIDER_TEXTDOMAIN);
					}
					self::ajaxResponseError($error);
				break;
				case "subscribe_to_newsletter":
					if(isset($data['email']) && !empty($data['email'])){
						$return = ThemePunch_Newsletter::subscribe($data['email']);
						
						if($return !== false){
							if(!isset($return['status']) || $return['status'] === 'error'){
								$error = (isset($return['message']) && !empty($return['message'])) ? $return['message'] : __('Invalid Email', REVSLIDER_TEXTDOMAIN);
								self::ajaxResponseError($error);
							}else{
								self::ajaxResponseSuccess(__("Success! Please check your Emails to finish the subscription", REVSLIDER_TEXTDOMAIN), $return);
							}
						}else{
							self::ajaxResponseError(__('Invalid Email/Could not connect to the Newsletter server', REVSLIDER_TEXTDOMAIN));
						}	
					}else{
						self::ajaxResponseError(__('No Email given', REVSLIDER_TEXTDOMAIN));
					}
				break;
				case "unsubscribe_to_newsletter":
					if(isset($data['email']) && !empty($data['email'])){
						$return = ThemePunch_Newsletter::unsubscribe($data['email']);
						
						if($return !== false){
							if(!isset($return['status']) || $return['status'] === 'error'){
								$error = (isset($return['message']) && !empty($return['message'])) ? $return['message'] : __('Invalid Email', REVSLIDER_TEXTDOMAIN);
								self::ajaxResponseError($error);
							}else{
								self::ajaxResponseSuccess(__("Success! Please check your Emails to finish the process", REVSLIDER_TEXTDOMAIN), $return);
							}
						}else{
							self::ajaxResponseError(__('Invalid Email/Could not connect to the Newsletter server', REVSLIDER_TEXTDOMAIN));
						}	
					}else{
						self::ajaxResponseError(__('No Email given', REVSLIDER_TEXTDOMAIN));
					}
				break;
				case 'change_specific_navigation':
					$nav = new RevSliderNavigation();
					
					$found = false;
					$navigations = $nav->get_all_navigations();
					foreach($navigations as $navig){
						if($data['id'] == $navig['id']){
							$found = true;
							break;
						}
					}
					if($found){
						$nav->create_update_navigation($data, $data['id']);
					}else{
						$nav->create_update_navigation($data);
					}
					
					self::ajaxResponseSuccess(__('Navigation saved/updated', REVSLIDER_TEXTDOMAIN), array('navs' => $nav->get_all_navigations()));
					
				break;
				case 'change_navigations':
					$nav = new RevSliderNavigation();
					
					$nav->create_update_full_navigation($data);
					
					self::ajaxResponseSuccess(__('Navigations updated', REVSLIDER_TEXTDOMAIN), array('navs' => $nav->get_all_navigations()));
				break;
				case 'delete_navigation':
					$nav = new RevSliderNavigation();
					
					if(isset($data) && intval($data) > 0){
						$return = $nav->delete_navigation($data);
						
						if($return !== true){
							self::ajaxResponseError($return);
						}else{
							self::ajaxResponseSuccess(__('Navigation deleted', REVSLIDER_TEXTDOMAIN), array('navs' => $nav->get_all_navigations()));
						}
					}
					
					self::ajaxResponseError(__('Wrong ID given', REVSLIDER_TEXTDOMAIN));
				break;
				case "get_facebook_photosets":
					if(!empty($data['url'])){
						$facebook = new RevSliderFacebook();
						$return = $facebook->get_photo_set_photos_options($data['url'],$data['album'],$data['app_id'],$data['app_secret']);
						if(!empty($return)){
							self::ajaxResponseSuccess(__('Successfully fetched Facebook albums', REVSLIDER_TEXTDOMAIN), array('html'=>implode(' ', $return)));
						}
						else{
							$error = __('Could not fetch Facebook albums', REVSLIDER_TEXTDOMAIN);
							self::ajaxResponseError($error);	
						}
					}
					else {
						self::ajaxResponseSuccess(__('Cleared Albums', REVSLIDER_TEXTDOMAIN), array('html'=>implode(' ', $return)));
					}
				break;
				case "get_flickr_photosets":
					if(!empty($data['url']) && !empty($data['key'])){
						$flickr = new RevSliderFlickr($data['key']);
						$user_id = $flickr->get_user_from_url($data['url']);
						$return = $flickr->get_photo_sets($user_id,$data['count'],$data['set']);
						if(!empty($return)){
							self::ajaxResponseSuccess(__('Successfully fetched flickr photosets', REVSLIDER_TEXTDOMAIN), array("data"=>array('html'=>implode(' ', $return))));
						}
						else{
							$error = __('Could not fetch flickr photosets', REVSLIDER_TEXTDOMAIN);
							self::ajaxResponseError($error);
						}
					}
					else {
						if(empty($data['url']) && empty($data['key'])){
							self::ajaxResponseSuccess(__('Cleared Photosets', REVSLIDER_TEXTDOMAIN), array('html'=>implode(' ', $return)));
						}
						elseif(empty($data['url'])){
							$error = __('No User URL - Could not fetch flickr photosets', REVSLIDER_TEXTDOMAIN);
							self::ajaxResponseError($error);
						}
						else{
							$error = __('No API KEY - Could not fetch flickr photosets', REVSLIDER_TEXTDOMAIN);
							self::ajaxResponseError($error);
						}
					}
				break;
				case "get_youtube_playlists":
					if(!empty($data['id'])){
						$youtube = new RevSliderYoutube(trim($data['api']),trim($data['id']));
						$return = $youtube->get_playlist_options($data['playlist']);
						self::ajaxResponseSuccess(__('Successfully fetched YouTube playlists', REVSLIDER_TEXTDOMAIN), array("data"=>array('html'=>implode(' ', $return))));
					}
					else {
						$error = __('Could not fetch YouTube playlists', REVSLIDER_TEXTDOMAIN);
						self::ajaxResponseError($error);
					}
				break;
				case 'rs_get_store_information': 
					global $wp_version;
					
					$api_key = get_option('revslider-api-key', '');
					$username = get_option('revslider-username', '');
					$code = get_option('revslider-code', '');
					$shop_version = RevSliderTemplate::SHOP_VERSION;
					
					$validated = get_option('revslider-valid', 'false');
					if($validated == 'false'){
						$api_key = '';
						$username = '';
						$code = '';
					}
					
					$rattr = array(
						'api' => urlencode($api_key),
						'username' => urlencode($username),
						'code' => urlencode($code),
						'product' => urlencode('revslider'),
						'shop_version' => urlencode($shop_version),
						'version' => urlencode(RevSliderGlobals::SLIDER_REVISION)
					);
					
					$request = wp_remote_post('http://templates.themepunch.tools/revslider/store.php', array(
						'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
						'body' => $rattr
					));
					
					$response = '';
					
					if(!is_wp_error($request)) {
						$response = json_decode(@$request['body'], true);
					}
					
					self::ajaxResponseData(array("data"=>$response));
				break;
				default:
					self::ajaxResponseError("wrong ajax action: $action");
				break;
			}

		}
		catch(Exception $e){

			$message = $e->getMessage();
			if($action == "preview_slide" || $action == "preview_slider"){
				echo $message;
				exit();
			}

			self::ajaxResponseError($message);
		}

		//it's an ajax action, so exit
		self::ajaxResponseError("No response output on $action action. please check with the developer.");
		exit();
	}
	
	/**
	 * Set the option to add a delay to the revslider javascript output
	 */
	public static function rev_set_js_delay($do_delay){
		return '300';
	}
	
	/**
	 * onAjax action handler
	 */
	public static function onFrontAjaxAction(){
		$db = new RevSliderDB();
		$slider = new RevSlider();
		$slide = new RevSlide();
		$operations = new RevSliderOperations();
		
		$token = self::getPostVar("token", false);
		
		//verify the token
		$isVerified = wp_verify_nonce($token, 'RevSlider_Front');
		
		$error = false;
		if($isVerified){
			$data = self::getPostVar('data', false);
			switch(self::getPostVar('client_action', false)){
				case 'get_slider_html':
					$id = intval(self::getPostVar('id', 0));
					if($id > 0){
						$html = '';
						add_filter('revslider_add_js_delay', array('RevSliderAdmin', 'rev_set_js_delay'));
						ob_start();
						$slider_class = RevSliderOutput::putSlider($id);
						$html = ob_get_contents();
						
						//add styling
						$custom_css = RevSliderOperations::getStaticCss();
						$custom_css = RevSliderCssParser::compress_css($custom_css);
						$styles = $db->fetch(RevSliderGlobals::$table_css);
						$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
						$styles = RevSliderCssParser::compress_css($styles);
						
						$html .= '<style type="text/css">'.$custom_css.'</style>';
						$html .= '<style type="text/css">'.$styles.'</style>';
						
						ob_clean();
						ob_end_clean();
						
						$result = (!empty($slider_class) && $html !== '') ? true : false;
						
						if(!$result){
							$error = __('Slider not found', REVSLIDER_TEXTDOMAIN);
						}else{
							
							if($html !== false){
								self::ajaxResponseData($html);
							}else{
								$error = __('Slider not found', REVSLIDER_TEXTDOMAIN);
							}
						}
					}else{
						$error = __('No Data Received', REVSLIDER_TEXTDOMAIN);
					}
				break;
			}
			
		}else{
			$error = true;
		}
		
		if($error !== false){
			$showError = __('Loading Error', REVSLIDER_TEXTDOMAIN);
			if($error !== true)
				$showError = __('Loading Error: ', REVSLIDER_TEXTDOMAIN).$error;
			
			self::ajaxResponseError($showError, false);
		}
		exit();
	}
	
}
?>