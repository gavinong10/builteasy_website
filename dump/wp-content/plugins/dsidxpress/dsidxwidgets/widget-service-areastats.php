<?php
class dsIDXWidgets_AreaStats extends WP_Widget {
    var $widgetsCdn;

    function dsIDXWidgets_AreaStats() {
        $this->WP_Widget("dsidx-areastats", "Area Statistics", array(
            "classname" => "dsidx-widget-areastats",
            "description" => "Show an interactive chart with important listing data"
            ));

        $this->widgetsCdn = dsWidgets_Service_Base::$widgets_cdn;
    }
    function widget($args, $instance) {
        extract($args);
        extract($instance);

        $error_message = dsWidgets_Service_Base::getWidgetErrorMsg($before_widget . $before_title . $title . $after_title, $after_widget);
        if($error_message){
            echo $error_message;
            return;
        }

        $options = get_option(DSIDXWIDGETS_OPTION_NAME);
        $randString = dsWidgets_Service_Base::get_random_string('abcdefghijklmnopqrstuvwxyz1234567890', 5);
        $title = htmlspecialchars($instance["title"]);
        $tract = htmlspecialchars($instance["tract"]);
        $community = htmlspecialchars($instance["community"]);
        $state = htmlspecialchars($instance["state"]);
        $city = htmlspecialchars($instance["city"]);
        $zip = htmlspecialchars($instance["zip"]);
        $propType = htmlspecialchars($instance["propType"]);
        $periodType = htmlspecialchars($instance["periodType"]);
        $statusType = htmlspecialchars($instance["statusType"]);
        $imagesStub = dsWidgets_Service_Base::$widgets_images_stub;
        $apiStub = dsWidgets_Service_Base::$widgets_api_stub;
        $curURL = get_home_url();

		$idxpress_options = get_option(DSIDXPRESS_OPTION_NAME);
		if(!empty($idxpress_options["AccountID"])){
			$aid = $idxpress_options["AccountID"];
		}
		else{
			$aid = $options["AccountID"];
		}
		if(!empty($idxpress_options["SearchSetupID"])){
			$ssid = $idxpress_options["SearchSetupID"];
		}
		else{
			$ssid = $options["SearchSetupID"];
		}

        echo $before_widget;
		
        if(defined('ZPRESS_API') && ZPRESS_API != ''){
            $widget_header = call_user_func('\zpress\themes\Options::GetOption', 'theme_widgets_require_header');
            if(!empty($widget_header->meta) && $widget_header->meta == 'true') echo $before_title . 'Area Stats' . $after_title;
        }
	
        echo <<<HTML
        <script type="text/javascript" id="divLocal{$randString}_">
			window.areaStatsHasDependency = true;
            LaunchBase{$randString} = function(){
                var historicalChartScript,historicalChartDep1Script,historicalChartDep2Script,historicalChartDep3Script, _ds_midx;
                CreateObject{$randString} = function () { _ds_midx = { currentURL: '{$curURL}', curAPIStub: '{$apiStub}', curImageStub: '{$imagesStub}', targetDomain: window["zpress_widget_domain_token"], accountId: '{$aid}',searchSetupId: '{$ssid}',muteStyles: true,message: '{$title}',tract: '{$tract}',community: '{$community}',city: '{$city}',state: '{$state}',zipCode: '{$zip}',propType: '{$propType}',curDivID: 'divLocal{$randString}_',chartType: 'line',querySchema: 'oXXi0sb6XR5WdK/vSGYAn12rcEoBW2Ngd/Oyx3/RCbZIV8mSvXrYIR4K5vWeaMSblc0c8/SrrXsKuifcmW5MBItoNGdGTYzHxJcxh9ISsYPoAsVhF+pY5eUXDftnio37',period: '{$periodType}',status: '{$statusType}' }; }
                AddJavaScriptToDOM{$randString}=function(c,d,e){ if(1!=d){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.async=true;a.src=c;a.onload=a.onreadystatechange=function(){ if(a.readyState){  if (a.readyState == "loaded" || a.readyState == "complete") {window[e] = 1;}}else{window[e] = 1;}};b.parentNode.insertBefore(a,b)}return 1};
                CreateWidget{$randString} = function () {
                 if ((window.historicalChartFinished == 1) && (window.historicalChartDep1Finished == 1) && (window.historicalChartDep2Finished != 1)) 
                { if (historicalChartDep2Script != 1)
                 { historicalChartDep2Script = AddJavaScriptToDOM{$randString}('{$this->widgetsCdn}/Scripts/Dependencies/g.raphael-min.js', historicalChartDep2Script, 'historicalChartDep2Finished');}}
                if ((window.historicalChartFinished == 1) && (window.historicalChartDep1Finished == 1) && (window.historicalChartDep2Finished == 1)) {
                if (1 != historicalChartDep3Script)
                {historicalChartDep3Script = AddJavaScriptToDOM{$randString}('{$this->widgetsCdn}/Scripts/Dependencies/g.line-min.js', historicalChartDep3Script, 'historicalChartDep3Finished');
                window.historicalChartDep3Finished == 1;}if (window.historicalChartDep3Finished == 1) 
                {window['ds.widget.view.historicalchart'].isProcessing = true;CreateObject{$randString}(); new window['ds.widget.view.historicalchart'](_ds_midx); window['ds.widget.view.historicalchart'].isProcessing = false; window.areaStatsHasDependency = false;}
                else {
                 window.setTimeout('CreateWidget{$randString}(false)', 20);}}
                else {
                window.setTimeout('CreateWidget{$randString}(false)', 20);} 
                }
                if (historicalChartScript != 1) { historicalChartScript = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/PostCompile/HistoricalChart_v1_1.js", historicalChartScript, 'historicalChartFinished') }; 
                if (historicalChartDep1Script != 1) { historicalChartDep1Script = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/Dependencies/raphael-min.js", historicalChartDep1Script, 'historicalChartDep1Finished') };
                CreateWidget{$randString}();
            }
            GetToken{$randString}=function(){if(!window.zpress_widget_domain_token&&1!=window.zpress_widget_domain_token_progress){window.zpress_widget_domain_token_progress=1;var c=-1<navigator.userAgent.indexOf("MSIE 7.0")?!0:!1,d=-1<navigator.userAgent.indexOf("MSIE 8.0")||-1<navigator.userAgent.indexOf("MSIE 9.0")?!0:!1;if(c)rr=document.createElement("script"),rr.async=!0,rr.id="domainScript",rr.type="text/javascript",rr.src="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=Basic&curDomain="+
            window.location.hostname+"&objectName_=error",rr.onload=rr.onreadystatechange=function(){"undefined"!=typeof window.error?alert("We had a problem authenticating this domain"):(window.zpress_widget_domain_token=window.encrypted_domain,LaunchBase{$randString}())},document.getElementsByTagName("head")[0].appendChild(rr);else if(c="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=CORS&objectName_=error",d){var a=new XDomainRequest;a.onload=function(){window.zpress_widget_domain_token=
            eval(a.responseText);LaunchBase{$randString}()};a.onerror=function(){};a.onprogress=function(){};a.open("GET",c,!0);a.send(null)}else{var b;if(b=new XMLHttpRequest)b.onreadystatechange=function(){if(4==b.readyState)if(200==b.status){var a=eval(b.responseText);"undefined"!=typeof a.listingsError?alert(a.listingsError[0].Message):(window.zpress_widget_domain_token=a,LaunchBase{$randString}())}},b.open("GET",c,!0),b.send()}}else window.zpress_widget_domain_token?LaunchBase{$randString}():
            window.setTimeout("GetToken{$randString}()",20)};GetToken{$randString}();
        </script>
HTML;
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        // we need to do this first-line awkwardness so that the title comes through in the sidebar display thing
        $new_instance["areastatsOptions"]["title"] = $new_instance["title"];
        $new_instance["areastatsOptions"]["tract"] = $new_instance["tract"];
        $new_instance["areastatsOptions"]["community"] = $new_instance["community"];
        $new_instance["areastatsOptions"]["state"] = $new_instance["state"];
        $new_instance["areastatsOptions"]["city"] = $new_instance["city"];
        $new_instance["areastatsOptions"]["zip"] = $new_instance["zip"];
        $new_instance["areastatsOptions"]["propType"] = $new_instance["propType"];
        $new_instance["areastatsOptions"]["periodType"] = $new_instance["periodType"];
        $new_instance["areastatsOptions"]["statusType"] = $new_instance["statusType"];
        $new_instance["areastatsOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance = $new_instance["areastatsOptions"];
        return $new_instance;
    }
    function form($instance) {
        wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery'), false, true);
        $personal_info = stripslashes_deep(get_option('personal_info')); 
        $city = empty($personal_info['city']) ? 'Irvine' : $personal_info['city'];
        $state = empty($personal_info['state']) ? 'CA' : $personal_info['state'];
        $instance = wp_parse_args($instance, array(
            "title"				=> "Area Statistics",
            "tract"           => "",
            "community"          => "",
            "state"                 => $state,
            "city"                  => $city,
            "zip"                   => "",
            "propType"                 => "",
            "periodType"        => "1",
            "statusType"        => "1",
            "eDomain" =>   ""
            ));
        $title = htmlspecialchars($instance["title"]);
        $titleFieldId = $this->get_field_id("title");
        $titleFieldName = $this->get_field_name("title");

        $tract = htmlspecialchars($instance["tract"]);
        $tractFieldId = $this->get_field_id("tract");
        $tractFieldName = $this->get_field_name("tract");

        $community = htmlspecialchars($instance["community"]);
        $communityFieldId = $this->get_field_id("community");
        $communityFieldName = $this->get_field_name("community");

        $state = htmlspecialchars($instance["state"]);
        $stateFieldId = $this->get_field_id("state");
        $stateFieldName = $this->get_field_name("state");

        $city = htmlspecialchars($instance["city"]);
        $cityFieldId = $this->get_field_id("city");
        $cityFieldName = $this->get_field_name("city");

        $zip = htmlspecialchars($instance["zip"]);
        $zipFieldId = $this->get_field_id("zip");
        $zipFieldName = $this->get_field_name("zip");

        $propType = htmlspecialchars($instance["propType"]);
        $propTypeFieldId = $this->get_field_id("propType");
        $propTypeFieldName = $this->get_field_name("propType");

        $period = $instance["periodType"]; 
        $periodFieldId = $this->get_field_id("periodType");
        $periodFieldName = $this->get_field_name("periodType");

        $status = $instance["statusType"]; 
        $statusFieldId = $this->get_field_id("statusType");
        $statusFieldName = $this->get_field_name("statusType");

        $eDomain = htmlspecialchars($instance["eDomain"]);
        $eDomainFieldId = $this->get_field_id("eDomain");
        $eDomainFieldName = $this->get_field_name("eDomain");

        $baseFieldId = $this->get_field_id("areastatsOptions");
        $baseFieldName = $this->get_field_name("areastatsOptions");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        $property_types = dsWidgetAgent_ApiRequest::FetchData('AccountPropertyTypes', array(), false, 60 * 60 * 24);

        $checked_html = ($propType == '' ? 'selected' : '');
        $property_types_html = '<option value="-1" {$checked_html} >Select Property Type</option>';

        $randString = dsWidgets_Service_Base::get_random_string('abcdefghijklmnopqrstuvwxyz1234567890', 5);
        $options = get_option(DSIDXWIDGETS_OPTION_NAME);
		$idxpress_options = get_option(DSIDXPRESS_OPTION_NAME);
		if(!empty($idxpress_options["AccountID"])){
			$aid = $idxpress_options["AccountID"];
		}
		else{
			$aid = $options["AccountID"];
		}
		if(!empty($idxpress_options["SearchSetupID"])){
			$ssid = $idxpress_options["SearchSetupID"];
		}
		else{
			$ssid = $options["SearchSetupID"];
		}

        if(!empty($property_types)){
            $property_types = json_decode($property_types["body"]);
            foreach ($property_types as $property_type) {
                $checked_html = '';
			    $name = htmlentities($property_type->DisplayName);
			    $id = $property_type->PropertyTypeID;
                if($id == $propType){
                    $checked_html = 'selected';
                }
                $property_types_html .= <<<HTML
                <option value="{$id}" {$checked_html} >{$name}</option>
HTML;
            }
        }

        echo <<<HTML
			<input type='hidden' id='ds_widgets_area_stats_domain' value='{$eDomain}' name="ds_widgets_area_stats_domain" />
        	<p>
				<label for="{$titleFieldId}">Title</label>
				<input id="{$titleFieldId}" name="{$titleFieldName}" value="{$title}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$tractFieldId}">Tract</label>
				<input id="{$tractFieldId}" name="{$tractFieldName}" value="{$tract}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$communityFieldId}">Community</label>
				<input id="{$communityFieldId}" name="{$communityFieldName}" value="{$community}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$cityFieldId}">City</label>
				<input id="{$cityFieldId}" name="{$cityFieldName}" value="{$city}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$stateFieldId}">State</label>
				<input id="{$stateFieldId}" name="{$stateFieldName}" value="{$state}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$zipFieldId}">Zip Code</label>
				<input id="{$zipFieldId}" name="{$zipFieldName}" value="{$zip}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$propTypeFieldId}">Property Type</label>
				<select id="{$propTypeFieldId}" name="{$propTypeFieldName}"  class="widefat" type="text" onchange="jQuery(zWidgets_Area_Listener(this))">
                    {$property_types_html}
                </select>
			</p>
            <p>
                <label for="{$periodFieldId}">Period</label>
				<select class="widefat" id="{$periodFieldId}" name="{$periodFieldName}" onchange="jQuery(zWidgets_Area_Listener(this))">
					<option 
HTML;
if ($period == '1') echo 'selected'; 
echo <<<HTML
                    value="1">Weekly</option>
                    <option 
HTML;
if ($period == '2') echo 'selected'; 
echo <<<HTML
                    value="2" >Monthly</option>
				</select>
			</p>
            <p>
                <label for="{$statusFieldId}">Status</label>
				<select class="widefat" id="{$statusFieldId}" name="{$statusFieldName}" onchange="jQuery(zWidgets_Area_Listener(this))">
					<option 
HTML;
        if ($status == '1') echo 'selected'; 
        echo <<<HTML
                    value="1">Active</option>
                    <option 
HTML;
        if ($status == '2') echo 'selected'; 
        echo <<<HTML
                    value="2" >Pending</option>
                    <option 
HTML;
        if ($status == '3') echo 'selected'; 
        echo <<<HTML
                    value="3" >Expired</option>
                    <option 
HTML;

        if ($status == '4') echo 'selected'; 
        echo <<<HTML
                    value="4" >Sold</option>
				</select>
			</p>
            <script>
                jQuery(function(){
                    var a = document.getElementById('ds_widgets_area_stats_domain');
                    if(a.value == ''){
                        zWidgetsAdmin.fetchEncryptedValue(window.location.hostname, 'domain', a, '{$apiStub}');
                    }
                })
                zWidgets_Area_Listener = function (event){
                    zWidgetsAdmin.checkForAreaData_(event, '$randString', document.getElementById('ds_widgets_area_stats_domain').value, '$apiStub', {$aid}, {$ssid} )
                };
            </script>
HTML;
}}?>