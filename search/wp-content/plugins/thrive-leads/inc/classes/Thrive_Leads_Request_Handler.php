<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 28.01.2015
 * Time: 14:49
 */
class Thrive_Leads_Request_Handler {
	/**
	 * gets a request value and returns a default if the key is not set
	 * it will first search the POST array
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function param( $key, $default = null ) {
		return isset( $_POST[ $key ] ) ? $_POST[ $key ] : ( isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $default );
	}
} 