<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */
 
if( !defined( 'ABSPATH') ) exit();

class RevSliderOperations extends RevSliderElementsBase{


	/**
	 * get button classes
	 */
	public function getButtonClasses(){

		$arrButtons = array(
			"red"=>"Red Button",
			"green"=>"Green Button",
			"blue"=>"Blue Button",
			"orange"=>"Orange Button",
			"darkgrey"=>"Darkgrey Button",
			"lightgrey"=>"Lightgrey Button",
		);

		return($arrButtons);
	}


	/**
	 * get easing functions array
	 */
	public function getArrEasing(){ //true

		$arrEasing = array(
			"Linear.easeNone" => "Linear.easeNone",
			"Power0.easeIn" => "Power0.easeIn  (linear)",
			"Power0.easeInOut" => "Power0.easeInOut  (linear)",
			"Power0.easeOut" => "Power0.easeOut  (linear)",
			"Power1.easeIn" => "Power1.easeIn",
			"Power1.easeInOut" => "Power1.easeInOut",
			"Power1.easeOut" => "Power1.easeOut",
			"Power2.easeIn" => "Power2.easeIn",
			"Power2.easeInOut" => "Power2.easeInOut",
			"Power2.easeOut" => "Power2.easeOut",
			"Power3.easeIn" => "Power3.easeIn",
			"Power3.easeInOut" => "Power3.easeInOut",
			"Power3.easeOut" => "Power3.easeOut",
			"Power4.easeIn" => "Power4.easeIn",
			"Power4.easeInOut" => "Power4.easeInOut",
			"Power4.easeOut" => "Power4.easeOut",
			"Quad.easeIn" => "Quad.easeIn  (same as Power1.easeIn)",
			"Quad.easeInOut" => "Quad.easeInOut  (same as Power1.easeInOut)",
			"Quad.easeOut" => "Quad.easeOut  (same as Power1.easeOut)",
			"Cubic.easeIn" => "Cubic.easeIn  (same as Power2.easeIn)",
			"Cubic.easeInOut" => "Cubic.easeInOut  (same as Power2.easeInOut)",
			"Cubic.easeOut" => "Cubic.easeOut  (same as Power2.easeOut)",
			"Quart.easeIn" => "Quart.easeIn  (same as Power3.easeIn)",
			"Quart.easeInOut" => "Quart.easeInOut  (same as Power3.easeInOut)",
			"Quart.easeOut" => "Quart.easeOut  (same as Power3.easeOut)",
			"Quint.easeIn" => "Quint.easeIn  (same as Power4.easeIn)",
			"Quint.easeInOut" => "Quint.easeInOut  (same as Power4.easeInOut)",
			"Quint.easeOut" => "Quint.easeOut  (same as Power4.easeOut)",
			"Strong.easeIn" => "Strong.easeIn  (same as Power4.easeIn)",
			"Strong.easeInOut" => "Strong.easeInOut  (same as Power4.easeInOut)",
			"Strong.easeOut" => "Strong.easeOut  (same as Power4.easeOut)",
			"Back.easeIn" => "Back.easeIn",
			"Back.easeInOut" => "Back.easeInOut",
			"Back.easeOut" => "Back.easeOut",
			"Bounce.easeIn" => "Bounce.easeIn",
			"Bounce.easeInOut" => "Bounce.easeInOut",
			"Bounce.easeOut" => "Bounce.easeOut",
			"Circ.easeIn" => "Circ.easeIn",
			"Circ.easeInOut" => "Circ.easeInOut",
			"Circ.easeOut" => "Circ.easeOut",
			"Elastic.easeIn" => "Elastic.easeIn",
			"Elastic.easeInOut" => "Elastic.easeInOut",
			"Elastic.easeOut" => "Elastic.easeOut",
			"Expo.easeIn" => "Expo.easeIn",
			"Expo.easeInOut" => "Expo.easeInOut",
			"Expo.easeOut" => "Expo.easeOut",
			"Sine.easeIn" => "Sine.easeIn",
			"Sine.easeInOut" => "Sine.easeInOut",
			"Sine.easeOut" => "Sine.easeOut",
			"SlowMo.ease" => "SlowMo.ease",
			//add old easings //From here on display none
			"easeOutBack" => "easeOutBack",
			"easeInQuad" => "easeInQuad",
			"easeOutQuad" => "easeOutQuad",
			"easeInOutQuad" => "easeInOutQuad",
			"easeInCubic" => "easeInCubic",
			"easeOutCubic" => "easeOutCubic",
			"easeInOutCubic" => "easeInOutCubic",
			"easeInQuart" => "easeInQuart",
			"easeOutQuart" => "easeOutQuart",
			"easeInOutQuart" => "easeInOutQuart",
			"easeInQuint" => "easeInQuint",
			"easeOutQuint" => "easeOutQuint",
			"easeInOutQuint" => "easeInOutQuint",
			"easeInSine" => "easeInSine",
			"easeOutSine" => "easeOutSine",
			"easeInOutSine" => "easeInOutSine",
			"easeInExpo" => "easeInExpo",
			"easeOutExpo" => "easeOutExpo",
			"easeInOutExpo" => "easeInOutExpo",
			"easeInCirc" => "easeInCirc",
			"easeOutCirc" => "easeOutCirc",
			"easeInOutCirc" => "easeInOutCirc",
			"easeInElastic" => "easeInElastic",
			"easeOutElastic" => "easeOutElastic",
			"easeInOutElastic" => "easeInOutElastic",
			"easeInBack" => "easeInBack",
			"easeInOutBack" => "easeInOutBack",
			"easeInBounce" => "easeInBounce",
			"easeOutBounce" => "easeOutBounce",
			"easeInOutBounce" => "easeInOutBounce",
			"Quad.easeIn" => "Quad.easeIn  (same as Power1.easeIn)",
			"Quad.easeInOut" => "Quad.easeInOut  (same as Power1.easeInOut)",
			"Quad.easeOut" => "Quad.easeOut  (same as Power1.easeOut)",
			"Cubic.easeIn" => "Cubic.easeIn  (same as Power2.easeIn)",
			"Cubic.easeInOut" => "Cubic.easeInOut  (same as Power2.easeInOut)",
			"Cubic.easeOut" => "Cubic.easeOut  (same as Power2.easeOut)",
			"Quart.easeIn" => "Quart.easeIn  (same as Power3.easeIn)",
			"Quart.easeInOut" => "Quart.easeInOut  (same as Power3.easeInOut)",
			"Quart.easeOut" => "Quart.easeOut  (same as Power3.easeOut)",
			"Quint.easeIn" => "Quint.easeIn  (same as Power4.easeIn)",
			"Quint.easeInOut" => "Quint.easeInOut  (same as Power4.easeInOut)",
			"Quint.easeOut" => "Quint.easeOut  (same as Power4.easeOut)",
			"Strong.easeIn" => "Strong.easeIn  (same as Power4.easeIn)",
			"Strong.easeInOut" => "Strong.easeInOut  (same as Power4.easeInOut)",
			"Strong.easeOut" => "Strong.easeOut  (same as Power4.easeOut)"
		);

		return($arrEasing);
	}


	/**
	 * get easing functions array
	 */
	public function getArrSplit(){ //true

		$arrSplit = array(
			"none" => "No Split",
			"chars" => "Char Based",
			"words" => "Word Based",
			"lines" => "Line Based"
		);

		return($arrSplit);
	}

	/**
	 * get arr end easing
	 */
	public function getArrEndEasing(){
		$arrEasing = $this->getArrEasing();
		$arrEasing = array_merge(array("nothing" => "No Change"),$arrEasing);

		return($arrEasing);
	}

	/**
	 * get transition array
	 */
	public function getArrTransition(){

		$arrTransition = array(
			"notselectable1"=>"BASICS",
			"notransition"=>"No Transition",
			"fade"=>"Fade",
			
			"notselectable2"=>"SLIDE SIMPLE",
			"slideup"=>"Slide To Top",
			"slidedown"=>"Slide To Bottom",
			"slideright"=>"Slide To Right",
			"slideleft"=>"Slide To Left",
			"slidehorizontal"=>"Slide Horizontal (Next/Previous)",
			"slidevertical"=>"Slide Vertical (Next/Previous)",

			"notselectable21"=>"SLIDE OVER",
			"slideoverup"=>"Slide Over To Top",
			"slideoverdown"=>"Slide Over To Bottom",
			"slideoverright"=>"Slide Over To Right",
			"slideoverleft"=>"Slide Over To Left",
			"slideoverhorizontal"=>"Slide Over Horizontal (Next/Previous)",
			"slideoververtical"=>"Slide Over Vertical (Next/Previous)",		

			"notselectable22"=>"SLIDE REMOVE",
			"slideremoveup"=>"Slide Remove To Top",
			"slideremovedown"=>"Slide Remove To Bottom",
			"slideremoveright"=>"Slide Remove To Right",
			"slideremoveleft"=>"Slide Remove To Left",
			"slideremovehorizontal"=>"Slide Remove Horizontal (Next/Previous)",
			"slideremovevertical"=>"Slide Remove Vertical (Next/Previous)",			
			
			"notselectable23"=>"SLOTS AND BOXES",
			"boxslide"=>"Slide Boxes",
			"slotslide-horizontal"=>"Slide Slots Horizontal",
			"slotslide-vertical"=>"Slide Slots Vertical",
			"boxfade"=>"Fade Boxes",
			"slotfade-horizontal"=>"Fade Slots Horizontal",
			"slotfade-vertical"=>"Fade Slots Vertical",
			
			"notselectable31"=>"FADE & SLIDE",
			"fadefromright"=>"Fade and Slide from Right",
			"fadefromleft"=>"Fade and Slide from Left",
			"fadefromtop"=>"Fade and Slide from Top",
			"fadefrombottom"=>"Fade and Slide from Bottom",
			"fadetoleftfadefromright"=>"To Left From Right",
			"fadetorightfadefromleft"=>"To Right From Left",
			"fadetotopfadefrombottom"=>"To Top From Bottom",
			"fadetobottomfadefromtop"=>"To Bottom From Top",
			
			"notselectable4"=>"PARALLAX",
			"parallaxtoright"=>"Parallax to Right",
			"parallaxtoleft"=>"Parallax to Left",
			"parallaxtotop"=>"Parallax to Top",
			"parallaxtobottom"=>"Parallax to Bottom",
			"parallaxhorizontal"=>"Parallax Horizontal",
			"parallaxvertical"=>"Parallax Vertical",			

			"notselectable5"=>"ZOOM TRANSITIONS",
			"scaledownfromright"=>"Zoom Out and Fade From Right",
			"scaledownfromleft"=>"Zoom Out and Fade From Left",
			"scaledownfromtop"=>"Zoom Out and Fade From Top",
			"scaledownfrombottom"=>"Zoom Out and Fade From Bottom",
			"zoomout"=>"ZoomOut",
			"zoomin"=>"ZoomIn",
			"slotzoom-horizontal"=>"Zoom Slots Horizontal",
			"slotzoom-vertical"=>"Zoom Slots Vertical",
			
			"notselectable6"=>"CURTAIN TRANSITIONS",
			"curtain-1"=>"Curtain from Left",
			"curtain-2"=>"Curtain from Right",
			"curtain-3"=>"Curtain from Middle",
			
			"notselectable7"=>"PREMIUM TRANSITIONS",
			"3dcurtain-horizontal"=>"3D Curtain Horizontal",
			"3dcurtain-vertical"=>"3D Curtain Vertical",
			"cube"=>"Cube Vertical",
			"cube-horizontal"=>"Cube Horizontal",
			"incube"=>"In Cube Vertical",
			"incube-horizontal"=>"In Cube Horizontal",
			"turnoff"=>"TurnOff Horizontal",
			"turnoff-vertical"=>"TurnOff Vertical",
			"papercut"=>"Paper Cut",
			"flyin"=>"Fly In",	

			"notselectable1a"=>"RANDOM",
			"random-selected"=>"Random of Selected",
			"random-static"=>"Random Flat",
			"random-premium"=>"Random Premium",
			"random"=>"Random Flat and Premium"	
		);
		
		return($arrTransition);
	}
	

