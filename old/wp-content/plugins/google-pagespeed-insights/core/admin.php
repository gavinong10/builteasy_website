<?php

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************/

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/************************ EXTEND WP_List_Table CLASS ***************************
 *******************************************************************************/

class GPI_List_Table extends WP_List_Table {
    
    function __construct(){
        global $status, $page, $hook_suffix;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'gpi_page_report',
            'plural'    => 'gpi_page_reports',
            'ajax'      => false
        ) );
        
    }
    
    // here for compatibility with 4.3
    function get_columns()
    {
        // Get options
        $gpi_options = $this->getOptions();

        return $this->gpi_get_columns(false, false, false, $gpi_options['strategy']);
    }

    // humanTiming used to calculate time since last report check
    function humanTiming($time)
    {
        if(empty($time)) return 'N/A';
        $time = time() - $time;

        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
        }

    }

    function no_items() {
        $pagetype = isset( $_GET['render'] ) ? $_GET['render'] : 'list';

        switch($pagetype)
        {
            case 'list':
                _e( 'No Pagespeed Reports Found. Google Pagespeed may still be checking your pages. If problems persist, see the following possible solutions:', 'gpagespeedi' );
                ?>
                <ul class="no-items">
                    <li><?php _e( 'Make sure that you have entered your Google API key on the ', 'gpagespeedi' );?><a href="?page=<?php echo $_REQUEST['page']; ?>&render=options">Options</a> page.</li>
                    <li><?php _e( 'Make sure that you have enabled "PageSpeed Insights API" from the Services page of the ', 'gpagespeedi' );?><a href="https://code.google.com/apis/console/">Google Console</a>.</li>
                    <li><?php _e( 'The Google Pagespeed API may be temporarily unavailable.', 'gpagespeedi' );?></li>
                </ul>
                <?php
                break;

            case 'ignored-urls':
                _e( 'No Ignored URLs found. A URL can be ignored from the <a href="?page=' . $_REQUEST['page'] . '&render=list">Report List</a> page if you would like to remove it from report pages', 'gpagespeedi' );
                break;

        }
    }

    // Get our plugin options
    function getOptions($option_name = 'gpagespeedi_options') {
        $gpi_options = get_option($option_name);

        return $gpi_options;
    }
    
    // Figure out which types to query for based on the plugin options
    function getTypesToCheck($restrict_type = 'all') {

        $types = array();
        $gpi_options = $this->getOptions();
        $typestocheck = array();

        if($gpi_options['check_pages']) {
            if($restrict_type == 'all' || $restrict_type == 'ignored' || $restrict_type == 'pages') {
                $typestocheck[] = 'type = %s';
                $types[1][] = "page";
            }
        }

        if($gpi_options['check_posts']) {
            if($restrict_type == 'all' || $restrict_type == 'ignored' || $restrict_type == 'posts') {
                $typestocheck[] = 'type = %s';
                $types[1][] = "post";
            }
        }

        if($gpi_options['check_categories']) {
            if($restrict_type == 'all' || $restrict_type == 'ignored' || $restrict_type == 'categories') {
                $typestocheck[] = 'type = %s';
                $types[1][] = "category";
            }
        }
        if($gpi_options['cpt_whitelist']) {
            if($restrict_type == 'all' || $restrict_type == 'ignored' || stristr($restrict_type, 'gpi_custom_posts')) {

                $cpt_whitelist_arr = false;
                if(!empty($gpi_options['cpt_whitelist'])) {
                    $cpt_whitelist_arr = unserialize($gpi_options['cpt_whitelist']);
                }
                $args=array(
                  'public'   => true,
                  '_builtin' => false
                ); 
                $custom_post_types = get_post_types($args,'names','and');
                if($restrict_type != 'gpi_custom_posts' && $restrict_type != 'all' && $restrict_type != 'ignored') {
                    $restrict_type = str_replace('gpi_custom_posts-', '', $restrict_type);
                    foreach($custom_post_types as $post_type)
                    {
                        if($cpt_whitelist_arr && in_array($post_type, $cpt_whitelist_arr)) {
                            if($post_type == $restrict_type) {
                                $typestocheck[] = 'type = %s';
                                $types[1][] = $custom_post_types[$post_type];
                            }
                        }
                    }
                } else {
                    foreach($custom_post_types as $post_type)
                    {
                        if($cpt_whitelist_arr && in_array($post_type, $cpt_whitelist_arr)) {
                            $typestocheck[] = 'type = %s';
                            $types[1][] = $custom_post_types[$post_type];
                        }
                    }
                }
            }
        }

        if(!empty($typestocheck)) {
            $types[0] = '';
            foreach($typestocheck as $type)
            {
                if(!is_array($type)) {
                    $types[0] .= $type . ' OR ';
                } else {
                    foreach($type as $custom_post_type)
                    {
                        $types[0] .= 'type = %s OR ';
                        $types[1][] = $custom_post_type;
                    }
                }
            }
            $types[0] = rtrim($types[0], ' OR ');
            return $types;
        }
        return null;
    }

    // Default Column handling behaviors
    function column_default($item, $column_name){
        switch($column_name){
            case 'desktop_last_modified':
                $formatted_time = $this->humanTiming($item['desktop_last_modified']);
                return $formatted_time;
            case 'mobile_last_modified':
                $formatted_time = $this->humanTiming($item['mobile_last_modified']);
                return $formatted_time;
            case 'type':
                return $item['type'];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    
    // URL column handling behavior
    function column_URL($item){
        
        //Strip domain from URL
        $siteurl = get_site_url();
        $siteurl_ssl = get_site_url('','','https');

        $search_urls = array($siteurl, $siteurl_ssl);

        $cleaned_url = str_replace($search_urls, '', $item['URL']);

        //Build row actions
        $actions = array(
            'view_details'  => sprintf('<a href="?page=%s&render=%s&page_id=%s">%s</a>',$_REQUEST['page'],'details',$item['ID'],__('Details', 'gpagespeedi')),
            'ignore'        => sprintf('?page=%s&render=%s&action=%s&id=%s',$_REQUEST['page'],'list','ignore',$item['ID']),
            'visit'         => sprintf('<a href="%s" target="_blank">%s</a>',$item['URL'],__('View URL', 'gpagespeedi')),
        );

        $url_to_nonce = $actions['ignore'];
        $nonced_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($url_to_nonce, 'bulk-gpi_page_reports') : $url_to_nonce;
        $actions['ignore'] = '<a href="'.$nonced_url.'">'.__('Ignore', 'gpagespeedi').'</a>';

        //Return the url contents
        return sprintf('<a href="?page=%3$s&render=%4$s&page_id=%5$s">%1$s</a> %2$s',
            /*$1%s*/ $cleaned_url,
            /*$2%s*/ $this->row_actions($actions),
            /*$3%s*/ $_REQUEST['page'],
            /*$4%s*/ 'details',
            /*$5%s*/ $item['ID']
        );
    }

    // Ignored URL column handling behavior
    function column_Ignored_URL($item){
        
        //Strip domain from URL
        $siteurl = get_site_url();
        $siteurl_ssl = get_site_url('','','https');

        $search_urls = array($siteurl, $siteurl_ssl);

        $cleaned_url = str_replace($search_urls, '', $item['URL']);

        //Build row actions
        $actions = array(
            'activate'  => sprintf('?page=%s&render=%s&action=%s&id=%s',$_REQUEST['page'],'ignored-urls','activate',$item['ID']),
            'visit'     => sprintf('<a href="%s" target="_blank">%s</a>',$item['URL'],__('View URL', 'gpagespeedi')),
        );
        
        $url_to_nonce = $actions['activate'];
        $nonced_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($url_to_nonce, 'bulk-gpi_page_reports') : $url_to_nonce;
        $actions['activate'] = '<a href="'.$nonced_url.'">'.__('Reactivate', 'gpagespeedi').'</a>';

        //Return the url contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $cleaned_url,
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    // Mobile Score column handling behavior
    function column_mobile_score($item){

        if(empty($item['mobile_score'])) return 'N/A';

        $gradient = array("", "FF0000","FE0500","FE0A00","FE0F00","FE1400","FE1900","FE1E00","FE2300","FE2800","FE2D00","FE3300","FE3800","FE3D00","FE4200","FE4700","FE4C00","FE5100","FD5600","FD5B00","FD6000","FD6600","FD6B00","FD7000","FD7500","FD7A00","FD7F00","FD8400","FD8900","FD8E00","FD9300","FD9900","FD9E00","FDA300","FDA800","FCAD00","FCB200","FCB700","FCBC00","FCC100","FCC600","FCCC00","FCD100","FCD600","FCDB00","FCE000","FCE500","FCEA00","FCEF00","FCF400","FCF900","FCFF00","F7FF00","F2FF00","EEFF00","E9FF00","E4FF00","E0FF00","DBFF00","D6FF00","D2FF00","CDFF00","C8FF00","C4FF00","BFFF00","BAFF00","B6FF00","B1FF00","ACFF00","A8FF00","A3FF00","9EFF00","9AFF00","95FF00","90FF00","8CFF00","87FF00","83FF00","7EFF00","79FF00","75FF00","70FF00","6BFF00","67FF00","62FF00","5DFF00","59FF00","54FF00","4FFF00","4BFF00","46FF00","41FF00","3DFF00","38FF00","33FF00","2FFF00","2AFF00","25FF00","21FF00","1CFF00","18FF00");
        $barcolor = $gradient[$item['mobile_score']];
        $innerdiv_css = "background-color:#".$barcolor.";width:".$item['mobile_score']."%";

        //Return the score contents
        return sprintf('<span class="scorenum">%1$s</span><div class="reportscore_outter_bar"><div class="reportscore_inner_bar" style="%2$s"></div></div>',$item['mobile_score'], $innerdiv_css);
    }

    // Desktop Score column handling behavior
    function column_desktop_score($item){

        if(empty($item['desktop_score'])) return 'N/A';

        $gradient = array("", "FF0000","FE0500","FE0A00","FE0F00","FE1400","FE1900","FE1E00","FE2300","FE2800","FE2D00","FE3300","FE3800","FE3D00","FE4200","FE4700","FE4C00","FE5100","FD5600","FD5B00","FD6000","FD6600","FD6B00","FD7000","FD7500","FD7A00","FD7F00","FD8400","FD8900","FD8E00","FD9300","FD9900","FD9E00","FDA300","FDA800","FCAD00","FCB200","FCB700","FCBC00","FCC100","FCC600","FCCC00","FCD100","FCD600","FCDB00","FCE000","FCE500","FCEA00","FCEF00","FCF400","FCF900","FCFF00","F7FF00","F2FF00","EEFF00","E9FF00","E4FF00","E0FF00","DBFF00","D6FF00","D2FF00","CDFF00","C8FF00","C4FF00","BFFF00","BAFF00","B6FF00","B1FF00","ACFF00","A8FF00","A3FF00","9EFF00","9AFF00","95FF00","90FF00","8CFF00","87FF00","83FF00","7EFF00","79FF00","75FF00","70FF00","6BFF00","67FF00","62FF00","5DFF00","59FF00","54FF00","4FFF00","4BFF00","46FF00","41FF00","3DFF00","38FF00","33FF00","2FFF00","2AFF00","25FF00","21FF00","1CFF00","18FF00");
        $barcolor = $gradient[$item['desktop_score']];
        $innerdiv_css = "background-color:#".$barcolor.";width:".$item['desktop_score']."%";

        //Return the score contents
        return sprintf('<span class="scorenum">%1$s</span><div class="reportscore_outter_bar"><div class="reportscore_inner_bar" style="%2$s"></div></div>',$item['desktop_score'], $innerdiv_css);
    }
    
    // Checkboxes column handling
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['ID']
        );
    }
    
    // Setup available column types
    function gpi_get_columns($ignored = false, $strategy){
        if($ignored) {
            $columns = array(
                'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
                'ignored_url'   => __('Ignored URL', 'gpagespeedi'),
                'type'          => __('Page Type', 'gpagespeedi'),
            );
        } elseif($strategy == "desktop") {
            $columns = array(
                'cb'                    => '<input type="checkbox" />', //Render a checkbox instead of text
                'url'                   => __('URL', 'gpagespeedi'),
                'desktop_score'         => __('Score', 'gpagespeedi'),
                'type'                  => __('Page Type', 'gpagespeedi'),
                'desktop_last_modified' => __('Last Checked', 'gpagespeedi'),
            );
        } elseif($strategy == "mobile") {
            $columns = array(
                'cb'                    => '<input type="checkbox" />', //Render a checkbox instead of text
                'url'                   => __('URL', 'gpagespeedi'),
                'mobile_score'          => __('Score', 'gpagespeedi'),
                'type'                  => __('Page Type', 'gpagespeedi'),
                'mobile_last_modified'  => __('Last Checked', 'gpagespeedi')
            );
        } else {
            $columns = array(
                'cb'                    => '<input type="checkbox" />', //Render a checkbox instead of text
                'url'                   => __('URL', 'gpagespeedi'),
                'desktop_score'         => __('Score (Desktop)', 'gpagespeedi'),
                'mobile_score'          => __('Score (Mobile)', 'gpagespeedi'),
                'type'                  => __('Page Type', 'gpagespeedi'),
                'desktop_last_modified' => __('Last Checked (Desktop)', 'gpagespeedi'),
                'mobile_last_modified'  => __('Last Checked (Mobile)', 'gpagespeedi')
            );
        }
        return $columns;
    }
    
    // Setup sortable columns
    function get_sortable_columns($ignored_urls = false) {
        $filter = (isset($_GET['filter'])) ? $_GET['filter'] : 'all';
        if($ignored_urls) {
            $sortable_columns = array(
                'type'          => array('type',false)
            );
        } elseif($filter == "all" || $filter == "custom_posts") {
            $sortable_columns = array(
                'desktop_score' => array('desktop_score',false),
                'mobile_score'  => array('mobile_score',false),
                'type'          => array('type',false)
            );
        } else {
            $sortable_columns = array(
                'desktop_score' => array('desktop_score',false),
                'mobile_score'  => array('mobile_score',false)
            );
        }
        return $sortable_columns;
    }

    // Setup bulk actions
    function get_bulk_actions() {
        $filter = (isset($_GET['filter'])) ? $_GET['filter'] : '';
        $render = (isset($_GET['render'])) ? $_GET['render'] : '';
        if($render == "ignored-urls") {
            $actions = array(
                'activate'          => __('Reactivate', 'gpagespeedi')
            );
        } else {
            $actions = array(
                'ignore'            => __('Ignore', 'gpagespeedi')
                //'recheck'           => __('Recheck', 'gpagespeedi')
            );
        }
        return $actions;
    }

    function extra_tablenav( $which ) {

        $gpi_options = $this->getOptions();

        // Filter report type
        $report_filter = 'all';
        if(isset($_GET['filter'])) {
            $report_filter = $_GET['filter'];
        }

        // Custom Posts Filter
        $args=array(
          'public'   => true,
          '_builtin' => false
        ); 
        $custom_post_types = get_post_types($args,'names','and');
        $subfilters['custom_posts'] = $custom_post_types;

        // Custom URLs Filter
        global $wpdb;

        $post_per_page = ( isset($_GET['post-per-page']) ) ? $_GET['post-per-page'] : 25 ;

        if ( 'top' == $which ) {
        ?>

            <div class="alignleft actions">
                <?php if( isset( $_GET['render'] ) && ( $_GET['render'] == "list" || $_GET['render'] == "summary") ) { ?>
                <select name="filter" id="filter">
                    <option value="all"><?php _e('All Reports', 'gpagespeedi'); ?></option>
                    <?php if($gpi_options['check_pages']) { ?>
                        <option <?php if($report_filter == 'pages') { echo 'selected="selected"'; } ?> value="pages"><?php _e('Pages', 'gpagespeedi'); ?></option>
                    <?php } ?>
                    <?php if($gpi_options['check_posts']) { ?>
                        <option <?php if($report_filter == 'posts') { echo 'selected="selected"'; } ?> value="posts"><?php _e('Posts', 'gpagespeedi'); ?></option>
                    <?php } ?>
                    <?php if($gpi_options['check_categories']) { ?>
                        <option <?php if($report_filter == 'categories') { echo 'selected="selected"'; } ?> value="categories"><?php _e('Categories', 'gpagespeedi'); ?></option>
                    <?php } ?>
                    <?php if($gpi_options['cpt_whitelist']) {

                        $cpt_whitelist_arr = false;
                        if(!empty($gpi_options['cpt_whitelist'])) {
                            $cpt_whitelist_arr = unserialize($gpi_options['cpt_whitelist']);
                        }

                        if($cpt_whitelist_arr) {
                        ?>
                            <optgroup label="<?php _e('Custom Post Types', 'gpagespeedi'); ?>">
                                <option <?php if($report_filter == 'gpi_custom_posts') { echo 'selected="selected"'; } ?> value="gpi_custom_posts"><?php _e('All Custom Post Types', 'gpagespeedi'); ?></option>
                                <?php
                                    $check_filter = str_replace('gpi_custom_posts-', '', $report_filter);
                                    foreach($subfilters['custom_posts'] as $filter)
                                    {
                                        if(in_array($filter, $cpt_whitelist_arr)) {
                                        ?>
                                            <option <?php if($check_filter == $filter) { echo 'selected="selected"'; } ?> value='gpi_custom_posts-<?php echo $filter; ?>'><?php echo $filter; ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </optgroup>
                            <?php
                        }
                    } ?>
                </select>
                <?php } ?>
                <?php if( isset( $_GET['render'] ) && $_GET['render'] != "summary") { ?>
                <select name="post-per-page" id="post-per-page">
                    <option value="25" <?php if($post_per_page == 25) {echo 'selected="selected"';} ?>><?php _e('25 Results/Page', 'gpagespeedi'); ?></option>
                    <option value="50" <?php if($post_per_page == 50) {echo 'selected="selected"';} ?>><?php _e('50 Results/Page', 'gpagespeedi'); ?></option>
                    <option value="100" <?php if($post_per_page == 100) {echo 'selected="selected"';} ?>><?php _e('100 Results/Page', 'gpagespeedi'); ?></option>
                    <option value="500" <?php if($post_per_page == 500) {echo 'selected="selected"';} ?>><?php _e('500 Results/Page', 'gpagespeedi'); ?></option>
                    <option value="1000" <?php if($post_per_page == 1000) {echo 'selected="selected"';} ?>><?php _e('1000 Results/Page', 'gpagespeedi'); ?></option>
                </select>
                <?php } ?>
                <?php
                submit_button( __( 'Filter', 'gpagespeedi' ), 'button', false, false, array( 'id' => 'post-query-submit' ) );
                ?>
            </div>
        <?php
        }
    }
       
    // Prepare our data for display - Active items
    function prepare_items($ignored_query = false, $type = '', $per_page = 25) {
        global $wpdb; //This is used only if making any database queries
        
        // Get options
        $gpi_options = $this->getOptions();

        // Setup Columns
        $columns = $this->gpi_get_columns($ignored_query, $gpi_options['strategy']);
        $hidden = array();
        $sortable = $this->get_sortable_columns($ignored_query);
        $this->_column_headers = array($columns, $hidden, $sortable);       
        
        // Figure out which page types we are checking (set in options)
        $typestocheck = $this->getTypesToCheck($type);

        // Not Null check for Report List scores
        switch($gpi_options['strategy']) {

            case 'both':
                $nullcheck = 'desktop_score IS NOT NULL AND mobile_score IS NOT NULL';
                break;

            case 'mobile':
                $nullcheck = 'mobile_score IS NOT NULL';
                break;

            case 'desktop':
                $nullcheck = 'desktop_score IS NOT NULL';
                break;

        }

        // Get our Data
        if(!is_null($typestocheck) && !$ignored_query) {
            $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
            if(isset($_GET['orderby'])) $orderby = $_GET['orderby'];
            if(isset($_GET['order'])) $order = $_GET['order'];

            if(isset($orderby)) {

                $data = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT ID, URL, desktop_score, mobile_score, desktop_last_modified, mobile_last_modified, type
                                FROM $gpi_page_stats
                                WHERE ($typestocheck[0])
                                AND $nullcheck
                                ORDER BY $orderby $order",
                                $typestocheck[1]
                            ),
                            ARRAY_A 
                        );

            } else {

                $data = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT ID, URL, desktop_score, mobile_score, desktop_last_modified, mobile_last_modified, type
                                FROM $gpi_page_stats
                                WHERE ($typestocheck[0])
                                AND $nullcheck",
                                $typestocheck[1]
                            ),
                            ARRAY_A 
                        );

            }
            
        } elseif($ignored_query) {
            $gpi_page_blacklist = $wpdb->prefix . 'gpi_page_blacklist';
            if(isset($_GET['orderby'])) $orderby = $_GET['orderby'];
            if(isset($_GET['order'])) $order = $_GET['order'];
            if(isset($orderby)) {

                $data = $wpdb->get_results( 
                            "SELECT ID, URL, type
                            FROM $gpi_page_blacklist
                            ORDER BY $orderby $order",
                            ARRAY_A 
                        );

            } else {

                $data = $wpdb->get_results(
                            "SELECT ID, URL, type
                            FROM $gpi_page_blacklist
                            ORDER BY ID DESC",
                            ARRAY_A
                        );

            }

        } else {
            $data = array();
        }
     
        // Figure out which page of results we are on
        $current_page = $this->get_pagenum();
        
        // Total number of results
        $total_items = count($data);
        
        // Slice up our data for Pagination
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        // Return sorted data to be used
        $this->items = $data;
        
        // Register pagination
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }
    
}

