<?php
// http://ithemes.com
//
class pb_backupbuddy_serverinfo {
	
	/*	get_wp_urls_paths()
	 *	
	 *	Gives an array of common WordPress URLs and file paths.
	 *	
	 *	@return		array			Contains multiple arrays of the format:
	 *									name		=>	''
	 *									title		=>	''
	 *									value		=>	''
	 */
	public static function get_wp_urls() {
		
		$items = array();
		
		
		$wp_upload_dir = wp_upload_dir();
		
		/*
		$items[] = array(
			'title'		=>	',
			'name'		=>	,
			'value'		=>	,
		);
		*/
		
		return $items;
		
	}
	
} // end class.