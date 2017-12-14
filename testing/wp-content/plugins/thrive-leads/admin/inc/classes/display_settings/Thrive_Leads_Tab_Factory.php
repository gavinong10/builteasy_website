<?php

/**
 * Class Thrive_Leads_Tab_Factory
 * Based on $type a specific tab object is returned
 */
class Thrive_Leads_Tab_Factory {
	public static function build( $type ) {
		$class  = "Thrive_Leads_";
		$chunks = explode( "_", $type );
		foreach ( $chunks as $chunk ) {
			$class .= ucfirst( $chunk ) . "_";
		}
		$class .= "Tab";

		if ( ! class_exists( $class ) ) {
			throw new Exception( "Missing Tab Class : " . $class );
		}

		return new $class;
	}
}
