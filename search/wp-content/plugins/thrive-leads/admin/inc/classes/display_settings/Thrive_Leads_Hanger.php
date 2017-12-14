<?php

class Thrive_Leads_Hanger {
	public $identifier;
	public $tabs = array();
	protected $group;

	public function __construct( $identifier, $group ) {
		$this->identifier = $identifier;
		$this->group      = $group;
	}

	public function initTabs( Array $identifiers ) {
		foreach ( $identifiers as $identifier => $label ) {
			/**
			 * @var $tab Thrive_Leads_Tab
			 */
			$tab = Thrive_Leads_Tab_Factory::build( $identifier );
			$tab->setGroup( $this->group )
			    ->setIdentifier( $identifier )
			    ->setLabel( $label )
			    ->setHanger( $this->identifier )
			    ->initOptions()
			    ->initFilters();

			$this->tabs[] = $tab;
		}
	}

}
