<?php
backupbuddy_core::verifyAjaxAccess();


/* email_error_test()
 *
 * Test sending emails on the Settings page. Tries sending email and dies with "1" on success, else error message string echo'd out and dies.
 *
 */

$email = pb_backupbuddy::_POST( 'email' );
if ( $email == '' ) {
	die( 'You must supply an Error email address to send test message to.' );
}
backupbuddy_core::mail_error( 'THIS IS ONLY A TEST. This is a test of the Error Notification email.', $email );
die('1');
