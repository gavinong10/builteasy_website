<?php

if( !defined( 'ABSPATH') ) exit();

//get input
$slideID = RevSliderFunctions::getGetVar("id");

if($slideID == 'new'){ //add new transparent slide
	$sID = intval(RevSliderFunctions::getGetVar("slider"));
	if($sID > 0){
		$revs = new RevSlider();
		$revs->initByID($sID);
		//check if we already have slides, if yes, go to first
		$arrS = $revs->getSlides(false);
		if(empty($arrS)){
			$slideID = $revs->createSlideFromData(array('sliderid'=>$sID),true);
		}else{
			$slideID = key($arrS);
		}
	}
}

$patternViewSlide = self::getViewUrl("slide","id=[slideid]");	

//init slide object
$slide = new RevSlide();
$slide->initByID($slideID);

$slideParams = $slide->getParams();

$operations = new RevSliderOperations();

//init slider object
$sliderID = $slide->getSliderID();
$slider = new RevSlider();
$slider->initByID($sliderID);
$sliderParams = $slider->getParams();
$arrSlideNames = $slider->getArrSlideNames();

$arrSlides = $slider->getSlides(false);

$arrSliders = $slider->getArrSlidersShort($sliderID);
$selectSliders = RevSliderFunctions::getHTMLSelect($arrSliders,"","id='selectSliders'",true);

//check if slider is template
$sliderTemplate = $slider->getParam("template","false");

//set slide delay
$sliderDelay = $slider->getParam("delay","9000");
$slideDelay = $slide->getParam("delay","");
if(empty($slideDelay))
	$slideDelay = $sliderDelay;

