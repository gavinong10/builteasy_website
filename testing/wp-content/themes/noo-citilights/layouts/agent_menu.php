<?php
global $current_user;
get_currentuserinfo();

$user_id			= $current_user->ID;
$agent_id			= get_user_meta($user_id, '_associated_agent_id',true );
$dash_profile		= noo_get_page_link_by_template( 'agent_dashboard_profile.php' );
$dash_properties	= noo_get_page_link_by_template( 'agent_dashboard.php' );
$dash_submit		= noo_get_page_link_by_template( 'agent_dashboard_submit.php' );

$page_template		= basename( get_page_template() );
$avatar_src			= wp_get_attachment_image_src( get_post_thumbnail_id( $agent_id ), 'full' );
if( empty($avatar_src) ) {
	$avatar_src		= NOO_ASSETS_URI . '/images/default-avatar.png';
} else {
	$avatar_src		= $avatar_src[0];
}

$membership_info	= NooAgent::get_membership_info( $agent_id );

$subscription_err   = false;
$err_message        = array();
$success            = false;

?>

<div class="user-sidebar-menu dashboard-sidebar">
	<div class="user-avatar content-thumb">
		<img src="<?php echo $avatar_src; ?>" alt="<?php echo $current_user->user_login; ?>"/>
	</div>
	<div class="user-menu-links">
		<?php if( !empty( $dash_profile ) ) : ?>
			<a href="<?php echo $dash_profile;?>" class="user-link <?php echo ( $page_template == 'agent_dashboard_profile.php' ? 'active' : '' ); ?>">
				<i class="fa fa-user"></i><?php _e('My Profile', 'noo');?>
			</a>
		<?php endif; ?>
		<?php if( !empty( $dash_properties ) ) : ?>
			<a href="<?php echo $dash_properties;?>" class="user-link <?php echo ( $page_template == 'agent_dashboard.php' ? 'active' : '' ); ?>">
				<i class="fa fa-home"></i><?php _e('My Properties', 'noo');?>
			</a>
		<?php endif; ?>
	</div>
	<div class="user-menu-links user-menu-logout">
		<a href="<?php echo wp_logout_url(NooAgent::get_login_url());?>" class="user-link" title="Logout">
			<i class="fa fa-sign-out"></i><?php _e('Log Out', 'noo');?>
		</a>
	</div>
	<?php if( $membership_info['type'] != 'none') : ?>
	<div class="user-menu-submit">
		<?php if( !empty( $dash_submit ) ) : ?>
			<a href="<?php echo $dash_submit;?>" class="btn btn-secondary <?php echo ( $page_template == 'agent_dashboard_submit.php' ? 'active' : '' ); ?>">
				<?php _e('SUBMIT PROPERTY', 'noo');?>
			</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>

<?php if( !empty( $membership_info ) && $membership_info['type'] == 'membership' ) : ?>
<h3 class="dashboard-sidebar-title"><?php _e('Your Current Package', 'noo'); ?></h3>
<div class="membership-info dashboard-sidebar">
	<div class="sidebar-content">
		<p><strong><?php echo $membership_info['data']['package_title']; ?></strong></p>
		<p><?php _e('Listing included', 'noo'); ?>:&nbsp;<?php echo $membership_info['data']['listing_included'] == -1 ? __('Unlimited', 'noo') : $membership_info['data']['listing_included']; ?></p>
		<p><?php _e('Listing remain', 'noo'); ?>:&nbsp;<?php echo $membership_info['data']['listing_remain'] == -1 ? __('Unlimited', 'noo') : $membership_info['data']['listing_remain']; ?></p>
		<p><?php _e('Featured included', 'noo'); ?>:&nbsp;<?php echo $membership_info['data']['featured_included']; ?></p>
		<p><?php _e('Featured remain', 'noo'); ?>:&nbsp;<?php echo $membership_info['data']['featured_remain']; ?></p>
		<?php $expired_date = $membership_info['data']['expired_date'];
		if( $expired_date === -1 ) :
		?>
		<p><?php _e('Your package is expired', 'noo'); ?>
		<?php else: ?>
		<p><?php _e('Ends On', 'noo'); ?>:&nbsp;<?php echo $expired_date; ?></p>
		<?php endif; ?>
	</div>
