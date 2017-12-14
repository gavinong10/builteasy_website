<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 30.10.2014
 * Time: 09:47
 */
class TCB_Event_Trigger_Exit_Intent extends TCB_Event_Trigger_Abstract {
	/**
	 * should return the Event name
	 * @return mixed
	 */
	public function getName() {
		return 'Exit intent (user about to leave the page)';
	}

	/**
	 * render the exit_intent settings
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function renderSettings( $data ) {
		return $this->renderTCBSettings( 'exit_intent.php', $data );
	}

	/**
	 * setup the main code for triggering exit intent events
	 * @return mixed|void
	 */
	public function outputGlobalJavascript() {
		/* TODO: not really sure if this is the best way to detect a browser from a device without a mouse */
		if ( wp_is_mobile() ) {
			return;
		}

		include dirname( dirname( dirname( __FILE__ ) ) ) . '/views/js/trigger_exit_intent.php';
	}

	/**
	 * only on mobile devices, if the user explicitely set this, it will trigger the action after a delay
	 *
	 * @param $config
	 *
	 * @return string
	 */
	public function getInstanceJavascript( $event_data ) {
		$config = $event_data['config'];
		if ( ! wp_is_mobile() || empty( $config['e_mobile'] ) ) {
			return '';
		}

		return 'jQuery(function () {setTimeout(function () {jQuery(document).trigger("tve-page-event-exit")}, ' . (int) $config['e_delay'] * 1000 . ')})';
	}
} 