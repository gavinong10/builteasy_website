<?php

/**
 * Class Thrive_Leads_Taxonomy_Archives_Tab
 */
class Thrive_Leads_Taxonomy_Archives_Tab extends Thrive_Leads_Tab implements Thrive_Leads_Tab_Interface {
	protected function matchItems() {
		if ( ! $this->getItems() ) {
			return array();
		}

		$optionArr = $this->getSavedOptions()->getTabSavedOptions( 6, $this->hanger );

		foreach ( $this->getItems() as $id => $taxonomy ) {
			$option = new Thrive_Leads_Option();
			$option->setLabel( $taxonomy->label );
			$option->setId( $id );
			$option->setIsChecked( in_array( $id, $optionArr ) );
			$this->options[] = $option;
		}
	}

	protected function getSavedOption( $item ) {
		return $this->getSavedOptionForTab( 6, $item );
	}

	/**
	 * @return $this
	 */
	protected function initItems() {
		$this->setItems( get_taxonomies( array(
			'public' => true
		), 'objects' ) );

		return $this;
	}

	/**
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	public function displayWidget( $taxonomy = null ) {
		if ( ! $taxonomy ) {
			return false;
		}

		$this->hanger = 'show_group_options';
		$showOption   = $this->getSavedOption( $taxonomy->taxonomy );
		$display      = $showOption->isChecked;

		if ( $display === true ) {
			$this->hanger = 'hide_group_options';
			$display      = ! $this->getSavedOption( $taxonomy->taxonomy )->isChecked;
		}

		return $display;

	}

	public function isTaxonomyAllowed( $taxonomy = null ) {
		$this->hanger = 'show_group_options';
		if ( empty( $taxonomy->taxonomy ) ) {
			return false;
		}

		return $this->getSavedOption( $taxonomy->taxonomy )->isChecked;
	}

	public function isTaxonomyDenied( $taxonomy = null ) {
		if ( empty( $taxonomy->taxonomy ) ) {
			return false;
		}
		$this->hanger = 'hide_group_options';

		return $this->getSavedOption( $taxonomy->taxonomy )->isChecked;
	}
}