</div>
<h3 id="subscription-sidebar" class="dashboard-sidebar-title"><?php _e('Change Your Package', 'noo'); ?></h3>
<div class="membership-payment dashboard-sidebar">
	<div class="sidebar-content">
		<?php 
			$noo_payment_settings = get_option('noo_payment_settings', '');
			$type_payment = NooPayment::get_payment_type();

			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $type_payment == 'woo' ) : ?>

				<?php $noo_ajax_payment = 'noo_ajax_payment_woo'; ?>

			<?php else : ?>
				
				<?php $noo_ajax_payment = 'noo_ajax_membership_payment';?>

			<?php endif; ?>
		<form class="subscription_post" >
			<div class="form-message">
			</div>
			<div class="form-group">
			<?php $args = array(
					'post_type'     => NooMembership::MEMBERSHIP_POST_TYPE,
					'posts_per_page' => -1,
					'post_status' => 'publish'
				);
				$packages = get_posts($args);
			?>
				<div class="dropdown" >
					<span class="package-label" data-toggle="dropdown"><?php _e('Select Package','noo'); ?></span>
					<ul class="dropdown-menu">
						<?php
						if(!empty($packages)) :
							foreach ($packages as $package) :
							$plan_price = noo_get_post_meta( $package->ID, '_noo_membership_price' );
						?>
							<li>
								<a href="#" data-value="<?php echo $package->ID; ?>" data-price="<?php echo $plan_price; ?>" data-plan="<?php echo $package->post_title; ?>"><?php echo $package->post_title; ?></a>
							</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
					<input type="hidden" id="package_id" name="package_id" value="" />
					<input type="hidden" id="plan" name="plan" value="" />
					<input type="hidden" id="price" name="price" value="" />
				</div>
			</div>
			
			<div>
			<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && ( $type_payment == 'woo' ) ) : ?>

				<input type="hidden" name="type_payment" value="woo" />
				<input type="submit" class="btn btn-secondary" value="<?php _e('PAYMENT NOW', 'noo');?>" />
			</div>

			<?php else : ?>

				<input type="hidden" name="type_payment" value="pay" />
				<input type="submit" class="btn btn-secondary" value="<?php _e('PAY WITH PAYPAL', 'noo');?>" />

			</div>
			<div class="form-group">
				<input type="hidden" name="recurring_payment" class="" value="0" />
				<label for="recurring_payment" class="checkbox-label">
					<input type="checkbox" id="recurring_payment" name="recurring_payment" value="1" checked /><?php _e('make payment recurring', 'noo'); ?>
				</label>
			</div>
			<div class="form-group recurring_time" style="display: none;" >
				<label for="recurring_time"><?php _e('Recurring times', 'noo'); ?></label>
				<input type="text" id="recurring_time" class="form-control" name="recurring_time" value="3" />
			</div>

			<?php endif; ?>
			<div>
			</div>
			<?php
			$noo_membership_page = get_option('noo_membership_page', '');
			$noo_membership_page = ( !empty( $noo_membership_page ) ? get_permalink( $noo_membership_page ) : '' );
			if( !empty( $noo_membership_page ) ) : ?>
			<div class="package-info">
				<?php _e('or', 'noo'); ?>&nbsp;<a href="<?php echo $noo_membership_page; ?>"><?php _e('View more detail', 'noo');?></a>
			</div>
			<?php endif; ?>
			<input type="hidden" name="action"  value="noo_ajax_membership_payment" />
			<input type="hidden" name="agent_id"  value="<?php echo $agent_id; ?>" />
			<?php wp_nonce_field('noo_subscription','_noo_membership_nonce'); ?>
		</form>
	</div>
</div>
<?php elseif( !empty( $membership_info ) && $membership_info['type'] == 'submission' ) : ?>
<h3 class="dashboard-sidebar-title"><?php _e('Paid Submission', 'noo'); ?></h3>
<div class="submission-info dashboard-sidebar">
	<div class="sidebar-content">
		<p><?php _e('This site uses paid submission model.', 'noo'); ?></p>
		<p><?php _e('Listing Price', 'noo'); ?>:&nbsp;<?php echo $membership_info['data']['listing_price_text']; ?></p>
		<p><?php _e('Featured Price (extra)', 'noo'); ?>:&nbsp;<?php echo $membership_info['data']['featured_price_text']; ?></p>
	</div>
</div>
<?php endif; ?>

 