<?php

/**
 * OAuthServiceProvider
 *
 * Represents the service provider in the OAuth authentication model.
 * The class that implements the service provider will contain the
 * specific knowledge about the API we are interfacing with, and
 * provide useful methods for interfacing with its API.
 *
 * For example, an OAuthServiceProvider would know the URLs necessary
 * to perform specific actions, the type of data that the API calls
 * would return, and would be responsible for manipulating the results
 * into a useful manner.
 *
 * It should be noted that the methods enforced by the OAuthServiceProvider
 * interface are made so that it can interact with our OAuthApplication
 * cleanly, rather than from a general use perspective, though some
 * methods for those purposes do exists (such as getUserData).
 *
 * @package
 * @version $id$
 */
interface Thrive_Dash_Api_AWeber_Oauth_ServiceProvider {
	public function getAccessTokenUrl();

	public function getAuthorizeUrl();

	public function getRequestTokenUrl();

	public function getAuthTokenFromUrl();

	public function getBaseUri();

	public function getUserData();
} 