/********************************** DO ACTIONS **********************************
 ********************************************************************************/

function do_gpi_actions() {

    if(!isset($_GET['page']) || $_GET['page'] != 'google-pagespeed-insights') {
        return;
    }

    $gpi_options = get_option('gpagespeedi_options');

    if(!isset($_GET['render'])) {
    
        if($gpi_options['google_developer_key'] == '') {
            wp_redirect( '?page=' . $_REQUEST['page'] . '&render=options' );
        } else {
            wp_redirect( '?page=' . $_REQUEST['page'] . '&render=list' );
        }
        
    }

    $doaction = '';
    if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
        $doaction = $_REQUEST['action'];

    if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
        $doaction = $_REQUEST['action2'];

    if($doaction != '') {

        check_admin_referer('bulk-gpi_page_reports');

        $page_id = (isset($_GET['id'])) ? $_GET['id'] : '';
        $page_report = (isset($_GET['gpi_page_report'])) ? $_GET['gpi_page_report'] : '';

        switch($doaction)
        {
            case 'recheck':
                require_once(GPI_DIRECTORY . '/includes/actions/recheck.php');
                $recheck_urls = gpi_action_recheck_page($page_id, $page_report);
                $action_message = $recheck_urls . ' ' . __('URLs have been scheduled for a recheck. Depending on the number of URLs to check, this may take a while to complete.', 'gpagespeedi');
                break;
            case 'single-recheck':
                require_once(GPI_DIRECTORY . '/includes/actions/single-recheck.php');
                $page_id = (isset($_GET['page_id'])) ? $_GET['page_id'] : '';
                $recheck_url = gpi_action_single_recheck_page($page_id);
                $action_message = $recheck_url;
                break;
            case 'activate':
                require_once(GPI_DIRECTORY . '/includes/actions/activate.php');
                $activated_pages = gpi_action_activate_page($page_id, $page_report);
                $action_message = $activated_pages . ' ' . __('URLs have been reactivated.', 'gpagespeedi');
                break;
            case 'ignore':
                require_once(GPI_DIRECTORY . '/includes/actions/ignore.php');
                $ignored_pages = gpi_action_ignore_page($page_id, $page_report);
                $action_message = $ignored_pages . ' ' . __('URLs have been ignored.', 'gpagespeedi');
                break;
        }

        if(isset($action_message)) {
            require_once GPI_DIRECTORY . '/core/core.php';
            $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);
            $googlePagespeedInsights->google_pagespeed_insights_Update_Options('action_message',$action_message,'gpagespeedi_ui_options');        
        }
    }

    $default_strategy = ( isset($_GET['strategy']) ) ? $_GET['strategy'] : false;
    if(!empty($default_strategy)) {
        if($default_strategy == 'mobile' || $default_strategy == 'desktop') {
            require_once GPI_DIRECTORY . '/core/core.php';
            $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);
            $googlePagespeedInsights->google_pagespeed_insights_Update_Options('view_preference',$default_strategy,'gpagespeedi_ui_options');
        }
    }           

    if ( !empty($_GET['_wp_http_referer']) || !empty($_GET['action']) || !empty($_GET['action2']) || !empty($_GET['strategy']) ) {
        wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'action', 'action2', 'id', 'gpi_page_report', 'single-recheck', 'strategy' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
        exit;
    }
}
add_action( 'admin_init', 'do_gpi_actions', 9 );

