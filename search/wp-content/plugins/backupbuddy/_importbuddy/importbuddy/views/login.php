<script>jQuery( '#pageTitle' ).html( 'Authentication Required' );</script>
<?php


if ( pb_backupbuddy::_POST( 'password' ) != '' ) {
	global $pb_login_attempts;
	pb_backupbuddy::alert( 'Invalid password. Please enter the password you provided within BackupBuddy Settings. Attempt #' . $pb_login_attempts . '.' );
	echo '<br>';
}
?>

<p>Enter your ImportBuddy password below to begin.</p>

<form method="post">
	<input type="hidden" name="action" value="login">
	<input type="password" name="password" style="width: 250px;">
	<input type="submit" name="submit" value="Authenticate" class="it-button">
</form>
