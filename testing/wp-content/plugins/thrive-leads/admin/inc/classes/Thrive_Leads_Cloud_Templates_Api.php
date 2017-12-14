<?php

/**
 * folder where we keep the landing pages downloaded from the cloud, relative to the wp-uploads folder
 */
defined( 'TVE_LEADS_CLOUD_FORM_FOLDER' ) || define( 'TVE_LEADS_CLOUD_FORM_FOLDER', 'tve_leads_templates' );

/**
 * Created by PhpStorm.
 * User: dan
 * Date: 18.04.2016
 * Time: 12:31
 */
class Thrive_Leads_Cloud_Templates_APi extends TCB_Landing_Page_Cloud_Templates_Api {

	protected $accepted_form_type = array();

	/**
	 * @var string
	 */
	protected $form_type;

	/**
	 * @var int|bool
	 */
	protected $multi_step;

	/**
	 * overwritten singleton
	 *
	 * @return Thrive_Leads_Cloud_Templates_APi
	 */
	public static function getInstance() {
		if ( null === self::$_instance ) {
			self::$_instance = new Thrive_Leads_Cloud_Templates_APi();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->accepted_form_type = array_keys( tve_leads_get_default_form_types( true ) );
	}

	/**
	 * get the template list
	 *
	 * @throws Exception if response data and success properties does not have expected values
	 *
	 * @return array
	 */
	public function getTemplateList() {

		if ( ! $this->isValidFormType() ) {
			throw new Exception ( 'Invalid form type' );
		}

		$params = array(
			'route'              => 'getAll',
			'form_type'          => $this->form_type,
			'exclude_multi_step' => isset( $_REQUEST['exclude_multi_step'] ) ? $_REQUEST['exclude_multi_step'] : '0',
		);

		$response = $this->_request( $params );

		$data = json_decode( $response, true );

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
	 * After getting the instance you can set some data on it
	 *
	 * @param $data
	 *
	 * @return $this
	 */
	public function init( $data ) {

		if ( is_array( $data ) && ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				$this->{$key} = $value;
			}
		}

		return $this;
	}

	protected function isValidFormType() {
		return ! empty( $this->form_type ) && in_array( $this->form_type, $this->accepted_form_type );
	}

	/**
	 * Creates template folder in wp_load[basedir]
	 *
	 * @param array $wp_upload
	 * @param string $template_key
	 *
	 * @throws Exception if the folder could not be made
	 */
	public function createFolder( $wp_upload, $template_key ) {

		$base = trailingslashit( $wp_upload['basedir'] ) . TVE_LEADS_CLOUD_FORM_FOLDER . '/';

		if ( false === wp_mkdir_p( $base . $this->form_type . '/' . $template_key ) ) {
			throw new Exception( 'Could not create the templates folder' );
		}
	}

	/**
	 * Downloads the zip file for a template
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

		/** create folders in uploads - make sure all of the required folders exist */
		$this->createFolder( $upload, $this->multi_step ? '' : $template );

		$params = array(
			'route'      => 'download',
			'tpl'        => $template,
			'form_type'  => $this->form_type,
			'multi_step' => (string) $this->multi_step,
		);

		$body = $this->_request( $params );

		$this->log();

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
		$zip_path = trailingslashit( $upload['basedir'] ) . TVE_LEADS_CLOUD_FORM_FOLDER . '/' . $template . '.zip';

		tve_wp_upload_bits( $zip_path, $body );

		$transfer = new Thrive_Leads_Landing_Page_Transfer( $this->form_type, $this->multi_step );

		$result = $transfer->importFromCloud( $zip_path, $template );

		/* unlink the zip archive and the configuration file */
		@unlink( $zip_path );
		@unlink( trailingslashit( dirname( $zip_path ) ) . $template . '.json' );

		return $result;
	}

	protected function log() {
		if ( is_writable( plugin_dir_path( __FILE__ ) ) ) {
			$logfile = plugin_dir_path( __FILE__ ) . 'api.log';
			$wrote   = file_put_contents( $logfile, var_export( $this->request, true ) . "\n\n=====\n\n" );
			if ( $wrote ) {
				// Set correct file permissions
				$stat  = @ stat( dirname( $logfile ) );
				$perms = $stat['mode'] & 0007777;
				$perms = $perms & 0000666;
				@chmod( plugin_dir_path( __FILE__ ) . 'api.log', $perms );
			}
		}
	}
}

class Thrive_Leads_Landing_Page_Transfer extends TCB_Landing_Page_Transfer {

	/**
	 * @var string $form_type
	 */
	protected $form_type;

	/**
	 * @var bool
	 */
	protected $multi_step;

	public function __construct( $form_type, $multi_step = false ) {
		$this->form_type  = $form_type;
		$this->multi_step = (bool) $multi_step;
	}

