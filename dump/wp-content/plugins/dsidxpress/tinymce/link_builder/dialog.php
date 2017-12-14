<?php
define('ZP_NO_REDIRECT', true);

// bootstrap our wordpress instance
$bootstrapSearchDir = dirname($_SERVER["SCRIPT_FILENAME"]);
$docRoot = dirname(isset($_SERVER["APPL_PHYSICAL_PATH"]) ? $_SERVER["APPL_PHYSICAL_PATH"] : $_SERVER["DOCUMENT_ROOT"]);

while (!file_exists($bootstrapSearchDir . "/wp-load.php")) {
	$bootstrapSearchDir = dirname($bootstrapSearchDir);
	if (strpos($bootstrapSearchDir, $docRoot) === false){
		$bootstrapSearchDir = "../../../../.."; // critical failure in our directory finding, so fall back to relative
		break;
	}
}
require_once($bootstrapSearchDir . '/wp-load.php');
require_once($bootstrapSearchDir . '/wp-admin/admin.php');
require_once(dirname( __FILE__ ) . '/../../admin.php');

if (!current_user_can("edit_posts"))
	wp_die("You can't do anything destructive in here, but you shouldn't be playing around with this anyway.");

global $wp_version, $tinymce_version;

$localUri = get_option("siteurl") . "/" . WPINC . "/js/";
if (is_ssl()) {
	$localUri = preg_replace('/http/', 'https', $localUri);
}
$adminUri = get_admin_url();
$property_types_html = "";
$property_types = dsSearchAgent_ApiRequest::FetchData('AccountSearchSetupPropertyTypes', array(), false, 60 * 60 * 24);
if(!empty($property_types)){
    $property_types = json_decode($property_types["body"]);
    foreach ($property_types as $property_type) {
        $checked_html = '';
        $name = htmlentities($property_type->DisplayName);
		$id = $property_type->SearchSetupPropertyTypeID;
        $property_types_html .= <<<HTML
{$id}: {$name},
HTML;
    }
}
$property_types_html = substr($property_types_html, 0, strlen($property_types_html)-1); 
$idxPagesUrl = get_admin_url().'edit.php?post_type=ds-idx-listings-page';
$pluginUrl = DSIDXPRESS_PLUGIN_URL;  
?>

<!DOCTYPE html>
<html>
<head>
	<title>dsIDXpress: Build Link</title>
	<script type="text/javascript">
		var dsIdxPluginUri = "<?php echo $pluginUrl; ?>";
	</script>
	<script src="<?php echo $localUri ?>/tinymce/tiny_mce_popup.js?ver=<?php echo urlencode($tinymce_version) ?>"></script>
	<script src="<?php echo $localUri ?>/tinymce/utils/mctabs.js?ver=<?php echo urlencode($tinymce_version) ?>"></script>
	<script src="<?php echo $localUri ?>jquery/jquery.js"></script>
	<script src="<?php echo $localUri ?>jquery/ui/jquery.ui.core.min.js"></script>
	<script src="<?php echo $localUri ?>jquery/ui/jquery.ui.widget.min.js"></script>
	<script src="<?php echo $localUri ?>jquery/ui/jquery.ui.mouse.min.js"></script>
	<script src="<?php echo $localUri ?>jquery/ui/jquery.ui.position.min.js"></script>
	<script src="<?php echo $localUri ?>jquery/ui/jquery.ui.menu.min.js"></script>
	<script src="<?php echo $localUri ?>jquery/ui/jquery.ui.autocomplete.min.js"></script>
	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing,geometry"></script>
	<script src="../../js/admin-utilities.js?ver=<?php echo urlencode(DSIDXPRESS_PLUGIN_VERSION) ?>"></script> 
	<script src="js/dialog.js?ver=<?php echo urlencode(DSIDXPRESS_PLUGIN_VERSION) ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $adminUri ?>../wp-includes/css/dashicons.min.css" />
	<link rel="stylesheet" type="text/css" href="../../css/admin-options.css?ver=<?php echo urlencode(DSIDXPRESS_PLUGIN_VERSION) ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo $adminUri ?>css/wp-admin.css" />
	<link rel="stylesheet" type="text/css" href="css/link_builder.css?foo=bar" />
</head>

<body class="wp-admin js admin-color-fresh">
	<div id="wpbody">
		<div class="postbox" id="ds-idx-dialog-notice">
			<div class="inside">
					<strong>NOTICE:</strong>
					<p>
						This tool is scheduled for removal. For future link insertion, please use the following steps:
						<ol>
						<li>Build your listings pages using the <a href="<?php echo $idxPagesUrl; ?>" target="_top">IDX Pages</a> section found in the left-hand navigation.</li>
						<li>Select the "Insert/edit link" button <img src="<?php echo DSIDXPRESS_PLUGIN_URL; ?>images/hyperlink-icon.png" alt="" style="position:relative; top:4px; width:20px;" /> from the text editor tool.</li>
						<li>Expand the "Or link to existing content" section and select from your available IDX Pages.</li>
						</ol>
					</p>
					<a href="#" style="float:right;" onclick="jQuery('#ds-idx-dialog-notice').remove(); return false;">close</a>
			</div>
		</div>
		<div class="postbox">
			<div class="inside">
                <input type="hidden" id="linkBuilderPropertyTypes" value="<?php echo $property_types_html ?>" />
				<?php dsSearchAgent_Admin::LinkBuilderHtml(true) ?>
			</div>
		</div>
	</div>
</body>
</html>
