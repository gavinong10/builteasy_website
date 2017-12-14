<?php
class dsIDXWidgets_MapSearch extends WP_Widget {
    
    var $widgetsCdn;
    var $instance = false;

    function dsIDXWidgets_MapSearch() {
        global $pagenow;
        $this->WP_Widget("dsidx-mapsearch", "Map Search", array(
            "classname" => "dsidx-widget-mapsearch",
            "description" => "Show specific area listings on a map"
            ));
        if ($pagenow == 'widgets.php')
            wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery'), false, true);

        $this->widgetsCdn = dsWidgets_Service_Base::$widgets_cdn;
    }
    
    function widget($args, $instance) {
        if (!$this->instance) {
            $this->instance = true;
        } 
        // abort silently for the second instance of the widget
        else {
            return;
        }

        extract($args);
        extract($instance);

        $error_message = dsWidgets_Service_Base::getWidgetErrorMsg($before_widget, $after_widget);
        if($error_message){
            echo $error_message;
            return;
        }

        $capabilities = dsWidgets_Service_Base::getCapabilities();

        $randString = dsWidgets_Service_Base::get_random_string('abcdefghijklmnopqrstuvwxyz1234567890', 5);
        wp_enqueue_script('googlemaps3', 'http://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'), false, true);

        $options = get_option(DSIDXWIDGETS_OPTION_NAME);
        $state = htmlspecialchars($instance["state"]);
		$city = str_replace(" \r\n ", ",", htmlspecialchars($instance["city"]));
		$city = str_replace(" \r\n", ",", $city);
		$city = str_replace("\r\n ", ",", $city);
		$city = str_replace("\r\n", ",", $city);

        
        if (empty($capabilities->MinPrice)) $instance['priceMin'] = '';
        if (empty($capabilities->MaxPrice)) $instance['priceMax'] = '';
        if (empty($capabilities->MinImprovedSqFt)) $instance['sqftMin'] = '';

        $instance = wp_parse_args($instance, array(
            "state"                 => $state,
            "city"                  => $city,
            "zip"                   => "",
            "priceMin"                 => "250000",
            "priceMax"              => "550000",
            "priceFloor"            => "100000",
            "priceCeiling"          => "1000000",
            "bedsMin"               => "2",
            "bathsMin"              => "2",
            "sqftMin"               => "1500",
            "statusType"        => "1",
            "rowCountType"      => "25",
            "sortType"          => "0",
            "eDomain" =>   "",
            "height" =>     "494",
            "width" =>      "548"
            ));
		
        $zip = htmlspecialchars($instance["zip"]);
        $priceMin = htmlspecialchars($instance["priceMin"]);
        $priceMax = htmlspecialchars($instance["priceMax"]);
        $priceFloor = htmlspecialchars($instance["priceFloor"]);
        $priceCeiling = htmlspecialchars($instance["priceCeiling"]);
        $bedsMin = htmlspecialchars($instance["bedsMin"]);
        $bathsMin = htmlspecialchars($instance["bathsMin"]);
        $sqftMin = htmlspecialchars($instance["sqftMin"]);
        $statusType = htmlspecialchars($instance["statusType"]);
        $rowCountType = htmlspecialchars($instance["rowCountType"]);
        $sortType = htmlspecialchars($instance["sortType"]);
        $imagesStub = dsWidgets_Service_Base::$widgets_images_stub;
        $apiStub = dsWidgets_Service_Base::$widgets_api_stub;
        $height = htmlspecialchars($instance["height"]);
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
		
        if(defined('ZPRESS_API') && ZPRESS_API != ''){
            $widget_header = call_user_func('\zpress\themes\Options::GetOption', 'theme_widgets_require_header');
    		if(!empty($widget_header->meta) && $widget_header->meta == 'true'){
    			echo $before_title;
    			echo 'Map Search';
    			echo $after_title;
    		}
        }
		
