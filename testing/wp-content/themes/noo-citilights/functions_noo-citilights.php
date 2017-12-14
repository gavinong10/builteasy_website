<?php


/* =============================================================================
 *
 * Function for specific theme, remember to keep all the functions
 * specified for this theme inside this file.
 *
 * ============================================================================*/

// Define theme specific constant
if (!defined('NOO_THEME_NAME'))
{
  define('NOO_THEME_NAME', 'noo-citilights');
}

if (!defined('NOO_THEME_VERSION'))
{
  define('NOO_THEME_VERSION', '0.0.1');
}

function noo_social_share( $post_id = null ) {
	$post_id = (null === $post_id) ? get_the_id() : $post_id;
	$post_type =  get_post_type($post_id);
	$prefix = 'noo_blog';

	if($post_type == 'portfolio_project' ) {
		$prefix = 'noo_portfolio';
	}

	if(noo_get_option("{$prefix}_social", true ) === false) {
		return '';
	}

	$share_url     = urlencode( get_permalink() );
	$share_title   = urlencode( get_the_title() );
	$share_source  = urlencode( get_bloginfo( 'name' ) );
	$share_content = urlencode( get_the_content() );
	$share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
	$popup_attr    = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';

	$facebook     = noo_get_option( "{$prefix}_social_facebook", true );
	$twitter      = noo_get_option( "{$prefix}_social_twitter", true );
	$google		  = noo_get_option( "{$prefix}_social_google", true );
	$pinterest    = noo_get_option( "{$prefix}_social_pinterest", true );
	$linkedin     = noo_get_option( "{$prefix}_social_linkedin", true );
	$html = array();

	if ( $facebook || $twitter || $google || $pinterest || $linkedin ) {
		$html[] = '<div class="content-share">';
		// $html[] = '<p class="share-title">';
		// $html[] = '</p>';
		$html[] = '<div class="noo-social social-share">';

		if($facebook) {
			$html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="noo-share"'
					. ' title="' . __( 'Share on Facebook', 'noo' ) . '"'
							. ' onclick="window.open('
									. "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
									. ' return false;">';
			$html[] = '<i class="nooicon-facebook"></i>';
			$html[] = '</a>';
		}

		if($twitter) {
			$html[] = '<a href="#share" class="noo-share"'
					. ' title="' . __( 'Share on Twitter', 'noo' ) . '"'
							. ' onclick="window.open('
									. "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
									. ' return false;">';
			$html[] = '<i class="nooicon-twitter"></i></a>';
		}

		if($google) {
			$html[] = '<a href="#share" class="noo-share"'
					. ' title="' . __( 'Share on Google+', 'noo' ) . '"'
							. ' onclick="window.open('
							. "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
							. ' return false;">';
							$html[] = '<i class="nooicon-google-plus"></i></a>';
		}

		if($pinterest) {
			$html[] = '<a href="#share" class="noo-share"'
					. ' title="' . __( 'Share on Pinterest', 'noo' ) . '"'
							. ' onclick="window.open('
									. "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
									. ' return false;">';
			$html[] = '<i class="nooicon-pinterest"></i></a>';
		}

		if($linkedin) {
			$html[] = '<a href="#share" class="noo-share"'
					. ' title="' . __( 'Share on LinkedIn', 'noo' ) . '"'
							. ' onclick="window.open('
									. "'http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}','popupLinkedIn','width=610,height=480,{$popup_attr}');"
									. ' return false;">';
			$html[] = '<i class="nooicon-linkedin"></i></a>';
		}

		$html[] = '</div>'; // .noo-social.social-share
		$html[] = '</div>'; // .share-wrap
	}

	echo implode("\n", $html);
}

