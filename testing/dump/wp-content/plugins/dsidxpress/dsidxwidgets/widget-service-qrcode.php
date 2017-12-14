<?php
class dsIDXWidgets_QRCode extends WP_Widget {
    var $widgetsCdn;

    function dsIDXWidgets_QRCode() {
        $this->WP_Widget("dsidx-qrcode", "QR Code", array(
            "classname" => "dsidx-widget-qrcode",
            "description" => "Show a QR Code image for your site."
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
        $message = htmlspecialchars($instance["message"]);
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
        echo <<<HTML
        <h3 class="widget-title">{$title}</h3>
        <script type="text/javascript" id="divLocal{$randString}_">
            LaunchBase{$randString} = function(){
                var qrCodeScript, _ds_midx;
                CreateObject{$randString} = function () { 
                    _ds_midx = { currentURL: '{$curURL}', showHeader: false, curAPIStub: '{$apiStub}', curImageStub: '{$imagesStub}', targetDomain: window["zpress_widget_domain_token"], accountId: '{$aid}',searchSetupId: '{$ssid}',muteStyles: true,message: '{$message}',curDivID: 'divLocal{$randString}_' }; 
                }
                AddJavaScriptToDOM{$randString}=function(c,d,e){
                    if(1!=d){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.async=!0;a.src=c;a.onload=function(){ window[e] = 1;};b.parentNode.insertBefore(a,b)}return 1};
                CreateWidget{$randString} = function () {
                    (window.qrCodeFinished == 1) ? (window["ds.widget.view.qrcode"].isProcessing = true, CreateObject{$randString}(), new window["ds.widget.view.qrcode"](_ds_midx), window["ds.widget.view.qrcode"].isProcessing = false) : window.setTimeout("CreateWidget{$randString}(false)", 20); 
                }
                if (qrCodeScript != 1) { qrCodeScript = AddJavaScriptToDOM{$randString}("{$this->widgetsCdn}/Scripts/PostCompile/QRCode.js", qrCodeScript, 'qrCodeFinished') }; 
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
        $new_instance["qrCodeOptions"]["title"] = $new_instance["title"];
        $new_instance["qrCodeOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance["qrCodeOptions"]["message"] = $new_instance["message"];
        $new_instance = $new_instance["qrCodeOptions"];
        return $new_instance;
    }
    function form($instance) {
        wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery'), false, true);
        $instance = wp_parse_args($instance, array(
            "title"				=> "QR Code",
            "eDomain" =>   "",
            "message"   => "Scan this code to take our mobile IDX with you."
                    ));
        $title = htmlspecialchars($instance["title"]);
        $titleFieldId = $this->get_field_id("title");
        $titleFieldName = $this->get_field_name("title");

        $message = htmlspecialchars($instance["message"]);
        $messageFieldId = $this->get_field_id("message");
        $messageFieldName = $this->get_field_name("message");

        $baseFieldId = $this->get_field_id("qrCodeOptions");
        $baseFieldName = $this->get_field_name("qrCodeOptions");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        echo <<<HTML
            <input type='hidden' id='{$eDomainFieldId}' value='{$eDomain}' name="{$eDomainFieldName}" />
        	<p>
				<label for="{$titleFieldId}">Widget title</label>
				<input id="{$titleFieldId}" name="{$titleFieldName}" value="{$title}" class="widefat" type="text" />
			</p>
            <p>
				<label for="{$messageFieldId}">Widget Message</label>
				<input id="{$messageFieldId}" name="{$messageFieldName}" value="{$message}" class="widefat" type="text" />
			</p>
HTML;
}}?>