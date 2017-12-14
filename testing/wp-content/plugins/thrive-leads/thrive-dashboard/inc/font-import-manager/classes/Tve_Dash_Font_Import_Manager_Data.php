<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/25/2015
 * Time: 1:42 PM
 */

if ( class_exists( 'Tve_Dash_Font_Import_Manager_Data' ) ) {
	return;
}

class Tve_Dash_Font_Import_Manager_Data {
	protected $zip_contents = array(
		'stylesheet.css'
	);

	public function processZip( $zip_file, $zip_url ) {
		$clean_filename = $this->validateZip( $zip_file );

		//Step 1: make sure the destination folder exists
		$font_dir_path = dirname( $zip_file ) . '/' . $clean_filename;

		//Step 1.1 prepare the filesystem
		$extra = array(
			'attachment_id',
		);

		$credentials = request_filesystem_credentials( admin_url( "admin.php?page=tve_dash_font_import_manager" ), '', false, false, $extra );
		if ( ! $credentials ) {
			//show FTP form
			die;
		}

		if ( ! WP_Filesystem( $credentials ) ) {
			throw new Exception( "Invalid credentials" );
		}

		$result = unzip_file( $zip_file, $font_dir_path );
		if ( is_wp_error( $result ) ) {
			throw new Exception( 'Error (unzip): ' . $result->get_error_message() );
		}

		//Step 3: process the zip

		//check for selection.json file to be able to check the font family from within it
		$this->checkExtractedFiles( $font_dir_path );

		//Step 4: extract the font families from css file
		$font_families = $this->extractFontFamilies( $font_dir_path . '/stylesheet.css' );

		$data = array(
			'folder'        => $font_dir_path,
			'css_file'      => dirname( $zip_url ) . '/' . $clean_filename . '/stylesheet.css',
			'font_families' => $font_families,
			'zip_url'       => $zip_url,
			'zip_path'      => $zip_file,
			'filename'      => basename( $zip_file )
		);

		return $data;
	}

	public function extractFontFamilies( $file ) {
		if ( ! is_file( $file ) ) {
			throw new Exception( $file . " cannot be found to apply changes on it !" );
		}

		$content = file_get_contents( $file );
		$pattern = "/font-family: '(.*)'/";

		preg_match_all( $pattern, $content, $matches );

		if ( empty( $matches[1] ) ) {
			throw new Exception( "No font family font" );
		}

		return $matches[1];
	}

	public function checkExtractedFiles( $dir, $specific_files = array() ) {
		if ( empty( $dir ) ) {
			throw new Exception( 'Empty folder' );
		}

		if ( ! is_array( $specific_files ) ) {
			$specific_files = array( $specific_files );
		}
		$files_to_check = array_intersect( $this->zip_contents, $specific_files );

		if ( empty( $files_to_check ) ) {
			$files_to_check = $this->zip_contents;
		}

		foreach ( $files_to_check as $expected_file ) {
			if ( ! is_file( $dir . '/' . $expected_file ) ) {
				throw new Exception( 'Could not find the following file inside the archive: ' . $expected_file );
			}
		}

	}

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

	public function deleteDir( $dirPath ) {
		if ( ! is_dir( $dirPath ) ) {
			throw new InvalidArgumentException( "$dirPath must be a directory" );
		}
		if ( substr( $dirPath, strlen( $dirPath ) - 1, 1 ) != '/' ) {
			$dirPath .= '/';
		}
		$files = glob( $dirPath . '*', GLOB_MARK );
		foreach ( $files as $file ) {
			if ( is_dir( $file ) ) {
				self::deleteDir( $file );
			} else {
				unlink( $file );
			}
		}

		return rmdir( $dirPath );
	}
}

if ( ! class_exists( 'Thrive_Font_Import_Manager' ) ) {
	class Thrive_Font_Import_Manager extends Tve_Dash_Font_Import_Manager_Data {

	}
}