<?php

/**
 * Simple Widget Logic Export Page
 */
function aviators_utils_wl_export_page() {
    $wl = new AviatorsUtilsWLExport();
    $wl->render();
}

/**
 * Simple Widget Logic Import Page
 */
function aviators_utils_wl_import_page() {
    $wl = new AviatorsUtilsWLImport();
    $wl->render();
}

/**
 * Class AviatorsUtilsWLImport
 * Widget Logic Import Class
 */
class AviatorsUtilsWLImport {

    public function __construct() {
    }

    public function render() {
    }

    public function form() {
    }

    public function validate($form, $values) {
        // if file is not there...
    }

    public function submit($form, $values) {
        $json_string = file_get_contents($_FILES['file']['tmp_name']);
        $json_array = json_decode($json_string, true);

        $this->import($json_array);
    }

    public function import($json_array) {
        update_option('widget_logic', $json_array);
    }
}

/**
 * Class AviatorsUtilsWLExport
 * Widget Logic Export Class
 */
class AviatorsUtilsWLExport {

    public function __construct() {

    }

    public function render() {
        $link = home_url() . '/aviators-utils/widget-logic';
        echo "<a href=\"$link\">Export</a>";

    }

    public function export() {
        $this->downloadHeaders();
        $widget_logic = get_option('widget_logic');
        $json_string = json_encode($widget_logic);
        echo $json_string;
    }

    public function downloadHeaders() {
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename=widget_logic.json");
        header("Content-Transfer-Encoding: binary");
    }
}