<?php

require_once get_template_directory() . '/aviators/plugins/properties/utils.php';

require_once 'widgets/map_simple_properties.php';
require_once 'widgets/map_properties.php';
require_once 'widgets/featured_properties.php';
require_once 'widgets/featured_properties_large.php';
require_once 'widgets/most_recent_properties.php';
require_once 'widgets/reduced_properties.php';
require_once 'widgets/carousel_properties.php';
require_once 'widgets/property_filter.php';
require_once 'widgets/enquire_properties.php';
require_once 'widgets/slider_properties.php';
require_once 'widgets/general_properties.php';
require_once 'widgets/revolution_slider.php';


/**
 * Meta options for custom post type
 */
$property_metabox = new WPAlchemy_MetaBox(array(
    'id' => '_property_meta',
    'title' => __('Property Options', 'aviators'),
    'template' => AVIATORS_DIR . '/plugins/properties/meta.php',
    'types' => array('property'),
    'prefix' => '_property_',
    'mode' => WPALCHEMY_MODE_EXTRACT,
));


/**
 * Register google map script for obtaining GPS locations from address
 */
function aviators_properties_load_styles() {
    if (is_admin()) {
        wp_register_script('gmap', 'http://maps.googleapis.com/maps/api/js?v=3&amp;sensor=true&libraries=places');
        wp_enqueue_script('gmap');
        wp_enqueue_script(
            'geolocation',
            get_template_directory_uri() . '/aviators/plugins/properties/assets/js/geolocation.js'
        );
    }
}

add_action('admin_head', 'aviators_properties_load_styles');

/**
 * Custom post type
 */
function aviators_properties_create_post_type() {
    $labels = array(
        'name' => __('Properties', 'aviators'),
        'singular_name' => __('Property', 'aviators'),
        'add_new' => __('Add New', 'aviators'),
        'add_new_item' => __('Add New Property', 'aviators'),
        'edit_item' => __('Edit Property', 'aviators'),
        'new_item' => __('New Property', 'aviators'),
        'all_items' => __('All Properties', 'aviators'),
        'view_item' => __('View Property', 'aviators'),
        'search_items' => __('Search Property', 'aviators'),
        'not_found' => __('No properties found', 'aviators'),
        'not_found_in_trash' => __('No properties found in Trash', 'aviators'),
        'parent_item_colon' => '',
        'menu_name' => __('Properties', 'aviators'),
    );

    register_post_type(
        'property',
        array(
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'author'),
            'public' => TRUE,
            'has_archive' => TRUE,
            'rewrite' => array('slug' => __('properties', 'aviators')),
            'menu_position' => 32,
            'categories' => array('property_types'),
            'menu_icon' => get_template_directory_uri() . '/aviators/plugins/properties/assets/img/properties.png',
        )
    );
}

add_action('init', 'aviators_properties_create_post_type');

/**
 * Custom taxonomies
 */
