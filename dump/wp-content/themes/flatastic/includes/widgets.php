<?php

/*  Register Widget Areas
/* ----------------------------------------------------------------- */

if (!function_exists('mad_widgets_register')) {
	function mad_widgets_register () {
		$mad_widget_args = array(
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-head"><h3 class="widget-title">',
			'after_title' => '</h3></div>'
		);

		// General Widget Area
		register_sidebar(array(
			'name' => 'General Widget Area',
			'id' => 'general-widget-area',
			'description'   => __('For all pages and posts.', MAD_BASE_TEXTDOMAIN),
			'before_widget' => $mad_widget_args['before_widget'],
			'after_widget' => $mad_widget_args['after_widget'],
			'before_title' => $mad_widget_args['before_title'],
			'after_title' => $mad_widget_args['after_title']
		));

		for ($i = 1; $i <= 16; $i++) {
			register_sidebar(array(
				'name' => 'Footer Row - widget ' . $i,
				'id' => 'footer-row-' . $i,
				'before_widget' => $mad_widget_args['before_widget'],
				'after_widget' => $mad_widget_args['after_widget'],
				'before_title' => $mad_widget_args['before_title'],
				'after_title' => $mad_widget_args['after_title']
			));
		}
	}
	add_action('widgets_init', 'mad_widgets_register');
}

/*	Actions
/* ----------------------------------------------------------------- */

if (!function_exists('mad_add_to_mailchimp_list')) {

	add_action('wp_ajax_add_to_mailchimp_list', 'mad_add_to_mailchimp_list');
	add_action('wp_ajax_nopriv_add_to_mailchimp_list', 'mad_add_to_mailchimp_list');

	function mad_add_to_mailchimp_list() {

		check_ajax_referer('ajax-nonce', 'ajax_nonce');

		$_POST = array_map('stripslashes_deep', $_POST);
		$apikey = mad_custom_get_option('mad_mailchimp_api');
		$dc = mad_custom_get_option('mad_mailchimp_center');
		$list_id = mad_custom_get_option('mad_mailchimp_id');
		$email = sanitize_email($_POST['email']);
		$name = sanitize_title($_POST['name']);

		if (empty($name) || $name == null) $name = '';

		$url = "https://$dc.api.mailchimp.com/2.0/lists/subscribe.json";
		$result = array();

		$request = wp_remote_post( $url, array(
			'body' => json_encode( array(
				'apikey' => $apikey,
				'id' => $list_id,
				'email' => array( 'email' => $email ),
				'merge_vars'        => array( 'FNAME' => $name )
			) )
		));

		$data = json_decode(wp_remote_retrieve_body( $request ));

		if (isset($data->error)) {
			$result['status'] = $data->status;
			$result['text'] = $data->error;
			echo json_encode($result);
			exit;
		}

		$result['status'] = 'success';
		$result['text']  = __('You\'ve been added to our sign-up list. We have sent an email, asking you to confirm the same.', MAD_BASE_TEXTDOMAIN);

		echo json_encode($result);
		wp_die();
	}

}

/*	Include Widgets
/* ----------------------------------------------------------------- */

include_once MAD_INCLUDES_PATH . 'widgets/latest-tweets-widget/latest-tweets.php';

if (!function_exists('mad_unregistered_widgets')) {
	function mad_unregistered_widgets () {
		unregister_widget( 'LayerSlider_Widget' );
	}
	add_action('widgets_init', 'mad_unregistered_widgets');
}

/*	Widget Popular Posts
/* ----------------------------------------------------------------- */

