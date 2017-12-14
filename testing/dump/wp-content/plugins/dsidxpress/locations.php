<?php
// bootstrap our wordpress instance
$bootstrapSearchDir = dirname($_SERVER["SCRIPT_FILENAME"]);
$docRoot = dirname(isset($_SERVER["APPL_PHYSICAL_PATH"]) ? $_SERVER["APPL_PHYSICAL_PATH"] : $_SERVER["DOCUMENT_ROOT"]);

while (!file_exists($bootstrapSearchDir . "/wp-load.php")) {
	$bootstrapSearchDir = dirname($bootstrapSearchDir);
	if (strpos($bootstrapSearchDir, $docRoot) === false){
		$bootstrapSearchDir = "../../.."; // critical failure in our directory finding, so fall back to relative
		break;
	}
}

require_once($bootstrapSearchDir . "/wp-admin/admin.php");

if (!current_user_can("edit_pages"))
	wp_die("You can't do anything destructive in here, but you shouldn't be playing around with this anyway.");

$options = get_option(DSIDXPRESS_OPTION_NAME);
$requestUri = dsSearchAgent_ApiRequest::$ApiEndPoint . "LocationsByType";
$apiHttpResponse = (array)wp_remote_post($requestUri, array(
	"body"			=> array(
		searchSetupID	=> $options["SearchSetupID"],
		type			=> $_REQUEST["type"]
	),
	"httpversion"	=> "1.1",
	"redirection"	=> "0",
	"reject_unsafe_urls" => false
));
$locations = json_decode($apiHttpResponse["body"]);
?>
<!DOCTYPE html>
<html>
<head>
	<style>* { font-family:Verdana; } h2 { font-size: 14px; } body { font-size: 12px; }</style>
</head>
<body>
	<h2>Possible <?php echo ucwords($_REQUEST["type"]); ?> Locations</h2>
<?php
if(is_array($locations)){
	foreach ($locations as $location) {
		?><div><?php echo $location->Name; ?></div><?php
	}
}
?>
	</body>
</html>
