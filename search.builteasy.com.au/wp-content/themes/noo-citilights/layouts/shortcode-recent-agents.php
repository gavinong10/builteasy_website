<?php 
extract( shortcode_atts( array(
	'title'             => __('Recent Agents','noo'),
	'number'            => '6',
	'visibility'        => '',
	'class'             => '',
	'custom_style'      => ''
), $atts ) );

wp_enqueue_script( 'noo-property' );

$visibility       = ( $visibility      != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
$class            = ( $class           != ''     ) ? 'recent-agents ' . esc_attr( $class ) : 'recent-agents';
$class           .= noo_visibility_class( $visibility );

$class = ( $class != '' ) ? ' class="' . esc_attr( $class ) . '"' : '';
$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '"' : '';
$args = array(
	'posts_per_page'      => $number,
	'no_found_rows'       => true,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'post_type'=>NooAgent::AGENT_POST_TYPE,
);

$q = new WP_Query($args);
	?>
	<?php if($q->have_posts()):?>
		<div class="recent-agents recent-agents-slider">
			<?php if(!empty($title)):?>
			<div class="recent-agents-title"><h3><?php echo $title?></h3></div>
			<?php endif;?>
			<div class="recent-agents-content">
				<div class="caroufredsel-wrap row">
				<ul class="">
				<?php while ($q->have_posts()): $q->the_post();global $post;?>
					<?php 
						// // Check if agent has properties
						// if( !NooAgent::has_my_properties( $post->ID ) ) {
						// 	continue;
						// }

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
					?>
					<li class="col-md-4 col-sm-6">
						<article id="agent-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="agent-featured">
								<a class="content-thumb" href="<?php the_permalink() ?>">
									<img src="<?php echo $avatar_src; ?>" alt="<?php the_title(); ?>"/>
								</a>
							</div>
							<div class="agent-wrap">
								<h2 class="agent-title">
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="agent-excerpt">
									<?php if($excerpt = apply_filters( 'the_content', $post->post_content )):?>
										<?php 
										$num_word = 20;
										$excerpt = strip_shortcodes($excerpt);
										echo '<p>' . wp_trim_words($excerpt,$num_word,'...') . '</p>';
										?>
									<?php endif;?>
								</div>
								<div class="agent-social">
									<?php echo ( !empty($facebook) ? '<a class="noo-social-facebook" href="' . $facebook . '"></a>' : '' ); ?>
									<?php echo ( !empty($twitter) ? '<a class="noo-social-twitter" href="' . $twitter . '"></a>' : '' ); ?>
									<?php echo ( !empty($google_plus) ? '<a class="noo-social-googleplus" href="' . $google_plus . '"></a>' : '' ); ?>
									<?php echo ( !empty($linkedin) ? '<a class="noo-social-linkedin" href="' . $linkedin . '"></a>' : '' ); ?>
									<?php echo ( !empty($pinterest) ? '<a class="noo-social-pinterest" href="' . $pinterest . '"></a>' : '' ); ?>
								</div>
							</div>
						</article>
					</li>
				<?php endwhile;?>
				</ul>
				</div>
				<a class="caroufredsel-prev" href="javascript:void(0)"></a>
		    	<a class="caroufredsel-next" href="javascript:void(0)"></a>
			</div>
		</div>
	<?php
	wp_reset_query();
	wp_reset_postdata();
endif;