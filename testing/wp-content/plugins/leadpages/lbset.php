<?php
add_action('admin_menu', 'lp_add_admin_menu');
add_action('admin_init', 'lp_settings_init');
add_filter('the_content', 'lp_leadbox_add');

$api_url = 'https://lead-pages.appspot.com/api/';
$ak = WP_PLUGIN_DIR . '/leadpages/api_key.php';

function set_api_key($key)
{
    update_option('leadpages_private_api_key', $key);
}

function _plugin_get($i)
{
    if (!function_exists('get_plugins')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $plugin_folder = get_plugins('/leadpages');

    return $plugin_folder['leadpages.php'][$i];
}

function get_url_ver()
{
    return '?lp-ver=' . _plugin_get('Version');
}

if (file_exists($ak)) {
    require_once($ak);
    if (defined('PRIVATE_LEADPAGES_API_KEY')) {
        $old = get_api_key();
        if ($old != PRIVATE_LEADPAGES_API_KEY) {
            set_api_key(PRIVATE_LEADPAGES_API_KEY);
        }
    }
}

$api_key = get_api_key();

if (defined('PRIVATE_LEADPAGES_API_URL')) {
    $api_url = PRIVATE_LEADPAGES_API_URL;
    $lb_api_url = str_replace('/api/', '/_ah/api', $api_url);
    $lb_api_url = str_replace('http:', 'https:', $lb_api_url);
    $lb_api_url = str_replace('https://my.leadpages.net/', 'https://lead-pages.appspot.com/', $lb_api_url);
    $lb_api_url = str_replace('https://my.leadpagestest.net/', 'https://leadpage-test.appspot.com/', $lb_api_url);
    $api_url = $lb_api_url;
}


if ($api_key == false) {
    show_message(false, 'Missing LeadPages API key! Please deactivate and delete this plugin, re-download the plugin and install again or follow our <a href="https://support.leadpages.net/entries/21804555-Missing-LeadPages-API-key-Please-contact-support-Error-Message" target="_blank">instructions</a>.');

    return false;
}

function get_api_key()
{
    return get_option('leadpages_private_api_key', false);
}

function lp_add_admin_menu()
{
    $menu_icon = plugin_dir_url(__FILE__) . 'admin/img/leadboxes_sm.png';

    add_menu_page('LeadBoxes', 'LeadBoxes', 'manage_options', 'leadboxes', 'leadboxes_options_page', $menu_icon);
}

function lp_settings_exist()
{
    if (false == get_option('lp_settings')) {
        add_option('lp_settings');
    }
}

/**
 * Check location against settings and adds LeadBox code if appropriate.
 *
 * @param $select_field
 * @param $lb_type
 * @return string
 */
function lb_add($select_field, $lb_type)
{
    // Return the LB embed code if appropriate for where we are.
    $lb_settings = get_option('lp_settings');
    $lb_id = $lb_settings[$select_field];
    $content = '';
    if ('x' != $lb_id) {
        $where = $lb_settings['leadboxes_' . $lb_type . '_display_radio'];
        if ('posts' == $where && !is_page(get_the_ID())) {
            $content .= lb_get_embed($lb_id, $lb_type);
        } elseif ('all' == $where) {
            $content .= lb_get_embed($lb_id, $lb_type);
        }
    }
    return $content;
}

/**
 * Get the API path for the specified LeadPages API.
 *
 * @param $api_path
 * @return string
 */
function lb_api_url($api_path)
{
    global $api_url;
    $lb_api_url = str_replace('/api/', '/_ah/api', $api_url);
    $html_api_url = $lb_api_url . '/leadpages/v1.0/' . $api_path;
    return $html_api_url;
}

/**
 * Get the LeadBox HTML API path
 *
 * @return string
 */
function lb_html_api_url()
{
    return lb_api_url('leadbox/html');
}

/**
 * Get embed code for given LeadBox $lb_id of type $lb_type
 *
 * @param $lb_id
 * @param $lb_type
 * @return string
 */
function lb_get_embed($lb_id, $lb_type)
{
    $args = array(
        'timeout' => '5'
    );
    if ($lb_id && $lb_id != 'x') {
        $timed_url = lb_html_api_url() . '?id=' . $lb_id . '&api_key=' . get_api_key() . '&lb_type=' . $lb_type;
        $response = wp_remote_get($timed_url, $args);
        $error_text = "<div class='error'>There was an issue loading your " . $lb_type .
            " LeadBox&trade;. Please check plugin settings.</div>";
        if (is_wp_error($response)) {
            return $error_text;
        } else {
            try {
                $lb_response = json_decode($response['body']);
                if (property_exists($lb_response, 'html')) {
                    return $lb_response->html;
                } else {
                    return $error_text;
                }
            } catch (Exception $ex) {
                return $error_text;
            }
        }
    }
    return '';
}

/**
 * Filter added to add LeadBoxes to the page content.
 *
 * @param $content
 * @return string
 */
function lp_leadbox_add($content)
{
    // The lines below are some ideas for future enhancement -
    // more flexibility in where the LBs are shown.
//  $lb_ignore_pages = get_option ( 'lb_ignore_pages' );
//  if ( is_array( $lb_ignore_pages ) && in_array( get_the_ID(), $lb_ignore_pages ) )
//    return $content;
//  if ( is_home() && get_option( 'lb_omit_home' ) == 1 )
//    return $content;
//  if ( is_front_page() && get_option( 'lb_omit_front' ) == 1 )
//    return $content;
//  if ( is_category() && get_option( 'lb_omit_cat' ) == 1 )
//    return $content;
//  if ( is_tag() && get_option( 'lb_omit_tag' ) == 1 )
//    return $content;
//  if ( is_date() && get_option( 'lb_omit_date' ) == 1 )
    //    return $content;
    $content .= lb_add('lp_select_field_0', 'timed');
    $content .= lb_add('lp_select_field_2', 'exit');
    return $content;
}

function lp_settings_init()
{
    register_setting('pluginPage', 'lp_settings');
    add_settings_section(
        'lp_timed_section',
        __('Timed LeadBox&trade; Popup Configuration', 'wordpress'),
        'lp_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'lp_select_timed_leadbox_0',
        __('Choose LeadBox&trade;', 'wordpress'),
        'lp_select_field_0_render',
        'pluginPage',
        'lp_timed_section'
    );
    add_settings_field(
        'leadboxes_timed_display_radio',
        __('Show LeadBox&trade; On', 'wordpress'),
        'lp_timed_radio_render',
        'pluginPage',
        'lp_timed_section'
    );

    add_settings_section(
        'lp_exit_section',
        __('Exit LeadBox&trade; Configuration', 'wordpress'),
        'lp_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'lp_select_exit_leadbox_0',
        __('Choose LeadBox&trade;', 'wordpress'),
        'lp_select_field_2_render',
        'pluginPage',
        'lp_exit_section'
    );
    add_settings_field(
        'leadboxes_exit_display_radio',
        __('Show LeadBox&trade; On', 'wordpress'),
        'lp_exit_radio_render',
        'pluginPage',
        'lp_exit_section'
    );
}


function lp_select_field_0_render()
{
    ?>
    <select name='lp_settings[lp_select_field_0]' id='select_timed' title="Timed LeadBox">
        <option value="x">None</option>
    </select>
<?php
}

function lp_timed_radio_render()
{
    $options = get_option('lp_settings');
    if (!is_array($options)) {
        $timed_display = 'all';
    } elseif (array_key_exists('leadboxes_timed_display_radio', $options)) {
        $timed_display = $options['leadboxes_timed_display_radio'];
    } else {
        $timed_display = 'all';
    }
    ?>
    <input type='radio' id="timed_radio_all" name='lp_settings[leadboxes_timed_display_radio]'
        <?php checked('all', $timed_display); ?> value='all'>
    <label for="timed_radio_all">Every WordPress page, including homepage, 404 and posts</label>
    <br/>
    <input type='radio' id="timed_radio_posts" name='lp_settings[leadboxes_timed_display_radio]'
        <?php checked('posts', $timed_display); ?> value='posts'>
    <label for="timed_radio_posts">Only on posts</label>
<?php
}

function lp_select_field_2_render()
{
    ?>
    <select name='lp_settings[lp_select_field_2]' id='select_exit' title="Exit LeadBox">
        <option value="x">None</option>
    </select>
<?php
}

function lp_exit_radio_render()
{
    $options = get_option('lp_settings');
    if (!is_array($options)) {
        $exit_display = 'all';
    } elseif (array_key_exists('leadboxes_exit_display_radio', $options)) {
        $exit_display = $options['leadboxes_exit_display_radio'];
    } else {
        $exit_display = 'all';
    }
    ?>
    <input type='radio' id="exit_radio_all" name='lp_settings[leadboxes_exit_display_radio]'
        <?php checked('all', $exit_display); ?> value='all'>
    <label for="exit_radio_all">Every WordPress page, including homepage, 404 and posts</label>
    <br/>
    <input type='radio' id="exit_radio_posts" name='lp_settings[leadboxes_exit_display_radio]'
        <?php checked('posts', $exit_display); ?> value='posts'>
    <label for="exit_radio_posts">Only on posts</label>
<?php
}

function lp_settings_section_callback($sec)
{
    echo '<div id=' . $sec['id'] . '></div>';
    if ($sec['id'] === 'lp_exit_section') {
        echo '<p>All your LeadBoxes&reg; are listed below.
Any LeadBoxes&reg; without Exit configuration will default to display every time a
user visits your page.  <a href="https://my.leadpages.net">Go to our application</a> to use your own settings.</p>';
    } else {
        echo '<p>All your LeadBoxes&reg; with Timed configuration are listed below.
    <a href="https://my.leadpages.net">Go to our application</a> to save or edit Timed settings for your LeadBoxes&reg;.</p>';
    }
}

function leadboxes_options_page()
{
    $v = get_url_ver();
    $admin_url = LEADPAGES_ABS_URL . 'admin/';
    global $api_url;
    $lb_api_url = str_replace('/api/', '/_ah/api', $api_url);
    wp_register_script('lb_options', $admin_url . 'js/leadbox-settings.js' . $v, array('leadpages_jquery'));
    wp_enqueue_script('lb_options');

    wp_register_script('leadpages_jquery', $admin_url . 'js/jquery_leadpages.js' . $v);
    wp_register_script('leadpages_bootstrap', $admin_url . 'js/twitter_bootstrap_leadpages.js' . $v, array('leadpages_jquery'));
    wp_register_style('leadpages_bootstrap', $admin_url . 'css/twitter_bootstrap_leadpages.css' . $v);
    wp_register_style('leadpages_admin', $admin_url . 'css/admin_leadpages.css' . $v);
    wp_enqueue_script('leadpages_jquery');
    wp_enqueue_script('leadpages_bootstrap');
    wp_enqueue_style('leadpages_bootstrap');
    wp_enqueue_style('leadpages_admin');
    ?>
    <!--suppress HtmlUnknownTarget -->
    <form action='options.php' method='post'>

        <h2>Configure LeadBoxes&reg;</h2>

        <p>Here you can setup timed and exit LeadBoxes&reg;. If you want to place a LeadBox&trade;
            via link, button, or image to any page, you need to copy and paste the HTML code you'll find
            in the LeadBox&trade; publish interface inside the LeadPages&trade; application.</p>

        <div id="leadbox-loading" style="width: 20%">Loading
            <div class="spinner"></div>
        </div>
        <div id="leadbox-options">
            <?php
            $options = get_option('lp_settings');
            $selected_timed = $options['lp_select_field_0'];
            $selected_exit = $options['lp_select_field_2'];

            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>
        </div>

    </form>
    <script type="text/javascript">
        var selected_timed = "<?php echo $selected_timed ?>";
        var selected_exit = "<?php echo $selected_exit  ?>";
        var api_key = "<?php echo get_api_key() ?>";
        var API_URL = "<?php echo $lb_api_url ?>";
        jQuery('#leadbox-loading').find('.spinner').show();
        jQuery('#leadbox-options').hide();
    </script>
<?php
}

?>
