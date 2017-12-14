<?php

class WPBakeryShortCode_Vc_Line_Chart extends WPBakeryShortCode {
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->jsScripts();
	}

	public function jsScripts() {
		wp_register_script( 'ChartJS', vc_asset_url( 'lib/bower/chartjs/Chart.min.js' ), WPB_VC_VERSION, true );
		wp_register_script( 'vc_line_chart', vc_asset_url( 'lib/vc_line_chart/vc_line_chart.js' ), array(
			'jquery',
			'ChartJS'
		), WPB_VC_VERSION, true );
	}
}