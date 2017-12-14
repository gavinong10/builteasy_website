<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class RevSliderSlider extends RevSliderElementsBase{

	const DEFAULT_POST_SORTBY = "ID";
	const DEFAULT_POST_SORTDIR = "DESC";
	
	const VALIDATE_NUMERIC = "numeric";
	const VALIDATE_EMPTY = "empty";
	const FORCE_NUMERIC = "force_numeric";
	
	const SLIDER_TYPE_GALLERY = "gallery";
	const SLIDER_TYPE_POSTS = "posts";
	const SLIDER_TYPE_TEMPLATE = "template";
	const SLIDER_TYPE_ALL = "all";
	
	private $slider_version = 5;
	private $id;
	private $title;
	private $alias;
	private $arrParams;
	private $settings;
	private $arrSlides = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 
	 * return if the slider is inited or not
	 */
	public function isInited(){
		if(!empty($this->id))
			return(true);
			
		return(false);
	}
	
	
	/**
	 * 
	 * validate that the slider is inited. if not - throw error
	 */
	private function validateInited(){
		if(empty($this->id))
			RevSliderFunctions::throwError("The slider is not initialized!");
	}
	
	/**
	 * 
	 * init slider by db data
	 * 
	 */
	public function initByDBData($arrData){
		
		$this->id = $arrData["id"];
		$this->title = $arrData["title"];
		$this->alias = $arrData["alias"];
		
		$settings = $arrData["settings"];
		$settings = (array)json_decode($settings);
		
		$this->settings = $settings;
		
		
		$params = $arrData["params"];
		$params = (array)json_decode($params);
		$params = RevSliderBase::translate_settings_to_v5($params);
		
		$this->arrParams = $params;
	}
	
	
	/**
	 * 
	 * init the slider object by database id
	 */
	public function initByID($sliderID){
		RevSliderFunctions::validateNumeric($sliderID,"Slider ID");
		$sliderID = $this->db->escape($sliderID);
		
		try{
			$sliderData = $this->db->fetchSingle(RevSliderGlobals::$table_sliders,"id=$sliderID");								
		}catch(Exception $e){
			$message = $e->getMessage();
			echo $message;
			exit;
		}
		
		$this->initByDBData($sliderData);
	}

	/**
	 * 
	 * init slider by alias
	 */
	public function initByAlias($alias){
		$alias = $this->db->escape($alias);

		try{
			$where = "alias='$alias' AND `type` != 'template'";
			
			$sliderData = $this->db->fetchSingle(RevSliderGlobals::$table_sliders,$where);
			
		}catch(Exception $e){
			$arrAliases = $this->getAllSliderAliases();
			$strAliases = "";
			
			if(!empty($arrAliases)){
				$arrAliases = array_slice($arrAliases, 0, 6); //show 6 other, will be enough
				
				$strAliases = "'".implode("' or '", $arrAliases)."'";
			}
				
			$errorMessage = 'Slider with alias <strong>'.esc_attr($alias).'</strong> not found.';
			if(!empty($strAliases))
				$errorMessage .= ' <br>Maybe you mean: '.$strAliases;
				
			RevSliderFunctions::throwError($errorMessage);
		}
		
		$this->initByDBData($sliderData);
	}
	
	
	/**
	 * 
	 * init by id or alias
	 */
	public function initByMixed($mixed){
		if(is_numeric($mixed))
			$this->initByID($mixed);
		else
			$this->initByAlias($mixed);
	}
	
	
	/**
	 * 
	 * get data functions
	 */
	public function getTitle(){
		return($this->title);
	}
	
	public function getID(){
		return($this->id);
	}
	
	public function getParams(){
		return($this->arrParams);
	}
	
	/*
	 * return Slider settings
	 * @since: 5.0
	 */
	public function getSettings(){
		return($this->settings);
	}
	
	/*
	 * return true if slider is favorite
	 * @since: 5.0
	 */
	public function isFavorite(){
		if(!empty($this->settings)){
			if(isset($this->settings['favorite']) && $this->settings['favorite'] == 'true') return true;
		}
		
		return false;
	}
	
	/**
	 * 
	 * set slider params
	 */
	public function setParams($arrParams){
		$this->arrParams = $arrParams;
	}
	
	
	/**
	 * 
	 * get parameter from params array. if no default, then the param is a must!
	 */
	function getParam($name,$default=null,$validateType = null,$title=""){
		
		if($default === null){
			//if(!array_key_exists($name, $this->arrParams))
			//	RevSliderFunctions::throwError("The param <b>$name</b> not found in slider params.");
			
			$default = "";
		}
		
		$value = RevSliderFunctions::getVal($this->arrParams, $name,$default);
		
		//validation:
		switch($validateType){
			case self::VALIDATE_NUMERIC:
			case self::VALIDATE_EMPTY:
				$paramTitle = !empty($title)?$title:$name;
				if($value !== "0" && $value !== 0 && empty($value))
					RevSliderFunctions::throwError("The param <strong>$paramTitle</strong> should not be empty.");
			break;
			case self::VALIDATE_NUMERIC:
				$paramTitle = !empty($title)?$title:$name;
				if(!is_numeric($value))
					RevSliderFunctions::throwError("The param <strong>$paramTitle</strong> should be numeric. Now it's: $value");
			break;
			case self::FORCE_NUMERIC:
				if(!is_numeric($value)){
					$value = 0;
					if(!empty($default))
						$value = $default;
				}
			break; 
		}
		
		return $value;
	}
	
	public function getAlias(){
		return($this->alias);
	}
	
	/**
	 * get combination of title (alias)
	 */
	public function getShowTitle(){
		$showTitle = $this->title;
		return($showTitle);
	}
	
	/**
	 * 
	 * get slider shortcode
	 */
	public function getShortcode(){
		$shortCode = '[rev_slider alias="'.$this->alias.'"]';
		return($shortCode);
	}
	
	
	/**
	 * 
	 * check if alias exists in DB
	 */
	public function isAliasExistsInDB($alias){
		$alias = $this->db->escape($alias);
		
		$where = "alias='$alias'";
		if(!empty($this->id)){
			$id = $this->db->escape($this->id);
			
			$where .= " and id != '".$id."' AND `type` != 'template'";
		}
		
		$response = $this->db->fetch(RevSliderGlobals::$table_sliders,$where);
		return(!empty($response));
		
	}
	
	
	/**
	 * 
	 * check if alias exists in DB
	 */
	public static function isAliasExists($alias){
		global $wpdb;
		
		$response = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".RevSliderGlobals::$table_sliders." WHERE alias = %s AND `type` != 'template'", $alias));
		
		return(!empty($response));
	}
	
	
	/**
	 * 
	 * validate settings for add
	 */
	private function validateInputSettings($title,$alias,$params){
		RevSliderFunctions::validateNotEmpty($title,"title");
		RevSliderFunctions::validateNotEmpty($alias,"alias");
		
		if($this->isAliasExistsInDB($alias))
			RevSliderFunctions::throwError("Some other slider with alias '$alias' already exists");
		
	}
	
	
	/**
	 * set new hero slide id for the Slider
	 * @since: 5.0
	 */
	public function setHeroSlide($data){
		$sliderID = RevSliderFunctions::getVal($data, "slider_id");
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$this->initByID($sliderID);

		$new_slide_id = RevSliderFunctions::getVal($data, "slide_id");
		RevSliderFunctions::validateNotEmpty($new_slide_id,"Hero Slide ID");
		
		$this->updateParam(array('hero_active' => intval($new_slide_id)));
		
		return($new_slide_id);
	}
	
	/**
	 * 
	 * create / update slider from options
	 */
	private function createUpdateSliderFromOptions($options, $sliderID = null){
		
		$arrMain = RevSliderFunctions::getVal($options, "main");
		$params = RevSliderFunctions::getVal($options, "params");
		
		//trim all input data
		$arrMain = RevSliderFunctions::trimArrayItems($arrMain);
		
		$params = RevSliderFunctions::trimArrayItems($params);
		
		$params = array_merge($arrMain,$params);
		
		$title = RevSliderFunctions::getVal($arrMain, "title");
		$alias = RevSliderFunctions::getVal($arrMain, "alias");
		
		if(!empty($sliderID))
			$this->initByID($sliderID);
			
		$this->validateInputSettings($title, $alias, $params);
		
		$jsonParams = json_encode($params);
		
		//insert slider to database
		$arrData = array();
		$arrData["title"] = $title;
		$arrData["alias"] = $alias;
		$arrData["params"] = $jsonParams;
		
		if(empty($sliderID)){	//create slider	
			
			$arrData['settings'] = json_encode(array('version' => 5.0));
			
			$sliderID = $this->db->insert(RevSliderGlobals::$table_sliders,$arrData);
			return($sliderID);
			
		}else{	//update slider
			$this->initByID($sliderID);
			
			$settings = $this->getSettings();
			$settings['version'] = 5.0;
			$arrData['settings'] = json_encode($settings);
			
			$sliderID = $this->db->update(RevSliderGlobals::$table_sliders,$arrData,array("id"=>$sliderID));
		}
	}
	
	
	/**
	 * delete slider from datatase
	 */
	public function deleteSlider(){
		
		$this->validateInited();
		
		//delete slider
		$this->db->delete(RevSliderGlobals::$table_sliders,"id=".$this->id);
		
		//delete slides
		$this->deleteAllSlides();
		$this->deleteStaticSlide();
	}

	/**
	 * 
	 * delete all slides
	 */
	private function deleteAllSlides(){
		$this->validateInited();
		
		$this->db->delete(RevSliderGlobals::$table_slides,"slider_id=".$this->id);			
	}
	

	/**
	 * 
	 * delete all slides
	 */
	public function deleteStaticSlide(){
		$this->validateInited();
		
		$this->db->delete(RevSliderGlobals::$table_static_slides,"slider_id=".$this->id);			
	}
	
	
	/**
	 * 
	 * get all slide children
	 */
	public function getArrSlideChildren($slideID){
	
		$this->validateInited();
		$arrSlides = $this->getSlidesFromGallery();
		if(!isset($arrSlides[$slideID]))
			RevSliderFunctions::throwError("Slide with id: $slideID not found in the main slides of the slider. Maybe it's child slide.");
		
		$slide = $arrSlides[$slideID];
		$arrChildren = $slide->getArrChildren();
		
		return($arrChildren);
	}
	
	
	
	/**
	 * 
	 * duplicate slider in datatase
	 */
	private function duplicateSlider($title = false){
		
		$this->validateInited();
		
		if($title === false){
			//get slider number:
			$response = $this->db->fetch(RevSliderGlobals::$table_sliders);
			$numSliders = count($response);
			$newSliderSerial = $numSliders+1;
			
			$newSliderTitle = "Slider".$newSliderSerial;
			$newSliderAlias = "slider".$newSliderSerial;
		}else{
			$newSliderTitle = $title;
			$newSliderAlias = sanitize_title($title);
			
			// Check Duplicate Alias
			$sqlTitle = $this->db->fetch(RevSliderGlobals::$table_sliders,"alias='".sanitize_title($title)."'");
			if(!empty($sqlTitle)){
				$response = $this->db->fetch(RevSliderGlobals::$table_sliders);
				$numSliders = count($response);
				$newSliderSerial = $numSliders+1;
				$newSliderTitle .= $newSliderSerial;
				$newSliderAlias .= $newSliderSerial;	
			}
		}
		
		//insert a new slider
		$sqlSelect = "select ".RevSliderGlobals::FIELDS_SLIDER." from ".RevSliderGlobals::$table_sliders." where id=".$this->id."";
		$sqlInsert = "insert into ".RevSliderGlobals::$table_sliders." (".RevSliderGlobals::FIELDS_SLIDER.") ($sqlSelect)";
		
		$this->db->runSql($sqlInsert);
		$lastID = $this->db->getLastInsertID();
		RevSliderFunctions::validateNotEmpty($lastID);
		
		//update the new slider with the title and the alias values
		$arrUpdate = array();
		$arrUpdate["title"] = $newSliderTitle;
		$arrUpdate["alias"] = $newSliderAlias;
		
		//update params
		$params = $this->arrParams;
		$params["title"] = $newSliderTitle;
		$params["alias"] = $newSliderAlias;
		$params["shortcode"] = "[rev_slider alias=\"". $newSliderAlias ."\"]";

		$jsonParams = json_encode($params);
		$arrUpdate["params"] = $jsonParams;

		$arrUpdate["type"] = '';//remove the type as we do not want it to be template if it was

		$this->db->update(RevSliderGlobals::$table_sliders, $arrUpdate, array("id"=>$lastID));
		
		//duplicate Slides
		$slides = $this->db->fetch(RevSliderGlobals::$table_slides, "slider_id=".$this->id);
		if(!empty($slides)){
			foreach($slides as $slide){
				$slide['slider_id'] = $lastID;
				$myID = $slide['id'];
				unset($slide['id']);
				$last_id = $this->db->insert(RevSliderGlobals::$table_slides,$slide);
				
				if(isset($myID)){
					$slider_map[$myID] = $last_id;
				}
			}
		}
		
		//duplicate static slide if exists
		$slide = new RevSlide();
		$staticID = $slide->getStaticSlideID($this->id);
		$static_id = 0;
		if($staticID !== false){
			$record = $this->db->fetchSingle(RevSliderGlobals::$table_static_slides,"id=$staticID");
			unset($record['id']);
			$record['slider_id'] = $lastID;
			
			$static_id = $this->db->insert(RevSliderGlobals::$table_static_slides, $record);
		}
		
		
		//update actions
		$slides = $this->db->fetch(RevSliderGlobals::$table_slides, "slider_id=$lastID");
		if($static_id > 0){
			$slides_static = $this->db->fetch(RevSliderGlobals::$table_static_slides, "id=$static_id");
			$slides = array_merge($slides, $slides_static);
		}
		if(!empty($slides)){
			foreach($slides as $slide){
				$c_slide = new RevSlide();
				$c_slide->initByData($slide);
				
				$layers = $c_slide->getLayers();
				$did_change = false;
				foreach($layers as $key => $value){
					if(isset($value['layer_action'])){
						if(isset($value['layer_action']->jump_to_slide) && !empty($value['layer_action']->jump_to_slide)){
							foreach($value['layer_action']->jump_to_slide as $jtsk => $jtsval){
								if(isset($slider_map[$jtsval])){
									
									$layers[$key]['layer_action']->jump_to_slide[$jtsk] = $slider_map[$jtsval];
									$did_change = true;
								}
							}
						}
					}
				}
				
				if($did_change === true){
					
					$arrCreate = array();
					$my_layers = json_encode($layers);
					if(empty($my_layers))
						$my_layers = stripslashes(json_encode($layers));
					
					$arrCreate['layers'] = $my_layers;
					
					if($slide['id'] == $static_id){
						$this->db->update(RevSliderGlobals::$table_static_slides,$arrCreate,array("id"=>$static_id));
					}else{
						$this->db->update(RevSliderGlobals::$table_slides,$arrCreate,array("id"=>$slide['id']));
					}
					
				}
			}
		}
	}
	
	
	/**
	 * 
	 * duplicate slide
	 */
	public function duplicateSlide($slideID){
		$slide = new RevSlide();
		$slide->initByID($slideID);
		$order = $slide->getOrder();
		$slides = $this->getSlidesFromGallery();
		$newOrder = $order+1;
		$this->shiftOrder($newOrder);
		
		//do duplication
		$sqlSelect = "select ".RevSliderGlobals::FIELDS_SLIDE." from ".RevSliderGlobals::$table_slides." where id=".intval($slideID);
		$sqlInsert = "insert into ".RevSliderGlobals::$table_slides." (".RevSliderGlobals::FIELDS_SLIDE.") ($sqlSelect)";
		
		$this->db->runSql($sqlInsert);
		$lastID = $this->db->getLastInsertID();
		RevSliderFunctions::validateNotEmpty($lastID);
		
		//update order
		$arrUpdate = array("slide_order"=>$newOrder);
		
		$this->db->update(RevSliderGlobals::$table_slides,$arrUpdate, array("id"=>$lastID));
		
		return($lastID);
	}
	
	
	/**
	 * 
	 * copy / move slide
	 */		
	private function copyMoveSlide($slideID,$targetSliderID,$operation){
		
		if($operation == "move"){
			
			$targetSlider = new RevSlider();
			$targetSlider->initByID($targetSliderID);
			$maxOrder = $targetSlider->getMaxOrder();
			$newOrder = $maxOrder+1;
			$arrUpdate = array("slider_id"=>$targetSliderID,"slide_order"=>$newOrder);	
			
			//update children
			$arrChildren = $this->getArrSlideChildren($slideID);
			foreach($arrChildren as $child){
				$childID = $child->getID();
				$this->db->update(RevSliderGlobals::$table_slides,$arrUpdate,array("id"=>$childID));
			}
			
			$this->db->update(RevSliderGlobals::$table_slides,$arrUpdate,array("id"=>$slideID));
			
		}else{	//in place of copy
			$newSlideID = $this->duplicateSlide($slideID);
			$this->duplicateChildren($slideID, $newSlideID);
			
			$this->copyMoveSlide($newSlideID,$targetSliderID,"move");
		}
	}
	
	
	/**
	 * 
	 * shift order of the slides from specific order
	 */
	private function shiftOrder($fromOrder){
		
		$where = " slider_id=".$this->id." and slide_order >= $fromOrder";
		$sql = "update ".RevSliderGlobals::$table_slides." set slide_order=(slide_order+1) where $where";
		$this->db->runSql($sql);
		
	}
	
	
	/**
	 * 
	 * create slider in database from options
	 */
	public function createSliderFromOptions($options){
		$sliderID = $this->createUpdateSliderFromOptions($options,null);
		return($sliderID);			
	}
	
	
	/**
	 * 
	 * export slider from data, output a file for download
	 */
	public function exportSlider($useDummy = false){
		$export_zip = true;
		if(function_exists("unzip_file") == false){				
			if( class_exists("ZipArchive") == false)
				$export_zip = false;
		}
		
		if(!class_exists('ZipArchive')) $export_zip = false;
		
		if($export_zip){
			$zip = new ZipArchive;
			$success = $zip->open(RevSliderGlobals::$uploadsUrlExportZip, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);
			
			if($success !== true)
				throwError("Can't create zip file: ".RevSliderGlobals::$uploadsUrlExportZip);
			
			$this->validateInited();
			
			$sliderParams = $this->getParamsForExport();
			$arrSlides = $this->getSlidesForExport($useDummy);
			$arrStaticSlide = $this->getStaticSlideForExport($useDummy);
			
			$usedCaptions = array();
			$usedAnimations = array();
			$usedImages = array();
			$usedVideos = array();
			
			$cfw = array();
			if(!empty($arrSlides) && count($arrSlides) > 0) $cfw = array_merge($cfw, $arrSlides);
			if(!empty($arrStaticSlide) && count($arrStaticSlide) > 0) $cfw = array_merge($cfw, $arrStaticSlide);

			if(!empty($cfw) && count($cfw) > 0){
				foreach($cfw as $key => $slide){
					if(isset($slide['params']['image']) && $slide['params']['image'] != '') $usedImages[$slide['params']['image']] = true; //['params']['image'] background url
					if(isset($slide['params']['background_image']) && $slide['params']['background_image'] != '') $usedImages[$slide['params']['background_image']] = true; //['params']['image'] background url
					if(isset($slide['params']['slide_thumb']) && $slide['params']['slide_thumb'] != '') $usedImages[$slide['params']['slide_thumb']] = true; //['params']['image'] background url
					
					//html5 video
					if(isset($slide['params']['background_type']) && $slide['params']['background_type'] == 'html5'){
						if(isset($slide['params']['slide_bg_html_mpeg']) && $slide['params']['slide_bg_html_mpeg'] != '') $usedVideos[$slide['params']['slide_bg_html_mpeg']] = true;
						if(isset($slide['params']['slide_bg_html_webm']) && $slide['params']['slide_bg_html_webm'] != '') $usedVideos[$slide['params']['slide_bg_html_webm']] = true;
						if(isset($slide['params']['slide_bg_html_ogv']) && $slide['params']['slide_bg_html_ogv'] != '') $usedVideos[$slide['params']['slide_bg_html_ogv']] = true;
					}else{
						if(isset($slide['params']['slide_bg_html_mpeg']) && $slide['params']['slide_bg_html_mpeg'] != '') $slide['params']['slide_bg_html_mpeg'] = '';
						if(isset($slide['params']['slide_bg_html_webm']) && $slide['params']['slide_bg_html_webm'] != '') $slide['params']['slide_bg_html_webm'] = '';
						if(isset($slide['params']['slide_bg_html_ogv']) && $slide['params']['slide_bg_html_ogv'] != '') $slide['params']['slide_bg_html_ogv'] = '';
					}
					
					//image thumbnail
					if(isset($slide['layers']) && !empty($slide['layers']) && count($slide['layers']) > 0){
						foreach($slide['layers'] as $lKey => $layer){
							if(isset($layer['style']) && $layer['style'] != '') $usedCaptions[$layer['style']] = true;
							if(isset($layer['animation']) && $layer['animation'] != '' && strpos($layer['animation'], 'customin') !== false) $usedAnimations[str_replace('customin-', '', $layer['animation'])] = true;
							if(isset($layer['endanimation']) && $layer['endanimation'] != '' && strpos($layer['endanimation'], 'customout') !== false) $usedAnimations[str_replace('customout-', '', $layer['endanimation'])] = true;
							if(isset($layer['image_url']) && $layer['image_url'] != '') $usedImages[$layer['image_url']] = true; //image_url if image caption
							
							if(isset($layer['type']) && $layer['type'] == 'video'){
								
								$video_data = (isset($layer['video_data'])) ? (array) $layer['video_data'] : array();
								
								if(!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] == 'html5'){

									if(isset($video_data['urlPoster']) && $video_data['urlPoster'] != '') $usedImages[$video_data['urlPoster']] = true;
									
									if(isset($video_data['urlMp4']) && $video_data['urlMp4'] != '') $usedVideos[$video_data['urlMp4']] = true;
									if(isset($video_data['urlWebm']) && $video_data['urlWebm'] != '') $usedVideos[$video_data['urlWebm']] = true;
									if(isset($video_data['urlOgv']) && $video_data['urlOgv'] != '') $usedVideos[$video_data['urlOgv']] = true;
									
								}elseif(!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] != 'html5'){ //video cover image
									if(isset($video_data['previewimage']) && $video_data['previewimage'] != '') $usedImages[$video_data['previewimage']] = true;
								}
							}
							
						}
					}
				}
			}
			
			/*if(!empty($arrStaticSlide) && count($arrStaticSlide) > 0){
				foreach($arrStaticSlide as $key => $slide){
					if(isset($slide['params']['image']) && $slide['params']['image'] != '') $usedImages[$slide['params']['image']] = true; //['params']['image'] background url
					
					//html5 video
					if(isset($slide['params']['background_type']) && $slide['params']['background_type'] == 'html5'){
						if(isset($slide['params']['slide_bg_html_mpeg']) && $slide['params']['slide_bg_html_mpeg'] != '') $usedVideos[$slide['params']['slide_bg_html_mpeg']] = true;
						if(isset($slide['params']['slide_bg_html_webm']) && $slide['params']['slide_bg_html_webm'] != '') $usedVideos[$slide['params']['slide_bg_html_webm']] = true;
						if(isset($slide['params']['slide_bg_html_ogv']) && $slide['params']['slide_bg_html_ogv'] != '') $usedVideos[$slide['params']['slide_bg_html_ogv']] = true;
					}
					
					if(isset($slide['layers']) && !empty($slide['layers']) && count($slide['layers']) > 0){
						foreach($slide['layers'] as $lKey => $layer){
							if(isset($layer['style']) && $layer['style'] != '') $usedCaptions[$layer['style']] = true;
							if(isset($layer['animation']) && $layer['animation'] != '' && strpos($layer['animation'], 'customin') !== false) $usedAnimations[str_replace('customin-', '', $layer['animation'])] = true;
							if(isset($layer['endanimation']) && $layer['endanimation'] != '' && strpos($layer['endanimation'], 'customout') !== false) $usedAnimations[str_replace('customout-', '', $layer['endanimation'])] = true;
							if(isset($layer['image_url']) && $layer['image_url'] != '') $usedImages[$layer['image_url']] = true; //image_url if image caption
							
							if(isset($layer['type']) && $layer['type'] == 'video'){
								
								$video_data = (isset($layer['video_data'])) ? (array) $layer['video_data'] : array();
								
								if(!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] == 'html5'){

									if(isset($video_data['urlPoster']) && $video_data['urlPoster'] != '') $usedImages[$video_data['urlPoster']] = true;
									
									if(isset($video_data['urlMp4']) && $video_data['urlMp4'] != '') $usedVideos[$video_data['urlMp4']] = true;
									if(isset($video_data['urlWebm']) && $video_data['urlWebm'] != '') $usedVideos[$video_data['urlWebm']] = true;
									if(isset($video_data['urlOgv']) && $video_data['urlOgv'] != '') $usedVideos[$video_data['urlOgv']] = true;
									
								}elseif(!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] != 'html5'){ //video cover image
									if(isset($video_data['previewimage']) && $video_data['previewimage'] != '') $usedImages[$video_data['previewimage']] = true;
								}
							}
						}
					}
				}
			}*/
			
			$arrSliderExport = array("params"=>$sliderParams,"slides"=>$arrSlides);
			if(!empty($arrStaticSlide))
				$arrSliderExport['static_slides'] = $arrStaticSlide;
			
			$strExport = serialize($arrSliderExport);
			
			//$strExportAnim = serialize(RevSliderOperations::getFullCustomAnimations());
			
			$exportname =(!empty($this->alias)) ? $this->alias.'.zip' : "slider_export.zip";
			
			
			
			$styles = '';
			if(!empty($usedCaptions)){
				$captions = array();
				foreach($usedCaptions as $class => $val){
					$cap = RevSliderOperations::getCaptionsContentArray($class);
					//set also advanced styles here...
					if(!empty($cap))
						$captions[] = $cap;
				}
				$styles = RevSliderCssParser::parseArrayToCss($captions, "\n", true);
			}
			
			$animations = '';
			if(!empty($usedAnimations)){
				$animation = array();
				foreach($usedAnimations as $anim => $val){
					$anima = RevSliderOperations::getFullCustomAnimationByID($anim);
					if($anima !== false) $animation[] = RevSliderOperations::getFullCustomAnimationByID($anim);
					
				}
				if(!empty($animation)) $animations = serialize($animation);
			}
			
			$usedImages = array_merge($usedImages, $usedVideos);
			//add images to zip
			if(!empty($usedImages)){
				$upload_dir = RevSliderFunctionsWP::getPathUploads();
				$upload_dir_multisiteless = wp_upload_dir();
				$cont_url = $upload_dir_multisiteless['baseurl'];
				$cont_url_no_www = str_replace('www.', '', $upload_dir_multisiteless['baseurl']);
				$upload_dir_multisiteless = $upload_dir_multisiteless['basedir'].'/';
				
				
				foreach($usedImages as $file => $val){
					if($useDummy == "true"){ //only use dummy images
						
					}else{ //use the real images
						if(strpos($file, 'http') !== false){
							$remove = false;
							$checkpath = str_replace(array($cont_url, $cont_url_no_www), '', $file);
							
							if(is_file($upload_dir.$checkpath)){
								$zip->addFile($upload_dir.$checkpath, 'images/'.$checkpath);
								$remove = true;
							}elseif(is_file($upload_dir_multisiteless.$checkpath)){
								$zip->addFile($upload_dir_multisiteless.$checkpath, 'images/'.$checkpath);
								$remove = true;
							}
							
							if($remove){ //as its http, remove this from strexport
								$strExport = str_replace(array($cont_url.$checkpath, $cont_url_no_www.$checkpath), $checkpath, $strExport);
							}
						}else{
							if(is_file($upload_dir.$file)){
								$zip->addFile($upload_dir.$file, 'images/'.$file);
							}elseif(is_file($upload_dir_multisiteless.$file)){
								$zip->addFile($upload_dir_multisiteless.$file, 'images/'.$file);
							}
						}
					}
				}
			}
			
			$zip->addFromString("slider_export.txt", $strExport); //add slider settings
			if(strlen(trim($animations)) > 0) $zip->addFromString("custom_animations.txt", $animations); //add custom animations
			if(strlen(trim($styles)) > 0) $zip->addFromString("dynamic-captions.css", $styles); //add dynamic styles
			
			$static_css = RevSliderOperations::getStaticCss();
			$zip->addFromString("static-captions.css", $static_css); //add slider settings
			
			$enable_slider_pack = apply_filters('revslider_slider_pack_export', false);
			if($enable_slider_pack){ //allow for slider packs the automatic creation of the info.cfg
				$zip->addFromString('info.cfg', md5($this->alias)); //add slider settings
			}
			
			$zip->close();
			
			header("Content-type: application/zip");
			header("Content-Disposition: attachment; filename=".$exportname);
			header("Pragma: no-cache");
			header("Expires: 0");
			readfile(RevSliderGlobals::$uploadsUrlExportZip);
			
			@unlink(RevSliderGlobals::$uploadsUrlExportZip); //delete file after sending it to user
		}else{ //fallback, do old export
			$this->validateInited();
		
			$sliderParams = $this->getParamsForExport();
			$arrSlides = $this->getSlidesForExport();
			
			$arrSliderExport = array("params"=>$sliderParams,"slides"=>$arrSlides);
			
			$strExport = serialize($arrSliderExport);
			
			if(!empty($this->alias))
				$filename = $this->alias.".txt";
			else
				$filename = "slider_export.txt";
			
			RevSliderFunctions::downloadFile($strExport,$filename);
		}
	}
	
	
	/**
	 * 
	 * import slider from multipart form
	 */
	public function importSliderFromPost($updateAnim = true, $updateStatic = true, $exactfilepath = false, $is_template = false, $single_slide = false){
		
		try{
			
			$sliderID = RevSliderFunctions::getPostVariable("sliderid");
			$sliderExists = !empty($sliderID);
			
			if($sliderExists)
				$this->initByID($sliderID);
			
			if($exactfilepath !== false){
				$filepath = $exactfilepath;
			}else{
				switch ($_FILES['import_file']['error']) {
					case UPLOAD_ERR_OK:
						break;
					case UPLOAD_ERR_NO_FILE:
						RevSliderFunctions::throwError(__('No file sent.', REVSLIDER_TEXTDOMAIN));
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						RevSliderFunctions::throwError(__('Exceeded filesize limit.', REVSLIDER_TEXTDOMAIN));
						
					default:
					break;
				}
				$filepath = $_FILES["import_file"]["tmp_name"];
			}
			
			if(file_exists($filepath) == false)
				RevSliderFunctions::throwError("Import file not found!!!");
			
			//check if zip file or fallback to old, if zip, check if all files exist
			if(!class_exists("ZipArchive")){
				$importZip = false;
			}else{
				$zip = new ZipArchive;
				$importZip = $zip->open($filepath, ZIPARCHIVE::CREATE);
				
				// Added by ThemeFuzz ( Stefan )
				if ( $importZip === 0 || !$zip->getStream('slider_export.txt') ) {
					if(!$zip->getStream('slider_export.txt')){
						$upload_dir = wp_upload_dir();
						$new_path =  $upload_dir['basedir'].'/'.$_FILES['import_file']['name'];
						move_uploaded_file( $_FILES["import_file"]["tmp_name"], $new_path);
						$importZip = $zip->open( $new_path, ZIPARCHIVE::CREATE);
					}
				}
				
			}
			
			if($is_template !== false && $importZip !== true){
				return(array("success"=>false,"error"=>__('Please select the correct zip file', REVSLIDER_TEXTDOMAIN)));
			}
			
			if($importZip === true){ //true or integer. If integer, its not a correct zip file
				
				//check if files all exist in zip
				$slider_export = $zip->getStream('slider_export.txt');
				$custom_animations = $zip->getStream('custom_animations.txt');
				$dynamic_captions = $zip->getStream('dynamic-captions.css');
				$static_captions = $zip->getStream('static-captions.css');
				
				$uid_file = $zip->getStream('info.cfg');
				$uid_check = '';
				if($uid_file){ while (!feof($uid_file)) $uid_check .= fread($uid_file, 1024); }
				
				if($is_template !== false){
					if($uid_check != $is_template){
						return(array("success"=>false,"error"=>__('Please select the correct zip file, checksum failed!', REVSLIDER_TEXTDOMAIN)));
					}
				}else{ //someone imported a template base Slider, check if it is existing in Base Sliders, if yes, check if it was imported
					if($uid_check !== ''){
						$tmpl = new RevSliderTemplate();
						$tmpl_slider = $tmpl->getThemePunchTemplateSliders();
						
						foreach($tmpl_slider as $tp_slider){
							if(!isset($tp_slider['installed'])) continue;
							
							if($tp_slider['uid'] == $uid_check){
								$is_template = $uid_check;
								break;
							}
						}
					}
				}
				
				
				if(!$slider_export)  RevSliderFunctions::throwError("slider_export.txt does not exist!");
				
				$content = '';
				$animations = '';
				$dynamic = '';
				$static = '';
				
				while (!feof($slider_export)) $content .= fread($slider_export, 1024);
				if($custom_animations){ while (!feof($custom_animations)) $animations .= fread($custom_animations, 1024); }
				if($dynamic_captions){ while (!feof($dynamic_captions)) $dynamic .= fread($dynamic_captions, 1024); }
				if($static_captions){ while (!feof($static_captions)) $static .= fread($static_captions, 1024); }

				fclose($slider_export);
				if($custom_animations){ fclose($custom_animations); }
				if($dynamic_captions){ fclose($dynamic_captions); }
				if($static_captions){ fclose($static_captions); }
				
				//check for images!
				
			}else{ //check if fallback
				//get content array
				$content = @file_get_contents($filepath);
			}
			
			if($importZip === true){ //we have a zip
				$db = new RevSliderDB();
				
				//update/insert custom animations
				$animations = @unserialize($animations);
				if(!empty($animations)){
					foreach($animations as $key => $animation){ //$animation['id'], $animation['handle'], $animation['params']
						$exist = $db->fetch(RevSliderGlobals::$table_layer_anims, "handle = '".$animation['handle']."'");
						if(!empty($exist)){ //update the animation, get the ID
							if($updateAnim == "true"){ //overwrite animation if exists
								$arrUpdate = array();
								$arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
								$db->update(RevSliderGlobals::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));
								
								$anim_id = $exist['0']['id'];
							}else{ //insert with new handle
								$arrInsert = array();
								$arrInsert["handle"] = 'copy_'.$animation['handle'];
								$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
								
								$anim_id = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
							}
						}else{ //insert the animation, get the ID
							$arrInsert = array();
							$arrInsert["handle"] = $animation['handle'];
							$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
							
							$anim_id = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
						}
						
						//and set the current customin-oldID and customout-oldID in slider params to new ID from $id
						$content = str_replace(array('customin-'.$animation['id'].'"', 'customout-'.$animation['id'].'"'), array('customin-'.$anim_id.'"', 'customout-'.$anim_id.'"'), $content);	
					}
					dmp(__("animations imported!",REVSLIDER_TEXTDOMAIN));
				}else{
					dmp(__("no custom animations found, if slider uses custom animations, the provided export may be broken...",REVSLIDER_TEXTDOMAIN));
				}
				
				//overwrite/append static-captions.css
				if(!empty($static)){
					if($updateStatic == "true"){ //overwrite file
						RevSliderOperations::updateStaticCss($static);
					}elseif($updateStatic == 'none'){
						//do nothing
					}else{//append
						$static_cur = RevSliderOperations::getStaticCss();
						$static = $static_cur."\n".$static;
						RevSliderOperations::updateStaticCss($static);
					}
				}
				//overwrite/create dynamic-captions.css
				//parse css to classes
				$dynamicCss = RevSliderCssParser::parseCssToArray($dynamic);
				
				if(is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0){
					foreach($dynamicCss as $class => $styles){
						//check if static style or dynamic style
						$class = trim($class);
						
						if(strpos($class, ',') !== false && strpos($class, '.tp-caption') !== false){ //we have something like .tp-caption.redclass, .redclass
							$class_t = explode(',', $class);
							foreach($class_t as $k => $cl){
								if(strpos($cl, '.tp-caption') !== false) $class = $cl;
							}
						}
						
						if((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
							strpos($class," ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
							strpos($class,".tp-caption") === false || // everything that is not tp-caption
							(strpos($class,".") === false || strpos($class,"#") !== false) || // no class -> #ID or img
							strpos($class,">") !== false){ //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img
							continue;
						}
						
						//is a dynamic style
						if(strpos($class, ':hover') !== false){
							$class = trim(str_replace(':hover', '', $class));
							$arrInsert = array();
							$arrInsert["hover"] = json_encode($styles);
							$arrInsert["settings"] = json_encode(array('hover' => 'true'));
						}else{
							$arrInsert = array();
							$arrInsert["params"] = json_encode($styles);
							$arrInsert["settings"] = '';
						}
						//check if class exists
						$result = $db->fetch(RevSliderGlobals::$table_css, "handle = '".$class."'");
						
						if(!empty($result)){ //update
							$db->update(RevSliderGlobals::$table_css, $arrInsert, array('handle' => $class));
						}else{ //insert
							$arrInsert["handle"] = $class;
							$db->insert(RevSliderGlobals::$table_css, $arrInsert);
						}
					}
					dmp(__("dynamic styles imported!",REVSLIDER_TEXTDOMAIN));
				}else{
					dmp(__("no dynamic styles found, if slider uses dynamic styles, the provided export may be broken...",REVSLIDER_TEXTDOMAIN));
				}
			}
			
			//$content = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $content); //clear errors in string //deprecated in newest php version
			$content = preg_replace_callback('!s:(\d+):"(.*?)";!', array('RevSlider', 'clear_error_in_string') , $content); //clear errors in string
			
			$arrSlider = @unserialize($content);
			if(empty($arrSlider))
				RevSliderFunctions::throwError("Wrong export slider file format! This could be caused because the ZipArchive extension is not enabled.");
			
			//update slider params
			$sliderParams = $arrSlider["params"];
			
			if($sliderExists){					
				$sliderParams["title"] = $this->arrParams["title"];
				$sliderParams["alias"] = $this->arrParams["alias"];
				$sliderParams["shortcode"] = $this->arrParams["shortcode"];
			}
			
			if(isset($sliderParams["background_image"]))
				$sliderParams["background_image"] = RevSliderFunctionsWP::getImageUrlFromPath($sliderParams["background_image"]);
			
			
			$import_statics = true;
			if(isset($sliderParams['enable_static_layers'])){
				if($sliderParams['enable_static_layers'] == 'off') $import_statics = false;
				unset($sliderParams['enable_static_layers']);
			}
			
			$json_params = json_encode($sliderParams);
			
			//update slider or create new
			if($sliderExists){
				$arrUpdate = array("params"=>$json_params);	
				$this->db->update(RevSliderGlobals::$table_sliders,$arrUpdate,array("id"=>$sliderID));
			}else{	//new slider
				$arrInsert = array();
				$arrInsert['params'] = $json_params;
				//check if Slider with title and/or alias exists, if yes change both to stay unique
				
				
				$arrInsert['title'] = RevSliderFunctions::getVal($sliderParams, 'title', 'Slider1');
				$arrInsert['alias'] = RevSliderFunctions::getVal($sliderParams, 'alias', 'slider1');	
				if($is_template === false){ //we want to stay at the given alias if we are a template
					$talias = $arrInsert['alias'];
					$ti = 1;
					while($this->isAliasExistsInDB($talias)){ //set a new alias and title if its existing in database
						$talias = $arrInsert['alias'] . $ti;
						$ti++;
					}
					if($talias !== $arrInsert['alias']){
						$arrInsert['title'] = $talias;
						$arrInsert['alias'] = $talias;
					}
				}
				
				if($is_template !== false){ //add that we are an template
					$arrInsert['type'] = 'template';
				}
				
				$sliderID = $this->db->insert(RevSliderGlobals::$table_sliders,$arrInsert);
			}
			
			//-------- Slides Handle -----------
			
			//delete current slides
			if($sliderExists)
				$this->deleteAllSlides();
			
			//create all slides
			$arrSlides = $arrSlider["slides"];
			
			$alreadyImported = array();
			
			//wpml compatibility
			$slider_map = array();
			
			foreach($arrSlides as $sl_key => $slide){
				$params = $slide["params"];
				$layers = $slide["layers"];
				$settings = (isset($slide["settings"])) ? $slide["settings"] : '';
				
				//convert params images:
				if($importZip === true){ //we have a zip, check if exists
					if(isset($params["image"])){
						$params["image"] = RevSliderBase::check_file_in_zip($zip, $params["image"], $filepath, $sliderParams["alias"], $alreadyImported);
						$params["image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["image"]);
					}
					
					if(isset($params["background_image"])){
						$params["background_image"] = RevSliderBase::check_file_in_zip($zip, $params["background_image"], $filepath, $sliderParams["alias"], $alreadyImported);
						$params["background_image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["background_image"]);
					}
					
					if(isset($params["slide_thumb"])){
						$params["slide_thumb"] = RevSliderBase::check_file_in_zip($zip, $params["slide_thumb"], $filepath, $sliderParams["alias"], $alreadyImported);
						$params["slide_thumb"] = RevSliderFunctionsWP::getImageUrlFromPath($params["slide_thumb"]);
					}
					
					if(isset($params["show_alternate_image"])){
						$params["show_alternate_image"] = RevSliderBase::check_file_in_zip($zip, $params["show_alternate_image"], $filepath, $sliderParams["alias"], $alreadyImported);
						$params["show_alternate_image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["show_alternate_image"]);
					}
					if(isset($params['background_type']) && $params['background_type'] == 'html5'){
						if(isset($params['slide_bg_html_mpeg']) && $params['slide_bg_html_mpeg'] != ''){
							$params['slide_bg_html_mpeg'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $params["slide_bg_html_mpeg"], $filepath, $sliderParams["alias"], $alreadyImported, true));
						}
						if(isset($params['slide_bg_html_webm']) && $params['slide_bg_html_webm'] != ''){
							$params['slide_bg_html_webm'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $params["slide_bg_html_webm"], $filepath, $sliderParams["alias"], $alreadyImported, true));
						}
						if(isset($params['slide_bg_html_ogv'])  && $params['slide_bg_html_ogv'] != ''){
							$params['slide_bg_html_ogv'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $params["slide_bg_html_ogv"], $filepath, $sliderParams["alias"], $alreadyImported, true));
						}
					}
				}
				
				//convert layers images:
				foreach($layers as $key=>$layer){					
					//import if exists in zip folder
					if($importZip === true){ //we have a zip, check if exists
						if(isset($layer["image_url"])){
							$layer["image_url"] = RevSliderBase::check_file_in_zip($zip, $layer["image_url"], $filepath, $sliderParams["alias"], $alreadyImported);
							$layer["image_url"] = RevSliderFunctionsWP::getImageUrlFromPath($layer["image_url"]);
						}
						if(isset($layer['type']) && $layer['type'] == 'video'){
							
							$video_data = (isset($layer['video_data'])) ? (array) $layer['video_data'] : array();
							
							if(!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] == 'html5'){

								if(isset($video_data['urlPoster']) && $video_data['urlPoster'] != ''){
									$video_data['urlPoster'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $video_data["urlPoster"], $filepath, $sliderParams["alias"], $alreadyImported));
								}
								
								if(isset($video_data['urlMp4']) && $video_data['urlMp4'] != ''){
									$video_data['urlMp4'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $video_data["urlMp4"], $filepath, $sliderParams["alias"], $alreadyImported, true));
								}
								if(isset($video_data['urlWebm']) && $video_data['urlWebm'] != ''){
									$video_data['urlWebm'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $video_data["urlWebm"], $filepath, $sliderParams["alias"], $alreadyImported, true));
								}
								if(isset($video_data['urlOgv']) && $video_data['urlOgv'] != ''){
									$video_data['urlOgv'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $video_data["urlOgv"], $filepath, $sliderParams["alias"], $alreadyImported, true));
								}
								
							}elseif(!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] != 'html5'){ //video cover image
								if(isset($video_data['previewimage']) && $video_data['previewimage'] != ''){
									$video_data['previewimage'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($zip, $video_data["previewimage"], $filepath, $sliderParams["alias"], $alreadyImported));
								}
							}
							
							$layer['video_data'] = $video_data;
							
						}
						
					}
					
					$layer['text'] = stripslashes($layer['text']);
					$layers[$key] = $layer;
				}
				$arrSlides[$sl_key]['layers'] = $layers;
				
				//create new slide
				$arrCreate = array();
				$arrCreate["slider_id"] = $sliderID;
				$arrCreate["slide_order"] = $slide["slide_order"];
				
				$my_layers = json_encode($layers);
				if(empty($my_layers))
					$my_layers = stripslashes(json_encode($layers));
				$my_params = json_encode($params);
				if(empty($my_params))
					$my_params = stripslashes(json_encode($params));
				$my_settings = json_encode($settings);
				if(empty($my_settings))
					$my_settings = stripslashes(json_encode($settings));
				
				
					
				$arrCreate["layers"] = $my_layers;
				$arrCreate["params"] = $my_params;
				$arrCreate["settings"] = $my_settings;
				
				$last_id = $this->db->insert(RevSliderGlobals::$table_slides,$arrCreate);
				
				if(isset($slide['id'])){
					$slider_map[$slide['id']] = $last_id;
				}
			}
			
			//change for WPML the parent IDs if necessary
			if(!empty($slider_map)){
				foreach($arrSlides as $sl_key => $slide){
					if(isset($slide['params']['parentid']) && isset($slider_map[$slide['params']['parentid']])){
						$update_id = $slider_map[$slide['id']];
						$parent_id = $slider_map[$slide['params']['parentid']];
						
						$arrCreate = array();
						
						$arrCreate["params"] = $slide['params'];
						$arrCreate["params"]['parentid'] = $parent_id;
						$my_params = json_encode($arrCreate["params"]);
						if(empty($my_params))
							$my_params = stripslashes(json_encode($arrCreate["params"]));
						
						$arrCreate["params"] = $my_params;
						
						$this->db->update(RevSliderGlobals::$table_slides,$arrCreate,array("id"=>$update_id));
					}
					
					$did_change = false;
					foreach($slide['layers'] as $key => $value){
						if(isset($value['layer_action'])){
							if(isset($value['layer_action']->jump_to_slide) && !empty($value['layer_action']->jump_to_slide)){
								$value['layer_action']->jump_to_slide = (array)$value['layer_action']->jump_to_slide;
								foreach($value['layer_action']->jump_to_slide as $jtsk => $jtsval){
									if(isset($slider_map[$jtsval])){
										$slide['layers'][$key]['layer_action']->jump_to_slide[$jtsk] = $slider_map[$jtsval];
										$did_change = true;
									}
								}
							}
						}
						
						$link_slide = RevSliderFunctions::getVal($value, 'link_slide', false);
						if($link_slide != false && $link_slide !== 'nothing'){ //link to slide/scrollunder is set, move it to actions
							if(!isset($slide['layers'][$key]['layer_action'])) $slide['layers'][$key]['layer_action'] = new stdClass();
							switch($link_slide){
								case 'link':
									$link = RevSliderFunctions::getVal($value, 'link');
									$link_open_in = RevSliderFunctions::getVal($value, 'link_open_in');
									$slide['layers'][$key]['layer_action']->action = array('a' => 'link');
									$slide['layers'][$key]['layer_action']->link_type = array('a' => 'a');
									$slide['layers'][$key]['layer_action']->image_link = array('a' => $link);
									$slide['layers'][$key]['layer_action']->link_open_in = array('a' => $link_open_in);
									
									unset($slide['layers'][$key]['link']);
									unset($slide['layers'][$key]['link_open_in']);
								case 'next':
									$slide['layers'][$key]['layer_action']->action = array('a' => 'next');
								break;
								case 'prev':
									$slide['layers'][$key]['layer_action']->action = array('a' => 'prev');
								break;
								case 'scroll_under':
									$scrollunder_offset = RevSliderFunctions::getVal($value, 'scrollunder_offset');
									$slide['layers'][$key]['layer_action']->action = array('a' => 'scroll_under');
									$slide['layers'][$key]['layer_action']->scrollunder_offset = array('a' => $scrollunder_offset);
									
									unset($slide['layers'][$key]['scrollunder_offset']);
								break;
								default: //its an ID, so its a slide ID
									$slide['layers'][$key]['layer_action']->action = array('a' => 'jumpto');
									$slide['layers'][$key]['layer_action']->jump_to_slide = array('a' => $slider_map[$link_slide]);
								break;
								
							}
							$slide['layers'][$key]['layer_action']->tooltip_event = array('a' => 'click');
							
							unset($slide['layers'][$key]['link_slide']);
							
							$did_change = true;
						}
						
						
						if($did_change === true){
							
							$arrCreate = array();
							$my_layers = json_encode($slide['layers']);
							if(empty($my_layers))
								$my_layers = stripslashes(json_encode($layers));
							
							$arrCreate['layers'] = $my_layers;
							
							$this->db->update(RevSliderGlobals::$table_slides,$arrCreate,array("id"=>$slider_map[$slide['id']]));
						}
					}
				}
			}
			
			//check if static slide exists and import
			if(isset($arrSlider['static_slides']) && !empty($arrSlider['static_slides']) && $import_statics){
				$static_slide = $arrSlider['static_slides'];
				foreach($static_slide as $slide){
					
					$params = $slide["params"];
					$layers = $slide["layers"];
					$settings = (isset($slide["settings"])) ? $slide["settings"] : '';
					
					
					//convert params images:
					if(isset($params["image"])){
						//import if exists in zip folder
						if(strpos($params["image"], 'http') !== false){
						}else{
							if(trim($params["image"]) !== ''){
								if($importZip === true){ //we have a zip, check if exists
									$image = $zip->getStream('images/'.$params["image"]);
									if(!$image){
										echo $params["image"].__(' not found!<br>', REVSLIDER_TEXTDOMAIN);

									}else{
										if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]])){
											$importImage = RevSliderFunctionsWP::import_media('zip://'.$filepath."#".'images/'.$params["image"], $sliderParams["alias"].'/');

											if($importImage !== false){
												$alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]] = $importImage['path'];
												
												$params["image"] = $importImage['path'];
											}
										}else{
											$params["image"] = $alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]];
										}


									}
								}
							}
							$params["image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["image"]);
						}
					}
					
					//convert layers images:
					foreach($layers as $key=>$layer){
						if(isset($layer["image_url"])){
							//import if exists in zip folder
							if(trim($layer["image_url"]) !== ''){
								if(strpos($layer["image_url"], 'http') !== false){
								}else{
									if($importZip === true){ //we have a zip, check if exists
										$image_url = $zip->getStream('images/'.$layer["image_url"]);
										if(!$image_url){
											echo $layer["image_url"].__(' not found!<br>');
										}else{
											if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]])){
												$importImage = RevSliderFunctionsWP::import_media('zip://'.$filepath."#".'images/'.$layer["image_url"], $sliderParams["alias"].'/');
												
												if($importImage !== false){
													$alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]] = $importImage['path'];
													
													$layer["image_url"] = $importImage['path'];
												}
											}else{
												$layer["image_url"] = $alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]];
											}
										}
									}
								}
							}
							$layer["image_url"] = RevSliderFunctionsWP::getImageUrlFromPath($layer["image_url"]);
							$layer['text'] = stripslashes($layer['text']);
							
						}
						
						if(isset($layer['layer_action'])){
							if(isset($layer['layer_action']->jump_to_slide) && !empty($layer['layer_action']->jump_to_slide)){
								foreach($layer['layer_action']->jump_to_slide as $jtsk => $jtsval){
									if(isset($slider_map[$jtsval])){
										$layer['layer_action']->jump_to_slide[$jtsk] = $slider_map[$jtsval];
									}
								}
							}
						}
						
						$link_slide = RevSliderFunctions::getVal($value, 'link_slide', false);
						if($link_slide != false && $link_slide !== 'nothing'){ //link to slide/scrollunder is set, move it to actions
							if(!isset($layer['layer_action'])) $layer['layer_action'] = new stdClass();
							
							switch($link_slide){
								case 'link':
									$link = RevSliderFunctions::getVal($value, 'link');
									$link_open_in = RevSliderFunctions::getVal($value, 'link_open_in');
									$layer['layer_action']->action = array('a' => 'link');
									$layer['layer_action']->link_type = array('a' => 'a');
									$layer['layer_action']->image_link = array('a' => $link);
									$layer['layer_action']->link_open_in = array('a' => $link_open_in);
									
									unset($layer['link']);
									unset($layer['link_open_in']);
								case 'next':
									$layer['layer_action']->action = array('a' => 'next');
								break;
								case 'prev':
									$layer['layer_action']->action = array('a' => 'prev');
								break;
								case 'scroll_under':
									$scrollunder_offset = RevSliderFunctions::getVal($value, 'scrollunder_offset');
									$layer['layer_action']->action = array('a' => 'scroll_under');
									$layer['layer_action']->scrollunder_offset = array('a' => $scrollunder_offset);
									
									unset($layer['scrollunder_offset']);
								break;
								default: //its an ID, so its a slide ID
									$layer['layer_action']->action = array('a' => 'jumpto');
									$layer['layer_action']->jump_to_slide = array('a' => $slider_map[$link_slide]);
								break;
								
							}
							$layer['layer_action']->tooltip_event = array('a' => 'click');
							
							unset($layer['link_slide']);
							
							$did_change = true;
						}
						
						$layers[$key] = $layer;
					}
					
					//create new slide
					$arrCreate = array();
					$arrCreate["slider_id"] = $sliderID;
					
					$my_layers = json_encode($layers);
					if(empty($my_layers))
						$my_layers = stripslashes(json_encode($layers));
					$my_params = json_encode($params);
					if(empty($my_params))
						$my_params = stripslashes(json_encode($params));
					$my_settings = json_encode($settings);
					if(empty($my_settings))
						$my_settings = stripslashes(json_encode($settings));
						
						
					$arrCreate["layers"] = $my_layers;
					$arrCreate["params"] = $my_params;
					$arrCreate["settings"] = $my_settings;
					
					if($sliderExists){
						unset($arrCreate["slider_id"]);
						$this->db->update(RevSliderGlobals::$table_static_slides,$arrCreate,array("slider_id"=>$sliderID));
					}else{
						$this->db->insert(RevSliderGlobals::$table_static_slides,$arrCreate);
					}
				}
			}
			
			$c_slider = new RevSliderSlider();
			$c_slider->initByID($sliderID);
			
			//check to convert styles to latest versions
			RevSliderPluginUpdate::update_css_styles(); //set to version 5
			RevSliderPluginUpdate::add_animation_settings_to_layer($c_slider); //set to version 5
			RevSliderPluginUpdate::add_style_settings_to_layer($c_slider); //set to version 5
			RevSliderPluginUpdate::change_settings_on_layers($c_slider); //set to version 5
			RevSliderPluginUpdate::add_general_settings($c_slider); //set to version 5
			RevSliderPluginUpdate::change_general_settings_5_0_7($c_slider); //set to version 5.0.7
			
			$cus_js = $c_slider->getParam('custom_javascript', '');
			
			if(strpos($cus_js, 'revapi') !== false){
				if(preg_match_all('/revapi[0-9]*/', $cus_js, $results)){

					if(isset($results[0]) && !empty($results[0])){
						foreach($results[0] as $replace){
							$cus_js = str_replace($replace, 'revapi'.$sliderID, $cus_js);
						}
					}
					
					$c_slider->updateParam(array('custom_javascript' => $cus_js));
					
				}
				
			}
			
			if($is_template !== false){ //duplicate the slider now, as we just imported the "template"
				if($single_slide !== false){ //add now one Slide to the current Slider
					$mslider = new RevSlider();
					
					//change slide_id to correct, as it currently is just a number beginning from 0 as we did not have a correct slide ID yet.
					$i = 0;
					$changed = false;
					foreach($slider_map as $value){
						if($i == $single_slide['slide_id']){
							$single_slide['slide_id'] = $value;
							$changed = true;
							break;
						}
						$i++;
					}
					
					if($changed){
						$return = $mslider->copySlideToSlider($single_slide);
					}else{
						return(array("success"=>false,"error"=>__('could not find correct Slide to copy, please try again.', REVSLIDER_TEXTDOMAIN),"sliderID"=>$sliderID));
					}
					
				}else{
					$mslider = new RevSlider();
					$title = RevSliderFunctions::getVal($sliderParams, 'title', 'slider1');	
					$talias = $title;
					$ti = 1;
					while($this->isAliasExistsInDB($talias)){ //set a new alias and title if its existing in database
						$talias = $title . $ti;
						$ti++;
					}
					$mslider->duplicateSliderFromData(array('sliderid' => $sliderID, 'title' => $talias));
				}
			}
			
			
		}catch(Exception $e){
			$errorMessage = $e->getMessage();
			return(array("success"=>false,"error"=>$errorMessage,"sliderID"=>$sliderID));
		}
		
		return(array("success"=>true,"sliderID"=>$sliderID));
	}
	
	
	/**
	 * 
	 * update slider from options
	 */
	public function updateSliderFromOptions($options){
		
		$sliderID = RevSliderFunctions::getVal($options, "sliderid");
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		
		$this->createUpdateSliderFromOptions($options,$sliderID);
	}
	
	/**
	 * 
	 * update some params in the slider
	 */
	public function updateParam($arrUpdate){
		$this->validateInited();
		
		$this->arrParams = array_merge($this->arrParams,$arrUpdate);
		$jsonParams = json_encode($this->arrParams);
		$arrUpdateDB = array();
		$arrUpdateDB["params"] = $jsonParams;
		
		$this->db->update(RevSliderGlobals::$table_sliders,$arrUpdateDB,array("id"=>$this->id));
	}
	
	/**
	 * update some settings in the slider
	 */
	public function updateSetting($arrUpdate){
		$this->validateInited();
		
		$this->settings = array_merge($this->settings,$arrUpdate);
		$jsonParams = json_encode($this->settings);
		$arrUpdateDB = array();
		$arrUpdateDB["settings"] = $jsonParams;
		
		$this->db->update(RevSliderGlobals::$table_sliders,$arrUpdateDB,array("id"=>$this->id));
	}
	
	
	/**
	 * 
	 * delete slider from input data
	 */
	public function deleteSliderFromData($data){
		$sliderID = RevSliderFunctions::getVal($data, "sliderid");
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$this->initByID($sliderID);
		
		$this->deleteSlider();
		
		return true;
	}

	
	/**
	 * 
	 * delete slider from input data
	 */
	public function duplicateSliderFromData($data){
		$sliderID = RevSliderFunctions::getVal($data, "sliderid");
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$this->initByID($sliderID);
		$this->duplicateSlider(RevSliderFunctions::getVal($data, "title"));
	}
	
	
	/**
	 * 
	 * duplicate slide from input data
	 */
	public function duplicateSlideFromData($data){
		
		//init the slider
		$sliderID = RevSliderFunctions::getVal($data, "sliderID");
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$this->initByID($sliderID);
		
		//get the slide id
		$slideID = RevSliderFunctions::getVal($data, "slideID");
		RevSliderFunctions::validateNotEmpty($slideID,"Slide ID");
		$newSlideID = $this->duplicateSlide($slideID);
		
		$this->duplicateChildren($slideID, $newSlideID);
		
		return(array($sliderID, $newSlideID));
	}
	
	
	/**
	 * duplicate slide children
	 * @param $slideID
	 */
	private function duplicateChildren($slideID,$newSlideID){
		
		$arrChildren = $this->getArrSlideChildren($slideID);
		
		foreach($arrChildren as $childSlide){
			$childSlideID = $childSlide->getID();
			//duplicate
			$duplicatedSlideID = $this->duplicateSlide($childSlideID);
			
			//update parent id
			$duplicatedSlide = new RevSlide();
			$duplicatedSlide->initByID($duplicatedSlideID);
			$duplicatedSlide->updateParentSlideID($newSlideID);
		}
		
	}
	
	
	/**
	 * copy slide from one Slider to the given Slider ID
	 * @since: 5.0
	 */
	public function copySlideToSlider($data){
		global $wpdb;
		
		$sliderID = intval(RevSliderFunctions::getVal($data, "slider_id"));
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$slideID = intval(RevSliderFunctions::getVal($data, "slide_id"));
		RevSliderFunctions::validateNotEmpty($slideID,"Slide ID");
		
		$tableSliders = $wpdb->prefix . RevSliderGlobals::TABLE_SLIDERS_NAME;
		$tableSlides = $wpdb->prefix . RevSliderGlobals::TABLE_SLIDES_NAME;

		//check if ID exists
		$add_to_slider = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tableSliders WHERE id = %s", $sliderID), ARRAY_A);
		
		if(empty($add_to_slider))
			return __('Slide could not be duplicated', REVSLIDER_TEXTDOMAIN);
		
		//get last slide in slider for the order
		$slide_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tableSlides WHERE slider_id = %s ORDER BY slide_order DESC", $sliderID), ARRAY_A);
		$order = (empty($slide_order)) ? 1 : $slide_order['slide_order'] + 1;
		
		$slide_to_copy = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tableSlides WHERE id = %s", $slideID), ARRAY_A);
		
		if(empty($slide_to_copy))
			return __('Slide could not be duplicated', REVSLIDER_TEXTDOMAIN);
		
		unset($slide_to_copy['id']); //remove the ID of Slide, as it will be a new Slide
		$slide_to_copy['slider_id'] = $sliderID; //set the new Slider ID to the Slide
		$slide_to_copy['slide_order'] = $order; //set the next slide order, to set slide to the end
		
		$response = $wpdb->insert($tableSlides, $slide_to_copy);
		
		if($response === false) return __('Slide could not be copied', REVSLIDER_TEXTDOMAIN);
		
		return true;
	}
	
	
	/**
	 * copy / move slide from data
	 */
	public function copyMoveSlideFromData($data){
		
		$sliderID = RevSliderFunctions::getVal($data, "sliderID");
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$this->initByID($sliderID);

		$targetSliderID = RevSliderFunctions::getVal($data, "targetSliderID");
		RevSliderFunctions::validateNotEmpty($sliderID,"Target Slider ID");
		$this->initByID($sliderID);
		
		if($targetSliderID == $sliderID)
			RevSliderFunctions::throwError("The target slider can't be equal to the source slider");
		
		$slideID = RevSliderFunctions::getVal($data, "slideID");
		RevSliderFunctions::validateNotEmpty($slideID,"Slide ID");
		
		$operation = RevSliderFunctions::getVal($data, "operation");
		
		$this->copyMoveSlide($slideID,$targetSliderID,$operation);
		
		return($sliderID);
	}
	
	
	/**
	 * create a slide from input data
	 */
	public function createSlideFromData($data,$returnSlideID = false){
		
		$sliderID = RevSliderFunctions::getVal($data, "sliderid");
		$obj = RevSliderFunctions::getVal($data, "obj");
		
		RevSliderFunctions::validateNotEmpty($sliderID,"Slider ID");
		$this->initByID($sliderID);
		
		if(is_array($obj)){	//multiple
			foreach($obj as $item){
				$slide = new RevSlide();
				$slideID = $slide->createSlide($sliderID, $item);
			}
			
			return(count($obj));
			
		}else{	//signle
			$urlImage = $obj;
			$slide = new RevSlide();
			$slideID = $slide->createSlide($sliderID, $urlImage);
			if($returnSlideID == true)
				return($slideID);
			else 
				return(1);	//num slides -1 slide created
		}
	}
	
	
	
	/**
	 * update slides order from data
	 */
	public function updateSlidesOrderFromData($data){
		$sliderID = RevSliderFunctions::getVal($data, "sliderID");
		$arrIDs = RevSliderFunctions::getVal($data, "arrIDs");
		RevSliderFunctions::validateNotEmpty($arrIDs,"slides");
		
		$this->initByID($sliderID);
		
		$isFromPosts = $this->isSlidesFromPosts();
		
		foreach($arrIDs as $index=>$slideID){
			
			$order = $index+1;
			
			if($isFromPosts){
				RevSliderFunctionsWP::updatePostOrder($slideID, $order);
			}else{
				
				$arrUpdate = array("slide_order"=>$order);
				$where = array("id"=>$slideID);
				$this->db->update(RevSliderGlobals::$table_slides,$arrUpdate,$where);
			}							
		}//end foreach
		
		//update sortby			
		if($isFromPosts){
			$arrUpdate = array();
			$arrUpdate["post_sortby"] = RevSliderFunctionsWP::SORTBY_MENU_ORDER;
			$this->updateParam($arrUpdate);
		} 
		
	}
	
	/**
	 * 
	 * get the "main" and "settings" arrays, for dealing with the settings.
	 */
	public function getSettingsFields(){
		$this->validateInited();
		
		$arrMain = array();
		$arrMain["title"] = $this->title;
		$arrMain["alias"] = $this->alias;
		
		$arrRespose = array("main"=>$arrMain, "params"=>$this->arrParams);
		
		return($arrRespose);
	}
	
	
	/**
	 * get slides from gallery
	 * force from gallery - get the slide from the gallery only
	 */
	public function getSlides($publishedOnly = false){
		
		$arrSlides = $this->getSlidesFromGallery($publishedOnly);
		
		return($arrSlides);
	}
	
	
	/**
	 * get slides from posts
	 */
	public function getSlidesFromPosts($publishedOnly = false){
		
		$slideTemplates = $this->getSlidesFromGallery($publishedOnly);
		$slideTemplates = RevSliderFunctions::assocToArray($slideTemplates);
		
		if(count($slideTemplates) == 0) return array();
		
		$sourceType = $this->getParam("source_type","gallery");
		switch($sourceType){
			case "posts":
				$arrPosts = $this->getPostsFromCategories($publishedOnly);
			break;
			case "specific_posts":
				$arrPosts = $this->getPostsFromSpecificList();
			break;
			default:
				RevSliderFunctions::throwError("getSlidesFromPosts error: This source type must be from posts.");
			break;
		}
		
		$arrSlides = array();
		
		$templateKey = 0;
		$numTemplates = count($slideTemplates);
		
		foreach($arrPosts as $postData){
			$slideTemplate = clone($slideTemplates[$templateKey]);
			
			//advance the templates
			$templateKey++;
			if($templateKey == $numTemplates){
				$templateKey = 0;
				$slideTemplates = $this->getSlidesFromGallery($publishedOnly); //reset as clone did not work properly
				$slideTemplates = RevSliderFunctions::assocToArray($slideTemplates);
			}

			$slide = new RevSlide();
			$slide->initByPostData($postData, $slideTemplate, $this->id);
			$arrSlides[] = $slide;
		}
		
		$this->arrSlides = $arrSlides;
		
		return($arrSlides);
	}
	
	
	/**
	 * get slides from posts
	 */
	public function getSlidesFromStream($publishedOnly = false){
		
		$slideTemplates = $this->getSlidesFromGallery($publishedOnly);
		$slideTemplates = RevSliderFunctions::assocToArray($slideTemplates);
		
		if(count($slideTemplates) == 0) return array();
		
		$arrPosts = array();
		
		$max_allowed = 999999;
		$sourceType = $this->getParam("source_type","gallery");
		$additions = array('fb_type' => 'album');
		switch($sourceType){
			case "facebook":
				$facebook = new RevSliderFacebook($this->getParam('facebook-transient','1200'));
				//removed due album not working properly, will be added later on again
				//if($this->getParam('facebook-type-source','timeline') == "album"){
				//	$arrPosts = $facebook->get_photo_set_photos($this->getParam('facebook-album'),$this->getParam('facebook-count',10),$this->getParam('facebook-app-id'),$this->getParam('facebook-app-secret'));
				//}else{
					$user_id = $facebook->get_user_from_url($this->getParam('facebook-page-url'));
					$arrPosts = $facebook->get_photo_feed($user_id,$this->getParam('facebook-app-id'),$this->getParam('facebook-app-secret'),$this->getParam('facebook-count',10));
					$additions['fb_type'] = 'timeline'; //SET TEMPORARY TO TIMELINE ALWAYS $this->getParam('facebook-type-source','timeline');
					$additions['fb_user_id'] = $user_id;
				//}
				if(!empty($arrPosts)){
					foreach($arrPosts as $k => $p){
						if(!isset($p->status_type)) continue;
						
						if(in_array($p->status_type, array("wall_post"))) unset($arrPosts[$k]);
					}
				}
				$max_posts = $this->getParam('facebook-count', '25', self::FORCE_NUMERIC);
				$max_allowed = 25;
			break;
			case "twitter":
				$twitter = new RevSliderTwitter($this->getParam('twitter-consumer-key'),$this->getParam('twitter-consumer-secret'),$this->getParam('twitter-access-token'),$this->getParam('twitter-access-secret'),$this->getParam('twitter-transient','1200'));
				$arrPosts = $twitter->get_public_photos($this->getParam('twitter-user-id'),$this->getParam('twitter-include-retweets'),$this->getParam( 'twitter-exclude-replies'),$this->getParam('twitter-count'),$this->getParam('twitter-image-only'));	
				$max_posts = $this->getParam('twitter-count', '500', self::FORCE_NUMERIC);
				$max_allowed = 500;
				$additions['twitter_user'] = $this->getParam('twitter-user-id');
			break;
			case "instagram":
				$instagram = new RevSliderInstagram($this->getParam('instagram-access-token'),$this->getParam('instagram-transient','1200'));
				$search_user_id = $this->getParam('instagram-user-id');
				$arrPosts = $instagram->get_public_photos($search_user_id,$this->getParam('instagram-count'));
				$max_posts = $this->getParam('instagram-count', '33', self::FORCE_NUMERIC);
				$max_allowed = 33;
			break;
			case "flickr":
				$flickr = new RevSliderFlickr($this->getParam('flickr-api-key'),$this->getParam('flickr-transient','1200'));
				switch($this->getParam('flickr-type')){
					case 'publicphotos':
						$user_id = $flickr->get_user_from_url($this->getParam('flickr-user-url'));
						$arrPosts = $flickr->get_public_photos($user_id,$this->getParam('flickr-count'));
					break;
					case 'gallery':
						$gallery_id = $flickr->get_gallery_from_url($this->getParam('flickr-gallery-url'));
						$arrPosts = $flickr->get_gallery_photos($gallery_id,$this->getParam('flickr-count'));
					break;
					case 'group':
						$group_id = $flickr->get_group_from_url($this->getParam('flickr-group-url'));
						$arrPosts = $flickr->get_group_photos($group_id,$this->getParam('flickr-count'));
					break;
					case 'photosets':
						$arrPosts = $flickr->get_photo_set_photos($this->getParam('flickr-photoset'),$this->getParam('flickr-count'));
					break;
				}
				$max_posts = $this->getParam('flickr-count', '99', self::FORCE_NUMERIC);
			break;
			case 'youtube':
				$channel_id = $this->getParam('youtube-channel-id');
				$youtube = new RevSliderYoutube($this->getParam('youtube-api'),$channel_id,$this->getParam('youtube-transient','1200'));
				
				if($this->getParam('youtube-type-source')=="playlist"){
					$arrPosts = $youtube->show_playlist_videos($this->getParam('youtube-playlist'),$this->getParam('youtube-count'));
				}
				else{
					$arrPosts = $youtube->show_channel_videos($this->getParam('youtube-count'));
				}
				$additions['yt_type'] = $this->getParam('youtube-type-source','channel');
				$max_posts = $this->getParam('youtube-count', '25', self::FORCE_NUMERIC);
				$max_allowed = 50;
				break;
			case 'vimeo':
				$vimeo = new RevSliderVimeo($this->getParam('vimeo-transient','1200'));
				$vimeo_type = $this->getParam('vimeo-type-source');
				
				switch ($vimeo_type) {
					case 'user':
						$arrPosts = $vimeo->get_vimeo_videos($vimeo_type,$this->getParam('vimeo-username'));
						break;
					case 'channel':
						$arrPosts = $vimeo->get_vimeo_videos($vimeo_type,$this->getParam('vimeo-channelname'));
						break;
					case 'group':
						$arrPosts = $vimeo->get_vimeo_videos($vimeo_type,$this->getParam('vimeo-groupname'));
						break;
					case 'album':
						$arrPosts = $vimeo->get_vimeo_videos($vimeo_type,$this->getParam('vimeo-albumid'));
						break;
					default:
						break;

				}
				$additions['vim_type'] = $this->getParam('vimeo-type-source','user');
				$max_posts = $this->getParam('vimeo-count', '25', self::FORCE_NUMERIC);
				$max_allowed = 60;
				break;
			default:
				RevSliderFunctions::throwError("getSlidesFromStream error: This source type must be from stream.");
			break;
		}
		
		if($max_posts < 0) $max_posts *= -1;
		
		$arrPosts = apply_filters('revslider_pre_mod_stream_data', $arrPosts, $sourceType, $this->id);
		
		while(count($arrPosts) > $max_posts || count($arrPosts) > $max_allowed){
			array_pop($arrPosts);
		}
		
		$arrPosts = apply_filters('revslider_post_mod_stream_data', $arrPosts, $sourceType, $this->id);
		
		$arrSlides = array();
		
		$templateKey = 0;
		$numTemplates = count($slideTemplates);
		
		if(empty($arrPosts)) RevSliderFunctions::throwError(__('Failed to load Stream', REVSLIDER_TEXTDOMAIN));
		
		foreach($arrPosts as $postData){
			$slideTemplate = $slideTemplates[$templateKey];
			
			//advance the templates
			$templateKey++;
			if($templateKey == $numTemplates)
				$templateKey = 0;
			
			$slide = new RevSlide();
			$slide->initByStreamData($postData, $slideTemplate, $this->id, $sourceType, $additions);
			$arrSlides[] = $slide;
		}
		
		$this->arrSlides = $arrSlides;
		
		return($arrSlides);
	}
	
	
	/**
	 * get slides of the current slider
	 */
	public function getSlidesFromGallery($publishedOnly = false, $allwpml = false){
	
		$this->validateInited();
		
		$arrSlides = array();
		$arrSlideRecords = $this->db->fetch(RevSliderGlobals::$table_slides,"slider_id=".$this->id,"slide_order");
		
		$arrChildren = array();
		
		foreach ($arrSlideRecords as $record){
			$slide = new RevSlide();
			$slide->initByData($record);
			
			$slideID = $slide->getID();
			$arrIdsAssoc[$slideID] = true;

			if($publishedOnly == true){
				$state = $slide->getParam("state", "published");
				if($state == "unpublished"){
					continue;
				}
			}
			
			$parentID = $slide->getParam("parentid","");
			if(!empty($parentID)){
				$lang = $slide->getParam("lang","");
				if(!isset($arrChildren[$parentID]))
					$arrChildren[$parentID] = array();
				$arrChildren[$parentID][] = $slide;
				if(!$allwpml)
					continue;	//skip adding to main list
			}
			
			//init the children array
			$slide->setArrChildren(array());
			
			$arrSlides[$slideID] = $slide;
		}
		
		//add children array to the parent slides
		foreach($arrChildren as $parentID=>$arr){
			if(!isset($arrSlides[$parentID])){
				continue;
			}
			$arrSlides[$parentID]->setArrChildren($arr);
		}
		
		$this->arrSlides = $arrSlides;

		return($arrSlides);
	}
	
	/**
	 * 
	 * get slide id and slide title from gallery
	 */
	public function getArrSlidesFromGalleryShort(){
		$arrSlides = $this->getSlidesFromGallery();
		
		$arrOutput = array();
		$counter = 0;
		foreach($arrSlides as $slide){
			$slideID = $slide->getID();
			$outputName = 'Slide '.$counter;
			$title = $slide->getParam('title','');
			$counter++;
			
			if(!empty($title))
				$outputName .= ' - ('.$title.')';
				
			$arrOutput[$slideID] = $outputName;
		}
		
		return($arrOutput);
	}
	
	
	/**
	 * 
	 * get slides for output
	 * one level only without children
	 */
	public function getSlidesForOutput($publishedOnly = false, $lang = 'all'){
		
		$isSlidesFromPosts = $this->isSlidesFromPosts();
		$isSlidesFromStream = $this->isSlidesFromStream();
		
		
		if(RevSliderWpml::isWpmlExists()){
			global $sitepress;
			$cur_lang = ICL_LANGUAGE_CODE;
			$sitepress->switch_lang($lang);
		}
		
		if($isSlidesFromPosts){
			$arrParentSlides = $this->getSlidesFromPosts($publishedOnly);
		}elseif($isSlidesFromStream !== false){
			$arrParentSlides = $this->getSlidesFromStream($publishedOnly);
		}else{
			$arrParentSlides = $this->getSlides($publishedOnly);
		}
		
		if(RevSliderWpml::isWpmlExists()){ //switch language back
			global $sitepress;
			$sitepress->switch_lang($cur_lang);
		}
		
		if($lang == 'all' || $isSlidesFromPosts || $isSlidesFromStream)
			return($arrParentSlides);
		
		$arrSlides = array();
		foreach($arrParentSlides as $parentSlide){
			$parentLang = $parentSlide->getLang();
			if($parentLang == $lang)
				$arrSlides[] = $parentSlide;
				
			$childAdded = false;
			$arrChildren = $parentSlide->getArrChildren();
			foreach($arrChildren as $child){
				$childLang = $child->getLang();
				if($childLang == $lang){
					$arrSlides[] = $child;
					$childAdded = true;
					break;
				}
			}
			
			if($childAdded == false && $parentLang == "all")
				$arrSlides[] = $parentSlide;
		}
		
		return($arrSlides);
	}
	
	
	/**
	 * 
	 * get array of slide names
	 */
	public function getArrSlideNames(){
		if(empty($this->arrSlides))
			$this->getSlidesFromGallery();
		
		$arrSlideNames = array();

		foreach($this->arrSlides as $number=>$slide){
			$slideID = $slide->getID();
			$filename = $slide->getImageFilename();	
			$slideTitle = $slide->getParam("title","Slide");
			$slideName = $slideTitle;
			if(!empty($filename))
				$slideName .= " ($filename)";
			
			$arrChildrenIDs = $slide->getArrChildrenIDs();
			 
			$arrSlideNames[$slideID] = array("name"=>$slideName,"arrChildrenIDs"=>$arrChildrenIDs,"title"=>$slideTitle);
		}
		return($arrSlideNames);
	}
	
	
	/**
	 * 
	 * get array of slides numbers by id's
	 */
	public function getSlidesNumbersByIDs($publishedOnly = false){
		
		if(empty($this->arrSlides))
			$this->getSlides($publishedOnly);
		
		$arrSlideNumbers = array();
		
		$counter = 0;
		
		if(empty($this->arrSlides)) return $arrSlideNumbers;
		
		foreach($this->arrSlides as $slide){
			$counter++;
			$slideID = $slide->getID();
			$arrSlideNumbers[$slideID] = $counter;				
		}
		return($arrSlideNumbers);
	}
	
	
	/**
	 * 
	 * get slider params for export slider
	 */
	private function getParamsForExport(){
		$exportParams = $this->arrParams;
		
		//modify background image
		$urlImage = RevSliderFunctions::getVal($exportParams, "background_image");
		if(!empty($urlImage))
			$exportParams["background_image"] = $urlImage;
		
		return($exportParams);
	}

	
	/**
	 * 
	 * get slides for export
	 */
	public function getSlidesForExport($useDummy = false){
		$arrSlides = $this->getSlidesFromGallery(false, true);
		$arrSlidesExport = array();
		
		foreach($arrSlides as $slide){
			$slideNew = array();
			$slideNew["id"] = $slide->getID();
			$slideNew["params"] = $slide->getParamsForExport();
			$slideNew["slide_order"] = $slide->getOrder();
			$slideNew["layers"] = $slide->getLayersForExport($useDummy);
			$slideNew["settings"] = $slide->getSettings();
			$arrSlidesExport[] = $slideNew;
		}
		
		return($arrSlidesExport);
	}

	
	/**
	 * 
	 * get slides for export
	 */
	public function getStaticSlideForExport($useDummy = false){
		$arrSlidesExport = array();
		
		$slide = new RevSlide();
		
		$staticID = $slide->getStaticSlideID($this->id);
		if($staticID !== false){
			$slideNew = array();
			$slide->initByStaticID($staticID);
			$slideNew["params"] = $slide->getParamsForExport();
			$slideNew["slide_order"] = $slide->getOrder();
			$slideNew["layers"] = $slide->getLayersForExport($useDummy);
			$slideNew["settings"] = $slide->getSettings();
			$arrSlidesExport[] = $slideNew;
		}
		
		return($arrSlidesExport);
	}
	
	
	/**
	 * get slides number
	 */
	public function getNumSlides($publishedOnly = false){
		
		if($this->arrSlides == null)
			$this->getSlides($publishedOnly);
		
		$numSlides = count($this->arrSlides);
		return($numSlides);
	}
	
	
	/**
	 * get real slides number, from posts, social streams ect.
	 */
	public function getNumRealSlides($publishedOnly = false, $type = 'post'){
		$numSlides = count($this->arrSlides);
		
		switch($type){
			case 'post':
				$this->getSlidesFromPosts($publishedOnly);
				$numSlides = count($this->arrSlides);
			break;
			case 'facebook':
				$numSlides = $this->getParam('facebook-count', count($this->arrSlides));
			break;
			case 'twitter':
				$numSlides = $this->getParam('twitter-count', count($this->arrSlides));
			break;
			case 'instagram':
				$numSlides = $this->getParam('instagram-count', count($this->arrSlides));
			break;
			case 'flickr':
				$numSlides = $this->getParam('flickr-count', count($this->arrSlides));
			break;
			case 'youtube':
				$numSlides = $this->getParam('youtube-count', count($this->arrSlides));
			break;
			case 'vimeo':
				$numSlides = $this->getParam('vimeo-count', count($this->arrSlides));
			break;
		}
		
		return($numSlides);
	}
	
	
	/**
	 * get real slides number, from posts, social streams ect.
	 */
	public function getNumRealStreamSlides($publishedOnly = false){
		
		$this->getSlidesFromStream($publishedOnly);
		
		$numSlides = count($this->arrSlides);
		return($numSlides);
	}
	
	
	/**
	 * get sliders array - function don't belong to the object!
	 */
	public function getArrSliders($orders = false, $templates = 'neither'){
		$order_fav = false;
		if($orders !== false && key($orders) != 'favorite'){
			$order_direction = reset($orders);
			$do_order = key($orders);
		}else{
			$do_order = 'id';
			$order_direction = 'ASC';
			if(is_array($orders) && key($orders) == 'favorite'){
				$order_direction = reset($orders);
				$order_fav = true;
			}
		}
		$where = "`type` != 'template'";
		
		$response = $this->db->fetch(RevSliderGlobals::$table_sliders,$where,$do_order,'',$order_direction);
		
		$arrSliders = array();
		foreach($response as $arrData){
			$slider = new RevSlider();
			$slider->initByDBData($arrData);
			
			/*
			This part needs to stay for backwards compatibility. It is used in the update process from v4x to v5x
			*/
			if($templates === true){
				if($slider->getParam("template","false") == "false") continue;
			}elseif($templates === false){
				if($slider->getParam("template","false") == "true") continue;
			}
			
			$arrSliders[] = $slider;
		}
		
		if($order_fav === true){
			$temp = array();
			$temp_not = array();
			foreach($arrSliders as $key => $slider){
				if($slider->isFavorite()){
					$temp_not[] = $slider;
				}else{
					$temp[] = $slider;
				}
			}
			$arrSliders = array();
			$arrSliders = ($order_direction == 'ASC') ? array_merge($temp, $temp_not) : array_merge($temp_not, $temp);
		}
		
		return($arrSliders);
	}

	
	/**
	 * get array of alias
	 */
	public function getAllSliderAliases(){
		$where = "`type` != 'template'";
		
		$response = $this->db->fetch(RevSliderGlobals::$table_sliders,$where,"id");
		
		$arrAliases = array();
		foreach($response as $arrSlider){
			$arrAliases[] = $arrSlider["alias"];
		}
		
		return($arrAliases);
	}

	/**
	 * get array of alias
	 */
	public function getAllSliderForAdminMenu(){
		$arrSliders = $this->getArrSliders();
		$arrShort = array();
		if(!empty($arrSliders)){
			foreach($arrSliders as $slider){
				$id = $slider->getID();
				$title = $slider->getTitle();
				$alias = $slider->getAlias();
				
				$arrShort[$id] = array('title' => $title, 'alias' => $alias);
			}
		}
		
		return($arrShort);
	}	
	
	
	/**
	 * 
	 * get array of slider id -> title
	 */		
	public function getArrSlidersShort($exceptID = null,$filterType = self::SLIDER_TYPE_ALL){
		$arrSliders = $this->getArrSliders();
		$arrShort = array();
		foreach($arrSliders as $slider){
			$id = $slider->getID();
			$isFromPosts = $slider->isSlidesFromPosts();
			$isTemplate = $slider->getParam("template","false");
			
			//filter by gallery only
			if($filterType == self::SLIDER_TYPE_POSTS && $isFromPosts == false)
				continue;
				
			if($filterType == self::SLIDER_TYPE_GALLERY && $isFromPosts == true)
				continue;
			
			//filter by template type
			if($filterType == self::SLIDER_TYPE_TEMPLATE && $isFromPosts == false)
				continue;
			
			//filter by except
			if(!empty($exceptID) && $exceptID == $id)
				continue;
				
			$title = $slider->getTitle();
			$arrShort[$id] = $title;
		}
		return($arrShort);
	}
	
	/**
	 * 
	 * get array of sliders with slides, short, assoc.
	 */
	public function getArrSlidersWithSlidesShort($filterType = self::SLIDER_TYPE_ALL){
		$arrSliders = self::getArrSlidersShort(null, $filterType);
		
		$output = array();
		foreach($arrSliders as $sliderID=>$sliderName){
			$slider = new RevSlider();
			$slider->initByID($sliderID);
			
			$isFromPosts = $slider->isSlidesFromPosts();
			$isTemplate = $slider->getParam("template","false");
			
			//filter by gallery only
			if($filterType == self::SLIDER_TYPE_POSTS && $isFromPosts == false)
				continue;
				
			if($filterType == self::SLIDER_TYPE_GALLERY && $isFromPosts == true)
				continue;
			
			//filter by template type
			if($filterType == self::SLIDER_TYPE_TEMPLATE && $isFromPosts == false) //$isTemplate == "false")
				continue;
				
			$sliderTitle = $slider->getTitle();
			$arrSlides = $slider->getArrSlidesFromGalleryShort();
			
			foreach($arrSlides as $slideID=>$slideName){
				$output[$slideID] = $sliderName.", ".$slideName;
			}
		}
		
		return($output);
	}
	
	
	/**
	 * 
	 * get max order
	 */
	public function getMaxOrder(){
		$this->validateInited();
		$maxOrder = 0;
		$arrSlideRecords = $this->db->fetch(RevSliderGlobals::$table_slides,"slider_id=".$this->id,"slide_order desc","","limit 1");
		if(empty($arrSlideRecords))
			return($maxOrder);
		$maxOrder = $arrSlideRecords[0]["slide_order"];
		
		return($maxOrder);
	}
	
	/**
	 * 
	 * get setting - start with slide
	 */
	public function getStartWithSlideSetting(){
		
		$numSlides = $this->getNumSlides();
		
		$startWithSlide = $this->getParam("start_with_slide","1");
		if(is_numeric($startWithSlide)){
			$startWithSlide = (int)$startWithSlide - 1;
			if($startWithSlide < 0)
				$startWithSlide = 0;
				
			if($startWithSlide >= $numSlides)
				$startWithSlide = 0;
			
		}else
			$startWithSlide = 0;
		
		return($startWithSlide);
	}
	
	
	/**
	 * return if the slides source is from posts
	 */
	public function isSlidesFromPosts(){
		$this->validateInited();
		$sourceType = $this->getParam("source_type","gallery");
		if($sourceType == "posts" || $sourceType == "specific_posts")
			return(true);
		
		return(false);
	}
	
	
	/**
	 * return if the slides source is from stream
	 */
	public function isSlidesFromStream(){
		$this->validateInited();
		$sourceType = $this->getParam("source_type","gallery");
		if($sourceType != "posts" && $sourceType != "specific_posts" && $sourceType != "gallery")
			return($sourceType);
		
		return(false);
	}
	
	
	/**
	 * 
	 * get posts from categories (by the slider params).
	 */
	private function getPostsFromCategories($publishedOnly = false){
		$this->validateInited();
		
		$catIDs = $this->getParam("post_category");
		$data = RevSliderFunctionsWP::getCatAndTaxData($catIDs);
		
		$taxonomies = $data["tax"];
		$catIDs = $data["cats"];
		
		$sortBy = $this->getParam("post_sortby",self::DEFAULT_POST_SORTBY);
		$sortDir = $this->getParam("posts_sort_direction",self::DEFAULT_POST_SORTDIR);
		$maxPosts = $this->getParam("max_slider_posts","30");
		if(empty($maxPosts) || !is_numeric($maxPosts))
			$maxPosts = -1;
		
		$postTypes = $this->getParam("post_types","any");
			
		//set direction for custom order
		if($sortBy == RevSliderFunctionsWP::SORTBY_MENU_ORDER)
			$sortDir = RevSliderFunctionsWP::ORDER_DIRECTION_ASC;
		
		//Events integration
		$arrAddition = array();
		if($publishedOnly == true)			
			$arrAddition["post_status"] = RevSliderFunctionsWP::STATE_PUBLISHED;
		
		if(RevSliderEventsManager::isEventsExists()){
			
			$filterType = $this->getParam("events_filter",RevSliderEventsManager::DEFAULT_FILTER);				
			$arrAddition = RevSliderEventsManager::getWPQuery($filterType, $sortBy);
		}
		
		$slider_id = $this->getID();
		$arrPosts = RevSliderFunctionsWP::getPostsByCategory($slider_id, $catIDs,$sortBy,$sortDir,$maxPosts,$postTypes,$taxonomies,$arrAddition);
		
		return($arrPosts);
	}  
	
	
	/**
	 * 
	 * get posts from specific posts list
	 */
	private function getPostsFromSpecificList(){
		
		$strPosts = $this->getParam("posts_list","");
		
		$strPosts = apply_filters('revslider_set_posts_list', $strPosts);
		
		$slider_id = $this->getID();
		
		$arrPosts = RevSliderFunctionsWP::getPostsByIDs($strPosts, $slider_id);
		
		return($arrPosts);
	}
	
	/**
	 * update sortby option
	 */
	public function updatePostsSortbyFromData($data){
		
		$sliderID = RevSliderFunctions::getVal($data, "sliderID");
		$sortBy = RevSliderFunctions::getVal($data, "sortby");
		RevSliderFunctions::validateNotEmpty($sortBy,"sortby");
		
		$this->initByID($sliderID);
		$arrUpdate = array();
		$arrUpdate["post_sortby"] = $sortBy;
		
		$this->updateParam($arrUpdate); 
	}

	/**
	 * 
	 * replace image urls
	 */
	public function replaceImageUrlsFromData($data){
		
		$sliderID = RevSliderFunctions::getVal($data, "sliderid");
		$urlFrom = RevSliderFunctions::getVal($data, "url_from");
		RevSliderFunctions::validateNotEmpty($urlFrom,"url from");
		$urlTo = RevSliderFunctions::getVal($data, "url_to");
		
		$this->initByID($sliderID);
		
		$arrSildes = $this->getSlides();
		foreach($arrSildes as $slide){
			$slide->replaceImageUrls($urlFrom, $urlTo);
		}
	}
	
	public function resetSlideSettings($data){
		$sliderID = RevSliderFunctions::getVal($data, "sliderid");
		
		$this->initByID($sliderID);
		
		$arrSildes = $this->getSlides();
		foreach($arrSildes as $slide){
			$slide->reset_slide_values($data);
		}
	}
	
	public static function clear_error_in_string($m){
		return 's:'.strlen($m[2]).':"'.$m[2].'";';
	}
	
}

/**
 * old classname extends new one (old classnames will be obsolete soon)
 * @since: 5.0
 **/
class RevSlider extends RevSliderSlider {}
?>