	/**
	 * get animations array
	 */
	public static function getArrAnimations($all = true){
		$arrAnimations = array(
			
		);
		
		$arrAnimations['custom'] = array('handle' => __('## Custom Animation ##', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['v5s'] = array('handle' => '-----------------------------------');
		$arrAnimations['v5'] = array('handle' => __('- VERSION 5.0 ANIMATIONS -', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['v5e'] = array('handle' => '-----------------------------------');
		
		$arrAnimations['LettersFlyInFromBottom'] = array('handle' => 'LettersFlyInFromBottom','params' => '{"movex":"inherit","movey":"[100%]","movez":"0","rotationx":"inherit","rotationy":"inherit","rotationz":"-35deg","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power4.easeInOut","speed":"2000","split":"chars","splitdelay":"5"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['LettersFlyInFromLeft'] = array('handle' => 'LettersFlyInFromLeft','params' => '{"movex":"[-105%]","movey":"inherit","movez":"0","rotationx":"0deg","rotationy":"0deg","rotationz":"-90deg","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power4.easeInOut","speed":"2000","split":"chars","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['LettersFlyInFromRight'] = array('handle' => 'LettersFlyInFromRight','params' => '{"movex":"[105%]","movey":"inherit","movez":"0","rotationx":"45deg","rotationy":"0deg","rotationz":"90deg","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power4.easeInOut","speed":"2000","split":"chars","splitdelay":"5"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['LettersFlyInFromTop'] = array('handle' => 'LettersFlyInFromTop','params' => '{"movex":"inherit","movey":"[-100%]","movez":"0","rotationx":"inherit","rotationy":"inherit","rotationz":"35deg","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power4.easeInOut","speed":"2000","split":"chars","splitdelay":"5"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['MaskedZoomOut'] = array('handle' => 'MaskedZoomOut','params' => '{"movex":"inherit","movey":"inherit","movez":"0","rotationx":"0deg","rotationy":"0","rotationz":"0","scalex":"2","scaley":"2","skewx":"0","skewy":"0","captionopacity":"0","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power2.easeOut","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['PopUpSmooth'] = array('handle' => 'PopUpSmooth','params' => '{"movex":"inherit","movey":"inherit","movez":"0","rotationx":"0","rotationy":"0","rotationz":"0","scalex":"0.9","scaley":"0.9","skewx":"0","skewy":"0","captionopacity":"0","mask":"false","mask_x":"0px","mask_y":"top","easing":"Power3.easeInOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['RotateInFromBottom'] = array('handle' => 'RotateInFromBottom','params' => '{"movex":"inherit","movey":"bottom","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"90deg","scalex":"2","scaley":"2","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","easing":"Power3.easeInOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['RotateInFormZero'] = array('handle' => 'RotateInFormZero','params' => '{"movex":"inherit","movey":"bottom","movez":"inherit","rotationx":"-20deg","rotationy":"-20deg","rotationz":"0deg","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","easing":"Power3.easeOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskFromBottom'] = array('handle' => 'SlideMaskFromBottom','params' => '{"movex":"inherit","movey":"[100%]","movez":"0","rotationx":"0deg","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"0","mask":"true","mask_x":"0px","mask_y":"[100%]","easing":"Power2.easeInOut","speed":"2000","split":"none","splitdelay":"5"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskFromLeft'] = array('handle' => 'SlideMaskFromLeft','params' => '{"movex":"[-100%]","movey":"inherit","movez":"0","rotationx":"0deg","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power3.easeInOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskFromRight'] = array('handle' => 'SlideMaskFromRight','params' => '{"movex":"[100%]","movey":"inherit","movez":"0","rotationx":"0deg","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power3.easeInOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskFromTop'] = array('handle' => 'SlideMaskFromTop','params' => '{"movex":"inherit","movey":"[-100%]","movez":"0","rotationx":"0deg","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"inherit","mask":"true","mask_x":"0px","mask_y":"0px","easing":"Power3.easeInOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SmoothPopUp_One'] = array('handle' => 'SmoothPopUp_One','params' => '{"movex":"inherit","movey":"inherit","movez":"0","rotationx":"0","rotationy":"0","rotationz":"0","scalex":"0.8","scaley":"0.8","skewx":"0","skewy":"0","captionopacity":"0","mask":"false","mask_x":"0px","mask_y":"top","easing":"Power4.easeOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SmoothPopUp_Two'] = array('handle' => 'SmoothPopUp_Two','params' => '{"movex":"inherit","movey":"inherit","movez":"0","rotationx":"0","rotationy":"0","rotationz":"0","scalex":"0.9","scaley":"0.9","skewx":"0","skewy":"0","captionopacity":"0","mask":"false","mask_x":"0px","mask_y":"top","easing":"Power2.easeOut","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SmoothMaskFromRight'] = array('handle' => 'SmoothMaskFromRight','params' => '{"movex":"[-175%]","movey":"0px","movez":"0","rotationx":"0","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"1","mask":"true","mask_x":"[100%]","mask_y":"0","easing":"Power3.easeOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SmoothMaskFromLeft'] = array('handle' => 'SmoothMaskFromLeft','params' => '{"movex":"[175%]","movey":"0px","movez":"0","rotationx":"0","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"1","mask":"true","mask_x":"[-100%]","mask_y":"0","easing":"Power3.easeOut","speed":"1500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SmoothSlideFromBottom'] = array('handle' => 'SmoothSlideFromBottom','params' => '{"movex":"inherit","movey":"[100%]","movez":"0","rotationx":"0deg","rotationy":"0","rotationz":"0","scalex":"1","scaley":"1","skewx":"0","skewy":"0","captionopacity":"0","mask":"false","mask_x":"0px","mask_y":"[100%]","easing":"Power4.easeInOut","speed":"2000","split":"none","splitdelay":"5"}', 'settings' => array('version' => '5.0'));
  
		$arrAnimations['v4s'] = array('handle' => '-----------------------------------');
		$arrAnimations['v4'] = array('handle' => __('- VERSION 4.0 ANIMATIONS -', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['v4e'] = array('handle' => '-----------------------------------');		
		$arrAnimations['noanim'] = array('handle' => 'No-Animation','params' => '{"movex":"inherit","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['tp-fade'] = array('handle' => 'Fade-In','params' => '{"movex":"inherit","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"0"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['sft'] = array('handle' => 'Short-from-Top','params' => '{"movex":"inherit","movey":"-50px","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['sfb'] = array('handle' => 'Short-from-Bottom','params' => '{"movex":"inherit","movey":"50px","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['sfl'] = array('handle' => 'Short-From-Left','params' => '{"movex":"-50px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['sfr'] = array('handle' => 'Short-From-Right','params' => '{"movex":"50px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['lfr'] = array('handle' => 'Long-From-Right','params' => '{"movex":"right","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['lfl'] = array('handle' => 'Long-From-Left','params' => '{"movex":"left","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['lft'] = array('handle' => 'Long-From-Top','params' => '{"movex":"inherit","movey":"top","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['lfb'] = array('handle' => 'Long-From-Bottom','params' => '{"movex":"inherit","movey":"bottom","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewfromleft'] = array('handle' => 'Skew-From-Long-Left','params' => '{"movex":"left","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"45px","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewfromright'] = array('handle' => 'Skew-From-Long-Right','params' => '{"movex":"right","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"-85px","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewfromleftshort'] = array('handle' => 'Skew-From-Short-Left','params' => '{"movex":"-200px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"85px","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewfromrightshort'] = array('handle' => 'Skew-From-Short-Right','params' => '{"movex":"200px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"-85px","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['randomrotate'] = array('handle' => 'Random-Rotate-and-Scale','params' => '{"movex":"{-250,250}","movey":"{-150,150}","movez":"inherit","rotationx":"{-90,90}","rotationy":"{-90,90}","rotationz":"{-360,360}","scalex":"{0,1}","scaley":"{0,1}","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		
		
		if($all){
			$arrAnimations['vss'] = array('handle' => '--------------------------------------');
			$arrAnimations['vs'] = array('handle' => __('- SAVED CUSTOM ANIMATIONS -', REVSLIDER_TEXTDOMAIN));
			$arrAnimations['vse'] = array('handle' => '--------------------------------------');
		
			//$custom = RevSliderOperations::getCustomAnimations('customin');
			$custom = RevSliderOperations::getCustomAnimationsFullPre('customin');

			$arrAnimations = array_merge($arrAnimations, $custom);
		}
		
		foreach($arrAnimations as $key => $value){
			if(!isset($value['params'])) continue;
			
			$t = json_decode(str_replace("'", '"', $value['params']), true);
			if(!empty($t))
				$arrAnimations[$key]['params'] = $t;
		}
		
		return($arrAnimations);
	}

	/**
	 * get "end" animations array
	 */
	public static function getArrEndAnimations($all = true){
		$arrAnimations = array();
		$arrAnimations['custom'] = array('handle' => __('## Custom Animation ##', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['auto'] = array('handle' => __('Automatic Reverse', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['v5s'] = array('handle' => '-----------------------------------');
		$arrAnimations['v5'] = array('handle' => __('- VERSION 5.0 ANIMATIONS -', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['v5e'] = array('handle' => '-----------------------------------');

		$arrAnimations['BounceOut'] = array('handle' => 'BounceOut','params' => '{"movex":"inherit","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"0deg","scalex":"0.7","scaley":"0.7","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"true","mask_x":"0","mask_y":"0","easing":"Back.easeIn","speed":"500","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['Fade-Out-Long'] = array('handle' => 'Fade-Out-Long','params' => '{"movex":"inherit","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","easing":"Power2.easeIn","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskToBottom'] = array('handle' => 'SlideMaskToBottom','params' => '{"movex":"inherit","movey":"[100%]","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"true","mask_x":"inherit","mask_y":"inherit","easing":"nothing","speed":"300","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskToLeft'] = array('handle' => 'SlideMaskToLeft','params' => '{"movex":"[-100%]","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"true","mask_x":"inherit","mask_y":"inherit","easing":"Power3.easeInOut","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskToRight'] = array('handle' => 'SlideMaskToRight','params' => '{"movex":"[100%]","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"true","mask_x":"inherit","mask_y":"inherit","easing":"Power3.easeInOut","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlideMaskToTop'] = array('handle' => 'SlideMaskToTop','params' => '{"movex":"inherit","movey":"[-100%]","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"true","mask_x":"inherit","mask_y":"inherit","easing":"nothing","speed":"300","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SlurpOut'] = array('handle' => 'SlurpOut','params' => '{"movex":"inherit","movey":"[100%]","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"0deg","scalex":"0.7","scaley":"0.7","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"true","mask_x":"0","mask_y":"0","easing":"Power3.easeInOut","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['SmoothCropToBottom'] = array('handle' => 'SmoothCropToBottom','params' => '{"movex":"inherit","movey":"[175%]","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"true","mask_x":"inherit","mask_y":"inherit","easing":"Power2.easeInOut","speed":"1000","split":"none","splitdelay":"10"}', 'settings' => array('version' => '5.0'));
 
		$arrAnimations['v4s'] = array('handle' => '-----------------------------------');
		$arrAnimations['v4'] = array('handle' => __('- VERSION 4.0 ANIMATIONS -', REVSLIDER_TEXTDOMAIN));
		$arrAnimations['v4e'] = array('handle' => '-----------------------------------');
		$arrAnimations['noanimout'] = array('handle' => 'No-Out-Animation','params' => '{"movex":"inherit","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['fadeout'] = array('handle' => 'Fade-Out','params' => '{"movex":"inherit","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"0"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['stt'] = array('handle' => 'Short-To-Top','params' => '{"movex":"inherit","movey":"-50px","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['stb'] = array('handle' => 'Short-To-Bottom','params' => '{"movex":"inherit","movey":"50px","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['stl'] = array('handle' => 'Short-To-Left','params' => '{"movex":"-50px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['str'] = array('handle' => 'Short-To-Right','params' => '{"movex":"50px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['ltr'] = array('handle' => 'Long-To-Right','params' => '{"movex":"right","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['ltl'] = array('handle' => 'Long-To-Left','params' => '{"movex":"left","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['ltt'] = array('handle' => 'Long-To-Top','params' => '{"movex":"inherit","movey":"top","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['ltb'] = array('handle' => 'Long-To-Bottom','params' => '{"movex":"inherit","movey":"bottom","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"inherit","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewtoleft'] = array('handle' => 'Skew-To-Long-Left','params' => '{"movex":"left","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"45px","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewtoright'] = array('handle' => 'Skew-To-Long-Right','params' => '{"movex":"right","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"-85px","skewy":"inherit","captionopacity":"inherit","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewtorightshort'] = array('handle' => 'Skew-To-Short-Right','params' => '{"movex":"200px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"-85px","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['skewtoleftshort'] = array('handle' => 'Skew-To-Short-Left','params' => '{"movex":"-200px","movey":"inherit","movez":"inherit","rotationx":"inherit","rotationy":"inherit","rotationz":"inherit","scalex":"inherit","scaley":"inherit","skewx":"85px","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));
		$arrAnimations['randomrotateout'] = array('handle' => 'Random-Rotate-Out','params' => '{"movex":"{-250,250}","movey":"{-150,150}","movez":"inherit","rotationx":"{-90,90}","rotationy":"{-90,90}","rotationz":"{-360,360}","scalex":"{0,1}","scaley":"{0,1}","skewx":"inherit","skewy":"inherit","captionopacity":"0","mask":"false","mask_x":"0","mask_y":"0","mask_speed":"500"}', 'settings' => array('version' => '5.0'));

		if($all){
			$arrAnimations['vss'] = array('handle' => '--------------------------------------');
			$arrAnimations['vs'] = array('handle' => __('- SAVED CUSTOM ANIMATIONS -', REVSLIDER_TEXTDOMAIN));
			$arrAnimations['vse'] = array('handle' => '--------------------------------------');
			//$custom = RevSliderOperations::getCustomAnimations('customout');
			$custom = RevSliderOperations::getCustomAnimationsFullPre('customout');

			$arrAnimations = array_merge($arrAnimations, $custom);
		}
		
		foreach($arrAnimations as $key => $value){
			if(!isset($value['params'])) continue;
			
			$t = json_decode(str_replace("'", '"', $value['params']), true);
			if(!empty($t))
				$arrAnimations[$key]['params'] = $t;
		}
		return($arrAnimations);
	}

	/**
	 * insert custom animations
	 */
	public static function insertCustomAnim($anim){
		if(isset($anim['handle'])) {
			$db = new RevSliderDB();

			$arrInsert = array();
			$arrInsert["handle"] = $anim['handle'];
			unset($anim['handle']);

			$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $anim['params'])));
			$arrInsert["settings"] = json_encode(array('version' => 'custom'));

			$result = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
		}

		$arrAnims['customin'] = RevSliderOperations::getCustomAnimations();
		$arrAnims['customout'] = RevSliderOperations::getCustomAnimations('customout');
		$arrAnims['customfull'] = RevSliderOperations::getFullCustomAnimations();

		return $arrAnims;
	}

	/**
	 * insert custom animations
	 */
	public static function updateCustomAnim($anim){
		
		if(isset($anim['handle'])) {
			$db = new RevSliderDB();
			$handle = $anim['handle'];
			unset($anim['handle']);
			
			$id = str_replace(array('customin-', 'customout-'), array('', ''), $handle);
			
			$arrUpdate = array();
			$arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $anim['params'])));
			//$arrUpdate["settings"] = json_encode(array('version' => 'custom'));
			
			$result = $db->update(RevSliderGlobals::$table_layer_anims, $arrUpdate, array('id' => $id));
		}

		$arrAnims['customin'] = RevSliderOperations::getCustomAnimations();
		$arrAnims['customout'] = RevSliderOperations::getCustomAnimations('customout');
		$arrAnims['customfull'] = RevSliderOperations::getFullCustomAnimations();

		return $arrAnims;
	}

	/**
	 * update custom animations name
	 * @since: 5.0
	 */
	public static function updateCustomAnimName($anim){
		if(isset($anim['handle'])) {
			$db = new RevSliderDB();
			$id = $anim['id'];
			unset($anim['id']);
			
			$result = $db->update(RevSliderGlobals::$table_layer_anims, $anim, array('id' => $id));
		}

		$arrAnims['customin'] = RevSliderOperations::getCustomAnimations();
		$arrAnims['customout'] = RevSliderOperations::getCustomAnimations('customout');
		$arrAnims['customfull'] = RevSliderOperations::getFullCustomAnimations();

		return $arrAnims;
	}

	/**
	 *
	 * delete custom animations
	 */
	public static function deleteCustomAnim($rawID){
		if(trim($rawID) != '') {
			$db = new RevSliderDB();
			$id = str_replace(array('customin-', 'customout-'), array('', ''), $rawID);
			$db->delete(RevSliderGlobals::$table_layer_anims, "id = '".intval($id)."'");
		}

		$arrAnims['customin'] = RevSliderOperations::getCustomAnimations();
		$arrAnims['customout'] = RevSliderOperations::getCustomAnimations('customout');
		$arrAnims['customfull'] = RevSliderOperations::getFullCustomAnimations();

		return $arrAnims;
	}

	/**
	 *
	 * get custom animations
	 */
	public static function getCustomAnimations($pre = 'customin'){
		$db = new RevSliderDB();

		$customAnimations = array();

		$result = $db->fetch(RevSliderGlobals::$table_layer_anims);
		if(!empty($result)){
			foreach($result as $key => $value){
				$params = json_decode($value['params'], true);
				if(!isset($params['type']) || $params['type'] == $pre){
					$customAnimations[$pre.'-'.$value['id']] = $value['handle'];
				}
			}
		}
		asort($customAnimations);
		
		return $customAnimations;
	}
	
	
	/**
	 *
	 * get custom animations
	 */
	public static function getCustomAnimationsFullPre($pre = 'customin'){
		$db = new RevSliderDB();

		$customAnimations = array();
		$customTemp = array();
		$sort = array();
		$result = $db->fetch(RevSliderGlobals::$table_layer_anims);
		if(!empty($result)){
			foreach($result as $key => $value){
				$params = json_decode($value['params'], true);
				if(!isset($params['type']) || $params['type'] == $pre){
					$customTemp[$pre.'-'.$value['id']] = $value;
					$sort[$pre.'-'.$value['id']] = $value['handle'];
				}
			}
		}
		if(!empty($sort)){
			asort($sort);
			foreach($sort as $k => $v){
				$customAnimations[$k] = $customTemp[$k];
			}
		}

		return $customAnimations;
	}
	

	/**
	 *
	 * get full custom animations
	 */
	public static function getFullCustomAnimations(){
		$db = new RevSliderDB();

		$customAnimations = array();

		$result = $db->fetch(RevSliderGlobals::$table_layer_anims);
		if(!empty($result)){
			foreach($result as $key => $value){
				$customAnimations[$key]['id'] = $value['id'];
				$customAnimations[$key]['handle'] = $value['handle'];
				$customAnimations[$key]['params'] = json_decode(str_replace("'", '"', $value['params']), true);
			}
		}

		return $customAnimations;
	}

	/**
	 *
	 * get animation params by handle
	 */
	public static function getCustomAnimationByHandle($handle){
		$db = new RevSliderDB();

		$result = $db->fetch(RevSliderGlobals::$table_layer_anims, "handle = '".$handle."'");
		if(!empty($result)) return json_decode(str_replace("'", '"', $result[0]['params']), true);

		return false;
	}

	/**
	 *
	 * get animation params by id
	 */
	public static function getFullCustomAnimationByID($id){
		$db = new RevSliderDB();

		$result = $db->fetch(RevSliderGlobals::$table_layer_anims, "id = '".$id."'");

		if(!empty($result)){
			$customAnimations = array();
			$customAnimations['id'] = $result[0]['id'];
			$customAnimations['handle'] = $result[0]['handle'];
			$customAnimations['params'] = json_decode(str_replace("'", '"', $result[0]['params']), true);
			return $customAnimations;
		}

		return false;
	}

	/**
	 * parse animation params
	 * 5.0.5: added (R) for reverse
	 */
	public static function parseCustomAnimationByArray($animArray, $is = 'start'){
		$retString = '';
		
		$reverse = (isset($animArray['x_'.$is.'_reverse']) && $animArray['x_'.$is.'_reverse'] == true) ? '(R)' : ''; //movex reverse
		if(isset($animArray['x_'.$is]) && $animArray['x_'.$is] !== '' && $animArray['x_'.$is] !== 'inherit') $retString.= 'x:'.$animArray['x_'.$is].$reverse.';'; //movex
		$reverse = (isset($animArray['y_'.$is.'_reverse']) && $animArray['y_'.$is.'_reverse'] == true) ? '(R)' : ''; //movey reverse
		if(isset($animArray['y_'.$is]) && $animArray['y_'.$is] !== '' && $animArray['y_'.$is] !== 'inherit') $retString.= 'y:'.$animArray['y_'.$is].$reverse.';'; //movey
		if(isset($animArray['z_'.$is]) && $animArray['z_'.$is] !== '' && $animArray['z_'.$is] !== 'inherit') $retString.= 'z:'.$animArray['z_'.$is].';'; //movez

		$reverse = (isset($animArray['x_rotate_'.$is.'_reverse']) && $animArray['x_rotate_'.$is.'_reverse'] == true) ? '(R)' : ''; //rotationx reverse
		if(isset($animArray['x_rotate_'.$is]) && $animArray['x_rotate_'.$is] !== '' && $animArray['x_rotate_'.$is] !== 'inherit') $retString.= 'rX:'.$animArray['x_rotate_'.$is].$reverse.';'; //rotationx
		$reverse = (isset($animArray['y_rotate_'.$is.'_reverse']) && $animArray['y_rotate_'.$is.'_reverse'] == true) ? '(R)' : ''; //rotationy reverse
		if(isset($animArray['y_rotate_'.$is]) && $animArray['y_rotate_'.$is] !== '' && $animArray['y_rotate_'.$is] !== 'inherit') $retString.= 'rY:'.$animArray['y_rotate_'.$is].$reverse.';'; //rotationy
		$reverse = (isset($animArray['z_rotate_'.$is.'_reverse']) && $animArray['z_rotate_'.$is.'_reverse'] == true) ? '(R)' : ''; //rotationz reverse
		if(isset($animArray['z_rotate_'.$is]) && $animArray['z_rotate_'.$is] !== '' && $animArray['z_rotate_'.$is] !== 'inherit') $retString.= 'rZ:'.$animArray['z_rotate_'.$is].$reverse.';'; //rotationz

		if(isset($animArray['scale_x_'.$is]) && $animArray['scale_x_'.$is] !== '' && $animArray['scale_x_'.$is] !== 'inherit'){ //scalex
			$reverse = (isset($animArray['scale_x_'.$is.'_reverse']) && $animArray['scale_x_'.$is.'_reverse'] == true) ? '(R)' : ''; //scalex reverse
			$retString.= 'sX:';
			$retString.= ($animArray['scale_x_'.$is] == 0) ? 0 : $animArray['scale_x_'.$is];
			$retString.= $reverse;
			$retString.= ';';
		}
		if(isset($animArray['scale_y_'.$is]) && $animArray['scale_y_'.$is] !== '' && $animArray['scale_y_'.$is] !== 'inherit'){ //scaley
			$reverse = (isset($animArray['scale_y_'.$is.'_reverse']) && $animArray['scale_y_'.$is.'_reverse'] == true) ? '(R)' : ''; //scaley reverse
			$retString.= 'sY:';
			$retString.= ($animArray['scale_y_'.$is] == 0) ? 0 : $animArray['scale_y_'.$is];
			$retString.= $reverse;
			$retString.= ';';
		}
		
		$reverse = (isset($animArray['skew_x_'.$is.'_reverse']) && $animArray['skew_x_'.$is.'_reverse'] == true) ? '(R)' : ''; //skewx reverse
		if(isset($animArray['skew_x_'.$is]) && $animArray['skew_x_'.$is] !== '' && $animArray['skew_x_'.$is] !== 'inherit') $retString.= 'skX:'.$animArray['skew_x_'.$is].$reverse.';'; //skewx
		$reverse = (isset($animArray['skew_y_'.$is.'_reverse']) && $animArray['skew_y_'.$is.'_reverse'] == true) ? '(R)' : ''; //skewy reverse
		if(isset($animArray['skew_y_'.$is]) && $animArray['skew_y_'.$is] !== '' && $animArray['skew_y_'.$is] !== 'inherit') $retString.= 'skY:'.$animArray['skew_y_'.$is].$reverse.';'; //skewy

		if(isset($animArray['opacity_'.$is]) && $animArray['opacity_'.$is] !== '' && $animArray['opacity_'.$is] !== 'inherit'){ //captionopacity
			$retString.= 'opacity:';
			$retString.= ($animArray['opacity_'.$is] == 0) ? 0 : $animArray['opacity_'.$is] / 100;
			$retString.= ';';
		}
		
		if($retString == ''){ //we do not have animations set, so set them here
			
		}
		
		if($is == 'start'){
			$retString .= 's:'.RevSliderFunctions::getVal($animArray, 'speed', 300).';';
			$retString .= 'e:'.RevSliderFunctions::getVal($animArray, 'easing', 'easeOutExpo').';';
		}else{
			$es = RevSliderFunctions::getVal($animArray, 'endspeed');
			$ee = trim(RevSliderFunctions::getVal($animArray, 'endeasing'));
			if(!empty($es)){
				$retString .= 's:'.$es.';';
				if(!empty($ee) && $ee !== 'nothing'){
					$retString .= 'e:'.$ee.';';
				}
			}
		}
		
		return $retString;
	}

	
	/**
	 * parse mask params
	 * @since: 5.0
	 */
	public static function parseCustomMaskByArray($animArray, $is = 'start'){
		$retString = '';
		$reverse = (isset($animArray['mask_x_'.$is.'_reverse']) && $animArray['mask_x_'.$is.'_reverse'] == true) ? '(R)' : '';
		if(isset($animArray['mask_x_'.$is]) && $animArray['mask_x_'.$is] !== '') $retString.= 'x:'.$animArray['mask_x_'.$is].$reverse.';';
		$reverse = (isset($animArray['mask_y_'.$is.'_reverse']) && $animArray['mask_y_'.$is.'_reverse'] == true) ? '(R)' : '';
		if(isset($animArray['mask_y_'.$is]) && $animArray['mask_y_'.$is] !== '') $retString.= 'y:'.$animArray['mask_y_'.$is].$reverse.';';
		if(isset($animArray['mask_speed_'.$is]) && $animArray['mask_speed_'.$is] !== '') $retString.= 's:'.$animArray['mask_speed_'.$is].';';
		if(isset($animArray['mask_ease_'.$is]) && $animArray['mask_ease_'.$is] !== '') $retString.= 'e:'.$animArray['mask_ease_'.$is].';';
		
		return $retString;
	}

	
	/**
	 *
	 * parse css file and get the classes from there.
	 */
	public function getArrCaptionClasses($contentCSS){
		//parse css captions file
		$parser = new RevSliderCssParser();
		$parser->initContent($contentCSS);
		$arrCaptionClasses = $parser->getArrClasses('','',true);
		
		return($arrCaptionClasses);
	}

	
	/**
	 *
	 * get all CSS classes from database with version numbers
	 */
	public function getArrCaptionSorted($contentCSS){ //get all from the database
		//parse css captions file
		$parser = new RevSliderCssParser();
		$parser->initContent($contentCSS);
		$arrCaptionClasses = $parser->getArrClasses('','',true);
		
		return($arrCaptionClasses);
	}

	/**
	 *
	 * get all font family types
	 */
	public function getArrFontFamilys($slider = false){
		//Web Safe Fonts
		$fonts = array(
			//Serif Fonts
			'Georgia, serif',
			'"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'"Times New Roman", Times, serif',

			//Sans-Serif Fonts
			'Arial, Helvetica, sans-serif',
			'"Arial Black", Gadget, sans-serif',
			'"Comic Sans MS", cursive, sans-serif',
			'Impact, Charcoal, sans-serif',
			'"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'Tahoma, Geneva, sans-serif',
			'"Trebuchet MS", Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',

			//Monospace Fonts
			'"Courier New", Courier, monospace',
			'"Lucida Console", Monaco, monospace'
		);
		
		if($slider !== false){
			if($slider->getParam("load_googlefont","false") == "true"){
				$font_custom = $slider->getParam("google_font","");
				if(!is_array($font_custom)) $font_custom = array($font_custom); //backwards compability

				if(is_array($font_custom)){
					foreach($font_custom as $key => $curFont){
						$font = $this->cleanFontStyle(stripslashes($curFont));
						if($font != false)
							$font_custom[$key] = $font;
						else
							unset($font_custom[$key]);
					}
					$fonts = array_merge($font_custom, $fonts);
				}
			}
		}

		return $fonts;
	}


	/**
	 *
	 * get font name in clean
	 */
	public function cleanFontStyle($font){
		$url = preg_match('/href=["\']?([^"\'>]+)["\']?/', $font, $match);
		if(!isset($match[1])) return false;
		$info = parse_url($match[1]);

		if(isset($info['query'])){
			$font = str_replace(array('family=', '+'), array('', ' '), $info['query']);
			$font = explode(':', $font);
			return (strpos($font['0'], ' ') !== false) ? '"'.$font['0'].'"' : $font['0'];

		}

		return false;
	}

	/**
	 *
	 * get the select classes html for putting in the html by ajax
	 */
	private function getHtmlSelectCaptionClasses($contentCSS){
		$arrCaptions = $this->getArrCaptionClasses($contentCSS);
		$htmlSelect = RevSliderFunctions::getHTMLSelect($arrCaptions,"","id='layer_caption' name='layer_caption'",true);
		return($htmlSelect);
	}

	/**
	 *
	 * get contents of the css table
	 */
	public function getCaptionsContent(){
		$result = $this->db->fetch(RevSliderGlobals::$table_css);
		$contentCSS = RevSliderCssParser::parseDbArrayToCss($result);
		return($contentCSS);
	}


	/**
	 *
	 * get contents of the css table
	 */
	public static function getCaptionsContentArray($handle = false){
		$db = new RevSliderDB();
		$result = $db->fetch(RevSliderGlobals::$table_css);
		$contentCSS = RevSliderCssParser::parseDbArrayToArray($result, $handle);
		return($contentCSS);
	}

	/**
	 *
	 * get contents of the static css file
	 */
	public static function getStaticCss(){
		if ( is_multisite() ){
			if(!get_site_option('revslider-static-css')){
				$contentCSS = @file_get_contents(RevSliderGlobals::$filepath_static_captions);
				self::updateStaticCss($contentCSS);
			}
			$contentCSS = get_site_option('revslider-static-css', '');
		}else{
			if(!get_option('revslider-static-css')){
				$contentCSS = @file_get_contents(RevSliderGlobals::$filepath_static_captions);
				self::updateStaticCss($contentCSS);
			}
			$contentCSS = get_option('revslider-static-css', '');
		}

		return($contentCSS);
	}

	/**
	 *
	 * get contents of the static css file
	 */
	public static function updateStaticCss($content){
		$content = str_replace(array("\'", '\"', '\\\\'),array("'", '"', '\\'), trim($content));

		if ( is_multisite() ){
			$c = get_site_option('revslider-static-css', '');
			$c = update_site_option('revslider-static-css', $content);
		}else{
			$c = get_option('revslider-static-css', '');
			$c = update_option('revslider-static-css', $content);
		}

		return $content;
	}

	/**
	 *
	 * get contents of the static css file
	 */
	public function getDynamicCss(){
		$db = new RevSliderDB();

		$styles = $db->fetch(RevSliderGlobals::$table_css);
		$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");

		return $styles;
	}

	/**
	 *
	 * insert captions css file content
	 * @return new captions html select
	 */
	public function insertCaptionsContentData($content){
		global $revSliderVersion;
		
		if(!isset($content['handle']) || !isset($content['idle']) || !isset($content['hover'])) return false; // || !isset($content['advanced'])
		
		$db = new RevSliderDB();

		$handle = $content['handle'];
		
		if(!isset($content['hover'])) $content['hover'] = '';
		if(!isset($content['advanced'])) $content['advanced'] = array();
		if(!isset($content['advanced']['idle'])) $content['advanced']['idle'] = array();
		if(!isset($content['advanced']['hover'])) $content['advanced']['hover'] = array();
		
		$arrInsert = array();
		$arrInsert["handle"] = '.tp-caption.'.$handle;
		$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $content['idle'])));
		$arrInsert["hover"] = stripslashes(json_encode(str_replace("'", '"', $content['hover'])));
		
		if(!isset($content['settings'])) $content['settings'] = array();
		$content['settings']['version'] = 'custom';
		$content['settings']['translated'] = '5'; // translated to version 5 currently
		$arrInsert["settings"] = stripslashes(json_encode(str_replace("'", '"', $content['settings'])));
		
		$arrInsert["advanced"] = array();
		$arrInsert["advanced"]['idle'] = $content['advanced']['idle'];
		$arrInsert["advanced"]['hover'] = $content['advanced']['hover'];
		$arrInsert["advanced"] = stripslashes(json_encode(str_replace("'", '"', $arrInsert["advanced"])));
		
		$result = $db->insert(RevSliderGlobals::$table_css, $arrInsert);

		//output captions array
		$arrCaptions = RevSliderCssParser::get_captions_sorted();
		
		return($arrCaptions);
	}

	/**
	 * update captions css file content
	 * @return new captions html select
	 */
	public function updateCaptionsContentData($content){
		global $revSliderVersion;
		
		if(!isset($content['handle']) || !isset($content['idle']) || !isset($content['hover']) || !isset($content['advanced'])) return false;
		
		$db = new RevSliderDB();

		//first get single entry to merge settings
		$styles = $db->fetchSingle(RevSliderGlobals::$table_css, '`handle` = "'.$db->escape('.tp-caption.'.$content['handle']).'"');
	
		if(empty($styles)) return false;
		
		$settings = json_decode(str_replace("'", '"', $styles['settings']), true);
		if(isset($content['settings']) && !empty($content['settings'])){
			foreach($content['settings'] as $key => $value){
				$settings[$key] = $value;
			}
			//$settings = array_merge($content['settings'], $settings);
		}
		
		$handle = $content['handle'];
		
		if(!isset($content['idle'])) $content['idle'] = '';
		if(!isset($content['hover'])) $content['hover'] = '';
		if(!isset($content['advanced'])) $content['advanced'] = array();
		if(!isset($content['advanced']['idle'])) $content['advanced']['idle'] = array();
		if(!isset($content['advanced']['hover'])) $content['advanced']['hover'] = array();
		
		$arrUpdate = array();
		$arrUpdate["params"] = stripslashes(json_encode(str_replace("'", '"', $content['idle'])));
		$arrUpdate["hover"] = stripslashes(json_encode(str_replace("'", '"', $content['hover'])));
		$arrUpdate["settings"] = stripslashes(json_encode(str_replace("'", '"', $settings)));
		
		$arrUpdate["advanced"] = array();
		$arrUpdate["advanced"]['idle'] = $content['advanced']['idle'];
		$arrUpdate["advanced"]['hover'] = $content['advanced']['hover'];
		$arrUpdate["advanced"] = stripslashes(json_encode(str_replace("'", '"', $arrUpdate["advanced"])));
		
		$result = $db->update(RevSliderGlobals::$table_css, $arrUpdate, array('handle' => '.tp-caption.'.$handle));
		
		//output captions array
		$arrCaptions = RevSliderCssParser::get_captions_sorted();
		return($arrCaptions);
	}
	
	
	/**
	 * update captions advanced css
	 * @return: new captions html select
	 * @since: 5.0
	 */
	public function updateAdvancedCssData($data){
		if(!isset($data['handle']) || !isset($data['styles']) || !isset($data['type'])) return false;
		if($data['type'] !== 'idle' && $data['type'] !== 'hover') return false;
		
		$db = new RevSliderDB();
		
		//get current styles
		$styles = $db->fetchSingle(RevSliderGlobals::$table_css, '`handle` = "'.$db->escape($data['handle']).'"');
		
		if(!empty($styles)){
			if(!isset($styles['advanced'])) $styles['advanced'] = '';
			
			$adv = json_decode(str_replace("'", '"', $styles['advanced']), true);
			
			if(!isset($adv['idle'])) $adv['idle'] = array();
			if(!isset($adv['hover'])) $adv['hover'] = array();
			
			$adv[$data['type']] = $data['styles'];

			
			$arrUpdate = array();
			
			$arrUpdate['advanced'] = json_encode(str_replace("'", '"', $adv));
			
			$result = $db->update(RevSliderGlobals::$table_css, $arrUpdate, array('handle' => $data['handle']));
			
			//output captions array
			$arrCaptions = RevSliderCssParser::get_captions_sorted();
			
			return($arrCaptions);
			
		}else{
			return false;
		}
		
	}
	
	/**
	 * rename caption
	 * @since: 5.0
	 */
	public function renameCaption($content){
		if(isset($content['old_name']) && isset($content['new_name'])) {
			$db = new RevSliderDB();

			$handle = $content['old_name'];

			$arrUpdate = array();
			$arrUpdate["handle"] = '.tp-caption.'.$content['new_name'];
			$result = $db->update(RevSliderGlobals::$table_css, $arrUpdate, array('handle' => '.tp-caption.'.$handle));
			if($result !== false){ //rename all layers in all Sliders that use this old name with the new name
				$slider = new RevSlider();
				$arrSliders = $slider->getArrSliders();
				if(!empty($arrSliders)){
					foreach($arrSliders as $slider){
						$arrSildes = $slider->getSlides();
						foreach($arrSildes as $slide){
							$slide->replaceCssClass($content['old_name'], $content['new_name']);
						}
					}
				}
			}
		}

		//output captions array
		$arrCaptions = RevSliderCssParser::get_captions_sorted();
		return($arrCaptions);
	}
	
	
	/**
	 *
	 * delete captions css file content
	 * @return new captions html select
	 */
	public function deleteCaptionsContentData($handle){
		$db = new RevSliderDB();

		$db->delete(RevSliderGlobals::$table_css,"handle='.tp-caption.".$handle."'");

		//$this->updateDynamicCaptions();

		//output captions array
		$arrCaptions = RevSliderCssParser::get_captions_sorted();
		
		return($arrCaptions);
	}

	/**
	 *
	 * update dynamic-captions css file content
	 */
	public static function updateDynamicCaptions($full = false){
		if($full){
			$captions = array();
			$captions = RevSliderOperations::getCaptionsContentArray();

			$styles = RevSliderCssParser::parseArrayToCss($captions, "\n");
		}else{
			//go through all sliders and check which classes are used, get all classes from DB and write them into the file
			$slider = new RevSlider();
			$arrSliders = $slider->getArrSliders();
			$classes = array();

			//get used classes
			if(!empty($arrSliders)){
				foreach($arrSliders as $slider){
					try{
						$slides = $slider->getSlides();

						if(!empty($slides)){
							foreach($slides as $slide){
								$layers = $slide->getLayers();
								if(!empty($layers)){
									foreach($layers as $layer){
										if(isset($layer['style'])){
											if(!empty($layer['style'])) $classes[$layer['style']] = true;
										}
									}
								}
							}
						}

					}catch(Exception $e){
						$errorMessage = "ERROR: ".$e->getMessage();
					}
				}
			}

			if(!empty($classes)){
				$captions = array();
				foreach($classes as $class => $val){
					$captionCheck = RevSliderOperations::getCaptionsContentArray($class);
					if(!empty($captionCheck)) $captions[] = $captionCheck;
				}

				$styles = RevSliderCssParser::parseArrayToCss($captions, "\n");
			}
		}
	}


	/**
	 *
	 * get contents of the css file
	 */
	public static function getCaptionsCssContentArray(){
		if(file_exists(RS_PLUGIN_PATH.'public/assets/css/captions.css'))
			$contentCSS = file_get_contents(RS_PLUGIN_PATH.'public/assets/css/captions.css');
		else if(file_exists(RS_PLUGIN_PATH.'public/assets/css/captions-original.css'))
			$contentCSS = file_get_contents(RS_PLUGIN_PATH.'public/assets/css/captions-original.css');
		else if(file_exists(RS_PLUGIN_PATH.'backup/'.'captions.css'))
			$contentCSS = file_get_contents(RS_PLUGIN_PATH.'backup/'.'captions.css');
		else if(file_exists(RS_PLUGIN_PATH.'backup/'.'captions-original.css'))
			$contentCSS = file_get_contents(RS_PLUGIN_PATH.'backup/'.'captions-original.css');
		else
			RevSliderFunctions::throwError("No captions.css found! This installation is incorrect, please make sure to reupload the Slider Revolution plugin and try again!");
		
		$result = RevSliderCssParser::parseCssToArray($contentCSS);

		return($result);
	}

	/**
	 *
	 * import contents of the css file
	 */
	public static function importCaptionsCssContentArray(){
		$db = new RevSliderDB();
		$css = self::getCaptionsCssContentArray();
		$static = array();
		if(is_array($css) && $css !== false && count($css) > 0){
			foreach($css as $class => $styles){
				//check if static style or dynamic style
				$class = trim($class);

				if((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
					strpos($class," ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
					strpos($class,".tp-caption") === false || // everything that is not tp-caption
					(strpos($class,".") === false || strpos($class,"#") !== false) || // no class -> #ID or img
					strpos($class,">") !== false){ //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img

					$static[$class] = $styles;
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
		}

		if(!empty($static)){ //save static into static-captions.css
			$css = RevSliderCssParser::parseStaticArrayToCss($static);
			$static_cur = RevSliderOperations::getStaticCss(); //get the open sans line!
			$css = $static_cur."\n".$css;

			self::updateStaticCss($css);
		}
	}

	/**
	 *
	 * move old captions.css and captions-original.css
	 */
	public static function moveOldCaptionsCss(){
		if(file_exists(RevSliderGlobals::$filepath_captions_original))
			$success = @rename(RevSliderGlobals::$filepath_captions_original, RevSliderGlobals::$filepath_backup.'/captions-original.css');

		if(file_exists(RevSliderGlobals::$filepath_captions))
			$success = @rename(RevSliderGlobals::$filepath_captions, RevSliderGlobals::$filepath_backup.'/captions.css');
	}

	/**
	 *
	 * preview slider output
	 * if output object is null - create object
	 */
	public function previewOutput($sliderID,$output = null){

		if($sliderID == "empty_output"){
			$this->loadingMessageOutput();
			exit();
		}

		if($output == null)
			$output = new RevSliderOutput();

		$slider = new RevSlider();
		$slider->initByID($sliderID);
		$isWpmlExists = RevSliderWpml::isWpmlExists();
		$useWpml = $slider->getParam("use_wpml","off");
		$wpmlActive = false;
		if($isWpmlExists && $useWpml == "on"){
			$wpmlActive = true;
			$arrLanguages = RevSliderWpml::getArrLanguages(false);

			//set current lang to output
			$currentLang = RevSliderFunctions::getPostGetVariable("lang");

			if(empty($currentLang))
				$currentLang = RevSliderWpml::getCurrentLang();

			if(empty($currentLang))
				$currentLang = $arrLanguages[0];

			$output->setLang($currentLang);

			$selectLangChoose = RevSliderFunctions::getHTMLSelect($arrLanguages,$currentLang,"id='select_langs'",true);
		}


		$output->setPreviewMode();

		//put the output html
		$urlPlugin = RS_PLUGIN_URL.'public/assets/';
		$urlPreviewPattern = RevSliderBase::$url_ajax_actions."&client_action=preview_slider&sliderid=".$sliderID."&lang=[lang]&nonce=[nonce]";
		$nonce = wp_create_nonce("revslider_actions");

		$setBase = (is_ssl()) ? "https://" : "http://";

		?>
			<html>
				<head>
					<link rel='stylesheet' href='<?php echo $urlPlugin?>css/settings.css?rev=<?php echo RevSliderGlobals::SLIDER_REVISION; ?>' type='text/css' media='all' />
					<link rel='stylesheet' href='<?php echo $urlPlugin?>fonts/font-awesome/css/font-awesome.css?rev=<?php echo RevSliderGlobals::SLIDER_REVISION; ?>' type='text/css' media='all' />
					<link rel='stylesheet' href='<?php echo $urlPlugin?>fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css?rev=<?php echo RevSliderGlobals::SLIDER_REVISION; ?>' type='text/css' media='all' />
					<?php
					$db = new RevSliderDB();

					$styles = $db->fetch(RevSliderGlobals::$table_css);
					$styles = RevSliderCssParser::parseDbArrayToCss($styles, "\n");
					$styles = RevSliderCssParser::compress_css($styles);

					echo '<style type="text/css">'.$styles.'</style>'; //.$stylesinnerlayers

					$http = (is_ssl()) ? 'https' : 'http';
					
					$operations = new RevSliderOperations();
					$arrValues = $operations->getGeneralSettingsValues();
					
					$set_diff_font = RevSliderFunctions::getVal($arrValues, "change_font_loading",'');
					if($set_diff_font !== ''){
						$font_url = $set_diff_font;
					}else{
						$font_url = $http.'://fonts.googleapis.com/css?family=';
					}

					$custom_css = RevSliderOperations::getStaticCss();
					echo '<style type="text/css">'.RevSliderCssParser::compress_css($custom_css).'</style>';
					?>

					<script type='text/javascript' src='<?php echo $setBase; ?>code.jquery.com/jquery-latest.min.js'></script>

					<script type='text/javascript' src='<?php echo $urlPlugin?>js/jquery.themepunch.tools.min.js?rev=<?php echo RevSliderGlobals::SLIDER_REVISION; ?>'></script>
					<script type='text/javascript' src='<?php echo $urlPlugin?>js/jquery.themepunch.revolution.min.js?rev=<?php echo RevSliderGlobals::SLIDER_REVISION; ?>'></script>
					
				</head>
				<body style="padding:0px;margin:0px;width:100%;height:100%;position:relative;">
					<?php
					if($wpmlActive == true){
						?>
						<div style="margin-bottom:10px;text-align:center;">
						<?php _e("Choose language",REVSLIDER_TEXTDOMAIN)?>: <?php echo $selectLangChoose?>
						</div>

						<script type="text/javascript">
							var g_previewPattern = '<?php echo $urlPreviewPattern; ?>';
							jQuery("#select_langs").change(function(){
								var lang = this.value;
								var nonce = "<?php echo $nonce; ?>";
								var pattern = g_previewPattern;
								var urlPreview = pattern.replace("[lang]",lang).replace("[nonce]",nonce);
								location.href = urlPreview;
							});
						</script>
						<?php
					}
					?>

					<?php
						$output->putSliderBase($sliderID);
					?>
				</body>
			</html>
		<?php
		exit();
	}

	/*
	 * show only the markup for jQuery version of plugin
	 */
	public function previewOutputMarkup($sliderID){
		$export_real = true; //if false, then kriki export for JavaScript Standalone version
		
		if($export_real){ //set all different file path's here
			$path_fonts = 'fonts/';
			$path_css = 'css/';
			$path_js = 'js/';
			$path_assets = 'assets';
			$path_assets_raw = 'assets';
			$path_assets_vid = 'assets';
			$path_assets_raw_vid = 'assets';
		}else{
			$path_fonts = '../../revolution/fonts/';
			$path_css = '../../revolution/css/';
			$path_js = '../../revolution/js/';
			$path_assets = '../../assets/images';
			$path_assets_raw = 'assets/images';
			$path_assets_vid = '../../assets/videos';
			$path_assets_raw_vid = 'assets/videos';
		}
		
		if(function_exists("unzip_file") == false && class_exists("ZipArchive") == false){
			echo __('ZipArchive extension not available, please enable it to use this functionality.', REVSLIDER_TEXTDOMAIN);
			exit;
		}
		
		$zip = new ZipArchive;
		$success = $zip->open(RevSliderGlobals::$uploadsUrlExportZip, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);
		
		if($success !== true){
			echo __("No write permissions. Can't create zip file: ", REVSLIDER_TEXTDOMAIN).RevSliderGlobals::$uploadsUrlExportZip;
			exit;
		}
		
		if($sliderID == "empty_output"){
			echo __("Wrong request!", REVSLIDER_TEXTDOMAIN);
			exit;
		}

		$output = new RevSliderOutput();
		$operations = new RevSliderOperations();

		$slider = new RevSlider();
		$slider->initByID($sliderID);
		
		$output->setPreviewMode();

		
		$http = (is_ssl()) ? 'https' : 'http';
		
		$arrValues = $operations->getGeneralSettingsValues();
		$set_diff_font = RevSliderFunctions::getVal($arrValues, "change_font_loading",'');
		if($set_diff_font !== ''){
			$font_url = $set_diff_font;
		}else{
			$font_url = $http.'://fonts.googleapis.com/css?family=';
		}

		$static_css = RevSliderOperations::getStaticCss();
		
		ob_start();
		$output->putSliderBase($sliderID, array(), true);
		$content = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		
		$fonts = '';
		while(strpos($content, '<!-- FONT -->') !== false){
			$temp_font = substr($content, strpos($content, '<!-- FONT -->'), strpos($content, '<!-- /FONT -->') + 14 - strpos($content, '<!-- FONT -->'))."\n";
			$fonts .= $temp_font;
			
			$starthtml = substr($content, 0, strpos($content, '<!-- FONT -->'));
			$endhtml = substr($content, strpos($content, '<!-- /FONT -->')+14);
			
			$content = $starthtml.$endhtml; //remove from html markup
		}
		$fonts = str_replace(array('<!-- FONT -->', '<!-- /FONT -->'), '', $fonts); //remove the tags
		
		$scripts = '';
		while(strpos($content, '<!-- SCRIPT -->') !== false){
			$temp_script = substr($content, strpos($content, '<!-- SCRIPT -->'), strpos($content, '<!-- /SCRIPT -->') + 16 - strpos($content, '<!-- SCRIPT -->'))."\n";
			$scripts .= $temp_script;
			
			$starthtml = substr($content, 0, strpos($content, '<!-- SCRIPT -->'));
			$endhtml = substr($content, strpos($content, '<!-- /SCRIPT -->')+16);
			
			$content = $starthtml.$endhtml; //remove from html markup
		}
		$scripts = str_replace(array('<!-- SCRIPT -->', '<!-- /SCRIPT -->'), '', $scripts); //remove the tags
		
		$styles = '';
		while(strpos($content, '<!-- STYLE -->') !== false){
			$temp_style = substr($content, strpos($content, '<!-- STYLE -->'), strpos($content, '<!-- /STYLE -->') + 15 - strpos($content, '<!-- STYLE -->'))."\n";
			$styles .= $temp_style;
			
			$starthtml = substr($content, 0, strpos($content, '<!-- STYLE -->'));
			$endhtml = substr($content, strpos($content, '<!-- /STYLE -->')+15);
			
			$content = $starthtml.$endhtml; //remove from html markup
		}
		$styles = str_replace(array('<!-- STYLE -->', '<!-- /STYLE -->'), '', $styles); //remove the tags
		
		$full_content = '';
		
		ob_start();
		?><!DOCTYPE html>
	<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
	<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
	<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $slider->getTitle(); ?> - Slider Revolution</title>
		<meta name="description" content="Slider Revolution Example" />
		<meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
		<meta name="author" content="ThemePunch" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- LOAD JQUERY LIBRARY -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
		
		<!-- LOADING FONTS AND ICONS -->
		<?php echo $fonts; ?>
		
		<link rel="stylesheet" type="text/css" href="<?php echo $path_fonts; ?>pe-icon-7-stroke/css/pe-icon-7-stroke.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $path_fonts; ?>font-awesome/css/font-awesome.min.css">
		
		<!-- REVOLUTION STYLE SHEETS -->
		<link rel="stylesheet" type="text/css" href="<?php echo $path_css; ?>settings.css">
		<!-- REVOLUTION LAYERS STYLES -->
		<?php 
		if($export_real){ 
			echo $styles;
			
			if($static_css !== ''){
				echo '<style type="text/css">';
				echo RevSliderCssParser::compress_css($static_css);
				echo '</style>'."\n";
			}
		}else{
			?>
			<link rel="stylesheet" type="text/css" href="<?php echo $path_css; ?>layers.css">
			
			<!-- REVOLUTION NAVIGATION STYLES -->
			<link rel="stylesheet" type="text/css" href="<?php echo $path_css; ?>navigation.css">
			
			<link rel="stylesheet" type="text/css" href="../../assets/css/noneed.css">
			<?php
		}
		?>
		<!-- REVOLUTION JS FILES -->
		<script type="text/javascript" src="<?php echo $path_js; ?>jquery.themepunch.tools.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>jquery.themepunch.revolution.min.js"></script>

		<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->	
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.actions.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.carousel.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.kenburn.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.layeranimation.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.migration.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.navigation.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.parallax.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.slideanims.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_js; ?>extensions/revolution.extension.video.min.js"></script>
	</head>
	
	<body>
		<?php if(!$export_real){ ?>
		<!-- HEADER -->
		<article class="content">
			<!-- Add your site or application content here -->
			<section class="header">
				<span class="logo" style="float:left"></span>
				<a class="button" style="float:right" href="http://www.themepunch.com/revsliderjquery-doc/get-started/"><i class="pe-7s-help2"></i>Online Documentation</a>
				<div class="clearfix"></div>
			</section>
		</article>

		<article class="small-history"> 
			<h2 class="textaligncenter" style="margin-bottom:25px;">Slideshow Gallery</h2>
			<p>The Slider below is a classic slideshow with timer, bullet and arrow navigation. Slider Revolution offers millions of layout combinations.</p>
		</article>

		<!-- SLIDER EXAMPLE -->
		<section class="example">
			<article class="content">
		<?php } ?>
	<?php
	$head = ob_get_contents();
	ob_clean();
	ob_end_clean();

	ob_start();
	?>
	<?php if(!$export_real){ ?>
			</article>
		</section>
	<?php } ?>
	</body>
</html>
<?php
$footer = ob_get_contents();
ob_clean();
ob_end_clean();

		$slider_html = $head."\n".$content."\n".$scripts."\n".$footer;
		
		$upload_dir = RevSliderFunctionsWP::getPathUploads();
		$upload_dir_multisiteless = wp_upload_dir();
		$cont_url = $upload_dir_multisiteless['baseurl'];
		$cont_url_no_www = str_replace('www.', '', $upload_dir_multisiteless['baseurl']);
		$upload_dir_multisiteless = $upload_dir_multisiteless['basedir'].'/';
		
		$search = array($cont_url, $cont_url_no_www, RS_PLUGIN_URL);
		
		$added = array();
		
		foreach($search as $s){
			preg_match_all("/(\"|')".str_replace('/', '\/', $s)."\S+(\"|')/", $slider_html, $_files);
			
			if(!empty($_files) && isset($_files[0]) && !empty($_files[0])){
				//go through all files, check for existance and add to the zip file
				foreach($_files[0] as $_file){
					$o = $_file;
					$_file = str_replace(array('"', "'", $s), '', $_file);
					
					//check if video or image
					$use_path = $path_assets;
					$use_path_raw = $path_assets_raw;
					
					preg_match('/.*?.(?:jpg|jpeg|gif|png)/i', $_file, $match);
					preg_match('/.*?.(?:ogv|webm|mp4)/i', $_file, $match2);
					$f = false;
					if(!empty($match) && isset($match[0]) && !empty($match[0])){
						//image
						$use_path = $path_assets;
						$use_path_raw = $path_assets_raw;
						$f = true;
					}
					if(!empty($match2) && isset($match2[0]) && !empty($match2[0])){
						//video
						$use_path = $path_assets_vid;
						$use_path_raw = $path_assets_raw_vid;
						$f = true;
					}
					
					if($f == false){ 
						//no file, just a location. So change the location accordingly by removing base and add ../../revolution
						if(strpos($o, 'public/assets/js/') !== false){ //this will be the jsFileLocation script part
							$slider_html = str_replace($o, '"'.$path_js.'"', $slider_html);
						}
						continue; //no correct file, nothing to add
					}
					
					if(isset($added[$_file])) continue;
					
					$add = '';
					$__file = '';
					$repl_to = explode('/', $_file);
					$repl_to = end($repl_to);
					
					$remove = false;
					
					if(is_file($upload_dir.$_file)){
						$zip->addFile($upload_dir.$_file, $use_path_raw.'/'.$repl_to);
						$remove = true;
					}elseif(is_file($upload_dir_multisiteless.$_file)){
						$zip->addFile($upload_dir_multisiteless.$_file, $use_path_raw.'/'.$repl_to);
						$remove = true;
					}elseif(is_file(RS_PLUGIN_PATH.$_file)){
						//remove admin/assets/
						$__file = str_replace('admin/assets/images/', '', $_file);
						$zip->addFile(RS_PLUGIN_PATH.$_file, $use_path_raw.'/'.$__file);
						$remove = true;
						$add = '/';
					}

					if($remove == true){
						$added[$_file] = true; //set as added
						//replace file with new path
						if($add !== '') $_file = $__file; //set the different path here
						$slider_html = str_replace($o, '"'.$use_path.'/'.$repl_to.'"', $slider_html);
					}
				}
				
			}
		}
		
		if($export_real){ //only include if real export
			//add common files to the zip
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.actions.min.js', 'js/extensions/revolution.extension.actions.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.carousel.min.js', 'js/extensions/revolution.extension.carousel.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.kenburn.min.js', 'js/extensions/revolution.extension.kenburn.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.layeranimation.min.js', 'js/extensions/revolution.extension.layeranimation.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.migration.min.js', 'js/extensions/revolution.extension.migration.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.navigation.min.js', 'js/extensions/revolution.extension.navigation.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.parallax.min.js', 'js/extensions/revolution.extension.parallax.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.slideanims.min.js', 'js/extensions/revolution.extension.slideanims.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/extensions/revolution.extension.video.min.js', 'js/extensions/revolution.extension.video.min.js');
			
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/jquery.themepunch.enablelog.js', 'js/jquery.themepunch.enablelog.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/jquery.themepunch.revolution.min.js', 'js/jquery.themepunch.revolution.min.js');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/js/jquery.themepunch.tools.min.js', 'js/jquery.themepunch.tools.min.js');
			
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/css/settings.css', 'css/settings.css');
			
			
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css', 'fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/pe-icon-7-stroke/css/helper.css', 'fonts/pe-icon-7-stroke/css/helper.css');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.eot', 'fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.eot');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.svg', 'fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.svg');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.ttf', 'fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.ttf');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.woff', 'fonts/pe-icon-7-stroke/fonts/Pe-icon-7-stroke.woff');
			
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/font-awesome/css/font-awesome.min.css', 'fonts/font-awesome/css/font-awesome.min.css');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/font-awesome/fonts/FontAwesome.otf', 'fonts/font-awesome/fonts/FontAwesome.otf');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/font-awesome/fonts/fontawesome-webfont.eot', 'fonts/font-awesome/fonts/fontawesome-webfont.eot');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/font-awesome/fonts/fontawesome-webfont.svg', 'fonts/font-awesome/fonts/fontawesome-webfont.svg');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/font-awesome/fonts/fontawesome-webfont.ttf', 'fonts/font-awesome/fonts/fontawesome-webfont.ttf');
			$zip->addFile(RS_PLUGIN_PATH.'/public/assets/fonts/font-awesome/fonts/fontawesome-webfont.woff', 'fonts/font-awesome/fonts/fontawesome-webfont.woff');
			
			
			$notice_text = "";
			$notice_text .= __('Using this data is only allowed with a valid licence of the jQuery Slider Revolution Plugin, which can be found at CodeCanyon: http://codecanyon.net/item/slider-revolution-responsive-jquery-plugin/2580848?ref=themepunch', REVSLIDER_TEXTDOMAIN);
			$zip->addFromString("NOTICE.txt", $notice_text); //add slider settings
			
		}
		
		$zip->addFromString("slider.html", $slider_html); //add slider settings
		
		$zip->close();
		
		header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename=".sanitize_title($slider->getAlias()).".zip");
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile(RevSliderGlobals::$uploadsUrlExportZip);
		
		@unlink(RevSliderGlobals::$uploadsUrlExportZip); //delete file after sending it to user
		exit();
	}

	/**
	 *
	 * output loading message
	 */
	public function loadingMessageOutput(){
		?>
		<div class="message_loading_preview"><?php _e("Loading Preview...",REVSLIDER_TEXTDOMAIN)?></div>
		<?php
	}

	/**
	 *
	 * put slide preview by data
	 */
	public function putSlidePreviewByData($data){

		if($data == "empty_output"){
			$this->loadingMessageOutput();
			exit();
		}

		$data = RevSliderFunctions::jsonDecodeFromClientSide($data);

		$slideID = $data["slideid"];
		$slide = new RevSlide();
		$slide->initByID($slideID);
		$sliderID = $slide->getSliderID();

		$output = new RevSliderOutput();
		$output->setOneSlideMode($data);

		$this->previewOutput($sliderID,$output);
	}


	/**
	 * update general settings
	 */
	public function updateGeneralSettings($data){

		$strSettings = serialize($data);
		update_option('revslider-global-settings', $data);
		
	}


	/**
	 *
	 * get general settigns values.
	 */
	static function getGeneralSettingsValues(){
		
		$arrValues = get_option('revslider-global-settings', '');
		
		$arrValues = maybe_unserialize($arrValues);

		return($arrValues);
	}


	/**
	 *
	 * modify custom slider params. This is instead custom settings difficulties.
	 */
	public function modifyCustomSliderParams($data){

		$arrNames = array("width","height",
						  "responsitive_w1","responsitive_sw1",
						  "responsitive_w2","responsitive_sw2",
						  "responsitive_w3","responsitive_sw3",
						  "responsitive_w4","responsitive_sw4",
						  "responsitive_w5","responsitive_sw5",
						  "responsitive_w6","responsitive_sw6");

		$arrMain = $data["main"];
		foreach($arrNames as $name){
			if(array_key_exists($name, $arrMain)){
				$arrMain[$name] = floatval($arrMain[$name]);
				if(!is_numeric($arrMain[$name])) $arrMain[$name] = 0;
			}
		}

		if(!isset($arrMain["fullscreen_offset_container"])) $arrMain["fullscreen_offset_container"] = '';
		
		$arrMain["fullscreen_offset_container"] = $arrMain["fullscreen_offset_container"];

		$data["main"] = $arrMain;

		return($data);
	}


	/**
	 *
	 * get post types with categories for client side.
	 */
	public static function getPostTypesWithCatsForClient(){
		$arrPostTypes = RevSliderFunctionsWP::getPostTypesWithCats();

		$globalCounter = 0;

		$arrOutput = array();
		foreach($arrPostTypes as $postType => $arrTaxWithCats){

			$arrCats = array();
			foreach($arrTaxWithCats as $tax){
				$taxName = $tax["name"];
				$taxTitle = $tax["title"];
				$globalCounter++;
				$arrCats["option_disabled_".$globalCounter] = "---- ".$taxTitle." ----";
				foreach($tax["cats"] as $catID=>$catTitle){
					$arrCats[$taxName."_".$catID] = $catTitle;
				}
			}//loop tax

			$arrOutput[$postType] = $arrCats;

		}//loop types

		return($arrOutput);
	}


	/**
	 *
	 * get html font import
	 */
	public static function getCleanFontImport($font){
		$setBase = (is_ssl()) ? "https://" : "http://";

		if(strpos($font, "href=") === false){ //fallback for old versions
			return '<link href="'.$setBase.'fonts.googleapis.com/css?family='.$font.'" rel="stylesheet" property="stylesheet" type="text/css" media="all" />'; //id="rev-google-font"
		}else{
			$font = str_replace(array('http://', 'https://'), array($setBase, $setBase), $font);
			return html_entity_decode(stripslashes($font));
		}
	}


	public function checkPurchaseVerification($data){
		global $wp_version;

		$response = wp_remote_post('http://updates.themepunch.tools/activate.php', array(
			'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
			'body' => array(
				'name' => urlencode($data['username']),
				'api' => urlencode($data['api_key']),
				'code' => urlencode($data['code']),
				'product' => urlencode('revslider')
			)
		));

		$response_code = wp_remote_retrieve_response_code( $response );
		$version_info = wp_remote_retrieve_body( $response );

		if ( $response_code != 200 || is_wp_error( $version_info ) ) {
			return false;
		}

		if($version_info == 'valid'){
			update_option('revslider-valid', 'true');
			update_option('revslider-api-key', $data['api_key']);
			update_option('revslider-username', $data['username']);
			update_option('revslider-code', $data['code']);

			return true;
		}elseif($version_info == 'exist'){
			RevSliderFunctions::throwError(__('Purchase Code already registered!', REVSLIDER_TEXTDOMAIN));
		}else{
			return false;
		}

	}

	public function doPurchaseDeactivation($data){
		global $wp_version;

		$key = get_option('revslider-api-key', '');
		$name = get_option('revslider-username', '');
		$code = get_option('revslider-code', '');

		$response = wp_remote_post('http://updates.themepunch.tools/deactivate.php', array(
			'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
			'body' => array(
				'name' => urlencode($name),
				'api' => urlencode($key),
				'code' => urlencode($code),
				'product' => urlencode('revslider')
			)
		));

		$response_code = wp_remote_retrieve_response_code( $response );
		$version_info = wp_remote_retrieve_body( $response );

		if ( $response_code != 200 || is_wp_error( $version_info ) ) {
			return false;
		}

		if($version_info == 'valid'){
			update_option('revslider-valid', 'false');
			return true;
		}else{
			return false;
		}

	}
	

	public static function get_performance($val, $min, $max) {
		
		if ($val==0) $val = 1;
		$arr = array();
		//print_r(($max-$min)."/".($val-$min)."=");
		$arr["proc"] = (($max-$min) / ($val-$min))*100;
		//print_r($arr["proc"]."  --> ");
		

		if ($arr["proc"]>100) $arr["proc"] = 100;
		if ($arr["proc"]<0) $arr["proc"] = 0;

		if ($arr["proc"]<35) $arr["col"] = "slow";
			else
		if ($arr["proc"]<75) $arr["col"] = "ok";
			else
		//print_r($arr["proc"]." <br>");


		$arr["col"] = "fast";



		return $arr;
	}
	
	
	/**
	 * view the estimated speed of the Slider
	 * @since: 5.0
	 */
	public static function get_slider_speed($sliderID){
		//$data = wp_get_attachment_metadata($cur_img_id);
		
		ob_start();

		$total_size = 0;
		
		$do_ssl = (is_ssl()) ? 'http:' : 'https:';
		
		$slider = new RevSliderSlider();
		$slider->initByID($sliderID);
		$slides = $slider->getSlidesForExport();
		
		$static_slides = $slider->getStaticSlideForExport();
		if(!empty($static_slides) && is_array($static_slides)){
			foreach($static_slides as $s_slide){
				$slides[] = $s_slide;
			}
		}
		
		$used_images = array();
		$used_videos = array();
		$used_captions = array();
		
		$using_kenburns = false;
		$using_parallax = false;
		$using_carousel = false;
		$using_navigation = false;
		$using_videos = false;
		$using_actions = false;
		$using_layeranim = false;
		
		$img_size = 0;
		$video_size = 0;
		$slide_counter = 0;
		$firstslide_size = 0;
		$smartslide_size = 0;
		
		if($slider->getParam("use_parallax","off") == 'on')
			$using_parallax = true;
		
		if($slider->getParam("slider-type","standard") == 'carousel')
			$using_carousel = true;
		
		$enable_arrows = $slider->getParam('enable_arrows','off');
		$enable_bullets = $slider->getParam('enable_bullets','off');
		$enable_tabs = $slider->getParam('enable_tabs','off');
		$enable_thumbnails = $slider->getParam('enable_thumbnails','off');
		
		if($enable_arrows == 'on' || $enable_bullets == 'on' || $enable_tabs == 'on' || $enable_thumbnails == 'on')
			$using_navigation = true;
		
		if(!empty($slides) && count($slides) > 0){
			foreach($slides as $key => $slide){
				
				if(isset($slide['params']['state']) && $slide['params']['state'] != 'published') continue;
				if(!isset($slide['id'])) continue;
				
				$slide_counter++;

				$slide_id = $slide['id'];
				
				if(isset($slide['params']['kenburn_effect']) && $slide['params']['kenburn_effect'] == 'on')
					$using_kenburns = true;
				
				
				if(!isset($slide['params']['image_source_type'])) $slide['params']['image_source_type'] = 'full';
				
				if(isset($slide['params']['image']) && $slide['params']['image'] != ''){
					//add infos of image to an array
					$infos = array();
					$urlImage = false;
					
					switch($slide['params']['background_type']){
						case 'streamyoutube':
						case 'streaminstagram':
						case 'streamvimeo':
						case 'youtube':
						case 'vimeo':
							$using_videos = true;
						break;
					}
					
					if(isset($slide['params']['image_id'])){
						$cur_img_id = $slide['params']['image_id'];
						//get image sizes by ID
						$urlImage = wp_get_attachment_image_src($slide['params']['image_id'], $slide['params']['image_source_type']);
					}
					if($urlImage === false){
						$cur_img_id = RevSliderFunctionsWP::get_image_id_by_url($slide['params']['image']);
						if($cur_img_id !== false){
							$urlImage = wp_get_attachment_image_src($cur_img_id, $slide['params']['image_source_type']);
						}
					}
					
					if($urlImage !== false){
						$infos['id'] = $cur_img_id;
						$file = get_attached_file( $cur_img_id );
						$infos['info'] = pathinfo( $file );
						
						if(file_exists( $file )){
							$infos['size'] = filesize( $file );
							$infos['size-format'] = size_format($infos['size'], 2);
							$img_size += $infos['size'];
							if ($slide_counter==1)
								$firstslide_size += $infos['size'];
							if ($slide_counter==1 || $slide_counter==2 || $slide_counter==count($slides))
								$smartslide_size += $infos['size'];
						}else{
							$infos['id'] = false;
						}
					}else{
						$infos['id'] = 'external';
					}
					
					if(strpos($slide_id, 'static_') !== false){
						$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id=static_'.$sliderID);
					}else{
						$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id='.$slide_id);
					}
					
					$used_images[$slide['params']['image']] = $infos;


				}
				
				if(isset($slide['layers']) && !empty($slide['layers']) && count($slide['layers']) > 0){
					
					$using_layeranim = true;
					
					foreach($slide['layers'] as $lKey => $layer){
						switch($layer['type']){
							case 'image':
								$infos = array();
								if(isset($layer['image_url']) && trim($layer['image_url']) != ''){
									
									$cur_img_id = RevSliderFunctionsWP::get_image_id_by_url($layer['image_url']);
									if($cur_img_id !== false){
										if(!isset($layer['layer-image-size']) || $layer['layer-image-size'] == 'auto') $layer['layer-image-size'] = $slide['params']['image_source_type'];
										
										$urlImage = wp_get_attachment_image_src($cur_img_id, $layer['layer-image-size']);
										
										if($urlImage !== false){
											$infos['id'] = $cur_img_id;
											$file = get_attached_file( $cur_img_id );
											$infos['info'] = pathinfo( $file );
											if(file_exists( $file )){
												$infos['size'] = filesize( $file );
												$infos['size-format'] = size_format($infos['size'], 2);
												$img_size += $infos['size'];
												if ($slide_counter==1)
													$firstslide_size += $infos['size'];
												if ($slide_counter==1 || $slide_counter==2 || $slide_counter==count($slides))
													$smartslide_size += $infos['size'];
												
											}else{
												$infos['id'] = false;
											}
										}else{
											$infos['id'] = 'external';
										}
									}else{
										$infos['id'] = 'external';
									}
									
									if(strpos($slide_id, 'static_') !== false){
										$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id=static_'.$sliderID);
									}else{
										$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id='.$slide_id);
									}
									
									$used_images[$layer['image_url']] = $infos; //image_url if image caption
								}
							break;
							case 'video':
								$using_videos = true;
								
								//get cover image if existing
								$infos = array();
								$poster_img = array();
								if(isset($layer['video_data']) && isset($layer['video_data']->urlPoster)){
									$poster_img[] = $layer['video_data']->urlPoster;
								}
								if(isset($layer['video_image_url']) && isset($layer['video_image_url'])){
									$poster_img[] = $layer['video_image_url'];
								}
								if(!empty($poster_img)){
									foreach($poster_img as $img){
										if(trim($img) == '') continue;
										
										$cur_img_id = RevSliderFunctionsWP::get_image_id_by_url($img);
										
										if($cur_img_id !== false){
											if(!isset($layer['layer-image-size']) || $layer['layer-image-size'] == 'auto') $layer['layer-image-size'] = $slide['params']['image_source_type'];
											
											$urlImage = wp_get_attachment_image_src($cur_img_id, $layer['layer-image-size']);
											
											if($urlImage !== false){
												$infos['id'] = $cur_img_id;
												$file = get_attached_file( $cur_img_id );
												$infos['info'] = pathinfo( $file );
												if(file_exists( $file )){
													$infos['size'] = filesize( $file );
													$infos['size-format'] = size_format($infos['size'], 2);
													$img_size += $infos['size'];
													if ($slide_counter==1)
														$firstslide_size += $infos['size'];
													if ($slide_counter==1 || $slide_counter==2 || $slide_counter==count($slides))
														$smartslide_size += $infos['size'];
												}else{
													$infos['id'] = false;
												}
											}else{
												$infos['id'] = 'external';
											}
										}else{
											$infos['id'] = 'external';
										}
										
										if(strpos($slide_id, 'static_') !== false){
											$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id=static_'.$sliderID);
										}else{
											$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id='.$slide_id);
										}
										
										$used_images[$img] = $infos; //image_url if image caption
									}
								}
								
								$infos = array();
								if(isset($layer['video_type'])){
									//add videos and try to get video size
									if(isset($layer['video_data'])){
										$video_arr = array();
										$max_video_size = 0;
										
										if(strpos($slide_id, 'static_') !== false){
											$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id=static_'.$sliderID);
										}else{
											$infos['url'] = RevSliderBaseAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDE, 'id='.$slide_id);
										}
										
										switch($layer['video_type']){
											case 'html5':
												if(isset($layer['video_data']->urlMp4) && !empty($layer['video_data']->urlMp4)) $video_arr['mp4'] = $layer['video_data']->urlMp4;
												if(isset($layer['video_data']->urlWebm) && !empty($layer['video_data']->urlWebm)) $video_arr['webm'] = $layer['video_data']->urlWebm;
												if(isset($layer['video_data']->urlOgv) && !empty($layer['video_data']->urlOgv)) $video_arr['mp4'] = $layer['video_data']->urlOgv;
												if(!empty($video_arr)){
													foreach($video_arr as $type => $url){
														$cur_id = RevSliderFunctionsWP::get_image_id_by_url($url);
														
														if($cur_id !== false){
															$infos['id'] = $cur_id;
															$file = get_attached_file( $cur_id );
															$infos['info'] = pathinfo( $file );
															if(file_exists( $file )){
																$infos['size'] = filesize( $file );
																$infos['size-format'] = size_format($infos['size'], 2);
																if($infos['size'] > $max_video_size) $max_video_size = $infos['size']; //add only the largest video of the three here as each browser loads only one file and we can add here the biggest
															}else{
																$infos['id'] = 'external';
															}
														}else{
															$infos['id'] = 'external';
														}
														
														$used_videos[$url] = $infos;
													}
													
													$video_size += $max_video_size;
												}
											break;
											case 'youtube':
												$infos['id'] = 'external';
												if(!isset($layer['video_data']->id) || empty($layer['video_data']->id)) continue;
												$used_videos[$do_ssl.'//www.youtube.com/watch?v='.$layer['video_data']->id] = $infos;
											break;
											case 'vimeo':
												if(!isset($layer['video_data']->id) || empty($layer['video_data']->id)) continue;
												$infos['id'] = 'external';
												$used_videos[$do_ssl.'//vimeo.com/'.$layer['video_data']->id] = $infos;
											break;
										}
										
									}
								}
							break;
						}
						
						//check captions for actions
						if(isset($layer['layer_action']) && !empty($layer['layer_action'])){
							
							$a_action = RevSliderFunctions::cleanStdClassToArray(RevSliderFunctions::getVal($layer['layer_action'], 'action', array()));
							$a_link_type = RevSliderFunctions::cleanStdClassToArray(RevSliderFunctions::getVal($layer['layer_action'], 'link_type', array()));
							
							if(!empty($a_action)){
								foreach($a_action as $num => $action){
									if($using_actions == true) break;
									
									if($action !== 'link'){
										$using_actions = true;
									}else{
										//check if jQuery or a tag
										if($a_link_type[$num] == 'jquery') $using_actions = true;
									}
								}
							}
							
						}
						
						if(isset($layer['style']) && $layer['style'] != '') $used_captions[$layer['style']] = true;
					}
				}
			}
		}



		$total_size += $img_size;

		$img_counter = 0;
		$issues ="";
		//$total_size += $video_size;		
		?>

		<span class="tp-clearfix" style="height:15px"></span>
		<hr>
		<span class="tp-clearfix" style="height:25px"></span>
		
		<!-- HEADER OF MONITORING -->
		<span class="tp-monitor-performance-title"><?php echo __("Overall Slider Performance",REVSLIDER_TEXTDOMAIN); ?></span>
		<span class="tp-monitor-performace-wrap">
			<span id="image-performace-bar" style="width: %overall_performance%%" class="tp-monitor-performance-bar mo-%overall_color%-col"></span>
			<span class="tp-monitor-slow"><?php echo __("Slow",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-ok"><?php echo __("Ok",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-fast"><?php echo __("Fast",REVSLIDER_TEXTDOMAIN); ?></span>
		</span>
		<span class="tp-clearfix" style="height:50px"></span>
		
		<span  class="tp-monitor-speed-table tp-monitor-single-speed">
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed UMTS:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="umts-speed">%umtsspeed-single%</span>
			</span>
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed DSL:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="dsl-speed">%dslspeed-single%</span>
			</span>
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed T1:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="t1-speed">%t1speed-single%</span>
			</span>
		</span>

		<span  class="tp-monitor-speed-table tp-monitor-smart-speed">
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed UMTS:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="umts-speed">%umtsspeed-smart%</span>
			</span>
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed DSL:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="dsl-speed">%dslspeed-smart%</span>
			</span>
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed T1:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="t1-speed">%t1speed-smart%</span>
			</span>
		</span>

		<span class="tp-monitor-speed-table tp-monitor-all-speed">
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed UMTS:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="umts-speed">%umtsspeed-all%</span>
			</span>
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed DSL:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="dsl-speed">%dslspeed-all%</span>
			</span>
			<span class="tp-monitor-speed-cell">
				<span class="tp-monitor-smalllabel"><?php echo __("Load Speed T1:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-total-subsize" id="t1-speed">%t1speed-all%</span>
			</span>
		</span>

		<span class="tp-clearfix" style="height:25px"></span>
		<span style="float:left;width:165px">
			<span class="tp-monitor-smalllabel"><?php echo __("Total Slider Size:",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-fullsize">%overall_size%</span>
			<a class="button-primary revblue tp-monitor-showdetails" data-target="#performance_overall_details" style="float:right; width:160px;vertical-align:top"><i class="eg-icon-chart-bar"></i>Show Full Statistics</a>
		</span>
		<span style="float:right; width:165px">
			<span class="tp-monitor-smalllabel"><?php echo __("Preloaded Slides Size:",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-fullsize tp-monitor-single-speed">%firstslide_size%</span>
			<span class="tp-monitor-fullsize tp-monitor-smart-speed">%smartslide_size%</span>
			<span class="tp-monitor-fullsize tp-monitor-all-speed">%allslide_size%</span>		
			<a class="button-primary revred tp-monitor-showdetails" data-target="#monitor-problems" style="float:right; width:160px;vertical-align:top;"><i class="eg-icon-info"></i>Show All Issues</a>
		</span>		
		<span class="tp-clearfix" style="height:15px"></span>
		<hr>
		<span class="tp-clearfix" style="height:25px"></span>
		
		<!-- THE IMAGE PERFORMANCE MESSING -->
		<div id="monitor-problems" style="display:none">
			<span class="tp-monitor-performance-title"><?php echo __("Need Some Attention",REVSLIDER_TEXTDOMAIN); ?></span>			
			<span class="tp-clearfix" style="height:25px"></span>
			<ul class="tp-monitor-list" id="monitor-problem-details" style="margin-bottom:15px;">
			%issues%
			</ul>
			<span class="tp-clearfix" style="height:15px"></span>
			<hr>
			<span class="tp-clearfix" style="height:25px"></span>
		</div>

		<div id="performance_overall_details" style="display:none">

		<!-- IMAGE LIST -->
		<?php
		if(!empty($used_images)){
			?>
			<!-- THE IMAGE PERFORMANCE MESSING -->
			<span class="tp-monitor-performance-title"><?php echo __("Image Performance",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-performace-wrap">
				<span id="image-performace-bar" style="width: %image_performance%%" class="tp-monitor-performance-bar mo-%image_color%-col"></span>
				<span class="tp-monitor-slow"><?php echo __("Slow",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-ok"><?php echo __("Ok",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-fast"><?php echo __("Fast",REVSLIDER_TEXTDOMAIN); ?></span>
			</span>

			<span class="tp-clearfix" style="height:35px"></span>

			<!-- FULL SIZE OF SUBCATEGORY && SHOW/HIDE LIST -->
			<span style="float:left;width:40%">
				<span class="tp-monitor-smalllabel"><?php echo __("Images Loaded:",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-imageicon"></span>
				<span id="image_sub_size" class="tp-monitor-total-subsize"><?php echo size_format($img_size,2); ?></span>
				</span>
			<span style="float:left;width:60%; text-align:right;">
				<span class="tp-monitor-showdetails" data-target="#monitor-image-details" data-open="</span><?php echo __("Hide Details",REVSLIDER_TEXTDOMAIN); ?>" data-close="</span><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?></span></span>
			</span>
			<span class="tp-clearfix" style="height:15px"></span>
			<!-- THE IMAGE LIST -->
			
				<ul class="tp-monitor-list" id="monitor-image-details" style="display:none;margin-bottom:15px;">
				<?php
				foreach($used_images as $path => $image){		
					$_li = '<li class="tp-monitor-listli">';
					

					if(isset($image['size'])) {
						$img_counter++;
						if ($image['size']<200000) 
							$_li .=  '<span class="tp-monitor-good"></span>';
						else
						if ($image['size']<400000) 
							$_li .=   '<span class="tp-monitor-well"></span>';
						else					
							$_li .=   '<span class="tp-monitor-warning"></span>';
						
						if ($image['size']>1000000) 
							$_li .=   '<span class="tp-monitor-size">'.size_format($image['size'],2).'</span>';
						else
							$_li .=   '<span class="tp-monitor-size">'.size_format($image['size'],0).'</span>';
					}else{
						if($image['id'] == 'external'){
							$_li .=   '<span class="tp-monitor-neutral"></span><span class="tp-monitor-size">'.__('extern', REVSLIDER_TEXTDOMAIN).'</span>';
						}else{
							$_li .=   '<span class="tp-monitor-warning"></span><span class="tp-monitor-size">'.__('missing', REVSLIDER_TEXTDOMAIN).'</span>';
						}
					}
					
					$_li .=   '<span class="tp-monitor-file">';
					if(!isset($image['info']['basename']) || empty($image['info']['basename'])){
						$_li .=   '...'.substr($path,-20);
					}else{
						$_li .=   substr($image['info']['basename'],-20);
					}
					$_li .=   '</span>';
					
					
					if(isset($image['url'])) {
						//$_li .=   ' <a href="'.$image['url'].'" target="_blank" class="tp-monitor-showimage"></a>';
						$_li .=   ' <a href="'.$image['url'].'" target="_blank" class="tp-monitor-linktoslide"></a>';
						
					}
					
					$_li .=   '</li>';
					echo $_li;
					if ((isset($image['size']) && $image['size']>199999) || (!isset($image['size']) && !$image['id'] == 'external'))
						$issues .= $_li; 
				}

				?>
				</ul>

			<?php		
		}
		?>
			
		<!-- VIDEO LIST -->
		<?php
		if(!empty($used_videos)){
		?>
			<span class="tp-clearfix" style="height:15px"></span>
			<hr>
			<span class="tp-clearfix" style="height:25px"></span>
			
			<!-- THE VIDEO PERFORMANCE MESSING -->
			<span class="tp-monitor-performance-title"><?php echo __("Video Performance",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-performace-wrap">
				<span id="video-performace-bar" style="width:50%" class="tp-monitor-performance-bar mo-neutral-col"></span>
				<span class="tp-monitor-slow"><?php echo __("Slow",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-ok"><?php echo __("Ok",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-fast"><?php echo __("Fast",REVSLIDER_TEXTDOMAIN); ?></span>
			</span>
			
			<span class="tp-clearfix" style="height:35px"></span>

			<!-- FULL SIZE OF SUBCATEGORY && SHOW/HIDE LIST -->
			<span style="float:left;width:40%; display:block">				
				<span class="tp-monitor-smalllabel"><?php echo __("Videos Loaded (max):",REVSLIDER_TEXTDOMAIN); ?></span>
				<?php if ($video_size>0) {?>				
					<span class="tp-monitor-imageicon"></span>
					<span id="video_sub_size" class="tp-monitor-total-subsize"><?php echo size_format($video_size,2); ?></span>
				<?php } else {?>
					<span class="tp-monitor-imageicon"></span>
					<span class="tp-monitor-total-subsize"><?php echo __("Unknown",REVSLIDER_TEXTDOMAIN); ?></span>
				<?php } ?>
			</span>
			<span style="float:left;width:60%; text-align:right;">
				<span class="tp-monitor-showdetails" data-target="#monitor-video-details" data-open="</span><?php echo __("Hide Details",REVSLIDER_TEXTDOMAIN); ?>" data-close="</span><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?></span></span>
			</span>
			<span class="tp-clearfix" style="height:15px"></span>
			
			<ul class="tp-monitor-list" id="monitor-video-details" style="margin-bottom:15px;display:none;">
			<?php
			foreach($used_videos as $path => $video){				
				$_li = '<li class="tp-monitor-listli">';
				
				if(isset($video['size'])){
					$_li .= '	<span class="tp-monitor-neutral"></span>';
					
					if ($video['size']>1000000) 
						$_li .= '<span class="tp-monitor-size">'.size_format($video['size'],2).'</span>';
					else
						$_li .= '<span class="tp-monitor-size">'.size_format($video['size'],0).'</span>';
				}else{
					if($video['id'] == 'external'){
						$_li .= '<span class="tp-monitor-neutral"></span><span class="tp-monitor-size">'.__('extern', REVSLIDER_TEXTDOMAIN).'</span>';
					}else{
						$_li .= '<span class="tp-monitor-warning"></span><span class="tp-monitor-size">'.__('missing', REVSLIDER_TEXTDOMAIN).'</span>';
					}
				}
				
				$_li .= '<span class="tp-monitor-file">';
				if(!isset($video['info']['basename']) || empty($video['info']['basename'])){
					$_li .= '...'.substr($path,-20);
				}else{
					$_li .= substr($video['info']['basename'],-20); 
				}
				$_li .= '</span>';

				if(isset($image['url'])) {						
						$_li .= ' <a href="'.$video['url'].'" target="_blank" class="tp-monitor-linktoslide"></a>';						
					}
				
				$_li .= '</li>';
				if (!isset($video['size']) && !$video['id'] == 'external')
					$issues .= $_li;
				echo $_li;
			}
			?>
			</ul>
			<?php
		}
		
		
		$css_size = 0;
		?>

		<span class="tp-clearfix" style="height:15px"></span>
		<hr>
		<span class="tp-clearfix" style="height:25px"></span>

		<!-- THE IMAGE PERFORMANCE MESSING -->
		<span class="tp-monitor-performance-title"><?php echo __("CSS Performance",REVSLIDER_TEXTDOMAIN); ?></span>
		<span class="tp-monitor-performace-wrap">
			<span id="image-performace-bar" style="width:%css_performance%%" class="tp-monitor-performance-bar mo-%css_color%-col"></span>
			<span class="tp-monitor-slow"><?php echo __("Slow",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-ok"><?php echo __("Ok",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-fast"><?php echo __("Fast",REVSLIDER_TEXTDOMAIN); ?></span>
		</span>

		<span class="tp-clearfix" style="height:35px"></span>

		<!-- FULL SIZE OF SUBCATEGORY && SHOW/HIDE LIST -->
		<span style="float:left;width:40%">
			<span class="tp-monitor-smalllabel"><?php echo __("CSS Loaded:",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-cssicon"></span><span id="css_sub_size" class="tp-monitor-total-subsize">%css_size%</span>
			</span>
		<span style="float:left;width:60%; text-align:right;">
			<span class="tp-monitor-showdetails" data-target="#monitor-CSS-details" data-open="</span><?php echo __("Hide Details",REVSLIDER_TEXTDOMAIN); ?>" data-close="</span><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?></span></span>
		</span>
		<span class="tp-clearfix" style="height:15px"></span>

		<?php
		//get css files
		echo '<ul class="tp-monitor-list" id="monitor-CSS-details" style="margin-bottom:15px;display:none;">';

		if(file_exists( RS_PLUGIN_PATH . '/public/assets/css/settings.css' )){
			$fs = filesize( RS_PLUGIN_PATH . '/public/assets/css/settings.css' );
			$_li ='<li class="tp-monitor-listli">';
			if ($fs<60000) 
				$_li .= '<span class="tp-monitor-good"></span>';
			else
			if ($fs<100000) 
				$_li .= '<span class="tp-monitor-well"></span>';
			else					
			$_li .= '<span class="tp-monitor-warning"></span>';
		
			$_li .= '<span class="tp-monitor-size">'.size_format($fs,0).'</span>';			
			$_li .= '<span class="tp-monitor-file">';
			$_li .= __('css/settings.css',REVSLIDER_TEXTDOMAIN);
			$_li .= '</span>';
		
			$_li .= '</li>';

			if ($fs>99999)
				$issues .=$_li;

			echo $_li;
			
			$total_size += $fs;
			$css_size += $fs;
		}

		$custom_css = RevSliderOperations::getStaticCss();
		$custom_css = RevSliderCssParser::compress_css($custom_css);

		$_li = '<li class="tp-monitor-listli">';
		if (strlen($custom_css)<50000) 
			$_li .= '<span class="tp-monitor-good"></span>';
		else
		if (strlen($custom_css)<100000) 
			$_li .= '<span class="tp-monitor-well"></span>';
		else					
			$_li .= '<span class="tp-monitor-warning"></span>';
	
		$_li .= '<span class="tp-monitor-size">'.size_format(strlen($custom_css),0).'</span>';			
		$_li .= '<span class="tp-monitor-file">';
		$_li .= __('Static Styles',REVSLIDER_TEXTDOMAIN);
		$_li .= '</span>';
		
		$_li .= '</li>';

		if (strlen($custom_css)>49999)
				$issues .=$_li;

			echo $_li;

		$total_size += strlen($custom_css);
		$css_size += strlen($custom_css);
		
		
		
		if(!empty($used_captions)){
			$captions = array();
			foreach($used_captions as $class => $val){
				$cap = RevSliderOperations::getCaptionsContentArray($class);
				if(!empty($cap))
					$captions[] = $cap;
			}
			$styles = RevSliderCssParser::parseArrayToCss($captions, "\n");
			$styles = RevSliderCssParser::compress_css($styles);
			
			$_li = '<li class="tp-monitor-listli">';
			if (strlen($styles)<50000) 
				$_li .=  '<span class="tp-monitor-good"></span>';
			else
			if (strlen($styles)<100000) 
				$_li .=  '<span class="tp-monitor-well"></span>';
			else					
				$_li .=  '<span class="tp-monitor-warning"></span>';
		
			$_li .=  '<span class="tp-monitor-size">'.size_format(strlen($styles),0).'</span>';			
			$_li .=  '<span class="tp-monitor-file">';
			$_li .=  __('Dynamic Styles',REVSLIDER_TEXTDOMAIN);
			$_li .=  '</span>';
			
			$_li .=  '</li>';
			if (strlen($styles)>49999)
				$issues .=$_li;

			echo $_li;

			$total_size += strlen($styles);
			$css_size += strlen($styles);
		}
		echo '</ul>';			
		echo ' <span style="display:none" id="css-size-hidden">'.size_format($css_size, 2).'</span>';
		
		
		
		$js_size = 0;
		
		?>
		<span class="tp-clearfix" style="height:15px"></span>
			<hr>
			<span class="tp-clearfix" style="height:25px"></span>
			
			<!-- THE jQuery PERFORMANCE MESSING -->
			<span class="tp-monitor-performance-title"><?php echo __("jQuery Performance",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-performace-wrap">
				<span id="video-performace-bar" style="width:%js_performance%%" class="tp-monitor-performance-bar mo-%js_color%-col"></span>
				<span class="tp-monitor-slow"><?php echo __("Slow",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-ok"><?php echo __("Ok",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-fast"><?php echo __("Fast",REVSLIDER_TEXTDOMAIN); ?></span>
			</span>
			
			<span class="tp-clearfix" style="height:35px"></span>

			<!-- FULL SIZE OF SUBCATEGORY && SHOW/HIDE LIST -->
			<span style="float:left;width:40%; display:block">				
				<span class="tp-monitor-smalllabel"><?php echo __("jQuery Loaded:",REVSLIDER_TEXTDOMAIN); ?></span>				
				<span class="tp-monitor-imageicon"></span><span id="jquery_sub_size" class="tp-monitor-total-subsize">%js_size%</span>				
			</span>
			<span style="float:left;width:60%; text-align:right;">
				<span class="tp-monitor-showdetails" data-target="#monitor-jquery-details" data-open="</span><?php echo __("Hide Details",REVSLIDER_TEXTDOMAIN); ?>" data-close="</span><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?></span></span>
			</span>
			<span class="tp-clearfix" style="height:15px"></span>
		
		<?php 
		echo '<ul class="tp-monitor-list" id="monitor-jquery-details" style="margin-bottom:15px;display:none">';
		
		$jsfiles = array(
			'jquery.themepunch.tools.min.js' => RS_PLUGIN_PATH . '/public/assets/js/jquery.themepunch.tools.min.js',
			'jquery.themepunch.revolution.min.js' => RS_PLUGIN_PATH . '/public/assets/js/jquery.themepunch.revolution.min.js',
			
		);
		//check which js files will be used by the Slider
		if($using_kenburns == true) $jsfiles['revolution.extension.kenburn.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.kenburn.min.js';
		if($using_parallax == true) $jsfiles['revolution.extension.parallax.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.parallax.js';
		if($using_navigation == true) $jsfiles['revolution.extension.navigation.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.navigation.min.js';
		if($using_videos == true) $jsfiles['revolution.extension.video.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.video.min.js';
		if($using_actions == true) $jsfiles['revolution.extension.actions.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.actions.min.js';
		if($using_layeranim == true) $jsfiles['revolution.extension.layeranimation.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.layeranimation.min.js';
		if($using_carousel == true)
			$jsfiles['revolution.extension.carousel.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.carousel.min.js';
		else
			$jsfiles['revolution.extension.slideanims.min.js'] = RS_PLUGIN_PATH . '/public/assets/js/extensions/revolution.extension.slideanims.min.js';
			
		
		//get the js files
		foreach($jsfiles as $name => $path){
			if(file_exists( $path )){
				$fs = filesize( $path );
				echo '<li class="tp-monitor-listli">';			
				echo '<span class="tp-monitor-good"></span>';											
				echo '<span class="tp-monitor-size">'.size_format($fs,0).'</span>';			
				echo '<span class="tp-monitor-file">';
				echo $name;
				echo '</span>';		
				echo '</li>';
				$total_size += $fs;
				$js_size += $fs;
			}
		}
		
		echo '</ul>';
		echo ' <span style="display:none" id="css-size-hidden">'.size_format($js_size, 2).'</span>';

		
		$http = (is_ssl()) ? 'https' : 'http';
		
		$operations = new RevSliderOperations();
		$arrValues = $operations->getGeneralSettingsValues();
		
		$set_diff_font = RevSliderFunctions::getVal($arrValues, "change_font_loading",'');
		if($set_diff_font !== ''){
			$font_url = $set_diff_font;
		}else{
			$font_url = $http.'://fonts.googleapis.com/css?family=';
		}
		
		$my_fonts = $slider->getParam('google_font', array());
		
		?>
		<span class="tp-clearfix" style="height:15px"></span>
			<hr>
			<span class="tp-clearfix" style="height:25px"></span>
			
			<!-- THE Fonts PERFORMANCE MESSING -->
			<span class="tp-monitor-performance-title"><?php echo __("Google Fonts Performance",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="tp-monitor-performace-wrap">
				<span id="video-performace-bar" style="width:%font_performance%%" class="tp-monitor-performance-bar mo-%font_color%-col"></span>
				<span class="tp-monitor-slow"><?php echo __("Slow",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-ok"><?php echo __("Ok",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="tp-monitor-fast"><?php echo __("Fast",REVSLIDER_TEXTDOMAIN); ?></span>
			</span>
			
			<span class="tp-clearfix" style="height:35px"></span>

			<!-- FULL SIZE OF SUBCATEGORY && SHOW/HIDE LIST -->
			<span style="float:left;width:40%; display:block">				
				<span class="tp-monitor-smalllabel"><?php echo __("Fonts Loaded:",REVSLIDER_TEXTDOMAIN); ?></span>				
				<span class="tp-monitor-jsicon"></span><span class="tp-monitor-total-subsize">%font_size%</span>				
			</span>
			<span style="float:left;width:60%; text-align:right;">
				<span class="tp-monitor-showdetails" data-target="#monitor-fonts-details" data-open="</span><?php echo __("Hide Details",REVSLIDER_TEXTDOMAIN); ?>" data-close="</span><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo __("Show Details",REVSLIDER_TEXTDOMAIN); ?></span></span>
			</span>
			<span class="tp-clearfix" style="height:15px"></span>
		
		<?php 		
		//echo '<span class="tp-monitor-smalllabel">'.$font_url.'</span>';
		
		echo '<ul class="tp-monitor-list" id="monitor-fonts-details" style="margin-bottom:15px;display:none">';
		$all_font_count = 0;
		if(!empty($my_fonts)){
			foreach($my_fonts as $c_font){
				$fcount = RevSliderBase::get_font_weight_count($c_font);

				$_li = '<li class="tp-monitor-listli">';			

				if ($fcount<4)
					$_li .= '<span class="tp-monitor-good"></span>';															
				else
				if ($fcount<7)
					$_li .= '<span class="tp-monitor-well"></span>';															
				else				
					$_li .= '<span class="tp-monitor-warning"></span>';															
				
				
				$_li .= '<span class="tp-monitor-file">';
				$_li .= strip_tags($c_font);
				$_li .= '</span>';		
				$_li .= '</li>';	
				if ($fcount > 4)
					$issues .= $_li;
				echo $_li;		
				$all_font_count += $fcount;	
			}
		}		
		echo '</ul>';

		?>
		</div><!-- END OF OVERALL Div-->
		
		<script>
			jQuery(document).on("ready",function() {
				
				jQuery('body').on('click','.tp-monitor-showdetails',function() {
					var bt = jQuery(this);
					if (bt.hasClass("selected")) {
						bt.find('.tp-show-inner-btn').html(bt.data('close'));
						bt.removeClass("selected");
						jQuery(bt.data('target')).slideUp(200);
					} else {
						bt.find('.tp-show-inner-btn').html(bt.data('open'));
						bt.addClass("selected");
						jQuery(bt.data('target')).slideDown(200);
					}
					
				})
			})
		</script>
		<?php

		$content = ob_get_contents();
		ob_end_clean();
		
		if ($img_counter ==0) $img_counter = 1;
		if ($slide_counter ==0) $slide_counter = 1;

		$overall = RevSliderOperations::get_performance($total_size/$slide_counter, 0,400000); // 400KB / Slide is ok
		$image = RevSliderOperations::get_performance($img_size/$img_counter, 0,100000); // 100KB Image OK
		$css = RevSliderOperations::get_performance($css_size, 0,150000); // 150KB CSS OK
		$js = RevSliderOperations::get_performance($js_size, 0,250000); // 250KB Image OK
		$font = RevSliderOperations::get_performance($all_font_count, 0,15); // 250KB Image OK
		$firstslide_size += $js_size;
		$firstslide_size += $css_size;
		$smartslide_size += $js_size;
		$smartslide_size += $css_size;

		$content = str_replace("%overall_performance%",$overall["proc"],$content);
		$content = str_replace("%overall_color%",$overall["col"],$content);
		$content = str_replace("%overall_size%",size_format($total_size,2),$content);
		
		$content = str_replace("%image_performance%",$image["proc"],$content);
		$content = str_replace("%image_color%",$image["col"],$content);
		
		$content = str_replace("%css_performance%",$css["proc"],$content);
		$content = str_replace("%css_color%",$css["col"],$content);
		$content = str_replace("%css_size%",size_format($css_size,2),$content);
		
		$content = str_replace("%js_performance%",$js["proc"],$content);
		$content = str_replace("%js_color%",$js["col"],$content);		
		$content = str_replace("%js_size%",size_format($js_size,2),$content);

		$content = str_replace("%font_performance%",$font["proc"],$content);
		$content = str_replace("%font_color%",$font["col"],$content);
		$content = str_replace("%font_size%",$all_font_count,$content);

		$content = str_replace("%issues%", $issues, $content);
		$content = str_replace("%firstslide_size%", size_format($firstslide_size,2), $content);
		$content = str_replace("%smartslide_size%", size_format($smartslide_size,2), $content);
		$content = str_replace("%allslide_size%", size_format($total_size,2), $content);

		$total_size = $total_size / 1000;
		$content = str_replace("%umtsspeed-all%", gmdate('i:s',$total_size/48), $content);
		$content = str_replace("%dslspeed-all%", gmdate('i:s',$total_size/307), $content);
		$content = str_replace("%t1speed-all%", gmdate('i:s',$total_size/1180), $content);

		$firstslide_size = $firstslide_size / 1000;
		$content = str_replace("%umtsspeed-single%", gmdate('i:s',$firstslide_size/48), $content);
		$content = str_replace("%dslspeed-single%", gmdate('i:s',$firstslide_size/307), $content);
		$content = str_replace("%t1speed-single%", gmdate('i:s',$firstslide_size/1180), $content);

		$smartslide_size = $smartslide_size / 1000;
		$content = str_replace("%umtsspeed-smart%", gmdate('i:s',$smartslide_size/48), $content);
		$content = str_replace("%dslspeed-smart%", gmdate('i:s',$smartslide_size/307), $content);
		$content = str_replace("%t1speed-smart%", gmdate('i:s',$smartslide_size/1180), $content);
		echo $content;
	}
	
	
	
	/**
	 * these are the specific slider settings, which the user can switch between, for easier usage
	 * @since: 5.0
	 */
	public static function get_preset_settings(){
		/**
		 * List of Elements based on version 5.0 (may be incomplete)
			arrows_always_on "true"
			auto_height "off"
			background_color "#333"
			background_dotted_overlay "none"
			background_image "http://server.local/revslider/wp-content/uploads/"
			bg_fit "cover"
			bg_position "center center"
			bg_repeat "no-repeat"
			bullets_align_hor "center"
			bullets_align_vert "bottom"
			bullets_always_on "true"
			bullets_direction "horizontal"
			bullets_offset_hor "0"
			bullets_offset_vert "20"
			bullets_space "5"
			carousel_borderr "0"
			carousel_borderr_unit "px"
			carousel_fadeout "off"
			carousel_hposition "center"
			carousel_infinity false
			carousel_maxitems "3"
			carousel_maxrotation "0"
			carousel_rotation "off"
			carousel_scale "off"
			carousel_scaledown "50"
			carousel_space "0"
			carousel_stretch "off"
			carousel_varyrotate "off"
			carousel_varyscale "off"
			carousel_vposition "center"
			client_action "import_slider"
			custom_css ""
			custom_javascript "jQuery(window).on('scrol...return ismobile;\n }"
			delay "9000"
			disable_kenburns_on_mobile "off"
			disable_on_mobile "off"
			disable_parallax_mobile "off"
			drag_block_vertical "off"
			enable_arrows "on"
			enable_bullets "on"
			enable_progressbar "off"
			enable_tabs "off"
			enable_thumbnails "off"
			export_dummy_images false
			first_transition_active "on"
			first_transition_duration "300"
			first_transition_slot_amount "7"
			first_transition_type "fade"
			full_screen_align_force "off"
			fullscreen_min_height ""
			fullscreen_offset_container ""
			fullscreen_offset_size ""
			hide_all_layers_under "0"
			hide_arrows "200"
			hide_arrows_on_mobile "off"
			hide_bullets "200"
			hide_bullets_on_mobile "off"
			hide_defined_layers_under "0"
			hide_slider_under "0"
			hide_tabs "200"
			hide_thumbs "200"
			hide_thumbs_delay_mobile "1500"
			hide_thumbs_on_mobile "off"
			hide_thumbs_under_resolution "0"
			image_source_type "full"
			jquery_noconflict "on"
			js_to_body "true"
			keyboard_navigation "off"
			lazy_load_type "none"
			leftarrow_align_hor "left"
			leftarrow_align_vert "center"
			leftarrow_offset_hor "10"
			leftarrow_offset_vert "0"
			loop_slide "off"
			margin_bottom "0"
			margin_left "0"
			margin_right "0"
			margin_top "0"
			min_height "0"
			navigation_arrow_style "round"
			navigation_bullets_style "round"
			next_slide_on_window_focus "off"
			nonce "a638dbd494"
			output_type "none"
			padding "0"
			parallax_bg_freeze "off"
			parallax_level_1 "5"
			parallax_level_10 "50"
			parallax_level_2 "10"
			parallax_level_3 "15"
			parallax_level_4 "20"
			parallax_level_5 "25"
			parallax_level_6 "30"
			parallax_level_7 "35"
			parallax_level_8 "40"
			parallax_level_9 "45"
			parallax_type "mouse"
			position "center"
			progress_height "5"
			progress_opa "15"
			progressbar_color "#000000"
			rightarrow_align_hor "right"
			rightarrow_align_vert "center"
			rightarrow_offset_hor "10"
			rightarrow_offset_vert "0"
			shadow_type "0"
			show_alternate_image ""
			show_alternative_type "off"
			show_background_image "on"
			show_timerbar "top"
			shuffle "on"
			simplify_ie8_ios4 "off"
			sliderid "56"
			span_tabs_wrapper "off"
			span_thumbnails_wrapper "off"
			spinner_color "#FFFFFF"
			start_js_after_delay "0"
			start_with_slide "1"
			stop_after_loops "0"
			stop_at_slide "2"
			stop_on_hover "on"
			stop_slider "on"
			swipe_min_touches "1"
			swipe_velocity "75"
			tabs_align_hor "center"
			tabs_align_vert "bottom"
			tabs_always_on "true"
			tabs_amount "5"
			tabs_direction "horizontal"
			tabs_height "50"
			tabs_inner_outer "inner"
			tabs_offset_hor "0"
			tabs_offset_vert "20"
			tabs_padding "5"
			tabs_space "5"
			tabs_style "custom"
			tabs_width "100"
			tabs_wrapper_color "transparent"
			tabs_wrapper_opacity "5"
			thumb_amount "4"
			thumb_height "75"
			thumb_width "120"
			thumbnail_direction "horizontal"
			thumbnails_align_hor "center"
			thumbnails_align_vert "bottom"
			thumbnails_inner_outer "inner"
			thumbnails_offset_hor "0"
			thumbnails_offset_vert "20"
			thumbnails_padding "5"
			thumbnails_space "5"
			thumbnails_wrapper_color "transparent"
			thumbnails_wrapper_opacity "5"
			thumbs_always_on "true"
			touchenabled "on"
			update_animations "true"
			update_static_captions "true"
			use_parallax "off"
			use_spinner "0"
			use_wpml false
		 **/
		 
		$presets = array();
		
		//ThemePunch default presets are added here directly
		
		//preset -> standardpreset || heropreset || carouselpreset

$presets[] = array (
    'settings' => array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/slideshow_auto_layout.png', 'name' => 'Slideshow-Auto', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'on',
      'stop_slider' => 'off',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'round',
      'arrows_always_on' => 'true',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'on',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'round',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '100',
      'thumb_height' => '50',
      'thumb_width_min' => '100',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '20',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'auto',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
  
$presets[] = array (
    'settings' => 
    array ( 'class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/slideshow_auto_layout.png', 'name' => 'Slideshow-Full-Width', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'on',
      'stop_slider' => 'off',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'round',
      'arrows_always_on' => 'true',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'on',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'round',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '100',
      'thumb_height' => '50',
      'thumb_width_min' => '100',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '20',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );

$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/slideshow_auto_layout.png', 'name' => 'Slideshow-Full-Screen', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'on',
      'stop_slider' => 'off',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'round',
      'arrows_always_on' => 'true',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'on',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'round',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '100',
      'thumb_height' => '50',
      'thumb_width_min' => '100',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '20',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullscreen',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
  
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/thumb_auto1.png', 'name' => 'Thumbs-Bottom-Auto', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'on',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '20',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'auto',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
  
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/thumbs_left_auto.png', 'name' => 'Thumbs-Left-Auto', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'right',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '40',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '0',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'left',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'auto',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
  
$presets[] = array (
    'settings' => array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/thumbs_right_auto.png', 'name' => 'Thumbs-Right-Auto', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '0',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'left',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '40',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'auto',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/scroll_fullscreen.png', 'name' => 'Vertical-Bullet-Full-Screen', 'preset' => 'standardpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'off',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '0',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'left',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '40',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'vertical',
      'bullets_always_on' => 'false',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'right',
      'bullets_align_vert' => 'center',
      'bullets_offset_hor' => '30',
      'bullets_offset_vert' => '0',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullscreen',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '500',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/wide_fullscreen.png', 'name' => 'Wide-Full-Screen', 'preset' => 'heropreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'off',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '0',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'left',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '40',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'vertical',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'right',
      'bullets_align_vert' => 'center',
      'bullets_offset_hor' => '30',
      'bullets_offset_vert' => '0',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullscreen',
      'width' => '1400',
      'width_notebook' => '1240',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '868',
      'height_notebook' => '768',
      'height_tablet' => '960',
      'height_mobile' => '720',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/wide_fullscreen.png', 'name' => 'Wide-Full-Width', 'preset' => 'heropreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'off',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '0',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'left',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '40',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'vertical',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'right',
      'bullets_align_vert' => 'center',
      'bullets_offset_hor' => '30',
      'bullets_offset_vert' => '0',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '1400',
      'width_notebook' => '1240',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '500',
      'height_tablet' => '400',
      'height_mobile' => '400',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/wide_fullscreen.png', 'name' => 'Regular-Full-Screen', 'preset' => 'heropreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'off',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '0',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'left',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '40',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'vertical',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'right',
      'bullets_align_vert' => 'center',
      'bullets_offset_hor' => '30',
      'bullets_offset_vert' => '0',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullscreen',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '868',
      'height_notebook' => '768',
      'height_tablet' => '960',
      'height_mobile' => '720',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/wide_fullscreen.png', 'name' => 'Regular-Full-Width', 'preset' => 'heropreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'off',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'off',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'bottom',
      'leftarrow_offset_hor' => '0',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'left',
      'rightarrow_align_vert' => 'bottom',
      'rightarrow_offset_hor' => '40',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'on',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'vertical',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'right',
      'bullets_align_vert' => 'center',
      'bullets_offset_hor' => '30',
      'bullets_offset_vert' => '0',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '5',
      'span_thumbnails_wrapper' => 'off',
      'thumbnails_wrapper_color' => 'transparent',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '5',
      'thumbnails_space' => '5',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '50',
      'thumb_height' => '50',
      'thumb_width_min' => '50',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'on',
      'thumbs_under_hidden' => '778',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'center',
      'thumbnails_offset_hor' => '20',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'off',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'off',
      'carousel_maxrotation' => '0',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '50',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '1240',
      'width_notebook' => '1024',
      'width_tablet' => '778',
      'width_mobile' => '480',
      'height' => '600',
      'height_notebook' => '500',
      'height_tablet' => '400',
      'height_mobile' => '300',
      'enable_custom_size_notebook' => 'on',
      'enable_custom_size_tablet' => 'on',
      'enable_custom_size_iphone' => 'on',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_javascript' => '',
      'custom_css' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/cover_carousel_thumbs.png', 'name' => 'Cover-Flow-Thumbs', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'navbar-old',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#000000',
      'thumbnails_wrapper_opacity' => '15',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'outer-bottom',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'off',
      'carousel_space' => '-150',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '5',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'on',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'on',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '600',
      'width_notebook' => '600',
      'width_tablet' => '600',
      'width_mobile' => '600',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '600',
      'height_mobile' => '600',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/cover_carousel_endless.png', 'name' => 'Cover-Flow-Infinite', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'round',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#000000',
      'thumbnails_wrapper_opacity' => '15',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'outer-bottom',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '-150',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'on',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'on',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '600',
      'width_notebook' => '600',
      'width_tablet' => '600',
      'width_mobile' => '600',
      'height' => '600',
      'height_notebook' => '600',
      'height_tablet' => '600',
      'height_mobile' => '600',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/flat_carousel_thumbs.png', 'name' => 'Flat-Infinite-Thumbs', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => '#111111',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'navbar',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#222222',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'outer-bottom',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '720',
      'width_notebook' => '720',
      'width_tablet' => '720',
      'width_mobile' => '720',
      'height' => '405',
      'height_notebook' => '405',
      'height_tablet' => '405',
      'height_mobile' => '405',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/flat_carousel.png', 'name' => 'Flat-Infinite', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => '#111111',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'uranus',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'off',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#222222',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'outer-bottom',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '720',
      'width_notebook' => '720',
      'width_tablet' => '720',
      'width_mobile' => '720',
      'height' => '405',
      'height_notebook' => '405',
      'height_tablet' => '405',
      'height_mobile' => '405',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/flat_carousel_thumbs_left.png', 'name' => 'Flat-Thumbs-Left', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => '#111111',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'uranus',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#222222',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'outer-left',
      'thumbnails_align_hor' => 'left',
      'thumbnails_align_vert' => 'top',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '720',
      'width_notebook' => '720',
      'width_tablet' => '720',
      'width_mobile' => '720',
      'height' => '405',
      'height_notebook' => '405',
      'height_tablet' => '405',
      'height_mobile' => '405',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/carousel_thumbs_right_fullscreen.png', 'name' => 'Full-Screen-Thumbs-Right', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',      
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => '#111111',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'uranus',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#222222',
      'thumbnails_wrapper_opacity' => '100',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'vertical',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'outer-right',
      'thumbnails_align_hor' => 'right',
      'thumbnails_align_vert' => 'top',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '0',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => 'px',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '3',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'off',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullscreen',
      'width' => '900',
      'width_notebook' => '720',
      'width_tablet' => '720',
      'width_mobile' => '720',
      'height' => '720',
      'height_notebook' => '405',
      'height_tablet' => '405',
      'height_mobile' => '405',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/cover_carousel_thumbs.png', 'name' => 'Cover-Flow-Full-Screen', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',
      'first_transition_active' => 'on',
      'first_transition_type' => 'fade',
      'first_transition_duration' => '1500',
      'first_transition_slot_amount' => '7',
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'navbar-old',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#000000',
      'thumbnails_wrapper_opacity' => '15',
      'thumbnails_style' => 'navbar',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '-150',
      'carousel_borderr' => '0',
      'carousel_borderr_unit' => '%',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '5',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'on',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'on',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullscreen',
      'width' => '800',
      'width_notebook' => '600',
      'width_tablet' => '600',
      'width_mobile' => '600',
      'height' => '800',
      'height_notebook' => '600',
      'height_tablet' => '600',
      'height_mobile' => '600',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
$presets[] = array (
    'settings' => 
    array ('class' => '', 'image' => RS_PLUGIN_URL.'admin/assets/images/sliderpresets/carousel_full_rounded.png', 'name' => 'Cover-Flow-Rounded', 'preset' => 'carouselpreset' ),
    'values' => 
    array (
      'next_slide_on_window_focus' => 'off',
      'delay' => '9000',
      'start_js_after_delay' => '0',
      'image_source_type' => 'full',
      0 => 'revapi39.bind(\\"revolution.slide.layeraction\\",function (e) {
	//data.eventtype - Layer Action (enterstage, enteredstage, leavestage,leftstage)
	//data.layertype - Layer Type (image,video,html)
	//data.layersettings - Default Settings for Layer
	//data.layer - Layer as jQuery Object
});',
      'start_with_slide' => '1',
      'first_transition_active' => 'on',
      'first_transition_type' => 'fade',
      'first_transition_duration' => '1500',
      'first_transition_slot_amount' => '7',
      'stop_on_hover' => 'off',
      'stop_slider' => 'on',
      'stop_after_loops' => '0',
      'stop_at_slide' => '1',
      'shuffle' => 'off',      
      'viewport_start' => 'wait',
      'viewport_area' => '80',
      'enable_progressbar' => 'on',                  
      'background_dotted_overlay' => 'none',
      'background_color' => 'transparent',
      'padding' => '0',
      'show_background_image' => 'off',
      'background_image' => '',
      'bg_fit' => 'cover',
      'bg_repeat' => 'no-repeat',
      'bg_position' => 'center center',
      'position' => 'center',      
      'use_spinner' => '-1',
      'spinner_color' => '#FFFFFF',
      'enable_arrows' => 'on',
      'navigation_arrow_style' => 'round',
      'arrows_always_on' => 'false',
      'hide_arrows' => '200',
      'hide_arrows_mobile' => '1200',
      'hide_arrows_on_mobile' => 'off',
      'arrows_under_hidden' => '600',
      'hide_arrows_over' => 'off',
      'arrows_over_hidden' => '0',
      'leftarrow_align_hor' => 'left',
      'leftarrow_align_vert' => 'center',
      'leftarrow_offset_hor' => '30',
      'leftarrow_offset_vert' => '0',
      'rightarrow_align_hor' => 'right',
      'rightarrow_align_vert' => 'center',
      'rightarrow_offset_hor' => '30',
      'rightarrow_offset_vert' => '0',
      'enable_bullets' => 'off',
      'navigation_bullets_style' => 'round-old',
      'bullets_space' => '5',
      'bullets_direction' => 'horizontal',
      'bullets_always_on' => 'true',
      'hide_bullets' => '200',
      'hide_bullets_mobile' => '1200',
      'hide_bullets_on_mobile' => 'on',
      'bullets_under_hidden' => '600',
      'hide_bullets_over' => 'off',
      'bullets_over_hidden' => '0',
      'bullets_align_hor' => 'center',
      'bullets_align_vert' => 'bottom',
      'bullets_offset_hor' => '0',
      'bullets_offset_vert' => '30',
      'enable_thumbnails' => 'on',
      'thumbnails_padding' => '20',
      'span_thumbnails_wrapper' => 'on',
      'thumbnails_wrapper_color' => '#000000',
      'thumbnails_wrapper_opacity' => '0',
      'thumbnails_style' => 'preview1',
      'thumb_amount' => '9',
      'thumbnails_space' => '10',
      'thumbnail_direction' => 'horizontal',
      'thumb_width' => '60',
      'thumb_height' => '60',
      'thumb_width_min' => '60',
      'thumbs_always_on' => 'false',
      'hide_thumbs' => '200',
      'hide_thumbs_mobile' => '1200',
      'hide_thumbs_on_mobile' => 'off',
      'thumbs_under_hidden' => '0',
      'hide_thumbs_over' => 'off',
      'thumbs_over_hidden' => '0',
      'thumbnails_inner_outer' => 'inner',
      'thumbnails_align_hor' => 'center',
      'thumbnails_align_vert' => 'bottom',
      'thumbnails_offset_hor' => '0',
      'thumbnails_offset_vert' => '0',
      'enable_tabs' => 'off',
      'tabs_padding' => '5',
      'span_tabs_wrapper' => 'off',
      'tabs_wrapper_color' => 'transparent',
      'tabs_wrapper_opacity' => '5',
      'tabs_style' => '',
      'tabs_amount' => '5',
      'tabs_space' => '5',
      'tabs_direction' => 'horizontal',
      'tabs_width' => '100',
      'tabs_height' => '50',
      'tabs_width_min' => '100',
      'tabs_always_on' => 'false',
      'hide_tabs' => '200',
      'hide_tabs_mobile' => '1200',
      'hide_tabs_on_mobile' => 'off',
      'tabs_under_hidden' => '0',
      'hide_tabs_over' => 'off',
      'tabs_over_hidden' => '0',
      'tabs_inner_outer' => 'inner',
      'tabs_align_hor' => 'center',
      'tabs_align_vert' => 'bottom',
      'tabs_offset_hor' => '0',
      'tabs_offset_vert' => '20',
      'touchenabled' => 'on',
      'drag_block_vertical' => 'off',
      'swipe_velocity' => '75',
      'swipe_min_touches' => '50',
      'swipe_direction' => 'horizontal',
      'keyboard_navigation' => 'off',
      'keyboard_direction' => 'horizontal',
      'mousescroll_navigation' => 'off',
      'carousel_infinity' => 'on',
      'carousel_space' => '-150',
      'carousel_borderr' => '50',
      'carousel_borderr_unit' => '%',
      'carousel_padding_top' => '0',
      'carousel_padding_bottom' => '0',
      'carousel_maxitems' => '5',
      'carousel_stretch' => 'off',
      'carousel_fadeout' => 'on',
      'carousel_varyfade' => 'on',
      'carousel_rotation' => 'off',
      'carousel_varyrotate' => 'on',
      'carousel_maxrotation' => '65',
      'carousel_scale' => 'on',
      'carousel_varyscale' => 'off',
      'carousel_scaledown' => '55',
      'carousel_hposition' => 'center',
      'carousel_vposition' => 'center',
      'use_parallax' => 'on',
      'disable_parallax_mobile' => 'off',
      'parallax_type' => 'mouse',
      'parallax_origo' => 'slidercenter',
      'parallax_speed' => '2000',
      'parallax_level_1' => '2',
      'parallax_level_2' => '3',
      'parallax_level_3' => '4',
      'parallax_level_4' => '5',
      'parallax_level_5' => '6',
      'parallax_level_6' => '7',
      'parallax_level_7' => '12',
      'parallax_level_8' => '16',
      'parallax_level_9' => '10',
      'parallax_level_10' => '50',
      'lazy_load_type' => 'smart',
      'seo_optimization' => 'none',
      'simplify_ie8_ios4' => 'off',
      'show_alternative_type' => 'off',
      'show_alternate_image' => '',
      'jquery_noconflict' => 'off',
      'js_to_body' => 'false',
      'output_type' => 'none',
      'jquery_debugmode' => 'off',
      'slider_type' => 'fullwidth',
      'width' => '800',
      'width_notebook' => '600',
      'width_tablet' => '600',
      'width_mobile' => '600',
      'height' => '800',
      'height_notebook' => '600',
      'height_tablet' => '600',
      'height_mobile' => '600',
      'enable_custom_size_notebook' => 'off',
      'enable_custom_size_tablet' => 'off',
      'enable_custom_size_iphone' => 'off',
      'main_overflow_hidden' => 'off',
      'auto_height' => 'off',
      'min_height' => '',
      'custom_css' => '',
      'custom_javascript' => '',
    ),
  );
		
		//add the presets made from customers
		$customer_presets = get_option('revslider_presets', array());
		
		
		$presets = array_merge($presets, $customer_presets);
		
		$presets = apply_filters('revslider_slider_presets', $presets);
		
		foreach($presets as $key => $preset){
			if(intval($preset['settings']['image']) > 0){
				$img = wp_get_attachment_image_src(esc_attr($preset['settings']['image']), 'medium');
				$presets[$key]['settings']['image'] = ($img !== false) ? $img['0'] : '';
			}
		}
		
		return $presets;
		
	}
	
	/**
	 * 
	 * @since: 5.0
	 **/
	public static function add_preset_setting($data){
		
		if(!isset($data['settings']) || !isset($data['values'])) return __('Missing values to add preset', REVSLIDER_TEXTDOMAIN);
		
		$customer_presets = get_option('revslider_presets', array());
		
		$data['settings']['custom'] = true;
		
		$customer_presets[] = array(
								'settings' => $data['settings'],
								'values' => $data['values']
							);
		
		update_option('revslider_presets', $customer_presets);
		
		return true;
	}
	
	
	/**
	 * @since: 5.0
	 **/
	public static function remove_preset_setting($data){
		
		if(!isset($data['name'])) return __('Missing values to remove preset', REVSLIDER_TEXTDOMAIN);
		
		$customer_presets = get_option('revslider_presets', array());
		
		if(!empty($customer_presets)){
			foreach($customer_presets as $key => $preset){
				if($preset['settings']['name'] == $data['name']){
					unset($customer_presets[$key]);
					break;				
				}
			}
		}
		
		update_option('revslider_presets', $customer_presets);
		
		return true;
	}
	
	
	/**
	 * @since: 5.0
	 **/
	public static function update_preset_setting($data){
		
		if(!isset($data['name'])) return __('Missing values to update preset', REVSLIDER_TEXTDOMAIN);
		
		$customer_presets = get_option('revslider_presets', array());
		
		if(!empty($customer_presets)){
			foreach($customer_presets as $key => $preset){
				if($preset['settings']['name'] == $data['name']){
					$customer_presets[$key]['values'] = $data['values'];
					break;				
				}
			}
		}
		
		update_option('revslider_presets', $customer_presets);
		
		return true;
	}
	
}


/**
 * old classname extends new one (old classnames will be obsolete soon)
 * @since: 5.0
 **/
class RevOperations extends RevSliderOperations {}
?>