function noo_list_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	GLOBAL $post;
	$avatar_size = isset($args['avatar_size']) ? $args['avatar_size'] : 60;
	?>
		<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-wrap">
				<div class="comment-img">
					<?php echo get_avatar($comment, $avatar_size); ?>
				</div>
				<article id="comment-<?php comment_ID(); ?>" class="comment-block">
					<header class="comment-header">
						<cite class="comment-author"><?php echo get_comment_author_link(); ?> 
							<?php if ($comment->user_id === $post->post_author): ?>
							<span class="ispostauthor">
								<?php _e('Author', 'noo'); ?>
							</span>
							<?php endif; ?>
						</cite>
						
						<div class="comment-meta">
							<time datetime="<?php echo get_comment_time('c'); ?>">
								<?php echo sprintf(__('%1$s at %2$s', 'noo') , get_comment_date() , get_comment_time()); ?>
							</time>
							<span class="comment-edit">
								<?php edit_comment_link('' . __('Edit', 'noo')); ?>
							</span>
						</div>
						<?php if ('0' == $comment->comment_approved): ?>
							<p class="comment-pending"><?php _e('Your comment is awaiting moderation.', 'noo'); ?></p>
						<?php endif; ?>
					</header>
					<section class="comment-content">
						<?php comment_text(); ?>
						<span class="comment-reply">
							<?php comment_reply_link(array_merge($args, array(
								'reply_text' => (__('Reply', 'noo') . '') ,
								'depth' => $depth,
								'max_depth' => $args['max_depth']
							))); ?>
						</span>
					</section>

				</article>
			</div>
		<?php
}

function noo_content_meta($is_shortcode=false) {
	$post_type = get_post_type();

	if ( $post_type == 'post' ) {
		if ((!is_single() && noo_get_option( 'noo_blog_show_post_meta' ) === false)
				|| (is_single() && noo_get_option( 'noo_blog_post_show_post_meta' ) === false)) {
					return;
				}
	} elseif ($post_type == 'portfolio_project') {
		if (noo_get_option( 'noo_portfolio_show_post_meta' ) === false) {
			return;
		}
	} else {
		return;
	}

	$html = array();
	$html[] = '<p class="content-meta">';
	if(get_post_format() =='video')
		$html[] = '<i class="nooicon-file-video-o"></i>';
	elseif (get_post_format() == 'audio')
		$html[] = '<i class="nooicon-file-audio-o"></i>';
	elseif (get_post_format() == 'gallery')
		$html[] = '<i class="nooicon-file-image-o"></i>';
	elseif (get_post_format() == 'quote')
		$html[] = '<i class="nooicon-file-quote-left"></i>';
	elseif (get_post_format() == 'link')
		$html[] = '<i class="nooicon-file-link-o"></i>';
	elseif (get_post_format() == 'image')
		$html[] = '<i class="nooicon-file-image-o"></i>';
	else
	$html[] = '<i class="nooicon-file-image-o"></i>';
	// Categories
	$categories_html = '';
	$separator = ', ';

	// if (get_post_type() == 'portfolio_project') {
	// 	if (has_term('', 'portfolio_category', NULL)) {
	// 		$categories = get_the_terms(get_the_id() , 'portfolio_category');
	// 		foreach ($categories as $category) {
	// 			$categories_html .= '<a' . ' href="' . get_term_link($category->slug, 'portfolio_category') . '"' . ' title="' . esc_attr(sprintf(__("View all Portfolio Items in: &ldquo;%s&rdquo;", 'noo') , $category->name)) . '">' . ' ' . $category->name . '</a>' . $separator;
	// 		}
	// 	}
	// } else {
		$categories = get_the_category();
		foreach ($categories as $category) {
			$categories_html.= '<a' . ' href="' . get_category_link($category->term_id) . '"' . ' title="' . esc_attr(sprintf(__("View all posts in: &ldquo;%s&rdquo;", 'noo') , $category->name)) . '">' . ' ' . $category->name . '</a>' . $separator;
		}
	// }

	$html[] = '<span>';
	$html[] = __('Posted in', 'noo');
	$html[] = trim($categories_html, $separator) . '</span>';

	// Date
	$html[] = '<span>';
	$html[] = __('on', 'noo');
	$html[] = '<time class="entry-date" datetime="' . esc_attr(get_the_date('c')) . '">';	
	$html[] = esc_html(get_the_date());
	$html[] = '</time>';
	$html[] = '</span>';

	// Author
	$html[] = '<span>';
	$html[] = __('by', 'noo');
	ob_start();
	the_author_posts_link();
	$html[] = ob_get_clean();
	$html[] = '</span>';
	
	
	// Comments
	$comments_html = '';

	if (comments_open()) {
		$comment_title = '';
		$comment_number = '';
		if (get_comments_number() == 0) {
			$comment_title = sprintf(__('Leave a comment on: &ldquo;%s&rdquo;', 'noo') , get_the_title());
			$comment_number = __(' Leave a Comment', 'noo');
		} else if (get_comments_number() == 1) {
			$comment_title = sprintf(__('View a comment on: &ldquo;%s&rdquo;', 'noo') , get_the_title());
			$comment_number = ' 1 ' . __('Comment', 'noo');
		} else {
			$comment_title = sprintf(__('View all comments on: &ldquo;%s&rdquo;', 'noo') , get_the_title());
			$comment_number =  ' ' . get_comments_number() . ' ' . __('Comments', 'noo');
		}
			
		$comments_html.= '<span><a' . ' href="' . esc_url(get_comments_link()) . '"' . ' title="' . esc_attr($comment_title) . '"' . ' class="meta-comments">';
		if(!is_singular() || $is_shortcode)
			$comments_html.= '<i class="nooicon-comments"></i> ';
		$comments_html.=  $comment_number . '</a></span>';
	}

	$html[] = $comments_html;

	echo implode($html, "\n");
}

