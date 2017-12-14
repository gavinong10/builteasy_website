<?php 

class Property{

	function controller(){
		global $wpdb;		
		
		if(isset($_GET['page'])){

			switch ($_GET['page']){
				case "pdfs" :
					include_once ( dirname (__FILE__) . '/pdfs.php' );		// admin functions
					break;

				case "add_pdfs" :
					include_once ( dirname (__FILE__) . '/add_pdfs.php' );		// admin functions
					break;

				case "edit_pdfs" :
					include_once ( dirname (__FILE__) . '/edit_pdfs.php' );		// admin functions
					break;		

			}

		}		

	}

}

?>