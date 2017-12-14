<?php
backupbuddy_core::verifyAjaxAccess();


// Server info page icicle for GUI file listing.
/*	icicle()
*	
*	Builds and returns graphical directory size listing. Echos.
*	
*	@return		null
*/

pb_backupbuddy::set_greedy_script_limits(); // Building the directory tree can take a bit.

$response = backupbuddy_core::build_icicle( ABSPATH, ABSPATH, '', -1 );

echo $response[0];
die();
