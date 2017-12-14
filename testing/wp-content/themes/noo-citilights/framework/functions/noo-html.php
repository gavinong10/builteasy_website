<?php
/**
 * HTML Functions for NOO Framework.
 * This file contains various functions used for rendering site's small layouts.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Shortcodes
require_once NOO_FRAMEWORK_FUNCTION . '/noo-html-shortcodes.php';

// Featured Content
require_once NOO_FRAMEWORK_FUNCTION . '/noo-html-featured.php';

// Pagination
require_once NOO_FRAMEWORK_FUNCTION . '/noo-html-pagination.php';

if (!function_exists('noo_content_meta')):
	function noo_content_meta() {
		$post_type = get_post_type();

		if ( $post_type == 'post' ) {
			if ((!is_single() && noo_get_option( 'noo_blog_show_post_meta', true ) === false)
				|| (is_single() && noo_get_option( 'noo_blog_post_show_post_meta', true ) === false)) {
				return;
			}
		} elseif ($post_type == 'portfolio_project') {
			if (noo_get_option( 'noo_portfolio_show_post_meta', true ) === false) {
				return;
			}
		}

		$html = array();
		$html[] = '<p class="content-meta">';
		// Author
		$html[] = '<span><i class="nooicon-pencil"></i> ' . get_the_author() . '</span>';
		// Date
		$html[] = '<span>';
		$html[] = '<time class="entry-date" datetime="' . esc_attr(get_the_date('c')) . '">';
		$html[] = '<i class="nooicon-calendar"></i>';
		$html[] = esc_html(get_the_date());
		$html[] = '</time>';
		$html[] = '</span>';
		// Categories
		$categories_html = '';
		$separator = ', ';

		// if (get_post_type() == 'portfolio_project') {
		// 	if (has_term('', 'portfolio_category', NULL)) {
		// 		$categories = get_the_terms(get_the_id() , 'portfolio_category');
		// 		foreach ($categories as $category) {
		// 			$categories_html .= '<a' . ' href="' . get_term_link($category->slug, 'portfolio_category') . '"' . ' title="' . esc_attr(sprintf(__("View all Portfolio Items in: &ldquo;%s&rdquo;", 'noo') , $category->name)) . '">' . '<i class="nooicon-bookmark"></i> ' . $category->name . '</a>' . $separator;
		// 		}
		// 	}
		// } else {
		$categories = get_the_category();
		foreach ($categories as $category) {
			$categories_html.= '<a' . ' href="' . get_category_link($category->term_id) . '"' . ' title="' . esc_attr(sprintf(__("View all posts in: &ldquo;%s&rdquo;", 'noo') , $category->name)) . '">' . '<i class="nooicon-archive"></i> ' . $category->name . '</a>' . $separator;
		}
		// }

		$html[] = '<span>' . trim($categories_html, $separator) . '</span>';
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
			
			$comments_html.= '<span><a' . ' href="' . esc_url(get_comments_link()) . '"' . ' title="' . esc_attr($comment_title) . '"' . ' class="meta-comments">' . '<i class="nooicon-comments"></i>' . $comment_number . '</a></span>';
		}

		$html[] = $comments_html;

		echo implode($html, "\n");
	}
endif;

if (!function_exists('noo_get_readmore_link')):
	function noo_get_readmore_link() {
		return '<a href="' . get_permalink() . '" class="read-more">'
		. __('Read More ', 'noo' ) 
		. '<i class="fa fa-arrow-circle-o-right"></i></a>';
	}
endif;

if (!function_exists('noo_readmore_link')):
	function noo_readmore_link() {
		if( noo_get_option('noo_blog_show_readmore', 1 ) ) {
			echo noo_get_readmore_link();
		} else {
			echo '';
		}
	}
endif;

if (!function_exists('noo_list_comments')):
	function noo_list_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		GLOBAL $post;
		$avatar_size = isset($args['avatar_size']) ? $args['avatar_size'] : 60;
?>
		<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-wrap">
				<div class="comment-img">
					<div class="img-thumbnail">
						<?php echo get_avatar($comment, $avatar_size); ?>
					</div>
					<?php if ($comment->user_id === $post->post_author): ?>
					<div class="ispostauthor">
						<?php _e('Post<br/>Author', 'noo'); ?>
					</div>
					<?php
		endif; ?>
				</div>
				<article id="comment-<?php comment_ID(); ?>" class="comment-block">
					<header class="comment-header">
						<cite class="comment-author"><?php echo get_comment_author_link(); ?></cite>
						<span class="pull-right">
							<?php 
								comment_reply_link(array_merge($args, array(
									'reply_text' => (__('Reply', 'noo') . ' <span class="comment-reply-link-after"><i class="nooicon-reply"></i></span>') ,
									'depth'      => $depth,
									'max_depth'  => $args['max_depth']
								))); 
							?>
						</span>
						<div class="comment-meta">
							<time datetime="<?php echo get_comment_time('c'); ?>">
								<?php echo sprintf(__('%1$s at %2$s', 'noo') , get_comment_date() , get_comment_time()); ?>
							</time>
							<span class="comment-edit">
								<?php edit_comment_link('<i class="nooicon-edit"></i> ' . __('Edit', 'noo')); ?>
							</span>
						</div>
						<?php if ('0' == $comment->comment_approved): ?>
							<p class="comment-pending"><?php _e('Your comment is awaiting moderation.', 'noo'); ?></p>
						<?php
		endif; ?>
					</header>
					<section class="comment-content">
						<?php comment_text(); ?>
					</section>
				</article>
			</div>
		<?php
	}
endif;

if ( ! function_exists( 'noo_portfolio_attributes' ) ) :
	function noo_portfolio_attributes( $post_id = null ) {
		if ( noo_get_option( 'noo_portfolio_enable_attribute', true ) === false) {
			return '';
		}

		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$attributes = get_the_terms( $post_id, 'portfolio_tag' );

		$html = array();
		$html[] = '<ul class="list-unstyled attribute-list">';
		$i=0;
		foreach( $attributes as $attribute ) {
			$html[] = '<li class="'.($i % 2 == 0 ? 'odd':'even').'">';
			$html[] = '<a href="' . get_term_link( $attribute->slug, 'portfolio_tag' ) . '">';
			$html[] = '<i class="nooicon-check"></i>';
			$html[] = $attribute->name;
			$html[] = '</a>';
			$html[] = '</li>';
			$i++;
		};
		$html[] = '</ul>';

		echo implode($html, "\n");
	}
endif;

if ( ! function_exists( 'noo_social_share' ) ) :
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

		$share_title  = noo_get_option( "{$prefix}_social_title", true );
		$facebook     = noo_get_option( "{$prefix}_social_facebook", true );
		$twitter      = noo_get_option( "{$prefix}_social_twitter", true );
		$google		  = noo_get_option( "{$prefix}_social_google", true );
		$pinterest    = noo_get_option( "{$prefix}_social_pinterest", false );
		$linkedin     = noo_get_option( "{$prefix}_social_linkedin", false );
		
		$html = array();

		if ( $facebook || $twitter || $google || $pinterest || $linkedin ) {
			$html[] = '<div class="content-share">';
			if( $share_title !== '' ) {
				$html[] = '<p class="share-title">';
				$html[] = '  ' . $share_title;
				$html[] = '</p>';
			}
			$html[] = '<div class="noo-social social-share">';

			if($facebook) {
				$html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="noo-share"'
							. ' title="' . __( 'Share on Facebook', 'noo' ) . '"'
							. ' onclick="window.open(' 
								. "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
								. ' return false;">';
				$html[] = '<i class="noo-social-facebook"></i>';
				$html[] = '</a>';
			}

			if($twitter) {
				$html[] = '<a href="#share" class="noo-share"'
							. ' title="' . __( 'Share on Twitter', 'noo' ) . '"'
							. ' onclick="window.open('
								. "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
								. ' return false;">';
				$html[] = '<i class="noo-social-twitter"></i></a>';
			}

			if($google) {
				$html[] = '<a href="#share" class="noo-share"'
							. ' title="' . __( 'Share on Google+', 'noo' ) . '"'
								. ' onclick="window.open('
								. "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
								. ' return false;">';
				$html[] = '<i class="noo-social-googleplus"></i></a>';
			}

			if($pinterest) {
				$html[] = '<a href="#share" class="noo-share"'
							. ' title="' . __( 'Share on Pinterest', 'noo' ) . '"'
							. ' onclick="window.open('
								. "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
								. ' return false;">';
				$html[] = '<i class="noo-social-pinterest"></i></a>';
			}

			if($linkedin) {
				$html[] = '<a href="#share" class="noo-share"'
							. ' title="' . __( 'Share on LinkedIn', 'noo' ) . '"'
							. ' onclick="window.open('
								. "'http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}','popupLinkedIn','width=610,height=480,{$popup_attr}');"
								. ' return false;">';
				$html[] = '<i class="noo-social-linkedin"></i></a>';
			}

			$html[] = '</div>'; // .noo-social.social-share
			$html[] = '</div>'; // .share-wrap
		}

		echo implode("\n", $html);
	}
endif;

if (!function_exists('noo_social_icons')):
	function noo_social_icons($position = 'topbar', $direction = '') {
		if ($position == 'topbar') {
			// Top Bar social
		} else {
			// Bottom Bar social
		}
		
		$class  = isset($direction) ? $direction : '';
		$html   = array();
		$html[] = '<div class="noo-social social-icons ' . $class . '">';
		
		$social_list = array(
			'facebook'  => __('Facebook', 'noo') ,
			'twitter'   => __('Twitter', 'noo') ,
			'google'    => __('Google+', 'noo') ,
			'pinterest' => __('Pinterest', 'noo') ,
			'linkedin'  => __('LinkedIn', 'noo') ,
			'rss'       => __('RSS', 'noo') ,
			'youtube'   => __('YouTube', 'noo') ,
			'instagram' => __('Instagram', 'noo') ,
		);
		
		$social_html = array();
		foreach ($social_list as $key => $title) {
			$social = noo_get_option("noo_social_{$key}", '');
			if ($social) {
				$social_html[] = '<a href="' . $social . '" title="' . $title . '" target="_blank">';
				$social_html[] = '<i class="noo-social-' . $key . '"></i>';
				$social_html[] = '</a>';
			}
		}
		
		if(empty($social_html)) {
			$social_html[] = __('No Social Media Link','noo');
		}
		
		$html[] = implode($social_html, "\n");
		$html[] = '</div>';
		
		echo implode($html, "\n");
	}
endif;

if(!function_exists('noo_gototop')):
	function noo_gototop(){
		if( noo_get_option( 'noo_back_to_top', true ) ) {
			echo '<a href="#" class="go-to-top on"><i class="fa fa-angle-up"></i></a>';
		}
		return ;
	}
	// add_action('wp_footer','noo_gototop');
endif;

