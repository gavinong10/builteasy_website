<?php

/**
 * Class PostsTab
 */
class Thrive_Leads_Posts_Tab extends Thrive_Leads_Tab implements Thrive_Leads_Tab_Interface {
	protected function matchItems() {
		if ( ! $this->getItems() ) {
			return array();
		}

		$optionArr = $this->getSavedOptions()->getTabSavedOptions( 2, $this->hanger );

		foreach ( $this->getItems() as $post ) {
			$option = new Thrive_Leads_Option();
			$option->setLabel( $post->post_title );
			$option->setId( $post->ID );
			$option->setIsChecked( in_array( $post->ID, $optionArr ) );
			$this->options[] = $option;
		}
	}

	/**
	 * Overwrite this method to set a specific list of actions
	 * @return array of Action
	 */
	public function getActions() {
		return array();
	}

	/**
	 * @param $item WP_Post
	 *
	 * @return Option
	 */
	protected function getSavedOption( $item ) {
		return $this->getSavedOptionForTab( 2, $item->ID );
	}

	/**
	 * @return $this
	 */
	protected function initItems() {
		$items = array();

		$options = $this->getSavedOptions()->getTabSavedOptions( 2, $this->hanger );
		if ( ! empty( $options ) ) {
			$items = get_posts( array(
				'posts_per_page' => - 1,
				'include'        => $options
			) );
		}

		$this->setItems( $items );

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

	public function isPostDenied( $post ) {
		$this->hanger = 'hide_group_options';

		return $this->getSavedOption( $post )->isChecked;
	}

	public function isPostAllowed( $post ) {
		$this->hanger = 'show_group_options';

		return $this->getSavedOption( $post )->isChecked;
	}

}
