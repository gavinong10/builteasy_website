<?php

class NSU_Checkbox {

	/**
	 * @var bool
	 */
	private $showed_checkbox = false;

	public function __construct() {

		$options = NSU::instance()->get_options();
		$opts    = $this->options = $options['checkbox'];

		// add hooks

		if ( $opts['add_to_comment_form'] == 1 ) {
			add_action( 'thesis_hook_after_comment_box', array( $this, 'output_checkbox' ), 20 );
			add_action( 'comment_form', array( $this, 'output_checkbox' ), 20 );
			add_action( 'comment_post', array( $this, 'grab_email_from_comment' ), 20, 2 );
		}

		if ( $opts['add_to_registration_form'] == 1 ) {
			add_action( 'register_form', array( $this, 'output_checkbox' ), 20 );
			add_action( 'register_post', array( $this, 'grab_email_from_wp_signup' ), 50 );
		}

		if ( $opts['add_to_buddypress_form'] == 1 ) {
			add_action( 'bp_before_registration_submit_buttons', array( $this, 'output_checkbox' ), 20 );
			add_action( 'bp_complete_signup', array( $this, 'grab_email_from_wp_signup' ), 20 );
		}

		if ( $opts['add_to_multisite_form'] == 1 ) {
			add_action( 'signup_extra_fields', array( $this, 'output_checkbox' ), 20 );
			add_action( 'signup_blogform', array( $this, 'add_hidden_checkbox' ), 20 );
			add_filter( 'add_signup_meta', array( $this, 'add_checkbox_to_usermeta' ) );
			add_action( 'wpmu_activate_blog', array( $this, 'grab_email_from_ms_blog_signup' ), 20, 5 );
			add_action( 'wpmu_activate_user', array( $this, 'grab_email_from_ms_user_signup' ), 20, 3 );
		}

		/* bbPress actions */
		if ( $opts['add_to_bbpress_forms'] ) {
			add_action( 'bbp_theme_after_topic_form_subscriptions', array( $this, 'output_checkbox' ), 10 );
			add_action( 'bbp_theme_after_reply_form_subscription', array( $this, 'output_checkbox' ), 10 );
			add_action( 'bbp_theme_anonymous_form_extras_bottom', array( $this, 'output_checkbox' ), 10 );
			add_action( 'bbp_new_topic', array( $this, 'subscribe_from_bbpress_new_topic' ), 10, 4 );
			add_action( 'bbp_new_reply', array( $this, 'subscribe_from_bbpress_new_reply' ), 10, 5 );
		}

	}


	/**
	 * Outputs the sign-up checkbox, will only run once.
	 *
	 * @return bool
	 */
	public function output_checkbox() {
		$opts = $this->options;

		// If using option to hide checkbox for subscribers and cookie is set, set instance variable showed_checkbox to true so checkbox won't show.
		if ( $opts['cookie_hide'] == 1 && isset( $_COOKIE['ns_subscriber'] ) ) {
			$this->showed_checkbox = true;
		}

		// User could have rendered the checkbox by manually adding 'the hook 'ns_comment_checkbox()' to their comment form
		// If so, abandon function.
		if ( $this->showed_checkbox ) {
			return false;
		}

		?>
		<!-- Checkbox by Newsletter Sign-Up Checkbox v<?php echo NSU_VERSION_NUMBER; ?> - https://wordpress.org/plugins/newsletter-sign-up/ -->
		<p id="nsu-checkbox">
			<label for="nsu-checkbox-input" id="nsu-checkbox-label">
				<input value="1" id="nsu-checkbox-input" type="checkbox" name="newsletter-sign-up-do" <?php checked( $opts['precheck'], 1 ); ?> />
				<?php _e( $opts['text'], 'newsletter-sign-up' ); ?>
			</label>
		</p>
		<!-- / Newsletter Sign-Up -->
		<?php

		// make sure checkbox doesn't show again
		$this->showed_checkbox = true;

		return true;
	}

	/**
	 * Adds a hidden checkbox to the second page of the MultiSite sign-up form (the blog sign-up form) containing the checkbox value of the previous screen
	 */
	function add_hidden_checkbox() {
		?>
		<input type="hidden" name="newsletter-sign-up-do" value="<?php echo ( isset( $_POST['newsletter-sign-up-do'] ) ) ? 1 : 0; ?>" />
	<?php
	}

	/**
	 * Save the value of the checkbox to MultiSite sign-ups table
	 */
	function add_checkbox_to_usermeta( $meta ) {
		$meta['newsletter-sign-up-do'] = ( isset( $_POST['newsletter-sign-up-do'] ) ) ? 1 : 0;

		return $meta;
	}

