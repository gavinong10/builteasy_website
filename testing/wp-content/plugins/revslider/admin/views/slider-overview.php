<?php

if( !defined( 'ABSPATH') ) exit();

$orders = false;
//order=asc&ot=name&type=reg
if(isset($_GET['ot']) && isset($_GET['order']) && isset($_GET['type'])){
	$order = array();
	switch($_GET['ot']){
		case 'alias':
			$order['alias'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
		case 'favorite':
			$order['favorite'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
		case 'name':
		default:
			$order['title'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
	}
	
	$orders = $order;
}


$slider = new RevSlider();
$arrSliders = $slider->getArrSliders($orders);

$addNewLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER);


$fav = get_option('rev_fav_slider', array());
if($orders == false){ //sort the favs to top
	if(!empty($fav) && !empty($arrSliders)){
		$fav_sort = array();
		foreach($arrSliders as $skey => $sort_slider){
			if(in_array($sort_slider->getID(), $fav)){
				$fav_sort[] = $arrSliders[$skey];
				unset($arrSliders[$skey]);
			}
		}
		if(!empty($fav_sort)){
			//revert order of favs
			krsort($fav_sort);
			foreach($fav_sort as $fav_arr){
				array_unshift($arrSliders, $fav_arr);
			}
		}
	}
}

global $revSliderAsTheme;

$exampleID = '"slider1"';
if(!empty($arrSliders))
	$exampleID = '"'.$arrSliders[0]->getAlias().'"';

$latest_version = get_option('revslider-latest-version', RevSliderGlobals::SLIDER_REVISION);
$stable_version = get_option('revslider-stable-version', '4.1');

?>

<div class='wrap'>
	<div class="clear_both"></div>
	<div class="title_line" style="margin-bottom:10px">
		<div id="icon-options-general" class="icon32"></div>
		<a href="<?php echo RevSliderGlobals::LINK_HELP_SLIDERS; ?>" class="button-secondary float_right mtop_10 mleft_10" target="_blank"><?php _e("Help",REVSLIDER_TEXTDOMAIN); ?></a>

		<a id="button_general_settings" class="button-secondary float_right mtop_10"><?php _e("Global Settings",REVSLIDER_TEXTDOMAIN); ?></a>
	</div>

	<div class="clear_both"></div>

	<div class="title_line nobgnopd" style="height:auto; min-height:50px">
		<div class="view_title">
			<?php _e("Revolution Sliders", REVSLIDER_TEXTDOMAIN); ?>			
		</div>
		<div class="slider-sortandfilter">
				<span class="slider-listviews slider-lg-views" data-type="rs-listview"><i class="eg-icon-align-justify"></i></span>
				<span class="slider-gridviews slider-lg-views active" data-type="rs-gridview"><i class="eg-icon-th"></i></span>
				<span class="slider-sort-drop"><?php _e("Sort By:",REVSLIDER_TEXTDOMAIN); ?></span>
				<select id="sort-sliders" name="sort-sliders" style="max-width: 105px;" class="withlabel">
					<option value="id" selected="selected"><?php _e("By ID",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="name"><?php _e("By Name",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="type"><?php _e("By Type",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="favorit"><?php _e("By Favorit",REVSLIDER_TEXTDOMAIN); ?></option>
				</select>
				
				<span class="slider-filter-drop"><?php _e("Filter By:",REVSLIDER_TEXTDOMAIN); ?></span>
				
				<select id="filter-sliders" name="filter-sliders" style="max-width: 105px;" class="withlabel">
					<option value="all" selected="selected"><?php _e("All",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="posts"><?php _e("Posts",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="gallery"><?php _e("Gallery",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="vimeo"><?php _e("Vimeo",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="youtube"><?php _e("YouTube",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="twitter"><?php _e("Twitter",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="facebook"><?php _e("Facebook",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="instagram"><?php _e("Instagram",REVSLIDER_TEXTDOMAIN); ?></option>
					<option value="flickr"><?php _e("Flickr",REVSLIDER_TEXTDOMAIN); ?></option>
				</select>
		</div>
		<div style="width:100%;height:1px;float:none;clear:both"></div>
	</div>

	<?php
	$no_sliders = false;
	if(empty($arrSliders)){
		?>
		<span style="display:block;margin-top:15px;margin-bottom:15px;">
		<?php  _e("No Sliders Found",REVSLIDER_TEXTDOMAIN); ?>
		</span>
		<?php
		$no_sliders = true;
	}

	require self::getPathTemplate('sliders-list');

	?>
	<!--
	THE INFO ABOUT EMBEDING OF THE SLIDER
	-->
	<div class="rs-dialog-embed-slider" style="display: none;">
		<div class="revyellow" style="background: none repeat scroll 0% 0% #F1C40F; left:0px;top:36px;position:absolute;height:224px;padding:20px 10px;"><i style="color:#fff;font-size:25px" class="revicon-arrows-ccw"></i></div>
		<div style="margin:5px 0px; padding-left: 55px;">
			<div style="font-size:14px;margin-bottom:10px;"><strong><?php _e("Standard Embeding",REVSLIDER_TEXTDOMAIN); ?></strong></div>
			<?php _e("For the",REVSLIDER_TEXTDOMAIN); ?> <b><?php _e("pages or posts editor",REVSLIDER_TEXTDOMAIN); ?></b> <?php _e("insert the shortcode:",REVSLIDER_TEXTDOMAIN); ?> <code class="rs-example-alias-1"></code>
			<div style="width:100%;height:10px"></div>
			<?php _e("From the",REVSLIDER_TEXTDOMAIN); ?> <b><?php _e("widgets panel",REVSLIDER_TEXTDOMAIN); ?></b> <?php _e("drag the \"Revolution Slider\" widget to the desired sidebar",REVSLIDER_TEXTDOMAIN); ?>
			<div style="width:100%;height:25px"></div>
			<div id="advanced-emeding" style="font-size:14px;margin-bottom:10px;"><strong><?php _e("Advanced Embeding",REVSLIDER_TEXTDOMAIN); ?></strong><i class="eg-icon-plus"></i></div>
			<div id="advanced-accord" style="display:none">
				<?php _e("From the",REVSLIDER_TEXTDOMAIN); ?> <b><?php _e("theme html",REVSLIDER_TEXTDOMAIN); ?></b> <?php _e("use",REVSLIDER_TEXTDOMAIN); ?>: <code>&lt?php putRevSlider( '<span class="rs-example-alias">alias</span>' ); ?&gt</code><br>
				<span><?php _e("To add the slider only to homepage use",REVSLIDER_TEXTDOMAIN); ?>: <code>&lt?php putRevSlider('<span class="rs-example-alias"><?php echo $exampleID; ?></span>', 'homepage'); ?&gt</code></span></br>
				<span><?php _e("To add the slider on specific pages or posts use",REVSLIDER_TEXTDOMAIN); ?>: <code>&lt?php putRevSlider('<span class="rs-example-alias"><?php echo $exampleID; ?></span>', '2,10'); ?&gt</code></span></br>
			</div>
			
		</div>
	</div>
	<script>
	 jQuery('#advanced-emeding').click(function() {
	 	jQuery('#advanced-accord').toggle(200);
	 })
	</script>
	
	<?php
	if(!$revSliderAsTheme){
		$validated = get_option('revslider-valid', 'false');
		?>
		<div style="width:100%;height:50px"></div>
		<!--
		THE CURRENT AND NEXT VERSION
		-->
		<div class="title_line nobgnopd"><div class="view_title"><?php _e("Version Information",REVSLIDER_TEXTDOMAIN); ?></div></div>

		<div class="valid_big_padding" style="border-top:1px solid #e5e5e5; padding:15px 15px 15px 80px; position:relative;overflow:hidden;background:#fff;">
			<div class="revgray valid_big_border" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="revicon-info-circled"></i></div>
			
			<?php _e("Installed Version",REVSLIDER_TEXTDOMAIN); ?>: <span  class="slidercurrentversion"><?php echo RevSliderGlobals::SLIDER_REVISION; ?></span><br>
			<?php
			if($validated !== 'true' && version_compare(RevSliderGlobals::SLIDER_REVISION, $stable_version, '<')){
			?>
				<span class="slideravailableversion"><?php _e("Latest Stable Version",REVSLIDER_TEXTDOMAIN); ?>: <?php echo $stable_version; ?></span><a href="update-core.php?checkforupdates=true" id="rs-check-updates" class="button-primary revpurple"><?php _e('Update to Stable (Free)',REVSLIDER_TEXTDOMAIN); ?></a><br>
				<?php
			}
			?>
			<span class="slideravailableversion"><?php _e("Latest Available Version",REVSLIDER_TEXTDOMAIN); ?>: <?php echo $latest_version; ?></span><a class='button-primary revblue' href='?page=revslider&checkforupdates=true' id="rev_check_version"><?php _e("Check Version",REVSLIDER_TEXTDOMAIN); ?> </a>
			
		</div>
		
		<div style="width:100%;height:50px"></div>
		
		<div class="title_line nobgnopd"><div class="view_title"><?php _e("Requirements & Recommendations",REVSLIDER_TEXTDOMAIN); ?></div></div>
		<div class="valid_big_padding" style="border-top:1px solid #e5e5e5; padding:15px 15px 15px 80px; position:relative;overflow:hidden;background:#fff;">
			<div class="revgray valid_big_border" style="background:#3d566e;left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff !important;font-size:25px" class="eg-icon-thumbs-up"></i></div>
			<span class="slideravailableversion"><?php _e('Uploads folder writable:', REVSLIDER_TEXTDOMAIN); ?></span><?php
			//check if uploads folder can be written into
			$dir = wp_upload_dir();
			if(wp_is_writable($dir['basedir'].'/')){
				echo '<i class="revgreenicon eg-icon-ok-squared"></i>';
			}else{
				echo '<i class="revredicon eg-icon-info-circled"></i><span style="margin-left:15px">'. __('Please set the write permission (755) to your wp-content/uploads folders.', REVSLIDER_TEXTDOMAIN).'</span>';
			}
			?><br>
			<span class="slideravailableversion"><?php _e('ZipArchive Extension:', REVSLIDER_TEXTDOMAIN); ?></span><?php
			//check if ZipArchive exists
			if(class_exists("ZipArchive")){
				echo '<i class="revgreenicon eg-icon-ok-squared"></i>';
			}else{
				echo '<i class="revredicon eg-icon-info-circled"></i><span style="margin-left:15px">'. __('Please enable the ZipArchive extension for PHP on your server.', REVSLIDER_TEXTDOMAIN).'</span>';
			}
			?><br>
			<span class="slideravailableversion"><?php _e('Contact ThemePunch Server:', REVSLIDER_TEXTDOMAIN); ?></span><?php
			$can_connect = get_option('revslider-connection', false);
			if($can_connect){
				echo '<i class="revgreenicon eg-icon-ok-squared"></i>';
			}else{
				echo '<i class="revredicon eg-icon-info-circled"></i><span style="margin-left:15px">'. __('Please make sure that your server can connect to updates.themepunch.tools and templates.themepunch.tools programmatically.', REVSLIDER_TEXTDOMAIN).'</span>';;
			}
			?> <a class='button-primary revblue' href='?page=revslider&checkforupdates=true' id="rev_check_version_1" style="margin-left:15px"><?php _e("Check Now",REVSLIDER_TEXTDOMAIN); ?></a><br>
			<span class="slideravailableversion"><?php _e('Memory Limit:', REVSLIDER_TEXTDOMAIN); ?></span><?php
			$mem_limit = ini_get('memory_limit');
			$mem_limit_byte = wp_convert_hr_to_bytes($mem_limit);

			if($mem_limit_byte < 268435456){
				//not good
				echo '<i style="margin-right:15px" class="revredicon eg-icon-info-circled"></i>';
			} else {
				echo '<i style="margin-right:15px"class="revgreenicon eg-icon-ok-squared"></i>';
			}

			echo __('Currently:', REVSLIDER_TEXTDOMAIN).' '.$mem_limit;

			if($mem_limit_byte < 268435456){
				//not good
				echo '<span style="margin-left:15px">'. __('Recommended 256M', REVSLIDER_TEXTDOMAIN).'</span>';
			} 
			?><br>
			<span class="slideravailableversion"><?php _e('Upload Max. Filesize:', REVSLIDER_TEXTDOMAIN); ?></span><?php
			$upload_max_filesize = ini_get('upload_max_filesize');
			$upload_max_filesize_byte = wp_convert_hr_to_bytes($upload_max_filesize);
			
			if($upload_max_filesize_byte < 33554432){
				//not good
				echo '<i style="margin-right:15px" class="revredicon eg-icon-info-circled"></i>';
			} else {
				echo '<i style="margin-right:15px"class="revgreenicon eg-icon-ok-squared"></i>';
			}

			echo __('Currently:', REVSLIDER_TEXTDOMAIN).' '.$upload_max_filesize;
			
			if($upload_max_filesize_byte < 33554432){
				echo '<span style="margin-left:15px">'. __('Recommended 32M', REVSLIDER_TEXTDOMAIN).'</span>';
			}
			?><br>
			<span class="slideravailableversion"><?php _e('Max. Post Size:', REVSLIDER_TEXTDOMAIN); ?></span><?php
			$post_max_size = ini_get('post_max_size');
			$post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);
			
			
			if($post_max_size_byte < 33554432){
				//not good
				echo '<i style="margin-right:15px" class="revredicon eg-icon-info-circled"></i>';
			} else {
				echo '<i style="margin-right:15px"class="revgreenicon eg-icon-ok-squared"></i>';
			}

			echo __('Currently:', REVSLIDER_TEXTDOMAIN).' '.$post_max_size;
			
			if($post_max_size_byte < 33554432){
				echo '<span style="margin-left:15px">'. __('Recommended 32M', REVSLIDER_TEXTDOMAIN).'</span>';
			}

			?>
		</div>

		<!--
		ACTIVATE THIS PRODUCT
		-->
		<a name="activateplugin"></a>
		<div style="width:100%;height:50px"></div>

		<div class="title_line nobgnopd">
			<div class="view_title"><span style="margin-right:10px"><?php _e("Need Premium Support and Live Updates ?",REVSLIDER_TEXTDOMAIN); ?></span><a style="vertical-align:middle" class='button-primary revblue' href='#' id="benefitsbutton"><?php _e("Why is this Important ?",REVSLIDER_TEXTDOMAIN); ?> </a></div>
		</div>

		<div id="benefitscontent" class="valid_big_padding" style="margin-top:10px;margin-bottom:10px;display:none;border:1px solid #e5e5e5; padding:15px 15px 15px 80px; position:relative;overflow:hidden;background-color:#fff;">
			<div class="revblue" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="revicon-doc"></i></div>
			<h3> <?php _e("Benefits",REVSLIDER_TEXTDOMAIN); ?>:</h3>
			<p>
			<strong><?php _e("Get Premium Support",REVSLIDER_TEXTDOMAIN); ?></strong><?php _e(" - We help you in case of Bugs, installation problems, and Conflicts with other plugins and Themes",REVSLIDER_TEXTDOMAIN); ?><br>
			<strong><?php _e("Live Updates",REVSLIDER_TEXTDOMAIN); ?></strong><?php _e(" - Get the latest version of our Plugin. New Features and Bug Fixes are available regularly !",REVSLIDER_TEXTDOMAIN); ?>
			</p>
		</div>

		<!--
		VALIDATION
		-->
		<div id="tp-validation-box" class="valid_big_padding" style="cursor:pointer; border-top:1px solid #e5e5e5; padding:15px 15px 15px 80px; position:relative;overflow:hidden;background:#fff">
			<?php self::requireView("system/validation"); ?>
		</div>
		<?php
	}else{
		?>
		<div style="width:100%;height:50px"></div>
		<!--
		THE CURRENT AND NEXT VERSION
		-->
		<div class="title_line nobgnopd"><div class="view_title"><?php _e("Information",REVSLIDER_TEXTDOMAIN); ?></div></div>

		<div class="valid_big_padding" style="border-top:1px solid #e5e5e5; padding:15px 15px 15px 80px; position:relative;overflow:hidden;background:#fff;">
			<div class="revgray valid_big_border" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="revicon-info-circled"></i></div>
			<p style="margin-top:5px; margin-bottom:5px;">
				<?php _e("Please note that this plugin came bundled with a theme. The use of the Revolution Slider is limited to this theme only.<br>If you need support from the Slider author ThemePunch or you want to use the Revolution slider with an other theme you will need an extra single license available at CodeCanyon.",REVSLIDER_TEXTDOMAIN); ?>
			</p>
		</div>
		<?php
	}
	?>

	<div style="width:100%;height:50px"></div>

	<!-- NEWSLETTER PART -->
	<div class="title_line nobgnopd">
		<div class="view_title"><span style="margin-right:10px"><?php _e('Newsletter', REVSLIDER_TEXTDOMAIN); ?></span><a style="vertical-align:middle" class='button-primary revred' href='#' id="why-subscribe"><?php _e("Why subscribe?", REVSLIDER_TEXTDOMAIN); ?></a></div>
	</div>		

	<div id="eg-newsletter-wrapper">		
		<div class="revred valid_big_border" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="font-size:25px" class="iconttowhite eg-icon-mail"></i></div>
		<div class="rtlmargintop105" style="margin-top:65px; margin-bottom:5px;">
			<span id="unsubscribe-text" style="display: none;"><?php _e("Unsubscribe our newsletter", REVSLIDER_TEXTDOMAIN); ?></span><span id="subscribe-text"><?php _e("Subscribe to our newsletter", REVSLIDER_TEXTDOMAIN); ?></span>: <input type="text" value="" placeholder="<?php _e('Enter your E-Mail here', REVSLIDER_TEXTDOMAIN); ?>" name="rs-email" style="width: 170px;" />
			<span class="subscribe-newsletter-wrap"><a href="javascript:void(0);" class="button-primary revgreen" id="subscribe-to-newsletter"><?php _e('Subscribe', REVSLIDER_TEXTDOMAIN); ?></a></span>
			<span class="unsubscribe-newsletter-wrap" style="display: none;">
				<a href="javascript:void(0);" class="button-primary revred" id="unsubscribe-to-newsletter"><?php _e('Unsubscribe', REVSLIDER_TEXTDOMAIN); ?></a>
				<a href="javascript:void(0);" class="button-primary revgreen" id="cancel-unsubscribe"><?php _e('Cancel', REVSLIDER_TEXTDOMAIN); ?></a>
			</span>
		</div>
		<a href="javascript:void(0);" id="activate-unsubscribe" style="font-size: 12px; color: #999; text-decoration: none;"><?php _e('unsubscibe from newsletter', REVSLIDER_TEXTDOMAIN); ?></a>
		<div id="why-subscribe-wrapper" style="display: none;">
			<div class="star_red"><strong style="font-weight:700"><?php _e('Perks of subscribing to our Newsletter', REVSLIDER_TEXTDOMAIN); ?></strong></div>
			<ul>
				<li><?php _e('Receive info on the latest ThemePunch product updates', REVSLIDER_TEXTDOMAIN); ?></li>
				<li><?php _e('Be the first to know about new products by ThemePunch and their partners', REVSLIDER_TEXTDOMAIN); ?></li>
				<li><?php _e('Participate in polls and customer surveys that help us increase the quality of our products and services', REVSLIDER_TEXTDOMAIN); ?></li>
			</ul>
		</div>
	</div>
	
	<!-- THE UPDATE HISTORY OF SLIDER REVOLUTION -->
	<div style="width:100%;height:50px"></div>
	
	<div class="title_line nobgnopd">
		<div class="view_title"><span style="margin-right:10px"><?php _e("Update History",REVSLIDER_TEXTDOMAIN);  ?></span></div>
	</div>

	<div class="valid_big_padding_2" style="border-top:1px solid #e5e5e5;  height:500px;padding:25px 15px 15px 80px; position:relative;overflow:hidden;background:#fff">
		<div class="revpurple valid_big_border" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="font-size:27px" class="iconttowhite eg-icon-back-in-time"></i></div>
		<div style="height:485px;overflow:scroll;width:100%;"><?php echo file_get_contents(RS_PLUGIN_PATH.'release_log.html'); ?></div>
	</div>
</div>

<!-- Import slider dialog -->
<div id="dialog_import_slider" title="<?php _e("Import Slider",REVSLIDER_TEXTDOMAIN); ?>" class="dialog_import_slider" style="display:none">
	<form action="<?php echo RevSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post">
		<br>
		<input type="hidden" name="action" value="revslider_ajax_action">
		<input type="hidden" name="client_action" value="import_slider_slidersview">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("revslider_actions"); ?>">
		<?php _e("Choose the import file",REVSLIDER_TEXTDOMAIN); ?>:
		<br>
		<input type="file" size="60" name="import_file" class="input_import_slider">
		<br><br>
		<span style="font-weight: 700;"><?php _e("Note: styles templates will be updated if they exist!",REVSLIDER_TEXTDOMAIN); ?></span><br><br>
		<table>
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
		<br><br>
		<input type="submit" class="button-primary revblue tp-be-button rev-import-slider-button" style="display: none;" value="<?php _e("Import Slider",REVSLIDER_TEXTDOMAIN); ?>">
	</form>
</div>


<div id="dialog_duplicate_slider" class="dialog_duplicate_layer" title="<?php _e('Duplicate', REVSLIDER_TEXTDOMAIN); ?>" style="display:none;">
	<div style="margin-top:14px">
		<span style="margin-right:15px"><?php _e('Title:', REVSLIDER_TEXTDOMAIN); ?></span><input id="rs-duplicate-animation" type="text" name="rs-duplicate-animation" value="" />
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		RevSliderAdmin.initSlidersListView();
		RevSliderAdmin.initNewsletterRoutine();
		
		jQuery('#benefitsbutton').hover(function() {
			jQuery('#benefitscontent').slideDown(200);
		}, function() {
			jQuery('#benefitscontent').slideUp(200);
		});
		
		jQuery('#why-subscribe').hover(function() {
			jQuery('#why-subscribe-wrapper').slideDown(200);
		}, function() {
			jQuery('#why-subscribe-wrapper').slideUp(200);				
		});
		
		jQuery('#tp-validation-box').click(function() {
			jQuery(this).css({cursor:"default"});
			if (jQuery('#rs-validation-wrapper').css('display')=="none") {
				jQuery('#tp-before-validation').hide();
				jQuery('#rs-validation-wrapper').slideDown(200);
			}
		});
	});
</script>
<?php
require self::getPathTemplate('template-slider-selector');
?>