<?php

class AviatorsLauncherWidgetSettingsImport implements AviatorsLauncherImporter {

    public function process($filepath) {
        require_once ABSPATH . '/wp-content/plugins/widget-settings-importexport/widget-data.php';
        $json = file_get_contents($filepath);
        $json_array = json_decode($json, TRUE);


//        $widgetData = new Widget_Data();
//        $widgetData->parse_import_data($json_array);
        list($sidebars, $widgets) = ($json_array);

        update_option('sidebars_widgets', $sidebars);
        foreach($widgets as $widgetID => $widgetOptions) {
            update_option('widget_' . $widgetID, $widgetOptions);
        }


        $messages = array('success');
        return $messages;
    }

    public function report($messages) {
        include AVIATORS_LAUNCHER_PATH . '/template/messages.php';
    }
}