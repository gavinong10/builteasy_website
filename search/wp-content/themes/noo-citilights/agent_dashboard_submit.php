<?php
/*
Template Name: Agent Dashboard Submit
*/

NooAgent::check_logged_in_user();

set_time_limit (600);

global $current_user;
get_currentuserinfo();
$user_id  = $current_user->ID;
$agent_id = intval( get_user_meta($user_id, '_associated_agent_id', true ) );
$prop_id  = '';
// Membership information
$membership_info		= NooAgent::get_membership_info( $agent_id );
$membership_type		= $membership_info['type'];
$admin_approve			= get_option('noo_admin_approve', 'add');

$has_err            = false;
$err_message        = array();
$success            = false;

// Default Value
$post_status = 'publish';

// Description & Price
$title              = '';
$desc               = '';
$price              = '';
$price_label        = '';
$area               = '';
$status             = '';
$type               = '';
$bedrooms           = '';
$bathrooms          = '';

// Featured image & Gallery
$featured_img		= '';
$gallery			= '';

// Additional info
$custom_fields = NooProperty::get_custom_field_option('custom_field');
$fields = array();
$fields_checklist = array();
$fields_prefix = 'noo_property_field';
if($custom_fields){
	foreach ($custom_fields as $custom_field){
		$key = sanitize_title(@$custom_field['name']);
		$field_id = '_'.$fields_prefix.'_'.$key;
		$field_name = $fields_prefix.'['.$key.']';
		$fields[$key] = array(
			'label' => ( isset($custom_field['label_translated']) && !empty($custom_field['label_translated']) ) ? $custom_field['label_translated'] : @$custom_field['label'] ,
			'id' => $field_id,
			'name' => $field_name,
			'value' => ''
		);
		$fields_checklist[] = $key;
	} 
}

// Location
$address            = '';
$location           = '';
$sub_location       = '';
$lat                = NooProperty::get_google_map_option('latitude','40.714398');
$long               = NooProperty::get_google_map_option('longitude','-74.005279');

// Featured
$featured           = false;

// Video
$video				= '';

// Amenities & Features
$custom_features = NooProperty::get_custom_features();
$features = array();
$features_checklist = array();
$features_prefix = 'noo_property_feature';
if($custom_features){
	foreach ($custom_features as $key => $feature){
		$features[$key] = array(
			'label' =>ucfirst($feature),
			'id' => '_'.$features_prefix.'_'.$key,
			'name' => $features_prefix.'['.$key.']',
			'value' => 'no'
		);
		$features_checklist[] = $key;
	}
}

$action = 'add';
$submit_title = __('Submit Property', 'noo');
$submit_text = __('Add Property', 'noo');

// Property editing, get value from database
if( isset( $_GET['prop_edit'] ) && is_numeric( $_GET['prop_edit'] ) ) {
	$prop_id  =  intval ($_GET['prop_edit']);
	if( !NooAgent::can_edit( $agent_id ) || !NooAgent::is_owner( $agent_id, $prop_id ) ) {
		exit('You don\'t have the rights to edit this property');
	}

	$the_property = get_post( $prop_id);

	$post_status = $the_property->post_status;

	// Description & Price
	$title              = get_the_title($prop_id); 
	$desc               = get_post_field('post_content', $prop_id);
	$price              = intval( noo_get_post_meta($prop_id,'_price','') );
	$price_label        = esc_html( noo_get_post_meta($prop_id,'_price_label','') ); 
	$area               = intval( noo_get_post_meta($prop_id,'_area','') ); 
	$bedrooms           = intval( noo_get_post_meta($prop_id,'_bedrooms','') ); 
	$bathrooms          = intval( noo_get_post_meta($prop_id,'_bathrooms','') ); 

	$status_array       = get_the_terms($prop_id, 'property_status');
	if(isset($status_array[0])) {
		$status         = esc_html( $status_array[0]->slug );
	}
	$type_array         = get_the_terms($prop_id, 'property_category');
	if(isset($type_array[0])) {
		$type           = esc_html( $type_array[0]->slug );
	}

	// Featured Image and Gallery
	$featured_img		= get_post_thumbnail_id($prop_id);
	$gallery			= esc_attr( noo_get_post_meta($prop_id,'_gallery','') );

	// Additional info
	if($fields){
		foreach ($fields as $index => $field){
			$fields[$index]['value'] = esc_html( noo_get_post_meta($prop_id,$field['id'],$field['value']) );
		} 
	}

	// Location
	$address            = esc_html( noo_get_post_meta($prop_id,'_address','') );
	$location_array     = get_the_terms($prop_id, 'property_location');
	if(isset($location_array[0])) {
		$location       = esc_html( $location_array[0]->slug );
	}
	$sub_location_array = get_the_terms($prop_id, 'property_sub_location');
	if(isset($sub_location_array[0])) {
		$sub_location   = esc_html( $sub_location_array[0]->slug );
	}
	$lat                = esc_html( noo_get_post_meta($prop_id,'_noo_property_gmap_latitude',$lat) );
	$long               = esc_html( noo_get_post_meta($prop_id,'_noo_property_gmap_longitude',$long) );

	$featured			= esc_attr( noo_get_post_meta($prop_id,'_featured','') ) == 'yes';

	// Video
	$video				= esc_url( noo_get_post_meta($prop_id,'_video_embedded',''));

	// Features & Amenities
	if($features){
		foreach ($features as $index => $feature){
			$features[$index]['value'] = esc_html( noo_get_post_meta($prop_id,$feature['id'],$feature['value']) );
		} 
	}

	$action = 'edit';
	$submit_title = __('Edit Property', 'noo');
	$submit_text = __('Update Property', 'noo');
}

