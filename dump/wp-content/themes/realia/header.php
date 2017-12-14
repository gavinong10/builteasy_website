<?php


$color_class = 'blue';

if(isset($_GET['action'])) {
  if($_GET['action'] == 'register') {
    aviators_flash_add_message('AVIATORS_FLASH_SUCCESS', __('Registration complete. Please check your e-mail.'));
  }
}

if (get_theme_mod('general_variant') != '') {
    $general_variant = get_theme_mod('general_variant');

    $name = explode('-', $general_variant);
    $classes = explode('.', end($name));

    if (!empty($classes[0])) {
        $color_class = $classes[0];
    }
}

$locations = get_nav_menu_locations();
$main_menu_settings = array(
    'theme_location' => 'main',
    'menu_class' => 'nav',
    'echo' => FALSE
);

if (!empty($locations['main']) && $locations['main'] == 0) {
    $main_menu_settings['menu'] = 'Main';
}


$anonymous_menu_settings = array(
    'theme_location' => 'anonymous',
    'menu_class' => 'nav nav-pills',
    'echo' => FALSE
);

if (!empty($locations['anonymous']) && $locations['anonymous'] == 0) {
    $anonymous_menu_settings['menu'] = 'Anonymous';
}

$authenticated_menu_settings = array(
    'theme_location' => 'authenticated',
    'menu_class' => 'nav nav-pills',
    'echo' => FALSE
);

if (!empty($locations['authenticated']) && $locations['authenticated'] == 0) {
    $authenticated_menu_settings['menu'] = 'Authenticated';
}

require_once get_template_directory() . '/aviators/plugins/properties/enquire.php';
do_action('aviators_before_page_render');

echo View::render('helpers/header.twig', array(
    'color_class' => $color_class,
    'main_menu' => wp_nav_menu($main_menu_settings),
    'anonymous_menu' => wp_nav_menu($anonymous_menu_settings),
    'authenticated_menu' => wp_nav_menu($authenticated_menu_settings),
));