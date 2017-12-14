<?php
// Template Tags
function seed_ucp_title() {
	$o = seed_ucp_get_settings();
	extract( $o );

	$output = '';

	$name = get_bloginfo('name');
	if ( !empty( $name ) ) {
		$output = esc_html( $name );
	}
	return $output;
}



function seed_ucp_privacy() {
	$output = '';

	if ( get_option( 'blog_public' ) == 0 ) {
		$output = "<meta name='robots' content='noindex,nofollow' />";
	}

	return $output;
}



function seed_ucp_head() {
	$o = seed_ucp_get_settings();
	extract( $o );

	// CSS
	$output = '';

	$output .= "<!-- Bootstrap and default Style -->\n";
	$output .= '<link rel="stylesheet" href="'.SEED_UCP_PLUGIN_URL.'themes/default/normalize.css">'."\n";
	$output .= '<link rel="stylesheet" href="'.SEED_UCP_PLUGIN_URL.'themes/default/style.css">'."\n";
	if ( is_rtl() ) {
		$output .= '<link rel="stylesheet" href="'.SEED_UCP_PLUGIN_URL.'themes/default/rtl.css">'."\n";
	}
	$output .= '<style type="text/css">'."\n";

	// Calculated Styles

	$output .= '/* calculated styles */'."\n";

	if(!empty($disable_overlay)){
		$output .= 'body{background-color:rgba(0,0,0,0.0);}';
	}
	ob_start();
	?>

	/* Background Style */
    html{
    	height:100%;
		<?php if ( !empty( $bg_image ) ): ;?>
				background: #000 url('<?php echo $bg_image; ?>') no-repeat top center fixed;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
        <?php endif; ?>

    }
    <?php

	$output .= ob_get_clean();

	$output .= '</style>'."\n";



	return $output;
}


function seed_ucp_description() {
	$o = seed_ucp_get_settings();
	extract( $o );

	$output = '';

	if ( !empty( $description ) ) {
		$output .= '<div id="seed-ucp-description">'.shortcode_unautop(wpautop(convert_chars(wptexturize($description)))).'</div>';
	}

	return  $output;
}

function seed_ucp_credit() {
	$o = seed_ucp_get_settings();
	extract( $o );

	$output = '';

	if ( !empty( $footer_credit ) ) {
		$output = '<div id="seed-ucp-credit">';
		$output .= '<a target="_blank" href="http://www.seedprod.com/?utm_source=under-construction-credit-link&utm_medium=banner&utm_campaign=under-construction-plugin-credit-link"><img src="'.plugins_url('under-construction-wp',dirname('.')).'/themes/default/images/seedprod-credit.png"></a>';
		$output .= '</div>';
	}

	return  $output;
}