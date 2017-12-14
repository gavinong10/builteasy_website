<?php
add_action("pre_get_posts", array("dsSearchAgent_Client", "PreActivate"));
add_filter("posts_request", array("dsSearchAgent_Client", "ClearQuery"));
add_filter("the_posts", array("dsSearchAgent_Client", "Activate"));
add_filter("comments_template", array("dsSearchAgent_Client", "CleanCommentsBlock"), 500);

class dsSearchAgent_Client {
	static $Options = null;
	static $CanonicalUri = null;
	static $TriggeredAlternateUrlStructure = null;
	static $QueryStringTranslations = array(
		"a" => "action",
		"q" => "query",
		"d" => "directive"
	);
	static $DebugAllowedFrom = array("127.0.0.1", "::1:", "10.10.10.10", "50.59.180.51", "50.59.180.50");
	static $meta_tag_data = null;
	// this is a roundabout way to make sure that any other plugin / widget / etc that uses the WP_Query object doesn't get our IDX data
	// in their query. since we don't actually get the query itself in the "the_posts" filter, we have to step around the issue by
	// checking it BEFORE it gets to the the_posts filter. later, in the the_posts filter, we restore the previous state of things.
	static function PreActivate($q) {
		global $wp_query;

		if (!is_array($wp_query->query) || !is_array($q->query) || isset($wp_query->query["suppress_filters"]) || isset($q->query["suppress_filters"])) {
			return;
		}

		if (isset($wp_query->query["idx-action"])) {
			if (!isset($q->query["idx-action"])) {
				$wp_query->query["idx-action-swap"] = $wp_query->query["idx-action"];
				unset($wp_query->query["idx-action"]);
			} else {
				$q->query_vars["ignore_sticky_posts"] = true;
			}
		}
	}
	static function Activate($posts, $params=array(), $idx_page_id=false) {
		global $wp_query;

		$get = array_merge($params, self::GetUrlParams());
		$prefix = '/?';
		$pattern = '/page-[0-9]{1,3}/';
		preg_match($pattern, $_SERVER['REQUEST_URI'], $matches);
		if(isset($matches[0])){
			$prefix = '/'.$matches[0].'?';
		}
		$wp_query->query['idx-result-params'] = $prefix . http_build_query($get);

		// for remote debugging
		if (in_array($_SERVER["REMOTE_ADDR"], self::$DebugAllowedFrom)) {
			if (isset($get["debug-wpquery"])) {
				print_r($wp_query);
				exit();
			}
			if (isset($get["debug-posts"])) {
				print_r($posts);
				exit();
			}
			if (isset($get["debug-plugins"])) {
				foreach (get_option("active_plugins") as $plugin) {
					print_r(get_plugin_data(WP_CONTENT_DIR . "/plugins/$plugin"));
					print_r("\n");
				}
				exit();
			}
			if (isset($get["debug-php"])) {
				phpinfo();
				exit();
			}
			if (isset($get["flush-idx-transients"])) {
				global $wpdb;
				$wpdb->query("DELETE FROM `{$wpdb->prefix}options` WHERE option_name LIKE '_transient_idx_%' OR option_name LIKE '_transient_timeout_idx_%'");
			}
		}

		$options = get_option(DSIDXPRESS_OPTION_NAME);
		if(!empty($wp_query->query["idx-action"]))
			$action = strtolower($wp_query->query["idx-action"]);

		if (!isset($options["Activated"])) {
			$wp_query->query_vars['error'] = '404';
			return $posts;
		}
		
		// the dsidxpress js that's on the CDN unfortunately looks to jquery to register a document.ready function. i dont like
		// having to include this on every page, but we have to pick our battles carefully. hopefully we can fix this someday.
		wp_enqueue_script('jquery');

		// see comment above PreActivate
		if (is_array($wp_query->query) && isset($wp_query->query["idx-action-swap"])) {
			$wp_query->query["idx-action"] = $wp_query->query["idx-action-swap"];
			unset($wp_query->query["idx-action-swap"]);
			return $posts;
		}

		if (!is_array($wp_query->query) || !isset($wp_query->query["idx-action"])) {
			return $posts;
		}

		$apiQueryOnlyParams = self::GetApiParams($get, true);

		if ($action == "results"
		    && empty($apiQueryOnlyParams["query.Locations"])
		    && empty($apiQueryOnlyParams["query.Cities"])
		    && empty($apiQueryOnlyParams["query.Communities"])
		    && empty($apiQueryOnlyParams["query.TractIdentifiers"])
		    && empty($apiQueryOnlyParams["query.Areas"])
		    && empty($apiQueryOnlyParams["query.ZipCodes"])
		    && empty($apiQueryOnlyParams["query.LatitudeMin"])
		    && empty($apiQueryOnlyParams["query.MlsNumbers"])
		    && empty($apiQueryOnlyParams["query.ListingAgentID"])
		    && empty($apiQueryOnlyParams["query.ListingOfficeID"])
		    && empty($apiQueryOnlyParams["query.AddressMask"])
		    && empty($apiQueryOnlyParams["query.AddressMasks"])
		    && empty($apiQueryOnlyParams["query.Counties"])
		    && empty($apiQueryOnlyParams["query.Schools"])
		    && empty($apiQueryOnlyParams["query.Schools.Name"])
		    && empty($apiQueryOnlyParams["query.Schools.Type"])

		    && empty($apiQueryOnlyParams["query.Locations[0]"])
		    && empty($apiQueryOnlyParams["query.Cities[0]"])
		    && empty($apiQueryOnlyParams["query.Communities[0]"])
		    && empty($apiQueryOnlyParams["query.TractIdentifiers[0]"])
		    && empty($apiQueryOnlyParams["query.Areas[0]"])
		    && empty($apiQueryOnlyParams["query.ZipCodes[0]"])
		    && empty($apiQueryOnlyParams["query.LatitudeMin[0]"])
		    && empty($apiQueryOnlyParams["query.MlsNumbers[0]"])
		    && empty($apiQueryOnlyParams["query.ListingAgentID[0]"])
		    && empty($apiQueryOnlyParams["query.ListingOfficeID[0]"])
		    && empty($apiQueryOnlyParams["query.AddressMask[0]"])
		    && empty($apiQueryOnlyParams["query.AddressMasks[0]"])
		    && empty($apiQueryOnlyParams["query.Counties[0]"])
		    && empty($apiQueryOnlyParams["query.Schools[0]"])
		    && empty($apiQueryOnlyParams["query.Schools[0].Name"])
		    && empty($apiQueryOnlyParams["query.Schools[0].Type"])

		    && empty($apiQueryOnlyParams["query.LinkID"])
		    && empty($apiQueryOnlyParams["query.PropertySearchID"])
		    && empty($apiQueryOnlyParams["query.RadiusDistance"])
		) {
			// we used to null out the $posts here, but we're going to try to just noindex instead, so we don't block a 
			// user from using the search intarface however they want.
			add_action("wp_head", array("dsSearchAgent_Client", "NoIndex"));
		} else if($options["SearchSetupID"] == "124") {
			// set no-index on test-data pages
			add_action("wp_head", array("dsSearchAgent_Client", "NoIndex"));
		}

		// keep wordpress from mucking up our HTML
		remove_filter("the_content", "wptexturize");
		remove_filter("the_content", "convert_smilies");
		remove_filter("the_content", "convert_chars");
		remove_filter("the_content", "wpautop");
		remove_filter("the_content", "prepend_attachment");

		add_filter('get_edit_post_link', array('dsSearchAgent_Client', 'RemoveEditLink'), 10, 3);
		add_filter('post_class', array('dsSearchAgent_Client', 'RemoveGoogleDataClasses'));

		// we handle our own redirects and canonicals
		add_filter("wp_redirect", array("dsSearchAgent_Client", "CancelAllRedirects"));
		add_filter("redirect_canonical", array("dsSearchAgent_Client", "CancelAllRedirects"));
		add_filter("page_link", array("dsSearchAgent_Client", "GetPermalink")); // for any plugin that needs it

		// "All in One SEO Pack" tries to do its own canonical URLs as well. we disable them here only to prevent
		// duplicate canonical elements. even if this fell through w/ another plugin though, the page_link filter would
		// ensure that the permalink is correct
		global $aioseop_options;
		if ($aioseop_options["aiosp_can"])
			$aioseop_options["aiosp_can"] = false;

		// we don't support RSS feeds just yet
		remove_action("wp_head", "feed_links");
		remove_action("wp_head", "feed_links_extra");

		$wp_query->found_posts = 0;
		$wp_query->max_num_pages = 0;
		$wp_query->is_page = 1;
		$wp_query->is_home = null;
		$wp_query->is_singular = 1;

		if($action == "framed")
			return self::FrameAction($action, $get, $idx_page_id);
		else
			return self::ApiAction($action, $get, $idx_page_id);
	}

