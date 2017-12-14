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

$localJsUri = get_option("siteurl") . "/" . WPINC . "/js/";
if (is_ssl()) {
	$localJsUri = preg_replace('/http/', 'https', $localJsUri);
}
$options = get_option(DSIDXPRESS_OPTION_NAME);

$mls_rules = dsSearchAgent_ApiRequest::FetchData('MlsDisplayRules', array('SearchSetupID' => $options["SearchSetupID"]));
if(isset($mls_rules['body'])){
	$mls_rules = json_decode($mls_rules['body'], true);
}
$hideSold = isset($mls_rules['HideSoldPropertyFunctionality']);

$propertyTypes = dsSearchAgent_ApiRequest::FetchData("AccountSearchSetupPropertyTypes", array(), false, 60 * 60 * 24);
$propertyTypes = json_decode($propertyTypes["body"]);

if (!defined('ZPRESS_API')) {
	$availableLinks = dsSearchAgent_ApiRequest::FetchData("AccountAvailableLinks", array(), false, 0);
	$availableLinks = json_decode($availableLinks["body"]);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>dsIDXpress: Insert Properties</title>

	<script src="<?php echo $localJsUri ?>tinymce/tiny_mce_popup.js?ver=<?php echo urlencode($tinymce_version) ?>"></script>
	<script src="<?php echo $localJsUri ?>tinymce/utils/mctabs.js?ver=<?php echo urlencode($tinymce_version) ?>"></script>
	<script src="<?php echo $localJsUri ?>jquery/jquery.js"></script>
	<script>
		var ApiRequest = {
			uriBase: '<?php echo str_replace('tinymce/multi_listings/', '', plugin_dir_url(__FILE__)) . 'client-assist.php' ?>',
			searchSetupID: <?php echo $options["SearchSetupID"] ?>
		};
		var tabsEnabled = false;
	</script>
	<script src="js/dialog.js?ver=<?php echo urlencode(DSIDXPRESS_PLUGIN_VERSION) ?>"></script>
	<style type="text/css">
		* {
			font-family:Verdana,Arial;
			font-size:10px;
			line-height:15px;
		}
		body {
			background: none;
		}
		p {
			margin: 0 0 15px;
		}
		#insert, #cancel, #apply, .mceActionPanel .button, input.mceButton, .updateButton {
			width: 114px;
		}
		.panel_wrapper {
			padding-top: 13px;
		}
		select {
			width: 100%;
		}
		#property-type-container {
			height: 98px;
			overflow: auto;
			border:1px solid #DDDDDD;
			width: 100%;
		}
		label {
			cursor: pointer;
		}
		th {
			text-align: left;
			vertical-align: top;
		}
		td {
			padding-bottom: 7px;
		}
		.panel_wrapper div.current {
			height: 475px;
		}
		#universal-options {
			margin:10px auto 0;
		}
		#universal-options th {
			font-weight: bold;
			margin-right: 10px;
			width: 175px;
		}
		#number-to-display {
			width: 30px;
		}
		.mceActionPanel {
			padding-bottom: 40px;
		}
		.listing-status-table td {
			display: inline;
			padding: 0px;
		}
		<?php if (defined('ZPRESS_API')): ?>
		.panel_wrapper {
			border-top: 1px solid #919B9C;
		}
		<?php endif ?>
	</style>