/** ************************ REGISTER THE ADMIN PAGE ****************************
 ********************************************************************************/

function google_pagespeed_insights_menu(){
    global $gpi_management_page;
    $gpi_management_page = add_management_page( 'Google Pagespeed Insights', 'Pagespeed Insights', 'manage_options', 'google-pagespeed-insights', 'gpi_render_admin_page' );
}
add_action( 'admin_menu', 'google_pagespeed_insights_menu', 10 );

function load_GPI_style($hook) {

    global $gpi_management_page;
    if($hook != $gpi_management_page) return;

    wp_register_style( 'gpagespeedi_css', plugins_url('/css/gpagespeedi_styles.css', GPI_PLUGIN_FILE), false, '1.0.0' );
    wp_enqueue_style( 'gpagespeedi_css' );

    wp_register_script( 'gpagespeedi_javascript', plugins_url('/js/gpagespeedi_javascript.js', GPI_PLUGIN_FILE), array('jquery'), '1.0.0' );
    wp_register_script( 'jquery-rotate', plugins_url('/js/jQueryRotateCompressed.js', GPI_PLUGIN_FILE), array('jquery'), '1.0.0' );
    wp_enqueue_script( 'gpagespeedi_javascript' );
    wp_enqueue_script( 'jquery-rotate' );
}
add_action( 'admin_enqueue_scripts', 'load_GPI_style' );

