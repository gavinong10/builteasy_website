<?php
/** THIS FILE IS NO LONGER BEING USED. PENDING DELETION **/
class dsIDXWidgets_quicksearch extends WP_Widget {
    var $widgetsCdn;

    function dsIDXWidgets_QuickSearch() {
        $this->WP_Widget("dsidx-quicksearch", "Quick Search", array(
            "classname" => "dsidx-widget-quicksearch",
            "description" => "Show a quicksearch input widget"
            ));

        $this->widgetsCdn = dsWidgets_Service_Base::$widgets_cdn;
    }
    function widget($args, $instance) {
        extract($args);
        extract($instance);
        $options = get_option(DSIDXWIDGETS_OPTION_NAME);
        $randString = dsWidgets_Service_Base::get_random_string('abcdefghijklmnopqrstuvwxyz1234567890', 5);
        $widgetType = htmlspecialchars($instance["widgetType"]);
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
    			echo 'Quick Search';
    			echo $after_title;
    		}
        }
		
        echo <<<HTML
        <script type="text/javascript" id="divLocal{$randString}_">
            LaunchBase{$randString} = function(){
				window.quickSearchHasDependency = true;
                var quickSearchScript, _ds_midx;
                CreateObject{$randString} = function () { _ds_midx = { currentURL: '{$curURL}', widgetType: '{$widgetType}', curAPIStub: '{$apiStub}', curImageStub: '{$imagesStub}', targetDomain: window["zpress_widget_domain_token"], accountId: '{$aid}',searchSetupId: '{$ssid}',muteStyles: true,location: 0,fields: '0123',city: '', community: '', curDivID: 'divLocal{$randString}_',product: '0' }; }
                AddJavaScriptToDOM{$randString}=function(c,d,e){if(1!=d){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.async=!0;a.src=c;a.onload=function(){ window[e] = 1;};b.parentNode.insertBefore(a,b)}return 1};
                CreateWidget{$randString} = function () {
                 (window.quickSearchFinished == 1) ? (window["ds.widget.view.quicksearch"].isProcessing = true, CreateObject{$randString}(), new window["ds.widget.view.quicksearch"](_ds_midx), window["ds.widget.view.quicksearch"].isProcessing = false, window.quickSearchHasDependency = false) : window.setTimeout("CreateWidget{$randString}(false)", 20); 
                }
                if (quickSearchScript != 1) { quickSearchScript = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/PostCompile/QuickSearch_v1_1.js", quickSearchScript, 'quickSearchFinished') }; 
                  CreateWidget{$randString}();
            }
            GetToken{$randString}=function(){if(!window.zpress_widget_domain_token&&1!=window.zpress_widget_domain_token_progress){window.zpress_widget_domain_token_progress=1;var c=-1<navigator.userAgent.indexOf("MSIE 7.0")?!0:!1,d=-1<navigator.userAgent.indexOf("MSIE 8.0")||-1<navigator.userAgent.indexOf("MSIE 9.0")?!0:!1;if(c)rr=document.createElement("script"),rr.async=!0,rr.id="domainScript",rr.type="text/javascript",rr.src="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=Basic&curDomain="+
            window.location.hostname+"&objectName_=error",rr.onload=rr.onreadystatechange=function(){"undefined"!=typeof window.error?alert("We had a problem authenticating this domain"):(window.zpress_widget_domain_token=window.encrypted_domain,LaunchBase{$randString}())},document.getElementsByTagName("head")[0].appendChild(rr);else if(c="{$apiStub}Encrypt/?targetString="+window.location.hostname+"&targetObject=domain&authType=CORS&objectName_=error",d){var a=new XDomainRequest;a.onload=function(){window.zpress_widget_domain_token=
            eval(a.responseText);LaunchBase{$randString}()};a.onerror=function(){};a.onprogress=function(){};a.open("GET",c,!0);a.send(null)}else{var b;if(b=new XMLHttpRequest)b.onreadystatechange=function(){if(4==b.readyState)if(200==b.status){var a=eval(b.responseText);"undefined"!=typeof a.listingsError?alert(a.listingsError[0].Message):(window.zpress_widget_domain_token=a,LaunchBase{$randString}())}},b.open("GET",c,!0),b.send()}}else window.zpress_widget_domain_token?LaunchBase{$randString}():
            window.setTimeout("GetToken{$randString}()",20)};GetToken{$randString}();
        </script>
HTML;
        echo $after_widget;
        dsidx_footer::ensure_disclaimer_exists("search");
    }
    function update($new_instance, $old_instance) {
        $new_instance["quicksearchOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance["quicksearchOptions"]["widgetType"] = $new_instance["widgetType"];
        $new_instance = $new_instance["quicksearchOptions"];
        return $new_instance;
    }
    function form($instance) {
        wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery'), false, true);
        $instance = wp_parse_args($instance, array(
            "eDomain" =>   "",
            "widgetType" => 0
                    ));

        $widgetType = htmlspecialchars($instance["widgetType"]);
        $widgetTypeFieldId = $this->get_field_id("widgetType");
        $widgetTypeFieldName = $this->get_field_name("widgetType");

        $baseFieldId = $this->get_field_id("quicksearchOptions");
        $baseFieldName = $this->get_field_name("quicksearchOptions");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        echo <<<HTML
        <p>
            <label>Widget Aspect Ratio</label><br/><br/>
            <input type="radio" name="{$widgetTypeFieldName}" id="{$widgetTypeFieldId}" 
HTML;
        if ($widgetType == '0') echo ' checked'; 
        echo <<<HTML
            value="0"/> Horizontal<br/>
            <input type="radio" name="{$widgetTypeFieldName}" 
HTML;
        if ($widgetType == '1') echo 'checked'; 
        echo <<<HTML
            value="1"/> Vertical - <i>Recommended for side columns</i><br/>
            <input type="radio" name="{$widgetTypeFieldName}"
HTML;
        if ($widgetType == '2') echo 'checked'; 
        echo <<<HTML
             value="2"/> Flat<br/>
        </p>
HTML;
}}?>