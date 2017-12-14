<?php 
if( !defined( 'ABSPATH') ) exit();

$operations = new RevSliderOperations();
$arrValues = $operations->getGeneralSettingsValues();

$role = RevSliderBase::getVar($arrValues, 'role', 'admin');
$includes_globally = RevSliderBase::getVar($arrValues, 'includes_globally', 'on');
$pages_for_includes = RevSliderBase::getVar($arrValues, 'pages_for_includes', '');
$js_to_footer = RevSliderBase::getVar($arrValues, 'js_to_footer', 'off');
$js_defer = RevSliderBase::getVar($arrValues, 'js_defer', 'off');
$show_dev_export = RevSliderBase::getVar($arrValues, 'show_dev_export', 'off');
$change_font_loading = RevSliderBase::getVar($arrValues, 'change_font_loading', '');
$enable_logs = RevSliderBase::getVar($arrValues, 'enable_logs', 'off');

$enable_newschannel = apply_filters('revslider_set_notifications', 'on');
$enable_newschannel = RevSliderBase::getVar($arrValues, "enable_newschannel",$enable_newschannel);

$width = RevSliderBase::getVar($arrValues, 'width', 1240);
$width_notebook = RevSliderBase::getVar($arrValues, 'width_notebook', 1024);
$width_tablet = RevSliderBase::getVar($arrValues, 'width_tablet', 778);
$width_mobile = RevSliderBase::getVar($arrValues, 'width_mobile', 480);
?>

