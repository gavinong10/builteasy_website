<?php
/**
 * Config
 *
 * @package WordPress
 * @subpackage seed_ucp
 * @since 0.1.0
 */

/**
 * Config Settings
 */
function seed_ucp_get_options(){

    /**
     * Create new menus
     */

    $seed_ucp_options[ ] = array(
        "type" => "menu",
        "menu_type" => "add_options_page",
        "page_name" => __( "Under Construction", 'under-construction-wp' ),
        "menu_slug" => "seed_ucp",
        "layout" => "2-col"
    );

    /**
     * Settings Tab
     */
    $seed_ucp_options[ ] = array(
        "type" => "tab",
        "id" => "seed_ucp_setting",
        "label" => __( "Content", 'under-construction-wp' ),
    );

    $seed_ucp_options[ ] = array(
        "type" => "setting",
        "id" => "seed_ucp_settings_content",
    );

    $seed_ucp_options[ ] = array(
        "type" => "section",
        "id" => "seed_ucp_section_general",
        "label" => __( "General", 'under-construction-wp' ),
    );

    $seed_ucp_options[ ] = array(
        "type" => "radio",
        "id" => "status",
        "label" => __( "Status", 'under-construction-wp' ),
        "option_values" => array(
            '0' => __( 'Disabled', 'under-construction-wp' ),
            '1' => __( 'Enable Under Construction Mode', 'under-construction-wp' ),
            '2' => __( 'Enable Maintenance Mode', 'under-construction-wp' )
        ),
        "desc" => __( "When you are logged in you'll see your normal website. Logged out visitors will see the Under Construction or Maintenance page. Under Construction Mode will be available to search engines if your site is not private. Maintenance Mode will notify search engines that the site is unavailable.", 'under-construction-wp' ),
        "default_value" => "0"
    );


    $csp4_maintenance_file = WP_CONTENT_DIR."/maintenance.php";
    if (file_exists($csp4_maintenance_file)) {
    $seed_ucp_options[ ] = array(
        "type" => "checkbox",
        "id" => "enable_maintenance_php",
        "label" => __( "Use maintenance.php", 'under-construction-wp' ),
        "desc" => __('maintenance.php detected, would you like to use this for your maintenance page?', 'under-construction-wp'),
        "option_values" => array(
             'name' => __( 'Yes', 'under-construction-wp' ),
             //'required' => __( 'Make Name Required', 'seed_ucp' ),
        )
    );
    }

    // Page Setttings
    $seed_ucp_options[ ] = array(
        "type" => "section",
        "id" => "seed_ucp_section_page_settings",
        "label" => __( "Page Settings", 'under-construction-wp' )
    );


    $seed_ucp_options[ ] = array(
        "type" => "wpeditor",
        "id" => "description",
        "label" => __( "Message", 'under-construction-wp' ),
        "desc" => __( 'Tell the visitor what to expect from your site. Looking for Shortcode Support? Upgrade to the <a href="http://www.seedprod.com/ultimate-coming-soon-page-vs-coming-soon-pro/?utm_source=under-construction-plugin&amp;utm_medium=banner&amp;utm_campaign=under-construction-link-in-plugin" target="_blank">Pro Version</a>!', 'under-construction-wp' ),
        "class" => "large-text"
    );

    $seed_ucp_options[ ] = array(
        "type" => "upload",
        "id" => "bg_image",
        "label" => __( "Optional Background Image", 'under-construction-wp' ),
    );

    $seed_ucp_options[ ] = array(
        "type" => "checkbox",
        "id" => "disable_overlay",
        "label" => __( "Disable Overlay", 'under-construction-wp' ),
        "desc" => __("Disable overlay effect.", 'under-construction-wp'),
        "option_values" => array(
             '1' => __( 'Disable', 'under-construction-wp' ),
        ),
        "default" => "1",
    );

    $seed_ucp_options[ ] = array(
        "type" => "checkbox",
        "id" => "disable_default_excluded_urls",
        "label" => __( "Disable Default Excluded URLs", 'under-construction-wp' ),
        "desc" => __("By default we exclude urls with the terms: login, admin, dashboard and account to prevent lockouts. Check to disable.", 'under-construction-wp'),
        "option_values" => array(
             '1' => __( 'Disable', 'under-construction-wp' ),
        ),
        "default" => "1",
    );

     $seed_ucp_options[ ] = array( "type" => "radio",
        "id" => "footer_credit",
        "label" => __("Powered By SeedProd", 'under-construction-wp'),
        "option_values" => array('0'=>__('Nope - Got No Love', 'seed_ucp'),'1'=>__('Yep - I Love You Man', 'under-construction-wp')),
        "desc" => __("Can we show a <strong>cool stylish</strong> footer credit at the bottom the page.", 'under-construction-wp'),
        "default_value" => "0",
    );
    // Page Setttings
    $seed_ucp_options[ ] = array(
        "type" => "section",
        "id" => "seed_ucp_section_custom_settings",
        "label" => __( "Custom HTML", 'under-construction-wp' )
    );

    $seed_ucp_options[ ] = array(
        "type" => "textarea",
        "id" => "custom_html",
        "class" => "large-text",
        "label" => __( "HTML", 'under-construction-wp' ),
        "desc" => __("This will replace the entire page's output with your custom html. Leave empty if you are not using custom html.", 'under-construction-wp'),
    );


 




    return $seed_ucp_options;

}