if (!class_exists('mad_widget_popular_widget')) {

	class mad_widget_popular_widget extends WP_Widget {

		public $defaults = array();
		public $version = "1.0.1";

		function __construct() {

			parent::__construct( 'popular-widget', strtoupper(MAD_BASE_TEXTDOMAIN) .' '. __('Widget Popular and Latest Posts', MAD_BASE_TEXTDOMAIN),
				array('classname' => 'widget_popular_posts', 'description' => __("Display most popular and latest posts", MAD_BASE_TEXTDOMAIN))
			);

			define('POPWIDGET_URL', MAD_INCLUDES_URI . 'widgets/popular-widget/');
			define('POPWIDGET_ABSPATH', str_replace("\\", "/", dirname(__FILE__) . '/widgets/popular-widget'));

			$this->defaults = array(
				'counter' => false,
				'excerptlength' => 5,
				'meta_key' => '_popular_views',
				'calculate' => 'visits',
				'limit' => 3,
				'thumb' => false,
				'excerpt' => false,
				'type' => 'popular'
			);

			add_action('admin_print_styles', array(&$this, 'load_admin_styles'));
			add_action('wp_enqueue_scripts', array(&$this, 'load_scripts_styles'));
			add_action('wp_ajax_popwid_page_view_count', array(&$this, 'set_post_view'));
			add_action('wp_ajax_nopriv_popwid_page_view_count', array(&$this, 'set_post_view'));
		}

		function widget($args, $instance) {
			if (file_exists(POPWIDGET_ABSPATH . '/inc/widget.php')) {
				include(POPWIDGET_ABSPATH . '/inc/widget.php');
			}
		}

		function form($instance) {
			if (file_exists(POPWIDGET_ABSPATH . '/inc/form.php')) {
				include(POPWIDGET_ABSPATH . '/inc/form.php');
			}
		}

		function update($new_instance, $old_instance) {
			foreach ($new_instance as $key => $val) {
				if (is_array($val)) {
					$new_instance[$key] = $val;
				} elseif (in_array($key, array('limit', 'excerptlength'))) {
					$new_instance[$key] = intval($val);
				} elseif (in_array($key, array('calculate'))) {
					$new_instance[$key] = trim($val, ',');
				}
			}
			if (empty($new_instance['meta_key'])) {
				$new_instance['meta_key'] = $this->defaults['meta_key'];
			}
			return $new_instance;
		}


		function load_admin_styles() {
			global $pagenow;
			if ($pagenow != 'widgets.php' ) return;

			wp_enqueue_style( 'popular-admin', POPWIDGET_URL . 'css/admin.css', NULL, $this->version );
			wp_enqueue_script( 'popular-admin', POPWIDGET_URL . 'js/admin.js', array('jquery',), $this->version, true );
		}

		function load_scripts_styles(){

			if (! is_admin() || is_active_widget( false, false, $this->id_base, true )) {
				wp_enqueue_script('popular-widget', POPWIDGET_URL . 'js/pop-widget.js', array('jquery'), $this->version, true);
			}

			if (! is_singular() && ! apply_filters( 'pop_allow_page_view', false )) return;

			global $post;
			wp_localize_script ( 'popular-widget', 'popwid', apply_filters( 'pop_localize_script_variables', array(
				'postid' => $post->ID
			), $post ));
		}

		function field_id($field) {
			echo $this->get_field_id($field);
		}

		function field_name($field) {
			echo $this->get_field_name($field);
		}

		function limit_words($string, $word_limit) {
			$words = explode(" ", wp_strip_all_tags(strip_shortcodes($string)));

			if ($word_limit && (str_word_count($string) > $word_limit)) {
				return $output = implode(" ",array_splice( $words, 0, $word_limit )) ."...";
			} else if( $word_limit ) {
				return $output = implode(" ", array_splice( $words, 0, $word_limit ));
			} else {
				return $string;
			}
		}

		function get_post_image($post_id, $size) {

			if (has_post_thumbnail($post_id) && function_exists('has_post_thumbnail')) {
				return get_the_post_thumbnail($post_id, $size);
			}

			$images = get_children(array(
				'order' => 'ASC',
				'numberposts' => 1,
				'orderby' => 'menu_order',
				'post_parent' => $post_id,
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
			), $post_id, $size);

			if (empty($images)) return false;

			foreach($images as $image) {
				return wp_get_attachment_image($image->ID, $size);
			}
		}

		function set_post_view() {

			if (empty($_POST['postid'])) return;
			if (!apply_filters('pop_set_post_view', true)) return;

			global $wp_registered_widgets;

			$meta_key_old = false;
			$postid = (int) $_POST['postid'];
			$widgets = get_option($this->option_name);

			foreach ((array) $widgets as $number => $widget) {
				if (!isset($wp_registered_widgets["popular-widget-{$number}"])) continue;

				$instance = $wp_registered_widgets["popular-widget-{$number}"];
				$meta_key = isset( $instance['meta_key'] ) ? $instance['meta_key'] : '_popular_views';

				if ($meta_key_old == $meta_key) continue;

				do_action( 'pop_before_set_pos_view', $instance, $number );

				if (isset($instance['calculate']) && $instance['calculate'] == 'visits') {
					if (!isset( $_COOKIE['popular_views_'.COOKIEHASH])) {
						setcookie( 'popular_views_' . COOKIEHASH, "$postid|", 0, COOKIEPATH );
						update_post_meta( $postid, $meta_key, get_post_meta( $postid, $meta_key, true ) +1 );
					} else {
						$views = explode("|", $_COOKIE['popular_views_' . COOKIEHASH]);
						foreach( $views as $post_id ){
							if( $postid == $post_id ) {
								$exist = true;  break;
							}
						}
					}
					if (empty($exist)) {
						$views[] = $postid;
						setcookie( 'popular_views_' . COOKIEHASH, implode( "|", $views ), 0 , COOKIEPATH );
						update_post_meta( $postid, $meta_key, get_post_meta( $postid, $meta_key, true ) +1 );
					}
				} else {
					update_post_meta( $postid, $meta_key, get_post_meta( $postid, $meta_key, true ) +1 );
				}
				$meta_key_old = $meta_key;
				do_action( 'pop_after_set_pos_view', $instance, $number );
			}
			die();
		}

		function get_latest_posts() {
			extract($this->instance);
			$posts = wp_cache_get("pop_latest_{$number}", 'pop_cache');

			if ($posts == false) {
				$args = array(
					'suppress_fun' => true,
					'post_type' => 'post',
					'posts_per_page' => $limit
				);
				$posts = get_posts(apply_filters('pop_get_latest_posts_args', $args));
				wp_cache_set("pop_latest_{$number}", $posts, 'pop_cache');
			}
			return $this->display_posts($posts);
		}

		function get_most_viewed() {
			extract($this->instance);
			$viewed = wp_cache_get("pop_viewed_{$number}", 'pop_cache');

			if ($viewed == false) {
				global $wpdb;  $join = $where = '';
				$viewed = $wpdb->get_results( $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS p.*, meta_value as views FROM $wpdb->posts p " .
					"JOIN $wpdb->postmeta pm ON p.ID = pm.post_id AND meta_key = %s AND meta_value != '' " .
					"WHERE 1=1 AND p.post_status = 'publish' AND post_date >= '{$this->time}' AND p.post_type IN ( 'post' )" .
					"GROUP BY p.ID ORDER BY ( meta_value+0 ) DESC LIMIT $limit", $meta_key));
				wp_cache_set( "pop_viewed_{$number}", $viewed, 'pop_cache');
			}
			return $this->display_posts($viewed);
		}

		function display_posts($posts) {

			if (empty ($posts) && !is_array($posts)) return;

			$output = '';
			extract( $this->instance );

			foreach ($posts as $key => $post) {
				$output .= '<article class="thumbnails-entry">';

				if (!empty($thumb)) {

					if (has_post_thumbnail($post->ID)) {
						$image = MAD_HELPER::get_the_post_thumbnail($post->ID, '60*60', array('title' => esc_attr( $post->post_title ), 'alt' => esc_attr( $post->post_title )));
					}

					if (isset($image)) {
						$output .= '<a class="entry-thumb-image" href="'. esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( $post->post_title ) . '">';
						$output .= $image;
						$output .= '</a>';
					}
				}

				// title
				$output .=
					'<div class="entry-post-holder">' .
					'<a href="' . esc_url(get_permalink($post->ID)) . '">'.
					'<h6 class="entry-post-title">'. $post->post_title . '</h6></a>' .
					'<span class="entry-post-date">' . get_the_time(get_option('date_format'), $post->ID) . '</span>';

				// counter
				if (!empty($counter) && isset($post->views)) {
					$output .= '<span class="entry-post-count"> (' . preg_replace( "/(?<=\d)(?=(\d{3})+(?!\d))/", ",", $post->views) . ')</span>';
				}

				// excerpt
				if (!empty($excerpt)) {
					if ($post->post_excerpt) {
						$output .= '<p class="entry-post-summary">' . $this->limit_words( ( $post->post_excerpt ), $excerptlength ) . '</p>';
					} else {
						$output .= '<p class="entry-post-summary">' . $this->limit_words( ( $post->post_content ), $excerptlength ) . '</p>';
					}
				}
				$output .= '</div></article>';
			}
			return $output;
		}

	}
}


