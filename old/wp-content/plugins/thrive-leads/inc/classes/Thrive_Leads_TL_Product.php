<?php

/**
 * Created by PhpStorm.
 * User: Aurelian Pop
 * Date: 09-Dec-15
 * Time: 7:06 PM
 */
class TL_Product extends TVE_Dash_Product_Abstract {
	protected $tag = 'tl';

	protected $title = 'Thrive Leads';

	protected $productIds = array();

	protected $type = 'plugin';

	public function __construct( $data = array() ) {
		parent::__construct( $data );

		$this->logoUrl = TVE_LEADS_ADMIN_URL . 'img/thrive-leads-logo.png';
		$this->logoUrlWhite = TVE_LEADS_ADMIN_URL . 'img/thrive-leads-logo-white.png';

		$this->description = __( 'Create and manage opt-in forms, keep track of your email list building and more.', 'thrive-leads' );

		$this->button = array(
			'active' => true,
			'url'    => admin_url( 'admin.php?page=thrive_leads_dashboard' ),
			'label'  => __( 'Thrive Leads Dashboard', 'thrive-leads' )
		);

		$this->moreLinks = array(
			'reporting' => array(
				'class'      => 'tve-leads-reporting',
				'icon_class' => 'tvd-icon-line-chart',
				'href'       => admin_url( 'admin.php?page=thrive_leads_reporting' ),
				'text'       => __( 'Reporting', 'thrive-leads' ),
			),
			'asset'     => array(
				'class'      => 'tve-leads-asset',
				'icon_class' => 'tvd-icon-cloud-download',
				'href'       => admin_url( 'admin.php?page=thrive_leads_asset_delivery' ),
				'text'       => __( 'Asset Delivery', 'thrive-leads' ),
			),
			'export'    => array(
				'class'      => 'tve-leads-export',
				'icon_class' => 'tvd-icon-group',
				'href'       => admin_url( 'admin.php?page=thrive_leads_contacts' ),
				'text'       => __( 'Lead Export', 'thrive-leads' ),
			),
			'tutorials' => array(
				'class'      => 'tve-leads-tutorials',
				'icon_class' => 'tvd-icon-graduation-cap',
				'href'       => 'https://thrivethemes.com/thrive-knowledge-base/thrive-leads/',
				'target'     => '_blank',
				'text'       => __( 'Tutorials', 'thrive-leads' ),
			),
			'support'   => array(
				'class'      => 'tve-leads-tutorials',
				'icon_class' => 'tvd-icon-life-bouy',
				'href'       => 'https://thrivethemes.com/forums/forum/plugins/thrive-leads/',
				'target'     => '_blank',
				'text'       => __( 'Support', 'thrive-leads' ),
			),
		);
	}

}