	/**
	 * Perform the sign-up for users that registered trough a MultiSite register form
	 * This function differs because of the need to grab the emailadress from the user using get_userdata
	 *
	 * @param int    $user_id  : the ID of the new user
	 * @param string $password : the password, we don't actually use this
	 * @param array  $meta     : the meta values that belong to this user, holds the value of our 'newsletter-sign-up' checkbox.
	 */
	public function grab_email_from_ms_user_signup( $user_id, $password = null, $meta = null ) {

		// only add meta if sign-up checkbox was checked
		if ( ! isset( $meta['newsletter-sign-up-do'] ) || $meta['newsletter-sign-up-do'] != 1 ) {
			return;
		}
		$user_info = get_userdata( $user_id );

		$email = $user_info->user_email;
		$name  = $user_info->first_name;

		NSU::instance()->send_post_data( $email, $name );
	}

	/**
	 * Perform the sign-up for users that registered trough a MultiSite register form
	 * This function differs because of the need to grab the emailadress from the user using get_userdata
	 *
	 * @param int   $blog_id The id of the new blow
	 * @param int   $user_id The ID of the new user
	 * @param       $a       No idea, seriously.
	 * @param       $b       No idea, seriously.
	 * @param array $meta    The meta values that belong to this user, holds the value of our 'newsletter-sign-up' checkbox.
	 */
	public function grab_email_from_ms_blog_signup( $blog_id, $user_id, $a, $b, $meta ) {

		// only subscribe this user if he checked the sign-up checkbox
		if ( ! isset( $meta['newsletter-sign-up-do'] ) || $meta['newsletter-sign-up-do'] != 1 ) {
			return;
		}

		$user_info = get_userdata( $user_id );

		$email = $user_info->user_email;
		$name  = $user_info->first_name;

		NSU::instance()->send_post_data( $email, $name );
	}

	/**
	 * Grab the emailadress (and name) from a regular WP or BuddyPress sign-up and then send this to mailinglist.
	 */
	function grab_email_from_wp_signup() {

		// only act if checkbox was checked
		if ( $_POST['newsletter-sign-up-do'] != 1 ) {
			return;
		}

		if ( isset( $_POST['user_email'] ) ) {

			// gather emailadress from user who WordPress registered
			$email = sanitize_text_field( $_POST['user_email'] );
			$name  = sanitize_text_field( $_POST['user_login'] );

		} elseif ( isset( $_POST['signup_email'] ) ) {

			// gather emailadress from user who BuddyPress registered
			$email = sanitize_text_field( $_POST['signup_email'] );
			$name  = sanitize_text_field( $_POST['signup_username'] );

		} else {
			return false;
		}

		NSU::instance()->send_post_data( $email, $name );
	}

	/**
	 * Grab the emailadress and name from comment and then send it to mailinglist.
	 *
	 * @param int    $cid     : the ID of the comment
	 * @param object $comment : the comment object, optionally
	 */
	public function grab_email_from_comment( $cid, $comment_approved = '' ) {

		if ( ! isset( $_POST['newsletter-sign-up-do'] ) || $_POST['newsletter-sign-up-do'] != 1 ) {
			return false;
		}

		if ( $comment_approved === 'spam' ) {
			return false;
		}

		// get comment data
		$comment = get_comment( $cid );

		$email = $comment->comment_author_email;
		$name  = $comment->comment_author;

		return NSU::instance()->send_post_data( $email, $name );
	}

	public function subscribe_from_bbpress( $anonymous_data, $user_id ) {
		// only act if sign-up checkbox was checked
		if ( ! isset( $_POST['newsletter-sign-up-do'] ) || $_POST['newsletter-sign-up-do'] != 1 ) {
			return false;
		}

		if ( $anonymous_data ) {

			$email = $anonymous_data['bbp_anonymous_email'];
			$name  = $anonymous_data['bbp_anonymous_name'];

		} elseif ( $user_id ) {

			$user_info = get_userdata( $user_id );
			$email     = $user_info->user_email;
			$name      = $user_info->first_name . ' ' . $user_info->last_name;

		} else {
			return false;
		}

		return NSU::instance()->send_post_data( $email, $name );
	}

	public function subscribe_from_bbpress_new_topic( $topic_id, $forum_id, $anonymous_data, $topic_author ) {
		return $this->subscribe_from_bbpress( $anonymous_data, $topic_author );
	}

	public function subscribe_from_bbpress_new_reply( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author ) {
		return $this->subscribe_from_bbpress( $anonymous_data, $reply_author );
	}

}