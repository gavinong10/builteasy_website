<?php

/**
 * Class PagesTab
 */
class Thrive_Leads_Page_Templates_Tab extends Thrive_Leads_Tab implements Thrive_Leads_Tab_Interface {
	protected function matchItems() {
		if ( ! $this->getItems() ) {
			return array();
		}

		$optionArr = $this->getSavedOptions()->getTabSavedOptions( 4, $this->hanger );

		foreach ( $this->getItems() as $pageFile => $pageName ) {
			$option = new Thrive_Leads_Option();
			$option->setLabel( $pageName );
			$option->setId( basename( $pageFile ) );
			$option->setIsChecked( in_array( basename( $pageFile ), $optionArr ) );
			$this->options[] = $option;
		}
	}

	protected function getSavedOption( $item ) {
		return $this->getSavedOptionForTab( 4, $item );
	}

	/**
	 * @return $this
	 */
	protected function initItems() {
		$templates             = wp_get_theme()->get_page_templates();
		$templates['page.php'] = __( 'Default', 'thrive-leads' );
		$this->setItems( $templates );

		return $this;
	}

	/**
	 * @param $template string
	 *
	 * @return bool
	 */
	public function displayWidget( $template ) {
		$templateLabel = $this->getTemplateLabel( $template );

		$this->hanger = 'show_group_options';
		$showOption   = $this->getSavedOption( $templateLabel );
		$display      = $showOption->isChecked;

		if ( $display === true ) {
			$this->hanger  = 'hide_group_options';
			$templateLabel = $this->getTemplateLabel( $template );
			$display       = ! $this->getSavedOption( $templateLabel )->isChecked;
		}

		return $display;

	}

	public function isTemplateDenied( $template ) {
		$this->hanger = 'hide_group_options';

		return $this->getSavedOption( $template )->isChecked;
	}

	public function isTemplateAllowed( $template ) {
		$this->hanger = 'show_group_options';

		return $this->getSavedOption( $template )->isChecked;
	}

}