	/**
	 * the zip archive is already downloaded from the cloud and stored in the wp-uploads folder
	 *
	 * @param string $zip_file_path
	 * @param string $template_key
	 * @param bool $skip_zip_check whether or not to check the zip file for integrity - from templates downloaded directly from the cloud, this can remain true
	 *
	 * @return string template_key
	 *
	 * @throws Exception
	 */
	public function importFromCloud( $zip_file_path, $template_key, $skip_zip_check = true ) {
		$old_umask = umask( 0 );

		defined( 'FS_METHOD' ) || define( 'FS_METHOD', 'direct' );
		WP_Filesystem();

		$upload = $this->getUploadDir();

		$folder = trailingslashit( $upload['basedir'] ) . TVE_LEADS_CLOUD_FORM_FOLDER . '/';

		if ( $skip_zip_check ) {
			/* this means the template archive is coming directly from the Thrive Template Cloud, we can trust it */
			$result = unzip_file( $zip_file_path, $folder );

			if ( $result instanceof WP_Error ) {
				umask( $old_umask );
				throw new Exception( __( 'Could not extract the archive file', 'thrive-leads' ) );
			}
		} else {
			// TODO: check if ZipArchive class exists, open zip file using ZipArchive, check for required file contents etc
		}

		$config_path = $folder . $this->form_type . '/' . $template_key . '/' . $template_key . '.json';
		if ( $this->multi_step ) {
			$config_path = $folder . $this->form_type . '/' . $template_key . '.json';
		}

		if ( ! is_readable( $config_path ) ) {
			throw new Exception( __( 'Could not read configuration file for the template', 'thrive-leads' ) );
		}

		$config = json_decode( file_get_contents( $config_path ), true );
		if ( $this->multi_step && ! isset( $config['states'] ) ) {
			throw new Exception( __( 'Config file missing', 'thrive-leads' ) );
		}

		/**
		 * is it's multi step then we save in db all the states as templates
		 */
		if ( isset( $config['states'] ) ) {
			$form_type_tmp = $this->form_type;
			$this->saveConfig( $config, $template_key );
			foreach ( $config['states'] as $key => $state ) {
				list( $type, $tpl ) = explode( '|', $state['tpl'] );
				$this->form_type = $type;
				$state_config    = json_decode( file_get_contents( $folder . $type . '/' . $tpl . '/' . $tpl . '.json' ), true );
				$this->prepareConfig( $state_config, $tpl );
				$this->saveConfig( $state_config, $tpl );
			}
			$this->form_type = $form_type_tmp;
		} else {
			$this->prepareConfig( $config, $template_key );
			$this->saveConfig( $config, $template_key );
		}

		return $template_key;
	}

	protected function prepareConfig( &$config, $template_key ) {

		if ( ! isset( $config['check'] ) ) {
			throw new Exception( __( 'Could not validate the configuration file for this template', 'thrive-leads' ) );
		}

		// validate the checksum of the data in the config array
		$check = $config['check'];
		unset( $config['check'] );

		if ( $check != md5( Thrive_Leads_Cloud_Templates_APi::API_KEY . serialize( $config ) ) ) {
			throw new Exception( __( 'Could not validate the configuration file for this template', 'thrive-leads' ) );
		}

		$upload = $this->getUploadDir();
		$folder = trailingslashit( $upload['basedir'] ) . TVE_LEADS_CLOUD_FORM_FOLDER . '/' . $this->form_type . '/' . $template_key;

		$config['base_url'] = trailingslashit( $upload['baseurl'] ) . TVE_LEADS_CLOUD_FORM_FOLDER . '/' . $this->form_type . '/' . $template_key;
		/** strip all protocols from the base_url - this is used for enqueing css / js */
		$config['base_url'] = str_replace( array( 'http://', 'https://' ), '//', $config['base_url'] );

		/** stored this here so we can be more flexible if there are changes of paths on the server */
		$config['base_dir'] = str_replace( '\\', '\\\\', untrailingslashit( $folder ) );

		if ( ! $this->multi_step ) {
			$config['thumbnail'] = trailingslashit( $upload['baseurl'] ) . TVE_LEADS_CLOUD_FORM_FOLDER . '/' . $this->form_type . '/' . $template_key . '/thumbnails/' . $template_key . '.png';
		}
	}

	protected function saveConfig( $config, $template_key ) {
		$downloaded_templates = tve_leads_get_downloaded_templates( $this->form_type );

		if ( ! isset( $downloaded_templates['multi_step'] ) || ! is_array( $downloaded_templates['multi_step'] ) ) {
			$downloaded_templates['multi_step'] = array();
		}

		if ( ! isset( $downloaded_templates['multi_step'][ $this->form_type ] ) || ! is_array( $downloaded_templates['multi_step'][ $this->form_type ] ) ) {
			$downloaded_templates['multi_step'][ $this->form_type ] = array();
		}

		if ( isset( $config['states'] ) ) {
			$downloaded_templates['multi_step'][ $this->form_type ][ $template_key ] = $config;
		}

		$downloaded_templates[ $template_key ] = $config;

		tve_leads_save_downloaded_templates( $this->form_type, $downloaded_templates );
	}
}
