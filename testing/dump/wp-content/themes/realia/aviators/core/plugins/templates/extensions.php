<?php

class TwigProxy {
	public function __call( $function, $arguments ) {
		if ( ! function_exists( $function ) ) {
			trigger_error( 'call to unexisting function ' . $function, E_USER_ERROR );
			return NULL;
		}
		return call_user_func_array( $function, $arguments );
	}
}


class HTMLDecodeTwigExtension extends Twig_Extension {
	public function getFilters() {

		return array(
			'htmldecode' => new Twig_Filter_Method( $this, 'htmldecode', array(
					'is_safe' => array( 'html' ) )
			),
		);
	}

	public function htmldecode( $string ) {
		return html_entity_decode( $string );
	}

	public function getName() {
		return 'html_decode_twig_extension';
	}
}