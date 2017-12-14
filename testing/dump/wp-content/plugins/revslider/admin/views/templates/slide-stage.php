<?php
if( !defined( 'ABSPATH') ) exit();

$startanims = $operations->getArrAnimations();
?>

<div style="width:100%;height:20px"></div>
<div class="editor_buttons_wrapper  postbox unite-postbox" style="max-width:100% !important; min-width:1140px !important">
	<div class="box-closed tp-accordion" style="border-bottom:5px solid #ddd;">
		<ul class="rs-layer-settings-tabs">
			<li id="rs-style-tab-button" data-content="#rs-style-content-wrapper" class="selected"><i style="height:45px" class="rs-mini-layer-icon rs-icon-droplet rs-toolbar-icon"></i>
				<span class="rs-anim-tab-txt"><?php _e("Style",REVSLIDER_TEXTDOMAIN); ?></span>
				<span id="style-morestyle" class="tipsy_enabled_top" title="<?php _e("Advanced Style on/off",REVSLIDER_TEXTDOMAIN); ?>">
					<i class="rs-icon-morestyles-dark"></i>
					<i class="rs-icon-morestyles-light"></i>
				</span>
			</li>
			<li id="rs-animation-tab-button" data-content="#rs-animation-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-2 rs-toolbar-icon"></i>
				<span class="rs-anim-tab-txt"><?php _e("Animation",REVSLIDER_TEXTDOMAIN); ?></span>
				<span id="layeranimation-playpause" class=" tipsy_enabled_top" title="<?php _e("Play/Pause Single Layer Animation",REVSLIDER_TEXTDOMAIN); ?>">
					<i class="eg-icon-play"></i>
					<i class="eg-icon-pause"></i>
				</span>
			</li>
			<li id="rs-loopanimation-tab-button" data-content="#rs-loop-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-3 rs-toolbar-icon"></i>
				<span class="rs-anim-tab-txt"><?php _e("Loop Animation",REVSLIDER_TEXTDOMAIN); ?></span>
				<span id="loopanimation-playpause" class="tipsy_enabled_top" title="<?php _e("Play/Pause Layer Loop Animation",REVSLIDER_TEXTDOMAIN); ?>">
					<i class="eg-icon-play"></i>
					<i class="eg-icon-pause"></i>
				</span>
			</li>
			<li data-content="#rs-visibility-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-visibility rs-toolbar-icon"></i><?php _e("Visibility",REVSLIDER_TEXTDOMAIN); ?></li>
			<li data-content="#rs-behavior-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon eg-icon-resize-full-2 rs-toolbar-icon "></i><?php _e("Behavior",REVSLIDER_TEXTDOMAIN); ?></li>
			<li data-content="#rs-action-content-wrapper"><i style="height:45px; font-size:16px" class="rs-mini-layer-icon eg-icon-link rs-toolbar-icon"></i><?php _e("Actions",REVSLIDER_TEXTDOMAIN); ?></li>
			
			<li data-content="#rs-attribute-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-edit-basic rs-toolbar-icon"></i><?php _e("Attributes",REVSLIDER_TEXTDOMAIN); ?></li>
			<?php if($slide->isStaticSlide()){ ?>
			<li data-content="#rs-static-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon eg-icon-dribbble-1 rs-toolbar-icon"></i><?php _e("Static Layers",REVSLIDER_TEXTDOMAIN); ?></li>
			<?php } ?>
			<li data-content="#rs-imageadvanced-content-wrapper"><i style="height:45px; font-size:16px;" class="rs-mini-layer-icon eg-icon-picture-1 rs-toolbar-icon"></i><?php _e("Performance",REVSLIDER_TEXTDOMAIN); ?></li>
		</ul>

		<div style="clear:both"></div>



	</div>
	<div style="clear:both"></div>

	<!-- THE AMAZING TOOLBAR ABOVE THE SLIDE EDITOR -->
	<form name="form_layers" class="form_layers notselected">

		<!-- LAYER STYLING -->
		<div class="layer-settings-toolbar" id="rs-style-content-wrapper" style="">
			<?php //add global styles editor ?>
			<div id="css_static_editor_wrap" title="<?php _e("Global Style Editor",REVSLIDER_TEXTDOMAIN) ?>" style="display:none;">
				<div id="css-static-accordion">
					<h3><?php _e("Dynamic Styles (Not Editable):",REVSLIDER_TEXTDOMAIN)?></h3>
					<div class="css_editor_novice_wrap">
						<textarea id="textarea_show_dynamic_styles" rows="20" cols="81"></textarea>
					</div>
					<h3 class="notopradius" style="margin-top:20px"><?php _e("Static Styles:",REVSLIDER_TEXTDOMAIN)?></h3>
					<div>
						<textarea id="textarea_edit_static" rows="20" cols="81"></textarea>
					</div>
				</div>
			</div>
			<div id="dialog-change-css-static" title="<?php _e("Save Static Styles",REVSLIDER_TEXTDOMAIN) ?>" style="display:none;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 50px 0;"></span><?php _e('Overwrite current static styles?',REVSLIDER_TEXTDOMAIN)?></p>
			</div>
			
			<div>

				<!-- FONT TEMPLATE -->
				<span class="rs-layer-toolbar-box" style="padding-right:15px;">
					<i class="rtlmr0 rs-mini-layer-icon rs-icon-fonttemplate rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Caption Style",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
					<input type="text"  style="width:150px; padding-right:30px;" class="textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Style Template",REVSLIDER_TEXTDOMAIN); ?>"  id="layer_caption" name="layer_caption" value="-" />
					<span id="layer_captions_down" ><i class="eg-icon-arrow-combo"></i></span>
					<!--<a href="javascript:void(0)" id='button_edit_css' class='revnewgray layer-toolbar-button  tipsy_enabled_top' title="<?php _e("More Style Settings",REVSLIDER_TEXTDOMAIN); ?>"><i class="revicon-cog"></i></a>-->
					<!--<a href="javascript:void(0)" id='button_css_reset' class='revnewgray layer-toolbar-button tipsy_enabled_top' title="<?php _e("Reset Style Template",REVSLIDER_TEXTDOMAIN); ?>"><i class="revicon-ccw"></i></a>-->

					<span id="css-template-handling-dd" class="clicktoshowmoresub">
						<span id="css-template-handling-dd-inner" class="clicktoshowmoresub_inner">
							<span style="background:#ddd !important; padding-left:20px;margin-bottom:5px" class="css-template-handling-menupoint"><span><?php _e("Style Options",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="save-current-css"   class="save-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php _e("Save",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="save-as-current-css"   class="save-as-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php _e("Save As",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="rename-current-css" class="rename-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-chooser-1"></i><span><?php _e("Rename",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="reset-current-css"  class="reset-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-2drotation"></i><span><?php _e("Reset",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="delete-current-css" class="delete-current-css css-template-handling-menupoint"><i style="background-size:10px 14px;" class="rs-mini-layer-icon rs-toolbar-icon rs-icon-delete"></i><span><?php _e("Delete",REVSLIDER_TEXTDOMAIN); ?></span></span>
						</span>
					</span>
				</span>



				
				<span class="rs-layer-toolbar-box">
					<!-- FONT SIZE -->
					<i class="rs-mini-layer-icon rs-icon-fontsize rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Size (px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:6px" ></i>
					<input type="text"  data-suffix="px" class="rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Size",REVSLIDER_TEXTDOMAIN); ?>" style="width:61px" id="layer_font_size_s" name="font_size_static" value="20px" />
					<span class="rs-layer-toolbar-space"></span>


					<!-- LINE HEIGHT -->
					<i class="rs-mini-layer-icon rs-icon-lineheight rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Line Height (px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:11px" ></i>
					<input type="text" data-suffix="px" class="rs-layer-input-field tipsy_enabled_top" title="<?php _e("Line Height",REVSLIDER_TEXTDOMAIN); ?>" style="width:61px" id="layer_line_height_s" name="line_height_static" value="22px" />
					<span class="rs-layer-toolbar-space" style="margin-right:6px" ></span>
				</span>


				<!-- WRAP -->
				<span class="rs-layer-toolbar-box tipsy_enabled_top" style="display: none" title="<?php _e("White Space",REVSLIDER_TEXTDOMAIN); ?>">
					<i class="rs-mini-layer-icon rs-icon-wrap rs-toolbar-icon"></i>
					<select class="rs-layer-input-field" style="width:95px" id="layer_whitespace" name="layer_whitespace">
						<option value="normal">Normal</option>
						<option value="pre">Pre</option>
						<option value="nowrap" selected="selected">No-wrap</option>
						<option value="pre-wrap">Pre-Wrap</option>
						<option value="pre-line">Pre-Line</option>
					</select>
				</span>

				<!-- ALIGN -->
				<span class="rs-layer-toolbar-box tipsy_enabled_top" style="padding-right:18px;" id="rs-align-wrapper">
					<i class="rs-mini-layer-icon eg-icon-arrow-combo rs-toolbar-icon" style="margin-right:3px"></i>
					<a href="javascript:void(0)" id='align_left' data-hor="left"  class='revnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php _e("Layer Align Left",REVSLIDER_TEXTDOMAIN); ?>"><i class="rs-mini-layer-icon rs-icon-alignleft rs-toolbar-icon"></i></a>
					<a href="javascript:void(0)" id='align_center_h' data-hor="center" class='revnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php _e("Layer Align Center",REVSLIDER_TEXTDOMAIN); ?>"><i class="rs-mini-layer-icon rs-icon-aligncenterh rs-toolbar-icon"></i></a>
					<a href="javascript:void(0)" id='align_right' data-hor="right" class='revnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php _e("Layer Align Right",REVSLIDER_TEXTDOMAIN); ?>"><i class="rs-mini-layer-icon rs-icon-alignright rs-toolbar-icon"></i></a>									
					<input type="text"  class='text-sidebar' style="display:none" id="layer_align_hor" name="layer_align_hor" value="left" />				

				</span>

				<span class="rs-layer-toolbar-box">
					<!-- POSITION X -->
					<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Horizontal Offset from Aligned Position (px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
					<input type="text" data-suffix="px" class="text-sidebar setting-disabled rs-layer-input-field tipsy_enabled_top" title="<?php _e("Horizontal Offset from Aligned Position (px)",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" id="layer_left" name="layer_left" value="" disabled="disabled">
					<span class="rs-layer-toolbar-space" style="margin-right:10px"></span>

					<!-- POSITION Y -->
					<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Vertical Offset from Aligned Position (px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
					<input type="text" data-suffix="px" class="text-sidebar setting-disabled rs-layer-input-field tipsy_enabled_top" title="<?php _e("Vertical Offset from Aligned Position (px)",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" id="layer_top" name="layer_top" value="" disabled="disabled">
					<span class="rs-layer-toolbar-space" style="margin-right:10px"></span>

				</span>
				
			</div>


			<div style="border-top:1px solid #ddd;">

				<!-- FONT FAMILY-->
				<span class="rs-layer-toolbar-box" style="padding-right:0px;">
					<i class="rs-mini-layer-icon rs-icon-fontfamily rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Family",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
					<input type="text" class="rs-staticcustomstylechange text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Family",REVSLIDER_TEXTDOMAIN); ?>" style="width:185px" type="text" name="css_font-family" value="" autocomplete="off"> <?php /*  id="font_family" */ ?>
					<span class="rs-layer-toolbar-space" style="margin-right:9px"></span>
				</span>



				<!-- FONT DIRECT MANAGEMENT -->
				<span class="rs-layer-toolbar-box">

					<!-- FONT WEIGHT -->
					<i class="rs-mini-layer-icon rs-icon-fontweight rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Weight",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select class="rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Weight",REVSLIDER_TEXTDOMAIN); ?>" style="width:61px" id="layer_font_weight_s" name="font_weight_static">
						<option value="100">100</option>
						<option value="200">200</option>
						<option value="300">300</option>
						<option value="400">400</option>
						<option value="500">500</option>
						<option value="600">600</option>
						<option value="700">700</option>
						<option value="800">800</option>
						<option value="900">900</option>
					</select>
					<span class="rs-layer-toolbar-space"></span>

					<!-- COLOR -->
					<i class="rs-mini-layer-icon rs-icon-color rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Color",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input type="text" class="my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Color",REVSLIDER_TEXTDOMAIN); ?>"  id="layer_color_s" name="color_static" value="#ffffff" />
				</span>

				<!-- ALIGN -->
				<span class="rs-layer-toolbar-box tipsy_enabled_top" style="padding-right:18px;" id="rs-align-wrapper-ver">
					<i class="rs-mini-layer-icon eg-icon-arrow-combo rs-toolbar-icon" style="margin-right:3px"></i>															
					<a href="javascript:void(0)" id='align_top' data-ver="top" class='revnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php _e("Layer Align Top",REVSLIDER_TEXTDOMAIN); ?>"><i class="rs-mini-layer-icon rs-icon-aligntop rs-toolbar-icon"></i></a>
					<a href="javascript:void(0)" id='align_center_v' data-ver="middle" class='revnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php _e("Layer Align Middle",REVSLIDER_TEXTDOMAIN); ?>"><i class="rs-mini-layer-icon rs-icon-aligncenterv rs-toolbar-icon"></i></a>
					<a href="javascript:void(0)" id='align_bottom' data-ver="bottom" class='revnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php _e("Layer Align Bottom",REVSLIDER_TEXTDOMAIN); ?>"><i class="rs-mini-layer-icon rs-icon-alignbottom rs-toolbar-icon"></i></a>
					<input type="text"  class='text-sidebar' style="display:none" id="layer_align_vert" name="layer_align_vert" value="top" />
				</span>

				<span class="rs-layer-toolbar-box">
					<!-- WIDTH -->
					<i class="rs-mini-layer-icon rs-icon-maxwidth rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Layer Width (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:3px"></i>
					<input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Layer Width (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="display:none;width:50px" id="layer_max_width" name="layer_max_width" value="auto">
					<input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Layer Width (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" id="layer_scaleX" name="layer_scaleX" value="">
					<input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Video Width (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="display:none;width:50px" id="layer_video_width" name="layer_video_width" value="">
					<span class="rs-layer-toolbar-space" style="margin-right:11px"></span>

					<i class="rs-mini-layer-icon rs-icon-maxheight rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Layer Height (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Layer Height (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="display:none;width:50px" id="layer_max_height" name="layer_max_height" value="auto">
					<input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Layer Height (px) Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" id="layer_scaleY" name="layer_scaleY" value="">
					<input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Video Height (px). Use 'auto' to respect White Space",REVSLIDER_TEXTDOMAIN); ?>" style="display:none;width:50px" id="layer_video_height" name="layer_video_height" value="">
					<span class="rs-layer-toolbar-space" style="margin-right:11px"></span>					

					<span id="layer-covermode-wrapper" class="tipsy_enabled_top" title="<?php _e("Cover Mode",REVSLIDER_TEXTDOMAIN); ?>">
						<i class="rs-mini-layer-icon eg-icon-resize-normal rs-toolbar-icon"></i>
						<select class="rs-layer-input-field"  style="width:75px" id="layer_cover_mode" name="layer_cover_mode">
							<option value="custom"><?php _e('Custom', REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="fullwidth"><?php _e('Full Width', REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="fullheight"><?php _e('Full Height', REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="cover"><?php _e('Stretch', REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="cover-proportional"><?php _e('Cover', REVSLIDER_TEXTDOMAIN); ?></option>
						</select>
					</span>
					<span class="rs-layer-toolbar-space" style="margin-right:11px"></span>
					
					<span id="layer-linebreak-wrapper" class="rs-linebreak-check layer-toolbar-button tipsy_enabled_top" title="<?php _e("Auto Linebreak (on/off - White Space:normal / nowrap).  ",REVSLIDER_TEXTDOMAIN); ?>" style="">
						<i class="rs-mini-layer-icon eg-icon-paragraph rs-toolbar-icon"></i>
						<input type="checkbox" id="layer_auto_line_break" class="inputCheckbox" name="layer_auto_line_break" style="width: 26px;height: 26px;margin: 0px;position: absolute;top: 0px;left: 0px; filter: alpha(opacity=0);-moz-opacity: 0.0;-khtml-opacity: 0.0;opacity: 0.0;">
					</span>

					<span id="layer-prop-wrapper" class="rs-proportion-check layer-toolbar-button tipsy_enabled_top" title="<?php _e("Keep Aspect Ratio (on/off)",REVSLIDER_TEXTDOMAIN); ?>" style="position:relative; display:inline-block;  width:26px; padding:0px; text-align:center; vertical-align: middle">
						<i class="rs-mini-layer-icon eg-icon-link rs-toolbar-icon"></i>
						<input type="checkbox" id="layer_proportional_scale" class="inputCheckbox" name="layer_proportional_scale" style="width: 26px;height: 26px;margin: 0px;position: absolute;top: 0px;left: 0px; filter: alpha(opacity=0);-moz-opacity: 0.0;-khtml-opacity: 0.0;opacity: 0.0;">
					</span>

					<a href="javascript:void(0)" id="reset-scale" class="revnewgray layer-toolbar-button  tipsy_enabled_top" title="<?php _e("Reset original size",REVSLIDER_TEXTDOMAIN); ?>"><i class="eg-icon-resize-normal"></i></a>
					

				</span>
			</div>


			<!-- SUB SETTINGS FOR CSS -->
			<div id="style_form_wrapper">
				<div id="extra_style_settings" class="extra_sub_settings_wrapper" >
					<div class="close_extra_settings"></div>
					<div class="inner-settings-wrapper">
						<div id="tp-idle-state-advanced-style" style="float:left; padding-left:20px;">
							
							<ul class="rs-layer-animation-settings-tabs" style="display:inline-block; ">
								<li data-content="#style-sub-font" class="selected"><?php _e("Font",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-background"><?php _e("Background",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-border"><?php _e("Border",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-transfrom" ><?php _e("Transform",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-rotation" ><?php _e("Rotation",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-perspective"><?php _e("Perspective",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-parallax"><?php _e("Parallax",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-sharpc"><?php _e("Corners",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#style-sub-advcss"><?php _e("Advanced CSS",REVSLIDER_TEXTDOMAIN); ?></li>		
								<li data-content="#style-sub-hover"><?php _e("Hover",REVSLIDER_TEXTDOMAIN); ?></li>		
							</ul>
							<div style="width:100%;height:1px;display:block"></div>
							<span id="style-sub-font" class="rs-layer-toolbar-box" style="display:block">

								<!-- FONT OPACITY -->
								<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_font-transparency" value="1">
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

								<!-- ITALIC -->
								<i class="rs-mini-layer-icon rs-icon-italic rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Italic Font",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input type="checkbox" name="css_font-style" class="rs-staticcustomstylechange tipsy_enabled_top tp-moderncheckbox" title="<?php _e("Italic Font On/Off",REVSLIDER_TEXTDOMAIN); ?>">
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

								<!-- DECORATION -->
								<i class="rs-mini-layer-icon rs-icon-decoration rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Decoration",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php _e("Font Decoration",REVSLIDER_TEXTDOMAIN); ?>" style="width:100px" style="cursor:pointer" name="css_text-decoration">
									<option value="none">none</option>
									<option value="underline">underline</option>
									<option value="overline">overline</option>
									<option value="line-through">line-through</option>
								</select>

								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>
								
								<!-- TEXT ALIGN -->
								<i class="rs-mini-layer-icon eg-icon-menu rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Text Align",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php _e("Text Align",REVSLIDER_TEXTDOMAIN); ?>" style="width:100px" style="cursor:pointer" name="css_text-align">
									<option value="left"><?php _e('Left', REVSLIDER_TEXTDOMAIN); ?></option>
									<option value="center"><?php _e('Center', REVSLIDER_TEXTDOMAIN); ?></option>
									<option value="right"><?php _e('Right', REVSLIDER_TEXTDOMAIN); ?></option>
								</select>
								
							</span>


							<span id="style-sub-background" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- BACKGROUND COLOR -->
								<i class="rs-mini-layer-icon rs-icon-bg rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Background Color",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input type="text" class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top my-color-field" title="<?php _e("Background Color",REVSLIDER_TEXTDOMAIN); ?>" style="width:150px" name="css_background-color" value="transparent" />
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

								<!-- BACKGROUND OPACITY -->
								<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Background Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Background Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_background-transparency" value="1">
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>
								
								<!-- PADDING -->
								<i class="rs-mini-layer-icon rs-icon-padding rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Padding",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Top",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_padding[]" value="">
								<input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Right",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_padding[]" value="">
								<input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Bottom",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_padding[]" value="">
								<input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Left",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_padding[]" value="">
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>
							</span>

							<span id="style-sub-border" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- BORDER COLOR -->
								<i class="rs-mini-layer-icon rs-icon-bordercolor rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input type="text" class="rs-staticcustomstylechange my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>"  style="width:150px" name="css_border-color-show" value="transparent" />
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

								<!-- FONT OPACITY -->
								<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_border-transparency" value="1">
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

								<!-- BORDER STYLE -->
								<i class="rs-mini-layer-icon rs-icon-borderstyle rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Style",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<select class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Style",REVSLIDER_TEXTDOMAIN); ?>" style="width:100px cursor:pointer" name="css_border-style">
									<option value="none">none</option>
									<option value="dotted">dotted</option>
									<option value="dashed">dashed</option>
									<option value="solid">solid</option>
									<option value="double">double</option>
								</select>
								<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

								<!-- BORDER WIDTH-->
								<i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_border-width" value="0">
								<span class="rs-layer-toolbar-space" style="margin-right:16px"></span>

								<!-- BORDER RADIUS -->
								<i class="rs-mini-layer-icon rs-icon-borderradius rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Radius (px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<input data-suffix="px" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0"  title="<?php _e("Border Radius Top Left",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
								<input data-suffix="px" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0" title="<?php _e("Border Radius Top Right",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
								<input data-suffix="px" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0" title="<?php _e("Border Radius Bottom Right",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
								<input data-suffix="px" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0" title="<?php _e("Border Radius Bottom Left",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
							</span>

						
							<span id="style-sub-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!--  X  ROTATE -->
								<i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Rotation on X axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<input data-suffix="deg" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Rotation on X axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__xrotate" name="layer__xrotate" value="0">
								<span class="rs-layer-toolbar-space"></span>
								<!--  Y ROTATE -->
								<i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Rotation on Y axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<input data-suffix="deg" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Rotation on Y axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__yrotate" name="layer__yrotate" value="0">
								<span class="rs-layer-toolbar-space"></span>

								<!--  Z ROTATE -->
								<i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Rotation on Z axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<input data-suffix="deg" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Rotation on Z axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="layer_2d_rotation" name="layer_2d_rotation" value="0">
								<span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>
								
								<!-- ORIGIN X -->
								<i class="rs-mini-layer-icon rs-icon-originx rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Horizontal Origin",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<input data-suffix="%" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Horizontal Origin",REVSLIDER_TEXTDOMAIN); ?>" id="layer_2d_origin_x" name="layer_2d_origin_x" value="50">
								<span class="rs-layer-toolbar-space"></span>
								<!-- ORIGIN Y -->
								<i class="rs-mini-layer-icon rs-icon-originy rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Vertical Origin",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<input data-suffix="%" type="text" style="width:55px;" class="textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Vertical Origin",REVSLIDER_TEXTDOMAIN); ?>" id="layer_2d_origin_y" name="layer_2d_origin_y" value="50">
					
							</span>

							<span id="style-sub-transfrom" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- OPACITY -->
								<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Opacity. (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
								<input data-suffix="" data-steps="0.05" data-min="0" data-max="1"  type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Opacity (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__opacity" name="layer__opacity" value="1">
								<span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>
								
								<!-- SCALE X -->
								<i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon tipsy_enabled_top" title="<?php _e("X Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
								<input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("X Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__scalex" name="layer__scalex" value="1">
								<span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>
								
								<!-- SCALE Y -->
								<i  class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Y Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
								<input data-suffix="" data-steps="0.01"  data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Y Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__scaley" name="layer__scaley" value="1">
								<span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>
								
								<!-- SKEW X -->
								<i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon tipsy_enabled_top" title="<?php _e("X Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
								<input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field  tipsy_enabled_top" title="<?php _e("X Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__skewx" name="layer__skewx" value="0">
								<span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>
								
								<!-- SKEW Y -->
								<i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Y Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
								<input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Y Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__skewy" name="layer__skewy" value="0">
					
							</span>

							<!-- ADVANCED CSS -->
							<span id="style-sub-advcss" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<div id="advanced-css-main" class="rev-advanced-css-idle advanced-copy-button"><?php _e("Template",REVSLIDER_TEXTDOMAIN); ?></div>
								<div id="advanced-css-layer" class="rev-advanced-css-idle-layer advanced-copy-button"><?php _e("Layer",REVSLIDER_TEXTDOMAIN); ?></div>
							</span>
							
							<?php $easings = $operations->getArrEasing(); ?>
							
							<!-- CAPTION HOVER CSS -->
							<span id="style-sub-hover" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- Caption Hover on/off -->
								<span><?php _e("Layer Hover",REVSLIDER_TEXTDOMAIN); ?></span>
								<span class="rs-layer-toolbar-space"></span>
								<input name="hover_allow" type="checkbox" class="tp-moderncheckbox" />
								<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>
								
								<!-- ANIMATION START SPEED -->
								<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Hover Animation Speed (in ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<input type="text" style="width:90px; padding-right:10px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Hover Animation Speed (in ms)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_speed" name="hover_speed" value="">
								<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>
								
								
								<!-- HOVER EASE -->
								<i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Hover Animation Easing",REVSLIDER_TEXTDOMAIN); ?>"></i>
								<select class="rs-layer-input-field tipsy_enabled_top" title="<?php _e("Hover Animation Easing",REVSLIDER_TEXTDOMAIN); ?>" style="width:140px"  id="hover_easing" name="hover_easing">
									<?php
									foreach($easings as $ehandle => $ename){
										echo '<option value="'.$ehandle.'">'.$ename.'</option>';
									}
									?>
								</select>
								<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

								<!-- CURSOR -->
								<i class="rs-mini-layer-icon eg-icon-up-hand rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Mouse Cursor",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
								<select class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top" title="<?php _e("Mouse Cursor",REVSLIDER_TEXTDOMAIN); ?>" style="width:100px cursor:pointer" name="css_cursor">
									<option value="auto">auto</option>
									<option value="default">default</option>
									<option value="crosshair">crosshair</option>
									<option value="pointer">pointer</option>
									<option value="move">move</option>
									<option value="text">text</option>
									<option value="wait">wait</option>
									<option value="help">help</option>
								</select>
								
							</span>
							

							<span id="style-sub-perspective" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- PERSPECTIVE -->
								<i class="rs-mini-layer-icon rs-icon-perspective rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Perspective (default 600)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
								<input type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Perspective (default 600)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__pers" name="layer__pers" value="600">
								<span class="rs-layer-toolbar-space"></span>

								<!-- Z - OFFSET -->
								<i class="rs-mini-layer-icon rs-icon-zoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Z Offset (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
								<input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Z Offset (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" id="layer__z" name="layer__z" value="0">
							</span>

							<span id="style-sub-parallax" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- PARALLAX -->
								<!-- PARALLAX LEVEL -->
								<span class="tipsy_enabled_top" title="<?php _e("Parallax Depth Level.",REVSLIDER_TEXTDOMAIN); ?>">
									<i class="rs-mini-layer-icon rs-icon-parallax rs-toolbar-icon"></i>
									<select class="rs-layer-input-field" style="width:149px" id="parallax_level" name="parallax_level">
										<option value="-"><?php _e('No Parallax', REVSLIDER_TEXTDOMAIN); ?></option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
									</select>
								</span>
								<?php 
									if ($use_parallax=="off") {	
										echo '<span class="rs-layer-toolbar-space"></span>';
										echo '<i style="color:#c0392b">';
										_e("Parallax Feature in Slider Settings is deactivated, parallax will be ignored.",REVSLIDER_TEXTDOMAIN); 
										echo '</i>';
									}
									?>
							</span>
							
							<span id="style-sub-sharpc" class="rs-layer-toolbar-box" style="display:none;border:none;">

								<span><?php _e("Sharp Left",REVSLIDER_TEXTDOMAIN); ?></span>
								<span class="rs-layer-toolbar-space"></span>
								<select id="layer_cornerleft" name="layer_cornerleft" class="rs-layer-input-field" style="width:175px">
									<option value="nothing" selected="selected"><?php _e("No Corner",REVSLIDER_TEXTDOMAIN); ?></option>
									<option value="curved"><?php _e("Sharp",REVSLIDER_TEXTDOMAIN); ?></option>
									<option value="reverced"><?php _e("Sharp Reversed",REVSLIDER_TEXTDOMAIN); ?></option>
								</select>
								<span class="rs-layer-toolbar-space"></span>
	
								<span><?php _e("Sharp Right",REVSLIDER_TEXTDOMAIN); ?></span>
								<span class="rs-layer-toolbar-space"></span>
								<select id="layer_cornerright" name="layer_cornerright" class="rs-layer-input-field" style="width:175px">
									<option value="nothing" selected="selected"><?php _e("No Corner",REVSLIDER_TEXTDOMAIN); ?></option>
									<option value="curved"><?php _e("Sharp",REVSLIDER_TEXTDOMAIN); ?></option>
									<option value="reverced"><?php _e("Sharp Reversed",REVSLIDER_TEXTDOMAIN); ?></option>
								</select>

							</span>
						</div>
						
						<!-- THE HOVER STLYE PART -->
						<div id="tp-hover-state-advanced-style" style="float:left;display:none; padding-left:20px;">
								<ul class="rs-layer-animation-settings-tabs" style="display:inline-block;min-width:615px ">
									<li data-content="#hover-sub-font" class="selected"><?php _e("Font",REVSLIDER_TEXTDOMAIN); ?></li>
									<li data-content="#hover-sub-background"><?php _e("Background",REVSLIDER_TEXTDOMAIN); ?></li>
									<li data-content="#hover-sub-border"><?php _e("Border",REVSLIDER_TEXTDOMAIN); ?></li>
									<li data-content="#hover-sub-transform"><?php _e("Transform",REVSLIDER_TEXTDOMAIN); ?></li>
									<li data-content="#hover-sub-rotation" ><?php _e("Rotation",REVSLIDER_TEXTDOMAIN); ?></li>
									<li data-content="#hover-sub-advcss" ><?php _e("Advanced CSS",REVSLIDER_TEXTDOMAIN); ?></li>
								</ul>

								<div style="width:100%;height:1px;display:block"></div>

								<span id="hover-sub-font" class="rs-layer-toolbar-box" style="display:block">

									<i class="rs-mini-layer-icon rs-icon-color rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Color",REVSLIDER_TEXTDOMAIN); ?>"></i>
									<input type="text" class="my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Color",REVSLIDER_TEXTDOMAIN); ?>"  id="hover_layer_color_s" name="hover_color_static" value="#ff0000" />
									<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

									<!-- FONT HOVER OPACITY -->
									<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Hover Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Font Hover Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_font-transparency" value="1">

									<!-- DECORATION -->
									<i class="rs-mini-layer-icon rs-icon-decoration rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Font Decoration",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php _e("Font Decoration",REVSLIDER_TEXTDOMAIN); ?>" style="width:100px" style="cursor:pointer" name="hover_css_text-decoration">
										<option value="none">none</option>
										<option value="underline">underline</option>
										<option value="overline">overline</option>
										<option value="line-through">line-through</option>
									</select>
								</span>
								
								<span id="hover-sub-background" class="rs-layer-toolbar-box" style="display:none;border:none;">
									<!-- BACKGROUND COLOR -->
									<i class="rs-mini-layer-icon rs-icon-bg rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Background Color",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input type="text" class="rs-staticcustomstylechange my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php _e("Background Color",REVSLIDER_TEXTDOMAIN); ?>"  name="hover_css_background-color" value="transparent" />
									<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

									<!-- BACKGROUND OPACITY -->
									<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Background Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Background Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_background-transparency" value="1">
								</span>

								<span id="hover-sub-border" class="rs-layer-toolbar-box" style="display:none;border:none;">
									<!-- BORDER COLOR -->
									<i class="rs-mini-layer-icon rs-icon-bordercolor rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input type="text" class="rs-staticcustomstylechange my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>"  name="hover_css_border-color-show" value="transparent" />

									<!-- BORDER OPACITY -->
									<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_border-transparency" value="1">


									<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

									<!-- BORDER STYLE -->
									<i class="rs-mini-layer-icon rs-icon-borderstyle rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Style",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<select class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Style",REVSLIDER_TEXTDOMAIN); ?>" style="width:100px" style="cursor:pointer" name="hover_css_border-style">
										<option value="none">none</option>
										<option value="dotted">dotted</option>
										<option value="dashed">dashed</option>
										<option value="solid">solid</option>
										<option value="double">double</option>
									</select>
									<span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

									<!-- BORDER WIDTH-->
									<i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_border-width" value="0">
									<span class="rs-layer-toolbar-space" style="margin-right:16px"></span>

									<!-- BORDER RADIUS -->
									<i class="rs-mini-layer-icon rs-icon-borderradius rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Border Radius (px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
									<input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Radius Top Left",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
									<input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Radius Top Right",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
									<input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Radius Bottom Right",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
									<input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Border Radius Bottom Left",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
								</span>
								
								<span id="hover-sub-transform" class="rs-layer-toolbar-box" style="display:none;border:none;">
									<!-- OPACITY -->
									<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Opacity. (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
									<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Opacity (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__opacity" name="hover_layer__opacity" value="1">
									<span class="rs-layer-toolbar-space"></span>
									
									<!-- SCALE X -->
									<i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon tipsy_enabled_top" title="<?php _e("X Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
									<input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("X Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__scalex" name="hover_layer__scalex" value="1">
									<span class="rs-layer-toolbar-space"></span>
									<!-- SCALE Y -->
									<i class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Y Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
									<input data-suffix="" data-steps="0.01"  data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Y Scale  1 = 100%, 0.5=50%... (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__scaley" name="hover_layer__scaley" value="1">
									<span class="rs-layer-toolbar-space"></span>
									
									<!-- SKEW X -->
									<i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon tipsy_enabled_top" title="<?php _e("X Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
									<input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field  tipsy_enabled_top" title="<?php _e("X Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__skewx" name="hover_layer__skewx" value="0">
									<span class="rs-layer-toolbar-space"></span>
									<!-- SKEW Y -->
									<i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Y Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
									<input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Y Skew (+/-  px)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__skewy" name="hover_layer__skewy" value="0">
								</span>


								<span id="hover-sub-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
									<!--  X  ROTATE -->
									<i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Rotation on X axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>"></i>
									<input data-suffix="deg" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Rotation on X axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__xrotate" name="hover_layer__xrotate" value="0">
									<span class="rs-layer-toolbar-space"></span>
									<!--  Y ROTATE -->
									<i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Rotation on Y axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>"></i>
									<input data-suffix="deg" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Rotation on Y axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer__yrotate" name="hover_layer__yrotate" value="0">
									<span class="rs-layer-toolbar-space"></span>

									<!--  Z ROTATE -->
									<i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Rotation on Z axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>"></i>
									<input data-suffix="deg" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Rotation on Z axis (+/-)",REVSLIDER_TEXTDOMAIN); ?>" id="hover_layer_2d_rotation" name="hover_layer_2d_rotation" value="0">

								</span>
								
								<!-- ADVANCED CSS -->
								<span id="hover-sub-advcss" class="rs-layer-toolbar-box" style="display:none;border:none;">
									<div id="advanced-css-main" class="rev-advanced-css-hover advanced-copy-button"><?php _e("Template",REVSLIDER_TEXTDOMAIN); ?></div>
									<div id="advanced-css-layer" class="rev-advanced-css-hover-layer advanced-copy-button"><?php _e("Layer",REVSLIDER_TEXTDOMAIN); ?></div>
								</span>
								
						</div>


						<!-- IDLE OR HOVER -->
						<div id="idle-hover-swapper" style="width:83px; z-index:900;position: relative;">
							<span id="toggle-idle-hover" class="idleisselected">
								<span class="advanced-label icon-styleidle"><?php _e("Idle",REVSLIDER_TEXTDOMAIN); ?></span>
								<span class="advanced-label icon-stylehover"><?php _e("Hover",REVSLIDER_TEXTDOMAIN); ?></span>
							</span>
							<div style="width:100%;height:1px; clear:both"></div>
							<div style="margin:10px 0px 0px; text-align: center">
								<div id="copy-idle-css" class="advanced-copy-button copy-settings-trigger clicktoshowmoresub"><?php _e("COPY",REVSLIDER_TEXTDOMAIN); ?><i class="eg-icon-down-open"></i>
									<span class="copy-settings-from clicktoshowmoresub_inner" style="display: none; height:58px;">
										<span class="copy-from-idle css-template-handling-menupoint"><?php _e("Copy From Idle",REVSLIDER_TEXTDOMAIN); ?></span>
										<span class="copy-from-hover css-template-handling-menupoint"><?php _e("Copy From Hover",REVSLIDER_TEXTDOMAIN); ?></span>
										<span class="copy-from-in-anim css-template-handling-menupoint"><?php _e("Copy From In Animation",REVSLIDER_TEXTDOMAIN); ?></span>
										<span class="copy-from-out-anim css-template-handling-menupoint"><?php _e("Copy From Out Animation",REVSLIDER_TEXTDOMAIN); ?></span>
									</span>
								</div>
							</div>
						</div>
						<div style="clear:both; display:block;"></div>
					</div>											
				</div>
			</div>

		</div><!-- LAYER POSITION AND ALIGN TOOLBAR ENDS HERE -->


		<!-- LAYER STYLING -->
		<!--<div class="layer-settings-toolbar" id="rs-hover-content-wrapper" style="display:none">
			<div id="extra_style_settings" class="extra_sub_settings_wrapper" style="margin:0; background:#fff;">
				<div style="width:137px;height:75px;float:left;display:inline-block;border-right:1px solid #ddd;padding: 6px 20px 0px;">
					<div id="advanced-css-hover" class="rev-advanced-css-hover"style="margin-right:0px"><?php _e("Hover CSS",REVSLIDER_TEXTDOMAIN); ?></div>
				</div>
			</div>
		</div><!-- LAYER HOVER STYLES ENDS HERE -->

		<!-- LAYER ANIMATIONS -->
		<div class="layer-settings-toolbar" id="rs-animation-content-wrapper" style="display:none">
			<p style="margin:0; border-bottom:1px solid #ddd">
				<!-- START TRANSITIONS -->
				<span class="rs-layer-toolbar-box startanim_mainwrapper">
					<i class="rs-icon-inanim rs-toolbar-icon" style="z-index:100; position:relative;"></i>
					<span id="startanim_wrapper" style="z-index:50; ">
						<span id="startanim_timerunnerbox"></span>
						<span id="startanim_timerunner"></span>
					</span>
				</span>
				
				<span id="add_customanimation_in" title="<?php _e("Advanced Settings",REVSLIDER_TEXTDOMAIN); ?>"><i style="width:40px;height:40px" class="rs-icon-plus-gray"></i></span>

				<span class="rs-layer-toolbar-box" style="">
					<!-- ANIMATION DROP DOWN -->
					<i class="rs-mini-layer-icon rs-icon-transition rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Template",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Template",REVSLIDER_TEXTDOMAIN); ?>" style="width:135px" id="layer_animation" name="layer_animation">
						<?php
						foreach($startanims as $ahandle => $aname){
							$dis = (in_array($ahandle,array('custom',"v5s","v5","v5e","v4s","v4","v4e","vss","vs","vse"))) ? ' disabled="disabled"' : '';
							echo '<option value="'.$ahandle.'"'.$dis.'>'.$aname['handle'].'</option>';
						}
						?>
					</select>
					<span id="animin-template-handling-dd" class="clicktoshowmoresub">
						<span id="animin-template-handling-dd-inner" class="clicktoshowmoresub_inner">
							<span style="background:#ddd !important; padding-left:20px;margin-bottom:5px" class="css-template-handling-menupoint"><span><?php _e("Template Options",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="save-current-animin"   	class="save-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php _e("Save",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="save-as-current-animin"   class="save-as-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php _e("Save As",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="rename-current-animin" class="rename-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-chooser-1"></i><span><?php _e("Rename",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="reset-current-animin"  class="reset-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-2drotation"></i><span><?php _e("Reset",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="delete-current-animin" class="delete-current-animin css-template-handling-menupoint"><i style="background-size:10px 14px;" class="rs-mini-layer-icon rs-toolbar-icon rs-icon-delete"></i><span><?php _e("Delete",REVSLIDER_TEXTDOMAIN); ?></span></span>
						</span>
					</span>
					
					<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>
					
					<!-- EASING-->
					<i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Easing",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Easing",REVSLIDER_TEXTDOMAIN); ?>" style="width:135px"  id="layer_easing" name="layer_easing">
						<?php
						foreach($easings as $ehandle => $ename){
							echo '<option value="'.$ehandle.'">'.$ename.'</option>';
						}
						?>
					</select>
					<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

					<!-- ANIMATION START SPEED -->
					<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Speed (in ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input type="text" style="width:60px; padding-right:10px;" class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Speed (in ms)",REVSLIDER_TEXTDOMAIN); ?>" id="layer_speed" name="layer_speed" value="">
					<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

					<!-- SPLIT TEXT -->
					<i class="rs-mini-layer-icon rs-icon-splittext rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Split Animaton Text (will not respect Html Markups !)",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select id="layer_split" name="layer_split" class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php _e("Split Animaton Text (will not respect Html Markups !)",REVSLIDER_TEXTDOMAIN); ?>" style="width:110px">
						<option value="none" selected="selected"><?php _e("No Split",REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="chars"><?php _e("Char Based",REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="words"><?php _e("Word Based",REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="lines"><?php _e("Line Based",REVSLIDER_TEXTDOMAIN); ?></option>
					</select>
					<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

					<i class="rs-mini-layer-icon rs-icon-splittext-delay rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Delay between Splitted Elements",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input type="text" style="width:65px; padding-right:10px;" class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Delay between Splitted Elements",REVSLIDER_TEXTDOMAIN); ?>" id="layer_splitdelay" name="layer_splitdelay" value="10" disabled="disabled">
				</span>
			</p>

			<div id="extra_start_animation_settings" class="extra_sub_settings_wrapper" style="margin:0; background:#fff; display:none; " >

				<div class="anim-direction-wrapper" style="text-align: center">
						<i class="rs-icon-schin rs-toolbar-icon" style="height:90px"></i>																
				</div>

				<div class="float_left" style="display:inline-block;padding:10px 0px;">
						<div class="inner-settings-wrapper">
							<ul class="rs-layer-animation-settings-tabs">
								<li data-content="#anim-sub-s-offset" class="selected"><?php _e("Offset",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#anim-sub-s-opacity"><?php _e("Opacity",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#anim-sub-s-rotation"><?php _e("Rotation",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#anim-sub-s-scale"><?php _e("Scale",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#anim-sub-s-skew"><?php _e("Skew",REVSLIDER_TEXTDOMAIN); ?></li>
								<li data-content="#anim-sub-s-mask"><?php _e("Masking",REVSLIDER_TEXTDOMAIN); ?></li>
								<!--li data-content="#anim-sub-s-origin"><?php _e("Origin",REVSLIDER_TEXTDOMAIN); ?></li-->
								<!--<li data-content="#anim-sub-s-perspective"><?php _e("Perspective",REVSLIDER_TEXTDOMAIN); ?></li>-->
							</ul>

							<!-- MASKING IN -->							
							<span id="anim-sub-s-mask" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<span class="mask-is-available">
									<i class="rs-mini-layer-icon eg-icon-scissors rs-toolbar-icon"></i>
									<input type="checkbox" name="masking-start" class="rs-inoutanimationfield tp-moderncheckbox"/>
									<span class="rs-layer-toolbar-space"></span>
								</span>
								<span class="mask-not-available">
									<strong><?php _e('Mask is not available due Style Transitions. Please remove any Rotation, Scale or skew effect form Idle and Hover settings', REVSLIDER_TEXTDOMAIN); ?></strong>
								</span>
								<span class="mask-start-settings">
									<!-- MASK X OFFSET -->
									<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon "  style="margin-right:8px"></i>								
									<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_xstart" name="mask_anim_xstart" value="0" data-reverse="on" data-selects="0||Random||Custom||Stage Left||Stage Right||-100%||100%||-175%||175%" data-svalues ="0||{-50,50}||50||stage_left||stage_right||[-100%]||[100%]||[-175%]||[175%]" data-icons="minus||shuffle||wrench||right||left||filter||filter||filter||filter">
									<span class="rs-layer-toolbar-space"></span>
									<!-- MASK Y OFFSET -->
									<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon "  style="margin-right:4px"></i>
									<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_ystart" name="mask_anim_ystart" value="0" data-reverse="on" data-selects="0||Random||Custom||Stage Top||Stage Bottom||-100%||100%||-175%||175%" data-svalues ="0||{-5,50}||50||stage_top||stage_bottom||[-100%]||[100%]||[-175%]||[175%]" data-icons="minus||shuffle||wrench||down||up||filter||filter||filter||filter">
									<span class="rs-layer-toolbar-space"></span>									
								</span>					
							</span>
							

							<span id="anim-sub-s-offset" class="rs-layer-toolbar-box" style="border:none;">
								<!-- X OFFSET -->
								<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon "  style="margin-right:8px"></i>								
								<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_xstart" name="layer_anim_xstart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Left||Stage Right||Stage Center||-100%||100%||-175%||175%" data-svalues ="inherit||{-50,50}||50||left||right||center||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||right||left||cancel||filter||filter||filter||filter">
								<span class="rs-layer-toolbar-space"></span>
								<!-- Y OFFSET -->
								<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon "  style="margin-right:4px"></i>
								<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_ystart" name="layer_anim_ystart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Top||Stage Bottom||Stage Middle||-100%||100%||-175%||175%" data-svalues ="inherit||{-5,50}||50||top||bottom||middle||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||down||up||cancel||filter||filter||filter||filter">
								<span class="rs-layer-toolbar-space"></span>
								<!-- Z OFFSET -->
								<i class="rs-mini-layer-icon rs-icon-zoffset rs-toolbar-icon "  style="margin-right:4px"></i>
								<input type="text" data-suffix="px" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_zstart" name="layer_anim_zstart" value="inherit" id="layer_anim_ystart" name="layer_anim_ystart" value="inherit" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-20,20}||20" data-icons="export||shuffle||wrench">
							</span>

							<span id="anim-sub-s-skew" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- SKEW X -->
								<i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon "  style="margin-right:8px"></i>
								<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field  "  id="layer_skew_xstart" name="layer_skew_xstart" value="inherit"  value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
								<span class="rs-layer-toolbar-space"></span>
								<!-- SKEW Y -->
								<i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon "  style="margin-right:8px"></i>
								<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_skew_ystart" name="layer_skew_ystart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
							</span>

							
							<span id="anim-sub-s-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!--  X  ROTATE -->
								<i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon " ></i>
								<input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_xrotate" name="layer_anim_xrotate" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
								<span class="rs-layer-toolbar-space"></span>
								<!--  Y ROTATE -->
								<i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon " ></i>
								<input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_yrotate" name="layer_anim_yrotate" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
								<span class="rs-layer-toolbar-space"></span>
								
								<!--  Z ROTATE -->
								<i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon " ></i>
								<input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_zrotate" name="layer_anim_zrotate" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-360,360}||45" data-icons="export||shuffle||wrench">

							</span>

							<span id="anim-sub-s-scale" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- SCALE X -->
								<i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon "  style="margin-right:8px"></i>
								<input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_scale_xstart" name="layer_scale_xstart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
								<span class="rs-layer-toolbar-space"></span>
								<!-- SCALE Y -->
								<i class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon " style="margin-right:8px"></i>
								<input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_scale_ystart" name="layer_scale_ystart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
							</span>

							<span id="anim-sub-s-opacity" class="rs-layer-toolbar-box" style="display:none;border:none;">
								<!-- OPACITY -->
								<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:8px"></i>
								<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" type="text" style="width:100px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_opacity_start" name="layer_opacity_start" value="inherit" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
							</span>
						</div>																
				</div>				
				<div style="clear:both; display:block;"></div>


			</div>

			<!-- END TRANSITIONS -->
			<p style="margin:0;">
				<span class="rs-layer-toolbar-box endanim_mainwrapper">
					<i class="rs-icon-outanim rs-toolbar-icon " style="z-index:100; position:relative;"></i>
					<span id="endanim_wrapper" style="z-index:50">
						<span id="endanim_timerunnerbox"></span>
						<span id="endanim_timerunner"></span>
					</span>
				</span>
				
				<span id="add_customanimation_out" title="<?php _e("Advanced Settings",REVSLIDER_TEXTDOMAIN); ?>"><i style="width:40px;height:40px" class="rs-icon-plus-gray"></i></span>
				
				<?php
				$endanims = $operations->getArrEndAnimations();
				?>
				<span class="rs-layer-toolbar-box" style="">
					<!-- ANIMATION DROP DOWN -->
					<i class="rs-mini-layer-icon rs-icon-transition rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Template",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select class="rs-inoutanimationfield rs-layer-input-field" style="width:135px" id="layer_endanimation" name="layer_endanimation" class=" tipsy_enabled_top" title="<?php _e("Animation Template",REVSLIDER_TEXTDOMAIN); ?>">
						<?php
						foreach($endanims as $ahandle => $aname){
							$dis = (in_array($ahandle,array('custom',"v5s","v5","v5e","v4s","v4","v4e","vss","vs","vse"))) ? ' disabled="disabled"' : '';
							echo '<option value="'.$ahandle.'"'.$dis.'>'.$aname['handle'].'</option>';
						}
						?>
					</select>
					<span id="animout-template-handling-dd" class="clicktoshowmoresub" style="z-index:901">
						<span id="animout-template-handling-dd-inner" class="clicktoshowmoresub_inner">
							<span style="background:#ddd !important; padding-left:20px;margin-bottom:5px" class="css-template-handling-menupoint"><span><?php _e("Template Options",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="save-current-animout"   	class="save-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php _e("Save",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="save-as-current-animout"   class="save-as-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php _e("Save As",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="rename-current-animout" class="rename-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-chooser-1"></i><span><?php _e("Rename",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="reset-current-animout"  class="reset-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-2drotation"></i><span><?php _e("Reset",REVSLIDER_TEXTDOMAIN); ?></span></span>
							<span id="delete-current-animout" class="delete-current-animout css-template-handling-menupoint"><i style="background-size:10px 14px;" class="rs-mini-layer-icon rs-toolbar-icon rs-icon-delete"></i><span><?php _e("Delete",REVSLIDER_TEXTDOMAIN); ?></span></span>
						</span>
					</span>

					<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>
					<?php
					$easings = $operations->getArrEndEasing();
					?>
					<!-- EASING-->
					<i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Easing",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Easing",REVSLIDER_TEXTDOMAIN); ?>" style="width:135px"  id="layer_endeasing" name="layer_endeasing">
						<?php
						foreach($easings as $ehandle => $ename){
							echo '<option value="'.$ehandle.'">'.$ename.'</option>';
						}
						?>
						</select>
						<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

						<!-- ANIMATION END SPEED -->
						<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Speed (in ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<input type="text" style="width:60px; " class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Speed (in ms)",REVSLIDER_TEXTDOMAIN); ?>" id="layer_endspeed" name="layer_endspeed" value="">
						<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

						<!-- SPLIT TEXT -->
						<i class="rs-mini-layer-icon rs-icon-splittext rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Split Animaton Text (will not respect Html Markups !)",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<select id="layer_endsplit" name="layer_endsplit" class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php _e("Split Animaton Text (will not respect Html Markups !)",REVSLIDER_TEXTDOMAIN); ?>" style="width:110px">
							<option value="none" selected="selected"><?php _e("No Split",REVSLIDER_TEXTDOMAIN); ?></option>
								<option value="chars"><?php _e("Char Based",REVSLIDER_TEXTDOMAIN); ?></option>
								<option value="words"><?php _e("Word Based",REVSLIDER_TEXTDOMAIN); ?></option>
								<option value="lines"><?php _e("Line Based",REVSLIDER_TEXTDOMAIN); ?></option>
						</select>
						<span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

						<i class="rs-mini-layer-icon rs-icon-splittext-delay rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Delay between Splitted Elements",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<input type="text" style="width:65px; " class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Animation Delay between Splitted Elements",REVSLIDER_TEXTDOMAIN); ?>" id="layer_endsplitdelay" name="layer_endsplitdelay" value="10" disabled="disabled">
				</span>
			</p>
			<div id="extra_end_animation_settings" class="extra_sub_settings_wrapper" style="margin:0; background:#fff; display:none;">
				<div class="anim-direction-wrapper" style="text-align: center">
						<i class="rs-icon-schout rs-toolbar-icon" style="height:90px"></i>																
				</div>


				<div class="float_left" style="display:inline-block;padding:10px 0px;">
					<div class="inner-settings-wrapper" >
						<ul class="rs-layer-animation-settings-tabs">
							<li data-content="#anim-sub-e-offset" class="selected"><?php _e("Offset",REVSLIDER_TEXTDOMAIN); ?></li>
							<li data-content="#anim-sub-e-opacity"><?php _e("Opacity",REVSLIDER_TEXTDOMAIN); ?></li>
							<li data-content="#anim-sub-e-rotation"><?php _e("Rotation",REVSLIDER_TEXTDOMAIN); ?></li>
							<li data-content="#anim-sub-e-scale"><?php _e("Scale",REVSLIDER_TEXTDOMAIN); ?></li>
							<li data-content="#anim-sub-e-skew"><?php _e("Skew",REVSLIDER_TEXTDOMAIN); ?></li>
							<li data-content="#anim-sub-e-mask"><?php _e("Masking",REVSLIDER_TEXTDOMAIN); ?></li>
							<!--li data-content="#anim-sub-e-origin"><?php _e("Origin",REVSLIDER_TEXTDOMAIN); ?></li-->
							<!--<li data-content="#anim-sub-e-perspective"><?php _e("Perspective",REVSLIDER_TEXTDOMAIN); ?></li>-->
						</ul>


						<!-- MASKING IN -->							
						<span id="anim-sub-e-mask" class="rs-layer-toolbar-box" style="display:none;border:none;">
							<span class="mask-is-available">
								<i class="rs-mini-layer-icon eg-icon-scissors rs-toolbar-icon"></i>
								<input type="checkbox" name="masking-end" class="rs-inoutanimationfield tp-moderncheckbox"/>
								<span class="rs-layer-toolbar-space"></span>
							</span>
							<span class="mask-not-available">
								<strong>Mask is not available due Style Transitions. Please remove any Rotation, Scale or skew effect form Idle and Hover settings</strong>
							</span>
							<span class="mask-end-settings">
								<!-- MASK X OFFSET -->
								<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon "  style="margin-right:8px"></i>								
								<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_xend" name="mask_anim_xend" value="0" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Left||Stage Right||Stage Center||-100%||100%||-175%||175%" data-svalues ="inherit||{-50,50}||50||left||right||center||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||right||left||cancel||filter||filter||filter||filter">
								<span class="rs-layer-toolbar-space"></span>
								<!-- MASK Y OFFSET -->
								<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon "  style="margin-right:4px"></i>
								<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_yend" name="mask_anim_yend" value="0" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Top||Stage Bottom||Stage Middle||-100%||100%||-175%||175%" data-svalues ="inherit||{-5,50}||50||top||bottom||middle||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||down||up||cancel||filter||filter||filter||filter">
								<span class="rs-layer-toolbar-space"></span>
							</span>					
						</span>


						<span id="anim-sub-e-offset" class="rs-layer-toolbar-box" style="border:none;">
							<!-- X OFFSET END-->
							<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon"  style="margin-right:8px"></i>								
							<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_xend" name="layer_anim_xend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Left||Stage Right||Stage Center||-100%||100%||-175%||175%" data-svalues ="inherit||{-50,50}||50||left||right||center||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||right||left||cancel||filter||filter||filter||filter">
							<span class="rs-layer-toolbar-space"></span>
							<!-- Y OFFSET END-->
							<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon"  style="margin-right:4px"></i>
							<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_yend" name="layer_anim_yend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Top||Stage Bottom||Stage Middle||-100%||100%||-175%||175%" data-svalues ="inherit||{-5,50}||50||top||bottom||middle||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||down||up||cancel||filter||filter||filter||filter">
							<span class="rs-layer-toolbar-space"></span>
							<!-- Z OFFSET END-->
							<i class="rs-mini-layer-icon rs-icon-zoffset rs-toolbar-icon"  style="margin-right:4px"></i>
							<input type="text" data-suffix="px" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_zend" name="layer_anim_zend" value="inherit" value="0" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-20,20}||20" data-icons="export||shuffle||wrench">
						</span>


						<span id="anim-sub-e-skew" class="rs-layer-toolbar-box" style="display:none;border:none;">
							<!-- SKEW X -->
							<i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon"  style="margin-right:8px"></i>
							<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_skew_xend" name="layer_skew_xend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
							<span class="rs-layer-toolbar-space"></span>
							<!-- SKEW Y -->
							<i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon"  style="margin-right:8px"></i>
							<input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_skew_yend" name="layer_skew_yend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
						</span>

			
						<span id="anim-sub-e-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
							<!--  X  ROTATE -->
							<i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon" ></i>
							<input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_xrotate_end" name="layer_anim_xrotate_end" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
							<span class="rs-layer-toolbar-space"></span>
							<!--  Y ROTATE END -->
							<i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon" ></i>
							<input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_yrotate_end" name="layer_anim_yrotate_end" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
							<span class="rs-layer-toolbar-space"></span>
							
							<!--  Z ROTATE END -->
							<i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon" ></i>
							<input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_zrotate_end" name="layer_anim_zrotate_end" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-360,360}||45" data-icons="export||shuffle||wrench">
						</span>

						<span id="anim-sub-e-scale" class="rs-layer-toolbar-box" style="display:none;border:none;">
							<!-- SCALE X -->
							<i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon "  style="margin-right:8px"></i>
							<input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_scale_xend" name="layer_scale_xend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
							<span class="rs-layer-toolbar-space"></span>
							<!-- SCALE Y -->
							<i class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon " style="margin-right:8px"></i>
							<input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_scale_yend" name="layer_scale_yend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
						</span>

						<span id="anim-sub-e-opacity" class="rs-layer-toolbar-box" style="display:none;border:none;">
							<!-- OPACITY -->
							<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:8px"></i>
							<input data-suffix="" data-steps="0.05" data-min="0" data-max="1" type="text" style="width:100px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_opacity_end" name="layer_opacity_end" value="inherit" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
						</span>

					

					</div>					
				</div>
				<div style="clear:both; display:block;"></div>

			</div>

		</div>

		<!-- LOOP ANIMATIONS -->
		<div class="layer-settings-toolbar" id="rs-loop-content-wrapper" style="display: none">
			<div class="topdddborder">
				<span class="rs-layer-toolbar-box" style="padding-right:26px">
					<span><?php _e("Loop Animation",REVSLIDER_TEXTDOMAIN); ?></span>
				</span>

				<span class="rs-layer-toolbar-box" style="">
					<i class="rs-mini-layer-icon rs-icon-transition rs-toolbar-icon"></i>
					<select class="rs-loopanimationfield rs-layer-input-field" style="width:110px" id="layer_loop_animation" name="layer_loop_animation" class="">
						<option value="none" selected="selected"><?php _e('Disabled', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="rs-pendulum"><?php _e('Pendulum', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="rs-rotate"><?php _e('Rotate', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="rs-slideloop"><?php _e('Slideloop', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="rs-pulse"><?php _e('Pulse', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="rs-wave"><?php _e('Wave', REVSLIDER_TEXTDOMAIN); ?></option>
					</select>
				</span>

				<!-- ANIMATION END SPEED -->
				<span id="layer_speed_wrapper" class="rs-layer-toolbar-box tipsy_enabled_top" title="<?php _e("Loop Speed (sec) - 0.3 = 300ms",REVSLIDER_TEXTDOMAIN); ?>" style="display:none">
					<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Loop Speed (ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field" id="layer_loop_speed" name="layer_loop_speed" value="2">
					<span class="rs-layer-toolbar-space"></span>
				</span>
				<?php
				$easings = $operations->getArrEasing();
				?>
				
				<!-- EASING-->
				<span id="layer_easing_wrapper" class="rs-layer-toolbar-box tipsy_enabled_top" title="<?php _e("Loop Easing",REVSLIDER_TEXTDOMAIN); ?>" style="display:none">
					<i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Loop Easing",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<select class="rs-loopanimationfield  rs-layer-input-field" style="width:175px"  id="layer_loop_easing" name="layer_loop_easing">
						<?php
						foreach($easings as $ehandle => $ename){
							echo '<option value="'.$ehandle.'">'.$ename.'</option>';
						}
						?>
					</select>
				</span>
			</div>
			<div>
				<!-- LOOP PARAMETERS -->
				<span class="rs-layer-toolbar-box" style="padding-right:18px; display:none;" id="layer_parameters_wrapper">
					<span><?php _e("Loop Parameters",REVSLIDER_TEXTDOMAIN); ?></span>
				</span>

				<!-- ROTATION PART -->
				<span id="layer_degree_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<!-- ROTATION START -->
					<i class="rs-mini-layer-icon rs-icon-rotation-start rs-toolbar-icon tipsy_enabled_top" title="<?php _e("2D Rotation start deg.",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input data-suffix="deg" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("2D Rotation start deg.",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_startdeg" name="layer_loop_startdeg" value="-20">
					<span class="rs-layer-toolbar-space"></span>
					<!-- ROTATION END -->
					<i class="rs-mini-layer-icon rs-icon-rotation-end rs-toolbar-icon tipsy_enabled_top" title="<?php _e("2D Rotation end deg.",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input data-suffix="deg" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("2D Rotation end deg.",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_enddeg" name="layer_loop_enddeg" value="20">
				</span>
				<!-- ORIGIN -->
				<span id="layer_origin_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<!-- ORIGIN X -->
					<i class="rs-mini-layer-icon rs-icon-originx rs-toolbar-icon tipsy_enabled_top" title="<?php _e("2D Rotation X Origin",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input data-suffix="%" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("2D Rotation X Origin",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_xorigin" name="layer_loop_xorigin" value="50">
					<span class="rs-layer-toolbar-space"></span>
					<!-- ORIGIN Y -->
					<i class="rs-mini-layer-icon rs-icon-originy rs-toolbar-icon tipsy_enabled_top" title="<?php _e("2D Rotation Y Origin",REVSLIDER_TEXTDOMAIN); ?>"></i>
					<input data-suffix="%" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("2D Rotation Y Origin",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_yorigin" name="layer_loop_yorigin" value="50">
				</span>
				<!-- X/Y START -->
				<span id="layer_x_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<span><?php _e("Start",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Start X Offset",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
					<input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Start X Offset",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_xstart" name="layer_loop_xstart" value="0">
					<span class="rs-layer-toolbar-space"></span>
					<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Start Y Offset",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
					<input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Start Y Offset",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_ystart" name="layer_loop_ystart" value="0">
				</span>
				<!-- X/Y END -->
				<span id="layer_y_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<span><?php _e("End",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("End X Offset",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:8px"></i>
					<input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("End X Offset",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_xend" name="layer_loop_xend" value="0">
					<span class="rs-layer-toolbar-space"></span>
					<i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon tipsy_enabled_top" title="<?php _e("End Y Offset",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:4px"></i>
					<input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("End Y Offset",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_yend" name="layer_loop_yend" value="0">
				</span>

				<!-- ZOOM -->
				<span id="layer_zoom_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<span><?php _e("Zoom Start",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Zoom Start",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_zoomstart" name="layer_loop_zoomstart" value="1">
					<span class="rs-layer-toolbar-space"></span>
					<span><?php _e("Zoom End",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Zoom End",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_zoomend" name="layer_loop_zoomend" value="1">
				</span>
				<!-- ANGLE -->
				<span id="layer_angle_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<span><?php _e("Angle",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Start Angle",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_angle" name="layer_loop_angle" value="0">
				</span>
				<!-- RADIUS -->
				<span id="layer_radius_wrapper" class="rs-layer-toolbar-box" style="display:none">
					<span><?php _e("Radius",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php _e("Radius of Rotation / Pendulum",REVSLIDER_TEXTDOMAIN); ?>" id="layer_loop_radius" name="layer_loop_radius" value="10">
				</span>
			</div>
		</div>

		<!-- LINK SETTINGS -->
		<div class="layer-settings-toolbar" id="rs-imageadvanced-content-wrapper" style="display:none;">
			<span class="rs-layer-toolbar-box rs-lazy-load-images-wrap">
				<span><?php _e("Lazy Loading",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="rs-layer-toolbar-space"></span>
				<select id="layer-lazy-loading" name="layer-lazy-loading" class="rs-layer-input-field" style="width:150px">
					<option value="auto" selected="selected"><?php _e("Default Setting",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="force"><?php _e("Force Lazy Loading",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="ignore"><?php _e("Ignore Lazy Loading",REVSLIDER_TEXTDOMAIN); ?></option>
				</select>
			</span>
			<span class="rs-layer-toolbar-box rs-lazy-load-images-wrap">
				<span><?php _e("Source Type",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="rs-layer-toolbar-space"></span>
				<select id="layer-image-size" name="layer-image-size" class="rs-layer-input-field" style="width:150px">
					<option value="auto" selected="selected"><?php _e("Default Setting",REVSLIDER_TEXTDOMAIN); ?></option>
					<?php
					foreach($img_sizes as $imghandle => $imgSize){
						echo '<option value="'.$imghandle.'">'.$imgSize.'</option>';
					}
					?>
				</select>
			</span>
			
			
		</div>
			
		<!-- LINK SETTINGS -->
		<div class="layer-settings-toolbar" id="rs-action-content-wrapper" style="display:none">		

			<div style="padding:5px 20px 5px">
				
				<span id="triggered-element-behavior">
					<span class="rs-layer-toolbar-box">
						<span><?php _e("Animation Timing",REVSLIDER_TEXTDOMAIN); ?></span>
						<span class="rs-layer-toolbar-space"></span>
						<select id="layer-animation-overwrite" name="layer-animation-overwrite" class="rs-layer-input-field" style="width:150px">
							<option value="default" selected="selected"><?php _e("In and Out Animation Default",REVSLIDER_TEXTDOMAIN); ?></option>							
							<option value="waitout"><?php _e("In Animation Default and Out Animation Wait for Trigger",REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="wait"><?php _e("Wait for Trigger",REVSLIDER_TEXTDOMAIN); ?></option>
						</select>
					</span>
					<span class="rs-layer-toolbar-box">
						<span><?php _e("Trigger Memory",REVSLIDER_TEXTDOMAIN); ?></span>
						<span class="rs-layer-toolbar-space"></span>
						<select id="layer-tigger-memory" name="layer-tigger-memory" class="rs-layer-input-field" style="width:150px">
							<option value="reset" selected="selected"><?php _e("Reset Animation and Trigger States every loop",REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="keep"><?php _e("Keep last selected State",REVSLIDER_TEXTDOMAIN); ?></option>
							
						</select>
					</span>
				</span>	

				<ul class="layer_action_table">
					
					<!-- actions will be added here -->
					
					
					<li class="layer_action_row layer_action_add_template">
						<div class="add-action-row">
							<i class="eg-icon-plus"></i>
						</div>
					</li>
				</ul>

				<script>
					jQuery(document).ready(function() {
						jQuery('body').on('click','.remove-action-row',function() {
							UniteLayersRev.remove_action(jQuery(this));
						});
						
						jQuery('.add-action-row').click(function(){
							UniteLayersRev.add_layer_actions();
						});
					});

				</script>
			</div>
		
		</div>

		<!-- ATTRIBUTE SETTINGS -->
		<div class="layer-settings-toolbar" id="rs-attribute-content-wrapper" style="display:none;">
			<div class="topdddborder">
				
				<!-- ID -->
				<span class="rs-layer-toolbar-box">
					<span><?php _e("ID",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_id" name="layer_id" value="">
				</span>
				
				<!-- CLASSES -->
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Classes",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_classes" name="layer_classes" value="">
				</span>
				
				<!-- TITLE -->
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Title",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_title" name="layer_title" value="">
				</span>
				
				<!-- REL -->
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Rel",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_rel" name="layer_rel" value="">
					<span class="rs-layer-toolbar-space"></span>
				</span>

				<!-- ALT -->
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Alt",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<select id="layer_alt_option" name="layer_alt_option" class="rs-layer-input-field" style="width:100px">
						<option value="media_library"><?php _e('From Media Library', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="file_name"><?php _e('From Filename', REVSLIDER_TEXTDOMAIN); ?></option>
						<option value="custom"><?php _e('Custom', REVSLIDER_TEXTDOMAIN); ?></option>
					</select>
					<input type="text" style="display: none; width:105px;" class="textbox-caption rs-layer-input-field" id="layer_alt" name="layer_alt" value="">
				</span>
				
				
				<!-- INTERNAL CLASSES -->
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Internal Classes:",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<?php 
					//ONLY FOR DEBUG!!
					?>
					<!-- input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="internal_classes" name="internal_classes" value="" -->
					<input type="hidden" style="width:105px;" class="textbox-caption rs-layer-input-field" id="internal_classes" name="internal_classes" value="">
					<span class="rs-internal-class-wrapper"></span>
					<span class="rs-layer-toolbar-space"></span>
				</span>
				
				<?php 
				//ONLY FOR DEBUG!!
				?>
				<!-- LAYER TYPE -->
				<!--span class="rs-layer-toolbar-box">
					<span><?php _e("Layer Type:",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<select name="layer_type" id="layer_type">
						<option value="text">text</option>
						<option value="video">video</option>
						<option value="button">button</option>
						<option value="shape">shape</option>
						<option value="image">image</option>
					</select>
					<span class="rs-layer-toolbar-space"></span>
				</span-->
				
			</div>
		</div>


		<!-- VISIBILITY SETTINGS -->
		<div class="layer-settings-toolbar" id="rs-visibility-content-wrapper" style="display:none">
			<div class="topdddborder">
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Visibility on Devices",REVSLIDER_TEXTDOMAIN); ?></span>
				</span>
				<span class="rs-layer-toolbar-box">
					<span class="rev-visibility-on-sizes">
						<i class="rs-mini-layer-icon rs-icon-desktop rs-toolbar-icon"></i>
						<input type="checkbox" name="visible-desktop" class="tp-moderncheckbox"/>
						<span class="rs-layer-toolbar-space"></span>

						<i class="rs-mini-layer-icon rs-icon-laptop rs-toolbar-icon"></i>
						<input type="checkbox" name="visible-notebook" class="tp-moderncheckbox"/>
						<span class="rs-layer-toolbar-space"></span>

						<i class="rs-mini-layer-icon rs-icon-tablet rs-toolbar-icon"></i>
						<input type="checkbox" name="visible-tablet" class="tp-moderncheckbox"/>
						<span class="rs-layer-toolbar-space"></span>

						<i class="rs-mini-layer-icon rs-icon-phone rs-toolbar-icon"></i>
						<input type="checkbox" name="visible-mobile" class="tp-moderncheckbox"/>
					</span>
				</span>
				
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Hide 'Under' Width",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="checkbox" id="layer_hidden" class="tp-moderncheckbox" name="layer_hidden">
				</span>
				
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Only on Slider Hover",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space"></span>
					<input type="checkbox" id="layer_on_slider_hover" class="tp-moderncheckbox" name="layer_on_slider_hover">
				</span>
			</div>
		</div>

		<!-- BEHAVIOR SETTINGS -->
		<div class="layer-settings-toolbar" id="rs-behavior-content-wrapper" style="display:none">
			<div class="topdddborder">
					
				<span class="rs-layer-toolbar-box">
					<span><?php _e("Auto Responsive",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
					<input type="checkbox" id="layer_resize-full" class="tp-moderncheckbox" name="layer_resize-full" checked="checked">
				</span>

				<span class="rs-layer-toolbar-box">
					<span><?php _e("Child Elements Responsive",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
					<input type="checkbox" id="layer_resizeme" class="tp-moderncheckbox" name="layer_resizeme" checked="checked">
				</span>

				<span class="rs-layer-toolbar-box">
					<span><?php _e("Align",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
						<select id="layer_align_base" name="layer_align_base" class="rs-layer-input-field" style="width:100px">
							<option value="grid" selected="selected"><?php _e("Grid Based",REVSLIDER_TEXTDOMAIN); ?></option>
							<option value="slide"><?php _e("Slide Based",REVSLIDER_TEXTDOMAIN); ?></option>							
						</select>
				</span>

				<span class="rs-layer-toolbar-box">
					<span><?php _e("Responsive Offset",REVSLIDER_TEXTDOMAIN); ?></span>
					<span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
					<input type="checkbox" id="layer_resp_offset" class="tp-moderncheckbox" name="layer_resp_offset" checked="checked">
				</span>

				
			</div>
			
			
			
		</div>


		<!-- STATIC LAYERS SETTINGS -->
		<div class="layer-settings-toolbar" id="rs-static-content-wrapper" style="display:none">
			<?php
				$show_static = 'display: none;';
				if($slide->isStaticSlide())
					$show_static = '';
				$isTemplate = $slider->getParam("template", "false");

			if($isTemplate == "true"){
			?>
				<span class="rs-layer-toolbar-box">
					<?php _e('Static Layers will be shown on every slide in template sliders', REVSLIDER_TEXTDOMAIN); ?>
				</span>
			<?php }
			?>

			<span class="rs-layer-toolbar-box" id="layer_static_wrapper" <?php echo ($isTemplate == "true") ? ' style="display: none;"' : ''; ?>>

				<span><?php _e("Start on Slide",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="rs-layer-toolbar-space"></span>
				<select id="layer_static_start" name="static_start">
					<?php
					if(!empty($arrSlideNames)){
						$si = 1;
						end($arrSlideNames);
						//fetch key of the last element of the array.
						$lastElementKey = key($arrSlideNames);
						foreach($arrSlideNames as $sid => $sparams){
							if($lastElementKey == $sid) break; //break on the last element
							?>
							<option value="<?php echo $si; ?>"><?php echo $si; ?></option>
							<?php
							$si++;
						}
						/*?><option value="last"><?php _e('Last Slide', REVSLIDER_TEXTDOMAIN); ?></option><?php*/
					}else{
						?>
						<option value="-1">-1</option>
						<?php
					}
					?>
				</select>
				<span class="rs-layer-toolbar-space"></span>
				<span><?php _e("End on Slide",REVSLIDER_TEXTDOMAIN); ?></span>
				<span class="rs-layer-toolbar-space"></span>
				<select id="layer_static_end" name="static_end">
					<?php
					if(!empty($arrSlideNames)){
						$si = 1;
						foreach($arrSlideNames as $sid => $sparams){
							?>
							<option value="<?php echo $si; ?>"><?php echo $si; ?></option>
							<?php
							$si++;
						}
						/*?><option value="last"><?php _e('Last Slide', REVSLIDER_TEXTDOMAIN); ?></option><?php*/
					}else{
						?>
						<option value="-1">-1</option>
						<?php
					}
					?>
				</select>
			</span>
		</div>
	</form>
	<!-- END OF AMAZING TOOLBAR -->
	<?php
	$slidertype = $slider->getParam("slider_type","fullwidth");
	$style .= ' margin: 0 auto;';
	$tempwidth_jq = $maxbgwidth;

	if($slidertype == 'fullwidth'){

		$style_wrapper .= ' width: 100%;';
		$maxbgwidth ="";
	} else {
		$style_wrapper .= $style;
	}

	$hor_lines = RevSliderFunctions::getVal($settings, "hor_lines","");
	$ver_lines = RevSliderFunctions::getVal($settings, "ver_lines","");
	?>
	<div id="thelayer-editor-wrapper">
		<!-- THE EDITOR PART -->
		<div id="horlinie"><div id="horlinetext">0</div></div>
		<div id="verlinie"><div id="verlinetext">0</div></div>
		<div id="hor-css-linear">
			<ul class="linear-texts"></ul>
			<div class="helplines-offsetcontainer">
				<?php
				if(!empty($hor_lines)){
					foreach($hor_lines as $lines){
						?>
						<div class="helplines" style="position:absolute;width:1px;background:#4AFFFF;left:<?php echo $lines; ?>;top:0px"><i class="helpline-drag eg-icon-move"></i><i class="helpline-remove eg-icon-cancel"></i></div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<div id="ver-css-linear">
			<ul class="linear-texts"></ul>
			<div class="helplines-offsetcontainer">
				<?php
				if(!empty($ver_lines)){
					foreach($ver_lines as $lines){
						?>
						<div class="helplines" style="position:absolute;height:1px;background:#4AFFFF;top:<?php echo $lines; ?>;left:0px"><i class="helpline-drag eg-icon-move"></i><i class="helpline-remove eg-icon-cancel"></i></div>
						<?php
					}
				}
				?>
			</div>
		</div>

		<div id="hor-css-linear-cover-left"></div>
		<div id="hor-css-linear-cover-right"></div>
		<div id="ver-css-linear-cover"></div>

		<div id="divLayers-wrapper" style="overflow: hidden;<?php echo $style.$maxbgwidth; ?>" class="slide_wrap_layers" >
			<div id="divbgholder" style="<?php echo $style_wrapper.$divbgminwidth.$maxbgwidth ?>" class="<?php echo $class_wrapper; ?>">
				<div class="oldslotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:0;">
					<div class="tp-bgimg defaultimg"></div>
				</div>
				<div class="slotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:1">
					<div class="tp-bgimg defaultimg" style="width: 100%;height: 100%;position: absolute;top:0px;left:0px; <?php echo $style_wrapper.$divbgminwidth.$maxbgwidth ?>"></div>
				</div>
				<div id="divLayers" class="<?php echo $divLayersClass?>" style="<?php echo $style.$divLayersWidth; ?>">
					<div class="slide_layers_border"></div>
				</div>
			</div>
			
		</div>

		<!-- TEXT / IMAGE INPUT FIELD CONTENT -->
		<form name="form_layers" class="form_layers">
			<div id="layer_text_holder">
				<div id="layer_text_wrapper" style="display:none">
					<div class="layer_text_wrapper_header">					
						<span style="display:none; font-weight:600;" class="layer-content-title-b"><?php _e("Image Layer Title ",REVSLIDER_TEXTDOMAIN); ?><span style="margin-left:5px;font-size:11px; font-style: italic;"><?php _e("(only for internal usage):",REVSLIDER_TEXTDOMAIN); ?></span> </span>					
					</div>
					<textarea id="layer_text" class="area-layer-params" name="layer_text" ></textarea>
				</div>
			</div>
		</form>

	
		<?php
			//show/hide layers of specific slides
			$add_static = 'false';
			if($slide->isStaticSlide()){
				$add_static = 'true';
			}
		?>
		<div id="add-layer-minimiser"></div>
		<div id="add-layer-selector-container">
			<a href="javascript:void(0)" id="button_add_any_layer" class="add-layer-button-any tipsy_enabled_top"><i class="rs-icon-addlayer2"></i><span class="add-layer-txt"><?php _e("Add a New Layer",REVSLIDER_TEXTDOMAIN)?></span></a>
			<div id="add-new-layer-container-wrapper">
				<div id="add-new-layer-container">
					<a href="javascript:void(0)" id="button_add_layer" 		  data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" ><i class="rs-icon-layerfont"></i><span class="add-layer-txt"><?php _e("Text/Html",REVSLIDER_TEXTDOMAIN)?></span></a>
					<a href="javascript:void(0)" id="button_add_layer_image"  data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" ><i class="rs-icon-layerimage"></i><span class="add-layer-txt"><?php _e("Image",REVSLIDER_TEXTDOMAIN)?></span></a>
					<a href="javascript:void(0)" id="button_add_layer_video"  data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" ><i class="rs-icon-layervideo"></i><span class="add-layer-txt"><?php _e("Video",REVSLIDER_TEXTDOMAIN)?></span></a>
					<a href="javascript:void(0)" id="button_add_layer_button"  data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" ><i class="rs-icon-layerbutton"></i><span class="add-layer-txt"><?php _e("Button",REVSLIDER_TEXTDOMAIN)?></span></a>
					<a href="javascript:void(0)" id="button_add_layer_shape"  data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" ><i class="rs-icon-layershape"></i><span class="add-layer-txt"><?php _e("Shape",REVSLIDER_TEXTDOMAIN)?></span></a>
				</div>
			</div>
		</div>

		<div id="quick-layer-selector-container">
			<a href="javascript:void(0)" id="button_select_quick_layer" class="tipsy_enabled_top"><i class="eg-icon-popup"></i><span class="add-layer-txt"><?php _e("Quick Layer Selector",REVSLIDER_TEXTDOMAIN)?></span></a>
			<div id="quick-layers-wrapper">				
				<div id="quick-layers">	
					<div class="quick-layer-all-lock"><i class="eg-icon-lock"></i></div>
					<div class="quick-layer-all-view"><i class="eg-icon-eye-off"></i></div>
					
					<div class="tp-clearfix"></div>
					<ul class="quick-layers-list">
						<li class="nolayersavailable"><div class="add-layer-button"><?php _e("Slide contains no layers",REVSLIDER_TEXTDOMAIN)?></div></li>
					</ul>
					<!--<div style="text-align:center; display:block; padding:0px 10px;">
						<span class="gototimeline">Animation Timeline</span>
						</div>-->
				</div>
			</div>
		</div>

		
		<!-- DESKTOP / TABLET / PHONE SIZING -->
		<?php
		$_width = $slider->getParam('width', 1240);
		$_width_notebook = $slider->getParam('width_notebook', 1024);
		$_width_tablet = $slider->getParam('width_tablet', 778);
		$_width_mobile = $slider->getParam('width_mobile', 480);

		$_height = $slider->getParam('height', 868);
		$_height_notebook = $slider->getParam('height_notebook', 768);
		$_height_tablet = $slider->getParam('height_tablet', 960);
		$_height_mobile = $slider->getParam('height_mobile', 720);

		if($adv_resp_sizes === true){
			?>
			<div id="rs-edit-minimiser"></div>
			<span id="rs-edit-layers-on-btn">
				<div data-val="desktop" class="rs-slide-device_selector rs-slide-ds-desktop selected"></div>
				<?php if($enable_custom_size_notebook == 'on'){ ?><div data-val="notebook" class="rs-slide-device_selector rs-slide-ds-notebook"></div><?php } ?>
				<?php if($enable_custom_size_tablet == 'on'){ ?><div data-val="tablet" class="rs-slide-device_selector rs-slide-ds-tablet"></div><?php } ?>
				<?php if($enable_custom_size_iphone == 'on'){ ?><div data-val="mobile" class="rs-slide-device_selector rs-slide-ds-mobile"></div><?php } ?>
			</span>
			<?php
		}
		
		?>


		<!-- ADD LAYERS, REMOVE LAYERS, DUPLICATE LAYERS -->
		<div id="layer-settings-toolbar-bottom" class="layer-settings-toolbar-bottom">
			

			<div id="layer-short-toolbar">	
				
				<div style="display:inline-block;  vertical-align:top; white-space:nowrap;">
				
					<span id="button_edit_layer" class="layer-short-tool revblue"><i class="eg-icon-font"></i></span>
					<span id="button_reset_size" class="layer-short-tool revblue"><i class="eg-icon-resize-normal"></i></span>				
					<span id="button_change_image_source" class="layer-short-tool revblue"><i class="eg-icon-picture-1"></i></span>		
					<span id="button_change_video_settings" class="layer-short-tool revblue"><i class="eg-icon-video"></i></span>		
					
					<span id="button_delete_layer" class="layer-short-tool revred"><i class="rs-lighttrash"></i></span>
					<span id="button_duplicate_layer" class="layer-short-tool revyellow" data-isstatic="<?php echo $add_static; ?>"><i class="rs-lightcopy"></i></span>				
					<span style="display:none;"  id="tp-addiconbutton" class="layer-short-tool revblue"><i class="eg-icon-th"></i></span>
					<?php if($slider_type != 'gallery'){ ?>
						<span id="linkInsertTemplate" class="layer-short-tool revyellow"><i class="eg-icon-pencil"></i></span>					
					<?php } ?>
					<span id="hide_layer_content_editor"  class="layer-short-tool revgreen" style="display:none;"><i class="eg-icon-check"></i></span>
				</div>
			</div>
		

		<!--<a href="javascript:void(0)" id="button_delete_all"      class="button-primary  revred button-disabled"><i class="revicon-trash"></i><?php _e("Delete All Layers",REVSLIDER_TEXTDOMAIN)?> </a>-->
		



			<select style="display:none" name="rs-edit-layers-on" id="rs-edit-layers-on">
				<option value="desktop"><?php _e('Desktop',REVSLIDER_TEXTDOMAIN); ?></option>
				<option value="notebook"><?php _e('Notebook',REVSLIDER_TEXTDOMAIN); ?></option>
				<option value="tablet"><?php _e('Tablet',REVSLIDER_TEXTDOMAIN); ?></option>
				<option value="mobile"><?php _e('Mobile',REVSLIDER_TEXTDOMAIN); ?></option>
			</select>
			<script type="text/javascript">
				

				jQuery('#add-layer-selector-container').hover(function() {
					jQuery('#add-new-layer-container-wrapper').show();
				},function() {
					jQuery('#add-new-layer-container-wrapper').hide();
				});

				jQuery('#quick-layer-selector-container').hover(function() {
					jQuery('#quick-layers-wrapper').show();
				},function() {
					jQuery('#quick-layers-wrapper').hide();
				});

				

				jQuery('#add-layer-minimiser').click(function() {
					var t = jQuery(this);
					if (t.hasClass("closed")) {
						t.removeClass("closed");
						punchgs.TweenLite.to(jQuery('#add-layer-selector-container'),0.4,{autoAlpha:1,rotationY:0,transformOrigin:"0% 50%",ease:punchgs.Power3.easeInOut});
						punchgs.TweenLite.to(jQuery('#quick-layer-selector-container'),0.4,{autoAlpha:1,rotationY:0,transformOrigin:"0% 50%",ease:punchgs.Power3.easeInOut});
					} else {
						t.addClass("closed");
						punchgs.TweenLite.to(jQuery('#add-layer-selector-container'),0.4,{autoAlpha:0,rotationY:-90,transformOrigin:"0% 50%",ease:punchgs.Power3.easeInOut});
						punchgs.TweenLite.to(jQuery('#quick-layer-selector-container'),0.4,{autoAlpha:0,rotationY:-90,transformOrigin:"0% 50%",ease:punchgs.Power3.easeInOut});
					}
					return false;
				});

				jQuery('#rs-edit-minimiser').click(function() {
					var t = jQuery(this);
					if (t.hasClass("closed")) {
						t.removeClass("closed");
						punchgs.TweenLite.to(jQuery('#rs-edit-layers-on-btn'),0.4,{autoAlpha:1,rotationX:0,transformOrigin:"50% 0%",ease:punchgs.Power3.easeInOut});
					} else {
						t.addClass("closed");
						punchgs.TweenLite.to(jQuery('#rs-edit-layers-on-btn'),0.4,{autoAlpha:0,rotationX:-90,transformOrigin:"50% 0%",ease:punchgs.Power3.easeInOut});
					}
					return false;
				});

				jQuery('#rs-edit-layers-on-btn').hover(function() {
					jQuery('.rs-slide-device_selector').show();
				}, function() {
					jQuery('.rs-slide-device_selector').each(function() {
						if (jQuery(this).hasClass("selected")) {

						} else {
							jQuery(this).hide();
						}
					})
				});

				jQuery('.rs-slide-device_selector').click(function() {
					jQuery(this).prependTo('#rs-edit-layers-on-btn')
				})

				jQuery('#add-new-layer-container a').click(function() {
					jQuery('#add-new-layer-container-wrapper').hide();
					return true;
				});

				<?php
				if($adv_resp_sizes === true){
					?>
					var rev_adv_resp_sizes = true;
					var rev_sizes = {
						'desktop': [<?php echo $_width.', '.$_height; ?>],
						'notebook': [<?php echo $_width_notebook.', '.$_height_notebook; ?>],
						'tablet': [<?php echo $_width_tablet.', '.$_height_tablet; ?>],
						'mobile': [<?php echo $_width_mobile.', '.$_height_mobile; ?>]
					};

					<?php
				}else{
					?>
					var rev_adv_resp_sizes = false;
					<?php
				}
				?>


			</script>

			<!-- HELPER GRID ON/OFF -->
			<span style="float:right;display:inline-block;line-height:40px;vertical-align: middle; margin-right:30px;">
				<span class="setting_text_3"><?php _e("Helper Grid:",REVSLIDER_TEXTDOMAIN); ?></span>
				<select name="rs-grid-sizes" id="rs-grid-sizes">
					<option value="1"><?php _e("Disabled",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="10">10 x 10</option>
					<option value="25">25 x 25</option>
					<option value="50">50 x 50</option>
					<option value="custom"><?php _e('Custom', REVSLIDER_TEXTDOMAIN); ?></option>
				</select>
				<span class="rs-layer-toolbar-space" style="margin-right:20px"></span>
				<span class="setting_text_3"><?php _e("Snap to:",REVSLIDER_TEXTDOMAIN); ?></span>
				<select name="rs-grid-snapto" id="rs-grid-snapto" >
					<option value=""><?php _e("None",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value=".helplines"><?php _e("Help Lines",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value=".slide_layer"><?php _e("Layers",REVSLIDER_TEXTDOMAIN); ?></option>
				</select>
			</span>
		</div>
	</div>

	<!-- THE CURRENT TIMER FOR LAYER -->
	<div style="direction:ltr !important">
		<div id="mastertimer-wrapper" class="layer_sortbox">
				<div id="timline-manual-dialog" style="display:none">
					<!-- ANIMATION START TIME -->
					
						<label style="width:70px"><?php _e("Start Time",REVSLIDER_TEXTDOMAIN); ?></label>
						<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Start Time (ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer_start_time" name="clayer_start_time" value="0">
						<span class="over-ms">ms</span>
					</span>
					<span class="rs-layer-toolbar-space" style="margin-right:20px"></span>

					<!-- ANIMATION END TIME -->
					<span>
						<label style="width:70px"><?php _e("End Time",REVSLIDER_TEXTDOMAIN); ?></label>
						<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation End Time (ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer_end_time" name="clayer_end_time" value="0">
						<span class="over-ms">ms</span>
					</span>

					
					<span class="tp-clearfix"></span>
					
					

					<!-- ANIMATION START DURATION -->
					<span>
						<label style="width:70px"><?php _e("Start speed",REVSLIDER_TEXTDOMAIN); ?></label>
						<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation Start Duration (ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer_start_speed" name="clayer_start_speed" value="0">
						<span class="over-ms">ms</span>
					</span>

					<span class="rs-layer-toolbar-space" style="margin-right:20px"></span>

					<!-- ANIMATION END DURATION -->
					<span>
						<label style="width:70px"><?php _e("End Speed",REVSLIDER_TEXTDOMAIN); ?></label>
						<i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php _e("Animation End Duration (ms)",REVSLIDER_TEXTDOMAIN); ?>"></i>
						<input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer_end_speed" name="clayer_end_speed" value="0">
						<span class="over-ms">ms</span>
					</span>				
					<div id="timline-manual-closer"><i class="eg-icon-cancel"></i></div>
				</div>


				<div id="master-selectedlayer-t"></div>
				<div id="master-selectedlayer-b"></div>
				<div class="master-leftcell">
					<div id="master-leftheader">
						<div id="mastertimer-playpause-wrapper">
								<i class="eg-icon-play"></i>
								<span><?php _e('PLAY', REVSLIDER_TEXTDOMAIN); ?></span>
						</div>
						<div id="mastertimer-backtoidle">
						</div>

						<div id="master-timer-time">00:00.00</div>
					</div>
					<div class="layers-wrapper">
						<div class="layers-wrapper-scroll">
							<div id="layers-left" class="sortlist">
								<ul>
									<li id="slide_in_sort" class="mastertimer-layer mastertimer-slide ui-state-default" style="overflow: visible !important; z-index: 1000; position: relative">
										<div id="fake-select-label-wrapper">
											<span id="fake-select-i" style="margin-right:0px;width:20px;">
												<i style="margin-left:5px;margin-right:0px;" class="eg-icon-cog"></i>
											</span>
											<span id="fake-select-label"><?php _e('Animation', REVSLIDER_TEXTDOMAIN); ?></span>

										</div>
									</li>

								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="master-rightcell">
					<div id="master-rightheader">
						<div id="mastertimer-position"><span id="mastertimer-poscurtime"><?php _e('DragMe',REVSLIDER_TEXTDOMAIN); ?></span></div>
						<div id="mastertimer-maxtime"><span id="mastertimer-maxcurtime"></span></div>
						<div id="mastertimer-idlezone"></div>


						<div class="mastertimer">

							<div id="mastertimer-linear">
								<ul class="linear-texts">
								</ul>
							</div>
						</div>
					</div>

					<div class="layers-wrapper">
						<div class="layers-wrapper-scroll">
							<div id="layers-right">
								<ul>
									<li id="slide_in_sort_time" class="mastertimer-layer mastertimer-slide ui-state-default">
										<div class="timeline">
											<div class="tl-fullanim">
												<div class="tl-startanim">
													<span class="sortbox_speedin">100</span>
													<span class="start-anim-puller"></span>
												</div>
											</div>
											<div class="slide-idle-section"></div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div id="mastertimer-wrapper-resizer"></div>
		</div>
	</div>
	<div id="tp-thelistofclasses"></div>

	<!-- THE BUTTON DIALOG WINDOW -->
	<div id="dialog_addbutton" class="dialog-addbutton" title="<?php _e("Add Button Layer",REVSLIDER_TEXTDOMAIN); ?>" style="display:none">
		<div class="addbuton-dialog-inner">
			<div id="addbutton-examples">
				<div class="addbe-title-row">					
					<span class="addbutton-bg-light"></span>
					<span class="addbutton-bg-dark"></span>
					<span class="addbutton-title" style="font-size:14px;"><?php _e("Click on Element to add it",REVSLIDER_TEXTDOMAIN); ?></span>
				</div>
				
				<div class="addbutton-examples-wrapper">
					<span class="addbutton-title"><?php _e("Buttons",REVSLIDER_TEXTDOMAIN); ?></span>
					<div class="addbutton-buttonrow" style="padding-top: 10px;">
						<a data-needclass="rev-btn" class="rev-btn rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
						<a data-needclass="rev-btn" class="rev-btn rev-medium rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
						<a data-needclass="rev-btn" class="rev-btn rev-small rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
					</div>
					<div class="addbutton-buttonrow">
						<a data-needclass="rev-btn" class="rev-btn rev-minround rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
						<a data-needclass="rev-btn" class="rev-btn rev-medium rev-minround rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
						<a data-needclass="rev-btn" class="rev-btn rev-small rev-minround rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
					</div>
					<div class="addbutton-buttonrow">
						<a data-needclass="rev-btn" class="rev-btn rev-maxround rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
						<a data-needclass="rev-btn" class="rev-btn rev-medium rev-maxround rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
						<a data-needclass="rev-btn" class="rev-btn rev-small rev-maxround rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?></a>
					</div>
					<div class="addbutton-buttonrow">
						<a data-needclass="rev-btn rev-withicon" class="rev-btn rev-maxround rev-withicon rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
						<a data-needclass="rev-btn rev-withicon" class="rev-btn rev-medium rev-maxround rev-withicon rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
						<a data-needclass="rev-btn rev-withicon" class="rev-btn rev-small rev-maxround rev-withicon rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
					</div>
					<div class="addbutton-buttonrow">
						<a data-needclass="rev-btn rev-hiddenicon" class="rev-btn rev-maxround rev-hiddenicon rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
						<a data-needclass="rev-btn rev-hiddenicon" class="rev-btn rev-medium rev-maxround rev-hiddenicon rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
						<a data-needclass="rev-btn rev-hiddenicon" class="rev-btn rev-small rev-maxround rev-hiddenicon rev-bordered" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
					</div>
					<div class="addbutton-buttonrow">
						<a data-needclass="rev-btn rev-hiddenicon" class="rev-btn rev-maxround rev-hiddenicon rev-bordered rev-uppercase" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
						<a data-needclass="rev-btn rev-hiddenicon" class="rev-btn rev-medium rev-maxround rev-hiddenicon rev-bordered rev-uppercase" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
						<a data-needclass="rev-btn rev-hiddenicon" class="rev-btn rev-small rev-maxround rev-hiddenicon rev-bordered rev-uppercase" href="javascript:void(0);"><?php _e("Click Here",REVSLIDER_TEXTDOMAIN); ?><i class="icon-right-open"></i></a>
					</div>
					<span class="addbutton-title" style="margin-top:10px;margin-bottom:10px;"><?php _e("Predefined Elements",REVSLIDER_TEXTDOMAIN); ?></span>
					<div class="addbutton-buttonrow trans_bg">
						<div class="dark_trans_overlay"></div> 
						<div data-needclass="rev-burger revb-white" class="revb-white rev-burger" style="display:inline-block;margin-right:10px">
						  <span></span>
						  <span></span>
						  <span></span>
						</div>
						<div data-needclass="rev-burger revb-whitenoborder" class="revb-whitenoborder rev-burger" style="display:inline-block;margin-right:10px">
						  <span></span>
						  <span></span>
						  <span></span>
						</div>
						<div data-needclass="rev-burger revb-darkfull" class="revb-darkfull rev-burger" style="display:inline-block;margin-right:10px">
						  <span></span>
						  <span></span>
						  <span></span>
						</div>
						<div data-needclass="rev-burger revb-dark" class="revb-dark rev-burger" style="display:inline-block;margin-right:10px">
						  <span></span>
						  <span></span>
						  <span></span>
						</div>
						<div data-needclass="rev-burger revb-darknoborder" class="revb-darknoborder rev-burger" style="display:inline-block;margin-right:10px">
						  <span></span>
						  <span></span>
						  <span></span>
						</div>
						<div data-needclass="rev-burger revb-whitefull" class="revb-whitefull rev-burger" style="display:inline-block;margin-right:10px">
						  <span></span>
						  <span></span>
						  <span></span>
						</div>
						
						<div style="width:100%;height:25px;display:block"></div>
						<span data-needclass="rev-scroll-btn" class="rev-scroll-btn" style="margin-right:10px">							
							<span>
							</span>							
						</span>
						<span data-needclass="rev-scroll-btn revs-dark" class="rev-scroll-btn revs-dark" style="margin-right:10px">
							<span>
							</span>												
						</span>

						<span data-needclass="rev-scroll-btn revs-fullwhite" class="rev-scroll-btn revs-fullwhite" style="margin-right:10px">
							<span>
							</span>							
						</span>

						<span data-needclass="rev-scroll-btn revs-fulldark" class="rev-scroll-btn revs-fulldark" style="margin-right:10px">
							<span>
							</span>
						</span>

						<span data-needclass="" class="rev-control-btn rev-sbutton rev-sbutton-blue" style="margin-right:10px;vertical-align:top">
							<i class="fa-icon-facebook"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-sbutton rev-sbutton-lightblue" style="margin-right:10px;vertical-align:top">
							<i class="fa-icon-twitter"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-sbutton rev-sbutton-red" style="margin-right:10px;vertical-align:top">
							<i class="fa-icon-google-plus"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-sbutton" style="margin-right:10px;vertical-align:top">
							<i class="fa-icon-envelope"></i>
						</span>

						<div style="width:100%;height:25px;display:block"></div>
						<span data-needclass="" class="rev-control-btn rev-cbutton-dark" style="margin-right:10px">
							<i class="fa-icon-play"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-cbutton-light" style="margin-right:10px">
							<i class="fa-icon-play"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-cbutton-dark-sr" style="margin-right:10px">
							<i class="fa-icon-play"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-cbutton-light-sr" style="margin-right:10px">
							<i class="fa-icon-play"></i>
						</span>
						<div style="width:100%;height:25px;display:block"></div>
						<span data-needclass="" class="rev-control-btn rev-cbutton-dark" style="margin-right:10px">
							<i class="fa-icon-pause"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-cbutton-light" style="margin-right:10px">
							<i class="fa-icon-pause"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-cbutton-dark-sr" style="margin-right:10px">
							<i class="fa-icon-pause"></i>
						</span>

						<span data-needclass="" class="rev-control-btn rev-cbutton-light-sr" style="margin-right:10px">
							<i class="fa-icon-pause"></i>
						</span>
						<div style="width:100%;height:25px;display:block"></div>

						
					</div>
				</div>
			</div>
			<div id="addbutton-settings">
				<div class="adb-configs" style="padding-top:0px">
					<!-- TITLE -->
					<div class="add-togglebtn"><span class="addbutton-title"><?php _e("Idle State",REVSLIDER_TEXTDOMAIN); ?></span><span class="adb-toggler eg-icon-minus"></span></div>
					<div class="add-intern-accordion" style="display:block">
						<!-- COLOR 1 -->
						<div class="add-lbl-wrapper">
						<label><?php _e("Background",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<!-- COLOR -->					
						<input type="text" class="rs-layer-input-field my-color-field" style="width:150px" name="adbutton-color-1" value="#000000" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<!-- OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " style="margin-right:5px"></i>
						<input data-suffix="" class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-1" value="0.75">
						

						
						<!-- TEXT / ICON -->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Color",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<!-- TEXT COLOR -->					
						<input type="text" class="rs-layer-input-field  my-color-field" title="<?php _e("Color 2",REVSLIDER_TEXTDOMAIN); ?>" style="width:150px" name="adbutton-color-2" value="#ffffff" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

						<!-- TEXT OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:5px"></i>
						<input class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-2" value="1">
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						
						<!-- BORDER -->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Border",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<!-- BORDER COLOR -->					
						<input type="text" class="rs-layer-input-field  my-color-field" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>" style="width:150px" name="adbutton-border-color" value="#000000" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

						<!-- BORDER OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="adb-input rs-layer-input-field " title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:45px" type="text" name="adbutton-border-opacity" value="1">
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<!-- BORDER WIDTH-->
						<i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon " title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="adb-input text-sidebar rs-layer-input-field " title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="width:45px" type="text" name="adbutton-border-width" value="0">
						<div style="width:100%;height:5px"></div>
						
						<!-- ICON  & FONT-->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Text / Icon",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
					
						<span class="addbutton-icon"><i class="fa-icon-chevron-right"></i></span>
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<i class="rs-mini-layer-icon rs-icon-fontfamily rs-toolbar-icon " title="<?php _e("Font Family",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="adb-input text-sidebar rs-layer-input-field " title="<?php _e("Font Family",REVSLIDER_TEXTDOMAIN); ?>" style="width:75px" type="text" name="adbutton-fontfamily" value="Roboto">
						
					</div>
				</div>
				<div class="adb-configs">
					<!-- TITLE -->
					<div class="add-togglebtn"><span class="addbutton-title"><?php _e("Hover State",REVSLIDER_TEXTDOMAIN); ?></span><span class="adb-toggler eg-icon-plus"></span></div>
					<div class="add-intern-accordion" style="display:none">
						<!-- COLOR 1 -->
						<div class="add-lbl-wrapper">
						<label><?php _e("Background",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<!-- COLOR -->					
						<input type="text" class="rs-layer-input-field my-color-field" style="width:150px" name="adbutton-color-1-h" value="#FFFFFF" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<!-- OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " style="margin-right:5px"></i>
						<input data-suffix="" class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-1-h" value="1">
						
						<!-- TEXT / ICON -->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Color",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>	
						
						<!-- TEXT COLOR -->					
						<input type="text" class="rs-layer-input-field  my-color-field" title="<?php _e("Color 2",REVSLIDER_TEXTDOMAIN); ?>" style="width:150px" name="adbutton-color-2-h" value="#000000" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

						<!-- TEXT OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:5px"></i>
						<input class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-2-h" value="1">
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						
						<!-- BORDER -->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Border",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<!-- BORDER COLOR -->					
						<input type="text" class="rs-layer-input-field  my-color-field" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>" style="width:150px" name="adbutton-border-color-h" value="#000000" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

						<!-- BORDER OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="adb-input rs-layer-input-field " title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:45px" type="text" name="adbutton-border-opacity-h" value="1">
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<!-- BORDER WIDTH-->
						<i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon " title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="adb-input text-sidebar rs-layer-input-field " title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="width:45px" type="text" name="adbutton-border-width-h" value="0">
						<div style="width:100%;height:5px"></div>
					</div>
					
					
				</div>
				<div class="adb-configs">	
					<div class="add-togglebtn"><span class="addbutton-title"><?php _e("Text",REVSLIDER_TEXTDOMAIN); ?></span><span class="adb-toggler eg-icon-plus"></span></div>
					<div class="add-intern-accordion" style="display:none">						
						<input class="adb-input text-sidebar rs-layer-input-field " style="width:100%" type="text" name="adbutton-text" value="Click Here">						
					</div>
				</div>

			</div>
		</div>
	</div>



	<!-- THE shape DIALOG WINDOW -->
	<div id="dialog_addshape" class="dialog-addshape" title="<?php _e("Add Shape Layer",REVSLIDER_TEXTDOMAIN); ?>" style="display:none">
		<div class="addbuton-dialog-inner">
			<div id="addshape-examples">
				<div class="addbe-title-row">					
					<span class="addshape-bg-light"></span>
					<span class="addshape-bg-dark"></span>
					<span class="addshape-title"><?php _e("Click your Shape below to add it",REVSLIDER_TEXTDOMAIN); ?></span>
				</div>
				<div class="addshape-examples-wrapper">
					
				</div>

			</div>
			<div id="addshape-settings">
				<div class="adb-configs" style="padding-top:0px">
					<!-- TITLE -->
					<span class="addshape-title"><?php _e("Shape Settings",REVSLIDER_TEXTDOMAIN); ?></span>
					<div class="add-intern-accordion" style="display:block">	
						<!-- COLOR 1 -->
						<div class="add-lbl-wrapper">
						<label><?php _e("Background",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<!-- COLOR -->					
						<input type="text" class="rs-layer-input-field my-color-field" style="width:150px" name="adshape-color-1" value="#000000" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<!-- OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " style="margin-right:5px"></i>
						<input data-suffix="" class="ads-input rs-layer-input-field "  style="width:45px" type="text" name="adshape-opacity-1" value="0.5">
						
						<!-- BORDER -->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Border",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					

						<!-- BORDER COLOR -->					
						<input type="text" class="rs-layer-input-field  my-color-field" title="<?php _e("Border Color",REVSLIDER_TEXTDOMAIN); ?>" style="width:150px" name="adshape-border-color" value="#000000" />
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

						<!-- BORDER OPACITY -->
						<i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="ads-input rs-layer-input-field " title="<?php _e("Border Opacity",REVSLIDER_TEXTDOMAIN); ?>" style="width:45px" type="text" name="adshape-border-opacity" value="0.5">
						<span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

						<!-- BORDER WIDTH-->
						<i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon " title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:5px"></i>
						<input class="ads-input text-sidebar rs-layer-input-field " title="<?php _e("Border Width",REVSLIDER_TEXTDOMAIN); ?>" style="width:45px" type="text" name="adshape-border-width" value="0">
						<div style="width:100%;height:5px"></div>	


						<!-- BORDER RADIUS-->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Border Radius",REVSLIDER_TEXTDOMAIN); ?></label>
						</div>					
						<i class="rs-mini-layer-icon rs-icon-borderradius rs-toolbar-icon"  style="margin-right:10px"></i>
						<input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
						<input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
						<input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
						<input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
						
						<!-- SIZE OF SHAPE-->
						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Width",REVSLIDER_TEXTDOMAIN); ?></label>
						<span class="rs-layer-toolbar-space" style="margin-right:30px"></span>
						<label><?php _e("Full-Width",REVSLIDER_TEXTDOMAIN); ?></label> 
						</div>				
						<input class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_width" value="200">
						<span class="rs-layer-toolbar-space" style="margin-right:13px"></span>						
						<input type="checkbox" name="shape_fullwidth" class="tp-moderncheckbox"/>

						<div style="width:100%;height:5px"></div>
						<div class="add-lbl-wrapper">
						<label><?php _e("Height",REVSLIDER_TEXTDOMAIN); ?></label>
						<span class="rs-layer-toolbar-space" style="margin-right:30px"></span>
						<label><?php _e("Full-Height",REVSLIDER_TEXTDOMAIN); ?></label> 
						</div>				
						<input class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_height" value="200">
						<span class="rs-layer-toolbar-space" style="margin-right:13px"></span>						
						<input type="checkbox" name="shape_fullheight" class="tp-moderncheckbox"/>

						<div class="shape_padding">
							<!-- SIZE OF SHAPE-->
							<div style="width:100%;height:5px"></div>
							<div class="add-lbl-wrapper">
								<label><?php _e("Padding",REVSLIDER_TEXTDOMAIN); ?></label>
							</div>
							<i class="rs-mini-layer-icon rs-icon-padding rs-toolbar-icon" title="<?php _e("Padding",REVSLIDER_TEXTDOMAIN); ?>" style="margin-right:10px"></i>
							<input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Top",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
							<input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Right",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
							<input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Bottom",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
							<input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php _e("Padding Left",REVSLIDER_TEXTDOMAIN); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
							
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
	
	<div id="dialog-change-style-from-css" title="<?php _e('Apply Styles to Selection', REVSLIDER_TEXTDOMAIN) ?>" style="display:none;width:275px">
				
		<div style="margin-top:3px;margin-bottom:13px;">
			<div class="rs-style-device-wrap"><div data-type="desktop" class="rs-style-device_selector_prev rs-preview-ds-desktop selected"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="desktop" checked="checked" /></div>
		<?php
		//check if advanced responsive size is enabled and which ones are
		if($adv_resp_sizes === true){
			if($enable_custom_size_notebook == 'on'){ ?><div class="rs-style-device-wrap"><div data-type="notebook" class="rs-style-device_selector_prev rs-preview-ds-notebook"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="notebook" checked="checked" /></div><?php }
			if($enable_custom_size_tablet == 'on'){ ?><div class="rs-style-device-wrap"><div data-type="tablet" class="rs-style-device_selector_prev rs-preview-ds-tablet"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="tablet" checked="checked" /></div><?php }
			if($enable_custom_size_iphone == 'on'){ ?><div class="rs-style-device-wrap"><div data-type="mobile" class="rs-style-device_selector_prev rs-preview-ds-mobile"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="mobile" checked="checked" /></div><?php }
		}
		?>
		</div>
		
		<p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="color" checked="checked" /><?php _e('Color', REVSLIDER_TEXTDOMAIN); ?></p>
		<p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="font-size" checked="checked" /><?php _e('Font Size', REVSLIDER_TEXTDOMAIN); ?></p>
		<p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="line-height" checked="checked" /><?php _e('Line Height', REVSLIDER_TEXTDOMAIN); ?></p>
		<p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="font-weight" checked="checked" /><?php _e('Font Weight', REVSLIDER_TEXTDOMAIN); ?></p>
		<p style="margin:20px 0px 0px 0px;font-size:13px;color:#999;font-style:italic"><?php _e('Advanced Styles will alwys be applied to all Device Sizes.', REVSLIDER_TEXTDOMAIN); ?></p>
	</div>
	
	
<script type="text/html" id="tmpl-rs-action-layer-wrap">
	<li class="layer_action_row layer_action_wrap">
		<# if(data['edit'] == true){ #>
		<div class="remove-action-row">
			<i class="eg-icon-minus"></i>
		</div>
		<# }else{ #>
		
		<# } #>
		
		<select name="<# if(data['edit'] == false){ #>no_<# } #>layer_tooltip_event[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:100px; margin-right:30px;">
			<option <# if( data['tooltip_event'] == 'click' ){ #>selected="selected" <# } #>value="click"><?php _e("Click",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['tooltip_event'] == 'mouseenter' ){ #>selected="selected" <# } #>value="mouseenter"><?php _e("Mouse Enter",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['tooltip_event'] == 'mouseleave' ){ #>selected="selected" <# } #>value="mouseleave"><?php _e("Mouse Leave",REVSLIDER_TEXTDOMAIN); ?></option>
		</select>
		
		<select name="<# if(data['edit'] == false){ #>no_<# } #>layer_action[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>layer_actions rs-layer-input-field" style="width:150px; margin-right:30px;">						
			<option <# if( data['action'] == 'none' ){ #>selected="selected" <# } #>value="none"><?php _e("Disabled",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'link' ){ #>selected="selected" <# } #>value="link"><?php _e("Simple Link",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'jumpto' ){ #>selected="selected" <# } #>value="jumpto"><?php _e("Jump to Slide",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'next' ){ #>selected="selected" <# } #>value="next"><?php _e("Next Slide",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'prev' ){ #>selected="selected" <# } #>value="prev"><?php _e("Previous Slide",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'pause' ){ #>selected="selected" <# } #>value="pause"><?php _e("Pause Slider",REVSLIDER_TEXTDOMAIN); ?></option>								
			<option <# if( data['action'] == 'resume' ){ #>selected="selected" <# } #>value="resume"><?php _e("Play Slider",REVSLIDER_TEXTDOMAIN); ?></option>																
			<option <# if( data['action'] == 'toggle_slider' ){ #>selected="selected" <# } #>value="toggle_slider"><?php _e("Toggle Slider",REVSLIDER_TEXTDOMAIN); ?></option>																
			<option <# if( data['action'] == 'callback' ){ #>selected="selected" <# } #>value="callback"><?php _e("CallBack",REVSLIDER_TEXTDOMAIN); ?></option>												
			<option <# if( data['action'] == 'scroll_under' ){ #>selected="selected" <# } #>value="scroll_under"><?php _e("Scroll Below Slider",REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'start_in' ){ #>selected="selected" <# } #>value="start_in"><?php _e('Start Layer "in" Animation',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'start_out' ){ #>selected="selected" <# } #>value="start_out"><?php _e('Start Layer "out" Animation',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'toggle_layer' ){ #>selected="selected" <# } #>value="toggle_layer"><?php _e('Toggle Layer Animation',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'start_video' ){ #>selected="selected" <# } #>value="start_video"><?php _e('Start Video',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'stop_video' ){ #>selected="selected" <# } #>value="stop_video"><?php _e('Stop Video',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'toggle_video' ){ #>selected="selected" <# } #>value="toggle_video"><?php _e('Toggle Video',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'simulate_click' ){ #>selected="selected" <# } #>value="simulate_click"><?php _e('Simulate Click',REVSLIDER_TEXTDOMAIN); ?></option>
			<option <# if( data['action'] == 'toggle_class' ){ #>selected="selected" <# } #>value="toggle_class"><?php _e('Toggle Layer Class',REVSLIDER_TEXTDOMAIN); ?></option>
		</select>

		<!-- SIMPLE LINK PARAMETERS -->
		<span class="action-link-wrapper" style="display:none;">
			<span><?php _e("Link Url",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space"></span>
			<input type="text" style="width:150px;margin-right:30px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_image_link[]" value="{{ data['image_link'] }}">

			<span><?php _e("Link Target",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space"></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>layer_link_open_in[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px;margin-right:30px;">
				<option <# if( data['link_open_in'] == '_same' ){ #>selected="selected" <# } #>value="_self"><?php _e("Same Window",REVSLIDER_TEXTDOMAIN); ?></option>
				<option <# if( data['link_open_in'] == '_blank' ){ #>selected="selected" <# } #>value="_blank"><?php _e("New Window",REVSLIDER_TEXTDOMAIN); ?></option>
			</select>
			
			<span><?php _e("Link Type",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space"></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>layer_link_type[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
				<option <# if( data['link_type'] == 'jquery' ){ #>selected="selected" <# } #>value="jquery"><?php _e("jQuery Link",REVSLIDER_TEXTDOMAIN); ?></option>
				<option <# if( data['link_type'] == 'a' ){ #>selected="selected" <# } #>value="a"><?php _e("a Tag Link",REVSLIDER_TEXTDOMAIN); ?></option>
			</select>
		</span>

		<!-- JUMP TO SLIDE -->
		<span class="action-jump-to-slide" style="display:none;">
			<span><?php _e("Jump To",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space"></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>jump_to_slide[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px" data-selectoption="{{ data['jump_to_slide'] }}">
			</select>

		</span>

		<!-- SCROLL OFFSET -->
		<span class="action-scrollofset" style="display:none;">						
			<span><?php _e("Scroll Offset",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space" ></span>
			<input type="text" style="width:125px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_scrolloffset[]" value="{{ data['scrolloffset'] }}">						
		</span>

		<!-- CALLBACK FUNCTION-->
		<span class="action-callback" style="display:none;">						
			<span><?php _e("Function",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space" ></span>
			<input type="text" style="width:250px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_actioncallback[]" value="{{ data['actioncallback'] }}">						
		</span>

		<span class="action-target-layer" style="display:none;">
			<span><?php _e("Target",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space"></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>layer_target[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:100px;margin-right:30px;" data-selectoption="{{ data['layer_target'] }}">
			</select>
			<span><?php _e("Delay",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space"></span>
			<input type="text" style="width:60px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field" name="<# if(data['edit'] == false){ #>no_<# } #>layer_action_delay[]" value="{{ data['action_delay'] }}"> <?php _e('ms', REVSLIDER_TEXTDOMAIN); ?>
		</span>		

		<span class="action-toggle_layer" style="display:none;">
			<span class="rs-layer-toolbar-space"></span>
			<span><?php _e("at Start",REVSLIDER_TEXTDOMAIN); ?></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>toggle_layer_type[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
				<option <# if( data['toggle_layer_type'] == 'visible' ){ #>selected="selected" <# } #>value="visible"><?php _e("Play In Animation",REVSLIDER_TEXTDOMAIN); ?></option>
				<option <# if( data['toggle_layer_type'] == 'hidden' ){ #>selected="selected" <# } #>value="hidden"><?php _e("Keep Hidden",REVSLIDER_TEXTDOMAIN); ?></option>
			</select>
		</span>	

		<!-- CALLBACK FUNCTION-->
		<span class="action-toggleclass" style="display:none;">	
			<span class="rs-layer-toolbar-space"></span>
			<span><?php _e("Class",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space" ></span>
			<input type="text" style="width:100px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_toggleclass[]" value="{{ data['toggle_class'] }}">
		</span>
		
		<span class="action-triggerstates" style="display: none; white-space:nowrap">
			<span class="rs-layer-toolbar-space"></span>
			<span><?php _e("Animation Timing",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space" ></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>do-layer-animation-overwrite[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
				<option value="default"><?php _e("In and Out Animation Default",REVSLIDER_TEXTDOMAIN); ?></option>
				<option value="waitout"><?php _e("In Animation Default and Out Animation Wait for Trigger",REVSLIDER_TEXTDOMAIN); ?></option>
				<option value="wait"><?php _e("Wait for Trigger",REVSLIDER_TEXTDOMAIN); ?></option>
			</select>
			<span class="rs-layer-toolbar-space" ></span>
			<span><?php _e("Trigger Memory",REVSLIDER_TEXTDOMAIN); ?></span>
			<span class="rs-layer-toolbar-space" ></span>
			<select name="<# if(data['edit'] == false){ #>no_<# } #>do-layer-trigger-memory[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
				<option value="reset"><?php _e("Reset Animation and Trigger States every loop",REVSLIDER_TEXTDOMAIN); ?></option>
				<option value="keep"><?php _e("Keep last selected State",REVSLIDER_TEXTDOMAIN); ?></option>
			</select>
		</span>
	</li>
</script>

	<script>
		// CHANGE STYLE OF EXAMPLE BUTTONS ON DEMAND
		// RGBA HEX CALCULATOR
		var local_cHex = function(hex,o){	
			o = parseFloat(o);
		    hex = hex.replace('#','');	    
		    var r = parseInt(hex.substring(0,2), 16),
		    	g = parseInt(hex.substring(2,4), 16),
		    	b = parseInt(hex.substring(4,6), 16),
				result = 'rgba('+r+','+g+','+b+','+o+')';
		    return result;
		}

		var getButtonExampleValues = function() {
			var o = new Object();
			o.bgc = local_cHex(jQuery('input[name="adbutton-color-1"]').val(), jQuery('input[name="adbutton-opacity-1"]').val());
			o.col = local_cHex(jQuery('input[name="adbutton-color-2"]').val(), jQuery('input[name="adbutton-opacity-2"]').val());
			o.borc = local_cHex(jQuery('input[name="adbutton-border-color"]').val(), jQuery('input[name="adbutton-border-opacity"]').val());
			o.borw = parseInt(jQuery('input[name="adbutton-border-width"]').val(),0)+"px";
			o.borwh = parseInt(jQuery('input[name="adbutton-border-width-h"]').val(),0)+"px";
			o.bgch = local_cHex(jQuery('input[name="adbutton-color-1-h"]').val(), jQuery('input[name="adbutton-opacity-1-h"]').val());
			o.colh = local_cHex(jQuery('input[name="adbutton-color-2-h"]').val(), jQuery('input[name="adbutton-opacity-2-h"]').val());
			o.borch = local_cHex(jQuery('input[name="adbutton-border-color-h"]').val(), jQuery('input[name="adbutton-border-opacity-h"]').val());
			o.ff = jQuery('input[name="adbutton-fontfamily"]').val();
			return o;
		}

		var setExampleButtons = function() {
			var c = jQuery('#addbutton-examples');
			c.find('.rev-btn').each(function() {
				var b = jQuery(this),
					o = getButtonExampleValues();
								
				b.css({backgroundColor:o.bgc,
					   color:o.col,
					   fontFamily:o.ff});
				
				b.find('i').css({color:o.col});


				if (b.hasClass("rev-bordered"))
					b.css({borderColor:o.borc,borderWidth:o.borw,borderStyle:"solid"})

				if (b.find('i').length>0) {
					b.find('i').remove();
					b.html(jQuery('input[name="adbutton-text"]').val());
					b.append(jQuery('.addbutton-icon').html());					
				} else {					
					b.html(jQuery('input[name="adbutton-text"]').val());
				}

				b.unbind('hover');
				b.hover(function() {
					var b = jQuery(this),
					o = getButtonExampleValues();				
					b.css({backgroundColor:o.bgch,color:o.colh});
					b.find('i').css({color:o.colh});					
					if (b.hasClass("rev-bordered"))
						b.css({borderColor:o.borch,borderWidth:o.borwh,borderStyle:"solid"});
				},
				function() {
					var b = jQuery(this),
					o = getButtonExampleValues();				
					b.css({backgroundColor:o.bgc,color:o.col});
					b.find('i').css({color:o.col});					
					if (b.hasClass("rev-bordered"))
						b.css({borderColor:o.borc,borderWidth:o.borw,borderStyle:"solid"});
				})

			})
		}

		var setExampleShape = function() {
			var p = jQuery('.addshape-examples-wrapper'),
				o = new Object();
			
			o.bgc = local_cHex(jQuery('input[name="adshape-color-1"]').val(), jQuery('input[name="adshape-opacity-1"]').val());
			o.w = parseInt(jQuery('input[name="shape_width"]').val(),0);
			o.h = parseInt(jQuery('input[name="shape_height"]').val(),0);
			o.borc = local_cHex(jQuery('input[name="adshape-border-color"]').val(), jQuery('input[name="adshape-border-opacity"]').val());
			o.borw = parseInt(jQuery('input[name="adshape-border-width"]').val(),0)+"px";			
			o.fw = jQuery('input[name="shape_fullwidth"]').is(':checked');
			o.fh = jQuery('input[name="shape_fullheight"]').is(':checked');	
			o.br = "";

			if (o.fw) {
				o.w = "100%";
				o.l = "0px";
				o.ml = "0px";
				jQuery('input[name="shape_width"]').attr("disabled","disabled");
			} else {
				o.w = parseInt(o.w,0)+"px";
				o.l="50%";
				o.ml = 0 - parseInt(o.w,0)/2;
				jQuery('input[name="shape_width"]').removeAttr("disabled");				
			}

			if (o.fh) {
				o.h = "100%";
				o.t = "0px";
				o.mt = "0px";
				jQuery('input[name="shape_height"]').attr("disabled","disabled");				
			} else {
				o.h = parseInt(o.h,0)+"px";
				o.t = "50%";
				o.mt = 0 - parseInt(o.h,0)/2;
				jQuery('input[name="shape_height"]').removeAttr("disabled");				
			}

			jQuery('input[name="shape_border-radius[]"]').each(function(i){		
				var t = jQuery.isNumeric(jQuery(this).val()) ? parseInt(jQuery(this).val(),0)+"px" : jQuery(this).val();
				o.br = o.br + t;
				o.br = i<3 ? o.br+" ":o.br;
			});
			o.pad="";
			if (o.fh && o.fw) {
				jQuery('input[name="shape_padding[]"]').removeAttr("disabled");
				jQuery('input[name="shape_padding[]"]').each(function(i){
					var t = jQuery.isNumeric(jQuery(this).val()) ? parseInt(jQuery(this).val(),0)+"px" : jQuery(this).val();
					o.pad = o.pad + t;
					o.pad = i<3 ? o.pad+" ":o.pad;

				});
			} else {
				jQuery('input[name="shape_padding[]"]').attr("disabled","disabled");
				o.pad="0";
				
			}

			
						
			
			if (p.find('.example-shape').length==0)
				p.append('<div class="example-shape-wrapper"><div class="example-shape"></div></div>');
			var e = p.find('.example-shape');

			e.css({backgroundColor:o.bgc, 
				   padding:o.pad,				   
				   borderStyle:"solid", borderWidth:o.borw, borderColor:o.borc, borderRadius:o.br});
			e.parent().css({
					top:o.t, left:o.l, marginLeft:o.ml,marginTop:o.mt,
				  	width:o.w, height:o.h,
					padding:o.pad
			})
			RevSliderSettings.onoffStatus(jQuery('input[name="shape_fullwidth"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="shape_fullheight"]'));
		}

		

		jQuery(document).ready(function() {

			jQuery('.quick-layers-list').perfectScrollbar({wheelPropagation:false});

			// MANAGE BG COLOR OF DIALOG BOXES
			jQuery('.addbutton-bg-dark').click(function() { jQuery('#addbutton-examples').css({backgroundColor:"#333333"});})
			jQuery('.addbutton-bg-light').click(function() { jQuery('#addbutton-examples').css({backgroundColor:"#eeeeee"});})
			jQuery('.addshape-bg-dark').click(function() { jQuery('#addshape-examples').css({backgroundColor:"#333333"});})
			jQuery('.addshape-bg-light').click(function() { jQuery('#addshape-examples').css({backgroundColor:"#eeeeee"});})
			
			// ADD BUTTON DIALOG RELEVANT FUNCTIONS
			jQuery('.addbutton-examples-wrapper').perfectScrollbar({wheelPropagation:true});
			jQuery('.add-togglebtn').click(function() {
				var aia = jQuery(this).parent().find('.add-intern-accordion');
				aia.addClass("nowactive");
				jQuery('.add-intern-accordion').each(function() {
					if (!jQuery(this).hasClass("nowactive")) jQuery(this).slideUp(200);
				});
				jQuery('.adb-toggler').removeClass("eg-icon-minus").addClass("eg-icon-plus");
				aia.slideDown(200);
				jQuery(this).find('.adb-toggler').addClass("eg-icon-minus").removeClass("eg-icon-plus");
				aia.removeClass("nowactive");
			})


			jQuery('body').on("click","fake-select-i, #fake-select-label" ,function() {
				var tab = jQuery('#slide-animation-settings-content-tab');
				tab.click();
				jQuery("html, body").animate({scrollTop:(tab.offset().top-200)+"px"},{duration:400});
			})

			jQuery('.master-rightcell .layers-wrapper, #divLayers-wrapper').perfectScrollbar({wheelPropagation:true, suppressScrollY:true});
			jQuery('.master-leftcell .layers-wrapper').perfectScrollbar({suppressScrollX:true});

			

			var bawi = jQuery('#thelayer-editor-wrapper').outerWidth(true)-2;
			//jQuery('.master-rightcell').css({maxWidth:bawi-222});
			jQuery('#mastertimer-wrapper').css({maxWidth:bawi});
			jQuery('.layers-wrapper').css({maxWidth:bawi-222});
			var scrint;

			jQuery('.master-rightcell, .master-leftcell').hover(function() {
				jQuery(this).addClass("overtoscroll");
			}, function() {
				jQuery(this).removeClass("overtoscroll");
			})


			jQuery('.master-rightcell .layers-wrapper').on("scroll",function() {
				if (jQuery('.master-rightcell').hasClass("overtoscroll")) {

					var ts = jQuery(this).scrollTop();
					jQuery('.master-leftcell .layers-wrapper').scrollTop(ts);

					clearTimeout(scrint);
					var ts = jQuery(this).scrollTop(),
						ls = jQuery('.master-rightcell .layers-wrapper').scrollLeft();

					jQuery('#master-rightheader').css({left:(0-ls)});
					jQuery(this).scrollLeft(ls);
					jQuery('.layers-wrapper').scrollTop(ts);
					scrint = setTimeout(function() {
						var ls = jQuery('.master-rightcell .layers-wrapper').scrollLeft();
						jQuery('#master-rightheader').css({left:(0-ls)});
						jQuery('.layers-wrapper').scrollTop(ts);
					},50);
				}
			});

			jQuery('.master-leftcell .layers-wrapper').on("scroll",function() {
				if (!jQuery('.master-rightcell').hasClass("overtoscroll")) {
					clearTimeout(scrint);
					var ts = jQuery(this).scrollTop();
					jQuery('.master-rightcell .layers-wrapper').perfectScrollbar('update').scrollTop(ts);
					jQuery('.master-rightcell .ps-scrollbar-x-rail').css({visibility:"hidden"});
					scrint = setTimeout(function() {
						jQuery('.master-rightcell .ps-scrollbar-x-rail').css({visibility:"visible"});
					},50);
				}
			});



			jQuery(window).resize(function() {
				var bawi = jQuery('#thelayer-editor-wrapper').outerWidth(true)-2;
				//jQuery('.master-rightcell').css({maxWidth:bawi-222});
				jQuery('#mastertimer-wrapper').css({maxWidth:bawi});
				jQuery('.layers-wrapper').css({maxWidth:bawi-222});
				jQuery('.master-rightcell .layers-wrapper, #divLayers-wrapper').perfectScrollbar("update");
			});

			jQuery('#mastertimer-wrapper').resizable({
				handles:"s",
				minHeight:102,
				//alsoResize:".layers-wrapper",
				start:function() {
					jQuery('.master-rightcell .layers-wrapper').perfectScrollbar("destroy");
				},
				resize:function() {
					jQuery('.layers-wrapper').height(jQuery('#mastertimer-wrapper').height()-50);
				},
				stop:function() {
					jQuery('.layers-wrapper').height(jQuery('#mastertimer-wrapper').height()-40);
					jQuery('.master-rightcell .layers-wrapper').perfectScrollbar({wheelPropagation:true});
					jQuery('.master-leftcell .layers-wrapper').perfectScrollbar({wheelPropagation:true, suppressScrollX:true});

				}
			});
			
			UniteAdminRev.initVideoDef();
		});
	</script>
</div>