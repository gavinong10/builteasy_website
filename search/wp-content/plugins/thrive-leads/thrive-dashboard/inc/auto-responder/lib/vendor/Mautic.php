<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * Mautic API Factory
 */
class Thrive_Dash_Api_Mautic {
	/**
	 * Get an API context object
	 *
	 * @param string $apiContext API context (leads, forms, etc)
	 * @param Thrive_Dash_Api_Mautic_AuthInterface $auth API Auth object
	 * @param string $baseUrl Base URL for API endpoints
	 *
	 * @return Thrive_Dash_Api_Mautic_Api
	 * @throws Thrive_Dash_Api_Mautic_ContextNotFoundException
	 *
	 * @deprecated
	 */
	public static function getContext( $apiContext, Thrive_Dash_Api_Mautic_AuthInterface $auth, $baseUrl = '' ) {
		static $contexts = array();

		$apiContext = ucfirst( $apiContext );

		if ( ! isset( $context[ $apiContext ] ) ) {
			$class = 'Thrive_Dash_Api_Mautic_' . $apiContext;

			if ( ! class_exists( $class ) ) {
				throw new Thrive_Dash_Api_Mautic_ContextNotFoundException( "A context of '$apiContext' was not found." );
			}

			$contexts[ $apiContext ] = new $class( $auth, $baseUrl );
		}

		return $contexts[ $apiContext ];
	}

	/**
	 * Get an API context object
	 *
	 * @param string $apiContext API context (leads, forms, etc)
	 * @param Thrive_Dash_Api_Mautic_AuthInterface $auth API Auth object
	 * @param string $baseUrl Base URL for API endpoints
	 *
	 * @return Thrive_Dash_Api_Mautic_Api
	 * @throws Thrive_Dash_Api_Mautic_ContextNotFoundException
	 */
	public function newApi( $apiContext, Thrive_Dash_Api_Mautic_AuthInterface $auth, $baseUrl = '' ) {
		$apiContext = ucfirst( $apiContext );

		$class = 'Thrive_Dash_Api_Mautic_' . $apiContext;

		if ( ! class_exists( $class ) ) {
			throw new Thrive_Dash_Api_Mautic_ContextNotFoundException( "A context of '$apiContext' was not found." );
		}

		return new $class( $auth, $baseUrl );
	}
}
