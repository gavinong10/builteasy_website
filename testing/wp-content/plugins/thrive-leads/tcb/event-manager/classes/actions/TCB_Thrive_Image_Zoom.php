<?php

/**
 * Created by PhpStorm.
 * User: sala
 * Date: 04.12.2015
 * Time: 14:44
 */
class TCB_Thrive_Image_Zoom extends TCB_Event_Action_Abstract {


	/**
	 * Should return the user-friendly name for this Action
	 *
	 * @return string
	 */
	public function getName() {
		return __( 'Zoom Image', 'thrive-cb' );
	}

	/**
	 * Should output the settings needed for this Action when a user selects it from the list
	 *
	 * @param mixed $data existing configuration data, etc
	 *
	 * @return string html
	 */
	public function renderSettings( $data ) {
		return $this->renderTCBSettings( 'zoom', $data );
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
	 * The function will be called in the context of the element
	 *
	 * The output MUST be a valid JS function definition.
	 *
	 * @return string the JS function definition (declaration + body)
	 */
	public function getJsActionCallback() {
		return '
            function (trigger, action, config) {
                var $element = jQuery(this),
                    image_src = $element.attr("src"),
                    $lightbox = jQuery("#tve_zoom_lightbox"),
                    windowWidth = window.innerWidth,
                    windowHeight = window.innerHeight,
                    img_size = $element.data("tve-zoom-clone")
                    resizeScale = windowWidth < 600 ? 0.8 : 0.9;

                if (typeof img_size === "undefined") {
                    var $clone = $element.clone();
                    $clone.css({
                        position: "absolute",
                        width: "",
                        height: "",
                        left: "-8000px",
                        top: "-8000px"
                    }).removeAttr("width height");
                    $clone.appendTo("body");

                    img_size = {
                        "originalWidth": $clone.width(),
                        "width": $clone.width(),
                        "originalHeight": $clone.height(),
                        "height": $clone.height()
                    };

                    if (img_size.originalWidth > windowWidth * resizeScale || img_size.originalHeight > windowHeight * resizeScale) {
                        var widthPercent = img_size.originalWidth / windowWidth,
                            heightPercent = img_size.originalHeight / windowHeight;

                        img_size.width = ((widthPercent > heightPercent) ? (windowWidth * resizeScale) : (windowHeight * resizeScale * (img_size.originalWidth / img_size.originalHeight)));
                        img_size.height = ((widthPercent > heightPercent) ? (windowWidth * resizeScale * (img_size.originalHeight / img_size.originalWidth)) : (windowHeight * resizeScale));
                    }
                    $element.data("tve-zoom-clone", img_size);
                }

                if ($lightbox.length > 0) {
                    $lightbox.show();
                    jQuery("#tve_zoom_overlay").show();
                } else {
                    jQuery("body").append(
                        "<div id=\'tve_zoom_lightbox\'>" +
                        "<div class=\'tve_close_lb thrv-icon-cross\'></div>" +
                        "<div id=\'tve_zoom_image_content\'></div>" +
                        "</div>" +
                        "<div id=\'tve_zoom_overlay\'></div>"
                    );
                    $lightbox = jQuery("#tve_zoom_lightbox");

                    var tve_close_lb = function () {
                        $lightbox.hide();
                        jQuery("#tve_zoom_overlay").hide();
                    };
                    /* set listeners for closing the lightbox */
                    jQuery(document).on("click", ".tve_close_lb", tve_close_lb);
                    jQuery(document).on("click", "#tve_zoom_overlay", tve_close_lb);
                    jQuery(document).keyup(function (e) {
                        if (e.keyCode == 27) {
                            tve_close_lb();
                        }
                    });

                    jQuery(window).resize(function () {
                        var $lightbox = jQuery("#tve_zoom_lightbox"),
                            _sizes = $lightbox.data("data-sizes"),
                            windowWidth = window.innerWidth,
                            windowHeight = window.innerHeight,
                            resizeScale = windowWidth < 600 ? 0.8 : 0.9;

                       if (_sizes.originalWidth > windowWidth * resizeScale || _sizes.originalHeight > windowHeight * resizeScale) {
                            var widthPercent = _sizes.originalWidth / windowWidth,
                                heightPercent = _sizes.originalHeight / windowHeight;

                            _sizes.width = ((widthPercent > heightPercent) ? (windowWidth * resizeScale) : (windowHeight * resizeScale * (_sizes.originalWidth / _sizes.originalHeight)));
                            _sizes.height = ((widthPercent > heightPercent) ? (windowWidth * resizeScale * (_sizes.originalHeight / _sizes.originalWidth)) : (windowHeight * resizeScale));
                        }

                        $lightbox.width(_sizes.width);
                        $lightbox.height(_sizes.height);

                        $lightbox.css("margin-left", -(_sizes.width+30) / 2);
                        $lightbox.css("margin-top", -(_sizes.height+30) / 2);
                    });
                }

                $lightbox.data("data-sizes", img_size);

                jQuery("#tve_zoom_image_content").html("<img src=\'" + image_src + "\'/>");

                $lightbox.width(img_size.width);
                $lightbox.height(img_size.height);

                $lightbox.css("margin-left", -(img_size.width+30) / 2);
                $lightbox.css("margin-top", -(img_size.height+30) / 2);
            }';
	}

	/**
	 * should check if the current action is available to be displayed in the lists inside the event manager
	 * @return bool
	 */
	public function isAvailable() {
		if ( ! empty( $_REQUEST['elementType'] ) && $_REQUEST['elementType'] == "img" ) {
			return true;
		}

		return false;
	}
}