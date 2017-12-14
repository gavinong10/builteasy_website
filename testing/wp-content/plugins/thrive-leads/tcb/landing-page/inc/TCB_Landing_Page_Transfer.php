<?php

/**
 * folder where we keep the landing pages downloaded from the cloud, relative to the wp-uploads folder
 */
defined( 'TVE_CLOUD_LP_FOLDER' ) || define( 'TVE_CLOUD_LP_FOLDER', 'tcb_lp_templates' );


/**
 * handles the communication with the landing pages API located at TCB_Landing_Page_Cloud_Templates_Api::API_URL
 *
 *
 * Class TCB_Landing_Page_Cloud_Templates_Api
 */
class TCB_Landing_Page_Cloud_Templates_Api {

	const API_KEY = '@(#$*%)^SDFKNgjsdi870234521SADBNC#';
	const API_PASS = '!!@#ThriveIsTheBest123$$@#';

	const API_URL_DEV = 'http://thrive.tpl/cloud-api/index-api.php';
	const API_URL = 'http://landingpages.thrivethemes.com/cloud-api/index-api.php';
	const API_SERVICE = 'http://service-api.thrivethemes.com/cloud-templates-api';
	const API_SERVICE_DEV = 'http://thrive.service/cloud-templates-api';

	/**
	 * @var TCB_Landing_Page_Cloud_Templates_Api
	 */
	protected static $_instance = null;

	protected $received_auth_header = '';

	protected $secret_key = '@#$()%*%$^&*(#@$%@#$%93827456MASDFJIK3245';

	/**
	 * holds the last response from the API server
	 * @var array
	 */
	protected $response = array();

	/**
	 * holds the last request sent to the API server.
	 *
	 * @var array
	 */
	protected $request = array();

	/**
	 * singleton
	 */
	public static function getInstance() {
		if ( null === self::$_instance ) {
			self::$_instance = new TCB_Landing_Page_Cloud_Templates_Api();
		}

		return self::$_instance;
	}

	/**
	 * constructor - use getInstance
	 */
	protected function __construct() {

	}

	/**
	 * get the template list
	 *
	 * @throws Exception if response data and success properties does not have expected values
	 *
	 * @return array
	 */
	public function getTemplateList() {
		$params = array(
			'route' => 'getAll',
		);

		$response = $this->_request( $params );
		$data     = json_decode( $response, true );

		if ( empty( $data['success'] ) ) {
			throw new Exception( $data['error_message'] );
		}

		if ( ! isset( $data['data'] ) ) {
			throw new Exception( 'Could not fetch templates.' );
		}

		$this->_validateReceivedHeader( $data );

		$templates = $data['data'];

		return $templates;
	}

	/**
	 * download the zip file for a template
	 *
	 * @param string $template
	 *
	 * @return string the new template name (with the cloud prefix prepended)
	 *
	 * @throws Exception
	 */
	public function download( $template ) {
		/**
		 * first make sure we can save the downloaded template
		 */
		$upload = wp_upload_dir();
		if ( ! empty( $upload['error'] ) ) {
			throw new Exception( $upload['error'] );
		}

		/* create folders in uploads - make sure all of the required folders exist */
		$this->createFolders( $upload, $template );

		$params = array(
			'route' => 'download',
			'tpl'   => $template
		);

		$body = $this->_request( $params );

		$control = array(
			'auth' => $this->request['headers']['X-Thrive-Authenticate'],
			'tpl'  => $template,
		);

		/**
		 * this means an error -> error message is json_encoded
		 */
		if ( empty( $this->received_auth_header ) ) {
			$data = json_decode( $body, true );
			throw new Exception( isset( $data['error_message'] ) ? $data['error_message'] : 'Invalid response: ' . $body );
		}

		$this->_validateReceivedHeader( $control );

		/**
		 * at this point, $body holds the contents of the zip file
		 */
		$zip_path = trailingslashit( $upload['basedir'] ) . TVE_CLOUD_LP_FOLDER . '/' . $template . '.zip';

		tve_wp_upload_bits( $zip_path, $body );

		$transfer = new TCB_Landing_Page_Transfer();

		$result = $transfer->importFromCloud( $zip_path, $template );

		/* unlink the zip archive and the configuration file */
		@unlink( $zip_path );
		@unlink( trailingslashit( dirname( $zip_path ) ) . $template . '.json' );

		return $result;
	}

	/**
	 * send an API request
	 *
	 * @param array $params body params to be sent in POST data
	 *
	 * @return string the response body
	 *
	 * @throws Exception
	 */
	protected function _request( $params ) {
		$params['pw'] = self::API_PASS;
		$headers      = array(
			'X-Thrive-Authenticate' => $this->_buildAuthString( $params ),
		);

		$this->request = array(
			'headers' => $headers,
			'body'    => $params,
		);

		$url = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? self::API_SERVICE_DEV : self::API_SERVICE;

		$url = add_query_arg( array(
			'p' => $this->calc_hash( $params ),
		), $url );

		$response = wp_remote_post( $url, array(
			'timeout'   => 20,
			'headers'   => $headers,
			'body'      => $params,
			'sslverify' => false,
		) );

		if ( $response instanceof WP_Error ) {
			throw new Exception( $response->get_error_message() );
		}

		$this->received_auth_header = wp_remote_retrieve_header( $response, 'X-Thrive-Authenticate' );
		/**
		 * also try lowercased, just in case this will be dependent of server
		 */
		if ( empty( $this->received_auth_header ) ) {
			$this->received_auth_header = wp_remote_retrieve_header( $response, 'x-thrive-authenticate' );
		}

		$this->response = $response;

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * build an authentication string, from each of the POST (or $data) values, concatenated with self::API_KEY between '|'
	 *
	 * @param array $data
	 *
	 * @return string md5 hash of the string above
	 */
	protected function _buildAuthString( $data = null ) {
		$string = '';

		if ( null === $data ) {
			$data = $_POST;
		}

		foreach ( $data as $field => $value ) {
			if ( is_array( $value ) ) {
				$value = serialize( $value );
			}
			$string .= $field . '=' . $value;
			$string .= '|' . self::API_KEY . '|';
		}

		return md5( $string );
	}

	/**
	 * Calculates the has that signs the requests for service.thrivethemes.com
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function calc_hash( $data ) {
		return md5( $this->secret_key . serialize( $data ) . $this->secret_key );
	}

	/**
	 * validate the received header containing authentication data
	 *
	 *
	 * @param array $data
	 *
	 * @throws Exception
	 */
	protected function _validateReceivedHeader( $data ) {
		if ( $this->received_auth_header != $this->_buildAuthString( $data ) ) {
			throw new Exception( 'Invalid data received from the API' );
		}
	}

	/**
	 * create all the necessary folders inside a tcb_lp_templates folder in the wp-uploads dir
	 * required folders are:
	 *
	 * - js
	 * - lightboxes
	 * - menu
	 * - templates
	 *            - css
	 *                 - fonts
	 *                 - images
	 *            - thumbnails
	 *
	 *
	 * @param array $wp_upload
	 * @param string $template_key
	 */
	public function createFolders( $wp_upload, $template_key ) {
		$folder_list = array(
			'js',
			'lightboxes',
			'menu',
			'templates',
			'templates/css',
			'templates/css/fonts',
			'templates/css/fonts/' . $template_key,
			'templates/css/images',
			'templates/thumbnails',
		);

		/**
		 * this filter would allow adding some extra custom folders (if needed in the future)
		 *
		 */
		$folder_list = apply_filters( 'tcb_lp_extra_folders_create', $folder_list );

		$base = trailingslashit( $wp_upload['basedir'] ) . TVE_CLOUD_LP_FOLDER . '/';

		foreach ( $folder_list as $folder ) {
			if ( false === wp_mkdir_p( $base . $folder ) ) {
				throw new Exception( 'Could not create the templates folder' );
			}
		}

	}

}

/**
 * Import / Export functionality for landing pages
 *
 * TODO: this could be the entry point for downloading LP also
 */
class TCB_Landing_Page_Transfer {
	/**
	 * holds ids of the exported lightboxes, to make sure we don't create an infinite loop
	 * @var array
	 */
	private $exported_lightboxes = array();

	/**
	 * flag that will be true when the icon pack needs to be included in the export
	 *
	 * @var bool
	 */
	private $export_icon_pack = false;

	/**
	 * holds the current WP Page (or landing page) that will be replaced with the imported landing page, OR the one that is being exported right now
	 *
	 * @var WP_Post
	 */
	private $current_page = null;

	/**
	 *
	 * @var array wordpress uploads folder
	 */
	private $wp_upload_dir = null;

	/**
	 * hold a copy of the config array for the import process
	 *
	 * @var array
	 */
	private $import_config = array();