function aviators_properties_create_taxonomies() {
  
    $property_contracts_labels = array(
        'name' => __('Contract Types', 'aviators'),
        'singular_name' => __('Contract Type', 'aviators'),
        'search_items' => __('Search Contract Types', 'aviators'),
        'all_items' => __('All Contract Types', 'aviators'),
        'parent_item' => __('Parent Contract Type', 'aviators'),
        'parent_item_colon' => __('Parent Contract Type:', 'aviators'),
        'edit_item' => __('Edit Contract Type', 'aviators'),
        'update_item' => __('Update Contract Type', 'aviators'),
        'add_new_item' => __('Add New Contract Type', 'aviators'),
        'new_item_name' => __('New Contract Type', 'aviators'),
        'menu_name' => __('Contract Type', 'aviators'),
    );

    register_taxonomy(
        'property_contracts',
        'property',
        array(
            'labels' => $property_contracts_labels,
            'hierarchical' => TRUE,
            'query_var' => 'property_contract',
            'rewrite' => array('slug' => __('property-contract', 'aviators')),
            'public' => TRUE,
            'show_ui' => TRUE,
        )
    );

    $property_types_labels = array(
        'name' => __('Property Types', 'aviators'),
        'singular_name' => __('Property Type', 'aviators'),
        'search_items' => __('Search Property Types', 'aviators'),
        'all_items' => __('All Property Types', 'aviators'),
        'parent_item' => __('Parent Property Type', 'aviators'),
        'parent_item_colon' => __('Parent Property Type:', 'aviators'),
        'edit_item' => __('Edit Property Type', 'aviators'),
        'update_item' => __('Update Property Type', 'aviators'),
        'add_new_item' => __('Add New Property Type', 'aviators'),
        'new_item_name' => __('New Property Type', 'aviators'),
        'menu_name' => __('Property Type', 'aviators'),
    );

    register_taxonomy(
        'property_types',
        'property',
        array(
            'labels' => $property_types_labels,
            'hierarchical' => TRUE,
            'query_var' => 'property_type',
            'rewrite' => array('slug' => __('property-type', 'aviators')),
            'public' => TRUE,
            'show_ui' => TRUE,
        )
    );

    $property_locations_labels = array(
        'name' => __('Locations', 'aviators'),
        'singular_name' => __('Location', 'aviators'),
        'search_items' => __('Search Location', 'aviators'),
        'all_items' => __('All Locations', 'aviators'),
        'parent_item' => __('Parent Location', 'aviators'),
        'parent_item_colon' => __('Parent Location:', 'aviators'),
        'edit_item' => __('Edit Location', 'aviators'),
        'update_item' => __('Update Location', 'aviators'),
        'add_new_item' => __('Add New Location', 'aviators'),
        'new_item_name' => __('New Location', 'aviators'),
        'menu_name' => __('Location', 'aviators'),
    );
    register_taxonomy(
        'locations',
        'property',
        array(
            'labels' => $property_locations_labels,
            'hierarchical' => TRUE,
            'query_var' => 'location',
            'rewrite' => array('slug' => __('location', 'aviators')),
            'public' => TRUE,
            'show_ui' => TRUE,
            'show_admin_column' => TRUE,
        )
    );

    $amenities_labels = array(
        'name' => __('Amenities', 'aviators'),
        'singular_name' => __('Amenity', 'aviators'),
        'search_items' => __('Search Amenity', 'aviators'),
        'all_items' => __('All Amenities', 'aviators'),
        'parent_item' => __('Parent Amenity', 'aviators'),
        'parent_item_colon' => __('Parent Amenity:', 'aviators'),
        'edit_item' => __('Edit Amenity', 'aviators'),
        'update_item' => __('Update Amenity', 'aviators'),
        'add_new_item' => __('Add New Amenity', 'aviators'),
        'new_item_name' => __('New Amenity', 'aviators'),
        'menu_name' => __('Amenity', 'aviators'),
    );

    register_taxonomy(
        'amenities',
        'property',
        array(
            'labels' => $amenities_labels,
            'hierarchical' => TRUE,
            'query_var' => 'amenity',
            'rewrite' => array('slug' => __('amenity', 'aviators')),
            'public' => TRUE,
            'show_ui' => TRUE,
            'show_admin_column' => TRUE,
        )
    );
}

add_action('init', 'aviators_properties_create_taxonomies', 0);


/**
 * Custom columns
 */
function aviators_properties_custom_post_columns() {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title', 'aviators'),
        'thumbnail' => __('Thumbnail', 'aviators'),
        'optional_title' => __('Optional Title', 'aviators'),
        'price' => __('Price', 'aviators'),
        'location' => __('Location', 'aviators'),
        'property_types' => __('Property Type', 'aviators'),
        'gps' => __('GPS', 'aviators'),
        'contract_type' => __('Contract Type', 'aviators'),
        'featured' => __('Featured', 'aviators'),
        'reduced' => __('Reduced', 'aviators'),
        'agents' => __('Agents', 'aviators'),
        'author' => __('Author', 'aviators'),
    );
}

add_filter('manage_edit-property_columns', 'aviators_properties_custom_post_columns');


