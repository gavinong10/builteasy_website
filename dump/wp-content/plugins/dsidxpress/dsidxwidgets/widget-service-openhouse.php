<?php
class dsIDXWidgets_OpenHouse extends WP_Widget {
    var $widgetsCdn;

    function dsIDXWidgets_OpenHouse() {
        $this->WP_Widget("dsidx-openhouse", "Open Houses", array(
            "classname" => "dsidx-widget-openhouse",
            "description" => "Show upcoming open houses in a specific area"
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
        $maxPrice = htmlspecialchars($instance["maxPrice"]);
        $state = htmlspecialchars($instance["state"]);
        $city = htmlspecialchars($instance["city"]);
        $zip = htmlspecialchars($instance["zip"]);
        $imagesStub = dsWidgets_Service_Base::$widgets_images_stub;
        $apiStub = dsWidgets_Service_Base::$widgets_api_stub;
        $width = htmlspecialchars($instance["width"]);
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
		
        echo <<<HTML
        <h3 class="widget-title">{$title}</h3>
        <script type="text/javascript" id="divLocal{$randString}_">
			window.openHouseHasDependency = true;
            LaunchBase{$randString} = function(){
                var openHouseScript, _ds_midx;
                CreateObject{$randString} = function () { _ds_midx = { currentURL: '{$curURL}', curWidth: '{$width}', showHeader: false, productType: '0', curAPIStub: '{$apiStub}', curImageStub: '{$imagesStub}', targetDomain: window["zpress_widget_domain_token"],accountId: '{$aid}',searchSetupId: '{$ssid}',muteStyles: true,curTitle: '{$title}',maxPrice: '{$maxPrice}',state: '{$state}',city: '{$city}',zip: '{$zip}',curDivID: 'divLocal{$randString}_',querySchema: '12dGTTViUjEzC1rrNlw6Lq6A6wZQlgBarlIcucpGTkQrUP3gCimYF6deRFaavu2IclpZHIg4RCzrHG9c9wL/T6KlYf/XKRDteSXSnjQSMq4=' }; }
                AddJavaScriptToDOM{$randString}=function(c,d,e){if(1!=d){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.async=!0;a.src=c;a.onload=function(){ window[e] = 1;};b.parentNode.insertBefore(a,b)}return 1};
                CreateWidget{$randString} = function () {
                    (window.openHouseFinished == 1) ? (window["ds.widget.view.openhouse"].isProcessing = true, CreateObject{$randString}(), new window["ds.widget.view.openhouse"](_ds_midx), window["ds.widget.view.openhouse"].isProcessing = false, window.openHouseHasDependency = false) : window.setTimeout("CreateWidget{$randString}(false)", 20); 
                }
                if (openHouseScript != 1) { openHouseScript = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/PostCompile/OpenHouse_v1_1.js", openHouseScript, 'openHouseFinished') }; 
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
        $new_instance["openhouseOptions"]["title"] = $new_instance["title"];
        $new_instance["openhouseOptions"]["maxPrice"] = $new_instance["maxPrice"];
        $new_instance["openhouseOptions"]["state"] = $new_instance["state"];
        $new_instance["openhouseOptions"]["city"] = $new_instance["city"];
        $new_instance["openhouseOptions"]["zip"] = $new_instance["zip"];
        $new_instance["openhouseOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance["openhouseOptions"]["width"] = $new_instance["width"];
        $new_instance = $new_instance["openhouseOptions"];
        return $new_instance;
    }
    function form($instance) {
        wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery'), false, true);
        $personal_info = stripslashes_deep(get_option('personal_info')); 
        $city = empty($personal_info['city']) ? 'Irvine' : $personal_info['city'];
        $state = empty($personal_info['state']) ? 'CA' : $personal_info['state'];
        $instance = wp_parse_args($instance, array(
            "title"				=> "Open houses near you:",
            "maxPrice"           => "950000",
            "state"                 => $state,
            "city"                  => $city,
            "zip"                   => "",
            "eDomain" =>   "",
            "width" =>      "300"
            ));
        $title = htmlspecialchars($instance["title"]);
        $titleFieldId = $this->get_field_id("title");
        $titleFieldName = $this->get_field_name("title");

        $maxPrice = htmlspecialchars($instance["maxPrice"]);
        $maxPriceFieldId = $this->get_field_id("maxPrice");
        $maxPriceFieldName = $this->get_field_name("maxPrice");

        $state = htmlspecialchars($instance["state"]);
        $stateFieldId = $this->get_field_id("state");
        $stateFieldName = $this->get_field_name("state");

        $city = htmlspecialchars($instance["city"]);
        $cityFieldId = $this->get_field_id("city");
        $cityFieldName = $this->get_field_name("city");

        $zip = htmlspecialchars($instance["zip"]);
        $zipFieldId = $this->get_field_id("zip");
        $zipFieldName = $this->get_field_name("zip");

        $baseFieldId = $this->get_field_id("openhouseOptions");
        $baseFieldName = $this->get_field_name("openhouseOptions");

        $width = htmlspecialchars($instance["width"]);
        $widthFieldId = $this->get_field_id("width");
        $widthFieldName = $this->get_field_name("width");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        echo <<<HTML
        	<p>
				<label for="{$titleFieldId}">Title</label>
				<input id="{$titleFieldId}" name="{$titleFieldName}" value="{$title}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$widthFieldId}">Width</label>
				<input id="{$widthFieldId}" name="{$widthFieldName}" value="{$width}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$maxPriceFieldId}">Max. Price</label>
				<input id="{$maxPriceFieldId}" name="{$maxPriceFieldName}" value="{$maxPrice}" class="widefat" type="text" />
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
HTML;
}}?>