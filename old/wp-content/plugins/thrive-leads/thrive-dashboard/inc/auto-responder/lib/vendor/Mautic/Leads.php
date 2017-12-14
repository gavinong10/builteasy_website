<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * Leads Context
 *
 * This class is deprecated and will be removed in future versions! Use Contacts instead!
 */
class Thrive_Dash_Api_Mautic_Leads extends Thrive_Dash_Api_Mautic_Contacts {
	/**
	 * Get a list of lead segments
	 *
	 * @return array|mixed
	 */
	public function getLists() {
		return $this->makeRequest( 'contacts/list/segments' );
	}

	/**
	 * Get a list of a lead's notes
	 *
	 * @param int $id Contact ID
	 * @param string $search
	 * @param int $start
	 * @param int $limit
	 * @param string $orderBy
	 * @param string $orderByDir
	 *
	 * @return array|mixed
	 */
	public function getLeadNotes( $id, $search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC' ) {
		$parameters = array();

		$args = array( 'search', 'start', 'limit', 'orderBy', 'orderByDir' );

		foreach ( $args as $arg ) {
			if ( ! empty( $$arg ) ) {
				$parameters[ $arg ] = $$arg;
			}
		}

		return $this->makeRequest( 'contacts/' . $id . '/notes', $parameters );
	}

	/**
	 * Get a segment of smart segments the lead is in
	 *
	 * @param $id
	 *
	 * @return array|mixed
	 */
	public function getLeadLists( $id ) {
		return $this->makeRequest( 'contacts/' . $id . '/segments' );
	}

	/**
	 * Get a segment of campaigns the lead is in
	 *
	 * @param $id
	 *
	 * @return array|mixed
	 */
	public function getLeadCampaigns( $id ) {
		return $this->makeRequest( 'contacts/' . $id . '/campaigns' );
	}
}
