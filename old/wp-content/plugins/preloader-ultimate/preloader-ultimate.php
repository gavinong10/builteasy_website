<?php
/*
Plugin Name: Preloader Ultimate
Plugin URI: 
Version: 1.0.0
Author: butsokoy
Description: Just another Wordpress Preloader
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'wp_enqueue_scripts', 'preloader_ultimate_scripts' , 1);
function preloader_ultimate_scripts(){

    global $is_IE;
   
    $option = get_preloader_settings();
 
    if($option['enable'] == '0') return;
    if(wp_is_mobile() && $option['mobile'] == '0') return;

    if(isset($option['exclude']) && $option['exclude'] != ""){
        if(check_exclude($option['exclude'])) return;
    }

    $html = $option['html'];
    $type = $option['type'];
    $width = $option['width'];
    $css = $option['spinner'];

    if ($is_IE && $option['type'] == 'svg'){
        $html = $option['ie_html'];
        $type = $option['ie_type'];
        $width = $option['ie_width'];
        $css = $option['ie_spinner'];
    }
      
    $spinner = '<div class="preloader-ultimate-container">'.$html.'</div>';
    $delay = ((float) $option['exit_delay']) * 1000;
    $duration = ($option['exit_duration'] == '0') ? '0.1' : $option['exit_duration'];

    $preloader_ultimate_settings = array(
        'spinner'       => stripslashes($spinner),
        'delay'         => $delay,
        'type'          => $type,
        'width'         => $width,
        'duration'      => $duration.'s',
        'exit_anim'     => $option['exit_anim'],
        'text_spinner'  => stripslashes($option['text_spinner']),
        'entrance'      => $option['page_entrance'],
        'entrance_du'   => ((float) $option['exit_duration'] + 0.5).'s'
    );

    if($type == 'css') wp_enqueue_style( 'pu-css3-spinner-style', plugins_url('/spinners/css/'.$css.'.min.css',__FILE__));
    if($option['scrollbar'] == '1') wp_enqueue_style( 'pu-html-overflow', plugins_url('/css/html-overflow.css',__FILE__));

    wp_enqueue_style('pu-animate-style', plugins_url('/css/lib/animate.min.css',__FILE__));    
    wp_enqueue_style('pu-site-style', plugins_url('/css/site.css',__FILE__));
  
    wp_enqueue_script('jquery');
    wp_enqueue_script('pu-pace-script', plugins_url('/js/lib/pace.min.js',__FILE__));
    wp_enqueue_script('pu-site-script', plugins_url('/js/site.js',__FILE__));

    wp_localize_script('pu-site-script', 'preloader_ultimate_settings', $preloader_ultimate_settings);  
}

function check_exclude($exclude){

    if(empty($exclude) || !isset($exclude)) return false;

    global $post;
    $slug    = $post->post_name;
    $page_id = get_the_ID();
    $archive = (is_archive()) ? strtolower(single_cat_title("", false)) : null;
    $home    = (is_front_page()) ? 'home' : null;

    $exclude_this_page = 0;
    $arr = explode(',',$exclude);
    foreach ($arr as $value) {
        $v = trim(strtolower($value));
        if($v == $page_id || $v == $slug || $v == $archive || $v == $home) $exclude_this_page = true;
    }

    if($exclude_this_page) return true;
    else return false;
}

function get_preloader_settings(){
    global $wpdb; global $post;
    
    $slug    = $post->post_name;
    $page_id = get_the_ID();
    $archive = (is_archive()) ? trim(strtolower(single_cat_title("", false))) : null ;

    if(is_front_page()){
        $ids = "'home'";
    }else if($archive != null){
        $ids = "'".$archive."'";
    }else{
        $ids = "'".$slug."', '".$page_id."'";
    }

    $sql = "SELECT * FROM {$wpdb->prefix}preloader_ultimate WHERE available_on IN ($ids)";

    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    if(count($result)){
        $settings = $result[0];
    }else{
        $settings = get_option('_preloader_ultimate_global_options', option_defaults());
    }

    return $settings;
}

function option_defaults(){
    $defaults = array(
        'title'         => '',
        'available_on'  => '',
        'enable'        => '1',
        'mobile'        => '1',
        'scrollbar'     => '0',
        'exclude'       => '',
        'color'         => '#ffffff',
        'background'    => '#1abc9c',
        'type'          => 'css',
        'spinner'       => 'ball-clip-rotate',
        'width'         => '',
        'html'          => '<div spinner="ball-clip-rotate" class="la-ball-clip-rotate center-spin" style="color:rgb(255,255,255);"><div></div></div>',
        'ie_type'       => 'css',
        'ie_spinner'    => 'ball-clip-rotate',
        'ie_width'      => '',
        'ie_color'      => '#ffffff',
        'ie_background' => '#1abc9c',
        'ie_html'       => '<div spinner="ball-clip-rotate" class="la-ball-clip-rotate center-spin" style="color:rgb(255,255,255);"><div></div></div>',
        'exit_delay'    => '1',
        'exit_anim'     => 'fadeOut',
        'exit_duration' => '1',
        'text_spinner'  => '',
        'text_color'    => '#ffffff',
        'text_size'     => '14',
        'page_entrance' => 'zoomIn'
    );
    return $defaults;
}

add_filter('body_class','my_body_classes');
function my_body_classes( $classes ) {
 
    $option = get_preloader_settings();

    if(wp_is_mobile() && $option['mobile'] == '0'){
        
        return $classes;

    }else if(check_exclude($option['exclude'])){

        return $classes;

    }else if($option['enable'] == '1'){

        $classes[] = 'preloader-ultimate';
    }   
    return $classes;    
}

add_action('wp_head','preloader_ultimate_background_css');
function preloader_ultimate_background_css() {
    global $is_IE;

    $opt = get_preloader_settings();

    $background = $opt['background'];

    if ($is_IE && $opt['type'] == 'svg'){
        $background = $opt['ie_background'];   
    }

    $style = '<style type="text/css"> body.preloader-ultimate:before{background-color:'.$background.' !important;} .preloader-ultimate-container{background-color:'.$background.' !important;} .preloader-text{color:'.$opt['text_color'].';font-size:'.$opt['text_size'].'px;} </style>';
    
    echo $style;
}

add_action('admin_menu', 'preloader_ultimate_menu');
function preloader_ultimate_menu() {
 
    $preloader_ultimate = add_menu_page(
        'Preloader Ultimate Global Settings',       
        'Preloader Ultimate',                
        'administrator',                
        'pu-global-settings',                
        'pu_global_settings_page',  
        plugins_url('/icon/preloader.png',__FILE__),                                
        '82.6'                              
    );
    add_submenu_page(
        'pu-global-settings',
        'Preloader Ultimate Global Settings',
        'Global Settings',
        'administrator',
        'pu-global-settings',
        'pu_global_settings_page'
    );
    $single_page = add_submenu_page(
        'pu-global-settings',
        'Single Page Settings',
        'Single Page Settings',
        'administrator',
        'pu-single-page-settings',
        'pu_single_settings_page'
    );  

    //only load the script in preloader ultimate setting page
    add_action( 'admin_print_scripts-' . $preloader_ultimate, 'preloader_ultimate_admin_scripts' ); 
    add_action( 'admin_print_scripts-' . $single_page, 'preloader_ultimate_admin_scripts' );
}

function pu_global_settings_page(){
    global $title; 
    require_once('pu-global-settings.php');
}

function pu_single_settings_page() {
    global $title; 
    require_once('pu-single-settings.php');
}

function preloader_ultimate_admin_scripts(){
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');

    wp_enqueue_style('pu-animate-style', plugins_url('/css/lib/animate.min.css',__FILE__)); 
    wp_enqueue_style('pu-spinners-style', plugins_url('/css/lib/loaders.css',__FILE__));
    wp_enqueue_style('pu-nouislider-style', plugins_url('/css/lib/nouislider.min.css',__FILE__));
    wp_enqueue_style('pu-settings-style', plugins_url('/css/settings.css',__FILE__));
   
    wp_enqueue_script('pu-nouislider-script', plugins_url('/js/lib/nouislider.min.js',__FILE__));
    wp_enqueue_script('pu-color-picker-alpha-script', plugins_url('/js/lib/wp-color-picker-alpha.min.js',__FILE__),array('jquery','wp-color-picker'));
    
    wp_enqueue_script('pu-spinners-list-script', plugins_url('/js/pu-spinners-list.js',__FILE__),'','',true);
    
    $screen = get_current_screen();

    if($screen->id == "toplevel_page_pu-global-settings"){

        wp_enqueue_script('pu-global-settings-script', plugins_url('/js/pu-global-settings.js',__FILE__),'','',true);

        $option = get_option('_preloader_ultimate_global_options', option_defaults());

        $option['html'] = stripcslashes($option['html']);
        $option['ie_html'] = stripcslashes($option['ie_html']);
        wp_localize_script('pu-global-settings-script', 'pu_global_settings', $option);
        wp_localize_script('pu-global-settings-script', 'pu_default_settings', option_defaults()); 

    }else if($screen->id == "preloader-ultimate_page_pu-single-page-settings"){

        wp_enqueue_script('pu-single-settings-script', plugins_url('/js/pu-single-settings.js',__FILE__),'','',true);
        wp_localize_script('pu-single-settings-script', 'pu_default_settings', option_defaults());

    }
}

add_action('wp_ajax_save_pu_single_page_settings', 'save_pu_single_page_settings_callback');
function save_pu_single_page_settings_callback() {

    global $wpdb;

    $preloader_ultimate_tbl = $wpdb->prefix.'preloader_ultimate';
    $opt = $_POST['preloader_options'];
    $available = "'".$opt['available']."'";

    $sql =  $sql = "SELECT * FROM $preloader_ultimate_tbl WHERE available_on IN ($available)";
    $result = $wpdb->get_results( $sql, 'ARRAY_A' );


    $settings = array( 
        'title'         => $opt['title'],
        'available_on'  => $opt['available'],
        'enable'        => $opt['enable'],
        'mobile'        => $opt['mobile'],
        'scrollbar'     => $opt['scrollbar'],          
        'color'         => $opt['color'],
        'background'    => $opt['background'],
        'type'          => $opt['type'],
        'spinner'       => $opt['spinner'],
        'width'         => $opt['width'],
        'html'          => $opt['html'],
        'ie_type'       => $opt['ie_type'],
        'ie_spinner'    => $opt['ie_spinner'],
        'ie_width'      => $opt['ie_width'],
        'ie_color'      => $opt['ie_color'],
        'ie_background' => $opt['ie_background'],
        'ie_html'       => $opt['ie_html'],
        'exit_delay'    => $opt['exit_delay'],
        'exit_anim'     => $opt['exit_anim'],
        'exit_duration' => $opt['exit_duration'],
        'page_entrance' => $opt['page_entrance'],
        'text_spinner'  => $opt['text_spinner'],
        'text_color'    => $opt['text_color'],
        'text_size'     => $opt['text_size']
    ); 

    if(count($result) && $opt['preloader_id'] == '0'){

        $data['response'] = 'exist';

    }else if($opt['preloader_id'] == '0'){

        $settings['time'] = current_time('mysql');
        $data['response'] = $wpdb->insert($preloader_ultimate_tbl, $settings);

    }else{

        $data['response'] = $wpdb->update(
            $preloader_ultimate_tbl,
            $settings,
            array('id' => $opt['preloader_id'])
        );
    }

    $data['settings'] = $settings;
   
    echo json_encode($data);

    die();
}

add_action('wp_ajax_delete_pu_single_page_settings', 'delete_pu_single_page_settings_callback');
function delete_pu_single_page_settings_callback() {

    global $wpdb;

    $preloader_ultimate_tbl = $wpdb->prefix.'preloader_ultimate';

    $data['response'] = $wpdb->delete($preloader_ultimate_tbl, ['id' => $_POST['preloader_id']],['%d']);

    echo json_encode($data);

    die();
}

add_action('wp_ajax_retrieve_pu_single_page_settings', 'retrieve_pu_single_page_settings_callback');
function retrieve_pu_single_page_settings_callback() {

    global $wpdb;

    $preloader_ultimate_tbl = $wpdb->prefix.'preloader_ultimate';
    $preloader_id = $_POST['preloader_id'];

    $sql = "SELECT * FROM $preloader_ultimate_tbl WHERE id=$preloader_id";
    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    $data['response'] = 0;

    if(count($result)){
        $data['response'] = 1;
        $settings = $result[0];

        $settings['text_spinner'] = stripcslashes($settings['text_spinner']);
        $settings['html'] = stripcslashes($settings['html']);
        $settings['ie_html'] = stripcslashes($settings['ie_html']);

        $data['settings'] = $settings;
    }

    echo json_encode($data);

    die();
}

add_action('wp_ajax_save_pu_global_options', 'save_pu_global_options_callback');
function save_pu_global_options_callback() {

    $data['response'] = update_option('_preloader_ultimate_global_options', $_POST['preloader_options']);
    $data['settings']  = get_option('_preloader_ultimate_global_options');

    echo json_encode($data);

    die();
}

if ( ! class_exists( 'WP_List_Table' ) )
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Preloader_Ultimate_List extends WP_List_Table {
    
    /**
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     */
    function __construct() {
        global $status, $page;
        //Set parent defaults
        parent::__construct(
            array(
                //singular name of the listed records
                'singular'  => 'preloader',
                //plural name of the listed records
                'plural'    => 'preloaders',
                //does this table support ajax?
                'ajax'      => true
            )
        );
        
    }

    function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'available_on'  : 
            case 'enable'        : 
            case 'html'          : 
            case 'exit_anim'     : 
            case 'page_entrance' :
            case 'time'          :
                return $item[ $column_name ];
            default:
                //Show the whole array for troubleshooting purposes
                return print_r( $item, true );
        }
    }

    function column_title( $item ) {
        //Build row actions
        $actions = array(
            'edit'      => sprintf( '<a href="" class="edit-preloader" id="%s">Edit</a>', $item['id'] ),
            'delete'    => sprintf( '<a href="" class="delete-preloader" id="%s">Delete</a>', $item['id'] ),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $this->row_actions( $actions )
        );
    }

    function column_enable( $item ) {
      return ($item['enable'] == '1') ? 'true' : 'false'; 
    }

    function column_html( $item ) {
        $html = stripcslashes($item['html']);
        $preloader = '<div spinwidth="'.$item['width'].'" style="background-color:'.$item['background'].'" class="table-preview">'.$html.'</div>';

      return $preloader; 
    }

    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ 'bulk-delete',   
            /*$2%s*/ $item['id']
        );
    }

    function get_columns() {
        return $columns = array(
            'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'         => 'Title',
            'available_on'  => 'Available on',
            'enable'        => 'Enable',
            'html'          => 'Preview',
            'exit_anim'     => 'Exit Effect',
            'page_entrance' => 'Page Entrance Effect',
            'time'          => 'Date'
        );
    }

    function get_sortable_columns() {
        return $sortable_columns = array(
            'title'         => array( 'title', false ), 
            'enable'        => array( 'enable', false ),
            'exit_anim'     => array( 'exit_anim', false ),
            'page_entrance' => array( 'page_entrance', false ),
            'time'          => array( 'time', true )
        );
    }

    function get_bulk_actions() {
        return $actions = array(
            'delete'    => 'Delete'
        );
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'=== $this->current_action() ) {

            $delete_ids = esc_sql( $_REQUEST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            $total = 0;
            foreach ( $delete_ids as $id ) {
                if($this->delete_preloader($id) == 1)
                    $total++;
            }

            if($total != 0)
            echo '<div class="updated notice is-dismissible below-h2"><p>'.$total.' preloader deleted.</p><button type="button" class="notice-dismiss"></button></div>';
        }    
    }

    function delete_preloader( $id ) {
        global $wpdb;

        return $wpdb->delete(
            "{$wpdb->prefix}preloader_ultimate",
            [ 'ID' => $id ],
            [ '%d' ]
        );
    }
        
    function get_preloader_list( $per_page = 10, $page_number = 1, $orderby = 'time', $order = 'desc') {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}preloader_ultimate";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
         $sql .= ' ORDER BY ' . $orderby;
         $sql .= ' ' . $order; 
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    function prepare_items() {
        global $wpdb; 
        
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
   
        $this->process_bulk_action();

        $current_page = $this->get_pagenum();
  
        $total_items = $this->record_count();

        $orderby = (! empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'time';

        $order  = (! empty( $_REQUEST['order'] ) && '' != $_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
    
        $data = $this->get_preloader_list($per_page, $current_page, $orderby, $order);
       
        $this->items = $data;
      
        $this->set_pagination_args(
            array(
                //WE have to calculate the total number of items
                'total_items'   => $total_items,
                //WE have to determine how many items to show on a page
                'per_page'  => $per_page,
                //WE have to calculate the total number of pages
                'total_pages'   => ceil( $total_items / $per_page ),
                // Set ordering values if needed (useful for AJAX)
                'orderby'   => ! $orderby,
                'order'     => ! $order
            )
        );
    }

    function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}preloader_ultimate";

        return $wpdb->get_var( $sql );
    }

    function display() {
        wp_nonce_field( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );
        echo '<input type="hidden" id="order" name="order" value="' . $this->_pagination_args['order'] . '" />';
        echo '<input type="hidden" id="orderby" name="orderby" value="' . $this->_pagination_args['orderby'] . '" />';
        parent::display();
    }

    function ajax_response() {
        check_ajax_referer( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );
        $this->prepare_items();
        extract( $this->_args );
        extract( $this->_pagination_args, EXTR_SKIP );
        ob_start();
        if ( ! empty( $_REQUEST['no_placeholder'] ) )
            $this->display_rows();
        else
            $this->display_rows_or_placeholder();
        $rows = ob_get_clean();
        ob_start();
        $this->print_column_headers();
        $headers = ob_get_clean();
        ob_start();
        $this->pagination('top');
        $pagination_top = ob_get_clean();
        ob_start();
        $this->pagination('bottom');
        $pagination_bottom = ob_get_clean();
        $response = array( 'rows' => $rows );
        $response['pagination']['top'] = $pagination_top;
        $response['pagination']['bottom'] = $pagination_bottom;
        $response['column_headers'] = $headers;
        if ( isset( $total_items ) )
            $response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );
        if ( isset( $total_pages ) ) {
            $response['total_pages'] = $total_pages;
            $response['total_pages_i18n'] = number_format_i18n( $total_pages );
        }
        die( json_encode( $response ) );
    }
}

