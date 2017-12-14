<?php 
// Variables
$prefix = '_noo_agent';

$avatar_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
if( empty($avatar_src) ) {
	$avatar_src		= NOO_ASSETS_URI . '/images/default-avatar.png';
} else {
	$avatar_src		= $avatar_src[0];
}

// Agent's info
$position		= noo_get_post_meta( get_the_ID(), "{$prefix}_position", '' );
$phone			= noo_get_post_meta( get_the_ID(), "{$prefix}_phone", '' );
$mobile			= noo_get_post_meta( get_the_ID(), "{$prefix}_mobile", '' );
$email			= noo_get_post_meta( get_the_ID(), "{$prefix}_email", '' );
$skype			= noo_get_post_meta( get_the_ID(), "{$prefix}_skype", '' );
$facebook		= noo_get_post_meta( get_the_ID(), "{$prefix}_facebook", '' );
$twitter		= noo_get_post_meta( get_the_ID(), "{$prefix}_twitter", '' );
$google_plus	= noo_get_post_meta( get_the_ID(), "{$prefix}_google_plus", '' );
$linkedin		= noo_get_post_meta( get_the_ID(), "{$prefix}_linkedin", '' );
$pinterest		= noo_get_post_meta( get_the_ID(), "{$prefix}_pinterest", '' );
$website		= noo_get_post_meta( get_the_ID(), "{$prefix}_website", '' );
$address		= noo_get_post_meta( get_the_ID(), "{$prefix}_address", '' );

?>

<?php get_header(); ?>
<div class="main-content container-boxed max offset">
	<div class="row">
		<div class="<?php noo_main_class(); ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="noo-agent">
					<h1 class="content-title agent-name">
						<?php the_title(); ?>
						<?php if( !empty($position) ) : ?>
							<small class="agent-position"><?php echo $position; ?></small>
						<?php endif; ?>
					</h1>
					<?php //if( !empty($facebook) || !empty($twitter) || !empty($google_plus) || !empty($linkedin) || !empty($pinterest) ) : ?>
					<div class="agent-social clearfix">
						<?php echo ( !empty($facebook) ? '<a class="noo-social-facebook" href="' . $facebook . '"></a>' : '' ); ?>
						<?php echo ( !empty($twitter) ? '<a class="noo-social-twitter" href="' . $twitter . '"></a>' : '' ); ?>
						<?php echo ( !empty($google_plus) ? '<a class="noo-social-googleplus" href="' . $google_plus . '"></a>' : '' ); ?>
						<?php echo ( !empty($linkedin) ? '<a class="noo-social-linkedin" href="' . $linkedin . '"></a>' : '' ); ?>
						<?php echo ( !empty($pinterest) ? '<a class="noo-social-pinterest" href="' . $pinterest . '"></a>' : '' ); ?>
					</div>
					<?php //endif; ?>
					<div class="agent-info">
						<div class="content-featured">
					        <div class="content-thumb">
					        	<img src="<?php echo $avatar_src; ?>" alt="<?php the_title(); ?>"/>
					        </div>
					    </div>
						<div class="agent-detail">
							<h4 class="agent-detail-title"><?php _e('Contact Info','noo')?></h4>
							<div class="agent-detail-info">
								<?php if( !empty( $phone ) ) : ?>
									<div class="agent-phone"><i class="fa fa-phone"></i>&nbsp;<span><?php _e('Phone:','noo')?></span><?php echo $phone; ?></div>
								<?php endif; ?>
								<?php if( !empty( $mobile ) ) : ?>
									<div class="agent-mobile"><i class="fa fa-tablet"></i>&nbsp;<span><?php _e('Mobile:','noo')?></span><?php echo $mobile; ?></div>
								<?php endif; ?>
								<?php if( !empty( $email ) ) : ?>
									<div class="agent-email"><i class="fa fa-envelope-square"></i>&nbsp;<span><?php _e('Email:','noo')?></span><?php echo $email; ?></div>
								<?php endif; ?>
								<?php if( !empty( $skype ) ) : ?>
									<div class="agent-skype"><i class="fa fa-skype"></i>&nbsp;<span><?php _e('Skype:','noo')?></span><?php echo $skype; ?></div>
								<?php endif; ?>
								<?php if( !empty( $address ) ) : ?>
									<div class="agent-address"><i class="fa fa-map-marker"></i>&nbsp;<span><?php _e('Address:','noo')?></span><?php echo $address; ?></div>
								<?php endif; ?>
								<?php if( !empty( $website ) ) : ?>
									<div class="agent-website"><i class="fa fa-globe"></i>&nbsp;<span><?php _e('Website:','noo')?></span><a href="<?php echo esc_url( $website ); ?>" target="_blank"><?php echo esc_url( $website ); ?></a></div>
								<?php endif; ?>
							</div>
							<div class="agent-desc">
								<h4 class="agent-detail-title"><?php _e('About Me','noo')?></h4>
								<?php the_content();?>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="conact-agent row">
						<h2 class="content-title col-md-12">
							<?php _e('Contact Me','noo')?>
						</h2>
						<form role="form" id="conactagentform" method="post">
							<div style="display: none;">
								<input type="hidden" name="action" value="noo_contact_agent">
								<input type="hidden" name="agent_id" value="<?php echo get_the_ID()?>">
								<input type="hidden" name="security" value="<?php echo wp_create_nonce('noo-contact-agent-'.get_the_ID())?>">
							</div>
							<?php do_action('before_noo_agent_contact_form')?>
							<?php do_action( 'noo_agent_contact_form_before_fields' ); ?>
							<?php 
							$fields = array(
								'name'=>'<div class="form-group col-md-6 col-sm-6"><input type="text" name="name" class="form-control" placeholder="'.__('Your Name *','noo').'"></div>',
								'email'=>'<div class="form-group  col-md-6 col-sm-6"><input type="email" name="email" class="form-control" placeholder="'.__('Your Email *','noo').'"></div>',
								'message'=>'<div class="form-group message col-md-12 col-sm-12"><textarea name="message" class="form-control" rows="5" placeholder="'.__('Message *','noo').'"></textarea></div>',
							);
							$fields = apply_filters( 'noo_agent_contact_form_default_fields', $fields );
							foreach ($fields as $field):
								echo $field;
							endforeach;
							do_action( 'noo_agent_contact_form_after_fields' );
							?>
							<div class="form-action col-md-12 col-sm-12">
								<img class="ajax-loader" src="<?php echo NOO_ASSETS_URI ?>/images/ajax-loader.gif" alt="<?php _e('Sending ...','noo')?>" style="visibility: hidden;">
								<button type="submit" class="btn btn-default"><?php _e('Send Me','noo')?></button>
							</div>
							<?php do_action('before_noo_agent_contact_form')?>
						</form>
					</div>
					<div class="agent-properties" data-agent-id="<?php the_ID()?>">
						<?php 
						global $noo_show_sold;
						$noo_show_sold = true;
						$args = array(
								'paged'=> 1,
								'posts_per_page' =>4,
								'post_type'=>'noo_property',
								'meta_query' => array(
									array(
										'key' => '_agent_responsible',
										'value' => get_the_ID(),
									),
								),
						);
						$r = new WP_Query($args);
						NooProperty::display_content($r,__('My Properties','noo'),true,'',true,true);
						wp_reset_query();
						wp_reset_postdata();
						$noo_show_sold = false;
						?>
					</div>
				</article> <!-- /#post- -->
				
			<?php endwhile; ?>
		</div>
		<?php get_sidebar(); ?>
	</div> <!-- /.row -->

</div> <!-- /.container-boxed.max.offset -->

<?php get_footer(); ?>