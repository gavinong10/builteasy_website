<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

abstract class TD_NM_Action_Abstract {

	/** @var array */
	protected $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	abstract public function execute( $prepare_data );

	abstract public function prepare_email_sign_up_data( $sign_up_data );

	abstract public function prepare_split_test_ends_data( $split_test_ends_data );
}