	/**
	 * get the wp upload dir info
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	protected function getUploadDir() {
		if ( ! is_null( $this->wp_upload_dir ) ) {
			return $this->wp_upload_dir;
		}

		$this->wp_upload_dir = wp_upload_dir();

		if ( ! empty( $this->wp_upload_dir['error'] ) ) {
			throw new Exception( sprintf( __( 'Could not determine uploads folder (%s)', 'thrive-cb' ), $this->wp_upload_dir['error'] ) );
		}

		return $this->wp_upload_dir;
	}

	/**
	 * create a zip file containing the landing page export
	 *
	 * @param int $page_id
	 * @param string $template_name
	 * @param int $thumbnail_attachment_id the id of the attachment to use as a thumbnail
	 *
	 * @return array with information about the zip file:
	 *      (
	 *          'path' => full path to the zip file
	 *          'url' => web-accessible url to the zip file
	 *      )
	 *
	 * @throws Exception
	 *
	 */
	public function export( $page_id, $template_name, $thumbnail_attachment_id = 0 ) {
		/* first, some general checks */
		if ( ! class_exists( 'ZipArchive' ) ) {
			throw new Exception( __( 'The PHP ZipArchive extension must be enabled in order to use this functionality. Please contact your hosting provider.', 'thrive-cb' ) );
		}

		/* build the meta-values config for the landing page */
		$config = $this->getTCBMeta( $page_id );

		$config['cloud_template'] = tve_is_cloud_template( $config['tve_landing_page'] );

		$config['tve_version'] = TVE_VERSION; /* just to be able to validate imports later on, if we'll need some kind of compatibility checks */

		$page_content = $config['tve_updated_post'];
		unset( $config['tve_updated_post'] ); // we do not need these in the config file

		/* parse and replace all image urls (both from img src and background: url(...) */
		$image_map = $this->exportImageMap( $page_content, $config );

		/* handle any possible included lightboxes */
		$config['lightbox'] = array();

		/* include any lightbox that's setup as a page-level event */
		if ( ! empty( $config['tve_page_events'] ) ) {
			foreach ( $config['tve_page_events'] as $event ) {
				$this->exportLightboxFromEvent( $event, $config, $image_map );
			}
		}

		/* parse the page contents to include any lightbox that's setup inside the html contents, e.g. from a link / button */
		$this->exportParseLightboxes( $page_content, $config, $image_map );

		$template_name = sanitize_title( $template_name );

		$uploads_dir = $this->getUploadDir();

		$zip_filename = 'tve-lp-' . $template_name . '.zip';

		@unlink( $uploads_dir['path'] . $zip_filename );

		WP_Filesystem();

		$old_umask = umask( 0 );
		$zip       = new ZipArchive();
		if ( $zip->open( trailingslashit( $uploads_dir['path'] ) . $zip_filename, ZipArchive::CREATE ) !== true ) {
			throw new Exception( 'Could not create zip archive' );
		}

		/* add images directly to the zip archive, in the images/ folder */
		$zip->addEmptyDir( 'images' );
		foreach ( $image_map as $key => $info ) {
			$config['image_map'][ $key ] = $info['name'];
			$zip->addFile( $info['path'], 'images/' . $key . '.img_file' );
		}

		/* check if the icon pack needs to be exported and include it in the archive file */
		$this->exportIconPack( $zip, $config );

		/* check if there is any font added through the Font Import Manager */
		$this->exportFontPack( $zip, $config );

		/* add lightbox template html to the lightboxes/ folder */
		if ( ! empty( $config['lightbox'] ) ) {
			$zip->addEmptyDir( 'lightboxes' );
			foreach ( $config['lightbox'] as $id => $data ) {
				$zip->addFromString( 'lightboxes/' . $id . '.html', $data['tve_updated_post'] );
				unset( $config['lightbox'][ $id ]['tve_updated_post'] );
				/* any extra icon_packs that might be included in the lightbox */
				$this->exportExtraIconPacks( $zip, $config['lightbox'][ $id ] );

				$this->exportFontPack( $zip, $config['lightbox'][ $id ] );
			}
		}

		/* add the font_pack config to the configuration array, if it has been included */
		$config['font_pack'] = isset( $this->exported_font_pack ) ? $this->exported_font_pack : array();

		/* add the contents of the page, in a separate file */
		$zip->addFromString( 'lp.html', $page_content );

		/* it's possible that this landing page or some of it's lightboxes have some extra icon packs (added from previous imports) */
		$this->exportExtraIconPacks( $zip, $config );

		/* also include the thumbnail for the landing page, if any */
		if ( ! empty( $thumbnail_attachment_id ) ) {
			$this->exportThumbnail( $thumbnail_attachment_id, $zip, $config );
		}

		/* add the json config file */
		$zip->addFromString( 'lp.json', json_encode( $config ) );

		if ( ! @$zip->close() ) {
			throw new Exception( 'Could not write the zip file' );
		}

		umask( $old_umask );

		return array(
			'path' => trailingslashit( $uploads_dir['path'] ) . $zip_filename,
			'url'  => trailingslashit( $uploads_dir['url'] ) . $zip_filename,
		);
	}

	/**
	 * also include the thumbnail chosen by the user, if empty => the default LP thumbnail will be used
	 *
	 * @param int $thumbnail_attachment_id
	 * @param ZipArchive $zip
	 * @param array $config
	 */
	protected function exportThumbnail( $thumbnail_attachment_id, ZipArchive $zip, & $config ) {
		if ( empty( $thumbnail_attachment_id ) || ! wp_attachment_is_image( $thumbnail_attachment_id ) ) {
			return;
		}

		$file = get_attached_file( $thumbnail_attachment_id, true );
		if ( ! is_file( $file ) ) {
			return;
		}

		$thumb_name = basename( $file );

		$zip->addEmptyDir( 'thumbnail' );
		if ( false === $zip->addFile( $file, 'thumbnail/' . $thumb_name ) ) {
			$zip->deleteName( 'thumbnail' );

			return;
		}

		$config['thumbnail'] = $thumb_name;

	}

	/**
	 *
	 * parse the HTML content, search for local images (stored on the server) AND images used in inline styles, retrieve the image paths and copy them to the $dest folder for each encountered image
	 * it will also replace the image srcs in HTML to a list of keys
	 *
	 * @param string $page_content
	 *
	 * @return array an image map, image_key => info
	 */
	protected function exportParseImages( & $page_content ) {
		$site_url = str_replace( array( 'http://', 'https://', '//' ), '', site_url() );

		/* regular expression that finds image links that point to the current server */
		$image_regexp = '#([\'"]*)(http://|https://|//)(' . preg_quote( $site_url, '#' ) . ')(.+?)(\.[png|gif|jpg|jpeg]+)\\1#is';

		$image_map = array();

		if ( preg_match_all( $image_regexp, $page_content, $matches ) ) {

			foreach ( $matches[4] as $index => $src ) {
				$img_src    = $matches[2][ $index ] . $matches[3][ $index ] . $src . $matches[5][ $index ];
				$no_qString = explode( '?', $img_src );
				$img_src    = $no_qString[0];

				$server_path = $matches[4][ $index ] . $matches[5][ $index ];

				$full_image_path = untrailingslashit( ABSPATH ) . $server_path;
				if ( ! file_exists( $full_image_path ) ) {
					continue;
				}

				$replacement               = md5( $img_src );
				$image_map[ $replacement ] = array(
					'name' => basename( $img_src ),
					'path' => $full_image_path,
					'url'  => $img_src
				);

				$page_content = str_replace( $img_src, '{{img=' . $replacement . '}}', $page_content );
			}
		}

		return $image_map;
	}

	/**
	 * get all the meta fields used by TCB for a page / lightbox
	 *
	 * @param int $post_id
	 *
	 * @return array
	 */
	protected function getTCBMeta( $post_id ) {
		$config = array();

		foreach ( tve_get_used_meta_keys() as $key ) {
			$config[ $key ] = $key === 'tve_landing_page' ? get_post_meta( $post_id, $key, true ) : tve_get_post_meta( $post_id, $key );
		}

		unset( $config['tve_content_before_more'], $config['tve_save_post'] );

		if ( ! empty( $config['thrive_icon_pack'] ) ) {
			$this->export_icon_pack = true;
		}

		$post                 = get_post( $post_id );
		$config['post_title'] = $post->post_title;

		return $config;
	}

