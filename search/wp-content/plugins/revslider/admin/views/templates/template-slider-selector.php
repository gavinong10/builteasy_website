<?php
if( !defined( 'ABSPATH') ) exit();

$tmpl = new RevSliderTemplate();

$tp_template_slider = $tmpl->getThemePunchTemplateSliders();
$author_template_slider = $tmpl->getDefaultTemplateSliders();

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
			<span data-showgroup="revolution-premium-templates" class="revolution-templatebutton premium-templatebutton"><i class="eg-icon-basket"></i><?php _e('Premium Sliders', REVSLIDER_TEXTDOMAIN); ?></span>
			<?php
			if(!empty($author_template_slider) && is_array($author_template_slider)){
				foreach($author_template_slider as $name => $v){
					?>
					<span data-showgroup="revolution-<?php echo sanitize_title($name); ?>" class="revolution-templatebutton"><?php echo esc_attr($name); ?></span>
					<?php
				}
			}
			?>
			<span class="rs-reload-shop"><i class="eg-icon-arrows-ccw"></i><?php _e('Check for new Templates', REVSLIDER_TEXTDOMAIN); ?></span>
		</div>

		<div class="revolution-template-subtitle"><?php _e('Add Slider', REVSLIDER_TEXTDOMAIN); ?>
			<span style="display:inline-block;width:40px;"></span>
			<span class="template_filter_button selected" data-type="temp_all"><?php _e('SHOW ALL', REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="template_filter_button" data-type="temp_slider"><?php _e('SLIDER', REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="template_filter_button" data-type="temp_carousel"><?php _e('CAROUSEL', REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="template_filter_button" data-type="temp_hero"><?php _e('HERO', REVSLIDER_TEXTDOMAIN); ?></span>
		</div>
	</div>

	<!-- THE REVOLUTION BASE TEMPLATES -->
	<div class="revolution-basic-templates revolution-template-groups">
		<?php
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
						<div class="template_group_wrappers <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">
							<?php
							foreach($c_slides as $c_slide){
								$c_slide['img'] = $m_slider['img']; //set slide image
								if(isset($m_slider['preview'])){
									$c_slide['preview'] = $m_slider['preview']; //set preview URL
								}
								if(isset($m_slider['filter'])){
									$c_slide['filter'] = $m_slider['filter']; //add filters 
								}
								
								if($c_slide['img'] == ''){
									//set default image
								}
								
								$c_slide['settings']['width'] = $width;
								$c_slide['settings']['height'] = $height;
								$tmpl->write_template_markup($c_slide, $c_slider->getID()); //add the Slider ID as we want to add a Slider and no Slide
								break; //only write the first, as we want to add a Slider and not a Slide
							}
							echo '<div class="template_thumb_title">'.$c_title.'</div>';
							?>
						</div><?php
					}
				}else{
					?>
					<div class="template_group_wrappers not-imported-wrapper <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">
						<?php
						$tmpl->write_import_template_markup($m_slider); //add the Slider ID as we want to add a Slider and no Slide
						echo '<div class="template_thumb_title">'.$m_slider['title'].'</div>';
						?>
					</div>
					<?php
				}
			}
		}else{
			echo '<span style="color: #F00; font-size: 20px">';
			_e('No data could be retrieved from the servers. Please make sure that your website can connect to the themepunch servers.', REVSLIDER_TEXTDOMAIN);
			echo '</span>';
		}
		?>
		<div style="clear:both;width:100%"></div>
	</div>
	
	<!-- THE REVOLUTION PREMIUM TEMPLATES -->
	<div class="revolution-premium-templates revolution-template-groups" style="display: none">
		<?php
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
							foreach($c_slides as $c_slide){
								$c_slide['img'] = $m_slider['img']; //set slide image
								if(isset($m_slider['preview'])){
									$c_slide['preview'] = $m_slider['preview']; //set preview URL
								}
								if(isset($m_slider['filter'])){
									$c_slide['filter'] = $m_slider['filter']; //add filters 
								}
								
								if($c_slide['img'] == ''){
									//set default image
								}
								
								$c_slide['settings']['width'] = $width;
								$c_slide['settings']['height'] = $height;
								$tmpl->write_template_markup($c_slide, $c_slider->getID()); //add the Slider ID as we want to add a Slider and no Slide
								break; //only write the first, as we want to add a Slider and not a Slide
							}
							echo '<div class="template_thumb_title">'.$c_title.'</div>';
							?>
						</div><?php
					}
				}else{
					?>
					<div class="template_group_wrappers not-imported-wrapper <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">
						<?php
						$tmpl->write_import_template_markup($m_slider); //add the Slider ID as we want to add a Slider and no Slide
						echo '<div class="template_thumb_title">'.$m_slider['title'].'</div>';
						?>
					</div>
					<?php
				}
			}
		}
		?>
		<div style="clear:both;width:100%"></div>
		
		<a href="javascript:void(0);" class="rs-visit-store"></a>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.rs-visit-store').click(function(){
				UniteAdminRev.ajaxRequest("rs_get_store_information", {}, function(response){
					if(response.success == true){
						if(typeof(response.data.message) !== 'undefined'){
							alert(response.data.message);
						}
					}
					
					return true;
				});
			});
			
		});
		</script>
		<div style="clear:both;width:100%"></div>
	</div>
	
	<?php
	if(!empty($author_template_slider) && is_array($author_template_slider)){
		foreach($author_template_slider as $name => $v){
			?>
			<!-- THE REVOLUTION AUTHOR TEMPLATES -->
			<div class="revolution-<?php echo sanitize_title($name); ?> revolution-template-groups" style="display: none">
				<?php
				if(!empty($v)){
					foreach($v as $m_slider){
						
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
									foreach($c_slides as $c_slide){
										$c_slide['img'] = $m_slider['img']; //set slide image
										if(isset($m_slider['preview'])){
											$c_slide['preview'] = $m_slider['preview']; //set preview URL
										}
										if(isset($m_slider['filter'])){
											$c_slide['filter'] = $m_slider['filter']; //add filters 
										}
										$c_slide['settings']['width'] = $width;
										$c_slide['settings']['height'] = $height;
										
										if($c_slide['img'] == ''){
											//set default image
										}
										
										$tmpl->write_template_markup($c_slide, $c_slider->getID()); //add the Slider ID as we want to add a Slider and no Slide
										break; //only write the first, as we want to add a Slider and not a Slide
									}
									echo '<div class="template_thumb_title">'.$c_title.'</div>';
									?>
									
								</div><?php
							}
						}else{
							?>
							<div class="template_group_wrappers not-imported-wrapper <?php if(isset($m_slider['filter'])){ echo implode(' ', $m_slider['filter']); } ?>">
								<?php
								$tmpl->write_import_template_markup($m_slider); //add the Slider ID as we want to add a Slider and no Slide
								echo '<div class="template_thumb_title">'.$m_slider['title'].'</div>';
								?>
								
							</div>
							<?php
						}
					}
				}
				?>
				<div style="clear:both;width:100%"></div>
			</div>
			<?php
		}
	}
	?>
