<?php
if( !defined( 'ABSPATH') ) exit();

global $revSliderVersion;

$wrapperClass = "";
if(RevSliderGlobals::$isNewVersion == false)
	$wrapperClass = " oldwp";

$nonce = wp_create_nonce("revslider_actions");

$rsop = new RevSliderOperations();
$glval = $rsop->getGeneralSettingsValues();
?>

<?php
$waitstyle = '';
if(isset($_REQUEST['update_shop'])){
	$waitstyle = 'display:block';
}
?>

<div id="waitaminute" style="<?php echo $waitstyle; ?>">
	<div class="waitaminute-message"><i class="eg-icon-emo-coffee"></i><br><?php _e("Please Wait...",REVSLIDER_TEXTDOMAIN); ?></div>
</div>


<script type="text/javascript">
	var g_revNonce = "<?php echo $nonce; ?>";
	var g_uniteDirPlugin = "revslider";
	var g_urlContent = "<?php echo str_replace(array("\n", "\r", chr(10), chr(13)), array(''), content_url())."/"; ?>";
	var g_urlAjaxShowImage = "<?php echo RevSliderBase::$url_ajax_showimage; ?>";
	var g_urlAjaxActions = "<?php echo RevSliderBase::$url_ajax_actions; ?>";
	var g_revslider_url = "<?php echo RS_PLUGIN_URL; ?>";
	var g_settingsObj = {};
	
	var global_grid_sizes = {
		'desktop': '<?php echo RevSliderBase::getVar($glval, 'width', 1230); ?>',
		'notebook': '<?php echo RevSliderBase::getVar($glval, 'width_notebook', 1230); ?>',
		'tablet': '<?php echo RevSliderBase::getVar($glval, 'width_tablet', 992); ?>',
		'mobile': '<?php echo RevSliderBase::getVar($glval, 'width_mobile', 480); ?>'
	};
	
</script>

<div id="div_debug"></div>

<div class='unite_error_message' id="error_message" style="display:none;"></div>

<div class='unite_success_message' id="success_message" style="display:none;"></div>

<div id="viewWrapper" class="view_wrapper<?php echo $wrapperClass; ?>">
	<?php self::requireView($view); ?>
</div>

<div id="divColorPicker" style="display:none;"></div>

<?php self::requireView("system/dialog-video"); ?>
<?php self::requireView("system/dialog-global-settings"); ?>

<div class="tp-plugin-version">
	<span style="margin-right:15px">&copy; All rights reserved, <a href="http://www.themepunch.com" target="_blank">ThemePunch</a>  ver. <?php echo $revSliderVersion; ?></span>
</div>
<?php
/*
<script type="text/javascript">
	<span class="rs-shop">SHOP</span>
	jQuery(document).ready(function(){
		jQuery('.rs-shop').click(function(){
			
		});
	});
</script>
*/
?>
<div id="rs-shop-overview">
	<?php
	$shop_data = get_option('tp-shop');
	?>
</div>

<div id="rs-preview-wrapper" style="display: none;">
	<div id="rs-preview-wrapper-inner">
		<div id="rs-preview-info">
			<div class="rs-preview-toolbar">
				<a class="rs-close-preview"><i class="eg-icon-cancel"></i></a>
			</div>
			
			<div data-type="desktop" class="rs-preview-device_selector_prev rs-preview-ds-desktop selected"></div>									
			<div data-type="notebook" class="rs-preview-device_selector_prev rs-preview-ds-notebook"></div>					
			<div data-type="tablet" class="rs-preview-device_selector_prev rs-preview-ds-tablet"></div>					
			<div data-type="mobile" class="rs-preview-device_selector_prev rs-preview-ds-mobile"></div>
			
		</div>
		<div class="rs-frame-preview-wrapper">
			<iframe id="rs-frame-preview" name="rs-frame-preview"></iframe>
		</div>
	</div>
</div>
<form id="rs-preview-form" name="rs-preview-form" action="<?php echo RevSliderBase::$url_ajax_actions; ?>" target="rs-frame-preview" method="post">
	<input type="hidden" id="rs-client-action" name="client_action" value="">
	<input type="hidden" id="rs-nonce" name="rs-nonce" value="<?php echo $nonce; ?>">
	
	<!-- SPECIFIC FOR SLIDE PREVIEW -->
	<input type="hidden" name="data" value="" id="preview-slide-data">
	
	<!-- SPECIFIC FOR SLIDER PREVIEW -->
	<input type="hidden" id="preview_sliderid" name="sliderid" value="">
	<input type="hidden" id="preview_slider_markup" name="only_markup" value="">
</form>


<div id="dialog_preview_sliders" class="dialog_preview_sliders" title="Preview Slider" style="display:none;">
	<iframe id="frame_preview_slider" name="frame_preview_slider" style="width: 100%;"></iframe>
</div>

<script type="text/javascript">
    <?php
	$validated = get_option('revslider-valid', 'false');
	?>
	rs_plugin_validated = <?php echo ($validated == 'true') ? 'true' : 'false'; ?>;
	
    jQuery('body').on('click','.rs-preview-device_selector_prev', function() {
    	var btn = jQuery(this);
    	jQuery('.rs-preview-device_selector_prev.selected').removeClass("selected");    	
    	btn.addClass("selected");
    	
    	var w = parseInt(global_grid_sizes[btn.data("type")],0);
    	if (w>1450) w = 1450;
    	jQuery('#rs-preview-wrapper-inner').css({maxWidth:w+"px"});
    	
    });

    jQuery(window).resize(function() {
    	var ww = jQuery(window).width();
    	if (global_grid_sizes)
	    	jQuery.each(global_grid_sizes,function(key,val) {    		
	    		if (ww<=parseInt(val,0)) {
	    			jQuery('.rs-preview-device_selector_prev.selected').removeClass("selected");
	    			jQuery('.rs-preview-device_selector_prev[data-type="'+key+'"]').addClass("selected");
	    		}
	    	})
    })

	/* SHOW A WAIT FOR PROGRESS */
	function showWaitAMinute(obj) {
		var wm = jQuery('#waitaminute');

		// SHOW AND HIDE WITH DELAY
		if (obj.delay!=undefined) {

			punchgs.TweenLite.to(wm,0.3,{autoAlpha:1,ease:punchgs.Power3.easeInOut});
			punchgs.TweenLite.set(wm,{display:"block"});
			
			setTimeout(function() {
				punchgs.TweenLite.to(wm,0.3,{autoAlpha:0,ease:punchgs.Power3.easeInOut,onComplete:function() {
					punchgs.TweenLite.set(wm,{display:"block"});	
				}});  			
			},obj.delay)
		}

		// SHOW IT
		if (obj.fadeIn != undefined) {
			punchgs.TweenLite.to(wm,obj.fadeIn/1000,{autoAlpha:1,ease:punchgs.Power3.easeInOut});
			punchgs.TweenLite.set(wm,{display:"block"});
			
		}

		// HIDE IT
		if (obj.fadeOut != undefined) {
			punchgs.TweenLite.to(wm,obj.fadeOut/1000,{autoAlpha:0,ease:punchgs.Power3.easeInOut,onComplete:function() {
					punchgs.TweenLite.set(wm,{display:"block"});	
				}});  
		}

		// CHANGE TEXT
		if (obj.text != undefined) {
			switch (obj.text) {
				case "progress1":

				break;
				default:					
					wm.html('<div class="waitaminute-message"><i class="eg-icon-emo-coffee"></i><br>'+obj.text+'</div>');
				break;	
			}
		}
	}
</script>