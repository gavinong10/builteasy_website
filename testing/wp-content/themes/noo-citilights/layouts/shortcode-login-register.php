<div class="noo-logreg <?php echo $mode; ?>">
	<div class="logreg-container">
		<div class="logreg-content row">
			<?php if( $mode == 'both' || $mode == 'login' ) : ?>
			<div class="login-form <?php echo $col_class; ?>">
				<form method="post" role="form" action="<?php echo wp_login_url(); ?>">
					<div class="logreg-title"><?php _e('Login Form', 'noo'); ?></div>
					<?php if( !empty($login_text) ) : ?>
					<p class="logreg-desc"><?php echo $login_text; ?></p>
					<?php endif; ?>
					<div class="form-message"></div>
					<div class="logreg-content">
						<div class="form-group">
							<label for="log" class="sr-only"><?php _e('Username', 'noo'); ?></label>
							<input type="text" class="form-control" id="log" name="log" class="" placeholder="<?php _e('Username', 'noo'); ?> *" required />
						</div>
						<div class="form-group">
							<label for="pwd" class="sr-only"><?php _e('Password', 'noo'); ?></label>
							<input type="password" class="form-control" id="pwd" name="pwd" class="" placeholder="<?php _e('Password', 'noo'); ?> *" required />
						</div>
					</div>
					<div class="logreg-action">
						<input type="hidden" name="rememberme" value="forever" />
						<input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>">
						<input type="hidden" name="action" value="noo_ajax_login">
						<?php wp_nonce_field('noo_ajax_login', '_noo_login_nonce'); ?>
						<input type="submit" class="btn btn-secondary btn-lg" id="login-submit" value="<?php _e('Login', 'noo'); ?>" />
					</div>
					<?php if( $mode == 'login' && $show_register_link ) : ?>
					<p class="logreg-desc">
					<?php $registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register', 'noo' ) ); ?>
					<?php echo $registration_url . ' | '; ?><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" title="<?php esc_attr_e( 'Password Lost and Found', 'noo' ); ?>"><?php _e( 'Lost your password?', 'noo' ); ?></a>
					</p>
					<?php else : ?>
					<p class="logreg-desc"><?php echo __('Lost your password?', 'noo'); ?> <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo __('Click here to reset', 'noo'); ?></a></p>
					<?php endif; ?>
				</form>
			</div>
			<?php endif; ?>
			<?php if( $mode == 'both' || $mode == 'register' ) : ?>
			<div  class="register-form <?php echo $col_class; ?>">
				<form name="registerform" id="registerform" action="<?php echo wp_registration_url(); ?>" method="post" role="form">
					<div class="logreg-title"><?php _e('Register Form', 'noo'); ?></div>
					<?php if( !empty($register_text) ) : ?>
					<p class="logreg-desc"><?php echo $register_text; ?></p>
					<?php endif; ?>
					<div class="form-message"></div>
					<div class="logreg-content">
						<div class="form-group">
							<label for="user_login" class="sr-only"><?php _e('Username', 'noo'); ?></label>
							<input type="text" class="form-control" id="user_login" name="user_login" class="" placeholder="<?php _e('Username', 'noo'); ?> *" required />
						</div>
						<div class="form-group">
							<label for="user_email" class="sr-only"><?php _e('Your Email', 'noo'); ?></label>
							<input type="email" class="form-control" id="user_email" name="user_email" class="" placeholder="<?php _e('Your Email', 'noo'); ?> *" required />
						</div>
						<?php // is Simple reCAPTCHA active?
						if ( function_exists( 'wpmsrc_display' ) ) { 
							echo wpmsrc_display();
						}
						?>
					</div>
					<div class="logreg-action">
						<input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>">
						<input type="hidden" name="action" value="noo_ajax_register">
						<?php wp_nonce_field('noo_ajax_register', '_noo_register_nonce'); ?>
						<input type="submit" class="btn btn-secondary btn-lg" id="register-submit" value="<?php _e('Register Account', 'noo'); ?>" />
					</div>
				</form>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
