<?php


/**
 * Thrive_Dash_Api_AWeber_Oauth_User
 *
 * Simple data class representing the user in an OAuth application.
 * @package
 * @version $id$
 */
class Thrive_Dash_Api_AWeber_Oauth_User {
	public $authorizedToken = false;
	public $requestToken = false;
	public $verifier = false;
	public $tokenSecret = false;
	public $accessToken = false;

	/**
	 * isAuthorized
	 *
	 * Checks if this user is authorized.
	 * @access public
	 * @return bool
	 */
	public function isAuthorized() {
		return ! ( empty( $this->authorizedToken ) && empty( $this->accessToken ) );
	}


	/**
	 * getHighestPriorityToken
	 *
	 * Returns highest priority token - used to define authorization
	 * state for a given Thrive_Dash_Api_AWeber_Oauth_User
	 * @access public
	 * @return string
	 */
	public function getHighestPriorityToken() {
		if ( ! empty( $this->accessToken ) ) {
			return $this->accessToken;
		}
		if ( ! empty( $this->authorizedToken ) ) {
			return $this->authorizedToken;
		}
		if ( ! empty( $this->requestToken ) ) {
			return $this->requestToken;
		}

		// Return no token, new user
		return '';
	}
} 