function aviators_properties_custom_post_manage($column, $post_id) {
    global $post;

    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, 'admin-thumb');
            }
            else {
                echo '<img src="' . get_template_directory_uri(
                    ) . '/assets/img/property-tmp-small.png' . '" width="80">';
            }
            break;
        case 'optional_title':
            $title = get_post_meta($post_id, '_property_title', TRUE);
            if (!empty($title)) {
                echo $title;
            }
            else {
                echo '<span style="color: red">' . __('Undefined', 'aviators') . '</span>';
            }
            break;
        case 'price':
            $price = get_post_meta($post_id, '_property_price', TRUE);
            if (empty($price)) {
                echo '<span style="color: red">' . __('Undefined', 'aviators') . '</span>';
            }
            else {
                echo $price;
            }
            break;
        case 'location':
            if (!is_array(get_the_terms($post, 'locations'))) {
                echo '<span style="color: red">' . __('Undefined', 'aviators') . '</span>';
            }
            else {
                $terms = get_the_terms($post, 'locations');
                $location = array_shift($terms);
                echo '<a href="?post_type=property&location=' . $location->slug . '">' . $location->name . '</a>';
            }
            break;
        case 'property_types':
            if (!is_array(get_the_terms($post, 'locations'))) {
                echo '<span style="color: red">' . __('Undefined', 'aviators') . '</span>';
            }
            else {
                $terms = get_the_terms($post, 'property_types');
                $property_type = array_shift($terms);
                echo '<a href="?post_type=property&property_type=' . $property_type->slug . '">' . $property_type->name . '</a>';
            }
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_property_featured');
            if ($featured) {
                echo '<span style="color:green;">' . __('On', 'aviators') . '</span>';
            }
            else {
                echo '<span style="color:red;">' . __('Off', 'aviators') . '</span>';
            }
            break;
        case 'gps':
            $longitude = get_post_meta($post_id, '_property_longitude', TRUE);
            $latitude = get_post_meta($post_id, '_property_latitude', TRUE);
            if (!$longitude || !$latitude) {
                echo '<span style="color: red">' . __('Missing', 'aviators') . '</span>';
            }
            else {
                echo '[' . $latitude . ', ' . $longitude . ']';
            }
            break;
        case 'reduced':
            $reduced = get_post_meta($post_id, '_property_reduced');
            if ($reduced) {
                echo '<span style="color:green;">' . __('On', 'aviators') . '</span>';
            }
            else {
                echo '<span style="color:red;">' . __('Off', 'aviators') . '</span>';
            }
            break;
        case 'contract_type':
            if (!is_array(get_the_terms($post, 'property_contracts'))) {
                echo '<span style="color: red">' . __('Undefined', 'aviators') . '</span>';
            }
            else {
                $terms = get_the_terms($post, 'property_contracts');
                $contract_type = array_shift($terms);
                echo '<a href="?post_type=property&property_contract=' . $contract_type->slug . '">' . $contract_type->name . '</a>';
            }
            break;
        case 'agents':
            $agents = get_post_meta($post_id, '_property_agents', TRUE);
            if (!is_array($agents)) {
                echo '<span style="color:red;">' . __('Not assigned', 'aviators') . '</span>';
            }
            else {
                foreach ($agents as $agent_id) {
                    echo get_post($agent_id)->post_title . '<br>';
                }
            }
            break;
    }
}

add_action('manage_property_posts_custom_column', 'aviators_properties_custom_post_manage', 10, 2);

/**
 * Change posts per page
 */
function aviators_modify_posts_per_properties_page() {
    add_filter('option_posts_per_page', 'aviators_option_posts_per_properties_page');
}

add_action('init', 'aviators_modify_posts_per_properties_page', 0);

function aviators_option_posts_per_properties_page($value) {
    if (is_post_type_archive('property') || is_tax('locations') || is_tax('amenities') || is_tax('property_types')) {
        return aviators_settings_get_value('properties', 'properties', 'per_page');
    }

    return $value;
}

function aviators_property_get_metabox() {
    return new Submission_MetaBox(array(
        'id' => '_property_meta',
        'title' => __('Property Options', 'aviators'),
        'template' => AVIATORS_DIR . '/plugins/properties/meta-submission.php',
        'types' => array('property'),
        'prefix' => '_property_',
        'mode' => WPALCHEMY_MODE_EXTRACT,
    ));
}

/**
 * Enqueue javascript frontend submission form
 */
function _aviators_property_form_enqueue_js() {
    wp_enqueue_script(
        'jquery',
        includes_url('/js/jquery/ui/jquery.js', __FILE__)
    );

    wp_enqueue_script(
        'geolocation',
        get_template_directory_uri() . '/aviators/plugins/properties/assets/js/geolocation.js'
    );

    wp_enqueue_script(
        'jquery.ui.core',
        includes_url('/js/jquery/ui/jquery.ui.core.min.js', __FILE__)
    );

    wp_enqueue_script(
        'jquery.ui.widget',
        includes_url('/js/jquery/ui/jquery.ui.widget.min.js', __FILE__)
    );

    wp_enqueue_script(
        'jquery.ui.mouse',
        includes_url('/js/jquery/ui/jquery.ui.mouse.min.js', __FILE__)
    );

    wp_enqueue_script(
        'jquery.ui.sortable',
        includes_url('/js/jquery/ui/jquery.ui.sortable.min.js', __FILE__)
    );
}

