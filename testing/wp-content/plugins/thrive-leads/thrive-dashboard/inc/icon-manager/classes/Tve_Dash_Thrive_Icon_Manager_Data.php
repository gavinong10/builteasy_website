<?php

if ( ! class_exists( 'Tve_Dash_Thrive_Icon_Manager_Data' ) ) {

	/**
	 *
	 * handles the processing and retrieving of all icon-manager related data
	 *
	 * Class Thrive_Icon_Manager_Data
	 */
	class Tve_Dash_Thrive_Icon_Manager_Data {

		/**
		 * a list containing all expected files inside a zip archive - relative to the archive root folder
		 * @var array
		 */
		protected $zip_contents = array(
			'demo.html',
			'style.css',
			'selection.json',
			'fonts/[fontFamily].eot',
			'fonts/[fontFamily].svg',
			'fonts/[fontFamily].ttf',
			'fonts/[fontFamily].woff',
		);

		/**
		 *
		 * @param string $zip_file full path to IcoMoon zip file
		 * @param string $zip_url
		 *
		 * @throws Exception
		 *
		 * @return array processed icons - Icon classes and the url to the css file
		 */
		public function processZip( $zip_file, $zip_url ) {
			$clean_filename = $this->validateZip( $zip_file );

			//Step 1: make sure the destination folder exists
			$font_dir_path = dirname( $zip_file ) . '/' . $clean_filename;

			//Step 1.1 prepare the filesystem
			$extra = array(
				'tve_save_icon_pack',
				'tve_icon_pack_url',
				'attachment_id',
			);

			$credentials = request_filesystem_credentials( admin_url( "admin.php?page=tve_dash_icon_manager" ), '', false, false, $extra );
			if ( ! $credentials ) {
				//show FTP form
				die;
			}

			if ( ! WP_Filesystem( $credentials ) ) {
				throw new Exception( "Invalid credentials" );
			}

			//Step 2: unzip
			$result = unzip_file( $zip_file, $font_dir_path );
			if ( is_wp_error( $result ) ) {
				throw new Exception( 'Error (unzip): ' . $result->get_error_message() );
			}

			/** @var WP_Filesystem_Base $wp_filesystem */
			global $wp_filesystem;
			//Step 3: process the zip

			//check for selection.json file to be able to check the font family from within it
			$this->checkExtractedFiles( $font_dir_path, 'selection.json' );

			//read the config
			$config = @json_decode( $wp_filesystem->get_contents( $font_dir_path . '/selection.json' ), true );

			//read the font family from config
			$font_family = $config['preferences']["fontPref"]["metadata"]["fontFamily"];

			//replace the placeholders for expecting files
			$this->prepareRequiredFiles( $font_family );

			//check the files expected to be in zip
			$this->checkExtractedFiles( $font_dir_path );
			$this->applyChangesOnStyle( $font_dir_path . '/style.css', $font_family );

			if ( empty( $config ) || empty( $config['icons'] ) ) {
				throw new Exception( 'It seems something is wrong inside the selection.json config file' );
			}
			$data = array(
				'folder'      => $font_dir_path,
				'css'         => dirname( $zip_url ) . '/' . $clean_filename . '/style.css',
				'icons'       => array(),
				'fontFamily'  => $font_family,
				'css_version' => rand( 1, 9 ) . '.' . str_pad( rand( 1, 99 ), 2, '0', STR_PAD_LEFT ),
				'variations'  => array(),
			);

			$prefix         = empty( $config['preferences']['fontPref']['prefix'] ) ? 'icon-' : $config['preferences']['fontPref']['prefix'];
			$class_selector = ! empty( $config['preferences']['fontPref']['classSelector'] ) ? str_replace( '.', '', $config['preferences']['fontPref']['classSelector'] ) : '';

			foreach ( $config['icons'] as $icon_data ) {
				if ( empty( $icon_data['properties']['name'] ) ) {
					continue;
				}
				$variations       = $this->get_icon_variations( $icon_data['properties']['name'], $prefix );
				$name             = reset( $variations );
				$data['icons'] [] = ( $class_selector ? $class_selector . ' ' : '' ) . $name;
				if ( count( $variations ) > 1 ) {
					$data['variations'][ $name ] = implode( ', ', $variations );
				}
			}

			return $data;
		}

		/**
		 * users can define multiple classes for an icon, separated by comma. this will make sure that we also handle those cases
		 *
		 * @param string $name
		 * @param string $prefix prefix for the icon names
		 *
		 * @return array
		 */
		protected function get_icon_variations( $name, $prefix ) {
			$prefixer = create_function( '$a', 'return "' . $prefix . '" . trim($a);' );

			$variations = explode( ',', $name );
			$variations = array_map( 'trim', $variations );
			$variations = array_filter( $variations );
			$variations = array_map( $prefixer, $variations );

			return $variations;
		}

		/**
		 * Replace the placeholders from required file names with $font_family
		 *
		 * @param $font_family
		 */
		public function prepareRequiredFiles( $font_family ) {
			foreach ( $this->zip_contents as $key => $file ) {
				$this->zip_contents[ $key ] = str_replace( "[fontFamily]", $font_family, $file );
			}
		}

		public function applyChangesOnStyle( $css_file, $font_family ) {
			/* @var WP_Filesystem_Base $wp_filesystem */
			global $wp_filesystem;
			if ( ! $wp_filesystem->is_file( $css_file ) ) {
				throw new Exception( $css_file . " cannot be found to apply changes on it !" );
			}
			$file_content = $wp_filesystem->get_contents( $css_file );

			$search      = "font-family: '{$font_family}';";
			$replacement = "font-family: '{$font_family}' !important;";

			/**
			 * make sure the replace is done after the @font-face declaration
			 */
			if ( ! preg_match( '#@font-face([^\}]+)\}#si', $file_content, $m, PREG_OFFSET_CAPTURE ) ) {
				return true;
			}
			$position = strlen( $m[0][0] ) + $m[0][1];
			$position = strpos( $file_content, $search, $position );
			if ( $position === false ) {
				return true;
			}

			$file_content = substr_replace( $file_content, $replacement, $position, strlen( $search ) );

			return $wp_filesystem->put_contents( $css_file, $file_content );
		}

		/**
		 * validate the file
		 *
		 * @param string $file
		 */
		public function validateZip( $file ) {
			if ( empty( $file ) || ! is_file( $file ) ) {
				throw new Exception( 'Invalid or empty file' );
			}

			$info = wp_check_filetype( $file );

			if ( strtolower( $info['ext'] ) !== 'zip' ) {
				throw new Exception( 'The selected file is not a zip archive' );
			}

			return trim( str_replace( $info['ext'], '', basename( $file ) ), '/. ' );

		}

		/**
		 * check if all the necessary files exist in the specified $dir
		 *
		 * @param string $dir
		 */
		public function checkExtractedFiles( $dir, $specific_files = array() ) {
			if ( empty( $dir ) ) {
				throw new Exception( 'Empty folder' );
			}
			/** @var WP_Filesystem_Base $wp_filesystem */
			global $wp_filesystem;

			if ( ! is_array( $specific_files ) ) {
				$specific_files = array( $specific_files );
			}
			$files_to_check = array_intersect( $this->zip_contents, $specific_files );

			if ( empty( $files_to_check ) ) {
				$files_to_check = $this->zip_contents;
			}

			foreach ( $files_to_check as $expected_file ) {
				if ( ! $wp_filesystem->is_file( $dir . '/' . $expected_file ) ) {
					throw new Exception( 'Could not find the following file inside the archive: ' . $expected_file );
				}
			}

		}

		/**
		 * try to completely remove the $folder
		 *
		 * @param string $folder full path to the IcoMoon folder
		 */
		public function removeIcoMoonFolder( $folder, $fontFamily = '' ) {
			$this->prepareRequiredFiles( $fontFamily );

			foreach ( $this->zip_contents as $file ) {
				if ( is_file( $folder . '/' . $file ) ) {
					@unlink( $folder . '/' . $file );
				}
			}

			@rmdir( $folder . '/fonts' );
			@unlink( $folder . '/demo-files/demo.css' );
			@unlink( $folder . '/demo-files/demo.js' );
			@rmdir( $folder . '/demo-files' );
			@unlink( $folder . '/Read Me.txt' );

			@rmdir( $folder );
		}
	}
}
