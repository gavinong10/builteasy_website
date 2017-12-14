<?php
if (!class_exists('NSU_Admin')) {

    class NSU_Admin {

        private $hook = 'newsletter-sign-up';
        private $longname = 'Newsletter Sign-Up';
        private $shortname = 'Newsletter Sign-Up';
        private $plugin_url = 'https://dannyvankooten.com/wordpress-plugins/newsletter-sign-up/';
        private $filename = 'newsletter-sign-up/newsletter-sign-up.php';
        private $accesslvl = 'manage_options';
        private $bp_active = FALSE;
        private $options = array();

        public function __construct() {
            $this->options = NSU::instance()->get_options();

            add_filter("plugin_action_links_{$this->filename}", array($this, 'add_settings_link'));
            add_action('admin_menu', array($this, 'add_option_page'));
            add_action('admin_init', array($this, 'settings_init'));
            add_action( 'admin_enqueue_scripts', array($this, 'load_css_and_js') );
            add_action('bp_include', array($this, 'set_bp_active'));

            if(isset($_GET['nsu-hide-mc4wp-notice'])) {
                add_option("nsu_hide_mc4wp_notice", true);
            } elseif($this->options['mailinglist']['provider'] == 'mailchimp' && get_option('nsu_hide_mc4wp_notice') == false) {
                add_action( 'admin_notices', array($this, 'notice_mailchimp_for_wp'));
            }

            
        }

        public function get_checkbox_compatible_plugins()
        {

            $checkbox_plugins = array(
                'comment_form' => "Comment form",
                "registration_form" => "Registration form"
                );
            if(is_multisite()) { $checkbox_plugins['ms_form'] = "MultiSite forms"; }
            if(class_exists("BuddyPress")) $checkbox_plugins['bp_form'] = "BuddyPress registration";
            if(class_exists('bbPress')) $checkbox_plugins['bbpress_forms'] = "bbPress";
            return $checkbox_plugins;
        }

        public function notice_mailchimp_for_wp()
        {
            ?>
            <div class="updated">
             <p><strong>Newsletter Sign-Up Notice:</strong> You are using MailChimp, great! Consider switching to <a href="http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/">MailChimp for WordPress</a>, you will <strong>love</strong> it. 
                It can be downloaded from the WordPress repository <a href="https://wordpress.org/plugins/mailchimp-for-wp/">here</a>. | <a href="?nsu-hide-mc4wp-notice=1">Hide Notice</a></p>
            </div>
            <?php
        }

        public function load_css_and_js($hook)
        {
            if(!stripos($hook, $this->hook)) { return false; }

            wp_enqueue_style($this->hook, plugins_url('newsletter-sign-up/assets/css/admin.css'));
            wp_enqueue_script(array('jquery'));
            wp_enqueue_script($this->hook, plugins_url('newsletter-sign-up/assets/js/admin.js'));           
        }

        /**
         * If buddypress is loaded, set buddypress_active to TRUE
         */
        public function set_bp_active() {
            $this->bp_active = TRUE;
        }

        /**
         * The default settings page
         */
        public function options_page_default() {
            $tab = 'mailinglist-settings';
            $opts = $this->options['mailinglist'];

            $viewed_mp = NULL;
            if (!empty($_GET['mp']))
                $viewed_mp = $_GET['mp'];
            elseif (empty($_GET['mp']) && isset($opts['provider']))
                $viewed_mp = $opts['provider'];
            if (!in_array($viewed_mp, array('mailchimp', 'icontact', 'aweber', 'phplist', 'ymlp', 'other')))
                $viewed_mp = NULL;

            // Fill in some predefined values if options not set or set for other newsletter service
            if ($opts['provider'] != $viewed_mp) {
                switch ($viewed_mp) {

                    case 'mailchimp':
                    if (empty($opts['email_id']))
                        $opts['email_id'] = 'EMAIL';
                    if (empty($opts['name_id']))
                        $opts['name_id'] = 'NAME';
                    break;

                    case 'ymlp':
                    if (empty($opts['email_id']))
                        $opts['email_id'] = 'YMP0';
                    break;

                    case 'aweber':
                    if (empty($opts['form_action']))
                        $opts['form_action'] = 'http://www.aweber.com/scripts/addlead.pl';
                    if (empty($opts['email_id']))
                        $opts['email_id'] = 'email';
                    if (empty($opts['name_id']))
                        $opts['name_id'] = 'name';
                    break;

                    case 'icontact':
                    if (empty($opts['email_id']))
                        $opts['email_id'] = 'fields_email';
                    break;
                }
            }

            require_once NSU_PLUGIN_DIR . 'includes/views/dashboard.php';
        }
        
        /**
         * The admin page for managing checkbox settings
         */
        public function options_page_checkbox_settings() {
            $tab = 'checkbox-settings';
            $opts = $this->options['checkbox'];
            require_once NSU_PLUGIN_DIR . 'includes/views/checkbox_settings.php';
        }

        /**
         * The admin page for managing form settings
         */
        public function options_page_form_settings() {
            $tab = 'form-settings';
            $opts = $this->options['form'];
            $opts['mailinglist'] = $this->options['mailinglist'];
            require_once NSU_PLUGIN_DIR . 'includes/views/form_settings.php';
        }

        /**
         * The page for the configuration extractor
         */
        public function options_page_config_helper() {
            $tab = 'config-helper';
            
            if ( isset( $_POST['form'] ) && ! empty( $_POST['form'] ) ) {

                $result = $this->extract_form_config( $_POST['form'] );
                
            }

            require_once NSU_PLUGIN_DIR . 'includes/views/config_helper.php';
        }

        private function extract_form_config( $form_html ) {

            // strip unneccessary tags
            $form = stripslashes( strip_tags($form_html, '<form><label><input><select><textarea><button>') );

            // set defaults
            $form_action = '';
            $email_name = 'Sorry, can\'t help you with this one.';
            $name_name = 'Sorry, can\'t help you with this one.';
            $additional_data = array();

            preg_match_all("'<(.*?)>'si", $form, $matches);

            if( is_array( $matches ) && isset($matches[0])) {
                $matches = $matches[0];
                $html = join('', $matches);
            } else {
                $html = $form;
            }

            // fake wrap in html and body tags
            $html = '<html><body>' . $html . '</body></html>';

            $doc = new DOMDocument();
            $doc->strictErrorChecking = false;
            $doc->loadHTML( $html);

            $xml = simplexml_import_dom($doc);

            if ( is_object( $xml ) ) {
                $form = $xml->body->form;

                if ($form) {

                    $form_action = (isset($form['action'])) ? $form['action'] : 'Can\'t help you on this one..';


                    if ( $form->input ) {

                        /* Loop trough input fields */
                        foreach ($form->input as $input) {


                                    // Check if this is a hidden field
                            if ($input['type'] == 'hidden') {
                                $additional_data[] = array($input['name'], $input['value']);
                                        // Check if this is the input field that is supposed to hold the EMAIL data
                            } elseif (stripos($input['id'], 'email') !== FALSE || stripos($input['name'], 'email') !== FALSE) {
                                $email_name = $input['name'];

                                        // Check if this is the input field that is supposed to hold the NAME data
                            } elseif (stripos($input['id'], 'name') !== FALSE || stripos($input['name'], 'name') !== FALSE) {
                                $name_name = $input['name'];
                            }

                                // remove input attributes
                            unset($input['id']);
                            unset($input['class']);
                            unset($input['style']);
                            unset($input['onfocus']);
                            unset($input['onblur']);
                            unset($input['size']);
                        }
                    }

                        // remove form attributes
                    unset($form['target']); 
                    unset($form['id']); 
                    unset($form['name']); 
                    unset($form['class']); 
                    unset($form['onsubmit']);
                    unset($form['enctype']);
                }

            }

            $doc->removeChild($doc->firstChild);    
            $doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);

            $simpler_form = $doc->saveHTML();

            // add tabs for improved readability
            $simpler_form = str_replace(array('><', '<input'), array(">\n<", "\t<input"), $simpler_form);
            

            return array(
                'simpler_form' => $simpler_form,
                'form_action' => $form_action,
                'email_name' => $email_name,
                'name_name' => $name_name,
                'additional_data' => $additional_data
                );
        }


        /**
         * Adds the different menu pages
         */
        public function add_option_page() {
            add_menu_page($this->longname, "Newsl. Sign-up", $this->accesslvl, $this->hook, array($this, 'options_page_default'), plugins_url('newsletter-sign-up/assets/img/icon.png'));
            add_submenu_page($this->hook, "Newsletter Sign-Up :: Mailinglist Settings", "List Settings", $this->accesslvl, $this->hook, array($this, 'options_page_default'));       
            add_submenu_page($this->hook, "Newsletter Sign-Up :: Checkbox Settings", "Checkbox Settings", $this->accesslvl, $this->hook . '-checkbox-settings', array($this, 'options_page_checkbox_settings'));
            add_submenu_page($this->hook, "Newsletter Sign-Up :: Form Settings", "Form Settings", $this->accesslvl, $this->hook . '-form-settings', array($this, 'options_page_form_settings'));
            add_submenu_page($this->hook, "Newsletter Sign-Up :: Configuration Extractor", "Config Extractor", $this->accesslvl, $this->hook . '-config-helper', array($this, 'options_page_config_helper'));
        }

        /**
         * Adds the settings link on the plugin's overview page
         * @param array $links Array containing all the settings links for the various plugins.
         * @return array The new array containing all the settings links
         */
        public function add_settings_link($links) {
            $settings_link = '<a href="admin.php?page=' . $this->hook . '">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        /**
         * Registers the settings using WP Settings API.
         */
        public function settings_init() {
            register_setting('nsu_form_group', 'nsu_form', array($this, 'validate_form_options'));
            register_setting('nsu_mailinglist_group', 'nsu_mailinglist', array($this, 'validate_mailinglist_options'));
            register_setting('nsu_checkbox_group', 'nsu_checkbox', array($this, 'validate_checkbox_options'));
        }

        /**
         * Validate the submitted options
         * @param array $options The submitted options
         */
        public function validate_options($options) {
            return $options;
        }

        public function validate_form_options($options) {

            $options['text_after_signup'] = strip_tags($options['text_after_signup'], '<a><b><strong><i><img><em><br><p><ul><li><ol>');
            
            // redirect to url should start with http
            if(isset($options['redirect_to']) && substr($options['redirect_to'],0,4) != 'http') {
                $options['redirect_to'] = '';
            }

            $options['name_required'] = (isset($options['name_required'])) ? 1 : 0;
            $options['wpautop'] = (isset($options['wpautop'])) ? 1 : 0;
            $options['use_html5'] = (isset($options['use_html5'])) ? 1 : 0;
            $options['load_form_css'] = (isset($options['load_form_css'])) ? 1 : 0;
            
            return $options;
        }

        public function validate_mailinglist_options($options) {
            if (is_array($options['extra_data'])) {
                foreach ($options['extra_data'] as $key => $value) {
                    if (empty($value['name']))
                        unset($options['extra_data'][$key]);
                }
            }

            return $options;
        }

        public function validate_checkbox_options($options) {
            $options['add_to_comment_form'] = (isset($options['add_to_comment_form'])) ? 1 : 0;
            return $options;
        }

    }

}