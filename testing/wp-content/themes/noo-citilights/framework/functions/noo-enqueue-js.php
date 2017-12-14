<?php
/**
 * NOO Framework Site Package.
 *
 * Register Script
 * This file register & enqueue scripts used in NOO Themes.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

//
// Site scripts
//
if ( ! function_exists( 'noo_enqueue_site_scripts' ) ) :
	function noo_enqueue_site_scripts() {

		// Main script

		// vendor script
		wp_register_script( 'vendor-modernizr', NOO_FRAMEWORK_URI . '/vendor/modernizr-2.7.1.min.js', null, null, false );
		
		wp_register_script( 'vendor-bootstrap', NOO_FRAMEWORK_URI . '/vendor/bootstrap.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'vendor-hoverIntent', NOO_FRAMEWORK_URI . '/vendor/hoverIntent-r7.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'vendor-superfish', NOO_FRAMEWORK_URI . '/vendor/superfish-1.7.4.min.js', array( 'jquery', 'vendor-hoverIntent' ), null, true );
    	wp_register_script( 'vendor-jplayer', NOO_FRAMEWORK_URI . '/vendor/jplayer/jplayer-2.5.0.min.js', array( 'jquery' ), null, true );
		
		wp_register_script( 'vendor-imagesloaded', NOO_FRAMEWORK_URI . '/vendor/imagesloaded.pkgd.min.js', null, null, true );
		wp_register_script( 'vendor-isotope', NOO_FRAMEWORK_URI . '/vendor/isotope-2.0.0.min.js', array('vendor-imagesloaded'), null, true );
		wp_register_script( 'vendor-infinitescroll', NOO_FRAMEWORK_URI . '/vendor/infinitescroll-2.0.2.min.js', null, null, true );
		wp_register_script( 'vendor-TouchSwipe', NOO_FRAMEWORK_URI . '/vendor/TouchSwipe/jquery.touchSwipe.js', array( 'jquery' ), null, true );
		wp_register_script( 'vendor-carouFredSel', NOO_FRAMEWORK_URI . '/vendor/carouFredSel/jquery.carouFredSel-6.2.1-packed.js', array( 'jquery', 'vendor-TouchSwipe' ), null, true );
		
		wp_register_script( 'vendor-easing', NOO_FRAMEWORK_URI . '/vendor/easing-1.3.0.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'vendor-appear', NOO_FRAMEWORK_URI . '/vendor/jquery.appear.js', array( 'jquery','vendor-easing' ), null, true );
		wp_register_script( 'vendor-countTo', NOO_FRAMEWORK_URI . '/vendor/jquery.countTo.js', array( 'jquery', 'vendor-appear' ), null, true );
		wp_register_script( 'vc_pie_custom', NOO_ASSETS_URI . '/js/jquery.vc_chart.custom.js',array('jquery','progressCircle','vendor-appear'), null, true);
		
		wp_register_script( 'vendor-nivo-lightbox-js', NOO_FRAMEWORK_URI . '/vendor/nivo-lightbox/nivo-lightbox.min.js', array( 'jquery' ), null, true );
		
		wp_register_script( 'vendor-parallax', NOO_FRAMEWORK_URI . '/vendor/jquery.parallax-1.1.3.js', array( 'jquery'), null, true );
		wp_register_script( 'vendor-nicescroll', NOO_FRAMEWORK_URI . '/vendor/nicescroll-3.5.4.min.js', array( 'jquery' ), null, true );
		
		// BigVideo scripts.
		wp_register_script( 'vendor-bigvideo-video',        NOO_FRAMEWORK_URI . '/vendor/bigvideo/video-4.1.0.min.js',        array( 'jquery', 'jquery-ui-slider', 'vendor-imagesloaded' ), NULL, true );
		wp_register_script( 'vendor-bigvideo-bigvideo',     NOO_FRAMEWORK_URI . '/vendor/bigvideo/bigvideo-1.0.0.min.js',     array( 'jquery', 'jquery-ui-slider', 'vendor-imagesloaded', 'vendor-bigvideo-video' ), NULL, true );

		// Bootstrap WYSIHTML5
		wp_register_script( 'vendor-bootstrap-wysihtml5', NOO_FRAMEWORK_URI . '/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.custom.min.js', array( 'jquery', 'vendor-bootstrap'), null, true );
		
		wp_register_script( 'noo-script', NOO_ASSETS_URI . '/js/noo.js', array( 'jquery','vendor-bootstrap', 'vendor-superfish', 'vendor-jplayer' ), null, true );
				
		wp_register_script( 'google-map','http'.(is_ssl() ? 's':'').'://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places',array('jquery'), null , true);
		wp_register_script( 'google-map-infobox', NOO_ASSETS_URI . '/js/infobox.min.js', array( 'jquery' , 'google-map' ), null, true );
		wp_register_script( 'vendor-form', NOO_FRAMEWORK_URI . '/vendor/jquery.form.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'google-map-markerclusterer', NOO_ASSETS_URI . '/js/markerclusterer.min.js', array( 'jquery' , 'google-map' ), null, true );

		wp_register_script( 'noo-property-map', NOO_ASSETS_URI . '/js/property-map.js', array( 'jquery', 'vendor-form', 'google-map-infobox', 'google-map-markerclusterer', 'jquery-ui-slider'), null, true );
		wp_register_script( 'noo-property', NOO_ASSETS_URI . '/js/property.js', array( 'jquery','vendor-carouFredSel','vendor-imagesloaded'), null, true );
		wp_register_script( 'noo-property-google-map', NOO_ASSETS_URI . '/js/map-picker.js', array( 'google-map'), null, true );
		
		wp_register_script( 'noo-img-uploader', NOO_ASSETS_URI . '/js/noo-img-uploader.js', array( 'jquery', 'plupload-all' ), null, true );
		wp_register_script( 'noo-dashboard', NOO_ASSETS_URI . '/js/dashboard.js', array( 'jquery', 'vendor-bootstrap-wysihtml5', 'noo-img-uploader' ), null, true );
		
		wp_localize_script('noo-img-uploader', 'noo_img_upload', array(
			'ajaxurl'        => admin_url('admin-ajax.php'),
			'nonce'          => wp_create_nonce('aaiu_upload'),
			'remove'         => wp_create_nonce('aaiu_remove'),
			'max_files'      =>0,
			'upload_enabled' => true,
			'confirmMsg'     => __('Are you sure you want to delete this?', 'noo'),
			'plupload'       => array(
				'runtimes'         => 'html5,flash,html4',
				'browse_button'    => 'aaiu-uploader',
				'container'        => 'aaiu-upload-container',
				'file_data_name'   => 'aaiu_upload_file',
				'max_file_size'    => (100 * 1000 * 1000) . 'b',
				'url'              => admin_url('admin-ajax.php') . '?action=noo_upload&nonce=' . wp_create_nonce('aaiu_allow'),
				'flash_swf_url'    => includes_url('js/plupload/plupload.flash.swf'),
				'filters'          => array(array('title' => __('Allowed Files', 'noo'), 'extensions' => "jpg,jpeg,gif,png")),
				'multipart'        => true,
				'urlstream_upload' => true,
				)
			));

			wp_localize_script( 'noo-dashboard', 'noo_dashboard', array(
				'delete_property'    => wp_create_nonce('noo_delete_property'),
				'featured_property'  => wp_create_nonce('noo_featured_property'),
				'status_property'    => wp_create_nonce('noo_status_property'),
				'listing_payment'    => wp_create_nonce('noo_listing_payment'),
				'confirmDeleteMsg'   => __('Are you sure you want to delete this Property? This action can\'t be undone.', 'noo'),
				'confirmFeaturedMsg' => __('The number of featured items will be subtracted from your package. This action can\'t be undone. Are you sure you want to do it?', 'noo'),
				'confirmStatusMsg'   => __('Are you sure you want to mark this property as Sold/Rent? This action can\'t be undone.', 'noo'),
			));

		if ( ! is_admin() ) {
			// Post type Property
			wp_enqueue_script( 'vendor-modernizr' );
			
			if( noo_get_option( 'noo_smooth_scrolling', true ) ) {
				wp_enqueue_script('vendor-nicescroll');
			}			

			// Required for nested reply function that moves reply inline with JS
			if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

			//if ( is_masonry_style() ) {
			//	wp_enqueue_script('vendor-infinitescroll');
			//	wp_enqueue_script('vendor-isotope');
			//}

			$is_agents			= is_post_type_archive( 'noo_agent' );
			$is_properties		= is_post_type_archive( 'noo_property' );
			$is_property		= is_singular( 'noo_property' );
			$is_shop			= NOO_WOOCOMMERCE_EXIST && is_shop();
			$is_product			= NOO_WOOCOMMERCE_EXIST && is_product();
			$nooL10n = array(
				'ajax_url'        => admin_url( 'admin-ajax.php', 'relative' ),
				'home_url'        => home_url( '/' ),
				'theme_dir'		  => get_template_directory(),
				'theme_uri'		  => get_template_directory_uri(),
				'is_logged_in'    => is_user_logged_in() ? 'true' : 'false',
				'is_blog'         => is_home() ? 'true' : 'false',
				'is_archive'      => is_post_type_archive('post') ? 'true' : 'false',
				'is_single'       => is_single() ? 'true' : 'false',
				'is_agents'       => $is_agents ? 'true' : 'false',
				'is_properties'   => $is_properties ? 'true' : 'false',
				'is_property'     => $is_property ? 'true' : 'false',
				'is_shop'   	  => $is_shop ? 'true' : 'false',
				'is_product'     => $is_product ? 'true' : 'false',
			);
			
			wp_localize_script('noo-script', 'nooL10n', $nooL10n);
			wp_enqueue_script( 'noo-script' );

			if( class_exists( 'NooProperty' ) ) {
				$nooPropertyL10n = array(
					'ajax_url'        => admin_url( 'admin-ajax.php', 'relative' ),
					'ajax_finishedMsg'=>__('All posts displayed','noo'),
				);
				wp_localize_script('noo-property', 'nooPropertyL10n', $nooPropertyL10n);
				wp_enqueue_script( 'noo-property' );

				if( is_page() ) {
					$page_template = noo_get_post_meta(get_the_ID(), '_wp_page_template', 'default');
					if(strpos($page_template, 'dashboard') !== false) {
						$lat = NooProperty::get_google_map_option('latitude','40.714398');
						$long = NooProperty::get_google_map_option('longitude','-74.005279');

						if( $page_template == 'agent_dashboard_submit.php' ) {
							if( isset( $_GET['prop_edit'] ) && is_numeric( $_GET['prop_edit'] ) ) {
								$edit_id =  intval ($_GET['prop_edit']);
								$lat     = noo_get_post_meta($edit_id,'_noo_property_gmap_latitude',$lat);
								$long    = noo_get_post_meta($edit_id,'_noo_property_gmap_longitude',$long);
							}
							$no_html     = array();
							if( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
								$lat	= wp_kses( $_POST['lat'], $no_html );
								$long	= wp_kses( $_POST['long'], $no_html );
							}
						}
						
						$nooGoogleMap = array(
							'latitude'=>$lat,
							'longitude'=>$long,
						);
						wp_localize_script('noo-property-google-map', 'nooGoogleMap', $nooGoogleMap);

						if( $page_template == 'agent_dashboard_submit.php' ) {
							wp_enqueue_script('noo-property-google-map');
						}

						wp_enqueue_script('noo-dashboard');
					}
				}
			}
		}
	}
add_action( 'wp_enqueue_scripts', 'noo_enqueue_site_scripts' );
endif;
