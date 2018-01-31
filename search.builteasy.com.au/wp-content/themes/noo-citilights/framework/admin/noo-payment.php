<?php
/**
 * Register NOO Order.
 * This file register Item and Category for NOO Order.
 *
 * @package    NOO CitiLights
 * @subpackage NOO Order
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if(!class_exists('NooPayment')) :
	class NooPayment {

		const ORDER_SLUG = 'orders';
		const ORDER_POST_TYPE = 'noo_order';
		const ORDER_META_PREFIX = '_noo_order';

		public function __construct() {
			$membership_type = NooMembership::get_membership_type();
			if( $membership_type == 'none' || $membership_type == 'free' ) {
				return;
			}

			add_action('init', array(&$this,'register_post_type'));			

			if( is_admin() ) {
				add_action( 'add_meta_boxes', array (&$this, 'remove_meta_boxes' ), 20 );
				add_action( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
				add_action( 'admin_menu', array(&$this,'settings_sub_menu') );

				add_filter('manage_edit-' . self::ORDER_POST_TYPE . '_columns', array(&$this, 'manage_edit_columns'));
				add_action('manage_posts_custom_column', array(&$this, 'manage_posts_custom_column'));
			}

			// Function for IPN stuff
			add_action('noo-paypal-ipn', array(&$this,'paypal_ipn'));
		}

		public function register_post_type() {
			$permalinks = get_option( 'noo_order_permalinks' );
			// Text for NOO Order.
			$noo_order_labels = array(
				'name' => __('Orders', 'noo'),
				'singular_name' => __('Order', 'noo'),
				'menu_name' => __('Payment', 'noo'),
				'all_items' => __('All Orders', 'noo'),
				'add_new' => __('Add Order', 'noo'),
				'add_new_item' => __('Add Order', 'noo'),
				'edit_item' => __('Edit Order', 'noo'),
				'new_item' => __('New Order', 'noo'),
				'view_item' => __('View Order', 'noo'),
				'search_items' => __('Search Order', 'noo'),
				'not_found' => __('No orders found', 'noo'),
				'not_found_in_trash' => __('No orders found in trash', 'noo'),
			);
			
			$noo_order_icon = NOO_FRAMEWORK_ADMIN_URI . '/assets/images/noo20x20.png';
			if ( floatval( get_bloginfo( 'version' ) ) >= 3.8 ) {
				$noo_order_icon = 'dashicons-cart';
			}
			

			// Options
			$noo_order_args = array(
				'labels' => $noo_order_labels,
				'public' => true,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 5,
				'menu_icon' => $noo_order_icon,
				'capability_type' => 'post',
				'hierarchical' => false,
				'supports' => array(
					'title',
					'revisions'
				),
				'has_archive' => false,
				'can_export' => false
			);
			
			register_post_type(self::ORDER_POST_TYPE, $noo_order_args);
		}

		public function remove_meta_boxes() {
			remove_meta_box( 'slugdiv', self::ORDER_POST_TYPE, 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', self::ORDER_POST_TYPE, 'normal' );
		}

		public function add_meta_boxes() {

			// Declare helper object
			$prefix = self::ORDER_META_PREFIX;
			$helper = new NOO_Meta_Boxes_Helper( $prefix, array( 'page' => self::ORDER_POST_TYPE ) );

			// Order Detail
			$meta_box = array(
				'id'           => "_meta_box_order",
				'title'        => __( 'Order Details', 'noo' ),
				'context'      => 'normal',
				'priority'     => 'core',
				// 'description'  => __( 'Details for Order ID:', 'noo' ) . ' <strong>' . get_the_ID() . '</strong>',
				'fields'       => array(
					array(
						'id' => "_order_id",
						'label' => __( 'Order ID', 'noo' ),
						'desc' => '',
						'type' => 'label',
						'std' => '<strong>' . get_the_ID() . '</strong>'
					)
				),
			);

			if( NooMembership::is_membership() ) {
				$meta_box['fields'][] = array(
						'id' => "_payment_type",
						'label' => __( 'Payment Type', 'noo' ),
						'desc' => '',
						'type' => 'membership_label',
						'std' => __( 'Membership Package', 'noo' ),
						'callback' => 'NooPayment::render_metabox_fields'
					);
				$meta_box['fields'][] = array(
						'id' => "_billing_type",
						'label' => __( 'Billing Type', 'noo' ),
						'desc' => '',
						'type' => 'select',
						'std'     => 'onetime',
						'options' => array(
							array('value'=>'onetime','label'=>__('One Time', 'noo')),
							array('value'=>'recurring','label'=>__('Recurring', 'noo')),
						)
					);
				$meta_box['fields'][] = array(
						'id' => "_item_id",
						'label' => __( 'Package', 'noo' ),
						'desc' => '',
						'type' => 'packages',
						'std'     => '',
						'callback' => 'NooAgent::render_metabox_fields'
					);
				$meta_box['fields'][] = array(
						'id' => "_total_price",
						'label' => __( 'Package Price', 'noo' ),
						'desc' => '',
						'type' => 'text',
						'std'     => ''
					);
			} elseif( get_option('noo_membership_type', 'membership') == 'submission' ) {
				$meta_box['fields'][] = array(
						'id' => "_payment_type",
						'label' => __( 'Payment Type', 'noo' ),
						'desc' => '',
						'type' => 'select',
						'std'     => 'listing',
						'options' => array(
							array('value'=>'listing','label'=>__('Publish Listing', 'noo')),
							array('value'=>'featured','label'=>__('Upgrade to Featured', 'noo')),
							array('value'=>'both','label'=>__('Publish Listing with Featured', 'noo')),
						)
					);
				$meta_box['fields'][] = array(
						'id' => "_item_id",
						'label' => __( 'Property ID', 'noo' ),
						'desc' => '',
						'type' => 'text',
						'std'     => ''
					);
				$meta_box['fields'][] = array(
						'id' => "_total_price",
						'label' => __( 'Total Price', 'noo' ),
						'desc' => '',
						'type' => 'text',
						'std'     => ''
					);
			}

			$meta_box['fields'][] = array(
						'id' => "_payment_status",
						'label' => __( 'Payment Status', 'noo' ),
						'desc' => '',
						'type' => 'select',
						'std'  => 'pending',
						'options' => array(
							array('value'=>'pending','label'=>__('Pending', 'noo')),
							array('value'=>'canceled','label'=>__('Canceled', 'noo')),
							array('value'=>'failed','label'=>__('Failed', 'noo')),
							array('value'=>'completed','label'=>__('Completed', 'noo')),
							array('value'=>'reversed','label'=>__('Reversed', 'noo')),
						)
					);
			$meta_box['fields'][] = array(
						'id' => "_purchase_date",
						'label' => __( 'Purchase Date', 'noo' ),
						'desc' => '',
						'type' => 'text',
						'std'     => ''
					);
			$meta_box['fields'][] = array(
						'id' => "_agent_id",
						'label' => __( 'Agent', 'noo' ),
						'desc' => '',
						'type' => 'agents',
						'std'     => '',
						'callback' => 'NooAgent::render_metabox_fields'
					);
			$meta_box['fields'][] = array(
						'id' => "_txn_id",
						'label' => __( 'Transaction ID', 'noo' ),
						'desc' => '',
						'type' => 'label',
						'std' => ''
					);

			$helper->add_meta_box($meta_box);
		}

		public function manage_edit_columns($columns) {
			$before = array_slice($columns, 1, 1);
			$after = array_slice($columns, 2);
			
			$order_columns = array(
				'payment_type' => __('Payment Type', 'noo'),
				'total_price' => __('Total Price', 'noo'),
				'payment_status' => __('Payment Status', 'noo'),
			);

			if( NooMembership::is_membership() ) {
				$order_columns['billing_type'] = __('Billing Type', 'noo');
			}

			$order_columns['agent'] = __('by Agent', 'noo');
			
			$columns = array_merge($before, $order_columns, $after);
			return $columns;
		}

		public function manage_posts_custom_column($column) {
			GLOBAL $post;
			$post_id = get_the_ID();
			
			if ($column == 'payment_type') {
				$payment_type = esc_attr( noo_get_post_meta($post_id, '_payment_type') );

				switch( $payment_type ) {
					case 'membership':
						_e( 'Membership Package', 'noo' );
						break;
					case 'listing':
						_e('Publish Listing', 'noo');
						break;
					case 'featured':
						_e('Upgrade to Featured', 'noo');
						break;
					case 'both':
						_e('Publish Listing with Featured', 'noo');
						break;
				}
			}

			if ($column == 'total_price') {
				$total_price = floatval( noo_get_post_meta($post_id, '_total_price') );
				echo $total_price;
			}

			if ($column == 'payment_status') {
				$payment_status = esc_attr( noo_get_post_meta($post_id, '_payment_status') );
				switch( $payment_status ) {
					case 'pending':
						_e('Pending', 'noo');
						break;
					case 'canceled':
						_e('Canceled', 'noo');
						break;
					case 'failed':
						_e('Failed', 'noo');
						break;
					case 'completed':
						_e( 'Completed', 'noo' );
						break;
					case 'reversed':
						_e('Reversed', 'noo');
						break;
				}
			}

			if ($column == 'billing_type') {
				$billing_type = esc_attr( noo_get_post_meta($post_id, '_billing_type') );
				switch( $billing_type ) {
					case 'onetime':
						_e( 'Onetime Payment', 'noo' );
						break;
					case 'recurring':
						$recurring_count = intval( noo_get_post_meta($post_id, '_recurring_count') );
						if( $recurring_count == 0 ) {
							_e('Recurring', 'noo');
						} elseif( $recurring_count == 1 ) {
							_e('Recurring 1 time', 'noo');
						} elseif( $recurring_count > 1 ) {
							echo sprintf( __('Recurring %s times', 'noo'), $recurring_count );
						}						
						break;
				}
			}

			if ($column == 'agent') {
				$agent_id = esc_attr( noo_get_post_meta($post_id, '_agent_id') );
				echo get_the_title($agent_id);
			}
		}

		public function settings_sub_menu() {
			//create new setting menu
			add_submenu_page('edit.php?post_type=noo_order', __('Payment Settings', 'noo'), __('Payment Settings', 'noo'), 'administrator', 'noo-payment-settings', array (&$this, 'payment_settings'));

			//call register settings function
			add_action( 'admin_init', array(&$this,'register_settings') );
		}

		public function register_settings() {
			//register our settings
			register_setting( 'noo_payment_settings', 'noo_payment_settings' );
		}

		public function payment_settings() {
			?>
			<div class="wrap">
			<h2><?php _e('Payment Settings', 'noo'); ?></h2>

				<form method="post" action="options.php">
					<?php settings_fields( 'noo_payment_settings' ); ?>
					<?php do_settings_sections( 'noo_payment_settings' ); ?>
					<?php
						
					?>
					<table class="form-table">
						<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
							<tr>
								<th scope="row"><label for="noo_payment_settings[merchant_account]"><?php _e( 'Select type of payment', 'noo' ); ?></label></th>
								<td id="check-type-payment">
									<input type="radio" name="noo_payment_settings[type_payment]" value="pay" <?php echo ((self::get_option('type_payment', '') != 'woo') ? 'checked' : 'checked'); ?> /> <?php _e('PayPal', 'noo') ?>
									<input type="radio" name="noo_payment_settings[type_payment]" value="woo" <?php echo ((self::get_option('type_payment', '') == 'woo') ? 'checked' : ''); ?> /> <?php _e('WooCommerce', 'noo') ?>
								</td>
							</tr>
						<?php else : ?>
							<input type="hidden" name="noo_payment_settings[type_payment]" value="pay" />
						<?php endif; ?>
						<tr valign="top" class="noo_payment_settings[merchant_account] paypal">
							<th scope="row"><label for="noo_payment_settings[merchant_account]"><?php _e( 'PayPal Merchant Account (ID or Email)', 'noo' ); ?></label></th>
							<td>
								<input name="noo_payment_settings[merchant_account]" type="text" class="regular-text code" value="<?php echo esc_attr( self::get_option('merchant_account', '') ); ?>" />
							</td>
						</tr>
						<tr valign="top" class="noo_payment_settings[enable_sandbox] paypal">
							<th scope="row"><label for="noo_payment_settings[enable_sandbox]"><?php _e( 'Enable PayPal Sandbox Testing', 'noo' ); ?></label></th>
							<td>
								<input type="hidden" name="noo_payment_settings[enable_sandbox]" value="0" />
								<input type="checkbox" name="noo_payment_settings[enable_sandbox]" <?php checked(  self::get_option('enable_sandbox', false) );?> value="1" />
							</td>
						</tr>
						<tr valign="top" class="noo_payment_settings[disable_ssl] paypal">
							<th scope="row"><label for="noo_payment_settings[disable_ssl]"><?php _e( 'Disable SSL secure connection (Not recommended)', 'noo' ); ?></label></th>
							<td><input type="checkbox" name="noo_payment_settings[disable_ssl]" <?php checked(  self::get_option('disable_ssl', false) );?> value="1" /></td>
						</tr>
						<tr valign="top" class="noo_payment_settings[notify_email] paypal">
							<th scope="row"><label for="noo_payment_settings[notify_email]"><?php _e( 'Email for sending payment notification', 'noo' ); ?></label></th>
							<td>
								<input name="noo_payment_settings[notify_email]" type="text" class="regular-text code" value="<?php echo esc_attr( self::get_option('notify_email', '') ); ?>" placeholder="youremail@email.com" />
							</td>
						</tr>
						<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
							<script>
							jQuery(document).ready(function($) {
								var default_type = $('#check-type-payment input[type=radio]:checked').val();
								if ( default_type == 'pay' ) $('.paypal').show();
								else $('.paypal').hide();
								$("#check-type-payment input[type=radio]").change(function(e){
									var type_payment = $('#check-type-payment input[type=radio]:checked').val();	
								    if(type_payment == 'pay') {
								       	$('.paypal').show();
								    } else {
								        $('.paypal').hide();
								    }
								});
							});
							</script>
						<?php endif; ?>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}

		public function paypal_ipn($POST) {
			$has_err		= false;
			$err_message	= array();

			$order_id		= intval( $POST['custom'] );
			$txn_id			= esc_attr( $POST['txn_id'] );
			$txn_type		= esc_attr( $POST['txn_type'] );
			$payment_status	= esc_attr( $POST['payment_status'] );

			$receiver_id		= esc_attr( $POST['receiver_id'] );
			$receiver_email	= esc_attr( $POST['receiver_email'] );
			$mc_gross 		= floatval( $POST['mc_gross'] );
			$mc_currency 	= esc_attr( $POST['mc_currency'] );

			if( $receiver_email != self::get_option('merchant_account') && $receiver_id != self::get_option('merchant_account')  ) {
				$has_err		= true;
				$err_message[]	= __('Different Receiver', 'noo');
			}
			if( empty($order_id) ) {
				$has_err		= true;
				$err_message[]	= __('Empty Order ID', 'noo');
			}

			$order					= array();
			$order['agent_id']		= intval( noo_get_post_meta( $order_id, '_agent_id' ) );
			if( empty( $order['agent_id'] ) ) {
				$has_err		= true;
				$err_message[]	= __('Order does not have Agent ID', 'noo');
			}
			$order['item_id']		= intval( noo_get_post_meta( $order_id, '_item_id' ) );
			if( empty( $order['item_id']) ) {
				$has_err		= true;
				$err_message[]	= __('Order does not have Item ID', 'noo');
			}
			$order['total_price']	= floatval( noo_get_post_meta( $order_id, '_total_price' ) );
			$order['currency_code']	= esc_attr( noo_get_post_meta( $order_id, '_currency_code' ) );
			if( $mc_gross != round($order['total_price'], 2) || strtoupper($mc_currency) != strtoupper($order['currency_code']) ) {
				$has_err		= true;
				$err_message[]	= __('Price or Currency does not match', 'noo');
			}
			$order['total_price'] = NooProperty::format_price( $order['total_price'], 'text' );

			$order['recurring']	= esc_attr( noo_get_post_meta( $order_id, '_billing_type' ) ) == 'recurring';
			$order['status']	= esc_attr( noo_get_post_meta( $order_id, '_payment_status' ) );
			$order_status		= '';
			if( !$order['recurring']) {
				$order_status	= $this->_payment_status( $payment_status );
			} else {
				if(preg_match( "#(subscr_payment)#i", $txn_type)) {
					$order_status	= $this->_payment_status( $payment_status );
				}
				// elseif(preg_match( "#(subscr_signup)#i", $txn_type)) {
				// 	$order_status	= "pending";
				// }
				// elseif(preg_match( "#(subscr_cancel)#i", $txn_type)) {
				// 	$order_status	= "canceled";
				// }
				// elseif(preg_match( "#(subscr_failed)#i", $txn_type)) {
				// 	$order_status	= "failed";
				// }
			}

			if( empty( $order_status ) ) {
				$has_err		= true;
				$err_message[]	= __('Unknown order status!', 'noo');
			}

			if( $has_err ) {
				// echo implode('<br/>', $err_message );
				// noo_mail( self::get_option('notify_email'), __('Error when processing Order', 'noo'), implode('<br/>', $err_message ) );
				return false;
			}

			if( $order['status'] != $order_status ) {
				update_post_meta( $order_id, '_payment_status', $order_status );

				if( $order['status'] == 'completed' ) {

					if( NooMembership::is_membership() ) {
						// Check if current membership is activated by this order
						$activation_date = noo_get_post_meta( $order['agent_id'], '_activation_date' );
						$purchase_date = noo_get_post_meta( $order_id, '_purchase_date' );
						if( $activation_date == $purchase_date ) {
							NooAgent::revoke_agent_membership( $order['agent_id'], $order['item_id'] );
						}

						if( $order['recurring'] ) {
							$recurring_count = intval( noo_get_post_meta( $order_id, '_recurring_count') );
							$recurring_count = max( 0, $recurring_count - 1 );
							update_post_meta( $order_id, '_recurring_count', $recurring_count );
						}
					} elseif( NooMembership::is_submission() ) {
						$order['payment_type'] = esc_attr( noo_get_post_meta( $order_id, '_payment_type' ) );
						NooAgent::revoke_property_status( $order['agent_id'], $order['item_id'], $order['payment_type'] );
					}
				}

				if( $order_status == 'completed' ) {
					$purchase_date = time();
					update_post_meta( $order_id, '_purchase_date', $purchase_date );
					update_post_meta( $order_id, '_txn_id', $txn_id );

					if( NooMembership::is_membership() ) {
						NooAgent::set_agent_membership( $order['agent_id'], $order['item_id'], $purchase_date );

						if( $order['recurring'] ) {
							$recurring_count = intval( noo_get_post_meta( $order_id, '_recurring_count') );
							update_post_meta( $order_id, '_recurring_count', $recurring_count + 1 );
						}

						// Email
						$admin_email = self::get_option('notify_email');
						if( empty($admin_email ) ) $admin_email = get_option('admin_email');

						$user_name = get_the_title( $order['agent_id'] );
						$user_email = noo_get_post_meta( $order['agent_id'], "_noo_agent_email");
						$package_name = get_the_title( $order['item_id'] );
						$site_name = get_option('blogname');

						// Admin email
						$message = sprintf( __("You have received a new payment for membership on %s", 'noo'), $site_name) . "<br/><br/>";
						$message .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$message .= sprintf( __("User's name: %s", 'noo'), $user_name) . "<br/><br/>";
						$message .= sprintf( __("Email address: %s", 'noo'), $user_email) . "<br/><br/>";
						$message .= sprintf( __("Package: %s", 'noo'), $package_name) . "<br/><br/>";
						$message .= sprintf( __("Amount: %s", 'noo'), $order['total_price']) . "<br/><br/>";
						$message .= sprintf( __("Transaction #: %s", 'noo'), $txn_id) . "<br/><br/>";
						$message .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$message .= __("You may review your invoice history at any time by logging in to backend.", 'noo') . "<br/><br/>";
						noo_mail($admin_email,
							sprintf(__('[%s] New Payment received for Membership purchase','noo'), $site_name),
							$message);

						// Agent email
						$message = sprintf( __("Hi %s,", 'noo'), $user_name) . "<br/><br/>";
						$message .= sprintf( __("You have paid %s for %s membership on %s.", 'noo'), $order['total_price'], $package_name, $site_name) . "<br/><br/>";
						$message .= sprintf( __("Your transaction ID is: %s,", 'noo'), $txn_id) . "<br/><br/>";
						$message .= __("Thank you and enjoy listing,", 'noo') . "<br/><br/>";
						noo_mail($user_email,
							sprintf(__('[%s] Payment for membership successfully processed','noo'), $site_name),
							$message);
					} elseif( NooMembership::is_submission() ) {
						$order['payment_type'] = esc_attr( noo_get_post_meta( $order_id, '_payment_type' ) );
						NooAgent::set_property_status( $order['agent_id'], $order['item_id'], $order['payment_type'] );

						// Email
						$admin_email = self::get_option('notify_email');
						if( empty($admin_email ) ) $admin_email = get_option('admin_email');
						$property_link = get_permalink( $order['item_id'] );
						$property_admin_link = admin_url( 'post.php?post=' . $order['item_id'] ) . '&action=edit';

						$user_name = get_the_title( $order['agent_id'] );
						$user_email = noo_get_post_meta( $order['agent_id'], "_noo_agent_email");
						$property_title = get_the_title( $order['item_id'] );
						$site_name = get_option('blogname');

						// Admin email
						$message = '';
						$title = '';
						if( $order['payment_type'] == 'listing' ) {
							$message .= sprintf( __("You have received a new payment for Paid Submission on %s", 'noo'), $site_name) . "<br/><br/>";
							$title .= sprintf(__('[%s] New Payment received for Paid Property Submission','noo'), $site_name);

						} elseif( $order['payment_type'] == 'featured' ) {
							$message .= sprintf( __("You have received a new payment for Featured property on %s", 'noo'), $site_name) . "<br/><br/>";
							$title .= sprintf(__('[%s] New Payment received for Featured property','noo'), $site_name);

						} elseif( $order['payment_type'] == 'both' ) {
							$message .= sprintf( __("You have received a new payment for Paid Submission and Featured property on %s", 'noo'), $site_name) . "<br/><br/>";
							$title .= sprintf(__('[%s] New Payment received for Paid Submission and Featured property','noo'), $site_name);
						}
						$message .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$message .= sprintf( __("User's name: %s", 'noo'), $user_name) . "<br/><br/>";
						$message .= sprintf( __("Email address: %s", 'noo'), $user_email) . "<br/><br/>";
						$message .= sprintf( __("Amount: %s", 'noo'), $order['total_price']) . "<br/><br/>";
						$message .= sprintf( __("Transaction #: %s", 'noo'), $txn_id) . "<br/><br/>";
						$message .= sprintf( __("Property link: %s", 'noo'), $property_admin_link) . "<br/><br/>";
						$message .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$message .= __("You may review your invoice history at any time by logging in to backend.", 'noo') . "<br/><br/>";
						noo_mail($admin_email,
							$title,
							$message);

						// Agent email
						$message = sprintf( __("Hi %s,", 'noo'), $user_name) . "<br/><br/>";
						$message .= sprintf( __("You have paid %s for %s property on %s. This is the link to the listing: %s", 'noo'), $order['total_price'],$property_title, $site_name, $property_link) . "<br/><br/>";
						$message .= sprintf( __("Your transaction ID is: %s,", 'noo'), $txn_id) . "<br/><br/>";
						$message .= __("Thank you and best regards,", 'noo') . "<br/><br/>";
						noo_mail($user_email,
							sprintf(__('[%s] Payment for Property listing successfully processed','noo'), $site_name),
							$message);
					}
				}
			} else {
				if( $order['recurring'] && $order_status == 'completed' ) {
					$purchase_date = time();
					$recurring_count = intval( noo_get_post_meta( $order_id, '_recurring_count') );
					update_post_meta( $order_id, '_purchase_date', $purchase_date );
					update_post_meta( $order_id, '_txn_id', $txn_id );
					update_post_meta( $order_id, '_recurring_count', $recurring_count + 1 );

					if( NooMembership::is_membership() ) {
						NooAgent::set_agent_membership( $order['agent_id'], $order['item_id'], $purchase_date );

						// Email
						$admin_email = self::get_option('notify_email');
						if( empty($admin_email ) ) $admin_email = get_option('admin_email');

						$user_name = get_the_title( $order['agent_id'] );
						$user_email = noo_get_post_meta( $order['agent_id'], "_noo_agent_email");
						$package_name = get_the_title( $order['item_id'] );
						$site_name = get_option('blogname');

						// Admin email
						$message = sprintf( __("You have received a new recurring payment for membership on %s", 'noo'), $site_name) . "<br/><br/>";
						$message .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$message .= sprintf( __("User's name: %s", 'noo'), $user_name) . "<br/><br/>";
						$message .= sprintf( __("Email address: %s", 'noo'), $user_email) . "<br/><br/>";
						$message .= sprintf( __("Package: %s", 'noo'), $package_name) . "<br/><br/>";
						$message .= sprintf( __("Amount: %s", 'noo'), $order['total_price']) . "<br/><br/>";
						$message .= sprintf( __("Transaction #: %s", 'noo'), $txn_id) . "<br/><br/>";
						$message .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$message .= __("You may review your invoice history at any time by logging in to backend.", 'noo') . "<br/><br/>";
						noo_mail($admin_email,
							sprintf(__('[%s] New Payment received for Membership purchase','noo'), $site_name),
							$message);

						// Agent email
						$message = sprintf( __("Hi %s,", 'noo'), $user_name) . "<br/><br/>";
						$message .= sprintf( __("You have paid %s for %s membership on %s.", 'noo'), $order['total_price'], $package_name, $site_name) . "<br/><br/>";
						$message .= sprintf( __("Your transaction ID is: %s,", 'noo'), $txn_id) . "<br/><br/>";
						$message .= __("Thank you and enjoy listing,", 'noo') . "<br/><br/>";
						noo_mail($user_email,
							sprintf(__('[%s] Payment for membership successfully processed','noo'), $site_name),
							$message);
					}
				}
			}
		}

		private function _payment_status( $payment_status ) {
			if(preg_match ("#(canceled_reversal|completed)#i", $payment_status)) {
				return "completed";
			}
			elseif(preg_match ("#(created|processed|pending)#i", $payment_status)) {
				return "pending";
			}
			elseif(preg_match ("#(canceled|denied)#i", $payment_status)) {
				return "canceled";
			}
			elseif(preg_match ("#(failed|expired|voided)#i", $payment_status)) {
				return "failed";
			}
			elseif(preg_match ("#(refunded|reversed)#i", $payment_status)) {
				return "reversed";
			}

			return '';
		}

		public static function get_option( $id, $default = null ) {
			$options = get_option('noo_payment_settings');
			if (isset($options[$id])) {
				return $options[$id];
			}
			return $default;
		}

		public static function render_metabox_fields( $post, $id, $type, $meta, $std = null, $field = null ) {
			switch( $type ) {
				case 'membership_label':
					$value = empty( $meta ) && ( $std != null && $std != '' ) ? $std : $meta;
					$value == 'membership' ? __('Membership Package', 'noo' ) : '';
					echo '<label id='.$id.' >'. $value . '</label>';
					break;
			}
		}

		public static function create_new_order( $payment_type = '', $billing_type = '', $item_id = '', $total_price = '', $agent_id = '', $title = '' ) {
			if( empty( $payment_type ) || empty( $item_id ) || empty( $total_price ) || empty( $agent_id ) ) {
				return 0;
			}

			if( $payment_type == 'membership' && empty( $billing_type ) ) {
				return 0;
			}

			if( !is_numeric( $item_id ) || !is_numeric( $agent_id ) ) {
				return 0;
			}

			$total_price = floatval($total_price);

			if( $total_price == 0 ) {
				return 0;
			}

			$order = array(
				'post_title' => $title,
				'post_type' => NooPayment::ORDER_POST_TYPE,
				'post_status' => 'publish'
			);
			$order_ID = wp_insert_post( $order );

			if( !$order_ID ) {
				return 0;
			}

			update_post_meta( $order_ID, '_payment_status', 'pending' );
			update_post_meta( $order_ID, '_currency_code', NooProperty::get_general_option('currency') );
			update_post_meta( $order_ID, '_payment_type', $payment_type );
			if( !empty( $billing_type )) {
				update_post_meta( $order_ID, '_billing_type', $billing_type );
			}
			update_post_meta( $order_ID, '_item_id', intval( $item_id ) );
			update_post_meta( $order_ID, '_total_price', $total_price );
			update_post_meta( $order_ID, '_agent_id', intval( $agent_id ) );

			return $order_ID;
		}

		/**
		 * Format the price with a currency symbol.
		 * @param float $price
		 * @return string
		 */
		public static function format_price($price, $html = true){
			$type_payment = self::get_payment_type();
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $type_payment == 'woo' ) :
				
				$decimal_separator  = wc_get_price_decimal_separator();
				$thousand_separator = wc_get_price_thousand_separator();
				$decimals           = wc_get_price_decimals();
				$price_format       = get_woocommerce_price_format();

				$price           = apply_filters( 'raw_woocommerce_price', $price );
				$price           = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

				if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
					$price = wc_trim_zeros( $price );
				}

				$formatted_price = sprintf( $price_format, get_woocommerce_currency_symbol( '' ), $price );

				if('text' === $html) {
					return $formatted_price;
				}

				if('number' === $html) {
					return $price;
				}

				return $formatted_price = '<span class="amount">' . $formatted_price . '</span>';
			else :
				return NooProperty::format_price($price, $html);

			endif;
		}

		public static function get_payment_type() {
			$noo_payment_settings = get_option('noo_payment_settings', '');
			return ( isset( $noo_payment_settings['type_payment'] ) ? $noo_payment_settings['type_payment'] : '' );
		}
	}
endif;

new NooPayment();
