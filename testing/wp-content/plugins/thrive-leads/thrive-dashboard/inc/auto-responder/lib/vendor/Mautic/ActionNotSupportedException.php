<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * Exception representing an unsupported action
 */
class Thrive_Dash_Api_Mautic_ActionNotSupportedException extends Exception {
	/**
	 * {@inheritdoc}
	 */
	public function __construct( $message = 'Action is not supported at this time.', $code = 500, Exception $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}
}
