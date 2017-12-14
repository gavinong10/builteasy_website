<?php
/*
Template Name: Agent Dashboard Profile
*/

NooAgent::check_logged_in_user();

global $current_user;
get_currentuserinfo();
$user_id  = $current_user->ID;
$agent_id = get_user_meta($user_id, '_associated_agent_id', true );
if (defined('ICL_LANGUAGE_CODE')) { // this is to not break code in case WPML is turned off, etc.
    $agent_id = icl_object_id($agent_id, 'noo_agent', true, ICL_LANGUAGE_CODE);
}

$has_err            = false;
$err_message        = array();
$success            = false;

$agent_prefix = '_noo_agent';

// Default Value
// Basic Information
$title		= '';
$position	= '';
$email		= '';
$phone		= '';
$mobile		= '';
$skype		= '';
$desc		= '';
$avatar		= '';

// Social
$facebook		= '';
$twitter		= '';
$google_plus	= '';
$linkedin		= '';
$pinterest		= '';
$website		= '';
$address		= '';
$agent			= empty( $agent_id ) ? '' : get_post($agent_id);

if( empty( $agent ) ) {
	$title		= $current_user->first_name . ' ' . $current_user->last_name;
	$title		= ( trim($title) == '' ) ? $current_user->user_login : $title;
	$email		= $current_user->user_email;
	$desc		= get_user_meta($user_id, 'description', true);
} else {
	$title				= $agent->post_title;
	$position			= noo_get_post_meta( $agent_id, "{$agent_prefix}_position", '' );
	$email				= noo_get_post_meta( $agent_id, "{$agent_prefix}_email", '' );
	$phone				= noo_get_post_meta( $agent_id, "{$agent_prefix}_phone", '' );
	$mobile				= noo_get_post_meta( $agent_id, "{$agent_prefix}_mobile", '' );
	$skype				= noo_get_post_meta( $agent_id, "{$agent_prefix}_skype", '' );
	$address			= noo_get_post_meta( $agent_id, "{$agent_prefix}_address", '' );
	$website			= noo_get_post_meta( $agent_id, "{$agent_prefix}_website", '' );
	$desc				= $agent->post_content;
	$avatar				= get_post_thumbnail_id($agent_id);

	$facebook			= noo_get_post_meta( $agent_id, "{$agent_prefix}_facebook", '' );
	$twitter			= noo_get_post_meta( $agent_id, "{$agent_prefix}_twitter", '' );
	$google_plus		= noo_get_post_meta( $agent_id, "{$agent_prefix}_google_plus", '' );
	$linkedin			= noo_get_post_meta( $agent_id, "{$agent_prefix}_linkedin", '' );
	$pinterest			= noo_get_post_meta( $agent_id, "{$agent_prefix}_pinterest", '' );

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
					<h1 class="page-title"><?php _e('My Profile', 'noo'); ?></h1>
				</div>
				<div class="submit-content">
					<form id="profile_form" name="profile_form" class="noo-form profile-form" role="form">
						<div class="noo-control-group">
							<div class="group-title">
								<?php echo sprintf( __('Welcome back, %s', 'noo'), $title ); ?>
							</div>
							<div class="group-container row">
								<div class="form-message">
								</div>
								<div class="col-md-12">
									<div class="form-group s-profile-title">
										<label for="title"><?php _e('Name','noo'); ?>&nbsp;*</label>
										<input type="text" id="title" class="form-control" value="<?php echo $title; ?>" name="title" required />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group s-profile-position">
										<label for="position"><?php _e('Title/Position','noo'); ?></label>
										<input type="text" id="position" class="form-control" value="<?php echo $position; ?>" name="position" />
									</div>
									<div class="form-group s-profile-email">
										<label for="email"><?php _e('Email','noo'); ?>&nbsp;*</label>
										<input type="text" id="email" class="form-control" value="<?php echo $email; ?>" name="email" required />
									</div>
									<div class="form-group s-profile-phone">
										<label for="phone"><?php _e('Phone','noo'); ?></label>
										<input type="text" id="phone" class="form-control" value="<?php echo $phone; ?>" name="phone" />
									</div>
									<div class="form-group s-profile-mobile">
										<label for="mobile"><?php _e('Mobile','noo'); ?></label>
										<input type="text" id="mobile" class="form-control" value="<?php echo $mobile; ?>" name="mobile" />
									</div>
									<div class="form-group s-profile-skype">
										<label for="skype"><?php _e('Skype','noo'); ?></label>
										<input type="text" id="skype" class="form-control" value="<?php echo $skype; ?>" name="skype" />
									</div>
									<div class="form-group s-profile-website">
										<label for="skype"><?php _e('Website','noo'); ?></label>
										<input type="text" id="website" class="form-control" value="<?php echo $website; ?>" name="website" />
									</div>
									<div class="form-group s-profile-address">
										<label for="address"><?php _e('Address','noo'); ?></label>
										<input type="text" id="address" class="form-control" value="<?php echo $address; ?>" name="address" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group s-profile-desc">
										<label for="desc"><?php _e('About me','noo'); ?></label>
										<textarea id="desc" class="form-control" name="desc" rows="8"><?php echo $desc; ?></textarea>
									</div>
									<div id="upload-container">
										<label><?php _e('Avatar','noo'); ?></label>
										<div id="aaiu-upload-container" class="row">
											<div class="col-md-6">
											<?php noo_upload_form( $avatar ); ?>
											</div>
											<div class="col-md-6">
												<p><?php _e('Recommended size: 370x500','noo');?></p>
												<a id="aaiu-uploader" class="btn btn-secondary btn-lg" href="#"><?php _e('Choose Image','noo');?></a>
											</div>
											<input type="hidden" name="avatar" id="avatar" value="<?php echo $avatar;?>">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="noo-control-group">
							<div class="group-title">
								<?php _e('Social Network', 'noo'); ?>
							</div>
							<div class="group-container row">
								<div class="col-md-6">
									<div class="form-group s-profile-facebook">
										<label for="facebook"><?php _e('Facebook Url','noo'); ?></label>
										<input type="text" id="facebook" class="form-control" value="<?php echo $facebook; ?>" name="facebook" />
									</div>
									<div class="form-group s-profile-twitter">
										<label for="twitter"><?php _e('Twitter Url','noo'); ?></label>
										<input type="text" id="twitter" class="form-control" value="<?php echo $twitter; ?>" name="twitter" />
									</div>
									<div class="form-group s-profile-google_plus">
										<label for="google_plus"><?php _e('Google Plus Url','noo'); ?></label>
										<input type="text" id="google_plus" class="form-control" value="<?php echo $google_plus; ?>" name="google_plus" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group s-profile-linkedin">
										<label for="linkedin"><?php _e('LinkedIn Url','noo'); ?></label>
										<input type="text" id="linkedin" class="form-control" value="<?php echo $linkedin; ?>" name="linkedin" />
									</div>
									<div class="form-group s-profile-pinterest">
										<label for="pinterest"><?php _e('Pinterest Url','noo'); ?></label>
										<input type="text" id="pinterest" class="form-control" value="<?php echo $pinterest; ?>" name="pinterest" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="noo-submit">
										<input type="submit" class="btn btn-primary btn-lg" id="profile_submit" value="<?php _e('Update', 'noo'); ?>" />
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="action" value="noo_ajax_update_profile">
						<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>">
						<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
						<?php wp_nonce_field('submit_profile','_noo_profile_nonce'); ?>
					</form>
					<form id="password_form" name="password_form" class="noo-form profile-form" role="form">
						<div class="noo-control-group">
							<div class="group-title">
								<?php _e('Change Password', 'noo'); ?>
							</div>
							<div class="group-container row">
								<div class="form-message">
								</div>
								<div class="col-md-6">
									<div class="form-group s-profile-old_pass">
										<label for="old_pass"><?php _e('Old Password','noo'); ?></label>
										<input type="password" id="old_pass" class="form-control" value="" name="old_pass" />
									</div>
									<div class="form-group s-profile-new_pass">
										<label for="new_pass"><?php _e('New Password','noo'); ?></label>
										<input type="password" id="new_pass" class="form-control" value="" name="new_pass" />
									</div>
									<div class="form-group s-profile-new_pass_confirm">
										<label for="new_pass_confirm"><?php _e('Confirm New Password','noo'); ?></label>
										<input type="password" id="new_pass_confirm" class="form-control" value="" name="new_pass_confirm" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="noo-submit">
										<input type="submit" class="btn btn-primary btn-lg" id="password_submit" value="<?php _e('Change Password', 'noo'); ?>" />
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="action" value="noo_ajax_change_password"/>
						<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
						<?php wp_nonce_field('submit_profile_password','_noo_profile_password_nonce'); ?>
					</form>
				</div><!-- /.submit-content -->
			</div> <!-- /.main -->
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->  
<?php get_footer(); ?>