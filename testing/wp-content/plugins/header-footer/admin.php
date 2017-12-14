<?php

delete_option('hefo_version');

add_action('admin_init', 'hefo_admin_init');

function hefo_admin_init() {
    global $hefo_options;
    if (isset($_GET['page']) && strpos($_GET['page'], 'header-footer/') === 0) {
        header('X-XSS-Protection: 0');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }

    if (isset($hefo_options['page_add_tags'])) {
        register_taxonomy_for_object_type('post_tag', 'page');
    }

    if (isset($hefo_options['page_add_categories'])) {
        register_taxonomy_for_object_type('category', 'page');
    }
}

add_action('admin_head', 'hefo_admin_head');

function hefo_admin_head() {
//    if (isset($_GET['page']) && strpos($_GET['page'], 'header-footer/') === 0) {
//        echo '<link type="text/css" rel="stylesheet" href="' .
//        get_option('siteurl') . '/wp-content/plugins/header-footer/admin.css"/>';
//    }
}

add_action('admin_menu', 'hefo_admin_menu');

function hefo_admin_menu() {
    add_options_page('Header and Footer', 'Header and Footer', 'manage_options', 'header-footer/options.php');
}

add_action('add_meta_boxes', 'hefo_add_meta_boxes');

add_action('save_post', 'hefo_save_post');

function hefo_add_meta_boxes() {
    foreach (array('post', 'page') as $screen) {
        add_meta_box(
                'hefo', __('Header and Footer', 'header-footer'), 'hefo_add_meta_boxes_callback', $screen
        );
    }
}

function hefo_add_meta_boxes_callback($post) {

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'hefo');

    // The actual fields for data entry
    // Use get_post_meta to retrieve an existing value from the database and use the value for the form
    $before = get_post_meta($post->ID, 'hefo_before', true);
    $after = get_post_meta($post->ID, 'hefo_after', true);
    echo '<label>';
    echo '<input type="checkbox" id="hefo_before" name="hefo_before" ' . (empty($before) ? "" : "checked") . '> ';
    _e("Disable top injection", 'header-footer');
    echo '</label> ';
    echo '<br>';
    echo '<label>';
    echo '<input type="checkbox" id="hefo_after" name="hefo_after" ' . (empty($after) ? "" : "checked") . '> ';
    _e("Disable bottom injection", 'header-footer');
    echo '</label> ';
}

function hefo_save_post($post_id) {

    // First we need to check if the current user is authorised to do this action.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return;
    }

    // Secondly we need to check if the user intended to change this value.
    if (!isset($_POST['hefo']) || !wp_verify_nonce($_POST['hefo'], plugin_basename(__FILE__)))
        return;

    update_post_meta($post_id, 'hefo_before', isset($_REQUEST['hefo_before']) ? 1 : 0);
    update_post_meta($post_id, 'hefo_after', isset($_REQUEST['hefo_after']) ? 1 : 0);
}
