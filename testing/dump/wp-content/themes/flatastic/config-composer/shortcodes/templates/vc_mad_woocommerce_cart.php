<?php

class WPBakeryShortCode_VC_mad_woocommerce_cart extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => ''
		), $atts, 'vc_mad_woocommerce_cart');

		$html = $this->html();

		return $html;
	}

	protected function entry_title($title) {
		return "<h2 class='section-title m_bottom_25'>". $title ."</h2>";
	}

	function get_terms() {
		?>

		<h2 class="section-title m_bottom_30">
			<?php _e('Terms of service', MAD_BASE_TEXTDOMAIN) ?>
		</h2>

		<div class="col2-set">
			<?php
			$checkout_page_id = wc_get_page_id( 'terms' );
			$content_post = get_post($checkout_page_id);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			echo $content;
			?>
		</div><!--/ .col2-set-->

		<?php
	}

	public function html() {

		extract($this->atts);

		ob_start() ?>

		<?php echo (!empty($title)) ? $this->entry_title($title): ""; ?>

		 <?php wc_print_notices(); ?>

		<?php do_shortcode('[woocommerce_cart]') ?>
		<?php //do_shortcode('[woocommerce_checkout]') ?>

		 <?php do_action( 'woocommerce_before_cart' ); ?>

		<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<table class="shop_table cart" cellspacing="0">

				<thead>
					<tr>
						<th><?php _e( 'Product Image & Name', MAD_BASE_TEXTDOMAIN ); ?></th>
						<th><?php _e( 'SKU', MAD_BASE_TEXTDOMAIN ); ?></th>
						<th><?php _e( 'Price', MAD_BASE_TEXTDOMAIN ); ?></th>
						<th><?php _e( 'Quantity', MAD_BASE_TEXTDOMAIN ); ?></th>
						<th><?php _e( 'Subtotal', MAD_BASE_TEXTDOMAIN ); ?></th>
					</tr>
				</thead>

				<tbody>

				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

						$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							?>
							<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

								<td class="product-thumbnail">
									<?php

									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array('110', '110')), $cart_item, $cart_item_key );

									if ( ! $_product->is_visible() ) {
										echo '<div class="thumbnail">';
											echo $thumbnail;
										echo '</div>';
									} else {
										echo '<div class="thumbnail">';
											printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
										echo '</div>';
									}
									?>

									<?php
									if ( ! $_product->is_visible() ) {
										echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
									} else {
										echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="product-name" href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );
									}

									// Meta data
									echo WC()->cart->get_item_data( $cart_item );

									// Backorder notification
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
										echo '<p class="backorder_notification">' . __( 'Available on backorder', MAD_BASE_TEXTDOMAIN ) . '</p>';
									?>

								</td>

								<td class="product-sku">
									<?php echo ($sku = $_product->get_sku()) ? $sku : __('N/A', MAD_BASE_TEXTDOMAIN); ?>
								</td>

								<td class="product-price">
									<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
								</td>

								<td class="product-quantity">

									<?php
										if ( $_product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
										} else {
											$product_quantity = woocommerce_quantity_input( array(
												'input_name'  => "cart[{$cart_item_key}][qty]",
												'input_value' => $cart_item['quantity'],
												'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
												'min_value'   => '0'
											), $_product, false );
										}

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
									?>

									<div class="clear"></div>

									<label class="update">
										<input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', MAD_BASE_TEXTDOMAIN ); ?>" />
									</label>

									<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">%s</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove', MAD_BASE_TEXTDOMAIN ), __( 'Remove', MAD_BASE_TEXTDOMAIN ) ), $cart_item_key ); ?>

								</td>

								<td class="product-subtotal">
									<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
								</td>

							</tr>
						<?php
						}
					}
					?>

				<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
					<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
						<td colspan="4">
							<div class="sub-title"><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
						</td>
						<td colspan="1">
							<div class="cart-subtitle">
								<?php wc_cart_totals_coupon_html( $coupon ); ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>

				<tr>
					<td colspan="4">
						<div class="sub-title">
							<?php _e( 'Cart Subtotal:', MAD_BASE_TEXTDOMAIN ); ?>
						</div>
					</td>
					<td colspan="1">
						<div class="cart-subtitle">
							<?php wc_cart_totals_subtotal_html(); ?>
						</div>
					</td>
				</tr>

				<?php if ( WC()->cart->tax_display_cart == 'excl' ) : ?>
					<tr>
						<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
							<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
								<td colspan="4" class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
									<div class="sub-title">
										<?php _e( 'Tax Total:', MAD_BASE_TEXTDOMAIN ); ?>
									</div>
								</td>
								<td colspan="1">
									<div class="cart-subtitle">
										<?php echo esc_html( $tax->label ); ?>
										<?php echo wp_kses_post( $tax->formatted_amount ); ?>
									</div>
								</td>
							<?php endforeach; ?>
						<?php else: ?>
							<td colspan="4">
								<div class="sub-title">
									<?php _e( 'Tax Total:', MAD_BASE_TEXTDOMAIN ); ?>
								</div>
							</td>
							<td colspan="1">
								<div class="cart-subtitle">
									<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
									<?php echo wc_cart_totals_taxes_total_html(); ?>
								</div>
							</td>
						<?php endif; ?>
					</tr>
				<?php endif; ?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<tr>
					<td colspan="4" class="actions">
						<?php if ( WC()->cart->coupons_enabled() ) : ?>
							<div class="coupon">

								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Enter you coupon code', MAD_BASE_TEXTDOMAIN ); ?>" />
								<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', MAD_BASE_TEXTDOMAIN ); ?>" />

								<?php do_action('woocommerce_cart_coupon'); ?>

							</div><!--/ .coupon-->
						<?php endif; ?>

						<span class="sub-title cart-total-color">
							<?php _e( 'Total:', MAD_BASE_TEXTDOMAIN ); ?>
						</span>
					</td>

					<td colspan="1">
						<div class="cart-subtitle cart-total-color">
							<?php wc_cart_totals_order_total_html(); ?>
						</div>
					</td>

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>

				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>

			</tbody>
		</table>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

		</form>


		<?php do_action( 'woocommerce_after_cart' ); ?>


		<?php $checkout = new WC_Checkout(); ?>

		<form name="checkout" action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" class="checkout" method="post">

			<h2 class="section-title m_bottom_25">
				<?php _e('Bill to & shipment information', MAD_BASE_TEXTDOMAIN) ?>
			</h2>

			<div class="col2-set">

				<div class="row">

					<div class="col-sm-6">

						<div class="woocommerce-billing-fields">
							<?php if ( WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

								<h5 class="form-field-title"><?php _e( 'Billing &amp; Shipping', MAD_BASE_TEXTDOMAIN ); ?></h5>

							<?php else : ?>

								<h5 class="form-field-title"><?php _e( 'Billing Details', MAD_BASE_TEXTDOMAIN ); ?></h5>

							<?php endif; ?>

							<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

							<?php foreach ( $checkout->checkout_fields['billing'] as $key => $field ) : ?>

								<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

							<?php endforeach; ?>

							<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>

							<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

								<?php if ( $checkout->enable_guest_checkout ) : ?>

									<p class="form-row form-row-wide create-account">
										<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', MAD_BASE_TEXTDOMAIN ); ?></label>
									</p>

								<?php endif; ?>

								<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

								<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

									<div class="create-account">

										<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', MAD_BASE_TEXTDOMAIN ); ?></p>

										<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

											<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

										<?php endforeach; ?>

										<div class="clear"></div>

									</div>

								<?php endif; ?>

								<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

							<?php endif; ?>
						</div>

					</div>

					<div class="col-sm-6">

						<div class="woocommerce-shipping-fields">
							<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>

								<?php
								if ( empty( $_POST ) ) {

									$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0;
									$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

								} else {

									$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

								}
								?>

								<h5 class="form-field-title" id="ship-to-different-address">
									<label for="ship-to-different-address-checkbox" class="checkbox"><?php _e( 'Ship to a different address?', MAD_BASE_TEXTDOMAIN ); ?></label>
									<input id="ship-to-different-address-checkbox" class="input-checkbox" <?php checked( $ship_to_different_address, 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
								</h5>

								<div class="shipping_address">

									<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

									<?php foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) : ?>

										<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

									<?php endforeach; ?>

									<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

								</div>

							<?php endif; ?>

							<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

							<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

								<?php if ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() ) : ?>

									<h5 class="form-field-title"><?php _e( 'Additional Information', MAD_BASE_TEXTDOMAIN ); ?></h5>

								<?php endif; ?>

								<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

									<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

								<?php endforeach; ?>

							<?php endif; ?>

							<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
						</div>

					</div>

				</div><!--/ .row-->

			</div><!--/ .col2-set-->


			<?php $this->get_terms(); ?>


			<div id="order_review">

				<table class="shop_table">

					<thead>
						<tr>
							<th class="product-name"><?php _e( 'Product', MAD_BASE_TEXTDOMAIN ); ?></th>
							<th class="product-total"><?php _e( 'Total', MAD_BASE_TEXTDOMAIN ); ?></th>
						</tr>
					</thead>

					<tbody>

						<?php do_action( 'woocommerce_review_order_before_cart_contents' );

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								?>
								<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
									<td class="product-name">
										<div class="sub-title">
											<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
											<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
											<?php echo WC()->cart->get_item_data( $cart_item ); ?>
										</div>
									</td>
									<td class="product-total">
										<div class="cart-subtitle">
											<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
										</div>
									</td>
								</tr>
							<?php
							}
						}

						do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

					</tbody>

					<tfoot>

						<tr class="cart-subtotal">
							<th>
								<div class="sub-title">
									<?php _e( 'Cart Subtotal', MAD_BASE_TEXTDOMAIN ); ?>
								</div>
							</th>
							<td>
								<div class="cart-subtitle">
									<?php wc_cart_totals_subtotal_html(); ?>
								</div>
							</td>
						</tr>

						<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
							<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
								<th>
									<div class="sub-title">
										<?php wc_cart_totals_coupon_label( $coupon ); ?>
									</div>
								</th>
								<td>
									<div class="cart-subtitle">
										<?php wc_cart_totals_coupon_html( $coupon ); ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>

						<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

							<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

							<?php wc_cart_totals_shipping_html(); ?>

							<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

						<?php endif; ?>

						<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
							<tr class="fee">
								<th>
									<div class="sub-title"><?php echo esc_html( $fee->name ); ?></div>
								</th>
								<td>
									<div class="cart-subtitle"><?php wc_cart_totals_fee_html( $fee ); ?></div>
								</td>
							</tr>
						<?php endforeach; ?>

						<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
							<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
								<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
									<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
										<th>
											<div class="sub-title"><?php echo esc_html( $tax->label ); ?></div>
										</th>
										<td>
											<div class="cart-subtitle"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr class="tax-total">
									<th>
										<div class="sub-title">
											<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
										</div>
									</th>
									<td>
										<div class="cart-subtitle">
											<?php echo wc_price( WC()->cart->get_taxes_total() ); ?>
										</div>
									</td>
								</tr>
							<?php endif; ?>
						<?php endif; ?>

						<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
							<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
								<th>
									<div class="sub-title">
										<?php wc_cart_totals_coupon_label( $coupon ); ?>
									</div>
								</th>
								<td>
									<div class="cart-subtitle">
										<?php wc_cart_totals_coupon_html( $coupon ); ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>

						<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

						<tr class="order-total">
							<th>
								<div class="sub-title cart-total-color">
									<?php _e( 'Order Total', MAD_BASE_TEXTDOMAIN ); ?>
								</div>
							</th>
							<td>
								<div class="cart-subtitle cart-total-color">
									<?php wc_cart_totals_order_total_html(); ?>
								</div>
							</td>
						</tr>

						<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

						<tr>
							<td colspan="2">

								<?php do_action( 'woocommerce_review_order_before_payment' ); ?>

								<div id="payment">
									<?php if ( WC()->cart->needs_payment() ) : ?>
										<ul class="payment_methods methods">
											<?php
											$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
											if ( ! empty( $available_gateways ) ) {

												// Chosen Method
												if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
													$available_gateways[ WC()->session->chosen_payment_method ]->set_current();
												} elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
													$available_gateways[ get_option( 'woocommerce_default_gateway' ) ]->set_current();
												} else {
													current( $available_gateways )->set_current();
												}

												foreach ( $available_gateways as $gateway ) {
													?>
													<li class="payment_method_<?php echo $gateway->id; ?>">
														<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
														<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
														<?php
														if ( $gateway->has_fields() || $gateway->get_description() ) :
															echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
															$gateway->payment_fields();
															echo '</div>';
														endif;
														?>
													</li>
												<?php
												}
											} else {

												if ( ! WC()->customer->get_country() )
													$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', MAD_BASE_TEXTDOMAIN );
												else
													$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', MAD_BASE_TEXTDOMAIN );

												echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';

											}
											?>
										</ul>
									<?php endif; ?>

									<div class="clear"></div>

								</div><!--/ #payment-->

								<?php do_action( 'woocommerce_review_order_after_payment' ); ?>



								<div class="form-row place-order">

									<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

									<?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) {
										$terms_is_checked = apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) );
										?>
										<p class="form-row terms">
											<input type="checkbox" class="input-checkbox" name="terms" <?php checked( $terms_is_checked, true ); ?> id="terms" />
											<label for="terms" class="checkbox"><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank">terms &amp; conditions</a>', MAD_BASE_TEXTDOMAIN ), esc_url( get_permalink( wc_get_page_id( 'terms' ) ) ) ); ?></label>
										</p>
									<?php } ?>

									<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

									<?php
									$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', MAD_BASE_TEXTDOMAIN ) );
									echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt m_bottom_20" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
									?>

									<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

								</div><!--/ .place-order-->

							</td>
						</tr>

					</tfoot>
				</table>

			</div><!--/ #order_review-->

		</form><!--/ .checkout-->

		<?php
		$output = ob_get_clean();
		return $output;
	}

}