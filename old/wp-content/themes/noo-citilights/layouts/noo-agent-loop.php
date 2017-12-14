<?php 
if($wp_query->have_posts()):
?>
	<div class="agents <?php echo (isset($_GET['mode']) ? $_GET['mode'] : 'grid') ?>">
		<div class="agents-header">
			<h1 class="page-title"><?php echo $title ?></h1>
		</div>
		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); global $post; ?>
			<?php 
				// Variables
				$prefix = NooAgent::AGENT_META_PREFIX;

				$avatar_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				if( empty($avatar_src) ) {
					$avatar_src		= NOO_ASSETS_URI . '/images/default-avatar.png';
				} else {
					$avatar_src		= $avatar_src[0];
				}

				// Agent's info
				$phone			= noo_get_post_meta( get_the_ID(), "{$prefix}_phone", '' );
				$mobile			= noo_get_post_meta( get_the_ID(), "{$prefix}_mobile", '' );
				$email			= noo_get_post_meta( get_the_ID(), "{$prefix}_email", '' );
				$skype			= noo_get_post_meta( get_the_ID(), "{$prefix}_skype", '' );
				$facebook		= noo_get_post_meta( get_the_ID(), "{$prefix}_facebook", '' );
				$twitter		= noo_get_post_meta( get_the_ID(), "{$prefix}_twitter", '' );
				$google_plus	= noo_get_post_meta( get_the_ID(), "{$prefix}_google_plus", '' );
				$linkedin		= noo_get_post_meta( get_the_ID(), "{$prefix}_linkedin", '' );
				$pinterest		= noo_get_post_meta( get_the_ID(), "{$prefix}_pinterest", '' );
				$address		= noo_get_post_meta( get_the_ID(), "{$prefix}_address", '' );
				$website		= noo_get_post_meta( get_the_ID(), "{$prefix}_website", '' );
			?>
			<article id="agent-<?php the_ID(); ?>" <?php post_class(); ?>>
			    <div class="agent-featured">
			        <a class="content-thumb" href="<?php the_permalink() ?>">
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
							<!--<?php if( !empty( $address ) ) : ?>
								<div class="agent-address"><i class="fa fa-map-marker"></i>&nbsp;<?php echo $address; ?></div>
							<?php endif; ?>
							<?php if( !empty( $website ) ) : ?>
								<div class="agent-website"><i class="fa fa-globe"></i>&nbsp;<a href="<?php echo esc_url( $website ); ?>" target="_blank"><?php echo esc_url( $website ); ?><a/></div>
							<?php endif; ?>-->
						</div>
						<div class="agent-desc">
							<?php if( !empty($facebook) || !empty($twitter) || !empty($google_plus) || !empty($linkedin) || !empty($pinterest) ) : ?>
							<div class="agent-social">
								<?php echo ( !empty($facebook) ? '<a class="noo-social-facebook" href="' . $facebook . '"></a>' : '' ); ?>
								<?php echo ( !empty($twitter) ? '<a class="noo-social-twitter" href="' . $twitter . '"></a>' : '' ); ?>
								<?php echo ( !empty($google_plus) ? '<a class="noo-social-googleplus" href="' . $google_plus . '"></a>' : '' ); ?>
								<?php echo ( !empty($linkedin) ? '<a class="noo-social-linkedin" href="' . $linkedin . '"></a>' : '' ); ?>
								<?php echo ( !empty($pinterest) ? '<a class="noo-social-pinterest" href="' . $pinterest . '"></a>' : '' ); ?>
							</div>
							<?php endif; ?>
							<div class="agent-action">
								<a href="<?php the_permalink()?>"><?php the_title(); ?></a>
							</div>
						</div>
						
					</div>
				</div>
			</article> <!-- /#post- -->
		<?php endwhile; ?>
	</div>
<?php endif;