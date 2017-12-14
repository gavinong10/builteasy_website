<?php
/**
 * seed_ucp Admin
 *
 * @package WordPress
 * @subpackage seed_ucp
 * @since 0.1.0
 */


class SEED_UCP_ADMIN
{
    public $plugin_version = SEED_UCP_VERSION;
    public $plugin_name = SEED_UCP_PLUGIN_NAME;

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

   /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Load Hooks
     */
    function __construct( )
    {
        if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ){
            add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts'  ) );
            add_action( 'admin_menu', array( &$this, 'create_menus'  ) );
            add_action( 'admin_init', array( &$this, 'reset_defaults' ) );
            add_action( 'admin_init', array( &$this, 'create_settings' ) );
            add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
        }
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
     * Reset the settings page. Reset works per settings id.
     *
     */
    function reset_defaults( )
    {
        if ( isset( $_POST[ 'seed_ucp_reset' ] ) ) {
            $option_page = $_POST[ 'option_page' ];
            check_admin_referer( $option_page . '-options' );
            require_once(SEED_UCP_PLUGIN_PATH.'inc/default-settings.php');

            $_POST[ $_POST[ 'option_page' ] ] = $seed_ucp_settings_deafults[$_POST[ 'option_page' ]];
            add_settings_error( 'general', 'seed_ucp-settings-reset', __( "Settings reset.", 'under-construction-wp' ), 'updated' );
        }
    }