function aviators_properties_property_get_actions($post_id) {
    $gateway = aviators_settings_get_value('submission', 'common', 'payment_gateway');
    $bool = aviators_settings_get_value('properties', 'common', 'frontend_needs_submission');

    $post = get_post($post_id);
    $links[] = sprintf(
        "<a title=\"%s\" class=\"edit\" href=\"%s\">%s</a>",
        _aviators_properties_submission_create_link(array('action' => 'edit', 'id' => $post_id)),
        _aviators_properties_submission_create_link(array('action' => 'edit', 'id' => $post_id)),
        __('Edit', 'aviators')
    );

    $links[] = sprintf(
        "<a title=\"%s\" class=\"remove\" href=\"%s\">%s</a>",
        _aviators_properties_submission_create_link(array('action' => 'delete', 'id' => $post_id)),
        _aviators_properties_submission_create_link(array('action' => 'delete', 'id' => $post_id)),
        __('Delete', 'aviators')
    );

    $links[] = sprintf(
        "<a title=\"%s\" class=\"view\" href=\"%s\">%s</a>",
        get_permalink($post_id),
        get_permalink($post_id),
        __('View', 'aviators')
    );

    $publish_link = sprintf(
        "<a title=\"%s\" class=\"publish\" href=\"%s\">%s</a>'",
        _aviators_properties_submission_create_link(array('action' => 'publish', 'id' => $post_id)),
        _aviators_properties_submission_create_link(array('action' => 'publish', 'id' => $post_id)),
        __('Publish', 'aviators')
    );

    $status = 'unpublish';
    if ($bool) {
        $status = 'pending';
    }

    $unpublish_link = sprintf(
        "<a title=\"%s\" class=\"unpublish\" href=\"%s\">%s</a>'",
        _aviators_properties_submission_create_link(array('action' => $status, 'id' => $post_id)),
        _aviators_properties_submission_create_link(array('action' => $status, 'id' => $post_id)),
        __('Unpublish', 'aviators')
    );

    if ($gateway == 'paypal') {
        // paid, user can publish, unpublish, whatever
        if (aviators_properties_submission_is_paid(get_current_user_id(), $post_id)) {
            if ($post->post_status == 'publish') {
                $links[] = $unpublish_link;
            }
            else {
                $links[] = $publish_link;
            }
        }
        else {

            $links[] = aviators_submission_create_paypal_purchase($post_id)->print_buy_button();

        }
    }
    else {
        // free of charge
        // admin approval is required
        if ($bool) {
            if ($post->post_status == 'publish') {
                $links[] = $unpublish_link;
            }
        }
        else {
            // free of charge
            if ($post->post_status == 'publish') {
                $links[] = $unpublish_link;
            }
            else {
                $links[] = $publish_link;
            }
        }
    }

    return $links;
}

/**
 * Access callback for actions related to frontend submission of properties
 * @param $post_id
 * @param $user_id
 * @param $action
 * @return bool
 */
function aviators_property_action_access($post_id, $user_id, $action) {

    if (!empty($post_id)) {
        $post = get_post($post_id);
        if ($post->post_author != $user_id) {
            aviators_flash_add_message(AVIATORS_FLASH_ERROR, __('You are not post owner.', 'aviators'));
            $page = _aviators_properties_get_submission_page();
            wp_redirect(get_permalink($page));

            return FALSE;
        }

        $paypal_condition = aviators_settings_get_value('submission', 'common', 'payment_gateway') == 'paypal';
        $is_paid = aviators_properties_submission_is_paid($user_id, $post_id);

        switch ($action) {
            case 'publish':
                /**
                 * Who can publish posts
                 * 1. User who paid for it - Paypal
                 * 2. User who submitted it - No approval required
                 */
                if ($paypal_condition && !$is_paid) {
                    aviators_flash_add_message(
                        AVIATORS_FLASH_ERROR,
                        __('You need to pay for submission item in order to publish it', 'aviators')
                    );
                    $page = _aviators_properties_get_submission_page();
                    wp_redirect(get_permalink($page));

                    return FALSE;
                }

                $bool = aviators_settings_get_value('properties', 'common', 'frontend_needs_submission');
                if ($bool) {
                    aviators_flash_add_message(
                        AVIATORS_FLASH_ERROR,
                        __('Administrator approval is required to publish the post', 'aviators')
                    );
                }
                break;
            case 'unpublish':
                /**
                 * Anyone can unpublish his own item
                 */
                break;
            case 'pending':
                break;
            default:
                break;
        }
    }

    // we do have access, what a glorious success!
    return TRUE;
}