/*	Widget Social Links
/* ----------------------------------------------------------------- */

if (!class_exists('mad_widget_social_links')) {

	class mad_widget_social_links extends WP_Widget {

		function __construct() {
			$settings = array('classname' => __CLASS__, 'description' => __('Displays website social links', MAD_BASE_TEXTDOMAIN));
			parent::__construct(__CLASS__, strtoupper(MAD_BASE_TEXTDOMAIN) .' '. __('Widget Social Links', MAD_BASE_TEXTDOMAIN), $settings);
		}

		function widget($args, $instance) {

			extract($args, EXTR_SKIP);

			$args['instance'] = $instance;
			$args['before_widget'] = $before_widget;
			$args['after_widget'] = $after_widget;
			$args['before_title'] = $before_title;
			$args['after_title'] = $after_title;
			echo MAD_HELPER::output_html('social_links', $args);
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['facebook_links'] = $new_instance['facebook_links'];
			$instance['twitter_links'] = $new_instance['twitter_links'];
			$instance['gplus_links'] = $new_instance['gplus_links'];
			$instance['rss_links'] = $new_instance['rss_links'];
			$instance['pinterest_links'] = $new_instance['pinterest_links'];
			$instance['instagram_links'] = $new_instance['instagram_links'];
			$instance['linkedin_links'] = $new_instance['linkedin_links'];
			$instance['vimeo_links'] = $new_instance['vimeo_links'];
			$instance['youtube_links'] = $new_instance['youtube_links'];
			$instance['flickr_links'] = $new_instance['flickr_links'];
			$instance['vk_links'] = $new_instance['vk_links'];
			$instance['contact_us'] = $new_instance['contact_us'];
			return $instance;
		}

		function form($instance) {
			$defaults = array(
				'title' => 'Social Links',
				'facebook_links' => 'http://www.facebook.com',
				'twitter_links' => 'https://twitter.com',
				'gplus_links' => 'http://plus.google.com/',
				'rss_links' => 'false',
				'pinterest_links' => 'https://www.pinterest.com/',
				'instagram_links' => 'http://instagram.com',
				'linkedin_links' => 'http://linkedin.com/',
				'vimeo_links' => 'https://vimeo.com/',
				'youtube_links' => 'https://youtube.com/',
				'flickr_links' => 'https://www.flickr.com/',
				'vk_links' => 'https://www.vk.com/',
				'contact_us' => 'your@mail.com',
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			$args = array();
			$args['instance'] = $instance;
			$args['widget'] = $this;
			echo MAD_HELPER::output_html('social_links_form', $args);
		}

	}
}

/*	Widget Advertising Area
/* ----------------------------------------------------------------- */

if (!class_exists('mad_widget_advertising_area')) {

	class mad_widget_advertising_area extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname' => 'widget_advertising_area',
				'description' => 'An advertising widget that displays image'
			);
			parent::__construct( __CLASS__,  strtoupper(MAD_BASE_TEXTDOMAIN).' '. __('Advertising Area', MAD_BASE_TEXTDOMAIN), $widget_ops );
		}

		function widget($args, $instance) {

			extract($args, EXTR_SKIP);

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

			if (empty($instance['image_url'])) {
				$image_url = '<span>'.__('Advertise here', MAD_BASE_TEXTDOMAIN).'</span>';
			} else {
				$image_url = '<img class="advertise-image" src="' . esc_url($instance['image_url']) . '" title="" alt=""/>';
			}

			$ref_url = empty($instance['ref_url']) ? '#' : apply_filters('widget_comments_title', $instance['ref_url']);

			ob_start(); ?>

			<?php echo $before_widget; ?>

				<?php if ($title !== ''): ?>
					<?php echo $before_title . $title . $after_title; ?>
				<?php endif; ?>

				<a target="_blank" href="<?php echo esc_url($ref_url); ?>"><?php echo $image_url; ?></a>

			<?php echo $after_widget; ?>

			<?php echo ob_get_clean();
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			foreach($new_instance as $key => $value) {
				$instance[$key]	= strip_tags($new_instance[$key]);
			}
			return $instance;
		}

		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array(
				'title' => '',
				'image_url' => '',
				'ref_url' => '',
			));
			$title = strip_tags($instance['title']);
			$image_url = strip_tags($instance['image_url']);
			$ref_url = strip_tags($instance['ref_url']); ?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('image_url'); ?>">Image URL: <?php if($this->add_cont == 2) echo "(125px * 125px):"; ?>
					<input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo esc_attr($image_url); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('ref_url'); ?>">Referal URL:
					<input class="widefat" id="<?php echo $this->get_field_id('ref_url'); ?>" name="<?php echo $this->get_field_name('ref_url'); ?>" type="text" value="<?php echo esc_attr($ref_url); ?>" />
				</label>
			</p>

		<?php
		}
	}
}

