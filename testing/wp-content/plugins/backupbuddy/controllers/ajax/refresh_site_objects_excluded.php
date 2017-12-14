<?php
backupbuddy_core::verifyAjaxAccess();


// Server info page site objects file count (sans exclusions) update.

/*	refresh_site_objects_excluded()
*	
*	Server info page site objects file count (sans exclusions) refresh. Echos out the new site file count (exclusions applied) (pretty version).
*	
*	@return		null
*/


$site_size = backupbuddy_core::get_site_size(); // array( site_size, site_size_sans_exclusions ).

echo $site_size[3];

die();