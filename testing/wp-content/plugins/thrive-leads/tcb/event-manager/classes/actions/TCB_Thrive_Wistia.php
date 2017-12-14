<?php

if ( ! class_exists( 'TCB_Thrive_Wistia' ) ) {
	/**
	 *?
	 * handles the server-side logic for the Thrive Lightbox action = opens a lightbox on an Event Trigger
	 *
	 * Class TCB_Thrive_Wistia
	 */
	class TCB_Thrive_Wistia extends TCB_Event_Action_Abstract {
		private static $_loaded_videos = array();

		/**
		 * Should return the user-friendly name for this Action
		 *
		 * @return string
		 */
		public function getName() {
			return 'Wistia Popover';
		}

		/**
		 * Should output the settings needed for this Action when a user selects it from the list
		 *
		 * @param mixed $data
		 */
		public function renderSettings( $data ) {
			return $this->renderTCBSettings( 'wistia_popover', $data );
		}

		/**
		 * Should return an actual string containing the JS function that's handling this action.
		 * The function will be called with 3 parameters:
		 *      -> event_trigger (e.g. click, dblclick etc)
		 *      -> action_code (the action that's being executed)
		 *      -> config (specific configuration for each specific action - the same configuration that has been setup in the settings section)
		 *
		 * Example (php): return 'function (trigger, action, config) { console.log(trigger, action, config); }';
		 *
		 * The output MUST be a valid JS function definition.
		 *
		 * @return string the JS function definition (declaration + body)
		 */
		public function getJsActionCallback() {
			return "function(t,a,c){
                        if (typeof Wistia === 'undefined') {
                            return false;
                        }
                        var videoId = c.event_video_url.split('/').pop(),
                            startTime = null,
                            uniqIdentifier = 'tve_wistia_' + videoId + '_' + c.event_option_uniq,
                            _video = Wistia.api(uniqIdentifier);
                        if (!_video) {
                            return false;
                        }
                        if (c.event_start_min_time && c.event_start_sec_time) {
                            _video.time(c.event_start_min_time * 60 + parseInt(c.event_start_sec_time));
                        }
                        _video.play();
                        return false;
                    };";
		}

		/**
		 * makes all necessary changes to the content depending on the $data param
		 *
		 * this gets called each time this action is encountered in the DOM event configuration
		 *
		 * @param $data
		 */
		public function applyContentFilter( $data ) {
			$config   = $data['config'];
			$videoUrl = $config['event_video_url'];

			$url     = explode( "/", $videoUrl );
			$videoId = end( $url );

			$attr = array(
				"videoFoam=true",
				"playbar=" . ( isset( $config['event_option_play_bar'] ) ? "true" : "false" ),
				"chromeless=" . ( isset( $config['event_option_hide_controls'] ) ? "true" : "false" ),
				"controlsVisibleOnLoad=" . ( isset( $config['event_option_onload'] ) ? "true" : "false" ),
				"fullscreenButton=" . ( isset( $config['event_option_fs'] ) ? "true" : "false" ),
				"popover=true",
				"popoverAnimateThumbnail=true"
			);

			if ( isset( $config['event_video_color'] ) && $config['event_video_color'] != '' ) {
				array_push( $attr, "playerColor=" . $config['event_video_color'] );
			}

			$wistia_url_class = "tve_wistia_popover wistia_embed wistia_async_" . $videoId . " ";
			$queryString      = implode( " ", $attr );
			$uniqIdentifier   = "tve_wistia_" . $videoId . "_" . $config['event_option_uniq'];
			$wistia_popover   = "<div id='" . $uniqIdentifier . "' class='" . $wistia_url_class . $queryString . "' style='display: none;'>Wistia Popover Video</div>";

			return '<div class="tve-wistia-wrap">' . $wistia_popover . '</div>';
		}

		/**
		 * make sure to include wistia popover js only once (E-v1.js)
		 *
		 * @param array $data
		 */
		public function mainPostCallback( $data ) {
			$videoUrl = $data['config']['event_video_url'];
			if ( ! is_editor_page() && isset( $videoUrl ) && $videoUrl != '' ) {
				wp_script_is( 'tl-wistia-popover' ) || wp_enqueue_script( 'tl-wistia-popover', '//fast.wistia.com/assets/external/E-v1.js', array(), '', true );
			}
		}
	}
}