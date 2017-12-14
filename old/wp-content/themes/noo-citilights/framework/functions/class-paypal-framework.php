<?php
/**
 * This helper class file is base on the plubin: PayPal Framework
 * http://bluedogwebservices.com/wordpress-plugin/paypal-framework/
 * It's use as a paypal process part of a more functionable admin with Orders management.
 */

/*  Copyright 2009  Aaron D. Campbell  (email : wp_plugins@xavisys.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * nooPayPalFramework is the class that handles ALL of the PayPal functionality,
 */
if( !class_exists('nooPayPalFramework')) :
	class nooPayPalFramework
	{
		/**
		 * @var array Plugin settings
		 */
		private $_settings;

		/**
		 * @var payment setting key
		 */
		private $_optionsName = 'noo_payment_settings';

		/**
		 * Static property to hold our singleton instance
		 * @var nooPayPalFramework
		 */
		static $instance = false;

		/**
		 * Form data
		 * @var array
		 */
		private $data = array();

		/**
		 * @var array Endpoints for sandbox and live
		 */
		private $_endpoint = array(
			'sandbox'	=> 'https://api-3t.sandbox.paypal.com/nvp',
			'live'		=> 'https://api-3t.paypal.com/nvp'
		);

		/**
		 * @var array URLs for sandbox and live
		 */
		private $_url = 'https://www.sandbox.paypal.com/webscr';

		/**
		 * @var array URLs for PayPal processor
		 */
		private $_notify_url = '';

		/**
		 * @access private
		 * @var string Query var for listener to watch for
		 */
		private $_listener_query_var		= 'paypalListener';

		/**
		 * @access private
		 * @var string Value that query var must be for listener to take overs
		 */
		private $_listener_query_var_value	= 'IPN';

		private $_currency = 'USD';

		/**
		 * This is our constructor, which is private to force the use of
		 * getInstance() to make this a Singleton
		 *
		 * @return nooPayPalFramework
		 */
		private function __construct() {
			$this->_getSettings();
			$this->_correctEmails();
			
			$this->_currency = NooProperty::get_general_option('currency');
			$this->_url = $this->getSetting('enable_sandbox', false) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
			$this->_notify_url = esc_url( add_query_arg( array( 'action' => 'paypal_listener' ), admin_url('admin-ajax.php') ) );

			// init some default values
			$this->_addField('rm',					'2');		// Return method = POST
			$this->_addField('business',			$this->getSetting('merchant_account'));
			// $this->_addField('receiver_email',		$this->getSetting('merchant_account'));

			// this is an optional
			$this->_addField('no_shipping',			1);

			/**
			 * Add filters and actions
			 */
			add_action( 'wp_ajax_nopriv_paypal_listener', array( $this, 'listener' ) );
			add_action( 'wp_ajax_paypal_listener', array( $this, 'listener' ) );
			// add_action( 'template_redirect', array( $this, 'template_redirect' ) );
			// add_filter( 'query_vars', array( $this, 'addPaypalListenerVar' ) );
		}

		/**
		 * Add form field data
		 */
		private function _addField($field, $value) {
			$this->data["$field"]	= $value;
		}

		/**
		 * If an instance exists, this returns it.  If not, it creates one and
		 * retuns it.
		 *
		 * @return nooPayPalFramework
		 */
		public static function getInstance() {
			if ( !self::$instance )
				self::$instance = new self;
			return self::$instance;
		}

		private function _getSettings() {
			if (empty($this->_settings))
				$this->_settings = get_option( $this->_optionsName );
			if ( !is_array( $this->_settings ) )
				$this->_settings = array();

			$defaults = array(
				'merchant_account'	=> '',
				'enable_sandbox'	=> false,
				'disable_ssl'		=> false,
				'notify_email'		=> '',
			);

			$this->_settings = wp_parse_args( $this->_settings, $defaults );
		}

		public function getSetting( $settingName, $default = false ) {
			if (empty($this->_settings))
				$this->_getSettings();

			if ( isset($this->_settings[$settingName]) )
				return $this->_settings[$settingName];
			else
				return $default;
		}

		/**
		 * Process payment method.
		 */
		public function getPaymentURL($order) {
			$name			= trim($order['name']);
			$space_pos		= strpos($name, ' ');
			$first_name 	= $space_pos ? substr($name, 0, $space_pos) : $name;
			$last_name 		= $space_pos ? substr($name, $space_pos + 1) : '';

			$this->_addField('custom',			$order['ID']);
			$this->_addField('first_name',		$first_name);
			$this->_addField('last_name',		$last_name);
			$this->_addField('email',			$order['email']);

			$this->_addField('return',			$order['return_url']);
			$this->_addField('cancel_return',	$order['cancel_url']);
			$this->_addField('notify_url',		$this->_notify_url);
			$this->_addField('item_name',		$order['item_name']);
			$this->_addField('item_number',		$order['item_number']);
			$this->_addField('currency_code',	$this->_currency);
			// $this->_addField('invoice',			$order['invoice']);

			if( isset($order['is_recurring']) && $order['is_recurring'] ) {
				$this->_addField('cmd',			'_xclick-subscriptions');
				$this->_addField('a3',			round($order['amount'], 2));
				$this->_addField('p3', 			$order['p3']);
				$this->_addField('t3', 			$order['t3']);
				$this->_addField('src', 		$order['src']);
				$this->_addField('srt', 		$order['srt']);
				$this->_addField('sra', 		$order['sra']);
			} else {
				$this->_addField('cmd',			'_ext-enter');
				$this->_addField('redirect_cmd',	'_xclick');
				$this->_addField('amount',		round($order['amount'], 2));
			}
			
			return $this->buildPaypalURL();;
		}
		
		/**
		 * Build PayPalUrl
		 */
		private function buildPaypalURL() {
			$nvpString = $this->makeNVP($this->data);
			return $this->_url . "?{$nvpString}";
		}

		/**
		 * Convert an associative array into an NVP string
		 *
		 * @param array Associative array to create NVP string from
		 * @param string[optional] Used to separate arguments (defaults to &)
		 *
		 * @return string NVP string
		 */
		private function makeNVP( $reqArray, $sep = '&' ) {
			if ( !is_array($reqArray) )
				return $reqArray;
			return http_build_query( $reqArray, '', $sep );
		}

		/**
		 * hashCall: Function to perform the API call to PayPal using API signature
		 * @param string|array $args Parameters needed for call
		 *
		 * @return array On success return associtive array containing the response from the server.
		 */
		public function hashCall( $args ) {
			$params = array(
				'body'		=> $this->_prepRequest($args),
				'sslverify' => apply_filters( 'paypal_framework_sslverify', false ),
				'timeout' 	=> 30,
			);

			// Send the request
			$resp = wp_remote_post( $this->_endpoint[$this->_settings['sandbox']], $params );

			// If the response was valid, decode it and return it.  Otherwise return a WP_Error
			if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 ) {
				// Used for debugging.
				$request = $this->_sanitizeRequest($params['body']);
				$message = __( 'Request:', 'noo' );
				$message .= "\r\n".print_r($request, true)."\r\n\r\n";
				$message .= __( 'Response:', 'noo' );
				$message .= "\r\n".print_r(wp_parse_args( $resp['body'] ), true)."\r\n\r\n";
				$this->_notify_mail( __( 'PayPal Framework - hashCall sent successfully', 'noo' ), $message );
				return wp_parse_args($resp['body']);
			} else {
				$request = $this->_sanitizeRequest($params['body']);
				$message = __( 'Request:', 'noo' );
				$message .= "\r\n".print_r($request, true)."\r\n\r\n";
				$message .= __( 'Response:', 'noo' );
				$message .= "\r\n".print_r($resp, true)."\r\n\r\n";
				$this->_notify_mail( __( 'PayPal Framework - hashCall failed', 'noo' ), $message );
				if ( !is_wp_error($resp) )
					$resp = new WP_Error('http_request_failed', $resp['response']['message'], $resp['response']);
				return $resp;
			}
		}

		private function _sanitizeRequest($request) {
			/**
			 * If this is a live request, hide sensitive data in the debug
			 * E-Mails we send
			 */
			if ( $this->_settings['sandbox'] != 'sandbox' ) {
				if ( !empty( $request['ACCT'] ) )
					$request['ACCT']	= str_repeat('*', strlen($request['ACCT'])-4) . substr($request['ACCT'], -4);
				if ( !empty( $request['EXPDATE'] ) )
					$request['EXPDATE']	= str_repeat('*', strlen($request['EXPDATE']));
				if ( !empty( $request['CVV2'] ) )
					$request['CVV2']	= str_repeat('*', strlen($request['CVV2']));
			}
			return $request;
		}

		/**
		 * Used to direct the user to the Express Checkout
		 *
		 * @param string|array $args Parameters needed for call.  *token is REQUIRED*
		 */
		public function sendToExpressCheckout($args) {
			$args['cmd'] = '_express-checkout';
			$nvpString = $this->makeNVP($args);
			wp_redirect($this->_url . "?{$nvpString}");
			exit;
		}

		public function template_redirect() {
			// Check that the query var is set and is the correct value.
			if ( get_query_var( $this->_listener_query_var ) == $this->_listener_query_var_value )
				$this->listener();
		}

		/**
		 * This is our listener.  If the proper query var is set correctly it will
		 * attempt to handle the response.
		 */
		public function listener() {
			$_POST = stripslashes_deep($_POST);
			// Try to validate the response to make sure it's from PayPal
			if ($this->_validateMessage())
				$this->_processMessage();

			// Stop WordPress entirely
			exit;
		}

		public function _correctEmails() {
			$this->_settings['notify_email'] = preg_split('/\s*,\s*/', $this->_settings['notify_email']);
			$this->_settings['notify_email'] = array_filter($this->_settings['notify_email'], 'is_email');
			$this->_settings['notify_email'] = implode(',', $this->_settings['notify_email']);
		}

		private function _notify_mail( $subject, $message ) {
			$notify_email = $this->getSetting('notify_email');
			// Notify about payment.
			if ( !empty($notify_email) )
				wp_mail( $notify_email, $subject, $message );
		}

		/**
		 * Validate the message by checking with PayPal to make sure they really
		 * sent it
		 */
		private function _validateMessage() {
			// Set the command that is used to validate the message
			$_POST['cmd'] = "_notify-validate";

			// We need to send the message back to PayPal just as we received it
			$params = array(
				'body' => $_POST,
				'sslverify' => ! $this->getSetting( 'disable_ssl', false ),
				'timeout' 	=> 30,
			);

			// Send the request
			$resp = wp_remote_post( $this->_url, $params );

			// Put the $_POST data back to how it was so we can pass it to the action
			unset( $_POST['cmd'] );
			$message = __('URL:', 'noo' );
			$message .= "\r\n".print_r($this->_url, true)."\r\n\r\n";
			$message .= __('Options:', 'noo' );
			$message .= "\r\n".print_r($this->_settings, true)."\r\n\r\n";
			$message .= __('Response:', 'noo' );
			$message .= "\r\n".print_r($resp, true)."\r\n\r\n";
			$message .= __('Post:', 'noo' );
			$message .= "\r\n".print_r($_POST, true);

			// If the response was valid, check to see if the request was valid
			if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 && (strcmp( $resp['body'], "VERIFIED") == 0)) {
				// $this->_notify_mail( __( 'IPN Listener Test - Validation Succeeded', 'noo' ), $message );
				return true;
			} else {
				// If we can't validate the message, assume it's bad
				// $this->_notify_mail( __( 'IPN Listener Test - Validation Failed', 'noo' ), $message );
				return false;
			}
		}

		/**
		 * Add our query var to the list of query vars
		 */
		public function addPaypalListenerVar($public_query_vars) {
			$public_query_vars[] = $this->_listener_query_var;
			return $public_query_vars;
		}

		/**
		 * Throw an action based off the transaction type of the message
		 */
		private function _processMessage() {
			do_action( 'noo-paypal-ipn', $_POST );
		}
	}
endif;

// Instantiate our class
$nooPayPalFramework = nooPayPalFramework::getInstance();
