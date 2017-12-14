<?php

/**
 * Class AviatorsLauncherHydraImport
 * Hydraforms import for launcher
 */
class AviatorsLauncherHydraImport implements AviatorsLauncherImporter {

    public function process($filepath) {
        require_once ABSPATH . '/wp-content/plugins/hydraforms/hydraforms.php';

        $importer = new HydraImportAdmin();
        $xmlString = file_get_contents($filepath);
        $messages = $importer->import($xmlString);

        return $messages;
    }

    public function report($messages) {
        include AVIATORS_LAUNCHER_PATH . '/template/messages.php';
    }
}