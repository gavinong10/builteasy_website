<?php

/**
 * Class PagesTab
 */
class Thrive_Leads_Pages_Tab extends Thrive_Leads_Tab implements Thrive_Leads_Tab_Interface {
	protected function matchItems() {
		if ( ! $this->getItems() ) {
			return array();
		}

		$optionArr = $this->getSavedOptions()->getTabSavedOptions( 3, $this->hanger );

		foreach ( $this->getItems() as $page ) {
			$option = new Thrive_Leads_Option();
			$option->setLabel( $page->post_title );
			$option->setId( $page->ID );
			$option->setType( 'item_page' );
			$option->setIsChecked( in_array( $page->ID, $optionArr ) );
			$this->options[] = $option;
		}
	}

	protected function getSavedOption( $item ) {
		return $this->getSavedOptionForTab( 3, $item->ID );
	}

	/**
	 * @return $this
	 */
	protected function initItems() {
		$this->setItems( get_pages( array(
			'sort_column'  => 'post_title',
			'sort_order'   => 'ASC',
			'hierarchical' => 0
		) ) );

		return $this;
	}

	/**
	 * @param $post WP_Post
	 *
	 * @return bool
	 */
	public function displayWidget( WP_Post $post ) {
		$this->hanger = 'show_group_options';
		$showOption   = $this->getSavedOption( $post );
		$display      = $showOption->isChecked;

		if ( $display === true ) {
			$this->hanger = 'hide_group_options';
			$display      = ! $this->getSavedOption( $post )->isChecked;
		}

		return $display;

	}

	public function isPageDenied( $page ) {
		$this->hanger = 'hide_group_options';

		return $this->getSavedOption( $page )->isChecked;
	}

	public function isPageAllowed( $page ) {
		$this->hanger = 'show_group_options';

		return $this->getSavedOption( $page )->isChecked;
	}

}