	/**
	 * replace all image links from $post_content and both global configuration (e.g. page background, lightbox background)
	 *
	 * @param string $post_content
	 * @param array $config
	 *
	 * @return array (hash => original image name)
	 */
	protected function exportImageMap( & $post_content, & $config ) {
		$image_map = $this->exportParseImages( $post_content );

		/* global setting for page background - pattern */
		if ( ! empty( $config['tve_globals']['lp_bgp'] ) ) {
			$image_map = array_merge( $image_map, $this->exportParseImages( $config['tve_globals']['lp_bgp'] ) );
		}
		/* global setting for page background - image */
		if ( ! empty( $config['tve_globals']['lp_bgi'] ) ) {
			$image_map = array_merge( $image_map, $this->exportParseImages( $config['tve_globals']['lp_bgi'] ) );
		}

		/* lightbox - global setting - content background */
		if ( ! empty( $config['tve_globals']['l_cimg'] ) ) {
			$image_map = array_merge( $image_map, $this->exportParseImages( $config['tve_globals']['l_cimg'] ) );
			unset( $config['tve_globals']['l_cpat'] );
		} elseif ( ! empty( $config['tve_globals']['l_cpat'] ) ) {
			$image_map = array_merge( $image_map, $this->exportParseImages( $config['tve_globals']['l_cpat'] ) );
		}

		return $image_map;
	}

	/**
	 * parse the contents and include in the export file any lightboxes that are used in event managers for individual elements
	 *
	 * @param string $content
	 * @param array $config
	 * @param array $image_map
	 */
	protected function exportParseLightboxes( $content, & $config, & $image_map ) {
		$events = $this->getRelevantEvents( $content );

		foreach ( $events as $event ) {
			$this->exportLightboxFromEvent( $event, $config, $image_map );
		}
	}

	/**
	 * parses $content for any events registered from the TCB event manager that have as actions "thrive_lightbox"
	 * and retrieves an array of all such events
	 *
	 * @param string $content
	 *
	 * @return array array of registered events
	 */
	protected function getRelevantEvents( $content ) {
		/* bail early if no event is defined */
		if ( strpos( $content, '__TCB_EVENT_' ) === false ) {
			return array();
		}

		$event_pattern = "#data-tcb-events=('|\")__TCB_EVENT_(.+?)_TNEVE_BCT__('|\")#";

		if ( ! preg_match_all( $event_pattern, $content, $matches ) ) {
			return array();
		}
		$events = array();

		foreach ( $matches[2] as $i => $str ) {
			$str = htmlspecialchars_decode( $str ); // the actual matched regexp group
			if ( ! ( $elem_events = json_decode( $str, true ) ) ) {
				$elem_events = array();
			}
			if ( empty( $elem_events ) ) {
				continue;
			}
			foreach ( $elem_events as $event ) {
				if ( empty( $event ) || $event['a'] != 'thrive_lightbox' ) {
					continue;
				}
				$events [] = $event;
			}
		}

		return $events;
	}

	/**
	 * add a lightbox to the exported landing page
	 *
	 * @param array $event
	 * @param array $config
	 * @param array $image_map to be completed with any other images from the lightbox
	 */
	protected function exportLightboxFromEvent( $event, & $config, & $image_map ) {
		if ( empty( $event ) || ! is_array( $event ) || empty( $event['a'] ) || $event['a'] != 'thrive_lightbox' ) {
			return;
		}

		if ( empty( $event['config'] ) || empty( $event['config']['l_id'] ) ) {
			return;
		}

		$lb_id = $event['config']['l_id'];

		if ( isset( $this->exported_lightboxes[ $lb_id ] ) || get_post_type( $lb_id ) != 'tcb_lightbox' ) {
			return;
		}

		$this->exported_lightboxes[ $lb_id ] = true;

		if ( empty( $config['lightbox'][ $lb_id ] ) ) {

			$lb_config                    = $this->getTCBMeta( $lb_id );
			$lb_config['tve_lp_lightbox'] = get_post_meta( $lb_id, 'tve_lp_lightbox', true );

			// parse images
			$image_map = array_merge( $image_map, $this->exportImageMap( $lb_config['tve_updated_post'], $lb_config ) );

			/* also handle the "lightbox in lightbox" cases */
			$this->exportParseLightboxes( $lb_config['tve_updated_post'], $config, $image_map );

			$config['lightbox'][ $lb_id ] = $lb_config;
		}
	}

	/**
	 * check if the export archive needs to contain the icon pack also and include the css and fonts for the icon pack
	 *
	 * @param ZipArchive $zip
	 * @param array $config
	 */
	public function exportIconPack( ZipArchive $zip, & $config ) {
		if ( ! $this->export_icon_pack ) {
			return;
		}

		$thrive_icon_pack = get_option( 'thrive_icon_pack' );
		if ( empty( $thrive_icon_pack ) ) {
			return;
		}

		if ( ! is_dir( $thrive_icon_pack['folder'] ) || ! is_file( trailingslashit( $thrive_icon_pack['folder'] ) . 'style.css' ) ) {
			return;
		}

		$zip->addEmptyDir( 'icon-pack' );
		$zip->addFile( trailingslashit( $thrive_icon_pack['folder'] ) . 'style.css', 'icon-pack/style.css' );

		/* add all font files */
		$zip->addEmptyDir( 'icon-pack/fonts' );

		/**
		 * TCB-1683 php < 5.3 does not have support for ZipArchive::addGlob()
		 */
		$icon_files = glob( trailingslashit( $thrive_icon_pack['folder'] ) . 'fonts/*', GLOB_BRACE );
		foreach ( $icon_files as $file ) {
			$zip->addFile( $file, 'icon-pack/fonts/' . basename( $file ) );
		}

		$config['icon_pack'] = array(
			'font-family' => $thrive_icon_pack['fontFamily'],
			'icons'       => $thrive_icon_pack['icons']
		);
	}

	/**
	 *
	 * if this is a previously imported landing page, it can have any number of extra icon packs associated. we need to include those in the export archive
	 *
	 * export any extra icon packs that might be associated with this landing page
	 * there can by any number of icon packs
	 *
	 * @param ZipArchive $zip
	 * @param array $config
	 */
	public function exportExtraIconPacks( ZipArchive $zip, & $config ) {
		if ( empty( $config['tve_globals']['extra_icons'] ) || empty( $config['tve_globals']['used_icon_packs'] ) ) {
			return;
		}

		$used_icon_packs = $config['tve_globals']['used_icon_packs'];

		foreach ( $config['tve_globals']['extra_icons'] as $i => $icon_pack ) {
			if ( empty( $icon_pack ) || ! in_array( $icon_pack['font-family'], $used_icon_packs ) ) {
				unset( $config['tve_globals']['extra_icons'][ $i ] );
				continue;
			}
			if ( ! is_dir( $icon_pack['folder'] ) || ! is_file( trailingslashit( $icon_pack['folder'] ) . 'style.css' ) ) {
				unset( $config['tve_globals']['extra_icons'][ $i ] );
				continue;
			}
			$icon_zip_folder = $icon_pack['font-family'];
			$zip->addEmptyDir( $icon_zip_folder );
			$zip->addFile( trailingslashit( $icon_pack['folder'] ) . 'style.css', $icon_zip_folder . '/style.css' );

			$zip->addEmptyDir( $icon_zip_folder . '/fonts' );

			/**
			 * TCB-1683 php < 5.3 does not have support for ZipArchive::addGlob()
			 */
			$icon_files = glob( trailingslashit( $icon_pack['folder'] ) . 'fonts/*', GLOB_BRACE );
			foreach ( $icon_files as $icon_file ) {
				$zip->addFile( $icon_file, $icon_zip_folder . '/fonts/' . basename( $icon_file ) );
			}

			unset( $config['tve_globals']['extra_icons'][ $i ]['folder'] );
			unset( $config['tve_globals']['extra_icons'][ $i ]['css'] );
		}

		$config['tve_globals']['extra_icons'] = array_values( $config['tve_globals']['extra_icons'] );

	}

	/**
	 * check if the export archive needs to contain the custom font pack, or any user-defined fonts
	 *
	 * @param ZipArchive $zip
	 * @param array $config
	 */
	public function exportFontPack( ZipArchive $zip, & $config ) {
		if ( empty( $config['thrive_tcb_post_fonts'] ) && empty( $config['tve_globals']['extra_fonts'] ) ) {
			return;
		}

		/* grab all the used fonts */
		$used_font_classes = isset( $config['tve_globals']['font_cls'] ) ? $config['tve_globals']['font_cls'] : array();
		$included          = array();

		$all_fonts = $this->getThriveFonts();

		foreach ( $used_font_classes as $font_class ) {
			$font_index = $this->findFontByProps( array( 'font_class' => $font_class ), $all_fonts, array( 'font_class' ) );
			if ( false === $font_index ) {
				continue;
			}
			$font = $all_fonts[ $font_index ];
			if ( Tve_Dash_Font_Import_Manager::isImportedFont( $font['font_name'] ) ) {
				$font['imported'] = true;
				$export_font_pack = true; // export the files included in the font pack
			}
			$included [] = $font;
		}

		$config['thrive_tcb_post_fonts'] = $included;

		if ( isset( $export_font_pack ) && ! isset( $this->exported_font_pack ) ) {
			$this->exported_font_pack = array();

			$font_pack = $this->getImportedFontPack();

			if ( ! empty( $font_pack ) && ! empty( $font_pack['folder'] ) && ! empty( $font_pack['css_file'] ) ) {
				$css_file = basename( $font_pack['css_file'] );

				$this->exportAddFontToZip( $zip, $font_pack['folder'], $font_pack['css_file'] );

				$this->exported_font_pack = array(
					'name' => basename( $font_pack['folder'] ),
					'css'  => $css_file,
				);
			}
		}

		/* finally, check for any extra fonts (that might exist from previous imports of landing pages)*/
		if ( empty( $config['tve_globals']['extra_fonts'] ) ) {
			return;
		}

		foreach ( $config['tve_globals']['extra_fonts'] as $i => $font ) {
			if ( ! empty( $font['ignore'] ) ) { // if not used, do not include it
				unset( $config['tve_globals']['extra_fonts'][ $i ] );
				continue;
			}

			$this->exportAddFontToZip( $zip, $font['font_folder'], basename( $font['font_url'] ) );
			$config['tve_globals']['extra_fonts'][ $i ]['font_folder'] = basename( $font['font_folder'] );

		}

		$config['tve_globals']['extra_fonts'] = array_values( $config['tve_globals']['extra_fonts'] );

	}

