<?php
if (is_admin()) {
        add_action( 'add_meta_boxes', array( 'dsIdxListingsPages', 'addIdxOptions' ) );
        add_action( 'save_post', array( 'dsIdxListingsPages', 'saveIdxOptions' ) );     
} else {
        add_action('admin_bar_menu', array('dsIdxListingsPages', 'AdminBar'), 500);
        add_filter('the_posts', array('dsIdxListingsPages', 'DisplayPage'), 100);
        add_filter('body_class', array('dsIdxListingsPages', 'AddPostClass'));
        add_filter('post_class', array('dsIdxListingsPages', 'AddPostClass'));
        add_action('init', array('dsIdxListingsPages', 'EnsureBaseUri'));
        add_filter('template_include', array('dsIdxListingsPages', 'SetTemplate'));
}
add_action('init', array('dsIdxListingsPages', 'Setup'));
add_action('init', array('dsIdxListingsPages', 'RewriteRules'));


class dsIdxListingsPages {
    
    const LANG = 'some_textdomain';

    public static function Setup(){
        /* Set up url paths for internal page links inside FAQ content */
        register_post_type( 'ds-idx-listings-page',
                array(
                        'labels' => array(
                                'name' => __( 'IDX Pages' ),
                                'menu_name' => __('IDX Pages'),
                                'singular_name' => __( 'IDX Page' ),
                                'add_new_item' => __( 'Add New IDX Page' ),
                                'new_item' => __( 'New IDX Page' ),
                                'edit_item' => __( 'Edit IDX  Page' ),
                                'view_item' => __( 'View IDX Page' ),
                                'all_items' => __( 'All IDX Pages' ),
                                'search_items' => __( 'Search IDX Pages' ),
                        ),
                'public' => true,
                'has_archive' => false,
                'show_in_menu' => true,
                'show_ui' => true,
                'menu_position' => 15,
                'menu_icon' => 'dashicons-admin-home',
                'supports' => array('title', 'editor', 'thumbnail'),
                'public' => true,
                'hierarchical' => true,
                'taxonomies' => array(),
                'capability_type'     => 'page',
                'rewrite' => array('slug'=>'idx/listings', 'with_base'=>true)
                )
        );
    }

    public static function EnsureBaseUri(){
        if (preg_match('/idx\/listings/', $_SERVER['REQUEST_URI'])){
            $parts = explode('?', $_SERVER['REQUEST_URI']);
            if(substr($parts[0], -1) == '/'){
                return;
            }
            if(count($parts) == 1){
                $redirect = $parts[0].'/';
                header("Location: $redirect", true, 301);
                exit();
            }
            return;
        }
    }

    public static function RewriteRules() {
            add_rewrite_tag('%ds-idx-listings-page%', '([^&]+)');
            add_rewrite_rule('[Ii][Dd][Xx]/[Ll][Ii][Ss][Tt][Ii][Nn][Gg][Ss]/([^/]+)(?:/page\-(\\d+))?', 'index.php?ds-idx-listings-page=$matches[1]&idx-d-ResultPage=$matches[2]', 'top');

            add_rewrite_tag('%ds-idx-archives-listings-page%', '([^&]+)');
            add_rewrite_rule('archives/[Ii][Dd][Xx]/[Ll][Ii][Ss][Tt][Ii][Nn][Gg][Ss]/([^/]+)(?:/page\-(\\d+))?', 'index.php?ds-idx-listings-page=$matches[1]&idx-d-ResultPage=$matches[2]', 'top');

            $rules = get_option('rewrite_rules');
            if (!isset($rules["[Ii][Dd][Xx]/[Ll][Ii][Ss][Tt][Ii][Nn][Gg][Ss]/([^/]+)(?:/page\-(\\d+))?"]))
                add_action('wp_loaded', array('dsIdxListingsPages', 'FlushRewriteRules'));

            if (!isset($rules["archives/[Ii][Dd][Xx]/[Ll][Ii][Ss][Tt][Ii][Nn][Gg][Ss]/([^/]+)(?:/page\-(\\d+))?"]))
                add_action('wp_loaded', array('dsIdxListingsPages', 'FlushRewriteRules'));

    }
    