function aviators_property_form() {
    $metabox = aviators_property_get_metabox();

    return aviators_properties_form_generate($metabox);
}

function aviators_properties_submission_is_paid($user_id, $post_id) {
    $query = array(
        'post_type' => 'transaction',
        'meta_query' => array(
            array(
                'key' => '_transaction_user_id',
                'value' => $user_id,
            ),
            array(
                'key' => '_transaction_post_id',
                'value' => $post_id,
            )
        )
    );

    $wp_query = new WP_Query($query);

    return $wp_query->have_posts();
}

function aviators_properties_property_edit($id = NULL, $values) {
    $metabox = aviators_property_get_metabox();
    $payment_gateway = aviators_settings_get_value('submission', 'common', 'payment_gateway');

    if ($id) {
        $post = (array) get_post($id);
    }
    else {
        $post = array();
    }

    $post['post_type'] = 'property';
    $post['post_title'] = $values['post_title'];
    $post['post_content'] = $values['content'];

    $bool = aviators_settings_get_value('properties', 'common', 'frontend_needs_submission');
    $gateway = aviators_settings_get_value('submission', 'common', 'payment_gateway');
    // Only if creating
    // it should go to pending approval if set so
    if ($gateway == 'paypal') {
        if ($id) {
            // if not paid, post status has always be set to draft
            // otherwise we leave it as it is
            if (!aviators_properties_submission_is_paid(get_current_user_id(), $id)) {
                $post['post_status'] = 'draft';
            }
        }
    }
    else {
        // free submission might require review
        if ($bool == 'on') {
            $post['post_status'] = 'pending';
        }
        else {
            $post['post_status'] = 'draft';
        }
    }

    // If already existing
    // Don't change the status

    // save
    if ($id) {
        wp_update_post($post);
        aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Post has been successfully updated.', 'aviators'));
    }
    else {
        $id = wp_insert_post($post);
        aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Post has been successfully created.', 'aviators'));
    }

    // set post terms
    $locations = array($values['property_locations']);
    if(isset($values['property_sublocations'])) {
        $locations[] = $values['property_sublocations'];
    }

    if(isset($values['property_subsublocations'])) {
        $locations[] = $values['property_subsublocations'];
    }


    wp_set_post_terms($id, $values['property_types'], 'property_types');
    wp_set_post_terms($id, $locations, 'locations');
    wp_set_post_terms($id, $values['property_contracts'], 'property_contracts');

    if (isset($values['tax_input']) && is_array($values['tax_input']) && is_array($values['tax_input']['amenities'])) {
        $tags = '';
        foreach ($values['tax_input']['amenities'] as $amenity) {
            $tags .= $amenity . ',';
        }
        wp_set_post_terms($id, $tags, 'amenities');
    }


    // save meta
    $metabox->force_save($id);

    // get submission index
    $pages = get_posts(
        array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-submission-index.php',
        )
    );
    $submission_page = $pages[0];


    // featured image was error
    if (!empty($_FILES['featured_image']['name']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_OK) {
        if (!empty($_FILES['featured_image'])) {
            aviators_flash_add_message(AVIATORS_FLASH_ERROR, __('Image can not be uploaded', 'aviators'));
            wp_redirect(get_permalink($submission_page->ID));

            return TRUE;
        }
    }
    else {
        if (!empty($_FILES['featured_image']['name'])) {
            $thumbnail_id = get_post_thumbnail_id($id);
            if ($thumbnail_id) {
                update_post_meta($id, '_thumbnail_id', '');
            }

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attach_id = media_handle_upload('featured_image', $id);
            update_post_meta($id, '_thumbnail_id', $attach_id);
        }
    }


    $slides = get_post_meta($id, '_property_slides');
    $slides = reset($slides);

    // parse out remaining / already existing slides
    $existing_slides = array();
    foreach ($values as $key => $field) {
        if (!is_array($field)) {
            if (strpos($key, '_property_meta_slides_id_') !== FALSE) {
                $slide_id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                if (isset($values['_property_meta_slides_weight_' . $slide_id])) {
                    $existing_slides[$values['_property_meta_slides_weight_' . $slide_id]] = $field;
                }
            }
        }
    }

    $new_slides = array();
    // remove old slides
    if (is_array($existing_slides) && count($existing_slides)) {
        foreach ($slides as $slide) {
            $bool = TRUE;

            foreach ($existing_slides as $weight => $existing_slide) {
                if ($slide['imgurl'] == $existing_slide) {
                    // we are preserving this
                    $bool = FALSE;
                    $new_slides[$weight] = $slide;
                    break;
                }
            }

            if ($bool) {
                global $wpdb;
                // @TODO - HERE IS RESIZE - remove it ?
                $guid = $slide['imgurl']; // str_replace('-150x150', '', $slide['imgurl']);
                // select according to guid
                $result = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = \"$guid\""));
                // delete old slide
                if ($result->id) {
                    wp_delete_attachment($result->id);
                }
            }
        }
    }

    foreach ($_FILES as $key => $file) {
        if (strpos($key, '_property_meta_slides') !== FALSE) {
            if (!empty($_FILES[$key]['name']) && $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
                aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Image can not be uploaded', 'aviators'));
                wp_redirect(get_permalink($submission_page->ID));

                return TRUE;
            }
            else {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attach_id = media_handle_upload($key, $id);
                $weight = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                if (isset($values['_property_meta_slides_weight_' . $weight])) {
                    $result = wp_get_attachment_image_src($attach_id);
                    $new_slides[$values['_property_meta_slides_weight_' . $weight]]['imgurl'] = $result[0];
                }
            }
        }
    }

    $results = get_post_meta($id);
    if (is_array($results['_property_meta_fields'])) {
        $meta_fields = $results['_property_meta_fields'];
    }
    else {
        $meta_fields = unserialize($results['_property_meta_fields']);
    }

    $meta_fields[] = '_property_slides';

    ksort($new_slides);

    update_post_meta(
        $id,
        '_property_meta_fields',
        array(
            '_property_id',
            '_property_title',
            '_property_landlord',
            '_property_agencies',
            '_property_agents',
            '_property_custom_text',
            '_property_price',
            '_property_price_suffix',
            '_property_bathrooms',
            '_property_hide_baths',
            '_property_hide_beds',
            '_property_area',
            '_property_latitude',
            '_property_longitude',
            '_property_featured',
            '_property_reduced',
            '_property_slider_image',
            '_property_slides'
        )
    );
    update_post_meta($id, '_property_slides', $new_slides);

    wp_redirect(get_permalink($submission_page->ID));

    return TRUE;
}

