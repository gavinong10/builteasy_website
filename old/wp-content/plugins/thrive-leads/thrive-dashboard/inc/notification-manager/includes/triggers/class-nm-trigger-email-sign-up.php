<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Trigger_Email_Sign_Up extends TD_NM_Trigger_Abstract {

	public function applicable( $tl_item ) {
		if ( $this->in_list( $this->settings['groups'], $tl_item->ID ) ) {
			return true;
		}

		if ( $this->in_list( $this->settings['shortcodes'], $tl_item->ID ) ) {
			return true;
		}

		if ( $this->in_list( $this->settings['thrive_boxes'], $tl_item->ID ) ) {
			return true;
		}

		return false;
	}

}
