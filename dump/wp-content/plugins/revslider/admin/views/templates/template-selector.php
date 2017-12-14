<?php
if( !defined( 'ABSPATH') ) exit();

$tmpl = new RevSliderTemplate();

$templates = $tmpl->getTemplateSlides();
$tp_templates = $tmpl->getThemePunchTemplateSlides();
$tp_template_slider = $tmpl->getThemePunchTemplateSliders();

$tmp_slider = new RevSlider();
$all_slider = $tmp_slider->getArrSliders();

?>
<!-- THE TEMPLATE AREA -->
<div id="template_area">
	<div id="template_header_part">
		<h2><span class="revlogo-mini" style="margin-right:15px;"></span><?php _e('Slider Template Library', REVSLIDER_TEXTDOMAIN); ?></h2>
		
		<div id="close-template"></div>
		
		<div class="revolution-template-switcher">
			<span data-showgroup="revolution-basic-templates" class="revolution-templatebutton selected"><?php _e('Revolution Base', REVSLIDER_TEXTDOMAIN); ?></span>
			<span data-showgroup="revolution-all-slides-templates" class="revolution-templatebutton"><?php _e('All Slides', REVSLIDER_TEXTDOMAIN); ?></span>
			<span data-showgroup="revolution-customer-templates" class="revolution-templatebutton" style="border-right:none"><?php _e('Templates', REVSLIDER_TEXTDOMAIN); ?></span>						
			<span data-showgroup="revolution-premium-templates" class="revolution-templatebutton premium-templatebutton"><i class="eg-icon-basket"></i><?php _e('Premium Sliders', REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-reload-shop"><i class="eg-icon-arrows-ccw"></i><?php _e('Check for new Templates', REVSLIDER_TEXTDOMAIN); ?></span>
		</div>

		<div class="revolution-template-subtitle"><?php _e('Add Single Slide', REVSLIDER_TEXTDOMAIN); ?>
			<div class="revolution-filters" style="display: inline-block;">
				<span style="display:inline-block;width:40px;"></span>
				<span class="template_filter_button selected" data-type="temp_all"><?php _e('SHOW ALL', REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="template_filter_button" data-type="temp_slider"><?php _e('SLIDER', REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="template_filter_button" data-type="temp_carousel"><?php _e('CAROUSEL', REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="template_filter_button" data-type="temp_hero"><?php _e('HERO', REVSLIDER_TEXTDOMAIN); ?></span>
			</div>
		</div>
	</div>
	<!-- THE REVOLUTION BASE TEMPLATES -->
	<div class="revolution-basic-templates revolution-template-groups">
		<?php
		/*if(!empty($tp_templates)){
			foreach($tp_templates as $template){
				$tmpl->write_template_markup($template);
			}
		}*/
		
		if(!empty($tp_template_slider)){
			foreach($tp_template_slider as $m_slider){
				
				if($m_slider['cat'] != 'Revolution Base') continue;
				
				if(!empty($m_slider['filter']) && is_array($m_slider['filter'])){
					foreach($m_slider['filter'] as $f => $v){
						$m_slider['filter'][$f] = 'temp_'.$v;
					}
				}
				
				if(!isset($m_slider['installed']) && !isset($m_slider['is_new'])){
					
					$c_slider = new RevSlider();
					$c_slider->initByDBData($m_slider);
					$c_slides = $tmpl->getThemePunchTemplateSlides(array($m_slider));
					$c_title = $c_slider->getTitle();
					$width = $c_slider->getParam("width",1240);
					$height = $c_slider->getParam("height",868);
					
					if(!empty($c_slides)){
						?>
						<div style="margin-bottom:30px" class="template_group_wrappers <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">							
							<?php
							echo '<div class="template_slider_title">';
							if(isset($m_slider['preview']) && $m_slider['preview'] !== ''){
								echo '<a href="'.esc_attr($m_slider['preview']).'" target="_blank" class="icon-preview_slider" style="margin-right:15px"></a>';
							}
							
							echo $c_title.'</div>';
							
							foreach($c_slides as $c_slide){
								if(isset($m_slider['filter'])){
									$c_slide['filter'] = $m_slider['filter']; //add filters 
								}
								$c_slide['settings']['width'] = $width;
								$c_slide['settings']['height'] = $height;
								
								$tmpl->write_template_markup($c_slide);
							}
							?>							
						</div><?php
					}
				}else{ //not yet imported
					
					$c_slides = $tmpl->getThemePunchTemplateDefaultSlides($m_slider['alias']);
					
					if(!empty($c_slides)){
						?>
						<div style="margin-bottom:30px"  class="template_group_wrappers not-imported-wrapper <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">
							
							<?php
							echo '<div class="template_slider_title">';
							if(isset($m_slider['preview']) && $m_slider['preview'] !== ''){
								echo '<a href="'.esc_attr($m_slider['preview']).'" target="_blank" class="icon-preview_slider" style="margin-right:15px"></a>';
							}
							echo $m_slider['title'].'</div>';
							
							foreach($c_slides as $key => $c_slide){
								if(isset($m_slider['filter'])){
									$c_slide['filter'] = $m_slider['filter']; //add filters 
								}
								$c_slide['width'] = $m_slider['width'];
								$c_slide['height'] = $m_slider['height'];
								$c_slide['uid'] = $m_slider['uid'];
								$c_slide['number'] = $key;
								$c_slide['zip'] = $m_slider['zip'];
								$tmpl->write_import_template_markup_slide($c_slide);
							}
							?>							
						</div><?php
					}
					
				}
				//echo '<div style="margin-bottom:30px" class="tp-clearfix"></div>';
			}
		}
		?>
		<div style="clear:both;width:100%"></div>
	</div>
	
	<div class="revolution-premium-templates revolution-template-groups" style="display: none">
		<?php
		/*if(!empty($tp_templates)){
			foreach($tp_templates as $template){
				$tmpl->write_template_markup($template);
			}
		}*/
		
		if(!empty($tp_template_slider)){
			foreach($tp_template_slider as $m_slider){
				
				if($m_slider['cat'] != 'Premium') continue;
				
				if(!empty($m_slider['filter']) && is_array($m_slider['filter'])){
					foreach($m_slider['filter'] as $f => $v){
						$m_slider['filter'][$f] = 'temp_'.$v;
					}
				}
				
				if(!isset($m_slider['installed']) && !isset($m_slider['is_new'])){
					
					$c_slider = new RevSlider();
					$c_slider->initByDBData($m_slider);
					$c_slides = $tmpl->getThemePunchTemplateSlides(array($m_slider));
					$c_title = $c_slider->getTitle();
					$width = $c_slider->getParam("width",1240);
					$height = $c_slider->getParam("height",868);
					
					if(!empty($c_slides)){
						?>
						<div class="template_group_wrappers <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">							
							<?php
							echo '<div class="template_slider_title">';
							if(isset($m_slider['preview']) && $m_slider['preview'] !== ''){
								echo '<a href="'.esc_attr($m_slider['preview']).'" target="_blank" class="icon-preview_slider" style="margin-right:15px"></a>';
							}
							
							echo $c_title.'</div>';
							
							foreach($c_slides as $c_slide){
								if(isset($m_slider['filter'])){
									$c_slide['filter'] = $m_slider['filter']; //add filters 
								}
								$c_slide['settings']['width'] = $width;
								$c_slide['settings']['height'] = $height;
								
								$tmpl->write_template_markup($c_slide);
							}
							?>							
						</div><?php
					}
				}else{ //not yet imported
					
					$c_slides = $tmpl->getThemePunchTemplateDefaultSlides($m_slider['alias']);
					
					if(!empty($c_slides)){
						?>
						<div class="template_group_wrappers not-imported-wrapper <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">
							
							<?php
							echo '<div class="template_slider_title">';
							if(isset($m_slider['preview']) && $m_slider['preview'] !== ''){
								echo '<a href="'.esc_attr($m_slider['preview']).'" target="_blank" class="icon-preview_slider" style="margin-right:15px"></a>';
							}
							echo $m_slider['title'].'</div>';
							
							foreach($c_slides as $key => $c_slide){
								if(isset($m_slider['filter'])){
									$c_slide['filter'] = $m_slider['filter']; //add filters 
								}
								$c_slide['width'] = $m_slider['width'];
								$c_slide['height'] = $m_slider['height'];
								$c_slide['uid'] = $m_slider['uid'];
								$c_slide['number'] = $key;
								$c_slide['zip'] = $m_slider['zip'];
								$tmpl->write_import_template_markup_slide($c_slide);
							}
							?>							
						</div><?php
					}
					
				}
				echo '<div style="margin-bottom:30px" class="tp-clearfix"></div>';
			}
		}
		?>
		<div style="clear:both;width:100%"></div>
	</div>
	
	
	<!-- THE REVOLUTION CUSTOMER TEMPLATES -->
	<div class="revolution-customer-templates revolution-template-groups">
		<div class="template_group_wrappers">
			<?php
			if(!empty($templates)){
				foreach($templates as $template){
					$tmpl->write_template_markup($template);
				}
			}
			?>
			<div style="clear:both;width:100%"></div>
		</div>
		<div style="clear:both;width:100%"></div>
	</div>


	<!-- THE ALL SLIDES GROUP -->
	<div class="revolution-all-slides-templates revolution-template-groups">
		<?php
		if(!empty($all_slider)){
			foreach($all_slider as $c_slider){
				$c_slides = $c_slider->getSlides(false);
				//$c_slides = $c_slider->getArrSlideNames();
				$c_title = $c_slider->getTitle();
				$width = $c_slider->getParam("width",1240);
				$height = $c_slider->getParam("height",868);
				
				/*if(!empty($c_slider['filter']) && is_array($c_slider['filter'])){
					foreach($c_slider['filter'] as $f => $v){
						$c_slider['filter'][$f] = 'temp_'.$v;
					}
				}*/
				
				if(!empty($c_slides)){
					?>
					<div class="template_group_wrappers <?php //if(isset($c_slider['filter'])){ echo implode(' ', $c_slider['filter']); } ?>">
						<?php
						echo '<div class="template_slider_title">'.$c_title.'</div>';
						foreach($c_slides as $c_slide){
							$mod_slide = array();
							$mod_slide['id'] = $c_slide->getID();
							$mod_slide['params'] = $c_slide->getParams();
							//$mod_slide['layers'] = $c_slide->getLayers();
							$mod_slide['settings'] = $c_slide->getSettings();
							$mod_slide['settings']['width'] = $width;
							$mod_slide['settings']['height'] = $height;
							
							$tmpl->write_template_markup($mod_slide);
							
						}
						?>						
					</div><?php
				}
				echo '<div style="margin-bottom:30px" class="tp-clearfix"></div>';
			}
		}
		?>
	</div>
</div>
<?php
if(!isset($rs_disable_template_script)){
?>
<script>
	jQuery("document").ready(function() {		
		templateSelectorHandling();
	});

	function templateSelectorHandling() {
		// TEMPLATE ELEMENTS
		

		// TEMPLATE ELEMENTS
		jQuery('.template_filter_button').on("click",function() {
			var btn = jQuery(this),
				sch = btn.data('type');
			jQuery('.template_filter_button').removeClass("selected");
			btn.addClass("selected");
			jQuery('.temp_slider, .temp_carousel, .temp_hero').hide();
			if (sch=="temp_all") 
				jQuery('.temp_slider, .temp_carousel, .temp_hero').show();
			else
				jQuery('.'+sch).show();
		});

		
		jQuery('.template_item, .template_slide_item_img').each(function() {
			var item = jQuery(this),
				gw = item.data('gridwidth'),
				gh = item.data('gridheight'),
				id = item.data('slideid'),
				w = 180;
				
			if (gw==undefined || gw<=0) gw = w;
			if (gh==undefined || gh<=0) gh = w;
			
			var	h = Math.round((w/gw)*gh);
			//item.css({height:h+"px"});
			
			var factor = w/gw;
			
			var htitle = item.closest('.template_group_wrappers').find('h3');
			if (!htitle.hasClass("modificated")) {
				htitle.html(htitle.html()+" ("+gw+"x"+gh+")").addClass("modificated");
			}			
		});
		
		// CLOSE SLIDE TEMPLATE
		jQuery('#close-template').click(function() {
			jQuery('#template_area').removeClass("show");
		});		

		function templateButtonClicked(btn) {			
			jQuery('.revolution-template-groups').each(function() { jQuery(this).hide();});			
			jQuery("."+btn.data("showgroup")).show();
			jQuery('.revolution-templatebutton').removeClass("selected");
			btn.addClass("selected");
			scrollTA();			
			jQuery('#template_area').perfectScrollbar();
			
			if(btn.data("showgroup") == 'revolution-basic-templates' || btn.data("showgroup") == 'revolution-premium-templates'){
				jQuery('.revolution-filters').show();
			}else{
				jQuery('.revolution-filters').hide();
			}
		};

		// TEMPLATE TAB CHANGE 
		jQuery('body').on("click",'.revolution-templatebutton',function() {			
			templateButtonClicked(jQuery(this));
		});

		scrollTA();			
		jQuery('#template_area').perfectScrollbar();

		function isElementInViewport(element,sctop,wh) {
			var etp = parseInt(element.position().top,0),
				ebp = etp + parseInt(element.height(),0),
				inviewport = false;
			
			//if ((etp>parseInt(sctop,0) && etp<parseInt(sctop,0)+parseInt(wh,0)) || (ebp<parseInt(sctop,0)+parseInt(wh,0) && ebp>sctop))
			if ((etp>0 && etp<parseInt(wh,0)) || (ebp<parseInt(wh,0) && ebp>0))
				inviewport =  true;
			
			return inviewport;
		}

		jQuery('#template_area').on("scroll", function() {
			scrollTA()
		});

		function scrollTA() {
			var ta = jQuery('#template_area'),
				st = ta.scrollTop(),
				wh = jQuery(window).height();

			ta.find('.template_item:visible, .template_slide_item_img:visible').each(function() {
				var el = jQuery(this);
					
				if (el.data('src')!=undefined && el.data('bgadded')!=1) {
					if (isElementInViewport(el,st,wh)){
						el.css({backgroundImage:'url("'+el.data('src')+'")'});
						el.data('bgadded',1);
					}
				}
			});
		}
		
		jQuery(".input_import_slider").change(function(){
			if(jQuery(this).val() !== ''){
				jQuery('.rs-import-slider-button').show();
			}else{
				jQuery('.rs-import-slider-button').hide();
			}
		});
		
	};

	<?php
	if(isset($_REQUEST['update_shop'])){
		?>
		var recalls_amount = 0;
		function callTemplateSlider() {   
			recalls_amount++;
			if (recalls_amount>5000) {
				jQuery('#waitaminute').hide();
			} else {
				if (jQuery('#template_area').length>0) {             
					jQuery('#template_area').addClass("show");
					jQuery('#waitaminute').hide();      
				} else {
					callTemplateSlider();
				}
			}   
		}
		callTemplateSlider();  
		<?php
	}
	?>
</script>

<!-- Import template slider dialog -->
<div id="dialog_import_template_slide" title="<?php _e("Import Template Slide",REVSLIDER_TEXTDOMAIN); ?>" class="dialog_import_template_slide" style="display:none">
	<form action="<?php echo RevSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post">
		<input type="hidden" name="action" value="revslider_ajax_action">
		<input type="hidden" name="client_action" value="import_slide_template_slidersview">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("revslider_actions"); ?>">
		<input type="hidden" name="uid" class="rs-uid" value="">
		<input type="hidden" name="slidenum" class="rs-slide-number" value="">
		<input type="hidden" name="slider_id" class="rs-slider-id" value="">
		<input type="hidden" name="redirect_id" class="rs-slide-id" value="">
		
		<p><?php _e('Please select the corresponding zip file from the download packages import folder called', REVSLIDER_TEXTDOMAIN); ?>:</p>
		<p class="filetoimport"><b><span class="rs-zip-name"></span></b></p>
		<p class="import-file-wrapper"><input type="file" size="60" name="import_file" class="input_import_slider"></p>
		<span style="margin-top:45px;display:block"><input type="submit" class="rs-import-slider-button button-primary revblue tp-be-button" value="<?php _e("Import Template Slide",REVSLIDER_TEXTDOMAIN); ?>"></span>
		<span class="tp-clearfix"></span>
		<span style="font-weight: 700;"><?php _e("Note: style templates will be updated if they exist!",REVSLIDER_TEXTDOMAIN); ?></span><br><br>
		<table style="display: none;">
			<tr>
				<td><?php _e("Custom Animations:",REVSLIDER_TEXTDOMAIN); ?></td>
				<td><input type="radio" name="update_animations" value="true" checked="checked"> <?php _e("overwrite",REVSLIDER_TEXTDOMAIN); ?></td>
				<td><input type="radio" name="update_animations" value="false"> <?php _e("append",REVSLIDER_TEXTDOMAIN); ?></td>
			</tr>
			<tr>
				<td><?php _e("Static Styles:",REVSLIDER_TEXTDOMAIN); ?></td>
				<td><input type="radio" name="update_static_captions" value="true"> <?php _e("overwrite",REVSLIDER_TEXTDOMAIN); ?></td>
				<td><input type="radio" name="update_static_captions" value="false"> <?php _e("append",REVSLIDER_TEXTDOMAIN); ?></td>
				<td><input type="radio" name="update_static_captions" value="none" checked="checked"> <?php _e("ignore",REVSLIDER_TEXTDOMAIN); ?></td>
			</tr>
		</table>		
		
	</form>
</div>


<div id="dialog_import_template_slide_from" title="<?php _e("Import Template Slide",REVSLIDER_TEXTDOMAIN); ?>" class="dialog_import_template_slide_from" style="display:none">
	<?php _e('Import Slide from local or from ThemePunch online server?', REVSLIDER_TEXTDOMAIN); ?>
	<form action="<?php echo RevSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post" name="rs-import-slide-template-from-server" id="rs-import-slide-template-from-server">
		<input type="hidden" name="action" value="revslider_ajax_action">
		<input type="hidden" name="client_action" value="import_slide_online_template_slidersview">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("revslider_actions"); ?>">
		<input type="hidden" name="uid" class="rs-uid" value="">
		<input type="hidden" name="slidenum" class="rs-slide-number" value="">
		<input type="hidden" name="slider_id" class="rs-slider-id" value="">
		<input type="hidden" name="redirect_id" class="rs-slide-id" value="">
	</form>
</div>
<?php
}
?>