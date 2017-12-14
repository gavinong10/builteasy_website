<?php

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class Thrive_Dash_Api_Postmark_Exception extends Exception {
	var $message;
	var $httpStatusCode;
	var $postmarkApiErrorCode;
}

?>