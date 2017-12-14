<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * OAuth Client modified from https://code.google.com/p/simple-php-oauth/
 */
class Thrive_Dash_Api_Mautic_ApiAuth {
	/**
	 * Get an API Auth object
	 *
	 * @param array $parameters
	 * @param string $authMethod
	 *
	 * @return $this
	 *
	 * @deprecated
	 */
	public static function initiate( $parameters = array(), $authMethod = 'OAuth' ) {
		$object = new self;

		return $object->newAuth( $parameters, $authMethod );
	}

	/**
	 * Get an API Auth object
	 *
	 * @param array $parameters
	 * @param string $authMethod
	 *
	 * @return $this
	 */
	public function newAuth( $parameters = array(), $authMethod = 'OAuth' ) {
		$class      = 'Thrive_Dash_Api_Mautic_' . $authMethod;
		$authObject = new $class();

		$reflection = new ReflectionMethod( $class, 'setup' );
		$pass       = array();

		foreach ( $reflection->getParameters() as $param ) {
			if ( isset( $parameters[ $param->getName() ] ) ) {
				$pass[] = $parameters[ $param->getName() ];
			} else {
				$pass[] = null;
			}
		}

		$reflection->invokeArgs( $authObject, $pass );

		return $authObject;
	}
}
