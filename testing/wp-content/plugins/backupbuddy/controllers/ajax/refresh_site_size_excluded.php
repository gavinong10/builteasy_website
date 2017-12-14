<?php
backupbuddy_core::verifyAjaxAccess();


// Server info page site size (sans exclusions) update.

/*	refresh_site_size_excluded()
*	
*	Server info page site size (sans exclusions) refresh. Echos out the new site size (pretty version).
*	
*	@return		null
*/


$site_size = backupbuddy_core::get_site_size(); // array( site_size, site_size_sans_exclusions ).

echo pb_backupbuddy::$format->file_size( $site_size[1] );

die();