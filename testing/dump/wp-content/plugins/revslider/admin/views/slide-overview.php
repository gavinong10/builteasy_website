<?php
if( !defined( 'ABSPATH') ) exit();

$operations = new RevSliderOperations();

$sliderID = self::getGetVar("id");

if(empty($sliderID))
	RevSliderFunctions::throwError("Slider ID not found"); 

$slider = new RevSlider();
$slider->initByID($sliderID);
$sliderParams = $slider->getParams();

$arrSliders = $slider->getArrSlidersShort($sliderID);
$selectSliders = RevSliderFunctions::getHTMLSelect($arrSliders,"","id='selectSliders'",true);

$numSliders = count($arrSliders);

//set iframe parameters	
$width = $sliderParams["width"];
$height = $sliderParams["height"];

$iframeWidth = $width+60;
$iframeHeight = $height+50;

$iframeStyle = "width:".$iframeWidth."px;height:".$iframeHeight."px;";

if($slider->isSlidesFromPosts()){
	$arrSlides = $slider->getSlidesFromPosts(false);
}elseif($slider->isSlidesFromStream()){
	$arrSlides = $slider->getSlidesFromStream(false);
}else{
	$arrSlides = $slider->getSlides(false);
}

$numSlides = count($arrSlides);

$linksSliderSettings = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,'id='.$sliderID);

