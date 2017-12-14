<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * Exception representing a requested API context which was not found
 */
class Thrive_Dash_Api_Mautic_ContextNotFoundException extends Exception {
	/**
	 * {@inheritdoc}
	 */
	public function __construct( $message = 'Context not found.', $code = 500, Exception $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}
}
