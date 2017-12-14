<?php
	extract($args, EXTR_SKIP);
	$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
?>

<?php echo $before_widget; ?>

<?php if ($title !== ''): ?>
	<?php echo $before_title . $title . $after_title; ?>
<?php endif; ?>

	<form class="mailchimp-newsletter" action="#" method="POST" >
		<?php
			if ( !empty($instance['mailchimp_intro']) ) {
				echo '<div id="mailchimp-sign-up" class="mailchimp-sign-up"><p>' . $instance['mailchimp_intro'] . '</p></div>';
			}
		?>
		<input  id="s-email" type="text" name="email" placeholder="<?php _e('Enter your email address', MAD_BASE_TEXTDOMAIN); ?>">
		<button id="signup-submit" name="newsletter-submit" type="submit"><?php _e("Subscribe", MAD_BASE_TEXTDOMAIN); ?></button>
		<p class="response"></p>
	</form>

<?php echo $after_widget; ?>