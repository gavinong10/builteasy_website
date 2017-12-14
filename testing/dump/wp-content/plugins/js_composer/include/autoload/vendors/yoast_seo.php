<?php
/**
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 *
 * Used to initialize plugin yoast vendor.
 */
add_action( 'plugins_loaded', 'vc_init_vendor_yoast' );
function vc_init_vendor_yoast() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
	if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || class_exists( 'WPSEO_Metabox' ) ) {
		require_once vc_path_dir( 'VENDORS_DIR', 'plugins/class-vc-vendor-yoast_seo.php' );
		$vendor = new Vc_Vendor_YoastSeo();
		add_action( 'vc_after_set_mode', array(
			$vendor,
			'load'
		) );
	}
}