function _ajax_fetch_custom_list_callback() {
    $wp_list_table = new Preloader_Ultimate_List();
    $wp_list_table->ajax_response();
}
add_action('wp_ajax__ajax_fetch_custom_list', '_ajax_fetch_custom_list_callback');

function preloader_ultimate_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'preloader_ultimate';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        title varchar(1000) DEFAULT '',
        available_on varchar(1000) NOT NULL,
        enable tinytext DEFAULT '',
        mobile tinytext DEFAULT '',
        scrollbar tinytext DEFAULT '',
        color tinytext DEFAULT '',
        background tinytext DEFAULT '',
        type tinytext DEFAULT '',
        width tinytext DEFAULT '',
        spinner tinytext DEFAULT '',
        html varchar(1000) DEFAULT '',
        ie_color tinytext DEFAULT '',
        ie_background tinytext DEFAULT '',
        ie_type tinytext DEFAULT '',
        ie_width tinytext DEFAULT '',
        ie_spinner tinytext DEFAULT '',
        ie_html varchar(1000) DEFAULT '',
        exit_delay tinytext DEFAULT '',
        exit_anim tinytext DEFAULT '',
        exit_duration tinytext DEFAULT '',
        page_entrance tinytext DEFAULT '',
        text_spinner tinytext DEFAULT '',
        text_color tinytext DEFAULT '',
        text_size tinytext DEFAULT '',
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    update_option('_preloader_ultimate_version', '1.0');
}

register_activation_hook( __FILE__, 'preloader_ultimate_install' );