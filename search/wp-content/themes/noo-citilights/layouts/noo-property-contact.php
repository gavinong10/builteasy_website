<?php
if($agent = get_post($agent_id)) :
	// Variables
	$prefix = '_noo_agent';

	$avatar_src = wp_get_attachment_image_src( get_post_thumbnail_id( $agent->ID ), 'full' );
	if( empty($avatar_src) ) {
		$avatar_src		= NOO_ASSETS_URI . '/images/default-avatar.png';
	} else {
		$avatar_src		= $avatar_src[0];
	}

	// Agent's info
	$phone			= noo_get_post_meta( $agent->ID, "{$prefix}_phone", '' );
	$mobile			= noo_get_post_meta( $agent->ID, "{$prefix}_mobile", '' );
	$email			= noo_get_post_meta( $agent->ID, "{$prefix}_email", '' );
	$skype			= noo_get_post_meta( $agent->ID, "{$prefix}_skype", '' );
	$facebook		= noo_get_post_meta( $agent->ID, "{$prefix}_facebook", '' );
	$twitter		= noo_get_post_meta( $agent->ID, "{$prefix}_twitter", '' );
	$google_plus	= noo_get_post_meta( $agent->ID, "{$prefix}_google_plus", '' );
	$linkedin		= noo_get_post_meta( $agent->ID, "{$prefix}_linkedin", '' );
	$pinterest		= noo_get_post_meta( $agent->ID, "{$prefix}_pinterest", '' );
	$address		= noo_get_post_meta( $agent->ID, "{$prefix}_address", '' );
	$website		= noo_get_post_meta( $agent->ID, "{$prefix}_website", '' );
	?>
	<div class="agent-property">
		<div class="agent-property-title">
			<h3><?php echo __('Contact Agent','noo')?></h3>
		</div>
		<div class="agents grid">
			<div <?php post_class('',$agent->ID); ?>>
			    <div class="agent-featured">
			        <a class="content-thumb" href="<?php echo get_permalink($agent->ID) ?>">
						<img src="<?php echo $avatar_src; ?>" alt="<?php the_title(); ?>"/>
					</a>
			    </div>
				<div class="agent-wrap">
					<div class="agent-summary">
						<div class="agent-info">
							<?php if( !empty( $phone ) ) : ?>
								<div class="agent-phone"><i class="fa fa-phone"></i>&nbsp;<?php echo $phone; ?></div>
							<?php endif; ?>
							<?php if( !empty( $mobile ) ) : ?>
								<div class="agent-mobile"><i class="fa fa-tablet"></i>&nbsp;<?php echo $mobile; ?></div>
							<?php endif; ?>
							<?php if( !empty( $email ) ) : ?>
								<div class="agent-email"><i class="fa fa-envelope-square"></i>&nbsp;<?php echo $email; ?></div>
							<?php endif; ?>
							<?php if( !empty( $skype ) ) : ?>
								<div class="agent-skype"><i class="fa fa-skype"></i>&nbsp;<?php echo $skype; ?></div>
							<?php endif; ?>
							<?php if( !empty( $address ) ) : ?>
								<div class="agent-address"><i class="fa fa-location-arrow"></i>&nbsp;<?php echo $address; ?></div>
							<?php endif; ?>
							<?php if( !empty( $website ) ) : ?>
								<div class="agent-website"><i class="fa fa-globe"></i>&nbsp;<?php echo $website; ?></div>
							<?php endif; ?>
						</div>
						<div class="agent-desc">
							<div class="agent-social">
								<?php echo ( !empty($facebook) ? '<a class="noo-social-facebook" href="' . $facebook . '"></a>' : '' ); ?>
								<?php echo ( !empty($twitter) ? '<a class="noo-social-twitter" href="' . $twitter . '"></a>' : '' ); ?>
								<?php echo ( !empty($google_plus) ? '<a class="noo-social-googleplus" href="' . $google_plus . '"></a>' : '' ); ?>
								<?php echo ( !empty($linkedin) ? '<a class="noo-social-linkedin" href="' . $linkedin . '"></a>' : '' ); ?>
								<?php echo ( !empty($pinterest) ? '<a class="noo-social-pinterest" href="' . $pinterest . '"></a>' : '' ); ?>
							</div>
							<div class="agent-action">
								<a href="<?php echo get_permalink($agent->ID)?>">
									<?php echo get_the_title($agent->ID); ?>
								</a>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="conact-agent">
				<form role="form" id="conactagentform" method="post">
					<div style="display: none;">
						<input type="hidden" name="action" value="noo_contact_agent_property">
						<input type="hidden" name="agent_id" value="<?php echo $agent->ID?>">
						<input type="hidden" name="property_id" value="<?php echo $property_id?>">
						<input type="hidden" name="security" value="<?php echo wp_create_nonce('noo-contact-agent-'.$agent->ID)?>">
					</div>
					<?php do_action('before_noo_agent_contact_form')?>
					<?php do_action( 'noo_agent_contact_form_before_fields' ); ?>
					<?php 
					$fields = array(
						'name'=>'<div class="form-group"><input type="text" name="name" class="form-control" placeholder="'.__('Your Name *','noo').'"></div>',
						'email'=>'<div class="form-group"><input type="email" name="email" class="form-control" placeholder="'.__('Your Email *','noo').'"></div>',
						'message'=>'<div class="form-group"><textarea name="message" class="form-control" rows="5" placeholder="'.__('Message *','noo').'"></textarea></div>',
					);
					$fields = apply_filters( 'noo_property_agent_contact_form_default_fields', $fields );
					foreach ($fields as $field):
						echo $field;
					endforeach;
					do_action( 'noo_agent_contact_form_after_fields' );
					?>
					<div class="form-action col-md-12 col-sm-12">
						<img class="ajax-loader" src="<?php echo NOO_ASSETS_URI ?>/images/ajax-loader.gif" alt="<?php _e('Sending ...','noo')?>" style="visibility: hidden;">
						<button type="submit" class="btn btn-default"><?php _e('Send a Message','noo')?></button>
					</div>
					<?php do_action('after_noo_agent_contact_form')?>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>