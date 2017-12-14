<?php

class WPBakeryShortCode_VC_mad_portfolio_image_list extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'images'   => "",
			'img_size' => "",
			'class'	   => ""
		), $atts, 'vc_mad_portfolio_image_list');

		return $this->html();
	}

	public function html() {

		$images = $img_size = $output = '';

		extract($this->atts);

		$zoom_image = mad_custom_get_option('zoom_image', '');

		$images = explode( ',', $images);

		if (strpos($img_size, '^')) {
			$img_sizes = explode( '^', $img_size);
		} else {
			$img_sizes = array($img_size);
		}

		foreach ( $images as $id => $attach_id ) {
			if ($attach_id > 0) {
				$img_new_size = ($img_sizes[$id]) ? $img_sizes[$id] : '';
				$post_thumbnail = MAD_HELPER::get_the_thumbnail($attach_id, $img_new_size, array( 'class' => 'tr_all_long_hover') );
			} else {
				$post_thumbnail = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
			}

			$output .= '<div class="image-overlay '. esc_attr($zoom_image) .'">';
				$output .= '<div class="folio-image-item photoframe">';
					$output .= $post_thumbnail;
				$output .= '</div>';
			$output .= '</div>';
		}
		return $output;
	}

}