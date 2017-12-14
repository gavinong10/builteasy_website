<?php
/**
 * Plugin class logic goes here
 */
class SEED_UCP{

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

	private $underconstruction_rendered = false;

	function __construct(){

			extract(seed_ucp_get_settings());

            // Actions & Filters if the landing page is active or being previewed
            if(((!empty($status) && $status === '1') || (!empty($status) && $status === '2')) || (isset($_GET['seed_ucp_preview']) && $_GET['seed_ucp_preview'] == 'true')){
            	if(function_exists('bp_is_active')){
                    add_action( 'template_redirect', array(&$this,'render_underconstruction_page'),9);
                }else{
                    add_action( 'template_redirect', array(&$this,'render_underconstruction_page'));
                }
                add_action( 'admin_bar_menu',array( &$this, 'admin_bar_menu' ), 1000 );
            }

            // Add this script globally so we can view the notification across the admin area
            add_action( 'admin_enqueue_scripts', array(&$this,'add_scripts') );
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Display admin bar when active
     */
    function admin_bar_menu($str){
        global $seed_ucp_settings,$wp_admin_bar;
        extract($seed_ucp_settings);

        if(!isset($status)){
            return false;
        }

        $msg = '';
        if($status == '1'){
        	$msg = __('Under Construction Mode Active', 'under-construction-wp');
        }elseif($status == '2'){
        	$msg = __('Maintenance Mode Active', 'under-construction-wp');
        }
    	//Add the main siteadmin menu item
        $wp_admin_bar->add_menu( array(
            'id'     => 'seed-ucp-notice',
            'href' => admin_url().'options-general.php?page=seed_ucp',
            'parent' => 'top-secondary',
            'title'  => $msg,
            'meta'   => array( 'class' => 'csp4-mode-active' ),
        ) );
    }

    /**
     * Display the default template
     */
    function get_default_template(){
        $file = file_get_contents(SEED_UCP_PLUGIN_PATH.'/themes/default/index.php');
        return $file;
    }

	/**
     * Load scripts
     */
    function add_scripts($hook) {
        wp_enqueue_style( 'seed-ucp-adminbar-notification', SEED_UCP_PLUGIN_URL.'inc/adminbar-style.css', false, SEED_UCP_VERSION, 'screen');
    }

    /**
     * Display the Under Construction page
     */
    function render_underconstruction_page() {

    	extract(seed_ucp_get_settings());

        if(!isset($status)){
            $err =  new WP_Error('error', __("Please enter your settings.", 'under-construction-wp'));
            echo $err->get_error_message();
            exit();
        }


        if(empty($_GET['seed_ucp_preview'])){
            $_GET['seed_ucp_preview'] = false;
        }

        // Check if Preview
        $is_preview = false;
        if ((isset($_GET['seed_ucp_preview']) && $_GET['seed_ucp_preview'] == 'true')) {
            $is_preview = true;
        }

        // Exit if a custom login page
        if(empty($disable_default_excluded_urls)){
            if(preg_match("/login|admin|dashboard|account/i",$_SERVER['REQUEST_URI']) > 0 && $is_preview == false){
                return false;
            }
        }


        // Check if user is logged in.
        if($is_preview === false){
            if(is_user_logged_in()){
                return false;
            }
        }


        // Finally check if we should show the Under Construction page.
        $this->underconstruction_rendered = true;

        // custom html
        if(!empty($custom_html)){
            echo $custom_html;
            exit();
        }

        // set headers
        if($status == '2'){
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 86400'); // retry in a day
            $csp4_maintenance_file = WP_CONTENT_DIR."/maintenance.php";
            if(!empty($enable_maintenance_php) and file_exists($csp4_maintenance_file)){
                include_once( $csp4_maintenance_file );
                exit();
            }
        }

        // render template tags

        $template = $this->get_default_template();
        require_once( SEED_UCP_PLUGIN_PATH.'/themes/default/functions.php' );
        $template_tags = array(
            '{Title}' => seed_ucp_title(),
            '{Privacy}' => seed_ucp_privacy(),
            '{Head}' => seed_ucp_head(),
            '{Description}' => seed_ucp_description(),
            '{Credit}' => seed_ucp_credit(),
            );
		echo strtr($template, $template_tags);
        exit();

    }

}