	/**
	 * add a font folder to the zip archive
	 *
	 * @param ZipArchive $zip
	 * @param string $folder_path
	 * @param string $css_file
	 * @param string $zip_folder_name if empty, it will take basename($folder_path)
	 */
	protected function exportAddFontToZip( ZipArchive $zip, $folder_path, $css_file, $zip_folder_name = null ) {
		$css_file = basename( $css_file );

		$css_path = trailingslashit( $folder_path ) . $css_file;
		if ( ! is_dir( $folder_path ) || ! is_file( $css_path ) ) {
			return;
		}

		$name = $zip_folder_name !== null ? $zip_folder_name : basename( $folder_path );

		$zip->addEmptyDir( 'font-pack/' . $name );
		$zip->addFile( $css_path, 'font-pack/' . $name . '/' . $css_file );

		/* add all font files */

		/**
		 * TCB-1683 php < 5.3 does not have support for ZipArchive::addGlob()
		 */
		$font_files = glob( trailingslashit( $folder_path ) . '*.{woff,WOFF,eot,EOT,woff2,WOFF2,ttf,TTF,svg,SVG}', GLOB_BRACE );
		foreach ( $font_files as $font_file ) {
			$zip->addFile( $font_file, 'font-pack/' . $name . '/' . basename( $font_file ) );
		}

	}


	/**
	 * get the path to a writable folder in this WP install
	 *
	 * @throws Exception
	 *
	 * @return string the path to a writable (temp) folder
	 */
	protected function getTempFolder() {
		$folder = get_temp_dir();

		if ( ! wp_is_writable( $folder ) ) {
			$folder = wp_upload_dir();
		}

		if ( ! wp_is_writable( $folder ) ) {
			throw new Exception( 'Could not find a writable folder to create the archive in. Does your wp-content/uploads folder exist?', 'thrive-cb' );
		}

		return $folder;
	}

	/** --------------------------------------- IMPORT methods --------------------------------------- */

	/**
	 * import a landing page from a zip file located in the attachment received as parameter
	 *
	 * @param mixed $attachment_id
	 * @param int $page_id - the existing page which will be transformed into a landing page
	 *
	 * @return int $page_id the ID of the landing page
	 *
	 * @throws Exception
	 */
	public function import( $attachment_id, $page_id ) {
		$file = get_attached_file( $attachment_id, true );

		$template_name = $this->importValidateFile( $file );

		$zip = new ZipArchive();
		if ( $zip->open( $file ) !== true ) {
			throw new Exception( __( 'Could not open the archive file', 'thrive-cb' ) );
		}

		$this->import_config = $config = $this->importReadConfig( $zip );
		$this->current_page  = get_post( $page_id );

		/**
		 * if this is a cloud template, we need to make sure the user has the cloud template files downloaded
		 */
		if ( ! empty( $config['cloud_template'] ) ) {
			$existing = tve_get_downloaded_templates();
			if ( ! isset( $existing[ $config['tve_landing_page'] ] ) ) {
				TCB_Landing_Page_Cloud_Templates_Api::getInstance()->download( $config['tve_landing_page'] ); // this will throw exceptions for any misconfiguration / problem
			}
		}

		/* Import any extra icon_packs that might be associated with the landing page */
		$this->importExtraIconPacks( $config, $zip );
		/* Import any fonts that might be included in the export file, for the landing page */
		/* the $font_class_map will hold a mapping between old classes (.ttfmx) and newly created classes */
		$font_class_map = $this->importFonts( $config, $zip );

		/* if any landing page fonts have been setup, we need to replace the font_classes for the selectors, with the values from font_class_map */
		if ( ! empty( $config['tve_globals']['landing_fonts'] ) ) {
			foreach ( $config['tve_globals']['landing_fonts'] as $key => $data ) {
				if ( ! isset( $font_class_map[ $data['css_class'] ] ) ) {
					continue;
				}
				$config['tve_globals']['landing_fonts'][ $key ]['css_class'] = $font_class_map[ $data['css_class'] ];
			}
		}

		/* also for any of the lightboxes */
		if ( ! empty( $config['lightbox'] ) ) {
			foreach ( $config['lightbox'] as $id => $item ) {
				$this->importExtraIconPacks( $config['lightbox'][ $id ], $zip );
				$font_class_map = array_merge( $font_class_map, $this->importFonts( $config['lightbox'][ $id ], $zip ) );
			}
		}

		/* 1b. include the icon pack as an extra resource to be saved in the landing page */
		if ( ! empty( $config['icon_pack'] ) ) {
			$new_icon_pack = $this->importIconPack( $config, $zip );
			if ( ! empty( $new_icon_pack ) && ! empty( $config['icon_pack']['icons'] ) && count( $config['icon_pack']['icons'] ) == count( $new_icon_pack['icons'] ) ) {
				if ( $new_icon_pack['icons'][0] != $config['icon_pack']['icons'][0] ) {
					$icon_search  = $config['icon_pack']['icons'];
					$icon_replace = $new_icon_pack['icons'];
				}
			}

			/* the default icons should not be included anymore in the LP or any of the lightboxes */
			if ( ! empty( $config['thrive_icon_pack'] ) ) {
				$config['thrive_icon_pack']                  = 0;
				$config['tve_globals']['used_icon_packs']    = empty( $config['tve_globals']['used_icon_packs'] ) ? array() : $config['tve_globals']['used_icon_packs'];
				$config['tve_globals']['extra_icons']        = empty( $config['tve_globals']['extra_icons'] ) ? array() : $config['tve_globals']['extra_icons'];
				$config['tve_globals']['extra_icons'] []     = $new_icon_pack;
				$config['tve_globals']['used_icon_packs'] [] = $new_icon_pack['font-family'];
			}
			foreach ( $config['lightbox'] as $id => $item ) {
				if ( ! empty( $item['thrive_icon_pack'] ) ) {
					$config['lightbox'][ $id ]['thrive_icon_pack']                  = 0;
					$config['lightbox'][ $id ]['tve_globals']['used_icon_packs']    = empty( $config['lightbox'][ $id ]['tve_globals']['used_icon_packs'] ) ? array() : $config['lightbox'][ $id ]['tve_globals']['used_icon_packs'];
					$config['lightbox'][ $id ]['tve_globals']['extra_icons']        = empty( $config['lightbox'][ $id ]['tve_globals']['extra_icons'] ) ? array() : $config['lightbox'][ $id ]['tve_globals']['extra_icons'];
					$config['lightbox'][ $id ]['tve_globals']['extra_icons'] []     = $new_icon_pack;
					$config['lightbox'][ $id ]['tve_globals']['used_icon_packs'] [] = $new_icon_pack['font-family'];
				}
			}
		}

		/* 2. import all the images (add them as attachments) and store the links in the config array */
		$image_map = $this->importImages( $config, $zip );

		/* 3. import all lightboxes (create new posts with type tcb_lightbox) */
		$lightbox_id_map = $this->importLightboxes( $config, $image_map, $zip );

		/* 3. page-level events - check if we have a lightbox there and replace its ID */
		if ( ! empty( $config['tve_page_events'] ) ) {
			foreach ( $config['tve_page_events'] as $index => $evt ) {
				if ( $evt['a'] !== 'thrive_lightbox' || empty( $evt['config']['l_id'] ) || ! isset( $lightbox_id_map[ $evt['config']['l_id'] ] ) ) {
					continue;
				}
				$config['tve_page_events'][ $index ]['config']['l_id'] = $lightbox_id_map[ $evt['config']['l_id'] ];
			}
		}

		/* 4. set all post-meta values to the lightbox */
		$this->importParseImageLinks( $config, $image_map );
		update_post_meta( $page_id, 'tve_landing_page', $config['tve_landing_page'] );
		foreach ( tve_get_used_meta_keys() as $meta_key ) {
			if ( ! isset( $config[ $meta_key ] ) || $meta_key == 'tve_landing_page' ) {
				continue;
			}
			tve_update_post_meta( $page_id, $meta_key, $config[ $meta_key ] );
		}

		/* 5. update the meta-field for TCB content - we need to parse the content and replace lightbox ids inside the event manager parameters */
		$page_content = $zip->getFromName( 'lp.html' );
		$this->importParseImageLinks( $page_content, $image_map );
		$this->importReplaceLightboxIds( $page_content, $lightbox_id_map );
		if ( isset( $icon_search ) && isset( $icon_replace ) ) {
			$page_content = str_replace( $icon_search, $icon_replace, $page_content );
		}
		$page_content = $this->importReplaceFontClasses( $font_class_map, $page_content );

		tve_update_post_meta( $page_id, 'tve_save_post', $page_content );
		tve_update_post_meta( $page_id, 'tve_updated_post', $page_content );

		/* 6. search inside the lightboxes for event manager actions that trigger other lightboxes and replace those also */
		if ( ! empty( $config['lightbox'] ) ) {
			foreach ( $config['lightbox'] as $old_id => $data ) {
				$lb_content = $zip->getFromName( 'lightboxes/' . $old_id . '.html' );
				$this->importReplaceLightboxIds( $lb_content, $lightbox_id_map );
				$this->importParseImageLinks( $lb_content, $image_map );
				if ( isset( $icon_search ) && isset( $icon_replace ) ) {
					$lb_content = str_replace( $icon_search, $icon_replace, $lb_content );
				}
				$lb_content = $this->importReplaceFontClasses( $font_class_map, $lb_content );
				update_post_meta( $lightbox_id_map[ $old_id ], 'tve_save_post', $lb_content );
				update_post_meta( $lightbox_id_map[ $old_id ], 'tve_updated_post', $lb_content );
			}
		}
		update_post_meta( $this->current_page->ID, 'test_radu', $config );
		$config = get_post_meta( $this->current_page->ID, 'test_radu', true );

		/* finally, save this import also as a landing page template */
		$template_name     = str_replace( 'tve-lp-', '', $template_name );
		$templates_content = get_option( 'tve_saved_landing_pages_content' ); // this should get unserialized automatically
		$templates_meta    = get_option( 'tve_saved_landing_pages_meta' ); // this should get unserialized automatically
		if ( ! empty( $templates_meta ) ) {
			foreach ( $templates_meta as $tpl ) {
				/* if this has been saved before return - we check the archive filesize - this should be identical for identical imports */
				if ( ! empty( $tpl['imported'] ) && ! empty( $tpl['zip_filesize'] ) && $tpl['zip_filesize'] == filesize( $file ) ) {
					return $page_id;
				}
			}
		}
		$template_content = array(
			'content'            => $page_content,
			'inline_css'         => $config['tve_custom_css'],
			'custom_css'         => $config['tve_user_custom_css'],
			'tve_globals'        => $config['tve_globals'],
			'tve_global_scripts' => $config['tve_global_scripts']
		);
		$template_meta    = array(
			'name'         => $template_name,
			'template'     => $config['tve_landing_page'],
			'tags'         => 'imported-template',
			'date'         => date( 'Y-m-d' ),
			'imported'     => 1,
			'zip_filesize' => filesize( $file ),
			'thumbnail'    => $this->importThumbnail( $config, $zip ),
		);

		if ( empty( $templates_content ) ) {
			$templates_content = array();
			$templates_meta    = array();
		}
		$templates_content [] = $template_content;
		$templates_meta []    = $template_meta;

		// make sure these are not autoloaded, as it is a potentially huge array
		add_option( 'tve_saved_landing_pages_content', null, '', 'no' );

		update_option( 'tve_saved_landing_pages_content', $templates_content );
		update_option( 'tve_saved_landing_pages_meta', $templates_meta );

		return $page_id;
	}