</head>
<body>
	<p>
		Using dsIDXpress's Live Listings&#8471; shortcode functionality, you can easily insert real estate listings into any page or blog post.
		The listings will stay updated whether the page/post is viewed hours, weeks, or even years after the page/post is created!
	</p>
	<?php if (!defined('ZPRESS_API')): ?>
	<p>
		In order embed multiple listings into your page/post, you can either create a quick custom search or, if you have
		<a href="http://www.diversesolutions.com/dssearchagent-idx-solution.aspx" target="_blank">dsSearchAgent Pro</a>, use a pre-saved link
		you've already created in your <a href="http://controlpanel.diversesolutions.com/" target="_blank">Diverse Solutions Control Panel</a>.
		Simply choose a tab below, configure the options, and then click "Insert Listings" at the bottom.
	</p>
	<div class="tabs">
		<ul>
			<li id="custom_search_tab" class="current"><span><a href="javascript:void(0);" onclick="dsidxMultiListings.changeTab('quick-search')">Quick Search</a></span></li>
			<li id="saved_links_tab"><span><a href="javascript:void(0);" onclick="dsidxMultiListings.changeTab('pre-saved-links')">Pre-saved Links</a></span></li>
		</ul>
	</div>
	<script type="text/javascript"> tabsEnabled = true; </script>	
	<?php endif ?>	
	<div class="panel_wrapper">
		<div id="custom_search_panel" class="panel current">
			<table style="width: 100%;">
				<tr>
					<th style="width: 110px !important;">Area type</th>
					<td style="width: 220px;">
						<select id="area-type">
							<option value="city">City</option>
							<option value="community">Community</option>
							<option value="county">County</option>
							<option value="tract">Tract</option>
							<option value="zip">Zip</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>Area name</th>
					<td>
						<select id="area-name"></select>
					</td>
				</tr>
				<tr>
					<th>Price range</th>
					<td>
						<input type="text" id="min-price" style="width: 70px;" />
						-
						<input type="text" id="max-price" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Beds Min/Max</th>
					<td>
						<input type="text" id="min-beds" style="width: 70px;" />
						-
						<input type="text" id="max-beds" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Baths Min/Max</th>
					<td>
						<input type="text" id="min-baths" style="width: 70px;" />
						-
						<input type="text" id="max-baths" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Days on Market</th>
					<td>
						<input type="text" id="min-dom" style="width: 70px;" />
						-
						<input type="text" id="max-dom" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Year Built</th>
					<td>
						<input type="text" id="min-year" style="width: 70px;" />
						-
						<input type="text" id="max-year" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Home SqFt Range</th>
					<td>
						<input type="text" id="min-impsqft" style="width: 70px;" />
						-
						<input type="text" id="max-impsqft" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Lot SqFt Range</th>
					<td>
						<input type="text" id="min-lotsqft" style="width: 70px;" />
						-
						<input type="text" id="max-lotsqft" style="width: 70px;" />
					</td>
				</tr>
				<tr>
					<th>Status</th>
					<td>
						<div id="listing-status-container">
							<table class="listing-status-table">
								<tr>
									<td><input type="checkbox" id="status-1" name="status-1" value="1" />Active</td>
									<td><input type="checkbox" id="status-2" name="status-2" value="2" />Conditional</td>
								</tr>
								<?php if (defined('ZPRESS_API') || isset($options["dsIDXPressPackage"]) && $options["dsIDXPressPackage"] == "pro"): ?>
								<tr>
									<td><input type="checkbox" id="status-3" name="status-3" value="3" />Pending</td>
									<td>
										<?php if(!$hideSold): ?>
										<input type="checkbox" id="status-4" name="status-4" value="4" />Sold
										<?php endif; ?>
									</td>
								</tr>
								<?php endif ?>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						Property types
						<div style="margin-top: 5px; font-weight: normal;">(will use your defaults or the MLS's defaults if not selected)</div>
					</th>
					<td>
						<div id="property-type-container">
<?php
if (!empty($propertyTypes)) {
	foreach ($propertyTypes as $propertyType) {
		$name = htmlentities($propertyType->DisplayName);
		$id = $propertyType->SearchSetupPropertyTypeID;
		echo <<<HTML
							<input type="checkbox" name="property-type-{$id}" id="property-type-{$id}" value="{$id}" />
							<label for="property-type-{$id}">{$name}</label>
							<br />
HTML;
	}
}
?>
						</div>
					</td>
				</tr>
				<tr>
					<th>Display order</th>
					<td>
						<select id="display-order-column">
							<option value="DateAdded|DESC" selected="selected">Days on market, newest first</option>
							<option value="DateAdded|ASC">Days on market, oldest first</option>
							<option value="LastUpdated|DESC">Last updated, newest first</option>
							<option value="Price|ASC">Price, lowest first</option>
							<option value="Price|DESC">Price, highest first</option>
							<option value="SalePrice|ASC">Sale Price, lowest first</option>
							<option value="SalePrice|DESC">Sale Price, highest first</option>
							<option value="ImprovedSqFt|DESC">Home size, largest first</option>
							<option value="LotSqFt|DESC">Lot size, largest first</option>
							<option value="WalkScore|DESC">Walk Score&trade;, highest first</option>
							<option value="OverallPriceDropPercent|DESC">Price drop (%), highest first</option>
						</select>
					</td>
				</tr>
			</table>
		</div>

		<div id="saved_links_panel" class="panel">
			<p>Select the pre-saved search link that you'd like to use for these results. To create more or edit the
			existing links, you will need to login to the <a href="http://controlpanel.diversesolutions.com/" target="_blank">Diverse Solutions Control Panel</a>.</p>
			<div style="text-align: center;">
				<select id="saved-link">
<?php
if (!empty($availableLinks)) {
	foreach ($availableLinks as $link) {
		echo "<option value=\"{$link->LinkID}\" {$selectedLink[$link->LinkID]}>{$link->Title}</option>";
	}
}
?>
				</select>
			</div>
		</div>
	</div>

	<table id="universal-options">
		<tr>
			<th><label for="number-to-display">Number of listings to display</label></th>
			<td><input type="text" id="number-to-display" /></td>
		</tr>
		<tr>
			<th><label for="larger-photos">Show larger photos?</label></th>
			<td><input type="checkbox" id="larger-photos" /></td>
		</tr>
	</table>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="insert" name="insert" value="Insert listings" onclick="dsidxMultiListings.insert();" />
		</div>

		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</body>
</html>