<div id="dialog_general_settings" title="<?php _e("General Settings",REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">

	<div class="settings_wrapper unite_settings_wide">
		<form name="form_general_settings" id="form_general_settings">
			<script type="text/javascript">
				g_settingsObj['form_general_settings'] = {};
			</script>
			<table class="form-table">				
				<tbody>
					<tr id="role_row" valign="top">
						<th scope="row">
							<?php _e("View Plugin Permission:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<select id="role" name="role">
								<option <?php selected($role, 'admin'); ?> value="admin"><?php _e("To Admin",REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($role, 'editor'); ?> value="editor"><?php _e("To Editor, Admin",REVSLIDER_TEXTDOMAIN); ?></option>
								<option <?php selected($role, 'author'); ?> value="author"><?php _e("Author, Editor, Admin",REVSLIDER_TEXTDOMAIN); ?></option>
							</select>
						
							<div class="description_container">
								<span class="description"><?php _e("The role of user that can view and edit the plugin",REVSLIDER_TEXTDOMAIN); ?></span>					
							</div>
						</td>
					</tr>								
					<tr id="includes_globally_row" valign="top">
						<th scope="row">
							<?php _e("Include RevSlider libraries globally:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<span id="includes_globally_wrapper" class="radio_settings_wrapper">
							<div class="radio_inner_wrapper">
								<input type="radio" id="includes_globally_1" value="on" name="includes_globally" <?php checked($includes_globally, 'on'); ?>>
								<label for="includes_globally_1" style="cursor:pointer;"><?php _e("On", REVSLIDER_TEXTDOMAIN); ?></label>
							</div>
				
							<div class="radio_inner_wrapper">
								<input type="radio" id="includes_globally_2" value="off" name="includes_globally" <?php checked($includes_globally, 'off'); ?>>
								<label for="includes_globally_2" style="cursor:pointer;"><?php _e("Off", REVSLIDER_TEXTDOMAIN); ?></label>
							</div>					
							</span>
				
							<div class="description_container">
								<span class="description"><?php _e("ON - Add CSS and JS Files to all pages. </br>Off - CSS and JS Files will be only loaded on Pages where any rev_slider shortcode exists.",REVSLIDER_TEXTDOMAIN); ?></span>					
							</div>
						</td>
					</tr>								
					<tr id="pages_for_includes_row" valign="top">
						<th scope="row">
							<?php _e("Pages to include RevSlider libraries:", REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<input type="text" class="regular-text" id="pages_for_includes" name="pages_for_includes" value="<?php echo $pages_for_includes; ?>">			
							<div class="description_container">
								<span class="description"><?php _e("Specify the page id's that the front end includes will be included in. Example: 2,3,5 also: homepage,3,4",REVSLIDER_TEXTDOMAIN); ?></span>
			
							</div>
						</td>
					</tr>								
					<tr id="js_to_footer_row" valign="top">
						<th scope="row">
							<?php _e("Insert JavaScript Into Footer:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<span id="js_to_footer_wrapper" class="radio_settings_wrapper">
								<div class="radio_inner_wrapper">
									<input type="radio" id="js_to_footer_1" value="on" name="js_to_footer" <?php checked($js_to_footer, 'on'); ?>>
									<label for="js_to_footer_1" style="cursor:pointer;"><?php _e("On",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>
				
								<div class="radio_inner_wrapper">
									<input type="radio" id="js_to_footer_2" value="off" name="js_to_footer" <?php checked($js_to_footer, 'off'); ?>>
									<label for="js_to_footer_2" style="cursor:pointer;"><?php _e("Off",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>					
							</span>					
							<div class="description_container">
								<span class="description"><?php _e("Putting the js to footer (instead of the head) is good for fixing some javascript conflicts.",REVSLIDER_TEXTDOMAIN); ?></span>				
							</div>
						</td>
					</tr>
					<tr id="js_defer_row" valign="top">
						<th scope="row">
							<?php _e("Defer JavaScript Loading:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<span id="js_defer_wrapper" class="radio_settings_wrapper">
								<div class="radio_inner_wrapper">
									<input type="radio" id="js_defer_1" value="on" name="js_defer" <?php checked($js_defer, 'on'); ?>>
									<label for="js_defer_1" style="cursor:pointer;"><?php _e("On",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>

								<div class="radio_inner_wrapper">
									<input type="radio" id="js_defer_2" value="off" name="js_defer" <?php checked($js_defer, 'off'); ?>>
									<label for="js_defer_2" style="cursor:pointer;"><?php _e("Off",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>					
							</span>					
							<div class="description_container">
								<span class="description"><?php _e("Defer the loading of the JavaScript libraries to maximize page loading speed.",REVSLIDER_TEXTDOMAIN); ?></span>				
							</div>
						</td>
					</tr>
					<tr id="show_dev_export_row" valign="top">
						<th scope="row">
							<?php _e("Enable Markup Export option:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<span id="show_dev_export_wrapper" class="radio_settings_wrapper">
								<div class="radio_inner_wrapper">
									<input type="radio" id="show_dev_export_1" value="on" name="show_dev_export" <?php checked($show_dev_export, 'on'); ?>>
									<label for="show_dev_export_1" style="cursor:pointer;"><?php _e("On",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>
				
								<div class="radio_inner_wrapper">
									<input type="radio" id="show_dev_export_2" value="off" name="show_dev_export" <?php checked($show_dev_export, 'off'); ?>>
									<label for="show_dev_export_2" style="cursor:pointer;"><?php _e("Off",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>					
							</span>					
							<div class="description_container">
								<span class="description"><?php _e("This will enable the option to export the Slider Markups to copy/paste it directly into websites.",REVSLIDER_TEXTDOMAIN); ?></span>				
							</div>
						</td>
					</tr>							
					<tr id="show_dev_export_row" valign="top">
						<th scope="row">
							<?php _e("Font Loading URL:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<input id="change_font_loading" name="change_font_loading" type="text" class="regular-text" value="<?php echo $change_font_loading; ?>">
							<div class="description_container">
								<span class="description"><?php _e("Insert something in it and it will be used instead of http://fonts.googleapis.com/css?family= (For example: http://fonts.useso.com/css?family= which will also work for chinese visitors)",REVSLIDER_TEXTDOMAIN); ?></span>				
							</div>
						</td>
					</tr>
					
					<tr id="advanced_resonsive_sizes_row" valign="top">
						<th scope="row">
							<?php _e("Default Settings for Advanced Responsive Grid Sizes:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<div><?php _e('Desktop Grid Width', REVSLIDER_TEXTDOMAIN); ?>
							<input id="width" name="width" type="text" class="textbox-small" value="<?php echo $width; ?>"></div>
							<div><?php _e('Notebook Grid Width', REVSLIDER_TEXTDOMAIN); ?>
							<input id="width_notebook" name="width_notebook" type="text" class="textbox-small" value="<?php echo $width_notebook; ?>"></div>
							<div><?php _e('Tablet Grid Width', REVSLIDER_TEXTDOMAIN); ?>
							<input name="width_tablet" type="text" class="textbox-small" value="<?php echo $width_tablet; ?>"></div>
							<div><?php _e('Mobile Grid Width', REVSLIDER_TEXTDOMAIN); ?>
							<input name="width_mobile" type="text" class="textbox-small" value="<?php echo $width_mobile; ?>"></div>
							
							<div class="description_container">
								<span class="description"><?php _e("Define the default Grid Sizes for devices: Desktop, Tablet and Mobile",REVSLIDER_TEXTDOMAIN); ?></span>				
							</div>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php _e("Enable Notifications:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<span id="enable_logs_wrapper" class="radio_settings_wrapper">
								<div class="radio_inner_wrapper">
									<input type="radio" id="" value="on" name="enable_newschannel" <?php checked($enable_newschannel, 'on'); ?>>
									<label for="" style="cursor:pointer;"><?php _e("On",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>
				
								<div class="radio_inner_wrapper">
									<input type="radio" id="" value="off" name="enable_newschannel" <?php checked($enable_newschannel, 'off'); ?>>
									<label for="" style="cursor:pointer;"><?php _e("Off",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>
							</span>
							<div class="description_container">
								<span class="description"><?php _e("Enable/Disable ThemePunch Notifications in the Admin Notice bar.",REVSLIDER_TEXTDOMAIN)?></span>				
							</div>
						</td>
					</tr>
					
					<tr id="use_hammer_js_row" valign="top">
						<th scope="row">
							<?php _e("Enable Logs:",REVSLIDER_TEXTDOMAIN); ?>
						</th>
						<td>
							<span id="enable_logs_wrapper" class="radio_settings_wrapper">
								<div class="radio_inner_wrapper">
									<input type="radio" id="enable_logs_1" value="on" name="enable_logs" <?php checked($enable_logs, 'on'); ?>>
									<label for="enable_logs_1" style="cursor:pointer;"><?php _e("On",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>
				
								<div class="radio_inner_wrapper">
									<input type="radio" id="use_hammer_js_2" value="off" name="enable_logs" <?php checked($enable_logs, 'off'); ?>>
									<label for="use_hammer_js_2" style="cursor:pointer;"><?php _e("Off",REVSLIDER_TEXTDOMAIN); ?></label>
								</div>
							</span>
							<div class="description_container">
								<span class="description"><?php _e("Enable console logs for debugging.",REVSLIDER_TEXTDOMAIN); ?></span>				
							</div>
						</td>
					</tr>								
				</tbody>
			</table>				
		</form>
	</div>
<br>

<a id="button_save_general_settings" class="button-primary" original-title=""><?php _e("Update",REVSLIDER_TEXTDOMAIN); ?></a>
<span id="loader_general_settings" class="loader_round mleft_10" style="display: none;"></span>

</div>
