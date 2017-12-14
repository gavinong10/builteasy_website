<?php

$defaults = array(
	'title' => 'Newsletter',
	'mailchimp_intro' => 'Sign up to our newsletter and get exclusive deals you wont find anywhere else straight to your inbox!',
);

$instance = wp_parse_args( (array) $instance, $defaults );
$mailchimp_intro = isset( $instance['mailchimp_intro'] ) ? $instance['mailchimp_intro'] : '';
$data_mailchimp_api = mad_custom_get_option('mad_mailchimp_api');

if ( $data_mailchimp_api == '' ) {
	echo __('Please enter your MailChimp API KEY in the theme options panel prior of using this widget.', MAD_BASE_TEXTDOMAIN);
	return;
}

?>
<p>
	<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', MAD_BASE_TEXTDOMAIN) ?></label>
	<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr( $instance['title'] ) ?>" />
</p>

<p>
	<label for="<?php esc_attr_e($this->get_field_id('mailchimp_intro')); ?>"><?php _e('Intro Text :', MAD_BASE_TEXTDOMAIN); ?></label>
	<textarea class="widefat" id="<?php esc_attr_e($this->get_field_id('mailchimp_intro')); ?>" name="<?php esc_attr_e($this->get_field_name('mailchimp_intro')); ?>" cols="35" rows="5"><?php echo esc_textarea($mailchimp_intro); ?></textarea>
</p>