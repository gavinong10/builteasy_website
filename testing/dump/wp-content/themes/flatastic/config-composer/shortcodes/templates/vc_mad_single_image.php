<?php

class WPBakeryShortCode_VC_mad_single_image extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'image' => '',
			'img_size' => '',
			'alignment' => '',
			'img_link_large' => false,
			'img_link' => '',
			'link' => '',
			'img_link_target' => '_self'
		), $atts, 'vc_mad_single_image');

		return $this->html();
	}

	public function html() {
		$output = $el_class = $image = $img_size = $img_link_target = $link = $img_link_large = $title = $alignment = '';

		extract($this->atts);

		$zoom_image = mad_custom_get_option('zoom_image', '');

		$img = array();
		$img_id = preg_replace('/[^\d]/', '', $image);
		$thumbnail_atts = array(
			'alt' => get_the_title($img_id),
			'title' => get_the_title($img_id)
		);

		$img['thumbnail'] = MAD_HELPER::get_the_thumbnail($img_id, $img_size, $thumbnail_atts);

		if ($img == NULL) {
			$img['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
		}

		$el_class = $this->getExtraClass( $el_class );
		$a_class = $link_to = '';

		if ($img_link_large == true) {
			$link_to = wp_get_attachment_image_src($img_id, '');
			$link_to = $link_to[0];
			$a_class = ' class="jackbox"';
		} else if (strlen($link) > 0) {
			$link_to = $link;
		} else if (!empty($img_link)) {
			$link_to = $img_link;
			if (!preg_match( '/^(https?\:\/\/|\/\/)/', $link_to)) {
				$link_to = 'http://' . $link_to;
			}
		}

		$img_output = $img['thumbnail'];
		$image_string = !empty($link_to) ? '<a' . $a_class . ' href="' . esc_url($link_to) . '"' . ' data-group="image-'. rand() .'" target="' . $img_link_target . '"'. '>' . $img_output . '</a>' : $img_output;
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element' . $el_class, $this->settings['base'], $this->atts );

		$css_class .= ' vc_align_' . $alignment;

		$output .= "\n\t" . '<div class="' . $css_class . '">';
		$output .= "\n\t\t" . '<div class="wpb_wrapper">';
		$output .= "\n\t\t\t" . wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_singleimage_heading'));
		$output .= "\n\t\t\t\t" . '<div class="image-overlay ' . esc_attr($zoom_image) . '">';
			$output .= "\n\t\t\t\t\t" . '<div class="photoframe wrapper">';
				$output .= "\n\t\t\t" . $image_string;
			$output .= "\n\t\t\t\t\t" . '</div>';
		$output .= "\n\t\t\t\t" . '</div>';
		$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
		$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_single_image' );

		return $output;
	}

}