<?php
class dsSearchAgent_Roles {
	static $Role_Name = "dsidxpress_visitor";
	static $Role_ViewDetails = "dsidxpress_view_details";
	static function Init() {
		$options = get_option(DSIDXPRESS_OPTION_NAME);
		
		if(!empty($options["DetailsRequiresRegistration"]) && $options["DetailsRequiresRegistration"]){
			$visitor_role = get_role(dsSearchAgent_Roles::$Role_Name);
			
			if(true || $visitor_role == FALSE){
				global $wp_roles;
				$wp_roles->add_role(dsSearchAgent_Roles::$Role_Name, "dsIDXpress Visitor", array());

				$wp_roles->add_cap(dsSearchAgent_Roles::$Role_Name, dsSearchAgent_Roles::$Role_ViewDetails);
				$wp_roles->add_cap(dsSearchAgent_Roles::$Role_Name, 'read');

				$wp_roles->add_cap('administrator', dsSearchAgent_Roles::$Role_ViewDetails);
				$wp_roles->add_cap('editor', dsSearchAgent_Roles::$Role_ViewDetails);
				$wp_roles->add_cap('author', dsSearchAgent_Roles::$Role_ViewDetails);
				$wp_roles->add_cap('contributor', dsSearchAgent_Roles::$Role_ViewDetails);
				$wp_roles->add_cap('subscriber', dsSearchAgent_Roles::$Role_ViewDetails);
			}
			
			add_action("user_register", array("dsSearchAgent_Roles", "ProcessNewUser"));
		}
	}
	
	static function ProcessNewUser($user_id){
		if (@$_POST["dsidxpress"] != "1")
			return;
			
		$new_user = new WP_User($user_id);
		$new_user->add_role(dsSearchAgent_Roles::$Role_Name);
		
		$referring_url = $_SERVER['HTTP_REFERER'];
		$post_vars = array();
		$post_vars["propertyID"] = $_POST["propertyID"];
		$post_vars["firstName"] = $_POST["first_name"];
		$post_vars["lastName"] = $_POST["last_name"];
		$post_vars["phoneNumber"] = $_POST["phone_number"];
		$post_vars["emailAddress"] = $_POST["user_email"];
		$post_vars["scheduleYesNo"] = "";
		$post_vars["scheduleDateDay"] = "1";
		$post_vars["scheduleDateMonth"] = "1";
		$post_vars["comments"] = "";
		$post_vars["referringURL"] = $referring_url;
		//$post_vars["returnURL"] = $_POST[""];
		
		$apiHttpResponse = dsSearchAgent_ApiRequest::FetchData("ContactForm", $post_vars, false, 0);
		
		wp_set_auth_cookie( $user_id, true, is_ssl() );
	}
}

dsSearchAgent_Roles::Init();
?>