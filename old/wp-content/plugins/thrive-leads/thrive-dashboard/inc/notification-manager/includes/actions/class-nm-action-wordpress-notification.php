<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Action_Wordpress_Notification extends TD_NM_Action_Abstract {

	public function execute( $prepared_data ) {

		preg_match_all( '/\\[link\\].*?\\[\/link\\]/is', $this->settings['message']['content'], $matches );
		$message = $this->settings['message']['content'];
		if ( ! empty( $matches ) && isset( $matches[0] ) ) {
			foreach ( $matches[0] as $expression ) {
				preg_match( '#\\[link\\](.+)\\[/link\\]#s', $expression, $expression_match );
				$message = str_replace( $expression_match[0], '<a href="javascript:void(0);" onclick="ThriveNMWordpressNotification.functions.trigger_dismiss_notice(' . $this->settings['notification_id'] . ')">' . $expression_match[1] . '</a>', $message );
			}
		}
		$meta_value = array( 'url' => $prepared_data['test_url'], 'message' => $message );
		$result = add_post_meta( $this->settings['notification_id'], 'td_nm_wordpress_notification', $meta_value, true ) or update_post_meta( $this->settings['notification_id'], 'td_nm_wordpress_notification', $meta_value );
	}

	public function prepare_email_sign_up_data( $sign_up_data ) {
		$data = array();

		$tl_item = $sign_up_data[0];
		$tl_form = $sign_up_data[1];

		$data['source']         = $tl_item->post_type;
		$data['key']            = $tl_form->ID;
		$data['test_url']       = admin_url( 'admin.php?page=thrive_leads_dashboard' ) . '#form-type/' . $tl_form->ID;
		$data['trigger_source'] = 'leads';

		return $data;
	}

	public function prepare_split_test_ends_data( $split_test_ends_data ) {

		$data      = array();
		$test_item = $split_test_ends_data[0];
		$test      = $split_test_ends_data[1];

		$data['key']            = $test_item->test_id;
		$data['trigger_source'] = $test->trigger_source;
		$data['test_url']       = $test->url;

		return $data;
	}

}
