<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

abstract class TD_NM_Trigger_Abstract {
	protected $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Check if item exists in list
	 *
	 * @param $list
	 * @param $id
	 *
	 * @return bool
	 */
	protected function in_list( $list, $id ) {
		foreach ( $list as $key => $item ) {
			if ( $item['value'] == $id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if settings are applicable for that item
	 *
	 * @param mixxed $item
	 *
	 * @return mixed
	 */
	abstract public function applicable( $item );
}