    public static function FlushRewriteRules(){
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    public static function AdminBar() {
            global $wp_query;

            if (is_array($wp_query->query) && isset($wp_query->query['ds-idx-listings-page']) && current_user_can('manage_options') && is_admin_bar_showing()) {
                    global $wp_admin_bar;
                    $wp_admin_bar->remove_menu('edit');
            }
    }

    public static function DisplayPage($posts) {
            global $wp_query;

            if (is_array($wp_query->query) && (isset($wp_query->query['ds-idx-listings-page']))) {
                remove_filter("the_posts", array("dsSearchAgent_Client", "Activate"));
                if(!isset($posts[0])){
                    return $posts;
                }
                $pageData = $posts[0];
                $pageContent = trim($pageData->post_content);
                if(!empty($pageContent)){
                    $pageContent = wpautop(wpautop($pageContent)).'<div class="dsidx-clear;"></div><hr class="dsidx-separator" />';
                }
                $wp_query->query['idx-action'] = 'results';
                $wp_query->is_page = 1;
                $wp_query->is_singular = 1;
                $wp_query->is_single = 0;
                if(!isset($_GET)){
                    $_GET = array();
                }
                $linkUrl = get_post_meta($pageData->ID, 'dsidxpress-assembled-url', true);
                $parts = parse_url($linkUrl);
                $filters = array();
                if(isset($parts['query'])){
                    parse_str($parts['query'], $filters);
                }
                $filters = array_map(array('dsIdxListingsPages','CleanIdxPageFilters'), $filters);
                $newPosts = dsSearchAgent_Client::Activate($posts, $filters, $pageData->ID);
                $newPosts[0]->post_content = $pageContent . $newPosts[0]->post_content;
                $newPosts[0]->post_name = $pageData->post_name;
                $newPosts[0]->ID = $pageData->ID;
                $newPosts[0]->post_title = $pageData->post_title;
                $newPosts[0]->post_type = 'ds-idx-listings-page';
                return $newPosts;
            }
            return $posts;
    }

    public static function CleanIdxPageFilters($item){
        return stripslashes($item);
    }

    public static function SetTemplate($template) {
        if (get_query_var('post_type') == 'ds-idx-listings-page') {
            $options = get_option(DSIDXPRESS_OPTION_NAME);
            if (!empty($options['IDXTemplate'])) {
                $newTemplate = locate_template(array($options['IDXTemplate']));
                if (!empty($newTemplate)) $template = $newTemplate;
            }
            else if (!empty($options['ResultsTemplate'])) {
                $newTemplate = locate_template(array($options['ResultsTemplate']));
                if (!empty($newTemplate)) $template = $newTemplate;
            }
        }
        return $template;
    }


    public static function AddPostClass($class) {
            global $wp_query;
            if (get_query_var('post_type') == 'ds-idx-listings-page') {
                    $class[] = 'page';
            }
            return $class;
    }

    static function header() {
            global $thesis;

            // let thesis handle the canonical
            if (!$thesis)
                echo "<link rel=\"canonical\" href=\"" . get_permalink() . "\" />\n";
    }

    public static function addIdxOptions($post){
        add_meta_box( 
            'idx_filters_box'
            ,__( 'IDX Data Filters', self::LANG )
            ,array( 'dsIdxListingsPages', 'renderIdxOptions' )
            ,'ds-idx-listings-page' 
            ,'normal'
            ,'high'
            );
    }

    public static function saveIdxOptions($post_id){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
       
        if (empty($_POST['ds-idx-page_nonce'])) return;
            
        if (!wp_verify_nonce( $_POST['ds-idx-page_nonce'], plugin_basename( __FILE__ ) ) ) die('no nonce');

        if ( 'ds-idx-listings-page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) return;
        }
        else {
            if ( !current_user_can( 'edit_post', $post_id ) ) die('uhh');
        }
        $url = $_POST['dsidxpress-assembled-url'];
        
        update_post_meta($post_id, 'dsidxpress-assembled-url', $url);
    }

    public static function renderIdxOptions($post){
        $url_value = null;
        $url_value = get_post_meta($post->ID, 'dsidxpress-assembled-url', true);
        $adminUri = get_admin_url();
        $property_types_html = "";

        wp_nonce_field( plugin_basename( __FILE__ ), 'ds-idx-page_nonce' );

        $property_types_html = "";
        $property_types = dsSearchAgent_ApiRequest::FetchData('AccountSearchSetupPropertyTypes', array(), false, 60 * 60 * 24);
        if(!empty($property_types) && is_array($property_types)){
            $property_types = json_decode($property_types["body"]);
            foreach ($property_types as $property_type) {
                $checked_html = '';
                $name = htmlentities($property_type->DisplayName);
                $id = $property_type->SearchSetupPropertyTypeID;
                $property_types_html .= <<<HTML
{$id}: {$name},
HTML;
            }
        }
        $property_types_html = substr($property_types_html, 0, strlen($property_types_html)-1);

        echo '
        <div class="postbox">
            <div class="inside">
                <input type="hidden" id="linkBuilderPropertyTypes" value="'.$property_types_html.'" />';
                dsSearchAgent_Admin::LinkBuilderHtml(false, -1, 1, true, $url_value);
        echo '
            </div>
        </div>
        <div><span class="description">You must Publish/Update your page after modifying the IDX data filters.</span></div>
        ';
    }
}
?>