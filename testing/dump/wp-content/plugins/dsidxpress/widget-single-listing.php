<?php
class dsSearchAgent_SingleListingWidget extends WP_Widget {
	public function dsSearchAgent_SingleListingWidget() {
		$this->WP_Widget('dsidx-single-listing', 'IDX Single Listing', array(
			'classname' => 'dsidx-widget-single-listing-wrap',
			'description' => 'Show a single real estate listing'
		));
	}
	
	public function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		$options = get_option(DSIDXPRESS_OPTION_NAME);

		if (!$options["Activated"])
			return;
		
		wp_enqueue_script('jquery', false, array(), false, true);
				
		$apiRequestParams = array();
		$apiRequestParams['responseDirective.ViewNameSuffix'] = 'widget';
		$apiRequestParams['query.MlsNumber'] = $instance['mls_number'];
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData('Details', $apiRequestParams);
		
		if (empty($apiHttpResponse['errors']) && $apiHttpResponse['response']['code'] == '200') {
			$data = $apiHttpResponse['body'];
		} else {
			switch ($apiHttpResponse["response"]["code"]) {
				case 403:
					$data = '<p class="dsidx-error">'.DSIDXPRESS_INACTIVE_ACCOUNT_MESSAGE.'</p>';
					break;
				case 404:
					$data = '<p class="dsidx-error">'.sprintf(DSIDXPRESS_INVALID_MLSID_MESSAGE, $instance["mls_number"]).'</p>';
					break;
				default:
					$data = '<p class="dsidx-error">'.DSIDXPRESS_IDX_ERROR_MESSAGE.'</p>';
			}
		}
		
		echo $before_widget . $data . $after_widget;
	}
	
	public function update($new_instance, $old_instance) {
		return $new_instance;
	}
	
	public function form($instance) {
		$instance = wp_parse_args($instance, array(
			'mls_number' => ''
		));
		
		echo <<<HTML
				<p>
					<label for="{$this->get_field_id('mls_number')}">Enter a MLS Number</label>
					<input type="text" id="{$this->get_field_id('mls_number')}" name="{$this->get_field_name('mls_number')}" value="{$instance['mls_number']}" maxlength="30" class="widefat" />
				</p>
HTML;
	}
}
