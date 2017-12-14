<?php if( !defined( 'ABSPATH') ) exit(); ?>

<div class="editor_buttons_wrapper  postbox unite-postbox" style="max-width:100% !important; min-width:1040px !important;">
	<div class="box-closed tp-accordion" style="border-bottom:5px solid #ddd;">
		<ul class="rs-slide-settings-tabs">
			<li data-content="#slide-main-image-settings-content" class="selected"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-1 rs-toolbar-icon"></i><span><?php _e("Main Background",REVSLIDER_TEXTDOMAIN); ?></span></li>
			<li data-content="#slide-general-settings-content"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-2 rs-toolbar-icon"></i><?php _e("General Settings",REVSLIDER_TEXTDOMAIN); ?></li>
			<li data-content="#slide-animation-settings-content" id="slide-animation-settings-content-tab"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-3 rs-toolbar-icon"></i><?php _e("Slide Animation",REVSLIDER_TEXTDOMAIN); ?></li>
			<li data-content="#slide-seo-settings-content"><i style="height:45px" class="rs-mini-layer-icon rs-icon-advanced rs-toolbar-icon"></i><?php _e("Link & Seo",REVSLIDER_TEXTDOMAIN); ?></li>
			<li data-content="#slide-info-settings-content"><i style="height:45px; font-size:16px;" class="rs-mini-layer-icon eg-icon-info-circled rs-toolbar-icon"></i><?php _e("Slide Info",REVSLIDER_TEXTDOMAIN); ?></li>						
		</ul>

		<div style="clear:both"></div>
		<script type="text/javascript">
			jQuery('document').ready(function() {
				jQuery('.rs-slide-settings-tabs li').click(function() {
					var tw = jQuery('.rs-slide-settings-tabs .selected'),
						tn = jQuery(this);
					jQuery(tw.data('content')).hide(0);
					tw.removeClass("selected");
					tn.addClass("selected");
					jQuery(tn.data('content')).show(0);
				});
			});
		</script>
	</div>
	<div style="padding:15px">
		<form name="form_slide_params" id="form_slide_params" class="slide-main-settings-form">
			
			<div id="slide-main-image-settings-content" class="slide-main-settings-form">

				<ul class="rs-layer-main-image-tabs" style="display:inline-block; ">
					<li data-content="#mainbg-sub-source" class="selected"><?php _e('Source', REVSLIDER_TEXTDOMAIN); ?></li>
					<li class="mainbg-sub-settings-selector" data-content="#mainbg-sub-setting"><?php _e('Source Settings', REVSLIDER_TEXTDOMAIN); ?></li>					
					<li class="mainbg-sub-parallax-selector" data-content="#mainbg-sub-parallax"><?php _e('Parallax', REVSLIDER_TEXTDOMAIN); ?></li>
					<li class="mainbg-sub-kenburns-selector" data-content="#mainbg-sub-kenburns"><?php _e('Ken Burns', REVSLIDER_TEXTDOMAIN); ?></li>
				</ul>

				<div class="tp-clearfix"></div>

				<script type="text/javascript">
					jQuery('document').ready(function() {
						jQuery('.rs-layer-main-image-tabs li').click(function() {
							var tw = jQuery('.rs-layer-main-image-tabs .selected'),
								tn = jQuery(this);
							jQuery(tw.data('content')).hide(0);
							tw.removeClass("selected");
							tn.addClass("selected");
							jQuery(tn.data('content')).show(0);
						});
					});
				</script>


				<!-- SLIDE MAIN IMAGE -->
				<span id="mainbg-sub-source" style="display:block">
					<div style="float:none; clear:both; margin-bottom: 15px;"></div>
					<input type="hidden" name="rs-gallery-type" value="<?php echo esc_attr($slider_type); ?>" />
					<span class="diblock bg-settings-block">
						<!-- IMAGE FROM MEDIAGALLERY -->												
						<?php
						if($slider_type == 'posts' || $slider_type == 'specific_posts'){
							?>
							<label><?php _e("Featured Image",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="radio" name="background_type" value="image" class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on" data-bgtype="image" id="radio_back_image" <?php checked($bgType, 'image'); ?>>
							
							<?php
							/*
							<div class="tp-clearfix"></div>
							<label><?php _e("Meta Image",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="radio" name="background_type" value="meta" class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on" data-bgtype="meta" <?php checked($bgType, 'meta'); ?>>
							<span id="" class="" style="margin-left:20px;">
								<span style="margin-right: 10px"><?php _e('Meta Handle', REVSLIDER_TEXTDOMAIN); ?></span>
								<input type="text" id="meta_handle" name="meta_handle" value="<?php echo $meta_handle; ?>">
							</span>*/ ?>
							<?php
						}elseif($slider_type !== 'gallery'){
							?>
							<label><?php _e("Stream Image",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="radio" name="background_type" value="image" class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on" data-bgtype="image" id="radio_back_image" <?php checked($bgType, 'image'); ?>>
							<?php
							if($slider_type == 'vimeo' || $slider_type == 'youtube' || $slider_type == 'instagram' || $slider_type == 'twitter'){
								?>
								<div class="tp-clearfix"></div>
								<label><?php _e("Stream Video",REVSLIDER_TEXTDOMAIN); ?></label>
								<input type="radio" name="background_type" value="stream<?php echo $slider_type; ?>" class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on" data-bgtype="stream<?php echo $slider_type; ?>" <?php checked($bgType, 'stream'.$slider_type); ?>>
								<span id="streamvideo_cover" class="streamvideo_cover" style="display:none;margin-left:20px;">
									<span style="margin-right: 10px"><?php _e("Use Cover",REVSLIDER_TEXTDOMAIN); ?></span>
									<input type="checkbox" class="tp-moderncheckbox" id="stream_do_cover" name="stream_do_cover" data-unchecked="off" <?php checked($stream_do_cover, 'on'); ?>>
								</span>
								
								<div class="tp-clearfix"></div>
								<label><?php _e("Stream Video + Image",REVSLIDER_TEXTDOMAIN); ?></label>
								<input type="radio" name="background_type" value="stream<?php echo $slider_type; ?>both" class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on" data-bgtype="stream<?php echo $slider_type; ?>both" <?php checked($bgType, 'stream'.$slider_type.'both'); ?>>
								<span id="streamvideo_cover_both" class="streamvideo_cover_both" style="display:none;margin-left:20px;">
									<span style="margin-right: 10px"><?php _e("Use Cover",REVSLIDER_TEXTDOMAIN); ?></span>
									<input type="checkbox" class="tp-moderncheckbox" id="stream_do_cover_both" name="stream_do_cover_both" data-unchecked="off" <?php checked($stream_do_cover_both, 'on'); ?>>
								</span>
								<?php
							}
						}else{
							?>
							<label ><?php _e("Main / Background Image",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="radio" name="background_type" value="image" class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on" data-bgtype="image" id="radio_back_image" <?php checked($bgType, 'image'); ?>>
							
							<?php
						}
						?>
						<!-- THE BG IMAGE CHANGED DIV -->
						<span id="tp-bgimagewpsrc" class="bgsrcchanger-div" style="display:none;margin-left:20px;">
							<a href="javascript:void(0)" id="button_change_image" class="button-primary revblue" ><?php _e("Change Image", REVSLIDER_TEXTDOMAIN); ?></a>
						</span>
						
						<div class="tp-clearfix"></div>
						
						<!-- IMAGE FROM EXTERNAL -->
						<label><?php _e("External URL",REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="radio" name="background_type" value="external" data-callid="tp-bgimageextsrc" data-imgsettings="on" class="bgsrcchanger" data-bgtype="external" id="radio_back_external" <?php checked($bgType, 'external'); ?>>

						<!-- THE BG IMAGE FROM EXTERNAL SOURCE -->
						<span id="tp-bgimageextsrc" class="bgsrcchanger-div" style="display:none;margin-left:20px;">
							<input type="text" name="bg_external" id="slide_bg_external" value="<?php echo $slideBGExternal?>" <?php echo ($bgType != 'external') ? ' class="disabled"' : ''; ?>>
							<a href="javascript:void(0)" id="button_change_external" class="button-primary revblue" ><?php _e("Get External",REVSLIDER_TEXTDOMAIN); ?></a>
						</span>
						
						<div class="tp-clearfix"></div>
						
						<!-- TRANSPARENT BACKGROUND -->
						<label><?php _e("Transparent",REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="radio" name="background_type" value="trans" data-callid="" class="bgsrcchanger" data-bgtype="trans" id="radio_back_trans" <?php checked($bgType, 'trans'); ?>>
						<div class="tp-clearfix"></div>
						
						<!-- COLORED BACKGROUND -->
						<label><?php _e("Solid Colored",REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="radio" name="background_type" value="solid"  data-callid="tp-bgcolorsrc" class="bgsrcchanger" data-bgtype="solid" id="radio_back_solid" <?php checked($bgType, 'solid'); ?>>
						
						<!-- THE COLOR SELECTOR -->
						<span id="tp-bgcolorsrc"  class="bgsrcchanger-div"  style="display:none;margin-left:20px;">
							<input type="text" name="bg_color" id="slide_bg_color" class="my-color-field" value="<?php echo $slideBGColor; ?>">
						</span>
						<div class="tp-clearfix"></div>

						<!-- THE YOUTUBE SELECTOR -->
						<label><?php _e("YouTube Video",REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="radio" name="background_type" value="youtube"  data-callid="tp-bgyoutubesrc" class="bgsrcchanger" data-bgtype="youtube" id="radio_back_youtube" <?php checked($bgType, 'youtube'); ?>>
						<div class="tp-clearfix"></div>
						
						<!-- THE BG IMAGE FROM YOUTUBE SOURCE -->
						<span id="tp-bgyoutubesrc" class="bgsrcchanger-div" style="display:none; margin-left:20px;">
							<label style="min-width:180px"><?php _e("ID:",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" name="slide_bg_youtube" id="slide_bg_youtube" value="<?php echo $slideBGYoutube; ?>" <?php echo ($bgType != 'youtube') ? ' class="disabled"' : ''; ?>>							
							<?php _e('example: T8--OggjJKQ', REVSLIDER_TEXTDOMAIN); ?>
							<div class="tp-clearfix"></div>
							<label style="min-width:180px"><?php _e("Cover Image:",REVSLIDER_TEXTDOMAIN); ?></label>
							<span id="youtube-image-picker"></span>
						</span>
						<div class="tp-clearfix"></div>
						
						<!-- THE VIMEO SELECTOR -->
						<label><?php _e("Vimeo Video",REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="radio" name="background_type" value="vimeo"  data-callid="tp-bgvimeosrc" class="bgsrcchanger" data-bgtype="vimeo" id="radio_back_vimeo" <?php checked($bgType, 'vimeo'); ?>>
						<div class="tp-clearfix"></div>

						<!-- THE BG IMAGE FROM VIMEO SOURCE -->
						<span id="tp-bgvimeosrc" class="bgsrcchanger-div" style="display:none; margin-left:20px;">
							<label style="min-width:180px"><?php _e("ID:",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" name="slide_bg_vimeo" id="slide_bg_vimeo" value="<?php echo $slideBGVimeo; ?>" <?php echo ($bgType != 'vimeo') ? ' class="disabled"' : ''; ?>>							
							<?php _e('example: 30300114', REVSLIDER_TEXTDOMAIN); ?>
							<div class="tp-clearfix"></div>
							<label style="min-width:180px"><?php _e("Cover Image:",REVSLIDER_TEXTDOMAIN); ?></label>
							<span id="vimeo-image-picker"></span>
						</span>
						<div class="tp-clearfix"></div>

						<!-- THE HTML5 SELECTOR -->
						<label><?php _e("HTML5 Video",REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="radio" name="background_type" value="html5"  data-callid="tp-bghtmlvideo" class="bgsrcchanger" data-bgtype="html5" id="radio_back_htmlvideo" <?php checked($bgType, 'html5'); ?>>
						<div class="tp-clearfix"></div>
						<!-- THE BG IMAGE FROM HTML5 SOURCE -->
						<span id="tp-bghtmlvideo" class="bgsrcchanger-div" style="display:none; margin-left:20px;">
							
							<label style="min-width:180px"><?php _e('MPEG:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" name="slide_bg_html_mpeg" id="slide_bg_html_mpeg" value="<?php echo $slideBGhtmlmpeg; ?>" <?php echo ($bgType != 'html5') ? ' class="disabled"' : ''; ?>>
							<span class="vidsrcchanger-div" style="margin-left:20px;">
								<a href="javascript:void(0)" data-inptarget="slide_bg_html_mpeg" class="button_change_video button-primary revblue" ><?php _e('Change Video', REVSLIDER_TEXTDOMAIN); ?></a>
							</span>
							<div class="tp-clearfix"></div>
							<label style="min-width:180px"><?php _e('WEBM:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" name="slide_bg_html_webm" id="slide_bg_html_webm" value="<?php echo $slideBGhtmlwebm; ?>" <?php echo ($bgType != 'html5') ? ' class="disabled"' : ''; ?>>
							<span class="vidsrcchanger-div" style="margin-left:20px;">
								<a href="javascript:void(0)" data-inptarget="slide_bg_html_webm" class="button_change_video button-primary revblue" ><?php _e('Change Video', REVSLIDER_TEXTDOMAIN); ?></a>
							</span>
							<div class="tp-clearfix"></div>
							<label style="min-width:180px"><?php _e('OGV:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" name="slide_bg_html_ogv" id="slide_bg_html_ogv" value="<?php echo $slideBGhtmlogv; ?>" <?php echo ($bgType != 'html5') ? ' class="disabled"' : ''; ?>>							
							<span class="vidsrcchanger-div" style="margin-left:20px;">
								<a href="javascript:void(0)" data-inptarget="slide_bg_html_ogv" class="button_change_video button-primary revblue" ><?php _e('Change Video', REVSLIDER_TEXTDOMAIN); ?></a>
							</span>
							<div class="tp-clearfix"></div>
							<label style="min-width:180px"><?php _e('Cover Image:', REVSLIDER_TEXTDOMAIN); ?></label>
							<span id="html5video-image-picker"></span>
						</span>
					</span>
				</span>
				<span id="mainbg-sub-setting" style="display:none">
					<div class="rs-img-source-url">
						<div style="float:none; clear:both; margin-bottom: 15px;"></div>
						<label><?php _e('Image Source:', REVSLIDER_TEXTDOMAIN); ?></label>
						<span class="text-selectable" id="the_image_source_url" style="margin-right:20px"></span>
					</div>

					<div class="rs-img-source-size">
						<div style="float:none; clear:both; margin-bottom: 15px;"></div>
						<label><?php _e('Image Source Size:', REVSLIDER_TEXTDOMAIN); ?></label>
						<span style="margin-right:20px">
							<select name="image_source_type">
								<?php
								foreach($img_sizes as $imghandle => $imgSize){
									$sel = ($bg_image_size == $imghandle) ? ' selected="selected"' : '';
									echo '<option value="'.sanitize_title($imghandle).'"'.$sel.'>'.$imgSize.'</option>';
								}
								?>
							</select>
						</span>
					</div>
					
					<span id="tp-bgimagesettings" class="bgsrcchanger-div" style="display:none;">
						<!-- ALT -->
						<p>
							<?php $alt_option = RevSliderFunctions::getVal($slideParams, 'alt_option', 'media_library'); ?>
							<label><?php _e("Alt:",REVSLIDER_TEXTDOMAIN); ?></label>
							<select id="alt_option" name="alt_option">
								<option value="media_library" <?php selected($alt_option, 'media_library'); ?>><?php _e('From Media Library', REVSLIDER_TEXTDOMAIN); ?></option>
								<option value="file_name" <?php selected($alt_option, 'file_name'); ?>><?php _e('From Filename', REVSLIDER_TEXTDOMAIN); ?></option>
								<option value="custom" <?php selected($alt_option, 'custom'); ?>><?php _e('Custom', REVSLIDER_TEXTDOMAIN); ?></option>
							</select>
							<?php $alt_attr = RevSliderFunctions::getVal($slideParams, 'alt_attr', ''); ?>
							<input style="<?php echo ($alt_option !== 'custom') ? 'display:none;' : ''; ?>" type="text" id="alt_attr" name="alt_attr" value="<?php echo $alt_attr; ?>">
						</p>
						<p class="ext_setting" style="display: none;">
							<label><?php _e('Width:', REVSLIDER_TEXTDOMAIN)?></label>
							<input type="text" name="ext_width" value="<?php echo $ext_width; ?>" />
						</p>
						<p class="ext_setting" style="display: none;">
							<label><?php _e('Height:', REVSLIDER_TEXTDOMAIN)?></label>
							<input type="text" name="ext_height" value="<?php echo $ext_height; ?>" />
						</p>
					</span>					
					
					<span id="video-settings" style="display: block;">
						<p>
							<label for="video_force_cover" class="video-label"><?php _e('Force Cover:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="checkbox" class="tp-moderncheckbox" id="video_force_cover" name="video_force_cover" data-unchecked="off" <?php checked($video_force_cover, 'on'); ?>>
						</p>
						<span id="video_dotted_overlay_wrap">
							<label for="video_dotted_overlay">
								<?php _e('Dotted Overlay:', REVSLIDER_TEXTDOMAIN); ?>
							</label>				
							<select id="video_dotted_overlay" name="video_dotted_overlay" style="width:100px">
								<option <?php selected($video_dotted_overlay, 'none'); ?> value="none"><?php _e('none', REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_dotted_overlay, 'twoxtwo'); ?> value="twoxtwo"><?php _e('2 x 2 Black', REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_dotted_overlay, 'twoxtwowhite'); ?> value="twoxtwowhite"><?php _e('2 x 2 White', REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_dotted_overlay, 'threexthree'); ?> value="threexthree"><?php _e('3 x 3 Black', REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_dotted_overlay, 'threexthreewhite'); ?> value="threexthreewhite"><?php _e('3 x 3 White', REVSLIDER_TEXTDOMAIN); ?></option>
							</select>
							<p style="clear: both;"></p>
							<label for="video_ratio">
								<?php _e("Aspect Ratio:", REVSLIDER_TEXTDOMAIN); ?>
							</label>				
							<select id="video_ratio" name="video_ratio" style="width:100px">
								<option <?php selected($video_ratio, '16:9');?> value="16:9"><?php _e('16:9',REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_ratio, '4:3');?> value="4:3"><?php _e('4:3',REVSLIDER_TEXTDOMAIN); ?></option>
							</select>
							<p style="clear: both;"></p>
						</span>
						<p>
							<label for="video_ratio">
								<?php _e("Start At:", REVSLIDER_TEXTDOMAIN); ?>
							</label>				
							<input type="text" value="<?php echo $video_start_at; ?>" name="video_start_at"> <?php _e('For Example: 00:17', REVSLIDER_TEXTDOMAIN); ?>
							<p style="clear: both;"></p>
						</p>
						<p>
							<label for="video_ratio">
								<?php _e("End At:", REVSLIDER_TEXTDOMAIN); ?>
							</label>				
							<input type="text" value="<?php echo $video_end_at; ?>" name="video_end_at"> <?php _e('For Example: 02:17', REVSLIDER_TEXTDOMAIN); ?>
							<p style="clear: both;"></p>
						</p>
						<p>
							<label for="video_loop"><?php _e('Loop Video:', REVSLIDER_TEXTDOMAIN); ?></label>
							<select id="video_loop" name="video_loop" style="width: 200px;">
								<option <?php selected($video_loop, 'none');?> value="none"><?php _e('Disable', REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_loop, 'loop');?> value="loop"><?php _e('Loop, Slide is paused', REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($video_loop, 'loopandnoslidestop');?> value="loopandnoslidestop"><?php _e('Loop, Slide does not stop', REVSLIDER_TEXTDOMAIN); ?></option>
							</select>
						</p>
						
						<p>	
							<label for="video_nextslide"><?php _e('Next Slide On End:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="checkbox" class="tp-moderncheckbox" id="video_nextslide" name="video_nextslide" data-unchecked="off" <?php checked($video_nextslide, 'on'); ?>>
						</p>
						<p>
							<label for="video_force_rewind"><?php _e('Rewind at Slide Start:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="checkbox" class="tp-moderncheckbox" id="video_force_rewind" name="video_force_rewind" data-unchecked="off" <?php checked($video_force_rewind, 'on'); ?>>
						</p>
						
						<p>	
							<label for="video_mute"><?php _e('Mute Video:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="checkbox" class="tp-moderncheckbox" id="video_mute" name="video_mute" data-unchecked="off" <?php checked($video_mute, 'on'); ?>>
						</p>
						
						<p class="vid-rev-vimeo-youtube video_volume_wrapper">
							<label for="video_volume"><?php _e('Video Volume:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" id="video_volume" name="video_volume" <?php echo esc_attr($video_volume); ?>>
						</p>

						<span id="vid-rev-youtube-options">
							<p>
								<label for="video_speed"><?php _e('Video Speed:', REVSLIDER_TEXTDOMAIN); ?></label>
								<select id="video_speed" name="video_speed" style="width:75px">
									<option <?php selected($video_speed, '0.25');?> value="0.25"><?php _e('0.25', REVSLIDER_TEXTDOMAIN); ?></option>
									<option <?php selected($video_speed, '0.50');?> value="0.50"><?php _e('0.50', REVSLIDER_TEXTDOMAIN); ?></option>
									<option <?php selected($video_speed, '1');?> value="1"><?php _e('1', REVSLIDER_TEXTDOMAIN); ?></option>
									<option <?php selected($video_speed, '1.5');?> value="1.5"><?php _e('1.5', REVSLIDER_TEXTDOMAIN); ?></option>
									<option <?php selected($video_speed, '2');?> value="2"><?php _e('2', REVSLIDER_TEXTDOMAIN); ?></option>
								</select>
							</p>
							<p>
								<label><?php _e('Arguments YouTube:', REVSLIDER_TEXTDOMAIN); ?></label>
								<input type="text" id="video_arguments" style="width:350px;" value="<?php echo esc_attr($video_arguments); ?>">
							</p>
						</span>
						<p id="vid-rev-vimeo-options">
							<label><?php _e('Arguments Vimeo:', REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" id="video_arguments_vim" style="width:350px;" value="<?php echo esc_attr($video_arguments_vim); ?>">
						</p>
					</span>
					
					<span id="bg-setting-wrap">
						<p>
							<label for="slide_bg_fit"><?php _e('Background Fit:', REVSLIDER_TEXTDOMAIN); ?></label>
							<select name="bg_fit" id="slide_bg_fit" style="margin-right:20px">
								<option value="cover"<?php selected($bgFit, 'cover'); ?>>cover</option>
								<option value="contain"<?php selected($bgFit, 'contain'); ?>>contain</option>
								<option value="percentage"<?php selected($bgFit, 'percentage'); ?>>(%, %)</option>
								<option value="normal"<?php selected($bgFit, 'normal'); ?>>normal</option>
							</select>
							<input type="text" name="bg_fit_x" style="min-width:54px;width:54px; <?php if($bgFit != 'percentage') echo 'display: none; '; ?> width:60px;margin-right:10px" value="<?php echo $bgFitX; ?>" />
							<input type="text" name="bg_fit_y" style="min-width:54px;width:54px;  <?php if($bgFit != 'percentage') echo 'display: none; '; ?> width:60px;margin-right:10px"  value="<?php echo $bgFitY; ?>" />
						</p>
						<p>
							<label for="slide_bg_position" id="bg-position-lbl"><?php _e('Background Position:', REVSLIDER_TEXTDOMAIN); ?></label>
							<span id="bg-start-position-wrapper">
								<select name="bg_position" id="slide_bg_position">
									<option value="center top"<?php selected($bgPosition, 'center top'); ?>>center top</option>
									<option value="center right"<?php selected($bgPosition, 'center right'); ?>>center right</option>
									<option value="center bottom"<?php selected($bgPosition, 'center bottom'); ?>>center bottom</option>
									<option value="center center"<?php selected($bgPosition, 'center center'); ?>>center center</option>
									<option value="left top"<?php selected($bgPosition, 'left top'); ?>>left top</option>
									<option value="left center"<?php selected($bgPosition, 'left center'); ?>>left center</option>
									<option value="left bottom"<?php selected($bgPosition, 'left bottom'); ?>>left bottom</option>
									<option value="right top"<?php selected($bgPosition, 'right top'); ?>>right top</option>
									<option value="right center"<?php selected($bgPosition, 'right center'); ?>>right center</option>
									<option value="right bottom"<?php selected($bgPosition, 'right bottom'); ?>>right bottom</option>
									<option value="percentage"<?php selected($bgPosition, 'percentage'); ?>>(x%, y%)</option>
								</select>
								<input type="text" name="bg_position_x" style="min-width:54px;width:54px; <?php if($bgPosition != 'percentage') echo 'display: none;'; ?>width:60px;margin-right:10px" value="<?php echo $bgPositionX; ?>" />
								<input type="text" name="bg_position_y" style="min-width:54px;width:54px; <?php if($bgPosition != 'percentage') echo 'display: none;'; ?>width:60px;margin-right:10px" value="<?php echo $bgPositionY; ?>" />
							</span>
						</p>

						<p>
							<label><?php _e("Background Repeat:",REVSLIDER_TEXTDOMAIN)?></label>
							<span>
								<select name="bg_repeat" id="slide_bg_repeat" style="margin-right:20px">
									<option value="no-repeat"<?php selected($bgRepeat, 'no-repeat'); ?>>no-repeat</option>
									<option value="repeat"<?php selected($bgRepeat, 'repeat'); ?>>repeat</option>
									<option value="repeat-x"<?php selected($bgRepeat, 'repeat-x'); ?>>repeat-x</option>
									<option value="repeat-y"<?php selected($bgRepeat, 'repeat-y'); ?>>repeat-y</option>
								</select>
							</span>
						</p>
					</span>
					
				</span>

				<span id="mainbg-sub-parallax" style="display:none">
					<p>
						<label><?php _e("Parallax Level:",REVSLIDER_TEXTDOMAIN); ?></label>
						<select name="slide_parallax_level" id="slide_parallax_level">
							<option value="-" <?php selected($slide_parallax_level, '-'); ?>><?php _e('No Parallax', REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="1" <?php selected($slide_parallax_level, '1'); ?>>1</option>
							<option value="2" <?php selected($slide_parallax_level, '2'); ?>>2</option>
							<option value="3" <?php selected($slide_parallax_level, '3'); ?>>3</option>
							<option value="4" <?php selected($slide_parallax_level, '4'); ?>>4</option>
							<option value="5" <?php selected($slide_parallax_level, '5'); ?>>5</option>
							<option value="6" <?php selected($slide_parallax_level, '6'); ?>>6</option>
							<option value="7" <?php selected($slide_parallax_level, '7'); ?>>7</option>
							<option value="8" <?php selected($slide_parallax_level, '8'); ?>>8</option>
							<option value="9" <?php selected($slide_parallax_level, '9'); ?>>9</option>
							<option value="10" <?php selected($slide_parallax_level, '10'); ?>>10</option>
						</select>
					</p>
					<?php 
					if ($use_parallax=="off") {						
						echo '<i style="color:#c0392b">';
						_e("Parallax Feature in Slider Settings is deactivated, parallax will be ignored.",REVSLIDER_TEXTDOMAIN); 
						echo '</i>';
					}
					?>
				</span>

				<span id="mainbg-sub-kenburns" style="display:none">
					<p>
						<label><?php _e('Ken Burns / Pan Zoom:', REVSLIDER_TEXTDOMAIN); ?></label>
						<input type="checkbox" class="tp-moderncheckbox withlabel" id="kenburn_effect" name="kenburn_effect" data-unchecked="off" <?php checked($kenburn_effect, 'on'); ?>>
					</p>
					<span id="kenburn_wrapper" <?php echo ($kenburn_effect == 'off') ? 'style="display: none;"' : ''; ?>>						
						<p>
							<label><?php _e('Scale: (in %):', REVSLIDER_TEXTDOMAIN); ?></label>
							<label style="min-width:40px"><?php _e('From', REVSLIDER_TEXTDOMAIN); ?></label>
							<input style="min-width:54px;width:54px" type="text" name="kb_start_fit" value="<?php echo intval($kb_start_fit); ?>" />
							<label style="min-width:20px"><?php _e('To', REVSLIDER_TEXTDOMAIN)?></label>
							<input style="min-width:54px;width:54px" type="text" name="kb_end_fit" value="<?php echo intval($kb_end_fit); ?>" />
						</p>
						
						<p>
							<label><?php _e('Horizontal Offsets:', REVSLIDER_TEXTDOMAIN)?></label>
							<label style="min-width:40px"><?php _e('From', REVSLIDER_TEXTDOMAIN); ?></label>							
							<input style="min-width:54px;width:54px" type="text" name="kb_start_offset_x" value="<?php echo $kbStartOffsetX; ?>" />
							<label style="min-width:20px"><?php _e('To', REVSLIDER_TEXTDOMAIN)?></label>
							<input style="min-width:54px;width:54px" type="text" name="kb_end_offset_x" value="<?php echo $kbEndOffsetX; ?>" />
							<span><i><?php _e('Use Negative and Positive Values to offset from the Center !', REVSLIDER_TEXTDOMAIN); ?></i></span>
						</p>

						<p>
							<label><?php _e('Vertical Offsets:', REVSLIDER_TEXTDOMAIN)?></label>		
							<label style="min-width:40px"><?php _e('From', REVSLIDER_TEXTDOMAIN); ?></label>												
							<input style="min-width:54px;width:54px" type="text" name="kb_start_offset_y" value="<?php echo $kbStartOffsetY; ?>" />
							<label style="min-width:20px"><?php _e('To', REVSLIDER_TEXTDOMAIN)?></label>
							<input style="min-width:54px;width:54px" type="text" name="kb_end_offset_y" value="<?php echo $kbEndOffsetY; ?>" />
							<span><i><?php _e('Use Negative and Positive Values to offset from the Center !', REVSLIDER_TEXTDOMAIN); ?></i></span>
						</p>
						
						<p>
							<label><?php _e('Rotation:', REVSLIDER_TEXTDOMAIN)?></label>		
							<label style="min-width:40px"><?php _e('From', REVSLIDER_TEXTDOMAIN); ?></label>												
							<input style="min-width:54px;width:54px" type="text" name="kb_start_rotate" value="<?php echo $kbStartRotate; ?>" />
							<label style="min-width:20px"><?php _e('To', REVSLIDER_TEXTDOMAIN)?></label>
							<input style="min-width:54px;width:54px" type="text" name="kb_end_rotate" value="<?php echo $kbEndRotate; ?>" />
						</p>
						
						<p>
							<label><?php _e('Easing:', REVSLIDER_TEXTDOMAIN); ?></label>
							<select name="kb_easing">
								<option <?php selected($kb_easing, 'Linear.easeNone'); ?> value="Linear.easeNone">Linear.easeNone</option>
								<option <?php selected($kb_easing, 'Power0.easeIn'); ?> value="Power0.easeIn">Power0.easeIn  (linear)</option>
								<option <?php selected($kb_easing, 'Power0.easeInOut'); ?> value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
								<option <?php selected($kb_easing, 'Power0.easeOut'); ?> value="Power0.easeOut">Power0.easeOut  (linear)</option>
								<option <?php selected($kb_easing, 'Power1.easeIn'); ?> value="Power1.easeIn">Power1.easeIn</option>
								<option <?php selected($kb_easing, 'Power1.easeInOut'); ?> value="Power1.easeInOut">Power1.easeInOut</option>
								<option <?php selected($kb_easing, 'Power1.easeOut'); ?> value="Power1.easeOut">Power1.easeOut</option>
								<option <?php selected($kb_easing, 'Power2.easeIn'); ?> value="Power2.easeIn">Power2.easeIn</option>
								<option <?php selected($kb_easing, 'Power2.easeInOut'); ?> value="Power2.easeInOut">Power2.easeInOut</option>
								<option <?php selected($kb_easing, 'Power2.easeOut'); ?> value="Power2.easeOut">Power2.easeOut</option>
								<option <?php selected($kb_easing, 'Power3.easeIn'); ?> value="Power3.easeIn">Power3.easeIn</option>
								<option <?php selected($kb_easing, 'Power3.easeInOut'); ?> value="Power3.easeInOut">Power3.easeInOut</option>
								<option <?php selected($kb_easing, 'Power3.easeOut'); ?> value="Power3.easeOut">Power3.easeOut</option>
								<option <?php selected($kb_easing, 'Power4.easeIn'); ?> value="Power4.easeIn">Power4.easeIn</option>
								<option <?php selected($kb_easing, 'Power4.easeInOut'); ?> value="Power4.easeInOut">Power4.easeInOut</option>
								<option <?php selected($kb_easing, 'Power4.easeOut'); ?> value="Power4.easeOut">Power4.easeOut</option>
								<option <?php selected($kb_easing, 'Back.easeIn'); ?> value="Back.easeIn">Back.easeIn</option>
								<option <?php selected($kb_easing, 'Back.easeInOut'); ?> value="Back.easeInOut">Back.easeInOut</option>
								<option <?php selected($kb_easing, 'Back.easeOut'); ?> value="Back.easeOut">Back.easeOut</option>
								<option <?php selected($kb_easing, 'Bounce.easeIn'); ?> value="Bounce.easeIn">Bounce.easeIn</option>
								<option <?php selected($kb_easing, 'Bounce.easeInOut'); ?> value="Bounce.easeInOut">Bounce.easeInOut</option>
								<option <?php selected($kb_easing, 'Bounce.easeOut'); ?> value="Bounce.easeOut">Bounce.easeOut</option>
								<option <?php selected($kb_easing, 'Circ.easeIn'); ?> value="Circ.easeIn">Circ.easeIn</option>
								<option <?php selected($kb_easing, 'Circ.easeInOut'); ?> value="Circ.easeInOut">Circ.easeInOut</option>
								<option <?php selected($kb_easing, 'Circ.easeOut'); ?> value="Circ.easeOut">Circ.easeOut</option>
								<option <?php selected($kb_easing, 'Elastic.easeIn'); ?> value="Elastic.easeIn">Elastic.easeIn</option>
								<option <?php selected($kb_easing, 'Elastic.easeInOut'); ?> value="Elastic.easeInOut">Elastic.easeInOut</option>
								<option <?php selected($kb_easing, 'Elastic.easeOut'); ?> value="Elastic.easeOut">Elastic.easeOut</option>
								<option <?php selected($kb_easing, 'Expo.easeIn'); ?> value="Expo.easeIn">Expo.easeIn</option>
								<option <?php selected($kb_easing, 'Expo.easeInOut'); ?> value="Expo.easeInOut">Expo.easeInOut</option>
								<option <?php selected($kb_easing, 'Expo.easeOut'); ?> value="Expo.easeOut">Expo.easeOut</option>
								<option <?php selected($kb_easing, 'Sine.easeIn'); ?> value="Sine.easeIn">Sine.easeIn</option>
								<option <?php selected($kb_easing, 'Sine.easeInOut'); ?> value="Sine.easeInOut">Sine.easeInOut</option>
								<option <?php selected($kb_easing, 'Sine.easeOut'); ?> value="Sine.easeOut">Sine.easeOut</option>
								<option <?php selected($kb_easing, 'SlowMo.ease'); ?> value="SlowMo.ease">SlowMo.ease</option>
							</select>
						</p>
						<p>
							<label><?php _e('Duration (in ms):', REVSLIDER_TEXTDOMAIN)?></label>
							<input type="text" name="kb_duration" value="<?php echo intval($kb_duration); ?>" />
						</p>
					</span>
				</span>
				
				<input type="hidden" id="image_url" name="image_url" value="<?php echo $imageUrl; ?>" />
				<input type="hidden" id="image_id" name="image_id" value="<?php echo $imageID; ?>" />
			</div>
			
			<div id="slide-general-settings-content" style="display:none">
				<!-- SLIDE TITLE -->
				<p style="display:none">
					<?php $title = RevSliderFunctions::getVal($slideParams, 'title','Slide'); ?>
					<label><?php _e("Slide Title",REVSLIDER_TEXTDOMAIN); ?></label>
					<input type="text" class="medium" id="title" disabled="disabled" name="title" value="<?php echo $title; ?>">
					<span class="description"><?php _e("The title of the slide, will be shown in the slides list.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- SLIDE DELAY -->
				<p>
					<?php $delay = RevSliderFunctions::getVal($slideParams, 'delay',''); ?>
					<label><?php _e('Slide "Delay":',REVSLIDER_TEXTDOMAIN); ?></label>
					<input type="text" class="small-text" id="delay" name="delay" value="<?php echo $delay; ?>">
					<span class="description"><?php _e("A new delay value for the Slide. If no delay defined per slide, the delay defined via Options (9000ms) will be used.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- SLIDE STATE --->
				<p>
					<?php $state = RevSliderFunctions::getVal($slideParams, 'state','published'); ?>
					<label><?php _e("Slide State",REVSLIDER_TEXTDOMAIN); ?></label>
					<select id="state" name="state">
						<option value="published"<?php selected($state, 'published'); ?>><?php _e("Published",REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="unpublished"<?php selected($state, 'unpublished'); ?>><?php _e("Unpublished",REVSLIDER_TEXTDOMAIN); ?></option>
					</select>
					<span class="description"><?php _e("The state of the slide. The unpublished slide will be excluded from the slider.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>
				<!-- SLIDE LANGUAGE SELECTOR -->
				<?php
				if(isset($slider) && $slider->isInited()){
					$isWpmlExists = RevSliderWpml::isWpmlExists();
					$useWpml = $slider->getParam("use_wpml","off");

					if($isWpmlExists && $useWpml == "on"){
						$arrLangs = RevSliderWpml::getArrLanguages();
						$curset_lang = RevSliderFunctions::getVal($slideParams, "lang","all");
						?>
						<p>
							<label><?php _e("Language",REVSLIDER_TEXTDOMAIN); ?></label>
							<select name="lang">
								<?php
								if(!empty($arrLangs) && is_array($arrLangs)){
									foreach($arrLangs as $lang_handle => $lang_name){
										$sel = ($lang_handle === $curset_lang) ? ' selected="selected"' : '';
										echo '<option value="'.$lang_handle.'"'.$sel.'>'.$lang_name.'</option>';
									}
								}
								?>
							</select>
							<span class="description"><?php _e("The language of the slide (uses WPML plugin).",REVSLIDER_TEXTDOMAIN); ?></span>
						</p>
						<?php
					}
				}
				?>
				<!-- SLIDE VISIBLE FROM -->
				<p>
					<?php $date_from = RevSliderFunctions::getVal($slideParams, 'date_from',''); ?>
					<label><?php _e("Visible from:",REVSLIDER_TEXTDOMAIN); ?></label>
					<input type="text" class="inputDatePicker" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
					<span class="description"><?php _e("If set, slide will be visible after the date is reached.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- SLIDE VISIBLE UNTIL -->
				<p>
					<?php $date_to = RevSliderFunctions::getVal($slideParams, 'date_to',''); ?>
					<label><?php _e("Visible until:",REVSLIDER_TEXTDOMAIN); ?></label>
					<input type="text" class="inputDatePicker" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
					<span class="description"><?php _e("If set, slide will be visible till the date is reached.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- THUMBNAIL SETTINGS -->
				<div>
					<?php $slide_thumb = RevSliderFunctions::getVal($slideParams, 'slide_thumb',''); ?>
					<span style="display:inline-block; vertical-align: top;">
						<label><?php _e("Thumbnail:",REVSLIDER_TEXTDOMAIN); ?></label>
					</span>
					<div style="display:inline-block; vertical-align: top;">
						<div id="slide_thumb_button_preview" class="setting-image-preview"><?php
						if(intval($slide_thumb) > 0){
							?>
							<div style="width:100px;height:70px;background:url('<?php echo admin_url( 'admin-ajax.php' ); ?>?action=revslider_show_image&amp;img=<?php echo $slide_thumb; ?>&amp;w=100&amp;h=70&amp;t=exact'); background-position:center center; background-size:cover;"></div>
							<?php
						}elseif($slide_thumb !== ''){
							?>
							<div style="width:100px;height:70px;background:url('<?php echo $slide_thumb; ?>'); background-position:center center; background-size:cover;"></div>
							<?php
						}
						?></div>
						<input type="hidden" id="slide_thumb" name="slide_thumb" value="<?php echo $slide_thumb; ?>">
						<span style="clear:both;display:block"></span>
						<input type="button" id="slide_thumb_button" style="width:110px !important; display:inline-block;" class="button-image-select button-primary revblue" value="Choose Image" original-title="">
						<input type="button" id="slide_thumb_button_remove" style="margin-right:20px !important; width:85px !important; display:inline-block;" class="button-image-remove button-primary revred"  value="Remove" original-title="">
						<span class="description"><?php _e("Slide Thumbnail. If not set - it will be taken from the slide image.",REVSLIDER_TEXTDOMAIN); ?></span>
					</div>
				</div>
				<?php $thumb_dimension = RevSliderFunctions::getVal($slideParams, 'thumb_dimension', 'slider'); ?>
				<p>
					<span style="display:inline-block; vertical-align: top;">
						<label><?php _e("Thumbnail Dimensions:",REVSLIDER_TEXTDOMAIN); ?></label>
					</span>
					<select name="thumb_dimension">
						<option value="slider" <?php selected($thumb_dimension, 'slider'); ?>><?php _e('From Slider Settings', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="orig" <?php selected($thumb_dimension, 'orig'); ?>><?php _e('Original Size', REVSLIDER_TEXTDOMAIN); ?></option>
					</select>
					<span class="description"><?php _e("Width and height of thumbnails can be changed in the Slider Settings -> Navigation -> Thumbs tab.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>
				<!-- SLIDE VISIBLE FROM -->
				<p style="display:none">
					<?php $save_performance = RevSliderFunctions::getVal($slideParams, 'save_performance','off'); ?>
					<label><?php _e("Save Performance:",REVSLIDER_TEXTDOMAIN); ?></label>
					<span style="display:inline-block; width:200px; margin-right:20px;">
						<input type="checkbox" class="tp-moderncheckbox withlabel" id="save_performance" name="save_performance" data-unchecked="off" <?php checked( $save_performance, "on" ); ?>>
					</span>
					<span class="description"><?php _e("Slide End Transition will first start when last Layer has been removed.",REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

			</div>

			<!-- SLIDE ANIMATIONS -->
			<div id="slide-animation-settings-content" style="display:none">

				<!-- ANIMATION / TRANSITION -->
				<div id="slide_transition_row">
					<?php
						$slide_transition = RevSliderFunctions::getVal($slideParams, 'slide_transition','fade');
						if(!is_array($slide_transition))
							$slide_transition = explode(',', $slide_transition);
						
						if(!is_array($slide_transition)) $slide_transition = array($slide_transition);
						$transitions = $operations->getArrTransition();
					?>
					<?php $slot_amount = (array) RevSliderFunctions::getVal($slideParams, 'slot_amount','default'); ?>
					<?php $transition_rotation = (array) RevSliderFunctions::getVal($slideParams, 'transition_rotation','0'); ?>
					<?php $transition_duration = (array) RevSliderFunctions::getVal($slideParams, 'transition_duration','default'); ?>
					<?php $transition_ease_in = (array) RevSliderFunctions::getVal($slideParams, 'transition_ease_in','default'); ?>
					<?php $transition_ease_out = (array) RevSliderFunctions::getVal($slideParams, 'transition_ease_out','default'); ?>
					<script type="text/javascript">
						var choosen_slide_transition = [];
						<?php
						$tr_count = count($slide_transition);
						foreach($slide_transition as $tr){
							echo 'choosen_slide_transition.push("'.$tr.'");'."\n";
						}
						?>
						var transition_settings = {
							'slot': [],
							'rotation': [],
							'duration': [],
							'ease_in': [],
							'ease_out': []
							};
						<?php
						foreach($slot_amount as $sa){
							echo 'transition_settings["slot"].push("'.$sa.'");'."\n";
						}
						$sac = count($slot_amount);
						if($sac < $tr_count){
							while($sac < $tr_count){
								$sac++;
								echo 'transition_settings["slot"].push("'.$slot_amount[0].'");'."\n";
							}
						}
						
						foreach($transition_rotation as $sa){
							echo 'transition_settings["rotation"].push("'.$sa.'");'."\n";
						}
						$sac = count($transition_rotation);
						if($sac < $tr_count){
							while($sac < $tr_count){
								$sac++;
								echo 'transition_settings["rotation"].push("'.$transition_rotation[0].'");'."\n";
							}
						}
						
						foreach($transition_duration as $sa){
							echo 'transition_settings["duration"].push("'.$sa.'");'."\n";
						}
						$sac = count($transition_duration);
						if($sac < $tr_count){
							while($sac < $tr_count){
								$sac++;
								echo 'transition_settings["duration"].push("'.$transition_duration[0].'");'."\n";
							}
						}
						
						foreach($transition_ease_in as $sa){
							echo 'transition_settings["ease_in"].push("'.$sa.'");'."\n";
						}
						$sac = count($transition_ease_in);
						if($sac < $tr_count){
							while($sac < $tr_count){
								$sac++;
								echo 'transition_settings["ease_in"].push("'.$transition_ease_in[0].'");'."\n";
							}
						}
						
						foreach($transition_ease_out as $sa){
							echo 'transition_settings["ease_out"].push("'.$sa.'");'."\n";
						}
						$sac = count($transition_ease_out);
						if($sac < $tr_count){
							while($sac < $tr_count){
								$sac++;
								echo 'transition_settings["ease_out"].push("'.$transition_ease_out[0].'");'."\n";
							}
						}
						
						?>
					</script>
					<div id="slide_transition"  multiple="" size="1" style="z-index: 100;">
						<?php
						if(!empty($transitions) && is_array($transitions)){
							$counter = 0;
							$optgroupexist = false;
							$transmenu = '<ul class="slide-trans-menu">';
							$lastclass = '';
							$transchecks ='';
							$listoftrans = '<div class="slide-trans-lists">';
							
							foreach($transitions as $tran_handle => $tran_name){

								$sel = (in_array($tran_handle, $slide_transition)) ? ' checked="checked"' : '';

								if (strpos($tran_handle, 'notselectable') !== false) {
									$listoftrans = $listoftrans.$transchecks;
									$lastclass = "slide-trans-".$tran_handle;
									$transmenu = $transmenu.'<li class="slide-trans-menu-element" data-reference="'.$lastclass.'">'.$tran_name.'</li>';
									$transchecks ='';		

								}
								else
									$transchecks = $transchecks.'<div class="slide-trans-checkelement '.$lastclass.'"><input name="slide_transition[]" type="checkbox" data-useval="true" value="'.$tran_handle.'"'.$sel.'>'.$tran_name.'</div>';							
							}

							$listoftrans = $listoftrans.$transchecks;
							$transmenu = $transmenu."</ul>";
							$listoftrans = $listoftrans."</div>";
							echo $transmenu;
							echo $listoftrans;							
						}
						?>
						
						<div class="slide-trans-example">
							<div class="slide-trans-example-inner">
								<div class="oldslotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:1">
									<div class="tp-bgimg defaultimg"></div>
								</div>
								<div class="slotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:1">
									<div class="tp-bgimg defaultimg"></div>
								</div>
							</div>
						</div>
						<div class="slide-trans-cur-selected">
							<p><?php _e("Used Transitions (Order in Loops)",REVSLIDER_TEXTDOMAIN); ?></p>
							<ul class="slide-trans-cur-ul">
							</ul>
						</div>
						<div class="slide-trans-cur-selected-settings">
							<!-- SLOT AMOUNT -->
							
							<label><?php _e("Slot / Box Amount:",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" class="small-text input-deepselects" id="slot_amount" name="slot_amount" value="<?php echo $slot_amount[0]; ?>" data-selects="1||Random||Custom||Default" data-svalues ="1||random||3||default" data-icons="thumbs-up||shuffle||wrench||key">
							<span class="tp-clearfix"></span>
							<span class="description"><?php _e("# of slots/boxes the slide is divided into.",REVSLIDER_TEXTDOMAIN); ?></span>					
							<span class="tp-clearfix"></span>
							
							<!-- ROTATION -->
							
							<label><?php _e("Slot Rotation:",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" class="small-text input-deepselects" id="transition_rotation" name="transition_rotation" value="<?php echo $transition_rotation[0]; ?>" data-selects="0||Random||Custom||Default||45||90||180||270||360" data-svalues ="0||random||-75||default||45||90||180||270||360" data-icons="thumbs-up||shuffle||wrench||key||star-empty||star-empty||star-empty||star-empty||star-empty">
							<span class="tp-clearfix"></span>
							<span class="description"><?php _e("Start Rotation of Transition (deg).",REVSLIDER_TEXTDOMAIN); ?></span>
							<span class="tp-clearfix"></span>

							<!-- DURATION -->
							
							<label><?php _e("Animation Duration:",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" class="small-text input-deepselects" id="transition_duration" name="transition_duration" value="<?php echo $transition_duration[0]; ?>" data-selects="300||Random||Custom||Default" data-svalues ="500||random||650||default" data-icons="thumbs-up||shuffle||wrench||key">
							<span class="tp-clearfix"></span>
							<span class="description"><?php _e("The duration of the transition.",REVSLIDER_TEXTDOMAIN); ?></span>
							<span class="tp-clearfix"></span>

							<!-- IN EASE -->
							
							<label><?php _e("Easing In:",REVSLIDER_TEXTDOMAIN); ?></label>
							<select name="transition_ease_in">
									<option value="default">Default</option>
									<option value="Linear.easeNone">Linear.easeNone</option>
									<option value="Power0.easeIn">Power0.easeIn  (linear)</option>
									<option value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
									<option value="Power0.easeOut">Power0.easeOut  (linear)</option>
									<option value="Power1.easeIn">Power1.easeIn</option>
									<option value="Power1.easeInOut">Power1.easeInOut</option>
									<option value="Power1.easeOut">Power1.easeOut</option>
									<option value="Power2.easeIn">Power2.easeIn</option>
									<option value="Power2.easeInOut">Power2.easeInOut</option>
									<option value="Power2.easeOut">Power2.easeOut</option>
									<option value="Power3.easeIn">Power3.easeIn</option>
									<option value="Power3.easeInOut">Power3.easeInOut</option>
									<option value="Power3.easeOut">Power3.easeOut</option>
									<option value="Power4.easeIn">Power4.easeIn</option>
									<option value="Power4.easeInOut">Power4.easeInOut</option>
									<option value="Power4.easeOut">Power4.easeOut</option>
									<option value="Back.easeIn">Back.easeIn</option>
									<option value="Back.easeInOut">Back.easeInOut</option>
									<option value="Back.easeOut">Back.easeOut</option>
									<option value="Bounce.easeIn">Bounce.easeIn</option>
									<option value="Bounce.easeInOut">Bounce.easeInOut</option>
									<option value="Bounce.easeOut">Bounce.easeOut</option>
									<option value="Circ.easeIn">Circ.easeIn</option>
									<option value="Circ.easeInOut">Circ.easeInOut</option>
									<option value="Circ.easeOut">Circ.easeOut</option>
									<option value="Elastic.easeIn">Elastic.easeIn</option>
									<option value="Elastic.easeInOut">Elastic.easeInOut</option>
									<option value="Elastic.easeOut">Elastic.easeOut</option>
									<option value="Expo.easeIn">Expo.easeIn</option>
									<option value="Expo.easeInOut">Expo.easeInOut</option>
									<option value="Expo.easeOut">Expo.easeOut</option>
									<option value="Sine.easeIn">Sine.easeIn</option>
									<option value="Sine.easeInOut">Sine.easeInOut</option>
									<option value="Sine.easeOut">Sine.easeOut</option>
									<option value="SlowMo.ease">SlowMo.ease</option>
							</select>
							<span class="tp-clearfix"></span>
							<span class="description"><?php _e("The easing of Appearing transition.",REVSLIDER_TEXTDOMAIN); ?></span>
							<span class="tp-clearfix"></span>

							<!-- OUT EASE -->
							
							<label><?php _e("Easing Out:",REVSLIDER_TEXTDOMAIN); ?></label>
							<select name="transition_ease_out">
									<option value="default">Default</option>
									<option value="Linear.easeNone">Linear.easeNone</option>
									<option value="Power0.easeIn">Power0.easeIn  (linear)</option>
									<option value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
									<option value="Power0.easeOut">Power0.easeOut  (linear)</option>
									<option value="Power1.easeIn">Power1.easeIn</option>
									<option value="Power1.easeInOut">Power1.easeInOut</option>
									<option value="Power1.easeOut">Power1.easeOut</option>
									<option value="Power2.easeIn">Power2.easeIn</option>
									<option value="Power2.easeInOut">Power2.easeInOut</option>
									<option value="Power2.easeOut">Power2.easeOut</option>
									<option value="Power3.easeIn">Power3.easeIn</option>
									<option value="Power3.easeInOut">Power3.easeInOut</option>
									<option value="Power3.easeOut">Power3.easeOut</option>
									<option value="Power4.easeIn">Power4.easeIn</option>
									<option value="Power4.easeInOut">Power4.easeInOut</option>
									<option value="Power4.easeOut">Power4.easeOut</option>
									<option value="Back.easeIn">Back.easeIn</option>
									<option value="Back.easeInOut">Back.easeInOut</option>
									<option value="Back.easeOut">Back.easeOut</option>
									<option value="Bounce.easeIn">Bounce.easeIn</option>
									<option value="Bounce.easeInOut">Bounce.easeInOut</option>
									<option value="Bounce.easeOut">Bounce.easeOut</option>
									<option value="Circ.easeIn">Circ.easeIn</option>
									<option value="Circ.easeInOut">Circ.easeInOut</option>
									<option value="Circ.easeOut">Circ.easeOut</option>
									<option value="Elastic.easeIn">Elastic.easeIn</option>
									<option value="Elastic.easeInOut">Elastic.easeInOut</option>
									<option value="Elastic.easeOut">Elastic.easeOut</option>
									<option value="Expo.easeIn">Expo.easeIn</option>
									<option value="Expo.easeInOut">Expo.easeInOut</option>
									<option value="Expo.easeOut">Expo.easeOut</option>
									<option value="Sine.easeIn">Sine.easeIn</option>
									<option value="Sine.easeInOut">Sine.easeInOut</option>
									<option value="Sine.easeOut">Sine.easeOut</option>
									<option value="SlowMo.ease">SlowMo.ease</option>
							</select>
							<span class="tp-clearfix"></span>
							<span class="description"><?php _e("The easing of Disappearing transition.",REVSLIDER_TEXTDOMAIN); ?></span>
							
						</div>

					</div>
					
				</div>

				
			</div>
			
			<!-- SLIDE BASIC INFORMATION -->
			<div id="slide-info-settings-content" style="display:none">
				<p>
					<?php
					for($i=1;$i<=10;$i++){
						?>
						<p>
							<?php _e('Parameter', REVSLIDER_TEXTDOMAIN); echo ' '.$i; ?> <input type="text" name="params_<?php echo $i; ?>" value="<?php echo stripslashes(esc_attr(RevSliderFunctions::getVal($slideParams, 'params_'.$i,''))); ?>">
							<?php _e('Max. Chars', REVSLIDER_TEXTDOMAIN); ?> <input type="text" style="width: 50px; min-width: 50px;" name="params_<?php echo $i; ?>_chars" value="<?php echo esc_attr(RevSliderFunctions::getVal($slideParams, 'params_'.$i.'_chars',10, RevSlider::FORCE_NUMERIC)); ?>">
							<?php if($slider_type !== 'gallery'){ ?><i class="eg-icon-pencil rs-param-meta-open" data-curid="<?php echo $i; ?>"></i><?php } ?>
						</p>
						<?php
					}
					?>
				</p>
				<!-- BASIC DESCRIPTION -->
				<p>
					<?php $slide_description = stripslashes(RevSliderFunctions::getVal($slideParams, 'slide_description', '')); ?>
					<label><?php _e("Description of Slider:",REVSLIDER_TEXTDOMAIN); ?></label>

					<textarea name="slide_description" style="height: 425px; width: 100%"><?php echo $slide_description; ?></textarea>
					<span class="description"><?php _e('Define a description here to show at the navigation if enabled in Slider Settings',REVSLIDER_TEXTDOMAIN); ?></span>
				</p>
			</div>

			<!-- SLIDE SEO INFORMATION -->
			<div id="slide-seo-settings-content" style="display:none">
				<!-- CLASS -->
				<p>
					<?php $class_attr = RevSliderFunctions::getVal($slideParams, 'class_attr',''); ?>
					<label><?php _e("Class:",REVSLIDER_TEXTDOMAIN); ?></label>
					<input type="text" class="" id="class_attr" name="class_attr" value="<?php echo $class_attr; ?>">
					<span class="description"><?php _e('Adds a unique class to the li of the Slide like class="rev_special_class" (add only the classnames, seperated by space)',REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- ID -->
				<p>
					<?php $id_attr = RevSliderFunctions::getVal($slideParams, 'id_attr',''); ?>
					<label><?php _e("ID:",REVSLIDER_TEXTDOMAIN); ?></label>
					<input type="text" class="" id="id_attr" name="id_attr" value="<?php echo $id_attr; ?>">
					<span class="description"><?php _e('Adds a unique ID to the li of the Slide like id="rev_special_id" (add only the id)',REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- CUSTOM FIELDS -->
				<p>
					<?php $data_attr = stripslashes(RevSliderFunctions::getVal($slideParams, 'data_attr','')); ?>
					<label><?php _e("Custom Fields:",REVSLIDER_TEXTDOMAIN); ?></label>
					<textarea id="data_attr" name="data_attr"><?php echo $data_attr; ?></textarea>
					<span class="description"><?php _e('Add as many attributes as you wish here. (i.e.: data-layer="firstlayer" data-custom="somevalue").',REVSLIDER_TEXTDOMAIN); ?></span>
				</p>

				<!-- Enable Link -->
				<p>
					<?php $enable_link = RevSliderFunctions::getVal($slideParams, 'enable_link','false'); ?>
					<label><?php _e("Enable Link:",REVSLIDER_TEXTDOMAIN); ?></label>
					<select id="enable_link" name="enable_link">
						<option value="true"<?php selected($enable_link, 'true'); ?>><?php _e("Enable",REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="false"<?php selected($enable_link, 'false'); ?>><?php _e("Disable",REVSLIDER_TEXTDOMAIN); ?></option>
					</select>
					<span class="description"><?php _e('Link the Full Slide to an URL or Action.',REVSLIDER_TEXTDOMAIN); ?></span>
				</p>
				
				<div class="rs-slide-link-setting-wrapper">
					<!-- Link Type -->
					<p>
						<?php $enable_link = RevSliderFunctions::getVal($slideParams, 'link_type','regular'); ?>
						<label><?php _e("Link Type:",REVSLIDER_TEXTDOMAIN); ?></label>
						<span style="display:inline-block; width:200px; margin-right:20px;">
							<input type="radio" id="link_type_1" value="regular" name="link_type"<?php checked($enable_link, 'regular'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php _e('Regular',REVSLIDER_TEXTDOMAIN); ?></span>
							<input type="radio" id="link_type_2" value="slide" name="link_type"<?php checked($enable_link, 'slide'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php _e('To Slide',REVSLIDER_TEXTDOMAIN); ?></span>
						</span>
						<span class="description"><?php _e('Regular - Link to URL,  To Slide - Call a Slide Action',REVSLIDER_TEXTDOMAIN); ?></span>
					</p>

					<div class="rs-regular-link-setting-wrap">
						<!-- SLIDE LINK -->
						<p>
							<?php $val_link = RevSliderFunctions::getVal($slideParams, 'link',''); ?>
							<label><?php _e("Slide Link:",REVSLIDER_TEXTDOMAIN); ?></label>
							<input type="text" id="rev_link" name="link" value="<?php echo $val_link; ?>">
							<span class="description"><?php _e('A link on the whole slide pic (use {{link}} or {{meta:somemegatag}} in template sliders to link to a post or some other meta)',REVSLIDER_TEXTDOMAIN); ?></span>
						</p>
					
						<!-- LINK TARGET -->
						<p>
							<?php $link_open_in = RevSliderFunctions::getVal($slideParams, 'link_open_in','same'); ?>
							<label><?php _e("Link Target:",REVSLIDER_TEXTDOMAIN); ?></label>
							<select id="link_open_in" name="link_open_in">
								<option value="same"<?php selected($link_open_in, 'same'); ?>><?php _e('Same Window',REVSLIDER_TEXTDOMAIN); ?></option>
								<option value="new"<?php selected($link_open_in, 'new'); ?>><?php _e('New Window',REVSLIDER_TEXTDOMAIN); ?></option>
							</select>
							<span class="description"><?php _e('The target of the slide link.',REVSLIDER_TEXTDOMAIN); ?></span>
						</p>
					</div>
					<!-- LINK TO SLIDE -->
					<p class="rs-slide-to-slide">
						<?php $slide_link = RevSliderFunctions::getVal($slideParams, 'slide_link','nothing');
						//num_slide_link
						$arrSlideLink = array();
						$arrSlideLink["nothing"] = __("-- Not Chosen --",REVSLIDER_TEXTDOMAIN);
						$arrSlideLink["next"] = __("-- Next Slide --",REVSLIDER_TEXTDOMAIN);
						$arrSlideLink["prev"] = __("-- Previous Slide --",REVSLIDER_TEXTDOMAIN);

						$arrSlideLinkLayers = $arrSlideLink;
						$arrSlideLinkLayers["scroll_under"] = __("-- Scroll Below Slider --",REVSLIDER_TEXTDOMAIN);
						$arrSlideNames = array();
						if(isset($slider) && $slider->isInited())
							$arrSlideNames = $slider->getArrSlideNames();
						if(!empty($arrSlideNames) && is_array($arrSlideNames)){
							foreach($arrSlideNames as $slideNameID=>$arr){
								$slideName = $arr["title"];
								$arrSlideLink[$slideNameID] = $slideName;
								$arrSlideLinkLayers[$slideNameID] = $slideName;
							}
						}
						?>
						<label><?php _e("Link To Slide:",REVSLIDER_TEXTDOMAIN); ?></label>
						<select id="slide_link" name="slide_link">
							<?php
							if(!empty($arrSlideLinkLayers) && is_array($arrSlideLinkLayers)){
								foreach($arrSlideLinkLayers as $link_handle => $link_name){
									$sel = ($link_handle == $slide_link) ? ' selected="selected"' : '';
									echo '<option value="'.$link_handle.'"'.$sel.'>'.$link_name.'</option>';
								}
							}
							?>
						</select>
						<span class="description"><?php _e('Call Slide Action',REVSLIDER_TEXTDOMAIN); ?></span>
					</p>
					<!-- Link POSITION -->
					<p>
						<?php $link_pos = RevSliderFunctions::getVal($slideParams, 'link_pos','front'); ?>
						<label><?php _e("Link Sensibility:",REVSLIDER_TEXTDOMAIN); ?></label>
						<span style="display:inline-block; width:200px; margin-right:20px;">
							<input type="radio" id="link_pos_1" value="front" name="link_pos"<?php checked($link_pos, 'front'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php _e('Front',REVSLIDER_TEXTDOMAIN); ?></span>
							<input type="radio" id="link_pos_2" value="back" name="link_pos"<?php checked($link_pos, 'back'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php _e('Back',REVSLIDER_TEXTDOMAIN); ?></span>
						</span>
						<span class="description"><?php _e('The z-index position of the link related to layers',REVSLIDER_TEXTDOMAIN); ?></span>
					</p>
				</div>
			</div>

		</form>

	</div>
</div>
<script type="text/javascript">
	var rs_plugin_url = '<?php echo RS_PLUGIN_URL; ?>';
	
	jQuery('document').ready(function() {
		
		jQuery('#enable_link').change(function(){
			if(jQuery(this).val() == 'true'){
				jQuery('.rs-slide-link-setting-wrapper').show();
			}else{
				jQuery('.rs-slide-link-setting-wrapper').hide();
			}
		});
		jQuery('#enable_link option:selected').change();
		
		jQuery('input[name="link_type"]').change(function(){
			if(jQuery(this).val() == 'regular'){
				jQuery('.rs-regular-link-setting-wrap').show();
				jQuery('.rs-slide-to-slide').hide();
			}else{
				jQuery('.rs-regular-link-setting-wrap').hide();
				jQuery('.rs-slide-to-slide').show();
			}
		});
		jQuery('input[name="link_type"]:checked').change();
		
	});
</script>