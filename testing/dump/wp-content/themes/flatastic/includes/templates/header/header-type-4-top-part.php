<!-- - - - - - - - - - - - Header Top Part- - - - - - - - - - - - - - -->

<?php ob_start(); ?>

	<?php if ( mad_is_shop_installed() ): ?>

		<?php $accountPage = get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>

		<?php if ( is_user_logged_in() ): ?>

			<?php
				global $current_user;
				get_currentuserinfo();
				$user_name = mad_get_user_name($current_user);
			?>

			<span class="welcome_username"><?php echo _e('Welcome visitor', MAD_BASE_TEXTDOMAIN) . ', ' . $user_name ?></span>

			<strong><a href="<?php echo esc_url($accountPage); ?>"><?php _e('My Account', MAD_BASE_TEXTDOMAIN) ?></a></strong>
			<span> / </span>
			<strong><a href="<?php echo wp_logout_url(esc_url(home_url())) ?>"><?php _e('Logout', MAD_BASE_TEXTDOMAIN) ?></a></strong>

		<?php else: ?>
			<div class="bar-login">
				<a class="to-login" data-href="<?php echo esc_attr($accountPage) ?>" href="<?php echo esc_url($accountPage); ?>"><?php _e('Login', MAD_BASE_TEXTDOMAIN); ?></a>
				<span> / </span>
				<a href="<?php echo esc_url($accountPage); ?>"><?php _e('Register', MAD_BASE_TEXTDOMAIN); ?></a>
			</div><!--/ .bar-login-->
		<?php endif; ?>

	<?php else: ?>

		<?php if ( is_user_logged_in() ): ?>

			<?php
				global $current_user;
				get_currentuserinfo();
				$user_name = mad_get_user_name($current_user);
			?>

			<span class="welcome_username"><?php echo _e('Welcome visitor', MAD_BASE_TEXTDOMAIN) . ', ' . esc_html($user_name) ?></span>

			<a href="<?php echo wp_logout( esc_url(home_url()) ); ?>"><?php _e('Logout', MAD_BASE_TEXTDOMAIN) ?></a>

		<?php else: ?>

			<a href="<?php echo wp_login_url(); ?>"><?php _e('Login', MAD_BASE_TEXTDOMAIN) ?></a>

			<?php echo wp_register('', '', false); ?>

		<?php endif; ?>

	<?php endif; ?>

<?php $output = ob_get_clean(); ?>

<?php if (mad_custom_get_option('header_top_part') == 'show'): ?>

	<div class="h_top_part">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-5 t_xs_align_c">

					<?php if (mad_is_shop_installed()): ?>

						<?php $accountPage = get_permalink(get_option('woocommerce_myaccount_page_id')); ?>

						<?php if (is_user_logged_in()): ?>

							<p>
								<?php
								global $current_user;
								get_currentuserinfo();
								$user_name = mad_get_user_name($current_user);
								?>

								<span class="welcome_username"><?php echo _e('Welcome visitor', MAD_BASE_TEXTDOMAIN) . ', ' . $user_name ?></span>
								<strong><a href="<?php echo esc_url($accountPage); ?>"><?php _e('My Account', MAD_BASE_TEXTDOMAIN) ?></a></strong>
								<strong><a href="<?php echo wp_logout_url(esc_url(home_url())) ?>"><?php _e('Logout', MAD_BASE_TEXTDOMAIN) ?></a></strong>
							</p>

						<?php else: ?>

							<div class="bar-login">
								<a class="to-login" data-href="<?php echo esc_url($accountPage) ?>" href="<?php echo esc_url($accountPage); ?>"><?php _e('Login', MAD_BASE_TEXTDOMAIN); ?></a>
								<span> / </span>
								<a href="<?php echo esc_url($accountPage); ?>"><?php _e('Register', MAD_BASE_TEXTDOMAIN); ?></a>
							</div>

						<?php endif; ?>

					<?php else: ?>

						<?php if (is_user_logged_in()): ?>

							<p>
								<?php
								global $current_user;
								get_currentuserinfo();
								$user_name = mad_get_user_name($current_user);
								?>

								<span class="welcome_username"><?php echo _e('Welcome visitor', MAD_BASE_TEXTDOMAIN) . ', ' . esc_html($user_name) ?></span>
								<a href="<?php echo wp_logout_url( esc_url(home_url()) ); ?>"><?php _e('Logout', MAD_BASE_TEXTDOMAIN) ?></a>
							</p>

						<?php else: ?>

							<p>
								<a href="<?php echo wp_login_url(); ?>"><?php _e('Login', MAD_BASE_TEXTDOMAIN) ?></a>
								<?php echo wp_register('', '', false); ?>
							</p>

						<?php endif; ?>

					<?php endif; ?>

				</div>
				<div class="col-md-4 col-sm-5 t_align_c t_xs_align_c">
					<span class="top-message">
						<?php echo mad_custom_get_option('promo_message', __('Promo message will be shown here!', MAD_BASE_TEXTDOMAIN), true) ?>
					</span>
				</div>
				<div class="col-md-4 col-sm-2 t_align_r t_xs_align_c">
					<?php if (mad_custom_get_option('show_call_us')): ?>
						<p><?php echo mad_custom_get_option('call_us', __('Call us toll free: <b>(123) 456-7890</b>', MAD_BASE_TEXTDOMAIN), true); ?></p>
					<?php endif; ?>
				</div>
			</div><!--/ .row-->
		</div><!--/ .container-->
	</div><!--/ .h_top_part-->

	<!-- - - - - - - - - - - - Header Bottom Part- - - - - - - - - - - - - - -->

<?php endif; ?>