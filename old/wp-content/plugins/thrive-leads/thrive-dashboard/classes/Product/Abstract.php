<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 12/9/2015
 * Time: 12:15 PM
 */
abstract class TVE_Dash_Product_Abstract {
	protected $tag;

	protected $logoUrl;

	protected $title;

	protected $description;

	protected $productIds;

	protected $type;

	protected $activated = false;

	protected $moreLinks = array();

	protected $button = array();

	protected $required_data = array(
		'tag',
		'logoUrl',
		'title',
		'description',
		'productIds',
		'type',
		'activated',
	);

	public function __construct( $data = array() ) {
		foreach ( $data as $key => $value ) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Check each each required data if it's empty
	 * @throws Exception
	 */
	final public function validate() {
		foreach ( $this->required_data as $data ) {
			if ( $data === 'productIds' && ! is_array( $this->productIds ) ) {
				throw new Exception( "{$data} is not array" );
			}
			if ( is_null( $this->{$data} ) ) {
				throw new Exception( "Field {$data} is empty" );
			}
		}
	}

	public function render() {
		try {
			$this->validate();
		} catch ( Exception $exception ) {
			require TVE_DASH_PATH . '/templates/product/error.phtml';

			return;
		}

		if ( $this->isActivated() ) {
			require TVE_DASH_PATH . '/templates/product/activated.phtml';

			return;
		}

		require TVE_DASH_PATH . '/templates/product/inactive.phtml';
	}

	public function isActivated() {
		if ( $this->activated === true ) {
			return true;
		}

		return TVE_Dash_Product_LicenseManager::getInstance()->itemActivated( $this );
	}

	public function renderButton() {
		return sprintf( '<a class="%s" href="%s" target="%s">%s</a>',
			"tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn" . ( $this->button['active'] ? '' : 'tvd-disabled' ) . ' ' . ( ! empty( $this->button['classes'] ) ? $this->button['classes'] : '' ),
			$this->button['active'] && ! empty( $this->button['url'] ) ? $this->button['url'] : 'javascript:void(0)',
			! empty( $this->button['target'] ) ? $this->button['target'] : '_self',
			$this->button['label']
		);
	}

	public function renderMoreLinks() {
		if ( empty( $this->moreLinks ) ) {
			return;
		}

		if ( $this->isActivated() ) {
			$links = '<a class="tvd-dropdown-button tvd-btn-floating tvd-right tvd-card-options" data-constrainwidth="false" data-beloworigin="true" data-alignment="right" data-activates="dropdown-' . $this->tag . '" href="javascript:void(0)"><i class="tvd-icon-more_vert"></i></a>';
			$links .= '<ul id="dropdown-' . $this->tag . '" class="tvd-dropdown-content" style="white-space: nowrap; position: absolute; top: 43px; left: 162px; opacity: 1; display: none;">';
			foreach ( $this->moreLinks as $link ) {
				$icon_class = isset( $link['icon_class'] ) ? $link['icon_class'] : '';
				$class      = isset( $link['class'] ) ? $link['class'] : '';
				$target     = isset( $link['target'] ) ? $link['target'] : '_self';
				$href       = isset( $link['href'] ) ? $link['href'] : '';
				$text       = isset( $link['text'] ) ? $link['text'] : 'Item';
				$links .= "<li><a class='" . $class . "' target='" . $target . "' href='" . $href . "'><i class='" . $icon_class . "'></i>" . $text . "</a></li>";

			}
			$links .= '</ul>';

			return $links;
		}

		return;

	}

	public function getTag() {
		return $this->tag;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setMoreLinks( $links ) {
		$this->moreLinks = $links;
	}
}
