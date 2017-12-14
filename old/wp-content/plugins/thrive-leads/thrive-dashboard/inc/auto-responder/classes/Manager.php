<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Manager {
	public static $ADMIN_HAS_ERROR = false;

	public static $API_TYPES = array(
		'autoresponder' => 'Email Marketing',
		'webinar'       => 'Webinars',
		'other'         => 'Other',
		'social'        => 'Social',
	);

	public static $AVAILABLE = array(

		'activecampaign'       => 'Thrive_Dash_List_Connection_ActiveCampaign',
		'arpreach'             => 'Thrive_Dash_List_Connection_ArpReach',
		'aweber'               => 'Thrive_Dash_List_Connection_AWeber',
		'campaignmonitor'      => 'Thrive_Dash_List_Connection_CampaignMonitor',
		'constantcontact'      => 'Thrive_Dash_List_Connection_ConstantContact',
		'convertkit'           => 'Thrive_Dash_List_Connection_ConvertKit',
		'drip'                 => 'Thrive_Dash_List_Connection_Drip',
		'facebook'             => 'Thrive_Dash_List_Connection_Facebook',
		'get-response'         => 'Thrive_Dash_List_Connection_GetResponse',
		'google'               => 'Thrive_Dash_List_Connection_Google',
		'gotowebinar'          => 'Thrive_Dash_List_Connection_GoToWebinar',
		'hubspot'              => 'Thrive_Dash_List_Connection_HubSpot',
		'icontact'             => 'Thrive_Dash_List_Connection_iContact',
		'infusionsoft'         => 'Thrive_Dash_List_Connection_Infusionsoft',
		'klicktipp'            => 'Thrive_Dash_List_Connection_KlickTipp',
		'madmimi'              => 'Thrive_Dash_List_Connection_MadMimi',
		'mailchimp'            => 'Thrive_Dash_List_Connection_Mailchimp',
		'mailerlite'           => 'Thrive_Dash_List_Connection_MailerLite',
		'mailpoet'             => 'Thrive_Dash_List_Connection_MailPoet',
		'mailrelay'            => 'Thrive_Dash_List_Connection_MailRelay',
		'mautic'               => 'Thrive_Dash_List_Connection_Mautic',
		'ontraport'            => 'Thrive_Dash_List_Connection_Ontraport',
		'recaptcha'            => 'Thrive_Dash_List_Connection_ReCaptcha',
		'sendreach'            => 'Thrive_Dash_List_Connection_Sendreach',
		'sendgrid'             => 'Thrive_Dash_List_Connection_SendGrid',
		'sendinblue'           => 'Thrive_Dash_List_Connection_Sendinblue',
		'sendy'                => 'Thrive_Dash_List_Connection_Sendy',
		'twitter'              => 'Thrive_Dash_List_Connection_Twitter',
		'webinarjamstudio'     => 'Thrive_Dash_List_Connection_WebinarJamStudio',
		'wordpress'            => 'Thrive_Dash_List_Connection_Wordpress',

		/* notification manger - these are now included in the dashboard - services for email notifications */
		'awsses'               => 'Thrive_Dash_List_Connection_Awsses',
		'campaignmonitoremail' => 'Thrive_Dash_List_Connection_CampaignMonitorEmail',
		'mailgun'              => 'Thrive_Dash_List_Connection_Mailgun',
		'mandrill'             => 'Thrive_Dash_List_Connection_Mandrill',
		'mailrelayemail'       => 'Thrive_Dash_List_Connection_MailRelayEmail',
		'postmark'             => 'Thrive_Dash_List_Connection_Postmark',
		'sendgridemail'        => 'Thrive_Dash_List_Connection_SendGridEmail',
		'sendinblueemail'      => 'Thrive_Dash_List_Connection_SendinblueEmail',
		'sparkpost'            => 'Thrive_Dash_List_Connection_SparkPost',

	);

	private static $_available = array();

	/**
	 * get a list of all available APIs
	 *
	 * @param bool $onlyConnected
	 * @param array $exclude_types
	 * @param bool $onlyNames
	 *
	 * @return array
	 */
	public static function getAvailableAPIs( $onlyConnected = false, $exclude_types = array(), $onlyNames = false ) {
		$lists = array();

		$credentials = self::credentials();

		foreach ( self::available() as $key => $api ) {
			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			if ( ! class_exists( $api ) ) {//for cases when Mandril is not updated in TL
				continue;
			}
			$instance = new $api( $key );
			if ( ( $onlyConnected && empty( $credentials[ $key ] ) ) || in_array( $instance->getType(), $exclude_types ) ) {
				continue;
			}
			if ( $onlyNames ) {
				$lists[ $key ] = self::connectionInstance( $key, isset( $credentials[ $key ] ) ? $credentials[ $key ] : array() )->getTitle();
			} else {
				$lists[ $key ] = self::connectionInstance( $key, isset( $credentials[ $key ] ) ? $credentials[ $key ] : array() );
			}
		}

		return $lists;
	}

	/**
	 * get a list of all available APIs by type
	 *
	 * @param bool $onlyConnected if true, it will return only APIs that are already connected
	 * @param array $include_types exclude connection by their type
	 *
	 * @return array Thrive_Dash_List_Connection_Abstract[]
	 */
	public static function getAvailableAPIsByType( $onlyConnected = false, $include_types = array() ) {
		$lists = array();

		$credentials = self::credentials();

		foreach ( self::available() as $key => $api ) {
			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			$instance = new $api( $key );
			if ( ( $onlyConnected && empty( $credentials[ $key ] ) ) || ! in_array( $instance->getType(), $include_types ) ) {
				continue;
			}

			$lists[ $key ] = self::connectionInstance( $key, isset( $credentials[ $key ] ) ? $credentials[ $key ] : array() );

		}

		return $lists;
	}

	/**
	 * fetch the connection credentials for a specific connection (or for all at once)
	 *
	 * @param string $key if empty, all will be returned
	 *
	 * @return array
	 */
	public static function credentials( $key = '' ) {
		$details = get_option( 'thrive_mail_list_api', array() );

		if ( empty( $key ) ) {
			return $details;
		}

		if ( ! isset( $details[ $key ] ) ) {
			return array();
		}

		return $details[ $key ];
	}

	/**
	 * save the credentials for an instance
	 *
	 * @param Thrive_Dash_List_Connection_Abstract $instance
	 */
	public static function save( $instance ) {
		$existing                        = self::credentials();
		$existing[ $instance->getKey() ] = $instance->getCredentials();

		update_option( 'thrive_mail_list_api', $existing );
	}

	/**
	 *
	 * factory method for a connection instance
	 *
	 * @param string $key
	 * @param bool|array $savedCredentials
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public static function connectionInstance( $key, $savedCredentials = false ) {
		$available = self::available();

		if ( ! isset( $available[ $key ] ) ) {
			return null;
		}
		/** @var Thrive_Dash_List_Connection_Abstract $instance */
		$instance = new $available[ $key ]( $key );

		if ( false !== $savedCredentials ) {
			$instance->setCredentials( $savedCredentials );
		} else {
			$instance->setCredentials( self::credentials( $key ) );
		}

		return $instance;
	}

	/**
	 * saves a message to be displayed on the next request
	 *
	 * @param string $type
	 * @param string $message
	 */
	public static function message( $type, $message ) {
		if ( $type == 'error' ) {
			self::$ADMIN_HAS_ERROR = true;
		}
		$messages          = get_option( 'tve_api_admin_notices', array() );
		$messages[ $type ] = $message;

		update_option( 'tve_api_admin_notices', $messages );
	}

	/**
	 * reads out all messages (success / error from the options table)
	 */
	public static function flashMessages() {
		$GLOBALS['thrive_list_api_message'] = get_option( 'tve_api_admin_notices', array() );

		delete_option( 'tve_api_admin_notices' );
	}

	/**
	 * transform the array of connections into one input string
	 * this is a really simple encrypt system, is there any need for a more complicated one ?
	 *
	 * @param array $connections a list of key => value pairs (api => list)
	 *
	 * @return string
	 */
	public static function encodeConnectionString( $connections = array() ) {
		return base64_encode( serialize( $connections ) );
	}

	/**
	 * transform the $string into an array of connections
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public static function decodeConnectionString( $string ) {
		if ( empty( $string ) ) {
			return array();
		}
		$string = @base64_decode( $string );
		if ( empty( $string ) ) {
			return array();
		}
		$data = @unserialize( $string );

		return empty( $data ) ? array() : $data;
	}

	public static function toJSON( $APIs = array() ) {
		if ( ! is_array( $APIs ) || empty( $APIs ) ) {
			return json_encode( array() );
		}

		$list = array();

		foreach ( $APIs as $key => $instance ) {

			/** @var $instance Thrive_Dash_List_Connection_Abstract */
			if ( ! is_subclass_of( $instance, 'Thrive_Dash_List_Connection_Abstract' ) ) {
				continue;
			}

			$list[] = $instance->prepareJSON();
		}

		return json_encode( $list );
	}

	public static function available() {
		if ( ! self::$_available ) {
			self::$_available = apply_filters( "tve_filter_available_connection", self::$AVAILABLE );
		}

		return self::$_available;
	}
}
