<?php
/*
* Add-on Name: Stats Counter for Visual Composer
* Add-on URI: http://dev.brainstormforce.com
*/
if(!class_exists('AIO_Stats_Counter'))
{
	class AIO_Stats_Counter
	{
		// constructor
		function __construct()
		{
			add_action('init',array($this,'counter_init'));
			add_action("wp_enqueue_scripts", array($this, "register_counter_assets"),1);
			add_shortcode('stat_counter',array($this,'counter_shortcode'));
		}
		function register_counter_assets()
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
			wp_register_script("ult-stats-counter-js",plugins_url($js_path."countUp".$ext.".js",__FILE__),array('jquery'),ULTIMATE_VERSION);
			wp_register_style("ult-stats-counter-style",plugins_url($css_path."stats-counter".$ext.".css",__FILE__),array(),ULTIMATE_VERSION);
		}
		// initialize the mapping function
		function counter_init()
		{
			if(function_exists('vc_map'))
			{
				// map with visual
				vc_map(
					array(
					   "name" => __("Counter","ultimate_vc"),
					   "base" => "stat_counter",
					   "class" => "vc_stats_counter",
					   "icon" => "vc_icon_stats",
					   "category" => "Ultimate VC Addons",
					   "description" => __("Your milestones, achievements, etc.","ultimate_vc"),
					   "params" => array(
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon to display:", "ultimate_vc"),
								"param_name" => "icon_type",
								"value" => array(
									__("Font Icon Manager","ultimate_vc") => "selector",
									__("Custom Image Icon","ultimate_vc") => "custom",
								),
								"description" => __("Use an existing font icon or upload a custom image.", "ultimate_vc")
							),
							array(
								"type" => "icon_manager",
								"class" => "",
								"heading" => __("Select Icon ","ultimate_vc"),
								"param_name" => "icon",
								"value" => "",
								"description" => __("Click and select icon of your choice. If you can't find the one that suits for your purpose","ultimate_vc").", ".__("you can","ultimate_vc")." <a href='admin.php?page=font-icon-Manager' target='_blank'>".__("add new here","ultimate_vc")."</a>.",
								"dependency" => Array("element" => "icon_type","value" => array("selector")),
							),
							array(
								"type" => "attach_image",
								"class" => "",
								"heading" => __("Upload Image Icon:", "ultimate_vc"),
								"param_name" => "icon_img",
								"value" => "",
								"description" => __("Upload the custom image icon.", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("custom")),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Image Width", "ultimate_vc"),
								"param_name" => "img_width",
								"value" => 48,
								"min" => 16,
								"max" => 512,
								"suffix" => "px",
								"description" => __("Provide image width", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("custom")),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Size of Icon", "ultimate_vc"),
								"param_name" => "icon_size",
								"value" => 32,
								"min" => 12,
								"max" => 72,
								"suffix" => "px",
								"description" => __("How big would you like it?", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("selector")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Color", "ultimate_vc"),
								"param_name" => "icon_color",
								"value" => "#333333",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("selector")),						
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon Style", "ultimate_vc"),
								"param_name" => "icon_style",
								"value" => array(
									__("Simple","ultimate_vc") => "none",
									__("Circle Background","ultimate_vc") => "circle",
									__("Square Background","ultimate_vc") => "square",
									__("Design your own","ultimate_vc") => "advanced",
								),
								"description" => __("We have given three quick preset if you are in a hurry. Otherwise, create your own with various options.", "ultimate_vc"),
								//"dependency" => Array("element" => "icon_type","value" => array("selector")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Background Color", "ultimate_vc"),
								"param_name" => "icon_color_bg",
								"value" => "#ffffff",
								"description" => __("Select background color for icon.", "ultimate_vc"),	
								"dependency" => Array("element" => "icon_style", "value" => array("circle","square","advanced")),
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon Border Style", "ultimate_vc"),
								"param_name" => "icon_border_style",
								"value" => array(
									__("None","ultimate_vc") => "",
									__("Solid","ultimate_vc") => "solid",
									__("Dashed","ultimate_vc") => "dashed",
									__("Dotted","ultimate_vc") => "dotted",
									__("Double","ultimate_vc") => "double",
									__("Inset","ultimate_vc") => "inset",
									__("Outset","ultimate_vc") => "outset",
								),
								"description" => __("Select the border style for icon.","ultimate_vc"),
								"dependency" => Array("element" => "icon_style", "value" => array("advanced")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Border Color", "ultimate_vc"),
								"param_name" => "icon_color_border",
								"value" => "#333333",
								"description" => __("Select border color for icon.", "ultimate_vc"),	
								"dependency" => Array("element" => "icon_border_style", "not_empty" => true),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Border Width", "ultimate_vc"),
								"param_name" => "icon_border_size",
								"value" => 1,
								"min" => 1,
								"max" => 10,
								"suffix" => "px",
								"description" => __("Thickness of the border.", "ultimate_vc"),
								"dependency" => Array("element" => "icon_border_style", "not_empty" => true),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Border Radius", "ultimate_vc"),
								"param_name" => "icon_border_radius",
								"value" => 500,
								"min" => 1,
								"max" => 500,
								"suffix" => "px",
								"description" => __("0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).", "ultimate_vc"),
								"dependency" => Array("element" => "icon_border_style", "not_empty" => true),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Background Size", "ultimate_vc"),
								"param_name" => "icon_border_spacing",
								"value" => 50,
								"min" => 0,
								"max" => 500,
								"suffix" => "px",
								"description" => __("Spacing from center of the icon till the boundary of border / background", "ultimate_vc"),
								"dependency" => Array("element" => "icon_style", "value" => array("advanced")),
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Animation","ultimate_vc"),
								"param_name" => "icon_animation",
								"value" => array(
							 		__("No Animation","ultimate_vc") => "",
									__("Swing","ultimate_vc") => "swing",
									__("Pulse","ultimate_vc") => "pulse",
									__("Fade In","ultimate_vc") => "fadeIn",
									__("Fade In Up","ultimate_vc") => "fadeInUp",
									__("Fade In Down","ultimate_vc") => "fadeInDown",
									__("Fade In Left","ultimate_vc") => "fadeInLeft",
									__("Fade In Right","ultimate_vc") => "fadeInRight",
									__("Fade In Up Long","ultimate_vc") => "fadeInUpBig",
									__("Fade In Down Long","ultimate_vc") => "fadeInDownBig",
									__("Fade In Left Long","ultimate_vc") => "fadeInLeftBig",
									__("Fade In Right Long","ultimate_vc") => "fadeInRightBig",
									__("Slide In Down","ultimate_vc") => "slideInDown",
									__("Slide In Left","ultimate_vc") => "slideInLeft",
									__("Slide In Left","ultimate_vc") => "slideInLeft",
									__("Bounce In","ultimate_vc") => "bounceIn",
									__("Bounce In Up","ultimate_vc") => "bounceInUp",
									__("Bounce In Down","ultimate_vc") => "bounceInDown",
									__("Bounce In Left","ultimate_vc") => "bounceInLeft",
									__("Bounce In Right","ultimate_vc") => "bounceInRight",
									__("Rotate In","ultimate_vc") => "rotateIn",
									__("Light Speed In","ultimate_vc") => "lightSpeedIn",
									__("Roll In","ultimate_vc") => "rollIn",
								),
								"description" => __("Like CSS3 Animations? We have several options for you!","ultimate_vc")
						  	),
						  array(
							 "type" => "dropdown",
							 "class" => "",
							 "heading" => __("Icon Position", "ultimate_vc"),
							 "param_name" => "icon_position",
							 "value" => array(
							 		__('Top','ultimate_vc') => 'top',
									__('Right','ultimate_vc') => 'right',
									__('Left','ultimate_vc') => 'left',	
							 		),							
							 "description" => __("Enter Position of Icon", "ultimate_vc")
							 ),
						  array(
							 "type" => "textfield",
							 "class" => "",
							 "heading" => __("Counter Title ", "ultimate_vc"),
							 "param_name" => "counter_title",
							 "admin_label" => true,
							 "value" => "",
							 "description" => __("Enter title for stats counter block", "ultimate_vc")
						  ),
						  array(
							 "type" => "textfield",
							 "class" => "",
							 "heading" => __("Counter Value", "ultimate_vc"),
							 "param_name" => "counter_value",
							 "value" => "1250",
							 "description" => __("Enter number for counter without any special character. You may enter a decimal number. Eg 12.76", "ultimate_vc")
						  ),
						  array(
							 "type" => "textfield",
							 "class" => "",
							 "heading" => __("Thousands Separator", "ultimate_vc"),
							 "param_name" => "counter_sep",
							 "value" => ",",
							 "description" => __("Enter character for thousanda separator. e.g. ',' will separate 125000 into 125,000", "ultimate_vc")
						  ),
						  array(
							 "type" => "textfield",
							 "class" => "",
							 "heading" => __("Replace Decimal Point With", "ultimate_vc"),
							 "param_name" => "counter_decimal",
							 "value" => ".",
							 "description" => __("Did you enter a decimal number (Eg - 12.76) The decimal point '.' will be replaced with value that you will enter above.", "ultimate_vc"),
						  ),
						  array(
							 "type" => "textfield",
							 "class" => "",
							 "heading" => __("Counter Value Prefix", "ultimate_vc"),
							 "param_name" => "counter_prefix",
							 "value" => "",
							 "description" => __("Enter prefix for counter value", "ultimate_vc")
						  ),
						  array(
							 "type" => "textfield",
							 "class" => "",
							 "heading" => __("Counter Value Suffix", "ultimate_vc"),
							 "param_name" => "counter_suffix",
							 "value" => "",
							 "description" => __("Enter suffix for counter value", "ultimate_vc")
						  ),
						  array(
								"type" => "number",
								"class" => "",
								"heading" => __("Counter rolling time", "ultimate_vc"),
								"param_name" => "speed",
								"value" => 3,
								"min" => 1,
								"max" => 10,
								"suffix" => "seconds",
								"description" => __("How many seconds the counter should roll?", "ultimate_vc")
							),
						  array(
								"type" => "number",
								"class" => "",
								"heading" => __("Title Font Size", "ultimate_vc"),
								"param_name" => "font_size_title",
								"value" => 18,
								"min" => 10,
								"max" => 72,
								"suffix" => "px",
								"description" => __("Enter value in pixels.", "ultimate_vc")
							),
						  array(
								"type" => "number",
								"class" => "",
								"heading" => __("Counter Font Size", "ultimate_vc"),
								"param_name" => "font_size_counter",
								"value" => 28,
								"min" => 12,
								"max" => 72,
								"suffix" => "px",
								"description" => __("Enter value in pixels.", "ultimate_vc")
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Counter Text Color", "ultimate_vc"),
								"param_name" => "counter_color_txt",
								"value" => "",
								"description" => __("Select text color for counter title and digits.", "ultimate_vc"),	
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Extra Class",  "ultimate_vc"),
								"param_name" => "el_class",
								"value" => "",
								"description" => __("Add extra class name that will be applied to the icon process, and you can use this class for your customizations.",  "ultimate_vc"),
							),
							array(
								"type" => "ult_param_heading",
								"param_name" => "title_text_typography",
								"heading" => __("Counter Title settings","ultimate_vc"),
								"value" => "",
								"group" => "Typography",
								"class" => "ult-param-heading",
								'edit_field_class' => 'ult-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
							),
							array(
								"type" => "ultimate_google_fonts",
								"heading" => __("Font Family","ultimate_vc"),
								"param_name" => "title_font",
								"value" => "",
								"group" => "Typography"
							),
							array(
								"type" => "ultimate_google_fonts_style",
								"heading" => __("Font Style","ultimate_vc"),
								"param_name" => "title_font_style",
								"value" => "",
								"group" => "Typography"
							),
							array(
								"type" => "number",
								"param_name" => "title_font_size",
								"heading" => __("Font size","ultimate_vc"),
								"value" => "",
								"suffix" => "px",
								"min" => 10,
								"group" => "Typography"
							),
							array(
								"type" => "number",
								"param_name" => "title_font_line_height",
								"heading" => __("Font Line Height","ultimate_vc"),
								"value" => "",
								"suffix" => "px",
								"min" => 10,
								"group" => "Typography"
							),
							array(
								"type" => "ult_param_heading",
								"param_name" => "desc_text_typography",
								"heading" => __("Counter Value settings","ultimate_vc"),
								"value" => "",
								"group" => "Typography",
								"class" => "ult-param-heading",
								'edit_field_class' => 'ult-param-heading-wrapper vc_column vc_col-sm-12',
							),
							array(
								"type" => "ultimate_google_fonts",
								"heading" => __("Font Family","ultimate_vc"),
								"param_name" => "desc_font",
								"value" => "",
								"group" => "Typography"
							),
							array(
								"type" => "ultimate_google_fonts_style",
								"heading" => __("Font Style","ultimate_vc"),
								"param_name" => "desc_font_style",
								"value" => "",
								"group" => "Typography"
							),
							array(
								"type" => "number",
								"param_name" => "desc_font_size",
								"heading" => __("Font size","ultimate_vc"),
								"value" => "",
								"suffix" => "px",
								"min" => 10,
								"group" => "Typography"
							),
							array(
								"type" => "number",
								"param_name" => "desc_font_line_height",
								"heading" => __("Font Line Height","ultimate_vc"),
								"value" => "",
								"suffix" => "px",
								"min" => 10,
								"group" => "Typography"
							),
							array(
								"type" => "colorpicker",
								"param_name" => "desc_font_color",
								"heading" => __("Color","ultimate_vc"),
								"group" => "Typography"
							),
							array(
								"type" => "ult_param_heading",
								"text" => "<span style='display: block;'><a href='http://bsf.io/t23kn' target='_blank'>".__("Watch Video Tutorial","ultimate_vc")." &nbsp; <span class='dashicons dashicons-video-alt3' style='font-size:30px;vertical-align: middle;color: #e52d27;'></span></a></span>",
								"param_name" => "notification",
								'edit_field_class' => 'ult-param-important-wrapper ult-dashicon ult-align-right ult-bold-font ult-blue-font vc_column vc_col-sm-12',
							),
						),
					)
				);
			}
		}
		// Shortcode handler function for stats counter
		function counter_shortcode($atts)
		{
			// enqueue js
			//wp_enqueue_script('ultimate-appear');
			//wp_enqueue_script('ultimate-custom');
			//wp_enqueue_script('front-js',plugins_url('../assets/min-js/countUp.min.js',__FILE__));
			
			$icon_type = $icon_img = $img_width = $icon = $icon_color = $icon_color_bg = $icon_size = $icon_style = $icon_border_style = $icon_border_radius = $icon_color_border = $icon_border_size = $icon_border_spacing = $icon_link = $el_class = $icon_animation = $counter_title = $counter_value = $icon_position = $counter_style = $font_size_title = $font_size_counter = $counter_font = $title_font = $speed = $counter_sep = $counter_suffix = $counter_prefix = $counter_decimal = $counter_color_txt = $desc_font_line_height = $title_font_line_height = '';
			$title_font = $title_font_style = $title_font_size = $title_font_color = $desc_font = $desc_font_style = $desc_font_size = $desc_font_color = '';
			extract(shortcode_atts( array(
				'icon_type' => 'selector',
				'icon' => '',
				'icon_img' => '',
				'img_width' => '48',
				'icon_size' => '32',				
				'icon_color' => '#333333',
				'icon_style' => 'none',
				'icon_color_bg' => '#ffffff',
				'icon_color_border' => '#333333',
				'icon_border_style' => '',
				'icon_border_size' => '1',
				'icon_border_radius' => '500',
				'icon_border_spacing' => '50',
				'icon_link' => '',
				'icon_animation' => '',
				'counter_title' => '',
				'counter_value' => '1250',
				'counter_sep' => ',',
				'counter_suffix' => '',
				'counter_prefix' => '',
				'counter_decimal' => '.',
				'icon_position'=>'top',
				'counter_style'=>'',
				'speed'=>'3',
				'font_size_title' => '18',
				'font_size_counter' => '28',
				'counter_color_txt' => '',
				'title_font' => '',
				'title_font_style' => '',
				'title_font_size' => '',
				'title_font_line_height'=> '',
				'desc_font' => '',
				'desc_font_style' => '',
				'desc_font_size' => '',
				'desc_font_color' => '',
				'desc_font_line_height'=> '',
				'el_class'=>'',
			),$atts));			 
			$class = $style = $title_style = $desc_style = '';
			//$font_args = array();
			$stats_icon = do_shortcode('[just_icon icon_type="'.$icon_type.'" icon="'.$icon.'" icon_img="'.$icon_img.'" img_width="'.$img_width.'" icon_size="'.$icon_size.'" icon_color="'.$icon_color.'" icon_style="'.$icon_style.'" icon_color_bg="'.$icon_color_bg.'" icon_color_border="'.$icon_color_border.'"  icon_border_style="'.$icon_border_style.'" icon_border_size="'.$icon_border_size.'" icon_border_radius="'.$icon_border_radius.'" icon_border_spacing="'.$icon_border_spacing.'" icon_link="'.$icon_link.'" icon_animation="'.$icon_animation.'"]');
			
			/* title */
			if($title_font != '')
			{
				$font_family = get_ultimate_font_family($title_font);
				$title_style .= 'font-family:\''.$font_family.'\';';
				//array_push($font_args, $title_font);
			}
			if($title_font_style != '')
				$title_style .= get_ultimate_font_style($title_font_style);
			if($title_font_size != '')
				$title_style .= 'font-size:'.$title_font_size.'px;';
			if($title_font_line_height != '')
				$title_style .= 'line-height:'.$title_font_line_height.'px;';
				
			/* description */
			if($desc_font != '')
			{
				$font_family = get_ultimate_font_family($desc_font);
				$desc_style .= 'font-family:\''.$font_family.'\';';
				//array_push($font_args, $desc_font);
			}
			if($desc_font_style != '')
				$desc_style .= get_ultimate_font_style($desc_font_style);
			if($desc_font_size != '')
				$desc_style .= 'font-size:'.$desc_font_size.'px;';
			if($desc_font_line_height != '')
				$desc_style .= 'line-height:'.$desc_font_line_height.'px;';
			if($desc_font_color != '')
				$desc_style .= 'color:'.$desc_font_color.';';
			//enquque_ultimate_google_fonts($font_args);
			
			if($counter_color_txt !== ''){
				$counter_color = 'color:'.$counter_color_txt.';';
			} else {
				$counter_color = '';
			}
			if($icon_color != '')
				$style.='color:'.$icon_color.';';
			if($icon_animation !== 'none')
			{
				$css_trans = 'data-animation="'.$icon_animation.'" data-animation-delay="03"';
			}
			$counter_font = 'font-size:'.$font_size_counter.'px;';
			$title_font = 'font-size:'.$font_size_title.'px;';
			if($counter_style !=''){
				$class = $counter_style;
				if(strpos($counter_style, 'no_bg')){
					$style.= "border:2px solid ".$counter_icon_bg_color.';';
				}
				elseif(strpos($counter_style, 'with_bg')){
					if($counter_icon_bg_color != '')
						$style.='background:'.$counter_icon_bg_color.';';
				}
			}
			if($el_class != '')
				$class.= ' '.$el_class;
			$ic_position = 'stats-'.$icon_position;
			$ic_class = 'aio-icon-'.$icon_position;
			$output = '<div class="stats-block '.$ic_position.' '.$class.'">';
				//$output .= '<div class="stats-icon" style="'.$style.'">
				//				<i class="'.$stats_icon.'"></i>
				//			</div>';
				$id = 'counter_'.uniqid(rand());
				if($counter_sep == ""){
					$counter_sep = 'none';
				}
				if($counter_decimal == ""){
					$counter_decimal = 'none';
				}
				if($icon_position !== "right")
					$output .= '<div class="'.$ic_class.'">'.$stats_icon.'</div>';
				$output .= '<div class="stats-desc">';
					if($counter_prefix !== ''){
						$output .= '<div class="counter_prefix" style="'.$counter_font.'">'.$counter_prefix.'</div>';
					}
					$output .= '<div id="'.$id.'" data-id="'.$id.'" class="stats-number" style="'.$counter_font.' '.$counter_color.' '.$desc_style.'" data-speed="'.$speed.'" data-counter-value="'.$counter_value.'" data-separator="'.$counter_sep.'" data-decimal="'.$counter_decimal.'">0</div>';
					if($counter_suffix !== ''){
						$output .= '<div class="counter_suffix" style="'.$counter_font.' '.$counter_color.'">'.$counter_suffix.'</div>';
					}
					$output .= '<div class="stats-text" style="'.$title_font.' '.$counter_color.' '.$title_style.'">'.$counter_title.'</div>';
				$output .= '</div>';
				if($icon_position == "right")
					$output .= '<div class="'.$ic_class.'">'.$stats_icon.'</div>';
			$output .= '</div>';				
			return $output;		
		}
	}
}
if(class_exists('AIO_Stats_Counter'))
{
	$AIO_Stats_Counter = new AIO_Stats_Counter;
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_stat_counter extends WPBakeryShortCode {
    }
}