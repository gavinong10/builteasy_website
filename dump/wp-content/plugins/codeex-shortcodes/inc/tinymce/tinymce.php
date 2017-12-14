<?php

	function typo_add_tinymce() {
	     
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
		 	return;
		}	 
		if ( get_user_option('rich_editing') == 'true' ) {
		 	add_filter( 'mce_external_plugins', 'typo_add_tinymce_plugin' );
		 	add_filter( 'mce_buttons', 'typo_add_tinymce_button' );   
		}	 
	    
	}

	// inlcude the js for tinymce
	function typo_add_tinymce_plugin( $plugin_array ) { 
		$plugin_array['typo_path'] = plugin_dir_url(__FILE__) . 'editor.js';
		return $plugin_array;
	}

	// Add the button key for address via JS
	function typo_add_tinymce_button( $buttons ) {
		array_push( $buttons, "", "typo_shortcodes" );
		return $buttons;
	}

	add_action('init', 'typo_add_tinymce');		
	
?>