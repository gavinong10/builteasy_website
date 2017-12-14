<?php

define ('AVIATORS_LAUNCHER_URI', get_stylesheet_directory_uri() . '/launcher');
define ('AVIATORS_LAUNCHER_PATH', dirname(__FILE__));

add_action('init', 'aviators_launcher_rewrite_rules');

function aviators_launcher_rewrite_rules() {
    add_rewrite_rule('^aviators-launcher/(.+)/?$', 'index.php?aviators-launcher=true&step=$matches[1]', 'top');
    add_rewrite_rule('^aviators-launcher-report/(.+)/?$', 'index.php?aviators-launcher-report=true&step=$matches[1]', 'top');
    flush_rewrite_rules();
}

add_filter('query_vars', 'aviators_launcher_add_query_vars');
function aviators_launcher_add_query_vars($vars) {
    $vars[] = 'aviators-launcher';
    $vars[] = 'aviators-launcher-report';
    $vars[] = 'step';
    return $vars;
}

add_action('template_redirect', 'aviators_launcher_catch_template');
function aviators_launcher_catch_template() {

    if (get_query_var('aviators-launcher') && get_query_var('step')) {
        $launcher = new AviatorsLauncher();
        $launcher->processStep(get_query_var('step'));
        die;
    }

    if (get_query_var('aviators-launcher-report') && get_query_var('step')) {
        $launcher = new AviatorsLauncher();
        $launcher->report(get_query_var('step'));
        die;
    }
}

function aviators_launcher_steps() {
    $steps = array();
    $steps = apply_filters('aviators_launcher_steps', $steps);

    return $steps;
}

/**
 * @param $definitions
 * @return mixed
 */

function aviators_launcher_define_importers($definitions) {

    $definitions['hydra'] = array(
        'title' => __('Hydra', 'aviators'),
        'class' => 'AviatorsLauncherHydraImport',
        'file' => dirname(__FILE__) . '/importers/hydra-import.php',
    );

    $definitions['content'] = array(
        'title' => __('Demo Content', 'aviators'),
        'class' => 'AviatorsLauncherContentImport',
        'file' => dirname(__FILE__) . '/importers/content-import.php',
    );

    $definitions['widget-settings'] = array(
        'title' => __('Widget Data', 'aviators'),
        'class' => 'AviatorsLauncherWidgetSettingsImport',
        'file' => dirname(__FILE__) . '/importers/widget-import.php',
    );

    $definitions['widget-logic'] = array(
        'title' => __('Widget Logic', 'aviators'),
        'class' => 'AviatorsLauncherWidgetLogicImport',
        'file' => dirname(__FILE__) . '/importers/logic-import.php',
    );

    $definitions['theme-options'] = array(
        'title' => __('Theme Options', 'aviators'),
        'class' => 'AviatorsLauncherThemeOptionsImport',
        'file' => dirname(__FILE__) . '/importers/theme-options-import.php',
    );

    return $definitions;
}

add_filter('aviators_launcher_define_importers', 'aviators_launcher_define_importers');

new AviatorsLauncher();
class AviatorsLauncher {

    function __construct() {
        add_action('admin_menu', array($this, 'registerMenu'));
    }

    public function registerMenu($defaultItem) {
        add_menu_page(__('One-Click Install', 'aviators'), __('One-Click Install', 'aviators'), AVIATORS_SETTINGS_CAPABALITY, 'launcher', array(
            $this,
            'page',
        ), AVIATORS_LAUNCHER_URI . '/assets/img/play.png', 25);
    }

    function page() {
        $steps = aviators_launcher_steps();
        $step_ids = implode(',', array_keys($steps));
        include 'template/launcher.php';
    }

    function processStep($step) {
        $stepsDefinitions = aviators_launcher_steps();
        $stepDefinition = $stepsDefinitions[$step];

        $importerDefinitions = $this->definitions();
        $importerDefinition = $importerDefinitions[$stepDefinition['importer']];

        // require importer file
        require_once($importerDefinition['file']);
        // pass file path to import
        $class = new ReflectionClass($importerDefinition['class']);
        $instance = $class->newInstanceArgs(array());

        $messages = $instance->process($stepDefinition['file']);
        $_SESSION['aviators-launcher-report'][$step] = $messages;
    }

    function report($step) {
        $stepsDefinitions = aviators_launcher_steps();
        $stepDefinition = $stepsDefinitions[$step];

        $importerDefinitions = $this->definitions();
        $importerDefinition = $importerDefinitions[$stepDefinition['importer']];

        // require importer file
        require_once($importerDefinition['file']);
        // pass file path to import
        $class = new ReflectionClass($importerDefinition['class']);
        $instance = $class->newInstanceArgs(array());

        $messages = $_SESSION['aviators-launcher-report'][$step];
        print $instance->report($messages);

        unset($_SESSION['aviators-launcher-report'][$step]);
    }

    function definitions() {
        $definitions = array();
        $definitions = apply_filters('aviators_launcher_define_importers', $definitions);
        return $definitions;
    }
}



interface AviatorsLauncherImporter {
    function process($filepath);
    function report($data);
}

