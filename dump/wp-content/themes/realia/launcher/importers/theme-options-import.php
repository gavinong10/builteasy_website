<?php

class AviatorsLauncherThemeOptionsImport implements  AviatorsLauncherImporter {

    public function process($filepath) {
        $jsonString = file_get_contents($filepath);
        $jsonArray = json_decode($jsonString, true);

        $import = new AviatorsUtilsTOImport();
        $messages = $import->import($jsonArray);
        return  $messages;
    }

    public function report($messages) {
        include AVIATORS_LAUNCHER_PATH . '/template/messages.php';
    }
}