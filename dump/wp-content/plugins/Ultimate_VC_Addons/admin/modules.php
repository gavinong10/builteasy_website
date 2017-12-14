<?php
	if(isset($_GET['author']))
		$author = true;
	else
		$author = false;
?>
<div class="wrap about-wrap">
	<h1><?php echo __("Ultimate Addons Settings","ultimate_vc"); ?></h1>
    <div class="about-text"><?php echo __('Enable or disable the features as per your preference.','ultimate_vc'); ?></div>
    <div class="ult-badge" style="background:url(<?php echo plugins_url('img/brainstorm-logo.png',__FILE__); ?>) no-repeat top center; background-size: 150px;"></div>
    <div id="msg"></div>
    <div id="bsf-message"></div>
    <h2 class="nav-tab-wrapper"> 
    	<a href="#ultimate-modules" data-tab="ultimate-modules" class="nav-tab nav-tab-active"> <?php echo __('Modules','ultimate_vc'); ?> </a> 
        <a href="#css-settings" data-tab="css-settings" class="nav-tab"> <?php echo __('Scripts and Styles','ultimate_vc'); ?> </a>
        <a href="#ultimate-settings" data-tab="ultimate-settings" class="nav-tab"> <?php echo __('Advanced Settings','ultimate_vc'); ?> </a>
        <?php if($author) : ?>
			<a href="#ultimate-debug" data-tab="ultimate-debug" class="nav-tab"> Debug </a> 
		<?php endif; ?>
    </h2>
    <?php
	$ultimate_modules = get_option('ultimate_modules');
	$ultimate_row = get_option('ultimate_row');
	$ultimate_animation = get_option('ultimate_animation');
	$ultimate_smooth_scroll = get_option('ultimate_smooth_scroll');
	
	$ultimate_css = get_option('ultimate_css');
	$ultimate_js = get_option('ultimate_js');
	
	$checked = '';
	if($ultimate_row == "enable"){
		$checked_row = 'checked="checked"';
	} else {
		$checked_row = '';
	}
	
	if($ultimate_animation == "enable"){
		$ultimate_animation = 'checked="checked"';
	} else {
		$ultimate_animation = '';
	}
	
	if($ultimate_smooth_scroll == "enable"){
		$ultimate_smooth_scroll = 'checked="checked"';
	} else {
		$ultimate_smooth_scroll = '';
	}
	
	if($ultimate_css == "enable"){
		$ultimate_css = 'checked="checked"';
	} else {
		$ultimate_css = '';
	}
	
	if($ultimate_js == "enable"){
		$ultimate_js = 'checked="checked"';
	} else {
		$ultimate_js = '';
	}
	$modules = array(
		'Ultimate_Animation' => 'Animation Block',
		'Ultimate_Buttons' => 'Advanced Buttons',
		'Ultimate_CountDown' => 'Count Down Timer',
		'Ultimate_Flip_Box' => 'Flip Boxes',
		'Ultimate_Google_Maps' => 'Google Maps',
		'Ultimate_Google_Trends' => 'Google Trends',
		'Ultimate_Headings' => 'Headings',
		'Ultimate_Icon_Timeline' => 'Timeline',
		'Ultimate_Info_Box' => 'Info Boxes',
		'Ultimate_Info_Circle' => 'Info Circle',
		'Ultimate_Info_List' => 'Info List',
		'Ultimate_Info_Tables' => 'Info Tables',
		'Ultimate_Interactive_Banners' => 'Interactive Banners',
		'Ultimate_Interactive_Banner_2' => 'Interactive Banners - 2',
		'Ultimate_Modals' => 'Modal Popups',
		'Ultimate_Pricing_Tables' => 'Price Box',
		'Ultimate_Spacer' => 'Spacer / Gap',
		'Ultimate_Stats_Counter' => 'Stats Counter',
		'Ultimate_Swatch_Book' => 'Swatch Book',
		'Ultimate_Icons' => 'Icons',
		'Ultimate_List_Icon' => 'List Icons',
		'Ultimate_Carousel'  => 'Advanced Carousel',
		'Ultimate_Fancy_Text'  => 'Fancy Text',
		'Ultimate_Hightlight_Box'  => 'Highlight Box',
		'Ultimate_Info_Banner' => 'Info Banner',
		'Ultimate_iHover'  => 'iHover',
		'Ultimate_Hotspot'  => 'Hotspot',
		'Ultimate_Video_Banner'  => 'Video Banner',
		'WooComposer' => 'WooComposer',
		'Ultimate_Dual_Button' => 'Dual Button',
		'Ultimate_link' => 'Creative Link',
		'Ultimate_Image_Separator' => 'Image Separator',
		'Ultimate_Content_Box' => 'Content Box',
		'Ultimate_Expandable_section' => 'Expandable Section',
		'Ultimate_Tab' =>'Advanced Tabs',
	);
	?>
    <div id="ultimate-modules" class="ult-tabs active-tab">
    <br/>
    <input type="checkbox" id="ult-all-modules-toggle" data-all="<?php echo count($modules) ?>" value="checkall" /> <label for="ult-all-modules-toggle"><?php echo __('Enable/Disable All', 'ultimate_vc') ?></label>
    <form method="post" id="ultimate_modules">
    	<input type="hidden" name="action" value="update_ultimate_modules" />
    	<table class="form-table">
        	<tbody>
            	<?php
					$i = 1;
					$checked_items = 0; 
					foreach($modules as $module => $label){
						if(is_array($ultimate_modules) && !empty($ultimate_modules)){ 
							if(in_array(strtolower($module),$ultimate_modules)){
								$checked = 'checked="checked"';
								$checked_items++;
							} else {
								$checked = '';
							}
						}
						if(($i % 2) == 1){
						?>
						<tr valign="top" style="border-bottom: 1px solid #ddd;">
						<?php } ?>
							<th scope="row"><?php echo $label; ?></th>
							<td> 
							<div class="onoffswitch">
								<input type="checkbox" <?php echo $checked; ?> class="onoffswitch-checkbox" value="<?php echo strtolower($module); ?>" id="<?php echo strtolower($module); ?>" name="ultimate_modules[]" />
								
								<label class="onoffswitch-label" for="<?php echo strtolower($module); ?>">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
							</div>
							</td>
						<?php if(($i % 2) == 1){ ?>
						<!-- </tr> -->
						<?php } ?>
                <?php $i++; } ?>
            </tbody>
        </table>
    </form>
	<p class="submit"><input type="submit" name="submit" id="submit-modules" class="button button-large button-primary" value="<?php echo __("Save Changes","ultimate_vc");?>"></p>
    </div>
    
    <div id="ultimate-settings" class="ult-tabs">
    <form method="post" id="ultimate_settings">
    	<input type="hidden" name="action" value="update_ultimate_options" />
    	<table class="form-table">
        	<tbody>
                <tr valign="top">
                	<th scope="row"><?php echo __("Row backgrounds","ultimate_vc"); ?></th>
                    <td> <div class="onoffswitch">
                    <input type="checkbox" <?php echo $checked_row; ?> id="ultimate_row" value="enable" class="onoffswitch-checkbox" name="ultimate_row" />
                         <label class="onoffswitch-label" for="ultimate_row">
                            <div class="onoffswitch-inner">
                                <div class="onoffswitch-active">
                                    <div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
                                </div>
                                <div class="onoffswitch-inactive">
                                    <div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
                                </div>
                            </div>
                        </label>
                        </div>
					</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><?php echo __("Animation Block on Mobile","ultimate_vc"); ?></th>
                    <td> <div class="onoffswitch">
                    <input type="checkbox" <?php echo $ultimate_animation; ?> id="ultimate_animations" value="enable" class="onoffswitch-checkbox" name="ultimate_animation" />
                         <label class="onoffswitch-label" for="ultimate_animations">
                            <div class="onoffswitch-inner">
                                <div class="onoffswitch-active">
                                    <div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
                                </div>
                                <div class="onoffswitch-inactive">
                                    <div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
                                </div>
                            </div>
                        </label>
                         </div>
					</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><?php echo __("Smooth Scroll","ultimate_vc"); ?></th>
                    <td> <div class="onoffswitch">
                    <input type="checkbox" <?php echo $ultimate_smooth_scroll; ?> id="ultimate_smooth_scroll" value="enable" class="onoffswitch-checkbox" name="ultimate_smooth_scroll" />
                         <label class="onoffswitch-label" for="ultimate_smooth_scroll">
                            <div class="onoffswitch-inner">
                                <div class="onoffswitch-active">
                                    <div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
                                </div>
                                <div class="onoffswitch-inactive">
                                    <div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
                                </div>
                            </div>
                        </label>
                         </div>
					</td>
                </tr>
            </tbody>
        </table>
    </form>
	<p class="submit"><input type="submit" name="submit" id="submit-settings" class="button button-large button-primary" value="<?php echo __("Save Changes","ultimate");?>"></p>
    </div>
    <div id="css-settings" class="ult-tabs">
        <form method="post" id="css_settings">
            <input type="hidden" name="action" value="update_css_options" />
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php echo __("Optimized CSS","ultimate_vc"); ?></th>
                        <td> <div class="onoffswitch">
                        <input type="checkbox" <?php echo $ultimate_css; ?> id="ultimate_css" value="enable" class="onoffswitch-checkbox" name="ultimate_css" />
                             <label class="onoffswitch-label" for="ultimate_css">
                                <div class="onoffswitch-inner">
                                    <div class="onoffswitch-active">
                                        <div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
                                    </div>
                                    <div class="onoffswitch-inactive">
                                        <div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
                                    </div>
                                </div>
                            </label>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __("Optimized JS","ultimate_vc"); ?></th>
                        <td> <div class="onoffswitch">
                        <input type="checkbox" <?php echo $ultimate_js; ?> id="ultimate_js" value="enable" class="onoffswitch-checkbox" name="ultimate_js" />
                             <label class="onoffswitch-label" for="ultimate_js">
                                <div class="onoffswitch-inner">
                                    <div class="onoffswitch-active">
                                        <div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
                                    </div>
                                    <div class="onoffswitch-inactive">
                                        <div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
                                    </div>
                                </div>
                            </label>
                             </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <p class="submit"><input type="submit" name="submit" id="submit-css-settings" class="button button-large button-primary" value="<?php echo __("Save Changes","ultimate_vc");?>"></p>
    </div>
    <?php if($author) : ?>
        <div id="ultimate-debug" class="ult-tabs">
            <form method="post" id="ultimate_debug_settings">
            	<input type="hidden" name="action" value="update_ultimate_debug_options" />
            	<table class="form-table">
        			<tbody>
                    	<tr>
                        	<th scope="row"><?php echo __('Theme', 'ultimate_vc') ?></th>
                            <td>
								<?php 
									$site_theme = wp_get_theme();
									$current_theme = $site_theme->get( 'Name' );
									echo $current_theme;
								?>
                          	</td>
                        </tr>
                    	<?php
							$ultimate_video_fixer = get_option('ultimate_video_fixer');
							if($ultimate_video_fixer == 'enable')
								$ultimate_video_fixer = 'checked="checked"';
						?>
						<tr valign="top">
							<th scope="row"><?php echo __("Video Fixer","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch">
							<input type="checkbox" <?php echo $ultimate_video_fixer; ?> id="ultimate_video_fixer" value="enable" class="onoffswitch-checkbox" name="ultimate_video_fixer" />
								 <label class="onoffswitch-label" for="ultimate_video_fixer">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>
                        
                        <?php
							$ultimate_ajax_theme = get_option('ultimate_ajax_theme');
							if($ultimate_ajax_theme == 'enable')
								$ultimate_ajax_theme = 'checked="checked"';
						?>
						<tr valign="top">
							<th scope="row"><?php echo __("Ajax","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch">
							<input type="checkbox" <?php echo $ultimate_ajax_theme; ?> id="ultimate_ajax_theme" value="enable" class="onoffswitch-checkbox" name="ultimate_ajax_theme" />
								 <label class="onoffswitch-label" for="ultimate_ajax_theme">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>
                        <tr valign="top">
							<th scope="row"><?php echo __("Delete Fonts","ultimate"); ?></th>
							<td> 
                            	<a href="<?php echo admin_url('admin.php?page=font-icon-Manager&delete-bsf-fonts'); ?>" target="_blank" class="button-primary">Delete Fonts</a>
							</td>
						</tr>
                        <?php
							$ultimate_theme_support = get_option('ultimate_theme_support');
							$theme_dependant = '';
							if($ultimate_theme_support == 'enable')
							{
								$ultimate_theme_support = 'checked="checked"';
								$theme_dependant = 'display:none;';
							}
						?>
                        <tr valign="top">
							<th scope="row"><?php echo __("Theme Support","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch" id="ult-theme-support-row">
							<input type="checkbox" <?php echo $ultimate_theme_support; ?> id="ultimate_theme_support" value="enable" class="onoffswitch-checkbox" name="ultimate_theme_support" />
								 <label class="onoffswitch-label" for="ultimate_theme_support">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>
                        <?php
							$ultimate_rtl_support = get_option('ultimate_rtl_support');
							if($ultimate_rtl_support == 'enable')
							{
								$ultimate_rtl_support = 'checked="checked"';
							}
						?>
                        <tr valign="top">
							<th scope="row"><?php echo __("RTL Suport","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch" id="ult-rtl-support-row">
							<input type="checkbox" <?php echo $ultimate_rtl_support; ?> id="ultimate_rtl_support" value="enable" class="onoffswitch-checkbox" name="ultimate_rtl_support" />
								 <label class="onoffswitch-label" for="ultimate_rtl_support">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>
                        <?php
							$ultimate_custom_vc_row = get_option('ultimate_custom_vc_row');
						?>
						<tr valign="top" class="ult-theme-support-row-dependant" style="<?php echo $theme_dependant; ?>">
							<th scope="row"><?php echo __("Custom VC Row Class","ultimate_vc"); ?></th>
							<td> 
                            	<div>
									<input type="text" id="ultimate_custom_vc_row" value="<?php echo $ultimate_custom_vc_row; ?>" name="ultimate_custom_vc_row" />
								</div>
							</td>
						</tr>
                        <!--<tr valign="top">
							<th scope="row"><?php echo __("Deregister Licence","ultimate_vc"); ?></th>
							<td> 
                            	<div>
									<a href="<?php echo admin_url('admin.php?page=bsf-dashboard&deregister-licence'); ?>" target="_blank" class="button-primary">Deregister Licence</a>
								</div>
							</td>
						</tr>-->
                        <?php
							$ultimate_modal_fixer = get_option('ultimate_modal_fixer');
							if($ultimate_modal_fixer == 'enable')
							{
								$ultimate_modal_fixer = 'checked="checked"';
							}
						?>
                        <!--<tr valign="top">
							<th scope="row"><?php echo __("Modal Fixer","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch">
							<input type="checkbox" <?php echo $ultimate_modal_fixer; ?> id="ultimate_modal_fixer" value="enable" class="onoffswitch-checkbox" name="ultimate_modal_fixer" />
								 <label class="onoffswitch-label" for="ultimate_modal_fixer">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>-->
						<?php
							$bsf_dev_mode = bsf_get_option('dev_mode');
							if($bsf_dev_mode == 'enable')
							{
								$bsf_dev_mode = 'checked="checked"';
							}
						?>
						<tr>
							<th scope="row"><?php echo __("Developer Mode","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch">
							<input type="checkbox" <?php echo $bsf_dev_mode; ?> id="bsf_dev_mode" value="enable" class="onoffswitch-checkbox" name="bsf_options[dev_mode]" />
								 <label class="onoffswitch-label" for="bsf_dev_mode">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>
						<?php
							$ultimate_global_scripts = bsf_get_option('ultimate_global_scripts');
							if($ultimate_global_scripts == 'enable')
							{
								$ultimate_global_scripts = 'checked="checked"';
							}
						?>
						<tr>
							<th scope="row"><?php echo __("Load scripts globally","ultimate_vc"); ?></th>
							<td> <div class="onoffswitch">
							<input type="checkbox" <?php echo $ultimate_global_scripts; ?> id="ultimate_global_scripts" value="enable" class="onoffswitch-checkbox" name="bsf_options[ultimate_global_scripts]" />
								 <label class="onoffswitch-label" for="ultimate_global_scripts">
									<div class="onoffswitch-inner">
										<div class="onoffswitch-active">
											<div class="onoffswitch-switch"><?php echo __('ON','ultimate_vc'); ?></div>
										</div>
										<div class="onoffswitch-inactive">
											<div class="onoffswitch-switch"><?php echo __('OFF','ultimate_vc'); ?></div>
										</div>
									</div>
								</label>
								 </div>
							</td>
						</tr>
                    </tbody>
              	</table>
            </form>
            <p class="submit"><input type="submit" name="submit" id="submit-debug-settings" class="button button-large button-primary" value="<?php echo __("Save Changes","ultimate");?>"></p>
        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">


var submit_btn = jQuery("#submit-modules");
submit_btn.bind('click',function(e){
	e.preventDefault();
	var data = jQuery("#ultimate_modules").serialize();
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		dataType: 'html',
		type: 'post',
		success: function(result){
			console.log(result);
			if(result == "success"){
				jQuery("#msg").html('<div class="updated"><p><?php echo __('Settings updated successfully!','ultimate_vc'); ?></p></div>');
				jQuery('html,body').animate({ scrollTop: 0 }, 300);
			} else {
				jQuery("#msg").html('<div class="error"><p><?php echo __('No settings were updated.','ultimate_vc'); ?></p></div>');
				jQuery('html,body').animate({ scrollTop: 0 }, 300);
			}
		}
	});
});
var submit_btn = jQuery("#submit-settings");
submit_btn.bind('click',function(e){
	e.preventDefault();
	var data = jQuery("#ultimate_settings").serialize();
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		dataType: 'html',
		type: 'post',
		success: function(result){
			console.log(result);
			if(result == "success"){
				jQuery("#msg").html('<div class="updated"><p><?php echo __('Settings updated successfully!','ultimate_vc'); ?></p></div>');
			} else {
				jQuery("#msg").html('<div class="error"><p><?php echo __('No settings were updated.','ultimate_vc'); ?></p></div>');
			}
		}
	});
});
var submit_btn = jQuery("#submit-css-settings");
submit_btn.bind('click',function(e){
	e.preventDefault();
	var data = jQuery("#css_settings").serialize();
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		dataType: 'html',
		type: 'post',
		success: function(result){
			console.log(result);
			if(result == "success"){
				jQuery("#msg").html('<div class="updated"><p><?php echo __('Settings updated successfully!','ultimate_vc'); ?></p></div>');
			} else {
				jQuery("#msg").html('<div class="error"><p><?php echo __('No settings were updated.','ultimate_vc'); ?></p></div>');
			}
		}
	});
});
var submit_btn = jQuery("#submit-debug-settings");
submit_btn.bind('click',function(e){
	e.preventDefault();
	var data = jQuery("#ultimate_debug_settings").serialize();
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		dataType: 'html',
		type: 'post',
		success: function(result){
			console.log(result);
			if(result == "success"){
				jQuery("#msg").html('<div class="updated"><p><?php echo __('Settings updated successfully!','ultimate_vc'); ?></p></div>');
			} else {
				jQuery("#msg").html('<div class="error"><p><?php echo __('No settings were updated.','ultimate_vc'); ?></p></div>');
			}
		}
	});
});
jQuery(document).ready(function(e) {
    var tab_link = jQuery(".nav-tab");
	var tabs = jQuery(".ult-tabs");
	var url = window.location,
		hash = url.hash.match(/^[^\?]*/)[0];
	if(hash != ''){
		tab_link.each(function(index, element) {
            jQuery(this).removeClass('nav-tab-active');
        });
		tabs.each(function(index, element) {
            jQuery(this).removeClass('active-tab');
        });
		jQuery('a[href="'+hash+'"]').addClass('nav-tab-active');
		jQuery(""+hash).addClass('active-tab');
	}
	// Toggle the tabs
	tab_link.click(function(e){
		e.preventDefault();
		window.location = jQuery(this).attr('href');	
		var cur_tab = jQuery(this).data('tab');
		tab_link.each(function(index, element) {
            jQuery(this).removeClass('nav-tab-active');
        });
		tabs.each(function(index, element) {
            jQuery(this).removeClass('active-tab');
        });
		jQuery(this).addClass('nav-tab-active');
		jQuery("#"+cur_tab).addClass('active-tab');
	});
	
	jQuery('.onoffswitch').on('onUltimateSwitchClick',function(){
		setTimeout(function(){
			if(jQuery('#ultimate_theme_support').is(':checked'))
			{
				jQuery('.ult-theme-support-row-dependant').fadeOut(200);
			}
			else
			{
				jQuery('.ult-theme-support-row-dependant').fadeIn(200);
			}
		},300);
	});
	
	jQuery('.onoffswitch').click(function(){
		$switch = jQuery(this);
		setTimeout(function(){
			if($switch.find('.onoffswitch-checkbox').is(':checked'))
				$switch.find('.onoffswitch-checkbox').attr('checked',false);
			else
				$switch.find('.onoffswitch-checkbox').attr('checked',true);
			$switch.trigger('onUltimateSwitchClick');
		},300);
		
	});

	var checked_items = <?php echo $checked_items ?>;
	var all_modules = parseInt(jQuery('#ult-all-modules-toggle').data('all'));
	if(checked_items === all_modules) {
		jQuery('#ult-all-modules-toggle').attr('checked',true);
	}

	jQuery('#ult-all-modules-toggle').click(function(){
		var is_check = (jQuery(this).is(':checked')) ? true : false;
		jQuery('.onoffswitch').trigger('click').find('.onoffswitch-checkbox').attr('checked',is_check);
	});
});
</script>
<style type="text/css">
/*On Off Checkbox Switch*/
.onoffswitch {
	position: relative;
	width: 95px;
	display: inline-block;
	float: left;
	margin-right: 15px;
	-webkit-user-select:none;
	-moz-user-select:none;
	-ms-user-select: none;
}
.onoffswitch-checkbox {
	display: none !important;
}
.onoffswitch-label {
	display: block;
	overflow: hidden;
	cursor: pointer;
	border: 0px solid #999999;
	border-radius: 0px;
}
.onoffswitch-inner {
	width: 200%;
	margin-left: -100%;
	-moz-transition: margin 0.3s ease-in 0s;
	-webkit-transition: margin 0.3s ease-in 0s;
	-o-transition: margin 0.3s ease-in 0s;
	transition: margin 0.3s ease-in 0s;
}
.rtl .onoffswitch-inner{
	margin: 0;
}
.rtl .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
	margin-right: -100%;
	margin-left:auto;
}
.onoffswitch-inner > div {
	float: left;
	position: relative;
	width: 50%;
	height: 24px;
	padding: 0;
	line-height: 24px;
	font-size: 12px;
	color: white;
	font-weight: bold;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
}
.onoffswitch-inner .onoffswitch-active {
	padding-left: 15px;
	background-color: #CCCCCC;
	color: #FFFFFF;
}
.onoffswitch-inner .onoffswitch-inactive {
	padding-right: 15px;
	background-color: #CCCCCC;
	color: #FFFFFF;
	text-align: right;
}
.onoffswitch-switch {
	/*width: 50px;*/
	width:35px;
	margin: 0px;
	text-align: center;
	border: 0px solid #999999;
	border-radius: 0px;
	position: absolute;
	top: 0;
	bottom: 0;
}
.onoffswitch-active .onoffswitch-switch {
	background: #3F9CC7;
	left: 0;
}
.onoffswitch-inactive .onoffswitch-switch {
	background: #7D7D7D;
	right: 0;
}
.onoffswitch-active .onoffswitch-switch:before {
	content: " ";
	position: absolute;
	top: 0;
	/*left: 50px;*/
	left:35px;
	border-style: solid;
	border-color: #3F9CC7 transparent transparent #3F9CC7;
	/*border-width: 12px 8px;*/
	border-width: 15px;
}
.onoffswitch-inactive .onoffswitch-switch:before {
	content: " ";
	position: absolute;
	top: 0;
	/*right: 50px;*/
	right:35px;
	border-style: solid;
	border-color: transparent #7D7D7D #7D7D7D transparent;
	/*border-width: 12px 8px;*/
	border-width: 50px;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
	margin-left: 0;
}
#ultimate-settings, #ultimate-modules, .ult-tabs{ display:none; }
#ultimate-settings.active-tab, #ultimate-modules.active-tab, .ult-tabs.active-tab{ display:block; }
.ult-badge {
	padding-bottom: 10px;
	height: 170px;
	width: 150px;
	position: absolute;
	border-radius: 3px;
	top: 0;
	right: 0;
}
div#msg > .updated, div#msg > .error { display:block !important;}
div#msg {
	position: absolute;
	left: 0;
	top: 100px;
	max-width: 30%;
}
.onoffswitch-inner:before,
.onoffswitch-inner:after {
    display:none
}
.onoffswitch-switch {
    height: initial !important;
	color: white !important;
}
</style>