<?php

/**
 * Class Vc_Vendor_YoastSeo
 * @since 4.4
 */
Class Vc_Vendor_YoastSeo implements Vc_Vendor_Interface {

	/**
	 * Created to improve yoast multiply calling wpseo_pre_analysis_post_content filter.
	 * @since 4.5.3
	 * @var string - parsed post content
	 */
	protected $parsedContent;

	/**
	 * Add filter for yoast.
	 * @since 4.4
	 */
	public function load() {
		if ( class_exists( 'WPSEO_Metabox' ) && ( 'admin_page' === vc_mode() || 'admin_frontend_editor' === vc_mode() ) ) {
			add_filter( 'wpseo_pre_analysis_post_content', array( &$this, 'filterResults' ) );
		}
	}

	/**
	 * Properly parse content to detect images/text keywords.
	 * @since 4.4
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function filterResults( $content ) {
		if ( empty( $this->parsedContent ) ) {
			global $post, $wp_the_query;
			$wp_the_query->post = $post; // since 4.5.3 to avoid the_post replaces
			/**
			 * @since 4.4.3
			 * vc_filter: vc_vendor_yoastseo_filter_results
			 */
			do_action( 'vc_vendor_yoastseo_filter_results' );
			$this->parsedContent = do_shortcode( shortcode_unautop( $content ) );
			wp_reset_query();
		}

		return $this->parsedContent;
	}
}