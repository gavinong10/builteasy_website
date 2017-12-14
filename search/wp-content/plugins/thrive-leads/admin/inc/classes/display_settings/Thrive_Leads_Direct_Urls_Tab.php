<?php

/**
 * Class DirectUrlsTab
 */
class Thrive_Leads_Direct_Urls_Tab extends Thrive_Leads_Tab implements Thrive_Leads_Tab_Interface {
	const OPTION_TYPE = 'direct_url';

	protected $exclusions = array();

	public function __construct() {
		//override the construct just to reset the actions array
	}

	public function setExclusions( $exclusions ) {
		$this->exclusions = $exclusions;
	}

	protected function matchItems() {
		if ( ! $this->getItems() ) {
			return array();
		}

		foreach ( $this->getItems() as $id => $link ) {
			$option = new Thrive_Leads_Option();
			$option->setLabel( $link );
			$option->setId( $id );
			$option->setType( self::OPTION_TYPE );
			$this->options[] = $option;
		}
	}

	/**
	 * Wordpress doesnt have a list of direct URLs
	 * and we dont have to match any item with any saved option
	 *
	 * @param $item
	 *
	 * @return Option|void
	 */
	protected function getSavedOption( $item ) {

	}

	/**
	 * User adds options|links
	 * Read them from DB and set as items
	 * @return $this
	 */
	protected function initItems() {
		$savedOptions = $this->getSavedOptions()->getTabSavedOptions( 7, $this->hanger );
		if ( ! $savedOptions ) {
			$this->setItems( array() );

			return $this;
		}

		$items = array();
		foreach ( $savedOptions as $option ) {
			if ( array_key_exists( $option, $this->exclusions ) ) {
				continue;
			}
			$items[ $option ] = $option;
		}
		$this->setItems( $items );

		return $this;
	}

	/**
	 * @param $url string
	 *
	 * @return bool
	 */
	public function displayWidget( $url ) {
		$this->hanger = 'show_group_options';
		$display      = false;

		foreach ( $this->getItems() as $showingUrl ) {
			if ( $url === $showingUrl ) {
				$display = true;
			}
		}

		if ( $display === true ) {
			$this->hanger = 'hide_group_options';
			$this->initItems();
			foreach ( $this->getItems() as $hidingUrl ) {
				if ( $url === $hidingUrl ) {
					$display = false;
				}
			}
		}

		return $display;

	}

	public function isUrlDenied( $url ) {
		$denied = false;

		$this->hanger = 'hide_group_options';
		$this->initItems();

		foreach ( $this->getItems() as $hidingUrl ) {
			if ( $this->clearUrl( $url ) === $this->clearUrl( $hidingUrl ) ) {
				$denied = true;
			}
		}

		return $denied;
	}

	public function isUrlAllowed( $url ) {
		$allowed = false;

		$this->hanger = 'show_group_options';
		$this->initItems();
		foreach ( $this->getItems() as $hidingUrl ) {
			if ( $this->clearUrl( $url ) === $this->clearUrl( $hidingUrl ) ) {
				$allowed = true;
			}
		}

		return $allowed;
	}

	protected function clearUrl( $url ) {
		if ( strpos( $url, 'http://' ) !== false ) {
			$url = substr( $url, strlen( 'http://' ) );
		}
		if ( strpos( $url, 'https://' ) !== false ) {
			$url = substr( $url, strlen( 'https://' ) );
		}
		if ( strpos( $url, 'www.' ) !== false ) {
			$url = substr( $url, strlen( 'www.' ) );
		}

		return trim( $url, '/ ' );
	}

}
