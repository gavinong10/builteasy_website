<?php
$animationClasses = array();
foreach ( array_keys( $this->_animations ) as $animation ) {
	$animationClasses [] = 'tve_lb_anim_' . $animation;
}
$animationClasses = implode( ' ', $animationClasses );
/* adding <script type="text/javascript"> just for editor autocompletion */
?>
<script type="text/javascript">
	function (trigger, action, config) {

		function getBrowserScrollSize() {
			var $ = jQuery;
			var css = {
				"border": "none",
				"height": "200px",
				"margin": "0",
				"padding": "0",
				"width": "200px"
			};

			var inner = $("<div>").css($.extend({}, css));
			var outer = $("<div>").css($.extend({
				"left": "-1000px",
				"overflow": "scroll",
				"position": "absolute",
				"top": "-1000px"
			}, css)).append(inner).appendTo("body")
				.scrollLeft(1000)
				.scrollTop(1000);

			var scrollSize = {
				"height": (outer.offset().top - inner.offset().top) || 0,
				"width": (outer.offset().left - inner.offset().left) || 0
			};

			outer.remove();
			return scrollSize;
		}

		var $target = jQuery("#tve_thrive_lightbox_" + config.l_id).css('display', ''),
			animation = config.l_anim ? config.l_anim : "instant",
			$body = jQuery('body'),
			$html = jQuery('html'),
			overflow_hidden = 'tve-o-hidden tve-l-open tve-hide-overflow',
			scroll_width = getBrowserScrollSize().width,
			oPadding = parseInt($body.css('padding-right'));

		function close_it($lightbox, skip_body_scroll) {
			$lightbox.find('.thrv_responsive_video iframe, .thrv_responsive_video video').each(function () {
				var $this = jQuery(this);
				$this.attr('data-src', $this.attr('src'));
				$this.attr('src', '');
			});

			$lightbox.removeClass('tve_lb_open tve_lb_opening').addClass('tve_lb_closing');
			if (typeof skip_body_scroll === 'undefined') {
				$body.removeClass(overflow_hidden).css('padding-right', '');
				$html.removeClass(overflow_hidden)
			}
			setTimeout(function () {
				$lightbox.attr('class', '').css('display', 'none').find('tve_p_lb_content').trigger('tve.lightbox-close');
			}, 300);
		}

		$target.off().on("click", ".tve_p_lb_close", function () {
			close_it($target);
		});

		$body.off('keyup.tve_lb_close').on('keyup.tve_lb_close', function (e) {
			if (e.which == 27) {
				close_it($target);
			}
		});

		$target.children('.tve_p_lb_overlay').off('click.tve_lb_close').on('click.tve_lb_close', function () {
			close_it($target);
		});

		/* close any other opened lightboxes */
		close_it(jQuery('.tve_p_lb_background.tve_lb_open'), true);

		$target.addClass('tve_p_lb_background tve_lb_anim_' + animation);

		$body.addClass(overflow_hidden);
		$html.addClass(overflow_hidden);

		var wHeight = jQuery(window).height(),
			page_has_scroll = wHeight < jQuery(document).height();

		if (page_has_scroll) {
			$body.css('padding-right', (oPadding + scroll_width) + 'px');
		}

		$target.find('.thrv_responsive_video iframe, .thrv_responsive_video video').each(function () {
			var $this = jQuery(this);
			if ($this.attr('data-src')) {
				$this.attr('src', $this.attr('data-src'));
			}
		});

		setTimeout(function () {

			$target.addClass('tve_lb_opening');

			/* reload any iframe that might be in there, this was causing issues with google maps embeds in hidden tabs */
			$target.find('iframe').each(function () {
				var $this = jQuery(this);
				if ($this.data('tve_ifr_loaded')) {
					return;
				}
				$this.data('tve_ifr_loaded', 1).attr('src', $this.attr('src'));
			});

			setTimeout(function () {
				var $lContent = $target.find('.tve_p_lb_content'),
					cHeight = $lContent.outerHeight(true),
					top = (wHeight - cHeight) / 2;
				$target.find('.tve_p_lb_overlay').css({
					height: (cHeight + 80) + 'px',
					'min-height': wHeight + 'px'
				});
				$lContent.css('top', (top < 40 ? 40 : top) + 'px');
				if (cHeight + 40 > wHeight) {
					$target.addClass('tve-scroll');
				}
			}, 0);
		}, 20);

		setTimeout(function () {
			$target.removeClass('tve_lb_opening').addClass('tve_lb_open').find('.tve_p_lb_content').trigger('tve.lightbox-open');
		}, 300);

		return false;
	}
	;
</script>