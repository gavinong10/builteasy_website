<?php

class WPBakeryShortCode_VC_mad_banners extends WPBakeryShortCode {

	public $atts = array();
	public $content = '';

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'type' => 'type-1',
			'border_color' => '',
			'bg_color' => '',
			'image' => '',
			'link' => "",
			'css_animation' => ''
		), $atts, 'vc_mad_banners');

		$this->content = $content;

		return $this->html();
	}

	public function getCSSAnimation($css_animation) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script('waypoints');
			$output = ' animate-' . $css_animation;
		}
		return $output;
	}

	public function html() {

		$type = $border_color = $bg_color = $image = $styles = $link = $img_size = $css_animation = '';

		extract($this->atts);

		$css_animation = $this->getCSSAnimation($css_animation);
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $type . ' ' . $css_animation, $this->settings['base']);

		if ($type == 'type-1') {
			$img_size = '';
		} else {
			$img_size = array(60, 60);
		}

		$img_id = preg_replace('/[^\d]/', '', $image);
		$img = wpb_getImageBySize(array(
			'attach_id' => $img_id,
			'thumb_size' => $img_size,
		));

		if ($img == null) {
			$img = '';
		} else {
			$img =  '<img src="' . $img['p_img_large'][0] . '" />';
		}

		if ($type == 'type-2') {
			if ($border_color != '') {
				$style = "border-color: {$border_color}; ";
			}

			if ($bg_color != '') {
				$style .= "background-color: {$bg_color};";
			}

			if (!empty($style)) {
				$styles = "style='{$style}'";
			}
		}

		$link = ($link == '||') ? '' : $link;
		$link = vc_build_link($link);
		$a_href = $link['url'];
		$a_title = $link['title'];
		($link['target'] != '') ? $a_target = $link['target'] : $a_target = '_self';

		ob_start(); ?>

		<div <?php echo $styles ?> class="banner-area <?php echo $css_class ?>">

			<?php if (isset($img) && !empty($img)): ?>

				<?php if (!empty($a_href) && $type == 'type-1'): ?>
					<a class="banner-button" title="<?php echo esc_attr($a_title) ?>" target="<?php echo esc_attr($a_target) ?>" href="<?php echo esc_url($a_href) ?>">
				<?php endif; ?>

					<div class="banner-image">
						<?php echo $img; ?>
					</div><!--/ .banner-image-->

				<?php if (!empty($a_href) && $type == 'type-1'): ?>
					</a>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ($type == 'type-2'): ?>

				<div class="banner-caption">

					<div class="banner-in-caption">

						<?php if (!empty($a_href) && $type == 'type-2'): ?>
							<a target="<?php echo esc_attr($a_target) ?>" href="<?php echo esc_url($a_href) ?>">
						<?php endif; ?>

							<?php echo wpb_js_remove_wpautop($this->content, true); ?>

						<?php if (!empty($a_href) && $type == 'type-2'): ?>
							</a>
						<?php endif; ?>

					</div><!--/ .banner-in-caption-->

				</div><!--/ .banner-caption-->

			<?php endif; ?>

		</div><!--/ .banner-area-->

		<?php return ob_get_clean();
	}

}