function noo_comment_form( $args = array(), $post_id = null ) {
	if ( null === $post_id )
		$post_id = get_the_ID();
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$args = wp_parse_args( $args );
	if ( ! isset( $args['format'] ) )
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html5    = 'html5' === $args['format'];
	$fields   =  array(
			'author' => '<p class="comment-form-author col-xs-6">'.
			'<input id="author" name="author" type="text" placeholder="' . __( 'Name*', 'noo' ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email col-xs-6">' .
			'<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . __( 'Email*', 'noo' ) . '" size="30"' . $aria_req . ' /></p>',
			'url'    => '<p class="comment-form-url col-xs-12">' . 
			'<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . __( 'Website', 'noo' ) . '" value="" size="30" /></p>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s', 'noo'), '<span class="required">*</span>' );

	/**
	 * Filter the default comment form fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $fields The default comment fields.
	*/
	$fields = apply_filters( 'comment_form_default_fields', $fields );
	$defaults = array(
			'fields'               => $fields,
			'comment_field'        => '<p class="comment-form-comment col-xs-12"> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			/** This filter is documented in wp-includes/link-template.php */
			'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'noo' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
			/** This filter is documented in wp-includes/link-template.php */
			'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'noo' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.', 'noo' ) . ( $req ? $required_text : '' ) . '</p>',
			'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'noo' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
			'id_form'              => 'commentform',
			'id_submit'            => 'submit',
			'title_reply'          => __( 'Leave <span>Your thought</span> on this post', 'noo' ),
			'title_reply_to'       => __( 'Leave a Reply to %s', 'noo' ),
			'cancel_reply_link'    => __( 'Cancel reply', 'noo' ),
			'label_submit'         => __( 'Post Comment', 'noo' ),
			'format'               => 'xhtml',
	);

	/**
	 * Filter the comment form default arguments.
	 *
	 * Use 'comment_form_default_fields' to filter the comment fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $defaults The default comment form arguments.
	*/
	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open( $post_id ) ) : ?>
			<?php
			/**
			 * Fires before the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_before' );
			?>
			<div id="respond" class="comment-respond">
				<h3 id="reply-title" class="comment-reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3>
				<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
					<?php echo $args['must_log_in']; ?>
					<?php
					/**
					 * Fires after the HTML-formatted 'must log in after' message in the comment form.
					 *
					 * @since 3.0.0
					 */
					do_action( 'comment_form_must_log_in_after' );
					?>
				<?php else : ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="comment-form"<?php echo $html5 ? ' novalidate' : ''; ?>>
						<?php
						/**
						 * Fires at the top of the comment form, inside the <form> tag.
						 *
						 * @since 3.0.0
						 */
						do_action( 'comment_form_top' );
						?>
						<div class="comment-form-fields <?php if (is_user_logged_in()) echo "comment-form-in-as"?>">
						<?php if ( is_user_logged_in() ) : ?>
							<?php
							/**
							 * Filter the 'logged in' message for the comment form for display.
							 *
							 * @since 3.0.0
							 *
							 * @param string $args_logged_in The logged-in-as HTML-formatted message.
							 * @param array  $commenter      An array containing the comment author's
							 *                               username, email, and URL.
							 * @param string $user_identity  If the commenter is a registered user,
							 *                               the display name, blank otherwise.
							 */
							echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
							?>
							<?php
							/**
							 * Fires after the is_user_logged_in() check in the comment form.
							 *
							 * @since 3.0.0
							 *
							 * @param array  $commenter     An array containing the comment author's
							 *                              username, email, and URL.
							 * @param string $user_identity If the commenter is a registered user,
							 *                              the display name, blank otherwise.
							 */
							do_action( 'comment_form_logged_in_after', $commenter, $user_identity );
							?>
						<?php else : ?>
							<?php echo $args['comment_notes_before']; ?>
								<div class="comment-form-input row">
							<?php
							/**
							 * Fires before the comment fields in the comment form.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_before_fields' );
							foreach ( (array) $args['fields'] as $name => $field ) {
								/**
								 * Filter a comment form field for display.
								 *
								 * The dynamic portion of the filter hook, $name, refers to the name
								 * of the comment form field. Such as 'author', 'email', or 'url'.
								 *
								 * @since 3.0.0
								 *
								 * @param string $field The HTML-formatted output of the comment form field.
								 */
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							/**
							 * Fires after the comment fields in the comment form.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_after_fields' );
							?>
							</div>
						<?php endif; ?>
							<div class="comment-form-textarea row">
							<?php
							/**
							 * Filter the content of the comment textarea field for display.
							 *
							 * @since 3.0.0
							 *
							 * @param string $args_comment_field The content of the comment textarea field.
							 */
							echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
							?>
							</div>
						</div>
						<?php echo $args['comment_notes_after']; ?>
						<p class="form-submit">
							<input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
							<?php comment_id_fields( $post_id ); ?>
						</p>
						<?php
						/**
						 * Fires at the bottom of the comment form, inside the closing </form> tag.
						 *
						 * @since 1.5.0
						 *
						 * @param int $post_id The post ID.
						 */
						do_action( 'comment_form', $post_id );
						?>
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php
			/**
			 * Fires after the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_after' );
		else :
			/**
			 * Fires after the comment form if comments are closed.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_comments_closed' );
		endif;
}

// function noo_excerpt_read_more( $more ) {
// 	return '';
// }
// add_filter( 'excerpt_more', 'noo_excerpt_read_more' );

function noo_content_read_more( $more ) {
	return '';
}

add_filter( 'the_content_more_link', 'noo_content_read_more' );


//// Include specific widgets
// require_once( $widget_path . '/<widgets_name>.php');

//if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) :
	
	//$type_payment = get_option('noo_payment_settings', '');
	//if ( $type_payment == 'woo' ) :
	
		if ( ! function_exists( 'noo_add_custom_price' ) ) :
			add_action( 'woocommerce_before_calculate_totals', 'noo_add_custom_price' );

			function noo_add_custom_price( $cart_object ) {
			    foreach ( $cart_object->cart_contents as $key => $value ) {
			        // @TODO: check if product is cause
			        if ( isset ($value['package_order_id']) ) :
			        
				        if ( isset($value['package_order_id']) && is_numeric($value['package_order_id']) ) {
				            $value['data']->price = $value['package_order_id'];
				        }

				        if ( isset($value['package_recurring_payment']) && is_numeric($value['package_recurring_payment']) ) {
				            $value['data']->price = $value['package_recurring_payment'];
				        }

				        if ( isset($value['package_agent_id']) && is_numeric($value['package_agent_id']) ) {
				            $value['data']->price = $value['package_agent_id'];
				        }

				        if ( isset($value['package_agent_price']) && is_numeric($value['package_agent_price']) ) {
				            $value['data']->price = $value['package_agent_price'];
				        }

				    endif;

				    if ( isset ($value['prop_id']) ) :

				    	if ( isset($value['prop_id']) && is_numeric($value['prop_id']) ) {
				            $value['data']->price = $value['prop_id'];
				        }
				        if ( isset($value['price_property']) && is_numeric($value['price_property']) ) {
				            $value['data']->price = $value['price_property'];
				        }

				    endif;

			    }
			}
		endif;

		//Store the custom field
		add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_custom_data_vase', 10, 2 );
		function add_cart_item_custom_data_vase( $cart_item_meta, $product_id ) {
		  	global $woocommerce;
		  	//exit($_POST['package_id']);
		  	// @TODO: check if product is cause
		  	if ( isset ( $_POST['package_id'] ) ) :

			  	$cart_item_meta['package_order_id'] = isset( $_POST['package_id'] ) ? intval( $_POST['package_id'] ) : '';
			  	$cart_item_meta['package_recurring_payment'] = isset( $_POST['recurring_payment'] ) ? (bool)( $_POST['recurring_payment'] ) : false;
			  	$cart_item_meta['package_agent_id'] =  isset( $_POST['agent_id'] ) ? intval( $_POST['agent_id'] ) : '';
			  	$cart_item_meta['package_agent_price'] =  isset( $_POST['price'] ) ? intval( $_POST['price'] ) : '';

			endif;
			if ( isset ( $_POST['prop_id'] ) ) :

				$cart_item_meta['prop_id'] = isset( $_POST['prop_id'] ) ? intval( $_POST['prop_id'] ) : '';
				$cart_item_meta['price_property'] = isset( $_POST['price_property'] ) ? floatval( $_POST['price_property'] ) : '';

			endif;

		  return $cart_item_meta; 
		}



		//Get it from the session and add it to the cart variable
		function get_cart_items_from_session( $item, $values, $key ) {

			if ( isset( $values['package_order_id'] ) && $values['package_order_id'] ) :

			    if ( array_key_exists( 'package_order_id', $values ) )
			        $item[ 'package_order_id' ] = $values['package_order_id'];

			    if ( array_key_exists( 'package_recurring_payment', $values ) )
			        $item[ 'package_recurring_payment' ] = $values['package_recurring_payment'];

			    if ( array_key_exists( 'package_agent_id', $values ) )
			        $item[ 'package_agent_id' ] = $values['package_agent_id'];
				
				if ( array_key_exists( 'package_agent_price', $values ) )
			        $item[ 'package_agent_price' ] = $values['package_agent_price'];

			endif;

			if ( isset( $values['prop_id'] ) && $values['prop_id'] ) :

				if ( array_key_exists( 'prop_id', $values ) )
			        $item[ 'prop_id' ] = $values['prop_id'];

			    if ( array_key_exists( 'price_property', $values ) )
			        $item[ 'price_property' ] = $values['price_property'];

			endif;
		    return $item;
		}
		add_filter( 'woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 1, 3 );

		/* -------------------------------------------------------
		 * Create functions noo_custom_checkout_fields
		 * ------------------------------------------------------- */

		if ( ! function_exists( 'noo_custom_checkout_fields' ) ) :
			add_filter( 'woocommerce_checkout_fields' , 'noo_custom_checkout_fields' );
			function noo_custom_checkout_fields( $fields ) {
				//error_reporting(0);
				global $woocommerce;
				echo '<style>#package_order_id, #package_recurring_payment, #package_agent_id, #package_id, #submisson_agent_id, #submisson_prop_id, #submisson_order_id, #submisson_payment_type {display: none;}</style>';
				$fields['billing']['package_id']['default'] = '';
				$fields['billing']['package_order_id']['default'] = '';
				// $fields['billing']['package_recurring_payment']['default'] = '';
				$fields['billing']['package_agent_id']['default'] = '';


				$fields['billing']['submisson_agent_id']['default'] = '';
				$fields['billing']['submisson_prop_id']['default'] = '';
				$fields['billing']['submisson_order_id']['default'] = '';
				$fields['billing']['submisson_payment_type']['default'] = '';
			    return $fields;
			}

		endif;

		/** ====== END noo_custom_checkout_fields ====== **/

		/* -------------------------------------------------------
		 * Create functions noo_checkout_fields_order_meta
		 * Update the order meta with field value
		 * ------------------------------------------------------- */

		if ( ! function_exists( 'noo_checkout_fields_order_meta' ) ) :
			add_action( 'woocommerce_checkout_update_order_meta', 'noo_checkout_fields_order_meta' );
			function noo_checkout_fields_order_meta( $order_id ) {
				global $woocommerce, $post_type;;
				$user_id	= get_current_user_id();
				$agent_id	= get_user_meta($user_id, '_associated_agent_id',true );
				$agent		= get_post( $agent_id );
				
				/* -------------------------------------------------------
				 * Create order Package, create fields package_order_id and set value order id
				 * ------------------------------------------------------- */
					foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
						if ( $cart_item['package_order_id'] ) :
							$billing_type = $cart_item['package_recurring_payment'] ? 'recurring' : 'onetime';
							$package_id = $cart_item['package_order_id'];
							$total_price = floatval( noo_get_post_meta( $package_id, '_noo_membership_price', '' ) );
							
							$package	= get_post( $package_id );

							if( !$agent || !$package ) {
								return false;
							}

							$title = $agent->post_title . ' - Purchase package: ' . $package->post_title;
							// creat order new

						   		$new_order_ID = NooPayment::create_new_order( 'membership', $billing_type, $package_id, $total_price, $agent_id, $title );
						    
						    // update order id in meta

							    update_post_meta( $order_id, '_billing_package_id', sanitize_text_field( $package_id ) );
							    update_post_meta( $order_id, '_billing_package_order_id', sanitize_text_field( $new_order_ID ) );
							    // update_post_meta( $order_id, '_billing_package_recurring_payment', sanitize_text_field( $new_order_ID ) );
							    update_post_meta( $order_id, '_billing_package_agent_id', sanitize_text_field( $agent_id ) );

						endif;

						if ( $cart_item['prop_id'] ) :

							$paid_listing      = (bool) esc_attr( noo_get_post_meta( $cart_item['prop_id'], '_paid_listing', false ) );
							$submit_featured = isset( $_POST['submission_featured'] ) ? (bool) $_POST['submission_featured'] : false;
							$featured        = esc_attr( noo_get_post_meta( $prop_id, '_featured', '' ) ) == 'yes';
							$payment_type    = '';

							$is_featured = $submit_featured && !$featured;
							$is_publish = !$paid_listing;

							if( $is_publish && $is_featured ) {
								$payment_type        = 'both';
							} elseif( $is_publish ) {
								$payment_type		= 'listing';
							} elseif( $is_featured) {
								$payment_type		= 'featured';
							}

							$property	= get_post( $cart_item['prop_id'] );
							if( !$agent || !$property ) {
								return false;
							}

							$title = $agent->post_title . ' - Payment for ' . $property->post_title;

							// creat order new

						   		//( $agent_id, $prop_id,  $total_price, !$paid_listing, $submit_featured && !$featured );
						   		$new_order_ID = NooPayment::create_new_order( $payment_type, '', $cart_item['prop_id'], floatval( $cart_item['price_property'] ), $agent_id, $title);
						    
						    // update order id in meta

							    update_post_meta( $order_id, '_billing_submisson_prop_id', sanitize_text_field( $cart_item['prop_id'] ) );
							    update_post_meta( $order_id, '_billing_submisson_order_id', sanitize_text_field( $new_order_ID ) );
							    update_post_meta( $order_id, '_billing_submisson_agent_id', sanitize_text_field( $agent_id ) );
							    update_post_meta( $order_id, '_billing_submisson_payment_type', sanitize_text_field( $payment_type ) );
							  

						endif;
				   }
			}

		endif;

		/** ====== END noo_checkout_fields_order_meta ====== **/



		/* -------------------------------------------------------
		 * Create functions noo_custom_auto_complete_order
		 * ------------------------------------------------------- */

		if ( ! function_exists( 'noo_custom_auto_complete_order' ) ) :
			add_action( 'woocommerce_order_status_completed', 'noo_custom_auto_complete_order' );
			function noo_custom_auto_complete_order( $order_id ) {
				
				global $woocommerce, $wpdb;
		    	
		    	$package_id = get_post_meta( $order_id, '_billing_package_id', true );
		    	$prop_id = get_post_meta( $order_id, '_billing_submisson_prop_id', true );

		    	if ( $package_id ) :

		    		$package_order_id = get_post_meta( $order_id, '_billing_package_order_id', true );
		    		$agent_id = get_post_meta( $order_id, '_billing_package_agent_id', true );
		     	
		     	endif;

		     	if ( $prop_id ) :

		     		$submisson_order_id = get_post_meta( $order_id, '_billing_submisson_order_id', true );
		     		$submisson_agent_id = get_post_meta( $order_id, '_billing_submisson_agent_id', true );
		     		$submisson_payment_type = get_post_meta( $order_id, '_billing_submisson_payment_type', true );

		     	endif;
		     	if ( !$order_id ) return;
		    
		    	$order = new WC_Order( $order_id );
		    	if ( $package_order_id ) :
		    		$purchase_date = time();

		    			update_post_meta( $package_order_id, '_payment_status', 'completed' );
		    			NooAgent::set_agent_membership( $agent_id, $package_id, $purchase_date );

		    		//exit($package_id);
		    	endif;

		    	if ( $submisson_order_id ) :

		    		update_post_meta( $submisson_order_id, '_payment_status', 'completed' );
		    		NooAgent::set_property_status($submisson_agent_id, $prop_id, $submisson_payment_type);

		    	endif;
		    	$order->update_status( 'completed' );

			}

		endif;

		/** ====== END noo_custom_auto_complete_order ====== **/


		/* -------------------------------------------------------
		 * Create functions noo_custom_auto_refunded_order
		 * ------------------------------------------------------- */

		if ( ! function_exists( 'noo_custom_auto_refunded_order' ) ) :
			add_action( 'woocommerce_order_status_refunded', 'noo_custom_auto_refunded_order' );
			function noo_custom_auto_refunded_order( $order_id ) {
				
				global $woocommerce, $wpdb;
		    	
		    	$package_id = get_post_meta( $order_id, '_billing_package_id', true );
		    	$prop_id = get_post_meta( $order_id, '_billing_submisson_prop_id', true );

		    	if ( $package_id ) :

		    		$package_order_id = get_post_meta( $order_id, '_billing_package_order_id', true );
		    		$agent_id = get_post_meta( $order_id, '_billing_package_agent_id', true );
		     	
		     	endif;

		     	if ( $prop_id ) :

		     		$submisson_order_id = get_post_meta( $order_id, '_billing_submisson_order_id', true );
		     		$submisson_agent_id = get_post_meta( $order_id, '_billing_submisson_agent_id', true );

		     	endif;
		     	if ( !$order_id ) return;
		    
		    	$order = new WC_Order( $order_id );
		    	if ( $package_order_id ) :

		    			update_post_meta( $package_order_id, '_payment_status', 'pending' );
		    			NooAgent::revoke_agent_membership( $agent_id, $package_id );
		    		
		    		//exit($package_id . ' - ' . $agent_id);
		    	endif;


	    		if ( $submisson_order_id ) :

	    			update_post_meta( $submisson_order_id, '_payment_status', 'pending' );
	    			NooAgent::revoke_property_status($submisson_agent_id, $prop_id);

	    		endif;
		    	$order->update_status( 'refunded' );

			}

		endif;

		/** ====== END noo_custom_auto_refunded_order ====== **/

	//endif;
	/** ====== END check type_payment ====== **/

//endif; /** ====== END check active plugin woocommerce ====== **/