	static function FrameAction($action, $get, $idx_page_id=false){
		global $wp_query;
		$options = get_option(DSIDXPRESS_OPTION_NAME);

		$post_id = !$idx_page_id?time():$idx_page_id;

		if ($options["AdvancedTemplate"])
			wp_cache_set($post_id, array("_wp_page_template" => array($options["AdvancedTemplate"])), "post_meta");

		$description = NULL;
		$title = NULL;
		$script_code = '<script src="http://idx.diversesolutions.com/scripts/controls/Remote-Frame.aspx?MasterAccountID='. $options['AccountID'] .'&amp;SearchSetupID='. $options['SearchSetupID'] .'&amp;LinkID=0&amp;Height=2000"></script>';

		set_query_var("name", "dsidxpress-{$action}"); // at least a few themes require _something_ to be set here to display a good <title> tag
		set_query_var("pagename", "dsidxpress-{$action}"); // setting pagename in case someone wants to do a custom theme file for this "page"

		$post = (object)array(
			"ID"				=> $post_id,
			"comment_count"		=> 0,
			"comment_status"	=> "closed",
			"ping_status"		=> "closed",
			"post_author"		=> 1,
			"post_content"		=> $script_code,
			"post_date"			=> date("c"),
			"post_date_gmt"		=> gmdate("c"),
			"post_excerpt"		=> $description,
			"post_name"			=> "dsidxpress-data",
			"post_parent"		=> 0,
			"post_status"		=> "publish",
			"post_title"		=> $title,
			"post_type"			=> "page"
		);
		if(!$idx_page_id){
			wp_cache_set( $post_id, $post, 'posts');
		}
		$posts = array( $post );

		return $posts;
	}