/**
 * Delete property
 * @param $id
 */
function aviators_properties_property_delete($id) {
    $link = _aviators_properties_submission_create_link(array('id' => $id, 'action' => 'delete-confirm'));
    aviators_flash_add_message(
        AVIATORS_FLASH_SUCCESS,
        __("Are you sure you want to delete this post? <a href=\"$link\" class=\"btn\">Yes, delete</a>", 'aviators')
    );
}

function aviators_properties_property_delete_confirm($id) {
    $page = _aviators_properties_get_submission_page();
    aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Post has been successfully deleted.', 'aviators'));

    wp_delete_post($id);
    wp_redirect(get_permalink($page));

    return TRUE;
}

/**
 * Helper function to create submission url link
 * @param $params
 * @return string $link
 */
function _aviators_properties_submission_create_link($params) {
    $page = _aviators_properties_get_submission_page();
    $permalink = get_permalink($page) . '?' . http_build_query($params);

    return $permalink;
}

/**
 * Helper function to get submission page index
 * @return bool|mixed
 */
function _aviators_properties_get_submission_page() {
    static $submissionPage;

    if ($submissionPage) {
        return $submissionPage;
    }
    else {
        $pages = get_posts(
            array(
                'post_type' => 'page',
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-submission-index.php',
            )
        );
    }

    if (count($pages)) {
        $submissionPage = reset($pages);
    }
    else {
        $submissionPage = FALSE;
    }

    return $submissionPage;
}


/**
 * Delete thumbnail on property
 * @param $id
 */
