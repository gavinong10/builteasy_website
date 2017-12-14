<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

/**
 * Class WPBakeryShortCode_VC_Btn
 * @since 4.5
 */
class WPBakeryShortCode_VC_Btn extends WPBakeryShortCode {

	/**
	 * @param $title
	 *
	 * @since 4.5
	 * @return string
	 */
	protected function outputTitle( $title ) {
		$icon = $this->settings( 'icon' );

		return '<h4 class="wpb_element_title"><span class="vc_general vc_element-icon vc_btn3-icon' . ( ! empty( $icon ) ? ' ' . $icon : '' ) . '"></span></h4>';
	}
}