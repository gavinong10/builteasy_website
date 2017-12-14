<?php
if ( ! defined( 'PB_IMPORTBUDDY' ) || ( true !== PB_IMPORTBUDDY ) ) {
	die( '<html></html>' );
}
Auth::require_authentication(); // Die if not logged in.

require_once( ABSPATH .'importbuddy/views/_header.php' );
?>
<script>jQuery( '#pageTitle' ).html( 'Server Information' );</script>

<div class="wrap">
	<?php
	global $detected_max_execution_time;
	require_once( 'server_tools.php' );
	?>
</div>

<?php
require_once( ABSPATH .'importbuddy/views/_footer.php' );