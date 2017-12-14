<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Checker {

	protected static $instance;

	protected function __construct() {
		$this->init_hooks();
	}

	public function init_hooks() {
		add_action( 'tve_leads_form_conversion', array( __CLASS__, 'on_email_sign_up' ), 10, 5 );
		add_action( 'tve_leads_action_set_test_item_winner', array( $this, 'on_split_test_ends' ), 10, 2 );
		add_action( 'tho_action_set_test_item_winner', array( $this, 'on_split_test_ends' ), 10, 2 );
	}

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function on_email_sign_up( $tl_item, $tl_form_type, $tl_variation, $tl_test, $optin_data ) {

		$notifications = td_nm_get_notifications( array(
			'meta_key'   => 'td_nm_trigger_type',
			'meta_value' => 'email_sign_up',
		) );

		$sign_up_data = func_get_args();

		foreach ( $notifications as $notification ) {
			$trigger = new TD_NM_Trigger_Email_Sign_Up( $notification->trigger['settings'] );
			if ( $trigger->applicable( $tl_item ) ) {
				self::instance()->execute_sign_up_actions( $notification->actions, $sign_up_data );
			}
		}
	}

	public function on_split_test_ends( $test_item, $test ) {
		$notifications = td_nm_get_notifications( array(
			'meta_key'   => 'td_nm_trigger_type',
			'meta_value' => 'split_test_ends',
		) );

		$test_data = func_get_args();

		foreach ( $notifications as $notification ) {
			$trigger = new TD_NM_Trigger_Split_Test_Ends( $notification->trigger['settings'] );
			if ( $trigger->applicable( $test ) ) {
				$this->execute_split_test_ends_actions( $notification->actions, $test_data );
			}
		}
	}

	public function execute_sign_up_actions( $actions, $sign_up_data ) {
		foreach ( $actions as $item ) {
			/** @var TD_NM_Action_Abstract $action */
			$action        = null;
			$prepared_data = array();

			/**
			 * ugly way to instantiate the action but we have to make sure it works in PHP 5.2
			 */
			switch ( $item['type'] ) {
				case 'custom_script':
					/** @var TD_NM_Action_Custom_Script $action */
					$action        = new TD_NM_Action_Custom_Script( $item );
					$prepared_data = $action->prepare_email_sign_up_data( $sign_up_data );
					break;
				case 'send_email_notification':
					/** @var TD_NM_Action_Send_Email_Notification $action */
					$action        = new TD_NM_Action_Send_Email_Notification( $item );
					$prepared_data = $action->prepare_email_sign_up_data( $sign_up_data );
					break;
				case 'wordpress_notification':
					/** @var TD_NM_Action_Wordpress_Notification $action */
					$action        = new TD_NM_Action_Wordpress_Notification( $item );
					$prepared_data = $action->prepare_email_sign_up_data( $sign_up_data );
					break;
			}

			if ( $action ) {
				$action->execute( $prepared_data );
			}
		}
	}

	public function execute_split_test_ends_actions( $actions, $test_data ) {
		foreach ( $actions as $item ) {
			/** @var TD_NM_Action_Abstract $action */
			$action        = null;
			$prepared_data = array();

			/**
			 * ugly way to instantiate the action but we have to make sure it works in PHP 5.2
			 */
			switch ( $item['type'] ) {
				case 'custom_script':
					/** @var TD_NM_Action_Custom_Script $action */
					$action        = new TD_NM_Action_Custom_Script( $item );
					$prepared_data = $action->prepare_split_test_ends_data( $test_data );
					break;
				case 'send_email_notification':
					/** @var TD_NM_Action_Send_Email_Notification $action */
					$action        = new TD_NM_Action_Send_Email_Notification( $item );
					$prepared_data = $action->prepare_split_test_ends_data( $test_data );
					break;
				case 'wordpress_notification':
					/** @var TD_NM_Action_Wordpress_Notification $action */
					$action        = new TD_NM_Action_Wordpress_Notification( $item );
					$prepared_data = $action->prepare_split_test_ends_data( $test_data );
					break;
			}

			if ( $action ) {
				$action->execute( $prepared_data );
			}
		}
	}
}