//treat in case of slides from gallery
if($slider->isSlidesFromPosts() == false){
	//removed in 5.0
}else{	//slides from posts
	
	$sourceType = $slider->getParam('source_type', 'posts');
	$showSortBy = ($sourceType == 'posts')? true : false;
	
	//get button links
	$urlNewPost = RevSliderFunctionsWP::getUrlNewPost();
	$linkNewPost = RevSliderFunctions::getHtmlLink($urlNewPost, '<i class="revicon-pencil-1"></i>'.__('New Post',REVSLIDER_TEXTDOMAIN),'button_new_post','button-primary revblue',true);
	
	//get ordering
	$arrSortBy = RevSliderFunctionsWP::getArrSortBy();
	$sortBy = $slider->getParam('post_sortby',RevSlider::DEFAULT_POST_SORTBY);
	$selectSortBy = RevSliderFunctions::getHTMLSelect($arrSortBy,$sortBy,"id='select_sortby'",true);
	?>
	<div class="wrap settings_wrap">
		<div class="title_line">
			<div id="icon-options-general" class="icon32"></div>
			<div class="view_title"><?php _e('Edit Posts',REVSLIDER_TEXTDOMAIN); ?>: <?php echo $slider->getTitle(); ?></div>
		</div>
		<div class="vert_sap"></div>
		
		<?php _e("This is a list of posts that are taken from multiple sources.",REVSLIDER_TEXTDOMAIN); ?> &nbsp;
		<?php if($showSortBy == true){ ?>
			<?php _e("Sort by",REVSLIDER_TEXTDOMAIN); ?>: <?php echo $selectSortBy; ?> &nbsp; <span class="hor_sap"></span>
		<?php } ?>
		<?php echo $linkNewPost; ?>
		<span id="slides_top_loader" class="slides_posts_loader" style="display:none;"><?php _e("Updating Sorting...",REVSLIDER_TEXTDOMAIN); ?></span>
		<div class="vert_sap"></div>
		<div class="sliders_list_container">
			<div class="postbox box-slideslist">
				<h3>
					<span class='slideslist-title'><?php _e('Post List',REVSLIDER_TEXTDOMAIN); ?></span>
					<span id="saving_indicator" class='slideslist-loading'><?php _e("Saving Order",REVSLIDER_TEXTDOMAIN); ?>...</span>
				</h3>
				<div class="inside">
					<?php if(empty($arrSlides)){?>
						<?php _e('No Posts Found',REVSLIDER_TEXTDOMAIN); ?>
					<?php } ?>
					<ul id="list_slides" class="list_slides ui-sortable">
						<?php
						foreach($arrSlides as $index=>$slide){
							$bgType = $slide->getParam("background_type","image");
							$bgFit = $slide->getParam("bg_fit","cover");
							$bgFitX = intval($slide->getParam("bg_fit_x","100"));
							$bgFitY = intval($slide->getParam("bg_fit_y","100"));
							$bgPosition = $slide->getParam("bg_position","center center");
							$bgPositionX = intval($slide->getParam("bg_position_x","0"));
							$bgPositionY = intval($slide->getParam("bg_position_y","0"));
							$bgRepeat = $slide->getParam("bg_repeat","no-repeat");
							$bgStyle = ' ';
							if($bgFit == 'percentage'){
								$bgStyle .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
							}else{
								$bgStyle .= "background-size: ".$bgFit.";";
							}
							if($bgPosition == 'percentage'){
								$bgStyle .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
							}else{
								$bgStyle .= "background-position: ".$bgPosition.";";
							}
							$bgStyle .= "background-repeat: ".$bgRepeat.";";
							
							if($sortBy == RevSliderFunctionsWP::SORTBY_MENU_ORDER)
								$order = $slide->getOrder();
							else
								$order = $index + 1;
								
							$urlImageForView = $slide->getUrlImageThumb();
							$slideTitle = $slide->getParam("title","Slide");
							$title = $slideTitle;
							$filename = $slide->getImageFilename();
							$imageAlt = stripslashes($slideTitle);
							
							if(empty($imageAlt))
								$imageAlt = "slide";
								
							if($bgType == "image" && !empty($filename))
								$title .= " (".$filename.")";
								
							$postID = $slide->getID();
							$urlEditSlide = RevSliderFunctionsWP::getUrlEditPost($postID);
							$linkEdit = RevSliderFunctions::getHtmlLink($urlEditSlide, $title,"","",true);
							$state = $slide->getParam("state","published");
							?>
							<li id="slidelist_item_<?php echo $postID; ?>" class="ui-state-default">
								<span class="slide-col col-order">
									<span class="order-text"><?php echo $order; ?></span>
									<div class="state_loader" style="display:none;"></div>
									<?php if($state == "published"){ ?>
										<div class="icon_state state_published" data-slideid="<?php echo $postID; ?>" title="<?php _e("Unpublish Post",REVSLIDER_TEXTDOMAIN); ?>"></div>
									<?php }else{ ?>
										<div class="icon_state state_unpublished" data-slideid="<?php echo $postID; ?>" title="<?php _e("Publish Post",REVSLIDER_TEXTDOMAIN); ?>"></div>
									<?php } ?>
								</span>
								<span class="slide-col col-name">
									<div class="slide-title-in-list"><?php echo $linkEdit; ?></div>
									<a class='button-primary revgreen' href='<?php echo $urlEditSlide; ?>'><i class="revicon-pencil-1"></i><?php _e("Edit Post",REVSLIDER_TEXTDOMAIN); ?></a>
								</span>
								<span class="slide-col col-image">
									<?php if(!empty($urlImageForView)){ ?>
										<div id="slide_image_<?php echo $postID; ?>" class="slide_image" title="<?php _e('Click to change the slide image. Note: The post featured image will be changed.', REVSLIDER_TEXTDOMAIN); ?>" alt="<?php echo $imageAlt; ?>" style="background-image:url('<?php echo $urlImageForView; ?>');<?php echo $bgStyle; ?>"></div>
									<?php }else{ ?>
										<div id="slide_image_<?php echo $postID; ?>" class="slide_image" title="<?php _e('Click to change the slide image. Note: The post featured image will be changed.', REVSLIDER_TEXTDOMAIN); ?>" alt=""><?php _e('no image', REVSLIDER_TEXTDOMAIN); ?></div>
									<?php } ?>
								</span>
								<span class="slide-col col-operations-posts">
									<a id="button_delete_slide" class='button-primary revred button_delete_slide' data-slideid="<?php echo $postID; ?>" href='javascript:void(0)'><i class="revicon-trash"></i><?php _e("Delete",REVSLIDER_TEXTDOMAIN); ?></a>
								</span>
								<span class="slide-col col-handle">
									<div class="col-handle-inside">
										<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
									</div>
								</span>
								<div class="clear"></div>
							</li>
							<?php
						} ?>	
					</ul>
				</div>
			</div>
		</div>
		<div class="vert_sap_medium"></div>
		<div class="list_slides_bottom">
			<?php echo $linkNewPost; ?>
			<a class="button-primary revyellow" href='<?php echo self::getViewUrl(RevSliderAdmin::VIEW_SLIDERS); ?>' ><i class="revicon-cancel"></i><?php _e("Close",REVSLIDER_TEXTDOMAIN); ?></a>
			<a href="<?php echo $linksSliderSettings; ?>" class="button-primary revgreen"><i class="revicon-cog"></i><?php _e("Slider Settings",REVSLIDER_TEXTDOMAIN); ?></a>
		</div>
	</div>
	<script type="text/javascript">
		var g_messageDeleteSlide = "<?php _e("Warning! Removing this entry will cause the original wordpress post to be deleted.",REVSLIDER_TEXTDOMAIN); ?>";
		var g_messageChangeImage = "<?php _e("Select Slide Image",REVSLIDER_TEXTDOMAIN); ?>";
		jQuery(document).ready(function() {
			RevSliderAdmin.initSlidesListViewPosts("<?php echo $sliderID; ?>");
		});
	</script>
	<?php
}
?>