function gpi_register_scripts($hook) {

    global $gpi_management_page;
    if($hook != $gpi_management_page) return;

    wp_enqueue_script( 'gpi-status-ajax', GPI_PUBLIC_PATH . '/js/ajax.js', array( 'jquery' ));  
    wp_localize_script( 'gpi-status-ajax', 'GPI_Ajax', array(
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'gpiNonce'      => wp_create_nonce( 'gpiNonce' ),
        'report_page'   => '?page=' . $_REQUEST['page'] . '&render=list'
        )
    );

    $gpi_options = get_option('gpagespeedi_options');

    if($gpi_options['scan_method'] == "ajax") {
        if(!$gpi_options['first_run_complete'] && $gpi_options['google_developer_key'] != '' || isset($_POST['check_new_pages']) || isset($_POST['recheck_all_pages'])) {
            if( isset( $_POST['recheck_all_pages'] ) ) {
                $recheck = 'true';
            } else {
                $recheck = 'false';
            }

            wp_enqueue_script( 'gpi-worker-ajax', GPI_PUBLIC_PATH . '/js/run_worker.js', array( 'jquery' ));  
            wp_localize_script( 'gpi-worker-ajax', 'GPI_WorkerAjax', array(
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'gpiNonce'      => wp_create_nonce( 'gpiNonce' ),
                'recheck'       => $recheck
                )
            );
        }
    }
    
}
add_action( 'admin_enqueue_scripts', 'gpi_register_scripts' );

