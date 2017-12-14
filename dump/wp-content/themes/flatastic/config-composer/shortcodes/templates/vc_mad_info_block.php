<?php

class WPBakeryShortCode_VC_mad_info_block extends WPBakeryShortCode {

	public $atts = array();
	public $content = '';

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'icon' => 'none',
			'type' => '',
			'bg_color' => '',
			'link' => '',
			'css_animation' => ''
		), $atts, 'vc_mad_info_block');

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

		$type = $output = $title = $icon = $link = $bg_color = $css_animation = '';

		extract($this->atts);

		$box_style = $this->getExtraClass($type);
		$animations = $this->getExtraClass($this->getCSSAnimation($css_animation));
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'info-block' . $box_style . $animations, $this->settings['base']);

		$link = ($link == '||') ? '' : $link;
		$link = vc_build_link($link);
		$a_href = $link['url'];
		$a_title = $link['title'];
		($link['target'] != '') ? $a_target = $link['target'] : $a_target = '_self';

		if (!empty($icon)) {
			$icon = '<i class="fa fa-'. $icon .'"></i>';
		}

		if (!empty($title)) {
			$title = '<h4 class="fw_medium m_bottom_15">'. $title .'</h4>';
		}

		if ($type == 'type-4') {
			$bg_color = 'style="background-color: '. $bg_color.'"';
		}

		ob_start() ?>

		<div <?php echo ($type == 'type-4') ? $bg_color : '' ?> class="<?php echo $css_class ?>">

			<?php if ($icon != 'none'): ?>
				<div class="icon-wrap"><?php echo $icon ?></div>
			<?php endif; ?>

			<div class="icon-text-holder">

				<?php if ($type !== 'type-4'): ?>
					<?php echo $title ?>
				<?php endif; ?>

				<?php echo wpb_js_remove_wpautop($this->content, true) ?>

				<?php if (!empty($a_href)): ?>
					<a class="icon-text-link" title="<?php echo esc_attr($a_title) ?>" target="<?php echo $a_target ?>" href="<?php echo esc_url($a_href) ?>">
						<?php echo $a_title ?>
					</a>
				<?php endif; ?>

			</div><!--/ .icon-text-holder-->

		</div><!--/ .info-block-->

		<?php return ob_get_clean();
	}

}