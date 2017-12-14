<?php
if (!class_exists('MAD_FORM_LOGIN')) {

	class MAD_FORM_LOGIN {

		public $href;

		function __construct($href) {
			$this->href = $href;
		}

		public function html() {

			$accountPage = get_permalink( get_option('woocommerce_myaccount_page_id') );

			?>
			<div id="modal-login" class="modal-inner-content modal-login">
				<button class="popup-close"></button>
				<div class="custom-scrollbar modal-product">
					<div class="row">
						<?php echo do_shortcode('[woocommerce_my_account]'); ?>
						<footer class="foot-modal-login">
							<h3 class="foot-modal-title"><?php _e('New Customer?', 'flatastic'); ?></h3>
							<a class="foot-modal-button" href="<?php echo esc_url($accountPage) ?>"><?php _e('Register', 'flatastic'); ?></a>
						</footer><!--/ .foot-modal-login-->
					</div><!--/ .row-->
				</div><!--/ .custom-scrollbar-->
			</div><!--/ .modal-inner-content-->
		<?php
		}
	}

}
