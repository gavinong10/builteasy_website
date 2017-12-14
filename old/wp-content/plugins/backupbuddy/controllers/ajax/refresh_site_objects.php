<?php	
backupbuddy_core::verifyAjaxAccess();


// Server info page site objects file count update.

/*	refresh_site_objects()
*	
*	Server info page site objects file count refresh. Echos out the new site file count (pretty version).
*	
*	@return		null
*/


$site_size = backupbuddy_core::get_site_size(); // array( site_size, site_size_sans_exclusions ).

echo $site_size[2];

die();
