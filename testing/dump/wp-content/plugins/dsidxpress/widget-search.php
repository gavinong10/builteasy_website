<?php
class dsSearchAgent_SearchWidget extends WP_Widget {
	function dsSearchAgent_SearchWidget() {
		$this->WP_Widget("dsidx-search", "IDX Search", array(
			"classname" => "dsidx-widget-search",
			"description" => "A real estate search form"
		));
	}
	function widget($args, $instance) {
		extract($args);
		extract($instance);
		$title = apply_filters("widget_title", $title);
		$options = get_option(DSIDXPRESS_OPTION_NAME);

		if (!$options["Activated"])
			return;

		$pluginUrl = plugins_url() . '/dsidxpress/';

		wp_enqueue_script('dsidxpress_widget_search_view', $pluginUrl . 'js/widget-client.js', array('jquery'), DSIDXPRESS_PLUGIN_VERSION, true);

		$formAction = get_home_url() . "/idx/";
		$capabilities = dsSearchAgent_ApiRequest::FetchData('MlsCapabilities');
		$capabilities = json_decode($capabilities['body'], true);
		
		$defaultSearchPanels = dsSearchAgent_ApiRequest::FetchData("AccountSearchPanelsDefault", array(), false, 60 * 60 * 24);
		$defaultSearchPanels = $defaultSearchPanels["response"]["code"] == "200" ? json_decode($defaultSearchPanels["body"]) : null;
		$propertyTypes = dsSearchAgent_ApiRequest::FetchData("AccountSearchSetupFilteredPropertyTypes", array(), false, 60 * 60 * 24);
		$propertyTypes = $propertyTypes["response"]["code"] == "200" ? json_decode($propertyTypes["body"]) : null;

		$account_options = dsSearchAgent_ApiRequest::FetchData("AccountOptions", array(), false);
		$account_options = $account_options["response"]["code"] == "200" ? json_decode($account_options["body"]) : null;

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;

		echo <<<HTML
			<div class="dsidx-search-widget dsidx-widget">
			<form action="{$formAction}" method="get" onsubmit="return dsidx_w.searchWidget.validate();" >
				<select name="idx-q-PropertyTypes" class="dsidx-search-widget-propertyTypes">
					<option value="">- All property types -</option>
HTML;

		if (is_array($propertyTypes)) {
			foreach ($propertyTypes as $propertyType) {
				$name = htmlentities($propertyType->DisplayName);
				echo "<option value=\"{$propertyType->SearchSetupPropertyTypeID}\">{$name}</option>";
			}
		}

		echo <<<HTML
				</select>
				<label id="idx-search-invalid-msg" style="color:red"></label>
HTML;
		if ($searchOptions['show_cities'] == 'yes' && !empty($searchOptions['cities'])) {
			echo <<<HTML
				<select id="idx-q-Cities" name="idx-q-Cities" class="idx-q-Location-Filter">
					<option value="">- City -</option>
HTML;
			foreach ($searchOptions["cities"] as $city) {
				// there's an extra trim here in case the data was corrupted before the trim was added in the update code below
				$city = htmlentities(trim($city));
				echo "<option value=\"{$city}\">{$city}</option>";
			}
			echo '</select>';
		}
		if($searchOptions['show_communities'] == 'yes' && !empty($searchOptions['communities'])) {
			echo <<<HTML
				<select id="idx-q-Communities" name="idx-q-Communities" class="idx-q-Location-Filter">
					<option value="">- Community -</option>
HTML;
			foreach ($searchOptions['communities'] as $community) {
				// there's an extra trim here in case the data was corrupted before the trim was added in the update code below
				$community = htmlentities(trim($community));
				echo "<option value=\"{$community}\">{$community}</option>";
			}
			echo '</select>';
		}
		if($searchOptions['show_tracts'] == 'yes' &&  !empty($searchOptions['tracts'])) {
			echo <<<HTML
				<select id="idx-q-TractIdentifiers" name="idx-q-TractIdentifiers" class="idx-q-Location-Filter">
					<option value="">- Tract -</option>
HTML;
			foreach ($searchOptions["tracts"] as $tract) {
				// there's an extra trim here in case the data was corrupted before the trim was added in the update code below
				$tract = htmlentities(trim($tract));
				echo "<option value=\"{$tract}\">{$tract}</option>";
			}
			echo '</select>';
		}
		if($searchOptions['show_zips'] == 'yes' && !empty($searchOptions['zips'])) {
			echo <<<HTML
				<select id="idx-q-ZipCodes" name="idx-q-ZipCodes" class="idx-q-Location-Filter">
					<option value="">- Zip -</option>
HTML;
			foreach ($searchOptions["zips"] as $zip) {
				// there's an extra trim here in case the data was corrupted before the trim was added in the update code below
				$zip = htmlentities(trim($zip));
				echo "<option value=\"{$zip}\">{$zip}</option>";
			}
			echo '</select>';
		}
		if($searchOptions["show_mlsnumber"] == "yes") {
			echo <<<HTML
				<label for="idx-q-MlsNumbers">MLS #</label>
				<input id="idx-q-MlsNumbers" name="idx-q-MlsNumbers" type="text" class="dsidx-mlsnumber" />
HTML;
		}
		echo <<<HTML
				<label for="idx-q-PriceMin">Price</label>
				<input id="idx-q-PriceMin" name="idx-q-PriceMin" type="text" class="dsidx-price" placeholder="min price" />
				<input id="idx-q-PriceMax" name="idx-q-PriceMax" type="text" class="dsidx-price" placeholder="max price" />
HTML;
		if(isset($defaultSearchPanels)){
			foreach ($defaultSearchPanels as $key => $value) {
				if ($value->DomIdentifier == "search-input-home-size" && isset($capabilities['MinImprovedSqFt']) && $capabilities['MinImprovedSqFt'] > 0) {
					echo <<<HTML
						<label for="idx-q-ImprovedSqFtMin">Size</label>
						<input id="idx-q-ImprovedSqFtMin" name="idx-q-ImprovedSqFtMin" type="text" class="dsidx-improvedsqft" placeholder="min sqft" />
HTML;
					break;
				}
			}
		}
		echo <<<HTML
				<label for="idx-q-BedsMin">Beds</label>
				<input id="idx-q-BedsMin" name="idx-q-BedsMin" type="text" class="dsidx-beds" placeholder="min bedrooms" />
				<label for="idx-q-BathsMin">Baths</label>
				<input id="idx-q-BathsMin" name="idx-q-BathsMin" type="text" class="dsidx-baths" placeholder="min bathrooms" />
				<div class="dsidx-search-button search-form">
					<input type="submit" class="submit" value="Search for properties" />
HTML;
		if($options["HasSearchAgentPro"] == "yes" && $searchOptions["show_advanced"] == "yes"){
			echo <<<HTML
					try our&nbsp;<a href="{$formAction}advanced/"><img src="{$pluginUrl}assets/adv_search-16.png" /> Advanced Search</a>
HTML;
		}
		if($account_options->EulaLink){
			$eula_url = $account_options->EulaLink;
			echo <<<HTML
					<p>By searching, you agree to the <a href="{$eula_url}" target="_blank">EULA</a></p>
HTML;
		}
		echo <<<HTML
				</div>
			</form>
			</div>
HTML;
		
		echo $after_widget;
		dsidx_footer::ensure_disclaimer_exists("search");
	}
	function update($new_instance, $old_instance) {
		$new_instance["title"] = strip_tags($new_instance["title"]);
		$new_instance["searchOptions"]["cities"] = explode("\n", $new_instance["searchOptions"]["cities"]);
		$new_instance["searchOptions"]["zips"] = explode("\n", $new_instance["searchOptions"]["zips"]);
		$new_instance["searchOptions"]["tracts"] = explode("\n", $new_instance["searchOptions"]["tracts"]);
		$new_instance["searchOptions"]["communities"] = explode("\n", $new_instance["searchOptions"]["communities"]);

		if ($new_instance["searchOptions"]["sortZips"])
			sort($new_instance["searchOptions"]["zips"]);

		if ($new_instance["searchOptions"]["sortCities"])
			sort($new_instance["searchOptions"]["cities"]);

		if ($new_instance["searchOptions"]["sortTracts"])
			sort($new_instance["searchOptions"]["tracts"]);

		if ($new_instance["searchOptions"]["sortCommunities"])
			sort($new_instance["searchOptions"]["communities"]);

		// we don't need to store this option
		unset($new_instance["searchOptions"]["sortCities"]);
		unset($new_instance["searchOptions"]["sortTracts"]);
		unset($new_instance["searchOptions"]["sortCommunities"]);
		unset($new_instance["searchOptions"]["sortZips"]);

		foreach ($new_instance["searchOptions"]["cities"] as &$area)
			$area = trim($area);
		foreach ($new_instance["searchOptions"]["tracts"] as &$area)
			$area = trim($area);
		foreach ($new_instance["searchOptions"]["communities"] as &$area)
			$area = trim($area);
		foreach ($new_instance["searchOptions"]["zips"] as &$area)
			$area = trim($area);

		/* we're doing this conversion from on/null to yes/no so that we can detect if the show_cities has never been
		 * set, because if it's never been set we want it to show */
		if($new_instance["searchOptions"]["show_cities"] == "on") $new_instance["searchOptions"]["show_cities"] = "yes";
		else $new_instance["searchOptions"]["show_cities"] = "no";

		if($new_instance["searchOptions"]["show_communities"] == "on") $new_instance["searchOptions"]["show_communities"] = "yes";
		else $new_instance["searchOptions"]["show_communities"] = "no";

		if($new_instance["searchOptions"]["show_tracts"] == "on") $new_instance["searchOptions"]["show_tracts"] = "yes";
		else $new_instance["searchOptions"]["show_tracts"] = "no";

		if($new_instance["searchOptions"]["show_zips"] == "on") $new_instance["searchOptions"]["show_zips"] = "yes";
		else $new_instance["searchOptions"]["show_zips"] = "no";

		if($new_instance["searchOptions"]["show_mlsnumber"] == "on") $new_instance["searchOptions"]["show_mlsnumber"] = "yes";
		else $new_instance["searchOptions"]["show_mlsnumber"] = "no";

		if($new_instance["searchOptions"]["show_advanced"] == "on") $new_instance["searchOptions"]["show_advanced"] = "yes";
		else $new_instance["searchOptions"]["show_advanced"] = "no";

		return $new_instance;
	}
	function form($instance) {
		wp_enqueue_script('dsidxpress_widget_search', DSIDXPRESS_PLUGIN_URL . 'js/widget-search.js', array('jquery'), DSIDXPRESS_PLUGIN_VERSION, true);
		
		$pluginUrl = DSIDXPRESS_PLUGIN_URL;

		$options = get_option(DSIDXPRESS_OPTION_NAME);

		$instance = wp_parse_args($instance, array(
			"title" => "Real Estate Search",
			"searchOptions" => array(
				"cities" => array(),
				"communities" => array(),
				"tracts" => array(),
				"zips" => array(),
				"show_cities" => 'yes',
				"show_communities" => 'no',
				"show_tracts" => 'no',
				"show_zips" => 'no',
				"show_mlsnumber" => 'no',
				"show_advanced" => 'no'
			)
		));

		$title = htmlspecialchars($instance["title"]);
		$cities = htmlspecialchars(implode("\n", (array)$instance["searchOptions"]["cities"]));
		$communities = htmlspecialchars(implode("\n", (array)$instance["searchOptions"]["communities"]));
		$tracts = htmlspecialchars(implode("\n", (array)$instance["searchOptions"]["tracts"]));
		$zips = htmlspecialchars(implode("\n", (array)$instance["searchOptions"]["zips"]));

		$titleFieldId = $this->get_field_id("title");
		$titleFieldName = $this->get_field_name("title");
		$searchOptionsFieldId = $this->get_field_id("searchOptions");
		$searchOptionsFieldName = $this->get_field_name("searchOptions");

		$show_cities = $instance["searchOptions"]["show_cities"] == "yes" || !isset($instance["searchOptions"]["show_cities"]) ? "checked=\"checked\" " : "";
		$show_communities = $instance["searchOptions"]["show_communities"] == "yes" ? "checked=\"checked\" " : "";
		$show_tracts = $instance["searchOptions"]["show_tracts"] == "yes" ? "checked=\"checked\" " : "";
		$show_zips = $instance["searchOptions"]["show_zips"] == "yes" ? "checked=\"checked\" " : "";
		$show_mlsnumber = $instance["searchOptions"]["show_mlsnumber"] == "yes" ? "checked=\"checked\" " : "";
		$show_advanced = $instance["searchOptions"]["show_advanced"] == "yes" ? "checked=\"checked\" " : "";

		echo <<<HTML
			<p>
				<label for="{$titleFieldId}">Widget title</label>
				<input id="{$titleFieldId}" name="{$titleFieldName}" value="{$title}" class="widefat" type="text" />
			</p>

			<p>
				<h4>Fields to Display</h4>
				<div id="{$searchOptionsFieldId}-show_checkboxes" class="search-widget-searchOptions">
					<input type="checkbox" id="{$searchOptionsFieldId}-show_cities" name="{$searchOptionsFieldName}[show_cities]" {$show_cities} onclick="dsWidgetSearch.ShowBlock(this);"/>
					<label for="{$searchOptionsFieldId}-show_cities">Cities</label><br />
					<input type="checkbox" id="{$searchOptionsFieldId}-show_communities" name="{$searchOptionsFieldName}[show_communities]" {$show_communities} onclick="dsWidgetSearch.ShowBlock(this);"/>
					<label for="{$searchOptionsFieldId}-show_communities">Communities</label><br />
					<input type="checkbox" id="{$searchOptionsFieldId}-show_tracts" name="{$searchOptionsFieldName}[show_tracts]" {$show_tracts} onclick="dsWidgetSearch.ShowBlock(this);"/>
					<label for="{$searchOptionsFieldId}-show_tracts">Tracts</label><br />
					<input type="checkbox" id="{$searchOptionsFieldId}-show_zips" name="{$searchOptionsFieldName}[show_zips]" {$show_zips} onclick="dsWidgetSearch.ShowBlock(this);"/>
					<label for="{$searchOptionsFieldId}-show_zips">Zips</label><br />
					<input type="checkbox" id="{$searchOptionsFieldId}-show_mlsnumber" name="{$searchOptionsFieldName}[show_mlsnumber]" {$show_mlsnumber} onclick="dsWidgetSearch.ShowBlock(this);"/>
					<label for="{$searchOptionsFieldId}-show_mlsnumber">MLS #'s</label><br />
HTML;
		if($options["HasSearchAgentPro"] == "yes") {
			echo <<<HTML
					<input id="{$searchOptionsFieldId}-show-advanced" name="{$searchOptionsFieldName}[show_advanced]" class="checkbox" type="checkbox" {$show_advanced} onclick="dsWidgetSearch.ShowBlock(this);"/>
					<label for="{$searchOptionsFieldId}-show-advanced">Show Advanced Option</label>
HTML;
		}
			echo <<<HTML
				</div>
			</p>

			<div id="{$searchOptionsFieldId}-cities_block">
				<h4>Cities (one per line)</h4>
				<p>
					<textarea id="{$searchOptionsFieldId}[cities]" name="{$searchOptionsFieldName}[cities]" class="widefat" rows="10">{$cities}</textarea>
				</p>
				<p>
					<label for="{$searchOptionsFieldId}[sortCities]">Sort Cities</label>
					<input id="{$searchOptionsFieldId}[sortCities]" name="{$searchOptionsFieldName}[sortCities]" class="checkbox" type="checkbox" />
				</p>
				<p>
					<span class="description">See all City Names <a href="javascript:void(0);" onclick="dsWidgetSearch.LaunchLookupList('{$pluginUrl}locations.php?type=city')">here</a></span>
				</p>
				<hr noshade="noshade" />
			</div>
			<div id="{$searchOptionsFieldId}-communities_block">
				<h3>Communities (one per line)</h3>
				<p>
					<textarea id="{$searchOptionsFieldId}[communities]" name="{$searchOptionsFieldName}[communities]" class="widefat" rows="10">{$communities}</textarea>
				</p>
				<p>
					<label for="{$searchOptionsFieldId}[sortCommunities]">Sort Communities</label>
					<input id="{$searchOptionsFieldId}[sortCommunities]" name="{$searchOptionsFieldName}[sortCommunities]" class="checkbox" type="checkbox" />
				</p>
				<p>
					<span class="description">See all Community Names <a href="javascript:void(0);" onclick="dsWidgetSearch.LaunchLookupList('{$pluginUrl}locations.php?type=community')">here</a></span>
				</p>
				<hr noshade="noshade" />
			</div>

			<div id="{$searchOptionsFieldId}-tracts_block">
				<h3>Tracts (one per line)</h3>
				<p>
					<textarea id="{$searchOptionsFieldId}[tracts]" name="{$searchOptionsFieldName}[tracts]" class="widefat" rows="10">{$tracts}</textarea>
				</p>
				<p>
					<label for="{$searchOptionsFieldId}[sortTracts]">Sort Tracts</label>
					<input id="{$searchOptionsFieldId}[sortTracts]" name="{$searchOptionsFieldName}[sortTracts]" class="checkbox" type="checkbox" />
				</p>
				<p>
					<span class="description">See all Tract Names <a href="javascript:void(0);" onclick="dsWidgetSearch.LaunchLookupList('{$pluginUrl}locations.php?type=tract')">here</a></span>
				</p>
				<hr noshade="noshade" />
			</div>

			<div id="{$searchOptionsFieldId}-zips_block">
				<h3>Zips (one per line)</h3>
				<p>
					<textarea id="{$searchOptionsFieldId}[zips]" name="{$searchOptionsFieldName}[zips]" class="widefat" rows="10">{$zips}</textarea>
				</p>
				<p>
					<label for="{$searchOptionsFieldId}[sortZips]">Sort Zips</label>
					<input id="{$searchOptionsFieldId}[sortZips]" name="{$searchOptionsFieldName}[sortZips]" class="checkbox" type="checkbox" />
				</p>
				<p>
					<span class="description">See all Zips <a href="javascript:void(0);" onclick="dsWidgetSearch.LaunchLookupList('{$pluginUrl}locations.php?type=zip')">here</a></span>
				</p>
			</div>
			<script> jQuery(document).ready(function() { if(typeof dsWidgetSearch != "undefined") { dsWidgetSearch.InitFields(); } }); </script>
HTML;
	}
}
?>