	/**
	 * validate if the file exists and it is a zip archive
	 *
	 * @param string $file
	 *
	 * @return string the file name without extension
	 *
	 * @throws Exception
	 */
	protected function importValidateFile( $file ) {
		if ( ! is_file( $file ) ) {
			throw new Exception( __( 'Could not find the archive file on the server. Please make sure the file exists.', 'thrive-cb' ) );
		}

		$name = basename( $file );
		$info = pathinfo( $name );

		if ( $info['extension'] !== 'zip' && $info['extension'] !== 'ZIP' ) {
			throw new Exception( __( 'Invalid file specified', 'thrive-cb' ) );
		}

		if ( ! class_exists( 'ZipArchive' ) ) {
			throw new Exception( __( 'The PHP ZipArchive extension must be enabled in order to use this functionality. Please contact your hosting provider.', 'thrive-cb' ) );
		}

		return str_ireplace( '.zip', '', $name );
	}

	/**
	 * check if all the required files are inside the zip archive
	 *
	 * @param ZipArchive $zip
	 *
	 * @return array the decoded configuration array
	 *
	 * @throws Exception
	 */
	protected function importReadConfig( ZipArchive $zip ) {
		/* 1. check if lp.json exists in the archive */
		if ( false === $zip->locateName( 'lp.json' ) ) {
			throw new Exception( __( 'This archive does not seem to be generated from Thrive Visual Editor - unable to find the config file', 'thrive-cb' ) );
		}

		/* 2. check if lp.html exists - the Landing Page contents */
		if ( false === $zip->locateName( 'lp.json' ) ) {
			throw new Exception( __( 'This archive does not seem to be generated from Thrive Visual Editor - unable to find the content file', 'thrive-cb' ) );
		}

		/* 3. extract the config array from the json config file */
		$contents = trim( $zip->getFromName( 'lp.json' ) );
		$config   = json_decode( $zip->getFromName( 'lp.json' ), true );

		/**
		 * Also support the base64-encoded config files
		 */
		if ( empty( $config ) ) {
			$config = @unserialize( @base64_decode( $contents ) );
		}

		if ( empty( $config ) ) {
			throw new Exception( __( 'This archive does not seem to be generated from Thrive Visual Editor - could not decode the configuration', 'thrive-cb' ) );
		}

		if ( empty( $config['tve_landing_page'] ) ) {
			throw new Exception( __( 'This archive does not seem to be generated from Thrive Visual Editor - configuration file is invalid', 'thrive-cb' ) );
		}

		/* 4. check for all lightboxes to exist as files */
		if ( ! empty( $config['lightbox'] ) ) {
			foreach ( $config['lightbox'] as $id => $data ) {
				if ( false == $zip->locateName( 'lightboxes/' . $id . '.html' ) ) {
					throw new Exception( __( 'Could not locate the lightbox #' . $id, 'thrive-cb' ) );
				}
				if ( ! array_key_exists( 'post_title', $data ) ) {
					throw new Exception( __( 'Values are missing from the configuration (post_title)', 'thrive-cb' ) );
				}
			}
		}

		/* 5. check that all images included in the config are also included as files in the archive */
		if ( ! empty( $config['image_map'] ) ) {
			foreach ( $config['image_map'] as $hash => $original_name ) {
				if ( false === $zip->locateName( 'images/' . $hash . '.img_file' ) ) {
					throw new Exception( __( 'Image is missing from the archive: ' . $original_name, 'thrive-cb' ) );
				}
			}
		}

		return $config;
	}

	/**
	 * save all images as attachments
	 *
	 * @param array $config
	 * @param ZipArchive $zip
	 *
	 * @return array image map with hash => new (imported) URL
	 *
	 * @throws Exception
	 */
	protected function importImages( $config, ZipArchive $zip ) {
		if ( empty( $config['image_map'] ) ) {
			return array();
		}

		$image_map = array();
		foreach ( $config['image_map'] as $hash => $image_name ) {
			$image_map[ $hash ] = $this->insertAttachment( $image_name, $zip->getFromName( 'images/' . $hash . '.img_file' ) );
		}

		return $image_map;
	}

	/**
	 * uploads an image to the wp media folder and insert it as an attachment
	 *
	 * @param string $image_name
	 * @param string $image_content full content of the image file
	 *
	 * @return string the url to the uploaded image
	 *
	 * @throws Exception
	 */
	protected function insertAttachment( $image_name, $image_content ) {
		$upload = wp_upload_bits( $image_name, null, $image_content );
		if ( ! empty( $upload['error'] ) ) {
			throw new Exception( sprintf( __( 'Could not upload the image: %s', 'thrive-cb' ), $image_name ) );
		}
		/* add the image to the media library, as an attachment */
		$wp_filetype   = wp_check_filetype( $image_name, null );
		$attachment    = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_parent'    => 0,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $image_name ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attachment_id = wp_insert_attachment( $attachment, $upload['file'] );
		if ( ! is_wp_error( $attachment_id ) ) {
			/* if we leave these filters in, the generate_attachment_metadata will run forever */
			remove_all_filters( 'wp_generate_attachment_metadata' );
			remove_all_filters( 'intermediate_image_sizes' );

			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
		}

		return $upload['url'];
	}