/*	Widget Contact Us
/* ----------------------------------------------------------------- */

if (!class_exists('mad_widget_contact_us')) {

	class mad_widget_contact_us extends WP_Widget {

		function __construct() {
			$settings = array('classname' => 'widget_contact_us', 'description' => __('Displays contact us', MAD_BASE_TEXTDOMAIN));
			parent::__construct( __CLASS__, strtoupper(MAD_BASE_TEXTDOMAIN) .' '. __('Widget Contact Us', MAD_BASE_TEXTDOMAIN), $settings );
		}

		function widget($args, $instance) {
			extract($args, EXTR_SKIP);

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$address = empty($instance['address']) ? '' : $instance['address'];
			$phone = empty($instance['phone']) ? '' : $instance['phone'];
			$email = empty($instance['email']) ? '' : $instance['email'];
			$hours = empty($instance['hours']) ? '' : $instance['hours'];

			ob_start(); ?>

			<?php echo $before_widget; ?>

			<?php if ($title !== ''): ?>
				<?php echo $before_title . $title . $after_title; ?>
			<?php endif; ?>

			<ul class="info-list">

				<?php if (!empty($address)): ?>
					<li>
						<div class="clearfix">
							<i class="fa fa-map-marker"></i>
							<span class="over"><?php echo $address ?></span>
						</div>
					</li>
				<?php endif; ?>

				<?php if (!empty($phone)): ?>
					<li>
						<div class="clearfix">
							<i class="fa fa-phone"></i>
							<span class="over"><?php echo $phone ?></span>
						</div>
					</li>
				<?php endif; ?>

				<?php if (!empty($email)): ?>
					<li>
						<div class="clearfix">
							<i class="fa fa-envelope"></i>
							<a target="_blank" class="over" href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
						</div>
					</li>
				<?php endif; ?>

				<?php if (!empty($hours)): ?>
					<li>
						<div class="clearfix">
							<i class="fa fa-clock-o"></i>
							<span class="over"><?php echo $hours ?></span>
						</div>
					</li>
				<?php endif; ?>

			</ul><!--/ .info-list-->

			<?php echo $after_widget; ?>

			<?php echo ob_get_clean();
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			foreach($new_instance as $key => $value) {
				if ($key == 'hours') { $instance[$key] = $new_instance[$key]; continue; }
				$instance[$key]	= strip_tags($new_instance[$key]);
			}
			return $instance;
		}

		function form($instance) {
			$defaults = array(
				'title' => 'Contact Us',
				'address' => '8901 Marmora Road, Glasgow, D04 89GR.',
				'phone' => '800-559-65-80',
				'email' => 'info@companyname.com',
				'hours' => 'Monday - Friday: 08.00-20.00 <br> Saturday: 09.00-15.00 <br> Sunday: closed'
			);
			$instance = wp_parse_args( (array) $instance, $defaults );
			?>

			<p>
				<label><?php _e('Title', MAD_BASE_TEXTDOMAIN);?>:
					<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
				</label>
			</p>

			<p>
				<label><?php _e('Address', MAD_BASE_TEXTDOMAIN);?>:
					<input id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" value="<?php echo $instance['address']; ?>" class="widefat" type="text"/>
				</label>
			</p>

			<p>
				<label><?php _e('Phone', MAD_BASE_TEXTDOMAIN);?>:
					<input id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" class="widefat" type="text"/>
				</label>
			</p>

			<p>
				<label><?php _e('E-mail', MAD_BASE_TEXTDOMAIN);?>:
					<input id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" class="widefat" type="text"/>
				</label>
			</p>

			<p>
				<label><?php _e('Working hours', MAD_BASE_TEXTDOMAIN);?>:
					<input id="<?php echo $this->get_field_id( 'hours' ); ?>" name="<?php echo $this->get_field_name( 'hours' ); ?>" value="<?php echo $instance['hours']; ?>" class="widefat" type="text"/>
				</label>
			</p>

		<?php
		}

	}
}

