<?php

define('ZP_NO_REDIRECT', true);

//bootstrap wordpress
$bootstrapSearchDir = dirname($_SERVER["SCRIPT_FILENAME"]);
$docRoot = dirname(isset($_SERVER["APPL_PHYSICAL_PATH"]) ? $_SERVER["APPL_PHYSICAL_PATH"] : $_SERVER["DOCUMENT_ROOT"]);

while (!file_exists($bootstrapSearchDir . "/wp-load.php")) {
	$bootstrapSearchDir = dirname($bootstrapSearchDir);
	if (strpos($bootstrapSearchDir, $docRoot) === false){
		$bootstrapSearchDir = "../../.."; // critical failure in our directory finding, so fall back to relative
		break;
	}
}
require_once($bootstrapSearchDir . "/wp-load.php");
if(defined('ZPRESS_API') && ZPRESS_API != '') {
	require_once(WPMU_PLUGIN_DIR . '/akismet/loadAkismet.php');

}

class dsSearchAgent_ClientAssist {
    static public function call($method) 
    { 
        if(method_exists('dsSearchAgent_ClientAssist', $method)) { 
			call_user_func(array('dsSearchAgent_ClientAssist', $method));
        }else{ 
        	die();
        } 
    } 

	static function SlideshowXml() {
		$uriSuffix = '';
		if (array_key_exists('uriSuffix', $_GET))
			$uriSuffix = $_GET['uriSuffix'];

		$urlBase = $_GET['uriBase'];

		if (!preg_match("/^http:\/\//", $urlBase))
			$urlBase = "http://" . $urlBase;
		$urlBase = str_replace(array('&', '"'), array('&amp;', '&quot;'), $urlBase);

		header('Content-Type: text/xml');
		echo '<?xml version="1.0"?><gallery><album lgpath="' . $urlBase . '" tnpath="' . $urlBase . '">';
		for($i = 0; $i < (int)$_GET['count']; $i++) {
			echo '<img src="' . $i . '-full.jpg' . $uriSuffix . '" tn="' . $i . '-medium.jpg' . $uriSuffix . '" link="javascript:dsidx.details.LaunchLargePhoto('. $i .','. $_GET['count'] .',\''. $urlBase .'\',\''. $uriSuffix .'\')" target="_blank" />';
		}
		echo '</album></gallery>';
	}
	static function SlideshowParams() {
		$count = @$_GET['count'];
		$uriSuffix = @$_GET['uriSuffix'];
		$uriBase = @$_GET['uriBase'];

		$slideshow_xml_url = dsSearchAgent_ApiRequest::MakePluginsUrlRelative(plugin_dir_url(__FILE__) . "client-assist.php?action=SlideshowXml&count=$count&uriSuffix=$uriSuffix&uriBase=$uriBase");
		$param_xml = file_get_contents('assets/slideshowpro-generic-params.xml');

		$param_xml = str_replace("{xmlFilePath}", htmlspecialchars($slideshow_xml_url), $param_xml);
		$param_xml = str_replace("{imageTitle}", "", $param_xml);

		header('Content-Type: text/xml');
		echo($param_xml);
	}
	static function EmailFriendForm() {
		$referring_url = $_SERVER['HTTP_REFERER'];
		$post_vars = $_POST;
		$post_vars["referringURL"] = $referring_url;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("EmailFriendForm", $post_vars, false, 0);

		echo $apiHttpResponse["body"];
		die();
	}
	static function LoginRecovery(){
		global $curent_site, $current_blog, $blog_id;
		
		$referring_url = $_SERVER['HTTP_REFERER'];
		$post_vars = $_POST;
		$post_vars["referringURL"] = $referring_url;
		$post_vars["domain"] = $current_blog->domain;
		$post_vars["path"] = $current_blog->path;
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("LoginRecovery", $post_vars, false, 0);
		
		echo $apiHttpResponse["body"];
		die();
	}
	static function ResetPassword()
	{
		$referring_url = $_SERVER['HTTP_REFERER'];
		$post_vars = $_POST;
		$post_vars["referringURL"] = $referring_url;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("ResetPassword", $post_vars, false, 0);
		
		echo $apiHttpResponse["body"];
		die();	
	}
	static function ContactForm(){
		$referring_url = @$_SERVER['HTTP_REFERER'];
		$post_vars = $_POST;
		$post_vars["referringURL"] = $referring_url;

		//Fix up post vars for Beast ContactForm API
		if (isset($post_vars['name']) && !isset($post_vars['firstName'])) {
			if(empty($post_vars['name']) || !is_email($post_vars['emailAddress'])){
				header('Content-type: application/json');
				echo '{ "Error": true, "Message": "Failed to submit." }';
				die();
	        }
			$name = $post_vars['name'];
			$name_split = preg_split('/[\s]+/', $post_vars['name'], 2, PREG_SPLIT_NO_EMPTY);
			$post_vars['firstName'] = count($name_split) > 0 ? $name_split[0] : '';
			$post_vars['lastName'] = count($name_split) > 1 ? $name_split[1] : '';
		}
		if (isset($post_vars['firstName']) && !isset($post_vars['name'])) {
			if(empty($post_vars['firstName']) || empty($post_vars['lastName']) || !is_email($post_vars['emailAddress'])){
				header('Content-type: application/json');
				echo '{ "Error": true, "Message": "Failed to submit." }';
				die();
	        }
	    }
		if (!isset($post_vars['phoneNumber'])) $post_vars['phoneNumber'] = '';
		
		$message = (!empty($post_vars['scheduleYesNo']) && $post_vars['scheduleYesNo'] == 'on' ? "Schedule showing on {$post_vars['scheduleDateMonth']} / {$post_vars['scheduleDateDay']} " : "Request info ") . 
						@"for ".(!empty($post_vars['propertyStreetAddress']) ? $post_vars['propertyStreetAddress']:"")." ".(!empty($post_vars['propertyCity']) ? $post_vars['propertyCity'] : "").", ".(!empty($post_vars['propertyState']) ? $post_vars['propertyState'] : "")." ".(!empty($post_vars['propertyZip']) ? $post_vars['propertyZip'] : "").
						@". ".$post_vars['comments'];

		if(defined('ZPRESS_API') && ZPRESS_API != ''){
			$z_akismet_values = array(
                'author' => isset($post_vars['name']) ? $post_vars['name'] : $post_vars['firstName']." ".$post_vars['lastName'],
                'email' => $post_vars['emailAddress'],
                'website' => '', 
                'body' => $message,
                'permalink' => '', 
                'comment_type' => 'ContactFormSubmission', //set for all contact requests
	        );
			$z_akismet = createAkismet(get_option('siteurl'), Z_AKISMET_API_KEY, $z_akismet_values);
			if ($z_akismet->errorsExist()) {
				call_user_func('zpress\api_request::call', '/Spam/SaveMessage', array( 'TypeID' => 3, 'Message' => json_encode($z_akismet->getErrors()) ));
				header('Content-type: application/json');
				echo '{ "Error": true, "Message": "Failed to submit." }';
				die();
			} else {
				if ($z_akismet->isSpam()) {
					call_user_func('zpress\api_request::call', '/Spam/SaveMessage', array( 'TypeID' => 1, 'Message' => json_encode($z_akismet_values) ));
				} else {
					call_user_func('zpress\api_request::call', '/Spam/SaveMessage', array( 'TypeID' => 2, 'Message' => json_encode($z_akismet_values) ));
					if(SNS_ARN_CONTACT_REQUEST != ''){
						$firstname = '';
						$lastname = '';
						if(!empty($post_vars['name'])){
							$name = $post_vars['name'];
							$name_split = preg_split(' ', $post_vars['name'], 2, PREG_SPLIT_NO_EMPTY);
							if(!empty($name_split)){
								$firstname = $name_split[0];
								if(count($name_split) > 1)
									$lastname = $name_split[1];
							}
						}

						if(!empty($post_vars['firstName'])) $firstname = $post_vars['firstName'];
						if(!empty($post_vars['lastName'])) $lastname = $post_vars['lastName'];

						// call sns to send the contact to Zillow.com
						$sns = new AmazonSNS( defined(AWS_KEY) ? 
							array('certificate_authority' => true, 'key' => AWS_KEY, 'secret' => AWS_SECRET_KEY) : 
							array('certificate_authority' => true));
						$sns->publish(SNS_ARN_CONTACT_REQUEST, json_encode((object) array(
							'ContactDate' => gmdate('Y-m-d\TH:i:s.uP'),
							'Email' => @$post_vars['emailAddress'],
							'FirstName' => $firstname,
							'LastName' => $lastname,
							'Message' => $message,
							'Phone' => @$post_vars['phoneNumber'],
							'ListingUrl' => @$post_vars['referringURL'],
							//'Subject' => "",
							'Zuid' => get_option('zuid'),
							'Uid' => md5(uniqid())
						)));
					}
					header('Content-type: application/json');
					echo '{ "Error": false, "Message": "" }';
					die();
				}
			}
		} else {
			$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("ContactForm", $post_vars, false, 0);

			if (false && $_POST["returnToReferrer"] == "1") {
				$post_response = json_decode($apiHttpResponse["body"]);

				if ($post_response->Error == 1)
					$redirect_url = $referring_url .'?dsformerror='. $post_response->Message;
				else
					$redirect_url = $referring_url;

				header( 'Location: '. $redirect_url ) ;
				die();
			} else {
				echo $apiHttpResponse["body"];
				die();
			}
		}
		header('Content-type: application/json');
		echo '{ "Error": false, "Message": "" }';
		die();
	}
	static function PrintListing(){
		if($_REQUEST["PropertyID"]) $apiParams["query.PropertyID"] = $_REQUEST["PropertyID"];
		if($_REQUEST["MlsNumber"]) $apiParams["query.MlsNumber"] = $_REQUEST["MlsNumber"];
		$apiParams["responseDirective.ViewNameSuffix"] = "print";
		$apiParams["responseDirective.IncludeDisclaimer"] = "true";
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Details", $apiParams, false);

		header('Cache-control: private');
		header('Pragma: private');
		header('X-Robots-Tag: noindex');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

		echo($apiHttpResponse["body"]);

		die();
	}
	static function OnBoard_GetAccessToken(){
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("OnBoard_GetAccessToken");
		echo $apiHttpResponse["body"];
		die();
	}
	static function Login(){
		$post_vars = $_POST;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Login", $post_vars, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
		
		if($response->Success){			
			$remember = !empty($_POST["remember"]) && $_POST["remember"] == "on" ? time()+60*60*24*30 : 0;
			
			setcookie('dsidx-visitor-public-id', $response->Visitor->PublicID, $remember, '/');
			setcookie('dsidx-visitor-auth', $response->Visitor->Auth, $remember, '/');
		}

		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function ValidateLogout() {
		// Already logged out
		if ($_COOKIE['dsidx-visitor-auth'] == '')
		{
			header('Content-Type: application/json');
			echo '{ success:false }';
			die();
		}

		$post_vars = $_POST;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Logout", $post_vars, false, 0);

		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];

		die();
	}
	static function Logout() {
		$post_vars = $_GET;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Logout", $post_vars, false, 0);
		echo $apiHttpResponse["body"];

		die();
	}
	static function GetVisitor() {
		$post_vars = $_POST;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("GetVisitor", $post_vars, false, 0);

		$response = json_decode($apiHttpResponse["body"]);

		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function isOptIn(){
		$post_vars = $_GET;
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("isOptIn", $post_vars, false, 0, null);
		$response = json_decode($apiHttpResponse["body"]);

		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();	
	}
	static function SsoAuthenticated () {
		$post_vars = $_GET;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("SSOAuthenticated", $post_vars, false, 0, null);
		$response = json_decode($apiHttpResponse["body"]);
		
		if($response->Success){			
			$remember = !empty($_POST["remember"]) && $_POST["remember"] == "on" ? time()+60*60*24*30 : 0;
			
			setcookie('dsidx-visitor-public-id', $response->Visitor->PublicID, $remember, '/');
			setcookie('dsidx-visitor-auth', $response->Visitor->Auth, $remember, '/');
		} else {
			if (isset($_COOKIE['dsidx-visitor-auth']) && $_COOKIE['dsidx-visitor-auth'] != '') {
				// This means the user is no longer logged in globally.
				// So log out of the current session by removing the cookie.
				setcookie('dsidx-visitor-public-id', '', time()-60*60*24*30, '/');
				setcookie('dsidx-visitor-auth', '', time()-60*60*24*30, '/');
			}
		}

		header('Location: ' . $response->Origin);
	}
	static function SsoAuthenticate () {
		$post_vars = $_GET;
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("SSO", $post_vars, false, 0, null, true);
	}
	static function SsoSignout () {
		$post_vars = $_GET;

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("SSOSignOut", $post_vars, false, 0, null, true);
	}
	static function Register(){
		
		foreach($_POST as $key => $value) {
			$post_vars[str_replace('newVisitor_', 'newVisitor.', $key)] = $_POST[$key];
		}

		if(defined('ZPRESS_API') && ZPRESS_API != ''){
			if(SNS_ARN_CONTACT_REQUEST != ''){
				$name = $post_vars['name'];
				$name_split = preg_split('/[\s]+/', $post_vars['name'], 2, PREG_SPLIT_NO_EMPTY);

				// call sns to send the contact to Zillow.com
				$sns = new AmazonSNS( defined(AWS_KEY) ? 
						array('certificate_authority' => true, 'key' => AWS_KEY, 'secret' => AWS_SECRET_KEY) : 
						array('certificate_authority' => true));
				$sns->publish(SNS_ARN_CONTACT_REQUEST, json_encode((object) array(
					'ContactDate' => gmdate('Y-m-d\TH:i:s.uP'),
					'Email' => $post_vars['newVisitor.Email'],
					'FirstName' => $post_vars['newVisitor.FirstName'],
					'LastName' => $post_vars['newVisitor.LastName'],
					'Message' => 'Registered new IDX account',
					'Phone' => $post_vars['newVisitor.PhoneNumber'],
					//'Subject' => '',
					'Zuid' => get_option('zuid'),
					'ListingUrl' => @$post_vars['newVisitor.ListingUrl'],
					'Uid' => md5(uniqid()),
					'optIn' => $post_vars['optIn'],
					'isOptIn' => $post_vars['isOptIn']
				)));
			}
			$post_vars["skipThirdParty"] = 'true';
		}
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Register", $post_vars, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
		
		if($response->Success){			
			$remember = @$_POST["remember"] == "on" ? time()+60*60*24*30 : 0;
			
			setcookie('dsidx-visitor-public-id', $response->Visitor->PublicID, $remember, '/');
			setcookie('dsidx-visitor-auth', $response->Visitor->Auth, $remember, '/');
		}
		
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function UpdatePersonalInfo(){
		
		foreach($_POST as $key => $value) {
			$post_vars[str_replace('personalInfo_', 'personalInfo.', $key)] = $_POST[$key];
		}
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("UpdatePersonalInfo", $post_vars, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
		
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function Searches(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Searches", null, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}

	static function UpdateSavedSearchTitle(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("UpdateSavedSearchTitle", $_POST, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}


	static function ToggleSearchAlert(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("ToggleSearchAlert", $_POST, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function DeleteSearch(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("DeleteSearch", $_POST, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function FavoriteStatus(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("FavoriteStatus", $_POST, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function Favorite(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Favorite", $_POST, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: application/json');
		echo $apiHttpResponse["body"];
		die();
	}
	static function VisitorListings(){
				
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("VisitorListings", $_POST, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
				
		header('Content-Type: text/html');
		echo $apiHttpResponse["body"];
		die();
	}
	static function LoadAreasByType(){
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("LocationsByType", $_REQUEST, false, 0);
		if(!isset($_REQUEST['dataField'])){
			$response = json_decode($apiHttpResponse["body"]);
			header('Content-Type: application/json');
			echo $apiHttpResponse["body"];
		}
		else{
			$response = json_decode($apiHttpResponse["body"], true);
			$r = array();
			foreach($response as $item){
				if(isset($item[$_REQUEST['dataField']])){
					$r[] = $item[$_REQUEST['dataField']];
				}
			}
			echo json_encode($r);
		}
		die();
	}
	static function LoadSimilarListings() {
		$apiParams = array();
		$apiParams["query.SimilarToPropertyID"] = $_POST["PropertyID"];
		$apiParams["query.ListingStatuses"] = '1';
		$apiParams['responseDirective.ViewNameSuffix'] = 'Similar';
		$apiParams['responseDirective.IncludeDisclaimer'] = 'true';
		$apiParams['directive.ResultsPerPage'] = '6';

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Results", $apiParams, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
		header('Content-Type: text/html');
		echo $apiHttpResponse["body"];
		die();
	}
	static function LoadSoldListings(){
		$apiParams = array();

		$apiParams["query.SimilarToPropertyID"] = $_POST["PropertyID"];
		$apiParams["query.ListingStatuses"] = '8';
		$apiParams['responseDirective.ViewNameSuffix'] = 'Sold';
		$apiParams['directive.ResultsPerPage'] = '6';

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Results", $apiParams, false, 0);

		$response = json_decode($apiHttpResponse["body"]);
		header('Content-Type: text/html');
		echo $apiHttpResponse["body"];
		die();
	}
	static function LoadSchools() {
		$apiParams = array();

		$apiParams['responseDirective.ViewNameSuffix'] = 'Schools';
		$apiParams['query.City'] = $_POST['city'];
		$apiParams['query.State'] = $_POST['state'];
		$apiParams['query.Zip'] = $_POST['zip'];
		$apiParams['query.Spatial'] = $_POST['spatial'];
		$apiParams['query.PropertyID'] = $_POST['PropertyID'];

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Schools", $apiParams, false);

		$response = json_decode($apiHttpResponse["body"]);
		header('Content-Type: text/html');
		echo $apiHttpResponse["body"];
		die();
	}
	static function LoadDistricts() {
		$apiParams = array();

		$apiParams['responseDirective.ViewNameSuffix'] = 'Districts';
		$apiParams['query.City'] = $_POST['city'];
		$apiParams['query.State'] = $_POST['state'];
		$apiParams['query.Spatial'] = $_POST['spatial'];
		$apiParams['query.PropertyID'] = $_POST['PropertyID'];

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Districts", $apiParams, false);

		$response = json_decode($apiHttpResponse["body"]);
		header('Content-Type: text/html');
		echo $apiHttpResponse["body"];
		die();
	}
	static function AutoComplete() {
		$apiParams = array();
		
		$apiParams['query.partialLocationTerm'] = $_GET['term'];
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData('AutoCompleteOmniBox', $apiParams, false, 0);
		
		header('Content-Type: application/json');
		echo $apiHttpResponse['body'];
		die();
	}
	static function GetPhotosXML() {
		$post_vars = array_map("stripcslashes", $_GET);
		$apiRequestParams = array();
		$apiRequestParams['propertyid'] = $post_vars['pid'];
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData('Photos', $apiRequestParams, false);
		header('Content-type: text/xml');
		echo $apiHttpResponse['body'];
		die();
	}
}
if(!empty($_REQUEST['action']))
{
	dsSearchAgent_ClientAssist::call($_REQUEST['action']);
}
else
{
	die;
}
?>