function aviators_properties_property_thumbnail_delete($id) {
    update_post_meta($id, '_thumbnail_id', '');
    aviators_flash_add_message(
        AVIATORS_FLASH_SUCCESS,
        __('Post\'s thumbnail has been successfully removed.', 'aviators')
    );
    $query = http_build_query(array('action' => 'edit', 'id' => $id));
    wp_redirect(get_permalink(get_the_ID()) . '/?' . $query);

    return TRUE;
}

/**
 * Change status for post
 * @param $id
 * @param $status
 */
function aviators_properties_property_status($id, $status) {
    $post = get_post($id);

    if ($status == 'unpublish') {
        $post->post_status = 'draft';
        aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Post has been successfully unpublished.', 'aviators'));
    }
    if ($status == 'pending') {
        $post->post_status = 'pending';
        aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Post is pending admin review.', 'aviators'));
    }
    if ($status == 'publish') {
        $post->post_status = 'publish';
        aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Post has been successfully published.', 'aviators'));
    }

    wp_update_post($post);


    $submission_page = _aviators_properties_get_submission_page();
    wp_redirect(get_permalink($submission_page->ID));

    return TRUE;
}

function aviators_properties_form_generate($metabox) {
    // js

    global $post;

    // Check if we are editing already existing post or adding new one
    if (isset($_GET['id'])) {
        $post = get_post($_GET['id']);
    }
    else {
        $post = new stdClass();
        $post->ID = 0;
    }

    // Include file rendering checboxes and combo boxes
    if (!function_exists('wp_terms_checklist')) {
        require_once ABSPATH . 'wp-admin/includes/template.php';
    }

    // Property contracts
    if (!empty($post->ID)) {
        $property_contracts_terms = wp_get_post_terms($post->ID, 'property_contracts');
        $property_contracts_selected_terms = $property_contracts_terms[0]->term_id;
    }
    else {
        $property_contracts_selected_terms = '';
    }

    $property_contracts = wp_dropdown_categories(
        array(
            'id' => 'property_contracts',
            'name' => 'property_contracts',
            'taxonomy' => 'property_contracts',
            'echo' => 0,
            'hide_empty' => 0,
            'selected' => $property_contracts_selected_terms,
        )
    );

    // Property types
    if (!empty($post->ID)) {
        $property_types_terms = wp_get_post_terms($post->ID, 'property_types');
        $property_types_selected_terms = $property_types_terms[0]->term_id;
    }
    else {
        $property_types_selected_terms = '';
    }
    $property_types = wp_dropdown_categories(
        array(
            'id' => 'property_types',
            'name' => 'property_types',
            'taxonomy' => 'property_types',
            'echo' => 0,
            'hide_empty' => 0,
            'selected' => $property_types_selected_terms,
        )
    );

    // Property locations
    if (empty($post->ID)) {
        $post->ID = 0;
    }


    $allterms = wp_get_post_terms($post->ID, 'locations');
    $selectedTerm = 0;
    foreach($allterms as $term) {
        if($term->parent == 0) {
            $selectedTerm = $term;
        }
    }


    $property_locations = aviators_dropdown_categories(
        array(
            'selected' => $selectedTerm->term_id,
            'id' => 'property_locations',
            'name' => 'property_locations',
            'taxonomy' => 'locations',
            'echo' => 0,
            'hide_empty' => 0,
            'hierarchical' => TRUE,
            'parent' => 0,
        )
    );

    if($selectedTerm) {
        $parentTerm  = $selectedTerm;

        $selectedTerm = 0;
        foreach($allterms as $term) {
            if($term->parent == $parentTerm->term_id) {
                $selectedTerm = $term;
            }
        }

    }
    $parentTermId = 100000;
    if(isset($parentTerm)) {
        $parentTermId = $parentTerm->term_id;
    }

    $property_sublocations = aviators_dropdown_categories(
        array(
            'id' => 'property_sublocations',
            'name' => 'property_sublocations',
            'taxonomy' => 'locations',
            'show_option_none' => true,
            'echo' => 0,
            'hide_empty' => 0,
            'hierarchical' => TRUE,
            'parent' => $parentTermId,
            'selected' => $selectedTerm->term_id
        )
    );

    if($selectedTerm) {
        $parentTerm  = $selectedTerm;

        $terms = wp_get_post_terms($post->ID, 'locations', array('parent' => $parentTerm->term_id));
        $selectedTerm = 0;
        foreach($allterms as $term) {
            if($term->parent == $parentTerm->term_id) {
                $selectedTerm = $term;
            }
        }
    }
    $parentTermId = 100000;
    if(isset($parentTerm)) {
        $parentTermId = $parentTerm->term_id;
    }


    $property_subsublocations = aviators_dropdown_categories(
        array(
            'id' => 'property_subsublocations',
            'name' => 'property_subsublocations',
            'taxonomy' => 'locations',
            'echo' => 0,
            'hide_empty' => 0,
            'hierarchical' => TRUE,
            'parent' => $parentTermId,
            'selected' => $selectedTerm->term_id
        )
    );


    ob_start();
    aviators_terms_checklist(
        $post->ID,
        array(
            'taxonomy' => 'amenities',
        )
    );
    $amenities = ob_get_clean();

    $slides = get_post_meta($post->ID, '_property_slides');
    if (is_array($slides) && count($slides)) {
        $slides = reset($slides);
    }
    else {
        $slides = array();
    }

    return array(
        'content' => View::render(
            'properties/form-content.twig',
            array(
                'slides' => $slides,
                'post' => $post,
                'amenities' => $amenities,
                'property_types' => $property_types,
                'property_locations' => $property_locations,
                'property_sublocations' => $property_sublocations,
                'property_subsublocations' => $property_subsublocations,
                'property_contracts' => $property_contracts,
            )
        ),
        'metabox' => $metabox,
    );
}

