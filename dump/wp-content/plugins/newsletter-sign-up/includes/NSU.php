<?php

class NSU {

	private $options = array();
	private static $instance = null;
	private static $checkbox = null;
	private static $form = null;

	public function __construct() {
		self::$instance = $this;

		$opts = $this->get_options();

		self::checkbox();
		self::form();

		// widget hooks
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// check if this is an AJAX request
		if ( !defined( "DOING_AJAX" ) || !DOING_AJAX ) {

			if ( is_admin() ) {

				// backend only
				require_once NSU_PLUGIN_DIR . 'includes/NSU_Admin.php';
				new NSU_Admin();

			} else {

				// frontend only
				require_once NSU_PLUGIN_DIR . 'includes/functions.php';

				add_action( 'wp_enqueue_scripts', array( $this, 'load_stylesheets' ) );
				add_action( 'login_enqueue_scripts',  array( $this, 'load_stylesheets' ) );

			}
		}
	}

	public static function checkbox() {
		if ( !self::$checkbox ) {
			require_once NSU_PLUGIN_DIR .'includes/NSU_Checkbox.php';
			self::$checkbox = new NSU_Checkbox;
		}

		return self::$checkbox;
	}

	public static function form() {
		if ( !self::$form ) {
			require_once NSU_PLUGIN_DIR . 'includes/NSU_Form.php';
			self::$form = new NSU_Form;
		}

		return self::$form;
	}


	/**
	 * Initalize options
	 *
	 * @return array $options
	 */
	public function get_options() {
		if ( empty( $this->options ) ) {
			$keys = array( 'form', 'mailinglist', 'checkbox' );

			$defaults = array(
				'form' => array( 'load_form_css' => 0, 'use_html5' => 1, 'submit_button' => 'Sign up',
					'name_label' => 'Name:', 'email_label' => "Email:", 'email_default_value' => 'Your emailaddress..', 'name_required' => 0, 'name_default_value' => 'Your name..', 'wpautop' => 0,
					'text_after_signup' => 'Thanks for signing up to our newsletter. Please check your inbox to confirm your email address.', 'redirect_to' => '',
					'text_empty_name' => 'Please fill in the name field.', 'text_empty_email' => 'Please fill in the email field.', 'text_invalid_email' => 'Please enter a valid email address.'
				),
				'mailinglist' => array( 'provider' => '', 'use_api' => 0, 'subscribe_with_name' => 0, 'email_id' => '', 'name_id' => '', 'form_action' => '' ),
				'checkbox' => array( 'text' => 'Sign me up for the newsletter', 'redirect_to' => '', 'precheck' => 0, 'cookie_hide' => 0, 'css_reset' => 0,
					'add_to_registration_form' => 0, 'add_to_comment_form' => 1, 'add_to_buddypress_form' => 0,
					'add_to_multisite_form' => 0, 'add_to_bbpress_forms' => 0
				)
			);

			foreach ( $keys as $key ) {
				if ( ( $option = get_option( 'nsu_'. $key ) ) == false ) {
					add_option( 'nsu_'. $key, $defaults[$key] );
				}

				$this->options[$key] = array_merge( $defaults[$key], (array) $option );
			}

		}

		return $this->options;
	}

	/**
	 * Registers the Newsletter Sign-Up Widget
	 *
	 * @return type
	 */
	public function register_widget() {
		require_once NSU_PLUGIN_DIR . 'includes/NewsletterSignUpWidget.php';
		return register_widget( 'NewsletterSignUpWidget' );
	}

	/**
	 * Factory method for NewsletterSignUp class. Only instantiate once.
	 *
	 * @return NewsletterSignUp Instance of Newsletter Sign-Up class
	 */
	public static function instance() {
		if ( !self::$instance ) self::$instance = new NSU();

		return self::$instance;
	}

	public function load_stylesheets() {
		$opts = $this->get_options();

		$stylesheets = array();
		if ( $opts['checkbox']['css_reset'] == 1 ) { $stylesheets['checkbox'] = 1; }
		if ( $opts['form']['load_form_css'] == 1 ) { $stylesheets['form'] = 1; }

		if ( !empty( $stylesheets ) ) {
			$stylesheet_url = add_query_arg( $stylesheets, plugins_url( "/assets/css/css.php", dirname( __FILE__ ) ) );
			wp_enqueue_style( 'newsletter-sign-up', $stylesheet_url );
		}

	}




