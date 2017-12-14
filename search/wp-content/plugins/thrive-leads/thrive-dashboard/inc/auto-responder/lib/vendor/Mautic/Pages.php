<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * Pages Context
 */
class Thrive_Dash_Api_Mautic_Pages extends Thrive_Dash_Api_Mautic_Api {

	/**
	 * {@inheritdoc}
	 */
	protected $endpoint = 'pages';

	/**
	 * {@inheritdoc}
	 */
	public function create( array $parameters ) {
		return $this->actionNotSupported( 'create' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function edit( $id, array $parameters, $createIfNotExists = false ) {
		return $this->actionNotSupported( 'edit' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete( $id ) {
		return $this->actionNotSupported( 'delete' );
	}
}