//add tools.min.js
wp_enqueue_script('tp-tools', RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.tools.min.js', array(), RevSliderGlobals::SLIDER_REVISION );

$arrLayers = $slide->getLayers();

//set Layer settings
$cssContent = $operations->getCaptionsContent();

$arrCaptionClasses = $operations->getArrCaptionClasses($cssContent);
//$arrCaptionClassesSorted = $operations->getArrCaptionClasses($cssContent);
$arrCaptionClassesSorted = RevSliderCssParser::get_captions_sorted();

$arrFontFamily = $operations->getArrFontFamilys($slider);
$arrCSS = $operations->getCaptionsContentArray();
$arrButtonClasses = $operations->getButtonClasses();


$arrAnim = $operations->getFullCustomAnimations();
$arrAnimDefaultIn = $operations->getArrAnimations(false);
$arrAnimDefaultOut = $operations->getArrEndAnimations(false);

$arrAnimDefault = array_merge($arrAnimDefaultIn, $arrAnimDefaultOut);

//set various parameters needed for the page
$width = $sliderParams["width"];
$height = $sliderParams["height"];
$imageUrl = $slide->getImageUrl();
$imageID = $slide->getImageID();

$slider_type = $slider->getParam('source_type','gallery');

/**
 * Get Slider params which will be used as default on Slides
 * @since: 5.0
 **/
$def_background_fit = $slider->getParam('def-background_fit', 'cover');
$def_bg_fit_x = $slider->getParam('def-bg_fit_x', '100');
$def_bg_fit_y = $slider->getParam('def-bg_fit_y', '100');
$def_bg_position = $slider->getParam('def-bg_position', 'center center');
$def_bg_position_x = $slider->getParam('def-bg_position_x', '0');
$def_bg_position_y = $slider->getParam('def-bg_position_y', '0');
$def_bg_repeat = $slider->getParam('def-bg_repeat', 'no-repeat');
$def_kenburn_effect = $slider->getParam('def-kenburn_effect', 'off');
$def_kb_start_fit = $slider->getParam('def-kb_start_fit', '100');
$def_kb_easing = $slider->getParam('def-kb_easing', 'Linear.easeNone');
$def_kb_end_fit = $slider->getParam('def-kb_end_fit', '100');
$def_kb_duration = $slider->getParam('def-kb_duration', '10000');
$def_transition = $slider->getParam('def-transition', 'fade');
$def_transition_duration = $slider->getParam('def-transition_duration', 'default');

$def_use_parallax = $slider->getParam('use_parallax', 'on');

/* NEW KEN BURN INPUTS */
$def_kb_start_offset_x = $slider->getParam('def-kb_start_offset_x', '0');
$def_kb_start_offset_y = $slider->getParam('def-kb_start_offset_y', '0');
$def_kb_end_offset_x = $slider->getParam('def-kb_end_offset_x', '0');
$def_kb_end_offset_y = $slider->getParam('def-kb_end_offset_y', '0');
$def_kb_start_rotate = $slider->getParam('def-kb_start_rotate', '0');
$def_kb_end_rotate = $slider->getParam('def-kb_end_rotate', '0');
/* END OF NEW KEN BURN INPUTS */

$imageFilename = $slide->getImageFilename();

$style = "height:".$height."px;"; //

$divLayersWidth = "width:".$width."px;";
$divbgminwidth = "min-width:".$width."px;";
$maxbgwidth = "max-width:".$width."px;";

//set iframe parameters
$iframeWidth = $width+60;
$iframeHeight = $height+50;

$iframeStyle = "width:".$iframeWidth."px;height:".$iframeHeight."px;";

$closeUrl = self::getViewUrl(RevSliderAdmin::VIEW_SLIDES, "id=".$sliderID);

$jsonLayers = RevSliderFunctions::jsonEncodeForClientSide($arrLayers);
$jsonFontFamilys = RevSliderFunctions::jsonEncodeForClientSide($arrFontFamily);
$jsonCaptions = RevSliderFunctions::jsonEncodeForClientSide($arrCaptionClassesSorted);

$arrCssStyles = RevSliderFunctions::jsonEncodeForClientSide($arrCSS);

$arrCustomAnim = RevSliderFunctions::jsonEncodeForClientSide($arrAnim);
$arrCustomAnimDefault = RevSliderFunctions::jsonEncodeForClientSide($arrAnimDefault);

//bg type params
$bgType = RevSliderFunctions::getVal($slideParams, 'background_type', 'image');
$slideBGColor = RevSliderFunctions::getVal($slideParams, 'slide_bg_color', '#E7E7E7');
$divLayersClass = "slide_layers";

$meta_handle = RevSliderFunctions::getVal($slideParams, 'meta_handle','');

$bgFit = RevSliderFunctions::getVal($slideParams, 'bg_fit', $def_background_fit);
$bgFitX = intval(RevSliderFunctions::getVal($slideParams, 'bg_fit_x', $def_bg_fit_x));
$bgFitY = intval(RevSliderFunctions::getVal($slideParams, 'bg_fit_y', $def_bg_fit_y));

$bgPosition = RevSliderFunctions::getVal($slideParams, 'bg_position', $def_bg_position);
$bgPositionX = intval(RevSliderFunctions::getVal($slideParams, 'bg_position_x', $def_bg_position_x));
$bgPositionY = intval(RevSliderFunctions::getVal($slideParams, 'bg_position_y', $def_bg_position_y));

$slide_parallax_level = RevSliderFunctions::getVal($slideParams, 'slide_parallax_level', '-');
$kenburn_effect = RevSliderFunctions::getVal($slideParams, 'kenburn_effect', $def_kenburn_effect);
$kb_duration = RevSliderFunctions::getVal($slideParams, 'kb_duration', $def_kb_duration);
$kb_easing = RevSliderFunctions::getVal($slideParams, 'kb_easing', $def_kb_easing);
$kb_start_fit = RevSliderFunctions::getVal($slideParams, 'kb_start_fit', $def_kb_start_fit);
$kb_end_fit = RevSliderFunctions::getVal($slideParams, 'kb_end_fit', $def_kb_end_fit);

$ext_width = RevSliderFunctions::getVal($slideParams, 'ext_width', '1920');
$ext_height = RevSliderFunctions::getVal($slideParams, 'ext_height', '1080');
$use_parallax = RevSliderFunctions::getVal($slideParams, 'use_parallax', $def_use_parallax);

$slideBGYoutube = RevSliderFunctions::getVal($slideParams, 'slide_bg_youtube', '');
$slideBGVimeo = RevSliderFunctions::getVal($slideParams, 'slide_bg_vimeo', '');
$slideBGhtmlmpeg = RevSliderFunctions::getVal($slideParams, 'slide_bg_html_mpeg', '');
$slideBGhtmlwebm = RevSliderFunctions::getVal($slideParams, 'slide_bg_html_webm', '');
$slideBGhtmlogv = RevSliderFunctions::getVal($slideParams, 'slide_bg_html_ogv', '');

$stream_do_cover = RevSliderFunctions::getVal($slideParams, 'stream_do_cover', 'on');
$stream_do_cover_both = RevSliderFunctions::getVal($slideParams, 'stream_do_cover_both', 'on');

$video_force_cover = RevSliderFunctions::getVal($slideParams, 'video_force_cover', 'on');
$video_dotted_overlay = RevSliderFunctions::getVal($slideParams, 'video_dotted_overlay', 'none');
$video_ratio = RevSliderFunctions::getVal($slideParams, 'video_ratio', 'none');
$video_loop = RevSliderFunctions::getVal($slideParams, 'video_loop', 'none');
$video_nextslide = RevSliderFunctions::getVal($slideParams, 'video_nextslide', 'off');
$video_force_rewind = RevSliderFunctions::getVal($slideParams, 'video_force_rewind', 'on');
$video_speed = RevSliderFunctions::getVal($slideParams, 'video_speed', '1');
$video_mute = RevSliderFunctions::getVal($slideParams, 'video_mute', 'on');
$video_volume = RevSliderFunctions::getVal($slideParams, 'video_volume', '100');
$video_start_at = RevSliderFunctions::getVal($slideParams, 'video_start_at', '');
$video_end_at = RevSliderFunctions::getVal($slideParams, 'video_end_at', '');
$video_arguments = RevSliderFunctions::getVal($slideParams, 'video_arguments', RevSliderGlobals::DEFAULT_YOUTUBE_ARGUMENTS);
$video_arguments_vim = RevSliderFunctions::getVal($slideParams, 'video_arguments_vimeo', RevSliderGlobals::DEFAULT_VIMEO_ARGUMENTS);

/* NEW KEN BURN INPUTS */
$kbStartOffsetX = intval(RevSliderFunctions::getVal($slideParams, 'kb_start_offset_x', $def_kb_start_offset_x));
$kbStartOffsetY = intval(RevSliderFunctions::getVal($slideParams, 'kb_start_offset_y', $def_kb_start_offset_y));
$kbEndOffsetX = intval(RevSliderFunctions::getVal($slideParams, 'kb_end_offset_x', $def_kb_end_offset_x));
$kbEndOffsetY = intval(RevSliderFunctions::getVal($slideParams, 'kb_end_offset_y', $def_kb_end_offset_y));
$kbStartRotate = intval(RevSliderFunctions::getVal($slideParams, 'kb_start_rotate', $def_kb_start_rotate));
$kbEndRotate = intval(RevSliderFunctions::getVal($slideParams, 'kb_end_rotate', $def_kb_start_rotate));
/* END OF NEW KEN BURN INPUTS*/

$bgRepeat = RevSliderFunctions::getVal($slideParams, 'bg_repeat', $def_bg_repeat);

$slideBGExternal = RevSliderFunctions::getVal($slideParams, "slide_bg_external","");

$img_sizes = RevSliderBase::get_all_image_sizes($slider_type);

$bg_image_size = RevSliderFunctions::getVal($slideParams, 'image_source_type', 'full');

$style_wrapper = '';
$class_wrapper = '';


switch($bgType){
	case "trans":
		$divLayersClass = "slide_layers";
		$class_wrapper = "trans_bg";
	break;
	case "solid":
		$style_wrapper .= "background-color:".$slideBGColor.";";
	break;
	case "image":
		switch($slider_type){
			case 'posts':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/post.png';
			break;
			case 'facebook':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/fb.png';
			break;
			case 'twitter':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/tw.png';
			break;
			case 'instagram':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/ig.png';
			break;
			case 'flickr':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/fr.png';
			break;
			case 'youtube':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/yt.png';
			break;
			case 'vimeo':
				$imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/vm.png';
			break;
		}
		$style_wrapper .= "background-image:url('".$imageUrl."');";
		if($bgFit == 'percentage'){
			$style_wrapper .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
		}else{
			$style_wrapper .= "background-size: ".$bgFit.";";
		}
		if($bgPosition == 'percentage'){
			$style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
		}else{
			$style_wrapper .= "background-position: ".$bgPosition.";";
		}
		$style_wrapper .= "background-repeat: ".$bgRepeat.";";
	break;
	case "external":
		$style_wrapper .= "background-image:url('".$slideBGExternal."');";
		if($bgFit == 'percentage'){
			$style_wrapper .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
		}else{
			$style_wrapper .= "background-size: ".$bgFit.";";
		}
		if($bgPosition == 'percentage'){
			$style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
		}else{
			$style_wrapper .= "background-position: ".$bgPosition.";";
		}
		$style_wrapper .= "background-repeat: ".$bgRepeat.";";
	break;
}

$slideTitle = $slide->getParam("title","Slide");
$slideOrder = $slide->getOrder();

//treat multilanguage
$isWpmlExists = RevSliderWpml::isWpmlExists();
$useWpml = $slider->getParam("use_wpml","off");
$wpmlActive = false;

if(!$slide->isStaticSlide()){
	if($isWpmlExists && $useWpml == "on"){
		$wpmlActive = true;
		$parentSlide = $slide->getParentSlide();
		$arrChildLangs = $parentSlide->getArrChildrenLangs();
	}
}

//<!--  load good font -->
$operations = new RevSliderOperations();

$googleFont = $slider->getParam("google_font",array());
if(!empty($googleFont)){
	if(is_array($googleFont)){
		foreach($googleFont as $key => $font){
			echo RevSliderOperations::getCleanFontImport($font);
		}
	}else{
		echo RevSliderOperations::getCleanFontImport($googleFont);
	}
}

if($slide->isStaticSlide() || $slider->isSlidesFromPosts()){ //insert sliderid for preview
	?><input type="hidden" id="sliderid" value="<?php echo $slider->getID(); ?>" /><?php
}

require self::getPathTemplate('template-selector');

?>

<div class="wrap settings_wrap">
	<div class="clear_both"></div>

	<div class="title_line" style="margin-bottom:0px !important;">
		<div id="icon-options-general" class="icon32"></div>		
		<a href="<?php echo RevSliderGlobals::LINK_HELP_SLIDE; ?>" class="button-primary float_right revblue mtop_10 mleft_10" target="_blank"><?php _e("Help",REVSLIDER_TEXTDOMAIN); ?></a>

	</div>

	<div class="rs_breadcrumbs">
		<a class='breadcrumb-button' href='<?php echo self::getViewUrl("sliders");?>'><i class="eg-icon-th-large"></i><?php _e("All Sliders", REVSLIDER_TEXTDOMAIN);?></a>
		<a class='breadcrumb-button' href="<?php echo self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,"id=$sliderID"); ?>"><i class="eg-icon-cog"></i><?php _e('Slider Settings', REVSLIDER_TEXTDOMAIN);?></a>
		<a class='breadcrumb-button selected' href="#"><i class="eg-icon-pencil-2"></i><?php _e('Slide Editor ', REVSLIDER_TEXTDOMAIN);?>"<?php echo ' '.$slider->getParam("title",""); ?>"</a>
		<div class="tp-clearfix"></div>
	</div>


	<?php
	require self::getPathTemplate('slide-selector');
	
	$useWpml = $slider->getParam("use_wpml","off");
	
	if($wpmlActive == true && $useWpml == 'on'){
		require self::getPathTemplate('wpml-selector');
	}
	
	if(!$slide->isStaticSlide()){
		require self::getPathTemplate('slide-general-settings');
	}
	
	$operations = new RevSliderOperations();

	$settings = $slide->getSettings();
	
	$enable_custom_size_notebook = $slider->getParam('enable_custom_size_notebook','off');
	$enable_custom_size_tablet = $slider->getParam('enable_custom_size_tablet','off');
	$enable_custom_size_iphone = $slider->getParam('enable_custom_size_iphone','off');
	
	$adv_resp_sizes = ($enable_custom_size_notebook == 'on' || $enable_custom_size_tablet == 'on' || $enable_custom_size_iphone == 'on') ? true : false;
	?>

	<div id="jqueryui_error_message" class="unite_error_message" style="display:none;">
		<?php _e("<b>Warning!!! </b>The jquery ui javascript include that is loaded by some of the plugins are custom made and not contain needed components like 'autocomplete' or 'draggable' function.
		Without those functions the editor may not work correctly. Please remove those custom jquery ui includes in order the editor will work correctly.", REVSLIDER_TEXTDOMAIN); ?>
	</div>
	
	<div class="edit_slide_wrapper<?php echo ($slide->isStaticSlide()) ? ' rev_static_layers' : ''; ?>">
		<?php
		require self::getPathTemplate('slide-stage');
		?>
		<div style="width:100%;clear:both;height:20px"></div>

		<div id="dialog_insert_icon" class="dialog_insert_icon" title="Insert Icon" style="display:none;"></div>

		

		<div id="dialog_template_insert" class="dialog_template_help" title="<?php _e('Insert Meta',REVSLIDER_TEXTDOMAIN) ?>" style="display:none;">
			<?php
			switch($slider_type){
				case 'posts':
				case 'specific_posts':
				case 'woocommerce':
					?>
					<b><?php _e('Post Replace Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('meta:somemegatag')">{{meta:somemegatag}}</a></td><td><?php _e("Any custom meta tag",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e("Post Title",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('excerpt')">{{excerpt}}</a></td><td><?php _e("Post Excerpt",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('alias')">{{alias}}</a></td><td><?php _e("Post Alias",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e("Post content",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e("The link to the post",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date')">{{date}}</a></td><td><?php _e("Date created",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date_modified')">{{date_modified}}</a></td><td><?php _e("Date modified",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('author_name')">{{author_name}}</a></td><td><?php _e("Author name",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('num_comments')">{{num_comments}}</a></td><td><?php _e("Number of comments",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('catlist')">{{catlist}}</a></td><td><?php _e("List of categories with links",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('taglist')">{{taglist}}</a></td><td><?php _e("List of tags with links",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('featured_image_url_<?php echo $img_handle; ?>')">{{featured_image_url_<?php echo $img_handle; ?>}}</a></td><td><?php _e("Featured Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('featured_image_<?php echo $img_handle; ?>')">{{featured_image_<?php echo $img_handle; ?>}}</a></td><td><?php _e("Featured Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php if(RevSliderEventsManager::isEventsExists()){ ?>
						<br><br>
						
						<b><?php _e('Events Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
						<table class="table_template_help">
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_start_date')">{{event_start_date}}</a></td><td><?php _e("Event start date",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_end_date')">{{event_end_date}}</a></td><td><?php _e("Event end date",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_start_time')">{{event_start_time}}</a></td><td><?php _e("Event start time",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_end_time')">{{event_end_time}}</a></td><td><?php _e("Event end time",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_event_id')">{{event_event_id}}</a></td><td><?php _e("Event ID",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_name')">{{event_location_name}}</a></td><td><?php _e("Event location name",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_slug%')">{{event_location_slug}}</a></td><td><?php _e("Event location slug",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_address')">{{event_location_address}}</a></td><td><?php _e("Event location address",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_town')">{{event_location_town}}</a></td><td><?php _e("Event location town",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_state')">{{event_location_state}}</a></td><td><?php _e("Event location state",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_postcode')">{{event_location_postcode}}</a></td><td><?php _e("Event location postcode",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_region')">{{event_location_region}}</a></td><td><?php _e("Event location region",REVSLIDER_TEXTDOMAIN) ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('event_location_country')">{{event_location_country}}</a></td><td><?php _e("Event location country",REVSLIDER_TEXTDOMAIN) ?></td></tr>
						</table>
					<?php } ?>
					<?php
				break;
				case 'flickr':
					?>
					<b><?php _e('Flickr Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e("Post Title",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e("Post content",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e("The link to the post",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date')">{{date}}</a></td><td><?php _e("Date created",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('author_name')">{{author_name}}</a></td><td><?php _e('Username',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('views')">{{views}}</a></td><td><?php _e('Views',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_url_<?php echo sanitize_title($img_handle); ?>')">{{image_url_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_<?php echo sanitize_title($img_handle); ?>')">{{image_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php
				break;
				case 'instagram':
					?>
					<b><?php _e('Instagram Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e("Title",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e("Content",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e("Link",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date')">{{date}}</a></td><td><?php _e("Date created",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('author_name')">{{author_name}}</a></td><td><?php _e('Username',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('likes')">{{likes}}</a></td><td><?php _e('Number of Likes',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('num_comments')">{{num_comments}}</a></td><td><?php _e('Number of Comments',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_url_<?php echo sanitize_title($img_handle); ?>')">{{image_url_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_<?php echo sanitize_title($img_handle); ?>')">{{image_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php
				break;
				case 'twitter':
					?>
					<b><?php _e('Twitter Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e('Title',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e('Content',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e("Link",REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date_published')">{{date_published}}</a></td><td><?php _e('Pulbishing Date',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('author_name')">{{author_name}}</a></td><td><?php _e('Username',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('retweet_count')">{{retweet_count}}</a></td><td><?php _e('Retweet Count',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('favorite_count')">{{favorite_count}}</a></td><td><?php _e('Favorite Count',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_url_<?php echo sanitize_title($img_handle); ?>')">{{image_url_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_<?php echo sanitize_title($img_handle); ?>')">{{image_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php
				break;
				case 'facebook':
					?>
					<b><?php _e('Facebook Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e('Title',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e('Content',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e('Link',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date_published')">{{date_published}}</a></td><td><?php _e('Pulbishing Date',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date_published')">{{date_modified}}</a></td><td><?php _e('Last Modify Date',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('author_name')">{{author_name}}</a></td><td><?php _e('Username',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('likes')">{{likes}}</a></td><td><?php _e('Number of Likes',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_url_<?php echo sanitize_title($img_handle); ?>')">{{image_url_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_<?php echo sanitize_title($img_handle); ?>')">{{image_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php
				break;
				case 'youtube':
					?>
					<b><?php _e('YouTube Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e('Title',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('excerpt')">{{excerpt}}</a></td><td><?php _e('Excerpt',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e('Content',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date_published')">{{date_published}}</a></td><td><?php _e('Pulbishing Date',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e('Link',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_url_<?php echo sanitize_title($img_handle); ?>')">{{image_url_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_<?php echo sanitize_title($img_handle); ?>')">{{image_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php
				break;
				case 'vimeo':
					?>
					<b><?php _e('Vimeo Placeholders:', REVSLIDER_TEXTDOMAIN) ?></b>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('title')">{{title}}</a></td><td><?php _e('Title',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('excerpt')">{{excerpt}}</a></td><td><?php _e('Excerpt',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('content')">{{content}}</a></td><td><?php _e('Content',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('link')">{{link}}</a></td><td><?php _e('The link to the post',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('date_published')">{{date_published}}</a></td><td><?php _e('Pulbishing Date',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('author_name')">{{author_name}}</a></td><td><?php _e('Username',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('likes')">{{likes}}</a></td><td><?php _e('Number of Likes',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('views')">{{views}}</a></td><td><?php _e('Number of Views',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRev.insertTemplate('num_comments')">{{num_comments}}</a></td><td><?php _e('Number of Comments',REVSLIDER_TEXTDOMAIN); ?></td></tr>
						<?php
						foreach($img_sizes as $img_handle => $img_name){
							?>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_url_<?php echo sanitize_title($img_handle); ?>')">{{image_url_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image URL",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRev.insertTemplate('image_<?php echo sanitize_title($img_handle); ?>')">{{image_<?php echo sanitize_title($img_handle); ?>}}</a></td><td><?php _e("Image &lt;img /&gt;",REVSLIDER_TEXTDOMAIN); echo ' '.$img_name; ?></td></tr>
							<?php
						}
						?>
					</table>
					<?php
				break;
			}
			?>
		</div>

		<div id="dialog_advanced_css" class="dialog_advanced_css" title="<?php _e('Advanced CSS', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
			<div style="display: none;"><span id="rev-example-style-layer">example</span></div>
			<div class="first-css-area">
				<span class="advanced-css-title" style="background:#e67e22"><?php _e('Style from Options', REVSLIDER_TEXTDOMAIN); ?><span style="margin-left:15px;font-size:11px;font-style:italic">(<?php _e('Editable via Option Fields, Saved in the Class:', REVSLIDER_TEXTDOMAIN); ?><span class="current-advance-edited-class"></span>)</span></span>
				<textarea id="textarea_template_css_editor_uneditable" rows="20" cols="81" disabled="disabled"></textarea>
			</div>
			<div class="second-css-area">
				<span class="advanced-css-title"><?php _e('Additional Custom Styling', REVSLIDER_TEXTDOMAIN); ?><span style="margin-left:15px;font-size:11px;font-style:italic">(<?php _e('Appended in the Class:', REVSLIDER_TEXTDOMAIN); ?><span class="current-advance-edited-class"></span>)</span></span>
				<textarea id="textarea_advanced_css_editor" rows="20" cols="81"></textarea>
			</div>
		</div>
		
		<div id="dialog_save_as_css" class="dialog_save_as_css" title="<?php _e('Save As', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php _e('Save As:', REVSLIDER_TEXTDOMAIN); ?></span><input id="rs-save-as-css" type="text" name="rs-save-as-css" value="" />
			</div>
		</div>
		 
		<div id="dialog_rename_css" class="dialog_rename_css" title="<?php _e('Rename CSS', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php _e('Rename to:', REVSLIDER_TEXTDOMAIN); ?></span><input id="rs-rename-css" type="text" name="rs-rename-css" value="" />
			</div>
		</div>
		 
		<div id="dialog_advanced_layer_css" class="dialog_advanced_layer_css" title="<?php _e('Layer Inline CSS', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
			<div class="first-css-area">
				<span class="advanced-css-title" style="background:#e67e22"><?php _e('Advanced Custom Styling', REVSLIDER_TEXTDOMAIN); ?><span style="margin-left:15px;font-size:11px;font-style:italic">(<?php _e('Appended Inline to the Layer Markup', REVSLIDER_TEXTDOMAIN); ?>)</span></span>
				<textarea id="textarea_template_css_editor_layer" name="textarea_template_css_editor_layer"></textarea>
			</div>
		</div>
		
		<div id="dialog_save_as_animation" class="dialog_save_as_animation" title="<?php _e('Save As', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php _e('Save As:', REVSLIDER_TEXTDOMAIN); ?></span><input id="rs-save-as-animation" type="text" name="rs-save-as-animation" value="" />
			</div>
		</div>
		
		<div id="dialog_save_animation" class="dialog_save_animation" title="<?php _e('Save Under', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php _e('Save Under:', REVSLIDER_TEXTDOMAIN); ?></span><input id="rs-save-under-animation" type="text" name="rs-save-under-animation" value="" />
			</div>
		</div>
		
		<script type="text/javascript">

			<?php
			$icon_sets = RevSliderBase::get_icon_sets();
			$sets = array();
			if(!empty($icon_sets)){
				$sets = implode("','", $icon_sets);
			}
			?>

			 var rs_icon_sets = new Array('<?php echo $sets; ?>');

			jQuery(document).ready(function() {
				<?php if(!empty($jsonLayers)){ ?>
					//set init layers object
					UniteLayersRev.setInitLayersJson(<?php echo $jsonLayers?>);
				<?php } ?>

				<?php
				if($slide->isStaticSlide()){
					$arrayDemoLayers = array();
					$arrayDemoSettings = array();
					if(!empty($all_slides) && is_array($all_slides)){
						foreach($all_slides as $cSlide){
							$arrayDemoLayers[$cSlide->getID()] = $cSlide->getLayers();
							$arrayDemoSettings[$cSlide->getID()] = $cSlide->getParams();
						}
					}
					$jsonDemoLayers = RevSliderFunctions::jsonEncodeForClientSide($arrayDemoLayers);
					$jsonDemoSettings = RevSliderFunctions::jsonEncodeForClientSide($arrayDemoSettings);
					?>
					//set init demo layers object
					UniteLayersRev.setInitDemoLayersJson(<?php echo $jsonDemoLayers; ?>);
					UniteLayersRev.setInitDemoSettingsJson(<?php echo $jsonDemoSettings; ?>);
					<?php
				} ?>

				<?php if(!empty($jsonCaptions)){ ?>
				UniteLayersRev.setInitCaptionClasses(<?php echo $jsonCaptions; ?>);
				<?php } ?>

				<?php if(!empty($arrCustomAnim)){ ?>
				UniteLayersRev.setInitLayerAnim(<?php echo $arrCustomAnim; ?>);
				<?php } ?>

				<?php if(!empty($arrCustomAnimDefault)){ ?>
				UniteLayersRev.setInitLayerAnimsDefault(<?php echo $arrCustomAnimDefault; ?>);
				<?php } ?>

				<?php if(!empty($jsonFontFamilys)){ ?>
				UniteLayersRev.setInitFontTypes(<?php echo $jsonFontFamilys; ?>);
				<?php } ?>

				<?php if(!empty($arrCssStyles)){ ?>
				UniteCssEditorRev.setInitCssStyles(<?php echo $arrCssStyles; ?>);
				<?php } ?>

				<?php
				$trans_sizes = RevSliderFunctions::jsonEncodeForClientSide($slide->translateIntoSizes());
				?>
				UniteLayersRev.setInitTransSetting(<?php echo $trans_sizes; ?>);

				UniteLayersRev.init("<?php echo $slideDelay; ?>");

				UniteCssEditorRev.init();

				RevSliderAdmin.initGlobalStyles();

				RevSliderAdmin.initLayerPreview();

				RevSliderAdmin.setStaticCssCaptionsUrl('<?php echo RevSliderGlobals::$urlStaticCaptionsCSS; ?>');

				/* var reproduce;
				jQuery(window).resize(function() {
					clearTimeout(reproduce);
					reproduce = setTimeout(function() {
						UniteLayersRev.refreshGridSize();
					},100);
				});*/

				<?php if($kenburn_effect == 'on'){ ?>
				jQuery('input[name="kenburn_effect"]:checked').change();
				<?php } ?>


				// DRAW  HORIZONTAL AND VERTICAL LINEAR
				var horl = jQuery('#hor-css-linear .linear-texts'),
					verl = jQuery('#ver-css-linear .linear-texts'),
					maintimer = jQuery('#mastertimer-linear .linear-texts'),
					mw = "<?php echo $tempwidth_jq; ?>";
					mw = parseInt(mw.split(":")[1],0);

				for (var i=-600;i<mw;i=i+100) {
					if (mw-i<100)
						horl.append('<li style="width:'+(mw-i)+'px"><span>'+i+'</span></li>');
					else
						horl.append('<li><span>'+i+'</span></li>');
				}

				for (var i=0;i<2000;i=i+100) {
					verl.append('<li><span>'+i+'</span></li>');
				}

				for (var i=0;i<160;i=i+1) {
					var txt = i+"s";

					maintimer.append('<li><span>'+txt+'</span></li>');
				}

				// SHIFT RULERS and TEXTS and HELP LINES//
				function horRuler() {
					var dl = jQuery('#divLayers'),
						l = parseInt(dl.offset().left,0) - parseInt(jQuery('#thelayer-editor-wrapper').offset().left,0);
					jQuery('#hor-css-linear').css({backgroundPosition:(l)+"px 50%"});
					jQuery('#hor-css-linear .linear-texts').css({left:(l-595)+"px"});
					jQuery('#hor-css-linear .helplines-offsetcontainer').css({left:(l)+"px"});

					jQuery('#ver-css-linear .helplines').css({left:"-15px"}).width(jQuery('#thelayer-editor-wrapper').outerWidth(true)-35);
					jQuery('#hor-css-linear .helplines').css({top:"-15px"}).height(jQuery('#thelayer-editor-wrapper').outerHeight(true)-41);
				}

				horRuler();


				jQuery('.my-color-field').wpColorPicker({
					palettes:false,
					height:250,

					border:false,
				    change:function(event,ui) {
				    	switch (jQuery(event.target).attr('name')) {
							case "adbutton-color-1":
							case "adbutton-color-2":
							case "adbutton-border-color":
								setExampleButtons();
							break;

							case "adshape-color-1":
							case "adshape-color-2":
							case "adshape-border-color":							
								setExampleShape();
							break;
							case "bg_color":
								var bgColor = jQuery("#slide_bg_color").val();
								jQuery("#divbgholder").css("background-color",bgColor);
								jQuery('.slotholder .tp-bgimg.defaultimg').css({backgroundColor:bgColor});
							break;
						}		

						if (jQuery('.layer_selected.slide_layer').length>0) {
							jQuery(event.target).blur().focus();
							//jQuery('#style_form_wrapper').trigger("colorchanged");
						}

					},
					clear:function(event,ui) {
						if (jQuery('.layer_selected.slide_layer').length>0) {
							var inp = jQuery(event.target).closest('.wp-picker-input-wrap').find('.my-color-field');
							inp.val("transparent").blur().focus();
							//jQuery('#style_form_wrapper').trigger("colorchanged");
						}
					}
								
				});

				jQuery('.adb-input').on("change blur focus",setExampleButtons);
				jQuery('.ads-input, input[name="shape_fullwidth"], input[name="shape_fullheight"]').on("change blur focus",setExampleShape);
				jQuery('.ui-autocomplete').on('click',setExampleButtons);

				jQuery('.wp-color-result').on("click",function() {

					if (jQuery(this).hasClass("wp-picker-open"))
						jQuery(this).closest('.wp-picker-container').addClass("pickerisopen");
					else
						jQuery(this).closest('.wp-picker-container').removeClass("pickerisopen");
				});

				jQuery("body").click(function(event) {
					jQuery('.wp-picker-container.pickerisopen').removeClass("pickerisopen");
				})

				// WINDOW RESIZE AND SCROLL EVENT SHOULD REDRAW RULERS
				jQuery(window).resize(horRuler);
				jQuery('#divLayers-wrapper').on('scroll',horRuler);


				jQuery('#toggle-idle-hover .icon-stylehover').click(function() {
					var bt = jQuery('#toggle-idle-hover');
					bt.removeClass("idleisselected").addClass("hoverisselected");
					jQuery('#tp-idle-state-advanced-style').hide();
					jQuery('#tp-hover-state-advanced-style').show();
				});

				jQuery('#toggle-idle-hover .icon-styleidle').click(function() {
					var bt = jQuery('#toggle-idle-hover');
					bt.addClass("idleisselected").removeClass("hoverisselected");
					jQuery('#tp-idle-state-advanced-style').show();
					jQuery('#tp-hover-state-advanced-style').hide();
				});


				jQuery('input[name="hover_allow"]').on("change",function() {
					if (jQuery(this).attr("checked")=="checked") {
						jQuery('#idle-hover-swapper').show();
					} else {
						jQuery('#idle-hover-swapper').hide();
					}
				});


				// HIDE /SHOW  INNER SAVE,SAVE AS ETC..
				jQuery('.clicktoshowmoresub').click(function() {
					jQuery(this).find('.clicktoshowmoresub_inner').show();
				});

				jQuery('.clicktoshowmoresub').on('mouseleave',function() {
					jQuery(this).find('.clicktoshowmoresub_inner').hide();
				});
				
				//arrowRepeater();
				function arrowRepeater() {
					var tw = new punchgs.TimelineLite();
					tw.add(punchgs.TweenLite.from(jQuery('.animatemyarrow'),0.5,{x:-10,opacity:0}),0);
					tw.add(punchgs.TweenLite.to(jQuery('.animatemyarrow'),0.5,{x:10,opacity:0}),0.5);
					
					tw.play(0);
					tw.eventCallback("onComplete",function() {
						tw.restart();
					})
				}
				
				RevSliderSettings.createModernOnOff();

			});

		</script>

	

		<?php
		if(!$slide->isStaticSlide()){
		?>
<!--			<a href="javascript:void(0)" id="button_save_slide" class="revgreen button-primary"><div class="updateicon"></div><i class="rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;background-repeat: no-repeat;margin-right:5px;"></i><?php _e("Save Slide",REVSLIDER_TEXTDOMAIN); ?></a>

-->
		<?php }else{ ?>
<!--			<a href="javascript:void(0)" id="button_save_static_slide" class="revgreen button-primary"><div class="updateicon"></div><i class="revicon-arrows-ccw"></i><?php _e("Update Static Layers",REVSLIDER_TEXTDOMAIN); ?></a>

-->
		<?php } ?>
<!--		<span id="loader_update" class="loader_round" style="display:none;"><?php _e("updating",REVSLIDER_TEXTDOMAIN); ?>...</span>
		<span id="update_slide_success" class="success_message" class="display:none;"></span>
		<a href="<?php echo self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,"id=$sliderID"); ?>" class="button-primary revblue"><i class="revicon-cog"></i><?php _e("Slider Settings",REVSLIDER_TEXTDOMAIN); ?></a>
		<a id="button_close_slide" href="<?php echo $closeUrl?>" class="button-primary revyellow"><div class="closeicon"></div><i class="revicon-list-add"></i><?php _e("Slides Overview",REVSLIDER_TEXTDOMAIN); ?></a>
-->
		<?php
		if(!$slide->isStaticSlide()){
		?>
<!--		<a href="javascript:void(0)" id="button_delete_slide" class="button-primary revred" original-title=""><i class="revicon-trash"></i><?php _e("Delete Slide",REVSLIDER_TEXTDOMAIN); ?></a>
	-->
		<?php } ?>
	</div>

<div class="vert_sap"></div>

<!-- FIXED TOOLBAR ON THE RIGHT SIDE -->
<div class="rs-mini-toolbar">
	<?php
	if(!$slide->isStaticSlide()){
		$savebtnid="button_save_slide-tb";
		$prevbtn = "button_preview_slide-tb";
		if($slider->isSlidesFromPosts()){
			$prevbtn = "button_preview_slider-tb";
		}
	}else{
		$savebtnid="button_save_static_slide-tb";
		$prevbtn = "button_preview_slider-tb";
	}
	?>
	<div class="rs-toolbar-savebtn">
		<a class='button-primary revgreen' href='javascript:void(0)' id="<?php echo $savebtnid; ?>" ><i class="rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;background-repeat: no-repeat;"></i><?php _e("Save Slide",REVSLIDER_TEXTDOMAIN); ?></a>
	</div>
	
	<div class="rs-toolbar-cssbtn">
		<a class='button-primary revpurple' href='javascript:void(0)' id='button_edit_css_global'><i class="">&lt;/&gt;</i><?php _e("CSS Global",REVSLIDER_TEXTDOMAIN); ?></a>
	</div>


	<div class="rs-toolbar-slides">
		<?php
		$slider_url = ($sliderTemplate == 'true') ? RevSliderAdmin::VIEW_SLIDER_TEMPLATE : RevSliderAdmin::VIEW_SLIDER;
		?>
		<a class="button-primary revblue" href="<?php echo self::getViewUrl($slider_url,"id=$sliderID"); ?>" id="link_edit_slides_t"><i class="revicon-cog"></i><?php _e("Slider Settings",REVSLIDER_TEXTDOMAIN); ?> </a>
		
	</div>
	<div class="rs-toolbar-preview">
		<a class="button-primary revgray" href="javascript:void(0)"  id="<?php echo $prevbtn; ?>" ><i class="revicon-search-1"></i><?php _e("Preview",REVSLIDER_TEXTDOMAIN); ?></a>
	</div>
	
</div>


<div id="dialog_rename_animation" class="dialog_rename_animation" title="<?php _e('Rename Animation', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
	<div style="margin-top:14px">
		<span style="margin-right:15px"><?php _e('Rename to:', REVSLIDER_TEXTDOMAIN); ?></span><input id="rs-rename-animation" type="text" name="rs-rename-animation" value="" />
	</div>
</div>

<?php
if($slide->isStaticSlide()){
	$slideID = $slide->getID();
}

$mslide_list = array();
foreach($arrSlides as $at_slide) {
	$mslID = $at_slide->getID();
	if($mslID == $slideID) continue;
	
	$mslide_list[] = array($mslID => $at_slide->getParam('title', 'Slide'));
}
$mslide_list = RevSliderFunctions::jsonEncodeForClientSide($mslide_list);

?>
<script type="text/javascript">
	var g_patternViewSlide = '<?php echo $patternViewSlide; ?>';

	
	var g_messageDeleteSlide = "<?php _e("Delete this slide?",REVSLIDER_TEXTDOMAIN); ?>";
	jQuery(document).ready(function(){
		RevSliderAdmin.initEditSlideView(<?php echo $slideID; ?>, <?php echo $sliderID; ?>, <?php echo ($slide->isStaticSlide()) ? 'true' : 'false'; ?>);
		
		UniteLayersRev.setInitSlideIds(<?php echo $mslide_list; ?>);
	});
	var curSlideID = <?php echo $slideID; ?>;
</script>

<?php
require self::getPathTemplate("../system/dialog-copy-move");
?>