	/**
	 * Send the post data to the newsletter service, mimic form request
	 */
	function send_post_data( $email, $name = '', $type = 'checkbox' ) {
		$opts = $this->options['mailinglist'];

		// when not using api and no form action has been given, abandon.
		if ( empty( $opts['use_api'] ) && empty( $opts['form_action'] ) ) return;

		/* Are we using API? */
		if ( $opts['use_api'] == 1 ) {

			switch ( $opts['provider'] ) {

				/* Send data using the YMLP API */
			case 'ymlp':
				$data = array(
					'key' => $opts['ymlp_api_key'],
					'username' => $opts['ymlp_username'],
					'Email' => $email,
					'GroupId' => $opts['ymlp_groupid'],
					'output' => 'JSON'
				);

				$data = array_merge( $data, $this->add_additional_data( array( 'api' => 'ymlp' ) ) );
				$data = http_build_query( $data );
				$url = 'https://www.ymlp.com/api/Contacts.Add?'.$data;

				$result = wp_remote_post( $url );

				if ( isset( $_POST['_nsu_debug'] ) || isset( $_GET['_nsu_debug'] ) ) {
					var_dump( $result ); die();
				}

				break;

				/* Send data using the MailChimp API */
			case 'mailchimp':
				$request   = array(
					'apikey' => $opts['mc_api_key'],
					'id' => $opts['mc_list_id'],
					'email_address' => $email,
					'double_optin' => ( isset( $opts['mc_no_double_optin'] ) && $opts['mc_no_double_optin'] == 1 ) ? FALSE : TRUE,
					'merge_vars' => array(
						'OPTIN_TIME' => date( 'Y-M-D H:i:s' )
					)
				);

				if ( isset( $opts['mc_use_groupings'] ) && $opts['mc_use_groupings'] == 1 && !empty( $opts['mc_groupings_name'] ) ) {
					$request['merge_vars']['GROUPINGS'] = array(
						array( 'name' => $opts['mc_groupings_name'], 'groups' => $opts['mc_groupings_groups'] )
					);
				}

				/* Subscribe with name? If so, add name to merge_vars array */
				if ( isset( $opts['subscribe_with_name'] ) && $opts['subscribe_with_name'] == 1 ) {
					// Try to provide values for First and Lastname fields
					// These can be overridden, of just ignored by mailchimp.
					$strpos = strpos( $name, ' ' );

					$request['merge_vars']['FNAME'] = $name;

					if ( $strpos ) {
						$request['merge_vars']['FNAME'] = substr( $name, 0, $strpos );
						$request['merge_vars']['LNAME'] = substr( $name, $strpos );
					} else {
						$request['merge_vars']['FNAME'] = $name;
						$request['merge_vars']['LNAME'] = '...';
					}

					$request['merge_vars'][$opts['name_id']] = $name;
				}

				// Add any set additional data to merge_vars array
				$request['merge_vars'] = array_merge( $request['merge_vars'], $this->add_additional_data( array( 'email' => $email, 'name' => $name ) ) );

				$result = wp_remote_post(
					'https://'.substr( $opts['mc_api_key'], -3 ).'.api.mailchimp.com/1.3/?output=php&method=listSubscribe',
					array( 'body' => json_encode( $request ) )
				);

				if ( isset( $_POST['_nsu_debug'] ) || isset( $_GET['_nsu_debug'] ) ) {
					var_dump( $result ); die();
				}

				break;

			}

		} else {
			/* We are not using API, mimic a normal form request */

			$post_data = array(
				$opts['email_id'] => $email,
			);

			// Subscribe with name? Add to $post_data array.
			if ( $opts['subscribe_with_name'] == 1 ) $post_data[$opts['name_id']] = $name;

			// Add list specific data
			switch ( $opts['provider'] ) {

			case 'aweber':
				$post_data['listname'] = $opts['aweber_list_name'];
				$post_data['redirect'] = get_bloginfo( 'wpurl' );
				$post_data['meta_message'] = '1';
				$post_data['meta_required'] = 'email';
				break;

			case 'phplist':
				$post_data['list['.$opts['phplist_list_id'].']'] = 'signup';
				$post_data['subscribe'] = "Subscribe";
				$post_data["htmlemail"] = "1";
				$post_data['emailconfirm'] = $email;
				$post_data['makeconfirmed']='0';
				break;

			}

			$post_data = array_merge( $post_data, $this->add_additional_data( array_merge( array( 'email' => $email, 'name' => $name ), $post_data ) ) );

			$result = wp_remote_post( $opts['form_action'],
				array( 'body' => $post_data )
			);

			if ( isset( $_POST['_nsu_debug'] ) || isset( $_GET['_nsu_debug'] ) ) {
				var_dump( $result ); die();
			}

		}

		// store a cookie, if preferred by site owner
		if ( $type == 'checkbox' && $this->options['checkbox']['cookie_hide'] == 1 ) @setcookie( 'ns_subscriber', TRUE, time() + 9999999 );

		// Check if we should redirect to a given page
		if ( $type == 'form' && strlen( $this->options['form']['redirect_to'] ) > 6 ) {
			wp_redirect( $this->options['form']['redirect_to'] );
			exit;
		} elseif ( $type == 'checkbox' && strlen( $this->options['checkbox']['redirect_to'] ) > 6 ) {
			wp_redirect( $this->options['checkbox']['redirect_to'] );
			exit;
		}

		return true;
	}


	/**
	 * Returns array with additional data names as key, values as value.
	 *
	 * @param array   $args, the normal form data (name, email, list variables)
	 */
	function add_additional_data( $args = array() ) {
		$opts = $this->options['mailinglist'];
		$defaults = array(
			'format' => 'array',
			'api' => NULL
		);

		$args = wp_parse_args( $args, $defaults );

		$add_data = array();
		if ( isset( $opts['extra_data'] ) && is_array( $opts['extra_data'] ) ) {
			foreach ( $opts['extra_data'] as $key => $value ) {
				if ( $args['api'] == 'ymlp' ) $value['name'] = str_replace( 'YMP', 'Field', $value['name'] );
				$value['value'] = str_replace( "%%NAME%%", $args['name'], $value['value'] );
				$value['value'] = str_replace( "%%IP%%", $_SERVER['REMOTE_ADDR'], $value['value'] );
				$add_data[$value['name']] = $value['value'];
			}
		}

		return $add_data;
	}

}