// Permission for Featured property
$need_approve				= true;
switch( $admin_approve ) {
	case 'add':
		$need_approve		= ( $action == 'add' );
		break;
	case 'none':
		$need_approve		= false;
		break;
	default:
		$need_approve		= true;
		break;
}

if( $need_approve ) {
	$post_status = 'pending';
}

$featured_permision	= array(
		'allow'		=> false,
		'message'	=> ''
	);
if( $membership_type == 'membership' ) {
	if( $membership_info['data']['featured_remain'] == 0 ) {
		$featured_permision['message'] = __('Please upgrade your membership before you can make this listing featured.', 'noo');
	} else {
		if( $post_status == 'publish' ) {
			$featured_permision['allow'] = true;
			$featured_permision['message'] = __('Make this listing featured. The number of featured items will be subtracted from your package.', 'noo');
		} else {
			$featured_permision['message'] = __('You can make this listing featured after your property is approved.', 'noo');
		}
	}
} elseif( $membership_type == 'submission' ) {
	$featured_permision['message'] = __('Make this listing featured from Your Properties list.', 'noo');
}

// Submit handler
// ===============================
if( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

	// Check nonce
	if ( !isset($_POST['_noo_property_nonce']) || !wp_verify_nonce($_POST['_noo_property_nonce'],'submit_property') ){
		exit(__('Sorry, your session is expired or you submitted an invalid property form.', 'noo'));
	}

	// Agent checking
	$submit_agent_id	= intval( $_POST['agent_id'] );
	if( empty( $agent_id ) && empty( $submit_agent_id ) ) {
		$agent_id = NooAgent::create_agent_from_user( $user_id );
		if( !$agent_id ) {
			$has_err = true;
			$err_message[] = __('There\'s an unknown error when creating an agent profile for your account. Please resubmit your property or contact Administrator.', 'noo');
		}
	} elseif( $agent_id != $submit_agent_id ) {
		$has_err = true;
		$err_message[] = __('There\'s an unknown error. Please resubmit your property or contact Administrator.', 'noo');
	}

	if( !$has_err ) {
		// variable
		$no_html			= array();
		$allowed_html		= array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'title' => array(),
				'rel' => array(),
			),
			'img' => array(
				'src' => array()
			),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'p' => array(),
			'br' => array(),
			'hr' => array(),
			'span' => array(),
			'em' => array(),
			'strong' => array(),
			'small' => array(),
			'b' => array(),
			'i' => array(),
			'u' => array(),
			'ul' => array(),
			'ol' => array(),
			'li' => array(),
			'blockquote' => array(),
		);
	
		// Submit data
		$title			= wp_kses( $_POST['title'], $no_html );
		$desc			= wp_kses( $_POST['desc'], $allowed_html );
		$price			= wp_kses( $_POST['price'], $no_html );
		$price_label	= wp_kses( $_POST['price_label'], $no_html );
		$area			= wp_kses( $_POST['area'], $no_html );
	
		if( !isset($_POST['status']) ) {
			$status			= '';
		} else {
			$status			= wp_kses( $_POST['status'], $no_html );
		}
		if( !isset($_POST['type']) ) {
			$type			= '';
		} else {
			$type			= wp_kses( $_POST['type'], $no_html );
		}

		// Featured Image and Gallery
		$featured_img		= wp_kses( $_POST['featured_img'], $no_html );
		$gallery			= wp_kses( $_POST['gallery'], $no_html );

		$bedrooms			= intval( $_POST['bedrooms'] );
		if( !$bedrooms ) $bedrooms = '';
		$bathrooms			= intval( $_POST['bathrooms'] );
		if( !$bathrooms ) $bathrooms = '';
	
		$submit_fields		= array();
		if( isset( $_POST[$fields_prefix] ) && is_array( $_POST[$fields_prefix] ) ) {
			foreach ($_POST[$fields_prefix] as $key => $field) {
				if( in_array($key, $fields_checklist)) {
					$submit_fields[$key] = wp_kses( $field, $no_html );
					$fields[$key]['value'] = $submit_fields[$key];
				}
			}
		}
	
		$address			= wp_kses( $_POST['address'], $no_html );
		if( !isset($_POST['location']) ) {
			$location		= '';
		} else {
			$location		= wp_kses( $_POST['location'], $no_html );
		}
		if( !isset($_POST['sub_location']) ) {
			$sub_location	= '';
		} else {
			$sub_location	= wp_kses( $_POST['sub_location'], $no_html );
		}

		$lat				= wp_kses( $_POST['lat'], $no_html );
		$long				= wp_kses( $_POST['long'], $no_html );

		// Video
		$video				= wp_kses( $_POST['video'], $no_html );

		$submit_features	= array();
		if( isset( $_POST[$features_prefix] ) && is_array( $_POST[$features_prefix] ) ) {
			foreach ($_POST[$features_prefix] as $key => $feature) {
				if( in_array($key, $features_checklist)) {
					$submit_features[$key] = wp_kses( $feature, $no_html );
					$features[$key]['value'] = $submit_features[$key];
				}
			}
		}

		// Error data checking
		if( empty($title) ) {
			$has_err = true;
			$err_message[] = __('Please submit a title for your property', 'noo');
		}

		if( empty($desc) ) {
			$has_err = true;
			$err_message[] = __('Please input a description for your property', 'noo');
		}

		if( empty($gallery) ) {
			$has_err = true;
			$err_message[] = __('Your property needs at least one image', 'noo');
		}

		if( empty($address) ) {
			$has_err = true;
			$err_message[] = __('Your property needs a specific address', 'noo');
		}
	}

	if( ! $has_err ) {
		$post = array(
			'post_title'	=> $title,
			'post_content'	=> $desc,
			'post_status'	=> $post_status, 
			'post_type'		=> 'noo_property'
		);

		if( $_POST['action'] == 'add' ) {
			if( !NooAgent::can_add( $agent_id ) ) {
				exit('Sorry, you don\'t have the permission to submit any property!');
			}

			$post_id = wp_insert_post( $post );
			if( !$post_id ) {
				$has_err = true;
				$err_message[] = __('There\'s an unknown error when inserting your property to database. Please resubmit your property or contact Administrator.', 'noo');
			} else {
				$success = true;
				update_post_meta( $post_id, '_agent_responsible', $agent_id );
				if( NooMembership::is_submission() ) {
					update_post_meta( $post_id, '_paid_listing', '' );
				}

				// Membership action
				NooAgent::decrease_listing_remain( $agent_id );

				// Email
				$admin_email = get_option('admin_email');
				$site_name = get_option('blogname');
				$property_admin_link = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';
				
				if( $need_approve ) {
					$message .= sprintf( __("A user has just submitted a listing on %s and it's now waiting for your approval. To approve or reject it, please follow this link: %s", 'noo'), $site_name, $property_admin_link) . "<br/><br/>";
					noo_mail($admin_email,
						sprintf(__('[%s] New submission needs approval','noo'), $site_name),
						$message);
				} else {
					$message .= sprintf( __("A user has just submitted a listing on %s. You can check it at %s", 'noo'), $site_name, $property_admin_link) . "<br/><br/>";
					noo_mail($admin_email,
						sprintf(__('[%s] New property submission','noo'), $site_name),
						$message);
				}
			}
		} elseif( $_POST['action'] == 'edit' ) {
			$post_id = intval( $_POST['prop_id'] );
			if( !NooAgent::can_edit( $agent_id ) || !NooAgent::is_owner( $agent_id, $post_id ) ) {
				exit('You don\'t have the rights to edit this property');
			}

			if( !empty( $post_id ) ) {
				$post['ID'] = $post_id;

				if( 0 === wp_update_post( $post ) ) {
					$has_err = true;
					$err_message[] = __('There\'s an unknown error when updating your property. Please resubmit your property or contact Administrator.', 'noo');
				} else {
					$success = true;

					// Email
					$admin_email = get_option('admin_email');
					$site_name = get_option('blogname');
					$property_admin_link = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';

					if( $need_approve ) {
						$message .= sprintf( __("A user has just edited one of his listings and it's now waiting for your approval. To approve or reject it, please follow this link: %s", 'noo'), $property_admin_link) . "<br/><br/>";
						noo_mail($admin_email,
							sprintf(__('[%s] New submission needs approval','noo'), $site_name),
							$message);
					} else {
						$message .= sprintf( __("A user has just edited one of his listings. You can check it at %s", 'noo'), $property_admin_link) . "<br/><br/>";
						noo_mail($admin_email,
							sprintf(__('[%s] A listing has been edited','noo'), $site_name),
							$message);
					}
				}
			}
		}

		// Update property meta when insert/update succeeded
		if( $success ) {
			update_post_meta( $post_id, '_price', $price );
			update_post_meta( $post_id, '_price_label', $price_label );
			update_post_meta( $post_id, '_area', $area );

			if( !empty($status) ) {
				wp_set_object_terms($post_id, $status,'property_status'); 
			}
			if( !empty($type) ) {
				wp_set_object_terms($post_id, $type,'property_category'); 
			}
			update_post_meta( $post_id, '_bedrooms', $bedrooms );
			update_post_meta( $post_id, '_bathrooms', $bathrooms );

			$featured_img		= trim( wp_kses( $_POST['featured_img'], $no_html ) );
			$gallery_arr		= explode(',', trim($gallery) );
			$featured_img_index = 0;
			if( empty($featured_img) ) {
				$featured_img = $gallery_arr[0];
			}

			foreach ($gallery_arr as $index => $gallery_item) {
				$gallery_arr[$index] = trim($gallery_arr[$index]);

				if( is_numeric( $gallery_arr[$index] ) ) {
					wp_update_post( array(
						'ID' => $gallery_item,
						'post_parent' => $post_id
					));
				}

				if( $featured_img == $gallery_item ) {
					$featured_img_index = $index;
				}
			}

			unset( $gallery_arr[$featured_img_index] );
			$gallery = implode(',', $gallery_arr);

			set_post_thumbnail( $post_id, $featured_img );
			update_post_meta( $post_id, '_gallery', $gallery );

			foreach( $submit_fields as $field_key => $submit_field ) {
				update_post_meta( $post_id, "_{$fields_prefix}_{$field_key}", $submit_field );
			}

			update_post_meta( $post_id, '_address', $address );
			if( !empty($location) ) {
				wp_set_object_terms($post_id, $location,'property_location'); 
			}
			if( !empty($sub_location) ) {
				wp_set_object_terms($post_id, $sub_location,'property_sub_location'); 
			}
			update_post_meta( $post_id, '_noo_property_gmap_latitude', $lat );
			update_post_meta( $post_id, '_noo_property_gmap_longitude', $long );

			foreach( $submit_features as $feature_key => $submit_feature ) {
				update_post_meta( $post_id, "_{$features_prefix}_{$feature_key}", $submit_feature );
			}

			// Featured property
			// Only update if change from no featured to featured
			if( !$featured && $featured_permision['allow'] ) {
				$submit_featured = isset( $_POST['featured'] ) ? (bool) wp_kses( $_POST['featured'], $no_html ) : $featured;
				
				if( $submit_featured ) {
					update_post_meta( $post_id, '_featured', 'yes' );
					NooAgent::decrease_featured_remain( $agent_id );
				}
			}

			update_post_meta( $post_id, '_video_embedded', $video );

			// reset query
			wp_reset_query();

			// redirect to dashboard default
			$redirect = noo_get_page_link_by_template( 'agent_dashboard.php' );
			wp_redirect( $redirect);
		}
	}
}