	/**
	 * return $string with all occurences of {{img=hash}} replace with the new image links
	 *
	 * @param string $string
	 * @param array $image_map
	 *
	 * @return string
	 */
	protected function importReplaceImageLinks( $string, $image_map ) {
		$search  = array();
		$replace = array();
		foreach ( $image_map as $hash => $url ) {
			$search []  = '{{img=' . $hash . '}}';
			$replace [] = $url;
		}

		return str_replace( $search, $replace, $string );
	}

	/**
	 * replace all occurences of {img=hash} with the generated URLs for the new images
	 *
	 * @param string|array $content the post content (or the configuration the lightbox / landing page - to replace background images and stuff)
	 * @param array $image_map
	 */
	protected function importParseImageLinks( & $content, $image_map ) {
		if ( is_array( $content ) ) {

			/* global setting for page background - image */
			if ( ! empty( $content['tve_globals']['lp_bgi'] ) ) {
				$content['tve_globals']['lp_bgi'] = $this->importReplaceImageLinks( $content['tve_globals']['lp_bgi'], $image_map );
			}
			if ( ! empty( $content['tve_globals']['lp_bgp'] ) ) {
				$content['tve_globals']['lp_bgp'] = $this->importReplaceImageLinks( $content['tve_globals']['lp_bgp'], $image_map );
			}

			/* lightbox - global setting - content background */
			if ( ! empty( $content['tve_globals']['l_cimg'] ) ) {
				$content['tve_globals']['l_cimg'] = $this->importReplaceImageLinks( $content['tve_globals']['l_cimg'], $image_map );
			} elseif ( ! empty( $content['tve_globals']['l_cpat'] ) ) {
				$content['tve_globals']['l_cpat'] = $this->importReplaceImageLinks( $content['tve_globals']['l_cpat'], $image_map );
			}

			return;
		}

		$content = $this->importReplaceImageLinks( $content, $image_map );
	}

	/**
	 * import (create) the lightboxes included in the export file
	 * this does not import the lb contents yet
	 *
	 * @param array $config
	 * @param array $image_map
	 *
	 * @return array map of old_id => new_id for each lightbox
	 */
	protected function importLightboxes( & $config, $image_map ) {
		if ( empty( $config['lightbox'] ) ) {
			return array();
		}

		$lightbox_id_map = array();

		foreach ( $config['lightbox'] as $id => $lb_data ) {
			$this->importParseImageLinks( $lb_data, $image_map );
			$globals = $lb_data['tve_globals'];
			unset( $lb_data['tve_globals'] );
			$lightbox_id_map[ $id ] = tve_create_lightbox( str_replace( '(Imported)', '', $lb_data['post_title'] ) . '(Imported)', '', $globals, $lb_data );
		}

		return $lightbox_id_map;
	}

	/**
	 * parse the content for event manager configurations and replace old lightbox ids with new ones
	 *
	 * @param string $content
	 * @param array $lightbox_id_map
	 */
	protected function importReplaceLightboxIds( & $content, $lightbox_id_map ) {
		if ( empty( $lightbox_id_map ) ) {
			return;
		}

		$search = $replace = array();

		foreach ( $lightbox_id_map as $old_id => $new_id ) {
			$search []  = '&quot;l_id&quot;:&quot;' . $old_id . '&quot;';
			$replace [] = '&quot;l_id&quot;:&quot;' . $new_id . '&quot;';
		}

		$content = str_replace( $search, $replace, $content );

	}

	/**
	 * import the main icon pack (the main Thrive Icon Pack setup on the site where the user made the export)
	 *
	 * @param array $config
	 * @param ZipArchive $zip
	 *
	 * @return array with new icon classes.
	 *
	 * @throws Exception
	 */
	protected function importIconPack( $config, ZipArchive $zip ) {
		if ( empty( $config['icon_pack']['icons'] ) ) {
			return array();
		}

		if ( false === $zip->locateName( 'icon-pack/style.css' ) || false === $zip->locateName( 'icon-pack/fonts/' ) ) {
			return array();
		}

		$font_css = $zip->getFromName( 'icon-pack/style.css' );

		$old_font_family = $config['icon_pack']['font-family'];
		$new_font_family = uniqid( 'tve-ff-' . $this->current_page->ID . '-' );

		/* create a new folder in the uploads_dir for the font-pack */
		$upload_dir    = $this->getUploadDir();
		$font_dir_path = trailingslashit( $upload_dir['basedir'] ) . $new_font_family . '/fonts';

		if ( ! wp_mkdir_p( $font_dir_path ) ) {
			throw new Exception( __( 'Could not create the folder for the Icon Pack', 'thrive-cb' ) );
		}

		$icon_pack = array(
			'font-family' => $new_font_family,
		);

		$new_cls_prefix = uniqid( 'tve-icm-' ) . '-';

		/* replace the font-family declaration AND all the class names with a prefix */
		$font_css = preg_replace( '#font-family:(.*?)' . preg_quote( $old_font_family, '#' ) . '(.*)$#m', 'font-family:$1' . $new_font_family . '$2', $font_css );

		/* replace all the CSS classes with the new cls prefix */
		/*
            do a preg_match do find the icon class. usually this is how the icomoon css file declares its classes:
            [class^="icon2-"], [class*=" icon2-"] {...}
            if we cannot find this pattern, there is no point in going forward, we cannot determine the global class selector
        */
		if ( preg_match_all( '#\[class(.*?)("|\')(.+?)("|\')#m', $font_css, $matches ) ) {
			$old_cls_prefix = $matches[3][0];
			$font_css       = preg_replace( '#\[class(.*?)("|\')(.+?)("|\')#m', '[class$1$2' . $new_cls_prefix . '$4', $font_css );
		}

		$new_icons = $config['icon_pack']['icons'];
		if ( isset( $old_cls_prefix ) ) {
			$font_css = str_replace( '.' . $old_cls_prefix, '.' . $new_cls_prefix, $font_css );
			foreach ( $new_icons as $i => $icon ) {
				$new_icons[ $i ] = str_replace( $old_cls_prefix, $new_cls_prefix, $icon );
			}
		}
		$icon_pack['icons'] = $new_icons;
		/* upload the font css file */
		tve_wp_upload_bits( trailingslashit( dirname( $font_dir_path ) ) . 'style.css', $font_css );

		/* save the URL to the uploaded css file */
		$icon_pack['css'] = trailingslashit( $upload_dir['baseurl'] ) . $new_font_family . '/style.css';

		/* save the folder path to the saved icon pack */
		$icon_pack['folder'] = str_replace( '\\', '\\\\', trailingslashit( $upload_dir['basedir'] ) . $new_font_family );

		/* go through the zip contents and grab all the font files - upload them to the fonts folder ($font_dir_path)  */
		$i = 0;
		while ( $info = $zip->statIndex( $i ) ) {
			if ( strpos( $info['name'], 'icon-pack/fonts/' ) === 0 && ! empty( $info['size'] ) ) {
				$pathinfo = pathinfo( $info['name'] );
				if ( empty( $pathinfo['extension'] ) || ! in_array( strtolower( $pathinfo['extension'] ), array(
						'woff',
						'woff2',
						'ttf',
						'svg',
						'eot'
					) )
				) {
					$i ++;
					continue;
				}
				tve_wp_upload_bits( trailingslashit( $font_dir_path ) . basename( $info['name'] ), $zip->getFromIndex( $i ) );
			}
			$i ++;
		}

		return $icon_pack;

	}

	/**
	 * import any extra icon_packs that might be existing in the $config array from a previous import
	 *
	 * @param array $config
	 * @param ZipArchive $zip
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	protected function importExtraIconPacks( & $config, ZipArchive $zip ) {
		if ( empty( $config['tve_globals']['extra_icons'] ) ) {
			return;
		}

		foreach ( $config['tve_globals']['extra_icons'] as $i => $icon_pack ) {
			$family = $icon_pack['font-family'];
			if ( false === $zip->locateName( $family . '/style.css' ) || false === $zip->locateName( $family . '/fonts/' ) ) {
				unset( $config['tve_globals']['extra_icons'][ $i ] );
				continue;
			}

			/* create a new folder in the uploads_dir for the font-pack */
			$upload_dir    = $this->getUploadDir();
			$font_dir_path = trailingslashit( $upload_dir['basedir'] ) . $family . '/fonts';

			if ( ! wp_mkdir_p( $font_dir_path ) ) {
				throw new Exception( __( 'Could not create the folder for the Icon Pack', 'thrive-cb' ) );
			}

			/* upload the font css file */
			tve_wp_upload_bits( trailingslashit( dirname( $font_dir_path ) ) . 'style.css', $zip->getFromName( $family . '/style.css' ) );

