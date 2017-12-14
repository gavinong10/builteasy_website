<?php
class dsSearchAgent_Shortcodes {
	static function Listing($atts, $content = null, $code = "") {
		$options = get_option(DSIDXPRESS_OPTION_NAME);
			if (!$options["Activated"])
				return "";

		$atts = shortcode_atts(array(
			"mlsnumber"			=> "",
			"statuses"			=> "",
			"showall"			=> "false",
			"showpricehistory"	=> "false",
			"showschools"		=> "false",
			"showextradetails"	=> "false",
			"showfeatures"		=> "false",
			"showlocation"		=> "false"
		), $atts);
		$apiRequestParams = array();
		$apiRequestParams["responseDirective.ViewNameSuffix"] = "shortcode";
		$apiRequestParams["query.MlsNumber"] = str_replace(" ", "", $atts["mlsnumber"]);
		if(self::TranslateStatuses($atts["statuses"])){
			$apiRequestParams["query.ListingStatuses"] = self::TranslateStatuses($atts["statuses"]);
		} //else the api will use active and conditional by default
		$apiRequestParams["responseDirective.ShowSchools"] = $atts["showschools"];
		$apiRequestParams["responseDirective.ShowPriceHistory"] = $atts["showpricehistory"];
		$apiRequestParams["responseDirective.ShowAdditionalDetails"] = $atts["showextradetails"];
		$apiRequestParams["responseDirective.ShowFeatures"] = $atts["showfeatures"];
		$apiRequestParams["responseDirective.ShowLocation"] = $atts["showlocation"];

		if ($atts["showall"] == "true") {
			$apiRequestParams["responseDirective.ShowSchools"] = "true";
			$apiRequestParams["responseDirective.ShowPriceHistory"] = "true";
			$apiRequestParams["responseDirective.ShowAdditionalDetails"] = "true";
			$apiRequestParams["responseDirective.ShowFeatures"] = "true";
			$apiRequestParams["responseDirective.ShowLocation"] = "true";
		}

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Details", $apiRequestParams, false);
		dsidx_footer::ensure_disclaimer_exists();

		if ($apiHttpResponse["response"]["code"] == "403") {
			return '<p class="dsidx-error">'.DSIDXPRESS_INACTIVE_ACCOUNT_MESSAGE.'</p>';
		}
		if ($apiHttpResponse["response"]["code"] == "404") {
			return '<p class="dsidx-error">'.sprintf(DSIDXPRESS_INVALID_MLSID_MESSAGE, $atts[mlsnumber]).'</p>';
		}
		else if (empty($apiHttpResponse["errors"]) && $apiHttpResponse["response"]["code"] == "200") {
			return $apiHttpResponse["body"];
		} else {
			return '<p class="dsidx-error">'.DSIDXPRESS_IDX_ERROR_MESSAGE.'</p>';
		}
	}
	static function Listings($atts, $content = null, $code = "") {
		$options = get_option(DSIDXPRESS_OPTION_NAME);
			if (!$options["Activated"])
				return "";

		$atts = shortcode_atts(array(
			"city"			=> "",
			"community"		=> "",
			"county"		=> "",
			"tract"			=> "",
			"zip"			=> "",
			"minprice"		=> "",
			"maxprice"		=> "",
			"minbeds"		=> "",
			"maxbeds"		=> "",
			"minbaths"		=> "",
			"maxbaths"		=> "",
			"mindom"		=> "",
			"maxdom"		=> "",
			"minyear"		=> "",
			"maxyear"		=> "",
			"minimpsqft"	=> "",
			"maximpsqft"	=> "",
			"minlotsqft"	=> "",
			"maxlotsqft"	=> "",
			"statuses"		=> "",
			"propertytypes"	=> "",
			"linkid"		=> "",
			"count"			=> "5",
			"orderby"		=> "DateAdded",
			"orderdir"		=> "DESC",
			"showlargerphotos"	=> "false"
		), $atts);

		$apiRequestParams = array();
		$apiRequestParams["responseDirective.ViewNameSuffix"] = "shortcode";
		$apiRequestParams["responseDirective.IncludeMetadata"] = "true";
		$apiRequestParams["responseDirective.IncludeLinkMetadata"] = "true";
		$apiRequestParams["responseDirective.ShowLargerPhotos"] = $atts["showlargerphotos"];
		$apiRequestParams["query.Cities"] = htmlspecialchars_decode($atts["city"]);
		$apiRequestParams["query.Communities"] = htmlspecialchars_decode($atts["community"]);
		$apiRequestParams["query.Counties"] = htmlspecialchars_decode($atts["county"]);
		$apiRequestParams["query.TractIdentifiers"] = htmlspecialchars_decode($atts["tract"]);
		$apiRequestParams["query.ZipCodes"] = $atts["zip"];
		$apiRequestParams["query.PriceMin"] = $atts["minprice"];
		$apiRequestParams["query.PriceMax"] = $atts["maxprice"];
		$apiRequestParams["query.BedsMin"] = $atts["minbeds"];
		$apiRequestParams["query.BedsMax"] = $atts["maxbeds"];
		$apiRequestParams["query.BathsMin"] = $atts["minbaths"];
		$apiRequestParams["query.BathsMax"] = $atts["maxbaths"];
		$apiRequestParams["query.DaysOnMarketMin"] = $atts["mindom"];
		$apiRequestParams["query.DaysOnMarketMax"] = $atts["maxdom"];
		$apiRequestParams["query.YearBuiltMin"] = $atts["minyear"];
		$apiRequestParams["query.YearBuiltMax"] = $atts["maxyear"];
		$apiRequestParams["query.ImprovedSqFtMin"] = $atts["minimpsqft"];
		$apiRequestParams["query.ImprovedSqFtMax"] = $atts["maximpsqft"];
		$apiRequestParams["query.LotSqFtMin"] = $atts["minlotsqft"];
		$apiRequestParams["query.LotSqFtMax"] = $atts["maxlotsqft"];
		if(self::TranslateStatuses($atts["statuses"]))
			$apiRequestParams["query.ListingStatuses"] = self::TranslateStatuses($atts["statuses"]);
		else
			$apiRequestParams["query.ListingStatuses"] = 3;
		if ($atts["propertytypes"]) {
			$propertyTypes = explode(",", str_replace(" ", "", $atts["propertytypes"]));
			$propertyTypes = array_combine(range(0, count($propertyTypes) - 1), $propertyTypes);
			foreach ($propertyTypes as $key => $value)
				$apiRequestParams["query.PropertyTypes[{$key}]"] = $value;
		}
		if ($atts["linkid"]) {
			$apiRequestParams["query.LinkID"] = $atts["linkid"];
			$apiRequestParams["query.ForceUsePropertySearchConstraints"] = "true";
		}
		$apiRequestParams["directive.ResultsPerPage"] = $atts["count"];
		$apiRequestParams["directive.SortOrders[0].Column"] = $atts["orderby"];
		$apiRequestParams["directive.SortOrders[0].Direction"] = $atts["orderdir"];

		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("Results", $apiRequestParams);
		dsidx_footer::ensure_disclaimer_exists();

		if (empty($apiHttpResponse["errors"]) && $apiHttpResponse["response"]["code"] == "200") {
			return $apiHttpResponse["body"];
		} else {
			if ($apiHttpResponse["response"]["code"] == "403") {
				return '<p class="dsidx-error">'.DSIDXPRESS_INACTIVE_ACCOUNT_MESSAGE.'</p>';
			}
			return '<p class="dsidx-error">'.DSIDXPRESS_IDX_ERROR_MESSAGE.'</p>';
		}
	}
	
	static function TranslateStatuses($ids) {
		$values = '';
		$ids = explode(',',$ids);
		foreach ($ids as $id) {
			switch($id) {
				case 1: $values .= 'Active,'; break;
				case 2: $values .= 'Conditional,'; break;
				case 3: $values .= 'Pending,'; break;
				case 4: $values .= 'Sold,'; break;
			}
		}
		return substr($values, 0, strlen($values) - 1);
	}

	static function IdxQuickSearch($atts, $content = null, $code = ""){
		$atts = shortcode_atts(array(
			"format"		=> "horizontal"
		), $atts);
		ob_start();
		dsSearchAgent_IdxQuickSearchWidget::shortcodeWidget(array('widgetType'=>$atts['format'], 'class'=>'dsidx-inline-form'));
		$markup = ob_get_clean();
		return '<p>'.$markup.'</p>';
	}
}

add_shortcode("idx-listing", array("dsSearchAgent_ShortCodes", "Listing"));
add_shortcode("idx-listings", array("dsSearchAgent_ShortCodes", "Listings"));
add_shortcode("idx-quick-search", array("dsSearchAgent_ShortCodes", "IdxQuickSearch"));
?>