<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class RevSliderTemplate {
	
	private $templates_url		= 'http://templates.themepunch.tools/';
	private $templates_list		= 'revslider/get-list.php';
	private $templates_download	= 'revslider/download.php';
	
	private $templates_server_path	= '/revslider/images/';
	private $templates_path			= '/revslider/templates/';
	private $templates_path_plugin	= 'admin/assets/imports/';
	
	const SHOP_VERSION				= '1.0.0';
	
	
	
	/**
	 * Download template by UID (also validates if download is legal)
	 * @since: 5.0.5
	 */
	public function _download_template($uid){
		global $wp_version;
		
		$uid = esc_attr($uid);
		
		$api_key = get_option('revslider-api-key', '');
		$username = get_option('revslider-username', '');
		$code = get_option('revslider-code', '');
		$shop_version = self::SHOP_VERSION;
		
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
			'shop_version' => urlencode($shop_version),
			'version' => urlencode(RevSliderGlobals::SLIDER_REVISION),
			'uid' => urlencode($uid)
		);
		
		$upload_dir = wp_upload_dir(); // Set upload folder
		// Check folder permission and define file location
		if(wp_mkdir_p( $upload_dir['basedir'].$this->templates_path ) ) { //check here to not flood the server
			$request = wp_remote_post($this->templates_url.$this->templates_download, array(
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
				'body' => $rattr
			));
			
			if(!is_wp_error($request)) {
				if($response = $request['body']) {
					if($response !== 'invalid'){
						//add stream as a zip file
						$file = $upload_dir['basedir']. $this->templates_path . '/' . $uid.'.zip';
						@mkdir(dirname($file));
						$ret = @file_put_contents( $file, $response );
						if($ret !== false){
							//return $file so it can be processed. We have now downloaded it into a zip file
							return $file;
						}else{//else, print that file could not be written
							return array('error' => __('Can\'t write the file into the uploads folder of WordPress, please change permissions and try again!', REVSLIDER_TEXTDOMAIN));
						}
					}
				}
			}//else, check for error and print it to customer
		}else{
			return array('error' => __('Can\'t write into the uploads folder of WordPress, please change permissions and try again!', REVSLIDER_TEXTDOMAIN));
		}
		
		return false;
	}
	
	
	/**
	 * Delete the Template file
	 * @since: 5.0.5
	 */
	public function _delete_template($uid){
		$uid = esc_attr($uid);
		
		$upload_dir = wp_upload_dir(); // Set upload folder
		
		// Check folder permission and define file location
		if( wp_mkdir_p( $upload_dir['basedir'].$this->templates_path ) ) {
			$file = $upload_dir['basedir']. $this->templates_path . '/' . $uid.'.zip';
			
			if(file_exists($file)){
				//delete file
				return unlink($file);
			}
		}
		
		return false;
	}
	
	
	/**
	 * Get the Templatelist from servers
	 * @since: 5.0.5
	 */
	public function _get_template_list($force = false){
		global $wp_version;
		
		$last_check = get_option('revslider-templates-check');
		
		if($last_check == false){ //first time called
			$last_check = 172801;
			update_option('revslider-templates-check',  time());
		}
		
		// Get latest Templates
		if(time() - $last_check > 345600 || $force == true){ //4 days
			
			update_option('revslider-templates-check',  time());
			
			$validated = get_option('revslider-valid', 'false');
			
			$api_key = get_option('revslider-api-key', '');
			$username = get_option('revslider-username', '');
			$code = get_option('revslider-code', '');
			$shop_version = self::SHOP_VERSION;
			
			if($validated == 'false'){
				$api_key = '';
				$username = '';
				$code = '';
			}
			
			
			$rattr = array(
				'api' => urlencode($api_key),
				'username' => urlencode($username),
				'code' => urlencode($code),
				'shop_version' => urlencode($shop_version),
				'version' => urlencode(RevSliderGlobals::SLIDER_REVISION)
			);
			
			$request = wp_remote_post($this->templates_url.$this->templates_list, array(
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
				'body' => $rattr
			));
			
			if(!is_wp_error($request)) {
				if($response = maybe_unserialize($request['body'])) {
					
					$templates = json_decode($response, true);
					
					if(is_array($templates)) {
						update_option('rs-templates-new', $templates);
					}
				}
			}
			
			$this->update_template_list();
		}
	}
	
	
	/**
	 * Update the Templatelist, move rs-templates-new into rs-templates
	 * @since: 5.0.5
	 */
	private function update_template_list(){
		
		$new = get_option('rs-templates-new', false);
		$cur = get_option('rs-templates', array());
		
		if($new !== false && !empty($new) && is_array($new)){
			if(empty($cur)){
				$cur = $new;
			}else{
				if(isset($new['slider']) && is_array($new['slider'])){
					foreach($new['slider'] as $n){
						$found = false;
						if(isset($cur['slider']) && is_array($cur['slider'])){
							foreach($cur['slider'] as $ck => $c){
								if($c['uid'] == $n['uid']){
									if(version_compare($c['version'], $n['version'], '<')){
										$n['is_new'] = true;
										$n['push_image'] = true; //push to get new image and replace
									}
									if(isset($c['is_new'])) $n['is_new'] = true; //is_new will stay until update is done
									
									$n['exists'] = true; //if this flag is not set here, the template will be removed from the list
									
									$cur['slider'][$ck] = $n;
									$found = true;
									break;
								}
							}
						}
						
						if(!$found){
							$n['exists'] = true;
							$cur['slider'][] = $n;
						}
						
					}
					
					foreach($cur['slider'] as $ck => $c){ //remove no longer available Slider
						if(!isset($c['exists'])){
							unset($cur['slider'][$ck]);
						}else{
							unset($cur['slider'][$ck]['exists']);
						}
					}
					
					$cur['slides'] = $new['slides']; // push always all slides
				}
			}
		
			update_option('rs-templates', $cur);
			update_option('rs-templates-new', false);
			
			$this->_update_images();
		}
	}
	
	
	/**
	 * Remove the is_new attribute which shows the "update available" button
	 * @since: 5.0.5
	 */
	public function remove_is_new($uid){
		$cur = get_option('rs-templates', array());
		
		if(isset($cur['slider']) && is_array($cur['slider'])){
			foreach($cur['slider'] as $ck => $c){
				if($c['uid'] == $uid){
					unset($cur['slider'][$ck]['is_new']);
					break;
				}
			}
		}
		
		update_option('rs-templates', $cur);
		
	}
	
	
	/**
	 * Update the Images get them from Server and check for existance on each image
	 * @since: 5.0.5
	 */
	private function _update_images(){
		$templates = get_option('rs-templates', array());
		
		$reload = array();
		
		if(!empty($templates) && is_array($templates)){
			$upload_dir = wp_upload_dir(); // Set upload folder
			if(!empty($templates['slider']) && is_array($templates['slider'])){
				foreach($templates['slider'] as $key => $temp){
					
					// Check folder permission and define file location
					if( wp_mkdir_p( $upload_dir['basedir'].$this->templates_path ) ) {
						$file = $upload_dir['basedir'] . $this->templates_path . '/' . $temp['img'];
						$file_plugin = RS_PLUGIN_PATH . $this->templates_path_plugin . '/' . $temp['img'];
						
						if((!file_exists($file) && !file_exists($file_plugin)) || isset($temp['push_image'])){
							
							$image_data = @file_get_contents($this->templates_url.$this->templates_server_path.$temp['img']); // Get image data
							if($image_data !== false){
								$reload[$temp['alias']] = true;
								unset($templates['slider'][$key]['push_image']);
								@mkdir(dirname($file));
								@file_put_contents( $file, $image_data );
							}else{//could not connect to server
							}
						}else{//use default image
						}
					}else{//use default images
					}
				}
			}
			if(!empty($templates['slides']) && is_array($templates['slides'])){
				foreach($templates['slides'] as $key => $temp){
					foreach($temp as $k => $tvalues){
					
						// Check folder permission and define file location
						if( wp_mkdir_p( $upload_dir['basedir'].$this->templates_path ) ) {
							$file = $upload_dir['basedir'] . $this->templates_path . '/' . $tvalues['img'];
							$file_plugin = RS_PLUGIN_PATH . $this->templates_path_plugin . '/' . $tvalues['img'];
							
							if((!file_exists($file) && !file_exists($file_plugin)) || isset($reload[$key])){ //update, so load again
								$image_data = @file_get_contents($this->templates_url.$this->templates_server_path.$tvalues['img']); // Get image data
								
								if($image_data !== false){
									@mkdir(dirname($file));
									@file_put_contents( $file, $image_data );
								}else{//could not connect to server
								}
							}else{//use default image
							}
						}else{//use default images
						}
						
					}
				}
			}
		}
		
		update_option('rs-templates', $templates); //remove the push_image
	}
	
	
	/**
	 * Copy a Slide to the Template Slide list
	 * @since: 5.0
	 */
	public function copySlideToTemplates($slide_id, $slide_title, $slide_settings = array()){
		if(intval($slide_id) == 0) return false;
		$slide_title = sanitize_text_field($slide_title);
		if(strlen(trim($slide_title)) < 3) return false;
		
		global $wpdb;
		
		$table_name = RevSliderGlobals::$table_slides;
		
		$duplicate = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %s", $slide_id), ARRAY_A);
		
		if(empty($duplicate)) // slide not found
			return false;
		
		unset($duplicate['id']);
		
		$duplicate['slider_id'] = -1; //-1 sets it to be a template
		$duplicate['slide_order'] = -1;
		
		$params = json_decode($duplicate['params'], true);
		$settings = json_decode($duplicate['settings'], true);
		
		$params['title'] = $slide_title;
		$params['state'] = 'published';
		
		if(isset($slide_settings['width'])) $settings['width'] = intval($slide_settings['width']);
		if(isset($slide_settings['height'])) $settings['height'] = intval($slide_settings['height']);
		
		$duplicate['params'] = json_encode($params);
		$duplicate['settings'] = json_encode($settings);
		
		$response = $wpdb->insert($table_name, $duplicate);
		
		if($response)
			return true;
		
		return false;
	}
	
	
	/**
	 * Get all Template Slides
	 * @since: 5.0
	 */
	public function getTemplateSlides(){
		global $wpdb;
		
		$table_name = RevSliderGlobals::$table_slides;
		
		$templates = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE slider_id = %s", -1), ARRAY_A);
		
		//add default Template Slides here!
		$default = $this->getDefaultTemplateSlides();
		
		$templates = array_merge($templates, $default);
		
		if(!empty($templates)){
			foreach($templates as $key => $template){
				$templates[$key]['params'] = json_decode($template['params'], true);
				$templates[$key]['layers'] = json_decode($template['layers'], true);
				$templates[$key]['settings'] = json_decode($template['settings'], true);
			}
		}
		
		return $templates;
	}
	
	
	/**
	 * Add default Template Slides that can't be deleted for example. Authors can add their own Slides here through Filter
	 * @since: 5.0
	 */
	private function getDefaultTemplateSlides(){
		$templates = array();
		
		$templates = apply_filters('revslider_set_template_slides', $templates);
		
		return $templates;
	}
	
	
	/**
	 * get default ThemePunch default Slides
	 * @since: 5.0
	 */
	public function getThemePunchTemplateSlides($sliders = false){
		global $wpdb;
		
		$templates = array();
		
		$slide_defaults = array();//
		
		if($sliders == false){
			$sliders = $this->getThemePunchTemplateSliders();
		}
		$table_name = RevSliderGlobals::$table_slides;
		
		if(!empty($sliders)){
			foreach($sliders as $slider){
				$slides = $this->getThemePunchTemplateDefaultSlides($slider['alias']);
				
				if(!isset($slider['installed'])){
					$templates = array_merge($templates, $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE slider_id = %s", $slider['id']), ARRAY_A));
				}else{
					$templates = array_merge($templates, $slides);
				}
				if(!empty($templates)){
					foreach($templates as $key => $tmpl){
						if(isset($slides[$key])) $templates[$key]['img'] = $slides[$key]['img'];
					}
				}
				
				/*else{
					$templates = array_merge($templates, array($slide_defaults[$slider['alias']]));
				}*/
			}
		}
		
		if(!empty($templates)){
			foreach($templates as $key => $template){
				if(!isset($template['installed'])){
					$template['params'] = (isset($template['params'])) ? $template['params'] : '';
					$template['layers'] = (isset($template['layers'])) ? $template['layers'] : '';
					$template['settings'] = (isset($template['settings'])) ? $template['settings'] : '';
					
					$templates[$key]['params'] = json_decode($template['params'], true);
					$templates[$key]['layers'] = json_decode($template['layers'], true);
					$templates[$key]['settings'] = json_decode($template['settings'], true);
				}
			}
		}
		
		return $templates;
	}
	
	
	/**
	 * get default ThemePunch default Slides
	 * @since: 5.0
	 */
	public function getThemePunchTemplateDefaultSlides($slider_alias){
		
		$templates = get_option('rs-templates', array());
		$slides = (isset($templates['slides']) && !empty($templates['slides'])) ? $templates['slides'] : array();
		
		return (isset($slides[$slider_alias])) ? $slides[$slider_alias] : array();
	}
	
	
	/**
	 * Get default Template Sliders
	 * @since: 5.0
	 */
	public function getDefaultTemplateSliders(){
		global $wpdb;
		
		$sliders = array();
		$check = array();
		
		$table_name = RevSliderGlobals::$table_sliders;
		
		//add themepunch default Sliders here
		$check = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'template'", ARRAY_A);
		
		$sliders = apply_filters('revslider_set_template_sliders', $sliders);
		
		/**
		 * Example		 
			$sliders['Slider Pack Name'] = array(
				array('title' => 'PJ Slider 1', 'alias' => 'pjslider1', 'width' => 1400, 'height' => 868, 'zip' => 'exwebproduct.zip', 'uid' => 'bde6d50c2f73f8086708878cf227c82b', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/exwebproduct.jpg'),
				array('title' => 'PJ Classic Slider', 'alias' => 'pjclassicslider', 'width' => 1240, 'height' => 600, 'zip' => 'classicslider.zip', 'uid' => 'a0d6a9248c9066b404ba0f1cdadc5cf2', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider.jpg')
			);
		 **/
		
		if(!empty($check) && !empty($sliders)){
			foreach($sliders as $key => $the_sliders){
				foreach($the_sliders as $skey => $slider){
					foreach($check as $ikey => $installed){
						if($installed['alias'] == $slider['alias']){
							$img = $slider['img'];
							$sliders[$key][$skey] = $installed;
							
							$sliders[$key][$skey]['img'] = $img;
							
							$sliders[$key]['version'] = (isset($slider['version'])) ? $slider['version'] : '';
							if(isset($slider['is_new'])) $sliders[$key]['is_new'] = true;
							
							$preview = (isset($slider['preview'])) ? $slider['preview'] : false;
							if($preview !== false) $sliders[$key]['preview'] = $preview;
							
							break;
						}
					}
				}
			}
		}
		
		return $sliders;
	}
	
	
	/**
	 * get default ThemePunch default Sliders
	 * @since: 5.0
	 */
	public function getThemePunchTemplateSliders(){
		global $wpdb;
		
		$sliders = array();
		
		$table_name = RevSliderGlobals::$table_sliders;
		
		//add themepunch default Sliders here
		$sliders = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'template'", ARRAY_A);
		
		$defaults = get_option('rs-templates', array());
		$defaults = (isset($defaults['slider'])) ? $defaults['slider'] : array();
		
		if(!empty($sliders)){
			if(!empty($defaults)){
				foreach($defaults as $key => $slider){
					foreach($sliders as $ikey => $installed){
						if($installed['alias'] == $slider['alias']){
							$img = $slider['img'];
							$preview = (isset($slider['preview'])) ? $slider['preview'] : false;
							$defaults[$key] = $installed;
							
							$defaults[$key]['img'] = $img;
							$defaults[$key]['version'] = $slider['version'];
							$defaults[$key]['cat'] = $slider['cat'];
							$defaults[$key]['filter'] = $slider['filter'];
							
							if(isset($slider['is_new'])){
								$defaults[$key]['is_new'] = true;
								$defaults[$key]['zip'] = $slider['zip'];
								$defaults[$key]['width'] = $slider['width'];
								$defaults[$key]['height'] = $slider['height'];
								$defaults[$key]['uid'] = $slider['uid'];
							}
							
							if($preview !== false) $defaults[$key]['preview'] = $preview;
							break;
						}
					}
				}
			}
		}
		
		return $defaults;
		
	}
	
	
	/**
	 * check if image was uploaded, if yes, return path or url
	 * @since: 5.0.5
	 */
	public function _check_file_path($image, $url = false){
		$upload_dir = wp_upload_dir(); // Set upload folder
		$file = $upload_dir['basedir'] . $this->templates_path . '/' . $image;
		$file_plugin = RS_PLUGIN_PATH . $this->templates_path_plugin . '/' . $image;
		
		if(file_exists($file)){ //downloaded image first, for update reasons
			if($url){
				$image = $upload_dir['baseurl'] . $this->templates_path . '/' . $image;
			}else{
				$image = $upload_dir['basedir'] . $this->templates_path . '/' . $image; //server path
			}
		}elseif(file_exists($file_plugin)){
			if($url){
				$image = RS_PLUGIN_URL . $this->templates_path_plugin . '/' . $image;
			}else{
				$image = RS_PLUGIN_URL . $this->templates_path_plugin . '/' . $image;
				//$image = $file_plugin; //server path
			}
		}else{
			
			//jump to a default image here?
			$image = false;
		}
		
		return $image;
	}
	
	/**
	 * output markup for the import template, the zip was not yet improted
	 * @since: 5.0
	 */
	public function write_import_template_markup($template){
		
		$template['img'] = $this->_check_file_path($template['img'], true);
		if($template['img'] == ''){
			//set default image
		}
		?>
		<div data-src="<?php echo $template['img']; ?>" class="template_slider_item_import"
			data-gridwidth="<?php echo $template['width']; ?>"
			data-gridheight="<?php echo $template['height']; ?>"
			data-zipname="<?php echo $template['zip']; ?>"
			data-uid="<?php echo $template['uid']; ?>"
			>
			<!--div class="template_title"><?php echo (isset($template['title'])) ? $template['title'] : ''; ?></div-->
			<div class="not-imported-overlay"></div>
			<div style="position:absolute;top:10px;right:10px;width:35px;text-align:right;z-index:2">				
				<div class="icon-install_slider"></div>
			</div>
			
		</div>
		<div style="position:absolute;top:10px;right:50px;width:35px;text-align:right;z-index:2">
			<?php if(isset($template['preview']) && $template['preview'] !== ''){ ?>
			<a class="icon-preview_slider" href="<?php echo esc_attr($template['preview']); ?>" target="_blank"></a>
			<?php } ?>
		</div>
		<?php
	}
	
	
	/**
	 * output markup for the import template, the zip was not yet imported
	 * @since: 5.0
	 */
	public function write_import_template_markup_slide($template){
		
		$template['img'] = $this->_check_file_path($template['img'], true);
		
		if($template['img'] == ''){
			//set default image
		}
		
		?>
		<div class="template_slide_item_import">
			<div class="template_slide_item_img" 
				data-src="<?php echo $template['img']; ?>" 
				data-gridwidth="<?php echo $template['width']; ?>"
				data-gridheight="<?php echo $template['height']; ?>"
				data-zipname="<?php echo $template['zip']; ?>"
				data-uid="<?php echo $template['uid']; ?>"
				data-slidenumber="<?php echo $template['number']; ?>"
			>
				<div class="not-imported-overlay"></div>
			</div>
			<div style="position:absolute;top:10px;right:10px;width:100%;text-align:right;z-index:2">
				<div class="icon-install_slider"></div>
			</div>
			<div class="template_title"><?php echo (isset($template['title'])) ? $template['title'] : ''; ?></div>
		</div>

		<?php
	}
	
	
	/**
	 * output markup for template
	 * @since: 5.0
	 */
	public function write_template_markup($template, $slider_id = false){
		$params = $template['params'];
		$settings = $template['settings'];
		$slide_id = $template['id'];
		$title = str_replace("'", "", RevSliderBase::getVar($params, 'title', 'Slide'));
		if($slider_id !== false) $title = ''; //remove Title if Slider
		
		$width = RevSliderBase::getVar($settings, "width", 1240);
		$height = RevSliderBase::getVar($settings, "height", 868);
		
		$bgType = RevSliderBase::getVar($params, "background_type","transparent");
		$bgColor = RevSliderBase::getVar($params, "slide_bg_color","transparent");

		$bgFit = RevSliderBase::getVar($params, "bg_fit","cover");
		$bgFitX = intval(RevSliderBase::getVar($params, "bg_fit_x","100"));
		$bgFitY = intval(RevSliderBase::getVar($params, "bg_fit_y","100"));

		$bgPosition = RevSliderBase::getVar($params, "bg_position","center center");
		$bgPositionX = intval(RevSliderBase::getVar($params, "bg_position_x","0"));
		$bgPositionY = intval(RevSliderBase::getVar($params, "bg_position_y","0"));

		$bgRepeat = RevSliderBase::getVar($params, "bg_repeat","no-repeat");

		$bgStyle = ' ';
		if($bgFit == 'percentage'){
			if(intval($bgFitY) == 0 || intval($bgFitX) == 0){
				$bgStyle .= "background-size: cover;";
			}else{
				$bgStyle .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
			}
		}else{
			$bgStyle .= "background-size: ".$bgFit.";";
		}
		if($bgPosition == 'percentage'){
			$bgStyle .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
		}else{
			$bgStyle .= "background-position: ".$bgPosition.";";
		}
		$bgStyle .= "background-repeat: ".$bgRepeat.";";
		
		
		if(isset($template['img'])){
			$thumb = $this->_check_file_path($template['img'], true);
		}else{
			$imageID = RevSliderBase::getVar($params, "image_id");
			if(empty($imageID)){
				$thumb = RevSliderBase::getVar($params, "image");
		
				$imgID = RevSliderBase::get_image_id_by_url($thumb);
				if($imgID !== false){
					$thumb = RevSliderFunctionsWP::getUrlAttachmentImage($imgID, RevSliderFunctionsWP::THUMB_MEDIUM);
				}
			}else{
				$thumb = RevSliderFunctionsWP::getUrlAttachmentImage($imageID,RevSliderFunctionsWP::THUMB_MEDIUM);
			}
		
			if($thumb == '') $thumb = RevSliderBase::getVar($params, "image");
		}
		

		$bg_fullstyle ='';
		$bg_extraClass='';
		$data_urlImageForView='';

		if(isset($template['img'])){
			$data_urlImageForView = 'data-src="'.$thumb.'"';
		}else{
			if($bgType == 'image' || $bgType == 'vimeo' || $bgType == 'youtube' || $bgType == 'html5') {
				$data_urlImageForView = 'data-src="'.$thumb.'"';
				$bg_fullstyle =' style="'.$bgStyle.'" ';
			}

			if($bgType=="solid")
				$bg_fullstyle =' style="background-color:'.$bgColor.';" ';
				
			if($bgType=="trans" || $bgType=="transparent")
				$bg_extraClass = 'mini-transparent';
		}
		?>
		<div class="template_slide_single_element" style="display:inline-block">
			<div <?php echo $data_urlImageForView; ?> class="<?php echo ($slider_id !== false) ? 'template_slider_item' : 'template_item'; ?> <?php echo $bg_extraClass; ?>" <?php echo $bg_fullstyle; ?>
				data-gridwidth="<?php echo $width; ?>"
				data-gridheight="<?php echo $height; ?>"
				<?php if($slider_id !== false){ ?>
				data-sliderid="<?php echo $slider_id; ?>"
				<?php }else{ ?>
				data-slideid="<?php echo $slide_id; ?>"
				<?php } ?>
				>
				
				<div class="not-imported-overlay"></div>			
				<div style="position:absolute;top:10px;right:10px;width:35px;text-align:right;z-index:2"><div class="icon-add_slider"></div></div>
				
			</div>
			<div style="position:absolute;top:10px;right:50px;width:35px;text-align:right;z-index:2">				
					<?php if(isset($template['preview']) && $template['preview'] !== ''){ ?>
					<a class="icon-preview_slider" href="<?php echo esc_attr($template['preview']); ?>" target="_blank"></a>
					<?php } ?>
				</div>
			<?php if($slider_id == false){ ?>
				<div class="template_title"><?php echo $title; ?></div>
			<?php
			}
			?>

		</div>
		<?php
	}
	
}

?>