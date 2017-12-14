<?php
class dsIDXWidgets_Affordability extends WP_Widget {
    var $widgetsCdn;

    function dsIDXWidgets_Affordability() {
        $this->WP_Widget("dsidx-affordability", "Search by Affordability", array(
            "classname" => "dsidx-widget-affordability",
            "description" => "Show a search input based on the user's affordability for your site."
            ));

        $this->widgetsCdn = dsWidgets_Service_Base::$widgets_cdn;
    }
    function widget($args, $instance) {
        extract($args);
        extract($instance);
        $options = get_option(DSIDXWIDGETS_OPTION_NAME);
        $randString = dsWidgets_Service_Base::get_random_string('abcdefghijklmnopqrstuvwxyz1234567890', 5);
        $income = htmlspecialchars($instance["income"]);
        $downPayment = htmlspecialchars($instance["downPayment"]);
        $monthlyDebts = htmlspecialchars($instance["monthlyDebts"]);
        $state = htmlspecialchars($instance["state"]);
        $city = htmlspecialchars($instance["city"]);
        $zip = htmlspecialchars($instance["zip"]);
        $priceMin = htmlspecialchars($instance["priceMin"]);
        $propType = '';
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
    		if(!empty($widget_header->meta) && $widget_header->meta == 'true'){
    			echo $before_title;
    			echo 'Affordability';
    			echo $after_title;
    		}
        }
		
