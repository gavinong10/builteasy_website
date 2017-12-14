<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

MAD_WOOCOMMERCE_CONFIG::enqueue_script('elevate-zoom');

?>
<div class="images product-frame">
	<?php

	if ( has_post_thumbnail() ) {

		$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
		$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );

		$atts_image_single = array(
			'title' => $image_title,
			'data-zoom-image' => $image_link
		);

		if (mad_custom_get_option('zoom_on_product_image')) {
			$atts_image_single['id'] = 'zoom_image';
		}

		$image       = get_the_post_thumbnail( $post->ID, 'shop_single', $atts_image_single );
		$attachment_count = count( $product->get_gallery_attachment_ids() );

		if ( $attachment_count > 0 ) {
			$gallery = 'product-gallery';
		} else {
			$gallery = '';
		}

		if (!$image) {
			if ( wc_placeholder_img_src() ) {
				$image = wc_placeholder_img( 'shop_single' );
			}
		}

		if (mad_custom_get_option('lightbox_on_product_image')) {
			$string = sprintf( '<div class="qv-preview">%s <a data-group="preview" class="qv-review-expand jackboxInit" href="%s"></a></div>', $image, $image_link );
		} else {
			$string = sprintf( '<div class="qv-preview">%s</div>', $image );
		}

		echo apply_filters( 'woocommerce_single_product_image_html', $string, $post->ID );
	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="qv-preview">%s</div>', wc_placeholder_img( 'shop_single' ) ), $post->ID );
	}
	?>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div><!--/ .images-->
