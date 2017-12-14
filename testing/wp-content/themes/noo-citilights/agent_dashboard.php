<?php
/*
Template Name: Agent Dashboard
*/

NooAgent::check_logged_in_user();

global $current_user;
get_currentuserinfo();

$user_id  = $current_user->ID;
$agent_id = get_user_meta($user_id, '_associated_agent_id', true );

$submit_link  = noo_get_page_link_by_template( 'agent_dashboard_submit.php' );

// Membership information
$membership_info  = NooAgent::get_membership_info( $agent_id );
$membership_type  = $membership_info['type'];
$can_set_featured = NooAgent::can_set_featured( $agent_id );
$noo_payment_settings = get_option('noo_payment_settings', '');
$type_payment     = NooPayment::get_payment_type();
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $type_payment == 'woo' ) :

	$payment_name = __('Pay Now', 'noo');
	$payment_class = 'woo';
	$type_payment = 'woo';

else :

	$payment_name = __('Pay with Paypal', 'noo');
	$payment_class = 'paypal';
	$type_payment = 'paypal';

endif;
?>

<?php get_header(); ?>
<div class="container-wrap">
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="noo-sidebar col-md-4">
				<div class="noo-sidebar-wrapper">
				<?php noo_get_layout('agent_menu');  ?>
				</div>
			</div> 
			<div class="<?php noo_main_class(); ?>" role="main">
				<div class="properties list my-properties">
					<div class="properties-header">
						<h1 class="page-title"><?php _e('My Properties', 'noo'); ?></h1>
					</div>
					<div class="properties-content">
					<?php
					global $noo_show_sold;
					$noo_show_sold = true;

					$args = array(
							'posts_per_page' => -1,
							'post_type'=>'noo_property',
							'meta_query' => array(
								array(
									'key' => '_agent_responsible',
									'value' => $agent_id,
								),
							),
							'post_status' => array( 'publish', 'pending' )
					);

					$properties = new WP_Query($args);
					global $wp_query;
					if(!empty($properties)){
						$wp_query = $properties;
					}
					?>
					<?php if ( $wp_query->have_posts() ) : ?>
						<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<?php
							$post_ID		= get_the_ID();
							$post_status	= get_post_status( $post_ID );
							$featured		= esc_attr( noo_get_post_meta( $post_ID, '_featured', '' ) ) == 'yes';
							$edit_link		= esc_url( add_query_arg( 'prop_edit', $post_ID, $submit_link ) );
							$sold_status	= get_option('default_property_status');
							$status_array	= get_the_terms($post_ID, 'property_status');

							if( $membership_type == 'submission' ) {
								$paid_listing		= (bool) esc_attr( noo_get_post_meta( $post_ID, '_paid_listing', false ) );
								$zero_price_text	= NooPayment::format_price( 0, 'text');
								$listing_price		= ( !$paid_listing ) ? $membership_info['data']['listing_price'] : 0;
								$featured_price		= ( !$featured ) ? $membership_info['data']['featured_price'] : 0;
								$total_price		= $listing_price + $featured_price;
								$total_price_text	= NooPayment::format_price( $total_price, 'text' );
								$total_price	= NooPayment::format_price( $total_price, 'number' );
							}
							?>
						<article id="property-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="property-featured">
								<?php if('yes' === noo_get_post_meta($post->ID,'_featured')):?>
									<span class="featured"><i class="fa fa-star"></i></span>
								<?php endif;?>
								<a class="content-thumb" href="<?php the_permalink() ?>">
									<?php echo get_the_post_thumbnail(get_the_ID(),'property-thumb') ?>
								</a>
								<?php 
								$sold = get_option('default_property_status');
								$is_sold = !empty($sold) && (has_term($sold,'property_status'));
								if($is_sold):
									$sold_term = get_term($sold, 'property_status');
								?>
									<span class="property-label sold"><?php echo $sold_term->name?></span>
								<?php
								endif;
								?>
								<?php 
								$_label = noo_get_post_meta(null,'_label');
								if(!empty($_label) && ($property_label = get_term($_label, 'property_label'))) :
									$noo_property_label_colors = get_option('noo_property_label_colors');
									$color 	= isset($noo_property_label_colors[$property_label->term_id]) ? $noo_property_label_colors[$property_label->term_id] : '';
								?>
									<span class="property-label" <?php echo (!empty($color) ? ' style="background-color:'.$color.'"':'')?>><?php echo $property_label->name?></span>
								<?php endif;?>
								<span class="property-category"><?php echo get_the_term_list(get_the_ID(), 'property_category')?></span>
							</div>
							<div class="property-wrap">
								<h2 class="property-title">
									<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permanent link to: "%s"','noo' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="property-labels">
									<?php if( $membership_type == 'submission' ) : ?>
										<?php if( !$paid_listing ) : ?>
									<span class="label label-danger"><?php _e('Not Paid', 'noo'); ?></span>
										<?php else : ?>
									<span class="label label-info"><?php _e('Paid', 'noo'); ?></span>
										<?php endif; ?>
									<?php endif; ?>
									<?php //if( $featured ) : ?>
									<!-- <span class="label label-success"><?php _e('Featured', 'noo'); ?></span> -->
									<?php //endif; ?>
									<?php if( $post_status == 'pending' ) : ?>
									<span class="label label-warning"><?php _e('Waiting for Approval', 'noo'); ?></span>
									<?php endif; ?>
								</div>
								<?php if( $membership_type == 'submission' && ( !$paid_listing || !$featured ) ) : ?>
								<form class="listing-payment-form" >
									<div class="form-message">
									</div>
									<div class="submission-payment" data-total-price-text="<?php echo $total_price_text; ?>" data-zero-price-text="<?php echo $zero_price_text; ?>" >
										<div class="payment-form">
											<?php if( !$paid_listing ) : ?>
											<p class="listing-price">
												<label><?php _e('Submission Fee', 'noo'); ?>:<?php echo $membership_info['data']['listing_price_text'] ?></label>
											</p>
											<?php endif; ?>
											<?php if( !$featured ) : ?>
											<p class="form-group featured-price">
												<label for="submission_featured_<?php echo $post_ID; ?>">
													<input type="checkbox" class="submission-featured" name="submission_featured" id="submission_featured_<?php echo $post_ID; ?>"/>&nbsp;<?php _e('Featured Fee', 'noo'); ?>:<?php echo $membership_info['data']['featured_price_text']; ?>
													<i></i>
												</label>
											</p>
											<?php endif; ?>
										</div>
										<div class="payment-total">
											<strong><?php echo ( !$paid_listing ) ? $membership_info['data']['listing_price_text'] : NooPayment::format_price( 0 ); ?></strong>
										</div>
										<div class="payment-action">
											<input type="submit" class="<?php echo $payment_class; ?>-btn btn btn-default <?php echo ( !$paid_listing ) ? '' : 'disabled'; ?>" value="<?php echo ($payment_name);?>" />
										</div>
										<input type="hidden" name="agent_id"  value="<?php echo $agent_id; ?>" />
										<input type="hidden" name="prop_id"  value="<?php echo $post_ID; ?>" />
										<input type="hidden" name="action"  value="noo_ajax_listing_payment" />
										<input type="hidden" name="type_payment"  value="<?php echo $type_payment; ?>" />
										<input type="hidden" name="title_property"  value="<?php echo the_title_attribute( 'echo=0' ); ?>" />
										<input type="hidden" name="price_property" id="price_property" value="<?php echo $listing_price; ?>" />
										<?php wp_nonce_field('noo_listing_payment', '_noo_listing_nonce'); ?>
									</div>
								</form>
								<?php else : ?>
								<div class="property-excerpt">
									<?php if($excerpt = $post->post_content):?>
										<?php 
										$num_word = 15;
										$excerpt = strip_shortcodes($excerpt);
										echo '<p>' . wp_trim_words($excerpt,15,'...') . '</p>';
										echo '<p class="property-fullwidth-excerpt">' . wp_trim_words($excerpt,25,'...') . '</p>';
										?>
									<?php endif;?>
								</div>
								<?php endif; ?>
								<div class="property-summary">
									<div class="property-detail">
										<div class="size"><span><?php echo NooProperty::get_area_html($post_ID);?></span></div>
										<div class="bathrooms"><span><?php esc_attr_e( noo_get_post_meta($post_ID,'_bathrooms') );?></span></div>
										<div class="bedrooms"><span><?php esc_attr_e( noo_get_post_meta($post_ID,'_bedrooms') );?></span></div>
									</div>
									<div class="property-info">
										<div class="property-price">
											<span><?php echo NooProperty::get_price_html($post_ID)?></span>
										</div>
										<div class="property-action">
											<div data-prop-id="<?php echo $post_ID; ?>" data-agent-id="<?php echo $agent_id; ?>" class="agent-action <?php echo ( ( $membership_type == 'membership' ) ? 'four-buttons' : '' ); ?>">
												<?php if( NooAgent::can_edit( $agent_id ) ) : ?>
												<a title="<?php _e('Edit this Property','noo');?>" href="<?php echo $edit_link;?>"><i class="fa fa-pencil"></i></a>
													<?php if( $is_sold ) : ?>
												<a class="active" title="<?php _e('Sold/Rent','noo');?>" href="javascript:void(0)"><i class="fa fa-check"></i></a>
													<?php else : ?>
												<a class="status-property" title="<?php _e('Mark As Sold/Rent','noo');?>" href="javascript:void(0)"><i class="fa fa-check"></i></a>
													<?php endif; ?>
												<?php else : ?>
												<a class="disabled" title="<?php _e('Can\'t edit this property','noo');?>" href="javascript:void(0)"><i class="fa fa-pencil"></i></a>
												<a class="disabled" title="<?php _e('Can\'t edit this property','noo');?>" href="javascript:void(0)"><i class="fa fa-check"></i></a>
												<?php endif; ?>
												<?php if( $membership_type == 'membership' ) : ?>
												<?php if( $featured ) : ?>
													<a class="active" title="<?php _e('Featured Property','noo');?>" href="javascript:void(0)"><i class="fa fa-star"></i></a>
												<?php elseif( !$can_set_featured ) : ?>
													<a class="disabled" title="<?php _e('Can\'t set featured','noo');?>" href="javascript:void(0)"><i class="fa fa-star"></i></a>
												<?php else : ?>
													<a class="featured-property" title="<?php _e('Make this a featured Property.','noo');?>" href="javascript:void(0)"><i class="fa fa-star"></i></a>
												<?php endif; ?>
												<?php endif; ?>
												<a class="delete-property" title="<?php _e('Delete this Property','noo');?>" href="javascript:void(0)"><i class="fa fa-times"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</article> <!-- /#property- -->
						<?php endwhile; ?>
					<?php else : ?>
						<h4><?php _e('You don\'t have any properties yet!', 'noo'); ?></h4>
					<?php endif;
					wp_reset_query();
					wp_reset_postdata();
					$noo_show_sold = false;
					?>
					</div> <!-- /.properties-content -->
				</div> <!-- /.properties -->
			</div> <!-- /.main -->
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->

<?php get_footer(); ?>