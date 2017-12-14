<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 12/9/2015
 * Time: 12:21 PM
 */
class TCB_Product extends TVE_Dash_Product_Abstract {
	protected $tag = 'tcb';

	protected $title = 'Content Builder & Landing Pages';

	protected $productIds = array();

	protected $type = 'plugin';

	public function __construct( $data = array() ) {
		parent::__construct( $data );

		$this->logoUrl = tve_editor_css() . '/images/thrive-content-builder-logo.png';
		$this->logoUrlWhite = tve_editor_css() . '/images/thrive-content-builder-logo-white.png';

		$this->description = __( 'Create beautiful content & conversion optimized landing pages.', 'thrive-cb' );

		$this->button = array(
			'label'   => __( 'View Video Tutorial', 'thrive-cb' ),
			'url'     => '//fast.wistia.net/embed/iframe/1tn4dyj6bo?popover=true',
			'active'  => true,
			'target'  => '_bank',
			'classes' => 'wistia-popover[height=450,playerColor=2bb914,width=800]'
		);

		$this->moreLinks = array(
			'tutorials' => array(
				'class'      => 'tve-leads-tutorials',
				'icon_class' => 'tvd-icon-graduation-cap',
				'href'       => 'https://thrivethemes.com/thrive-knowledge-base/thrive-content-builder-thrive-plugins/',
				'target'     => '_blank',
				'text'       => __( 'Tutorials', 'thrive-cb' ),
			),
			'support'   => array(
				'class'      => 'tve-leads-tutorials',
				'icon_class' => 'tvd-icon-life-bouy',
				'href'       => 'https://thrivethemes.com/forums/forum/plugins/thrive-content-builder/',
				'target'     => '_blank',
				'text'       => __( 'Support', 'thrive-cb' ),
			),
		);
	}

}
