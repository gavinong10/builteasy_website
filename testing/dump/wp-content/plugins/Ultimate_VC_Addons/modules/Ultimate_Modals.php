<?php
/*
* Add-on Name: Ultimate Modals
* Add-on URI: https://www.brainstormforce.com
*/
if(!class_exists('Ultimate_Modals'))
{
	class Ultimate_Modals
	{
		function __construct()
		{
			// Add shortcode for modal popup
			add_shortcode('ultimate_modal', array(&$this, 'modal_shortcode' ) );
			// Initialize the modal popup component for Visual Composer
			add_action('init', array( $this, 'ultimate_modal_init' ) );
			add_action("wp_enqueue_scripts", array($this, "register_modal_assets"),1);
		}
		function register_modal_assets()
		{
			$bsf_dev_mode = bsf_get_option('dev_mode');
			if($bsf_dev_mode === 'enable') {
				$js_path = '../assets/js/';
				$css_path = '../assets/css/';
				$ext = '';
			}
			else {
				$js_path = '../assets/min-js/';
				$css_path = '../assets/min-css/';
				$ext = '.min';
			}
			wp_register_script("ultimate-modal-all",plugins_url("../assets/min-js/modal-all.min.js",__FILE__),array('jquery'),ULTIMATE_VERSION);
			//wp_register_script("ultimate-modal-all-switched",plugins_url("../assets/min-js/modal-all.min-switched.js",__FILE__),array('jquery'),ULTIMATE_VERSION);
			wp_register_style("ultimate-modal",plugins_url($css_path."modal".$ext.".css",__FILE__),array(),ULTIMATE_VERSION);
		}	
		// Add shortcode for icon-box
		function modal_shortcode($atts, $content = null)
		{
			$row_setting = '';
			// enqueue js
			$icon = $modal_on = $modal_contain = $btn_size = $btn_bg_color = $btn_txt_color = $btn_text = $read_text = $txt_color = $modal_title = $modal_size = $el_class = $modal_style = $icon_type = $icon_img = $btn_img = $overlay_bg_color = $overlay_bg_opacity = $modal_on_align = $content_bg_color = $content_text_color = $header_bg_color = $header_text_color = $modal_border_style = $modal_border_width = $modal_border_color = $modal_border_radius = '';
			extract(shortcode_atts(array(
				'icon_type' => 'none',
				'icon' => '',
				'icon_img' => '',
				'modal_on' => 'ult-button',
				'modal_contain' => 'ult-html',
				'onload_delay'=>'2',
				'init_extra_class' => '',
				'btn_size' => 'sm',
				'overlay_bg_color' => '#333333',
				'overlay_bg_opacity' => '80',
				'btn_bg_color' => '#333333',
				'btn_txt_color' => '#FFFFFF',
				'btn_text' => '',				
				'read_text' => '',
				'txt_color' => '#f60f60',
				'btn_img' => '',
				'modal_title' => '',
				'modal_size' => 'small',
				'modal_style' => 'overlay-cornerbottomleft',
				'content_bg_color' => '',
				'content_text_color' => '',
				'header_bg_color' => '',
				'header_text_color' => '#333333',
				'modal_on_align' => 'center',
				'modal_border_style' => 'solid',
				'modal_border_width' => '2',
				'modal_border_color' => '#333333',
				'modal_border_radius' => '0',
				'el_class' => '',
				),$atts,'ultimate_modal'));
			$html = $style = $box_icon = $modal_class = $modal_data_class = $uniq = $overlay_bg = $content_style = $header_style = $border_style = '';
			if($modal_on == "ult-button"){
				$modal_on = "button";
			}
			// Create style for content background color
			if($content_bg_color !== '')
				$content_style .= 'background:'.$content_bg_color.';';
			// Create style for content text color
			if($content_text_color !== '')
				$content_style .= 'color:'.$content_text_color.';';
			// Create style for header background color
			if($header_bg_color !== '')
				$header_style .= 'background:'.$header_bg_color.';';
			// Create style for header text color
			if($header_text_color !== '')
				$header_style .= 'color:'.$header_text_color.';';
			if($modal_border_style !== ''){
				$border_style .= 'border-style:'.$modal_border_style.';';
				$border_style .= 'border-width:'.$modal_border_width.'px;';
				$border_style .= 'border-radius:'.$modal_border_radius.'px;';
				$border_style .= 'border-color:'.$modal_border_color.';';
				$header_style .= 'border-color:'.$modal_border_color.';';
			}
			$overlay_bg_opacity = ($overlay_bg_opacity/100);
			if($overlay_bg_color !== ''){
				if(strlen($overlay_bg_color) <= 7)
					$overlay_bg = ultimate_hex2rgb($overlay_bg_color,$overlay_bg_opacity);
				else
					$overlay_bg = $overlay_bg_color;
					
				if($modal_style != 'overlay-show-cornershape' && $modal_style != 'overlay-show-genie' && $modal_style != 'overlay-show-boxes'){
					$overlay_bg = 'background:'.$overlay_bg.';';
				} else {
					$overlay_bg = 'fill:'.$overlay_bg.';';
				}
			}
		
			$uniq = uniqid();
			if($icon_type == 'custom'){
				//$ico_img = wp_get_attachment_image_src( $icon_img, 'large');
				$ico_img = apply_filters('ult_get_img_single', $icon_img, 'url');
				$box_icon = '<div class="modal-icon"><img src="'.$ico_img.'" class="ult-modal-inside-img"></div>';
			} elseif($icon_type == 'selector'){
				if($icon !== '')
					$box_icon = '<div class="modal-icon"><i class="'.$icon.'"></i></div>';
			}
			if($modal_style != 'overlay-show-cornershape' && $modal_style != 'overlay-show-genie' && $modal_style != 'overlay-show-boxes'){
				$modal_class = 'overlay-show';
				$modal_data_class = 'data-overlay-class="'.$modal_style.'"';
			} else {
				$modal_class = $modal_style;
				$modal_data_class = '';
			}
			$html .= '<div class="ult-modal-input-wrapper">';
			if($modal_on == "button"){
				if($btn_bg_color !== ''){
					$style .= 'background:'.$btn_bg_color.';';
					$style .= 'border-color:'.$btn_bg_color.';';
				}
				if($btn_txt_color !== ''){
					$style .= 'color:'.$btn_txt_color.';';
				}
				if($el_class != '')
					$modal_class .= ' '.$el_class.'-button ';
					
				$html .= '<button style="'.$style.'" data-class-id="content-'.$uniq.'" class="btn-modal btn-primary btn-modal-'.$btn_size.' '.$modal_class.' '.$init_extra_class.' ult-align-'.$modal_on_align.'" '.$modal_data_class.'>'.$btn_text.'</button>';
			} elseif($modal_on == "image"){
				if($btn_img !==''){
					if($el_class != '')
						$modal_class .= ' '.$el_class.'-image ';
					// $img = wp_get_attachment_image_src( $btn_img, 'large');
					$img = apply_filters('ult_get_img_single', $btn_img, 'url');
					$html .= '<img src="'.$img.'" data-class-id="content-'.$uniq.'" class="ult-modal-img '.$init_extra_class.' '.$modal_class.' ult-align-'.$modal_on_align.' ult-modal-image-'.$el_class.'" '.$modal_data_class.'/>';
				}
			} 
			elseif($modal_on == "onload"){				
				$html .= '<div data-class-id="content-'.$uniq.'" class="ult-onload '.$modal_class.' " '.$modal_data_class.' data-onload-delay="'.$onload_delay.'"></div>';				
			} 
			else {
				if($txt_color !== ''){
					$style .= 'color:'.$txt_color.';';
					$style .= 'cursor:pointer;';
				}
				if($el_class != '')
					$modal_class .= ' '.$el_class.'-link ';
				$html .= '<span style="'.$style.'" data-class-id="content-'.$uniq.'" class="'.$modal_class.' ult-align-'.$modal_on_align.'" '.$modal_data_class.'>'.$read_text.'</span>';
			}
			$html .= '</div>';
			if($modal_style == 'overlay-show-cornershape') {
				$html .= "\n".'<div class="ult-overlay overlay-cornershape content-'.$uniq.' '.$el_class.'" style="display:none" data-class="content-'.$uniq.'" data-path-to="m 0,0 1439.999975,0 0,805.99999 -1439.999975,0 z">';
            	$html .= "\n\t".'<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 1440 806" preserveAspectRatio="none">
                					<path class="overlay-path" d="m 0,0 1439.999975,0 0,805.99999 0,-805.99999 z" style="'.$overlay_bg.'"/>
            					</svg>';
			} elseif($modal_style == 'overlay-show-genie') {
				$html .= "\n".'<div class="ult-overlay overlay-genie content-'.$uniq.' '.$el_class.'" style="display:none" data-class="content-'.$uniq.'" data-steps="m 701.56545,809.01175 35.16718,0 0,19.68384 -35.16718,0 z;m 698.9986,728.03569 41.23353,0 -3.41953,77.8735 -34.98557,0 z;m 687.08153,513.78234 53.1506,0 C 738.0505,683.9161 737.86917,503.34193 737.27015,806 l -35.90067,0 c -7.82727,-276.34892 -2.06916,-72.79261 -14.28795,-292.21766 z;m 403.87105,257.94772 566.31246,2.93091 C 923.38284,513.78233 738.73561,372.23931 737.27015,806 l -35.90067,0 C 701.32034,404.49318 455.17312,480.07689 403.87105,257.94772 z;M 51.871052,165.94772 1362.1835,168.87863 C 1171.3828,653.78233 738.73561,372.23931 737.27015,806 l -35.90067,0 C 701.32034,404.49318 31.173122,513.78234 51.871052,165.94772 z;m 52,26 1364,4 c -12.8007,666.9037 -273.2644,483.78234 -322.7299,776 l -633.90062,0 C 359.32034,432.49318 -6.6979288,733.83462 52,26 z;m 0,0 1439.999975,0 0,805.99999 -1439.999975,0 z">';
				$html .= "\n\t".'<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 1440 806" preserveAspectRatio="none">
							<path class="overlay-path" d="m 701.56545,809.01175 35.16718,0 0,19.68384 -35.16718,0 z" style="'.$overlay_bg.'"/>
						</svg>';
			} elseif($modal_style == 'overlay-show-boxes') {
				$html .= "\n".'<div class="ult-overlay overlay-boxes content-'.$uniq.' '.$el_class.'" style="display:none" data-class="content-'.$uniq.'">';
				$html .= "\n\t".'<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="101%" viewBox="0 0 1440 806" preserveAspectRatio="none">';
				$html .= "\n\t\t".'<path d="m0.005959,200.364029l207.551124,0l0,204.342453l-207.551124,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m0.005959,400.45401l207.551124,0l0,204.342499l-207.551124,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m0.005959,600.544067l207.551124,0l0,204.342468l-207.551124,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m205.752151,-0.36l207.551163,0l0,204.342437l-207.551163,0l0,-204.342437z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m204.744629,200.364029l207.551147,0l0,204.342453l-207.551147,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m204.744629,400.45401l207.551147,0l0,204.342499l-207.551147,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m204.744629,600.544067l207.551147,0l0,204.342468l-207.551147,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m410.416046,-0.36l207.551117,0l0,204.342437l-207.551117,0l0,-204.342437z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m410.416046,200.364029l207.551117,0l0,204.342453l-207.551117,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m410.416046,400.45401l207.551117,0l0,204.342499l-207.551117,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m410.416046,600.544067l207.551117,0l0,204.342468l-207.551117,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m616.087402,-0.36l207.551086,0l0,204.342437l-207.551086,0l0,-204.342437z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m616.087402,200.364029l207.551086,0l0,204.342453l-207.551086,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m616.087402,400.45401l207.551086,0l0,204.342499l-207.551086,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m616.087402,600.544067l207.551086,0l0,204.342468l-207.551086,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m821.748718,-0.36l207.550964,0l0,204.342437l-207.550964,0l0,-204.342437z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m821.748718,200.364029l207.550964,0l0,204.342453l-207.550964,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m821.748718,400.45401l207.550964,0l0,204.342499l-207.550964,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m821.748718,600.544067l207.550964,0l0,204.342468l-207.550964,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1027.203979,-0.36l207.550903,0l0,204.342437l-207.550903,0l0,-204.342437z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1027.203979,200.364029l207.550903,0l0,204.342453l-207.550903,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1027.203979,400.45401l207.550903,0l0,204.342499l-207.550903,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1027.203979,600.544067l207.550903,0l0,204.342468l-207.550903,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1232.659302,-0.36l207.551147,0l0,204.342437l-207.551147,0l0,-204.342437z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1232.659302,200.364029l207.551147,0l0,204.342453l-207.551147,0l0,-204.342453z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1232.659302,400.45401l207.551147,0l0,204.342499l-207.551147,0l0,-204.342499z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m1232.659302,600.544067l207.551147,0l0,204.342468l-207.551147,0l0,-204.342468z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t\t".'<path d="m-0.791443,-0.360001l207.551163,0l0,204.342438l-207.551163,0l0,-204.342438z" style="'.$overlay_bg.'"/>';
				$html .= "\n\t".'</svg>';
			} else {
				$html .= "\n".'<div class="ult-overlay content-'.$uniq.' '.$el_class.'" data-class="content-'.$uniq.'" id="button-click-overlay" style="'.$overlay_bg.' display:none;">';
			}
			$html .= "\n\t".'<div class="ult_modal ult-fade ult-'.$modal_size.'">';
			$html .= "\n\t\t".'<div class="ult_modal-content ult-hide" style="'.$border_style.'">';
			if($modal_title !== ''){
				$html .= "\n\t\t\t".'<div class="ult_modal-header" style="'.$header_style.'">';
				$html .= "\n\t\t\t\t".$box_icon.'<h3 class="ult_modal-title">'.$modal_title.'</h3>';
				$html .= "\n\t\t\t".'</div>';
			}
			$html .= "\n\t\t\t".'<div class="ult_modal-body '.$modal_contain.'" style="'.$content_style.'">';
			$html .= "\n\t\t\t".do_shortcode($content);
			$html .= "\n\t\t\t".'</div>';
			$html .= "\n\t".'</div>';
			$html .= "\n\t".'</div>';
			$html .= "\n\t".'<div class="ult-overlay-close">Close</div>';
			$html .= "\n".'</div>';
			return $html;
		}
		/* Add modal popup Component*/
		function ultimate_modal_init()
		{
			if ( function_exists('vc_map'))
			{
				vc_map( 
					array(
						"name"		=> __("Modal Box", "ultimate_vc"),
						"base"		=> "ultimate_modal",
						"icon"		=> "vc_modal_box",
						"class"	   => "modal_box",
						"category"  => "Ultimate VC Addons",
						"description" => __("Adds bootstrap modal box in your content","ultimate_vc"),
						"controls" => "full",
						"show_settings_on_create" => true,
						"params" => array(
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon to display:", "ultimate_vc"),
								"param_name" => "icon_type",
								"value" => array(
									__("No Icon","ultimate_vc") => "none",
									__("Font Icon Manager","ultimate_vc") => "selector",
									__("Custom Image Icon","ultimate_vc") => "custom",
								),
								"description" => __("Use existing font icon or upload a custom image.", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "icon_manager",
								"class" => "",
								"heading" => __("Select Icon ","ultimate_vc"),
								"param_name" => "icon",
								"value" => "",
								"description" => __("Click and select icon of your choice. If you can't find the one that suits for your purpose","ultimate_vc").", ".__("you can","ultimate_vc")." <a href='admin.php?page=font-icon-Manager' target='_blank'>".__("add new here","ultimate_vc")."</a>.",
								"dependency" => Array("element" => "icon_type","value" => array("selector")),
								"group" => "General",
							),
							array(
								"type" => "ult_img_single",
								"class" => "",
								"heading" => __("Upload Image Icon:", "ultimate_vc"),
								"param_name" => "icon_img",
								"value" => "",
								"description" => __("Upload the custom image icon.", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("custom")),
								"group" => "General",
							),
							// Modal Title
							array(
								"type" => "textfield",
								"heading" => __("Modal Box Title", "ultimate_vc"),
								"param_name" => "modal_title",
								"admin_label" => true,
								"value" => "",
								"description" => __("Provide the title for modal box.", "ultimate_vc"),
								"group" => "General",
							),
							// Add some description
							array(
								"type" => "textarea_html",
								"heading" => __("Modal Content", "ultimate_vc"),
								"param_name" => "content",
								"value" => "",
								"description" => __("Content that will be displayed in Modal Popup.", "ultimate_vc"),
								"group" => "General",
								"edit_field_class" => "ult_hide_editor_fullscreen vc_col-xs-12 vc_column wpb_el_type_textarea_html vc_wrapper-param-type-textarea_html vc_shortcode-param",
							),
							array(
								"type" => "dropdown",
								"heading" => __("What's in Modal Popup?", "ultimate_vc"),
								"param_name" => "modal_contain",
								"value" => array(
									__("Miscellaneous Things","ultimate_vc") => "ult-html",
									__("Youtube Video","ultimate_vc") => "ult-youtube",
									__("Vimeo Video","ultimate_vc") => "ult-vimeo",
								),
								"group" => "General",
							),
							array(
								"type" => "dropdown",
								"heading" => __("Display Modal On -", "ultimate_vc"),
								"param_name" => "modal_on",
								"value" => array(
									__("Button","ultimate_vc") => "ult-button",
									__("Image","ultimate_vc") => "image",
									__("Text","ultimate_vc") => "text",
									__("On Page Load","ultimate_vc") => "onload",
								),
								"description" => __("When should the popup be initiated?", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type"=>"number",
								"heading"=>__("Delay in Popup Display","ultimate_vc"),
								"param_name"=>"onload_delay",
								"value"=>"2",
								"suffix"=>"seconds",
								"description"=>__("Time delay before modal popup on page load (in seconds)","ultimate_vc"),
								"dependency"=>Array("element"=>"modal_on","value"=>array("onload")),
								"group" => "General",
								),
							array(
								"type" => "ult_img_single",
								"heading" => __("Upload Image", "ultimate_vc"),
								"param_name" => "btn_img",
								"admin_label" => true,
								"value" => "",
								"description" => __("Upload the custom image / image banner.", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("image")),
								"group" => "General",
							),
							array(
								"type" => "dropdown",
								"heading" => __("Button Size", "ultimate_vc"),
								"param_name" => "btn_size",
								"value" => array(
									__("Small","ultimate_vc") => "sm",
									__("Medium","ultimate_vc") => "md",
									__("Large","ultimate_vc") => "lg",
									__("Block","ultimate_vc") => "block",
								),
								"description" => __("How big the button would you like?", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("ult-button")),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Button Background Color", "ultimate_vc"),
								"param_name" => "btn_bg_color",
								"value" => "#333333",
								"group" => "General",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("ult-button")),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Button Text Color", "ultimate_vc"),
								"param_name" => "btn_txt_color",
								"value" => "#FFFFFF",
								"group" => "General",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("ult-button")),
								"group" => "General",
							),
							array(
								"type" => "dropdown",
								"heading" => __("Alignment", "ultimate_vc"),
								"param_name" => "modal_on_align",
								"value" => array(
									__("Center","ultimate_vc") => "center",
									__("Left","ultimate_vc") => "left",
									__("Right","ultimate_vc") => "right",
								),
								"dependency"=>Array("element"=>"modal_on","value"=>array("ult-button","image","text")),
								"description" => __("Selector the alignment of button/text/image", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "textfield",
								"heading" => __("Text on Button", "ultimate_vc"),
								"param_name" => "btn_text",
								"admin_label" => true,
								"value" => "",
								"description" => __("Provide the title for this button.", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("ult-button")),
								"group" => "General",
							),
						
							// Custom text for modal trigger
							array(
								"type" => "textfield",
								"heading" => __("Enter Text", "ultimate_vc"),
								"param_name" => "read_text",
								"value" => "",
								"description" => __("Enter the text on which the modal box will be triggered.", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("text")),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Text Color", "ultimate_vc"),
								"param_name" => "txt_color",
								"value" => "#f60f60",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("text")),
							),
							// Modal box size
							array(
								"type" => "dropdown",
								"heading" => __("Modal Size", "ultimate_vc"),
								"param_name" => "modal_size",
								"value" => array(
									__("Small","ultimate_vc") => "small",
									__("Medium","ultimate_vc") => "medium",
									__("Large","ultimate_vc") => "container",
									__("Block","ultimate_vc") => "block",
								),
								"description" => __("How big the modal box would you like?", "ultimate_vc"),
								"group" => "General",
							),
							// Modal Style
							array(
								"type" => "dropdown",
								"heading" => __("Modal Box Style","ultimate_vc"),
								"param_name" => "modal_style",
								"value" => array(
									__("Corner Bottom Left","ultimate_vc") => "overlay-cornerbottomleft",
									__("Corner Bottom Right","ultimate_vc") => "overlay-cornerbottomright",
									__("Corner Top Left","ultimate_vc") => "overlay-cornertopleft",
									__("Corner Top Right","ultimate_vc") => "overlay-cornertopright",
									__("Corner Shape","ultimate_vc") => "overlay-show-cornershape",
									__("Door Horizontal","ultimate_vc") => "overlay-doorhorizontal",
									__("Door Vertical","ultimate_vc") => "overlay-doorvertical",
									__("Fade","ultimate_vc") => "overlay-fade",
									__("Genie","ultimate_vc") => "overlay-show-genie",
									__("Little Boxes","ultimate_vc") => "overlay-show-boxes",
									__("Simple Genie","ultimate_vc") => "overlay-simplegenie",
									__("Slide Down","ultimate_vc") => "overlay-slidedown",
									__("Slide Up","ultimate_vc") => "overlay-slideup",
									__("Slide Left","ultimate_vc") => "overlay-slideleft",
									__("Slide Right","ultimate_vc") => "overlay-slideright",
									__("Zoom in","ultimate_vc") => "overlay-zoomin",
									__("Zoom out","ultimate_vc") => "overlay-zoomout",
								),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Overlay Background Color", "ultimate_vc"),
								"param_name" => "overlay_bg_color",
								"value" => "#333333",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "number",
								"heading" => __("Overlay Background Opacity", "ultimate_vc"),
								"param_name" => "overlay_bg_opacity",
								"value" => 80,
								"min" => 10,
								"max" => 100,
								"suffix" => "%",
								"description" => __("Select opacity of overlay background.", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Content Background Color", "ultimate_vc"),
								"param_name" => "content_bg_color",
								"value" => "",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Content Text Color", "ultimate_vc"),
								"param_name" => "content_text_color",
								"value" => "",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Header Background Color", "ultimate_vc"),
								"param_name" => "header_bg_color",
								"value" => "",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Header Text Color", "ultimate_vc"),
								"param_name" => "header_text_color",
								"value" => "#333333",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"group" => "General",
							),
							// Modal box size
							array(
								"type" => "dropdown",
								"heading" => __("Modal Box Border", "ultimate_vc"),
								"param_name" => "modal_border_style",
								"value" => array(
									__("None","ultimate_vc") => "",
									__("Solid","ultimate_vc") => "solid",
									__("Double","ultimate_vc") => "double",
									__("Dashed","ultimate_vc") => "dashed",
									__("Dotted","ultimate_vc") => "dotted",
									__("Inset","ultimate_vc") => "inset",
									__("Outset","ultimate_vc") => "outset",
								),
								"description" => __("Do you want to give border to the modal content box?", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "number",
								"heading" => __("Border Width", "ultimate_vc"),
								"param_name" => "modal_border_width",
								"value" => 2,
								"min" => 1,
								"max" => 25,
								"suffix" => "px",
								"description" => __("Select size of border.", "ultimate_vc"),
								"dependency" => Array("element" => "modal_border_style","not_empty" => true),
								"group" => "General",
							),
							array(
								"type" => "colorpicker",
								"heading" => __("Border Color", "ultimate_vc"),
								"param_name" => "modal_border_color",
								"value" => "#333333",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"dependency" => Array("element" => "modal_border_style","not_empty" => true),
								"group" => "General",
							),
							array(
								"type" => "number",
								"heading" => __("Border Radius", "ultimate_vc"),
								"param_name" => "modal_border_radius",
								"value" => 0,
								"min" => 1,
								"max" => 500,
								"suffix" => "px",
								"description" => __("Want to shape the modal content box?.", "ultimate_vc"),
								"dependency" => Array("element" => "modal_border_style","not_empty" => true),
								"group" => "General",
							),
							array(
								"type" => "textfield",
								"heading" => __("Extra Class (Button/Image)", "ultimate_vc"),
								"param_name" => "init_extra_class",
								"admin_label" => true,
								"value" => "",
								"description" => __("Provide ex class for this button/image.", "ultimate_vc"),
								"dependency" => Array("element" => "modal_on","value" => array("ult-button","image")),
								"group" => "General",
							),
							// Customize everything
							array(
								"type" => "textfield",
								"heading" => __("Extra Class (Modal)", "ultimate_vc"),
								"param_name" => "el_class",
								"value" => "",
								"description" => __("Add extra class name that will be applied to the modal popup, and you can use this class for your customizations.", "ultimate_vc"),
								"group" => "General",
							),
							array(
								"type" => "ult_param_heading",
								"text" => "<span style='display: block;'><a href='http://bsf.io/ei2r5' target='_blank'>".__("Watch Video Tutorial","ultimate_vc")." &nbsp; <span class='dashicons dashicons-video-alt3' style='font-size:30px;vertical-align: middle;color: #e52d27;'></span></a></span>",
								"param_name" => "notification",
								'edit_field_class' => 'ult-param-important-wrapper ult-dashicon ult-align-right ult-bold-font ult-blue-font vc_column vc_col-sm-12',
								"group" => "General",
							),
						) // end params array
					) // end vc_map array
				); // end vc_map
			} // end function check 'vc_map'
		}// end function icon_box_init
	}//Class Ultimate_Modals end
}
if(class_exists('Ultimate_Modals'))
{
	$Ultimate_Modals = new Ultimate_Modals;
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_ultimate_modal extends WPBakeryShortCode {
    }
}