        echo <<<HTML
        <script type="text/javascript" id="divLocal{$randString}_">
			window.mapSearchHasDependency = true;
            var launchBaseCalled = false;
            var mapSearchDep1Finished = 1;
            LaunchBase{$randString} = function(){
                var mapSearchScript,mapSearchDep1RevScript, _ds_midx, mapSearchProgress;
                CreateObject{$randString} = function () { _ds_midx = { currentURL: '{$curURL}', curHeight: '{$height}', curWidth: '{$width}', productType: '0', curAPIStub: '{$apiStub}', curImageStub: '{$imagesStub}', targetDomain: window["zpress_widget_domain_token"],accountId: '{$aid}',searchSetupId: '{$ssid}',muteStyles: true,state: '{$state}',city: '{$city}',zip: '{$zip}',priceMin: '{$priceMin}',priceMax: '{$priceMax}',priceFloor: '{$priceFloor}',priceCeiling: '{$priceCeiling}',bedsMin: '{$bedsMin}',bathsMin: '{$bathsMin}',sqftMin: '{$sqftMin}',curDivID: 'divLocal{$randString}_',querySchema: 'HNIPilgrh/9PwdKmimpgPE05NfSeqIkyvHeXiSh+gUIUzKp3KXDCFoWJ/DzaOsYlntCSXtSk36hbB76URZk1Sirc9iLz3tiLPAN0SK/EbNCrr6XWxD7hAYVJcDwXtpN4',status: '{$statusType}',rowCount: '{$rowCountType}',sort: '{$sortType}' }; }
                AddJavaScriptToDOM{$randString}=function(c,d,e){ if(d!=1){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.id=e;a.type='text/javascript';a.async=true;a.src=c;a.onload=a.onreadystatechange=function(){ if(a.readyState){  if (a.readyState == "loaded" || a.readyState == "complete") {window[e] = 1;}}else{window[e] = 1;}};b.parentNode.insertBefore(a,b)}return 1};
                CreateWidget{$randString} = function () {
                 (window.mapSearchFinished == 1 && mapSearchDep1Finished == 1) ? (window["ds.widget.view.mapsearch"].isProcessing = true, CreateObject{$randString}(), new window["ds.widget.view.mapsearch"](_ds_midx), window["ds.widget.view.mapsearch"].isProcessing = false, window.mapSearchHasDependency = false, DetectMapScripts{$randString}()) : window.setTimeout("CreateWidget{$randString}(false)", 20); 
                }
                if (mapSearchScript != 1 && mapSearchProgress != 1) {mapSearchProgress=1, mapSearchScript = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/PostCompile/MapSearch_v1_1.js", mapSearchScript, 'mapSearchFinished') }; 
                 CreateWidget{$randString}();
            }
			DetectMapScripts{$randString} = function(){
				if (typeof google === 'object' && typeof google.maps === 'object') {
					MapSearchMapCallback();
				}
				else{
					window.setTimeout("DetectMapScripts{$randString}()", 20)
				}
			}
            GetToken{$randString}=function(){
                if(!window.zpress_widget_domain_token && window.zpress_widget_domain_token_progress!=1){
                    window.zpress_widget_domain_token_progress=1;var c=-1<navigator.userAgent.indexOf("MSIE 7.0")?!0:!1,d=-1<navigator.userAgent.indexOf("MSIE 8.0")||-1<navigator.userAgent.indexOf("MSIE 9.0")?!0:!1;
                    if(c){
                        rr=document.createElement("script");
                        rr.async=1;
                        rr.id="domainScript";
                        rr.type="text/javascript";
                        rr.src="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=Basic&curDomain=" + window.location.hostname+"&objectName_=error";
                        rr.onload=rr.onreadystatechange=function(){
                            if("undefined"!=typeof window.error){
                                alert("We had a problem authenticating this domain");
                            }
                            else{
                                window.zpress_widget_domain_token=window.encrypted_domain;
                                if(rr.readyState && (rr.readyState == "loaded" || rr.readyState == "complete")){                         
                                    if(launchBaseCalled != true){
                                        LaunchBase{$randString}();
                                    }
                                    launchBaseCalled = true;
                                }
                                else{ 
                                    window.setTimeout("GetToken{$randString}()",20);
                                }
                            }
                        };
                        document.getElementsByTagName("head")[0].appendChild(rr);
                    }
                    else if(c="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=CORS&objectName_=error",d){var a=new XDomainRequest;a.onload=function(){window.zpress_widget_domain_token=
                        eval(a.responseText);LaunchBase{$randString}()};a.onerror=function(){};a.onprogress=function(){};a.open("GET",c,!0);a.send(null)}else{var b;if(b=new XMLHttpRequest)b.onreadystatechange=function(){if(4==b.readyState)if(200==b.status){var a=eval(b.responseText);"undefined"!=typeof a.listingsError?alert(a.listingsError[0].Message):(window.zpress_widget_domain_token=a,LaunchBase{$randString}())}},b.open("GET",c,!0),b.send()}
                    }
                    else{
                        if(window.zpress_widget_domain_token && launchBaseCalled != true) {
                            LaunchBase{$randString}();
                            launchBaseCalled = true;
                        }
                        else{
                            if(launchBaseCalled != true){
                                window.setTimeout("GetToken{$randString}()",20);
                        }
                    }
                } 
            }
            GetToken{$randString}();
        </script>
HTML;
        echo $after_widget;
        dsidx_footer::ensure_disclaimer_exists("search");
    }
    function update($new_instance, $old_instance) {
        // we need to do this first-line awkwardness so that the title comes through in the sidebar display thing
        $new_instance["mapsearchOptions"]["state"] = $new_instance["state"];
        $new_instance["mapsearchOptions"]["city"] = $new_instance["city"];
        $new_instance["mapsearchOptions"]["zip"] = $new_instance["zip"];
        $new_instance["mapsearchOptions"]["priceMin"] = $new_instance["priceMin"];
        $new_instance["mapsearchOptions"]["priceMax"] = $new_instance["priceMax"];
        $new_instance["mapsearchOptions"]["priceFloor"] = $new_instance["priceFloor"];
        $new_instance["mapsearchOptions"]["priceCeiling"] = $new_instance["priceCeiling"];
        $new_instance["mapsearchOptions"]["bedsMin"] = $new_instance["bedsMin"];
        $new_instance["mapsearchOptions"]["bathsMin"] = $new_instance["bathsMin"];
        $new_instance["mapsearchOptions"]["sqftMin"] = $new_instance["sqftMin"];
        $new_instance["mapsearchOptions"]["statusType"] = $new_instance["statusType"];
        $new_instance["mapsearchOptions"]["rowCountType"] = $new_instance["rowCountType"];
        $new_instance["mapsearchOptions"]["sortType"] = $new_instance["sortType"];
        $new_instance["mapsearchOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance["mapsearchOptions"]["height"] = $new_instance["height"];
        $new_instance["mapsearchOptions"]["width"] = $new_instance["width"];
        $new_instance = $new_instance["mapsearchOptions"];
        return $new_instance;
    }
    function form($instance) {
        $personal_info = stripslashes_deep(get_option('personal_info')); 
        $city = empty($personal_info['city']) ? 'Irvine' : $personal_info['city'];
        $state = empty($personal_info['state']) ? 'CA' : $personal_info['state'];
        $instance = wp_parse_args($instance, array(
            "state"                 => $state,
            "city"                  => $city,
            "zip"                   => "",
            "priceMin"                 => "250000",
            "priceMax"              => "550000",
            "priceFloor"            => "100000",
            "priceCeiling"          => "1000000",
            "bedsMin"               => "2",
            "bathsMin"              => "2",
            "sqftMin"               => "1500",
            "statusType"        => "1",
            "rowCountType"      => "25",
            "sortType"          => "0",
            "eDomain" =>   "",
            "height" =>     "494",
            "width" =>      "548"
            ));

        $state = htmlspecialchars($instance["state"]);
        $stateFieldId = $this->get_field_id("state");
        $stateFieldName = $this->get_field_name("state");

        $city = htmlspecialchars($instance["city"]);
        $cityFieldId = $this->get_field_id("city");
        $cityFieldName = $this->get_field_name("city");

        $zip = htmlspecialchars($instance["zip"]);
        $zipFieldId = $this->get_field_id("zip");
        $zipFieldName = $this->get_field_name("zip");

        $priceMin = htmlspecialchars($instance["priceMin"]);
        $priceMinFieldId = $this->get_field_id("priceMin");
        $priceMinFieldName = $this->get_field_name("priceMin");

        $priceMax = htmlspecialchars($instance["priceMax"]);
        $priceMaxFieldId = $this->get_field_id("priceMax");
        $priceMaxFieldName = $this->get_field_name("priceMax");

        $priceFloor = htmlspecialchars($instance["priceFloor"]);
        $priceFloorFieldId = $this->get_field_id("priceFloor");
        $priceFloorFieldName = $this->get_field_name("priceFloor");

        $priceCeiling = htmlspecialchars($instance["priceCeiling"]);
        $priceCeilingFieldId = $this->get_field_id("priceCeiling");
        $priceCeilingFieldName = $this->get_field_name("priceCeiling");

        $bedsMin = htmlspecialchars($instance["bedsMin"]);
        $bedsMinFieldId = $this->get_field_id("bedsMin");
        $bedsMinFieldName = $this->get_field_name("bedsMin");

        $bathsMin = htmlspecialchars($instance["bathsMin"]);
        $bathsMinFieldId = $this->get_field_id("bathsMin");
        $bathsMinFieldName = $this->get_field_name("bathsMin");

        $sqftMin = htmlspecialchars($instance["sqftMin"]);
        $sqftMinFieldId = $this->get_field_id("sqftMin");
        $sqftMinFieldName = $this->get_field_name("sqftMin");

        $status = $instance["statusType"]; 
        $statusFieldId = $this->get_field_id("statusType");
        $statusFieldName = $this->get_field_name("statusType");

        $rowCount = $instance["rowCountType"]; 
        $rowCountFieldId = $this->get_field_id("rowCountType");
        $rowCountFieldName = $this->get_field_name("rowCountType");

        $sort = $instance["sortType"]; 
        $sortFieldId = $this->get_field_id("sortType");
        $sortFieldName = $this->get_field_name("sortType");

        $baseFieldId = $this->get_field_id("mapsearchOptions");
        $baseFieldName = $this->get_field_name("mapsearchOptions");

        $height = htmlspecialchars($instance["height"]);
        $heightFieldId = $this->get_field_id("height");
        $heightFieldName = $this->get_field_name("height");

        $width = htmlspecialchars($instance["width"]);
        $widthFieldId = $this->get_field_id("width");
        $widthFieldName = $this->get_field_name("width");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        $capabilities = dsWidgets_Service_Base::getCapabilities();

        echo <<<HTML
            <p>
				<label for="{$heightFieldId}">Height</label>
				<input id="{$heightFieldId}" name="{$heightFieldName}" value="{$height}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$widthFieldId}">Width</label>
				<input id="{$widthFieldId}" name="{$widthFieldName}" value="{$width}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$cityFieldId}">City (Press enter after each city)</label>
				<textarea id="{$cityFieldId}" name="{$cityFieldName}" rows="4" class="widefat" type="text" >{$city}</textarea>
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
        if (!empty($capabilities->MinPrice)) {
            echo <<<HTML
            <p>
                <label for="{$priceMinFieldId}">Search Min. Price</label>
                <input id="{$priceMinFieldId}" name="{$priceMinFieldName}" value="{$priceMin}" class="widefat" type="text" />
            </p>
HTML;
        } 
        if (!empty($capabilities->MaxPrice)) {
            echo <<<HTML
            <p>
                <label for="{$priceMaxFieldId}">Search Max. Price</label>
                <input id="{$priceMaxFieldId}" name="{$priceMaxFieldName}" value="{$priceMax}" class="widefat" type="text" />
            </p>
HTML;
        } 
            echo <<<HTML
            <p>
				<label for="{$priceFloorFieldId}">Min. Searchable Price</label>
				<input id="{$priceFloorFieldId}" name="{$priceFloorFieldName}" value="{$priceFloor}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$priceCeilingFieldId}">Max. Searchable Price</label>
				<input id="{$priceCeilingFieldId}" name="{$priceCeilingFieldName}" value="{$priceCeiling}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$bedsMinFieldId}">Min. Beds</label>
				<input id="{$bedsMinFieldId}" name="{$bedsMinFieldName}" value="{$bedsMin}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$bathsMinFieldId}">Min. Baths</label>
				<input id="{$bathsMinFieldId}" name="{$bathsMinFieldName}" value="{$bathsMin}" class="widefat" type="text" />
			</p>
HTML;
        if (!empty($capabilities->MinImprovedSqFt)) {
            echo <<<HTML
            <p>
                <label for="{$sqftMinFieldId}">Min. Square Feet</label>
                <input id="{$sqftMinFieldId}" name="{$sqftMinFieldName}" value="{$sqftMin}" class="widefat" type="text" />
            </p>
HTML;
        } 
        echo <<<HTML
            <p>
                <label for="{$statusFieldId}">Status</label>
				<select class="widefat" id="{$statusFieldId}" name="{$statusFieldName}">
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
            <p>
                <label for="{$rowCountFieldId}">Max. # of Listings</label>
				<select class="widefat" id="{$rowCountFieldId}" name="{$rowCountFieldName}">
					<option 
HTML;
        if ($rowCount == '10') echo 'selected'; 
        echo <<<HTML
                    value="10">10</option>
                    <option 
HTML;
        if ($rowCount == '25') echo 'selected'; 
        echo <<<HTML
                    value="25" >25</option>
                    <option 
HTML;
        if ($rowCount == '50') echo 'selected'; 
        echo <<<HTML
                    value="50" >50</option>
                    <option 
HTML;
        if ($rowCount == '100') echo 'selected'; 
        echo <<<HTML
                    value="100" >100</option>
				</select>
			</p>
            <p>
                <label for="{$sortFieldId}">Sort</label>
				<select class="widefat" id="{$sortFieldId}" name="{$sortFieldName}">
					<option 
HTML;
        if ($sort == '0') echo 'selected'; 
        echo <<<HTML
                    value="0">Price $ to $$$</option>
                    <option 
HTML;
        if ($sort == '1') echo 'selected'; 
        echo <<<HTML
                    value="1" >Price $$$ to $</option>
				</select>
			</p>
HTML;
}}?>