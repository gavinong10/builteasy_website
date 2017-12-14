<?php

define('WP_USE_THEMES', FALSE);


require_once ABSPATH . '/wp-includes/class-phpmailer.php';


if (is_array($_POST) && !empty($_POST['post_id'])) {

    if(isset($_POST['full_name']) && !empty($_POST['full_name'])) {
        // honeypot!
        return;
    }

    $agents = get_post_meta($_POST['post_id'], '_property_agents', TRUE);
    $post = get_post($_POST['post_id']);

    $user_data = get_userdata($post->post_author);
    $author_email = $user_data->data->user_email;

    if (is_array($agents) || !empty($author_email)) {
        $message = '';

        if (!aviators_settings_get_value('properties', 'fields', 'hide_link')) {
            $permalink = get_permalink($_POST['post_id']);

            $message .= __('Link to property', 'aviators') . ': ' . $permalink . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_name')) {
            $message .= __('Name', 'aviators') . ': ' . $_POST['name'] . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_phone')) {
            $message .= __('Phone', 'aviators') . ': ' . $_POST['phone'] . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_date')) {
            $message .= __('Date', 'aviators') . ': ' . $_POST['date'] . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_date_from')) {
            $message .= __('Date From', 'aviators') . ': ' . $_POST['date_from'] . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_date_to')) {
            $message .= __('Date To', 'aviators') . ': ' . $_POST['date_to'] . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_email')) {
            $message .= __('E-mail', 'aviators') . ': ' . $_POST['email'] . "\n\n";
        }

        if (!aviators_settings_get_value('properties', 'fields', 'hide_message')) {
            $message .= __('Message', 'aviators') . ': ' . $_POST['message'] . "\n\n";
        }

        $message .= __('Location', 'aviators') . ': ' . $_SERVER['HTTP_REFERER'];

        // define headers

        $headers = 'From: ' . aviators_settings_get_value('properties', 'enquire_form', 'name') . ' <' . aviators_settings_get_value('properties', 'enquire_form', 'email') . '>' . "\r\n";
        // if we want to send to author
        if(aviators_settings_get_value('properties', 'enquire_form_receive', 'author')) {
            if (!empty($author_email)) {
                $is_sent = wp_mail($author_email, aviators_settings_get_value('properties', 'enquire_form', 'subject'), $message, $headers);
            }
        }

        // send to agents
        if (aviators_settings_get_value('properties', 'enquire_form_receive', 'agent') && is_array($agents)) {
            foreach ($agents as $agent_id) {
                $email = get_post_meta($agent_id, '_agent_email', TRUE);
                $is_sent = wp_mail($email, aviators_settings_get_value('properties', 'enquire_form', 'subject'), $message, $headers);
            }
        }

        // send to admin
        if(aviators_settings_get_value('properties', 'enquire_form_receive', 'admin')) {
            $is_sent = wp_mail(get_option( 'admin_email' ), aviators_settings_get_value('properties', 'enquire_form', 'subject'), $message, $headers);
        }

        // send to custom address
        $send_to_custom = aviators_settings_get_value('properties', 'enquire_form_receive', 'custom');
        if(!empty($send_to_custom)) {
            $emails = explode(',', $send_to_custom);
            foreach($emails as $email) {
                $is_sent = wp_mail(trim($email), aviators_settings_get_value('properties', 'enquire_form', 'subject'), $message, $headers);
            }
        }

        if ($is_sent) {
            aviators_flash_add_message(AVIATORS_FLASH_SUCCESS, __('Your enquire was successfully sent.', 'aviators'));
        }
        else {
            aviators_flash_add_message(AVIATORS_FLASH_ERROR, __('An error occured. Your enquire can not be sent.', 'aviators'));
        }
    }
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    else {
        header('Location: ' . site_url());
    }
}