    /**
     * Properly enqueue styles and scripts for our theme options page.
     *
     * This function is attached to the admin_enqueue_scripts action hook.
     *
     * @since  0.1.0
     * @param string $hook_suffix The name of the current page we are on.
     */
    function admin_enqueue_scripts( $hook_suffix )
    {
        if ( $hook_suffix != $this->plugin_screen_hook_suffix )
            return;

        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'wp-lists' );
        wp_enqueue_script( 'seed_ucp-framework-js', SEED_UCP_PLUGIN_URL . 'framework/settings-scripts.js', array( 'jquery' ), $this->plugin_version );
        wp_enqueue_script( 'theme-preview' );
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'media-upload' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'seed_ucp-framework-css', SEED_UCP_PLUGIN_URL . 'framework/settings-style.css', false, $this->plugin_version );
        wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', false, $this->plugin_version );
    }

    /**
     * Creates WordPress Menu pages from an array in the config file.
     *
     * This function is attached to the admin_menu action hook.
     *
     * @since 0.1.0
     */
    function create_menus( )
    {
      $this->plugin_screen_hook_suffix = add_options_page(
            __( "Under Construction", 'under-construction-wp' ),
            __( "Under Construction", 'under-construction-wp' ),
            'manage_options',
            'seed_ucp',
            array( &$this , 'option_page' )
            );
    }

    /**
     * Display settings link on plugin page
     */
    function plugin_action_links( $links, $file )
    {
        $plugin_file = SEED_UCP_SLUG;

        if ( $file == $plugin_file ) {
            $settings_link = '<a href="options-general.php?page=seed_ucp">Settings</a>';
            array_unshift( $links, $settings_link );
        }
        return $links;
    }


    /**
     * Allow Tabs on the Settings Page
     *
     */
    function plugin_options_tabs( )
    {
        $menu_slug   = null;
        $page        = $_REQUEST[ 'page' ];
        $uses_tabs   = false;
        $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : false;

        //Check if this config uses tabs
        foreach ( seed_ucp_get_options() as $v ) {
            if ( $v[ 'type' ] == 'tab' ) {
                $uses_tabs = true;
                break;
            }
        }

        // If uses tabs then generate the tabs
        if ( $uses_tabs ) {
            echo '<h2 class="nav-tab-wrapper" style="padding-left:20px">';
            $c = 1;
            foreach ( seed_ucp_get_options() as $v ) {
                    if ( isset( $v[ 'menu_slug' ] ) ) {
                        $menu_slug = $v[ 'menu_slug' ];
                    }
                    if ( $menu_slug == $page && $v[ 'type' ] == 'tab' ) {
                        $active = '';
                        if ( $current_tab ) {
                            $active = $current_tab == $v[ 'id' ] ? 'nav-tab-active' : '';
                        } elseif ( $c == 1 ) {
                            $active = 'nav-tab-active';
                        }
                        echo '<a class="nav-tab ' . $active . '" href="?page=' . $menu_slug . '&tab=' . $v[ 'id' ] . '">' . $v[ 'label' ] . '</a>';
                        $c++;
                    }
            }
            echo '<a class="nav-tab seed_ucp-preview thickbox-preview" target="_blank" href="'.home_url().'?seed_ucp_preview=true&TB_iframe=true&width=640&height=632" title="'.__('&larr; Close Window', 'under-construction-wp').'">'.__('Live Preview', 'under-construction-wp').'</a>';
            echo '<a class="nav-tab seed_csp4-support" style="background-color: #fcf8e3;" href="http://www.seedprod.com/ultimate-coming-soon-page-vs-coming-soon-pro/?utm_source=under-construction-plugin&amp;utm_medium=banner&amp;utm_campaign=under-construction-link-in-plugin" target="_blank"> Upgrade to Pro for more Professional Features</a>';
            echo '</h2>';

        }
    }

    /**
     * Get the layout for the page. classic|2-col
     *
     */
    function get_page_layout( )
    {
        $layout = 'classic';
        foreach ( seed_ucp_get_options() as $v ) {
            switch ( $v[ 'type' ] ) {
                case 'menu';
                    $page = $_REQUEST[ 'page' ];
                    if ( $page == $v[ 'menu_slug' ] ) {
                        if ( isset( $v[ 'layout' ] ) ) {
                            $layout = $v[ 'layout' ];
                        }
                    }
                    break;
            }
        }
        return $layout;
    }

    /**
     * Render the option pages.
     *
     * @since 0.1.0
     */
    function option_page( )
    {
        $menu_slug = null;
        $page   = $_REQUEST[ 'page' ];
        $layout = $this->get_page_layout();
        ?>
        <div class="wrap columns-2 seed-ucp">
        <?php screen_icon(); ?>
            <h2><?php echo $this->plugin_name; ?> <span class="seed_ucp-version"> <?php echo SEED_UCP_VERSION; ?></span></h2>
            <?php //settings_errors() ?>
            <?php $this->plugin_options_tabs(); ?>
            <?php if ( $layout == '2-col' ): ?>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content" >
            <?php endif; ?>
                    <?php if(!empty($_GET['tab']))
                            do_action( 'seed_ucp_render_page', array('tab'=>$_GET['tab']));
                    ?>
                    <form action="options.php" method="post">

                    <!-- <input name="submit" type="submit" value="<?php _e( 'Save All Changes', 'under-construction-wp' ); ?>" class="button-primary"/> -->
                    <?php if(!empty($_GET['tab']) && $_GET['tab'] != 'seed_ucp_tab_3') { ?>
                    <!-- <input id="reset" name="reset" type="submit" value="<?php _e( 'Reset Settings', 'under-construction-wp' ); ?>" class="button-secondary"/>     -->
                    <?php } ?>

                            <?php
                            $show_submit = false;
                            foreach ( seed_ucp_get_options() as $v ) {
                                if ( isset( $v[ 'menu_slug' ] ) ) {
                                    $menu_slug = $v[ 'menu_slug' ];
                                }
                                    if ( $menu_slug == $page ) {
                                        switch ( $v[ 'type' ] ) {
                                            case 'menu';
                                                break;
                                            case 'tab';
                                                $tab = $v;
                                                if ( empty( $default_tab ) )
                                                    $default_tab = $v[ 'id' ];
                                                break;
                                            case 'setting':
                                                $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
                                                if ( $current_tab == $tab[ 'id' ] ) {
                                                    settings_fields( $v[ 'id' ] );
                                                    $show_submit = true;
                                                }

                                                break;
                                            case 'section':
                                                $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
                                                if ( $current_tab == $tab[ 'id' ] or $current_tab === false ) {
                                                    if ( $layout == '2-col' ) {
                                                        echo '<div id="'.$v[ 'id' ].'" class="postbox seedprod-postbox">';
                                                        $this->do_settings_sections( $v[ 'id' ],$show_submit );
                                                        echo '</div>';
                                                    } else {
                                                        do_settings_sections( $v[ 'id' ] );
                                                    }

                                                }
                                                break;

                                        }

                                }
                            }
                        ?>
                    <?php if($show_submit): ?>
                    <p>
                    <!-- <input name="submit" type="submit" value="<?php _e( 'Save All Changes', 'under-construction-wp' ); ?>" class="button-primary"/> -->
                    <!-- <input id="reset" name="reset" type="submit" value="<?php _e( 'Reset Settings', 'under-construction-wp' ); ?>" class="button-secondary"/> -->
                    </p>
                    <?php endif; ?>
                    </form>

                    <?php if ( $layout == '2-col' ): ?>
                    </div> <!-- #post-body-content -->

                    <div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
<a href="http://www.seedprod.com/ultimate-coming-soon-page-vs-coming-soon-pro/?utm_source=plugin&amp;utm_medium=banner&amp;utm_campaign=under-construction-in-plugin-banner" target="_blank"><img src="http://static.seedprod.com/ads/coming-soon-pro-sidebar.png"></a>
                            <br> <br>
                            <div class="postbox support-postbox" style="background-color:#d9edf7">
                                <div class="handlediv" title="Click to toggle"><br /></div>
                                <h3 class="hndle"><span><?php _e('Plugin Support', 'under-construction-wp') ?></span></h3>
                                <div class="inside">
                                    <div class="support-widget">
                                        <p>
                                            <?php _e('Got a Question, Idea, Problem or Praise?','under-construction-wp') ?>
                                        </p>
                                        <ul>
                                            <li>&raquo; <a href="https://wordpress.org/support/plugin/under-construction-wp" target="_blank"><?php _e('Support Request', 'under-construction-wp') ?></a></li>
                                            <li>&raquo; <a href="http://support.seedprod.com/article/83-how-to-clear-wp-super-caches-cache" target="_blank"><?php _e('Common Caching Issues Resolutions', 'under-construction-wp') ?></a></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <?php if($this->plugin_type != 'pro'){ ?>
                                <div class="postbox like-postbox" style="background-color:#d9edf7">
                                    <div class="handlediv" title="Click to toggle"><br /></div>
                                    <h3 class="hndle"><span><?php _e('Show Some Love', 'under-construction-wp') ?></span></h3>
                                    <div class="inside">
                                        <div class="like-widget">
                                            <p><?php _e('Like this plugin? Show your support by:', 'under-construction-wp')?></p>
                                            <ul>
                                                <li>&raquo; <a target="_blank" href="http://www.seedprod.com/features/?utm_source=under-construction-plugin&utm_medium=banner&utm_campaign=under-construction-link-in-plugin"><?php _e('Buy It', 'under-construction-wp') ?></a></li>

                                                <li>&raquo; <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/under-construction-wp?rate=5#postform"><?php _e('Rate It', 'under-construction-wp') ?></a></li>
                                                <li>&raquo; <a target="_blank" href="<?php echo "http://twitter.com/share?url=https%3A%2F%2Fwordpress.org%2Fplugins%2Funder-construction%2F&text=Check out this awesome %23WordPress Plugin I'm using, Under Construction by SeedProd"; ?>"><?php _e('Tweet It', 'under-construction-wp') ?></a></li>

                                                <li>&raquo; <a href="https://www.seedprod.com/submit-site/"><?php _e('Submit your site to the Showcase', 'under-construction-wp') ?></a></li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>


                                <div class="postbox rss-postbox" style="background-color:#d9edf7">
    											<div class="handlediv" title="Click to toggle"><br /></div>
    											<h3 class="hndle"><span><?php _e('SeedProd Blog', 'under-construction-wp') ?></span></h3>
    											<div class="inside">

    												<div class="rss-widget">
    													<?php
    													wp_widget_rss_output(array(
    													'url' => 'http://feeds.feedburner.com/seedprod/',
    													'title' => 'SeedProd Blog',
    													'items' => 3,
    													'show_summary' => 0,
    													'show_author' => 0,
    													'show_date' => 1,
    												));
    												?>
    												<ul>
    													<br>
    												<li>&raquo; <a href="https://www.getdrip.com/forms/9414625/submissions/new"><?php _e('Subscribe by Email', 'under-construction-wp') ?></a></li>
    											</ul>
    										</div>
    									</div>
    								</div>

                        </div>
                    </div>
                </div> <!-- #post-body -->


            </div> <!-- #poststuff -->
            <?php endif; ?>
        </div> <!-- .wrap -->

        <!-- JS login to confirm setting resets. -->
        <script>
            jQuery(document).ready(function($) {
                $('#reset').click(function(e){
                    if(!confirm('<?php _e( 'This tabs settings be deleted and reset to the defaults. Are you sure you want to reset?', 'under-construction-wp' ); ?>')){
                        e.preventDefault();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Create the settings options, sections and fields via the WordPress Settings API
     *
     * This function is attached to the admin_init action hook.
     *
     * @since 0.1.0
     */
    function create_settings( )
    {
        foreach ( seed_ucp_get_options() as $k => $v ) {

            switch ( $v[ 'type' ] ) {
                case 'menu':
                    $menu_slug = $v[ 'menu_slug' ];

                    break;
                case 'setting':
                    if ( empty( $v[ 'validate_function' ] ) ) {
                        $v[ 'validate_function' ] = array(
                             &$this,
                            'validate_machine'
                        );
                    }
                    register_setting( $v[ 'id' ], $v[ 'id' ], $v[ 'validate_function' ] );
                    $setting_id = $v[ 'id' ];
                    break;
                case 'section':
                    if ( empty( $v[ 'desc_callback' ] ) ) {
                        $v[ 'desc_callback' ] = array(
                             &$this,
                            '__return_empty_string'
                        );
                    } else {
                        $v[ 'desc_callback' ] = $v[ 'desc_callback' ];
                    }
                    add_settings_section( $v[ 'id' ], $v[ 'label' ], $v[ 'desc_callback' ], $v[ 'id' ] );
                    $section_id = $v[ 'id' ];
                    break;
                case 'tab':
                    break;
                default:
                    if ( empty( $v[ 'callback' ] ) ) {
                        $v[ 'callback' ] = array(
                             &$this,
                            'field_machine'
                        );
                    }

                    add_settings_field( $v[ 'id' ], $v[ 'label' ], $v[ 'callback' ], $section_id, $section_id, array(
                         'id' => $v[ 'id' ],
                        'desc' => ( isset( $v[ 'desc' ] ) ? $v[ 'desc' ] : '' ),
                        'setting_id' => $setting_id,
                        'class' => ( isset( $v[ 'class' ] ) ? $v[ 'class' ] : '' ),
                        'type' => $v[ 'type' ],
                        'default_value' => ( isset( $v[ 'default_value' ] ) ? $v[ 'default_value' ] : '' ),
                        'option_values' => ( isset( $v[ 'option_values' ] ) ? $v[ 'option_values' ] : '' )
                    ) );

            }
        }
    }

    /**
     * Create a field based on the field type passed in.
     *
     * @since 0.1.0
     */
    function field_machine( $args )
    {
        extract( $args ); //$id, $desc, $setting_id, $class, $type, $default_value, $option_values

        // Load defaults
        $defaults = array( );
        foreach ( seed_ucp_get_options() as $k ) {
            switch ( $k[ 'type' ] ) {
                case 'setting':
                case 'section':
                case 'tab':
                    break;
                default:
                    if ( isset( $k[ 'default_value' ] ) ) {
                        $defaults[ $k[ 'id' ] ] = $k[ 'default_value' ];
                    }
            }
        }
        $options = get_option( $setting_id );

        $options = wp_parse_args( $options, $defaults );

        $path = SEED_UCP_PLUGIN_PATH . 'framework/field-types/' . $type . '.php';
        if ( file_exists( $path ) ) {
            // Show Field
            include( $path );
            // Show description
            if ( !empty( $desc ) ) {
                echo "<small class='description'>{$desc}</small>";
            }
        }

    }

    /**
     * Validates user input before we save it via the Options API. If error add_setting_error
     *
     * @since 0.1.0
     * @param array $input Contains all the values submitted to the POST.
     * @return array $input Contains sanitized values.
     * @todo Figure out best way to validate values.
     */
    function validate_machine( $input )
    {
        $option_page = $_POST['option_page'];
        foreach ( seed_ucp_get_options() as $k ) {
            switch ( $k[ 'type' ] ) {
                case 'menu':
                case 'setting':
                    if(isset($k['id']))
                        $setting_id = $k['id'];
                case 'section':
                case 'tab';
                    break;
                default:
                    if ( !empty( $k[ 'validate' ] ) && $setting_id == $option_page ) {
                        $validation_rules = explode( ',', $k[ 'validate' ] );

                        foreach ( $validation_rules as $v ) {
                            $path = SEED_UCP_PLUGIN_PATH . 'framework/validations/' . $v . '.php';
                            if ( file_exists( $path ) ) {
                                // Defaults Values
                                $is_valid  = true;
                                $error_msg = '';

                                // Test Validation
                                include( $path );

                                // Is it valid?
                                if ( $is_valid === false ) {
                                    add_settings_error( $k[ 'id' ], 'seedprod_error', $error_msg, 'error' );
                                    // Unset invalids
                                    unset( $input[ $k[ 'id' ] ] );
                                }

                            }
                        } //end foreach

                    }
            }
        }

        return $input;
    }

    /**
     * Dummy function to be called by all sections from the Settings API. Define a custom function in the config.
     *
     * @since 0.1.0
     * @return string Empty
     */
    function __return_empty_string( )
    {
        echo '';
    }


    /**
     * SeedProd version of WP's do_settings_sections
     *
     * @since 0.1.0
     */
    function do_settings_sections( $page, $show_submit )
    {
        global $wp_settings_sections, $wp_settings_fields;

        if ( !isset( $wp_settings_sections ) || !isset( $wp_settings_sections[ $page ] ) )
            return;

        foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
            echo "<h3 class='hndle'>{$section['title']}</h3>\n";
            echo '<div class="inside">';
            call_user_func( $section[ 'callback' ], $section );
            if ( !isset( $wp_settings_fields ) || !isset( $wp_settings_fields[ $page ] ) || !isset( $wp_settings_fields[ $page ][ $section[ 'id' ] ] ) )
                continue;
            echo '<table class="form-table">';
            $this->do_settings_fields( $page, $section[ 'id' ] );
            echo '</table>';
            if($show_submit): ?>
                <p>
                <input name="submit" type="submit" value="<?php _e( 'Save All Changes', 'under-construction-wp' ); ?>" class="button-primary"/>
                </p>
            <?php endif;
            echo '</div>';
        }
    }

    function do_settings_fields($page, $section) {
          global $wp_settings_fields;

          if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) )
              return;

          foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
              echo '<tr valign="top">';
              if ( !empty($field['args']['label_for']) )
                  echo '<th scope="row"><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label></th>';
              else
                  echo '<th scope="row"><strong>' . $field['title'] . '</strong><!--<br>'.$field['args']['desc'].'--></th>';
              echo '<td>';
              call_user_func($field['callback'], $field['args']);
              echo '</td>';
              echo '</tr>';
          }
      }

}