/*	Mailchimp Widget
/* ----------------------------------------------------------------- */

if (!class_exists('mad_widget_mailchimp')) {

	class mad_widget_mailchimp extends WP_Widget {
		public $data = '';
		public $version = '1.0';

		function __construct() {
			$settings = array('classname' => 'widget_zn_mailchimp', 'description' => __('Use this widget to add a mailchimp newsletter to your site.', MAD_BASE_TEXTDOMAIN));
			parent::__construct('widget-zn-mailchimp', strtoupper(MAD_BASE_TEXTDOMAIN) .' '. __('Widget Newsletter', MAD_BASE_TEXTDOMAIN), $settings);

			define('MAILCHIMP_URL', MAD_INCLUDES_URI . 'widgets/mailchimp/');
			define('MAILCHIMP_ABSPATH', str_replace("\\", "/", dirname(__FILE__) . '/widgets/mailchimp'));

			add_action('wp_enqueue_scripts', array(&$this, 'load_script'));
		}

		function load_script() {
			wp_enqueue_script('jquery-form');

			if ( is_active_widget( false, false, 'widget-zn-mailchimp', true ) && ! is_admin() ) {
				wp_enqueue_script( 'newsletter-widget', MAILCHIMP_URL . 'js/newsletter.js', array('jquery'), $this->version, true );
			}
		}

		function widget($args, $instance) {
			if (file_exists(MAILCHIMP_ABSPATH . '/inc/widget.php')) {
				include(MAILCHIMP_ABSPATH . '/inc/widget.php');
			}
		}

		function update( $new_instance, $old_instance ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
			$instance['mailchimp_intro'] =  stripslashes($new_instance['mailchimp_intro']) ;
			return $instance;
		}

		function form( $instance ) {
			if (file_exists(MAILCHIMP_ABSPATH . '/inc/form.php')) {
				include(MAILCHIMP_ABSPATH . '/inc/form.php');
			}
		}
	}
}

