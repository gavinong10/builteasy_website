<?php

/**
 * Simple Widget Logic Export Page
 */
function aviators_utils_to_export_page() {
    $to = new AviatorsUtilsTOExport();
    $to->render();
}

/**
 * Simple Widget Logic Import Page
 */
function aviators_utils_to_import_page() {
    $to = new AviatorsUtilsTOImport();
    $to->render();
}

/**
 * Class AviatorsUtilsWLExport
 * Widget Logic Export Class
 */
class AviatorsUtilsTOExport {
    public function __construct() {

    }

    public function render() {
        $link = home_url() . '/aviators-utils/theme-options';
        echo "<a href=\"$link\">Export</a>";
    }

    public function export() {
        $this->downloadHeaders();

        $options = array('theme_mods_' . strtolower(get_option( 'current_theme' )), 'show_on_front', 'page_for_posts', 'page_on_front');
        $options = apply_filters('aviators_utils_theme_options', $options);

        $exportOptions = array();
        foreach($options as $option) {
            $exportOptions['options'][$option] = get_option($option);
        }

        $menuTerms = get_terms('nav_menu');
        foreach($menuTerms as $term) {
            $exportOptions['menu_associations'][$term->term_id] = $term->slug;
        }


        $associated_posts = get_posts(
            array(
                'post_type' => 'page',
                'post__in' => array(get_option('page_on_front'), get_option('page_for_posts'))
            ));

        foreach($associated_posts as $post) {
            $exportOptions['post_associations'][$post->ID] = $post->post_name;
        }

        $json_string = json_encode($exportOptions);
        echo $json_string;
    }


    public function downloadHeaders() {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename=theme_options.json");
        header("Content-Transfer-Encoding: binary");
    }
}

/**
 * Class AviatorsUtilsTOImport
 */
class AviatorsUtilsTOImport {
    public function __construct() {
    }

    public function render() {

    }

    public function import($json_array) {
        $messages = array();
        $options = $json_array['options'];
        $menuAssociations = $json_array['menu_associations'];
        $postAssociations = $json_array['post_associations'];

        foreach($options as $key => $option) {
            if(update_option($key, $option)) {
                $messages[] = $key;
            }
        }

        $menuLocations = get_theme_mod('nav_menu_locations');
        $newLocations = array();

        foreach($menuLocations as $location => $termId) {
            $term = get_term_by('slug', $menuAssociations[$termId], 'nav_menu');
            if(!is_wp_error($term)) {
                $newLocations[$location] = $term->term_id;
            }
            $messages[] = $term->slug;
        }
        set_theme_mod('nav_menu_locations', $newLocations);
        update_option('show_on_front', 'page');

        if(isset($postAssociations[get_option('page_on_front')])) {
            $post_name = $postAssociations[get_option('page_on_front')];

            $posts = get_posts(array('post_type' => 'page', 'name' => $post_name));
            if($posts) {
                $post = reset($posts);
                update_option('page_on_front', $post->ID);
            }

        }

        if(isset($postAssociations[get_option('page_for_posts')])) {
            $post_name = $postAssociations[get_option('page_for_posts')];

            $posts = get_posts(array('post_type' => 'page', 'name' => $post_name));
            if($posts) {
                $post = reset($posts);
                update_option('page_for_posts', $post->ID);
            }
        }

        return $messages;
    }
}