function gpi_check_status_callback() {

    if(!wp_verify_nonce($_POST['gpiNonce'], 'gpiNonce')) {
        echo 'nonce_failure';
        exit;
    }

    $options = get_option('gpagespeedi_options');

    $current_status = $options['progress'];
    if($current_status != null) {

        $split_status = explode(':', $current_status);

        $percent_complete = $split_status[0] / $split_status[1];
        $percent_complete = round($percent_complete * 100);

        echo $percent_complete;
    } else {
        echo 'done';
    }
    exit;
}
add_action('wp_ajax_gpi_check_status', 'gpi_check_status_callback');

function gpi_run_worker_service() {

    if(!wp_verify_nonce($_POST['gpiNonce'], 'gpiNonce')) {
        echo 'nonce_failure';
        exit;
    }

    if($_POST['recheck'] == 'true') {
        $recheck = true;
    } else {
        $recheck = false;
    }

    $gpi_options = get_option('gpagespeedi_options');

    require_once GPI_DIRECTORY . '/core/core.php';
    $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);

    $googlePagespeedInsights->googlepagespeedinsightsworker( array(), true, $recheck );

    exit;
}
add_action('wp_ajax_gpi_run_worker_service', 'gpi_run_worker_service');

/***************************** RENDER ADMIN PAGES ********************************
 *********************************************************************************/

