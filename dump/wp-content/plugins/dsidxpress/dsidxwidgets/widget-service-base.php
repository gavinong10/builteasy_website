<?php
class dsWidgets_Service_Base {
    static $widgets_api_stub = 'http://api-b.idx.diversesolutions.com/api/';
    static $widgets_admin_api_stub = 'http://api-b.idx.diversesolutions.com/api/';
    static $widgets_images_stub = 'http://widgets.diverse-cdn.com/Content/Images/widgets';
    static $widgets_cdn = 'http://widgets.diverse-cdn.com';
    //static $widgets_images_stub = '/wp-content/mu-plugins/dsidxwidgets/images';
    static function get_random_string($valid_chars, $length)
    {
        $random_string = "";
        $num_valid_chars = strlen($valid_chars);
        for ($i = 0; $i < $length; $i++)
        {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick-1];
            $random_string .= $random_char;
        }
        return $random_string;
    }

    static function getCapabilities() {
        $capabilities = dsSearchAgent_ApiRequest::FetchData('MlsCapabilities');
        if (isset($capabilities['response']['code'])) {
            switch($capabilities['response']['code']){
                case 200:
                    return json_decode($capabilities['body']);
                    break;
                default:
                    return $capabilities['response']['code'];
            }   
        } else {
            return false;
        }
    }

    static function getWidgetErrorMsg($before='', $after='') {
        $capabilities = self::getCapabilities();
        if(!$capabilities || $capabilities == 500){
            return $before.'<p class="dsidx-error">'.DSIDXPRESS_IDX_ERROR_MESSAGE.'</p>'.$after;
        }
        if($capabilities == 403){
            return $before.'<p class="dsidx-error">'.DSIDXPRESS_INACTIVE_ACCOUNT_MESSAGE.'</p>'.$after;
        }
        return false;
    }
}
?>