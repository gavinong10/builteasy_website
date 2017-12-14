<?php

/**
* Displays the comment checkbox, call this function if your theme does not use the 'comment_form' action in the comments.php template.
*/
if(!function_exists('nsu_checkbox')) {
	function nsu_checkbox() {
	    NSU::checkbox()->output_checkbox();
	}
}

/**
* Outputs a sign-up form, for usage in your theme files.
*/
if(!function_exists('nsu_form')) {
	function nsu_form() {
		NSU::form()->output_form(true);
	}
}

/* Backwards Compatibility */
if(!function_exists('nsu_signup_form')) {
	function nsu_signup_form()
	{
		nsu_form();
	}
}