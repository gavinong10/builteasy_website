<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Trigger_Split_Test_Ends extends TD_NM_Trigger_Abstract {

	public function applicable( $test_item ) {

		//make sure $test_item is THO test //  isset( $test_item->config ) &&  @Danut Why this ?
		if ( $this->in_list( $this->settings['tho'], $test_item->id ) ) {
			return true;
		} else if ( $this->in_list( $this->settings['tl'], $test_item->id ) ) { //make sure $test_item is TL test
			return true;
		}

		return false;
	}

}
