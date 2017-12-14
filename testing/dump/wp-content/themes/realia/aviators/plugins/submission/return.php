<?php

require_once '../../../../../../wp-load.php';


$post = get_post($_GET['post_id']);
$post->post_status = 'publish';

switch ($_GET['paypal']) {
  case 'paid':
    wp_update_post($post);
    $transaction_id = wp_insert_post(array(
      'post_title' => 'Transaction ' . mysql2date(get_option('date_format'), date("Y-m-d H:i:s")),
      'post_type' => 'transaction',
      'post_status' => 'publish',
    ));

    global $current_user;
    $purchase = aviators_submission_create_paypal_purchase($_GET['post_id']);
    $purchase->process_payment();

    $price = aviators_settings_get_value('submission', 'pay_per_post', 'price') + aviators_settings_get_value('submission', 'pay_per_post', 'tax');
    $formatted_price = aviators_price_format($price);

    update_post_meta($transaction_id, '_transaction_user_id', $current_user->ID);
    update_post_meta($transaction_id, '_transaction_cost', $formatted_price);
    update_post_meta($transaction_id, '_transaction_status', $_GET['paypal']);
    update_post_meta($transaction_id, '_transaction_post_id', $_GET['post_id']);
    update_post_meta($transaction_id, '_transaction_token', $_GET['token']);
    update_post_meta($transaction_id, '_transaction_payer_id', $_GET['PayerID']);
    update_post_meta($transaction_id, '_transaction_meta_fields', array(
      '_transaction_user_id',
      '_transaction_cost',
      '_transaction_status',
      '_transaction_post_id',
      '_transaction_token',
      '_transaction_payer_id',
    ));

    aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Submission has been successfully published. Thanks!', 'aviators'));
    return wp_redirect(home_url());
    break;
  default:
    aviators_flash_add_message(AVIATORS_FLASH_ERROR, __('Submission has not been paid yet.', 'aviators'));
    return wp_redirect(home_url());
    break;
}
