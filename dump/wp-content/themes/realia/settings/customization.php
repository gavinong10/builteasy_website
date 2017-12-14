<?php

function aviators_realia_customize($wp_customize) {
    aviators_realia_customize_general($wp_customize);
    aviators_realia_customize_topbar($wp_customize);
    aviators_realia_customize_header($wp_customize);
}

function aviators_realia_customize_general($wp_customize) {
    $wp_customize->add_section('realia_general', array('title' => __('General', 'aviators'), 'priority' => 0));

    $wp_customize->add_setting('general_palette_is_hidden');
    $wp_customize->add_control('general_palette_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide palette', 'aviators'), 'section' => 'realia_general', 'settings' => 'general_palette_is_hidden', 'priority' => 0
    ));

    // Layout
    $wp_customize->add_setting('general_layout', array('default' => 'layout-wide'));
    $wp_customize->add_control('general_layout', array(
    'type' => 'select', 'label' => __('Layout', 'aviators'), 'section' => 'realia_general', 'settings' => 'general_layout', 'priority' => 0,
    'choices' => array(
        'layout-wide' => __('Wide', 'aviators'),
        'layout-boxed' => __('Boxed', 'aviators'),
    )));

    // Variant
    $wp_customize->add_setting('general_variant', array('default' => 'realia-blue.css'));
    $wp_customize->add_control('general_variant', array(
    'type' => 'select', 'label' => __('Variant', 'aviators'), 'section' => 'realia_general', 'settings' => 'general_variant', 'priority' => 0,
    'choices' => array(
        'realia-blue.css' => __('Blue', 'aviators'),
        'realia-gray-blue.css' => __('Gray Blue', 'aviators'),
        'realia-brown.css' => __('Brown', 'aviators'),
        'realia-gray-brown.css' => __('Gray Brown', 'aviators'),
        'realia-brown-dark.css' => __('Brown Dark', 'aviators'),
        'realia-gray-brown-dark.css' => __('Gray Brown Dark', 'aviators'),
        'realia-red.css' => __('Red', 'aviators'),
        'realia-gray-red.css' => __('Gray Red', 'aviators'),
        'realia-magenta.css' => __('Magenta', 'aviators'),
        'realia-gray-magenta.css' => __('Gray Magenta', 'aviators'),
        'realia-orange.css' => __('Orange', 'aviators'),
        'realia-gray-orange.css' => __('Gray Orange', 'aviators'),
        'realia-violet.css' => __('Violet', 'aviators'),
        'realia-gray-violet.css' => __('Gray Violet', 'aviators'),
        'realia-turquiose.css' => __('Turquiose', 'aviators'),
        'realia-gray-turquiose.css' => __('Gray Turquiose', 'aviators'),
        'realia-green.css' => __('Green', 'aviators'),
        'realia-gray-green.css' => __('Gray Green', 'aviators'),
        'realia-green-light.css' => __('Green Light', 'aviators'),
        'realia-gray-green-light.css' => __('Gray Green Light', 'aviators'),
    )));

    // Pattern
    $wp_customize->add_setting('general_pattern', array('default' => 'pattern-none'));
    $wp_customize->add_control('general_pattern', array('type' => 'select', 'label' => __('Pattern', 'aviators'), 'section' => 'realia_general', 'settings' => 'general_pattern', 'priority' => 0,
    'choices' => array(
        'pattern-cloth-alike' => __('Cloth Alike', 'aviators'),
        'pattern-corrugation' => __('Corrugation', 'aviators'),
        'pattern-diagonal-noise' => __('Diagonal Noise', 'aviators'),
        'pattern-dust' => __('Dust', 'aviators'),
        'pattern-fabric-plaid' => __('Fabric Plaid', 'aviators'),
        'pattern-farmer' => __('Farmer', 'aviators'),
        'pattern-grid-noise' => __('Grid Noise', 'aviators'),
        'pattern-lghtmesh' => __('Lghtmesh', 'aviators'),
        'pattern-pw-maze-white' => __('PW Maze White', 'aviators'),
        'pattern-none' => __('None', 'aviators'),
    )));


}

function aviators_realia_customize_topbar($wp_customize) {
    $wp_customize->add_section('realia_topbar', array('title' => __('Top Bar', 'aviators'), 'priority' => 1));

    // Breadcrumb
    $wp_customize->add_setting('topbar_breadcrumb_is_hidden');
    $wp_customize->add_control('topbar_breadcrumb_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide breadcrumb', 'aviators'), 'section' => 'realia_topbar', 'settings' => 'topbar_breadcrumb_is_hidden', 'priority' => 1
    ));

    // User links
    $wp_customize->add_setting('topbar_user_links_is_hidden');
    $wp_customize->add_control('topbar_user_links_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide user links', 'aviators'), 'section' => 'realia_topbar', 'settings' => 'topbar_user_links_is_hidden', 'priority' => 2
    ));
}

function aviators_realia_customize_header($wp_customize) {
    $wp_customize->add_section('realia_header', array('title' => __('Header', 'aviators'), 'priority' => 2));

    // Title
    $wp_customize->add_setting('header_position_is_fixed');
    $wp_customize->add_control('header_position_is_fixed', array(
        'type' => 'checkbox', 'label' => __('Fixed', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_position_is_fixed', 'priority' => 2
    ));

    // Variant
    $wp_customize->add_setting('header_variant', array('default' => 'header-normal'));
    $wp_customize->add_control('header_variant', array(
        'type' => 'select', 'label' => __('Variant', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_variant', 'priority' => 0,
        'choices' => array(
            'header-light' => __('Light', 'aviators'),
            'header-normal' => __('Normal', 'aviators'),
            'header-dark' => __('Dark', 'aviators'),
        )
    ));

    // Logo
    $wp_customize->add_setting('header_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_logo', array(
        'label' => __('Logo', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_logo', 'priority' => 1
    )));

    // Title
    $wp_customize->add_setting('header_title_is_hidden');
    $wp_customize->add_control('header_title_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide title', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_title_is_hidden', 'priority' => 2
    ));

    // Description
    $wp_customize->add_setting('header_description_is_hidden');
    $wp_customize->add_control('header_description_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide description', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_description_is_hidden', 'priority' => 3
    ));

    // Email
    $wp_customize->add_setting('header_email_is_hidden');
    $wp_customize->add_control('header_email_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide email', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_email_is_hidden', 'priority' => 4,
    ));

    $wp_customize->add_setting('header_email', array('default' => 'info@byaviators.com', 'type' => 'option'));
    $wp_customize->add_control('header_email', array(
        'label' => __('Email value', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_email', 'priority' => 5,
    ));

    // Phone
    $wp_customize->add_setting('header_phone_is_hidden');
    $wp_customize->add_control('header_phone_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide phone', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_phone_is_hidden', 'priority' => 6,
    ));

    $wp_customize->add_setting('header_phone', array('default' => '333-444-555', 'type' => 'option'));
    $wp_customize->add_control('header_phone', array(
        'label' => __('Phone value', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_phone', 'priority' => 7,
    ));

    // Call to action: hidden
    $wp_customize->add_setting('header_call_to_action_is_hidden');
    $wp_customize->add_control('header_call_to_action_is_hidden', array(
        'type' => 'checkbox', 'label' => __('Hide call to action button', 'aviators'), 'section' => 'realia_header', 'settings' => 'header_call_to_action_is_hidden', 'priority' => 7,
    ));
    aviators_property_submission_button_options($wp_customize);

}

add_action('customize_register', 'aviators_realia_customize');