	static function GetUrlParams($get=array()){
		// wordpress adds magic quotes for us automatically. this quoting behavior seems to be pretty old and well built in, and so we're going to
		// forcefully strip them out. see http://core.trac.wordpress.org/browser/trunk/wp-includes/load.php?rev=12732#L346 for an example of how long
		// this has existed for
		if(empty($get)){
			$get = stripslashes_deep($_GET);
		}
		else{
			$get = stripslashes_deep($get);
		}
		// we're going to make our own _corrected_ array for the superglobal $_GET due to bugs in the "preferred" way to host WP on windows w/ IIS 6.
		// the reason for this is because the URL that handles the request becomes wp-404-handler.php and _SERVER["QUERY_STRING"] subsequently ends up
		// being in the format of 404;http://<domain>:<port>/<url>?<query-arg-1>&<query-arg-2>. the result of that problem is that the first query arg
		// ends up becoming the entire request url up to the second query param

		$getKeys = array_keys($get);
		if (isset($getKeys[0]) && strpos($getKeys[0], "404;") === 0) {
			$get[substr($getKeys[0], strpos($getKeys[0], "?") + 1)] = $get[$getKeys[0]];
			unset($get[$getKeys[0]]);
		}
		return $get;
	}

	static function GetApiParams($get, $onlyQueryParams = false) {
		global $wp_query;
		
		$apiParams = array();
		foreach ($wp_query->query as $key => $value) {
			if (strpos($key, "idx-q") === false && ((!$onlyQueryParams && strpos($key, "idx-d") === false) || $onlyQueryParams))
				continue;
			if ($value == '') // don't use empty() here, need to pass through values of '0', and empty will return true on '0'
				continue;

			$key = str_replace(array("-", "<", ">"), array(".", "[", "]"), substr($key, 4));
			$key = self::$QueryStringTranslations[substr($key, 0, 1)] . substr($key, strpos($key, "."));
			$value = str_replace("_", "-", str_replace("-", " ", $value));
			$value = str_replace(";amp;", "&", $value);
			$apiParams[(string)$key] = $value;
		}
		foreach ($get as $key => $value) {
			if (strpos($key, "idx-q") === false && ((!$onlyQueryParams && strpos($key, "idx-d") === false) || $onlyQueryParams))
				continue;
			if ($value == '') // don't use empty() here, need to pass through values of '0', and empty will return true on '0'
				continue;

			$key = str_replace(array("-", "<", ">"), array(".", "[", "]"), substr($key, 4));
			$key = self::$QueryStringTranslations[substr($key, 0, 1)] . substr($key, strpos($key, "."));

			$apiParams[(string)$key] = $value;
		}
		return $apiParams;
	}
	static function ApiAction($action, $get, $idx_page_id=null) {
		global $wp_query;
		$options = get_option(DSIDXPRESS_OPTION_NAME);
		
		$post_id = !$idx_page_id?time():$idx_page_id;

		wp_enqueue_script("jquery-ui-dialog", false, array(), false, true);
		
		add_action("wp_head", array("dsSearchAgent_Client", "Header"));

		// allow wordpress to consume the page template option the user choose in the dsIDXpress settings
		if ($action == "results" && !empty($options["ResultsTemplate"]))
			wp_cache_set($post_id, array("_wp_page_template" => array($options["ResultsTemplate"])), "post_meta");
		else if ($action == "details" && !empty($options["DetailsTemplate"]))
			wp_cache_set($post_id, array("_wp_page_template" => array($options["DetailsTemplate"])), "post_meta");

		$apiParams = self::GetApiParams($get);
		
		// pull account options
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("AccountOptions");
		if (!empty($apiHttpResponse["errors"]) || $apiHttpResponse["response"]["code"] != "200"){
			switch ($apiHttpResponse["response"]["code"]) {
				case 403:
					wp_die(
						"We're sorry, but there’s nothing to display here; MLS data service is not activated for this account.");
				break;
				default:
					wp_die("We're sorry, but we ran into a temporary problem while trying to load the account data. Please check back soon.", "Account data load error");
			}
		} else {
			$account_options = json_decode($apiHttpResponse["body"]);	
		}
		if ($action == "results" || $action == "search") dsidxpress_autocomplete::AddScripts(false);

		if ($action == "results") {
			// save search
			if(!empty($get["idx-save"]) && $get["idx-save"] == "true") {
				$apiParams["name"] = $get["idx-save-name"];
				$apiParams["updates"] = $get["idx-save-updates"] == "on" ? "true" : "false";

				$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("SaveSearch", $apiParams, false, 0);
				
				$response = json_decode($apiHttpResponse["body"]);				
				
				header('Content-Type: application/json');
				echo $apiHttpResponse["body"];
				die();
			}
			
			// check allowed searched before registration
			$allow_results_view = 1;
			if (!empty($account_options->AllowedSearchesBeforeRegistration) && isset($_COOKIE['dsidx-visitor-results-views'])) {
				if ((int) $account_options->AllowedSearchesBeforeRegistration <= (int) $_COOKIE['dsidx-visitor-results-views']) {
					$allow_results_view = 0;
				}
			}
			$apiParams["requester.AllowVisitorResultsView"] = $allow_results_view;

			if (isset($apiParams["query.LinkID"]))
				$apiParams["query.ForceUsePropertySearchConstraints"] = "true";
			if (isset($apiParams["query.PropertySearchID"]))
				$apiParams["query.ForceUsePropertySearchConstraints"] = "true";
			$apiParams["directive.ResultsPerPage"] = 25;
			if (isset($apiParams["directive.ResultPage"]))
				$apiParams["directive.ResultPage"] = $apiParams["directive.ResultPage"] - 1;
			$apiParams["responseDirective.IncludeMetadata"] = "true";
			$apiParams["responseDirective.IncludeLinkMetadata"] = "true";
		} else if($action == "details"){
			// check allowed searched before registration
			$allow_details_view = 1;
			if (!empty($account_options->AllowedDetailViewsBeforeRegistration) && isset($_COOKIE['dsidx-visitor-details-views'])) {
				if ((int) $account_options->AllowedDetailViewsBeforeRegistration <= (int) $_COOKIE['dsidx-visitor-details-views']) {
					$allow_details_view = 0;
				}
			}

			$useJuiceBox = 'true';

			if (isset($options['ImageDisplay']))
				$useJuiceBox = $options['ImageDisplay'] == "slideshow" ? 'true' : 'false';

			// echo $useJuiceBox;
			$apiParams["requester.AllowVisitorDetailView"] = $allow_details_view;
			$apiParams["responseDirective.UseJuiceBoxSlideShow"] = $useJuiceBox;
			
			// if we have an auth cookie then record a property visit
			if(@$_COOKIE['dsidx-visitor-auth']) {
				$visitParams = array( "mlsNumber" => $apiParams["query.MlsNumber"] );
				$apiVisitResponse = dsSearchAgent_ApiRequest::FetchData("RecordVisit", $visitParams, false, 0);
			}
			
			$screen_name = get_option('zillow_screen_name');
			if (!empty($screen_name))
				$apiParams["responseDirective.ZillowScreenName"] = $screen_name;
		}
		$apiParams["responseDirective.IncludeDisclaimer"] = "true";
		$apiParams["responseDirective.IncludeDsDisclaimer"] = (defined('ZPRESS_API') && ZPRESS_API != '') ? "false" : "true";
		$apiParams["responseDirective.RemoveDsDisclaimerLinks"] = (isset($options['RemoveDsDisclaimerLinks']) && $options['RemoveDsDisclaimerLinks'] == 'Y') ? "true" : "false";

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData($wp_query->query["idx-action"], $apiParams, false);
		
		if(!isset($apiHttpResponse["body"]) || !isset($apiHttpResponse["response"])){
			wp_die("We're sorry, but we ran into a temporary problem while trying to load the real estate data. Please check back soon.", "Real estate data load error");
		}

		$apiData = $apiHttpResponse["body"];
		$apiData = str_replace('{$contentDomId}', $post_id, $apiData);

		if ($action == 'details' && defined('ZPRESS_API') && ZPRESS_API != '') {
			if (!isset($personal_info)) $personal_info = stripslashes_deep(get_option('personal_info'));
			if (!empty($personal_info['google_authorship']) && !empty($personal_info['googleplus'])) {
				$apiData .= '<p>By <a href="'.esc_url($personal_info['googleplus']).'?rel=author" target="_blank">'.$personal_info['first_name'].' '.$personal_info['last_name'].'</a></p>';
			}
		}

		if (in_array($_SERVER["REMOTE_ADDR"], self::$DebugAllowedFrom)) {
			if (isset($get["debug-api-response"])) {
				print_r($apiHttpResponse);
				exit();
			}
		}

		if ($apiHttpResponse["response"]["code"] == "404") {
			$wp_query->set('is_404', true);
			add_action('get_header', array("dsSearchAgent_Client", "Return404"));
		} else if ($apiHttpResponse["response"]["code"] == "302") {
			$redirect = dsSearchAgent_Client::GetBasePath() . self::ExtractValueFromApiData($apiData, "redirect");
			header("Location: $redirect", true, 302);
			exit();
		} else if (empty($apiHttpResponse["body"]) || !empty($apiHttpResponse["errors"]) || substr($apiHttpResponse["response"]["code"], 0, 1) == "5") {
			wp_die("We're sorry, but we ran into a temporary problem while trying to load the real estate data. Please check back soon.", "Real estate data load error");
		}
		/*if ($options['ResultsTitle'] != '')
			$title = $options['ResultsTitle'];
		else */
		$seo_title = self::ExtractValueFromApiData($apiData, "seo_title");
		$seo_description = self::ExtractValueFromApiData($apiData, "seo_description");
		$seo_keywords = self::ExtractValueFromApiData($apiData, "seo_keywords");
		$title = self::ExtractValueFromApiData($apiData, "title");
		$dateaddedgmt = self::ExtractValueFromApiData($apiData, "dateaddedgmt");
		$description = self::ExtractValueFromApiData($apiData, "description");
		$firstimage = self::ExtractValueFromApiData($apiData, "firstimage");
		self::$meta_tag_data = array('firstimage' => $firstimage, 'title' => $title, 'description' => $description);
		self::$CanonicalUri = self::ExtractValueFromApiData($apiData, "canonical");
		self::$TriggeredAlternateUrlStructure = self::ExtractValueFromApiData($apiData, "alternate-urls");
		if (!isset($wp_query->query['ds-idx-listings-page'])){
			if ($apiHttpResponse["response"]["code"] != "404"){
				self::EnsureBaseUri();
			}
		}
		
		set_query_var("name", "dsidxpress-{$action}"); // at least a few themes require _something_ to be set here to display a good <title> tag
		set_query_var("pagename", "dsidxpress-{$action}"); // setting pagename in case someone wants to do a custom theme file for this "page"

		$post = (object)array(
			"ID"				=> $post_id,
			"comment_count"		=> 0,
			"comment_status"	=> "closed",
			"ping_status"		=> "closed",
			"post_author"		=> 1,
			"post_content"		=> $apiData,
			"post_date"			=> $dateaddedgmt ? $dateaddedgmt : date("c"),
			"post_date_gmt"		=> $dateaddedgmt ? $dateaddedgmt : gmdate("c"),
			"post_excerpt"		=> $description,
			"post_name"			=> "dsidxpress-data",
			"post_parent"		=> 0,
			"post_status"		=> "publish",
			"post_title"		=> $title,
			"post_type"			=> "page"
		);
		if(!$idx_page_id){
			wp_cache_set( $post_id, $post, 'posts');
		}
		$posts = array( $post );

		// track the detail & result views, do this at the end in case something errors or w/e
		$views = intval(@$_COOKIE["dsidx-visitor-$action-views"]);
		setcookie("dsidx-visitor-$action-views", $views + 1, time()+60*60*24*30, '/');

		$dsidxpress_seo = new dsidxpress_seo($seo_title, $seo_description, $seo_keywords);
		if (!$idx_page_id && isset($seo_title)) {
			add_filter('wp_title', array($dsidxpress_seo, 'dsidxpress_title_filter'));
		}
		if (isset($seo_keywords) || isset($seo_description)) {	
			add_action('wp_head', array($dsidxpress_seo, 'dsidxpress_head_action'));
		}
		
 		add_action('wp_head', array( "dsSearchAgent_Client", 'SocialMetaTags'));
		
 		 if ($action == "search")
 		 	dsidx_footer::ensure_disclaimer_exists("search");
		return $posts;
	}
	static function Return404($header) { 
		return status_header(404);
	}
	static function ExtractValueFromApiData(&$apiData, $key) {
		preg_match('/^\<!\-\-\s*' . $key . ':\s*"(?P<value>[^"]+)"\s*\-\-\>/ms', $apiData, $matches);
		if (isset($matches[0])) {
			$apiData = str_replace($matches[0], "", $apiData);
			return $matches["value"];
		}
		return "";
	}
	static function EnsureBaseUri() {
		$basePath = dsSearchAgent_Client::GetBasePath();
		$queryPosition = strrpos(self::$CanonicalUri, "?");
		if ($queryPosition !== false)
			$hardPermalink = substr(self::$CanonicalUri, 0, $queryPosition);
		else
			$hardPermalink = self::$CanonicalUri;

		$requestedPath = $_SERVER["REQUEST_URI"];
		$queryPosition = strrpos($requestedPath, "?");
		if ($queryPosition !== false)
			$requestedPath = substr($requestedPath, 0, $queryPosition);
		else
			$requestedPath = $requestedPath;

		$expectedPath = $basePath . urldecode($hardPermalink);

		if ($requestedPath != $expectedPath) {
			$redirect = $basePath . self::$CanonicalUri;
			$sortColumnKey = "idx-d-SortOrders<0>-Column";
			$sortDirectionKey = "idx-d-SortOrders<0>-Direction";
			$sortColumn = (isset($_GET[$sortColumnKey])) ? $_GET[$sortColumnKey] : null;
			$sortDirection = (isset($_GET[$sortDirectionKey])) ? $_GET[$sortDirectionKey] : null;

			if ($sortColumn !== null && $sortDirection !== null) {
				if (substr($redirect, strlen($redirect) - 1, 1) == "/")
					$redirect .= "?";
				else
					$redirect .= "&";
				$redirect .= urlencode($sortColumnKey) . "=" . urlencode($sortColumn) . "&" . urlencode($sortDirectionKey) . "=" . urlencode($sortDirection);
			}
			header("Location: $redirect", true, 301);
			exit();
		}
	}
	static function GetBasePath(){
		$urlSlug = empty(self::$TriggeredAlternateUrlStructure) ? "idx/" : "";
		$blogUrlWithoutProtocol = str_replace("http://", "", get_home_url());
		$blogUrlDirIndex = strpos($blogUrlWithoutProtocol, "/");
		$blogUrlDir = "";
		if ($blogUrlDirIndex) // don't need to check for !== false here since WP prevents trailing /'s
			$blogUrlDir = substr($blogUrlWithoutProtocol, strpos($blogUrlWithoutProtocol, "/"));

		return $blogUrlDir . "/" . $urlSlug;
	}
	static function ClearQuery($query) {
		global $wp_query;

		if(!is_array($wp_query->query) || !isset($wp_query->query["idx-action"]))
			return $query;

		return "";
	}
	static function CancelAllRedirects($location) {
		return false;
	}
	static function NoIndex(){
		echo "<meta name=\"robots\" content=\"noindex\">\n";
	}
	static function GetPermalink($incomingLink = null) {
		$blogUrl = get_home_url();
		$urlSlug = dsSearchAgent_Rewrite::GetUrlSlug();
		$canonicalUri = self::$CanonicalUri;

		if (isset($canonicalUri) && (!$incomingLink || preg_match("/dsidxpress-data/", $incomingLink)))
			return "{$blogUrl}/{$urlSlug}{$canonicalUri}";
		else
			return $incomingLink;
	}
	static function Header() {
		global $thesis;

		// let thesis handle the canonical
		if (self::$CanonicalUri && !$thesis)
			echo "<link rel=\"canonical\" href=\"" . self::GetPermalink() . "\" />\n";
	}
	public static function CleanCommentsBlock($path){
        global $wp_query;
        if (is_array($wp_query->query) && isset($wp_query->query["idx-action"])) {
            return dirname(__FILE__) . '/comments-template.php';
        }
        return $path;
    }
	static function SocialMetaTags() {
		$firstimage = self::$meta_tag_data['firstimage'];
		$title = self::$meta_tag_data['title'];
		$description = self::$meta_tag_data['description'];
		if (!empty($firstimage)) {
			echo "<meta property='og:image' content='" . $firstimage . "0-medium.jpg' />";
		}
		$blogUrl = get_home_url();
		//Twitter Card
		echo <<<HTML
			<meta name="og:description" content="{$description}">
			<meta name="twitter:card" content="summary">
		    <meta name="twitter:url" content="{$blogUrl}">
		    <meta name="twitter:title" content="{$title}">
		    <meta name="twitter:description" content="{$description}">
		    <meta name="twitter:image" content="{$firstimage}0-medium.jpg">
HTML;
	}
	static function RemoveEditLink($editLink, $postId, $context) {
		return;
	}
	static function RemoveGoogleDataClasses($classes) {
		global $wp_query;
		if (isset($wp_query->query['idx-action'])) {
			$classes = array_diff($classes, array('hentry'));
		}
		return $classes;
	}
}
?>