			/* save the URL to the uploaded css file */
			$icon_pack['css'] = trailingslashit( $upload_dir['baseurl'] ) . $family . '/style.css';

			/* save the folder path to the saved icon pack */
			$icon_pack['folder'] = str_replace( '\\', '\\\\', trailingslashit( $upload_dir['basedir'] ) . $family );

			/* go through the zip contents and grab all the font files - upload them to the fonts folder ($font_dir_path)  */
			$zip_index = 0;
			while ( $info = $zip->statIndex( $zip_index ) ) {
				if ( strpos( $info['name'], $family . '/fonts/' ) === 0 && ! empty( $info['size'] ) ) {
					$pathinfo = pathinfo( $info['name'] );
					if ( empty( $pathinfo['extension'] ) || ! in_array( strtolower( $pathinfo['extension'] ), array(
							'woff',
							'ttf',
							'svg',
							'eot'
						) )
					) {
						$zip_index ++;
						continue;
					}
					tve_wp_upload_bits( trailingslashit( $font_dir_path ) . basename( $info['name'] ), $zip->getFromIndex( $zip_index ) );
				}
				$zip_index ++;
			}
			$config['tve_globals']['extra_icons'][ $i ] = $icon_pack;

		}

		$config['tve_globals']['extra_icons'] = array_values( $config['tve_globals']['extra_icons'] );

	}

	/**
	 * create new fonts from the exported ones, return a class map in the form of .ttfmx => .ttfmy - to be used to replace occurences of ttfmx with ttfmy in the page / lightbox contents
	 *
	 * @param array $config
	 * @param ZipArchive $zip
	 *
	 * @return array
	 */
	public function importFonts( & $config, ZipArchive $zip ) {
		if ( empty( $config['thrive_tcb_post_fonts'] ) && empty( $config['tve_globals']['extra_fonts'] ) ) {
			return array();
		}

		$class_map = array();

		$config['tve_globals']['font_cls'] = array();

		$config['tve_globals']['extra_fonts'] = isset( $config['tve_globals']['extra_fonts'] ) ? $config['tve_globals']['extra_fonts'] : array();

		$this->import_config['imported_font_index'] = isset( $this->import_config['imported_font_index'] ) ? $this->import_config['imported_font_index'] : 0;
		$this->import_config['imported_font_index'] ++;

		$this->import_config['imported_fonts'] = isset( $this->import_config['imported_fonts'] ) ? $this->import_config['imported_fonts'] : array();

		/* check for any extra_fonts included in the import file */
		if ( ! empty( $config['tve_globals']['extra_fonts'] ) ) {
			foreach ( $config['tve_globals']['extra_fonts'] as $i => $font ) {
				$location = $this->importFontPack( $zip, $font['font_folder'], basename( $font['font_url'] ), $font['font_folder'] ); // use the same folder name
				if ( empty( $location ) ) {
					unset( $config['tve_globals']['extra_fonts'][ $i ] );
					continue;
				}

				$already_imported_index = $this->findFontByProps( $font, $this->import_config['imported_fonts'] );

				if ( false === $already_imported_index ) {
					$font['font_url']    = $location['url'];
					$font['font_folder'] = str_replace( '\\', '\\\\', $location['path'] );

					$font['font_id']                  = $this->import_config['imported_font_index'] ++;
					$new_css_class                    = 'ttfm-i-' . $font['font_id'];
					$class_map[ $font['font_class'] ] = $new_css_class;
					$font['font_class']               = $new_css_class;

					$this->import_config['imported_fonts'] [] = $font;
				} else {
					$font = $this->import_config['imported_fonts'][ $already_imported_index ];
				}

				$config['tve_globals']['extra_fonts'][ $i ] = $font;

			}
		}

		$config['tve_globals']['extra_fonts'] = array_values( $config['tve_globals']['extra_fonts'] );

		$all_fonts = $this->getThriveFonts();

		foreach ( $config['thrive_tcb_post_fonts'] as $index => $font_data ) {
			if ( empty( $font_data['imported'] ) ) {
				$font_index = $this->findFontByProps( $font_data, $all_fonts );
				if ( $font_index === false ) {
					$new_font = $this->addThriveFont( $font_data, $all_fonts );
				} else {
					$new_font = $all_fonts[ $font_index ];
				}

				if ( $font_data['font_class'] != $new_font['font_class'] ) {
					$class_map[ $font_data['font_class'] ] = $new_font['font_class'];
				}
				if ( tve_is_safe_font( $new_font ) ) {
					/* it does not require an URL */
					unset( $config['thrive_tcb_post_fonts'][ $index ] );
				} else {
					$config['thrive_tcb_post_fonts'][ $index ] = tve_custom_font_get_link( $new_font );
				}

				/* add the font class to the global font_cls field */
				$config['tve_globals']['font_cls'][] = $new_font['font_class'];
				continue;
			}

			if ( empty( $this->import_config['font_pack'] ) ) {
				unset( $config['thrive_tcb_post_fonts'][ $index ] );
				continue;
			}

			/* at this point, the included font was imported using the thrive font import manager */
			$location = $this->importFontPack( $zip, $this->import_config['font_pack']['name'], $this->import_config['font_pack']['css'] );
			if ( empty( $location ) ) { // nothing we can do here, the archive seems corrupted
				unset( $config['thrive_tcb_post_fonts'][ $index ] );
				continue;
			}

			$already_imported_index = $this->findFontByProps( $font_data, $this->import_config['imported_fonts'] );

			/* search and see if this has already been imported in this run */
			if ( false === $already_imported_index ) {
				$font_data['font_url']    = $location['url'];
				$font_data['font_folder'] = str_replace( '\\', '\\\\', $location['path'] );

				$font_data['font_id']                  = $this->import_config['imported_font_index'] ++;
				$new_css_class                         = 'ttfm-i-' . $font_data['font_id'];
				$class_map[ $font_data['font_class'] ] = $new_css_class;
				$font_data['font_class']               = $new_css_class;
				unset( $font_data['imported'] );
				$this->import_config['imported_fonts'][] = $font_data;
			} else {
				$font_data = $this->import_config['imported_fonts'][ $already_imported_index ];
			}

			$config['tve_globals']['extra_fonts'][] = $font_data;

			/* this can be removed from here at the moment */
			unset( $config['thrive_tcb_post_fonts'][ $index ] );
		}

		/* reorder the array, to skip non-consecutive indexes */
		$config['thrive_tcb_post_fonts'] = array_values( $config['thrive_tcb_post_fonts'] );

		return $class_map;
	}

	/**
	 * import the actual files for the font pack included in the archive
	 *
	 * @param ZipArchive $zip
	 * @param string $folder_name folder name where the font files are stored in the zip archive
	 * @param string $css_file name of the css file from the font folder
	 * @param string $new_folder_name if empty, a random folder name will be generated
	 *
	 * @return array|false
	 *  url -> the full url to the css font definition file
	 *  path -> the full path to the folder containing the font files
	 *
	 * @throws Exception
	 */
	public function importFontPack( $zip, $folder_name, $css_file, $new_folder_name = null ) {
		if ( ! empty( $this->import_config['font_pack_imported'][ $folder_name ] ) ) {
			return $this->import_config['font_pack_imported'][ $folder_name ];
		}

		$zip_path = 'font-pack/' . $folder_name;
		if ( false === $zip->locateName( $zip_path . '/' . $css_file ) ) {
			throw new Exception( sprintf( __( 'Could not find the main imported font in the archive file (%s)', 'thrive-cb' ), $zip_path ) );
		}

		$folder = null === $new_folder_name ? ( substr( $folder_name, 0, 10 ) . uniqid( '-imp-' ) ) : $new_folder_name;

		/* create a new folder in the uploads_dir for the font-pack */
		$upload_dir    = $this->getUploadDir();
		$font_dir_path = trailingslashit( $upload_dir['basedir'] ) . $folder;

		if ( ! wp_mkdir_p( $font_dir_path ) ) {
			throw new Exception( __( 'Could not create the folder for the Custom Font Pack', 'thrive-cb' ) );
		}

		/* upload the font css file */
		tve_wp_upload_bits( trailingslashit( $font_dir_path ) . $css_file, $zip->getFromName( $zip_path . '/' . $css_file ) );

		/* save the URL to the uploaded css file */
		$this->import_config['font_pack_imported'][ $folder_name ] = array(
			'url'  => trailingslashit( $upload_dir['baseurl'] ) . $folder . '/' . $css_file,
			'path' => $font_dir_path,
		);

		/* go through the zip contents and grab all the font files - upload them to the fonts folder ($font_dir_path)  */
		$zip_index = 0;
		while ( $info = $zip->statIndex( $zip_index ) ) {
			if ( strpos( $info['name'], $folder_name ) === false ) {
				$zip_index ++;
				continue;
			}
			if ( ! preg_match( '#\.(woff|eot|woff2|ttf|svg)$#i', $info['name'] ) ) {
				$zip_index ++;
				continue;
			}
			tve_wp_upload_bits( trailingslashit( $font_dir_path ) . basename( $info['name'] ), $zip->getFromIndex( $zip_index ) );
			$zip_index ++;
		}

		return $this->import_config['font_pack_imported'][ $folder_name ];

	}

	/**
	 * replace all occurences of the array keys of the $class_map with the array values
	 * the problem here is that we cannot go over the string more than once - it's possible that some of the replacements are identical with some of the searches
	 *
	 * @param array $class_map
	 * @param string $content
	 *
	 * @return string
	 */
	protected function importReplaceFontClasses( $class_map, $content ) {
		if ( empty( $class_map ) ) {
			return $content;
		}

		$search = array_keys( $class_map );

		/* hold this to be used in the callback function for replacing */
		$this->import_temp_replace_map = $class_map;

		$pattern = '#(' . implode( '|', $search ) . ')#s';

		return preg_replace_callback( $pattern, array( $this, '_importReplaceFontClassCallback' ), $content );
	}

	private function _importReplaceFontClassCallback( $matches ) {
		return $this->import_temp_replace_map[ $matches[0] ];
	}

	/**
	 * check if there is a thumbnail included in the archive and import it
	 *
	 * @param array $config configuration array
	 * @param ZipArchive $zip zip archive containing the exported landing page
	 *
	 * @return string the url to the uploaded thumbnail file
	 */
	protected function importThumbnail( $config, ZipArchive $zip ) {
		if ( empty( $config['thumbnail'] ) ) {
			return '';
		}

		if ( false === $zip->locateName( 'thumbnail/' . $config['thumbnail'] ) ) {
			return '';
		}

		try {
			return $this->insertAttachment( $config['thumbnail'], $zip->getFromName( 'thumbnail/' . $config['thumbnail'] ) );
		} catch ( Exception $e ) {
			return '';
		}

	}

	/**
	 * wp_upload_bits does not allow creating subfolders in the upload_dir structure
	 * we need to upload some things into a grouped manner - e.g. font files for custom icons
	 *
	 * @param string $full_path full file path
	 * @param string $contents
	 *
	 * @throws Exception
	 *
	 * @return true on success
	 */
	protected function wpUploadBits( $full_path, $contents ) {

		$ifp = @ fopen( $full_path, 'wb' );
		if ( ! $ifp ) {
			throw new Exception( sprintf( __( 'Could not write file %s', 'thrive-cb' ), basename( $full_path ) ) );
		}

		@fwrite( $ifp, $contents );
		fclose( $ifp );
		clearstatcache();

		// Set correct file permissions
		$stat  = @ stat( dirname( $full_path ) );
		$perms = $stat['mode'] & 0007777;
		$perms = $perms & 0000666;
		@ chmod( $full_path, $perms );
		clearstatcache();

		return true;
	}

	/**
	 * get all fonts that are defined from the font manager
	 *
	 * @return array
	 */
	protected function getThriveFonts() {
		$fonts = json_decode( get_option( 'thrive_font_manager_options', '[]' ), true );

		return empty( $fonts ) ? array() : $fonts;

	}

	/**
	 * update (save) the fonts (some new fonts might have been added during the import process)
	 *
	 * @param array|string $fonts
	 */
	protected function updateThriveFonts( $fonts ) {
		if ( is_array( $fonts ) ) {
			$fonts = json_encode( $fonts );
		}

		update_option( 'thrive_font_manager_options', $fonts );
	}

	/**
	 * check if we can find the font defined by $font properties in the $fonts array
	 *
	 * @param array $font font properties
	 * @param array $fonts array to search in
	 * @param array $field_search an array of fields which all need to match
	 *
	 * @return int|false the index of the match, or false otherwise
	 */
	protected function findFontByProps(
		$font, $fonts, $field_search = array(
		'font_name',
		'font_style',
		'font_bold',
		'font_color'
	)
	) {
		foreach ( $fonts as $i => $f ) {
			$found = true;
			foreach ( $field_search as $field ) {
				if ( $font[ $field ] != $f[ $field ] ) {
					$found = false;
					break;
				}
			}
			if ( $found ) {
				return $i;
			}
		}

		return false;
	}

	/**
	 * add new font (same functionality as the thrive font manager)
	 *
	 * @param array $font
	 * @param array $fonts
	 *
	 * @return array the added font (having a new font_id and a new font_class fields)
	 */
	protected function addThriveFont( $font, & $fonts ) {
		$last               = end( $fonts );
		$font['font_id']    = empty( $last ) ? 1 : ( $last['font_id'] + 1 );
		$font['font_class'] = 'ttfm' . $font['font_id'];

		$fonts [] = $font;

		$this->updateThriveFonts( $fonts );

		return $font;

	}

	/**
	 * get the imported font pack metadata, if any
	 *
	 * @return array
	 */
	public function getImportedFontPack() {
		return get_option( Tve_Dash_Font_Import_Manager::OPTION_NAME, array() );
	}

	/** import template from a zip archive that's downloaded from the cloud */

	/**
	 * the zip archive is already downloaded from the cloud and stored in the wp-uploads folder
	 *
	 * @param string $zip_file_path
	 * @param string $template_key
	 * @param bool $skip_zip_check whether or not to check the zip file for integrity - from templates downloaded directly from the cloud, this can remain true
	 *
	 * @return string the new template name (with the prefix appended to it)
	 */
	public function importFromCloud( $zip_file_path, $template_key, $skip_zip_check = true ) {
		$old_umask = umask( 0 );

		defined( 'FS_METHOD' ) || define( 'FS_METHOD', 'direct' );
		WP_Filesystem();

		$upload = $this->getUploadDir();

		$folder = trailingslashit( $upload['basedir'] ) . TVE_CLOUD_LP_FOLDER . '/';

		if ( $skip_zip_check ) {
			/* this means the template archive is coming directly from the Thrive Template Cloud, we can trust it */
			$result = unzip_file( $zip_file_path, $folder );

			if ( $result instanceof WP_Error ) {
				umask( $old_umask );
				throw new Exception( __( 'Could not extract the archive file', 'thrive-cb' ) );
			}
		} else {
			// TODO: check if ZipArchive class exists, open zip file using ZipArchive, check for required file contents etc
		}

		if ( ! is_readable( $folder . $template_key . '.json' ) ) {
			throw new Exception( __( 'Could not read configuration file for the template', 'thrive-cb' ) );
		}

		$config = json_decode( file_get_contents( $folder . $template_key . '.json' ), true );

		if ( ! isset( $config['check'] ) ) {
			throw new Exception( __( 'Could not validate the configuration file for this template', 'thrive-cb' ) );
		}

		// validate the checksum of the data in the config array
		$check = $config['check'];
		unset( $config['check'] );

		if ( $check != md5( TCB_Landing_Page_Cloud_Templates_Api::API_KEY . serialize( $config ) ) ) {
			throw new Exception( __( 'Could not validate the configuration file for this template', 'thrive-cb' ) );
		}

		$downloaded_templates = tve_get_downloaded_templates();

		$config['base_url'] = trailingslashit( $upload['baseurl'] ) . TVE_CLOUD_LP_FOLDER;
		/* strip all protocols from the base_url - this is used for enqueing css / js */
		$config['base_url'] = str_replace( array( 'http://', 'https://' ), '//', $config['base_url'] );
		$config['base_dir'] = str_replace( '\\', '\\\\', untrailingslashit( $folder ) ); // stored this here so we can be more flexible if there are changes of paths on the server
		$config['thumb']    = trailingslashit( $upload['baseurl'] ) . TVE_CLOUD_LP_FOLDER . '/templates/thumbnails/' . $template_key . '.png';

		$downloaded_templates[ $template_key ] = $config;

		tve_save_downloaded_templates( $downloaded_templates );

		return $template_key;

	}

}

/** various utility functions for handling file operations */

/**
 * wp_upload_bits does not allow creating subfolders in the upload_dir structure
 * we need to upload some things into a grouped manner - e.g. font files for custom icons
 *
 * @param string $full_path full file path
 * @param string $contents
 *
 * @throws Exception
 *
 * @return true on success
 */
function tve_wp_upload_bits( $full_path, $contents ) {
	$ifp = @ fopen( $full_path, 'wb' );
	if ( ! $ifp ) {
		throw new Exception( sprintf( __( 'Could not write file %s', 'thrive-cb' ), basename( $full_path ) ) );
	}

	@fwrite( $ifp, $contents );
	fclose( $ifp );
	clearstatcache();

	// Set correct file permissions
	$stat  = @ stat( dirname( $full_path ) );
	$perms = $stat['mode'] & 0007777;
	$perms = $perms & 0000666;
	@ chmod( $full_path, $perms );
	clearstatcache();

	return true;
}