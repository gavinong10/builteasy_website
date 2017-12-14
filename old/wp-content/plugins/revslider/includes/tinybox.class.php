<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */
 
if( !defined( 'ABSPATH') ) exit();

class RevSliderTinyBox {
	
    public function __construct(){
		add_action('admin_head', array('RevSliderTinyBox', 'my_print_shortcodes_in_js'));
		add_action('admin_head', array('RevSliderTinyBox', 'my_add_tinymce'));
    }
	
	public static function my_print_shortcodes_in_js(){
		$sld = new RevSlider();
		$sliders = $sld->getArrSliders();
		$shortcodes = '';
		if(!empty($sliders)){
			$first = true;
			/*foreach($sliders as $slider){
				$shortcode = $slider->getParam('shortcode','false');
				if($shortcode != 'false'){
					if(!$first) $shortcodes .= ',';
					
					$shortcodes.="'".$shortcode."'";
					$first = false;
				}
			}*/
			foreach($sliders as $slider){
				$alias = $slider->getParam('alias','false');
				if($alias != 'false'){
					if(!$first) $shortcodes .= ',';
					
					$shortcodes.="'[rev_slider alias=\"".$alias."\"]'";
					$first = false;
				}
			}
		}
		?>
		<script type="text/javascript">
			var revslider_shortcodes = [<?php echo $shortcodes; ?>];
		</script>
		<?php
	}
	
	public static function my_add_tinymce() {
		add_filter('mce_external_plugins', array('RevSliderTinyBox', 'my_add_tinymce_plugin'));
		add_filter('mce_buttons', array('RevSliderTinyBox', 'my_add_tinymce_button'));
	}
	
	public static function my_add_tinymce_plugin($plugin_array) {
		$version = get_bloginfo('version'); 
		if($version<3.9){
			$plugin_array['revslider'] =  RS_PLUGIN_URL.'admin/assets/js/tbld.js';
		}elseif($version<4.3){
			$plugin_array['revslider'] = RS_PLUGIN_URL.'admin/assets/js/tbld-3.9.js';
		}else{
			$plugin_array['revslider'] = RS_PLUGIN_URL.'admin/assets/js/tbld-4.3.js';
		}
		
		return $plugin_array;
	}
	 
	public static function my_add_tinymce_button($buttons) {
		array_push($buttons, 'revslider');
		return $buttons;
	}

}

/**
 * old classname extends new one (old classnames will be obsolete soon)
 * @since: 5.0
 **/
class RevSlider_TinyBox extends RevSliderTinyBox {}
?>