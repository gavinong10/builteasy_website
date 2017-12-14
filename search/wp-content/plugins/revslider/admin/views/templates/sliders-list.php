<?php
if( !defined( 'ABSPATH') ) exit();
?>
<ul class="tp-list_sliders">
	<?php 
	if(!$no_sliders){

		$useSliders = $arrSliders;
		
		foreach($arrSliders as $slider){
			
			try{
				$errorMessage = '';

				$id = $slider->getID();
				$showTitle = $slider->getShowTitle();
				$title = $slider->getTitle();
				$alias = $slider->getAlias();
				$isFromPosts = $slider->isSlidesFromPosts();
				$isFromStream = $slider->isSlidesFromStream();
				$strSource = __("Gallery",REVSLIDER_TEXTDOMAIN);
				$preicon = "revicon-picture-1";
				
				$is_favorite = $slider->isFavorite();
				
				$shortCode = $slider->getShortcode();
				$numSlides = $slider->getNumSlides();
				$numReal = '';
				
				$rowClass = "";
				$slider_type = 'gallery';
				if($isFromPosts == true){
					$strSource = __('Posts',REVSLIDER_TEXTDOMAIN);
					$preicon ="revicon-doc";
					$rowClass = "class='row_alt'";
					$numReal = $slider->getNumRealSlides();
					$slider_type = 'posts';
				}elseif($isFromStream !== false){
					$strSource = __('Social',REVSLIDER_TEXTDOMAIN);
					$preicon ="revicon-doc";
					$rowClass = "class='row_alt'";
					switch($isFromStream){
						case 'facebook':
							$strSource = __('Facebook',REVSLIDER_TEXTDOMAIN);
							$preicon ="eg-icon-facebook";
							$numReal = $slider->getNumRealSlides(false, 'facebook');
							$slider_type = 'facebook';
						break;
						case 'twitter':
							$strSource = __('Twitter',REVSLIDER_TEXTDOMAIN);
							$preicon ="eg-icon-twitter";
							$numReal = $slider->getNumRealSlides(false, 'twitter');
							$slider_type = 'twitter';
						break;
						case 'instagram':
							$strSource = __('Instagram',REVSLIDER_TEXTDOMAIN);
							$preicon ="eg-icon-info";
							$numReal = $slider->getNumRealSlides(false, 'instagram');
							$slider_type = 'instagram';
						break;
						case 'flickr':
							$strSource = __('Flickr',REVSLIDER_TEXTDOMAIN);
							$preicon ="eg-icon-flickr";
							$numReal = $slider->getNumRealSlides(false, 'flickr');
							$slider_type = 'flickr';
						break;
						case 'youtube':
							$strSource = __('YouTube',REVSLIDER_TEXTDOMAIN);
							$preicon ="eg-icon-youtube";
							$numReal = $slider->getNumRealSlides(false, 'youtube');
							$slider_type = 'youtube';
						break;
						case 'vimeo':
							$strSource = __('Vimeo',REVSLIDER_TEXTDOMAIN);
							$preicon ="eg-icon-vimeo";
							$numReal = $slider->getNumRealSlides(false, 'vimeo');
							$slider_type = 'vimeo';
						break;
						
					}
					
				}
				
				$first_slide_image_thumb = array('url' => '', 'class' => 'mini-transparent', 'style' => '');
				
				if(intval($numSlides) == 0){
					$first_slide_id = 'new&slider='.$id;
					
					
				}else{
					$slides = $slider->getSlides(false);
					
					if(!empty($slides)){
						$first_slide_id = $slides[key($slides)]->getID();
						//$first_slide_id = ($isFromPosts == true) ? $slides[key($slides)]->templateID : $slides[key($slides)]->getID();
						
						$first_slide_image_thumb = $slides[key($slides)]->get_image_attributes($slider_type);
					}else{
						$first_slide_id = 'new&slider='.$id;
					}
				}
				
				$editLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,"id=$id");
				
				$orderSlidesLink = ($isFromPosts) ? self::getViewUrl(RevSliderAdmin::VIEW_SLIDES,"id=$id") : '';
				$editSlidesLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDE,"id=$first_slide_id");

				$showTitle = RevSliderFunctions::getHtmlLink($editLink, $showTitle);
				
			}catch(Exception $e){
				$errorMessage = "ERROR: ".$e->getMessage();
				$strSource = "";
				$numSlides = "";
				$isFromPosts = false;
			}
			
			//print_r($first_slide_image_thumb);
			?>
			<li class="tls-slide tls-stype-all tls-stype-<?php echo $slider_type; ?>" data-favorit="<?php echo ($is_favorite) ? 'a' : 'b'; ?>" data-id="<?php echo $id; ?>" data-name="<?php echo $title; ?>" data-type="<?php echo $slider_type; ?>">
				<div class="tls-main-metas">
					
					<span class="tls-firstslideimage <?php echo $first_slide_image_thumb['class']; ?>" style="<?php echo $first_slide_image_thumb['style']; ?>;<?php if (!empty($first_slide_image_thumb['url'])) {?>background-image:url( <?php echo $first_slide_image_thumb['url']; ?>) <?php } ?>"></span>
					<a href="<?php echo $editSlidesLink; ?>" class="tls-grad-bg tls-bg-top"></a>				
					<span class="tls-source"><?php echo "<i class=".$preicon."></i>".$strSource; ?></span>
					<span class="tls-star"><a href="javascript:void(0);" class="rev-toogle-fav" id="reg-toggle-id-<?php echo $id; ?>"><i class="eg-icon-star<?php echo ($is_favorite) ? '' : '-empty'; ?>"></i></a></span>
					<span class="tls-slidenr"><?php echo $numSlides; if($numReal !== '') echo ' ('.$numReal.')'; ?></span>

					<span class="tls-title-wrapper">
						<span class="tls-id">#<?php echo $id; ?><span id="slider_title_<?php echo $id; ?>" class="hidden"><?php echo $title; ?></span><span class="tls-alias hidden" ><?php echo $alias; ?></span></span>
						<span class="tls-title"><?php echo $showTitle; ?>
							<?php if(!empty($errorMessage)){ ?>
								<span class='error_message'><?php echo $errorMessage; ?></span>
							<?php } ?>
						</span>
						<a class="button-primary tls-settings" href='<?php echo $editLink; ?>'><i class="revicon-cog"></i></a>
						<a class="button-primary tls-editslides" href='<?php echo $editSlidesLink; ?>'><i class="revicon-pencil-1"></i></a>
						<span class="button-primary tls-showmore"><i class="eg-icon-down-open"></i></span>
						
					</span>
					
				</div>

				<div class="tls-hover-metas">
					<!--<span class="tls-shortcode"><?php echo $shortCode; ?></span>-->
					<span class="button-primary rs-embed-slider" ><i class="eg-icon-plus"></i><?php _e("Embed Slider",REVSLIDER_TEXTDOMAIN); ?></span>		
					
					
					<?php
					if($isFromPosts){
						?>
						<a class="button-primary " href='<?php echo $orderSlidesLink; ?>'><i class="revicon-pencil-1"></i><?php _e("Order Posts",REVSLIDER_TEXTDOMAIN); ?></a>
						<?php
					}
					?>
					<a class="button-primary  export_slider_overview" id="export_slider_<?php echo $id; ?>" href="javascript:void(0);" ><i class="revicon-export"></i><?php _e("Export",REVSLIDER_TEXTDOMAIN); ?></a>
					<?php
					$operations = new RevSliderOperations();
					$general_settings = $operations->getGeneralSettingsValues();
					
					$show_dev_export = RevSliderBase::getVar($general_settings, 'show_dev_export', 'off');
					
					if($show_dev_export == 'on'){
						?>
						<a class="button-primary  export_slider_standalone" id="export_slider_standalone_<?php echo $id; ?>" href="javascript:void(0);" ><i class="revicon-export"></i><?php _e("Export to HTML",REVSLIDER_TEXTDOMAIN); ?></a>
						<?php
					}
					?>
					<a class="button-primary  button_delete_slider" id="button_delete_<?php echo $id; ?>" href='javascript:void(0)'><i class="revicon-trash"></i><?php _e("Delete",REVSLIDER_TEXTDOMAIN); ?></a>
					<a class="button-primary  button_duplicate_slider" id="button_duplicate_<?php echo $id; ?>" href='javascript:void(0)'><i class="revicon-picture"></i><?php _e("Duplicate",REVSLIDER_TEXTDOMAIN); ?></a>
					<div id="button_preview_<?php echo $id; ?>" class="button_slider_preview button-primary revgray"><i class="revicon-search-1"></i><?php _e("Preview",REVSLIDER_TEXTDOMAIN); ?></div>
				</div>
				<div class="tls-dimmme"></div>
			</li>
			
			<?php
		}
	}
	?>
	<li class="tls-slide tls-addnewslider">
		<a href='<?php echo $addNewLink; ?>'>
			<span class="tls-main-metas">
				<span class="tls-new-icon-wrapper">
					<span class="slider_list_add_buttons add_new_slider_icon"></span>
				</span>
				<span class="tls-title-wrapper">			
					<span class="tls-title"><?php _e("New Slider",REVSLIDER_TEXTDOMAIN); ?></span>					
				</span>
			</span>
		</a>
	</li>
	<li class="tls-slide tls-addnewslider">
		<a href="javascript:void(0);" id="button_import_template_slider">
			<span class="tls-main-metas">
				<span class="tls-new-icon-wrapper add_new_template_icon_wrapper">
					<i class="slider_list_add_buttons add_new_template_icon"></i>
				</span>
				<span class="tls-title-wrapper">			
					<span class="tls-title"><?php _e("Add Slider Template",REVSLIDER_TEXTDOMAIN); ?></span>					
				</span>
			</span>
		</a>
	</li>
	<li class="tls-slide tls-addnewslider">
		<a href="javascript:void(0);" id="button_import_slider">
			<span class="tls-main-metas">
				<span class="tls-new-icon-wrapper">
					<i class="slider_list_add_buttons  add_new_import_icon"></i>
				</span>
				<span class="tls-title-wrapper">			
					<span class="tls-title"><?php _e("Import Slider",REVSLIDER_TEXTDOMAIN); ?></span>					
				</span>
			</span>
		</a>		
	</li>
