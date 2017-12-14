<?php

/**
 * Class Action Model
 */
class Thrive_Leads_Action {
	/**
	 * @var string css class
	 */
	public $cssClass;

	/**
	 * @var string html element id
	 */
	public $identifier;

	/**
	 * @var string html text
	 */
	public $label;

	/**
	 * @param string $cssClass
	 * @param string $identifier
	 * @param string $label
	 */
	public function __construct( $cssClass = '', $identifier = '', $label = '' ) {
		$this->cssClass   = $cssClass;
		$this->identifier = $identifier;
		$this->label      = $label;
	}

	/**
	 * @param mixed $cssClass
	 */
	public function setCssClass( $cssClass ) {
		$this->cssClass = $cssClass;
	}

	/**
	 * @return mixed
	 */
	public function getCssClass() {
		return $this->cssClass;
	}

	/**
	 * @param mixed $identifier
	 */
	public function setIdentifier( $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @param mixed $label
	 */
	public function setLabel( $label ) {
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function getLabel() {
		return $this->label;
	}

}