// This is our page type selector
function gpi_render_admin_page(){

    $admin_page = (isset($_GET['render'])) ? $_GET['render'] : '';
    if ( isset( $_REQUEST['render1'] ) && -1 != $_REQUEST['render1'] )
        $admin_page = $_REQUEST['render1'];

    if ( isset( $_REQUEST['render2'] ) && -1 != $_REQUEST['render2'] )
        $admin_page = $_REQUEST['render2'];

    $GPI_ListTable = new GPI_List_Table();
    $gpi_options = $GPI_ListTable->getOptions();
    $gpi_ui_options = $GPI_ListTable->getOptions('gpagespeedi_ui_options');

    ?>
    <div class="wrap">
        
        <div id="icon-gpi" class="icon32"><br/></div>
        <h2>Google Pagespeed Insights</h2>
        <div class="reportmodes">
            <?php if($gpi_options['strategy'] == 'both' || $gpi_options['strategy'] == 'desktop') { ?>
                <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&strategy=desktop" class="button-gpi desktop<?php if($gpi_ui_options['view_preference'] == "desktop") { echo ' active'; } ?>"><?php _e('Desktop Mode', 'gpagespeedi'); ?></a>
            <?php } ?>
            <?php if($gpi_options['strategy'] == 'both' || $gpi_options['strategy'] == 'mobile') { ?>
                <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&strategy=mobile" class="button-gpi mobile<?php if($gpi_ui_options['view_preference'] == "mobile") { echo ' active'; } ?>"><?php _e('Mobile Mode', 'gpagespeedi'); ?></a>
            <?php } ?>
        </div>
        <h3 class="nav-tab-wrapper">
            <a href="?page=<?php echo $_REQUEST['page'];?>&render=list" class="nav-tab <?php if($admin_page == '' || $admin_page == 'list' || $admin_page == 'ignore' || $admin_page == 'recheck'){echo 'nav-tab-active';} ?>"><?php _e('Report List', 'gpagespeedi'); ?></a>
            <?php if($admin_page == 'details') { ?>
                <a href="?page=<?php echo $_REQUEST['page'];?>&render=details&page_id=<?php echo $_GET['page_id']; ?>" class="nav-tab nav-tab-active nav-tab-temp"><?php _e('Report Details', 'gpagespeedi'); ?></a>
            <?php } ?>
            <a href="?page=<?php echo $_REQUEST['page'];?>&render=summary" class="nav-tab <?php if($admin_page == 'summary'){echo 'nav-tab-active';} ?>"><?php _e('Report Summary', 'gpagespeedi'); ?></a>
            <a href="?page=<?php echo $_REQUEST['page'];?>&render=ignored-urls" class="nav-tab <?php if($admin_page == 'ignored-urls' || $admin_page == 'activate'){echo 'nav-tab-active';} ?>"><?php _e('Ignored URLs', 'gpagespeedi'); ?></a>
            <a href="?page=<?php echo $_REQUEST['page'];?>&render=options" class="nav-tab <?php if($admin_page == 'options'){echo 'nav-tab-active';} ?>"><?php _e('Options', 'gpagespeedi'); ?></a>
            <a href="?page=<?php echo $_REQUEST['page'];?>&render=about" class="nav-tab <?php if($admin_page == 'about'){echo 'nav-tab-active';} ?>"><?php _e('About', 'gpagespeedi'); ?></a>
        </h3>

        <?php if($gpi_options['google_developer_key'] == '' && $admin_page != 'options') { ?>
            <div id="message" class="error">
                <p><strong><?php _e('You must enter your Google API key to use this plugin! Enter your API key in the', 'gpagespeedi'); ?> <a href="?page=<?php echo $_REQUEST['page'];?>&render=options"><?php _e('Options', 'gpagespeedi'); ?></a></strong>.</p>
            </div>
        <?php } ?>
        <?php if($gpi_options['bad_api_key'] && $admin_page != 'options') { ?>
            <div id="message" class="error">
                <p><strong><?php _e('The Google Pagespeed API Key you entered appears to be invalid. Please update your API key in the', 'gpagespeedi'); ?> <a href="?page=<?php echo $_REQUEST['page'];?>&render=options"><?php _e('Options', 'gpagespeedi'); ?></a></strong>.</p>
            </div>
        <?php } ?>
        <?php if($gpi_options['pagespeed_disabled'] && $admin_page != 'options') { ?>
            <div id="message" class="error">
                <p><strong><?php _e('The "PageSpeed Insights API" service is not enabled. To enable it, please visit the "Services" page from your ', 'gpagespeedi'); ?> <a href="https://code.google.com/apis/console/" target="_blank"><?php _e('Google API Console', 'gpagespeedi'); ?></a></strong>.</p>
            </div>
        <?php } ?>
        <?php if($gpi_ui_options['action_message']) { ?>
            <div id="message" class="updated">
                <p><?php echo $gpi_ui_options['action_message']; ?></p>
            </div>
        <?php } ?>
        <?php if($gpi_options['new_ignored_items']) { ?>
            <div id="message" class="error">
                <p><strong><?php _e('One or more URLs could not be reached by Google Pagespeed Insights and have automatically been added to the', 'gpagespeedi'); ?> <a href="?page=<?php echo $_REQUEST['page'];?>&render=ignored-urls"><?php _e('Ignored URLs', 'gpagespeedi'); ?></a></strong>.</p>
            </div>
        <?php } ?>
        <?php if($gpi_options['backend_error']) { ?>
            <div id="message" class="error">
                <p><strong><?php _e('An error has been encountered while checking one or more URLs. Possible causes: <br /><br />Daily API Limit Exceeded <a href="https://code.google.com/apis/console" target="_blank">Check API Usage</a> <br />API Key user limit exceeded <a href="https://code.google.com/apis/console" target="_blank">Check API Usage</a> <br />the URL is not publicly accessible or is bad. <br /><br />The URL(s) have been added to the', 'gpagespeedi'); ?> <a href="?page=<?php echo $_REQUEST['page'];?>&render=ignored-urls"><?php _e('Ignored URLs', 'gpagespeedi'); ?></a></strong></p>
            </div>
        <?php } ?>
        <?php

        require_once GPI_DIRECTORY . '/core/core.php';
        $googlePagespeedInsights = new googlePagespeedInsights($gpi_options);

        //Show currently working status on admin pages if GPI is working in the background
        $worker_status = $googlePagespeedInsights->google_pagespeed_insights_Check_Status();
        if($worker_status) { ?>
            <div id="message" class="updated">
                <span><p id="gpi_status_finished" style="font-size: 13px; display: none;"><?php _e('Google Pagespeed has finished checking pagespeed scores. <a href="javascript:location.reload(true);">Refresh to see new results.</a>', 'gpagespeedi'); ?></p><p id="gpi_status_ajax" style="font-size: 13px;"><?php _e('Google Pagespeed is running in the background. Progress...', 'gpagespeedi'); ?></p></span>
            </div>
        <?php }

        //Clear any one-time messages from above
        $googlePagespeedInsights->google_pagespeed_insights_Update_Options('backend_error',false,'gpagespeedi_options');
        $googlePagespeedInsights->google_pagespeed_insights_Update_Options('action_message',false,'gpagespeedi_ui_options');
        $googlePagespeedInsights->google_pagespeed_insights_Update_Options('new_ignored_items',false,'gpagespeedi_options');

        $default_strategy = $gpi_ui_options['view_preference'];

        switch($admin_page)
        {
            case 'list':
                require_once(GPI_DIRECTORY . '/includes/admin/list.php');
                gpi_render_list_page();
                break;
            case 'ignored-urls':
                require_once(GPI_DIRECTORY . '/includes/admin/ignored-urls.php');
                gpi_render_ignored_urls_page();
                break;
            case 'options':
                require_once(GPI_DIRECTORY . '/includes/admin/options.php');
                gpi_render_options_page();
                break;
            case 'details':
                $page_id = (isset($_GET['page_id'])) ? $_GET['page_id'] : '';
                require_once(GPI_DIRECTORY . '/includes/admin/details.php');
                gpi_render_details_page($default_strategy, $page_id);
                break;
            case 'summary':
                require_once(GPI_DIRECTORY . '/includes/admin/summary.php');
                gpi_render_summary_page($default_strategy);
                break;
            case 'about':
                require_once(GPI_DIRECTORY . '/includes/admin/about.php');
                gpi_render_about();
                break;
            default:
                require_once(GPI_DIRECTORY . '/includes/admin/list.php');
                gpi_render_list_page();
                break;
        }
        ?>
    </div>
    <?php

}