</div>

<script>
	jQuery("document").ready(function() {		
		
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

		
		jQuery('.template_slider_item, .template_slider_item_import').each(function() {
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

		// TEMPLATE TAB CHANGE 
		jQuery('body').on("click",'.revolution-templatebutton',function() {			
			var btn = jQuery(this);
			jQuery('.revolution-template-groups').each(function() { jQuery(this).hide();});			
			jQuery("."+btn.data("showgroup")).show();
			jQuery('.revolution-templatebutton').removeClass("selected");
			btn.addClass("selected");
			scrollTA();
			jQuery('#template_area').perfectScrollbar();
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

			ta.find('.template_slider_item:visible, .template_slider_item_import:visible').each(function() {
				var el = jQuery(this);
					
				if (el.data('src')!=undefined && el.data('bgadded')!=1) {										
					if (isElementInViewport(el,st,wh)) 	{						
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
	});
	
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
<div id="dialog_import_template_slider" title="<?php _e("Import Template Slider",REVSLIDER_TEXTDOMAIN); ?>" class="dialog_import_template_slider" style="display:none">
	<form action="<?php echo RevSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post">
		<input type="hidden" name="action" value="revslider_ajax_action">
		<input type="hidden" name="client_action" value="import_slider_template_slidersview">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("revslider_actions"); ?>">
		<input type="hidden" name="uid" class="rs-uid" value="">
		
		<p><?php _e('Please select the corresponding zip file from the download packages import folder called', REVSLIDER_TEXTDOMAIN); ?>:</p> 
		<p class="filetoimport"><b><span class="rs-zip-name"></span></b></p>
		<p class="import-file-wrapper"><input type="file" size="60" name="import_file" class="input_import_slider "></p>
		<span style="margin-top:45px;display:block"><input type="submit" class="rs-import-slider-button button-primary revblue tp-be-button" value="<?php _e("Import Template Slider",REVSLIDER_TEXTDOMAIN); ?>"></span>
		<span class="tp-clearfix"></span>
		<span style="font-weight: 700;"><?php _e("Note: style templates will be updated if they exist!",REVSLIDER_TEXTDOMAIN); ?></span>
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


<div id="dialog_import_template_slider_from" title="<?php _e("Import Template Slider",REVSLIDER_TEXTDOMAIN); ?>" class="dialog_import_template_slider_from" style="display:none">
	<?php _e('Import Slider from local or from ThemePunch server?', REVSLIDER_TEXTDOMAIN); ?>
	<form action="<?php echo RevSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post" name="rs-import-template-from-server" id="rs-import-template-from-server">
		<input type="hidden" name="action" value="revslider_ajax_action">
		<input type="hidden" name="client_action" value="import_slider_online_template_slidersview">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("revslider_actions"); ?>">
		<input type="hidden" name="uid" class="rs-uid" value="">
	</form>
</div>