        echo <<<HTML
        <div>
            <script type="text/javascript" id="divLocal{$randString}_">
				window.affordabilityHasDependency = true;
                LaunchBase{$randString} = function(){
                    var affordabilityScript, _ds_midx;
                    CreateObject{$randString} = function () { _ds_midx = {currentURL: '{$curURL}',  curAPIStub: '{$apiStub}', curImageStub: '{$imagesStub}', targetDomain: window["zpress_widget_domain_token"], accountId: '{$aid}',searchSetupId: '{$ssid}',muteStyles: true,income: '{$income}',downPayment: '{$downPayment}',monthlyDebts: '{$monthlyDebts}',state: '{$state}',city: '{$city}',zip: '{$zip}',priceMin: '{$priceMin}',curDivID: 'divLocal{$randString}_',querySchema: '12dGTTViUjEzC1rrNlw6Lq6A6wZQlgBarlIcucpGTkQrUP3gCimYF6deRFaavu2IbPpaOkZ9I4K42QaAhLVEcA==',productType: '0' }; }
                    AddJavaScriptToDOM{$randString}=function(c,d,e){if(1!=d){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.async=!0;a.src=c;a.onload=function(){ window[e] = 1;};b.parentNode.insertBefore(a,b)}return 1};
                    CreateWidget{$randString} = function () {
                     (window.affordabilityFinished == 1) ? (window["ds.widget.view.affordability"].isProcessing = true, CreateObject{$randString}(), new window["ds.widget.view.affordability"](_ds_midx), window["ds.widget.view.affordability"].isProcessing = false, window.affordabilityHasDependency = false) : window.setTimeout("CreateWidget{$randString}(false)", 20); 
                    }
                    if (affordabilityScript != 1) { affordabilityScript = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/PostCompile/Affordability_v1_1.js", affordabilityScript, 'affordabilityFinished') }; 
                      CreateWidget{$randString}();
                }
                GetToken{$randString}=function(){if(!window.zpress_widget_domain_token&&1!=window.zpress_widget_domain_token_progress){window.zpress_widget_domain_token_progress=1;var c=-1<navigator.userAgent.indexOf("MSIE 7.0")?!0:!1,d=-1<navigator.userAgent.indexOf("MSIE 8.0")||-1<navigator.userAgent.indexOf("MSIE 9.0")?!0:!1;if(c)rr=document.createElement("script"),rr.async=!0,rr.id="domainScript",rr.type="text/javascript",rr.src="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=Basic&curDomain="+
                window.location.hostname+"&objectName_=error",rr.onload=rr.onreadystatechange=function(){"undefined"!=typeof window.error?alert("We had a problem authenticating this domain"):(window.zpress_widget_domain_token=window.encrypted_domain,LaunchBase{$randString}())},document.getElementsByTagName("head")[0].appendChild(rr);else if(c="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=CORS&objectName_=error",d){var a=new XDomainRequest;a.onload=function(){window.zpress_widget_domain_token=
                eval(a.responseText);LaunchBase{$randString}()};a.onerror=function(){};a.onprogress=function(){};a.open("GET",c,!0);a.send(null)}else{var b;if(b=new XMLHttpRequest)b.onreadystatechange=function(){if(4==b.readyState)if(200==b.status){var a=eval(b.responseText);"undefined"!=typeof a.listingsError?alert(a.listingsError[0].Message):(window.zpress_widget_domain_token=a,LaunchBase{$randString}())}},b.open("GET",c,!0),b.send()}}else window.zpress_widget_domain_token?LaunchBase{$randString}():
                window.setTimeout("GetToken{$randString}()",20)};GetToken{$randString}();
            </script>
        </div>
HTML;
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        // we need to do this first-line awkwardness so that the title comes through in the sidebar display thing
        $new_instance["affordabilityOptions"]["income"] = $new_instance["income"];
        $new_instance["affordabilityOptions"]["downPayment"] = $new_instance["downPayment"];
        $new_instance["affordabilityOptions"]["monthlyDebts"] = $new_instance["monthlyDebts"];
        $new_instance["affordabilityOptions"]["state"] = $new_instance["state"];
        $new_instance["affordabilityOptions"]["city"] = $new_instance["city"];
        $new_instance["affordabilityOptions"]["zip"] = $new_instance["zip"];
        $new_instance["affordabilityOptions"]["priceMin"] = $new_instance["priceMin"];
        $new_instance["affordabilityOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance = $new_instance["affordabilityOptions"];
        return $new_instance;
    }
    function form($instance) {
        wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery, jquery'), false, true);
        $instance = wp_parse_args($instance, array(
            "income"				=> "100000",
            "downPayment"           => "20000",
            "monthlyDebts"          => "500",
            "state"                 => "",
            "city"                  => "",
            "zip"                   => "",
            "priceMin"              => "",
            "eDomain" =>   ""
                    ));
        $income = htmlspecialchars($instance["income"]);
        $incomeFieldId = $this->get_field_id("income");
        $incomeFieldName = $this->get_field_name("income");

        $downPayment = htmlspecialchars($instance["downPayment"]);
        $downPaymentFieldId = $this->get_field_id("downPayment");
        $downPaymentFieldName = $this->get_field_name("downPayment");

        $monthlyDebts = htmlspecialchars($instance["monthlyDebts"]);
        $monthlyDebtsFieldId = $this->get_field_id("monthlyDebts");
        $monthlyDebtsFieldName = $this->get_field_name("monthlyDebts");

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

        $baseFieldId = $this->get_field_id("affordabilityOptions");
        $baseFieldName = $this->get_field_name("affordabilityOptions");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        echo <<<HTML
        	<p>
				<label for="{$incomeFieldId}">Income</label>
				<input id="{$incomeFieldId}" name="{$incomeFieldName}" value="{$income}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$downPaymentFieldId}">Down Payment</label>
				<input id="{$downPaymentFieldId}" name="{$downPaymentFieldName}" value="{$downPayment}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$monthlyDebtsFieldId}">Monthly Debts</label>
				<input id="{$monthlyDebtsFieldId}" name="{$monthlyDebtsFieldName}" value="{$monthlyDebts}" class="widefat" type="text" />
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
                <label for="{$priceMinFieldId}">Results Minimum Price</label>
                <input id="{$priceMinFieldId}" name="{$priceMinFieldName}" value="{$priceMin}" class="widefat" type="text" />
            </p>
HTML;
}}?>