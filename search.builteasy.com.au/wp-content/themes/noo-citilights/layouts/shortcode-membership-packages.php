<?php 
extract( shortcode_atts( array(
	'style'				=> 'ascending',
	'featured_item'		=> '2',
	'btn_text'   		=> __('Sign Up', 'noo'),
	'visibility'        => '',
	'class'             => '',
	'custom_style'      => ''
), $atts ) );

wp_enqueue_script('noo-dashboard');

$btn_text         = ( $btn_text    != '' ) ? $btn_text : __('Sign Up', 'noo');
$visibility       = ( $visibility  != '' ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class            = ( $class       != '' ) ? 'membership-packages ' . esc_attr( $class ) : 'membership-packages';
$class           .= noo_visibility_class( $visibility );

$class = ( $class != '' ) ? '' . esc_attr( $class ) . ' noo-pricing-table ascending' : ' noo-pricing-table ascending';
$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '"' : '';

$packages = new WP_Query(array(
	'post_type'=>'noo_membership',
	'posts_per_page'=> -1,
	'ignore_sticky_posts' => 1,
	'order' => 'ASC',
));
$is_logged_in = is_user_logged_in();
$html = array();
if($packages->have_posts()) :
	switch( count( $packages->posts) ) {
		case 1:
			$class .= ' one-col';
			break;
		case 2:
			$class .= ' two-col';
			break;
		case 3:
			$class .= ' three-col';
			break;
		case 4:
			$class .= ' four-col';
			break;
		case 5:
			$class .= ' five-col';
			break;
		case 6:
			$class .= ' six-col';
			break;
		default:
			$class .= ' three-col';
			break;
	}
	?>
	<div class="<?php echo $class; ?>" <?php echo $custom_style; ?>>
	<?php
	$count = 0;
	$noo_payment_settings = get_option('noo_payment_settings', '');
	$type_payment = NooPayment::get_payment_type();
	while ($packages->have_posts()): $packages->the_post();
		$count++;
		$title = get_the_title();
		$price = noo_get_post_meta(null,'_noo_membership_price');
		$interval = noo_get_post_meta(null,'_noo_membership_interval');
		if( (int)$interval > 1 ) {
			$after_price = noo_get_post_meta(null,'_noo_membership_interval').' '.noo_get_post_meta(null,'_noo_membership_interval_unit');	
		} else {
			$after_price = noo_get_post_meta(null,'_noo_membership_interval_unit');
		}

		$price = NooPayment::format_price( $price, 'text' );
		$price = '<span class="noo-price">' . $price  . '</span>';
		$price = ( trim($after_price)  != '' ) ? $price . ' /' . esc_attr( $after_price ) : $price;
		

		$plan_price = noo_get_post_meta( get_the_ID(), '_noo_membership_price' );
		$user_id	= get_current_user_id();
		$agent_id	= get_user_meta($user_id, '_associated_agent_id',true );

		
		$package_listing = noo_get_post_meta(null,'_noo_membership_listing_num');
		$package_listing_unlimited = noo_get_post_meta(null,'_noo_membership_listing_num_unlimited', false);
		if( $package_listing_unlimited ) {
			$package_listing = __('Unlimited Property Submit', 'noo');
		} else {
			$package_listing = __('Property Submit Limit: ', 'noo') . $package_listing;
		}
		$package_featured = __('Featured Property: ', 'noo') . noo_get_post_meta(null,'_noo_membership_featured_num');
		?>
		<div class="noo-pricing-column <?php echo ($count == (int)$featured_item ? 'featured' : ''); ?>">
		  	<div class="pricing-content" >
			    <div class="pricing-header" >
			      <h2 class="pricing-title"><?php echo esc_html($title); ?></h2>
			      <h3 class="pricing-value"><?php echo $price; ?></h3>
			    </div>
			    <div class="pricing-info">
			        <p class="text-center"><?php echo esc_html($package_listing); ?></p>
			        <p class="text-center"><?php echo esc_html($package_featured); ?></p>
					<?php
					for( $i = 1; $i < 5; $i++ ) {
						$additional = noo_get_post_meta(null,'_noo_membership_additional_info_' . $i);
						if( !empty( $additional ) ) {
							echo '<p class="text-center">'.$additional.'</p>';
						}
					}
				?>
			    </div>
			    <div class="pricing-footer" >
				    <form class="subscription_post">
				        <div class="form-message"></div>
				        <input type="hidden" id="package_id" name="package_id" value="<?php echo get_the_ID(); ?>">
				        <input type="hidden" name="action" value="noo_ajax_membership_payment" />
						<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $type_payment == 'woo' ) : ?>
							<input type="hidden" name="type_payment" value="woo" />
							<input type="hidden" name="plan" value="<?php echo esc_attr($title); ?>" />
							<input type="hidden" name="price" value="<?php echo esc_attr($plan_price); ?>" />
							<input type="hidden" name="agent_id" value="<?php echo esc_attr($agent_id); ?>" />
						<?php endif; ?>
				        <input type="submit" class="btn btn-lg btn-primary" role="button" value="<?php echo esc_attr($btn_text); ?>" />
				    	<?php wp_nonce_field('noo_subscription','_noo_membership_nonce', false); ?>
				    </form>
			    </div>
			</div>
		</div>
	<?php endwhile; ?>
	</div>
<?php endif;
wp_reset_query();
if( !$is_logged_in ) :
	$current_url = get_permalink();
?>
	<div class="noo-login-form" style="display:none;">
	 	<div class="form-message text-center"><h4><?php _e('Sorry, you will have to login first!', 'noo'); ?></h4></div>
			<?php echo do_shortcode( '[noo_login_register mode="both" redirect_to="' .$current_url . '" hide_for_login="true"]' ); ?>
		</div>
<?php endif; ?>