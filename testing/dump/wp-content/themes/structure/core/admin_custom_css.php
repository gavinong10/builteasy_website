<?php
/**
* ============================================================================
* Add CSS to backend
* ============================================================================
*/
function admin_custom_css() {
echo '<style type="text/css">
	.hide-if-no-customize{
		display: none;
	}
	@-ms-keyframes spin {
		from {
			-ms-transform: rotate(0deg);
		}
		to {
			-ms-transform: rotate(360deg);
		}
	}
	@-moz-keyframes spin {
		from {
			-moz-transform: rotate(0deg);
		}
		to {
			-moz-transform: rotate(360deg);
		}
	}
	@-webkit-keyframes spin {
		from {
			-webkit-transform: rotate(0deg);
		}
		to {
			-webkit-transform: rotate(360deg);
		}
	}
	@keyframes spin {
		from {
			transform: rotate(0deg);
		}
		to {
			transform: rotate(360deg);
		}
	}
	.toplevel_page_customize div.wp-menu-image:before{
		-webkit-transition: all 0.2s ease-out;
		-moz-transition: all 0.2s ease-out;
		-o-transition: all 0.2s ease-out;
		transition: all 0.2s ease-out;
		-webkit-animation:spin 4s linear infinite;
		-moz-animation:spin 4s linear infinite;
		animation:spin 4s linear infinite;
	}
	.vc_license-activation-notice{
	  display:none !important
	}
</style>';
}
add_action('admin_head', 'admin_custom_css');