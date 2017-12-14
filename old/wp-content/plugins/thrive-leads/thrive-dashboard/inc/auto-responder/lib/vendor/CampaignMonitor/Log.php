<?php
defined( 'Thrive_Dash_Api_CampaignMonitor_Log_VERBOSE' ) or define( 'Thrive_Dash_Api_CampaignMonitor_Log_VERBOSE', 1000 );
defined( 'Thrive_Dash_Api_CampaignMonitor_Log_WARNING' ) or define( 'Thrive_Dash_Api_CampaignMonitor_Log_WARNING', 500 );
defined( 'Thrive_Dash_Api_CampaignMonitor_Log_ERROR' ) or define( 'Thrive_Dash_Api_CampaignMonitor_Log_ERROR', 250 );
defined( 'Thrive_Dash_Api_CampaignMonitor_Log_NONE' ) or define( 'Thrive_Dash_Api_CampaignMonitor_Log_NONE', 0 );

class Thrive_Dash_Api_CampaignMonitor_Log {
	var $_level;

	function __construct( $level ) {
		$this->_level = $level;
	}

	function log_message( $message, $module, $level ) {
		if ( $this->_level >= $level ) {
			echo date( 'G:i:s' ) . ' - ' . $module . ': ' . $message . "<br />\n";
		}
	}
}