get_header(); ?>
<div class="container-wrap">
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="noo-sidebar col-md-4">
				<div class="noo-sidebar-wrapper">
				<?php noo_get_layout('agent_menu');  ?>
				</div>
			</div>
			<div class="<?php noo_main_class(); ?>" role="main">   
				<div class="submit-header">
					<h1 class="page-title"><?php echo $submit_title; ?></h1>
				</div>
				<?php if( ( $action == 'add' ) && !NooAgent::can_add( $agent_id ) ) : ?>
				<div class="submit-content">
				<h4><?php if( NooMembership::is_membership() ) {
					_e('Your current package doesn\'t let you publish properties anymore! You will have to upgrade your membership first.', 'noo');
					do_shortcode( '[noo_membership_packages style="ascending" featured_item="2" ]' );
				} else {
					_e('Sorry, you don\'t have the permission to submit any property!', 'noo');
				}
				?></h4>
				</div>
				<?php else : ?>
				<div class="submit-content">
					<?php if( $has_err && !empty($err_message) ) : ?>
						<div class="submit-error">
							<?php foreach ($err_message as $message) : ?>
							<div class="noo-message alert alert-danger alert-dimissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php _e( 'Close', 'noo' ); ?></span></button>
								<?php echo $message; ?>
							</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<form id="new_post" name="new_post" method="post" enctype="multipart/form-data" class="noo-form property-form" role="form">
						<div class="noo-control-group">
							<div class="group-title">
								<?php _e('Property Description & Price', 'noo'); ?>
							</div>
							<div class="group-container row">
								<div class="col-md-8">
									<div class="form-group s-prop-title">
										<label for="title"><?php _e('Title','noo'); ?>&nbsp;*</label>
										<input type="text" id="title" class="form-control" value="<?php echo $title; ?>" name="title" required />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group s-prop-area">
										<label for="area"><?php _e('Area','noo'); ?>&nbsp;(<?php echo NooProperty::get_general_option('area_unit'); ?>)</label>
										<input type="text" id="area" class="form-control" value="<?php echo $area; ?>" name="area" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group s-prop-desc">
										<label for="desc"><?php _e('Description','noo'); ?>&nbsp;*</label>
										<textarea class="form-control" id="desc" name="desc" rows="10" required ><?php echo $desc; ?></textarea>
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group s-prop-price row">
										<div class="price col-md-4">
											<label for="price"><?php _e('Price','noo'); ?>&nbsp;*&nbsp;(<?php echo NooProperty::get_currency_symbol(NooProperty::get_general_option('currency')); ?>)</label>
											<input type="text" id="price" class="form-control" value="<?php echo $price; ?>" name="price" required />
										</div>
										<div class="price_label col-md-8">
											<label for="price_label"><?php _e('After price label (ex: "per month")','noo'); ?></label>
											<input type="text" id="price_label" class="form-control" value="<?php echo $price_label; ?>" name="price_label" />
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group s-prop-status">
										<label><?php _e('Status','noo'); ?></label>
										<div class="dropdown">
							   				<?php 
					   						if(!empty($status) && ($status_term = get_term_by('slug',$status,'property_status'))):
					   						?>
					   						<span class="status-label" data-toggle="dropdown"><?php echo esc_html($status_term->name)?></span>
					   						<?php
					   						else:
					   						?>
					   						<span class="status-label" data-toggle="dropdown"><?php echo ''?></span>
					   						<?php
					   						endif;
					   						?>
							   				<?php
												noo_dropdown_taxonomy(
													'property_status',
													get_option('default_property_status'),
													'ul',
													'li',
													'dropdown-menu',
													'level-0'
												);
											?>
							   				<input type="hidden" id="status_input" name="status" value="<?php echo $status; ?>">
							   			</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group s-prop-type">
										<label><?php _e('Type','noo'); ?></label>
										<div class="dropdown">
							   				<?php 
					   						if(!empty($type) && ($type_term = get_term_by('slug',$type,'property_category'))):
					   						?>
					   						<span class="type-label" data-toggle="dropdown"><?php echo esc_html($type_term->name)?></span>
					   						<?php
					   						else:
					   						?>
					   						<span class="type-label" data-toggle="dropdown"><?php echo ''?></span>
					   						<?php
					   						endif;
					   						?>
							   				<?php 
					   						noo_dropdown_search(array(
						   						'taxonomy'=>'property_category',
						   						'show_option_none'=>'',
												'hide_empty'=>0,
						   						'show_count'=>0,
						   					));
					   						?>
							   				<input type="hidden" id="type_input" name="type" value="<?php echo $type; ?>">
							   			</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group s-prop-bedrooms">
										<label for="bedrooms"><?php _e('Bed Rooms','noo'); ?></label>
										<input type="text" id="bedrooms" class="form-control" value="<?php echo $bedrooms; ?>" name="bedrooms" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group s-prop-bathrooms">
										<label for="bathrooms"><?php _e('Bath Rooms','noo'); ?></label>
										<input type="text" id="bathrooms" class="form-control" value="<?php echo $bathrooms; ?>" name="bathrooms" />
									</div>
								</div>
							</div>
						</div>
						<div class="noo-control-group">
							<div class="group-title">
								<?php _e('Property Images', 'noo'); ?>
							</div>
							<div class="group-container row">
								<div class="col-md-12">
									<div id="upload-container">
										<div id="aaiu-upload-container">
											<?php
											$gallery_value = noo_upload_form( $gallery, $featured_img, true );
											?>
											<input type="hidden" name="gallery" id="gallery" value="<?php echo $gallery_value;?>">
											<input type="hidden" name="featured_img" id="featured_img" value="<?php echo $featured_img;?>">
											<a id="aaiu-uploader" class="btn btn-secondary btn-lg" href="#"><?php _e('Select Images','noo');?></a>
											<p><?php _e('At least 1 image is required for a valid submission. The featured image will be used to dispaly on property listing page.','noo');?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php if(!empty($fields)) : ?>
						<div class="noo-control-group">
							<div class="group-title">
								<?php _e('Additional Info', 'noo'); ?>
							</div>
							<div class="group-container row">
								<?php foreach ($fields as $field) : ?>
								<div class="col-md-4">
									<div class="form-group s-prop-<?php echo $field['id']; ?>">
										<label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
										<input type="text" id="<?php echo $field['id']; ?>" name="<?php echo $field['name']; ?>" class="form-control" value="<?php echo $field['value']; ?>" />
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>
						<div class="noo-control-group">
							<div class="group-title">
								<?php _e('Listing Location', 'noo'); ?>
							</div>
							<div class="group-container row">
								<div class="col-md-8">
									<div class="form-group s-prop-address">
										<label for="address"><?php _e('Address','noo'); ?>&nbsp;*</label>
										<textarea id="address" class="form-control" name="address" rows="4" required ><?php echo $address; ?></textarea>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group s-prop-location">
										<label><?php _e('Location','noo'); ?></label>
										<div class="dropdown">
							   				<?php 
					   						if(!empty($location) && ($location_term = get_term_by('slug',$location,'property_location'))):
					   						?>
					   						<span class="location-label" data-toggle="dropdown"><?php echo esc_html($location_term->name)?></span>
					   						<?php
					   						else:
					   						?>
					   						<span class="location-label" data-toggle="dropdown"><?php _e('None','noo')?></span>
					   						<?php
					   						endif;
					   						?>
							   				<?php 
					   						noo_dropdown_search(array(
						   						'taxonomy'=>'property_location',
						   						'show_option_none'=>__('None','noo'),
												'hide_empty'=>0,
						   						'show_count'=>0,
							   				));?>
							   				<input type="hidden" id="location_input" name="location" value="<?php echo $location; ?>">
							   			</div>
									</div>
									<div class="form-group s-prop-sub_location">
										<label><?php _e('Sub Location','noo'); ?></label>
										<div class="dropdown">
							   				<?php 
					   						if(!empty($sub_location) && ($sub_location_term = get_term_by('slug',$sub_location,'property_sub_location'))):
					   						?>
					   						<span class="sub_location-label" data-toggle="dropdown"><?php echo esc_html($sub_location_term->name)?></span>
					   						<?php
					   						else:
					   						?>
					   						<span class="sub_location-label" data-toggle="dropdown"><?php _e('None','noo')?></span>
					   						<?php
					   						endif;
					   						?>
					   						<?php noo_dropdown_search(array(
						   						'taxonomy'=>'property_sub_location',
						   						'show_option_none'=>__('None','noo'),
						   						'hide_empty'=>0,
						   						'show_count'=>0,
						   					)); ?>
							   				<input type="hidden" id="sub_location_input" name="sub_location" value="<?php echo $sub_location; ?>">
							   			</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group s-prop-lat">
										<label for="_noo_property_gmap_latitude"><?php _e('Latitude (Google Maps)','noo'); ?></label>
										<input type="text" id="_noo_property_gmap_latitude" class="form-control" value="<?php echo $lat; ?>" name="lat" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group s-prop-long">
										<label for="_noo_property_gmap_longitude"><?php _e('Longitude (Google Maps)','noo'); ?></label>
										<input type="text" id="_noo_property_gmap_longitude" class="form-control" value="<?php echo $long; ?>" name="long" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="noo_property_google_map">
										<div id="noo_property_google_map" class="form-group noo_property_google_map" style="height: 300px; margin-top: 25px; overflow: hidden;position: relative;width: 100%;">
										</div>
										<div class="noo_property_google_map_search">
											<input placeholder="<?php echo __('Search your map','noo')?>" type="text" autocomplete="off" id="noo_property_google_map_search_input">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<?php if( $featured || $featured_permision['allow'] || !empty( $featured_permision['message'] ) ) : ?>
								<div class="noo-control-group small-group">
									<div class="group-title">
										<?php _e('Featured Submission', 'noo'); ?>
									</div>
									<div class="group-container row">
										<div class="col-md-12">
											<div class="form-group s-prop-featured">
												<?php if( $featured ) : ?>
												<span class="label label-success"><?php _e('Featured Property', 'noo'); ?></span>
												<?php elseif( $featured_permision['allow'] ) : ?>
												<input type="hidden" value="0" name="featured" />
												<label for="featured" class="checkbox-label">
													<input type="checkbox" id="featured" class="" value="1" name="featured" />&nbsp;<?php echo $featured_permision['message']; ?>
													<i></i>
												</label>
												<?php elseif( !empty( $featured_permision['message'] ) ) : ?>
												<p><?php echo $featured_permision['message']; ?></p>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
								<div class="noo-control-group small-group">
									<div class="group-title">
										<?php _e('Property Video', 'noo'); ?>
									</div>
									<div class="group-container row">
										<div class="col-md-12">
											<div class="form-group s-prop-video">
												<label for="_video_embedded"><?php _e('Video Embedded','noo'); ?></label>
												<input type="text" id="_video_embedded" class="form-control" value="<?php echo $video; ?>" name="video" />
												<small><?php echo sprintf( __('Enter a Youtube, Vimeo, Soundcloud, etc... URL. See supported services at %s', 'noo'), '<a href="http://codex.wordpress.org/Embeds" target="_BLANK">http://codex.wordpress.org/Embeds</a>'); ?>
												</small>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php if(!empty($features)) : ?>
							<div class="col-md-7">
								<div class="noo-control-group small-group">
									<div class="group-title">
										<?php _e('Amenities & Features', 'noo'); ?>
									</div>
									<div class="group-container row">
										<?php foreach ($features as $index => $feature) : ?>
										<div class="col-md-6">
											<div class="form-group s-prop-<?php echo $feature['id']; ?>">
												<input type="hidden" name="<?php echo $feature['name']; ?>" class="" value="0" />
												<label for="<?php echo $feature['id']; ?>" class="checkbox-label">
													<input type="checkbox" id="<?php echo $feature['id']; ?>" name="<?php echo $feature['name']; ?>" class="" value="1" <?php checked($feature['value']); ?> />&nbsp;<?php echo $feature['label']; ?>
													<i></i>
												</label>
											</div>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="noo-submit row">
							<div class="col-md-12">
								<input type="submit" class="btn btn-primary btn-lg" id="property_submit" value="<?php echo $submit_text; ?>" />
								<?php if( $need_approve && $action == 'add') : ?>
								<label><?php _e('Your submission will be reviewed by Administrator before it can be published', 'noo'); ?></label>
								<?php elseif( $need_approve && $action == 'edit') : ?>
								<label><?php _e('Your property will be unpublished for Administrator to review your changes', 'noo'); ?></label>
								<?php endif; ?>
							</div>
						</div>  
						<input type="hidden" name="action" value="<?php echo $action;?>">
						<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>">
						<input type="hidden" name="prop_id" value="<?php echo $prop_id;?>">
						<?php wp_nonce_field('submit_property','_noo_property_nonce'); ?>
					</form>
				</div>
				<?php endif; ?>
			</div> <!-- /.main -->
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->

<?php get_footer(); ?>