function aviators_property_before_page_render() {
    if (isset($_GET['load_terms'])) {
        $terms = get_terms('locations', array('parent' => $_GET['load_terms']));

        $output = '';
        $output .= '<option value="">-</option>';

        foreach ($terms as $term) {
            $output .= '<option value="' . $term->term_id . '" >' . $term->name . '</option>';
        }
        print $output;
        exit;
    }
}

add_action('aviators_before_page_render', 'aviators_property_before_page_render');


function aviators_property_submission_button_options($wp_customize) {
// url
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages();
        $defaultLanguage = icl_get_default_language();
        $priority = 10;

        foreach ($languages as $code => $language) {
            if ($code == $defaultLanguage) {
                aviators_property_submission_option_default($wp_customize);
            }
            else {


                $wp_customize->add_setting(
                    'header_call_to_action_url_' . $code,
                    array('default' => '/wp-admin/edit.php?post_type=property')
                );
                $wp_customize->add_control(
                    'header_call_to_action_url_' . $code,
                    array(
                        'label' => __('Call to action URL', 'aviators') . " " . $language['translated_name'],
                        'section' => 'realia_header',
                        'settings' => 'header_call_to_action_url_' . $code,
                        'priority' => $priority,
                    )
                );
                $priority++;
                // text
                $wp_customize->add_setting(
                    'header_call_to_action_text_' . $code,
                    array('default' => 'List your property')
                );
                $wp_customize->add_control(
                    'header_call_to_action_text_' . $code,
                    array(
                        'label' => __('Call to action text', 'aviators') . " " . $language['translated_name'],
                        'section' => 'realia_header',
                        'settings' => 'header_call_to_action_text_' . $code,
                        'priority' => $priority,
                    )
                );
                $priority++;
            }
        }
    }
    else {
        aviators_property_submission_option_default($wp_customize);
    }
}

function aviators_property_submission_option_default($wp_customize) {
    $wp_customize->add_setting(
        'header_call_to_action_url',
        array('default' => '/wp-admin/edit.php?post_type=property')
    );
    $wp_customize->add_control(
        'header_call_to_action_url',
        array(
            'label' => __('Call to action URL', 'aviators'),
            'section' => 'realia_header',
            'settings' => 'header_call_to_action_url',
            'priority' => 8,
        )
    );
    // text
    $wp_customize->add_setting('header_call_to_action_text', array('default' => 'List your property'));
    $wp_customize->add_control(
        'header_call_to_action_text',
        array(
            'label' => __('Call to action text', 'aviators'),
            'section' => 'realia_header',
            'settings' => 'header_call_to_action_text',
            'priority' => 9,
        )
    );
}

function aviators_property_submission_button_get($type) {
    if (function_exists('icl_get_languages')) {

        if (icl_get_default_language() == ICL_LANGUAGE_CODE) {
            return get_theme_mod('header_call_to_action_' . $type);
        }
        else {
            return get_theme_mod('header_call_to_action_' . $type . '_' . ICL_LANGUAGE_CODE);
        }
    }
    return get_theme_mod('header_call_to_action_' . $type);
}