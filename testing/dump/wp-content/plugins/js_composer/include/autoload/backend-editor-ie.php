<?php

function vc_add_ie9_degradation() {
	echo '<!--[if lte IE 9]><link rel="stylesheet" type="text/css" href="' . vc_asset_url( 'css/vc_lte_ie9.css' ) . '" media="screen"><![endif]-->';
}

add_action( 'vc_backend_editor_footer_render', 'vc_add_ie9_degradation' );