</ul>	
<script>
  jQuery(document).on("ready",function() {  	
  	 jQuery('.tls-showmore').click(function() {  	 	
  	 	jQuery(this).closest('.tls-slide').find('.tls-hover-metas').show();
  	 	var elements = jQuery('.tls-slide:not(.hovered) .tls-dimmme');  	 	
  	 	punchgs.TweenLite.to(elements,0.5,{autoAlpha:0.6,overwrite:"all",ease:punchgs.Power3.easeInOut});
  	 	punchgs.TweenLite.to(jQuery(this).find('.tls-dimmme'),0.3,{autoAlpha:0,overwrite:"all",ease:punchgs.Power3.easeInOut})
  	 })

  	 jQuery('.tls-slide').hover(function() {
  	 	jQuery(this).addClass("hovered");  	 	
  	 }, function() {
  	 	var elements = jQuery('.tls-slide .tls-dimmme');
  	 	punchgs.TweenLite.to(elements,0.5,{autoAlpha:0,overwrite:"auto",ease:punchgs.Power3.easeInOut});
  	 	jQuery(this).removeClass("hovered");
  	 	jQuery(this).find('.tls-hover-metas').hide();
  	 });


  })

  jQuery('#filter-sliders').on("change",function() {
  	jQuery('.tls-slide').hide();
  	jQuery('.tls-stype-'+jQuery(this).val()).show();
  	jQuery('.tls-addnewslider').show();
  })

  function sort_li(a, b){
	    return (jQuery(b).data(jQuery('#sort-sliders').val())) < (jQuery(a).data(jQuery('#sort-sliders').val())) ? 1 : -1;    
	}

  jQuery('#sort-sliders').on('change',function() {
  	jQuery(".tp-list_sliders li").sort(sort_li).appendTo('.tp-list_sliders'); 	
  	jQuery('.tls-addnewslider').appendTo('.tp-list_sliders'); 	
  });

  jQuery('.slider-lg-views').click(function() {
	var tls =jQuery('.tp-list_sliders'),
		t = jQuery(this);
	jQuery('.slider-lg-views').removeClass("active");
	jQuery(this).addClass("active");
	tls.removeClass("rs-listview");
	tls.removeClass("rs-gridview");
	tls.addClass(t.data('type'));
  })

</script>

	




