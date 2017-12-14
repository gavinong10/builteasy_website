<?php
if ( !class_exists( "pb_backupbuddy_dropbuddy" ) ) {
	
	
	// Handle PEAR include files.
	// june 16, 2012: All PEAR files now explicitly included via absolute path. No longer using system PEARs.
	/*
	$include_path = get_include_path(); // Get current include path.
	if ( $include_path == '' ) { // If no current include path then load the default.
		$include_path = DEFAULT_INCLUDE_PATH; // Default include path.
	}
	
	if ( pb_backupbuddy::$options['include_dropbox_pear'] == '1' ) {
		$include_path = DEFAULT_INCLUDE_PATH; // Default include path.
		ini_set( 'include_path', $include_path . PATH_SEPARATOR  . dirname( __FILE__ ) . '/pear_includes' );
	}
	*/


	
	include( 'dropbox_api/autoload.php' );
	
	
	class pb_backupbuddy_dropbuddy {
		var $_key  = '0hss3jh8kmdrcgr';
		var $_secret = '8u40d9dn6t4gv18';
		
		function __construct( &$token ) {
			$this->_token = &$token;
			//echo 'token:<pre>';
			//print_r( $this->_token );
			//echo '</pre>!';
			if ( !isset( $this->_token['access'] ) ) {
				$this->_token['access'] = false;
				$this->_token['request'] = false;
				//echo 'tokennew:<pre>';
				//print_r( $this->_token );
				//echo '</pre>!';
			}

		}
		
		function authenticate() {
			$oauth = new Dropbox_OAuth_PEAR( $this->_key, $this->_secret );
			
			if ( $this->_token['access'] === false ) { // Need to get a token if we dont have access yet.
				try {
					//echo 'Getting_Token.';
					//echo '<pre>';
					//print_r( $this->_token );
					//echo '</pre>';
					$oauth->setToken( $this->_token['request'] );
					$this->_token['access'] = $oauth->getAccessToken();
					pb_backupbuddy::save();
				} catch ( Exception $e ) { // Authorization failed. No token.
					//echo 'Access_Denied.';
					$this->_token['access'] = false;
				}
				//pb_backupbuddy::save();
			} else {
				$oauth->setToken( $this->_token['access'] );
			}
			$this->_dropbox = new Dropbox_API( $oauth );
			
			return $this->is_authorized();
		}
		
		function get_authorize_url() {
			$oauth = new Dropbox_OAuth_PEAR( $this->_key, $this->_secret );
			
			$this->_token['request'] = $oauth->getRequestToken();
			pb_backupbuddy::save();
			
			//echo 'authorizeurltoken:<pre>';
			//print_r( $this->_token );
			//echo '</pre>';
			
			return str_replace( 'api.', 'www.', $oauth->getAuthorizeUrl() );
		}
		
		function get_account_info() {
			try {
				return $this->_dropbox->getAccountInfo();
			} catch( Exception $e ) {
				return false;
			}
		}
		
		function get_meta_data( $path ) {
			try {
				return $this->_dropbox->getMetaData( $path );
			} catch ( Exception $e ) {
				return 'The specified path does not exist.';
			}
			
		}
		
		// Remote path includes filename.  Ex: backupbuddy\file.zip
		// @return true on success, array of results on failure.
		function put_file( $remote_path, $file ) {
			return $this->_dropbox->putFile( $remote_path, $file );
		}
		
		function get_file( $path ) {
			return $this->_dropbox->getFile( $path );
		}
		
		function delete( $path ) {
			return $this->_dropbox->delete( $path );
		}
		
		function is_authorized() {
			return $this->_token['access'] && $this->get_account_info();
		}
	} // End class
	
}