<?php
add_action('wp_head', array('dsSearchAgent_IdxQuickSearchWidget', 'LoadScripts'), 100);

class dsSearchAgent_IdxQuickSearchWidget extends WP_Widget {
    var $widgetsCdn;

    function dsSearchAgent_IdxQuickSearchWidget() {
        $this->WP_Widget("dsidx-quicksearch", "IDX Quick Search", array(
            "classname" => "dsidx-widget-quick-search",
            "description" => "Choose either horizontal or vertical format. A simple responsive search form. Allow users to type any location, select from available property types and filter by price range."
            ));

        $this->widgetsCdn = dsWidgets_Service_Base::$widgets_cdn;
    }
    public static function LoadScripts(){
        dsidxpress_autocomplete::AddScripts(true);
    }

    public static function shortcodeWidget($values){
        self::renderWidget(array(), $values);
    }

    function widget($args, $instance) { // public so we can use this on our shortcode as well
        self::renderWidget($args, $instance);
    }

    public static function renderWidget($args, $instance){
        extract($args);
        extract($instance);
        $title = apply_filters("widget_title", $title);

        $options = get_option(DSIDXPRESS_OPTION_NAME);
        if (!$options["Activated"])
            return;

        $pluginUrl = get_home_url() . '/wp-content/plugins/dsidxpress/';
        $formAction = get_home_url() . "/idx/";

        $defaultSearchPanels = \dsSearchAgent_ApiRequest::FetchData("AccountSearchPanelsDefault", array(), false, 60 * 60 * 24);
        $defaultSearchPanels = $defaultSearchPanels["response"]["code"] == "200" ? json_decode($defaultSearchPanels["body"]) : null;
        $propertyTypes = \dsSearchAgent_ApiRequest::FetchData("AccountSearchSetupFilteredPropertyTypes", array(), false, 60 * 60 * 24);
        $propertyTypes = $propertyTypes["response"]["code"] == "200" ? json_decode($propertyTypes["body"]) : null;
        $account_options = \dsSearchAgent_ApiRequest::FetchData("AccountOptions");
        $account_options = $account_options["response"]["code"] == "200" ? json_decode($account_options["body"]) : null;
        $widgetType = htmlspecialchars($instance["widgetType"]);

        $values =array();
        $values['idx-q-Locations'] = isset($_GET['idx-q-Locations']) ? stripslashes($_GET['idx-q-Locations']) : null;
        $values['idx-q-PropertyTypes'] = isset($_GET['idx-q-PropertyTypes']) ? $_GET['idx-q-PropertyTypes'] : null;
        $values['idx-q-PriceMin'] = isset($_GET['idx-q-PriceMin']) ? $_GET['idx-q-PriceMin'] : null;
        $values['idx-q-PriceMax'] = isset($_GET['idx-q-PriceMax']) ? $_GET['idx-q-PriceMax'] : null;

        $specialSlugs = array(
            'city'      => 'idx-q-Cities',
            'community' => 'idx-q-Communities',
            'tract'     => 'idx-q-TractIdentifiers',
            'zip'       => 'idx-q-ZipCodes'
        );

        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        $count = 0;
        foreach($urlParts as $p){
            if(array_key_exists($p, $specialSlugs) && isset($urlParts[$count + 1])){
                $values['idx-q-Locations'] = ucwords($urlParts[$count + 1]);
            }
            $count++;
        }

        echo $before_widget;
        if ($title)
            echo $before_title . $title . $after_title;

        $widgetClass = ($widgetType == 1 || $widgetType == 'vertical')?'dsidx-resp-vertical':'dsidx-resp-horizontal';
        
        if(isset($instance['class'])){ //Allows us to add custim class for shortcode etc.
            $widgetClass .= ' '.$instance['class'];
        }

        echo <<<HTML
            <div class="dsidx-resp-search-box {$widgetClass}">
                <form class="dsidx-resp-search-form" action="{$formAction}" method="GET">
                    <fieldset>
                        <div class="dsidx-resp-area dsidx-resp-location-area">
                            <label for="dsidx-resp-location" class="dsidx-resp-location">Location</label>
                            <input placeholder="Search Term" name="idx-q-Locations" type="text" class="text dsidx-search-omnibox-autocomplete" id="dsidx-resp-location" value="{$values['idx-q-Locations']}" />
                        </div>
                        <div class="dsidx-resp-area dsidx-resp-type-area">
                            <label for="dsidx-resp-area-type" class="dsidx-resp-type">Type</label>                      
                            <select id="dsidx-resp-area-type" class="dsidx-resp-select" name="idx-q-PropertyTypes">
                                <option value="">Any</option>
HTML;
                                if (is_array($propertyTypes)) {
                                    foreach ($propertyTypes as $propertyType) {
                                        $name = htmlentities($propertyType->DisplayName);
                                        $selected = $propertyType->SearchSetupPropertyTypeID == $values['idx-q-PropertyTypes']?' selected="selected"':'';
                                        echo "<option value=\"{$propertyType->SearchSetupPropertyTypeID}\"{$selected}>{$name}</option>";
                                    }
                                }

                                echo <<<HTML
                            </select>
                        </div>

                        <div class="dsidx-resp-area dsidx-quick-resp-min-baths-area dsidx-resp-area-half dsidx-resp-area-left">
                            <label for="idx-q-BedsMin">Beds</label>
                            <select id="idx-q-BedsMin" name="idx-q-BedsMin" class="dsidx-beds">
                                <option value="">Any</option>
HTML;
                                for($i=1; $i<=9; $i++){
                                    $selected = $i == $values['idx-q-BedsMin']?' selected="selected"':'';
                                    echo '<option value="'.$i.'"'.$selected.'>'.$i.'+</option>';
                                }
                            echo <<<HTML
                            </select>
                        </div>

                        <div class="dsidx-resp-area dsidx-quick-resp-min-baths-area dsidx-resp-area-half dsidx-resp-area-right">
                            <label for="idx-q-BathsMin">Baths</label>
                            <select id="idx-q-BathsMin" name="idx-q-BathsMin" class="dsidx-baths">
                                <option value="">Any</option>
HTML;
                                for($i=1; $i<=9; $i++){
                                    $selected = $i == $values['idx-q-BathsMin']?' selected="selected"':'';
                                    echo '<option value="'.$i.'"'.$selected.'>'.$i.'+</option>';
                                }
                            echo <<<HTML
                            </select>
                        </div>

                        <div class="dsidx-resp-area dsidx-quick-resp-price-area dsidx-resp-price-area-min dsidx-resp-area-half dsidx-resp-area-left">
                            <label for="dsidx-resp-price-min" class="dsidx-resp-price">Price</label>
                            <input id="idx-q-PriceMin" name="idx-q-PriceMin" type="text" class="dsidx-price" placeholder="Any" value="{$values['idx-q-PriceMin']}" />
                        </div>
                        <div class="dsidx-resp-area dsidx-quick-resp-price-area dsidx-resp-price-area-max dsidx-resp-area-half dsidx-resp-area-right">
                            <label for="dsidx-resp-price-max" class="dsidx-resp-price">To</label>
                            <input id="idx-q-PriceMax" name="idx-q-PriceMax" type="text" class="dsidx-price" placeholder="Any" value="{$values['idx-q-PriceMax']}" />
                        </div>
                        <div class="dsidx-resp-area dsidx-resp-area-submit">
                            <label for="dsidx-resp-submit" class="dsidx-resp-submit">&nbsp;</label>
                            <input type="submit" class="dsidx-resp-submit" value="Search" />
                        </div>
                    </fieldset>
                </form>
            </div>
HTML;
        \dsidx_footer::ensure_disclaimer_exists("search");
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $new_instance["quicksearchOptions"]["title"] = strip_tags($new_instance["title"]);
        $new_instance["quicksearchOptions"]["eDomain"] = $new_instance["eDomain"];
        $new_instance["quicksearchOptions"]["widgetType"] = $new_instance["widgetType"];
        $new_instance = $new_instance["quicksearchOptions"];
        return $new_instance;
    }
    function form($instance) {
        wp_enqueue_script('dsidxwidgets_widget_service_admin', DSIDXWIDGETS_PLUGIN_URL . 'js/widget-service-admin.js', array('jquery'), false, true);
        $instance = wp_parse_args($instance, array(
            "title" => "Real Estate Search",
            "eDomain" =>   "",
            "widgetType" => 1
                    ));

        $title = htmlspecialchars($instance["title"]);
        $widgetType = htmlspecialchars($instance["widgetType"]);
        $widgetTypeFieldId = $this->get_field_id("widgetType");
        $widgetTypeFieldName = $this->get_field_name("widgetType");

        $titleFieldId = $this->get_field_id("title");
        $titleFieldName = $this->get_field_name("title");
        $baseFieldId = $this->get_field_id("quicksearchOptions");
        $baseFieldName = $this->get_field_name("quicksearchOptions");

        $apiStub = dsWidgets_Service_Base::$widgets_admin_api_stub;

        echo <<<HTML
        <p>
            <label for="{$titleFieldId}">Widget title</label>
            <input id="{$titleFieldId}" name="{$titleFieldName}" value="{$title}" class="widefat" type="text" />
        </p>
        <p>
            <label>Widget Aspect Ratio</label><br/><br/>
            <input type="radio" name="{$widgetTypeFieldName}" id="{$widgetTypeFieldId}" 
HTML;
        if ($widgetType == '1') echo ' checked'; 
        echo <<<HTML
            value="1"/> Vertical - <i>Recommended for side columns</i><br/>
            <input type="radio" name="{$widgetTypeFieldName}" 
HTML;
        if ($widgetType == '0') echo 'checked'; 
        echo <<<HTML
            value="0"/> Horizontal - <i>Recommended for wider areas</i><br/>
        </p>
HTML;
}}?>