/*	Widget Flickr
/* ----------------------------------------------------------------- */

if (!class_exists('mad_widget_flickr')) {

	class mad_widget_flickr extends WP_Widget {

		function __construct() {
			$settings = array('classname' => 'widget_flickr', 'description' => __('Flickr feed widget', MAD_BASE_TEXTDOMAIN));
			parent::__construct(__CLASS__,  strtoupper(MAD_BASE_TEXTDOMAIN) .' '. __('Widget Flickr feed', MAD_BASE_TEXTDOMAIN), $settings);
		}

		function widget($args, $instance) {
			extract($args, EXTR_SKIP);

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$unique_id = rand(0, 300);

			MAD_BASE_FUNCTIONS::enqueue_script('jflickrfeed');

			echo $before_widget;

			if ($title !== '') {
				echo $before_title . $title . $after_title;
			}

			?>

			<ul id="flickr_feed_<?php echo $unique_id ?>" class="flickr-feed"></ul>

			<script type="text/javascript">
				jQuery(function () {
					jQuery('#flickr_feed_<?php echo $unique_id ?>').jflickrfeed({
						limit: <?php echo $instance['imagescount'] ?>,
						qstrings: { id: '<?php echo $instance['username'] ?>' },
						itemTemplate: '<li><a class="jackbox" data-group="flickr" target="_blank" href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
					}, function () {
						if (jQuery('.jackbox[data-group=flickr]').length) {
							jQuery.jackBox.available(function() {
								jQuery(".jackbox[data-group=flickr]").each(function() {
									jQuery(this).jackBox("newItem");
								});
								jQuery(".jackbox[data-group=flickr]").jackBox("init");
							});
						}
					});
				});
			</script>

			<?php echo $after_widget;
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['username'] = $new_instance['username'];
			$instance['imagescount'] = (int) $new_instance['imagescount'];
			return $instance;
		}

		function form($instance) {
			$defaults = array(
				'title' => 'Flickr Feed',
				'username' => '76745153@N04',
				'imagescount' => '8',
			);
			$instance = wp_parse_args((array) $instance, $defaults); ?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', MAD_BASE_TEXTDOMAIN) ?>:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Flickr Username', MAD_BASE_TEXTDOMAIN) ?>:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo $instance['username']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('imagescount'); ?>"><?php _e('Number of images', MAD_BASE_TEXTDOMAIN) ?>:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('imagescount'); ?>" name="<?php echo $this->get_field_name('imagescount'); ?>" value="<?php echo $instance['imagescount']; ?>" />
			</p>

		<?php
		}

	}
}

add_action('widgets_init', create_function('', 'return register_widget("mad_widget_popular_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("mad_widget_social_links");'));
add_action('widgets_init', create_function('', 'return register_widget("mad_widget_advertising_area");'));
add_action('widgets_init', create_function('', 'return register_widget("mad_widget_contact_us");'));
add_action('widgets_init', create_function('', 'return register_widget("mad_widget_mailchimp");'));
add_action('widgets_init', create_function('', 'return register_widget("mad_widget_flickr");'));

?>